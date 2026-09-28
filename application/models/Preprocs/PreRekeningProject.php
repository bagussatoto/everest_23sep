<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreRekeningProject extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $paymentMethod = array();


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
        $this->load->model("Mdls/MdlPaymentSourceProject");
        $this->load->model("Coms/ComTransaksiProject");

    }

    //<editor-fold desc="getter-setter">
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }

        if (sizeof($inParams) > 0) {
//            arrPrintWebs($inParams);

            $externID = isset($inParams['static']['extern_id']) ? $inParams['static']['extern_id'] : 0;
            $externName = isset($inParams['static']['extern_nama']) ? $inParams['static']['extern_nama'] : 0;
            $extern2ID = isset($inParams['static']['extern2_id']) ? $inParams['static']['extern2_id'] : 0;
            $extern2Name = isset($inParams['static']['extern2_nama']) ? $inParams['static']['extern2_nama'] : 0;
            $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
            $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;
            $jenisTr = isset($inParams['static']['jenisTr']) ? $inParams['static']['jenisTr'] : 0;
            $termin_nppn = isset($inParams['static']['termin_nppn']) ? $inParams['static']['termin_nppn'] : 0;

            //------------------------------
            $psp = New MdlPaymentSourceProject();
            $psp->setFilters(array());
            $psp->addFilter("project_id='$externID'");
            $psp->addFilter("cabang_id='$cabangID'");
            $psp->addFilter("target_jenis='$jenisTr'");
            $pspTmp = $psp->lookupAll()->result();
//            showLast_query("biru");
//            arrPrintWebs($pspTmp);
            $termin = $pspTmp[0]->termin;
            $sisa = $pspTmp[0]->sisa;

            //------------------------------
            $cp = New ComTransaksiProject();
            $cp->addFilter(array());
            $cp->addFilter("periode='forever'");
            $cp->addFilter("extern_id='$externID'");
            $cp->addFilter("cabang_id='$cabangID'");
            $cpTmp = $cp->lookUpAll()->result();
            $saldo = $cpTmp[0]->debet;

            //------------------------------

            $cCode = "_TR_".$jenisTr;
            $new_sisa = $saldo - $nilai;
            $new_termin = $termin + 1;
            if ($new_sisa > 0) {
                $title = "INVOICE TERMIN $new_termin";
                $_SESSION[$cCode]['main']['piutang_dagang'] = $termin_nppn;
            }
            else {
                $title = "INVOICE PELUNASAN PROJECT";
//                $_SESSION[$cCode]['main']['piutang_retensi'] = $termin_nppn;
                $_SESSION[$cCode]['main']['piutang_dagang'] = $termin_nppn;
            }

            $_SESSION[$cCode]['main']['title_nota'] = $title;


//            mati_disini("==  ==");

        }


        return true;


    }

    public function exec()
    {
        return true;
    }
}