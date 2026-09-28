<?php

//--include_once "MdlHistoriData.php";

class MdlEmployeeGudang_shadow extends MdlMother
{
    protected $tableName = "per_employee";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
        "employee_type='gudang'",
        "ghost='1",
    );

    protected $validationRules = array(
        "name" => array("required", "singleOnly", "unique"),
        "login_name" => array("required", "singleOnly", "unique"),
        "password" => array("required"),
        "status" => array("required"),
        "country" => array("required"),
        "division" => array("required"),
        "div_id" => array("required"),
        "cabang_id" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "division" => array(
            "label" => "division",
            "type" => "int", "length" => "24", "kolom" => "div_id",
            "inputType" => "combo",
            "reference" => "MdlDiv",
        ),
        //        "first_name" => array(
        //            "label" => "first name",
        //            "type" => "int", "length" => "24", "kolom" => "nama_depan",
        //            "inputType" => "text",
        //        ),
        //        "last_name" => array(
        //            "label" => "last name",
        //            "type" => "int", "length" => "24", "kolom" => "nama_belakang",
        //            "inputType" => "text",
        //        ),
        "login_name" => array(
            "label" => "login ID",
            "type" => "int", "length" => "24", "kolom" => "nama_login",
            "inputType" => "text",
            //--"inputName" => "nama_login",
        ),
        "password" => array(
            "label" => "password",
            "type" => "int", "length" => "24", "kolom" => "password",
            "inputType" => "password",
            //--"inputName" => "nama_login",
        ),
        "email" => array(
            "label" => "email",
            "type" => "int", "length" => "24", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp" => array(
            "label" => "telp",
            "type" => "int", "length" => "24", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "hp" => array(
            "label" => "handphone",
            "type" => "int", "length" => "24", "kolom" => "tlp_2",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "alamat",
            "type" => "int", "length" => "24", "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten" => array(
            "label" => "district",
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
//        "country" => array(
//            "label" => "country",
//            "type" => "varchar", "length" => "3", "kolom" => "country",
//            "reference" => "MdlCountry",
//            "defaultValue" => "ID",
//            "inputType" => "combo",
//            //--"inputName" => "alamat",
//        ),
        "cabang" => array(
            "label" => "cabang",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            "referenceFilter" => array(
                "id<>.18"
            ),
        ),
        "gudang" => array(
            "label" => "gudang",
            "type" => "int", "length" => "24", "kolom" => "gudang_id",
            "inputType" => "combo",
            "reference" => "MdlGudang",
        ),

        "membership" => array(
            "type" => "mediumblob",
            "label" => "access rights",
            "kolom" => "membership",
            "inputType" => "checkbox",
            //                "dataSource" => $this->config->item('userGroups'),
            "dataSource" => array(//===see __construct
            ),
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        "phpsessid" => array(
            "label" => "alamat",
            "type" => "varchar", "length" => "", "kolom" => "phpsessid",
            "inputType" => "hidden",
        ),
        "phpsess_dtime" => array(
            "label" => "alamat",
            "type" => "varchar", "length" => "", "kolom" => "phpsess_dtime",
            "inputType" => "hidden",
        ),
    );
    protected $listedFields = array(
        "div_id" => "division",
        "nama" => "name",
        "nama_login" => "login name",
        "alamat_1" => "address",
        "email" => "email",
        "tlp_1" => "phone",
        "cabang_id" => "branch",
        "gudang_id" => "gudang",
        "country" => "country",

    );
    protected $updateFields = array(
        "phpsessid" => "phpsesid",
        "phpsess_dtime" => "phpses_dtime",
        "php_session" => "php_session",
        "ipadd" => "ipadd",
        "devices" => "devices",
    );


    public function __construct()
    {
        $this->fields['membership']['dataSource'] = $this->config->item('userGroup_gudang');
        $this->load->helper("he_access_right");
        $this->fields['membership']['groupTransaksiLabel'] = groupAccessLabel_he_access_right();
    }

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

    //region gs
    public function getTableName()
    {
        return $this->tableName;
    }

    public function getUpdateFields()
    {
        return $this->updateFields;
    }

    public function setUpdateFields($updateFields)
    {
        $this->updateFields = $updateFields;
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


}