<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Konfigurasi Values (Formula matematis) untuk Amandemen Invoice
 * COMPLIANCE: Standar UI CodeIgniter HMVC ERP
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config["coTransaksiValues"] = array(
    "4822a" => array(
        "formulas" => array(
            "tagihan_total" => "SUM(items.qty * items.harga)"
        )
    )
);
