<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 4:12 PM
 */
// $domain = "http://demo.mayagrahakencana.com";
// $domain = "http://192.168.1.47";
$domain = "http://localhost";
// $folder = "debug/grosir_tmp";
$folder = "debug/ci_san";
$config['heApi'] = array(
    "webs" => array(
        // ../limit/page
        // "produks"          => "http://demo.mayagrahakencana.com/debug/ci_san/eusvc/products/asklimited/10/1",
        "produk_datas"            => "$domain/$folder/eusvc/products/asklimited/",
        "produk_jml_total"        => "$domain/$folder/eusvc/products/asktotalnumbers/",
        "produk_folders"          => "$domain/$folder/eusvc/products/seefolders/",
        "produk_folder_jml_total" => "$domain/$folder/eusvc/products/asktotalnumbersinfolder/",
        // "produk_folder_datas"     => "$domain/debug/ci_san/eusvc/products/asklimitedinfolder/33/10/1/",
        "produk_folder_datas"     => "$domain/$folder/eusvc/products/asklimitedinfolder/",
        // .../produk_id/cabang_id/harga_jenis
        "produk_harga"            => "$domain/$folder/eusvc/products/whatisprice/59/-1/jual",
        "produk_detile"           => "$domain/$folder/eusvc/products/seeitemdetail/59",
        // "produk_hargas"            => "$domain/debug/ci_san/eusvc/products/seeprices/-1/",
        "produk_hargas"           => "$domain/$folder/eusvc/products/seeprices/",
        "ipadd_showrom"           => "$domain/$folder/eusvc/LineState/whichactiveip/-1/-1/",
// $domain/debug/ci_san/eusvc/LineState/whichactiveip/-1/-1	> result: active admin ip address if any, 0 if not
        //Array
        //(
        //  [main] => Array
        //         (
        //             [customer_id] => 55
        //             [customer_name] => paijo corp
        //             [transaksi_nilai] => 560000000
        //             [olehID] => 23
        //             [olehName] => budi
        //             [pihakID] => 66
        //             [pihakName] => iswatun hasanah
        //             [placeID] => -1
        //             [placeName] => pusat
        //             [cabangID] => -1
        //             [cabangName] => pusat
        //             [gudangID] => -1
        //             [gudangName] => main warehouse
        //         )
        //
        //     [items] => Array
        //         (
        //             [59] => Array
        //                 (
        //                     [id] => 59
        //                     [nama] => item number #9
        //                     [satuan] => pcs
        //                     [jml] => 2
        //                     [harga] => 33500
        //                 )
        //
        //             [57] => Array
        //                 (
        //                     [id] => 57
        //                     [nama] => cloud number #7
        //                     [satuan] => pcs
        //                     [jml] => 5
        //                     [harga] => 42600
        //                 )
        //
        //         )
        //
        // )
        "post_shopingcart"        => "$domain/$folder/eusvc/entries/postentry/581/",

        "post_reg_customer"       => "$domain/$folder/eusvc/customers/additem/",
        // mengunakan nama & password
        "post_login"              => "$domain/$folder/eusvc/CAuth/authcheck/",
        // 10 terakhir olehID=17
        "last_transaski"          => "$domain/$folder/eusvc/entries/asklastentries/581/17/",
        // data transaksi dgn id=929
        "transaski"               => "$domain/$folder/eusvc/entries/askentrydetail/581/929",

    ),

);