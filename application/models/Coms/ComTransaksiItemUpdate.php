<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComTransaksiItemUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outParamsDetail = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
        "id",
        "cabang_id",
        "cabang_nama",
        "produk_id",
        "produk_nama",
        "produk_jenis",
        "produk_ord_jml_return",
        "produk_ord_diterima",
        "produk_ord_kurang",
        "transaksi_id",
        "sinkron",
        "trash2",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
//        cekHitam("cetak inParams ");
        arrPrint($this->inParams);

        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $lCounter++;
//                $defaultTransID = isset($array_params['static']['transaksi_id']) ? $array_params['static']['transaksi_id'] : 0;
//                $_preValue = $this->cekPreValue($array_params['static']['produk_jenis'], $array_params['static']['produk_id'], $defaultTransID);
//
//                if ($_preValue != null) {
//                    if (sizeof($_preValue) > 0) {
//                        $new_produk_jml_return = $_preValue['produk_jml_return'] + $array_params['static']['jumlah'];
//                        if ($new_produk_jml_return > $_preValue['produk_jml']) {
//                            $msg = "transaksi gagal, karena jumlah return " . $array_params['static']['produk_nama'] . " melebihi ketentuan. {" . __LINE__ . "]";
//                            die(lgShowAlert($msg));
//                        }
//                        else {
//                            $this->writeMode = "update";
//                            $this->outParams[$lCounter]['produk_ord_jml_return'] = $new_produk_jml_return;
//                            cekKuning("cek: new return: $new_produk_jml_return; ori: " . $_preValue['produk_jml']);
//
//                        }
//                    }
//                }
//                else {
//                    $msg = "transaksi gagal, karena jumlah return " . $array_params['static']['produk_nama'] . " melebihi ketentuan. {" . __LINE__ . "]";
//                    die(lgShowAlert($msg));
//                }
                $this->writeMode = "update";
                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $this->outParams[$lCounter]['sinkron'] = (isset($array_params['static']['sinkron']) && $array_params['static']['sinkron'] > 0) ? $array_params['static']['sinkron'] : 0;

                //---
                $updateDetail = false;
                if (isset($array_params['static']["reference_id_so_so"]) && ($array_params['static']["reference_id_so_so"] > 0)) {
                    $updateDetail = true;
                    $transaksiID = $array_params['static']["reference_id_so_so"];
                }
                elseif (isset($array_params['static']["reference_id_so_po"]) && ($array_params['static']["reference_id_so_po"] > 0)) {
                    $updateDetail = true;
                    $transaksiID = $array_params['static']["reference_id_so_po"];
                }
                if ($updateDetail == true) {
                    $prev = $this->cekPreValue(
                        $array_params['static']["jenis"],
                        $array_params['static']["produk_id"],
                        $transaksiID
                    );
                    arrPrint($prev);
//                    cekHitam($this->db->last_query());
                    if ($prev != NULL) {
                        $prev_jml_kurang = $prev["produk_jml_kurang"];
                        cekHitam("jmlkurang: $prev_jml_kurang || jml: " . $array_params['static']["jumlah"]);
                        if ($prev_jml_kurang >= $array_params['static']["jumlah"]) {
                            //lolos
                        }
                        else {
                            matiAlert("transaksi sudah di followup. Silahkan login ulang. code: " . __LINE__);
                        }
                        $new_jml_kurang = $prev_jml_kurang - $array_params['static']["jumlah"];
                        foreach ($array_params['static'] as $key => $value) {
                            if (in_array($key, $this->outFields)) {
                                $this->outParamsDetail[$lCounter][$key] = $value;
                            }
                        }
                        $this->outParamsDetail[$lCounter]['produk_ord_kurang'] = $new_jml_kurang;
                        $this->outParamsDetail[$lCounter]['transaksi_id'] = $transaksiID;
                        $this->outParamsDetail[$lCounter]['id'] = $prev["id"];

                    }
                    else {
                        cekHitam($this->db->last_query());
                        matiAlert("transaksi sudah di followup. Silahkan login ulang. code: " . __LINE__);
                    }
                }


            }
        }
        if (sizeof($this->outParams) > 0) {
            return true;
        }
        elseif (sizeof($this->outParamsDetail) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function cekPreValue($jenis, $extern_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();
        $l->setTableName($l->getTableNames()['detail']);
        $l->setFilters(array());
        $l->addFilter("transaksi_id='$transaksiID'");
        $l->addFilter("produk_jenis='$jenis'");
        $l->addFilter("produk_id='$extern_id'");
        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "id" => $row->id,
                    "produk_id" => $row->produk_id,
                    "produk_jml" => $row->produk_ord_jml,
                    "produk_jml_return" => $row->produk_ord_jml_return,
                    "produk_jml_diterima" => $row->produk_ord_diterima,
                    "produk_jml_kurang" => $row->produk_ord_kurang,
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
        $insertIDs = array();
        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
//                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        //  region update details....
                        $l->setTableName($l->getTableNames()['main']);
                        $insertIDs[] = $l->updateData(
                            array(
                                "id" => $params['transaksi_id'],
                            ),
                            array("sinkron" => $params['sinkron']));
                        //  endregion
                        cekBiru($this->db->last_query());

                        //  region update details....
//                        $l->setTableName($l->getTableNames()['detail']);
//                        unset($params['returned']);
//                        $insertIDs[] = $l->updateData(array(
//                            "produk_jenis" => $params['produk_jenis'],
//                            "produk_id" => $params['produk_id'],
//                            "transaksi_id" => $params['transaksi_id'],
//                        ), $params);
                        //  endregion

                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
            }
        }

//        arrPrintPink($this->outParamsDetail);
        if (sizeof($this->outParamsDetail) > 0) {
            foreach ($this->outParamsDetail as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
//                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
//
//                        //  region update details....
//                        $l->setTableName($l->getTableNames()['main']);
//                        $insertIDs[] = $l->updateData(
//                            array(
//                                "id" => $params['transaksi_id'],
//                            ),
//                            array("sinkron" => $params['sinkron']));
//                        //  endregion
//
//                        cekBiru($this->db->last_query());

                        //  region update details....
                        $idTbl = $params['id'];
                        $l->setTableName($l->getTableNames()['detail']);
                        unset($params['returned']);
                        unset($params['id']);
                        $insertIDs[] = $l->updateData(array(
                            "id" => $idTbl,
                        ), $params);
                        //  endregion

                        cekBiru($this->db->last_query());


                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
            }
        }


        if (sizeof($insertIDs) > 0) {
            return true;
        }
        else {
            return false;
        }

    }
}