<?php

class ReComUangMuka extends MdlMother
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
        $this->blacklist = array("9911", "9912");
    }

    public function pair($gate, $source)
    {
        //digeser ke model awalnya dari session bentrok dengan recom yang lain yang  menggunakan model dan key sebagai acuan
        $cCode = "_TR_" . $this->jenisTr;
        $this->load->model("Mdls/MdlSupplierCreditUangMuka");
        $b = new MdlSupplierCreditUangMuka();
        $b->setFilters(array());
        $b->addFilter("id='$source'");
        $bTmp = $b->lookupAll()->result();
        showLast_query("biru");
        $srcValue = $bTmp[0]->sisa;
        $compareVal = isset($gate['pair_source']) ? $gate['pair_source'] : "sisa_tagihan";
        $key = $gate['target'];
        $pair_to_rekening = isset($gate['pair_to_rekening']) ? $gate['pair_to_rekening'] : NULL;

        if (!in_array($this->jenisTr, $this->blacklist)) {

            $validVal = $_SESSION[$cCode]['main'][$compareVal] > $srcValue * 1 ? $srcValue : $_SESSION[$cCode]['main'][$compareVal];
            cekKuning("[$key] [$validVal] [$compareVal] [$srcValue]");
            if (sizeof($_SESSION[$cCode]['main']) > 0) {
                if (!isset($_SESSION[$cCode]['main'][$key])) {
                    $_SESSION[$cCode]['main'][$key] = $validVal;
                }
                else {
                    $_SESSION[$cCode]['main'][$key] = $validVal;
                }
            }

            if ($pair_to_rekening != NULL) {
                // berhubung saldo diambil dari rekening maka main key direset lagi ke 0 dan dibuat lagi.
                $_SESSION[$cCode]['main'][$key] = 0;

                $mdlRekening = $pair_to_rekening["mdlRekening"];
                $this->load->model("Mdls/$mdlRekening");
                $mr = New $mdlRekening();
                $mr->addFilter("extern_id=" . $bTmp[0]->extern_id);
                $mr->addFilter("extern2_id=" . $bTmp[0]->extern2_id);
                $mr->addFilter("cabang_id=" . $bTmp[0]->cabang_id);
                $mr->addFilter("debet>0");
                $mrTmp = $mr->lookupAll()->result();
//                showLast_query("orange");
//                arrPrintCyan($mrTmp);
                if (sizeof($mrTmp) > 0) {
                    $debet = $mrTmp[0]->debet;
                    $validVal = $_SESSION[$cCode]['main'][$compareVal] > $debet * 1 ? $debet : $_SESSION[$cCode]['main'][$compareVal];
                    cekOrange("[$key] [$validVal] [$compareVal] [$debet]");
                    if (sizeof($_SESSION[$cCode]['main']) > 0) {
                        if (!isset($_SESSION[$cCode]['main'][$key])) {
                            $_SESSION[$cCode]['main'][$key] = $validVal;
                        }
                        else {
                            $_SESSION[$cCode]['main'][$key] = $validVal;
                        }
                    }
                }
            }
        }
//        cekPink("[$key] || " . $_SESSION[$cCode]['main'][$key]);
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