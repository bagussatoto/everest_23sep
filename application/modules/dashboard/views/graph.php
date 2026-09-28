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
                } elseif ($stok_now == $newPo) {
                    $bg_color = "text-yellow";
                } elseif ($stok_now > $newPo) {
                    $bg_color = "text-green";
                } else {
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
        } else {
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
        } else {

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
        } else {
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
        } else {

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
            "label" => "index (%)",
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
                0 => 2,
                1 => 2,
                2 => 2,
                3 => 2,
                4 => 2,
                5 => 2,
                7 => 2,
                8 => 2,
                9 => 2,
                11 => 2,
                12 => 2,
            );
            $colspan_keys = array(
                6 => 2,
                // 7  => 2,
                10 => 2,
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
                $strBuffer = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin bborder-none $bgDb_b' style='width: 50px' value='$bufferx' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";
                $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin bborder-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
                $strMoq = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin bborder-none $bgDb_m' style='width: 50px' value='$moqx' onclick=\"this.select();\" onblur=\"getData('$link_moq?v='+this.value,'update_buffer');\">";

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
        } else {
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
        } else {

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
                    } else {
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
            } else {
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
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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

        // arrPrint($content);

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
                } else {
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
            } else {
                // -----------BASE DATA PENJUALAN--------------
                if (is_array($penjualanBulanan) && (sizeof($penjualanBulanan) > 0)) {
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

        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_1'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'>Akumulasi penjualan LOCAL, EXPORT dan PROJECT</p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        // $strData .= "</div>"; // box end 12

        //        arrPrint($varDatas);

        $strData .= "<script>

        var chart = Highcharts.chart('container_1', {
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
            height: 400,
          },
          title: {
            text: 'GRAPH REPORTS',
            align: 'left'
          },
          subtitle: {
            text: \"<div class='text-bold'>laba bersih = penjualan - hpp - biaya</div><br><div class='text-bold'>laba kotor = penjualan - hpp</div><br>Akumulasi penjualan LOCAL, EXPORT dan PROJECT<div class='text-bold'> </div>\",
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
            tickInterval: 10000000,
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
              format: '{text}',
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
          },
          {
            name: 'Total Biaya',
            type: 'spline',
            color: 'pink',
            yAxis: 1,
            data: $biayahpp,
            tooltip: {
              valueSuffix: ' '
            }
          },
          // {
          //   name: 'project',
          //   type: 'spline',
          //   color: 'brown',
          //   yAxis: 1,
          //   data: $project,
          //   tooltip: {
          //     valueSuffix: ' '
          //   }
          // }
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

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = '';
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData = "";

        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {

            echo $strData;
        }

        break;
    case "viewGraphSales":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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

        // arrPrint($content);

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
                // cekHijau($xLabel);
                // cekHijau($xData);
                if ("tahun" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                } else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($tahun);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        $penjualan_tahun_lalu_0 = json_decode($penjualan_past_0)[0];
        $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        $target_tahun_ini = json_decode($target_tahun_now)[0];
        $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        $persen_selisih = $penjualan_tahun_lalu > 0 ? (($selisih_target / $penjualan_tahun_lalu) * 100) : 0;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");
        /* ------------------------------------------------------------------
         * YG BISA MERUBAH TARGET Penjualan
         * ------------------------------------------------------------------*/
        $allowed_save = array(
            // "9" => "holding",
            // "17" => "holding_",
            "159" => "hindra kurniawan",
            "78" => "data_admin/santoso pusat",
        );
        $target_save_nilai = 0;
        if (in_array(my_id(), array_keys($allowed_save))) {
            $target_save_nilai = my_id();
        }
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
        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        // $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label
        $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label 
<input type='button' id='target_save' value='$target_save_nilai' disabled style='display: none;'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'>
          <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
        var pmtRange = document.getElementById('investment'),chart;
        function setData(chart) {
            var pmt = +pmtRange.value,
                target_persen = $target_persen,
                ranges = [];

            ranges[0] = pmt;
            
            chart.series[3].setData(ranges);
        }
        
        var chart = Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    // toggle: {
                    //     align: 'left',
                    //     y: 30,
                    //     x: 20,
                    //     height: 14,
                    //     theme: {
                    //         'stroke-width': 1,
                    //         stroke: 'silver',
                    //         r: 0
                    //     },
                    //     text: 'Chart Height',
                    //     menuItems: [{
                    //         text: '400px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 400)
                    //         }
                    //     }, {
                    //         text: '800px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 800)
                    //         }
                    //     }, {
                    //         text: '1200px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1200)
                    //         }
                    //     }, {
                    //         text: '1600px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1600)
                    //         }
                    //     }]
                    // }
                }
            },
          // chart: {
          //   type: \"xy\",
          //   height: 300,
          // },
          chart: {
                type: 'column'
            },
          title: {
            text: 'PENJUALAN',
            align: 'left'
          },
          subtitle: {
             text: \"<div class='text-bold' id='open_editor_target' title='klik untuk merubah'>Target Pertumbuhan <link>$target_persen%</link> dari $penjualan_past_label</div><br><div class='text-bold'>&nbsp;</div><br><div class='text-bold'> </div>\",
            align: 'left'
          },
          xAxis: [{
            categories: ['PENJUALAN DALAM MILYAR'],
            // crosshair: true
          }],
          yAxis: [
            { // Primary yAxis
            labels: {
              format: '{value}',
              style: {
                color: Highcharts.getOptions().colors[1]
              }
            },
            title: {
              text: '',
              style: {
                color: Highcharts.getOptions().colors[2]
              }
            },
            // opposite: true
          },
          { // Secondary yAxis
//            type: 'linear',
//            max: ymax,
//            min: ymin,
            tickInterval: 5,
            gridLineWidth: 1,
            plotLines: [{
                // color: '#C0C0C0',
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
                // series: {
                //     label: {
                //         enabled: false,
                //     }
                // }
                series: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}',
                                align: 'right',
                                // color: '#FFFFFF',
                                x: -10
                            },
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
           },
          legend: {
            // layout: 'vertical',
            align: 'right',
            x: 10,
            verticalAlign: 'top',
            y: 40,
            floating: true,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },                    
          series: [
              {
                name: $penjualan_past_label_0,
                // type: 'column',
                color: 'green',
                // yAxis: 1,
                // stacking: 'normal',
                data: $penjualan_past_0,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                    style: {
                        fontWeight: 'normal',
                        fontSize: '9px'
                    }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M',
                }
              },
              {
                name: $penjualan_past_label,
                // type: 'column',
                color: 'red',
                // yAxis: 1,
                // stacking: 'normal',
                data: $penjualan_past,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                    // inside: true,
                    style: {
                        fontWeight: 'normal',
                        fontSize: '9px'
                    }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M',
                }
              },
              {
                name: $penjualan_now_label,
                // type: 'column',
                color: 'blue',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$persen_now% vs $penjualan_past_label<br>$date_now',
                     // rotation: -90,
                    // inside: true
                    style: {
                        fontWeight: 'normal',
                        fontSize: '9px'
                    }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
              {
                name: $target_penjualan_now_label,
                // type: 'column',
                color: '#ccc',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $target_penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    style: {
                        fontWeight: 'normal',
                        fontSize: '9px'
                    }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
              {
                name: $target_tahun_now_label,
                color: '#333',
                data: $target_tahun_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$target_persen%',
                    style: {
                        fontWeight: 'normal',
                        fontSize: '9px'
                    }
                }],
               tooltip: {
                    pointFormat:'<span style=\"color:{point . color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
     
          ],
          
          // responsive: {
          //   rules: [{
          //     condition: {
          //       maxWidth: 500
          //     },
          //     chartOptions: {
          //       legend: {
          //         floating: false,
          //         layout: 'horizontal',
          //         align: 'center',
          //         verticalAlign: 'bottom',
          //         x: 0,
          //         y: 0
          //       }
          //     }
          //   }]
          // }
        });

        pmtRange.addEventListener('change', setData.bind(null, chart));

$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSales":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();


        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure' sstyle='height: 100px;'>";
        $strData .= "<div id='container_68461584'></div>";
        $strData .= "<p class='highcharts-description'><span id='editor_target'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'>
          <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";

        $judul = "Penjualan YTD <small >(" . dtimeNow("d M Y") . ")</small>";

        $nilai_local = $content["penjualan"] * 1;
        $nilai_project = $content["penjualan_projek"] * 1;
        $nilai_total = $content["penjualan_total"] * 1;
        $nilai_total_f = number_format($nilai_total, 2);

        // $nilai_project = $content["penjualan_projek"];
        // $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>     
        Highcharts.setOptions({
             colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4']
        });
        // var chart;  
        var chart = Highcharts.chart('container_68461584', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    // toggle: {
                    //     align: 'left',
                    //     y: 30,
                    //     x: 20,
                    //     height: 14,
                    //     theme: {
                    //         'stroke-width': 1,
                    //         stroke: 'silver',
                    //         r: 0
                    //     },
                    //     text: 'Chart Height',
                    //     menuItems: [{
                    //         text: '400px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 400)
                    //         }
                    //     }, {
                    //         text: '800px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 800)
                    //         }
                    //     }, {
                    //         text: '1200px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1200)
                    //         }
                    //     }, {
                    //         text: '1600px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1600)
                    //         }
                    //     }]
                    // }
                }
            },
          chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                // height: 300,
                // width: 300,
            },
            title: {
                text: '$judul',
                align: 'left'
            },
            subtitle: {
                text: 'Total <b>$nilai_total_f M</b>',
                align: 'left'},
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%<br>Nilai: {point.y:.2f}M<br>Total: {point.total:.2f}M',
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        // enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%<br>{point.y:.2f}M/{point.total:.2f}M'
                    }
                }
            },
            series: [{
                name: 'Persen',
                colorByPoint: true,
                data: [{
                    name: 'Local',
                    y: $nilai_local,                    
                    
                }, 
                // {
                //     name: 'export',
                //     y: $nilai_export
                // },  
                    {
                    name: 'Project',
                    y: $nilai_project
                }, 
                //     {
                //     name: 'lain-lain',
                //     y: 2
                // },
                ]
            }]
        });

        pmtRange.addEventListener('change', setData.bind(null, chart));

$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSeller":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();


        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure' sstyle='height: 100px;'>";
        $strData .= "<div id='container_19812651'></div>";
        $strData .= "<p class='highcharts-description'><span id='editor_target'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'>
          <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";

        $judul = "Penjualan YTD <small >(" . dtimeNow("d M Y") . ")</small>";

        $nilai_local = $content["kategoriGede"];
        $nilai_order = $content["order"];
        // $nilai_total = $content["penjualan_total"] * 1;
        // $nilai_total_f = number_format($nilai_total,2);

        // $nilai_project = $content["penjualan_projek"];
        // $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        // $test[]['anu']= 55;
        $test[] = array("ff", 55);
        $test[] = array("ft", 25);
        $test[] = array("xt", 15);
        // arrPrintHijau($test);
        $warna = array('#9c27b0', '#f50e0e', '#0b34fd', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#006400', '#FF69B4', '#FFF263', '#6AF9C4', '#FF1493');
        $warna_json = json_encode($warna, true);
        arrPrint($warna_json);
        // arrPrint($warna);
        foreach ($warna as $werno) {
            echo "<div style='background-color: $werno;float: left;width: 100px;align-content: center;'>$werno</div>";
        }
        echo "<hr>";
        $testing = json_encode($test);
        // $testing = json_encode($nilai_local);
        arrPrintPink($testing);
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>     
        Highcharts.setOptions({
             // colors: ['#9c27b0','#f50e0e','#0b34fd','#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655','#006400','#FF69B4','#FFF263', '#6AF9C4','#FF1493']
             colors: $warna_json
        });
        // var chart;  
            var dt = $testing;
        var chart = Highcharts.chart('container_19812651', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: true
                    },
                    // toggle: {
                    //     align: 'left',
                    //     y: 30,
                    //     x: 20,
                    //     height: 14,
                    //     theme: {
                    //         'stroke-width': 1,
                    //         stroke: 'silver',
                    //         r: 0
                    //     },
                    //     text: 'Chart Height',
                    //     menuItems: [{
                    //         text: '400px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 400)
                    //         }
                    //     }, {
                    //         text: '800px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 800)
                    //         }
                    //     }, {
                    //         text: '1200px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1200)
                    //         }
                    //     }, {
                    //         text: '1600px',
                    //         onclick: function () {
                    //             var defWidth = Highcharts.charts[0].get().chart.chartWidth;
                    //             Highcharts.charts[0].setSize(defWidth, 1600)
                    //         }
                    //     }]
                    // }
                }
            },
          chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                // height: 300,
                // width: 300,
            },
            title: {
                text: '$judul',
                align: 'left'
            },
            subtitle: {
                text: 'Total <b>$nilai_total_f M</b>',
                align: 'left'},
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%<br>Nilai: {point.y:.2f}M<br>Total: {point.total:.2f}M',
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        // enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%<br>{point.y:.2f}M/{point.total:.2f}M'
                    }
                }
            },
            series: [{
                // type: 'pie',
                name: 'Browser share',
                data: dt,
            }]
            
        });

        pmtRange.addEventListener('change', setData.bind(null, chart));

