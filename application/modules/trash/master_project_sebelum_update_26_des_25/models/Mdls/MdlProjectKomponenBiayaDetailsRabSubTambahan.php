<?php

//--include_once "MdlHistoriData.php";

class MdlProjectKomponenBiayaDetailsRabSubTambahan extends MdlMother
{

    protected $tableName = "project_komponen_biaya_details_rab_sub_tambahan";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;

//    protected $filters = array("jenis<>'division'","status='1'", "trash='0'");
    protected $filters = array("status='1'", "trash='0'");
    protected $validationRules = array(
        "nama"   => array("required", "singleOnly"),
        "cat_id"  => array("required"),
        "status" => array("required"),
    );
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"        => array(
            "label"     => "id",
            "type"      => "int", "length" => "240", "kolom" => "id",
            "inputType" => "hidden",
        ),
        "nama"      => array(
            "label"     => "nama",
            "type"      => "int", "length" => "240", "kolom" => "nama",
            "inputType" => "text",
        ),
        "cat_id" => array(
            "label" => "kategori biaya",//kategori
            "type" => "int",
            "length" => "255",
            "kolom" => "cat_id",
            "inputType" => "combo",
            "reference" => "MdlProdukRakitanPreBiaya",
            "strField" => "nama",
            "kolom_nama" => "cat_nama",
        ),
        "cat_nama"      => array(
            "label"     => "kategori nama",
            "type"      => "int",
            "length"    => "240",
            "kolom"     => "cat_nama",
            "inputType" => "hidden",
        ),
        "rekening"      => array(
            "label"     => "rekening",
            "type"      => "int",
            "length"    => "240",
            "kolom"     => "rekening",
            "inputType" => "hidden",
        ),

//        "rekening" => array(
//            "label" => "rekening",
//            "type" => "int",
//            "length" => "255",
//            "kolom" => "rekening",
//            "inputType" => "combo",
//            "reference" => "MdlProdukRakitanPreBiaya",
//            "strField" => "nama",
//            "kolom_nama" => "rekening",
//        ),

        "coa_code"      => array(
            "label"     => "coa_code",
            "type"      => "int", "length" => "240", "kolom" => "coa_code",
            "inputType" => "hidden",
        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),

    );
    protected $listedFields = array(
        "project_nama"       => "PROJECT",
        "no_spk"   => "NO SPK",
        "biaya_nama"   => "JENIS BIAYA",
        "data"   => "SUPPLIES/BIAYA",
        "request_by"   => "DIREQUEST OLEH",
        "request_dtime"   => "REQUEST DATE",
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

    public function lookupByPID($pID)
    {
        $criteria = array("biaya_id" => $pID);
//        $criteria = "";
        $criteria2 = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $criteria + $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
//        $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }

    protected $connectingData = array(
//        "MdlAccounts" => array(
//            "path"          => "Mdls",
//            "fungsi"        => "addExtern_coa",
//            /* ------------------- ------------------- -------------------
//             * staticOptions bisa handling array atau singgle
//             * ---------------------------------------------------------*/
//            // "staticOptions" => "0601",//old
//            "staticOptions" => "6020",//new coa
//            "fields"        => array(
//                "extern_jenis"   => array(
//                    "str" => "rekening_in",
//                ),
//                "extern_id"      => array(
//                    "var_main" => "mainInsertId",
//                ),
//                "rekening"      => array(
//                    "var_main" => "mainInsertId",
//                ),
//                "head_name"      => array(
//                    "var_main" => "nama",
//                ),
//                "p_head_name"    => array(
//                    "var_main" => "strHead_code",
//                ),
//                "create_by"      => array(
//                    "var_main" => "my_name",
//                ),
//                /* -------------------------------------------------
//                 * filter yg ingin langsung diaktifkan
//                 * -------------------------------------------------*/
//                "is_active"      => array(
//                    "str" => "1",
//                ),
//                "is_transaction" => array(
//                    "str" => "1",
//                ),
//                "is_rekening_pembantu" => array(
//                    "str" => "1",
//                ),
//                "is_gl" => array(
//                    "str" => "1",
//                ),
//            ),
//            "updateMain"    => array(
//                "condites" => array(
//                    "id" => "mainInsertId",
//                ),
//                "datas"    => array(
//                    "coa_code" => "lastInset_code",
//                )
//            )
//        )
    );

    public function getConnectingData()
    {
        return $this->connectingData;
    }
    public function setConnectingData($connectingData)
    {
        $this->connectingData = $connectingData;
    }
    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlProdukRakitanPreBiaya" => array(
                "id" => "cat_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "cat_nama",
                ),
            ),

        );

        return $mdls;

    }

}