<?php

//--include_once "MdlHistoriData.php";

class MdlCustomerProject extends MdlMother
{
    protected $tableName = "per_customers";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash='0'",
        "status='1'",
        "kategori_id='2'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "tlp_1" => array("required", "numberOnly"),
        "no_ktp" => array("required", "numberOnly", "unique", "singleOnly"),
        "npwp" => array("required", "unique"),
//        "nik" => array("required"),
        "status" => array("required"),
        "image_ktp" => array("image"),
        "image_npwp" => array("image"),
        "country" => array("required"),
    );

    protected $unionPairs = array("no_ktp", "npwp");


    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    protected $listedFieldsView = array("nama", "tlp_1", "npwp");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
//        "member_id" => array(
//            "label" => "member ID",
//            "type" => "varchar", "length" => "50", "kolom" => "member_id",
//            "inputType" => "text",// hidden
//            //--"inputName" => "id",
//        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "first_name" => array(
            "label" => "first name",
            "type" => "int", "length" => "255", "kolom" => "nama_depan",
            "inputType" => "text",
            //--"inputName" => "nama_depan",
        ),
        "last_name" => array(
            "label" => "last name",
            "type" => "int", "length" => "255", "kolom" => "nama_belakang",
            "inputType" => "text",
            //--"inputName" => "nama_belakang",
        ),
        //        "login_name" => array(
        //            "label" => "login ID",
        //            "type" => "int", "length" => "24", "kolom" => "nama_login",
        //            "inputType" => "text",
        //            //--"inputName" => "nama_login",
        //        ),
        "email" => array(
            "label" => "email",
            "type" => "varchar", "length" => "45", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "phone" => array(
            "label" => "phone",
            "type" => "varchar", "length" => "45", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "address",
            "type" => "int", "length" => "255", "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten" => array(
            "label" => "district",
            "type" => "int", "length" => "255", "kolom" => "kabupaten",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "propinsi" => array(
            "label" => "province",
            "type" => "int", "length" => "255", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "country" => array(
            "label" => "country",
            "type" => "varchar", "length" => "3", "kolom" => "country",
            "reference" => "MdlCountry",
            "defaultValue" => "ID",
            "inputType" => "combo",
            //--"inputName" => "alamat",
        ),
        "nik" => array(
            "label" => "nik",
            "type" => "int", "length" => "255", "kolom" => "no_ktp",
            "inputType" => "text",
            //--"inputName" => "nik",
        ),
        "image_ktp" => array(
            "label" => "ID card image",
            "type" => "image", "length" => "", "kolom" => "image_ktp",
            "inputType" => "image",
        ),
        "npwp" => array(
            "label" => "npwp",
            "type" => "int", "length" => "255", "kolom" => "npwp",
            "inputType" => "text",
            //--"inputName" => "npwp",
        ),
        "image_npwp" => array(
            "label" => "npwp image",
            "type" => "image", "length" => "", "kolom" => "image_npwp",
            "inputType" => "image",
        ),
        //        "due time" => array(
        //            "label" => "due (in seconds)",
        //            "type" => "int", "length" => "24", "kolom" => "jatuh_tempo",
        //            "inputType" => "text",
        //            //--"inputName" => "jatuh_tempo",
        //        ),
        "jatuh tempo" => array(
            "label" => "due (in days)",
            "type" => "int", "length" => "24", "kolom" => "due_days",
            "inputType" => "text",
            //--"inputName" => "jatuh_tempo",
        ),
        "credit_limit" => array(
            "label" => "credit limit",
            "type" => "int", "length" => "24", "kolom" => "kredit_limit",
            "inputType" => "text",
            //--"inputName" => "kredit_limit",
        ),
        //        "trash" => array(
        //            "label" => "trash",
        //            "type" =>"int","length"=>"24","kolom" => "trash",
        //            "inputType" => "int",
        //            //--"inputName" => "trash",
        //        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "ppn" => array(
        //            "label" => "VAT factor (%)",
        //            "type" =>"int","length"=>"24","kolom" => "ppn",
        //            "inputType" => "number",
        //            //--"inputName" => "ppn",
        //        ),
        "diskon" => array(
            "label" => "discount (%)",
            "type" => "int", "length" => "24", "kolom" => "diskon",
            "inputType" => "number",
            //--"inputName" => "diskon",
        ),
        "attn" => array(
            "label" => "CP",
            "type" => "int", "length" => "255", "kolom" => "contact_person",
            "inputType" => "text",
            //--"inputName" => "person_nama",
        ),

    );

    protected $listedFields = array(
        "nama" => "name",
//        "member_id" => "member_id",
        "email" => "email",
        "tlp_1" => "phone",
        "country" => "country",
        "no_ktp" => "nik",
        "npwp" => "npwp",
        "diskon" => "diskon(%)",
        "kredit_limit" => "kredit_limit",
        "alamat_1" => "alamat",
        "image_npwp" => "image npwp",
        "image_ktp" => "image ktp",
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