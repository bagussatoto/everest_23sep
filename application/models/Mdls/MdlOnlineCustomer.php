<?php

//--include_once "MdlHistoriData.php";
class MdlOnlineCustomer extends MdlMother
{

    protected $tableName = "online_customers";
    private $tableNames = array(
        "main"  => "online_customers",
        "child" => "per_customers",
    );


    protected $indexFields = "id";

    protected $validateData = array(
        "customers_tipe_online_nama", "customers_id"
    );
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        // "address.jenis='bill'", "address.extern_type='customer'",
        "online_customers.status='1'",
        "online_customers.trash='0'"
    );
    protected $sortBy = array(
        // "kolom" => "alias",
        // "mode"  => "ASC",
    );
    protected $validationRules = array(

        // "tlp_1"     => array("required", "numberOnly"),
        // "no_ktp"    => array("required", "numberOnly"),
        // "npwp"      => array("required"),
        // "status"    => array("required"),
        // "tlp"       => array("required"),
        // "alamat"    => array("required"),
        // "kabupaten" => array("required"),
        // "propinsi"  => array("required"),
        // "alias"  => array("required"),
    );
    protected $unionPairs = array();
    protected $listedFieldsView = array();
    protected $fields = array(
        "id"                    => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "customer"              => array(
            "label"        => "customer",
            "type"         => "int",
            "length"       => "24",
            "kolom"        => "customers_id",
            "inputType"    => "combo",
            "reference"    => "MdlCustomer",
            "strField"     => "nama",
            "editable"     => true,
            "kolom_nama"   => "customers_nama",
            "defaultValue" => "id",

        ),
        // "extern_type" => array(
        //     "label"     => "extern_type",
        //     "type"      => "int",
        //     "length" => "24",
        //     "kolom" => "extern_type",
        //     "inputType" => "hidden",
        //     // "defaultValue" => "customer",
        //     "defaultValueKey" => "tp",
        // ),
        // "jenis" => array(
        //     "label"     => "jenis",
        //     "type"      => "int",
        //     "length" => "24",
        //     "kolom" => "jenis",
        //     "inputType" => "hidden",
        //     "defaultValue" => "jn",
        //     "defaultValueKey" => "jn",
        // ),
        "customers_tipe_online" => array(
            "label"      => "type online",
            "type"       => "int",
            "length"     => "24",
            "kolom"      => "customers_tipe_online_id",
            "inputType"  => "radio",
            "reference"  => "MdlCustomerTipeOnline",
            "strField"   => "nama",
            "kolom_nama" => "customers_tipe_online_nama",
            // "defaultValue" => "jn",
            // "defaultValueKey" => "tr",
        ),

        // "nama" => array(
        //     "label"     => "nama",
        //     "type"      => "varchar", "length" => "64",
        //     "kolom"     => "nama",
        //     "inputType" => "text",
        //     //--"inputName" => "email",
        // ),
        "customers_alias"       => array(
            "label"     => "nama di marketplace",
            "type"      => "varchar",
            "length"    => "64",
            "kolom"     => "customers_alias",
            "inputType" => "text",
            //--"inputName" => "email",
        ),
        // "no_ktp" => array(
        //     "label"     => "ktp",
        //     "type"      => "varchar", "length" => "255", "kolom" => "no_ktp",
        //     "inputType" => "text",
        //     //--"inputName" => "email",
        // ),
        // "npwp" => array(
        //     "label"     => "npwp",
        //     "type"      => "varchar", "length" => "255", "kolom" => "npwp",
        //     "inputType" => "text",
        //     //--"inputName" => "email",
        // ),
        //
        // "email"     => array(
        //     "label"     => "email",
        //     "type"      => "int", "length" => "24", "kolom" => "email",
        //     "inputType" => "text",
        //     //--"inputName" => "email",
        // ),
        // "telp"      => array(
        //     "label"     => "phone",
        //     "type"      => "int", "length" => "24", "kolom" => "tlp",
        //     "inputType" => "text",
        //     //--"inputName" => "telp",
        // ),
        // "telp2"     => array(
        //     "label"     => "phone#2",
        //     "type"      => "int", "length" => "24", "kolom" => "tlp_2",
        //     "inputType" => "text",
        //     //--"inputName" => "telp",
        // ),
        // "telp3"     => array(
        //     "label"     => "phone#3",
        //     "type"      => "int", "length" => "24", "kolom" => "tlp_3",
        //     "inputType" => "text",
        //     //--"inputName" => "telp",
        // ),
        // "alamat"    => array(
        //     "label"     => "alamat",
        //     "type"      => "int", "length" => "255", "kolom" => "alamat",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kelurahan" => array(
        //     "label"     => "kelurahan",
        //     "type"      => "int", "length" => "24", "kolom" => "kelurahan",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kecamatan" => array(
        //     "label"     => "kecamatan",
        //     "type"      => "int", "length" => "24", "kolom" => "kecamatan",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kabupaten" => array(
        //     "label"     => "kabupaten",
        //     "type"      => "int", "length" => "24", "kolom" => "kabupaten",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "propinsi"  => array(
        //     "label"     => "propinsi",
        //     "type"      => "int", "length" => "24", "kolom" => "propinsi",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        // "kodepos"   => array(
        //     "label"     => "kode pos",
        //     "type"      => "varchar", "length" => "6", "kolom" => "kodepos",
        //     "inputType" => "text",
        //     //--"inputName" => "alamat",
        // ),
        //
        // "status" => array(
        //     "label"      => "status",
        //     "type"       => "int", "length" => "24", "kolom" => "status",
        //     "inputType"  => "combo",
        //     "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
        //     //--"inputName" => "status",
        // ),
    );
    protected $listedFields = array(
        "customers_tipe_online_id" => "marketplace terelasi",
        "customers_alias" => "nama di marketplace",
        // "alias"     => "ATTN (PIC)",
        // "no_ktp"    => "ktp",
        // "npwp"      => "npwp",
        // "alamat"    => "address",
        // "kecamatan" => "kecamatan",
        // "kabupaten" => "kabupaten",
        // "propinsi"  => "propinsi",
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

    public function getTableNames()
    {
        return $this->tableNames;
    }

    public function setTableNames($tableNames)
    {
        $this->tableNames = $tableNames;
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

    public function lookUpJointCustomer()
    {
        $this->db->select("address.id,address.extern_id,address.alias,address.alamat,address.kabupaten,address.propinsi,address.tlp,address.tlp_2,address.kecamatan,per_customers.no_ktp as nik,per_customers.npwp as npwp");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }

        //        $this->db->select('*')
        $this->db->from('address');
        $this->db->join('per_customers', 'per_customers.id = address.extern_id');
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        //            ->where('list_id', $id);

        $val = $this->db->get();
        cekHijau($this->db->last_query());
        return $val;
        //        arrPrint($criteria);
        //die();
        //        $this->db->join($this->tableNames['child'], $this->tableNames['child'] . ".id = " . $this->tableNames['main'] . ".extern_id");
        //        cekHijau($this->db->last_query());
        //        return $this->db->get($this->tableNames['main']);

    }

}