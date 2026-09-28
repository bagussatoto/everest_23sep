<?php


class PreSyncDiskonPembelian extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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

    public function pair__OLD($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {
//            $patchers = array();
//            foreach ($inParams as $cCtr => $sentParams) {
//                foreach ($sentParams as $pSpec) {
//                    foreach ($this->resultParams as $gateName => $paramSpec) {
//                        foreach ($paramSpec as $key => $val) {
//                            $patchers[$gateName][$pSpec['extern_id_src']][$key] = $pSpec[$val];
//                        }
//                    }
//                }
//            }
//            arrPrintPink($inParams);

            //region cek ada yang diklai dari uang muka tidak?
            arrprint($inParams);
            $allow_preproc = false;
            if (isset($inParams["static"]['po_id'])) {
                $cekPreValue = $this->_prevalueDoskonRelasi($inParams["static"]['po_id'], $inParams["static"]['reference_id']);
                arrPrintKuning($cekPreValue);
                if ($cekPreValue["skip"] == 1) {

                }
                else {
                    $allow_preproc = true;
                }
            }
            else {
                $allow_preproc = true;
            }
            //endregion
            cekHitam("[allow_preproc: $allow_preproc]");
            if ($allow_preproc) {
                $arrDataDiskon = array();
                $this->load->model("Mdls/MdlSupplierDiskon");
                $sd = New MdlSupplierDiskon();
                $sd->addFilter("jenis='reguler'");
                $sdTmp = $sd->lookupAll()->result();
                foreach ($sdTmp as $sdSpec) {
                    $arrDataDiskon[$sdSpec->id] = array(
                        "id" => $sdSpec->id,
                        "nama" => $sdSpec->nama,
                        "coa_code" => $sdSpec->coa_code,
                    );
                }

                $cCode = "_TR_" . $inParams["static"]["jenisTrMaster"];

                $_SESSION[$cCode][$inParams["static"]["target"]] = array();// sudah benar, gerbang target items4_sum selalu direset/dikosongkan karena sesuai dengan GRN.
                $src_key = isset($inParams["static"]["source"]) ? $inParams["static"]["source"] : "items";
                $items = $_SESSION[$cCode][$src_key];
                foreach ($items as $pID => $iSpec) {
                    foreach ($arrDataDiskon as $iii => $iiiSpec) {
//                    arrPrintKuning($iiiSpec);
                        $key_nama = $iiiSpec["nama"];
                        $key_nama_cek = $iiiSpec["nama"] . "_id";
                        if (array_key_exists($key_nama_cek, $iSpec)) {
                            cekmerah("ada $key_nama_cek dibuatkan items4_sum");
                            $data4_sum = array(
                                "id" => $iSpec["id"],
                                "nama" => $iSpec["nama"],
                                "name" => $iSpec["name"],
                                "jml" => $iSpec["jml"],
                                "qty" => $iSpec["qty"],
                                "diskon_id" => $iSpec[$key_nama . "_id"],
                                "diskon_nama" => $iSpec[$key_nama . "_nama"],
                                "diskon_name" => $iSpec[$key_nama . "_nama"],
                                "diskon_persen" => $iSpec[$key_nama . "_persen"],
                                "diskon_nilai" => $iSpec[$key_nama . "_nilai"],
//                            "diskon_nilai" => "",
                            );
                            arrPrintHijau($data4_sum);
                            $_SESSION[$cCode][$inParams["static"]["target"]][] = $data4_sum;
                        }
                        else {
//                        cekhitam("tidak ada $key_nama_cek dibuatkan items4_sum");
                        }
                    }
                }
            }


//arrPrintHijau($_SESSION[$cCode][$inParams["static"]["target"]]);
            mati_disini(__LINE__);


//            $this->result = $patchers;
            return true;
//            if (sizeof($this->result) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }

        }
    }

    public function pair($master_id, $inParams)
    {

        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {

            //region cek ada yang diklai dari uang muka tidak?
            arrprint($inParams);
            $allow_preproc = false;
            if (isset($inParams["static"]['po_id'])) {
                $cekPreValue = $this->_prevalueDoskonRelasi($inParams["static"]['po_id'], $inParams["static"]['reference_id']);
//                arrPrintKuning($cekPreValue);
//                if ($cekPreValue["skip"] == 1) {
//
//                }
//                else {
//                    $allow_preproc = true;
//                }
                $allow_preproc = true;
            }
            else {
                $allow_preproc = true;
            }
            //endregion
            cekHitam("[allow_preproc: $allow_preproc]");
            if ($allow_preproc) {
                $arrDataDiskon = array();
                $this->load->model("Mdls/MdlSupplierDiskon");
                $sd = New MdlSupplierDiskon();
                $sd->addFilter("jenis='reguler'");
                $sdTmp = $sd->lookupAll()->result();
                foreach ($sdTmp as $sdSpec) {
                    $arrDataDiskon[$sdSpec->id] = array(
                        "id" => $sdSpec->id,
                        "nama" => $sdSpec->nama,
                        "coa_code" => $sdSpec->coa_code,
                    );
                }

                $cCode = "_TR_" . $inParams["static"]["jenisTrMaster"];
                arrPrintWebs($arrDataDiskon);

                $_SESSION[$cCode][$inParams["static"]["target"]] = array();// sudah benar, gerbang target items4_sum selalu direset/dikosongkan karena sesuai dengan GRN.
                $src_key = isset($inParams["static"]["source"]) ? $inParams["static"]["source"] : "items";
                $items = $_SESSION[$cCode][$src_key];

                if (isset($inParams["static"]['forceMode']) && ($inParams["static"]['forceMode'] == 1)) {
                    foreach ($items as $pID => $iSpec) {
                        foreach ($arrDataDiskon as $iii => $iiiSpec) {
//                    arrPrintKuning($iiiSpec);
                            $key_nama = $iiiSpec["nama"];
                            $key_nama_cek = $iiiSpec["nama"] . "_id";
                            if (array_key_exists($key_nama_cek, $iSpec)) {
                                cekmerah("ada $key_nama_cek dibuatkan items4_sum");
                                $data4_sum = array(
                                    "id" => $iSpec["id"],
                                    "nama" => $iSpec["nama"],
                                    "name" => $iSpec["name"],
                                    "jml" => $iSpec["jml"],
                                    "qty" => $iSpec["qty"],
                                    "diskon_id" => $iSpec[$key_nama . "_id"],
                                    "diskon_nama" => $iSpec[$key_nama . "_nama"],
                                    "diskon_name" => $iSpec[$key_nama . "_nama"],
                                    "diskon_persen" => $iSpec[$key_nama . "_persen"],
                                    "diskon_nilai" => $iSpec[$key_nama . "_nilai"],
//                            "diskon_nilai" => "",
                                );
                                arrPrintHijau($data4_sum);
                                $_SESSION[$cCode][$inParams["static"]["target"]][] = $data4_sum;
                            }
                            else {
//                        cekhitam("tidak ada $key_nama_cek dibuatkan items4_sum");
                            }
                        }
                    }
                }
                else {
                    foreach ($items as $pID => $iSpec) {
                        foreach ($arrDataDiskon as $iii => $iiiSpec) {
                            $key_nama = $iiiSpec["nama"];
                            $key_nama_cek = $iiiSpec["nama"] . "_id";
                            if (array_key_exists($key_nama_cek, $iSpec)) {
                                $diskon_key_id = $iSpec[$key_nama_cek];
                                if (isset($cekPreValue[$pID][$diskon_key_id])) {
                                    cekmerah("ada $key_nama_cek dibuatkan items4_sum");
                                    $data4_sum = array(
                                        "id" => $iSpec["id"],
                                        "nama" => $iSpec["nama"],
                                        "name" => $iSpec["name"],
                                        "jml" => $iSpec["jml"],
                                        "qty" => $iSpec["qty"],
                                        "diskon_id" => $iSpec[$key_nama . "_id"],
                                        "diskon_nama" => $iSpec[$key_nama . "_nama"],
                                        "diskon_name" => $iSpec[$key_nama . "_nama"],
                                        "diskon_persen" => $iSpec[$key_nama . "_persen"],
                                        "diskon_nilai" => $iSpec[$key_nama . "_nilai"],
//                            "diskon_nilai" => "",
                                    );
                                    arrPrintHijau($data4_sum);
                                    $_SESSION[$cCode][$inParams["static"]["target"]][] = $data4_sum;
                                }
                                else {
                                    cekHere("diskon: $diskon_key_id, sudah 0 di pre-locker diskon");
                                    $key_reset = "diskon_$diskon_key_id" . "_nilai";
                                    cekMerah("$key_reset = 0");
                                    $_SESSION[$cCode][$src_key][$pID][$key_reset] = 0;
                                    $_SESSION[$cCode][$src_key][$pID]["sub_" . $key_reset] = 0;
                                    $_SESSION[$cCode][$src_key][$pID]["diskon_pph23"] = 0;
                                    $_SESSION[$cCode][$src_key][$pID]["sub_diskon_pph23"] = 0;
                                }
                            }
//                        else {
////                        cekhitam("tidak ada $key_nama_cek dibuatkan items4_sum");
//                        }
//
                        }
                    }
                    foreach ($_SESSION[$cCode][$src_key] as $pID => $iSpec) {
                        $diskon_nilai_total = 0;
                        foreach ($arrDataDiskon as $iii => $iiiSpec) {
                            $diskon_id = $iiiSpec["id"];
                            $key_reset = "diskon_$diskon_id" . "_nilai";
                            $diskon_nilai_total += isset($iSpec[$key_reset]) ? $iSpec[$key_reset] : 0;
                        }
                        $_SESSION[$cCode][$src_key][$pID]["diskon_nilai_total"] = $diskon_nilai_total;
                        $_SESSION[$cCode][$src_key][$pID]["sub_diskon_nilai_total"] = $diskon_nilai_total * $iSpec["jml"];
                    }
                }
            }


//            arrPrintPink($_SESSION[$cCode][$inParams["static"]["target"]]);
//            mati_disini(__LINE__);


            return true;


        }
    }

    public function exec()
    {
        return $this->result;
    }

    public function _prevalueDoskonRelasi($po_id, $referenceID)
    {
        $this->load->model("Mdls/MdlLockerStockPreDiskonVendor");
        $m = new MdlLockerStockPreDiskonVendor();
        $m->addFilter("transaksi_id='$po_id'");
        $m->addFilter("reference_id='$referenceID'");
        $m->addFilter("nilai>'10'");
        $tmp = $m->lookUpAll()->result();
        showLast_query("biru");
        arrPrint($tmp);
        $result = array();
        if (count($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                $diskon_id = $tmpSpec->extern_id;
                $produk_id = $tmpSpec->extern2_id;
                $jumlah = $tmpSpec->jumlah;
                $nilai = $tmpSpec->nilai;
                $result[$produk_id][$diskon_id] = array(
                    "jumlah" => $jumlah,
                    "nilai" => $nilai,
                );
            }
        }
        else {
            $result = array();
        }

//        mati_disini(__LINE__);

        return $result;

    }
}