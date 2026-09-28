<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiReturnUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    public function __construct()
    {
        parent::__construct();
    }

    private $outFields = array( // dari tabel rek_cache
        "id",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "jenis",
    );

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $_preValue = $this->cekPreValue($this->inParams['static']['jenis'], $this->inParams['static']['cabang_id'], $defaultTransID);
            if ($_preValue != null) {
                $this->writeMode = "update";

                    $this->outParams[$lCounter]["returned"] = 1;

            } else {
                $this->writeMode = "none";
            }

            foreach ($this->inParams['static'] as $key => $value) {
                if (in_array($key, $this->outFields)) {
                    $this->outParams[$lCounter][$key] = $value;
                }
            }
        }

        if (sizeof($this->outParams) > 0) {
            return true;
        } else {
            return false;
        }
    }


    private function cekPreValue($jenis, $cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();


        $l->addFilter("id='$transaksiID'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            arrPrint($this->outParams);
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {
//                    case "new":
//                        $insertIDs[] = $l->addData($params);
//                        break;
//
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "jenis" => $params['jenis'],
                            "cabang_id" => $params['cabang_id'],
                            "id" => $params['id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru($this->db->last_query());
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            die("nothing to write down here");
            return false;
        }

    }
}