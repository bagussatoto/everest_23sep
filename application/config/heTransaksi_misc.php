<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/3/2019
 * Time: 8:16 PM
 */

$config['transaksi_returnRoutes'] = array(
    "467" => "967",
    "461" => "961",
    "582" => "982",
);

$config['payment_source'] = array(
    "466" => array(
        2 => array(
            array(
                "label" => "outgoing cash",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "488",
                "jenisSrc" => "466",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "460" => array(
        //step ambil dari source 466 step 3
        3 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "exchange__nilai_credit", // nilai_credit --> nilai dalam rupiah
                "jenisTarget" => "4891",
                "jenisSrc" => "460",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "nilai_tambah_ppn_in",
                    "ppn_status" => "0", // butuh diapprove ppn masukannya...
                    "valasId" => "currencyDetails",
                    "valasLabel" => "currencyDetails__nama",
                    "extern_nilai2" => "currencyDetails__exchange",//kurs simpan sini karena valas_nilai bentrok dengan transaksi
                    "valasTagihan" => "nilai_credit", // nilai dalam valas
                    "valasSisa" => "nilai_credit", // nilai dalam valas
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern_label2" => "description_main_followup",
                ),
            ),
        ),
    ),
    "467" => array(
        //step ambil dari source 466 step 3
        4 => array(
            array(
                "label" => "hutang dagang",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "nilai_tambah_piutang_pembelian",
                "jenisTarget" => "489",
                "jenisSrc" => "467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "dpp_ppn" => "nilai_credit",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "reguler",
                    "extern_label2" => "description_main_followup",
                    "extern_jenis" => "tipePo__kode",
                    "extern3_id" => "tipePo",
                    "extern3_nama" => "tipePo__kode",
                ),
            ),
        ),
    ),
    "1467" => array(
        //step ambil dari source 466 step 3
        4 => array(
            array(
                "label" => "hutang dagang",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "489",
                "jenisSrc" => "1467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "project",
                    "extern_label2" => "description_main_followup",
                ),
            ),
        ),
    ),
//    "489" => array(
    /*
     * vesi dari realisasi multi GRN
     */
//        1 => array(
//            array(
//                "label" => "ppn realisasi",
//                "label_key" => "ppn in realisasi",
//                "valueSrc" => "ppn_sudah_faktur",
//                "jenisTarget" => "0000",
//                "jenisSrc" => "489",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "extern_id" => "cabangID",
//                    "extern_nama" => "cabangName",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "ppn" => "ppn_sudah_faktur",
////                    "tagihan" => "ppn",//dpp ppn
//                    "extern_nilai2" => "dpp_final",//dpp ppn
//                    "extern_label2" => "eFaktur",//dpp ppn
//                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
//                    "extern_nama2" => "efakturSource",//nomer grn
//                    "npwp" => "vendorDetails__npwp", // npwp
////                    "extLabel" => "vendor",
////                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
//    ),
    "111" => array(
        /*
         * vesi dari realisasi multi GRN
         */
//        2 => array(
//            array(
//                "label" => "ppn realisasi",
//                "label_key" => "ppn in realisasi",
//                "valueSrc" => "ppn_belum_faktur",
//                "jenisTarget" => "0000",
//                "jenisSrc" => "111",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "extern_id" => "cabangID",
//                    "extern_nama" => "cabangName",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "ppn" => "ppn_belum_faktur",
////                    "tagihan" => "ppn",//dpp ppn
//                    "extern_nilai2" => "dpp_final",//dpp ppn
//                    "extern_label2" => "eFaktur",//dpp ppn
//                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
//                    "extern_nama2" => "efakturSource",//nomer grn
//                    "npwp" => "vendorDetails__npwp", // npwp
////                    "extLabel" => "vendor",
////                    "extLabel" => "vendor",
//                ),
//            ),
//        ),

        //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
//        5 => array(
//            array(
//                "label" => "ppn realisasi",
//                "label_key" => "ppn in realisasi",
//                "valueSrc" => "ppn_realisasi",
//                "jenisTarget" => "0000",
//                "jenisSrc" => "111",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "extern_id" => "cabangID",
//                    "extern_nama" => "cabangName",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "ppn" => "ppn_realisasi",
////                    "tagihan" => "ppn",//dpp ppn
//                    "extern_nilai2" => "harga",//dpp ppn
//                    "extern_label2" => "eFaktur",//dpp ppn
//                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
//                    "extern_nama2" => "efakturSource",//nomer grn
//                    "npwp" => "vendorDetails__npwp", // npwp
////                    "extLabel" => "vendor",
////                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
    ),
    "1111" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
        5 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "1111",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "112" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 461 step 3
        4 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "112",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "113" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 463 dan 1463 step 4
        4 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "113",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "110" => array(
        3 => array(
            // bukan gunggungan
            array(
                "label" => "ppn out",
                "valueSrc" => "new_grand_ppn_non_gunggungan",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "place2ID",
                    "nama" => "place2Name",
                    "extLabel" => "branch",
                    "ppn_approved" => "new_grand_ppn_non_gunggungan",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "referensi_id",//invoice penjualan
                    "extern2_nama" => "efaktur_source",//invoice penjualan
                    "customers_id" => "customerID",//customer penjualan
                    "customers_nama" => "customerName",//customer penjualan
                    "npwp" => "deliveryDetails__npwp",//customer penjualan
                ),
            ),
            // gunggungan
            array(
                "label" => "ppn out gunggung",
                "valueSrc" => "new_grand_ppn_gunggungan",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "place2ID",
                    "nama" => "place2Name",
                    "extLabel" => "branch",
                    "ppn_approved" => "new_grand_ppn_gunggungan",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
//                    "extern2_id" => "referensi_id",//invoice penjualan
//                    "extern2_nama" => "efaktur_source",//invoice penjualan
//                    "customers_id" => "customerID",//customer penjualan
//                    "customers_nama" => "customerName",//customer penjualan
//                    "npwp" => "deliveryDetails__npwp",//customer penjualan
                ),
            ),
        ),
    ),
    //konversi supplies ke aset dan penambahan dibuatkan pymsrc untuk setor ppn keluaran
    "7622f" => array(
        4 => array(
            array(
                "label" => "ppn out",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "customers_id",
                    "nama" => "customers_nama",
                    "extLabel" => "branch",
                    "ppn_approved" => "ppn_realisasi",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "currentID",//invoice konversi
                    "extern2_nama" => "efakturSource",//invoice penjualan
                    "customers_id" => "customers_id",//customer penjualan
                    "customers_nama" => "customers_nama",//customer penjualan
                ),
            ),
        ),
    ),
    "7620f" => array(
        3 => array(
            array(
                "label" => "ppn out",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "114",
                "jenisSrc" => "110",
                "externSrc" => array(
                    "id" => "customers_id",
                    "nama" => "customers_nama",
                    "extLabel" => "branch",
                    "ppn_approved" => "ppn_realisasi",//ppn
                    "extern_nilai2" => "dpp_ppn",//dpp
                    "extern_label2" => "eFaktur",//nomer faktur
                    "extern_date2" => "dateFaktur",//tgl faktur
                    "extern2_id" => "currentID",//invoice konversi
                    "extern2_nama" => "efakturSource",//invoice penjualan
                    // "customers_id" => ".-1",//customer penjualan
                    // "customers_nama" => ".PT Indosan Berkat Bersama",//customer penjualan
                    "customers_id" => "customers_id",//customer penjualan
                    "customers_nama" => "customers_nama",//customer penjualan
                ),
            ),
        ),
    ),
    "3113" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 463 dan 1463 step 4
        4 => array(
            array(
                "label" => "ppn realisasi",
                "valueSrc" => "ppn_realisasi",
                "label_key" => "ppn in realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "3113",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dppPPn",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
//    "461r" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "486",
//                "jenisSrc" => "461r",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
//    ),
    "463o" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "485",
//                "jenisSrc" => "463o",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_nilai2" => "harga_disc",
//                    "ppn" => "ppn",
//                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
    ),
    "1463o" => array(
        2 => array(
            array(
                "label" => "outgoing cash",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "485",
                "jenisSrc" => "1463o",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga_disc",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                ),
            ),
        ),
    ),
    "461" => array(
        3 => array(
            array(
                "label" => "hutang dagang",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "harga_disc",
                "jenisTarget" => "487",
                "jenisSrc" => "461",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "dpp_ppn" => "harga_disc",
                    "extern2_id" => "referenceID__2",
                    "extern2_nama" => "referenceNomer__2",
                    "extern2_label" => "reguler",
                ),
            ),
        ),
    ),
    "463" => array(
        3 => array(
            array(
                "label" => "hutang biaya",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "harga_disc",
                "jenisTarget" => "462",
                "jenisSrc" => "463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
//                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph update versi baru
                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph
                    "extern_nilai5" => "dppPPh21",//ini untuk dpp pph21
//                    "extern_nilai2" => "harga_disc",//ini untuk dpp pph
                    "ppn" => "ppn",
                    "extern_nilai3" => "dppPPn",//untuk nyimpen dpp ppn
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                    "extern3_id" => "referenceID__2",
                    "extern3_nama" => "referenceNomer__2",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => "",// id pembantu biaya
                    "biaya_rekening_id_label" => "",// nama pembantu biaya

                ),
            ),
        ),
    ),
    "1463" => array(
        3 => array(
            array(
                "label" => "hutang biaya",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "harga_disc",
                "jenisTarget" => "1462",
                "jenisSrc" => "1463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga_disc",
                    "extern_nilai5" => "dppPPh21",//ini untuk dpp pph21
                    "ppn" => "ppn",
                    "extern_nilai3" => "nilai_dpp_ppn",
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                    "extern3_id" => "referenceID__2",
                    "extern3_nama" => "referenceNomer__2",
                ),
            ),
        ),
    ),
    "3463" => array(
        3 => array(
            array(
                "label" => "hutang dagang",
//                "valueSrc" => "nilai_credit",
                "valueSrc" => "harga_disc",
                "jenisTarget" => "483",
                "jenisSrc" => "3463",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "dppPPh",//ini untuk dpp pph
                    "extern_nilai5" => "dppPPh",//ini untuk dpp pph21
                    "ppn" => "ppn",
                    "extern_nilai3" => "dppPPn",//untuk nyimpen dpp ppn
                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
                    "extern2_id" => "pph23MethodPotongan",//dipotong/tidak
                    "extern2_nama" => "pph23MethodPotongan__name",
                    "extern3_id" => "referenceID__2",
                    "extern3_nama" => "referenceNomer__2",
                ),
            ),
        ),
    ),
    "462" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1477",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "483" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
//                "valueSrc" => "pph23_nilai",
                "valueSrc" => "hutang_pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "483",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
//                "valueSrc" => "pph21_nilai",
                "valueSrc" => "hutang_pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "483",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "483",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "1462" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "1462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "extern_nilai2",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "1462",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1477",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "485" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "485",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
            ),
        ),
    ),
    "672" => array(
        2 => array(
            array(
                "label" => "refill pettycash",
                "valueSrc" => "harga",
                "jenisTarget" => "771",
                "jenisSrc" => "672",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "vendor",
//                    "jenis" => "extern_label2",
                    "extern_label2" => "pihakMainName",
                    "ppn" => "ppn_nilai",
                    "dpp_ppn" => "dpp_nilai",
                ),
            ),
        ),
    ),
    "1672" => array(
        2 => array(
            array(
                "label" => "refill pettycash",
                "valueSrc" => "harga",
                "jenisTarget" => "1771",
                "jenisSrc" => "1672",
                "externSrc" => array(
//                    "id" => "cabang2ID",// cabang pembebanan
//                    "nama" => "cabang2Name",// cabang pembebanan
                    "id" => "cabang_pembebanan",// cabang pembebanan
                    "nama" => "cabang_pembebanan__label",// cabang pembebanan
                    "extLabel" => "vendor",
                    "extern_label2" => "pihakMainName",
                    "ppn" => "ppn_nilai",
                    "dpp_ppn" => "dpp_nilai",
                    "cabang_pembebanan_id" => "cabang_pembebanan",
                    "cabang_pembebanan_nama" => "cabang_pembebanan__label",
                    "cabang_pembebanan_kode" => "cabang_pembebanan__kode",
                ),
            ),
        ),
    ),
    //--------------------------------------
    "582so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "582",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "5822so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "5822",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "5823so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "5823",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
//                    "extern2_id" => "ppn",//payment_method_id
//                    "extern2_nama" => "ppn",//payment methode nama
                    "extern_jenis" => "paymentMethod__label",//paymentmethode cash/cashless/transfer

                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "584so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //tambahan untuk jasa kirim
    "582spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "582spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "582",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "5822spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "5822spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010+nilai_tambah_1010020090", // nilai yang dipakai piutang dagang
//                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "5822",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    "extern2_id" => "id_master",//id master
                    "extern2_nama" => "nomer_top",//nomer top
                    "extern3_id" => "marketplaceID",//id marketplace
                    "extern3_nama" => "marketplaceName",//nama marketplace
                    "extern4_id" => "tipe_penjualan",//id marketplace
                    "extern4_nama" => "tipe_penjualan_nama",//nama marketplace
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "584" => array(
        3 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010110", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "584",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //--------------------------------------
    "580so" => array(
        2 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_cash", // nilai yang dipakai piutang dagang
                "jenisTarget" => "4464",
                "jenisSrc" => "580",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "cash_account" => "cash_account",
                    "cash_account_label" => "cash_account__label",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    "580spd" => array(
        4 => array(
            array(
                "label" => "piutang dagang",
                "valueSrc" => "srcOngkir",
                "jenisTarget" => "2749",
                "jenisSrc" => "580spd",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
            array(
                "label" => "piutang dagang",
                "valueSrc" => "nilai_tambah_2010050_2010050010", // nilai yang dipakai piutang dagang
                "jenisTarget" => "749",
                "jenisSrc" => "580",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    //-------------
                    "dpp_ppn" => "dpp_ppn",
                    "ppn" => "ppn",
                    //-------------
                ),
                "addValueValidator" => "new_net1",
            ),
        ),
    ),
    //--------------------------------------
//    "584" => array(
//        3 => array(
//            array(
//                "label" => "hutang setoran jasa",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "759",
//                "jenisSrc" => "584",
//                "externSrc" => array(
//                    "id" => "olehID",
//                    "nama" => "olehName",
//                    "extLabel" => "person",
//                ),
//            ),
//            array(
//                "label" => "piutang dagang jasa",
//                "valueSrc" => "nilai_credit",
//                "jenisTarget" => "1784",
//                "jenisSrc" => "584",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                    "pph_23" => "pph_23",
//                    "extern_nilai2" => "nett1_bulat",
//                    "ppn" => "ppn_out_bulat",
//                ),
//            ),
//        ),
//    ),

    "382" => array(
        5 => array(
            array(
                "label" => "piutang valas",
                "valueSrc" => "tagihan",
                "jenisTarget" => "1749",
                "jenisSrc" => "382",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "valasId" => "valasDetails",
                    "valasLabel" => "valasDetails__nama",
                    "valasValue" => "valasDetails__exchange",
                    "valasTagihan" => "grand_total_valas",
                    "valasSisa" => "grand_total_valas",
                ),
            ),

        ),
    ),
    "383" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "grand_total",
                "jenisTarget" => "759",
                "jenisSrc" => "749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                ),
            ),

        ),
    ),

    "749" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "759",
                "jenisSrc" => "749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),
            //uang muka pph auto terbit di pusat
            array(
                "label" => "uang muka pph23",
                "valueSrc" => "pph23",
                "jenisTarget" => "0000", //
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern_nilai2" => "tagihan",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "extern2_id" => ".9",
                    "extern2_nama" => ".customer",
                    "extern3_id" => "pihakMainID",
                    "extern3_nama" => "pihakMainName",
                    "extern_date2" => "dateFaktur",
                    "extern_label2" => "eFaktur",
                ),
            ),

        ),
    ),

    "4464" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "7759", // aslinya 759, ganti setoran uang muka...
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
            //uang muka pph auto terbit di pusat
            array(
                "label" => "uang muka pph23",
                "valueSrc" => "pph23",
                "jenisTarget" => "0000", //
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern_nilai2" => "tagihan",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "extern2_id" => ".9",
                    "extern2_nama" => ".customer",
                    "extern3_id" => "pihakMainID",
                    "extern3_nama" => "pihakMainName",
                ),
            ),
        ),
    ),
    "4465" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nett",
                "jenisTarget" => "7759", // aslinya 759, ganti setoran uang muka...
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
            //uang muka pph auto terbit di pusat
