<?php

class MdlDiskonKelompok extends MdlMother
{
    protected $tableName = "diskon_kelompok";
    protected $tableNameLog = "diskon_kelompok_log";
    protected $indexFields = "id";
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        // "untuk='diskon_jual'",
        // "jenis='diskon'",
        // "status='1'",
        // "trash='0'"
    );
    protected $ciFilters = array(
        // "jenis" => "bank",
        "status" => "1",
        "trash"  => "0",
    );
    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "supplier_id" => array("required", "singleOnly"),
    );
    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"      => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "jenis"   => array(
            "label"        => "jenis",
            "type"         => "varchar",
            "length"       => "255",
            "kolom"        => "jenis",
            "inputType"    => "hidden",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
            "defaultValue" => "kelompok",
        ),
        "nama" => array(
            "label"     => "nama kelompok",
            "type"      => "varchar",
            "length"    => "50",
            "kolom"     => "nama",
            "inputType" => "text",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "supplier_id"  => array(
            "label"      => "supplier",
            "type"       => "int",
            "length"     => "11",
            "kolom"      => "supplier_id",
            "inputType"  => "combo",
            "reference"  => "MdlSupplier",
            "strField"   => "nama",
            "kolom_nama" => "supplier_nama",
            "defaultValue" => "supplier_id",
            "editable"  => false,
        ),
        "status"  => array(
            "label"        => "status",
            "type"         => "int",
            "length"       => "24",
            "kolom"        => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
//            "editable"  => false,
            //--"inputName" => "status",
        ),
    );
    protected $listedFields = array(
        "nama" => "name",
        "supplier_id" => "supplier",
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

    public function getTableNameLog()
    {
        return $this->tableNameLog;
    }

    public function setTableNameLog($tableNameLog)
    {
        $this->tableNameLog = $tableNameLog;
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

    public function callRebate($produkIds = ""){
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
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            if ($produkIds > 0) {
                $this->db->where("produk_id", $produkIds);
            }
        }
        $jenis_diskon = array(
            "khusus",
            "khusus_abs",
        );
        $this->db->where_in("jenis", $jenis_diskon);
        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        // arrPrintHijau($vars_0);
        $vars = array();
        foreach ($vars_0 as $item) {
            $speks_diskon[$item->per_supplier_diskon_id] = $item;
            ksort($speks_diskon);
            $vars[$item->produk_id] = $speks_diskon;
        }


        return $vars;
    }

    public function saveRebate($datas)
    {
        $table_name = $this->tableName;
        $table_Log = $this->tableNameLog;

        $condites = array(
            "id" => $datas["kelompok_id"],
            "trash"     => 0,
            "status"    => 1,
        );
        $this->db->where($condites);
        $srcs = $this->db->get($table_name)->result();
        showLast_query("hijau");

        if (count($srcs)==0) {
            // $data_new = array(
            //         "jenis" => "produk_grosir"
            //     ) + $datas;
            //
            // $this->db->insert($table_name, $data_new);
        }
        else {

            $datas_log = array();
            foreach($srcs[0] as $ky => $val){
                $datas_log[$ky] = $val;
            }
            unset($datas_log["id"]);

            $datas_log["trash"] = 1;
            $datas_log["trash_dtime"]  = dtimeNow();
            $datas_log["trash_author"] = my_id();
            $datas_log["kelompok_id"] = $srcs[0]->id;
            $datas_log["jenis"] = $datas['jenis'];

            $this->db->insert($table_Log, $datas_log);
            showLast_query("hijau");

            $datas_upd = array(
                "maxim" => $datas['maxim'],
                "persen" => $datas['persen'],
                "nilai" => $datas['nilai'],
                "last_update" => date("Y-m-d H:i:s"),
            );
            $wheres = array(
                "id" => $srcs[0]->id,
            );
            $this->db->where($wheres);
            $this->db->update($this->tableName, $datas_upd);

            showLast_query("merah");
        }

    }

    /*
     * KELOMPOK UTAMA
     */
    public function deleteRebate($id_data)
    {
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
        $sungses = $this->db->update($this->tableName, $datas);
        return $sungses;
    }

    public function renameRebate($post)
    {
        $datas = array(
            "nama"        => $post["new_nama"],
        );
        $wheres = array(
            "id"    => $post['id'],
            "trash" => 0,
        );
        $this->db->where($wheres);
        $sungses = $this->db->update($this->tableName, $datas);
        return $sungses;
    }


    /*
     * DELETE PRODUK RELASI KELOMPOK
     */
    public function deleteProdukKelompok($post)
    {
        $datas = array(
            "trash"        => 1,
            "trash_dtime"  => dtimeNow(),
            "trash_author" => my_id(),
        );
        $wheres = array(
            "id"    => $post,
            "trash" => 0,
        );
        $this->db->where($wheres);
        $sungses = $this->db->update("diskon_pembelian_supplier", $datas);
        return $sungses;
    }

}