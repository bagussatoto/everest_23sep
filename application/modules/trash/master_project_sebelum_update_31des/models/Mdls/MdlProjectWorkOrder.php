<?php

class MdlProjectWorkOrder extends MdlMother
{
    protected $tableName = "project_workorder";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),


    );

    protected $listedFieldsView = array("nama");
//    protected $listedFieldsView = array("employee_nama");
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
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "produk_nama",
        ),

        "qty" => array(
            "label"     => "jumlah",
            "type"      => "int", "length" => "255", "kolom" => "qty",
            "inputType" => "text",
            "editable"        => true,
        ),
        "nama" => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField"        => "nama",
            "editable"        => true,
            // "kolom_nama"      => "cabang_nama",
        ),
        "lokasi" => array(
            "label"     => "lokasi",
            "type"      => "int", "length" => "255", "kolom" => "lokasi",
            "inputType" => "text",
            "editable"        => true,
        ),

        "keterangan" => array(
            "label"     => "keterangan",
            "type"      => "int", "length" => "255", "kolom" => "keterangan",
            "inputType" => "text",
            "editable"        => true,
        ),

//        "employee_id" => array(
//            "label"     => "penanggung jawab",
//            "type"      => "int", "length" => "255", "kolom" => "employee_id",
//            "inputType" => "combo",
//            "strField" => "employee_nama",
//            "editable"        => true,
//            "reference"=>"MdlTimWorkProject",
//            "kolom_nama" => "employee_nama",
//            //untuknyari dari relasi non id dan kolom selsain nama baru bisa 1 tinggat
//            //key_source_id=>key_source_nama
//            "reference_src_rel"=>array(
//                "key_id"=>"employee_id",
//                "key_nama"=>"employee_nama",
////                "employee_nama" =>"employee_nama",
//            ),
//        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "fase_nama" => "Rencana Kerja",
        "nama" => "Tugas",
        "keterangan" => "keterangan",
        "lokasi" => "lokasi",
        "qty" => "qty",
//        "employee_nama" => "pelaksana",
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
        );

        return $mdls;

    }
}