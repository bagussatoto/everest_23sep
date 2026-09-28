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

        // Container untuk Filter Date
        if (isset($dateSelected) && ($dateSelected == true)) {
            $list_data .= "<div style='flex-shrink: 0; background: #f8f9fa; padding: 6px 12px; border-radius: 4px; border: 1px solid #ddd;'>";
            $list_data .= "<div style='display: flex; align-items: center; gap: 8px;'>";
            $list_data .= "<span style='font-weight: 500; color: #555;'><i class='fa fa-calendar'></i> Pilih Periode:</span>";
            $list_data .= "<input type='month' class='form-control' style='width: 180px;' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage&date='+this.value;\" title='Pilih bulan dan tahun'>";
            $list_data .= "</div>";
            $list_data .= "</div>";
        }
        if (isset($selectTahun) && ($selectTahun == true)) {
//            print_r($defaultThnKiriman);
//            print_r($thisPage);
            $p->setOnClickTarget($thisPage);
//        $list_data .= "<div class='panel-body alert alert-info-dot'>";
//        $list_data .= "<div class='input-group'>";
//        $list_data .= "<span class='input-group-add-on' >select year </span>";
            $list_data .= $p->selectTahunPembantu($defaultThn, "thn", "", $defaultThnKiriman);
//        $list_data .= "</div class='input-group'>";
//        $list_data .= "</div class='panel-body'>";
        }

        // Container untuk Search (memakai sisa ruang) - DIPERBAIKI
        $list_data .= "<div style='flex-grow: 1; min-width: 300px; max-width: 500px;'>";
        $list_data .= "<div style='display: flex; width: 100%;'>";
        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='Cari produk...' onfocus='this.select()' onkeydown=\"if(event.key === 'Enter'){document.location.href='" . $thisPage . "&q='+this.value;}\" style='border-top-right-radius: 0; border-bottom-right-radius: 0;'>";
        $list_data .= "<button class='btn btn-outline-secondary' type='button' title='Hapus kata kunci' onclick=\"document.location.href='" . $thisPage . "&q=';\" style='border-radius: 0; border-left: none;'>";
        $list_data .= "<i class='fa fa-times'></i>";
        $list_data .= "</button>";
        $list_data .= "<button class='btn btn-primary' type='button' title='Cari' onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\" style='border-top-left-radius: 0; border-bottom-left-radius: 0;'>";
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

                    $data_total .= "<td title='$headerKey' class='$class_null'>";
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
                    $bg_dibatalakan = "background-color:#ff9c43;";
                    // $bg_dibatalakan = "color:#ff9c43;";
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

                                        $muValue = formatField_he_format($formatKey, $cValue);

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
                                $data_total .= "<td title='4'>" . formatField_he_format($h4Src, $cValue) . "</td>";
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
                                        $data_total .= formatField_he_format($headerKey, $cValue);
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

                                    $data_total .= "<td data-jenis_master='$_info_jenis_master' data-cValue='$cValue' data-modul_path='$_info_modul_path' data-headerKey='$headerKey' data-toggle='popover' data-content='$transaksi_id'>" . formatField_he_format($headerKey, $cValue, $itemData['jenis_master'], $itemData["modul_path"]) . "</td>";
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

                    $data_total .= "<td title='$headerKey' data-order='$cValue'>";

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

