<?php

class ReComSessionClear extends MdlMother
{
    private $jenisTr;


    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }


    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);

    }

    public function pair($gate, $source)
    {
        $cCode = "_TR_" . $this->jenisTr;

        $keys = $gate['keys'];

        $main = array(
            "main",
            "tableIn_master",
            "tableIn_master_values",
        );
        $detail = array(
            "items",
            "tableIn_detail",
            "tableIn_detail_values",
        );

        //-------------------------
        foreach ($main as $gateName){
            if(isset($_SESSION[$cCode][$gateName]) && sizeof($_SESSION[$cCode][$gateName])>0){
                foreach ($keys as $val){
                    if(array_key_exists($val, $_SESSION[$cCode][$gateName])){
                        $_SESSION[$cCode][$gateName][$val] = NULL;
                        unset($_SESSION[$cCode][$gateName][$val]);
                    }
                }
            }
        }
        //-------------------------
        foreach ($detail as $gateName){
            if(isset($_SESSION[$cCode][$gateName]) && sizeof($_SESSION[$cCode][$gateName])>0){
                foreach ($_SESSION[$cCode][$gateName] as $ii => $iSpec){
                    foreach ($keys as $val){
                        if(array_key_exists($val, $_SESSION[$cCode][$gateName][$ii])){
                            $_SESSION[$cCode][$gateName][$ii][$val] = NULL;
                            unset($_SESSION[$cCode][$gateName][$ii][$val]);
                        }
                    }
                }
            }
        }
        //-------------------------



        return true;
    }


    public function exec()
    {
        return true;
    }
}