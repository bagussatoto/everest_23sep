<?php

//--include_once "MdlHistoriData.php";

class MdlCliLogTime extends MdlMother
{

    protected $tableName = "log_load_time_cli";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();
    protected $validationRules = array();
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "judul" => array(
            "label" => "judul",
            "type" => "varchar",
            "length" => "255",
            "kolom" => "judul",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "waktu_start" => array(
            "label" => "waktu_start",
            "type" => "text",
            "length" => "100",
            "kolom" => "waktu_start",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "waktu_stop" => array(
            "label" => "waktu_stop",
            "type" => "text",
            "length" => "100",
            "kolom" => "waktu_stop",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "waktu" => array(
            "label" => "waktu",
            "type" => "text",
            "length" => "100",
            "kolom" => "waktu",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "transaksi_id" => array(
            "label" => "transaksi_id",
            "type" => "int",
            "length" => "11",
            "kolom" => "transaksi_id",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "nomer" => array(
            "label" => "nomer",
            "type" => "text",
            "length" => "100",
            "kolom" => "nomer",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
    );
    protected $listedFields = array(
        "nama" => "name",
        "alamat" => "address",

    );

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

}