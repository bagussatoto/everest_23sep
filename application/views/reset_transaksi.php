<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
//cekHere($mode);
switch ($mode) {

    case "view":

        $p = New Layout("$title", "", "application/template/default.html");
        $p->addTags(
            array(
                "menu_left"    => callMenuLeft(),
                "content"      => $contens,
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left

        $p->render();
        break;

}