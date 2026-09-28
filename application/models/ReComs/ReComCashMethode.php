<?php

class ReComCashMethode extends MdlMother
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
// arrPrint($this->uri->segment_array());
        $cCode = "_TR_" . $this->jenisTr;
// cekBiru($source);
// matiHEre($cCode);
        $this->load->model("Mdls/MdlBank");
        $b = New MdlBank();
        $b->setFilters(array());
        $b->addFilter("id='$source'");
        $bTmp = $b->lookupAll()->result();
// cekLime($this->db->last_query());
        if (sizeof($bTmp) > 0) {
            $jenis_bank = $bTmp[0]->jenis;
            if ($jenis_bank == "rekening_koran") {
                $arrCashMethode = array(
                    "cashMethode" => "rekening_koran",
                    "cashMethode__label" => "rekening koran",
                    "cashMethode__nama" => "rekening koran",
                );

            }
            else {
                $arrCashMethode = array(
                    "cashMethode" => "reguler",
                    "cashMethode__label" => "reguler",
                    "cashMethode__nama" => "reguler",
                );
            }
// arrPrint($arrCashMethode);
//             matiHEre(__LINE__." ".__FUNCTION__);
            foreach ($arrCashMethode as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }

        }
        // matiHEre(__LINE__." ".__FUNCTION__);

        return true;
    }

    public function exec()
    {
        return true;
    }
}