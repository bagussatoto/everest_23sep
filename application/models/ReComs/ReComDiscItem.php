<?php

class ReComDiscItem extends MdlMother
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
        $cCode = cCodeBuilderMisc($this->jenisTr);
        if((isset($_SESSION[$cCode]['items'])) && (sizeof($_SESSION[$cCode]['items'])) > 0){
            foreach ($_SESSION[$cCode]['items'] as $k => $iSpec){
                if(!isset($iSpec['disc_percent'])){
                    $_SESSION[$cCode]['items'][$k]['disc_percent'] = 0;
                    $_SESSION[$cCode]['items'][$k]['disc'] = 0;
                }
                else{
                    $_SESSION[$cCode]['items'][$k]['disc_percent'] = 0;
                    $_SESSION[$cCode]['items'][$k]['disc'] = 0;
                }
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