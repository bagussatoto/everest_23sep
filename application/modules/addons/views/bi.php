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
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class'   => 'text-center',
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
            "menu_right_isi"   => callMenuRightIsi(),
            "menu_left"        => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav"      => $content_nav,
            "content"          => $strDataPropose,

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
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "omset (M)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "stock sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label"   => "buffer sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label"   => "moq sett (M)",
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
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class'   => 'text-center',
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
            "menu_right_isi"   => callMenuRightIsi(),
            "menu_left"        => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav"      => $content_nav,
            "content"          => $strDataPropose,

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
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : MODUL_TEMPLATE_PATH . "/template/bi.html";
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
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "omset (M)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "stock sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label"   => "buffer sett (M)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label"   => "moq sett (M)",
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
            $content_nav .= "<input type='hidden' name='Limit' id='Limit' value='$_GET[limit]'>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";
        // $content_nav .= " <a href='" . base_url() . "Bi/formSetting' data-toggle='modal' data-target='#myModal' class='btn btn-info'><i class='fa fa-android'></i></a>";

        // $content_nav .= form_button("cek", "show graph", "class='btn btn-info pull-right' onclick=\"window.open('" . base_url() . "Bi/viewGraphSales');\"");
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
                "<span class='text-blue'>month set</span>",
                "<span class='text-blue'>buffer</span>",
                "<span class='text-yellow'>month set</span>",
                "<span class='text-yellow'>moq</span>",
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

            // $heads = array_merge($heads_1, $bulans, $heads_2);
            $heads = array_merge($heads_1, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($heads as $key => $label) {
                $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $tblHeads .= "<th class='text-center' $attr>$label</th>";
            }
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

                $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;

                $newPo = ($ideal_stok) - ($stok_now);
                $newPox = $newPo > 0 ? $newPo : 0;

                $newPo_f = ceil($newPox);
                $ideal_stok_f = ceil($ideal_stok);

                $strIndex = "<input type='number' tabindex='$xi' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');\">";
                $strBuffer = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none $bgDb_b' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');\">";
                $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin border-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
                $strMoq = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin border-none $bgDb_m' style='width: 50px' value='$moqx_f' onclick=\"this.select();\" onblur=\"getData('$link_moq?v='+this.value,'update_buffer');\">";

                $tblBodies .= "<td class='text-right bg-warning text-bold'>$stok_out</td>";

                $tblBodies .= "<td class='text-right'>$avg_f</td>";
                $tblBodies .= "<td class='text-right'>$strBufferTime</td>";
                $tblBodies .= "<td class='text-right'>$strBuffer</td>";

                $tblBodies .= "<td class='text-right'>$strMoqTime</td>";
                $tblBodies .= "<td class='text-right'>$strMoq</td>";

                $tblBodies .= "<td class='text-right'>$strIndex</td>";

                $tblBodies .= "<td class='text-right'>$stok_now_l</td>";

                $tblBodies .= "<td class='text-right'>$strLeadTime</td>";
                $tblBodies .= "<td class='text-right'>$ideal_stok_f</td>";
                $tblBodies .= "<td class='text-right bg-success font-size-1-2'>$newPo_f</td>";
                $tblBodies .= "</tr>";
            }
            $tblBodies .= "</tbody>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($produks) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        // $strDataPropose = $this->table->generate();
        $strDataPropose = "<table class='table table-hover table-condensed ' id='bi_table'>";
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

                                $('.sidebar-toggle').on( 'click', function () {
                                    bi_table
                                        .draw();
                                    setTimeout( function(){
                                        $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
                                    }, 2500);
                                } );

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
        //endregion
        if (sizeof($produks) > 0) {

            // $propDisplay = "block";
            $propDisplay = "none";
        }
        else {

            $propDisplay = "none";
        }
        //cekHere($strEditLink);

        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi"   => callMenuRightIsi(),
            "menu_left"        => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "add_link"         => $content_note,
            "content_nav"      => $content_nav,
            "content"          => $strDataPropose,

            "stop_time" => "",
        ));
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
            // "nilai_ot" => "sale",
            "nilai_af" => "total penjualan",
        );

        if (sizeof($penjualanBulanan) > 0) {
            /* =================================
             * penjualan bulanan
             * -------------------*/
            foreach ($penjualanBulanan as $th => $datas_1) {
                foreach ($datas_1 as $bl => $datas_2) {

                    // $specs['fire'] = $penjualanFireBulanan[$th][$bl];
                    // $specs['nonFire'] = $penjualanNonFireBulanan[$th][$bl];

                    $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
                    $thBl = "$th " . $namaBl;
                    // $thBl = $namaBl;
                    // cekHere($thBl);
                    $specs['bulan'] = $thBl;

                    $varYkeys = array();
                    $varYlabels = array();
                    $varYkeys[] = 'fire';
                    $varYkeys[] = 'nonFire';

                    $varYlabels[] = 'fire';
                    $varYlabels[] = 'non fire';
                    foreach ($yKoloms as $yKolom => $yLabel) {
                        $specs[$yKolom] = $datas_2[$yKolom];

                        $varYkeys[] = $yKolom;
                        $varYlabels[] = $yLabel;
                    }

                    $varDatas[] = $specs;
                }
            }

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

        $jsonQtDatas = json_encode($qtDatas);
        $jsonDatas = json_encode($varDatas);
        $jsonYkeys = json_encode($varYkeys);
        $jsonYlabel = json_encode($varYlabels);
        // arrPrint($namaBulan);
        // arrPrint($jsonYkeys);
        // arrPrint($jsonDatas);
        // arrPrint($varDatas);
        // arrPrint($varYlabels);
        // arrPrint($qtDatas);
        // arrPrint($jsonQtDatas);
        // matiHere();

        // region penjuala vs pembelian
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
            $baseData = "pembelian";
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

        // region penjualan harian
        foreach ($penjualanHarian as $tg => $datas_1) {
            // foreach ($datas_1 as $bl => $datas_2) {
            $indek++;
            // cekHijau("$datas_2");
            // $namaBl = array_key_exists($bl, $namaBulan) ? $namaBulan[$bl] : $namaBulan['0' . $bl];
            // $thBl = "$th " . $namaBl;
            // $thBl = $namaBl;
            // cekHere($thBl);
            $specs_2['tanggal'] = $tg;
            $specs_2['penjualan'] = $datas_1["nilai_af"];

            // arrPrintWebs($specs_2);
            $varDatasHarian[] = $specs_2;
            // }
        }
        $varYkeysHarian[] = 'penjualan';
        $varYlabelsHarian[] = 'penjualan';

        $jsonDatasHarian = json_encode($varDatasHarian);
        $jsonYkeysHarian = json_encode($varYkeysHarian);
        $jsonYlabelHarian = json_encode($varYlabelsHarian);
        // endregion penjualan harian

        $strData = "<div>"; // start div disini
        // $strData .= "xxx";
        // $strData .= "<div class='nav-tabs-custom'>";
        // $strData .= "<div class='col-md-12'>";
        $strData .= "<div class='box box-solid box-success'>";
        $strData .= "<div class='box-header with-border'><h3>Monthly Sales</h3></div>";
        //
        // $strData .= "<ul class='nav nav-tabs pull-right ui-sortable-handle'>
        //               <li class='active'><a href='#revenue-chart' data-toggle='tab'>Area</a></li>
        //               // <li><a href='#sales-chart' data-toggle='tab'>Donut</a></li>
        //               // <li class='pull-left header'><i class='fa fa-inbox'></i> Sales</li>
        //             </ul>";

        $strData .= "<div class='box-body'>";

        $strData .= "<div class='dchart' id='bar-chart' style='pposition: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        $strData .= "</div>";
        $strData .= "<div id='legend' class='bars-legend'></div>";

        $strData .= "</div>"; // body

        $strData .= "</div>"; // box end
        $strData .= "<script>
  
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
              lineColors:['red','blue', 'green'],
              lineWidth:['1','1','2'],
              parseTime: false,                       
             };
            
            // config.element = 'area-chart';
            // Morris.Area(config);
            config.element = 'bar-chart';
            var browsersChart = Morris.Line(config);
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
                    console.log(label);
                var legendItem = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
                legendItem.find('span')
                  .css('backgroundColor', browsersChart.options.lineColors[i])
                  .css('width', '20px')
                  .css('display', 'inline-block')
                  .css('margin', '5px');
                $('#legend').append(legendItem)
              });
            
            
        
            Morris.Area({
                element : 'line-chart',
                data:dataQt,
                xkey:'quarter',
                ykeys:['qt'],
                labels:['avg'],
            //  hideHover:'auto',
            //  stacked:true
                fillOpacity: 0.6,
                behaveLikeLine: true,
                resize: true,
                parseTime: false,
            });
                    </script>";
        // -----

        //region data penjualan per produk
        $prHeades = "<tr class='bg-grey-1 text-uppercase'>";
        foreach ($kolomPenjualanProduk as $itemKolom => $kolomAttr) {

            // $bValue = $item->$itemKolom;
            $attrHead = isset($kolomAttr['attr_head']) ? $kolomAttr['attr_head'] : "";
            $label = isset($kolomAttr['label']) ? $kolomAttr['label'] : $itemKolom;

            $prHeades .= "<th $attrHead>$label</th>";
        }
        $prHeades .= "</tr>";

        $prBodies = "";
        $no = 0;
        foreach ($penjualanBulananProduk as $item) {
            $no++;
            $prBodies .= "<tr>";
            foreach ($kolomPenjualanProduk as $itemKolom => $kolomAttr) {

                $bValue = isset($item->$itemKolom) ? $item->$itemKolom : $no;
                $attr = isset($kolomAttr['attr']) ? $kolomAttr['attr'] : "";
                $bValue_f = isset($kolomAttr['format']) ? $kolomAttr['format']($itemKolom, $bValue) : $bValue;

                if (isset($kolomAttr['summary'])) {
                    if (!isset($itemKolomSum[$itemKolom])) {
                        $itemKolomSum[$itemKolom] = 0;
                    }
                    $itemKolomSum[$itemKolom] += $bValue;
                }

                $prBodies .= "<td $attr>$bValue_f</td>";
            }
            $prBodies .= "</tr>";
        }

        $prFooters = "<tr class='bg-grey-1'>";
        foreach ($kolomPenjualanProduk as $itemKolom => $kolomAttr) {

            $bValue = isset($itemKolomSum[$itemKolom]) ? $itemKolomSum[$itemKolom] : "-";
            $attr = isset($kolomAttr['attr']) ? $kolomAttr['attr'] : "";
            $bValue_f = isset($kolomAttr['format']) ? $kolomAttr['format']($itemKolom, $bValue) : $bValue;

            $prFooters .= "<th $attr>$bValue_f</th>";
        }
        $prFooters .= "</tr>";

        $penjualanProduk = "<table id='penjualanProduk' class='table table-condensed table-striped table-bordered no-margin'>";
        $penjualanProduk .= "<thead>";
        $penjualanProduk .= $prHeades;
        $penjualanProduk .= "</thead>";

        $penjualanProduk .= "<tbody>";
        $penjualanProduk .= $prBodies;
        $penjualanProduk .= "</tbody>";

        $penjualanProduk .= "<tfoot>";
        $penjualanProduk .= $prFooters;
        $penjualanProduk .= "</tfoot>";
        $penjualanProduk .= "</table>";
        $penjualanProduk .= "<script>\n
                 $(document).ready(function() {
                    // var bi_table = $('#penjualanProduk').DataTable({
                    //     order: [[ 9, 'desc' ]],
                    //     lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                    //     pageLength: -1,
                    //     stateSave: true,
                    //     responsive: true,
                    //     paging: false,
                    //     buttons: [
                    //         {extend: 'print', footer: true },
                    //         {extend: 'excel', text: 'Excel',
                    //             exportOptions: {
                    //                 modifier: {
                    //                     page: 'current'
                    //                 }
                    //             }
                    //         }
                    //     ],
                    // });
                    //
                    // new $.fn.dataTable.FixedHeader( bi_table );

                });

        </script>";

        // -------------------------

        $penjualan_box_tool = "<div class='pull-right box-tools'>";
        $penjualan_box_tool .= "<button type='button' class='btn btn-danger btn-sm pull-right' data-widget='collapse' data-toggle='tooltip' title='' style='margin-right: 5px;' data-original-title='Collapse'><i class='fa fa-plus'></i></button>";
        $penjualan_box_tool .= "</div>";
        //endregion


        $strData = "<div>"; // start div disini

        /* ---------------------------------------------------
         * graph penjualan vs pembelian bulanan
         * ----------------------------------------------*/
        $strData .= "<div class='box box-solid bg-aqua'>";
        $strData .= "<div class='box-header with-border'><h3>Penjualan Vs Pembelian Bulanan</h3></div>";
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
                  .css('backgroundColor', browsersChart.options.lineColors[i])
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

        /* ---------------------------------------------------
         * detile penjualan produk perode tertentu
         * ----------------------------------------------*/
        $penjualanNilai = formatField('no', $itemKolomSum['nilai_af']);
        $strData .= "<div class='box box-solid box-danger collapsed-box'>";
        $strData .= "<div class='box-header with-border'><h3 class='box-title'>";
        $strData .= "Produk Yang Dijual periode $dateStart s/d $dateStop &nbsp;Rp.&nbsp; $penjualanNilai</h3>";
        $strData .= "$penjualan_box_tool";
        $strData .= "</div>";
        $strData .= "<div class='box-body' style='display: none;'>$penjualanProduk</div>";
        $strData .= "<div class='meta text-center'>Untuk menampilkan summary penjualan tiap produk silahkan klik tanda plus</div>";
        $strData .= "</div>";

        /* ---------------------------------------------------
         * graph penjualan harian
         * ----------------------------------------------*/
        $strData .= "<div class='box box-solid box-warning'>";
        $strData .= "<div class='box-header with-border'><h3 class='box-title'>Penjualan Harian</h3></div>";
        $strData .= "<div class='box-body chart' id='line-chart-harian' style='position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);'>";
        $strData .= "</div>";
        $strData .= "<div id='legend-line-chart-harian' class='bars-legend'></div>";

        $strData .= "</div>";
        $strData .= "<script>
        
            var dataDb_2 = $jsonDatasHarian;
            var yKey_2 = $jsonYkeysHarian;
            var yLabel_2 = $jsonYlabelHarian;
        
            config = {
              data: dataDb_2,
              xkey: 'tanggal',
              ykeys: yKey_2,
              labels: yLabel_2,
              fillOpacity: 0.6,
              hideHover: 'auto',
              behaveLikeLine: true,
              resize: true,
              pointFillColors:['#ffffff'],
              pointStrokeColors: ['black'],
              lineColors:['green'],
              parseTime: false,
             };
        
            // config.element = 'area-chart';
            // Morris.Area(config);
            config.element = 'line-chart-harian';
            var browsersChartPenjualanHarian = Morris.Line(config);
        
            browsersChartPenjualanHarian.options.labels.forEach(function(label, i) {
                // bar.options.labels.forEach(function(label, i) {
                    console.log(label);
                var legendItemHarian = $('<span></span>').text( label).prepend(' <span>&nbsp;</span>');
                legendItemHarian.find('span')
                  .css('backgroundColor', browsersChart.options.lineColors[i])
                  .css('width', '20px')
                  .css('display', 'inline-block')
                  .css('margin', '5px');
                $('#legend-line-chart-harian').append(legendItemHarian)
              });
        
                    </script>";
        // -----------------------------------------------end

        $strData .= "</div>";


        //region add to content
        $p->addTags(array(
            // "prop_display"          => $propDisplay,
            "menu_right_isi"   => callMenuRightIsi(),
            "menu_left"        => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            // "data_propose_title"    => $strDataProposeTitle,
            "content_nav"      => $content_nav,
            // "content_nav"      => "",
            "content"          => $strData,

            "stop_time" => "",
            "add_link"  => "",
        ));
        //endregion

        $p->render();
        break;

    case "viewBiPurchasing":
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
        // $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : MODUL_TEMPLATE_PATH ."/template/bi.html";
        $pageTemplate = MODUL_TEMPLATE_PATH . "/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        $vendorID = (isset($vendorId) && ($vendorId != NULL)) ? $vendorId : "0";
        $hipo_target = base_url() . "Bi/createSession";

        //region navigasi atribute
        $arrBiAttr["indeks"] = array(
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "omset (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "stock sett (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label"   => "buffer sett (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label"   => "moq sett (hari)",
            "minimal" => "1",
        );
        //endregion

        $navigasiAttr = $arrBiAttr;
        $hipo_target = base_url() . "addons/Bi/createSession";

        // arrPrint($navigasi);

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

        if (isset($_GET['limit'])) {
            $content_nav .= "<input type='hidden' name='qLimit' id='qLimit' value='$_GET[limit]'>";
            $content_nav .= "<input type='hidden' name='Limit' id='Limit' value='$_GET[limit]'>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";

        $link_biItems = base_url() . "/Bi/viewProdukSales/466/pembelian/";
        $content_nav .= "<button type='button' class='btn btn-success btn-xl' style='margin-left: 5px;' onclick=\"$('#biItems').load('$link_biItems');\">
                                    <i class='fa fa-magic'></i></button> ";
        $content_nav .= "</form>";
        // $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
        //                                 onclick=\"window.location.reload();\">
        //                             <i class='fa fa-refresh'></i></button>";
        //
        $content_nav .= "</div>";
        $content_nav .= "</div>";

        $content_nav = "<div id='wadah'></div>";


        // arrPrint($produks);
        if (isset($produks) && (sizeof($produks) > 0)) {
            // arrPrintWebs($penjualanBulanan);
            $bulans = array();
            $bulanDatas = array();
            foreach ($penjualanBulanan as $thn => $datas_2) {
                foreach ($datas_2 as $bln => $datas_3) {
                    $bulans[] = "$thn<br>$bln";
                    $bulanDatas[] = $datas_3;
                }
            }
            // $jmlBulan = sizeof($bulans);
            $jmlBulan = $periode;
            $jmlBulan_1 = $periode_1;
            $jmlBulan_2 = $periode_2;
            $jmlBulan_3 = $periode_3;

            $heads_1 = array(
                "no",
                "pid",
                // "code",
                "name",
                "satuan",
            );
            $heads_2 = array(
                //------------
                "omset  <div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset' value='$jmlBulan' name='periode' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> hari</div>",
                // "omset 7 <div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset_1' value='$jmlBulan_2' name='periode' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> hari</div>",
                // "omset 30 <div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset_2' value='$jmlBulan_2' name='periode' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> hari</div>",
                // "omset 100 <div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset_3' value='$jmlBulan_3' name='periode' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> hari</div>",
                "rata2 harian",
                "<span class='text-blue'>cadangan (hari)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_buffer_time' value='$limitTime' name='limitTime' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "<span class='text-blue'>cadangan (unit)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_buffer' value='$buffer' name='limit' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "stok tersedia <br>sekarang",
                "<span class='text-green'>umur stok normal <br>(hari periode order)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_lead_time' value='$leadTime' name='leadTime' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "<span class='text-green'>normal stok</span>",
                "index <div><input type='number' class='form-control text-center' style='width: 50px;' value='$indeks' name='indeks' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "order qty",
                "<span class='text-yellow'>minimal satuan <br>order (moq)</span>",
                "order MOQ",
                // "netto",
                // "return",
                // "sales",
                // "<span class='text-yellow'>moq time</span>",


            );
            $head_styles[0] = "style='vertical-align:middle;'";
            $head_styles[] = "style='vertical-align:middle;'";
            $head_styles[] = "style='vertical-align:middle;'";
            $head_styles[] = "style='vertical-align:middle;'";
            $head_styles[5] = "style='vertical-align:middle;'";
            $head_styles[6] = "style='vertical-align:bottom;'";
            $head_styles[] = "style='vertical-align:bottom;'";
            $head_styles[] = "style='vertical-align:middle;'";
            $head_styles[10] = "style='vertical-align:middle;'";
            $head_styles[12] = "style='vertical-align:middle;'";
            $head_styles[] = "style='vertical-align:middle;'";
            $head_styles[] = "style='vertical-align:middle;'";
            // $head_styles[] = "style='vertical-align:middle;'";

            // $heads = array_merge($heads_1, $bulans, $heads_2);
            $heads = array_merge($heads_1, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($heads as $key => $label) {
                $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $tblHeads .= "<th class='text-center' $attr title='$key'>$label</th>";
            }

            $cekAll = "<input type='checkbox' class='calcCheckAll' onclick='calcCheckAll(this)'>";
            $tblHeads .= "<th class='text-center' style='vertical-align: middle;'>$cekAll</th>";

            $tblHeads .= "</tr>";
            $tblHeads .= "</thead>";


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
                $limit_db = $val->limit;
                $limit_time = $val->limit_time;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $moq = $val->moq;
                $moq_time = $val->moq_time;
                $kode = $val->kode;
                $satuan = $val->satuan;

                // $isi[] = array('data' => "$value ", 'class' => 'text-left');
                //
                $link_buffer = MODUL_PATH . "Bi/updateProdukLimit/$id";
                $link_bufferTime = MODUL_PATH . "Bi/updateProdukLimitTime/$id";
                $link_indeks = MODUL_PATH . "Bi/updateProdukIndeks/$id";
                $link_leadTime = MODUL_PATH . "Bi/updateProdukLeadTime/$id";
                $link_moqTime = MODUL_PATH . "Bi/updateProdukMoqTime/$id";
                $link_moq = MODUL_PATH . "Bi/updateProdukMoq/$id";
                $link_katalog = MODUL_PATH . "Katalog/viewProduk?q=$kode";
                $link_ceklist = MODUL_PATH . "Bi/checklistBi/$vendorID/?mode=item&pid=$id";


                $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
                //     $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
                //     $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
                //     $stok_net = $stok_out - $stok_in;
                //
                $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' target='_blank'>$stok_now</a>";
                //     $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
                //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
                //     $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;
                //     // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
                //     $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
                //     $newPox = $newPo > 0 ? $newPo : 0;
                //     $newPo_f = formatField("stok", $newPox);
                //     $ideal_stok_f = formatField("stok", $ideal_stok);

                $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
                $bgDb_b = $limit_db > 0 ? "bg-danger" : "";
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
                $tblBodies .= "<tr class='$bg_color' style='' id='row_$id'>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$no</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-center text-bold'>" . $val->id . "</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-bold'>$kode</td>";
                $tblBodies .= "<td style='white-space: nowrap;'>" . $val->nama . "</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-center'>$satuan</td>";

                foreach ($bulanDatas as $bulanData) {
                    $stok_out_0 = isset($bulanData[$id]) ? $bulanData[$id]['unit_af'] : 0;
                    // $tblBodies .= "<td class='text-right bg-yellow-light'>$stok_out</td>";

                    if (!isset($jml{$id})) {

                        $jml[$id] = 0;
                    }
                    $jml[$id] += $stok_out_0;
                }
                // arrPrint();

                $stok_out = isset($jml[$id]) ? $jml[$id] : 0;
                $avg = $stok_out > 0 ? ($stok_out / $jmlBulan) : 0;
                $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

                $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
                $limitTimex = $limit_time > 0 ? $limit_time : $limitTime; // buffer_time
                $moqTimex = $moq_time > 0 ? $moq_time : $moqTime;
                $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;
                $limitx = $limit_db > 0 ? $limit_db : $limit; // buffer

                $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
                $bufferx = $limit_db > 0 ? $limitx : ($avg * $limitTimex);
                $bufferx_f = number_format($bufferx, 0);
                $moqx_f = number_format($moqx, 0);
                // cekPink("$limitx = $limit_db > 0 ? $limit_db : $limit;");
                /* ------------------------------------------
                 * stok ideal stok (stok normal) dengan index
                 * ------------------------------------------*/
                // $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;
                /* ------------------------------------------
                 * stok ideal tanpa index
                 * avg *
                 * ------------------------------------------*/
                $ideal_stok = ($avg * $leadTimex) + $bufferx;
                $ideal_stok_f = ceil($ideal_stok);

                // cekMerah("$ideal_stok"." || ".$stok_now);
                /* ------------------------------------------
                 * order stok qty
                 * ------------------------------------------*/
                // $newPo = ($ideal_stok) - ($stok_now);
                $newPo = ($ideal_stok - $stok_now) * $indeksx / 100;
                // cekPink("$id ==== $newPo = ($ideal_stok) - ($stok_now) * $indeksx / 100;");
                $newPox = $newPo > 0 ? $newPo : 0;
                $newPo_f = ceil($newPox);

                /* ------------------------------------------
                 * order stok sesuai MOQ
                 * ------------------------------------------*/
                $newPoMoq = mround($newPo_f, $moqx_f);

                $strIndex = "<input type='number' tabindex='$xi' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" 
onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');hitung_order(this.value,$id);\">";
                $strBuffer = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none $bgDb_b' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" 
onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');hitung_normal_stok(this.value,$id);\">";
                $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin border-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
                $strMoq = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin border-none $bgDb_m' style='width: 50px' value='$moqx_f' onclick=\"this.select();\" 
onblur=\"getData('$link_moq?v='+this.value,'update_buffer');hitung_order_moq(this.value, $id, $newPox);\">";

                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-danger text-bold'>$stok_out</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-warning text-bold'>$stok_out_1</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-warning text-bold'>$stok_out_2</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-warning text-bold'>$stok_out_3</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='avg_$id'>$avg_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$limitTime</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='buffer_$id'>$strBuffer</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='stok_now_$id'>$stok_now_l</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strLeadTime</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='ideal_stok_$id'>$ideal_stok_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strIndex</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-success font-size-1-2' id='order_$id'>$newPo_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strMoq</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-success font-size-1-2' id='order_moq_$id'>$newPoMoq</td>";

                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strMoqTime</td>";

                /* ----------------------------------------------------------------
                 * order dari qty
                 * ----------------------------------------------------------------
                 */
                // $ceklist = "<input type='checkbox' povalue='$newPo_f' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
                // $arrProdukID_order[$id] = $newPo_f;
                /* ----------------------------------------------------------------
                 * order dari MOQ
                 * ----------------------------------------------------------------
                 */
                $ceklist = "<input type='checkbox' povalue='$newPoMoq' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
                $arrProdukID_order[$id] = $newPoMoq;

                $tblBodies .= "<td class='text-center bg-success'>$ceklist</td>";

                $tblBodies .= "</tr>";

                /* ----------------------------------------------------------------
                 * order dari qty
                 * ----------------------------------------------------------------
                 */
                // $arrProdukID_order[$id] = $newPo_f;
                /* ----------------------------------------------------------------
                 * order dari MOQ
                 * ----------------------------------------------------------------
                 */
                // $arrProdukID_order[$id] = $newPoMoq;
            }
            // arrPrintHijau($arrProdukID_order);
            // arrPrintHijau($arrProdukID_order2);

            $tblBodies .= "</tbody>";
            /* ---------------------------------------------------------
             * funsi itunganan ui
             * ---------------------------------------------------------*/
            $tblBodies .= "<script>
                function RoundTo(number, roundto){
                  return roundto * Math.ceil(number/roundto);
                }
                
                function hitung_order_moq(n,t,x) {
                    console.log(n);
                    
                    var ss = RoundTo(x,n);
                    $('#order_moq_'+t).text(ss);
                    $('#row_'+t).attr('style','background-color:yellow');
                }
                
                function hitung_normal_stok(n,t) {
                  var ss = n; 
                    $('#ideal_stok__'+t).text(ss);
                }

                function reload_page(n,t){
                    var oldValue = localStorage.getItem('oldValue');
                    
                    if(n != oldValue){                        
                        document.location.reload();
                    }                    
                }
                
                function hitung_order(n,t) {
                    var stok_now = $('stok_now_'+t).text();
                    var stok_ideal = $('ideal_stok_'+t).text();                    
                    var new_nilai = (stok_ideal - stok_now) * n / 100;
                    console.log(n);
                    console.log(t);
                    console.log(stok_now);
                    console.log(stok_ideal);
                    
                    $('#order_'+t).text(new_nilai);
                    $('#row_'+t).attr('style','background-color:yellow');
                }

                
            </script>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
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

        // cekHijau("$btnToShoppingCart");
        if (isset($btnToShoppingCart) && ($btnToShoppingCart != NULL)) {
            if (isset($arrProdukID_order) && (sizeof($arrProdukID_order) > 0)) {
                $arrProdukID_order_blob = blobEncode($arrProdukID_order);
                $link = "document.getElementById('result').src='$btnToShoppingCart?items=$arrProdukID_order_blob'";
                $btn = "<div cclass='pull-right' style='text-align: right;margin-top: 25px;'>";
                $btn .= "<button id='btnCreateShoppingCart' class='btn btn-warning'>";
                //                $btn .= "<button id='btnCreateShoppingCart' class='btn btn-warning' onclickxx=\"$link\">";
                $btn .= "<span>Masukkan ke Shopping Cart</span>";
                $btn .= "</button>";
                $btn .= "</div>";
            }
        }
        $strDataPropose = "";
        $strDataPropose .= "<table class='table table-hover compact table-condensed' id='bi_table'>";
        $strDataPropose .= $tblHeads;
        $strDataPropose .= $tblBodies;
        $strDataPropose .= "</table>";
        $strDataPropose .= "<div id='update_buffer'></div>";


        //region legenda koloms diatus dari heBi
        $content_note = "";
        foreach ($notes as $legenda => $lNote) {

            $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
            $content_note .= "<span class='text-primary text-uppercase'>$legenda</span> : ";
            $content_note .= "$lNote";
            $content_note .= "</p> ";
        }
        $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
        $content_note .= "<span class='label bg-danger text-black'>&nbsp;##&nbsp;</span> : ";
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
        $str = "<style type='text/css'>
            .dt-button {
                font-size: 1.12em !important;
            }
        </style>";
        $str .= "<div class='box box-danger'>";
        $str .= "<div class='box-header with-border text-green'>";
        $str .= "<h4 class='no-padding no-margin text-black'><span class=\"glyphicon glyphicon-th-list\"></span> CALCULATOR STOCK</h4> <h1 class='no-padding no-margin text-uppercase'>$vendorNama_f <small style='color:red;'>$jumlahProduk_f</small></h1>";
        $str .= "</div class='box box-header'>";
        $str .= "<div class='box-body'>";
        $str .= $content_nav;
        // $str .= $content_note;
        $str .= $strDataPropose;
        $str .= isset($btn) ? $btn : "";
        $str .= "</div class='box-body'>";
        $str .= "</div class='box box-danger'>";

        // ?v='+this.value+'&n='+this.name,'wadah');";
        $str .= "\n\n<script>

                    setTimeout( function(){
                        if($('#bi_table thead th').length>1){
                            $('#bi_table').DataTable({
                                initComplete: function () {
                                    // Apply the search
                                    this.api().columns().every( function () {
                                        // var that = this;
                                        //
                                        // $( 'input', this.header() ).on( 'keyup change clear', function () {
                                        //     if ( that.search() !== this.value ) {
                                        //         that
                                        //             .search( this.value )
                                        //             .draw();
                                        //     }
                                        // });
                                        
                                        $('input', this.header()).on('click', function(e) {
                                            e.stopPropagation();
                                        });                                                                                        
                                                                                    
                                    });                                                                                                                                       
                                },
                                dom: 'lBfrtip',
                                order: [[ 14, 'desc' ]],
                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                pageLength: -1,
                                paging: false,
                                info: false,
                                stateSave: true,
                                buttons: [
                                    {
                                        text: 'omset 7 hari',
                                        action: function ( e, dt, node, config ) {
                                            $('#wadah').load('$this_domain?v=7&n=periode');
                                            document.location.reload();
                                        }
                                    },
                                    {
                                        text: 'omset 14 hari',
                                        action: function ( e, dt, node, config ) {
                                             $('#wadah').load('$this_domain?v=14&n=periode');
                                            document.location.reload();
                                        }
                                    },
                                    {
                                        text: 'omset <b>30</b> hari',
                                        action: function ( e, dt, node, config ) {
                                             $('#wadah').load('$this_domain?v=30&n=periode');
                                            document.location.reload();
                                        }
                                    },   
                                 ]
                            });
                            $('#bi_table_wrapper').addClass('table-responsive');
                        }
                        else{
                            $('#bi_table').DataTable({
                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                pageLength: -1,
                                paging: false,
                            });
                            $('#bi_table_wrapper').addClass('table-responsive');
                        }
                        
                    }, 1000);

                    function initBtn(){
                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
                        var arrValid = {}
                        if(arrCheck.length > 0){
                            jQuery.each(arrCheck, function(i, b){
                                var povalue = $(b).attr('povalue');
                                var id = $(b).attr('id');
                                if( $(b).is(':checked') ){
                                    arrValid[id] = povalue
                                }
                            })
                        }
                        if(Object.keys(arrValid).length === 0){
                            $('#btnCreateShoppingCart')
                            .removeClass('btn-warning')
                            .addClass('btn-default')
                            .off('click')
                        }
                        else{
                            $('#btnCreateShoppingCart')
                            .off('click')
                            .removeClass('btn-default')
                            .addClass('btn-warning')
                            .on('click', function(){
                                $.ajax({
                                    type: 'POST',
                                    url: '$btnToShoppingCart',
                                    data: { items: btoa(JSON.stringify(arrValid)) },
                                    success: function(data) {
                                        var arrData = JSON.parse(data)
                                        console.log(data);
                                        console.log(arrData.status);
                                        if(arrData.status==1){
                                            if(top.document.getElementById('shopping_cart')){
                                                top.$('#shopping_cart').load(arrData.url);
                                            };
                                        }
                                    },
                                    error: function(){
                                        swal('koneksi error');
                                        HoldOn.close()
                                    }
                                });
                            })
                        }
                    }
                    initBtn()
                    
                    function calcCheckAll(e){
                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
                        if(arrCheck.length > 0){
                            jQuery.each(arrCheck, function(i, b){
                                if( $(e).is(':checked') ){
                                    $(b).prop('checked', true)
                                    initBtn()
                                }
                                else{
                                    $(b).prop('checked', false)
                                    initBtn()
                                }
                            })
                        }
                    }
                    
                    function resetCalcCheckAll(){
                        $('.calcCheckAll').prop('checked', false);
                    }
                    
                \n</script>";

        /*
         * kala mau melihat langsung tambahkan tpl=1 pada url
         * else digunakan kalau akan diload di pembelian 466
         */
        if (isset($_GET['tpl']) && $_GET['tpl'] == 1) {

            $p->addTags(array(
                // "prop_display"          => $propDisplay,
                "menu_right_isi"   => callMenuRightIsi(),
                "menu_left"        => callMenuLeft(),
                //                "trans_menu" => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                // "data_propose_title"    => $strDataProposeTitle,
                "add_link"         => "",
                "content_nav"      => "",
                "content"          => $str,

                "stop_time" => "",
            ));
            $p->render();
        }
        else {
            if (isset($jenisTr) && ($jenisTr == 466)) {

                echo $str;
            }
        }


        break;
    /*-------------ctr viewProdukSales_2----------------*/
    case "viewBiPurchasing_2":
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
        // $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : MODUL_TEMPLATE_PATH ."/template/bi.html";
        $pageTemplate = MODUL_TEMPLATE_PATH . "/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        $vendorID = (isset($vendorId) && ($vendorId != NULL)) ? $vendorId : "0";
        // $hipo_target = base_url() . "Bi/createSession";

        //region navigasi atribute
        $arrBiAttr["indeks"] = array(
            "label"   => "index",
            "minimal" => "100",
        );
        $arrBiAttr["buffer"] = array(
            "label"   => "buffer per hari",
            "minimal" => "1",
        );
        $arrBiAttr["periode"] = array(
            "label"   => "omset (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["leadTime"] = array(
            "label"   => "stock sett (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["limitTime"] = array(
            "label"   => "buffer sett (hari)",
            "minimal" => "1",
        );
        $arrBiAttr["moqTime"] = array(
            "label"   => "moq sett (hari)",
            "minimal" => "1",
        );
        //endregion

        $navigasiAttr = $arrBiAttr;
        // $hipo_target = base_url() . "addons/Bi/createSession";
        $hipo_target = $this_domain = MODUL_PATH . "Bi/createSession";
        $this_bi_items = MODUL_PATH . "Bi/viewProdukSales_2/466/pembelian";

        // arrPrint($navigasi);

        $content_nav = "<div class='col-md-12'>";
        $content_nav .= "<div class='form-inline'>";
        $content_nav .= "<form method='get'>";
        foreach ($navigasi as $keyNav => $valNav) {
            $labelNav = isset($navigasiAttr[$keyNav]["label"]) ? $navigasiAttr[$keyNav]["label"] : "";
            $minimal = isset($navigasiAttr[$keyNav]["minimal"]) ? $navigasiAttr[$keyNav]["minimal"] : "";
            $valNavX = isset($_GET[$keyNav]) ? $_GET[$keyNav] : $valNav;
            $content_nav .= "<div class='form-group' style='margin-left: 5px;'>
                                    <label>$labelNav: </label>
                                    <input name='$keyNav' id='$keyNav' class='form-control' style='width: 50px;' type='number' value='$valNavX' onclick=\"this.select();\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" min='$minimal'>
                                </div>";
        }

        if (isset($_GET['limit'])) {
            $content_nav .= "<input type='hidden' name='qLimit' id='qLimit' value='$_GET[limit]'>";
            $content_nav .= "<input type='hidden' name='Limit' id='Limit' value='$_GET[limit]'>";
        }
        $content_nav .= "<button type='submit' class='btn btn-primary btn-xl' style='margin-left: 5px;'>
                                    <i class='fa fa-refresh'></i></button> ";

        $link_biItems = base_url() . "/Bi/viewProdukSales/466/pembelian/";
        $content_nav .= "<button type='button' class='btn btn-success btn-xl' style='margin-left: 5px;' onclick=\"$('#biItems').load('$link_biItems');\">
                                    <i class='fa fa-magic'></i></button> ";
        $content_nav .= "</form>";
        // $content_nav .= "<button type='button' class='btn btn-primary btn-xl' style='margin-left: 5px;'
        //                                 onclick=\"window.location.reload();\">
        //                             <i class='fa fa-refresh'></i></button>";
        //
        $content_nav .= "</div>";
        $content_nav .= "</div>";

        $content_nav = "<div id='wadah'></div>";

        /* -----------------------------------------------
         * penyusun data utama
         * -----------------------------------------------*/
        // arrPrint($produks);
        if (isset($produks) && (sizeof($produks) > 0)) {

            $jmlBulan = $periode;
            $jmlBulan_1 = $periode_1;
            $jmlBulan_2 = $periode_2;
            $jmlBulan_3 = $periode_3;

            $checked_1 = $periode == $periode_1 ? "checked" : "";
            $checked_2 = $periode == $periode_2 ? "checked" : "";
            $checked_3 = $periode == $periode_3 ? "checked" : "";

            $heads_1 = array(
                "no",
                "pid",
                // "code",
                "name",
                "satuan dasar",
            );
            $heads_2 = array(
                "omset unit <br>$jmlBulan hari",

                "rata2 harian dalam omset unit",

                // "rata2 harian <br>(omset $jmlBulan hari)",
                "<span class='text-blue'>cadangan (hari)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_buffer_time' value='$limitTime' name='limitTime' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "<span class='text-blue'>cadangan (unit)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_buffer' value='$buffer' name='limit' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "stok tersedia <br>sekarang<br>(unit)",
                "<span class='text-green'>periode order/<br>umur stok reguler (hari)</span> <div><input type='number' class='form-control text-center' style='width: 50px;' id='global_lead_time' value='$leadTime' name='leadTime' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "<span class='text-green'>reguler <br>stok <br>satuan <br>dasar</span>",
                "index <div><input type='number' class='form-control text-center' style='width: 50px;' value='$indeks' name='indeks' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . "\" onblur=\"reload_page(this.value, this.id);\"></div>",
                "order qty <br>satuan dasar",
                "<span class='text-yellow'>minimal order <br>satuan dasar</span>",
                "order MOQ <br>(satuan khusus)",
                // "netto",
                // "return",
                // "sales",
                // "<span class='text-yellow'>moq time</span>",


            );

            $head_sub = array(
                // "omset  <div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset' value='$jmlBulan' name='periode' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> hari</div>",
                "<div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset' value='$periode_1' name='periode_1' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> <input type='radio' name='omsetnya' onclick=\"$('#wadah').load('$this_domain?v=$periode_1&n=periode');\" $checked_1> hari</div>",
                "<div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset' value='$periode_2' name='periode_2' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> <input type='radio' name='omsetnya' onclick=\"$('#wadah').load('$this_domain?v=$periode_2&n=periode');\" $checked_2> hari</div>",
                "<div><input type='number' class='form-control text-center' style='width: 40px;' id='global_omset' value='$periode_3' name='periode_3' onclick=\"this.select();localStorage.setItem('oldValue',this.value);\" onkeyup=\"" . sendToSession($hipo_target, 'wadah') . ";\" onblur=\"reload_page(this.value, this.id);\"> <input type='radio' name='omsetnya' onclick=\"$('#wadah').load('$this_domain?v=$periode_3&n=periode');\" $checked_3> hari</div>",
            );

            $head_styles[0] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:bottom;' colspan='3'";
            // $head_styles[6] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:bottom;' rowspan='2'";
            $head_styles[] = "style='vertical-align:bottom;' rowspan='2'";
            $head_styles[8] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:bottom;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[11] = "style='vertical-align:bottom;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[13] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            $head_styles[] = "style='vertical-align:middle;' rowspan='2'";
            // $head_styles[] = "style='vertical-align:middle;'";

            // $heads = array_merge($heads_1, $bulans, $heads_2);
            $heads = array_merge($heads_1, $heads_2);
            $jmlKolom = sizeof($heads);
            $tblHeads = "<thead>";
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($heads as $key => $label) {
                $attr = isset($head_styles[$key]) ? $head_styles[$key] : "";
                $tblHeads .= "<th class='text-center' $attr title='$key'>$label</th>";
            }

            $cekAll = "<input type='checkbox' class='calcCheckAll' onclick='calcCheckAll(this)'>";
            $tblHeads .= "<th class='text-center' style='vertical-align: middle;' rowspan='2'>$cekAll</th>";

            $tblHeads .= "</tr>";

            /*
             * sub head
             */
            $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
            foreach ($head_sub as $item_head) {
                $tblHeads .= "<th class='text-center' style='vertical-align: middle;'>$item_head</th>";
            }
            $tblHeads .= "</tr>";

            $tblHeads .= "</thead>";

            $bg_avg_1 = $periode == $periode_1 ? "bg-danger" : "bg-warning";
            $bg_avg_3 = $periode == $periode_3 ? "bg-danger" : "bg-warning";
            $bg_avg_2 = $periode == $periode_2 ? "bg-danger" : "bg-warning";

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
                $limit_db = $val->limit;
                $limit_time = $val->limit_time;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $moq = $val->moq;
                $moq_time = $val->moq_time;
                $kode = $val->kode;
                $satuan = $val->satuan;

                // $isi[] = array('data' => "$value ", 'class' => 'text-left');
                //
                $link_buffer = MODUL_PATH . "Bi/updateProdukLimit/$id";
                $link_bufferTime = MODUL_PATH . "Bi/updateProdukLimitTime/$id";
                $link_indeks = MODUL_PATH . "Bi/updateProdukIndeks/$id";
                $link_leadTime = MODUL_PATH . "Bi/updateProdukLeadTime/$id";
                $link_moqTime = MODUL_PATH . "Bi/updateProdukMoqTime/$id";
                $link_moq = MODUL_PATH . "Bi/updateProdukMoq/$id";
                $link_katalog = MODUL_PATH . "Katalog/viewProduk?q=$kode";
                $link_ceklist = MODUL_PATH . "Bi/checklistBi/$vendorID/?mode=item&pid=$id";


                $stok_now = isset($stokNow[$val->id]) ? $stokNow[$id]["qty_debet_sum"] : 0;
                //     $stok_out = isset($penjualan[$val->id]) ? $penjualan[$id]["qty_kredit_sum"] : 0;
                //     $stok_in = isset($returnPenjualan[$id]) ? $returnPenjualan[$id]["qty_debet_sum"] : 0;
                //     $stok_net = $stok_out - $stok_in;
                //

                //     $avg = $stok_net > 0 ? ($stok_net / $periode) : 0;
                //     $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;
                //     $ideal_stok = ($avg * ($indeks / 100)) + $bufferx;
                //     // $newPo = (($leadTimex / 100) * $avg) - ($stok_now + $bufferx);
                //     $newPo = (($leadTimex / 100) * $ideal_stok) - ($stok_now);
                //     $newPox = $newPo > 0 ? $newPo : 0;
                //     $newPo_f = formatField("stok", $newPox);
                //     $ideal_stok_f = formatField("stok", $ideal_stok);

                $bgDb_bt = $limit_time > 0 ? "bg-danger" : "";
                $bgDb_b = $limit_db > 0 ? "bg-danger" : "";
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
                $tblBodies .= "<tr class='$bg_color' style='' id='row_$id'>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$no</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-center text-bold'>" . $val->id . "</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-bold'>$kode</td>";
                $tblBodies .= "<td style='white-space: nowrap;'>" . $val->nama . "</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-center'>$satuan</td>";

                $stok_out_1 = isset($omset_1[$id]) ? $omset_1[$id] : 0;
                $stok_out_2 = isset($omset_2[$id]) ? $omset_2[$id] : 0;
                $stok_out_3 = isset($omset_3[$id]) ? $omset_3[$id] : 0;
                $stok_out_1_f = number_format($stok_out_1, 2);
                $stok_out_2_f = number_format($stok_out_2, 2);
                $stok_out_3_f = number_format($stok_out_3, 2);


                // arrPrint();
                $jml = $omset_xd;
                $stok_out = isset($jml[$id]) ? $jml[$id] : 0;
                $avg = $stok_out > 0 ? ($stok_out / $jmlBulan) : 0;
                $avg_f = $avg > 0 ? formatField("angka", $avg) : 0;

                $leadTimex = $lead_time > 0 ? $lead_time : $leadTime;
                $limitTimex = $limit_time > 0 ? $limit_time : $limitTime; // buffer_time
                $moqTimex = $moq_time > 0 ? $moq_time : $moqTime;
                $indeksx = $indeks_db > 0 ? $indeks_db : $indeks;
                $limitx = $limit_db > 0 ? $limit_db : $limit; // buffer

                $moqx = $moq > 0 ? ($moq * $moqTimex) : ($avg * $moqTimex);
                $bufferx = $limit_db > 0 ? $limitx : ($avg * $limitTimex);
                $bufferx_f = number_format($bufferx, 0);
                $moqx_f = number_format($moqx, 0);
                // cekPink("$limitx = $limit_db > 0 ? $limit_db : $limit;");
                /* ------------------------------------------
                 * stok ideal stok (stok normal) dengan index
                 * ------------------------------------------*/
                // $ideal_stok = ($avg * ($indeks / 100)) * ($leadTimex / 1) + $bufferx;
                /* ------------------------------------------
                 * stok ideal tanpa index
                 * avg *
                 * ------------------------------------------*/
                $ideal_stok = ($avg * $leadTimex) + $bufferx;
                $ideal_stok_f = $ideal_stok > 0 ? ceil($ideal_stok) : "n/a";

                $warna_stok_lebih = $stok_now > $ideal_stok ? "text-red font-size-1-2 text-bold" : "";
                $stok_now_l = "<a href='$link_katalog' title='lokasi persediaan' target='_blank' class='$warna_stok_lebih'>$stok_now</a>";

                // cekMerah("$ideal_stok"." || ".$stok_now);
                /* ------------------------------------------
                 * order stok qty
                 * ------------------------------------------*/
                // $newPo = ($ideal_stok) - ($stok_now);
                $newPo = ($ideal_stok - $stok_now) * $indeksx / 100;
                // cekPink("$id ==== $newPo = ($ideal_stok) - ($stok_now) * $indeksx / 100;");
                $newPox = $newPo > 0 ? $newPo : 0;
                $newPo_f = ceil($newPox);

                /* ------------------------------------------
                 * order stok sesuai MOQ
                 * ------------------------------------------*/
                $newPoMoq = mround($newPo_f, $moqx_f);

                $strIndex = "<input type='number' tabindex='$xi' name='indeks' id='indeks_$id' class='text-center no-padding no-margin border-none $bgDb_i' style='width: 50px' value='$indeksx' onclick=\"this.select();\" 
onblur=\"getData('$link_indeks?v='+this.value,'update_buffer');hitung_order(this.value,$id);\">";
                $strBuffer = "<input type='number' tabindex='$xb' name='buffer' id='buffer_$id' class='text-center no-padding no-margin border-none $bgDb_b' style='width: 50px' value='$bufferx_f' onclick=\"this.select();\" onblur=\"getData('$link_buffer?v='+this.value,'update_buffer');\">";
                $strBufferTime = "<input type='number' tabindex='$xbt' name='bufferTime' id='bufferTime_$id' class='text-center no-padding no-margin border-none $bgDb_bt' style='width: 50px' value='$limitTimex' onclick=\"this.select();\" onblur=\"getData('$link_bufferTime?v='+this.value,'update_buffer');\">";
                $strLeadTime = "<input type='number' tabindex='$xlt' name='leadTime' id='leadTime_$id' class='text-center no-padding no-margin border-none $bgDb_lt' style='width: 50px' value='$leadTimex' onclick=\"this.select();\" 
onblur=\"getData('$link_leadTime?v='+this.value,'update_buffer');hitung_normal_stok(this.value,$id);\">";
                $strMoqTime = "<input type='number' tabindex='$xmt' name='moqTime' id='moqTime_$id' class='text-center no-padding no-margin border-none $bgDb_mt' style='width: 50px' value='$moqTimex' onclick=\"this.select();\" onblur=\"getData('$link_moqTime?v='+this.value,'update_buffer');\">";
                $strMoq = "<input type='number' tabindex='$xm' name='moq' id='moq_$id' class='text-center no-padding no-margin border-none $bgDb_m' style='width: 50px' value='$moqx_f' onclick=\"this.select();\" 
onblur=\"getData('$link_moq?v='+this.value,'update_buffer');hitung_order_moq(this.value, $id, $newPox);\">";

                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-danger text-bold'>$stok_out</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-danger'>$stok_out</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right $bg_avg_1 text-bold'>$stok_out_1_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right $bg_avg_2 text-bold'>$stok_out_2_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right $bg_avg_3 text-bold'>$stok_out_3_f</td>";
                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='avg_$id'>$avg_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$limitTime</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='buffer_$id'>$strBuffer</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='stok_now_$id'>$stok_now_l</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strLeadTime</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' id='ideal_stok_$id' data-order='$ideal_stok'>$ideal_stok_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strIndex</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-success font-size-1-2' id='order_$id'>$newPo_f</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right' data-order='$moqx_f'>$strMoq</td>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right bg-success font-size-1-2' id='order_moq_$id'>$newPoMoq</td>";

                // $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$strMoqTime</td>";

                /* ----------------------------------------------------------------
                 * order dari qty
                 * ----------------------------------------------------------------
                 */
                // $ceklist = "<input type='checkbox' povalue='$newPo_f' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
                // $arrProdukID_order[$id] = $newPo_f;
                /* ----------------------------------------------------------------
                 * order dari MOQ
                 * ----------------------------------------------------------------
                 */
                $ceklist = "<input type='checkbox' povalue='$newPoMoq' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
                $arrProdukID_order[$id] = $newPoMoq;

                $tblBodies .= "<td class='text-center bg-success'>$ceklist</td>";

                $tblBodies .= "</tr>";

                /* ----------------------------------------------------------------
                 * order dari qty
                 * ----------------------------------------------------------------
                 */
                // $arrProdukID_order[$id] = $newPo_f;
                /* ----------------------------------------------------------------
                 * order dari MOQ
                 * ----------------------------------------------------------------
                 */
                // $arrProdukID_order[$id] = $newPoMoq;
            }
            // arrPrintHijau($arrProdukID_order);
            // arrPrintHijau($arrProdukID_order2);

            $tblBodies .= "</tbody>";
            /* ---------------------------------------------------------
             * funsi itunganan ui
             * ---------------------------------------------------------*/
            $tblBodies .= "<script>
                function RoundTo(number, roundto){
                  return roundto * Math.ceil(number/roundto);
                }
                
                function hitung_order_moq(n,t,x) {
                    console.log(n);
                    
                    var ss = RoundTo(x,n);
                    $('#order_moq_'+t).text(ss);
                    $('#row_'+t).attr('style','background-color:yellow');
                }
                
                function hitung_normal_stok(n,t) {
                  var ss = n; 
                    $('#ideal_stok__'+t).text(ss);
                }

                function reload_page(n,t){
                    var oldValue = localStorage.getItem('oldValue');
                    
                    if(n != oldValue){                        
                        document.location.reload();
                    }                    
                }
                
                function hitung_order(n,t) {
                    var stok_now = $('stok_now_'+t).text();
                    var stok_ideal = $('ideal_stok_'+t).text();                    
                    var new_nilai = (stok_ideal - stok_now) * n / 100;
                    console.log(n);
                    console.log(t);
                    console.log(stok_now);
                    console.log(stok_ideal);
                    
                    $('#order_'+t).text(new_nilai);
                    $('#row_'+t).attr('style','background-color:yellow');
                }

                
            </script>";

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
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
            $tblBodies = "<td colspan=''>Silahkan tentukan VENDOR terlebih dahulu atau belum ada relasi Vendor dengan produk.</td>";
            // $tblBodies .= "</tr>";
            // $tblBodies .= "</tbody>";
            $isi = tplNoData("<r>Bila supplier belum dipilih, Silahkan tentukan supplier terlebih dahulu  <br> Bila sudah ditentukan, berarti supplier belum ada relasi dengan produk maka harus direlasikan terlebih dahulu</r>");
            $tblBodies = "<div>$isi</td>";
        }

        // cekHijau("$btnToShoppingCart");
        if (isset($btnToShoppingCart) && ($btnToShoppingCart != NULL)) {
            if (isset($arrProdukID_order) && (sizeof($arrProdukID_order) > 0)) {
                $arrProdukID_order_blob = blobEncode($arrProdukID_order);
                $link = "document.getElementById('result').src='$btnToShoppingCart?items=$arrProdukID_order_blob'";
                $btn = "<div cclass='pull-right' style='text-align: right;margin-top: 25px;'>";
                $btn .= "<button id='btnCreateShoppingCart' class='btn btn-warning'>";
                //                $btn .= "<button id='btnCreateShoppingCart' class='btn btn-warning' onclickxx=\"$link\">";
                $btn .= "<span>Masukkan ke Shopping Cart</span>";
                $btn .= "</button>";
                $btn .= "</div>";
            }
        }
        $strDataPropose = "";
        $strDataPropose .= "<table class='table table-hover compact table-condensed' id='bi_table'>";
        $strDataPropose .= $tblHeads;
        $strDataPropose .= $tblBodies;
        $strDataPropose .= "</table>";
        $strDataPropose .= "<div id='update_buffer'></div>";


        //region legenda koloms diatus dari heBi
        $content_note = "";
        foreach ($notes as $legenda => $lNote) {

            $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
            $content_note .= "<span class='text-primary text-uppercase'>$legenda</span> : ";
            $content_note .= "$lNote";
            $content_note .= "</p> ";
        }
        $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
        $content_note .= "<span class='label bg-danger text-black'>&nbsp;##&nbsp;</span> : ";
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
        $str = "<style type='text/css'>
            .dt-button {
                font-size: 1.12em !important;
            }
        </style>";
        $str .= "<div class='box box-danger'>";
        $str .= "<div class='box-header with-border text-green'>";
        $str .= "<h4 class='no-padding no-margin text-black'><span class=\"glyphicon glyphicon-th-list\"></span> CALCULATOR STOCK</h4> <h1 class='no-padding no-margin text-uppercase'>$vendorNama_f <small style='color:red;'>$jumlahProduk_f</small></h1>";

        $str .= "<div class='box-tools pull-right'>";
        $str .= "<button type='button' class='btn btn-box-tool text-red' data-widget='collapse' title='sembunyikan atau memunculkan data'>sembunyikan&nbsp; <i class='fa fa-minus'></i></button>";
        $str .= "</div>";

        $str .= "</div>";
        $str .= "<div class='box-body'>";
        $str .= $content_nav;
        // $str .= $content_note;
        $str .= $strDataPropose;
        $str .= isset($btn) ? $btn : "";
        $str .= "</div class='box-body'>";
        $str .= "</div class='box box-danger'>";
        $this_domain = MODUL_PATH . "/Bi/createSession";
        // ?v='+this.value+'&n='+this.name,'wadah');";
        $str .= "\n\n<script>

                    setTimeout( function(){
                        if($('#bi_table thead th').length>1){
                            $('#bi_table').DataTable({
                                initComplete: function () {
                                    // Apply the search
                                    this.api().columns().every( function () {
                                        // var that = this;
                                        //
                                        // $( 'input', this.header() ).on( 'keyup change clear', function () {
                                        //     if ( that.search() !== this.value ) {
                                        //         that
                                        //             .search( this.value )
                                        //             .draw();
                                        //     }
                                        // });
                                        
                                        $('input', this.header()).on('click', function(e) {
                                            e.stopPropagation();
                                        });                                                                                        
                                                                                    
                                    });                                                                                                                                       
                                },
                                dom: 'lBfrtip',
                                order: [[ 0, 'asc' ]],
                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                pageLength: -1,
                                paging: false,
                                info: false,
                                stateSave: true,
                                buttons: [
                                    {
                                        text: 'omset 7 hari',
                                        action: function ( e, dt, node, config ) {
                                            $('#wadah').load('$this_domain?v=7&n=periode');
                                            document.location.reload();
                                        }
                                    },
                                    {
                                        text: 'omset 14 hari',
                                        action: function ( e, dt, node, config ) {
                                             $('#wadah').load('$this_domain?v=14&n=periode');
                                            document.location.reload();
                                        }
                                    },
                                    {
                                        text: 'omset <b>30</b> hari',
                                        action: function ( e, dt, node, config ) {
                                             $('#wadah').load('$this_domain?v=30&n=periode');
                                            document.location.reload();
                                        }
                                    },   
                                 ]
                            });
                            $('#bi_table_wrapper').addClass('table-responsive');
                        }
                        else{
                            $('#bi_table').DataTable({
                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                pageLength: -1,
                                paging: false,
                            });
                            $('#bi_table_wrapper').addClass('table-responsive');
                        }
                        
                    }, 1000);

                    function initBtn(){
                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
                        var arrValid = {}
                        if(arrCheck.length > 0){
                            jQuery.each(arrCheck, function(i, b){
                                var povalue = $(b).attr('povalue');
                                var id = $(b).attr('id');
                                if( $(b).is(':checked') ){
                                    arrValid[id] = povalue
                                }
                            })
                        }
                        if(Object.keys(arrValid).length === 0){
                            $('#btnCreateShoppingCart')
                            .removeClass('btn-warning')
                            .addClass('btn-default')
                            .off('click')
                        }
                        else{
                            $('#btnCreateShoppingCart')
                            .off('click')
                            .removeClass('btn-default')
                            .addClass('btn-warning')
                            .on('click', function(){
                                $.ajax({
                                    type: 'POST',
                                    url: '$btnToShoppingCart',
                                    data: { items: btoa(JSON.stringify(arrValid)) },
                                    success: function(data) {
                                        var arrData = JSON.parse(data)
                                        console.log(data);
                                        console.log(arrData.status);
                                        if(arrData.status==1){
                                            if(top.document.getElementById('shopping_cart')){
                                                top.$('#shopping_cart').load(arrData.url);
                                            };
                                        }
                                    },
                                    error: function(){
                                        swal('koneksi error');
                                        HoldOn.close()
                                    }
                                });
                            })
                        }
                    }
                    initBtn();
                    
                    function calcCheckAll(e){
                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
                        if(arrCheck.length > 0){
                            jQuery.each(arrCheck, function(i, b){
                                if( $(e).is(':checked') ){
                                    $(b).prop('checked', true)
                                    initBtn()
                                }
                                else{
                                    $(b).prop('checked', false)
                                    initBtn()
                                }
                            })
                        }
                    }
                    
                    function resetCalcCheckAll(){
                        $('.calcCheckAll').prop('checked', false);
                    }
                    
                \n</script>";

        /* ----------------------------------------------------------------------------------
         * kala mau melihat langsung tambahkan tpl=1 pada url
         * else digunakan kalau akan diload di pembelian 466
         * ----------------------------------------------------------------------------------
         */
        if (isset($_GET['tpl']) && $_GET['tpl'] == 1) {

            $p->addTags(array(
                // "prop_display"          => $propDisplay,
                "menu_right_isi"   => callMenuRightIsi(),
                "menu_left"        => callMenuLeft(),
                //                "trans_menu" => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                // "data_propose_title"    => $strDataProposeTitle,
                "add_link"         => "",
                "content_nav"      => "",
                "content"          => $str,

                "stop_time" => "",
            ));
            $p->render();
        }
        else {
            if (isset($jenisTr) && ($jenisTr == 466)) {

                echo $str;
            }
        }


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

        //         arrPrint($navigasiAttr);
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
        $content_note .= "<p class='meta no-margin' style='padding-left:15px;'>";
        $content_note .= "<span class='label bg-danger text-black'>&nbsp;##&nbsp;</span> : ";
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
            "menu_right_isi"   => callMenuRightIsi(),
            "menu_left"        => callMenuLeft(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content_nav"      => "",
            "content"          => $str,
            "free_content"     => "",
            "stop_time"        => "",
        ));
        //endregion

        $p->render();


        break;
    /*----------ctr: showStokLimit----------*/
    case "cekStokLimit":
        $pageTemplate = MODUL_TEMPLATE_PATH . "/template/bi.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");
        $vendorID = (isset($vendorId) && ($vendorId != NULL)) ? $vendorId : "0";

        $str = "";

        $heads_1 = array(
            // "no",
            "id"     => array(
                "label" => "pid",
                // "attr_head" => "rowspan='2'",
            ),
            "nama"   => array(
                "label" => "nama produk",
                // "attr_head" => "rowspan='2'",
            ),
            "satuan" => array(
                "label" => "satuan dasar",
                // "attr_head" => "rowspan='2'",
            ),
        );
        // arrPrint($cabangs);
        $bg_warnas = array(
            "-1"  => "bg-warning",
            "100" => "bg-info",
            "101" => "bg-success",
        );

        $head_cbs_2 = array();
        $head_cbs_3 = array();
        foreach ($cabangs as $cb_id => $cabang) {
            $cabang_nama = $cabang['nama'];
            $cabang_id = $cabang['id'];
            $bg_warna = $bg_warnas[$cabang_id];

            if ($cabang_id > 0) {
                $cb_speks["label"] = "omset <r>$omset_hari hari</r><br>$cabang_nama";
                $cb_speks["attr"] = "class='text-right $bg_warna'";
                $cb_speks["attr_head"] = "";
                $cb_speks["format_key"] = "stok";
                $cb_speks["format"] = "formatField_he_format";
                $head_cbs["omset_$cb_id"] = $cb_speks;

                $cb_speks["label"] = "rata2 harian<br>$cabang_nama";
                $cb_speks["format_key"] = "avg";
                $cb_speks["format"] = "formatField_he_format";
                $cb_speks["attr_head"] = "";
                $cb_speks["attr"] = "class='text-right $bg_warna'";
                $head_cbs["avg_$cb_id"] = $cb_speks;

            }
            // $head_cbs["rata2_$cb_id"] = $cb_speks;

            $cb_speks_2["label"] = "stok aktif<br>$cabang_nama";
            $cb_speks_2["attr"] = "class='text-right $bg_warna'";
            $cb_speks_2["format_key"] = "stok";
            $cb_speks_2["format"] = "formatField_he_format";
            $head_cbs["qty_debet_sum_$cb_id"] = $cb_speks_2;

            if ($cabang_id > 0) {
                $cb_speks_2["label"] = "umur <br>stok<br>$cabang_nama";
                $cb_speks_2["attr"] = "class='text-right $bg_warna  text-orange'";
                $cb_speks_2["format"] = "formatField_he_format";
                $head_cbs["umur_stok_$cb_id"] = $cb_speks_2;

                $cb_speks["label"] = "stok budget<br>$cabang_nama";
                $cb_speks["attr"] = "class='text-right $bg_warna'";
                $cb_speks["attr_head"] = "";
                $head_cbs["qty_ideal_$cb_id"] = $cb_speks;

                $cb_speks_3["label"] = "rekom. order<br>$cabang_nama";
                // $cb_speks_3["attr_head"] = "class='text-red'";
                $cb_speks_3["attr_head"] = "style='color:red;'";
                $cb_speks_3["attr"] = "class='text-right $bg_warna'";
                $cb_speks_3["format_key"] = "stok";
                $cb_speks_3["format"] = "formatField_he_format";
                $head_cbs["qty_order_$cb_id"] = $cb_speks_3;
            }

        }
        $heads_2 = array(
            "omset" => array(
                "label" => "omset holding<br> <r>$omset_hari hari</r>",
                "attr"  => "class='bg-danger text-right'",
                "format_key" => "stok",
                "format"     => "formatField_he_format",
            ),
            "avg"   => array(
                "label"  => "rata2<br>harian holding",
                // "attr_head" => "colspan='2'",
                // "format_key" => "stok",
                "format" => "formatField_he_format",
                "attr"   => "class='bg-danger text-right'",
            ),
        );
        // foreach ($cabangs as $cb_id => $cabang) {
        //     $cb_speks["label"] = "omset<br>" . $cabang['nama'];
        //     $head_cbs["omset_$cb_id"] = $cb_speks;
        // }
        $heads_3 = array(
            "qty_debet_sum" => array(
                "label" => "stok aktif holding",
                "attr"  => "class='bg-danger text-right'",
                "format_key" => "stok",
                "format"     => "formatField_he_format",
            ),
            "qty_ideal"     => array(
                // "label" => "stok budget holding<br><input value='$bufer_hari' style='width: 30px;text-align: center;'> hari",
                "label" => "stok budget holding<br> <r>$bufer_hari hari</r>",
                "attr"  => "class='bg-danger text-right'",
                "format_key" => "stok",
                "format"     => "formatField_he_format",
            ),
        );
        $heads_4 = array(
            "qty_order"      => array(
                "label"      => "recom. order <br>dc<br>holding",
                "attr_head"  => "style='color:red;'",
                "attr"       => "class='bg-warning text-right'",
                "format_key" => "stok",
                "format"     => "formatField_he_format",
            ),
            "umur_stok"      => array(
                "label" => "umur stok (hari)",
                "attr"  => "class='bg-danger text-right'",
            ),
            "tgl_stok_habis" => array(
                "label" => "tgl perkiraan habis",
                "attr"  => "class='bg-danger text-right'",
            ),
        );
        $heads = array_merge($heads_1, $head_cbs, $heads_2, $head_cbs_2, $heads_3, $head_cbs_3, $heads_4);

        $head_sub_penjualan = array(
            "omset",
            "avg",
        );

        /* ----------------------------------------------------
         * thead
         * ----------------------------------------------------*/
        $tblHeads = "<thead>";
        $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
        $tblHeads .= "<th class='text-center'>No</th>";
        foreach ($heads as $key => $params) {
            $label = isset($params["label"]) ? $params["label"] : $key;
            $attr = isset($params["attr_head"]) ? $params["attr_head"] : "";

            $tblHeads .= "<th class='text-center' $attr title='$key'>$label</th>";

        }

         $cekAll = "<input type='checkbox' class='calcCheckAll' onclick='calcCheckAll(this)'>";
         $tblHeads .= "<th class='text-center' style='vertical-align: middle;'>$cekAll</th>";
         $tblHeads .= "<th class='text-center' style='vertical-align: middle;'>NAMA PRODUK</th>";

        $tblHeads .= "</tr>";

        /*
         * sub head
         */
        // $tblHeads .= "<tr class='bg-grey-2 text-uppercase'>";
        // foreach ($head_sub_penjualan as $item_head) {
        //     $tblHeads .= "<th class='text-center' style='vertical-align: middle;'>$item_head</th>";
        // }
        // $tblHeads .= "</tr>";

        $tblHeads .= "</thead>";

        /* ----------------------------------------------------
         * tbody
         * ----------------------------------------------------*/
        $arrProdukID_order = array();
        $tblBodies = "";
        $tblBodies .= "<tbody>";
        $no = 0;
        if (isset($master_datas) && (sizeof($master_datas) > 0)) {
            foreach ($master_datas as $key => $val) {
                $no++;

                $id = $val->id;
                $produk_nama = $val->nama;
                $limit_db = $val->limit;
                $limit_time = $val->limit_time;
                $lead_time = $val->lead_time;
                $indeks_db = $val->indeks;
                $moq = $val->moq;
                $moq_time = $val->moq_time;
                $kode = $val->kode;
                $satuan = $val->satuan;
                $qty_order = $val->qty_order;


                $link_ceklist = MODUL_PATH . "Bi/checklistBi/$vendorID/?mode=item&pid=$id";
//arrPrintPink($val);

                $tblBodies .= "<tr class='' style='' id='row_$id'>";
                $tblBodies .= "<td style='white-space: nowrap;' class='text-right'>$no</td>";
                foreach ($heads as $key => $params) {
//                     arrPrint($params);
                    $nilai_1 = isset($val->$key) ? $val->$key : "";
                    $format_key = isset($params['format_key']) ? $params['format_key'] : $key;
                    $nilai_f = isset($params['format']) ? $params['format']($format_key, $nilai_1) : $nilai_1;
                    $attr = isset($params['attr']) ? $params['attr'] : "";

                    $tblBodies .= "<td $attr title='$key' data-order='$nilai_1' title='$format_key'>$nilai_f</td>";
                }

                // foreach ($head_sub_penjualan as $item_head) {
                //
                //     $nilai_2 = $qtyis[$id][$item_head];
                //     $tblBodies .= "<td class='' title='$item_head'>$nilai_2</td>";
                // }
                //
                // foreach ($heads_3 as $item => $params_head_3) {
                //     $nilai_3 = $stokNow[$id][$item];
                //     $tblBodies .= "<td class='' title='$item'>$nilai_3</td>";
                // }
                if($qty_order>0){
                    $newPoMoq = $qty_order;
                }
                else{
                    $newPoMoq = 0;
                }
                $ceklist = "<input type='checkbox' povalue='$newPoMoq' id='$id' name='cl_stk[]' class='' onclick='initBtn();resetCalcCheckAll()' onclicks=\"document.getElementById('result').src='$link_ceklist'\">";
                $tblBodies .= "<td class='text-center bg-success'>$ceklist</td>";
                $tblBodies .= "<td class='text-left'>$produk_nama</td>";

                $arrProdukID_order[$id] = $newPoMoq;
            }
        }
        $tblBodies .= "</tbody>";

        /* ----------------------------------------------------
         * tfoot
         * ----------------------------------------------------*/
        $tblfoots = "";
        $tblfoots .= "<tfoot>";
        $tblfoots .= "</tfoot>";

        /*
         *
         * */
        $ki = 0;
        $kolom_ready = "";
        foreach ($heads as $head) {
            $ki++;
            $koLabel = str_replace("<br>", " ", $head['label']);

            $kvar = "<label class='text-uppercase'><input type='checkbox' id='ch_$ki' value='$ki' onclick=\"handleCheckboxChange(this.value, this.id)\"> $koLabel</label> ";
            $kolom_ready .= $kvar;

        }

        $supplier_ready = "";
        if(isset($arrSupplierData)){
            foreach ($arrSupplierData as $spID => $spNama){
                $selected = "";
                if($vendorId == $spID){
                    $selected = "checked";
                }
                $str_sp = "<label class='text-uppercase'><input type='radio' name='supplier' $selected id='ch_$spID' value='$spID' onclick=\"pilihSupplier($spID)\"> $spNama</label> ";
                $supplier_ready .= $str_sp;
            }
        }


        if (isset($btnToShoppingCart) && ($btnToShoppingCart != NULL)) {
            if (isset($arrProdukID_order) && (sizeof($arrProdukID_order) > 0)) {
                $arrProdukID_order_blob = blobEncode($arrProdukID_order);

                $link = "document.getElementById('result').src='$btnToShoppingCart?items=$arrProdukID_order_blob'";
                $btn = "<div cclass='pull-right' style='text-align: right;margin-top: 25px;'>";
                $btn .= "<button id='btnCreateShoppingCart' class='btn btn-warning'>";
                $btn .= "<span>Masukkan ke Shopping Cart</span>";
                $btn .= "</button>";
                $btn .= "</div>";
            }
            $btnToShoppingCart = $btnToShoppingCart . "?supplier=$vendorId";
        }

        $tbl = "";
        $tbl .= "<div id='supplier-hidden' style='display: none;' class='body bg-info kolom-hidden'>$supplier_ready</div>";
        $tbl .= "<div id='kolom-hidden' style='display: none;' class='body bg-info kolom-hidden'>$kolom_ready</div>";
        $tbl .= "<style type='text/css'>
                    .kolom-hidden{
                        margin-bottom: 10px;
                        padding: 5px 10px;
                        border-radius: 5px;
                    }
                    #stok_limit_filter .form-control{
                        height: 30px !important;
                    }
                    #stok_limit_length .form-control{
                        width: 50px;
                    }
                    .dataTables_length{                        
                        margin-right: 10px;;
                    }
                    .toolbar, .pilih_kolom{
                        float: left;
                        margin-right: 10px;
                    }
                    .form-control-buffer{
                        padding: 5px 2px;
                        height: 30px;
                        width: 40px;
                        font-size: 14px;
                        text-align: center;
                        border: 1px solid #ccc;
                        border-radius: 2px;
                    }
                    a.dt-button{
                        font-size: 0.89em;   
                    }
                </style>";
        $tbl_id = "stok_limit";
        $tbl .= "<div class='table-responsive' style='font-sizee: 0.95em;'>";
        // $tbl .= "Toggle column: <a href='#' class=\"toggle-vis\" data-column=\"0\">NO</a> - <a class=\"toggle-vis\" data-column=\"1\">PID</a> - <a class=\"toggle-vis\" data-column=\"2\">Office</a> - <a class=\"toggle-vis\" data-column=\"3\">Age</a> - <a class=\"toggle-vis\" data-column=\"4\">Start date</a> - <a class=\"toggle-vis\" data-column=\"5\">Salary</a>";
        $tbl .= "<table class='table table-hover-color-red compact table-condensed table-striped table-bordered' id='$tbl_id'>";
        $tbl .= $tblHeads;
        $tbl .= $tblBodies;
        $tbl .= "</table>";
        $tbl .= "</div>";
        $tbl .= isset($btn) ? $btn : "";
        $tbl .= "<div id='wadah'></div>";

        // $str .= $tbl;
        $btn_colaps = "";
        $btn_colaps .= "<button type='button' id='supplier' class='btn btn-sm btn-box-tool text-uppercase text-red' value='1' ><i class='fa fa-truck'></i> pilih supplier</button>";
        $btn_colaps .= "<button type='button' class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeadingCss("text-uppercase text-red box-solid");
        $p->setLayoutBoxHeading($title, $btn_colaps);
        $p->setLayoutBoxCss("box-danger");
        // $p->setLayoutBoxAttribut();
        $p->setLayoutBoxBody(true);
        $str .= $p->layout_box($tbl);

        $link_pilih_supplier = base_url() . "addons/Bi/cekStokLimit/";
        $str .= "<script>

                    function pilihSupplier(a){
                        $('#wadah_stok_limit').load('$link_pilih_supplier?sp='+a);
                    };


                    function handleCheckboxChange(z,t) {                        
                        console.log(z);
                        console.log(t);
                    
                        if($('#'+t).is(':checked')) {
                            $('table').DataTable().columns(z).visible(false); // hidden     
                            $('table').draw();
                            // alert('cek');
                         } 
                         else {
                             // alert('nocek')
                            $('table').DataTable().columns(z).visible(true); // hidden
                            $('table').draw();
                        }
                    
                        // $('#'+t).change(function() {
                        //   handleCheckboxChange(); // Call the function when the checkbox changes
                        // });                           
                    };
                    
                    $('#supplier').on('click', function(e) { 
                                $('#supplier').val(function(i, text) {
                                console.log(text);
                                    if(text === '1'){
                                        $('#supplier-hidden').show();
                                    }
                                    else{
                                        $('#supplier-hidden').hide();
                                    }
                                    
                                    return text === '1' ? '0' : '1';                                    
                                });
                            });
                    
            </script>";
        $this_domain = base_url() . "addons/Bi/doSaveUxParams";
        $str .= "\n\n<script>

                    setTimeout( function(){
                        if($('#stok_limit thead th').length>1){
                           var datareview$tbl_id = $('#$tbl_id').DataTable({
                                initComplete: function () {
                                    // Apply the search
                                    this.api().columns().every( function () {
                                        // var that = this;
                                        //
                                        // $( 'input', this.header() ).on( 'keyup change clear', function () {
                                        //     if ( that.search() !== this.value ) {
                                        //         that
                                        //             .search( this.value )
                                        //             .draw();
                                        //     }
                                        // });
                                        
                                        $('input', this.header()).on('click', function(e) {
                                            e.stopPropagation();
                                        });                                                                                        
                                                                                    
                                    });                                                                                                                                       
                                },
                                // dom: '<\"toolbar\"> Blfrtip ',
                                dom: '<\"toolbar\"> Bl <\"pilih_kolom\"> frtp',
                                order: [[ 0, 'asc' ]],
                                lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                                pageLength: 50,
                                paging: true,
                                info: true,
                                language: {                                    
                                    lengthMenu: 'Tampilkan _MENU_ per halaman',
                                },
                                fixedHeader: true,
                                stateSave: true,
                                buttons: [
                                    {extend:'csv', footer: true},
                                    { extend: 'excelHtml5', footer: true },
                                    // {
                                    //     text: 'omset 7 hari',
                                    //     action: function ( e, dt, node, config ) {
                                    //         $('#wadah').load(encodeURI('$this_domain?v=7&n=periode&j=$title'));
                                    //
                                    //     }
                                    // },
                                    // {
                                    //     text: 'omset 14 hari',
                                    //     action: function ( e, dt, node, config ) {
                                    //          $('#wadah').load('$this_domain?v=14&n=periode');
                                    //         document.location.reload();
                                    //     }
                                    // },
                                    {
                                        text: 'omset <b>90</b> hari',
                                        action: function ( e, dt, node, config ) {
                                             $('#wadah').load(encodeURI('$this_domain?v=90&n=periode&j=$title'));
                                            // document.location.reload();
                                        }
                                    },   
                                 ]
                            });
                            
                            document.querySelector('div.toolbar').innerHTML = 'Buffer Stok <input type=number class=form-control-buffer id=buffer-hari value=$bufer_hari> hari';                            
                            
                            $('#buffer-hari').on('blur', function(e) {
                                var buffer_nilai = $('#buffer-hari').val(); 
                                // alert('wedew ' + buffer_nilai);
                                $('#wadah').load(encodeURI('$this_domain?v=' + buffer_nilai + '&n=buffer_nilai&j=$title'));
                                // table.reload();
                                datareview$tbl_id.draw();
                            });
                            
                            document.querySelector('div.pilih_kolom').innerHTML = '<button id=pilih_kolom class=dt-button value=1 >pilih kolom</button>';
                            $('#pilih_kolom').on('click', function(e) { 
                                $('#pilih_kolom').val(function(i, text) {
                                
                                    if(text === '1'){
                                        $('#kolom-hidden').show();
                                    }
                                    else{
                                        $('#kolom-hidden').hide();
                                    }
                                    
                                    return text === '1' ? '0' : '1';                                    
                                });
                            });
                            
                    // function handleCheckboxChange(z,t) {                        
                    //     console.log(z);
                    //     console.log(t);
                    //
                    //     if ($('#'+t).is(':checked')) {
                    //         $('table').DataTable().columns(z).visible(false); // hidden     
                    //      } 
                    //      else {
                    //         $('table').DataTable().columns(z).visible(true); // hidden
                    //     }
                    //
                    //     $('#'+t).change(function() {
                    //       handleCheckboxChange(); // Call the function when the checkbox changes
                    //     });                           
                    // };
                            
                            // $('#stok_limit').addClass('table-responsive');
                            
                            // document.querySelectorAll('a.toggle-vis').forEach((el) => {
                            //     el.addEventListener('click', function (e) {
                            //         e.preventDefault();
                            // 
                            //         let columnIdx = e.target.getAttribute('data-column');
                            //         let column = table.column(columnIdx);
                            // 
                            //         // Toggle the visibility
                            //         column.visible(!column.visible());
                            //     });
                            // });
                            
                             $('a.toggle-vis').on( 'click', function (e) {
                                e.preventDefault();
                            
                                // Get the column API object
                                var column = table.column( $('#stok_limit').attr('data-column') );
                            
                                // Toggle the visibility
                                column.visible( ! column.visible() );
                            } );
                            
                        }
                        // else{
                        //     $('#bi_table').DataTable({
                        //         lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'All']],
                        //         pageLength: -1,
                        //         paging: false,
                        //     });
                        //     $('#bi_table_wrapper').addClass('table-responsive');
                        // }
                        
                    }, 1000);

                    function initBtn(){
                        var arrCheck = $(\"input[name='cl_stk\[\]']\");
                        var arrValid = {}
                        if(arrCheck.length > 0){
                            jQuery.each(arrCheck, function(i, b){
                                var povalue = $(b).attr('povalue');
                                var id = $(b).attr('id');
                                if( $(b).is(':checked') ){
                                    arrValid[id] = povalue
                                }
                            })
                        }
                        if(Object.keys(arrValid).length === 0){
                            $('#btnCreateShoppingCart')
                            .removeClass('btn-warning')
                            .addClass('btn-default')
                            .off('click')
                        }
                        else{
                             $('#btnCreateShoppingCart')
                             .off('click')
                             .removeClass('btn-default')
                             .addClass('btn-warning')
                             .on('click', function(){
                                 $.ajax({
                                     type: 'POST',
                                     url: '$btnToShoppingCart',
                                     data: { items: btoa(JSON.stringify(arrValid)) },
                                     success: function(data) {
                                         var arrData = JSON.parse(data)
                                         console.log(data);
                                         console.log(arrData.status);
//                                         if(arrData.status==1){
//                                             if(top.document.getElementById('shopping_cart')){
//                                                 top.$('#shopping_cart').load(arrData.url);
//                                             };
//                                         }
                                         if(arrData.status_a==1){
                                            if(top.document.getElementById('result')){
                                                    top.$('#result').load(arrData.url_a);
                                                };
                                            }
                                            if(arrData.status_b==1){
                                                if(top.document.getElementById('result')){
                                                    top.$('#result').load(arrData.url_b);
                                                };
                                            }
                                            if(arrData.status_c==1){
                                                if(top.document.getElementById('shopping_cart')){
                                                    top.$('#shopping_cart').load(arrData.url_c);
                                                };
                                            }
                                     },
                                     error: function(){
                                         swal('koneksi error');
                                         HoldOn.close()
                                     }
                                 });
                             })
                        }
                    }
                    // initBtn();
                    
                    // function calcCheckAll(e){
                    //     var arrCheck = $(\"input[name='cl_stk\[\]']\");
                    //     if(arrCheck.length > 0){
                    //         jQuery.each(arrCheck, function(i, b){
                    //             if( $(e).is(':checked') ){
                    //                 $(b).prop('checked', true)
                    //                 initBtn()
                    //             }
                    //             else{
                    //                 $(b).prop('checked', false)
                    //                 initBtn()
                    //             }
                    //         })
                    //     }
                    // }
                    
                    function resetCalcCheckAll(){
                        $('.calcCheckAll').prop('checked', false);
                    }
                    
                \n</script>";

        /* ----------------------------------------------------------------------------------
         * kala mau melihat langsung tambahkan tpl=1 pada url
         * else digunakan kalau akan diload di pembelian 466
         * ----------------------------------------------------------------------------------
         */
        if (isset($_GET['tpl']) && $_GET['tpl'] == 1) {

            $p->addTags(array(
                // "prop_display"          => $propDisplay,
                "menu_right_isi"   => callMenuRightIsi(),
                "menu_left"        => callMenuLeft(),
                //                "trans_menu" => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                // "data_propose_title"    => $strDataProposeTitle,
                "add_link"         => "",
                "content_nav"      => "",
                "content"          => $str,

                "stop_time" => "",
            ));
            $p->render();
        }
        else {
            // if (isset($jenisTr) && ($jenisTr == 466)) {
            echo $str;
            // }
        }
        break;

    case "indek":
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/bi.html");

        /* --------------------------------------------------------------------
         * THEAD
         * --------------------------------------------------------------------*/
        // $strHead = "";
        // $strHead .= "<tr>";
        // $strHead .= "<td>no</td>";
        // foreach ($arrHeaders as $kolom => $arrHeader) {
        //     $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
        //     $strHead .= "<th>$hLabel</th>";
        // }
        // $strHead .= "</tr>";
        //
        // /* --------------------------------------------------------------------
        //  * TBODY
        //  * --------------------------------------------------------------------*/
        // $strBody = "";
        // $no = 0;
        // $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        // $jenistr = isset($jenisTr) ? $jenisTr : "580";
        // // matiHere($jenistr);
        // foreach ($master_data as $master_datum) {
        //     $no++;
        //     $strBody .= "<tr >";
        //     $strBody .= "<td>$no</td>";
        //     foreach ($arrHeaders as $kolom => $attrs) {
        //         // $nilai = $master_datum[$kolom];
        //         // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
        //         $nilai = isset($master_datum[$kolom]) ? (is_numeric($master_datum[$kolom]) ? $master_datum[$kolom] * 1 : $master_datum[$kolom]) : "";
        //
        //         $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
        //         $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
        //         $nilai_f = isset($attrs['format']) ? ($nilai > 0 ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
        //
        //         if (isset($attrs['links'])) {
        //             // matiHere();
        //             $modal_size = isset($attrs['links']['modal_size']) ? $attrs['links']['modal_size'] : "";
        //             $title_head_key = isset($attrs['links']['title_head_key']) ? $attrs['links']['title_head_key'] : "";
        //             $title_head = isset($attrs['links']['title_head_key']) ? (isset($master_datum[$title_head_key]) ? $master_datum[$title_head_key] : 'none') : $nilai;
        //             $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
        //             $strTitle_head = urlencode(trim("$link_title $title_head"));
        //             // cekHere("$strTitle_head");
        //             $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
        //             $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
        //             $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&modalSize=$modal_size" : "";
        //             $linkDetile = base_url() . $linking . "";
        //             $linkModal = modalDialogBtn("$strTitle_head", $linkDetile);
        //             $nilai_link = isset($attrs['links']['target']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
        //         }
        //         else {
        //             // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
        //             // $linkDetile = base_url() . $linking . "";
        //             // $linkModal = modalDialogBtn("$nilai", $linkDetile);
        //             $nilai_link = $nilai_f;
        //         }
        //
        //         // $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
        //         // $linkDetile = base_url() . $linking . "";
        //         // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
        //         // $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;
        //
        //         $strBody .= "<td $attr>$nilai_link</td>";
        //
        //         if (isset($attrs['summary'])) {
        //             if (!isset($totals[$kolom])) {
        //                 $totals[$kolom] = 0;
        //             }
        //             $totals[$kolom] += $nilai;
        //         }
        //     }
        //     $strBody .= "</tr>";
        //
        // }
        //
        // /* --------------------------------------------------------------------
        //  * TFOOD
        //  * --------------------------------------------------------------------*/
        // $strFoot = "";
        // $strFoot .= "<tr class='bg-danger'>";
        // $strFoot .= "<th></th>";
        // foreach ($arrHeaders as $kolom => $attrs) {
        //     $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
        //     $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
        //     // $label = $attrs['label'];
        //     // $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
        //     $attr = isset($attrs['attr_footer']) ? $attrs['attr_footer'] : (isset($attrs['attr']) ? $attrs['attr'] : "");
        //
        //     $strFoot .= "<th $attr>";
        //     $strFoot .= $fNilai_f;
        //     $strFoot .= "</th>";
        // }
        //
        // $tbl_id = "data_ok";
        // $strTbl = "";
        //
        // $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        // $strTbl .= "<table class='table table-condensade table-striped' id='$tbl_id'>";
        // $strTbl .= "<thead class='text-uppercase'>";
        // $strTbl .= $strHead;
        // $strTbl .= "</thead>";
        // $strTbl .= "<tbody>";
        // $strTbl .= $strBody;
        // $strTbl .= "</tbody>";
        // $strTbl .= "<tfoot>";
        // $strTbl .= $strFoot;
        // $strTbl .= "</tfoot>";
        // $strTbl .= "</table>";
        // $strTbl .= "</div>";


        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCABANG
         * ---------------------------------------------------------------------------------------------*/
        $loader_indikator = "<div class='loader-5 center'><span></span></div>";
        $content = "";
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu'>Mempersiapkan Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
            $str .= "<script>$('#sum_satu').load('$sum_satu');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSALESMAN
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua'></div>";
            $str .= "<script>$('#sum_dua').load('$sum_dua');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCUSTOMER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_tiga)) {
            $str = "";
            $str .= "<div id='sum_tiga'></div>";
            $str .= "<script>$('#sum_tiga').load('$sum_tiga');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERSUPPLIER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_empat)) {
            $str = "";
            $str .= "<div id='sum_empat'></div>";
            $str .= "<script>$('#sum_empat').load('$sum_empat');</script>";

            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser LIMA
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_lima)) {
            $str = "";
            $str .= "<div id='sum_lima'></div>";
            $str .= "<script>$('#sum_lima').load('$sum_lima');</script>";
            $content .= $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser ROW DATA perproduk transaksi
         * ---------------------------------------------------------------------------------------------*/
        // $p->setLayoutBoxCss("box-info");
        // $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        // $p->setLayoutBoxHeading("$subTitle", $btn_colaps);
        // $p->setLayoutBoxBody(true);
        // $content .= $p->layout_box($strTbl);
        if (isset($sum_null)) {
            // mati_disini();
            $str = "";
            $str .= "<div id='sum_null'></div>";
            $str .= "<script>$('#sum_null').load('$sum_null');</script>";

            $content .= $str;
        }
        if (isset($wadah_stok_limit)) {
            // mati_disini();
            $str = "";
            $str .= "<div id='wadah_stok_limit'>Mempersiapkan Data...  <div style='margin-left:50%;'>$loader_indikator</div></div>";
            $str .= "<script>$('#wadah_stok_limit').load('$wadah_stok_limit');</script>";

            $content .= $str;
        }

        //region loader popup syncro data
        // $loaders = "";
        if (isset($loader)) {
            foreach ($loader as $div_id => $item_to_load) {
                $content .= "<div id='$div_id'></div>";

                // $loaders .= "\n $('#$div_id').load('$item_to_load')";
                $loaders .= "\n createPop('$item_to_load', '$div_id')";
            }

            $content .= "<script>$loaders</script>";
        }
        //endregion

        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "trans_menu"       => callTransMenu(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "add_td"           => isset($add_td) ? $add_td : "",
                "content"          => $content,
                // navigasi
                // "url"              => $url,
                // "date1"            => $date1,
                // "date2"            => $date2,
                // "date_min"         => $date_min,
                // "date_max"         => $date_max,
                "content_nav"      => "",
            )
        );
        $p->render();
        break;
    // -------------------------------------------end-

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
                    if ($key == "kode") {
                        $numValue = $pidData[$key];
                        $numValue_f = $pidData[$key];
                    }
                    else {
                        $numValue = str_replace(",", "", $pidData[$key]);
                        $numValue_f = formatField_he_format($key, $numValue);
                    }

                    $tblBody .= "<td class='text-right' key='$key' data-order='$numValue'>$numValue_f</td>";
                }
                else if (!is_numeric($newValue_f)) {
                    $tblBody .= "<td key='$key' class='text-left' data-order='$pidData[$key]'>$pidData[$key]</td>";
                }
                else {

                    $tblBody .= "<td key='$key' class='text-left text-bold' data-order='$pidData[$key]'>$pidData[$key]</td>";
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


        $strDataPropose .= "<div style='padding-top: 12px;padding-bottom: 50px;'>";
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
                            order: [[ 13, 'desc' ]],
                            paging: false,
                            cache: false,
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