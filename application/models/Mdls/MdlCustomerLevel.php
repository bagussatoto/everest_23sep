<?php

//--include_once "MdlHistoriData.php";

class MdlCustomerLevel extends MdlMother
{
    protected $tableName = "per_customer_level";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash='0'",
        //        "status='1'"
    );
    protected $validationRules = array(
        "nama"       => array("required", "singleOnly"),
        // "tlp_1"      => array("numberOnly"),
        // "no_ktp"     => array("numberOnly", "unique", "singleOnly"),
        // "npwp"       => array("unique"),
        //        "nik" => array("required"),
        "status"     => array("required"),
        "image_ktp"  => array("image"),
        "image_npwp" => array("image"),
        // "country" => array("required"),
    );
    protected $unionPairs = array();
    protected $listedFieldsView = array("nama", "tlp_1", "npwp");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        //        "member_id" => array(
        //            "label" => "member ID",
        //            "type" => "varchar", "length" => "50", "kolom" => "member_id",
        //            "inputType" => "text",// hidden
        //            //--"inputName" => "id",
        //        ),
        "name"       => array(
            "label"     => "name",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),

    );
    protected $listedFields = array(
        "nama"         => "name",
        //        "member_id" => "member_id",
        "email"        => "email",
        "tlp_1"        => "phone",

//        "diskon"       => "diskon(%)",
//        "kredit_limit" => "kredit_limit",
        "alamat_1"     => "alamat",
        "kabupaten"      => "Kabupaten/kota",
        "propinsi"      => "Propinsi",
        "country"      => "country",
        "no_ktp"       => "nik",
        "npwp"         => "npwp",
        "image_npwp"   => "image npwp",
        "image_ktp"    => "image ktp",
        "kredit_limit"    => "credit limit",
    );
    protected $connectingData = array(
        "MdlAccounts" => array(
            array(
                "path"          => "Mdls",
                "fungsi"        => "addExtern_coa_tk",
                "staticOptions" => "010201",// piutang usaha lokal
                "fields"        => array(
                    "extern_jenis"   => array(
                        "str" => "customer",
                    ),
                    "extern_id"      => array(
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
                    // "is_transaction" => array(
                    //     "str" => "1",
                    // ),
                    "is_gl" => array(
                        "str" => "1",
                    ),
                    "is_neraca" => array(
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
            ),
            array(
                "path"          => "Mdls",
                "fungsi"        => "addExtern_coa_tk",
                "staticOptions" => "011004",// uang muka
                "fields"        => array(
                    "extern_jenis"   => array(
                        "str" => "customer",
                    ),
                    "extern_id"      => array(
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
                    // "is_transaction" => array(
                    //     "str" => "1",
                    // ),
                    "is_gl" => array(
                        "str" => "1",
                    ),
                    "is_neraca" => array(
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
            ),
            array(
                "path"          => "Mdls",
                "fungsi"        => "addExtern_coa_tk",
                "staticOptions" => "020403",// uang mukahutang ke konsumen
                "fields"        => array(
                    "extern_jenis"   => array(
                        "str" => "customer",
                    ),
                    "extern_id"      => array(
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
                    // "is_transaction" => array(
                    //     "str" => "1",
                    // ),
                    "is_gl" => array(
                        "str" => "1",
                    ),
                    "is_neraca" => array(
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
            ),
        ),
    );

    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
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