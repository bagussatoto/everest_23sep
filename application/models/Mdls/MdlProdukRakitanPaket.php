<?php

//--include_once "MdlHistoriData.php";
class MdlProdukRakitanPaket extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "DESC",
    );
    protected $filters = array("jenis='item_paket'", "status='1'", "trash='0'");

    protected $validationRules = array(
//        "produk_jenis_id" => array("required"),
//        "kategori_id" => array("required"),
        "nama" => array("required", "singleOnly"),
//        "folders" => array("required"),
//        "kode" => array("required", "singleOnly"),
//        "lebar_gross" => array("required"),
//        "panjang_gross" => array("required"),
//        "tinggi_gross" => array("required"),
//        "berat_gross" => array("required"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
//        "cabang"=>array(
//            "label" => "cabang produksi",
//            "type" => "int", "length" => "255", "kolom" => "cabang_id",
//            "inputType" => "combo",
//            "reference" => "MdlCabangProduksi",
//            "strField" => "nama",
//            "editable" => true,
//            "kolom_nama" => "kategori_nama",
//        ),
//        "kategori" => array(
//            "label" => "category",
//            "type" => "int", "length" => "255", "kolom" => "kategori_id",
//            "inputType" => "combo",
//            "reference" => "MdlProdukKategori",
//
//            "strField" => "nama",
//            "editable" => true,
//            "kolom_nama" => "kategori_nama",
//        ),
//        "folder" => array(
//            "label" => "folder",
//            "type" => "int",
//            "length" => "24",
//            "kolom" => "folders",
//            "inputType" => "combo",
//            "reference" => "MdlFolderProdukRakitan",
//            "strField" => "nama",
//            "editable" => true,
//            "kolom_nama" => "folders_nama",
//            //--"inputName" => "folders",
//        ),
//        "produk_jenis" => array(
//            "label" => "jenis produk",
//            "type" => "int",
//            "length" => "255",
//            "kolom" => "produk_jenis_id",
//            "inputType" => "combo",
//            "reference" => "MdlProdukJenisRakitan",
//            "strField" => "nama",
//            "editable" => true,
//            "kolom_nama" => "produk_jenis_nama",
//        ),
//        "kode" => array(
//            "label" => "kode",
//            "type" => "int", "length" => "24", "kolom" => "kode",
//            "inputType" => "text",
//            //--"inputName" => "kode",
//        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
//        "label" => array(
//            "label" => "label",
//            "type" => "int", "length" => "24", "kolom" => "label",
//            "inputType" => "text",
//            //--"inputName" => "label",
//        ),
        "keterangan" => array(
            "label" => "keterangan",
            "type" => "int", "length" => "255", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "",
        ),
//        "deskripsi" => array(
//            "label" => "deskripsi",
//            "type" => "int", "length" => "24", "kolom" => "deskripsi",
//            "inputType" => "text",
//            //--"inputName" => "",
//        ),

        "satuan" => array(
            "label" => "satuan",
            "type" => "int", "length" => "100", "kolom" => "satuan",
            "inputType" => "combo",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
        ),
//        "nopart" => array(
//            "label" => "no part",
//            "type" => "int", "length" => "24", "kolom" => "no_part",
//            "inputType" => "text",
//            //--"inputName" => "satuan",
//        ),

        //        "disc_cat" => array(
        //            "label" => "discount cat",
        //            "type" => "int", "length" => "24", "kolom" => "diskon_kategori",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),

        //        "berat" => array(
        //            "label" => "weight (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "berat",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang" => array(
        //            "label" => "length (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "panjang",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar" => array(
        //            "label" => "width (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "lebar",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi" => array(
        //            "label" => "height (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "tinggi",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume" => array(
        //            "label" => "volume (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "volume",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),

//        "panjang_ckd" => array(
//            "label" => "CKD length (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "panjang_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "lebar_ckd" => array(
//            "label" => "CKD width (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "lebar_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "tinggi_ckd" => array(
//            "label" => "CKD height (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "tinggi_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "berat_ckd" => array(
//            "label" => "CKD weight (in grams)",
//            "type" => "int", "length" => "24", "kolom" => "berat_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
        //        "volume_ckd" => array(
        //            "label" => "volume (CKD)",
        //            "type" => "int", "length" => "24", "kolom" => "volume_gross",
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
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "id" => "pID",
//        "kategori_id" => "kategori",
//        "produk_jenis_id" => "jenis produk",
//        "folders" => "folder",
//        "kode" => "kode",
        "nama" => "nama",
//        "label" => "label",
        "keterangan" => "keterangan",
//        "satuan" => "satuan",
        "status" => "status",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "id" => "id",
        "nama" => "nama",
//        "kode" => "kode",
        "keterangan" => "keterangan",
//        "label" => "label",
//        "no_part" => "no_part",
//        "folders_nama" => "folders_nama",
    );
    protected $autoFillFields = array(
//        "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );

    public function getAutoFillFields()
    {
        return $this->autoFillFields;
    }

    public function setAutoFillFields($autoFillFields)
    {
        $this->autoFillFields = $autoFillFields;
    }

    /**
     * @return array
     */
    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    /**
     * @param array $listedFieldsSelectItem
     */
    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
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
//            "MdlFolderProdukRakitan" => array(
//                "id" => "folders",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "folders_nama",
//                ),
//            ),
//            "MdlProdukKategori" => array(
//                "id" => "kategori_id",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "kategori_nama",
//                ),
//            ),
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
//            "MdlProdukJenis" => array(
//                "id" => "produk_jenis_id",
//                // "str" => "lokasi_nama",
//                "kolomDatas" => array(
//                    "kode" => "produk_jenis_nama",
//                    "nilai" => "produk_jenis_nilai",
//                ),
//            ),
//            "MdlCabangProduksi" => array(
//                "id" => "cabang_id",
//                // "str" => "lokasi_nama",
//                "kolomDatas" => array(
//                    // "kode" => "produk_jenis_nama",
//                    "nama" => "cabang_nama",
//                ),
//            ),
        );

        return $mdls;

    }

    /*----------------------------------------------------------------
 * auto penambahan COA, bisa dugunakan keperluan lain
 * konnecting ke model yg lain
 * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            "path" => "Mdls",
            "fungsi" => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            // "staticOptions" => "010305",//old
            "staticOptions" => "1010030070",//new coa
            "fields" => array(
                "extern_jenis" => array(
                    "str" => "produk_paket",
                ),
                "extern_id" => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening" => array(
                    "var_main" => "mainInsertId",
                ),
                "head_name" => array(
                    "var_main" => "nama",
                ),
                "p_head_name" => array(
                    "var_main" => "strHead_code",
                ),
                "create_by" => array(
                    "var_main" => "my_name",
                ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "is_active" => array(
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
            "updateMain" => array(
                "condites" => array(
                    "id" => "mainInsertId",
                ),
                "datas" => array(
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