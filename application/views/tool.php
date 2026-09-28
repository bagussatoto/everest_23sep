<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "index":
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

        //        $list_data .= "<div class='panel'>";
        //        $list_data .= "<div class='input-group'>";
        //
        //        $list_data .= "<span class='input-group-btn'>";
        //        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        //        $list_data .= "</span>";
        //
        //        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
        //
        //        $list_data .= "<span class='input-group-btn'>";
        //
        //        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        //        $list_data .= "</span class='input-group-addon'>";
        //
        //        $list_data .= "</div class='input-group'>";
        //
        //        $list_data .= "</div class='panel panel-default'>";

        if (isset($button) && sizeof($button) > 0) {
            $list_data .= "<div class='panel'>";
            $list_data .= "<div class='input-group'>";
            foreach ($button as $bKey => $bVal) {
                $list_data .= $bVal;
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $data_total = "";

        if (isset($warning) && sizeof($warning) > 0) {
            $data_total .= "<div class='panel' style='background-color:yellow'>";
            foreach ($warning as $wSpec) {
                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
            }
            $data_total .= "</div>";
        }


        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";

            $data_total .= "<div>";
            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
            $data_total .= "</div>";

            $data_total .= "<div class='table-responsive'>";
            $data_total .= "<table width='100%' class='table table-bordered datatables'>";

            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            $data_total .= "<td align='center'>---</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                $data_total .= "<td align='right'>$add_name</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                        if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                            $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                        }
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {
                        $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                        $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tr bgcolor='#e5e5e5'>";

            $data_total .= "<td>&nbsp;</td>";
            $data_total .= "<td>&nbsp;</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

        }
        //        else {
        //
        //            $list_data .= "<div class='panel panel-default'>";
        //            $list_data .= "<div class='panel-body'>";
        //            $list_data .= "there is no item name matched your criteria<br>";
        //            $list_data .= "you mant want to go back or select other keyword<br>";
        //
        //            $list_data .= "</div>";
        //            $list_data .= "</div>";
        //
        //        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->setContent($contens);
        $p->render();
        break;

    case "stock":
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

        if (!isset($search) || ((isset($search)) && ($search == true))) {
            $list_data .= "<div class='panel'>";
            $list_data .= "<div class='input-group'>";

            $list_data .= "<span class='input-group-btn'>";
            $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
            $list_data .= "</span>";

            $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";

            $list_data .= "<span class='input-group-btn'>";

            $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
            $list_data .= "</span class='input-group-addon'>";

            $list_data .= "</div class='input-group'>";

            $list_data .= "</div class='panel panel-default'>";
        }


        if (isset($button) && sizeof($button) > 0) {
            $list_data .= "<div class='panel'>";
            $list_data .= "<div class='input-group'>";
            foreach ($button as $bKey => $bVal) {
                $list_data .= $bVal;
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }


        $data_total = "";
        $data_total_geseh = "";
        $data_total_2 = "";
        $data_total_3 = "";

        if (isset($warning) && sizeof($warning) > 0) {
            $data_total .= "<div class='panel' style='background-color:yellow'>";
            foreach ($warning as $wSpec) {
                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
            }
            $data_total .= "</div>";
        }

        // stok yang geseh tampil disini
        if (isset($itemsGeseh) && sizeof($itemsGeseh) > 0) {
            $i = 0;
            $data_total_geseh .= "<div class='panel'>";

            $data_total_geseh .= "<div>";
            $data_total_geseh .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
            $data_total_geseh .= "</div>";

            $data_total_geseh .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total_geseh .= "<tr bgcolor='#e5e5e5'>";
            $data_total_geseh .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total_geseh .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total_geseh .= "$cValue &nbsp;";
                $data_total_geseh .= "</th>";
            }
            $data_total_geseh .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($itemsGeseh as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total_geseh .= "<tr style='background-color:$background_color;'>";
                $data_total_geseh .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                        $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total_geseh .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total_geseh .= "<td style='background-color:$bg_color_c;'>" . formatField($headerKey, $cValue) . "</td>";
                    }

                }
                $data_total_geseh .= "</tr>";
            }

            $data_total_geseh .= "<tr bgcolor='#e5e5e5'>";

            $data_total_geseh .= "<td>&nbsp;";
            $data_total_geseh .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total_geseh .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total_geseh .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total_geseh .= "</tr>";
            $data_total_geseh .= "</table>";
            $data_total_geseh .= "</div>";
            $data_total_geseh .= "</div>";

            $data_total_geseh .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total_geseh;
            $list_data .= "<br><br>";

        }
        // items 2
        if (isset($items2) && sizeof($items2) > 0) {
            $i = 0;
            $data_total_2 .= "<div class='panel'>";
            if (isset($items2_label)) {

                $data_total_2 .= "<div>";
                $data_total_2 .= "<h4>$items2_label</h4>";
                $data_total_2 .= "</div>";
            }

            $data_total_2 .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total_2 .= "<tr bgcolor='#e5e5e5'>";
            $data_total_2 .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total_2 .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total_2 .= "$cValue &nbsp;";
                $data_total_2 .= "</th>";
            }
            $data_total_2 .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items2 as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total_2 .= "<tr style='background-color:$background_color;'>";
                $data_total_2 .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                        $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total_2 .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total_2 .= "<td style='background-color:$bg_color_c;'>" . formatField($headerKey, $cValue) . "</td>";
                    }

                }
                $data_total_2 .= "</tr>";
            }

            $data_total_2 .= "<tr bgcolor='#e5e5e5'>";

            $data_total_2 .= "<td>&nbsp;";
            $data_total_2 .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total_2 .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total_2 .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total_2 .= "</tr>";
            $data_total_2 .= "</table>";
            $data_total_2 .= "</div>";
            $data_total_2 .= "</div>";

            $data_total_2 .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total_2;
            $list_data .= "<br><br>";

        }
        // items 3
        if (isset($items3) && sizeof($items3) > 0) {
            $i = 0;
            $data_total_3 .= "<div class='panel'>";
            if (isset($items3_label)) {

                $data_total_3 .= "<div>";
                $data_total_3 .= "<h4>$items3_label</h4>";
                $data_total_3 .= "</div>";
            }

            $data_total_3 .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total_3 .= "<tr bgcolor='#e5e5e5'>";
            $data_total_3 .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total_3 .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total_3 .= "$cValue &nbsp;";
                $data_total_3 .= "</th>";
            }
            $data_total_3 .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items3 as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total_3 .= "<tr style='background-color:$background_color;'>";
                $data_total_3 .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                        $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total_3 .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total_3 .= "<td style='background-color:$bg_color_c;'>" . formatField($headerKey, $cValue) . "</td>";
                    }

                }
                $data_total_3 .= "</tr>";
            }

            $data_total_3 .= "<tr bgcolor='#e5e5e5'>";

            $data_total_3 .= "<td>&nbsp;";
            $data_total_3 .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total_3 .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total_3 .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total_3 .= "</tr>";
            $data_total_3 .= "</table>";
            $data_total_3 .= "</div>";
            $data_total_3 .= "</div>";

            $data_total_3 .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total_3;
            $list_data .= "<br><br>";

        }

        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";

            $data_total .= "<div>";
            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
            $data_total .= "</div>";

            $data_total .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($cData['pID'])) {

                        if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                            $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                        }
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField($headerKey, $cValue) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tr bgcolor='#e5e5e5'>";

            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }


        echo $list_data;
