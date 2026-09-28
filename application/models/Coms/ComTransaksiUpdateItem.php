<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiUpdateItem extends MdlMother
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
                arrPrintCyan($array_params);
//                cekBiru("masuk disini...");
                $lCounter++;
                $defaultProdukID = isset($array_params['static']['extern_id']) ? $array_params['static']['extern_id'] : 0;
                $rejection = isset($array_params['static']['rejection']) ? $array_params['static']['rejection'] : 0;
//                $defaultTransID = isset($array_params['static']['transaksi_id']) ? $array_params['static']['transaksi_id'] : 0;
//                $produk_ord_kurang = isset($array_params['static']['produk_ord_kurang']) ? $array_params['static']['produk_ord_kurang'] : 0;
                if ($defaultProdukID > 0) {
                    $_preValue = $this->cekPreValue(

                        $defaultProdukID
                    );
                    arrPrintHitam($_preValue);
                    if ($_preValue != null) {
                        $this->writeMode = "update";
//                        $this->outParams[$lCounter]["produk_ord_dibeli"] = $_preValue["produk_ord_dibeli"] + $produk_ord_kurang;
//                        $this->outParams[$lCounter]["produk_ord_kurang"] = $_preValue["produk_ord_kurang"] - $produk_ord_kurang;
                        $this->outParams[$lCounter]["id"] = $_preValue["id"];
                        if ($rejection == 1) {
//                        $this->outParams[$lCounter]["biaya_bank_status"] = $array_params["static"]["biaya_bank_status"];
                            $this->outParams[$lCounter]["biaya_bank_status"] = $array_params["static"]["jumlah"] + $_preValue["biaya_bank_status"];
                        }
                        else {
//                        $this->outParams[$lCounter]["biaya_bank_status"] = $array_params["static"]["biaya_bank_status"];
                            $this->outParams[$lCounter]["biaya_bank_status"] = $array_params["static"]["jumlah"];
                        }

                    }
                    else {
                        $this->writeMode = "none";
                    }
//                    mati_disini(__LINE__ . " ||| ");
                }
            }
        }

        arrPrintPink($this->outParams);
//        mati_disini(__LINE__);

        return true;

    }


    private function cekPreValue($defaultProdukID)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setFilters(array());
//        $l->addFilter("id='$transaksiID'");
        $l->addFilter("id='$defaultProdukID'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
//                    "transaksi_id" => $row->transaksi_id,
//                    "produk_ord_kurang" => $row->produk_ord_kurang,
//                    "produk_ord_dibeli" => $row->produk_ord_dibeli,
                    "biaya_bank_status" => $row->biaya_bank_status,
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
                        $l->setTableName($l->getTableNames()["main"]);
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


