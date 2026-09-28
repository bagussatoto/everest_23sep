<?php

//--include_once "MdlHistoriData.php";
class MdlCustomerAddress extends MdlMother
{

    protected $tableName = "address";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='shipment'", "extern_type='customer'", "status='1'", "trash='0'");
    protected $sortBy = array(
        "kolom" => "alias",
        "mode" => "ASC",
    );
    protected $validationRules = array(

        "tlp_1" => array("required", "numberOnly"),
        "no_ktp" => array("required", "numberOnly"),
        "extern_id" => array("required"),
        "alias" => array("required"),
        "npwp" => array("required"),
        "status" => array("required"),
        "tlp" => array("required"),
        "alamat" => array("required"),
        "kabupaten" => array("required"),
        "propinsi" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "customer" => array(
            "label" => "customer",
            "type" => "int", "length" => "24", "kolom" => "extern_id",
            "inputType" => "combo",
            "reference" => "MdlCustomer",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "extern_name",
            "defaultValue" => "id",
            //--"inputName" => "nama",
        ),

        "alias" => array(
            "label" => "ATTN",
            "type" => "varchar", "length" => "64", "kolom" => "alias",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "email" => array(
            "label" => "email",
            "type" => "int", "length" => "24", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp" => array(
            "label" => "phone",
            "type" => "int", "length" => "24", "kolom" => "tlp",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "telp2" => array(
            "label" => "phone#2",
            "type" => "int", "length" => "24", "kolom" => "tlp_2",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "telp3" => array(
            "label" => "phone#3",
            "type" => "int", "length" => "24", "kolom" => "tlp_3",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "address",
            "type" => "int", "length" => "255", "kolom" => "alamat",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kelurahan" => array(
            "label" => "kelurahan",
            "type" => "int", "length" => "24", "kolom" => "kelurahan",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kecamatan" => array(
            "label" => "kecamatan",
            "type" => "int", "length" => "24", "kolom" => "kecamatan",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten" => array(
            "label" => "kabupaten",
            "type" => "int", "length" => "24", "kolom" => "kabupaten",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "propinsi" => array(
            "label" => "province",
            "type" => "int", "length" => "24", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),

        "kodepos" => array(
            "label" => "zip code",
            "type" => "varchar", "length" => "6", "kolom" => "kodepos",
            "inputType" => "text",
            //--"inputName" => "alamat",
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
        "extern_id" => "customer",
        "alias" => "ATTN",
        "alamat" => "address",
        "kecamatan" => "kecamatan",
        "kabupaten" => "kabupaten",
        "propinsi" => "propinsi",
    );

    function __construct()
    {
        parent::__construct();
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