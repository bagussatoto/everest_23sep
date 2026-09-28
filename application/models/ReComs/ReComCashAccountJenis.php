<?php

class ReComCashAccountJenis extends MdlMother
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
        $filter = isset($gate['filter']) ? $gate['filter'] : array();
        $result = isset($gate['result']) ? $gate['result'] : array();

        $this->load->model("Mdls/MdlBank");
        $b = New MdlBank();
        $b->setFilters(array());
        $b->addFilter("id='$source'");
        if (sizeof($filter) > 0) {
            makeFilter($filter, $_SESSION[$cCode]['main'], $b);

        }
        $bTmp = $b->lookupAll()->result();
//        showLast_query("biru");
//
//        cekHere("$source");
//        arrPrintWebs($gate);

        $arrResult = array();
        if (sizeof($bTmp) > 0) {
            $jenis_bank = $bTmp[0]->jenis;
            if ($jenis_bank == "account_cash") {
                if (sizeof($result) > 0) {
                    foreach ($result as $key => $val) {
                        $arrResult[$key] = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                    }
                }
                //---tambahan manual-------------
                $arrResult['paymentSrcLock'] = 1;
            }
            else {
                if (sizeof($result) > 0) {
                    foreach ($result as $key => $val) {
                        $arrResult[$key] = 0;
                    }
                }
                //---tambahan manual-------------
                $arrResult['paymentSrcLock'] = 0;
            }
//            arrPrintWebs($arrResult);
//            mati_disini("=====");
//
//
            foreach ($arrResult as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }

        }


        return true;
    }

    public function exec()
    {
        return true;
    }
}