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

        $debuger = isset($_GET['debuger']) && $_GET['debuger']!='' ? trim($_GET['debuger']) : false;
        $lv1 = isset($_GET['lv1']) && $_GET['lv1']!='' ? trim($_GET['lv1']) : false;
        $lv2 = isset($_GET['lv2']) && $_GET['lv2']!='' ? trim($_GET['lv2']) : false;

        cekUngu("<form target='".$this->jenisTr."'>
        <label> Lv1: <input value='$lv1' placeholder='eg: main,items' name='lv1'></label>
        <label> Lv2: <input value='$lv2' placeholder='eg: rekening/key dll' name='lv2'></label>
        <label> debug: <input value='$debuger' name='debuger'></label>
        <button href='' type='submit'>DEBUG</button></form>");

        if (isset($_SESSION[$cCode])) {
            if($lv1&&!$lv2){
                arrprint($_SESSION[$cCode][$lv1]);
            }
            else if($lv1&&$lv2){
                arrprint($_SESSION[$cCode][$lv1][$lv2]);
            }
            else{
                arrprint($_SESSION[$cCode]);
            }
        }
        else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

}
