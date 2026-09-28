<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */
switch ($mode) {
    case "index":

        $mytitle = "Indeks Data";
        $hasil = "";

        //$myId = my_id();
        //$myCabangId = my_cabang_id();

        $p = New Layout("$mytitle", "sub judul", "application/template/pages.html");


        if (sizeof($availMenus) > 0) {
            $hasil .= "<ul class='list-group'>";
            foreach ($availMenus as $jenis => $jenisName) {
                $hasil .= "<li class='list-group-item'>";
                if (array_key_exists($jenis, $availNewMenus)) {
//                    $hasil .= "<a href='" . base_url() . "DataView/add/$jenisName'><span class='glyphicon glyphicon-plus'></span></a> ";
                    $hasil .= "<a href='" . base_url() . "Data/add/$jenisName'><span class='glyphicon glyphicon-plus'></span></a> ";
                }
//                $hasil .= "<a href='" . base_url() . "DataView/view/$jenisName/active/1'>$jenisName</a>";
                $hasil .= "<a href='" . base_url() . "Data/view/$jenisName/'>$jenisName</a>";
                $hasil .= "</li class='list-group-item'>";
            }
            $hasil .= "</ul class='list-group'>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "btn_back" => callBackNav(),
                "content" => $hasil,
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->render();

        break;


}