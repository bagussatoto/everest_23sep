<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 7:18 PM
 */

$config["heBi"] = array(
    "umum"      => array(
        "exe_tgl"   => array(
            "label"   => "schedule date",
            "minimal" => "1",
            "type"    => "date",
        ),
        "exe_jam"   => array(
            "label" => "time",
            "type"  => "time",
        ),
        "exe_email" => array(
            "label" => "email",
            "type"  => "email",
        ),
    ),
    "pembelian" => array(
        "produk"   => array(
            "setting"      => array(
                "indeks"    => array(
                    "label"   => "index (%)",
                    "minimal" => "100",
                    "default" => "100",
                    // "formula" => "9",
                ),
                "periode"   => array(
                    "label"   => "omset",
                    "minimal" => "1",
                    "default" => "6",
                    // "formula" => "9",
                ),
                "limitTime" => array(
                    "label"   => "buffer sett (m)",
                    "minimal" => "1",
                    "default" => "3",
                    // "formula" => "9",
                ),
                "moqTime"   => array(
                    "label"   => "moq sett (m)",
                    "minimal" => "1",
                    "default" => "1",
                    // "formula" => "9",
                ),
                "leadTime"  => array(
                    "label"   => "stock sett (m)",
                    "minimal" => "1",
                    "default" => "6",
                    // "formula" => "9",
                ),
            ),
            "headerField" => array(
                "avg"        => array(
                    "label" => "monthly avg",
                    "formula" => "omset / month set omset",
                ),
                "limitTime"  => array(
                    "label" => "month set",
                ),
                "limit"      => array(
                    "label"   => "buffer unit",
                    "formula" => "monthly avg x month set buffer",
                ),
                // "mogTime"    => array(
                //     "label" => "month set",
                // ),
                "moq"        => array(
                    "label"   => "unit moq",
                    "formula" => "satuan terkecil yang dapat diorder",
                ),
                "indek"      => array(
                    "label" => "index",
                ),
                "persediaan" => array(
                    "label" => "available stock",
                ),
                "leadTime"   => array(
                    "label" => "month",
                ),
                "idealStok"  => array(
                    "label"   => "ideal stock unit",
                    "formula" => "{(monthly avg x ideal stock month) x index} + buffer",
                ),
                "order"      => array(
                    "label"   => "order qty",
                    "formula" => "ideal stock - available stock",
                ),
            )
        ),
        "supplies" => array(),
    ),
);