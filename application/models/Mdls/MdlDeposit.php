<?php

//--include_once "MdlHistoriData.php";

class MdlDeposit extends MdlMother
{

    protected $tableName = "dta_uang_muka";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
//        "jenis='uang muka'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "status" => array("required"),
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
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "jenis" => array(
            "label" => "jenis",
            "type" => "varchar", "length" => "255", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
//            "inputName" => "status",
        ),
    );

    protected $listedFields = array(
        "nama" => "nama",
        "oleh_name" => "create by",
        "dtime" => "dtime",
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


    //------------------------------------------------
    public function lookupSelected()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->createSmartSearch($key, $this->listedFieldsSelectItem);
        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        $result = $this->db->get($this->tableName);

//        $this->db->select('*,currency.id as id');
//        $this->db->from($this->tableName);
//        $tmpTblName = $this->tableName;
//        $this->db->join('_rek_pembantu_subvalas_cache', "_rek_pembantu_subvalas_cache.extern_id = $tmpTblName.id and _rek_pembantu_subvalas_cache.periode = 'forever'");
//
//        $result = $this->db->get();

        return $result;
    }

}