<?php

//--include_once "MdlHistoriData.php";

class MdlEmployeeWorker extends MdlMother
{
    protected $tableName = "per_employee";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
        "employee_type='employee_worker'",
        "ghost='0'",
        // "cabang_id" // trigger filter cabang_id akan diisi di form herlper
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "nama_login" => array("required", "singleOnly", "unique"),
//        "password" => array("required"),
        "status" => array("required"),
//        "country" => array("required"),
        "division" => array("required"),
        "div_id" => array("required"),
//        "cabang_id" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "division" => array(
            "label" => "division",
            "type" => "int",
            "length" => "24",
            "kolom" => "div_id",
            "inputType" => "combo",
            "reference" => "MdlDiv",
            "defaultValue" => 18,
        ),
//        "login_name" => array(
//            "label" => "login ID",
//            "type" => "int", "length" => "24", "kolom" => "nama_login",
//            "inputType" => "text",
//            //--"inputName" => "nama_login",
//        ),
//        "password" => array(
//            "label" => "password",
//            "type" => "int", "length" => "24", "kolom" => "password",
//            "inputType" => "password",
//            //--"inputName" => "nama_login",
//        ),
        "email" => array(
            "label" => "email",
            "type" => "int", "length" => "24", "kolom" => "email",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        "telp" => array(
            "label" => "telp",
            "type" => "int", "length" => "24", "kolom" => "tlp_1",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "alamat" => array(
            "label" => "alamat",
            "type" => "int", "length" => "24", "kolom" => "alamat_1",
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
//        "country" => array(
//            "label" => "country",
//            "type" => "varchar", "length" => "3", "kolom" => "country",
//            "reference" => "MdlCountry",
//            "defaultValue" => "ID",
//            "inputType" => "combo",
//            //--"inputName" => "alamat",
//        ),
//        "cabang" => array(
//            "label" => "branch",
//            "type" => "int", "length" => "24", "kolom" => "cabang_id",
//            "inputType" => "combo",
//            "reference" => "MdlCabang",
//            "referenceFilter" => array(
//                "id<>.-1"
//            ),
//        ),
//        "membership" => array(
//            "type" => "mediumblob",
//            "label" => "access rights",
//            "kolom" => "membership",
//            "inputType" => "checkbox",
//            //                "dataSource" => $this->config->item('userGroups'),
//            "dataSource" => array(//===see __construct
//            ),
//        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
//        "phpsessid" => array(
//            "label" => "phpsessid",
//            "type" => "varchar", "length" => "255", "kolom" => "phpsessid",
//            "inputType" => "hidden",// hidden
//        ),
    );
    protected $listedFields = array(
        "div_id" => "division",
        "id" => "id",
        "nama" => "name",
//        "nama_login" => "login name",
//        "cabang_id" => "branch",
        //        "alamat_1"   => "address",
        "email" => "email",
        "tlp_1" => "phone",
        //        "gudang_id"  => "gudang",
        "kabupaten" => "kabupaten",
        "propinsi" => "propinsi",
    );

    protected $listedUnsetFields = array(
//        "phpsessid",
    );


    public function __construct()
    {
//        $this->fields['membership']['dataSource'] = $this->config->item('userGroup_cabang');
        $this->load->helper("he_access_right");
//        $this->fields['membership']['groupTransaksiLabel'] = groupAccessLabel_he_access_right();
    }

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

    public function getListedUnsetFields()
    {
        return $this->listedUnsetFields;
    }

    public function setListedUnsetFields($listedUnsetFields)
    {
        $this->listedUnsetFields = $listedUnsetFields;
    }

    //endregion

    public function lookupSeller()
    {
        $koloms = array(
            "id" => array(),
            "nama_login" => array(),
            "nama" => array(),
            "nama_depan" => array(),
            "nama_belakang" => array(),
            "email" => array(),
            "tlp_1" => array(),
            "last_dtime" => array(),
            "cabang_id" => array(),
            "gudang_id" => array(),
            "employee_type" => array(),
            "last_dtime_active" => array(),
        );
        $this->addFilter("jenis='seller'");
        $vars["raws"] = parent::lookupAll(); // TODO: Change the autogenerated stub
        $vars["koloms"] = $koloms;
        return (object)$vars;
    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            // "kode",
            "nama",
            "employee_type",
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
}