<?php

//--include_once "MdlHistoriData.php";
class MdlPoint extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item'", "status='1'", "trash='0'");
    protected $ciFilters = array(
        "jenis"  => "item",
        "status" => "1",
        "trash"  => "0",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama"         => "nama",
        "kode"         => "kode",
        "label"        => "label",
        "folder_nama" => "folder_nama",
        "kemasan"      => "kemasan",
        "warna"        => "warna",
        "barcode"      => "barcode",
        "images"       => "images",
    );
    protected $validationRules = array(
        "nama" => array("required",),
        "satuan_id" => array("required",),
        // "kode"   => array("required", "singleOnly"),
        // "panjang_gross" => array("required", "singleOnly"),
        // "tinggi_gross"  => array("required", "singleOnly"),
        // "berat_gross"   => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $kolomAlt = true;
    protected $fields = array(

        "id"          => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

        "folder_nama" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "kategori",
            "type"            => "int",
            "length"          => "24",
            "kolom"           => "folder_id",
            // "kolom_alt" => "folders_nama",
            "editable"        => true,
            "inputType"       => "combo",
            "reference"       => "MdlFolderProduk",
            "strField"        => "nama",
            "kolom_nama"      => "folder_nama",
            "referenceFilter" => array(
                "toko_id=toko_id",
            ),
            "referenceSrc"    => "id",
        ),

        "nama"        => array(
            "label"     => "nama",
            "type"      => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),

        "kode"        => array(
            "label"     => "kode",
            "type"      => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            "editable"  => false,

            "eventTrigger"   => "onkeydown=\"if(detectEnter()==true){ return false; }\"",
            //--"inputName" => "kode",
        ),

        // "no_part"        => array(
        //     "label"     => "no part",
        //     "type"      => "int", "length" => "24", "kolom" => "no_part",
        //     "inputType" => "text",
        //     //--"inputName" => "satuan",
        // ),

        "label"       => array(
            "label"     => "label",
            "type"      => "int", "length" => "24", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),

        "tipe_pajak"  => array(
            "label"        => "jenis pajak",
            "type"         => "int", "length" => "24", "kolom" => "tipe_pajak",
            "inputType"    => "combo",
            "dataSource"   => array(
                "0" => "BTKP",//barang tidak kena pajak
                "1" => "BKP" //barang kena pajak
            ),
            "defaultValue" => "1",
        ),

        "ppn_produk"         => array(
            "label"        => "ppn",
            "type"         => "int", "length" => "24", "kolom" => "ppn_produk",
            "inputType"    => "combo",
            "dataSource"   => array(
                "0"  => "tidak dipungut",
                "1" => "dipungut",
            ),
            "defaultValue" => "1",
        ),

        // "type_nama"      => array(
        //     "label"     => "type",
        //     "type"      => "text",
        //     "length"    => "32",
        //     "kolom"     => "type_nama",
        //     "inputType" => "text",
        // ),

        // "model_nama"     => array(
        //     "label"     => "model",
        //     "type"      => "text",
        //     "length"    => "32",
        //     "kolom"     => "model_nama",
        //     "inputType" => "text",
        // ),

        // "tahun"          => array(
        //     "label"     => "tahun",
        //     "type"      => "text",
        //     "length"    => "8",
        //     "kolom"     => "tahun",
        //     "inputType" => "text",
        // ),

        "barcode"    => array(
            "label"          => "barcode",
            "type"           => "int",
            "length"         => "24",
            "kolom"          => "barcode",
            "inputType"      => "text",
            "eventTrigger"   => "onkeydown=\"if(detectEnter()==true){ return false; }\"",

//            "transformValue" => "JsBarcode", //transform kodebarcode jadi image
        ),

//        "images"     => array(
//            "label"     => "images",
//            "type"      => "int",
//            "length"    => "24",
//            "kolom"     => "images",
//            "inputType" => "images",
//            //--"inputName" => "",
//        ),

        // "keterangan"     => array(
        //     "label"     => "keterangan",
        //     "type"      => "varchar",
        //     "length"    => "255",
        //     "kolom"     => "keterangan",
        //     "inputType" => "text",
        //     //--"inputName" => "",
        // ),

        "deskripsi"  => array(
            "label"     => "deskripsi",
            "type"      => "int", "length" => "5", "kolom" => "deskripsi",
            "inputType" => "textarea", //length menjadi baris/row
            //--"inputName" => "",
        ),

        "kemasan"    => array(
            "label"     => "kemasan",
            "type"      => "varchar", "length" => "255", "kolom" => "kemasan",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "warna"      => array(
            "label"     => "warna",
            "type"      => "varchar", "length" => "255", "kolom" => "warna",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "satuan" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "satuan dasar",
            "type"            => "int",
            "length"          => "24",
            "kolom"           => "satuan_id",
            // "kolom_alt" => "folders_nama",
            "editable"        => true,
            "inputType"       => "combo",
            "reference"       => "MdlSatuan",
            "strField"        => "nama",
            "kolom_nama"      => "satuan",
            "referenceFilter" => array(
                "toko_id=toko_id",
            ),
            "referenceSrc"    => "id",
        ),

        // "kemasan" => array(
        //     "label" => "kemasan",
        //     "type" => "int",
        //     "length" => "24",
        //     "kolom" => "kemasan",
        //     "inputType" => "combo",
        //     "reference" => "MdlKemasan",
        //     "attr" => "class='text-center'",
        //     "referenceFilter" => array(
        //         "toko_id=toko_id",
        //     ),
        //     "referenceSrc" => "nama",
        // ),

        "merek_nama" => array(
            "label"           => "merek",
            "type"            => "int",
            "length"          => "24",
            "kolom"           => "merek_id",
            "inputType"       => "combo",
            "editable"        => true,
            "reference"       => "MdlMerek",
            "strField"        => "nama",
            "kolom_nama"      => "merek_nama",
            "referenceFilter" => array(
                "toko_id=toko_id",
            ),
            "referenceSrc"    => "id",
        ),

        // "kendaraan_nama" => array(
        //     "label"      => "kendaraan",
        //     "type"       => "int",
        //     "length"     => "24",
        //     "kolom"      => "kendaraan_id",
        //     "inputType"  => "combo",
        //     "editable"   => true,
        //     "reference"  => "MdlKendaraan",
        //     "strField"   => "nama",
        //     "kolom_nama" => "kendaraan_nama",
        // ),

         "lokasi_nama"    => array(
             "label"      => "lokasi/rak",
             "type"       => "int",
             "length"     => "24",
             "kolom"      => "lokasi",
             "inputType"  => "combo",
             "editable"   => true,
             "reference"  => "MdlLokasiIndex",
             // "reference"  => "MdlRakCabang",
             // "referenceFilter"  => "cabang_id",
             "strField"   => "nama",
             "kolom_nama" => "lokasi_nama",
         ),

        // "lokasi"     => array(
        //     "label"     => "lokasi rak",
        //     "type"      => "text",
        //     "length"    => "24",
        //     "kolom"     => "",
        //     "inputType" => "text",
        //     "editable"  => false,
        // ),

        // "supplier"   => array(
        //     "label"     => "supplier",
        //     "type"      => "text",
        //     "length"    => "24",
        //     "kolom"     => "",
        //     "inputType" => "text",
        //     "editable"  => false,
        // ),

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
        //     "label"     => "CKD length (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "panjang_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),

        // "lebar_ckd"   => array(
        //     "label"     => "CKD width (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "lebar_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),

        // "tinggi_ckd"  => array(
        //     "label"     => "CKD height (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "tinggi_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),

        // "berat_ckd"   => array(
        //     "label"     => "CKD weight (in grams)",
        //     "type"      => "int", "length" => "24", "kolom" => "berat_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),

        "toko_id"    => array(
            "label"           => "toko",
            "type"            => "varchar", "length" => "200",
            "kolom"           => "toko_id",
            // "inputType" => "radio",
            "inputType"       => "hidden_ref",
            "reference"       => "MdlToko",
            "referenceFilter" => array("toko_id=toko_id"),
            "referenceSrc"    => "id",
            // "strField"        => "toko_nama",
            // "editable"        => false,
            // "kolom_nama"      => "toko_nama",
            //     //--"inputName" => "folders",
        ),

        "lead_time" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "lead time",
            "type"            => "int",
            "length"          => "24",
            "kolom"           => "lead_time",
            "inputType" => "text",
        ),

        "limit" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "limit",
            "type"            => "varchar",
            "length"          => "24",
            "kolom"           => "limit",
            "inputType" => "text",
        ),

        "limit_time" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "limit_time",
            "type"            => "varchar",
            "length"          => "24",
            "kolom"           => "limit_time",
            "inputType" => "text",
        ),

        "indeks" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "indeks",
            "type"            => "varchar",
            "length"          => "24",
            "kolom"           => "indeks",
            "inputType" => "text",
        ),

        "moq" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "moq",
            "type"            => "varchar",
            "length"          => "24",
            "kolom"           => "moq",
            "inputType" => "text",
        ),

        "moq_time" => array( // harus di daftarkan di paramSyncNamaNama() fungsinya ada di bawah
            "label"           => "moq_time",
            "type"            => "varchar",
            "length"          => "24",
            "kolom"           => "moq_time",
            "inputType" => "text",
        ),

        "status"     => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),

        "trash"      => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "trash",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
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
        "id"         => "pID",
        "barcode"       => "barcode",
        "nama"          => "Nama",
        "folder_nama"   => "Kategori",
        "merek_nama"    => "Merek",
        // "kode" => "kode produk",
        // "label"        => "label",
        // "no_part"      => "part no",
        // "kemasan"     => "kemasan/varian",
        "satuan"        => "Satuan",
         "lokasi_nama"  => "lokasi/rak",
        // "kemasan" => "kemasan",
        "tipe_pajak"    => "jenis",
        "ppn_produk"    => "ppn",
        // "warna"       => "warna",

        "images"        => "images",
    );
    protected $autoFillFields = array(
        "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );
    protected $order_column = array(
        "id",
        "folders_nama",
        "kode",
        "label",
        // "no_part",
        "nama",
        // "keterangan",
        "satuan",
        "barcode",
    );


    protected $pairValidate = array("nama", "toko_id");
    /*----------------------------------------------------------------
     * auto penambahan COA, bisa dugunakan keperluan lain
     * konnecting ke model yg lain
     * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            array(
                "path"          => "Mdls",
                "fungsi"        => "addExtern_coa_tk",
                /* ------------------- ------------------- -------------------
                 * staticOptions bisa handling array atau singgle
                 * ---------------------------------------------------------*/
                "staticOptions" => "1010030030",
                "fields"        => array(
                    "extern_jenis"         => array(
                        "str" => "item",
                    ),
                    "extern_id"            => array(
                        "var_main" => "mainInsertId",
                    ),
                    "head_name"            => array(
                        "var_main" => "nama",
                    ),
                    "p_head_name"          => array(
                        "var_main" => "strHead_code",
                    ),
                    "create_by"            => array(
                        "var_main" => "my_name",
                    ),
                    /* -------------------------------------------------
                     * filter yg ingin langsung diaktifkan
                     * -------------------------------------------------*/
                    "is_active"            => array(
                        "str" => "1",
                    ),
                    /*
                     * penanda sebagai rekening pembantu
                     */
                    "is_rekening_pembantu" => array(
                        "str" => "1",
                    ),
                    "is_gl"                => array(
                        "str" => "1",
                    ),
                    "is_neraca"            => array(
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
        )
    );

    protected $excelFields = array(
        // "folders_nama" => array(
        //     "label" => "kategori",
        //     "type"  => "string",
        // ),
        "id"   => array(
            "label" => "pID",
            "type"  => "integer",
        ),
        "kode" => array(
            "label" => "kode",
            "type"  => "string",
        ),
        // "no_part" => array(
        //     "label" => "part",
        //     "type" => "string",
        // ),
        "nama" => array(
            "label" => "produk",
            "type"  => "string",
        ),
        //        "keterangan" => array(
        //            "label" => "note",
        //            "type"  => "string",
        //        ),
        // "toko_id" => array(
        //     "label" => "tk_code",
        //     "type" => "integer",
        // ),
        // "lebar_gross" => array(
        //     "label" => "lebar",
        //     "type" => "integer",
        // ),
        // "panjang_gross" => array(
        //     "label" => "panjang",
        //     "type" => "integer",
        // ),
        // "tinggi_gross" => array(
        //     "label" => "tinggi",
        //     "type" => "integer",
        // ),
        // "pic" => array(
        //     "label" => "images",
        //     "type" => "string",
        //     "replacer" => "hahaha",
        // ),
        /* -------------- ----------------------------------
         * key masih manual untuk pairing dr data tambahan
         * ----------------------- ------------------------*/

        "kemasan"             => array(
            "label" => "kemasan",
            "type"  => "string",
        ),
        //        "vendor"     => array(
        //            "label" => "vendor",
        //            "type"  => "string",
        //        ),
        "jml_stok"            => array(
            "label" => "jml stok",
            "type"  => "integer",
        ),
        "satuan"              => array(
            "label" => "satuan",
            "type"  => "string",
        ),
        "nilai_stok_per_unit" => array(
            "label" => "nilai stok per unit",
            "type"  => "integer",
        ),
        "total_nilai_stok"    => array(
            "label" => "total nilai stok",
            "type"  => "integer",
        ),
    );

    /* ----------------------------------------------------- -----------------------------
     * dengan adanya methode ini akan metriger munculnya tombol download xlsx di GUI
     * berpasangan dengan method excelFields diatasnya ini
     * ----------------------------------- ----------------------------------------------*/
    protected $excelWriters = array(
        "namaFile"           => "persediaan_produk",
        "dataTambahan"       => "MdlProdukPerSupplier",
        "dataTambahanBase"   => "MdlProduk",
        "dataTambahanFields" => array(
            "produk_id",
            "suppliers_id"
        ),
        "dataImage"          => "MdlImages",
        "dataImageFields"    => array(
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


    // protected $navFilters = array(
    //     "label"     => "kategori",
    //     "mdlFilter" => "MdlFolderProduk",
    //     "kolomKey"  => "folders",
    // );
    //
    // public function getNavFilters()
    // {
    //     return $this->navFilters;
    // }
    //
    // public function setNavFilters($navFilters)
    // {
    //     $this->navFilters = $navFilters;
    // }
    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

    public function isKolomAlt()
    {
        return $this->kolomAlt;
    }

    public function setKolomAlt($kolomAlt)
    {
        $this->kolomAlt = $kolomAlt;
    }

    // protected $pairedData = array(
    //     "MdlImages" => array(
    //         "kolom" => "images",
    //         "label" => "image",
    //         "link"  => "image"
    //     ),
    // );


    // public function getPairedData()
    // {
    //     return $this->pairedData;
    // }
    //
    // public function setPairedData($pairedData)
    // {
    //     $this->pairedData = $pairedData;
    // }
    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
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


    public function callSpecs($produkIds = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiDisini("tokid harap diset " . __METHOD__);
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
            "diskon_persen",
            "premi_beli",
            "diskon_beli",
            "biaya_beli",
            "premi_jual",
            "harga_jual",
            "biaya_jual",
                        "limit",
            "limit_time",
            "lead_time",
            "indeks",
            "moq",
            "moq_time",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("id", $produkIds);
        }
        else {
            if($produkIds > 0){
                $this->db->where("id", $produkIds);
            }
        }
        $this->db->where("toko_id",$toko_id);
        // $vars_0 = $this->lookupAll()->result();

        $this->load->model("Coms/ComRekeningPembantuCustomer");
        $cp = new ComRekeningPembantuCustomer();
        $vars_0 = $cp->fetchBalances("2010050");
showLast_query("Biru");


        // showLast_query("orange");
        $vars = array();
        if (sizeof($vars_0) > 0) {
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }
        }


        return $vars;
    }

    // public function ssyncNamaNama($produkIds = "")
    // {
    //     $mdls = array(
    //         "MdlFolderProduk" => array(
    //             "id"         => "folders",
    //             // "str" => "folders_nama",
    //             "kolomDatas" => array(
    //                 "nama" => "folders_nama",
    //
    //             )
    //         ),
    //         "MdlMerek"        => array(
    //             "id"  => "merek_id",
    //             "str" => "merek_nama",
    //         ),
    //         "MdlKendaraan"    => array(
    //             "id"  => "kendaraan_id",
    //             "str" => "kendaraan_nama",
    //         ),
    //         "MdlLokasiIndex"  => array(
    //             "id"  => "lokasi",
    //             "str" => "lokasi_nama",
    //         ),
    //     );
    //
    //     // arrPrintPink($mdls);
    //     $vars = array();
    //     $this->db->trans_begin();
    //     foreach ($mdls as $mdl => $params) {
    //         $this->load->model("Mdls/$mdl");
    //         $tm = new $mdl();
    //         $kolom = $params['id'];
    //         $kolom_target = $params['str'];
    //
    //         $tmps = $tm->lookupAll()->result();
    //         showLast_query("orange");
    //
    //         foreach ($tmps as $tmp) {
    //             $vars[$kolom][$tmp->id] = $tmp->nama;
    //
    //
    //             $wheres = array(
    //                 $kolom => $tmp->id
    //             );
    //
    //             $datas = array(
    //                 $kolom_target => $tmp->nama
    //             );
    //
    //             $this->updateData($wheres, $datas);
    //             showLast_query("lime");
    //             // matiHere();
    //         }
    //
    //     }
    //     $this->db->trans_complete();
    //     // arrPrintPink($vars);
    //
    //     // foreach ($vars as $var => $vals) {
    //     //
    //     //     foreach ($vals as $key => $val) {
    //     //
    //     //         $wheres = array(
    //     //             $var => $key
    //     //         );
    //     //
    //     //         $datas = array();
    //     //
    //     //         $this->updateData($wheres, $datas);
    //     //     }
    //     // }
    //
    // }


}