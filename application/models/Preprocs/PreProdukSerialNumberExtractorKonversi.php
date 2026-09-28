<?php


class PreProdukSerialNumberExtractorKonversi extends CI_Model
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
            mati_disini("params required!");
        }


        if (sizeof($inParams) > 0) {
            $jenisTr = $inParams["static"]["jenisTr"];
            $cabangID = $inParams["static"]["cabang_id"];
            $cCode = "_TR_" . $jenisTr;

            $_SESSION[$cCode]["items6_sum"] = array();
            if (isset($_SESSION[$cCode]["items2"])) {
                foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                    foreach ($spec as $produk_sku => $subSpec) {
                        foreach ($subSpec as $serial_number => $serialSpec) {
                            //--------------------------
                            $produk_konversi_id = "0";
                            $produk_konversi_nama = "";
                            if (isset($_SESSION[$cCode]["items4"][$produk_id])) {
                                foreach ($_SESSION[$cCode]["items4"][$produk_id] as $konversiSpec) {
                                    if ($konversiSpec["barcode"] == $produk_sku) {
                                        $produk_konversi_id = $konversiSpec["id"];
                                        $produk_konversi_nama = $konversiSpec["nama"];
                                        break;
                                    }
                                    if ($konversiSpec["kode"] == $produk_sku) {
                                        $produk_konversi_id = $konversiSpec["id"];
                                        $produk_konversi_nama = $konversiSpec["nama"];
                                        break;
                                    }
                                }
                            }
//                            matiHere(__LINE__);
                            //--------------------------
                            $itemFlip = array_flip($_SESSION[$cCode]["items"][$produk_id]);


                            if ($itemFlip[$produk_sku] == NULL) {
                                $pNama = $_SESSION[$cCode]["items"][$produk_id]["nama"];
                                $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                mati_disini($msg);
                            }


                            $key_data_arr = explode("_", $itemFlip[$produk_sku]);
                            switch ($key_data_arr[0]) {
                                case "outdoor":
                                    $part_kode = "OT";
                                    break;
                                case "indoor":
                                    $part_kode = "IN";
                                    break;
                                default:
                                    $part_kode = "";
                                    break;
                            }
                            $data = array(
                                "id" => $produk_id,
                                "nama" => $_SESSION[$cCode]["items"][$produk_id]["nama"],
                                "name" => $_SESSION[$cCode]["items"][$produk_id]["name"],
                                "jml" => $serialSpec["qty"],
                                "qty" => $serialSpec["qty"],
                                "produk_kode" => $_SESSION[$cCode]["items"][$produk_id]["produk_kode"],
                                "no_part" => $_SESSION[$cCode]["items"][$produk_id]["no_part"],
                                "label" => $_SESSION[$cCode]["items"][$produk_id]["label"],
                                "serial_number" => $serialSpec["serial"],
                                "produk_serial" => $serialSpec["serial"],
                                "produk_sku" => $produk_sku,
                                "produk_sku_serial" => $produk_sku . $serialSpec["serial"],
                                "produk_sku_part_id" => $serialSpec["produk_sku_part_id"],
                                "produk_sku_part_nama" => $produk_sku,
                                "produk_sku_part_serial" => $serialSpec["sku_part_serial"],
                                "produk_konversi_id" => $produk_konversi_id,
                                "produk_konversi_nama" => $produk_konversi_nama,
                                "part_keterangan" => $part_kode,
                            );
                            $_SESSION[$cCode]["items6_sum"][] = $data;
                        }
                    }
                }
            }

        }


//arrPrintKuning($_SESSION[$cCode]["items6_sum"]);
//mati_disini(__LINE__);
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}