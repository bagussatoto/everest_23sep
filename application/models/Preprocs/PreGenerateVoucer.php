<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreGenerateVoucer extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $allowedJenis =array(
        "468a","468"
    );

    /**
     * @return mixed
     */
    public function getAllowedJenis()
    {
        return $this->allowedJenis;
    }

    /**
     * @param mixed $allowedJenis
     */
    public function setAllowedJenis($allowedJenis)
    {
        $this->allowedJenis = $allowedJenis;
    }


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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
//        $this->load->model("Mdls/MdlFifoAverage");
//        arrPrint($inParams);
        $patchers =array();
        if(in_array($inParams["static"]["jenis"],$this->allowedJenis)){
            $preVoucher = getVoucherNumber("voucher","VC",$inParams["static"],$inParams["static"]["jenis"]);
            foreach ($this->resultParams as $gateName => $paramSpec){
                foreach($preVoucher as $k =>$val){
                    $patchers[$gateName][$k]=$val;
                }
            }
        }
//        arrPrint($patchers);
        $this->result = $patchers;
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}