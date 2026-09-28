<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiDataUpdate extends MdlMother
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
//        "extern_id",
//        "extern_nama",
//        "jenis",
//
    );

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $array_params) {
//                cekBiru("masuk disini...");
                $lCounter++;
                $defaultProdukID = isset($array_params['static']['extern_id']) ? $array_params['static']['extern_id'] : 0;
                $defaultTransID = isset($array_params['static']['transaksi_id']) ? $array_params['static']['transaksi_id'] : 0;
                $produk_ord_kurang = isset($array_params['static']['produk_ord_kurang']) ? $array_params['static']['produk_ord_kurang'] : 0;
                if ($defaultTransID > 0) {
                    $_preValue = $this->cekPreValue(
                        $defaultTransID,
                        $defaultProdukID
                    );
//                    arrPrintHitam($_preValue);
                    if ($_preValue != null) {
                        $this->writeMode = "update";
                        $this->outParams[$lCounter]["produk_ord_dibeli"] = $_preValue["produk_ord_dibeli"] + $produk_ord_kurang;
//                        $this->outParams[$lCounter]["produk_ord_kurang"] = $_preValue["produk_ord_kurang"] - $produk_ord_kurang;
                        $this->outParams[$lCounter]["id"] = $_preValue["id"];

                    }
                    else {
                        $this->writeMode = "none";
                    }
//                    mati_disini(__LINE__ . " ||| ");
                }
            }
        }

//        arrPrintPink($this->outParams);
//        mati_disini(__LINE__);

        return true;

    }


    private function cekPreValue($transaksiID, $defaultProdukID)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setFilters(array());
//        $l->addFilter("id='$transaksiID'");
        $l->addFilter("produk_id='$defaultProdukID'");
        $tmp = $l->lookupDetailTransaksiAll($transaksiID);
        cekMerah($this->db->last_query() . " # " . count($tmp));
        if (sizeof($tmp) > 0) {
            foreach ($tmp[$transaksiID] as $row) {
                $result = array(
                    "id" => $row->id,
                    "transaksi_id" => $row->transaksi_id,
                    "produk_ord_kurang" => $row->produk_ord_kurang,
                    "produk_ord_dibeli" => $row->produk_ord_dibeli,
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
                $tbl_id = $params["id"];
                unset($params["id"]);

                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        $l->setFilters(array());
                        $l->setTableName($l->getTableNames()["detail"]);
                        $insertIDs[] = $l->updateData(array(
                            "id" => $tbl_id,
                        ), $params);
                        showLast_query("orange");
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
            }

//            mati_disini(__LINE__);
            return true;
//            if (sizeof($insertIDs) > 0) {
//                return true;
//            }
//            else {
//                return false;
//            }

        }
        else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }
}


