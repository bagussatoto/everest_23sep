<?php


class PreProdukKonversi extends CI_Model
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
                    foreach ($spec as $sub_produk_id => $subSpec) {
//                        arrPrintPink($subSpec);

//                        $data = array(
//                            "id" => $produk_id,
//                            "nama" => $_SESSION[$cCode]["items"][$produk_id]["nama"],
//                            "name" => $_SESSION[$cCode]["items"][$produk_id]["name"],
//                            "jml" => $serialSpec["qty"],
//                            "qty" => $serialSpec["qty"],
//                            "produk_kode" => $_SESSION[$cCode]["items"][$produk_id]["produk_kode"],
//                            "no_part" => $_SESSION[$cCode]["items"][$produk_id]["no_part"],
//                            "label" => $_SESSION[$cCode]["items"][$produk_id]["label"],
//                            "serial_number" => $serialSpec["serial"],
//                            "produk_serial" => $serialSpec["serial"],
//                            "produk_sku" => $serialSpec["sku"],
//                            "produk_sku_serial" => $serialSpec["sku_serial"],
//                            "produk_sku_part_id" => $serialSpec["produk_sku_part_id"],
////                                "produk_sku_part_nama" => $serialSpec["produk_sku_part_nama"],
//                            "produk_sku_part_nama" => $produk_sku,
//                            "produk_sku_part_serial" => $serialSpec["sku_part_serial"],
//                        );
                        $_SESSION[$cCode][$target][] = $subSpec;
                    }
                }
            }

        }
//arrPrintKuning($_SESSION[$cCode]["items3_sum"]);
//mati_disini(__LINE__);
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}