$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSellerDonut":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();


        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure' sstyle='height: 100px;'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'><span id='editor_target'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'>
          <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";

        $judul = "Penjualan YTD <small >(" . dtimeNow("d M Y") . ")</small>";

        $nilai_local = $content["penjualan"];
        $nilai_order = $content["order"];
        // $nilai_total = $content["penjualan_total"] * 1;
        // $nilai_total_f = number_format($nilai_total,2);

        // $nilai_project = $content["penjualan_projek"];
        // $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        // $test[]['anu']= 55;
        $test[] = array("ff", 55);
        $test[] = array("ft", 25);
        $test[] = array("xt", 15);
        // arrPrintHijau($test);
        $warna = array('#9c27b0', '#f50e0e', '#0b34fd', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#006400', '#FF69B4', '#FFF263', '#6AF9C4', '#FF1493');
        $warna_json = json_encode($warna, true);
        arrPrint($warna_json);
        // arrPrint($warna);
        foreach ($warna as $werno) {
            echo "<div style='background-color: $werno;float: left;width: 100px;align-content: center;'>$werno</div>";
        }
        echo "<hr>";
        $testing = json_encode($test);
        // $testing = json_encode($nilai_local);
        arrPrintPink($testing);
        // $strData .= "<script>
        //                 $('#target_persen').blur(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //
        //                 $('#target_save').click(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //             </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>     
        Highcharts.setOptions({
             // colors: ['#9c27b0','#f50e0e','#0b34fd','#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655','#006400','#FF69B4','#FFF263', '#6AF9C4','#FF1493']
             colors: $warna_json
        });
      
         var dt = $testing;
         var colors = Highcharts.getOptions().colors,
            categories = [
                'fire',
                'non fire',
            ],
            data = [
                {
                    y: 50,
                    color: colors[2],
                    drilldown: {
                        name: 'Chrome',
                        categories: [
                            'Chrome v97.0',
                            'Chrome v94.0',
                            'Chrome v93.0',
                            'Chrome v92.0',
                            'Chrome v91.0',
  
                        ],
                        data: [
                            30,
                            15,
                            5,
                            6,
                            4,
                          ]
                    }
                },
                {
                    y: 50,
                    color: colors[1],
                    drilldown: {
                        name: 'Safari',
                        categories: [
                            'Safari v15.3',
                            'Safari v15.2',
                            'Safari v15.1',
                            'Safari v15.0',
                            'Safari v14.1',
                            'Safari v14.0',
                        ],
                        data: [
                            5,
                            2,
                            6,
                            8,
                            10,
                            9
                        ]
                    }
                },

            ],
            
            browserData = [],
            versionsData = [],
            i,
            j,
            dataLen = data.length,
            drillDataLen,
            brightness;


    // Build the data arrays
    for (i = 0; i < dataLen; i += 1) {
    
        // add browser data
        browserData.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });
    
        // add version data
        drillDataLen = data[i].drilldown.data.length;
        for (j = 0; j < drillDataLen; j += 1) {
            brightness = 0.2 - (j / drillDataLen) / 5;
            versionsData.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                color: Highcharts.color(data[i].color).brighten(brightness).get()
            });
        }
    }
    
    // Create the chart
    Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Browser market share, January, 2022'
        },
        subtitle: {
            text: 'Source: <a href=\"http://statcounter.com\" target=\"_blank\">statcounter.com</a>'
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%']
            }
        },
        tooltip: {
            valueSuffix: '%'
        },
        series: [{
            name: 'Browsers',
            data: browserData,
            size: '60%',
            dataLabels: {
                formatter: function () {
                    return this.y > 5 ? this.point.name : null;
                },
                color: '#ffffff',
                distance: -30
            }
        }, {
            name: 'Versions',
            data: versionsData,
            size: '80%',
            innerSize: '60%',
            dataLabels: {
                formatter: function () {
                    // display only if larger than 1
                    return this.y > 1 ? '<b>' + this.point.name + ':</b> ' +
                        this.y + '%' : null;
                }
            },
            id: 'versions'
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 400
                },
                chartOptions: {
                    series: [{
                    }, {
                        id: 'versions',
                        dataLabels: {
                            enabled: false
                        }
                    }]
                }
            }]
        }
    });

        

        // pmtRange.addEventListener('change', setData.bind(null, chart));

        $('.highcharts-credits').remove();

         $('#open_editor_target').click(function() {
            // var nilai = $('#target_persen').val();
            
            // $('#editor_target').removeClass('hidden');
            $('#target_save').fadeIn().prop('disabled', false).val('Apply');
        });
    // $('#target_slider').fadeOut();
        </script>\n";
        $content_nav = "";
        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSalesDonut":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();


        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure' sstyle='height: 100px;'>";
        $strData .= "<div id='container_donut_$container'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'><span id='editor_target'></span></p>";
        $strData .= "<div class='range-slider hidden' id='target_slider'></div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";

        // $judul = "Penjualan YTD <small >(" . dtimeNow("d M Y") . ")</small>";

        $kategoriGedeAlias = $content["kategoriGedeAlias"];
        $kategoriCilikAlias = $content["kategoriCilikAlias"];
        $kategoriCilik = $content["kategoriCilik"];
        $dataCilik = $content["dataCilik"];
        $kategoriGede_0 = $content["kategoriGede"];

        $nilai_order = isset($content["order"]) ? $content["order"] : 0;
        $dataGede = $content["dataGede"];
        $totalGede = $content["totalGede"];
        $totalGede_f = number_format($totalGede, 2);
        // cekBiru($dataGede);
        // cekBiru($kategoriCilik);
        // cekBiru($dataCilik);
        // cekBiru($kategoriCilik);
        // cekBiru($kategoriCilikAlias);
        // cekBiru($kategoriGedeAlias);
        // cekBiru($kategoriGede);
        $kategoriGede = array();
        foreach ($kategoriGede_0 as $ky => $item) {
            $kategoriGede[] = $kategoriGedeAlias[$ky];
        }
        $kategoriGede_js = json_encode($kategoriGede);
        foreach ($dataCilik as $ky => $item) {
            if ($ky == 2) {
                $dtc_2[] = $item;
            } else {
                $dtc_01[] = $item;
            }
        }
        $dtc_2_js = str_replace('"', '', json_encode($dtc_2));
        $dtc_01_js = str_replace('"', '', json_encode($dtc_01));

        foreach ($kategoriCilik as $ky => $item) {
            // foreach ($kategoriCilikAlias as $ky => $item) {
            if ($ky == 2) {
                $ktc_2[] = $kategoriCilikAlias[$item];
            } else {
                $ktc_01[] = $kategoriCilikAlias[$item];
            }
        }
        $ktc_2_js = json_encode($ktc_2);
        $ktc_01_js = json_encode($ktc_01);
        // arrPrint($ktc_01);
        // arrPrint($dtc_01_js);
        // $nilai_project = $content["penjualan_projek"];
        // $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        // $test[]['anu']= 55;
        $test[] = array("ff", 55);
        $test[] = array("ft", 25);
        $test[] = array("xt", 15);
        // arrPrintHijau($test);
        $warna = array('#9c27b0', '#f50e0e', '#0b34fd', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#006400', '#FF69B4', '#FFF263', '#6AF9C4', '#FF1493', '#7c0000');
        $warna_json = json_encode($warna, true);
        // arrPrint($warna_json);
        // arrPrint($warna);
        // foreach ($warna as $wk => $werno) {
        //     echo "<div style='background-color: $werno;float: left;width: 100px;align-content: center;'>$werno  $wk</div>";
        // }
        // echo "<hr>";

        $testing = json_encode($test);
        $judul = "$title $totalGede_f" . "M";
        $str_periode = isset($periode) ? $periode : "";
        $sub_judul = "<small >$str_periode (" . formatTanggal($tgl_ini, "d M Y") . ")</small>";
        $date_now = dtimeNow('d M');
        $strData .= "<script>     
        Highcharts.setOptions({
             // colors: ['#9c27b0','#f50e0e','#0b34fd','#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655','#006400','#FF69B4','#FFF263', '#6AF9C4','#FF1493']
             colors: $warna_json
        });
              
         var colors = Highcharts.getOptions().colors,
            categories = $kategoriGede_js,
            data = [
                {
                    y: $dataGede[0],
                    color: colors[14],
                    drilldown: {                        
                        name: 'Chrome',
                        categories: $ktc_01_js,
                        data: $dtc_01_js
                    }
                },
                {
                    y: $dataGede[1],
                    color: colors[9],
                    drilldown: {
                        name: 'Safari',
                        categories: $ktc_2_js,
                        data: $dtc_2_js
                    }
                },

            ],
            
            browserData = [],
            versionsData = [],
            i,
            j,
            dataLen = data.length,
            drillDataLen,
            brightness;


    // Build the data arrays
    var i = 2;
    for (i = 0; i < dataLen; i += 1) {
    
        // add browser data
        browserData.push({
            name: categories[i],
            y: data[i].y,
            color: data[i].color
        });
    
        // add version data
        drillDataLen = data[i].drilldown.data.length;
        for (j = 0; j < drillDataLen; j += 1) {
            brightness = 0.2 - (j / drillDataLen) / 5;
            versionsData.push({
                name: data[i].drilldown.categories[j],
                y: data[i].drilldown.data[j],
                // color: Highcharts.color(data[i].color).brighten(brightness).get()
            });
        }
    }
    
    // Create the chart
    Highcharts.chart('container_donut_$container', {
        chart: {
            type: 'pie',
            // height: 300,
            // width: 200,
        },
        title: {
            text: '$judul',
            align: 'left'
        },
        subtitle: {
            text: '$sub_judul',
            align: 'left'
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
                dataLabels: {
                        // enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f}%<br>{point.y:.2f}M'
                    }
            }
        },
        tooltip: {
            // valueSuffix: '%',
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Nilai: {point.y:.2f}M<br>Total: {point.total:.2f}M',
        },
        series: [{
            name: 'Persentase',
            data: browserData,
            size: '60%',
            dataLabels: {
                 distance: -50,
                formatter: function () {
                    return this.y > 5 ? this.point.name : null;
                },
                color: '#ffffff',
            }
        }, {
            name: 'Persentase',
            data: versionsData,
            size: '80%',
            innerSize: '60%',
           
            dataLabels: {
                 // distance: -60,
                formatter: function () {
                    // display only if larger than 1
                    return this.y > 1 ? '<b>' + this.point.name + ':</b> ' +
                        this.y + '%' : null;
                }
            },
            id: 'versions'
        }],
        // responsive: {
        //     rules: [{
        //         condition: {
        //             // maxWidth: 400
        //         },
        //         chartOptions: {
        //             series: [{
        //             }, {
        //                 id: 'versions',
        //                 dataLabels: {
        //                     enabled: false
        //                 }
        //             }]
        //         }
        //     }]
        // }
    });

        

        // pmtRange.addEventListener('change', setData.bind(null, chart));

        $('.highcharts-credits').remove();

         $('#open_editor_target').click(function() {
            // var nilai = $('#target_persen').val();
            
            // $('#editor_target').removeClass('hidden');
            $('#target_save').fadeIn().prop('disabled', false).val('Apply');
        });
    // $('#target_slider').fadeOut();
        </script>\n";
        $content_nav = "";
        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewComparePerSales":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                // cekHijau($xLabel);
                // cekHijau($xData);
                if ("tahun" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                } else {
                    // $$xLabel = "[" . implode(",", $xData) . "]";
                    $$xLabel = json_encode($xData);
                    // $$xLabel = str_replace("\"","'", str_replace("}","]",str_replace("{","[",json_encode($xData))));
                }
            }
        }
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($tahun);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        // $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        // $target_tahun_ini = json_decode($target_tahun_now)[0];
        // $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        // $persen_selisih = ($selisih_target / $penjualan_tahun_lalu) * 100;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");

        $strData = "";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container'></div>";
        $strData .= "<p class='highcharts-description'></p>";

        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTarget?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);


        // arrPrintPink($penjualan_now);
        // arrPrintPink(json_decode($penjualan_now));
        $date_now = dtimeNow('d M');
        $strData .= "<script>
           
        var chart = Highcharts.chart('container', {
            exporting: {
                buttons: {
                    contextButton: {
                        enabled: false
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
            height: 700,
                        
            events: {
              load: function() {
                var yData = this.series[1].yData;
                const sum = yData.reduce((a, b) => a + b);
            
                this.yAxis[0].addPlotLine({
                  value: sum / yData.length,
                  // value: 600000000,
                  color: 'red',
                  dashStyle: 'longdash',
                  label: {
                    text: 'rata-rata penjualan',
                     style: {
                                        
                    	color: 'red',
                    	
                    }
                  }
                });
              }
            }
          },
          // chart: {
          //       type: 'column'
          //   },
          title: {
            text: 'PENJUALAN',
            align: 'left'
          },
          subtitle: {
             text: \"<div class='text-bold' id='open_editor_target' title='klik untuk merubah'>Target Pertumbuhan <link>$target_persen%</link> dari $penjualan_past_label</div><br><div class='text-bold'>&nbsp;</div><br><div class='text-bold'> </div>\",
            align: 'left'
          },
          xAxis: {
                type: 'category',
                labels: {
                        rotation: -45,
                        style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
        }
          },
          yAxis: [
            { // Primary yAxis
                labels: {
                  format: '{text}',
                  style: {
                    color: Highcharts.getOptions().colors[1]
                  }
                },
                title: {
                  text: '',
                  style: {
                    color: Highcharts.getOptions().colors[0]
                  }
                },
                // opposite: true
            },
            { // Secondary yAxis
    //            type: 'linear',
    //            max: ymax,
    //            min: ymin,
                tickInterval: 1000,
                gridLineWidth: 1,
                plotLines: [{
                    // color: '#C0C0C0',
                    width: 3,
                    value: 100000,
                    color: 'green',
                    dashStyle: 'shortdash',

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
            },
            // plotLines: [{
            //     value: minRate,
            //     color: 'green',
            //     dashStyle: 'shortdash',
            //     width: 2,
            //     label: {
            //         text: 'Last quarter minimum'
            //     }
            // }, {
            //     value: maxRate,
            //     color: 'red',
            //     dashStyle: 'shortdash',
            //     width: 2,
            //     label: {
            //         text: 'Last quarter maximum'
            //     }
            // }]
          ],
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
                name: $penjualan_past_label,
                type: 'column',
                color: 'red',
                // yAxis: 1,
                // stacking: 'normal',
                data: $penjualan_past,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
              {
                name: $penjualan_now_label,
                type: 'column',
                color: 'blue',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                  // valueSuffix: ' M'
                }
              },
               {
                name: $target_penjualan_now_label,
                type: 'column',
                color: '#ccc',
                // yAxis: 1,
                // stacking: 'normal',
                // pointPlacement: -0.1,
                data: $target_penjualan_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}',
                     // rotation: -90,
                    // inside: true
                    // style: {
                    //     fontSize: '1.1em'
                    // }
                }],
                tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
              {
                name: $target_tahun_now_label,
                type: 'column',
                color: '#333',
                data: $target_tahun_now,
                dataLabels: [{
                    enabled: true,
                    format: '{point.y:.2f}<br>$target_persen%',
                }],
               tooltip: {
                    pointFormat:'<span style=\"color:{point.color}\">\u25CF</span> <b>{series.name}</b>: {point.y:.2f}<br>',
                }
              },
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



$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----


        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewEfisiensiBom":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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
                $xLabel2 = $xLabel . "_2";
                // cekHijau($xLabel);
                // cekHijau($xData);
                // if ("tahun" == $xLabel) {
                //     $$xLabel = "['" . implode("','", $xData) . "']";
                // }
                // else {
                $$xLabel = "[$xData]";
                $$xLabel2 = $xData;
                // }
            }
        }

        $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        // $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        // $target_tahun_ini = json_decode($target_tahun_now)[0];
        // $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        // $persen_selisih = ($selisih_target / $penjualan_tahun_lalu) * 100;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");

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
        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_pro'></div>";
        //         $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label
        // <input type='button' id='target_save' value='0' disabled style='display: none;'></span></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                
        var chart = Highcharts.chart('container_pro', {
             chart: {
                    type: 'column'
                },
                title: {
                    text: '$title',
                    align: 'left'
                },
                subtitle: {
                    text: '$subTitle',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Perbandingan data'],
                    crosshair: true
                },
                yAxis: {
                    title: {
                        useHTML: null,
                        text: '',
                        offset: 0,
                    }
                },
                tooltip: {
                    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                        '<td style=\"padding:0\"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}',
                                align: 'right',
                                color: '#FFFFFF',
                                x: -10
                            },
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'BOM',
                    data: $bom,
                    color: 'red',                              
                }, {
                    name: 'realisasi',
                    data: $realisasi,
                    color: 'blue',
            
                }, {
                    name: 'efisiensi',
                    data: $efisiensi,
                    color: 'green',
                    dataLabels: [{
                        enabled: true,
                        format: '{point.y:.2f}<br>$efisiensi_persen_2_f%',
                    }],
                }, {
                    name: 'prediksi',
                    data: $prediksi,
                    color: '#939393',
                    
            
                }],
                legend: {
                    layout: 'horizontal', // default
                    // itemDistance: 50
                },
                
        });


