<?php

class MdlProdukSeries extends MdlMother
{
    protected $tableName = "produk_series";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
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
            //--"inputName" => "nama",
            // "reference"  => false,
            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        // "supplier"          => array(
        //     "label"      => "supplier",
        //     "type"       => "int",
        //     "length"     => "255",
        //     "kolom"      => "supplier_id",
        //     "inputType"  => "combo",
        //     "reference"  => "MdlSupplier",
        //     "strField"   => "nama",
        //     "editable"   => true,
        //     "kolom_nama" => "supplier_nama",
        //     "add_btn"    => true,
        //     // "childDefault"   => "MdlMerek"
        // ),
        // "supplier_nama"     => array(
        //     "label"     => "supplier",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "supplier_nama",
        //     "inputType" => "hidden",
        // ),
        "status" => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nama" => "name",
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


    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlSupplier"       => array(
                "id"         => "supplier_id",
                "kolomDatas" => array(
                    "nama" => "supplier_nama",
                ),
            ),
        );
        return $mdls;
    }

}