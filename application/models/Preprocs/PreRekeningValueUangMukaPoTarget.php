<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreRekeningValueUangMukaPoTarget extends CI_Model
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

            $accountChilds = null != $this->config->item('accountChilds') ? $this->config->item('accountChilds') : array();

            $sentParams = $inParams;
            $patchers = array();
            $usedVal = array();
            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);


            $modelChild = isset($accountChilds[$sentParams['static']['jenis']]) ? "Com" . $accountChilds[$sentParams['static']['jenis']] : "";
            $getPosition = detectRekDefaultPosition($sentParams['static']['jenis']);
            if (isset($sentParams['static']['tipe_po']) && ($sentParams['static']['tipe_po'] == NULL)) {
                $sentParams['static']['tipe_po'] = "reguler";
            }
            cekHitam("[$modelChild] || " . $sentParams['static']['tipe_po']);

            if (isset($sentParams['static']['tipe_po']) && ($sentParams['static']['tipe_po'] == "target")) {
                cekHijau("ATAS");
                if ($inParams['static']['nilai'] > 0) {
                    // RekeningPembantuUangMukaMainReference
//                    $accountChilds = null != $this->config->item('accountChilds') ? $this->config->item('accountChilds') : array();
                    $accountChilds = null != $this->config->item('accountSuperSubChilds') ? $this->config->item('accountSuperSubChilds') : array();
                    $modelChild = isset($accountChilds[$sentParams['static']['jenis']]) ? "Com" . $accountChilds[$sentParams['static']['jenis']] : "";
                    cekHitam("[$modelChild] || " . $sentParams['static']['tipe_po']);
                    $this->load->model("Coms/$modelChild");
                    $b = new $modelChild();
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");
                    $b->addFilter("extern2_id='" . $sentParams['static']['referensi_po_id'] . "'");
                    $tmp = $b->fetchBalances($sentParams['static']['jenis']);
                    if (sizeof($tmp) > 0) {
                        $valRek = $tmp[0]->$getPosition;
                    }
                    else {
                        $valRek = "0";
                    }
//arrPrint($sentParams['static']['validate']);

                    /*
                     * proteksi GRN PO target yang wajib memiliki uang muka berelasi po dengan ppn
                     * sementara dicabut dulu dan berjalan seperti GRN PO REGULER
                     * 16 juni 2025 jam 12.00
                     * */
//                    if (isset($sentParams['static']['validate']) && ($sentParams['static']['validate'] == 1)) {
//                        if ($valRek <= 0) {
//                            $msg = "Transaksi gagal disimpan. ";
//                            $msg .= "Pembelian Target wajib ada UANG MUKA dahulu. Silahkan buat UANG MUKA untuk pembelian target ini. code: " . __LINE__;
//                            mati_disini($msg);
//                        }
//                    }

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
            }
            else {
                cekHijau("BAWAH");
                cekHitam("[$modelChild]");
//                if ($inParams['static']['nilai'] > 0) {
//
//                    $this->load->model("Coms/$modelChild");
//                    $b = new $modelChild();
//                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
//                    $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");
//                    $tmp = $b->fetchBalances($sentParams['static']['jenis']);
//
//
//                    if (sizeof($tmp) > 0) {
//                        $valRek = $tmp[0]->$getPosition;
//                    }
//                    else {
//                        $valRek = "0";
//                    }
//
//                    if ($valRek > $inParams['static']['nilai']) {
//                        $valNew = $inParams['static']['nilai'];
//                        $valSisa = 0;
//                    }
//                    else {
//                        if ($valRek > 0) {
//                            $valNew = $valRek;
//                            $valSisa = $inParams['static']['nilai'] - $valRek;
//                        }
//                        else {
//                            $valNew = 0;
//                            $valSisa = $inParams['static']['nilai'];
//                        }
//                    }
//
//
//                    if (($valSisa >= 0) && ($valSisa <= 100)) {
//                        // selisih tetap...
//                    }
//                    elseif (($valSisa <= 0) && ($valSisa >= -100)) {
//                        // selisih tetap...
//                    }
//                    else {
//                        $valSisa = 0;
//                    }
//
//
//                    if ($valNew > 0) {
//                        $usedVal = array(
//                            "nilai_dipakai" => $valNew,
//                            "nilai_sisa" => $valSisa,
//                        );
//                    }
//                    else {
//                        $usedVal = array(
//                            "nilai_dipakai" => $valNew,
//                            "nilai_sisa" => $valSisa,
//                        );
//                    }
//                }
//                else {
//                    $usedVal = array(
//                        "nilai_dipakai" => 0,
//                        "nilai_sisa" => 0,
//                    );
//                }
                $usedVal = array(
                    "nilai_dipakai" => 0,
                    "nilai_sisa" => 0,
                );
                $usedVal["nilai_tambah"] = $inParams['static']['nilai'] - $usedVal["nilai_dipakai"];

            }


            foreach ($this->resultParams as $gateName => $paramSpec) {
                foreach ($paramSpec as $key => $val) {
                    cekHere($key . "_" . $patchersKey . " ==== " . $usedVal[$key]);
                    $patchers[$gateName][$key . "_" . $patchersKey] = $usedVal[$key];
                }
            }
//            mati_disini(__LINE__);
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

