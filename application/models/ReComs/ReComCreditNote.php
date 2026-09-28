<?php

class ReComCreditNote extends MdlMother
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

    public function pair($gate,$source)
    {
        $cCode = "_TR_" . $this->jenisTr;
//arrPrint($gate);
//arrPrint($source);
        $compatreVal = isset($gate['pair_source'])? $gate['pair_source'] :"sisa_tagihan";


//arrPrint($_SESSION[$cCode]['main']['sisa']);
//        matiHere();
        $key = $gate['target'];
        $validVal = $_SESSION[$cCode]['main'][$compatreVal] > $source * 1 ? $source:$_SESSION[$cCode]['main'][$compatreVal];
        if(sizeof($_SESSION[$cCode]['main']) > 0){
//            foreach ($_SESSION[$cCode]['main'] as $k => $iSpec){
                if(!isset($_SESSION[$cCode]['main'][$key])){
                    $_SESSION[$cCode]['main'][$key] = $validVal;
//                    $_SESSION[$cCode]['items'][$k]['disc'] = 0;
                }
                else{
                    $_SESSION[$cCode]['main'][$key] = $validVal;
//                    $_SESSION[$cCode]['items'][$k]['disc'] = 0;
                }
//            }
        }


        return true;
    }

    public function exec()
    {

//        mati_disini();
        return true;
    }
}