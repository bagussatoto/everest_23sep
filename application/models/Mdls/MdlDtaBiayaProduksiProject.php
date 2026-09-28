<?php

//--include_once "MdlHistoriData.php";

class MdlDtaBiayaProduksiProject extends MdlMother
{

    protected $tableName          = "dta_biayaproduksi";
    protected $indexFields        = "id";
    protected $listedFieldsForm   = array();
    protected $listedFieldsHidden = array();
    protected $search;

//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");

    protected $filters = array(
        "status='1'",
        "trash='0'"
    );

    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "cat_id"  => array("required"),
        "status" => array("required"),
//        "division" => array("required"),
    );

    protected $listedFieldsView = array("nama");

    protected $fields = array(
        "id"        => array(
            "label"     => "id",
            "type"      => "int", "length" => "240", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"      => array(
            "label"     => "nama",
            "type"      => "int", "length" => "240", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "cat_id" => array(
            "label" => "kategori biaya",//kategori
            "type" => "int",
            "length" => "255",
            "kolom" => "cat_id",
            "inputType" => "combo",
            "reference" => "MdlProdukRakitanPreBiaya",
            "strField" => "nama",
//            "editable" => true,
            "kolom_nama" => "cat_nama",
        ),
        "coa_code"      => array(
            "label"     => "coa_code",
            "type"      => "int", "length" => "240", "kolom" => "coa_code",
//            "inputType" => "text",
            "inputType" => "hidden",// hidden
            //--"inputName" => "nama",
        ),
//        "division" => array(
//            "label" => "division",
//            "type" => "int", "length" => "24", "kolom" => "div_id",
//            "inputType" => "combo",
//            "reference" => "MdlDiv",
//        ),
        //        "email" => array(
        //            "label" => "email",
        //            "type" =>"int","length"=>"24","kolom" => "email",
        //            "inputType" => "text",
        //            //--"inputName" => "email",
        //        ),
//        "telp"      => array(
//            "label"     => "telp",
//            "type"      => "int", "length" => "24", "kolom" => "tlp_1",
//            "inputType" => "text",
            //--"inputName" => "telp",
//        ),
//        "alamat"    => array(
//            "label"     => "alamat",
//            "type"      => "int", "length" => "24", "kolom" => "alamat",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "kabupaten" => array(
//            "label"     => "kabupaten",
//            "type"      => "int", "length" => "24", "kolom" => "kabupaten",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//        "propinsi"  => array(
//            "label"     => "propinsi",
//            "type"      => "int", "length" => "24", "kolom" => "propinsi",
//            "inputType" => "text",
            //--"inputName" => "alamat",
//        ),
//
//        "status" => array(
//            "label"      => "status",
//            "type"       => "int", "length" => "24", "kolom" => "status",
//            "inputType"  => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
//        ),
    );
    protected $listedFields = array(
        "nama"   => "nama biaya",
        "project_id"   => "untuk project",

//        "cat_nama"   => "kategori biaya",
//        "alamat" => "address",
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

    protected $connectingData = array(
        "MdlAccounts" => array(
            "path"          => "Mdls",
            "fungsi"        => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            // "staticOptions" => "0601",//old
            "staticOptions" => "6020",//new coa
            "fields"        => array(
                "extern_jenis"   => array(
                    "str" => "rekening_in",
                ),
                "extern_id"      => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening"      => array(
                    "var_main" => "mainInsertId",
                ),
                "head_name"      => array(
                    "var_main" => "nama",
                ),
                "p_head_name"    => array(
                    "var_main" => "strHead_code",
                ),
                "create_by"      => array(
                    "var_main" => "my_name",
                ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "is_active"      => array(
                    "str" => "1",
                ),
                "is_transaction" => array(
                    "str" => "1",
                ),
                "is_rekening_pembantu" => array(
                    "str" => "1",
                ),
                "is_gl" => array(
                    "str" => "1",
                ),
            ),
            "updateMain"    => array(
                "condites" => array(
                    "id" => "mainInsertId",
                ),
                "datas"    => array(
                    "coa_code" => "lastInset_code",
                )
            )
        )
    );

    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }
    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlProdukRakitanPreBiaya" => array(
                "id" => "folders",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "cat_nama",
                ),
            ),

        );

        return $mdls;

    }

}