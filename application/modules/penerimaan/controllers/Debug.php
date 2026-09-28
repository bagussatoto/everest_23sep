<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        require_once "_construct_file.php";

    }


    public function index()
    {


        $cCode = $this->cCode;
        if (isset($_SESSION[$cCode])) {
            arrprint($_SESSION[$cCode]);
        }
        else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

}
