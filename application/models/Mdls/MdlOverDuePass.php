<?php

//--include_once "MdlHistoriData.php";
class MdlOverDuePass extends MdlMother
{

    protected $tableName = "over_due_pass";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("trash='0'");

    protected $validationRules = array(
//        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "customers_id" => array(
            "label" => "customers nama",
            "type" => "int", "length" => "255", "kolom" => "customers_nama",
            "inputType" => "text",
        ),
        "customers_name" => array(
            "label" => "customers nama",
            "type" => "int", "length" => "255", "kolom" => "customers_nama",
            "inputType" => "text",
        ),
        "request_id" => array(
            "label" => "request nama",
            "type" => "int", "length" => "255", "kolom" => "request_id",
            "inputType" => "text",
        ),
        "request_name" => array(
            "label" => "request nama",
            "type" => "int", "length" => "255", "kolom" => "request_nama",
            "inputType" => "text",
        ),
        "oleh_id" => array(
            "label" => "author id",
            "type" => "int", "length" => "255", "kolom" => "oleh_id",
            "inputType" => "text",
        ),
        "oleh_name" => array(
            "label" => "author nama",
            "type" => "int", "length" => "255", "kolom" => "oleh_nama",
            "inputType" => "text",
        ),

        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "255", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "customers_nama" => "customers",
        "request_nama" => "request by",
        "request_dtime" => "date",
        "cabang_nama" => "branch",
    );

    function __construct()
    {
        parent::__construct();
    }

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