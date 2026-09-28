<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiItemReturnUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

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
        "transaksi_id",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;


        $lCounter = 0;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                arrPrint($array_params);
                if (isset($array_params['static']['returnMethod'])) {
                    switch ($array_params['static']['returnMethod']) {
                        case "nota":
                            $postProcc = true;
                            break;
                        case "barang":
                            $postProcc = false;
                            break;
                        default:
                            $msg = "Transaksi gagal dilanjutkan karena metode return by nota / by barang belum ditentukan.";
                            die(lgShowAlert($msg));
                            break;
                    }
                }
                else {
                    $postProcc = true;
                }
                cekUngu("postProcc :: " . $postProcc);
                if ($postProcc == true) {
                    $lCounter++;
                    $defaultTransID = isset($array_params['static']['transaksi_id']) ? $array_params['static']['transaksi_id'] : 0;
                    $_preValue = $this->cekPreValue($array_params['static']['produk_jenis'], $array_params['static']['produk_id'], $defaultTransID);

                    if ($_preValue != null) {
                        if (sizeof($_preValue) > 0) {
                            $new_produk_jml_return = $_preValue['produk_jml_return'] + $array_params['static']['jumlah'];
                            if ($new_produk_jml_return > $_preValue['produk_jml']) {
                                $msg = "transaksi gagal, karena jumlah return " . $array_params['static']['produk_nama'] . " melebihi ketentuan. [" . __LINE__ . "]";
                                die(lgShowAlert($msg));
                            }
                            else {
                                $this->writeMode = "update";
                                $this->outParams[$lCounter]['produk_ord_jml_return'] = $new_produk_jml_return;
                            }
                        }
                    }
                    else {
                        $msg = "transaksi gagal, karena jumlah return " . $array_params['static']['produk_nama'] . " melebihi ketentuan. [" . __LINE__ . "]";
                        die(lgShowAlert($msg));
                    }

                    foreach ($array_params['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = $value;
                        }
                    }
                    $this->outParams[$lCounter]['returned'] = (isset($array_params['static']['seluruhnya']) && $array_params['static']['seluruhnya'] > 0) ? $array_params['static']['seluruhnya'] : 0;
                }


            }
        }

        arrPrint($this->outParams);
//        mati_disini(":: comTransaksiItemReturnUpdate ::");
//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
        return true;
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
                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":

                        //  region update details....
                        $l->setTableName($l->getTableNames()['main']);
                        $insertIDs[] = $l->updateData(
                            array(
                                "id" => $params['transaksi_id'],
                            ),
                            array("returned" => $params['returned']));
                        //  endregion

                        cekBiru($this->db->last_query());


                        //  region update details....
                        $l->setTableName($l->getTableNames()['detail']);
                        unset($params['returned']);
                        $insertIDs[] = $l->updateData(array(
                            "produk_jenis" => $params['produk_jenis'],
                            "produk_id" => $params['produk_id'],
                            "transaksi_id" => $params['transaksi_id'],
                        ), $params);
                        //  endregion

                        cekBiru($this->db->last_query());


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
//            die("nothing to write down here");
//            return false;
            return true;
        }
//        mati_disini();
    }
}