//            array(
//                "label" => "uang muka pph23",
//                "valueSrc" => "pph23",
//                "jenisTarget" => "0000", //
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extern_nilai2" => "tagihan",
//                    "cabang_id"=>"place2ID",
//                    "cabang_nama"=>"place2Name",
//                ),
//            ),
        ),
    ),
    "4467" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_payment_source",
                "jenisTarget" => "04467", // uangmuka ada ppn
                "jenisSrc" => "4467",
                "method" => "update",
                "methodFilter" => array(
                    "target_jenis=.04467",
                    "extern_id=pihakID",// id konsumen
                    "extern2_id=referensi_so__id",// id so project
                    "project_id=referensi_so_project",// id project
                    "cabang_id=placeID",
                    "label=.uang muka konsumen",
                ),
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nilai" => "referensi_so_project__harga",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "project_id" => "referensi_so_project__id",
                    "project_nama" => "referensi_so_project__nama",
                    "extLabel" => "customer",
                    "dpp_ppn" => "dpp_nilai",
                    "ppn" => "ppn",
                    "ppn_sisa" => "ppn",

                ),
            ),
        ),
    ),
    "4656" => array(
        1 => array(
            //untuk uang muka yang masuk relasi SO
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_payment_source", //int
                "jenisTarget" => "04467", //uang muka disamakan dengan um project
                "jenisSrc" => "4656",
                "swapJenis" => "d_swapJenis",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "project_id" => "referensi_so__project_id",
                    "project_nama" => "referensi_so__project_nama",
                    "extLabel" => "customer",
                    "dpp_ppn" => "dpp_nilai",
                    "ppn" => "ppn",
                ),
            ),
            //uang muka pph auto terbit di pusat
//            array(
//                "label" => "uang muka pph23",
//                "valueSrc" => "pph23",
//                "jenisTarget" => "0000", //
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extern_nilai2" => "tagihan",
//                    "cabang_id"=>"place2ID",
//                    "cabang_nama"=>"place2Name",
//                ),
//            ),
        ),
    ),
    "8789" => array(
        2 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "tagihan_ui",
                "jenisTarget" => "759", // aslinya 759, ganti setoran uang muka...
                "jenisSrc" => "8789",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),

        ),
    ),
    // uangmuka ke supplier dengan ppn
    "464" => array(
        1 => array(
            array(
                "label" => "uang muka supplier",
                "valueSrc" => "um_ppn_nilai",
                "jenisTarget" => "0464", // uangmuka ada ppn
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "elementReference__extern2_id",
                    "extern2_nama" => "elementReference__extern2_nama",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "vendor",
                    "dpp_ppn" => "um_ppn_nilai",
                    "ppn" => "ppn_nilai",

                ),
                "method" => "update",
                "methodFilter" => array(
                    "target_jenis=.0464",
                    "extern_id=pihakID",
                    "extern2_id=elementReference__extern2_id",
                    "cabang_id=placeID",
                    "label=.uang muka supplier",
                ),
            ),
        ),
    ),
    "464a" => array(
        //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 466 step 3
        2 => array(
            array(
                "label" => "ppn realisasi",
                "label_key" => "ppn in realisasi",
                "valueSrc" => "ppn_realisasi",
                "jenisTarget" => "0000",
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "extern_id" => "cabangID",
                    "extern_nama" => "cabangName",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_realisasi",
//                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "dpp_ppn",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "npwp" => "vendorDetails__npwp", // npwp
//                    "extLabel" => "vendor",
//                    "extLabel" => "vendor",
                    "extern2_id" => "referensi_so__id",//idpo
                    "extern2_nama" => "referensi_so__nomer",//nomer po
                ),
            ),
        ),
    ),
    "465" => array(
        1 => array(
            array(
                "label" => "uang muka supplier",
                "valueSrc" => "harga",
                "jenisTarget" => "0464", // uangmuka ada ppn
                "jenisSrc" => "465",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensiSo",
                    "extern2_nama" => "referensiSo__label",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "vendor",
                    "dpp_ppn" => "harga",
//                    "ppn" => "ppn_nilai",

                ),
                "method" => "update",
                "methodFilter" => array(
                    "target_jenis=.0464",
                    "extern_id=pihakID",
                    "extern2_id=referensiSo",
                    "cabang_id=placeID",
                    "label=.uang muka supplier",
                ),
            ),
        ),
    ),

    "7761" => array(
        1 => array(
            array(
                "label" => "settlement uang muka konsumen (4464)",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "7760",
                "jenisSrc" => "7761",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
        ),
    ),
    "1749" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "1759",
                "jenisSrc" => "1749",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "valasId" => "valasDetails",
                    "valasLabel" => "valasDetails__nama",
                    "valasValue" => "valasDetails__exchange",
//                    "valasTagihan" => "grand_total_valas",
//                    "valasSisa" => "grand_total_valas",
                    "valasTagihan" => "nilai_entry",
                    "valasSisa" => "nilai_entry",
                ),
            ),
        ),
    ),
    "700" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "759",
                "jenisSrc" => "700",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                ),
            ),
//            array(
//                "label" => "setoran tunai",
//                "valueSrc" => "nilai_setoran_tunai",
//                "jenisTarget" => "751",
//                "jenisSrc" => "700",
//                "externSrc" => array(
//                    "id" => "olehID",
//                    "nama" => "olehName",
//                    "extLabel" => "person",
//                ),
//            ),
        ),
    ),
    "7001" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_cash",
                "jenisTarget" => "759",
                "jenisSrc" => "7001",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "pph_23" => "pph_23",
                    "bank_id" => "cash_account",
                    "bank_label" => "cash_account_label",
                ),
            ),
        ),
    ),
    "1674" => array(
        2 => array(
            array(
                "label" => "hutang gaji",
//                "valueSrc" => "hutang_gaji",
                "valueSrc" => "hutang_gaji_main",
                "jenisTarget" => "1485",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",
                ),
            ),
        ),

    ),
    "21674" => array(
        2 => array(
            array(
                "label" => "hutang gaji",
//                "valueSrc" => "hutang_gaji",
                "valueSrc" => "hutang_gaji_main",
                "jenisTarget" => "1485",
                "jenisSrc" => "21674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",
                ),
            ),
        ),

    ),
    "7674" => array(
        2 => array(
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "hutang_pph21_main",
                "jenisTarget" => "1483",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",

                    "biaya_rekening" => "biaya_option__coa_code",// berisi coa
                    "biaya_rekening_label" => "biaya_option__label",// label coa
                    "biaya_rekening_id" => "biaya_option__biaya_gaji_id",// id pembantu biaya
                    "biaya_rekening_id_label" => ".biaya gaji",// nama pembantu biaya
                    "biaya_rekening2_id" => "biaya_option__biaya_pph21_id",// id pembantu biaya
                    "biaya_rekening2_id_label" => ".biaya pph21",// nama pembantu biaya
                    "cabang2_id" => "pihakID",// id cabang pembebanan biaya
                    "cabang2_nama" => "pihakName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang bpjs",
//                "valueSrc" => "hutang_bpjs",
                "valueSrc" => "hutang_bpjs_main",
                "jenisTarget" => "1487",
                "jenisSrc" => "1674",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",

                    "biaya_rekening" => "biaya_option__coa_code",// berisi coa
                    "biaya_rekening_label" => "biaya_option__label",// label coa
                    "biaya_rekening_id" => "biaya_option__biaya_pph21_id",// id pembantu biaya
                    "biaya_rekening_id_label" => ".biaya pph21",// nama pembantu biaya

                ),
            ),
        ),

    ),

//    "2674" => array(
//        2 => array(
//            array(
//                "label" => "hutang gaji",
//                "valueSrc" => "hutang_gaji_main",
//                "jenisTarget" => "1485",
//                "jenisSrc" => "2674",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "branch",
//                ),
//            ),
//            array(
//                "label" => "hutang pph 21",
//                "label_key" => "hutang pph21",
//                "valueSrc" => "hutang_pph21_main",
//                "jenisTarget" => "1483",
//                "jenisSrc" => "2674",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "branch",
//                ),
//            ),
//            array(
//                "label" => "hutang bpjs",
//                "valueSrc" => "hutang_bpjs_main",
//                "jenisTarget" => "1487",
//                "jenisSrc" => "2674",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "branch",
//                ),
//            ),
//        ),
//
//    ),

    "588st" => array(
        3 => array(
            //buat termin saat nombol start project
            array(
                "label" => "piutang dagang",
//                "valueSrc" => "harga_termin", // hanya nilai termin
                "valueSrc" => "projectHarga", // full project
                "jenisTarget" => "7499",
                "jenisSrc" => "588st",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "no_kontrak",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                    "extern_nilai2" => "projectHarga",
                ),
            ),
            //retensi jika ada
            array(
                "label" => "retensi",
                "valueSrc" => "retensi_project",
                "jenisTarget" => "7488",
                "jenisSrc" => "588st",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
                    "extern_nilai2" => "projectHarga",
                    "payment_source_keterangan" => "paymentSourceKeterangan",
                ),
            ),
        ),
    ), //nombol start project

    "5887" => array(
        2 => array(
            array(
                "label" => "biaya project",
                "valueSrc" => "biaya_project",//biaya project tidak terduga
                "jenisTarget" => "4888",//
                "jenisSrc" => "5887",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "project",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
//                    "extern_date2" => "dateGaransi",// tanggal masa garansi
                    "extern_nilai2" => "harga_nppn",//nilai project
//                    "ppn_pph_factor" => "tarifGaransi",//tarif garansi
                ),
            ),
        ),
    ),

    "7499" => array(
        1 => array(
            array(
                "label" => "piutang dagang",
//                "valueSrc" => "piutang_dagang",
                "valueSrc" => "piutang_usaha",
                "jenisTarget" => "749",//
                "jenisSrc" => "7499",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                    "project_id" => "projectID",
                    "project_nama" => "projectName",
//                    "extern2_id" => "",
//                    "extern2_nama" => "",
                    //-------------
                    "dpp_ppn" => "nilai_entry",
                    "ppn" => "grand_ppn",
                    "payment_source_keterangan" => "description",
                    //-------------
                    "extern2_id" => "referensi_so",
                    "extern2_nama" => "referensi_so__label",
                    "extern4_id" => ".11",
                    "extern4_nama" => ".PROJECT",
                ),
            ),
//            /*
//             * perlu ngobrol lagi
//             */
//            array(
//                "label" => "piutang retensi",
//                "valueSrc" => "piutang_retensi",
//                "jenisTarget" => "7488",//
//                "jenisSrc" => "7499",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                    "project_id" => "projectID",
//                    "project_nama" => "projectName",
//                ),
//            ),
        ),
    ),

    "114" => array(
        1 => array(
            array(
                "label" => "desposit pajak",
                "valueSrc" => "saldo_deposit",
                "jenisTarget" => "00001",
                "jenisSrc" => "114",
                "method" => "update",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extLabel" => "branch",
//                    "ppn_approved" => "saldo_deposit",//ppn
//                    "extern_nilai2" => "",//dpp
//                    "extern_label2" => "",//nomer faktur
//                    "extern_date2" => "",//tgl faktur
                ),
            ),
            array(
                "label" => "setor ppn bulanan",
                "valueSrc" => "nilai_entry",//perlu setor
                "jenisTarget" => "1148",
                "jenisSrc" => "114",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",//ebilling aka nomer tagihan djp
                    "extLabel" => "branch",
//                    "ppn_approved" => "new_grand_ppn_non_gunggungan",//ppn
                    "extern_nilai2" => "ppn_masukan",//ppn masukan
                    "extern_nilai3" => "nilai_sisa",//ppn ppn keluaran
                    "extern_nilai4" => "denda_nilai",//denda_nilai
                    "extern_label2" => "ebilling",//nomer faktur
                    "extern_date2" => "ebillingDate",//tgl faktur

//                    "extern2_id" => "referensi_id",//invoice penjualan
//                    "extern2_nama" => "ebilling",//invoice penjualan
//                    "customers_id" => "customerID",//customer penjualan
//                    "customers_nama" => "customerName",//customer penjualan
//                    "npwp" => "deliveryDetails__npwp",//customer penjualan
                ),
            ),
        ),
    ),

    //biaya import -- expense
    "651" => array(
        2 => array(
            array(
                "label" => "import expense",
                "valueSrc" => "harga",
                "jenisTarget" => "652",
                "jenisSrc" => "651",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "import expense",
                ),
            ),
        ),
    ),

    "673" => array(
        1 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "harga",
                "jenisTarget" => "473",
                "jenisSrc" => "673",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),

    //biaya jasa / imbalan jasa by kategory produksi/umum/usaha
    "119" => array(
        2 => array(
            array(
                "label" => "hutang imbalan jasa",
                "valueSrc" => "nett",
                "jenisTarget" => "2119",
                "jenisSrc" => "119",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extern_nilai2" => "harga",//dpp
                    "extern_label2" => "taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
                    "pph_23" => "ppn", //nilai pajak
                    "extern_jenis" => "pihakMainName",
                    "ppn_pph_faktor" => "ppnPersen",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "nilai_pph21",
                "jenisTarget" => "1483",
                "jenisSrc" => "119",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_nilai2" => "harga",//dpp
                    "extern_label2" => "taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
                    "pph_23" => "ppn", //nilai pajak
                    "extern_jenis" => "pihakMainName",
                    "ppn_pph_faktor" => "ppnPersen",

                    "biaya_rekening" => "pihakMainCoa",// berisi coa
                    "biaya_rekening_label" => "pihakMainCoa",// label coa
                    "biaya_rekening_id" => "",// id pembantu biaya
                    "biaya_rekening_id_label" => "",// nama pembantu biaya

                ),
            ),
        ),
    ),

    //objek pajak
    "681" => array(
        2 => array(
            array(
                "label" => "objek pajak",
                "valueSrc" => "harga",
                "jenisTarget" => "682",
                "jenisSrc" => "681",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName2",
                    "extLabel" => "asap",
                    "extern2_id" => "supplierID",
                    "extern2_nama" => "supplierNama",
                    "suppliers_id" => "supplierID",
                    "suppliers_nama" => "supplierNama",
                ),
            ),

        ),
    ),
    "5681" => array(
        2 => array(
            array(
                "label" => "objek pajak",
                "valueSrc" => "harga",
                "jenisTarget" => "5682",
                "jenisSrc" => "5681",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName2",
                    "extLabel" => "asap",
                ),
            ),

        ),
    ),

    //===582_ (pakai underscore) berarti dipasang pada 582 step bebas
//    "582_" => array(
//        array(
//            "label" => "incoming cash",
//            "valueSrc" => "nilai_cia",
//            "jenisTarget" => "700",
//            "jenisSrc" => "582_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//
//
//            ),
//        ),
//        array(
//            "label" => "incoming cash",
//            "valueSrc" => "dp",
//            "jenisTarget" => "700",
//            "jenisSrc" => "582_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//            ),
//        ),
//    ),
    //===584_ (pakai underscore) berarti dipasang pada 584 step bebas
    "584_" => array(
        array(
            "label" => "incoming cash",
            "valueSrc" => "nilai_cia",
            "jenisTarget" => "7001",
            "jenisSrc" => "584_",//===pakai underscore berarti step bebas (tidak bernomor urut)
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
                "pph_23" => "pph_23",
                "bank_id" => "cash_account",
                "bank_label" => "cash_account__label",
            ),
        ),
        array(
            "label" => "incoming cash",
            "valueSrc" => "dp",
            "jenisTarget" => "7001",
            "jenisSrc" => "584_",//===pakai underscore berarti step bebas (tidak bernomor urut)
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
                "pph_23" => "pph_23",
                "bank_id" => "cash_account",
                "bank_label" => "cash_account__label",
            ),
        ),
    ),
    //===466_ (pakai underscore) berarti dipasang pada 466 step bebas
    "466_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cash",
//            "jenisTarget" => "400",
//            "jenisSrc" => "466_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),
    //===461_ (pakai underscore) berarti dipasang pada 461 step bebas
    "461_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cash",
