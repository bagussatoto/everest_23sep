<?php

$config["coTransaksiCore"] = array(
    // barang terjual
    "3311" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // cash/tunai
                    "1" => array(
                        "nilai_piutang" => "0",
                        "nilai_cash" => "harga",
                    ),
                    // deposit/piutang pembelian
                    "2" => array(
                        "nilai_piutang" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "pendapatan_lain_lain" => "harga",
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
            "3311" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                            "1010020030" => "nilai_piutang",// piutang pembelian
                            "7010170" => "pendapatan_lain_lain",// pendapatan lain-lain
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
                            "1010010010" => "nilai_cash",// kas
                            "1010020030" => "nilai_piutang",// piutang pembelian
                            "7010170" => "pendapatan_lain_lain",// pendapatan lain-lain
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
                            "1010020030" => "nilai_piutang",// piutang pembelian
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010020030" => "nilai_cash",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
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
            "3311" => array(
                "master" => array(
                    // locker kas
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
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang pembelian",
                            "sisa" => "nilai_piutang",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),

        "closedRequest" => array(
            "466" => array(
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
    // stok tersisa
    "3322" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
                "persediaan_produk" => "harga",
            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // cash/tunai
                    "1" => array(
                        "nilai_piutang" => "0",
                        "nilai_cash" => "harga",
                    ),
                    // deposit/piutang pembelian
                    "2" => array(
                        "nilai_piutang" => "harga",
                        "nilai_cash" => "0",
                    ),
                ),
            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "persediaan_produk" => "harga",
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
            "3322" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                            "1010020030" => "nilai_piutang",// piutang pembelian
                            "1010030030" => "-persediaan_produk",// pendapatan lain-lain
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
                            "1010010010" => "nilai_cash",// kas
                            "1010020030" => "nilai_piutang",// piutang pembelian
                            "1010030030" => "-persediaan_produk",// pendapatan lain-lain
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
                            "1010020030" => "nilai_piutang",// piutang pembelian
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010020030" => "nilai_cash",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
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
                            "1010030030" => "-sub_persediaan_produk",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => ".0",
                            "produk_nilai" => "persediaan_produk",
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
            "3322" => array(
                "master" => array(
                    // locker kas
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
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // post procc payment anti source
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "label" => ".piutang pembelian",
                            "sisa" => "nilai_piutang",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => ".0",
                            "produk_id" => "id",
                            "hpp" => "harga",
                            "jml_nilai" => "-sub_persediaan_produk",
//                            "hpp_riil" => "harga",
//                            "jml_nilai_riil" => "sub_harga",
//                            "ppv_riil" => "ppv",
//                            "ppv_nilai_riil" => "sub_ppv",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
//                            "ppn_in" => "ppn",
//                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),

        "closedRequest" => array(
            "466" => array(
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

    // realisasi klaim dari piutang ke kas/credit note
    "3333" => array(
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
//                "pph23Methode__tarif" => "pph23MethodeTarif__tarif",

            ),
            "detail" => array(//===sumber nilai berupa rincian
//                "ppn" => "(ppnFactor*harga)/100",
//                "hpp_nppn" => "harga+ppn",
////                "hpp_nppv" => "harga*ppv_index__nilai",
////                "ppv" => "hpp_nppv-harga",
//                "hpp_nppv" => ".0",
//                "ppv" => ".0",
//                "hpp_nppn_nppv" => "hpp_nppn+ppv",
//                "nett" => "harga+ppn", // yg dipakai di grand total

//                "subtotal" => "qty*diskon_supplier_nilai",//diskon_supplier_nilai->ini sudah subtotal
                "subtotal" => "qty*harga",
                "diskon_supplier_nilai" => "harga",//nilai perunit karena akan dikali qty/jml jadi dihitung lagi
//                "nilai_items_pph23" => "((pph23Methode__tarif)/100)*diskon_supplier_nilai",

                "nilai_items_pph23" => "((pph23Methode__tarif)/100)*harga",
                "produk_rel_harga_after_pph" => "harga-nilai_items_pph23",
            ),
            "master_dependent" => array(
//                "pph23Methode" => array(
//                    // dipotong pph23
//                    "1" => array(
//                        "pph23_tarif" => "15",
//
//                    ),
//                    // tidak dipotong pph23
//                    "2" => array(
//                        "pph23_tarif" => "0",
//                    ),
//                ),
                "kompensasiMethod" => array(
                    // cash/tunai
                    "1" => array(
                        "pph_pembagi" => ".100",
                        "cek_pph" => "(pph_pembagi-pph23Methode__tarif)/pph_pembagi",
                        "nilai_persediaan" => ".0",
                        "nilai_piutang" => "diskon_supplier_nilai",
//                        "nilai_cash" => "((100-pph23Methode__tarif)/100)*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_cash" => "cek_pph*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_logam_mulia" => ".0",
                        "nilai_voucher" => ".0",
                        "nilai_credit_note" => ".0",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "grandtotal_netto" => "nilai_cash",
                        "nilai_hutang_pph23" => ".0",
                    ),
                    // credit note
                    "2" => array(
                        "pph_pembagi" => ".100",
                        "cek_pph" => "(pph_pembagi-pph23Methode__tarif)/pph_pembagi",
                        "nilai_persediaan" => ".0",
                        "nilai_piutang" => "diskon_supplier_nilai",
//                        "nilai_credit_note" => "((100-pph23Methode__tarif)/100)*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_credit_note" => "cek_pph*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_voucher" => ".0",
                        "nilai_cash" => ".0",
                        "nilai_logam_mulia" => ".0",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "grandtotal_netto" => "nilai_credit_note",
                        "nilai_hutang_pph23" => ".0",
                    ),
                    // voucher
                    "3" => array(
                        "nilai_persediaan" => ".0",
                        "nilai_piutang" => "diskon_supplier_nilai",
                        "nilai_credit_note" => ".0",
                        "nilai_voucher" => "diskon_supplier_nilai",
                        "nilai_cash" => ".0",
                        "nilai_logam_mulia" => ".0",
                        "nilai_pph23" => ".0",
                        "grandtotal_netto" => "diskon_supplier_nilai",
                        "nilai_hutang_pph23" => ".0",
                    ),
                    // persediaan
                    "4" => array(
                        "pph_pembagi" => ".100",
                        "cek_pph" => "(pph_pembagi-pph23Methode__tarif)/pph_pembagi",
//                        "nilai_persediaan" => "((100-pph23Methode__tarif)/100)*diskon_supplier_nilai",
                        "nilai_persediaan" => "cek_pph*diskon_supplier_nilai",
                        "nilai_piutang" => "diskon_supplier_nilai",
                        "nilai_credit_note" => ".0",
                        "nilai_voucher" => ".0",
                        "nilai_cash" => ".0",
                        "nilai_logam_mulia" => ".0",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*diskon_supplier_nilai",
                        "grandtotal_netto" => "nilai_persediaan",
                        "nilai_hutang_pph23" => ".0",
                    ),
                    // logam mulia dan permata
                    "5" => array(
                        "pph_pembagi" => ".100",
                        "cek_pph" => "(pph_pembagi-pph23Methode__tarif)/pph_pembagi",
                        "nilai_pph23" => "((pph23Methode__tarif)/pph_pembagi)*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_dibayar_sendiri" => "diskon_supplier_nilai*pph23Methode__kode_bayar_sendiri",
                        "nilai_termasuk_klaim" => "cek_pph*diskon_supplier_nilai*pph23Methode__kode_termasuk_klaim",
                        "nilai_pph23_dibayar_sendiri" => "nilai_pph23*pph23Methode__kode_bayar_sendiri",

                        "nilai_persediaan" => ".0",
                        "nilai_piutang" => "diskon_supplier_nilai",
                        "nilai_cash" => "nilai_pph23_dibayar_sendiri*(-1)",// jumlah uang yang ditransfer ke supplier sebesar pph yang tanggung sendiri 15%
                        "nilai_logam_mulia" => "nilai_dibayar_sendiri+nilai_termasuk_klaim",//
//                        "nilai_logam_mulia" => "diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
//                        "nilai_logam_mulia" => "cek_pph*diskon_supplier_nilai",// pph23 nya masih ditembak langsung disini, perlu diganti
                        "nilai_voucher" => ".0",
                        "nilai_credit_note" => ".0",
                        "grandtotal_netto" => "nilai_logam_mulia",
                        "nilai_hutang_pph23" => ".0",
//                        "nilai_hutang_pph23" => "nilai_pph23",
                    ),
                ),

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "nett",
            "tagihan" => "grand_total-discount-dp",
            "pendapatan_lain_lain" => "harga",
        ),
        "preProcessor" => array(
            "3333" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractorRealiasiDiskon",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "kompensasiMethod" => "kompensasiMethod",
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

                "reference_jenis" => "pihakMainReferenceJenis",
                "reference_id" => "pihakMainID",
                "reference_nomer" => "pihakMainName",

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
            "3333" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                            "1010010030" => "nilai_credit_note",// credit note
                            "1010020030" => "-nilai_piutang",// piutang pembelian
                            "1010040030" => "nilai_pph23",// pph23 dibayar dimuka
                            "1010010050" => "nilai_voucher",// voucher
                            "1010030030" => "nilai_persediaan",//persediaan produk
                            "1010025010" => "nilai_logam_mulia",//logam mulia dan permata
                            "2030030" => "nilai_hutang_pph23",// hutang pph23
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
                            "1010010010" => "nilai_cash",// kas
                            "1010010030" => "nilai_credit_note",// credit note
                            "1010020030" => "-nilai_piutang",// piutang pembelian
                            "1010040030" => "nilai_pph23",// pph23 dibayar dimuka
                            "1010010050" => "nilai_voucher",// voucher
                            "1010030030" => "nilai_persediaan",//persediaan produk
                            "1010025010" => "nilai_logam_mulia",//logam mulia dan permata
                            "2030030" => "nilai_hutang_pph23",// hutang pph23
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
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010010050" => "nilai_voucher",// voucher
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

                    //------------------------------------
                    array(
                        "comName" => "RekeningPembantuCreditNote",
                        "loop" => array(
                            "1010010030" => "nilai_credit_note",// credit note
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "vendorRebate",
//                            "extern_nama" => "vendorRebate__label",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "RekeningPembantuCreditNoteDetail",
//                        "loop" => array(
//                            "1010010030" => "nilai_credit_note",// piutang pembelian
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "extern2_id" => "vendorRebate",
////                            "extern2_nama" => "vendorRebate__label",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "extern_id" => "vendorRebate",
//                            "extern_nama" => "vendorRebate__label",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    array(
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "-nilai_piutang",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "vendorRebate",
//                            "extern_nama" => "vendorRebate__label",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierDetailMain",
//                        "loop" => array(
//                            "1010020030" => "-nilai_piutang",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "extern2_id" => "vendorRebate",
////                            "extern2_nama" => "vendorRebate__label",
////                            "extern_id" => "pihakMainID",
////                            "extern_nama" => "pihakMainLabel",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "pihakMainID_diskon",
//                            "extern_nama" => "pihakMainLabel_diskon",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //bagian rekening pembantu logam mulia------------------------------------
                    array(
                        "comName" => "RekeningPembantuLogamMulia",
                        "loop" => array(
                            "1010025010" => "nilai_logam_mulia",//logam mulia dan permata
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "extern_id" => "logam_mulia",
                            "extern_nama" => "logam_mulia__label",
                            "produk_qty" => "logam_mulia__qty",
                            "produk_nilai" => "nilai_logam_mulia/logam_mulia__qty",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //bagian fifo logam mulia, berjalan di gerabng Main------------------------------------
                    array(
                        "comName" => "FifoLogamMuliaMain",
                        "loop" => array(
                            "exec" => "nilai_logam_mulia",
                        ),
                        "static" => array(
                            "jenis" => ".logam_mulia",
//                            "jml" => "logam_mulia__qty",
                            "unit" => "logam_mulia__qty",
                            "produk_id" => "logam_mulia",
                            "nama" => "logam_mulia__nama",
                            "hpp" => "nilai_logam_mulia/logam_mulia__qty",
                            "jml_nilai" => "nilai_logam_mulia",
                            "hpp_riil" => "nilai_logam_mulia/logam_mulia__qty",
                            "jml_nilai_riil" => "nilai_logam_mulia",
                            "ppv_riil" => ".0",
                            "ppv_nilai_riil" => ".0",
                            "hpp_nppv" => ".0",
                            "jml_nilai_nppv" => ".0",
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "ppn_in" => ".0",
                            "ppn_in_nilai" => ".0",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".logam_mulia",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // rekening pembantu hutang pph 23
                    array(
                        "comName" => "RekeningPembantuPphMain",
                        "loop" => array(
                            "2030030" => "nilai_hutang_pph23",// hutang pph23
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// id vender/supplier
                            "extern_nama" => "pihakName",// id vender/supplier
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_hutang_pph23",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
//                            "1010020030" => "-diskon_supplier_nilai",// piutang supplier
                            "1010020030" => "-sub_diskon_supplier_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "diskon_id",//
                            "extern_nama" => "diskon_nama",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
//                            "1010020030" => "-diskon_supplier_nilai",// piutang supplier
                            "1010020030" => "-sub_diskon_supplier_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "diskon_id",// jenis diskon
                            "extern2_nama" => "diskon_nama",// jenis diskon
                            "extern_id" => "pihakMainID",// transaksi id
                            "extern_nama" => "pihakMainName",// transaksi id
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
//                        "loop" => array(
////                            "1010020030" => "-sub_produk_rel_harga",// piutang uspplier yang sudah dikurangi pph nanti gak lunas kalau pakai gerbang ini
//                            "1010020030" => "-sub_diskon_supplier_nilai",// piutang supplier
//                        ),
//                        "static" => array(
//                            //extern_id diinject di model untuk ambil transaksi_id
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",// jenis diskon
//                            "extern_nama" => "diskon_nama",// jenis diskon
//                            "extern2_id" => "pihakID",// supplier
//                            "extern2_nama" => "pihakName",// supplier
//                            "extern3_id" => "extern_id",// produk yang dapet diskon (ac)
//                            "extern3_nama" => "extern_nama",// jenis diskon
//                            "extern4_id" => "produk_id",// hadiahnya produknya(kabel,selang)
//                            "extern4_nama" => "produk_nama",// jenis diskon
//                            "produk_qty" => "-qty",// jenis diskon
//                            "produk_nilai" => "diskon_supplier_nilai",// jenis diskon
//                        ),
//                        "srcGateName" => "items5_sum",
//                        "srcRawGateName" => "items5_sum",
//                    ),

                    // oakai items2_sum karena rekening pembantunya tidak diakumulasi.
                    // preval = diskon_id, supplier_id, produk_yang_dapat_bonus, produk_bonus.
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "-sub_harga",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "diskon_id",// jenis diskon
                            "extern_nama" => "diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "extern2_id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "extern2_nama",// produk yang dapet diskon (ac)
                            "extern4_id" => "extern_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "extern_nama",// hadiahnya produknya(kabel,selang)
                            "produk_qty" => "-jml",// jumlah barang hadiahnya
                            "produk_nilai" => "harga",// nilai diskon per-barangnya
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    // menambah persediaan karena persediaan pada piutang supplier
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_produk_rel_harga",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "produk_rel_harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    //locker
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(
                            "exec_locker" => "sub_produk_rel_harga",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
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
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "produk_rel_harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "3333" => array(
                "master" => array(
                    // locker kas
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
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // locker logam mulia
                    array(
                        "comName" => "LockerStockLogamMuliaMain",
                        "loop" => array(
                            "exec_locker" => "nilai_logam_mulia",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".logam_mulia",
                            "state" => ".active",
                            "jumlah" => "logam_mulia__qty",
                            "produk_id" => "logam_mulia",
                            "nama" => "logam_mulia__label",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // menambah persediaan karena persediaan pada piutang supplier
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".produk",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "produk_rel_harga",
                            "jml_nilai" => "sub_produk_rel_harga",
                            "hpp_riil" => "produk_rel_harga",
                            "jml_nilai_riil" => "produk_rel_harga",
                            "ppv_riil" => "ppv",
                            "ppv_nilai_riil" => "sub_ppv",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "ppn_in" => "ppn",
                            "ppn_in_nilai" => "sub_ppn",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "produk_jenis" => ".lokal",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    array(
                        "comName" => "ProdukSerialNumber",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => "jenisTr",
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
                            "gudang_id" => "gudangID",
                            //---------------
                            "transaksi_reference_id" => "refID",
//                            "transaksi_reference_no" => "referenceNomer",
//                            "transaksi_reference_dtime" => "dtime",
//                            "transaksi_reference_fulldate" => "fulldate",
                            "transaksi_reference_count" => "curent_transaksi_count",
                            "transaksi_count" => "curent_transaksi_count",
                            "transaksi_jenis_count" => "curent_transaksi_count",
                            "part_keterangan" => "part_keterangan",
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "-sub_harga",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "-jml",
//                            "jumlah" => "-qty",
                            "nilai" => "-sub_harga",
                            "nilai_diklaim" => "sub_harga",
                            "nilai2" => "produk_rel_harga",

//                            "produk_id" => "pihakMainID",//id diskon
//                            "produk_id" => "pihakMainID_diskon",//id diskon
//                            "nama" => "pihakMainName_diskon",
//                            "nama" => "pihakMainName",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",
                            "extern_id" => "extern_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "extern_nama",
                            "extern2_id" => "extern2_id",//produk yang dibeli
                            "extern2_nama" => "extern2_nama",//produk yang dibeli
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
//                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                            "refID" => "refID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                ),
            ),
        ),

        "closedRequest" => array(),

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

    // diskon supplier dadakan
    "3344" => array(
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
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
//                "hpp_nppv" => "harga*ppv_index__nilai",
//                "ppv" => "hpp_nppv-harga",
                "hpp_nppv" => ".0",
                "ppv" => ".0",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
            ),
            "master_dependent" => array(
                "kompensasiMethod" => array(
                    // cash/tunai
                    "1" => array(
                        "nilai_piutang" => "0",
                        "nilai_cash" => "harga_kompensasi",
                        "nilai_credit_note" => "0",
                    ),
                    // credit note
                    "2" => array(
                        "nilai_credit_note" => "harga_kompensasi",
                        "nilai_piutang" => "0",
                        "nilai_cash" => "0",
                    ),
                    // credit note
                    "3" => array(
                        "nilai_credit_note" => "0",
                        "nilai_piutang" => "harga_kompensasi",
                        "nilai_cash" => "0",
                    ),
                ),
                "kompensasiTargetMethod" => array(
                    // persediaan produk ( 1010030030 )
                    "1" => array(
//                        "nilai_persediaan" => "harga_kompensasi",
                        "nilai_persediaan" => 0,
                        "nilai_pendapatan_lain" => "harga_kompensasi",
                        "diskon_id" => ".7",
                        "diskon_nama" => ".free_produk",
                    ),
                    // pendapatan lain-lain ( 7010170 )
                    "2" => array(
                        "nilai_persediaan" => 0,
                        "nilai_pendapatan_lain" => "harga_kompensasi",
                        "diskon_id" => ".6",
                        "diskon_nama" => ".diskon tambahan",
                    ),
                ),
            ),
            "master_dependent_items" => array(
                "kompensasiTargetMethod" => array(
                    // persediaan produk ( 1010030030 )
                    "1" => array(
                        "nilai_persediaan_detail" => "harga_kompensasi",
                        "nilai_pendapatan_lain_detail" => 0,
                        "sub_nilai_persediaan_detail" => "sub_harga_kompensasi",
                        "sub_nilai_pendapatan_lain_detail" => 0,
                    ),
                    // pendapatan lain-lain ( 7010170 )
                    "2" => array(
                        "nilai_persediaan_detail" => 0,
                        "nilai_pendapatan_lain_detail" => "harga_kompensasi",
                        "sub_nilai_persediaan_detail" => 0,
                        "sub_nilai_pendapatan_lain_detail" => "sub_harga_kompensasi",
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
            "3344" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                            "1010010030" => "nilai_credit_note",// credit note
                            "1010020030" => "nilai_piutang",// piutang pembelian
//                            "1010030030" => "-nilai_persediaan",// persediaan
                            "7010170" => "nilai_pendapatan_lain",// pendapatan lain-lain
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
                            "1010010010" => "nilai_cash",// kas
                            "1010010030" => "nilai_credit_note",// credit note
                            "1010020030" => "nilai_piutang",// piutang pembelian
//                            "1010030030" => "-nilai_persediaan",// persediaan
                            "7010170" => "nilai_pendapatan_lain",// pendapatan lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu kas
                    array(
                        "comName" => "RekeningPembantuKas",
                        "loop" => array(
                            "1010010010" => "nilai_cash",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu piutang supplier, diskon tambahan
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nilai_piutang",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => "vendorRebate__coa_code",
//                            "extern_nama" => "vendorRebate__label",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu piutang supplier, diskon tambahan, supplierID
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailMain",
                        "loop" => array(
                            "1010020030" => "nilai_piutang",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => "vendorRebate__coa_code",
//                            "extern2_nama" => "vendorRebate__label",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "diskon_id",//
                            "extern_nama" => "diskon_nama",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransMain",
                        "loop" => array(
                            "1010020030" => "nilai_piutang",// piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern3_id" => "pihakID",
                            "extern3_nama" => "pihakName",
                            "extern2_id" => "diskon_id",
                            "extern2_nama" => "diskon_nama",
//                            "extern_id" => "transaksi_id",
//                            "extern_nama" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_nilai_persediaan_detail",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_id" => "masterID",
                            "transaksi_no" => "nomer",
                            "extern_id" => ".7",// jenis diskon
                            "extern_nama" => ".free_produk",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "nama",// jenis diskon
                            "extern4_id" => "id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "nama",// jenis diskon
                            "produk_qty" => "qty",// jenis diskon
                            "produk_nilai" => "nilai_persediaan_detail",// jenis diskon
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //diskon tambahan
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_nilai_pendapatan_lain_detail",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_id" => "masterID",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",// jenis diskon
//                            "extern_nama" => "diskon_nama",// jenis diskon
                            "extern_id" => ".6",// produk yang dapet diskon (ac)
                            "extern_nama" => ".diskon tambahan",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "id_x",// unix id
                            "extern3_nama" => "referensi_item_supplier",// referesnsi
                            "extern4_id" => "id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "nama",// jenis diskon
                            "produk_qty" => ".1",// jenis diskon
                            "produk_nilai" => "nilai_pendapatan_lain_detail",// jenis diskon
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty


                    // menambah kredit note supplier
//                    array(
//                        "comName" => "RekeningPembantuCreditNoteItem",
//                        "loop" => array(
//                            "1010010030" => "nilai_credit_note",// piutang pembelian
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "piutangSupplierDetail_coa",
//                            "extern_nama" => "piutangSupplierDetailName_coa",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),
                    // menambah kredit note supplier, + supplier id
//                    array(
//                        "comName" => "RekeningPembantuCreditNoteDetailItem",
//                        "loop" => array(
//                            "1010010030" => "nilai_credit_note",// piutang pembelian
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern2_id" => "piutangSupplierDetail_coa",
//                            "extern2_nama" => "piutangSupplierDetailName_coa",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),

                    // rekening pembantu piutang supplier, diskon supplier
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierItem",
//                        "loop" => array(
//                            "1010020030" => "piutang_supplier",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern_id" => "piutangSupplierDetail_coa",
//                            "extern_nama" => "piutangSupplierDetailName_coa",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),
                    // rekening pembantu piutang supplier, diskon supplier, supplier
//                    array(
//                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
//                        "loop" => array(
//                            "1010020030" => "piutang_supplier",// piutang supplier
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => "piutangSupplierDetail_coa",
//                            "extern2_nama" => "piutangSupplierDetailName_coa",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),

                    // rekening pembantu produk, mengurangi sebesar diskon supplier
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "-sub_nilai_persediaan_detail",//persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => ".0",
//                            "produk_nilai" => "-nilai_persediaan_detail",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "3344" => array(
                "master" => array(
                    // locker kas
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
                            "nilai" => "nilai_cash",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    // mengurangi persediaan, sebesar diskon konsumen
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "jenis" => ".produk",
//                            "jml" => ".0",
//                            "produk_id" => "id",
//                            "nama" => "name",
//                            "hpp" => "nilai_persediaan_detail",
//                            "jml_nilai" => "-sub_nilai_persediaan_detail",
//                            "hpp_riil" => "nilai_persediaan_detail",
//                            "jml_nilai_riil" => "-sub_nilai_persediaan_detail",
//                            "ppv_riil" => "ppv",
//                            "ppv_nilai_riil" => "sub_ppv",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "ppn_in" => "ppn",
//                            "ppn_in_nilai" => "sub_ppn",
//                            "suppliers_id" => "pihakID",
//                            "suppliers_nama" => "pihakName",
//                            "produk_jenis" => ".lokal",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    //locker diskon value produk
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_nilai_persediaan_detail",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "nilai" => "sub_nilai_persediaan_detail",
                            "nilai2" => "nilai_persediaan_detail",
                            "nilai_unit" => "nilai_persediaan_detail",

                            "produk_id" => ".7",//id diskon
                            "nama" => ".free_produk",

                            "extern_id" => "id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
//                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                            "refID" => "referenceID",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //untuk locker value diskon
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_nilai_pendapatan_lain_detail",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "nilai" => "sub_nilai_pendapatan_lain_detail",
                            "nilai2" => "nilai_pendapatan_lain_detail",
                            "nilai_unit" => "nilai_pendapatan_lain_detail",

                            "produk_id" => ".6",//id diskon
                            "nama" => ".diskon_tambahan",

                            "extern_id" => ".6",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => ".diskon_tambahan",
                            "extern2_id" => "id_x",//produk yang dibeli
                            "extern2_nama" => "referensi_item_supplier",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
//                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",

                            "refID" => "referenceID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),


                ),
            ),
        ),

        "closedRequest" => array(),

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