//        $p->addTags(
//            array(
//                "menu_left" => callMenuLeft(),
//                "float_menu_atas" => callFloatMenu('atas'),
//                "float_menu_bawah" => callFloatMenu(),
//                "menu_taskbar" => callMenuTaskbar(),
//                "btn_back" => callBackNav(),
//                "content" => $list_data,
//                "profile_name" => $this->session->login['nama'],
//            )
//        );
//
//        $p->setContent($contens);
//        $p->render();


        break;

    case "indexDate":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/toolModif.html");

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


        $data_total = "";

        if (isset($warning) && sizeof($warning) > 0) {
            $data_total .= "<div class='panel' style='background-color:yellow'>";
            foreach ($warning as $wSpec) {
                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
            }
            $data_total .= "</div>";
        }


        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";

            $data_total .= "<div>";
            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
            $data_total .= "</div>";

            $data_total .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;
                $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                $jenis_master = isset($cData['jenis_master']) ? $cData['jenis_master'] : NULL;
                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($cData['pID'])) {
                        if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                            $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                        }
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenis_master, $modul_path) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tr bgcolor='#e5e5e5'>";

            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],

                "url" => $thisPage,
                "date1" => $filters['date1'],
                "date2" => $filters['date2'],
                "date_min" => "2020-01-01",
                "date_max" => $filters['dates'],
            )
        );

        $p->setContent($contens);
        $p->render();

    case "viewMenu":
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/tool.html");
        $list_data = "";

        $jenis_group_f = $jenis_group == "data" ? "transaksi" : "data";
        $methodenya = "view" . ucwords($jenis_group_f) . "_ui";
        $gotoClick = base_url() . "Tool/$methodenya";
        $addLink = base_url() . "Data/add/MenuGroup";
        $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'New $title',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );";
        $btn_top = "<button href='javascript:void(0)' class=\" btn btn-xs btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title'><span class='glyphicon glyphicon-plus'></span> new group menu</button>";
        $btn_top .= "<button href='javascript:void(0)' class=\" btn btn-xs btn-warning\" onClick=\"location.href='$gotoClick'\" data-toggle='tooltip' data-placement='top' title='go to'><span class='glyphicon glyphicon-send'></span> go to $jenis_group_f</button>";

        $xx = 0;
        $datas = "";
        foreach ($confSources as $jenis => $confSource) {
            $xx++;
            $label = $confSource['label'];
            $groupJenies = isset($jeniseGroup[$jenis]) ? $jeniseGroup[$jenis] : array();
            $hasil = "";
            foreach ($groupJenies as $groupJeny) {
                $var = $groupJeny;
                if ($hasil == "") {
                    $hasil .= "<i style='color: red;'>$var</i>";
                }
                else {
                    $hasil = "<b>$hasil</b>" . "-<i style='color: deepskyblue;'>$var</i>";
                }
            }
            $strGjeny = $hasil;
            $label_e = str_replace("=", "", base64_encode($label));
            $groupJ = isset($jeniseGroup[$jenis]) ? $strGjeny : "none";
            $linkMenuG = $linkEditor . "/$jenis/$label_e/$jenis_group";
            $groupJ_l = "<a href='$linkMenuG' data-toggle='modal' data-target='#myModal'>$groupJ</a>";

            $datas .= "<tr>";
            $datas .= "<td>$xx</td>";
            $datas .= "<td>$jenis</td>";
            $datas .= "<td>$label</td>";
            $datas .= "<td>$groupJ_l</td>";
            $datas .= "</tr>";
            // echo "<b>$jenis</b>-$label$groupJ<br>";
            // echo "<div class='col-md-3 border-cek'>**</div>";
        }

        $content = "";
        $content .= "<table class='table table-hover-color-red table-bordered table-condensed' id='contoh'>";
        $content .= "<thead>";
        $content .= "<tr>";
        $content .= "<th>no</th>";
        $content .= "<th>kode/key</th>";
        $content .= "<th>menu</th>";
        $content .= "<th>group</th>";
        $content .= "</tr>";
        $content .= "</thead>";

        $content .= "<tbody>";
        $content .= $datas;
        $content .= "</tbody>";

        $content .= "</table>";

        $script_bottom = "<script>

            $(document).ready( function(){

                     $('#contoh').DataTable({
                                stateSave: true,
                                // order: [[ 11, 'desc' ]],
                                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                pageLength: -1,                                   
                            });
                });
 
             </script>";

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "btn_top" => $btn_top,
                "stop_time" => "",
                "content" => $content,
                "script_bottom" => $script_bottom,
                "profile_name" => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;

    case "indexLap":
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