//            "jenisTarget" => "401",
//            "jenisSrc" => "461_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),
    //===463_ (pakai underscore) berarti dipasang pada 463 step bebas
    "463_" => array(

//        array(
//            "label" => "outgoing cash",
//            "valueSrc" => "nilai_cia",
//            "jenisTarget" => "485",
//            "jenisSrc" => "463_",//===pakai underscore berarti step bebas (tidak bernomor urut)
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "supplier",
//            ),
//        ),

    ),

//    "1463o" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "485",
//                "jenisSrc" => "1463o",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_nilai2" => "harga_disc",
//                    "ppn" => "ppn",
//                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
//
//    ),

    //config biaya umum
    "2675" => array(
        2 => array(
            array(
                "label" => "hutang biaya umum",
                "valueSrc" => "harga",
                "jenisTarget" => "475",
                "jenisSrc" => "2675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "2676" => array(
        2 => array(
            array(
                "label" => "hutang biaya produksi",
                "valueSrc" => "harga",
                "jenisTarget" => "476",
                "jenisSrc" => "2676",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "2677" => array(
        2 => array(
            array(
                "label" => "hutang biaya usaha",
                "valueSrc" => "harga",
                "jenisTarget" => "477",
                "jenisSrc" => "2677",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),
    "1675r" => array(
        1 => array(
            array(
                "label" => "hutang biaya umum",
                "valueSrc" => "harga",
                "jenisTarget" => "1475",
                "jenisSrc" => "1675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),

    "1677r" => array(
        1 => array(
            array(
                "label" => "hutang biaya usaha",
//                "valueSrc" => "harga",
                "valueSrc" => "biaya_pusat",
                "jenisTarget" => "1477",
                "jenisSrc" => "1677",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                    "terbayar" => "kas_out",
                ),
            ),
        ),
    ),
    "4675" => array(
        2 => array(
            array(
                "label" => "hutang biaya",
                "valueSrc" => "harga",
                "jenisTarget" => "6475",
                "jenisSrc" => "4675",
                "externSrc" => array(
                    "id" => "cabang2ID",
                    "nama" => "cabang2Name",
                    "extLabel" => "asap",
                ),
            ),
        ),
    ),

    // CIA SEWA
    "424" => array(
//        2 => array(
//            array(
//                "label" => "outgoing cash",
//                "valueSrc" => "nilai_cash",
//                "jenisTarget" => "4241",
//                "jenisSrc" => "424",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                ),
//            ),
//        ),
    ),

    //config sewa
    "425" => array(
        3 => array(
            array(
                "label" => "hutang sewa",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "1424",
                "jenisSrc" => "424",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "ppn" => "ppn_out_bulat",//ppn
                    "extern_nilai2" => "nett1_bulat",//dpp pph
                    "pph_23" => "pph_value",
                    "ppn_pph_faktor" => "tarif_pph",
                    "extern_nilai3" => "nett1_bulat",//dpp ppn
                    "extern_jenis" => "pphGate",//tipe hutang pph pph23/pph4 2
//                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
                    "extern2_id" => "pihakMainRulesID",//tipe hutang pph pph23/pph4 2
                    "extern2_nama" => "pihakMainRulesName",//tipe hutang pph pph23/pph4 2
                ),
            ),
//            array(
//                "label" => "{pphGate}",//contoh lihat di modul assetmanagenment controller followup bagian paymentsource
//                "valueSrc" => "pph_value",
//                "jenisTarget" => "{extern_target_jenis}",
//                "jenisSrc" => "424",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_nilai2" => "harga",//dpp pph
//                    "pph_23" => "pph_value",
//                    "target_jenis" => "extern_target_jenis",
//                    "reference_jenis" => "jenisTr",
//                    "ppn_pph_faktor" => "tarif_pph",//dpp ppn
//                    "extern_jenis" => "pphGate",//tipe hutang pph pph23/pph4 2
//                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
//                ),
//            ),
        ),
    ),
    "1424" => array(
        1 => array(
            array(
                "label" => "hutang pph4 ayat 2",//contoh lihat di modul assetmanagenment controller followup bagian paymentsource
                "valueSrc" => "pphps4_2_nilai",
                "jenisTarget" => "1120",
                "jenisSrc" => "1424",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga",//dpp pph
                    "pph_23" => "pphps4_2_nilai",
                    "target_jenis" => "1120",
                    "reference_jenis" => "jenisTr",
                    "ppn_pph_faktor" => "pph_tarif",//dpp ppn
                    "extern_jenis" => "hutang pph4 ayat 2",//tipe hutang pph pph23/pph4 2
                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
                ),
            ),
            array(
                "label" => "utang pph23",//contoh lihat di modul assetmanagenment controller followup bagian paymentsource
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "1424",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga",//dpp pph
                    "pph_23" => "pph23_nilai",
                    "target_jenis" => "115",
                    "reference_jenis" => "jenisTr",
                    "ppn_pph_faktor" => "pph_tarif",//dpp ppn
                    "extern_jenis" => "hutang pph23",//tipe hutang pph pph23/pph4 2
                    "extern2_nama" => "pihakMainName",//tipe hutang pph pph23/pph4 2
                    "extern2_id" => "2",
//                    "extern2_nama" => "supplier",
                ),
            ),
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1424",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),

    ),
    //config pembelian aset
    "423" => array(
        3 => array(
            array(
                "label" => "hutang aktiva tetap",
                "valueSrc" => "nilai_credit",
                "jenisTarget" => "4821",
                "jenisSrc" => "423",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern_nilai2" => "harga",//dpp
                    "extern_nilai4" => "other",
//                    "extern_label2" =>"taxesMethod",//rek name
                    "extern2_nama" => "pihakMainRulesID",//pph 21/23
//                    "pph_23" => "ppn", //nilai pajak
                    "ppn" => "ppn", //nilai pajak
//                    "extern_jenis" =>"pihakMainName",
//                    "ppn_pph_faktor" =>"ppnPersen",
                    "extern_jenis" => "pihakMainID_coa",
                    "extern3_id" => "fasilitasPajak",
                    "extern3_nama" => "fasilitasPajak__nama",
                ),
            ),
        ),
    ),

    //config hutang bank
    "444" => array(
        2 => array(
            array(
                "label" => "hutang bank",
                "valueSrc" => "harga",
                "jenisTarget" => "4447",
                "jenisSrc" => "444",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "448" => array(
        2 => array(
            array(
                "label" => "hutang bank",
                "valueSrc" => "harga",
                "jenisTarget" => "4440",
                "jenisSrc" => "448",
                "externSrc" => array(
                    "id" => "pihakRelId",
                    "nama" => "pihakRelName",
                    "extLabel" => "vendor",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),
        ),
    ),
    "4449" => array(
        2 => array(
            array(
                "label" => "hutang biaya bunga",
                "valueSrc" => "nilai_bunga",
                "jenisTarget" => "4410",
                "jenisSrc" => "4449",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",
                    "ppn_pph_faktor" => "pph_nilai",
                    "extern_nilai2" => "nilai_kas_dipakai",
                    "npwp" => "npwp",
                ),
            ),
            array(
                "label" => "hutang pph23",
                "valueSrc" => "nilai_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "4449",
//                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",//nilai pph 23
                    "ppn_pph_faktor" => "pph_nilai",//prosentase
                    "extern_nilai2" => "nilai_bunga",//dpp
                    "npwp" => "npwp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
                //

//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern_id" => "pihakID",
//                    "extern_nama" => "pihakName",
//                    "npwp" => "vendorDetails__npwp",
//                    "extern_nilai2" => "source_dpp",
//                ),
            ),
        ),
    ),
    "4412" => array(
        2 => array(
            array(
                "label" => "hutang biaya bunga",
                "valueSrc" => "nilai_kas_dipakai",
                "jenisTarget" => "4410",
                "jenisSrc" => "4412",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "pph_23" => "nilai_pph23",
                    "ppn_pph_faktor" => "",
                    "extern_nilai_2" => "",
                    "npwp" => "",
                ),
            ),
        ),
    ),
    "5683" => array(
        2 => array(
            array(
                "label" => "hutang pph 29",
                "label_key" => "hutang pph29",
                "valueSrc" => "harga",
                "jenisTarget" => "5684",
                "jenisSrc" => "5683",
                "externSrc" => array(
                    "id" => "placeID",
                    "nama" => "placeName",
                    "extLabel" => "placeName",
                ),
            ),
        ),
    ),
    //pib
    "682" => array( //"bikin payment source ppn masukan yang akan dicomapre dengan ppn keluaran"
        //step ambil dari source 461 step 3
        1 => array(
            array(
                "label" => "pib",
                "label_key" => "pib",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "0000",
                "jenisSrc" => "682",
                "externSrc" => array(
                    "id" => "pairPihakID",
                    "extern_id" => "pairPihakID",
                    "extern_nama" => "pairPihakName",
                    "nama" => "pairPihakName",
                    "extLabel" => "vendor",
                    "ppn" => "nilai_entry",
                    //                    "tagihan" => "ppn",//dpp ppn
                    "extern_nilai2" => "harga",//dpp ppn
                    "extern_label2" => "eFaktur",//dpp ppn
                    "extern_date2" => "dateFaktur",//tgl faktur ppn masukan
                    "extern_nama2" => "efakturSource",//nomer grn
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp", // npwp
                    //                    "extLabel" => "vendor",
                    //                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),

    "3333" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "nilai_hutang_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "3333",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "diskon_supplier_nilai",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",
                ),
            ),
            // cadangan pph23 dibayar dimuka, untuk input faktur pph23
            array(
                "label" => "cadangan pph23",
                "valueSrc" => "nilai_pph23",
                "jenisTarget" => "2255",
                "jenisSrc" => "3333",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "nilai_sumber_dpp",
                    "extern2_id" => ".2",
                    "extern2_nama" => ".supplier",
                    "extern3_id" => "pihakMainID",
                    "extern3_nama" => "pihakMainName",
                ),
            ),
        ),
    ),
    "6677" => array(
        1 => array(
            array(
                "label" => "piutang pph",
                "valueSrc" => "piutang_pph_21_produk",
                "jenisTarget" => "7468",
                "jenisSrc" => "6677",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback_produk",
                    "extern_nilai5" => "nilai_kas_cn",
                    "extern2_id" => "9",
                    "extern2_nama" => "customer",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "piutang pph",
                "valueSrc" => "piutang_pph_23_produk",
                "jenisTarget" => "7468",
                "jenisSrc" => "6677",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback_produk",
                    "extern_nilai5" => "nilai_kas_cn",
                    "extern2_id" => "9",
                    "extern2_nama" => "customer",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya
                ),
            ),
        ),
    ),
    "16677" => array(
        2 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "hutang_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "16677",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback",
                    "extern2_id" => "9",
                    "extern2_nama" => "customer",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "valueSrc" => "hutang_pph21",
                "jenisTarget" => "1483",
                "jenisSrc" => "16677",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "freelancerDetails",// diisi customer
                    "extern_nama" => "freelancerDetails__nama",// diisi customer
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback",
                    "extern2_id" => "11",
                    "extern2_nama" => "freelancer",
                    "extern5_id" => "pihakMainRulesID",
                    "extern5_nama" => "pihakMainRulesName",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya

                ),
            ),
//            array(
//                "label" => "hutang komisi",
//                "valueSrc" => "hutang_komisi",
//                "jenisTarget" => "1488",// pembayaran hutang komisi
//                "jenisSrc" => "16677",
//                "externSrc" => array(
////                    "id" => "pihakID",
////                    "nama" => "pihakName",
////                    "id" => "freelancerDetails",// diisi customer
////                    "nama" => "freelancerDetails__nama",// diisi customer
//                    "id" => "place2ID",//
//                    "nama" => "place2Name",//
//                    "extLabel" => "branch",
////                    "extern_id" => "pihakID",
////                    "extern_nama" => "pihakName",
//                    "extern_id" => "freelancerDetails",// diisi customer
//                    "extern_nama" => "freelancerDetails__nama",// diisi customer
//                    "npwp" => "customerDetails__npwp",
//                    "extern_nilai2" => "biaya_cashback",
//                    "extern2_id" => "11",
//                    "extern2_nama" => "freelancer",
//                    "extern5_id" => "pihakMainRulesID",
//                    "extern5_nama" => "pihakMainRulesName",
//                    "cabang_id" => "placeID",
//                    "cabang_nama" => "placeName",
//                    "extern4_id" => "pihakID",
//                    "extern4_nama" => "pihakName",
//                ),
//            ),
        ),
    ),
    "16678" => array(
        2 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "hutang_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "16678",
//                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback",
                    "extern2_id" => "9",
                    "extern2_nama" => "customer",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "valueSrc" => "hutang_pph21",
                "jenisTarget" => "1483",
                "jenisSrc" => "16678",
//                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
//                    "extern_id" => "pihakID",
//                    "extern_nama" => "pihakName",
                    "extern_id" => "freelancerDetails",// diisi customer
                    "extern_nama" => "freelancerDetails__nama",// diisi customer
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback",
                    "extern2_id" => "11",
                    "extern2_nama" => "freelancer",
                    "extern5_id" => "pihakMainRulesID",
                    "extern5_nama" => "pihakMainRulesName",

                    "biaya_rekening" => ".6010",// berisi coa
                    "biaya_rekening_label" => ".biaya usaha",// label coa
                    "biaya_rekening_id" => "pihakMainID",// id pembantu biaya
                    "biaya_rekening_id_label" => "pihakMainName",// nama pembantu biaya
                    "cabang2_id" => "place2ID",// id cabang pembebanan biaya
                    "cabang2_nama" => "place2Name",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang komisi",
                "valueSrc" => "hutang_komisi_pym_src",
                "jenisTarget" => "1488",// pembayaran hutang komisi
                "jenisSrc" => "16678",
                "externSrc" => array(
                    "id" => "freelancerDetails",//
                    "nama" => "freelancerDetails__nama",//
                    "extLabel" => "branch",
//                    "extern_id" => "freelancerDetails",// diisi freelancer global
//                    "extern_nama" => "freelancerDetails__nama",// diisi freelancer global
                    "npwp" => "customerDetails__npwp",
                    "extern_nilai2" => "biaya_cashback",
                    "cabang_id" => "placeID",
                    "cabang_nama" => "placeName",
                    "extern2_id" => "11",
                    "extern2_nama" => "freelancer",
                    "extern3_id" => "11",
                    "extern3_nama" => "freelancer",
                    "extern4_id" => "pihakID",
                    "extern4_nama" => "pihakName",
                    "extern5_id" => "place2ID",
                    "extern5_nama" => "place2Name",
                ),
            ),
        ),
    ),

    "3674" => array(
        2 => array(
            array(
                "label" => "budget project",
                "valueSrc" => "piutang_tambah",
                "jenisTarget" => "3675",
                "jenisSrc" => "3674",
                "externSrc" => array(
                    "id" => "pihakWoProjekEmployee",
                    "nama" => "pihakWoProjekEmployeeName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakWoProjekEmployee",
                    "extern_nama" => "pihakWoProjekEmployeeName",
                    "project_id" => "pihakWoProjek",
                    "project_nama" => "pihakWoProjekName",
                    "customers_id" => "pihakProjekCustomerID",
                    "customers_nama" => "pihakProjekCustomerName",
                    "extern2_id" => ".1",//pph
                    "extern2_nama" => ".dipotong",
                    "extern3_id" => "pihakWoProjek",//workorder
                    "extern3_nama" => "pihakWoProjekName",
                    "extern4_nama" => "pihakWoProjekSpk",//spk
                    "extern5_id" => "place2ID",
                    "extern5_nama" => "place2Name",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",


                ),
            ),
        ),
    ),
    "3675" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "3675",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "dppPPh",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "3675",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "source_dpp",
                    "extern_nilai5" => "source_dpp",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "placeID",// id cabang pembebanan biaya
                    "cabang2_nama" => "placeName",// nama cabang pembebanan biaya
                ),
            ),
        ),
    ),
    "477" => array(
        1 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "hutang_pph23_nilai",
                "jenisTarget" => "115",
                "jenisSrc" => "3675",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pphVendor",
                    "nama" => "pphVendor__nama",
                    "extLabel" => "branch",
                    "extern_id" => "pphVendor",
                    "extern_nama" => "pphVendor__nama",
                    "npwp" => "pphVendor__npwp",
                    "extern_nilai2" => "dppPPh",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "pihakID",// id cabang pembebanan biaya
                    "cabang2_nama" => "pihakName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "label_key" => "hutang pph21",
                "valueSrc" => "hutang_pph21_nilai",
                "jenisTarget" => "1483",
                "jenisSrc" => "3675",
                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pphVendor",
                    "nama" => "pphVendor__nama",
                    "extLabel" => "branch",
                    "extern_id" => "pphVendor",
                    "extern_nama" => "pphVendor__nama",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "dppPPh",
                    "extern_nilai5" => "dppPPh",
                    "extern2_id" => "2",
                    "extern2_nama" => "supplier",

                    "biaya_rekening" => ".6100010",// berisi coa
                    "biaya_rekening_label" => ".biaya belum ditempatkan",// label coa
                    "biaya_rekening_id" => ".28",// id pembantu biaya
                    "biaya_rekening_id_label" => ".BIAYA/JASA",// nama pembantu biaya

                    "cabang2_id" => "pihakID",// id cabang pembebanan biaya
                    "cabang2_nama" => "pihakName",// nama cabang pembebanan biaya
                ),
            ),
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "477",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),

    "1676" => array(
        2 => array(
            array(
                "label" => "hutang pph23",
                "valueSrc" => "nilai_pph23",
                "jenisTarget" => "115",
                "jenisSrc" => "1676",
//                "model" => "MdlTaxesStatic",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "nilai_pph23",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",
                ),
            ),
            array(
                "label" => "hutang pph 21",
                "valueSrc" => "nilai_pph21",
                "jenisTarget" => "1483",
                "jenisSrc" => "1676",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "branch",
                    "extern_id" => "pihakID",
                    "extern_nama" => "pihakName",
                    "npwp" => "vendorDetails__npwp",
                    "extern_nilai2" => "nilai_pph21",
                    "extern2_id" => "3",
                    "extern2_nama" => "cabang",
                ),
            ),
        ),
    ),

    "7488" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "759",
                "jenisSrc" => "7488",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),

        ),
    ),

    "7468" => array(
        1 => array(
            array(
                "label" => "hutang setoran",
                "valueSrc" => "nilai_entry",
                "jenisTarget" => "759",
                "jenisSrc" => "7468",
                "externSrc" => array(
                    "id" => "olehID",
                    "nama" => "olehName",
                    "extLabel" => "person",
                    "payment_locked" => "paymentSrcLock",
                    "cash_account" => "cash_account",
                    "cash_account_nama" => "cash_account__nama",
                    "extern2_id" => "pihakID",
                    "extern2_nama" => "pihakName",
                ),
            ),
        ),
    ),

