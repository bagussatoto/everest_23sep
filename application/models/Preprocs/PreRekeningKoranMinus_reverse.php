<?php


class PreRekeningKoranMinus_reverse extends CI_Model
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


        $pakai_ini = 0;
        if($pakai_ini == 1){

            if (sizeof($inParams) > 0) {
                $cashID = isset($inParams['static']['extern_id']) ? $inParams['static']['extern_id'] : 0;
                $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
                $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;
                $method = isset($inParams['static']['method']) ? $inParams['static']['method'] : NULL;
                $jenis = isset($inParams['static']['jenis']) ? $inParams['static']['jenis'] : NULL;
                $state = isset($inParams['static']['state']) ? $inParams['static']['state'] : NULL;


                if (($method != NULL) && ($method == "rekening_koran")) {
                    // membaca saldo hutang rekening koran
                    $this->load->model("Mdls/MdlLockerValue");
                    $b = new MdlLockerValue();
//                $b->addFilter("rekening='rekening koran'");
                    $b->addFilter("cabang_id='" . $cabangID . "'");
                    $b->addFilter("produk_id='" . $cashID . "'");
                    $b->addFilter("jenis='$jenis'");
                    $b->addFilter("state='$state'");
                    $tmp = $b->lookupAll()->result();
                    // membaca plafon rekening koran
//                showLast_query("biru");
//                arrPrintWebs($tmp);

                    $selisih = $nilai - $tmp[0]->nilai;

                    if ($tmp[0]->nilai > 0) {
                        if ($selisih > 0) {
                            $nilai_koran = $selisih;
                            $nilai_cash = $tmp[0]->nilai;
                        }
                        else {
                            $nilai_koran = $nilai;
                            $nilai_cash = 0;
                        }
                    }
                    else {
                        $nilai_koran = $nilai;
                        $nilai_cash = 0;
                    }
                }
                elseif(($method != NULL) && ($method == "reguler")) {

                    $nilai_koran = 0;
                    $nilai_cash = $nilai;

                }
                else {

                    $nilai_koran = 0;
                    $nilai_cash = 0;

                }
                cekMerah(":: nilai koran: $nilai_koran,, nilai kas: $nilai_cash,, setoran: $nilai,, selisih: $selisih");

                // masuk ke gerbang...
                foreach ($this->resultParams as $gateName => $paramSpec) {
                    foreach ($paramSpec as $key => $val) {
                        $patchers[$gateName][$key] = $$val;
                    }
                }

            }
            $this->result = $patchers;
        }
        return true;



    }

    public function exec()
    {
        return $this->result;
    }
}