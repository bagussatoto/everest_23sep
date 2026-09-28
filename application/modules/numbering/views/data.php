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
        mati_disini("mode <b>$mode</b> belum ditentukan/ada " . __FILE__);
        break;

    case "view":
        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
        //        arrPrint($arrayHistoryLabels);
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        //region template table
        $template = array(
            'table_open'         => '<table id="table" class="table table-condensed table-striped table-responsive">',
            // 'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $mainTemplate = array(
            'table_open'         => '<table id="main_table" class="table table-condensed margin-top-5 stripe">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed anu">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="folder_table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion

        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {
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
                            $value = isset($val[$key]) ? $val[$key] : "";

                        }
                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region histories
        if (sizeof($arrayHistory) > 0) {
            if (sizeof($arrayHistoryLabels) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabels as $key => $label) {

                    $header_Hist_f = array('data' => strtoupper($label), 'class' => 'text-muted');
                    $header_Hist[] = $header_Hist_f;
                }
                $this->table->set_heading($header_Hist);
            }
            foreach ($arrayHistory as $key => $val) {

                if (sizeof($arrayHistoryLabels) > 0) {

                    $isi_data = array();
                    foreach ($arrayHistoryLabels as $key => $label) {

                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                $imgsrc = "src='$images'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";
                        }
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            if (strstr($value, "Javascript:void(0)")) {
                                $isExist_href = str_replace("javascript:void(0)", "#", $value);
                            }
                            else {
                                $isExist_href = $value;
                            }
                        }
                        else {
                            $isExist_href = $value;
                        }
                        $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strActiveDataFooter = "<a class='btn btn-default ' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strActiveDataFooter = "";
        }

        $this->table->set_template($mainTemplate);
        $strActiveData = $this->table->generate();
        /* ---------------------------------------
         * dataTable dipangil dari template data.html
         * -------------------------------------*/

        //region folders
        $strFolder = "";
        if (sizeof($folders) > 0) {
            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                $isExist_href = "#";
                $formTarget1 = "#";

            }
            else {
                $isExist_href = "javascript:void(0)";
                $formTarget1 = "$fmdlTarget";
            }
            $addStr = "";
            if ($faddLink != "") {
                $addClick = "BootstrapDialog.show(
                                   {
                                        title:'add folder',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $faddLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                $addStr = "<a href='$isExist_href' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
            }

            $this->table->set_heading(array("<a href='" . $formTarget1 . "'>folder name</a>", $addStr));
            foreach ($folders as $fID => $fName) {
                $isi_data = array();
                $newTargetPage = str_replace("fID", "_f", $thisPage) . "&fID=$fID&fName=$fName";
                $targetHref = isset($_GET['mode']) && $_GET['mode'] == 'print' ? "#" : $newTargetPage;
                $value = "<a href='$targetHref'><span class='fa fa-folder-o'></span> $fName</a>";
                //                $value = "<a href='$newTargetPage'><span class='fa fa-folder-o'></span> $fName*</a>";
                $bgColor = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#e5e5ef" : "transparent";
                $color = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#000000" : "#005689";

                //region manip
                $editClick = "";
                $editStr = "";
                if ($fID > 0 && $feditLink != "") {
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify folder $fName',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $feditLink . $fID . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                    $editStr = "<a href='$isExist_href' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                }
                //endregion

                $isi_data[] = array(
                    'data'    => $value,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $isi_data[] = array(
                    'data'    => $editStr,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $this->table->add_row($isi_data);

            }
            $this->table->set_template($foldersTemplate);
            $strFolder = $this->table->generate();
        }
        //endregion


        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {
            //arrPrint($arrayRecapLabels);
            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strDataHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $this->table->set_template($template);
        $strDataHist = $this->table->generate();
        //endregion


        if (sizeof($arrayOnProgress) > 0) {

            $propDisplay = "block";
        }
        else {

            $propDisplay = "none";
        }
        //cekHere($strEditLink);

        $p->addTags(array(
            "prop_display"          => $propDisplay,
            "menu_right_isi"        => callMenuRightIsi(),
            "menu_left"             => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"       => callFloatMenu('atas'),
            "float_menu_bawah"      => callFloatMenu(),
            "menu_taskbar"          => callMenuTaskbar(),
            "btn_back"              => callBackNav(),
            "data_propose_title"    => $strDataProposeTitle,
            "data_propose_content"  => $strDataPropose,
            "data_propose_footer"   => $strDataProposeFooter,
            "add_link"              => $strAddLink,
            "edit_link"             => $strEditLink,
            "data_active_title"     => $strActiveDataTitle,
            "data_active_content"   => $strActiveData,
            "data_active_footer"    => $alternateLink,
            "data_hist_title"       => $strDataHistTitle,
            "data_hist_content"     => $strDataHist,
            "data_hist_footer"      => $strDataHistFooter,
            "profile_name"          => $this->session->login['nama'],
            "link_str"              => $linkStr,
            "error_msg"             => $error,
            //                "search_str" => $searchStr,
            "this_page"             => $thisPage,
            "search_str"            => isset($_GET['k']) ? $_GET['k'] : "",
            "folders"               => $strFolder,
            "reg_folders_classname" => sizeof($folders) > 0 ? "col-lg-3" : "col-lg-0",
            "reg_items_classname"   => sizeof($folders) > 0 ? "col-lg-9" : "col-lg-12",
            "stop_time"             => "",
            "menu_depresiasi"       => "",
        ));
        $p->render();

        break;
    case "viewHistories":
        $p = new Layout("$title", "$subTitle", "application/template/data_history.html");

        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $seleted_data = ("<ul class='pager'>");
            //            $content.=("<li>select state: ");
            //            $content.=("</li>");
            //            arrPrint($stepLabels);
            foreach ($stepLabels as $step => $label) {
                //                $btnClass = $step == $currentState ? "btn-success" : "btn-default";
                $btnClass = "btn-default";
                $seleted_data .= ("<li>");
                $seleted_data .= ("<button class='btn $btnClass' onclick =\"location.href='" . $stepLinks[$label] . "';\">$step</button>");
                $seleted_data .= ("</li>");
            }
            $seleted_data .= ("</ul class='pager'>");
        }
        $content = "";
        $content .= ("<div class=\"box box-solid box-danger\">");
        $content .= ("<div class=\"box-header\">");
        $content .= ("<span class=\"glyphicon glyphicon-flash\"></span> $subTitle ");

        //        $content .= ("<span class=\"btn btn-tool pull-right\"><a href='$alternateLink'>$alternateLinkCaption</a></span>");

        $content .= ("</div>");
        $content .= ("<div class=\"box-body\">");
        $content .= ("<div class='tablee-responsive'>");
        $content .= "<table class='table table-condensed table-bordered no-padding'>";
        if (sizeof($header) > 0) {
            $content .= ("<tr>");
            foreach ($header as $key => $label) {
                $content .= ("<th class='text-muted'>");
                $content .= ($label);
                $content .= ("</th>");
            }
            $content .= ("</tr>");

        }
        $rowCtr = 0;
        if (sizeof($items) > 0) {
            foreach ($items as $items_0) {
                $content .= ("<tr >");
                foreach ($header as $key => $label) {
                    $rowCtr++;
                    $value = $items_0[$key];
                    $content .= ("<td>");
                    $content .= $value;
                    $content .= ("</td>");
                }
                $content .= ("</tr>");
            }
        }
        else {
            $colspan = sizeof($header);
            $content .= "<tr>";
            $content .= "<td colspan='$colspan'>";
            $content .= "<div>no item to show</div>";
            $content .= "<div>you can try to select another tab</div>";
            $content .= "</td>";
            $content .= "</tr>";
        }
        $content .= ("</table class='table table-bordered'>");
        $content .= ("</div class='table-responsive'>");
        $content .= ("</div>");

        $content .= ("</div>");
        $p->addTags(array(
            "menu_left"           => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"     => callFloatMenu('atas'),
            "float_menu_bawah"    => callFloatMenu(),
            "menu_taskbar"        => callMenuTaskbar(),
            "btn_back"            => callBackNav(),
            "data_active_content" => $content,
            "profile_name"        => $this->session->login['nama'],
            "search_str"          => isset($_GET['k']) ? $_GET['k'] : "",
            "selected_data"       => $seleted_data,
            "link_str"            => $linkStr,
            "data_active_title"   => $strActiveDataTitle,

        ));

        $p->render();
        break;
    case "add":
        $p = New Layout("$title", "$subTitle", "application/template/data.html");

        $p->addTags(array(
            "menu_left"        => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $content,
            "profile_name"     => $this->session->login['nama'],
        ));

        $p->render();
        break;
    case "edit":
//        arrPrint($jsBottom);
        $p = New Layout("$title", "$subTitle", "application/template/data.html");
        $p->addTags(array(
            "menu_left"        => callMenuLeft(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $content,
            "jsBottom"         => $jsBottom,
            "profile_name"     => $this->session->login['nama'],
        ));

        $p->render();
        break;
    case "addMany":
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $p = New Layout("$title", "$subTitle", "application/template/massEditor.html");
        $p->addTags(array(
            "menu_left"           => callMenuLeft(),
            "float_menu_atas"     => callFloatMenu('atas'),
            "float_menu_bawah"    => callFloatMenu(),
            "menu_taskbar"        => callMenuTaskbar(),
            "btn_back"            => callBackNav(),
            "data_active_title"   => "You can fill in one or more rows to $title",
            "data_active_content" => $content,
            "profile_name"        => $this->session->login['nama'],
            "error_msg"           => $error,
            "this_page"           => $thisPage,
            "form_target"         => $formTarget,
            "search_str"          => isset($_GET['k']) ? $_GET['k'] : "",
            "data_hist_title" => "",
            "data_hist_content" => "",
            "stop_time" => "",
        ));
        //endregion

        $p->render();
        break;
    case "editMany":
        //arrPrint($strOnprogFooter);
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/data.html");

        //region template table
        $template = array(
            'table_open'        => '<table id="table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion

        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";

                        $isi[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strOnprogFooter = "";
        }
        $strOnprog = $this->table->generate();
        //endregion

        //region histories
        //    arrPrint($arrayHistoryStyle);
        if (sizeof($arrayHistory) > 0) {
            if (sizeof($arrayHistoryLabels) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabels as $key => $label) {
                    //                    $width = isset($fieldSpec['width']) ? $fieldSpec['width']."px" : "";
                    $header_Hist_f = array(
                        'data'  => "<div class='div_td' >" . $label . "</div>",
                        'class' => 'text-muted',
                    );
                    $header_Hist[] = $header_Hist_f;
                }
                $this->table->set_heading($header_Hist);
            }
            foreach ($arrayHistory as $key => $val) {
                if (sizeof($arrayHistoryLabels) > 0) {
                    $isi_data = array();
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_data[] = array(
                            'data'  => "<div class='div_td'>" . $value . "</div>",
                            'class' => 'text-left',
                            'style' => 'margin:0px;padding:0px;',
                        );
                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strHistFooter = "";
        }

        $strHist = "<form method=post id=fmany name=fmany action='$formTarget' target='result'>";
        $strHist .= $this->table->generate();
        $strHist .= "</form>";

        //region folders
        $strFolder = "";
        if (sizeof($folders) > 0) {

            $addStr = "";
            if ($faddLink != "") {
                $addClick = "BootstrapDialog.show(
                                   {
                                        title:'add folder',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $faddLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                $addStr = "<a href='javascript:void(0)' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
            }

            $this->table->set_heading(array("<a href='" . $fmdlTarget . "'>folder name</a>", $addStr));
            foreach ($folders as $fID => $fName) {
                $isi_data = array();
                $newTargetPage = str_replace("fID", "_f", $thisPage) . "&fID=$fID&fName=$fName";
                $value = "<a href='$newTargetPage'><span class='fa fa-folder-o'></span> $fName</a>";
                $bgColor = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#e5e5ef" : "transparent";
                $color = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#000000" : "#005689";

                //region manip
                $editClick = "";
                $editStr = "";
                if ($fID > 0 && $feditLink != "") {
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify folder $fName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $feditLink . $fID . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                    $editStr = "<a href='javascript:void(0)' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                }
                //endregion

                $isi_data[] = array(
                    'data'    => $value,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $isi_data[] = array(
                    'data'    => $editStr,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $this->table->add_row($isi_data);

            }
            $this->table->set_template($foldersTemplate);
            $strFolder = $this->table->generate();
        }
        //endregion

        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {

            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strRecapFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strRecapFooter = "";
        }
        $strRecap = $this->table->generate();
        //endregion


        if (sizeof($arrayOnProgress) > 0) {

            $propDisplay = "block";
        }
        else {

            $propDisplay = "none";
        }

        //region add to content
        $p->addTags(array(

            "prop_display"          => $propDisplay,
            "menu_left"             => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"       => callFloatMenu('atas'),
            "float_menu_bawah"      => callFloatMenu(),
            "menu_taskbar"          => callMenuTaskbar(),
            "btn_back"              => callBackNav(),
            "data_propose_title"    => $onprogressTitle,
            "data_propose_content"  => $strOnprog,
            "data_propose_footer"   => $strOnprogFooter,
            "add_link"              => $strAddLink,
            "edit_link"             => "",
            "data_active_title"     => $historyTitle,
            "data_active_content"   => $strHist,
            "data_active_footer"    => $alternateLink,
            "data_hist_title"       => $recapTitle,
            "data_hist_content"     => $strRecap,
            "data_hist_footer"      => $strRecapFooter,
            "profile_name"          => $this->session->login['nama'],
            "link_str"              => $linkStr,
            "error_msg"             => $error,
            //                "search_str" => $searchStr,
            "this_page"             => $thisPage,
            "search_str"            => isset($_GET['k']) ? $_GET['k'] : "",
            "folders"               => $strFolder,
            "reg_folders_classname" => sizeof($folders) > 0 ? "col-lg-3" : "col-lg-0",
            "reg_items_classname"   => sizeof($folders) > 0 ? "col-lg-9" : "col-lg-12",
        ));
        //endregion

        $p->render();

        break;
    case "righMenu":
        //        arrPrint($_REQUEST);
        break;
    case "myProfile":
        // cekHijau("hhhh");
        $p = New Layout("$title", "$subTitle", $template);
        $elementLabels = array();
        // arrPrint(array_filter((array)$arrProfile));
        // $empId = $this->u
        $arrMembership = blobDecode($arrProfile->membership);

        // $strMember = "<strong>jjj</strong>";
        $strMember = "<p>";
        foreach ($arrMembership as $item) {
            // $strMember .= "<button type='button' class='btn btn-default btn-xs'>$item</button> ";
            $strMember .= "<span class='label label-info'>$item</span> ";
        }
        $strMember .= "</p>";
        // arrPrint($arrMembership);

        $arrData = $updateFields;

        $strProfile = "<img class=\"profile-user-img img-responsive img-circle\" src=\"" . base_url() . "public/images/profiles/profile-default.png\" alt=\"User profile picture\">";
        $strProfile .= "<h3 class=\"profile-username text-center\">" . $arrProfile->nama . "</h3>";
        $strProfile .= "<p class=\"text-muted text-center\">" . $arrProfile->email . "</p>";

        //region field yg tampil
        $strProfile .= "<ul class=\"list-group list-group-unbordered\">";

        if (isset($arrData)) {
            foreach ($arrData as $keField => $arrDatum) {
                $field = $arrDatum['kolom'];

                if (array_key_exists("replaceValue", $arrDatum)) {
                    if (is_array($arrDatum['replaceValue'])) {
                        $nilai = $arrDatum['replaceValue'][$arrProfile->$field];
                    }
                    else {
                        $nilai = $arrDatum['replaceValue'];
                    }
                }
                else {
                    $nilai = $arrProfile->$field;
                }


                if (isset($arrDatum['format'])) {
                    $nilai_f = $arrDatum['format']($field, $nilai);
                }
                else {
                    $nilai_f = $nilai;
                }
                if (isset($arrDatum['link'])) {

                    $href = strlen($arrDatum['link']) == 0 ? "" : "href='" . base_url() . $arrDatum['link'] . "/$field' data-toggle='modal' data-target='#myModal'";
                    $nilai_f = strlen($nilai_f) > 0 ? $nilai_f : "<i class='fa fa-pencil-square-o'></i>";
                    $nilai_l = "<a $href class='pull-right' title='edit' data-toggle='tooltip'>$nilai_f</a>";
                }
                else {
                    $nilai_l = "<span class='pull-right'>$nilai_f</span>";
                }
                $strProfile .= "<li class=\"list-group-item\">";
                $strProfile .= "<b>" . $arrDatum['label'] . "</b> $nilai_l";
                $strProfile .= "</li>";
            }
        }
        // $strProfile .= "<li class='text-center'>";
        // $strProfile .= $strMember;
        // $strProfile .= "</li>";

        $strProfile .= "</ul>";
        //endregion
        // cekHitam(my_cabang_id());
        $mdl_name = my_cabang_id() > 0 ? "EmployeeCabang" : "Employee";
        //region btn update profile
        $btn_update = "<a class='btn btn-primary btn-block' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"BootstrapDialog.show(
                                           {
                                                title:'Modify Employee ',
                                               size: BootstrapDialog.SIZE_WIDE,
                                                cssClass: 'edit-dialog',
                                                message: $('<div></div>').load('" . base_url() . "Data/edit/$mdl_name/" . $arrProfile->id . "'),
                                                draggable:true,
                                                closable:true,
                                                });\"><b>Update Data</b></a>";
        //endregion
        // $strProfile .= $btn_update;

        //region show my activity

        $template = array(
            'table_open' => '<table id="table" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $header_f = array();
        $header_f[] = array('data' => 'No', 'class' => 'text-center text-muted');
        foreach ($arrayHeader as $kolom => $label) {
            $header_f[] = array('data' => $label, 'class' => 'text-center text-muted');
        }
        $this->table->set_heading($header_f);
        if (sizeof($arrActivitylog) > 0) {
            $k = 0;
            foreach ($arrActivitylog as $kunci => $arrActivitylog_0) {
                $k++;

                $isi = array();
                $isi[] = array('data' => $k);
                foreach ($arrayHeader as $kolom => $label) {
                    $colValue = isset($arrActivitylog_0->$kolom) ? $arrActivitylog_0->$kolom : "";
                    $val_f = $kolom == "dtime" ? formatTanggal($colValue) : $colValue;
                    $data_result_f = $val_f;
                    $input_value = $data_result_f;
                    $isi[] = array('data' => $input_value);
                }
                $this->table->add_row($isi);
            }
        }
        else {
            $this->table->add_row(array(
                'data'    => "no history found for $title",
                'colspan' => count($arrayHeader) + 2,
                'class'   => 'text-center',
            ));
        }
        $content = ($this->table->generate());


        //endregion

        $p->setLayoutBoxCss("box box-danger");
        $p->setLayoutBoxBody(true);
        $showProfile = $p->layout_box("$strProfile");

        $p->setLayoutBoxCss("box box-success");
        $p->setLayoutBoxHeading("Member off");
        $p->setLayoutBoxBody(true);
        $showProfile .= $p->layout_box("$strMember");

        $elementLabels['leftProfile'] = $showProfile;

        $p->setLayoutBoxCss("box box-info");
        $p->setLayoutBoxHeading("My Activity Log");
        $p->setLayoutBoxBody(true);
        //        $elementLabels['rightConten'] = $p->layout_box(print_r($arrActivitylog, true));
        $elementLabels['rightConten'] = $p->layout_box($content);


        // arrPrint($arrActivitylog);
        //region add to content

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {

                $arrTags[$tKey] = $tValue;
            }
        }

        $arrTags["menu_right_isi"] = callMenuRightIsi();
        $arrTags["menu_left"] = callMenuleft();
        $arrTags["stop_time"] = "";
        $arrTags["head_tpl"] = headTpl();
        $arrTags["foot_tpl"] = footTpl();
        $arrTags["isi_modal"] = "";
        $arrTags["float_menu_bawah"] = callFloatMenu();
        $arrTags["menu_taskbar"] = callMenuTaskbar();

        $p->addTags($arrTags);

        // $p->addTags(array(
        //     // "prop_display"          => $propDisplay,
        //     "menu_right_isi"        => callMenuRightIsi(),
        //     "menu_left"             => callMenuLeft(),
        //     "leftProfile"             => $leftProfile,
        //     "rightConten"             => $rightConten,
        //
        //     "stop_time"             => "",
        //     "title"             => $title,
        //     "head_tpl"             => $headTpl,
        //     "foot_tpl"             => $footTpl,
        // ));

        //endregion
        $p->render();
        break;
    case "modal":
        $ly = new Layout();
        $footer = "";
        // arrPrint($forms);
        if (isset($forms)) {
            $ly->setFormGroupLeftClass("col-sm-3 text-right");
            $ly->setFormGroupRightClass("col-sm-8");

            $forms_viewe = "<div class='overflow-h'>";
            if (is_array($forms)) {
            foreach ($forms as $label => $nilai) {
                $forms_viewe .= $ly->form_group($label, $nilai);
            }
            }
            else {
                $forms_viewe .= $forms;
            }
            $forms_viewe .= "</div>";
            if (sizeof($field) > 5) {
                $forms_viewe .= form_hidden("field", "$field");
            }
        }
        else {
            $forms_viewe = "kosong";
        }
        if (isset($notes) && (strlen($notes) > 2)) {
            $forms_viewe .= $notes;
            // $forms_viewe .= "<div class='alert bg-yellow-light no-margin'>****</div>";
        }
        $footer .= form_button('close', 'Close', "class='btn pull-left' data-dismiss='modal'");
        if (isset($heading)) {
            $ly->setLayoutModalHeader("$heading", true);
        }
        $ly->setLayoutModalBody("$forms_viewe");
        $ly->setLayoutModalFooter("$footer");

        $att = array(
            "class"  => "form-horizontal",
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";


        echo $mdl;
        break;
    case "Images":
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
            $p = New Layout("$title", "$subTitle", "application/template/blank.html");
            $attached = true;
        }
        else {
            $p = New Layout("$title", "$subTitle", "application/template/harga.html");
            $attached = false;
        }


        //region tambahan css
        //        $content = "<style>
        //        td {
        //
        //        /* css-3 */
        //                overflow: hidden;
        //        max-width: 100px;
        //        white-space: -o-pre-wrap;
        //        word-wrap: break-word;
        //        white-space: pre-wrap;
        //        white-space: -moz-pre-wrap;
        //
        //}
        //</style>";

        //endregion

        //    $content=$error;

        //        arrPrint($content);

        echo $content;
        die();
        $p->addTags(array(
            //            "menu_left"        => callMenuLeft(),
            //            "trans_menu"       => callTransMenu(),
            //            "btn_back"         => callBackNav(),
            //            "start_page"       => $startPage,
            //            "form_target"      => $formTarget,
            "content" => $content,
            //            "profile_name"     => $this->session->login['nama'],
            //            "self"             => $self,
            //            "default_key"      => $defaultKey,
            //            "error_msg"        => $error,
            //            "submit_btn_label" => $buttonLabel,
            //            "stop_time" => "",

            //                "add_link" => $btn_save,
        ));

        $p->render();
        break;
    case "modalCheck":
        $ly = new Layout();
        $footer = isset($footer) ? $footer : "";
        // arrPrint($forms);
        // arrPrint($menuGroupMember);
        // matiHere();
        if (isset($forms)) {
            // region pilih kategori folder
            $tik_folder = "<div class='alert alert-info'>Pilih group-group untuk diberikan pada menu <b class='text-uppercase'>$heading</b> $trjenis</div>";
            // $tik_folder = "";
            $tik_folder .= "<div class='row funkyradio'>";
            // $tik_folder .= "<div class='funkyradio'>";
            foreach ($forms as $i => $dataTemp) {
                $f_id = $dataTemp->id;
                $f_jenis = $dataTemp->jenis;
                $f_nama = $dataTemp->nama;
                $f_icon = isset($dataTemp->icon) ? $dataTemp->icon : "fa fa-circle";
                $f_label = $dataTemp->label;

                $jmlGroupMember = isset($menuGroupMember[$f_nama]) ? $menuGroupMember[$f_nama] : 0;
                $arrDatas = array(
                    "group_jenis" => $f_jenis,
                    "group_nama"  => $f_nama,
                    "menu_jenis"  => $trjenis,
                    // "author_id"   => my_id(),
                );
                $arrDatas_e = str_replace("=", "", blobEncode($arrDatas));

                $str_checked = in_array($f_nama, $onGroups) ? "checked" : "";

                $tik_folder .= "<div class='col-md-6' style='margin-bottom: 2px;'>";

                $tik_folder .= "<div class='funkyradio-success'>";

                $link_save = $linkSave . "/saveGroup/$arrDatas_e";
                $tik_folder .= "<input type='checkbox' name='folder[]' id='checkbox_$f_id' value='$f_nama' $str_checked onclick=\"btn_result('$link_save');\">";
                $tik_folder .= "<label for='checkbox_$f_id' class='no-margin no-padding text-uppercase'><span class='fa $f_icon' style='margin-left: -40px;'></span> $f_label (<i class='text-lowercase text-red'>$f_nama</i>) <span class='label label-success'>$jmlGroupMember</span></label>";
                $tik_folder .= "</div>";

                $tik_folder .= "</div>";
            }
            // $tik_folder .= "</div>";
            $tik_folder .= "</div>";
            // endregion pilih kategori folder


            $hd = "<tr>";
            foreach ($field as $gNama => $fItems) {

                $hd .= "<th>$gNama</th>";
            }
            $hd .= "</tr>";
            //
            //
            $forms_viewe = "<table class='table'>";
            $forms_viewe .= $hd;
            $forms_viewe .= "</table>";
            $forms_viewe = $tik_folder;
        }
        else {
            $forms_viewe = "kosong";
        }
        if (isset($notes) && (strlen($notes) > 2)) {
            $forms_viewe .= $notes;
            // $forms_viewe .= "<div class='alert bg-yellow-light no-margin'>****</div>";
        }
        $footer .= form_button('close', 'Close', "class='btn pull-left' data-dismiss='modal'");
        if (isset($heading)) {

            $ly->setLayoutModalHeader("<span class='text-uppercase'>$heading</span>", true);
        }
        $ly->setLayoutModalBody("$forms_viewe");
        $ly->setLayoutModalFooter("$footer");

        $att = array(
            "class"  => "form-horizontal",
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";


        echo $mdl;
        break;
    case "barcodeView":

        $p = New Layout("", "", "application/template/modalBarcode.html");

//        $jsBottom .= "
//
//            var table = top.$('#table_editor_number').DataTable({
//                info: false,
//                paging: false,
//                searching: false,
//                ordering: false,
//                bAutoWidth: true,
//                colReorder: true
//            });
//            $( table.table().container() ).removeClass( 'form-inline' );
//            table.on( 'column-reorder', delay_v2( function ( e, settings, details ) {
//                showNumbering();
//            }, 2500) );
//            top.$('.urut_numbering').off();
//            top.$('.urut_numbering').on('keyup', function(){
//                showNumbering();
//            })
//
//            function showNumbering(){
//                var dtime       = $(\"input[name='show_dtime']:checked\").val();
//                var format_dtime     = $(\"input[name='format_dtime']:checked\").val();
//                var arrUrutNumber = $('.urut_numbering');
//                var hasil_number = '';
//
//                top.jQuery.each(arrUrutNumber, function(a, b){
//                    hasil_number += $(b).val();
//                    console.log(b);
//                })
//
//                $('.hasil_sample_numbering').html('<h3>'+hasil_number+'</h3>')
//                console.log(hasil_number);
//            }
//
//            showNumbering();
//
//        ";
        $p->addTags(array(
            "content" => $content,
            "jsBottom" => $jsBottom,
        ));

        $p->render();
        break;
    case "addDiscount":
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data2.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        //region template table
        $template = array(
            'table_open'        => '<table id="table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion

        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                $values = blobDecode($images);
                                $img = base64_encode($values['image']);
                                $imgsrc = "src='data:image/jpeg;base64,$img'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";

                        }
                        //                        cekHijau($key);


                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region histories
        if (sizeof($arrayHistory) > 0) {
            if (sizeof($arrayHistoryLabels) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabels as $key => $label) {

                    $header_Hist_f = array('data' => strtoupper($label), 'class' => 'text-muted');
                    $header_Hist[] = $header_Hist_f;
                }
                $this->table->set_heading($header_Hist);
            }
            foreach ($arrayHistory as $key => $val) {

                if (sizeof($arrayHistoryLabels) > 0) {

                    $isi_data = array();
                    foreach ($arrayHistoryLabels as $key => $label) {
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                $values = blobDecode($images);
                                $img = base64_encode($values['image']);
                                $imgsrc = "src='data:image/jpeg;base64,$img'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";
                        }
                        if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {

                            if (strstr($value, "javascript:void(0)")) {
                                $isExist_href = str_replace("javascript:void(0)", "#", $value);
                            }
                            else {
                                $isExist_href = $value;
                            }


                        }
                        else {
                            $isExist_href = $value;
                        }
                        $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => 'text-left');
                        //                        $isi_data[] = array('data' => $isExist_href, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strActiveDataFooter = "<a class='btn btn-default ' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strActiveDataFooter = "";
        }
        $strActiveData = $this->table->generate();
        //endregion

        //region folders
        $strFolder = "";
        if (sizeof($folders) > 0) {
            if ((isset($_GET['mode']) && $_GET['mode'] == 'print')) {
                $isExist_href = "#";
                $formTarget1 = "#";

            }
            else {
                $isExist_href = "javascript:void(0)";
                $formTarget1 = "$fmdlTarget";
            }
            $addStr = "";
            if ($faddLink != "") {
                $addClick = "BootstrapDialog.show(
                                   {
                                        title:'add folder',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $faddLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                $addStr = "<a href='$isExist_href' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
            }

            $this->table->set_heading(array("<a href='" . $formTarget1 . "'>folder name</a>", $addStr));
            foreach ($folders as $fID => $fName) {
                $isi_data = array();
                $newTargetPage = str_replace("fID", "_f", $thisPage) . "&fID=$fID&fName=$fName";
                $targetHref = isset($_GET['mode']) && $_GET['mode'] == 'print' ? "#" : $newTargetPage;
                $value = "<a href='$targetHref'><span class='fa fa-folder-o'></span> $fName</a>";
                //                $value = "<a href='$newTargetPage'><span class='fa fa-folder-o'></span> $fName*</a>";
                $bgColor = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#e5e5ef" : "transparent";
                $color = isset($_GET['fID']) && $fID == $_GET['fID'] ? "#000000" : "#005689";

                //region manip
                $editClick = "";
                $editStr = "";
                if ($fID > 0 && $feditLink != "") {
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify folder $fName',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $feditLink . $fID . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                    $editStr = "<a href='$isExist_href' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                }
                //endregion

                $isi_data[] = array(
                    'data'    => $value,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $isi_data[] = array(
                    'data'    => $editStr,
                    'class'   => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style"   => "color:$color",
                );
                $this->table->add_row($isi_data);

            }
            $this->table->set_template($foldersTemplate);
            $strFolder = $this->table->generate();
        }
        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {

            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strDataHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $strDataHist = $this->table->generate();
        //endregion


        if (sizeof($arrayOnProgress) > 0) {

            $propDisplay = "block";
        }
        else {

            $propDisplay = "none";
        }

        //region add to content
        $p->addTags(array(
            "prop_display"          => $propDisplay,
            "menu_right_isi"        => callMenuRightIsi(),
            "menu_left"             => callMenuLeft(),
            //                "trans_menu" => callTransMenu(),
            "float_menu_atas"       => callFloatMenu('atas'),
            "float_menu_bawah"      => callFloatMenu(),
            "menu_taskbar"          => callMenuTaskbar(),
            "btn_back"              => callBackNav(),
            "data_propose_title"    => $strDataProposeTitle,
            "data_propose_content"  => $strDataPropose,
            "data_propose_footer"   => $strDataProposeFooter,
            "add_link"              => $strAddLink,
            "edit_link"             => $strEditLink,
            "data_active_title"     => $strActiveDataTitle,
            "data_active_content"   => $strActiveData,
            "data_active_footer"    => $alternateLink,
            "data_hist_title"       => $strDataHistTitle,
            "data_hist_content"     => $strDataHist,
            "data_hist_footer"      => $strDataHistFooter,
            "profile_name"          => $this->session->login['nama'],
            "link_str"              => $linkStr,
            "error_msg"             => $error,
            //                "search_str" => $searchStr,
            "this_page"             => $thisPage,
            "search_str"            => isset($_GET['k']) ? $_GET['k'] : "",
            "folders"               => $strFolder,
            "reg_folders_classname" => sizeof($folders) > 0 ? "col-lg-3" : "col-lg-0",
            "reg_items_classname"   => sizeof($folders) > 0 ? "col-lg-9" : "col-lg-12",
            "stop_time"             => "",
        ));
        //endregion

        $p->render();
        break;
    case "viewRekeningKoran":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = "application/template/tool.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        arrPrint($link);
        $data_total = "";
        if (sizeof($items) > 0) {
            $data_total .= "<div class='panel'>";
            $data_total .= "<div class='table-responsive'>";
            $data_total .= "<table width='100%' class='table table-bordered datatables' id='contoh'>";
            $data_total .= "<thead>";
            $data_total .= "<tr bgcolor='#e5e5e5'>";
            $data_total .= "<th align='left'>No.</th>";
            foreach ($headerFields as $kol => $label) {
                $data_total .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;'>";
                $data_total .= "$label &nbsp;";
                $data_total .= "</th>";
            }
            $data_total .= "</tr>";
            $data_total .= "</thead>";
            $data_total .= "<tbody>";
            $x = 0;
            foreach ($items as $i => $tmpI) {
                $x++;

                $data_total .= "<tr>";
                $data_total .= "<td>$x</td>";
                foreach ($headerFields as $kol => $aliasKey) {
                    $link_tmp = $link[$i] ? $link[$i] : "#";
                    $data_total .= "<td>";
                    $data_total .= "<a href='$link_tmp' data-toggle='tooltip' title='detail ' target='_blank'>" . formatField($kol, $tmpI[$kol]) . "</a>";
                    $data_total .= "</td>";
                    //                    $data_total .="<td>".formatField($kol,$tmpI[$kol])."</td>";
                }
                $data_total .= "</tr>";
                //                arrPrint($tmpI);
            }
            $data_total .= "</tbody>";
            $data_total .= "</table>";
            $data_total .= "</div>";
            $data_total .= "</div>";
        }
        else {
            $data_total .= "";
        }
        //        foreach()
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
                "menu_left"        => callMenuLeft(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "content"          => $data_total,
                "profile_name"     => $this->session->login['nama'],
                "script_bottom"    => $script_bottom,
                "btn_top"          => "",
            )
        );

        //        $p->setContent($contens);
        $p->render();
        break;
    case "data_table":
        // $strhead = "";
        // $strhead .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>";
        // $strhead .= "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' />";
        // $strhead .= "<script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>";
        // $strhead .= "<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>";
        // $strhead .= "<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css' />";

        $mdlName = $mdl;
        $this->load->model("Mdls/" . $mdlName);
        // $fetch_data = $this->$mdlName->make_datatables();
        // $listedFields = $this->$mdlName->getListedFields();
        $listedFields = $this->$mdlName->getListedFields();
        // cekBiru($fetch_data);
        // arrPrintKuning($listedFields);
//         cekBiru($maximumData);
        $tdPaired = "";
        if (method_exists($this->$mdlName, "getPairedData")) {
            // cekOrange(__LINE__);
            $paireds = $this->$mdlName->getPairedData();
            // cekBiru($paireds);
            $pairedKolom = array();
            foreach ($paireds as $mdl_nama => $pairAttr) {
                // cekBiru($pairAttr);
                // $pairedKolom[$pairAttr['kolom']] = $pairAttr['label'];
                if (is_array($pairAttr['kolom'])) {
                    foreach ($pairAttr['kolom'] as $itemKolom => $itemLabel) {
                        $tdPaired .= "<td>" . $itemLabel . "</td>";
                    }
                }
                else {

                    $tdPaired .= "<td>" . $pairAttr['label'] . "</td>";
                }
            }
        }
        else {
            // $pairedKolom = array();
            // cekMerah();
            $tdPaired ="";
        }

        $str = "";
        // $str .= $strhead;
        // $str .= 'ooo'.barcode('123654','JsBarcode');
        $str .= "<table id='user_data' border='1' class='table table-sm compact table-condensed table-striped table-bordered'>";
        $str .= "<thead>";
        $str .= "<tr class='bg-primary text-uppercase'>";
        $str .= "<td style='width: 20px;'>no</td>";
        foreach ($listedFields as $kolom => $label) {
            $str .= "<th>$label</th>";
        }
        $str .= $tdPaired;
        $str .= "<td>actions</td>";
        $str .= "</tr>";
        $str .= "</thead>";
        $str .= "<tbody>";
        $str .= "</tbody>";

//        $str .= "<tfoot>";
//        $str .= "<tr class='bg-primary'>";
//        $str .= "<th style='width: 20px;'>no</th>";
//        foreach ($listedFields as $kolom => $label) {
//            $str .= "<th>$label</th>";
//        }
//        $str .= $tdPaired;
//        $str .= "<th>actions</th>";
//        $str .= "</tr>";
//        $str .= "</tfoot>";

        $str .= "</table>";
        $fId = $filterId;
        $str_fId = $filterId_str;
        // --------------------script untuk memangil ajax data table-----------------------
        $url = base_url() . "numbering/Data/fetch_data/$segment_3".$str_fId;
         // cekHitam("$url");
//         cekHitam("$mdl");
        $str_1 = "<script>
                $(document).ready( function () {
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
                     
                    $('table#user_data thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        var nilai ='';
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter\" type=\"text\" style=\"width: 50px;color:red;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });                     

                     var dataTable = $('#user_data').DataTable({
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
//                                        $('#overlay').remove()
                            $(\".chart\").removeClass(\"loading_2\");
                                },
                            stateLoadCallback: function(settings) {
                                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                            },                     
                            dom: 'lBfrtip',
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: 50,
                            'processing':true,
                            'serverSide':true,
                            'order':[],
                            'stateSave' :true,
//                            ajax: posrurl,
                            'ajax':{
                                url:posrurl,
                                type:'POST',
                                data: {mdl:posmdl,fid:postfid}
                            },
                            'buttons': [
                                'print',
                                {
                                    extend: 'csvHtml5',
                                    text: 'CSV',
                                    exportOptions: {
                                        modifier: {
                                            search: 'none'
                                        },
                                        format: {
                                            body: function ( data, row, column, node ) {
                                                if( /<\/?[a-z][\s\S]*>/i.test(data) ){
                                                    var indexnya = $(data).text().indexOf('>')
                                                    if(indexnya>0){
                                                        var result = $(data).text().substring(indexnya + 1);
                                                        return result;
                                                        //console.error( result );
                                                    }

                                                    return $(data).text()
                                                }
                                                else{   
                                                    return data;
                                                    //console.log(data);
                                                }
                                            }
                                        }
                                    },
                                },
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
                            'columnDefs':[
                                {
                                    'targets':[0],
                                    'orderable':false,
                                },
                            ],

//                            initComplete: function () {
//                                this.api()
//                                .columns()
//                                .every(function () {
//                                    var column = this;
//                                    console.log(column);
//                                    var select = $('<select><option value=\"\"></option></select>')
//                                        .appendTo($(column.footer()).empty())
//                                        .on('change', function () {
//                                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
//                                            column.search(val ? '^' + val + '$' : '', true, false).draw();
//                                        });
//                                    column
//                                        .data()
//                                        .unique()
//                                        .sort()
//                                        .each(function (d, j) {
//                                            select.append('<option value=\"' + d + '\">' + d + '</option>');
//                                        });
//                                });
//                            },

                        });
                                dataTable.on('order.dt search.dt', function () {
                                    let i = 1;                        
                                    dataTable.cells(null, 0, { 
                                        search: 'applied', order: 'applied' 
                                        }).every(function (cell) {
                                            this.data(i++);
                                        });
                                }).draw();

                });
                </script>";
        // -------------------------------------------

        // echo $str;
        // echo $str_1;

        //region template table
        $template = array(
            'table_open'         => '<table id="table" class="table table-condensed">',
            // 'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $mainTemplate = array(
            'table_open'         => '<table id="main_table" class="table table-condensed margin-top-5">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed anu">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="folder_table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion
        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {

                        //                        cekBiru($key);
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                //                                $values = blobDecode($images);
                                //                                $img = base64_encode($values['image']);
                                //                                $imgsrc = "src='data:image/jpeg;base64,$img'";
                                $imgsrc = "src='$images'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif?=v1";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";

                        }
                        //                        cekHijau($key);


                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {

            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strDataHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $this->table->set_template($template);
        $strDataHist = $this->table->generate();
        //endregion
        $prop_display = sizeof($arrayOnProgress) < 1 ? "none" : "block";

        $error_msg = "";
        $sisa_quota_data = $maximumData - $jmlDataNow;
        if(($maximumData > 0) && ($sisa_quota_data < 2)){
            $error_msg .= "<div class='alert alert-warning text-uppercase text-center font-size-1-5' style='padding:5px;'>";
            $error_msg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $error_msg .= "jumlah data yang diijinkan <r class='font-size-1-5'>$maximumData</r>, tersisa <r class='font-size-1-5'>$sisa_quota_data</r> lagi";
            $error_msg .= "</div>";
        }

        if (strlen($error) > 3) {
            $error_msg .= "<div class='alert alert-info'>";
            $error_msg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $error_msg .= $error;
            $error_msg .= "</div>";
        }

        $subTitle = "";
        $title = $title;
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? MODUL_TEMPLATE_PATH . "template/defaultPrint.html" : MODUL_TEMPLATE_PATH . "template/data_table.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        $p->addTags(array(
            "menu_right_isi"       => callMenuRightIsi(),
            "menu_left"            => callMenuLeft(),
            "float_menu_bawah"     => "",
            "error_msg"            => $error_msg,
            // -------------------
            "data_propose_title"   => $strDataProposeTitle,
            "data_propose_content" => $strDataPropose,
            "data_propose_footer"  => "",
            // -------------
            "data_hist_title"      => $strDataHistTitle,
            "data_hist_content"    => $strDataHist,
            "data_hist_footer"     => "",
            // ----data utama---------
            "data_active_content"  => $str,
            "script_bottom"        => $str_1,
            "link_str"             => $linkStr,
            "add_link"             => $add_link,
            "edit_link"            => $edit_link,
            "folder_link"            => $folder_link,
            //---end data utama--------
            "prop_display"         => $prop_display,
            "lebar_modal"       => "modal-lg",
            "isi_modal"            => "content here",
        ));
        $p->render();
        // cekMerah();
        break;
    case "modalView__":
        $ly = new Layout();
        // arrPrint($forms);
        if (isset($forms)) {

            $forms_viewe = "<div class='overflow-h'>";
            $forms_viewe .= "<table class='table table-condensed'>";
            $forms_viewe .= "<tr>";
            foreach ($field as $kolom => $kolomParams) {
                $label = $kolomParams['label'];

                $forms_viewe .= "<th>$label</th>";
                }
            $forms_viewe .= "<t/r>";

                foreach ($forms as $datas) {
                    $forms_viewe .= "<tr>";
                    foreach ($field as $kolom => $kolomParams) {
                        $data = $datas->$kolom;

                        $forms_viewe .= "<td>$data</td>";
                    }
                $forms_viewe .= "</tr>";
            }

            $forms_viewe .= "</table>";
            $forms_viewe .= "</div>";

        }
        else {
            $forms_viewe = "kosong";
        }
        if (isset($notes) && (strlen($notes) > 2)) {
            $forms_viewe .= $notes;
            // $forms_viewe .= "<div class='alert bg-yellow-light no-margin'>****</div>";
        }
        $footer .= form_button('close', 'Close', "class='btn pull-left' data-dismiss='modal'");
        if (isset($heading)) {
            $ly->setLayoutModalHeader("$heading", true);
        }

        $ly->setLayoutModalBody("$forms_viewe");
        $ly->setLayoutModalFooter("$footer");

        $att = array(
            "class"  => "form-horizontal",
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";


        echo $mdl;
        break;

    case "modalView":
        $ly = new Layout();
        // arrPrint($forms);
        if (isset($forms)) {

            $forms_viewe = "<div class='overflow-h'>";
            $forms_viewe .= "<table class='table table-condensed table-striped table-hover-color-red' id='dataTables'>";

            /* --------------------------------------------------------------
             * head
             * --------------------------------------------------------------*/
            $forms_viewe .= "<tr class='text-uppercase'>";
            if(isset($field_nomer)){
                $forms_viewe .= "<th classs='bg-primary'>".$field_nomer['label']."</th>";
                $no = $field_nomer['start'];

            }
            foreach ($field as $kolom => $kolomParams) {

                $label = isset($kolomParams['label']) ? $kolomParams['label'] : $kolom;

                $forms_viewe .= "<th classs='bg-primary'>$label</th>";
            }
            if(isset($field_tambahan)){
                foreach ($field_tambahan as $item => $itemAttr) {

                    $forms_viewe .= "<th classs='bg-primary text-center'>".$itemAttr['label']."</th>";
                }
            }
            $forms_viewe .= "</tr>";

            /* --------------------------------------------------------------
             * body
             * --------------------------------------------------------------*/
            if(sizeof($forms) > 0){
                foreach ($forms as $datas) {
//                    cekKuning($datas);
                    $id = $datas->id;
                    $nama = $datas->nama;
                    $status = $datas->status;
                    $forms_viewe .= "<tr>";
                    if(isset($field_nomer)){
                        $no++;
                        $forms_viewe .= "<td>$no</td>";
                    }
                    foreach ($field as $kolom => $kolomParams) {
                        $data = $datas->$kolom;

                        $forms_viewe .= "<td>$data</td>";
                    }
                    if(isset($field_tambahan)){
                        foreach ($field_tambahan as $item => $itemAttr) {
                            $tipe = $itemAttr['tipe'];
                            $ivalue = $itemAttr['value'];
                            $iclass = $itemAttr['class'];
                            $iattr = isset($itemAttr['attr']) ? $itemAttr['attr'] : "";
                            $ilink = $itemAttr['link']."/$id/$status";

                            switch ($tipe){
                                case "button":
                                    // $item_f = "<button type='$tipe' class='$iclass' onClick=\"delete_confirm('Peringatan!','Data $nama akan diaktifkan','$ilink');\">$ivalue</button>";
                                    $item_f = "<button type='$tipe' class='$iclass' onClick=\"confirm_alert_result('Peringatan!','Data $nama akan diaktifkan','$ilink');\">$ivalue</button>";
                                    break;
                            }

                            $forms_viewe .= "<td class='text-center'>$item_f</td>";
                        }
                    }
                    $forms_viewe .= "</tr>";
                }
            }
            else{
                $forms_viewe .= "<tr>";
                $forms_viewe .= "<td colspan='5' class='text-center text-renggang-10 margin-top-10'>tidak ada data</td>";
                $forms_viewe .= "</tr>";
            }

            $forms_viewe .= "</table>";
            $forms_viewe .= "</div>";

        }
        else {
            $forms_viewe = "kosong";
        }
        if (isset($notes) && (strlen($notes) > 2)) {
            $forms_viewe .= $notes;
            // $forms_viewe .= "<div class='alert bg-yellow-light no-margin'>****</div>";
        }
        $footer = form_button('close', 'Close', "class='btn pull-left' data-dismiss='modal'");
        if (isset($heading)) {
            $ly->setLayoutModalHeader("$heading", true);
        }

        $ly->setLayoutModalBody("$forms_viewe");
        $ly->setLayoutModalFooter("$footer");

        $actions = "";
        $target = "";
        $att = array(
            "class"  => "form-horizontal",
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";
        // $mdl .= "<script>
        //             var thisTable = $('.modal').find('.table');
        //             $(thisTable).dataTable();
        //         </script>";
        // $mdl .= "<script>
        //                 $(document).ready( function(){
        //
        //                     var table = $('#dataTables').dataTable({
        //                         dom: 'lBfrtip',
        //                         fixedHeader: true,
        //                         lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
        //                         pageLength: -1,
        //                         stateSave: true,
        //                         buttons: [
        //                                     { extend: 'print', footer: true },
        //                                 ],
        //
        //                     });
        //
        //                 });
        //
        //                 $('.table-responsive').floatingScroll();
        //
        //                 </script>";


        echo $mdl;
        break;

    case "data_table_subs":
        $mdlName = $mdl;
        $this->load->model("Mdls/" . $mdlName);
        $fetch_data = $this->$mdlName->make_datatables();
        $listedFields = $this->$mdlName->getListedFields();

        $tdPaired = "";
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            $pairedKolom = array();
            foreach ($paireds as $mdl_nama => $pairAttr) {
                if (is_array($pairAttr['kolom'])) {
                    foreach ($pairAttr['kolom'] as $itemKolom => $itemLabel) {
                        $tdPaired .= "<td>" . $itemLabel . "</td>";
                    }
                }
                else {
                    $tdPaired .= "<td>" . $pairAttr['label'] . "</td>";
                }
            }
        }
        else {
            $tdPaired ="";
        }

        $str = "";
        $str .= "<table id='user_data' border='1' class='table table-sm compact table-condensed table-striped table-bordered'>";
        $str .= "<thead>";
        $str .= "<tr class='bg-primary'>";
        $str .= "<td style='width: 20px;'>no</td>";
        foreach ($listedFields as $kolom => $label) {
            $str .= "<td>$label</td>";
        }
        $str .= $tdPaired;
        $str .= "<td>actions</td>";
        $str .= "</tr>";
        $str .= "</thead>";
        $str .= "<tbody>";
        $str .= "</tbody>";

        $str .= "</table>";
        $fId = $filterId;
        $str_fId = $filterId_str;
        // --------------------script untuk memangil ajax data table-----------------------
        $url = base_url() . "data/fetch_data/$segment_3".$str_fId;
        $str_1 = "<script>
                $(document).ready( function () {
                     var posrurl = '$url';
                     var posmdl = '$mdl';
                     var postfid = '$fId';
                     var buttonCommon = {
                        exportOptions: {
                            format: {
                                body: function ( data, row, column, node ) {
                                    var newData = String(data);
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

                     var dataTable = $('#user_data').DataTable({
                            dom: 'lBfrtip',
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: 20,
                            'processing':true,
                            'serverSide':true,
                            'order':[],
                            'ajax':{
                                url:posrurl,
                                type:'POST',
                                data: {mdl:posmdl,fid:postfid}
                            },
                            'buttons': [
//                                $.extend( true, {}, buttonCommon, {
//                                    extend: 'copyHtml5'
//                                } ),
//                                $.extend( true, {}, buttonCommon, {
//                                    extend: 'excelHtml5'
//                                } ),
//                                $.extend( true, {}, buttonCommon, {
//                                    extend: 'pdfHtml5'
//                                } )
                            ],
                            'columnDefs':[
                                {
                                    'targets':[0],
                                    'orderable':false,
                                },
                            ],
                        });


                });
                </script>";
        // -------------------------------------------

        // echo $str;
        // echo $str_1;

        //region template table
        $template = array(
            'table_open'         => '<table id="table" class="table table-condensed">',
            // 'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $mainTemplate = array(
            'table_open'         => '<table id="main_table" class="table table-condensed margin-top-5">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed anu">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="folder_table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion
        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {

                        //                        cekBiru($key);
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                //                                $values = blobDecode($images);
                                //                                $img = base64_encode($values['image']);
                                //                                $imgsrc = "src='data:image/jpeg;base64,$img'";
                                $imgsrc = "src='$images'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif?=v1";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";

                        }
                        //                        cekHijau($key);


                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {

            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strDataHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $this->table->set_template($template);
        $strDataHist = $this->table->generate();
        //endregion
        $prop_display = sizeof($arrayOnProgress) < 1 ? "none" : "block";

        $error_msg = "";
        if (strlen($error) > 3) {
            $error_msg .= "<div class='alert alert-info'>";
            $error_msg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $error_msg .= $error;
            $error_msg .= "</div>";
        }

        $subTitle = "";
        $title = $title;
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data_table.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        $p->addTags(array(
            "menu_right_isi"       => callMenuRightIsi(),
            "menu_left"            => callMenuLeft(),
            "float_menu_bawah"     => "",
            "error_msg"            => $error_msg,
            // -------------------
            "data_propose_title"   => $strDataProposeTitle,
            "data_propose_content" => $strDataPropose,
            "data_propose_footer"  => "",
            // -------------
            "data_hist_title"      => $strDataHistTitle,
            "data_hist_content"    => $strDataHist,
            "data_hist_footer"     => "",
            // ----data utama---------
            "data_active_content"  => $str,
            "script_bottom"        => $str_1,
            "link_str"             => $linkStr,
            "add_link"             => $add_link,
            "edit_link"            => $edit_link,
            "folder_link"            => $folder_link,
            //---end data utama--------
            "prop_display"         => $prop_display,
            "lebar_modal"       => "modal-lg",
        ));
        $p->render();
        // cekMerah();
        break;

    case "data_table_pos":

        $dateSelected = date('Y-m-d');

        $mdlName = $mdl;
        $this->load->model("Mdls/" . $mdlName);
        $this->db->where("link_id='0' and fulldate = '$dateSelected'"); //show today
//        $this->db->where("link_id='0' and fulldate = '".date('Y-m-d', strtotime('-1 days'))."'"); //untuk show maju mundur tgl
        $fetch_data = $this->$mdlName->make_datatables();
        // cekHitam($this->db->last_query());
        $listedFields = $this->$mdlName->getListedFields();

//        arrPrint($listedFields);

        $series = array();
        $mdlNameData = $mdl."DataSum";
        $this->load->model("Mdls/" . $mdlNameData);
//        $this->db->where("link_id='0' and fulldate = '".date('Y-m-d')."' and jenis_label !='settlement'");
        $fetch_tr_data = $this->$mdlNameData->make_datatables();
        // cekBiru($this->db->last_query());
        $listedFieldsData = $this->$mdlNameData->getListedFields();

        $tdPaired = "";
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            $pairedKolom = array();
            foreach ($paireds as $mdl_nama => $pairAttr) {
                if (is_array($pairAttr['kolom'])) {
                    foreach ($pairAttr['kolom'] as $itemKolom => $itemLabel) {
                        $tdPaired .= "<td>" . $itemLabel . "</td>";
                    }
                }
                else {
                    $tdPaired .= "<td>" . $pairAttr['label'] . "</td>";
                }
            }
        }
        else {
            $tdPaired ="";
        }

        $byOlehName=array();
        $total_jual=0;
        $total_return=0;
        $series = array();
        $nota_series = array();
        $tmpNota = array();
        asort($fetch_data);
        if(!empty($fetch_data)){
            foreach($fetch_data as $kk => $dt){
                $byOlehName[$dt->oleh_nama][] = $dt;
                $total_jual += $dt->transaksi_nilai;
                $total_return += $dt->transaksi_dibayar_return;
                if(!isset($series[date("Y-m-d H:00", strtotime($dt->dtime))])){
                    $series[date("Y-m-d H:00", strtotime($dt->dtime))] = 0;
                }
                $series[date("Y-m-d H:00", strtotime($dt->dtime))] += $dt->transaksi_nilai;
                $tmpNota[date("Y-m-d H:00", strtotime($dt->dtime))][] = $dt->nomer;
                $nota_series[date("Y-m-d H:00", strtotime($dt->dtime))] = count($tmpNota[date("Y-m-d H:00", strtotime($dt->dtime))]);
            }
        }

        $countAllTr = count($fetch_data);

        $str = "";
        $str .= "";

//        $str .= "<div class='box'>";
//        $str .= "<div class='box-body'>";

//        $str .= "<div class='alert bg-warning hiddenx'>";
//        $str .= "<div class='hidden' id='total_nota'>$countAllTr</div>";
//        $str .= "<div class='hidden' id='total_jual'>$total_jual</div>";
//        $str .= "<div class='hidden' id='total_return'>$total_return</div>";
//        $str .= "<p style='font-size: 18px;' class='text-bold text-blue hidden' id='span_jual'>Penjualan: Rp 0</p>";
//        $str .= "<p style='font-size: 18px;' class='text-bold text-blue hidden' id='span_return'>Pembatalan: Rp 0</p>";
//        $str .= "<p class='' id='container_table'></p>";
//        $str .= "</div>";

        //summary

        $str .= "<table id='summary_data' class='table display compact'>";
        $str .= "<caption class='text-bold '><div class='fa-2x text-red'><i class='fa fa-calculator'></i> SUMMARY PRODUK  (ALL KASIR) <span id='tmp_sum_dtime'></span></div><small><r><i>Total dari Summary Produk mungkin lebih besar dari Per Nota, karena Summary Produk belum mempertimbangkan Diskon Produk.</i></r></small></caption>";
        $str .= "<thead>";
        $str .= "<tr class='bg-primary'>";
        $str .= "<th style='width: 20px;'>no</th>";
        foreach ($listedFieldsData as $kolom => $label) {
            $str .= "<th>$label</th>";
        }
        $str .= $tdPaired;
        $str .= "</tr>";
        $str .= "</thead>";
        $str .= "<tbody>";
        $str .= "</tbody>";

        $str .= "<tfoot>";
        $str .= "<tr class='bg-primary'>";
        $str .= "<th style='width: 20px;'>no</th>";
        foreach ($listedFieldsData as $kolom => $label) {
            $str .= "<th>$label</th>";
        }
        $str .= $tdPaired;
        $str .= "</tr>";
        $str .= "</tfoot>";
        $str .= "</table>";

        //summary

        $str .= "<div style='margin-top: 50px;' class=''>&nbsp;";
        //==============PEMISAH TABLE==============
        $str .= "</div>";

        //pernota
        $str .= "<table id='user_data' class='table display compact'>";
//        $str .= "<caption class='fa-2x text-bold text-red'><i class='fa fa-sticky-note'></i> PENJUALAN PERNOTA</caption>";
        $str .= "<caption class='text-bold '><div class='fa-2x text-red'><i class='fa fa-sticky-note'></i> PENJUALAN PERNOTA <span id='tmp_nota_dtime'></span></div><small><r><i>Nota teratas pada sorting default adalah nota terbaru.</i></r></small>";

        $str .= "<ul id='menu_kasir' style='border-bottom: 0px solid #fff !important;' class='nav nav-tabs'>";
        $str .= "</ul>";

        $str .= "</caption>";
        $str .= "<thead>";
        $str .= "<tr class='bg-primary'>";
        $str .= "<th style='width: 20px;'>no</th>";
        foreach ($listedFields as $kolom => $label) {
            $str .= "<th>$label</th>";
        }
        $str .= $tdPaired;
        $str .= "</tr>";
        $str .= "</thead>";
        $str .= "<tbody>";
        $str .= "</tbody>";

        $str .= "<tfoot>";
        $str .= "<tr class='bg-primary'>";
        $str .= "<th style='width: 20px;'>no</th>";
        foreach ($listedFields as $kolom => $label) {
            $str .= "<th>$label</th>";
        }
        $str .= $tdPaired;
        $str .= "</tr>";
        $str .= "</tfoot>";
        $str .= "</table>";

        $str .= "<div style='margin-top: 50px;' class=''>&nbsp;";
        //==============PEMISAH TABLE==============
        $str .= "</div>";

        $str .= "
        <figure class='highcharts-figure'>
            <div id='container'></div>
            <p class='highcharts-description'>*Notes: -    </p>
        </figure>";

        $fId = $filterId;
        $str_fId = $filterId_str;
        // --------------------script untuk memangil ajax data table-----------------------
        $url = base_url() . "RealTimePos/fetch_data/$segment_3".$str_fId;
        $urlsum = base_url() . "RealTimePos/fetch_data/$segment_3"."DataSum";
        $urlchart = base_url() . "RealTimePos/fetch_data_chart/$segment_3?mdl=MdlRealTimePos";

        $str_1 = "<script>

                function preview_transaksi(nomer){
                    var arrSplit = nomer.split('-');
                    var jenis = arrSplit[0];
                    var text_label = jenis=='582' ? 'PREVIEW PENJUALAN POS' : 'PREVIEW PEMBATALAN PENJUALAN'
                    BootstrapDialog.closeAll();
                    BootstrapDialog.show({
                        message: $('<div> <div class=\'text-center text-bold\'><i class=\'fa fa-refresh fa-3x fa-spin\'></i> <br> Memuat Data POS</div> </div>').load('".base_url()."penjualan/Transaksi/viewResumePos/'+jenis+'/'+nomer),
                        title: text_label,
                        size: BootstrapDialog.SIZE_WIDE,
                        closable: true,
                        animate: false,
                        closeByBackdrop: false,
                        closeByKeyboard: false,
                        draggable: true,
                        buttons: [
                            {
                                label: 'TUTUP',
                                cssClass: 'btn-danger',
                                action: function(dialogRef){
                                    dialogRef.close();
                                }
                            }
                        ],
                        onshown: function(dialog) {

                        }
                    })
                }

                let chart;
                async function requestData() {
                    const result = await fetch('$urlchart');
                      if (result.ok) {
                            const data = await result.json();
                            chart.series[0].update({
                                data: data.series_nilai,
                                pointStart: Date.UTC((data.sp_thn*1), (data.sp_bln*1), (data.sp_day*1), (data.sp_jam*1), 0, 0)
                            }, true);
                            chart.series[1].update({
                                data: data.struk_series,
                                pointStart: Date.UTC((data.sp_thn*1), (data.sp_bln*1), (data.sp_day*1), (data.sp_jam*1), 0, 0)
                            }, true);
                      }
                }

                window.addEventListener('load', function () {
                    chart = new Highcharts.Chart({
                        chart: {
                            type: 'spline',
                            style: {
                                color: 'red',
                                fontSize:'15px'
                            },
                            renderTo: 'container',
                            scrollablePlotArea: {
                                minWidth: 600,
                                scrollPositionX: 1
                            },
                            events: {
                                load: requestData
                            }
                        },
                        title: {
                            text: 'Penjualan',
                            align: 'left',
                            style: {
                                color: 'red',
                                fontSize:'15px'
                            }
                        },
                        subtitle: {
                            text: 'Timeline Penjualan POS Per 1 Jam',
                            align: 'left',
                                style: {
                                    color: 'red',
                                    fontSize:'15px'
                                }
                        },
                        xAxis: {
                            type: 'datetime',
                            labels: {
                                overflow: 'justify',
                                style: {
                                    color: 'red',
                                    fontSize:'15px'
                                }
                            }
                        },
                        tooltip: {
                            valueSuffix: '',
                            style: {
                                color: 'red',
                                fontSize:'15px'
                            }
                        },
                        plotOptions: {
                            spline: {
                                lineWidth: 4,
                                states: {
                                    hover: {
                                        lineWidth: 5
                                    }
                                },
                                marker: {
                                    enabled: true
                                },
                                pointInterval: 3600000, // one hour
                                pointStart: 0,
                                style: {
                                    color: 'red',
                                    fontSize:'15px'
                                }
                            }
                        },
                        series: [{ name: 'Penjualan (Rp)', data: [] },{ name: 'Jumlah Nota', data: [] }],
                        navigation: {
                            menuItemStyle: {
                                fontSize: '14px'
                            }
                        }
                    });
                });

                $(document).ready( function () {

                     var posrurl = '$url';
                     var posmdl = '$mdl';

                     var posrurlsum = '$urlsum';
                     var posmdlsum = '$mdlNameData';
                     var postfid = '$fId';

                     var selectuser = '';
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

                    var dataTableSum = $('#summary_data').DataTable({
                        dom: 'lBfrtip',
                        lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                        scrollY: '450px',
                        scrollCollapse: true,
                        searchDelay: 500,
                        paging: false,
                        processing:true,
                        serverSide:true,
                        order:[[4, 'desc']],
                        ajax:{
                            url:posrurlsum,
                            type:'POST',
                            data: {mdl:posmdlsum,user:selectuser}
                        },
                        buttons: [
                            $.extend(true,{},buttonCommon,{
                                extend: 'copyHtml5'
                            }),
                            $.extend(true,{},buttonCommon,{
                                extend: 'excelHtml5'
                            }),
                            $.extend(true,{},buttonCommon,{
                                extend: 'pdfHtml5'
                            })
                        ],
                        columnDefs:[
                            {
                                'targets':[0],
                                'orderable':false,
                            },
                            {
                                'targets':[1],
                                'visible': false,
                                'orderable':true,
                            },
                            {
                                'targets':[2],
//                                'visible': false,
                                'orderable':true,
                            },
                            {
                                'targets':[3],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(data) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                            {
                                'targets':[4],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(data) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                            {
                                'targets':[5],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(Math.round(data)) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                            {
                                'targets':[6],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas( Math.round(data) ) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                            {
                                'targets':[7],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(Math.round(data)) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                        ],
                        rowCallback: function( row, data, index ) {
//                          if ( data[4] == '-' ) {
//                            $(row).addClass('bg-yellow');
//                          }
                        },
                        footerCallback: function (row, data, start, end, display) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ? i.replace(/[\$,-]/g, '') * 1 : typeof i === 'number' ? i : 0;
                            };

                            var dtime = api
                                .column(1)
                                .data()
                                .reduce(function (a, b) {
                                    return b;
                                }, 0);

                            total = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pageTotal = api
                                .column(5, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            $(api.column(5).footer()).html(\"<div class='text-right'>\" + addCommas( Math.round(pageTotal) ) + '</div>' );

                            total = api
                                .column(6)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pageTotal = api
                                .column(6, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            $(api.column(6).footer()).html(\"<div class='text-right'>\" + addCommas( Math.round(pageTotal) ) + '</div>' );

                            total = api
                                .column(7)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            pageTotal = api
                                .column(7, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            $(api.column(7).footer()).html(\"<div class='text-right'>\" + addCommas( Math.round(pageTotal) ) + '</div>' );

                        },

                    });

                    var dataTable = $('#user_data').DataTable({
                        dom: 'lBfrtip',
//                        lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
//                        pageLength: 10,
                        scrollY: '450px',
                        scrollCollapse: true,
                        paging: false,
                        searchDelay: 500,
                        processing:true,
                        serverSide:true,
                        order:[[1, 'desc']],
                        ajax:{
                            url:posrurl,
                            type:'POST',
                            data: {mdl:posmdl,fid:postfid,user:selectuser}
                        },
                        buttons: [
                            $.extend(true,{},buttonCommon,{
                                extend: 'copyHtml5'
                            }),
                            $.extend(true,{},buttonCommon,{
                                extend: 'excelHtml5'
                            }),
                            $.extend(true,{},buttonCommon,{
                                extend: 'pdfHtml5'
                            })
                        ],
                        columnDefs:[
                            {
                                'targets':[0],
                                'orderable':false,
                            },
                            {
                                'targets':[1],
                                'visible': true,
                                'orderable':true,
                            },
                            {
                                'targets':[2],
                                'orderable':true,
                                'render': function ( data, type, row, meta ) {
                                    return '<div style=\"cursor: pointer;\" class=\"text-blue text-bold\">'+data+'</div>';
                                }
                            },
                            {
                                'targets':[6],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(data) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                            {
                                'targets':[7],
                                'render': function ( data, type, row, meta ) {
                                    return data*1> 0 ? '<div class=\"text-right\">' + addCommas(data) + '</div>' : '<div class=\"text-right\">0</div>';
                                }
                            },
                        ],
                        rowCallback: function( row, data, index ) {
                             if ( $(data[5]).selector == '-' ) {
                                   $(row).addClass('bg-orange');
                             }
                        },
                        footerCallback: function (row, data, start, end, display) {

                            var api = this.api(), data;
                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ? i.replace(/[\$,-]/g, '') * 1 : typeof i === 'number' ? i : 0;
                            };
                            var dtime = 0;
                            setTimeout(function(){
                                dtime = api
                                .column(1)
                                .data()
                                .reduce(function (a, b) {
                                    return b;
                                }, 0);
                                if(dtime!=0){
                                    dtime_f = moment(dtime.split(' ')[0]).format('D/MMMM/YYYY');
                                    $('#tmp_nota_dtime').html('( Tgl ' + dtime_f + ' )');
                                    $('#tmp_sum_dtime').html('( Tgl ' + dtime_f + ' )');
                                    console.log('apply: tgl ' + dtime_f);
                                    localStorage.tglPantau = dtime.split(' ')[0];
                                }
                            }, 1000)

                            arr = {}
                            var kasir = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    if(!arr[b]){
                                        arr[b] = 0
                                    }
                                    arr[b] += 1
                                    return JSON.stringify(arr);
                                }, 0);

                            var ls_kasir = typeof localStorage.ls_kasir != 'undefined' ? JSON.parse(localStorage.ls_kasir) : {}
                            var kasir_select = typeof localStorage.kasir_select != 'undefined' ? localStorage.kasir_select : 'all'
                            var last_totalKasir = typeof localStorage.last_totalKasir != 'undefined' ? localStorage.last_totalKasir : 0

                            var kasir_ori = kasir;

                            if( end > last_totalKasir ){
                                localStorage.ls_kasir = kasir;
                            }
                            else{
                                kasir = localStorage.ls_kasir
                            }

                            arrKasir = JSON.parse(kasir)
                            totalKasir = 0
                            kasir_html = ''

                            jQuery.each(arrKasir, function(a, b){
                                ids = btoa(a).replaceAll('=', '');
                                terselect = ids==kasir_select ? 'btn-warning' : 'btn-info';
                                kasir_html += \"<li class='nav-item'>\"
                                kasir_html += \"<span id='\"+ids+\"' class='btn_nama text-capitalize nav-link btn btn-md btn-flat \"+terselect+\"'>\"
                                kasir_html += \"<span class='fa fa-user'></span> \" + a
                                kasir_html += \"&nbsp; <span class='badge bg-red'>\"+b+\"</span>\"
                                kasir_html += \"</span>\"
                                kasir_html += \"</li>\"
                                totalKasir += b
                            })

                            localStorage.last_totalKasir = totalKasir
                            allSelected = kasir_select=='YWxs' ? 'btn-warning' : 'btn-info'

                            kasir_html += \"<li class='nav-item'>\"
                            kasir_html += \"<span id='all' class='btn_nama text-capitalize nav-link btn btn-md btn-flat  \"+allSelected+\"'>\"
                            kasir_html += \"<span class='fa fa-user'></span> All Kasir\"
                            kasir_html += \"&nbsp; <span class='badge bg-red'>\"+totalKasir+\"</span>\"
                            kasir_html += \"</span>\"
                            kasir_html += \"</li>\"

                            $('#menu_kasir').html(kasir_html);

                            setTimeout( function(){
                                var nilai_totalKasir_ui = $('#user_data>tbody>tr').length;
                                var nilai_totalBtnBadge = $('#all>span.badge').html()*1;
                                var arrKasirLive = JSON.parse(kasir_ori);
                                var totalLive = 0
                                var jmlKasirLive = countObj(arrKasirLive)
                                jQuery.each(arrKasirLive, function(a,b){
                                    totalLive += b
                                })
                                if(jmlKasirLive!=1 && totalLive!=nilai_totalBtnBadge){
                                    localStorage.ls_kasir = kasir_ori;
                                    window.location.reload()
                                }
                            }, 5000)

                            top.$('.btn_nama').on('click', function(){
                                console.log('btn kasir di klik');
                                var ids = $(this).attr('id');
                                ids = ids!='all' ? atob(ids) : ids
                                $('#span_nama').html(ids);
                                if('$str_fId'==''){
                                    dataTable.ajax.url(posrurl+'?kasir_filter='+ids).load();
                                }
                                else{
                                    dataTable.ajax.url(posrurl+'&kasir_filter='+ids).load();
                                }
                                localStorage.kasir_select = btoa(ids).replaceAll('=', '')
                                console.log('localStorage.kasir_select: ', localStorage.kasir_select);
                            })

                            total = api
                            .column(6)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            pageTotal = api
                            .column(6, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            $(api.column(6).footer()).html(\"<div class='text-right'>\" + addCommas(pageTotal) + '</div>' );

                            totalreturn = api
                                .column(7)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            pageTotalreturn = api
                                .column(7, { page: 'current' })
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            $(api.column(7).footer()).html(\"<div class='text-right'>\" + addCommas(pageTotalreturn) + '</div>' );

                            $('.click_invoice').off('click');
                            $('.click_invoice').on('click', function(){
                                var nomer = $(this).attr('nomer');
                            });
                        },
                    });

                    function selectDefaultKasir(){
                        if(typeof localStorage.kasir_select != 'undefined' && localStorage.kasir_select != 'all'){
                            var mustSelectId = localStorage.kasir_select;
                            if( $('.btn_nama#'+mustSelectId) && $('#tmp_nota_dtime').html() != '' ){
                                $('.btn_nama#'+mustSelectId).click()
                                console.log('1#mustSelectId: ', mustSelectId);
                            }
                            else{
                                setTimeout( function(){ selectDefaultKasir() } , 1000 );
                                console.log('1000 timeout 2#mustSelectId: ', mustSelectId);
                            }
                        }
                    }

                    selectDefaultKasir()

                    top.$('#user_data').on('click', 'tbody td', function(e){
                        e.stopPropagation();
                        var rowIdx = dataTable.cell(this).index().row;
                        var data = dataTable.row(rowIdx).data();
                            preview_transaksi(data[2]);
                    });

                    top.$('#btn_sinkron_pos').addClass('hidden');
                    top.$('#btn_sinkron_pos').on('click', function(){
                        swal('sedang melakukan sinkronisasi data<br>mohon tunggu...');
                        swal.enableLoading();
                        var link = $(this).attr('link')
                        $('#result').load(link, function(){
                            dataTable.ajax.reload();
                            dataTableSum.ajax.reload();
                            swal('BERHASIL', 'Sinkronisasi Berhasil', 'success');
                            swal.enableLoading();
                            setTimeout( function(){ swal.close() },2000)
                        })
                    })

                    top.$('#btn_auto_sync').on('click', function(){
                        if( $(this).hasClass('active_sync') ){
                            //off kan karena posisi sudah aktif
                            $(this).removeClass('active_sync')
                            $('i', $(this)).addClass('fa-square').removeClass('fa-check-square text-red text-bold');
                            $(this).addClass('btn-default').removeClass('btn-success');
                            localStorage.auto_sync = 'off'
                        }
                        else{
                            //on kan karena posisi sekarang off
                            $(this).addClass('active_sync')
                            $('i', $(this)).addClass('fa-check-square text-red text-bold').removeClass('fa-square');
                            $(this).addClass('btn-success').removeClass('btn-default');
                            localStorage.auto_sync = 'on'
                        }
                    })

                    localStorage.auto_sync = 'on'

                    var autoSycn=null
                    function initSync(){
                        var setTimingSync = 240000;
                        if( localStorage.auto_sync == 'on' ){
                            if( $('#btn_auto_sync').hasClass('active_sync') ){

                            }
                            else{
                                $('#btn_auto_sync').trigger('click')
                            }
                        }
                        autoSycn = setInterval( function(){
                            if( $('#btn_auto_sync').hasClass('active_sync') ){
                                setTimeout( function() { doSync() },400);
                            }
                        }, setTimingSync)
                    }

                    localStorage.brp_sync = 0
                    var doSync = function(){
                        console.log('jalan dari doSync()');
                        if(localStorage.brp_sync*1<1){
                            clearInterval(autoSycn);
                            setTimeout( function(){ dataTable.ajax.reload() }, 400); //dataTable kasir
                            setTimeout( function(){ dataTableSum.ajax.reload() }, 800); //dataTable Sum Produk
                            setTimeout( function(){ requestData() }, 1200); //Chart
                            setTimeout( function(){ initSync(); }, 1400);
                            setTimeout( function(){ localStorage.brp_sync = ((localStorage.brp_sync*1)+1) }, 1400);
                        }
                        else{
                            window.location.reload()
                        }
                    }

                    initSync();

                    function cobaDeh(){
                        dataTable
                        .column( 4 )
                        .data()
                        .filter( function ( value, index ) {
                            return value > 20 ? true : false;
                        } );
                    }
                });

                setTimeout( function(){
                    window.location.reload();
                }, 310000)

            </script>";
        // -------------------------------------------

        // echo $str;
        // echo $str_1;

        //region template table
        $template = array(
            'table_open'         => '<table id="table" class="table table-condensed">',
            // 'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $mainTemplate = array(
            'table_open'         => '<table id="main_table" class="table table-condensed margin-top-5">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',

            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table table-bordered table-condensed anu">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $foldersTemplate = array(
            'table_open'        => '<table id="folder_table" class="table table-condensed">',
            'thead_open'        => '<thead class="text text-muted text-capitalize" style="background:#f0f0f0;">',
            'thead_close'       => '</thead>',
            'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'  => '<tr>',
            'footer_row_end'    => '</tr>',
            'footer_cell_start' => '<th>',
            'footer_cell_end'   => '</th>',
            'tfoot_close'       => '</tfoot>',
            'table_close'       => '</table>',
        );
        $this->table->set_template($template);
        //endregion
        //region onprogress

        if (sizeof($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => '');
                    $header_prog[] = $header_result_f;
                }
                $this->table->set_heading($header_prog);
            }
            foreach ($arrayOnProgress as $key => $val) {

                if (sizeof($arrayProgressLabels) > 0) {
                    $isi = array();
                    foreach ($arrayProgressLabels as $key => $label) {

                        //                        cekBiru($key);
                        if ($key == "image") {
                            $images = isset($val[$key]) ? $val[$key] : "";
                            if (strlen($images) > 0) {
                                //                                $values = blobDecode($images);
                                //                                $img = base64_encode($values['image']);
                                //                                $imgsrc = "src='data:image/jpeg;base64,$img'";
                                $imgsrc = "src='$images'";

                            }
                            else {
                                $imageAvail = base_url() . "public/images/img_blank.gif?=v1";
                                $imgsrc = "src='$imageAvail'";
                            }

                            $value = "<div class='thumbnail'><img $imgsrc' class='img-responsive' width='150px'></div>";
                        }
                        else {
                            $value = isset($val[$key]) ? $val[$key] : "";

                        }
                        //                        cekHijau($key);


                        $isi[] = array('data' => "$value ", 'class' => 'text-left');
                    }
                    $this->table->add_row($isi);
                }
            }

            $strDataProposeFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {

            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => '');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }

            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
            }

            $strDataHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $this->table->set_template($template);
        $strDataHist = $this->table->generate();
        //endregion
        $prop_display = sizeof($arrayOnProgress) < 1 ? "none" : "block";

        $error_msg = "";
        if (strlen($error) > 3) {
            $error_msg .= "<div class='alert alert-info'>";
            $error_msg .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $error_msg .= $error;
            $error_msg .= "</div>";
        }

        $subTitle = "";
        $title = $title;
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data_table_pos.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        $p->addTags(array(
            "menu_right_isi"       => callMenuRightIsi(),
            "menu_left"            => callMenuLeft(),
            "float_menu_bawah"     => "",
            "error_msg"            => $error_msg,
            // -------------------
            "data_propose_title"   => $strDataProposeTitle,
            "data_propose_content" => $strDataPropose,
            "data_propose_footer"  => "",
            // -------------
            "data_hist_title"      => $strDataHistTitle,
            "data_hist_content"    => $strDataHist,
            "data_hist_footer"     => "",
            // ----data utama---------
            "data_active_content"  => $str,
            "script_bottom"        => $str_1,
            "link_str"             => $linkStr,
            "add_link"             => $add_link,
            "edit_link"            => $edit_link,
            "folder_link"            => $folder_link,
            //---end data utama--------
            "prop_display"         => $prop_display,
            "lebar_modal"       => "modal-lg",
        ));
        $p->render();
        // cekMerah();
        break;
}