<?php


class PreRekeningCadanganRealtime extends CI_Model
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

            $accountChilds = "ComRekeningPembantuSupplier";

            $sentParams = $inParams;
            $patchers = array();
            $usedVal = array();
            $preAllow = $this->preAllowedSupplier($sentParams['static']['extern_id']);
            $patchersKey = str_replace(" ", "_", $sentParams['static']['jenis']);
            if($preAllow==1){

                $modelChild = "ComRekeningPembantuSupplier";
                $getPosition = detectRekDefaultPosition($sentParams['static']['jenis']);


                if ($inParams['static']['nilai'] > 0) {

                    $this->load->model("Coms/$modelChild");
                    $b = new $modelChild();
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("extern_id='" . $sentParams['static']['extern_id'] . "'");
                    $tmp = $b->fetchBalances($sentParams['static']['jenis']);
                    cekHitam($getPosition);
                    cekMerah($this->db->last_query());
                    if (sizeof($tmp) > 0) {
                        $valRek = $tmp[0]->$getPosition;
                    }
                    else {
                        $valRek = "0";
                    }

                    cekHitam($valRek."::");

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

                    cekHitam($valSisa."::".$valNew);

                    if (($valSisa >= 0) && ($valSisa <= 100)) {
                        // selisih tetap...
                    }
                    elseif (($valSisa <= 0) && ($valSisa >= -100)) {
                        // selisih tetap...
                    }
                    else {
                        cekKuning(__LINE__);
                        $valSisa = 0;
                    }

//                    cekBiru($valSisa."::".$valNew);
//                    matiHEre();
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
                        cekHere($key . "_" . $patchersKey . " ==== " . $usedVal[$key]);
                        $patchers[$gateName][$key . "_" . $patchersKey] = $usedVal[$key];
                    }
                }
//matiHEre(__LINE__);
//                arrprint($patchers);
//                matiHere();
                $this->result = $patchers;
            }
            else{
                $usedVal = array(
                    "nilai_dipakai" => 0,
                    "nilai_sisa" => 0,
                    "nilai_tambah" => 0,
                );
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        cekHere($key . "_" . $patchersKey . " ==== " . $usedVal[$key]);
                        $patchers[$gateName][$key . "_" . $patchersKey] = $usedVal[$key];
                    }
                }
//                arrprint($patchers);
//                matiHere();
                $this->result = $patchers;
            }
        }
        else {
            $this->result = array();
        }

// arrPrint($this->result);
//        mati_disini();
        if (sizeof($this->result) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function preAllowedSupplier($supplierID){
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dcu = new MdlDiskonPembelian();
        $dcu->addFilter("supplier_id='".$supplierID."'");
        $dcu->addFilter("jenis in ('khusus','khusus_abs','kelompok')");
        $temp = $dcu->lookUpAll()->result();
        if(count($temp)>0){
            $value = 1;
        }
        else{
            $value = 0;
        }
        return $value;
    }

    public function exec()
    {
        return $this->result;
    }
}