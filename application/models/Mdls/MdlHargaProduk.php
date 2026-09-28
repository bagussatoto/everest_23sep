<?php

//--include_once "MdlHistoriData.php";
class MdlHargaProduk extends MdlMother
{
    protected $tableName = "price";
    protected $indexFields = "id";
    protected $toko_id;

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    protected $cabang_id;

    public function getCabangId()
    {
        return $this->cabang_id;
    }

    public function setCabangId($cabang_id)
    {
        $this->cabang_id = $cabang_id;
    }

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("trash='0'", "jenis='produk'", "status='1'");

    protected $validationRules = array(
//        "nama" => array("required", "singleOnly"),
//        "status" => array("required"),
    );
    protected $sortBy = array(
        "kolom" => "dtime",
        "mode" => "asc",
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

    );
    protected $listedFields = array(
        "produk_id" => "product",
        "cabang_id" => "branch",
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

    public function callProdukHarga($produk_ids = "")
    {
        // -----------------------------
        $showKoloms = array(
            "produk_id" => "produk_id",
            "nilai" => "nilai",
            "cabang_id" => "cabang_id",
            "dtime" => "dtime",

        );
        if (is_array($produk_ids)) {
            $this->db->where_in("produk_id", $produk_ids);
            // $this->db->where_in("price_per_supplier.produk_id", $produk_ids);
        }
        elseif ($produk_ids > 0) {
            $this->db->where("produk_id", $produk_ids);
            // $this->db->where("price_per_supplier.produk_id", $produk_ids);
        }
        else {
            // tanpa condite
        }
        // $this->db->select("*,");
        $condites = array(
            "trash" => 0,
            "status" => 1,
            "jenis" => "produk",
        );
        $this->db->where($condites);
        // $condites_dua = "per_supplier.id = produk_per_supplier.suppliers_id";
        // $this->db->join("per_supplier", $condites_dua);

        // $condites_tiga = "per_supplier.id = price_per_supplier.suppliers_id and price_per_supplier.jenis_value='hpp'";
        // $this->db->join("price_per_supplier", $condites_tiga);
        $this->db->order_by('dtime', 'asc');
        $vals = $query = $this->db->get($this->tableName)->result();
        // showLast_query("orange");
        // arrPrint($vals);
        $val = array();
        foreach ($vals as $item) {
            // if ($item->suppliers_id > 0) {
            foreach ($showKoloms as $srcKolom => $outKolom) {

                $datas[$outKolom] = $item->$srcKolom;
            }
            // $datas['suppliers_nama'] = $item->nama;
            // $datas['suppliers_kode'] = $item->suppliers_kode;
            $val[$item->produk_id][$item->cabang_id][$item->jenis_value] = $datas;
            // }
        }

        return $val;
    }

    public function getHargaJual($idProduks)
    {
        $cabang_pilihan = $this->cabang_id;
        $key_acuan = "harga_list";
        $condite_hargas = array(
            "cabang_id" => "$cabang_pilihan",
        );
        $this->db->where($condite_hargas);
        $hrgs = $this->callProdukHarga($idProduks);
        // showLast_query("merah");
        // arrPrintKuning(array_slice($hrgs, 0, 1));
        $harga_produks = array();
        foreach ($hrgs as $hrg_datas) {
            foreach ($hrg_datas as $cb_id => $hrg_data) {
                $pro_id = isset($hrg_data[$key_acuan]) ? $hrg_data[$key_acuan]['produk_id'] : 0;
                $pro_harga = isset($hrg_data[$key_acuan]) ? $hrg_data[$key_acuan]['nilai'] * 1 : 0;

                $harga_produks[$pro_id] = $pro_harga;
            }
        }

        return $harga_produks;
    }

    public function callProdukHpp($produk_ids = "")
    {
        // -----------------------------
        $showKoloms = array(
            "produk_id" => "produk_id",
            "nilai" => "nilai",
            "cabang_id" => "cabang_id",
            "dtime" => "dtime",

        );
        if (is_array($produk_ids)) {
            $this->db->where_in("produk_id", $produk_ids);
            // $this->db->where_in("price_per_supplier.produk_id", $produk_ids);
        }
        elseif ($produk_ids > 0) {
            $this->db->where("produk_id", $produk_ids);
            // $this->db->where("price_per_supplier.produk_id", $produk_ids);
        }
        else {
            // tanpa condite
        }
        // $this->db->select("*,");
        $condites = array(
            "trash" => 0,
            "status" => 1,
            "jenis" => "produk",
        );
        $this->db->where($condites);
        // $condites_dua = "per_supplier.id = produk_per_supplier.suppliers_id";
        // $this->db->join("per_supplier", $condites_dua);

        // $condites_tiga = "per_supplier.id = price_per_supplier.suppliers_id and price_per_supplier.jenis_value='hpp'";
        // $this->db->join("price_per_supplier", $condites_tiga);
        $this->db->order_by('dtime', 'asc');
        $vals = $query = $this->db->get($this->tableName)->result();
        // showLast_query("orange");
        // arrPrint($vals);
        $val = array();
        foreach ($vals as $item) {
            // if ($item->suppliers_id > 0) {
            foreach ($showKoloms as $srcKolom => $outKolom) {

                $datas[$outKolom] = $item->$srcKolom;
            }
            // $datas['suppliers_nama'] = $item->nama;
            // $datas['suppliers_kode'] = $item->suppliers_kode;
            $val[$item->produk_id][$item->cabang_id][$item->jenis_value] = $datas;
            // }
        }

        return $val;
    }
}