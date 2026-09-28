<?php

class ReComMethodLebihBayar extends MdlMother
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
//            if (isset($_SESSION[$cCode]['main']['kelebihanBayar'])) {
//                $lebihBayarMethod = $_SESSION[$cCode]['main']['kelebihanBayar'];
//                $lebihBayarValue = $_SESSION[$cCode]['main']['lebih_bayar'];
//                if ($lebihBayarMethod == 0) {
//                    if ($lebihBayarValue < 0) {
//
//                        $_SESSION[$cCode]['main']['lebih_bayar'] = 0;
//                    }
//                    elseif ($lebihBayarValue > 0) {
//                        $msg = "Ada kelebihan bayar, silahkan pilih Deposit Konsumen atau Pendapatan Lain-lain.";
//                        die(lgShowAlert($msg));
//                    }
//                }
//                elseif ($lebihBayarMethod == 1) {
//                    if ($lebihBayarValue <= 0) {
//                        $msg = "Tidak ada kelebihan bayar, silahkan pilih None.";
//                        die(lgShowAlert($msg));
//                    }
//                }
//                elseif ($lebihBayarMethod == 2) {
//                    if ($lebihBayarValue <= 0) {
//                        $msg = "Tidak ada kelebihan bayar, silahkan pilih None.";
//                        die(lgShowAlert($msg));
//                    }
//                }
//
//            }
//            else {
//                if ($_SESSION[$cCode]['main']['lebih_bayar'] <= 0) {
//                    $msg = "Tidak ada kelebihan bayar, silahkan pilih None.";
//                    die(lgShowAlert($msg));
//                }
//            }

//            if (isset($_SESSION[$cCode]['main']['lebih_bayar']) && ($_SESSION[$cCode]['main']['lebih_bayar'] <= 0)) {
//                $msg = "Tidak ada kelebihan bayar, tidak perlu memilih tujuan kelebihan bayar.";
//                die(lgShowAlert($msg));
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