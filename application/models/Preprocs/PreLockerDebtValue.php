<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerDebtValue extends CI_Model
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
        cekHitam("cetak inParams LockerValue");


        if (sizeof($inParams) > 0) {
            $sentParams = $inParams;
            $patchers = array();
            $usedVal = array();
            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);

            $this->load->model("Mdls/MdlLockerDebtValue");
            $b = new MdlLockerDebtValue();
            $b->addFilter("rekening='" . $sentParams['static']['jenis'] . "'"); // rekeningnya
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");

            $tmp = $b->lookupAll()->result();

            if (sizeof($tmp) > 0) {
                $valKre = $tmp[0]->kredit;
            }
            else {
                $valKre = "0";

            }

            if ($valKre > $inParams['static']['nilai']) {
                $valNew = $inParams['static']['nilai'];
                $valSisa = 0;
            }
            else {
                if ($valKre > 0) {
                    $valNew = $valKre;
                    $valSisa = $inParams['static']['nilai'] - $valKre;
                }
                else {
                    $valNew = 0;
                    $valSisa = $inParams['static']['nilai'];
                }
            }
            if ($valNew > 0) {
                $usedVal = array(
                    "nilai_dipakai" => $valNew,
                    "nilai_sisa" => $valSisa,
                );
            }
            else {
                $usedVal = array(
                    "nilai_dipakai" => $valNew,
                    "nilai_sisa" => $valSisa,
                );
            }


            foreach ($this->resultParams as $gateName => $paramSpec) {
                foreach ($paramSpec as $key => $val) {
                    cekHere($key . " " . $patchersKey);
                    $patchers[$gateName][$key . "_" . $patchersKey] = $usedVal[$key];
                }
            }

            $this->result = $patchers;

        }
        else {
            $this->result = array();
        }


        if (sizeof($this->result) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function exec()
    {
        return $this->result;
    }
}