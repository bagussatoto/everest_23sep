<?php


class MdlBiayaProduksi_prebiaya extends MdlMother
{
    protected $tableName = "produk_pre_biaya_produksi";
    protected $tableName2 = "produk_pre_biaya";
    protected $indexFields = "id";
    protected $indexFields2 = "biayaproduksi_id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
//        "nama" => "produk.nama",
//        "kode" => "produk.kode",
//        "keterangan" => "produk.keterangan",
    );
    protected $search;
    protected $filters = array(
        "produk_pre_biaya_produksi.status='1'",
        "produk_pre_biaya_produksi.trash='0'",
    );
    protected $sortBy = array(
        "kolom" => "id",
        "mode" => "ASC",
    );
    protected $validationRules = array(
        "biayaproduksi_id" => array("required", "singleOnly"),
    );
    protected $validateData = array(
        "biayaproduksi_id",
//        "pre_biaya_id",
    );


    public function getValidateData()
    {
        return $this->validateData;
    }

    public function setValidateData($validateData)
    {
        $this->validateData = $validateData;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    protected $listedFieldsView = array(
        "pre_biaya_nama",
        "biayaproduksi_nama",
    );
//    protected $listedFieldsView = array("kode");

    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "biayaproduksi id" => array(
            "label" => "expense",
            "type" => "int",
            "length" => "24",
            "kolom" => "biayaproduksi_id",
            "inputType" => "combo",
            "reference" => "MdlDtaBiayaProduksi",
            "kolom_nama" =>"biayaproduksi_nama",
            "strField" =>"nama",
        ),
        "pre biaya id" => array(
            "label" => "product cost",
            "type" => "int",
            "length" => "24",
            "kolom" => "pre_biaya_id",
            "inputType" => "combo",
            "reference" => "MdlProdukRakitanPreBiaya",
            "kolom_nama" =>"pre_biaya_nama",
            "strField" =>"nama",
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
//        "kode"       => "kode",
        "pre_biaya_id" => "product cost",
        "biayaproduksi_id" => "expense name",
//        "keterangan" => "keterangan",
    );


    //<editor-fold desc="getter and setter">
    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getTableName2()
    {
        return $this->tableName2;
    }

    public function setTableName2($tableName2)
    {
        $this->tableName2 = $tableName2;
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

    //</editor-fold>

    public function lookupByKeyword($key)
    {
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }
        $colCtr = 0;
        $this->db->where($criteria);

        $this->db->group_start();

        foreach ($this->listedFieldsSelectItem as $fieldName) {
            $colCtr++;
            if ($colCtr == 1) {
                $this->db->like($fieldName, $key);
            }
            else {
                $this->db->or_like($fieldName, $key);
            }
        }
        $this->db->group_end();
        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        $this->db->join($this->tableName, "$this->tableName.pre_biaya_id = $this->tableName2.id");
        $result = $this->db->get($this->tableName2);


        return $result;
    }

    public function lookupAll()
    {
        $this->db->select('*');
//        $this->db->select('*', "produk_pre_biaya_produksi as id");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
//        arrPrint($criteria);
        $this->db->from($this->tableName);
        $this->db->join($this->tableName2, 'produk_pre_biaya.id = produk_pre_biaya_produksi.pre_biaya_id');
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $val = $this->db->get();
//        arrPrint($val);
//        cekHitam($this->db->last_query());
//        die();
        return $val;
    }

    public function lookupLimitedData($limit, $start, $key = "", $condition = null)
    {
        $this->db->select("*,produk_pre_biaya_produksi.id as id");
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }

        $this->db->where($criteria);

        if ($key != "") {
//            $this->db->group_start();
//            $colCtr = 0;
//            foreach ($this->fields as $fName => $fSpec) {
//                $colCtr++;
//                $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
//                if ($colCtr == 1) {
//                    $this->db->like($this->tableName . "." . $fieldName, $key);
//                } else {
//                    $this->db->or_like($this->tableName . "." . $fieldName, $key);
//                }
//            }
//            $this->db->group_end();

            $tmpCols = array();
            foreach ($this->fields as $fName => $fSpec) {
                $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                $tmpCols[$fieldName] = $fieldName;
            }
            $this->createSmartSearch($key, $tmpCols);
        }


        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
//            cekkuning("sorting by ".$this->sortBy['kolom']);
        }
        else {
//            cekkuning("not sorting");
        }

        $this->db->limit($limit, $start);
        $this->db->from($this->tableName);
        $query = $this->db->get();
//        $query = $this->db->get($this->tableName);
//        die($this->db->last_query());
        $data = array();

        if ($query->num_rows() > 0) {
            $listedData = array();
            foreach ($query->result() as $row) {
                $listedData[] = $row->biayaproduksi_id;
//                $data[] = $row;
            }
            $tmp = "('" . implode("','", $listedData) . "')";

            $criteria = "biayaproduksi_id in $tmp";

            $this->db->select("*,produk_pre_biaya_produksi.id as id");
//            cekHitam($this->db->last_query());
//            $criteria = array();
//            $criteria2 = "";
//            if (sizeof($this->filters) > 0) {
//                $this->fetchCriteria();
//                $criteria = $this->getCriteria();
//                $criteria2 = $this->getCriteria2();
//            }
//        arrPrint($criteria);
            $this->db->from($this->tableName);
            $this->db->join($this->tableName2, 'produk_pre_biaya.id = produk_pre_biaya_produksi.pre_biaya_id');
            if (sizeof($criteria) > 0) {
                $this->db->where($criteria);
            }
//            if ($criteria2 != "") {
//                $this->db->where($criteria2);
//            }

            $val = $this->db->get();
//            cekHitam($this->db->last_query());
            foreach ($val->result() as $row0) {
//                arrPrint($row0);
                $data[] = $row0;
            }
//            arrPrint($val);

            return $data;
        }
        else {
            return $data;
        }

//        cekHitam($tmp);
//        die();

        return false;
    }
}