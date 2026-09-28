<?php

//--include_once "MdlHistoriData.php";

class MdlNumbering extends MdlMother
{
    protected $tableName = "set_numbering";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash='0'",
        "status='1'"
    );

    protected $validationRules = array(
        // "kategori_id" => array("required"),
//        "nama" => array("required", "singleOnly"),
        // "tlp_1" => array("required", "numberOnly"),
        // "tlp_1" => array("numberOnly"),
        // "no_ktp" => array("required", "numberOnly", "unique", "singleOnly"),
        // "npwp" => array("required", "unique", "singleOnly"),
//        "nik" => array("required"),
//        "status" => array("required"),
        // "image_ktp" => array("image"),
        // "image_npwp" => array("image"),
        // "country" => array("required"),

//        "kategori_id" => array("required"),
    );

    protected $unionPairs = array(
        // "no_ktp", "npwp"
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "id" => "id",
        "nama" => "nama",
        "nama_depan" => "nama_depan",
        "nama_belakang" => "nama_belakang",
        "member_id" => "member_id",
        "tlp_1" => "tlp_1",
        "no_ktp" => "no_ktp",
        "npwp" => "npwp",
        "contact_person" => "contact_person",
    );

    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    protected $listedFieldsView = array();
    protected $fields = array(
        "id"                => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"              => array(
            "label"     => "label",
            "type"      => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
        ),
        "json"              => array(
            "label"     => "json",
            "type"      => "varchar", "length" => "255", "kolom" => "json",
            "inputType" => "hidden",
        ),
//        "separator"              => array(
//            "label"     => "karakter pemisah",
//            "type"      => "varchar", "length" => "255", "kolom" => "separator",
//            "inputType"    => "radio",
//            "dataSource"   => array(
//                "." => " titik (.)",
//                "-" => " strip (-)",
//                "/" => " garing (/)",
//                "" => "tanpa pemisah"
//            ),
//            "defaultValue" => ".",
//        ),
        "show_dtime"              => array(
            "label"     => "tampilkan tgl dan waktu",
            "type"      => "varchar", "length" => "255", "kolom" => "show_dtime",
            "inputType"    => "radio",
            "dataSource"   => array(0 => "sembunyikan", 1 => "tampilkan"),
            "defaultValue" => 1,
        ),
        "format_dtime"              => array(
            "label"     => "atur format waktu",
            "type"      => "varchar", "length" => "255", "kolom" => "format_dtime",
            "inputType"    => "radio",
            "dataSource"   => array(
                "Y" => "Year (2019)",
                "m" => "Month (01)",
                "d" => "Day (01)",
                "Ymd" => "Ymd (20190101)",
                "dmY" => "dmY (01012019)"
            ),
            "defaultValue" => "Ymd",
        ),
//        "sample"              => array(
//            "label"     => "sample",
//            "type"      => "varchar", "length" => "255", "kolom" => "sample",
//            "inputType" => "format-numbering",
////            "editable" => false,
//        ),

        "value"              => array(
            "label"     => "sample",
            "type"      => "varchar", "length" => "255", "kolom" => "value",
            "inputType" => "format-numbering",
//            "editable" => false,
        ),

//        "status"            => array(
//            "label"        => "status",
//            "type"         => "int", "length" => "24", "kolom" => "status",
//            "inputType"    => "combo",
//            "dataSource"   => array(0 => "inactive", 1 => "active"),
//            "defaultValue" => 1,
//        ),
        "default"              => array(
            "label"     => "set sebagai default setting",
            "type"      => "varchar", "length" => "255", "kolom" => "default",
            "inputType"    => "radio",
            "dataSource"   => array(0 => "tidak", 1 => "set sebagai default"),
            "defaultValue" => 1,
        ),
    );

    protected $listedFields = array(
//        "id"            => "pID",
        "nama"          => "nama/deskripsi",
        "separator"     => "karakter pemisah",
        "show_dtime"    => "tampilkan tgl & waktu",
        "format_dtime"  => "format waktu",
        "sample"        => "contoh numbering",
    );

    protected $pairValidate = array();

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
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
        $selecteds = array(
            "id",
            // "kode",
            "nama",
            // "employee_type",
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
        // $this->db->where("toko_id",my_toko_id());
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

    public function paramSyncNamaNama()
    {
        $mdls = array(
//            "MdlCustomerTipe" => array(
//                "id" => "kategori_id",
//                "kolomDatas" => array(
//                    "nama" => "kategori_nama",
//                ),
//            ),

        );

        return $mdls;

    }

    /*----------------------------------------------------------------
 * auto penambahan COA, bisa dugunakan keperluan lain
 * konnecting ke model yg lain
 * ----------------------------------------------------------*/
    // protected $connectingData = array(
    //     "MdlAccounts" => array(
    //         "path"          => "Mdls",
    //         "fungsi"        => "addExtern_coa",
    //         /* ------------------- ------------------- -------------------
    //          * staticOptions bisa handling array atau singgle
    //          * ---------------------------------------------------------*/
    //         "staticOptions" => array("020403","010201"),
    //         "fields"        => array(
    //             "extern_jenis"   => array(
    //                 "str" => "customer",
    //             ),
    //             "extern_id"      => array(
    //                 "var_main" => "mainInsertId",
    //             ),
    //             "rekening"      => array(
    //                 "var_main" => "mainInsertId",
    //             ),
    //             "head_name"      => array(
    //                 "var_main" => "nama",
    //             ),
    //             "p_head_name"    => array(
    //                 "var_main" => "strHead_code",
    //             ),
    //             "create_by"      => array(
    //                 "var_main" => "my_name",
    //             ),
    //             /* -------------------------------------------------
    //              * filter yg ingin langsung diaktifkan
    //              * -------------------------------------------------*/
    //             "is_active"      => array(
    //                 "str" => "1",
    //             ),
    //             "is_transaction" => array(
    //                 "str" => "1",
    //             ),
    //             "is_rekening_pembantu" => array(
    //                 "str" => "1",
    //             ),
    //             // "is_hutang" => array(
    //             //     "str" => "1",
    //             // ),
    //             // "is_gl" => array(
    //             //     "str" => "1",
    //             // ),
    //         ),
    //         "updateMain"    => array(
    //             "condites" => array(
    //                 "id" => "mainInsertId",
    //             ),
    //             "datas"    => array(
    //                 "coa_code" => "lastInset_code",
    //             )
    //         )
    //     )
    // );
    //
    // public function getConnectingData()
    // {
    //     return $this->connectingData;
    // }
    //
    // public function setConnectingData($connectingData)
    // {
    //     $this->connectingData = $connectingData;
    // }

}