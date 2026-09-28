<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 15:43
 */
class MdlBalanceSheet extends MdlMother
{
    protected $rekening;
    protected $accountBehavior;
    protected $tableNames;
    protected $selectKoloms;
    protected $filters;

    //region getter setter

    public function __construct()
    {
        parent::__construct();
        $this->filters = array(
            "periode" => "forever",
        );
        $this->tableNames = "rek_cache";
        $this->selectKoloms = array(
            "rekening",
            "after_saldo",
        );
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getTableName()
    {
        return $this->tableNames;
    }

    //endregion

    public function setTableName($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    public function lookupBalanceSheet()
    {
        $arrkolom = "";
        foreach ($this->selectKoloms as $kolom) {
            $arrkolom .= "$kolom,";
        }
        $arrkolom = rtrim($arrkolom, ",");

        $this->db->select($arrkolom);
        $this->db->where($this->filters);
        $q = $this->db->get($this->tableNames)->result();

        return $q;

    }
}