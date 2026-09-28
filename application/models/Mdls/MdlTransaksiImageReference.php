<?php

class MdlTransaksiImageReference extends MdlMother
{
    protected $tableName = "transaksi_image_reference";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'","trash='0'");

    protected $validationRules = array(
//        "nama" => array("required", "singleOnly", "unique"),
//        "supplier_id" => array("required"),


    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "nama",
            "inputType" => "text",

        ),
        "id_master"   => array(
            "label"     => "id_master",
            "type"      => "int",
            "length"    => "255", "kolom" => "id_master",
            "inputType" => "text",

        ),
        "cdn_link"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "cdn_link",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "oleh_id"   => array(
            "label"     => "oleh_id",
            "type"      => "int",
            "length"    => "255", "kolom" => "oleh_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "oleh_nama"   => array(
            "label"     => "oleh_nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "oleh_nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "status"   => array(
            "label"     => "status",
            "type"      => "int",
            "length"    => "255", "kolom" => "status",
            "inputType" => "text",
        ),


    );
    protected $listedFields = array(
//        "nama" => "name",
//        "supplier_nama" => "supplier",


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

    protected $pairValidate = array();

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

}