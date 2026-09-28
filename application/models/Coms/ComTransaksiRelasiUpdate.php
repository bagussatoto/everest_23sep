<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiRelasiUpdate extends MdlMother
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

        "deskripsi",
        "trash_4",
        "cancel_dtime",
        "cancel_name",
        "cancel_id",

    );

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams['static']) > 0) {
            arrPrintPink($inParams);
            $lCounter = 0;
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $defaultReferenceID = isset($this->inParams['static']['referensi_id']) ? $this->inParams['static']['referensi_id'] : 0;
            $defaultNilai = isset($this->inParams['static']['status_inv']) ? $this->inParams['static']['status_inv'] : 0;
            $_preValue = $this->cekPreValue($this->inParams['static']['cabang_id'], $defaultReferenceID);
            if ($_preValue != null) {
                $this->writeMode = "update";
                $this->outParams[$lCounter]["status_inv"] = $defaultNilai;
                $this->outParams[$lCounter]["id"] = $_preValue;
                $this->outParams[$lCounter]["cancel_dtime"] = date("Y-m-d H:i:s");

            }
            else {
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
        }
        else {
            return false;
        }
    }


    private function cekPreValue($cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->addFilter("id='$transaksiID'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
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
//            arrPrint($this->outParams);
            cekMerah(" ---- " . $this->writeMode);
            foreach ($this->outParams as $ctr => $params) {
                $tbl_id = $params["id"];
                unset($params["id"]);

                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "id" => $tbl_id,
                        ), $params);
                        break;
                    case "none":
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
            die("nothing to write down here");
            return false;
        }

    }
}