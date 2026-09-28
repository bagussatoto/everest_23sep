<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 08/12/18
 * Time: 16:39
 */
switch ($mode) {
    default:
    case "index":
        $p = New Layout("details", "{subTitle}", "application/template/viewdetails.html");
        $p->addTags(array('content'=>$content));
        $p->render();
        break;
}