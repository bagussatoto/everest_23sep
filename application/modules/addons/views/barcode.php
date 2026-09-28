<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    case "index":
        // cekHere();
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
        $pageTemplate = MODUL_TEMPLATE_PATH ."/template/barcode.html";
        $p = New Layout("$title", "$subTitle", "$pageTemplate");

        // arrPrint($arrayHistory);
        //region add to content
        $p->addTags(array(
            "menu_right_isi"      => callMenuRightIsi(),
            "menu_left"           => callMenuLeft(),
            "float_menu_atas"     => callFloatMenu('atas'),
            "float_menu_bawah"    => callFloatMenu(),
            "menu_taskbar"        => callMenuTaskbar(),
            "btn_back"            => callBackNav(),
            "data_active_title"   => $strActiveDataTitle,
            "data_active_content" => $arrayHistory,
            "profile_name"        => $this->session->login['nama'],
            "link_str"            => $linkStr,
            "error_msg"           => $error,
            "this_page"           => $thisPage,
            "search_str"          => isset($_GET['k']) ? $_GET['k'] : "",
            "stop_time"           => "",
            "btn_generete"        => $btn_gen,
            "navigasi_top"        => $navigasi_top,
        ));
        //endregion

        $p->render();
        break;
    case "viewPrint":
        $p = New Layout("--", "--", MODUL_TEMPLATE_PATH . "template/barcode_print.html");
        $p->addTags(array(
            "content" => $tmp,
            // "str_onload"        => "window.print();",
            // "str_onload"        => "",
        ));
        $p->render();
        break;

    case "dinamic_code":

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

        $p->addTags(array(
            "menu_right_isi"      => callMenuRightIsi(),
            "menu_left"           => callMenuLeft(),
            "float_menu_atas"     => callFloatMenu('atas'),
            "float_menu_bawah"    => callFloatMenu(),
            "menu_taskbar"        => callMenuTaskbar(),
            "btn_back"            => callBackNav(),
            "data_active_title"   => "",
            "data_active_content" => $arrayHistory,
            "profile_name"        => $this->session->login['nama'],
            "link_str"            => "",
            "error_msg"           => $error,
            "this_page"           => $thisPage,
            "search_str"          => "",
            "stop_time"           => "",
            "btn_generete"        => $btn_gen,
        ));

        $p->render();

        break;
}