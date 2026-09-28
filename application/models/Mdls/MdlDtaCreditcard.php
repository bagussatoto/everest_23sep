<?php

class MdlDtaCreditcard extends MdlMother
{
    protected $tableName = "bank";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='creditcard'",
        "status='1'",
        "trash='0'"
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "folders" => array("required"),
        "kartu_id" => array("required"),
//        "swift" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder" => array(
            "label" => "bank",
            "type" => "int",
            "length" => "24",
            "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlBank",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "folders_nama",
//            "add_btn" => true,
            //--"inputName" => "folders",
        ),
        "folders_nama" => array(
            "label" => "folder nama",
            "type" => "int",
            "length" => "255",
            "kolom" => "folders_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "kartu_id" => array(
            "label" => "jenis kartu",
            "type" => "int",
            "length" => "24",
            "kolom" => "kartu_id",
            "inputType" => "combo",
            "reference" => "MdlCreditCard",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "kartu_nama",
//            "add_btn" => true,
            //--"inputName" => "folders",
        ),
        "kartu_nama" => array(
            "label" => "kartu nama",
            "type" => "int",
            "length" => "255",
            "kolom" => "kartu_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "nama" => array(
            "label" => "label",
            "type" => "int",
            "length" => "255",
            "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "alias" => array(
            "label" => "pemegang credit card",
            "type" => "int",
            "length" => "255",
            "kolom" => "alias",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int",
            "length" => "24",
            "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "kartu_nama" => "jenis card",
        "folders_nama" => "bank",
        "nama" => "label/nama",
        "alias" => "pemegang credit card",

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
            "MdlBank" => array(
                "id" => "folders",
                "kolomDatas" => array(
                    "nama" => "folders_nama",
                ),
            ),
            "MdlCreditCard" => array(
                "id" => "kartu_id",
                "kolomDatas" => array(
                    "nama" => "kartu_nama",
                ),
            ),

        );

        return $mdls;

    }

}