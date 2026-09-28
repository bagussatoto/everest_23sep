<?php

//--include_once "MdlHistoriData.php";

class MdlSubscription extends MdlMother
{
    protected $tableName = "subscription";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash=0",
        "status=1",
    );

    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama"   => "nama",
        "mid"    => "mid",
        "tlp_1"  => "tlp_1",
        "no_ktp"  => "no_ktp",
    );

    protected $validationRules = array(
        "nama"  => array("required"),
        "mid"   => array("unique", "required"),
        "tlp_1" => array("unique", "required"),
        "no_ktp"=> array("unique", "required"),

        // "no_ktp"     => array("numberOnly", "unique", "singleOnly"),
        // "npwp"       => array("unique"),
        //        "nik" => array("required"),

        "status" => array("required"),
        "image_ktp" => array("image"),
        "image_npwp" => array("image"),
        // "country" => array("required"),
    );
    protected $unionPairs = array();
    protected $listedFieldsView = array("nama", "tlp_1", "npwp");

    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

        "dtime" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "dtime",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),

        "jml_nota" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "jml_nota",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),

        "jml_nota_last_month" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "jml_nota_last_month",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),


        "biaya_pernota" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "biaya_pernota",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),

        "biaya_terlambat" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "biaya_terlambat",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),

        "total_biaya" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "total_biaya",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),

        "status_tagihan" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "status_tagihan",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
    );

    protected $fieldsBlacklist = array();

    protected $listedFields = array(
        "dtime" => "tanggal",
        "jml_nota" => "Jml Nota Bulan Ini",
        "jml_nota_last_month" => "Jml Nota Bulan Lalu",
        "biaya_pernota" => "Biaya Per Nota (Rp)",
        "biaya_terlambat" => "Biaya Terlambat",
        "total_biaya" => "Total Biaya (Rp)",
        "status_tagihan" => "Status Tagihan",
    );

    protected $connectingData = array(
//        "MdlAccounts" => array(
//            array(
//                "path" => "Mdls",
//                "fungsi" => "addExtern_coa_tk",
//                "staticOptions" => "1010020010",// piutang usaha lokal
//                "fields" => array(
//
//                    "extern_jenis" => array(
//                        "str" => "customer",
//                    ),
//
//                    "extern_id" => array(
//                        "var_main" => "mainInsertId",
//                    ),
//
//                    "head_name" => array(
//                        "var_main" => "nama",
//                    ),
//
//                    "p_head_name" => array(
//                        "var_main" => "strHead_code",
//                    ),
//
//                    "create_by" => array(
//                        "var_main" => "my_name",
//                    ),
//
//                    /* -------------------------------------------------
//                     * filter yg ingin langsung diaktifkan
//                     * -------------------------------------------------*/
//
//                    "is_active" => array(
//                        "str" => "1",
//                    ),
//
//                    "is_gl" => array(
//                        "str" => "1",
//                    ),
//
//                    "is_neraca" => array(
//                        "str" => "1",
//                    ),
//
//                ),
//                "updateMain" => array(
//
//                    "condites" => array(
//                        "id" => "mainInsertId",
//                    ),
//
//                    "datas" => array(
//                        "coa_code" => "lastInset_code",
//                    )
//
//                )
//            ),
//        ),
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

    public function getBlacklistFields()
    {
        return $this->fieldsBlacklist;
    }

    public function setBlacklistFields($fieldsBlacklist)
    {
        $this->fieldsBlacklist = $fieldsBlacklist;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
//            "MdlCustomerLevel" => array(
//                "id" => "level_id",
//                "kolomDatas" => array(
//                    "nama" => "level_nama",
//                ),
//            ),
//            "MdlVirtualAccount" => array(
//                "id" => "va_id",
//                "kolomDatas" => array(
//                    "nama" => "va_nama",
//                ),
//            ),

            // "MdlSatuan"       => array(
            //     "id"         => "satuan_id",
            //     "kolomDatas" => array(
            //         "nama" => "satuan",
            //     ),
            // ),
            // "MdlKemasan"      => array(
            //     "id"         => "kemasan",
            //     "kolomDatas" => array(
            //         "nama" => "kemasan",
            //     ),
            // ),
            // "MdlMerek"        => array(
            //     "id"         => "merek_id",
            //     // "str" => "merek_nama",
            //     "kolomDatas" => array(
            //         "nama" => "merek_nama",
            //     ),
            // ),
            // "MdlKendaraan"    => array(
            //     "id"         => "kendaraan_id",
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