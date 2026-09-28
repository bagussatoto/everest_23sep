<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerSupplier extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//                                "jenis",
                                "produk_id",
                                "cabang_id",
                                "nama",
                                "satuan",
                                "state",
                                "nilai",
                                "oleh_id",
                                "oleh_nama",
                                "transaksi_id",
                                "nomer",

    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli_x) {
                $paramAsli = $this->inParams;
//                arrPrint($paramAsli);
//                mati_disini();
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id'], $paramAsli['static']['state'], $defaultOlehID, $defaultTransID);
                if ($_preValue != null) {
                    $this->writeMode = "update";
                    $this->outParams[$lCounter]["nilai"] = ($paramAsli['static']['nilai'] + $_preValue);
                } else {
                    $this->writeMode = "new";
                }
            }
        }
        arrPrint($this->outParams);
//        mati_disini();
        if (sizeof($this->outParams) > 0) {
            return true;
        } else {
            return false;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0)
    {

        $this->load->model("Mdls/MdlLockerSupplier");
        $l = new MdlLockerSupplier();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));
//        arrPrint($tmp);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->nilai;
            }
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
//        print_r($this->outParams);die();
//        arrPrint($this->outParams);
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlLockerSupplier");
                $l = new MdlLockerSupplier();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "cabang_id" => $params['cabang_id'],
                            "produk_id" => $params['produk_id'],
                            "state"     => $params['state'],
                            "oleh_id"   => $params['oleh_id'],
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