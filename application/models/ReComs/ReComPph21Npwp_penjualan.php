<?php

class ReComPph21Npwp_penjualan extends MdlMother
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
        if (sizeof($_SESSION[$cCode]['main']) > 0) {

            $this->load->model("Mdls/MdlCustomer");
            $sp = New MdlCustomer();
            $spTmp = $sp->lookupByID($_SESSION[$cCode]['main']['pihakID'])->result();
//            arrPrint($spTmp);
            if (sizeof($spTmp) > 0) {
                if (strlen($spTmp[0]->npwp) == 0) {
                    $msg = htmlspecialchars($_SESSION[$cCode]['main']['pihakName']) . " tidak memiliki NPWP. Silahkan memilih pilihan Non NPWP.";
                    die(lgShowAlert($msg));
                }
//                $_SESSION[$cCode]['main']['pph23_tarif'] = isset($_SESSION[$cCode]['main']['pph23Method__tarif']) ? $_SESSION[$cCode]['main']['pph23Method__tarif'] : 0;
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