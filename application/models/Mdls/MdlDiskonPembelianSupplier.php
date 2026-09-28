<?php

class MdlDiskonPembelianSupplier extends MdlMother
{
    protected $tableName = "diskon_pembelian_supplier";
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
        "produk_id"   => array(
            "label"     => "produk_id",
            "type"      => "int",
            "length"    => "255", "kolom" => "produk_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "per_supplier_diskon_id"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "per_supplier_diskon_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "per_supplier_diskon_nama"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "per_supplier_diskon_id",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "jenis"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "jenis",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "persen"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "persen",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "nilai"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "nilai",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "minim"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "minim",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "maxim"   => array(
            "label"     => "maxim",
            "type"      => "int",
            "length"    => "255", "kolom" => "maxim",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
//            "strField"  => "nama",
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
        $selecteds = array(
            "id",
            "jenis",
            "nama",
            "persen",
            "nilai",
            "supplier_id",
            "produk_id",
            // "barcode",
            // "no_part",
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
            $this->db->where_in("id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("id", $produkIds);
            }
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    public function callRebate($produkIds = "",$supplierID=""){
        /*
        * sebelumnya 2/8/2024 ditemukan keyyg digunakan adalah id
        *
        * */
        $selecteds = array(
            "id",
            //            "jenis",
            "per_supplier_diskon_id",
            "per_supplier_diskon_nama",
            "supplier_id",
            "produk_id",
            "jenis",
            "persen",
            "nilai",
            "minim",
            "maxim",
            "jenis",

        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
//        if (is_array($produkIds)) {
//            $this->db->where_in("produk_id", $produkIds);
//        }
//        else {
//            if ($produkIds > 0) {
//                $this->db->where("produk_id", $produkIds);
//            }
//        }
        $jenis_diskon = array(
            "absolut",
//            "kelompok",
        );
        if($supplierID >0){
            $this->db->where("supplier_id","$supplierID");
        }
        $this->db->where("status","1");
        $this->db->where("trash","0");
        $this->db->where_in("jenis", $jenis_diskon);
        $vars_0 = $this->lookupAll()->result();
//        showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $speks_diskon = $item;
//            ksort($speks_diskon);
            $vars[] = $speks_diskon;
        }
        $rebateKelompok = $this->callRebateKelompok($supplierID)->result();
//        arrPrint($rebateKelompok);
//        matiHere();
        if(count($rebateKelompok)>0){
            foreach ($rebateKelompok as $rebateKelompok_0){
                $vars[] = $rebateKelompok_0;
            }
        }




//        arrPrintWebs($vars);
//matiHere(__LINE__);
        return $vars;
    }

    public function callRebateKelompok($supplierID){

        $this->db->select("*,diskon_kelompok.jenis as jn, diskon_pembelian_supplier.jenis as jenis,diskon_kelompok.persen as persen,diskon_kelompok.nilai as nilai,diskon_kelompok.minim as minim,diskon_kelompok.maxim as maxim");
        $this->db->where("diskon_pembelian_supplier.supplier_id='$supplierID'");
        $this->db->where("diskon_pembelian_supplier.jenis='kelompok'");
        $this->db->where("diskon_pembelian_supplier.status='1'");
        $this->db->where("diskon_pembelian_supplier.trash='0'");
        $this->db->join("diskon_kelompok", "diskon_kelompok.id = " . $this->tableName . ".kelompok_id");
        $vars = $this->db->get($this->tableName);
//        cekLime($this->db->last_query());
        return $vars;
//        arrprint($vars);
//        matiHere();
    }

    public function saveRebate($datas)
    {
        $table_name = $this->tableName;
        // $condites = $this->filters['grosir'];
        $condites = array(
            "supplier_id" => $datas["supplier_id"],
            "maxim"     => $datas["maxim"],
            // "persen"    => $datas["persen"],
            "trash"     => 0,
            "status"    => 1,
        );
        $this->db->where($condites);
        $srcs = $this->db->get($table_name)->result();
        showLast_query("hijau");

        if (sizeof($srcs) == 0) {
            // $data_new = array(
            //         "jenis" => "produk_grosir"
            //     ) + $datas;
            //
            // $this->db->insert($table_name, $data_new);
        }
        else {
            cekBiru("update grosir");
            $datas_upd = array(
                "trash"        => 1,
                "trash_dtime"  => dtimeNow(),
                "trash_author" => my_id(),
            );
            $wheres = array(
                "supplier_id" => $datas["supplier_id"],
                "jenis" => $datas["jenis"],
                // "minim"     => $datas["minim"],
                // "persen"     => $datas["persen"],
            );
            $this->db->where($wheres);
            $this->db->update($this->tableName, $datas_upd);

            // $this->deleteProdukGrosir($datas["produk_id"]);
            showLast_query("merah");
        }

        $data_new = array(
                "jenis" => $datas["jenis"],
            ) + $datas;

        $this->db->insert($table_name, $data_new);
//cekMErah($this->db->last_query());

        // $this->updateData($condites, $data_upd);
    }

    public function deleteRebate($id_data)
    {
//        $this->tableName = $table_name = $this->tableNames['grosir'];
        $datas = array(
            "trash"        => 1,
            "trash_dtime"  => dtimeNow(),
            "trash_author" => my_id(),
        );
        $wheres = array(
            "id"    => $id_data,
            "trash" => 0,
        );
        $this->db->where($wheres);
        $this->db->update($this->tableName, $datas);

        return 1;
    }

    protected $innerJoint = array(
        "tabel_1" => array(
            "tbl"    => "diskon_kelompok", // slave
            "select" => array(
                "*","jenis as jn"
            ),
            "where_slave"  => array(
                "trash" => 0,
            ),
            "on"     => array(
                "kelompok_id" => "id",
            )
        ),
    );
}