//    "588" => array(
//        2 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "nett1_bulat",
//                "jenisTarget" => "4469",
//                "jenisSrc" => "588",
//                "externSrc" => array(
//                    "id" => "customerDetails",
//                    "nama" => "customerDetails__nama",
//                    "extLabel" => "project",
////                    "ppn" => "nilai_tambah_ppn_in",
////                    "ppn_status" => "1", // butuh diapprove ppn masukannya...
//                ),
//            ),
//        ),
//    ),

    "487" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "487",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "1477" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1477",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "475" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "475",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "1475" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1475",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "4821" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "4821",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "1487" => array(
        1 => array(
            array(
                "label" => "hutang creditcard",
                "valueSrc" => "nilai_dipakai_credit_card",
                "jenisTarget" => "4811",
                "jenisSrc" => "1487",
                "externSrc" => array(
                    "id" => "credit_card_account",
                    "nama" => "credit_card_account__label",
                    "extern2_id" => "credit_card_account__kartu_id",
                    "extern2_nama" => "credit_card_account__kartu_nama",
                    "extern2_label" => "reguler",
                    "extern3_id" => "credit_card_account__folders",
                    "extern3_nama" => "credit_card_account__folders_nama",
                ),
            ),
        ),
    ),
    "771" => array(
        1 => array(
            array(
                "label" => "uang muka supplier",
                "valueSrc" => "um_nilai",
                "jenisTarget" => "0464", // uangmuka ada ppn
                "jenisSrc" => "771",
                "externSrc" => array(
                    "id" => "supplier_id",
                    "nama" => "supplier_nama",
                    "extern2_id" => "referensiSo",
                    "extern2_nama" => "referensiSo__label",
                    "extern_date2" => "referensiSo__fulldate",
                    "extLabel" => "vendor",
                    "dpp_ppn" => "um_nilai",
                    "ppn" => "ppn_nilai_biaya",
                    "ppn_sisa" => "ppn_nilai_biaya",
                ),
                "method" => "update",
                "methodFilter" => array(
                    "target_jenis=.0464",
                    "extern_id=supplier_id",
                    "extern2_id=referensiSo",
                    "cabang_id=placeID",
                    "label=.uang muka supplier",
                ),
            ),
        ),

    ),
);

$config['payment_pembantu_Source'] = array(
    //jalan di items akan looping khusus untuk project
    "3674" => array(
        2 => array(
            array(
                "label" => "budget project",
                "valueSrc" => "biaya_tambahan",
                "jenisTarget" => "3675",
                "jenisSrc" => "3674",
                "gate" => "items2",
                "externSrc" => array(
                    "id" => "biaya_dasar_id",
                    "nama" => "biaya_dasar_nama",
                    "extLabel" => "branch",
                    "extern_id" => "id",//biaya dasar_id
                    "extern_nama" => "nama",//biaya dasar nama
                    "project_id" => "project_id",
                    "project_nama" => "project_nama",
//                    "customers_id"=>"pihakProjekCustomerID",
//                    "customers_nama"=>"pihakProjekCustomerName",
                    "produk_id" => "id",//
                    "produk_nama" => "nama",//
                    "extern2_id" => "biaya_id",//biaya index
                    "extern2_nama" => "biaya_nama",
                    "extern3_id" => "wo_id",//workorder
                    "extern3_nama" => "wo_nama",
                    "extern4_nama" => "no_spk",//spk
                    "extern5_id" => "place2ID",
                    "extern5_nama" => "place2Name",
                    "cabang_id" => "place2ID",
                    "cabang_nama" => "place2Name",


                ),
            ),
        ),
    ),
);

$config['payment_antiSource'] = array(
//    "967" => array(
//        array(
//            "label" => "hutang dagang",
//            "valueSrc" => "nett",
//            "jenisTarget" => "489",
//            "jenisSrc" => "467",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "vendor",
//            ),
//        ),
//    ),
//    "961" => array(
//        array(
//            "label" => "hutang dagang",
//            "valueSrc" => "nett",
//            "jenisTarget" => "487",
//            "jenisSrc" => "461",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "vendor",
//            ),
//        ),
//    ),

//    "982" => array(
//        array(
//            "label" => "piutang dagang",
//            //            "valueSrc" => "harga_nett3",
//            //            "valueSrc"    => "tagihan",
//            "valueSrc" => "nett2",
//            "jenisTarget" => "749",
//            "jenisSrc" => "582",
//            "externSrc" => array(
//                "id" => "pihakID",
//                "nama" => "pihakName",
//                "extLabel" => "customer",
//            ),
//        ),
//    ),
    "1984" => array(
        array(
            "label" => "piutang dagang jasa",
            //            "valueSrc" => "harga_nett3",
            //            "valueSrc"    => "tagihan",
            "valueSrc" => "nett2",
            "jenisTarget" => "1784",
            "jenisSrc" => "584",
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
            ),
        ),
    ),
    "980" => array(
        array(
            "label" => "piutang dagang",
            "valueSrc" => "harga_nett3",
            "jenisTarget" => "749",
            "jenisSrc" => "580",
            "externSrc" => array(
                "id" => "pihakID",
                "nama" => "pihakName",
                "extLabel" => "customer",
            ),
        ),
    ),
);

$config['uang_muka'] = array(
    //ini off pindah ke ComUangMukaSourceDetail karena sumber berasal dari items....subject jenis uang muka, contoh uang muka pembelaian, uang muka asurnsi ....off dulu
    // direvisi balik lagi ke sini sebagai subject vendor
    "464" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "um_noppn_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                ),
            ),
        ),
    ),
    "4643" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "um_noppn_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "referensi_so",
                    "extern2_nama" => "referensi_so__label",
                ),
            ),
            //uang muka non relasi PO
            array(
                "label" => "uang muka nonrelasi",
                "valueSrc" => "um_noppn_nonrelasi_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
//                    "extern2_id" => "referensi_so",
//                    "extern2_nama" => "referensi_so__label",
                    "extern2_id" => "0",
                    "extern2_nama" => "0",
                ),
            ),
        ),
    ),
    "4644" => array(
        1 => array(
            //masuk  dari jurnal
            array(
//                "label" => "uang muka",//salah posting ini untuk relasi pos
                "label" => "uang muka nonrelasi",
                "valueSrc" => "nilai_uang_muka__nonrelasi_source",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "targetSo",
                    "extern2_nama" => "targetSo__nomer",
                ),
            ),
            //jika hanya pindah referensi jrunal pembantu
            array(
                "label" => "uang muka",
                "valueSrc" => "nilai_uang_muka_source",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "targetSo",
                    "extern2_nama" => "targetSo__nomer",
                ),
            ),
            //uang muka non relasi PO
//            array(
//                "label" => "uang muka nonrelasi",
//                "valueSrc" => "um_noppn_nonrelasi_nilai",
//                "jenisTarget" => "1464", // target tidak dipakai
//                "jenisSrc" => "464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "vendor",
//                    "extern2_id" => "targetSo",
//                    "extern2_nama" => "targetSo",
//                ),
//            ),

        ),
    ),
    "1967a" => array(
        2 => array(
//            uang muka non relasi PO
            array(
                "label" => "uang muka nonrelasi",
                "valueSrc" => "titipan_fullfill_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "0",
                    "extern2_nama" => "0",
                    "stepcode" => "0",
                ),
            ),
        ),
    ),
    "9994" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "um_noppn_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "referensi_so",
                    "extern2_nama" => "referensi_so__label",
                ),
            ),
            //uang muka non relasi PO
            array(
                "label" => "uang muka nonrelasi",
                "valueSrc" => "um_noppn_nonrelasi_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "vendor",
                    "extern2_id" => "referensi_so",
                    "extern2_nama" => "referensi_so__label",
                ),
            ),
        ),
    ),
//    "4464" => array(
//        1 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "tagihan",
//                "jenisTarget" => "04464", // target tidak dipakai
//                "jenisSrc" => "4464",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                ),
//            ),
//        ),
//    ),

    "4465" => array(
        1 => array(
            array(
                "label" => "uang muka",
                "valueSrc" => "tagihan",
                "jenisTarget" => "04465", // target tidak dipakai
                "jenisSrc" => "4465",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extLabel" => "customer",
                ),
            ),
        ),
    ),
    "4467" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_uang_muka_source",
                "jenisTarget" => "04467", // uangmuka ada ppn
                "jenisSrc" => "4467",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => "referensi_so__id",
                    "extern2_nama" => "referensi_so__nomer",
                    "extern_date2" => "referensi_so__fulldate",
                    "extLabel" => "customer",
                    "project_id" => "customer",
                ),
            ),
        ),
    ),
    "4656" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "nilai_uang_muka_source",
                "jenisTarget" => "04467",
                "jenisSrc" => "4656",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
                    "extern2_id" => ".0",
                    "extern2_nama" => ".0",
//                    "extern_date2" => "referensi_so__fulldate",
//                    "project_id" => "referensi_so__project_id",
//                    "project_nama" => "referensi_so__project_nama",
                    "extLabel" => "customer",
                ),
            ),
        ),
    ),
    "4464___" => array(
        1 => array(
            array(
                "label" => "uang muka konsumen",
                "valueSrc" => "deposit_konsumen",
                "jenisTarget" => "04464", // uangmuka ada ppn
                "jenisSrc" => "4464",
                "externSrc" => array(
                    "id" => "pihakID",
                    "nama" => "pihakName",
//                    "extern2_id" => "referensi_so__id",
//                    "extern2_nama" => "referensi_so__nomer",
//                    "extern_date2" => "referensi_so__fulldate",
//
                    "extLabel" => "customer",

                ),
            ),
        ),
    ),
    "771" => array(
        1 => array(
            //uang muka non relasi PO
            array(
                "label" => "uang muka nonrelasi",
                "valueSrc" => "titipan_nilai",
                "jenisTarget" => "1464", // target tidak dipakai
                "jenisSrc" => "771",
                "externSrc" => array(
                    "id" => "supplierTitipan__id",
                    "nama" => "supplierTitipan__nama",
                    "extLabel" => "vendor",
//                    "extern2_id" => "referensi_so",
//                    "extern2_nama" => "referensi_so__label",
                    "extern2_id" => "0",
                    "extern2_nama" => "0",
                ),
            ),
        ),
    ),
//    "4465" => array(
//        2 => array(
//            array(
//                "label" => "uang muka",
//                "valueSrc" => "uang_muka_dpp",
//                "valueSrcPpn" => "uang_muka_ppn",
//                "jenisTarget" => "04465", // target tidak dipakai
//                "jenisSrc" => "4465",
//                "externSrc" => array(
//                    "id" => "pihakID",
//                    "nama" => "pihakName",
//                    "extLabel" => "customer",
//                ),
//            ),
//        ),
//    ),
);

$config['transaksi_createIndex'] = array(
    "749" => "Transaksi/index",
    "1749" => "Transaksi/index",
    "2749" => "Transaksi/index",
    "489" => "Transaksi/index",
    "488" => "Transaksi/index",//FG prepaid
    "486" => "Transaksi/index",//RM prepaid
    "485" => "Transaksi/index",//SERVICE prepaid
    "1485" => "Transaksi/index",//SERVICE prepaid
    "1483" => "Transaksi/index",//SERVICE prepaid
    "1487" => "Transaksi/index",//SERVICE prepaid
    "487" => "Transaksi/index",
    "462" => "Transaksi/index",
    "400" => "Transaksi/index",
    "700" => "Transaksi/index",
    "7001" => "Transaksi/index",
    "759" => "Transaksi/index",
    "771" => "Transaksi/index",
    "1771" => "Transaksi/index",
    "473" => "Transaksi/index",
    "652" => "Transaksi/index",
    "682" => "Transaksi/index",
    "475" => "Transaksi/index",
    "477" => "Transaksi/index",
    "476" => "Transaksi/index",
    "1784" => "Transaksi/index",
    "5682" => "Transaksi/index",
    "4821" => "Transaksi/index",
    "114" => "Transaksi/index",
    "115" => "Transaksi/index",
    //    "1784" => "Transaksi/index",
    "4447" => "Transaksi/index",
    "4448" => "Transaksi/index",
    "4410" => "Transaksi/index",
    "5684" => "Transaksi/index",
    "483" => "Transaksi/index",
    "1424" => "Transaksi/index",
    "4891" => "Transaksi/index",
    "1462" => "Transaksi/index",
    "1120" => "Transaksi/index",
    "2119" => "Transaksi/index",
    "4411" => "Transaksi/index",
    "1475" => "Transaksi/index",
    "6475" => "Transaksi/index",
    "1477" => "Transaksi/index",
    "7499" => "Transaksi/index",
);

