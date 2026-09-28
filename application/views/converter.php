<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {


    default:


        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/home.html");

        $strOnprog = "";

        $link_load = base_url()."Converter/$methode/$segment_tambahan";
        $script_bottom = "<script>";
        $script_bottom .= "$(\"#summary_indeks\").load(\"$link_load\");";

        $link_load = base_url() . "Converter/persiapan_data";
        $script_bottom .= "$(\"#persiapan\").load(\"$link_load\");";
        $script_bottom .= "</script>";


        $script_bottom .= "<script>";
        $script_bottom .= "

            var lastUploadData = localStorage.last_upload_data != undefined ? JSON.parse(localStorage.last_upload_data) : []

            if(countObj(lastUploadData)>0){
                console.log(lastUploadData);
            }

        ";
        $script_bottom .= "</script>";


        $p->addTags(array(
            "menu_left"          => callMenuLeft(),
            //                "trans_menu"         => callTransMenu(),
            "float_menu_atas"    => callFloatMenu('atas'),
            "float_menu_bawah"   => callFloatMenu(),
            "menu_taskbar"       => callMenuTaskbar(),
            "btn_back"           => callBackNav(),
            "alt_display"        => "",
            "prop_display"       => "",
            "onprogress_title"   => "kosong",
            "onprogress_content" => "",
            "onprogress_footer"  => "",
            "add_link"           => "",
            "history_title"      => "kosong",
            "history_content"    => "",
            "history_footer"     => "",
            "profile_name"       => "",
            "recap_title"        => "status persiapan data",
            "recap_content"      => "<div id='persiapan'></div>",
            "recap_footer"       => "",
            "stop_time"          => "",
            "top_content"         => "",
            "script_bottom"      => $script_bottom,
        ));

        $p->render();


        break;


}