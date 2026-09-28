<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Finansial Global & Pembulatan Presisi (ISO 20022 / ISO 80000-1)
 * SOT Status Pembayaran Transaksi (transaksi_payment_source)
 * Kompatibel 100% dengan PHP 5.6 dan CodeIgniter 3.1.8
 */

// START OF COMPLETE REPEATED LOGIC
if (!function_exists('fin_sub')) {
    /**
     * Pengurangan string desimal presisi tinggi (BC Math)
     * Mengeliminasi floating-point precision bug pada PHP
     */
    function fin_sub($val1, $val2, $precision = 4)
    {
        $v1 = isset($val1) && strlen(trim($val1)) > 0 ? strval($val1) : "0";
        $v2 = isset($val2) && strlen(trim($val2)) > 0 ? strval($val2) : "0";
        return bcsub($v1, $v2, intval($precision));
    }
}

if (!function_exists('fin_add')) {
    /**
     * Penambahan string desimal presisi tinggi (BC Math)
     */
    function fin_add($val1, $val2, $precision = 4)
    {
        $v1 = isset($val1) && strlen(trim($val1)) > 0 ? strval($val1) : "0";
        $v2 = isset($val2) && strlen(trim($val2)) > 0 ? strval($val2) : "0";
        return bcadd($v1, $v2, intval($precision));
    }
}

if (!function_exists('fin_mul')) {
    /**
     * Perkalian string desimal presisi tinggi (BC Math)
     */
    function fin_mul($val1, $val2, $precision = 4)
    {
        $v1 = isset($val1) && strlen(trim($val1)) > 0 ? strval($val1) : "0";
        $v2 = isset($val2) && strlen(trim($val2)) > 0 ? strval($val2) : "0";
        return bcmul($v1, $v2, intval($precision));
    }
}

if (!function_exists('fin_div')) {
    /**
     * Pembagian string desimal presisi tinggi (BC Math)
     */
    function fin_div($val1, $val2, $precision = 4)
    {
        $v1 = isset($val1) && strlen(trim($val1)) > 0 ? strval($val1) : "0";
        $v2 = isset($val2) && strlen(trim($val2)) > 0 ? strval($val2) : "0";
        if (bccomp($v2, "0", intval($precision)) == 0) {
            return "0";
        }
        return bcdiv($v1, $v2, intval($precision));
    }
}

if (!function_exists('fin_is_zero')) {
    /**
     * Pengecekan sisa nol mutlak (Epsilon Comparison)
     * Mengembalikan true jika nilai bernilai 0 (bebas dari sisa desimal mikro 0.000000000001)
     */
    function fin_is_zero($val, $precision = 4)
    {
        $v = isset($val) && strlen(trim($val)) > 0 ? strval($val) : "0";
        $clean = bcsub($v, "0", intval($precision));
        return bccomp($clean, "0", intval($precision)) == 0;
    }
}

if (!function_exists('fin_round')) {
    /**
     * Pembulatan Akuntansi Perbankan (Bankers Rounding ISO 80000-1)
     */
    function fin_round($val, $precision = 0)
    {
        $v = isset($val) ? floatval($val) : 0.0;
        return round($v, intval($precision), PHP_ROUND_HALF_EVEN);
    }
}

