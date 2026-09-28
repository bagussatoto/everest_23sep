<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreProdukProjectReturn extends CI_Model
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
        $this->load->model("Mdls/MdlFifoAverage");
        arrPrint($inParams);

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        $tmp = array();
        $arrHasil = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $gate_target = $pSpec["gate_target"];
                    $cCode = "_TR_" . $pSpec["jenisTr"];
                    $pid = $pSpec["produk_id"];
                    $jml_dipakai = (isset($_SESSION[$cCode]["items"][$pid]["jml_dipakai"]) && ($_SESSION[$cCode]["items"][$pid]["jml_dipakai"] > 0)) ? $_SESSION[$cCode]["items"][$pid]["jml_dipakai"] : 0;
                    if ($jml_dipakai > 0) {
                        $arrHasil = $_SESSION[$cCode]["items"][$pid];
                        $arrHasil["jml"] = $jml_dipakai;
                        $arrHasil["qty"] = $jml_dipakai;
                        unset($arrHasil["harga"]);
                        unset($arrHasil["hpp"]);
                        unset($arrHasil["subtotal"]);
                        unset($arrHasil["sub_harga"]);
                        unset($arrHasil["sub_hpp"]);
                        unset($arrHasil["sub_subtotal"]);

                        if (!isset($_SESSION[$cCode][$gate_target])) {
                            $_SESSION[$cCode][$gate_target] = array();
                        }
                        if (!isset($_SESSION[$cCode][$gate_target][$pid])) {
                            $_SESSION[$cCode][$gate_target][$pid] = array();
                        }
                        $_SESSION[$cCode][$gate_target][$pid] = $arrHasil;
                    }
                }
            }



        }
        return true;
    }

    public function exec()
    {
        return true;
    }


}