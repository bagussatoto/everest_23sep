<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Konfigurasi Layout Kolom untuk form Amandemen Invoice
 * COMPLIANCE: Standar UI CodeIgniter HMVC ERP
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config["coTransaksiLayout"] = array(
    "4822a" => array(
        "list" => array(
            "fields" => array(
                "id" => array("type" => "hidden"),
                "nomer" => array("type" => "text", "label" => "No. Amandemen"),
                "reference_nomer" => array("type" => "text", "label" => "No. Invoice Lama"),
                "dtime" => array("type" => "datetime", "label" => "Tanggal"),
                "customerName" => array("type" => "text", "label" => "Customer"),
                "tagihan_total" => array("type" => "currency", "label" => "Tagihan Baru"),
            )
        )
    )
);
