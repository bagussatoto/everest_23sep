<?php

class MdlTimWorkProject extends MdlMother
{
    protected $tableName = "project_tim_work";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
    );

//    protected $listedFieldsView = array("nama");
    protected $listedFieldsView = array("employee_nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            "editable"        => false,
            //--"inputName" => "id",
        ),
        "produk_id" => array(
            "label"     => "project",
            "type"      => "int", "length" => "255", "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProdukProject",

            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "produk_nama",
        ),
        "employee_id" => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "employee_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "reference" => "MdlEmployee_all",
            "editable"        => false,
            "kolom_nama" => "employee_nama",
            // "kolom_nama"      => "cabang_nama",
        ),
        "hak_akses_id" => array(
            "label"     => "hak akses",
            "type"      => "int", "length" => "255", "kolom" => "hak_akses_id",
            "inputType" => "combo",
            //--"inputName" => "nama",
            "defaultValue" => "ID",
            "strField"        => "nama",
            "reference" => "MdlProjectAccess",
            "editable"        => true,
            // "kolom_nama"      => "access_nama",
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
        "employee_nama" => "nama",
        "hak_akses_nama" => "hak akses",
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
            "MdlProjectAccess" => array(
                "id"         => "hak_akses_id",    // kolom_src => kolom_target (berisi id src)
                "kolomDatas" => array(
                    "nama" => "hak_akses_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
        );

        return $mdls;

    }

}