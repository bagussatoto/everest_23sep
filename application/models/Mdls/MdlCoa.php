<?php

//--include_once "MdlHistoriData.php";

class MdlCoa extends MdlMother
{

    protected $tableName = "coa";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");
    protected $filters = array("trash='0'");

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "tlp_1"  => array("required", "numberOnly"),
        "status" => array("required"),
        "division" => array("required"),
    );

    protected $listedFieldsView = array("nama","kode");
    protected $fields = array(
        "id"        => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"      => array(
            "label"     => "rekening",
            "type"      => "int", "length" => "64", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
       "kode" => array(
           "label" => "coa",
           "type" => "int", "length" => "24",
           // "kolom" => "kode",
           "inputType" => "text",
           // "reference" => "MdlDiv",
       ),
        "alias"      => array(
            "label"     => "alias",
            "type"      => "int", "length" => "64", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),



    );
    protected $listedFields = array(
        "nama"   => "name",
        "kode" => "cart of account",
        "alias" => "alias",

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