//        $list_data .= "<div class='panel'>";
//        $list_data .= "<div class='input-group'>";
//
//        $list_data .= "<span class='input-group-btn'>";
//        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
//        $list_data .= "</span>";
//
//        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
//
//        $list_data .= "<span class='input-group-btn'>";
//
//        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
//        $list_data .= "</span class='input-group-addon'>";
//
//        $list_data .= "</div class='input-group'>";
//
//        $list_data .= "</div class='panel panel-default'>";


        $data_total = "";

        if (isset($warning) && sizeof($warning) > 0) {
            $data_total .= "<div class='panel' style='background-color:yellow'>";
            foreach ($warning as $wSpec) {
                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
            }
            $data_total .= "</div>";
        }


        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";

            $data_total .= "<div>";
            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
            $data_total .= "</div>";

            $data_total .= "<table id='' width='100%' class='table table-bordered'>";

            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";

            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                        $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "2", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {

                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField($headerKey, $cValue) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tr bgcolor='#e5e5e5'>";

            $data_total .= "<td>&nbsp;";
            $data_total .= "</td>";

            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }

            $data_total .= "</tr>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

        }
//        else {
//
//            $list_data .= "<div class='panel panel-default'>";
//            $list_data .= "<div class='panel-body'>";
//            $list_data .= "there is no item name matched your criteria<br>";
//            $list_data .= "you mant want to go back or select other keyword<br>";
//
//            $list_data .= "</div>";
//            $list_data .= "</div>";
//
//        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->setContent($contens);
        $p->render();
        break;

    case "coa":
        // $this->load->model(array(
        //     'Mdls/MdlAccounts',
        //     // 'Web_settings'
        // ));
        //
        $p = New Layout("$title", "$subTitle", "application/template/tool.html");
        $var = "<link rel='stylesheet' href='" . base_url() . "assets/custom/style.min.css' />";

        $var .= "<div class='box box-info'>";
        $var .= "<div class='box-body'>";
        $var .= "<div class='row'>";
        $var .= "<div class='col-md-4'>";
        $var .= "<div id='jstree1'>";
        $var .= "<ul>";

        $visit = array();
        for ($i = 0; $i < count($userList); $i++) {
            $visit[$i] = false;
        }

        // $var_data = $p->dfs('COA', '0', $userList, $visit, 0);
        $var_data = $p->dfs_code('COA', '0', $userList, $visit, 0);
        $var .= $var_data;

        $var .= "</ul>";

        $var .= "</div>"; // jstree
        $var .= "</div>"; // col-md-4

        $var .= "<div class='col-md-8' style='position: fixed; border: 0px solid red;left: 46%;width: 750px;z-index:1000;background-color: #ffffff;padding:10px 0;' id='newform'></div>";

        // $var .= "</div>";
        $var .= "</div>"; // panel-body
        $var .= "</div>"; // panel
        $var .= "</div>"; // row

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "btn_top" => "",
                "stop_time" => "",
                "content" => $var,
                // "script_bottom"    => $script_bottom,
                "profile_name" => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;


    //-------------------
    case "cekMasterDetail":
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
        $data_geseh = "";

        if (isset($button) && sizeof($button) > 0) {
            $list_data .= "<div class='panel'>";
            $list_data .= "<div class='input-group'>";
            foreach ($button as $bKey => $bVal) {
                $list_data .= $bVal;
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        // region saldo
        if (isset($arrSaldo) && (sizeof($arrSaldo) > 0)) {
            $data_geseh .= "<div class='panel'>";

            $data_geseh .= "<div class='table-responsive'>";
            $data_geseh .= "<table width='100%' class='table table-bordered datatables'>";

            $data_geseh .= "<tr bgcolor='#e5e5e5'>";
            $data_geseh .= "<td align='right'>No.</td>";
            foreach ($headerFieldsSaldo as $cName => $cValue) {
                $data_geseh .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_geseh .= "$cValue &nbsp;";
                $data_geseh .= "</th>";
            }
            $data_geseh .= "</tr>";

            if (isset($arrSaldo) && (sizeof($arrSaldo) > 0)) {
                $total = array();
                $iCtr = 0;
                foreach ($arrSaldo as $cData) {
                    $iCtr++;

                    $add_name = "";
                    $background_color = "";

//                    if (isset($marking) && sizeof($marking) > 0) {
//                        $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
//                        $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
//                    }

                    $data_geseh .= "<tr style='background-color:$background_color;'>";
                    $data_geseh .= "<td align='right'>$iCtr.</td>";
//            $data_geseh .= "<td align='right'>$add_name</td>";
                    foreach ($headerFieldsSaldo as $headerKey => $headerLabel) {
                        $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                        $link = isset($cData['link']) ? $cData['link'] : "";

                        $bg_color_c = "";
                        if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                            if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                                $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                            }
                        }

                        if (is_numeric($cValue)) {
                            if ($headerKey != "pID") {
                                if (!isset($total[$headerKey])) {
                                    $total[$headerKey] = 0;
                                }
                                $total[$headerKey] += $cValue;

                                $align_class = "text-right";
                                $cValue_f = number_format($cValue, "0", ".", ",");
                            }
                            else {
                                $align_class = "text-center";
                                $cValue_f = $cValue;
                            }

                            $data_geseh .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                        }
                        else {
                            $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                            $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                            $data_geseh .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                        }

                    }
                    $data_geseh .= "</tr>";
                }
            }

//            $data_geseh .= "<tr bgcolor='#e5e5e5'>";
//            $data_geseh .= "<td>&nbsp;</td>";
////        $data_geseh .= "<td>&nbsp;</td>";
//            foreach ($headerFields as $cName => $cValue) {
//                if (isset($arrTotalBawahGeseh[$cName])) {
//                    $data_geseh .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($arrTotalBawahGeseh[$cName]) . "</td>";
//                }
//                else {
//                    $data_geseh .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
//                }
//            }
//            $data_geseh .= "</tr>";

            $data_geseh .= "</table>";
            $data_geseh .= "</div>";
            $data_geseh .= "</div>";
            $data_geseh .= "<br>";
        }
        // endregion saldo


        //region items geseh.....
        if (isset($itemsGeseh) && (sizeof($itemsGeseh) > 0)) {
            $data_geseh .= "<div class='panel'>";

            $data_geseh .= "<div class='table-responsive'>";
            $data_geseh .= "<table width='100%' class='table table-bordered datatables'>";

            $data_geseh .= "<tr bgcolor='#e5e5e5'>";
            $data_geseh .= "<td align='right'>No.</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_geseh .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_geseh .= "$cValue &nbsp;";
                $data_geseh .= "</th>";
            }
            $data_geseh .= "</tr>";

            if (isset($itemsGeseh) && (sizeof($itemsGeseh) > 0)) {
                $total = array();
                $iCtr = 0;
                foreach ($itemsGeseh as $cData) {
                    $iCtr++;

                    $add_name = "";
                    $background_color = "";

                    if (isset($marking) && sizeof($marking) > 0) {
                        $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                        $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                    }

                    $data_geseh .= "<tr style='background-color:$background_color;'>";
                    $data_geseh .= "<td align='right'>$iCtr.</td>";
//            $data_geseh .= "<td align='right'>$add_name</td>";
                    foreach ($headerFields as $headerKey => $headerLabel) {
                        $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                        $link = isset($cData['link']) ? $cData['link'] : "";

                        $bg_color_c = "";
                        if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                            if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                                $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                            }
                        }

                        if (is_numeric($cValue)) {
                            if ($headerKey != "pID") {
                                if (!isset($total[$headerKey])) {
                                    $total[$headerKey] = 0;
                                }
                                $total[$headerKey] += $cValue;

                                $align_class = "text-right";
                                $cValue_f = number_format($cValue, "0", ".", ",");
                            }
                            else {
                                $align_class = "text-center";
                                $cValue_f = $cValue;
                            }

                            $data_geseh .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                        }
                        else {
                            $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                            $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                            $data_geseh .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                        }

                    }
                    $data_geseh .= "</tr>";
                }
            }

            $data_geseh .= "<tr bgcolor='#e5e5e5'>";
            $data_geseh .= "<td>&nbsp;</td>";
