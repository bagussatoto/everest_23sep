<?php

//--include_once "MdlHistoriData.php";

class MdlAddDiscount extends MdlMother
{
    protected $tableName = "add_discount";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "min_qty"  => array("required", "numberOnly"),
        "max_qty" => array("required", "numberOnly"),
//        "discount_persen"   => array("required"),
//        "discount_qty" => array("required"),

    );
    protected $xorPairs = array(
        array("discount_persen","discount_qty"),
    );
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"           => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"         => array(
            "label"     => "discount name",
            "type"      => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "min_qty"   => array(
            "label"     => "min qty",
            "type"      => "int", "length" => "24", "kolom" => "min_qty",
            "inputType" => "text",
            //--"inputName" => "nama_depan",
        ),
        "max_qty"    => array(
            "label"     => "max qty",
            "type"      => "int", "length" => "24", "kolom" => "max_qty",
            "inputType" => "text",
            //--"inputName" => "nama_belakang",
        ),

        "discount_persen"        => array(
            "label"     => "discount(%)",
            "type"      => "varchar", "length" => "24", "kolom" => "discount_persen",
            "inputType" => "text",

        ),
        "discount_qty"         => array(
            "label"     => "discount(qty)",
            "type"      => "int", "length" => "24", "kolom" => "discount_qty",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),


    );
    protected $listedFields = array(
        "nama"  => "nama",
        "min_qty" => "minimal quantity",
        "max_qty" => "maximal quantity",
        "discount_persen"  => "discount(%)",
        "discount_qty"  => "discount(qty)",

    );
    protected $limiteEditor = array("addMany","editMany");


    public function getLimiteEditor()
    {
        return $this->limiteEditor;
    }


    public function setLimiteEditor($limiteEditor)
    {
        $this->limiteEditor = $limiteEditor;
    }


    public function getXorPairs()
    {
        return $this->xorPairs;
    }

    public function setXorPairs($xorPairs)
    {
        $this->xorPairs = $xorPairs;
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