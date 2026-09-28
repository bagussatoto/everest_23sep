<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 05/12/18
 * Time: 21:38
 */
//cekHere($mode);
switch ($mode) {
    default:
        mati_disini("mode belum dibuat [$mode] @" . __LINE__ . __FILE__);
        break;

    case "view":
        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
        //        arrPrint($arrayHistoryLabels);
        // if (strlen($errMsg) > 0) {
        //     $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        // }
        // else {
        //     $error = "";
        // }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        // cekHijau();
        //         arrPrintWebs($produks);
        //         arrPrintWebs($stokNow);
        // matiHere();
        // arrPrint($navigasi);
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        // $content_nav .= "<div class='form-group'>
        //                             <label>index: </label>
        //                             <input name='indeks' id='indeks' class='form-control' type='number' value='$indeks' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\" min='100'>
        //                         </div>";
        // $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                             <label>buffer: </label>
        //                             <input name='buffer' id='buffer' class='form-control' type='number' value='$buffer' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\">
        //                         </div>";
        // $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                             <label>periode data penjualan: </label>
        //                             <input name='periode' id='periode' class='form-control' type='number' value='$periode' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\">
        //                         </div>";
        foreach ($navigasi as $keyNav => $valNav) {
            $labelNav = $navigasiAttr[$keyNav]["label"];
            $minimal = $navigasiAttr[$keyNav]["minimal"];

            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
                                </div>";
        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";

        //region onprogress
        //     cekHere($content_nav);
        // matiHere();
        if (sizeof($produks) > 0) {
            // if (sizeof($produks) > 0) {
            //     $header_prog = array();
            $heads = array(
                "no",
                "pid",
                "code",
                "name",
                //------------
                "buffer",
                "index",
                "available stock",
                "sales",
                "return",
                "netto",
                // "outstanding PO",
                "monthly avg",
                "index lead time",
                "ideal stock",
                "order qty",
            );
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-0 text-uppercase'>";
            foreach ($heads as $key => $label) {

                $tblHeads .= "<th class='text-center'>$label</th>";
            }
            $tblHeads .= "</tr>";
            $tblHeads .= "</thead>";
            //     $this->table->set_heading($header_prog);
            // }
            $tblBodies = "";
            $tblBodies .= "<tbody>";
            $no = 0;
            foreach ($produks as $key => $val) {
                $no++;
                $id = $val->id;
                $limit = $val->limit;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $kode = $val->kode;
                $bufferx = $limit > 0 ? $limit : $buffer;
                $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
                $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;
                // $isi[] = array('data' => "$value ", 'class' => 'text-left');
                //
                $link_buffer = base_url() . "Bi/updateProdukLimit/$id";
                $link_indeks = base_url() . "Bi/updateProdukIndeks/$id";
                $link_leadTime = base_url() . "Bi/updateProdukLeadTime/$id";
                $link_katalog = base_url() . "Katalog/viewProduk?q=$kode";
                $strIndex = "<input type='number' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
                $strBuffer = "<input type='number' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$bufferx' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";

                $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
                $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
                $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
                $stok_net = $stok_out - $stok_in;

                $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' target='_blank'>$stok_now</a>";
                $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
                $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

                $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;

                // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
                $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
                $newPox = $newPo > 0 ? $newPo : 0;

                $newPo_f = formatField("stok", $newPox);
                $ideal_stok_f = formatField("stok", $ideal_stok);

                if ($stok_now < $newPo) {
                    $bg_color = "text-red";
                }
                elseif ($stok_now == $newPo) {
                    $bg_color = "text-yellow";
                }
                elseif ($stok_now > $newPo) {
                    $bg_color = "text-green";
                }
                else {
                    $bg_color = "";
                }
                // $this->table->add_row($isi);
                $tblBodies .= "<tr class='$bg_color'>";
                $tblBodies .= "<td class='text-right'>$no</td>";
                $tblBodies .= "<td class='text-center'>" . $val->id . "</td>";
                $tblBodies .= "<td>$kode</td>";
                $tblBodies .= "<td>" . $val->nama . "</td>";
                $tblBodies .= "<td class='text-right'>$strBuffer</td>";
                $tblBodies .= "<td class='text-right'>$strIndex</td>";
                $tblBodies .= "<td class='text-right'>$stok_now_l</td>";
                // ----------------------
                $tblBodies .= "<td class='text-right'>$stok_out</td>";
                $tblBodies .= "<td class='text-right'>$stok_in</td>";
                $tblBodies .= "<td class='text-right'>$stok_net</td>";
                // ----------------------
                $tblBodies .= "<td class='text-right'>$avg_f</td>";
                $tblBodies .= "<td class='text-right'>$strLeadTime</td>";
                $tblBodies .= "<td class='text-right'>$ideal_stok_f</td>";
                $tblBodies .= "<td class='text-right'>$newPo_f</td>";
                $tblBodies .= "</tr>";
            }
            $tblBodies .= "</tbody>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data' => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class' => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        // $strDataPropose = $this->table->generate();
        $strDataPropose = "<table class='table table-hover table-condensed' id='bi_table'>";
        $strDataPropose .= $tblHeads;
        $strDataPropose .= $tblBodies;
        $strDataPropose .= "</table>";
        $strDataPropose .= "<div id='update_buffer'></div>";
        $strDataPropose .= "<script>\n
                            $(document).ready(function() {
                                var bi_table = $('#bi_table').DataTable({
                                    order: [[ 9, 'desc' ]],
                                    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                    pageLength: -1,
                                    stateSave: true,
                                    responsive: true,
                                    paging: false,
                                    buttons: [
                                        {extend: 'print', footer: true },
                                        {extend: 'excel', text: 'Excel',
                                            exportOptions: {
                                                modifier: {
                                                    page: 'current'
                                                }
                                            }
                                        }
                                    ],
                                });

                                new $.fn.dataTable.FixedHeader( bi_table );

                            });
                            </script>";
        //endregion

        //    arrprint($arrayHistory);
        //    die();

        // arrprint($arrayHistory);

        if (sizeof($produks) > 0) {

            // $propDisplay = "block";
            $propDisplay = "none";
        }
        else {

            $propDisplay = "none";
        }
        //cekHere($strEditLink);
        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav" => $content_nav,
            "content" => $strDataPropose,

            "stop_time" => "",
        ));
        //endregion

        $p->render();

        break;
    case "viewMonthly_1":
        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
        //        arrPrint($arrayHistoryLabels);
        // if (strlen($errMsg) > 0) {
        //     $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        // }
        // else {
        //     $error = "";
        // }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        // cekHijau();
        //         arrPrintWebs($produks);
        //         arrPrintWebs($stokNow);
        // matiHere();
        // arrPrint($navigasi);
        //region navigasi atribute
        $arrBiAttr["indeks"] = array(
            "label" => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label" => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label" => "omset (M)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label" => "stock sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label" => "buffer sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label" => "moq sett (M)",
            "minimal" => "1",
        );
        //endregion
        $navigasiAttr = $arrBiAttr;
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";
        $content_nav .= "<form method='get'>";
        // $content_nav .= "<div class='form-group'>
        //                             <label>index: </label>
        //                             <input name='indeks' id='indeks' class='form-control' type='number' value='$indeks' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\" min='100'>
        //                         </div>";
        // $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                             <label>buffer: </label>
        //                             <input name='buffer' id='buffer' class='form-control' type='number' value='$buffer' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\">
        //                         </div>";
        // $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                             <label>periode data penjualan: </label>
        //                             <input name='periode' id='periode' class='form-control' type='number' value='$periode' onclick=\"this.select();\" onkeyup=\"".sendToSession($hipo_target,'wadah')."\">
        //                         </div>";
        foreach ($navigasi as $keyNav => $valNav) {
            $labelNav = $navigasiAttr[$keyNav]["label"];
            $minimal = $navigasiAttr[$keyNav]["minimal"];
            $valNavX = isset($_GET[$keyNav]) ? $_GET[$keyNav] : $valNav;
            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' style='width: 50px' type='number' value='$valNavX' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
                                </div>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";
        $content_nav .= form_button("cek", "show graph", "class='btn btn-info pull-right' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");
        $content_nav .= "</form>";
        // $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
        //                                 onclick=\"window.location.reload();\">
        //                             <i class='fa fa-refresh'></i></button>";


        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";

        //region onprogress
        //     cekHere($content_nav);
        // matiHere();
        if (sizeof($produks) > 0) {
            // if (sizeof($produks) > 0) {
            //     $header_prog = array();
            $bulans = array();
            foreach ($penjualanBulanan as $thn => $datas_2) {
                foreach ($datas_2 as $bln => $datas_3) {
                    $bulans[] = "$thn<br>$bln";
                    $bulanDatas[] = $datas_3;
                }
            }
            $jmlBulan = sizeof($bulans);
            // arrPrint($bulans);
            $heads_1 = array(
                "no",
                "pid",
                "code",
                "name",
            );
            $heads_2 = array(
                //------------
                "omset <p class='meta no-margin'> $jmlBulan m</p>",
                "monthly avg",
                "<span class='text-blue'>month set</span>",
                "<span class='text-blue'>buffer</span>",
                "index",
                "available stock",
                // "sales",
                // "return",
                // "netto",

                "<span class='text-green'>month set</span>",
                "<span class='text-green'>ideal stock</span>",
                "order qty",
            );

            // $head_styles[5] = "style='border-left:1px solid red;border-top:1px solid red;'";
            // $head_styles[6] = "style='border-right:1px solid red;border-top:1px solid red;'";
            // $head_styles[9] = "style='border-left:1px solid red;border-top:1px solid red;'";
            // $head_styles[10] = "style='border-right:1px solid red;border-top:1px solid red;'";

            $heads = array_merge($heads_1, $bulans, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($heads as $key => $label) {
                // $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $tblHeads .= "<th class='text-center' $attr>$label</th>";
            }
            $tblHeads .= "</tr>";
            $tblHeads .= "</thead>";
            //     $this->table->set_heading($header_prog);
            // }
            $tblBodies = "";
            $tblBodies .= "<tbody>";
            $no = 0;
            foreach ($produks as $key => $val) {
                $no++;
                $id = $val->id;
                $limit = $val->limit;
                $limit_time = $val->limit_time;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $kode = $val->kode;

                // $isi[] = array('data' => "$value ", 'class' => 'text-left');
                //
                $link_buffer = base_url() . "Bi/updateProdukLimit/$id";
                $link_indeks = base_url() . "Bi/updateProdukIndeks/$id";
                $link_leadTime = base_url() . "Bi/updateProdukLeadTime/$id";
                $link_katalog = base_url() . "Katalog/viewProduk?q=$kode";


                $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
                //     $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
                //     $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
                //     $stok_net = $stok_out - $stok_in;
                //
                $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' target='_blank'>$stok_now</a>";
                //     $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
                //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
                //
                //     $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;
                //
                //     // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
                //     $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
                //     $newPox = $newPo > 0 ? $newPo : 0;
                //
                //     $newPo_f = formatField("stok", $newPox);
                //     $ideal_stok_f = formatField("stok", $ideal_stok);
                //
                $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
                $bgDb_b = $limit > 0 ? "bg-danger" : "";
                $bgDb_lt = $lead_time > 0 ? "bg-danger" : "";
                $bgDb_i = $indeks_db > 0 ? "bg-danger" : "";
                $bg_color = "";
                // if ($stok_now < $newPo) {
                //     $bg_color = "text-red";
                // }
                // elseif ($stok_now == $newPo) {
                //     $bg_color = "text-yellow";
                // }
                // elseif ($stok_now > $newPo) {
                //     $bg_color = "text-green";
                // }
                // else {
                //     $bg_color = "";
                // }
                // $this->table->add_row($isi);
                $tblBodies .= "<tr class='$bg_color'>";
                $tblBodies .= "<td class='text-right'>$no</td>";
                $tblBodies .= "<td class='text-center'>" . $val->id . "</td>";
                $tblBodies .= "<td>$kode</td>";
                $tblBodies .= "<td>" . $val->nama . "</td>";

                foreach ($bulanDatas as $bulanData) {
                    $stok_out = isset($bulanData[$id]) ? $bulanData[$id]['unit_af'] : 0;
                    $tblBodies .= "<td class='text-right bg-yellow-light'>$stok_out</td>";

                    if (!isset($jml{$id})) {

                        $jml[$id] = 0;
                    }
                    $jml[$id] += $stok_out;
                }
                // arrPrint();

                //     // ----------------------
                //     $tblBodies .= "<td class='text-right'>$stok_out</td>";
                //     $tblBodies .= "<td class='text-right'>$stok_in</td>";
                //     $tblBodies .= "<td class='text-right'>$stok_net</td>";
                //     // ----------------------
                $stok_out = isset($jml[$id]) ? $jml[$id] : 0;
                $avg = $stok_out > 0 ? ($stok_out / $jmlBulan) : 0;
                $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

                $limitTimex = $limit_time > 0 ? $limit_time : $leadTime;
                $bufferx = $limit > 0 ? $limit : ($avg * $limitTimex);
                $bufferx_f = ceil($bufferx);
                $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
                $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;

                $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;

                $newPo = ($ideal_stok) - ($stok_now);
                $newPox = $newPo > 0 ? $newPo : 0;

                $newPo_f = ceil($newPox);
                $ideal_stok_f = ceil($ideal_stok);

                $strIndex = "<input type='number' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
                $strBuffer = "<input type='number' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";

                $tblBodies .= "<td class='text-right bg-warning text-bold'>$stok_out</td>";
                $tblBodies .= "<td class='text-right'>$avg_f</td>";
                $tblBodies .= "<td class='text-right'>$strBufferTime</td>";
                $tblBodies .= "<td class='text-right'>$strBuffer</td>";
                $tblBodies .= "<td class='text-right'>$strIndex</td>";
                $tblBodies .= "<td class='text-right'>$stok_now_l</td>";

                $tblBodies .= "<td class='text-right'>$strLeadTime</td>";
                $tblBodies .= "<td class='text-right font-size-1-2'>$ideal_stok_f</td>";
                $tblBodies .= "<td class='text-right font-size-1-2'>$newPo_f</td>";
                $tblBodies .= "</tr>";
            }
            $tblBodies .= "</tbody>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data' => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class' => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        // $strDataPropose = $this->table->generate();
        $strDataPropose = "<table class='table table-hover table-condensed ' id='example'>";
        $strDataPropose .= $tblHeads;
        $strDataPropose .= $tblBodies;
        $strDataPropose .= "</table>";
        $strDataPropose .= "<div id='update_buffer'></div>";
        $strDataPropose .= "<script>
                            $(document).ready(function() {

                                $('#example').DataTable({
                                    order: [[ 9, 'desc' ]],
                                    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                    pageLength: -1,
                                    stateSave: true,
                                    buttons: [
                                        {extend: 'print', 
                                         footer: true },
                                        {extend: 'excel',
                                            text: 'Excel',
                                            exportOptions: {
                                                modifier: {
                                                    page: 'current'
                                                }
                                            }
                                        }
                                    ],
                                });
                            });
                            </script>";
        //endregion

        //    arrprint($arrayHistory);
        //    die();

        // arrprint($arrayHistory);

        if (sizeof($produks) > 0) {

            // $propDisplay = "block";
            $propDisplay = "none";
        }
        else {

            $propDisplay = "none";
        }
        //cekHere($strEditLink);
        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav" => $content_nav,
            "content" => $strDataPropose,

            "stop_time" => "",
        ));
        //endregion

        $p->render();

        break;
    case "viewMonthly":
        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
        //        arrPrint($arrayHistoryLabels);
        // if (strlen($errMsg) > 0) {
        //     $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        // }
        // else {
        //     $error = "";
        // }
        // arrPrint($_SESSION["Bi"]);
        $limitTime = $_SESSION['Bi']['limitTime'];
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        // cekHijau();
        //         arrPrintWebs($produks);
        //         arrPrintWebs($stokNow);
        // matiHere();
        // arrPrint($navigasi);
        $hipo_target = base_url() . "Bi/createSession";
        //region navigasi atribute
        $arrBiAttr["indeks"] = array(
            "label"   => "index (%)",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label" => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label" => "omset (M)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label" => "stock sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label" => "buffer sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label" => "moq sett (M)",
            "minimal" => "1",
        );
        //endregion
        $navigasiAttr = $arrBiAttr;
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";
        $content_nav .= "<form method='get'>";
        foreach ($navigasi as $keyNav => $valNav) {
            $labelNav = $navigasiAttr[$keyNav]["label"];
            $minimal = $navigasiAttr[$keyNav]["minimal"];
            $valNavX = isset($_GET[$keyNav]) ? $_GET[$keyNav] : $valNav;
            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' style='width: 50px;' type='number' value='$valNavX' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
                                </div>";
        }
        // foreach ($navigasi as $keyNav => $valNav) {
        //     $labelNav = $navigasiAttr[$keyNav]["label"];
        //     $minimal = $navigasiAttr[$keyNav]["minimal"];
        //     $valNavX = isset($_GET[$keyNav]) ? $_GET[$keyNav] : $valNav;
        //     $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
        //                             <label>$labelNav: </label>
        //                             <input name='$keyNav' id='$keyNav' class='form-control' style='width: 50px' type='number' value='$valNavX' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
        //                         </div>";
        //
        //     $$keyNav = $valNavX;
        //     // cekOrange("$keyNav $valNavX");
        // }
        if (isset($_GET['limit'])) {
            $content_nav .= "<input type='hidden' name='qLimit' id='qLimit' value='$_GET[limit]'>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";
        // $content_nav .= " <a href='" . base_url() . "Bi/formSetting' data-toggle='modal' data-target='#myModal' class='btn btn-info'><i class='fa fa-android'></i></a>";

        $content_nav .= form_button("cek", "show graph", "class='btn btn-info pull-right' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");
        $content_nav .= "</form>";
        // $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
        //                                 onclick=\"window.location.reload();\">
        //                             <i class='fa fa-refresh'></i></button>";

        // cekLime("$limitTime $moqTime");
        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";

        //region onprogress
        //     cekHere($content_nav);
        // matiHere();
        if (sizeof($produks) > 0) {
            // if (sizeof($produks) > 0) {
            //     $header_prog = array();
            $bulans = array();
            $bulanDatas = array();
            foreach ($penjualanBulanan as $thn => $datas_2) {
                foreach ($datas_2 as $bln => $datas_3) {
                    $bulans[] = "$thn<br>$bln";
                    $bulanDatas[] = $datas_3;
                }
            }
            $jmlBulan = sizeof($bulans);
            // arrPrint($bulans);
            $heads_1 = array(
                "no",
                "pid",
                "code",
                "name",
            );
            $heads_2 = array(
                //------------
                "omset <p class='meta no-margin'> $jmlBulan m</p>",
                "monthly avg",
                "<span class='text-blue'>buffer</span>",
                // "<span class='text-blue'>month set</span>",
                // "<span class='text-blue'>buffer</span>",
                // "<span class='text-yellow'>month set</span>",
                // "<span class='text-yellow'>moq</span>",
                "unit MOQ",
                "index (%)",
                "available stock",
                // "sales",
                // "return",
                // "netto",

                // "<span class='text-green'>month set</span>",
                // "<span class='text-green'>ideal stock</span>",
                "<span class='text-green'>ideal stok</span>",
                "order&nbsp;qty <p class='meta no-margin'>unit satuan</p>",
                "order&nbsp;qty <p class='meta no-margin'>unit moq</p>",
            );
            // $limitTimex = $buffer;
            // $head_styles[5] = "style='border-left:1px solid red;border-top:1px solid red;'";
            // $head_styles[6] = "style='border-right:1px solid red;border-top:1px solid red;'";
            // $head_styles[9] = "style='border-left:1px solid red;border-top:1px solid red;'";
            // $head_styles[10] = "style='border-right:1px solid red;border-top:1px solid red;'";
            $rowspan_keys = array(
                0  => 2,
                1  => 2,
                2  => 2,
                3  => 2,
                4  => 2,
                5  => 2,
                7 => 2,
                8 => 2,
                9 => 2,
                11 => 2,
                12 => 2,
            );
            $colspan_keys = array(
                6  => 2,
                // 7  => 2,
                10  => 2,
                // 11  => 2,
            );
            $attr = isset($attr) ? $attr : "";
            // $heads = array_merge($heads_1, $bulans, $heads_2);
            $heads = array_merge($heads_1, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($heads as $key => $label) {
                // $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $colspan = array_key_exists($key, $rowspan_keys) ? "rowspan='" . $rowspan_keys[$key] . "'" : "";
                $rowspan = array_key_exists($key, $colspan_keys) ? "colspan='" . $colspan_keys[$key] . "'" : "";
                $tblHeads .= "<th $colspan $rowspan class='text-center' $attr>$label</th>";
            }
            $tblHeads .= "</tr>";

            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            $tblHeads .= "<th class='text-center text-blue'>month</th>";
            $tblHeads .= "<th class='text-center text-blue'>unit</th>";
            $tblHeads .= "<th class='text-center text-green'>month</th>";
            $tblHeads .= "<th class='text-center text-green'>unit</th>";
            // $tblHeads .= "<th class='text-center'>month</th>";
            // $tblHeads .= "<th class='text-center'>unit</th>";
            $tblHeads .= "</tr>";

            $tblHeads .= "</thead>";
            //     $this->table->set_heading($header_prog);
            // }

            $tblBodies = "";
            $tblBodies .= "<tbody>";
            $no = 0;
            $xi = 0;
            $xbt = 2000;
            $xb = 4000;
            $xlt = 6000;
            $xmt = 8000;
            $xm = 10000;
            $bgDb_b = "";
            $bgDb_bt = "";
            $bgDb_lt = "";
            $bgDb_l = "";
            $bgDb_mt = "";
            $bgDb_m = "";
            foreach ($produks as $key => $val) {
                //region incerement
                $no++;
                $xi++;
                $xb++;
                $xbt++;
                $xlt++;
                $xmt++;
                $xm++;
                //endregion
                $id = $val->id;
                $limit = $val->limit;
                $limit_time = $val->limit_time;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $moq = $val->moq;
                $moq_time = $val->moq_time;
                $kode = $val->kode;

                // $isi[] = array('data' => "$value ", 'class' => 'text-left');
                //
                $link_buffer = base_url() . "Bi/updateProdukLimit/$id";
                $link_bufferTime = base_url() . "Bi/updateProdukLimitTime/$id";
                $link_indeks = base_url() . "Bi/updateProdukIndeks/$id";
                $link_leadTime = base_url() . "Bi/updateProdukLeadTime/$id";
                $link_moqTime = base_url() . "Bi/updateProdukMoqTime/$id";
                $link_moq = base_url() . "Bi/updateProdukMoq/$id";
                $link_katalog = base_url() . "Katalog/viewProduk?q=$kode";


                $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
                //     $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
                //     $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
                //     $stok_net = $stok_out - $stok_in;
                //
                $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' target='_blank'>$stok_now</a>";
                //     $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
                //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
                //
                //     $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;
                //
                //     // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
                //     $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
                //     $newPox = $newPo > 0 ? $newPo : 0;
                //
                //     $newPo_f = formatField("stok", $newPox);
                //     $ideal_stok_f = formatField("stok", $ideal_stok);
                //
                $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
                $bgDb_b = $limit > 0 ? "bg-danger" : "";
                $bgDb_lt = $lead_time > 0 ? "bg-danger" : "";
                $bgDb_i = $indeks_db > 0 ? "bg-danger" : "";
                $bgDb_mt = $moq_time > 0 ? "bg-danger" : "";
                $bgDb_m = $moq > 0 ? "bg-danger" : "";
                $bg_color = "";
                // if ($stok_now < $newPo) {
                //     $bg_color = "text-red";
                // }
                // elseif ($stok_now == $newPo) {
                //     $bg_color = "text-yellow";
                // }
                // elseif ($stok_now > $newPo) {
                //     $bg_color = "text-green";
                // }
                // else {
                //     $bg_color = "";
                // }
                // $this->table->add_row($isi);
                $tblBodies .= "<tr class='$bg_color'>";
                $tblBodies .= "<td class='text-right'>$no</td>";
                $tblBodies .= "<td class='text-center'>" . $val->id . "</td>";
                $tblBodies .= "<td>$kode</td>";
                $tblBodies .= "<td>" . $val->nama . "</td>";

                foreach ($bulanDatas as $bulanData) {
                    $stok_out = isset($bulanData[$id]) ? $bulanData[$id]['unit_af'] : 0;
                    // $tblBodies .= "<td class='text-right bg-yellow-light'>$stok_out</td>";

                    if (!isset($jml{$id})) {

                        $jml[$id] = 0;
                    }
                    $jml[$id] += $stok_out;
                }
                // arrPrint();

                //     // ----------------------
                //     $tblBodies .= "<td class='text-right'>$stok_out</td>";
                //     $tblBodies .= "<td class='text-right'>$stok_in</td>";
                //     $tblBodies .= "<td class='text-right'>$stok_net</td>";
                //     // ----------------------
                $stok_out = isset($jml[$id]) ? $jml[$id] : 0;
                $avg = $stok_out > 0 ? ($stok_out / $jmlBulan) : 0;
                $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

                $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
                $limitTimex = $limit_time > 0 ? $limit_time : $limitTime;
                $moqTimex = $moq_time > 0 ? $moq_time : $moqTime;
                $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;

                $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
                $bufferx = $limit > 0 ? $limit : ($avg * $limitTimex);
                $bufferx_f = number_format($bufferx, 2);
                $moqx_f = number_format($moqx, 2);
                // cekHijau("$id:: $bufferx = $limit > 0 ? $limit : ($avg * $limitTimex)");
                $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;

                $newPo = ($ideal_stok) - ($stok_now);
                $newPox = $newPo > 0 ? $newPo : 0;

                $newPo_f = ceil($newPox);
                $ideal_stok_f = ceil($ideal_stok);

                $newPoMoq = ($moqx > 0) ? (ceil($newPox / $moqx) * $moqx) : 0;

                $strIndex = "<input type='number' tabindex='$xi' name='indeks' id='indeks_$id' class='text-center no-padding no-margin bborder-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
                $strBuffer      = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin bborder-none $bgDb_b' style='width: 50px' value='$bufferx' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";
                $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
                $strMoq         = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin bborder-none $bgDb_m' style='width: 50px' value='$moqx' onclick=\"this.select();\" onblur=\"getData('$link_moq?v='+this.value,'update_buffer');\">";

                $tblBodies .= "<td data-order='$stok_out' class='text-right bg-warning text-bold'>$stok_out</td>";
                $tblBodies .= "<td data-order='$avg' class='text-right'>$avg_f</td>";
                $tblBodies .= "<td data-order='$limitTimex' class='text-right bg-info'>$strBufferTime</td>";
                $tblBodies .= "<td data-order='$bufferx' class='text-right bg-info'>$strBuffer</td>";
                $tblBodies .= "<td data-order='$moqx' class='text-center'>$strMoq</td>";
                $tblBodies .= "<td data-order='$indeksx' class='text-right'>$strIndex</td>";
                $tblBodies .= "<td data-order='$stok_now' class='text-right'>$stok_now_l</td>";
                $tblBodies .= "<td data-order='$leadTimex' class='text-right bg-success'>$strLeadTime</td>";
                $tblBodies .= "<td data-order='$ideal_stok' class='text-right bg-success'>$ideal_stok_f</td>";
                $tblBodies .= "<td data-order='$newPo' class='text-right bg-danger font-size-1-2'>$newPo_f</td>";
                $tblBodies .= "<td data-order='$newPoMoq' class='text-right text-bold'>$newPoMoq</td>";

                $tblBodies .= "<td class='text-right'>$avg_f</td>";
                $tblBodies .= "<td class='text-right bg-info'>$strBufferTime</td>";
                $tblBodies .= "<td class='text-right bg-info'>$strBuffer</td>";

                // $tblBodies .= "<td class='text-right'>$strMoqTime</td>";
                $tblBodies .= "<td class='text-center'>$strMoq</td>";

                $tblBodies .= "<td class='text-right'>$strIndex</td>";

                $tblBodies .= "<td class='text-right'>$stok_now_l</td>";

                $tblBodies .= "<td class='text-right bg-success'>$strLeadTime</td>";
                $tblBodies .= "<td class='text-right bg-success'>$ideal_stok_f</td>";
                $tblBodies .= "<td class='text-right bg-danger font-size-1-2'>$newPo_f</td>";
                $tblBodies .= "<td class='text-right text-bold'>$newPoMoq</td>";
                $tblBodies .= "</tr>";
            }
            $tblBodies .= "</tbody>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data' => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class' => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        // $strDataPropose = $this->table->generate();
        $strDataPropose = "<table id='bi_table' class='table table-hover table-condensed'>";
        $strDataPropose .= $tblHeads;
        $strDataPropose .= $tblBodies;
        $strDataPropose .= "</table>";
        $strDataPropose .= "<div id='update_buffer'></div>";
        $strDataPropose .= "<script>
                            $(document).ready(function() {

                                var bi_table = $('#bi_table').DataTable({
                                    order: [[ 9, 'desc' ]],
                                    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                    pageLength: -1,
                                    stateSave: true,
                                    responsive: true,
                                    paging: false,
                                    buttons: [
                                        {extend: 'print', 
                                         footer: true },
                                        {extend: 'excel',
                                            text: 'Excel',
                                            exportOptions: {
                                                modifier: {
                                                    page: 'current'
                                                }
                                            }
                                        }
                                    ],

                                });

                                new $.fn.dataTable.FixedHeader( bi_table );
                                top.$('.box-body').floatingScroll();
                                $('.sidebar-toggle').on( 'click', function () {
                                    bi_table
                                        .draw();
                                    setTimeout( function(){
                                        $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
                                    }, 2500);
                                } );

                                $( '.box-body' ).scroll(function() {
                                    setTimeout( function(){
                                        $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
                                    }, 200);
                                });

                            });
                            </script>";
        //endregion

        //    arrprint($arrayHistory);
        //    die();

        // arrprint($arrayHistory);
        //region legenda koloms diatus dari heBi
        $content_note = "";
        foreach ($notes as $legenda => $lNote) {

            $content_note .= "<p class='meta no-margin'>";
            $content_note .= "<span class='text-primary text-uppercase'>$legenda</span> : ";
            $content_note .= "$lNote";
            $content_note .= "</p> ";
        }
        $content_note .= "<p class='meta no-margin'>";
        $content_note .= "<span class='label bg-danger text-black'>&nbsp;##&nbsp;</span> : ";
        $content_note .= "fixed applied setting";
        $content_note .= "</p> ";
        // $content_note .= "<p class='meta no-margin'>";
        // $content_note .= "<span class='text-primary text-uppercase'>month set</span> : ";
        // $content_note .= "setting periode pada tiap kolom sesuai warna header text";
        // $content_note .= "</p> ";

        //endregion
        if (sizeof($produks) > 0) {

            // $propDisplay = "block";
            $propDisplay = "none";
        }
        else {

            $propDisplay = "none";
        }
        //cekHere($strEditLink);
        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "add_link" => $content_note,
            "content_nav" => $content_nav,
            "content" => $strDataPropose,

            "stop_time" => "",
        ));
        //endregion

        $p->render();

        break;
    case "viewGraph_old":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        foreach ($navigasi as $keyNav => $valNav) {
            $labelNav = $navigasiAttr[$keyNav]["label"];
            $minimal = $navigasiAttr[$keyNav]["minimal"];

            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
                                </div>";
        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";
        //endregion

        /* ==============================
         * URUSAN CHART
         * ----------------------*/

        $kolomX = array(
            "nilai_af" => "netto",
        );
        $yKoloms = array(
            "nilai_af" => "total penjualan",
        );

        $varDatas = array();
        $varBiaya = array();
        $qtDatas = array();
        $qtBiaya = array();
        $varYlabels = array();
        $varYkeys = array();


        if (sizeof($penjualanBulanan) > 0) {
            /* =================================
             * penjualan bulanan
             * -------------------*/
            $hChart = array();
            foreach ($penjualanBulanan as $th => $datas_1) {
                foreach ($datas_1 as $bl => $datas_2) {

                    $totals = round($penjualanFireBulanan[$th][$bl]) + round($penjualanNonFireBulanan[$th][$bl]);

                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;

                    $hChart['netto'][] = ($totals - $hpp - $biaya);
                    $specs['netto'] = ($totals - $hpp - $biaya);

                    $hChart['bruto'][] = ($totals - $hpp);
                    $specs['bruto'] = ($totals - $hpp);

                    $hChart['hpp'][] = $hpp;
                    $hChart['biaya'][] = $biaya;

                    $specs['hpp'] = $hpp;
                    $specs['biaya'] = $biaya;

//                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
//                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
                    $hChart['nilai_af'][] = round($datas_2['nilai_af']);
                    $specs['nilai_af'] = round($datas_2['nilai_af']);

                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                    $thBl = "$th " . $namaBl;

                    $hChart['bulan'][] = $thBl;
                    $specs['bulan'] = $thBl;

                    $varYkeys = array();
                    $varYlabels = array();

                    //bar
                    $varYkeys[] = 'netto';
                    $varYlabels[] = 'laba bersih';
                    $varYkeys[] = 'bruto';
                    $varYlabels[] = 'laba kotor';

                    //line
                    $varYkeys[] = 'nilai_af';
                    $varYlabels[] = 'total penjualan';
//                    $varYkeys[] = 'fire';
//                    $varYlabels[] = 'fire';
//                    $varYkeys[] = 'nonFire';
//                    $varYlabels[] = 'nonFire';

                    $varYkeys[] = 'biaya';
                    $varYlabels[] = 'biaya';
                    $varYkeys[] = 'hpp';
                    $varYlabels[] = 'hpp';

                    $varAll['min'][] = min($specs);
                    $varAll['max'][] = max($specs);

                    $varDatas[] = $specs;
                }

//                foreach ($datas_1 as $bl => $datas_2) {
//
//                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
//
//                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
//                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
//
////                    $specs['totalBiaya'] = $biaya;
////                    $specs['totalHpp'] = $hpp;
//
//                    $specs['netto'] = ($totals-$hpp-$biaya);
//                    $specs['bruto'] = ($totals-$hpp);
//
//                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
//                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
//                    $specs['nilai_af'] = round($datas_2['nilai_af']);
//
//                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
//                    $thBl = "$th " . $namaBl;
//
//                    $specs['bulan'] = $thBl;
//
//                    $varYkeys = array();
//                    $varYlabels = array();
//
//
//                    $varYkeys[] = 'fire';
//                    $varYkeys[] = 'nonFire';
//                    $varYkeys[] = 'nilai_af';
//                    $varYkeys[] = 'bruto';
//                    $varYkeys[] = 'netto';
//
////                    $varYkeys[] = 'totalBiaya';
////                    $varYkeys[] = 'totalHpp';
//
//                    $varYlabels[] = 'fire';
//                    $varYlabels[] = 'nonFire';
//                    $varYlabels[] = 'total penjualan';
//                    $varYlabels[] = 'laba kotor';
//                    $varYlabels[] = 'laba bersih';
////                    $varYlabels[] = 'total BIAYA';
////                    $varYlabels[] = 'total HPP';
//
////                    foreach ($yKoloms as $yKolom => $yLabel) {
////                        $varYlabels[] = $yLabel;
////                        $varYkeys[] = $yKolom;
////                        $specs[$yKolom] = round($datas_2[$yKolom]);
////                    }
//
//                    $varAll['min'][] = min($specs);
//                    $varAll['max'][] = max($specs);
//
//                    $varDatas[] = $specs;
//                }

            }

            if (sizeof($hChart) > 0) {
                foreach ($hChart as $xLabel => $xData) {
                    if ("bulan" == $xLabel) {
                        $$xLabel = "['" . implode("','", $xData) . "']";
                    }
                    else {
                        $$xLabel = "[" . implode(",", $xData) . "]";
                    }
                }
            }


            $ymin = min($varAll['min']);
            $ymin = round($ymin - (($ymin * 10) / 100));
            $ymax = max($varAll['max']);
            $ymax = round($ymax - (($ymax * 10) / 100));

            // arrPrint($penjualanQuarter);
//             arrPrint( $ymax );
//             arrPrint( $ymin );
            // arrPrint($penjualanQuarter);

            /* ----------------------
             * penjual rata2 per triwulan
             * --------------------------------------------------dimatikan dulu bosss
             * ------------------------*/
            $qtDatas = array();
            // foreach ($penjualanQuarter as $th => $datas_1a) {
            //
            //     $urut = 0;
            //     foreach ($datas_1a as $qt => $datas_3a) {
            //         $bl = $dataQuarter[$th][$qt]['bl'];
            //
            //         $qtSpecs['quarter'] = (string)"#$qt/$th";
            //         $urut++;
            //         $qtSpecs['qt'] = (string)$datas_3a;
            //         $qtDatas[] = $qtSpecs;
            //     }
            // }
        }

//        sort($varYkeys);
        $jsonQtDatas = json_encode($qtDatas);
        $jsonDatas = json_encode($varDatas);
        $jsonYkeys = json_encode($varYkeys);
        $jsonYlabel = json_encode($varYlabels);
        // arrPrint($namaBulan);
        // arrPrint($jsonYkeys);
        // arrPrint($jsonDatas);
        // arrPrint($varDatas);

//         arrPrint($varYkeys);
//         arrPrint($varBiaya);
        // arrPrint($varYlabels);
        // arrPrint($qtDatas);
        // arrPrint($jsonQtDatas);
        // matiHere();

        // region penjuala vs pembelian
        $varDatas_2 = array();
        if (sizeof($pembelianBulanan) > 0) {
            /* =================================
             * penjualan bulanan
             * -------------------*/
            // arrPrintWebs($pembelianBulanan);
            // arrPrintWebs($varDatas);
            // $varDatas = array();
            $varYkeys = array();
            $varYlabels = array();
            $indek = -1;
            $baseData = "penjualan";
            if ($baseData == "pembelian") {
                // -----------BASE DATA PEMBELIAN--------------
                foreach ($pembelianBulanan as $th => $datas_1) {
                    foreach ($datas_1 as $bl => $datas_2) {
                        $indek++;
                        // cekHijau("$datas_2");
                        $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                        $thBl = "$th " . $namaBl;
                        // $thBl = $namaBl;
                        // cekHere($thBl);
                        $specs_2['bulan'] = $thBl;
                        $specs_2['pembelian'] = $datas_2;
                        $specs_2['penjualan'] = $varDatas[$indek]["nilai_af"];

                        // arrPrintWebs($specs_2);
                        $varDatas_2[] = $specs_2;
                    }
                }
            }
            else {
                // -----------BASE DATA PENJUALAN--------------
                foreach ($penjualanBulanan as $th => $datas_1) {
                    foreach ($datas_1 as $bl => $datas_2) {
                        $indek++;
                        // arrPrint($datas_2);
                        $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                        $thBl = "$th " . $namaBl;
                        // $thBl = $namaBl;
                        // cekHere($thBl);
                        $specs_2['bulan'] = $thBl;
                        $specs_2['penjualan'] = $datas_2['nilai_af'];
                        $specs_2['pembelian'] = isset($pembelianBulanan[$th][$bl]) ? $pembelianBulanan[$th][$bl] : 0;

                        // arrPrintWebs($specs_2);
                        $varDatas_2[] = $specs_2;
                    }
                }
            }

            $varYkeys_2[] = 'pembelian';
            $varYkeys_2[] = 'penjualan';
            $varYlabels_2[] = 'pembelian';
            $varYlabels_2[] = 'penjualan';
            // arrPrint($varDatas_2);
        }

        $jsonDatas_2 = json_encode($varDatas_2);
        $jsonYkeys_2 = json_encode($varYkeys_2);
        $jsonYlabel_2 = json_encode($varYlabels_2);
        // endregion penjuala vs pembelian


        $strData = "";
        // $strData .= "xxx";
        // $strData .= "<div class='nav-tabs-custom'>";
        // $strData .= "<div class='col-md-12'>";

//        $strData .= "<div class='box box-solid box-success'>";
//        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Morris</h3></div>";
//        $strData .= "<div class='box-body'>";
//        $strData .= "<div class='dchart' id='bar-chart' style='pposition: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
//        $strData .= "</div>";
//        $strData .= "<div id='legend' class='bars-legend'></div>";
//        $strData .= "</div>"; // body
//        $strData .= "</div>"; // box end

        $strData .= "<div class='box box-solid box-success'>";
        $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'></p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end

//        arrPrint($varDatas);

        $strData .= "<script>
  
        Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    toggle: {
                        align: 'left',
                        y: 30,
                        x: 20,
                        height: 14,
                        theme: {
                            'stroke-width': 1,
                            stroke: 'silver',
                            r: 0
                        },
                        text: 'Chart Height',
                        menuItems: [{
                            text: '400px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 400)
                            }
                        }, {
                            text: '800px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 800)
                            }
                        }, {
                            text: '1200px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1200)
                            }
                        }, {
                            text: '1600px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1600)
                            }
                        }]
                    }
                }
            },
          chart: {
            type: \"xy\",
            height: 600
          },
          title: {
            text: 'GRAPH REPORTS',
            align: 'left'
          },
          subtitle: {
            text: \"<div class='text-bold'>laba bersih = penjualan - hpp - biaya</div><br><div class='text-bold'>laba kotor = penjualan - hpp</div><br><div class='text-bold'> </div>\",
            align: 'center'
          },
          xAxis: [{
            categories: $bulan,
            crosshair: true
          }],
          yAxis: [
            { // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            opposite: true
          },
          { // Secondary yAxis
//            type: 'linear',
//            max: $ymax,
//            min: $ymin,
            tickInterval: 5,
            gridLineWidth: 1,
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            },
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            }
          }],
          tooltip: {
            shared: true
          },
          plotOptions: {
                series: {
                    label: {
                        enabled: false,
                    }
                }
           },
          legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },
          series: [
          {
            name: 'penjualan',
            type: 'spline',
            color: 'black',
            yAxis: 1,
            data: $nilai_af,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'HPP',
            type: 'spline',
            color: 'blue',
            yAxis: 1,
            data: $hpp,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Biaya (tanpa HPP)',
            type: 'spline',
            color: 'orange',
            yAxis: 1,
            data: $biaya,
            tooltip: {
              valueSuffix: ' '
            }
          },
            {
            name: 'Laba Kotor',
            type: 'column',
            color: 'red',
            yAxis: 1,
            stacking: 'normal',
            data: $bruto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Laba Bersih',
            type: 'column',
            yAxis: 1,
            color: 'green',
            stacking: 'normal',
            data: $netto,
            tooltip: {
              valueSuffix: ' '
            }
          },],
          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  floating: false,
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  x: 0,
                  y: 0
                }
              }
            }]
          }
        });


