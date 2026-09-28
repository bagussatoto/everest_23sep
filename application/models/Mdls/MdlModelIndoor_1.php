<?php

class MdlModelIndoor_1 extends MdlMother
{
    protected $tableName = "produk_indoor_1";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "sku" => array("required", "singleOnly"),
        "merek_id" => array("required"),
//        "barcode" => array("required"),
        "nama" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "alias" => array(
            "label" => "nama/alias",
            "type" => "int",
            "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
            //            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "nama" => array(
            "label" => "SKU/KODE",
            "type" => "int",
            "length" => "255", "kolom" => "sku",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
            //            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "barcode" => array(
            "label" => "barcode",
            "type" => "int",
            "length" => "255",
            "kolom" => "barcode",
            //            "inputType"  => "combo",
            "inputType" => "text",
            //     "reference"  => "MdlTipe",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "tipe_nama",
            "add_btn" => true,
        ),
        "merek" => array(
            "label" => "merek",
            "type" => "int",
            "length" => "255",
            "kolom" => "merek_id",
            "inputType" => "combo",
            "reference" => "MdlMerek",
            "referenceFilter" => array(
                "id" => array(
                    "var" => "merek_id"
                )
            ),
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "merek_nama",
            "add_btn" => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "merek_nama" => array(
            "label" => "merek",
            "type" => "int",
            "length" => "255",
            "kolom" => "merek_nama",
            "inputType" => "hidden",
            "kolom_nama" => "merek_nama",
        ),
        "supplier" => array(
            "label" => "supplier",
            "type" => "int",
            "length" => "255",
            "kolom" => "supplier_id",
            "inputType" => "combo",
            "reference" => "MdlSupplier",
            "referenceFilter" => array(
                "id" => array(
                    "var" => "supplier_id"
                )
            ),
            "referenceSrc" => "id",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "supplier_nama",
            "add_btn" => true,
            "keterangan" => true,
            /* ---------------------------------------------
             * untuk relativ fungsi js belum dicoba
             * ---------------------------------------------*/
            // "event_js"   => "",
        ),
        "supplier_nama" => array(
            "label" => "supplier",
            "type" => "int",
            "length" => "255",
            "kolom" => "supplier_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "merek_nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nama" => "name",
        "sku" => "sku/kode",
        "barcode" => "barcode",
        "merek_nama" => "merek",
        "supplier_nama" => "supplier",
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
        // "MdlProduk" => array(
        //     "path"   => "Mdls",
        //     "fungsi" => "addProdukIndoor",
        //     /* ------------------- ------------------- -------------------
        //      * staticOptions bisa handling array atau singgle
        //      * ---------------------------------------------------------*/
        //     // "staticOptions" => "010304",old
        //
        //     "staticOptions" => array(
        //         // "suppliers_id"  => array(
        //         //     "var_main" => "supplier_id",
        //         // ),
        //         "id" => array(
        //             "var_main" => "mainInsertId",
        //         ),
        //         // "staticOptions" => array(
        //         //     "var_main" => "mainInsertId",
        //     ),//new coa
        //     "fields"        => array(
        //         "id"            => array(
        //             "var_main" => "mainInsertId",
        //         ),
        //         "nama"          => array(
        //             "var_main" => "nama",
        //         ),
        //         "kode"          => array(
        //             "var_main" => "nama",
        //         ),
        //         "merek_id"      => array(
        //             "var_main" => "merek_id",
        //         ),
        //         "merek_nama"    => array(
        //             "var_main" => "merek_nama",
        //         ),
        //         /* -------------------------------------------------
        //          * filter yg ingin langsung diaktifkan
        //          * -------------------------------------------------*/
        //         "status"        => array(
        //             "str" => "1",
        //         ),
        //         "kategori_id"   => array(
        //             "str" => "1",
        //         ),
        //         "kategori_nama" => array(
        //             "str" => "non unit",
        //         ),
        //     ),
        //     /* ----------------------------------------------------
        //      * untuk ngupdate data parent yg sekarng ini berdiri
        //      * -----------------------------------------------*/
        //     // "updateMain"    => array(
        //     //     "condites" => array(
        //     //         "id" => "mainInsertId",
        //     //     ),
        //     //     "datas"    => array(
        //     //         "coa_code" => "lastInset_code",
        //     //     )
        //     // )
        // ),
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
            "MdlMerek" => array(
                "id" => "merek_id",
                // "str" => "kendaraan_nama",
                "kolomDatas" => array(
                    "nama" => "merek_nama",
                ),
            ),
            "MdlSupplier" => array(
                "id" => "supplier_id",
                // "str" => "kendaraan_nama",
                "kolomDatas" => array(
                    "nama" => "supplier_nama",
                ),
            ),
        );

        return $mdls;

    }

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }
}