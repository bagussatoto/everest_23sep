<?php

//--include_once "MdlHistoriData.php";

class MdlRealTimePosData extends MdlMother
{
    protected $tableName = "transaksi_data_realtimepos";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
        "employee_type='employee_cabang'",
        "ghost='0'",
        "membership"=>"YToxOntpOjA7czo3OiJvX2thc2lyIjt9",
    );
    protected $ciFilters = array(
        "employee_type" => "employee_cabang",
        "status" => "1",
        "trash" => "0",
        "ghost" => "0",
    );

    protected $validationRules = array(
        "nama"       => array("required", "singleOnly", "unique"),
        "nama_login" => array("required", "singleOnly", "unique"),
        "password"   => array("required"),
        "status"     => array("required"),
    );

    /*--------------------------
     * protected $kolomAlt = true;
     * akan mengunakan key pada $fiekds sebagai kolom alternatif (menampilkan string)
     *  "kolom_alt" => "nama", sebagai gantinya tuliskan manual
     * -----*/
    // protected $kolomAlt = true;
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

    );
    protected $listedFields = array(
        "nama" => "name",
        "nama_login" => "login name",
        "email" => "email",
        "tlp_1" => "phone",
        "oto_settlement" => "SPV Kasir",
        "country" => "country",
    );

    protected $listedUnsetFields = array(
        "phpsessid",
    );

    protected $navFilters = array(
        "label"     => "cabang",
        "mdlFilter" => "MdlCabang",
        "kolomKey"  => "cabang_id",
    );

    public function getNavFilters()
    {
        return $this->navFilters;
    }

    public function setNavFilters($navFilters)
    {
        $this->navFilters = $navFilters;
    }

    public function __construct()
    {
        // $this->fields['membership']['dataSource'] = $this->config->item('userGroup_cabang');
//        $usergrup = $this->config->item('userGroup_cabang');
        // unset($usergrup['o_holding']);

//        $this->fields['membership']['dataSource'] =$usergrup;
    }

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

    public function getListedUnsetFields()
    {
        return $this->listedUnsetFields;
    }

    public function setListedUnsetFields($listedUnsetFields)
    {
        $this->listedUnsetFields = $listedUnsetFields;
    }

    //endregion

    public function lookupSeller()
    {
        $koloms = array(
            "id" => array(),
            "nama_login" => array(),
            "nama" => array(),
            "nama_depan" => array(),
            "nama_belakang" => array(),
            "email" => array(),
            "tlp_1" => array(),
            "last_dtime" => array(),
            "cabang_id" => array(),
            "gudang_id" => array(),
            "employee_type" => array(),
            "last_dtime_active" => array(),
        );
        $this->addFilter("jenis='seller'");
        $vars["raws"] = parent::lookupAll(); // TODO: Change the autogenerated stub
        $vars["koloms"] = $koloms;
        return (object)$vars;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlCabang" => array(
                "id"         => "cabang_id",
                // "str" => "folders_nama",
                "kolomDatas" => array(
                    "nama" => "cabang_nama",
                ),
            ),
             "MdlDiv"        => array(
                 "id"  => "div_id",
                 "kolomDatas" => array(
                     "nama" => "div_name",
                 ),
             ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kendaraan_nama",
            //     ),
            // ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
        );

        return $mdls;

    }
}