<?php


class PreProdukSerialNumberExtractorOpname extends CI_Model
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

    // START OF COMPLETE REPEATED LOGIC
    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }

//        arrPrint($inParams);
        if (sizeof($inParams) > 0) {
            $jenisTr = $inParams["static"]["jenisTr"];
            $cabangID = $inParams["static"]["cabang_id"];
            $step_number = $inParams["static"]["step_number"];
            $cCode = "_TR_" . $jenisTr;
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
//            arrPrint($configUiMasterModulJenis);
            if (isset($configUiMasterModulJenis["steps"][$step_number]["allowScaner"]) && ($configUiMasterModulJenis["steps"][$step_number]["allowScaner"] == true)) {
                $settingScanerGrn = $configUiMasterModulJenis["steps"][$step_number]["allowScaner"];// harusnya membaca db setting modul pembeluan...
            }
            else {
                $settingScanerGrn = false;// harusnya membaca db setting modul pembeluan...
            }


            $_SESSION[$cCode]["items3_sum"] = array();

            if ($settingScanerGrn == true) {
                cekHitam("masuk disini, scan serial saat grn...");
                if (isset($_SESSION[$cCode]["items2"])) {
                    foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                        foreach ($spec as $produk_sku => $subSpec) {
                            $itemFlip = array_flip($_SESSION[$cCode]["items"][$produk_id]);
                            $key_data_arr = isset($itemFlip[$produk_sku]) ? explode("_", $itemFlip[$produk_sku]) : array("");
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
                                    "nama" => $_SESSION[$cCode]["items"][$produk_id]["nama"],
                                    "name" => $_SESSION[$cCode]["items"][$produk_id]["name"],
                                    "kategori_id" => $_SESSION[$cCode]["items"][$produk_id]["kategori_id"],
                                    "kategori_nama" => $_SESSION[$cCode]["items"][$produk_id]["kategori_nama"],
//                                    "jml" => $serialSpec["qty"],
//                                    "qty" => $serialSpec["qty"],
                                    "jml" => 1,
                                    "qty" => 1,
                                    "barcode" => $_SESSION[$cCode]["items"][$produk_id]["barcode"],
                                    "kode" => $_SESSION[$cCode]["items"][$produk_id]["kode"],
                                    "produk_kode" => $_SESSION[$cCode]["items"][$produk_id]["produk_kode"],
                                    "no_part" => $_SESSION[$cCode]["items"][$produk_id]["no_part"],
                                    "label" => $_SESSION[$cCode]["items"][$produk_id]["label"],
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
                cekHitam("masuk disini, tidak scan serial saat grn...");
                $processedProdukIds = array();
                if (isset($_SESSION[$cCode]["items2"])) {
                    foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                        // hanya bila nambah stok...
                        $jml_opname = isset($_SESSION[$cCode]["items"][$produk_id]["qty_debet"]) ? (int)$_SESSION[$cCode]["items"][$produk_id]["qty_debet"] : 0;
                        cekHere("jml_opname debet: $jml_opname");
                        if ($jml_opname > 0 && is_array($spec) && sizeof($spec) > 0) {
                            $hasGeneratedSpec = false;
                            foreach ($spec as $produk_sku => $subSpec) {
//                                $produk_sku = trim($produk_sku);
                                $jml_sku = isset($_SESSION[$cCode]["items"][$produk_id][$produk_sku]) ? (int)$_SESSION[$cCode]["items"][$produk_id][$produk_sku] : $jml_opname;
                                cekHere("jml_sku [$produk_sku] : $jml_sku");
                                $itemFlip = array_flip($_SESSION[$cCode]["items"][$produk_id]);

                                if (!isset($itemFlip[$produk_sku]) || $itemFlip[$produk_sku] == NULL) {
                                    $part_kode = "PART";
                                }
                                else {
                                    $key_data_arr = explode("_", $itemFlip[$produk_sku]);
                                    cekHitam("HAHAHA: " . $itemFlip[$produk_sku]);
                                    switch ($key_data_arr[0]) {
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
                                }
                                for ($ii = 1; $ii <= $jml_sku; $ii++) {
                                    $data = array(
                                        "id" => $produk_id,
                                        "nama" => isset($_SESSION[$cCode]["items"][$produk_id]["nama"]) ? $_SESSION[$cCode]["items"][$produk_id]["nama"] : "",
                                        "name" => isset($_SESSION[$cCode]["items"][$produk_id]["name"]) ? $_SESSION[$cCode]["items"][$produk_id]["name"] : "",
                                        "kategori_id" => isset($_SESSION[$cCode]["items"][$produk_id]["kategori_id"]) ? $_SESSION[$cCode]["items"][$produk_id]["kategori_id"] : 0,
                                        "kategori_nama" => isset($_SESSION[$cCode]["items"][$produk_id]["kategori_nama"]) ? $_SESSION[$cCode]["items"][$produk_id]["kategori_nama"] : "",
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => isset($_SESSION[$cCode]["items"][$produk_id]["barcode"]) ? $_SESSION[$cCode]["items"][$produk_id]["barcode"] : "",
                                        "kode" => isset($_SESSION[$cCode]["items"][$produk_id]["kode"]) ? $_SESSION[$cCode]["items"][$produk_id]["kode"] : "",
                                        "produk_kode" => isset($_SESSION[$cCode]["items"][$produk_id]["produk_kode"]) ? $_SESSION[$cCode]["items"][$produk_id]["produk_kode"] : "",
                                        "no_part" => isset($_SESSION[$cCode]["items"][$produk_id]["no_part"]) ? $_SESSION[$cCode]["items"][$produk_id]["no_part"] : "",
                                        "label" => isset($_SESSION[$cCode]["items"][$produk_id]["label"]) ? $_SESSION[$cCode]["items"][$produk_id]["label"] : "",
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
                                    $hasGeneratedSpec = true;
                                }
                            }
                            if ($hasGeneratedSpec) {
                                $processedProdukIds[$produk_id] = true;
                            }
                        }
                    }
                }

                // Fallback loop untuk produk berserial single-part / yang belum diproses di items2 di atas
                if (isset($_SESSION[$cCode]["items"]) && is_array($_SESSION[$cCode]["items"])) {
                    foreach ($_SESSION[$cCode]["items"] as $produk_id => $item) {
                        $jml_opname = isset($item["qty_debet"]) ? (int)$item["qty_debet"] : 0;
                        if ($jml_opname > 0 && !isset($processedProdukIds[$produk_id])) {
                            $jml_serial = isset($item["jml_serial"]) ? (int)$item["jml_serial"] : 0;
                            $scan_mode = isset($item["scan_mode"]) ? $item["scan_mode"] : "";
                            $kategori_nama = isset($item["kategori_nama"]) ? strtolower($item["kategori_nama"]) : "";
                            $is_serial = ($jml_serial > 0 || $scan_mode == "serial" || $kategori_nama == "unit");

                            if ($is_serial) {
                                $sku = (isset($item["kode"]) && strlen(trim($item["kode"])) > 0) ? trim($item["kode"]) : (isset($item["barcode"]) ? trim($item["barcode"]) : "");
                                for ($ii = 1; $ii <= $jml_opname; $ii++) {
                                    $data = array(
                                        "id" => $produk_id,
                                        "nama" => isset($item["nama"]) ? $item["nama"] : "",
                                        "name" => isset($item["name"]) ? $item["name"] : (isset($item["nama"]) ? $item["nama"] : ""),
                                        "kategori_id" => isset($item["kategori_id"]) ? $item["kategori_id"] : 0,
                                        "kategori_nama" => isset($item["kategori_nama"]) ? $item["kategori_nama"] : "",
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => isset($item["barcode"]) ? $item["barcode"] : "",
                                        "kode" => isset($item["kode"]) ? $item["kode"] : "",
                                        "produk_kode" => isset($item["produk_kode"]) ? $item["produk_kode"] : "",
                                        "no_part" => isset($item["no_part"]) ? $item["no_part"] : "",
                                        "label" => isset($item["label"]) ? $item["label"] : "",
                                        "serial_number" => "",
                                        "produk_serial" => "",
                                        "produk_sku" => $sku,
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $sku,
                                        "produk_sku_part_serial" => "",
                                        "part_keterangan" => "UNIT",
                                        //-----------
                                    );
                                    $_SESSION[$cCode]["items3_sum"][] = $data;
                                }
                                $processedProdukIds[$produk_id] = true;
                            }
                        }
                    }
                }
            }
        }
//        arrPrintPink($_SESSION[$cCode]["items3_sum"]);
//        mati_disini(__LINE__);
        return true;
    }
    // END OF COMPLETE REPEATED LOGIC

    public function exec()
    {
        return $this->result;
    }
}