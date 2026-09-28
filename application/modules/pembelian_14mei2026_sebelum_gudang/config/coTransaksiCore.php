<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(

    "466" => array(
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
            "dpp_pengganti" => "harga*(ppnFactor/12)",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
            "uang_muka_dipakai_ppn" => "nilai_dipakai_1010050030",
        ),
        "preProcessor" => array(
            "466" => array(
                /**
                 * buat nerbitin diskon dari awal, kalau dipakai oleh titipan ke supplier
                 */
                "master" => array(
                    array(
                        "comName" => "SyncDiskonPembelian",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "source" => ".items",
                            "target" => ".items4_sum",
                            "jenisTr" => "jenisTr",
                            "jenisTrMaster" => "jenisTrMaster",
                            "forceMode" => ".1",// karena PO, maka dibuatkan items4_sum untuk locker_pre_diskon
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
            ),
            "467r" => array(
                "master" => array(
                    // ekstrak items, items2 ke items3_sum
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                            "step_number" => "step_number",
                            "gate_source" => ".items",
                            // dipasang referensi so
                            // menandakan po dari penjualan dropship/dikirim dari pabrik.
//                            "request_reference_so_id" => "requestReferenceSoID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(),
            ),
            "467" => array(
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
                    //----
                    array(
//                        "comName" => "RekeningValue",
                        "comName" => "RekeningValueUangMukaPoTarget",// hanya berfungsi saat PO Target
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "harga-nilai_dipakai_ppn_in",
                            "jenis" => ".1010050030",// uang muka ppn per-supplier
                            "tipe_po" => "tipePo__kode",// tipe po reguler atau po target
                            "referensi_po_id" => "referenceID__2",// referensi id po
                            "validate" => ".1",// wajib ada saldonya, jika 0 maka dihentikan
                        ),
                        "resultParams" => array(
                            "main" => array(
                                "nilai_dipakai" => "nilai_dipakai",
                                "nilai_sisa" => "nilai_sisa",
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
//                            "nilai" => "harga-nilai_dipakai_ppn_in",
                            "nilai" => "nilai_tambah_1010050030",
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
                    // extract diskon items
                    array(
                        "comName" => "SyncDiskonPembelian",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "source" => ".items",
                            "target" => ".items4_sum",
                            "jenisTr" => "jenisTr",
                            "jenisTrMaster" => "jenisTrMaster",
                            "po_id" => "referenceID__2",//untuk kebutuhan ngecek kalau sudah di masukan saa bayar titipan relasi
                            "reference_id" => "referenceID",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    //untuk ambil stok holddari free produk
                    array(
                        "comName" => "LockerStockFreeProduk",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "produk_rel_id",
                            "extern_nama" => "produk_rel_nama",
                            "harga" => "produk_rel_harga",
                            "produk_qty" => "jml",
                            "gudang_id" => "gudangID",
                            "transaksi_id" => "masterID",//sebagai gerbang kunci locker hold
                            "transaksi_no" => "nomer",
                            "jenis" => ".freeproduk",
                            "state" => ".hold",
                            //---------
                            "produk_id" => "produk_id",
                            "produk_nama" => "produk_nama",
                        ),
                        "resultParams" => array(
                            "items5_sum" => array(
//                                "hpp" => "hpp",
//                                "harga" => "harga",
//                                "jml" => "jml",
//                                "qty" => "qty",
                                "produk_rel_harga" => "harga",


                            ),
                        ),
                        "srcGateName" => "items5_sum",
                        "srcRawGateName" => "items5_sum",
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
                "transaksi_nilai" => "nett",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

                "status_diskon" => "diskon_supplier",
                "transaksi_jenis2_label" => "tipePo__kode",

                "reference_cabang_id" => "requestReferenceCabangID",
                "reference_cabang_nama" => "requestReferenceCabangName",
                "reference_gudang_id" => "requestReferenceGudangID",
                "reference_gudang_nama" => "requestReferenceGudangName",
                "reference_jenis" => "requestReferenceSoJenis",
                "reference_id" => "requestReferenceSoID",
                "reference_nomer" => "requestReferenceSoNomer",
                "reference_jenis_top" => "requestReferenceSoJenisTop",
                "reference_id_top" => "requestReferenceSoIDTop",
                "reference_nomer_top" => "requestReferenceSoNomerTop",
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
            "466" => array(
                "detail" => array(
                    /**
                     * untuk nulis jika ada diskon 1,2,3,4,5 dst
                     *  ditulis dulu dilocker, dipindah ke postproc tgl 12 maret 2025, antisipasi reject
                     *
                     */
                    // locker stok diskon mempertimbangkan nilai tidak hanya qty
//                    array(
//                        "comName" => "LockerPreDiskonValue",
//                        "loop" => array(
//                            "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".diskon",
//                            "jenis2" => ".diskon",
//                            "jenis_locker" => ".stock",
//                            "state" => ".active",
//                            "jumlah" => ".1",
//                            "nilai" => "sub_diskon_nilai",
//                            "nilai2" => "sub_diskon_nilai",
//                            "nilai_unit" => "sub_diskon_nilai",
//                            "produk_id" => "diskon_id",//id diskon
//                            "nama" => "diskon_nama",
//
//                            "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
//                            "extern_nama" => "diskon_nama",
//                            "extern2_id" => "id",//produk yang dibeli
//                            "extern2_nama" => "nama",
//                            "satuan" => "satuan",
////                            "transaksi_id" => "transaksi_id",
//                            "transaksi_no" => "nomer",
//                            "nomer" => "nomer",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
//                            "supplier_id" => "pihakID",
//                            "supplier_nama" => "pihakName",
//                            "reference_id" => "referenceID",
//                            "reference_nomer" => "referenceNomer",
//                        ),
//                        "srcGateName" => "items4_sum",
//                        "srcRawGateName" => "items4_sum",
//                    ),
//                    // locker stok free produk
//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => ".freeproduk",
//                            "jenis2" => ".freeproduk",
//                            "state" => ".hold",
//                            "jumlah" => "qty",
//                            "produk_id" => "produk_rel_id",
//                            "nama" => "produk_rel_nama",
//                            "satuan" => "satuan",
//                            "transaksi_id" => "masterID",
//                            "transaksi_no" => "nomer",
//                            "oleh_id" => ".0",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "items5_sum",
//                        "srcRawGateName" => "items5_sum",
//                    ),
//                    // locker stok mutasi
//                    array(
//                        "comName" => "LockerStockMutasi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "produk_rel_id",
//                            "extern_nama" => "produk_rel_nama",
//                            "qty_debet" => "qty",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items5_sum",
//                        "srcRawGateName" => "items5_sum",
//                    ),
                ),
            ),
            "467" => array(
                "master" => array(

                    //region jurnal pertama
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => ".0",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
                            "1010050030" => "-uang_muka_dipakai_ppn",// (Uang Muka Dibayar Dengan Ppn) pemakaian uang muka karena po target

                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
                            "1010030030" => "harga_produk",//persediaan produk
                            "8020" => "harga_produk",//persediaan produk riil
                            "8030" => "-harga_produk",//rekening pembelian produk
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
                            "1010030020" => ".0",//persediaan supplies riil
                            "1010040050" => "nilai_tambah_ppn_in",//ppn in belum ada faktur
                            "2010010" => "nilai_tambah_piutang_pembelian",//hutang dagan
                            "1010050030" => "-uang_muka_dipakai_ppn",// pemakaian uang muka karena po target

                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
                            "1010030030" => "harga_produk",//persediaan produk
                            "8020" => "harga_produk",//persediaan produk riil
                            "8030" => "-harga_produk",//rekening pembelian produk
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
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050030" => "-uang_muka_dipakai_ppn",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// supplier id
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu uang muka dengan ppn yang terelasi dengan PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050030" => "-uang_muka_dipakai_ppn",// uang muka dibayar dengan ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",// supplier id
                            "extern_nama" => "pihakName",// supplier
                            "extern2_id" => "referenceID__2",// referensi po/relasi dengan po
                            "extern2_nama" => "referenceNumber__2",
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
                    //untuk matching persediaan riil dengan laba lain-lain
                    97 => array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "8020" => "-laba_lain_lain",// persediaan riil
                            "7010150" => "-laba_lain_lain",// laba lain-lain
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
                            "8020" => "-laba_lain_lain",// persediaan riil
                            "7010150" => "-laba_lain_lain",// laba lain-lain
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
                    95 => array(
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
                    94 => array(
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
                            "8020" => "sub_harga_produk",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "RekeningPembantuProdukRiilKoreksiNilai",//hanyauntuk kreksi saldo qty tidak diperhitungkan
                        "loop" => array(
                            "8020" => "-sub_laba_lain_lain",//persediaan produk riil dikurangi dskon
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "harga_produk",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
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
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
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
//                            "transaksi_id" => "transaksi_id",
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
        ),
        "postProcessor" => array(
            "466r" => array(
                "master" => array(

                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),
                ),
                "detail" => array(
                    // update tabel transaksi_data, produk_ord_dibeli ditambah jml dibeli.
                    // dari referensi sales order.
                    array(
                        "comName" => "TransaksiDataUpdate",
                        "loop" => array(),
                        "static" => array(
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "jenis" => "jenisTr",
                            "transaksi_id" => "requestReferenceSoID",
                            "transaksi_no" => "requestReferenceSoNomer",
                            "produk_ord_kurang" => "jml",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "466" => array(
                "master" => array(
                    array(
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "466" => "grand_total",
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
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),
                ),
                "detail" => array(
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
            "467r" => array(
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
                            "gudang_id" => "gudangID",
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
            "467" => array(
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

                    // locker transkasi
                    // array(
                    //     "comName" => "LockerTransaksi",
                    //     "loop" => array(),
                    //     "static" => array(
                    //         "cabang_id" => "placeID",
                    //         "jenis" => ".transaksi",
                    //         "state" => ".active",
                    //         "jumlah" => ".1",
                    //         "produk_id" => ".0",
                    //         "nama" => "",
                    //         "satuan" => "",
                    //         "oleh_id" => ".0",
                    //         "gudang_id" => ".0",
                    //     ),
                    //     "srcGateName" => "main",
                    //     "srcRawGateName" => "main",
                    // ),

                    array(
                        "comName" => "PaymentSourceReferenceMain",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "jenisTr" => "jenisTr",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "referenceID__2",
                            "referensi_po_id" => "referenceID__2",

                            "label" => ".uang muka supplier",
                            "terbayar" => "uang_muka_dipakai_ppn",//uang_muka_dipakai_ppn
                            "gateSource" => ".main",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "TransaksiStatus",
                        "loop" => array(
                            "467" => "grand_total",
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
                            "hpp_riil" => "hrg_tandas",
                            "jml_nilai_riil" => "sub_hrg_tandas",
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),

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
                            "gudang_id" => "gudangID",
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
//                            "gudang_id" => "gudangID",
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
//                            "gudang_id" => "gudangID",
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
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),


                    // INI KHUSUS PRODUK...
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_grn",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // last purchase, tabel produk price last purchase
                    array(
                        "comName" => "PriceProdukLastPurchase",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // last purchase, tabel price
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "harga_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // last purchase ppn, tabel price
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hpp_nppn_produk",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppn",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // harga tandas
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hrg_tandas",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // harga tandas
                    array(
                        "comName" => "PriceProduk",
                        "loop" => array(),
                        "static" => array(
                            "produk_id" => "id",
                            "produk_nama" => "name",
                            "nilai" => "hrg_tandas_npph23",
                            "cabang_id" => "placeID",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "jenis" => ".produk",
                            "jenis_value" => ".hpp_nppv_pph23",
                            "jenis_barang" => "jenis_barang",
                        ),
                        "srcGateName" => "items10_sum",
                        "srcRawGateName" => "items10_sum",
                    ),
                    // INI KHUSUS PRODUK...


//                    // INI KHUSUS SUPPLIES...
//                    array(
//                        "comName" => "PriceSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga_supplies",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp_grn",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // last purchase, tabel produk price last purchase
//                    array(
//                        "comName" => "PriceSuppliesLastPurchase",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga_supplies",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // last purchase, tabel price
//                    array(
//                        "comName" => "PriceSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "harga_supplies",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // last purchase ppn, tabel price
//                    array(
//                        "comName" => "PriceSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hpp_nppn_supplies",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp_nppn",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // harga tandas
//                    array(
//                        "comName" => "PriceSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hrg_tandas",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp_nppv",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // harga tandas
//                    array(
//                        "comName" => "PriceSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "nilai" => "hrg_tandas_npph23",
//                            "cabang_id" => "placeID",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => ".supplies",
//                            "jenis_value" => ".hpp_nppv_pph23",
//                            "jenis_barang" => "jenis_barang",
//                        ),
//                        "srcGateName" => "items9_sum",
//                        "srcRawGateName" => "items9_sum",
//                    ),
//                    // INI KHUSUS SUPPLIES...


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


    "967" => array(
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
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
//                "fifo_riil" => "hpp/1.25",
//                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
//            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
            "selisih_fifo" => "(hpp+ppn)-(nett)",
        ),
        "valueBuilders_rsltItems" => array(//            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "967sc" => array(
                "master" => array(
                    //untuk reguler terbit items3_sum
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
            "967" => array(
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
                            "gudang_id" => "gudangID",
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
            "967" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "8020" => "-hpp",//persediaan produk
                            "8030" => "hpp",//pembelian
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
                            "8020" => "-hpp",//persediaan produk
                            "8030" => "hpp",//pembelian
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
                            "1010030030" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                            "7010050" => "nett-(hpp+ppn)",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                            "validate" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            //                            "persediaan produk"                        => "-hpp",
                            "1010030030" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".1010020030010",
//                            "extern_nama" => ".Return Pembelian",
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
                    //<editor-fold desc="Post-rekening pembantu, detail">
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
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "8020" => "-sub_hpp",//persediaan produk riil
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
                    //</editor-fold>

                ),
            ),
        ),
        "postProcessor" => array(
            "967r" => array(
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "967sc" => array(
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
                            "gudang_id" => "gudangID",
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
            "967" => array(
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
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
    "1967" => array(
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
        "components" => array(
            "1967a" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010050010" => "-titipan_fullfill_nilai",// uang muka dibayar tanpa ppn
                            "1010050040" => "titipan_fullfill_nilai",// uang muka dibayar tanpa ppn non relasi
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
                            "1010050010" => "-titipan_fullfill_nilai",// uang muka dibayar tanpa ppn
                            "1010050040" => "titipan_fullfill_nilai",// uang muka dibayar tanpa ppn non relasi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    //pembantu uang muka tanpa ppn dengan relasi PO supplier
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050010" => "-titipan_fullfill_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //pembantu uang muka tanpa ppn tanpa  relasi PO supplier
                    array(
                        "comName" => "RekeningPembantuUangMukaMain",
                        "loop" => array(
                            "1010050040" => "titipan_fullfill_nilai",// uang muka dibayar tanpa ppn non relasi PO
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".0",
                            "extern2_nama" => ".0",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening pembantu uang muka tanpa ppn persupplier relasi PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050010" => "-titipan_fullfill_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "titipan_extern2_id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => "titipan_extern2_nama",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //rekening pembantu uang muka tanpa ppn persupplier tanpa relasi PO
                    array(
                        "comName" => "RekeningPembantuUangMukaMainReference",
                        "loop" => array(
                            "1010050040" => "titipan_fullfill_nilai",// uang muka dibayar tanpa ppn
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
//                            "extern2_id" => "referensi_so__id",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
//                            "extern2_nama" => "referensi_so__nomer",
                            "extern2_id" => ".0",//jika tidak punya relasi diisi 0 bebas dipakai oleh uang muka tanpa relasi/ sebaliknya jika terelasi supaya bebas dibuat transaksi un-relasi dulu
                            "extern2_nama" => ".0",
                            "extern3_id" => "option_nota",
                            "extern3_nama" => "option_nota__nama",
                            "extern4_nama" => "option_nota__jenis",
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
            "1967a" => array(
                "master" => array(
                    /*
                     * sengaja dibuat 2 karena bkare ada  pilihan dipindah /cabut
                     */

                    //dicabut titipan keluar dari_relasi po
                    array(
                        "comName" => "PaymentUangMuka",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "titipan_reference_id",
                            "jenis" => "uangMuka__jenis",
                            //"nomer"        => "referenceNomer",
                            "extern_id" => "supplierID",
                            "extern_nama" => "supplierName",
                            "extern2_id" => "titipan_extern2_id",
                            "extern2_nama" => "titipan_extern2_nama",
                            "label" => ".uang muka",
                            "terbayar" => "titipan_fullfill_nilai",
                            "extern_label2" => ".vendor",//ini update untuk pembeda vemdor/ customer
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //tititpan dipindah non relasilihat di heTranasksi_misc

                    // update tabel transaksi supaya tidak bisa dibatalkan
                    // status menjadi reject karena dicabut relasi/pindah relasi
                    array(
                        "comName" => "TransaksiRelasiUpdate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "transaksi_id" => "titipan_reference_id",
                            "referensi_id" => "titipan_reference_id",
//                            "deskripsi" => "actionType__label",
                            "deskripsi" => ".cabut relasi PO/pindah ke PO lain",
                            "trash_4" => ".1",
                            "cancel_name" => "olehName",
                            "cancel_id" => "olehID",

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

    //config pre request supplies from cabang to DC
    "1763" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "cabang2ID",
                "pihakName" => "cabang2Name",
                "gudang" => "gudang2ID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
                "gudang2Name" => "gudang2Name",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",

                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "pihakID" => "place2ID",
                "pihakName" => "place2Name",
                "pihakName2" => "place2Name",
                "cabang2ID" => "place2ID",
                "cabang2Name" => "place2ID",
                "place2ID" => "place2ID",
                "place2Name" => "place2ID",

                "gudang" => "gudangID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
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
        "components" => array(),
        "postProcessor" => array(),
    ),
    "11763" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|cabang2ID",
            "stepCode|placeID|cabang2ID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "pihakID" => "cabang2ID",
                "pihakName" => "cabang2Name",
                "gudang" => "gudang2ID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
                "gudang2Name" => "gudang2Name",

            ),
            "detail" => array(//===sumber nilai berupa rincian

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                //                "dtime" => "dtime",
                //                "id" => "id",
                //                "code" => "code",
                //                "label" => "label",
                //                "name" => "nama",
                //                "qty" => "jml",
                //                "satuan" => "satuan",
                //                "note" => "note",
                //
                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                //                "berat_gross" => "berat_gross",
                //                "lebar_gross" => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross" => "tinggi_gross",
                //                "volume_gross" => "volume_gross",
                //
                //                "hpp" => "hpp",
                //                "harga" => "harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_harga" => "sub_harga",
                //
                //                "pihakID" => "pihakID",
                //                "pihakName" => "pihakName",
                //                "cabangID" => "placeID",
                //                "cabangName" => "placeName",
                //                "olehID" => "olehID",
                //                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(),
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

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "hpp",

                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "pihakID" => "place2ID",
                "pihakName" => "place2Name",
                "pihakName2" => "place2Name",
                "cabang2ID" => "place2ID",
                "cabang2Name" => "place2ID",
                "place2ID" => "place2ID",
                "place2Name" => "place2ID",

                "gudang" => "gudangID",
                "gudang__label" => "gudang2Name",
                "gudang__name" => "gudang2Name",
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
        "components" => array(),
        "postProcessor" => array(),
    ),

    //supplies
    "461" => array(
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
            "461" => array(
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
            "461" => array(
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
//            "112" => array(
//                "master" => array(
//                    //region seleish ppn 10 vs 11 %
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
////                            "1010040050" => "-selisih_ppn_realisasi",//ppn in
////                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
//                            "1010040050" => "selisih_ppn_realisasi*-1",
//                            "2010010" => "selisih_ppn_realisasi*-1",
//
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
//                            "1010040050" => "selisih_ppn_realisasi*-1",
//                            "2010010" => "selisih_ppn_realisasi*-1",
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
//                            "1010040050" => "selisih_ppn_realisasi*-1",//ppn in
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
//                    /*
//                     * dimatikan karena detail hutang dagang lokal/import belum digenerate
//                     * tujuan untuk memisah kategori hutang dagang
//                     * 22 desember 2022*/
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
//                            "1010040050" => "-ppn_realisasi",//ppn in
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
//                            "1010040050" => "-ppn_realisasi",//ppn in
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
//                            "1010040050" => "-ppn_realisasi",//ppn in
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
            "461ro" => array(
                "master" => array(),
                "detail" => array(),
            ),
            "461r" => array(
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
            "461" => array(
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
            "461r" => array(
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
    //  config return pembelian supplies
    "961" => array(
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
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "detail_rsltItems" => array(//===sumber nilai berupa rincian

            ),
        ),
        "valueBuilders" => array(
            //            "bruto" => "sub_harga",
            //            "ppn" => "sub_ppn",
            //            "nett" => "sub_nett",
        ),
        "valueBuilders_rsltItems" => array(
            //            "bruto" => "sub_harga",
            //            "ppn"   => "sub_ppn",
            "hpp" => "sub_hpp",
            //            "nett"  => "sub_nett",
        ),
        "preProcessor" => array(
            "961" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "FifoAverageSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "gudang_id" => "gudangID",
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
                            ),
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoSupplies",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
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
                "produk_jenis" => "supplies",
            ),
            "detail_rsltItems" => array(
                "trash" => 0,
                "produk_jenis" => "supplies",
            ),
        ),

        "components" => array(
            "961" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030010" => "-hpp",//persediaan supplies
                            "1010030020" => "hpp_riil",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
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
                            "1010030010" => "-hpp",//persediaan supplies
                            "1010030020" => "hpp_riil",//persediaan supplies riil
                            "2010090010" => ".0",//hutang lain ppv
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

                    //<editor-fold desc="Com-jurnal dan rekening">
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030020" => "-hpp_riil",//persediaan supplies riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",
                            "validate" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010030020" => "-hpp_riil",//persediaan supplies riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in
                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            //                            "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>

                    //<editor-fold desc="Com-rekening pembantu">
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
//                            "hutang dagang" => "-nett",
                            "1010020030" => "nett",//piutang pembelian
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
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "1010040050" => "-ppn",//ppn in
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
                    //</editor-fold>
                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuSupplies",
                        "loop" => array(
                            "1010030010" => "-sub_hpp",//persediaan supplies
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "produk_qty" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "961r" => array(
                "master" => array(),
                "detail" => array(
                    //<editor-fold desc="Post-Item return update">
                    array(
                        "comName" => "TransaksiItemReturnUpdate",
                        "loop" => array(),
                        "static" => array(
                            "produk_jenis" => ".supplies",
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
                    //</editor-fold>
                    //<editor-fold desc="Post-locker stock supplies">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".active",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => ".0",
                            "nomer" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "oleh_id" => ".0",
                            "oleh_nama" => ".0",
                            "transaksi_id" => "transaksi_id",
                            "nomer" => "nomer",
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
                ),
            ),
            "961" => array(
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
                    //<editor-fold desc="Post-locker stock">
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".hold",
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            //                            "transaksi_id" => "transaksi_id",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "LockerStockSupplies",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".supplies",
                            "state" => ".deactivated",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "oleh_nama" => "",
                            "gudang_id" => "gudangID",
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
                            "qty_debet" => "-qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //</editor-fold>
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
    //  config cancel purchasing SP (make fullfill)
    "1961" => array(
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
        "postProcessor" => array(
            "961r" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(//                            "activity" => ".1",
                        ),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),
    "9763" => array(
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
        "postProcessor" => array(
            "9763" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal_activity",
                        "loop" => array(//                            "activity" => ".1",
                        ),
                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "cabang2_id" => "placeID",
//                            "cabang2_nama" => "placeName",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "jenis_master" => "jenisTrMaster",
//                            "jenis_top" => "jenisTrTop",
//                            "master_id" => "transaksi_id",
//                            "step_number" => ".1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
    ),


    // config po jasa projek
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
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "disc" => "(discPersen*harga)/100",
                "harga_disc" => "harga-disc",
                "dppPPn" => "harga_disc*(dpp_persen/100)",
                "dppPPh" => "harga_disc*pph",
                "ppn_persen" => ".10",
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
            "3463" => array(
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
            "3463" => array(
                "master" => array(
                    //region PO PUSAT
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
//                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
                            "2010010" => "harga_disc",//hutang dagang
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                            "1010030060" => "harga_disc",//projek cost
//                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
                            "2010010" => "harga_disc",//hutang dagang
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
//                            "1010020030" => "-nilai_dipakai_piutang_pembelian",//piutang pembelian
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
                    array(
                        "comName" => "RekeningPembantuSupplier",
                        "loop" => array(
                            "2010010" => "harga_disc",//hutang dagang
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
//                    array(
//                        "comName" => "RekeningPembantuSupplier",
//                        "loop" => array(
//                            "1010040070" => "nilai_tambah_ppn_in",//ppn in jasa
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

                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
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

                    // region pembantu hutang per-supplier, per-grn
                    array(
                        "comName" => "RekeningPembantuSubSupplier",
                        "loop" => array(
                            "2010010" => "harga_disc",//hutang dagang
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

                    //endregion

                    //region PUSAT, PINDAH PROJEK COST
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                            "1010060010" => "harga_disc",//piutang cabang
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
                            "1010030060" => "-harga_disc",//projek cost
                            "1010060010" => "harga_disc",//piutang cabang
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
                            "1010060010" => "harga_disc",//piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "place2ID",
                            "extern_nama" => "place2Name",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
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
                    //endregion PUSAT, PINDAH PROJEK COST

                    //region CABANG, TERIMA PROJEK COST
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
                            "2040010" => "harga_disc",//hutang ke pusat
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
                            "1010030060" => "harga_disc",//projek cost
                            "2040010" => "harga_disc",//hutang ke pusat
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
                            "2040010" => "harga_disc",//hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "cabang2_id" => "place2ID",
                            "cabang2_nama" => "place2Name",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "harga_disc",//projek cost
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
                    //endregion

                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
                            "5030" => "harga_disc",//hpp projek
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
                            "1010030060" => "-harga_disc",//projek cost
                            "5030" => "harga_disc",//hpp projek
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
                        "comName" => "RekeningPembantuProjek",
                        "loop" => array(
                            "1010030060" => "-harga_disc",//projek cost
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
                    array(
                        "comName" => "RekeningPembantuHpp",
                        "loop" => array(
                            "5030" => "harga_disc",//hpp projek
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "customerID",// customer projek
                            "extern_nama" => "customerName",// customer projek
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
                ),
                "detail" => array(),
            ),
//            "3113" => array(
//                "master" => array(
//                    //region seleish ppn 10 vs 11 %
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
//                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
//
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
//                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
//                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
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
//                            "2010010" => "-selisih_ppn_realisasi",//hutang dagang
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
//                            "1010040070" => "-selisih_ppn_realisasi",//ppn in jasa
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
//                    //endregion
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
//                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
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
//                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
//                            "1010040060" => "nilai_tambah_ppn_in",//ppn in realisasi
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
//                            "1010040070" => "-nilai_tambah_ppn_in",//ppn in jasa
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
                            "label" => ".hutang dagang",
//                            "target_jenis" => ".483",
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
    ),

//pembelian barang bekas
    "468" => array(
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
                "ppn" => ".0",
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
            "tagihan" => "grand_total",
//            "selisih_ppn_realisasi" => "nilai_tambah_ppn_in-ppn_realisasi",
//            "new_sisa" =>"nilai_tambah_piutang_pembelian-selisih_ppn_realisasi",
        ),
        "preProcessor" => array(
            "468" => array(
                "master" => array(
                    array(
                        "comName" => "GenerateVoucer",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".active",
//                          "jenis" => ".ppn in",
                            "jenis" => "jenisTr",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "harga",
//                            "transaksi_id" => "masterID",
                            "transaksi_id" => "currentID",
                            "oleh_id" => ".0",
                            "paymentMethod" => "paymentMethod",
                            "label" => "description",
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
            "468" => array(
                "master" => array(
                    //region jurnal pusat
                    #1
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "harga",//persediaan produk
                            "2010050" => "harga",//persediaan produk riil
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
                            "1010030030" => "harga",//persediaan produk
                            "2010050" => "harga",//persediaan produk riil
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen masuk
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "extern2_nama" => "paramVoucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke konsumen generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "extern3_nama" => "paramVoucher",//kode voucher
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cache vouceher nya mask
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #2
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010060010" => "-harga",//piutang cabang
                            "2010050" => "-harga",//hutang voucher
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
                            "1010060010" => "-harga",//piutang cabang
                            "2010050" => "-harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen dikeluarkan dari pusat masuk ke cabang
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "extern2_nama" => "paramVoucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "extern3_nama" => "paramVoucher",//kode voucher
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //cache vouceher nya keluar ke cabang
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "-harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu antar cabang
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "1010060010" => "-harga",// piutang cabang
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang2_id" => "cabangTarget",
                            "cabang2_nama" => "cabangTarget__nama",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion


                    //region cabang
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2040010" => "-harga",//hutang kepusat
                            "2010050" => "harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "2040010" => "-harga",//hutang kepusat
                            "2010050" => "harga",//hutang voucher
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu voucher/hutang ke konsumen  masuk ke cabang
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "harga",//hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => ".2010050070",//voucher
                            "extern_nama" => ".voucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => ".1125",//dimatikan selalu masuk ke generic
                            "extern_nama" => ".generic",
                            "extern2_id" => ".2010050070",// voucher
                            "extern2_nama" => ".voucher",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuVoucher",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "extern_id" => "paramVoucher",//dimatikan selalu masuk ke generic
                            "extern_nama" => "paramVoucher",
                            "extern2_id" => "description_main_followup",// voucher
                            "extern2_nama" => "description_main_followup",
                            "extern3_nama" => "description",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    #rekening pembantu antar cabang
                    array(
                        "comName" => "RekeningPembantuAntarcabang",
                        "loop" => array(
                            "2040010" => "-harga",// hutang ke pusat
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "cabang2_id" => "cabangTarget",
                            "cabang2_nama" => "cabangTarget__nama",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //endregion

                ),
                "detail" => array(
                    //rekening pembantu produk di pusat
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_harga",//persediaan produk
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
                            "supplierID" => "pihakID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
                    ),
                ),
            ),
        ),
        "postProcessor" => array(
            "468" => array(
                "master" => array(
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "harga",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "cabangTarget",
                            "cabang_nama" => "cabangTarget__nama",
                            "gudang_id" => ".0",
                            "extern_id" => ".1125",//konsumen generic sengaja karena perlu pembantu
                            "extern_nama" => ".generic",
                            "nilai" => "harga",
                            "label" => ".voucher",
                            "extern_label2" => "paramVoucher",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
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
                            "gudang_id" => "gudangID",
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
                            "qty_debet" => "qty",
                            "produk_nilai" => "harga",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),

        ),

        "closedRequest" => array(
            "468" => array(
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
    "968" => array(
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
                "hpp_nppv" => "harga*ppv_index__nilai",
                "ppv" => "hpp_nppv-harga",
                "hpp_nppn_nppv" => "hpp_nppn+ppv",
                "nett" => "harga+ppn",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
//                "fifo_riil" => "hpp/1.25",
//                "ppv" => "hpp-fifo_riil",
            ),
        ),
        "valueBuilders" => array(
//            "ppv" => "hpp-hpp_riil",
//            "selisih_fifo" => "(hpp+ppn)-(nett+ppv)",
            "selisih_fifo" => "(hpp+ppn)-(nett)",
        ),
        "valueBuilders_rsltItems" => array(//            "hpp" => "sub_hpp",

        ),
        "preProcessor" => array(
            "967sc" => array(
                "master" => array(
                    //untuk reguler terbit items3_sum
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
            "967" => array(
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
                            "gudang_id" => "gudangID",
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
            "968" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010030030" => "-hpp",//persediaan produk
                            "8020" => "hpp",//persediaan produk riil
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
                            "8020" => "hpp",//persediaan produk riil
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
                            "8020" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                            "8020" => "-hpp",//persediaan produk riil
                            "1010020030" => "nett",//piutang pembelian
                            "1010040050" => "-ppn",//ppn in belum ada faktur
//                            "7010050" => "(hpp_riil+ppn)-nett",//laba(rugi) selisih fifo return pembelian
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
                        "comName" => "RekeningPembantuPiutangSupplierMain",
                        "loop" => array(
                            "1010020030" => "nett",//piutang pembelian
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "extern_id" => ".1010020030010",
//                            "extern_nama" => ".Return Pembelian",
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
                    //<editor-fold desc="Post-rekening pembantu, detail">
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
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "8020" => "sub_hpp",//persediaan produk riil
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
                        "comName" => "RekeningPembantuProdukRiil",
                        "loop" => array(
                            "8020" => "-sub_hpp",//persediaan produk riil
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
                    //</editor-fold>

                    // rekening pembantu produk serial
                    array(
                        "comName" => "RekeningPembantuProdukPerSerial",
                        "loop" => array(
                            "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
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
        ),
        "postProcessor" => array(
            "968r" => array(
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "968" => array(
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
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
                            "gudang_id" => "gudangID",
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
);