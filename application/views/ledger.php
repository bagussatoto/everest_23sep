<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/6/2019
 * Time: 8:44 PM
 */


switch ($mode) {
    case "saldo":
        /**
         * metode concat
         */
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";

        // $arrBgColor = array();
        // if (isset($items_blok) && sizeof($items_blok) > 0) {
        //     foreach ($items_blok as $ctr => $spec) {
        //         $bagi = $ctr % 2;
        //         $background_color = ($bagi == 0)? "background-color:#F8F8FF;" : "background-color:#FFE4E1;";
        //
        //         $arrBgColor[$spec['main']] = $background_color;
        //         $arrBgColor[$spec['relasi']] = $background_color;
        //     }
        // }
        // matiHere(__LINE__);
        $memberships = $_SESSION['login']['membership'];
        // matiHere(__LINE__);
        // region Description searching by php
        $list_data .= "<div style='margin-bottom: 15px;'>";
        $list_data .= "<div style='display: flex; flex-wrap: wrap; gap: 10px; align-items: center;'>";

        $link_excel = base_url() . "ExcelWriter/persediaan/$param_to_excel";

        /*---allow to download-------------*/
        $allowBtns = array(
            "c_gudang",
            "c_gudang_spv",
            "c_holding"
        );

        // Container untuk Action Buttons (Excel Download)
        $list_data .= "<div style='flex-shrink: 0;'>";
        if (isset($param_to_excel)) {

            $btnExcels = array();
            foreach ($memberships as $membership) {
                if (in_array($membership, $allowBtns)) {
                    $btnExcels[] = $membership;
                }
            }

            if (isset($btnExcels) && sizeof($btnExcels) > 0) {
                $list_data .= "<button type='button' class='btn btn-success' style='white-space: nowrap;' data-toggle='tooltip' title='Download seluruh data ke Excel' onclick=\"btn_alert_result('Excell','Download data akan muncul setelah beberapa saat diklik','$link_excel');\"><i class='fa fa-file-excel-o'></i> Download Excel</button>";
            }
            else {
                $list_data .= "<button type='button' disabled class='btn btn-secondary' style='white-space: nowrap;' data-toggle='tooltip' title='Anda tidak memiliki akses untuk download'><i class='fa fa-file-excel-o'></i> Download Excel</button>";
            }
        }
        $list_data .= "</div>";

        $showPiutangHierarchy = isset($showPiutangHierarchy) ? (bool)$showPiutangHierarchy : false;
        $piutangHierarchyCurrent = isset($piutangHierarchyCurrent) ? (string)$piutangHierarchyCurrent : "";
        $piutangHierarchyLinks = isset($piutangHierarchyLinks) && is_array($piutangHierarchyLinks) ? $piutangHierarchyLinks : array();
        $piutangHierarchyNote = isset($piutangHierarchyNote) ? (string)$piutangHierarchyNote : "";
        $piutangHierarchyTotals = isset($piutangHierarchyTotals) && is_array($piutangHierarchyTotals) ? $piutangHierarchyTotals : array();
        $piutangHierarchyBlockLabel = isset($piutangHierarchyBlockLabel) ? trim((string)$piutangHierarchyBlockLabel) : "Piutang";
        if ($piutangHierarchyBlockLabel == "") {
            $piutangHierarchyBlockLabel = "Piutang";
        }
        $piutangHierarchyBlockIcon = isset($piutangHierarchyBlockIcon) ? trim((string)$piutangHierarchyBlockIcon) : "fa-sitemap";
        if ($piutangHierarchyBlockIcon == "" || !preg_match('/^[a-zA-Z0-9\-\s_]+$/', $piutangHierarchyBlockIcon)) {
            $piutangHierarchyBlockIcon = "fa-sitemap";
        }
        $piutangHierarchyTotalsLabel = isset($piutangHierarchyTotalsLabel) ? (string)$piutangHierarchyTotalsLabel : "Total Piutang (Reguler + Project)";
        $piutangHierarchyTotalsAllLabel = isset($piutangHierarchyTotalsAllLabel) ? (string)$piutangHierarchyTotalsAllLabel : "Total Piutang Semua (Termasuk Marketplace Legacy)";
        if ($showPiutangHierarchy && sizeof($piutangHierarchyLinks) > 0) {
            $piutangHierarchyBlockLabelEsc = htmlspecialchars($piutangHierarchyBlockLabel, ENT_QUOTES);
            $piutangHierarchyBlockIconEsc = htmlspecialchars($piutangHierarchyBlockIcon, ENT_QUOTES);
            $list_data .= "<div style='flex: 1 1 100%; background: #eef7ff; border: 1px solid #b9daf5; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
            $list_data .= "<span style='font-weight: 600; color: #24557a;'><i class='fa $piutangHierarchyBlockIconEsc'></i> $piutangHierarchyBlockLabelEsc:</span>";
            foreach ($piutangHierarchyLinks as $rekCode => $hierarchySpec) {
                $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
                $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
                $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
                $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
                $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;
                $hierarchyCountLabel = isset($hierarchySpec['count_label']) ? trim((string)$hierarchySpec['count_label']) : "akun";
                if ($hierarchyCountLabel == "") {
                    $hierarchyCountLabel = "akun";
                }

                $isActiveHierarchy = ($piutangHierarchyCurrent == $rekCode);
                $hierarchyCardStyle = $isActiveHierarchy
                    ? "display:block; min-width:220px; border:1px solid #4f9ed6; background:#dff0ff; border-radius:6px; padding:8px 10px; text-decoration:none;"
                    : "display:block; min-width:220px; border:1px solid #cbd8e3; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
                $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
                $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
                $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
                $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                $hierarchyCountEsc = htmlspecialchars(number_format($hierarchyCount, 0, ",", ".") . " " . $hierarchyCountLabel, ENT_QUOTES);

                $list_data .= "<a href='$hierarchyUrlEsc' data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyle'>";
                $list_data .= "<div style='font-weight:600; color:#25506f;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
                $list_data .= "<div style='font-size:11px; color:#5d7488; margin-top:2px;'>$hierarchyNoteEsc</div>";
                $list_data .= "<div style='font-size:17px; font-weight:700; color:#1f4f72; margin-top:3px;'>$hierarchySaldoEsc</div>";
                $list_data .= "<div style='font-size:11px; color:#5d7488;'>$hierarchyCountEsc</div>";
                $list_data .= "</a>";
            }
            $totalHirarki = isset($piutangHierarchyTotals['hirarki']) ? (float)$piutangHierarchyTotals['hirarki'] : 0;
            $totalAll = isset($piutangHierarchyTotals['all']) ? (float)$piutangHierarchyTotals['all'] : $totalHirarki;
            $totalHirarkiEsc = htmlspecialchars("Rp " . number_format($totalHirarki, 0, ",", "."), ENT_QUOTES);
            $totalAllEsc = htmlspecialchars("Rp " . number_format($totalAll, 0, ",", "."), ENT_QUOTES);

            $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
            $totalHirarkiLabelEsc = htmlspecialchars($piutangHierarchyTotalsLabel, ENT_QUOTES);
            $list_data .= "<span class='label label-primary' style='padding:6px 10px; font-size:12px;'>$totalHirarkiLabelEsc: $totalHirarkiEsc</span>";
            if (abs($totalAll - $totalHirarki) > 0.0001) {
                $totalAllLabelEsc = htmlspecialchars($piutangHierarchyTotalsAllLabel, ENT_QUOTES);
                $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>$totalAllLabelEsc: $totalAllEsc</span>";
            }
            $list_data .= "</div>";
            if (strlen($piutangHierarchyNote) > 0) {
                $piutangHierarchyNoteEsc = htmlspecialchars($piutangHierarchyNote, ENT_QUOTES);
                $list_data .= "<span style='color: #4f6f8a; font-size: 12px;'>" . $piutangHierarchyNoteEsc . "</span>";
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $showKasHierarchy = isset($showKasHierarchy) ? (bool)$showKasHierarchy : false;
        $kasHierarchyCurrent = isset($kasHierarchyCurrent) ? (string)$kasHierarchyCurrent : "";
        $kasHierarchyLinks = isset($kasHierarchyLinks) && is_array($kasHierarchyLinks) ? $kasHierarchyLinks : array();
        $kasHierarchyNote = isset($kasHierarchyNote) ? (string)$kasHierarchyNote : "";
        $kasHierarchyTotals = isset($kasHierarchyTotals) && is_array($kasHierarchyTotals) ? $kasHierarchyTotals : array();
        if ($showKasHierarchy && sizeof($kasHierarchyLinks) > 0) {
            $list_data .= "<div style='flex: 1 1 100%; background: #ecfff3; border: 1px solid #bde4cc; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
            $list_data .= "<span style='font-weight: 600; color: #2a6e4f;'><i class='fa fa-money'></i> Kas & Valas:</span>";
            foreach ($kasHierarchyLinks as $rekCode => $hierarchySpec) {
                $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
                $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
                $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
                $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
                $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;

                $isActiveHierarchy = ($kasHierarchyCurrent == $rekCode);
                $hierarchyCardStyle = $isActiveHierarchy
                    ? "display:block; min-width:220px; border:1px solid #5ab381; background:#dcf7e8; border-radius:6px; padding:8px 10px; text-decoration:none;"
                    : "display:block; min-width:220px; border:1px solid #c7dccf; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
                $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
                $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
                $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
                $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                $hierarchyCountEsc = htmlspecialchars(number_format($hierarchyCount, 0, ",", ".") . " akun", ENT_QUOTES);

                $list_data .= "<a href='$hierarchyUrlEsc' data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyle'>";
                $list_data .= "<div style='font-weight:600; color:#2c6b4e;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
                $list_data .= "<div style='font-size:11px; color:#5f7f6f; margin-top:2px;'>$hierarchyNoteEsc</div>";
                $list_data .= "<div style='font-size:17px; font-weight:700; color:#2a6e4f; margin-top:3px;'>$hierarchySaldoEsc</div>";
                $list_data .= "<div style='font-size:11px; color:#5f7f6f;'>$hierarchyCountEsc</div>";
                $list_data .= "</a>";
            }
            $totalKas = isset($kasHierarchyTotals['all']) ? (float)$kasHierarchyTotals['all'] : 0;
            $totalKasEsc = htmlspecialchars("Rp " . number_format($totalKas, 0, ",", "."), ENT_QUOTES);
            $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
            $list_data .= "<span class='label label-success' style='padding:6px 10px; font-size:12px;'>Total Kas: $totalKasEsc</span>";
            $list_data .= "</div>";
            if (strlen($kasHierarchyNote) > 0) {
                $kasHierarchyNoteEsc = htmlspecialchars($kasHierarchyNote, ENT_QUOTES);
                $list_data .= "<span style='color: #4f7b62; font-size: 12px;'>" . $kasHierarchyNoteEsc . "</span>";
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $showPersediaanHierarchy = isset($showPersediaanHierarchy) ? (bool)$showPersediaanHierarchy : false;
        $persediaanHierarchyCurrent = isset($persediaanHierarchyCurrent) ? (string)$persediaanHierarchyCurrent : "";
        $persediaanHierarchyLinks = isset($persediaanHierarchyLinks) && is_array($persediaanHierarchyLinks) ? $persediaanHierarchyLinks : array();
        $persediaanHierarchyNote = isset($persediaanHierarchyNote) ? (string)$persediaanHierarchyNote : "";
        $persediaanHierarchyTotals = isset($persediaanHierarchyTotals) && is_array($persediaanHierarchyTotals) ? $persediaanHierarchyTotals : array();
        $persediaanHierarchyTotalsLabel = isset($persediaanHierarchyTotalsLabel) ? trim((string)$persediaanHierarchyTotalsLabel) : "Total Persediaan";
        if ($persediaanHierarchyTotalsLabel == "") {
            $persediaanHierarchyTotalsLabel = "Total Persediaan";
        }
        $persediaanHierarchyDimensionLabel = isset($persediaanHierarchyDimensionLabel) ? trim((string)$persediaanHierarchyDimensionLabel) : "item";
        if ($persediaanHierarchyDimensionLabel == "") {
            $persediaanHierarchyDimensionLabel = "item";
        }
        $persediaanHierarchyDimensionSuffix = isset($persediaanHierarchyDimensionSuffix) ? trim((string)$persediaanHierarchyDimensionSuffix) : "unik";
        $persediaanHierarchyShowAsetBreakdown = isset($persediaanHierarchyShowAsetBreakdown) ? (bool)$persediaanHierarchyShowAsetBreakdown : false;
        $persediaanHierarchyBlockLabel = isset($persediaanHierarchyBlockLabel) ? trim((string)$persediaanHierarchyBlockLabel) : "Persediaan";
        if ($persediaanHierarchyBlockLabel == "") {
            $persediaanHierarchyBlockLabel = "Persediaan";
        }
        $showPersediaanBrandSummary = isset($showPersediaanBrandSummary) ? (bool)$showPersediaanBrandSummary : false;
        $persediaanBrandSummary = isset($persediaanBrandSummary) && is_array($persediaanBrandSummary) ? $persediaanBrandSummary : array();
        $persediaanBrandCurrent = isset($persediaanBrandCurrent) ? trim((string)$persediaanBrandCurrent) : "";
        $persediaanBrandNote = isset($persediaanBrandNote) ? trim((string)$persediaanBrandNote) : "";
        $persediaanBrandClearUrl = isset($persediaanBrandClearUrl) ? trim((string)$persediaanBrandClearUrl) : "";
        $persediaanBrandViewMode = isset($persediaanBrandViewMode) ? strtolower(trim((string)$persediaanBrandViewMode)) : "chip";
        if ($persediaanBrandViewMode != "dropdown") {
            $persediaanBrandViewMode = "chip";
        }
        $persediaanBrandViewOptions = isset($persediaanBrandViewOptions) && is_array($persediaanBrandViewOptions) ? $persediaanBrandViewOptions : array(
            "chip" => "Chip",
            "dropdown" => "Dropdown",
        );
        $persediaanBrandViewUrls = isset($persediaanBrandViewUrls) && is_array($persediaanBrandViewUrls) ? $persediaanBrandViewUrls : array();
        $persediaanHierarchyBlockIcon = isset($persediaanHierarchyBlockIcon) ? trim((string)$persediaanHierarchyBlockIcon) : "fa-cubes";
        if ($persediaanHierarchyBlockIcon == "" || !preg_match('/^[a-zA-Z0-9\-\s_]+$/', $persediaanHierarchyBlockIcon)) {
            $persediaanHierarchyBlockIcon = "fa-cubes";
        }
        if ($showPersediaanHierarchy && sizeof($persediaanHierarchyLinks) > 0) {
            $plannedHideTokens = array("INV-TRANSIT", "INV-KONSINYASI", "INV-OBSOLETE", "INV-ALLOWANCE");
            $normalizeToken = function ($raw) {
                $raw = strtoupper((string)$raw);
                $raw = preg_replace('/[^A-Z0-9]+/', '', $raw);
                return $raw;
            };
            $plannedHideTokenNorm = array();
            foreach ($plannedHideTokens as $plannedToken) {
                $plannedHideTokenNorm[$normalizeToken($plannedToken)] = 1;
            }
            $isTargetPlannedCard = function ($rekCodeRaw, $hierarchySpecRaw) use ($plannedHideTokenNorm, $normalizeToken) {
                $candidates = array();
                $candidates[] = $rekCodeRaw;
                if (is_array($hierarchySpecRaw)) {
                    if (isset($hierarchySpecRaw['label'])) {
                        $candidates[] = $hierarchySpecRaw['label'];
                    }
                    if (isset($hierarchySpecRaw['note'])) {
                        $candidates[] = $hierarchySpecRaw['note'];
                    }
                }

                foreach ($candidates as $candidateRaw) {
                    $candidateNorm = $normalizeToken($candidateRaw);
                    if ($candidateNorm == "") {
                        continue;
                    }
                    foreach ($plannedHideTokenNorm as $tokenNorm => $tmpOne) {
                        if ($tokenNorm == "") {
                            continue;
                        }
                        if (strpos($candidateNorm, $tokenNorm) !== false) {
                            return true;
                        }
                    }
                }
                return false;
            };
            $plannedHiddenCount = 0;
            foreach ($persediaanHierarchyLinks as $tmpRekCode => $tmpHierarchySpec) {
                if ($isTargetPlannedCard($tmpRekCode, $tmpHierarchySpec)) {
                    $plannedHiddenCount++;
                }
            }

            $persediaanBlockLabelRender = trim(str_ireplace("(PSAK 14)", "", $persediaanHierarchyBlockLabel));
            if ($persediaanBlockLabelRender == "") {
                $persediaanBlockLabelRender = "Persediaan";
            }
            $persediaanHierarchyBlockLabelEsc = htmlspecialchars($persediaanBlockLabelRender, ENT_QUOTES);
            $persediaanHierarchyBlockIconEsc = htmlspecialchars($persediaanHierarchyBlockIcon, ENT_QUOTES);
            $list_data .= "<div id='persediaan-hierarchy-panel' style='flex: 1 1 100%; background: #eef8f3; border: 1px solid #c3e1d2; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='font-weight: 600; color: #2b6f53;'><i class='fa $persediaanHierarchyBlockIconEsc'></i> $persediaanHierarchyBlockLabelEsc:</div>";
            if ($plannedHiddenCount > 0) {
                $plannedHiddenCountTxt = number_format($plannedHiddenCount, 0, ",", ".");
                $list_data .= "<div style='display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-top:6px;'>";
                $list_data .= "<button type='button' class='btn btn-default btn-xs js-toggle-persediaan-planned' data-state='hidden' data-count='$plannedHiddenCountTxt' style='padding:2px 8px;'>Tampilkan Kategori Planned ($plannedHiddenCountTxt)</button>";
                $list_data .= "<span class='js-toggle-persediaan-note' style='font-size:11px; color:#5c7f6c;'>Kategori planned disembunyikan dari tampilan awal.</span>";
                $list_data .= "</div>";
            }
            $list_data .= "<div class='hierarchy-cards' style='display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-top:8px;'>";
            foreach ($persediaanHierarchyLinks as $rekCode => $hierarchySpec) {
                $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
                $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
                $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
                $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
                $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;
                $hierarchyAset = isset($hierarchySpec['aset']) ? (float)$hierarchySpec['aset'] : $hierarchySaldo;
                $hierarchyAkumulasi = isset($hierarchySpec['akumulasi']) ? (float)$hierarchySpec['akumulasi'] : 0;
                $hierarchyNetto = isset($hierarchySpec['netto']) ? (float)$hierarchySpec['netto'] : $hierarchySaldo;
                if ($persediaanHierarchyShowAsetBreakdown) {
                    $hierarchySaldo = $hierarchyNetto;
                }
                $hierarchyCountLabel = isset($hierarchySpec['count_label']) ? trim((string)$hierarchySpec['count_label']) : "item";
                if ($hierarchyCountLabel == "") {
                    $hierarchyCountLabel = "item";
                }
                $hierarchyStatus = isset($hierarchySpec['status']) ? strtolower(trim((string)$hierarchySpec['status'])) : "active";
                if ($hierarchyStatus == "") {
                    $hierarchyStatus = "active";
                }
                $hierarchyRouteEnabled = isset($hierarchySpec['route_enabled']) ? (bool)$hierarchySpec['route_enabled'] : true;
                $isPlannedHierarchy = ($hierarchyStatus == "planned");
                if ($isPlannedHierarchy) {
                    $hierarchyRouteEnabled = false;
                }
                $hidePlannedCardDefault = $isTargetPlannedCard($rekCode, $hierarchySpec);

                $isActiveHierarchy = ($persediaanHierarchyCurrent == $rekCode);
                if ($isPlannedHierarchy) {
                    $hierarchyCardStyle = "display:block; min-width:250px; border:1px dashed #b7d1c3; background:#fbfdfc; border-radius:6px; padding:8px 10px; text-decoration:none;";
                }
                else {
                    $hierarchyCardStyle = $isActiveHierarchy
                        ? "display:block; min-width:250px; border:1px solid #4ea97f; background:#def3e8; border-radius:6px; padding:8px 10px; text-decoration:none;"
                        : "display:block; min-width:250px; border:1px solid #c5d8cd; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                }

                $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
                $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
                $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
                $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
                $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                $hierarchyCountEsc = htmlspecialchars(number_format($hierarchyCount, 0, ",", ".") . " " . $hierarchyCountLabel, ENT_QUOTES);
                $hierarchyAsetEsc = htmlspecialchars("Rp " . number_format($hierarchyAset, 0, ",", "."), ENT_QUOTES);
                $hierarchyAkumulasiTxt = "Rp. " . number_format(abs($hierarchyAkumulasi), 0, ",", ".");
                if ($hierarchyAkumulasi < 0) {
                    $hierarchyAkumulasiTxt = "( " . $hierarchyAkumulasiTxt . " )";
                }
                $hierarchyAkumulasiEsc = htmlspecialchars($hierarchyAkumulasiTxt, ENT_QUOTES);
                $hierarchyNettoEsc = htmlspecialchars("Rp " . number_format($hierarchyNetto, 0, ",", "."), ENT_QUOTES);
                $statusBadge = $isPlannedHierarchy
                    ? "<span class='label label-default' style='font-size:10px;'>Planned</span>"
                    : "<span class='label label-success' style='font-size:10px;'>Active</span>";
                $plannedCardClassAttr = $hidePlannedCardDefault ? " class='js-persediaan-planned-card'" : "";
                $hierarchyCardStyleFinal = $hierarchyCardStyle . ($hidePlannedCardDefault ? " display:none;" : "");

                $cardBody = "";
                $cardBody .= "<div style='display:flex; justify-content:space-between; align-items:flex-start; gap:6px;'>";
                $cardBody .= "<div style='font-weight:600; color:#2b6f53;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
                $cardBody .= $statusBadge;
                $cardBody .= "</div>";
                $cardBody .= "<div style='font-size:11px; color:#5c7f6c; margin-top:2px;'>$hierarchyNoteEsc</div>";
                $cardBody .= "<div style='font-size:17px; font-weight:700; color:#2b6f53; margin-top:3px;'>$hierarchySaldoEsc</div>";
                if ($persediaanHierarchyShowAsetBreakdown) {
                    $cardBody .= "<div style='font-size:11px; color:#4f6f5f; margin-top:2px;'>Aset: $hierarchyAsetEsc</div>";
                    $cardBody .= "<div style='font-size:11px; color:#4f6f5f;'>Akumulasi: $hierarchyAkumulasiEsc</div>";
                    $cardBody .= "<div style='font-size:11px; color:#2b6f53; font-weight:600;'>Netto: $hierarchyNettoEsc</div>";
                }
                $cardBody .= "<div style='font-size:11px; color:#5c7f6c;'>$hierarchyCountEsc</div>";

                if ($hierarchyRouteEnabled) {
                    $list_data .= "<a href='$hierarchyUrlEsc'$plannedCardClassAttr data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyleFinal'>";
                    $list_data .= $cardBody;
                    $list_data .= "</a>";
                }
                else {
                    $list_data .= "<div$plannedCardClassAttr style='$hierarchyCardStyleFinal' data-toggle='tooltip' title='Kategori best practice, routing detail belum diaktifkan'>";
                    $list_data .= $cardBody;
                    $list_data .= "</div>";
                }
            }
            $list_data .= "</div>";

            if ($plannedHiddenCount > 0) {
                $list_data .= "<script>
                    (function(window) {
                        var initAttempt = 0;
                        function initPlannedToggle() {
                            var \$ = window.jQuery;
                            if (!\$ || !\$.fn) {
                                initAttempt++;
                                if (initAttempt <= 50) {
                                    window.setTimeout(initPlannedToggle, 200);
                                }
                                return;
                            }

                            var \$panel = \$('#persediaan-hierarchy-panel');
                            var \$btn = \$panel.find('.js-toggle-persediaan-planned');
                            var \$cards = \$panel.find('.js-persediaan-planned-card');
                            var \$note = \$panel.find('.js-toggle-persediaan-note');
                            if (\$btn.length === 0 || \$cards.length === 0) {
                                return;
                            }
                            if (\$btn.data('plannedBind') === 1) {
                                return;
                            }
                            \$btn.data('plannedBind', 1);

                            \$btn.on('click', function() {
                                var state = String(\$btn.attr('data-state') || 'hidden');
                                var countTxt = String(\$btn.attr('data-count') || \$cards.length);
                                if (state === 'hidden') {
                                    \$cards.css('display', 'block');
                                    \$btn.attr('data-state', 'shown')
                                        .removeClass('btn-default')
                                        .addClass('btn-success')
                                        .text('Sembunyikan Kategori Planned (' + countTxt + ')');
                                    if (\$note.length > 0) {
                                        \$note.text('Kategori planned sedang ditampilkan.');
                                    }
                                }
                                else {
                                    \$cards.css('display', 'none');
                                    \$btn.attr('data-state', 'hidden')
                                        .removeClass('btn-success')
                                        .addClass('btn-default')
                                        .text('Tampilkan Kategori Planned (' + countTxt + ')');
                                    if (\$note.length > 0) {
                                        \$note.text('Kategori planned disembunyikan dari tampilan awal.');
                                    }
                                }
                            });
                        }
                        initPlannedToggle();
                    })(window);
                </script>";
            }

            $totalPersediaan = isset($persediaanHierarchyTotals['all']) ? (float)$persediaanHierarchyTotals['all'] : 0;
            $totalAsetBruto = isset($persediaanHierarchyTotals['asset_all']) ? (float)$persediaanHierarchyTotals['asset_all'] : $totalPersediaan;
            $totalAkumulasi = isset($persediaanHierarchyTotals['akumulasi_all']) ? (float)$persediaanHierarchyTotals['akumulasi_all'] : 0;
            $totalNetto = isset($persediaanHierarchyTotals['netto_all']) ? (float)$persediaanHierarchyTotals['netto_all'] : $totalPersediaan;
            if ($persediaanHierarchyShowAsetBreakdown) {
                $totalPersediaan = $totalNetto;
            }
            $totalPersediaanEsc = htmlspecialchars("Rp " . number_format($totalPersediaan, 0, ",", "."), ENT_QUOTES);
            $itemCountAll = isset($persediaanHierarchyTotals['item_count']) ? (int)$persediaanHierarchyTotals['item_count'] : 0;
            $itemCountAllTxt = number_format($itemCountAll, 0, ",", ".") . " " . $persediaanHierarchyDimensionLabel;
            if ($persediaanHierarchyDimensionSuffix != "") {
                $itemCountAllTxt .= " " . $persediaanHierarchyDimensionSuffix;
            }
            $itemCountAllEsc = htmlspecialchars($itemCountAllTxt, ENT_QUOTES);
            $persediaanTotalsLabelEsc = htmlspecialchars($persediaanHierarchyTotalsLabel, ENT_QUOTES);
            $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
            $list_data .= "<span class='label label-success' style='padding:6px 10px; font-size:12px;'>$persediaanTotalsLabelEsc: $totalPersediaanEsc</span>";
            if ($persediaanHierarchyShowAsetBreakdown) {
                $totalAsetBrutoEsc = htmlspecialchars("Rp " . number_format($totalAsetBruto, 0, ",", "."), ENT_QUOTES);
                $totalAkumulasiTxt = "Rp. " . number_format(abs($totalAkumulasi), 0, ",", ".");
                if ($totalAkumulasi < 0) {
                    $totalAkumulasiTxt = "( " . $totalAkumulasiTxt . " )";
                }
                $totalAkumulasiEsc = htmlspecialchars($totalAkumulasiTxt, ENT_QUOTES);
                $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Total Aset: $totalAsetBrutoEsc</span>";
                $list_data .= "<span class='label label-warning' style='padding:6px 10px; font-size:12px;'>Total Akumulasi: $totalAkumulasiEsc</span>";
            }
            $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Dimensi Aktif: $itemCountAllEsc</span>";
            $list_data .= "</div>";
            if ($showPersediaanBrandSummary && sizeof($persediaanBrandSummary) > 0) {
                $brandPanelData = array(
                    "showPersediaanBrandSummary" => $showPersediaanBrandSummary,
                    "persediaanBrandSummary" => $persediaanBrandSummary,
                    "persediaanBrandCurrent" => $persediaanBrandCurrent,
                    "persediaanBrandNote" => $persediaanBrandNote,
                    "persediaanBrandClearUrl" => $persediaanBrandClearUrl,
                    "persediaanBrandViewMode" => $persediaanBrandViewMode,
                    "persediaanBrandViewOptions" => $persediaanBrandViewOptions,
                    "persediaanBrandViewUrls" => $persediaanBrandViewUrls,
                );
                $list_data .= "<div id='persediaan-brand-panel' style='flex: 1 1 100%; margin-top:6px; border-top:1px dashed #b9d8c7; padding-top:8px;'>";
                $list_data .= $this->load->view("partials/ledger_persediaan_brand_panel", $brandPanelData, TRUE);
                $list_data .= "</div>";
                $list_data .= "<script>
                    (function(window) {
                        var bootAttempt = 0;

                        function bootBrandAjax() {
                            var \$ = window.jQuery;
                            if (!\$ || !\$.fn) {
                                bootAttempt++;
                                if (bootAttempt <= 80) {
                                    window.setTimeout(bootBrandAjax, 200);
                                }
                                return;
                            }
                            if (window.__ledgerBrandAjaxReady === true) {
                                if (typeof window.__ledgerBrandApplyViewMode === 'function') {
                                    window.__ledgerBrandApplyViewMode(\$('#persediaan-brand-panel'));
                                }
                                else if (typeof window.__ledgerBrandInitDropdown === 'function') {
                                    window.__ledgerBrandInitDropdown(\$('#persediaan-brand-panel'));
                                }
                                return;
                            }
                            window.__ledgerBrandAjaxReady = true;
                            var brandAjaxRunning = false;
                            var selectpickerState = 'idle';
                            var selectpickerWaiters = [];

                            function resolveSupportBaseUrl() {
                                var marker = 'bootstrap-3.3.7-dist/js/bootstrap.min.js';
                                var scripts = window.document.getElementsByTagName('script');
                                for (var i = 0; i < scripts.length; i++) {
                                    var src = String(scripts[i].src || '');
                                    var markerPos = src.indexOf(marker);
                                    if (markerPos >= 0) {
                                        return src.substring(0, markerPos);
                                    }
                                }
                                return '';
                            }

                            function finishSelectpickerLoad(ok) {
                                selectpickerState = ok ? 'ready' : 'failed';
                                while (selectpickerWaiters.length > 0) {
                                    var waiterCb = selectpickerWaiters.shift();
                                    if (typeof waiterCb === 'function') {
                                        try {
                                            waiterCb(ok);
                                        }
                                        catch (e) {}
                                    }
                                }
                            }

                            function waitSelectpickerReady(remainTry) {
                                var \$ = window.jQuery;
                                if (\$ && \$.fn && \$.fn.selectpicker) {
                                    finishSelectpickerLoad(true);
                                    return;
                                }
                                if (remainTry <= 0) {
                                    finishSelectpickerLoad(false);
                                    return;
                                }
                                window.setTimeout(function() {
                                    waitSelectpickerReady(remainTry - 1);
                                }, 150);
                            }

                            function ensureSelectpickerLoaded(onReady) {
                                var \$ = window.jQuery;
                                var readyCb = (typeof onReady === 'function') ? onReady : function() {};
                                if (\$ && \$.fn && \$.fn.selectpicker) {
                                    selectpickerState = 'ready';
                                    readyCb(true);
                                    return;
                                }
                                if (selectpickerState === 'ready') {
                                    readyCb(true);
                                    return;
                                }
                                if (selectpickerState === 'failed') {
                                    readyCb(false);
                                    return;
                                }
                                selectpickerWaiters.push(readyCb);
                                if (selectpickerState === 'loading') {
                                    return;
                                }
                                selectpickerState = 'loading';

                                var supportBase = resolveSupportBaseUrl();
                                if (supportBase === '') {
                                    finishSelectpickerLoad(false);
                                    return;
                                }

                                var cssId = 'ledger-brand-selectpicker-css';
                                if (!window.document.getElementById(cssId)) {
                                    var linkNode = window.document.createElement('link');
                                    linkNode.id = cssId;
                                    linkNode.rel = 'stylesheet';
                                    linkNode.href = supportBase + 'bootstrap-select-1.13.9/dist/css/bootstrap-select.css';
                                    window.document.getElementsByTagName('head')[0].appendChild(linkNode);
                                }

                                var jsId = 'ledger-brand-selectpicker-js';
                                var existingScript = window.document.getElementById(jsId);
                                if (existingScript) {
                                    waitSelectpickerReady(40);
                                    return;
                                }

                                var scriptNode = window.document.createElement('script');
                                scriptNode.id = jsId;
                                scriptNode.src = supportBase + 'bootstrap-select-1.13.9/dist/js/bootstrap-select.js';
                                scriptNode.onload = function() {
                                    waitSelectpickerReady(40);
                                };
                                scriptNode.onerror = function() {
                                    finishSelectpickerLoad(false);
                                };
                                window.document.getElementsByTagName('head')[0].appendChild(scriptNode);
                            }

                            function ensureBrandOverlay() {
                                var \$overlay = \$('#ledger-brand-overlay');
                                if (\$overlay.length > 0) {
                                    return \$overlay;
                                }
                                var overlayHtml = '';
                                overlayHtml += '<div id=\"ledger-brand-overlay\" style=\"display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(239,247,243,0.72); z-index:99999;\">';
                                overlayHtml += '<div style=\"position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:#ffffff; border:1px solid #b9d8c7; border-radius:6px; padding:12px 16px; color:#2b6f53; font-weight:600; box-shadow:0 3px 12px rgba(0,0,0,0.12);\">';
                                overlayHtml += '<i class=\"fa fa-spinner fa-spin\" style=\"margin-right:6px;\"></i><span class=\"js-brand-overlay-label\">Memuat filter merek...</span>';
                                overlayHtml += '</div>';
                                overlayHtml += '</div>';
                                \$('body').append(overlayHtml);
                                return \$('#ledger-brand-overlay');
                            }

                            function setBrandOverlay(visible, messageTxt) {
                                var \$overlay = ensureBrandOverlay();
                                if (messageTxt && messageTxt !== '') {
                                    \$overlay.find('.js-brand-overlay-label').text(messageTxt);
                                }
                                if (visible) {
                                    \$overlay.show();
                                }
                                else {
                                    \$overlay.hide();
                                }
                            }

                            function initBrandDropdownUI(panelRoot) {
                                var \$select = panelRoot.find('.js-brand-dropdown');
                                if (\$select.length === 0) {
                                    return;
                                }
                                ensureSelectpickerLoaded(function(loaded) {
                                    if (!loaded || !\$.fn.selectpicker) {
                                        return;
                                    }
                                    \$select.each(function() {
                                        var \$el = \$(this);
                                        try {
                                            \$el.selectpicker('destroy');
                                        }
                                        catch (e) {}
                                        \$el.selectpicker({
                                            liveSearch: true,
                                            liveSearchNormalize: true,
                                            size: 9
                                        });
                                        \$el.selectpicker('refresh');
                                    });
                                });
                            }
                            window.__ledgerBrandInitDropdown = initBrandDropdownUI;

                            function normalizeBrandViewMode(modeRaw) {
                                var modeNorm = \$.trim(String(modeRaw || '')).toLowerCase();
                                if (modeNorm !== 'dropdown') {
                                    modeNorm = 'chip';
                                }
                                return modeNorm;
                            }

                            function detectBrandViewMode(panelRoot) {
                                var modeAttr = panelRoot.find('.js-brand-panel-body').attr('data-brand-view-mode');
                                return normalizeBrandViewMode(modeAttr);
                            }

                            function applyBrandViewMode(panelRoot, modeRaw) {
                                var viewMode = normalizeBrandViewMode(modeRaw);
                                var \$panelBody = panelRoot.find('.js-brand-panel-body');
                                if (\$panelBody.length > 0) {
                                    \$panelBody.attr('data-brand-view-mode', viewMode);
                                }

                                panelRoot.find('.js-brand-view-chip').css('display', (viewMode === 'chip') ? 'flex' : 'none');
                                panelRoot.find('.js-brand-view-dropdown').css('display', (viewMode === 'dropdown') ? 'flex' : 'none');
                                panelRoot.find('.js-brand-note-chip').css('display', (viewMode === 'chip') ? 'block' : 'none');
                                panelRoot.find('.js-brand-note-dropdown').css('display', (viewMode === 'dropdown') ? 'block' : 'none');

                                panelRoot.find('.js-brand-view-toggle').each(function() {
                                    var \$toggle = \$(this);
                                    var toggleMode = normalizeBrandViewMode(\$toggle.data('brandView'));
                                    if (toggleMode === viewMode) {
                                        \$toggle.removeClass('label-default').addClass('label-success');
                                    }
                                    else {
                                        \$toggle.removeClass('label-success').addClass('label-default');
                                    }
                                });

                                if (viewMode === 'dropdown') {
                                    initBrandDropdownUI(panelRoot);
                                }
                            }
                            window.__ledgerBrandApplyViewMode = function(panelRoot, modeRaw) {
                                if (!panelRoot || panelRoot.length === 0) {
                                    return;
                                }
                                var finalMode = (typeof modeRaw === 'undefined' || modeRaw === null || modeRaw === '')
                                    ? detectBrandViewMode(panelRoot)
                                    : modeRaw;
                                applyBrandViewMode(panelRoot, finalMode);
                            };

                            function getQueryValue(targetUrl, keyName) {
                                if (!targetUrl || !keyName) {
                                    return '';
                                }
                                var parts = String(targetUrl).split('?');
                                if (parts.length < 2) {
                                    return '';
                                }
                                var queryRaw = parts.slice(1).join('?');
                                var pairs = queryRaw.split('&');
                                for (var i = 0; i < pairs.length; i++) {
                                    var pairRaw = String(pairs[i] || '');
                                    if (pairRaw === '') {
                                        continue;
                                    }
                                    var pairParts = pairRaw.split('=');
                                    var pairKey = decodeURIComponent(String(pairParts[0] || '').replace(/\\+/g, ' '));
                                    if (pairKey !== keyName) {
                                        continue;
                                    }
                                    var pairValueRaw = pairParts.length > 1 ? pairParts.slice(1).join('=') : '';
                                    return decodeURIComponent(String(pairValueRaw).replace(/\\+/g, ' '));
                                }
                                return '';
                            }

                            function setQueryValue(targetUrl, keyName, keyVal, removeWhenEmpty) {
                                if (!targetUrl || !keyName) {
                                    return targetUrl;
                                }
                                var urlRaw = String(targetUrl);
                                var hashTxt = '';
                                var hashPos = urlRaw.indexOf('#');
                                if (hashPos >= 0) {
                                    hashTxt = urlRaw.substring(hashPos);
                                    urlRaw = urlRaw.substring(0, hashPos);
                                }

                                var qPos = urlRaw.indexOf('?');
                                var baseUrl = (qPos >= 0) ? urlRaw.substring(0, qPos) : urlRaw;
                                var queryTxt = (qPos >= 0) ? urlRaw.substring(qPos + 1) : '';
                                var pairs = (queryTxt === '') ? [] : queryTxt.split('&');
                                var outPairs = [];
                                var keyNorm = String(keyName);
                                for (var i = 0; i < pairs.length; i++) {
                                    var pairRaw = String(pairs[i] || '');
                                    if (pairRaw === '') {
                                        continue;
                                    }
                                    var pairParts = pairRaw.split('=');
                                    var pairKey = decodeURIComponent(String(pairParts[0] || '').replace(/\\+/g, ' '));
                                    if (pairKey === keyNorm) {
                                        continue;
                                    }
                                    outPairs.push(pairRaw);
                                }

                                var valStr = String((typeof keyVal === 'undefined' || keyVal === null) ? '' : keyVal);
                                if (!(removeWhenEmpty && valStr === '')) {
                                    outPairs.push(encodeURIComponent(keyNorm) + '=' + encodeURIComponent(valStr));
                                }

                                var finalUrl = baseUrl;
                                if (outPairs.length > 0) {
                                    finalUrl += '?' + outPairs.join('&');
                                }
                                finalUrl += hashTxt;
                                return finalUrl;
                            }

                            function syncBrandViewInUrl(targetUrl, panelRoot) {
                                var viewModeNow = detectBrandViewMode(panelRoot);
                                if (viewModeNow === 'chip') {
                                    return setQueryValue(targetUrl, 'brand_view', '', true);
                                }
                                return setQueryValue(targetUrl, 'brand_view', viewModeNow, false);
                            }

                            function findMerekColumnIdx(tableRef) {
                                if (!tableRef || !tableRef.columns) {
                                    return -1;
                                }
                                var headers = tableRef.columns().header();
                                if (!headers || !headers.length) {
                                    return -1;
                                }
                                for (var idx = 0; idx < headers.length; idx++) {
                                    var headerTxt = \$.trim(String(\$(headers[idx]).text() || '')).toLowerCase();
                                    if (headerTxt.indexOf('merek') !== -1) {
                                        return idx;
                                    }
                                }
                                return -1;
                            }

                            function reloadBrandDataTable(targetUrl, onDone) {
                                if (!\$.fn.dataTable || !\$.fn.dataTable.isDataTable('#myNewTable')) {
                                    if (typeof onDone === 'function') {
                                        onDone(false);
                                    }
                                    return false;
                                }
                                var table = \$('#myNewTable').DataTable();
                                var dtSettings = table.settings()[0];
                                var serverSideOn = !!(dtSettings && dtSettings.oFeatures && dtSettings.oFeatures.bServerSide);
                                if (serverSideOn) {
                                    var ajaxUrl = targetUrl;
                                    if (ajaxUrl.indexOf('?') >= 0) {
                                        ajaxUrl += '&server=1&json=1';
                                    }
                                    else {
                                        ajaxUrl += '?server=1&json=1';
                                    }
                                    table.ajax.url(ajaxUrl).load(function() {
                                        if (typeof onDone === 'function') {
                                            onDone(true);
                                        }
                                    }, false);
                                    return true;
                                }

                                var merekValue = \$.trim(String(getQueryValue(targetUrl, 'merek') || ''));
                                var merekColumnIdx = findMerekColumnIdx(table);
                                if (merekColumnIdx < 0) {
                                    if (typeof onDone === 'function') {
                                        onDone(false);
                                    }
                                    return false;
                                }
                                table.column(merekColumnIdx).search(merekValue, false, false).draw(false);
                                if (typeof onDone === 'function') {
                                    onDone(true);
                                }
                                return true;
                            }

                            function replaceBrowserUrl(targetUrl) {
                                if (window.history && window.history.replaceState) {
                                    window.history.replaceState({}, '', targetUrl);
                                }
                            }

                            function fetchBrandPanel(targetUrl, refreshTable) {
                                var \$panel = \$('#persediaan-brand-panel');
                                if (\$panel.length === 0) {
                                    return;
                                }
                                if (brandAjaxRunning) {
                                    return;
                                }
                                brandAjaxRunning = true;
                                \$panel.css('opacity', '0.65');
                                if (refreshTable) {
                                    setBrandOverlay(true, 'Memuat filter merek...');
                                }
                                var ajaxUrl = targetUrl;
                                if (ajaxUrl.indexOf('?') >= 0) {
                                    ajaxUrl += '&brand_panel_ajax=1';
                                }
                                else {
                                    ajaxUrl += '?brand_panel_ajax=1';
                                }
                                \$.ajax({
                                    url: ajaxUrl,
                                    type: 'GET',
                                    dataType: 'json'
                                }).done(function(resp) {
                                    if (!resp || resp.status !== 'ok') {
                                        if (refreshTable) {
                                            setBrandOverlay(false, '');
                                        }
                                        if (window.console && window.console.warn) {
                                            window.console.warn('Ledger brand panel response invalid.');
                                        }
                                        return;
                                    }
                                    var finalUrl = (resp.url && resp.url !== '') ? resp.url : targetUrl;
                                    \$panel.html(resp.html || '');
                                    applyBrandViewMode(\$panel, detectBrandViewMode(\$panel));
                                    replaceBrowserUrl(finalUrl);
                                    if (refreshTable) {
                                        var tableHandled = reloadBrandDataTable(finalUrl, function() {
                                            setBrandOverlay(false, '');
                                        });
                                        if (!tableHandled) {
                                            setBrandOverlay(false, '');
                                        }
                                    }
                                }).fail(function() {
                                    if (refreshTable) {
                                        setBrandOverlay(false, '');
                                    }
                                    if (window.console && window.console.warn) {
                                        window.console.warn('Ledger brand panel AJAX failed.');
                                    }
                                }).always(function() {
                                    brandAjaxRunning = false;
                                    \$panel.css('opacity', '1');
                                });
                            }

                            \$(document).off('click.ledgerBrandAjax').on('click.ledgerBrandAjax', '#persediaan-brand-panel .js-brand-ajax-link', function(e) {
                                var actionType = \$.trim(String(\$(this).data('brandAction') || ''));
                                var targetUrl = \$(this).attr('href');
                                if (actionType === 'view') {
                                    e.preventDefault();
                                    var \$panel = \$('#persediaan-brand-panel');
                                    var viewMode = normalizeBrandViewMode(\$(this).data('brandView'));
                                    applyBrandViewMode(\$panel, viewMode);
                                    if (targetUrl) {
                                        replaceBrowserUrl(targetUrl);
                                    }
                                    return;
                                }
                                if (!targetUrl) {
                                    return;
                                }
                                e.preventDefault();
                                var refreshTable = (actionType === 'select' || actionType === 'clear');
                                targetUrl = syncBrandViewInUrl(targetUrl, \$('#persediaan-brand-panel'));
                                fetchBrandPanel(targetUrl, refreshTable);
                            });

                            \$(document).off('change.ledgerBrandAjax').on('change.ledgerBrandAjax', '#persediaan-brand-panel .js-brand-dropdown', function(e) {
                                var targetUrl = \$(this).val();
                                if (!targetUrl) {
                                    return;
                                }
                                e.preventDefault();
                                targetUrl = syncBrandViewInUrl(targetUrl, \$('#persediaan-brand-panel'));
                                fetchBrandPanel(targetUrl, true);
                            });

                            applyBrandViewMode(\$('#persediaan-brand-panel'), detectBrandViewMode(\$('#persediaan-brand-panel')));
                        }

                        bootBrandAjax();
                    })(window);
                </script>";
            }
            if (strlen($persediaanHierarchyNote) > 0) {
                $persediaanHierarchyNoteEsc = htmlspecialchars($persediaanHierarchyNote, ENT_QUOTES);
                $list_data .= "<span style='color: #507365; font-size: 12px;'>" . $persediaanHierarchyNoteEsc . "</span>";
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $showHutangHierarchy = isset($showHutangHierarchy) ? (bool)$showHutangHierarchy : false;
        $hutangHierarchyCurrent = isset($hutangHierarchyCurrent) ? (string)$hutangHierarchyCurrent : "";
        $hutangHierarchyLinks = isset($hutangHierarchyLinks) && is_array($hutangHierarchyLinks) ? $hutangHierarchyLinks : array();
        $hutangHierarchyNote = isset($hutangHierarchyNote) ? (string)$hutangHierarchyNote : "";
        $hutangHierarchyEntityLabel = isset($hutangHierarchyEntityLabel) ? trim((string)$hutangHierarchyEntityLabel) : "supplier";
        if ($hutangHierarchyEntityLabel == "") {
            $hutangHierarchyEntityLabel = "supplier";
        }
        $hutangHierarchyTotals = isset($hutangHierarchyTotals) && is_array($hutangHierarchyTotals) ? $hutangHierarchyTotals : array();
        if ($showHutangHierarchy && sizeof($hutangHierarchyLinks) > 0) {
            $list_data .= "<div style='flex: 1 1 100%; background: #fff7ec; border: 1px solid #f0d6ab; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
            $list_data .= "<span style='font-weight: 600; color: #8a5a20;'><i class='fa fa-credit-card'></i> Hutang:</span>";
            foreach ($hutangHierarchyLinks as $rekCode => $hierarchySpec) {
                $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
                $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
                $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
                $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
                $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;
                $hierarchyCabangCount = isset($hierarchySpec['cabang_count']) ? (int)$hierarchySpec['cabang_count'] : 0;
                $hierarchyStatus = isset($hierarchySpec['status']) ? strtolower(trim((string)$hierarchySpec['status'])) : "active";
                if ($hierarchyStatus == "") {
                    $hierarchyStatus = "active";
                }
                $hierarchyRouteEnabled = isset($hierarchySpec['route_enabled']) ? (bool)$hierarchySpec['route_enabled'] : true;
                $isPlannedHierarchy = ($hierarchyStatus == "planned");
                if ($isPlannedHierarchy) {
                    $hierarchyRouteEnabled = false;
                }

                $isActiveHierarchy = ($hutangHierarchyCurrent == $rekCode);
                if ($isPlannedHierarchy) {
                    $hierarchyCardStyle = "display:block; min-width:240px; border:1px dashed #d7c4a6; background:#fffdf9; border-radius:6px; padding:8px 10px; text-decoration:none;";
                }
                else {
                    $hierarchyCardStyle = $isActiveHierarchy
                        ? "display:block; min-width:240px; border:1px solid #dca457; background:#fff0d9; border-radius:6px; padding:8px 10px; text-decoration:none;"
                        : "display:block; min-width:240px; border:1px solid #e0c9a6; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                }
                $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
                $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
                $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
                $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
                $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                $dimensiTxt = number_format($hierarchyCount, 0, ",", ".") . " " . $hutangHierarchyEntityLabel;
                if ($hierarchyCabangCount > 0) {
                    $dimensiTxt .= " | " . number_format($hierarchyCabangCount, 0, ",", ".") . " cabang";
                }
                $dimensiTxtEsc = htmlspecialchars($dimensiTxt, ENT_QUOTES);
                $statusBadge = $isPlannedHierarchy
                    ? "<span class='label label-default' style='font-size:10px;'>Planned</span>"
                    : "<span class='label label-warning' style='font-size:10px; color:#5f3d13;'>Active</span>";

                $cardBody = "";
                $cardBody .= "<div style='display:flex; justify-content:space-between; align-items:flex-start; gap:6px;'>";
                $cardBody .= "<div style='font-weight:600; color:#8a5a20;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
                $cardBody .= $statusBadge;
                $cardBody .= "</div>";
                $cardBody .= "<div style='font-size:11px; color:#9a7a52; margin-top:2px;'>$hierarchyNoteEsc</div>";
                $cardBody .= "<div style='font-size:17px; font-weight:700; color:#8a5a20; margin-top:3px;'>$hierarchySaldoEsc</div>";
                $cardBody .= "<div style='font-size:11px; color:#9a7a52;'>$dimensiTxtEsc</div>";

                if ($hierarchyRouteEnabled) {
                    $list_data .= "<a href='$hierarchyUrlEsc' data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyle'>";
                    $list_data .= $cardBody;
                    $list_data .= "</a>";
                }
                else {
                    $list_data .= "<div style='$hierarchyCardStyle' data-toggle='tooltip' title='Mapping route belum aktif untuk kategori ini'>";
                    $list_data .= $cardBody;
                    $list_data .= "</div>";
                }
            }
            $totalHutang = isset($hutangHierarchyTotals['all']) ? (float)$hutangHierarchyTotals['all'] : 0;
            $totalHutangEsc = htmlspecialchars("Rp " . number_format($totalHutang, 0, ",", "."), ENT_QUOTES);
            $supplierCountAll = isset($hutangHierarchyTotals['supplier_count']) ? (int)$hutangHierarchyTotals['supplier_count'] : 0;
            $cabangCountAll = isset($hutangHierarchyTotals['cabang_count']) ? (int)$hutangHierarchyTotals['cabang_count'] : 0;
            $dimensiAllTxt = number_format($supplierCountAll, 0, ",", ".") . " " . $hutangHierarchyEntityLabel;
            if ($cabangCountAll > 0) {
                $dimensiAllTxt .= " | " . number_format($cabangCountAll, 0, ",", ".") . " cabang";
            }
            $dimensiAllTxtEsc = htmlspecialchars($dimensiAllTxt, ENT_QUOTES);
            $totalHutangLabel = ($hutangHierarchyEntityLabel == "customer") ? "Total Hutang ke Konsumen" : "Total Hutang";
            $totalHutangLabelEsc = htmlspecialchars($totalHutangLabel, ENT_QUOTES);
            $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
            $list_data .= "<span class='label label-warning' style='padding:6px 10px; font-size:12px; color:#5f3d13;'>$totalHutangLabelEsc: $totalHutangEsc</span>";
            $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Dimensi Aktif: $dimensiAllTxtEsc</span>";
            $list_data .= "</div>";
            if (strlen($hutangHierarchyNote) > 0) {
                $hutangHierarchyNoteEsc = htmlspecialchars($hutangHierarchyNote, ENT_QUOTES);
                $list_data .= "<span style='color: #8a6d3b; font-size: 12px;'>" . $hutangHierarchyNoteEsc . "</span>";
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $showTaxHierarchy = isset($showTaxHierarchy) ? (bool)$showTaxHierarchy : false;
        $taxHierarchyCurrent = isset($taxHierarchyCurrent) ? (string)$taxHierarchyCurrent : "";
        $taxHierarchyLinks = isset($taxHierarchyLinks) && is_array($taxHierarchyLinks) ? $taxHierarchyLinks : array();
        $taxHierarchyNote = isset($taxHierarchyNote) ? (string)$taxHierarchyNote : "";
        $taxHierarchyTotals = isset($taxHierarchyTotals) && is_array($taxHierarchyTotals) ? $taxHierarchyTotals : array();
        if ($showTaxHierarchy && sizeof($taxHierarchyLinks) > 0) {
            $totalTax = isset($taxHierarchyTotals['all']) ? (float)$taxHierarchyTotals['all'] : 0;
            $entityCountAll = isset($taxHierarchyTotals['entity_count']) ? (int)$taxHierarchyTotals['entity_count'] : 0;
            $cabangCountAll = isset($taxHierarchyTotals['cabang_count']) ? (int)$taxHierarchyTotals['cabang_count'] : 0;
            $activeAccountCount = isset($taxHierarchyTotals['active_account_count']) ? (int)$taxHierarchyTotals['active_account_count'] : 0;
            $plannedAccountCount = isset($taxHierarchyTotals['planned_account_count']) ? (int)$taxHierarchyTotals['planned_account_count'] : 0;
            $categoryTotals = isset($taxHierarchyTotals['category_totals']) && is_array($taxHierarchyTotals['category_totals']) ? $taxHierarchyTotals['category_totals'] : array();

            $taxSections = array();
            foreach ($taxHierarchyLinks as $rekCode => $hierarchySpec) {
                $catKey = isset($hierarchySpec['category_key']) ? trim((string)$hierarchySpec['category_key']) : "lainnya";
                if ($catKey == "") {
                    $catKey = "lainnya";
                }
                $catLabel = isset($hierarchySpec['category_label']) ? trim((string)$hierarchySpec['category_label']) : "Kategori Lainnya";
                if ($catLabel == "") {
                    $catLabel = "Kategori Lainnya";
                }
                if (!isset($taxSections[$catKey])) {
                    $taxSections[$catKey] = array(
                        "label" => $catLabel,
                        "items" => array(),
                    );
                }
                $taxSections[$catKey]["items"][] = array(
                    "rek_code" => $rekCode,
                    "label" => isset($hierarchySpec['label']) ? (string)$hierarchySpec['label'] : $rekCode,
                    "note" => isset($hierarchySpec['note']) ? (string)$hierarchySpec['note'] : "",
                    "url" => isset($hierarchySpec['url']) ? (string)$hierarchySpec['url'] : "#",
                    "saldo" => isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0,
                    "count" => isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0,
                    "cabang_count" => isset($hierarchySpec['cabang_count']) ? (int)$hierarchySpec['cabang_count'] : 0,
                    "status" => isset($hierarchySpec['status']) ? strtolower((string)$hierarchySpec['status']) : "active",
                    "route_enabled" => isset($hierarchySpec['route_enabled']) ? (bool)$hierarchySpec['route_enabled'] : false,
                );
            }

            foreach ($taxSections as $taxSectionKey => $taxSectionSpec) {
                if (isset($taxSections[$taxSectionKey]["items"]) && sizeof($taxSections[$taxSectionKey]["items"]) > 1) {
                    usort($taxSections[$taxSectionKey]["items"], function ($left, $right) {
                        $leftAbs = abs(isset($left["saldo"]) ? (float)$left["saldo"] : 0);
                        $rightAbs = abs(isset($right["saldo"]) ? (float)$right["saldo"] : 0);
                        if ($leftAbs == $rightAbs) {
                            $leftName = isset($left["label"]) ? strtolower((string)$left["label"]) : "";
                            $rightName = isset($right["label"]) ? strtolower((string)$right["label"]) : "";
                            if ($leftName == $rightName) {
                                return 0;
                            }
                            return ($leftName < $rightName) ? -1 : 1;
                        }
                        return ($leftAbs > $rightAbs) ? -1 : 1;
                    });
                }
            }

            $orderedSections = array();
            $preferredOrder = array("aset_lancar_pajak", "liabilitas_lancar_pajak", "beban_pajak");
            foreach ($preferredOrder as $catKeyOrder) {
                if (isset($taxSections[$catKeyOrder])) {
                    $orderedSections[$catKeyOrder] = $taxSections[$catKeyOrder];
                    unset($taxSections[$catKeyOrder]);
                }
            }
            foreach ($taxSections as $catKeyOrder => $catSpecOrder) {
                $orderedSections[$catKeyOrder] = $catSpecOrder;
            }

            $panelId = "tax-hierarchy-panel";
            $list_data .= "<div id='" . $panelId . "' style='flex: 1 1 100%; background: #f3f8fb; border: 1px solid #c2d6e3; padding: 10px 12px; border-radius: 6px;'>";
            $list_data .= "<div style='display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:8px; margin-bottom:6px;'>";
            $list_data .= "<div style='font-weight:700; color:#1f4f6d; font-size:15px;'><i class='fa fa-balance-scale'></i> Pajak PKP</div>";
            $list_data .= "<div style='display:flex; flex-wrap:wrap; gap:6px;'>";
            $list_data .= "<button type='button' data-tax-filter='all' style='border:1px solid #6aa0c0; background:#2f83b5; color:#fff; border-radius:4px; padding:4px 8px; font-size:11px;'>Semua</button>";
            $list_data .= "<button type='button' data-tax-filter='active' style='border:1px solid #9bb8ca; background:#fff; color:#355b74; border-radius:4px; padding:4px 8px; font-size:11px;'>Active</button>";
            $list_data .= "<button type='button' data-tax-filter='planned' style='border:1px solid #9bb8ca; background:#fff; color:#355b74; border-radius:4px; padding:4px 8px; font-size:11px;'>Planned</button>";
            $list_data .= "<button type='button' data-tax-filter='nonzero' style='border:1px solid #9bb8ca; background:#fff; color:#355b74; border-radius:4px; padding:4px 8px; font-size:11px;'>Saldo != 0</button>";
            $list_data .= "</div>";
            $list_data .= "</div>";

            $totalTaxEsc = htmlspecialchars("Rp " . number_format($totalTax, 0, ",", "."), ENT_QUOTES);
            $dimensiAllTxt = number_format($entityCountAll, 0, ",", ".") . " entitas";
            if ($cabangCountAll > 0) {
                $dimensiAllTxt .= " | " . number_format($cabangCountAll, 0, ",", ".") . " cabang";
            }
            $dimensiAllTxtEsc = htmlspecialchars($dimensiAllTxt, ENT_QUOTES);
            $list_data .= "<div style='display:flex; flex-wrap:wrap; gap:8px; margin-bottom:8px;'>";
            $list_data .= "<span class='label label-primary' style='padding:6px 10px; font-size:12px;'>Total Saldo Pajak: $totalTaxEsc</span>";
            $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Dimensi: $dimensiAllTxtEsc</span>";
            $list_data .= "<span class='label label-info' style='padding:6px 10px; font-size:12px;'>Akun Active: " . number_format($activeAccountCount, 0, ",", ".") . "</span>";
            if ($plannedAccountCount > 0) {
                $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Akun Planned: " . number_format($plannedAccountCount, 0, ",", ".") . "</span>";
            }
            $list_data .= "</div>";

            foreach ($orderedSections as $catKey => $catSpec) {
                $catLabel = isset($catSpec["label"]) ? (string)$catSpec["label"] : "Kategori";
                $catItems = isset($catSpec["items"]) && is_array($catSpec["items"]) ? $catSpec["items"] : array();
                $catSummary = isset($categoryTotals[$catKey]) && is_array($categoryTotals[$catKey]) ? $categoryTotals[$catKey] : array();
                $catSaldo = isset($catSummary["saldo"]) ? (float)$catSummary["saldo"] : 0;
                $catActive = isset($catSummary["active_account_count"]) ? (int)$catSummary["active_account_count"] : 0;
                $catSaldoEsc = htmlspecialchars("Rp " . number_format($catSaldo, 0, ",", "."), ENT_QUOTES);
                $catLabelEsc = htmlspecialchars($catLabel, ENT_QUOTES);
                $catMetaEsc = htmlspecialchars(number_format($catActive, 0, ",", ".") . " akun aktif", ENT_QUOTES);

                $list_data .= "<div data-tax-category='" . htmlspecialchars($catKey, ENT_QUOTES) . "' style='margin-top:8px;'>";
                $list_data .= "<div style='display:flex; flex-wrap:wrap; align-items:center; gap:6px; margin-bottom:6px;'>";
                $list_data .= "<span style='font-weight:700; color:#2e5f7d; font-size:13px;'>$catLabelEsc</span>";
                $list_data .= "<span class='label label-default' style='font-size:11px; padding:4px 8px;'>$catSaldoEsc</span>";
                $list_data .= "<span class='label label-default' style='font-size:11px; padding:4px 8px;'>$catMetaEsc</span>";
                $list_data .= "</div>";
                $list_data .= "<div style='display:flex; flex-wrap:wrap; gap:8px;'>";
                foreach ($catItems as $cardSpec) {
                    $hierarchyLabelEsc = htmlspecialchars(isset($cardSpec["label"]) ? (string)$cardSpec["label"] : "-", ENT_QUOTES);
                    $hierarchyCodeEsc = htmlspecialchars(isset($cardSpec["rek_code"]) ? (string)$cardSpec["rek_code"] : "-", ENT_QUOTES);
                    $hierarchyNoteEsc = htmlspecialchars(isset($cardSpec["note"]) ? (string)$cardSpec["note"] : "", ENT_QUOTES);
                    $hierarchyUrlEsc = htmlspecialchars(isset($cardSpec["url"]) ? (string)$cardSpec["url"] : "#", ENT_QUOTES);
                    $hierarchySaldo = isset($cardSpec["saldo"]) ? (float)$cardSpec["saldo"] : 0;
                    $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                    $hierarchyCount = isset($cardSpec["count"]) ? (int)$cardSpec["count"] : 0;
                    $hierarchyCabangCount = isset($cardSpec["cabang_count"]) ? (int)$cardSpec["cabang_count"] : 0;
                    $hierarchyStatus = isset($cardSpec["status"]) ? strtolower((string)$cardSpec["status"]) : "active";
                    if ($hierarchyStatus != "planned") {
                        $hierarchyStatus = "active";
                    }
                    $routeEnabled = isset($cardSpec["route_enabled"]) ? (bool)$cardSpec["route_enabled"] : false;
                    $isCurrentCard = (isset($cardSpec["rek_code"]) && (string)$cardSpec["rek_code"] == $taxHierarchyCurrent);
                    $isZero = (abs($hierarchySaldo) < 0.0001);
                    $dimensiTxt = number_format($hierarchyCount, 0, ",", ".") . " entitas";
                    if ($hierarchyCabangCount > 0) {
                        $dimensiTxt .= " | " . number_format($hierarchyCabangCount, 0, ",", ".") . " cabang";
                    }
                    $dimensiEsc = htmlspecialchars($dimensiTxt, ENT_QUOTES);

                    if ($hierarchyStatus == "planned") {
                        $cardStyle = "display:block; width:265px; border:1px dashed #bcc5cc; background:#fbfbfc; border-radius:6px; padding:8px 10px; text-decoration:none;";
                        $statusBadge = "<span class='label label-default' style='font-size:10px;'>Planned</span>";
                    }
                    else {
                        $cardStyle = $isCurrentCard
                            ? "display:block; width:265px; border:1px solid #2d87bb; background:#e8f4fc; border-radius:6px; padding:8px 10px; text-decoration:none;"
                            : "display:block; width:265px; border:1px solid #c9dbe6; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                        $statusBadge = "<span class='label label-info' style='font-size:10px;'>Active</span>";
                    }

                    $cardBody = "";
                    $cardBody .= "<div style='display:flex; justify-content:space-between; align-items:flex-start; gap:6px;'>";
                    $cardBody .= "<div style='font-weight:700; color:#1f4f6d; line-height:1.2;'>" . $hierarchyLabelEsc . "</div>";
                    $cardBody .= $statusBadge;
                    $cardBody .= "</div>";
                    $cardBody .= "<div style='font-size:10px; color:#607d90; margin-top:2px;'>(" . $hierarchyCodeEsc . ")</div>";
                    $cardBody .= "<div style='font-size:19px; font-weight:700; color:#1e638f; margin-top:6px;'>" . $hierarchySaldoEsc . "</div>";
                    $cardBody .= "<div style='font-size:11px; color:#607d90; margin-top:2px;'>" . $dimensiEsc . "</div>";
                    if ($hierarchyNoteEsc != "") {
                        $cardBody .= "<div style='font-size:11px; color:#6f8797; margin-top:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;' title='" . $hierarchyNoteEsc . "'>" . $hierarchyNoteEsc . "</div>";
                    }

                    $cardAttr = " data-tax-card='1' data-tax-status='" . $hierarchyStatus . "' data-tax-zero='" . ($isZero ? "1" : "0") . "'";
                    if ($routeEnabled) {
                        $list_data .= "<a href='" . $hierarchyUrlEsc . "' data-toggle='tooltip' title='" . $hierarchyNoteEsc . "' style='" . $cardStyle . "'" . $cardAttr . ">" . $cardBody . "</a>";
                    }
                    else {
                        $list_data .= "<div style='" . $cardStyle . "'" . $cardAttr . " data-toggle='tooltip' title='Akun best practice, mapping ledger belum aktif'>" . $cardBody . "</div>";
                    }
                }
                $list_data .= "</div>";
                $list_data .= "</div>";
            }

            if (strlen($taxHierarchyNote) > 0) {
                $taxHierarchyNoteEsc = htmlspecialchars($taxHierarchyNote, ENT_QUOTES);
                $list_data .= "<div style='margin-top:8px; color: #4f6f86; font-size: 12px;'>" . $taxHierarchyNoteEsc . "</div>";
            }
            $list_data .= "<script>
                (function () {
                    var root = document.getElementById('" . $panelId . "');
                    if (!root) {
                        return;
                    }
                    var buttons = root.querySelectorAll('[data-tax-filter]');
                    var cards = root.querySelectorAll('[data-tax-card]');
                    var sections = root.querySelectorAll('[data-tax-category]');
                    var applyFilter = function (filterMode) {
                        for (var i = 0; i < cards.length; i++) {
                            var card = cards[i];
                            var status = card.getAttribute('data-tax-status');
                            var isZero = card.getAttribute('data-tax-zero') === '1';
                            var visible = true;
                            if (filterMode === 'active' && status !== 'active') {
                                visible = false;
                            }
                            if (filterMode === 'planned' && status !== 'planned') {
                                visible = false;
                            }
                            if (filterMode === 'nonzero' && isZero) {
                                visible = false;
                            }
                            card.style.display = visible ? 'block' : 'none';
                        }
                        for (var j = 0; j < sections.length; j++) {
                            var section = sections[j];
                            var sectionCards = section.querySelectorAll('[data-tax-card]');
                            var hasVisible = false;
                            for (var k = 0; k < sectionCards.length; k++) {
                                if (sectionCards[k].style.display !== 'none') {
                                    hasVisible = true;
                                    break;
                                }
                            }
                            section.style.display = hasVisible ? 'block' : 'none';
                        }
                        for (var b = 0; b < buttons.length; b++) {
                            var btn = buttons[b];
                            if (btn.getAttribute('data-tax-filter') === filterMode) {
                                btn.style.background = '#2f83b5';
                                btn.style.color = '#fff';
                                btn.style.borderColor = '#6aa0c0';
                            }
                            else {
                                btn.style.background = '#fff';
                                btn.style.color = '#355b74';
                                btn.style.borderColor = '#9bb8ca';
                            }
                        }
                    };
                    for (var m = 0; m < buttons.length; m++) {
                        buttons[m].addEventListener('click', function () {
                            applyFilter(this.getAttribute('data-tax-filter'));
                        });
                    }
                    applyFilter('all');
                })();
            </script>";
            $list_data .= "</div>";
        }

        $showUangMukaHierarchy = isset($showUangMukaHierarchy) ? (bool)$showUangMukaHierarchy : false;
        $uangMukaHierarchyCurrent = isset($uangMukaHierarchyCurrent) ? (string)$uangMukaHierarchyCurrent : "";
        $uangMukaHierarchyLinks = isset($uangMukaHierarchyLinks) && is_array($uangMukaHierarchyLinks) ? $uangMukaHierarchyLinks : array();
        $uangMukaHierarchyNote = isset($uangMukaHierarchyNote) ? (string)$uangMukaHierarchyNote : "";
        $uangMukaHierarchyTotals = isset($uangMukaHierarchyTotals) && is_array($uangMukaHierarchyTotals) ? $uangMukaHierarchyTotals : array();
        $uangMukaMode = isset($uangMukaMode) ? trim((string)$uangMukaMode) : "hierarchy";
        $uangMukaModeOptions = isset($uangMukaModeOptions) && is_array($uangMukaModeOptions) ? $uangMukaModeOptions : array(
            "hierarchy" => "Akun",
            "pivot" => "Pivot Dimensi",
        );
        $uangMukaPivotRowDim = isset($uangMukaPivotRowDim) ? trim((string)$uangMukaPivotRowDim) : "supplier";
        $uangMukaPivotColDim = isset($uangMukaPivotColDim) ? trim((string)$uangMukaPivotColDim) : "none";
        $uangMukaPivotMetric = isset($uangMukaPivotMetric) ? trim((string)$uangMukaPivotMetric) : "saldo_open";
        $uangMukaPivotRowOptions = isset($uangMukaPivotRowOptions) && is_array($uangMukaPivotRowOptions) ? $uangMukaPivotRowOptions : array("supplier" => "Supplier");
        $uangMukaPivotColOptions = isset($uangMukaPivotColOptions) && is_array($uangMukaPivotColOptions) ? $uangMukaPivotColOptions : array("none" => "(Tanpa Kolom)");
        $uangMukaPivotMetricOptions = isset($uangMukaPivotMetricOptions) && is_array($uangMukaPivotMetricOptions) ? $uangMukaPivotMetricOptions : array("saldo_open" => "Saldo Open (IDR)");
        if ($showUangMukaHierarchy && sizeof($uangMukaHierarchyLinks) > 0) {
            $list_data .= "<div style='flex: 1 1 100%; background: #eef3ff; border: 1px solid #ccd8fb; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
            $list_data .= "<span style='font-weight: 600; color: #374b94;'><i class='fa fa-sitemap'></i> Uang Muka:</span>";
            foreach ($uangMukaHierarchyLinks as $rekCode => $hierarchySpec) {
                $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
                $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
                $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
                $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
                $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;

                $isActiveHierarchy = ($uangMukaHierarchyCurrent == $rekCode);
                $hierarchyCardStyle = $isActiveHierarchy
                    ? "display:block; min-width:240px; border:1px solid #6f8df0; background:#e4ecff; border-radius:6px; padding:8px 10px; text-decoration:none;"
                    : "display:block; min-width:240px; border:1px solid #d2dbf7; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
                $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
                $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
                $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
                $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
                $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
                $hierarchyCountEsc = htmlspecialchars(number_format($hierarchyCount, 0, ",", ".") . " supplier", ENT_QUOTES);

                $list_data .= "<a href='$hierarchyUrlEsc' data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyle'>";
                $list_data .= "<div style='font-weight:600; color:#374b94;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
                $list_data .= "<div style='font-size:11px; color:#5f6f9c; margin-top:2px;'>$hierarchyNoteEsc</div>";
                $list_data .= "<div style='font-size:17px; font-weight:700; color:#2f4688; margin-top:3px;'>$hierarchySaldoEsc</div>";
                $list_data .= "<div style='font-size:11px; color:#5f6f9c;'>$hierarchyCountEsc</div>";
                $list_data .= "</a>";
            }

            $totalUangMuka = isset($uangMukaHierarchyTotals['all']) ? (float)$uangMukaHierarchyTotals['all'] : 0;
            $totalUangMukaEsc = htmlspecialchars("Rp " . number_format($totalUangMuka, 0, ",", "."), ENT_QUOTES);
            $supplierCountUangMuka = isset($uangMukaHierarchyTotals['supplier_count']) ? (int)$uangMukaHierarchyTotals['supplier_count'] : 0;
            $supplierCountUangMukaEsc = htmlspecialchars(number_format($supplierCountUangMuka, 0, ",", ".") . " supplier unik", ENT_QUOTES);
            $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
            $list_data .= "<span class='label label-primary' style='padding:6px 10px; font-size:12px;'>Total Uang Muka: $totalUangMukaEsc</span>";
            $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Dimensi Aktif: $supplierCountUangMukaEsc</span>";
            $list_data .= "</div>";
            if (strlen($uangMukaHierarchyNote) > 0) {
                $uangMukaHierarchyNoteEsc = htmlspecialchars($uangMukaHierarchyNote, ENT_QUOTES);
                $list_data .= "<span style='color: #5567a0; font-size: 12px;'>" . $uangMukaHierarchyNoteEsc . "</span>";
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $showAdvanceAudit = isset($showAdvanceAudit) ? (bool)$showAdvanceAudit : false;
        $advanceAudit = isset($advanceAudit) && is_array($advanceAudit) ? $advanceAudit : array();
        if ($showAdvanceAudit && isset($advanceAudit['enabled']) && $advanceAudit['enabled']) {
            $auditProfile = isset($advanceAudit['profile']) ? (string)$advanceAudit['profile'] : "";
            $auditAsOf = isset($advanceAudit['as_of_date']) ? (string)$advanceAudit['as_of_date'] : "-";
            $auditGroupField = isset($advanceAudit['group_field']) ? (string)$advanceAudit['group_field'] : "-";
            $auditSourceTotal = isset($advanceAudit['due_source_total']) ? (int)$advanceAudit['due_source_total'] : 0;
            $auditSourceValid = isset($advanceAudit['due_source_valid']) ? (int)$advanceAudit['due_source_valid'] : 0;
            $auditSkipNoGroup = isset($advanceAudit['due_skipped_no_group']) ? (int)$advanceAudit['due_skipped_no_group'] : 0;
            $auditSkipNoDate = isset($advanceAudit['due_skipped_no_due_date']) ? (int)$advanceAudit['due_skipped_no_due_date'] : 0;
            $auditSkipAfterAsOf = isset($advanceAudit['due_skipped_posting_after_as_of']) ? (int)$advanceAudit['due_skipped_posting_after_as_of'] : 0;
            $auditDueEmployees = isset($advanceAudit['due_employee_count']) ? (int)$advanceAudit['due_employee_count'] : 0;
            $auditRowsNonZero = isset($advanceAudit['rows_balance_nonzero']) ? (int)$advanceAudit['rows_balance_nonzero'] : 0;
            $auditRowsApplied = isset($advanceAudit['rows_due_applied']) ? (int)$advanceAudit['rows_due_applied'] : 0;
            $auditRowsFallback = isset($advanceAudit['rows_due_fallback']) ? (int)$advanceAudit['rows_due_fallback'] : 0;
            $auditRowsMissing = isset($advanceAudit['rows_due_missing']) ? (int)$advanceAudit['rows_due_missing'] : 0;
            $auditRowsZero = isset($advanceAudit['rows_balance_zero']) ? (int)$advanceAudit['rows_balance_zero'] : 0;
            $auditRowsHidden = isset($advanceAudit['rows_due_hidden_balance_zero']) ? (int)$advanceAudit['rows_due_hidden_balance_zero'] : 0;
            $auditMissingIds = isset($advanceAudit['sample_missing_ids']) && is_array($advanceAudit['sample_missing_ids']) ? $advanceAudit['sample_missing_ids'] : array();

            $auditTitle = "Audit Due/Aging";
            if ($auditProfile != "") {
                $auditTitle .= " (" . htmlspecialchars($auditProfile, ENT_QUOTES) . ")";
            }
            $list_data .= "<div style='flex: 1 1 100%; background: #f7f7f7; border: 1px dashed #bcbcbc; padding: 8px 12px; border-radius: 4px;'>";
            $list_data .= "<div style='font-weight:600; color:#555; margin-bottom:6px;'><i class='fa fa-bug'></i> " . $auditTitle . "</div>";
            $list_data .= "<div style='display:flex; flex-wrap:wrap; gap:6px; margin-bottom:6px;'>";
            $list_data .= "<span class='label label-default'>As Of: " . htmlspecialchars($auditAsOf, ENT_QUOTES) . "</span>";
            $list_data .= "<span class='label label-default'>Group Field: " . htmlspecialchars($auditGroupField, ENT_QUOTES) . "</span>";
            $list_data .= "<span class='label label-info'>Due Src: " . number_format($auditSourceValid, 0, ",", ".") . "/" . number_format($auditSourceTotal, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-default'>Skip(no group): " . number_format($auditSkipNoGroup, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-default'>Skip(no due): " . number_format($auditSkipNoDate, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-default'>Skip(>asof): " . number_format($auditSkipAfterAsOf, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-primary'>Due Linked: " . number_format($auditDueEmployees, 0, ",", ".") . "</span>";
            $list_data .= "</div>";
            $list_data .= "<div style='display:flex; flex-wrap:wrap; gap:6px;'>";
            $list_data .= "<span class='label label-success'>Saldo!=0: " . number_format($auditRowsNonZero, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-success'>Due Applied: " . number_format($auditRowsApplied, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-warning'>Fallback Used: " . number_format($auditRowsFallback, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-danger'>Due Missing: " . number_format($auditRowsMissing, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-default'>Saldo=0: " . number_format($auditRowsZero, 0, ",", ".") . "</span>";
            $list_data .= "<span class='label label-default'>Hidden@Saldo0: " . number_format($auditRowsHidden, 0, ",", ".") . "</span>";
            $list_data .= "</div>";
            if (sizeof($auditMissingIds) > 0) {
                $list_data .= "<div style='margin-top:6px; font-size:12px; color:#666;'>";
                $list_data .= "Sample missing extern_id: " . htmlspecialchars(implode(", ", $auditMissingIds), ENT_QUOTES);
                $list_data .= "</div>";
            }
            $list_data .= "</div>";
        }

        $selectedPeriode = isset($selectedPeriode) ? $selectedPeriode : "full";
        $selectedThn = isset($selectedThn) ? (int)$selectedThn : (int)date("Y");
        $selectedBln = isset($selectedBln) ? (int)$selectedBln : (int)date("m");
        $selectedDate = isset($selectedDate) && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $selectedDate) ? $selectedDate : date("Y-m-d");
        $todayDate = date("Y-m-d");
        $currentYear = isset($currentYear) ? (int)$currentYear : (int)date("Y");
        $filterBaseUrl = isset($filterBaseUrl) && strlen($filterBaseUrl) ? $filterBaseUrl : $thisPage;
        $filterBaseUrlSafe = str_replace("\\", "\\\\", $filterBaseUrl);
        $filterBaseUrlSafe = str_replace("'", "\\'", $filterBaseUrlSafe);
        $periodeOptions = array(
            "full" => "Full (Tahun Berjalan)",
            "tahunan" => "Tahunan",
            "bulanan" => "Bulanan",
            "harian" => "Harian (As Of)",
        );
        $showYear = ($selectedPeriode == "tahunan" || $selectedPeriode == "bulanan");
        $showMonth = ($selectedPeriode == "bulanan");
        $showDate = ($selectedPeriode == "harian");
        $showUangMukaPivotFields = ($showUangMukaHierarchy && $uangMukaMode == "pivot");

        $list_data .= "<div style='flex-shrink: 0; background: #f8f9fa; padding: 6px 12px; border-radius: 4px; border: 1px solid #ddd;'>";
        $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
        $list_data .= "<span style='font-weight: 500; color: #555;'><i class='fa fa-calendar'></i> Periode:</span>";
        $list_data .= "<select id='filter-periode' class='form-control' style='width: 210px;' onchange='ledgerPeriodFilterToggle()'>";
        foreach ($periodeOptions as $periodeKey => $periodeLabel) {
            $selectedAttr = ($selectedPeriode == $periodeKey) ? "selected" : "";
            $list_data .= "<option value='$periodeKey' $selectedAttr>$periodeLabel</option>";
        }
        $list_data .= "</select>";

        $list_data .= "<span id='filter-thn-wrap' style='display:" . ($showYear ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
        $list_data .= "<select id='filter-thn' class='form-control' style='width: 100px;'>";
        for ($yearOpt = $currentYear; $yearOpt >= ($currentYear - 10); $yearOpt--) {
            $selectedAttr = ($selectedThn == $yearOpt) ? "selected" : "";
            $list_data .= "<option value='$yearOpt' $selectedAttr>$yearOpt</option>";
        }
        $list_data .= "</select>";
        $list_data .= "</span>";

        $list_data .= "<span id='filter-bln-wrap' style='display:" . ($showMonth ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
        $list_data .= "<select id='filter-bln' class='form-control' style='width: 90px;'>";
        for ($monthOpt = 1; $monthOpt <= 12; $monthOpt++) {
            $selectedAttr = ($selectedBln == $monthOpt) ? "selected" : "";
            $monthLabel = date("M", mktime(0, 0, 0, $monthOpt, 1, 2000));
            $list_data .= "<option value='$monthOpt' $selectedAttr>$monthLabel</option>";
        }
        $list_data .= "</select>";
        $list_data .= "</span>";

        $list_data .= "<span id='filter-date-wrap' style='display:" . ($showDate ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
        $list_data .= "<input type='date' id='filter-date' class='form-control' style='width: 160px;' value='$selectedDate' max='$todayDate'>";
        $list_data .= "</span>";

        if ($showUangMukaHierarchy && sizeof($uangMukaModeOptions) > 0) {
            $list_data .= "<span style='display:inline-flex; align-items:center; gap:6px; margin-left: 6px;'>";
            $list_data .= "<span style='font-weight: 500; color: #555;'><i class='fa fa-table'></i> Mode:</span>";
            $list_data .= "<select id='filter-um-mode' class='form-control' style='width: 180px;' onchange='ledgerUangMukaModeToggle()'>";
            foreach ($uangMukaModeOptions as $modeKey => $modeLabel) {
                $selectedMode = ($uangMukaMode == $modeKey) ? "selected" : "";
                $list_data .= "<option value='$modeKey' $selectedMode>$modeLabel</option>";
            }
            $list_data .= "</select>";
            $list_data .= "</span>";

            $list_data .= "<span id='filter-um-row-wrap' style='display:" . ($showUangMukaPivotFields ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
            $list_data .= "<select id='filter-um-row' class='form-control' style='width: 150px;'>";
            foreach ($uangMukaPivotRowOptions as $rowKey => $rowLabel) {
                $selectedRow = ($uangMukaPivotRowDim == $rowKey) ? "selected" : "";
                $list_data .= "<option value='$rowKey' $selectedRow>Baris: $rowLabel</option>";
            }
            $list_data .= "</select>";
            $list_data .= "</span>";

            $list_data .= "<span id='filter-um-col-wrap' style='display:" . ($showUangMukaPivotFields ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
            $list_data .= "<select id='filter-um-col' class='form-control' style='width: 170px;'>";
            foreach ($uangMukaPivotColOptions as $colKey => $colLabel) {
                $selectedCol = ($uangMukaPivotColDim == $colKey) ? "selected" : "";
                $list_data .= "<option value='$colKey' $selectedCol>Kolom: $colLabel</option>";
            }
            $list_data .= "</select>";
            $list_data .= "</span>";

            $list_data .= "<span id='filter-um-metric-wrap' style='display:" . ($showUangMukaPivotFields ? "inline-flex" : "none") . "; align-items:center; gap:6px;'>";
            $list_data .= "<select id='filter-um-metric' class='form-control' style='width: 220px;'>";
            foreach ($uangMukaPivotMetricOptions as $metricKey => $metricLabel) {
                $selectedMetric = ($uangMukaPivotMetric == $metricKey) ? "selected" : "";
                $list_data .= "<option value='$metricKey' $selectedMetric>Metric: $metricLabel</option>";
            }
            $list_data .= "</select>";
            $list_data .= "</span>";
        }

        $list_data .= "<button type='button' class='btn btn-default' onclick='ledgerApplyPeriodFilter(false)'><i class='fa fa-filter'></i> Terapkan</button>";
        $list_data .= "</div>";
        $list_data .= "</div>";

        $list_data .= "<script>
            function ledgerPeriodFilterToggle() {
                var periodSelect = document.getElementById('filter-periode');
                var periodValue = periodSelect ? periodSelect.value : 'full';
                var yearWrap = document.getElementById('filter-thn-wrap');
                var monthWrap = document.getElementById('filter-bln-wrap');
                var dateWrap = document.getElementById('filter-date-wrap');
                if (yearWrap) {
                    yearWrap.style.display = (periodValue === 'tahunan' || periodValue === 'bulanan') ? 'inline-flex' : 'none';
                }
                if (monthWrap) {
                    monthWrap.style.display = (periodValue === 'bulanan') ? 'inline-flex' : 'none';
                }
                if (dateWrap) {
                    dateWrap.style.display = (periodValue === 'harian') ? 'inline-flex' : 'none';
                }
                ledgerUangMukaModeToggle();
            }
            function ledgerUangMukaModeToggle() {
                var modeSelect = document.getElementById('filter-um-mode');
                if (!modeSelect) {
                    return;
                }
                var modeValue = modeSelect.value || 'hierarchy';
                var rowWrap = document.getElementById('filter-um-row-wrap');
                var colWrap = document.getElementById('filter-um-col-wrap');
                var metricWrap = document.getElementById('filter-um-metric-wrap');
                var isPivot = (modeValue === 'pivot');
                if (rowWrap) {
                    rowWrap.style.display = isPivot ? 'inline-flex' : 'none';
                }
                if (colWrap) {
                    colWrap.style.display = isPivot ? 'inline-flex' : 'none';
                }
                if (metricWrap) {
                    metricWrap.style.display = isPivot ? 'inline-flex' : 'none';
                }
            }
            function ledgerApplyPeriodFilter(resetKeyword) {
                var baseUrl = '" . $filterBaseUrlSafe . "';
                var periodSelect = document.getElementById('filter-periode');
                var periodValue = periodSelect ? periodSelect.value : 'full';
                var url = baseUrl + '&periode=' + encodeURIComponent(periodValue);
                if (periodValue === 'tahunan' || periodValue === 'bulanan') {
                    var thnSelect = document.getElementById('filter-thn');
                    var thnValue = thnSelect ? thnSelect.value : '';
                    if (thnValue !== '') {
                        url += '&thn=' + encodeURIComponent(thnValue);
                    }
                }
                if (periodValue === 'bulanan') {
                    var blnSelect = document.getElementById('filter-bln');
                    var blnValue = blnSelect ? blnSelect.value : '';
                    if (blnValue !== '') {
                        url += '&bln=' + encodeURIComponent(blnValue);
                    }
                }
                if (periodValue === 'harian') {
                    var dateInput = document.getElementById('filter-date');
                    var dateValue = dateInput ? dateInput.value : '';
                    if (dateValue !== '') {
                        url += '&date=' + encodeURIComponent(dateValue);
                    }
                }
                var umModeSelect = document.getElementById('filter-um-mode');
                if (umModeSelect) {
                    var umModeValue = umModeSelect.value || 'hierarchy';
                    url += '&mode=' + encodeURIComponent(umModeValue);
                    if (umModeValue === 'pivot') {
                        var umRowSelect = document.getElementById('filter-um-row');
                        var umColSelect = document.getElementById('filter-um-col');
                        var umMetricSelect = document.getElementById('filter-um-metric');
                        if (umRowSelect && umRowSelect.value !== '') {
                            url += '&row_dim=' + encodeURIComponent(umRowSelect.value);
                        }
                        if (umColSelect && umColSelect.value !== '') {
                            url += '&col_dim=' + encodeURIComponent(umColSelect.value);
                        }
                        if (umMetricSelect && umMetricSelect.value !== '') {
                            url += '&metric=' + encodeURIComponent(umMetricSelect.value);
                        }
                    }
                }
                var qInput = document.getElementById('q');
                if (!resetKeyword && qInput && qInput.value !== '') {
                    url += '&q=' + encodeURIComponent(qInput.value);
                }
                document.location.href = url;
            }
            function ledgerResetKeyword() {
                var qInput = document.getElementById('q');
                if (qInput) {
                    qInput.value = '';
                }
                ledgerApplyPeriodFilter(true);
            }
            ledgerPeriodFilterToggle();
            ledgerUangMukaModeToggle();
        </script>";

        // Container untuk Search (memakai sisa ruang) - DIPERBAIKI
        $list_data .= "<div style='flex-grow: 1; min-width: 300px; max-width: 500px;'>";
        $list_data .= "<div style='display: flex; width: 100%;'>";
        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='Cari produk...' onfocus='this.select()' onkeydown=\"if(event.key === 'Enter'){ledgerApplyPeriodFilter(false);}\" style='border-top-right-radius: 0; border-bottom-right-radius: 0;'>";
        $list_data .= "<button class='btn btn-outline-secondary' type='button' title='Hapus kata kunci' onclick='ledgerResetKeyword()' style='border-radius: 0; border-left: none;'>";
        $list_data .= "<i class='fa fa-times'></i>";
        $list_data .= "</button>";
        $list_data .= "<button class='btn btn-primary' type='button' title='Cari' onclick='ledgerApplyPeriodFilter(false)' style='border-top-left-radius: 0; border-bottom-left-radius: 0;'>";
        $list_data .= "<i class='fa fa-search'></i>";
        $list_data .= "</button>";

//        $pakai_ini = 1;
//        if($pakai_ini == 1){
//            $list_data .= $p->selectTahun($defaultDate, "date");
//        }

        $list_data .= "</div>";
        $list_data .= "</div>";

        $list_data .= "</div>";
        $list_data .= "</div>";
        //endregion


        $data_total = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 1==========
            //========================

            //========================
            //========AREA HEADER LEVEL 2==========
            //            $data_total .= "<tr bgcolor='#e5e5e5'>";
            //            $data_total .= "<th align='right'></th>";
            //            foreach ($headerFields as $cName => $cValue) {
            //                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'></th>";
            //            }
            //            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 2==========
            //========================

            $data_total .= "</thead>";

            $total = array();
            $iCtr = 0;
            //arrPrint($items);
            //            arrPrint($headerFields);
            //            arrPrint($pairedSerial_add);
            $data_total .= "<tbody>";
            foreach ($items as $cData) {
                $iCtr++;
                //                arrPrintWebs($cData);
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";

                //                arrPrint($pairedSerial_add);

                $data_total .= "<tr style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $class_null = $cValue > 0 ? "isi" : "kosong";
                    $orderValue = $cValue;
                    $headerKeyNormalized = strtolower((string)$headerKey);
                    $isDueDateField = (strpos($headerKeyNormalized, "due_date") !== FALSE) || (strpos($headerKeyNormalized, "duedate") !== FALSE);
                    if ($isDueDateField) {
                        $dueDateRaw = trim(strip_tags((string)$cValue));
                        if ($dueDateRaw !== "") {
                            $dueDateTs = strtotime($dueDateRaw);
                            if ($dueDateTs !== FALSE) {
                                $orderValue = date("YmdHis", $dueDateTs);
                            }
                            else {
                                $orderValue = strtolower($dueDateRaw);
                            }
                        }
                        else {
                            $orderValue = "";
                        }
                    }
                    elseif (is_numeric($orderValue)) {
                        $orderValue = (string)(0 + $orderValue);
                    }
                    else {
                        $orderValue = strtolower(trim(strip_tags((string)$orderValue)));
                    }
                    $orderValueAttr = htmlspecialchars((string)$orderValue, ENT_QUOTES);

                    if (isset($customLinkAdd) && count($customLinkAdd) > 0) {
                        if (isset($customLinkAdd[$pid]["customLink"][$headerKey])) {
                            $adlink = $customLinkAdd[$pid]["customLink"][$headerKey];
                            $link = $cData['link'] . "&w=$adlink";
                            //                            matiHere();
                        }
                        else {
                            //                            cekMerah($headerKey);
                            $link = $cData['link'];
                        }

                    }
                    else {
                        $link = $cData['link'];
                    }
                    $linkMain = isset($cData['link_main'][$headerKey]) ? $cData['link_main'][$headerKey] : NULL;

                    $data_total .= "<td title='$headerKey' class='$class_null' data-order='$orderValueAttr'>";
                    // $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";

                    if ($linkMain != NULL) {
                        $data_total .= "<span class='pull-right'><a href='$linkMain' data-toggle='tooltip' title='mutasi $cValue' target='_blank'><span class='text-muted fa fa-clock-o'></span></a></span>";
                    }

                    if ($headerKey == "extern_nama") {
                        if (isset($pairedResult_add[$cData['pId']]['link_history']) && ($pairedResult_add[$cData['pId']]['link_history'] != NULL)) {
                            $historyClick = $pairedResult_add[$cData['pId']]['link_history'];
                            $data_total .= "<a href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view data histories of this entry' 
                                onclick=\"$historyClick\">
                                <span class='pull-right text-muted fa fa-clock-o'></span>
                                </a>";
                        }
                        if (isset($pairedResult_add[$cData['pId']]['keterangan'])) {
                            $keterangan = "\n" . $pairedResult_add[$cData['pId']]['keterangan'];
                            $data_total .= nl2br($keterangan);
                        }
                    }
                    if ($headerKey == "jml_serial") {
                        /* ------------------------------
                         * serial viewer
                         * --------------------------------*/
                        $qty_debet_nya = $cData['qty_debet'];
                        // cekHere("$cValue % $qty_debet_nya");
                        $sisa_serial = $cValue >= $qty_debet_nya ? $cValue % $qty_debet_nya : 0;
                        if ($sisa_serial > 0) {
                            $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                            // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                            $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                        }
                        else {
                            $sisa_serial_f = "";
                            $sisa_title = "$cValue";
                        }
                        $jml_serial_ok = $cValue >= $qty_debet_nya ? $cValue - $sisa_serial : $cValue;

                        if ($qty_debet_nya > 0) {
                            $btn_serial_number = "<button type='button' class='btn btn-success *******' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' 
    onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        else {
                            $btn_serial_number = "<button type='button' class='btn btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                        }
                        // -----------------------------------------
                        if (isset($pairedSerial_add[$cData['pId']]['link_serial']) && ($pairedSerial_add[$cData['pId']]['link_serial'] != NULL)) {

                            $historyClick_serial = $pairedSerial_add[$cData['pId']]['link_serial'];
                            $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_barcode'])) {
                            $historyClick_barcode = $pairedSerial_add[$cData['pId']]['link_barcode'];
                            $data_total2 = "<span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "<span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>";
                        }
                        /* ----------------------------------
                         * penampil button
                         * -----------------------------*/
                        if ($cData["tipe_produk"] == "serial") {
                            $data_total .= "<div class=\"btn-group pull-right\" >";
                            $data_total .= $btn_serial_number;
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey])) {
                                $data_total .= "
                              <button type='button' class='btn btn-success' title='lihat detail serial'>$data_total1</button>
                              <button type='button' class='btn btn-warning' title='cetak serial barcode'>$data_total2</button>
                              <button type='button' class='btn btn-danger' title='cetak serial qr'>$data_total3</button>";
                            }
                            $data_total .= "</div>";
                        }
                        else {
                            $data_total .= "-";
                        }
                    }
                    else {
                        // qty produk gudang ---------------------------------------------------------
                        $aa_var = "<button onclick=\"window.open('$link', '_blank')\" type='button' data-toggle='tooltip' class='btn btn-xs btn-warning' title='saldo qty $cValue'>" . formatField($headerKey, $cValue) . "</button>";
                        if (isset($pairedSerial_add[$pid][$headerKey])) {
                            //                            $data_total .=$pairedSerial_add[$pid][$headerKey]["jml_serial"]."~~";
                            $qty_debet_nya = $cData['qty_debet'];
                            // cekHere("$cValue % $qty_debet_nya");
                            $sisa_serial = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] % $qty_debet_nya : 0;
                            if ($sisa_serial > 0) {
                                $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                                // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                                $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                            }
                            else {
                                $sisa_serial_f = "";
                                $sisa_title = "$cValue";
                            }

                            //                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] - $sisa_serial : $pairedSerial_add[$pid][$headerKey]["jml_serial"];

                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"];
                            $jml_serial_transit_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial_transit"];

                            //                            if ($qty_debet_nya > 0) { //<<=========== INI PENYEBAB NYA
                            $historyClick_serial_transit = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr_transit'];
                            $btn_serial_number2 = "<button type='button' pid=$pid headerKey=$headerKey 
                                class='btn btn-xs btn-danger' data-toggle='tooltip' title='jumlah serial intransit' style='ppadding: 3px 5px;width: 47px;' 
                                onclick=\"$historyClick_serial_transit\" >$jml_serial_transit_ok</button>";
                            $btn_serial_number = "<button type='button' pid=$pid headerKey=$headerKey class='btn btn-xs btn-success' data-toggle='tooltip' title='jumlah serial' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok</button>";

                            //                            }

                            //                            elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            //                                $link_remove = $linkRemoveSerial . "/$pid";
                            //                                $sisa_title .= "serial number bisa diremove";
                            //                                $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-xs btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                            //                            }
                            //                            else {
                            //                                $btn_serial_number = "<button type='button' class='btn btn-xs btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                            //                            }
                            // -----------------------------------------
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_serial']) && ($pairedSerial_add[$cData['pId']][$headerKey]['link_serial'] != NULL)) {

                                $historyClick_serial = $pairedSerial_add[$cData['pId']][$headerKey]['link_serial'];
                                $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'])) {
                                $historyClick_barcode = $pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'];
                                $data_total2 = "
                                <span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_qr'])) {
                                $historyClick_qr = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr'];
                                $data_total3 = "
                                <span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>
                                ";
                            }
                            /* ----------------------------------
                             * penampil button
                             * -----------------------------*/
                            //                            cekHere($cData["tipe_produk"]);
                            if ($cData["tipe_produk"] == "serial") {
                                $data_total .= "<div class=\"btn-group pull-left\" >";
                                $data_total .= $btn_serial_number2;
                                $data_total .= $btn_serial_number;
                                if (isset($pairedSerial_add[$cData['pId']][$headerKey]) && $cValue > 0) {
                                    $data_total .= "
                                                    <button type='button' class='btn btn-xs btn-success' title='lihat detail serial'>$data_total1 </button>
                                                    <button type='button' class='btn btn-xs btn-warning' title='cetak serial barcode'>  $data_total2</button>
                                                    <button type='button' class='btn btn-xs btn-danger' title='cetak serial qr'>  $data_total3</button>";
                                }

                                $data_total .= "$aa_var";
                                $data_total .= "</div>";
                            }
                            else {
                                // disini tipe produk bukan serial, ditampilkan apa adanya... 02 maret 2024
                                //                                $data_total .= "-";
                                //                                $data_total .= "$aa_var";
                                $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                            }
                        }
                        else {
                            //                            cekHere("tidak ada paired serial");
                            //                            $data_total .="$aa_var";
                            $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank' >" . formatField($headerKey, $cValue) . "</a>";
                        }
                        //                        $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                    }

                    if (($headerKey == "ng_qty_debet") && ($cData["ng_qty_debet"] > 0)) {
                        $historyClick_barcode = $pairedGudang_add[$cData['pId']]['link_history'];
                        $data_total .= "
                            <button type='button' class='btn btn-primary btn-xs' title='lihat detail stok per-gudang'>
                            <span class='fa fa-home' onclick=\"$historyClick_barcode\"></span>
                            </button>
                        ";
                    }

                    $data_total .= "</td>";


                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";


            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;' title='$cName'>" . $totalVal . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table dataTable compact nowrap display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $data_total .= "<tbody>";
            $data_total .= "</tbody>";

            $data_total .= "<tfoot masuk_kosong>";
            $data_total .= "<tr bgcolor='#e5e5e5' id='current-page-footer'>";
            $data_total .= "</tr>";
            $data_total .= "<tr bgcolor='#e5e500' id='all-data-footer'>";
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

            //            $list_data .= "<div class='panel panel-default'>";
            //            $list_data .= "<div class='panel-body'>";
            //            $list_data .= "there is no item name matched your criteria<br>";
            //            $list_data .= "you mant want to go back or select other keyword<br>";
            //            $list_data .= "</div>";
            //            $list_data .= "</div>";
        }


        $params = array(
            "fifo" => "MdlFifoAverage",
            "cabang_id" => my_cabang_id(),
        );
        $headerFields_json = array_merge(array("no" => "no"), $headerFields);
        $paramEs = blobEncode($params);
        $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "link_excel" => $linkExcell,
            "server" => $server,
            "server_json" => $server_json,
            "url_serverside" => $url_serverside,
            "headerFields" => json_encode($headerFields_json),
            // "link_excel"       => $link_excel, // ikut yg tombol atas


        ));

        $p->setContent($contens);
        $p->render();
        break;
    case "saldoHereDoc":
        /**
         * metode heredoc
         */
        $add_style = "font-size:20px;";
        $contens = "";
        $p = new Layout($title, $subTitle, "application/template/default.html");

        // Template Table (CI Table Class)
        $template = [
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        ];
        $this->table->set_template($template);

        $list_data = "";
        $memberships = $_SESSION['login']['membership'];

        // region: SEARCH BAR + BUTTON
        $link_excel = base_url() . "ExcelWriter/persediaan/{$param_to_excel}";
        $allowBtns = ["c_gudang", "c_gudang_spv", "c_holding"];

        $list_data .= <<<HTML
<div class='panel'>
    <div class='input-group'>
        <span class='input-group-btn'>
HTML;

        // tombol Excel
        if (isset($param_to_excel)) {
            $btnExcels = [];
            foreach ($memberships as $membership) {
                if (in_array($membership, $allowBtns)) {
                    $btnExcels[] = $membership;
                }
            }

            if (!empty($btnExcels)) {
                $list_data .= <<<HTML
            <button type='button' class='btn btn-primary' 
                    data-toggle='tooltip' 
                    title='download seluruh data ke excel' 
                    data-placement='right' 
                    onclick="btn_alert_result('Excell','Download data akan muncul setelah beberapa saat diklik','{$link_excel}');">
                <i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk
            </button>
HTML;
            }
            else {
                $list_data .= <<<HTML
            <button type='button' disabled class='btn btn-default' 
                    data-toggle='tooltip' 
                    title='download ke excel' 
                    data-placement='right'>
                <i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk
            </button>
HTML;
            }
        }

        // date picker
        if (!empty($dateSelected)) {
            $maxDate = date("Y-m-d");
            $list_data .= <<<HTML
            <span class='input-group-add-on'>select month </span>
            <input type='date' class='form-control' 
                   value="{$defaultDate}" 
                   min="{$oldDate}" 
                   max="{$maxDate}" 
                   onchange="location.href='{$thisPage}&date='+this.value;">
HTML;
        }

        // remove keyword + search box
        $list_data .= <<<HTML
            <a class='btn btn-default' href="javascript:void(0)" 
               title='remove keyword' data-toggle='tooltip' data-placement='right' 
               onclick="document.location.href='{$thisPage}&q=';">
                <span class='glyphicon glyphicon-remove'></span>
            </a>
        </span>
        <input type='text' name='q' id='q' class='form-control' 
               value="{$q}" 
               placeholder="{$q} (type to search..)" 
               onfocus='this.select()' 
               onkeydown="if(detectEnter()==true){document.location.href='{$thisPage}&q='+this.value;}">
        <span class='input-group-btn'>
            <a class='btn btn-default' href='javascript:void(0)' 
               title='search using keyword' data-toggle='tooltip' data-placement='left' 
               onclick="document.location.href='{$thisPage}&q='+document.getElementById('q').value;">
                <span class='glyphicon glyphicon-search'></span>
            </a>
        </span>
    </div>
</div>
HTML;
        // endregion

        // region: TABLE DATA
        $data_total = "";
        if (!empty($items)) {
            $data_total .= <<<HTML
    <div class='table-responsive myNewTable'>
        <table id='myNewTable' class='table display'>
            <thead>
                <tr bgcolor='#e5e5e5'>
                    <th align='right'>No.</th>
HTML;

            // header
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue['label'];
                    $bg_color = $cValue['bg-color'];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= <<<HTML
                    <th class='text-center text-uppercase' 
                        style='color:#555;padding:3px;background-color:{$bg_color};'>
                        {$label}
                    </th>
HTML;
            }

            $data_total .= <<<HTML
                </tr>
            </thead>
            <tbody>
HTML;

            // isi table
            $total = [];
            $iCtr = 0;

            foreach ($items as $cData) {
                $iCtr++;
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";

                $data_total .= <<<HTML
                <tr style="{$bgColor}">
                    <td align='right'>{$iCtr}.</td>
HTML;

                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = "";

                    if (isset($customLinkAdd) && count($customLinkAdd) > 0 && isset($customLinkAdd[$pid]["customLink"][$headerKey])) {
                        $adlink = $customLinkAdd[$pid]["customLink"][$headerKey];
                        $link = $cData['link'] . "&w=$adlink";
                    }

                    $linkMain = isset($cData['link_main'][$headerKey]) ? $cData['link_main'][$headerKey] : null;

                    $data_total .= <<<HTML
                    <td title="{$headerKey}">
HTML;

                    if ($linkMain) {
                        $data_total .= <<<HTML
                        <span class='pull-right'>
                            <a href="{$linkMain}" data-toggle='tooltip' title="mutasi {$cValue}" target='_blank'>
                                <span class='text-muted fa fa-clock-o'></span>
                            </a>
                        </span>
HTML;
                    }

                    if ($headerKey == "extern_nama") {
                        if (isset($pairedResult_add[$cData['pId']]['link_history']) && ($pairedResult_add[$cData['pId']]['link_history'] != NULL)) {
                            $historyClick = $pairedResult_add[$cData['pId']]['link_history'];
                            $data_total .= <<<HTML
                            <a href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view data histories of this entry' 
                                data-onclick="{$historyClick}">
                                <span class='pull-right text-muted fa fa-clock-o'></span>
                            </a>
HTML;
                        }
                        if (isset($pairedResult_add[$cData['pId']]['keterangan'])) {
                            $keterangan = "\n" . $pairedResult_add[$cData['pId']]['keterangan'];
                            $ket = nl2br(htmlspecialchars($keterangan));
                            $data_total .= <<<HTML
<span style='font-size: 12px;color:red;font-style: italic;'>{$ket}</span>
HTML;
                        }
                    }

                    if ($headerKey == "jml_serial") {
                        $qty_debet_nya = $cData['qty_debet'];
                        $sisa_serial = $cValue >= $qty_debet_nya ? $cValue % $qty_debet_nya : 0;

                        if ($sisa_serial > 0) {
                            $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                            $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                        }
                        else {
                            $sisa_serial_f = "";
                            $sisa_title = "$cValue";
                        }

                        $jml_serial_ok = $cValue >= $qty_debet_nya ? $cValue - $sisa_serial : $cValue;

                        if ($qty_debet_nya > 0) {
                            $btn_serial_number = "<button type='button' class='btn btn-success' data-toggle='tooltip' title='$sisa_title' style='padding: 3px 5px;width: 47px;'>$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\">$jml_serial_ok $sisa_serial_f</button>";
                        }
                        else {
                            $btn_serial_number = "<button type='button' class='btn btn-link' data-toggle='tooltip' title='$sisa_title' style='padding: 3px 5px;width: 47px;'>-</button>";
                        }

                        $data_total1 = $data_total2 = $data_total3 = "";
                        if (isset($pairedSerial_add[$cData['pId']]['link_serial']) && ($pairedSerial_add[$cData['pId']]['link_serial'] != NULL)) {
                            $historyClick_serial = $pairedSerial_add[$cData['pId']]['link_serial'];
                            $data_total1 = "<span class='fa fa-list' data-onclick=\"{$historyClick_serial}\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_barcode'])) {
                            $historyClick_barcode = $pairedSerial_add[$cData['pId']]['link_barcode'];
                            $data_total2 = "<span class='fa fa-barcode' data-onclick=\"{$historyClick_barcode}\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "<span class='fa fa-qrcode' data-onclick=\"{$historyClick_qr}\"></span>";
                        }

                        if ($cData["tipe_produk"] == "serial") {
                            $data_total .= <<<HTML
                        <div class='btn-group pull-right'>
                            {$btn_serial_number}
HTML;
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey])) {
                                $data_total .= <<<HTML
                            <button type='button' class='btn btn-success' title='lihat detail serial'>{$data_total1}</button>
                            <button type='button' class='btn btn-warning' title='cetak serial barcode'>{$data_total2}</button>
                            <button type='button' class='btn btn-danger' title='cetak serial qr'>{$data_total3}</button>
HTML;
                            }
                            $data_total .= <<<HTML
                        </div>
HTML;
                        }
                        else {
                            $data_total .= <<<HTML
                        -
HTML;
                        }
                    }
                    else {
                        $aa_var = "<button data-onclick=\"window.open('{$link}', '_blank')\" type='button' data-toggle='tooltip' class='btn btn-xs btn-warning' title='saldo qty {$cValue}'>" . formatField($headerKey, $cValue) . "</button>";

                        if (isset($pairedSerial_add[$pid][$headerKey])) {
                            $qty_debet_nya = $cData['qty_debet'];
                            $sisa_serial = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] % $qty_debet_nya : 0;

                            if ($sisa_serial > 0) {
                                $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                                $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                            }
                            else {
                                $sisa_serial_f = "";
                                $sisa_title = "$cValue";
                            }

                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"];
                            $jml_serial_transit_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial_transit"];

                            $historyClick_serial_transit = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr_transit'];
                            $btn_serial_number2 = "<button type='button' pid='{$pid}' headerKey='{$headerKey}' class='btn btn-xs btn-danger' data-toggle='tooltip' title='jumlah serial intransit' style='padding: 3px 5px;width: 47px;' data-onclick=\"{$historyClick_serial_transit}\">{$jml_serial_transit_ok}</button>";
                            $btn_serial_number = "<button type='button' pid='{$pid}' headerKey='{$headerKey}' class='btn btn-xs btn-success' data-toggle='tooltip' title='jumlah serial' style='padding: 3px 5px;width: 47px;'>{$jml_serial_ok}</button>";

                            $data_total1 = $data_total2 = $data_total3 = "";
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_serial']) && ($pairedSerial_add[$cData['pId']][$headerKey]['link_serial'] != NULL)) {
                                $historyClick_serial = $pairedSerial_add[$cData['pId']][$headerKey]['link_serial'];
                                $data_total1 = "<span class='fa fa-list' data-onclick=\"{$historyClick_serial}\"></span>";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'])) {
                                $historyClick_barcode = $pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'];
                                $data_total2 = "<span class='fa fa-barcode' data-onclick=\"{$historyClick_barcode}\"></span>";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_qr'])) {
                                $historyClick_qr = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr'];
                                $data_total3 = "<span class='fa fa-qrcode' data-onclick=\"{$historyClick_qr}\"></span>";
                            }

                            if ($cData["tipe_produk"] == "serial") {
                                $data_total .= <<<HTML
                            <div class="btn-group pull-left">
                                {$btn_serial_number2}
                                {$btn_serial_number}
HTML;
                                if (isset($pairedSerial_add[$cData['pId']][$headerKey]) && $cValue > 0) {
                                    $data_total .= <<<HTML
                                <button type='button' class='btn btn-xs btn-success' title='lihat detail serial'>{$data_total1}</button>
                                <button type='button' class='btn btn-xs btn-warning' title='cetak serial barcode'>{$data_total2}</button>
                                <button type='button' class='btn btn-xs btn-danger' title='cetak serial qr'>{$data_total3}</button>
HTML;
                                }
                                $data_total .= <<<HTML
                                {$aa_var}
                            </div>
HTML;
                            }
                            else {
                                $data_total .= <<<HTML
                            {$cValue}
HTML;
                            }
                        }
                        else {
                            $data_total .= <<<HTML
                        {$cValue}
HTML;
                        }
                    }

                    if (($headerKey == "ng_qty_debet") && ($cData["ng_qty_debet"] > 0)) {
                        $historyClick_barcode = $pairedGudang_add[$cData['pId']]['link_history'];
                        $data_total .= <<<HTML
                        <button type='button' class='btn btn-primary btn-xs' title='lihat detail stok per-gudang'>
                            <span class='fa fa-home' data-onclick="{$historyClick_barcode}"></span>
                        </button>
HTML;
                    }

                    $data_total .= <<<HTML
                    </td>
HTML;

                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        $total[$headerKey] = (isset($total[$headerKey]) ? $total[$headerKey] : 0) + $cValue;
                    }
                }

                $data_total .= <<<HTML
                </tr>
HTML;
            }

            // footer
            $data_total .= <<<HTML
            </tbody>
            <tfoot>
                <tr bgcolor='#e5e5e5'>
                    <td>&nbsp;</td>
HTML;

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $val = $total[$cName];
                    $totalVal = $val < 0 ? "(" . number_format($val * -1) . ")" : number_format($val);
                    $data_total .= <<<HTML
                    <td class='text-bold text-right' style='color:#555;padding:3px;' title="{$cName}">
                        {$totalVal}
                    </td>
HTML;
                }
                else {
                    $data_total .= <<<HTML
                    <td class='text-center text-uppercase' style='color:#555;padding:3px;'>&nbsp;</td>
HTML;
                }
            }

            $data_total .= <<<HTML
                </tr>
            </tfoot>
        </table>
    </div>
HTML;

            $list_data .= $data_total;
        }
        else {
            // jika kosong
            $data_total .= <<<HTML
    <div class='table-responsive myNewTable'>
        <table id='myNewTable' class='table dataTable compact nowrap display'>
            <thead>
                <tr bgcolor='#e5e5e5'>
                    <th align='right'>No.</th>
HTML;

            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue['label'];
                    $bg_color = $cValue['bg-color'];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= <<<HTML
                    <th class='text-center text-uppercase' 
                        style='color:#555;padding:3px;background-color:{$bg_color};'>
                        {$label}
                    </th>
HTML;
            }

            $data_total .= <<<HTML
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr bgcolor='#e5e5e5' id='current-page-footer'></tr>
                <tr bgcolor='#e5e500' id='all-data-footer'></tr>
            </tfoot>
        </table>
    </div>
HTML;

            $list_data .= $data_total;
        }
        // endregion

        // region: TEMPLATE RENDER
        $params = ["fifo" => "MdlFifoAverage", "cabang_id" => my_cabang_id()];
        $headerFields_js = array_merge(["no" => "no"], $headerFields);
        $paramEs = blobEncode($params);
        $linkExcell = base_url() . "ExcelWriter/persediaan/{$paramEs}";

        $p->addTags([
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "link_excel" => $linkExcell,
            "server" => $server,
            "server_json" => $server_json,
            "url_serverside" => $url_serverside,
            "headerFields" => json_encode($headerFields_js),
        ]);

        $p->setContent($contens);
        $p->render();
        break;
    case "saldoDummy":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");


        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";

        $arrBgColor = array();
        if (isset($items_blok) && sizeof($items_blok) > 0) {
            foreach ($items_blok as $ctr => $spec) {
                $bagi = $ctr % 2;

                if ($bagi == 0) {
                    $background_color = "background-color:#F8F8FF;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
                else {
                    $background_color = "background-color:#FFE4E1;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
            }
        }

        $memberships = $_SESSION['login']['membership'];

        //region Description searching by php...
        $list_data .= "<div class='panel'>";
        $list_data .= "<div class='input-group'>";

        $link_excel = base_url() . "ExcelWriter/persediaan/$param_to_excel";
        // $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";
        $list_data .= "<span class='input-group-btn'>";
        // if (in_array("c_holding", $memberships)) {

        /*---allow to download-------------*/
        $allowBtns = array(
            "c_gudang",
            "c_gudang_spv",
            "c_holding"
        );
        // arrPrint($memberships);
        // arrPrint($allowBtns);

        if (isset($param_to_excel)) {

            $btnExcels = array();
            foreach ($memberships as $membership) {
                $btnExcel = array();
                if (in_array($membership, $allowBtns)) {
                    $btnExcels[] = $membership;
                }
            }
            // if (in_array("c_gudang_spv", $memberships)) {
            // cekKuning(sizeof($btnExcels));
            if (isset($btnExcels) && sizeof($btnExcels) > 0) {
                // $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' onclick=\"location.href='$link_excel'\"><i class='fa fa-file-excel-o'>&nbsp;</i>excel</button>";

                // $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' onclick=\"btn_result('$link_excel');\"><i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk</button>";
                $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download seluruh data ke excel' data-placement='right' onclick=\"btn_alert_result('Excell','Download data akan muncul setelah beberapa saat diklik','$link_excel');\"><i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk</button>";
            }
            else {
                $list_data .= "<button type='button' disabled class='btn btn-default' data-toggle='tooltip' title='download ke excel' data-placement='right'
                    onclick=\"location.href='#'\"><i class='fa fa-file-excel-o'>&nbsp;</i>Download Data Produk</button>";
            }
        }

        if (isset($dateSelected) && ($dateSelected == true)) {
            $list_data .= "<span class='input-group-add-on' >select month </span>";
            $list_data .= "<input type='date' class='form-control' value='$defaultDate' min='$oldDate' max='" . date("Y-m-d") . "' onchange=\"location.href='$thisPage&date='+this.value;\">";

        }

        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        $list_data .= "</span>";
        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
        $list_data .= "<span class='input-group-btn'>";
        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        $list_data .= "</span class='input-group-addon'>";
        $list_data .= "</div class='input-group'>";
        $list_data .= "</div class='panel panel-default'>";
        //endregion

        // arrPrintHijau($items);
        $data_total = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 1==========
            //========================

            //========================
            //========AREA HEADER LEVEL 2==========
            //            $data_total .= "<tr bgcolor='#e5e5e5'>";
            //            $data_total .= "<th align='right'></th>";
            //            foreach ($headerFields as $cName => $cValue) {
            //                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'></th>";
            //            }
            //            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 2==========
            //========================

            $data_total .= "</thead>";

            $total = array();
            $iCtr = 0;
            //arrPrint($items);
            //            arrPrint($headerFields);
            //            arrPrint($pairedSerial_add);
            $data_total .= "<tbody>";
            foreach ($items as $cData) {
                $iCtr++;
                //                arrPrintWebs($cData);
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";

                //                arrPrint($pairedSerial_add);

                $data_total .= "<tr style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;

                    if (isset($customLinkAdd) && count($customLinkAdd) > 0) {
                        if (isset($customLinkAdd[$pid]["customLink"][$headerKey])) {
                            $adlink = $customLinkAdd[$pid]["customLink"][$headerKey];
                            $link = $cData['link'] . "&w=$adlink";
                            //                            matiHere();
                        }
                        else {
                            //                            cekMerah($headerKey);
                            $link = $cData['link'];
                        }

                    }
                    else {
                        $link = $cData['link'];
                    }
                    $linkMain = isset($cData['link_main'][$headerKey]) ? $cData['link_main'][$headerKey] : NULL;

                    $data_total .= "<td title='$headerKey'>";
                    // $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";

                    if ($linkMain != NULL) {
                        $data_total .= "<span class='pull-right'><a href='$linkMain' data-toggle='tooltip' title='mutasi $cValue' target='_blank'><span class='text-muted fa fa-clock-o'></span></a></span>";
                    }

                    if ($headerKey == "extern_nama") {
                        if (isset($pairedResult_add[$cData['pId']]['link_history']) && ($pairedResult_add[$cData['pId']]['link_history'] != NULL)) {
                            $historyClick = $pairedResult_add[$cData['pId']]['link_history'];
                            $data_total .= "<a href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view data histories of this entry'
                                onclick=\"$historyClick\">
                                <span class='pull-right text-muted fa fa-clock-o'></span>
                                </a>";
                        }
                        if (isset($pairedResult_add[$cData['pId']]['keterangan'])) {
                            $keterangan = "\n" . $pairedResult_add[$cData['pId']]['keterangan'];
                            $data_total .= nl2br($keterangan);
                        }
                    }
                    if ($headerKey == "jml_serial") {
                        /* ------------------------------
                         * serial viewer
                         * --------------------------------*/
                        $qty_debet_nya = $cData['qty_debet'];
                        // cekHere("$cValue % $qty_debet_nya");
                        $sisa_serial = $cValue >= $qty_debet_nya ? $cValue % $qty_debet_nya : 0;
                        if ($sisa_serial > 0) {
                            $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                            // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                            $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                        }
                        else {
                            $sisa_serial_f = "";
                            $sisa_title = "$cValue";
                        }
                        $jml_serial_ok = $cValue >= $qty_debet_nya ? $cValue - $sisa_serial : $cValue;

                        if ($qty_debet_nya > 0) {
                            $btn_serial_number = "<button type='button' class='btn btn-success *******' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;'
    onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        else {
                            $btn_serial_number = "<button type='button' class='btn btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                        }
                        // -----------------------------------------
                        if (isset($pairedSerial_add[$cData['pId']]['link_serial']) && ($pairedSerial_add[$cData['pId']]['link_serial'] != NULL)) {

                            $historyClick_serial = $pairedSerial_add[$cData['pId']]['link_serial'];
                            $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_barcode'])) {
                            $historyClick_barcode = $pairedSerial_add[$cData['pId']]['link_barcode'];
                            $data_total2 = "<span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "<span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>";
                        }
                        /* ----------------------------------
                         * penampil button
                         * -----------------------------*/
                        if ($cData["tipe_produk"] == "serial") {
                            $data_total .= "<div class=\"btn-group pull-right\" >";
                            $data_total .= $btn_serial_number;
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey])) {
                                $data_total .= "
                              <button type='button' class='btn btn-success' title='lihat detail serial'>$data_total1</button>
                              <button type='button' class='btn btn-warning' title='cetak serial barcode'>$data_total2</button>
                              <button type='button' class='btn btn-danger' title='cetak serial qr'>$data_total3</button>";
                            }
                            $data_total .= "</div>";
                        }
                        else {
                            $data_total .= "-";
                        }
                    }
                    else {
                        // qty produk gudang ---------------------------------------------------------
                        $aa_var = "<button onclick=\"window.open('$link', '_blank')\" type='button' data-toggle='tooltip' class='btn btn-xs btn-warning' title='saldo qty $cValue'>" . formatField($headerKey, $cValue) . "</button>";
                        if (isset($pairedSerial_add[$pid][$headerKey])) {
                            //                            $data_total .=$pairedSerial_add[$pid][$headerKey]["jml_serial"]."~~";
                            $qty_debet_nya = $cData['qty_debet'];
                            // cekHere("$cValue % $qty_debet_nya");
                            $sisa_serial = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] % $qty_debet_nya : 0;
                            if ($sisa_serial > 0) {
                                $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                                // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                                $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                            }
                            else {
                                $sisa_serial_f = "";
                                $sisa_title = "$cValue";
                            }

                            //                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] - $sisa_serial : $pairedSerial_add[$pid][$headerKey]["jml_serial"];

                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"];
                            $jml_serial_transit_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial_transit"];

                            //                            if ($qty_debet_nya > 0) { //<<=========== INI PENYEBAB NYA
                            $historyClick_serial_transit = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr_transit'];
                            $btn_serial_number2 = "<button type='button' pid=$pid headerKey=$headerKey
                                class='btn btn-xs btn-danger' data-toggle='tooltip' title='jumlah serial intransit' style='ppadding: 3px 5px;width: 47px;'
                                onclick=\"$historyClick_serial_transit\" >$jml_serial_transit_ok</button>";
                            $btn_serial_number = "<button type='button' pid=$pid headerKey=$headerKey class='btn btn-xs btn-success' data-toggle='tooltip' title='jumlah serial' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok</button>";

                            //                            }

                            //                            elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            //                                $link_remove = $linkRemoveSerial . "/$pid";
                            //                                $sisa_title .= "serial number bisa diremove";
                            //                                $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-xs btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                            //                            }
                            //                            else {
                            //                                $btn_serial_number = "<button type='button' class='btn btn-xs btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                            //                            }
                            // -----------------------------------------
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_serial']) && ($pairedSerial_add[$cData['pId']][$headerKey]['link_serial'] != NULL)) {

                                $historyClick_serial = $pairedSerial_add[$cData['pId']][$headerKey]['link_serial'];
                                $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'])) {
                                $historyClick_barcode = $pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'];
                                $data_total2 = "
                                <span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_qr'])) {
                                $historyClick_qr = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr'];
                                $data_total3 = "
                                <span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>
                                ";
                            }
                            /* ----------------------------------
                             * penampil button
                             * -----------------------------*/
                            //                            cekHere($cData["tipe_produk"]);
                            if ($cData["tipe_produk"] == "serial") {
                                $data_total .= "<div class=\"btn-group pull-left\" >";
                                $data_total .= $btn_serial_number2;
                                $data_total .= $btn_serial_number;
                                if (isset($pairedSerial_add[$cData['pId']][$headerKey]) && $cValue > 0) {
                                    $data_total .= "
                                                    <button type='button' class='btn btn-xs btn-success' title='lihat detail serial'>$data_total1 </button>
                                                    <button type='button' class='btn btn-xs btn-warning' title='cetak serial barcode'>  $data_total2</button>
                                                    <button type='button' class='btn btn-xs btn-danger' title='cetak serial qr'>  $data_total3</button>";
                                }

                                $data_total .= "$aa_var";
                                $data_total .= "</div>";
                            }
                            else {
                                // disini tipe produk bukan serial, ditampilkan apa adanya... 02 maret 2024
                                //                                $data_total .= "-";
                                //                                $data_total .= "$aa_var";
                                $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                            }
                        }
                        else {
                            //                            cekHere("tidak ada paired serial");
                            //                            $data_total .="$aa_var";
                            $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                        }
                        //                        $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                    }

                    if (($headerKey == "ng_qty_debet") && ($cData["ng_qty_debet"] > 0)) {
                        $historyClick_barcode = $pairedGudang_add[$cData['pId']]['link_history'];
                        $data_total .= "
                            <button type='button' class='btn btn-primary btn-xs' title='lihat detail stok per-gudang'>
                            <span class='fa fa-home' onclick=\"$historyClick_barcode\"></span>
                            </button>
                        ";
                    }

                    $data_total .= "</td>";


                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";


            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;' title='$cName'>" . $totalVal . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table dataTable compact nowrap display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $data_total .= "<tbody>";
            $data_total .= "</tbody>";

            $data_total .= "<tfoot masuk_kosong>";
            $data_total .= "<tr bgcolor='#e5e5e5' id='current-page-footer'>";
            $data_total .= "</tr>";
            $data_total .= "<tr bgcolor='#e5e500' id='all-data-footer'>";
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

            //            $list_data .= "<div class='panel panel-default'>";
            //            $list_data .= "<div class='panel-body'>";
            //            $list_data .= "there is no item name matched your criteria<br>";
            //            $list_data .= "you mant want to go back or select other keyword<br>";
            //            $list_data .= "</div>";
            //            $list_data .= "</div>";
        }

        $params = array(
            "fifo" => "MdlFifoAverage",
            "cabang_id" => my_cabang_id(),
        );
        $headerFields_json = array_merge(array("no" => "no"), $headerFields);
        $paramEs = blobEncode($params);
        $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";
        // cekHere("$linkExcell");
        //        echo json_encode($list_data);
        //        echo json_encode($server);
        //        echo json_encode($server_json);
        //         matiHere(__LINE__);

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "link_excel" => $linkExcell,
            "server" => $server,
            "server_json" => $server_json,
            "url_serverside" => $url_serverside,
            "headerFields" => json_encode($headerFields_json),
            // "link_excel"       => $link_excel, // ikut yg tombol atas


        ));

        $p->setContent($contens);
        $p->render();
        break;

    case "mutasi":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        if (sizeof($items) > 0) {

            $i = 0;

            $data_total = "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' width='100%' class='table table-bordered'>";
            $data_total .= "<thead>";
            $data_total .= "<tr>";

            foreach ($headerFields as $nm => $dta) {
                $data_total .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>$dta</th>";
            }

            $data_total .= "</tr>";

            $data_total .= "</thead>";
            $data_total .= "<tbody>";

            $total = array();
            foreach ($items as $itemData) {
                $jenis_master = isset($itemData['jenis_master']) ? $itemData['jenis_master'] : "";
                $modul_path = isset($itemData['modul_path']) ? $itemData['modul_path'] : "";

                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                }

                if (round($itemData['debet'], 2) > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (round($itemData['kredit'], 2) > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }


                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                    if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                        if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                            $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                            $data_total .= "<td><a href='$link' target='_blank'>";
                            $data_total .= formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path);
                            $data_total .= "</a></td>";
                        }
                        else {
                            $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                        }
                    }
                    else {
                        $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                    }

                    if (is_numeric($cValue) && $headerKey != 'jenis') {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                        //                        $data_total .= "<td class='text-right' >".number_format(formatField($cName,$cValue))."</td>";
                    }
                    else {
                        //                        $data_total .= "<td>".formatField($cName,$cValue)."</td>";
                        //                        $data_total .= "<td>".formatField($cName,$cValue)."</td>";
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "</tbody>";
            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "mutasiDetails":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        if (sizeof($items) > 0) {

            $warnaKoloms = array(
                "in" => array(
                    "header" => "#a4ffa7",
                ),
                "out" => array(
                    "header" => "#f3adad",
                ),
            );

            $i = 0;
            $tool .= "<input type='text' style='width: 200px;' class='form-control pull-left' placeholder='masukan text untuk highlight' name='keyword' >";

            $data_total = "";
            $data_total .= "<style type='text/css'>
                table.dataTable thead th, table.dataTable thead td, 
                 table.dataTable tbody th, table.dataTable tbody td {
                    white-space: unset !important;
                }
                
            </style>";
            // $data_total .= "<div class='row'>";
            // $data_total .= "<div class='container-fluid'>";
            // $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-left' placeholder='masukan text untuk highlight' name='keyword' >";
            // $data_total .= "</div>";
            // $data_total .= "</div>";

            $data_total .= "<div class='clearfix'>&nbsp;</div>";

            $data_total .= "<div class='row'>";
            $data_total .= "<div class='container-fluid'>";
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display table-bordered'>";
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (array_key_exists($nm, $headerFields2)) {
                    $colspanX = sizeof($headerFields2[$nm]);
                    $rowspanX = "";
                }
                else {
                    $colspanX = "";
                    $rowspanX = "2";
                }

                $warnaHeader = isset($warnaKoloms[$nm]["header"]) ? $warnaKoloms[$nm]["header"] : "#e5e5e5";

                $data_total .= "<th class='text-center text-uppercase' title='$nm' style='background:$warnaHeader;color:#555555;padding:3px;' colspan='$colspanX' rowspan='$rowspanX'>$dta</th>";
            }
            $data_total .= "</tr>";
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            $warnaHeader = isset($warnaKoloms[$yParent]["header"]) ? $warnaKoloms[$yParent]["header"] : "#e5e5e5";

                            $data_total .= "<th class='text-center text-uppercase' title='$jn' style='background:$warnaHeader;color:#555555;padding:3px;' colspan=''>$detailsLabelsName</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }


            $data_total .= "</thead>";

            $data_total .= "<tbody>";

            $total = array();
            $itemsCek = array();
            //arrPrintPink($items);
            foreach ($items as $x => $itemData) {
                //                arrPrintWebs($itemData);
                $jenis_master = isset($itemData['jenis_master']) ? $itemData['jenis_master'] : "";
                $modul_path = isset($itemData['modul_path']) ? $itemData['modul_path'] : "";

                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    if (isset($itemData['transaksi_id'])) {

                        $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                    }
                    else {
                        $hightlight = "";
                    }
                }

                $addDetils = isset($items2[$x]) ? $items2[$x] : array();

                if (isset($itemsCek[$x]['in']) && $itemsCek[$x]['in'] > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (isset($itemsCek[$x]['out']) && $itemsCek[$x]['out'] > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }

                if (isset($itemData['jenis_master_bg_color']) && $itemData['jenis_master_bg_color'] != NULL) {
                    $warna = $itemData['jenis_master_bg_color'];
                    $bgcolor = "background-color:$warna;color:#ffffff;$hightlight";
                }

                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    if (array_key_exists($headerKey, $headerFields2)) {
                        $detilsValue = isset($addDetils[$headerKey]) ? $addDetils[$headerKey] : array();
                        if (isset($headerFields2[$headerKey])) {
                            foreach ($headerFields2[$headerKey] as $jn => $unused) {
                                $cValue = isset($detilsValue[$jn]) ? $detilsValue[$jn] : "";
                                $data_total .= "<td>" . formatField_he_format($unused, $cValue, $jenis_master, $modul_path) . "</td>";
                                if (is_numeric($cValue) && $headerKey != 'jenis') {
                                    if (isset($summary) && in_array($headerKey, $summary)) {
                                        if (!isset($total[$headerKey][$jn])) {
                                            $total[$headerKey][$jn] = 0;
                                        }
                                        $total[$headerKey][$jn] += $cValue;
                                    }
                                }
                            }
                        }
                    }
                    else {
                        $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                        if (is_array($cValue)) {
                            //                            cekHere($headerKey);
                            //                            arrPrintWebs($cValue);
                            $data_total .= "<td>";
                            if (sizeof($cValue) > 1) {
                                foreach ($cValue as $cSpec) {
                                    if (isset($cSpec["nomer"])) {
                                        if ($cSpec["nomer"] != $itemData["transaksi_no"]) {
                                            $data_total .= formatField_he_format("nomer", $cSpec["nomer"], $jenis_master, $modul_path) . "<br>";
                                        }
                                    }
                                    else {
                                        //                                        cekHere("$headerKey :: $cSpec");
                                        $ctr_account = "- $cSpec<br>";
                                        $data_total .= $ctr_account;
                                    }
                                }
                            }
                            else {
                                if (isset($cValue[1]["nomer"])) {
                                    $data_total .= formatField_he_format("nomer", $cValue[1]["nomer"], $jenis_master, $modul_path);
                                }
                                else {
                                    $ctr_account = "- $cValue[0]<br>";
                                    $data_total .= $ctr_account;
                                }
                            }
                            $data_total .= "</td>";
                        }
                        else {
                            if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                                if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                                    $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                                    $data_total .= "<td><a href='$link' target='_blank'>";
                                    $data_total .= formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path);
                                    $data_total .= "</a></td>";
                                }
                                else {
                                    $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                                }
                            }
                            else {
                                $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                            }
                        }
                    }
                }
                $data_total .= "</tr>";
            }

            $data_total .= "</tbody>";

            $data_total .= "<tfoot>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($headerFields2[$nm])) {
                    foreach ($headerFields2[$nm] as $jn => $unused) {

                        if (isset($total[$nm][$jn])) {
                            $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("angka", $total[$nm][$jn]) . "</td>";
                        }
                        else {
                            $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                        }
                    }
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                }

            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";


            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }

        // $link_excel
        if (isset($link_excel) && strlen($link_excel) > 5) {
            $excel_name = "$file_excel-" . dtimeNow('Ymd-His');
            $strItems = blobEncode($items);
            $strItems2 = blobEncode($items2);
            // arrPrint($strItems);
            // arrPrint(blobDecode($strItems));
            $excel_data = "data=$strItems&item2=$strItems2";

            $btn_tambahan = "<button type='button' class='btn btn-warning' onclick=\"download_excel()\"><i class='fa fa-download'></i> excel</button>";
            $btn_tambahan .= downloadXlsx($link_excel, $excel_data, $excel_name);
            //             $btn_tambahan .= "
            //                 <script>
            //
            //                     var download_excel = function(){
            //
            //                         var xhr = new XMLHttpRequest();
            //                         xhr.open('POST', '$link_excel', true);
            //                        xhr.responseType = 'blob';
            // //                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
            //                         xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            //                         xhr.onload = function(e) {
            //                             if (this.status == 200) {
            //                                 var blob = new Blob([this.response], {type: 'application/vnd.ms-excel'});
            //                                 var downloadUrl = URL.createObjectURL(blob);
            //                                 var a = document.createElement(\"a\");
            //                                 a.href = downloadUrl;
            //                                 a.download = \"$excel_name.xlsx\";
            //                                 document.body.appendChild(a);
            //                                 a.click();
            //                             } else {
            //                                 alert('Unable to download excel.')
            //                             }
            //                         };
            //                         xhr.send('data=$strItems&item2=$strItems2');
            //
            //                     }
            //                 </script>
            //
            //             ";


        }

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "disabled" => isset($disabled) ? $disabled : "",
            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
            "tool" => $tool,
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "mutasiDetails_v1":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        foreach ($propertyFields as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sumRow'])) {
                $fieldSumRow[$field] = $field;
            }

        }

        $list_data = "";
        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "<div class='table-responsive myNewTable'>";
            $data_total .= "<table class='table table-bordered no-margin no-padding table-hover'>";

            $data_total .= "<tr class='bg-info text-uppercase'>";
            $jml_headerFields = count($headerFields);
            foreach ($headerFields as $nm => $dtas) {
                // foreach ($fieldToshows as $nm => $dtas) {
                // if (array_key_exists($nm, $headerFields2)) {
                //     $colspanX = sizeof($headerFields2[$nm]);
                //     $rowspanX = "";
                // }
                // else {
                //     $colspanX = "";
                //     $rowspanX = "2";
                //     //                    $rowspanX = sizeof($headerFields2[$nm]);
                // }
                //                cekhitam($colspanX);
                //                arrPrint($dtas);
                $dta = $dtas['label'];
                $attr = $dtas['attr'];

                $data_total .= "<th $attr>$dta</th>";

            }
            $data_total .= "</tr>";
            // arrPrint($headerFields2);
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr class='bg-info text-uppercase'>";
                for ($i = 1; $i <= 4; $i++) {
                    foreach ($headerFields2 as $yParent => $yDetails) {


                        $subHeader = $yDetails['label'];
                        $subAttr = isset($yDetails['attr']) ? $yDetails['attr'] : "";
                        $data_total .= "<th $subAttr>$subHeader</th>";

                    }
                }
                $data_total .= "</tr>";
            }

            // ============================================
            // arrPrint($headerFields);
            // arrPrint($items);
            $sumQty_debet = 0;
            foreach ($items as $item) {

                $data_total .= "<tr>";

                foreach ($item as $hKey => $hvalue) {
                    if (isset($notToShow) && (!in_array($hKey, $notToShow))) {

                        $mainData = $hvalue;
                        $attr2 = "";
                        if (isset($headerFields[$hKey]['format'])) {

                            $mainData = $headerFields[$hKey]['format']($hKey, $hvalue, $item['jenis_master'], $item['modul_path']);

                        }

                        //region summary footer
                        if (in_array($hKey, $summaryKey)) {
                            if (!isset($sum[$hKey])) {
                                $sum[$hKey] = 0;
                            }
                            $sum[$hKey] += $mainData;
                        }
                        //endregion

                        //                        $mainData_f = isset($fieldFormat[$hKey]) ? $fieldFormat[$hKey]($hKey, $mainData) : $mainData;
                        $mainData_f = formatField_he_format($hKey, $hvalue, $item['jenis_master'], $item['modul_path']);
                        $attr = isset($fieldAttr[$hKey]) ? $fieldAttr[$hKey] : $attr2;

                        $data_total .= "<th $attr>$mainData_f</th>";
                    }

                }
                $data_total .= "</tr>";
            }


            $footer_0s = array(
                "totalan" => array(
                    "label" => "total",
                    "attr" => "class='text-uppercase bg-info' colspan='5'",
                ),

            );
            foreach ($fieldSumRow as $fKey => $fValue) {


                $footer_1s[$fKey]['label'] = isset($sum[$fKey]) ? $sum[$fKey] : "-";
                $footer_1s[$fKey]['attr'] = "class='text-right bg-info'";

            }
            $footers = $footer_0s + $footer_1s;


            $data_total .= "<tr>";
            $data_total .= "<td class='text-uppercase bg-info text-center text-renggang-10' colspan='$total_colspan'>Total</td>";
            foreach ($footer_1s as $fkey => $fDatas) {

                $fAttr = $fDatas['attr'];
                $fValue = $fDatas['label'];

                // $fValue_f = array_key_exists($fKey,$fieldFormat) ? $fieldFormat[$fKey]($fKey, $fValue) : $fValue;
                $fValue_f = isset($fieldFormat[$fKey]) ? $fieldFormat[$fKey]($fKey, $fValue) : $fValue;

                $data_total .= "<td $fAttr>$fValue_f</td>";
            }
            $data_total .= "<td class='text-uppercase bg-info' colspan='3'>-</td>";
            $data_total .= "</tr>";

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }
        //        cekHitam(callBackNav());
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "btn_tambahan" => "",
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "movement":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/movement.html");

        $unformatKey = array(
            "harga_debet_awal",
            "harga_debet",
            "harga_avail",
            "harga_kredit",
            "harga_akhir",
        );
        $list_data = "";
        if (sizeof($mainHeaders) > 0) {

            $i = 0;
            $data_total = "<div class='panel table-responsive'>";
            $data_total .= "<table class='table table-bordered datatables table-hover'>";

            //region table heading
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($mainHeaders as $header => $hAttr) {
                ;
                $data_total .= "<th $hAttr>$header</th>";
            }
            $data_total .= "</tr>";

            // arrPrint($subHeaders);
            if (sizeof($subHeaders) > 0) {
                $data_total .= "<tr>";
                for ($i = 1; $i <= $rowLoop; $i++) {

                    foreach ($subHeaders as $subHeader => $sAttr) {
                        $data_total .= "<th $sAttr>$subHeader</th>";
                    }
                }
                $data_total .= "</tr>";
            }
            $data_total .= "</thead>";
            //endregion
            // arrPrint($bodies);
            //region table body
            if (sizeof($bodies) > 0) {
                $data_total .= "<tbody>";
                foreach ($bodies as $row => $bDatas) {
                    // arrPrint($bDatas);
                    $data_total .= "<tr>";
                    foreach ($bDatas as $vKey => $rDatas) {

                        // arrPrint($rDatas);
                        // matiHere();
                        // if(!isset($sumQty[$mutasiKolom])){
                        //     $sumQty[$mutasiKolom] =0;
                        // }
                        // $sumQty[$mutasiKolom] += $val2;

                        // cekMerah($vKey);
                        if (in_array($vKey, $unformatKey)) {
                            $rDatas_value = $rDatas['value'];
                        }
                        else {
                            $rDatas_value = formatField($vKey, $rDatas['value']);
                        }
                        // $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . formatField($vKey, $rDatas['value']) . "</td>";
                        $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . $rDatas_value . "</td>";
                    }
                    $data_total .= "</tr>";
                }

                $data_total .= "</tbody>";
            }
            //endregion

            if (sizeof($footers) > 0) {
                $data_total .= "<thead>";
                $data_total .= "<tr>";
                // foreach ($footers as $footer => $fAttr) {
                //     $data_total .= "<th $fAttr>$footer</th>";
                // }

                // arrPrint($sumfooters);
                //region footer gaya manual berooooo
                $fAttr = "class='bg-info text-right text-uppercase'";
                $data_total .= "<th $fAttr colspan='3'>total</th>";
                // foreach ($sumfooters as $sumkey =>$sumvalue) {
                $data_total .= "<th $fAttr>" . formatField("qty_debet_awal", $sumfooters["qty_debet_awal"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("debet_awal", $sumfooters["debet_awal"]) . "</th>";

                $data_total .= "<th $fAttr>" . formatField("qty_debet", $sumfooters["qty_debet"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("debet", $sumfooters["debet"]) . "</th>";

                $data_total .= "<th $fAttr>" . formatField("qty_avail", $sumfooters["qty_avail"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("avail", $sumfooters["avail"]) . "</th>";

                $data_total .= "<th $fAttr>" . formatField("qty_kredit", $sumfooters["qty_kredit"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("kredit", $sumfooters["kredit"]) . "</th>";

                $data_total .= "<th $fAttr>" . formatField("qty_akhir", $sumfooters["qty_akhir"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("akhir", $sumfooters["akhir"]) . "</th>";

                $data_total .= "</tr>";
                //endregion
                $data_total .= "</thead>";
            }

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";
        }
        $list_data .= "<script>
                            $(document).ready( function(){

                                var calculatePlus = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)+parseFloat(r2))>0)?(parseFloat(r1)+parseFloat(r2)):0
                                    return calc
                                };

                                var calculateMin = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)-parseFloat(r2))>0)?(parseFloat(r1)-parseFloat(r2)):0
                                    return calc
                                };

                                var table = $('table.datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                {
                                                    extend: 'excel',
                                                    text: 'Excel',
                                                    exportOptions: {
                                                        modifier: {
                                                            page: 'current'
                                                        }
                                                    }
                                                }
                                            ],
                                    columnDefs: [
                                                    {
                                                        targets: 11,
                                                        data: 'realvalue',
                                                        render: function ( data, type, row, meta ) {
                                                            return calculatePlus( row[5].display, row[8].display )
                                                        }
                                                    }
                                               ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                    });
                                                    console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
                                                });
                                            }
                                        });
                                    });
        
                                    $('.table-responsive').floatingScroll();
                                    $('.table-responsive').scroll( delay_v2(function(){ $('table.datatables').DataTable().fixedHeader.adjust(); }, 200) );
                            </script>";

        //        cekHitam(callBackNav());
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $date1,
            "date2" => $date2,
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            // "date_max"         => $filters['dates']['end'],
            "url" => $thisPage,
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "movementGroupOLD":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/movement.html");

        $unformatKey = array(
            //            "harga_debet_awal",
            //            "harga_debet",
            //            "harga_avail",
            //            "harga_kredit",
            //            "harga_akhir",
        );
        $list_data = "";
        if (sizeof($mainHeaders) > 0) {

            $i = 0;
            $data_total = "<div class='panel table-responsive'>";
            $data_total .= "<table class='table table-bordered datatables table-hover'>";

            //region table heading
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($mainHeaders as $header => $hAttr) {
                ;
                $data_total .= "<th $hAttr>$header</th>";
            }
            $data_total .= "</tr>";

            // arrPrint($subHeaders);
            if (sizeof($subHeaders) > 0) {
                $data_total .= "<tr>";
                foreach ($subHeaders as $hKey => $sHeaders) {
                    foreach ($sHeaders as $subHeader => $sAttr) {
                        $data_total .= "<th $sAttr>$subHeader</th>";
                    }
                }

                $data_total .= "</tr>";
            }
            $data_total .= "</thead>";
            //endregion
            // arrPrint($bodies);
            // cekMerah();
            //region table body
            if (sizeof($bodies) > 0) {
                $data_total .= "<tbody>";
                foreach ($bodies as $row => $bDatas) {
                    // arrPrint($bDatas);
                    $data_total .= "<tr>";
                    foreach ($bDatas as $vKey => $rDatas) {

                        // arrPrint($rDatas);
                        // matiHere("$vKey");
                        // if(!isset($sumQty[$mutasiKolom])){
                        //     $sumQty[$mutasiKolom] =0;
                        // }
                        // $sumQty[$mutasiKolom] += $val2;

                        // cekMerah($vKey);
                        if (in_array($vKey, $unformatKey)) {
                            $rDatas_value = $rDatas['value'];
                        }
                        else {
                            //                            cekHere(":: $vKey ::");
                            $rDatas_value = formatField($vKey, $rDatas['value']);
                        }
                        // $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . formatField($vKey, $rDatas['value']) . "</td>";
                        $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . $rDatas_value . "</td>";

                        if (in_array($vKey, $sumfooters)) {
                            if (!isset($sumFooters[$vKey])) {
                                $sumFooters[$vKey] = 0;
                            }
                            $sumFooters[$vKey] += $rDatas['value'];
                        }
                    }
                    $data_total .= "</tr>";
                }

                $data_total .= "</tbody>";
            }
            //endregion
            // arrPrintHere($sumFooters);
            if (sizeof($footers) > 0) {
                $footer_colspan = isset($mdlFields) ? sizeof($mdlFields) + 1 : 0;
                $data_total .= "<thead>";
                $data_total .= "<tr>";
                // foreach ($footers as $footer => $fAttr) {
                //     $data_total .= "<th $fAttr>$footer</th>";
                // }

                // arrPrint($sumfooters);

                //region footer gaya manual berooooo
                $fAttr = "class='bg-info text-right text-uppercase'";
                $fAttr2 = "class='bg-success text-right text-uppercase'";
                $fAttr3 = "class='bg-grey-2 text-right text-uppercase'";
                $data_total .= "<th $fAttr colspan='$footer_colspan'>total</th>";
                // foreach ($sumfooters as $sumkey =>$sumvalue) {
                // foreach ($subHeaders as $hKey => $sHeaders) {
                //     foreach ($sHeaders as $subHeader => $sAttr) {
                //
                //         $data_total .= "<th $sAttr>$hKey</th>";
                //     }
                // }
                $data_total .= "<th $fAttr3>" . formatField("qty_debet_awal", $sumFooters["qty_debet_awal"]) . "</th>";
                $data_total .= "<th $fAttr3>-</th>";
                $data_total .= "<th $fAttr3>" . formatField("debet_awal", $sumFooters["debet_awal"]) . "</th>";

                $data_total .= "<th $fAttr2>" . formatField("qty_debet", $sumFooters["qty_debet_int"]) . "</th>";
                $data_total .= "<th $fAttr2>-</th>";
                $data_total .= "<th $fAttr2>" . formatField("debet", $sumFooters["debet_int"]) . "</th>";
                //
                $data_total .= "<th $fAttr>" . formatField("qty_avail", $sumFooters["qty_debet"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("avail", $sumFooters["debet"]) . "</th>";
                //
                $data_total .= "<th $fAttr2>" . formatField("qty_kredit", $sumFooters["qty_kredit_int"]) . "</th>";
                $data_total .= "<th $fAttr2>-</th>";
                $data_total .= "<th $fAttr2>" . formatField("kredit", $sumFooters["kredit_int"]) . "</th>";
                //
                $data_total .= "<th $fAttr>" . formatField("qty_akhir", $sumFooters["qty_kredit"]) . "</th>";
                $data_total .= "<th $fAttr>-</th>";
                $data_total .= "<th $fAttr>" . formatField("akhir", $sumFooters["kredit"]) . "</th>";
                //
                $data_total .= "<th $fAttr3>" . formatField("qty_akhir", $sumFooters["qty_akhir"]) . "</th>";
                $data_total .= "<th $fAttr3>-</th>";
                $data_total .= "<th $fAttr3>" . formatField("akhir", $sumFooters["akhir"]) . "</th>";

                $data_total .= "</tr>";
                //endregion

                $data_total .= "</thead>";
            }

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";
        }

        //region data table
        $list_data .= "<script>
                            $(document).ready( function(){

                                var calculatePlus = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)+parseFloat(r2))>0)?(parseFloat(r1)+parseFloat(r2)):0
                                    return calc
                                };

                                var calculateMin = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)-parseFloat(r2))>0)?(parseFloat(r1)-parseFloat(r2)):0
                                    return calc
                                };

                                var table = $('table.datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                {
                                                    extend: 'excel',
                                                    text: 'Excel',
                                                    exportOptions: {
                                                        modifier: {
                                                            page: 'current'
                                                        }
                                                    }
                                                }
                                            ],
