<?php

class MdlPos extends MdlMother
{

    protected $fields = array();
    protected $indexFieldName = "id";
    protected $fieldContents = array();
    protected $historyEnabled;
    protected $validationRules = array(
        "name"      => array("required", "unique"),
        "nama_login" => array("required", "alphanumeric", "unique"),
        "password"  => array("required"),
        "address"   => array("required"),
        //            "outlet_id" => array("required"),
        "email"     => array("required"),
        "division" => array("required"),
        "div_id" => array("required"),
        "cabang_id" => array("required"),
    );
    protected $tableName = "per_employee";
    protected $tableName__tmp;
    protected $tableName__history;
    protected $unlistedFields = array();
    protected $listedFields = array(
        "nama"       => "sub-branch name",
        "cabang_id"       => "branch",
        "nama_login" => "login name",
        "tlp_1"      => "phone",
        "email"      => "email",
        //            "active"=>"is active?",
    );
    protected $listedFields_compact = array("loginName", "phone1", "email",);
    protected $relations = array(); //===isi array berupa data model
    protected $selfRelation = false;


    protected $sortby;
    protected $customLink = array();

    protected $filters = array(
        "status='1'",
        "trash='0'",
        "employee_type='employee_session'",
        "ghost='0",
        //        "php_session => '1'",
    );

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    // <editor-fold defaultstate="collapsed" desc="getter-setter">
    public function getFields()
    {
        return $this->fields;
    }

    public function getIndexFieldName()
    {
        return $this->indexFieldName;
    }

    public function getFieldContents()
    {
        return $this->fieldContents;
    }

    public function getHistoryEnabled()
    {
        return $this->historyEnabled;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getUnlistedFields()
    {
        return $this->unlistedFields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function getSelfRelation()
    {
        return $this->selfRelation;
    }

    public function getSelfCategorySpec()
    {
        return $this->selfCategorySpec;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getChild()
    {
        return $this->child;
    }

    public function getSortby()
    {
        return $this->sortby;
    }

    public function setFields($fields)
    {

        $this->fields = $fields;
    }

    public function setIndexFieldName($indexFieldName)
    {
        $this->indexFieldName = $indexFieldName;
    }

    public function setFieldContents($fieldContents)
    {
        $this->fieldContents = $fieldContents;
    }

    public function setHistoryEnabled($historyEnabled)
    {
        $this->historyEnabled = $historyEnabled;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setUnlistedFields($unlistedFields)
    {
        $this->unlistedFields = $unlistedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    public function setSelfRelation($selfRelation)
    {
        $this->selfRelation = $selfRelation;
    }

    public function setSelfCategorySpec($selfCategorySpec)
    {
        $this->selfCategorySpec = $selfCategorySpec;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function setChild($child)
    {
        $this->child = $child;
    }

    public function setSortby($sortby)
    {
        $this->sortby = $sortby;//parent::setSortby($sortby);
    }

    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getTableNameHistory()
    {
        return $this->tableName__history;
    }

    public function setTableNameHistory($tableName__history)
    {
        $this->tableName__history = $tableName__history;
    }

    public function getListedFieldsCompact()
    {
        return $this->listedFields_compact;
    }

    public function setListedFieldsCompact($listedFields_compact)
    {
        $this->listedFields_compact = $listedFields_compact;
    }

    public function getCustomLink()
    {
        return $this->customLink;
    }

    public function setCustomLink($customLink)
    {
        $this->customLink = $customLink;
    }

// </editor-fold>

    public function __construct()
    {


        $this->tableName__tmp = $this->tableName . "__tmp";
        $this->tableName__history = $this->tableName . "__history";
        $this->fields = array(
            "id"        =>
                array(
                    "type"      => "int",
                    "label"     => "id",
                    "inputType" => "hidden",
                ),
            "name"      =>
                array(
                    "type"   => "varchar",
                    "kolom"  => "nama",
                    "label"  => "nama pegawai",
                    "length" => "16",
                ),
            "cabang" => array(
                "label" => "branch",
                "type" => "int", "length" => "24", "kolom" => "cabang_id",
                "inputType" => "combo",
                "reference" => "MdlCabang",
                "referenceFilter" => array(
                    "id<>.-1"
                ),
            ),
            "division"  => array(
                "label"     => "division",
                "type"      => "int", "length" => "24", "kolom" => "div_id",
                "inputType" => "combo",
                "reference" => "MdlDiv",
            ),
            "loginName" =>
                array(
                    "type"   => "varchar",
                    "kolom"  => "nama_login",
                    "label"  => "nama login",
                    "length" => "16",
                ),
            "password"  =>
                array(
                    "type"      => "varchar",
                    "kolom"     => "password",
                    "label"     => "sandi",
                    "length"    => "128",
                    "inputType" => "password",
                ),
            "alamat"    => array(
                "label"     => "alamat",
                "type"      => "int", "length" => "24", "kolom" => "alamat_1",
                "inputType" => "text",
                //--"inputName" => "alamat",
            ),
            "telp"      => array(
                "label"     => "phone",
                "type"      => "int", "length" => "24", "kolom" => "tlp_1",
                "inputType" => "text",
                //--"inputName" => "telp",
            ),


            "email" => array(
                "label"     => "email",
                "type"      => "int", "length" => "24", "kolom" => "email",
                "inputType" => "text",

            ),

            "membership" =>
                array(
                    "type"       => "mediumblob",
                    "label"      => "hak akses",
                    "kolom"      => "membership",
                    "inputType"  => "checkbox",
                    //"dataSource" => $this->config->item('accountConfig'),
                    "dataSource" => $this->config->item('userGroup_pos'),
                ),

            "active" =>
                array(
                    "type"         => "varchar",
                    "kolom"        => "status",
                    "label"        => "Status aktif",
                    "length"       => "3",
                    "inputType"    => "combo",
                    "dataSource"   => array(0 => "Tidak aktif", 1 => "Aktif"),
                    "defaultValue" => 1,
                ),
        );

    }


}
