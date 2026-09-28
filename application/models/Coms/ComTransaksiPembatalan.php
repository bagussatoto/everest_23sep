<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComTransaksiPembatalan extends MdlMother
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

    private $outFields = array(

        "trash_4",
        "cancel_dtime",
        "cancel_id",
        "cancel_name",
        "cancel_transaksi_jenis",
        "cancel_transaksi_id",
        "cancel_transaksi_nomer",

    );

    public function pair($inParams)
    {

        $this->inParams = $inParams;
        if (sizeof($this->inParams['static']) > 0) {

            $lCounter = 0;
            $defaultJenis = isset($this->inParams['static']['jenis']) ? $this->inParams['static']['jenis'] : 0;// transaksi request pembatalan
            $defaultTransID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;// transaksi request pembatalan
            $defaultTransNomer = isset($this->inParams['static']['transaksi_no']) ? $this->inParams['static']['transaksi_no'] : 0;// transaksi request pembatalan
            $defaultReferenceID = isset($this->inParams['static']['referensi_id']) ? $this->inParams['static']['referensi_id'] : 0;// transaksi yang dibatalkan
            $defaultJml = isset($this->inParams['static']['jumlah']) ? $this->inParams['static']['jumlah'] : 0;// transaksi yang dibatalkan
            $olehID = isset($this->inParams['static']['oleh_id']) ? $this->inParams['static']['oleh_id'] : 0;// transaksi yang dibatalkan
            $olehName = isset($this->inParams['static']['oleh_nama']) ? $this->inParams['static']['oleh_nama'] : "";// transaksi yang dibatalkan
            $rejection = isset($this->inParams['static']['rejection']) ? $this->inParams['static']['rejection'] : 0;// transaksi yang dibatalkan

            $_preValue = $this->cekPreValue($this->inParams['static']['cabang_id'], $defaultReferenceID);

            if ($_preValue != null) {

                $this->writeMode = "update";
                $this->outParams[$lCounter]["id"] = $_preValue;
                $this->outParams[$lCounter]["cancel_dtime"] = date("Y-m-d H:i:s");
                $this->outParams[$lCounter]["cancel_id"] = $olehID;
                $this->outParams[$lCounter]["cancel_name"] = $olehName;
                $this->outParams[$lCounter]["cancel_transaksi_jenis"] = $defaultJenis;
                $this->outParams[$lCounter]["cancel_transaksi_id"] = $defaultTransID;
                $this->outParams[$lCounter]["cancel_transaksi_nomer"] = $defaultTransNomer;
                $this->outParams[$lCounter]["trash_4"] = $defaultJml;

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
//        $l->addFilter("jenis='$jenis'");
//        $l->addFilter("cabang_id='$cabang_id'");

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
            foreach ($this->outParams as $ctr => $params) {
                arrPrintCyan($params);
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