//                                     columnDefs: [
//                                                     {
//                                                         targets: 110,
//                                                         data: 'realvalue',
//                                                         render: function ( data, type, row, meta ) {
//                                                             return calculatePlus( row[5].display, row[8].display )
//                                                         }
//                                                     }
// //                                                    ,
// //                                                    {
// //                                                        targets: 17,
// //                                                        data: 'realvalue',
// //                                                        render: function ( data, type, row, meta ) {
// //                                                            var colmCount = calculatePlus( row[5].display, row[8].display )
// //
// //                                                            console.log( row[5].display );
// //                                                            console.log( row[8].display );
// //                                                            console.log( parseFloat(row[5].display) + parseFloat(row[8].display) );
// //                                                            console.log( row[14].display );
// //
// ////                                                            return 123123123
// ////                                                            return calculateMin( parseFloat(colmCount) , row[14].display )
// //                                                        }
// //                                                    }
//                                                ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
        //                                                console.log( $('span', obj).html() );
        //                                                console.log( obj );
        //                                                console.error( $(obj).html() );
                                                    });
                                                    console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
                                                });
        // Total over all pages
        //                                        var total2=0;
        //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
        //                                            total2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total3=0;
        //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
        //                                            total3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total4=0;
        //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
        //                                            total4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total5=0;
        //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
        //                                            total5 += intVal( $('span', obj).html() );
        //                                        });
        
        
                                                // Total over this page
        //                                        pageTotal2 = api
        //                                            .column( 2, { page: 'current'} )
        //                                            .data()
        //                                            .reduce( function (a, b) {
        //                                                return intVal(a) + intVal(b);
        //                                            }, 0 );
        
        //                                        var pageTotal2=0;
        //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal3=0;
        //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal4=0;
        //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal5=0;
        //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal5 += intVal( $('span', obj).html() );
        //                                        });
        
                                                // Update footer
        //                                        $( api.column( 2 ).footer() ).html(
        //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 3 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 4 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 5 ).footer() ).html(
        //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
        //                                        );
                                            }
                                        });
                                    });
        
                                    $('.table-responsive').floatingScroll();
                            </script>";
        //endregion

        if (isset($btnGroups)) {
            $strBtn = "";
            foreach ($btnGroups as $btnKey => $btnSpecs) {

                $btnLabel = $btnSpecs['label'];
                $btnLink = $btnSpecs['link'];
                $btn_active = isset($_GET['mv']) && $btnKey == $_GET['mv'] ? "btn-warning" : "";
                $strBtn .= "<button type='button' class='btn btn-danger $btn_active' onclick=\"location.href='" . base_url() . "$btnLink'\">$btnLabel</button>";
            }
            $btn_groups = "<div class='btn-group'>";
            $btn_groups .= $strBtn;
            $btn_groups .= "</div>";

        }
        //        cekHitam(callBackNav());
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $date1,
            "date2" => $date2,
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "btn_group" => $btn_groups,
            // "date_max"         => $filters['dates']['end'],
            "url" => $thisPage,
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "movementGroup":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/movement.html");

        $unformatKey = array(
            //            "harga_debet_awal",
            //            "harga_debet",
            //            "harga_avail",
            //            "harga_kredit",
            //            "harga_akhir",
        );
        $list_data = "";
        if (sizeof($mainHeaders) > 0) {

            $i = 0;
            $data_total = "<div class='table-responsive'>";
            $data_total .= "<table class='table table-bordered datatables table-hover'>";

            //region table heading
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($mainHeaders as $header => $hAttr) {

                $data_total .= "<th $hAttr>$header</th>";
            }
            $data_total .= "</tr>";


            if (sizeof($subHeaders) > 0) {
                $data_total .= "<tr>";
                foreach ($subHeaders as $hKey => $sHeaders) {
                    foreach ($sHeaders as $subHeader => $sAttr) {
                        $data_total .= "<th $sAttr>$subHeader</th>";
                    }
                }

                $data_total .= "</tr>";
            }
            $data_total .= "</thead>";
            //endregion


            //region table body
            if (sizeof($bodies) > 0) {
                $data_total .= "<tbody>";
                foreach ($bodies as $row => $bDatas) {
                    // arrPrint($bDatas);
                    $data_total .= "<tr>";
                    foreach ($bDatas as $vKey => $rDatas) {

                        // cekMerah($vKey);
                        if (in_array($vKey, $unformatKey)) {
                            $rDatas_value = $rDatas['value'];
                        }
                        else {
                            //                            cekHere(":: $vKey ::");
                            $rDatas_value = formatField($vKey, $rDatas['value']);
                        }
                        // $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . formatField($vKey, $rDatas['value']) . "</td>";
                        $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "' realvalue='" . $rDatas['value'] . "'>" . $rDatas_value . "</td>";

                        if (in_array($vKey, $sumfooters)) {
                            if (!isset($sumFooters[$vKey])) {
                                $sumFooters[$vKey] = 0;
                            }
                            $sumFooters[$vKey] += isset($rDatas['value']) ? $rDatas['value'] : 0;
                        }
                    }
                    $data_total .= "</tr>";
                }

                $data_total .= "</tbody>";
            }
            //endregion

            if (sizeof($footers) > 0) {
                $footer_colspan = isset($mdlFields) ? sizeof($mdlFields) + 1 : 0;
                $data_total .= "<thead>";
                $data_total .= "<tr>";

                //region footer gaya manual berooooo
                $fAttr = "class='bg-info text-right text-uppercase'";
                $fAttr2 = "class='bg-success text-right text-uppercase'";
                $fAttr3 = "class='bg-grey-2 text-right text-uppercase'";
                $fAttr4 = "class='bg-danger text-right text-uppercase'";

                $data_total .= "<th $fAttr colspan='$footer_colspan'>total</th>";
                //                arrPrint($sumFooters);
                //                arrPrint($bodies[0]);
                if (sizeof($bodies) > 0) {
                    foreach ($bodies[0] as $key => $val) {
                        if (!in_array($key, $footersBlacklist)) {
                            $attr = $val['attr'];
                            $data_total .= "<th $attr>";
                            if (isset($sumFooters[$key])) {

                                $data_total .= formatField("$key", $sumFooters[$key]);
                            }
                            else {
                                $data_total .= "-";
                            }
                            $data_total .= "</th>";
                        }
                    }
                }


                //                $data_total .= "<th $fAttr3>" . formatField("qty_debet_awal", $sumFooters["qty_debet_awal"]) . "</th>";
                //                $data_total .= "<th $fAttr3>-</th>";
                //                $data_total .= "<th $fAttr3>" . formatField("debet_awal", $sumFooters["debet_awal"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr2>" . formatField("qty_debet", $sumFooters["qty_debet_int"]) . "</th>";
                //                $data_total .= "<th $fAttr2>-</th>";
                //                $data_total .= "<th $fAttr2>" . formatField("debet", $sumFooters["debet_int"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr>" . formatField("qty_avail", $sumFooters["qty_debet"]) . "</th>";
                //                $data_total .= "<th $fAttr>-</th>";
                //                $data_total .= "<th $fAttr>" . formatField("avail", $sumFooters["debet"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr4>" . formatField("qty_avail_bom", $sumFooters["qty_debet_bom"]) . "</th>";
                //                $data_total .= "<th $fAttr4>-</th>";
                //                $data_total .= "<th $fAttr4>" . formatField("avail_bom", $sumFooters["debet_bom"]) . "</th>";
                //
                //
                //                $data_total .= "<th $fAttr2>" . formatField("qty_kredit", $sumFooters["qty_kredit_int"]) . "</th>";
                //                $data_total .= "<th $fAttr2>-</th>";
                //                $data_total .= "<th $fAttr2>" . formatField("kredit", $sumFooters["kredit_int"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr>" . formatField("qty_akhir", $sumFooters["qty_kredit"]) . "</th>";
                //                $data_total .= "<th $fAttr>-</th>";
                //                $data_total .= "<th $fAttr>" . formatField("akhir", $sumFooters["kredit"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr4>" . formatField("qty_akhir_bom", $sumFooters["qty_kredit_bom"]) . "</th>";
                //                $data_total .= "<th $fAttr4>-</th>";
                //                $data_total .= "<th $fAttr4>" . formatField("akhir_bom", $sumFooters["kredit_bom"]) . "</th>";
                //
                //                $data_total .= "<th $fAttr3>" . formatField("qty_akhir", $sumFooters["qty_akhir"]) . "</th>";
                //                $data_total .= "<th $fAttr3>-</th>";
                //                $data_total .= "<th $fAttr3>" . formatField("akhir", $sumFooters["akhir"]) . "</th>";

                //endregion

                $data_total .= "</tr>";
                $data_total .= "</thead>";
            }

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";

        }

        //region data table
        $list_data .= "<script>
                            $(document).ready( function(){

                                var calculatePlus = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)+parseFloat(r2))>0)?(parseFloat(r1)+parseFloat(r2)):0
                                    return calc
                                };

                                var calculateMin = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)-parseFloat(r2))>0)?(parseFloat(r1)-parseFloat(r2)):0
                                    return calc
                                };

                                var table = $('table.datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                {
                                                    extend: 'excel',
                                                    text: 'Excel',
                                                    exportOptions: {
                                                        modifier: {
                                                            page: 'current'
                                                        }
                                                    }
                                                }
                                            ],
