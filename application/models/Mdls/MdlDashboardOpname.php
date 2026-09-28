<?php

//--include_once "MdlHistoriData.php";
class MdlDashboardOpname extends MdlMother
{
    protected $tableName = "dashboard_opname";
    protected $indexFields = "id";
    // protected $cabang_id;

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'");

    protected $validationRules = array(
        // "nama" => array("required", "singleOnly"),
        // "tlp_1" => array("required", "numberOnly"),
        // "no_ktp" => array("required", "numberOnly"),
        // "npwp" => array("required"),
        // "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "cabang" => array(
            "label" => "branch",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            //--"inputName" => "nama",
        ),

        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "nama" => "name",
        "cabang_id" => "branch",
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

    public function lookupAktiveOpname()
    {

        $result = $this->db->get($this->tableName);

        return $result;
    }

    public function cekOpnameAktive($opname_condites = array())
    {
        $cabang_id = isset($this->cabang_id) ? $this->cabang_id : matiHere("cabang_id harap di set dulu " . __METHOD__); // u/san
        // $cabang_id = isset($this->cabangId) ? $this->cabangId : matiHere("cabang_id harap di set dulu " . __METHOD__);       // u/luar san
        if ($cabang_id != false) {
            $cabangs = array(
                "cabang_id" => $cabang_id,
            );
        }
        else {
            $cabangs = array();
        }
        $op_condites = array(
                "status" => "1",
                "confirm_id" => "0",

            ) + $cabangs + $opname_condites;

        $srcOpname = $this->lookupByCondition($op_condites)->result();
        // showLast_query("hijau");
        // cekHijau(sizeof($srcOpname));

        return $srcOpname;
    }

}