//config untuk mengabaikan nulis rekening pembantu jika nilai 0
$config['transaksi_value_required_components'] = array(
    "ComRekeningPembantuHutangBpjs",
    "ComRekeningPembantuSubHutangBpjs",
    "ComRekeningPembantuSupplies",
    "ComRekeningPembantuSuppliesProses",
    "ComRekeningPembantuBiayaSewa",
    "ComRekeningPembantuSupplier",
    "ComRekeningPembantuProduk",
    "ComRekeningPembantuValas",
    "ComRekeningPembantuKas",
    "ComRekeningPembantuPph",
    "ComRekeningPembantuEkspedisi",
    "ComRekeningPembantuCustomer",
    "ComRekeningPembantuAntarcabang",
    "ComRekening",
    "ComJurnal",
    "ComRekeningPembantuBiayaMain",

    "ComRekeningPembantuBiayaKomposisiProduksi",
    "ComRekeningPembantuBiaya",
    "ComRekeningPembantuBiayaProduksi",
    "ComRekeningPembantuBiayaUmumMain",
    "ComRekeningPembantuBiayaUmum",
    "ComRekeningPembantuBiayaUsahaMain",
    "ComRekeningPembantuBiayaUsaha",
    "ComRekeningPembantuCustomerValas",
    "ComRekeningPembantuPendapatan",
    "ComRekeningPembantuAktivaTetap",
    "ComRekeningPembantuAkumPenyusutanAktivaTetap",
    "ComRekeningPembantuBiayaJasa",
    "ComRekeningPembantuBiayaOperasional",
    "ComRekeningPembantuEfisiensiBiaya",
    "ComRekeningPembantuEfisiensiBiayaMain",
    "ComRekeningPembantuEfisiensiBiayaSubMain",
    "ComRekeningPembantuAktivaBerwujud",
    "ComRekeningPembantuAktivaBerwujudMain",//ini tanpa qty hanya value dari supplies ke aktiva
    "ComRekeningPembantuBank",
    "ComRekeningPembantuRelasiRekeningKoran",
    "ComRekeningPembantuRekeningKoran",
    "ComRekeningPembantuRekeningKoranMain",
    "ComRekeningPembantuBiayaMain",
    "ComRekeningPembantuBiaya",
    "ComRekeningPembantuUangMukaMain",
    "ComRekeningPembantuUangMuka",
    "ComRekeningPembantuPphMain",
    "ComRekeningPembantuLRLainlain",
    "ComRekeningPembantuBiayaUsahaMain",
    "ComRekeningPembantuBiayaUmumMain",
    "ComRekeningPembantuBiayaUsahaSubMain",
    "ComRekeningPembantuBiayaUmumSubMain",

    "ComPaymentUangMukaCustomer",
    "ComPaymentUangMukaSupplier",
    "ComPaymentUangMukaSupplierValas",
    "ComPaymentAntisourceCustomer",
    "ComRekeningPembantuEfisiensiBiayaFaseMain",
    "ComRekeningPembantuEfisiensiBiayaFase",
    "ComRekeningPembantuReseller",
    "ComRekeningPembantuPenjualan",
    "ComRekeningPembantuPenjualanKonsumen",
    "ComRekeningPembantuPenjualanSeller",
    "ComRekeningPembantuHpp",
    "ComRekeningPembantuCustomerDetail",
    "ComTransaksiKreditLimit",

    "ComRekeningPembantuPiutangSupplier",
    "ComRekeningPembantuPiutangSupplierMain",
    "ComRekeningPembantuPiutangSupplierItem",
    "ComRekeningPembantuPiutangSupplierDetail",
    "ComRekeningPembantuPiutangSupplierDetailMain",
    "ComRekeningPembantuPiutangSupplierDetailItem",
    "ComRekeningPembantuPiutangSupplierDetailTransMain",
    "ComRekeningPembantuPiutangSupplierDetailTransItem",
    "ComRekeningPembantuPiutangSupplierDetailTransProdukItem",
    "ComRekeningPembantuCreditNote",
    "ComRekeningPembantuBiayaHarusDibayar",
    "ComLockerDiskonValue",
    "ComRekeningPembantuRawMainEfisiensi",//efiesnsi project
    "ComRekeningPembantuUangMukaMainReference",//uang muka/titipan yang berelasi dengan PO
    "ComRekeningPembantuLogamMulia",//logam mulia, hanya dijalankan saat pilih logam mulia
    "ComLockerPreDiskonValue",//logam mulia, hanya dijalankan saat pilih logam mulia
//    "ComRekeningPembantuProdukPerSerial",//logam mulia, hanya dijalankan saat pilih logam mulia
    "ComRekeningPembantuKomisi",
    "ComRekeningPembantuKomisiItem",
    "ComRekeningPembantuBiayaProjectMain",
    "ComRekeningPembantuBiayaProject",
    "ComRekeningPembantuCustomerDetailItem",
    //-----
    "ComRekeningPembantuCustomerProject",
    "ComRekeningPembantuCustomerProjectDetail",
    "ComRekeningPembantuPenjualan",
    "ComRekeningPembantuPenjualanProject",
    "ComRekeningPembantuCreditCard",
    "ComRekeningPembantuPpnProject",
);

$config['transaksi_static_columns'] = array(
    "pihakID",
    "cabangID",
    "cabang2ID",
    "placeID",
    "place2ID",
    "gudangID",
    "gudang2ID",
    "customerID",
    "stepCode",
    "olehID",
    "customerID",
    "supplierID",
    "ppnFactor",
    "jenisTr",
    "masterID",

    "id", "jml", "refID",
);

$config['transaksi_global_validationRules'] = array(
    "main" => array(
        "cabangID",
        "placeID",
        "gudangID",
        "olehID",

    ),
    "items" => array(
        "jml",
        "harga",
        "id",

    ),
    "tableIn_master" => array(
        "cabang_id",
        "gudang_id",
        "oleh_id",

    ),
    "tableIn_detail" => array(
        "produk_ord_jml",
        "produk_ord_hrg",
        "produk_id",

    ),
);

$config['heGlobalPopulators'] = array(
    //untuk 1 level array
    "items" => "main",
    //        "items2" => "main",
    "items2_sum" => "main",
    "items3_sum" => "main",
    "items4_sum" => "main",
    "items5_sum" => "main",
    "items6_sum" => "main",
    "items7_sum" => "main",
    "items8_sum" => "main",
    "rsltItems" => "main",
    "rsltItems2" => "main",
    "rsltItems3" => "main",
);
$config["GlobalPopulator_sub"] = array(
    //untuk 2 level array
    "items6" => "main",
);

$config['transaksi_itemRecapExceptions'] = array(
//    "stok",
//    "stok_booking",
//    "stok_total",
//    "stok_center",
    "has_fasilitas",
    "tarif_efektif_total",
    "dateFaktur",
    "eFaktur",
    "type_cashback",
    "biaya_nilai",
    "ppnMethode__key",
    "lewati_state",
    "pym_terbayar_nett",
    "cabangRef_id",
    "cabangRef_nama",
    "asetKategory__coa_code",
    "jenis_ref_po",
    "dateFaktur",
    "eFaktur",
    "divID",
    "div_id",
    "rek_coa",
    "extern_coa",
    "transaksi_ref_id_po",
    "transaksi_ref_po_nomer",
    "nilai_uang_muka_source",
    "nilai_uang_muka__nonrelasi_source",
    "ppnTransaksi",
    "note",
    "projectCabangID",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihakMainAkumDetails",

    "rekName_2_coa",
    "pihakMainChild_coa",
    "qty",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",
    "pihakMainID",
    "pihakMainName",

    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasID",
    "valasName",
    "valasFactor",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppv_index__nilai",
    "transaksi_id",
    "sent_jml",
    "detilSize",
    "srcPosition",
    "srcAccount",
    "srcRel",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",
    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    "costNameCoa",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "comRekName_1_child_coa",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    "fase_id",
    //------
    "pihakMainChild",
    "comName_items",
    "extern2_id",
    "extern2_nama",
    "dtaDetail",
    "dtaDetail__label",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "nilai_bayar",
    "nilai_bayar_valas",
    "valas_nilai_bayar",
    "uangMuka",

    "jenisTr_reference",
    "bunga",

    "comRekName_1_child",
    "comRekName_2_child",
    "comRekName_3_child",
    "rekName_1_child",
    "rekName_2_child",
    "rekName_3_child",
    "rekName1IDChild",
    "rekName2IDChild",
    "rekName3IDChild",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "biayaJasa",
    "biaya_jasa",
    "akun_pph_id",
    "akun_pph_label",
//    "tarif_pph",

    "cashMethode",
    "cashMethodeOption",

//    "pph_persen_ext",
    "pihak2Exchange",
//    "extern_nilai2",//dioffkan karena pph 23 tidak tampil di servive purcahsing A/P ppayment by widi
    "kurs__exchange",
    "kurs_actual",
    "referenceID",

    "sellerID",
    "sellerName",
    "valid_ppn",
    //-----------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",

    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",
    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "outdoor_sku",
    "indoor_sku_1",
    "indoor_sku_2",
    "indoor_sku_3",
    "indoor_sku_4",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    "jenis_source",
    //"pph23Methode__tarif",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "pihakMainID_diskon",
    "pihakMainName_diskon",
    "pihakMainLabel_diskon",
    "cek_pph",
    "pph23Methode__tarif",
    "pph21Methode__tarif",
    "kompensasiMethod",
    "pph__tarif",
    "produk_order",
    "produk_sent",

    "marketplaceID",
    "marketplaceNama",
    "marketplaceName",
    "tipe_penjualan",
    "tipe_penjualan_id",
    "tipe_penjualan_nama",
    "extern3_id",
    "extern3_nama",
    "extern4_id",
    "extern4_nama",
    "requestReferenceSoID",
    "requestReferenceSoNomer",
    "requestReferenceSoIDTop",
    "requestReferenceSoNomerTop",
    "requestReferenceSoJenis",
    "requestReferenceSoJenisTop",
    "requestReferenceSoJenisMaster",
    "ebillingDate",
    "ebilling",
    "valas",
    "valas__label",
    "valas__nama",
    "gudangTujuan",
    "gudangTujuanID",
    "gudangTujuanNama",
    "gudangTujuanName",
    "gudangTujuanLabel",
    "cash_account",
    "cash_account__label",
    "cash_account__folders_nama",
    "cash_account__nama",
    "cash_account__machine_id",
    "cash_account__jenis",
    "cash_account__folders",
    "cash_account_id",
    "cash_account_nama",
    "description",
    "uang_muka_nppn_relasi_soproject",
    "gudang_asal",
    "gudang_asal__id",
    "gudang_asal__label",
    "gudang_asal__nama",
    "gudang_asal__name",
    "gudang",
    "gudang__id",
    "gudang__label",
    "gudang__nama",
    "gudang__name",
    "cabang_pembebanan_id",
    "cabang_pembebanan_nama",
    "cabang_pembebanan_kode",
); // ke atas
//subitems untuk 2level array /items2,items6,items7
$config['transaksi_subitemRecapExceptions'] = array(
    "has_fasilitas",
    "tarif_efektif_total",
    "dateFaktur",
    "eFaktur",
    "type_cashback",
    "ppnMethode__key",
    "lewati_state",
    "pym_terbayar_nett",
    "cabangRef_id",
    "cabangRef_nama",
    "asetKategory__coa_code",
    "dateFaktur",
    "eFaktur",
    "divID",
    "div_id",
    "ppnTransaksi",
    "note",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihakMainAkumDetails",
    "projectCabangID",
    "rekName_2_coa",
    "pihakMainChild_coa",
    "qty",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",
    "pihakMainID",
    "pihakMainName",

    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasID",
    "valasName",
    "valasFactor",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppv_index__nilai",
    "transaksi_id",
    "sent_jml",
    "detilSize",
    "srcPosition",
    "srcAccount",
    "srcRel",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",
    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    "costNameCoa",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    "comRekName_1_child_coa",
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    "fase_id",
    //------
    "pihakMainChild",
    "comName_items",
    "extern2_id",
    "extern2_nama",
    "dtaDetail",
    "dtaDetail__label",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "nilai_bayar",
    "nilai_bayar_valas",
    "valas_nilai_bayar",
    "uangMuka",

    "jenisTr_reference",
    "bunga",

    "comRekName_1_child",
    "comRekName_2_child",
    "comRekName_3_child",
    "rekName_1_child",
    "rekName_2_child",
    "rekName_3_child",
    "rekName1IDChild",
    "rekName2IDChild",
    "rekName3IDChild",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "biayaJasa",
    "biaya_jasa",
    "akun_pph_id",
    "akun_pph_label",
//    "tarif_pph",

    "cashMethode",
    "cashMethodeOption",

//    "pph_persen_ext",
    "pihak2Exchange",
//    "extern_nilai2",//dioffkan karena pph 23 tidak tampil di servive purcahsing A/P ppayment by widi
    "kurs__exchange",
    "kurs_actual",
    "referenceID",

    "sellerID",
    "sellerName",
    "valid_ppn",
    //-----------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",

    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",
    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "harga",
    "subtotal",
    "jenis_source",
    //"pph23Methode__tarif",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
//    "harga",
    "pihakMainID_diskon",
    "pihakMainName_diskon",
    "pihakMainLabel_diskon",
    "cek_pph",
    "pph23Methode__tarif",
    "pph21Methode__tarif",
    "kompensasiMethod",
    "pph__tarif",
    "produk_order",
    "produk_sent",

    "marketplaceID",
    "marketplaceNama",
    "marketplaceName",
    "tipe_penjualan",
    "tipe_penjualan_id",
    "tipe_penjualan_nama",
    "extern3_id",
    "extern3_nama",
    "extern4_id",
    "extern4_nama",
    "requestReferenceSoID",
    "requestReferenceSoNomer",
    "requestReferenceSoIDTop",
    "requestReferenceSoNomerTop",
    "requestReferenceSoJenis",
    "requestReferenceSoJenisTop",
    "requestReferenceSoJenisMaster",
    "ebillingDate",
    "ebilling",
    "valas",
    "valas__label",
    "valas__nama",
    "gudangTujuan",
    "gudangTujuanID",
    "gudangTujuanNama",
    "gudangTujuanName",
    "gudangTujuanLabel",
    "cash_account",
    "cash_account__label",
    "cash_account__folders_nama",
    "cash_account__nama",
    "cash_account__machine_id",
    "cash_account__jenis",
    "cash_account__folders",
    "cash_account_id",
    "cash_account_nama",
    "description",
    "uang_muka_nppn_relasi_soproject",
    "gudang_asal",
    "gudang_asal__id",
    "gudang_asal__label",
    "gudang_asal__nama",
    "gudang_asal__name",
    "gudang",
    "gudang__id",
    "gudang__label",
    "gudang__nama",
    "gudang__name",
    "cabang_pembebanan_id",
    "cabang_pembebanan_nama",
    "cabang_pembebanan_kode",
); // ke atas

