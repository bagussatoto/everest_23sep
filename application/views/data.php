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
        mati_disini("mode belum ditentukan");
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
        $topNotif = isset($topNotifLimit) ? $topNotifLimit : "";
        //arrPrint($topNotif);
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/data.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        //region template table
        $template = array(
            'table_open'         => '<table id="table_propose" class="table display compact">',
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
            'table_open'         => '<table id="table_active" class="table display compact">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',
            'tfoot_open'         => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'   => '<tr>',
            'footer_row_end'     => '</tr>',
            'footer_cell_start'  => '<th>',
            'footer_cell_end'    => '</th>',
            'tfoot_close'        => '</tfoot>',
            'table_close'        => '</table>',
        );
        $historyTemplate = array(
            'table_open'         => '<table id="table_history" class="table display compact">',
            'thead_open'         => '<thead>',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr class="bg-grey-2">',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th>',
            'heading_cell_end'   => '</th>',
            'tfoot_open'         => '<tfoot class="ui-widget-header ui-priority-secondary">',
            'footer_row_start'   => '<tr>',
            'footer_row_end'     => '</tr>',
            'footer_cell_start'  => '<th>',
            'footer_cell_end'    => '</th>',
            'tfoot_close'        => '</tfoot>',
            'table_close'        => '</table>',
        );
        $itemsTemplate = array(
            'table_open'        => '<table id="table" class="table display compact">',
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
            'table_open'        => '<table id="folder_table" class="table display compact">',
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
        $notifLimit = "";
        if (strlen($topNotif) > 10) {
            $notifLimit .= "<div class='bg-red text-center alert'><span><h4>$topNotif</h4></span></div>";
        }

        //region onprogress

        if (count($arrayOnProgress) > 0) {
            if (sizeof($arrayProgressLabels) > 0) {
                $header_prog = array();
                foreach ($arrayProgressLabels as $key => $label) {
                    $header_result_f = array('data' => $label, 'class' => 'ini');
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

            $strDataProposeFooter = "<a class='btn btn-sm btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry- ini kah',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        //region histories
        if (count($arrayHistory) > 0) {
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
                        //                        cekHere(":: $key ::");
                        if ($key == "nilai") {
                            if (is_numeric($isExist_href)) {
                                $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => 'text-left');
                            }
                            else {
                                $isi_data[] = array('data' => nl2br($isExist_href));
                            }
                        }
                        else {
                            $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => 'text-left');
                        }

                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strActiveDataFooter = "<a class='btn btn-sm btn-default ' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
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
        if (count($arrayRecap) > 0) {
            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                $header_recap[] = array('data' => 'No', 'class' => 'line');
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => 'line');
                    $header_recap[] = $header_recap_f;

                }

                $this->table->set_heading($header_recap);

            }
            $no = 1;
            foreach ($arrayRecap as $key => $val) {
                if (sizeof($arrayRecapLabels) > 0) {
                    $isi_history = array();
                    $isi_history[] = array('data' => $no, 'class' => 'numbering');
                    foreach ($arrayRecapLabels as $key => $label) {
                        $value = isset($val[$key]) ? $val[$key] : "";
                        $isi_history[] = array('data' => $value, 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_history);
                }
                $no++;
            }

            $strDataHistFooter = "<a class='btn btn-sm btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewRecap/" . "'><span class='glyphicon glyphicon-time'></span> complete $title reports ...</a>";
        }
        else {

            $this->table->add_row(array(
                'data'    => '-the item you specified has no entry-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class'   => 'text-center',
            ));
            $strDataHistFooter = "";
        }
        $this->table->set_template($historyTemplate);
        $strDataHist = $this->table->generate();
        //endregion


        if (sizeof($arrayOnProgress) > 0) {

            $propDisplay = "block";
        }
        else {

            $propDisplay = "none";
        }

        $p->addTags(array(
            "prop_display"          => $propDisplay,
            "menu_right_isi"        => callMenuRightIsi(),
            "menu_left"             => callMenuLeft(),
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
            "data_nonactive_footer" => isset($nonaktifLink) ? $nonaktifLink : "",
            "data_hist_title"       => $strDataHistTitle,
            "data_hist_content"     => $strDataHist,
            "data_hist_footer"      => $strDataHistFooter,
            "profile_name"          => $this->session->login['nama'],
            "link_str"              => $linkStr,
            "error_msg"             => $error,
            "this_page"             => $thisPage,
            "search_str"            => isset($_GET['k']) ? $_GET['k'] : "",
            "folders"               => $strFolder,
            "reg_folders_classname" => sizeof($folders) > 0 ? "col-lg-3" : "col-lg-0",
            "reg_items_classname"   => sizeof($folders) > 0 ? "col-lg-9" : "col-lg-12",
            "stop_time"             => "",
            "menu_depresiasi"       => "",
            "top_limited"           => "$notifLimit",
        ));
        $p->render();

        break;
    case "viewHistories":
        $p = new Layout("$title", "$subTitle", "application/template/data_history.html");

        if (isset($stepLabels) && sizeof($stepLabels) > 0) {
            $seleted_data = "<ul class='pager'>";
            foreach ($stepLabels as $step => $label) {
                $btnClass = "btn-default";
                $seleted_data .= "<li>";
                $seleted_data .= "<button class='btn $btnClass' onclick =\"location.href='" . $stepLinks[$label] . "';\">$step</button>";
                $seleted_data .= "</li>";
            }
            $seleted_data .= "</ul class='pager'>";
        }
        $content = "";
        $content .= "<div class='box box-solid box-danger'>";
        $content .= "<div class='box-header'>";
        $content .= "<span class='glyphicon glyphicon-flash'></span> $subTitle ";
        $content .= "</div>";
        $content .= "<div class='box-body'>";
        $content .= "<div class='table-responsive'>";
        $content .= "<table class='table table-condensed table-bordered no-padding'>";
        if (sizeof($header) > 0) {
            $content .= "<tr>";
            foreach ($header as $key => $label) {
                $content .= "<th class='text-muted'>";
                $content .= $label;
                $content .= "</th>";
            }
            $content .= "</tr>";
        }
        $rowCtr = 0;
        if (sizeof($items) > 0) {
            foreach ($items as $items_0) {
                $content .= "<tr>";
                foreach ($header as $key => $label) {
                    $rowCtr++;
                    $value = $items_0[$key];
                    $content .= "<td>";
                    $content .= $value;
                    $content .= "</td>";
                }
                $content .= "</tr>";
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
        $content .= "</table class='table table-bordered'>";
        $content .= "</div class='table-responsive'>";
        $content .= "</div>";
        $content .= "</div>";
        $p->addTags(array(
            "menu_left"           => callMenuLeft(),
            "float_menu_atas"     => callFloatMenu('atas'),
            "float_menu_bawah"    => callFloatMenu(),
            "menu_taskbar"        => callMenuTaskbar(),
            "btn_back"            => callBackNav(),
            "data_active_content" => $content,
            "profile_name"        => $this->session->login['nama'],
            "search_str"          => isset($_GET['k']) ? $_GET['k'] : "",
            "selected_data"       => $seleted_data,
            "link_str"            => $linkStr,
            "data_active_title"   => $strActiveDataTitle
        ));

        $p->render();
        break;
    case "add":
        $p = New Layout("$title", "$subTitle", "application/template/data.html");
        $p->addTags(array(
            "menu_left"        => callMenuLeft(),
            "float_menu_atas"  => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back"         => callBackNav(),
            "content"          => $content,

            "profile_name" => $this->session->login['nama'],
        ));

        $p->render();
        break;
    case "edit":
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
        ));
        //

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
                    $header_result_f = array('data' => $label, 'class' => 'sini');
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

        if (sizeof($arrayHistory) > 0) {
            if (sizeof($arrayHistoryLabels) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabels as $key => $label) {
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



                if(isset($arrDatum['img'])){

                    $strProfile .= "<b>" . $arrDatum['label'] . "</b>";

                    $img_scr = $defaultValue = isset($arrProfile->esignature_img) && $arrProfile->esignature_img != null && $arrProfile->esignature_img != '' ? "src='" . $arrProfile->esignature_img . "'" : "src='".base_url()."public/images/img_blank.gif?v=edan'";
                    $readonlyStr = "";
                    $length = "";
                    $fName = "img";
                    $xorPlaceHolder = "";
                    $hiden_val = "";

                    $images_del = "<a href='javascript:void(0)' class='btn btn-link' onclick=\"top.confirm_alert_result('Hapus Foto?','foto untuk produk ini akan di hapus','$deleteLink')\" title='klik untuk hapus gambar produk'><i class='fa fa-trash-o'></i></a>";

                    $img_list .= "<form action='../../Data/saveSignature/User/esign' id='esign' method='post' enctype='multipart/form-data' target='result' class='form-horizontal' accept-charset='utf-8'>";
                    $img_list .= "<div style='border: 1px solid lightgray;' class='box box-warning'>";
                    $img_list .= "<div class='box-body'>";
                    $img_list .= "<div class='col-xs-6 col-sm-4 col-lg-3 no-padding'>";
                    $img_list .= "<div class='thumbnail'>";
                    $img_list .= "<img $img_scr class='img-responsive' style='filter: grayscale(100%) contrast(5)' width='130px'>";
                    $img_list .= "</div>"; //thumbnail
                    $img_list .= "</div>"; //col-col 3
                    $img_list .= "<div class='col-xs-6 col-sm-12 col-lg-12 no-padding'>";

                    $img_list .= "<div class='clearfix'>&nbsp;</div>"; //box-body
                    $img_list .= "<input type='file' maxlength='" . $length . "' name='$fName' id='_$fName' $readonlyStr placeholder='" . $xorPlaceHolder . "' value=" . $defaultValue . " class='form-control fc-modal' autocomplete='off' >"; //col-col
                    $img_list .= "<input name='id' value=" . $arrProfile->id . " class='hidden'>";
                    $img_list .= "<input name='nama_login' value=" . $arrProfile->nama_login . " class='hidden'>";
                    $img_list .= "<input name='nama' value=" . $arrProfile->nama . " class='hidden'>";
                    $img_list .= "<input name='referal' value=" . "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . " class='hidden'>";
                    $img_list .= "</div>"; //col-col 12
                    $img_list .= "</div>"; //box-body
                    $img_list .= "<div class='btn btn-md btn-warning pull-right' id='_btn_upload'> <i class='fa fa-send'></i> UPLOAD </div>"; //box-body

                    $img_list .= "</div>"; //box-warning
                    $img_list .= "</form>"; //box-warning

                    $images_val .= $img_list;

                    $images_val .= " \n<script>
                                        $(document).ready(function(){
                                            $('#_$fName').fileinput({
                                                showUpload: false,
                                                maxFileCount: 1,
                                                mainClass: 'input-group-sm'
                                            });

                                            $('#esign').bind('ajax:complete', function(data) {
                                                console.log('form upload complete');
                                                console.log(data);
                                            });

                                            $('#_btn_upload').on('click', function(){
                                                $('#esign').submit();
                                                top.swal('mohon tunggu...', 'E-Signature sedang di proses..', 'info');
                                                top.swal.enableLoading();
                                            })

                                        });
                                    </script>";

                    $strProfile      .= $images_val;
                }
                else{

                    $strProfile .= "<b>" . $arrDatum['label'] . "</b> $nilai_l";
                }

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
            foreach ($forms as $label => $nilai) {
                $forms_viewe .= $ly->form_group($label, $nilai);
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
                $f_icon_db = isset($dataTemp->icon) ? $dataTemp->icon : "";
                $f_icon = strlen($f_icon_db) < 2 ? "fa-star" : $f_icon_db;
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
        $p->addTags(array(
            "content"  => $content,
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
                if( $('#contoh').length > 0 ){
                     $('#contoh').DataTable({
                                stateSave: true,
                                // order: [[ 11, 'desc' ]],
                                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                pageLength: -1,                                   
                            });
                }
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
}