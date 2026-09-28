<?php


class MdlAktivaTetap extends MdlMother
{
    protected $tableName = "aktiva_tetap";
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
//        "folder"     => array(
//            "label"     => "folder",
//            "type"      => "int", "length" => "24", "kolom" => "folders",
//            "inputType" => "combo",
//            "reference" => "MdlFolderPettycash",
//
//        ),
//        "label"      => array(
//            "label"     => "label",
//            "type"      => "int", "length" => "24", "kolom" => "label",
//            "inputType" => "text",
//            //--"inputName" => "label",
//        ),
        "nama"       => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
//        "keterangan" => array(
//            "label"     => "keterangan",
//            "type"      => "int", "length" => "24", "kolom" => "label",
//            "inputType" => "text",
//            //--"inputName" => "",
//        ),
        "deskripsi"  => array(
            "label"     => "deskripsi",
            "type"      => "int", "length" => "24", "kolom" => "deskripsi",
            "inputType" => "text",
            //--"inputName" => "",
        ),
    );
    protected $listedFields = array(
//        "folders"    => "folder",
//        "kode"       => "kode",
//        "label"      => "label",
        "nama"       => "nama",
        "keterangan" => "keterangan",
        "deskripsi" => "deskripsi",
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