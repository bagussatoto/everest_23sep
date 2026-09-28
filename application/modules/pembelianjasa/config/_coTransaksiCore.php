<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

//     "466" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn", // yg dipakai di grand total
//             ),
//             "master_dependent" => array(
//                 "paymentMethod" => array(
// //                    "cash" => array(
// //                        "nilai_cash" => "tagihan",
// //                        "nilai_credit" => "0",
// //                    ),
//                     "credit" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cbd" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cia" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "tt_adv" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                 ),
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "nett",
//             "tagihan" => "grand_total-discount-dp",
// //            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
// //            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
//         ),
//         "preProcessor" => array(
//             "467" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "ppn",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "tagihan-nilai_dipakai_ppn_in",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "nett",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//
//         "components" => array(
//             "467" => array(
//                 "master" => array(
//                     //region jurnal pertama
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk riil" => "harga",
//                             "ppn in" => "nilai_tambah_ppn_in",
//
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk riil" => "harga",
//                             "ppn in" => "nilai_tambah_ppn_in",
//
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     //region jurnal kedua pindah persediaan riil ke persediaan(std)
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk" => "hpp_nppv",
//                             "persediaan produk riil" => "-harga",
//                             "hutang lain ppv" => "ppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk" => "hpp_nppv",
//                             "persediaan produk riil" => "-harga",
//                             "hutang lain ppv" => "ppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "RekeningPembantuProdukRiil",
//                         "loop" => array(
//                             "persediaan produk riil" => "sub_harga",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "harga",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProdukRiil",
//                         "loop" => array(
//                             "persediaan produk riil" => "-sub_harga",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "harga",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk" => "sub_hpp_nppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "hpp_nppv",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//
//                 ),
//             ),
//             "111" => array(
//                 "master" => array(
//                     //region seleish ppn 10 vs 11 %
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "ppn in" => "-ppn_realisasi",
//                             "ppn in realisasi" => "ppn_realisasi",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "ppn in" => "-ppn_realisasi",
//                             "ppn in realisasi" => "ppn_realisasi",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "-ppn_realisasi",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "postProcessor" => array(
//             "466r" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     // locker transkasi
//                     // array(
//                     //     "comName" => "LockerTransaksi",
//                     //     "loop" => array(),
//                     //     "static" => array(
//                     //         "cabang_id" => "placeID",
//                     //         "jenis" => ".transaksi",
//                     //         "state" => ".active",
//                     //         "jumlah" => ".1",
//                     //         "produk_id" => ".0",
//                     //         "nama" => "",
//                     //         "satuan" => "",
//                     //         "oleh_id" => ".0",
//                     //         "gudang_id" => ".0",
//                     //     ),
//                     //     "srcGateName" => "main",
//                     //     "srcRawGateName" => "main",
//                     // ),
//                 ),
//                 "detail" => array(),
//             ),
//             "466" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     // locker transkasi
//                     // array(
//                     //     "comName" => "LockerTransaksi",
//                     //     "loop" => array(),
//                     //     "static" => array(
//                     //         "cabang_id" => "placeID",
//                     //         "jenis" => ".transaksi",
//                     //         "state" => ".active",
//                     //         "jumlah" => ".1",
//                     //         "produk_id" => ".0",
//                     //         "nama" => "",
//                     //         "satuan" => "",
//                     //         "oleh_id" => ".0",
//                     //         "gudang_id" => ".0",
//                     //     ),
//                     //     "srcGateName" => "main",
//                     //     "srcRawGateName" => "main",
//                     // ),
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "PriceProdukPerSupplier",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "suppliers_id" => "pihakID",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".produk",
//                             "jenis_value" => ".hpp",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "PriceProdukLastPurchase",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".produk",
//                             "jenis_value" => ".hpp",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "PriceProduk",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".produk",
//                             "jenis_value" => ".hpp",
//
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "467" => array(
//                 "master" => array(
//
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_ppn_in",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_piutang_pembelian",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "partial_otorisasi" => "partial_otorisasi",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "partial_otorisasi" => "partial_otorisasi",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     // locker transkasi
//                     // array(
//                     //     "comName" => "LockerTransaksi",
//                     //     "loop" => array(),
//                     //     "static" => array(
//                     //         "cabang_id" => "placeID",
//                     //         "jenis" => ".transaksi",
//                     //         "state" => ".active",
//                     //         "jumlah" => ".1",
//                     //         "produk_id" => ".0",
//                     //         "nama" => "",
//                     //         "satuan" => "",
//                     //         "oleh_id" => ".0",
//                     //         "gudang_id" => ".0",
//                     //     ),
//                     //     "srcGateName" => "main",
//                     //     "srcRawGateName" => "main",
//                     // ),
//                 ),
//                 "detail" => array(
//
//                     array(
//                         "comName" => "FifoAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "jenis" => ".produk",
//                             "jml" => "qty",
//                             "produk_id" => "id",
//                             "hpp" => "hpp_nppv",
//                             "jml_nilai" => "sub_hpp_nppv",
//                             "hpp_riil" => "harga",
//                             "jml_nilai_riil" => "sub_harga",
//                             "ppv_riil" => "ppv",
//                             "ppv_nilai_riil" => "sub_ppv",
//                             "nama" => "name",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "ppn_in" => "ppn",
//                             "ppn_in_nilai" => "sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoProdukJadi",
//                         "loop" => array(),
//                         "static" => array(
//                             "unit" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "hpp" => "hpp_nppv",
//                             "jml_nilai" => "sub_hpp_nppv",
//                             "hpp_riil" => "harga",
//                             "jml_nilai_riil" => "sub_harga",
//                             "ppv_riil" => "ppv",
//                             "ppv_nilai_riil" => "sub_ppv",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "ppn_in" => "ppn",
//                             "ppn_in_nilai" => "sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok reguler
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".active",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             // "transaksi_no" => "nomer",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//                     array(
//                         "comName" => "PriceProduk",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "hpp_nppv",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".produk",
//                             "jenis_value" => ".hpp_nppv",
//
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "PriceProduk",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".produk",
//                             "jenis_value" => ".hpp_grn",
//
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "111" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
// //                            "target_jenis" => "jenisTr",
//                             "jenis" => ".467",
//                             "transaksi_id" => "currentID",
//                             "ppn_approved" => "ppn_realisasi",
// //                            "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
// //                            "target_jenis" => ".489",
//                             "jenis" => ".467",
//                             "transaksi_id" => "currentID",
//                             "terbayar" => "selisih_ppn_realisasi",
//                             "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".4",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".4",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//
//         "closedRequest" => array(
//             "466" => array(
//                 "enabled" => true,
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//     ),
//     "967" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
// //                "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//                 "fifo_riil" => "hpp/1.25",
//                 "ppv" => "hpp-fifo_riil",
//             ),
//         ),
//         "valueBuilders" => array(
// //            "ppv" => "hpp-hpp_riil",
//             "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
//         ),
//         "valueBuilders_rsltItems" => array(
//
//             "hpp" => "sub_hpp",
//
//         ),
//         "preProcessor" => array(
//             "967" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     array(
//                         "comName" => "FifoAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "items" => array(
//                                 "hpp" => "hpp",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoProdukJadi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "rsltItems" => array(
//                                 "id" => "produk_id",
//                                 "nama" => "nama",
//                                 "name" => "nama",
// //                                "harga" => "hpp",
//                                 "hpp" => "hpp",
//                                 "jml" => "qty",
//                                 "qty" => "qty",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "subtotal" => "subtotal",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//
//             "rsltItems" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//             "rsltItemsValues" => array(
//                 "harga" => "harga",
//                 "hpp" => "hpp",
//                 "ppn" => "ppn",
//                 "nett" => "nett",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//             "detail_rsltItems" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//
//         "components" => array(
//             "967" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk" => "-hpp",
//                             "persediaan produk riil" => "hpp_riil",
// //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk" => "-hpp",
//                             "persediaan produk riil" => "hpp_riil",
// //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //<editor-fold desc="Com-jurnal dan rekening">
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
// //                            "persediaan produk"                        => "-hpp",
//                             "persediaan produk riil" => "-hpp_riil",
//                             "piutang pembelian" => "nett",
//                             "ppn in" => "-ppn",
// //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
// //                            "persediaan produk"                        => "-hpp",
//                             "persediaan produk riil" => "-hpp_riil",
//                             "piutang pembelian" => "nett",
//                             "ppn in" => "-ppn",
// //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//
//                     //<editor-fold desc="Com-rekening pembantu">
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "piutang pembelian" => "nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "-ppn",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//                 ),
//                 "detail" => array(
//                     //<editor-fold desc="Post-rekening pembantu, detail">
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk" => "-sub_hpp",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             //							"produk_nilai" => "harga",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk riil" => "sub_hpp_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             //							"produk_nilai" => "harga",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk riil" => "-sub_hpp_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             //							"produk_nilai" => "harga",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     //</editor-fold>
//
//                 ),
//             ),
//         ),
//         "postProcessor" => array(
//             "967r" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     array(
//                         "comName" => "TransaksiItemReturnUpdate",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_jenis" => ".produk",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "transaksi_id" => "referenceID",
//                             "seluruhnya" => "seluruhnya",
//                             "returnMethod" => "pihakMainName", // by pass diisi metode per-barang atau per-nota
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".active",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => ".0",
//                             "nomer" => ".0",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".hold",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => "transaksi_id",
//                             "nomer" => "nomer",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "967" => array(
//                 "master" => array(
//                     // post procc payment anti source
//                     array(
//                         "comName" => "PaymentAntiSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "transaksi_id" => ".0",
//                             "jenis" => ".0",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
// //                            "label" => ".hutang dagang",
//                             "label" => ".piutang pembelian",
//                             "sisa" => "nett",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".hold",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => "masterID",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".deactivated",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//     ),
//
//     "1967" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "harga+ppn",
//             "tagihan" => "grand_total-discount",
//         ),
//         "valueBuilders_rsltItems" => array(),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//         "components" => array(),
//         "postProcessor" => array(
//             "master" => array(
// //                array(
// //                    "comName" => "Jurnal_activity",
// //                    "loop" => array(//                            "activity" => ".1",
// //                    ),
// //                    "static" => array(
// ////                            "cabang_id" => "placeID",
// ////                            "cabang_nama" => "placeName",
// ////                            "cabang2_id" => "placeID",
// ////                            "cabang2_nama" => "placeName",
// ////                            "oleh_id" => "olehID",
// ////                            "oleh_nama" => "olehName",
// ////                            "jenis" => "jenisTr",
// ////                            "jenis_master" => "jenisTrMaster",
// ////                            "jenis_top" => "jenisTrTop",
// ////                            "master_id" => "transaksi_id",
// ////                            "step_number" => ".1",
// //                    ),
// //                    "srcGateName" => "main",
// //                    "srcRawGateName" => "main",
// //                ),
//             ),
//             "detail" => array(),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//             "stepCode|masterID|placeID|olehID|supplierID",
//             "stepCode|masterID|placeID|supplierID",
//             "stepCode|masterID|olehID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//             "stepCode|masterID|placeID|olehID|supplierID",
//             "stepCode|masterID|placeID|supplierID",
//             "stepCode|masterID|olehID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//
//     ),
//     //supplies
//     "461" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//                 "gudang2ID" => "gudang2",
//                 "gudang2Name" => "gudang2__nama",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "disc" => "(discPersen*harga)/100",
//                 "harga_disc" => "harga-disc",
//                 //                "ppn" => "(ppnFactor*harga)/100",
//                 "ppnPersen" => "ppnFactor",
//                 "ppn" => "(ppnPersen*harga_disc)/100",
// //                "ppn" => "(ppnFactor*harga_disc)/100",
//                 "hpp_nppn" => "harga_disc+ppn",
//                 "hpp_nppv" => "harga_disc*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga_disc",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "hpp_nppn",
//             ),
//             "master_dependent" => array(
//                 "paymentMethod" => array(
//                     "credit" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cbd" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cia" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "tt_adv" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                 ),
//
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "nett",
//             "tagihan" => "grand_total-discount-dp",
//         ),
//         "preProcessor" => array(
//             "461" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "ppn",
//                             //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "tagihan-nilai_dipakai_ppn_in",
//                             //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "harga",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//                 "gudang2_id" => "gudang2ID",
//                 "gudang2_nama" => "gudang2Name",
//             ),
//
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//                 "keterangan" => "note",
//             ),
//
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "supplies",
//             ),
//         ),
//
//         "components" => array(
//             "461" => array(
//                 "master" => array(
//                     //region jurnal pertama
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan supplies riil" => "harga_disc",
//                             "ppn in" => "nilai_tambah_ppn_in",
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan supplies riil" => "harga_disc",
//                             "ppn in" => "nilai_tambah_ppn_in",
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             //                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     //region jurnal kedua pindah persediaan riil ke persediaan(std)
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan supplies" => "hpp_nppv",
//                             "persediaan supplies riil" => "-harga_disc",
//                             "hutang lain ppv" => "ppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan supplies" => "hpp_nppv",
//                             "persediaan supplies riil" => "-harga_disc",
//                             "hutang lain ppv" => "ppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "RekeningPembantuSuppliesRiil",
//                         "loop" => array(
//                             "persediaan supplies riil" => "sub_harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "harga_disc",
//                             "gudang_id" => "gudangID",
//                             //                            "gudang_id" => "gudang2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//                     array(
//                         "comName" => "RekeningPembantuSuppliesRiil",
//                         "loop" => array(
//                             "persediaan supplies riil" => "-sub_harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "harga_disc",
//                             "gudang_id" => "gudangID",
//                             //                            "gudang_id" => "gudang2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplies",
//                         "loop" => array(
//                             "persediaan supplies" => "sub_hpp_nppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "hpp_nppv",
//                             "gudang_id" => "gudangID",
//                             //                            "gudang_id" => "gudang2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "112" => array(
//                 "master" => array(
//                     //region seleish ppn 10 vs 11 %
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
// //                            "hutang dagang" => "-selisih_ppn_realisasi",
//                             "hutang dagang" => "selisih_ppn_realisasi*-1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
// //                            "ppn in" => "-selisih_ppn_realisasi",
//                             "ppn in" => "selisih_ppn_realisasi*-1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "ppn in" => "-nilai_tambah_ppn_in",
//                             "ppn in realisasi" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "ppn in" => "-nilai_tambah_ppn_in",
//                             "ppn in realisasi" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "-nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "postProcessor" => array(
//             "461ro" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
//                             //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//             "461r" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
//                             //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "PriceSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".supplies",
//                             "jenis_value" => ".hpp",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "461" => array(
//                 "master" => array(
//                     //                    array(
//                     //                        "comName" => "Signature",
//                     //                        "loop" => array(
//                     //                            "transaksi_id" => "references",
//                     //                        ),
//                     //                        "static" => array(
//                     //
//                     //                            "nomer" => "nomer",
//                     //                            "step_number" => ".3",
//                     //                            "step_code" => ".761ro",
//                     //                            "step_name" => ".request process",
//                     //                            "group_code" => ".sys",
//                     //                            "oleh_id" => "olehID",
//                     //                            "oleh_nama" => "olehName",
//                     //                            "keterangan" => ".autostep by other transaction",
//                     //                        ),
//                     //                        "srcGateName" => "main",
//                     //                        "srcRawGateName" => "main",
//                     //                    ),
//                     //                    array(
//                     //                        "comName" => "TransaksiStepUpdater",
//                     //                        "loop" => array(
//                     //                            "references" => "references",
//                     //                        ),
//                     //                        "static" => array(
//                     //                            "next_step_code" => ".761",
//                     //                            "next_step_label" => ".request fulfill",
//                     //                            "next_group_code" => ".admin",
//                     //                            "next_step_num" => ".4",
//                     //                            "step_current" => ".2",
//                     //                        ),
//                     //                        "static2" => array(//==untuk rincian transaksi
//                     //                            "next_substep_code" => ".761",
//                     //                            "next_substep_label" => ".request fulfill",
//                     //                            "next_subgroup_code" => ".admin",
//                     //                            "next_substep_num" => ".4",
//                     //                            "sub_step_current" => ".2",
//                     //                        ),
//                     //                        "srcGateName" => "main",
//                     //                        "srcRawGateName" => "main",
//                     //                    ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_ppn_in",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_piutang_pembelian",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
//                             //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "LockerStockSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".supplies",
//                             "state" => ".active",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "gudang_id" => "gudangID",
//                             //                            "gudang_id" => "gudang2ID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "jenis" => ".supplies",
//                             "jml" => "qty",
//                             "produk_id" => "id",
//                             "hpp" => "hpp_nppv",
//                             "jml_nilai" => "sub_hpp_nppv",
//                             "hpp_riil" => "harga_disc",
//                             "jml_nilai_riil" => "sub_harga_disc",
//                             "ppv_riil" => "ppv",
//                             "ppv_nilai_riil" => "sub_ppv",
//                             "nama" => "name",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             //                            "gudang_id" => "gudang2ID",
//                             "ppn_in" => "ppn",
//                             "ppn_in_nilai" => "sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "unit" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "hpp" => "hpp_nppv",
//                             "jml_nilai" => "sub_hpp_nppv",
//                             "hpp_riil" => "harga_disc",
//                             "jml_nilai_riil" => "sub_harga_disc",
//                             "ppv_riil" => "ppv",
//                             "ppv_nilai_riil" => "sub_ppv",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "ppn_in" => "ppn",
//                             "ppn_in_nilai" => "sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasiSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             // "transaksi_no" => "nomer",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//                     array(
//                         "comName" => "PriceSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "hpp_nppv",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".supplies",
//                             "jenis_value" => ".hpp_nppv",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "PriceSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".supplies",
//                             "jenis_value" => ".hpp_grn",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "112" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
//                             //                            "target_jenis" => "jenisTr",
//                             "jenis" => ".461",
//                             "transaksi_id" => "currentID",
//                             "ppn_approved" => "nilai_tambah_ppn_in",
//                             //                            "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
// //                            "target_jenis" => ".487",
//                             "jenis" => ".461",
//                             "transaksi_id" => "currentID",
//                             "terbayar" => "selisih_ppn_realisasi",
//                             "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".4",
//                             //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//
//         "closedRequest" => array(
//             2 => array(
//                 "enabled" => true,
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//     ),
//     //config pre request supplies from cabang to DC
//     "1763" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|cabang2ID",
//             "stepCode|placeID|cabang2ID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "pihakID" => "cabang2ID",
//                 "pihakName" => "cabang2Name",
//                 "gudang" => "gudang2ID",
//                 "gudang__label" => "gudang2Name",
//                 "gudang__name" => "gudang2Name",
//                 "gudang2Name" => "gudang2Name",
//
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//                 //                "dtime" => "dtime",
//                 //                "id" => "id",
//                 //                "code" => "code",
//                 //                "label" => "label",
//                 //                "name" => "nama",
//                 //                "qty" => "jml",
//                 //                "satuan" => "satuan",
//                 //                "note" => "note",
//                 //
//                 //"berat"         => "berat",
//                 //"lebar"         => "lebar",
//                 //"panjang"       => "panjang",
//                 //"tinggi"        => "tinggi",
//                 //"volume"        => "volume",
//                 //                "berat_gross" => "berat_gross",
//                 //                "lebar_gross" => "lebar_gross",
//                 //                "panjang_gross" => "panjang_gross",
//                 //                "tinggi_gross" => "tinggi_gross",
//                 //                "volume_gross" => "volume_gross",
//                 //
//                 //                "hpp" => "hpp",
//                 //                "harga" => "harga",
//                 //                "sub_hpp" => "sub_hpp",
//                 //                "sub_harga" => "sub_harga",
//                 //
//                 //                "pihakID" => "pihakID",
//                 //                "pihakName" => "pihakName",
//                 //                "cabangID" => "placeID",
//                 //                "cabangName" => "placeName",
//                 //                "olehID" => "olehID",
//                 //                "olehName" => "olehName",
//             ),
//         ),
//         "valueBuilders" => array(),
//         "valueBuilders_rsltItems" => array(),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "hpp",
//
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "pihakID" => "place2ID",
//                 "pihakName" => "place2Name",
//                 "pihakName2" => "place2Name",
//                 "cabang2ID" => "place2ID",
//                 "cabang2Name" => "place2ID",
//                 "place2ID" => "place2ID",
//                 "place2Name" => "place2ID",
//
//                 "gudang" => "gudangID",
//                 "gudang__label" => "gudang2Name",
//                 "gudang__name" => "gudang2Name",
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "hpp",
//                 "hpp" => "harga",
//                 //                "ppn" => "ppn",
//                 // "produk_ord_diskon",
//                 // "produk_hrg_ori",
//                 // "produk_hrg_gap",
//                 "satuan" => "satuan",
//             ),
//             "rsltItems" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "hpp",
//                 "hpp" => "harga",
//                 //                "ppn" => "ppn",
//                 // "produk_ord_diskon",
//                 // "produk_hrg_ori",
//                 // "produk_hrg_gap",
//                 "satuan" => "satuan",
//             ),
//
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//             "rsltItems" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//         "components" => array(),
//         "postProcessor" => array(),
//     ),
//     "11763" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|cabang2ID",
//             "stepCode|placeID|cabang2ID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "pihakID" => "cabang2ID",
//                 "pihakName" => "cabang2Name",
//                 "gudang" => "gudang2ID",
//                 "gudang__label" => "gudang2Name",
//                 "gudang__name" => "gudang2Name",
//                 "gudang2Name" => "gudang2Name",
//
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//                 //                "dtime" => "dtime",
//                 //                "id" => "id",
//                 //                "code" => "code",
//                 //                "label" => "label",
//                 //                "name" => "nama",
//                 //                "qty" => "jml",
//                 //                "satuan" => "satuan",
//                 //                "note" => "note",
//                 //
//                 //"berat"         => "berat",
//                 //"lebar"         => "lebar",
//                 //"panjang"       => "panjang",
//                 //"tinggi"        => "tinggi",
//                 //"volume"        => "volume",
//                 //                "berat_gross" => "berat_gross",
//                 //                "lebar_gross" => "lebar_gross",
//                 //                "panjang_gross" => "panjang_gross",
//                 //                "tinggi_gross" => "tinggi_gross",
//                 //                "volume_gross" => "volume_gross",
//                 //
//                 //                "hpp" => "hpp",
//                 //                "harga" => "harga",
//                 //                "sub_hpp" => "sub_hpp",
//                 //                "sub_harga" => "sub_harga",
//                 //
//                 //                "pihakID" => "pihakID",
//                 //                "pihakName" => "pihakName",
//                 //                "cabangID" => "placeID",
//                 //                "cabangName" => "placeName",
//                 //                "olehID" => "olehID",
//                 //                "olehName" => "olehName",
//             ),
//         ),
//         "valueBuilders" => array(),
//         "valueBuilders_rsltItems" => array(),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "hpp",
//
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "pihakID" => "place2ID",
//                 "pihakName" => "place2Name",
//                 "pihakName2" => "place2Name",
//                 "cabang2ID" => "place2ID",
//                 "cabang2Name" => "place2ID",
//                 "place2ID" => "place2ID",
//                 "place2Name" => "place2ID",
//
//                 "gudang" => "gudangID",
//                 "gudang__label" => "gudang2Name",
//                 "gudang__name" => "gudang2Name",
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "hpp",
//                 "hpp" => "harga",
//                 //                "ppn" => "ppn",
//                 // "produk_ord_diskon",
//                 // "produk_hrg_ori",
//                 // "produk_hrg_gap",
//                 "satuan" => "satuan",
//             ),
//             "rsltItems" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "hpp",
//                 "hpp" => "harga",
//                 //                "ppn" => "ppn",
//                 // "produk_ord_diskon",
//                 // "produk_hrg_ori",
//                 // "produk_hrg_gap",
//                 "satuan" => "satuan",
//             ),
//
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//             "rsltItems" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//         "components" => array(),
//         "postProcessor" => array(),
//     ),
//
//     //  config return pembelian supplies
//     "961" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "detail_rsltItems" => array(//===sumber nilai berupa rincian
//
//             ),
//         ),
//         "valueBuilders" => array(
//             //            "bruto" => "sub_harga",
//             //            "ppn" => "sub_ppn",
//             //            "nett" => "sub_nett",
//         ),
//         "valueBuilders_rsltItems" => array(
//             //            "bruto" => "sub_harga",
//             //            "ppn"   => "sub_ppn",
//             "hpp" => "sub_hpp",
//             //            "nett"  => "sub_nett",
//         ),
//         "preProcessor" => array(
//             "961" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     array(
//                         "comName" => "FifoAverageSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "items" => array(
//                                 "hpp" => "hpp",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "rsltItems" => array(
//                                 "id" => "produk_id",
//                                 "nama" => "nama",
//                                 "name" => "nama",
// //                                "harga" => "hpp",
//                                 "hpp" => "hpp",
//                                 "jml" => "qty",
//                                 "qty" => "qty",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "subtotal" => "subtotal",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//
//             "rsltItems" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//             "rsltItemsValues" => array(
//                 "harga" => "harga",
//                 "hpp" => "hpp",
//                 "ppn" => "ppn",
//                 "nett" => "nett",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "supplies",
//             ),
//             "detail_rsltItems" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "supplies",
//             ),
//         ),
//
//         "components" => array(
//             "961" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan supplies" => "-hpp",
//                             "persediaan supplies riil" => "hpp_riil",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan supplies" => "-hpp",
//                             "persediaan supplies riil" => "hpp_riil",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     //<editor-fold desc="Com-jurnal dan rekening">
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
// //                            "persediaan supplies" => "-hpp",
//                             "persediaan supplies riil" => "-hpp_riil",
//                             "piutang pembelian" => "nett",
//                             "ppn in" => "-ppn",
// //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
// //                            "persediaan supplies" => "-hpp",
//                             "persediaan supplies riil" => "-hpp_riil",
//                             "piutang pembelian" => "nett",
//                             "ppn in" => "-ppn",
// //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//
//                     //<editor-fold desc="Com-rekening pembantu">
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
// //                            "hutang dagang" => "-nett",
//                             "piutang pembelian" => "nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "-ppn",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "RekeningPembantuSupplies",
//                         "loop" => array(
//                             "persediaan supplies" => "-sub_hpp",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "nama",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                 ),
//             ),
//         ),
//         "postProcessor" => array(
//             "961r" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     //<editor-fold desc="Post-Item return update">
//                     array(
//                         "comName" => "TransaksiItemReturnUpdate",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_jenis" => ".supplies",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "transaksi_id" => "referenceID",
//                             "seluruhnya" => "seluruhnya",
//                             "returnMethod" => "pihakMainName", // by pass diisi metode per-barang atau per-nota
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     //</editor-fold>
//                     //<editor-fold desc="Post-locker stock supplies">
//                     array(
//                         "comName" => "LockerStockSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".supplies",
//                             "state" => ".active",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => ".0",
//                             "nomer" => ".0",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStockSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".supplies",
//                             "state" => ".hold",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => "transaksi_id",
//                             "nomer" => "nomer",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     //</editor-fold>
//                 ),
//             ),
//             "961" => array(
//                 "master" => array(
//                     // post procc payment anti source
//                     array(
//                         "comName" => "PaymentAntiSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "transaksi_id" => ".0",
//                             "jenis" => ".0",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
// //                            "label" => ".hutang dagang",
//                             "label" => ".piutang pembelian",
//                             "sisa" => "nett",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
//                     //<editor-fold desc="Post-locker stock">
//                     array(
//                         "comName" => "LockerStockSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".supplies",
//                             "state" => ".hold",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             //                            "transaksi_id" => "transaksi_id",
//                             "transaksi_id" => "masterID",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStockSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".supplies",
//                             "state" => ".deactivated",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasiSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     //</editor-fold>
//                 ),
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//
//     ),
//     //  config cancel purchasing SP (make fullfill)
//     "1961" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "harga+ppn",
//             "tagihan" => "grand_total-discount",
//         ),
//         "valueBuilders_rsltItems" => array(),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//         "components" => array(),
//         "postProcessor" => array(
//             "961r" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(//                            "activity" => ".1",
//                         ),
//                         "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "cabang_nama" => "placeName",
// //                            "cabang2_id" => "placeID",
// //                            "cabang2_nama" => "placeName",
// //                            "oleh_id" => "olehID",
// //                            "oleh_nama" => "olehName",
// //                            "jenis" => "jenisTr",
// //                            "jenis_master" => "jenisTrMaster",
// //                            "jenis_top" => "jenisTrTop",
// //                            "master_id" => "transaksi_id",
// //                            "step_number" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//             "stepCode|masterID|placeID|olehID|supplierID",
//             "stepCode|masterID|placeID|supplierID",
//             "stepCode|masterID|olehID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//             "stepCode|masterID|placeID|olehID|supplierID",
//             "stepCode|masterID|placeID|supplierID",
//             "stepCode|masterID|olehID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//
//     ),
//     "9763" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|placeID|olehID|supplierID",
//             "stepCode|supplierID",
//             "stepCode|placeID|supplierID",
//             "stepCode|olehID|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "harga+ppn",
//             "tagihan" => "grand_total-discount",
//         ),
//         "valueBuilders_rsltItems" => array(),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//         "components" => array(),
//         "postProcessor" => array(
//             "9763" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(//                            "activity" => ".1",
//                         ),
//                         "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "cabang_nama" => "placeName",
// //                            "cabang2_id" => "placeID",
// //                            "cabang2_nama" => "placeName",
// //                            "oleh_id" => "olehID",
// //                            "oleh_nama" => "olehName",
// //                            "jenis" => "jenisTr",
// //                            "jenis_master" => "jenisTrMaster",
// //                            "jenis_top" => "jenisTrTop",
// //                            "master_id" => "transaksi_id",
// //                            "step_number" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//     ),


    // config po jasa
    "463" => array(
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
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "dppPPn" => "harga_disc*(dpp_persen/100)",
                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => "ppnFactor",
                "ppn" => "(ppn_persen/100)*dppPPn",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "max_dpp_persen" => ".100",
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
            "ppn_value" => "nilai_dpp_ppn*ppnFactor/100",
            "payment_out" => "nett",
            "dppPph_dipakai" => "valid_pph_key*dppPPh",

        ),
        "preProcessor" => array(
            "463" => array(
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
                            "nilai" => "ppn",
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
            "463" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010040" => "harga_disc",//biaya
//                            "hutang biaya" => "nett",
                            "1010040070" => "nilai_tambah_ppn_in",//ppn masukan jasa belum ada faktur
//                            "ppn in" => "nilai_tambah_ppn_in",
//                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",//hutang biaya
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010010040" => "harga_disc",
//                            "hutang biaya" => "nett",
                            "1010040070" => "nilai_tambah_ppn_in",
//                            "ppn in" => "nilai_tambah_ppn_in",
//                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
//                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
//                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "1010040070" => "nilai_tambah_ppn_in",
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
                        "comName" => "RekeningPembantuBiaya",
                        "loop" => array(
                            "1010010040" => "harga",
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
            "113" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010040070" => "-selisih_ppn_realisasi",
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",

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
//                            "1010040070" => "-selisih_ppn_realisasi",
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",

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
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",
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
//                            "1010040070" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
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
                            "1010040070" => "-ppn_realisasi",
                            "ppn in realisasi" => "ppn_realisasi",
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
                            "1010040070" => "-ppn_realisasi",
                            "ppn in realisasi" => "ppn_realisasi",
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
                            "1010040070" => "-ppn_realisasi",
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
            "463ro" => array(
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
                ),
                "detail" => array(),
            ),
            "463o" => array(
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
            "463" => array(
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
                ),
                "detail" => array(
//                    array(
//                        "comName" => "TransaksiItemUpdate",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_jenis" => ".invoice",
//                            "jumlah" => "qty",
//                            "produk_id" => "transaksi_id",
//                            "produk_nama" => ".0",
//                            "transaksi_id" => "transaksi_id",
//                            "sinkron" => ".1",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
            "113" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya",
//                            "target_jenis" => "jenisTr",
                            "jenis" => ".463",
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
                            "label" => ".hutang biaya",
//                            "target_jenis" => ".462",
                            "jenis" => ".463",
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
    ),
    "1463" => array(
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
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
//                "ppn" => "(ppnFactor*harga)/100",

                "ppn" => "(ppnPersen*harga_disc)/100",
//                "ppn" => "(ppnFactor*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "nett" => "hpp_nppn",
                "dppPPn" => "harga_disc*(dpp_persen/100)",
                "dppPPh" => "harga_disc*pph",
                "max_dpp_persen" => ".100",
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
            "dppPph_dipakai" => "valid_pph_key*dppPPh",
            "payment_out" => "nett",
        ),
        "preProcessor" => array(
            "1463" => array(
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
                            "nilai" => "ppn",
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
            "1463" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya import" => "harga_disc",
                            "1010040070" => "nilai_tambah_ppn_in",
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "biaya import" => "harga_disc",
                            "1010040070" => "nilai_tambah_ppn_in",
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
//                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
//                            "hutang biaya" => "nilai_tambah_piutang_pembelian",
                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
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
                            "1010040070" => "nilai_tambah_ppn_in",
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

                    // langsung mengeluarkan biaya import ke laba lain-lain
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "biaya import" => "-harga_disc",
                            "laba lain lain" => "-harga_disc",
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
                            "biaya import" => "-harga_disc",
                            "laba lain lain" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "laba lain lain" => "-harga_disc",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".1",
//                            "extern_nama" => ".laba lain lain ppv",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),


                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "laba lain lain" => "-harga_disc",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".3",// laba rugi lain-lain ppv
                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuBiayaImport",
                        "loop" => array(
                            "biaya import" => "harga",
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
                    // biaya import langsung dikeluarkan
                    array(
                        "comName" => "RekeningPembantuBiayaImport",
                        "loop" => array(
                            "biaya import" => "-harga",
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
            "113" => array(
                "master" => array(
                    //region seleish ppn 10 vs 11 %
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010040070" => "-selisih_ppn_realisasi",
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",

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
//                            "1010040070" => "-selisih_ppn_realisasi",
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",
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
//                            "hutang biaya" => "-selisih_ppn_realisasi",
                            "hutang biaya" => "selisih_ppn_realisasi*-1",
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
//                            "1010040070" => "-selisih_ppn_realisasi",
                            "1010040070" => "selisih_ppn_realisasi*-1",
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
                            "1010040070" => "-nilai_tambah_ppn_in",
                            "ppn in realisasi" => "nilai_tambah_ppn_in",
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
                            "1010040070" => "-nilai_tambah_ppn_in",
                            "ppn in realisasi" => "nilai_tambah_ppn_in",
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
                            "1010040070" => "-nilai_tambah_ppn_in",
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
            "1463r" => array(
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
                ),
                "detail" => array(),
            ),
            "1463o" => array(
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
            "1463" => array(
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
                ),
                "detail" => array(),
            ),
            "113" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".hutang biaya",
//                            "target_jenis" => "jenisTr",
                            "jenis" => ".1463",
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
                            "label" => ".hutang biaya",
//                            "target_jenis" => ".1462",
                            "jenis" => ".1463",
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
    ),

//     "460" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn", // yg dipakai di grand total
//             ),
//             "master_dependent" => array(
//                 "paymentMethod" => array(
// //                    "cash" => array(
// //                        "nilai_cash" => "tagihan",
// //                        "nilai_credit" => "0",
// //                    ),
//                     "credit" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cbd" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cia" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "tt_adv" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                 ),
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "nett",
//             "tagihan" => "grand_total-discount-dp",
//         ),
//         "preProcessor" => array(
//             "460" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "ppn",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "tagihan-nilai_dipakai_ppn_in",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "nett",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//
//         "components" => array(
//             "460" => array(
//                 "master" => array(
//
//                     //region jurnal pertama
//
// //                    array(
// //                        "comName" => "Jurnal",
// //                        "loop" => array(
// //                            "persediaan produk riil" => "harga",
// //                            "ppn in" => "nilai_tambah_ppn_in",
// //
// //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
// //                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
// //                    array(
// //                        "comName" => "Rekening",
// //                        "loop" => array(
// //                            "persediaan produk riil" => "harga",
// //                            "ppn in" => "nilai_tambah_ppn_in",
// //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
// //                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
// //                    array(
// //                        "comName" => "RekeningPembantuSupplier",
// //                        "loop" => array(
// //
// //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "pihakID",
// //                            "extern_nama" => "pihakName",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
// //                    array(
// //                        "comName" => "RekeningPembantuSupplier",
// //                        "loop" => array(
// //                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "pihakID",
// //                            "extern_nama" => "pihakName",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
// //                    array(
// //                        "comName" => "RekeningPembantuSupplier",
// //                        "loop" => array(
// //                            "ppn in" => "nilai_tambah_ppn_in",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "pihakID",
// //                            "extern_nama" => "pihakName",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
//
//                     //endregion
//
//                     //region jurnal kedua pindah persediaan riil ke persediaan(std)
//
// //                    array(
// //                        "comName" => "Jurnal",
// //                        "loop" => array(
// //                            "persediaan produk" => "hpp_nppv",
// //                            "persediaan produk riil" => "-harga",
// //                            "hutang lain ppv" => "ppv",
// ////                            "ppn in" => "ppn",
// ////                            "ppn in"            => "nilai_tambah_ppn_in",
// ////                            "hutang lain ppv"   => "ppv",
// ////
// //////                            "kas" => "-nilai_cash",
// //////                            "hutang dagang" => "hpp_nppn",
// //////                            "kas" => "-nilai_cash",
// ////                            "hutang dagang"     => "nilai_tambah_piutang_pembelian",
// ////                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
// //                    array(
// //                        "comName" => "Rekening",
// //                        "loop" => array(
// //                            "persediaan produk" => "hpp_nppv",
// //                            "persediaan produk riil" => "-harga",
// //                            "hutang lain ppv" => "ppv",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "main",
// //                        "srcRawGateName" => "main",
// //                    ),
//
//                     //endregion
//
//
//                     //region jurnal pertama
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk riil" => "exchange__harga",
//                             "ppn in" => "exchange__nilai_tambah_ppn_in",
//
//                             "hutang dagang" => "exchange__nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-exchange__nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk riil" => "exchange__harga",
//                             "ppn in" => "exchange__nilai_tambah_ppn_in",
//                             "hutang dagang" => "exchange__nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-exchange__nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//
//                             "hutang dagang" => "exchange__nilai_tambah_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "piutang pembelian" => "-exchange__nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "exchange__nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     //region jurnal kedua pindah persediaan riil ke persediaan(std)
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk" => "exchange__hpp_nppv",
//                             "persediaan produk riil" => "-exchange__harga",
//                             "hutang lain ppv" => "exchange__ppv",
// //                            "ppn in" => "ppn",
// //                            "ppn in"            => "nilai_tambah_ppn_in",
// //                            "hutang lain ppv"   => "ppv",
// //
// ////                            "kas" => "-nilai_cash",
// ////                            "hutang dagang" => "hpp_nppn",
// ////                            "kas" => "-nilai_cash",
// //                            "hutang dagang"     => "nilai_tambah_piutang_pembelian",
// //                            "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk" => "exchange__hpp_nppv",
//                             "persediaan produk riil" => "-exchange__harga",
//                             "hutang lain ppv" => "exchange__ppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//
//                 ),
//                 "detail" => array(
// //                    array(
// //                        "comName" => "RekeningPembantuProdukRiil",
// //                        "loop" => array(
// //                            "persediaan produk riil" => "sub_harga",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "id",
// //                            "extern_nama" => "name",
// //                            "produk_qty" => "qty",
// //                            "produk_nilai" => "harga",
// //                            "gudang_id" => "gudangID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
// //                    array(
// //                        "comName" => "RekeningPembantuProdukRiil",
// //                        "loop" => array(
// //                            "persediaan produk riil" => "-sub_harga",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "id",
// //                            "extern_nama" => "name",
// //                            "produk_qty" => "-qty",
// //                            "produk_nilai" => "harga",
// //                            "gudang_id" => "gudangID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
// //                    array(
// //                        "comName" => "RekeningPembantuProduk",
// //                        "loop" => array(
// //                            "persediaan produk" => "sub_hpp_nppv",
// //                        ),
// //                        "static" => array(
// //                            "cabang_id" => "placeID",
// //                            "extern_id" => "id",
// //                            "extern_nama" => "name",
// //                            "produk_qty" => "qty",
// //                            "produk_nilai" => "hpp_nppv",
// //                            "gudang_id" => "gudangID",
// //                            "jenis" => "jenisTr",
// //                            "transaksi_no" => "nomer",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
//
//                     array(
//                         "comName" => "RekeningPembantuProdukRiil",
//                         "loop" => array(
//                             "persediaan produk riil" => "exchange__sub_harga",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "exchange__harga",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProdukRiil",
//                         "loop" => array(
//                             "persediaan produk riil" => "-exchange__sub_harga",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "exchange__harga",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk" => "exchange__sub_hpp_nppv",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "exchange__hpp_nppv",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                             //---------------------------------
//                             "produk_nilai_riil" => "exchange__hpp",
//                             "produk_nilai_ppv" => "exchange__ppv",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//
//         ),
//         "postProcessor" => array(
//             "460r" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//             "460a" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
// //                    array(
// //                        "comName" => "PriceProdukPerSupplier",
// //                        "loop" => array(),
// //                        "static" => array(
// //                            "produk_id" => "id",
// //                            "suppliers_id" => "pihakID",
// //                            "produk_nama" => "name",
// //                            "nilai" => "harga",
// //                            "cabang_id" => "placeID",
// //                            "oleh_id" => "olehID",
// //                            "oleh_nama" => "olehName",
// //                            "jenis" => ".produk",
// //                            "jenis_value" => ".hpp",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
//                 ),
//             ),
//             "460" => array(
//                 "master" => array(
//
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-exchange__nilai_dipakai_ppn_in",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-exchange__nilai_dipakai_piutang_pembelian",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "partial_otorisasi" => "partial_otorisasi",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "partial_otorisasi" => "partial_otorisasi",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
// //                    array(
// //                        "comName" => "FifoAverage",
// //                        "loop" => array(),
// //                        "static" => array(
// //                            "jenis" => ".produk",
// //                            "jml" => "qty",
// //                            "produk_id" => "id",
// //                            "hpp" => "hpp_nppv",
// //                            "jml_nilai" => "sub_hpp_nppv",
// //                            "hpp_riil" => "harga",
// //                            "jml_nilai_riil" => "sub_harga",
// //                            "ppv_riil" => "ppv",
// //                            "ppv_nilai_riil" => "sub_ppv",
// //                            "nama" => "name",
// //                            "cabang_id" => "placeID",
// //                            "gudang_id" => "gudangID",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
// //                    array(
// //                        "comName" => "FifoProdukJadi",
// //                        "loop" => array(),
// //                        "static" => array(
// //                            "unit" => "qty",
// //                            "produk_id" => "id",
// //                            "produk_nama" => "name",
// //                            "hpp" => "hpp_nppv",
// //                            "jml_nilai" => "sub_hpp_nppv",
// //                            "hpp_riil" => "harga",
// //                            "jml_nilai_riil" => "sub_harga",
// //                            "ppv_riil" => "ppv",
// //                            "ppv_nilai_riil" => "sub_ppv",
// //                            "cabang_id" => "placeID",
// //                            "gudang_id" => "gudangID",
// //                        ),
// //                        "srcGateName" => "items",
// //                        "srcRawGateName" => "items",
// //                    ),
//
//                     array(
//                         "comName" => "FifoAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "jenis" => ".produk",
//                             "jml" => "qty",
//                             "produk_id" => "id",
//                             "hpp" => "exchange__hpp_nppv",
//                             "jml_nilai" => "exchange__sub_hpp_nppv",
//                             "hpp_riil" => "exchange__harga",
//                             "jml_nilai_riil" => "exchange__sub_harga",
//                             "ppv_riil" => "exchange__ppv",
//                             "ppv_nilai_riil" => "exchange__sub_ppv",
//                             "nama" => "name",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//
//                             "ppn_in" => "exchange__ppn",
//                             "ppn_in_nilai" => "exchange__sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoProdukJadi",
//                         "loop" => array(),
//                         "static" => array(
//                             "unit" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "hpp" => "exchange__hpp_nppv",
//                             "jml_nilai" => "exchange__sub_hpp_nppv",
//                             "hpp_riil" => "exchange__harga",
//                             "jml_nilai_riil" => "exchange__sub_harga",
//                             "ppv_riil" => "exchange__ppv",
//                             "ppv_nilai_riil" => "exchange__sub_ppv",
//
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//
//                             "ppn_in" => "exchange__ppn",
//                             "ppn_in_nilai" => "exchange__sub_ppn",
//                             "suppliers_id" => "pihakID",
//                             "suppliers_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//
//
//                     // locker stok reguler
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".active",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                             // "transaksi_no" => "nomer",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//
//         ),
//
//         "closedRequest" => array(
//             2 => array(
//                 "enabled" => true,
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//     ),
//     //  config return pembelian finish goods import
//     "960" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn",
//             ),
//             "rsltItems" => array(//===sumber nilai berupa rincian
//                 "fifo_riil" => "hpp/1.25",
//                 "ppv" => "hpp-fifo_riil",
//             ),
//         ),
//         "valueBuilders" => array(
// //            "ppv" => "hpp-hpp_riil",
//             "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
//         ),
//         "valueBuilders_rsltItems" => array(
//
//             "hpp" => "sub_hpp",
//
//         ),
//         "preProcessor" => array(
//             "960" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     array(
//                         "comName" => "FifoAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "items" => array(
//                                 "hpp" => "hpp",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "FifoProdukJadi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "resultParams" => array(
//                             "rsltItems" => array(
//                                 "id" => "produk_id",
//                                 "nama" => "nama",
//                                 "name" => "nama",
// //                                "harga" => "hpp",
//                                 "hpp" => "hpp",
//                                 "jml" => "qty",
//                                 "qty" => "qty",
//                                 "hpp_riil" => "hpp_riil",
//                                 "ppv_riil" => "ppv_riil",
//                                 "subtotal" => "subtotal",
//                                 "ppn_in" => "ppn_in",
//                                 "ppn_in_nilai" => "ppn_in_nilai",
//                                 "suppliers_id" => "suppliers_id",
//                                 "suppliers_nama" => "suppliers_nama",
//                             ),
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "bruto",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//                 "referensi_id" => "referenceID",
//
//                 "pembayaran" => "paymentMethod",
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//
//             "rsltItems" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//             "rsltItemsValues" => array(
//                 "harga" => "harga",
//                 "hpp" => "hpp",
//                 "ppn" => "ppn",
//                 "nett" => "nett",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//             "detail_rsltItems" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//
//         "components" => array(
//             "960" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk" => "-hpp",
//                             "persediaan produk riil" => "hpp_riil",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk" => "-hpp",
//                             "persediaan produk riil" => "hpp_riil",
//                             "hutang lain ppv" => "-ppv_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //<editor-fold desc="Com-jurnal dan rekening">
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "persediaan produk riil" => "-hpp_riil",
//                             "piutang pembelian" => "exchange__nett",
//                             "ppn in" => "-ppn",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-exchange__nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "persediaan produk riil" => "-hpp_riil",
//                             "piutang pembelian" => "exchange__nett",
//                             "ppn in" => "-ppn",
//                             "laba(rugi) selisih fifo return pembelian" => "(hpp_riil+ppn)-exchange__nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//
//                     //<editor-fold desc="Com-rekening pembantu">
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "piutang pembelian" => "exchange__nett",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "ppn in" => "-ppn",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             //                            "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //</editor-fold>
//                 ),
//                 "detail" => array(
//                     //<editor-fold desc="Post-rekening pembantu, detail">
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk" => "-sub_hpp",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk riil" => "sub_hpp_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProduk",
//                         "loop" => array(
//                             "persediaan produk riil" => "-sub_hpp_riil",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "produk_qty" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "srcGateName" => "rsltItems",
//                         "srcRawGateName" => "rsltItems",
//                     ),
//                     //</editor-fold>
//
//                 ),
//             ),
//         ),
//         "postProcessor" => array(
//             "960r" => array(
//                 "master" => array(),
//                 "detail" => array(
//                     array(
//                         "comName" => "TransaksiItemReturnUpdate",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_jenis" => ".produk",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "transaksi_id" => "referenceID",
//                             "seluruhnya" => "seluruhnya",
//                             "returnMethod" => "pihakMainName", // by pass diisi metode per-barang atau per-nota
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".active",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => ".0",
//                             "nomer" => ".0",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".hold",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => ".0",
//                             "transaksi_id" => "transaksi_id",
//                             "nomer" => "nomer",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "960" => array(
//                 "master" => array(
//                     // post procc payment anti source
//                     array(
//                         "comName" => "PaymentAntiSourceValas",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "transaksi_id" => ".0",
//                             "jenis" => ".0",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".piutang pembelian",
//                             "sisa" => "exchange__nett",
//                             "sisa_valas" => "nett",
//                             "valas_id" => "currencyDetails",
//                             "valas_nama" => "currencyDetails__label",
//                             "target_jenis" => ".4891",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     // fifo valas dari return pembelian avg dan riil
//                     array(
//                         "comName" => "FifoValasExternReturnAverage",
//                         "loop" => array(),
//                         "static" => array(
//                             "jenis" => ".valas",
//                             "produk_id" => "pihak2ID",
//                             "nama" => "currencyDetails__label",
//                             "jml" => "nett", // jumlah valas
//                             "hpp" => "pihak2Exchange", // kurs valas dari nota grn
//                             "jml_nilai" => "exchange__nett",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => ".0",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "FifoValasExternReturn",
//                         "loop" => array(),
//                         "static" => array(
//                             "jenis" => ".valas",
//                             "produk_id" => "pihak2ID",
//                             "produk_nama" => "currencyDetails__label",
//                             "unit" => "nett",
//                             "hpp" => "pihak2Exchange",
//                             "jml_nilai" => "exchange__nett",
//                             "cabang_id" => "placeID",
//                             "gudang_id" => ".0",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".hold",
//                             "jumlah" => "-qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => "masterID",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     array(
//                         "comName" => "LockerStock",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => ".produk",
//                             "state" => ".deactivated",
//                             "jumlah" => "qty",
//                             "produk_id" => "id",
//                             "nama" => "name",
//                             "satuan" => "satuan",
//                             "transaksi_id" => ".0",
//                             "oleh_id" => ".0",
//                             "oleh_nama" => "",
//                             "gudang_id" => "gudangID",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                     // locker stok mutasi
//                     array(
//                         "comName" => "LockerStockMutasi",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "id",
//                             "extern_nama" => "name",
//                             "qty_debet" => "-qty",
//                             "produk_nilai" => "hpp",
//                             "gudang_id" => "gudangID",
//                             "jenis" => "jenisTr",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//         ),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//
//     ),
//     "1960" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "ppn" => "(ppnFactor*harga)/100",
//                 "hpp_nppn" => "harga+ppn",
//                 "hpp_nppv" => "harga*ppv_index__nilai",
//                 "ppv" => "hpp_nppv-harga",
//                 "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                 "nett" => "harga+ppn", // yg dipakai di grand total
//             ),
//             "master_dependent" => array(
//                 "paymentMethod" => array(
// //                    "cash" => array(
// //                        "nilai_cash" => "tagihan",
// //                        "nilai_credit" => "0",
// //                    ),
//                     "credit" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cbd" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cia" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "tt_adv" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                 ),
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "nett",
//             "tagihan" => "grand_total-discount-dp",
//         ),
//         "preProcessor" => array(),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "nett",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//
//             ),
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "produk_kode",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//             ),
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "produk",
//             ),
//         ),
//
//         "components" => array(),
//         "postProcessor" => array(),
//         //-----
//         "countersEdit" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaEdit" => "stepCode|placeID",
//         "countersReject" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//
//             "stepCode|masterID",
//             "stepCode|masterID|placeID",
//             "stepCode|masterID|olehID",
//             "stepCode|masterID|placeID|olehID",
//             "stepCode|masterID|supplierID",
//         ),
//         "formatNotaReject" => "stepCode|placeID",
//
//     ),
//
//     // config po jasa projek
//     "3463" => array(
//         "counters" => array(
//             "stepCode|placeID",
//             "stepCode|olehID",
//             "stepCode|placeID|olehID",
//             "stepCode|supplierID",
//         ),
//         "formatNota" => "stepCode|placeID",
//         "valueGates" => array(//==sumber nilai yang dikirim kemana2
//             "master" => array(//==sumber nilai utama
//                 "supplierID" => "pihakID",
//                 "supplierName" => "pihakName",
//                 "place2ID" => "branch",
//                 "place2Name" => "branch__label",
//                 "customerID" => "customerProjek",
//                 "customerName" => "customerProjek__label",
// //                "transaksi_id_target" => "transaksiData",
// //                "transaksi_nomer_target" => "transaksiData__label",
//             ),
//             "detail" => array(//===sumber nilai berupa rincian
//                 "disc" => "(discPersen*harga)/100",
//                 "harga_disc" => "harga-disc",
//                 "dppPPn" => "harga_disc*(dpp_persen/100)",
//                 "dppPPh" => "harga_disc*pph",
//                 "ppn_persen" => ".10",
//                 "ppn" => "(ppn_persen/100)*dppPPn",
//                 "hpp_nppn" => "harga_disc+ppn",
//                 "nett" => "hpp_nppn",
//                 "max_dpp_persen" => ".100",
//             ),
//             "master_dependent" => array(
//                 "paymentMethod" => array(
//                     "credit" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cbd" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "cia" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                     "tt_adv" => array(
//                         "nilai_credit" => "tagihan",
//                         "nilai_cash" => "0",
//                     ),
//                 ),
//             ),
//         ),
//         "valueBuilders" => array(
//             "grand_total" => "nett",
//             "tagihan" => "grand_total-discount-dp",
//             "ppn_value" => "nilai_dpp_ppn*ppnFactor/100",
//             "payment_out" => "nett",
//             "dppPph_dipakai" => "valid_pph_key*dppPPh",
//
//         ),
//         "preProcessor" => array(
//             "3463" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "ppn",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".active",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "tagihan-nilai_dipakai_ppn_in",
// //                            "transaksi_id" => "masterID",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                             "paymentMethod" => "paymentMethod",
//                         ),
//                         "resultParams" => array(
//                             "main" => array(
//                                 "nilai_dipakai" => "nilai_dipakai",
//                                 "nilai_tambah" => "nilai_tambah",
//                             ),
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "tableIn" => array(
//             "master" => array(
//                 "jenis_master" => "jenisTrMaster",
//                 "jenis_top" => "jenisTrTop",
//                 "jenis" => "jenisTr",
//                 "jenis_label" => "jenisTrName",
//                 "div_id" => "divID",
//                 "div_nama" => "divName",
//                 "dtime" => "dtime",
//                 "fulldate" => "fulldate",
//                 "oleh_id" => "olehID",
//                 "oleh_nama" => "olehName",
//
//                 "suppliers_id" => "supplierID",
//                 "suppliers_nama" => "supplierName",
//                 "customers_id" => "customerID",
//                 "customers_nama" => "customerName",
//
//                 "cabang_id" => "placeID",
//                 "cabang_nama" => "placeName",
//                 "transaksi_nilai" => "harga",
//                 "transaksi_jenis" => "jenisTr",
//                 "keterangan" => "description",
//
//                 "gudang_id" => "gudangID",
//                 "gudang_nama" => "gudangName",
//             ),
//
//             "detail" => array(
//                 "dtime" => "dtime",
//                 "produk_id" => "id",
//                 "produk_kode" => "code",
//                 "produk_label" => "label",
//                 "produk_nama" => "name",
//                 "produk_ord_jml" => "qty",
//                 "produk_ord_hrg" => "harga",
//                 "satuan" => "satuan",
//                 "keterangan" => "note",
//             ),
//
//         ),
//         "tableIn_static" => array(
//             "master" => array(
//                 "trash" => 0,
//             ),
//             "detail" => array(
//                 "trash" => 0,
//                 "produk_jenis" => "service",
//             ),
//         ),
//
//         "components" => array(
//             "3463" => array(
//                 "master" => array(
//                     //region PO PUSAT
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                             "1010040070" => "nilai_tambah_ppn_in",
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                             "1010040070" => "nilai_tambah_ppn_in",
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "piutang pembelian" => "-nilai_dipakai_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "hutang dagang" => "nilai_tambah_piutang_pembelian",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "1010040070" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "RekeningPembantuProjek",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "customerID",// konsumen
//                             "extern_nama" => "customerName",// konsumen
// //                            "extern2_id" => "transaksi_id_target",// so
// //                            "extern2_nama" => "transaksi_nomer_target",// so
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     //region PUSAT, PINDAH PROJEK COST
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                             "piutang cabang" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                             "piutang cabang" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuAntarcabang",
//                         "loop" => array(
//                             "piutang cabang" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang2_id" => "place2ID",
//                             "cabang2_nama" => "place2Name",
//                             "extern_id" => "place2ID",
//                             "extern_nama" => "place2Name",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProjek",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "customerID",// konsumen
//                             "extern_nama" => "customerName",// konsumen
// //                            "extern2_id" => "transaksi_id_target",// so
// //                            "extern2_nama" => "transaksi_nomer_target",// so
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion PUSAT, PINDAH PROJEK COST
//
//                     //region CABANG, TERIMA PROJEK COST
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                             "hutang ke pusat" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                             "hutang ke pusat" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuAntarcabang",
//                         "loop" => array(
//                             "hutang ke pusat" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "cabang2_id" => "place2ID",
//                             "cabang2_nama" => "place2Name",
//                             "extern_id" => "placeID",
//                             "extern_nama" => "placeName",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProjek",
//                         "loop" => array(
//                             "projek cost" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "extern_id" => "customerID",// konsumen
//                             "extern_nama" => "customerName",// konsumen
// //                            "extern2_id" => "transaksi_id_target",// so
// //                            "extern2_nama" => "transaksi_nomer_target",// so
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//
//                     //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                             "hpp projek" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                             "hpp projek" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuProjek",
//                         "loop" => array(
//                             "projek cost" => "-harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "extern_id" => "customerID",// konsumen
//                             "extern_nama" => "customerName",// konsumen
// //                            "extern2_id" => "transaksi_id_target",// so
// //                            "extern2_nama" => "transaksi_nomer_target",// so
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuHpp",
//                         "loop" => array(
//                             "hpp projek" => "harga_disc",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "place2ID",
//                             "extern_id" => "customerID",// customer projek
//                             "extern_nama" => "customerName",// customer projek
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//                 ),
//                 "detail" => array(),
//             ),
//             "3113" => array(
//                 "master" => array(
//                     //region seleish ppn 10 vs 11 %
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "1010040070" => "-selisih_ppn_realisasi",
//                             "hutang dagang" => "-selisih_ppn_realisasi",
//
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "1010040070" => "-selisih_ppn_realisasi",
//                             "hutang dagang" => "-selisih_ppn_realisasi",
//
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "hutang dagang" => "-selisih_ppn_realisasi",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "1010040070" => "-selisih_ppn_realisasi",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     //endregion
//                     array(
//                         "comName" => "Jurnal",
//                         "loop" => array(
//                             "1010040070" => "-nilai_tambah_ppn_in",
//                             "ppn in realisasi" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Rekening",
//                         "loop" => array(
//                             "1010040070" => "-nilai_tambah_ppn_in",
//                             "ppn in realisasi" => "nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "RekeningPembantuSupplier",
//                         "loop" => array(
//                             "1010040070" => "-nilai_tambah_ppn_in",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "jenis" => "jenisTr",
//                             "transaksi_no" => "nomer",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//         "postProcessor" => array(
//             "3463ro" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".1",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//             "3463o" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".2",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(
//                     array(
//                         "comName" => "PriceSupplies",
//                         "loop" => array(),
//                         "static" => array(
//                             "produk_id" => "id",
//                             "produk_nama" => "name",
//                             "nilai" => "harga",
//                             "cabang_id" => "placeID",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => ".jasa",
//                             "jenis_value" => ".hpp",
//                         ),
//                         "srcGateName" => "items",
//                         "srcRawGateName" => "items",
//                     ),
//                 ),
//             ),
//             "3463" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".ppn in",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_ppn_in",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "LockerValue",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "gudang_id" => "gudangID",
//                             "state" => ".hold",
//                             "jenis" => ".piutang pembelian",
//                             "produk_id" => "pihakID",
//                             "nama" => "pihakName",
//                             "nilai" => "-nilai_dipakai_piutang_pembelian",
//                             "transaksi_id" => "currentID",
//                             "oleh_id" => ".0",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".3",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//             "3113" => array(
//                 "master" => array(
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
// //                            "target_jenis" => "jenisTr",
//                             "jenis" => ".463",
//                             "transaksi_id" => "currentID",
//                             "ppn_approved" => "nilai_tambah_ppn_in",
// //                            "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "PaymentSource",
//                         "loop" => array(),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "extern_id" => "pihakID",
//                             "extern_nama" => "pihakName",
//                             "label" => ".hutang dagang",
// //                            "target_jenis" => ".483",
//                             "jenis" => ".463",
//                             "transaksi_id" => "currentID",
//                             "terbayar" => "selisih_ppn_realisasi",
//                             "sisa" => "new_sisa",
//                         ),
//                         "reversable" => true,
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activity",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".4",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                     array(
//                         "comName" => "Jurnal_activityMain",
//                         "loop" => array(
//                             "activity" => ".1",
//                         ),
//                         "static" => array(
//                             "cabang_id" => "placeID",
//                             "cabang_nama" => "placeName",
//                             "cabang2_id" => "placeID",
//                             "cabang2_nama" => "placeName",
//                             "oleh_id" => "olehID",
//                             "oleh_nama" => "olehName",
//                             "jenis" => "jenisTr",
//                             "jenis_master" => "jenisTrMaster",
//                             "jenis_top" => "jenisTrTop",
//                             "master_id" => "transaksi_id",
//                             "step_number" => ".4",
// //                            "step_number" => "step_number",
//                             "nilai" => ".1",
//                         ),
//                         "srcGateName" => "main",
//                         "srcRawGateName" => "main",
//                     ),
//                 ),
//                 "detail" => array(),
//             ),
//         ),
//     ),


);