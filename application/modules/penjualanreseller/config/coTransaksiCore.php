<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiCore"] = array(

    "580" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID|customerID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                //--------------
//                "disc" => "harga-nett1",
//                "disc_percent" => "(disc/harga)*100",
                //--------------
                "qty" => "jml",
                "nett1" => "(premi+harga-disc)",
                "subtotal" => "jml*nett1",
                //-------------
                "nett1_dropshiper" => "(premi+harga_dropshiper-disc)",
                "subtotal_dropshiper" => "jml*nett1_dropshiper",


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
//                //"berat"         => "berat",
//                //"lebar"         => "lebar",
//                //"panjang"       => "panjang",
//                //"tinggi"        => "tinggi",
//                //"volume"        => "volume",
//                "berat_gross" => "berat_gross",
//                "lebar_gross" => "lebar_gross",
//                "panjang_gross" => "panjang_gross",
//                "tinggi_gross" => "tinggi_gross",
//                "volume_gross" => "(lebar_gross*panjang_gross*tinggi_gross)",
//
//                //                "ppv"           => "ppv",
//                "hpp" => "hpp",
                //                "harga"         => "harga",
                //                "ppn"           => "harga*(10/100)",
                //                "ppn_persen"    => "ppn_persen",
                //                "diskon"        => "diskon",
                //                "diskon_persen" => "diskon_persen",
                //                "nett"          => "harga-disc-ftot_discount+ppn",

                //                "sub_harga" => "sub_harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_diskon" => "sub_diskon",
                //                "sub_ppn" => "sub_ppn",
                //                "sub_nett" => "sub_nett",