$('.highcharts-credits').remove();

        </script>\n";

        $strData .= "<script>

  //region config chart 1

            var dataDb = $jsonDatas;
            var dataQt = $jsonQtDatas;   
            var yKey = $jsonYkeys;
            var yLabel = $jsonYlabel;
            
            config = {
              data: dataDb,
              xkey: 'bulan',
              ykeys: yKey,
              labels: yLabel,
              fillOpacity: 0.6,
              hideHover: 'auto',
              behaveLikeLine: true,
              resize: true,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black'],
              lineColors:['green','red','black','orange', 'blue'],
              barColors:['green','red','black','orange', 'blue'],
//              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
//              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
              lineWidth:['2','2','2','2','2'],
              parseTime: false,                       
              dataLabels: false,
              animation: false,
              goals: [1.0, -1.0],
              goalStrokeWidth: 1,
//              goalLineColors: ['orange'],
              //axes: true, //default true
              stacked: true,
              nbYkeys2: 3,
//              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
              gridIntegers: true,
              ymin: $ymin,
              ymax: $ymax,
              ymin2: $ymin,
              ymax2: $ymax,
             };
            
            config.element = 'bar-chart';
//            var browsersChart = Morris.Bar(config);
            //endregion config chart 1

            // config.element = 'area-chart';
            // Morris.Area(config);
            // config.element = 'bar-chart';
            // Morris.Bar(config);
            // config.element = 'stacked';
            // config.stacked = true;
            // Morris.Bar(config);
            // Morris.Donut({
            //   element: 'pie-chart',
            //   data: [
            //     {label: \"Friends\", value: 30},
            //     {label: \"Allies\", value: 15},
            //     {label: \"Enemies\", value: 45},
            //     {label: \"Neutral\", value: 10}
            //   ]
            // });
            // config.element = 'bar-chart';
            
