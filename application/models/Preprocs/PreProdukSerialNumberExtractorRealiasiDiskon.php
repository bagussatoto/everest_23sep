<?php


class PreProdukSerialNumberExtractorRealiasiDiskon extends CI_Model
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
            $pakai_ini = 0;
            if (isset($inParams["static"]["kompensasiMethod"]) && $inParams["static"]["kompensasiMethod"] == "4") {
                $pakai_ini = 1;
            }

            if ($pakai_ini == 1) {
                $jenisTr = $inParams["static"]["jenisTr"];
                $cabangID = $inParams["static"]["cabang_id"];
                $step_number = $inParams["static"]["step_number"];
                $cCode = "_TR_" . $jenisTr;
                $_SESSION[$cCode]["items3_sum"] = array();
//                cekHitam("masuk disini, tidak scan serial saat grn...");

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    if (isset($_SESSION[$cCode]["items2"])) {
                        if (isset($_SESSION[$cCode]["items2"])) {
                            foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                                foreach ($spec as $produk_sku => $subSpec) {
                                    if (sizeof($subSpec) == 0) {
                                        if (isset($_SESSION[$cCode]["items5_sum"][$produk_id])) {
                                            $konversiSpec = $_SESSION[$cCode]["items5_sum"][$produk_id];
                                            $jml = $konversiSpec["qty"];
                                            $jml_sku = $konversiSpec[$produk_sku];
                                            $jml_serial = $konversiSpec["jml_serial"];
                                            $itemFlip = array_flip($konversiSpec);
                                            if ($itemFlip[$produk_sku] == NULL) {
                                                $pNama = $_SESSION[$cCode][$gateItems][$produk_id]["nama"];
                                                $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                                mati_disini($msg);
                                            }
                                            $key_data_arr = explode("_", $itemFlip[$produk_sku]);
                                            $key = $key_data_arr[0] == "sub" ? "1" : "0";
                                            switch ($key_data_arr[$key]) {
                                                case "outdoor":
                                                    $part_kode = "OT";
                                                    break;
                                                case "indoor":
                                                    $part_kode = "IN";
                                                    break;
                                                default:
                                                    $part_kode = "PART";
                                                    break;
                                            }
                                            if ($jml_serial > 0) {
                                                if ($produk_sku == NULL) {
                                                    $msg = "SKU Barang/Produk tidak dikenali. Silahkan periksa Data Barang/Produk anda. code: " . __LINE__;
                                                    mati_disini($msg);
                                                }
                                                for ($x = 1; $x <= $jml_sku; $x++) {
                                                    unset($konversiSpec["jml"]);
                                                    unset($konversiSpec["qty"]);
                                                    $data = $konversiSpec + array(
                                                            "jml" => 1,
                                                            "qty" => 1,
                                                            "serial_number" => "",
                                                            "produk_serial" => "",
                                                            "produk_sku" => trim($produk_sku),
                                                            "produk_sku_serial" => "",
                                                            "produk_sku_part_id" => "",
                                                            "produk_sku_part_nama" => trim($produk_sku),
                                                            "produk_sku_part_serial" => "",
                                                            "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                            "transaksi_reference_fulldate" => date("Y-m-d"),
                                                            "transaksi_reference_count" => 1,
                                                            "part_keterangan" => $part_kode,
                                                        );
                                                    $_SESSION[$cCode]["items3_sum"][] = $data;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else {
                    if (isset($_SESSION[$cCode]["items5_sum"]) && (sizeof($_SESSION[$cCode]["items5_sum"]) > 0)) {
                        foreach ($_SESSION[$cCode]["items5_sum"] as $produk_id => $spec) {
                            $jml = $spec["qty"];
                            for ($x = 1; $x <= $jml; $x++) {
                                unset($spec["jml"]);
                                unset($spec["qty"]);
                                $data = $spec + array(
                                        "jml" => 1,
                                        "qty" => 1,
                                        "serial_number" => "",
                                        "produk_serial" => "",
                                        "produk_sku" => $spec["kode"],
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $spec["kode"],
                                        "produk_sku_part_serial" => "",
                                        "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                        "transaksi_reference_fulldate" => date("Y-m-d"),
                                        "transaksi_reference_count" => 1,
                                        "part_keterangan" => "PART",
                                    );
                                $_SESSION[$cCode]["items3_sum"][] = $data;
                            }
                        }
                    }
                    else {
                        $msg = "transaksi gagal disimpan, syarat untuk nomer serial tidak terpenuhi. Silahkan cek kembali. code: " . __LINE__;
                        mati_disini($msg);
                    }
                }
            }
        }

//        arrPrintPink($_SESSION[$cCode]["items3_sum"]);
//        mati_disini(__LINE__);
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}