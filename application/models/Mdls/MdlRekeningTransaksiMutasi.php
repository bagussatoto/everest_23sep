<?php


class MdlRekeningTransaksiMutasi extends MdlMother
{
    protected $tableName = "aset_berwujud";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "unique","singleOnly"),
        "folders" => array("required"),
    );

    protected $listedFieldsView = array("nama", "label");

    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder"     => array(
            "label"     => "kategory",
            "type"      => "int", "length" => "24", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderAset",

        ),
        "label"      => array(
            "label"     => "label",
            "type"      => "varchar", "length" => "255", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),
        "nama"       => array(
            "label"     => "nama",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
//        "kode" => array(
//            "label"     => "kode",
//            "type"      => "varchar", "length" => "255", "kolom" => "kode",
//            "inputType" => "text",
//            //--"inputName" => "",
//        ),
//        "no_seri" => array(
//            "label"     => "no seri",
//            "type"      => "varchar", "length" => "255", "kolom" => "no_seri",
//            "inputType" => "text",
//            //--"inputName" => "",
//        ),
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

//        "kode"       => "kode",
//        "label"      => "label",
        "nama"       => "nama",
//        "kode"       => "kode",
//        "no_seri"       => "no seri",
        "folders"    => "folder",
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