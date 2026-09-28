<?php

class MdlListQris extends MdlMother
{
    protected $tableName = "bank_idn";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='qris'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"      => array("required", "singleOnly"),
        "folders_nama"    => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"       => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder"   => array(
            "label"      => "BANK PENERBIT QRIS",
            "type"       => "int", "length" => "24", "kolom" => "folders",
            "inputType"  => "combo",
            "reference"  => "MdlListAllBank",
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "folders_nama",
//            "add_btn"    => true,
        ),
        "folders_nama"     => array(
            "label"      => "jenis",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "folders_nama",
            "inputType"  => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "nama"     => array(
            "label"     => "NAMA QRIS (TOKO/USAHA)",
            "type"      => "text", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "keterangan"     => array(
            "label"     => "keterangan tambahan",
            "type"      => "varchar", "length" => "4", "kolom" => "keterangan",
            "inputType" => "textarea",
            //--"inputName" => "nama",
        ),
        "status"   => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "folders_nama"   => "BANK PENERBIT",
        "nama"      => "NAMA QRIS (TOKO/USAHA)",
        "keterangan"      => "keterangan",
    );

    //region gs
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
    //endregion

    /*----------------------------------------------------------------
     * auto penambahan COA, bisa dugunakan keperluan lain
     * konnecting ke model yg lain
     * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            "path"          => "Mdls",
            "fungsi"        => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            "staticOptions" => "1010010010",
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
                "is_hutang" => array(
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

}