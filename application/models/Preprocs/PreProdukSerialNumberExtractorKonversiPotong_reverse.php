<?php


class PreProdukSerialNumberExtractorKonversiPotong_reverse extends CI_Model
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
            mati_disini("kiriman params is required!");
        }

        $pakai_ini = 0;
        if($pakai_ini == 1){
            if (sizeof($inParams) > 0) {
                $jenisTr = $inParams["static"]["jenisTr"];
                $cabangID = $inParams["static"]["cabang_id"];
                $cCode = "_TR_" . $jenisTr;

                $_SESSION[$cCode]["items6_sum"] = array();
                $_SESSION[$cCode]["items5_sum"] = array();

                if (isset($_SESSION[$cCode]["items2"])) {
                    foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                        foreach ($spec as $produk_sku => $subSpec) {
                            foreach ($subSpec as $serial_number => $serialSpec) {
                                //--------------------------
//                            $produk_konversi_id = "0";
//                            $produk_konversi_nama = "";
                                if (isset($_SESSION[$cCode]["items4"][$produk_id])) {
                                    foreach ($_SESSION[$cCode]["items4"][$produk_id] as $konversiSpec) {
//                                    arrprint($konversiSpec);
//                                    arrPrintPink($konversiSpec);
//                                    if($konversiSpec["kode"] == $produk_sku){


                                        $produk_konversi_id = $konversiSpec["id"];
                                        $produk_konversi_nama = $konversiSpec["nama"];
                                        $jml = $konversiSpec["qty"];
                                        for ($x = 1; $x <= $jml; $x++) {
//                                        cekMerah($produk_konversi_id." $x");
                                            unset($konversiSpec["jml"]);
                                            unset($konversiSpec["qty"]);
//
                                            $data = $konversiSpec + array(
                                                    "jml" => 1,
                                                    "qty" => 1,
                                                    "serial_number" => "",
                                                    "produk_serial" => "",
                                                    "produk_sku" => $konversiSpec["produk_sku"],
                                                    "produk_sku_serial" => "",
                                                    "produk_sku_part_id" => "",
                                                    "produk_sku_part_nama" => $konversiSpec["produk_sku"],
                                                    "produk_sku_part_serial" => "",
                                                    "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                    "transaksi_reference_fulldate" => date("Y-m-d"),
                                                    "transaksi_reference_count" => 1,
                                                );
                                            $_SESSION[$cCode]["items6_sum"][] = $data;
                                        }

//                                        break;
//                                    }


                                    }

                                }

                                //--------------------------

//                            $data = array(
//                                "id" => $produk_id,
//                                "nama" => $_SESSION[$cCode]["items"][$produk_id]["nama"],
//                                "name" => $_SESSION[$cCode]["items"][$produk_id]["name"],
//                                "jml" => $serialSpec["qty"],
//                                "qty" => $serialSpec["qty"],
//                                "produk_kode" => $_SESSION[$cCode]["items"][$produk_id]["produk_kode"],
//                                "no_part" => $_SESSION[$cCode]["items"][$produk_id]["no_part"],
//                                "label" => $_SESSION[$cCode]["items"][$produk_id]["label"],
//                                "serial_number" => $serialSpec["serial"],
//                                "produk_serial" => $serialSpec["serial"],
//                                "produk_sku" => $produk_sku,
//                                "produk_sku_serial" => $produk_sku . $serialSpec["serial"],
//                                "produk_sku_part_id" => $serialSpec["produk_sku_part_id"],
//                                "produk_sku_part_nama" => $produk_sku,
//                                "produk_sku_part_serial" => $serialSpec["sku_part_serial"],
//                                "produk_konversi_id" => $produk_konversi_id,
//                                "produk_konversi_nama" => $produk_konversi_nama,
//                            );
//
//                            $_SESSION[$cCode]["items6_sum"][] = $data;

                            }
                        }
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