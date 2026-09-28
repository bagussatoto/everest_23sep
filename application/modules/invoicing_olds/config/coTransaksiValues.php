<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/22/2018
 * Time: 8:38 PM
 */
$config["coTransaksiValues"] = array(

    "4822" => array(
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
                // "ppn" => "(nett1*(10/100))",
                // "nett2" => "(nett1+ppn)",
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
            //ini dimatikasn cash in advamce dan lainlain hanya jadi catatan
            //             "master_dependent" => array(
            //                 "paymentMethod" => array(
            //                     "cash" => array(
            //                         // "nilai_cash" => "tagihan",
            //                         // "nilai_credit" => "0",
            //                         // bila ada dp untuk instansi
            //                         "nilai_cash" => "0",
            //                         "nilai_credit" => "tagihan",
            //                     ),
            //                     "cia" => array(
            //                         "nilai_cash" => "tagihan",
            // //                        "nilai_cash" => "0",
            //                         "nilai_credit" => "0",
            //                     ),
            //                     "credit" => array(
            //                         "nilai_credit" => "tagihan",
            //                         "nilai_cash" => "0",
            //                     ),
            //                     "credit_card" => array(
            //                         "nilai_cash" => "tagihan",
            //                         "nilai_credit" => "0",
            //                     ),
            //                     "debit_card" => array(
            //                         "nilai_cash" => "tagihan",
            //                         "nilai_credit" => "0",
            //                     ),
            //                 ),
            //                 "shippingService" => array(
            //                     "ongkir_ppn_by_cust" => array(
            //                         "ongkir_ui" => "shipping_service",
            //                         "ongkir" => "shipping_service",
            //                         "ongkir_ppn" => "shipsvc_ppn_value",
            //                         "ongkir_net" => "shipping_service",
            //                         "srcOngkir" => "0",
            //                     ),
            //                     "ongkir_tanpa_ppn_by_cust" => array(
            //                         "ongkir_ui" => "shipping_service",
            //                         "ongkir" => "0",
            //                         "ongkir_ppn" => "0",
            //                         "ongkir_net" => "0",
            //                         "srcOngkir" => "shipping_service",
            //                     ),
            //                     "ongkir_tanpa_ppn_by_company" => array(
            //                         "ongkir_ui" => "0",
            //                         "ongkir" => "0",
            //                         "ongkir_ppn" => "0",
            //                         "ongkir_net" => "0",
            //                         "srcOngkir" => "0",
            //                     ),
            //                     "tanpa_ongkir" => array(
            //                         "ongkir_ui" => "0",
            //                         "ongkir" => "0",
            //                         "ongkir_ppn" => "0",
            //                         "ongkir_net" => "0",
            //                     ),
            //                 ),
            //             ),
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
            // "new_grand_ppn"           => "grand_ppn-dp_ppn_value",//digeser ke pair pajak supaya dihitun ulang
            "new_net1" => "(nett1+ongkir)-add_diskon",
            "new_net2" => "nett2+ongkir",
            //"new_net3" => "new_net2+ongkir_ppn",//ppn dihilangin karena akan dihitung saat invoicing
            "new_net3" => "new_net2+ongkir_ppn",
//            "tagihan" => "grand_total+grand_ppn-dp-nilai_cia",
            "tagihan" => "nett1_bulat+ppn_out_bulat-dp-nilai_cia",
            "grand_total_ui" => "(nett1+install_tax+install+ongkir)-add_diskon",
            "tagihan_ui" => "nett1+install_tax+install+ongkir+grand_ppn-dp-nilai_cia",
            "grand_net" => "new_net3-nilai_dipakai_ppn_out",
            "nett1_bulat" => "new_net1",

        ),
        "injectorPajak" => array(
            "source" => "grand_total_ui",
        ),
        "pairPajak" => array(
            "ppn" => "ppn",
            "grand_ppn" => "ppn",
            "new_grand_ppn" => "ppn",
            "dpp_ppn" => "dppPpn",
            // "grand_total_ui"=>"grandTotal",
            "grandTotal" => "grandTotal",
            "new_net3" => "grandTotal",
            // "nett1_bulat"=>"hasil",
            "ppn_out_bulat" => "ppn",
            "grand_pembulatan" => "grandTotal",
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
        "externalValues" => array(),
        "preValidator" => array(
            //            2 => array(
            //                "LockerStock",
            //            ),
        ),
        "preProcessor" => array(

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

        ),
        "postProcessor" => array(

        ),
        "extendedSteps" => array(
            "discount" => array(
                "srcKey" => "discount",
                "groupID" => "c_finance",
                "components" => array(),
            ),
        ),
    ),

);