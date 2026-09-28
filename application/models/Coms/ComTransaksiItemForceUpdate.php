<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComTransaksiItemForceUpdate extends MdlMother
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
        cekHitam("cetak inParams ");
        arrPrint($this->inParams);

        $inserID = array();
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {

//                arrprint($array_params);
//                matiHere();
                $update = array(
                    "valid_qty" => 0,
                );
                $where = array("transaksi_id" => $array_params["static"]["refID"]);

                $this->load->model("MdlTransaksi");
                $l = new MdlTransaksi();
                $l->setFilters(array());
                $l->setTableName($l->getTableNames()['detail']);
                $inserID[] = $l->updateData($where, $update) or matiHere("gagal memperbaharui data, silahkan coba beberapa saat lagi " . date("Y-m-d H:i"));
//cekHitam($this->db->last_query());

            }
        }
        if (count($inserID) > 0) {
            return true;
        } else {
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
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        return true;

    }
}