$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewEfisiensiBomThn":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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
        // arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                $xLabel2 = $xLabel . "_2";
                // cekHijau($xLabel);
                // cekHijau($xData);
                // if ("tahun" == $xLabel) {
                //     $$xLabel = "['" . implode("','", $xData) . "']";
                // }
                // else {
                $$xLabel = "[$xData]";
                $$xLabel2 = $xData;
                // }
            }
        }

        $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);

        // arrPrintHijau(json_decode($target_tahun_now)[0]);
        // $penjualan_tahun_lalu = json_decode($penjualan_past)[0];
        // $target_tahun_ini = json_decode($target_tahun_now)[0];
        // $selisih_target = $target_tahun_ini - $penjualan_tahun_lalu;
        // $persen_selisih = ($selisih_target / $penjualan_tahun_lalu) * 100;
        // cekHere("$selisih_target == $target_tahun_ini - $penjualan_tahun_lalu; $persen_selisih");

        $strData = "";
        $strData .= "<style>
            .col-md-2 {padding-right: 0 !important;}
            .col-xl-2 {padding-right: 5px !important;}
            .col-lg-4 {padding-right: 5px !important;}
        </style>";
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
        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_pro_thn_$keyBiaya'></div>";
        //         $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label
        // <input type='button' id='target_save' value='0' disabled style='display: none;'></span></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                
        var chart = Highcharts.chart('container_pro_thn_$keyBiaya', {
             chart: {
                    type: 'column'
                },
                title: {
                    text: '$title',
                    align: 'left'
                },
                subtitle: {
                    text: '$subTitle',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Perbandingan data'],
                    crosshair: true,
                },
                yAxis: {
                    title: {
                        useHTML: null,
                        text: '',
                        offset: 0,                       
                    },
                     formatter: function() {
                        let value = this.total;
                
                        if (value > 1000) {
                          return Math.floor(value / 1000) + ' k'
                        } else {
                          return value
                        }
                      }
                },
                tooltip: {
                    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                        '<td style=\"padding:0\"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.2f}',
                                style: {
                                        fontSize: '8px'
                                    },
                                align: 'right',
                                color: '#FFFFFF',
                                // x: -10
                            },
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'BOM',
                    data: $bom,
                    color: 'red',                              
                }, {
                    name: 'Realisasi',
                    data: $realisasi,
                    color: 'blue',
            
                }, {
                    name: 'Efisiensi',
                    data: $efisiensi,
                    color: 'green',
                    // dataLabels: [{
                    //     enabled: true,
                    //     format: '{point.y:.2f}<br>$efisiensi_persen_2_f%',
                    // }],
                }, {
                    name: 'Prediktif efisiensi',
                    data: $prediksi,
                    color: '#939393',
                    
            
                }],
                legend: {
                    layout: 'horizontal', // default
                    // itemDistance: 50
                },
                
        });


