<?php

//--include_once "MdlHistoriData.php";
class MdlProjectKomposisiWorkorderSubTambahan extends MdlMother
{
    protected $tableName = "project_komposisi_sub_workoder_tambahan";
    protected $tableName2 = "produk";
    protected $indexFields = "id";
    protected $indexFielddasar_s = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "produk.nama",
    );
    protected $search;
    protected $filters = array(
        "jenis in ('supplies', 'biaya')",
        "project_komposisi_sub_workoder_tambahan.status='1'",
        "project_komposisi_sub_workoder_tambahan.trash='0'",
    );
    protected $sortBy = array(
        "kolom" => "produk_nama",
        "mode" => "ASC",
    );
    protected $validationRules = array(
        "produk_id" => array("required", "singleOnly"),
    );

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }


    protected $listedFieldsView = array("produk_nama");
    protected $fields = array(

    );
    protected $listedFields = array(
        "produk_id" => "produk name",
        "produk_dasar_id" => "component name",
        "jml" => "qty",
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

    public function lookupByPID($pID)
    {
        $criteria = array("produk_id" => $pID);
        $criteria2 = "";
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
        // $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }

    public function lookupBiayaByPID($pID)
    {
        $criteriax = array("produk_id" => $pID, "jenis" => "biaya", "status" => "1", "trash" => "0");
        $criteria2 = "";
        // arrPrint($this->getCriteria());
        if (sizeof($this->filters) > 0) {
            $criteria0 = $this->getCriteria();
            // $criteria = $this->getCriteria();
            $criteria = $criteriax;
            $criteria2 = $this->getCriteria2();
        }
        // arrPrint($criteria0);
        // arrPrintWebs($criteria);
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        // $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }

    public function addKomposisiBiaya($condite, $datas)
    {
        $data = $condite + $datas;
        arrPrint($data);
        $var = parent::addData($data); // TODO: Change the autogenerated stub

        return $var;
    }

    public function deleteKomposisiBiaya($where)
    {
        $datas = array(
            "trash" => 1,
        );
        $this->setFilters(array());
        $this->addFilter("jenis='biaya'");

        $var = parent::updateData($where, $datas); // TODO: Change the autogenerated stub

        return $var;
    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "barcode",
            "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            "satuan",
            "jenis",
            // "kendaraan_nama",
        );
        // $this->db->select($selecteds);

        // if (isset($produkIds)) {
        $this->setFilters(array());
        if (is_array($produkIds)) {
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("produk_id", $produkIds);
            }
        }
        $condites = array(
            "status" => 1,
            "trash" => 0,
        );
        $this->db->where($condites);

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->produk_id][] = $item;
            // $vars[] = $item;
        }


        return $vars;
    }

    public function lookUpKomposisiFase(){
        $selectField = array(
            "id",
            "produk_dasar_id",
            "produk_id","produk_nama", "jml",
            "jenis", "produk_nama", "produk_dasar_nama", "nilai","harga","fase_id",
            "gudang_id","gudang2_id","cat_id","cat_nama","satuan","satuan_id","fase_id","jenis","status","trash"
        );
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

        //$this->db->limit(15, 0);// walah ngapain dilimit 15 disini?

        // $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);

        $tmp = $this->db->get($this->tableName)->result();
        // cekBiru($this->db->last_query());
        $data = array();
        if(count($tmp)>0){
            foreach($tmp as $tmp_0){
                $tmp = array();
                foreach($selectField as $i =>$field){
                    $tmp[$field]=$tmp_0->$field;
                }
                $data[$tmp_0->fase_id][$tmp_0->jenis][]=$tmp;
                // arrPrint($tmp_0);
            }
        }
        // arrprint($data);
        // matiHEre();
        return $data;
    }
}