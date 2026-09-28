<?php

class ReComRekeningKoran extends MdlMother
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

        $compatreVal = isset($gate['pair_source']) ? $gate['pair_source'] : "sisa_tagihan";
        $key = $gate['target']; // gerbang target inject
        $key_source = $gate['source']; // gerbang sumber
        $key_cash_id = $gate['id']; // gerbang sumber
        $mdlName = $gate['mdlName'];
        $jenis_source = $gate['jenis_source'];



//        mati_disini($_SESSION[$cCode]['main']['cash_account']);


        if ($_SESSION[$cCode]['main'][$jenis_source] == "rekening_koran") {

            // mengecek rekening pembantu kas, dengan externID == rekening koran
            $this->load->model("Coms/$mdlName");
            $m = New $mdlName();
            $m->setFilters(array());
            $m->addFilter("cabang_id='" . $_SESSION[$cCode]['main']['placeID'] . "'");
            $m->addFilter("extern_id='" . $_SESSION[$cCode]['main'][$key_cash_id] . "'");
            $tmp = $m->fetchBalances("kas");


            // kalau ada kas dari rekening koran
            if (sizeof($tmp) > 0) {

                $nilai_source = $_SESSION[$cCode]['main'][$key_source];
                $nilai_saldo = $tmp[0]->debet;
                if ($nilai_source > $nilai_saldo) {
                    $nilai_cash = $nilai_saldo;
                    $nilai_koran = $nilai_source - $nilai_saldo;
                }
                else {
                    $nilai_cash = 0;
                    $nilai_koran = $nilai_source;
                }


                // inject gerbang nilai main
                if (isset($_SESSION[$cCode]['main']['kas_value'])) {
                    $_SESSION[$cCode]['main']['kas_value'] = 0;
                }
                if (isset($_SESSION[$cCode]['main']['rekening_koran_value'])) {
                    $_SESSION[$cCode]['main']['rekening_koran_value'] = 0;
                }

                $_SESSION[$cCode]['main']['kas_value'] = $nilai_cash;
                $_SESSION[$cCode]['main']['rekening_koran_value'] = $nilai_koran;
            }
            else {
                // TIDAK ADA inject gerbang nilai main
                $_SESSION[$cCode]['main']['kas_value'] = 0;
                $_SESSION[$cCode]['main']['rekening_koran_value'] = $nilai_source;
            }
        }
        else {
            $_SESSION[$cCode]['main']['kas_value'] = $nilai_source;
            $_SESSION[$cCode]['main']['rekening_koran_value'] = 0;
        }


//        mati_disini();
        return true;
    }


    public function exec()
    {
        return true;
    }
}