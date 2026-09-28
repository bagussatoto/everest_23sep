<?php


class PreSelisihKurs extends CI_Model
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

            cekHitam("cetak kiriman preprocc PreSelisihKurs");
            arrprint($inParams);

            $last_exchange = 0;
            $last_exchange_uang_muka_stock_valas = 0;
            $new_exchange = isset($inParams['static']['new_exchange']) ? $inParams['static']['new_exchange'] : 0;
            $cashMethodeOption = isset($inParams['static']['cashMethodeOption']) ? $inParams['static']['cashMethodeOption'] : 0;
            $additional = isset($inParams['static']['additional']) ? $inParams['static']['additional'] : 0;
            $additional_value = isset($inParams['static']['additional_value']) ? $inParams['static']['additional_value'] : 0;
            $nilai_entry = isset($inParams['static']['nilai_entry']) ? $inParams['static']['nilai_entry'] : 0;
            $uang_muka_stock_valas = isset($inParams['static']['uang_muka_stock_valas']) ? $inParams['static']['uang_muka_stock_valas'] : 0;
            $jenis_selisih_kurs = isset($inParams['static']['jenis']) ? $inParams['static']['jenis'] : NULL;
            $cCode = "_TR_" . $inParams['static']['jenisTr'];

            if ($jenis_selisih_kurs == "uang muka") {
                if (isset($_SESSION[$cCode]['items']) && (sizeof($_SESSION[$cCode]['items']) > 0)) {
                    foreach ($_SESSION[$cCode]['items'] as $iSpec) {

                        $last_exchange += $iSpec['nilai_bayar_valas'] * $iSpec['extern_nilai2'];

                        //-- mencari $last_exchange_uang_muka_stock_valas yang dipakai
                        $last_exchange_uang_muka_stock_valas += $iSpec['nilai_bayar_valas'] * $iSpec['extern_nilai2'];
                    }
                }
            }
            else {

                if (isset($_SESSION[$cCode]['items']) && (sizeof($_SESSION[$cCode]['items']) > 0)) {
                    foreach ($_SESSION[$cCode]['items'] as $iSpec) {

                        $last_exchange += $iSpec['valas_nilai_bayar'] * $iSpec['extern_nilai2'];

                        //-- mencari $last_exchange_uang_muka_stock_valas yang dipakai
//                        $last_exchange_uang_muka_stock_valas += $iSpec['uang_muka_stok_valas'] * $iSpec['extern_nilai2'];
                        $last_exchange_uang_muka_stock_valas += $iSpec['valas_nilai_bayar'] * $iSpec['extern_nilai2'];
                    }
                }
            }
            //--------------------------------------------
            $additional_value_valas = $uang_muka_stock_valas - $last_exchange_uang_muka_stock_valas;
            //--------------------------------------------
            cekMerah("lastExchnage: $last_exchange :: newExchange: $new_exchange");
            cekMerah("uang muka stok valas: $uang_muka_stock_valas || additional value valas: $additional_value_valas");
            //--------------------------------------------
            if ($additional == "-1") {// keutungan kurs
                $selisih_kurs = $additional_value * -1;
            }
            elseif ($additional == "1") {// kerugian kurs
                $selisih_kurs = $additional_value;
            }
            else { // draw
                $selisih_kurs = 0;
            }
            //--------------------------------------------
//            $selisih_kurs_total = $selisih_kurs + $additional_value_valas;
            $selisih_kurs_total_0 = ($selisih_kurs + $additional_value_valas);
            $selisih_kurs_total = ($selisih_kurs_total_0 != 0) ? ($selisih_kurs_total_0 - $nilai_entry) : 0;
            //--------------------------------------------
            cekMerah("selisih fifo: $additional_value_valas, selisih valas: $selisih_kurs, selisih kurs total: $selisih_kurs_total");

            if ($selisih_kurs_total > 0) {
                // masuk ke kerugian kurs
                $result = array(
                    "additional" => "1",
                    "add_jenis" => "kerugian kurs",
                    "additional_value_total" => $selisih_kurs_total,
                    "add_diskon_selisih_kurs" => $selisih_kurs_total * -1,
                );
            }
            elseif ($selisih_kurs_total < 0) {
                // masuk ke keuntungan kurs
                $result = array(
                    "additional" => "-1",
                    "add_jenis" => "keutungan kurs",
                    "additional_value_total" => $selisih_kurs_total * -1,
                    "add_diskon_selisih_kurs" => $selisih_kurs_total * -1,
                );
            }
            else {
                // masuk draw
                $result = array(
                    "additional" => "0",
                    "add_jenis" => "keutungan kurs",
                    "additional_value_total" => 0,
                    "add_diskon_selisih_kurs" => 0,
                );
            }

//                if ($selisih_exchange > 0) {
//                    // masuk ke kerugian kurs
//                    $result = array(
//                        "additional" => "1",
//                        "add_jenis" => "kerugian kurs",
//                        "additional_value_valas" => $selisih_exchange,
//                    );
//                }
//                elseif ($selisih_exchange < 0) {
//                    // masuk ke keuntungan kurs
//                    $result = array(
//                        "additional" => "-1",
//                        "add_jenis" => "keutungan kurs",
//                        "additional_value_valas" => $selisih_exchange * -1,
//                    );
//                }
//                else {
//                    // netral
//                    $result = array(
//                        "additional_value" => "0",
//                        "add_jenis" => "kerugian kurs",
//                        "additional_value_valas" => "0",
//                    );


//            arrPrintWebs($result);
//                mati_disini(get_class($this) . " :: $cCode :: $selisih_exchange ::");

            if (sizeof($result) > 0) {
                foreach ($result as $key => $val) {
                    $_SESSION[$cCode]['main'][$key] = $val;
                }
            }

//            }


        }

//        mati_disini(__LINE__);

        return true;
    }

    public function exec()
    {
//        return $this->result;
        return true;
    }
}