$config['transaksi_itemPopulateExceptions'] = array(
    "has_fasilitas",
    "tarif_efektif_total",
    "dateFaktur",
    "eFaktur",
    "type_cashback",
    "biaya_nilai",
    "ppnMethode__key",
    "lewati_state",
    "pym_terbayar_nett",
    "cabangRef_id",
    "cabangRef_nama",
    "jenis_ref_po",
    "dateFaktur",
    "eFaktur",
    "divID",
    "div_id",
    "rek_coa",
    "extern_coa",
    "nilai_uang_muka_source",
    "nilai_uang_muka__nonrelasi_source",
    "ppnTransaksi",
    "note",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "id",
    //    "jml",
    "pihakMainCoa",
    "pihakMainAkum",
    "pihak2Coa_code",
    "projectCabangID",
    "comRekName_2_coa",
//    "rekName_5_coa",
//    "comRekName_5_coa",

    "pihakMainChild_coa",
    "rekName_2_coa",
    "pihak3Coa_code",
    "costID_coa",
    "costNameCoa",
    "pihakMainAkumDetails",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "comRekName_1_child_coa",
    "produk_kode",
    "ppnFactor",
    "ppn_persen_dipakai",
    "handler",
    "nama",
    "name",
    "nomer",
    "label",
    "satuan",
    "next_substep_code",
    "next_subgroup_code",
    "sub_step_number",
    "sub_step_current",
    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNomer_top",
    "pihakExternMasterID",
    "jenisTr_reference",
    "seluruhnya",
    "valasID",
    "valasName",
    "valasFactor",
    "new_sisa",
    "new_sisa_valas",
    "sent_jml",
    "detilSize",
    "harga_source",
    "pihakMainID",
    "pihakMainName",
    "pihakMainChild",
    "source_ppn_persen",
    "pph_persen_ext",
    "extern2_id",
    "extern2_nama",
    "pph23_nilai",
    "non_pph",
    "reComs",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilaiMasuk",
    "bunga",
    "sewaPeriode",
    "sewaDtime_start",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "tarif_pph",
    "biayaJasa",
    "biaya_jasa",
    "allow_params_edit",
    "akun_pph_id",
    "akun_pph_label",
    "kurs__exchange",

    "sellerID",
    "sellerName",
    //-------------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",
    "valid_ppn",
    // "premi",
    // "premi_percent",

    "rel_target_num",
    "targetJenisNextStep",
    "targetJenisLabel",
    "targetJenis",
    "sourceJenis",

    "_stepCode_placeID",
    "_stepCode_olehID",
    "_stepCode_placeID_olehID",
    "_stepCode_placeID_olehID_customerID",
    "_stepCode_customerID",
    "_stepCode_placeID_customerID",
    "_stepCode_olehID_customerID",
    "_stepCode",
    "_stepCode_placeID_olehID_supplierID",
    "_stepCode_supplierID",
    "_stepCode_placeID_supplierID",
    "_stepCode_olehID_supplierID",
    //------------
    "shippingDate",
    "dtime_order",
    "dtime_kirim",
    "dtime_terima",
    "_step_1_nomer",
    "_step_1_olehName",
    "_step_2_nomer",
    "_step_2_olehName",
    "_step_3_nomer",
    "_step_3_olehName",
    "_step_4_nomer",
    "_step_4_olehName",
    "_step_5_nomer",
    "_step_5_olehName",
    "coa_code",
    //------
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    "fase_id",
    //------
    //------
    "efisiensiID_1_coa",
    "efisiensiID_2_coa",
    "efisiensiID_3_coa",
    "efisiensiID_4_coa",
    "efisiensiID_5_coa",
    "cost2ID_1_coa",
    "cost2ID_2_coa",
    "cost2ID_3_coa",
    "cost2ID_4_coa",
    "cost2ID_5_coa",
    "costID_1_coa",
    "costID_2_coa",
    "costID_3_coa",
    "costID_4_coa",
    "costID_5_coa",
    "asetKategory__coa_code",
    //------
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",

    "referensi_id",
    "referensi_jenis",
    "transaksi_id",
    "transaksi_count",
    "transaksi_jenis_count",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "gudangID_produk",
    "gudangName_produk",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",

    "barcode",
    "part_id_1",
    "part_nama_1",
    "part_barcode_1",
    "part_id_2",
    "part_nama_2",
    "part_barcode_2",
    "heater_id",
    "heater_nama",
    "heater_barcode",
    "outdoor_id",
    "outdoor_nama",
    "outdoor_barcode",
    "outdoor_sku",
    "indoor_sku_1",
    "indoor_sku_2",
    "indoor_sku_3",
    "indoor_sku_4",
    "indoor_id_1",
    "indoor_nama_1",
    "indoor_barcode_1",
    "indoor_id_2",
    "indoor_nama_2",
    "indoor_barcode_2",
    "indoor_id_3",
    "indoor_nama_3",
    "indoor_barcode_3",
    "indoor_id_4",
    "indoor_nama_4",
    "indoor_barcode_4",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    "jenis_source",
    //"pph23Methode__tarif",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "pihakMainID_diskon",
    "pihakMainName_diskon",
    "pihakMainLabel_diskon",
    "cek_pph",
    "pph23Methode__tarif",
    "pph21Methode__tarif",
    "kompensasiMethod",
    "pph__tarif",
    "produk_order",
    "produk_sent",

    "marketplaceID",
    "marketplaceNama",
    "marketplaceName",
    "tipe_penjualan",
    "tipe_penjualan_id",
    "tipe_penjualan_nama",
    "extern3_id",
    "extern3_nama",
    "extern4_id",
    "extern4_nama",
    "requestReferenceSoID",
    "requestReferenceSoNomer",
    "requestReferenceSoIDTop",
    "requestReferenceSoNomerTop",
    "requestReferenceSoJenis",
    "requestReferenceSoJenisTop",
    "requestReferenceSoJenisMaster",
    "ebillingDate",
    "ebilling",
    "valas",
    "valas__label",
    "valas__nama",
    "gudangTujuan",
    "gudangTujuanID",
    "gudangTujuanNama",
    "gudangTujuanName",
    "gudangTujuanLabel",
    "cash_account",
    "cash_account__label",
    "cash_account__folders_nama",
    "cash_account__nama",
    "cash_account__machine_id",
    "cash_account__jenis",
    "cash_account__folders",
    "cash_account_id",
    "cash_account_nama",
    "description",
    "uang_muka_nppn_relasi_soproject",
    "gudang_asal",
    "gudang_asal__id",
    "gudang_asal__label",
    "gudang_asal__nama",
    "gudang_asal__name",
    "gudang",
    "gudang__id",
    "gudang__label",
    "gudang__nama",
    "gudang__name",
    "cabang_pembebanan_id",
    "cabang_pembebanan_nama",
    "cabang_pembebanan_kode",
); // ke kanan

$config['transaksi_masterPopulateExceptions'] = array(
    "has_fasilitas",
    "tarif_efektif_total",
    "type_cashback",
    "ppnMethode__key",
    "cabangRef_id",
    "cabangRef_nama",
    "divID",
    "div_id",
    "note",
    "olehID",
    "pihakID",
    "placeID",
    "cabangID",
    "gudangID",
    "place2ID",
    "cabang2ID",
    "gudang2ID",
    "comRekName_1_child_coa",

    "projectCabangID",

//    "rekName_5_coa",
    "jenisTr",
    "jenisTrMaster",
    "masterID",
    "referenceID",
    "referenceJenis",
    "seluruhnya",
    "ppnFactor",
    "step_number",
    "step_current",
    "next_step_code",
    "next_group_code",
    "transaksi_jenis",
    "jenisTrMaster",
    "jenisTrTop",
    "stepCode",
    "stepNumber",
    "rekName_2_coa",


    //--ini dari cloner dibaw akesini biar gak direkap
    "olehID",
    "olehName",
    "pihakID",
    "pihakName",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "nomer",
    "masterID",
    "referenceID",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceJenis",
    "referenceNomer",
    "new_sisa",
    "new_sisa_valas",
    "ppv_index__nilai",
    "ppn_persen_dipakai",
    "harga_source",
    "extern_nilai2",
    "pph_23",
    "pph_persen_ext",
    "extern2_nama",
    //    "nilai_bayar",
    //    "valasID",
    //    "valasName",
    //    "valasFactor",
    // "premi",
    // "premi_percent",
    "sellerID",
    "sellerName",
    "valid_ppn",
    "coa_code",
    "pihakMainID_coa_name",
    "pihakName_name",
    "pihakMainCoa",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------
    "rowPreFifo",

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",
    "serial_bahan_baku",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",
    "transaksi_count",
    "transaksi_jenis_count",

    "produk_id",
    "produk_nama",
    "produk_jml",
    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    "diskon_id",// jenis diskon
    "diskon_nama",// jenis diskon
    "jenis_source",
    //"pph23Methode__tarif",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "pihakMainID_diskon",
    "pihakMainName_diskon",
    "pihakMainLabel_diskon",
    "kompensasiMethod",
    "pph__tarif",

    "marketplaceID",
    "marketplaceNama",
    "marketplaceName",
    "tipe_penjualan",
    "tipe_penjualan_id",
    "tipe_penjualan_nama",
    "extern3_id",
    "extern3_nama",
    "extern4_id",
    "extern4_nama",
    "requestReferenceSoID",
    "requestReferenceSoNomer",
    "requestReferenceSoIDTop",
    "requestReferenceSoNomerTop",
    "requestReferenceSoJenis",
    "requestReferenceSoJenisTop",
    "requestReferenceSoJenisMaster",
    "ebillingDate",
    "ebilling",
    "asetKategory__coa_code",
    "valas",
    "valas__label",
    "valas__nama",
    "gudangTujuan",
    "gudangTujuanID",
    "gudangTujuanNama",
    "gudangTujuanName",
    "gudangTujuanLabel",
    "cash_account",
    "cash_account__label",
    "cash_account__folders_nama",
    "cash_account__nama",
    "cash_account__machine_id",
    "cash_account__jenis",
    "cash_account__folders",
    "cash_account_id",
    "cash_account_nama",
    "dateFaktur",
    "eFaktur",
    "description",
    "uang_muka_nppn_relasi_soproject",
    "gudang_asal",
    "gudang_asal__id",
    "gudang_asal__label",
    "gudang_asal__nama",
    "gudang_asal__name",
    "gudang",
    "gudang__id",
    "gudang__label",
    "gudang__nama",
    "gudang__name",
    "cabang_pembebanan_id",
    "cabang_pembebanan_nama",
    "cabang_pembebanan_kode",
);

$config['transaksi_masterToItemCloners'] = array(
    "type_cashback",
    "biaya_nilai",
    "ppnMethode__key",
    "lewati_state",
    "pym_terbayar_nett",
    "olehID",
    "olehName",
    "sellerID",
    "sellerName",
    "pihakID",
    "pihakName",
    "pihakMainID_coa",
    "pihakMainID_coa_name",
    "pihakMainChild_coa",
    "rekName_2_coa",
//    "comRekName_5_coa",
//    "rekName_5_coa",
    "comRekName_2_coa",


    "projectCabangID",

    "jatuh_tempo",
    "supplierID",
    "supplierName",
    "customerID",
    "customerName",
    "nomer_top2",
    "pihakMainAkum",
    "pihakMainAkumDetails",

    "pihak2ID",
    "pihak2Name",
    "pihak2Mdl",
    "pihak2Com",
    "pihak2Coa_code",
    "pihak3Coa_code",
    "pihak3ID",
    "pihak3Name",
    "pihak3Mdl",
    "pihak3Com",

    "pihakMainCoa",
    "pihakMainID",
    "pihakMainName",
    "pihakMainChild",
    "pihakMainName",
    "pihakMainNameCoa",
    "pihakMainName2",
    "pihakMainName2Coa",
    "pihakMainName_rev",
    "pihakMainNameCoa_rev",
    "pihakMainName2_rev",
    "pihakMainName2Coa_rev",
    "pihakMainAkumID_coa",
    "pihakMainAkumName_coa",
    "comRekName_1_child_coa",
    "asetKategory__coa_code",
    "placeID",
    "placeName",
    "cabangID",
    "cabangName",
    "gudangID",
    "gudangName",
    "place2ID",
    "place2Name",
    "cabang2ID",
    "cabang2Name",
    "gudang2ID",
    "gudang2Name",
    "jenisTr",
    "jenisTrMaster",
    "nomer",
    "transaksi_id",
    "transaksi_no",
    "masterID",
    "referenceID",
    "referenceID_top",
    "referenceJenis",
    "referenceNomer",
    "referenceNumber",
    "referenceDtime",
    "referenceFulldate",
    "referenceCount",
    "referenceNomer_top",
    "pihakExternMasterID",
    "jenisTr_reference",
    "seluruhnya",
    "defWHID",
    "defWHID__label",
    "defWHID__name",
    "valasDetails__exchange",
    "valasDetails",
    "valasFactor",
    "valasID",
    "valasName",
    "ppv_index__nilai",
    //    "nilai_bayar",
    //    "new_sisa",
//    "new_sisa_valas",
//    "bayar_total",
    "pettycash_account",
    "pettycash_account__label",
    "pettycash_account__nama",
    "pettycash_plafon__saldo",
    "ppnFactor",
    "detilSize",

    "srcPosition",
    "srcAccount",
    "srcRel",
    "pph_23",
    "pph23_nilai",
    "non_pph",
    "terbayar_pph23",
    "costID",
    "costName",

    "costID_1",
    "costID_2",
    "costID_3",
    "costID_4",
    "costID_5",
    "costID_coa",

    "costName_1",
    "costName_2",
    "costName_3",
    "costName_4",
    "costName_5",
    "costNameCoa",

    "costIdCoa_1",
    "costIdCoa_2",
    "costIdCoa_3",
    "costIdCoa_4",
    "costIdCoa_5",
    "costNameCoa_1",
    "costNameCoa_2",
    "costNameCoa_3",
    "costNameCoa_4",
    "costNameCoa_5",
    //-----
    "cost2IdCoa_1",
    "cost2IdCoa_2",
    "cost2IdCoa_3",
    "cost2IdCoa_4",
    "cost2IdCoa_5",
    "cost2NameCoa_1",
    "cost2NameCoa_2",
    "cost2NameCoa_3",
    "cost2NameCoa_4",
    "cost2NameCoa_5",
    //-----
    "ppn_persen_dipakai",
    "harga_source",
    //dimatiin karena ppn geser dari item ke main hanya baca dari global aka main karena ada pembulatan dpp ke 1523-->1520
    // "valid_dpp",
    // "valid_ppn",
    "comName_items",
    "dtaDetail",
    "dtaDetail__label",
    "reComs",
    "externMain",
    "externMain__label",
    "branchTarget",
    "branchTarget__nama",
    "nilai_dpp_ppn",
    "uangMuka",
//    "extern2_nama",
    "jenisTr_reference",
    "branchTarget__nilai_persediaan",
    "branchTarget__placeID",
    "branchTarget__gudangID",
    "sewaPeriode",
    "sewaDtime_start",
    "tarif_pph",
    "biayaJasa",
    "biaya_jasa",
    "awal_pinjaman",
    "akun_pph_id",
    "akun_pph_label",
    "pihak2Exchange",
    "kurs__exchange",
    "kurs_actual",
    "cashMethode",
    "cashMethodeOption",
    "referenceID",
    //------------
    "produkProjek__transaksi_id_app",
    "produkProjek__transaksi_no_app",
    //------ tambahan data isinya transaksi yang dibatalakan
    "reference_id",
    "reference_nomer",
    "reference_jenis",
    "reference_id_top",
    "reference_nomer_top",
    "reference_jenis_top",
    //------

    "bomProdukID",
    "bomProdukNama",
    "bomProdukName",
    "bom_id",
    "bom_nama",
    "fase_id",
    "fase_nama",
    "gudang_source_id",
    "gudang_source_nama",
    "gudang_target_id",
    "gudang_target_nama",
    "currentID",
    "gudangID_produk",
    "gudangName_produk",
    "kode_produksi",

    "tanggalStart",
    "tenggatWaktu",
    "projectID",
    "projectName",
    "pihakProjekID",
    "pihakProjekID",
    "pihakProjekMasterID",
    "pihakProjekName",
    "pihakProjekValueSrc",
    "pihakProjekRevertStep",
    "pihakProjekDetailGate",
    "pihakProjekGudangID",
    "pihakProjekGudangName",
    "pihakProjekGudangNama",
    "pihakProjekCustomerID",
    "pihakProjekCustomerNama",
    "pihakProjekStartDtime",
    "pihakProjekEndDtime",
    "pihakProjekWorkOrderID",
    "pihakProjekWorkOrderNama",
    "gudangProjectID",
    "gudangProjectName",
    "gudangProjectNama",
    "pihakProjekWorkorderGudangID",
    "pihakProjekWorkorderGudangNama",
    "pihakProjekWorkorderGudangName",
    "tipePenjualanID",
    "tipePenjualanNama",
    "tipePenjualanLabel",
    "pihakProjekWorkOrderSubID",
    "pihakProjekWorkOrderSubNama",
    "pihakProjekWorkorderSubGudangID",
    "pihakProjekWorkorderSubGudangName",
    "pihakProjekWorkorderSubGudangNama",
    "kompensasiTargetMethod",
    "kompensasiTargetMethod__label",
    "kompensasiTargetMethod__name",
    "kompensasiTargetMethod__coa_code",
    "transaksi_count",
    "transaksi_jenis_count",

    "reference_id_top",
    "reference_nomer_top",
    "reference_id",
    "reference_nomer",
    "reference_customers_id",
    "reference_customers_nama",
    "reference_cabang_id",
    "reference_cabang_nama",
    "reference_gudang_id",
    "reference_gudang_nama",
    "reference_gudang_status_id",
    "reference_gudang_status_nama",
    "reference_gudang_status_jenis",
    "reference_salesman_id",
    "reference_salesman_nama",
    "requestReferenceID",
    "requestReferenceNomer",
    "requestReferenceIDTop",
    "requestReferenceNomerTop",
    "requestReferenceJenis",
    "requestReferenceJenisTop",
    "requestReferenceJenisMaster",
    /*
     * ini kerekap di items multi diskon, sehingga diskon id jadi salah!
     * diskon _id dikeluarkan dari auto force item
     * diskon_nama dikeluarkan dari auto force item
     */

//    "diskon_id",// jenis diskon
//    "diskon_nama",// jenis diskon

    //"pph23Methode__tarif",
    "referenceID__1",
    "referenceNumber__1",
    "referenceNomer__1",
    "referenceDtime__1",
    "referenceFulldate__1",
    "referenceID__2",
    "referenceNumber__2",
    "referenceNomer__2",
    "referenceDtime__2",
    "referenceFulldate__2",
    "referenceID__3",
    "referenceNumber__3",
    "referenceNomer__3",
    "referenceDtime__3",
    "referenceFulldate__3",
    "referenceID__4",
    "referenceNumber__4",
    "referenceNomer__4",
    "referenceDtime__4",
    "referenceFulldate__4",
    "referenceID__5",
    "referenceNumber__5",
    "referenceNomer__5",
    "referenceDtime__5",
    "referenceFulldate__5",
    "nilai_uang_muka_source",
    "nilai_uang_muka__nonrelasi_source",
    "cek_pph",
    "pph23Methode__tarif",
    "pph21Methode__tarif",
    "kompensasiMethod",
    "pph__tarif",
//    "dateFaktur",
//    "eFaktur",
    "requestReferenceSoID",
    "requestReferenceSoNomer",
    "requestReferenceSoIDTop",
    "requestReferenceSoNomerTop",
    "requestReferenceSoJenis",
    "requestReferenceSoJenisTop",
    "requestReferenceSoJenisMaster",
    "ebillingDate",
    "ebilling",
    "cabangRef_id",
    "cabangRef_nama",
    "valas",
    "valas__label",
    "valas__nama",
    "gudangTujuan",
    "gudangTujuanID",
    "gudangTujuanNama",
    "gudangTujuanName",
    "gudangTujuanLabel",
    "cash_account",
    "cash_account__label",
    "cash_account__folders_nama",
    "cash_account__nama",
    "cash_account__machine_id",
    "cash_account__jenis",
    "cash_account__folders",
    "cash_account_id",
    "cash_account_nama",
    "dateFaktur",
    "eFaktur",
    "gudang_asal",
    "gudang_asal__id",
    "gudang_asal__label",
    "gudang_asal__nama",
    "gudang_asal__name",
    "gudang",
    "gudang__id",
    "gudang__label",
    "gudang__nama",
    "gudang__name",
);
$config['transaksi_fixedItem_subValues'] = array(
    "qty" => "jml",
    "name" => "nama",

);
$config['transaksi_fixedTableIn_subValues'] = array(
    "produk_nama" => "nama",
    "produk_ord_jml" => "jml",
    "valid_qty" => "jml",
);

