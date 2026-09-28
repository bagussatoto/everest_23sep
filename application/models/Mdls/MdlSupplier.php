<?php

//--include_once "MdlHistoriData.php";
class MdlSupplier extends MdlMother
{

    protected $tableName = "per_supplier";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("employee_type='supplier'", "status='1'", "trash='0'");

    protected $validationRules = array(
        "nama"  => array("required", "singleOnly"),
        // "tlp_1" => array("required", "numberOnly"),
        "tlp_1" => array("numberOnly"),

        // "no_ktp" => array("numberOnly", "unique", "singleOnly"),
        // "npwp" => array("unique", "singleOnly"),
        //        "no_ktp" => array("numberOnly", "singleOnly"),
        //        "npwp" => array("singleOnly"),

        "status" => array("required"),
        // "country" => array("required"),
        // "tipe" => array("required"),

        //        "wajib_pajak" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"           => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        //        "tipe" => array(
        //            "label" => "jenis usaha",
        //            "type" => "varchar", "length" => "255", "kolom" => "tipe",
        //            "reference" => "MdlCompanyMethod",
        ////            "defaultValue" => "ID",
        //            "inputType" => "combo",
        //        ),
        "name"         => array(
            "label"     => "name",
            "type"      => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        //        "first_name" => array(
        //            "label" => "first name",
        //            "type" => "int", "length" => "24", "kolom" => "nama_depan",
        //            "inputType" => "text",
        //            //--"inputName" => "nama_depan",
        //        ),
        //        "last_name" => array(
        //            "label" => "last name",
        //            "type" => "int", "length" => "24", "kolom" => "nama_belakang",
        //            "inputType" => "text",
        //            //--"inputName" => "nama_belakang",
        //        ),
        //        "login_name" => array(
        //            "label" => "login ID",
        //            "type" => "int", "length" => "24", "kolom" => "nama_login",
        //            "inputType" => "text",
        //            //--"inputName" => "nama_login",
        //        ),
        "email"        => array(
            "label"     => "email",
            "type"      => "int", "length" => "255", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp"         => array(
            "label"     => "phone",
            "type"      => "int", "length" => "255", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat"       => array(
            "label"     => "address",
            "type"      => "int", "length" => "255", "kolom" => "alamat_1",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "kabupaten"    => array(
            "label"     => "district",
            "type"      => "int", "length" => "255", "kolom" => "kabupaten",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "propinsi"     => array(
            "label"     => "province",
            "type"      => "int", "length" => "255", "kolom" => "propinsi",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "country"      => array(
            "label"        => "country",
            "type"         => "varchar",
            "length"       => "3",
            "kolom"        => "country",
            "reference"    => "MdlCountry",
            "defaultValue" => "ID",
            "inputType"    => "combo",
            //--"inputName" => "alamat",
        ),
        //-----------------
        //        "wajib_pajak" => array(
        //            "label" => "status pajak",
        //            "type" => "varchar", "length" => "255", "kolom" => "wajib_pajak",
        //            "reference" => "MdlWajibPajak",
        ////            "defaultValue" => "ID",
        //            "inputType" => "combo",
        //        ),
        //-----------------
        "nik"          => array(
            "label"     => "nik",
            "type"      => "int", "length" => "255", "kolom" => "no_ktp",
            "inputType" => "text",
            //--"inputName" => "nik",
        ),
        "npwp"         => array(
            "label"     => "NPWP",
            "type"      => "int", "length" => "255", "kolom" => "npwp",
            "inputType" => "text",
            //--"inputName" => "npwp",
        ),
        "pkp"       => array(
            "label"        => "PKP/Non PKP",
            "type"         => "varchar",
            "length"       => "255",
            "kolom"        => "pkp",
            "reference"    => "MdlStaticPkp",
            "defaultValue" => "1",
            "inputType"    => "combo",
            // "dataSource" => array(0 => "NON-PKP", 1 => "PKP"), "defaultValue" => 1,
            "checbox"          => true,
            "checbox_fungsi" => "checkboxUpdProject",
        ),
        // "country_oye"      => array(
        //     "label"        => "pkpkpk",
        //     "type"         => "varchar",
        //     "length"       => "3",
        //     "kolom"        => "pkp",
        //     "reference"    => "MdlStaticPkp",
        //     "defaultValue" => "1",
        //     "inputType"    => "combo",
        // ),

        //        "due time" => array(
        //            "label" => "due (in seconds)",
        //            "type" => "int", "length" => "24", "kolom" => "jatuh_tempo",
        //            "inputType" => "text",
        //            //--"inputName" => "jatuh_tempo",
        //        ),
        "jatuh tempo"  => array(
            "label"     => "term of payment",
            "type"      => "int", "length" => "255", "kolom" => "due_days",
            "inputType" => "text",
            //--"inputName" => "jatuh_tempo",
        ),
        "credit_limit" => array(
            "label"     => "credit limit",
            "type"      => "int", "length" => "255", "kolom" => "kredit_limit",
            "inputType" => "text",
            //--"inputName" => "kredit_limit",
        ),


        "ppn"    => array(
            "label"     => "VAT factor (%)",
            "type"      => "int", "length" => "255", "kolom" => "ppn",
            "inputType" => "number",
            //--"inputName" => "ppn",
        ),
        "diskon" => array(
            "label"     => "discount (%)",
            "type"      => "int", "length" => "255", "kolom" => "diskon",
            "inputType" => "number",
            //--"inputName" => "diskon",
        ),
        "attn"   => array(
            "label"     => "CP/Up to",
            "type"      => "int", "length" => "255", "kolom" => "contact_person",
            "inputType" => "text",
            //--"inputName" => "person_nama",
        ),
        "status" => array(
            "label"      => "status",
            "type"       => "int", "length" => "255", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "id"       => "id",
        "nama"     => "name",
        "alamat_1" => "address",
        "email"    => "email",
        "tlp_1"    => "phone",
        "country"  => "country",
        // "pkp"  => "pkp",
        "npwp"     => "npwp",

    );
    protected $unionPairs = array(
        //        "no_ktp",
        //        "npwp",
    );

    function __construct()
    {
        parent::__construct();
    }

    public function getUnionPairs()
    {
        return $this->unionPairs;
    }

    public function setUnionPairs($unionPairs)
    {
        $this->unionPairs = $unionPairs;
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

    public function callSpecs($dataIds = "")
    {
        $selecteds = array(
            "id",
            // "kode",
            "nama",
            "dpp_rebate",
            // "folders_nama",
            // "barcode",
            // "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            // "satuan",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {

        if (is_array($dataIds)) {
            $this->setFilters(array());
            $this->db->where_in("id", $dataIds);
        }
        else {
            if ($dataIds == "") {

            }
            else {
                $this->db->where("id", $dataIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
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
            // "staticOptions" => array("2010010010"),
            "staticOptions" => "2010010010",
            "fields"        => array(
                "extern_jenis"         => array(
                    "str" => "customer",
                ),
                "extern_id"            => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening"             => array(
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
                "is_transaction"       => array(
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

    //
    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }
}