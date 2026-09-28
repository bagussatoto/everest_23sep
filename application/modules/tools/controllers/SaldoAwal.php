<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class SaldoAwal extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }

    public function cek()
    {
        cekHijau(url_segment(4));
        $produk_id = url_segment(4);
        $tahun = "2025";
        $cabang_id = CB_ID_PUSAT;
        $gudang_id = "-1";
        $cabang_id = "1";
        $gudang_id = "-10";
        // $produk_id = "1814";
        // $produk_id = "1826";
        $common_filters = [
            "year(dtime)" => $tahun,
            // "cabang_id" => $cabang_id,
            // "gudang_id" => $gudang_id,
            "extern_id"   => $produk_id,
        ];

//         $saldo_awal = $this->get_saldo_awal($common_filters);
//         showLast_query("biru");
//         arrPrint($saldo_awal);
//         $total_masuk = $this->get_barang_masuk($common_filters);
//         showLast_query("kuning");
//         arrPrintKuning($total_masuk);
//         $total_keluar = $this->get_barang_keluar($common_filters);
//         showLast_query("kuning");
//         arrPrintKuning($total_keluar);
//
//         $qty_saldo_akhir = $saldo_awal["qty_saldo_awal"] + $total_masuk["qty_masuk"] - $total_keluar["qty_keluar"];
//         $saldo_akhir = $saldo_awal["saldo_awal"] + $total_masuk["masuk"] - $total_keluar["keluar"];
//
//         cekBiru("qty_saldo_akhir : $qty_saldo_akhir
// <br>saldo_akhir : $saldo_akhir");

        $final = $this->get_stok_akhir($common_filters);
        showLast_query("lime");
        arrPrintWebs($final);

    }

    private function get_saldo_awal($common_filters)
    {
        $tbl_2 = "__rek_pembantu_produk__1010030030";

        $koloms = [
            "sum(saldo_awal_qty_debet) as 'qty_saldo_awal'",
            "sum(saldo_awal_debet) as 'saldo_awal'",
        ];
        $this->db->select($koloms);
        $condites = $common_filters;
        $this->db->where($condites);
        $produkSupplierData = $this->db->get($tbl_2)->row_array();

        return $produkSupplierData;
    }

    private function get_barang_masuk($common_filters)
    {
        $tbl_2 = "__rek_pembantu_produk__1010030030";

        $koloms = [
            "sum(qty_debet) as 'qty_masuk'",
            "sum(debet) as 'masuk'"
        ];
        $this->db->select($koloms);
        $condites = $common_filters;
        $this->db->where($condites);
        $produkSupplierData = $this->db->get($tbl_2)->row_array();

        return $produkSupplierData;
    }

    private function get_barang_keluar($common_filters)
    {
        $tbl_2 = "__rek_pembantu_produk__1010030030";

        $koloms = [
            "sum(qty_kredit) as 'qty_keluar'",
            "sum(kredit) as 'keluar'"
        ];
        $this->db->select($koloms);
        $condites = $common_filters;
        $this->db->where($condites);
        $produkSupplierData = $this->db->get($tbl_2)->row_array();

        return $produkSupplierData;
    }

    private function get_stok_akhir($common_filters)
    {
        $this->db->select('
        cabang_id,
        gudang_id,
        extern_id as produk_id,
    SUM(saldo_awal_qty_debet) AS qty_saldo_awal, 
    SUM(saldo_awal_debet) AS saldo_awal,
    SUM(qty_debet) AS qty_masuk, 
    SUM(debet) AS masuk, 
    SUM(qty_kredit) AS qty_keluar, 
    SUM(kredit) AS keluar,
    (SUM(saldo_awal_qty_debet) + SUM(qty_debet) - SUM(qty_kredit)) AS qty_saldo_akhir,
    (SUM(saldo_awal_debet) + SUM(debet) - SUM(kredit)) AS saldo_akhir,
    
');
        $this->db->from('__rek_pembantu_produk__1010030030');
        // $this->db->where('YEAR(dtime)', '2025');
        // $this->db->where('cabang_id', '-1');
        // $this->db->where('gudang_id', '-1');
        // $this->db->where('extern_id', '42');
        $condites = $common_filters;
        $this->db->where($condites);
        $this->db->group_by("gudang_id");
        $query = $this->db->get();
        $result1 = $query->result_array();
        foreach ($result1 as $items) {
            $gudang_id = $items['gudang_id'];
            $produk_id = $items['produk_id'];

            $result[$produk_id][$gudang_id] = $items;
        }

        return $result;
    }

    public function index()
    {
        matiHere("typo");
    }

}