$config['transaksi_fixedTableIn_values'] = array(
    "cabang_id" => "placeID",
    "cabang_nama" => "placeName",
    "gudang_id" => "gudangID",
    "gudang_nama" => "gudangName",
    "oleh_id" => "olehID",
    "oleh_nama" => "olehName",

);

$config['sessionToGateAlwaysUpdaters'] = array(//==key -> src
    "longitude" => "longitude",
    "lattitude" => "lattitude",
    "accuracy" => "accuracy",
);

$config['heTransaksi_paramPatchers'] = array(
    "RekeningPembantuSupplier" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerValasItem" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSupplierItem" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerItem" => array("transaksi_id" => "insertID"),
    "RekeningPembatuKasItem" => array("transaksi_id" => "insertID"),
    "Rekening" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomerValas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiaya" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiayaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEfisiensiBiayaSubMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuEkspedisi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuKas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuProduk" => array("transaksi_id" => "insertID"),
    "RekeningPembantuProdukRiil" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSupplies" => array("transaksi_id" => "insertID"),
    "RekeningPembantuSuppliesProses" => array("transaksi_id" => "insertID"),
    "RekeningPembantuValas" => array("transaksi_id" => "insertID"),
    "RekeningPembantuCustomer" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaTetap" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaTetapTakBerwujud" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAntarcabang" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaJasa" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiaya" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUmum" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUsaha" => array("transaksi_id" => "insertID"),
    "ComRekeningPembantuBiayaProjectMain" => array("transaksi_id" => "insertID"),
    "ComRekeningPembantuBiayaProject" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaKomposisiProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaHarusDibayar" => array("transaksi_id" => "insertID"),
    "RekeningPembantuDepresiasi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaImport" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRelasiRekeningKoran" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRekeningKoran" => array("transaksi_id" => "insertID"),
    "RekeningPembantuRekeningKoranMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBank" => array("transaksi_id" => "insertID"),
    "RekeningPembantuUangMukaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuUangMukaExternMain" => array("transaksi_id" => "insertID"),

    "RekeningPembantuAkumPenyusutanBangunan" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanKendaraan" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanMesinProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array("transaksi_id" => "insertID"),
    "RekeningPembantusewa" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaBerwujud" => array("transaksi_id" => "insertID"),
    "RekeningPembantuAktivaBerwujudMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangPihak3Item" => array("transaksi_id" => "insertID"),
    "RekeningPembantuPph" => array("transaksi_id" => "insertID"),
    "RekeningPembantuBiayaUsahaMain" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangSaham" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHutangPihakLain" => array("transaksi_id" => "insertID"),
    "ManufacturIdentity" => array("transaksi_id" => "insertID"),

    //
    "FifoSupplies" => array("transaksi_id" => "insertID"),
    "FifoProdukJadi" => array("transaksi_id" => "insertID"),
    "FifoProdukJadiRakitan" => array("transaksi_id" => "insertID"),
    "FifoValas" => array("transaksi_id" => "insertID"),
    "Jurnal" => array("transaksi_id" => "insertID"),
    "JurnalItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "JurnalValuesItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "Jurnal_activity" => array("transaksi_id" => "insertID"),
    "Jurnal_activityMain" => array("transaksi_id" => "insertID"),
    "Jurnal_activityItem" => array("transaksi_id" => "insertID"),
    #tambahan untuk modul dipasang per modul
    "JurnalModulSales" => array("transaksi_id" => "insertID"),
    "JurnalModulPurcashing" => array("transaksi_id" => "insertID"),
    "JurnalModulDistribution" => array("transaksi_id" => "insertID"),
    "JurnalModulManufactur" => array("transaksi_id" => "insertID"),
    "JurnalModulBanking" => array("transaksi_id" => "insertID"),
    "JurnalModulCash" => array("transaksi_id" => "insertID"),
    "JurnalModulPettycash" => array("transaksi_id" => "insertID"),
    "JurnalModulTax" => array("transaksi_id" => "insertID"),
    "JurnalModulConvert" => array("transaksi_id" => "insertID"),
    "JurnalModulAdjustment" => array("transaksi_id" => "insertID"),
    "JurnalModulAsetmanagement" => array("transaksi_id" => "insertID"),
    #end tambahan modul

    "LockerStockMutasi" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiSupplies" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiSuppliesProses" => array("transaksi_id" => "insertID"),
    "LockerStockMutasiAktiva" => array("transaksi_id" => "insertID"),
    "LockerStockPlafonBankMutasiMain" => array("transaksi_id" => "insertID"),
    "LockerTransaksi" => array("transaksi_id" => "insertID"),
    "PriceSupplies" => array("transaksi_id" => "insertID"),
    "PriceProduk" => array("transaksi_id" => "insertID"),
    "PaymentSourceAntarCabang" => array("transaksi_id" => "insertID"),
    "ProjectSales" => array("transaksi_id" => "insertID"),
    "ProjectSalesMain" => array("transaksi_id" => "insertID"),
    "LockerStockMain" => array("transaksi_id" => "insertID"),
    "TransaksiDataGaransi" => array("transaksi_id" => "insertID"),
    "RekeningPembantuHppProject" => array("transaksi_id" => "insertID"),
//    "RekeningPembantuPenjualanProject" => array("transaksi_id" => "insertID"),
    //-----------
    "RekeningTransaksi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiPembantu" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningTransaksiDebet" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "RekeningPembantuPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuLRLainlain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "RekeningPembantuSupplierJenis" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuSupplierSubJenis" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    //-----------
    "PaymentUangMukaCustomer" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentUangMukaSupplier" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentUangMukaSupplierValas" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentAntisourceCustomer" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuEfisiensiBiayaFaseMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuEfisiensiBiayaFase" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningModulSales" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuProdukModulSales" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukSerialNumber" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuReseller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuHpp" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCustomerDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCustomerProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiKreditLimit" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentSrcMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuPiutangSupplier" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPiutangSupplierDetailTransProdukItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNote" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetail" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetailMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCreditNoteDetailItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "LockerDiskonValue" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuProdukPerSerial" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuProdukPerSerialIntransit" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "ProdukSerialNumber" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuRawMainEfisiensi" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuVoucher" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentSourceFakturItems" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCustomerProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuPenjualanProject" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuLogamMulia" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuLogamMuliaItem" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuUangMukaMainReference" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuUangMukaMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuBiayaUsahaSubMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuBiayaUmumSubMain" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "TransaksiPembatalan" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "LockerPreDiskonValue" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "KoreksiRekeningPembantuProdukSaldo" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "KoreksiRekeningPembantuProdukRiilSaldo" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "LockerTransaksiMain" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuSubSupplier" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuSubSupplierItem" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "PaymentSourceBuilder" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "RekeningPembantuBiayaUsahaSubItem" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuBiayaUsahaSubDetail" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuBiayaUmumSubDetail" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuKomisi" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuKomisiItem" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuBiayaProjectSubItem" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),
    "RekeningPembantuCustomerDetailItem" => array("produk_id" => "insertID", "transaksi_id" => "insertID", "transaksi_no" => "insertNum"),

    "LockerStockLogamMulia" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "LockerStockLogamMuliaMain" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "TransaksiProject" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "TransaksiProjectItem" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "LockerValueDetail" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "RekeningPembantuCreditCard" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "TransaksiStatus" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
    "TransaksiStatusItem" => array("transaksi_no" => "insertNum", "transaksi_id" => "insertID"),
);

$config['heTransaksi_paramForceFillers'] = array(
    "RekeningPembantuSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerValasItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSupplierItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuKasItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Rekening" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomerValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiaya" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaSubMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuEkspedisi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuKas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuProduk" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuProdukRiil" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSuppliesProses" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaTetap" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaTetapTakBerwujud" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanAktivaTetap" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAntarcabang" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaJasa" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiaya" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUmum" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUsaha" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaKomposisiProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuDepresiasi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaHarusDibayar" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    "RekeningPembantuAkumPenyusutanBangunan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanKendaraan" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanMesinProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanPeralatanKantor" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAkumPenyusutanPeralatanProduksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuSewa" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaBerwujud" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuAktivaBerwujudMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangPihak3Item" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuPph" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBiayaUsahaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "ComRekeningPembantuBiayaProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "ComRekeningPembantuBiayaProjectMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuUangMukaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuUangMukaExternMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    //
    "Jurnal" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Neraca" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RugiLaba" => array("transaksi_id" => "insertID", "transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "JurnalValuesItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaImport" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activity" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activityMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "Jurnal_activityItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    #tambahan untuk modul dipasang per modul
    "JurnalModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulPurcashing" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulDistribution" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulManufactur" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulBanking" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulCash" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulPettycash" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulTax" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulConvert" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulAdjustment" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "JurnalModulAsetmanagement" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    #end tambahan modul

    "RekeningPembantuRelasiRekeningKoran" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuRekeningKoran" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuRekeningKoranMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuBank" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangSaham" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "RekeningPembantuHutangPihakLain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),

    "PaymentSourceAntarCabang" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasi" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiSuppliesProses" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockMutasiAktiva" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerStockPlafonBankMutasiMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis"),
    "LockerTransaksi" => array("transaksi_no" => "insertNum"),
    "PriceSupplies" => array("transaksi_no" => "insertNum"),
    "PriceProduk" => array("transaksi_no" => "insertNum"),
    "FifoSupplies" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoProdukJadi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoProdukJadiRakitan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "FifoValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProjectSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProjectSalesMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "LockerStockMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiDataGaransi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuHppProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningTransaksi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "RekeningPembantuPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuLRLainlain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "RekeningPembantuSupplierJenis" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuSupplierSubJenis" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    //------
    "PaymentUangMukaCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentUangMukaSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentUangMukaSupplierValas" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentAntisourceCustomer" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuEfisiensiBiayaFaseMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuEfisiensiBiayaFase" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuProdukModulSales" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ManufacturIdentity" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProdukProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProdukSerialNumber" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuReseller" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanKonsumen" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanSeller" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuHpp" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCustomerDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiKreditLimit" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentSrcMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "RekeningPembantuPiutangSupplier" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPiutangSupplierDetailTransProdukItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNote" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetail" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetailMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditNoteDetailItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "LockerDiskonValue" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuProdukPerSerial" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuProdukPerSerialIntransit" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "ProdukSerialNumber" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuRawMainEfisiensi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuVoucher" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "PaymentSourceFakturItems" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCustomerProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuPenjualanProject" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuLogamMulia" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuLogamMuliaItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuUangMukaMainReference" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuUangMukaMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaUsahaSubMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaUmumSubMain" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "TransaksiPembatalan" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "KoreksiRekeningPembantuProdukSaldo" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "KoreksiRekeningPembantuProdukRiilSaldo" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuKomisi" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),
    "RekeningPembantuKomisiItem" => array("transaksi_no" => "insertNum", "jenis" => "jenis", "transaksi_jenis" => "jenis"),

    "LockerTransaksiMain" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuSubSupplier" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuSubSupplierItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "PaymentSourceBuilder" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),

    "RekeningPembantuBiayaUsahaSubItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaUsahaSubDetail" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaUmumSubDetail" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuBiayaProjectSubItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "LockerPreDiskonValue" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCustomerDetailItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "LockerStockLogamMulia" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "LockerStockLogamMuliaMain" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "TransaksiProject" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "TransaksiProjectItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "LockerValueDetail" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "RekeningPembantuCreditCard" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "TransaksiStatus" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
    "TransaksiStatusItem" => array("transaksi_no" => "insertNum", "transaksi_jenis" => "jenis"),
);

$config['heTransaksi_paramForceFillers_jenisTR'] = array(
    "FifoSupplies" => array(
        "461" => array(
            "purchase_id" => "insertID",
            "purchase_nomer" => "insertNum",
        ),

    ),
    "LockerDiskonValue" => array(
        "3344" => array(
            "nomer" => "insertNum",
            "transaksi_id" => "insertID",
            "transaksi_no" => "insertNum",
            "refID" => "insertID",
        ),
        "4643" => array(
            "nomer" => "insertNum",
            "transaksi_id" => "insertID",
            "transaksi_no" => "insertNum",
            "refID" => "insertID",
        ),
    ),
);

// ======================= ======================= =======================
$config['heTransaksi_regulerRoutes'] = array(
    // purchasing fg
    "466" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing supplies
    "461" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing service to branch
    "463" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan service",
        ),
        "3" => array(
            "debet" => "penerimaan service",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),
    // purchasing service for center
    "1463" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan service",
        ),
        "3" => array(
            "debet" => "penerimaan service",
            "kredit" => "realisasi ppn masukan",
        ),
        "4" => array(
            "debet" => "realisasi ppn masukan",
            "kredit" => "pembayaran",
        ),
        "5" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),


    // distribusi fg ke cabang
    "583" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "585" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi supplies ke cabang
    "3583" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "3585" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi bom ke pusat
    "3683" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "3685" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // distribusi aktiva tetap ke cabang
    "2483" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "penerimaan barang",
        ),
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),
    "2485" => array(
        "3" => array(
            "debet" => "penerimaan barang",
            "kredit" => "request",
        ),
    ),

    // sales, penjualan
    "582" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "pre packinglist",
        ),
        "3" => array(
            "debet" => "pre packinglist",
            "kredit" => "packinglist",
        ),
        "4" => array(
            "debet" => "packinglist",
            "kredit" => "invoice",
        ),
        "5" => array(
            "debet" => "invoice",
            "kredit" => "pembayaran",
        ),
        "6" => array(
            "debet" => "pembayaran",
            "kredit" => "request",
        ),
    ),

    // penyetoran kas
    "759" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "penerimaan setoran",
        ),
        "2" => array(
            "debet" => "penerimaan setoran",
            "kredit" => "request",
        ),
    ),
    "758" => array(
        "2" => array(
            "debet" => "penerimaan setoran",
            "kredit" => "request",
        ),
    ),

    // produksi
    "776" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),
    // pemindahan rekening pembantu kas branch
    "757" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),
    // pemindahan rekening pembantu kas center
    "1757" => array(
        "1" => array(
            "debet" => "request",
            "kredit" => "otorisasi",
        ),
        "2" => array(
            "debet" => "otorisasi",
            "kredit" => "request",
        ),
    ),

);

$config['heTransaksi_headerStatus_fields'] = array(
    "jenis_label" => "activity",
    "dtime" => "date",
    "status_next" => "status",
    "cabang_nama" => "cabang pengirim",
    "cabang2_nama" => "cabang penerima",
    "customers_nama" => "customer",
    "suppliers_nama" => "vendor",
    "nomer_top" => "PO number",
    "nomer" => "receipt number",
    "oleh_nama" => "person",
    "harga" => "amount",
    "disc" => "discount",
    "ppn" => "ppn",
    "nett" => "total amount",
    //            "trash_4" => "trash 4",
    //            "id" => "ID",
);

