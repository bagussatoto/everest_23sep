<?php

//--include_once "MdlHistoriData.php";
class MdlHargaAktivaTetap extends MdlMother
{
    protected $tableName = "price";
    protected $indexFields = "produk_id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("trash='0'", "jenis='aktiva'");

    protected $validationRules = array(
//        "nama" => array("required", "singleOnly"),
//        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"          => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "jenis"       => array(
            "label"     => "jenis",
            "type"      => "int", "length" => "24", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "jenis_value" => array(
            "label"     => "jenis",
            "type"      => "int", "length" => "24", "kolom" => "jenis_value",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "produk_id"   => array(
            "label"     => "produk_id",
            "type"      => "int", "length" => "24", "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlSupplies",
            //--"inputName" => "label",
        ),
        "cabang_id"   => array(
            "label"     => "cabang_id",
            "type"      => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            //--"inputName" => "nama",
        ),
        "nilai"       => array(
            "label"     => "nilai",
            "type"      => "int", "length" => "24", "kolom" => "nilai",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "oleh_id"     => array(
            "label"     => "oleh_id",
            "type"      => "int", "length" => "24", "kolom" => "oleh_id",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "oleh_nama"   => array(
            "label"     => "oleh_name",
            "type"      => "int", "length" => "24", "kolom" => "oleh_nama",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "status" => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "produk_id"   => "product",
        "cabang_id"   => "branch",
        "jenis_value" => "price",
        "nilai"       => "price value",
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