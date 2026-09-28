<?php

class MdlDiskonPembelian extends MdlMother
{
    protected $tableName = "diskon_pembelian";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        // "nama" => array("required", "singleOnly", "unique"),


    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "status" => array(
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
        /*
         * sebelumnya 2/8/2024 ditemukan keyyg digunakan adalah id
         *
         * */
        $selecteds = array(
            "id",
//            "jenis",
            "per_supplier_diskon_id",
            "per_supplier_diskon_nama",
            "persen",
            "nilai",
            "supplier_id",
            "produk_id",
            "status",
            "trash",
            // // "merek_nama",
            // // "model_nama",
            // // "type_nama",
            // // "tahun",
            // // "lokasi_nama",
            // "satuan",
            // "jml_serial",
            // "diskon_persen",
            // "premi_jual",
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
        // arrPrintHijau($vars_0);

        $vars = array();
        foreach ($vars_0 as $item) {
            $speks_diskon[$item->per_supplier_diskon_id] = $item;
            ksort($speks_diskon);

            // $vars[$item->produk_id] = $speks_diskon;
            $vars[$item->produk_id][$item->per_supplier_diskon_id] = $item;
        }


        return $vars;
    }

    public function callRebate($produkIds = "", $supplier_id = "")
    {
        /* ----------------------------------------------------------
        * sebelumnya 2/8/2024 ditemukan keyyg digunakan adalah id
        * ----------------------------------------------------------*/
        $selecteds = array(
            "id",
            "per_supplier_diskon_id",
            "per_supplier_diskon_nama",
            "persen",
            "nilai",
            "supplier_id",
            "produk_id",
            "minim",
            "maxim",
            "jenis",
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
        $jenis_diskon = array(
            "khusus",
//            "khusus_abs",
        );
        $this->db->where_in("jenis", $jenis_diskon);
        if ($supplier_id > 0) {
            $this->db->where("supplier_id", $supplier_id);
        }
        // $this->db->where("persen >", "0");
        $vars_0 = $this->lookupAll()->result_array();
        // showLast_query("orange");
        // arrPrintHijau($vars_0);
        $vars = array();
        foreach ($vars_0 as $item) {
            $speks_diskon[$item["per_supplier_diskon_id"]] = $item;
            ksort($speks_diskon);

            if ($supplier_id > 0) {
                $vars[$item["produk_id"]][] = $item;
                $varJenis["unit"][$item["produk_id"]][] = $item;
            }
            else {

                $vars[$item["supplier_id"]][$item["produk_id"]][] = $item;
                $varJenis[$item["supplier_id"]]["unit"][$item["produk_id"]][] = $item;
            }
        }

        /* ----------------------------------------
         * nyebrang ke tabel lain :>
         * ----------------------------------------*/
        $this->load->model("Mdls/MdlDiskonPembelianSupplier");
        $ds = new MdlDiskonPembelianSupplier();

        if ($supplier_id > 0) {
            $condites = array(
                "supplier_id" => $supplier_id,
                "status" => 1,
                "trash" => 0,
            );
            $this->db->where($condites);
//            matiHEre(__LINE__);
        }
        else{
//            matiHere($supplier_id);
        }
        $src_2 = $ds->callRebate("", $supplier_id);
//        cekLime($this->db->last_query());
        foreach ($src_2 as $x => $item) {
//            arrPrint($item);
//            matiHere();
            $jk = $item->jenis;
            if ($jk == "kelompok") {
                if (in_array($item->produk_id, $produkIds)) {
            if ($supplier_id > 0) {
                        $varJenis[$jk][$item->produk_id][] = (array)$item;
                    } else {
                        $varJenis[$item->supplier_id][$jk][] = (array)$item;
                    }
                }
            }
            else{
                $varJenis[$jk][] = (array)$item;
            }


        }
        // arrPrintPink($src_2);


        $array = array();
        $array["row"] = $vars;
        $array["jenis"] = $varJenis;

        return $array;
    }

}