//            browsersChart.options.labels.forEach(function(label, i) {
//                // bar.options.labels.forEach(function(label, i) {
////                    console.log(label);
//                var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
//                legendItem.find('span')
//                  .css('backgroundColor', browsersChart.options.lineColors[i])
//                  .css('width', '20px')
//                  .css('display', 'inline-block')
//                  .css('margin', '5px');
//                $('#legend').append(legendItem)
//              });
            
            
        
//            Morris.Area({
//                element : 'line-chart',
//                data:dataQt,
//                xkey:'quarter',
//                ykeys:['qt'],
//                labels:['avg'],
//            //  hideHover:'auto',
//            //  stacked:true
//                fillOpacity: 0.6,
//                behaveLikeLine: true,
//                resize: true,
//                parseTime: false,
//            });
                    </script>";
        // -----

        $strData .= "<div class='box box-solid bg-aqua'>";
        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Vs Purchases</h3></div>";
        $strData .= "<div class='chart' id='line-chart' style='position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        $strData .= "</div>";
        $strData .= "<div id='legend-line-chart' class='bars-legend'></div>";

        $strData .= "</div>";
        $strData .= "<script>
        
            var dataDb_2 = $jsonDatas_2;
            var yKey_2 = $jsonYkeys_2;
            var yLabel_2 = $jsonYlabel_2;
        
            config = {
              data: dataDb_2,
              xkey: 'bulan',
              ykeys: yKey_2,
              labels: yLabel_2,
              fillOpacity: 0.6,
              hideHover: 'auto',
              behaveLikeLine: true,
              resize: true,
              dataLabels: false,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black'],
              lineColors:['red','green'],
              parseTime: false,
             };
        
            // config.element = 'area-chart';
            // Morris.Area(config);
            config.element = 'line-chart';
            var browsersChartPembelian = Morris.Line(config);
        
            browsersChartPembelian.options.labels.forEach(function(label, i) {
                // bar.options.labels.forEach(function(label, i) {
                    console.log(label);
                var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
                legendItem.find('span')
                  .css('backgroundColor', browsersChartPembelian.options.lineColors[i])
                  .css('width', '20px')
                  .css('display', 'inline-block')
                  .css('margin', '5px');
                $('#legend-line-chart').append(legendItem)
              });
        
                    </script>";
        // -----

        // $strData .= "<div class='col-md-6'>";
        // $strData .= "<div class='chart tab-pane active' id='line-chart' style='position: relative; height: 200px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        // $strData .= "</div>";
        $strData .= "</div>";


        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav" => $content_nav,
            // "content_nav"      => "",
            "content" => $strData,
            "add_link" => "",
            "stop_time" => "",
        ));
        //endregion

        $p->render();
        break;
    case "viewGraph":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

