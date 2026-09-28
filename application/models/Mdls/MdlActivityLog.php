<?php

class MdlActivityLog extends MdlMother
{

    protected $tableName = "log";
    protected $fields = array(

        "uid" => array(
            "label" => "user id",
            "type" => "int", "length" => "24", "kolom" => "uid",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "uname" => array(
            "label" => "oleh",
            "type" => "int", "length" => "24", "kolom" => "uname",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "dtime" => array(
            "label" => "tanggal",
            "type" => "timestamp", "length" => "24", "kolom" => "dtime",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "transaksi_id" => array(
            "label" => "transaksi id",
            "type" => "int", "length" => "24", "kolom" => "transaksi_id",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "deskripsi_old" => array(
            "label" => "deskripsi_old",
            "type" => "int", "length" => "24", "kolom" => "deskripsi_old",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "deskripsi_new" => array(
            "label" => "deskripsi_new",
            "type" => "int", "length" => "24", "kolom" => "deskripsi_new",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "jenis" => array(
            "label" => "jenis",
            "type" => "int", "length" => "24", "kolom" => "jenis",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "ipadd" => array(
            "label" => "ipadd",
            "type" => "int", "length" => "24", "kolom" => "ipadd",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "devices" => array(
            "label" => "devices",
            "type" => "int", "length" => "24", "kolom" => "devices",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "category" => array(
            "label" => "category",
            "type" => "int", "length" => "24", "kolom" => "category",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "controller" => array(
            "label" => "controller",
            "type" => "int", "length" => "24", "kolom" => "controller",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "method" => array(
            "label" => "method",
            "type" => "int", "length" => "24", "kolom" => "method",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "url" => array(
            "label" => "url",
            "type" => "int", "length" => "24", "kolom" => "url",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "devices" => array(
            "label" => "device",
            "type" => "int", "length" => "24", "kolom" => "devices",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "title" => array(
            "label" => "title",
            "type" => "int", "length" => "24", "kolom" => "title",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "subtitle" => array(
            "label" => "subTitle",
            "type" => "int", "length" => "24", "kolom" => "sub_title",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
    );
    protected $indexFields;
    protected $validationRules = array();
    protected $listedFields = array(
        "dtime" => "tanggal",
        "uname" => "oleh",
//        "transaksi_id" => "transaksi id",
//        "deskripsi_old" => "deskripsi",
//        "deskripsi_new" => "deskripsi",
        "category" => "category",
        "title" => "title",
        "sub_title" => "sub-title",
//        "jenis" => "jenis",
        "ipadd" => "location",
        "devices" => "device",

//        "controller" => "controller",
//        "method" => "method",
//        "url" => "url",

    );
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
//    protected $search;
    protected $filters = array("status='1'", "trash='0'", "ghost='0'");

    protected $sortBy = array(
        "kolom" => "dtime",
        "mode" => "desc",
    );


    function __construct()
    {
        parent::__construct();

//        cekHere($this->tableName);
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }


    //  region setter, getter


    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getTableName()
    {
        return $this->tableName;
    }


    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
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


    public function setListedFieldsView($listedFields)
    {
        $this->listedFields = $listedFields;
    }


    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }


    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }


    public function getIndexFields()
    {
        return $this->indexFields;
    }


    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }


    public function getValidationRules()
    {
        return $this->validationRules;
    }


    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }


    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

//    protected $filters = array();
    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }
    //  endregion setter, getter

    //--menulis nilai log ke table
    public function writeLogData($detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
//                foreach ($arrKolom as $kolom) {
//                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
//                    $data[$kolom] = $isi;
//                }
                $this->db->insert($this->tableName["log"], $detailParams);

                cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }


}