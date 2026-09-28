<?php

//--include_once "MdlHistoriData.php";

class MdlCabang extends MdlMother
{

    protected $tableName = "per_cabang";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");
//    protected $filters = array("status='1'", "trash='0'", "id>'0'");
    protected $filters = array("status='1'", "trash='0'", "jenis='cabang'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "tlp_1" => array("required", "numberOnly"),
        "status" => array("required"),
        "division" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "kode_cabang" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "kode_cabang",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
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
        "telp" => array(
            "label" => "telp",
            "type" => "int", "length" => "24", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "alamat",
            "type" => "int", "length" => "24", "kolom" => "alamat",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten" => array(
            "label" => "kabupaten",
            "type" => "int", "length" => "24", "kolom" => "kabupaten",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "propinsi" => array(
            "label" => "propinsi",
            "type" => "int", "length" => "24", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        // "tlp_1"  => array(
        //     "label"     => "telephone",
        //     "type"      => "int",
        //     "length" => "24",
        //     "kolom" => "tlp_1",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
//        "toko"   => array(
//            "label"           => "toko",
//            "type"            => "varchar", "length" => "200",
//            "kolom"           => "toko_id",
//            // "inputType" => "radio",
//            "inputType"       => "hidden_ref",
//            "reference"       => "MdlToko",
//            "referenceFilter" => array("toko_id=toko_id"),
//            "referenceSrc"    => "id",
//            // "strField"        => "toko_nama",
//            // "editable"        => false,
//            // "kolom_nama"      => "toko_nama",
//            //     //--"inputName" => "folders",
//        ),
        "jenis" => array(
            "label" => "jenis",
            "type" => "varchar", "length" => "200",
            "kolom" => "jenis",
            // "inputType" => "text",
            // "inputType"       => "hidden_ref",
            "inputType" => "hidden",
            // "reference"       => "MdlToko",
            // "referenceFilter" => array("toko_id=toko_id"),
            // "referenceSrc"    => "id",
            "defaultValue" => "cabang",
        ),
        "harga_jenis" => array(
            "label" => "hj",
            "type" => "varchar", "length" => "200",
            "kolom" => "harga_jenis",
            // "inputType" => "radio",
            "inputType" => "hidden",
            // "reference"       => "MdlToko",
            // "referenceFilter" => array("toko_id=toko_id"),
            // "referenceSrc"    => "id",
            // "strField"        => "toko_nama",
            // "editable"        => false,
            // "kolom_nama"      => "toko_nama",
            //     //--"inputName" => "folders",
        ),
        "point_jenis" => array(
            "label" => "pj",
            "type" => "varchar", "length" => "200",
            "kolom" => "point_jenis",
            // "inputType" => "radio",
            "inputType" => "hidden",
            // "reference"       => "MdlToko",
            // "referenceFilter" => array("toko_id=toko_id"),
            // "referenceSrc"    => "id",
            // "strField"        => "toko_nama",
            // "editable"        => false,
            // "kolom_nama"      => "toko_nama",
            //     //--"inputName" => "folders",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "id" => "pid",
        "kode_cabang" => "kode",
        "nama" => "nama",
        "alamat" => "alamat",
        "kabupaten" => "kabupaten",
        "propinsi" => "propinsi",
        // "tlp_1" => "telephone",

    );
    /* --------------------------------------------------------------------------------
     * untuk meberi batasan maximal data yg diperkenankan
     * --------------------------------------------------------------------------------*/
    // protected $maximumData = 3;
    /* --------------------------------------------------------------------------------
     * setting untuk reladi ke model yg menjadi ankannya
     * --------------------------------------------------------------------------------*/
    protected $btnActions = array(
        "anakan" => array(
            "label" => "setting",
            "icon" => "fa-gear",
            "mdl" => "MdlCabangDevice",
            "events" => "onclick=\"$('#show_anakan_1').load('{base_url}setting/Cabang/ViewSetting/{rowID}');\"",
        )
    );

    protected $btnActionAll = array(
        "setting" => array(
            "label" => "show device",
            "mdl" => "MdlCabangDevice",
            "events" => "onclick=\"$('#show_anakan_1').load('{base_url}setting/Cabang/ViewSetting');\"",
        )
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

    public function paramSyncNamaNama()
    {
        $mdls = array(
            // "MdlFolderProduk" => array(
            //     "id"         => "folders",
            //     // "str" => "folders_nama",
            //     "kolomDatas" => array(
            //         "nama" => "folders_nama",
            //     ),
            // ),
            // "MdlMerek"        => array(
            //     "id"  => "merek_id",
            //     // "str" => "merek_nama",
            //     "kolomDatas" => array(
            //         "nama" => "merek_nama",
            //     ),
            // ),
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

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            // "kode",
            "nama",

            // "folders_nama",
            // "barcode",
            // "no_part",
            // // "merek_nama",
            // // "model_nama",
            // // "type_nama",
            // // "tahun",
            // // "lokasi_nama",
            // "satuan",
            // "diskon_persen",
            // "premi_beli",
            // "diskon_beli",
            // "biaya_beli",
            // "premi_jual",
            // "harga_jual",
            // "biaya_jual",
            // "limit",
            // "limit_time",
            // "lead_time",
            // "indeks",
            // "moq",
            // "moq_time",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("id", $produkIds);
            }
        }
        $this->db->where("jenis", 'cabang');
        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        if (sizeof($vars_0) > 0) {
            foreach ($vars_0 as $item) {
                $vars[$item->id] = $item;
            }
        }


        return $vars;
    }

    /*----------------------------------------------------------------
         * auto penambahan COA, bisa dugunakan keperluan lain
         * konnecting ke model yg lain
         * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlPettycashAccount" => array(
            "path" => "Mdls",
            "fungsi" => "addData",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
//            "staticOptions" => "010104",
            "staticOptions" => false,
            "fields" => array(
                "jenis" => array(
                    "str" => "pettycash",
                ),
                "jenis2" => array(
                    "str" => "0",
                ),
                "folders" => array(
                    "str" => "0",
                ),
                "cabang_id" => array(
                    "var_main" => "mainInsertId",
                ),
                "cabang_nama" => array(
                    "var_main" => "nama",
                ),
                "nama" => array(
                    "var_main" => "nama",
                ),
            ),
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