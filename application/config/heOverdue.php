<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 11/5/2019
 * Time: 1:09 PM
 */
$config["heOverdue"] = array(
    "overdue"       => array(
        "title"      => "overdue transaksi",
        "mainFields" => array(
            "customer_id" => array(
                "label"      => "customer",
                "attrHeader" => "text-center text-grey-2",
            ),
            "data_field"  => array(
                "label"      => "transaksi",
                "attrHeader" => "text-center text-grey-2",
            ),
            "btn_action"  => array(
                "label"      => "action",
                "attrHeader" => "text-center text-grey-2",
            ),
        ),
        "dataFields" => array(
            "data_field" => array(
                "nomer_top"       => array(
                    "label"      => "sales order",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "nomer"           => array(
                    "label"      => "packing list",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "dtime"           => array(
                    "label"      => "shipment date",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "duedate_value"   => array(
                    "label"      => "duedate",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "aging"           => array(
                    "label"      => "age (day)",
                    "attrHeader" => "text-center text-grey-2",

                ),
                "over_due"        => array(
                    "label"      => "overdue (day)",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "transaksi_nilai" => array(
                    "label"      => "amount",
                    "attrHeader" => "text-center text-grey-2",
                ),
                "status"          => array(
                    "label"      => "status",
                    "attrHeader" => "text-center text-grey-2",
                ),
            ),
        ),
    ),
    "historyBypass" => array(
        "title"          => "historical overdue ",
        "mdlFields"      => array(
            "id"             => array(),
            "auth_dtime"     => array(
                "label"      => "date",
                "format"     => "formatField",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "customers_nama" => array(
                "label"      => "customer",
                // "link" => "controler/method",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "oleh_nama"      => array(
                "label"      => "approval",
                // "link" => "controler/method",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "request_nama"   => array(
                "label"      => "inisiator",
                // "link" => "controler/method",
                "attrHeader" => "class='bg-info text-center'",
            ),
            // "used_byNama" => array(
            //     "label" => "anu",
            //     // "link" => "controler/method",
            //     "attrHeader" => "class='bg-info text-center'",
            // ),

        ),
        "mdlFieldChilds" => array(
            "nomer_top"       => array(
                "label"      => "no. SO",
                "format"     => "formatField",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "nomer"           => array(
                "label"      => "no. PL",
                "format"     => "formatField",
                "attrHeader" => "class='bg-info text-center'",
            ),
            "transaksi_nilai" => array(
                "label"      => "overdue values (idr)",
                "format"     => "formatField",
                "attrHeader" => "class='bg-info text-center'",
            ),
        ),
    ),

);
