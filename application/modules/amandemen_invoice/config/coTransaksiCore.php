<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Konfigurasi Core untuk modul Amandemen Invoice (4822a) berdasar pola coTransaksiCore 7499
 * COMPLIANCE: ISO 9001 (Audit Trail), ISO/IEC 27001
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
$config["coTransaksiCore"] = array(
    "4822a" => array(
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
        "valueGates" => array(
            "master" => array(),
            "detail" => array(
                "qty" => "jml",
                "harga" => "harga",
            )
        ),
        "components" => array(
            "4822a" => array(
                "master" => array(
                    // 0: Jurnal Umum Penyesuaian Amandemen
                    array(
                        "comName" => "Jurnal",
                        "loop" => array(
                            "1010020010" => "delta_tagihan",
                            "4010"       => "delta_dpp",
                            "6010"       => "delta_beban_pajak",
                            "2030060"    => "delta_ppn",
                            "4030"       => "delta_tagihan",
                            "1010070030" => "-delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis"     => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 1: Buku Besar Rekening Utama (Update _rek_master_cache & __rek_master_*)
                    array(
                        "comName" => "Rekening",
                        "loop" => array(
                            "1010020010" => "delta_tagihan",
                            "4010"       => "delta_dpp",
                            "6010"       => "delta_beban_pajak",
                            "2030060"    => "delta_ppn",
                            "4030"       => "delta_tagihan",
                            "1010070030" => "-delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id" => "placeID",
                            "jenis"     => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 2: Buku Pembantu Piutang Konsumen (Update _rek_pembantu_customer_cache)
                    array(
                        "comName" => "RekeningPembantuCustomer",
                        "loop" => array(
                            "1010020010" => "delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id"   => "placeID",
                            "extern_id"   => "pihakID",
                            "extern_nama" => "pihakName",
                            "jenis"       => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 3: Buku Pembantu Piutang Belum Realisasi Project (Update _rek_pembantu_customer_project_cache)
                    array(
                        "comName" => "RekeningPembantuCustomerProject",
                        "loop" => array(
                            "1010070030" => "-delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id"    => "placeID",
                            "extern_id"    => "pihakID",
                            "extern_nama"  => "pihakName",
                            "extern2_id"   => "projectID",
                            "extern2_nama" => "projectName",
                            "jenis"        => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 4: Buku Pembantu Penjualan Kontinjensi / Termin (4030)
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4030" => "delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id"    => "placeID",
                            "extern_id"    => ".4030030",
                            "extern_nama"  => ".penjualan kontijensi project",
                            "extern4_id"   => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis"        => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 5: Buku Pembantu Penjualan Kontinjensi Project (4030)
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4030" => "delta_tagihan"
                        ),
                        "static" => array(
                            "cabang_id"    => "placeID",
                            "extern_id"    => "projectID",
                            "extern_nama"  => "projectName",
                            "extern2_id"   => ".4030030",
                            "extern2_nama" => ".penjualan kontijensi project",
                            "extern4_id"   => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis"        => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 6: Buku Pembantu Penjualan Riil DPP (4010)
                    array(
                        "comName" => "RekeningPembantuPenjualan",
                        "loop" => array(
                            "4010" => "delta_dpp"
                        ),
                        "static" => array(
                            "cabang_id"    => "placeID",
                            "extern_id"    => ".4010030",
                            "extern_nama"  => ".penjualan project",
                            "extern4_id"   => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis"        => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 7: Buku Pembantu Penjualan Project Riil DPP (4010)
                    array(
                        "comName" => "RekeningPembantuPenjualanProject",
                        "loop" => array(
                            "4010" => "delta_dpp"
                        ),
                        "static" => array(
                            "cabang_id"    => "placeID",
                            "extern_id"    => "projectID",
                            "extern_nama"  => "projectName",
                            "extern2_id"   => ".4010030",
                            "extern2_nama" => ".penjualan project",
                            "extern4_id"   => "pihakID",
                            "extern4_nama" => "pihakName",
                            "jenis"        => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    ),
                    // 8: Buku Pembantu Beban Pajak Diserap (6010 / 601000036 Beban Penjualan Lainnya)
                    array(
                        "comName" => "RekeningPembantuBiayaUsaha",
                        "loop" => array(
                            "6010" => "delta_beban_pajak"
                        ),
                        "static" => array(
                            "cabang_id"   => "placeID",
                            "extern_id"   => ".36",
                            "extern_nama" => ".beban penjualan lainnya",
                            "rek_id"      => ".0360000000",
                            "jenis"       => "jenisTr"
                        ),
                        "srcGateName" => "main",
                        "srcRawGateName" => "main"
                    )
                )
            )
        )
    )
);
// END OF COMPLETE REPEATED LOGIC
