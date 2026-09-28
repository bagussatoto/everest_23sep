<?php

//--include_once "MdlHistoriData.php";
class MdlFifoAverageSupplies extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array();
    protected $listedFieldsSelectItem = array();


    function __construct()
    {
        parent::__construct();
        $this->tableName = "fifo_avg";
        $this->indexFields = "id";
        $this->fields = array(
            "id"        => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "produk id" => array(
                "label"     => "produk_id",
                "type"      => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",// hidden
                //--"inputName" => "produk_id",
            ),
            "nama"      => array(
                "label"     => "nama",
                "type"      => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "jumlah"    => array(
                "label"     => "jml",
                "type"      => "int", "length" => "24", "kolom" => "jml",
                "inputType" => "varchar",
                //--"inputName" => "jumlah",
            ),
            "jenis"     => array(
                "label"     => "jenis",
                "type"      => "int", "length" => "24", "kolom" => "jenis",
                "inputType" => "varchar",
                //--"inputName" => "jenis",
            ),
            "cabang id" => array(
                "label"     => "cabang id",
                "type"      => "int", "length" => "24", "kolom" => "cabang_id",
                "inputType" => "int",
                //--"inputName" => "cabang_id",
            ),
            //            "satuan" => array(
            //                "label" => "satuan",
            //                "type" =>"int","length"=>"24","kolom" => "satuan",
            //                "inputType" => "varchar",
            //                //--"inputName" => "satuan",
            //            ),
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();

        $this->listedFieldsSelectItem = array("produk id", "nama", "jumlah", "jenis", "cabang id");
    }


    public function getTableName()
    {
        return $this->tableName;
    }


    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
}