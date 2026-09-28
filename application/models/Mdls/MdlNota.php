<?php

//--include_once "MdlHistoriData.php";

class MdlNota extends MdlMother
{
    protected $tableName = "transaksi";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");
    protected $validationRules = array();
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"            => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nomer"         => array(
            "label"     => "receipt num",
            "type"      => "int", "length" => "24", "kolom" => "nomer",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "nilai"         => array(
            "label"     => "amount",
            "type"      => "int", "length" => "24", "kolom" => "transaksi_nilai",
            "inputType" => "number",
            //--"inputName" => "nama",
        ),
        "nilaitagihan"  => array(
            "label"     => "due amount",
            "type"      => "int", "length" => "24", "kolom" => "transaksi_nilai_tagihan",
            "inputType" => "number",
            //--"inputName" => "nama",
        ),
        "nilaiterbayar" => array(
            "label"     => "paid amount",
            "type"      => "int", "length" => "24", "kolom" => "transaksi_nilai_terbayar",
            "inputType" => "number",
            //--"inputName" => "nama",
        ),
        "nilaisisa"     => array(
            "label"     => "unpaid amount",
            "type"      => "int", "length" => "24", "kolom" => "transaksi_nilai_sisa",
            "inputType" => "number",
            //--"inputName" => "nama",
        ),


    );
    protected $listedFields = array(
        "nama"  => "name",
        //        "alamat_1"=>"address",
        "email" => "email",
        "tlp_1" => "phone",
        "npwp"  => "tax-ID",

    );

    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
                                              "nomer" => "nomer","suppliers_nama" =>"suppliers_nama",

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


}