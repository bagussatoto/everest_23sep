<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerValueAktivaItem extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;
    private $paymentMethod = array(
        "credit",
        "cia",
        "tt_adv",
        "cbd"
    );


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
//        cekHitam("cetak inParams LockerValue");
//        arrPrint($inParams);

//        matiHere(__LINE__);
        if (sizeof($inParams) > 0) {
//            $sentParams = $inParams;
            foreach($inParams as $sentParams){
                $patchers = array();
                $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);

//                if ((!isset($sentParams['static']['transaksi_id'])) || ($sentParams['static']['transaksi_id'] == 0)) {
//                    die(lgShowAlert("insufficient transaksiID for " . $inParams['static']['jenis'] . " by 0" . " code " . __LINE__));
//                }
                $this->load->model("Mdls/MdlLockerValue");

                if($sentParams['static']['nilai'] > 0){
                    $b = new MdlLockerValue();
                    $b->addFilter("jenis='" . $sentParams['static']['jenis'] . "'"); // rekeningnya
                    $b->addFilter("state='" . $sentParams['static']['state'] . "'");
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                    $b->addFilter("produk_id='" . $sentParams['static']['produk_id'] . "'"); // customersnya
//                $b->addFilter("transaksi_id='" . $sentParams['static']['transaksi_id'] . "'"); // trID SO
                    $tmp = $b->lookupAll()->result();
                    if(count($tmp)>0){
                        $valRek = $tmp[0]->nilai;
                    }
                    else{
                        $valRek = 0;
                        matiHere("Gagal mengambil value rekening ".__LINE__." ".date());
                    }

                    if ($valRek > $sentParams['static']['nilai']) {
                        $valNew = $sentParams['static']['nilai'];
                        $valSisa = 0;
                    }
                    else {
                        if ($valRek > 0) {
                            $valNew = $valRek;
                            $valSisa = 0;
                        }
                        else {
                            $valNew = 0;
                            $valSisa = 0;
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
                }
                else{
                    $usedVal = array(
                        "nilai_dipakai" => 0,
                        "nilai_sisa" => 0,
                    );
                }
                $usedVal["nilai_tambah"] = 0;
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        cekHere($key . "_" . $patchersKey . " ==== " . $usedVal[$key]);
                        $patchers[$gateName][$sentParams['static']['produk_id']][$key . "_" . $patchersKey] = $usedVal[$key];
                    }
                }
                $this->result = $patchers;

            }
        }
        else {
            $this->result = array();
        }
//        arrprint($this->result);
//        matiHere();
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