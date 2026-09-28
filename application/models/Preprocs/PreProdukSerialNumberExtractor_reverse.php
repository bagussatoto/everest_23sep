<?php


class PreProdukSerialNumberExtractor_reverse extends CI_Model
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
        $pakai_ini = 0;
        if($pakai_ini == 1){

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
//            mati_disini(__LINE__ . ", scanner: $settingScanerGrn");
                cekMerah(__LINE__ . ", scanner: $settingScanerGrn");
                $_SESSION[$cCode]["items3_sum"] = array();

                if ($settingScanerGrn == true) {
                    cekHitam("masuk disini, scan serial saat grn...");
                    if (isset($_SESSION[$cCode]["items2"])) {
                        foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                            foreach ($spec as $produk_sku => $subSpec) {
                                $itemFlip = array_flip($_SESSION[$cCode]["items"][$produk_id]);
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
                                        "produk_sku" => $serialSpec["sku"],
                                        "produk_sku_serial" => $serialSpec["sku_serial"],
                                        "produk_sku_part_id" => $serialSpec["produk_sku_part_id"],
                                        "produk_sku_part_nama" => $produk_sku,
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
                    if (isset($_SESSION[$cCode]["items2"])) {
                        foreach ($_SESSION[$cCode]["items2"] as $produk_id => $spec) {
                            foreach ($spec as $produk_sku => $subSpec) {
                                $jml_sku = $_SESSION[$cCode]["items"][$produk_id][$produk_sku];
                                $itemFlip = array_flip($_SESSION[$cCode]["items"][$produk_id]);
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
                                for ($ii = 1; $ii <= $jml_sku; $ii++) {
                                    $data = array(
                                        "id" => $produk_id,
                                        "nama" => $_SESSION[$cCode]["items"][$produk_id]["nama"],
                                        "name" => $_SESSION[$cCode]["items"][$produk_id]["name"],
                                        "kategori_id" => $_SESSION[$cCode]["items"][$produk_id]["kategori_id"],
                                        "kategori_nama" => $_SESSION[$cCode]["items"][$produk_id]["kategori_nama"],
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => $_SESSION[$cCode]["items"][$produk_id]["barcode"],
                                        "kode" => $_SESSION[$cCode]["items"][$produk_id]["kode"],
                                        "produk_kode" => $_SESSION[$cCode]["items"][$produk_id]["produk_kode"],
                                        "no_part" => $_SESSION[$cCode]["items"][$produk_id]["no_part"],
                                        "label" => $_SESSION[$cCode]["items"][$produk_id]["label"],
                                        "serial_number" => "",
                                        "produk_serial" => "",
                                        "produk_sku" => $produk_sku,
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $produk_sku,
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
//        arrPrintPink($_SESSION[$cCode]["items3_sum"]);
//        mati_disini(__LINE__);

        }
        return true;
    }

    public function exec()
    {
        return $this->result;
    }
}