//        $data_geseh .= "<td>&nbsp;</td>";
            foreach ($headerFields as $cName => $cValue) {
                if (isset($arrTotalBawahGeseh[$cName])) {
                    $data_geseh .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($arrTotalBawahGeseh[$cName]) . "</td>";
                }
                else {
                    $data_geseh .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }
            $data_geseh .= "</tr>";

            $data_geseh .= "</table>";
            $data_geseh .= "</div>";
            $data_geseh .= "</div>";
            $data_geseh .= "<br>";
        }
        //endregion items geseh.....


        //region items normal.....
        $data_total = "<div class='panel'>";

        $data_total .= "<div class='table-responsive'>";
        $data_total .= "<table width='100%' class='table table-bordered datatables'>";

        $data_total .= "<tr bgcolor='#e5e5e5'>";
        $data_total .= "<td align='right'>No.</td>";
        foreach ($headerFields as $cName => $cValue) {
            $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
            $data_total .= "$cValue &nbsp;";
            $data_total .= "</th>";
        }
        $data_total .= "</tr>";

        if (isset($items) && (sizeof($items) > 0)) {
            $arrTotalBawah_item = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
//            $data_total .= "<td align='right'>$add_name</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                        if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                            $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                        }
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($arrTotalBawah_item[$headerKey])) {
                                $arrTotalBawah_item[$headerKey] = 0;
                            }
                            $arrTotalBawah_item[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";


                    }
                    else {
                        $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                        $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }
