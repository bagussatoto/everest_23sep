<?php

//--include_once "MdlHistoriData.php";

class MdlLockerDebtValue extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array(
        "periode='forever'",
//        "jenis='produk'",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "rekening", "kredit",
    );


    function __construct()
    {
        parent::__construct();
        $this->tableName = "_rek_master_cache";
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

    public function cekLoker($cab)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("periode='forever'");

        $tmp = $this->lookupAll()->result();
        cekMerah($this->db->last_query());

        if (sizeof($tmp) > 0) {
            return array(
                "id" => $tmp[0]->id,
                "nilai" => $tmp[0]->nilai,
            );
        }
        else {
            return array();
        }
    }


}