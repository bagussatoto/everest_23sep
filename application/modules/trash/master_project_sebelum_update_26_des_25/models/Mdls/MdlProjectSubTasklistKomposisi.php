<?php

//--include_once "MdlHistoriData.php";

class MdlProjectSubTasklistKomposisi extends MdlMother
{
    protected $tableName = "project_sub_tasklist_komposisi";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "dtime" => array(
            "label"     => "tgl",
            "type"      => "int", "length" => "24", "kolom" => "dtime",
            "inputType" => "text",
        ),
        "produk_id" => array(
            "label"     => "tgl",
            "type"      => "int", "length" => "24", "kolom" => "produk_id",
            "inputType" => "text",
        ),
        "fase_id" => array(
            "label"     => "fase_id",
            "type"      => "int", "length" => "24", "kolom" => "fase_id",
            "inputType" => "text",
        ),
        "project_work_order_id" => array(
            "label"     => "project_work_order_id",
            "type"      => "int", "length" => "24", "kolom" => "project_work_order_id",
            "inputType" => "text",
        ),
        "aktivitas" => array(
            "label"     => "aktivitas",
            "type"      => "int", "length" => "24", "kolom" => "aktivitas",
            "inputType" => "text",
        ),


    );

    protected $listedFields = array(
        "nama"     => "nama",
        "label"     => "label",
    );

    public function __construct()
    {

    }
    //region gs
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
    //endregion

}