//            $data_total .= "<tr style='background-color:$background_color;'>";
//            $data_total .= "<td align='right'>$iCtr.</td>";
//            foreach ($headerFields as $headerKey => $headerLabel){
//                $align_class = "text-right";
//                $total_bawah = isset($total[$key]) ? $total[$key] : 0;
//                $total_bawah_f = number_format($cValue, "0", ".", ",");
//                $data_total .= "<td class='$align_class'>" . $total_bawah_f . "</td>";
//            }
//            $data_total .= "</tr>";
        }

        $data_total .= "<tr bgcolor='#e5e5e5'>";
        $data_total .= "<td>&nbsp;</td>";
//        $data_total .= "<td>&nbsp;</td>";
        foreach ($headerFields as $cName => $cValue) {
            if (isset($arrTotalBawah_item[$cName])) {
                $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($arrTotalBawah_item[$cName]) . "</td>";
            }
            else {
                $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
            }
        }
        $data_total .= "</tr>";

        $data_total .= "</table>";
        $data_total .= "</div>";
        $data_total .= "</div>";
        //endregion


        //region data hari.....
        $data_hari = "";
        if (isset($arrSaldoHari) && (sizeof($arrSaldoHari) > 0)) {

            $data_hari .= "<div class='panel'>";

            $data_hari .= "<div class='table-responsive'>";
            $data_hari .= "<table width='100%' class='table table-bordered datatables'>";

            $data_hari .= "<tr bgcolor='#e5e5e5'>";
            $data_hari .= "<td align='right'>No.</td>";
            foreach ($headerFieldsHari as $cName => $cValue) {
                $data_hari .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_hari .= "$cValue &nbsp;";
                $data_hari .= "</th>";
            }
            $data_hari .= "</tr>";

            if (isset($arrSaldoHari) && (sizeof($arrSaldoHari) > 0)) {
                $total = array();
                $iCtr = 0;
                foreach ($arrSaldoHari as $cData) {
                    $iCtr++;

                    $add_name = "";
                    $background_color = "";

                    if (isset($marking) && sizeof($marking) > 0) {
                        $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                        $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                    }

                    $data_hari .= "<tr style='background-color:$background_color;'>";
                    $data_hari .= "<td align='right'>$iCtr.</td>";
//            $data_hari .= "<td align='right'>$add_name</td>";
                    foreach ($headerFieldsHari as $headerKey => $headerLabel) {
                        $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                        $link = isset($cData['link']) ? $cData['link'] : "";

                        $bg_color_c = "";
                        if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                            if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                                $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                            }
                        }

                        if (is_numeric($cValue)) {
                            if ($headerKey != "pID") {
                                if (!isset($total[$headerKey])) {
                                    $total[$headerKey] = 0;
                                }
                                $total[$headerKey] += $cValue;

                                $align_class = "text-right";
                                $cValue_f = number_format($cValue, "0", ".", ",");
                            }
                            else {
                                $align_class = "text-center";
                                $cValue_f = $cValue;
                            }

                            $data_hari .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                        }
                        else {
                            $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                            $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                            $data_hari .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                        }

                    }
                    $data_hari .= "</tr>";
                }
            }

            $data_hari .= "<tr bgcolor='#e5e5e5'>";
            $data_hari .= "<td>&nbsp;</td>";
