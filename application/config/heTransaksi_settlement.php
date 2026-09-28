<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 4/29/2019
 * Time: 1:05 PM
 */


$config["heTransaksi_elementPairs"] = array(
    "582" => array(
        "paymentMethod" => array(
            "id" => "key",
            "label" => "labelValue"
        ),

        "paymentMethod_debit_card_cash_account" => array(
            "id" => "key",
            "label" => "labelValue"
        ),

        "paymentMethod_credit_card_cash_account" => array(
            "id" => "key",
            "label" => "labelValue"
        ),

        "paymentMethod_cash_cash_account" => array(
            "id" => "key",
            "label" => "labelValue"
        ),

    ),
);

$config["heTransaksi_identifierGroups"] = array(
    "kasir" => array(
        "paymentMethod_id" => "payment method",
        "paymentMethod_credit_card_cash_account_id" => "CC account",
        "paymentMethod_debit_card_cash_account_id" => "debit card account",
        "paymentMethod_cash_cash_account_id" => "cash account",
    ),
    "gudang0" => array(
        "produk_id" => "product",
    ),
    "gudang" => array(
        "produk_id" => "product",
    ),
    "gudang_out" => array(
        "produk_id" => "product",
    ),
    "seller" => array(
        "produk_id" => "product",
        "customer_id" => "customer",
    ),
    "seller_entry" => array(
        "produk_id" => "product",
        "customer_id" => "customer",
    ),
);
