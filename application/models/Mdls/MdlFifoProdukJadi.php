<?php

//--include_once "MdlHistoriData.php";
class MdlFifoProdukJadi extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array();
    protected $listedFieldsSelectItem = array();


    function __construct()
    {
        parent::__construct();
        $this->tableName = "rek_cache_persediaan_produk_fifo";
        $this->indexFields = "id";
        $this->fields = array(
            "id" => array(
                "label" => "id",
                "type" => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "produk id" => array(
                "label" => "produk_id",
                "type" => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",// hidden
                //--"inputName" => "produk_id",
            ),
            "nama" => array(
                "label" => "nama",
                "type" => "int", "length" => "24", "kolom" => "produk_nama",
                "inputType" => "text",
                //--"inputName" => "nama",
            ),
            "jumlah" => array(
                "label" => "jml",
                "type" => "int", "length" => "24", "kolom" => "unit",
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
                "label" => "cabang id",
                "type" => "int", "length" => "24", "kolom" => "cabang_id",
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

    public function lookupAllQty()
    {
        $criteria = array();
        $criteria2 = "";
        $this->db->select("sum(unit) as qty");
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

        return $this->db->get($this->tableName);

    }

    public function lookupTrash()
    {
        $condites = array(
            "unit" => "0",
        );
        $tmp = $this->lookupByCondition($condites);

        return $tmp;
    }

    private function deleteTrash()
    {
        $condites = array(
            "unit" => 0
        );

        return $this->deleteData($condites);

    }

    public function moveToTrash()
    {
        $tbl_trash = "rek_cache_persediaan_produk_fifo_trash";
        $srcs = $this->lookupTrash()->result();
        showLast_query("orange");
        if (sizeof($srcs) > 0) {

            cekHijau("ada " . sizeof($srcs) . " sampah fifo");
            foreach ($srcs as $datas) {

                $this->db->insert($tbl_trash, $datas);
                showLast_query("kuning");
            }

            $xx = $this->deleteTrash();
            // cekMerah("$xx");
            showLast_query("merah");
        }
        else {
            matiHere("fifo tidak ada sampah");
        }
        // arrPrint($srcs);

        return 1;
    }


}