//        foreach ($navigasi as $keyNav => $valNav) {
//            $labelNav = $navigasiAttr[$keyNav]["label"];
//            $minimal = $navigasiAttr[$keyNav]["minimal"];
//
//            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
//                                    <label>$labelNav: </label>
//                                    <input name='$keyNav' id='$keyNav' class='form-control' type='number' value='$valNav' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
//                                </div>";
//        }
        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";
        //endregion

        /* ==============================
         * URUSAN CHART
         * ----------------------*/

        $kolomX = array(
            "nilai_af" => "netto",
        );
        $yKoloms = array(
            "nilai_af" => "total penjualan",
        );

        $varDatas = array();
        $varBiaya = array();
        $qtDatas = array();
        $qtBiaya = array();
        $varYlabels = array();
        $varYkeys = array();

//        arrPrint($content);

//        matiHere();
//        if (sizeof($penjualanBulanan) > 0) {
//            /* =================================
//             * penjualan bulanan
//             * -------------------*/
//            $hChart=array();
//            foreach ($penjualanBulanan as $th => $datas_1) {
//                foreach ($datas_1 as $bl => $datas_2) {
//
//                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
//
//                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
//                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
//
//                    $hChart['netto'][] = ($totals-$hpp-$biaya);
//                    $specs['netto'] = ($totals-$hpp-$biaya);
//
//                    $hChart['bruto'][] = ($totals-$hpp);
//                    $specs['bruto'] = ($totals-$hpp);
//
//                    $hChart['hpp'][]=$hpp;
//                    $hChart['biaya'][] = $biaya;
//
//                    $specs['hpp'] = $hpp;
//                    $specs['biaya'] = $biaya;
//
////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
//                    $hChart['nilai_af'][] = round($datas_2['nilai_af']);
//                    $specs['nilai_af'] = round($datas_2['nilai_af']);
//
//                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
//                    $thBl = "$th " . $namaBl;
//
//                    $hChart['bulan'][] = $thBl;
//                    $specs['bulan'] = $thBl;
//
//                    $varYkeys = array();
//                    $varYlabels = array();
//
//                    //bar
//                    $varYkeys[] = 'netto';
//                    $varYlabels[] = 'laba bersih';
//                    $varYkeys[] = 'bruto';
//                    $varYlabels[] = 'laba kotor';
//
//                    //line
//                    $varYkeys[] = 'nilai_af';
//                    $varYlabels[] = 'total penjualan';
////                    $varYkeys[] = 'fire';
////                    $varYlabels[] = 'fire';
////                    $varYkeys[] = 'nonFire';
////                    $varYlabels[] = 'nonFire';
//
//                    $varYkeys[] = 'biaya';
//                    $varYlabels[] = 'biaya';
//                    $varYkeys[] = 'hpp';
//                    $varYlabels[] = 'hpp';
//
//                    $varAll['min'][] = min($specs);
//                    $varAll['max'][] = max($specs);
//
//                    $varDatas[] = $specs;
//                }
//
////                foreach ($datas_1 as $bl => $datas_2) {
////
////                    $totals = round($penjualanFireBulanan[$th][$bl])+round($penjualanNonFireBulanan[$th][$bl]);
////
////                    $biaya = isset($biayaBulanan[$th][$bl]['nilai_af']) ? round($biayaBulanan[$th][$bl]['nilai_af']) : 0;
////                    $hpp = isset($hppBulanan[$th][$bl]['nilai_af']) ? round($hppBulanan[$th][$bl]['nilai_af']) : 0;
////
//////                    $specs['totalBiaya'] = $biaya;
//////                    $specs['totalHpp'] = $hpp;
////
////                    $specs['netto'] = ($totals-$hpp-$biaya);
////                    $specs['bruto'] = ($totals-$hpp);
////
////                    $specs['fire'] = round($penjualanFireBulanan[$th][$bl]);
////                    $specs['nonFire'] = round($penjualanNonFireBulanan[$th][$bl]);
////                    $specs['nilai_af'] = round($datas_2['nilai_af']);
////
////                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
////                    $thBl = "$th " . $namaBl;
////
////                    $specs['bulan'] = $thBl;
////
////                    $varYkeys = array();
////                    $varYlabels = array();
////
////
////                    $varYkeys[] = 'fire';
////                    $varYkeys[] = 'nonFire';
////                    $varYkeys[] = 'nilai_af';
////                    $varYkeys[] = 'bruto';
////                    $varYkeys[] = 'netto';
////
//////                    $varYkeys[] = 'totalBiaya';
//////                    $varYkeys[] = 'totalHpp';
////
////                    $varYlabels[] = 'fire';
////                    $varYlabels[] = 'nonFire';
////                    $varYlabels[] = 'total penjualan';
////                    $varYlabels[] = 'laba kotor';
////                    $varYlabels[] = 'laba bersih';
//////                    $varYlabels[] = 'total BIAYA';
//////                    $varYlabels[] = 'total HPP';
////
//////                    foreach ($yKoloms as $yKolom => $yLabel) {
//////                        $varYlabels[] = $yLabel;
//////                        $varYkeys[] = $yKolom;
//////                        $specs[$yKolom] = round($datas_2[$yKolom]);
//////                    }
////
////                    $varAll['min'][] = min($specs);
////                    $varAll['max'][] = max($specs);
////
////                    $varDatas[] = $specs;
////                }
//
//            }
//
//
//        arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
//                    cekHijau($xLabel);
//                    cekHijau($xData);
                if ("label" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                }
                else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }


