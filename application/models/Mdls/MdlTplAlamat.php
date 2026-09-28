<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 13/11/18
 * Time: 16:51
 */
class MdlTplAlamat extends CI_Model
{
    protected $filters = array();
    private $fields = array(
        "tlp", "alamat",
    );
    private $tableNames = array(
        "main" => "tpl_alamat",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function getTableName()
    {
        return $this->tableNames;
    }

    public function setTableName($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function lookupTplALamat($tpl_id)
    {
        $arrWhere = array(
            "id" => "$tpl_id",
        );
        $this->db->select($this->fields);
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableNames['main']);
        return $q;

    }


}