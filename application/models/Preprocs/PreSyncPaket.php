<?php

class PreSyncPaket extends CI_Model
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
        cekUngu("[$master_id]");
        arrPrintCyan($inParams);
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {
//            $patchers = array();
//            foreach ($inParams as $cCtr => $sentParams) {
//                foreach ($sentParams as $pSpec) {
//                    foreach ($this->resultParams as $gateName => $paramSpec) {
//                        foreach ($paramSpec as $key => $val) {
//                            $patchers[$gateName][$pSpec['extern_id_src']][$key] = $pSpec[$val];
//                        }
//                    }
//                }
//            }
//            $this->result = $patchers;

            $jenisTr = $inParams["static"]["jenisTr"];
            $jenisTrMaster = $inParams["static"]["jenisTrMaster"];
            $source = $inParams["static"]["source"];
            $target = $inParams["static"]["target"];
            $cCode = "_TR_" . $jenisTr;
            if(isset($_SESSION[$cCode][$source]) && (sizeof($_SESSION[$cCode][$source])>0)){
                $arrHasil = array();
                foreach ($_SESSION[$cCode][$source] as $paket_id => $komponenSpec){
                    foreach ($komponenSpec as $produk_id => $spec){
                        if(!isset($arrHasil[$paket_id]["hpp_paket_original"])){
                            $arrHasil[$paket_id]["hpp_paket_original"] = 0;
                        }
                        $arrHasil[$paket_id]["hpp_paket_original"] += ($spec["jml"]*$spec["hpp_paket"]);
                    }
                }
                if(sizeof($arrHasil)>0){
                    foreach ($arrHasil as $paket_id => $data_paket){
                        $nilai = $data_paket["hpp_paket_original"];
                        $paket_qty = $_SESSION[$cCode][$target][$paket_id]["jml"];
                        $nilai_per_paket = $nilai/$paket_qty;
                        $_SESSION[$cCode][$target][$paket_id]["hpp_paket_original"] = $nilai_per_paket;
                    }
                }
            }
            return true;
//            if (sizeof($this->result) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }
        }
    }

    public function exec()
    {
        return true;
//        return $this->result;
    }
}