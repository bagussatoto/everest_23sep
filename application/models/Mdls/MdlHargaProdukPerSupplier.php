<?php

//--include_once "MdlHistoriData.php";
class MdlHargaProdukPerSupplier extends MdlMother
{
    protected $tableName = "price_per_supplier";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "trash='0'",
//        "jenis='produkSupplier'",
        "jenis='produk'",
        "status='1'"
    );

    protected $validationRules = array(
        //        "nama" => array("required", "singleOnly"),
        //        "status" => array("required"),
    );
    protected $sortBy = array(
        "kolom" => "produk_id",
        "mode" => "desc",
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "text",// hidden
            //--"inputName" => "id",
        ),
        "jenis" => array(
            "label" => "jenis",
            "type" => "int", "length" => "24", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "jenis_value" => array(
            "label" => "jenis",
            "type" => "int", "length" => "24", "kolom" => "jenis_value",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "produk_id" => array(
            "label" => "produk_id",
            "type" => "int", "length" => "24", "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk",
            //--"inputName" => "label",
        ),
        "cabang_id" => array(
            "label" => "cabang_id",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            //--"inputName" => "nama",
        ),
        "suppliers_id" => array(
            "label" => "suppliers_id",
            "type" => "int", "length" => "24", "kolom" => "suppliers_id",
            "inputType" => "combo",
            "reference" => "MdlCabang",
            //--"inputName" => "nama",
        ),
        "nilai" => array(
            "label" => "nilai",
            "type" => "int", "length" => "24", "kolom" => "nilai",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "oleh_id" => array(
            "label" => "oleh_id",
            "type" => "int", "length" => "24", "kolom" => "oleh_id",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "oleh_nama" => array(
            "label" => "oleh_name",
            "type" => "int", "length" => "24", "kolom" => "oleh_nama",
            "inputType" => "text",
            //--"inputName" => "",
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
        "produk_id" => "product",
        "suppliers_id" => "vendor",
        "jenis_value" => "price",
        "nilai" => "price value",
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

    public function lookupProdukSupplier()
    {
        $this->load->model("Mdls/MdlSupplier");
        $sp = new MdlSupplier();
        $columSelected = array(
          "nama","id"
        );
        $this->db->select($columSelected);
        $spAll = $sp->lookupAll();
        showLast_query("hijau");

        // $all = $this->lookupAll();
        $all = $this->get;

// arrPrint($spAll->result());
        arrPrintWebs($spAll);
// $xx = array_merge($spAll,$all);
// arrPrintWebs($xx);;
        return $all;
    }

    public function callSpecs($produkIds = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus di set dulu");
        $cabang_id = isset($this->cabang_id) ? $this->cabang_id : matiHere("cabang_id harus di set dulu");
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "barcode",
            "no_part",
            "merek_nama",
            "model_nama",
            "type_nama",
            "tahun",
            "lokasi_nama",
            "satuan",
            "diskon_persen",
            "harga_jual",
        );
        // $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            if ($produkIds == "") {

            }
            else {
                $this->db->where("produk_id", $produkIds);
            }
        }
        $this->db->where(array("toko_id" => $toko_id, "cabang_id" => $cabang_id));

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->produk_id][] = $item;
        }


        return $vars;
    }

    public function callHistories($produkIds = "")
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harus di set dulu");
        $cabang_id = isset($this->cabang_id) ? $this->cabang_id : matiHere("cabang_id harus di set dulu");
        $selecteds = array(
            "id",
            "kode",
            "nama",
            "label",
            "folders_nama",
            "barcode",
            "no_part",
            "merek_nama",
            "model_nama",
            "type_nama",
            "tahun",
            "lokasi_nama",
            "satuan",
            "diskon_persen",
            "harga_jual",
        );
        // $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            if ($produkIds == "") {

            }
            else {
                $this->db->where("produk_id", $produkIds);
            }
        }
        $this->db->where(array("toko_id" => $toko_id, "cabang_id" => $cabang_id));
        $this->setFilters(array());
        $condites = array(
            // "trash" => '1'
        );
        $this->db->where($condites);
        // $this->setSortBy("id");
        $this->db->order_by("id", "desc");
        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->produk_id][] = $item;
        }


        return $vars;
    }

}