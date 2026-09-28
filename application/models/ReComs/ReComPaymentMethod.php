<?php

class ReComPaymentMethod extends MdlMother
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

            if (isset($_SESSION[$cCode]['main']['pihakKreditLimit']) && ($_SESSION[$cCode]['main']['pihakKreditLimit'] == 0)) {
                if ($_SESSION[$cCode]['main']['paymentMethod'] != "cash") {

                    $pihakName = $_SESSION[$cCode]['main']['pihakName'];
                    $msg = "Konsumen $pihakName saat ini tidak mendapatkan kredit. Silahkan proses transaksi secara tunai/cash.";
//                    die(lgShowAlert($msg));
                }
            }

        }


        return true;
    }

    public function exec()
    {

        return true;
    }
}