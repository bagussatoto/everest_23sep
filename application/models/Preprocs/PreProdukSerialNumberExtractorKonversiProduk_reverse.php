<?php


class PreProdukSerialNumberExtractorKonversiProduk_reverse extends CI_Model
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

                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (isset($_SESSION[$cCode]["items2"])) {
                        foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                            //--------------------------
                            if (isset($_SESSION[$cCode]["items2_sum"][$produk_id])) {
                                foreach ($_SESSION[$cCode]["items2_sum"] as $produk_id => $konversiSpec) {
                                    if ($konversiSpec["kategori_nama"] == "unit") {
                                        $itemFlip = array_flip($konversiSpec);
                                        $produk_konversi_id = $konversiSpec["id"];
                                        $produk_konversi_nama = $konversiSpec["nama"];
                                        $jml = $konversiSpec["jml"];
                                        $jml_serial = $konversiSpec["jml_serial"];//hanya produk berserial yang dibuatkan items6_sum
                                        if (isset($_SESSION[$cCode]["items4"][$produk_konversi_id])) {
                                            foreach ($_SESSION[$cCode]["items4"][$produk_konversi_id] as $produk_sku => $subSpec) {
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
                                                for ($x = 1; $x <= $jml; $x++) {
                                                    unset($konversiSpec["jml"]);
                                                    unset($konversiSpec["qty"]);
                                                    $data = $konversiSpec + array(
                                                            "jml" => 1,
                                                            "qty" => 1,
                                                            "serial_number" => "",
                                                            "produk_serial" => "",
                                                            "produk_sku" => $produk_sku,
                                                            "produk_sku_serial" => "",
                                                            "produk_sku_part_id" => "",
                                                            "produk_sku_part_nama" => $produk_sku,
                                                            "produk_sku_part_serial" => "",
                                                            "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                            "transaksi_reference_fulldate" => date("Y-m-d"),
                                                            "transaksi_reference_count" => 1,
                                                            "part_keterangan" => $part_kode,
                                                        );
                                                    $_SESSION[$cCode]["items6_sum"][] = $data;
                                                }
                                            }
                                        }
                                        else {
                                            $pNama = $produk_konversi_nama;
                                            $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                            mati_disini($msg);
                                        }
                                    }
                                    else {
                                        $produk_konversi_id = $konversiSpec["id"];
                                        $produk_konversi_nama = $konversiSpec["nama"];
                                        $jml = $konversiSpec["jml"];
                                        $jml_serial = $konversiSpec["jml_serial"];//hanya produk berserial yang dibuatkan items6_sum
                                        if ($jml_serial > 0) {
                                            for ($x = 1; $x <= $jml; $x++) {
                                                unset($konversiSpec["jml"]);
                                                unset($konversiSpec["qty"]);
                                                $data = $konversiSpec + array(
                                                        "jml" => 1,
                                                        "qty" => 1,
                                                        "serial_number" => "",
                                                        "produk_serial" => "",
//                                                "produk_sku" => $konversiSpec["produk_sku"],
                                                        "produk_sku" => $konversiSpec["produk_kode"],
                                                        "produk_sku_serial" => "",
                                                        "produk_sku_part_id" => "",
//                                                "produk_sku_part_nama" => $konversiSpec["produk_sku"],
                                                        "produk_sku_part_nama" => $konversiSpec["produk_kode"],
                                                        "produk_sku_part_serial" => "",
                                                        "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                        "transaksi_reference_fulldate" => date("Y-m-d"),
                                                        "transaksi_reference_count" => 1,
                                                    );
                                                $_SESSION[$cCode]["items6_sum"][] = $data;
                                            }
                                        }
                                    }
                                }
                            }
                            //--------------------------
                        }
                    }
                }
                else {
                    //--------------------------
                    if (isset($_SESSION[$cCode]["items2_sum"])) {
                        foreach ($_SESSION[$cCode]["items2_sum"] as $produk_id => $konversiSpec) {
                            if ($konversiSpec["kategori_nama"] == "unit") {
                                $itemFlip = array_flip($konversiSpec);
                                $produk_konversi_id = $konversiSpec["id"];
                                $produk_konversi_nama = $konversiSpec["nama"];
                                $jml = $konversiSpec["jml"];
                                $jml_serial = $konversiSpec["jml_serial"];//hanya produk berserial yang dibuatkan items6_sum
                                if (isset($_SESSION[$cCode]["items4"][$produk_konversi_id])) {
                                    foreach ($_SESSION[$cCode]["items4"][$produk_konversi_id] as $produk_sku => $subSpec) {
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
                                        for ($x = 1; $x <= $jml; $x++) {
                                            unset($konversiSpec["jml"]);
                                            unset($konversiSpec["qty"]);
                                            $data = $konversiSpec + array(
                                                    "jml" => 1,
                                                    "qty" => 1,
                                                    "serial_number" => "",
                                                    "produk_serial" => "",
                                                    "produk_sku" => $produk_sku,
                                                    "produk_sku_serial" => "",
                                                    "produk_sku_part_id" => "",
                                                    "produk_sku_part_nama" => $produk_sku,
                                                    "produk_sku_part_serial" => "",
                                                    "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                    "transaksi_reference_fulldate" => date("Y-m-d"),
                                                    "transaksi_reference_count" => 1,
                                                    "part_keterangan" => $part_kode,
                                                );
                                            $_SESSION[$cCode]["items6_sum"][] = $data;
                                        }
                                    }
                                }
                                else {
                                    $pNama = $produk_konversi_nama;
                                    $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                    mati_disini($msg);
                                }
                            }
                            else {
                                $produk_konversi_id = $konversiSpec["id"];
                                $produk_konversi_nama = $konversiSpec["nama"];
                                $jml = $konversiSpec["jml"];
                                $jml_serial = $konversiSpec["jml_serial"];//hanya produk berserial yang dibuatkan items6_sum
                                if ($jml_serial > 0) {
                                    for ($x = 1; $x <= $jml; $x++) {
                                        unset($konversiSpec["jml"]);
                                        unset($konversiSpec["qty"]);
                                        $data = $konversiSpec + array(
                                                "jml" => 1,
                                                "qty" => 1,
                                                "serial_number" => "",
                                                "produk_serial" => "",
//                                                "produk_sku" => $konversiSpec["produk_sku"],
                                                "produk_sku" => $konversiSpec["produk_kode"],
                                                "produk_sku_serial" => "",
                                                "produk_sku_part_id" => "",
//                                                "produk_sku_part_nama" => $konversiSpec["produk_sku"],
                                                "produk_sku_part_nama" => $konversiSpec["produk_kode"],
                                                "produk_sku_part_serial" => "",
                                                "transaksi_reference_dtime" => date("Y-m-d H:i:s"),
                                                "transaksi_reference_fulldate" => date("Y-m-d"),
                                                "transaksi_reference_count" => 1,
                                            );
                                        $_SESSION[$cCode]["items6_sum"][] = $data;
                                    }
                                }
                            }
                        }
                    }
                    //--------------------------
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