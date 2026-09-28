<?php

//--include_once "MdlHistoriData.php";
class MdlProdukFase extends MdlMother
{
    protected $tableName = "produk_fase";
    protected $indexFields = "id";
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "ASC",
    );

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");
//    protected $filters = array("jenis='folder'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        // "cabang_id" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama" => array(
            "label"     => "nama",
            "type"      => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "aktivitas" => array(
            "label"     => "aktivitas",
            "type"      => "varchar", "length" => "255", "kolom" => "aktivitas",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "cabang_id" => array(
            "label"     => "cabang",
            "type"      => "varchar", "length" => "255", "kolom" => "cabang_id",
            "inputType" => "hidden_ref",
            "reference"       => "MdlProdukRakitan",
            "referenceFilter" => array("id=produk_id"),
            "referenceSrc"    => "cabang_id",
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
        "produk_id" => "produk_id",
        "nama" => "nama",
        "steps_code" => "kode",
        "urut" => "urut",
        "cabang_id" => "cabang_id",
        "aktivitas" => "aktivitas",
    );



    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

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

    /*----------------------------------------------------------------
 * auto penambahan COA, bisa dugunakan keperluan lain
 * konnecting ke model yg lain
 * ----------------------------------------------------------*/
    protected $connectingData = array(
        "MdlAccounts" => array(
            "path"          => "Mdls",
            "fungsi"        => "addExtern_coa",
            /* ------------------- ------------------- -------------------
             * staticOptions bisa handling array atau singgle
             * ---------------------------------------------------------*/
            "staticOptions" => "010304",
            "fields"        => array(
                "extern_jenis"   => array(
                    "str" => "produk",
                ),
                "extern_id"      => array(
                    "var_main" => "mainInsertId",
                ),
                "rekening"      => array(
                    "var_main" => "mainInsertId",
                ),
                "head_name"      => array(
                    "var_main" => "nama",
                ),
                "p_head_name"    => array(
                    "var_main" => "strHead_code",
                ),
                "create_by"      => array(
                    "var_main" => "my_name",
                ),
                /* -------------------------------------------------
                 * filter yg ingin langsung diaktifkan
                 * -------------------------------------------------*/
                "is_active"      => array(
                    "str" => "1",
                ),
                "is_transaction" => array(
                    "str" => "1",
                ),
                "is_rekening_pembantu" => array(
                    "str" => "1",
                ),
                // "is_hutang" => array(
                //     "str" => "1",
                // ),
                // "is_gl" => array(
                //     "str" => "1",
                // ),
            ),
            "updateMain"    => array(
                "condites" => array(
                    "id" => "mainInsertId",
                ),
                "datas"    => array(
                    "coa_code" => "lastInset_code",
                )
            )
        )
    );

    public function getConnectingData()
    {
        return $this->connectingData;
    }

    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }

    public function conectedProduct(){
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

        //        $this->db->limit(15, 0);// walah ngapain dilimit 15 disini?

        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);

        $tmp = $this->db->get($this->tableName)->result();
        $result = array();
        if(sizeof($tmp)>0){
            foreach($tmp as $tmp_0){
                $result[$tmp_0->produk_id][$tmp_0->urut] = array(
                    "nama"=>$tmp_0->nama,
                    "aktivitas"=>$tmp_0->aktivitas,
                    "cabang_id"=>$tmp_0->cabang_id,
                    "gudang_id"=>$tmp_0->gudang_id,
                    "gudang2_id"=>$tmp_0->gudang2_id,
                    "gudang2_nama"=>$tmp_0->gudang2_nama,
                    "kode"=>$tmp_0->kode,
                    "jenis_master"=>$tmp_0->jenis_master,
                    "kode_transaksi"=>$tmp_0->kode_transaksi,
                    "next_kode_transaksi"=>$tmp_0->next_kode_transaksi,
                    "urut"=>$tmp_0->urut,
                );
            }
        }

        return $result;
    }

    public function lookUpAvailFase($produk_id){
        $where=array();
        if(is_array($produk_id)){
            $this->db->where_in("produk_id", $produk_id);
        }
        else{
            $this->db->where("produk_id", $produk_id);
        }
        $this->db->order_by("urut","asc");
        $vars_0 = $this->lookupAll()->result();
        $faseUrut = array();
        if(count($vars_0)>0){
            foreach($vars_0 as $tempVar){
                $faseUrut[$tempVar->urut]=$tempVar->urut;
            }
        }
        return $faseUrut;
        // arrprint($faseUrut);


    }

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }
}