$('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSebaran":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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


        // arrPrint($content);
        if (sizeof($content) > 0) {
            // foreach ($content as $xLabel => $xData) {
            foreach ($content as $xDatas) {
                // $xLabel2 = $xLabel . "_2";
                // arrPrint($xDatas);
                $pId = $xDatas['extern_id'];
                $pNama = $xDatas['extern_nama'];
                $harga = $xDatas['harga'];
                $harga_netto = $xDatas['harga_netto'];

                $selisih = $harga_netto - $harga;
                $margin_beli = (($harga_netto / $harga) - 1) * 100;
                $margin_jual = (1 - ($harga / $harga_netto)) * 100;
                // $margin = (($harga_netto / $harga) - 1) * 100;
                $margin = $harga_netto < $harga ? $margin_beli : $margin_jual;

                // cekHijau("$pId :: mj $margin_jual || mb $margin_beli");
                if ($margin <= 500 && $margin > -100) {

                    // $hargas[] = $margin;
                    $hargas[] = $margin;
                    $nama[] = $pNama;

                    $selisihs[] = $selisih;
                    $produkId[] = $pId;
                }

                // $$xLabel = "[$xData]";
                // $$xLabel2 = $xData;
                // }
            }
        }
        // $test = array(
        //     array(1, "ii"),
        //     5
        // );
        // $jes = json_encode($test);
        // arrPrintPink($test);
        // arrPrintPink($jes);
        // arrPrintPink($hargas);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // asort($hargas);
        $sumHarga = 0;
        foreach ($hargas as $hrg) {
            $sumHarga += $hrg;
        }
        $jml_data = sizeof($hargas);
        $avg = $sumHarga / $jml_data;
        // $data_sebaran_e = json_encode($hargas, JSON_NUMERIC_CHECK);
        $data_sebaran_e = json_encode($hargas);
        $data_label_e = json_encode($nama);

        $minimum = min($hargas);
        $min_data_2 = $minimum - (0.2 * $minimum);
        $min_data = (0.2 * $minimum);

        $maximum = max($hargas);
        $max_data_2 = $maximum - (0.2 * $maximum);
        $max_data = (0.2 * $maximum);
        // arrPrintKuning($hargas);
        // arrPrintHijau($produkId);
        asort($hargas);
        // arrPrintKuning($data_label_e);
        // arrPrintKuning($data_sebaran_e);
        // arrPrintKuning(min($hargas));
        // arrPrintKuning(max($hargas));


        $batas_data = 40;
        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai awal
         * ----------------------------------------------------------------------------*/
        $first_datas = (array_slice($hargas, 0, floor($jml_data * ($batas_data / 100))));
        $avg_min = array_sum($first_datas) / count($first_datas);

        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai akhir
         * ----------------------------------------------------------------------------*/
        $last_datas = (array_slice($hargas, floor($jml_data * ($batas_data / 100)) * -1));
        $avg_max = array_sum($last_datas) / count($last_datas);
        // cekHijau("avg:: $avg up:: $avg_max min:: $avg_min");
        // arrPrintKuning($hargas);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);
        // $data_sebaran_e =
        $strData = "";
        $strData .= "<style>
            // .col-md-2 {padding-right: 0 !important;}
            .col-xl-2 {padding-right: 5px !important;}
            // .col-lg-4 {padding-right: 5px !important;}
        </style>";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='containerSebaran'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        // $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        // $strData .= "<script>
        //                 $('#target_persen').blur(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //
        //                 $('#target_save').click(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //             </script>";

        // arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $avg_f = number_format($avg, 2);
        $avg_max_f = number_format($avg_max, 2);
        $avg_min_f = number_format($avg_min, 2);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                var data_label = $data_label_e;
                console.log(data_label);
                Highcharts.chart('containerSebaran', {
                    title: {
                        text: '$title',
                        align: 'left'
                    },
                    subtitle: {
                        text: '$subTitle',
                        align: 'left'
                    },
                    xAxis: {
                        // min: 0,
                        // max: 220
                        data: $data_label_e
                    },
                    yAxis: {
                        title: {
                        // useHTML: null,
                        text: '',
                        offset: 0,                       
                    },
                        // min: $min_data,
                        // visible: false,
                        plotLines: [
                        //     {
                        //   value: [$max_data_2],
                        //   color: 'blue',
                        //  
                        //   width: 1,
                        //   label: {
                        //     text: '$max_data_2 uper'
                        //   }
                        // },
                        {
                          value: [$avg_max],
                          color: 'blue',
                          dashStyle: 'dash',
                          width: 1,
                          label: {
                            text: '$batas_data% profit tertinggi $avg_max_f%',
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1.1em'
                            }
                          }
                        },
                        {
                          value: [$avg],
                          color: 'red',
                          
                          width: 1,
                          label: {
                            text: 'Rata-rata $avg_f%',
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1.1em'
                            }                            
                          }
                        },
                        {
                          value: [$avg_min],
                          color: 'green',
                          dashStyle: 'dash',
                          width: 1,
                          label: {
                            text: '$batas_data% profit terendah $avg_min_f%',
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1.1em'
                            }                            
                          }
                        },
                        // {
                        //   value: [$min_data_2],
                        //   color: 'green',
                        //   width: 1,
                        //   label: {
                        //     text: '$min_data_2 under'
                        //   }
                        // }
                        ]
                    },
                    tooltip: {
                        // headerFormat: '{data.x} {point.x} <br>',
                        // pointFormat: '{point.y}',                        
                        // shared: true,
                        formatter: function () {
                           // var s = formatNumber(10000);
                            return 'Produk <b>' + data_label[this.x] + '</b> margin <b>' + Number(this.y).toFixed(2) + '</b>%';
                            // return  s;
                        },
                        valueDecimals: 2,
                        // headerFormat: '<b>{point.point.name}</b><br>',
                    },
                    series: [
                    //     {
                    //     type: 'line',
                    //     name: 'Regression Line',
                    //     data: [[0, 1.11], [300, 71259420.34375]],
                    //     marker: {
                    //         enabled: false
                    //     },
                    //     states: {
                    //         hover: {
                    //             lineWidth: 0
                    //         }
                    //     },
                    //     enableMouseTracking: false
                    // },
                     {
                        type: 'scatter',
                        name: 'Margin',
                         color: 'brown',
                        // name: $data_label_e,
                        // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                        data: $data_sebaran_e,
                        // data: data,
                        marker: {
                            radius: 3
                        }
                    }]
                });
        


                $('.highcharts-credits').remove();
                
                 $('#open_editor_target').click(function() {
                    // var nilai = $('#target_persen').val();
                    
                    // $('#editor_target').removeClass('hidden');
                    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
                });
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSebaranPenjualan":

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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


        // arrPrint($content);
        if (sizeof($content) > 0) {
            // foreach ($content as $xLabel => $xData) {
            $dt_ini = $content["thn_ini"];
            $dt_lalu = $content["thn_lalu"];
            $dt_produks = $content["produk"];

            $sum_saldo_kirim_thn_lalu = 0;
            $sum_saldo_kirim_thn_ini = 0;
            $sum_pertumbuhan_nilai = 0;
            $ix = -1;
            foreach ($dt_produks as $pId => $pNama) {
                $ix++;
                $dt_thn_ini = isset($dt_ini[$pId]) ? $dt_ini[$pId] : array();
                $saldo_kirim_thn_ini = isset($dt_thn_ini->saldo_kirim) ? $dt_thn_ini->saldo_kirim : 0;

                $dt_thn_lalu = isset($dt_lalu[$pId]) ? $dt_lalu[$pId] : array();
                $saldo_kirim_thn_lalu = isset($dt_thn_lalu->saldo_kirim) ? $dt_thn_lalu->saldo_kirim : 0;

                // $pertumbuhan_persen = (($saldo_kirim_thn_ini / $saldo_kirim_thn_lalu) - 1) *100;

                $pertumbuhan_nilai = $saldo_kirim_thn_ini - $saldo_kirim_thn_lalu;
                $pertumbuhan_persen = $saldo_kirim_thn_lalu != 0 ? ($pertumbuhan_nilai / $saldo_kirim_thn_lalu) * 100 : 500;

                if ($saldo_kirim_thn_lalu > 0) {

                    if ($pertumbuhan_persen <= 1500 && $pertumbuhan_persen > -101) {
                        $pertumbuhan_persen_f = $pertumbuhan_persen != 0 ? number_format($pertumbuhan_persen, 2) : 0;

                        // $imbol = "triangle";
                        // $imbol = "circle";
                        if ($pertumbuhan_persen <= -80) {
                            $warna = "red";
                            $rad_size = 3;
                            $imbol = "triangle-down";
                            $pertumbuhan_persen = -80;
                        } elseif ($pertumbuhan_persen >= 175) {
                            $warna = "blue";
                            $rad_size = 3;
                            // $imbol = "circle";
                            $imbol = "triangle";
                            $pertumbuhan_persen = 175;
                        } else {
                            $warna = "green";
                            $rad_size = 3;
                            $imbol = "circle";
                        }

                        $persen_bdy[] = array(
                            "y" => $pertumbuhan_persen,
                            "color" => "$warna",
                            "marker" => array(
                                "radius" => $rad_size,
                                "symbol" => $imbol
                            ),
                        );
                        // }
                        $persen_all[] = $pertumbuhan_persen;
                        $nilai[] = $pertumbuhan_nilai / 1000000;
                        $nilai_penjualan[] = $saldo_kirim_thn_ini / 1000000;

                        $produkId[] = $pId;
                        $nama[] = $pNama;
                    }
                }

                // $produkId[] = $pId;
                // $nama[] = $pNama;

                $sum_saldo_kirim_thn_ini += $saldo_kirim_thn_ini;
                $sum_saldo_kirim_thn_lalu += $saldo_kirim_thn_lalu;
                $sum_pertumbuhan_nilai += $pertumbuhan_nilai;
            }
            $hargas = $persen_bdy;

        }
        // $test = array(
        //     array(1, "ii"),
        //     5
        // );
        // $jes = json_encode($test);
        // arrPrintPink($produkId);
        // arrPrintPink($jes);
        // arrPrintPink($nilai);
        // arrPrintPink($persen_btm);
        // arrPrintPink($hargas);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // matiHere(__LINE__);
        // cekBiru("$sum_pertumbuhan_nilai || $sum_saldo_kirim_thn_ini || $sum_saldo_kirim_thn_lalu ");
        // asort($hargas);
        $avg_weighted = ($sum_pertumbuhan_nilai / $sum_saldo_kirim_thn_lalu) * 100;

        $sumHarga = 0;
        foreach ($persen_all as $hrg) {
            $sumHarga += $hrg;
        }
        $jml_data = sizeof($persen_all);
        $avg = $sumHarga / $jml_data;
        // $data_sebaran_e = json_encode($hargas, JSON_NUMERIC_CHECK);
        // $data_sebaran_btm_e = json_encode($persen_btm);
        $data_sebaran_e = json_encode($hargas);
        $data_label_e = json_encode($nama);
        $data_nilai_e = json_encode($nilai);
        $data_penjualan_thn_ini_e = json_encode($nilai_penjualan);

        $minimum = min($hargas);
        // $min_data_2 = $minimum - (0.2 * $minimum);
        $min_data_2 = 0;
        // $min_data = (0.2 * $minimum);
        $min_data = (0.2 * 1);

        $maximum = max($hargas);
        // $max_data_2 = $maximum - (0.2 * $maximum);
        $max_data_2 = 100;
        // $max_data = (0.2 * $maximum);
        $max_data = (0.2 * 1);
        // arrPrintKuning($hargas);
        // arrPrintHijau($produkId);
        asort($hargas);
        // arrPrintKuning($data_label_e);
        // arrPrintKuning($data_sebaran_e);
        // arrPrintKuning(min($hargas));
        // arrPrintKuning(max($hargas));


        $batas_data = 40;
        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai awal
         * ----------------------------------------------------------------------------*/
        $first_datas = (array_slice($hargas, 0, floor($jml_data * ($batas_data / 100))));
        $avg_min = count($first_datas) > 0 ? array_sum($first_datas) / count($first_datas) : 0;

        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai akhir
         * ----------------------------------------------------------------------------*/
        $last_datas = (array_slice($hargas, floor($jml_data * ($batas_data / 100)) * -1));
        $avg_max = array_sum($last_datas) / count($last_datas);
        // cekHijau("avg:: $avg up:: $avg_max min:: $avg_min");
        // arrPrintKuning($hargas);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);
        // $data_sebaran_e =
        $strData = "";
        $strData .= "<style>
            // .col-md-2 {padding-right: 0 !important;}
            .col-xl-2 {padding-right: 5px !important;}
            // .col-lg-4 {padding-right: 5px !important;}
        </style>";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='containerSebaranPenjualan'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        // $strData .= "<button id=\"button\" class=\"autocompare\">Add plot line</button>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        // $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        // $strData .= "<script>
        //                 $('#target_persen').blur(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //
        //                 $('#target_save').click(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //             </script>";

        // arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $button = "button";
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                var data_label = $data_label_e;
                var data_nilai = $data_nilai_e;
                var data_penjualan = $data_penjualan_thn_ini_e;
                
                Highcharts.chart('containerSebaranPenjualan', {
                    title: {
                        text: '$title',
                        align: 'left'
                    },
                    subtitle: {
                        text: '$subTitle',
                        align: 'left'
                    },
                    xAxis: {
                        // min: 0,
                        // max: 220
                        data: $data_label_e
                    },
                    yAxis: {
                        title: {
                        // useHTML: null,
                        text: '',
                        offset: 0,                       
                    },
                        // min: $min_data,
                        // visible: false,
                        plotLines: [
                            // {
                            //   value: [$avg_max],
                            //   color: 'blue',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit tertinggi'
                            //   }
                            // },
                            {
                              value: [$avg],
                              color: 'red',
                            
                              width: 1,
                              label: {
                                text: 'AVG simpel <b>' + $avg.toFixed(2) + '</b>%',
                                style: {
                                    fontWeight: 'normal',
                                    fontSize: '1.2em'
                                }                                
                              }
                            },
                            {
                              value: [$avg_weighted],
                              color: 'red',
                             dashStyle: 'dash',
                              width: 1,
                              label: {
                                text: 'AVG weighted <b>' + $avg_weighted.toFixed(2) + '</b>%',
                                style: {
                                    fontWeight: 'normal',
                                    fontSize: '1.2em'
                                }                                
                              }
                            },
                            // {
                            //   value: [$avg_min],
                            //   color: 'green',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit terendah'
                            //   }
                            // },
                        ]
                    },
                    tooltip: {
                        // headerFormat: '{data.x} {point.x} <br>',
                        // pointFormat: '{point.y}',                        
                        // shared: true,
                        formatter: function () {
                           var profit_persen = Number(this.y).toFixed(2);
                           if(profit_persen == 0){
                               var profit_persen = 0;
                           }
                           var profit_nilai = Number(data_nilai[this.x]).toFixed(2);
                           var penjualan_nilai = Number(data_penjualan[this.x]).toFixed(2);
                           
                           //  return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + Number(this.y).toFixed(2) + '</b>% nilai kenaikan<b>' + Number(data_nilai[this.x]).toFixed(2) + '</b>';
                            return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + profit_persen + '</b>% nilai perubahan <b>' + profit_nilai + '</b>J ' + 'penjualan <b>' + penjualan_nilai +'</b>J';
                            // return  s;
                        },
                        valueDecimals: 2,
                        // headerFormat: '<b>{point.point.name}</b><br>',
                    },
                     chart: {
                        events: {
                          load: function() {
                            const chart = this,
                              data = chart.series[0].data;
                    
                            data.map((element) => {
                              // if (element.y > -100) {
                              //   element.update({
                              //     color: 'red',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   })
                              // }
                              // if (element.y > (-100) && element.y < (-80)){
                              //   element.update({
                              //     color: 'pink',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   }) 
                              // }                              
                            });
                            
                            
                          
                          }
                      }
                    },
                    series: [
                         {
                            type: 'scatter',
                            name: 'Profit',
                            
                             color: 'green',
                            // name: $data_label_e,
                            // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                            data: $data_sebaran_e,
                            // data: data,
                            marker: {
                                radius: 3
                            }
                        },
                        //  {
                        //     type: 'scatter',
                        //     name: 'pertumbuhan terendah',
                        //    
                        //      color: 'blue',
                        //     // name: $data_label_e,
                        //     // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                        //     data: $data_sebaran_e,
                        //     // data: data,
                        //     marker: {
                        //         radius: 2
                        //     }
                        // }
                        
                        {
                            showInLegend: true,
                            type: 'scatter',
                            name: '= -100%',
                            color: 'red',
                            marker: {
                                symbol: 'triangle-down'    
                            }                            
                        },
                        {
                            showInLegend: true,
                            type: 'scatter',
                            name: '> 200%',
                            color: 'blue',
                            marker: {
                                symbol: 'triangle'    
                            }                            
                        },                            
                    ]
                });
                
                // the button action
                // var hasPlotLine = false,
                //     $button = $('#button');
                //
                // $button.click(function () {
                //     if (!hasPlotLine) {
                //         chart.yAxis[0].addPlotLine({
                //             value: [$avg],
                //             color: 'red',
                //             width: 2,
                //             id: 'plot-line-1'
                //         });
                //         $button.html('Remove plot line');
                //     } else {
                //         chart.yAxis[0].removePlotLine('plot-line-1');
                //         $button.html('Add plot line');
                //     }
                //     hasPlotLine = !hasPlotLine;
                // });



                $('.highcharts-credits').remove();
                
                 $('#open_editor_target').click(function() {
                    // var nilai = $('#target_persen').val();
                    
                    // $('#editor_target').removeClass('hidden');
                    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
                });
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSebaranPenjualanAbsolute":

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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


        // arrPrint($content);
        if (sizeof($content) > 0) {
            // foreach ($content as $xLabel => $xData) {
            $dt_ini = $content["thn_ini"];
            $dt_lalu = $content["thn_lalu"];
            $dt_produks = $content["produk"];

            $sum_saldo_kirim_thn_lalu = 0;
            $sum_saldo_kirim_thn_ini = 0;
            $sum_pertumbuhan_nilai = 0;
            $ix = -1;
            foreach ($dt_produks as $pId => $pNama) {
                $ix++;
                $dt_thn_ini = isset($dt_ini[$pId]) ? $dt_ini[$pId] : array();
                $saldo_kirim_thn_ini = isset($dt_thn_ini->saldo_kirim) ? $dt_thn_ini->saldo_kirim : 0;

                $dt_thn_lalu = isset($dt_lalu[$pId]) ? $dt_lalu[$pId] : array();
                $saldo_kirim_thn_lalu = isset($dt_thn_lalu->saldo_kirim) ? $dt_thn_lalu->saldo_kirim : 0;

                // $pertumbuhan_persen = (($saldo_kirim_thn_ini / $saldo_kirim_thn_lalu) - 1) *100;

                $pertumbuhan_nilai = $saldo_kirim_thn_ini - $saldo_kirim_thn_lalu;
                $pertumbuhan_nilai_j = $pertumbuhan_nilai / 1000000;
                $pertumbuhan_persen = $saldo_kirim_thn_lalu != 0 ? ($pertumbuhan_nilai / $saldo_kirim_thn_lalu) * 100 : 500;

                if ($saldo_kirim_thn_lalu > 0) {

                    if ($pertumbuhan_nilai_j <= 200 && $pertumbuhan_nilai_j > -200) {
                        $pertumbuhan_persen_f = $pertumbuhan_persen != 0 ? number_format($pertumbuhan_persen, 2) : 0;

                        if ($pertumbuhan_nilai_j < 0) {
                            $warna = "red";
                            $rad_size = 2;
                        } elseif ($pertumbuhan_nilai_j >= 200) {
                            $warna = "blue";
                            $rad_size = 4;
                        } else {
                            $warna = "green";
                            $rad_size = 3;
                        }


                        $persen_bdy[] = array(
                            "y" => $pertumbuhan_nilai_j,
                            "color" => "$warna",
                            "marker" => array(
                                "radius" => $rad_size,
                                // "symbol" => "triangle"
                            ),
                        );
                        // }
                        $persen_all[] = $pertumbuhan_nilai_j;
                        $nilai[] = $pertumbuhan_persen;
                        $nilai_penjualan[] = $saldo_kirim_thn_ini / 1000000;
                    }
                }

                $produkId[] = $pId;
                $nama[] = $pNama;

                $sum_saldo_kirim_thn_ini += $saldo_kirim_thn_ini;
                $sum_saldo_kirim_thn_lalu += $saldo_kirim_thn_lalu;
                $sum_pertumbuhan_nilai += $pertumbuhan_nilai;
            }
            $hargas = $persen_bdy;

        }
        // $test = array(
        //     array(1, "ii"),
        //     5
        // );
        // $jes = json_encode($test);
        // arrPrintPink($produkId);
        // arrPrintPink($jes);
        // arrPrintPink($nilai);
        // arrPrintPink($persen_btm);
        // arrPrintPink($hargas);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // matiHere(__LINE__);
        // cekBiru("$sum_pertumbuhan_nilai || $sum_saldo_kirim_thn_ini || $sum_saldo_kirim_thn_lalu ");
        // asort($hargas);
        $avg_weighted = ($sum_pertumbuhan_nilai / $sum_saldo_kirim_thn_lalu) * 100;

        $sumHarga = 0;
        foreach ($persen_all as $hrg) {
            $sumHarga += $hrg;
        }
        $jml_data = sizeof($persen_all);
        $avg = $sumHarga / $jml_data;
        // $data_sebaran_e = json_encode($hargas, JSON_NUMERIC_CHECK);
        // $data_sebaran_btm_e = json_encode($persen_btm);
        $data_sebaran_e = json_encode($hargas);
        $data_label_e = json_encode($nama);
        $data_nilai_e = json_encode($nilai);
        $data_penjualan_thn_ini_e = json_encode($nilai_penjualan);

        $minimum = min($hargas);
        // $min_data_2 = $minimum - (0.2 * $minimum);
        $min_data_2 = 0;
        // $min_data = (0.2 * $minimum);
        $min_data = (0.2 * 1);

        $maximum = max($hargas);
        // $max_data_2 = $maximum - (0.2 * $maximum);
        $max_data_2 = 100;
        // $max_data = (0.2 * $maximum);
        $max_data = (0.2 * 1);
        // arrPrintKuning($hargas);
        // arrPrintHijau($produkId);
        asort($hargas);
        // arrPrintKuning($data_label_e);
        // arrPrintKuning($data_sebaran_e);
        // arrPrintKuning(min($hargas));
        // arrPrintKuning(max($hargas));


        $batas_data = 40;
        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai awal
         * ----------------------------------------------------------------------------*/
        $first_datas = (array_slice($hargas, 0, floor($jml_data * ($batas_data / 100))));
        $avg_min = count($first_datas) > 0 ? array_sum($first_datas) / count($first_datas) : 0;

        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai akhir
         * ----------------------------------------------------------------------------*/
        $last_datas = (array_slice($hargas, floor($jml_data * ($batas_data / 100)) * -1));
        $avg_max = array_sum($last_datas) / count($last_datas);
        // cekHijau("avg:: $avg up:: $avg_max min:: $avg_min");
        // arrPrintKuning($hargas);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);
        // $data_sebaran_e =
        $strData = "";
        $strData .= "<style>
            // .col-md-2 {padding-right: 0 !important;}
            .col-xl-2 {padding-right: 5px !important;}
            // .col-lg-4 {padding-right: 5px !important;}
        </style>";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='containerSebaranPenjualan'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        // $strData .= "<button id=\"button\" class=\"autocompare\">Add plot line</button>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        // $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        // $strData .= "<script>
        //                 $('#target_persen').blur(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //
        //                 $('#target_save').click(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //             </script>";

        // arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $button = "button";
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                var data_label = $data_label_e;
                var data_nilai = $data_nilai_e;
                var data_penjualan = $data_penjualan_thn_ini_e;
                
                Highcharts.chart('containerSebaranPenjualan', {
                    title: {
                        text: '$title',
                        align: 'left'
                    },
                    subtitle: {
                        text: '$subTitle',
                        align: 'left'
                    },
                    xAxis: {
                        // min: 0,
                        // max: 220
                        data: $data_label_e
                    },
                    yAxis: {
                        title: {
                        // useHTML: null,
                        text: '',
                        offset: 0,                       
                    },
                        // min: $min_data,
                        // visible: false,
                        plotLines: [
                            // {
                            //   value: [$avg_max],
                            //   color: 'blue',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit tertinggi'
                            //   }
                            // },
                            // {
                            //   value: [$avg],
                            //   color: 'red',
                            // 
                            //   width: 1,
                            //   label: {
                            //     text: 'AVG simpel <b>' + $avg.toFixed(2) + '</b>%'
                            //   }
                            // },
                            // {
                            //   value: [$avg_weighted],
                            //   color: 'red',
                            //  dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: 'AVG weighted <b>' + $avg_weighted.toFixed(2) + '</b>%'
                            //   }
                            // },
                            // {
                            //   value: [$avg_min],
                            //   color: 'green',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit terendah'
                            //   }
                            // },
                        ]
                    },
                    tooltip: {
                        // headerFormat: '{data.x} {point.x} <br>',
                        // pointFormat: '{point.y}',                        
                        // shared: true,
                        formatter: function () {
                           var profit_persen = Number(this.y).toFixed(2);
                           if(profit_persen == 0){
                               var profit_persen = 0;
                           }
                           var profit_nilai = Number(data_nilai[this.x]).toFixed(2);
                           var penjualan_nilai = Number(data_penjualan[this.x]).toFixed(2);
                           
                           //  return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + Number(this.y).toFixed(2) + '</b>% nilai kenaikan<b>' + Number(data_nilai[this.x]).toFixed(2) + '</b>';
                            return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + profit_persen + '</b>J ';
                            // return  s;
                        },
                        valueDecimals: 2,
                        // headerFormat: '<b>{point.point.name}</b><br>',
                    },
                     chart: {
                        events: {
                          load: function() {
                            const chart = this,
                              data = chart.series[0].data;
                    
                            data.map((element) => {
                              // if (element.y > -100) {
                              //   element.update({
                              //     color: 'red',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   })
                              // }
                              // if (element.y > (-100) && element.y < (-80)){
                              //   element.update({
                              //     color: 'pink',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   }) 
                              // }                              
                            });
                            
                            
                          
                          }
                      }
                    },
                    series: [
                         {
                            type: 'column',
                            name: 'Profit',
                            
                             color: 'green',
                            // name: $data_label_e,
                            // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                            data: $data_sebaran_e,
                            // data: data,
                            marker: {
                                radius: 3
                            }
                        },
                        //  {
                        //     type: 'scatter',
                        //     name: 'pertumbuhan terendah',
                        //    
                        //      color: 'blue',
                        //     // name: $data_label_e,
                        //     // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                        //     data: $data_sebaran_e,
                        //     // data: data,
                        //     marker: {
                        //         radius: 2
                        //     }
                        // }
                        
                        {
                            showInLegend: true,
                            type: 'scatter',
                            name: 'Minus',
                            color: 'red',
                            marker: {
                                symbol: 'circle'    
                            }                            
                        },
                        // {
                        //     showInLegend: true,
                        //     type: 'scatter',
                        //     name: '> 200%',
                        //     color: 'blue',
                        //     marker: {
                        //         symbol: 'circle'    
                        //     }                            
                        // },                            
                    ]
                });
                
                // the button action
                // var hasPlotLine = false,
                //     $button = $('#button');
                //
                // $button.click(function () {
                //     if (!hasPlotLine) {
                //         chart.yAxis[0].addPlotLine({
                //             value: [$avg],
                //             color: 'red',
                //             width: 2,
                //             id: 'plot-line-1'
                //         });
                //         $button.html('Remove plot line');
                //     } else {
                //         chart.yAxis[0].removePlotLine('plot-line-1');
                //         $button.html('Add plot line');
                //     }
                //     hasPlotLine = !hasPlotLine;
                // });



                $('.highcharts-credits').remove();
                
                 $('#open_editor_target').click(function() {
                    // var nilai = $('#target_persen').val();
                    
                    // $('#editor_target').removeClass('hidden');
                    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
                });
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewSebaranProfitMargin":

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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


        // arrPrint($content);
        if (sizeof($content) > 0) {
            // foreach ($content as $xLabel => $xData) {
            $dt_ini = $content["thn_ini"];
            $dt_lalu = $content["thn_lalu"];
            $dt_produks = $content["produk"];

            $sum_saldo_kirim_thn_lalu = 0;
            $sum_saldo_kirim_thn_ini = 0;
            $sum_pertumbuhan_nilai = 0;
            $ix = -1;
            foreach ($dt_produks as $pId => $pNama) {
                $ix++;
                cekHijau("pid $pId");
                // arrPrintHijau($pembelian_data);
                $produk_hpp = isset($pembelian_data[$pId]) ? $pembelian_data[$pId]["harga"] : 0;


                $dt_thn_ini = isset($dt_ini[$pId]) ? $dt_ini[$pId] : array();
                $saldo_kirim_thn_ini = isset($dt_thn_ini->saldo_kirim) ? $dt_thn_ini->saldo_kirim : 0;

                $dt_thn_lalu = isset($dt_lalu[$pId]) ? $dt_lalu[$pId] : array();
                $saldo_kirim_thn_lalu = isset($dt_thn_lalu->saldo_kirim) ? $dt_thn_lalu->saldo_kirim : 0;

                cekMerah("$produk_hpp || $saldo_kirim_thn_ini");
                break;
                // $pertumbuhan_persen = (($saldo_kirim_thn_ini / $saldo_kirim_thn_lalu) - 1) *100;

                $pertumbuhan_nilai = $saldo_kirim_thn_ini - $saldo_kirim_thn_lalu;
                $pertumbuhan_persen = $saldo_kirim_thn_lalu != 0 ? ($pertumbuhan_nilai / $saldo_kirim_thn_lalu) * 100 : 500;

                if ($saldo_kirim_thn_lalu > 0) {

                    if ($pertumbuhan_persen <= 1500 && $pertumbuhan_persen > -101) {
                        $pertumbuhan_persen_f = $pertumbuhan_persen != 0 ? number_format($pertumbuhan_persen, 2) : 0;

                        // $imbol = "triangle";
                        // $imbol = "circle";
                        if ($pertumbuhan_persen <= -100) {
                            $warna = "red";
                            $rad_size = 2;
                            $imbol = "triangle-down";
                        } elseif ($pertumbuhan_persen >= 200) {
                            $warna = "blue";
                            $rad_size = 3;
                            // $imbol = "circle";
                            $imbol = "triangle";
                            $pertumbuhan_persen = 200;
                        } else {
                            $warna = "green";
                            $rad_size = 3;
                            $imbol = "circle";
                        }

                        $persen_bdy[] = array(
                            "y" => $pertumbuhan_persen,
                            "color" => "$warna",
                            "marker" => array(
                                "radius" => $rad_size,
                                "symbol" => $imbol
                            ),
                        );
                        // }
                        $persen_all[] = $pertumbuhan_persen;
                        $nilai[] = $pertumbuhan_nilai / 1000000;
                        $nilai_penjualan[] = $saldo_kirim_thn_ini / 1000000;

                        $produkId[] = $pId;
                        $nama[] = $pNama;
                    }
                }

                // $produkId[] = $pId;
                // $nama[] = $pNama;

                $sum_saldo_kirim_thn_ini += $saldo_kirim_thn_ini;
                $sum_saldo_kirim_thn_lalu += $saldo_kirim_thn_lalu;
                $sum_pertumbuhan_nilai += $pertumbuhan_nilai;
            }
            $hargas = $persen_bdy;

        }
        // $test = array(
        //     array(1, "ii"),
        //     5
        // );
        // $jes = json_encode($test);
        // arrPrintPink($produkId);
        // arrPrintPink($jes);
        // arrPrintPink($nilai);
        // arrPrintPink($persen_btm);
        // arrPrintPink($hargas);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // matiHere(__LINE__);
        // cekBiru("$sum_pertumbuhan_nilai || $sum_saldo_kirim_thn_ini || $sum_saldo_kirim_thn_lalu ");
        // asort($hargas);
        $avg_weighted = ($sum_pertumbuhan_nilai / $sum_saldo_kirim_thn_lalu) * 100;

        $sumHarga = 0;
        foreach ($persen_all as $hrg) {
            $sumHarga += $hrg;
        }
        $jml_data = sizeof($persen_all);
        $avg = $sumHarga / $jml_data;
        // $data_sebaran_e = json_encode($hargas, JSON_NUMERIC_CHECK);
        // $data_sebaran_btm_e = json_encode($persen_btm);
        $data_sebaran_e = json_encode($hargas);
        $data_label_e = json_encode($nama);
        $data_nilai_e = json_encode($nilai);
        $data_penjualan_thn_ini_e = json_encode($nilai_penjualan);

        $minimum = min($hargas);
        // $min_data_2 = $minimum - (0.2 * $minimum);
        $min_data_2 = 0;
        // $min_data = (0.2 * $minimum);
        $min_data = (0.2 * 1);

        $maximum = max($hargas);
        // $max_data_2 = $maximum - (0.2 * $maximum);
        $max_data_2 = 100;
        // $max_data = (0.2 * $maximum);
        $max_data = (0.2 * 1);
        // arrPrintKuning($hargas);
        // arrPrintHijau($produkId);
        asort($hargas);
        // arrPrintKuning($data_label_e);
        // arrPrintKuning($data_sebaran_e);
        // arrPrintKuning(min($hargas));
        // arrPrintKuning(max($hargas));


        $batas_data = 40;
        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai awal
         * ----------------------------------------------------------------------------*/
        $first_datas = (array_slice($hargas, 0, floor($jml_data * ($batas_data / 100))));
        $avg_min = count($first_datas) > 0 ? array_sum($first_datas) / count($first_datas) : 0;

        /* ----------------------------------------------------------------------------
         * mendapatkan sekian data dari arai akhir
         * ----------------------------------------------------------------------------*/
        $last_datas = (array_slice($hargas, floor($jml_data * ($batas_data / 100)) * -1));
        $avg_max = array_sum($last_datas) / count($last_datas);
        // cekHijau("avg:: $avg up:: $avg_max min:: $avg_min");
        // arrPrintKuning($hargas);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($bom);
        // $data_sebaran_e =
        $strData = "";
        $strData .= "<style>
            // .col-md-2 {padding-right: 0 !important;}
            .col-xl-2 {padding-right: 5px !important;}
            // .col-lg-4 {padding-right: 5px !important;}
        </style>";

        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='containerSebaranPenjualan'></div>";
        $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        // $strData .= "<button id=\"button\" class=\"autocompare\">Add plot line</button>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        // $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        // $strData .= "<script>
        //                 $('#target_persen').blur(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //
        //                 $('#target_save').click(function() {
        //                     var nilai = $('#target_persen').val();
        //                     var save = $('#target_save').val();
        //
        //                     if(save == 0){
        //                         var param = '';
        //                     }
        //                     else {
        //                         var param = '&save=1';
        //                     }
        //                     $('#wadah_compare').load('$link_save' + nilai + param);
        //                 });
        //             </script>";

        // arrPrint($varDatas);
        // arrPrintPink($target_tahun_now);
        $button = "button";
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                var data_label = $data_label_e;
                var data_nilai = $data_nilai_e;
                var data_penjualan = $data_penjualan_thn_ini_e;
                
                Highcharts.chart('containerSebaranPenjualan', {
                    title: {
                        text: '$title',
                        align: 'left'
                    },
                    subtitle: {
                        text: '$subTitle',
                        align: 'left'
                    },
                    xAxis: {
                        // min: 0,
                        // max: 220
                        data: $data_label_e
                    },
                    yAxis: {
                        title: {
                        // useHTML: null,
                        text: '',
                        offset: 0,                       
                    },
                        // min: $min_data,
                        // visible: false,
                        plotLines: [
                            // {
                            //   value: [$avg_max],
                            //   color: 'blue',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit tertinggi'
                            //   }
                            // },
                            {
                              value: [$avg],
                              color: 'red',
                            
                              width: 1,
                              label: {
                                text: 'AVG simpel <b>' + $avg.toFixed(2) + '</b>%',
                                style: {
                                    fontWeight: 'normal',
                                    fontSize: '1.2em'
                                }                                
                              }
                            },
                            {
                              value: [$avg_weighted],
                              color: 'red',
                             dashStyle: 'dash',
                              width: 1,
                              label: {
                                text: 'AVG weighted <b>' + $avg_weighted.toFixed(2) + '</b>%',
                                style: {
                                    fontWeight: 'normal',
                                    fontSize: '1.2em'
                                }                                
                              }
                            },
                            // {
                            //   value: [$avg_min],
                            //   color: 'green',
                            //   dashStyle: 'dash',
                            //   width: 1,
                            //   label: {
                            //     text: '$batas_data% profit terendah'
                            //   }
                            // },
                        ]
                    },
                    tooltip: {
                        // headerFormat: '{data.x} {point.x} <br>',
                        // pointFormat: '{point.y}',                        
                        // shared: true,
                        formatter: function () {
                           var profit_persen = Number(this.y).toFixed(2);
                           if(profit_persen == 0){
                               var profit_persen = 0;
                           }
                           var profit_nilai = Number(data_nilai[this.x]).toFixed(2);
                           var penjualan_nilai = Number(data_penjualan[this.x]).toFixed(2);
                           
                           //  return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + Number(this.y).toFixed(2) + '</b>% nilai kenaikan<b>' + Number(data_nilai[this.x]).toFixed(2) + '</b>';
                            return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + profit_persen + '</b>% nilai perubahan <b>' + profit_nilai + '</b>J ' + 'penjualan <b>' + penjualan_nilai +'</b>J';
                            // return  s;
                        },
                        valueDecimals: 2,
                        // headerFormat: '<b>{point.point.name}</b><br>',
                    },
                     chart: {
                        events: {
                          load: function() {
                            const chart = this,
                              data = chart.series[0].data;
                    
                            data.map((element) => {
                              // if (element.y > -100) {
                              //   element.update({
                              //     color: 'red',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   })
                              // }
                              // if (element.y > (-100) && element.y < (-80)){
                              //   element.update({
                              //     color: 'pink',
                              //     marker: {
                              //       radius: 3
                              //     }
                              //   }) 
                              // }                              
                            });
                            
                            
                          
                          }
                      }
                    },
                    series: [
                         {
                            type: 'scatter',
                            name: 'Profit',
                            
                             color: 'green',
                            // name: $data_label_e,
                            // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                            data: $data_sebaran_e,
                            // data: data,
                            marker: {
                                radius: 3
                            }
                        },
                        //  {
                        //     type: 'scatter',
                        //     name: 'pertumbuhan terendah',
                        //    
                        //      color: 'blue',
                        //     // name: $data_label_e,
                        //     // data: [['anu',1],['inu', 1.5], 2.8, 3.5, 3.9, 4.2],
                        //     data: $data_sebaran_e,
                        //     // data: data,
                        //     marker: {
                        //         radius: 2
                        //     }
                        // }
                        
                        {
                            showInLegend: true,
                            type: 'scatter',
                            name: '= -100%',
                            color: 'red',
                            marker: {
                                symbol: 'triangle-down'    
                            }                            
                        },
                        {
                            showInLegend: true,
                            type: 'scatter',
                            name: '> 200%',
                            color: 'blue',
                            marker: {
                                symbol: 'triangle'    
                            }                            
                        },                            
                    ]
                });
                
                // the button action
                // var hasPlotLine = false,
                //     $button = $('#button');
                //
                // $button.click(function () {
                //     if (!hasPlotLine) {
                //         chart.yAxis[0].addPlotLine({
                //             value: [$avg],
                //             color: 'red',
                //             width: 2,
                //             id: 'plot-line-1'
                //         });
                //         $button.html('Remove plot line');
                //     } else {
                //         chart.yAxis[0].removePlotLine('plot-line-1');
                //         $button.html('Add plot line');
                //     }
                //     hasPlotLine = !hasPlotLine;
                // });



                $('.highcharts-credits').remove();
                
                 $('#open_editor_target').click(function() {
                    // var nilai = $('#target_persen').val();
                    
                    // $('#editor_target').removeClass('hidden');
                    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
                });
    // $('#target_slider').fadeOut();
        </script>\n";

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewEfisiensiBomBlnan":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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

        // arrPrint($content);

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
                } else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }

        foreach ($label_series as $label_key => $label_nama) {
            $strNama = $label_key . "_nama";
            $$strNama = $label_nama;
        }
        foreach ($tipe_series as $label_key => $label_nama) {
            $strNama = $label_key . "_type";
            $$strNama = $label_nama;
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
            } else {
                // -----------BASE DATA PENJUALAN--------------
                if (is_array($penjualanBulanan) && (sizeof($penjualanBulanan) > 0)) {
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

        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_produksi_$keyBiaya'></div>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'>Akumulasi penjualan LOCAL, EXPORT dan PROJECT</p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        // $strData .= "</div>"; // box end 12

        //        arrPrint($varDatas);

        $strData .= "<script>

        var chart = Highcharts.chart('container_produksi_$keyBiaya', {
            // exporting: {
            //     buttons: {
            //         contextButton: {
            //             enabled: false
            //         },
            //         toggle: {
            //             align: 'left',
            //             y: 30,
            //             x: 20,
            //             height: 14,
            //             theme: {
            //                 'stroke-width': 1,
            //                 stroke: 'silver',
            //                 r: 0
            //             },
            //             text: 'Chart Height',
            //             menuItems: [{
            //                 text: '400px',
            //                 onclick: function () {
            //                     var defWidth = Highcharts.charts[0].get().chart.chartWidth;
            //                     Highcharts.charts[0].setSize(defWidth, 400)
            //                 }
            //             }, {
            //                 text: '800px',
            //                 onclick: function () {
            //                     var defWidth = Highcharts.charts[0].get().chart.chartWidth;
            //                     Highcharts.charts[0].setSize(defWidth, 800)
            //                 }
            //             }, {
            //                 text: '1200px',
            //                 onclick: function () {
            //                     var defWidth = Highcharts.charts[0].get().chart.chartWidth;
            //                     Highcharts.charts[0].setSize(defWidth, 1200)
            //                 }
            //             }, {
            //                 text: '1600px',
            //                 onclick: function () {
            //                     var defWidth = Highcharts.charts[0].get().chart.chartWidth;
            //                     Highcharts.charts[0].setSize(defWidth, 1600)
            //                 }
            //             }]
            //         }
            //     }
            // },
          chart: {
            type: \"xy\",
            // height: 400,
          },
          title: {
            text: '$title',
            align: 'left'
          },
          subtitle: {
            text: '$subTitle',
            align: 'right',
            useHTML: true
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
            tickInterval: 10000000,
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
              format: '{text}',
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
            // layout: 'vertical',
            align: 'center',
            // x: 10,
            verticalAlign: 'bottom',
            y: 10,
            floating: false,
            backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || // theme
              'rgba(255,255,255,0.25)'
          },
          series: [
            {
            name: '$bruto_nama',
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
            name: '$hpp_nama',
            // type: 'spline',
            type: '$hpp_type',
            color: 'blue',
            yAxis: 1,
            pointPlacement: -0.1,
            data: $hpp,
            tooltip: {
              valueSuffix: ' '
            }
          },
          {
            name: '$netto_nama',
            type: '$netto_type',
            // type: 'column',
            yAxis: 1,
            pointPlacement: -0.2,
            color: 'green',
            // stacking: 'normal',
            data: $netto,
            tooltip: {
              valueSuffix: ' '
            }
          },
          // {
          //   name: 'penjualan',
          //   type: 'spline',
          //   color: 'black',
          //   yAxis: 1,
          //   data: $penjualan,
          //   tooltip: {
          //     valueSuffix: ' '
          //   }
          // },
          // {
          //   name: '$biaya_nama',
          //   type: 'spline',
          //   color: 'orange',
          //   yAxis: 1,
          //   data: $biaya,
          //   tooltip: {
          //     valueSuffix: ' '
          //   }
          // },
          // {
          //   name: '$biayahpp_nama',
          //   type: 'spline',
          //   // type: 'column',
          //   // stacking: 'normal',
          //   color: 'brown',
          //   yAxis: 1,
          //   data: $biayahpp,
          //   tooltip: {
          //     valueSuffix: ' '
          //   }
          // },
          // {
          //   name: '$project_nama',
          //   type: 'spline',
          //   color: 'grey',
          //   yAxis: 1,
          //   data: $project,
          //   tooltip: {
          //     valueSuffix: ' '
          //   }
          // }
          ],
          // responsive: {
          //   rules: [{
          //     condition: {
          //       maxWidth: 500
          //     },
          //     chartOptions: {
          //       legend: {
          //         floating: false,
          //         layout: 'horizontal',
          //         align: 'center',
          //         verticalAlign: 'bottom',
          //         x: 0,
          //         y: 0
          //       }
          //     }
          //   }]
          // }
        });

$('#mode_$keyBiaya').click(function()
{
    // $('#graph_pro').load('$link_cart');
    $('#graph_pro').append($('<div/>').load('$link_cart'));
});

$('.highcharts-credits').remove();

        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = '';
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData = "";

        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {

            echo $strData;
        }

        break;
    case "viewMultyEfisiensiBomBlnan":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

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

        // arrPrint($content);
        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                //                    cekHijau($xLabel);
                //                    cekHijau($xData);
                if ("label" == $xLabel) {
                    $$xLabel = "['" . implode("','", $xData) . "']";
                } else {
                    $$xLabel = "[" . implode(",", $xData) . "]";
                }
            }
        }

        foreach ($label_series as $label_key => $label_nama) {
            $strNama = $label_key . "_nama";
            $$strNama = $label_nama;
        }
        foreach ($tipe_series as $label_key => $label_nama) {
            $strNama = $label_key . "_type";
            $$strNama = $label_nama;
        }

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
            } else {
                // -----------BASE DATA PENJUALAN--------------
                if (is_array($penjualanBulanan) && (sizeof($penjualanBulanan) > 0)) {
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
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_efisiensi_$keyBiaya'></div>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'>Akumulasi penjualan LOCAL, EXPORT dan PROJECT</p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        // $strData .= "</div>"; // box end 12

        //        arrPrint($varDatas);

        $strData .= "<script>

            var chart = Highcharts.chart('container_efisiensi_$keyBiaya', {
    
              chart: {
                type: \"xy\",
                // height: 400,
              },
              title: {
                text: '$title',
                align: 'left'
              },
              subtitle: {
                text: '$subTitle',
                align: 'right',
                useHTML: true
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
                tickInterval: 10000000,
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
                  format: '{text}',
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
                // layout: 'vertical',
                align: 'center',
                // x: 10,
                verticalAlign: 'bottom',
                y: 10,
                floating: false,
                backgroundColor:
                  Highcharts.defaultOptions.legend.backgroundColor || // theme
                  'rgba(255,255,255,0.25)'
              },
              series: [
                {
                name: '$bruto_nama',
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
                name: '$hpp_nama',
                // type: 'spline',
                type: '$hpp_type',
                color: 'blue',
                yAxis: 1,
                pointPlacement: -0.1,
                data: $hpp,
                tooltip: {
                  valueSuffix: ' '
                }
              },
              {
                name: '$netto_nama',
                type: '$netto_type',
                // type: 'column',
                yAxis: 1,
                pointPlacement: -0.2,
                color: 'green',
                // stacking: 'normal',
                data: $netto,
                tooltip: {
                  valueSuffix: ' '
                }
              },
             
              ],
             
            });
            
            //additional tools
            $('#mode_$keyBiaya').click(function()
            {
                // $('#graph_pro2').load('$link_cart');
                $('#graph_pro2').append($('<div/>').load('$link_cart'));

            });
            
            $('.highcharts-credits').remove();

        </script>\n";


        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {

            echo $strData;
        }


        break;

    case "viewTodolistTransaksi":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();


        $strData = "";
        $strData .= "<div class='box box-solid box-danger' >";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_efisiensi_$keyBiaya'></div>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'></p>";
        // $strData .= "<p class='highcharts-description' style='height: 23px;'>Akumulasi penjualan LOCAL, EXPORT dan PROJECT</p>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        // $strData .= "</div>"; // box end 12

        $btns = "<button type='button' class='btn btn-success btn-sm' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeading("To Do List", $btns);
        $p->setLayoutBoxBody(true);
        $p->setLayoutBoxAttribut("style='background-color:red;'");
        $strData = $p->layout_box($content);

        //        arrPrint($varDatas);
//cekhitam($_GET['tpl']);

        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => "",
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {

            echo $strData;
        }


        break;
    case "viewJmlNotaBulanan":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $namaBulan = namaBulan();

        /* ==============================
         * URUSAN NAVIGASI
         * ----------------------*/
        //region navigasi hlaman
        $hipo_target = base_url() . "Bi/createSession";
        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";

        $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
                                        onclick=\"window.location.reload();\">
                                    <i class='fa fa-refresh'></i></button>";

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

        // arrPrint($content);

        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                $xLabel2 = $xLabel . "_2";
                $$xLabel = $xData;
            }
        }
        // arrPrintPink(($penjualan));
        // arrPrintHijau(end($penjualan));
        // arrPrintHijau($penjualan_avg);
        // arrPrintHijau($nota_avg);

        $penjualan_avg_bulan = json_encode($penjualan_avg_bulan, true);
        $nota_avg_json = json_encode($nota_avg, true);
        $penjualan_avg_json = json_encode($penjualan_avg, true);
        $penjualan = json_encode($content['penjualan'], JSON_NUMERIC_CHECK);
        $jml_nota = isset($content['jml_nota']) ? json_encode($content['jml_nota'], JSON_NUMERIC_CHECK) : 0;
        $hpp = json_encode($content['hpp'], JSON_NUMERIC_CHECK);
        $labarugi = json_encode($content['labarugi'], JSON_NUMERIC_CHECK);
        $label_0 = json_encode($content['label'], true);
        $label = str_replace('"', "'", $label_0);

        // arrPrintPink($penjualan);
        $strData = "";
        $strData .= "<div class='box box-solid box-success'>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_nota_bulanan'></div>";

        $strData .= "<div style='height: 31px;'></div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end

        $date_now = dtimeNow('d M');
        $strData .= "<script>

        Highcharts.setOptions({
                lang: {
                    thousandsSep: ' ',
                    decimalPoint: ','
                }
            });
        var chart = Highcharts.chart('container_nota_bulanan', {
             chart: {
                    type: 'spline',
                },
                title: {
                    text: '$title',
                    align: 'left'
                },
                subtitle: {
                    text: '$subTitle',
                    align: 'left'
                },
                xAxis: [{
                    categories: $label,
                    crosshair: true
                }],
                yAxis: [                
                {
                    title: {
                        useHTML: null,
                        text: 'Penjualan',
                        offset: 40,
                    },                     
                    // data : ['yellow'],
                    plotLines: [
                        {
                          value: $penjualan_avg_json,
                          color: 'red',
                          dashStyle: 'dash',
                          width: 1,
                          label: {
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1.1em',
                            },
                            align: 'right',
                            text: 'Penjualan Rata-rata (bulan penuh) $penjualan_avg_json',
                            horisontalAlign: 'right',
                            // textAlign: 'left',
                            format: '{point.y:.2f}',
                            // x: 1000,
                          }
                        },
                    ]
                },
                {
                    title: {
                        // useHTML: null,
                        text: 'Jumlah Nota',
                        // offset: 10,   
                         style: {
                            color: Highcharts.getOptions().colors[3]
                        }                     
                    },
                    labels: {
                        // format: '{value}',
                         style: {
                            color: Highcharts.getOptions().colors[3]
                        }
                    },
                    opposite: true,
                },
                ],
                tooltip: {
                    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                        '<td style=\"padding:0\"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [
                    {
                    // name: 'Penjualan (/$pembagi)',
                    name: 'Penjualan',
                    data: $penjualan,
                    dataLabels: {
                        enabled: true,
                         y: 40,
                         // format: '{point.y:.0f}<br>{y}',
                         // format: '{point.y:.0f}',
                         formatter: function () {
                                return Highcharts.numberFormat(this.y,1);
                            },  
                        style: {
                            fontWeight: 'bold',
                            fontSize: '12px',
                            textOutline: 'none'
                        }
                    },
                    color: 'rgba(0, 190, 70, 0.70)',
                    type: 'column',
                    },
                    {
                        name: 'rata2 harian',
                        data: $penjualan_avg_bulan,
                        // yAxis: 1,
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '14px',
                                color: 'blue',
                                textOutline: 'none'
                            },
                            formatter: function () {
                                return Highcharts.numberFormat(this.y,2);
                            },
                        },
                        color: 'blue',
                        dashStyle: 'dash',
                    },
                    // {
                    //     name: 'Jml Nota',
                    //     data: $jml_nota,
                    //     yAxis: 1,
                    //     dataLabels: {
                    //         enabled: true,
                    //         style: {
                    //             fontWeight: 'normal',
                    //             fontSize: '14px',
                    //             color: 'red',
                    //             textOutline: 'none'
                    //         }
                    //     },
                    //     color: 'red',
                    // },
                    
                ],
                legend: {
                    layout: 'horizontal', // default
                    // itemDistance: 50
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 2);
                    }
                }
                
        });


        $('.highcharts-credits').remove();



        </script>\n";

        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));
            $p->render();
        } else {
            echo $strData;
        }

        break;
    // --------sepasang lo ini-------------------
    case "viewPenjualanHarian":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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

        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                $xLabel2 = $xLabel . "_2";
                // cekHijau($xLabel);
                // cekHijau($xData);
                // if ("tahun" == $xLabel) {
                //     $$xLabel = "['" . implode("','", $xData) . "']";
                // }
                // else {
                // $$xLabel = "[$xData]";
                $$xLabel = $xData;
                // }
            }
        }
        $penjualan_avg_json = json_encode($penjualan_avg, true);
        $penjualan = json_encode($content['penjualan'], JSON_NUMERIC_CHECK);
        $hpp = json_encode($content['hpp'], JSON_NUMERIC_CHECK);
        $labarugi = json_encode($content['labarugi'], JSON_NUMERIC_CHECK);
        $label_0 = json_encode($content['label'], true);
        $label = str_replace('"', "'", $label_0);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($content['hpp']);
        // arrPrintKuning($penjualan_avg_json);

        $strData = "";
        // $strData .= "xxx";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_harian'></div>";
        //         $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label
        // <input type='button' id='target_save' value='0' disabled style='display: none;'></span></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        $strData .= "<div style='height: 31px;'></div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                    </script>";

        //        arrPrint($varDatas);
        //        arrPrint($label);
        //        arrPrint($penjualan);
        // arrPrintPink($target_tahun_now);
        $date_now = dtimeNow('d M');
        $strData .= "<script>
                
        var chart = Highcharts.chart('container_harian', {
             chart: {
                    type: 'spline',
                },
                title: {
                    text: '$title',
                    align: 'left'
                },
                subtitle: {
                    text: '$subTitle',
                    align: 'left'
                },
                xAxis: {
                    categories: $label,
                    crosshair: true
                },
                yAxis: {
                    title: {
                        useHTML: null,
                        text: '',
                        offset: 0,
                    },
                    plotLines: [
                        {
                          value: $penjualan_avg_json,
                          color: 'red',
                          dashStyle: 'dash',
                          width: 1,
                          label: {
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1em'
                            },
                            text: 'Rata-rata $penjualan_avg_json',
                            format: '{point.y:.2f}<br>%',
                          }
                        },

                        ]
                },
                tooltip: {
                    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                        '<td style=\"padding:0\"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                            // dataLabels: {
                            //     enabled: true,
                            //     format: '{point.y:.2f}',
                            //     align: 'right',
                            //     color: '#FFFFFF',
                            //     x: -10
                            // },
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Penjualan',
                    data: $penjualan,
                    color: 'green',                              
                }, 
                // {
                //     name: 'HPP',
                //     data: $hpp,
                //     color: 'red',                              
                // }, 
                // {
                //     name: 'RL',
                //     data: $labarugi,
                //     color: 'blue',
                //     // type: 'line',                              
                // }, 
                ],
                legend: {
                    layout: 'horizontal', // default
                    // itemDistance: 50
                },
                // exporting: {
                //     buttons: [{
                //        
                //       text: 'custom button',
                //       onclick: function () {
                //         alert('clicked');
                //       },
                //       theme: {
                //             'stroke-width': 1,
                //             stroke: 'silver',
                //             r: 0,
                //             states: {
                //                 hover: {
                //                     fill: '#a4edba'
                //                 },
                //                 select: {
                //                     stroke: '#039',
                //                     fill: '#a4edba'
                //                 }
                //             }
                //         }
                //     }]
                // }
                
        });


 $('.highcharts-credits').remove();

 $('#open_editor_target').click(function() {
    // var nilai = $('#target_persen').val();
    
    // $('#editor_target').removeClass('hidden');
    $('#target_save').fadeIn().prop('disabled', false).val('Apply');
});
    // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    case "viewPenjualanHarianHolding":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = MODUL_TEMPLATE_PATH . "template/graph.html";
        // $pageTemplate = "application/template/bi.html";
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

        // arrPrint($content);

        if (sizeof($content) > 0) {
            foreach ($content as $xLabel => $xData) {
                $xLabel2 = $xLabel . "_2";
                // cekHijau($xLabel);
                // cekHijau($xData);
                // if ("tahun" == $xLabel) {
                //     $$xLabel = "['" . implode("','", $xData) . "']";
                // }
                // else {
                // $$xLabel = "[$xData]";
                $$xLabel = $xData;
                // }
            }
        }
        $penjualan_avg_json = json_encode($penjualan_avg, true);
        $branch_names = isset($branch_names) ? $branch_names : array();
        $branch_name_0 = isset($branch_names[0]) ? $branch_names[0] : "Cabang I";
        $branch_name_1 = isset($branch_names[1]) ? $branch_names[1] : "Cabang II";
        $branch_name_2 = isset($branch_names[2]) ? $branch_names[2] : "Cabang III";

        $jml_nota = json_encode($content['jml_nota'], JSON_NUMERIC_CHECK);
        $jml_nota_100 = json_encode($content['jml_nota_100'], JSON_NUMERIC_CHECK);
        $jml_nota_101 = json_encode($content['jml_nota_101'], JSON_NUMERIC_CHECK);
        $jml_nota_102 = isset($content['jml_nota_102']) ? json_encode($content['jml_nota_102'], JSON_NUMERIC_CHECK) : json_encode(array());

        $penjualan = json_encode($content['penjualan'], JSON_NUMERIC_CHECK);
        $penjualan_100 = json_encode($content['penjualan_100'], JSON_NUMERIC_CHECK);
        $penjualan_101 = json_encode($content['penjualan_101'], JSON_NUMERIC_CHECK);
        $penjualan_102 = isset($content['penjualan_102']) ? json_encode($content['penjualan_102'], JSON_NUMERIC_CHECK) : json_encode(array());
        $hpp = json_encode($content['hpp'], JSON_NUMERIC_CHECK);
        $labarugi = json_encode($content['labarugi'], JSON_NUMERIC_CHECK);
        $label_0 = json_encode($content['label'], true);
        $label = str_replace('"', "'", $label_0);
        // $efisiensi_persen_2_f = number_format(($efisiensi_persen_2 * 100), 2);
        // arrPrintKuning($penjualan_past);
        // arrPrintKuning($penjualan_now);
        // arrPrintKuning($target_penjualan_now);
        // arrPrintKuning($content['hpp']);
        // arrPrintKuning($penjualan_avg_json);

        $strData = "";
        // $strData .= "xxx";
        $strData .= "<div class='box box-solid box-success'>";
        // $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        $strData .= "<div class='box-body'>";

        $strData .= "<figure class='highcharts-figure'>";
        $strData .= "<div id='container_harian'></div>";
        //         $strData .= "<p class='highcharts-description'><span id='editor_target'>Target pertumbuhan <input placeholder='target' id='target_persen' value='$target_persen' style='width: 40px;text-align: center;'> % dari pencapaian $penjualan_past_label
        // <input type='button' id='target_save' value='0' disabled style='display: none;'></span></p>";
        //         $strData .= "<div class='range-slider hidden' id='target_slider'>
        //           <input id='investment' type='range' value='$target_tahun_ini' min='0' max='100' step='1'>
        //         </div>";
        $strData .= "<div style='height: 31px;'></div>";
        $strData .= "</figure>";

        $strData .= "</div>"; // body
        $strData .= "</div>"; // box end
        $strData .= "<span id='wadah_compare'></span>";
        // arrPrintKuning($_GET);
        /*---auto reload garafik-----*/
        $get_v = isset($_GET['v']) ? $_GET['v'] : '14';
        $link_save = base_url() . "dashboard/Graph/doSaveTargett?nilai=";
        $base_url = base_url() . "/dashboard/Graph/viewpenjualanHarian";
        $strData .= "<script>
                        $('#target_persen').blur(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        $('#target_save').click(function() {
                            var nilai = $('#target_persen').val();
                            var save = $('#target_save').val();

                            if(save == 0){
                                var param = '';
                            }
                            else {
                                var param = '&save=1';
                            }
                            $('#wadah_compare').load('$link_save' + nilai + param);
                        });
                        
                        setTimeout(function(){
                           $('#sales_harian').load('$base_url?v=$get_v');
                        }, 3600000);
                    </script>";

        //        arrPrint($varDatas);
        //        arrPrint($label);
        //        arrPrint($penjualan);
        // arrPrintPink($target_tahun_now);
        // $fillBtnChart = "#ddd";

        $date_now = dtimeNow('d M');
        $strData .= "<script>
        var nota_cb_100 = $jml_nota_100;
        var chart = Highcharts.chart('container_harian', {
             chart: {
                    type: 'spline',
                },
                title: {
                    text: '$title',
                    align: 'left'
                },
                subtitle: {
                    text: '$subTitle',
                    align: 'left'
                },
                exporting: {
                  buttons: {
                        contextButton: {
                            // text: 'Export',
                            enabled: true,
                            theme: {
                                fill: '#ffffff',
                                'stroke-width': 0,
                                stroke: 'silver',
                                r: 0,
                                states: {
                                    hover: {
                                        fill: '#a4edba'
                                    },
                                    select: {
                                        stroke: '#039',
                                        fill: '#a4edba'
                                    }
                                }
                            }
                        },
                        tujuh: {
                            text: '7 hari',
                            id: '7_hari',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=8');
                            },
                            theme: {
                                fill: '" . (($v_param == "8") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "8") ? "2" : "1") . "'
                            }
                        },
                        empatbelas: {
                            text: '14 hari',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=15');
                            },
                            theme: {
                                fill: '" . (($v_param == "15" || $v_param == "14h") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "15" || $v_param == "14h") ? "2" : "1") . "'
                            }
                        },
                        tigapuluh: {
                            text: '30 hari',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=32');
                            },
                            theme: {
                                fill: '" . (($v_param == "32") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "32") ? "2" : "1") . "'
                            }
                        },
                        tigabulan: {
                            text: '3 bulan',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=3m');
                            },
                            theme: {
                                fill: '" . (($v_param == "3m") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "3m") ? "2" : "1") . "'
                            }
                        },
                        enambulan: {
                            text: '6 bulan',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=6m');
                            },
                            theme: {
                                fill: '" . (($v_param == "6m") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "6m") ? "2" : "1") . "'
                            }
                        },
                        duabelasbulan: {
                            text: '12 bulan',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=12m');
                            },
                            theme: {
                                fill: '" . (($v_param == "12m") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "12m") ? "2" : "1") . "'
                            }
                        },
                        all: {
                            text: 'seluruh data',
                            onclick: function () {
                                $('#sales_harian').load('$base_url?v=all');
                            },
                            theme: {
                                fill: '" . (($v_param == "all") ? "#a4edba" : "#ffffff") . "',
                                stroke: 'silver',
                                'stroke-width': '" . (($v_param == "all") ? "2" : "1") . "'
                            }
                        }                        
                    }              
                },
                navigation: {
                    buttonOptions: {
                        theme: {
                            // fill: '#ccc',
                            'stroke-width': 1,
                            stroke: 'silver',
                            r: 0,
                            states: {
                                hover: {
                                    fill: '#a4edba'
                                },
                                select: {
                                    stroke: '#039',
                                    fill: '#a4edba'
                                }
                            }
                        }
                    }
                },
                xAxis: [{
                    categories: $label,
                    crosshair: true
                }],
                yAxis: [
                    {
                    title: {
                        useHTML: null,
                        text: '',
                        offset: 0,

                    },
                    plotLines: [
                        {
                          value: $penjualan_avg_json,
                          color: 'red',
                          dashStyle: 'dash',
                          width: 1,
                          label: {
                            style: {
                                fontWeight: 'normal',
                                fontSize: '1em'
                            },
                            text: 'Rata-rata $penjualan_avg_json',
                            format: '{point.y:.2f}',
                            align: 'right',
                          }
                        },

                        ]
                    },
                    {
                        title: {
                            text: 'Jumlah Nota',  
                             style: {
                                color: 'pink',
                            }                     
                        },
                        // tickInterval: 10,
                        // min: -1000,
                        labels: {
                             style: {
                                color: 'pink',
                            }
                        },
                        opposite: true,
                    },
                ],
                tooltip: {
                 // formatter: function () {
                 //           // var profit_persen = Number(this.y).toFixed(2);
                 //           // if(profit_persen == 0){
                 //           //     var profit_persen = 0;
                 //           // }
                 //           // var profit_nilai = Number(data_nilai[this.x]).toFixed(2);
                 //           var penjualan_nilai = Number(nota_cb_100[this.x]).toFixed(2);
                 //           console.log(this.x);
                 //
                 //           // return 'Produk <b>' + data_label[this.x] + '</b> profit <b>' + profit_persen + '</b>% nilai perubahan <b>' + profit_nilai + '</b>J ' + 'penjualan <b>' + penjualan_nilai +'</b>J';
                 //           return 'jml nota ' + penjualan_nilai;
                 //
                 //        },
                        
                    headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                    pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                        '<td style=\"padding:0\"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    series: {
                            dataLabels: {
                                enabled: true,
                            //     format: '{point.y:.2f}',
                            //     align: 'right',
                            //     color: '#FFFFFF',
                            //     x: -10
                            formatter: function() {
                                    if (this.isLast || this.isFirst) {
                                    return this.value
                                  }
                                }
                            },
                            pointPadding: 0.2,
                            groupPadding: 0
                        },
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [
//                    {
//                        name: 'Jml Nota',
//                        data: $jml_nota,
//                        color: 'lime',  
//                        dashStyle: 'dash',
//                        yAxis: 1,
//                        visible: false,                            
//                    },
//                    {
//                        name: 'Jml Nota ' + '$branch_name_0',
//                        data: $jml_nota_100,
//                        color: 'brown',  
//                        dashStyle: 'dash',
//                        yAxis: 1,
//                        visible: false,                            
//                    },
//                    {
//                        name: 'Jml Nota ' + '$branch_name_1',
//                        data: $jml_nota_101,
//                        color: 'orange',  
//                        dashStyle: 'dash',
//                        yAxis: 1,
//                        visible: false,                            
//                    },
//                    {
//                        name: 'Jml Nota ' + '$branch_name_2',
//                        data: $jml_nota_102,
//                        color: 'purple',  
//                        dashStyle: 'dash',
//                        yAxis: 1,
//                        visible: false,                            
//                    },
                    {
                        name: 'Penjualan Total',
                        data: $penjualan,
                        color: 'green',  
                    }, 
                    {
                        name: '$branch_name_0',
                        data: $penjualan_100,
                        color: 'blue',
                        type: 'column',    
                    }, 
                    {
                        name: '$branch_name_1',
                        data: $penjualan_101,
                        color: 'red',  
                        type: 'column',      
                        pointPlacement: -0.1,
                    }, 
                    {
                        name: '$branch_name_2',
                        data: $penjualan_102,
                        color: 'orange',  
                        type: 'column',  
                        pointPlacement: -0.2, 
                    }, 
                ],
                legend: {
                    layout: 'horizontal', // default
                    // itemDistance: 50
                },
                     
        });

 $('.highcharts-credits').remove();

