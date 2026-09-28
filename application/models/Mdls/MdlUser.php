<?php

//--include_once "MdlHistoriData.php";

class MdlUser extends MdlMother
{
    protected $tableName = "per_employee";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        // "status='1'",
        // "trash='0'",
        // "employee_type='root'",
//        "php_session => '1'",
    );

    protected $validationRules = array(
        "name"       => array("required", "singleOnly"),
        "login_name" => array("required", "singleOnly"),
        "password"   => array("required"),
        "status"     => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"       => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),

        "nama_login" => array(
            "label"     => "login ID",
            "type"      => "int", "length" => "24", "kolom" => "nama_login",
            "inputType" => "text",
            //--"inputName" => "nama_login",
        ),
        "password"   => array(
            "label"     => "password",
            "type"      => "password", "length" => "24", "kolom" => "password",
            "inputType" => "password",
            //--"inputName" => "nama_login",
        ),
        "email"      => array(
            "label"     => "email",
            "type"      => "int", "length" => "24", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "tlp_1"       => array(
            "label"     => "phone",
            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "tlp_2"       => array(
            "label"     => "handphone",
            "type"      => "int", "length" => "24", "kolom" => "tlp_2",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat_1"     => array(
            "label"     => "alamat",
            "type"      => "int", "length" => "24", "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "membership" => array(
            "type"       => "mediumblob",
            "label"      => "access rights",
            "kolom"      => "membership",
            "inputType"  => "checkbox",
            //                "dataSource" => $this->config->item('userGroup_root'),
            "dataSource" => array(//===see __construct
            ),
        ),
        "status"     => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        "phpsessid" =>array(
            "label"     => "alamat",
            "type"      => "varchar", "length" => "", "kolom" => "phpsessid",
            "inputType" => "hidden",
        ),
        "phpsess_dtime" =>array(
            "label"     => "alamat",
            "type"      => "varchar", "length" => "", "kolom" => "phpsess_dtime",
            "inputType" => "hidden",
        ),
    );
    protected $listedFields = array(
        "nama"       => "name",
        "nama_login" => "login name",
//        "alamat_1"   => "address",
        "email"      => "email",
        "tlp_1"      => "phone",

    );
    protected $updateFields = array(
        "phpsessid" => "phpsesid",
        "phpsess_dtime" => "phpses_dtime",
        "php_session" => "php_session",
        "ipadd" => "ipadd",
        "devices" => "devices",
    );
    protected $listedUpdateFields = array(
        "login_name" => array(
            "label" => "login name",
            "kolom"     => "nama_login",
        ),
        "password" => array(
            "label" => "password",
            "kolom"     => "password",
            "link" => "Data/editone/User",
            "type" => "password",
            "replaceValue" => "******",
        ),
        "email" => array(
            "label" => "email",
            "kolom"     => "email",
            "link" => "Data/editone/User",
        ),
        "telp" => array(
            "label" => "Phone",
            "kolom"     => "tlp_1",
            "link" => "Data/editone/User",
        ),
        "last_dtime" => array(
            "label" => "Last Active",
            "kolom"     => "last_dtime",
            "format" => "formatField",
        ),
        "e-sign" => array(
            "label" => "E-Signature",
            "kolom"     => "esignature_img",
            "img" => "enable",
        ),
        "status" => array(
            "label" => "Status",
            "kolom"     => "status",
            "replaceValue" => array(
                0 => "non active",
                1 => "active",
            ),
        ),
    );

    public function getListedUpdateFields()
    {
        return $this->listedUpdateFields;
    }

    public function setListedUpdateFields($listedUpdateFields)
    {
        $this->listedUpdateFields = $listedUpdateFields;
    }




    public function __construct()
    {
        $this->fields['membership']['dataSource'] = $this->config->item('userGroup_root');
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