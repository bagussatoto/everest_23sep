<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 2:51 PM
 */
class _globalTemplate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
//        $cCode = "_TR_" . $this->jenisTr;

    }

    public function globalTemplate()
    {
        $cCode = $this->uri->segment(4);
        $val = $this->uri->segment(5);

        if($val!=''){
            $_SESSION[$cCode]['globalTemplate'] = $val;
        }

        echo "<script>top.location.reload()</script>";
    }

}