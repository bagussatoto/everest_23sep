<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
//cekHere($mode);
switch ($mode) {

    case "viewStocks#":
        //$myId = my_id();
        //$myCabangId = my_cabang_id();
        $contens = "";
        $contens .= "<script type=\"text/javascript\">
         var table;
                        $(document).ready(function() {
                        table = $('#table').DataTable({
//                        'processing': true,
//                        'serverSide': true,
                        'pageLength': 50,
                   });

                        });
                        </script>";

        $p = New Pages("$title", "$subTitle", "application/template/pages.html");
        $template = array(
            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
        );

        matiHere("hooppppp");
        $this->table->set_template($template);
        if (sizeof($arrayKoloms) > 0) {
            $header = array('data' => "No", 'class' => 'text-center');
            $header_f = array();
            $header_f[] = $header;
            foreach ($arrayKoloms as $baris => $label) {
                $header_result_f = array('data' => $label, 'class' => 'text-center');
                $header_f[] = $header_result_f;
            }
            $this->table->set_heading($header_f);

            if (sizeof($items) > 0) {
                $no = 0;
                foreach ($items as $key => $baris) {
                    $no++;
                    $no_f = array('data' => $no, 'class' => 'text-center');
                    $isi = array();
                    $isi[] = $no_f;
                    foreach ($arrayKoloms as $kolom => $label) {
                        $stylev = $style[$key][$kolom];
                        if (sizeof($baris) > 0) {
                            $nilai = $baris[$kolom];
                            $input_value = $nilai;
                        }
                        else {
                            $input_value = "";
                        }
                        $isi[] = array('data' => $input_value, 'class' => $stylev);
                    }
                    $this->table->add_row($isi);
                }
            }
            else {
                $this->table->add_row(array(
                    'data' => 'tidak ada data',
                    'colspan' => count($arrayKoloms) + 2,
                    'class' => 'text-center'
                ));
            }
        }
        $contens .= $this->table->generate();

        $p->setMenuLeft($left_menu);
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }


        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;
    case "viewLastMutasi":
        //$myId = my_id();
        //$myCabangId = my_cabang_id();
        //  region segment-segment
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $segment_url = "";
        for ($x = 1; $x <= ($segment_page - 1); $x++) {
            $segment_url .= $this->uri->segment($x) . "/";
        }
        $segment_url = rtrim($segment_url, "/");
        $segment_url .= "";

        $segment_url_right = "";
        for ($x = 1; $x <= ($segment_page - 2); $x++) {
            $segment_url_right .= $this->uri->segment($x) . "/";
        }
        $segment_url_right = rtrim($segment_url_right, "/");
        $segment_url_right .= "";

        $base_url = base_url();
        //endregion

        //region form pencarian bahan
        $arrAtribut = array(
            "name" => "resultt",
            "id" => "resultt",
            'method' => 'post',
        );
        $action_link = $base_url . "$segment_url/findBahan";
        $form_searching = "<div style='padding: 0 0 10px 0;'>";
        $form_searching .= "<div style='padding-bottom:40px;' class='col-md-3'>";
        $form_searching .= "<div class='form-group'><input type='text' id='textHint' name='search' value='' class='form-control' placeholder='ketikan nama bahan'></div><div id='textResult'></div>";
        $form_searching .= "<script type=\"text/javascript\" >
                $(document).ready(function () {
                    $(\"#textHint\").keyup(function () {
                        var str = $(this).val();
                        $.get(\"$action_link?p=\" + str, function (data) {
                            $(\"#textResult\").html(data);
                        });
                    });
                });
            </script>";
        $form_searching .= "</div>";

        $form_searching .= "</div>";

        //endregion


        $contens = "";
        $link = "";
        if (isset($pages)) {
            $link .= "<div class='pull-right'>";
            foreach ($pages as $nomer => $target) {
                $link .= "<a href='$target' class='btn btn-xs btn-default' >$nomer</a>";
            }
            $link .= "</div>";
        }

        $contens .= "<script type=\"text/javascript\">
         var table;
                        $(document).ready(function() {
                        table = $('#table').DataTable({
//                        'processing': true,
//                        'serverSide': true,
                        'pageLength': 50,
                   });

                        });
                        </script>";

        $p = New Pages("$title", "$subTitle", "application/template/pages.html");
        $template = array(
            'table_open' => '<table id="tablee" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
        );
        $this->table->set_template($template);
        if (sizeof($arrayKoloms) > 0) {
            $header_f[] = "No";
            foreach ($arrayKoloms as $kolom => $alias) {
                $header_result_f = array('data' => $alias, 'class' => 'text-center');
                $header_f[] = $header_result_f;
            }
            $this->table->set_heading($header_f);
        }

        if (is_array($items) > 0) {
            $no = 0;
            foreach ($items as $arrayTemp) {
                $no++;
                $isi = array();
                $isi[] = $no;
                foreach ($arrayKoloms as $kolom => $label) {
                    if (sizeof($arrayTemp) > 0) {
                        if (array_key_exists($label, $arrayTemp)) {
                            $nilai = $arrayTemp[$label];
                            $input_value = $nilai;
                        }
                        else {
                            $input_value = "0";
                        }

                    }
                    else {
                        $input_value = "";
                    }
                    $isi[] = array('data' => $input_value, 'class' => 'text-center');
                }
                $this->table->add_row($isi);

            }
        }
        else {
            $this->table->add_row(array(
                'data' => 'tidak ada data mutasi',
                'colspan' => count($arrayKoloms) + 2,
                'class' => 'text-center'
            ));
        }
        $contens .= $form_searching;
        $contens .= $link;
        $contens .= $this->table->generate();
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->setContent($contens);
        $p->setProfileName($this->session->login['nama']);
        $p->render();
        break;
    case "viewStocks":
