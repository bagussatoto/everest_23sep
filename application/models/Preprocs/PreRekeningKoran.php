<?php


class PreRekeningKoran extends CI_Model
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
            $cashID = isset($inParams['static']['extern_id']) ? $inParams['static']['extern_id'] : 0;
            $cabangID = isset($inParams['static']['cabang_id']) ? $inParams['static']['cabang_id'] : 0;
            $nilai = isset($inParams['static']['nilai']) ? $inParams['static']['nilai'] : 0;
            $method = isset($inParams['static']['method']) ? $inParams['static']['method'] : NULL;
            $jenis = isset($inParams['static']['jenis']) ? $inParams['static']['jenis'] : NULL;
            $state = isset($inParams['static']['state']) ? $inParams['static']['state'] : NULL;


            if ($method == "rekening_koran") {
                // membaca saldo hutang rekening koran
                $this->load->model("Coms/ComRekeningPembantuRekeningKoranMain");
                $b = new ComRekeningPembantuRekeningKoranMain();
                $b->addFilter("rekening='rekening koran'");
                $b->addFilter("cabang_id='" . $cabangID . "'");
                $b->addFilter("extern_id='" . $cashID . "'");
                $b->addFilter("periode='forever'");
                $tmp = $b->lookupAll()->result();
                // membaca plafon rekening koran

                $nilai_koran_full = $nilai;
                $nilai_cash_full = 0;

                $selisih = $nilai - $tmp[0]->kredit;

                if ($tmp[0]->kredit > 0) {
                    if ($selisih > 0) {
                        $nilai_koran = $selisih;
                        $nilai_cash = $selisih;
                    }
                    else {
                        $nilai_koran = 0;
                        $nilai_cash = 0;
                    }
                }
                else {
                    $nilai_koran = $nilai;
                    $nilai_cash = $nilai;
                }
            }
            else {
//                cekKuning(__LINE__);
                $nilai_koran = 0;
                $nilai_cash = 0;

                $nilai_koran_full = 0;
                $nilai_cash_full = $nilai;
            }
//mati_disini(":: nilai koran: $nilai_koran,, nilai kas: $nilai_cash,, setoran: $nilai,, selisih: $selisih");

            // masuk ke gerbang...
            foreach ($this->resultParams as $gateName => $paramSpec) {
                foreach ($paramSpec as $key => $val) {
                    $patchers[$gateName][$key] = $$val;
//                    $patchers[$gateName][$key . "_" . $patchersKey] = $$val;
                }
            }

        }
        $this->result = $patchers;
//        arrPrintWebs($this->result);
//        mati_disini();
        if (sizeof($this->result) > 0) {
            return true;
        }
        else {
            return false;
        }
//        mati_disini(":: rekkoran $nilai_koran :: cashnilai $nilai_cash :: nilai $nilai ::");
    }

    public function exec()
    {
        return $this->result;
    }
}