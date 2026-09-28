<?php

class Numbering extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //        $this->masterConfigUi = $this->config->item("heTransaksi_ui");

    }

    function index()
    {
        $kolom_block = array("ext_intext", "ids_his", "ids_his_intext", "project_nama", "reg_edit_old", "reg_edit_new", "link_id", "project_id", "indexing_main_values", //            "counters",
            "counters_intext", "_company_cabangID_gudang2ID", "_company_cabangID_cabang2ID", "_company_gudang2ID", "_company_cabang2ID", "_company_cabangID_modul_cabang2ID", "_company_cabangID_modul_gudang2ID", "_company_cabangID_modul_subModul_gudang2ID", "_company_cabangID_modul_subModul_jenisTr_supplierID", "_company_cabangID_modul_subModul_cabang2ID", "_company_cabangID_modul_subModul_jenisTr_cabang2ID", "_company_cabangID_modul_supplierID", "_company_cabangID_modul_subModul_supplierID", "_company_cabangID_supplierID", "_company_supplierID",);
        $kolom_all = $this->getColumns();

        $modul = "penjualan";
        // $modul = "distribusi";
        switch ($modul) {
            case "penjualan":
                $filters = $this->getFilterDataPenjualan()["filters"];
                $parts_kolom = $this->getFilterDataPenjualan()["parts_kolom"];
                break;
            default:
                $filters = $this->getFilterDataDistribusi()["filters"];
                $parts_kolom = $this->getFilterDataDistribusi()["parts_kolom"];
                break;
        }

        $kolom_tambahan = array(sql_concat_with_delimiter($parts_kolom, "nomer_new"));
        //        arrPrint($kolom_tambahan);
        //        matiHere(__LINE__);

        $kolom = array_diff($kolom_all, $kolom_block);
        $kolom_pilihan = array_merge($kolom, $kolom_tambahan);
        //        arrPrintPink($kolom);

        $this->db->select($kolom_pilihan);
        $datas = $this->getDataTransaksi($filters)->result_array();
        $query = $this->db->last_query();
        // showLast_query("biru");
        //        arrPrint($datas);

        $kolom_config = array("id" => array("alias" => "Transaction ID", "order" => 1), "nomer" => array("alias" => "customer_reference_no", "order" => 2), "nomer_new" => array("alias" => "standard_transaction_no", "order" => 3), "dtime" => array("alias" => "tanggal", "order" => 4), "jenis" => array("alias" => "jenis", "order" => 5),);

        // Gabungkan kolom dengan konfigurasi
        $kolom_final = array();
        foreach ($kolom as $k) {
            if (isset($kolom_config[$k])) {
                $kolom_final[$k] = $kolom_config[$k]; // Gunakan konfigurasi jika ada
            }
            else {
                $kolom_final[$k] = array("alias" => $k, "order" => PHP_INT_MAX); // Tampilkan apa adanya jika tidak ada alias
            }
        }

        // Urutkan kolom berdasarkan order
        uasort($kolom_final, function ($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });

        $data = array("datas" => $datas, "query" => $query, "kolom_config" => $kolom_final,);

        $this->load->view("table_view", $data);
    }


    function getFilterDataPenjualan()
    {
        $condites = array(
            "jenis_master" => "5822",
            "jenis"        => "5822spo",
            // "cabang_id"    => 1,
        );

        $parts_kolom = array('jenis',
            "DATE_FORMAT(dtime, '%Y%m%d')",
            'cabang_id',
            'customers_id',
            '_company_cabangID_modul_subModul_jenisTr_customerID',
            '_company_cabangID_jenisTr'
        );

        $hasil = [];
        $hasil["filters"] = $condites;
        $hasil["parts_kolom"] = $parts_kolom;

        return $hasil;
    }

    function getFilterDataDistribusi()
    {
        $condites = array(
            "jenis_master" => "583",
            "jenis"        => "583r",
            "cabang_id"    => "-1",
        );

        $parts_kolom = array('jenis',
            "DATE_FORMAT(dtime, '%Y%m%d')",
            'cabang_id',
            'cabang2_id',
            '_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID',
            '_company_cabangID_modul_stepCode'
        );

        $hasil = [];
        $hasil["filters"] = $condites;
        $hasil["parts_kolom"] = $parts_kolom;

        return $hasil;
    }

    /**
     * Mendapatkan data transaksi dengan kondisi dinamis
     * edited by glg (WIB, 2026-01-12)
     * change: refactor getDataPenjualan & getDataDistribusi menjadi satu method generik
     * technical rationale: mengurangi duplikasi, parameterisasi kondisi, tetap backward compatible
     */
    private function getDataTransaksi($condites = array())
    {
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $this->db->limit(60);
        $this->db->order_by("id", "DESC");
        $srcs = $tr->lookupByCondition($condites);
        return $srcs;
    }

    public function getColumns()
    {
        $this->load->database(); // Pastikan database sudah diload
        $fields = $this->db->list_fields('transaksi'); // Nama tabel
        return $fields;
    }

    /**
     * gpt1
     * */
    function sql_concat_with_delimiter_($columns, $alias = '', $delimiter = '.')
    {
        $parts = [];
        foreach ($columns as $i => $col) {
            if ($i > 0) {
                $parts[] = "'$delimiter'";
            }
            $parts[] = $col;
        }

        $sql = "CONCAT(" . implode(", ", $parts) . ")";
        return $alias ? "$sql AS $alias" : $sql;
    }

}