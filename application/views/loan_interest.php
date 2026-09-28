<?php
switch ($mode) {
    default:
        mati_disini("mode belum ditentukan");
        break;
    case "view":
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
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/defaultPrint.html" : "application/template/loan_interest.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        //region template table

        $itemsTemplate = array(
            'table_open' => '<table id="table" class="table table-bordered table-condensed">',
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
            'table_open' => '<table id="table" class="table table-condensed">',
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
        $template = array(
            'table_open' => '<table id="datatablesDepre" class="table datatablesDepre table-condensed">',
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
                'data' => '-the item you specified has no entry arrayOnProgress-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class' => 'text-center',
            ));
            $strDataProposeFooter = "";
        }
        $strDataPropose = $this->table->generate();
        //endregion

        $template = array(
            'table_open' => '<table id="datatablesPending" class="table datatablesPending table-condensed table-hover">',
            'thead_open' => '<thead>',
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

        //region assets pending
        if (sizeof($arrayHistoryPending) > 0) {
            if (sizeof($arrayHistoryLabelsPending) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabelsPending as $key => $label) {
                    $header_Hist_f = array('data' => strtoupper($label), 'class' => 'text-muted');
                    $header_Hist[] = $header_Hist_f;
                }
                $this->table->set_heading($header_Hist);
            }
            foreach ($arrayHistoryPending as $key => $val) {
                if (sizeof($arrayHistoryLabelsPending) > 0) {
                    $isi_data = array();
                    $bgAnimated = "";
                    $bgId = $key;
                    foreach ($arrayHistoryLabelsPending as $key => $label) {
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
                        $bgAnimated = isset($_GET['show']) && $_GET['show'] == $bgId ? "backgroundAnimated" : "";

                        $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => "text-left $bgAnimated");
                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strActiveDataFooter = "<a class='btn btn-default ' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data' => "<div style='font-size: 18px;' class='box-header text-bold text-green'>-</div>",
                'colspan' => count($arrayProgressLabels) + 2,
                'class' => 'text-center',
            ));
            $strActiveDataFooter = "";
        }
        $strActiveDataPending = $this->table->generate();
        //endregion assets pending

        $template = array(
            'table_open' => '<table id="datatablesActive" class="table datatablesActive table-condensed table-hover">',
            'thead_open' => '<thead>',
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

        //region assets active
        if (sizeof($arrayHistoryActive) > 0) {
            if (sizeof($arrayHistoryLabelsActive) > 0) {
                $header_Hist = array();
                foreach ($arrayHistoryLabelsActive as $key => $label) {
                    $header_Hist_f = array('data' => strtoupper($label), 'class' => 'text-muted');
                    $header_Hist[] = $header_Hist_f;
                }
                $this->table->set_heading($header_Hist);
            }
            foreach ($arrayHistoryActive as $key => $val) {
                if (sizeof($arrayHistoryLabelsActive) > 0) {
                    $isi_data = array();
                    foreach ($arrayHistoryLabelsActive as $key => $label) {
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
                        $isi_data[] = array('data' => formatField($key, $isExist_href), 'class' => 'text-left');
                    }
                    $this->table->add_row($isi_data);
                }
            }
            $strActiveDataFooter = "<a class='btn btn-default ' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $this->table->add_row(array(
                'data' => "<div style='font-size: 18px;' class='box-header text-bold text-green'>---</div>",
                'colspan' => count($arrayProgressLabels) + 2,
                'class' => 'text-center',
            ));
            $strActiveDataFooter = "";
        }
        $strActiveDataActive = $this->table->generate();
        //endregion assets active

        $template = array(
            'table_open' => '<table id="datatablesDepre" class="table datatablesDepre table-condensed table-hover">',
            'thead_open' => '<thead>',
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

        //region foldersPending
        $strFolderPending = "";
        if (sizeof($foldersPending) > 0) {
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
            foreach ($foldersPending as $fID => $fName) {
                $isi_data = array();
                $newTargetPage = str_replace("fID", "_f", $thisPage) . "&fID=$fID&fName=$fName";
                $targetHref = isset($_GET['mode']) && $_GET['mode'] == 'print' ? "#" : $newTargetPage;
                $value = "<a href='$targetHref'><span class='fa fa-folder-o'></span> $fName</a>";
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
                    'data' => $value,
                    'class' => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style" => "color:$color",
                );
                $isi_data[] = array(
                    'data' => $editStr,
                    'class' => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style" => "color:$color",
                );
                $this->table->add_row($isi_data);

            }
            $this->table->set_template($foldersTemplate);
            $strFolderPending = $this->table->generate();
        }
        //endregion

        //region foldersActive
        $strFolderActive = "";
        if (sizeof($foldersActive) > 0) {
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
            foreach ($foldersActive as $fID => $fName) {
                $isi_data = array();
                $newTargetPage = str_replace("fID", "_f", $thisPage) . "&fID=$fID&fName=$fName";
                $targetHref = isset($_GET['mode']) && $_GET['mode'] == 'print' ? "#" : $newTargetPage;
                $value = "<a href='$targetHref'><span class='fa fa-folder-o'></span> $fName</a>";
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
                    'data' => $value,
                    'class' => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style" => "color:$color",
                );
                $isi_data[] = array(
                    'data' => $editStr,
                    'class' => 'text-left',
                    'bgcolor' => "$bgColor",
                    "style" => "color:$color",
                );
                $this->table->add_row($isi_data);

            }
            $this->table->set_template($foldersTemplate);
            $strFolderActive = $this->table->generate();
        }
        //endregion

        //region recap
        if (sizeof($arrayRecap) > 0) {
            if (sizeof($arrayRecapLabels) > 0) {
                $header_recap = array();
                foreach ($arrayRecapLabels as $key => $label) {
                    $header_recap_f = array('data' => $label, 'class' => 'header');
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
                'data' => '-the item you specified has no entry arrayRecap-',
                'colspan' => count($arrayProgressLabels) + 2,
                'class' => 'text-center',
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

//        $alternateLink = "  <div class='title'>You have no pending assets to be recorded</div>
//                            Just bought a new asset?  <a href='".base_url()."Transaksi/createForm/421'>Add it here</a>
//                            <br>
//                            Had it with you before migrating to IBS? <a href='".base_url()."Transaksi/index/2483'>Record it here</a>";

        //region add to content
        $p->addTags(array(
            "prop_display" => $propDisplay,
            "title_depresiasi" => $title_depresiasi,

            "data_active_title" => $strActiveDataTitle,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "data_propose_title" => $strDataProposeTitle,
            "data_propose_content" => $strDataPropose,
            "data_propose_footer" => $strDataProposeFooter,

            "data_hist_title" => $strDataHistTitle,
            "data_hist_content" => $strDataHist,
            "data_hist_footer" => $strDataHistFooter,
            "profile_name" => $this->session->login['nama'],
            "error_msg" => $error,

            "reg_folders_classnamePending" => sizeof($foldersPending) > 0 ? "col-lg-3" : "col-lg-0",
            "reg_folders_classnameActive" => sizeof($foldersActive) > 0 ? "col-lg-3" : "col-lg-0",

            "reg_items_classnamePending" => sizeof($foldersPending) > 0 ? "col-lg-9" : "col-lg-12",
            "reg_items_classnameActive" => sizeof($foldersActive) > 0 ? "col-lg-9" : "col-lg-12",

            "stop_time" => "",

            //
            "title_pending_assets" => "pending assets",
            "title_active_assets" => "active assets",

            //
            "sub_title_pending_assets" => "bunga pinjaman yang belum diotomatisasi",
            "sub_title_active_assets" => "otomatisasi bunga pinjaman telah aktif",

            //
            "add_link_pending_assets" => $strAddLinkPending,
            "add_link_active_assets" => $strAddLinkActive,

            //
            "edit_link_pending_assets" => $strEditLinkPending,
            "edit_link_active_assets" => $strEditLinkActive,

            //
            "data_active_content_pending_assets" => $strActiveDataPending,
            "data_active_content_active_assets" => $strActiveDataActive,

            //
            "data_active_footer_pending_assets" => $alternateLinkPending,
            "data_active_footer_active_assets" => $alternateLinkActive,

            //
            "badge_pending" => isset($badge_pending) && $badge_pending > 0 ? $badge_pending : "",
            "badge_active" => isset($badge_active) && $badge_active > 0 ? $badge_active : "",

            //
            "link_str_pending_assets" => $linkStrPending,
            "link_str_active_assets" => $linkStrActive,

            //
            "this_page_pending_assets" => $thisPagePending,
            "this_page_active_assets" => $thisPageActive,

            //
            "search_str_pending_assets" => isset($_GET['kPending']) ? $_GET['kPending'] : "",
            "search_str_active_assets" => isset($_GET['kActive']) ? $_GET['kActive'] : "",

            //
            "folders_pending_assets" => $strFolderPending,
            "folders_active_assets" => $strFolderActive,
            "custom_js" => isset($custom_js) ? $custom_js : "",

        ));
        //endregion

        $p->render();

        break;
}