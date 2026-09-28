<?php

class MdlTasklistProjectLog extends MdlMother
{
    protected $tableName = "project_tasklist_log";
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
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "produk_id" => array(
            "label"     => "project",
            "type"      => "int", "length" => "255", "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProdukProject",
            "defaultValue"=>"",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "produk_nama",
        ),
        "nama" => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "input_property" => array(
            "label"     => "tipe",
            "type"      => "int", "length" => "255", "kolom" => "input_property",
            "inputType" => "combo",
            "reference" => "MdlInputProperty",

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
        "dtime_start" => "mulai",
        "dtime_end" => "tenggat",
        "nama" => "tugas",
        "employee_nama" => "pelaksana",
        "nilai" => "keterangan",
        "progress_nama" => "status",
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
            "MdlEmployee_all" => array(
                "id"         => "employee_id",    // kolom_src => kolom_target (berisi id src)
                // "str" => "folders_nama",
                "kolomDatas" => array(
                    "nama" => "employee_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
            "MdlProdukProject" => array(
                "id"         => "produk_id",    // kolom_src => kolom_target (berisi id src)
                // "str" => "folders_nama",
                "kolomDatas" => array(
                    "nama" => "produk_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
            "MdlProgresTasklist" => array(
                "id"         => "progress_id",    // kolom_src => kolom_target (berisi id src)
                "kolomDatas" => array(
                    "nama" => "progress_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
        );

        return $mdls;

    }
}