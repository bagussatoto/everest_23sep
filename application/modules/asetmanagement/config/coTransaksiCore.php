<?php

/**
 * Created by PhpStorm.
 * User: chepy
 * Date: 10/23/2021
 * Time: 13:16 PM
 */

$config["coTransaksiCore"] = array(

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

                "dpp_pengganti_item"=>"harga_disc*dpp_persen_pengganti/100)",

                "ppn" => "(ppn_persen_dipakai*dpp_pengganti_item)/100",

//                "ppn" => "(ppnPersen*harga_disc)/100",

                "hpp_nppn" => "harga_disc+ppn+other",

                "nett" => "hpp_nppn",
//                "nett" => "harga_disc",
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
                        "nilai_persediaan" => "harga_other",
                        "dpp_vat" => ".0",
//                        "ppnFactor" =>"0",
                    ),
                ),
                "paymentMethod" => array(
                    "credit" => array(
                        "nilai_credit" => "harga_other",
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
            "tagihan" => "harga_other-discount-dp",
//            "dpp_nilai_pengganti" => "dpp_pengganti_item*(ppnFactor/12)",
            "dpp_pengganti" => "dpp_pengganti_item*(ppnFactor/12)",
        ),
        "preProcessor" => array(
            "423" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".ppn in",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => ".0",//sengaja di nol kan karena ppn masuk saat baya bayar biar satu pola
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
                            ),
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
                            "state" => ".active",
                            "jenis" => ".piutang pembelian",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "tagihan-nilai_dipakai_ppn_in",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
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
        "components" => array(
            /*
             * notes
             * a.rekening aktiva tetap di gser ke rekening aktivanya, misal kendaraan, mesin produksi, bangunan .
             * untuk aktiva tetap hanya sebagai index di pelaporan neraca,
             * supaya level rekening aktiva(a) sama dengan akumulasi penyusutannya
             * 25/10 2022 obrolan terakhir di bo m1
             */
            "423" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            // "1020070010" => "nilai_persediaan",//aktiva tetap
                            "{pihakMainID_coa}" => "nilai_persediaan",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll
                            "2010030" => "nilai_tambah_piutang_pembelian",//hutang aktiva tetap
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
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
                            // "1020070010" => "nilai_persediaan",//aktiva tetap
                            "{pihakMainID_coa}" => "nilai_persediaan",//digeser ke rekening pembelian
                            "2010030" => "nilai_tambah_piutang_pembelian",//hutang aktiva tetap
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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

                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010030" => "nilai_tambah_piutang_pembelian",//hutang aktiva tetap
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
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
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

                    array(
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(

                            /*
                             * 22/10/2022 obrolan terakhir by zoom
                             * aktiva tetap digeser ke kendaraan,peralatan kantor,mesin/mesin produksi suapya satu level dengan akumulasi penyusutannta
                             */
                            // "1020" => "harga_dipakai",//
                            "{pihakMainID_coa}" => "harga_dipakai",//aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID_coa",// diisi co code
                            "extern_nama" => "pihakMainID_coa_name",// diisi nama bank
                            "produk_nilai" => "harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            // "1020" => "harga_dipakai",//aktiva tetap
                            "{pihakMainID_coa}" => "harga_dipakai",//kendaraan, mesin,mesin produksi,bangunan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "note" => "note",
                            "produk_nilai" => "harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //region sini harus ditambahi locker value

//                    array(
//                        "comName" => "LockerStockSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".supplies",
//                            "state" => ".active",
//                            "jumlah" => "qty",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "satuan" => "satuan",
//                            "transaksi_id" => ".0",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
////                            "gudang_id" => "gudang2ID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
            ),
            "111" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040050" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "2010030" => "-selisih_ppn_realisasi",//hutang aktiva tetap
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
                            "1010040050" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
                            "2010030" => "-selisih_ppn_realisasi",//hutang aktiva tetap
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010030" => "-selisih_ppn_realisasi",//hutang aktiva tetap
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
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-selisih_ppn_realisasi",//ppn in belum ada faktur
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
                    //endregion

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010040050" => "-ppn_realisasi",////ppn in belum ada faktur
                            "1010040060" => "ppn_realisasi",//ppn in realisasi
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
                            "1010040050" => "-ppn_realisasi",////ppn in belum ada faktur
                            "1010040060" => "ppn_realisasi",//ppn in realisasi
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
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn_realisasi",////ppn in belum ada faktur
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
                "detail" => array(),
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
    ),//done

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
        "components" => array(
            "422" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga",//aktiva tetap
                            "1020070010" => "-harga",//aktiva belum ditempatkan
//                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                            "ppn in" => "nilai_tambah_ppn_in",
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
                            "{pihakMainID_coa}" => "harga",//aktiva tetap
                            "1020070010" => "-harga",//aktiva belum ditempatkan
//                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                            "ppn in" => "nilai_tambah_ppn_in",
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
                        "comName" => "RekeningPembantuAktivaBelumDitempatkan",
                        "loop" => array(
                            "1020070010" => "-harga",//aktiva belum ditempatkan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID",// diisi id bank
                            "extern_nama" => "pihakMainName",// diisi nama bank
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga",//aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakMainID",// diisi id bank
                            "extern_nama" => "pihakMainName",// diisi nama bank
                            "produk_nilai" => "harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga_dipakai",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
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
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    //region sini harus ditambahi locker value
                ),
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
        "preProcessor" => array(
            "2483r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "LockerValueAktivaItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".aktiva",
                            "produk_id" => "id",
                            "nama" => "name",
                            "nilai" => "harga",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
//                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
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
        "components" => array(
            "2483r" => array(
                "master" => array(),
                "detail" => array(),
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
                            "nilai" => "-nilai_dipakai_aktiva",
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
                            "nilai" => "nilai_dipakai_aktiva",
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
    ),//done

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
        "preProcessor" => array(
            "2485" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "RekeningValueDetailAkum",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "pihakName",
                            //                            "nilai" => "creditAmount+nilai_entry", // nilai pembayaran total
                            "nilai" => "harga", // nilai pembayaran total
                            "jenis" => "pihakMainAkumID_coa",//hutang biaya
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
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
        "components" => array(
            "2485" => array(
                "master" => array(

                    //jurnal pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060020" => "harga_disc-nilai_dipakai_1020010020_",//piutang aktiva tetap cabang
                            "{pihakMainID_coa}" => "-harga_disc",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll
                            "{pihakMainAkumID_coa}" => "nilai_dipakai_1020010020_",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll

                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010060020" => "harga_disc-nilai_dipakai_1020010020_",//piutang aktiva tetap cabang
                            "{pihakMainID_coa}" => "-harga_disc",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll
                            "{pihakMainAkumID_coa}" => "nilai_dipakai_1020010020_",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakMainID_coa}" => "-harga_disc",//aktiva tetap -> di geser ke coa kendaraan,mesin produksi,dll
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "pihakMainID_coa",// diisi coa code
                            "extern_nama" => "pihakMainID_coa_name",// diisi coa nama
                            "produk_nilai" => "-harga_disc",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060020" => "harga_disc-nilai_dipakai_1020010020_",//piutang aktiva tetap cabang
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "cabang2_id" => "pihakID",
                            "cabang2_nama" => "pihakName",
                            "extern_id" => "cabangID",
                            "extern_nama" => "cabangName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal kedua untuk auto distribusi kecabang

                    //jurnal cabang

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga_disc",//aktiva tetap
                            "2040030" => "harga_disc-nilai_dipakai_1020010020_",//hutang aktiva tetap(AT)//hutang aktiva tetap pada dc
                            "{pihakMainAkumID_coa}" => "-nilai_dipakai_1020010020_",//akum penyu
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga_disc",//aktiva tetap
                            "2040030" => "harga_disc-nilai_dipakai_1020010020_",//hutang aktiva tetap(AT)//hutang aktiva tetap pada dc
                            "{pihakMainAkumID_coa}" => "-nilai_dipakai_1020010020_",//hutang aktiva tetap(AT)//hutang aktiva tetap pada dc
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga_disc",//aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "extern_id" => "pihakMainID_coa",// diisi id bank
                            "extern_nama" => "pihakMainID_coa_name",// diisi nama bank
                            // "extern_id" => "pihakMainID",// diisi id bank
                            // "extern_nama" => "pihakMainName",// diisi nama bank
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040030" => "harga_disc-nilai_dipakai_1020010020_",//hutang aktiva tetap(AT)//hutang aktiva tetap pada dc
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "cabang2ID",
                            "extern_nama" => "cabang2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                ),

                ),
                "detail" => array(
                    //detil pusat
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "-sub_harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "sub_harga_disc",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //akum penyu dipindah

                    array(
                        "comName" => "{comRekName_1_child_coa}",
                        "loop" => array(
                            "{pihakMainAkumID_coa}" => "nilai_dipakai_1020010020_",
                        ),
                        "static" => array(
                            "cabang_id" => "cabang2ID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "nilai_dipakai_1020010020_",
                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //cabang
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            "{pihakMainID_coa}" => "harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //akum penyu masuk

                    array(
                        "comName" => "{comRekName_1_child_coa}",
                        "loop" => array(
                            "{pihakMainAkumID_coa}" => "-nilai_dipakai_1020010020_",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "nilai_dipakai_1020010020_",
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
                            "nilai" => "-nilai_dipakai_aktiva",
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
                            "nilai" => "nilai_dipakai_aktiva",
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
                            "state" => ".distribute",
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

                    //clone seting depresiasi jika sudah diseting oleh pusat ada/ cabang tidak perlu seting ulang
                    array(
                        "comName" => "cloneSetupDepresiasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "gudang_id" => "cabang2ID",
                            "cabang2_id" => "placeID",
                            "gudang2_id" => "cabangID",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "jenis" => ".assets",
//                            "nilai" => "-nilai_dipakai_aktiva",
////                            "transaksi_id" => "transaksi_id",
//                            "transaksi_id" => "masterID",
//                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
        ),
    ),//done

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
                "produk_jenis" => "aktiva",
            ),
        ),
        "components" => array(
            "8786" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "harga",//penyusutan xx
                            "{rekAkumPenyu_coa}" => "-harga",//akum penyu xx
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
                            "{pihakMainChild_coa}" => "harga",
                            "{rekAkumPenyu_coa}" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal kedua pindah penyusutan ke biaya

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{rekName_2_coa}" => "harga",//ini biaya umum/prod/usaha
                            "{pihakMainChild_coa}" => "-harga",// penyu xx
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
                            "{rekName_2_coa}" => "harga",// ini biaya umum/prod/usaha
                            "{pihakMainChild_coa}" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // jurnal biaya vs efisiensi
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "{rekName3ID_coa}" => "harga",//ini biaya umum/prod/usaha
//                            "{rekName_2_coa}" => "harga",// penyu xx
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
//                            "{rekName3ID_coa}" => "harga",// ini biaya umum/prod/usaha
//                            "{rekName_2_coa}" => "harga",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // jurnal biaya vs efisiensi
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "{rekName_2_coa}" => "-harga",//ini biaya umum/prod/usaha
//                            "efisiensi biaya" => "harga",// penyu xx
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
//                            "{rekName_2_coa}" => "-harga",// ini biaya umum/prod/usaha
//                            "efisiensi biaya" => "harga",// penyu xx
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
//                        "loop" => array(
//                            "efisiensi biaya" => "harga",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "costID",
//                            "extern_nama" => "costName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(

                    array(
                        "comName" => "{comRekName_1_child_coa}", // RekeningPembantuAkumPenyusutanBangunan/Kendaraan/peraltankantor
                        "loop" => array(
                            "{rekAkum_child_coa}" => "-harga", // coa akum penyu
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "{comRekName_2_child_coa}", //RekeningPembantuBiayaUmum/BiayaUsaha || khusus produksi ke
                        "loop" => array(
                            "{rekName_2_child_coa}" => "harga",//ini relative ikut jenis biaya comName
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "extern_id",// item biaya xxx
                            "extern_nama" => "extern_nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //ini in penyusutan
                    array(
                        "comName" => "RekeningPembantuDepresiasi",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "harga",// penyusutan xxx
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",//ini item aktiva
                            "extern_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //out penyusutan ke biaya xxx
                    array(
                        "comName" => "RekeningPembantuDepresiasi",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "-harga",// penyusutan xxx dikeluarkan dari rekening penyusutan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_nilai" => "-harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

//                    array(
//                        "comName" => "{comRekName_3_child}",
//                        "loop" => array(
//                            "{pihakMainChild}" => "-harga",//tembak dulu
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "rekName3IDChild",
//                            "extern_nama" => "rekName_3_child",
//                            "produk_nilai" => "-harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "currentID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
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
            "master_dependent" => array(
                "cabangID" => array(
                    "25" => array(
                        "harga_efisiensi" => "harga",
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
        "components" => array(
            "8787" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "harga",//penyusutan xx
                            "{rekAkumPenyu_coa}" => "-harga",//akum penyu xx
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
                            "{pihakMainChild_coa}" => "harga",
                            "{rekAkumPenyu_coa}" => "-harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //jurnal kedua pindah penyusutan ke biaya, untuk cabang produksi pindah ke hpp
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{rekName_2_coa}" => "harga",//ini quality, direct labor, delivery cost rombaong hpp produksi
                            "{pihakMainChild_coa}" => "-harga",// penyusutan xx
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
                            "{rekName_2_coa}" => "harga",// ini quality, direct labor, delivery cost
                            "{pihakMainChild_coa}" => "-harga",// penyusutan xx
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // jurnal biaya vs efisiensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            // "{rekName3ID_coa}" => "harga",// efisiensi
                            // "{rekName_2_coa}" => "-harga",// ini quality, direct labor, delivery cost
                            "{rekEfisiensi_coa}" => "-harga_efisiensi",// efisiensi
                            "{rekName_2_coa}" => "-harga_efisiensi",// ini quality, direct labor, delivery cost
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
                            // "{rekName3ID_coa}" => "harga",// efisiensi
                            // "{rekName_2_coa}" => "-harga",// ini quality, direct labor, delivery cost
                            "{rekEfisiensi_coa}" => "-harga_efisiensi",// efisiensi
                            "{rekName_2_coa}" => "-harga_efisiensi",// ini quality, direct labor, delivery cost
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
                        "comName" => "RekeningPembantuEfisiensiBiayaMain",
                        "loop" => array(
                            "{rekEfisiensi_coa}" => "-harga_efisiensi",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "efisensi_child_id",
                            "extern_nama" => "rekName_3_coa",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(

                    array(
                        "comName" => "{comRekName_1_child_coa}", // RekeningPembantuAkumPenyusutanBangunan
                        "loop" => array(
                            "{rekAkum_child_coa}" => "-harga", // coa akum penyu
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //ini in penyusutan
                    array(
                        "comName" => "RekeningPembantuDepresiasi",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "harga",// penyusutan xxx
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //out penyusutan ke biaya xxx
                    array(
                        "comName" => "RekeningPembantuDepresiasi",
                        "loop" => array(
                            "{pihakMainChild_coa}" => "-harga",// penyusutan xxx dikeluarkan dari rekening penyusutan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_nilai" => "-harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //pembantu biaya produksi(quality,derectlabor dll)masuk
                    array(
                        "comName" => "{comRekName_2_child_coa}", //RekeningPembantuBiayaUmum/BiayaUsaha || khusus produksi ke
                        "loop" => array(
                            "{rekName_2_child_coa}" => "harga", //ini relative ikut jenis biaya comName
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "extern_id",
                            "extern_nama" => "extern_nama",
                            "produk_nilai" => "harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    //biaya keluar
//                    array(
//                        "comName" => "{comRekName_2_child_coa}", //RekeningPembantuBiayaUmum/BiayaUsaha || khusus produksi ke
//                        "loop" => array(
//                            "{rekName_2_child_coa}" => "-harga", //ini relative ikut jenis biaya comName
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "extern_id" => "extern_id",
//                            "extern_nama" => "extern_nama",
//                            "produk_nilai" => "harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "transaksi_id",
//                            "cek_cabang"=>".cek_cabang",//untuk skip supaya tidak jalan walaupun gerbang ada isinya hanya jalan untuk solo
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//
//                    //pembantu efisiensi
//                    array(
//                        "comName" => "RekeningPembantuEfisiensiBiaya",//item
//                        "loop" => array(
//                            "{rekEfisiensi_coa}" => "-harga",//tembak dulu
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "efisensi_child2_id",
//                            "extern_nama" => "efisensi_child2_nama",
//                            "extern2_id" => "efisensi_child_id",
//                            "extern2_nama" => "efisensi_child_nama",
//                            "produk_nilai" => "harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "currentID",
//                            "cek_cabang"=>".cek_cabang",//untuk skip supaya tidak jalan walaupun gerbang ada isinya
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                ),
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
                            "state" => ".hold",
                            "jenis" => ".depresiasi",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "nilai" => "-harga",
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
                "produk_jenis" => "sewa",
            ),
        ),
        "components" => array(
            "8788" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1030010" => "-harga", //011001=sewa dibayar dimuka
                            "{rekName_2_coa}" => "harga", //060400016 biaya sewa belum ditempatkan
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
                            "1030010" => "-harga", //011001=sewa dibayar dimuka
                            "{rekName_2_coa}" => "harga", //060400016 biaya sewa belum ditempatkan
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
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{rekName_2_coa}" => "-harga", //060400016 biaya sewa belum ditempatkan
                            "{rekName_3_coa}" => "harga", // hpp produksi 050204 (untuk solo)   || 060200024/060300024 = beban usaha/umum (untuk selain solo)
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
                            "{rekName_2_coa}" => "-harga", //060400016 biaya sewa belum ditempatkan
                            "{rekName_3_coa}" => "harga", // hpp produksi 050204 (untuk solo)    || 060200024/060300024 = beban usaha/umum (untuk selain solo)
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // jurnal #3 hanya untuk produksi (solo)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "{rekName_4_coa}" => "-harga", // hpp produksi 050204 (untuk solo)  || selain solo tidak ada
                            "{rekName_5_coa}" => "harga", // efisiansi 030201 > quality 03020100003 (untuk solo)  || selain solo tidak ada
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
                            "{rekName_4_coa}" => "-harga", // hpp produksi 0502 (untuk solo)   || selain solo tidak ada
                            "{rekName_5_coa}" => "harga", // efisiansi 030201 > quality 03020100003 (untuk solo)  || selain solo tidak ada
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
                        "comName" => "RekeningPembantuSewa",
                        "loop" => array(
                            "1030010" => "-harga",//tembak dulu //011001=sewa dibayar dimuka
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_nilai" => "-harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //ini in ke biaya
//                    array(
//                        "comName" => "RekeningPembantuSewa",
//                        "loop" => array(
//                            "{rekName_1_child_coa}" => "-harga", // semua lewat sini dulu || 060400016 (beban belum ditempatkan)
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_nilai" => "-harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "currentID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    //out biaya
//                    array(
//                        "comName" => "RekeningPembantuSewa",
//                        "loop" => array(
//                            "{rekName_1_child_coa}" => "-harga",  // semua lewat sini dulu || 060400016 (beban belum ditempatkan)
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_nilai" => "-harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "currentID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    //in ke hpp produksi / biayaumum/biaya usaha
                    array(
                        "comName" => "{comRekName_2_child_coa}",//solo RekeningPembantuBiayaKomposisiProduksi | selain itu RekeningPembantuBiayaUmum/BiayaUsaha
                        "loop" => array(
                            "{rekName_2_child_coa}" => "-harga",//050204 quality untuk solo || selain itu (sewa) biaya usaha / biaya umum
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "rekName5ID_coa",
                            "extern_nama" => "rekName5Name_coa",
                            "extern2_id" => "rekName5ID_coa",
                            "extern2_nama" => "rekName5Name_coa",
                            "produk_nilai" => "-harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //out dr hpp produksi / biayaumum/biaya usaha
//                    array(
//                        "comName" => "{comRekName_2_child_coa}",//solo RekeningPembantuBiayaKomposisiProduksi | selain itu tidak ada
//                        "loop" => array(
//                            "{rekName_2_child_coa}" => "-sub_harga_rev",//050204 quality untuk solo || selain itu (sewa) biaya usaha / biaya umum
//                        ),
//                        "static" => array(
//                            "cabang_id" => "cabangID",
//                            "cabang_nama" => "cabangName",
//                            "extern_id" => "rekName2IDChild",
//                            "extern_nama" => "rekName_2_child",
//                            "produk_nilai" => "-sub_harga",
//                            "produk_qty" => ".1",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "transaksi_id" => "currentID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // pembantu ke efisiensi biaya
                    // dimatikan dulu karena relative nya error ( chepy jumat, 24 june 2022 )
                    // jika di paksa nyala, akan tidak bisa di approve transaksi yang telah di bikin oleh CLI auto amortisasi

                    array(
                        "comName" => "{comRekName_5_coa}", // harus relatif == RekeningPembantuEfisiensiBiaya solo saja selain itu tidak ada
                        "loopRequire" => true, // harus relatif == RekeningPembantuEfisiensiBiaya solo saja selain itu tidak ada
                        "loop" => array(
                            "{rekName_5_coa}" => "sub_harga",//solo quality 03020100003 | selain itu tidak ada
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "pihakMainID_coa",
                            "extern_nama" => "pihakMainName_coa",
                            "extern2_id" => "rekName5ID_coa",
                            "extern2_nama" => "rekName5Name_coa",
                            "produk_nilai" => "sub_harga",
                            "produk_qty" => ".1",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                            "cek_cabang" => ".cek_cabang",//untuk skip supaya tidak jalan walaupun gerbang ada isinya
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
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
//                "ppn" => "nett1*(ppn_faktor/100)",
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
                        "nilai_ppn" => "new_net2*(ppnFactor/100)",
                        "nilai_persediaan" => "harga_disc",
                        "ppn_faktor" => "ppnFactor",
                        "ppn_cek"=>".1",
                    ),
                    "non_pk" => array(
                        "nilai_ppn" => ".0",
                        "nilai_persediaan" => "nett",
                        "ppn_faktor" => ".0",
                        "ppn_cek"=>".0",
                    ),
                ),
                "txt_rugilaba" => array(
                    "keuntungan" => array(
                        "rugiLabaLainRekName" => ".7010150",//laba lain lain
                        "rugiLabaLain" => "nilai_final_rugilaba",
                    ),
                    "kerugian" => array(
                        "rugiLabaLainRekName" => ".7010150",//rugi lain lain
                        "rugiLabaLain" => "-nilai_final_rugilaba",
                    ),
                ),
                "pihakMainName" => array(
                    "kendaraan" => array(
                        "pihakMainRekName" => ".1020010010",//kendaraan
                        "pihakMainAkumDetails" => ".1020010020",//akum penyu kendaraan
                    ),
                    "peralatan kantor" => array(
                        "pihakMainRekName" => ".1020020010",//aperalatan kantor
                        "pihakMainAkumDetails" => ".1020020020",//akum penyu peralatan kantor
                    ),
                    "mesin produksi" => array(
                        "pihakMainRekName" => ".1020040010",//mesin produksi
                        "pihakMainAkumDetails" => ".1020040020",//akum penyu mesin produksi
                    ),
                    "peralatan produksi" => array(
                        "pihakMainRekName" => ".1020041010",//akum penyu peralatan produksi
                        "pihakMainAkumDetails" => ".1020041020",//akum penyu peralatan produksi
                    ),
                    "mesin" => array(
                        "pihakMainRekName" => ".1020030010",//akum penyu mesin
                        "pihakMainAkumDetails" => ".1020030020",//akum penyu mesin
                    ),
                    "tanah dan bangunan" => array(
                        "pihakMainRekName" => ".1020050010",//akum penyu tanah dan bangunan
                        "pihakMainAkumDetails" => ".1020050020",//akum penyu tanah dan bangunan
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
            "dpp_pengganti"=>"nett1*(11/12)*ppn_cek",
            "dpp_pengganti_factor"=>"11/12",
            "ppn"=>"(dpp_pengganti*12)/100",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
            "grand_total" => "nett1+install_tax+install+ongkir",
            "dpp" => "dpp_pengganti",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "grand_ppn",
            "new_net1" => "nett1+ongkir",
            "new_net2" => "nett2+ongkir",
            "new_net3" => "new_net2",
            "tagihan" => "new_net1+grand_ppn-dp-nilai_cia",
            "grand_total_ui" => "nett1+install_tax+install+ongkir_ui",
            "tagihan_ui" => "nett1+install_tax+install+ongkir_ui+ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",
            "dpp_nppn"=>"tagihan_ui",

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
        "preProcessor" => array(
            "8789" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".ppn out",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "ppn",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            )
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
        "components" => array(
            "8789" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "tagihan_ui",//kas
                            "{pihakMainAkumID_coa}" => "harga_depre",//akum penyu xxx
                            "{rugiLabaLainRekName}" => "rugiLabaLain",//rugilaba lain lain
                            "{pihakMainID_coa}" => "-harga_perolehan",//aktiva tetap
                            "2030060" => "ppn",//ppn out
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
                            "1010010010" => "tagihan_ui",//kas
                            "{pihakMainAkumID_coa}" => "harga_depre",//akum penyu xxx
                            "{rugiLabaLainRekName}" => "rugiLabaLain",//rugilaba lain lain
                            "{pihakMainID_coa}" => "-harga_perolehan",//aktiva tetap
                            "2030060" => "ppn",//ppn out
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
                        "comName" => "RekeningPembantuAktivaTetap",
                        "loop" => array(
                            "{pihakMainID_coa}" => "-harga_perolehan",//aktiva tetap
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern2_id" => "pihakMainID",
                            "extern2_nama" => "pihakMainName",
                            "extern_id" => "pihakMainID_coa",
                            "extern_nama" => "pihakMainID_coa_name",
                            "produk_nilai" => "-harga_perolehan",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "tagihan_ui",//kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    array(
                        "comName" => "{pihakMainAkum}",
                        "loop" => array(
                            "{pihakMainAkumID_coa}" => "harga_depre",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
//                            "produk_qty" => "-qty",
                            "note" => "note",
                            "produk_nilai" => "harga_depre",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuAktivaBerwujud",
                        "loop" => array(
                            // "{pihakMainName}" => "-harga_perolehan",
                            "{pihakMainID_coa}" => "-harga_perolehan",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "note" => "note",
                            "produk_nilai" => "harga_perolehan",
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
        "preProcessorAuto" => array(
            "8789" => array(
                "master" => array(
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040020",//hutang biaya ke pusat
                            "nilai" => "tagihan_ui",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "LockerDebtValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2040010",//hutang ke pusat
                            "nilai" => "nilai_sisa_2040020",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
                            ),
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "componentsAuto" => array(
            "8789" => array(
                "master" => array(
                    //region bagian cabang
                    90 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-tagihan_ui",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    91 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "-nilai_sisa_2040010",// laba ditempatkan pusat
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                            "1010010010" => "-tagihan_ui",// kas
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    92 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "-tagihan_ui",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    93 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-nilai_dipakai_2040010",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    94 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040020" => "-nilai_dipakai_2040020",// hutang biaya ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => ".-1",
                            "cabang2_nama" => ".PUSAT (DC)",
                            "extern_id" => ".-1",
                            "extern_nama" => ".PUSAT (DC)",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    100 => array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010010030" => "-nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion>

                    //region bagian pusat
                    95 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "tagihan_ui",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "3020050" => "nilai_sisa_2040010",// laba ditempatkan pusat
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                            "1010010010" => "tagihan_ui",// kas
//                            "2020020" => "-nilai_koran_full",// hutang bank
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    97 => array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "tagihan_ui",// kas
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "cash_account",// diisi id bank
                            "extern_nama" => "cash_account__nama",// diisi nama bank
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-nilai_dipakai_2040010",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    99 => array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060040" => "-nilai_dipakai_2040020",// piutang biaya cabang
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    101 => array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_dijadikan_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "extern_id" => "customerDetails__parent",
                            "extern_nama" => "customerDetails__nama",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion
                ),
                "detail" => array(),
            ),
        ),
        "postProcessorAuto" => array(
            "8789" => array(
                "master" => array(
                    // locker kas cabang
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "-tagihan_ui",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // locker kas reguler pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => ".-1",
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account",
                            "nama" => "cash_account__nama",
                            "nilai" => "tagihan_ui",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentSrcMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang setoran",
                            "target_jenis" => ".759",
                            "transaksi_id" => "transaksi_id",
                            "terbayar" => "tagihan_ui",
                            "sisa" => ".0",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
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
                "pph_value" => "(harga*tarif_pph_item)/100",
                "harga_disc" => "harga-pph_value",
                "ppn" => "(ppnFactor*harga)/100",
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
                "ppnPersenCheck" => array(
                    "1" => array(
                        "nilai_ppn" => "ppn",
                        "nilai_persediaan" => "harga_disc",
                    ),
                    "0" => array(
                        "nilai_ppn" => "0",
                        "nilai_persediaan" => "nett",
                    ),
                ),
                "paymentMethod" => array(
                    "credit" => array(
                        "nilai_credit" => "harga",
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
            "pph_key_val" => "100-tarif_pph_item",

            "totalCredit" => "creditAmount+creditValue",

            "harus_bayar_orig" => "extern_nilai2-non_pph",

            "ppn_key" => "source_ppn_persen+100",
            "source_dpp" => "(nilai_entry*100)/ppn_key",

            "valid_dpp" => "source_dpp-non_pph",
            "pph23_nilai" => "(pph23Method__tarif/100)*valid_dpp",
            "nilai_bayar" => "nilai_entry+totalCredit",
            "valid_ppn" => "source_dpp*source_ppn_persen/100",


        ),
        "preProcessor" => array(
            "425" => array(
                "master" => array(
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
//                            "nilai" => "nilai_ppn",
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
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
                            "jenis" => ".sewa",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "harga",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_tambah" => "nilai_tambah",
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
        "components" => array(
            "425" => array(
                "master" => array(
                    //region pusat
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                            "2010020" => "nilai_tambah_sewa",//hutang sewa
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
//                            "{pphGate_coa}" => "pph_value",//hutang pph 23 /hutang pph 4 ps 2
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "jurnal_1" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                            "2010020" => "nilai_tambah_sewa",//hutang sewa
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
//                            "{pphGate_coa}" => "pph_value",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "rekening_1" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010020" => "nilai_tambah_sewa",//hutang sewa
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "RekeningPembantuSupplier_1" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "RekeningPembantuSupplier_2" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),


                    //endregion pusat

                    //region pusat distribusi ke cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1030010" => "-harga",//sewa dibayar dimuka
                            "1010060010" => "harga",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "jurnal_2" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1030010" => "-harga",//sewa dibayar dimuka
                            "1010060010" => "harga",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "rekening_2" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "harga",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang2_id" => "placeID",
                            "cabang2_nama" => "placeName",
                            "extern_id" => "branchTarget",
                            "extern_nama" => "branchTarget__nama",
                            "RekeningPembantuAntarcabang_1" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion distribusi ke cabang

                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                            "2040010" => "harga",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "branchTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "jurnal_3" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                            "2040010" => "harga",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "branchTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "rekening_3" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "harga",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "branchTarget",
                            "cabang2_id" => "branchTarget",
                            "cabang2_nama" => "branchTarget__nama",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "RekeningPembantuSewa_6" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //endregion cabang

                    //region auto geser ppn masukan

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040050" => "-nilai_tambah_ppn_in",//ppn in
//                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "jurnal_1" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010040050" => "-nilai_tambah_ppn_in",//ppn in
//                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "rekening_1" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion
                ),
                "detail" => array(
                    // pembantu sewa pusat
                    array(
                        "comName" => "RekeningPembantuSewa",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "RekeningPembantuSewa_4" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSewa",
                        "loop" => array(
                            "1030010" => "-harga",//sewa dibayar dimuka
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "qty",
                            "produk_nilai" => "-harga_dipakai",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "RekeningPembantuSewa_5" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // pembantu sewa cabang
                    array(
                        "comName" => "RekeningPembantuSewa",
                        "loop" => array(
                            "1030010" => "harga",//sewa dibayar dimuka
                        ),
                        "static" => array(
                            "cabang_id" => "branchTarget",
                            "cabang_nama" => "branchTarget__nama",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "RekeningPembantuSewa_6" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
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

                    //nulis payment source di geser ke heTransaksi_misc karena com hanya untuk update tidak untuk insert baru
                    // array(
                    //     "comName" => "PaymentSource",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "cabang_nama" => "cabangName",
                    //         "extern_id" => "pihakID",
                    //         "extern_nama" => "pihakName",
                    //         "label" => "pphGate",
                    //         "jenis" => "jenisTr",
                    //         "target_jenis" => "extern_target_jenis",
                    //         "transaksi_id" => "transaksi_id",
                    //         "terbayar" => "0",
                    //         "tagihan" => "pph_value",
                    //         "sisa" => "pph_value",
                    //         "nomer" => "nomer",
                    //         "reference_jenis" => "jenisTr",
                    //         "extern_nilai2" => "harga",
                    //         "oleh_id" => "olehID",
                    //         "oleh_nama" => "olehName",
                    //         "ppn_pph_faktor" => "tarif_pph",
                    //         //                            "extern2_nama" =>"pihakMainName",
                    //         //                            "extern_jenis" =>"pphGate",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),

                ),
                "detail" => array(

                    array(
                        "comName" => "LockerValueItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "branchTarget",
                            "gudang_id" => "branchTarget__gudangID",
                            "state" => ".active",
                            "jenis" => ".sewa",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "note" => "note",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            //-----
                            "cabang_id_label" => ".transaksi gagal disimpan karena cabang pembebanan sewa belum ditentukan. silahkan periksa ulang transaksi anda.",
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
                            // "cabang_id" => "placeID",
//                            "asset_account" => "asset_account",
                            "rekening_main" => "externMain",
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