//        arrPrint($bruto);
//            $ymin = min($varAll['min']);
//            $ymin = round($ymin-(($ymin*10)/100));
//            $ymax = max($varAll['max']);
//            $ymax = round($ymax-(($ymax*10)/100));
//
//            // arrPrint($penjualanQuarter);
////             arrPrint( $ymax );
////             arrPrint( $ymin );
//            // arrPrint($penjualanQuarter);
//
//            /* ----------------------
//             * penjual rata2 per triwulan
//             * --------------------------------------------------dimatikan dulu bosss
//             * ------------------------*/
//            $qtDatas = array();
//            // foreach ($penjualanQuarter as $th => $datas_1a) {
//            //
//            //     $urut = 0;
//            //     foreach ($datas_1a as $qt => $datas_3a) {
//            //         $bl = $dataQuarter[$th][$qt]['bl'];
//            //
//            //         $qtSpecs['quarter'] = (string)"#$qt/$th";
//            //         $urut++;
//            //         $qtSpecs['qt'] = (string)$datas_3a;
//            //         $qtDatas[] = $qtSpecs;
//            //     }
//            // }
//        }
//
////        sort($varYkeys);
//        $jsonQtDatas = json_encode($qtDatas);
//        $jsonDatas = json_encode($varDatas);
//        $jsonYkeys = json_encode($varYkeys);
//        $jsonYlabel = json_encode($varYlabels);

        // arrPrint($namaBulan);
        // arrPrint($jsonYkeys);
        // arrPrint($jsonDatas);
        // arrPrint($varDatas);

//         arrPrint($varYkeys);
//         arrPrint($varBiaya);
        // arrPrint($varYlabels);
        // arrPrint($qtDatas);
        // arrPrint($jsonQtDatas);
        // matiHere();

        // region penjuala vs pembelian
        $varDatas_2 = array();
        if (sizeof($pembelianBulanan) > 0) {
            /* =================================
             * penjualan bulanan
             * -------------------*/
            // arrPrintWebs($pembelianBulanan);
            // arrPrintWebs($varDatas);
            // $varDatas = array();
            $varYkeys = array();
            $varYlabels = array();
            $indek = -1;
            $baseData = "penjualan";
            if ($baseData == "pembelian") {
                // -----------BASE DATA PEMBELIAN--------------
                foreach ($pembelianBulanan as $th => $datas_1) {
                    foreach ($datas_1 as $bl => $datas_2) {
                        $indek++;
                        // cekHijau("$datas_2");
                        $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                        $thBl = "$th " . $namaBl;
                        // $thBl = $namaBl;
                        // cekHere($thBl);
                        $specs_2['bulan'] = $thBl;
                        $specs_2['pembelian'] = $datas_2;
                        $specs_2['penjualan'] = $varDatas[$indek]["nilai_af"];

                        // arrPrintWebs($specs_2);
                        $varDatas_2[] = $specs_2;
                    }
                }
            }
            else {
                // -----------BASE DATA PENJUALAN--------------
                if(is_array($penjualanBulanan) && (sizeof($penjualanBulanan) > 0)){
                    foreach ($penjualanBulanan as $th => $datas_1) {
                        foreach ($datas_1 as $bl => $datas_2) {
                            $indek++;
                            // arrPrint($datas_2);
                            $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                            $thBl = "$th " . $namaBl;
                            // $thBl = $namaBl;
                            // cekHere($thBl);
                            $specs_2['bulan'] = $thBl;
                            $specs_2['penjualan'] = $datas_2['nilai_af'];
                            $specs_2['pembelian'] = isset($pembelianBulanan[$th][$bl]) ? $pembelianBulanan[$th][$bl] : 0;

                            // arrPrintWebs($specs_2);
                            $varDatas_2[] = $specs_2;
                        }
                    }
                }

            }

            $varYkeys_2[] = 'pembelian';
            $varYkeys_2[] = 'penjualan';
            $varYlabels_2[] = 'pembelian';
            $varYlabels_2[] = 'penjualan';
            // arrPrint($varDatas_2);
        }

        $jsonDatas_2 = json_encode($varDatas_2);
        $jsonYkeys_2 = json_encode($varYkeys_2);
        $jsonYlabel_2 = json_encode($varYlabels_2);
        // endregion penjuala vs pembelian


        $strData = "";
        // $strData .= "xxx";
        // $strData .= "<div class='nav-tabs-custom'>";
        // $strData .= "<div class='col-md-12'>";

//        $strData .= "<div class='box box-solid box-success'>";
//        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Morris</h3></div>";
//        $strData .= "<div class='box-body'>";
//        $strData .= "<div class='dchart' id='bar-chart' style='pposition: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
//        $strData .= "</div>";
//        $strData .= "<div id='legend' class='bars-legend'></div>";
//        $strData .= "</div>"; // body
//        $strData .= "</div>"; // box end

        $strData .= "<div class='box box-solid box-success'>";
        $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'></p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end

//        arrPrint($varDatas);

        $strData .= "<script>

        var chart = Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    toggle: {
                        align: 'left',
                        y: 30,
                        x: 20,
                        height: 14,
                        theme: {
                            'stroke-width': 1,
                            stroke: 'silver',
                            r: 0
                        },
                        text: 'Chart Height',
                        menuItems: [{
                            text: '400px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 400)
                            }
                        }, {
                            text: '800px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 800)
                            }
                        }, {
                            text: '1200px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1200)
                            }
                        }, {
                            text: '1600px',
                            onclick: function () {
                                var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                                Highcharts.charts[0].setSize(defWidth, 1600)
                            }
                        }]
                    }
                }
            },
          chart: {
            type: \"xy\",
            height: 600,
          },
          title: {
            text: 'GRAPH REPORTS',
            align: 'left'
          },
          subtitle: {
            text: \"<div class='text-bold'>laba bersih = penjualan - hpp - biaya</div><br><div class='text-bold'>laba kotor = penjualan - hpp</div><br><div class='text-bold'> </div>\",
            align: 'center'
          },
          xAxis: [{
            categories: $label,
            crosshair: true
          }],
          yAxis: [
            { // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            opposite: true
          },
          { // Secondary yAxis
//            type: 'linear',
//            max: ymax,
//            min: ymin,
            tickInterval: 5,
            gridLineWidth: 1,
            plotLines: [{
                color: '#C0C0C0',
                width: 3,
                value: 0
            }],
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            },
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[0]
              }
            }
          }],
          tooltip: {
            shared: true
          },
          plotOptions: {
                series: {
                    label: {
                        enabled: false,
                    }
                }
           },
          legend: {
            layout: 'vertical',
            align: 'right',
            x: 10,
            verticalAlign: 'top',
            y: 85,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },
          series: [
            {
            name: 'Laba Kotor',
            type: 'column',
            color: 'red',
            yAxis: 1,
            stacking: 'normal',
            data: $bruto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Laba Bersih',
            type: 'column',
            yAxis: 1,
            color: 'green',
            stacking: 'normal',
            data: $netto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'penjualan',
            type: 'spline',
            color: 'black',
            yAxis: 1,
            data: $penjualan,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'HPP',
            type: 'spline',
            color: 'blue',
            yAxis: 1,
            data: $hpp,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: 'Biaya (tanpa HPP)',
            type: 'spline',
            color: 'orange',
            yAxis: 1,
            data: $biaya,
            tooltip: {
              valueSuffix: ' '
            }
          }
          ],
          responsive: {
            rules: [{
              condition: {
                maxWidth: 500
              },
              chartOptions: {
                legend: {
                  floating: false,
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  x: 0,
                  y: 0
                }
              }
            }]
          }
        });

//function getDataChart() {
//  setTimeout(function() {
//    fetch('https://demo.mayagrahakencana.com/san_saham_up/eusvc/Graph/askPenjualanBulanan').then(function(response) {
//      return response.json()
//    }).then(function(data) {
//
////var label = this.xAxis.categories,
////    bruto = this.series[0],
////    netto = this.series[1];
//
//    console.log(data.label);
//
//    //label.addPoint([x, y], false, true);
//
//        chart.options.xAxis.categories = data.label.split(\",\");
//        chart.options.series[0].data = data.bruto.split(\",\");
//        chart.options.series[1].data = data.netto.split(\",\");
//    })
//  }, 1000)
//}

