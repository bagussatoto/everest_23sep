<?php

//--include_once "MdlHistoriData.php";
class MdlProdukWip extends MdlMother
{
    protected $tableName = "produk_supplies";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("produk.jenis='item_wip'", "produk.status='1'", "produk.trash='0'");
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "nama",
        "kode" => "kode",
        "keterangan" => "keterangan",
        "label" => "label",
        "no_part" => "no_part",
        "folders_nama" => "folders_nama",
    );
    protected $validationRules = array(
        "produk_jenis_id" => array("required"),
        "kategori_id" => array("required"),
        "nama" => array("required", "unique"),
        // "lebar_gross" => array("required", "singleOnly"),
        // "panjang_gross" => array("required", "singleOnly"),
        // "tinggi_gross" => array("required", "singleOnly"),
        // "berat_gross" => array("required", "singleOnly"),
        "kode" => array("required", "unique"),
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
        "kategori" => array(
            "label" => "category",
            "type" => "int", "length" => "255", "kolom" => "kategori_id",
            "inputType" => "combo",
            "reference" => "MdlProdukKategori",

            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "kategori_nama",
        ),
        "folder" => array(
            "label" => "folder",
            "type" => "int", "length" => "255", "kolom" => "folders",
            "inputType" => "combo",
            "reference" => "MdlFolderProduk",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "folders_nama",

        ),
        "produk_jenis" => array(
            "label" => "jenis produk (lokal/import)",
            "type" => "int",
            "length" => "255",
            "kolom" => "produk_jenis_id",
            "inputType" => "combo",
            "reference" => "MdlProdukJenis",
            "strField" => "nama",
            "editable" => true,
            "kolom_nama" => "produk_jenis_nama",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "label" => array(
            "label" => "label",
            "type" => "int", "length" => "24", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "keterangan" => array(
            "label" => "keterangan",
            "type" => "varchar", "length" => "255", "kolom" => "keterangan",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "deskripsi" => array(
            "label" => "deskripsi",
            "type" => "int", "length" => "24", "kolom" => "deskripsi",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "satuan" => array(
            "label" => "satuan",

            "type" => "int", "length" => "24", "kolom" => "satuan",
            "inputType" => "combo",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
            "attr" => "class='text-center'",
        ),
        "nopart" => array(
            "label" => "no part",
            "type" => "int", "length" => "24", "kolom" => "no_part",
            "inputType" => "text",
            //--"inputName" => "satuan",
        ),

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

        // "panjang_ckd" => array(
        //     "label" => "CKD length (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "panjang_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "lebar_ckd" => array(
        //     "label" => "CKD width (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "lebar_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "tinggi_ckd" => array(
        //     "label" => "CKD height (in millimeters)",
        //     "type" => "int", "length" => "24", "kolom" => "tinggi_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "berat_ckd" => array(
        //     "label" => "CKD weight (in grams)",
        //     "type" => "int", "length" => "24", "kolom" => "berat_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),


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
        "id" => "pID",
        "kategori_id" => "kategori",
        "folders" => "folder",
        "produk_jenis_id" => "jenis produk (lokal/import)",
        "kode" => "kode produk",
        "label" => "label",
        "no_part" => "no part",
        "nama" => "nama/deskripsi",
        "keterangan" => "keterangan",
        "satuan" => "satuan",
        "status" => "status",
    );
    protected $autoFillFields = array(
        // "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );

    protected $excelFields = array(
        // "folders_nama" => array(
        //     "label" => "kategori",
        //     "type"  => "string",
        // ),
        "id" => array(
            "label" => "pID",
            "type" => "integer",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "string",
        ),
        "no_part" => array(
            "label" => "part",
            "type" => "string",
        ),
        "nama" => array(
            "label" => "produk",
            "type" => "string",
        ),
        "keterangan" => array(
            "label" => "note",
            "type" => "string",
        ),
        "berat_gross" => array(
            "label" => "berat",
            "type" => "integer",
        ),
        "lebar_gross" => array(
            "label" => "lebar",
            "type" => "integer",
        ),
        "panjang_gross" => array(
            "label" => "panjang",
            "type" => "integer",
        ),
        "tinggi_gross" => array(
            "label" => "tinggi",
            "type" => "integer",
        ),
        "pic" => array(
            "label" => "images",
            "type" => "string",
            "replacer" => "hahaha",
        ),
        /* -------------- ----------------------------------
         * key masih manual untuk pairing dr data tambahan
         * ----------------------- ------------------------*/
        "vendor" => array(
            "label" => "vendor",
            "type" => "string",
        ),
    );

    /* ----------------------------------------------------- -----------------------------
     * dengan adanya methode ini akan metriger munculnya tombol download xlsx di GUI
     * berpasangan dengan method excelFields diatasnya ini
     * ----------------------------------- ----------------------------------------------*/
    protected $excelWriters = array(
        "namaFile" => "data_produk",
        "dataTambahan" => "MdlProdukPerSupplier",
        "dataTambahanBase" => "MdlSupplier",
        "dataTambahanFields" => array(
            "produk_id",
            "suppliers_id"
        ),
        "dataImage" => "MdlImages",
        "dataImageFields" => array(
            "parent_id" => "files"
        ),
    );

    public function getExcelFields()
    {
        return $this->excelFields;
    }

    public function setExcelFields($excelFields)
    {
        $this->excelFields = $excelFields;
    }

    public function getExcelWriters()
    {
        return $this->excelWriters;
    }

    public function setExcelWriters($excelWriters)
    {
        $this->excelWriters = $excelWriters;
    }

    public function getAutoFillFields()
    {
        return $this->autoFillFields;
    }

    public function setAutoFillFields($autoFillFields)
    {
        $this->autoFillFields = $autoFillFields;
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

    public function updateLimit($produk_id, $limit)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "limit" => $limit,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateLeadTime($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "lead_time" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateIndeks($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "indeks" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function callProdukFire()
    {
        $fireKategories = array(
            "143"
        );
        $produks = $this->lookupAll()->result();

        $nonFireProduks = array();
        $fireProduks = array();
        foreach ($produks as $produkSrc) {
            if (in_array($produkSrc->folders, $fireKategories)) {
                $fireProduks[] = $produkSrc;
            }
            else {
                $nonFireProduks[] = $produkSrc;
            }
        }
        $vars['fire'] = $fireProduks;
        $vars['nonFire'] = $nonFireProduks;

        return $vars;
    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "barcode",
            "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            "satuan",
            // "kendaraan_nama",
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

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
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
            "MdlFolderProduk" => array(
                "id" => "folders",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "folders_nama",
                ),
            ),
            "MdlProdukKategori" => array(
                "id" => "kategori_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "kategori_nama",
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
            "MdlProdukJenis" => array(
                "id" => "produk_jenis_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "kode" => "produk_jenis_nama",
                    "nilai" => "produk_jenis_nilai",
                ),
            ),
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
            // "staticOptions" => "010304",old
            "staticOptions" => "1010030030",//new coa
            "fields" => array(
                "extern_jenis" => array(
                    "str" => "produk",
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