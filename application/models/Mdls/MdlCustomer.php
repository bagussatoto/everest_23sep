<?php

//--include_once "MdlHistoriData.php";

class MdlCustomer extends MdlMother
{
    protected $tableName = "per_customers";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash='0'",
        //        "status='1'"
    );

    protected $validationRules = array(

        "kategori_id" => array("required"),
        "folder_id" => array("required"),
        "nama" => array("required", "singleOnly"),
        // "nama"   => array("required"),
        "alamat_1" => array("required"),
        /*--------------------------dimatikan oleh widi untuk merelasikan suplier 13Agus2026----------*/
        // "blok" => array("required"),
        // "rt" => array("required"),
        // "rw" => array("required"),
        // "kelurahan" => array("required"),
        // "kecamatan" => array("required"),
        // "kabupaten" => array("required"),
        // "propinsi" => array("required"),
        /*----------------------------------------------------------------*/
        // "tlp_1" => array("required", "numberOnly"),
        // "tlp_1" => array("numberOnly"),
        // "no_ktp" => array("required", "numberOnly", "unique", "singleOnly"),
        // "npwp" => array("required", "unique", "singleOnly"),
        //        "nik" => array("required"),
        "status" => array("required"),
        // "image_ktp" => array("image"),
        // "image_npwp" => array("image"),
        // "country" => array("required"),

        //        "kategori_id" => array("required"),
    );

    protected $unionPairs = array(// "no_ktp", "npwp"
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
        "alamat_1" => "alamat_1",
        "propinsi" => "propinsi",
        "kabupaten" => "kabupaten",
    );

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
    }

    protected $listedFieldsView = array("nama", "tlp_1", "npwp");
    protected $fields = array(
        "kategori_id" => array(
            "label" => "kategori konsumen",
            "type" => "int",
            "length" => "3",
            "kolom" => "kategori_id",
            "reference" => "MdlCustomerTipe",
            //            "defaultValue" => "ID",
            "inputType" => "radio",
            //--"inputName" => "alamat",
        ),
        "kategori_nama" => array(
            "label" => "kategori konsumen",
            "type" => "int",
            "length" => "3",
            "kolom" => "kategori_nama",
            "reference" => "MdlCustomerTipe",
            //            "defaultValue" => "ID",
            "inputType" => "hidden",
            //--"inputName" => "alamat",
        ),
        "folder_nama" => array(
            "label" => "kategori konsumen",
            "type" => "int",
            "length" => "3",
            "kolom" => "folder_nama",
            "reference" => "MdlCustomerTipe",
            //            "defaultValue" => "ID",
            "inputType" => "hidden",
            //--"inputName" => "alamat",
        ),
        "folder_id" => array(
            "label" => "jenis konsumen",
            "type" => "int",
            "length" => "3",
            "kolom" => "folder_id",
            "reference" => "MdlCustomerFolder",
            "inputType" => "radio",
        ),
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        //        "member_id" => array(
        //            "label" => "member ID",
        //            "type" => "varchar", "length" => "50", "kolom" => "member_id",
        //            "inputType" => "text",// hidden
        //            //--"inputName" => "id",
        //        ),
        "name" => array(
            "label" => "nama konsumen",
            "type" => "int",
            "length" => "255",
            "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        // "first_name"   => array(
        //     "label"     => "nama depan",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "nama_depan",
        //     "inputType" => "text",
        //     //--"inputName" => "nama_depan",
        // ),
        // "last_name"    => array(
        //     "label"     => "nama belakang",
        //     "type"      => "int",
        //     "length"    => "255",
        //     "kolom"     => "nama_belakang",
        //     "inputType" => "text",
        //     //--"inputName" => "nama_belakang",
        // ),
        //        "login_name" => array(
        //            "label" => "login ID",
        //            "type" => "int", "length" => "24", "kolom" => "nama_login",
        //            "inputType" => "text",
        //            //--"inputName" => "nama_login",
        //        ),
        "email" => array(
            "label" => "email",
            "type" => "varchar", "length" => "45", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "phone" => array(
            "label" => "Nomor Telepon",
            "type" => "varchar",
            "length" => "45",
            "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "Alamat",
            "type" => "int",
            "length" => "255",
            "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "alamat2" => array(
            "label" => "Jalan",
            "type" => "int", "length" => "255",
            "kolom" => "alamat_2",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "blok" => array(
            "label" => "Blok",
            "type" => "int",
            "length" => "255",
            "kolom" => "blok",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "nomer" => array(
            "label" => "nomer",
            "type" => "int",
            "length" => "255",
            "kolom" => "nomer",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "rt" => array(
            "label" => "RT",
            "type" => "int",
            "length" => "255",
            "kolom" => "rt",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "rw" => array(
            "label" => "RW",
            "type" => "int",
            "length" => "255",
            "kolom" => "rw",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kalurahan" => array(
            "label" => "kalurahan",
            "type" => "int",
            "length" => "255",
            "kolom" => "kelurahan",
            "inputType" => "text",
        ),
        "kecamatan" => array(
            "label" => "kecamatan",
            "type" => "int",
            "length" => "255",
            "kolom" => "kecamatan",
            "inputType" => "text",
        ),
        "kabupaten" => array(
            "label" => "kabupaten/Kota",
            "type" => "int",
            "length" => "255",
            "kolom" => "kabupaten",
            "inputType" => "text",
        ),
        "propinsi" => array(
            "label" => "propinsi",
            "type" => "int", "length" => "255", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "country" => array(
            "label" => "negara",
            "type" => "varchar", "length" => "3", "kolom" => "country",
            "reference" => "MdlCountry",
            "defaultValue" => "ID",
            "inputType" => "combo",
            //--"inputName" => "alamat",
        ),
        "nik" => array(
            "label" => "nik",
            "type" => "int", "length" => "255", "kolom" => "no_ktp",
            "inputType" => "text",
            //--"inputName" => "nik",
            "keterangan" => "isikan salah satu antara NIK atau NPWP.",
        ),
        "image_ktp" => array(
            "label" => "ID card image",
            "type" => "image", "length" => "", "kolom" => "image_ktp",
            "inputType" => "image",
        ),
        "npwp" => array(
            "label" => "npwp",
            "type" => "int", "length" => "255", "kolom" => "npwp",
            "inputType" => "text",
            //--"inputName" => "npwp",
            "keterangan" => "isikan salah satu antara NIK atau NPWP.",
        ),
        "image_npwp" => array(
            "label" => "npwp image",
            "type" => "image", "length" => "", "kolom" => "image_npwp",
            "inputType" => "image",
        ),
        //        "due time" => array(
        //            "label" => "due (in seconds)",
        //            "type" => "int", "length" => "24", "kolom" => "jatuh_tempo",
        //            "inputType" => "text",
        //            //--"inputName" => "jatuh_tempo",
        //        ),
        "jatuh tempo" => array(
            "label" => "TOP (hari)",
            "type" => "int",
            "length" => "24",
            "kolom" => "due_days",
            "inputType" => "text",
            //--"inputName" => "jatuh_tempo",
        ),
        "credit_limit" => array(
            "label" => "kredit limit",
            "type" => "int",
            "length" => "24",
            "kolom" => "kredit_limit",
            "inputType" => "text",
            //--"inputName" => "kredit_limit",
        ),
        "diskon" => array(
            "label" => "discount (%)",
            "type" => "int", "length" => "24", "kolom" => "diskon",
            "inputType" => "number",
            //--"inputName" => "diskon",
        ),
        "attn" => array(
            "label" => "CP",
            "type" => "int", "length" => "255", "kolom" => "contact_person",
            "inputType" => "text",
            //--"inputName" => "person_nama",
        ),
        //        "trash" => array(
        //            "label" => "trash",
        //            "type" =>"int","length"=>"24","kolom" => "trash",
        //            "inputType" => "int",
        //            //--"inputName" => "trash",
        //        ),
                     "parent" => array(
                   "label" => "Relasikan Supplier",
                   "type" => "varchar",
                   "length" => "255",
                   "kolom" => "parent",
                   "reference" => "MdlSupplier",
        //            "defaultValue" => "1",
                   "inputType" => "combo",
                   "strField" => "nama",
                   "editable" => true,
                   "kolom_nama" => "kategori_nama",
               ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
        //        "ppn" => array(
        //            "label" => "VAT factor (%)",
        //            "type" =>"int","length"=>"24","kolom" => "ppn",
        //            "inputType" => "number",
        //            //--"inputName" => "ppn",
        //        ),
        //---------------

    );

    protected $listedFields = array(
        "kategori_nama" => "kategori konsumen",
        "folder_nama" => "jenis konsumen",
        "nama" => "name",
        //        "member_id" => "member_id",
        "email" => "email",
        "tlp_1" => "nomer telepon",
        "no_ktp" => "nik",
        "npwp" => "npwp",
        "diskon" => "diskon(%)",
        "kredit_limit" => "kredit_limit",
        "alamat_1" => "alamat",
        "alamat_2" => "jalan",
        "blok" => "blok",
        "nomer" => "nomer",
        "rt" => "RT",
        "rw" => "RW",
        "kelurahan" => "desa",
        "kecamatan" => "kecamatan",
        "kabupaten" => "kabupaten",
        "propinsi" => "propinsi",
        "country" => "negara",
        "image_npwp" => "image npwp",
        "image_ktp" => "image ktp",
    );

    protected $pairValidate = array("nama");

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
            "tlp_1",
            "nama",
            "npwp",
            "due_days",
            "kredit_limit",
            // "nik",
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
            "MdlCustomerTipe" => array(
                "id" => "kategori_id",
                "kolomDatas" => array(
                    "nama" => "kategori_nama",
                ),
            ),
            "MdlCustomerFolder" => array(
                "id" => "folder_id",
                "kolomDatas" => array(
                    "nama" => "folder_nama",
                ),
            ),

        );

        return $mdls;

    }

    /**
     * Menentukan parent COA piutang customer berdasarkan standar hirarki.
     * Prioritas: folder_parent_map -> keyword folder -> branch_parent_map -> default.
     *
     * @param array $data
     * @param string $externTipe
     * @param array $paramConnecting
     * @return string
     */
    public function resolveCustomerArHeadCode($data = array(), $externTipe = "lokal", $paramConnecting = array())
    {
        $this->load->config("heAccounting");
        $cfg = $this->config->item("accountHierarchyPiutangDagang");
        $cfg = is_array($cfg) ? $cfg : array();

        $defaultParent = isset($cfg["default_parent"]) ? $cfg["default_parent"] : "1010020010";
        $projectParent = isset($cfg["project_parent"]) ? $cfg["project_parent"] : "1010020080";
        $resolvedParent = $defaultParent;

        // fallback luar lokal tetap mengikuti staticOptions bila didefinisikan.
        if ($externTipe != "lokal") {
            if (isset($paramConnecting["staticOptions"]) && is_array($paramConnecting["staticOptions"])) {
                if (isset($paramConnecting["staticOptions"][$externTipe])) {
                    $resolvedParent = $paramConnecting["staticOptions"][$externTipe];
                }
            }
        }

        $folderId = isset($data["folder_id"]) ? (int)$data["folder_id"] : 0;
        $cabangId = isset($data["cabang_id"]) ? (int)$data["cabang_id"] : 0;

        if ($folderId > 0 && isset($cfg["folder_parent_map"]) && is_array($cfg["folder_parent_map"])) {
            if (isset($cfg["folder_parent_map"][$folderId]) && ($cfg["folder_parent_map"][$folderId] != "")) {
                $resolvedParent = $cfg["folder_parent_map"][$folderId];
            }
        }

        if ($folderId > 0) {
            $folderName = "";
            if (isset($data["folder_name"]) && ($data["folder_name"] != "")) {
                $folderName = $data["folder_name"];
            }
            elseif (isset($data["folder_nama"]) && ($data["folder_nama"] != "")) {
                $folderName = $data["folder_nama"];
            }
            elseif (isset($data["folder_label"]) && ($data["folder_label"] != "")) {
                $folderName = $data["folder_label"];
            }
            else {
                $folderRow = $this->db->select(array("name", "nama", "label"))
                    ->from("per_customer_folders")
                    ->where("id", $folderId)
                    ->limit(1)
                    ->get()
                    ->row_array();
                if (is_array($folderRow) && sizeof($folderRow) > 0) {
                    if (isset($folderRow["name"]) && ($folderRow["name"] != "")) {
                        $folderName = $folderRow["name"];
                    }
                    elseif (isset($folderRow["nama"]) && ($folderRow["nama"] != "")) {
                        $folderName = $folderRow["nama"];
                    }
                    elseif (isset($folderRow["label"]) && ($folderRow["label"] != "")) {
                        $folderName = $folderRow["label"];
                    }
                }
            }

            $keywords = isset($cfg["project_keywords"]) && is_array($cfg["project_keywords"])
                ? $cfg["project_keywords"]
                : array("project", "projek");
            $folderNameLower = strtolower($folderName);
            foreach ($keywords as $keyword) {
                $kw = strtolower(trim($keyword));
                if (($kw != "") && (strpos($folderNameLower, $kw) !== false)) {
                    $resolvedParent = $projectParent;
                    break;
                }
            }
        }

        if ($cabangId > 0 && isset($cfg["branch_parent_map"]) && is_array($cfg["branch_parent_map"])) {
            $branchGroup = ($resolvedParent == $projectParent) ? "project" : "reguler";
            if (isset($cfg["branch_parent_map"][$branchGroup]) && is_array($cfg["branch_parent_map"][$branchGroup])) {
                if (isset($cfg["branch_parent_map"][$branchGroup][$cabangId]) && ($cfg["branch_parent_map"][$branchGroup][$cabangId] != "")) {
                    $resolvedParent = $cfg["branch_parent_map"][$branchGroup][$cabangId];
                }
            }
        }

        return $resolvedParent;
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
            // "staticOptions" => array("1010020010"), // yg mana dipakai?
            "staticOptions" => array(
                "lokal" => "1010020010",
                "non_lokal" => "1010020010",
            ),
            // parent COA dipilih dinamis berdasar hirarki customer.
            "staticOptionsResolver" => "resolveCustomerArHeadCode",
            "fields" => array(
                "extern_jenis" => array(
                    "str" => "customer",
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

    //
    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }

    // protected $pairedData = array(
    //     "MdlImages" => array(
    //         // "kolom"       => array(
    //         //     "files"         => "images",
    //         //     ),
    //         "kolom" => "image",
    //         "default_nilai" => 0,
    //         "label" => "image",
    //         "link" => "image",
    //         "methode" => "callSpecs",
    //         "methode_key" => "files",
    //     ),
    // );
    //
    // public function getPairedData()
    // {
    //     return $this->pairedData;
    // }
    //
    // public function setPairedData($pairedData)
    // {
    //     $this->pairedData = $pairedData;
    // }

}