//        $data_hari .= "<td>&nbsp;</td>";
            foreach ($headerFieldsHari as $cName => $cValue) {
                if (isset($arrTotalBawah[$cName])) {
                    $data_hari .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($arrTotalBawah[$cName]) . "</td>";
                }
                else {
                    $data_hari .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }
            $data_hari .= "</tr>";

            $data_hari .= "</table>";
            $data_hari .= "</div>";
            $data_hari .= "</div>";
        }
        //endregion


        //region saldo mutasi vs saldo cache.....
        $data_mcache = "";
        if (isset($arrMutasiCache) && (sizeof($arrMutasiCache) > 0)) {

            $data_mcache .= "<div class='panel'>";

            $data_mcache .= "<div class='table-responsive'>";
            $data_mcache .= "<table width='100%' class='table table-bordered datatables'>";

            $data_mcache .= "<tr bgcolor='#e5e5e5'>";
            $data_mcache .= "<td align='right'>No.</td>";
            foreach ($headerMutasiCache as $cName => $cValue) {
                $data_mcache .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_mcache .= "$cValue &nbsp;";
                $data_mcache .= "</th>";
            }
            $data_mcache .= "</tr>";

            if (isset($arrMutasiCache) && (sizeof($arrMutasiCache) > 0)) {
                $total = array();
                $iCtr = 0;
                foreach ($arrMutasiCache as $cData) {
                    $iCtr++;

                    $add_name = "";
                    $background_color = "";

                    if (isset($markingMutasiCache) && sizeof($markingMutasiCache) > 0) {
                        $add_name = isset($markingMutasiCache[$cData['pID']]['add_name']) ? $markingMutasiCache[$cData['pID']]['add_name'] : "";
                        $background_color = isset($markingMutasiCache[$cData['pID']]['background-color']) ? $markingMutasiCache[$cData['pID']]['background-color'] : "";
                    }

                    $data_mcache .= "<tr style='background-color:$background_color;'>";
                    $data_mcache .= "<td align='right'>$iCtr.</td>";
//            $data_mcache .= "<td align='right'>$add_name</td>";
                    foreach ($headerMutasiCache as $headerKey => $headerLabel) {
                        $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                        $link = isset($cData['link']) ? $cData['link'] : "";

                        $bg_color_c = "";
                        if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                            if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                                $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                            }
                        }

                        if (is_numeric($cValue)) {
                            if ($headerKey != "pID") {
                                if (!isset($total[$headerKey])) {
                                    $total[$headerKey] = 0;
                                }
                                $total[$headerKey] += $cValue;

                                $align_class = "text-right";
                                $cValue_f = number_format($cValue, "0", ".", ",");
                            }
                            else {
                                $align_class = "text-center";
                                $cValue_f = $cValue;
                            }

                            $data_mcache .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                        }
                        else {
                            $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                            $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                            $data_mcache .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                        }

                    }
                    $data_mcache .= "</tr>";
                }
            }

            $data_mcache .= "<tr bgcolor='#e5e5e5'>";
            $data_mcache .= "<td>&nbsp;</td>";
