<?php

switch ($mode) {
    case "index":

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/json_viewer.html");

        $p->addTags(
            array(
                "session"    => json_encode($_SESSION),
            )
        );

        $p->render();

        break;
}