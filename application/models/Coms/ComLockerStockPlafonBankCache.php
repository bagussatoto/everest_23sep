<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerStockPlafonBankCache extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        "jenis",
        "produk_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "debet",
        "kredit",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
        "status",
        "trash",
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
            foreach ($this->inParams as $lCtr => $paramAsli) {
//                arrPrint($paramAsli);
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = $paramAsli['static']['gudang_id'];

                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id'], $defaultGudangID);
                if ($_preValue != null) {

                    $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['jumlah'] + $_preValue);
                    $this->outParams[$lCounter]["mode"] = "update";

                    cekLime($paramAsli['static']['jumlah']);
                    cekLime($_preValue);
                    arrPRint($this->outParams);
                    if ($this->outParams[$lCounter]["jumlah"] < 0) {
                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: " . $_preValue;
                        die(lgShowAlert($msg));
                    }
                }
                else {
                    $this->outParams[$lCounter]["mode"] = "new";
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

    private function cekPreValue($cabang_id, $produk_id, $gudang_id)
    {

        $this->load->model("Mdls/MdlLockerStockCache");
        $l = new MdlLockerStockCache();


//        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("extern_id='$produk_id'");

        $tmp = $l->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->qty_debet;
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

        matiHEre();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlLockerStockCache");
                $l = new MdlLockerStockCache();
                $insertIDs = array();
                $mode = $params['mode'];
                unset($params['mode']);
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "cabang_id" => $params['cabang_id'],
                            "gudang_id" => $params['gudang_id'],
                            "extern_id" => $params['produk_id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
//                cekBiru("LOCKER ". $this->db->last_query());

            }
            matiHEre("donot save yet");
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