//                                     columnDefs: [
//                                                     {
//                                                         targets: 110,
//                                                         data: 'realvalue',
//                                                         render: function ( data, type, row, meta ) {
//                                                             return calculatePlus( row[5].display, row[8].display )
//                                                         }
//                                                     }
// //                                                    ,
// //                                                    {
// //                                                        targets: 17,
// //                                                        data: 'realvalue',
// //                                                        render: function ( data, type, row, meta ) {
// //                                                            var colmCount = calculatePlus( row[5].display, row[8].display )
// //
// //                                                            console.log( row[5].display );
// //                                                            console.log( row[8].display );
// //                                                            console.log( parseFloat(row[5].display) + parseFloat(row[8].display) );
// //                                                            console.log( row[14].display );
// //
// ////                                                            return 123123123
// ////                                                            return calculateMin( parseFloat(colmCount) , row[14].display )
// //                                                        }
// //                                                    }
//                                                ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
        //                                                console.log( $('span', obj).html() );
        //                                                console.log( obj );
        //                                                console.error( $(obj).html() );
                                                    });
                                                    console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
                                                });
        // Total over all pages
        //                                        var total2=0;
        //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
        //                                            total2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total3=0;
        //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
        //                                            total3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total4=0;
        //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
        //                                            total4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total5=0;
        //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
        //                                            total5 += intVal( $('span', obj).html() );
        //                                        });
        
        
                                                // Total over this page
        //                                        pageTotal2 = api
        //                                            .column( 2, { page: 'current'} )
        //                                            .data()
        //                                            .reduce( function (a, b) {
        //                                                return intVal(a) + intVal(b);
        //                                            }, 0 );
        
        //                                        var pageTotal2=0;
        //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal3=0;
        //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal4=0;
        //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal5=0;
        //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal5 += intVal( $('span', obj).html() );
        //                                        });
        
                                                // Update footer
        //                                        $( api.column( 2 ).footer() ).html(
        //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 3 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 4 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 5 ).footer() ).html(
        //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
        //                                        );
                                            }
                                        });
                                    });
        
                                    $('.table-responsive').floatingScroll();
                            </script>";
        //endregion
        $list_data .= isset($alerter) ? $alerter : "*";

        if (isset($btnGroups)) {
            $strBtn = "";
            foreach ($btnGroups as $btnKey => $btnSpecs) {

                $btnLabel = $btnSpecs['label'];
                $btnLink = $btnSpecs['link'];
                $btn_active = isset($_GET['mv']) && $btnKey == $_GET['mv'] ? "btn-warning" : "";
                $strBtn .= "<button type='button' class='btn btn-danger $btn_active' onclick=\"location.href='" . base_url() . "$btnLink'\">$btnLabel</button>";
            }
            $btn_groups = "<div class='btn-group'>";
            $btn_groups .= $strBtn;
            $btn_groups .= "</div>";

        }
        //        cekHitam(callBackNav());
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $date1,
            "date2" => $date2,
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "btn_group" => $btn_groups,
            // "date_max"         => $filters['dates']['end'],
            "url" => $thisPage,
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "persediaan":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/persediaan.html");

        // arrPrint($bodies);
        // arrPrint($items);
        // arrPrint($mainHeaders);
        // arrPrint($subHeaders);

        $list_data = "";
        if (sizeof($mainHeaders) > 0) {

            $i = 0;
            $data_total = "<div class='panel table-responsive'>";
            $data_total .= "<table class='table table-bordered datatables table-hover'>";

            //region table heading
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($mainHeaders as $header => $hAttr) {
                ;
                $data_total .= "<th $hAttr>$header</th>";
            }
            $data_total .= "</tr>";

            // arrPrint($subHeaders);
            if (sizeof($subHeaders) > 0) {
                $data_total .= "<tr>";
                for ($i = 1; $i <= $rowLoop; $i++) {

                    foreach ($subHeaders as $subHeader => $sAttr) {
                        $data_total .= "<th $sAttr>$subHeader</th>";
                    }
                }
                $data_total .= "</tr>";
            }
            $data_total .= "</thead>";
            //endregion

            //region table body
            if (sizeof($bodies) > 0) {
                $data_total .= "<tbody>";
                foreach ($bodies as $row => $bDatas) {
                    // arrPrint($bDatas);
                    $data_total .= "<tr>";
                    foreach ($bDatas as $vKey => $rDatas) {

                        $data_total .= "<td " . $rDatas['attr'] . " data-order='" . $rDatas['value'] . "'>" . formatField($vKey, $rDatas['value']) . "</td>";
                    }
                    $data_total .= "</tr>";
                }

                $data_total .= "</tbody>";
            }
            //endregion

            if (sizeof($footers) > 0) {
                $data_total .= "<thead>";
                $data_total .= "<tr>";
                foreach ($footers as $footer => $fAttr) {
                    $data_total .= "<th $fAttr>$footer</th>";
                }
                $data_total .= "</tr>";
                $data_total .= "</thead>";
            }

            $data_total .= "</table>";
            $data_total .= "</div>";

            // cekHitam($data_total);
            $list_data .= $data_total;
            // $list_data .= "hauahahah";

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";
        }
        $list_data .= "<script>
                            $(document).ready( function(){
        
                                var table = $('table.datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                {
                                                    extend: 'excelHtml5',
                                                    text: 'Save current page',
                                                    exportOptions: {
                                                        modifier: {
                                                            page: 'current'
                                                        }
                                                    }
                                                }
                                            ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
        
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
        
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                    });
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
        
        
                                                });
                                            }
                                        });
        
                                    });
        
                                    $('.table-responsive').floatingScroll();
                                    $('.table-responsive').scroll( delay_v2(function(){ $('table.datatables').DataTable().fixedHeader.adjust(); }, 200) );
                            </script>";

        //        cekHitam(callBackNav());
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            // "date1" => $date1,
            // "date2" => $date2,
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            // "date_max"         => $filters['dates']['end'],
            "url" => $thisPage,
            "navigasi" => "------",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "rlDetail":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        if (sizeof($items) > 0) {

            $i = 0;

            $data_total = "<div class='table-responsive'>";
            $data_total .= "<table width='100%' class='table table-bordered'>";
            $data_total .= "<tr>";

            foreach ($headerFields as $nm => $dta) {
                $data_total .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>$dta</th>";
            }

            $data_total .= "</tr>";


            $total = array();
            foreach ($items as $itemData) {
                $bgcolor = "";
                //                if (round($itemData['debet'], 2) > 0) {
                //                    $bgcolor = "background-color:#DFF0D8;";
                //                }
                //                elseif (round($itemData['kredit'], 2) > 0) {
                //                    $bgcolor = "background-color:#F2DEDE;";
                //                }
                //                else {
                //                    $bgcolor = "";
                //                }
                $jenis_master = isset($itemData['jenis_master']) ? $itemData['jenis_master'] : "";
                $modul_path = isset($itemData['modul_path']) ? $itemData['modul_path'] : "";


                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                    if ($headerKey == "transaksi_no") {
                        $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . $itemData['urut'] . "</td>";
                    }
                    else {
                        $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                    }

                    if (is_numeric($cValue) && ($headerKey != 'jenis') && (in_array($headerKey, $summary))) {

                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;

                    }
                    else {

                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($total[$nm])) {
                    $nilai = $total[$nm] >= 0 ? number_format($total[$nm]) : "(" . number_format($total[$nm] * -1) . ")";
                    $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . $nilai . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }
            $data_total .= "</tr>";

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            "date_min" => date("Y-01-01"),
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "btn_tambahan" => "",
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "efisiensi":

        $add_style = "font-size:20px;";
        $contens = "";
        //        $p = New Layout("$title", "$subTitle", "application/template/movement.html");
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $unformatKey = array(
            //            "harga_debet_awal",
            //            "harga_debet",
            //            "harga_avail",
            //            "harga_kredit",
            //            "harga_akhir",
        );
        $list_data = "";
        if (isset($mainHeaders) && sizeof($mainHeaders) > 0) {

            $i = 0;
            $data_total = "<div class='table-responsive'>";
            $data_total .= "<table class='table table-bordered datatables table-hover'>";

            //region table heading
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            $data_total .= "<th>No.</th>";
            foreach ($mainHeaders as $key => $mhSpec) {
                $attr = $mhSpec['attr'];
                $label = $mhSpec['label'];
                $data_total .= "<th $attr>$label</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";
            //endregion


            //region table body
            $no = 0;
            $data_total .= "<tbody>";

            foreach ($items as $ii => $iSpec) {

                $no++;
                $data_total .= "<tr>";
                $data_total .= "<td style='text-align: right;'>" . $no . "</td>";
                foreach ($mainHeaders as $key => $mhSpec) {

                    $attr = "";
                    if (isset($iSpec[$key])) {
                        if (is_numeric($iSpec[$key])) {
                            if ($iSpec[$key] >= 0) {
                                $value_tmp = formatField($key, $iSpec[$key]);
                                $attr = "style='text-align:right;'";
                            }
                            else {
                                $value_tmp = "(" . number_format($iSpec[$key] * -1, "0", ".", ",") . ")";
                                $attr = "style='text-align:right;'";
                            }

                            if (in_array($key, $sumfooters)) {
                                if (!isset($sumFooters[$key])) {
                                    $sumFooters[$key] = 0;
                                }
                                $sumFooters[$key] += isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            }
                        }
                        else {
                            $value_tmp = formatField($key, $iSpec[$key]);
                        }
                    }
                    else {
                        $value_tmp = formatField($key, 0);
                    }

                    if ($key == "nama") {
                        $value_tmp = isset($alias[$iSpec[$key]]) ? $alias[$iSpec[$key]] : $iSpec[$key];
                        $link = isset($iSpec['link']) ? "<span class='pull-right'>" . $iSpec['link'] . "</span>" : "";
                        //                        cekHere(":: $value_tmp :: " . $iSpec['link']);
                        $value_tmp .= $link;
                    }

                    $value = $value_tmp;

                    $data_total .= "<td $attr>" . $value . "</td>";
                }

                $data_total .= "</tr>";
            }

            $data_total .= "</tbody>";

            //endregion

            if (sizeof($footers) > 0) {
                $footer_colspan = isset($mdlFields) ? sizeof($mdlFields) + 1 : 0;
                $data_total .= "<thead>";
                $data_total .= "<tr>";

                //region footer gaya manual berooooo
                $data_total .= "<th colspan='2'>total</th>";
                if (sizeof($mainHeaders) > 0) {
                    foreach ($mainHeaders as $key => $mhSpec) {
                        if (!in_array($key, $footersBlacklist)) {
                            $attr = $mhSpec['attr'];
                            $attrx = "";
                            if (isset($sumFooters[$key])) {
                                if ($sumFooters[$key] >= 0) {
                                    $value_tmp = formatField($key, $sumFooters[$key]);
                                }
                                else {
                                    $value_tmp = "(" . number_format($sumFooters[$key] * -1, "0", ".", ",") . ")";
                                    $attrx = "style='text-align:right;'";
                                }
                            }
                            else {
                                $value_tmp = "-";
                            }

                            $data_total .= "<th $attr $attrx>";
                            $data_total .= $value_tmp;
                            $data_total .= "</th>";
                        }
                    }
                }

                //endregion

                $data_total .= "</tr>";
                $data_total .= "</thead>";
            }

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";
        }

        //region data table
        $list_data .= "<script>
                            $(document).ready( function(){

                                var calculatePlus = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)+parseFloat(r2))>0)?(parseFloat(r1)+parseFloat(r2)):0
                                    return calc
                                };

                                var calculateMin = function (a1,a2) {
                                        a1 = typeof $(a1).html() != 'undefined' ? $(a1).html() : a1!='' ? a1 : 0;
                                        a2 = typeof $(a2).html() != 'undefined' ? $(a2).html() : a2!='' ? a2 : 0;
                                    var r1 = 0;
                                        r1 = parseFloat (a1.replace(/,/g,'') );
                                        r1 = typeof r1 === 'string' ? 0 : parseFloat(r1);
                                    var r2 = 0;
                                        r2 = parseFloat( a2.replace(/,/g,'') );
                                        r2 = typeof r2 === 'string' ? 0 : parseFloat(r2);
                                    var calc = ((parseFloat(r1)-parseFloat(r2))>0)?(parseFloat(r1)-parseFloat(r2)):0
                                    return calc
                                };

                                var table = $('table.datatables').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                                { extend: 'print', footer: true },
                                                {
                                                    extend: 'excel',
                                                    text: 'Excel',
                                                    exportOptions: {
                                                        modifier: {
                                                            page: 'current'
                                                        }
                                                    }
                                                }
                                            ],
