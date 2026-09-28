<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreLockerDownpayment extends CI_Model
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

        if (sizeof($inParams) > 0) {
            $sentParams = $inParams;
            $cCode = "_TR_" . $sentParams['static']['jenisTr'];
            $this->load->model("Mdls/MdlLockerValue");
            //region cek yang aktif
            $b = new MdlLockerValue();
            $b->addFilter("jenis='" . $sentParams['static']['jenis'] . "'"); // rekeningnya
            $b->addFilter("state='" . $sentParams['static']['state'] . "'");
            $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
            $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
            $b->addFilter("produk_id='" . $sentParams['static']['produk_id'] . "'"); // customersnya
            $tmp = $b->lookupAll()->result();

//            showLast_query("pink");
//            arrPrint($tmp);
//            arrPrint($sentParams);

            $total = 0;
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $tmpSpec) {
                    $total += $tmpSpec->nilai;
                }
            }

//            cekPink2("saldo DP: $total");

            $_SESSION[$cCode]['main']['saldoDownpayment'] = $total;


            return true;
        }
        else {
            return true;
        }

    }

    public function exec()
    {
        return $this->result;
    }
}