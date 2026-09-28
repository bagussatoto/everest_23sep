<?php


class MdlMongoFinanceConfig extends MdlMongoMother
{

    protected $tableName = "finance_config";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status" => "1",
        "trash" => "0",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "tlp_1" => array("required", "numberOnly"),
        "status" => array("required"),
        "division" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "param" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "param",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "values" => array(
            "label" => "values",
            "type" => "longblob", "kolom" => "values",
            "inputType" => "text",
            //--"inputName" => "telp",
        ),
        "bln" => array(
            "label" => "bln",
            "type" => "int", "length" => "2", "kolom" => "bln",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "thn" => array(
            "label" => "thn",
            "type" => "int", "length" => "4", "kolom" => "thn",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "periode" => array(
            "label" => "periode",
            "type" => "varchar", "length" => "255", "kolom" => "periode",
            "inputType" => "text",
            //--"inputName" => "alamat",
        ),
        "tipe" => array(
            "label" => "tipe",
            "type" => "varchar", "length" => "255", "kolom" => "tipe",
            "inputType" => "text",
            //--"inputName" => "alamat",
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
        "alamat" => "address",

    );


    //region getter dan setter
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
    //endregion

}