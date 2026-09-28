<?php

//--include_once "MdlHistoriData.php";

class MdlOpname_xls extends MdlMother
{
    protected $tableName = "stok_opname_xls";
    protected $tableChild = "";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'","cli"=>"0");

    protected $validationRules = array(
//        "nama"   => array("required", "singleOnly"),
//        "tlp_1"  => array("required", "numberOnly"),
//        "no_ktp" => array("required", "numberOnly"),
//        "npwp"   => array("required"),
//        "status" => array("required"),
//        "image_ktp" => array("image"),
//        "image_npwp" => array("image"),
    );

    protected $listedFieldsView = array("nama","oleh_nama");
    protected $fields = array(
        "id"           => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "oleh_id"         => array(
            "label"     => "oleh id",
            "type"      => "int", "length" => "24", "kolom" => "oleh_id",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "oleh_nama"   => array(
            "label"     => "oleh nama",
            "type"      => "int", "length" => "24", "kolom" => "oleh_nama",
            "inputType" => "text",
            //--"inputName" => "nama_depan",
        ),
        "file_name"    => array(
            "label"     => "nama",
            "type"      => "text", "length" => "", "kolom" => "file_name",
            "inputType" => "text",

        ),
        "file_ext"    => array(
            "label"     => "file extension",
            "type"      => "text", "length" => "", "kolom" => "file_ext",
            "inputType" => "text",

        ),
        "opname_dir"    => array(
            "label"     => "kategory",
            "type"      => "file", "length" => "", "kolom" => "opname_dir",
            "inputType" => "file",

        ),
        "full_path"    => array(
            "label"     => "kategory",
            "type"      => "file", "length" => "", "kolom" => "full_path",
            "inputType" => "file",

        ),
        "cabang_id"        => array(
            "label"     => "cabang_id",
            "type"      => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "status"       => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        "sync_cli_time"       => array(
            "label"      => "prosesing time",
            "type"       => "date", "length" => "24", "kolom" => "sync_cli_time",
            "inputType"  => "date",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array();
    protected $fieldChilds = array();

    protected $folderListed = array(
        "id" =>"select",
        "nama" => "category",

    );

    protected $elementsData = array(
        "dtime" => "tanggal",
        "oleh" => "oleh",
        "footerDtime" => "printed on @",
    );


    public function getElementsData()
    {
        return $this->elementsData;
    }


    public function setElementsData($elementsData)
    {
        $this->elementsData = $elementsData;
    }


    public function getFolderListed()
    {
        return $this->folderListed;
    }

    public function setFolderListed($folderListed)
    {
        $this->folderListed = $folderListed;
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