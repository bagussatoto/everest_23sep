<?php

//--include_once "MdlHistoriData.php";

class MdlPaymentSourceProject extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nomer",
//        "satuan",
    );
    protected $sortBy = array(
        "kolom" => "dtime",
        "mode" => "ASC",
    );

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getIndexFields()
    {
        return $this->indexFields;
    }

    /**
     * @param string $indexFields
     */
    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return array
     */
    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    /**
     * @param array $listedFieldsSelectItem
     */
    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    /**
     * @return array
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param array $sortBy
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }


    function __construct()
    {
        parent::__construct();
        $this->tableName = "transaksi_payment_source";
        $this->indexFields = "id";
        $this->fields = array(
            "id" => array(
                "label" => "id",
                "type" => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "produk_id" => array(
                "label" => "produk_id",
                "type" => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",// hidden
                //--"inputName" => "produk_id",
            ),
            "nama" => array(
                "label" => "nama",
                "type" => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "jumlah" => array(
                "label" => "jumlah",
                "type" => "int", "length" => "24", "kolom" => "jumlah",
                "inputType" => "varchar",
                //--"inputName" => "jumlah",
            ),
            "satuan" => array(
                "label" => "satuan",
                "type" => "int", "length" => "24", "kolom" => "satuan",
                "inputType" => "varchar",
                //--"inputName" => "satuan",
            ),
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();


    }

//    public function cekLoker($cab, $prod, $state, $oleh = 0, $transaksi_id = 0)
//    {
////        $this->addFilter("jenis='$jenis'");
//        $this->addFilter("cabang_id='$cab'");
//        $this->addFilter("produk_id='$prod'");
//        $this->addFilter("state='$state'");
//        if ($oleh != 0) {
//            $this->addFilter("oleh_id='$oleh'");
//        }
//        if ($transaksi_id > 0) {
//            $this->addFilter("transaksi_id='$transaksi_id'");
//        }
//        $tmp = $this->lookupAll()->result();
//        cekMerah($this->db->last_query());
//        if (sizeof($tmp) > 0) {
//            return array(
//                "id" => $tmp[0]->id,
//                "jumlah" => $tmp[0]->jumlah
//            );
//        } else {
//            return array();
//        }
//    }


}