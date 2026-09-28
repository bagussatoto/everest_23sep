<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComPriceProtector extends CI_Model
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
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "nomer",
        "gudang_id",
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
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

//                arrprint($paramAsli['static']);

                $nama = isset($paramAsli["static"]["nama"]) ? $paramAsli["static"]["nama"] : "";
                $harga_jual = isset($paramAsli['static']['harga']) ? $paramAsli['static']['harga'] : "0";
//                $harga_beli = isset($paramAsli['static']["hpp"]) ? $paramAsli['static']["hpp"] : 0;

                $harga_beli=0;
                $_preValue = $this->cekPreValue($paramAsli['static']['jenis'], $paramAsli['static']['cabang_id'], $paramAsli['static']['produk_id']);
                if ($_preValue != null) {
                    $harga_beli = isset($_preValue["hpp"]) ? $_preValue["hpp"] : 0;
                }

                if ($paramAsli['static']['harga'] < $harga_beli) {
                    $msg = "Transaksi gagal, karena harga jual produk $nama lebih kecil dari harga beli. Segera hubungi admin.";
                    die(lgShowAlert($msg));
                }
                elseif ($harga_jual == 0) {
                    $msg = "Transaksi gagal, karena harga jual produk $nama belum diatur. Segera hubungi admin.";
                    die(lgShowAlert($msg));
                }
//                elseif ($harga_beli == 0) {
//                    $msg = "Transaksi gagal, karena harga beli produk $nama belum diatur. Segera hubungi admin.";
//                    die(lgShowAlert($msg));
//                }
                elseif ($harga_beli == $harga_jual) {
                    $msg = "Transaksi gagal, karena harga beli sama dengan harga jual produk $nama. Segera hubungi admin.";
                    die(lgShowAlert($msg));
                }
                else {
//                        $msg = "Berhasil HOREE.....";
//                        die(lgShowAlert($msg));
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

    private function cekPreValue($jenis, $cabang_id, $produk_id)
    {

        $this->load->model("Mdls/MdlHargaProduk");
        $l = new MdlHargaProduk();


        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("produk_id='$produk_id'");


        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));


        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result[$row->jenis_value] = $row->nilai;
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
        return true;
    }
}