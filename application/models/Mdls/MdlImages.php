<?php

//--include_once "MdlHistoriData.php";

class MdlImages extends MdlMother
{
    protected $tableName = "images";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(

//    "nama"   => array("required", "singleOnly"),
//    "tlp_1"  => array("required", "numberOnly"),
//    "no_ktp" => array("required", "numberOnly"),
//    "npwp"   => array("required"),
//    "status" => array("required"),
//    "image_ktp" => array("image"),
        "files" => array("image"),

    );

    protected $listedFieldsView = array("nama", "tlp_1", "npwp");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "parent_id" => array(
            "label" => "produk id",
            "type" => "int", "length" => "24", "kolom" => "parent_id",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "jenis" => array(
            "label" => "jenis",
            "type" => "varchar", "length" => "255", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "nama_depan",
        ),
        "files" => array(
            "label" => "images",
            "type" => "image", "length" => "", "kolom" => "files",
            "inputType" => "file",
            //--"inputName" => "nama_belakang",
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
        "files" => "images",
//        "email" => "email",
//        "tlp_1" => "phone",
//        "npwp"  => "tax-ID",

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

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "kategori_id",
            "kategori_nama",
            "barcode",
            "no_part",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            "satuan",
            "jml_serial",
            "diskon_persen",
            "premi_jual",
            "supplier_id",
            "merek_nama",
            "kategori_nama",
        );
        // $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("id", $produkIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        foreach ($vars_0 as $item) {
            $vars[$item->parent_id][] = $item;
        }


        return $vars;
    }
}