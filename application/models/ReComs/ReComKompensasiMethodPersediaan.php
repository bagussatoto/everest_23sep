<?php

class ReComKompensasiMethodPersediaan extends MdlMother
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
        $this->diskon_id_allowed = 7;
    }

    public function pair()
    {
        $cCode = "_TR_" . $this->jenisTr;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            $arrDiskonID = array();
            foreach ($_SESSION[$cCode]['items'] as $idd => $spec) {
                $diskon_id = $spec["diskon_id"];
                $arrDiskonID[$diskon_id] = $diskon_id;
            }
            if (sizeof($arrDiskonID) > 0) {
                if (sizeof($arrDiskonID) == 1) {
                    if (array_key_exists($this->diskon_id_allowed, $arrDiskonID)) {

                    }
                    else{
                        $msg = "Transaksi tidak bisa dilanjutkan karena tidak terdapat diskon free produk.";
                        $msg .= " Silahkan pilih hasil klaim Cash/Tunai atau Creditnote. Code: " . __LINE__;
                        mati_disini($msg);
                    }
                }
                else {
                    $msg = "Transaksi tidak bisa dilanjutkan karena terdapat diskon selain free produk.";
                    $msg .= " Silahkan hapus diskon selain free produk dari daftar. Code: " . __LINE__;
                    mati_disini($msg);
                }
            }
            else {
                $msg = "Transaksi tidak bisa dilanjutkan karena tidak terdapat diskon free produk.";
                $msg .= " Silahkan pilih hasil klaim Cash/Tunai atau Creditnote. Code: " . __LINE__;
                mati_disini($msg);
            }

//            if (array_key_exists($this->diskon_id_disallowed, $arrDiskonID)) {
//                $msg = "Transaksi tidak bisa dilanjutkan karena terdapat diskon free produk (realisasi diskon free produk dilakukan terpisah).";
//                $msg .= " Silahkan hapus diskon free produk dari daftar. Code: " . __LINE__;
//                mati_disini($msg);
//            }

        }


        return true;
    }

    public function exec()
    {
        return true;
    }
}