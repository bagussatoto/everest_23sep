<?php

if (isset($items)) {

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

    if (sizeof($items) > 0) {

        /*===bagian logic tambahan taxes untuk payment src*/
        if (isset($shopingCartAddTax) && sizeof($shopingCartAddTax) > 0) {
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = $checkTaxes == $sels ? "checked" : "";
                echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_pajak' $checked value='$sels'  onclick=\"$('#result').load('" . $shopingCartAddTaxAction . "/?val='+this.value+'&p=$sels');\">
                              <span>$label</span>
                          </label>";
            }
            echo "</div>";
        }

        $tmpDP = array();
        if (!empty($project_dp)) {
            foreach ($project_dp as $id => $aSpec) {
                $tmpDP[$id] = $aSpec;
            }
        }

        $tmpTermin = array();
        if (!empty($project_termin)) {
            foreach ($project_termin as $id => $aSpec) {
                $tmpTermin[$id] = $aSpec;
            }
        }

        $tmpGaransi = array();
        if (!empty($project_garansi)) {
            foreach ($project_garansi as $id => $aSpec) {
                $tmpGaransi[$id] = $aSpec;
            }
        }

        $ppnFactorNum = isset($ppnFactor) ? ($ppnFactor * 1) : 11;
        $divisorPpn = 1 + ($ppnFactorNum / 100);
        if ($divisorPpn <= 0) {
            $divisorPpn = 1;
        }

        $sumDpDpp = 0;
        if (!empty($tmpDP)) {
            foreach ($tmpDP as $dpSpec) {
                $sumDpDpp += isset($dpSpec['harga']) && $dpSpec['harga'] * 1 > 0 ? ($dpSpec['harga'] / $divisorPpn) : 0;
            }
        }

        $sumTerminDpp = 0;
        if (!empty($tmpTermin)) {
            foreach ($tmpTermin as $terminSpec) {
                $sumTerminDpp += isset($terminSpec['harga']) && $terminSpec['harga'] * 1 > 0 ? ($terminSpec['harga'] / $divisorPpn) : 0;
            }
        }

        $sumRetensiDpp = 0;
        if (!empty($tmpGaransi)) {
            foreach ($tmpGaransi as $retensiSpec) {
                $sumRetensiDpp += isset($retensiSpec['harga']) && $retensiSpec['harga'] * 1 > 0 ? ($retensiSpec['harga'] / $divisorPpn) : 0;
            }
        }

        $totalProjectDppSetting = $sumDpDpp + $sumTerminDpp + $sumRetensiDpp;
        $projectNilaiDpp = isset($projectNilai) ? ($projectNilai * 1) : 0;
        if ($totalProjectDppSetting <= 0) {
            $totalProjectDppSetting = $projectNilaiDpp;
        }

        $paidDpp = isset($main['terbayar']) ? ($main['terbayar'] * 1) : 0;
        $sisaDpp = isset($main['sisa']) ? ($main['sisa'] * 1) : 0;
        if ($sisaDpp <= 0 && $totalProjectDppSetting > 0) {
            $sisaDpp = $totalProjectDppSetting - $paidDpp;
        }
        if ($sisaDpp < 0) {
            $sisaDpp = 0;
        }

        $dpReadyToBill = $sisaDpp - $sumRetensiDpp;
        if ($dpReadyToBill < 0) {
            $dpReadyToBill = 0;
        }
        if ($dpReadyToBill > $sumDpDpp) {
            $dpReadyToBill = $sumDpDpp;
        }

        $retensiReadyToBill = 0;
        if ($sisaDpp > 0) {
            $retensiReadyToBill = $sisaDpp >= $sumRetensiDpp ? $sumRetensiDpp : $sisaDpp;
        }
        if ($retensiReadyToBill < 0) {
            $retensiReadyToBill = 0;
        }

        $terminReadyToBill = $sisaDpp - $dpReadyToBill - $retensiReadyToBill;
        if ($terminReadyToBill < 0) {
            $terminReadyToBill = 0;
        }

        $arrTerminDpIssued = (isset($terminprojectDp) && is_array($terminprojectDp)) ? $terminprojectDp : array();
        $arrTerminTerminIssued = (isset($terminprojectTermin) && is_array($terminprojectTermin)) ? $terminprojectTermin : ((isset($terminproject) && is_array($terminproject)) ? $terminproject : array());
        $arrTerminRetensiIssued = (isset($terminprojectRetensi) && is_array($terminprojectRetensi)) ? $terminprojectRetensi : array();
        $hasIssuedTypeBreakdown = (sizeof($arrTerminDpIssued) + sizeof($arrTerminTerminIssued) + sizeof($arrTerminRetensiIssued)) > 0;

        if (!function_exists('mgkReconField')) {
            /**
             * Ambil field dari row object/array secara aman.
             *
             * @param mixed $row
             * @param string $field
             * @param mixed $default
             * @return mixed
             */
            function mgkReconField($row, $field, $default = '')
            {
                if (is_array($row) && isset($row[$field])) {
                    return $row[$field];
                }
                if (is_object($row) && isset($row->$field)) {
                    return $row->$field;
                }
                return $default;
            }
        }

        if (!function_exists('mgkReconDualNominalHtml')) {
            /**
             * Format nilai DPP + info nilai Incl.PPN.
             *
             * @param float $dppValue
             * @param float $ppnFactor
             * @return string
             */
            function mgkReconDualNominalHtml($dppValue, $ppnFactor)
            {
                $dppNominal = $dppValue * 1;
                $divisor = 1 + (($ppnFactor * 1) / 100);
                if ($divisor <= 0) {
                    $divisor = 1;
                }
                $inclNominal = $dppNominal * $divisor;
                return number_format($dppNominal) . "<div class='text-muted' style='font-size:11px;'>Incl: " . number_format($inclNominal) . "</div>";
            }
        }

        if (!function_exists('mgkBuildReconSourceSummary')) {
            /**
             * Build data sumber terbit untuk modal detail.
             *
             * @param array $issuedRows
             * @param float $ppnFactor
             * @return array
             */
            function mgkBuildReconSourceSummary($issuedRows, $ppnFactor)
            {
                $result = array(
                    'rowsHtml' => "",
                    'count' => 0,
                    'totalDpp' => 0,
                    'totalPpn' => 0,
                    'totalIncl' => 0,
                    'totalReturned' => 0,
                    'totalReturnedIncl' => 0,
                    'totalNetDpp' => 0,
                    'totalNetIncl' => 0,
                    'totalPaidIncl' => 0,
                    'totalOutstandingIncl' => 0
                );

                if (!is_array($issuedRows) || empty($issuedRows)) {
                    $result['rowsHtml'] = "<tr><td colspan='11' class='text-center text-muted'>Belum ada data penerbitan.</td></tr>";
                    return $result;
                }

                $rowNo = 0;
                $divisor = 1 + (($ppnFactor * 1) / 100);
                if ($divisor <= 0) {
                    $divisor = 1;
                }

                foreach ($issuedRows as $issuedRow) {
                    $rowNo++;
                    $dtime = trim((string) mgkReconField($issuedRow, 'dtime', '-'));
                    if ($dtime === "") {
                        $dtime = "-";
                    }

                    $nomer = trim((string) mgkReconField($issuedRow, 'nomer', ''));
                    if ($nomer === "") {
                        $nomer = trim((string) mgkReconField($issuedRow, 'nomer_top', ''));
                    }
                    if ($nomer === "") {
                        $nomer = "-";
                    }

                    $rawTagihan = mgkReconField($issuedRow, 'tagihan', 0) * 1;
                    $ppnNominal = mgkReconField($issuedRow, 'ppn', 0) * 1;
                    $dppNominal = mgkReconField($issuedRow, 'dpp_ppn', 0) * 1;
                    $targetJenis = trim((string) mgkReconField($issuedRow, 'target_jenis', ''));

                    // Sesuai 6 Aturan payment_source:
                    // 1. target_jenis 7499: Plafon Kontrak (Hanya DPP)
                    // 2. target_jenis 04467: UM Modul Uang Muka (Hanya DPP)
                    // 3. target_jenis 7488: Tagihan Retensi Lapangan (Gross: DPP + PPN)
                    // 4, 5, 6. target_jenis 749 (_key: dp, termin, retensi): Faktur A/R Kasir (Gross: DPP + PPN)
                    if ($targetJenis === '04467') {
                        $inclForRatio = $rawTagihan * $divisor;
                        if ($dppNominal <= 0) {
                            $dppNominal = $rawTagihan;
                        }
                        if ($ppnNominal <= 0) {
                            $ppnNominal = $inclForRatio - $dppNominal;
                        }
                    } else if ($ppnNominal > 0 && abs($rawTagihan - $dppNominal) < 10) {
                        // Anomali legacy: tersimpan sebagai DPP di record faktur kasir 749
                        $inclForRatio = $rawTagihan + $ppnNominal;
                        if ($dppNominal <= 0) {
                            $dppNominal = $rawTagihan;
                        }
                    } else {
                        // Standar baku: tersimpan sebagai Gross (DPP + PPN)
                        $inclForRatio = $rawTagihan;
                        if ($dppNominal <= 0) {
                            $dppNominal = $rawTagihan - $ppnNominal;
                            if ($dppNominal <= 0) {
                                $dppNominal = $rawTagihan > 0 ? ($rawTagihan / $divisor) : 0;
                            }
                        }
                        if ($ppnNominal <= 0 && $inclForRatio > 0) {
                            $ppnNominal = $inclForRatio - $dppNominal;
                        }
                    }

                    $returnedNominalRaw = mgkReconField($issuedRow, 'returned', 0) * 1;
                    $returnedDppNominal = $returnedNominalRaw;
                    if ($returnedDppNominal > 0 && $dppNominal > 0) {
                        if ($inclForRatio > 0 && $returnedDppNominal > ($dppNominal + 1)) {
                            $returnedDppNominal = ($returnedDppNominal / $inclForRatio) * $dppNominal;
                        }
                        if ($returnedDppNominal > $dppNominal) {
                            $returnedDppNominal = $dppNominal;
                        }
                    } else if ($returnedDppNominal < 0) {
                        $returnedDppNominal = 0;
                    }

                    $returnedInclNominal = 0;
                    if ($returnedNominalRaw > 0) {
                        if ($inclForRatio > 0 && $returnedNominalRaw > ($dppNominal + 1)) {
                            $returnedInclNominal = $returnedNominalRaw;
                        } else {
                            $returnedInclNominal = $returnedDppNominal * $divisor;
                        }
                        if ($returnedInclNominal > $inclForRatio && $inclForRatio > 0) {
                            $returnedInclNominal = $inclForRatio;
                        }
                    }

                    $netDppNominal = $dppNominal - $returnedDppNominal;
                    if ($netDppNominal < 0) {
                        $netDppNominal = 0;
                    }
                    $netInclNominal = $inclForRatio - $returnedInclNominal;
                    if ($netInclNominal < 0) {
                        $netInclNominal = 0;
                    }

                    $paidInclNominal = mgkReconField($issuedRow, 'terbayar', 0) * 1;
                    if ($paidInclNominal < 0) {
                        $paidInclNominal = 0;
                    }
                    if ($paidInclNominal <= 0 && $netInclNominal > 0) {
                        $sisaInclNominalRaw = mgkReconField($issuedRow, 'sisa', 0) * 1;
                        if ($ppnNominal > 0 && abs($sisaInclNominalRaw - $dppNominal) < 10) {
                            $sisaInclNominalRaw = $netInclNominal;
                        }
                        if ($sisaInclNominalRaw >= 0 && $sisaInclNominalRaw <= ($netInclNominal + 1)) {
                            $paidInclNominal = $netInclNominal - $sisaInclNominalRaw;
                        }
                    }
                    if ($paidInclNominal > $netInclNominal) {
                        $paidInclNominal = $netInclNominal;
                    }

                    $outstandingInclNominal = $netInclNominal - $paidInclNominal;
                    if ($outstandingInclNominal < 0) {
                        $outstandingInclNominal = 0;
                    }

                    $cancelId = mgkReconField($issuedRow, 'cancel_id', 0) * 1;
                    $trash = mgkReconField($issuedRow, 'trash', 0) * 1;
                    $isMuted = ($cancelId > 0) || ($trash > 0) || ($returnedNominalRaw > 0);
                    $rowStyle = $isMuted ? " style='background:#f5f5f5;color:#777;'" : "";
                    $statusText = "-";
                    if ($cancelId > 0 || $trash > 0) {
                        $statusText = "Dibatalkan";
                    } else if ($returnedInclNominal > 0 && $netInclNominal <= 1) {
                        $statusText = "Return 100%";
                    } else if ($outstandingInclNominal <= 1 && $netInclNominal > 0) {
                        $statusText = "Lunas";
                    } else if ($paidInclNominal > 0) {
                        $statusText = "Dibayar Sebagian";
                    } else if ($netInclNominal > 0) {
                        $statusText = "Belum Dibayar";
                    }

                    $result['totalDpp'] += $dppNominal;
                    $result['totalPpn'] += $ppnNominal;
                    $result['totalIncl'] += $inclForRatio;
                    $result['totalReturned'] += $returnedNominalRaw;
                    $result['totalReturnedIncl'] += $returnedInclNominal;
                    $result['totalNetDpp'] += $netDppNominal;
                    $result['totalNetIncl'] += $netInclNominal;
                    $result['totalPaidIncl'] += $paidInclNominal;
                    $result['totalOutstandingIncl'] += $outstandingInclNominal;

                    $result['rowsHtml'] .= "<tr" . $rowStyle . ">";
                    $result['rowsHtml'] .= "<td class='text-center'>" . $rowNo . "</td>";
                    $result['rowsHtml'] .= "<td>" . htmlspecialchars($dtime, ENT_QUOTES, 'UTF-8') . "</td>";
                    $result['rowsHtml'] .= "<td>" . htmlspecialchars($nomer, ENT_QUOTES, 'UTF-8') . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($dppNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($ppnNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($inclForRatio) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($returnedInclNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($netInclNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($paidInclNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-right'>" . number_format($outstandingInclNominal) . "</td>";
                    $result['rowsHtml'] .= "<td class='text-center'>" . $statusText . "</td>";
                    $result['rowsHtml'] .= "</tr>";
                }

                $result['count'] = $rowNo;
                if ($result['rowsHtml'] === "") {
                    $result['rowsHtml'] = "<tr><td colspan='11' class='text-center text-muted'>Belum ada data penerbitan.</td></tr>";
                }

                return $result;
            }
        }

        if (!function_exists('mgkBuildReconComponentSummary')) {
            /**
             * Ringkasan komponen penagihan untuk panel status.
             *
             * @param float $alokasiDpp
             * @param array $sourceSummary
             * @param float $ppnFactor
             * @return array
             */
            function mgkBuildReconComponentSummary($alokasiDpp, $sourceSummary, $ppnFactor)
            {
                $alokasiDpp = $alokasiDpp * 1;
                if ($alokasiDpp < 0) {
                    $alokasiDpp = 0;
                }
                $ppnNominal = $alokasiDpp * (($ppnFactor * 1) / 100);
                if ($ppnNominal < 0) {
                    $ppnNominal = 0;
                }
                $alokasiIncl = $alokasiDpp + $ppnNominal;
                $brutoIncl = isset($sourceSummary['totalIncl']) ? ($sourceSummary['totalIncl'] * 1) : 0;
                $returIncl = isset($sourceSummary['totalReturnedIncl']) ? ($sourceSummary['totalReturnedIncl'] * 1) : 0;
                if ($returIncl < 0) {
                    $returIncl = 0;
                }
                $netoDitagihIncl = isset($sourceSummary['totalNetIncl']) ? ($sourceSummary['totalNetIncl'] * 1) : ($brutoIncl - $returIncl);
                if ($netoDitagihIncl < 0) {
                    $netoDitagihIncl = 0;
                }
                $netoDibayarIncl = isset($sourceSummary['totalPaidIncl']) ? ($sourceSummary['totalPaidIncl'] * 1) : 0;
                if ($netoDibayarIncl < 0) {
                    $netoDibayarIncl = 0;
                }
                if ($netoDibayarIncl > $netoDitagihIncl) {
                    $netoDibayarIncl = $netoDitagihIncl;
                }
                $sisaNetoIncl = $alokasiIncl - $netoDitagihIncl;
                if ($sisaNetoIncl < 0) {
                    $sisaNetoIncl = 0;
                }

                return array(
                    'alokasiDpp' => $alokasiDpp,
                    'alokasiPpn' => $ppnNominal,
                    'alokasiIncl' => $alokasiIncl,
                    'brutoDitagihIncl' => $brutoIncl,
                    'returIncl' => $returIncl,
                    'netoDitagihIncl' => $netoDitagihIncl,
                    'netoDibayarIncl' => $netoDibayarIncl,
                    'sisaNetoIncl' => $sisaNetoIncl
                );
            }
        }

        $reconSourceDp = mgkBuildReconSourceSummary($arrTerminDpIssued, $ppnFactor);
        $reconSourceTermin = mgkBuildReconSourceSummary($arrTerminTerminIssued, $ppnFactor);
        $reconSourceRetensi = mgkBuildReconSourceSummary($arrTerminRetensiIssued, $ppnFactor);
        $reconModalSuffix = (isset($projectID) ? ($projectID * 1) : 0) . "_" . __LINE__;
        $reconModalDpId = "modal_recon_src_dp_" . $reconModalSuffix;
        $reconModalTerminId = "modal_recon_src_termin_" . $reconModalSuffix;
        $reconModalRetensiId = "modal_recon_src_retensi_" . $reconModalSuffix;

        if ($hasIssuedTypeBreakdown) {
            $issuedDpDpp = isset($reconSourceDp['totalNetDpp']) ? ($reconSourceDp['totalNetDpp'] * 1) : 0;
            if ($issuedDpDpp < 0) {
                $issuedDpDpp = 0;
            }

            $issuedTerminDpp = isset($reconSourceTermin['totalNetDpp']) ? ($reconSourceTermin['totalNetDpp'] * 1) : 0;
            if ($issuedTerminDpp < 0) {
                $issuedTerminDpp = 0;
            }

            $issuedRetensiDpp = isset($reconSourceRetensi['totalNetDpp']) ? ($reconSourceRetensi['totalNetDpp'] * 1) : 0;
            if ($issuedRetensiDpp < 0) {
                $issuedRetensiDpp = 0;
            }

            $dpReadyToBill = $sumDpDpp - $issuedDpDpp;
            if ($dpReadyToBill < 0) {
                $dpReadyToBill = 0;
            }
            if ($dpReadyToBill > $sumDpDpp) {
                $dpReadyToBill = $sumDpDpp;
            }

            $terminReadyToBill = $sumTerminDpp - $issuedTerminDpp;
            if ($terminReadyToBill < 0) {
                $terminReadyToBill = 0;
            }
            if ($terminReadyToBill > $sumTerminDpp) {
                $terminReadyToBill = $sumTerminDpp;
            }

            $retensiReadyToBill = $sumRetensiDpp - $issuedRetensiDpp;
            if ($retensiReadyToBill < 0) {
                $retensiReadyToBill = 0;
            }
            if ($retensiReadyToBill > $sumRetensiDpp) {
                $retensiReadyToBill = $sumRetensiDpp;
            }

            $paidDpp = $issuedDpDpp + $issuedTerminDpp + $issuedRetensiDpp;
            if ($paidDpp < 0) {
                $paidDpp = 0;
            }
            if ($paidDpp > $totalProjectDppSetting) {
                $paidDpp = $totalProjectDppSetting;
            }
            $sisaDpp = $totalProjectDppSetting - $paidDpp;
            if ($sisaDpp < 0) {
                $sisaDpp = 0;
            }

            // Residual Plug Pattern: $dpReadyToBill tidak boleh melebihi sisa plafon proyek riil ($sisaDpp)
            if ($dpReadyToBill > $sisaDpp) {
                $dpReadyToBill = $sisaDpp;
            }
        }

        $totalReadyToBill = $dpReadyToBill + $terminReadyToBill + $retensiReadyToBill;
        if ($totalReadyToBill < 0) {
            $totalReadyToBill = 0;
        }

        $targetSisaDpRetensi = $sumDpDpp + $sumRetensiDpp;
        $toleranceDpp = 100;
        $isSisaKomponenDpRetensi = ($sisaDpp > 0) && (abs($sisaDpp - $targetSisaDpRetensi) <= $toleranceDpp);

        $paidPct = $totalProjectDppSetting > 0 ? round(($paidDpp / $totalProjectDppSetting) * 100, 2) : 0;
        if ($paidPct < 0) {
            $paidPct = 0;
        }
        if ($paidPct > 100) {
            $paidPct = 100;
        }
        $sisaPct = 100 - $paidPct;
        if ($sisaPct < 0) {
            $sisaPct = 0;
        }

        $typePaymentDefault = isset($typePaymentDefault) ? strtolower(trim($typePaymentDefault)) : "";
        $selectedTypePayment = $typePaymentDefault;
        if ($selectedTypePayment === "uang muka" || $selectedTypePayment === "uang_muka" || $selectedTypePayment === "downpayment") {
            $selectedTypePayment = "dp";
        }
        else if ($selectedTypePayment === "garansi") {
            $selectedTypePayment = "retensi";
        }
        if (!in_array($selectedTypePayment, array("dp", "termin", "retensi"))) {
            $selectedTypePayment = "";
        }

        //region DP, Termin dan Garansi
//        cekMerah("Down Payment");
//        arrPrint($tmpDP);
//        cekMerah("Termin");
//        arrPrint($tmpTermin);


        $tblDP = "";
        if (!empty($tmpDP)) {
            $tblDP .= "<table class='table dataTable compact display table-bordered'>";
            $tblDP .= "<thead>";
            $tblDP .= "<tr>";
            $tblDP .= "<th>No</th>";
            $tblDP .= "<th>Bobot<br>(%)</th>";
            $tblDP .= "<th>DPP<br>(Rp)</th>";
            $tblDP .= "<th>PPN 11%<br>(Rp)</th>";
            $tblDP .= "<th>Nilai Incl.PPN<br>(Rp)</th>";
            $tblDP .= "</tr>";
            $tblDP .= "</thead>";
            $tblDP .= "<tbody>";
            $noDP = 0;
            foreach ($tmpDP as $kt => $datas) {
                $dpp_dp = $datas['harga'] * 1 > 0 ? $datas['harga'] / (1 + (11 / 100)) : 0;
                $ppn_dp = $datas['harga'] * 1 > 0 ? ($datas['harga'] - $dpp_dp) : 0;
                $noDP++;
                $tblDP .= "<tr>";
                $tblDP .= "<td>$noDP</td>";
                $tblDP .= "<td class='text-right'>" . $datas['persen'] . " %</td>";
                $tblDP .= "<td style='cursor: pointer' onclick=\"triggerEntriNilai(this.textContent,'uangmuka','DP Project')\" class='text-right text-bold text-green'>" . number_format($dpp_dp) . "</td>";
                $tblDP .= "<td class='text-right'>" . number_format($ppn_dp) . "</td>";
                $tblDP .= "<td class='text-right'>" . number_format($datas['harga']) . "</td>";
                $tblDP .= "</tr>";
            }
            $tblDP .= "</tbody>";
            $tblDP .= "</table>";
        }
        else {
            $tblDP .= "<table class='table dataTable compact display table-bordered'>";
            $tblDP .= "<thead>";
            $tblDP .= "<tr>";
            $tblDP .= "<th class='bg-red'>TIDAK ADA UANG MUKA</th>";
            $tblDP .= "</tr>";
            $tblDP .= "</thead>";
            $tblDP .= "</table>";
        }

        $tblTermin = "";
        if (!empty($tmpTermin)) {
            $tblTermin .= "<table class='table dataTable compact display table-bordered'>";
            $tblTermin .= "<thead>";
            $tblTermin .= "<tr>";
            $tblTermin .= "<th>No</th>";
            $tblTermin .= "<th>Desc</th>";
            $tblTermin .= "<th>Progress</th>";
            $tblTermin .= "<th>Bayar<br>(%)</th>";
            $tblTermin .= "<th>DPP<br>(Rp)</th>";
            $tblTermin .= "<th>PPN 11%<br>(Rp)</th>";
            $tblTermin .= "<th>Bayar<br>(Rp)</th>";
            $tblTermin .= "</tr>";
            $tblTermin .= "</thead>";
            $tblTermin .= "<tbody>";
            $noT = 0;
            foreach ($tmpTermin as $kt => $termin) {
                $dpp_termin = $termin['harga'] * 1 > 0 ? $termin['harga'] / (1 + (11 / 100)) : 0;
                $ppn_termin = $termin['harga'] * 1 > 0 ? ($termin['harga'] - $dpp_termin) : 0;
                $noT++;
                $tblTermin .= "<tr>";
                $tblTermin .= "<td>$noT</td>";
                $tblTermin .= "<td>" . $termin['nama'] . "</td>";
                $tblTermin .= "<td class='text-right'>" . $termin['progress'] . " %</td>";
                $tblTermin .= "<td class='text-right'>" . $termin['persen'] . " %</td>";
                $tblTermin .= "<td style='cursor: pointer' onclick=\"triggerEntriNilai(this.textContent,'termin','" . $termin['nama'] . "')\" class='text-right text-bold text-green'>" . number_format($dpp_termin) . "</td>";
                $tblTermin .= "<td class='text-right'>" . number_format($ppn_termin) . "</td>";
                $tblTermin .= "<td class='text-right'>" . number_format($termin['harga']) . "</td>";
                $tblTermin .= "</tr>";
            }
            $tblTermin .= "</tbody>";
            $tblTermin .= "</table>";
        }
        else {
            $tblTermin .= "<table class='table dataTable compact display table-bordered'>";
            $tblTermin .= "<thead>";
            $tblTermin .= "<tr>";
            $tblTermin .= "<th class='bg-red'>TIDAK ADA SETTINGAN TERMIN</th>";
            $tblTermin .= "</tr>";
            $tblTermin .= "</thead>";
            $tblTermin .= "</table>";
        }

        $tblGaransi = "";
        if (!empty($tmpGaransi)) {
            $tblGaransi .= "<table class='table dataTable compact display table-bordered'>";
            $tblGaransi .= "<thead>";
            $tblGaransi .= "<tr>";
            $tblGaransi .= "<th>No</th>";
            $tblGaransi .= "<th>Bobot<br>(%)</th>";
            $tblGaransi .= "<th>DPP<br>(Rp)</th>";
            $tblGaransi .= "<th>PPN 11%<br>(Rp)</th>";
            $tblGaransi .= "<th>Nilai<br>(Rp)</th>";
            $tblGaransi .= "</tr>";
            $tblGaransi .= "</thead>";
            $tblGaransi .= "<tbody>";
            $noGr = 0;
            foreach ($tmpGaransi as $kt => $datas) {
                $dpp_garansi = $datas['harga'] * 1 > 0 ? $datas['harga'] / (1 + (11 / 100)) : 0;
                $ppn_garansi = $datas['harga'] * 1 > 0 ? ($datas['harga'] - $dpp_garansi) : 0;
                $noGr++;
                $tblGaransi .= "<tr>";
                $tblGaransi .= "<td>$noGr</td>";
                $tblGaransi .= "<td class='text-right'>" . $datas['persen'] . " %</td>";
                $tblGaransi .= "<td style='cursor: pointer' onclick=\"triggerEntriNilai(this.textContent,'retensi','retensi (" . $datas['persen'] . "%)')\" class='text-right text-bold text-green'>" . number_format($dpp_garansi) . "</td>";
                $tblGaransi .= "<td class='text-right'>" . number_format($ppn_garansi) . "</td>";
                $tblGaransi .= "<td class='text-right'>" . number_format($datas['harga']) . "</td>";
                $tblGaransi .= "</tr>";
            }
            $tblGaransi .= "</tbody>";
            $tblGaransi .= "</table>";
        }
        else {
            $tblGaransi .= "<table class='table dataTable compact display table-bordered'>";
            $tblGaransi .= "<thead>";
            $tblGaransi .= "<tr>";
            $tblGaransi .= "<th class='bg-red'>TIDAK ADA SETTINGAN GARANSI</th>";
            $tblGaransi .= "</tr>";
            $tblGaransi .= "</thead>";
            $tblGaransi .= "</table>";
        }

//        cekMerah("Garansi");
//        arrPrint($tmpGaransi);

        echo "<div style='xbackground-color: #ffb301 !important;' line='" . __LINE__ . "' class='row no-margin'>";

        echo "<div class='col-lg-12'>";
        echo "<label class='fa-2x'><u>EVALUASI PROJECT: $projectName</u></label><br>";
        echo "<label class='fa-2x'>NILAI PROJECT (Excl.PPN): " . number_format($projectNilai) . "</label>";
        echo "<input type='text' id='projectNilai' value='$projectNilai' class='hidden'>";
        echo "<input type='text' id='projectName' value='$projectName' class='hidden'>";
        echo "<input type='text' id='ppnFactor' value='$ppnFactor' class='hidden'>";
        echo "<br>";
        echo "<br>";
        echo "</div>";

        echo "<div class='col-lg-4'>";
        echo "<label class='fa-2x'>UANG MUKA</label>";
        echo $tblDP;
        echo "</div>";

        echo "<div class='col-lg-4'>";
        echo "<label class='fa-2x'>TERMIN</label>";
        echo $tblTermin;
        echo "</div>";

        echo "<div class='col-lg-4'>";
        echo "<label class='fa-2x'>RETENSI/GARANSI</label>";
        echo $tblGaransi;
        echo "</div>";

        $dpSudahDitagih = $sumDpDpp - $dpReadyToBill;
        if ($dpSudahDitagih < 0) {
            $dpSudahDitagih = 0;
        }
        $terminSudahDitagih = $sumTerminDpp - $terminReadyToBill;
        if ($terminSudahDitagih < 0) {
            $terminSudahDitagih = 0;
        }
        $retensiSudahDitagih = $sumRetensiDpp - $retensiReadyToBill;
        if ($retensiSudahDitagih < 0) {
            $retensiSudahDitagih = 0;
        }

        $dpPct = $totalProjectDppSetting > 0 ? round(($sumDpDpp / $totalProjectDppSetting) * 100, 2) : 0;
        $terminAllocPct = $totalProjectDppSetting > 0 ? round(($sumTerminDpp / $totalProjectDppSetting) * 100, 2) : 0;
        $terminIssuedPct = $totalProjectDppSetting > 0 ? round(($terminSudahDitagih / $totalProjectDppSetting) * 100, 2) : 0;
        $retensiPct = $totalProjectDppSetting > 0 ? round(($sumRetensiDpp / $totalProjectDppSetting) * 100, 2) : 0;
        $remainingPct = $terminAllocPct - $terminIssuedPct;

        if ($dpPct < 0) {
            $dpPct = 0;
        }
        if ($terminAllocPct < 0) {
            $terminAllocPct = 0;
        }
        if ($terminIssuedPct < 0) {
            $terminIssuedPct = 0;
        }
        if ($terminIssuedPct > $terminAllocPct) {
            $terminIssuedPct = $terminAllocPct;
        }
        if ($retensiPct < 0) {
            $retensiPct = 0;
        }
        if ($remainingPct < 0) {
            $remainingPct = 0;
        }
        if ($remainingPct > $terminAllocPct) {
            $remainingPct = $terminAllocPct;
        }

        $btnDpDisabled = $dpReadyToBill > 0 ? "" : "disabled";
        $btnTerminDisabled = $terminReadyToBill > 0 ? "" : "disabled";
        $btnRetensiDisabled = $retensiReadyToBill > 0 ? "" : "disabled";
        $dpSummary = mgkBuildReconComponentSummary($sumDpDpp, $reconSourceDp, $ppnFactor);
        $terminSummary = mgkBuildReconComponentSummary($sumTerminDpp, $reconSourceTermin, $ppnFactor);
        $retensiSummary = mgkBuildReconComponentSummary($sumRetensiDpp, $reconSourceRetensi, $ppnFactor);
        $totalSisaNetoIncl = $dpSummary['sisaNetoIncl'] + $terminSummary['sisaNetoIncl'] + $retensiSummary['sisaNetoIncl'];
        if ($totalSisaNetoIncl < 0) {
            $totalSisaNetoIncl = 0;
        }

        //--------------------

// START OF COMPLETE REPEATED LOGIC
        // Collapsible Panel Histori Uang Muka (DP 4467) - Posisi Kotak Hijau
        if (isset($historiDP)) {
            $jmlDpCount = is_array($historiDP) ? sizeof($historiDP) : 0;
            echo "<div class='col-lg-12' style='margin-top:10px;'>";
            echo "<div class='panel panel-default no-margin' style='border: 1px solid #17a2b8; border-radius: 8px;'>";
            echo "<div class='panel-heading bg-info' style='padding: 6px 12px; font-weight: bold; border-top-left-radius: 7px; border-top-right-radius: 7px;'>";
            echo "<span>RINCIAN HISTORI PENERIMAAN UANG MUKA</span>";
            if ($jmlDpCount > 0) {
                echo "<button type='button' class='btn btn-xs btn-info pull-right btn-toggle-dp' data-toggle='collapse' data-target='#panel_histori_dp' aria-expanded='false' style='margin-top: -2px;'>";
                echo "<i class='fa fa-eye'></i> Lihat Rincian DP ($jmlDpCount) <i class='fa fa-chevron-down icon-arrow'></i>";
                echo "</button>";
            } else {
                echo "<span class='pull-right text-muted font-size-11' style='font-weight:normal;'>Belum ada histori DP cair</span>";
            }
            echo "</div>";

            echo "<div id='panel_histori_dp' class='collapse' style='padding: 10px;'>";
            echo "<div class='table-responsive no-padding'>";
            echo "<table class='table table-bordered table-condensed compact no-margin bg-white'>";
            echo "<thead>";
            echo "<tr class='bg-grey-1 text-uppercase' style='font-size: 11px;'>";
            echo "<th class='text-center' width='3%'>No</th>";
            echo "<th class='text-center'>Tanggal</th>";
            echo "<th class='text-center'>Nomor</th>";
            echo "<th class='text-center'>Penerima Uang Muka</th>";
            echo "<th class='text-center'>Faktur Pajak Uang Muka</th>";
            echo "<th class='text-right'>Nilai DPP</th>";
            echo "<th class='text-right'>Nilai PPN</th>";
            echo "<th class='text-right'>Total DP (Incl PPN)</th>";
//            echo "<th class='text-right'>Terpakai Lalu</th>";
//            echo "<th class='text-right'>Saldo DP Active</th>";
//            echo "<th class='text-center' width='6%'>Status</th>";
            echo "<th class='text-center'>Bank</th>";
            echo "<th class='text-center'>Akun</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            if ($jmlDpCount > 0) {
                $noDp = 0;
                $dppDpTotal = 0;
                $ppnDpTotal = 0;
                $grossDpTotal = 0;
                foreach ($historiDP as $dp) {
                    $noDp++;
                    $bank = isset($dp['bank_nama']) ? $dp['bank_nama'] : 0;
                    $bank_rekening = isset($dp['bank_rekening_nama']) ? $dp['bank_rekening_nama'] : 0;
                    $sisaActive = isset($dp['sisa_dp_active']) ? $dp['sisa_dp_active'] : 0;
                    $sisaActive = isset($dp['sisa_dp_active']) ? $dp['sisa_dp_active'] : 0;
                    $grossDp = isset($dp['total_dp_gross']) ? $dp['total_dp_gross'] : 0;
                    $dppDp = isset($dp['dpp_dp']) ? $dp['dpp_dp'] : 0;
                    $ppnDp = isset($dp['ppn_dp']) ? $dp['ppn_dp'] : 0;
                    $penerimaNama = isset($dp['penerima_nama']) && !empty($dp['penerima_nama']) ? $dp['penerima_nama'] : '-';
                    $terpakaiLalu = isset($dp['dp_terpakai_lalu']) ? $dp['dp_terpakai_lalu'] : 0;
//                    $tglCair = isset($dp['tanggal_terima']) ? date('d-m-Y H:i', strtotime($dp['tanggal_terima'])) : '-';
                    $tglCair = isset($dp['tanggal_terima']) ? formatField('dtime', $dp['tanggal_terima']) : '-';
                    $noKwitansi = isset($dp['no_kwitansi_dp']) ? $dp['no_kwitansi_dp'] : '-';
                    $fakturPajak = isset($dp['no_faktur_pajak']) && !empty($dp['no_faktur_pajak']) ? $dp['no_faktur_pajak'] : '-';
                    $dpId = isset($dp['transaksi_dp_id']) ? $dp['transaksi_dp_id'] : 0;
                    $jenisTr = isset($dp['jenis']) ? $dp['jenis'] : 0;
                    $modul_path = base_url() . "kas/";

                    $dppDpTotal += $dppDp;
                    $ppnDpTotal += $ppnDp;
                    $grossDpTotal += $grossDp;

                    echo "<tr>";
                    echo "<td class='text-center'>$noDp</td>";
                    echo "<td class='text-center'>$tglCair</td>";
                    echo "<td>";
//                    echo "<a href='" . base_url() . "kas/Printing/index/4467/$dpId' target='_blank' class='text-bold text-blue'>";
//                    echo "<i class='fa fa-print'></i> $noKwitansi";
//                    echo "</a>";
                    echo "<i class='fa fa-print'></i> " . formatField_he_format("nomer", $noKwitansi, $jenisTr, $modul_path);
                    echo "</td>";
                    echo "<td class='text-center text-bold'>$penerimaNama</td>";
                    echo "<td class='text-center'>$fakturPajak</td>";
                    echo "<td class='text-right'>" . number_format($dppDp, 0, ',', '.') . "</td>";
                    echo "<td class='text-right'>" . number_format($ppnDp, 0, ',', '.') . "</td>";
                    echo "<td class='text-right text-bold'>" . number_format($grossDp, 0, ',', '.') . "</td>";
//                    echo "<td class='text-right text-muted'>" . number_format($terpakaiLalu, 0, ',', '.') . "</td>";
//                    echo "<td class='text-right text-bold text-green'>" . number_format($sisaActive, 0, ',', '.') . "</td>";
//                    echo "<td class='text-center'><span class='label label-success' style='font-size:10px;'>CAIR</span></td>";
                    echo "<td class='text-left'>$bank</td>";
                    echo "<td class='text-left'>$bank_rekening</td>";
                    echo "</tr>";
                }
                echo "<tr>";
                echo "<td class='text-center'>-</td>";
                echo "<td class='text-center'>-</td>";
                echo "<td>-</td>";
                echo "<td class='text-center text-bold'>-</td>";
                echo "<td class='text-center'>-</td>";
                echo "<td class='text-right'>" . number_format($dppDpTotal, 0, ',', '.') . "</td>";
                echo "<td class='text-right'>" . number_format($ppnDpTotal, 0, ',', '.') . "</td>";
                echo "<td class='text-right text-bold'>" . number_format($grossDpTotal, 0, ',', '.') . "</td>";
                echo "<td class='text-left'>-</td>";
                echo "<td class='text-left'>-</td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='10' class='text-center text-muted'>Belum ada histori penerimaan Uang Muka untuk proyek ini.</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
// END OF COMPLETE REPEATED LOGIC

        //--------------------

        echo "<div class='col-lg-12' style='margin-top:10px;'>";
        echo "<div class='panel panel-default no-margin' style='border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);'>";
        echo "<div class='panel-body' style='padding:16px;'>";
        echo "<div class='text-bold text-red' style='font-size:16px;margin-bottom:10px;'>STATUS PENAGIHAN PROYEK (" . strtoupper($projectName) . ")</div>";
        echo "<div style='margin-bottom:8px;'>Visualisasi Alokasi vs Penagihan (Total: " . number_format($totalProjectDppSetting) . "):</div>";

        echo "<div style='display:flex;height:30px;border-radius:5px;overflow:hidden;margin-bottom:16px;background:#eee;'>";
        if ($dpPct > 0) {
            echo "<div style='display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:bold;background:#5bc0de;width:" . number_format($dpPct, 2) . "%;'>DP " . number_format($dpPct, 2) . "%</div>";
        }
        if ($terminIssuedPct > 0) {
            echo "<div style='display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:bold;background:#5cb85c;width:" . number_format($terminIssuedPct, 2) . "%;'>Termin Terbit " . number_format($terminIssuedPct, 2) . "%</div>";
        }
        if ($remainingPct > 0) {
            echo "<div style='display:flex;align-items:center;justify-content:center;color:#777;font-size:12px;font-weight:bold;background:#d9d9d9;width:" . number_format($remainingPct, 2) . "%;'>Sisa Termin (DP/Retensi) " . number_format($remainingPct, 2) . "%</div>";
        }
        if ($retensiPct > 0) {
            echo "<div style='display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:bold;background:#f0ad4e;width:" . number_format($retensiPct, 2) . "%;'>Retensi " . number_format($retensiPct, 2) . "%</div>";
        }
        echo "</div>";

        echo "<table class='table table-bordered no-margin'>";
        echo "<thead>";
        echo "<tr style='background:#f8f9fa;'>";
        echo "<th>Komponen Biaya</th>";
        echo "<th class='text-right'>Alokasi (%)</th>";
        echo "<th class='text-right'>Alokasi DPP</th>";
        echo "<th class='text-right'>Alokasi PPN</th>";
        echo "<th class='text-center'>Alokasi<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Bruto Ditagih<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Retur/Pembatalan<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Neto Ditagih<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Neto Dibayar<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Sisa Neto<br>(Incl.PPN)</th>";
        echo "<th class='text-center'>Aksi Utama</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        echo "<tr>";
        echo "<td><strong>Uang Muka (DP)</strong></td>";
        echo "<td class='text-right'>" . number_format($dpPct, 2) . "%</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['alokasiDpp']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['alokasiPpn']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['alokasiIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['brutoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['returIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['netoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($dpSummary['netoDibayarIncl']) . "</td>";
        $dpSisaClass = $dpSummary['sisaNetoIncl'] > 0 ? "text-bold text-red" : "text-bold text-success";
        echo "<td class='text-right " . $dpSisaClass . "'>" . number_format($dpSummary['sisaNetoIncl']) . "</td>";
        echo "<td class='text-center'>";
        echo "<button type='button' class='btn btn-xs btn-primary' $btnDpDisabled onclick=\"applyTypePayment('dp', true)\">Terbitkan DP</button> ";
        echo "<button type='button' class='btn btn-xs btn-default' data-toggle='modal' data-target='#$reconModalDpId'>Lihat Sumber</button>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><strong>Termin Progres</strong></td>";
        echo "<td class='text-right'>" . number_format($terminAllocPct, 2) . "%</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['alokasiDpp']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['alokasiPpn']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['alokasiIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['brutoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['returIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['netoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($terminSummary['netoDibayarIncl']) . "</td>";
        $terminSisaClass = $terminSummary['sisaNetoIncl'] > 0 ? "text-bold text-red" : "text-bold text-success";
        echo "<td class='text-right " . $terminSisaClass . "'>" . number_format($terminSummary['sisaNetoIncl']) . "</td>";
        echo "<td class='text-center'>";
        echo "<button type='button' class='btn btn-xs btn-info' $btnTerminDisabled onclick=\"applyTypePayment('termin', true)\">Terbitkan Termin</button> ";
        echo "<button type='button' class='btn btn-xs btn-default' data-toggle='modal' data-target='#$reconModalTerminId'>Lihat Sumber</button>";
        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><strong>Retensi</strong></td>";
        echo "<td class='text-right'>" . number_format($retensiPct, 2) . "%</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['alokasiDpp']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['alokasiPpn']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['alokasiIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['brutoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['returIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['netoDitagihIncl']) . "</td>";
        echo "<td class='text-right'>" . number_format($retensiSummary['netoDibayarIncl']) . "</td>";
        $retensiSisaClass = $retensiSummary['sisaNetoIncl'] > 0 ? "text-bold text-red" : "text-bold text-success";
        echo "<td class='text-right " . $retensiSisaClass . "'>" . number_format($retensiSummary['sisaNetoIncl']) . "</td>";
        if ($sumRetensiDpp <= 0) {
            echo "<td class='text-center'><small>(Tidak Ada Retensi)</small><br><button type='button' class='btn btn-xs btn-default' disabled>Lihat Sumber</button></td>";
        }
        else if ($retensiReadyToBill > 0) {
            echo "<td class='text-center'><button type='button' class='btn btn-xs btn-warning' $btnRetensiDisabled onclick=\"applyTypePayment('retensi', true)\">Terbitkan Retensi</button> <button type='button' class='btn btn-xs btn-default' data-toggle='modal' data-target='#$reconModalRetensiId'>Lihat Sumber</button></td>";
        }
        else {
            echo "<td class='text-center'><small>(Menunggu Jatuh Tempo)</small><br><button type='button' class='btn btn-xs btn-default' data-toggle='modal' data-target='#$reconModalRetensiId'>Lihat Sumber</button></td>";
        }
        echo "</tr>";
        echo "</tbody>";
        echo "<tfoot>";
        echo "<tr style='background:#eee;font-weight:bold;'>";
        echo "<td colspan='9' class='text-right'>Total Sisa Saldo Di Modul Termin (Incl.PPN):</td>";
        echo "<td colspan='2' style='font-size:14px;'>Rp " . number_format($totalSisaNetoIncl) . "</td>";
        echo "</tr>";
        echo "</tfoot>";
        echo "</table>";

        echo "<div class='modal fade project-recon-source-modal' id='$reconModalDpId' tabindex='-1' role='dialog'>";
        echo "<div class='modal-dialog modal-lg' role='document'><div class='modal-content'>";
        echo "<div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button><h4 class='modal-title'>Sumber Data Penagihan - DP</h4></div>";
        echo "<div class='modal-body'>";
        echo "<div class='table-responsive'><table class='table table-bordered table-striped no-margin'>";
        echo "<thead><tr><th class='text-center' style='width:40px;'>No</th><th>Tgl</th><th>Nomer</th><th class='text-right'>DPP</th><th class='text-right'>PPN</th><th class='text-center'>Bruto<br>(Incl.PPN)</th><th class='text-center'>Retur/Pembatalan<br>(Incl.PPN)</th><th class='text-center'>Neto Ditagih<br>(Incl.PPN)</th><th class='text-center'>Neto Dibayar<br>(Incl.PPN)</th><th class='text-center'>Sisa Neto<br>(Incl.PPN)</th><th class='text-center'>Status</th></tr></thead>";
        echo "<tbody>" . $reconSourceDp['rowsHtml'] . "</tbody>";
        echo "<tfoot><tr style='background:#f5f5f5;font-weight:bold;'><td colspan='3' class='text-right'>Total</td><td class='text-right'>" . number_format($reconSourceDp['totalDpp']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalPpn']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalIncl']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalReturnedIncl']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalNetIncl']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalPaidIncl']) . "</td><td class='text-right'>" . number_format($reconSourceDp['totalOutstandingIncl']) . "</td><td class='text-center'>" . number_format($reconSourceDp['count']) . " row</td></tr></tfoot>";
        echo "</table></div></div>";
        echo "</div></div></div>";

        echo "<div class='modal fade project-recon-source-modal' id='$reconModalTerminId' tabindex='-1' role='dialog'>";
        echo "<div class='modal-dialog modal-lg' role='document'><div class='modal-content'>";
        echo "<div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button><h4 class='modal-title'>Sumber Data Penagihan - Termin</h4></div>";
        echo "<div class='modal-body'>";
        echo "<div class='table-responsive'><table class='table table-bordered table-striped no-margin'>";
        echo "<thead><tr><th class='text-center' style='width:40px;'>No</th><th>Tgl</th><th>Nomer</th><th class='text-right'>DPP</th><th class='text-right'>PPN</th><th class='text-center'>Bruto<br>(Incl.PPN)</th><th class='text-center'>Retur/Pembatalan<br>(Incl.PPN)</th><th class='text-center'>Neto Ditagih<br>(Incl.PPN)</th><th class='text-center'>Neto Dibayar<br>(Incl.PPN)</th><th class='text-center'>Sisa Neto<br>(Incl.PPN)</th><th class='text-center'>Status</th></tr></thead>";
        echo "<tbody>" . $reconSourceTermin['rowsHtml'] . "</tbody>";
        echo "<tfoot><tr style='background:#f5f5f5;font-weight:bold;'><td colspan='3' class='text-right'>Total</td><td class='text-right'>" . number_format($reconSourceTermin['totalDpp']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalPpn']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalIncl']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalReturnedIncl']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalNetIncl']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalPaidIncl']) . "</td><td class='text-right'>" . number_format($reconSourceTermin['totalOutstandingIncl']) . "</td><td class='text-center'>" . number_format($reconSourceTermin['count']) . " row</td></tr></tfoot>";
        echo "</table></div></div>";
        echo "</div></div></div>";

        echo "<div class='modal fade project-recon-source-modal' id='$reconModalRetensiId' tabindex='-1' role='dialog'>";
        echo "<div class='modal-dialog modal-lg' role='document'><div class='modal-content'>";
        echo "<div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button><h4 class='modal-title'>Sumber Data Penagihan - Retensi</h4></div>";
        echo "<div class='modal-body'>";
        echo "<div class='table-responsive'><table class='table table-bordered table-striped no-margin'>";
        echo "<thead><tr><th class='text-center' style='width:40px;'>No</th><th>Tgl</th><th>Nomer</th><th class='text-right'>DPP</th><th class='text-right'>PPN</th><th class='text-center'>Bruto<br>(Incl.PPN)</th><th class='text-center'>Retur/Pembatalan<br>(Incl.PPN)</th><th class='text-center'>Neto Ditagih<br>(Incl.PPN)</th><th class='text-center'>Neto Dibayar<br>(Incl.PPN)</th><th class='text-center'>Sisa Neto<br>(Incl.PPN)</th><th class='text-center'>Status</th></tr></thead>";
        echo "<tbody>" . $reconSourceRetensi['rowsHtml'] . "</tbody>";
        echo "<tfoot><tr style='background:#f5f5f5;font-weight:bold;'><td colspan='3' class='text-right'>Total</td><td class='text-right'>" . number_format($reconSourceRetensi['totalDpp']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalPpn']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalIncl']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalReturnedIncl']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalNetIncl']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalPaidIncl']) . "</td><td class='text-right'>" . number_format($reconSourceRetensi['totalOutstandingIncl']) . "</td><td class='text-center'>" . number_format($reconSourceRetensi['count']) . " row</td></tr></tfoot>";
        echo "</table></div></div>";
        echo "</div></div></div>";

        echo "<div class='alert alert-info' style='margin-top:10px;margin-bottom:0;'>";
        echo "<strong>Informasi:</strong> Saldo di modul termin mencakup alokasi Uang Muka dan Retensi yang belum ditarik. ";
        echo "Gunakan tombol \"Terbitkan\" di atas untuk memproses penagihan agar saldo termin menjadi nol.";
        echo "</div>";

        $columnRecorderBase = isset($columnRecorderTarget) ? rtrim($columnRecorderTarget, "/") : "";
        echo "<input type='hidden' id='recon_dp_ready' value='" . round($dpReadyToBill) . "'>";
        echo "<input type='hidden' id='recon_termin_ready' value='" . round($terminReadyToBill) . "'>";
        echo "<input type='hidden' id='recon_retensi_ready' value='" . round($retensiReadyToBill) . "'>";
        echo "<input type='hidden' id='type_payment_default' value='" . $selectedTypePayment . "'>";
        echo "<input type='hidden' id='column_recorder_target' value='" . htmlspecialchars($columnRecorderBase, ENT_QUOTES, 'UTF-8') . "'>";
        $typePaymentRecorderTarget = $columnRecorderBase !== "" ? ($columnRecorderBase . "/type_payment") : "";
        echo "<input type='hidden' id='type_payment_recorder_target' value='" . htmlspecialchars($typePaymentRecorderTarget, ENT_QUOTES, 'UTF-8') . "'>";

        echo "</div>";
        echo "</div>";
        echo "</div>";

        echo "</div>";

        echo "<hr>";
        echo "<style>
            .project-recon-source-modal{z-index:2050 !important;}
            .project-recon-source-modal .modal-dialog{
                width:96%;
                max-width:1400px;
                margin:24px auto 0 auto;
            }
            .project-recon-source-backdrop{z-index:2040 !important;}
            @media (max-width:767px){
                .project-recon-source-modal .modal-dialog{
                    width:auto;
                    margin:10px;
                }
            }
        </style>";
        echo "

        <script>
            function initReconSourceModal(){
                if(typeof $ === 'undefined' || typeof $.fn.modal !== 'function'){
                    return;
                }
                $('.project-recon-source-modal').each(function(){
                    var modalEl = $(this);
                    if(modalEl.attr('data-recon-modal-init') === '1'){
                        return;
                    }
                    modalEl.attr('data-recon-modal-init', '1');

                    modalEl.on('show.bs.modal', function(){
                        var selfEl = $(this);
                        var parentScrollTop = $(window).scrollTop();
                        var parentBodyEl = null;
                        var parentModalEl = $(document).find('.modal.in').not(selfEl).last();
                        if(parentModalEl.length > 0){
                            parentBodyEl = parentModalEl.find('.modal-body:first');
                            if(parentBodyEl.length > 0){
                                parentScrollTop = parentBodyEl.scrollTop();
                            }
                        }

                        selfEl.data('recon-parent-scroll', parentScrollTop);
                        selfEl.data('recon-parent-body', parentBodyEl);

                        if(!selfEl.parent().is('body')){
                            selfEl.appendTo('body');
                        }

                        selfEl.css('z-index', 2050);
                        setTimeout(function(){
                            $('.modal-backdrop').last().addClass('project-recon-source-backdrop');
                            selfEl.find('.modal-dialog:first').css('margin-top', '24px');
                        }, 0);
                    });

                    modalEl.on('hidden.bs.modal', function(){
                        var selfEl = $(this);
                        $('body').addClass('modal-open');
                        var parentBodyEl = selfEl.data('recon-parent-body');
                        var parentScrollTop = parseInt(selfEl.data('recon-parent-scroll'), 10);
                        if(parentBodyEl && parentBodyEl.length > 0){
                            if(isNaN(parentScrollTop)){
                                parentScrollTop = 0;
                            }
                            parentBodyEl.scrollTop(parentScrollTop);
                        }
                    });
                });
            }

            function runResultRequest(targetUrl, doneCallback){
                var doneTriggered = false;
                var finish = function(){
                    if(doneTriggered){
                        return;
                    }
                    doneTriggered = true;
                    if(typeof doneCallback === 'function'){
                        doneCallback();
                    }
                };

                var topResultEl = null;
                if(typeof top !== 'undefined' && top.document){
                    topResultEl = top.document.getElementById('result');
                }
                var localResultEl = document.getElementById('result');
                var resultEl = topResultEl ? topResultEl : localResultEl;

                if(resultEl && resultEl.tagName && resultEl.tagName.toLowerCase() == 'iframe'){
                    resultEl.src = targetUrl;
                    setTimeout(finish, 250);
                    return;
                }

                if(typeof top !== 'undefined' && top.$ && top.$('#result').length > 0){
                    top.$('#result').load(targetUrl, function(){
                        finish();
                    });
                    setTimeout(finish, 800);
                    return;
                }

                if($('#result').length > 0){
                    $('#result').load(targetUrl, function(){
                        finish();
                    });
                    setTimeout(finish, 800);
                    return;
                }

                if(resultEl){
                    resultEl.src = targetUrl;
                    setTimeout(finish, 250);
                    return;
                }

                finish();
            }

            function buildColumnRecorderUrl(columnName, value, skipRefresh){
                var recorderBase = ($('#column_recorder_target').val() + '').replace(/\/+$/, '');
                if(recorderBase == ''){
                    return '';
                }

                var requestUrl = recorderBase + '/' + columnName + '?val=' + encodeURIComponent(value);
                if(skipRefresh === true){
                    requestUrl += '&norefresh=1';
                }

                return requestUrl;
            }

            function recordProjectDescription(descValue, skipRefresh, doneCallback){
                var requestUrl = buildColumnRecorderUrl('description', descValue, (skipRefresh === true));
                if(requestUrl == ''){
                    if(typeof doneCallback === 'function'){
                        doneCallback();
                    }
                    return;
                }

                runResultRequest(requestUrl, doneCallback);
            }

            function persistTypePayment(mode, doneCallback){
                var recorderTarget = ($('#type_payment_recorder_target').val() + '').replace(/\/+$/, '');
                if(recorderTarget == ''){
                    if(typeof doneCallback === 'function'){
                        doneCallback();
                    }
                    return;
                }

                var descVal = $('#desc_notes').length > 0 ? $('#desc_notes').val() : '';
                var requestUrl = recorderTarget + '/?val=' + encodeURIComponent(mode) + '&norefresh=1';
                requestUrl += '&valCol=description&valValue=' + encodeURIComponent(descVal);
                runResultRequest(requestUrl, doneCallback);
            }

            function resolveDescNotesValueForRequest(){
                if(typeof top !== 'undefined' && top.$ && top.$('#desc_notes').length > 0){
                    return top.$('#desc_notes').val();
                }
                if(typeof $ !== 'undefined' && $('#desc_notes').length > 0){
                    return $('#desc_notes').val();
                }
                return '';
            }

            function fillAutoDescription(nominalDpp, ket){
                if($('#desc_notes').length < 1){
                    return;
                }
                if((ket + '') == ''){
                    return;
                }
                var projectName = $('#projectName').val();
                var ppnFactor = $('#ppnFactor').val();
                var nilai_incl_ppn = (nominalDpp*1)*(1+ppnFactor/100)*1;
                $('#desc_notes').val('Project: ' + projectName + '<br>- ' + ket + '<br>- ' + '('+ addCommas(Math.round(nilai_incl_ppn)) +') Incl.PPn');
            }

            function resolveTypePaymentNoteLabel(modeVal){
                var mode = modeVal;
                if((mode + '') == ''){
                    mode = $('#type_payment_project').length > 0 ? ($('#type_payment_project').val() + '') : '';
                }
                mode = (mode + '').toLowerCase();
                if(mode == 'dp'){
                    return 'DP Project';
                }
                if(mode == 'retensi'){
                    return 'Retensi Project';
                }
                if(mode == 'termin'){
                    return 'Termin Project';
                }
                return '';
            }

            function setAutoNoteLabel(labelVal){
                var lbl = (labelVal + '');
                if(typeof $ !== 'undefined' && $.trim){
                    lbl = $.trim(lbl);
                }
                window.autoTypePaymentLabel = lbl;
            }

            function syncAutoDescriptionFromEntry(forcedLabel){
                var noteLabel = (forcedLabel + '');
                if(typeof $ !== 'undefined' && $.trim){
                    noteLabel = $.trim(noteLabel);
                }
                if(noteLabel == ''){
                    noteLabel = (window.autoTypePaymentLabel + '');
                    if(typeof $ !== 'undefined' && $.trim){
                        noteLabel = $.trim(noteLabel);
                    }
                }
                if(noteLabel == ''){
                    noteLabel = resolveTypePaymentNoteLabel('');
                }
                if(noteLabel == ''){
                    return;
                }

                var rawNilaiEntry = $('#nilai_entry').length > 0 ? ($('#nilai_entry').val() + '') : '0';
                var nominalDpp = parseNominalInput(rawNilaiEntry);
                if(nominalDpp <= 0){
                    return;
                }
                fillAutoDescription(nominalDpp, noteLabel);
            }

            function parseNominalInput(rawVal){
                var s = (rawVal + '');
                if(s == ''){
                    return 0;
                }
                s = s.replace(/[^0-9\-]/g, '');
                if(s == '' || s == '-'){
                    return 0;
                }
                return parseInt(s, 10) || 0;
            }

            function triggerEntriNilai(nilai,jenis='',ket=''){
                var trimNilai = removeCommas(trim(nilai))*1;
                var nilai_entry = $('#nilai_entry');
                if(ket!=''){
                    setAutoNoteLabel(ket);
                    fillAutoDescription(trimNilai, ket);
                }
                else{
                    setAutoNoteLabel(resolveTypePaymentNoteLabel(''));
                    syncAutoDescriptionFromEntry('');
                }
                if( !$(nilai_entry).prop('disabled') ){
                    var normalizedTarget = Math.round(trimNilai*1);
                    if(normalizedTarget < 0){
                        normalizedTarget = 0;
                }
                    var currentVal = parseNominalInput($(nilai_entry).val());
                    var defaultVal = parseNominalInput($(nilai_entry).prop('defaultValue'));
                    $(nilai_entry).val(addCommas(normalizedTarget));
                    if(currentVal !== normalizedTarget || defaultVal !== normalizedTarget){
                        $(nilai_entry).trigger('blur');
                    }
                }
                else{
                    swal('tidak bisa input, karena nilai entry disabled / tidak bisa di input.');
                }
            }

            function applyBillingNominal(nominalDpp, jenis, ket){
                var nominal = Math.round((nominalDpp*1));
                if(nominal < 0){
                    nominal = 0;
                }
                if(nominal <= 0){
                    return false;
                }
                triggerEntriNilai(addCommas(nominal), jenis, ket);
                return true;
            }

            function applyTypePayment(typePayment, persistState){
                var mode = (typePayment + '').toLowerCase();
                var hint = $('#type_payment_hint');
                var dpReady = removeCommas($('#recon_dp_ready').val())*1;
                var terminReady = removeCommas($('#recon_termin_ready').val())*1;
                var retensiReady = removeCommas($('#recon_retensi_ready').val())*1;
                var currentNilaiEntry = $('#nilai_entry').length > 0 ? parseNominalInput($('#nilai_entry').val()) : 0;
                var shouldForceNominal = (persistState === true) || (currentNilaiEntry <= 0);
                var postPersistAction = function(){};

                if(mode == 'dp'){
                    setAutoNoteLabel('DP Project');
                    if(shouldForceNominal){
                        fillAutoDescription(dpReady, 'DP Project');
                        postPersistAction = function(){
                            applyBillingNominal(dpReady, 'uangmuka', '');
                        };
                    }
                    else{
                        syncAutoDescriptionFromEntry('DP Project');
                        postPersistAction = function(){
                            syncAutoDescriptionFromEntry('DP Project');
                        };
                    }
                    hint.html('<span class=\'text-blue text-bold\'>Mode DP aktif.</span>');
                }
                else if(mode == 'retensi'){
                    setAutoNoteLabel('Retensi Project');
                    if(shouldForceNominal){
                        fillAutoDescription(retensiReady, 'Retensi Project');
                        postPersistAction = function(){
                            applyBillingNominal(retensiReady, 'retensi', '');
                        };
                    }
                    else{
                        syncAutoDescriptionFromEntry('Retensi Project');
                        postPersistAction = function(){
                            syncAutoDescriptionFromEntry('Retensi Project');
                        };
                    }
                    hint.html('<span class=\'text-orange text-bold\'>Mode Retensi aktif.</span>');
                }
                else if(mode == 'termin'){
                    mode = 'termin';
                    setAutoNoteLabel('Termin Project');
                    syncAutoDescriptionFromEntry('Termin Project');
                    hint.html('<span class=\'text-green text-bold\'>Mode Termin aktif.</span>');
                    if(terminReady <= 0){
                        hint.append(' <span class=\'text-muted\'>Sisa termin progres: 0</span>');
                    }
                }
                else{
                    mode = '';
                    setAutoNoteLabel('');
                    hint.html('<span class=\'text-red text-bold\'>Type Payment wajib dipilih.</span>');
                }

                if($('#type_payment_project').length > 0 && $('#type_payment_project').val() != mode){
                    $('#type_payment_project').val(mode);
                }

                if(persistState === true){
                    persistTypePayment(mode, function(){
                        postPersistAction();
                    });
                }
                else{
                    postPersistAction();
                }
            }

            $(function(){
                initReconSourceModal();
                window.autoTypePaymentLabel = '';
                $(document).off('keyup.autoNoteSync change.autoNoteSync blur.autoNoteSync', '#nilai_entry');
                $(document).on('keyup.autoNoteSync change.autoNoteSync blur.autoNoteSync', '#nilai_entry', function(){
                    syncAutoDescriptionFromEntry('');
                });
                var defaultMode = $('#type_payment_project').length > 0 ? $('#type_payment_project').val() : $('#type_payment_default').val();
                applyTypePayment(defaultMode, false);
            });
        </script>

        ";

        //endregion DP, Termin dan Garansi

        /*============end tambahan*/
        $jmlKolomHeader = sizeof($itemLabels) + 2;
        // cekHijau($jmlKolomHeader);
        echo "<div class='table-responsive no-padding no-border'>";
        /*=============== BADGE PPN / NON PPN =================*/
        if (sizeof($arrHeaderElement) > 0) {
            foreach ($arrHeaderElement as $el => $eDetails) {
                $elLabel = $eDetails['label'];
                $elClass = $eDetails['class'];
                echo "<div class='$elClass'>";
                echo "<div class='text-center text-bold bg-yellow'> $elLabel </div>";
                foreach ($eDetails['subElements'] as $sels => $seDetails) {
                    $selsLabel = $seDetails['label'];
                    $selsValue = $seDetails['value'];
                    $selsMainTarget = $seDetails['srcMain'];
                    $selsItemsTarget = $seDetails['srcItem'];
                    $mainOverwrite = $seDetails['overWriteMain'];
                    $currentPPN = isset($main[$selsMainTarget]) ? $main[$selsMainTarget] : 0;
                    $ppnPersenItems = isset($items[0]['ppnVendor']) ? $items[0]['ppnVendor'] : 0;
                    $autoTerapkan = ($ppnPersenItems != $currentPPN) && ($selsValue == $currentPPN) ? true : false;
                    $checked = $selsValue == $currentPPN ? "checked" : "";
                    $jenisTr = isset($arrHeaderElementJenis) ? $arrHeaderElementJenis : "";
                    // cekhitam($checked."$currentPPN");
                    echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_ppn' value='$selsValue' $checked 
                              onclick=\"$('#result').load('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn='+this.value+'&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite');\">
                              <span>$selsLabel</span>
                          </label>";
                }
                echo "</div>";
            }
        }
        /*=============== BADGE PPN / NON PPN =================*/
        echo "<table class='table dataTable compact table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                echo "<tr class='bg-grey-2 text-uppercase'>";
                echo "<th style='width:1%;' class='text-muted text-center'>";
                echo "NO";
                echo "</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    echo $label;
                    echo "</th>";
                }
                //----------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "V";
                    echo "</th>";
                }
                //----------
                if (!$avoidRemove) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "x";
                    echo "</th>";
                }
                echo "</tr>";
            }
        }

        /*===============body shoping cart=======================================*/
        $no = 0;
        foreach ($items as $iSpec) {

            if ($showItems) {

                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }

                echo "<tr style='xbackground-color: #ffb301 !important;' items line='" . __LINE__ . "' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td style='vertical-align:middle; width:1%' class='text-center'>";
                echo $no;
                echo "</td>";
                $colCtr = 0;
                $queryParams = "";
                $colID = array();
                $listMode = array();
                $readOnly = array();
                $qtyParam = "";
                if (isset($itemLabels['jml'])) {
                    $qtyParam = "+removeCommas(document.getElementById('jml_$no').value)";
                }
                foreach ($itemLabels as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                        if (isset($keyupAction) && $keyupAction == true) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                    }
                    else {
                    }
                    if (strlen($keyupEvent[$key]) > 2) {
                        $keyUpStr[$key] = " onkeyup=\"" . $keyupEvent[$key] . "\" ";
                    }
                    if (in_array($key, $editableFields)) {
                        $readOnly[$key] = "";
                        if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                            $readOnly[$key] = "readonly_xz";
                        }
                        if (isset($paramsForceEditable[$key])) {
                            if ($paramsForceEditable[$key] == true) {

                            }
                            else {
                                $readOnly[$key] = "readonly_xxz";
                                $listMode[$key] = "text";
                            }
                        }
                    }
                    else {
                        $readOnly[$key] = "readonly_xxz";
                        $listMode[$key] = "text";
                    }
                    $colID[$key] = $key . "_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
                }
                foreach ($itemLabels as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    echo "<td align='left'>";
                    $colID = $key . "_" . $no;
                    $keyID = $key;
                    $noID = $no;
                    $tabIndexNum = $colCtr . $no;
                    $fieldVal = "";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        if (isset($iSpec[$key])) {
                            if (is_numeric($iSpec[$key])) {
                                $fieldVal = $iSpec[$key] + 0;
                            }
                            else {
                                $fieldVal = $iSpec[$key];
                            }
                        }
                    }
                    if (sizeof($minValues) > 0) {
                        $moq = isset($minValues['moq'][$iID]) ? $minValues['moq'][$iID] : 0;
                        $validateKey_up = true;
                    }
                    else {
                        $moq = 0;
                        $validateKey_up = false;
                    }
                    $keyupData = (($key == "qty" || $key == "jml") && $validateKey_up == true) ? "onkeydown=\"if(parseInt(this.value)<$moq){setTimeout(function(){ this.value='" . $iSpec[$key] . "'}, 1000);} \"" : "";
                    switch ($listMode[$key]) {
                        case "input":
                            echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseout=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';} \" ";
                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    echo str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        echo $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        echo $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                echo $pemicuGerbangAsli;
                            }
                            if (isset($keyupAction) && $keyupAction == true) {
                                echo "onkeyup=\"document.getElementById('result').src='" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams';if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            echo ">";
                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                    }
                    echo "</td>";
                }

                //-----------------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
                        $ceklist_checked = "checked";
                    }
                    else {
                        $ceklist_checked = "";
                    }
                    echo "<td width='1%'>";
                    echo "<input type='checkbox' $ceklist_checked 
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    echo "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    echo "<td width='1%'>";
                    echo "<a class='text-red btn' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                    echo "</td>";
                }
                //endregion

                echo "</tr>";

                echo "
            <script>
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
            </script>
            ";

                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . $colspan . "'>";
                    $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                    if (isset($noteType)) {
                        switch ($noteType) {
                            case "textarea":
                                echo "<textarea class='form-control' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >$noteVal</textarea>";
                                break;
                            case "text":
                            default:
                                echo "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                break;
                        }
                    }

                    echo "</td>";
                    if ($imageEnable == true) {
                        echo "<td colspan='2'>";
                        $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                        if (isset($imageType)) {
                            switch ($imageType) {
                                case "images":
                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    echo "<div class='input-groups'>";
                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";
                                        echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                        echo "<input type='hidden' name='img_$iID' value='$file'>";
                                    }
                                    echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";
                                    echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";
                                    echo "</form>";
                                    echo "</div>";
                                    break;
                                case "text":
                                default:
                                    echo "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                onmouseout=\"if(this.value!=this.defaultValue){hiliteDiv(this);document.getElementById('result').src='" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID';}\"
                                >";
                                    break;
                            }
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                if ($pairedItemEnabled == true) {
                    if (sizeof($pairedItemField) > 0) {
                        $listModePairedItem = array();
                        $readOnlyPairedItem = array();
                        foreach ($pairedItemField as $key => $label) {
                            $listModePairedItem[$key] = "input";
                            if (in_array($key, $editableFields)) {
                                $readOnlyPairedItem[$key] = "";
                                if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                                    $readOnlyPairedItem[$key] = "readonly_x";
                                }
                            }
                            else {
                                $readOnlyPairedItem[$key] = "readonly_xx";
                                $listModePairedItem[$key] = "text";
                            }
                        }
                    }
                    echo "<tr>";
                    echo "<td>&nbsp;</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                    echo "<td colspan='" . $c_colspan . "'>";
                    //==pairedItems, if any
                    if (isset($selItems) && sizeof($selItems) > 0) {
                        echo "<select
                                title='Choose one of the following...'
                                data-header='Ketik Nama/Kode/Folder/Barcode'
                                data-size='10'
                                data-container='body'
                                class='picker_$iID selectpicker form-control select2 show-tick'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control'
                                onchange=\"document.getElementById('result').src='" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID'\"
                                >";

                        asort($selItems);

                        foreach ($selItems as $piID => $piName) {
                            if ($piID != $iSpec['id']) {
                                $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
                                $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
                                $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
                                $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
                                $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
                                echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
                            }
                        }

                        echo "</select>";

                    }

                    echo "</td>";

//                echo "<script>top.$('.select2').selectpicker();</script>";
//                echo "<script> setTimeout( function(){ top.$('.picker_$iID').selectpicker(); console.log('dari shopingcart picker_$iID') }, 100 ); </script>";

                    echo "<script> $('.picker_$iID').selectpicker(); </script>";

//                echo "<script> setTimeout( function(){ top.$('.select2').selectpicker(); console.log('dari shopingcart') }, 500 ); </script>";

                    if (sizeof($pairedItemField) > 0) {
                        foreach ($pairedItemField as $key => $label) {
                            $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
                            $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
                            $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
                            echo "<td>";
                            switch ($listMode[$key]) {
                                case "input":
                                    echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    onmouseout=\"document.getElementById('result').src='" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID';\"
                                    >";
                                    break;
                                case "text":
                                    if (is_numeric($fieldVal)) {
                                        echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                    }
                                    else {
                                        echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                    }
                                    break;
                            }
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }
            }


        }

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {
            echo "<tr class='bg-info items2'>";
            echo "<td colspan='$jmlKolomHeader'>";

            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table id='items2' class='table dataTable compact table-condensed no-padding table-bordered no-margin'>";

            if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items2 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
                    if (isset($items2_sum_kurang[$iID])) {
                        $bgColor = "yellow";
                    }
                }
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels2 as $key => $label) {
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                }
                foreach ($itemLabels2 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;
                    if (is_numeric($iSpec[$key])) {
                        echo formatField($key, $iSpec[$key]);
                    }
                    else {
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        //region items3
        if (isset($items3) && sizeof($items3) > 0) {
            echo "<tr class='bg-info items3'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table id='items3' class='table dataTable compact table-condensed no-padding table-bordered no-margin'>";

            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels3 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items3 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels3 as $key => $label) {
                    //                if(in_array($key,$editableFields)){
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    //                }
                }

                foreach ($itemLabels3 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    else {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion


            if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                $nr = 0;
                foreach ($sumRows3 as $key => $label) {
                    $val = 0;
                    $nr++;
                    $bottom_borderless = $nr < sizeof($sumRows3) ? "bottom-borderless" : "";

                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($addValues[$key]) && $addValues[$key] > 0) {
                            $val = $addValues[$key];
                        }
                    }

                    echo "<tr class='bg-grey-01 3'>";
                    echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td class='right-borderlesss'>";
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        //region items4
        if (isset($items4) && sizeof($items4) > 0) {
            echo "<tr class='bg-info items4'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table id='items4' class='table dataTable compact table-condensed no-padding table-bordered no-margin'>";

            if (sizeof($itemLabels4) && (is_array($itemLabels4)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels4 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items4 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels4 as $key => $label) {
                    //                if(in_array($key,$editableFields)){
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    //                }
                }

                foreach ($itemLabels4 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    else {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion


            if (isset($sumRows4) && sizeof($sumRows4) > 0) {
                $nr = 0;
                foreach ($sumRows4 as $key => $label) {
                    $val = 0;
                    $nr++;
                    $bottom_borderless = $nr < sizeof($sumRows4) ? "bottom-borderless" : "";

                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($addValues[$key]) && $addValues[$key] > 0) {
                            $val = $addValues[$key];
                        }
                    }

                    echo "<tr class='bg-grey-01 3'>";
                    echo "<td colspan='" . sizeof($itemLabels4) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td class='right-borderlesss'>";
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        //region items5
        if (isset($items5) && sizeof($items5) > 0) {
            echo "<tr style='xbackground-color: #ffb301 !important;' class='bg-info items5'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table id='items5' class='table dataTable compact table-condensed no-padding table-bordered no-margin table-hover'>";
            $captionWoText = "PILIH PADA DAFTAR WORKORDER DIBAWAH INI SEBAGAI KOMPONEN DASAR PENAGIHAN";
            $currentTypePaymentView = isset($_SESSION[$cCode]['main']['type_payment']) ? strtolower(trim($_SESSION[$cCode]['main']['type_payment'])) : "";
            if ($currentTypePaymentView === "uang muka" || $currentTypePaymentView === "uang_muka" || $currentTypePaymentView === "downpayment" || $currentTypePaymentView === "dp") {
                $captionWoText = "KOMPONEN RENCANA ANGGARAN & UANG MUKA PROYEK (DASAR ACUAN DP)";
            }
            else if ($currentTypePaymentView === "garansi" || $currentTypePaymentView === "retensi") {
                $captionWoText = "KOMPONEN REALISASI PENYELESAIAN PROYEK (DASAR PENARIKAN RETENSI / GARANSI)";
            }
            echo "<caption class='text-bold text-red'>$captionWoText</caption>";

            if (sizeof($itemLabels5) && (is_array($itemLabels5)) && $showItems) {
                //region header table anakan
                echo "<thead>";
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels5 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                echo "</thead>";
                //endregion
            }

            $no = 0;
            //region body table anakan
//            arrPrint($items5);
            echo "<tbody>";
            $kurangStoks = array();
            $hrg5 = 0;
            $prg5 = 0;
            $invoiced = 0;
            $sisa = 0;
            $bobot_wo5 = 0;
            foreach ($items5 as $iSpec) {

                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels5 as $key => $label) {
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                }
                foreach ($itemLabels5 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "checklistTarget" || $key == "persen_sub") {
                        $cAlign .= " text-center";
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;
                    if (is_numeric($iSpec[$key])) {
                        echo str_replace('.00', '', number_format($iSpec[$key], 2));
                    }
                    else {
                        if (is_array($iSpec[$key])) {
                            $isProduk = array();
                            foreach ($iSpec[$key] as $jn => $datas) {
                                $isProduk[$jn] = $datas;
                            }

                            echo "<table width='100%' class='table dataTable compact no-border table-hover'>";
                            $total_rp = 0;
                            if (isset($isProduk['produk'])) {
                                foreach ($isProduk['produk'] as $ky => $prdData) {
                                    $qtyLebih = "";
                                    if (!empty($iSpec['kelebihan']['produk'][$prdData['produk_dasar_id']])) {
                                        $qtyLebih = $iSpec['kelebihan']['produk'][$prdData['produk_dasar_id']]['jmlFromWO'] - $iSpec['kelebihan']['produk'][$prdData['produk_dasar_id']]['jmlFromBOM'];
                                    }
                                    echo "<tr class='text-meta text-bold'>";
                                    echo "<td>" . $prdData['produk_dasar_nama'] . "</td>";
                                    echo "<td class='text-bold'>" . $prdData['jml'] . "x</td>";
                                    echo "<td class='text-right'>@" . number_format($prdData['harga']) . "</td>";
                                    echo "<td>&nbsp; $qtyLebih</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td class='text-right'><span class='pull-left'>Rp.</span>" . number_format($prdData['saldo']) . "</td>";
                                    echo "</tr>";
                                    $total_rp += $prdData['saldo'] * 1;
                                }
                            }
                            if (isset($isProduk['biaya'])) {
                                foreach ($isProduk['biaya'] as $ky => $prdData) {
//                                    echo "<tr class='text-olive text-bold'>";
                                    echo "<tr class='text-red text-bold'>";
                                    echo "<td>" . $prdData['produk_dasar_nama'] . "</td>";
                                    echo "<td class='text-bold'>" . $prdData['jml'] . "x</td>";
                                    echo "<td class='text-right'>@" . number_format($prdData['nilai']) . "</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td>&nbsp;</td>";
                                    echo "<td class='text-right'><span class='pull-left'>Rp.</span>" . number_format($prdData['saldo']) . "</td>";
                                    echo "</tr>";
                                    $total_rp += $prdData['saldo'] * 1;
                                }
                            }

                            echo "<tr class='text-purple text-bold'>";
                            echo "<td style='border-top: 1px solid gray;' class='text-bold'>&nbsp;</td>";
                            echo "<td style='border-top: 1px solid gray;'>&nbsp;</td>";
                            echo "<td style='border-top: 1px solid gray;' class='text-right'>&nbsp;</td>";
                            echo "<td style='border-top: 1px solid gray;'>&nbsp;</td>";
                            echo "<td style='border-top: 1px solid gray;'>&nbsp;</td>";
                            echo "<td style='border-top: 1px solid gray;' class='text-right'><span class='pull-left'>Rp.</span>" . number_format($total_rp) . "</td>";
                            echo "</tr>";

                            echo "</table>";
                        }
                        else {
                            echo $iSpec[$key];
                        }
                    }
                    echo "</td>";
                }
                echo "</tr>";

                $hrg5 += $iSpec['nilai_sub_fase'];
                $prg5 += $iSpec['progress_percent'];
                $bobot_wo5 += $iSpec['persen_sub'];
                $invoiced += $iSpec['debet'];
                $sisa += $iSpec['sisa'];
            }
            echo "</tbody>";
            //endregion

            echo "<tfoot>";
            echo "<tr class='bg-grey-2'>";
            echo "<td class=''>&nbsp;</td>";
            echo "<td class=''>&nbsp;</td>";
            echo "<td class='text-right valign-m text-uppercase'>" . number_format($hrg5) . "</td>";
            echo "<td class='text-right valign-m text-uppercase'>" . number_format($bobot_wo5, 2) . "%</td>";
            echo "<td class=''>&nbsp;</td>";
//            echo "<td class='text-right valign-m text-uppercase'>".number_format($invoiced, 2)."</td>";
//            echo "<td class='text-right valign-m text-uppercase'>".number_format($sisa, 2)."</td>";
            echo "<td class=''>&nbsp;</td>";
            echo "</tr>";
            echo "</tfoot>";

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        /*=============================sumrows============================*/
        if (isset($sumRows) && sizeof($sumRows) > 0) {
            $nr = 0;
            foreach ($sumRows as $key => $label) {
                $val = 0;
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows) ? "bottom-borderless" : "";

                if (isset($main[$key]) && $main[$key] > 0) {
                    $val = $main[$key];
                }
                else {
                    if (isset($addValues[$key]) && $addValues[$key] > 0) {
                        $val = $addValues[$key];
                    }
                }

                if ($showItems) {
                    echo "<tr class='bg-grey-01 0'>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td colspan='3' class='right-borderlesss'>";
                    echo "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    echo "</td>";
                    echo "</tr>";
                }

            }
        }

        if (isset($sumRows2) && sizeof($sumRows2) > 0) {

            echo "<!-- ===========sumRows2============= -->";
            echo "<tr bgcolor='#e0e0e0'>";
            echo "<td colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</td>";
            echo "</td>";
            echo "</tr>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";

                echo "<tr line='" . __LINE__ . "' bgcolor='#f0f0f5'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-m text-uppercase'>$label</td>";
                echo "<td>";
                echo $sumSpec2[$key];
                echo "</td>";
                echo "</tr>";
            }
        }

        if (sizeof($addRows) > 0) {
            $typeNoneSelected = $selectedTypePayment == "" ? "selected" : "";
            $typeDpSelected = $selectedTypePayment == "dp" ? "selected" : "";
            $typeTerminSelected = $selectedTypePayment == "termin" ? "selected" : "";
            $typeRetensiSelected = $selectedTypePayment == "retensi" ? "selected" : "";

            echo "<tr class='bg-info'>";
            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right valign-m text-uppercase text-bold'>Type Payment</td>";
            echo "<td colspan='2' class='text-right' style='width:260px;max-width:260px;'>";
            echo "<select id='type_payment_project' class='form-control text-left' onchange=\"applyTypePayment(this.value, true)\">";
            echo "<option value='' $typeNoneSelected>-- Pilih Type Payment --</option>";
            echo "<option value='dp' $typeDpSelected>DP (Uang Muka)</option>";
            echo "<option value='termin' $typeTerminSelected>Termin Progress</option>";
            echo "<option value='retensi' $typeRetensiSelected>Retensi / Garansi</option>";
            echo "</select>";
            echo "<div id='type_payment_hint' class='text-left text-muted' style='margin-top:4px;font-size:11px;line-height:1.25;white-space:normal!important;word-break:break-word;overflow-wrap:anywhere;max-width:240px;'></div>";
            echo "</td>";
            echo "</tr>";

            $nr = 0;
            foreach ($addRowLabels as $k => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr line='" . __LINE__ . "' class='$rowHide'>";
                if ($k == "nilai_entry") {
                    if ($persenProgressAll * 1 > 0) {
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    }
                    else {
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>
                        <div class='col-md-10 alert alert-warning text-left'>
                            <div style='font-size: 14px;' class='alert-header text-bold'><i class='fa fa-warning text-red text-bold'></i> PERHATIAN <i class='fa fa-warning text-red text-bold'></i></div>
                            <div style='margin-top: 12px;' class='alert-body'>
                                <b>PROJECT YANG ANDA PILIH BELUM BISA DITERBITKAN INVOICE.
                                <br>AGAR BISA MENERBITKAN INVOICE, LANGKAH-LANGKAH YANG HARUS DILAKUKAN:</b>
                                <br>1. BUAT SPK/TASKLIST KEPADA PELAKSANA,
                                <br>2. LAKUKAN DISTRIBUSI BAHAN BAKU YANG DIBUTUHKAN,
                                <br>3. PASTIKAN PELAKSANA MELAKUKAN POSTING SPK/TASKLIST YANG TELAH SELESAI.
                            </div>
                        </div>
                        $label
                        </td>";
                    }
                }
                else {
//                    arrPrint($receiptAddSDppTax[$k]);
                    $colspan2 = sizeof($itemLabels);
                    if (isset($receiptAddSDppTax[$k])) {
                        $colspan_1 = $colspan2 - 2;
                        foreach ($receiptAddSDppTax[$k] as $key_dpp => $alias_dpp_key) {
                            echo "<td colspan='$colspan_1' class='text-right text-bold'>$alias_dpp_key</td>";
                            echo "<td>" . formatField_he_format("subtotal", $sumDppTax[$k][$key_dpp]) . "</td>";
                        }
                        $colspan2_1 = "1";
                    }
                    else {
                        $colspan2_1 = $colspan2;
                    }
                    echo "<td colspan='" . $colspan2_1 . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                }
                echo "<td colspan='2' class='text-right'>";
                if ($k == "nilai_entry") {
                    if ($persenProgressAll * 1 > 0) {
                        echo $addRows[$k];
                    }
                    else {
                        echo "<input disabled type='text' id='nilai_entry' class='form-control bg-red text-white text-bold text-right' style='font-size:17px;' placeholder='nilai_entry' value='0'>";
                    }
                }
                else {
                    echo $addRows[$k];
                }
                echo "</td>";
                echo "</tr>";
            }
        }

        //region clear shoping cart
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            echo "<tr class='bg-grey-2'>";
            echo "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";

            echo "<span class='pull-left'>";
            echo "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            echo "</span>";

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        echo "</table class='table'>";
        echo "</div class='table-responsive'>";

        echo "<script>                                                          
                        function labelMencolok(key) {
                            var saldotext = $('#saldo_' + key).text();
                            var num_saldotext = Number(saldotext.replace(/\./g, ''));
                            let nilai_round = Number($('#nilai_round').val());
                            let lebih_bayar = Number($('#lebih_bayar').val());
                            let ketikan = $('#'+ key).val();
                            
                            if(num_saldotext > 0){
                                $('#label_' + key).addClass('text-red text-bold');
                                $('#' + key).prop('disabled', false);
                            }
                            else if(num_saldotext == 0){
                                // $('#label_' + key).addClass('text-red text-bold');
                                 $('#' + key).prop('disabled', true);
                            }
                            
                            
                            let new_sisa = Number($('#new_sisa').val());
                            if(new_sisa <= 0 && lebih_bayar == 0){
                                  $('#nilai_entry').prop('disabled', true);
                            }
                            else {
                                $('#nilai_entry').prop('disabled', false).css('background-color','yellow');
                            }
                            
                                // console.log('item', key);
                                // console.log('nilai_round', nilai_round);
                                // console.log('ketikan', ketikan);
                                // console.log('lebih_bayar', lebih_bayar);
                            if(ketikan > nilai_round){
                                    swal({
                                            title: 'peringatan.. !!',
                                            html: 'maximal value yang bisa digunakan ' + addCommas(nilai_round) + ', sekarang ' + addCommas(ketikan)
                                        });
                                    $('#' + key).css('background-color','#fdb5b5');
                                    
                                    return false; 
                                }
                        }
                                                
                        var labelKeis = ['credit_amount', 'point_konsumen_qtt','uang_muka_dipakai','uang_muka_dipakai_ppn'];                        
                        labelKeis.forEach(function(item) {                        
                            labelMencolok(item);   
                        });

                        labelKeis.forEach(function(item) {
                            $('#' + item).on('blur', function() {
                                let ketikan = $('#'+ item).val();
                                let saldotext = $('#saldo_' + item).text();
                                let num_saldotext = Number(saldotext.replace(/\./g, ''));
                                let nilai_round = Number($('#nilai_round').val());
                                
                                // console.log('item', item);
                                // console.log('nilai_round', nilai_round);
                                // console.log('ketikan', ketikan);
                                // console.log('saldotext', num_saldotext);
                                // if(ketikan > num_saldotext){
                                //     swal({
                                //             title: 'peringatan.. !!',
                                //             // html: 'maximal diskon ' + addCommas(max_diskon_nilai) + ', sekarang ' + addCommas(diskon_nilai) + ' dari ' + addCommas(dpp)
                                //         });
                                // }
                                
                                // if(ketikan > nilai_round){
                                //     swal({
                                //             title: 'peringatan.. !!',
                                //             html: 'maximal yang bisa digunakan ' + addCommas(nilai_round) + ', sekarang ' + addCommas(ketikan)
                                //         });
                                //     $('#' + item).css('background-color','#fdb5b5');
                                // }
                            });                                            
                        });
                        
            </script>";

        //--------
        $faktur = "";
        if (count($shopingCartFakturItems) > 0) {
            if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {

                $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $faktur .= "<table class='table'>";
                $faktur .= "<tr class='bg-primary'>";
                foreach ($shopingCartFakturParam["fields"] as $ff => $ff_abels) {
                    $faktur .= "<th>$ff_abels</th>";
                }
                $faktur .= "</tr>";
                $faktur .= "<tr>";
                $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
//                cekMerah($fff);

                    if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                        $inputType = $shopingCartFakturParam["editableFields"][$fff];

                        $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";

                        if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                            $classinputType = "";
                            $labels = "tic disini jika faktur belum tersedia";
                            $vals = "checked";
                            $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                        }
                        else {
                            $classinputType = "form-control ";
                            $labels = "";
                            $vals = "value";
                            $checked = "";
                        }

                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                    }
                    else {
                        $value = formatField($fff, $shopingCartFakturItems[$fff]);
                    }
                    $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";
                }
                $faktur .= "</tr>";

                $faktur .= "</table>";
                $faktur .= "</div>";
                $faktur .= "<script>
                function eksekutor(nilai,nama) {
//  console.log(nilai);
//  console.log(nama);
  $('#result').load('$linkFaktur?nilai='+nilai+'&nama='+nama)
}

                var skip_faktur = $('#skip_faktur').prop('checked');
                // if (skip_faktur) {
                    // console.log('Checkbox dengan id skip_faktur telah dicentang.');
                // } else {
                    // console.log('Checkbox dengan id skip_faktur belum dicentang.');
                // }

                var dateFaktur = $('#dateFaktur').val();
                var eFaktur = $('#eFaktur').val();

                console.log('skip_faktur:', skip_faktur);
                console.log('dateFaktur:', dateFaktur);
                console.log('eFaktur:', eFaktur);
                console.log('konfirmasi_cek 1421:', konfirmasi_cek);

                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
                    $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                    $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
                    $('#dateFaktur').css('border-color', 'red');
                    // $('#eFaktur').css('border-color', 'red').focus();
                    $('#eFaktur').css('border-color', 'red');

                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);

                    swal({type: 'warning',title: 'Upss..',html: 'Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia'});
                    konfirmasi_cek = false;
                }
                // else if(eFaktur != '' && dateFaktur == '' && skip_faktur == false){
                //     $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                //     $('#dateFaktur').css('border-color', 'red').focus();
                //
                //     $('#skip_faktur').prop('checked', false);
                //      $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                // }
                //  else if(eFaktur != '' && dateFaktur != ''){
                //     // $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
                //     // $('#dateFaktur').css('border-color', 'red').focus();
                //
                //     $('#skip_faktur').prop('checked', false);
                //     // $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                // }
                // else if(skip_faktur == true){
                //     $('#dateFaktur').val('');
                //     $('#eFaktur').val('');
                //
                //     if(konfirmasi_cek == false){
                //         $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                //     }
                // }

                console.log('konfirmasi_cek 1450:', konfirmasi_cek);
                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
                    konfirmasi_cek = false;

                    swal({type: 'warning',title: 'Upss..',html: 'Pilih salah satu Sumber Dana untuk pembayaran 1455'});
                }
                else if(nilai_entry == 0 && isCa == 0) {
                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                }

                $('input[name=\"cash_account\"]').change(function(){
                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
                });

</script>";

            }

        }

        echo $faktur;
        if (isset($fixedNote)) {
            echo "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$fixedNote</span>";
            if (isset($fixedNoteLink)) {
//                arrPrintHijau($fixedNoteLink);
                foreach ($fixedNoteLink as $fixedNoteLink_spec) {
                    $link = isset($fixedNoteLink_spec['link']) ? $fixedNoteLink_spec['link'] : NULL;
                    $labels = isset($fixedNoteLink_spec['label']) ? $fixedNoteLink_spec['label'] : NULL;
                    echo "<span><br>- $labels</span>";
                    if ($link != NULL) {
                        echo "atau <a href=\"$link\"
                                target='_parent'>klik disini</a>.";
                    }
                }
            }
            echo "</div>";
        }

        if (isset($fixedNote)) {
            echo "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$fixedNote</span>";
            echo "</div>";
        }

        /*---------------------sum CBM CKD------------------------------------*/
        $volume_gross = "";
        $berat_gross = "";
        if (isset($detilSizeBar)) {
            if (sizeof($detilSizeBar) > 0) {

                $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;

                $volume = isset($detilSizeBar['volume']) ? $detilSizeBar['volume'] : 0;
                $berat = isset($detilSizeBar['berat']) ? $detilSizeBar['berat'] : 0;


                echo "<div class='row bg-danger' style='background: #ffdecf;padding: 7px;'>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                        </div>
                     </div>";
                echo "</div>";
            }
        }

        //--------
        if (isset($checkOpnameEnabled) && ($checkOpnameEnabled == true)) {
            $noteEncode1 = blobEncode($checkOpnameNote1);
            $noteEncode2 = blobEncode($checkOpnameNote2);

            if (isset($checkOpnameCek1) && ($checkOpnameCek1 == 1)) {
                $ceklist_checked_1 = "checked";
            }
            else {
                $ceklist_checked_1 = "";
            }
            if (isset($checkOpnameCek2) && ($checkOpnameCek2 == 1)) {
                $ceklist_checked_2 = "checked";
            }
            else {
                $ceklist_checked_2 = "";
            }

            $strcekNote = "<br><div class='alert alert-danger' style='text-align: left;'>";

            $strcekNote .= "<input type='checkbox' value='' $ceklist_checked_1
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note1=$noteEncode1';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote1</span>";

            $strcekNote .= "<br><input type='checkbox' value='' $ceklist_checked_2
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note2=$noteEncode2';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote2</span>";

            $strcekNote .= "</div>";
            echo $strcekNote;
        }
        //--------

        if (sizeof($elements) > 0) {
            echo "<div class='panel-body table-responsive'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            echo "</div class='col-md-12'>";
            echo "</div class='row'>";
            echo "<div class='col-lg-12 no-padding text-center' style='text-align:center;'>";
            $elCtr = 0;
            foreach ($elements as $eName => $pSpec) {
                $elCtr++;
                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
                    // type hidden tidak perlu tampil di ui //
                }
                else {
                    //region penampil untuk elemen pada shopingcart
                    if ($elCtr % 2 == 0) {
                    }
                    else {
                        echo "<div class='col-lg-12 no-padding'>";
                        echo "<div class='row row-eq-height'>";
                    }
                    echo "<div class='col-md-6 col-lg-6' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";

                    echo "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";

                    echo $pSpec['label'] . " ";
                    if (isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect']) {

                    }
                    else {
                        echo "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                    }
                    //----------------------------------------
                    if (isset($elementConfigMutasi[$eName])) {
                        echo "&nbsp;&nbsp;&nbsp;<a href='" . $elementConfigMutasi[$eName] . "' target='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                    }
                    //----------------------------------------
                    echo "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";

                    echo "</div class='box-title'>";

                    if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                        echo "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                    }


                    echo "<div class=''>&nbsp;</div>";
                    echo $pSpec['string'];

                    echo "</div>";
                    if ($elCtr % 2 == 0) {
                        echo "</div>";
                        echo "</div>";
                    }
                    //endregion
                }
            }

            echo "</div class='row'>";

            if (isset($showScheme) && sizeof($showScheme) > 0) {

                echo "<div class='clearfix'><hr></div>";
                echo "<div class='col-md-12 no-padding'>";
                echo "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
                echo "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
                echo "<div class='text-center text-danger text-bold'> ========================================== </div>";

                //header skema
                echo "<div class='col-md-12 no-padding'>";

                echo "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";

//                $headerScheme = array(
//                    "nama" => "$nmPemengangSaham",
//                    "jml_pinjaman" => "$nilai_pinjaman",
//                    "bunga_tahunan" => "$rate_bunga",
//                    "awal_meminjam" => "$awal_pinjaman",
//                    "pelunasan_pinjaman" => "$jatuh_tempo",
//                    "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
//                );

                echo "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>";

                echo "</div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
                echo "<tr>  <th width='1%'>No</th>
                            <th>Periode</th>
                            <th>jml hari / periode</th>
                            <th>Pokok Pinjaman</th>
                            <th>Rate Bunga</th>
                            <th>Nilai Bunga</th>
                            <th>PPh23</th>
                            <th>bunga setelah dipotong PPh</th>
                      </tr>";

                echo "</thead><tbody>";

                $total_bunga = 0;
                $total_pph23 = 0;
                $total_bunga_pph23 = 0;
                $total_hari = 0;
                $no = 1;

                foreach ($showScheme as $thnbln => $pinjaman) {

                    $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
                    $bgColor = " ";

                    switch ($setBackground) {
                        default:
                        case "merah":
                            $bgColor = "bg-white";
                            break;
                        case "hijau":
                            $bgColor = "bg-success";
                            break;
                        case "berjalan":
                            $bgColor = "bg-warning";
                            break;
                    }

                    echo "  <tr>
                                <td class='$bgColor'>$no</td>
                                <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
                                <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
                                <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
                            </tr>";

                    $no++;

                    $total_bunga += $pinjaman['nilai_bunga'] * 1;
                    $total_pph23 += $pinjaman['nilai_pph23'] * 1;
                    $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
                    $total_hari += $pinjaman['jml_hari_dbln'] * 1;
                }

                echo "<tfoot>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>" . $total_hari . "</td>
                            <td>-</td>
                            <td>-</td>
                            <td>" . number_format($total_bunga, 0) . "</td>
                            <td>" . number_format($total_pph23, 0) . "</td>
                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
                        </tr>
                    </tfoot>";

                echo "</tbody>
                        </table>
                        </div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div class='text-left'>Keterangan:</div>";
                echo "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
                echo "</div>";
            }

        }

        if (sizeof($inputs) > 0) {
            echo "<div class='col-lg-12 no-padding' style='margin-top:5px;'>";
            echo "<div class='alert alert-info-dot'>";
            echo "<h4 class='text-left'>additional values</h4>";
            echo "<table class='table table-condensed'>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td class='text-muted'>";
                echo $inputLabels[$eName];
                echo "</td>";
            }
            echo "</tr>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td>";
                echo $eStr;
                echo "</td>";
            }
            echo "</div>";
            echo "</div>";
            echo "</tr>";
            echo "</table class='table table-condensed'>";
            echo "</div class='panel-default'>";
            echo "</div class='panel'>";
        }

        if (isset($previewJurnal) && sizeof($previewJurnal) > 0) {
            $headersJurnal = $previewJurnal['header'];
            foreach ($previewJurnal['jurnal'] as $cabangID => $subItems) {
                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($previewJurnal['cabang'][$cabangID]) ? $previewJurnal['cabang'][$cabangID] : "";
                    echo "<h4 class='text-blue' style='text-align: left;margin-top: 10px;'><span class='fa fa-book'></span> preview journal entries ($cabangNama)</h4>";
                    echo "<div class='tabel table-responsive'>";
                    echo "<table class='table table-condensed'>";
                    echo "<tr bgcolor='#f0f0f0'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        echo "$label";
                        echo "</td>";
                    }
                    echo "</tr>";
                    foreach ($subItems as $iSpec) {
                        echo "<tr>";
                        foreach ($headersJurnal as $key => $label) {
                            echo "<td style='text-align: left;'>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                            if (is_numeric($iSpec[$key])) {
                                if (!isset($total[$cabangID][$key])) {
                                    $total[$cabangID][$key] = 0;
                                }
                                $total[$cabangID][$key] += $iSpec[$key];
                            }
                        }
                        echo "</tr>";
                    }
                    echo "<tr style='font-size: 15px;font-weight: bold;'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        if (isset($total[$cabangID][$key])) {
                            echo formatField($key, $total[$cabangID][$key]);
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                    echo "</table>";
                    echo "</div>";
                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
        }

        if (isset($viewDescriptionNote) && ($viewDescriptionNote == true)) {
            echo "<span>Catatan:</span>";
            echo "<div class='box-footer bg-gray'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<textarea id='desc_notes' class='form-control' placeholder='description note'
                  style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\"
                  onblur=\"recordProjectDescription(this.value, true);\"
                >$default_description</textarea>";
            echo "</div class=\"col-md-12\">";
            echo "</div class=\"row\">";
            echo "</div class=\"box-footer bg-gray\">";
        }

        echo "<script>
                if( $('span[keyid=qty_debet]').length > 0 ){
                    top.shoppingCardValidator()
                    //top.console.log('perlu validator shoppingcart');
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }
                $('.checkbox_tasklist').off();
                $('.checkbox_tasklist').on('change', function(a, b){
                    var arrTaskCheck = $('.checkbox_tasklist');
                    var check = {};
                    var totalCheckRp = 0;
                    var totalCheckRpNonTax = 0;
                    var totalCheckPercent = 0;
                    var addPpn = 1.11;
                    jQuery.each(arrTaskCheck, function(a, b){
                        if($(b).prop('checked')){
                            id = $(this).attr('pid')*1;
                            harga = $(this).attr('harga')*1;
                            persen = $(this).attr('persen')*1;
                            harga_nppn = harga * addPpn;
                            check[id] = {id,harga,harga_nppn,persen}
                            totalCheckRp += harga_nppn*1
                            totalCheckRpNonTax += harga*1
                            totalCheckPercent += persen*1
                        }
                    })
                    $('#result').load('$linkSelectWorkOrder', {data:check, totalCheckRp, totalCheckPercent});
                    var currentTypePayment = $('#type_payment_project').length > 0 ? ($('#type_payment_project').val() + '').toLowerCase() : '';
                    if(currentTypePayment == 'termin'){
                        $('#nilai_entry').val(totalCheckRpNonTax).trigger('keyup').trigger('mouseout');
                    }
                });

                // 1. Cek apakah ada checkbox yang BELUM tercentang
                var totalCheckbox = $('.checkbox_tasklist:not(:disabled)').length;
                var totalChecked  = $('.checkbox_tasklist:checked').length;
                var defaultTypePayment = $('#type_payment_project').length > 0 ? ($('#type_payment_project').val() + '').toLowerCase() : '';

                // 2. Jika jumlah yang dicentang masih 0 (artinya ini load pertama)
                if ((defaultTypePayment == 'termin' || defaultTypePayment == 'dp') && totalChecked === 0 && totalCheckbox > 0) {
                    $('.checkbox_tasklist:not(:disabled)').prop('checked', true); // Centang semua yang aktif
                    $('.checkbox_tasklist:not(:disabled)').first().trigger('change'); // Trigger kalkulasi sekali saja
                }

                // 3. Submit Guard untuk Termin Progres (Wajib membawa bahan evaluasi realisasi)
                top.validateRealizationBeforeSubmit = function() {
                    var curType = $('#type_payment_project').length > 0 ? ($('#type_payment_project').val() + '').toLowerCase() : '';
                    if (curType === 'termin' || curType === '' || curType === 'termin progress') {
                        var checkedCount = $('.checkbox_tasklist:checked').length;
                        if (checkedCount === 0) {
                            if (typeof top.swal !== 'undefined') {
                                top.swal('Peringatan!', 'Untuk Termin Progres, Anda wajib mencentang minimal satu Work Order sebagai komponen dasar penagihan dan bahan evaluasi realisasi.', 'warning');
                            } else {
                                alert('Peringatan: Untuk Termin Progres, Anda wajib mencentang minimal satu Work Order sebagai komponen dasar penagihan dan bahan evaluasi realisasi.');
                            }
                            return false;
                        }
                    }
                    return true;
                };

