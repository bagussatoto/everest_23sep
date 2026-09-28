<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/6/2019
 * Time: 8:44 PM
 */


switch ($mode) {
    case "saldo":
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

        // arrPrintHijau($items);
        $data_total = "";
        if (sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='table-responsive myNewTable'>";
            $data_total .= "<table id='myNewTable' class='table display'>";

            $data_total .= "<thead>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=ASC' title='sort by $cValue, ascending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-up'></span></a>&nbsp;";
                $data_total .= "$cValue&nbsp;";
                //                $data_total .= "<a class='btn btn-tool' href='" . $thisURL . "&sortBy=$cName&sortMode=DESC' title='sort by $cValue, descending' data-toggle='tooltip' data-placement='right'><span class='fa fa-arrow-down'></span></a>";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $total = array();
            $iCtr = 0;
            //arrPrint($items);
            $data_total .= "<tbody>";
            foreach ($items as $cData) {
                $iCtr++;
                //arrPrintWebs($cData);
                $pid = $cData["pId"];
                $bgColor = isset($arrBgColor[$iCtr]) ? $arrBgColor[$iCtr] : "";

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
                            $btn_serial_number = "<button type='button' class='btn btn-success' data-toggle='tooltip' title='$sisa_title' style='ppadding: 3px 5px;width: 47px;' >$jml_serial_ok $sisa_serial_f</button>";
                        }
                        elseif ($qty_debet_nya == 0 && $jml_serial_ok > 0) {
                            $link_remove = $linkRemoveSerial . "/$pid";
                            $sisa_title .= "serial number bisa diremove";
                            $btn_serial_number = "<button type='button' id='btn-remove' class='btn btn-info' data-toggle='tooltip' title='$sisa_title' style='width: 47px;' onclick=\"confirm_alert_result_disabled('Membuang serial number','pastikan stok sudah kosong, karena seluruh data yang sudah dihapus tidak bisa dikembalikan ','$link_remove','lanjutkan Meremove',this.value);\" >$jml_serial_ok $sisa_serial_f</button>";
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
                            $data_total2 = "
                                <span class='fa fa-barcode' onclick=\"$historyClick_barcode\"></span>
                                ";
                        }
                        if (isset($pairedSerial_add[$cData['pId']]['link_qr'])) {
                            $historyClick_qr = $pairedSerial_add[$cData['pId']]['link_qr'];
                            $data_total3 = "
                                <span class='fa fa-qrcode' onclick=\"$historyClick_qr\"></span>
                                ";
                        }
                        /* ----------------------------------
                         * penampil button
                         * -----------------------------*/
                        if($cData["tipe_produk"]=="serial"){

                            $data_total .= "<div class=\"btn-group pull-right\" >";
                            $data_total .= $btn_serial_number;
                            if (isset($pairedSerial_add[$cData['pId']])) {
                                $data_total .= "<button type='button' class='btn btn-success' title='lihat detail serial'>$data_total1 </button>
                              <button type='button' class='btn btn-warning' title='cetak serial barcode'>  $data_total2</button>
                              <button type='button' class='btn btn-danger' title='cetak serial qr'>  $data_total3</button>";
                            }
                            $data_total .= "</div>";
                        }
                        else{
                            $data_total .= "-";
                        }
                    }
                    else {
                        $data_total .= "<a href='$link' data-toggle='tooltip' title='detail $cValue' target='_blank'>" . formatField($headerKey, $cValue) . "</a>";
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

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content" => $list_data,
            "profile_name" => $this->session->login['nama'],
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

            $data_total = "";
            $data_total .= "<style type='text/css'>
                table.dataTable thead th, table.dataTable thead td, 
                 table.dataTable tbody th, table.dataTable tbody td {
                    white-space: unset !important;
                }
                
            </style>";
            $data_total .= "<div class='row'>";
            $data_total .= "<div class='container-fluid'>";
            $data_total .= "<input type='text' style='width: 24%;' class='form-control pull-left' placeholder='masukan text untuk highlight' name='keyword' >";
            $data_total .= "</div>";
            $data_total .= "</div>";

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
        ));


        $p->setContent($contens);
        $p->render();

        break;

    case "viewMoveDetails":
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
        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "<div class='panel'>";
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
            "btn_tambahan" => isset($btn_tambahan) ? $btn_tambahan : "",
            "geturl" => isset($geturl) ? $geturl : "",
        ));


        $p->setContent($contens);
        $p->render();

        break;
}

