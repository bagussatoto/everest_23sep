<?php


class MdlExpense extends MdlMother
{
    protected $tableName = "pettycash";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder"     => array(
            "label"     => "folder",
            "type"      => "int", "length" => "24", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderPettycash",

        ),
        "label"      => array(
            "label"     => "label",
            "type"      => "int", "length" => "255", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),
        "tipe"      => array(
            "label"     => "tipe",
            "type"      => "varchar", "length" => "255", "kolom" => "tipe",
            "inputType" => "radio",
            "dataSource" => array("pettycash" => "pettycash", "local" => "local","import"=>"import"), "defaultValue" => "pettycash",
            //--"inputName" => "label",
        ),
        "nama"       => array(
            "label"     => "nama",
            "type"      => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "keterangan" => array(
            "label"     => "keterangan",
            "type"      => "varchar", "length" => "255", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "deskripsi"  => array(
            "label"     => "deskripsi",
            "type"      => "varchar", "length" => "255", "kolom" => "deskripsi",
            "inputType" => "text",
            //--"inputName" => "",
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
        "folders"    => "folder",
//        "kode"       => "kode",
        "label"      => "label",
        "nama"       => "nama",
        "deskripsi" => "keterangan",
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