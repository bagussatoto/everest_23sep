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
        break;
    case "edit":
//        arrPrint($anu);


        $p = New Layout("", "", "application/template/komposisi2.html");
//        $p = New Layout("", "", "application/template/transaksi.html");
        $p->addTags(array(
            "composition" => $content,
            "prodID" => isset($_GET['sID']) ? $_GET['sID'] : "0",
        ));

        $p->render();
        break;

    case "addMany":
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-warning-dot text-center'><span>$errMsg</span></div>";
        } else {
            $error = "";
        }
        $p = New Layout("$title", "$subTitle", "application/template/massEditor2.html");
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
            "float_menu_atas"    => callFloatMenu('atas'),
            "float_menu_bawah"   => callFloatMenu(),
            "menu_taskbar"     => callMenuTaskbar(),
            "btn_back" => callBackNav(),

            "data_active_title" => "You can fill in one or more rows to $title",
            "data_active_content" => $content,

            "profile_name" => $this->session->login['nama'],
            //                "link_str" => $linkStr,
            "error_msg" => $error,
            //                "search_str" => $searchStr,
            "this_page" => $thisPage,
            "form_target" => $formTarget,
            "search_str" => isset($_GET['k']) ? $_GET['k'] : "",
        ));
        //endregion

        $p->render();
        break;



}