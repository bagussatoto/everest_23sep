<?php

//--include_once "MdlHistoriData.php";

class MdlDtaModal2 extends MdlMother
{

    protected $tableName = "per_employee";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
//        "tlp_1"  => array("required", "numberOnly"),
        "status" => array("required"),
        "ppn" => array("required"),
//        "division" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"        => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"      => array(
            "label"     => "nama",
            "type"      => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "npwp"      => array(
            "label"     => "mo npwp",
            "type"      => "int", "length" => "24", "kolom" => "npwp",
            "inputType" => "text",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "nama",
        ),
        "ppn"      => array(
            "label"     => "tarif",
            "type"      => "varchar", "length" => "24", "kolom" => "ppn",
            "inputType" => "combo",
            "dataSource" => array(15 => "15%", 30 => "30%"),
            //--"inputName" => "nama",
        ),
        "ppn_status"      => array(
            "label"     => "status",
            "type"      => "varchar", "length" => "24", "kolom" => "ppn_status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "nama",
        ),
//        "division" => array(
//            "label" => "division",
//            "type" => "int", "length" => "24", "kolom" => "div_id",
//            "inputType" => "combo",
//            "reference" => "MdlDiv",
//        ),
        //        "email" => array(
        //            "label" => "email",
        //            "type" =>"int","length"=>"24","kolom" => "email",
        //            "inputType" => "text",
        //            //--"inputName" => "email",
        //        ),
//        "telp"      => array(
//            "label"     => "telp",
//            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
//            "inputType" => "text",
            //--"inputName" => "telp",
//        ),
//        "alamat"    => array(
//            "label"     => "alamat",
//            "type"      => "int", "length" => "24", "kolom" => "alamat",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "kabupaten" => array(
//            "label"     => "kabupaten",
//            "type"      => "int", "length" => "24", "kolom" => "kabupaten",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "propinsi"  => array(
//            "label"     => "propinsi",
//            "type"      => "int", "length" => "24", "kolom" => "propinsi",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//
//        "status" => array(
//            "label"      => "status",
//            "type"       => "int", "length" => "24", "kolom" => "status",
//            "inputType"  => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
//        ),


    );
    protected $listedFields = array(
        "nama"   => "name",
//        "alamat" => "address",

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