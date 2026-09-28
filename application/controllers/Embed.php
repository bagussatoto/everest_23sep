<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/30/2019
 * Time: 5:30 AM
 */
class Embed extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function embed(){
        // arrPrintHijau($_GET);
        $url=blobdecode($_GET['e']);
        // cekHere($url);

        $str="";
        // $xx = "<iframe width='100 %' height='315' src='$url?autoplay=1&rel=0' frameborder='0' allow='autoplay; encrypted - media' allowfullscreen></iframe>";

        $str.= "<div class='text-center'>";
        $str.= "<iframe width='100%' height='480' src='$url' frameborder='0' allow='autoplay; encrypted - media' allowfullscreen></iframe>";
        $str.= "</div class='text-center'>";


        $p = New Layout("tutorial video", blobDecode($_GET['l']), "application/template/blank.html");
        $p->addTags(
            array(

                "content"          => $str,
            )
        );

        $p->render();
    }

    public function Youtube(){
        // arrPrintHijau($_GET);
        $url=blobdecode($_GET['e']);
        // cekHere($url);
        $str="";
        // $xx = "<iframe width='100 %' height='315' src='$url?autoplay=1&rel=0' frameborder='0' allow='autoplay; encrypted - media' allowfullscreen></iframe>";

        $str.= "<div class='text-center'>";
        $str.= "<iframe width='100%' height='480' src='$url' frameborder='0' allow='autoplay; encrypted - media' allowfullscreen></iframe>";
        $str.= "</div class='text-center'>";

        echo $str;
    }
}