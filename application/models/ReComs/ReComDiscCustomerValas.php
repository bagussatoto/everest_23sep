<?php

class ReComDiscCustomerValas extends MdlMother
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

    public function pair()
    {
        $cCode = "_TR_" . $this->jenisTr;
        if(sizeof($_SESSION[$cCode]['items']) > 0){
            foreach ($_SESSION[$cCode]['items'] as $k => $iSpec){
                if(!isset($iSpec['disc_percent'])){
                    $_SESSION[$cCode]['items'][$k]['disc_percent'] = 0;
                    $_SESSION[$cCode]['items'][$k]['disc_valas'] = 0;
                }
                else{
                    $disc_percent = isset($_SESSION[$cCode]['main']['pihakDisc']) ? $_SESSION[$cCode]['main']['pihakDisc'] : 0;
                    $_SESSION[$cCode]['items'][$k]['disc_percent'] = $disc_percent;
                    $_SESSION[$cCode]['items'][$k]['disc_valas'] = isset($_SESSION[$cCode]['items'][$k]['valas_nilai']) ? ($disc_percent/100) * $_SESSION[$cCode]['items'][$k]['valas_nilai'] : 0;
                }
//                cekHere($k . ", discPercent: $disc_percent, discValue: " . $_SESSION[$cCode]['items'][$k]['dics_valas']);
            }
        }


        return true;
    }

    public function exec()
    {

//        mati_disini();
        return true;
    }
}