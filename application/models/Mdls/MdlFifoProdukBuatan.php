<?php

//--include_once "MdlHistoriData.php";
class MdlFifoProdukBuatan extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array();
    protected $listedFieldsSelectItem = array();


    function __construct()
    {
        parent::__construct();
        $this->tableName = "rek_cache_persediaan_produk_produksi_fifo";
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
                "type"      => "int", "length" => "24", "kolom" => "produk_nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "jumlah"    => array(
                "label"     => "jml",
                "type"      => "int", "length" => "24", "kolom" => "unit",
                "inputType" => "varchar",
                //--"inputName" => "jumlah",
            ),
            //            "jenis" => array(
            //                "label" => "jenis",
            //                "type" =>"int","length"=>"24","kolom" => "jenis",
            //                "inputType" => "varchar",
            //                //--"inputName" => "jenis",
            //            ),
            "cabang id" => array(
                "label"     => "cabang id",
                "type"      => "int", "length" => "24", "kolom" => "cabang_id",
                "inputType" => "int",
                //--"inputName" => "cabang_id",
            ),
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();

        $this->listedFieldsSelectItem = array("produk id", "nama", "jumlah", "cabang id");
    }

}