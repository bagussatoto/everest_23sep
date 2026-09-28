<?php


switch ($mode) {

    case "index":
        $p = New Layout("$title", "$subTitle", "application/template/transaksi_pindahbuku.html");

        // -------------------------------------------------------- stop
        $content_lagi = "<div id='content_2'></div>";
        $script_bottom = "";
        $link_load_pd = base_url() . "Converter/persiapan_data";
//        $script_bottom .= "$(\"#content_2\").load(\"$link_load\");";
        $p->setLayoutBoxCss("box-success box-solid text-uppercase");
        $p->setLayoutBoxHeading("status persiapan data");
        $p->setLayoutBoxBody(true);
        $p->setLayoutBoxFooter("");

        $topConten = $p->layout_box($content_lagi);
        // -------------------------------------------------------- stop


        /* -----------------------------------------------------
         * barange di viewer home.php mode viewNeracaTmp
         * -----------------------------------------------------*/
        $content_tmp_neraca = "<div id='tmp_neraca'></div>";

        $link_load = base_url() . "TransaksiPindahBuku/viewNeracaTmp?s=$simple";

        $script_bottom .= "

            var viewPersiapanData = function () {
                 top.$('#content_2').html(\"<div class='row' style='margin-top: 14px; padding-bottom: 14px;'> <span class='col-lg-12 text-center'><img width='10%' src='" . base_url() . "assets/images/loading_16_p.gif'> <br><b>LOADING PERSIAPAN DATA</b></span> </div>\");
                 setTimeout(function () {
                      if (top.$('#content_2').find('img').length > 0) {
                          top.$('#content_2').load('$link_load_pd', function(){viewPersiapanData})
                      }
                 },1000);
             }

             viewPersiapanData();

            var viewTmpNeraca = function () {
                 top.$('#tmp_neraca').html(\"<div class='row' style='margin-top: 14px; padding-bottom: 14px;'> <span class='col-lg-12 text-center'><img width='10%' src='" . base_url() . "assets/images/loading_16_p.gif'> <br><b>LOADING NERACA SEMENTARA</b></span> </div>\");
                 setTimeout(function () {
                      if (top.$('#tmp_neraca').find('img').length > 0) {
                          top.$('#tmp_neraca').load('$link_load', function(){viewTmpNeraca})
                      }
                 },1000);
             }

             viewTmpNeraca();

        ";

        $topConten .= $content_tmp_neraca;
        // -------------------------------------------------------- stop


        $content = "";
//
//        if(isset($download_coa) && (sizeof($download_coa)>0)){
//            foreach ($download_coa as $label => $link){
//                $content .= "<button type='button' $btn_status class='btn btn-info' title='download ke excel'
//                    onclick=\"location.href='$link'\">Download $label</button> &nbsp;&nbsp;";
//            }
//        }
//        if(isset($upload_coa) && (strlen($upload_coa)>5)){
//
//            $content .= "<br><br>";
//            $content .= "<form action='$upload_coa' method='post' target='result' id='upload_file' enctype='multipart/form-data'>";
//            $content .= "<div class='input-group input-group-sm'>";
//            $content .= "<span class='input-group-addon'>Upload file Excel</span>";
//
//            $content .= "<input class='form-control' type='file' name='userfile'>";
//
//            $content .= "<span class='input-group-btn'>";
//
//            $content .= "<button $btn_status class='btn btn-success' type='submit' name='upload' value='upload'>UPLOAD</button>";
//
//            $content .= "</span>";
//            $content .= "</div>";
//            $content .= "</form>";
//        }

        $scriptBottom = "<script>$script_bottom</script>";
        $p->addTags(
            array(
                "trName" => $trName,
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "top_conten" => isset($topConten) ? $topConten : "",
                "content" => $content,
                "scriptBottom" => $scriptBottom,

//                "error_msg" => $error,
//                "jenisTr" => $jenisTr . $str_group,
//                "alt_display" => $altDisplay,
//                "prop_display" => $propDisplay,
//                "prop_display_2" => $propDisplay_2,
//                // --
//                // --
//                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
//                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
//                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",
//                // --
//                "onprogress_title" => $onprogressTitle,
//                "onprogress_content" => $strOnprog,
//                "onprogress_content_2" => $strOnProgTransaksi,
//                "onprogress_content_mobile" => isset($strOnprogMb) ? $strOnprogMb : "",
//                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",
//                // --
//                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
//                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
//                "onprogressView_content" => $strOnprogView,
//                "onprop_display_view" => $onpropDisplayView,
//                // --
//                "add_link" => $addLinkStr,
//                "history_title" => $historyTitle,
//                "history_content" => $strHist,
//                "history_footer" => $strHistFooter,
//                "recap_title" => $recapTitle,
//                "recap_content" => $strRecap,
//                "recap_footer" => $strRecapFooter,
//                "profile_name" => $this->session->login['nama'],
//                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "JavaScript:void(0)",
//                "newTrDisp" => isset($addLink['link']) ? "table-cell" : "none",
//                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",

//                //-----
//                "onprogress_settle_title" => $strSettledTitle,
//                "onprogress_settle_content" => $strSettled,
//                "add_settle_link" => isset($addSettledLink) ? $addSettledLink : "",
//                "onprogress_settle_footer" => isset($strSettledFooter) ? $strSettledFooter : "",
//                "settle_display" => isset($settleDisplay) ? $settleDisplay : "",
            )
        );
        $p->render();


        break;

}