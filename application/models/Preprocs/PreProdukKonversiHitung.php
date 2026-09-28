<?php


class PreProdukKonversiHitung extends CI_Model
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
        arrPrintKuning($inParams);

        if (sizeof($inParams) > 0) {
            $jenisTr = $inParams["static"]["jenisTr"];
            $cabangID = $inParams["static"]["cabang_id"];
            $target = $inParams["static"]["target"];
            $cCode = "_TR_" . $jenisTr;

            $_SESSION[$cCode][$target] = array();
            if (isset($_SESSION[$cCode]["items4"])) {
                foreach ($_SESSION[$cCode]["items4"] as $produk_id => $spec) {
                    if (isset($_SESSION[$cCode]["items"][$produk_id]) && (sizeof($_SESSION[$cCode]["items"][$produk_id]) > 0)) {
                        $msg_1 = "Hpp " . $_SESSION[$cCode]["items"][$produk_id]["nama"] . " tidak terdaftar. Silahkan diperiksa kembali.";
                        $msg_2 = "Spesifikasi " . $_SESSION[$cCode]["items"][$produk_id]["nama"] . " tidak lengkap. Silahkan diperiksa kembali.";
                        $produk_hpp = ($_SESSION[$cCode]["items"][$produk_id]["hpp"] > 0) ? $_SESSION[$cCode]["items"][$produk_id]["hpp"] : mati_disini($msg_1 . " code: " . __LINE__);
//                        $produk_qty_spec = ($_SESSION[$cCode]["items"][$produk_id]["qty_spec"] > 0) ? $_SESSION[$cCode]["items"][$produk_id]["qty_spec"] : mati_disini($msg_2 . " code: " . __LINE__);// per-satuan (misal 1 roll, 100 meter)
                        $produk_qty_spec = ($_SESSION[$cCode]["items"][$produk_id]["satuan_nilai"] > 0) ? $_SESSION[$cCode]["items"][$produk_id]["satuan_nilai"] : mati_disini($msg_2 . " code: " . __LINE__);// per-satuan (misal 1 roll, 100 meter)
                        $produk_hpp_spec = $produk_hpp / $produk_qty_spec;
                        $_SESSION[$cCode]["items"][$produk_id]["hpp_spec"] = $produk_hpp_spec;

                        foreach ($spec as $sub_produk_id => $subSpec) {
                            $subSpec["hpp_spec"] = $produk_hpp_spec;
                            $subSpec["hpp_spec_qty"] = ($produk_hpp_spec * $subSpec["satuan_nilai"]) * $subSpec["qty"];
                            $subSpec["hpp_spec_jml"] = ($produk_hpp_spec * $subSpec["satuan_nilai"]) * $subSpec["jml"];
                            $subSpec["hpp_spec_satuan"] = ($produk_hpp_spec * $subSpec["satuan_nilai"]);
                            $hasil = applyMaterialityAdjustment($produk_hpp_spec);
                            $subSpec["hpp_spec_matelriality"] = $hasil["adjusted_value"];
                            $subSpec["hpp_spec_qty_matelriality"] = ($hasil["adjusted_value"]) * $spec["jml"];
                            $subSpec["hpp_spec_jml_matelriality"] = ($hasil["adjusted_value"]) * $spec["jml"];
                            $subSpec["hpp_spec_satuan_matelriality"] = ($hasil["adjusted_value"]);
                            $subSpec["hpp_spec_matelriality_adjustment"] = $hasil["miscellaneous_profit_loss"];
                            $subSpec["hpp_spec_qty_matelriality_adjustment"] = ($hasil["miscellaneous_profit_loss"]) * $spec["jml"];
                            $subSpec["hpp_spec_jml_matelriality_adjustment"] = ($hasil["miscellaneous_profit_loss"]) * $spec["jml"];
                            $subSpec["hpp_spec_satuan_matelriality_adjustment"] = ($hasil["miscellaneous_profit_loss"]);

                            $_SESSION[$cCode]["items4"][$produk_id][$sub_produk_id] = $subSpec;
                        }
                    }
                    else {
                        $msg = "Transaksi konversi ini terdeteksi salah. Silahklan diperiksa kembali.";
                        mati_disini($msg . " code: " . __LINE__);
                    }

                }
            }

        }

        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}