//        $data_mcache .= "<td>&nbsp;</td>";
            foreach ($headerMutasiCache as $cName => $cValue) {
                if (isset($arrTotalBawahMutasiCache[$cName])) {
                    $data_mcache .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($arrTotalBawahMutasiCache[$cName]) . "</td>";
                }
                else {
                    $data_mcache .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }
            $data_mcache .= "</tr>";

            $data_mcache .= "</table>";
            $data_mcache .= "</div>";
            $data_mcache .= "</div>";
            $data_mcache .= "<br>";
        }
        //endregion


        $list_data .= $data_geseh;
        $list_data .= $data_total;
        $list_data .= $data_hari;
        $list_data .= $data_mcache;


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->setContent($contens);
        $p->render();
        break;

    case "index_all":
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


        if (isset($button) && sizeof($button) > 0) {
            $list_data .= "<div class='panel'>";
            $list_data .= "<div class='input-group'>";
            foreach ($button as $bKey => $bVal) {
                $list_data .= $bVal;
            }
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $data_total = "";

        if (isset($warning) && sizeof($warning) > 0) {
            $data_total .= "<div class='panel' style='background-color:yellow'>";
            foreach ($warning as $wSpec) {
                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
            }
            $data_total .= "</div>";
        }


        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";

//            $data_total .= "<div>";
//            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
//            $data_total .= "</div>";
            foreach ($cabang as $cID => $cNama) {
                $data_total .= "<div>";
                $data_total .= "<h4>$cNama</h4>";
                $data_total .= "</div>";

                $data_total .= "<div class='table-responsive'>";
                $data_total .= "<table width='100%' class='table table-bordered datatables'>";
                $data_total .= "<tr bgcolor='#e5e5e5'>";
                $data_total .= "<td align='right'>No.</td>";
                $data_total .= "<td align='center'>---</td>";
                foreach ($headerFields as $cName => $cValue) {
                    $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                    $data_total .= "$cValue &nbsp;";
                    $data_total .= "</th>";
                }
                $data_total .= "</tr>";

                $total = array();
                $iCtr = 0;
                foreach ($items[$cID] as $cData) {
                    $iCtr++;

                    $add_name = "";
                    $background_color = "";

                    if (isset($marking) && sizeof($marking) > 0) {
                        $add_name = isset($marking[$cID][$cData['pID']]['add_name']) ? $marking[$cID][$cData['pID']]['add_name'] : "";
                        $background_color = isset($marking[$cID][$cData['pID']]['background-color']) ? $marking[$cID][$cData['pID']]['background-color'] : "";
                    }

                    $data_total .= "<tr style='background-color:$background_color;'>";
                    $data_total .= "<td align='right'>$iCtr.</td>";
                    $data_total .= "<td align='right'>$add_name</td>";
                    foreach ($headerFields as $headerKey => $headerLabel) {
                        $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : 0;
                        $link = isset($cData['link']) ? $cData['link'] : "";

                        $bg_color_c = "";
                        if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                            if (isset($markingColumn[$cID][$cData['pID']]) && sizeof($markingColumn[$cID][$cData['pID']]) > 0) {
                                $bg_color_c = isset($markingColumn[$cID][$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cID][$cData['pID']][$headerKey]['background-color'] : "";
                            }
                        }

                        if (is_numeric($cValue)) {
                            if ($headerKey != "pID") {
                                if (!isset($total[$cID][$headerKey])) {
                                    $total[$cID][$headerKey] = 0;
                                }
                                $total[$cID][$headerKey] += $cValue;

                                $align_class = "text-right";
                                $cValue_f = number_format($cValue, "0", ".", ",");
                            }
                            else {
                                $align_class = "text-center";
                                $cValue_f = $cValue;
                            }

                            $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                        }
                        else {
                            $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                            $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                            $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                        }

                    }
                    $data_total .= "</tr>";
                }

                $data_total .= "<tr bgcolor='#e5e5e5'>";
                $data_total .= "<td>&nbsp;</td>";
                $data_total .= "<td>&nbsp;</td>";
                foreach ($headerFields as $cName => $cValue) {
                    if (isset($total[$cID][$cName])) {
                        $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cID][$cName]) . "</td>";
                    }
                    else {
                        $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                    }
                }
                $data_total .= "</tr>";
                $data_total .= "</table>";
                $data_total .= "</div>";

            }


            $data_total .= "</div>";

            $data_total .= "<script>
                    $(document).ready( function(){

                        var table = $('table.datatables').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [
                                        { extend: 'print', footer: true },

                                    ],

//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],
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
                            
                                });

                            });

                            $('.table-responsive').floatingScroll();
                    </script>";

            $list_data .= $data_total;

        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->setContent($contens);
        $p->render();
        break;

    case "indexManufactur":
        $add_style = "font-size:20px;";
        $contens = "";
        $p = New Layout("$title", "$subTitle", "application/template/toolManufactur.html");

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

        //        $list_data .= "<div class='panel'>";
        //        $list_data .= "<div class='input-group'>";
        //
        //        $list_data .= "<span class='input-group-btn'>";
        //        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip' data-placement='right' onclick=\"document.location.href='" . $thisPage . "&q=';\"><span class='glyphicon glyphicon-remove'></span></a>";
        //        $list_data .= "</span>";
        //
        //        $list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='$q (type to search..)' onfocus='this.select()' onkeydown=\"if(detectEnter()==true){document.location.href='" . $thisPage . "&q='+this.value;}\">";
        //
        //        $list_data .= "<span class='input-group-btn'>";
        //
        //        $list_data .= "<a class='btn btn-default' href='javascript:void(0)' title='search using keyword' data-toggle='tooltip' data-placement='left'  onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\"><span class='glyphicon glyphicon-search'></span></a>";
        //        $list_data .= "</span class='input-group-addon'>";
        //
        //        $list_data .= "</div class='input-group'>";
        //
        //        $list_data .= "</div class='panel panel-default'>";

//        if (isset($button) && sizeof($button) > 0) {
//            $list_data .= "<div class='panel'>";
//            $list_data .= "<div class='input-group'>";
//            foreach ($button as $bKey => $bVal) {
//                $list_data .= $bVal;
//            }
//            $list_data .= "</div>";
//            $list_data .= "</div>";
//        }

        $data_total = "";
