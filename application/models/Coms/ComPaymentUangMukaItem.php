<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentUangMukaItem extends MdlMother
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
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {

            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {

                    $lCounter++;
                    $defaultTransID = isset($inSpec['static']['transaksi_id']) ? $inSpec['static']['transaksi_id'] : 0;
                    $uangMukaDipakai = isset($inSpec['static']['terbayar']) ? $inSpec['static']['terbayar'] : 0;
                    $uangMukaDibatalkan = isset($this->inParams['static']['returned']) ? $this->inParams['static']['returned'] : 0;
                    $defaultTransNomer = isset($inSpec['static']['transaksi_no']) ? $inSpec['static']['transaksi_no'] : 0;
                    cekHitam("nomer: $defaultTransNomer");

                    if ($uangMukaDipakai > 0) {
                        $_preValue = $this->cekPreValue(
                            $inSpec['static']['jenis'],
                            $inSpec['static']['cabang_id'],
                            $inSpec['static']['extern_id'],
                            $inSpec['static']['label'],
                            $defaultTransID,
                            $inSpec['static']['extern_label2']
                        );
                        // matiHere($this->db->last_query());
                        foreach ($inSpec['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParams[$lCounter][$key] = $value;
                            }
                        }
                        if ($_preValue != null) {
//                            $this->writeMode = "update";
                            $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                            $this->outParams[$lCounter]["mode"] = "update";
                            if (isset($inSpec['static']['terbayar'])) {
                                $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $inSpec['static']['terbayar']);
                                $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $inSpec['static']['terbayar']);
                            }
                            elseif (isset($inSpec['static']['returned'])) {
                                $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $inSpec['static']['returned']);
                                $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $inSpec['static']['returned'];
                            }
                        }
                    }
                    elseif ($uangMukaDibatalkan > 0) {
                        $_preValue = $this->cekPreValue(
                            $this->inParams['static']['jenis'],
                            $this->inParams['static']['cabang_id'],
                            $this->inParams['static']['extern_id'],
                            $this->inParams['static']['label'],
                            $defaultTransID,
                            $this->inParams['static']['extern_label2']
//                            $this->inParams['static']['extern2_id']
                        );
                        foreach ($this->inParams['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParams[$lCounter][$key] = $value;
                            }
                        }
                        if ($_preValue != null) {
                            $this->writeMode = "update";
                            $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                            if (isset($this->inParams['static']['returned'])) {
                                $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['returned']);
                                $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $this->inParams['static']['returned'];
                            }
                        }
                        else {
                            cekHitam("hasilnya NULL");
                        }
                    }
                    else {
                        $defaultTransNomer_ex = explode(".", $defaultTransNomer);
                        $jenisTr_ini = $defaultTransNomer_ex[0];
                        if (!in_array($jenisTr_ini, $this->jenisBlacklist)) {

                            // tidak menggunakan uang muka, diberikan outParams supaya bisa jalan normal
                            $this->writeMode = "skip";
                            foreach ($inSpec['static'] as $key => $value) {
                                if (in_array($key, $this->outFields)) {
                                    $this->outParams[$lCounter][$key] = $value;
                                }
                            }
                            if (isset($inSpec['static']['tambah'])) {
                                $_preValue = $this->cekPreValue(
                                    $inSpec['static']['jenis'],
                                    $inSpec['static']['cabang_id'],
                                    $inSpec['static']['extern_id'],
                                    $inSpec['static']['label'],
                                    $defaultTransID,
                                    $inSpec['static']['extern_label2']
                                );
                                foreach ($inSpec['static'] as $key => $value) {
                                    if (in_array($key, $this->outFields)) {
                                        $this->outParams[$lCounter][$key] = $value;
                                    }
                                }
                                if ($_preValue != null) {
//                                    $this->writeMode = "new";
                                    $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                                    $this->outParams[$lCounter]["mode"] = "update";
                                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $inSpec['static']['tambah']);
                                    $this->outParams[$lCounter]["tagihan"] = ($_preValue["tagihan"] + $inSpec['static']['tambah']);
                                }
                                else {
//                                    $this->writeMode = "new";
                                    $this->outParams[$lCounter]["mode"] = "new";
                                    $this->outParams[$lCounter]["sisa"] = $inSpec['static']['tambah'];
                                    $this->outParams[$lCounter]["tagihan"] = $inSpec['static']['tambah'];
                                }

                            }
                        }
                        else {
                            $_preValue = $this->cekPreValue(
                                $inSpec['static']['jenis'],
                                $inSpec['static']['cabang_id'],
                                $inSpec['static']['extern_id'],
                                $inSpec['static']['label'],
                                $defaultTransID,
                                $inSpec['static']['extern_label2']
                            );
                            // matiHere($this->db->last_query());
                            if ($_preValue != null) {
                                foreach ($inSpec['static'] as $key => $value) {
                                    if (in_array($key, $this->outFields)) {
                                        $this->outParams[$lCounter][$key] = $value;
                                    }
                                }

//                                $this->writeMode = "update";
                                $this->outParams[$lCounter]["tbl_id"] = $_preValue["id"];
                                $this->outParams[$lCounter]["mode"] = "update";
                                if (isset($inSpec['static']['terbayar'])) {
                                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $inSpec['static']['terbayar']);
                                    $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $inSpec['static']['terbayar']);
                                }
                                elseif (isset($inSpec['static']['returned'])) {
                                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $inSpec['static']['returned']);
                                    $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $inSpec['static']['returned'];
                                }
                                elseif (isset($inSpec['static']['tambah'])) {
                                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $inSpec['static']['tambah']);
                                }
                            }
                            else {
                                cekHitam("tidak ada uang muka yang digunakan saat ar");
                                if (isset($inSpec['static']['tambah'])) {
//                                    $this->writeMode = "new";
                                    $this->outParams[$lCounter]["mode"] = "new";
                                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $inSpec['static']['tambah']);
                                    foreach ($inSpec['static'] as $key => $value) {
                                        if (in_array($key, $this->outFields)) {
                                            $this->outParams[$lCounter][$key] = $value;
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
            }

        }
//arrPrintKuning($this->outParams);
//mati_disini(__LINE__);
        return true;

    }


    private function cekPreValue($jenis, $cabang_id, $extern_id, $label, $transaksiID = 0, $extern_label2)
    {

        $this->load->model("Mdls/MdlPaymentUangMuka");
        $l = new MdlPaymentUangMuka();


//        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("extern_label2='$extern_label2'");
//        $l->addFilter("label='$label'");
//        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
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
//                switch ($this->writeMode) {
                $mode = $params["mode"];
                unset($params["mode"]);
                switch ($mode) {
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
                            "id" => $tbl_id,
                        ),
                            $params);
//                        showLast_query("orange");
                        break;
                    case "skip":
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