$('.highcharts-credits').remove();

        </script>\n";

        $strData .= "<script>

  //region config chart 1

            var dataDb = jsonDatas;
            var dataQt = jsonQtDatas;
            var yKey = jsonYkeys;
            var yLabel = jsonYlabel;

            config = {
              data: dataDb,
              xkey: 'bulan',
              ykeys: yKey,
              labels: yLabel,
              fillOpacity: 0.6,
              hideHover: 'auto',
              behaveLikeLine: true,
              resize: true,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black'],
              lineColors:['green','red','black','orange', 'blue'],
              barColors:['green','red','black','orange', 'blue'],
//              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
//              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
              lineWidth:['2','2','2','2','2'],
              parseTime: false,
              dataLabels: false,
              animation: false,
              goals: [1.0, -1.0],
              goalStrokeWidth: 1,
//              goalLineColors: ['orange'],
              //axes: true, //default true
              stacked: true,
              nbYkeys2: 3,
//              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
              gridIntegers: true,
//              ymin: ymin,
//              ymax: ymax,
//              ymin2: ymin,
//              ymax2: ymax,
             };

            config.element = 'bar-chart';
            var browsersChart = Morris.Bar(config);
            //endregion config chart 1

            // config.element = 'area-chart';
            // Morris.Area(config);
            // config.element = 'bar-chart';
            // Morris.Bar(config);
            // config.element = 'stacked';
            // config.stacked = true;
            // Morris.Bar(config);
            // Morris.Donut({
            //   element: 'pie-chart',
            //   data: [
            //     {label: \"Friends\", value: 30},
            //     {label: \"Allies\", value: 15},
            //     {label: \"Enemies\", value: 45},
            //     {label: \"Neutral\", value: 10}
            //   ]
            // });
            // config.element = 'bar-chart';

            browsersChart.options.labels.forEach(function(label, i) {
                // bar.options.labels.forEach(function(label, i) {
//                    console.log(label);
                var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
                legendItem.find('span')
                  .css('backgroundColor', browsersChart.options.lineColors[i])
                  .css('width', '20px')
                  .css('display', 'inline-block')
                  .css('margin', '5px');
                $('#legend').append(legendItem)
              });



//            Morris.Area({
//                element : 'line-chart',
//                data:dataQt,
//                xkey:'quarter',
//                ykeys:['qt'],
//                labels:['avg'],
//            //  hideHover:'auto',
//            //  stacked:true
//                fillOpacity: 0.6,
//                behaveLikeLine: true,
//                resize: true,
//                parseTime: false,
//            });
                    </script>";
        // -----

        $strData .= "<div class='box box-solid bg-aqua'>";
        $strData .= "<div class='box-header with-border'><h3>Monthly Sales Vs Purchases</h3></div>";
        $strData .= "<div class='chart' id='line-chart' style='position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        $strData .= "</div>";
        $strData .= "<div id='legend-line-chart' class='bars-legend'></div>";

        $strData .= "</div>";
        $strData .= "<script>

