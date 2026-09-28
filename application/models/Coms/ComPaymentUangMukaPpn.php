<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentUangMukaPpn extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "tagihan",
        "terbayar",
        "returned",
        "sisa",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "extern2_id",
        "extern2_nama",
        "transaksi_id",
        "jenis",
        "label",
        "extern_label2",
    );

    public function __construct()
    {
        parent::__construct();
        $this->jenisBlacklist = array("9911", "9912");
    }

    public function pair($inParams)
    {
        arrPrintWebs($inParams);
        $this->inParams = $inParams;

        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $uangMukaDipakai = isset($this->inParams['static']['terbayar']) ? $this->inParams['static']['terbayar'] : 0;
            $defaultTransNomer = isset($this->inParams['static']['transaksi_no']) ? $this->inParams['static']['transaksi_no'] : 0;
            cekHitam("nomer: $defaultTransNomer");
            $uangMukaTambah = isset($this->inParams['static']['tambah']) ? $this->inParams['static']['tambah'] : 0;

//            matiHere($uangMukaDipakai);
            if ($uangMukaDipakai > 0) {

                $_preValue = $this->cekPreValue(
                    $this->inParams['static']['jenis'],
                    $this->inParams['static']['cabang_id'],
                    $this->inParams['static']['extern_id'],
                    $this->inParams['static']['label'],
                    $defaultTransID,
                    $this->inParams['static']['extern_label2'],
                    $this->inParams['static']['extern2_id']
                );


// arrPrint($_preValue);
//                 matiHere($this->db->last_query());
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
// cekHitam("sini");
                if ($_preValue != null) {
                    $this->writeMode = "update";
                    $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                    if (isset($this->inParams['static']['terbayar'])) {
                        $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['terbayar']);
                        $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $this->inParams['static']['terbayar']);
                    }
                    elseif (isset($this->inParams['static']['returned'])) {
                        $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['returned']);
                        $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $this->inParams['static']['returned'];
                    }
                }
            }
            else {
                $defaultTransNomer_ex = explode(".", $defaultTransNomer);
//                arrPrint($defaultTransNomer_ex);

                $jenisTr_ini = $defaultTransNomer_ex[0];
                if (!in_array($jenisTr_ini, $this->jenisBlacklist)) {
                    cekMerah("MASUK DISINI");
                    // tidak menggunakan uang muka, diberikan outParams supaya bisa jalan normal
                    $this->writeMode = "skip";
                    foreach ($this->inParams['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    if (isset($this->inParams['static']['tambah']) && ($this->inParams['static']['tambah'] > 0)) {
                        $_preValue = $this->cekPreValue(
                            $this->inParams['static']['jenis'],
                            $this->inParams['static']['cabang_id'],
                            $this->inParams['static']['extern_id'],
                            $this->inParams['static']['label'],
                            $defaultTransID,
                            $this->inParams['static']['extern_label2']
                        );
//                        arrPrintKuning($_preValue);
                        if ($_preValue != NULL) {
                            $this->writeMode = "update";
                            $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $this->inParams['static']['tambah']);
                            $this->outParams[$lCounter]["tagihan"] = ($_preValue["tagihan"] + $this->inParams['static']['tambah']);
                        }
                        else {
                            $this->writeMode = "new";
                            $this->outParams[$lCounter]["sisa"] = ($this->inParams['static']['tambah']);
                            $this->outParams[$lCounter]["tagihan"] = ($this->inParams['static']['tambah']);
                        }

                        foreach ($this->inParams['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParams[$lCounter][$key] = $value;
                            }
                        }
                    }
                }
                else {

                    $_preValue = $this->cekPreValue(
                        $this->inParams['static']['jenis'],
                        $this->inParams['static']['cabang_id'],
                        $this->inParams['static']['extern_id'],
                        $this->inParams['static']['label'],
                        $defaultTransID,
                        $this->inParams['static']['extern_label2']
                    );
//                    arrPrint($_preValue);
//                    arrprint($this->jenisBlacklist);
////                    matiHere($uangMukaDipakai);
//                    cekMerah($this->inParams['static']['terbayar']);
//                     matiHere($this->db->last_query());

//                    foreach ($this->inParams['static'] as $key => $value) {
//                        if (in_array($key, $this->outFields)) {
//                            $this->outParams[$lCounter][$key] = $value;
//                        }
//                    }

                    if ($_preValue != null) {
                        foreach ($this->inParams['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParams[$lCounter][$key] = $value;
                            }
                        }

                        $this->writeMode = "update";
                        $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                        if (isset($this->inParams['static']['terbayar'])) {
                            if ($this->inParams['static']['terbayar'] > 0) {
                                $inparam_terbayar = $this->inParams['static']['terbayar'];
                            }
                            else {
                                $inparam_terbayar = $this->inParams['static']['terbayar'] * -1;
                            }
                            $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $inparam_terbayar);
                            $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $inparam_terbayar);
                        }
                        elseif (isset($this->inParams['static']['returned'])) {
                            $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['returned']);
                            $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $this->inParams['static']['returned'];
                        }
                        elseif (isset($this->inParams['static']['tambah'])) {
                            $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $this->inParams['static']['tambah']);
                        }
                    }
                    else {
                        cekHitam("tidak ada uang muka yang digunakan saat ar");
                        if (isset($this->inParams['static']['tambah'])) {
                            $this->writeMode = "new";
                            $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $this->inParams['static']['tambah']);
                            foreach ($this->inParams['static'] as $key => $value) {
                                if (in_array($key, $this->outFields)) {
                                    $this->outParams[$lCounter][$key] = $value;
                                }
                            }
                        }
                    }
                }

            }
//            arrPrintPink($this->outParams);
//            cekHitam("write mode: " . $this->writeMode);
        }
//        mati_disini(__LINE__);
        return true;

    }


    private function cekPreValue($jenis, $cabang_id, $extern_id, $label, $transaksiID = 0, $extern_label2, $extern2_id)
    {

        $this->load->model("Mdls/MdlPaymentUangMuka");
        $l = new MdlPaymentUangMuka();


//        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("extern2_id='$extern2_id'");
        $l->addFilter("extern_label2='$extern_label2'");
//        $l->addFilter("label='$label'");
//        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));
//        matiHere(__LINE__);

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                );
            }
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
//        arrPrintWebs($this->outParams);
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlPaymentUangMuka");
                $l = new MdlPaymentUangMuka();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $tbl_id = $params['tbl_id'];
                        unset($params['tbl_id']);
                        $insertIDs[] = $l->updateData(array(
//                            "cabang_id" => $params['cabang_id'],
//                            "extern_id" => $params['extern_id'],
//                            "extern_label2" => $params['extern_label2'],
//
                            "id" => $tbl_id,
                        ), $params);
//                        showLast_query("orange");
                        break;
                    case "skip":
                        cekHitam("proses ini di skip ya");
                        return true;

                        break;
                    default:
                        matiHere("unknown writemode on exec uang muka  Error code E  " . __LINE__ . ". Silahkan Hubungi tim developer untuk pengecekan");
                        break;
                }
                cekPink($this->db->last_query());
            }
            // matiHere("888");
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
//                return false;
                return true;
            }

        }
        else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }
}