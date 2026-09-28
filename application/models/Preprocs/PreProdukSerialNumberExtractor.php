<?php


class PreProdukSerialNumberExtractor extends CI_Model
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
            $jenisTr = $inParams["static"]["jenisTr"];
            $cabangID = $inParams["static"]["cabang_id"];
            $step_number = $inParams["static"]["step_number"];
            $gateItems = isset($inParams["static"]["gate_source"]) ? $inParams["static"]["gate_source"] : "items";
            $cCode = "_TR_" . $jenisTr;
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
//            arrPrint($configUiMasterModulJenis);
            if (isset($configUiMasterModulJenis["steps"][$step_number]["allowScaner"]) && ($configUiMasterModulJenis["steps"][$step_number]["allowScaner"] == true)) {
                $settingScanerGrn = $configUiMasterModulJenis["steps"][$step_number]["allowScaner"];// harusnya membaca db setting modul pembeluan...
            }
            else {
                $settingScanerGrn = false;// harusnya membaca db setting modul pembeluan...

                // ini untuk penjualan dengan pengiriman langsung dari pabrik, pakai nomer serial pabrik
                if (isset($inParams["static"]["request_reference_so_id"]) && ($inParams["static"]["request_reference_so_id"] > 0)) {
                    $settingScanerGrn = true;
                }
            }
//            mati_disini(__LINE__ . ", scanner: $settingScanerGrn");
//            cekMerah(__LINE__ . ", scanner: $settingScanerGrn");
            $_SESSION[$cCode]["items3_sum"] = array();

            if ($settingScanerGrn == true) {
                cekHitam("masuk disini, scan serial saat grn...");
                if (isset($_SESSION[$cCode]["items2"])) {
                    foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                        foreach ($spec as $produk_sku => $subSpec) {
                            $itemFlip = array_flip($_SESSION[$cCode][$gateItems][$produk_id]);

                            if ($produk_sku === NULL) {
                                $msg = "SKU Barang/Produk tidak dikenali. Silahkan periksa Data Barang/Produk anda. code: " . __LINE__;
                                mati_disini($msg);
                            }

                            if (isset($inParams["static"]["request_reference_so_id"]) && ($inParams["static"]["request_reference_so_id"] > 0)) {
                                if (sizeof($subSpec) == 0) {
                                    $produk_nama = $itemFlip["nama"];
                                    $msg = "Nomer serial Barang/Produk $produk_nama dengan SKU $produk_sku dari pabrik belum diinput. Silahkan dikoreksi dahulu. code: " . __LINE__;
                                    mati_disini($msg);
                                }
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
                            foreach ($subSpec as $serial_number => $serialSpec) {
                                $data = array(
                                    "id" => $produk_id,
                                    "nama" => $_SESSION[$cCode][$gateItems][$produk_id]["nama"],
                                    "name" => $_SESSION[$cCode][$gateItems][$produk_id]["name"],
                                    "kategori_id" => $_SESSION[$cCode][$gateItems][$produk_id]["kategori_id"],
                                    "kategori_nama" => $_SESSION[$cCode][$gateItems][$produk_id]["kategori_nama"],
//                                    "jml" => $serialSpec["qty"],
//                                    "qty" => $serialSpec["qty"],
                                    "jml" => 1,
                                    "qty" => 1,
                                    "barcode" => $_SESSION[$cCode][$gateItems][$produk_id]["barcode"],
                                    "kode" => $_SESSION[$cCode][$gateItems][$produk_id]["kode"],
                                    "produk_kode" => $_SESSION[$cCode][$gateItems][$produk_id]["produk_kode"],
                                    "no_part" => $_SESSION[$cCode][$gateItems][$produk_id]["no_part"],
                                    "label" => $_SESSION[$cCode][$gateItems][$produk_id]["label"],
                                    "serial_number" => $serialSpec["serial"],
                                    "produk_serial" => $serialSpec["serial"],
                                    "produk_sku" => trim($serialSpec["sku"]),
                                    "produk_sku_serial" => $serialSpec["sku_serial"],
                                    "produk_sku_part_id" => $serialSpec["produk_sku_part_id"],
                                    "produk_sku_part_nama" => trim($produk_sku),
                                    "produk_sku_part_serial" => $serialSpec["sku_part_serial"],
                                    "part_keterangan" => $part_kode,
                                    //-----------

                                );
                                $_SESSION[$cCode]["items3_sum"][] = $data;
                            }
                        }
                    }
                }
            }
            else {
//                cekHitam("masuk disini, tidak scan serial saat grn...".$gateItems);
//                arrPrint($_SESSION[$cCode]["items2"]);
                if (isset($_SESSION[$cCode]["items2"])) {
                    foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                        foreach ($spec as $produk_sku => $subSpec) {
//                            $produk_sku = trim($produk_sku);
                            $jml_sku = $_SESSION[$cCode][$gateItems][$produk_id][$produk_sku];
                            $jml_serial = $_SESSION[$cCode][$gateItems][$produk_id]["jml_serial"];
                            $itemFlip = array_flip($_SESSION[$cCode][$gateItems][$produk_id]);

                            cekHitam("[$gateItems] [$produk_sku] [$jml_sku] HAHAHA: " . $itemFlip[$produk_sku]);

                            if ($itemFlip[$produk_sku] == NULL) {
                                $pNama = $_SESSION[$cCode][$gateItems][$produk_id]["nama"];
                                $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                mati_disini($msg);
                            }

                            $key_data_arr = explode("_", $itemFlip[$produk_sku]);
                            $key = $key_data_arr[0] == "sub" ? "1" : "0";
//                            cekLime($key);
//                            arrprint($key_data_arr);
//                            cekHitam($key_data_arr[$key]);
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
                                for ($ii = 1; $ii <= $jml_sku; $ii++) {
                                    $data = array(
                                        "id" => $produk_id,
                                        "nama" => $_SESSION[$cCode][$gateItems][$produk_id]["nama"],
                                        "name" => $_SESSION[$cCode][$gateItems][$produk_id]["name"],
                                        "kategori_id" => $_SESSION[$cCode][$gateItems][$produk_id]["kategori_id"],
                                        "kategori_nama" => $_SESSION[$cCode][$gateItems][$produk_id]["kategori_nama"],
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => $_SESSION[$cCode][$gateItems][$produk_id]["barcode"],
                                        "kode" => $_SESSION[$cCode][$gateItems][$produk_id]["kode"],
                                        "produk_kode" => $_SESSION[$cCode][$gateItems][$produk_id]["produk_kode"],
                                        "no_part" => $_SESSION[$cCode][$gateItems][$produk_id]["no_part"],
                                        "label" => $_SESSION[$cCode][$gateItems][$produk_id]["label"],
                                        "serial_number" => "",
                                        "produk_serial" => "",
                                        "produk_sku" => trim($produk_sku),
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => trim($produk_sku),
                                        "produk_sku_part_serial" => "",
                                        "part_keterangan" => $part_kode,
                                        //-----------
                                    );
                                    $_SESSION[$cCode]["items3_sum"][] = $data;

                                }
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