//            var dataDb_2 = jsonDatas_2;
//            var yKey_2 = jsonYkeys_2;
//            var yLabel_2 = jsonYlabel_2;

            config = {
              data: dataDb_2,
              xkey: 'bulan',
              ykeys: yKey_2,
              labels: yLabel_2,
              fillOpacity: 0.6,
              hideHover: 'auto',
              behaveLikeLine: true,
              resize: true,
              dataLabels: false,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black'],
              lineColors:['red','green'],
              parseTime: false,
             };

            // config.element = 'area-chart';
            // Morris.Area(config);
            config.element = 'line-chart';
            var browsersChartPembelian = Morris.Line(config);

            browsersChartPembelian.options.labels.forEach(function(label, i) {
                // bar.options.labels.forEach(function(label, i) {
                    console.log(label);
                var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
                legendItem.find('span')
                  .css('backgroundColor', browsersChartPembelian.options.lineColors[i])
                  .css('width', '20px')
                  .css('display', 'inline-block')
                  .css('margin', '5px');
                $('#legend-line-chart').append(legendItem)
              });

                    </script>";
        // -----

        // $strData .= "<div class='col-md-6'>";
        // $strData .= "<div class='chart tab-pane active' id='line-chart' style='position: relative; height: 200px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        // $strData .= "</div>";
        $strData .= "</div>";


        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav" => $content_nav,
            // "content_nav"      => "",
            "content" => $strData,
            "add_link" => "",
            "stop_time" => "",
        ));
        //endregion

        $p->render();
        break;
    case "viewSetupBi":
        // cekHitam("uhuii__");
        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
        //        arrPrint($arrayHistoryLabels);
        // if (strlen($errMsg) > 0) {
        //     $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        // }
        // else {
        //     $error = "";
        // }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        $vendorID = (isset($vendorId) && ($vendorId != NULL)) ? $vendorId : "0";
        $hipo_target = base_url() . "Bi/createSession";

        //arrPrint($navigasiAttr);
        // matiHEre();
        //region navigasi atribute
        // $arrBiAttr["indeks"] = array(
        //     "label" => "index",
        //     "minimal" => "100",
        // );
        // $arrBiAttr["buffer"] = array(
        //     "label" => "buffer per hari",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["periode"] = array(
        //     "label" => "omset (M)",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["leadTime"] = array(
        //     "label" => "stock sett (M)",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["limitTime"] = array(
        //     "label" => "buffer sett (M)",
        //     "minimal" => "1",
        // );
        // $arrBiAttr["moqTime"] = array(
        //     "label" => "moq sett (M)",
        //     "minimal" => "1",
        // );
        //endregion
        // arrprint($navigasi);
        // arrprint($arrBiAttr);

        // $navigasiAttr = $arrBiAttr;
        $hipo_target = base_url() . "Bi/createSession";


        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";
        $content_nav .= "<form method='get'>";
        foreach ($navigasi as $keyNav => $valNav) {
            // foreach ($arrBiAttr as $biID => $navigasiAttr) {
            // cekBiru($keyNav);
            $link_seting_buffer = base_url() . "Bi/updateSetingLimit/" . $arrBiAttr[$keyNav]["id"];
            $labelNav = $arrBiAttr[$keyNav]["label"];
            $valNavX = $arrBiAttr[$keyNav]["nilai"];
            $minimal = $navigasiAttr[$keyNav]["minimal"];
            // $valNavX = isset($_GET[$keyNav]) ? $_GET[$keyNav] : $valNav;
            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' style='width: 50px;' type='number' value='$valNavX'  onclick=\"this . select();\" onblur=\"getData('$link_seting_buffer?v=' + this . value, 'update_buffer');\" min='$minimal'>
                                </div>";
            // }
        }

        if (isset($_GET['limit'])) {
            $content_nav .= "<input type='hidden' name='qLimit' id='qLimit' value='$_GET[limit]'>";
            $content_nav .= "<input type='hidden' name='Limit' id='Limit' value='$_GET[limit]'>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";

        $link_biItems = base_url() . "Bi/viewSetupBi/466/pembelian/";
        $content_nav .= "<button type='button' class='btn btn-success btn-xl' style='margin-left: 5px;' onclick=\"$('#biItems').load('$link_biItems');\">
                                    <i class='fa fa-magic'></i></button> ";
        $content_nav .= "</form>";
        // $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
        //                                 onclick=\"window.location.reload();\">
        //                             <i class='fa fa-refresh'></i></button>";
        //

        $content_nav .= "</div>";
        $content_nav .= "</div>";
        $content_nav .= "<div id='wadah'></div>";


        //region onprogress
        if (isset($produks) && (sizeof($produks) > 0)) {

            // $bulans = array();
            // $bulanDatas = array();
            // foreach ($penjualanBulanan as $thn => $datas_2) {
            //     foreach ($datas_2 as $bln => $datas_3) {
            //         $bulans[] = "$thn<br>$bln";
            //         $bulanDatas[] = $datas_3;
            //     }
            // }
            // $jmlBulan = sizeof($bulans);
            $bulans = array();
            $heads_1 = array(
                "no",
                "pid",
                "barcode",
                "item produk",
            );
            $heads_2 = array(
                //------------
                "omzet <br> $dataPeriode Hari</p>",
                "average<br>harian",
                "<span class='text-blue'>buffer<br>(Hari)</span>",
                "<span class='text-blue'>buffer<br>(qty)</span>",
                // "<span class='text-yellow'>month set</span>",
                // "<span class='text-yellow'>moq</span>",
                "index",
                "stok<br>tersedia",
                // "sales",
                // "return",
                // "netto",
                "<span class='text-red'> umur stok<br>(hari)</span>",
                "<span class='text-red'> tlg<br> habis</span>",
                "<span class='text-green'>proyeksi stok<br>(hari)</span>",
                "<span class='text-green'>proyeksi stok<br>(qty)</span>",
                // "<span class='text-green'>umur proyeksi stok <br>(hari)</span>",
                "<span class='text-green'>tgl habis <br>proyeksi stok</span>",

                "<h4 class='text text-bold'>rekomendasi <br>order</h4>",
            );


            $heads = array_merge($heads_1, $bulans, $heads_2);
            $heads = array_merge($heads_1, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            $tblHeads_ = "<thead>";
            $tblHeads_ = "<tr>";
            foreach ($heads as $key => $label) {
                $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $tblHeads .= "<th class='text-center' $attr>$label</th>";
                $tblHeads_ .= "<td>$label</td>";
            }

            $cekAll = "<input type='checkbox' class='calcCheckAll' onclick='calcCheckAll(this)'>";
            // $tblHeads .= "<th class='text-center'>$cekAll</th>";

            $tblHeads .= "</tr>";
            $tblHeads .= "</thead>";
            $tblHeads_ .= "</tr>";
            $tblHeads_ .= "</thead>";


            $tblBodies = "";
            $tblBodies .= "<tbody>";
            $no = 0;
            $xi = 0;
            $xbt = 2000;
            $xb = 4000;
            $xlt = 6000;
            $xmt = 8000;
            $xm = 10000;
            $bgDb_b = "";
            $bgDb_bt = "";
            $bgDb_lt = "";
            $bgDb_l = "";
            $bgDb_mt = "";
            $bgDb_m = "";
            $arrProdukID_order = array();

            // foreach ($produks as $key => $val) {
            //region incerement
            //     $no++;
            //     $xi++;
            //     $xb++;
            //     $xbt++;
            //     $xlt++;
            //     $xmt++;
            //     $xm++;
            //endregion

            //     $id = $val->id;
            //     $limit = $val->limit;
            //     $limit_time = $val->limit_time;
            //     $lead_time = $val->lead_time;
            //     $indeks_db = $val->indeks;
            //     $moq = $val->moq;
            //     $moq_time = $val->moq_time;
            //     $kode = $val->kode;
            //
            //     // $isi[] = array('data' => "$value ", 'class' => 'text-left');
            //     //
            //     $link_buffer = base_url() . "Bi/updateProdukLimit/$id";
            //     $link_bufferTime = base_url() . "Bi/updateProdukLimitTime/$id";
            //     $link_indeks = base_url() . "Bi/updateProdukIndeks/$id";
            //     $link_leadTime = base_url() . "Bi/updateProdukLeadTime/$id";
            //     $link_moqTime = base_url() . "Bi/updateProdukMoqTime/$id";
            //     $link_moq = base_url() . "Bi/updateProdukMoq/$id";
            //     $link_katalog = base_url() . "Katalog/viewProduk?q=$kode";
            //     $link_ceklist = base_url() . "Bi/checklistBi/$vendorID/?mode=item&pid=$id";
            //
            //
            //     $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
            //     //     $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
            //     //     $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
            //     //     $stok_net = $stok_out - $stok_in;
            //     //
            //     $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' starget='_blank'>$stok_now</a>";
            //     //     $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
            //     //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
            //     //     $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;
            //     //     // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
            //     //     $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
            //     //     $newPox = $newPo > 0 ? $newPo : 0;
            //     //     $newPo_f = formatField("stok", $newPox);
            //     //     $ideal_stok_f = formatField("stok", $ideal_stok);
            //
            //     $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
            //     $bgDb_b = $limit > 0 ? "bg-danger" : "";
            //     $bgDb_lt = $lead_time > 0 ? "bg-danger" : "";
            //     $bgDb_i = $indeks_db > 0 ? "bg-danger" : "";
            //     $bgDb_mt = $moq_time > 0 ? "bg-danger" : "";
            //     $bgDb_m = $moq > 0 ? "bg-danger" : "";
            //     $bg_color = "";
            //     // if ($stok_now < $newPo) {
            //     //     $bg_color = "text-red";
            //     // }
            //     // elseif ($stok_now == $newPo) {
            //     //     $bg_color = "text-yellow";
            //     // }
            //     // elseif ($stok_now > $newPo) {
            //     //     $bg_color = "text-green";
            //     // }
            //     // else {
            //     //     $bg_color = "";
            //     // }
            //     // $this->table->add_row($isi);
            //     $tblBodies .= "<tr class='$bg_color' style=''>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$no</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-center text-bold'>" . $val->id . "</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-bold'>$kode</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;'>" . $val->nama . "</td>";
            //
            //     foreach ($bulanDatas as $bulanData) {
            //         $stok_out = isset($bulanData[$id]) ? $bulanData[$id]['unit_af'] : 0;
            //         // $tblBodies .= "<td class='text-right bg-yellow-light'>$stok_out</td>";
            //
            //         if (!isset($jml{$id})) {
            //
            //             $jml[$id] = 0;
            //         }
            //         $jml[$id] += $stok_out;
            //     }
            //     // arrPrint();
            //
            //     //     // ----------------------
            //     //     $tblBodies .= "<td class='text-right'>$stok_out</td>";
            //     //     $tblBodies .= "<td class='text-right'>$stok_in</td>";
            //     //     $tblBodies .= "<td class='text-right'>$stok_net</td>";
            //     //     // ----------------------
            //     $stok_out = isset($jml[$id]) ? $jml[$id] : 0;
            //     $avg = $stok_out > 0 ? ($stok_out / $jmlBulan) : 0;
            //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
            //
            //     $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
            //     $limitTimex = $limit_time > 0 ? $limit_time : $limitTime;
            //     $moqTimex = $moq_time > 0 ? $moq_time : $moqTime;
            //     $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;
            //
            //     $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
            //     $bufferx = $limit > 0 ? $limit : ($avg * $limitTimex);
            //     $bufferx_f = number_format($bufferx, 2);
            //     $moqx_f = number_format($moqx, 2);
            //
            //     $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;
            //
            //     $newPo = ($ideal_stok) - ($stok_now);
            //     $newPox = $newPo > 0 ? $newPo : 0;
            //
            //     $newPo_f = ceil($newPox);
            //     $ideal_stok_f = ceil($ideal_stok);
            //
            //     $strIndex = "<input type='number' tabindex='$xi' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
            //     $strBuffer = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none $bgDb_b' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
            //     $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
            //     $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";
            //     $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin border-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
            //     $strMoq = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin border-none $bgDb_m' style='width: 50px' value='$moqx_f' onclick=\"this.select();\" onblur=\"getData('$link_moq?v='+this.value,'update_buffer');\">";
            //
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-warning text-bold'>$stok_out</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$avg_f</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strBufferTime</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strBuffer</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strMoqTime</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strMoq</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strIndex</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$stok_now_l</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strLeadTime</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$ideal_stok_f</td>";
            //     $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-success font-size-1-2'>$newPo_f</td>";
            //     $ceklist = "<input type='checkbox' povalue='$newPo_f' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
            //     // $tblBodies .= "<td class='text-center bg-success'>$ceklist</td>";
            //
            //     $tblBodies .= "</tr>";
            //
            //
            //     $arrProdukID_order[$id] = $newPo_f;
            //
            //
            // }
            // $tblBodies .= "</tbody>";
            //
            // $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            //            $this->table->add_row(array(
            //                'data' => '-the item you specified has no entry-',
            //                'colspan' => count($produks) + 2,
            //                'class' => 'text-center',
            //            ));
            $strDataProposeFooter = "";
            $tblHeads = "";
            $tblBodies = "";
            $tblBodies .= "<thead>";
            $tblBodies .= "<tr>";
            $tblBodies .= "<th>---------------------------------------</th>";
            $tblBodies .= "</tr>";
            $tblBodies .= "</thead>";
            $tblBodies .= "<tbody>";
            $tblBodies .= "<tr style='height: 35px;font-size: larger;'>";
            $tblBodies .= "<td colspan=''>Silahkan tentukan VENDOR terlebih dahulu atau belum ada relasi Vendor dengan produk.</td>";
            $tblBodies .= "</tr>";
            $tblBodies .= "</tbody>";
        }
        //
        $strDataPropose = "";
        $strDataPropose .= "<table style='font-family: monospace;' class='table table-hover nowrap compact table-condensed' id='bi_table'>";
        $strDataPropose .= $tblHeads;

        //        $strDataPropose .= $tblBodies;
        $strDataPropose .= $tblHeads_;

        $strDataPropose .= "<tbody><tr></tr></tbody>";
        $strDataPropose .= "<tfoot><tr></tr></tfoot>";
        $strDataPropose .= "</table>";

        $strDataPropose .= "<div id='update_buffer'></div>";
        //endregion


        //region legenda koloms diatus dari heBi
        $content_note = "";
        foreach ($notes as $legenda => $lNote) {

            $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
            $content_note .= "<span class='text-primary text-uppercase'>$legenda</span> : ";
            $content_note .= "$lNote";
            $content_note .= "</p> ";
        }
        $content_note .= "<p class='megta no-margin' style='padding-left:15px;'>";
        $content_note .= "<span class='label bg-danger text-black' style=\"
    padding-right: 20px;
    padding-left: 20px;
    padding-top: 3px;
    padding-bottom: 3px;
\">&nbsp;##&nbsp;</span> : ";
        $content_note .= "fixed applied setting";
        $content_note .= "</p> ";
        //endregion

        if (sizeof($produks) > 0) {

            // $propDisplay = "block";
            $propDisplay = "none";
        }
        else {

            $propDisplay = "none";
        }


        $vendorNama_f = "";
        $jumlahProduk_f = "";
        if (isset($vendorNama) && ($vendorNama != NULL)) {
            $vendorNama_f = "  " . $vendorNama;
            $jumlahProduk_f = " (" . sizeof($produks) . " items)";
        }
        $str = "<div class='box box-danger'>";
        //        $str .= "<div class='box-header with-border text-green'>";
        //        $str .= "<h4 class='no-padding no-margin'><span class=\"glyphicon glyphicon-th-list\"></span> CALCULATOR STOCK</h4>";
        //        $str .= "<div class='box-tools pull-right'><button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button></div>";
        //        $str .= "</div class='box box-header'>";
        $str .= "<div style='zoom: 0.9' class='box-body'>";
        $str .= $content_nav;
        $str .= $content_note;
        $str .= $strDataPropose;
        $str .= isset($btn) ? $btn : "";
        $str .= "</div class='box-body'>";
        $str .= "</div class='box box-danger'>";

        $url = base_url() . 'Bi/fetch_data';
        $mdl = 'MdlProduk';
        $fId = '';

        $str .= "\n\n<script>
 $(document).ready( function(){
    
                         var posrurl = '$url';
                         var posmdl = '$mdl';
                         var postfid = '$fId';
                         var buttonCommon = {
                            exportOptions: {
                                format: {
                                    body: function ( data, row, column, node ) {
                                        var newData = String(data);
                                        console.log(newData);
                                        var pos = newData.indexOf('<a ');
                        
                                        if(pos!==-1){

                                }
                                        else{
                                            var pos1 = newData.indexOf('<i ');
                                            if(pos1!==-1){
                        }
                        else{
                                                return data;
                                        }
                                    }
                        }
                    }
                            }
                        };
                    
                         var dataTable = $('#bi_table').DataTable({
                            dom: 'lBfrtip',
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: 20,
                            processing:true,
                            serverSide:true,
                            searchDelay: 1000,
                            order:[],
//                            ajax: posrurl,
                            ajax:{
                                url:posrurl,
                                type:'POST',
                                data: {mdl:posmdl,fid:postfid}
                            },
                            buttons: [
                                $.extend( true, {}, buttonCommon, {
                                    extend: 'copyHtml5'
                                } ),
                                $.extend( true, {}, buttonCommon, {
                                    extend: 'excelHtml5'
                                } ),
                                $.extend( true, {}, buttonCommon, {
                                    extend: 'pdfHtml5'
                                } )
                            ],
                            columnDefs:[
                                {
                                    'targets':[0],
                                    'orderable':false,
                                },
                            ],
                            rowCallback: function( row, data ) {
                                var tmpUrl = window.location.href;
                                    tmpUrl = tmpUrl.replace('https://', '');
                                var fullurl = tmpUrl.split('?')
                                    fullurl = fullurl[0]

                                var segmentUrl = fullurl.split('/');
                                // console.log( typeof segmentUrl[3] );
                                if(typeof segmentUrl[3] != 'undefined' ){
                                    // console.error( segmentUrl[3] );
                                    if( segmentUrl[3] == 'ProdukDeassemble' ){
                                        if( parseFloat(data[8]) <= 0){
                                            $(row).addClass('bg-red');
                                }
                                }
                        }
                    }
                        });

//                    setTimeout( function(){
//                        if($('#bi_table thead th').length>1){
//                            $('#bi_table').DataTable({
//                                order: [[ 14, 'desc' ]],
//                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
//                                pageLength: -1,
//                                paging: false,
//                                info: false,
//                            });
//                            $('#bi_table_wrapper').addClass('table-responsive');
//                        }
//                        else{
//                            $('#bi_table').DataTable({
//                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
//                                pageLength: -1,
//                                paging: false,
//                                info: false,
//                            });
//                            $('#bi_table_wrapper').addClass('table-responsive');
//                        }
//
//                    }, 1000);
//
//                    function initBtn(){
//                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
//                        var arrValid = {}
//                        if(arrCheck.length > 0){
//                            jQuery.each(arrCheck, function(i, b){
//                                var povalue = $(b).attr('povalue');
//                                var id = $(b).attr('id');
//                                if( $(b).is(':checked') ){
//                                    arrValid[id] = povalue
//                                }
//                            })
//                        }
//                        if(Object.keys(arrValid).length === 0){
//                            $('#btnCreateShoppingCart')
//                            .removeClass('btn-warning')
//                            .addClass('btn-default')
//                            .off('click')
//                        }
//                        else{
//                            $('#btnCreateShoppingCart')
//                            .off('click')
//                            .removeClass('btn-default')
//                            .addClass('btn-warning')
//                            .on('click', function(){
//                                $.ajax({
//                                    type: 'POST',
//                                    url: '$btnToShoppingCart',
//                                    data: { items: btoa(JSON.stringify(arrValid)) },
//                                    success: function(data) {
//                                        var arrData = JSON.parse(data)
//                                        console.log(data);
//                                        console.log(arrData.status);
//                                        if(arrData.status==1){
//                                            if(top.document.getElementById('shopping_cart')){
//                                                top.$('#shopping_cart').load(arrData.url);
//                                            };
//                                        }
//                                    },
//                                    error: function(){
//                                        swal('koneksi error');
//                                        HoldOn.close()
//                                    }
//                                });
//                            })
//                        }
//                    }
//                    initBtn()
//
//                    function calcCheckAll(e){
//                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
//                        if(arrCheck.length > 0){
//                            jQuery.each(arrCheck, function(i, b){
//                                if( $(e).is(':checked') ){
//                                    $(b).prop('checked', true)
//                                    initBtn()
//                                }
//                                else{
//                                    $(b).prop('checked', false)
//                                    initBtn()
//                                }
//                            })
//                        }
//                    }
//
//                    function resetCalcCheckAll(){
//                        $('.calcCheckAll').prop('checked', false);
//                    }
                    })
                \n</script>";

        // if (isset($jenisTr) && ($jenisTr == 466)) {
        //
        //     echo $str;
        // }

        $p->addTags(array(
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "content_nav" => "",
            "content" => $str,
            "free_content" => "",
            "stop_time" => "",
        ));
        //endregion

        $p->render();


        break;

    case "showStokLimit":
        $str = "";
        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($tabHistoryFields) > 0) {
                //                cekKuning("::");
                $str .= "<div class=\"clearfix\">&nbsp;</div>";
                $str .= "<div class=\"nav-tabs-custom\">";
                $str .= "<ul class=\"nav nav-tabs\">";
                $str .= "<li style='margin-top: 0px;' class=\"pull-left header\"><i class=\"fa fa-th\"></i> STOK LIMIT<br><span style='margin-top: -18px;' class='pull-right text-red blink'>----------></span></li>";
                $no1 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {
                    $active = $no1 == 1 ? "active text-bold text-green" : "text-bold";
                    $str .= "<li class=\"$active\"><a href=\"#tab_$ky\" data-toggle=\"tab\" aria-expanded=\"false\">" . $arrLab['label'] . "</a></li>";
                    $no1++;
                }

                $str .= "<li class='pull-right'>
                    <div>
                        <span style='padding: 10px; cursor: pointer;' onclick=\"viewStokLimit()\"><i class='fa fa-refresh'></i></span>
                        <span style='padding: 10px; cursor: pointer;' data-toggle='collapse' data-target='#tab-content' class='collapsed' aria-expanded='true'><i class='fa fa-minus'></i></span>
                    </div>
                </li>";

                $str .= "</ul>";
                $str .= "<div id='tab-content' class=\"tab-content\" aria-expanded='true'>";

                $no2 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {

                    $active = $no2 == 1 ? " active" : "";
                    $str .= "<div class=\"tab-pane$active\" id=\"tab_$ky\">";

                    // $str .= "<form method='post' id='$ky' name='$ky' target='result' action='$reqFormTarget'>";
                    // $str .= "<form method='post' id='$ky' name='$ky' target='result' action='#'>";
                    // $str .= "<table class='table $ky table-hover' >";
                    // $str .= "<thead style='background: lightgrey;'>";
                    // $str .= "<tr line=" . __LINE__ . ">";
                    // foreach ($tabFieldsItems[$ky] as $kyLabel => $label) {
                    //
                    //     if ($kyLabel == 'select') {
                    //         if (isset($allowMultiSelect) && $allowMultiSelect == true) {
                    //             $selectAll = "<input type='checkbox' id='selectAll_$ky'>";
                    //             $str .= "<th>$selectAll $label</th>";
                    //         }
                    //         else {
                    //             $str .= "<th>$label</th>";
                    //         }
                    //
                    //     }
                    //     else {
                    //         $str .= "<th>$label</th>";
                    //     }
                    //
                    // }
                    // $str .= "</tr>";
                    // $str .= "</thead>";
                    // $str .= "<tbody>";
                    //
                    // foreach ($arrayOnProgress as $row) {
                    //
                    //     if(isset($vendorPair[$ky])){
                    //         foreach($vendorData as $vID =>$vLabel){
                    //             $str .= "<tr line=" . __LINE__ . ">";
                    //             $str .="<span>$vLabel</span>";
                    //             $str .= "</tr>";
                    //         }
                    //     }
                    //     else{
                    //         $str .= "<tr line=" . __LINE__ . ">";
                    //         foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                    //             if (isset($row[$kyLabel])) {
                    //                 $str .= "<td>";
                    //                 $str .= $row[$kyLabel];
                    //                 $str .= "</td>";
                    //             }
                    //         }
                    //         $str .= "</tr>";
                    //     }
                    //
                    // }
                    // $str .= "</tbody>";
                    // $str .= "<tfoot style='background: lightgrey;'>";
                    // $str .= "<tr line=" . __LINE__ . ">";
                    // foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                    //     $angka = array();
                    //     foreach ($arrayOnProgress2[$ky] as $row) {
                    //         if (!isset($angka[$ky])) {
                    //             $angka[$ky] = 0;
                    //         }
                    //         $angka[$ky] += is_numeric($row[$kyLabel]) ? $row[$kyLabel] : "";
                    //     }
                    //     $str .= "<th>";
                    //     $str .= $arrayOnProgress2[$ky];
                    //     $str .= "</th>";
                    // }
                    // $str .= "<th>";
                    $str .= isset($arrayOnProgress2[$ky])?$arrayOnProgress2[$ky]:"";
                    // $str .= "</th>";

                    // $str .= "</tr>";
                    // $str .= "</tfoot>";
                    // $str .= "</table>";


                    // if ($allowMultiSelect == true) {
                    //     $str .= "<div class='row'>";
                    //     if (isset($needToClear) && $needToClear == true) {
                    //         $str .= "<div class='col-sm-12 text-center'>";
                    //         $str .= "<div class='text-warning'>";
                    //         $str .= "to process one of entries above, you need to clear selected items<br>";
                    //         $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items </a>";
                    //         $str .= "</div class='alert alert-warning'>";
                    //         $str .= "</div>";
                    //     }
                    //     else {
                    //         if (isset($arrLab['allowFollowup']) && $arrLab['allowFollowup'] == true) {
                    //             cekHijau($ky);
                    //             $str .= "<div class='col-sm-6 text-left'>";
                    //             $str .= "<button id='btnConnect$ky' name='btnConnect$ky' class='btn btn-primary' href=# onclick=\"this.disabledxx=true;this.innerHTML='clear the list to connect another one';document.getElementById('$ky').submit()\"><span class='fa fa-external-link'></span> Followup " . $arrLab['label'] . "</button>";
                    //             $str .= "</div>";
                    //             $str .= "<div class='col-sm-6'></div>";
                    //         }
                    //         else {
                    //             $str .= "<div class='clearfix'>&nbsp;</div>";
                    //             $str .= "<div class='col-sm-12 text-left'>";
                    //             $str .= "<div class='alert alert-danger' role='error'>";
                    //             $str .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                    //             $str .= "<span class='sr-only'>Error:</span>";
                    //             $str .= " NOTE:";
                    //             $str .= "<div> - Tidak Bisa Followup " . $arrLab['label'] . ", silahkan Followup melalui metode lainnya.</div>";
                    //             $str .= "<div> - Bagian ini hanya untuk kebutuhan Pengecekan.</div>";
                    //             $str .= "<div> - Letakan Cursor pada isi kolom produk, untuk melihat Rincian.</div>";
                    //             $str .= "</div>";
                    //             $str .= "</div>";
                    //         }
                    //
                    //     }
                    //     $str .= "</div class=row>";
                    // }


                    $str .= "</div>";
                    // $str .= "</form>";
                    $no2++;
                }
                $str .= "</div>";
                $str .= "</div>";
                $str .= "\n<script>$(document).ready( function () {
                            $('#data_produk').DataTable({
                                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                                order: [[ 9, 'desc' ]],
                                paging: false,
                                info: false,
                                search: false,
                            });
                        } );</script>";

            }
            else {
                cekMerah("####");
                $str .= "<div class='box box-danger'>";
                $str .= "<div class='box-header text-green blink'>";
                $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
                $str .= "</div class='box box-header'>";
                $str .= "<div class='box-body'>";
                if (sizeof($arrayOnProgress2) > 0) {
                    $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    $str .= "<div class='panel panel-default'>";
                    $str .= "<div class='panel-body'>";
                    $str .= "<h4 class='text-blue'>from requests</h4>";
                    $str .= "<div class='row'>";
                    $rCtr = 0;
                    foreach ($arrayOnProgress2 as $key => $pSpec) {
                        $rCtr++;
                        if (isset($pSpec['select'])) {
                            $str .= "<label>";
                        }
                        $str .= "<div class='text-center alaIcon'>";
                        foreach ($arrayProgress2Labels as $k => $label) {
                            $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                            $str .= "<div>";
                            $str .= formatGlanceField($k, $iVal);
                            $str .= "</div>";
                        }
                        if (isset($pSpec['select'])) {
                            $str .= $pSpec['select'];
                        }
                        $str .= "</div class='col-md-3'>";
                        if (isset($pSpec['select'])) {
                            $str .= "</label>";
                        }
                    }
                    $str .= "</div>";
                    if ($allowMultiSelect == true) {
                        $str .= "<div class='row'>";
                        if (isset($needToClear) && $needToClear == true) {
                            $str .= "<div class='col-sm-12 text-center'>";
                            $str .= "<div class='text-warning'>";
                            $str .= "to process one of entries above, you need to clear selected items<br>";
                            $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                            $str .= "</div class='alert alert-warning'>";
                            $str .= "</div>";
                        }
                        else {
                            $str .= "<div class='col-sm-6'></div>";
                            $str .= "<div class='col-sm-6 text-right'>";
                            $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                            $str .= "</div>";
                        }
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                    $str .= "</div class='panel panel-default'>";
                    $str .= "</form>";


                }
                if (sizeof($arrayOnProgress) > 0) {
                    $str .= "<table class='table table-condensed'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>$label</td>";
                    }
                    $str .= "</tr>";
                    foreach ($arrayOnProgress as $key => $pSpec) {
                        $str .= "<tr line=" . __LINE__ . ">";
                        foreach ($arrayProgressLabels as $k => $label) {
                            $str .= "<td>";
                            $str .= $pSpec[$k];
                            $str .= "</td>";
                        }
                        $str .= "</tr>";
                    }
                    $str .= "</table class='table table-condensed'>";
                }
                $str .= "</div class='box-body'>";
                $str .= "</div class='box box-danger'>";
            }
        }
        echo $str;
        break;

    case"showVendorRelation":

        $tblHeads = "<thead>";
        $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
        // $tblHeads_ = "<thead>";
        // $tblHeads_ = "<tr>";
        $tblHeads .= "<td>No</td>";
        foreach ($arrayProgressHeader as $key => $label) {
            $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
            $tblHeads .= "<th class='text-center' $attr>$label</th>";
        }
        $tblHeads .= "<td>Pilih<input id='$vendorID' type='checkbox' class='calcCheckAll' onclick='calcCheckAllVendor(this)'> </td>";

        $tblHeads .= "</tr>";
        $tblHeads .= "</thead>";

        $tblBody = "";
        $ii = 0;
        foreach ($arrayOnProgress as $pID => $pidData) {
            $rel_id = "produk_" . $vendorID . "_" . $pID;
            $ii++;
            $tblBody .= "<tr>";
            $tblBody .= "<td>$ii</td>";
            foreach ($arrayProgressHeader as $key => $kLabel) {
                $newValue = $pidData[$key];
                $newValue_f = str_replace(",", "", trim($newValue));
                if (is_numeric($newValue_f) && $key != 'pid') {
                    $numValue = str_replace(",", "", $pidData[$key]);
                    $numValue_f = number_format($numValue);
                    $tblBody .= "<td class='text-right' key='$key' data-order='$numValue'>$numValue_f</td>";
                }
                else if (!is_numeric($newValue_f)) {
                    $tblBody .= "<td key='$key' class='text-left' data-order='$pidData[$key]'>$pidData[$key]</td>";
                }
                else {
                    $tblBody .= "<td key='$key' class='text-center text-bold' data-order='$pidData[$key]'>$pidData[$key]</td>";
                }
            }
            $order = $pidData['new_order'];
            $inputcheckbox = "<input type='checkbox' id='$rel_id' name=\"produk_$vendorID" . "[]" . "\" onchange=\"$('#result').load('$link_ceklist&mode=toitem&pid=$pID&order=$order&val='+$(this).prop('checked'))\">";
            $tblBody .= "<td>$inputcheckbox</td>";
            $tblBody .= "</tr>";
        }

        //
        $strDataPropose = "<div class='row'>";
        $strDataPropose .= "<div class='col-md-12 table-responsive'>";
        $strDataPropose .= "<form method='post' id='$vendorID' name='$vendorID' target='result' action='$targetForm_link?sid=$vendorID'>";
        $strDataPropose .= "<table name='vendor_$vendorID' style='font-family: monospace;font-size:12px;' class='table table-hover nowrap compact table-condensed' id='bi_table'>";
        $strDataPropose .= $tblHeads;

        //        $strDataPropose .= $tblBodies;
        // $strDataPropose .= $tblHeads_;

        $data_tgl = date("Y-m-d H:i:s");

        $strDataPropose .= "<caption class='text-bold'><h4>DATA STOK LIMIT BERDASARKAN VENDOR <r>PER TANGGAL $data_tgl</r><br>VENDOR: <span class='text-capitalize text-success'>$vendorName</span></h4></caption>";

        $strDataPropose .= "<tbody>$tblBody</tbody>";
        $strDataPropose .= "<tfoot></tfoot>";
        $strDataPropose .= "</table>";


        $strDataPropose .= "<div style='padding-top: 12px;'>";
        $strDataPropose .= "<button type='button' id='btn_" . $vendorID . "' class='btn btn-warning btn-flat pull-right' onclick=\"document.getElementById('$vendorID').submit();\">Masukan ke shopingcart</button>";
        $strDataPropose .= "</div>";
        $strDataPropose .= "</form>";
        $strDataPropose .= "</div>";
        $strDataPropose .= "</div>";
        $strDataPropose .= "<div id='update_buffer'></div>";

        $modalSize = "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')";
        $strDataPropose .= "<script>$modalSize</script>";

        $strDataPropose .= "\n\n<script>

                    $(document).ready( function(){
                        $('#bi_table').DataTable({
                            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                            order: [[ 12, 'desc' ]],
                            paging: false,
                            info: false,
                            search: false,
                        });
                    });

                    function calcCheckAllVendor(e){
                        var idsid = $(e).prop('id');
                        console.log('idsid', idsid);
                        $(\"input[name='produk_\"+idsid+\"\[\]']\").each( function(i, b){
                            if( $(e).is(':checked') ){
                                $(b).prop('checked', true);
                                $(b).trigger('change');
                            }
                            else{
                                $(b).prop('checked', false);
                                $(b).trigger('change');
                            }
                        })
                    }
                
                
                \n</script>";
        echo $strDataPropose;
        break;
}