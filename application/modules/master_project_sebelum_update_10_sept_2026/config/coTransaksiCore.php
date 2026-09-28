<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */

$config["coTransaksiCore"] = array(
    // realisasi project
    "5882" => array(
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
                "customerID" => "pihakProjekCustomerID",
                "customerName" => "pihakProjekCustomerNama",
                //-----------------
//                "gudang2ID" => "pihakProjekGudangID",
//                "gudang2Name" => "pihakProjekGudangNama",
//                "place2ID" => "branch",
//                "place2Name" => "branch__label",
                //-----------------
                "gudangID" => "pihakProjekGudangID",
                "gudangName" => "pihakProjekGudangName",
                "ppn_items2" => "harga_items2*(11/100)",
//                "perkiraan_rl_hpp" => "harga_non_ppn-total_anggaran",
//                "perkiraan_rl_ang" => "harga_non_ppn-total_anggaran",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",
//                "subtotal" => "jml*nett2",


            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "sisa_alokasi" => "qty_saldo-qty",
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",
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
            "master_dependent" => array(
                "paymentMethod" => array(
                    "cash" => array(
                        // "nilai_cash" => "tagihan",
                        // "nilai_credit" => "0",
                        // bila ada dp untuk instansi
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "cia" => array(
                        "nilai_cash" => "0",
                        //                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "credit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "debit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
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
        //supaya gerbang tidak direcap ke main
        "recapValueException" => array(
            "items2_sum",
        ),
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
        "valueBuilders" => array(
            "shipsvc_ppn_value" => "(shipping_service*ppnFactor/100)",
            "dp_value" => "(dp*100)/(100+ppnFactor)",
            "dp_ppn_value" => "dp_value*(ppnFactor/100)",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
            "grand_total" => "nett1+install_tax+install+ongkir",
            "grand_ppn" => "ongkir_ppn+ppn",
            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
            "new_net1" => "nett1+ongkir",
            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
            "tagihan" => "nett1_bulat+ppn_out_bulat-dp-nilai_cia",
            "grand_total_ui" => "nett1+install_tax+install+ongkir",
            "tagihan_ui" => "nett1+install_tax+install+ongkir+grand_ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",

            "garansi_nilai" => "(tarifGaransi/100)*grand_total_ui",// ini menjadi retensi
            "garansi_nilai_ppn" => "garansi_nilai/ppnFactor",// ini menjadi retensi ppn
            "piutang_retensi" => "garansi_nilai_ppn+garansi_nilai",

            "penjualan_retensi" => "projectHarga-garansi_nilai",
            "penjualan_ppn_retensi" => "penjualan_retensi/ppnFactor",
            "piutang_dagang" => "penjualan_retensi+penjualan_ppn_retensi",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
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

            //            "grand_hutang" => "",

        ),
        "valueInjectorBulat" => array(
            "source" => "grand_total_ui",
            "injectTo" => array(
                "hasil" => "nett1_bulat",
                "hasil_child" => "ppn_out_bulat",
                "pembulatan" => "nilai_pembulatan",
                "hasil_total" => "grand_pembulatan",
            ),
        ),
        "externalValues" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
//            "5882" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "gudang_id" => "gudangID",
//                        ),
////                        "resultParams" => array(
////                            "items2_sum" => array(
////                                "hpp" => "hpp",
////                                "hpp_riil" => "hpp_riil",
////                                "ppv_riil" => "ppv_riil",
////                                "ppn_in" => "ppn_in",
////                                "ppn_in_nilai" => "ppn_in_nilai",
////                                "suppliers_id" => "suppliers_id",
////                                "suppliers_nama" => "suppliers_nama",
////                            ),
////                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
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
////                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    // preprocc ini bertugas menginject nilai dari rsltItems ke gerbang items
//                    // yang sesuai antara extern_id rsltItem dengan id/key items
////                    array(
////                        "comName" => "Sync2GatesRsltItems",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "id",
////                            "extern_nama" => "nama",
////                            "produk_qty" => "qty",
////                            "gudang_id" => "gudangID",
////
////                            "extern_id_src" => "id",
////                            "hpp" => "hpp",
////                            "hpp_riil" => "hpp_riil",
////                            "ppv_riil" => "ppv_riil",
////                        ),
////                        "resultParams" => array(
////                            "items" => array(
////                                "hpp" => "hpp",
////                                "hpp_riil" => "hpp_riil",
////                                "ppv_riil" => "ppv_riil",
////                            ),
////                        ),
////                        "srcGateName" => "rsltItems",
////                        "srcRawGateName" => "rsltItems",
////                    ),
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
            "sub_detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
            "sub_detail_items" => array(
                "dtime" => "dtime",
                "produk_id" => "produk_id",
                "produk_label" => "label",
                "produk_nama" => "produk_nama",
                "produk_ord_jml" => "produk_ord_jml",
                "valid_qty" => "valid_qty",
                "produk_ord_hrg" => "produk_ord_harga",
                "produk_jenis" => "produk_jenis",
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
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
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
        "valuePembulatan" => array(
            4 => array(
                "source" => "new_net1",
                "replacer" => array(
                    "hasil" => "nett1_bulat",
                    "hasil_child" => "ppn_out_bulat",
                    "pembulatan" => "nilai_pembulatan",
                    "hasil_total" => "grand_pembulatan",
                    "new_tagihan" => "nilai_credit",

                ),
            ),
        ),
        "components" => array(
//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-hpp",// persediaan produk
////                            "5030" => "(hpp+ppv_riil)",// hpp projek
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                            "7010150" => "ppv_riil",// laba lain lain
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
//                            "1010030030" => "-hpp",// persediaan produk
////                            "5030" => "(hpp+ppv_riil)",// hpp projek
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //jurnal ppv pusat
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010100010" => "-ppv_riil",// hutang lain ppv
////                            "7010150" => "ppv_riil",// laba lain lain
////                        ),
////                        "static" => array(
////                            "cabang_id" => ".-1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010100010" => "-ppv_riil",// hutang lain ppv
////                            "7010150" => "ppv_riil",// laba lain lain
////                        ),
////                        "static" => array(
////                            "cabang_id" => ".-1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // pembantu hpp
//                    array(
//                        "comName" => "RekeningPembantuHpp",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".5010030",
//                            "extern_nama" => ".project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "nett1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening hpp pembantu projek
//                    array(
//                        "comName" => "RekeningPembantuHppProject",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "pihakProjekCustomerID",//"customerID",
//                            "extern2_nama" => "pihakProjekCustomerNama",//"customerName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening hpp pembantu projek
//                    array(
//                        "comName" => "RekeningPembantuHppProjectWorkorder",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "workOrderDetails",//"workorderID",
//                            "extern2_nama" => "workOrderDetails__nama",//"workorderName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    // project cost digeser saat packinglist di cabang (hpp project pada porject cost) 2022-06-29
//
//                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                            "5010" => "project_cost",
//////                            "5030" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                            "5010" => "project_cost",
//////                            "5030" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuProjek",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "customerID",// konsumen
////                            "extern_nama" => "customerName",// konsumen
//////                            "extern2_id" => "transaksi_id_target",// so
//////                            "extern2_nama" => "transaksi_nomer_target",// so
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
////                    array(
////                        "comName" => "RekeningPembantuProjekCustomer",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakProjekID",// project
////                            "extern_nama" => "pihakProjekName",// project
////                            "extern2_id" => "customerID",// konsumen
////                            "extern2_nama" => "customerName",// konsumen
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//
//                    // pembantu hpp, project cost
//
////                    array(
////                        "comName" => "RekeningPembantuHpp",
////                        "loop" => array(
////                            "5010" => "project_cost",// hpp
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".5010030",
////                            "extern_nama" => ".project",
////                            "extern2_id" => ".0",
////                            "extern2_nama" => "",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "harga" => "nett1",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuHppProject",
////                        "loop" => array(
////                            "5010" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakProjekID",// projek
////                            "extern_nama" => "pihakProjekName",// projek
////                            "extern2_id" => "pihakProjekCustomerID",
////                            "extern2_nama" => "pihakProjekCustomerNama",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//                ),
//                "detail" => array(
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "-sub_hpp",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuHppProjectWorkorderDetail",
//                        "loop" => array(
//                            "1010030030" => "-sub_hpp",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "pihakProjekWorkOrderID",//"workorderID",
//                            "extern2_nama" => "pihakProjekWorkOrderNama",//"workorderName",
//                            "extern3_id" => "id",
//                            "extern3_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//
//                ),
//            ),

//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "0612" => "garansi_nilai",//biaya garansi
//                            "020413" => "garansi_nilai",//hutang garansi
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
//                            "0612" => "garansi_nilai",//biaya garansi
//                            "020413" => "garansi_nilai",//hutang garansi
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
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "5030" => "garansi_nilai",//hpp projek
//                            "0612" => "-garansi_nilai",//biaya garansi
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
//                            "5030" => "garansi_nilai",//hpp projek
//                            "0612" => "-garansi_nilai",//biaya garansi
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
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "020413" => "garansi_nilai",//hutang garansi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // JURNAL PENJUALAN, DIPOTONG RETENSI
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
//////                            "penjualan projek" => "nett1",
//////                            "ppn out" => "nilai_tambah_ppn_out",
//////
////                            "piutang dagang" => "piutang_dagang",
////                            "penjualan projek" => "penjualan_retensi",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
//////
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
//////                            "penjualan projek" => "nett1",
//////                            "ppn out" => "nilai_tambah_ppn_out",
////                            "piutang dagang" => "piutang_dagang",
////                            "penjualan projek" => "penjualan_retensi",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
////                            "piutang dagang" => "piutang_dagang",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
//////                            "ppn out" => "nilai_tambah_ppn_out",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // JURNAL PIUTANG RETENSI
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
//////
//////                            "piutang retensi" => "piutang_retensi",
//////                            "penjualan projek" => "garansi_nilai",
//////                            "ppn out" => "garansi_nilai_ppn",
////                            "piutang retensi" => "piutang_retensi",
////                            "penjualan projek" => "garansi_nilai",
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "piutang retensi" => "piutang_retensi",
////                            "penjualan projek" => "garansi_nilai",
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "piutang retensi" => "piutang_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(

            "5882" => array(
                "master" => array(),
                "detail" => array(

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //tambahan lockerstokworkorder
                    array(
                        "comName" => "LockerStockWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi work order
                    array(
                        "comName" => "LockerStockMutasiWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //update project_komposisi_sub_workoder kolom kredit dan saldo
                    array(
                        "comName" => "LockerProjectKomposisiSubWorkoder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "produk_dasar_id" => "id",
                            "produk_dasar_nama" => "nama",
                            "qty_kredit" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                            "project_id" => "pihakProjekID",
                            "produk_id" => "pihakProjekID",
                            "fase_id" => "pihakProjekWorkOrderID",
                            "sub_fase_id" => "pihakProjekWorkOrderSubID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),


                ),
            ),


//            "5882" => array(
//                "master" => array(
////                    array(
////                        "comName" => "TransaksiDataGaransi",
////                        "loop" => array(),
////                        "static" => array(
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "produk_ord_hrg" => "projectHarga",
////                            "produk_ord_ppn" => "projectPpn",
////                            "produk_ord_netto" => "projectGrandtotal",
////                            "garansi_tarif" => "tarifGaransi",
////                            "garansi_nilai" => "garansi_nilai",
////                            "garansi_dtime" => "dateGaransi",
////                            "produk_id" => "projectID",
////                            "produk_nama" => "projectName",
////                            "customers_id" => "customerDetails",
////                            "customers_nama" => "customerDetails__nama",
////                            "status" => ".1",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "extendedSteps" => array(
            "discount" => array(
                "srcKey" => "discount",
                "groupID" => "c_finance",
                "components" => array(),
            ),
        ),
        //nulis/udpate produk project di update dengan transaksi ID  588spo dan nomernya
        "postUpdaterMisc" => array(
//            "588spo" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".open"
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".update",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "ProjectSalesMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "projectID",
//                            "closing_oleh_id" => "olehID",
//                            "closing_oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".close",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                        "revert" => true,
//                    ),
//                ),
//            ),
        ),

        //untuk nulis items2-sum ke table transaksi_data_details
        "writeSubDetail" => array(
//            "588spo" => true,
//            "588so" => true,
//            "5882spd" => true,
//            "5882" => false,
        ),
        //untuk pembalik jika didelete, reaktivasi produk project
        "postProcRevert" => array(
//            "588spo" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah di po project belum?
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".revert",
//                            //open,close,revert,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing, revert untuk reject transaksi dan mengaktifkan kembali produk projet
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah dipo projek belum
//
//            ),
        ),
        //pasang difollowup untuk update subitems valid qty
        "postUpdateItems" => array(
//            "588so" => array(
//                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
//            ),
//            "5882spd" => array(
//                "extractedSubItems" => "sub_detail",
//                "extractedSubItems2" => "items3_sum",
//            ),
//            "5882" => array(//dipasang langsung di kontroller karena menggunakan mode multi
//            ),
        ),
    ),
    "5883" => array(
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
                "customerID" => "pihakProjekCustomerID",
                "customerName" => "pihakProjekCustomerNama",
                //-----------------
//                "gudang2ID" => "pihakProjekGudangID",
//                "gudang2Name" => "pihakProjekGudangNama",
//                "place2ID" => "branch",
//                "place2Name" => "branch__label",
                //-----------------
                "gudangID" => "pihakProjekGudangID",
                "gudangName" => "pihakProjekGudangName",
                "ppn_items2" => "harga_items2*(11/100)",
            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",
//                "subtotal" => "jml*nett2",


            ),
            "detail2_sum" => array(//===sumber nilai berupa rincian
                "sisa_alokasi" => "qty_saldo-qty",
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",
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
            "master_dependent" => array(
                "paymentMethod" => array(
                    "cash" => array(
                        // "nilai_cash" => "tagihan",
                        // "nilai_credit" => "0",
                        // bila ada dp untuk instansi
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "cia" => array(
                        "nilai_cash" => "0",
                        //                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "credit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "debit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
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
        //supaya gerbang tidak direcap ke main
        "recapValueException" => array(
            "items2_sum",
        ),
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
        "valueBuilders" => array(
            "shipsvc_ppn_value" => "(shipping_service*ppnFactor/100)",
            "dp_value" => "(dp*100)/(100+ppnFactor)",
            "dp_ppn_value" => "dp_value*(ppnFactor/100)",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
            "grand_total" => "nett1+install_tax+install+ongkir",
            "grand_ppn" => "ongkir_ppn+ppn",
            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
            "new_net1" => "nett1+ongkir",
            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
            "tagihan" => "nett1_bulat+ppn_out_bulat-dp-nilai_cia",
            "grand_total_ui" => "nett1+install_tax+install+ongkir",
            "tagihan_ui" => "nett1+install_tax+install+ongkir+grand_ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",

            "garansi_nilai" => "(tarifGaransi/100)*harga",// ini menjadi retensi
            "garansi_nilai_ppn" => "garansi_nilai/ppnFactor",// ini menjadi retensi ppn
            "piutang_retensi" => "garansi_nilai_ppn+garansi_nilai",

            "penjualan_retensi" => "projectHarga-garansi_nilai",
            "penjualan_ppn_retensi" => "penjualan_retensi/ppnFactor",
            "piutang_dagang" => "penjualan_retensi+penjualan_ppn_retensi",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
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

            //            "grand_hutang" => "",

        ),
        "valueInjectorBulat" => array(
            "source" => "grand_total_ui",
            "injectTo" => array(
                "hasil" => "nett1_bulat",
                "hasil_child" => "ppn_out_bulat",
                "pembulatan" => "nilai_pembulatan",
                "hasil_total" => "grand_pembulatan",
            ),
        ),
        "externalValues" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
//            "5882" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "gudang_id" => "gudangID",
//                        ),
////                        "resultParams" => array(
////                            "items2_sum" => array(
////                                "hpp" => "hpp",
////                                "hpp_riil" => "hpp_riil",
////                                "ppv_riil" => "ppv_riil",
////                                "ppn_in" => "ppn_in",
////                                "ppn_in_nilai" => "ppn_in_nilai",
////                                "suppliers_id" => "suppliers_id",
////                                "suppliers_nama" => "suppliers_nama",
////                            ),
////                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
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
////                                "ppv_riil" => "ppv_riil",
//                                "subtotal" => "subtotal",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//
//                    // preprocc ini bertugas menginject nilai dari rsltItems ke gerbang items
//                    // yang sesuai antara extern_id rsltItem dengan id/key items
////                    array(
////                        "comName" => "Sync2GatesRsltItems",
////                        "loop" => array(),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "id",
////                            "extern_nama" => "nama",
////                            "produk_qty" => "qty",
////                            "gudang_id" => "gudangID",
////
////                            "extern_id_src" => "id",
////                            "hpp" => "hpp",
////                            "hpp_riil" => "hpp_riil",
////                            "ppv_riil" => "ppv_riil",
////                        ),
////                        "resultParams" => array(
////                            "items" => array(
////                                "hpp" => "hpp",
////                                "hpp_riil" => "hpp_riil",
////                                "ppv_riil" => "ppv_riil",
////                            ),
////                        ),
////                        "srcGateName" => "rsltItems",
////                        "srcRawGateName" => "rsltItems",
////                    ),
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
            "sub_detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
            "sub_detail_items" => array(
                "dtime" => "dtime",
                "produk_id" => "produk_id",
                "produk_label" => "label",
                "produk_nama" => "produk_nama",
                "produk_ord_jml" => "produk_ord_jml",
                "valid_qty" => "valid_qty",
                "produk_ord_hrg" => "produk_ord_harga",
                "produk_jenis" => "produk_jenis",
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
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
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
        "valuePembulatan" => array(
            4 => array(
                "source" => "new_net1",
                "replacer" => array(
                    "hasil" => "nett1_bulat",
                    "hasil_child" => "ppn_out_bulat",
                    "pembulatan" => "nilai_pembulatan",
                    "hasil_total" => "grand_pembulatan",
                    "new_tagihan" => "nilai_credit",

                ),
            ),
        ),
        "components" => array(
//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010030030" => "-hpp",// persediaan produk
////                            "5030" => "(hpp+ppv_riil)",// hpp projek
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                            "7010150" => "ppv_riil",// laba lain lain
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
//                            "1010030030" => "-hpp",// persediaan produk
////                            "5030" => "(hpp+ppv_riil)",// hpp projek
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    //jurnal ppv pusat
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "2010100010" => "-ppv_riil",// hutang lain ppv
////                            "7010150" => "ppv_riil",// laba lain lain
////                        ),
////                        "static" => array(
////                            "cabang_id" => ".-1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "2010100010" => "-ppv_riil",// hutang lain ppv
////                            "7010150" => "ppv_riil",// laba lain lain
////                        ),
////                        "static" => array(
////                            "cabang_id" => ".-1",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // detail laba lain-lain
//                    array(
//                        "comName" => "RekeningPembantuLRLainlain",
//                        "loop" => array(
//                            "7010150" => "ppv_riil",// laba lain lain
//                        ),
//                        "static" => array(
//                            "cabang_id" => ".-1",
//                            "extern_id" => ".3",// laba rugi lain-lain ppv
//                            "extern_nama" => ".ppv", // laba rugi lain-lain ppv
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // pembantu hpp
//                    array(
//                        "comName" => "RekeningPembantuHpp",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".5010030",
//                            "extern_nama" => ".project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "harga" => "nett1",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening hpp pembantu projek
//                    array(
//                        "comName" => "RekeningPembantuHppProject",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "pihakProjekCustomerID",//"customerID",
//                            "extern2_nama" => "pihakProjekCustomerNama",//"customerName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // rekening hpp pembantu projek
//                    array(
//                        "comName" => "RekeningPembantuHppProjectWorkorder",
//                        "loop" => array(
//                            "5010" => "(hpp+ppv_riil)",// hpp projek
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "workOrderDetails",//"workorderID",
//                            "extern2_nama" => "workOrderDetails__nama",//"workorderName",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//
//                    // project cost digeser saat packinglist di cabang (hpp project pada porject cost) 2022-06-29
//
//                    //region CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                            "5010" => "project_cost",
//////                            "5030" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                            "5010" => "project_cost",
//////                            "5030" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuProjek",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "customerID",// konsumen
////                            "extern_nama" => "customerName",// konsumen
//////                            "extern2_id" => "transaksi_id_target",// so
//////                            "extern2_nama" => "transaksi_nomer_target",// so
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
////                    array(
////                        "comName" => "RekeningPembantuProjekCustomer",
////                        "loop" => array(
////                            "1010030060" => "-project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakProjekID",// project
////                            "extern_nama" => "pihakProjekName",// project
////                            "extern2_id" => "customerID",// konsumen
////                            "extern2_nama" => "customerName",// konsumen
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//
//                    // pembantu hpp, project cost
//
////                    array(
////                        "comName" => "RekeningPembantuHpp",
////                        "loop" => array(
////                            "5010" => "project_cost",// hpp
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => ".5010030",
////                            "extern_nama" => ".project",
////                            "extern2_id" => ".0",
////                            "extern2_nama" => "",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                            "harga" => "nett1",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuHppProject",
////                        "loop" => array(
////                            "5010" => "project_cost",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakProjekID",// projek
////                            "extern_nama" => "pihakProjekName",// projek
////                            "extern2_id" => "pihakProjekCustomerID",
////                            "extern2_nama" => "pihakProjekCustomerNama",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    //endregion CABANG, KELUAR PROJEK COST ke HPP, HPP PROJEK
//                ),
//                "detail" => array(
//                    array(
//                        "comName" => "RekeningPembantuProduk",
//                        "loop" => array(
//                            "1010030030" => "-sub_hpp",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuHppProjectWorkorderDetail",
//                        "loop" => array(
//                            "1010030030" => "-sub_hpp",// persediaan produk
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakProjekID",// projek
//                            "extern_nama" => "pihakProjekName",// projek
//                            "extern2_id" => "pihakProjekWorkOrderID",//"workorderID",
//                            "extern2_nama" => "pihakProjekWorkOrderNama",//"workorderName",
//                            "extern3_id" => "id",
//                            "extern3_nama" => "name",
//                            "produk_qty" => "-jml",
//                            "produk_nilai" => "hpp",
//                            "gudang_id" => "gudangID",
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "rsltItems",
//                        "srcRawGateName" => "rsltItems",
//                    ),
//
//                ),
//            ),

//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "0612" => "garansi_nilai",//biaya garansi
//                            "020413" => "garansi_nilai",//hutang garansi
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
//                            "0612" => "garansi_nilai",//biaya garansi
//                            "020413" => "garansi_nilai",//hutang garansi
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
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "5030" => "garansi_nilai",//hpp projek
//                            "0612" => "-garansi_nilai",//biaya garansi
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
//                            "5030" => "garansi_nilai",//hpp projek
//                            "0612" => "-garansi_nilai",//biaya garansi
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
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "020413" => "garansi_nilai",//hutang garansi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//
//                            "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//
//                    // JURNAL PENJUALAN, DIPOTONG RETENSI
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
//////                            "penjualan projek" => "nett1",
//////                            "ppn out" => "nilai_tambah_ppn_out",
//////
////                            "piutang dagang" => "piutang_dagang",
////                            "penjualan projek" => "penjualan_retensi",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
//////
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
//////                            "penjualan projek" => "nett1",
//////                            "ppn out" => "nilai_tambah_ppn_out",
////                            "piutang dagang" => "piutang_dagang",
////                            "penjualan projek" => "penjualan_retensi",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
//////                            "piutang dagang" => "nilai_tambah_hutang_ke_konsumen",
////                            "piutang dagang" => "piutang_dagang",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
//////                            "ppn out" => "nilai_tambah_ppn_out",
////                            "ppn out" => "penjualan_ppn_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//
//                    // JURNAL PIUTANG RETENSI
//
////                    array(
////                        "comName" => "Jurnal",
////                        "loop" => array(
//////
//////                            "piutang retensi" => "piutang_retensi",
//////                            "penjualan projek" => "garansi_nilai",
//////                            "ppn out" => "garansi_nilai_ppn",
////                            "piutang retensi" => "piutang_retensi",
////                            "penjualan projek" => "garansi_nilai",
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "Rekening",
////                        "loop" => array(
////                            "piutang retensi" => "piutang_retensi",
////                            "penjualan projek" => "garansi_nilai",
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "piutang retensi" => "piutang_retensi",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
////                    array(
////                        "comName" => "RekeningPembantuCustomer",
////                        "loop" => array(
////                            "ppn out" => "garansi_nilai_ppn",
////                        ),
////                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "extern_id" => "pihakID",
////                            "extern_nama" => "pihakName",
////                            "jenis" => "jenisTr",
////                            "transaksi_no" => "nomer",
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "postProcessor" => array(

            "5882" => array(
                "master" => array(),
                "detail" => array(

                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "-jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    array(
                        "comName" => "LockerStock",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".hold",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => "transaksi_id",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    // locker stok mutasi
                    array(
                        "comName" => "LockerStockMutasi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "-jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //tambahan lockerstokworkorder
                    array(
                        "comName" => "LockerStockWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis" => ".produk",
                            "state" => ".active",
                            "jumlah" => "jml",
                            "produk_id" => "id",
                            "nama" => "nama",
                            "satuan" => "satuan",
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                        ),
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),
                    // locker stok mutasi work order
                    array(
                        "comName" => "LockerStockMutasiWorkOrder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "qty_debet" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                            "project_id" => "pihakProjekID",
                            "work_order_id" => "pihakProjekWorkOrderID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),

                    //update project_komposisi_sub_workoder kolom kredit dan saldo
                    array(
                        "comName" => "LockerProjectKomposisiSubWorkoder",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "produk_dasar_id" => "id",
                            "produk_dasar_nama" => "nama",
                            "qty_kredit" => "jml",
                            "produk_nilai" => "hpp",
                            "gudang_id" => "pihakProjekWorkorderSubGudangID",
                            "jenis" => "jenisTr",
                            "project_id" => "pihakProjekID",
                            "produk_id" => "pihakProjekID",
                            "fase_id" => "pihakProjekWorkOrderID",
                            "sub_fase_id" => "pihakProjekWorkOrderSubID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items2_sum",
                        "srcRawGateName" => "items2_sum",
                    ),


                ),
            ),


//            "5882" => array(
//                "master" => array(
////                    array(
////                        "comName" => "TransaksiDataGaransi",
////                        "loop" => array(),
////                        "static" => array(
////                            "jenis" => "jenisTr",
////                            "jenis_master" => "jenisTrMaster",
////                            "jenis_top" => "jenisTrTop",
////                            "master_id" => "transaksi_id",
////
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "oleh_id" => "olehID",
////                            "oleh_nama" => "olehName",
////                            "produk_ord_hrg" => "projectHarga",
////                            "produk_ord_ppn" => "projectPpn",
////                            "produk_ord_netto" => "projectGrandtotal",
////                            "garansi_tarif" => "tarifGaransi",
////                            "garansi_nilai" => "garansi_nilai",
////                            "garansi_dtime" => "dateGaransi",
////                            "produk_id" => "projectID",
////                            "produk_nama" => "projectName",
////                            "customers_id" => "customerDetails",
////                            "customers_nama" => "customerDetails__nama",
////                            "status" => ".1",
////
////                        ),
////                        "srcGateName" => "main",
////                        "srcRawGateName" => "main",
////                    ),
//                ),
//                "detail" => array(),
//            ),
        ),
        "extendedSteps" => array(
            "discount" => array(
                "srcKey" => "discount",
                "groupID" => "c_finance",
                "components" => array(),
            ),
        ),
        //nulis/udpate produk project di update dengan transaksi ID  588spo dan nomernya
        "postUpdaterMisc" => array(
//            "588spo" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".open"
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".update",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "5882" => array(
//                "master" => array(
//                    array(
//                        "comName" => "ProjectSalesMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "projectID",
//                            "closing_oleh_id" => "olehID",
//                            "closing_oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".close",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                        "revert" => true,
//                    ),
//                ),
//            ),
        ),

        //untuk nulis items2-sum ke table transaksi_data_details
        "writeSubDetail" => array(
//            "588spo" => true,
//            "588so" => true,
//            "5882spd" => true,
//            "5882" => false,
        ),
        //untuk pembalik jika didelete, reaktivasi produk project
        "postProcRevert" => array(
//            "588spo" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah di po project belum?
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".revert",
//                            //open,close,revert,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing, revert untuk reject transaksi dan mengaktifkan kembali produk projet
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah dipo projek belum
//
//            ),
        ),
        //pasang difollowup untuk update subitems valid qty
        "postUpdateItems" => array(
//            "588so" => array(
//                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
//            ),
//            "5882spd" => array(
//                "extractedSubItems" => "sub_detail",
//                "extractedSubItems2" => "items3_sum",
//            ),
//            "5882" => array(//dipasang langsung di kontroller karena menggunakan mode multi
//            ),
        ),
    ),

    // membuat quotation dan approve jadi so
    "588" => array(
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
                //-----------------
                "gudang2ID" => "pihakProjekGudangID",
                "gudang2Name" => "pihakProjekGudangNama",
                "place2ID" => "branch",
                "place2Name" => "branch__label",
                //-----------------
                "projectID" => "current_projectID",//"id",
                "projectName" => "nama",


            ),
            "detail" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
                "subtotal" => "jml*nett1",
//                "subtotal" => "jml*nett2",


            ),

            "detail2_sum" => array(//===sumber nilai berupa rincian
                "qty" => "jml",
                "nett1" => "(harga-disc)",
//                "ppn" => "(nett1*(10/100))",
                "ppn" => "(nett1*(ppnFactor/100))",
//                "nett2" => "(nett1+ppn)",

                //
                //==ini config terkait diskon kumulatif, yang diembatkan dari majumapan
//                              "harga1"      => "jual", // dpp
//                              "disc"        => "akumDisc",
//                              "nett1" => "lastNett",
//                              "ppn"         => "(nett1*(10/100))",
//                              "nett2" => "(nett1+ppn)",
//                "subtotal" => "jml*nett1",
//                "subtotal" => "jml*nett2",


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
            "master_dependent" => array(
                "paymentMethod" => array(
                    "cash" => array(
                        // "nilai_cash" => "tagihan",
                        // "nilai_credit" => "0",
                        // bila ada dp untuk instansi
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "cia" => array(
                        "nilai_cash" => "0",
                        //                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "credit" => array(
                        "nilai_credit" => "tagihan",
                        "nilai_cash" => "0",
                    ),
                    "credit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
                    "debit_card" => array(
                        "nilai_cash" => "0",
                        "nilai_credit" => "tagihan",
                    ),
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
        //supaya gerbang tidak direcap ke main
        "recapValueException" => array(
            "items2_sum",
        ),
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
        "valueBuilders" => array(
            "harga_items3"=>"nett1-diskon_pembulatan",
            "harga_nppn" => "harga_items2+ppn_items2",
            "harga_non_ppn" => "harga_items3",
//            "harga_nppn" => "harga_items2+ppn_items2",
            "shipsvc_ppn_value" => "(shipping_service*ppnFactor/100)",
            "dp_value" => "(dp*100)/(100+ppnFactor)",
            "dp_ppn_value" => "dp_value*(ppnFactor/100)",
            "shipping_service_amount" => "(shipping_service+shipsvc_ppn_value)",
            "grand_total" => "nett1+install_tax+install+ongkir",
            "grand_ppn" => "ongkir_ppn+ppn",
            "new_grand_ppn" => "grand_ppn-dp_ppn_value",
            "new_net1" => "nett1+ongkir",
            "new_net2" => "nett2+ongkir",
//            "new_net2" => "nett2+ongkir+ongkir_ppn",
            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
            "tagihan" => "nett1_bulat+ppn_out_bulat-dp-nilai_cia",
//            "grand_total_ui" => "nett1+install_tax+install+ongkir",
//            "tagihan_ui" => "nett1+install_tax+install+ongkir+grand_ppn-dp-nilai_cia",
//            "grand_net" => "new_net3-nilai_dipakai_ppn_out",
            "grand_total_ui" => "harga_non_ppn", // ori
//            "grand_total_ui" => "harga_jual_project",
//            "tagihan_ui" => "harga_non_ppn+grand_ppn",
//            "grand_net" => "new_net3-nilai_dipakai_ppn_out",

            "garansi_nilai" => "(tarifGaransi/100)*grand_total_ui",// ini menjadi retensi
            "garansi_nilai_ppn" => "garansi_nilai/ppnFactor",// ini menjadi retensi ppn
            "piutang_retensi" => "garansi_nilai_ppn+garansi_nilai",

            "penjualan_retensi" => "projectHarga-garansi_nilai",
            "penjualan_ppn_retensi" => "penjualan_retensi/ppnFactor",
            "piutang_dagang" => "penjualan_retensi+penjualan_ppn_retensi",

            // persiapan gerbang uangmuka konsumen/hutang ke konsumen yang dipakai pada project ini
            "hutang_ke_konsumen" => "pym_src_dipakai",// hutang ke konsumen
            "penjualan" => "pym_src_dipakai",// penjualan, rek pembantu penjualan project
            "ppn_keluaran_belum_faktur" => ".0",// ppn keluaran belum faktur
            "hutang_ke_konsumen_nppn" => "hutang_ke_konsumen*((100+ppnFactor)/100)",// hutang ke konsumen + ppn
            "harga_jual_project_nppn" => "harga_jual_project_ppn+harga_jual_project",
        ),
        "valueBuilders_rsltItems" => array(
            //            "ppv"         => "sub_ppv",
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

            //            "grand_hutang" => "",

        ),
        "valueInjectorBulat" => array(
            "source" => "grand_total_ui",
            "injectTo" => array(
                "hasil" => "nett1_bulat",
                "hasil_child" => "ppn_out_bulat",
                "pembulatan" => "nilai_pembulatan",
                "hasil_total" => "grand_pembulatan",
            ),
        ),
        "externalValues" => array(),
        "preValidator" => array(),
        "preProcessor" => array(
//            "588so" => array( // dijalankan bila mendapatkan target 582so, (bila mendapatkan optTarget 582spod tidak dijalankan)
//                "master" => array(
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
//                            "nilai" => "new_net3-nilai_dipakai_ppn_out", //ini aslinya... akan aktif bila ada dp (untuk instansi), dan cia
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
//                            "jenis" => ".ppn out",
//                            "produk_id" => "pihakID",
//                            "nama" => "pihakName",
//                            //                            "nilai" => "ppn",
//                            "nilai" => "grand_ppn",// aslinya...
//                            //                            "nilai" => "ppn_out_bulat",
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
//                "detail" => array(),
//            ),
//            "588spd" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "FifoAverage",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "items2_sum" => array(
//                                "hpp" => "hpp",
//                                "hpp_riil" => "hpp_riil",
//                                "ppv_riil" => "ppv_riil",
//                                "ppn_in" => "ppn_in",
//                                "ppn_in_nilai" => "ppn_in_nilai",
//                                "suppliers_id" => "suppliers_id",
//                                "suppliers_nama" => "suppliers_nama",
//                            ),
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
//                    ),
//                    array(
//                        "comName" => "FifoProdukJadi",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "produk_qty" => "jml",
//                            "gudang_id" => "gudangID",
//                        ),
//                        "resultParams" => array(
//                            "rsltItems2" => array(
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
//                            ),
//                        ),
//                        "srcGateName" => "items2_sum",
//                        "srcRawGateName" => "items2_sum",
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
//                ),
//            ),
            "588spo" => array(
                "master" => array(),
                "detail" => array(
                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProject",
                        "loop" => array(
                            "project" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "cabangID",
                            "cabang_nama" => "cabangName",
                            "nama" => "nama",
                            "kode" => "produk_kode",
                            "start_dtime" => "tanggalStart",
                            "end_dtime" => "tenggatWaktu",
                            "harga" => "harga",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
                            "spek" => "note",
                            "projectID"=>"projectID",
                            "jenis" => "jenisTr",
                            "create_by_id" => "olehID",
                            "create_by_name" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                ),
            ),
            "588st" => array(
                "master" => array(
//                    array(
//                        "comName" => "PaymentSourceUmProject",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
////                            "jenis" => ".2010050",// hutang ke konsumen
//                            "label" => ".uang muka konsumen",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => "referenceID__2",// transaksi id referensi so project
//                            "extern2_nama" => "referenceNomer__2",// transaksi nomer referensi so project
////                            "extern5_id" => "projectID",// id project
////                            "extern5_nama" => "projectName",// nama project
//                            "nilai" => "grandTotal",
////                            "force" => ".true",
////                            "payment_method" => "paymentMethod",
////                            "tipe_penjualan" => "tipe_penjualan",
////                            "tipe_penjualan_coa" => ".1010020090",
//                        ),
//                        "resultParams" => array(
//                            "main" => array(
//                                "pym_src_dipakai" => "pym_src_dipakai",
//                                "pym_src_ppn_dipakai" => "pym_src_ppn_dipakai",
//                                "pym_src_total_dipakai" => "pym_src_total_dipakai",
//                                "pym_src_tambah" => "pym_src_tambah",
//                                "pym_src_tambah_ui" => "pym_src_tambah_ui",
//                            ),
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

                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
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
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
            ),
            "sub_detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "nama",
                "produk_ord_jml" => "jml",
                "valid_qty" => "jml",
                "produk_ord_hrg" => "harga",
                "satuan" => "satuan",
            ),
            "sub_detail_items" => array(
                "dtime" => "dtime",
                "produk_id" => "produk_id",
                "produk_label" => "label",
                "produk_nama" => "produk_nama",
                "produk_ord_jml" => "produk_ord_jml",
                "valid_qty" => "valid_qty",
                "produk_ord_hrg" => "produk_ord_harga",
                "produk_jenis" => "produk_jenis",
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
                //
                //                "produk_berat_gross"   => "berat_gross",
                //                "produk_volume_gross"  => "volume_gross",
                //                "tinggi_gross"  => "tinggi_gross",
                //                "panjang_gross" => "panjang_gross",
                //                "lebar_gross"   => "lebar_gross",
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
        "valuePembulatan" => array(
            4 => array(
                "source" => "new_net1",
                "replacer" => array(
                    "hasil" => "nett1_bulat",
                    "hasil_child" => "ppn_out_bulat",
                    "pembulatan" => "nilai_pembulatan",
                    "hasil_total" => "grand_pembulatan",
                    "new_tagihan" => "nilai_credit",

                ),
            ),
        ),
        "components" => array(
            "588st" => array(
                "master" => array(
                    // region jurnal kontijensi
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010070030" => "grandTotal", // piutang usaha kontijensi
                            "4030" => "grandTotal", // penjualan kontijensi
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
                            "1010070030" => "grandTotal", // piutang usaha kontijensi
                            "4030" => "grandTotal", // penjualan kontijensi
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
                            "1010070030" => "grandTotal",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010070030" => "grandTotal",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "projectID",//project
                            "extern2_nama" => "projectName",//project
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4030" => "grandTotal",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4030030",
                            "extern_nama" => ".penjualan kontijensi project",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4030" => "grandTotal",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",//project
                            "extern_nama" => "projectName",//project
                            "extern2_id" => ".4030030",
                            "extern2_nama" => ".penjualan kontijensi project",
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // endregion jurnal kontijensi

                    //region uang muka konsumen diakui

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
////                            "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
//                            "4010" => "penjualan",// penjualan, rek pembantu penjualan project
//                            "2030060" => "ppn_keluaran_belum_faktur",// ppn keluaran belum faktur
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
////                            "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
//                            "4010" => "penjualan",// penjualan, rek pembantu penjualan project
//                            "2030060" => "ppn_keluaran_belum_faktur",// ppn keluaran belum faktur
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
//                        "comName" => "RekeningPembantuPenjualan",
//                        "loop" => array(
//                            "4010" => "penjualan",// penjualan, rek pembantu penjualan project
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4010030",
//                            "extern_nama" => ".penjualan project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPenjualanProject",
//                        "loop" => array(
//                            "4010" => "penjualan",// penjualan, rek pembantu penjualan project
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "projectID",//project
//                            "extern_nama" => "projectName",//project
//                            "extern2_id" => ".4010030",
//                            "extern2_nama" => ".penjualan project",
//                            "extern3_id" => ".0",//kontrak
//                            "extern3_nama" => "note",//kontrak
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

//pindah ke penerimaan project (termin)
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".2010050060",
//                            "extern_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomerDetail",
//                        "loop" => array(
//                            "2010050" => "-hutang_ke_konsumen",// hutang ke konsumen
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => ".2010050060",
//                            "extern2_nama" => ".Uang Muka Konsumen",
//                            "jenis" => "jenisTr",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                    //endregion uang muka konsumen diakui

                    // region piutang kontijensi dan penjualan kontijensi berkurang

//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010070030" => "-hutang_ke_konsumen_nppn", // piutang usaha kontijensi
//                            "4030" => "-hutang_ke_konsumen_nppn", // penjualan kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Rekening",
//                        "loop" => array(
//                            "1010070030" => "-hutang_ke_konsumen_nppn", // piutang usaha kontijensi
//                            "4030" => "-hutang_ke_konsumen_nppn", // penjualan kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "1010070030" => "-hutang_ke_konsumen_nppn",// piutang dagang kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomerProject",
//                        "loop" => array(
//                            "1010070030" => "-hutang_ke_konsumen_nppn",// piutang dagang kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => "projectID",//project
//                            "extern2_nama" => "projectName",//project
//                            "extern3_id" => ".0",//kontrak
//                            "extern3_nama" => "note",//kontrak
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPenjualan",
//                        "loop" => array(
//                            "4030" => "-hutang_ke_konsumen_nppn",// piutang dagang kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4030030",
//                            "extern_nama" => ".penjualan kontijensi project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPenjualanProject",
//                        "loop" => array(
//                            "4030" => "-hutang_ke_konsumen_nppn",// piutang dagang kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "projectID",//project
//                            "extern_nama" => "projectName",//project
//                            "extern2_id" => ".4030030",
//                            "extern2_nama" => ".penjualan kontijensi project",
//                            "extern3_id" => ".0",//kontrak
//                            "extern3_nama" => "note",//kontrak
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),

                    // endregion piutang kontijensi dan penjualan kontijensi berkurang

//                    // region berhubung belum ada transaksi closing/serahterima project maka piutang retensi dibuat disini
//                    array(
//                        "comName" => "Jurnal",
//                        "loop" => array(
//                            "1010020060" => "piutang_retensi", // piutang retensi
//                            "4010" => "garansi_nilai", // penjualan kontijensi
//                            "2030060" => "garansi_nilai_ppn",// ppn keluaran belum faktur
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
//                            "1010020060" => "piutang_retensi", // piutang retensi
//                            "4010" => "garansi_nilai", // penjualan kontijensi
//                            "2030060" => "garansi_nilai_ppn",// ppn keluaran belum faktur
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
//                        "comName" => "RekeningPembantuCustomer",
//                        "loop" => array(
//                            "1010020060" => "piutang_retensi", // piutang retensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuCustomerProject",
//                        "loop" => array(
//                            "1010020060" => "piutang_retensi", // piutang retensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "extern2_id" => "projectID",//project
//                            "extern2_nama" => "projectName",//project
//                            "extern3_id" => ".0",//kontrak
//                            "extern3_nama" => "note",//kontrak
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPenjualan",
//                        "loop" => array(
//                            "4010" => "garansi_nilai", // penjualan kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => ".4010030",
//                            "extern_nama" => ".penjualan project",
//                            "extern2_id" => ".0",
//                            "extern2_nama" => "",
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "RekeningPembantuPenjualanProject",
//                        "loop" => array(
//                            "4010" => "garansi_nilai", // penjualan kontijensi
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "projectID",//project
//                            "extern_nama" => "projectName",//project
//                            "extern2_id" => ".4010030",
//                            "extern2_nama" => ".penjualan project",
//                            "extern3_id" => ".0",//kontrak
//                            "extern3_nama" => "note",//kontrak
//                            "extern4_id" => "pihakID",
//                            "extern4_nama" => "pihakName",
//                            "jenis" => "jenisTr",
////                        "transaksi_no" => "nomer",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    // endregion berhubung belum ada transaksi closing/serahterima project maka piutang retensi dibuat disini
                ),
                "detail" => array(),
            ),
            "588sta" => array(
                "master" => array(
                    // region berhubung belum ada transaksi closing/serahterima project maka piutang retensi dibuat disini
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010020060" => "piutang_retensi", // piutang retensi
                            "4010" => "garansi_nilai", // penjualan kontijensi
                            "2030060" => "garansi_nilai_ppn",// ppn keluaran belum faktur
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
                            "1010020060" => "piutang_retensi", // piutang retensi
                            "4010" => "garansi_nilai", // penjualan kontijensi
                            "2030060" => "garansi_nilai_ppn",// ppn keluaran belum faktur
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
                            "1010020060" => "piutang_retensi", // piutang retensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010020060" => "piutang_retensi", // piutang retensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "projectID",//project
                            "extern2_nama" => "projectName",//project
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4010" => "garansi_nilai", // penjualan kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4010030",
                            "extern_nama" => ".penjualan project",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4010" => "garansi_nilai", // penjualan kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",//project
                            "extern_nama" => "projectName",//project
                            "extern2_id" => ".4010030",
                            "extern2_nama" => ".penjualan project",
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),

                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010070030" => "-piutang_retensi", // piutang usaha kontijensi
                            "4030" => "-piutang_retensi", // penjualan kontijensi
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
                            "1010070030" => "-piutang_retensi", // piutang usaha kontijensi
                            "4030" => "-piutang_retensi", // penjualan kontijensi
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
                            "1010070030" => "-piutang_retensi",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010070030" => "-piutang_retensi",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "extern2_id" => "projectID",//project
                            "extern2_nama" => "projectName",//project
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4030" => "-piutang_retensi",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => ".4030030",
                            "extern_nama" => ".penjualan kontijensi project",
                            "extern2_id" => ".0",
                            "extern2_nama" => "",
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4030" => "-piutang_retensi",// piutang dagang kontijensi
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "projectID",//project
                            "extern_nama" => "projectName",//project
                            "extern2_id" => ".4030030",
                            "extern2_nama" => ".penjualan kontijensi project",
                            "extern3_id" => ".0",//kontrak
                            "extern3_nama" => "note",//kontrak
                            "extern4_id" => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // endregion berhubung belum ada transaksi closing/serahterima project maka piutang retensi dibuat disini
                ),
                "detail" => array(),
            ),
        ),
        "postProcessor" => array(
            "588spo" => array(
                "master" => array(),
                "detail" => array(

                    // mutasi project
                    array(
                        "comName" => "TransaksiProjectItem",
                        "loop" => array(
                            "project" => "harga",
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "extern_id" => "id",
                            "extern_nama" => "nama",
                            "transaksi_id" => "transaksi_id",
                            "transaksi_no" => "nomer",
                            "terbayar" => "harga",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
//                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProjectUpdate",
                        "loop" => array(
                            "project" => "harga",
                        ),
                        "static" => array(
                            "nama" => "nama",
//                            "kode" => "produk_kode",
//                            "start_dtime" => "tanggalStart",
//                            "end_dtime" => "tenggatWaktu",
//                            "harga" => "harga",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
//                            "spek" => "note",
                            "jenis" => "jenisTr",
                            "project_id" => "id",// id project

                            "create_by_id" => "olehID",
                            "create_by_name" => "olehName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "ProdukProjectItems3",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".termin",
                            "persen" => "persen",
                            "progress" => "progress",
                            "harga_project" => "harga_project",
                            "jumlah" => "harga",
                            "produk_id" => "urut",
                            "nama" => "nama",
                        ),
                        "srcGateName" => "items3",
                        "srcRawGateName" => "items3",
                    ),
                    array(
                        "comName" => "ProdukProjectItems4",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".uangmuka",
                            "harga_project" => "harga_project",
                            "persen" => "persen",
                            "jumlah" => "harga",
                            "keterangan_dp" => "keterangan_dp",
                        ),
                        "srcGateName" => "items4",
                        "srcRawGateName" => "items4",
                    ),

                    array(
                        "comName" => "ProdukProjectItems5",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".garansi",
                            "harga_project" => "harga_project",
                            "persen" => "persen",
                            "jumlah" => "harga",
                            "tgl_akhir_garansi" => "tgl_akhir_garansi",
                            "keterangan_garansi" => "keterangan_garansi",
                        ),
                        "srcGateName" => "items5",
                        "srcRawGateName" => "items5",
                    ),

                    array(
                        "comName" => "ProdukProjectItems7",
                        "loop" => array(),
                        "static" => array(
                            "jenis" => ".kontrak",
                            "ref_kontrak" => "ref_kontrak",
                            "tgl_kontrak" => "tgl_kontrak",
                            "tgl_mulai_project" => "tgl_mulai_project",
                            "tgl_akhir_project" => "tgl_akhir_project",
                            "notes_project" => "notes_project",
                        ),
                        "srcGateName" => "items7",
                        "srcRawGateName" => "items7",
                    ),
                ),
            ),
            "588so" => array(
                "master" => array(
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
//
//                    array(
//                        "comName" => "Jurnal_activity",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
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
//                            "step_number" => ".4",
//                            //                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                    array(
//                        "comName" => "Jurnal_activityMain",
//                        "loop" => array(
//                            "activity" => ".1",
//                        ),
//                        "static" => array(
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
//                            "step_number" => ".4",
//                            //                            "step_number" => "step_number",
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
                ),
                "detail" => array(
//                    // mutasi project
//                    array(
//                        "comName" => "TransaksiProjectItem",
//                        "loop" => array(
//                            "project" => "harga",
//                        ),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
//                            "extern_id" => "id",
//                            "extern_nama" => "nama",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_no" => "nomer",
//                            "terbayar" => "harga",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                    // data project yang sudah diapprove
//                    array(
//                        "comName" => "ProdukProject",
//                        "loop" => array(
//                            "project" => "harga",
//                        ),
//                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
////                            "extern_id" => "id",
////                            "extern_nama" => "nama",
//                            "nama" => "nama",
//                            "kode" => "produk_kode",
////                            "transaksi_id" => "transaksi_id",
////                            "transaksi_no" => "nomer",
//                            "start_dtime" => "tanggalStart",
//                            "end_dtime" => "tenggatWaktu",
//                            "harga" => "harga",
//                            "customer_id" => "customerID",
//                            "customer_nama" => "customerName",
//                            "spek" => "note",
//                            "jenis" => "jenisTr",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),

                    array(
                        "comName" => "TransaksiProduk",
                        "loop" => array(
                            "582so" => "-qty",
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

                        ),
                        "reversable" => true,
                        "srcGateName" => "items7_sum",
                        "srcRawGateName" => "items7_sum",
                    ),

                ),
            ),
            "588st" => array(
                // mengurangi payment source dp project
                "master" => array(
//                    array(
//                        "comName" => "PaymentSourceReferenceMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "cabang_id" => "placeID",
//                            "cabang_nama" => "placeName",
////                            "transaksi_id" => "uangMuka__transaksi_id",
////                            "jenis" => "uangMuka__jenis",
//                            "extern_id" => "pihakID",
//                            "extern_nama" => "pihakName",
//                            "label" => ".uang muka konsumen",
////                            "tambah" => "deposit_konsumen",
////                            "extern_label2" => ".customer",//ini update untuk pembeda vemdor/ customer
//                            "extern2_id" => "referenceID__2",// transaksi id referensi so project
//                            "extern2_nama" => "referenceNomer__2",// transaksi nomer referensi so project
//                            "referensi_po_id" => "referenceID__2",// transaksi id referensi so project
//                            "terbayar" => "hutang_ke_konsumen",
//                            "gateSource" => ".main",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
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
        //nulis/udpate produk project di update dengan transaksi ID  588spo dan nomernya
        "postUpdaterMisc" => array(
//            "588spo" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".open"
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".update",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                        "revert" => true,
//                    ),
//                ),
//            ),
//            "588" => array(
//                "master" => array(
//                    array(
//                        "comName" => "ProjectSalesMain",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "projectID",
//                            "closing_oleh_id" => "olehID",
//                            "closing_oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".close",
//                            //open,close,,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing
//                        ),
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                        "revert" => true,
//                    ),
//                ),
//            ),
        ),

        //untuk nulis items2-sum ke table transaksi_data_details
        "writeSubDetail" => array(
//            "588spo" => true,
//            "588so" => true,
//            "588spd" => true,
//            "588" => false,
        ),
        //untuk pembalik jika didelete, reaktivasi produk project
        "postProcRevert" => array(
//            "588spo" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah di po project belum?
//                "detail" => array(
//                    array(
//                        "comName" => "ProjectSales",
//                        "loop" => array(),
//                        "static" => array(
//                            "id" => "id",
//                            "oleh_id" => "olehID",
//                            "oleh_nama" => "olehName",
//                            "jenis" => "jenisTr",
//                            "methode" => ".revert",
//                            //open,close,revert,, open untuk memulai menggunakan produk project, close untuk menutup produk project supaya tidak bisa dipakai PO dan invoicing, revert untuk reject transaksi dan mengaktifkan kembali produk projet
//
//                        ),
//                        "srcGateName" => "items",
//                        "srcRawGateName" => "items",
//                    ),
//                ),
//            ),
//            "588so" => array(
//                "master" => array(),//nanti disini dipasang untuk checker transaksi sudah dipo projek belum
//
//            ),
        ),
        //pasang difollowup untuk update subitems valid qty
        "postUpdateItems" => array(
            "588so" => array(
                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
            ),
            "588st" => array(
                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
            ),
            "588sta" => array(
                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
            ),
//            "588spd" => array(
//                "extractedSubItems" => "sub_detail",
//                "extractedSubItems2" => "items3_sum",
//            ),
//            "588" => array(//dipasang langsung di kontroller karena menggunakan mode multi
//            ),
            "588" => array(
                "extractedItems" => "detail",
//                "extractedSubItems" => "sub_detail",
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

    // uang muka project
    "4469" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakID",
                "customerName" => "pihakName",
            ),
            "detail" => array(//                "nett" => "nilai_entry",
            ),
        ),
        "valueBuilders" => array(
            "nett" => "nilai_entry",
            "nilai_bayar" => "nilai_entry",
        ),
        "valuePopulator" => array(
            "valueSrc" => "nilai_bayar",
            "acuanSrc" => ".sisa",
        ),

        "populators" => array(
            "nilai_bayar" => array(
                "mainSrc" => array(
                    "key" => "nilai_bayar",
                ),
                "itemTarget" => array(
                    "key" => "nilai_bayar",
                    "maxAmountSrc" => "sisa",
                ),
            ),
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
                "produk_jenis" => "uangmuka",
            ),
        ),
        "components" => array(
            "4469" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010010010" => "nett",// kas
                            "2010050" => "nett",// hutang ke konsumen
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
                            "1010010010" => "nett",// kas
                            "2010050" => "nett",// hutang ke konsumen
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
                            "1010010010" => "nett",// kas
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "cash_account",
                            "extern_nama" => "cash_account__label",
                            "jenis" => "jenisTr",
                            // "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "2010050" => "nett",// hutang ke konsumen
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
//            "4469r" => array(
//                "master" => array(
//                    // data project yang sudah diapprove
//                    array(
//                        "comName" => "ProdukProjectUangMuka",
//                        "loop" => array(
//                            "project" => "harga",
//                        ),
//                        "static" => array(
////                            "cabang_id" => "placeID",
////                            "cabang_nama" => "placeName",
//                            "id" => "projectDetails",
//                            "nama" => "projectDetails__nama",
//                            "kode" => "produk_kode",
//                            "uang_muka_request" => "harga",
//                            "customer_id" => "customerID",
//                            "customer_nama" => "customerName",
//                        ),
//                        "reversable" => true,
//                        "srcGateName" => "main",
//                        "srcRawGateName" => "main",
//                    ),
//                ),
//                "detail" => array(
//
//                ),
//            ),
            "4469" => array(
                "master" => array(
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
                            "nilai" => "nett", // nilai_entry
                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // payment source uang muka (cache dan mutasi)
                    array(
                        "comName" => "PaymentUangMukaCustomer",
                        "loop" => array(
                            "2010050" => "nett",// hutang ke konsumen
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "cabang_nama" => "placeName",
                            "gudang_id" => ".0",
                            "extern_id" => "pihakID",
                            "extern_nama" => "pihakName",
                            "nilai" => "nett",
                            "label" => ".uang muka",
                            "extern_label2" => ".customer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                    // data project yang sudah diapprove
                    array(
                        "comName" => "ProdukProjectUangMuka",
                        "loop" => array(
                            "project" => "nett",
                        ),
                        "static" => array(
                            "id" => "projectDetails",
                            "nama" => "projectDetails__nama",
                            "kode" => "produk_kode",
                            "uang_muka_approved" => "nett",
                            "customer_id" => "customerID",
                            "customer_nama" => "customerName",
                        ),
                        "reversable" => true,
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),
                ),
                "detail" => array(
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".uang muka",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "terbayar" => "nilai_bayar",
                            "sisa" => "new_sisa",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    array(
                        "comName" => "PaymentSrcItem",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "extern_id" => "id",
                            "extern_nama" => "name",
                            "label" => ".uang muka",
                            "target_jenis" => "jenisTr",
                            "transaksi_id" => "refID",
                            "dihapus" => "new_sisa",
                            "sisa" => ".0",
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

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    "5886" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakProjekID",
                "customerName" => "pihakProjekName",
                "cabang2ID" => "cabang2",
                "cabang2Name" => "cabang2__nama",
                "place2ID" => "cabang2",
                "place2Name" => "cabang2__nama",
                "gudang2ID" => "gudang2",
                "gudang2Name" => "gudang2__label",
            ),
            "detail" => array(//                "nett" => "nilai_entry",
            ),
        ),
        "valueBuilders" => array(
            "biaya_project" => "harga",
//            "nilai_bayar" => "nilai_entry",
        ),
        "valuePopulator" => array(
//            "valueSrc" => "nilai_bayar",
//            "acuanSrc" => ".sisa",
        ),

        "populators" => array(
//            "nilai_bayar" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_bayar",
//                ),
//                "itemTarget" => array(
//                    "key" => "nilai_bayar",
//                    "maxAmountSrc" => "sisa",
//                ),
//            ),
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
                "customers_id" => "customerID",
                "customers_nama" => "customerName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "cabang2_id" => "place2ID",
                "cabang2_nama" => "place2Name",
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
            ),
        ),
        "tableIn_static" => array(
            "master" => array(
                "trash" => 0,
            ),
            "detail" => array(
                "trash" => 0,
                "produk_jenis" => "biaya project",
            ),
        ),
        "components" => array(),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
    "5887" => array(
        "counters" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",
        ),
        "formatNota" => "stepCode|placeID",
        "valueGates" => array(//==sumber nilai yang dikirim kemana2
            "master" => array(//==sumber nilai utama
                "customerID" => "pihakProjekID",
                "customerName" => "pihakProjekName",
            ),
            "detail" => array(//                "nett" => "nilai_entry",
            ),
        ),
        "valueBuilders" => array(
            "biaya_project" => "harga",
//            "nilai_bayar" => "nilai_entry",
        ),
        "valuePopulator" => array(
//            "valueSrc" => "nilai_bayar",
//            "acuanSrc" => ".sisa",
        ),
        "populators" => array(
//            "nilai_bayar" => array(
//                "mainSrc" => array(
//                    "key" => "nilai_bayar",
//                ),
//                "itemTarget" => array(
//                    "key" => "nilai_bayar",
//                    "maxAmountSrc" => "sisa",
//                ),
//            ),
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
                "produk_jenis" => "biaya project",
            ),
        ),
        "components" => array(
            "5887" => array(
                "master" => array(
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "5020060" => "biaya_project",//biaya usaha
                            "2010040" => "biaya_project",//hutang biaya
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
                            "5020060" => "biaya_project",//biaya usaha
                            "2010040" => "biaya_project",//hutang biaya
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
                            "2010040" => "biaya_project",//hutang biaya
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "placeID",
                            "extern_nama" => "placeName",
                            "jenis" => "jenisTr",
                            "transaksi_no" => "nomer",
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main",
                    ),


                ),
                "detail" => array(
                    array(
                        "comName" => "RekeningPembantuHppProjectBiayaLain",
                        "loop" => array(
                            "5020060" => "harga",//biaya usaha
                        ),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "extern_id" => "projectID",
                            "extern_nama" => "projectName",
                            "jenis" => "jenisTr",
                        ),
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                    //endregion
                ),
            ),
        ),
        "postProcessor" => array(),
        //-----
        "countersEdit" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaEdit" => "stepCode|placeID",
        "countersReject" => array(
            "stepCode|placeID",
            "stepCode|olehID",
            "stepCode|placeID|olehID",
            "stepCode|customerID",

            "stepCode|masterID",
            "stepCode|masterID|placeID",
            "stepCode|masterID|olehID",
            "stepCode|masterID|placeID|olehID",
            "stepCode|masterID|customerID",
        ),
        "formatNotaReject" => "stepCode|placeID",
    ),
);