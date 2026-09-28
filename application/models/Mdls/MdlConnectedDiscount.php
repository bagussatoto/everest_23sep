<?php

//--include_once "MdlHistoriData.php";
class MdlConnectedDiscount extends MdlMother
{
    protected $tableName = "diskon_relasi";
    protected $tableName2 = "produk";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "diskon_nama",
    );
    protected $search;
    protected $filters = array(
        "diskon_relasi.status='1'",
        "diskon_relasi.trash='0'",
        );
    protected $sortBy = array(
        "kolom" => "diskon_nama",
        "mode"  => "ASC",
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



    protected $listedFieldsView = array("diskon_nama");
    protected $fields = array(
        "id"         => array(
            "label"     => "id",
            "type"      => "int",
            "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "produk id"       => array(
            "label"     => "produk",
            "type"      => "int",
            "length" => "24",
            "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk",
            //--"inputName" => "nama",
        ),
        "diskon nama"       => array(
            "label"     => "diskon nama",
            "type"      => "varchar", "length" => "255",
            "kolom" => "diskon_nama",
            "inputType" => "hidden",
//            "reference" => "MdlAddDiscount",
            //--"inputName" => "nama",
        ),
        "diskon id"       => array(
            "label"     => "diskon",
            "type"      => "int", "length" => "24",
            "kolom" => "diskon_id",
            "inputType" => "combo",
            "reference" => "MdlAddDiscount",
            //--"inputName" => "nama",
        ),
        "status" => array(
            "label"      => "status",
            "type"       => "int", "length" => "24", "kolom" => "status",
            "inputType"  => "combo",
            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
        ),
//        "supplier nama"       => array(
//            "label"     => "supplier nama",
//            "type"      => "int", "length" => "24",
//            "kolom" => "suppliers_nama",
//            "inputType" => "text",
//            //--"inputName" => "nama",
//        ),
    );
    protected $listedFields = array(
//        "folders"    => "folder",
//        "kode"       => "kode",
//        "label"      => "label",
        "diskon_id"       => "active discount",
//        "keterangan" => "keterangan",
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
            } else {
                $this->db->or_like($fieldName, $key);
            }
        }
        $this->db->group_end();
        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        $this->db->join($this->tableName, "$this->tableName.produk_id = $this->tableName2.id");
        $result = $this->db->get($this->tableName2);
//        cekBiru($this->db->last_query());


        return $result;
    }
}