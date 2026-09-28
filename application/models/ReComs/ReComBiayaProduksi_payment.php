<?php

class ReComBiayaProduksi_payment extends MdlMother
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
        unset( $_SESSION[$cCode]['main']['reComs']);
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            $_SESSION[$cCode]['main']['reComs']="RekeningPembantuBiayaKomposisiProduksi";
                }

//matiHere("hooppp");
        return true;
    }

    public function exec()
    {

//        mati_disini();
        return true;
    }
}