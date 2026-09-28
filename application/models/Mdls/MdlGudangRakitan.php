<?php


class MdlGudangRakitan extends MdlMother
{
    protected $tableName = "gudang";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='gudang rakitan'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "tlp_1"  => array("required", "numberOnly"),
        "no_ktp" => array("required", "numberOnly"),
        "npwp"   => array("required"),
        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"   => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "cabang" => array(
            "label"     => "branch",
            "type"      => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            //--"inputName" => "nama",
        ),

        "status" => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "jenis" => array(
        //            "label" => "jenis",
        //            "type" =>"int","length"=>"24","kolom" => "jenis",
        //            "inputType" => "text",
        //            //--"inputName" => "jenis",
        //        ),
        //        "dtime" => array(
        //            "label" => "dtime",
        //            "type" =>"int","length"=>"24","kolom" => "dtime",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "oleh name" => array(
        //            "label" => "pic",
        //            "type" =>"int","length"=>"24","kolom" => "oleh_name",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "keterangan" => array(
        //            "label" => "keterangan",
        //            "type" =>"int","length"=>"24","kolom" => "label",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "pemindahan produk" => array(
        //            "label" => "pemindahan produk",
        //            "type" =>"int","length"=>"24","kolom" => "pemindahan_produk",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
    );
    protected $listedFields = array(
        "nama"      => "name",
        "cabang_id" => "branch",
        "status"    => "status",

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