if (!function_exists('fin_evaluate_payment_status')) {
    /**
     * Evaluasi Status Pembayaran Berdasarkan Single Source of Truth (transaksi_payment_source)
     * Kompatibel PHP 5.6
     *
     * @param array $paymentData Data pembayaran (hasil query transaksi_payment_source atau summary array)
     * @param float $tolerance Toleransi selisih rupiah pelunasan (default 1000.0)
     * @return array
     */
    function fin_evaluate_payment_status($paymentData, $tolerance = 1000.0)
    {
        $totTagihan = 0.0;
        $totTerbayar = 0.0;
        $totReturned = 0.0;
        $totSisa = 0.0;
        $hasRecord = false;
        $details = array();

        if (is_array($paymentData) && sizeof($paymentData) > 0) {
            if (isset($paymentData['tagihan']) || isset($paymentData['terbayar']) || isset($paymentData['sisa'])) {
                $tag = isset($paymentData['tagihan']) ? floatval($paymentData['tagihan']) : 0.0;
                $ter = isset($paymentData['terbayar']) ? floatval($paymentData['terbayar']) : 0.0;
                $ret = isset($paymentData['returned']) ? floatval($paymentData['returned']) : 0.0;
                $sis = isset($paymentData['sisa']) ? floatval($paymentData['sisa']) : ($tag - $ter - $ret);
                if ($tag > 0 || $ter > 0 || $ret > 0) {
                    $hasRecord = true;
                    $totTagihan = $tag;
                    $totTerbayar = $ter;
                    $totReturned = $ret;
                    $totSisa = $sis;
                }
            } else {
                foreach ($paymentData as $item) {
                    $tag = 0.0;
                    $ter = 0.0;
                    $ret = 0.0;
                    $sis = 0.0;
                    $trID = 0;
                    if (is_object($item)) {
                        $trID = isset($item->transaksi_id) ? intval($item->transaksi_id) : 0;
                        $tag = isset($item->tagihan) ? floatval($item->tagihan) : 0.0;
                        $ter = isset($item->terbayar) ? floatval($item->terbayar) : 0.0;
                        $ret = isset($item->returned) ? floatval($item->returned) : 0.0;
                        $sis = isset($item->sisa) ? floatval($item->sisa) : ($tag - $ter - $ret);
                    } elseif (is_array($item)) {
                        $trID = isset($item['transaksi_id']) ? intval($item['transaksi_id']) : 0;
                        $tag = isset($item['tagihan']) ? floatval($item['tagihan']) : 0.0;
                        $ter = isset($item['terbayar']) ? floatval($item['terbayar']) : 0.0;
                        $ret = isset($item['returned']) ? floatval($item['returned']) : 0.0;
                        $sis = isset($item['sisa']) ? floatval($item['sisa']) : ($tag - $ter - $ret);
                    }

                    if ($tag > 0 || $ter > 0 || $ret > 0) {
                        $hasRecord = true;
                        $totTagihan += $tag;
                        $totTerbayar += $ter;
                        $totReturned += $ret;
                        $totSisa += $sis;
                        if ($trID > 0) {
                            $details[$trID] = array(
                                "tagihan"  => $tag,
                                "terbayar" => $ter,
                                "returned" => $ret,
                                "sisa"     => $sis,
                            );
                        }
                    }
                }
            }
        }

        $status = "BELUM_LUNAS";
        $badgeSm = "<span class='label label-default' style='font-size:11px; color:#555;'>🟡 BELUM LUNAS</span>";
        $badgeLg = "<span class='label label-warning' style='font-size:12px; margin-left:10px; color:#8a6d3b; background-color:#fcf8e3; border:1px solid #faebcc;'><i class='fa fa-clock-o'></i> BELUM MENERIMA PELUNASAN</span>";

        if ($hasRecord && ($totTagihan > 0 || $totTerbayar > 0)) {
            $tol = floatval($tolerance);
            if ($totTerbayar > 0.0 && $totTagihan > 0.0 && ($totSisa <= 0.0 || $totSisa <= $tol)) {
                $status = "LUNAS";
                $badgeSm = "<span class='label label-success' style='font-size:11px;'>🟢 LUNAS</span>";
                $badgeLg = "<span class='label label-success' style='font-size:12px; margin-left:10px;'><i class='fa fa-check-circle'></i> LUNAS</span>";
            } elseif ($totTerbayar > 0.0 && $totTagihan > 0.0) {
                $status = "CICILAN";
                $terbayarFmt = number_format($totTerbayar, 0, ',', '.');
                $sisaFmt = number_format($totSisa, 0, ',', '.');
                $badgeSm = "<span class='label label-warning' style='font-size:11px;'>🟠 CICILAN</span>";
                $badgeLg = "<span class='label label-warning' style='font-size:12px; margin-left:10px; color:#ffffff; background-color:#f0ad4e; border:1px solid #eea236;'><i class='fa fa-hourglass-half'></i> PELUNASAN SEBAGIAN (Terbayar: Rp " . $terbayarFmt . " / Sisa: Rp " . $sisaFmt . ")</span>";
            } elseif ($totTerbayar > 0.0 && $totTagihan == 0.0) {
                $status = "DP";
                $terbayarFmt = number_format($totTerbayar, 0, ',', '.');
                $badgeSm = "<span class='label label-warning' style='font-size:11px; background-color:#f0ad4e;'>🟠 DP DITERIMA</span>";
                $badgeLg = "<span class='label label-warning' style='font-size:12px; margin-left:10px; color:#ffffff; background-color:#f0ad4e; border:1px solid #eea236;'><i class='fa fa-check-circle-o'></i> UANG MUKA / DP DITERIMA (Rp " . $terbayarFmt . ")</span>";
            } elseif ($totReturned >= $totTagihan || ($totReturned > 0.0 && $totSisa <= 0.0)) {
                $status = "BATAL";
                $badgeSm = "<span class='label label-default' style='font-size:11px; color:#777; background-color:#e6e6e6;'>⚪ BATAL/RETUR</span>";
                $badgeLg = "<span class='label label-default' style='font-size:12px; margin-left:10px; color:#555; background-color:#eee; border:1px solid #ccc;'><i class='fa fa-ban'></i> FAKTUR DIBATALKAN / DIRETUR PENUH</span>";
            }
        }

        return array(
            "status"      => $status,
            "is_lunas"    => ($status === "LUNAS"),
            "is_cicilan"  => ($status === "CICILAN"),
            "is_dp"       => ($status === "DP"),
            "is_batal"    => ($status === "BATAL"),
            "tagihan"     => $totTagihan,
            "terbayar"    => $totTerbayar,
            "returned"    => $totReturned,
            "sisa"        => $totSisa,
            "has_record"  => $hasRecord,
            "badge"       => $badgeSm,
            "badge_sm"    => $badgeSm,
            "badge_lg"    => $badgeLg,
            "details"     => $details,
        );
    }
}

