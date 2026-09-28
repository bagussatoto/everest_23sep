<?php

class ComTransaksiItems3_sum extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "produk_id",
        "produk_nama",
        "produk_jenis",
        "produk_ord_jml",
        "produk_ord_hrg",
        "produk_ord_diskon",
        "valid_qty",
        "satuan",
        "detail_description",
        "transaksi_id",
    );

    public function __construct()
    {
        parent::__construct();
        $this->jenisTrUpdate = array(
            "9911", "9912"
        );
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        cekHitam("cetak inParams ");
        arrPrint($this->inParams);


        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
//            if(in_array($this->inParams['static']['jenisTr'], $this->jenisTrUpdate)){
//                $this->writeMode = "update";
//                $this->outParams[$lCounter]['where'] = array(
//                    "produk_id" => $this->inParams['static']['referenceID'],
//                    "transaksi_id" => $this->inParams['static']['transaksi_id'],
//                );
//                $this->outParams[$lCounter]['valid_qty'] = 0;
//
//            }
//            else{
//                $this->writeMode = "new";
//                foreach ($this->inParams as $array_params) {
//                    $lCounter++;
//                    foreach ($array_params as $key => $value) {
//                        if (in_array($key, $this->outFields)) {
//                            $this->outParams[$lCounter][$key] = $value;
//                        }
//                    }
//                    $prev = $this->cekPreValue($array_params['transaksi_id']);
//                    if ($prev != NULL) {
//                        $prevDecode = blobDecode($prev);
//                    }
//                    else {
//                        $prevDecode = array();
//                    }
//                    $this->outParams[$lCounter]['indexing_items3_sum'] = $prevDecode;
//
//                }
//            }
            foreach ($this->inParams as $array_params) {
                $lCounter++;
//                foreach ($array_params as $key => $value) {
//                    if (in_array($key, $this->outFields)) {
//                        $this->outParams[$lCounter][$key] = $value;
//                    }
//                }
                $prev = $this->cekPreValue($array_params['produk_jenis'], $array_params['produk_id'], $array_params['transaksi_id']);
                $prevSubDetail = $this->cekPreValueSubDetail($array_params['transaksi_id']);
                if ($prev != NULL) { // ada data items3_sum
                    $this->writeMode = "update";
//                    $prevDecode = blobDecode($prev);
//                    $this->outParams[$lCounter]['indexing_items3_sum'] = $prevDecode;
                    $this->outParams[$lCounter]["valid_qty"] = ($array_params['valid_qty'] + $prev['valid_qty']);
                    $this->outParams[$lCounter]["where"] = array(
                        "id" => $prev['id'],
                    );
                }
                else {// tidak ada data items3_sum
                    $this->writeMode = "new";
                    foreach ($array_params as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    $prevSubDetailDecode = $prevSubDetail != NULL ? blobDecode($prevSubDetail) : array();
                    $this->outParams[$lCounter]['indexing_items3_sum'] = $prevSubDetailDecode;
                }


            }
        }

//        arrPrintWebs($this->outParams);
//        mati_disini();
        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($jenis, $produk_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setTableName($l->getTableNames()['items3_sum']);
        $l->setFilters(array());
        $l->addFilter("transaksi_id='$transaksiID'");
        $l->addFilter("produk_jenis='$jenis'");
        $l->addFilter("produk_id='$produk_id'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            $result = array(
                "id" => $tmp[0]->id,
                "transaksi_id" => $tmp[0]->transaksi_id,
                "produk_id" => $tmp[0]->produk_id,
                "produk_jenis" => $tmp[0]->produk_jenis,
                "produk_nama" => $tmp[0]->produk_nama,
                "valid_qty" => $tmp[0]->valid_qty,
                "produk_ord_hrg" => $tmp[0]->produk_ord_hrg,
                "produk_ord_jml" => $tmp[0]->produk_ord_jml,
            );
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    private function cekPreValueSubDetail($transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setFilters(array());
        $l->addFilter("id='$transaksiID'");
//        $l->addFilter("produk_jenis='$jenis'");
//        $l->addFilter("produk_id='$produk_id'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            $result = $tmp[0]->indexing_items3_sum;
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
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "new":
                        $indexing_sub_details = $params['indexing_items3_sum'];
                        unset($params['indexing_items3_sum']);
                        $transaksi_id = $params['transaksi_id'];
                        $l->setTableName($l->getTableNames()['items3_sum']);
                        $insertIDs[] = $l->addData($params);
                        cekBiru($this->db->last_query());
                        //---------------------------------
                        if (sizeof($indexing_sub_details) > 0) {
                            foreach ($insertIDs as $val) {
                                $indexing_sub_details[] = $val;
                            }
                        }
                        else {
                            $indexing_sub_details = $insertIDs;
                        }

                        $indexing_sub_details_encode = blobEncode($indexing_sub_details);
                        $this->load->model("MdlTransaksi");
                        $l = new MdlTransaksi();
                        $l->setFilters(array());
                        $where = array(
                            "id" => $transaksi_id,
                        );
                        $data = array(
                            "indexing_items3_sum" => $indexing_sub_details_encode,
                        );
                        $upd[] = $l->updateData($where, $data);
                        showLast_query("orange");

                        break;
                    case "update":
                        $where = $params['where'];
                        unset($params['where']);
                        $l->setTableName($l->getTableNames()['items3_sum']);
                        $insertIDs[] = $l->updateData($where, $params);
                        showLast_query("orange");

                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
            }
//            mati_disini();
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