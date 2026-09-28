<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(
    //config return (juranl manual non stok)
    "9911" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                //                "transaksi_nilai" => "srcDefValue",
                "transaksi_nilai" => "nilai_cancel",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
            ),
            "detailValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
        "components" => array(//kosong baca dari builder helper lihat he_accounting
        ),
        "relativeComponets" => true,//untuk baca dari session builder helper relative jurnal
        "postProcessor" => array(//kosong baca dari builder helper lihat he_accounting

        ),
        "postProcessorRequestRevert" => array(//kosong baca dari builder helper lihat he_accounting
            "19467" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "place2Name",
                            "transaksi_id" => ".0",
//                            "jenis" => "uangMuka__jenis",
                            "extern_id" => "customerDetails",
                            "extern_nama" => "customerDetails__nama",
                            "label" => ".uang muka konsumen",
                            "terbayar" => "-harga_2010050050",
                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "PaymentAntiSource",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "place2Name",
                            "transaksi_id" => ".0",
                            "jenis" => ".0",
                            "extern_id" => "customerDetails",
                            "extern_nama" => "customerDetails__nama",
                            "label" => ".piutang dagang",
                            "terbayar" => "-harga_2010050040",
//                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "TransaksiPengembalianUangReference",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang_nama" => "place2Name",
//                            "transaksi_id" => ".0",
//                            "transaksi_no" => ".0",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "jumlah" => ".1",
                            "referensi_id" => "referensi_so",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "nilai" => "-harga",
                            "jenis_reference" => ".4464",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "1757" => array(
                "master" => array(
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => ".0",
                            "state" => ".hold",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "-harga",
                            "transaksi_id" => "referenceID_top",
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
                            "gudang_id" => ".0",
                            "state" => ".active",
                            "jenis" => ".kas",
                            "produk_id" => "cash_account_source",
                            "nama" => "cash_account_source__label",
                            "nilai" => "harga",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "componentsGantiRekening" => array(
            "19467" => array(
                "key" => "harga_2010050010",// nilainya lebih dari 0
                "core" => array(
                    "master" => array(
                        // mengeluarkan um konsumen tunai/dengan relasi
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "-harga_2010050010",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "extern_id" => ".2010050010",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                                // "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "-harga_2010050010",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "extern_id" => "customerID",
                                "extern_nama" => "customerName",
                                "extern2_id" => ".2010050010",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                                // "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // menambah um konsumen non relasi
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "harga_2010050010",// hutang ke konsumen Uang Muka Konsumen Tanpa Ppn
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "extern_id" => ".2010050050",// Uang Muka Konsumen Tanpa Ppn
                                "extern_nama" => ".Uang Muka Konsumen Tanpa Ppn",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "harga_2010050010",// hutang ke konsumen Uang Muka Konsumen Tanpa Ppn
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "extern_id" => "customerID",
                                "extern_nama" => "customerName",
                                "extern2_id" => ".2010050050",// Uang Muka Konsumen Tanpa Ppn
                                "extern2_nama" => ".Uang Muka Konsumen Tanpa Ppn",
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
        ),
        "postProcessorGantiRekening" => array(
            "19467" => array(
                "key" => "harga_2010050010",// nilainya lebih dari 0
                "core" => array(
                    "master" => array(
                        array(
                            "comName" => "PaymentUangMukaCustomer",
                            "loop" => array(
                                "2010050" => "harga_2010050010",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "cabang_nama" => "place2Name",
                                "gudang_id" => ".0",
                                "extern_id" => "customerID",
                                "extern_nama" => "customerName",
                                "nilai" => "harga_2010050010",
                                "label" => ".uang muka",
                                "extern_label2" => ".customer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "2010050" => "harga_2010050010",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "place2ID",
                                "cabang_nama" => "place2Name",
                                "gudang_id" => ".0",
                                "transaksi_id" => ".0",
                                "extern_id" => "customerID",
                                "extern_nama" => "customerName",
                                "extern2_id" => ".0",
                                "extern2_nama" => "",
                                "tambah" => "harga_2010050010",
                                "label" => ".uang muka konsumen",
                                "extern_label2" => ".customer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                    ),
                    "detail" => array(),
                ),

            ),
        ),
    ),
    //config return (juranl manual non stok cabang)
    "9912" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

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

                "cabang2_id" => "placeID",
                "cabang2_nama" => "placeName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                //                "transaksi_nilai" => "srcDefValue",
                "transaksi_nilai" => "nilai_cancel",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "gudang2_id" => "gudang2ID",
                "gudang2_nama" => "gudang2Name",
            ),
            "mainValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
            ),
            "detailValues" => array(
                "debet" => "debet",
                "kredit" => "kredit",
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
        "components" => array(//kosong baca dari builder helper lihat he_accounting
        ),
        "relativeComponets" => true,//untuk baca dari session builder helper relative jurnal
        "postProcessor" => array(//kosong baca dari builder helper lihat he_accounting

        ),
        "componentsAwalGantiRekening" => array(
            "4467" => array(// uang muka konsumen tanpa relasi so
                "key" => "hutang_ke_konsumen",// nilainya lebih dari 0
                "tipeuangmuka" => "referensiNota",// titipan == 1
                "batastanggal" => "2026-01-13",
                "core" => array(
                    "master" => array(
                        //=========================
                        // PUSAT
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010060010" => "-hutang_ke_konsumen",// piutang cabang
                                "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010060010" => "-hutang_ke_konsumen",// piutang cabang
                                "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuAntarcabang",
                            "loop" => array(
                                "1010060010" => "-hutang_ke_konsumen",// piutang cabang
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang2_id" => "placeID",
                                "cabang2_nama" => "placeName",
                                "extern_id" => "placeID",
                                "extern_nama" => "placeName",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "-hutang_ke_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang_nama" => ".PUSAT",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "terbayar" => "hutang_ke_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),

                        // CABANG
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                                "2040010" => "-hutang_ke_konsumen",// hutang ke pusat
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
                                "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                                "2040010" => "-hutang_ke_konsumen",// hutang ke pusat
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
                                "2040010" => "-hutang_ke_konsumen",// hutang ke pusat
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang2_id" => ".-1",
                                "cabang2_nama" => ".PUSAT",
                                "extern_id" => ".-1",
                                "extern_nama" => ".PUSAT",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "hutang_ke_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "hutang_ke_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang_nama" => "placeName",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "tambah" => "hutang_ke_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        //=========================
                    ),
                    "detail" => array(),
                ),
            ),
            "4464" => array(// penerimaan penjualan tunai (ada lebih bayar)
                "key" => "deposit_konsumen",// nilainya lebih dari 0
                "tipeuangmuka" => ".1",// titipan == 1
                "batastanggal" => "2026-01-13",
                "core" => array(
                    "master" => array(
                        //=========================
                        // PUSAT
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuAntarcabang",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang2_id" => "placeID",
                                "cabang2_nama" => "placeName",
                                "extern_id" => "placeID",
                                "extern_nama" => "placeName",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "-deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang_nama" => ".PUSAT",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "terbayar" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),

                        // CABANG
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang2_id" => ".-1",
                                "cabang2_nama" => ".PUSAT",
                                "extern_id" => ".-1",
                                "extern_nama" => ".PUSAT",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang_nama" => "placeName",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "tambah" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        //=========================
                    ),
                    "detail" => array(),
                ),
            ),
            "749" => array(// penerimaan ar (ada lebih bayar)
                "key" => "deposit_konsumen",// nilainya lebih dari 0
                "tipeuangmuka" => ".1",// titipan == 1
                "batastanggal" => "2026-01-13",
                "core" => array(
                    "master" => array(
                        //=========================
                        // PUSAT
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuAntarcabang",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang2_id" => "placeID",
                                "cabang2_nama" => "placeName",
                                "extern_id" => "placeID",
                                "extern_nama" => "placeName",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "-deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang_nama" => ".PUSAT",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "terbayar" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),

                        // CABANG
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang2_id" => ".-1",
                                "cabang2_nama" => ".PUSAT",
                                "extern_id" => ".-1",
                                "extern_nama" => ".PUSAT",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang_nama" => "placeName",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "tambah" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        //=========================
                    ),
                    "detail" => array(),
                ),
            ),
            "7488" => array(// penerimaan ar (ada lebih bayar)
                "key" => "deposit_konsumen",// nilainya lebih dari 0
                "tipeuangmuka" => ".1",// titipan == 1
                "batastanggal" => "2026-01-13",
                "core" => array(
                    "master" => array(
                        //=========================
                        // PUSAT
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "Rekening",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "jenis" => "jenisTr",
                                "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "RekeningPembantuAntarcabang",
                            "loop" => array(
                                "1010060010" => "-deposit_konsumen",// piutang cabang
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang2_id" => "placeID",
                                "cabang2_nama" => "placeName",
                                "extern_id" => "placeID",
                                "extern_nama" => "placeName",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "-deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "-deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => ".-1",
                                "cabang_nama" => ".PUSAT",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "terbayar" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),

                        // CABANG
                        array(
                            "comName" => "Jurnal",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
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
                                "2040010" => "-deposit_konsumen",// hutang ke pusat
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang2_id" => ".-1",
                                "cabang2_nama" => ".PUSAT",
                                "extern_id" => ".-1",
                                "extern_nama" => ".PUSAT",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar)
                        array(
                            "comName" => "RekeningPembantuCustomer",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => ".2010050050",
                                "extern_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        // rekening pembantu hutang ke konsumen, uang muka (lebih bayar), konsumenID
                        array(
                            "comName" => "RekeningPembantuCustomerDetail",
                            "loop" => array(
                                "2010050" => "deposit_konsumen",// hutang ke konsumen
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "extern2_id" => ".2010050050",
                                "extern2_nama" => ".Uang Muka Konsumen",
                                "jenis" => "jenisTr",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        array(
                            "comName" => "PaymentUangMuka",
                            "loop" => array(
                                "xxx" => "deposit_konsumen",
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "cabang_nama" => "placeName",
                                "transaksi_id" => ".0",
                                "jenis" => "jenis",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                                "label" => ".uang muka konsumen",
                                "tambah" => "deposit_konsumen",
                                "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
                            ),
                            "reversable" => true,
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        //=========================
                    ),
                    "detail" => array(),
                ),
            ),
        ),
    ),
    // config penerimaan piutang customer (uang masuk dari konsumen)
    "9749" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama

                "customerID" => "pihakID",
                "customerName" => "pihakName",
                //                "refs" => "refs",
                //                "refs_intext" => "refs_intext",
            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),

        ),
        "valueBuilders" => array(
            //            "totalCredit" => "credit_note_dipakai+creditValue",
            //            "nilai_bayar" => "nilai_entry+totalCredit+nilai_biaya",
            //            "lebih_bayar" => "nilai_entry-harus_bayar",

            "nilai_entry" => "sisa",
            "nilai_bayar" => "nilai_entry",
            // "nilai_penghapusan"=>"nilai_entry",
            "lebih_bayar" => "nilai_entry-harus_bayar",
            "new_sisa" => "sisa-nilai_entry",
            "harus_bayar" => "sisa",
        ),
        "valuePopulator" => array(
            //            array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
            //        ),
        ),

        //        "additionalSource" => true,
        //        "additionalItemSourceKey" => array(
        //            "top" => "nilai_bayar",
        //            "bottom" => "tagihan",//harga_nett2
        //        ),
        //        "additionalItemSource" => array(
        //            "harga_nett2" => "tagihan",//harga_nett2
        //            "hpp" => "hpp",
        //            "ppn" => "ppn",
        //            "laba_kotor" => "tagihan-hpp",//harga_nett2-hpp
        //        ),
        //        "additionalItemResult" => array(
        //            "harga_nett2" => "tagihan",//harga_nett2
        //            "hpp" => "hpp",
        //            "ppn" => "ppn",
        //            "laba_kotor" => "laba_kotor",
        //        ),

        //        "populators" => array(
        //            "nilai_bayar" => array(
        //                "mainSrc" => array(
        //                    "key" => "nilai_bayar",
        //                ),
        //                "itemTarget" => array(
        //                    "key" => "nilai_bayar",
        //                    "maxAmountSrc" => "sisa",
        //                ),
        //            ),
        //        ),
        //        "additionalBuilders" => array(//==per-item
        //            "new_sisa" => "sisa-nilai_bayar",
        //        ),
        //        "additionalMainBuilders" => array(//==per-item
        //            "harus_bayar" => "sisa-totalCredit-nilai_biaya",
        //            //            "nilai_bayar" => "nilai_entry+totalCredit",
        //        ),

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

                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "nilai_bayar",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "bank_rekening_id" => "cash_id",
                "bank_rekening_nama" => "bank_rekening_nama",

                "ids_ref" => "refs",
                "ids_ref_intext" => "refs_intext",
            ),
            "mainValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",

                "harus_bayar" => "harus_bayar",
                "creditAmount" => "creditAmount",
                "nilai_entry" => "nilai_entry",
                "new_sisa" => "new_sisa",
            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => ".1",
                "produk_ord_hrg" => "nilai_bayar",

            ),
            "detailValues" => array(
                "tagihan" => "tagihan",
                "terbayar" => "terbayar",
                "sisa" => "sisa",
                "nilai_bayar" => "nilai_bayar",
                //                "new_sisa" => "new_sisa",
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "invoice",
            ),
        ),
        "components" => array(
            "9749" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "piutang dagang" => "-nilai_bayar",
                            "rugilaba lain lain" => "nilai_bayar",
                            // "rugi piutang dihapus" => "sisa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "piutang dagang" => "-nilai_bayar",
                            "rugilaba lain lain" => "nilai_bayar",
                            // "rugi piutang dihapus" => "sisa",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // ====== =============
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "piutang dagang" => "-nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "rugilaba lain lain" => "-nilai_bayar",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",
                            "extern_nama" => ".penghapusan piutang",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "9749r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".piutang dagang",
                            //                            "target_jenis" => "jenisTr",
                            //                            "target_jenis" => "jenis_source",
                            "target_jenis" => ".749",
                            "transaksi_id" => "refID",
                            //                            "terbayar" => "nilai_bayar",
                            //                            "dihapus" => "nilai_bayar",
                            "dihapus" => "nilai_bayar",
                            "sisa" => "new_sisa",
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
            "stepCode|customerID",
            "stepCode|placeID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
);