//
//        if (isset($warning) && sizeof($warning) > 0) {
//            $data_total .= "<div class='panel' style='background-color:yellow'>";
//            foreach ($warning as $wSpec) {
//                $data_total .= isset($wSpec['update']) ? $wSpec['update'] . "<br><br>" : "";
//                $data_total .= isset($wSpec['insert']) ? $wSpec['insert'] . "<br><br>" : "";
//            }
//            $data_total .= "</div>";
//        }

        if (isset($gudangSetting) && sizeof($gudangSetting) > 0) {
            $strGudang = "<div cclass='input-group col-md-12'>";
            $strGudang .= "<span class='text-larger'>$gudangLabel :</span> &nbsp;&nbsp;&nbsp;&nbsp;";
            foreach ($gudangSetting as $spec) {
                $id = $spec["id"];
                $label = $spec["nama"];
                $selected = $spec["selected"];
                $strGudang .= "<label class='radio-inline text-larger'><input type='radio' class='radio' name='setting_gudang' $selected> $label</label>";
            }
            $strGudang .= "</div class='input-group'>";
        }


        if (isset($items) && sizeof($items) > 0) {
            $i = 0;
            $data_total .= "<div class='panel'>";
//
//            $data_total .= "<div>";
//            $data_total .= "NB: baris dengan background kuning berarti tidak cocok / geseh......";
//            $data_total .= "</div>";

            $data_total .= "<div class='table-responsive'>";
            $data_total .= "<table id='table_history' width='100%' class='table table-bordered datatables'>";

            $data_total .= "<thead>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th align='right'>No.</th>";
//            $data_total .= "<td align='center'>---</td>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$cValue &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $data_total .= "<tbody>";
            $total = array();
            $iCtr = 0;
            foreach ($items as $cData) {
                $iCtr++;

                $add_name = "";
                $background_color = "";

                if (isset($marking) && sizeof($marking) > 0) {
                    $add_name = isset($marking[$cData['pID']]['add_name']) ? $marking[$cData['pID']]['add_name'] : "";
                    $background_color = isset($marking[$cData['pID']]['background-color']) ? $marking[$cData['pID']]['background-color'] : "";
                }

                $data_total .= "<tr style='background-color:$background_color;'>";
                $data_total .= "<td align='right'>$iCtr.</td>";
//                $data_total .= "<td align='right'>$add_name</td>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = isset($cData[$headerKey]) ? $cData[$headerKey] : "";
                    $link = isset($cData['link']) ? $cData['link'] : "";

                    $bg_color_c = "";
                    if (isset($markingColumn) && sizeof($markingColumn) > 0) {
                        if (isset($markingColumn[$cData['pID']]) && sizeof($markingColumn[$cData['pID']]) > 0) {
                            $bg_color_c = isset($markingColumn[$cData['pID']][$headerKey]['background-color']) ? $markingColumn[$cData['pID']][$headerKey]['background-color'] : "";
                        }
                    }

                    if (is_numeric($cValue)) {
                        if ($headerKey != "pID") {
                            if (!isset($total[$headerKey])) {
                                $total[$headerKey] = 0;
                            }
                            $total[$headerKey] += $cValue;

                            $align_class = "text-right";
                            $cValue_f = number_format($cValue, "0", ".", ",");
                        }
                        else {
                            $align_class = "text-center";
                            $cValue_f = $cValue;
                        }

                        $data_total .= "<td style='background-color:$bg_color_c;' class='$align_class'>" . $cValue_f . "</td>";
                    }
                    else {
                        $jenisTr_master = isset($cData['jenisTr_master']) ? $cData['jenisTr_master'] : NULL;
                        $modul_path = isset($cData['modul_path']) ? $cData['modul_path'] : NULL;
                        $data_total .= "<td style='background-color:$bg_color_c;'>" . formatField_he_format($headerKey, $cValue, $jenisTr_master, $modul_path) . "</td>";
                    }

                }
                $data_total .= "</tr>";
            }
            $data_total .= "</tbody>";

            $data_total .= "<tfoot>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<td>&nbsp;</td>";
//            $data_total .= "<td>&nbsp;</td>";
            foreach ($headerFields as $cName => $cValue) {
                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='color:#555555;padding:3px;'>&nbsp;</td>";
                }
            }
            $data_total .= "</tr>";
            $data_total .= "</tfoot>";

            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";

//            $data_total .= "<script>
//                    $(document).ready( function(){
//
//                        var table = $('table.datatables').DataTable({
//                            dom: 'lBfrtip',
//                            fixedHeader: true,
//                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
//                            pageLength: -1,
//                            buttons: [
//                                        { extend: 'print', footer: true },
//
//                                    ],
//
//
//                                });
//
//                            });
//
//                            $('.table-responsive').floatingScroll();
//                    </script>";

            $data_total .= "<script>
                    $(document).ready( function(){
                        var table = $('#table_history').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            stateSave: true,
                            processing: true,
                            searchDelay: 1500,
                            search: {
                                smart: false
                            },

                            buttons: [
                                        { extend: 'print', footer: true },
                                        {
                                            text: 'Download Excel',
                                            action: function (e, dt, node, config) {
                                                fnExcelReport('table_history');
                                            }
                                        },
                                        $custom_button
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
                                        var arrayFooter = $('#table_history>tfoot>tr>th');
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
                                $('.table-responsive.table_history').floatingScroll();
                                $('.table-responsive.table_history').scroll(function() {
                                    setTimeout(function () {
                                        $('#table_history').DataTable().fixedHeader.adjust();
                                    }, 100);
                            });
                            });
                    </script>";

            $list_data .= $data_total;

        }
        //        else {
        //
        //            $list_data .= "<div class='panel panel-default'>";
        //            $list_data .= "<div class='panel-body'>";
        //            $list_data .= "there is no item name matched your criteria<br>";
        //            $list_data .= "you mant want to go back or select other keyword<br>";
        //
        //            $list_data .= "</div>";
        //            $list_data .= "</div>";
        //
        //        }


        if (isset($content)) {
            $list_data .= "<div class='panel panel-default'>";
            $list_data .= "<div class='panel-body'>";
            $list_data .= $content;
            $list_data .= "</div>";
            $list_data .= "</div>";
        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
                "sub_subtitle" => isset($gudangLabel) ? $gudangLabel : "",
                "sub_content" => isset($strGudang) ? $strGudang : "",
            )
        );

        $p->setContent($contens);
        $p->render();
        break;
}