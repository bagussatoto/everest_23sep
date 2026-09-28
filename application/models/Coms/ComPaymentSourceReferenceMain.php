<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentSourceReferenceMain extends MdlMother
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
        "tagihan_valas",
        "terbayar_valas",
        "returned_valas",
        "sisa_valas",
        "ppn",
        "ppn_approved",
        "ppn_sisa",
        "ppn_status",
        "ppn_pph_faktor",
        "extern_nilai2",
        "target_jenis",
        "reference_jenis",
        "dihapus",

    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams['static']) > 0) {
            arrPrintHijau($inParams);
//cekMerah(__LINE__);
            $lCounter = 0;
//            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $jenisTr = isset($this->inParams['static']['jenisTr']) ? $this->inParams['static']['jenisTr'] : NULL;
            $gateSource = isset($this->inParams['static']['gateSource']) ? $this->inParams['static']['gateSource'] : NULL;

//            matiHere($jenisTr . " | " . $gateSource);

            if ($jenisTr != NULL) {
                $cCode = "_TR_" . $jenisTr;
                if ($gateSource != NULL) {
                    $total_terbayar = $this->inParams['static']['terbayar'];
                    $total_terbayar_PPN = $this->inParams['static']['ppn_sisa'];
                    $sessionGate = $_SESSION[$cCode][$gateSource];// ini session main

//                    matiHere("sessionGate: $sessionGate");
//                    switch ($sessionGate) {// ini kok malah switch sesi ya? bukannya switch $gateSource? berbang main?
                    switch ($gateSource) {
                        case "main":
                            $referensi_po_id = $this->inParams['static']["referensi_po_id"];
                            $_preValue = $this->cekPreValue(
                                $this->inParams['static']['cabang_id'],
                                $this->inParams['static']['extern_id'],
                                $this->inParams['static']['label'],
                                $referensi_po_id
                            );
                            cekMerah($this->db->last_query());
                            foreach ($this->inParams['static'] as $key => $value) {
                                if (in_array($key, $this->outFields)) {
                                    $this->outParams[$lCounter][$key] = $value;
                                }
                            }
                            arrPrint($_preValue);
                            if ($_preValue != null) {
                                $this->writeMode = "update";
                                $this->outParams[$lCounter]["tbl_id"] = $_preValue["tbl_id"];
                                if ($total_terbayar >= $_preValue["sisa"]) {
                                    $outSisa = 0;
//                                    $outTerbayar = $_preValue["sisa"];
                                    $outTerbayar = $_preValue["terbayar"] + $total_terbayar;
                                    $pengurang = $_preValue["sisa"];

                                    $outSisaPPN = 0;
                                    $outTerbayarPPN = $_preValue["ppn_sisa"];
                                    $pengurangPPN = $_preValue["ppn_sisa"];
                                }
                                else {
                                    $outSisa = $_preValue["sisa"] - $total_terbayar;
                                    $outTerbayar = $_preValue["terbayar"] + $total_terbayar;
                                    $pengurang = $total_terbayar;

                                    $outSisaPPN = $_preValue["ppn_sisa"] - $total_terbayar_PPN;
                                    $outTerbayarPPN = $total_terbayar_PPN;
                                    $pengurangPPN = $total_terbayar_PPN;

                                }
                                $total_terbayar -= $pengurang;
                                $this->outParams[$lCounter]["sisa"] = $outSisa;
                                $this->outParams[$lCounter]["terbayar"] = $outTerbayar;

                                $total_terbayar_PPN -= $pengurangPPN;
                                $this->outParams[$lCounter]["ppn_sisa"] = $outSisaPPN;
                                $this->outParams[$lCounter]["ppn_approved"] = $outTerbayarPPN;

                            }
                            else {
                                //tidak diijinkan nulis payment source dari model. harus dari controler Transaksi letakkan di heTransaksi_misc
                                switch ($jenisTr) {
                                    case "466":
                                    case "467":
                                    case "749":
                                    case "7499":
                                        $this->writeMode = "skip";
                                        break;
                                    default:
                                        $this->writeMode = "skip";
//                                        matiHere("Gagal menyimpan transaksi, silahkan hubungi tim developer untuk melakukan pengecekan sistem. Error Code:  Com " . __LINE__);
//                                        $this->writeMode = "new";
                                        break;
                                }
                            }

                            break;
                        default:
                            foreach ($sessionGate as $ii => $iiSpec) {
                                if (isset($iiSpec["referensi_po_id"]) && ($iiSpec["referensi_po_id"] > 0)) {
                                    $referensi_po_id = $iiSpec["referensi_po_id"];
                                    $_preValue = $this->cekPreValue(
                                        $this->inParams['static']['cabang_id'],
                                        $this->inParams['static']['extern_id'],
                                        $this->inParams['static']['label'],
                                        $referensi_po_id
                                    );
                                    cekMerah($this->db->last_query());
                                    foreach ($this->inParams['static'] as $key => $value) {
                                        if (in_array($key, $this->outFields)) {
                                            $this->outParams[$lCounter][$key] = $value;
                                        }
                                    }
                                    arrPrint($_preValue);
                                    if ($_preValue != null) {
                                        $this->writeMode = "update";
                                        $this->outParams[$lCounter]["tbl_id"] = $_preValue["tbl_id"];
                                        if ($total_terbayar >= $_preValue["sisa"]) {
                                            $outSisa = 0;
                                            $outTerbayar = $_preValue["sisa"];
                                            $pengurang = $_preValue["sisa"];
                                        }
                                        else {
                                            $outSisa = $_preValue["sisa"] - $total_terbayar;
                                            $outTerbayar = $total_terbayar;
                                            $pengurang = $total_terbayar;
                                        }
                                        $total_terbayar -= $pengurang;
                                        $this->outParams[$lCounter]["sisa"] = $outSisa;
                                        $this->outParams[$lCounter]["terbayar"] = $outTerbayar;
                                    }
                                    else {
                                        //tidak diijinkan nulis payment source dari model. harus dari controler Transaksi letakkan di heTransaksi_misc
                                        matiHere("Gagal menyimpan transaksi, silahkan hubungi tim developer untuk melakukan pengecekan sistem. Error Code:  Com " . __LINE__);
                                        $this->writeMode = "new";
                                    }
                                }
                            }
                            break;
                    }
                }
            }
        }

