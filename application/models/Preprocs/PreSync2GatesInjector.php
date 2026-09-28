<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreSync2GatesInjector extends CI_Model
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
//arrPrintWebs($inParams);
        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {
            $patchers = array();
            foreach ($inParams as $cCtr => $sentParams) {
                if (isset($sentParams['static']['target'])) {
//                    $target = $sentParams['static']['target'];
                    $source = $sentParams['static']['source'];
                    $jenisTr = $sentParams['static']['jenisTr'];
                    $externID = $sentParams['static']['extern_id'];
                    $rowPreFifo = $sentParams['static']['rowPreFifo'];

                    $cCode = "_TR_" . $jenisTr;
                    $gateSource = $_SESSION[$cCode][$source];
//                    arrPrint($gateSource);
                    if (array_key_exists($externID, $gateSource)) {
                        foreach ($this->resultParams as $gateTarget => $gateSpec) {
                            foreach ($gateSpec as $key => $val) {
                                $patchers[$gateTarget][$rowPreFifo][$key] = $gateSource[$externID][$val];
                            }
                        }
                    }
                }
            }
            $this->result = $patchers;

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