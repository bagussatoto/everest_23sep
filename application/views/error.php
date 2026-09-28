<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {

    default:
        // arrPrint($contents);
        $p = New Layout("$title", "$subTitle", "application/template/error.html");
        $var = "<div class='container marketing shadow' style='margin-top: 0px;border: solid #e5e5c5 1px;'>";

        $var .= "<div class='text-center' style='padding: 20px;'>";

        $var .= "<h1>$judul</h1>";
        $var .= "<p>$isi</p>";
        $var .= "</div>";

        $var .= "</div>";

        // $varScriptBotton = "$('#pages').append('$judul');";

        $link = base_url() . "Error/er_404";
        // $varScriptBotton = "$('#pages').load('$link');";
        $varScriptBotton = "";
        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "meta_description" => $meta_description,
            "slider"           => "",
            "fixed_news"       => "",
            "content"           => $var,
            "script_botton"    => $varScriptBotton,
            "menu_taskbar" => "",
            "float_menu_bawah" => "",
        ));

        $p->render();
        break;


}