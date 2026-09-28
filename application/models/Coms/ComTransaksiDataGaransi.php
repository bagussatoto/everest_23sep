<?php

class ComTransaksiDataGaransi extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "transaksi_id",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "produk_ord_hrg",
        "produk_ord_ppn",
        "produk_ord_netto",
        "garansi_tarif",
        "garansi_nilai",
        "garansi_dtime",
        "produk_id",
        "produk_nama",
        "customers_id",
        "customers_nama",
    );

    public function __construct()
    {
        parent::__construct();

    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        cekHitam("cetak inParams ");
        arrPrint($this->inParams);


        $lCounter = 0;
        $this->outParams = array();
        if (isset($this->inParams['static']) && (sizeof($this->inParams['static']) > 0)) {
            $lCounter++;
            $transaksiID = isset($this->inParams['static']['transaksi_id']) ? $this->inParams['static']['transaksi_id'] : 0;
            $produkID = isset($this->inParams['static']['produk_id']) ? $this->inParams['static']['produk_id'] : 0;
            $customersID = isset($this->inParams['static']['customers_id']) ? $this->inParams['static']['customers_id'] : 0;
            $prev = $this->cekPreValue($transaksiID, $produkID, $customersID);
            if(sizeof($prev)>0){
                // update
                $this->writeMode = "update";
            }
            else{
                // insert baru
                $this->writeMode = "insert";
                foreach ($this->inParams['static'] as $key => $value){
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

            }
        }

//mati_disini();
        return true;

    }

    private function cekPreValue($transaksiID, $produkID, $customersID)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setTableName($l->getTableNames()['garansi']);
        $l->setFilters(array());
        $l->setSortBy(array("kolom" => "transaksi_id", "mode" => "ASC"));
        $l->addFilter("transaksi_id='$transaksiID'");
        $l->addFilter("produk_id='$produkID'");
        $l->addFilter("customers_id='$customersID'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            $result = array(
                "id" => $tmp[0]->transaksi_id,

            );
        }
        else {
            $result = array();
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
//        mati_disini();

        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "insert":
//
//                        $indexing_sub_details = $params['indexing_items3_sum'];
//                        unset($params['indexing_items3_sum']);
//                        $transaksi_id = $params['transaksi_id'];
                        $l->setTableName($l->getTableNames()['garansi']);
                        $insertIDs[] = $l->addData($params);
                        cekBiru($this->db->last_query());

                        break;
                    case "update":

                        break;

                    default:
                        die("unknown writemode!");
                        break;
                }
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            cekHitam(":: tidak ada Update di " . __CLASS__);
            return true;

        }
    }


}