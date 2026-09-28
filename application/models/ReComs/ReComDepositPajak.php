<?php

class ReComDepositPajak extends MdlMother
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
//        mati_disini($source);
        //digeser ke model awalnya dari session bentrok dengan recom yang lain yang  menggunakan model dan key sebagai acuan
        $cCode = "_TR_" . $this->jenisTr;
        $this->load->model("Mdls/MdlDepositPajak");
        $b = new MdlDepositPajak();
        $b->setFilters(array());
        $b->addFilter("id='$source'");
        $bTmp = $b->lookupAll()->result();
        $srcValue = $bTmp[0]->sisa;
        $compareVal = isset($gate['pair_source']) ? $gate['pair_source'] : "sisa_tagihan";
        $key = $gate['target'];

        $validVal = $_SESSION[$cCode]['main'][$compareVal] > $srcValue * 1 ? $srcValue : $_SESSION[$cCode]['main'][$compareVal];
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            if (!isset($_SESSION[$cCode]['main'][$key])) {
                $_SESSION[$cCode]['main'][$key] = $validVal;
            }
            else {
                $_SESSION[$cCode]['main'][$key] = $validVal;
            }
//            }
        }

        // cekHitam($this->db->last_query());
        // matiHere(__LINE__." ".__FILE__."src id ".$source." @@@ ".$srcValue);
        return true;
    }

    public function exec()
    {

//        mati_disini();
        return true;
    }
}