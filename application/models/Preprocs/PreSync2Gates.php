<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreSync2Gates extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


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
        cekHere("cetak inParams");
        arrprint($inParams);

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {
            $patchers = array();
            foreach ($inParams as $cCtr => $sentParams) {
                foreach ($sentParams as $pSpec) {

                    $hpp = $pSpec['produk_hrg_src'] / $pSpec['produk_qty'];
                    $subtotal = $pSpec['produk_hrg_src'];

                    foreach ($this->resultParams as $gateName => $paramSpec) {
                        foreach ($paramSpec as $key => $val) {
//                            cekkuning("gateName: $gateName, key: $key, val: $val");

//                            $patchers[$gateName][$pSpec['extern_id_src']][$key] = $$val;
                            $patchers[$gateName][$pSpec['rowPreFifo']][$key] = $$val;
                        }
                    }
                }
            }

            $this->result = $patchers;
//            cekKuning(":: cetak patcher");
//            arrPrint($patchers);
//            mati_disini(get_class($this));


            if (sizeof($this->result) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    public function exec()
    {
        return $this->result;
    }
}