if (typeof top !== 'undefined' && typeof top.$ !== 'undefined') {
    var jqTop = top.$;
    var activeModalEl = jqTop();
    var hostFrameEl = jqTop('iframe').filter(function(){
        try {
            return this.contentWindow === window;
        } catch(err) {
            return false;
        }
    }).first();

    if (hostFrameEl.length > 0) {
        activeModalEl = hostFrameEl.closest('.modal, .bootstrap-dialog');
    }
    if (activeModalEl.length < 1) {
        activeModalEl = jqTop('.bootstrap-dialog.in:visible:last, .modal.in:visible:last').first();
    }
    if (activeModalEl.length > 0) {
        activeModalEl.addClass('size-wide');
        activeModalEl.find('.modal-dialog:first')
            .addClass('modal-lg modal-xl')
            .css({'width':'95%','max-width':'1400px'});
    }
    jqTop('.bootstrap-dialog.in:visible .modal-dialog:first, .modal.in:visible .modal-dialog:first')
        .addClass('modal-lg modal-xl')
        .css({'width':'95%','max-width':'1400px'});
}

// START OF COMPLETE REPEATED LOGIC
$(document).ready(function() {
    $('#panel_histori_dp').on('show.bs.collapse', function () {
        $('.btn-toggle-dp .icon-arrow').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }).on('hide.bs.collapse', function () {
        $('.btn-toggle-dp .icon-arrow').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });

    $('.input-alokasi-dp').on('input change', function() {
        var inputVal = parseFloat($(this).val()) || 0;
        var maxVal   = parseFloat($(this).data('max')) || 0;

        if (inputVal > maxVal) {
            alert('Alokasi Uang Muka tidak boleh melebihi sisa saldo DP yang tersedia (Maksimal Rp ' + maxVal.toLocaleString('id-ID') + ')');
            $(this).val(maxVal);
            $(this).addClass('border-red');
        } else {
            $(this).removeClass('border-red');
        }
    });
});
// END OF COMPLETE REPEATED LOGIC
                </script>";
    }
    else {
        echo "<div class='panel-body'>";
        echo "<div class='text-danger'>";
        echo "- <strong>you have not chosen any item yet</strong> -<br>";
        echo "<small>you can do so by selecting items from available selectors</small><br>";
        echo "</div class='text-warning'>";
        echo "</div class='panel-body'>";
    }

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
