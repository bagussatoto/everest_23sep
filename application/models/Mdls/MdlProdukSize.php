<?php

//--include_once "MdlHistoriData.php";
class MdlProdukSize extends MdlMother
{
    protected $tableName = "produk_size";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");
    protected $ciFilters = array(
        // "jenis"  => "item",
        "status" => "1",
        "trash"  => "0",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "nama",
        // "kode"         => "kode",
        // "label"        => "label",
        // "folder_nama" => "folder_nama",
        // "kemasan"      => "kemasan",
        // "warna"        => "warna",
        // "barcode"      => "barcode",
        // "images"       => "images",
    );
    protected $validationRules = array(
        "nama" => array("required",),
        // "satuan_id" => array("required",),
        // "kode"   => array("required", "singleOnly"),
        // "panjang_gross" => array("required", "singleOnly"),
        // "tinggi_gross"  => array("required", "singleOnly"),
        // "berat_gross"   => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $kolomAlt = true;
    protected $fields = array(

        "id" => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

        "nama" => array(
            "label"     => "nama",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),

        "status" => array(
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

        // "trash" => array(
        //     "label"     => "id",
        //     "type"      => "int", "length" => "24",
        //     "kolom"     => "trash",
        //     "inputType" => "hidden",// hidden
        //     //--"inputName" => "id",
        // ),

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
        "id"   => "pID",
        "nama" => "Nama",

    );
    protected $autoFillFields = array(// "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );
    protected $order_column = array(
        "id",
        // "folders_nama",
        // "kode",
        // "label",
        // "no_part",
        "nama",
        // "keterangan",
        // "satuan",
        // "barcode",
    );


    protected $pairValidate = array("nama", "toko_id");
    /*----------------------------------------------------------------
     * auto penambahan COA, bisa dugunakan keperluan lain
     * konnecting ke model yg lain
     * ----------------------------------------------------------*/
    // protected $connectingData = array(
    //     "MdlAccounts" => array(
    //         array(
    //             "path"          => "Mdls",
    //             "fungsi"        => "addExtern_coa_tk",
    //             /* ------------------- ------------------- -------------------
    //              * staticOptions bisa handling array atau singgle
    //              * ---------------------------------------------------------*/
    //             "staticOptions" => "1010030030",
    //             "fields"        => array(
    //                 "extern_jenis"         => array(
    //                     "str" => "item",
    //                 ),
    //                 "extern_id"            => array(
    //                     "var_main" => "mainInsertId",
    //                 ),
    //                 "head_name"            => array(
    //                     "var_main" => "nama",
    //                 ),
    //                 "p_head_name"          => array(
    //                     "var_main" => "strHead_code",
    //                 ),
    //                 "create_by"            => array(
    //                     "var_main" => "my_name",
    //                 ),
    //                 /* -------------------------------------------------
    //                  * filter yg ingin langsung diaktifkan
    //                  * -------------------------------------------------*/
    //                 "is_active"            => array(
    //                     "str" => "1",
    //                 ),
    //                 /*
    //                  * penanda sebagai rekening pembantu
    //                  */
    //                 "is_rekening_pembantu" => array(
    //                     "str" => "1",
    //                 ),
    //                 "is_gl"                => array(
    //                     "str" => "1",
    //                 ),
    //                 "is_neraca"            => array(
    //                     "str" => "1",
    //                 ),
    //             ),
    //             "updateMain"    => array(
    //                 "condites" => array(
    //                     "id" => "mainInsertId",
    //                 ),
    //                 "datas"    => array(
    //                     "coa_code" => "lastInset_code",
    //                 )
    //             )
    //         ),
    //     )
    // );

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
            // "label",
            // "folders_nama",
            // "barcode",
            // "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            //     "satuan",
            //     "diskon_persen",
            //     "premi_beli",
            //     "diskon_beli",
            //     "biaya_beli",
            //     "premi_jual",
            //     "harga_jual",
            //     "biaya_jual",
            //     "limit",
            //     "limit_time",
            //     "lead_time",
            //     "indeks",
            //     "moq",
            //     "moq_time",
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
        $this->db->where("toko_id", $toko_id);
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