$config['heTransaksi_source_internal_connect'] = array(
    "1582",
);

$config['heTransaksi_center_connect'] = array(
    "583",
    "3583",
    "3683",
    "2483",
    "759",
);

// ======================= ======================= =======================
$config['heTransaksi_pembatalanValidate'] = array(
    // validasi ini berjalan secara query adalah:
    // hasil dari query setelah difilter mendapatkan jumlah row > 0, maka STOP.
    // berdasarkan jenis master transaksi
    // purchasing service to branch
    "463" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya",
                "terbayar>.10",
                "jenis=.463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    // purchasing service to center
    "1463" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya",
                "terbayar>.10",
                "jenis=.1463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    // purchasing service project
    "3463" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.10",
                "jenis=.3463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    // purchasing finish goods
    "466" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.10",
                "jenis=.467",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "returned=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan return purchasing.",
            "detailCekQty" => true,
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "trash_4=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembatalan transaksi.",
        ),
    ),
    // purchasing finish goods
    "1466" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.0",
                "jenis=.1467",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "returned=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan return purchasing.",
            "detailCekQty" => true,
        ),
        array(
            "mdlName" => "MdlTransaksiData",
            "mdlFilter" => array(
                "id=transaksi_id",
                "trash_4=.1",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembatalan transaksi.",
        ),
    ),
    // purchasing supplies
    "461" => array(
//        array(
//
//            "mdlName" => "MdlJurnalActivityCache",
//            "mdlFilter" => array(
//                "master_id=id_master",
//                "jenis_master=.461",
//                "activity=.pembayaran",
//                "kredit=.0",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
//        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.10",
                "jenis=.461",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),

    "2677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "terbayar>.10",
                "jenis=.2677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "returned>.10",
                "jenis=.2677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    "2676" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya produksi",
                "terbayar>.10",
                "jenis=.2676",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),
    "2675" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya umum",
                "terbayar>.10",
                "jenis=.2675",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    "1677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "terbayar>.10",
                "jenis=.1677r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "returned>.0",
                "jenis=.1677r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),
    "1677r" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "terbayar>.10",
                "jenis=.1677r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya usaha",
                "returned>.0",
                "jenis=.1677r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
    ),

    "1676" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya produksi",
                "terbayar>.1",
                "jenis=.1676r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),
    "1676r" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya produksi",
                "terbayar>.0",
                "jenis=.1676r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    "1675" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya umum",
                "terbayar>.0",
                "jenis=.1675r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),
    "1675r" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang biaya umum",
                "terbayar>.0",
                "jenis=.1675r",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    // penjualan/packinglist
    "5822" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.piutang dagang",
                "terbayar>.0",
                "jenis=.5822spd",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dibayar/sudah lunas dari menu Penerimaan AR. Silahkan untuk membatalkan Penerimaan AR dahulu.",
        ),
    ),

    // penerimaan piutang di cabang
//    "749" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang setoran",
//                "terbayar>.0",
//                "jenis=.749",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan penyetoran ke pusat. Silahkan untuk membatalkan penyetorannya dahulu.",
//        ),
//    ),

    // penerimaan finish goods dari vendor
//    "466" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang dagang",
//                "terbayar>.0",
//                "jenis=.467",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
//        ),
//    ),

//    // penerimaan supplies dari vendor
//    "461" => array(
//        array(
//            "mdlName" => "MdlPaymentSource",
//            "mdlFilter" => array(
//                "transaksi_id=transaksi_id",
//                "label=.hutang dagang",
//                "terbayar>.0",
//                "jenis=.461",
//            ),
//            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
//        ),
//    ),

    // hutang aktiva tetap
    "421" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang aktiva tetap",
                "terbayar>.0",
                "jenis=.423",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),
    // hutang bank
    "444" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang bank",
                "terbayar>.0",
                "jenis=.444",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran dahulu.",
        ),
    ),

    "3463" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang dagang",
                "terbayar>.0",
                "jenis=.3463",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlTransaksiDataItems3_sum",
            "mdlFilter" => array(
                "transaksi_id=produkProjek__transaksi_id_app",
                "produk_id=transaksi_id",
                "valid_qty=.0",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan Packinglist di cabang.",
        ),
    ),
    // pembatalan  project
    "588" => array(),
    // titipan ke supplier dengan relasi PO
    "4643" => array(
        // berelasi po
        array(
            "mdlName" => "MdlPaymentUangMuka",
            "mdlFilter" => array(
                "extern_id=suppliers_id",
                "extern2_id=referensi_so__id",
                "label=.uang muka",
                "extern_label2=.vendor",
                "terbayar>.0",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Titipan sudah dipakai untuk pelunasan/pembayaran. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        // tidak berelasi po

    ),
    // biaya gaji
    "1674" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang gaji",
                "terbayar>.0",
                "jenis=.1674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang Gaji sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.1674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh 21 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang bpjs",
                "terbayar>.0",
                "jenis=.1674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang BPJS sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
    ),
    "21674" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang gaji",
                "terbayar>.0",
                "jenis=.21674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang Gaji sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
    ),
    "7674" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.7674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh 21 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang bpjs",
                "terbayar>.0",
                "jenis=.7674",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang BPJS sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
    ),

    "462" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph23",
                "terbayar>.0",
                "jenis=.462",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh23 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.462",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh 21 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
    ),

    "483" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph23",
                "terbayar>.0",
                "jenis=.483",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh23 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.483",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh 21 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),

    ),

    // ini hadiah berupa barang
    "6677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.piutang pph",
                "terbayar>.0",
                "jenis=.6677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena tagihan pajak hadiah sudah dibayar. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),

    ),

    "16677" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang komisi",
                "terbayar>.0",
                "jenis=.16677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayarannya dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.16677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena PPh Ps 21 sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran PPh Ps 21 dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph23",
                "terbayar>.0",
                "jenis=.16677",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena PPh Ps 23 sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran PPh Ps 23 dahulu.",
        ),
    ),

    "16678" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang komisi",
                "terbayar>.0",
                "jenis=.16678",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena komisi sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran komisi dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.16678",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena PPh Ps 21 sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran PPh Ps 21 dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph23",
                "terbayar>.0",
                "jenis=.16678",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena PPh Ps 23 sudah dilakukan pembayaran. Silahkan untuk membatalkan pembayaran PPh Ps 23 dahulu.",
        ),
    ),

    "3675" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph23",
                "terbayar>.0",
                "jenis=.3675",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh23 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.hutang pph 21",
                "terbayar>.0",
                "jenis=.3675",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena Hutang PPh 21 sudah dibayar. Silahkan untuk membatalkan Pembayaran dahulu.",
        ),
    ),

    "7499" => array(
        array(
            "mdlName" => "MdlPaymentSource",
            "mdlFilter" => array(
                "transaksi_id=transaksi_id",
                "label=.piutang dagang",
                "terbayar>.0",
                "jenis=.7499",
            ),
            "label" => "Transaksi yang dipilih tidak bisa dibatalkan karena sudah dibayar/sudah lunas dari menu Penerimaan AR. Silahkan untuk membatalkan Penerimaan AR dahulu.",
        ),
    ),
    // penerimaan uang untuk pembayaran pajak/hadiah/sejenisnya
    "7468" => array(),

    "66771" => array(),

);

$config['heTransaksi_pembatalanFifoValidate'] = array(
    "460" => array(
        "mdlNameLoc" => "Preprocs",
        "mdlName" => "PreFifoProdukJadi_reverse",
        "method" => "lookupFifoById",
        "label" => "Silahkan menggunakan transaksi FG purchases return (import).",
    ),
//    "585" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "Silahkan menggunakan transaksi Stock Return (by Product).",
//    ),
//    "1985" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "",
//    ),
//    "3685" => array(
//        "mdlNameLoc" => "Preprocs",
//        "mdlName" => "PreFifoProdukJadi_reverse",
//        "method" => "lookupFifoById",
//        "label" => "Silahkan menggunakan transaksi Stock Distribution (Rakitan ke Pabrik).",
//    ),
);

$config['heTransaksi_revertJenisException'] = array(
//    "334",
//    "1334",
//    "585",// penerimaan distribusi
//    "1985",// penerimaan return distribusi
//    "3685",// penerimaan transfer stock
);
// ======================= ======================= =======================
$config['heTransaksi_revertMainGateReplacer'] = array(
    "3685" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
    "585" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
    "1985" => array(
        "piutang cabang" => "nilai_cancel",
        "hutang ke pusat" => "nilai_cancel",
    ),
);

// ======================= ======================= =======================
$config['heTransaksi_midValidatePreProcc'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
    ),
    "preProcc" => array(
        "detail" => array(
            "FifoProdukJadi" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
            "FifoProdukJadiRakitan" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
            "FifoSupplies" => array(
                "sourceGate" => "srcGateName",
                "targetGate" => "resultParams",
            ),
        ),
    ),
);
$config['heTransaksi_validatePostProcc'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
    ),
    "postProcc" => array(
        "detail" => array(
            "FifoProdukJadi" => array(
                "model" => "FifoProdukJadi",
            ),
            "FifoProdukJadiRakitan" => array(
                "model" => "FifoProdukJadiRakitan",
            ),
            "FifoSupplies" => array(
                "model" => "FifoSupplies",
            ),
        ),
    ),
);
$config['heTransaksi_validateComponentDetail'] = array(
    "enabled" => true,
    "jenisTrException" => array(
        "1119",
        "2229",
        "1118",
        "2228",
        "2227",
        "3339",
        "5559",
        "334",
        "1334",
        "335",
        "2334",
        "2335",
        "2336",
        "2337",
        "776",
        "1463",
        "463",
        "1462",
        "462",
        "677",
        "2677",
        "110r",
        "110",
        "8786",
        "8787",
        "8788",
        "1675",
        "2676",
        "771",
        "742",
        "682",
        "5682",
        "4447",
        "743",
        "2675",
        "464",
        "773",
        "9911",
        "9912",
        "117",
        "1337",
        "1677",
        "444",
        "681",
        "682",
    ),
    "subComponent" => array(
        "detail" => array(
            "RekeningPembantuProduk",
            "RekeningPembantuSupplies",

        ),
    ),
    "dobleValidate" => array(
        "585",
        "985",
        "1985",
        "2985",
        "3585",
    ),// validasi debet vs request dan kredit vs request

);

$config['heTransaksi_rejectException'] = array(
    "code" => array(
        "110",
        "582",
    ),
);

$config['heTransaksi_pembatalanChecker'] = array(
    "467" => array(
        "serial" => array(
            "mdlNameLoc" => "Mdls",
            "mdlName" => "MdlProdukPerSerialNumber",
//            "mdlFilterIn" => "",
            "mdlFilter" => array(
                "transaksi_id=referenceID__3",
            ),
            "label" => "",
            "targetGate" => "",
            "pairedModel" => array(
                "mdlNameLoc" => "Coms",
                "mdlName" => "ComRekeningPembantuProdukPerSerial",
                "mdlFilterInSrc" => "produk_serial_number_2",
                "mdlFilterIn" => "extern_nama",
                "mdlFilter" => array(
                    "cabang_id=placeID",
                    "gudang_id=gudangID",
                    "qty_debet>.0",
                ),
            ),
        ),

    ),
    "4464" => array(
        "packinglist" => array(
            "mdlNameLoc" => NULL,
            "mdlName" => "MdlTransaksi",
//            "mdlFilterIn" => "",
            "mdlFilter" => array(
                "transaksi_id=referenceID__3",
            ),
            "label" => "",
            "targetGate" => "",
//            "pairedModel" => array(
//                "mdlNameLoc" => "Coms",
//                "mdlName" => "ComRekeningPembantuProdukPerSerial",
//                "mdlFilterInSrc" => "produk_serial_number_2",
//                "mdlFilterIn" => "extern_nama",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "gudang_id=gudangID",
//                    "qty_debet>.0",
//                ),
//            ),
        ),
        "salesorder" => array(
            "mdlNameLoc" => NULL,
            "mdlName" => "MdlTransaksi",
//            "mdlFilterIn" => "",
//            "mdlFilter" => array(
//                "transaksi_id=referenceID__3",
//            ),
//            "label" => "",
//            "targetGate" => "",
//            "pairedModel" => array(
//                "mdlNameLoc" => "Coms",
//                "mdlName" => "ComRekeningPembantuProdukPerSerial",
//                "mdlFilterInSrc" => "produk_serial_number_2",
//                "mdlFilterIn" => "extern_nama",
//                "mdlFilter" => array(
//                    "cabang_id=placeID",
//                    "gudang_id=gudangID",
//                    "qty_debet>.0",
//                ),
//            ),
        ),
    ),
    "7468" => array(
        "hadiah" => array(
            "mdlNameLoc" => NULL,
            "mdlName" => "MdlTransaksi",
//            "mdlFilter" => array(
//                "transaksi_id=referenceID__3",
//            ),
//            "label" => "",
//            "targetGate" => "",
        ),
    ),


);

// ======================= ======================= =======================
$config['heTransaksi_checkerSession'] = array(
    // region distribusi
    "583" => array(
        "main_reference" => array(
//            "gudangStatusDetails" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
            "pihakMainID" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
            "pihakMain2ID" => "Salesman wajib ditentukan.",// salesman
            "pihakID" => "Konsumen wajib ditentukan.",// konsumen
            "customerID" => "Konsumen wajib ditentukan.",// konsumen
            "requestReferenceID" => "Referensi Sales Order tidak dikenal.",// konsumen
        ),
        "main" => array(
            "cabang2ID" => "Cabang tujuan distribusi wajib ditentukan.",// gudang barang
        ),
    ),
    "983" => array(
        "main" => array(
            "cabang2ID" => "Cabang tujuan distribusi wajib ditentukan.",// gudang barang
        ),
    ),
    // endregion distribusi

    // region penjualan
    "5822" => array(
        "main" => array(
            "gudangStatusDetails" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
            "pihakMainID" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
            "pihakMain2ID" => "Salesman wajib ditentukan.",// salesman
            "pihakID" => "Konsumen wajib ditentukan.",// konsumen
            "customerID" => "Konsumen wajib ditentukan.",// konsumen
        ),
    ),
    "9822" => array(
        "main" => array(
//            "gudangStatusDetails" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
//            "pihakMainID" => "Gudang pengambilan barang wajib ditentukan.",// gudang barang
//            "pihakMain2ID" => "Salesman wajib ditentukan.",// salesman
            "pihakID" => "Konsumen wajib ditentukan.",// konsumen
            "customerID" => "Konsumen wajib ditentukan.",// konsumen
            "referenceID" => "Nomer Pengiriman/referensi return penjualan wajib ditentukan.",// konsumen
        ),
    ),
    // endregion penjualan

    // region pembelian
    "466" => array(// pembelian fg
        "main" => array(
            "pihakID" => "Supplier wajib ditentukan.",// supplier
            "tipePo" => "Tipe PO wajib ditentukan (PO Reguler atau PO Target).",//
            "ppnPersenCheck" => "Tipe Pembelian wajib ditentukan (Pembelian dengan PPN atau Pembelian Tanpa PPN).",//
            "harga" => "Jumlah Pembelian 0. Pastikan harga beli sudah diinput dengan benar.",//
        ),
    ),
    "461" => array(// pembelian supplies
        "main" => array(
            "pihakID" => "Supplier wajib ditentukan.",// supplier
//            "tipePo" => "Tipe PO wajib ditentukan (PO Reguler atau PO Target).",//
            "ppnPersenCheck" => "Tipe Pembelian wajib ditentukan (Pembelian dengan PPN atau Pembelian Tanpa PPN).",//
            "harga" => "Jumlah Pembelian 0. Pastikan harga beli sudah diinput dengan benar.",//
        ),
    ),
    "463" => array(// pembelian jasa project
        "main" => array(
            "pihakID" => "Vendor wajib ditentukan.",// supplier
            "harga" => "Jumlah Pembelian 0. Pastikan harga beli sudah diinput dengan benar.",
            "pihakPembebanan" => "Cabang Pembebanan Biaya wajib ditentukan (DC/Pusat atau Cabang) ",
        ),
    ),
    // endregion pembelian

    // region pembayaran
    // endregion pembayaran

    // region penerimaan
    // endregion penerimaan

);


?>
