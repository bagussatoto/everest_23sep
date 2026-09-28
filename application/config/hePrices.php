<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 4:12 PM
 */
$config['hePrices'] = array(
    "produk" => array(
        "hpp" => array(
            // "label" => "cost (dealing)",// direvisi tgl 21/5/2020 menjadi last purchase price  nyontoh SAP . notes WAG
            "label" => "last purchase price",
            "srcType" => "normal",
            "editable" => false,
            "purchase" => false,
        ),
        "hpp_nppv" => array(
//            "label" => "hpp",
            "label" => "harga tandas*",
            "srcType" => "normal",
            "linkHistory" => false,
            "editable" => false,
//            "editable" => true,
        ),
        "hpp_nppv_sales" => array(
            "label" => "hpp sales",
            "srcType" => "normal",
            // "linkHistory" => false,
            // "editable" => false,
            "purchase" => false,
        ),
        "jual" => array(
            "label" => "harga jual sekarang",
            "srcType" => "normal",
        ),
//        "jual_online" => array(
//            "label" => "harga jual online",
//            "srcType" => "normal",
//        ),
        "jual_nppn" => array(
            "label" => "jual+ppn (auto)",
//            "srcType" => "normal",
//            "srcSrc"  => "jual_nppn",
//            "srcSrc"  => "((jual_nppn*100)/(100+10))",
            "srcType" => "formula",
            "srcVal" => "(jual*(111/100))",
            "purchase" => false,
        ),
        "disc" => array(
            "label" => "disc (%)",
            "srcType" => "percentage",
            "srcSrc" => "jual",
            "purchase" => false,
        ),
        "disc_percent" => array(
            "label" => "disc (%)",
            "srcType" => "normal",
            //            "srcType" => "instantFormula",
            //            "srcSrc"  => "((disc/hpp_nppn)*100)",
            //            "rawJsFunct"  => "((disc/hpp_nppn)*100)",
            "keyUpEvent" => "document.getElementById('{disc}').value=((parseFloat(this.value)*parseFloat(document.getElementById('{jual}').value))/100)",
            "purchase" => false,
        ),
        "disc" => array(
            "label" => "disc (IDR)",
            "srcType" => "normal",
            //            "srcType" => "instantFormula",
            //            "srcSrc"  => "((hpp_nppn*disc_percent)/100)",
            "keyUpEvent" => "document.getElementById('{disc_percent}').value=((parseFloat(this.value)/parseFloat(document.getElementById('{jual}').value))*100)",
            "purchase" => false,
        ),
    ),
    "supplies" => array(
        "hpp" => array(
            "label" => "hpp",
            "srcType" => "normal",
        ),
        "jual" => array(
            "label" => "jual",
            "srcType" => "normal",
        ),
    ),
    "produkRakitan" => array(
        "hpp"  => array(
            "label"   => "hpp",
            "srcType" => "normal",
        ),
        "jual" => array(
            "label" => "jual",
            "srcType" => "normal",
        ),
        "jual_nppn" => array(
            "label" => "jual+ppn (auto)",
            "srcType" => "normal",
//            "srcSrc"  => "jual_nppn",
//            "srcSrc"  => "((jual_nppn*100)/(100+10))",
//            "srcType" => "formula",
            "srcVal" => "(jual*(111/100))",
        ),
    ),
    "produkKomposit" => array(
        "hpp"  => array(
            "label"   => "hpp",
            "srcType" => "normal",
        ),
        "jual" => array(
            "label" => "jual",
            "srcType" => "normal",
        ),
        "jual_nppn" => array(
            "label" => "jual+ppn (auto)",
            "srcType" => "normal",
//            "srcSrc"  => "jual_nppn",
//            "srcSrc"  => "((jual_nppn*100)/(100+10))",
//            "srcType" => "formula",
            "srcVal" => "(jual*(111/100))",
        ),
    ),
    "produkPaket" => array(
//        "hpp"  => array(
//            "label"   => "hpp",
//            "srcType" => "normal",
//        ),
        "jual" => array(
            "label" => "jual",
            "srcType" => "normal",
        ),
        "jual_nppn" => array(
            "label" => "jual+ppn (auto)",
            "srcType" => "normal",
//            "srcSrc"  => "jual_nppn",
//            "srcSrc"  => "((jual_nppn*100)/(100+10))",
//            "srcType" => "formula",
            "srcVal" => "(jual*(111/100))",
        ),
    ),
    "produkSupplier" => array(
        "hpp" => array(
            "label" => "hpp",
            "srcType" => "normal",
        ),
    ),
);