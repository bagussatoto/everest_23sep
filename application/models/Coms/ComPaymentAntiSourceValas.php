<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentAntiSourceValas extends MdlMother
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
        "valas_id",
        "valas_nama",
        "target_jenis",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;

        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $valasID = isset($this->inParams['static']['valas_id']) ? $this->inParams['static']['valas_id'] : 0;
            $targetJenis = isset($this->inParams['static']['target_jenis']) ? $this->inParams['static']['target_jenis'] : 0;
            $_preValue = $this->cekPreValue($this->inParams['static']['jenis'], $this->inParams['static']['cabang_id'], $this->inParams['static']['extern_id'], $this->inParams['static']['label'], $defaultTransID, $valasID, $targetJenis);


            foreach ($this->inParams['static'] as $key => $value) {
                if (in_array($key, $this->outFields)) {
                    $this->outParams[$lCounter][$key] = $value;
                }
            }

            if ($_preValue != null) {
                $this->outParams[$lCounter]["mode"] = "update";
                if (isset($this->inParams['static']['terbayar'])) {
                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['terbayar']);
                    $this->outParams[$lCounter]["terbayar"] = ($_preValue["terbayar"] + $this->inParams['static']['terbayar']);
                }
                elseif (isset($this->inParams['static']['returned'])) {
                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] - $this->inParams['static']['returned']);
                    $this->outParams[$lCounter]["returned"] = $_preValue['returned'] + $this->inParams['static']['returned'];
                }
                elseif (isset($this->inParams['static']['sisa'])) {
                    $this->outParams[$lCounter]["tagihan"] = ($_preValue["tagihan"] + $this->inParams['static']['sisa']);
                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $this->inParams['static']['sisa']);
                }

                //-----------------------------------------------
                if (isset($this->inParams['static']['terbayar_valas'])) {
                    $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] - $this->inParams['static']['terbayar_valas']);
                    $this->outParams[$lCounter]["terbayar_valas"] = ($_preValue["terbayar_valas"] + $this->inParams['static']['terbayar_valas']);
                }
                elseif (isset($this->inParams['static']['returned_valas'])) {
                    $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] - $this->inParams['static']['returned_valas']);
                    $this->outParams[$lCounter]["returned_valas"] = $_preValue['returned_valas'] + $this->inParams['static']['returned_valas'];
                }
                elseif (isset($this->inParams['static']['sisa_valas'])) {
                    $this->outParams[$lCounter]["tagihan_valas"] = ($_preValue["tagihan_valas"] + $this->inParams['static']['sisa_valas']);
                    $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] + $this->inParams['static']['sisa_valas']);
                }


                //-----------------------------------------------
                if (($this->inParams['static']['terbayar'] != 0) || ($this->inParams['static']['returned'] != 0)) {
                    if ($this->outParams[$lCounter]["sisa"] == $_preValue["sisa"]) {
                        $msg = "Transaksi gagal, karena tagihan tidak berkurang. Silahkan diperiksa kembali transaksi ini.";
                        die(lgShowAlert($msg));
                    }
                }
            }
            else {
                $this->outParams[$lCounter]["mode"] = "new";
                if (isset($this->inParams['static']['sisa'])) {
                    $this->outParams[$lCounter]["tagihan"] = ($_preValue["tagihan"] + $this->inParams['static']['sisa']);
                    $this->outParams[$lCounter]["sisa"] = ($_preValue["sisa"] + $this->inParams['static']['sisa']);
                }
                if (isset($this->inParams['static']['sisa_valas'])) {
                    $this->outParams[$lCounter]["tagihan_valas"] = ($_preValue["tagihan_valas"] + $this->inParams['static']['sisa_valas']);
                    $this->outParams[$lCounter]["sisa_valas"] = ($_preValue["sisa_valas"] + $this->inParams['static']['sisa_valas']);
                }
            }


        }
        return true;

    }


    private function cekPreValue($jenis, $cabang_id, $extern_id, $label, $transaksiID = 0, $valasID, $targetJenis)
    {

        $this->load->model("Mdls/MdlPaymentAntiSource");
        $l = new MdlPaymentAntiSource();


        $l->addFilter("target_jenis='$targetJenis'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("label='$label'");
        $l->addFilter("transaksi_id='$transaksiID'");
        $l->addFilter("valas_id='$valasID'");
//
//        $tmp = $l->lookupAll()->result();
//        cekMerah($this->db->last_query() . " # " . count($tmp));
//
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

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "terbayar" => $row->terbayar,
                    "returned" => $row->returned,
                    "tagihan_valas" => $row->tagihan_valas,
                    "sisa_valas" => $row->sisa_valas,
                    "terbayar_valas" => $row->terbayar_valas,
                    "returned_valas" => $row->returned_valas,
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
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $mode = $params['mode'];
                unset($params['mode']);

                $this->load->model("Mdls/MdlPaymentAntiSource");
                $l = new MdlPaymentAntiSource();
                $insertIDs = array();
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        showLast_query("hijau");
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
//                            "jenis" => $params['jenis'],
                            "cabang_id" => $params['cabang_id'],
                            "extern_id" => $params['extern_id'],
                            "label" => $params['label'],
//                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        showLast_query("orange");
                        break;
                    default:
//                        die("unknown writemode!");
                        break;
                }
//                cekBiru($this->db->last_query());
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
//                return false;
                return true;
            }

        }
        else {
            die("nothing to write down here");
//            return false;
            return true;
        }

    }
}