//  $('#open_editor_target').click(function() {
//     // var nilai = $('#target_persen').val();
//    
//     // $('#editor_target').removeClass('hidden');
//     $('#target_save').fadeIn().prop('disabled', false).val('Apply');
// });
//     // $('#target_slider').fadeOut();
        </script>\n";

        //         $strData .= "<script>
        //
        //   //region config chart 1
        //
        //             var dataDb = jsonDatas;
        //             var dataQt = jsonQtDatas;
        //             var yKey = jsonYkeys;
        //             var yLabel = jsonYlabel;
        //
        //             config = {
        //               data: dataDb,
        //               xkey: 'bulan',
        //               ykeys: yKey,
        //               labels: yLabel,
        //               fillOpacity: 0.6,
        //               hideHover: 'auto',
        //               behaveLikeLine: true,
        //               resize: true,
        //               pointFillColors:['#ffffff'],
        //               pointStrokeColors: ['black'],
        //               lineColors:['green','red','black','orange', 'blue'],
        //               barColors:['green','red','black','orange', 'blue'],
        // //              barColors:['lightgreen','lightblue','red','orange', 'darkyellow'],
        // //              eventLineColors: ['#005a04','#005a04','#005a04','#005a04','#005a04'],
        //               lineWidth:['2','2','2','2','2'],
        //               parseTime: false,
        //               dataLabels: false,
        //               animation: false,
        //               goals: [1.0, -1.0],
        //               goalStrokeWidth: 1,
        // //              goalLineColors: ['orange'],
        //               //axes: true, //default true
        //               stacked: true,
        //               nbYkeys2: 3,
        // //              yLabelFormat: function(y){ return y != Math.round(y)? addCommas(Math.round(parseFloat(y)/1000000)) + ' M' : addCommas(Math.round(parseFloat(y)/1000000)) + ' M' ; },
        //               gridIntegers: true,
        // //              ymin: ymin,
        // //              ymax: ymax,
        // //              ymin2: ymin,
        // //              ymax2: ymax,
        //              };
        //
        //             config.element = 'bar-chart';
        //             var browsersChart = Morris.Bar(config);
        //             //endregion config chart 1
        //
        //             // config.element = 'area-chart';
        //             // Morris.Area(config);
        //             // config.element = 'bar-chart';
        //             // Morris.Bar(config);
        //             // config.element = 'stacked';
        //             // config.stacked = true;
        //             // Morris.Bar(config);
        //             // Morris.Donut({
        //             //   element: 'pie-chart',
        //             //   data: [
        //             //     {label: \"Friends\", value: 30},
        //             //     {label: \"Allies\", value: 15},
        //             //     {label: \"Enemies\", value: 45},
        //             //     {label: \"Neutral\", value: 10}
        //             //   ]
        //             // });
        //             // config.element = 'bar-chart';
        //
        //             browsersChart.options.labels.forEach(function(label, i) {
        //                 // bar.options.labels.forEach(function(label, i) {
        // //                    console.log(label);
        //                 var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
        //                 legendItem.find('span')
        //                   .css('backgroundColor', browsersChart.options.lineColors[i])
        //                   .css('width', '20px')
        //                   .css('display', 'inline-block')
        //                   .css('margin', '5px');
        //                 $('#legend').append(legendItem)
        //               });
        //
        //
        //
        // //            Morris.Area({
        // //                element : 'line-chart',
        // //                data:dataQt,
        // //                xkey:'quarter',
        // //                ykeys:['qt'],
        // //                labels:['avg'],
        // //            //  hideHover:'auto',
        // //            //  stacked:true
        // //                fillOpacity: 0.6,
        // //                behaveLikeLine: true,
        // //                resize: true,
        // //                parseTime: false,
        // //            });
        //                     </script>";
        // -----

        // $strData="";
        if (isset($_GET['tpl'])) {
            $p->addTags(array(
                "content_nav" => $content_nav,
                "content" => $strData,
                "add_link" => "",
                "stop_time" => "",
            ));

            $p->render();
        } else {
            echo $strData;
        }

        break;
    // -----------end pasangan
}