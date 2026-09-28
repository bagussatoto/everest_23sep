<?php

class MdlDiskonPenjualan extends MdlMother
{
    protected $tableName = "diskon_penjualan";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "produk_id"        => array("required"),
        // "supplier_id"      => array("required"),
        "produk_rel_id"    => array("required"),
        "produk_rel_nama"  => array("required"),
        // "produk_rel_satuan_id"   => array("required"),
        // "produk_rel_satuan_nama" => array("required"),
        "produk_rel_harga" => array("required"),
        // "produk_rel_qty"   => array("required"),
        "qty_min"          => array("required"),


    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

        "produk_id"        => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "produk_id",
            "inputType" => "hidden",
            //--"inputName" => "nama",
            // "reference"  => false,
            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "nilai_hadiah"      => array(
            "label"     => "nilai_hadiah",
            "type"      => "int",
            "length"    => "255",
            "kolom" => "nilai_hadiah",
            "inputType" => "hidden",
            //--"inputName" => "nama",
            // "reference"  => false,
            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "produk_rel_qty"   => array(
            "label"        => "qty hadiah",
            "type"         => "int",
            "length"       => "255",
            "kolom"        => "produk_rel_qty",
            "inputType"    => "text",
            "editable"     => false,
            "defaultValue" => 1,
            "format"       => "angka",
            "strField"     => "nama",
        ),
        "produk_rel_harga" => array(
            "label"     => "harga hadiah",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "produk_rel_harga",
            "inputType" => "text",
            "format"    => "angka",
            // "strField"  => "nama",
        ),
        "qty_min"          => array(
            "label"     => "sdk (minimal qty)",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "qty_min",
            "inputType" => "text",
            "strField"  => "nama",
        ),
        "start_date"       => array(
            "label"     => "tgl mulai",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "start_date",
            "inputType" => "date",
            "strField"  => "nama",
        ),
        "expired_date"     => array(
            "label"     => "tgl berakhir",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "expired_date",
            "inputType" => "date",
            "strField"  => "nama",
        ),
        "status"           => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nama" => "name",


    );
    protected $data;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    //region gs
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

    //endregion
    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "produk_id",
            "produk_nama",
            "produk_rel_id",
            "produk_rel_nama",
            "produk_rel_qty",
            "produk_rel_harga",
            "produk_rel_satuan_nama",
            "qty_min",
            "start_date",
            "expired_date",
            "persen",
            "nilai",
            "kelipatan",
            "status",
            "dtime",
            "nilai_hadiah",
            // "dtime",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("produk_id", $produkIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->produk_id] = $item;
        }


        return $vars;
    }

    public function fetchPairDiskon($produk_id, $supplierID)
    {

        $curentDate = date("Y-m-d");
        //        $curentDate = "2023-09-01";
        $this->addFilter("produk_id='$produk_id'");
        // $this->addFilter("supplier_id='$supplierID'");//UI seting diskon tidak kenal supplier jadi filter ini dimatikan 22 april 2024
        $this->db->limit(1);
        $this->db->order_by("id", "desc");

        $this->db->select("*");
        $this->db->select('produk_rel_harga / produk_rel_qty AS produk_rel_harga_unit', FALSE);
        $tmp = $this->lookupAll()->result();
        $result = array();
        if (count($tmp) > 0) {
            $startDate = $tmp[0]->start_date;
            $expiredDate = $tmp[0]->expired_date;
            if ($curentDate >= $startDate && $curentDate <= $expiredDate) {
                $result = $tmp;
            }

        }
        return $result;
    }


    public function writeFreeDiscProduk()
    {
        $datas = isset($this->data) ? $this->data : "data belum diset untuk ditulis";

        arrPrintPink($datas);
        $produk_id = $datas['produk_id'];
        $src_data = $this->callSpecs($produk_id);
        $jml_data = count($src_data);
        showLast_query("biru", $jml_data);

        if ($jml_data == 0) {
            cekHijau("create");
            $this->addData($datas);
            showLast_query("orange");

        }
        else {
            cekKuning("update");
            $condites = array(
                "produk_id" => $produk_id,
            );
            $data_upds = array(
                "trash"       => 1,
                "trash_dtime" => $datas['dtime'],
                "oleh_id"     => $datas['oleh_id'],
            );
            $this->updateData($condites, $data_upds);
            showLast_query("merah");
            $this->addData($datas);
            showLast_query("orange");
        }

        return 1;
    }

    public function callFreeProduk($produk_ids = ""){
        $curentDate = date("Y-m-d");
        if ($produk_ids != "") {
            if (is_array($produk_ids)) {
//                matiHere("undefine ligic " . __METHOD__);
                $this->db->where_in("produk_id",$produk_ids);
            }
            else {
                $condite_lain = array(
                    "produk_id" => $produk_ids,
                );
                $this->db->where($condite_lain);
            }
        }

        $table_name = $this->tableName;
//        matiHere($table_name);
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
        $src = $this->db->get($table_name)->result();
//        showLast_query("kuning");
        $listData = array();
        if(count($src)>0){
            foreach ($src as $src_0){
                $listData[$src_0->produk_id]=(array)$src_0;
            }
        }

//        arrPrint($listData);

//         arrPrint($src);
//matiHere();
        return $listData;
    }

}