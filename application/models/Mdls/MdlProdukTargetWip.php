<?php

//--include_once "MdlHistoriData.php";
class MdlProdukTargetWip extends MdlMother
{
    protected $tableName = "produk_supplies";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='item_wip'",
        "status='1'",
        "trash='0'",
        "item_jenis='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "folder" => array(
            "label" => "folder",
            "type" => "int", "length" => "255", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderSupplies",
            //--"inputName" => "folders",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "folders_nama",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),


//        "satuan" => array(
//            "label" => "satuan",
//            "type" => "int", "length" => "24", "kolom" => "satuan",
//            "inputType" => "combo",
//            "dataSource" => array(
//                "pcs" => "piece",
//                "unit" => "unit"),
//            //--"inputName" => "satuan",
//        ),
        "satuan" => array(
            "label" => "satuan",
            "type" => "varchar", "length" => "24", "kolom" => "satuan",
            "reference" => "MdlSatuan",
            "defaultValue" => "ID",
            "inputType" => "combo",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "nopart" => array(
        //            "label" => "no part",
        //            "type" =>"int","length"=>"24","kolom" => "no_part",
        //            "inputType" => "combo",
        //            //--"inputName" => "satuan",
        //        ),
        //        "jenis" => array(
        //            "label" => "pilih jenis",
        //            "type" => "int", "length" => "24", "kolom" => "jenis",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),
        //        "disc_cat" => array(
        //            "label" => "discount cat",
        //            "type" =>"int","length"=>"24","kolom" => "diskon_kategori",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),

        //        "berat" => array(
        //            "label" => "weight (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "berat",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang" => array(
        //            "label" => "length (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "panjang",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar" => array(
        //            "label" => "width (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "lebar",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi" => array(
        //            "label" => "height (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "tinggi",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume" => array(
        //            "label" => "volume (CBU)",
        //            "type" =>"int","length"=>"24","kolom" => "volume",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "berat ckd" => array(
        //            "label" => "weight (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "berat_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang ckd" => array(
        //            "label" => "length (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "panjang_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar ckd" => array(
        //            "label" => "width (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "lebar_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi ckd" => array(
        //            "label" => "height (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "tinggi_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume ckd" => array(
        //            "label" => "volume (CKD)",
        //            "type" =>"int","length"=>"24","kolom" => "volume_gross",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),


        //        "harga" => array(
        //            "label" => "harga",
        //            "type" =>"int","length"=>"24","kolom" => "harga",
        //            "inputType" => "number",
        //            //--"inputName" => "harga",
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
        //
        //        "komposisi" => array(
        //            "label" => "komposisi",
        //            "type" =>"int","length"=>"24","kolom" => "komposisi",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "produk dasar" => array(
        //            "label" => "bahan",
        //            "type" =>"int","length"=>"24","kolom" => "produk_dasar_nama",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "jumlah" => array(
        //            "label" => "jumlah",
        //            "type" =>"int","length"=>"24","kolom" => "jml",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "bahan utama" => array(
        //            "label" => "bahan utama",
        //            "type" =>"int","length"=>"24","kolom" => "bahan_utama",
        //            "inputType" => "combo",
        //            //--"inputName" => "bahan_utama",
        //        ),
    );
    protected $listedFields = array(
        "folders" => "folder",
        "nama" => "name",
        "keterangan" => "keterangan",
        "satuan" => "satuan",
        "status" => "status",

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
            // "MdlSatuan" => array(
            //     "id"         => "satuan_id",    // kolom_src => kolom_target (berisi id src)
            //     // "str" => "folders_nama",
            //     "kolomDatas" => array(
            //         "satuan" => "satuan",       // kolom_data => kolom_target (berisi nama)
            //     ),
            // ),
            "MdlFolderSupplies"     => array(
                "id"         => "folders",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "folders_nama",
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
            // "staticOptions" => "010303",//old
            "staticOptions" => "1010030010",//new coa
            "fields"        => array(
                "extern_jenis"   => array(
                    "str" => "supplies",
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
                // "is_hutang" => array(
                //     "str" => "1",
                // ),
                // "is_gl" => array(
                //     "str" => "1",
                // ),
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