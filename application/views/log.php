<?php
//cekHere($mode);
switch ($mode) {
//    case "viewLog":
//        $add_style = "font-size:20px;";
//        $contens = "";
//        $p = New Layout("$title", "", "application/template/pages.html");
//        $template = array(
//            'table_open' => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
//            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
//            'thead_close' => '</thead>',
//            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
//            'footer_row_start' => '<tr>',
//            'footer_row_end' => '</tr>',
//            'footer_cell_start' => '<th>',
//            'footer_cell_end' => '</th>',
//            'tfoot_close' => '</tfoot>',
//            'table_close' => '</table>'
//        );
//        $this->table->set_template($template);
//
//        $p->addTags(
//            array(
//                "menu_left" => $menuLeft,
////                "btn_back"=>callBackNav(),
//                "content" => $list_data,
//                "profile_name" => $this->session->login['nama'],
//            )
//        );
//        //  endregion menu left
//matiHere("XXXXXXXX");
//        if (isset($lebar_modal)) {
//            $p->setLebarModal($lebar_modal);
//        }
//        $p->setContent($contens);
//        $p->render();
//        break;

    case "viewLog":
        $add_style = "font-size:20px;";
        $contens = "";
//        $p = New Layout("$title", "$subTitle", "application/template/toolModif.html");
        $p = New Layout("$title", "$subTitle", "application/template/log.html");

        //region template table
        $template = array(
            'table_open' => '<table id="table" class="table table-condensed table-striped table-responsive">',
            // 'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_open' => '<thead>',
            'thead_close' => '</thead>',
            'heading_row_start' => '<tr class="bg-grey-2">',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end' => '</th>',

            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );
        $mainTemplate = array(
            'table_open' => '<table id="main_table" class="table table-condensed margin-top-5 stripe">',
            'thead_open' => '<thead>',
            'thead_close' => '</thead>',
            'heading_row_start' => '<tr class="bg-grey-2">',
            'heading_row_end' => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end' => '</th>',

            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );
        $itemsTemplate = array(
            'table_open' => '<table id="table" class="table table-bordered table-condensed anu">',
            'thead_open' => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close' => '</thead>',
            'tfoot_open' => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start' => '<tr>',
            'footer_row_end' => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end' => '</th>',
            'tfoot_close' => '</tfoot>',
            'table_close' => '</table>',
        );
        $foldersTemplate = array(
            'table_open' => '<table id="folder_table" class="table table-condensed">',
            'thead_open' => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
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
        //endregion

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
            foreach ($headerFields as $cName => $cValue) {
                $header_result_f = array('data' => $cValue, 'class' => '');
                $header_prog[] = $header_result_f;

            }
//            $isi[] = array('data' => "Nomer ", 'class' => 'text-left');
            $this->table->set_heading($header_prog);

            $total = array();
            $iCtr = 0;
            foreach ($items as $val) {
                $iCtr++;
                if (sizeof($headerFields) > 0) {
                    $isi = array();
                    foreach ($headerFields as $key => $label) {
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                $imgsrc = "src='$images'";
                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif?=v1";
                                $imgsrc = "src='$imageAvail'";
                            }
                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $jenis_master = isset($val['jenis_master']) ? $val['jenis_master'] : "";
                            $modul_path = isset($val['modul_path']) ? $val['modul_path'] : "";
                            $value = isset($val[$key]) ? formatField_he_format($key, $val[$key], $jenis_master, $modul_path) : "";
                        }
                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
//                    $isi[] = array('data' => "Nomer ", 'class' => 'text-left');
                    $this->table->add_row($isi);
                }

            }
//            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
            $strDataProposeFooter = "";
//            $list_data .= $data_total;

        }
        else {
            $this->table->add_row(array(
                'data' => '-the item you specified has no entry-',
                'colspan' => count($headerFields) + 2,
                'class' => 'text-center',
            ));
            $strDataProposeFooter = "";
        }

        $strDataPropose = $this->table->generate();


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
                "content" => $strDataPropose,
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
        break;
}