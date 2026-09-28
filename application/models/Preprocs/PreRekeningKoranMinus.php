<?php


class PreRekeningKoranMinus extends CI_Model
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
        arrPrintWebs($inParams);
// mati_disini();
        if (sizeof($inParams) > 0) {
            $cashID = isset($inParams['static']['extern_id']) ? $inParams['static']['extern_id'] : 0;
            $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
            $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;
            $method = isset($inParams['static']['method']) ? $inParams['static']['method'] : NULL;
            $jenis = isset($inParams['static']['jenis']) ? $inParams['static']['jenis'] : NULL;
            $state = isset($inParams['static']['state']) ? $inParams['static']['state'] : NULL;

            $selisih = 0;
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
            elseif (($method != NULL) && ($method == "reguler")) {

                $nilai_koran = 0;
                $nilai_cash = $nilai;

            }elseif (($method != NULL) && ($method == "none")){
                $nilai_koran = 0;
                $nilai_cash = $nilai;
            }
            else {

                $nilai_koran = 0;
                $nilai_cash = $nilai;

            }
            cekMerah(":: nilai koran: $nilai_koran,, nilai kas: $nilai_cash,, setoran: $nilai,, selisih: $selisih,, method: $method");
// matiHEre($method);
            // masuk ke gerbang...
            foreach ($this->resultParams as $gateName => $paramSpec) {
                foreach ($paramSpec as $key => $val) {
                    $patchers[$gateName][$key] = $$val;
                }
            }

        }
//         arrPrint($patchers);
//         matiHEre();
        $this->result = $patchers;
        return true;

//        if (sizeof($this->result) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }

    }

    public function exec()
    {
        return $this->result;
    }
}