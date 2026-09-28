<?php

//--include_once "MdlHistoriData.php";
class MdlRoute extends CI_Model
{

    protected $tableName = "terima";
    protected $fields = array();
    protected $indexFields;
    private $tabel;
    private $debug;
    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
    function __construct()
    {
        $this->db3 = $this->load->database('route', TRUE);
//        $this->tableNames = "terima";

    }

    public function updateData($where, $datas)
    {
        $tbl = isset($this->tableName) ? $this->tableName : matiHere(__METHOD__ . " tablename belum diset");
        $this->db3->where($where);
        $this->db3->update($tbl, $datas);
        if ($this->debug === true) {
            cekMerah($this->db3->last_query());
        }

        return true;
    }

    public function addData($data)
    {
        $tbl = isset($this->tableName) ? $this->tableName : matiHere(__METHOD__ . " tablename belum diset");
        $this->db3->insert($tbl, $data);

        if ($this->debug === true) {
            cekBiru($this->db3->last_query());
        }

        return $this->db->insert_id();
    }

}