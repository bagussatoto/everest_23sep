<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    case "index":

        //        arrPrint($fmdlTarget);
        //        cekHijau("iki broo");
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
        $pageTemplate = "application/template/barcode.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");


        //region add to content
        $p->addTags(array(
//            "prop_display" => $propDisplay,
            "menu_right_isi" => callMenuRightIsi(),
            "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
            "float_menu_atas" => callFloatMenu('atas'),
            "float_menu_bawah" => callFloatMenu(),
            "menu_taskbar" => callMenuTaskbar(),
            "btn_back" => callBackNav(),
            "data_active_title" => $strActiveDataTitle,
            "data_active_content" => $arrayHistory,
//            "data_hist_content" => $strDataHist,
//            "data_hist_footer" => $strDataHistFooter,
            "profile_name" => $this->session->login['nama'],
            "link_str" => $linkStr,
            "error_msg" => $error,
            "this_page" => $thisPage,
            "search_str" => isset($_GET['k']) ? $_GET['k'] : "",
//            "folders" => $strFolder,
//            "reg_folders_classname" => sizeof($folders) > 0 ? "col-lg-3" : "col-lg-0",
//            "reg_items_classname" => sizeof($folders) > 0 ? "col-lg-9" : "col-lg-12",
            "stop_time" => "",
            "btn_generete" => $btn_gen,
        ));
        //endregion

        $p->render();
        break;
    case "viewPrint":

        $p = New Layout("--", "--", "application/template/barcode_print.html");
//        if (sizeof($content) > 0) {
//            foreach ($content as $tKey => $tValue) {
//                $arrTags[$tKey] = $tValue;
//            }
//        }


        $p->addTags(array(
            "content" => $tmp
        ));

        $p->render();
        break;

}