<?php

class MdlKirimNotif extends MdlMother
{
    protected $tableName = "kirim_email";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array();

    protected $validationRules = array(
//        "nama" => array("required", "singleOnly"),


    );

    protected $listedFieldsView = array("msg");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "tr_id"   => array(
            "label"     => "tr_id",
            "type"      => "int", "length" => "24", "kolom" => "tr_id",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "dtime" => array(
            "label"     => "nama bank",
            "type"      => "date", "length" => "255", "kolom" => "dtime",
            "inputType" => "date",
            //--"inputName" => "nama",
//            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "msg" => array(
            "label"     => "msg",
            "type"      => "text", "length" => "255", "kolom" => "msg",
            "inputType" => "text",
            //--"inputName" => "nama",
//            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "ipadd" => array(
            "label"     => "ipadd",
            "type"      => "text", "length" => "255", "kolom" => "ipadd",
            "inputType" => "text",
            //--"inputName" => "nama",
//            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "tanggal" => array(
            "label"     => "tanggal",
            "type"      => "date", "length" => "255", "kolom" => "tanggal",
            "inputType" => "date",
            //--"inputName" => "nama",
//            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "jenis" => array(
            "label"     => "jenis",
            "type"      => "text", "length" => "255", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "nama",
//            "strField"        => "nama",
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
        "dtime" => "dtime",
        "tanggal" => "tanggal",
        "msg" => "msg",
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