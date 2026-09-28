<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComProdukSerialNumberExec extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
//        "jenis",
        "produk_serial_number",
        "produk_serial_number_2",
        "produk_sku",
        "produk_sku_label",
        "produk_sku_serial",
        "produk_id",
        "produk_nama",
        "produk_sku_part_id",
        "produk_sku_part_nama",
        "produk_sku_part_serial",
        "cabang_id",
        "cabang_nama",
        "gudang_id",
        "gudang_nama",
//        "nama",
//        "satuan",
//        "state",
//        "jumlah",
        "oleh_id",
        "oleh_nama",
        "supplier_id",
        "supplier_nama",
//        "transaksi_id",
//        "nomer",
//        "gudang_id",
//
        "status",
        "trash",
        "dtime",
        "fulldate",
        "transaksi_id",
        "transaksi_no",
        "transaksi_count",
        "transaksi_reference_id",
        "transaksi_reference_no",
        "transaksi_reference_dtime",
        "transaksi_reference_count",
    );

    public function __construct()
    {
        parent::__construct();
//        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProdukPerSerialNumber");

    }

    public function pair($inParams)
    {
//        arrPrintPink($inParams);
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $lCounter = 0;

                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = trim($value);
                    }
                }
                $_preValue = $this->cekPreValue(
                    $array_params['static']['produk_id'],
                    $array_params['static']['produk_serial_number_2'],
                    $array_params['static']['cabang_id'],
                    $array_params['static']['gudang_id']
                );


                if ($_preValue != null) {
                    $this->outParams[$lCounter]["mode"] = "update";
                    $l = new MdlProdukPerSerialNumber();
                    $update_exec = array(
                        "exec_cache"=>"1",
                    );
                    $where = array(
                        "id"=>$_preValue
                    );
                    $l->updateData($where,$update_exec);
                }
                else {
                }
            }

        }
        return true;

    }

    private function cekPreValue($produk_id,$serial,$cabang_id,$gudang_id)
    {


        $l = new MdlProdukPerSerialNumber();
        $l->addFilter("produk_serial_number_2='$serial'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("exec_cache='0'");
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//        $tmp = $this->db->query("{$query}")->row_array();
//        cekBiru($this->db->last_query());
//matiHere("ini $state");
//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {
            $result = $tmp['id'];
        }
        else {
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