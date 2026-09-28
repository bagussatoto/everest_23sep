<?php

class MdlRakCabang extends MdlMother
{
    protected $tableName = "lokasi";
    protected $indexFields = "id";
    protected $tableNames = array();

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
        // "cabang_id='0'",
    );

    /* ---------------------------------------
     * dimatikan dulu metodenya belum fix
     * kalau ini hidum mdl ini hanya akan ngluarin data sesuai login dia di cabang mana
     * ----------------------------*/
    // protected $ciFilters = array(
    //     "status" => "1",
    //     "trash"  => "0",
    //     "cabang_id"  => "session.my_cabang_id",
    // );
    protected $validationRules = array(
        "nama" => array("required", "singleOnly", "unique"),
    );
    /* --------------------------------------------
     * tambahan filter validasi data
     * --------------------------------------------*/
    protected $validationRuleFilters = array(
        "cabang_id",
        // "cabang_id",
    );

    protected $kolomAlt = true;
    protected $listedFieldsView = array("nama");
    /* -----------------------------
     * defaultValue
     * untuk yg bersamaan dengan reference value berupa NAMA_KOLOM atau DOT_FUNGSI
     * -----------------------------*/
    protected $fields = array(
        "id"          => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "cabang_nama" => array(
            "label"        => "cabang",
            "type"         => "int",
            "length"       => "24",
            "kolom"        => "cabang_id",
            "inputType"    => "combo",
            "editable"     => true,
            "reference"    => "MdlCabang",
            "strField"     => "nama",
            "kolom_nama"   => "cabang_nama",
            "defaultValue" => ".my_cabang_id",
            // "defaultValue" => "id",
        ),
        "nama"        => array(
            "label"     => "nama",
            "type"      => "text", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //            "reference" => "MdlBank",
            //--"inputName" => "folders",
        ),
        "oleh_id"     => array(
            "label"           => "oleh id",
            "type"            => "int", "length" => "24", "kolom" => "oleh_id",
            "inputType"       => "hidden",
            "referenceFilter" => "",
        ),
        "oleh_nama"   => array(
            "label"           => "oleh nama",
            "type"            => "text", "length" => "24", "kolom" => "oleh_nama",
            "inputType"       => "hidden",
            "referenceFilter" => "",
        ),
        "status"      => array(
            "label"        => "status",
            "type"         => "int", "length" => "24",
            "kolom"        => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "nama"        => "Nomor rak",
        "cabang_nama" => "cabang toko",
        //        "relName" => "relName",

    );
    protected $navFilters = array(
        "label"     => "cabang",
        "mdlFilter" => "MdlCabang",
        "kolomKey"  => "cabang_id",
    );

    //region gs
    public function getValidationRuleFilters()
    {
        return $this->validationRuleFilters;
    }

    public function setValidationRuleFilters($validationRuleFilters)
    {
        $this->validationRuleFilters = $validationRuleFilters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
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

    /* --------------------------
     * jika dimatikan makan navigasi filter tidak muncul
     * ------------------------*/
    public function getNavFilters()
    {
        return $this->navFilters;
    }

    public function setNavFilters($navFilters)
    {
        $this->navFilters = $navFilters;
    }

    //endregion

    public function paramSyncNamaNama()
    {
        $mdls = array(
            // "MdlFolderProduk" => array(
            //     "id"  => "folders",
            //     "str" => "folders_nama",
            // ),
            // "MdlMerek"        => array(
            //     "id"  => "merek_id",
            //     "str" => "merek_nama",
            // ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     "str" => "kendaraan_nama",
            // ),
            "MdlCabang" => array(
                "id"         => "cabang_id",
                "kolomDatas" => array(
                    "nama" => "cabang_nama",

                )
            ),
        );

        return $mdls;

    }
}