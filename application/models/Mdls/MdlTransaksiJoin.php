<?php

/*
 * biar bisa load MdlTransaksi oleh receiptElement
 */
require APPPATH . '/models/MdlTransaksi.php';//just add this line and keep rest

class MdlTransaksiJoin extends MdlTransaksi
{
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nomer" => "nomer",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }


    public function lookupAll()
    {
        $this->tableName = "transaksi";
        $this->tableNameStatus = "transaksi_status";

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }

        // kalau ada table2 → union
        if (!empty($this->tableUnion)) {
            $columns = '*,transaksi.id as id'; // ganti sesuai kebutuhan (lebih aman tulis nama kolom)

            // SELECT dari table1
            $this->db->select($columns);
            if (!empty($criteria)) $this->db->where($criteria);
            if ($criteria2 != "") $this->db->where($criteria2);
            $sql1 = $this->db->get_compiled_select($this->tableName);
            $this->db->reset_query();

            // SELECT dari table2
            $this->db->select($columns);
            if (!empty($criteria)) $this->db->where($criteria);
            if ($criteria2 != "") $this->db->where($criteria2);
            $sql2 = $this->db->get_compiled_select($this->tableUnion);
            $this->db->reset_query();

            // gabung UNION ALL
            $unionSql = "$sql1 UNION ALL $sql2";

            // order by global
            if (isset($this->sortBy) && !empty($this->sortBy)) {
                $unionSql .= " ORDER BY " . $this->sortBy['kolom'] . " " . $this->sortBy['mode'];
            }

            return $this->db->query($unionSql);
        }
        else {
            $columns = '*,transaksi.id as id'; // ganti sesuai kebutuhan (lebih aman tulis nama kolom)
            $this->db->select($columns);
        }

        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
        }

        $this->db->join($this->tableNameStatus, $this->tableNameStatus . ".transaksi_id = " . $this->tableName . ".id");
        $result = $this->db->get($this->tableName);
        showLast_query("hitam");
        return $result;
//        $res = $this->db->get($this->tableName);
//        return $res;
//
//
    }

}