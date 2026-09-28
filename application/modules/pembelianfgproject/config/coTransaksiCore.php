<?php


$config["coTransaksiCore"] = array(

    "1466" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|supplierID",
            "stepCode|referenceID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "supplierID" => "pihakID",
                "supplierName" => "pihakName",
                "gudangProjectID" => "gudangProject",
                "gudangProjectName" => "gudangProject__nama",
                "gudangProjectNama" => "gudangProject__nama",
                "gudangTujuanID" => "gudangTujuan",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn", // yg dipakai di grand total
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
            "1467r" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangTujuanID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "gate_source" => ".items10_sum",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "1467" => array(
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
//                            "nilai" => "ppn",//geser ke bayar-bayar tidak ada ppn disni
                            "nilai" => ".0",
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
//                            "nilai" => "tagihan-nilai_dipakai_ppn_in",//geser ke harga perolehan
                            "nilai" => "harga-nilai_dipakai_ppn_in",
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
//
//                    array(
//                        "comName" => "ProdukSerialNumberExtractor",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "jenisTr" => "jenisTrMaster",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
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
            "1467" => array(
                "master" => array(

                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "harga_supplies",//persediaan supplies riil
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030020" => "harga_supplies",//persediaan supplies riil
                            "1010030040" => "harga_produk",//persediaan produk riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
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

                    // pembantu hutang dagang (supplier)
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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

                    /*
 * dimatikan karena detail hutang dagang lokal/import belum digenerate
 * tujuan untuk memisah kategori hutang dagang
 * 22 desember 2022*/
                    // pembantu hutang dagang (lokal / import)

//                    array(
//                        "comName" => "RekeningPembantuSupplierJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // pembantu hutang dagang (lokal/import dengan supplier)

//                    array(
//                        "comName" => "RekeningPembantuSupplierSubJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion


                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil

                            "1010030010" => "harga_supplies",//persediaan supplies
                            "1010030020" => "-harga_supplies",//persediaan supplies riil

                            "2010090010" => ".0",//hutang lain ppv
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
//                            "1010030030" => "hpp_nppv",//persediaan produk
//                            "1010030040" => "-harga",//persediaan produk riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030030" => "harga_produk",//persediaan produk
                            "1010030040" => "-harga_produk",//persediaan produk riil

                            "1010030010" => "harga_supplies",//persediaan supplies
                            "1010030020" => "-harga_supplies",//persediaan supplies riil

                            "2010090010" => ".0",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    // region mencatat piutang, diskon dari supplier
                    99 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    98 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "diskon_nilai_total",// piutang supplier
                            "7010150" => "laba_lain_lain",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // endregion mencatat piutang, diskon dari supplier

                    //region jurnal diskon bonus produk lain dari vendor
                    97 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    96 => array(
                        "comName" => "Rekening",
                        "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                            "1010020030" => "produk_rel_harga",// piutang supplier
                            "7010150" => "produk_rel_harga",// laba lain-lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    // region pembantu hutang per-supplier, per-grn
                    array(
                        "comName" => "RekeningPembantuSubSupplier",
                        "loop" => array(
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // endregion pembantu hutang per-supplier, per-grn
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "1010030040" => "-sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
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
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),

                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "sub_harga_supplies",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "-sub_harga_supplies",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "sub_harga_supplies",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_supplies",
                            "gudang_id" => "gudangTujuanID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items9_sum",
                        "srcRawGateName" => "items9_sum",
                    ),


                    // rekening pembantu piutang supplier, diskon supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "diskon_id",
                            "extern_nama" => "diskon_nama",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "diskon_id",// jenis diskon
                            "extern2_nama" => "diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_diskon_nilai",// piutang supplier
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
                            "extern3_id" => "id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "nama",
                            "extern4_id" => "diskon_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "diskon_nama",// jenis diskon
                            "produk_qty" => ".1",// jenis diskon
                            "produk_nilai" => "diskon_nilai",// jenis diskon
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "nilai" => "sub_diskon_nilai",
                            "nilai2" => "sub_diskon_nilai",
                            "nilai_unit" => "sub_diskon_nilai",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",

                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "diskon_nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),

                    //region free produk

                    // rekening pembantu piutang supplier, diskon free produk
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "extern_id" => "per_supplier_diskon_id",
                            "extern_nama" => "per_supplier_diskon_nama",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id,produk,produk diskon
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern3_id" => "pihakID",// supplier
                            "extern3_nama" => "pihakName",// supplier
                            "extern2_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern2_nama" => "per_supplier_diskon_nama",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                        "loop" => array(
                            "1010020030" => "sub_produk_rel_harga",// piutang supplier
                        ),
                        "static" => array(
                            //extern_id diinject di model untuk ambil transaksi_id
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "extern_id" => "per_supplier_diskon_id",// jenis diskon
                            "extern_nama" => "per_supplier_diskon_nama",// jenis diskon
                            "extern2_id" => "pihakID",// supplier
                            "extern2_nama" => "pihakName",// supplier
                            "extern3_id" => "produk_id",// produk yang dapet diskon (ac)
                            "extern3_nama" => "produk_nama",// jenis diskon
                            "extern4_id" => "produk_rel_id",// hadiahnya produknya(kabel,selang)
                            "extern4_nama" => "produk_rel_nama",// jenis diskon
                            "produk_qty" => "qty",// jenis diskon
                            "produk_nilai" => "produk_rel_harga",// jenis diskon
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                    //endregion


                    //locker diskon free produk
                    array(
                        "comName" => "LockerDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_produk_rel_harga",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "nilai" => "sub_produk_rel_harga",
                            "nilai2" => "produk_rel_harga",
                            "nilai_unit" => "produk_rel_harga",
                            "produk_id" => "per_supplier_diskon_id",//id diskon
                            "nama" => "per_supplier_diskon_nama",

                            "extern_id" => "produk_rel_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "produk_rel_nama",
                            "extern2_id" => "produk_id",//produk yang dibeli
                            "extern2_nama" => "produk_nama",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",

                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),

                ),
            ),
//            "1111" => array(
//                "master" => array(
//                    //region seleish ppn 10 vs 11 %
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040050" => "selisih_ppn_realisasi*-1",//ppn in belum ada faktur
//                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
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
//                            "1010040050" => "selisih_ppn_realisasi*-1",//ppn in belum ada faktur
//                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
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
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "selisih_ppn_realisasi*-1",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // pembantu hutang dagang (lokal / import)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierJenis",
////                        "loop" => array(
////                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // pembantu hutang dagang (lokal/import dengan supplier)
//
////                    array(
////                        "comName" => "RekeningPembantuSupplierSubJenis",
////                        "loop" => array(
////                            "2010010" => "selisih_ppn_realisasi*-1",//hutang dagang
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".2010010010",
////                            "extern_nama" => ".lokal",
////                            "extern2_id" => "pihakID",
////                            "extern2_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    //endregion
//
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040050" => "-ppn_realisasi",////ppn in belum ada faktur
//                            "1010040060" => "ppn_realisasi",//ppn in realisasi
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
//                            "1010040050" => "-ppn_realisasi",//ppn in belum ada faktur
//                            "1010040060" => "ppn_realisasi",//ppn in realisasi
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
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040050" => "-ppn_realisasi",//ppn in belum ada faktur
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(
            "1466r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1466r" => "qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1466" => "-qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //dibawah ini mengurangi jumlah tabel di alokasi produk project
                    array(
                        "comName" => "ProjectPoolUpdate",
                        "loop" => array(
                            "1466" => "qty",
                        ),
                        "static" => array(
                            "project_purchase_pool_id" => "project_purchase_pool_id",
                            "produk_id"                => "id",
                            "produk_qty"               => "qty",
                            "produk_harga"             => "harga",
                            "oleh_id"                  => "olehID",
                            "oleh_nama"                => "olehName",
                            "master_id"                => "transaksi_id",
                            "master_jenis"             => "jenisTrMaster",
                        ),
                        "reversable"     => true,
                        "srcGateName"    => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
            "1466" => array(
                "master" => array(
                    array(
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "1466" => "grand_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenis" => "stepCode",
                            "jenis_master" => "jenisTrMaster",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "id_master" => "masterID",
                            "transaksi_nilai" => "harga",
                            "diskon_nilai" => ".0",
                            "ppn_nilai" => "ppn",
                            "transaksi_net" => "grand_total",
                            "transaksi_dibayar" => ".0",
                            "transaksi_reject" => ".0",
                            "transaksi_fullfillment" => ".0",
                            "transaksi_dikirim" => "grand_total",
                            "transaksi_nett" => "grand_total",
                            "transaksi_saldo" => "grand_total",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PriceProdukPerSupplier",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "suppliers_id" => "pihakID",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProdukLastPurchase",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1466" => "qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1467" => "-qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    /**
                     * untuk nulis jika ada diskon 1,2,3,4,5 dst
                     *  ditulis dulu dilocker
                     *
                     */
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    array(
                        "comName" => "LockerPreDiskonValue",
                        "loop" => array(
                            "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => ".1",
                            "nilai" => "sub_diskon_nilai",
                            "nilai2" => "sub_diskon_nilai",
                            "nilai_unit" => "sub_diskon_nilai",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",

                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "diskon_nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
//                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                            "reference_id" => "referenceID",
                            "reference_nomer" => "referenceNomer",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),
                    // locker stok free produk
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".freeproduk",
                            "jenis2" => ".freeproduk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "produk_rel_id",
                            "nama" => "produk_rel_nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "transaksi_no" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "produk_rel_id",
                            "extern_nama" => "produk_rel_nama",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
                    ),
                ),
            ),
            "1467r" => array(
                "master" => array(),
                "detail" => array(
                    // serial number produk
                    //saat pregrn mengaktifkan serial pernah kejadian jadi pengakuan serial saat grn
                    //disini hanya generate saja tanpa nulis ke rekening pembantu serial
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
                            "gudang_id" => "gudangTujuanID",
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
            "1467" => array(
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
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "1467" => "grand_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenis" => "stepCode",
                            "jenis_master" => "jenisTrMaster",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "id_master" => "masterID",
                            "transaksi_nilai" => "harga",
                            "diskon_nilai" => ".0",
                            "ppn_nilai" => "ppn",
                            "transaksi_net" => "grand_total",
                            "transaksi_dibayar" => ".0",
                            "transaksi_reject" => ".0",
                            "transaksi_fullfillment" => ".0",
                            "transaksi_dikirim" => "grand_total",
                            "transaksi_nett" => "grand_total",
                            "transaksi_saldo" => "grand_total",
                            "transaksi_id" => "referenceID__2",
                            "transaksi_no" => "referenceNomer__2",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangTujuanID",
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
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangTujuanID",
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
                            "produk_nilai" => "hpp",
                            "jenis" => "jenisTr",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangTujuanID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    //untuk pengakuan serial dipindah ke sat GRN sebelum GRN hanya generte akrena jika ada produk yang rusak tidak dapat dilakukan saat pregrn
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangTujuanID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "produk_id",
                            "produk_nama" => "produk_nama",
                            "produk_qty" => ".1",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                            "dtime" => "dtime",
                            "fulldate" => "fulldate",
                        ),
                        "srcGateName" => "items7_sum",
                        "srcRawGateName" => "items7_sum",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberExec",
                        "loop" => array(
                            "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangTujuanID",
                            "extern_id" => ".0",
                            "produk_serial_number_2" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "produk_id",
                            "produk_nama" => "produk_nama",
                            "produk_qty" => "jml",
                            "produk_nilai" => ".1",
//                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "transaksi_id" => "transaksi_id",
                            "dtime" => "dtime",
                            "fulldate" => "fulldate",
                        ),
                        "srcGateName" => "items7_sum",
                        "srcRawGateName" => "items7_sum",
                    ),

//                    // menambah persediaan supplies full
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "jenis" => ".supplies",
//                            "jml" => "qty",
//                            "produk_id" => "id",
//                            "hpp" => "harga_supplies",
//                            "jml_nilai" => "sub_harga_supplies",
//                            "hpp_riil" => "harga_supplies",
//                            "jml_nilai_riil" => "sub_harga_supplies",
//                            "ppv_riil" => "ppv",
//                            "ppv_nilai_riil" => "sub_ppv",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                            "nama" => "name",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangProjectID",
//                            "ppn_in" => "ppn",
//                            "ppn_in_nilai" => "sub_ppn",
//                            "suppliers_id" => "pihakID",
//                            "suppliers_nama" => "pihakName",
//                            "produk_jenis" => ".lokal",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // locker stok supplies reguler
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
//                            "gudang_id" => "gudangProjectID",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // locker stok supplies mutasi
//                    array(
//                        "comName" => "LockerStockMutasiSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "qty",
//                            "produk_nilai" => "hpp_supplies",
//                            "gudang_id" => "gudangProjectID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),

                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
                    array(
                        "comName" => "LockerPreDiskonValue",
                        "loop" => array(
                            "exec_locker" => "-sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".diskon",
                            "jenis2" => ".diskon",
                            "jenis_locker" => ".stock",
                            "state" => ".active",
                            "jumlah" => ".-1",
                            "nilai" => "-sub_diskon_nilai",
                            "nilai2" => "sub_diskon_nilai",
                            "nilai_unit" => "sub_diskon_nilai",
                            "produk_id" => "diskon_id",//id diskon
                            "nama" => "diskon_nama",

                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                            "extern_nama" => "diskon_nama",
                            "extern2_id" => "id",//produk yang dibeli
                            "extern2_nama" => "nama",
                            "satuan" => "satuan",
//                            "nomer" => "nomer",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangTujuanID",
                            "supplier_id" => "pihakID",
                            "supplier_nama" => "pihakName",
                            "reference_id" => "referenceID",
                            "reference_nomer" => "referenceNomer",
                            "transaksi_id" => "referenceID__2",
                            "transaksi_no" => "referenceNomer__2",
                        ),
                        "srcGateName" => "items4_sum",
                        "srcRawGateName" => "items4_sum",
                    ),


                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppv",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_grn",

                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1467" => "qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "1466r" => "-qty*.1",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "gudang_nama" => "gudangName",
                            "cabang_nama" => "placeName",
                            "rekening_nama" => "targetJenisLabel",
                            "produk_qty" => "-qty",
                            "produk_nilai" => ".1",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_kode" => "code",
                            "produk_part" => "no_part",
                            "produk_label" => "label",
                            "produk_jenis" => "jenis",
                            "produk_satuan" => "satuan",
                            "satuan" => "satuan",
                            "oleh_id" => "olehID",
                            "oleh_name" => "olehName",
                            //         "transaksi_id"                        => "transaksi_id",
                            "master_id" => "transaksi_id",
                            "master_jenis" => "jenisTrMaster",
                            //------
//                            "_stepCode_placeID" => "_stepCode_placeID",
//                            "_stepCode_olehID" => "_stepCode_olehID",
//                            "_stepCode_placeID_olehID" => "_stepCode_placeID_olehID",
//                            "_stepCode_placeID_olehID_customerID" => "_stepCode_placeID_olehID_customerID",
//                            "_stepCode_customerID" => "_stepCode_customerID",
//                            "_stepCode_placeID_customerID" => "_stepCode_placeID_customerID",
//                            "_stepCode_olehID_customerID" => "_stepCode_olehID_customerID",
//                            "_stepCode" => "_stepCode",
//                            "_stepCode_placeID_olehID_supplierID" => "_stepCode_placeID_olehID_supplierID",
//                            "_stepCode_supplierID" => "_stepCode_supplierID",
//                            "_stepCode_placeID_supplierID" => "_stepCode_placeID_supplierID",
//                            "_stepCode_olehID_supplierID" => "_stepCode_olehID_supplierID",
                            //------
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // update relasi produk dengan supplier
                    array(
                        "comName" => "ProdukPerSupplier",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_kode" => "barcode",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "cabang_id" => "placeID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),

        "closedRequest" => array(
            "1466" => array(
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

    "9967" => array(
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
                "gudangProjectID" => "gudangProject",
                "gudangProjectName" => "gudangProject__nama",
                "gudangProjectNama" => "gudangProject__nama",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "ppn" => "(ppnFactor*harga)/100",
                "hpp_nppn" => "harga+ppn",
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "fifo_riil" => "hpp/1.25",
                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
        ),
        "valueBuilders_rsltItems" => array(

            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "9967sc" => array(
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
            "9967" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "resultParams" => array(
                            "items" => array(
                                "hpp" => "hpp",
                                "hpp_riil" => "hpp_riil",
                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
                                "hpp_nppv" => "hpp_nppv",
                                "produk_jenis" => "produk_jenis",
                                "produk_jenis_id" => "produk_jenis_id",
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
////                            "gudang_id" => "gudangID",
//                            "gudang_id" => "gudangProjectID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
////                                "harga" => "hpp",
//                                "hpp" => "hpp",
//                                "jml" => "qty",
//                                "qty" => "qty",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                                "hpp_nppv" => "hpp_nppv",
//                                "produk_jenis" => "produk_jenis",
//                                "produk_jenis_id" => "produk_jenis_id",
//                            ),
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
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
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
            "rsltItemsValues" => array(
                "harga" => "harga",
                "hpp" => "hpp",
                "ppn" => "ppn",
                "nett" => "nett",
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
            "9967" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
//                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "1010030040" => "hpp",//persediaan produk riil
                            //                            "laba(rugi) selisih fifo return pembelian" => "selisih_fifo",
//                            "2010090010" => "-ppv_riil",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                            "7010050" => "(hpp+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            //                            "persediaan produk"                        => "-hpp",
                            "1010030040" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
                            //                            "laba(rugi) selisih fifo return pembelian" => "(hpp+ppn)-nett",
//                            "7010050" => "(hpp+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010020030" => "nett",//piutang pembelian
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            //                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
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
                    array(
                        "comName" => "RekeningPembantuPiutangSupplierDetailMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1010020030010",
                            "extern_nama" => ".Return Pembelian",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn",//ppn in bekum ada faktur
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "-sub_hpp",//persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030040" => "-sub_hpp",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            //							"produk_nilai" => "harga",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "9967r" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "TransaksiItemReturnUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".produk",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "transaksi_id" => "referenceID",
                            "seluruhnya" => "seluruhnya",
                            "returnMethod" => "pihakMainName", // by pass diisi metode per-barang atau per-nota
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
            "9967sc" => array(
                "master" => array(),
                "detail" => array(
                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangProjectID",
                            "extern_id" => ".0",
                            "extern_nama" => "produk_serial",
                            "extern2_id" => ".0",
                            "extern2_nama" => "produk_sku_part_nama",
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => ".1",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                            "kategori_id" => "kategori_id",//ini untuk skip produk jasa
                        ),
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),
                ),
            ),
            "9967" => array(
                "master" => array(
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
//                            "label" => ".hutang dagang",
                            "label" => ".piutang pembelian",
                            "sisa" => "nett",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".deactivated",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
                            "gudang_id" => "gudangProjectID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukSerialNumberLocker",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "produk_serial_number" => "produk_serial",
                            "jumlah" => ".0",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "qty_debet" => "-qty",
//                            "produk_nilai" => "hpp",
//                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items3_sum",
                        "srcRawGateName" => "items3_sum",
                    ),

                ),
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
    "19967" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|supplierID",
            "stepCode|supplierID",
            "stepCode|placeID|supplierID",
            "stepCode|olehID|supplierID",
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
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            "grand_total" => "harga+ppn",
            "tagihan" => "grand_total-discount",
        ),
        "valueBuilders_rsltItems" => array(),
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
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "referensi_id" => "referenceID",

                "pembayaran" => "paymentMethod",
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
        "components" => array(),
        "postProcessor" => array(),


    ),


    //supplies
    "1461" => array(
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
                //"ppn" => "(ppnFactor*harga)/100",
                "ppnPersen" => "ppnFactor",
                "ppn" => "(ppnPersen*harga_disc)/100",
//                "ppn" => "(ppnFactor*harga_disc)/100",
                "hpp_nppn" => "harga_disc+ppn",
                "hpp_nppv" => "harga_disc*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga_disc",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "hpp_nppn",
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
            "1461" => array(
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
//                            "nilai" => "ppn",
                            "nilai" => ".0",
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
//                            "nilai" => "tagihan-nilai_dipakai_ppn_in",
                            "nilai" => "harga_disc-nilai_dipakai_ppn_in",
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
//                "transaksi_nilai" => "harga",
                "transaksi_nilai" => "nett",
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
                "produk_jenis" => "supplies",
            ),
        ),
        "components" => array(
            "1461" => array(
                "master" => array(
                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "harga_disc",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "validate" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030020" => "harga_disc",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            //                            "hutang dagang" => "nilai_tambah_piutang_pembelian",
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
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
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

                    /*
 * dimatikan karena detail hutang dagang lokal/import belum digenerate
 * tujuan untuk memisah kategori hutang dagang
 * 22 desember 2022*/
                    // pembantu hutang dagang (lokal / import)
//                    array(
//                        "comName" => "RekeningPembantuSupplierJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // pembantu hutang dagang (lokal/import dengan supplier)

//                    array(
//                        "comName" => "RekeningPembantuSupplierSubJenis",
//                        "loop" => array(
//                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010010010",
//                            "extern_nama" => ".lokal",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //endregion

                    //region jurnal kedua pindah persediaan riil ke persediaan(std)
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
//                            "1010030010" => "hpp_nppv",//persediaan supplies
//                            "1010030020" => "-harga_disc",//persediaan supplies riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030010" => "harga_disc",//persediaan supplies
                            "1010030020" => "-harga_disc",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
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
//                            "1010030010" => "hpp_nppv",//persediaan supplies
//                            "1010030020" => "-harga_disc",//persediaan supplies riil
//                            "2010090010" => "ppv",//hutang lain ppv
                            "1010030010" => "harga_disc",//persediaan supplies
                            "1010030020" => "-harga_disc",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                    // region pembantu hutang per-supplier, per-grn
                    array(
                        "comName" => "RekeningPembantuSubSupplier",
                        "loop" => array(
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // endregion pembantu hutang per-supplier, per-grn
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "sub_harga_disc",//persediaan supplies riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSuppliesRiil",
                        "loop" => array(
                            "1010030020" => "-sub_harga_disc",//persediaan supplies riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "harga_disc",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
//                            "1010030010" => "sub_hpp_nppv",//persediaan supplies
                            "1010030010" => "sub_harga_disc",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
//                            "produk_nilai" => "hpp_nppv",
                            "produk_nilai" => "harga_disc",
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
            "1461ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "1461r" => array(
                "master" => array(
                    array(
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "461r" => "grand_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenis" => "stepCode",
                            "jenis_master" => "jenisTrMaster",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "id_master" => "masterID",
                            "transaksi_nilai" => "harga",
                            "diskon_nilai" => ".0",
                            "ppn_nilai" => "ppn",
                            "transaksi_net" => "grand_total",
                            "transaksi_dibayar" => ".0",
                            "transaksi_reject" => ".0",
                            "transaksi_fullfillment" => ".0",
                            "transaksi_dikirim" => "grand_total",
                            "transaksi_nett" => "grand_total",
                            "transaksi_saldo" => "grand_total",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
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
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "1461" => array(
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
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "461" => "grand_total",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenis" => "stepCode",
                            "jenis_master" => "jenisTrMaster",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "suppliers_id" => "pihakID",
                            "suppliers_nama" => "pihakName",
                            "id_master" => "masterID",
                            "transaksi_nilai" => "harga",
                            "diskon_nilai" => ".0",
                            "ppn_nilai" => "ppn",
                            "transaksi_net" => "grand_total",
                            "transaksi_dibayar" => ".0",
                            "transaksi_reject" => ".0",
                            "transaksi_fullfillment" => ".0",
                            "transaksi_nett" => "grand_total",
                            "transaksi_saldo" => "grand_total",
                            "transaksi_id" => "referenceID__2",
                            "transaksi_no" => "referenceNomer__2",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                            //                            "gudang_id" => "gudang2ID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "FifoAverage",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".supplies",
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "harga_disc",
                            "jml_nilai" => "sub_harga_disc",
                            "hpp_riil" => "harga_disc",
                            "jml_nilai_riil" => "sub_harga_disc",
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
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasiSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "qty_debet" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    array(
                        "comName" => "PriceSupplies",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppv",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp_nppv",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
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
                            "jenis" => ".supplies",
                            "jenis_value" => ".hpp_grn",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),

        "closedRequest" => array(
            "1461r" => array(
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