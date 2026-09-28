<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPaymentAntiSource extends MdlMother
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
            $_preValue = $this->cekPreValue($this->inParams['static']['jenis'], $this->inParams['static']['cabang_id'], $this->inParams['static']['extern_id'], $this->inParams['static']['label'], $defaultTransID);


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
            }


        }
        return true;

    }


    private function cekPreValue($jenis, $cabang_id, $extern_id, $label, $transaksiID = 0)
    {

        $this->load->model("Mdls/MdlPaymentAntiSource");
        $l = new MdlPaymentAntiSource();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("extern_id='$extern_id'");
        $l->addFilter("label='$label'");
        $l->addFilter("transaksi_id='$transaksiID'");
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