<?php

//--include_once "MdlHistoriData.php";
class MdlProdukPerSupplier extends MdlMother
{
    protected $tableName = "produk_per_supplier";
    protected $tableName2 = "produk";
    protected $indexFields = "id";
    protected $indexFields2 = "suppliers_id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "id" => "produk.id",
        "nama" => "produk.nama",
        "kode" => "produk.kode",
        "keterangan" => "produk.keterangan",
    );
    protected $search;
    protected $filters = array(
        "produk_per_supplier.status='1'",
        "produk_per_supplier.trash='0'",
        "produk_per_supplier.cabang_id='-1'",
//        "produk_per_supplier.suppliers='item'",
    );
    protected $sortBy = array(
        "kolom" => "produk_nama",
        "mode" => "ASC",
    );
    protected $validationRules = array(
        "produk_id" => array("required", "singleOnly"),
        "suppliers_id" => array("required"),
    );
    protected $validateData = array(
        "produk_id", "suppliers_id"
    );
    protected $conditional;

    public function getConditional()
    {
        return $this->conditional;
    }

    public function setConditional($conditional)
    {
        $this->conditional = $conditional;
    }

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

    protected $listedFieldsView = array("produk_nama");
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
        "produk id" => array(
            "label" => "produk",
            "type" => "int",
            "length" => "24",
            "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk",
            "name2" => "nama",
//            "name2" => "keterangan",
            //--"inputName" => "nama",
        ),
//        "produk nama"       => array(
//            "label"     => "produk nama",
//            "type"      => "int", "length" => "24",
//            "kolom" => "produk_nama",
//            "inputType" => "text",
//            //--"inputName" => "nama",
//        ),
        "supplier id" => array(
            "label" => "supplier",
            "type" => "int", "length" => "24",
            "kolom" => "suppliers_id",
            "inputType" => "combo",
            "reference" => "MdlSupplier",
            "defaultValue" => "id",
            //--"inputName" => "nama",
        ),
//        "supplier nama"       => array(
//            "label"     => "supplier nama",
//            "type"      => "int", "length" => "24",
//            "kolom" => "suppliers_nama",
//            "inputType" => "text",
//            //--"inputName" => "nama",
//        ),
        "status" => array(
            "label" => "status",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
//        "folders"    => "folder",
        "kode" => "kode",
//        "label"      => "label",
        "produk_id" => "produk name",
        "keterangan" => "keterangan",
    );


    //<editor-fold desc="getter and setter">
    public function getTableName2()
    {
        return $this->tableName2;
    }

    public function setTableName2($tableName2)
    {
        $this->tableName2 = $tableName2;
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
        $this->db->join($this->tableName, "$this->tableName.produk_id = $this->tableName2.id");
        $result = $this->db->get($this->tableName2);


        return $result;
    }


    public function lookupAll()
    {

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
//        arrPrint($criteria);

        $this->db->select("*, produk.kode as kode,produk_per_supplier.id as rel_id");
        $this->db->from($this->tableName);
        $this->db->join($this->tableName2, 'produk.id = produk_per_supplier.produk_id');
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
        $query = $this->db->get($this->tableName);
//        die($this->db->last_query());
        $data = array();

        if ($query->num_rows() > 0) {
            $listedData = array();
            foreach ($query->result() as $row) {
                $listedData[] = $row->produk_id;
//                $data[] = $row;
            }
            $tmp = "('" . implode("','", $listedData) . "')";

            $criteria = "produk_id in $tmp";

            $this->db->select('*', "produk.kode as kode");

//            $criteria = array();
//            $criteria2 = "";
//            if (sizeof($this->filters) > 0) {
//                $this->fetchCriteria();
//                $criteria = $this->getCriteria();
//                $criteria2 = $this->getCriteria2();
//            }
//        arrPrint($criteria);
            $this->db->from($this->tableName);
            $this->db->join($this->tableName2, 'produk.id = produk_per_supplier.produk_id');
            if (sizeof($criteria) > 0) {
                $this->db->where($criteria);
            }
//            if ($criteria2 != "") {
//                $this->db->where($criteria2);
//            }

            $val = $this->db->get();
            cekHitam($this->db->last_query());
            foreach ($val->result() as $row0) {
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

    public function callSpecs($supplierID)
    {
        $selecteds = array(
            "produk.id",
            "produk.kode",
            "produk.nama",
            "produk.label",
            "produk.folders_nama",
            "produk.kategori_id",
            "produk.kategori_nama",
            "produk.barcode",
            "produk.no_part",
            "produk.satuan",
        );
        $this->db->select($selecteds);
        if ($supplierID > 0) {
            $this->db->where("suppliers_id", $supplierID);
        }
        $this->db->where($this->tableName.".status", 1);
        $this->db->where($this->tableName.".trash", 0);
        $this->db->from($this->tableName2);
        $this->db->join($this->tableName, 'produk.id = produk_per_supplier.produk_id');
        $vars_0 = $this->db->get()->result();
//        showLast_query("biru");
//        arrPrintPink($val);
        // showLast_query("orange");
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    public function addProdukSupplier($head_code, $datas)
    {
        arrPrintHijau($head_code);
        // matiHere(__LINE__);
        /*------------mencari data yg sama sudah ada didb belom---------------*/
        $this_data = $this->db->select('*')
            ->from($this->tableName)
            ->where($head_code)
            ->get()
            ->row();
        // sizeof($this_data) == 0 ? matiHere("data untuk $head_code tidak ditemukan " . __METHOD__) : "";

        showLast_query("lime");

        /// menulis ke tabel
       $last_id =  $this->addData($datas);
        // showLast_query("merah");

        return $last_id;
        // return $head_code;
    }

    protected $innerJoint = array(
        "tabel_1" => array(
            "tbl"    => "produk", // slave
            "select" => array(
                "*",
            ),
            "where_slave"  => array(
                "trash" => 0,
            ),
            "on"     => array(
                "produk_id" => "id",
            )
        ),
    );
}