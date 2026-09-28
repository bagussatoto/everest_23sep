<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(
    "6698" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
//        "formatNota" => "stepCode,fulldate,stepCode|fulldate,placeID,stepCode|placeID,olehID,stepCode|olehID,pihakID,stepCode|pihakID",
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "hpp" => "harga",
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
                //----------------------------
                "diskon_npph_nilai_total" => "diskon_nilai_total-diskon_pph23",
//                "laba_lain_lain" => "diskon_pph23",
                "laba_lain_lain" => "diskon_nilai_total",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
//                    "cash" => array(
//                        "nilai_cash" => "tagihan",
//                        "nilai_credit" => "0",
//                    ),
                    "credit" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cbd" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "cia" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                    "tt_adv" => array(
                        "nilai_credit" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
        ),
        "preProcessor" => array(
            "6698s" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "gate_source" => ".items",
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
                "transaksi_nilai" => "nett",
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
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "produk",
            ),
        ),
        "components" => array(
            "6698" => array(
                "master" => array(

                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "harga_produk",//persediaan produk
                            "3010020" => "harga_produk",//modal
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "harga_produk",//persediaan produk
                            "3010020" => "harga_produk",//modal
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga_produk",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => ".0",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),


                ),
            ),
        ),
        "postProcessor" => array(
            "6698s" => array(
                "master" => array(),
                "detail" => array(
                    // serial number produk
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_serial_number" => "serial_number",
                            "produk_sku" => "produk_sku",
                            "produk_sku_serial" => "produk_sku_serial",
                            "produk_sku_part_id" => "produk_sku_part_id",
                            "produk_sku_part_nama" => "produk_sku_part_nama",
                            "produk_sku_part_serial" => "produk_sku_part_serial",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "supplier_id" => "supplierID",
                            "supplier_nama" => "supplierName",
                            "gudang_id" => "pihakID",
                            //---------------
                            "transaksi_reference_id" => "referenceID",
                            "transaksi_reference_no" => "referenceNomer",
                            "transaksi_reference_dtime" => "referenceDate",
                            "transaksi_reference_fulldate" => "referenceFulldate",
                            "transaksi_reference_count" => "referenceCount",
                            "transaksi_count" => "transaksi_count",
                            "transaksi_jenis_count" => "transaksi_jenis_count",
                            "part_keterangan" => "part_keterangan",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "6698" => array(
                "master" => array(),
                "detail" => array(
                    // menambah persediaan full
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_produk",
                            "jml_nilai" => "sub_harga_produk",
                            "hpp_riil" => "harga_produk",
                            "jml_nilai_riil" => "sub_harga_produk",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "pihakID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // locker stok reguler
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
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
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
                            "produk_nilai" => "hpp_produk",
                            "gudang_id" => "pihakID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                ),
            ),

        ),

        "closedRequest" => array(
            "6698" => array(
                "enabled" => true,
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

);