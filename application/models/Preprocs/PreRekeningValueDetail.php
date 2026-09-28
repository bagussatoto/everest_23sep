<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */

// preprocc rekening pembantu level 2

class PreRekeningValueDetail extends CI_Model
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
        arrPrint($inParams);
        if (sizeof($inParams) > 0) {

//            $accountChilds = null != $this->config->item('accountChilds') ? $this->config->item('accountChilds') : array();
            $accountChilds = null != $this->config->item('accountSubChilds') ? $this->config->item('accountSubChilds') : array();

            $sentParams = $inParams;
            $patchers = array();
            $usedVal = array();
            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);
            $patchersKey_2 = str_replace(" ", "_", $sentParams['static']['extern2_id']);

            $tipe_penjualan = isset($sentParams['static']['tipe_penjualan']) ? $sentParams['static']['tipe_penjualan'] : 0;
            $tipe_penjualan_coa = isset($sentParams['static']['tipe_penjualan_coa']) ? $sentParams['static']['tipe_penjualan_coa'] : 0;
            $paymentMethod = isset($sentParams['static']['payment_method']) ? $sentParams['static']['payment_method'] : "";
            $force = isset($sentParams['static']['force']) ? $sentParams['static']['force'] : NULL;
            if (isset($sentParams['static']['pos_jenis'])) {
                //untuk handling pos
                $paymentMethod = $sentParams['static']['pos_jenis'];
            }

            if ($force == "true") {
//                matiHere($paymentMethod);
                switch ($paymentMethod) {
                    case "cashless":// dari jalur pos malah diisi cashless.... sementara didaftarkan disini.
                    case "transfer":// dari jalur pos malah diisi transfer.... sementara didaftarkan disini.
                    case "cash":
                        $modelChild = isset($accountChilds[$sentParams['static']['jenis']]) ? "Com" . $accountChilds[$sentParams['static']['jenis']] : "";
                        $getPosition = detectRekDefaultPosition($sentParams['static']['jenis']);
                        if ($inParams['static']['nilai'] > 0) {

                            $this->load->model("Coms/$modelChild");
                            $b = new $modelChild();
                            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                            $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");// konsumen
                            $b->addFilter("extern2_id='" . $sentParams['static']['extern2_id'] . "'");// jenis hutang ke konsumen, uang muka, return penjualan
                            $tmp = $b->fetchBalances($sentParams['static']['jenis']);
                            cekMerah($this->db->last_query());

                            if (sizeof($tmp) > 0) {
                                $valRek = $tmp[0]->$getPosition;
                            }
                            else {
                                $valRek = "0";
                            }

                            if ($valRek > $inParams['static']['nilai']) {
                                $valNew = $inParams['static']['nilai'];
                                $valSisa = 0;
                            }
                            else {
                                if ($valRek > 0) {
                                    $valNew = $valRek;
                                    $valSisa = $inParams['static']['nilai'] - $valRek;
                                }
                                else {
                                    $valNew = 0;
                                    $valSisa = $inParams['static']['nilai'];
                                }
                            }


                            if (($valSisa >= 0) && ($valSisa <= 100)) {
                                // selisih tetap...
                            }
                            elseif (($valSisa <= 0) && ($valSisa >= -100)) {
                                // selisih tetap...
                            }
                            else {
                                $valSisa = 0;
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
                        else {
                            $usedVal = array(
                                "nilai_dipakai" => 0,
                                "nilai_sisa" => 0,
                            );
                        }
                        $usedVal["nilai_tambah"] = $inParams['static']['nilai'] - $usedVal["nilai_dipakai"];
                        foreach ($this->resultParams as $gateName => $paramSpec) {
                            foreach ($paramSpec as $key => $val) {
                                cekHere($key . "_" . $patchersKey . "_" . $patchersKey_2 . " ==== " . $usedVal[$key] . " ----- " . __LINE__);
                                $patchers[$gateName][$key . "_" . $patchersKey . "_" . $patchersKey_2] = $usedVal[$key];
                            }
                        }
                        break;
                    // selain cash dibawah sini
                    default:
                        $usedVal = array(
                            "nilai_dipakai" => 0,
                            "nilai_sisa" => 0,
                        );
                        $usedVal["nilai_tambah"] = $inParams['static']['nilai'] - $usedVal["nilai_dipakai"];
                        foreach ($this->resultParams as $gateName => $paramSpec) {
                            foreach ($paramSpec as $key => $val) {
                                cekHere($key . "_" . $patchersKey . "_" . $patchersKey_2 . " ==== " . $usedVal[$key] . " ----- " . __LINE__);
                                $patchers[$gateName][$key . "_" . $patchersKey . "_" . $patchersKey_2] = $usedVal[$key];
                            }
                        }
                        break;
                }
                cekHitam("kiriman nilai: " . $inParams['static']['nilai']);
                cekHitam("nilai_dipakai: ". $usedVal["nilai_dipakai"]);
//                cekHitam($inParams['static']['nilai']);
                $usedVal["nilai_tambah"] = $inParams['static']['nilai'] - $usedVal["nilai_dipakai"];
//                mati_disini(__LINE__);

            }
            else {

                $modelChild = isset($accountChilds[$sentParams['static']['jenis']]) ? "Com" . $accountChilds[$sentParams['static']['jenis']] : "";
                $getPosition = detectRekDefaultPosition($sentParams['static']['jenis']);


                if ($inParams['static']['nilai'] > 0) {

                    $this->load->model("Coms/$modelChild");
                    $b = new $modelChild();
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");// konsumen
                    $b->addFilter("extern2_id='" . $sentParams['static']['extern2_id'] . "'");// jenis hutang ke konsumen, uang muka, return penjualan
                    $tmp = $b->fetchBalances($sentParams['static']['jenis']);
// cekMerah($this->db->last_query());

                    if (sizeof($tmp) > 0) {
                        $valRek = $tmp[0]->$getPosition;
                    }
                    else {
                        $valRek = "0";
                    }

                    if ($valRek > $inParams['static']['nilai']) {
                        $valNew = $inParams['static']['nilai'];
                        $valSisa = 0;
                    }
                    else {
                        if ($valRek > 0) {
                            $valNew = $valRek;
                            $valSisa = $inParams['static']['nilai'] - $valRek;
                        }
                        else {
                            $valNew = 0;
                            $valSisa = $inParams['static']['nilai'];
                        }
                    }


                    if (($valSisa >= 0) && ($valSisa <= 100)) {
                        // selisih tetap...
                    }
                    elseif (($valSisa <= 0) && ($valSisa >= -100)) {
                        // selisih tetap...
                    }
                    else {
                        $valSisa = 0;
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
                else {
                    $usedVal = array(
                        "nilai_dipakai" => 0,
                        "nilai_sisa" => 0,
                    );
                }
                $usedVal["nilai_tambah"] = $inParams['static']['nilai'] - $usedVal["nilai_dipakai"];
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        cekHere($key . "_" . $patchersKey . "_" . $patchersKey_2 . " ==== " . $usedVal[$key] . " ----- " . __LINE__);
                        $patchers[$gateName][$key . "_" . $patchersKey . "_" . $patchersKey_2] = $usedVal[$key];
                    }
                }
            }

            //----------------------
            if ($tipe_penjualan > 0) {
                $patchers = array();
                $usedVal = array(
                    "nilai_dipakai" => 0,
                    "nilai_sisa" => 0,
                    "nilai_tambah" => $inParams['static']['nilai'],
                );
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        cekHijau($key . "_" . $tipe_penjualan_coa . " ==== " . $usedVal[$key] . " ----- " . __LINE__);
                        $patchers[$gateName][$key . "_" . $tipe_penjualan_coa] = $usedVal[$key];
                    }
                }
            }
            else {
                cekHitam("PENJUALAN REGULER");
            }
            //----------------------

            $this->result = $patchers;

        }
        else {
            $this->result = array();
        }


//        arrPrint($this->result);
//        mati_disini();


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