//                                     columnDefs: [
//                                                     {
//                                                         targets: 110,
//                                                         data: 'realvalue',
//                                                         render: function ( data, type, row, meta ) {
//                                                             return calculatePlus( row[5].display, row[8].display )
//                                                         }
//                                                     }
// //                                                    ,
// //                                                    {
// //                                                        targets: 17,
// //                                                        data: 'realvalue',
// //                                                        render: function ( data, type, row, meta ) {
// //                                                            var colmCount = calculatePlus( row[5].display, row[8].display )
// //
// //                                                            console.log( row[5].display );
// //                                                            console.log( row[8].display );
// //                                                            console.log( parseFloat(row[5].display) + parseFloat(row[8].display) );
// //                                                            console.log( row[14].display );
// //
// ////                                                            return 123123123
// ////                                                            return calculateMin( parseFloat(colmCount) , row[14].display )
// //                                                        }
// //                                                    }
//                                                ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                                var api = this.api(), data;
                                                // Remove the formatting to get integer data for summation
                                                var intVal = function ( i ) {
                                                    return typeof i === 'string' ?
                                                        i.replace(/[$,]/g, '')*1 :
                                                        typeof i === 'number' ?
                                                            i : 0;
                                                };
                                                var arrayFooter = $('tfoot>tr>th');
                                                var dpageTotal = [];
                                                jQuery.each(arrayFooter, function(i,d){
                                                    var id_n_index = parseFloat(i);
                                                    dpageTotal[id_n_index] = 0;
                                                    jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                        dpageTotal[id_n_index] += intVal( $(obj).html() );
        //                                                console.log( $('span', obj).html() );
        //                                                console.log( obj );
        //                                                console.error( $(obj).html() );
                                                    });
                                                    console.log('dpageTotal[id_n_index]: ' + ' ' + id_n_index + ' '  +  dpageTotal[id_n_index] );
                                                if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                    $( api.column(id_n_index).footer() ).html(
                                                        \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                    );
                                                }
                                                });
        // Total over all pages
        //                                        var total2=0;
        //                                        jQuery.each( $(api.column(2).data()), function(i, obj){
        //                                            total2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total3=0;
        //                                        jQuery.each( $(api.column(3).data()), function(i, obj){
        //                                            total3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total4=0;
        //                                        jQuery.each( $(api.column(4).data()), function(i, obj){
        //                                            total4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var total5=0;
        //                                        jQuery.each( $(api.column(5).data()), function(i, obj){
        //                                            total5 += intVal( $('span', obj).html() );
        //                                        });
        
        
                                                // Total over this page
        //                                        pageTotal2 = api
        //                                            .column( 2, { page: 'current'} )
        //                                            .data()
        //                                            .reduce( function (a, b) {
        //                                                return intVal(a) + intVal(b);
        //                                            }, 0 );
        
        //                                        var pageTotal2=0;
        //                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal2 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal3=0;
        //                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal3 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal4=0;
        //                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal4 += intVal( $('span', obj).html() );
        //                                        });
        //
        //                                        var pageTotal5=0;
        //                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
        //                                            pageTotal5 += intVal( $('span', obj).html() );
        //                                        });
        
                                                // Update footer
        //                                        $( api.column( 2 ).footer() ).html(
        //                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 3 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 4 ).footer() ).html(
        //                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
        //                                        );
        
        //                                        $( api.column( 5 ).footer() ).html(
        //                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
        //                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
        //                                        );
                                            }
                                        });
                                    });
        
                                    $('.table-responsive').floatingScroll();
                            </script>";
        //endregion

        $btn_groups = "";
        if (isset($btnGroups) && sizeof($btnGroups) > 0) {
            $strBtn = "";
            foreach ($btnGroups as $btnKey => $btnSpecs) {

                $btnLabel = $btnSpecs['label'];
                $btnLink = $btnSpecs['link'];
                $btn_active = isset($_GET['mv']) && $btnKey == $_GET['mv'] ? "btn-warning" : "";
                $strBtn .= "<button type='button' class='btn btn-danger $btn_active' onclick=\"location.href='" . base_url() . "$btnLink'\">$btnLabel</button>";
            }
            $btn_groups .= "<div class='btn-group'>";
            $btn_groups .= $strBtn;
            $btn_groups .= "</div>";

        }

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "btn_group" => $btn_groups,
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "btn_tambahan" => "",
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
    case "mutasiDetailLocker":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "";
            $data_total .= "<div class='row'>";
            $data_total .= "<div class='container-fluid'>";
            $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-right' placeholder='masukan text untuk highlight' name='keyword' >";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $data_total .= "<div class='clearfix'>&nbsp;</div>";

            $data_total .= "<div class='row'>";
            $data_total .= "<div class='container-fluid'>";
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display'>";
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (array_key_exists($nm, $headerFields2)) {
                    $colspanX = sizeof($headerFields2[$nm]);
                    $rowspanX = "";
                }
                else {
                    $colspanX = "";
                    $rowspanX = "2";
                }
                $data_total .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;' colspan='$colspanX' rowspan='$rowspanX'>$dta</th>";
            }
            $data_total .= "</tr>";
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            $data_total .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;' colspan=''>$detailsLabelsName</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }


            $data_total .= "</thead>";
            $data_total .= "<tbody>";

            $total = array();
            $itemsCek = array();

            foreach ($items as $x => $itemData) {
                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                }

                $addDetils = $items2[$x];

                if (isset($itemsCek[$x]['in']) && $itemsCek[$x]['in'] > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (isset($itemsCek[$x]['out']) && $itemsCek[$x]['out'] > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }

                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    if (array_key_exists($headerKey, $headerFields2)) {
                        $detilsValue = isset($addDetils[$headerKey]) ? $addDetils[$headerKey] : array();
                        if (isset($headerFields2[$headerKey])) {
                            foreach ($headerFields2[$headerKey] as $jn => $unused) {
                                $cValue = $detilsValue[$jn];


                                $data_total .= "<td>" . formatField($unused, $cValue) . "</td>";

                                if (is_numeric($cValue) && $headerKey != 'jenis') {
                                    if (isset($summary) && in_array($headerKey, $summary)) {
                                        if (!isset($total[$headerKey][$jn])) {
                                            $total[$headerKey][$jn] = 0;
                                        }
                                        $total[$headerKey][$jn] += $cValue;
                                    }
                                }
                            }
                        }
                    }
                    else {
                        $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                        if (is_array($cValue)) {
                            $data_total .= "<td>";
                            if (sizeof($cValue) > 1) {
                                foreach ($cValue as $cSpec) {
                                    if ($cSpec["nomer"] != $itemData["transaksi_no"]) {
                                        $data_total .= formatField("nomer", $cSpec["nomer"]) . "<br>";
                                    }
                                }
                            }
                            else {
                                $data_total .= formatField("nomer", $cValue[1]["nomer"]);
                            }
                            $data_total .= "</td>";
                        }
                        else {
                            if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                                if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                                    $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                                    $data_total .= "<td><a href='$link' target='_blank'>";
                                    $data_total .= formatField($headerKey, $cValue);
                                    $data_total .= "</a></td>";
                                }
                                else {
                                    $data_total .= "<td>" . formatField($headerKey, $cValue) . "</td>";
                                }
                            }
                            else {
                                $data_total .= "<td>" . formatField($headerKey, $cValue) . "</td>";
                            }
                        }
                    }
                }
                $data_total .= "</tr>";
            }

            $data_total .= "</tbody>";

            $data_total .= "<tfoot>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($headerFields2[$nm])) {
                    foreach ($headerFields2[$nm] as $jn => $unused) {

                        if (isset($total[$nm][$jn])) {
                            $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("angka", $total[$nm][$jn]) . "</td>";
                        }
                        else {
                            $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                        }
                    }
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                }

            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";


            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "disabled" => isset($disabled) ? $disabled : "",
            "tool" => "",
        ));


        $p->setContent($contens);
        $p->render();

        break;

    case "viewMoveDetails":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        // $list_data .= "<style>
        //     .popover {
        //         position: absolute;
        //     }
        //     </style>";
        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "<div class='panel'>";
            // $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-right' placeholder='masukan text untuk highlight' name='keyword' >";
            // $data_total .= "<div class='clearfix col-sm-12 col-md-12 col-lg-12 no-padding no-margin'>&nbsp;</div>";
            $data_total .= "<table width='100%' id='myNewTable' class='table table-hover stripe table-bordered no-margin no-padding pageResize'>";

            /* ---------------------------------------------------------------------------
             * header
             * ---------------------------------------------------------------------------*/
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (array_key_exists($nm, $headerFields2)) {
                    if (array_key_exists($nm, $headerFields3)) {
                        $colspanX = count($headerFields2[$nm]) * count($headerFields3[$nm]);
                        $rowspanX = "";
                    }
                    else {
                        $colspanX = sizeof($headerFields2[$nm]);
                        $rowspanX = "";
                    }
                }
                else {
                    if (array_key_exists($nm, $headerFields4)) {
                        $colspanX = count($headerFields4[$nm]);
                        $rowspanX = "2";
                    }
                    else {
                        $colspanX = "";
                        $rowspanX = count($headerFields3) > 0 ? "3" : "2";
                    }
                }
                if ($nm == "in") {
                    $bgcolor = "background-color:#DFF0D8;";
                }
                elseif ($nm == "out") {
                    $bgcolor = "background-color:#F2DEDE;";
                }
                else {
                    $bgcolor = "background:#e5e5e5;color:#555555;";
                }
                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanX' rowspan='$rowspanX'>$dta</th>";
            }
            $data_total .= "</tr>";
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $colspanY = count($headerFields3[$yParent]);
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanY'>$detailsLabelsName</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }
            if (count($headerFields3) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            foreach ($headerFields3[$yParent] as $h3key => $h3Label) {
                                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$h3Label</th>";
                            }
                        }
                    }
                    if (array_key_exists($yParent, $headerFields4)) {
                        foreach ($headerFields4[$yParent] as $yy => $yyLabel) {
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$yyLabel</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }

            $data_total .= "</thead>";

            /* ---------------------------------------------------------------------------
             * body
             * ---------------------------------------------------------------------------*/
            $headerFormatException = array(
                "742_in" => "diskon"
            );
            $data_total .= "<tbody>";
            $total = array();
            // $itemsCek = array();

            foreach ($items as $x => $itemData) {
                //                arrPrintCyan($itemData);
                $transaksi_id = $itemData['transaksi_id'];
                $suppliers_id = $itemData['suppliers_id'];
                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                }
                $addDetils = $items2[$x];
                if (isset($itemsCek[$x]['in']) && $itemsCek[$x]['in'] > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (isset($itemsCek[$x]['out']) && $itemsCek[$x]['out'] > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }

                $bg_dibatalakan = "";
                if ($itemData["trash_4"] == 1) {
                    switch ($itemData["cancel_jenis"]) {
                        case "4644":
                            $bg_dibatalakan = "background-color:#dfabf7;";

                            break;
                        default:
                            $bg_dibatalakan = "background-color:#ff9c43;";
                            break;
                    }
                }

                if (isset($itemData['jenis_master_bg_color']) && $itemData['jenis_master_bg_color'] != NULL) {
                    $warna = $itemData['jenis_master_bg_color'];
                    $bgcolor = "background-color:$warna;color:#ffffff;$hightlight";
                }

                $data_total .= "<tr style='$bgcolor $bg_dibatalakan'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    if (array_key_exists($headerKey, $headerFields2)) {
                        $detilsValue = isset($addDetils[$headerKey]) ? $addDetils[$headerKey] : array();
                        if (isset($headerFields2[$headerKey])) {
                            foreach ($headerFields2[$headerKey] as $jn => $unused) {
                                if (count($headerFields3) > 0 && isset($headerFields3[$headerKey])) {
                                    foreach ($headerFields3[$headerKey] as $keyh3 => $keyh3LAbel) {
                                        $cValue = isset($detilsValue[$jn][$keyh3]) ? $detilsValue[$jn][$keyh3] : 0;
                                        $jn_ky = $jn . "_" . $keyh3;
                                        $formatKey = isset($headerFormatException[$jn_ky]) ? $headerFormatException[$jn_ky] : "debet";

                                        $muValue = formatField_he_format($formatKey, $cValue, $itemData["jenis_master"], $itemData["modul_path"]);

                                        // $data_total .= "<td title='5 $jn $keyh3'>" . formatField_he_format("debet", $cValue) . "</td>";

                                        $_info_headerKey = $headerKey;
                                        $_info_transaksi_id = $transaksi_id;
                                        $_info_cValue = $cValue;
                                        $_info_jenis_master = $jn;
                                        $_info_modul_path = $itemData["modul_path"];
                                        $_info_link_mutasi_details = isset($_link_mutasi_details[$itemData['jenis_master']]) ? $_link_mutasi_details[$itemData['jenis_master']] : [];

                                        $muValue = isset($_link_mutasi_details[$jn]) && $cValue * 1 > 0 ? "<a title='" . $_link_mutasi_details[$jn]['title'] . "' class='pull-right' onclick=\"window.open('" . $_link_mutasi_details[$jn]['link'] . "/$suppliers_id', '_blank')\" href='javascript:void(0);'>" . number_format($cValue) . "</a>" : $muValue;

                                        $data_total .= "<td titlex='5 $jn $keyh3' data-jenis_master='$_info_jenis_master' data-cValue='$cValue' data-modul_path='$_info_modul_path' data-headerKey='$headerKey'>$muValue</td>";

                                        if (is_numeric($cValue) && $headerKey != 'jenis') {
                                            if (isset($summary) && in_array($keyh3, $summary)) {
                                                if (!isset($total[$headerKey][$jn][$keyh3])) {
                                                    $total[$headerKey][$jn][$keyh3] = 0;
                                                }
                                                $total[$headerKey][$jn][$keyh3] += $cValue;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else {
                        if (array_key_exists($headerKey, $headerFields4)) {
                            foreach ($headerFields4[$headerKey] as $h4Src => $h4Label) {
                                $cValue = $itemData[$h4Src];
                                $data_total .= "<td title='4'>" . formatField_he_format($h4Src, $cValue, $itemData["jenis_master"], $itemData["modul_path"]) . "</td>";
                                if (is_numeric($cValue) && $headerKey != 'jenis') {
                                    if (isset($summary) && in_array($h4Src, $summary)) {
                                        if (!isset($total[$headerKey][$h4Src])) {
                                            $total[$headerKey][$h4Src] = 0;
                                        }
                                        $total[$headerKey][$h4Src] += $cValue;
                                    }
                                }
                            }
                        }
                        else {

                            $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                            if (is_array($cValue)) {
                                $data_total .= "<td title='0'>";
                                if (sizeof($cValue) > 1) {
                                    // cekHitam("ada");
                                    foreach ($cValue as $cSpec) {
                                        if ($cSpec["nomer"] != $itemData["transaksi_no"]) {
                                            // cekMErah("t ". $cSpec["nomer"]);
                                            $data_total .= formatField_he_format("nomer", $cSpec["nomer"], $itemData['jenis_master'], $itemData["modul_path"]) . "<br>";
                                        }
                                    }
                                }
                                else {
                                    $data_total .= formatField_he_format("nomer", $cValue[1]["nomer"], $itemData['jenis_master'], $itemData["modul_path"]);
                                }
                                $data_total .= "</td>";
                            }
                            else {
                                if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                                    if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                                        $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                                        $data_total .= "<td title='1'><a href='$link' target='_blank'>";
                                        $data_total .= formatField_he_format($headerKey, $cValue, $itemData["jenis_master"], $itemData["modul_path"]);
                                        $data_total .= "</a></td>";
                                    }
                                    else {
                                        $data_total .= "<td title='2'>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
                                    }
                                }
                                else {
                                    $_info_headerKey = $headerKey;
                                    $_info_transaksi_id = $transaksi_id;
                                    $_info_cValue = $cValue;
                                    $_info_jenis_master = $itemData['jenis_master'];
                                    $_info_modul_path = $itemData["modul_path"];
                                    $_info_link_mutasi_details = isset($_link_mutasi_details[$itemData['jenis_master']]) ? $_link_mutasi_details[$itemData['jenis_master']] : [];


                                    if(in_array($headerKey, $noFormat)){
                                        $data_total .= "<td data-jenis_master='$_info_jenis_master' data-modul_path='$_info_modul_path' data-headerKey='$headerKey' 
data-toggle='popover' data-content='$transaksi_id'>";
                                        $data_total .= $cValue;
                                    }
                                    else{
                                        $data_total .= "<td data-jenis_master='$_info_jenis_master' data-cValue='$cValue' data-modul_path='$_info_modul_path' data-headerKey='$headerKey' 
data-toggle='popover' data-content='$transaksi_id'>";
                                        $data_total .= formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]);
                                    }
                                    $data_total .= "</td>";
                                }
                            }
                        }

                    }
                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            /* ---------------------------------------------------------------------------
             * footer
             * ---------------------------------------------------------------------------*/
            $data_total .= "<tfoot>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($headerFields2[$nm])) {
                    // cekBiru($nm);
                    foreach ($headerFields2[$nm] as $jn => $unused) {
                        foreach ($headerFields3[$nm] as $h => $j) {
                            if (isset($total[$nm][$jn][$h])) {

                                $fValue = $total[$nm][$jn][$h];
                                $jn_ky = $jn . "_" . $h;
                                $formatKey = isset($headerFormatException[$jn_ky]) ? $headerFormatException[$jn_ky] : "debet";
                                $sumValue = formatField_he_format($formatKey, $fValue);

                                // $data_total .= "<td title='7 $jn $h' class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$jn][$h]) . "</td>";
                                $data_total .= "<td title='7 $jn $h' class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>$sumValue</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }
                        }
                    }
                }
                else {
                    if (isset($headerFields4[$nm])) {

                        foreach ($headerFields4[$nm] as $h => $yy) {
                            $value = isset($total[$nm][$h]) ? formatField("angka", $total[$nm][$h]) : "-";
                            if (isset($total[$nm][$h])) {
                                $data_total .= "<td title='6' class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$h]) . "</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }

                        }
                    }
                    else {
                        $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                    }
                }

            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";


            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "<script>
                $(function () {
                  $('[data-toggle=\"popover\"]').popover({
                    container: 'body',
                    placement: 'top',
                    // trigger: 'hover', // atau 'focus' tergantung kebutuhan
                    html: true
                  });
                });
                </script>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }

        $arrLegend = array(
            "MASUK" => "#DFF0D8",
            "KELUAR" => "#F2DEDE",
            "DIBATALKAN/DIKEMBALIKAN" => "#ff9c43",
            "GANTI RELASI PO" => "#dfabf7",
        );
        $tool = "";
        $tool .= "<span style='font-size: 24px;'>";
        $tool .= "<b>Legenda</b>: &nbsp;&nbsp;";
        foreach ($arrLegend as $lb => $warna) {
            $tool .= "&nbsp;&nbsp;<i class='fa fa-square ' style='color: $warna;font-size: 24px;transform: scaleX(1.80); display: inline-block;'></i>&nbsp;  $lb  &nbsp;&nbsp;";
        }
        $tool .= "</span>";
        $tool .= "<input type='text' style='width: 190px;' class='form-control pull-right' placeholder='masukan text untuk highlight' name='keyword' >";
        // $tool .= "<i class='fa fa-circle'></i> keluar";
        // $tool .= "<i class='fa fa-circle'></i> dibatalkan";

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "date1" => $filters['date1'],
            "date2" => $filters['date2'],
            //            "date_min" => date("Y-01-01"),
            "date_min" => "2019-01-01",
            "date_max" => $filters['dates']['end'],
            "url" => $thisPage,
            "disabled" => isset($disabled) ? $disabled : "",
            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
            "geturl" => isset($geturl) ? $geturl : "",
            "tool" => $tool,
        ));


        $p->setContent($contens);
        $p->render();

        break;

    case "loadMoveDetails":

        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");
        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        $list_data .= "<style>
            table.table-bordered > tbody > tr > td{
                white-space:unset !important;
            }
            .table>thead>tr>th{
                white-space: unset !important;
            }
            saldo{
                 overflow: scroll;
            }
</style>";


        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "<div class='panel table-responsive'>";
            $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-right' placeholder='masukan text untuk highlight' name='keyword' >";
            $data_total .= "<div class='clearfix col-sm-12 col-md-12 col-lg-12'>&nbsp;</div>";
            $data_total .= "<table width='100%' id='myNewTable' class='table table-hover stripe table-bordered no-margin no-padding pageResize'>";
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (array_key_exists($nm, $headerFields2)) {
                    if (array_key_exists($nm, $headerFields3)) {
                        $colspanX = count($headerFields2[$nm]) * count($headerFields3[$nm]);
                        $rowspanX = "";
                    }
                    else {
                        $colspanX = sizeof($headerFields2[$nm]);
                        $rowspanX = "";
                    }
                }
                else {
                    if (array_key_exists($nm, $headerFields4)) {
                        $colspanX = count($headerFields4[$nm]);
                        $rowspanX = "2";
                    }
                    else {
                        $colspanX = "";
                        $rowspanX = count($headerFields3) > 0 ? "3" : "2";
                    }
                }
                if ($nm == "in") {
                    $bgcolor = "background-color:#DFF0D8;";
                }
                elseif ($nm == "out") {
                    $bgcolor = "background-color:#F2DEDE;";
                }
                else {
                    $bgcolor = "background:#e5e5e5;color:#555555;";
                }
                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanX' rowspan='$rowspanX'>$dta</th>";
            }
            $data_total .= "</tr>";
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $colspanY = count($headerFields3[$yParent]);
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanY'>$detailsLabelsName</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }
            if (count($headerFields3) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            foreach ($headerFields3[$yParent] as $h3key => $h3Label) {
                                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$h3Label</th>";
                            }
                        }
                    }
                    if (array_key_exists($yParent, $headerFields4)) {
                        foreach ($headerFields4[$yParent] as $yy => $yyLabel) {
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$yyLabel</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }


            $data_total .= "</thead>";
            $data_total .= "<tbody>";

            $total = array();
            // $itemsCek = array();

            foreach ($items as $x => $itemData) {
                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                }
                $addDetils = $items2[$x];
                if (isset($itemsCek[$x]['in']) && $itemsCek[$x]['in'] > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (isset($itemsCek[$x]['out']) && $itemsCek[$x]['out'] > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }

                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    if (array_key_exists($headerKey, $headerFields2)) {
                        $detilsValue = isset($addDetils[$headerKey]) ? $addDetils[$headerKey] : array();
                        if (isset($headerFields2[$headerKey])) {
                            foreach ($headerFields2[$headerKey] as $jn => $unused) {
                                if (count($headerFields3) > 0 && isset($headerFields3[$headerKey])) {
                                    foreach ($headerFields3[$headerKey] as $keyh3 => $keyh3LAbel) {
                                        $cValue = isset($detilsValue[$jn][$keyh3]) ? $detilsValue[$jn][$keyh3] : 0;
                                        $data_total .= "<td>" . formatField_he_format("debet", $cValue) . "</td>";
                                        if (is_numeric($cValue) && $headerKey != 'jenis') {
                                            if (isset($summary) && in_array($keyh3, $summary)) {
                                                if (!isset($total[$headerKey][$jn][$keyh3])) {
                                                    $total[$headerKey][$jn][$keyh3] = 0;
                                                }
                                                $total[$headerKey][$jn][$keyh3] += $cValue;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else {
                        if (array_key_exists($headerKey, $headerFields4)) {
                            foreach ($headerFields4[$headerKey] as $h4Src => $h4Label) {
                                $cValue = $itemData[$h4Src];
                                $data_total .= "<td>" . formatField_he_format($h4Src, $cValue) . "</td>";
                                if (is_numeric($cValue) && $headerKey != 'jenis') {
                                    if (isset($summary) && in_array($h4Src, $summary)) {
                                        if (!isset($total[$headerKey][$h4Src])) {
                                            $total[$headerKey][$h4Src] = 0;
                                        }
                                        $total[$headerKey][$h4Src] += $cValue;
                                    }
                                }
                            }
                        }
                        else {
                            $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                            if (is_array($cValue)) {
                                $data_total .= "<td>";
                                if (sizeof($cValue) > 1) {
                                    // cekHitam("ada");
                                    foreach ($cValue as $cSpec) {

                                        if ($cSpec["nomer"] != $itemData["transaksi_no"]) {
                                            // cekMErah("t ". $cSpec["nomer"]);
                                            $data_total .= formatField_he_format("nomer", $cSpec["nomer"], $itemData['jenis_master'], $itemData["modul_path"]) . "<br>";
                                        }
                                    }
                                }
                                else {
                                    $data_total .= formatField_he_format("nomer", $cValue[1]["nomer"], $itemData['jenis_master'], $itemData["modul_path"]);
                                }
                                $data_total .= "</td>";
                            }
                            else {
                                if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                                    if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                                        $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                                        $data_total .= "<td><a href='$link' target='_blank'>";
                                        $data_total .= formatField_he_format($headerKey, $cValue);
                                        $data_total .= "</a></td>";
                                    }
                                    else {
                                        $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
                                    }
                                }
                                else {
                                    $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
                                }
                            }
                        }

                    }
                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            $data_total .= "<tfoot>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($headerFields2[$nm])) {
                    // cekBiru($nm);
                    foreach ($headerFields2[$nm] as $jn => $unused) {
                        foreach ($headerFields3[$nm] as $h => $j) {
                            if (isset($total[$nm][$jn][$h])) {
                                $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$jn][$h]) . "</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }
                        }
                    }
                }
                else {
                    if (isset($headerFields4[$nm])) {

                        foreach ($headerFields4[$nm] as $h => $yy) {
                            $value = isset($total[$nm][$h]) ? formatField("angka", $total[$nm][$h]) : "-";
                            if (isset($total[$nm][$h])) {
                                $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$h]) . "</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }

                        }
                    }
                    else {
                        $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                    }
                }

            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";


            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= "<div class='clearfix col-sm-12 col-md-12 col-lg-12'><h3>$title $subTitle</h3></div>";;
            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }


        $list_data .= "<script>
//$('.modal-dialog').removeClass('.modal-lg').addClass('.modal-xl');
$('.modal-dialog').addClass('modal-xl');

</script>";
        echo $list_data;
        //        $p->addTags(array(
        //            "menu_left" => callMenuLeft(),
        //            //                "trans_menu" => callTransMenu(),
        //            "float_menu_atas" => callFloatMenu('atas'),
        //            "float_menu_bawah" => callFloatMenu(),
        //            "menu_taskbar" => callMenuTaskbar(),
        //            "btn_back" => callBackNav(),
        //            "content" => $list_data,
        //            "profile_name" => $this->session->login['nama'],
        //            "date1" => $filters['date1'],
        //            "date2" => $filters['date2'],
        //            //            "date_min" => date("Y-01-01"),
        //            "date_min" => "2019-01-01",
        //            "date_max" => $filters['dates']['end'],
        //            "url" => $thisPage,
        //            "disabled" => isset($disabled) ? $disabled : "",
        //            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
        //            "geturl" => isset($geturl) ? $geturl : "",
        //        ));
        //        $p->setContent($contens);
        //        $p->render();

        break;

    case "viewMoveDetailsKas":
        // cekLime("888");
        //         arrPrint($items);
        //         arrPrintWebs($items2);
        //         arrPrintWebs($headerFields2);
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/mutasi.html");
        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        $list_data .= "<style>
            table.table-bordered > tbody > tr > td{
                white-space:unset !important;
            }
            saldo{
                 overflow: scroll;
            }
</style>";


        if (sizeof($items) > 0) {

            $i = 0;
            $tbl_id = "myNewTableKas";
            $data_total = "<div class='panel'>";
            // $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-right' placeholder='masukan text untuk highlight' name='keyword' >";
            $data_total .= "<div class='clearfix col-sm-12 col-md-12 col-lg-12'>&nbsp;</div>";
            $data_total .= "<table width='100%' id='$tbl_id' class='table table-hover stripe table-bordered no-margin no-padding pageResize'>";
            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (array_key_exists($nm, $headerFields2)) {
                    if (array_key_exists($nm, $headerFields3)) {
                        $colspanX = count($headerFields2[$nm]) * count($headerFields3[$nm]);
                        $rowspanX = "";
                    }
                    else {
                        $colspanX = sizeof($headerFields2[$nm]);
                        $rowspanX = "";
                    }
                }
                else {
                    if (array_key_exists($nm, $headerFields4)) {
                        $colspanX = count($headerFields4[$nm]);
                        $rowspanX = "2";
                    }
                    else {
                        $colspanX = "";
                        $rowspanX = count($headerFields3) > 0 ? "3" : "2";
                    }
                }
                if ($nm == "in") {
                    $bgcolor = "background-color:#DFF0D8;";
                }
                elseif ($nm == "out") {
                    $bgcolor = "background-color:#F2DEDE;";
                }
                else {
                    $bgcolor = "background:#e5e5e5;color:#555555;";
                }
                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanX' rowspan='$rowspanX'>$dta</th>";
            }
            $data_total .= "</tr>";
            if (sizeof($headerFields2) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $colspanY = count($headerFields3[$yParent]);
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan='$colspanY'>$detailsLabelsName</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }
            if (count($headerFields3) > 0) {
                $data_total .= "<tr>";
                foreach ($headerFields as $yParent => $yDetails) {
                    if ($yParent == "in") {
                        $bgcolor = "background-color:#DFF0D8;";
                    }
                    elseif ($yParent == "out") {
                        $bgcolor = "background-color:#F2DEDE;";
                    }
                    else {
                        $bgcolor = "background:#e5e5e5;color:#555555;";
                    }
                    if (array_key_exists($yParent, $headerFields2)) {
                        foreach ($headerFields2[$yParent] as $jn => $unused) {
                            $detailsLabelsName = isset($detailsLabels[$jn]) ? $detailsLabels[$jn] : "&nbsp;";
                            foreach ($headerFields3[$yParent] as $h3key => $h3Label) {
                                $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$h3Label</th>";
                            }
                        }
                    }
                    if (array_key_exists($yParent, $headerFields4)) {
                        foreach ($headerFields4[$yParent] as $yy => $yyLabel) {
                            $data_total .= "<th class='text-center text-uppercase' style='$bgcolor;padding:3px;' colspan=''>$yyLabel</th>";
                        }
                    }
                }
                $data_total .= "</tr>";
            }


            $data_total .= "</thead>";

            // ----------------------------------------------------------
            $data_total .= "<tbody>";

            $total = array();
            // $itemsCek = array();

            foreach ($items as $x => $itemData) {
                $hightlight = "";
                if (isset($addStyle) && sizeof($addStyle) > 0) {
                    $hightlight = isset($addStyle[$itemData['transaksi_id']]) ? $addStyle[$itemData['transaksi_id']] : "";
                }
                $addDetils = $items2[$x];
                if (isset($itemsCek[$x]['in']) && $itemsCek[$x]['in'] > 0) {
                    $bgcolor = "background-color:#DFF0D8;$hightlight";
                }
                elseif (isset($itemsCek[$x]['out']) && $itemsCek[$x]['out'] > 0) {
                    $bgcolor = "background-color:#F2DEDE;$hightlight";
                }
                else {
                    $bgcolor = "$hightlight";
                }

                $data_total .= "<tr style='$bgcolor'>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    if (array_key_exists($headerKey, $headerFields2)) {
                        $detilsValue = isset($addDetils[$headerKey]) ? $addDetils[$headerKey] : array();
                        if (isset($headerFields2[$headerKey])) {
                            foreach ($headerFields2[$headerKey] as $jn => $unused) {
                                if (count($headerFields3) > 0 && isset($headerFields3[$headerKey])) {
                                    foreach ($headerFields3[$headerKey] as $keyh3 => $keyh3LAbel) {
                                        $cValue = isset($detilsValue[$jn][$keyh3]) ? $detilsValue[$jn][$keyh3] : 0;
                                        $data_total .= "<td>" . formatField_he_format("debet", $cValue) . "</td>";
                                        if (is_numeric($cValue) && $headerKey != 'jenis') {
                                            if (isset($summary) && in_array($keyh3, $summary)) {
                                                if (!isset($total[$headerKey][$jn][$keyh3])) {
                                                    $total[$headerKey][$jn][$keyh3] = 0;
                                                }
                                                $total[$headerKey][$jn][$keyh3] += $cValue;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else {
                        if (array_key_exists($headerKey, $headerFields4)) {
                            foreach ($headerFields4[$headerKey] as $h4Src => $h4Label) {
                                $cValue = $itemData[$h4Src];
                                $data_total .= "<td>" . formatField_he_format($h4Src, $cValue) . "</td>";
                                if (is_numeric($cValue) && $headerKey != 'jenis') {
                                    if (isset($summary) && in_array($h4Src, $summary)) {
                                        if (!isset($total[$headerKey][$h4Src])) {
                                            $total[$headerKey][$h4Src] = 0;
                                        }
                                        $total[$headerKey][$h4Src] += $cValue;
                                    }
                                }
                            }
                        }
                        else {
                            $cValue = isset($itemData[$headerKey]) ? $itemData[$headerKey] : "";
                            if (is_array($cValue)) {
                                $data_total .= "<td>";
                                if (sizeof($cValue) > 1) {
                                    // cekHitam("ada");
                                    foreach ($cValue as $cSpec) {

                                        if ($cSpec["nomer"] != $itemData["transaksi_no"]) {
                                            // cekMErah("t ". $cSpec["nomer"]);
                                            $data_total .= formatField_he_format("nomer", $cSpec["nomer"], $itemData['jenis_master'], $itemData["modul_path"]) . "<br>";
                                        }
                                    }
                                }
                                else {
                                    $data_total .= formatField_he_format("nomer", $cValue[1]["nomer"], $itemData['jenis_master'], $itemData["modul_path"]);
                                }
                                $data_total .= "</td>";
                            }
                            else {
                                if (isset($addDetailLink) && sizeof($addDetailLink) > 0) {
                                    if (isset($addDetailLink[$itemData['transaksi_id']][$headerKey])) {
                                        $link = $addDetailLink[$itemData['transaksi_id']][$headerKey];
                                        $data_total .= "<td><a href='$link' target='_blank'>";
                                        $data_total .= formatField_he_format($headerKey, $cValue);
                                        $data_total .= "</a></td>";
                                    }
                                    else {
                                        $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
                                    }
                                }
                                else {
                                    $data_total .= "<td>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
                                }
                            }
                        }

                    }
                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            $data_total .= "<tfoot>";
            $data_total .= "<tr>";
            foreach ($headerFields as $nm => $dta) {
                if (isset($headerFields2[$nm])) {
                    // cekBiru($nm);
                    foreach ($headerFields2[$nm] as $jn => $unused) {
                        foreach ($headerFields3[$nm] as $h => $j) {
                            if (isset($total[$nm][$jn][$h])) {
                                $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$jn][$h]) . "</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }
                        }
                    }
                }
                else {
                    if (isset($headerFields4[$nm])) {

                        foreach ($headerFields4[$nm] as $h => $yy) {
                            $value = isset($total[$nm][$h]) ? formatField("angka", $total[$nm][$h]) : "-";
                            if (isset($total[$nm][$h])) {
                                $data_total .= "<td class='text-right text-bold' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField("debet", $total[$nm][$h]) . "</td>";
                            }
                            else {
                                $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                            }

                        }
                    }
                    else {
                        $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>&nbsp;-</td>";
                    }
                }

            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";


            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= "<div class='clearfix col-sm-12 col-md-12 col-lg-12'><h3>$title $subTitle</h3></div>";;
            $list_data .= $data_total;
            $list_data .= "<script>
                $modalSize
                               
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        // $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview$tbl_id = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                        
                                        close_holdon();
                                                                                                                                       
                                            },
                                    stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
                                        }
                                    ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        var arrayFooter = $('tfoot>tr>th');
                                        var dpageTotal = [];
                                        var jml_kolom = (arrayFooter.length) - 1;
                                        jQuery.each(arrayFooter, function(i,d){
                                            var id_n_index = parseFloat(i);
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii = '', obj = ''){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);
                                        //
                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );

                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
                    });
                    

                                    }, 500));

                   </script>";

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='box-body text-center'>";

            $list_data .= ("no changes found within selected date(s).<br>");
            $list_data .= ("you may try to select other date(s) or you can click <strong>BACK</strong> button to go to previous page<br>");

            $list_data .= "</div>";


        }


        //        $list_data .= "<script>
        ////$('.modal-dialog').removeClass('.modal-lg').addClass('.modal-xl');
        //$('.modal-dialog').addClass('modal-xl');
        //
        //</script>";
        echo $list_data;
        //        $p->addTags(array(
        //            "menu_left" => callMenuLeft(),
        //            //                "trans_menu" => callTransMenu(),
        //            "float_menu_atas" => callFloatMenu('atas'),
        //            "float_menu_bawah" => callFloatMenu(),
        //            "menu_taskbar" => callMenuTaskbar(),
        //            "btn_back" => callBackNav(),
        //            "content" => $list_data,
        //            "profile_name" => $this->session->login['nama'],
        //            "date1" => $filters['date1'],
        //            "date2" => $filters['date2'],
        //            //            "date_min" => date("Y-01-01"),
        //            "date_min" => "2019-01-01",
        //            "date_max" => $filters['dates']['end'],
        //            "url" => $thisPage,
        //            "disabled" => isset($disabled) ? $disabled : "",
        //            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
        //            "geturl" => isset($geturl) ? $geturl : "",
        //        ));
        //        $p->setContent($contens);
        //        $p->render();

        break;


    case "saldo_2":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";

        $arrBgColor = array();
        if (isset($items_blok) && sizeof($items_blok) > 0) {
            foreach ($items_blok as $ctr => $spec) {
                $bagi = $ctr % 2;

                if ($bagi == 0) {
                    $background_color = "background-color:#F8F8FF;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
                else {
                    $background_color = "background-color:#FFE4E1;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
            }
        }

        $memberships = $_SESSION['login']['membership'];

        //region Description searching by php...
        $list_data .= "<div class='panel'>";
        $list_data .= "<div class='input-group'>";

        $link_excel = base_url() . "ExcelWriter/persediaan/$param_to_excel";
        $list_data .= "<span class='input-group-btn'>";
        // if (in_array("c_holding", $memberships)) {

        $allowBtns = array(
            "c_gudang_spv",
            "c_holding"
        );
        // arrPrint($memberships);
        // arrPrint($allowBtns);

        if (isset($param_to_excel)) {

            $btnExcels = array();
            foreach ($memberships as $membership) {
                $btnExcel = array();
                if (in_array($membership, $allowBtns)) {
                    $btnExcels[] = $membership;
                }
            }
            // if (in_array("c_gudang_spv", $memberships)) {
            // cekKuning(sizeof($btnExcels));
            if (isset($btnExcels) && sizeof($btnExcels) > 0) {
                // $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' onclick=\"location.href='$link_excel'\"><i class='fa fa-file-excel-o'>&nbsp;</i>excel</button>";

                $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' 
                    
                    onclick=\"btn_result('$link_excel');\"><i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk</button>";
            }
            else {
                $list_data .= "<button type='button' disabled class='btn btn-default' data-toggle='tooltip' title='download ke excel' data-placement='right' 
                    onclick=\"location.href='#'\"><i class='fa fa-file-excel-o'>&nbsp;</i>excel</button>";
            }
        }

        if (isset($dateSelected) && ($dateSelected == true)) {
            $list_data .= "<span class='input-group-add-on' >select month </span>";
            $list_data .= "<input type='date' class='form-control' value='$defaultDate' min='$oldDate' max='" . date("Y-m-d") . "' onchange=\"location.href='$thisPage&date='+this.value;\">";

        }

        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        $list_data .= "</span>";
        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
        $list_data .= "<span class='input-group-btn'>";
        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        $list_data .= "</span class='input-group-addon'>";
        $list_data .= "</div class='input-group'>";
        $list_data .= "</div class='panel panel-default'>";
        //endregion


        $data_total = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display'>";

            $data_total .= "<thead>";

            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 1==========
            //========================

            //========================
            //========AREA HEADER LEVEL 2==========
            //            $data_total .= "<tr bgcolor='#e5e5e5'>";
            //            $data_total .= "<th align='right'></th>";
            //            foreach ($headerFields as $cName => $cValue) {
            //                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'></th>";
            //            }
            //            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 2==========
            //========================

            $data_total .= "</thead>";

            $total = array();
            $iCtr = 0;
            //arrPrint($items);
            //            arrPrint($headerFields);
            //            arrPrint($pairedSerial_add);
            $data_total .= "<tbody>";
            foreach ($items as $cData) {
                $iCtr++;
                //arrPrintWebs($cData);
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";

                //                arrPrint($pairedSerial_add);

                $data_total .= "<tr style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;

                    if (isset($customLinkAdd) && count($customLinkAdd) > 0) {
                        if (isset($customLinkAdd[$pid]["customLink"][$headerKey])) {
                            $adlink = $customLinkAdd[$pid]["customLink"][$headerKey];
                            $link = $cData['link'] . "&w=$adlink";
                            //                            matiHere();
                        }
                        else {
                            //                            cekMerah($headerKey);
                            $link = $cData['link'];
                        }

                    }
                    else {
                        $link = $cData['link'];
                    }
                    $linkMain = isset($cData['link_main'][$headerKey]) ? $cData['link_main'][$headerKey] : NULL;


                    $data_total .= "<td title='$headerKey'>";
                    // $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";


                    if ($linkMain != NULL) {
                        $data_total .= "<span class='pull-right'><a href='$linkMain' data-toggle='tooltip' title='mutasi $cValue' target='_blank'><span class='text-muted fa fa-clock-o'></span></a></span>";
                    }

                    if ($headerKey == "extern_nama") {
                        if (isset($pairedResult_add[$cData['pId']]['link_history']) && ($pairedResult_add[$cData['pId']]['link_history'] != NULL)) {

                            $historyClick = $pairedResult_add[$cData['pId']]['link_history'];
                            $data_total .= "<a href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view data histories of this entry' 
                                onclick=\"$historyClick\">
                                <span class='pull-right text-muted fa fa-clock-o'></span>
                                </a>";
                        }
                        if (isset($pairedResult_add[$cData['pId']]['keterangan'])) {

                            $keterangan = "\n" . $pairedResult_add[$cData['pId']]['keterangan'];
                            $data_total .= nl2br($keterangan);
                        }
                    }
                    if ($headerKey == "jml_serial") {
                        /* ------------------------------
                         * serial viewer
                         * --------------------------------*/
                        $qty_debet_nya = $cData['qty_debet'];
                        // cekHere("$cValue % $qty_debet_nya");
                        $sisa_serial = $cValue >= $qty_debet_nya ? $cValue % $qty_debet_nya : 0;
                        if ($sisa_serial > 0) {
                            $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                            // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                            $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                        }
                        else {
                            $sisa_serial_f = "";
                            $sisa_title = "$cValue";
                        }
                        $jml_serial_ok = $cValue >= $qty_debet_nya ? $cValue - $sisa_serial : $cValue;

                        if ($qty_debet_nya > 0) {
                            $btn_serial_number = "<button type='button' class='btn btn-success *******' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' 
    onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        else {
                            $btn_serial_number = "<button type='button' class='btn btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                        }
                        // -----------------------------------------
                        if (isset($pairedSerial_add[$cData['pId']]['link_serial']) && ($pairedSerial_add[$cData['pId']]['link_serial'] != NULL)) {

                            $historyClick_serial = $pairedSerial_add[$cData['pId']]['link_serial'];
                            $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_barcode'])) {
                            $historyClick_barcode = $pairedSerial_add[$cData['pId']]['link_barcode'];
                            $data_total2 = "<span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "<span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>";
                        }
                        /* ----------------------------------
                         * penampil button
                         * -----------------------------*/
                        if ($cData["tipe_produk"] == "serial") {
                            $data_total .= "<div class=\"btn-group pull-right\" >";
                            $data_total .= $btn_serial_number;
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey])) {
                                $data_total .= "
                              <button type='button' class='btn btn-success' title='lihat detail serial'>$data_total1</button>
                              <button type='button' class='btn btn-warning' title='cetak serial barcode'>$data_total2</button>
                              <button type='button' class='btn btn-danger' title='cetak serial qr'>$data_total3</button>";
                            }
                            $data_total .= "</div>";
                        }
                        else {
                            $data_total .= "-";
                        }
                    }
                    else {
                        $aa_var = "<button onclick=\"window.open('$link', '_blank')\" type='button' data-toggle='tooltip' class='btn btn-xs btn-warning' title='saldo qty $cValue'>" . formatField($headerKey, $cValue) . "</button>";
                        if (isset($pairedSerial_add[$pid][$headerKey])) {
                            //                            $data_total .=$pairedSerial_add[$pid][$headerKey]["jml_serial"]."~~";
                            $qty_debet_nya = $cData['qty_debet'];
                            // cekHere("$cValue % $qty_debet_nya");
                            $sisa_serial = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] % $qty_debet_nya : 0;
                            if ($sisa_serial > 0) {
                                $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                                // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                                $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                            }
                            else {
                                $sisa_serial_f = "";
                                $sisa_title = "$cValue";
                            }

                            //                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] - $sisa_serial : $pairedSerial_add[$pid][$headerKey]["jml_serial"];

                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"];
                            $jml_serial_transit_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial_transit"];

                            //                            if ($qty_debet_nya > 0) { //<<=========== INI PENYEBAB NYA
                            $historyClick_serial_transit = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr_transit'];
                            $btn_serial_number2 = "<button type='button' pid=$pid headerKey=$headerKey 
                                class='btn btn-xs btn-danger' data-toggle='tooltip' title='jumlah serial intransit' style='ppadding: 3px 5px;width: 47px;' 
                                onclick=\"$historyClick_serial_transit\" >$jml_serial_transit_ok</button>";
                            $btn_serial_number = "<button type='button' pid=$pid headerKey=$headerKey class='btn btn-xs btn-success' data-toggle='tooltip' title='jumlah serial' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok</button>";

                            //                            }

                            //                            elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            //                                $link_remove = $linkRemoveSerial . "/$pid";
                            //                                $sisa_title .= "serial number bisa diremove";
                            //                                $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-xs btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                            //                            }
                            //                            else {
                            //                                $btn_serial_number = "<button type='button' class='btn btn-xs btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                            //                            }
                            // -----------------------------------------
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_serial']) && ($pairedSerial_add[$cData['pId']][$headerKey]['link_serial'] != NULL)) {

                                $historyClick_serial = $pairedSerial_add[$cData['pId']][$headerKey]['link_serial'];
                                $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'])) {
                                $historyClick_barcode = $pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'];
                                $data_total2 = "
                                <span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_qr'])) {
                                $historyClick_qr = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr'];
                                $data_total3 = "
                                <span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>
                                ";
                            }
                            /* ----------------------------------
                             * penampil button
                             * -----------------------------*/
                            //                            cekHere($cData["tipe_produk"]);
                            if ($cData["tipe_produk"] == "serial") {
                                $data_total .= "<div class=\"btn-group pull-left\" >";
                                $data_total .= $btn_serial_number2;
                                $data_total .= $btn_serial_number;
                                if (isset($pairedSerial_add[$cData['pId']][$headerKey]) && $cValue > 0) {
                                    $data_total .= "
                                                    <button type='button' class='btn btn-xs btn-success' title='lihat detail serial'>$data_total1 </button>
                                                    <button type='button' class='btn btn-xs btn-warning' title='cetak serial barcode'>  $data_total2</button>
                                                    <button type='button' class='btn btn-xs btn-danger' title='cetak serial qr'>  $data_total3</button>";
                                }

                                $data_total .= "$aa_var";
                                $data_total .= "</div>";
                            }
                            else {
                                // disini tipe produk bukan serial, ditampilkan apa adanya... 02 maret 2024
                                //                                $data_total .= "-";
                                //                                $data_total .= "$aa_var";
                                $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                            }
                        }
                        else {
                            //                            cekHere("tidak ada paired serial");
                            //                            $data_total .="$aa_var";
                            $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                        }
                        //                        $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                    }

                    $data_total .= "</td>";


                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";


            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;' title='$cName'>" . $totalVal . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= "there is no item name matched your criteria<br>";
            $list_data .= "you mant want to go back or select other keyword<br>";

            $list_data .= "</div>";
            $list_data .= "</div>";

        }
        $params = array(
            "fifo" => "MdlFifoAverage",
            "cabang_id" => my_cabang_id(),
        );
        $paramEs = blobEncode($params);
        $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
            "link_excel" => $linkExcell,
        ));

        $p->setContent($contens);
        $p->render();
        break;
    case "saldo_periode":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        $list_data .= "<style>
            .pagination{
                margin:unset;
            }
            a.produk_id {
                text-decoration: underline;
            }
            a:hover{
                color: red !important;
            }
        </style>";

        $arrBgColor = array();
        if (isset($items_blok) && sizeof($items_blok) > 0) {
            foreach ($items_blok as $ctr => $spec) {
                $bagi = $ctr % 2;

                if ($bagi == 0) {
                    $background_color = "background-color:#F8F8FF;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
                else {
                    $background_color = "background-color:#FFE4E1;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
            }
        }


        //region Description searching by php...
        $list_data .= "<div class='panel'>";

        $list_data .= "<div class='row'>";
        $list_data .= "<div class='col-md-4'>";
        $list_data .= "<div class='input-group'>";

        $list_data .= "<span class='input-group-btn'>";
        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        $list_data .= "</span>";

        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";

        $list_data .= "<span class='input-group-btn'>";

        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        $list_data .= "</span>";

        $list_data .= "</div>";
        $list_data .= "</div>";
        $get2 = $get = $_GET;
        unset($get2['date']);
        // arrPrintKuning($get);
        // arrPrintKuning($get2);
        $get_new = http_build_query($get2);
        $link_baru = current_url() . "?$get_new";

        $list_data .= "<div class='col-md-2'>";
        $list_data .= "<input type='date' name='date' minn='$defaultDate' max='$maxDate' id='date' class='form-control' value='$defaultDate' onchange=\"location.href='$link_baru&date=' + this.value\">";
        $list_data .= "</div>";
        // $list_data .= "$link_baru";
        $list_data .= "<div class='col-md-6'>";
        $list_data .= $btnPage;
        $list_data .= "</div>";

        $list_data .= "</div>"; // row

        $list_data .= "</div>";
        $list_data .= $warning_str;
        //endregion


        $data_total = "";
        $cekplus = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";
            $data_total .= "<table id='myNewTable_2' width='100%' class='table table-bordered table-hover'>";

            $data_total .= "<thead>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            // arrPrintPink($headerFields);
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;' title='$cName'>";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=ASC' title='sort by $cValue, ascending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-up'></span></a>&nbsp;";
                $data_total .= "$cValue&nbsp;";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=DESC' title='sort by $cValue, descending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-down'></span></a>";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";


            $data_total .= "<tbody>";
            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;
                // arrPrint($cData);
                // matiHere(__LINE__);
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";
                //cekHere($customBackgroundStyle[0]);
                $data_total .= "<tr style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $addStyle = isset($headerStyle[$headerKey]) ? $headerStyle[$headerKey] : "";
                    if (isset($customBackgroundStyle)) {
                        //                        cekHere($cData[$customBackgroundStyle[1]]);
                        //                        cekHitam($cData[$customBackgroundStyle[0]]);
                        //                        if($cData[$customBackgroundStyle[1]] < $cData[$customBackgroundStyle[1]]){
                        $cek1 = $customBackgroundStyle[1];
                        $cek2 = $customBackgroundStyle[0];
                        //                        if($cData["harga_list"] < $cData["harga_beli"]){
                        if ($cData[$cek1] < $cData[$cek2]) {
                            $addStyle .= "background-color:red;";
                        }

                    }

                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;

                    $link = $cData['link'];
                    $data_total .= "<td style='color:#000000;$addStyle' title='$headerKey'><a class='$headerKey' href='$link' target='_blank' style='color:#000000;'>" . formatField($headerKey, $cValue) . "</a></td>";
                    //                    $data_total .= "<td style='color:#000000;$addStyle'><a href='$link' target='_blank' style='color:#000000;'>" . $cValue . "</a>*</td>";

                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                        $cekplus++;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            // cekMerah($cekplus);
            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . $totalVal . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "<script>
                    $(document).ready( function(){
                        var table = $('#myNewTable_2').dataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            paging: false,
                            searching: false,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
                                    ],
                           buttons: [
                                       'copy', 'csv', 'excel', 'pdf', 'print'
                                   ],
//                            buttons: [
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'Office info',
//                                            show: [ 1, 2 ],
//                                            hide: [ 3, 4, 5 ]
//                                        },
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'HR info',
//                                            show: [ 3, 4, 5 ],
//                                            hide: [ 1, 2 ]
//                                        },
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'Show all',
//                                            show: ':hidden'
//                                        }
//                                    ]
                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        var arrayFooter = $('tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){

                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;

                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{

                                                }

                                            });

                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                            $( api.column(id_n_index).footer() ).html(
                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                            );
                                        }


                                        });

                                    }
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

            $list_data .= "<div class='row'>";
            $list_data .= "<div class='col-md-6'>";
            $list_data .= $btnPage;
            $list_data .= "</div>";
            $list_data .= "</div>";

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= "there is no item name matched your criteria<br>";
            $list_data .= "you mant want to go back or select other keyword<br>";

            $list_data .= "</div>";
            $list_data .= "</div>";

        }


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "content_free" => isset($content_free) ? $content_free : "",
            "profile_name" => $this->session->login['nama'],
        ));

        $p->setContent($contens);
        $p->render();
        break;

    case "saldo_periode_3":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        $list_data .= "<style>
            .pagination{
                margin:unset;
            }
            a.produk_id {
                text-decoration: underline;
            }
            a:hover{
                color: red !important;
            }
        </style>";

        $arrBgColor = array();
        if (isset($items_blok) && sizeof($items_blok) > 0) {
            foreach ($items_blok as $ctr => $spec) {
                $bagi = $ctr % 2;

                if ($bagi == 0) {
                    $background_color = "background-color:#F8F8FF;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
                else {
                    $background_color = "background-color:#FFE4E1;";
                    $arrBgColor[$spec['main']] = $background_color;
                    $arrBgColor[$spec['relasi']] = $background_color;
                }
            }
        }


        //region Description searching by php...
        $list_data .= "<div class='panel'>";

        $list_data .= "<div class='row'>";
        $list_data .= "<div class='col-md-4'>";
        $list_data .= "<div class='input-group'>";

        $list_data .= "<span class='input-group-btn'>";
        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        $list_data .= "</span>";

        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";

        $list_data .= "<span class='input-group-btn'>";

        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        $list_data .= "</span>";

        $list_data .= "</div>";
        $list_data .= "</div>";
        $get2 = $get = $_GET;
        unset($get2['date']);
        // arrPrintKuning($get);
        // arrPrintKuning($get2);
        $get_new = http_build_query($get2);
        $link_baru = current_url() . "?$get_new";

        $list_data .= "<div class='col-md-2'>";
        $list_data .= "<input type='date' name='date' minn='$defaultDate' max='$maxDate' id='date' class='form-control' value='$defaultDate' onchange=\"location.href='$link_baru&date=' + this.value\">";
        $list_data .= "</div>";
        // $list_data .= "$link_baru";
        $list_data .= "<div class='col-md-6'>";
        $list_data .= $btnPage;
        $list_data .= "</div>";

        $list_data .= "</div>"; // row

        $list_data .= "</div>";
        $list_data .= $warning_str;
        //endregion


        $data_total = "";
        $cekplus = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";
            $data_total .= "<table id='myNewTable_2' width='100%' class='table table-bordered table-hover'>";

            $data_total .= "<thead>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            // arrPrintPink($headerFields);
            foreach ($headerFields as $cName => $cValue) {
                $class = isset($headerFieldsClass[$cName]["class"]) ? $headerFieldsClass[$cName]["class"] : "";
                $data_total .= "<th class='text-center text-uppercase $class' style='color:#555555;padding:3px;' title='$cName'>";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=ASC' title='sort by $cValue, ascending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-up'></span></a>&nbsp;";
                $data_total .= "$cValue&nbsp;";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=DESC' title='sort by $cValue, descending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-down'></span></a>";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";


            $data_total .= "<tbody>";
            $total = array();
            $iCtr = $nomer_mulai;
            foreach ($items as $cData) {
                $iCtr++;
                // arrPrint($cData);

                // matiHere(__LINE__);
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";
                //cekHere($customBackgroundStyle[0]);
                $data_total .= "<tr style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $class = isset($headerFieldsClass[$headerKey]["class"]) ? $headerFieldsClass[$headerKey]["class"] : "";
                    $addStyle = isset($headerStyle[$headerKey]) ? $headerStyle[$headerKey] : "";
                    if (isset($customBackgroundStyle)) {
                        //                        cekHere($cData[$customBackgroundStyle[1]]);
                        //                        cekHitam($cData[$customBackgroundStyle[0]]);
                        //                        if($cData[$customBackgroundStyle[1]] < $cData[$customBackgroundStyle[1]]){
                        $cek1 = $customBackgroundStyle[1];
                        $cek2 = $customBackgroundStyle[0];
                        //                        if($cData["harga_list"] < $cData["harga_beli"]){
                        if ($cData[$cek1] < $cData[$cek2]) {
                            $addStyle .= "background-color:red;";
                        }

                    }

                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;

                    $expl = explode(" ", $headerLabel);
                    $strink2 = $expl[1];
                    if ($strink2 != 0) {
                        $cabang_gudang_id = $cData["cabang_gudang_" . $strink2];
                        $w = "&w=$strink2&o=$cabang_gudang_id";
                    }
                    else {
                        $w = "";
                    }
                    $link = $cData['link'] . $w;
                    $data_total .= "<td style='color:#000000;$addStyle' class='$class' title='$headerKey'>
                                    <a class='$headerKey' href='$link' target='_blank' style='color:#000000;'>" . formatField($headerKey, $cValue) . "</a>
                                </td>";
                    //                    $data_total .= "<td style='color:#000000;$addStyle'><a href='$link' target='_blank' style='color:#000000;'>" . $cValue . "</a>*</td>";

                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                        $cekplus++;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            // cekMerah($cekplus);
            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                $class = isset($headerFieldsClass[$cName]["class"]) ? $headerFieldsClass[$cName]["class"] : "";
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right $class' style='color:#555555;padding:3px;'>" . $totalVal . " <div class='meta'>$cValue</div></td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "<script>
                    $(document).ready( function(){
                        var table = $('#myNewTable_2').dataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            paging: false,
                            searching: false,
                            buttons: [
                                        { extend: 'print', footer: true },
                                          $custom_button
                                    ],
                           buttons: [
                                       'copy', 'csv', 'excel', 'pdf', 'print'
                                   ],
//                            buttons: [
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'Office info',
//                                            show: [ 1, 2 ],
//                                            hide: [ 3, 4, 5 ]
//                                        },
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'HR info',
//                                            show: [ 3, 4, 5 ],
//                                            hide: [ 1, 2 ]
//                                        },
//                                        {
//                                            extend: 'colvisGroup',
//                                            text: 'Show all',
//                                            show: ':hidden'
//                                        }
//                                    ]
                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        var arrayFooter = $('tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){

                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;

                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{

                                                }

                                            });

                                        if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                            $( api.column(id_n_index).footer() ).html(
                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                            );
                                        }


                                        });

                                    }
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

            $list_data .= "<div class='row'>";
            $list_data .= "<div class='col-md-6'>";
            $list_data .= $btnPage;
            $list_data .= "</div>";
            $list_data .= "</div>";

        }
        else {

            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= "there is no item name matched your criteria<br>";
            $list_data .= "you mant want to go back or select other keyword<br>";

            $list_data .= "</div>";
            $list_data .= "</div>";

        }


        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "content_free" => isset($content_free) ? $content_free : "",
            "profile_name" => $this->session->login['nama'],
        ));

        $p->setContent($contens);
        $p->render();
        break;

    case "loadSaldo":
        /**
         * metode concat
         */
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/default.html");

        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );

        $this->table->set_template($template);
        $list_data = "";
        $list_data .= "<h4>$title</h4>";


        $memberships = $_SESSION['login']['membership'];

        //region Description searching by php...
        $list_data .= "<div class='panel'>";
        $list_data .= "<div class='input-group'>";

        $link_excel = base_url() . "ExcelWriter/persediaan/$param_to_excel";
        // $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";
        $list_data .= "<span class='input-group-btn'>";
        // if (in_array("c_holding", $memberships)) {
        // matiHere(__LINE__);
        /*---allow to download-------------*/
        $allowBtns = array(
            "c_gudang",
            "c_gudang_spv",
            "c_holding"
        );
        // arrPrint($memberships);
        // arrPrint($allowBtns);

        if (isset($param_to_excel)) {

            $btnExcels = array();
            foreach ($memberships as $membership) {
                $btnExcel = array();
                if (in_array($membership, $allowBtns)) {
                    $btnExcels[] = $membership;
                }
            }
            // if (in_array("c_gudang_spv", $memberships)) {
            // cekKuning(sizeof($btnExcels));
            if (isset($btnExcels) && sizeof($btnExcels) > 0) {
                // $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' onclick=\"location.href='$link_excel'\"><i class='fa fa-file-excel-o'>&nbsp;</i>excel</button>";

                // $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download ke excel' data-placement='right' onclick=\"btn_result('$link_excel');\"><i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk</button>";
                $list_data .= "<button type='button' class='btn btn-primary' data-toggle='tooltip' title='download seluruh data ke excel' data-placement='right' onclick=\"btn_alert_result('Excell','Download data akan muncul setelah beberapa saat diklik','$link_excel');\"><i class='fa fa-file-excel-o'>&nbsp;</i> Download Data Produk</button>";
            }
            else {
                $list_data .= "<button type='button' disabled class='btn btn-default' data-toggle='tooltip' title='download ke excel' data-placement='right' 
                    onclick=\"location.href='#'\"><i class='fa fa-file-excel-o'>&nbsp;</i>Download Data Produk</button>";
            }
        }

        if (isset($dateSelected) && ($dateSelected == true)) {
            $list_data .= "<span class='input-group-add-on' >select month </span>";
            $list_data .= "<input type='date' class='form-control' value='$defaultDate' min='$oldDate' max='" . date("Y-m-d") . "' onchange=\"location.href='$thisPage&date='+this.value;\">";

        }

        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        $list_data .= "</span>";
        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
        $list_data .= "<span class='input-group-btn'>";
        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        $list_data .= "</span class='input-group-addon'>";
        $list_data .= "</div class='input-group'>";
        $list_data .= "</div class='panel panel-default'>";
        //endregion


        $data_total = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable_saldo' class='table display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            //========END AREA HEADER LEVEL 1==========
            //========================
            $data_total .= "</thead>";

            $total = array();
            $iCtr = 0;
            $data_total .= "<tbody>";
            foreach ($items as $cData) {
                $iCtr++;
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";
                $data_total .= "<tr id='tr$iCtr' style='$bgColor'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $orderValue = $cValue;
                    $headerKeyNormalized = strtolower((string)$headerKey);
                    $isDueDateField = (strpos($headerKeyNormalized, "due_date") !== FALSE) || (strpos($headerKeyNormalized, "duedate") !== FALSE);
                    if ($isDueDateField) {
                        $dueDateRaw = trim(strip_tags((string)$cValue));
                        if ($dueDateRaw !== "") {
                            $dueDateTs = strtotime($dueDateRaw);
                            if ($dueDateTs !== FALSE) {
                                $orderValue = date("YmdHis", $dueDateTs);
                            }
                            else {
                                $orderValue = strtolower($dueDateRaw);
                            }
                        }
                        else {
                            $orderValue = "";
                        }
                    }
                    elseif (is_numeric($orderValue)) {
                        $orderValue = (string)(0 + $orderValue);
                    }
                    else {
                        $orderValue = strtolower(trim(strip_tags((string)$orderValue)));
                    }
                    $orderValueAttr = htmlspecialchars((string)$orderValue, ENT_QUOTES);
                    if (isset($customLinkAdd) && count($customLinkAdd) > 0) {
                        if (isset($customLinkAdd[$pid]["customLink"][$headerKey])) {
                            $adlink = $customLinkAdd[$pid]["customLink"][$headerKey];
                            $link = $cData['link'] . "&w=$adlink";
                            //                            matiHere();
                        }
                        else {
                            //                            cekMerah($headerKey);
                            $link = $cData['link'];
                        }

                    }
                    else {
                        $link = $cData['link'];
                    }
                    $linkMain = isset($cData['link_main'][$headerKey]) ? $cData['link_main'][$headerKey] : NULL;

                    $data_total .= "<td title='$headerKey' data-order='$orderValueAttr'>";

                    if ($linkMain != NULL) {
                        $data_total .= "<span class='pull-right'><a href='$linkMain' data-toggle='tooltip' title='mutasi $cValue' target='_blank'><span class='text-muted fa fa-clock-o'></span></a></span>";
                    }

                    if ($headerKey == "extern_nama") {
                        if (isset($pairedResult_add[$cData['pId']]['link_history']) && ($pairedResult_add[$cData['pId']]['link_history'] != NULL)) {
                            $historyClick = $pairedResult_add[$cData['pId']]['link_history'];
                            $data_total .= "<a href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view data histories of this entry' 
                                onclick=\"$historyClick\">
                                <span class='pull-right text-muted fa fa-clock-o'></span>
                                </a>";
                        }
                        if (isset($pairedResult_add[$cData['pId']]['keterangan'])) {
                            $keterangan = "\n" . $pairedResult_add[$cData['pId']]['keterangan'];
                            $data_total .= nl2br($keterangan);
                        }
                    }
                    if ($headerKey == "jml_serial") {
                        /* ------------------------------
                         * serial viewer
                         * --------------------------------*/
                        $qty_debet_nya = $cData['qty_debet'];
                        // cekHere("$cValue % $qty_debet_nya");
                        $sisa_serial = $cValue >= $qty_debet_nya ? $cValue % $qty_debet_nya : 0;
                        if ($sisa_serial > 0) {
                            $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                            // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                            $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                        }
                        else {
                            $sisa_serial_f = "";
                            $sisa_title = "$cValue";
                        }
                        $jml_serial_ok = $cValue >= $qty_debet_nya ? $cValue - $sisa_serial : $cValue;

                        if ($qty_debet_nya > 0) {
                            $btn_serial_number = "<button type='button' class='btn btn-success *******' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' 
    onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        else {
                            $btn_serial_number = "<button type='button' class='btn btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                        }
                        // -----------------------------------------
                        if (isset($pairedSerial_add[$cData['pId']]['link_serial']) && ($pairedSerial_add[$cData['pId']]['link_serial'] != NULL)) {

                            $historyClick_serial = $pairedSerial_add[$cData['pId']]['link_serial'];
                            $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_barcode'])) {
                            $historyClick_barcode = $pairedSerial_add[$cData['pId']]['link_barcode'];
                            $data_total2 = "<span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "<span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>";
                        }
                        /* ----------------------------------
                         * penampil button
                         * -----------------------------*/
                        if ($cData["tipe_produk"] == "serial") {
                            $data_total .= "<div class=\"btn-group pull-right\" >";
                            $data_total .= $btn_serial_number;
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey])) {
                                $data_total .= "
                              <button type='button' class='btn btn-success' title='lihat detail serial'>$data_total1</button>
                              <button type='button' class='btn btn-warning' title='cetak serial barcode'>$data_total2</button>
                              <button type='button' class='btn btn-danger' title='cetak serial qr'>$data_total3</button>";
                            }
                            $data_total .= "</div>";
                        }
                        else {
                            $data_total .= "-";
                        }
                    }
                    else {
                        // qty produk gudang ---------------------------------------------------------
                        $aa_var = "<button onclick=\"window.open('$link', '_blank')\" type='button' data-toggle='tooltip' class='btn btn-xs btn-warning' title='saldo qty $cValue'>" . formatField($headerKey, $cValue) . "</button>";
                        if (isset($pairedSerial_add[$pid][$headerKey])) {
                            //                            $data_total .=$pairedSerial_add[$pid][$headerKey]["jml_serial"]."~~";
                            $qty_debet_nya = $cData['qty_debet'];
                            // cekHere("$cValue % $qty_debet_nya");
                            $sisa_serial = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] % $qty_debet_nya : 0;
                            if ($sisa_serial > 0) {
                                $sisa_serial_f = $sisa_serial > 0 ? "<sub style='color: cyan'>$sisa_serial</sub>" : "";
                                // $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan, bisa dihapus saat persediaan kosong";
                                $sisa_title = "$sisa_serial serial sudah tidak ada dalam persediaan";
                            }
                            else {
                                $sisa_serial_f = "";
                                $sisa_title = "$cValue";
                            }

                            //                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"] >= $qty_debet_nya ? $pairedSerial_add[$pid][$headerKey]["jml_serial"] - $sisa_serial : $pairedSerial_add[$pid][$headerKey]["jml_serial"];

                            $jml_serial_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial"];
                            $jml_serial_transit_ok = $pairedSerial_add[$pid][$headerKey]["jml_serial_transit"];

                            //                            if ($qty_debet_nya > 0) { //<<=========== INI PENYEBAB NYA
                            $historyClick_serial_transit = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr_transit'];
                            $btn_serial_number2 = "<button type='button' pid=$pid headerKey=$headerKey 
                                class='btn btn-xs btn-danger' data-toggle='tooltip' title='jumlah serial intransit' style='ppadding: 3px 5px;width: 47px;' 
                                onclick=\"$historyClick_serial_transit\" >$jml_serial_transit_ok</button>";
                            $btn_serial_number = "<button type='button' pid=$pid headerKey=$headerKey class='btn btn-xs btn-success' data-toggle='tooltip' title='jumlah serial' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok</button>";

                            //                            }

                            //                            elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            //                                $link_remove = $linkRemoveSerial . "/$pid";
                            //                                $sisa_title .= "serial number bisa diremove";
                            //                                $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-xs btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
                            //                            }
                            //                            else {
                            //                                $btn_serial_number = "<button type='button' class='btn btn-xs btn-link' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >-</button>";
                            //                            }
                            // -----------------------------------------
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_serial']) && ($pairedSerial_add[$cData['pId']][$headerKey]['link_serial'] != NULL)) {

                                $historyClick_serial = $pairedSerial_add[$cData['pId']][$headerKey]['link_serial'];
                                $data_total1 = "
                                <span class='fa fa-list'  onclick=\"$historyClick_serial\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'])) {
                                $historyClick_barcode = $pairedSerial_add[$cData['pId']][$headerKey]['link_barcode'];
                                $data_total2 = "
                                <span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>
                                ";
                            }
                            if (isset($pairedSerial_add[$cData['pId']][$headerKey]['link_qr'])) {
                                $historyClick_qr = $pairedSerial_add[$cData['pId']][$headerKey]['link_qr'];
                                $data_total3 = "
                                <span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>
                                ";
                            }
                            /* ----------------------------------
                             * penampil button
                             * -----------------------------*/
                            //                            cekHere($cData["tipe_produk"]);
                            if ($cData["tipe_produk"] == "serial") {
                                $data_total .= "<div class=\"btn-group pull-left\" >";
                                $data_total .= $btn_serial_number2;
                                $data_total .= $btn_serial_number;
                                if (isset($pairedSerial_add[$cData['pId']][$headerKey]) && $cValue > 0) {
                                    $data_total .= "
                                                    <button type='button' class='btn btn-xs btn-success' title='lihat detail serial'>$data_total1 </button>
                                                    <button type='button' class='btn btn-xs btn-warning' title='cetak serial barcode'>  $data_total2</button>
                                                    <button type='button' class='btn btn-xs btn-danger' title='cetak serial qr'>  $data_total3</button>";
                                }

                                $data_total .= "$aa_var";
                                $data_total .= "</div>";
                            }
                            else {
                                // disini tipe produk bukan serial, ditampilkan apa adanya... 02 maret 2024
                                //                                $data_total .= "-";
                                //                                $data_total .= "$aa_var";
                                $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                            }
                        }
                        else {
                            //                                                        cekHere("tidak ada paired serial --- $headerKey");
                            //                            $data_total .="$aa_var";
                            $data_total .= "<a href='$link' data-toggle='tooltip' title='detil $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                        }
                        //                        $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
                    }

                    if (($headerKey == "ng_qty_debet") && ($cData["ng_qty_debet"] > 0)) {
                        $historyClick_barcode = $pairedGudang_add[$cData['pId']]['link_history'];
                        $data_total .= "
                            <button type='button' class='btn btn-primary btn-xs' title='lihat detail stok per-gudang'>
                            <span class='fa fa-home' onclick=\"$historyClick_barcode\"></span>
                            </button>
                        ";
                    }

                    $data_total .= "</td>";


                    if (is_numeric($cValue) && in_array($headerKey, $summary)) {
                        if (!isset($total[$headerKey])) {
                            $total[$headerKey] = 0;
                        }
                        $total[$headerKey] += $cValue;
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";


            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;-";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    if (is_numeric($total[$cName])) {
                        if ($total[$cName] < 0) {
                            $totalVal = "(" . number_format($total[$cName] * -1) . ")";
                        }
                        else {
                            $totalVal = number_format($total[$cName]);
                        }
                    }
                    else {
                        $totalVal = number_format($total[$cName]);
                    }
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;' title='$cName'>" . $totalVal . "</td>";
                }
                else {
                    if ($cName == "nomer_po") {
                        $data_total .= "<td class='text-center text-uppercase text-bold' style='color:#555555;padding:1px;'>Total $bottomTitle</td>";
                    }
                    else {
                        $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                    }
                }
            }

            $data_total .= "</tr>";

            $data_total .= "</tfoot>";
            $data_total .= "</table>";
            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){
                        var table = $('#myNewTable_saldo').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            stateSave: true,
                            processing: true,
                            searchDelay: 1500,
                            order: [[1, 'asc']],
                            search: {
                                smart: false
                            },

                            buttons: [
//                                        { extend: 'colvis', text: 'Pilih Kolom' },
//                                        { 
//                                            extend: 'print',
//                                            footer: true,
//                                            exportOptions: {
//                                                columns: ':visible',
//                                                format: {
//                                                    body: function (data, row, column, node) {
//                                                        var el = $('<div>').html(data);
//                                                        var span = el.find('span').first();
//                                                        if (span.length) {
//                                                            return span.text().trim();
//                                                        } 
//                                                        else {
//                                                            return el.text().trim();
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                        },
//                                        {
//                                            text: 'Download Excel',
//                                            action: function (e, dt, node, config) {
//                                                fnExcelReport('table_history_$segmenUriEnc');
//                                            }
//                                        },

                                    ],



                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                                        var arrayFooter = $('#table_history_$segmenUriEnc>tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){

                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;

                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){

                                                var pos = obj.indexOf('<');
                                                var hr = obj.indexOf('<hr>');
                                                if(pos!==-1&&hr==-1&&id_n_index>0){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] >= 0 ){
                                            $( api.column(id_n_index).footer() ).html(
                                                \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                            );
                                        }


                                        });
                                    }
                                });


                                //new $.fn.dataTable.FixedHeader( table );
                                $('.table-responsive.table_history_$segmenUriEnc').floatingScroll();
                                $('.table-responsive.table_history_$segmenUriEnc').scroll(function() {
                                    setTimeout(function () {
                                        $('#table_history_$segmenUriEnc').DataTable().fixedHeader.adjust();
                                    }, 100);
                            });
                            });
                    </script>";

            $list_data .= $data_total;

        }
        else {

            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table dataTable compact nowrap display'>";
            $data_total .= "<thead>";
            //========================
            //========AREA HEADER LEVEL 1==========
            $colspan = 1;
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th colspan='$colspan' align='right'>No.</th>";
            foreach ($headerFields as $cName => $cValue) {
                if (is_array($cValue)) {
                    $label = $cValue["label"];
                    $bg_color = $cValue["bg-color"];
                }
                else {
                    $label = $cValue;
                    $bg_color = "";
                }
                $data_total .= "<th colspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
                $data_total .= "$label";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $data_total .= "<tbody>";
            $data_total .= "</tbody>";

            $data_total .= "<tfoot masuk_kosong>";
            $data_total .= "<tr bgcolor='#e5e5e5' id='current-page-footer'>";
            $data_total .= "</tr>";
            $data_total .= "<tr bgcolor='#e5e500' id='all-data-footer'>";
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";

            $data_total .= "</table>";
            $data_total .= "</div>";

            $list_data .= $data_total;

            //            $list_data .= "<div class='panel panel-default'>";
            //            $list_data .= "<div class='panel-body'>";
            //            $list_data .= "there is no item name matched your criteria<br>";
            //            $list_data .= "you mant want to go back or select other keyword<br>";
            //            $list_data .= "</div>";
            //            $list_data .= "</div>";
        }


        $params = array(
            "fifo" => "MdlFifoAverage",
            "cabang_id" => my_cabang_id(),
        );
        $headerFields_json = array_merge(array("no" => "no"), $headerFields);
        $paramEs = blobEncode($params);
        $linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";


        echo $list_data;

        break;


}