if (!function_exists('fin_get_payment_status')) {
    /**
     * Ambil dan hitung status pembayaran transaksi dari tabel transaksi_payment_source
     * Kompatibel PHP 5.6 dan CodeIgniter 3.1.8
     *
     * @param int|string|array $transaksiIDs ID transaksi tunggal atau array ID
     * @param float $tolerance Batas toleransi rupiah pembulatan pelunasan (default 1000.0)
     * @param bool $autoResolveChain Otomatis lacak ID transaksi satu rantai (id_top/id_master) jika true
     * @return array
     */
    function fin_get_payment_status($transaksiIDs, $tolerance = 1000.0, $autoResolveChain = true)
    {
        $cleanIDs = array();
        if (is_array($transaksiIDs)) {
            foreach ($transaksiIDs as $idVal) {
                $idInt = intval($idVal);
                if ($idInt > 0 && !in_array($idInt, $cleanIDs)) {
                    $cleanIDs[] = $idInt;
                }
            }
        } elseif (is_string($transaksiIDs) && strpos($transaksiIDs, ',') !== false) {
            $ex = explode(',', $transaksiIDs);
            foreach ($ex as $idVal) {
                $idInt = intval(trim($idVal));
                if ($idInt > 0 && !in_array($idInt, $cleanIDs)) {
                    $cleanIDs[] = $idInt;
                }
            }
        } else {
            $idInt = intval($transaksiIDs);
            if ($idInt > 0) {
                $cleanIDs[] = $idInt;
            }
        }

        if (sizeof($cleanIDs) == 0) {
            return fin_evaluate_payment_status(array(), $tolerance);
        }

        $CI =& get_instance();

        // Lacak seluruh rantai transaksi jika diminta
        if ($autoResolveChain) {
            $CI->db->select("id, id_top, id_master");
            $CI->db->group_start();
            $CI->db->where_in("id", $cleanIDs);
            $CI->db->or_where_in("id_top", $cleanIDs);
            $CI->db->group_end();
            $CI->db->where("trash", 0);
            $CI->db->where("trash_4", 0);
            $qChain = $CI->db->get("transaksi")->result();
            $topIDs = array();
            if (sizeof($qChain) > 0) {
                foreach ($qChain as $cRow) {
                    $cID = intval($cRow->id);
                    $cTop = intval($cRow->id_top);
                    if ($cID > 0 && !in_array($cID, $cleanIDs)) {
                        $cleanIDs[] = $cID;
                    }
                    if ($cTop > 0 && !in_array($cTop, $topIDs)) {
                        $topIDs[] = $cTop;
                    }
                }
            }

            if (sizeof($topIDs) > 0) {
                $CI->db->select("id");
                $CI->db->where_in("id_top", $topIDs);
                $CI->db->where("trash", 0);
                $CI->db->where("trash_4", 0);
                $qSiblings = $CI->db->get("transaksi")->result();
                if (sizeof($qSiblings) > 0) {
                    foreach ($qSiblings as $sRow) {
                        $sID = intval($sRow->id);
                        if ($sID > 0 && !in_array($sID, $cleanIDs)) {
                            $cleanIDs[] = $sID;
                        }
                    }
                }
            }

            // Jika rantai transaksi memiliki dokumen aktif (trash = 0 AND trash_4 = 0),
            // pastikan ID transaksi yang berstatus reject/batal (trash_4 = 1) disaring keluar
            // agar data retur dari faktur lama yang mati tidak mencemari kartu piutang transaksi aktif
            if (sizeof($cleanIDs) > 0) {
                $CI->db->select("id");
                $CI->db->where_in("id", $cleanIDs);
                $CI->db->where("trash", 0);
                $CI->db->where("trash_4", 0);
                $qActiveCheck = $CI->db->get("transaksi")->result();
                if (sizeof($qActiveCheck) > 0) {
                    $CI->db->select("id");
                    $CI->db->where_in("id", $cleanIDs);
                    $CI->db->group_start();
                    $CI->db->where("trash !=", 0);
                    $CI->db->or_where("trash_4 !=", 0);
                    $CI->db->group_end();
                    $qDeadTrans = $CI->db->get("transaksi")->result();
                    if (sizeof($qDeadTrans) > 0) {
                        $deadIDs = array();
                        foreach ($qDeadTrans as $dRow) {
                            $deadIDs[] = intval($dRow->id);
                        }
                        $filteredCleanIDs = array();
                        foreach ($cleanIDs as $cID) {
                            if (!in_array($cID, $deadIDs)) {
                                $filteredCleanIDs[] = $cID;
                            }
                        }
                        $cleanIDs = $filteredCleanIDs;
                    }
                }
            }
        }

        // Ambil data transaksi_payment_source
        if (sizeof($cleanIDs) == 0) {
            return fin_evaluate_payment_status(array(), $tolerance);
        }

        $CI->db->select("transaksi_id, tagihan, terbayar, returned, sisa");
        $CI->db->where_in("transaksi_id", $cleanIDs);
        $CI->db->where("dihapus", 0);
        $tmpPay = $CI->db->get("transaksi_payment_source")->result();

        return fin_evaluate_payment_status($tmpPay, $tolerance);
    }
}
// END OF COMPLETE REPEATED LOGIC