//        arrPrintPink($this->outParams);
//        mati_disini(__LINE__);
        return true;
    }


    private function cekPreValue($cabang_id, $extern_id, $label, $referensi_po_id = 0)
    {

        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();
//        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("label='$label'");
        $l->addFilter("extern2_id='$referensi_po_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($l->getFilters()) > 0) {
            foreach ($l->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        showLast_query("biru");
        cekMerah(__LINE__);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "tbl_id" => $row->id,
                    "sisa" => $row->sisa,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "diskon" => $row->diskon,
                    "terbayar_valas" => $row->terbayar_valas,
                    "returned_valas" => $row->returned_valas,
                    "sisa_valas" => $row->sisa_valas,
                    "diskon_valas" => $row->diskon_valas,
                    "ppn_approved" => $row->ppn_approved,
                    "ppn_sisa" => $row->ppn_sisa,
                );
            }
        }
        else {
            $result = null;
        }


        return $result;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            $insertIDs = array();
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlPaymentSource");
                $l = new MdlPaymentSource();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $tbl_id = $params['tbl_id'];
                        unset($params['tbl_id']);
                        $insertIDs[] = $l->updateData(array(
//                            "jenis" => $params['jenis'],
//                            "cabang_id" => $params['cabang_id'],
//                            "extern_id" => $params['extern_id'],
//                            "label" => $params['label'],
//                            "transaksi_id" => $params['transaksi_id'],
                            "id" => $tbl_id,
                        ), $params);
                        break;
                    case "skip":
                        $insertIDs[] = 1;
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }
}