//        arrPrint($btnTop);
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
        $list_data .= $btnTop;
        $list_data .= "<div class='row' style=\"margin-bottom: 10px;\"></div>";
        if (sizeof($items) > 0) {

            $i = 0;
            $data_total = "<div class=''>";
            $data_total .= "<div class='clearfix'> &nbsp; </div>";
            $data_total .= "<table width='100%' class='table datatable table-bordered'>";

            $data_total .= "<thead>";
            $data_total .= "<tr>";
            foreach ($headerFields as $cName => $cValue) {
                $data_total .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>$cValue</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";

            $total = array();
            foreach ($items as $cData) {
                $data_total .= "<tr>";
                foreach ($headerFields as $headerKey => $headerLabel) {
                    $cValue = $cData[$headerKey] ? $cData[$headerKey] : 0;
                    $link = $cData['link'];
                    $data_total .= "<td><a href='$link'>" . formatField($headerKey, $cValue) . "</a></td>";
                    if (is_numeric($cValue)) {
                        if (!isset($total[$cName])) {
                            $total[$cName] = 0;
                        }
                        $total[$cName] += $cValue;
                    }

                }
                $data_total .= "</tr>";
            }

            $data_total .= "<tfoot>";
            $data_total .= "<tr>";

            foreach ($headerFields as $cName => $cValue) {

                if (isset($total[$cName])) {
                    $data_total .= "<td class='text-bold text-right' style='background:#e5e5e5;color:#555555;padding:3px;'>" . number_format($total[$cName]) . "</td>";
                }
                else {
                    $data_total .= "<td class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>" . formatField('textJumlah', 'jumlah') . "</td>";
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
            $list_data .= "<div class='panel-heading col-md-12'>";
            $list_data .= "<table>";
            $list_data .= "<tr>";
            foreach ($headerFields as $cName => $cValue) {
                $list_data .= "<th class='text-center text-uppercase' style='background:#e5e5e5;color:#555555;padding:3px;'>$cValue</th>";
            }
            $list_data .= "</tr>";
            $list_data .= "<tr><td colspan='3'>No data to view</td></tr>";
            $list_data .= "<table>";
            $list_data .= "</div>";
            $list_data .= "<div class=' text-center '>No data to view</div>";
            $list_data .= "</div>";

        }

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
//                "menu_sub" => callSubMEnu(),
                "content" => $list_data,
                "profile_name" => $this->session->login['nama'],
                "btnTop" => $btnTop,
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        $p->setContent($contens);
        $p->render();
        break;
    case "view":

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "menu_sub" => callSubMEnu(),
                "content" => "",
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        $p->setContent($contens);
        $p->render();
        break;

}