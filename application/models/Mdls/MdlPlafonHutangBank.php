<?php

class MdlPlafonHutangBank extends MdlMother
{
    protected $tableName = "dta_plafon_hutang_bank";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "extern_nama" => array("required", "singleOnly"),


    );

    protected $listedFieldsView = array("extern_nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "extern_id" => array(
            "label"     => "bank id",
            "type"      => "int", "length" => "255", "kolom" => "extern_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "extern_nama" => array(
            "label"     => "nama bank",
            "type"      => "int", "length" => "255", "kolom" => "extern_nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "nilai" => array(
            "label"     => "plafon",
            "type"      => "int", "length" => "255", "kolom" => "nilai",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "extern_nama" => "rekening",
        "nilai" => "plafon",
    );

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