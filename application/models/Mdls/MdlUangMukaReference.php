<?php

class MdlUangMukaReference extends MdlMother
{
    protected $tableName = "_rek_pembantu_uang_muka_reference_cache";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("periode='forever'");

    protected $validationRules = array(
//        "nama" => array("required", "singleOnly", "unique"),
//        "supplier_id" => array("required"),


    );

    protected $listedFieldsView = array("extern2_nama");
    protected $listedFieldsSelectItem = array("extern_nama","extern2_nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "extern_id"   => array(
            "label"     => "extern_id",
            "type"      => "int",
            "length"    => "255", "kolom" => "externid",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "extern2_id"   => array(
            "label"     => "extern2_id",
            "type"      => "int",
            "length"    => "255", "kolom" => "extern2_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "extern2_nama"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "extern2_nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),

//        "supplier"          => array(
//            "label"      => "supplier",
//            "type"       => "int",
//            "length"     => "255",
//            "kolom"      => "supplier_id",
//            "inputType"  => "combo",
//            "reference"  => "MdlSupplier",
//            "referenceFilter"  => array(
//                "id" => array(
//                    "var" => "supplier_id"
//                )
//            ),
//            "editable"   => true,
//            "kolom_nama" => "supplier_nama",
//            "add_btn"    => true,
//            // "mdlChild"   => "MdlMerek"
//        ),
//        "supplier_nama"     => array(
//            "label"     => "supplier",
//            "type"      => "int",
//            "length"    => "255",
//            "kolom"     => "supplier_nama",
//            "inputType" => "hidden",
//        ),
//        "status" => array(
//            "label"        => "status",
//            "type"         => "int", "length" => "24", "kolom" => "status",
//            "inputType"    => "combo",
//            "dataSource"   => array(0 => "inactive", 1 => "active"),
//            "defaultValue" => 1,
//            //--"inputName" => "status",
//        ),


    );
    protected $listedFields = array(
//        "nama" => "name",
//        "supplier_nama" => "supplier",


    );

    //region gs
    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
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