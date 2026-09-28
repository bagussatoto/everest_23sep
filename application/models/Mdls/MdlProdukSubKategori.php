<?php

//--include_once "MdlHistoriData.php";
class MdlProdukSubKategori extends MdlMother
{
    protected $tableName = "set_menu_statik";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("active='1'", "trash='0'");
//    protected $filters = array("jenis='folder'");

    protected $validationRules = array(
        "label" => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("label");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "sub_kategori_id" => array(
            "label" => "sub_kategori_id",
            "type" => "int", "length" => "24",
            "kolom" => "sub_kategori_id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "label" => array(
            "label" => "label",
            "type" => "varchar", "length" => "255", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
//        "nama" => array(
//            "label" => "nama",
//            "type" => "varchar", "length" => "255", "kolom" => "nama",
//            "inputType" => "text",
//            //--"inputName" => "nama",
//        ),
//        "status" => array(
//            "label" => "status",
//            "type" => "int", "length" => "24", "kolom" => "status",
//            "inputType" => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
//            //--"inputName" => "status",
//        ),
    );
    protected $listedFields = array(
        "id" => "id",
//        "nama" => "nama",
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