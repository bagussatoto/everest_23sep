<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Konfigurasi UI untuk form Amandemen Invoice
 * COMPLIANCE: Standar UI CodeIgniter HMVC ERP
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$config["coTransaksiUi"] = array(
    "4822a" => array(
        "icon" => "fa fa-pencil-square-o",
        "label" => "Amandemen Invoice",
        "place" => "center",
        "counter_global" => "_company_cabangID_jenisTr",
        "counter_global_part" => array(
            'jenis',
            "dtime",
            'cabangID',
            'customerID',
            '_company_cabangID_modul_subModul_jenisTr_customerID',
        ),
        "steps" => array(
            1 => array(
                "label" => "Amandemen Draf",
                "actionLabel" => "Simpan Draf Amandemen",
                "source" => "",
                "target" => "4822ar",
                "userGroup" => "c_finance",
                "stateLabel" => "draft",
                "stateColor" => "#dd3300",
                "stateCaption" => "prepared by",
            ),
            2 => array(
                "label" => "Eksekusi Amandemen",
                "actionLabel" => "Eksekusi & Simpan Amandemen",
                "source" => "4822ar",
                "target" => "4822a",
                "userGroup" => "c_finance_spv",
                "stateLabel" => "amended",
                "stateColor" => "#009900",
                "stateCaption" => "approved by",
                "allowEdit" => true,
            ),
        ),
    )
);
