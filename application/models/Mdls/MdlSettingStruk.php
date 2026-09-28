<?php

//--include_once "MdlHistoriData.php";

class MdlSettingStruk extends MdlMother
{
    protected $tableName = "setting_struk";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
         "status='1'",
         "trash='0'",
        // "employee_type='root'",
//        "php_session => '1'",
    );

    protected $validationRules = array(
//        "header"       => array("required", "singleOnly"),
//        "login_name" => array("required", "singleOnly"),
//        "password"   => array("required"),
//        "status"     => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
        ),

        "nama"       => array(
            "label"     => "label",
            "type"      => "text",
            "length"    => "24",
            "kolom"     => "nama",
            "inputType" => "text",
        ),

        "ukuran"       => array(
            "label"     => "Ukuran Printer",
            "type"      => "text",
            "length"    => "24",
            "kolom"     => "jenis_printer",
            "inputType"  => "combo",
            "dataSource" => array(56 => "56mm POS", 80 => "80mm POS"), "defaultValue" => 56,
        ),

        "cetak_logo"       => array(
            "label"     => "Cetak Logo",
            "type"      => "text",
            "length"    => "24",
            "kolom"     => "cetak_logo",
            "inputType"  => "combo",
            "dataSource" => array(1 => "Dengan Logo", 0 => "Tanpa Logo"), "defaultValue" => 1,
        ),

        "logo"       => array(
            "label"     => "Logo",
            "type"      => "text",
            "length"    => "24",
            "kolom"     => "logo",
            "inputType" => "image",
        ),

        "header1"       => array(
            "label"     => "header 1",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "header1",
            "inputType" => "textarea",
        ),

        "header2"       => array(
            "label"     => "header 2",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "header2",
            "inputType" => "textarea",
        ),

        "header3"       => array(
            "label"     => "header 3",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "header3",
            "inputType" => "textarea",
        ),

        "footer1"       => array(
            "label"     => "footer 1",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "footer1",
            "inputType" => "textarea",
        ),

        "footer2"       => array(
            "label"     => "footer 2",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "footer2",
            "inputType" => "textarea",
        ),

        "footer3"       => array(
            "label"     => "footer 3",
            "type"      => "text",
            "length"    => "3",
            "kolom"     => "footer3",
            "inputType" => "textarea",
        ),

        "status"     => array(
            "label"      => "status",
            "type"       => "int",
            "length"     => "24",
            "kolom"      => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
        ),
    );
    protected $listedFields = array(
        "nama"    => "label",
        "logo"    => "logo",
        "header1" => "header 1",
        "header2" => "header 2",
        "header3" => "header 3",
        "footer1" => "footer 1",
        "footer2" => "footer 2",
        "footer3" => "footer 3",
    );
    protected $updateFields = array(
//        "phpsessid" => "phpsesid",
//        "phpsess_dtime" => "phpses_dtime",
//        "php_session" => "php_session",
//        "ipadd" => "ipadd",
//        "devices" => "devices",
    );
    protected $listedUpdateFields = array(
//        "login_name" => array(
//            "label" => "login name",
//            "kolom"     => "nama_login",
//        ),
//        "password" => array(
//            "label" => "password",
//            "kolom"     => "password",
//            "link" => "Data/editone/User",
//            "type" => "password",
//            "replaceValue" => "******",
//        ),
//        "email" => array(
//            "label" => "email",
//            "kolom"     => "email",
//            "link" => "Data/editone/User",
//        ),
//        "telp" => array(
//            "label" => "Phone",
//            "kolom"     => "tlp_1",
//            "link" => "Data/editone/User",
//        ),
//        "last_dtime" => array(
//            "label" => "Last Active",
//            "kolom"     => "last_dtime",
//            "format" => "formatField",
//        ),
//        "status" => array(
//            "label" => "Status",
//            "kolom"     => "status",
//            "replaceValue" => array(
//                0 => "non active",
//                1 => "active",
//            ),
//        ),
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
//        $this->fields['membership']['dataSource'] = $this->config->item('userGroup_root');
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

    public function callSetting($cabang_id, $step_code){

        $condites = array(
          // "trash" => 0,
          "cabang_id" => $cabang_id,
          "tr_step_code" => $step_code,
        );
        $srcs = $this->lookupByCondition($condites)->row();

        return $srcs;
    }
}