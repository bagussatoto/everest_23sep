<?php


class PreSyncEfisiensi extends CI_Model
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
            $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
            $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;

//            showLast_query("biru");

            /*
             * karena baya project direkuest degnan jenis transksi terpisah maka efisiensi langsung dijalankan dan filter cabang dicabut
             */
            $patchers = array();
            $production = false;
            $pakaiini = 0;
            if($pakaiini==0){
                $production=true;
            }
            else{
                $this->load->model("Mdls/MdlCabang");
                $c = New MdlCabang();
                $c->addFilter("id='" . $cabangID . "'");
                $tmp = $c->lookupAll()->result();
                if (sizeof($tmp) > 0) {
                    $production = (isset($tmp[0]->tipe) && $tmp[0]->tipe == "produksi") ? true : false;
                }
            }


            if ($production == true) {

                // masuk ke gerbang...
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        $patchers[$gateName][$key] = $$val;
                    }
                }

            }

            $this->result = $patchers;
        }
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}