//                "pihakID" => "pihakID",
//                "pihakName" => "pihakName",
//                "cabangID" => "placeID",
//                "cabangName" => "placeName",
//                "placeID" => "placeID",
//                "placeName" => "placeName",
//                "olehID" => "olehID",
//                "olehName" => "olehName",
            ),
            "master_dependent" => array(
                "paymentMethod" => array(
                    "cash" => array(
                        "nilai_cash" => "tagihan",
                        "nilai_credit" => "0",
                        "nilai_penjualan_cash" => "0",
                        "nilai_penjualan_credit" => "nett1-add_diskon",
                        "nilai_ppn_cash" => "0",
                        "nilai_ppn_credit" => "grand_ppn",
                        "nilai_piutang_dagang" => "0",
                        "nilai_cash_dropshiper" => "tagihan_dropshiper",
                        "nilai_credit_dropshiper" => "0",
                        "nilai_penjualan_cash_dropshiper" => "0",
                        "nilai_penjualan_credit_dropshiper" => "nett1_dropshiper-add_diskon",
                        "nilai_ppn_cash_dropshiper" => "0",
                        "nilai_ppn_credit_dropshiper" => "grand_ppn_dropshiper",
                        "nilai_piutang_dagang_dropshiper" => "0",
                        "defaultPaymentMethod" => ".cash",
                        "kredit_limit_order" => "0",
                    ),
                    "cia" => array(
                        "nilai_cash" => "tagihan",
                        "nilai_credit" => "0",
                        "nilai_penjualan_cash" => "0",
                        "nilai_penjualan_credit" => "nett1-add_diskon",
                        "nilai_ppn_cash" => "0",
                        "nilai_ppn_credit" => "grand_ppn",
                        "nilai_piutang_dagang" => "0",
                        "nilai_cash_dropshiper" => "tagihan_dropshiper",
                        "nilai_credit_dropshiper" => "0",
                        "nilai_penjualan_cash_dropshiper" => "0",
                        "nilai_penjualan_credit_dropshiper" => "nett1_dropshiper-add_diskon",
                        "nilai_ppn_cash_dropshiper" => "0",
                        "nilai_ppn_credit_dropshiper" => "grand_ppn_dropshiper",
                        "nilai_piutang_dagang_dropshiper" => "0",
                        "defaultPaymentMethod" => ".cash",
                        "kredit_limit_order" => "0",
                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                        "nilai_piutang_dagang" => "tagihan",
                        "nilai_penjualan_cash" => "0",
                        "nilai_penjualan_credit" => "nett1-add_diskon",
                        "nilai_ppn_credit" => "grand_ppn",
                        "nilai_ppn_cash" => "0",
                        "nilai_credit_dropshiper" => "tagihan_dropshiper",
                        "nilai_cash_dropshiper" => "0",
                        "nilai_piutang_dagang_dropshiper" => "tagihan_dropshiper",
                        "nilai_penjualan_cash_dropshiper" => "0",
                        "nilai_penjualan_credit_dropshiper" => "nett1_dropshiper-add_diskon",
                        "nilai_ppn_credit_dropshiper" => "grand_ppn_dropshiper",
                        "nilai_ppn_cash_dropshiper" => "0",
                        "defaultPaymentMethod" => ".credit",
                        "kredit_limit_order" => "tagihan_dropshiper",
                    ),
//                    "credit_card" => array(
//                        "nilai_cash" => "0",
//                        "nilai_credit" => "tagihan",
//                    ),
//                    "debit_card" => array(
//                        "nilai_cash" => "0",
//                        "nilai_credit" => "tagihan",
//                    ),
                ),
                "shippingService" => array(
                    "ongkir_ppn_by_cust" => array(
                        "ongkir_ui" => "shipping_service",
                        "ongkir" => "shipping_service",
                        "ongkir_ppn" => "shipsvc_ppn_value",
                        "ongkir_net" => "shipping_service",
                        "srcOngkir" => "0",
                    ),
                    "ongkir_tanpa_ppn_by_cust" => array(
                        "ongkir_ui" => "shipping_service",
                        "ongkir" => "0",
                        "ongkir_ppn" => "0",
                        "ongkir_net" => "0",
                        "srcOngkir" => "shipping_service",
                    ),
                    "ongkir_tanpa_ppn_by_company" => array(
                        "ongkir_ui" => "0",
                        "ongkir" => "0",
                        "ongkir_ppn" => "0",
                        "ongkir_net" => "0",
                        "srcOngkir" => "0",
                    ),
                    "tanpa_ongkir" => array(
                        "ongkir_ui" => "0",
                        "ongkir" => "0",
                        "ongkir_ppn" => "0",
                        "ongkir_net" => "0",
                    ),
                ),
            ),
        ),
        "extFormula" => array(
            // "master" => array(
            //     "ceil" => array(
            //         "nett1","jual",
            //         "dp_value",
            //     ),
            //     "floor" => array(
            //         "ppn",
            //         "ppn_out_bulat",
            //         "grand_pembulatan",
            //         "dp_ppn_value",
            //     ),
            // ),
            // "detail" => array(
            //     "ceil" => array("nett1","jual"),
            //     "floor" => array("disc","premi"),
            // ),

        ),
        "valueBuilders" => array(
            //gerbang valid_ppn dibuild di postProc karena ada sisa transaksi lama yang ppn nya sudah masuk saat jurnal pembelian update 8/12/2021
            "shipsvc_ppn_value" => "(shipping_service*ppnFactor/100)",
            "dp_value" => "(dp*100)/(100+ppnFactor)",
            "dp_ppn_value" => "dp_value*ppnFactor/100",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",

            "grand_total" => "nett1+install_tax+install+ongkir",
            "grand_ppn" => "ongkir_ppn+ppn",
            "new_net1" => "(nett1+ongkir)-add_diskon",
            "new_net2" => "nett2+ongkir",
            "new_net3" => "new_net2+ongkir_ppn",
            "tagihan" => "nett1_bulat+ppn_out_bulat-dp-nilai_cia",
            "grand_total_ui" => "(nett1+install_tax+install+ongkir)-add_diskon",
            "tagihan_ui" => "nett1+install_tax+install+ongkir+grand_ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",
            "nett1_bulat" => "new_net1",
            "total_diskon" => "disc+add_diskon",
            //-----------------------------------
            "grand_total_dropshiper" => "nett1_dropshiper+install_tax+install+ongkir",
            "grand_ppn_dropshiper" => "ongkir_ppn+ppn_dropshiper",
            "new_net1_dropshiper" => "(nett1_dropshiper+ongkir)-add_diskon",
            "new_net2_dropshiper" => "nett2_dropshiper+ongkir",
            "new_net3_dropshiper" => "new_net2_dropshiper+ongkir_ppn",
            "tagihan_dropshiper" => "nett1_bulat_dropshiper+ppn_out_bulat_dropshiper-dp-nilai_cia",
            "grand_total_ui_dropshiper" => "(nett1_dropshiper+install_tax+install+ongkir)-add_diskon",
            "tagihan_ui_dropshiper" => "nett1_dropshiper+install_tax+install+ongkir+grand_ppn_dropshiper-dp-nilai_cia",
            "grand_net_dropshiper" => "new_net3_dropshiper-nilai_dipakai_ppn_out",
            "nett1_bulat_dropshiper" => "new_net1_dropshiper",
            "total_diskon_dropshiper" => "disc+add_diskon",
            //-----------------------------------
            "ppn_ditanggung_dropshiper" => "grand_ppn_dropshiper-new_grand_ppn",
            "komisi_bruto" => "nilai_penjualan_credit_dropshiper-nilai_penjualan_credit",
            "komisi_netto" => "komisi_bruto-ppn_ditanggung_dropshiper",
        ),
        //-----------------------------------
        "injectorPajak" => array(
            "source" => "grand_total_ui",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            "grandTotal" => "grandTotal",
            "new_net3" => "grandTotal",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
        ),
        //-----------------------------------
        "injectorPajakReseller" => array(
            "source" => "grand_total_ui_dropshiper",
        ),
        "pairPajakReseller" => array(
            "ppn_dropshiper" => "ppn",
            "grand_ppn_dropshiper" => "ppn",
            "new_grand_ppn_dropshiper" => "ppn",
            "dpp_ppn_dropshiper" => "dppPpn",
            "grandTotal_dropshiper" => "grandTotal",
            "new_net3_dropshiper" => "grandTotal",
            "ppn_out_bulat_dropshiper" => "ppn",
            "grand_pembulatan_dropshiper" => "grandTotal",
        ),
        //-----------------------------------
        "externalValues" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(
            "580pkd" => array( // dijalankan bila mendapatkan target 582so, (bila mendapatkan optTarget 582spod tidak dijalankan)
                "master" => array(
                    array(
                        "comName" => "ProdukSerialNumberExtractor",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "jenisTr" => "jenisTrMaster",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
//                    array(
//                        "comName" => "LockerStock",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
                ),
            ),
            "580spd" => array(
                "master" => array(
                    //untuk membuat gerbang pembeda antara yang sudah kena ppn dipackingList
//                    array(
//                        "comName" => "ValidateNewPL",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "nilai" => "ppn",
//                            "jenis" => "jenisTrMaster",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    array(
                        "comName" => "RekeningValueDetail",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".2010050",// hutang ke konsumen
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050010",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "nilai" => "nett1_dropshiper+ppn", //ppn di geser  ke invoicing
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
//                                "ppv_riil" => "ppv_riil",
                                "ppn_in" => "ppn_in",
                                "ppn_in_nilai" => "ppn_in_nilai",
                                "suppliers_id" => "suppliers_id",
                                "suppliers_nama" => "suppliers_nama",
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
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems" => array(
//                                "id" => "produk_id",
//                                "nama" => "nama",
//                                "name" => "nama",
//                                "harga" => "hpp",
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
//                    // preprocc ini bertugas menginject nilai dari rsltItems ke gerbang items
//                    // yang sesuai antara extern_id rsltItem dengan id/key items
//                    array(
//                        "comName" => "Sync2GatesRsltItems",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "qty",
//                            "gudang_id" => "gudangID",
//
//                            "extern_id_src" => "id",
//                            "hpp" => "hpp",
//                            "hpp_riil" => "hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                        ),
//                        "resultParams" => array(
//                            "items" => array(
//                                "hpp" => "hpp",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                            ),
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
                ),
            ),
//            "582" => array(
//                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".ppn out",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "valid_ppn",// aslinya...
//                            "transaksi_id" => "masterID",
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
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".hutang ke konsumen",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            "nilai" => "nilai_credit", //ppn+nilai barang di geser  ke invoicing
//                            "transaksi_id" => "masterID",
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
//                ),
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

                "seller_id" => "sellerID",
                "seller_nama" => "sellerName",
                "top" => "top",
                "top_nama" => "top__nama",
                "tos" => "tos",
                "tos_nama" => "tos__nama",
                "referensi_id" => "referenceID",
                "referensi_nomer" => "referenceNomer",
                "referensi_jenis" => "referenceJenis",
                "pembayaran" => "paymentMethod",
                "pembayaran_sys" => "paymentMethod",
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
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "detailValues"    => array(
//                "harga" => "harga",
//                "hpp"   => "hpp",
//                "disc"  => "disc",
//                "ppn"   => "ppn",
//                "nett1" => "nett1",
//                "nett2" => "nett2",
//
//                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "code",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
//            "rsltItemsValues" => array(
//                //                "harga"  => "harga",
//                "hpp" => "hpp",
//                //                "diskon" => "diskon",
//                //                "ppn"    => "ppn",
//                //                "nett"   => "nett",
//
//                //                "ppv" => "ppv",
//
//                "berat_gross"  => "berat_gross",
//                "volume_gross" => "volume_gross",
//                //                "lebar_gross"   => "lebar_gross",
//                //                "panjang_gross" => "panjang_gross",
//                //                "tinggi_gross"  => "tinggi_gross",
//            ),
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
            "580spd" => array(
                "master" => array(

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "-nilai_dipakai_2010050_2010050010",// hutang ke konsumen
                            "1010020010" => "nilai_tambah_2010050_2010050010",// piutang dagang
                            "4010" => "nilai_penjualan_credit_dropshiper",// penjualan digeser ke penjualan lokal dari penjualan
                            "7010130" => "ongkir",// jasa kirim
                            "1010030030" => "-hpp",// persediaan produk
                            "5010" => "(hpp+ppv_riil)",// hpp
                            "7010150" => "ppv_riil",// laba lain lain
                            "2030060" => "nilai_ppn_credit_dropshiper",// ppn out
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
                            "2010050" => "-nilai_dipakai_2010050_2010050010",// hutang ke konsumen
                            "1010020010" => "nilai_tambah_2010050_2010050010",// piutang dagang
                            "4010" => "nilai_penjualan_credit_dropshiper",// penjualan
                            "7010130" => "ongkir",// jasa kirim
                            "1010030030" => "-hpp",// persediaan produk
                            "5010" => "(hpp+ppv_riil)",// hpp
                            "7010150" => "ppv_riil",// laba lain lain
                            "2030060" => "nilai_ppn_credit_dropshiper",// ppn out
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010020010" => "nilai_tambah_2010050_2010050010",//dikurangi nilai ppn// piutang dagang
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
                    // pembantu customer, uang muka
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "-nilai_dipakai_2010050_2010050010",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050010",
                            "extern_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "-nilai_dipakai_2010050_2010050010",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050010",
                            "extern2_nama" => ".Uang Muka Konsumen",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu penjualan lokal
                    array(
                        "comName" => "RekeningPembantuPenjualan",// lokal
                        "loop" => array(
                            "4010" => "nilai_penjualan_credit_dropshiper",// penjualan
//                            "4010" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4010010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_penjualan_credit",
//                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu penjualan lokal - konsumen
                    array(
                        "comName" => "RekeningPembantuPenjualanKonsumen",// lokal - konsumen
                        "loop" => array(
                            "4010" => "nilai_penjualan_credit_dropshiper",// penjualan
//                            "4010" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4010010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_penjualan_credit",
//                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu penjualan lokal - seller
                    array(
                        "comName" => "RekeningPembantuPenjualanSeller",
                        "loop" => array(
                            "4010" => "nilai_penjualan_credit_dropshiper",// penjualan
//                            "4010" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4010010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => "sellerID",
                            "extern2_nama" => "sellerName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nilai_penjualan_credit",
//                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu hpp
                    array(
                        "comName" => "RekeningPembantuHpp",
                        "loop" => array(
                            "5010" => "(hpp+ppv_riil)",// hpp
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".5010010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "(hpp+ppv_riil)",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // bagian ngurus komisi dropshiper
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "6110" => "komisi_netto",// biaya komisi
                            "2010120" => "komisi_netto",// hutang komisi
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
                            "6110" => "komisi_netto",// biaya komisi
                            "2010120" => "komisi_netto",// hutang komisi
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
                        "comName" => "RekeningPembantuReseller",
                        "loop" => array(
                            "2010120" => "komisi_netto",// hutang komisi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakDropshipID",
                            "extern_nama" => "pihakDropshiperName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    // bila ada point
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "2010050" => "point_transaksi_nilai",// hutang ke konsumen
                            "4010" => "-point_transaksi_nilai",// penjualan
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
                            "2010050" => "point_transaksi_nilai",// hutang ke konsumen
                            "4010" => "-point_transaksi_nilai",//penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu customer, point
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "point_transaksi_nilai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".2010050030",
                            "extern_nama" => ".Point",
                            "harga" => "point_set_nilai",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerDetail",
                        "loop" => array(
                            "2010050" => "point_transaksi_nilai",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => ".2010050030",
                            "extern2_nama" => ".Point",
                            "qty" => "point_transaksi",
                            "jml" => "point_transaksi",
                            "harga" => "point_set_nilai",
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
                            "1010030030" => "-sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "-jml",
                            "produk_nilai" => "hpp",
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
            "580spo" => array(
                "master" => array(
                    // mengurangi kredit limit konsumen
                    array(
                        "comName" => "TransaksiKreditLimit",
                        "loop" => array(
                            "582spo" => "-kredit_limit_order",// hanya penjualan kredit....
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "produk_qty" => ".0",
                            "produk_nilai" => "-kredit_limit_order",
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
                        "comName" => "PriceProtector",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "produk_id" => "id",
                            "nama" => "name",
                            "harga" => "harga",
                        ),
                        "reversable" => false,
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
                            "jumlah" => "-qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => "olehID",
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
                            "state" => ".hold",
                            "jumlah" => "qty",
                            "produk_id" => "id",
                            "nama" => "name",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudangID",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "580so" => array(
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
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                        "reversable" => true,
                    ),


                ),
                "detail" => array(),
            ),
            "580pkd" => array(
                "master" => array(
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".active",
//                            "jenis" => ".downpayment",
//                            "produk_id" => "masterID",
//                            "nama" => "nomer",
//                            "nilai" => "-dp_value",
//                            "transaksi_id" => "masterID",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                        "reversable" => true,
//                    ),
//                    array(
//                        "comName" => "LockerValue",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "state" => ".hold",
//                            "jenis" => ".downpayment",
//                            "produk_id" => "masterID",
//                            "nama" => "nomer",
//                            "nilai" => "dp_value",
//                            "transaksi_id" => "masterID",
//                            "oleh_id" => ".0",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                        "reversable" => true,
//                    ),
//
//
//
                ),
                "detail" => array(),
            ),
            "580spd" => array(
                "master" => array(

                    //<editor-fold desc="Post-signature">
                    array(
                        "comName" => "Signature",
                        "loop" => array(
                            "transaksi_id" => "references",
                        ),
                        "static" => array(

                            "nomer" => "nomer",
                            "step_number" => ".2",
                            "step_code" => ".581",
                            "step_name" => ".order process",
                            "group_code" => ".sys",
                            "oleh_id" => "olehID",
                            "oleh_nama" => "olehName",
                            "keterangan" => ".autostep by other transaction",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>
                    //<editor-fold desc="Post-step updater">
                    array(
                        "comName" => "TransaksiStepUpdater",
                        "loop" => array(
                            "references" => "references",
                        ),
                        "static" => array(
                            "next_step_code" => ".",
                            "next_step_label" => ".",
                            "next_group_code" => ".",
                            "next_step_num" => ".",
                            "step_current" => ".2",
                        ),
                        "static2" => array(//==untuk rincian transaksi
                            "next_substep_code" => ".",
                            "next_substep_label" => ".",
                            "next_subgroup_code" => ".",
                            "next_substep_num" => ".",
                            "sub_step_current" => ".2",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //</editor-fold>


                ),
                "detail" => array(
                    //<editor-fold desc="Post-locker stock produk">
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
                    //</editor-fold>


                    //  NON AKUNTING


                ),
            ),
            "580" => array(
                "master" => array(
                    //bagian ppn auto geser ke pusat
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".ppn out",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_ppn_out",
                            "transaksi_id" => "masterID",
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
                            "state" => ".sold",
                            "jenis" => ".ppn out",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "nilai_dipakai_ppn_out",
                            "transaksi_id" => "masterID",
                            //                            "transaksi_id" => "transaksi_id",
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
                            "state" => ".sold",
                            "jenis" => ".hutang ke konsumen",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "nilai_dipakai_hutang_ke_konsumen",
                            "transaksi_id" => "masterID",
                            //                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    //<editor-fold desc="LockerValue MASTER">
                    //<editor-fold desc="LockerValue HOLD">
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".hold",
                            "jenis" => ".ppn out",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_ppn_out",
                            "transaksi_id" => "masterID",
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
                            "jenis" => ".hutang ke konsumen",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "-nilai_dipakai_hutang_ke_konsumen",
                            "transaksi_id" => "masterID",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                    //</editor-fold>
                    //<editor-fold desc="LockerValue SOLD">
                    array(
                        "comName" => "LockerValue",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "state" => ".sold",
                            "jenis" => ".ppn out",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "nilai_dipakai_ppn_out",
                            "transaksi_id" => "masterID",
                            //                            "transaksi_id" => "transaksi_id",
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
                            "state" => ".sold",
                            "jenis" => ".hutang ke konsumen",
                            "produk_id" => "pihakID",
                            "nama" => "pihakName",
                            "nilai" => "nilai_dipakai_hutang_ke_konsumen",
                            "transaksi_id" => "masterID",
                            //                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(),
            ),
        ),
        "extendedSteps" => array(
            "discount" => array(
                "srcKey" => "discount",
                "groupID" => "c_finance",
                "components" => array(),
            ),
        ),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),


    //  config return penjualan
    "980" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
//                "pihakID" => "pihakID",
//                "pihakName" => "pihakName",
//                "cabangID" => "placeID",
//                "cabangName" => "placeName",
//                "paymentMethod" => "paymentMethod",
//                "referenceID" => "referenceID",
//                "referenceJenis" => "referenceJenis",
//                "referenceNomer" => "referenceNomer",
//                "gudangID" => "gudangID",
//                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
                // "ppn" => "(nett1*(ppnFactor/100))",
                // "nett2" => "(nett1+ppn)",

            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "produk_kode" => "produk_kode",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "(lebar_gross*panjang_gross*tinggi_gross)",

                //                "ppv"           => "ppv",
                "hpp" => "hpp",
                //                "harga"         => "harga",
                //                "ppn"           => "harga*(10/100)",
                //                "ppn_persen"    => "ppn_persen",
                //                "diskon"        => "diskon",
                //                "diskon_persen" => "diskon_persen",
                //                "nett"          => "harga-disc-ftot_discount+ppn",

                //                "sub_harga" => "sub_harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_diskon" => "sub_diskon",
                //                "sub_ppn" => "sub_ppn",
                //                "sub_nett" => "sub_nett",

//                "pihakID" => "pihakID",
//                "pihakName" => "pihakName",
//                "cabangID" => "placeID",
//                "cabangName" => "placeName",
//                "placeID" => "placeID",
//                "placeName" => "placeName",
//                "olehID" => "olehID",
//                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
//            "bruto" => "sub_harga",
//            "hpp" => "sub_hpp",
//            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(
            "hpp" => "sub_hpp",
            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",
            "berat_gross" => "sub_berat_gross",
            "volume_gross" => "sub_volume_gross",
        ),
        "injectorPajak" => array(
            "source" => "nett1",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            // "grand_total_ui"=>"grandTotal",
            "grandTotal" => "grandTotal",
            "nett2" => "grandTotal",
            "new_net2" => "grandTotal",
            "new_net3" => "grandTotal",
            // "nett1_bulat"=>"hasil",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
        ),
        "preProcessor" => array(
            "980" => array(
                "master" => array(),
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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",

                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

                "seller_id" => "sellerIDOrig",
                "seller_nama" => "sellerNameOrig",
                "top" => "top",
                "top_nama" => "top__nama",
                "tos" => "tos",
                "tos_nama" => "tos__nama",
                "referensi_id" => "referenceID",
                "referensi_nomer" => "referenceNomer",
                "referensi_jenis" => "referenceJenis",
                "pembayaran" => "paymentMethod",
                "pembayaran_sys" => "paymentMethod",
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
            "detailValues" => array(
                "harga" => "harga",
                "hpp" => "hpp",
                "ppn" => "ppn",
                "nett" => "nett",
                "ppv" => "ppv",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
            "rsltItemsValues" => array(
                //                "harga"  => "harga",
                "hpp" => "hpp",
                //                "diskon" => "diskon",
                //                "ppn"    => "ppn",
                //                "nett"   => "nett",

                //                "ppv" => "ppv",

                "berat_gross" => "berat_gross",
                "volume_gross" => "volume_gross",
                //                "lebar_gross"   => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross"  => "tinggi_gross",
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
            "980" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "4020" => "nett1",// return penjualan
                            "2030060" => "-ppn",// ppn out belum ada faktur
                            "1010030030" => "hpp",// persediaan produk
                            "5010" => "-hpp",// hpp
                            // "060200040" => "-add_disc",// diskon
                            "2010050" => "nett2",// hutang ke konsumen
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
                            "4020" => "nett1",// return penjualan
                            "2030060" => "-ppn",// ppn out
                            "1010030030" => "hpp",// persediaan produk
                            "5010" => "-hpp",// hpp
                            // "060200040" => "-add_disc",// diskon
                            "2010050" => "nett2",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",

                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // jurnal pusat
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "2010090010" => "ppv_riil",// hutang lain ppv
//                            "7010150" => "-ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            // "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "2010090010" => "ppv_riil",// hutang lain ppv
//                            "7010150" => "-ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    //<editor-fold desc="com-rekening-pembantu">
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nett2",// hutang ke konsumen
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
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2030060" => "-ppn",// ppn out
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
                    // detail laba lain-lain
                    array(
                        "comName" => "RekeningPembantuLRLainlain",
                        "loop" => array(
                            "7010150" => "-ppv_riil",// laba lain lain
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
//                            "cabang_id" => ".-1",
                            "extern_id" => ".3",// laba rugi lain-lain ppv
                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu hpp
                    array(
                        "comName" => "RekeningPembantuHpp",
                        "loop" => array(
                            "5010" => "-hpp",// hpp
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".1",
                            "extern_nama" => ".lokal",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "hpp",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    // pembantu return penjualan lokal
                    array(
                        "comName" => "RekeningPembantuReturnPenjualan",// lokal
                        "loop" => array(
                            "4020" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4020010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu return penjualan lokal - konsumen
                    array(
                        "comName" => "RekeningPembantuReturnPenjualanKonsumen",// lokal - konsumen
                        "loop" => array(
                            "4020" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4020010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => "pihakID",
                            "extern2_nama" => "pihakName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // pembantu return penjualan lokal - seller
                    array(
                        "comName" => "RekeningPembantuReturnPenjualanSeller",
                        "loop" => array(
                            "4020" => "nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4020010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => "sellerID",
                            "extern2_nama" => "sellerName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nett1",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuProduk",
                        "loop" => array(
                            "1010030030" => "sub_hpp",// persediaan produk
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "produk_qty" => "qty",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "gudangID",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                    //----------------------
                    // pembantu penjualan lokal - jenis produk (lokal/import)
                    array(
                        "comName" => "RekeningPembantuReturnPenjualanItemByProdukJenis",// lokal - produk-jenis
                        "loop" => array(
                            "4020" => "sub_nett1",// retur penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4020010",
                            "extern_nama" => ".lokal",
                            "extern2_id" => ".1",// produk lokal/import
                            "extern2_nama" => ".lokal",// produk lokal/import
                            /*
                             * ini nanti dihidupkan lagi broo masih ditembak default lokal karena data lama tidak menyimpan jenis penjualan
                             */
                            // "extern2_id" => "produkJenisID",// produk lokal/import
                            // "extern2_nama" => "produkJenisName",// produk lokal/import
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nett1",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    // pembantu penjualan jenis produk (lokal/import)
                    array(
                        "comName" => "RekeningPembantuReturnPenjualanItemByJenis",// jenis produknya (lokal/import)
                        "loop" => array(
                            "4020" => "sub_nett1",// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            /*
                             * ini nanti dihidupkan juga
                             */
                            // "extern_id" => "produkJenisID",// produk lokal/import
                            // "extern_nama" => "produkJenisName",// produk lokal/import
                            "extern_id" => ".1",// produk lokal/import
                            "extern_nama" => ".lokal",// produk lokal/import
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                            "harga" => "nett1",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //----------------------

                ),
            ),
        ),
        "postProcessor" => array(
            "980r" => array(
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
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),

                ),
            ),
            "980g" => array(
                "master" => array(),
                "detail" => array(
                    array(
                        "comName" => "ReleaserDueDate",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "referenceID",
                            "extern_nama" => "referenceNomer",
                            "extern_jenis" => "referenceJenis",
//                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_nomer" => "nomer",
//                            "terbayar" => "nilai_bayar",
//                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "980" => array(
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
                            "label" => ".piutang dagang",
                            "sisa" => "nett2",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment anti source return penjualan (cache dan mutasi)
                    array(
                        "comName" => "PaymentAntisourceCustomer",
                        "loop" => array(
                            "2010050" => "nett2",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett2",
                            "label" => ".piutang dagang",
                            "extern_label2" => ".customer",
                        ),
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
                            "jml" => "qty",
                            "produk_id" => "id",
                            "hpp" => "hpp",
                            "jml_nilai" => "sub_hpp",
                            "hpp_riil" => "hpp_riil",
                            "jml_nilai_riil" => "sub_hpp_riil",
                            "ppv_riil" => "ppv_riil",
                            "ppv_nilai_riil" => "sub_ppv_riil",
                            "nama" => "name",
                            "cabang_id" => "placeID",
                            "gudang_id" => "gudangID",
                            "hpp_nppv" => "hpp_nppv",
                            "jml_nilai_nppv" => "sub_hpp_nppv",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "unit" => "qty",
//                            "produk_id" => "id",
//                            "produk_nama" => "name",
//                            "hpp" => "hpp",
//                            "jml_nilai" => "sub_hpp",
//                            "hpp_riil" => "hpp_riil",
//                            "jml_nilai_riil" => "sub_hpp_riil",
//                            "ppv_riil" => "ppv_riil",
//                            "ppv_nilai_riil" => "sub_ppv_riil",
//                            "cabang_id" => "placeID",
//                            "gudang_id" => "gudangID",
//                            "hpp_nppv" => "hpp_nppv",
//                            "jml_nilai_nppv" => "sub_hpp_nppv",
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".sold",
                            "jumlah" => "-qty",
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

        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
    //  config cancel packing (make fullfill)
    "1980" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
//                "pihakID" => "pihakID",
//                "pihakName" => "pihakName",
//                "cabangID" => "placeID",
//                "cabangName" => "placeName",
//                "paymentMethod" => "paymentMethod",
//                "referenceID" => "referenceID",
//                "referenceJenis" => "referenceJenis",
//                "referenceNomer" => "referenceNomer",
//                "gudangID" => "gudangID",
//                "gudangName" => "gudangName",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
                "ppn" => "(nett1*(10/100))",
                "nett2" => "(nett1+ppn)",
            ),
            "rsltItems" => array(//===sumber nilai berupa rincian
                "dtime" => "dtime",
                "id" => "id",
                "produk_kode" => "produk_kode",
                "label" => "label",
                "name" => "nama",
                "qty" => "jml",
                "satuan" => "satuan",
                "note" => "note",

                //"berat"         => "berat",
                //"lebar"         => "lebar",
                //"panjang"       => "panjang",
                //"tinggi"        => "tinggi",
                //"volume"        => "volume",
                "berat_gross" => "berat_gross",
                "lebar_gross" => "lebar_gross",
                "panjang_gross" => "panjang_gross",
                "tinggi_gross" => "tinggi_gross",
                "volume_gross" => "(lebar_gross*panjang_gross*tinggi_gross)",

                //                "ppv"           => "ppv",
                "hpp" => "hpp",
                //                "harga"         => "harga",
                //                "ppn"           => "harga*(10/100)",
                //                "ppn_persen"    => "ppn_persen",
                //                "diskon"        => "diskon",
                //                "diskon_persen" => "diskon_persen",
                //                "nett"          => "harga-disc-ftot_discount+ppn",

                //                "sub_harga" => "sub_harga",
                //                "sub_hpp" => "sub_hpp",
                //                "sub_diskon" => "sub_diskon",
                //                "sub_ppn" => "sub_ppn",
                //                "sub_nett" => "sub_nett",

//                "pihakID" => "pihakID",
//                "pihakName" => "pihakName",
//                "cabangID" => "placeID",
//                "cabangName" => "placeName",
//                "placeID" => "placeID",
//                "placeName" => "placeName",
//                "olehID" => "olehID",
//                "olehName" => "olehName",
            ),
        ),
        "valueBuilders" => array(
//            "bruto" => "sub_harga",
//            "hpp" => "sub_hpp",
//            "nett" => "sub_nett",
            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            "tagihan" => "grand_total-discount-dp",
        ),
        "valueBuilders_rsltItems" => array(
            "hpp" => "sub_hpp",
            //            "harga"       => "sub_harga",
            //            "ppn"         => "sub_ppn",
            //            "diskon"      => "sub_diskon",
            //            "nett"        => "sub_nett",
            //            "grand_total" => "harga+ppn+ongkir_tax+install_tax+ongkir+install",
            //			"advance_cash"   => ".0", // jumlah uang yang diterima
            //			"advance_hutang" => "(advance_cash*100)/(100+10)", // jumlah hutang ke konsumen atau piutang minus
            //			"advance_ppn"    => "advance_hutang/10", // jumlah ppn yang dibayarkan
            //
            //            "tagihan" => "grand_total-discount-dp-nilai_cia",

            "berat_gross" => "sub_berat_gross",
            "volume_gross" => "sub_volume_gross",

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

                "customers_id" => "customerID",
                "customers_nama" => "customerName",

                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "bruto",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
//                "referensi_id" => "referenceID",
//
//                "pembayaran" => "paymentMethod",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",

                "seller_id" => "sellerID",
                "seller_nama" => "sellerName",
                "top" => "top",
                "top_nama" => "top__nama",
                "tos" => "tos",
                "tos_nama" => "tos__nama",
                "referensi_id" => "referenceID",
                "referensi_nomer" => "referenceNomer",
                "referensi_jenis" => "referenceJenis",
                "pembayaran" => "paymentMethod",
                "pembayaran_sys" => "paymentMethod",
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
            "detailValues" => array(
                "harga" => "harga",
                "hpp" => "hpp",
                "ppn" => "ppn",
                "nett" => "nett",
                "ppv" => "ppv",
            ),
            "rsltItems" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
            "rsltItemsValues" => array(
                //                "harga"  => "harga",
                "hpp" => "hpp",
                //                "diskon" => "diskon",
                //                "ppn"    => "ppn",
                //                "nett"   => "nett",

                //                "ppv" => "ppv",

                "berat_gross" => "berat_gross",
                "volume_gross" => "volume_gross",
                //                "lebar_gross"   => "lebar_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "tinggi_gross"  => "tinggi_gross",
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
        "components" => array(),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID|customerID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|placeID|olehID|customerID",
            "stepCode|customerID",
            "stepCode|placeID|customerID",
            "stepCode|olehID|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|placeID|olehID|customerID",
            "stepCode|masterID|customerID",
            "stepCode|masterID|placeID|customerID",
            "stepCode|masterID|olehID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID|customerID",
    ),
);