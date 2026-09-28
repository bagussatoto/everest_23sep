<?php

class MdlTasklistProject extends MdlMother
{
    protected $tableName = "project_tasklist";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $listedFieldsSelectItem = array(
        //===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "no_spk" => "no_spk",
        "no_pre_spk" => "no_pre_spk",
        "nama" => "nama",
        "nomer" => "nomer",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("nama");

    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "produk_id" => array(
            "label" => "project",
            "type" => "int", "length" => "255", "kolom" => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProdukProject",
            "defaultValue" => "",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "produk_nama",
        ),

        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            "strField" => "nama",
            "editable" => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "input_property" => array(
            "label" => "tipe",
            "type" => "int", "length" => "255", "kolom" => "input_property",
            "inputType" => "combo",
            "reference" => "MdlInputProperty",
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
        "dtime_start" => "mulai",
        "dtime_end" => "tenggat",
//        "nama" => "Project",
        "owner_nama" => "Pembeli/<br>Pemilik Rumah/<br>Gedung",
        "produk_nama" => "tugas",
        "produk_paket_nama" => "PAKET",
        "employee_nama" => "pelaksana",
        "nilai" => "keterangan",
        "progress_nama" => "status",
        "progress_percent" => "progress",
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

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlEmployee_all" => array(
                "id" => "employee_id",    // kolom_src => kolom_target (berisi id src)
                "kolomDatas" => array(
                    "nama" => "employee_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
            "MdlProdukProject" => array(
                "id" => "produk_id",    // kolom_src => kolom_target (berisi id src)
                "kolomDatas" => array(
                    "nama" => "produk_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
            "MdlProgresTasklist" => array(
                "id" => "progress_id",    // kolom_src => kolom_target (berisi id src)
                "kolomDatas" => array(
                    "nama" => "progress_nama",       // kolom_data => kolom_target (berisi nama)
                ),
            ),
        );

        return $mdls;

    }

    public function lookupJoin_($tbl_1, $tbl_2)
    {

        $this->db->select('
            cp.*,
            cp.propinsi,
            cp.kabupaten,
            pc.propinsi,
            pc.kabupaten,
            pc.kecamatan,
            pc.kelurahan,           
        ');
        $this->db->from("$tbl_1 cp");
        $this->db->join("$tbl_2 pc",
            'cp.propinsi = pc.propinsi_id AND cp.kabupaten = pc.kabupaten_id AND cp.kecamatan = pc.kecamatan_id AND cp.kelurahan = pc.kelurahan_id',
            'inner');
        // $this->db->where('cp.id', $company_id);

        $query = $this->db->get();
        return $query->row(); // M
    }

    public function lookupJoin__($tbl_1, $tbl_2)
    {
//        $alias_1 = 'p';
//        $alias_2 = 's';

        $produkCols = generateAliasedColumns($tbl_1, "tbl_1", "tbl_1", $this->db);
        $supplierCols = generateAliasedColumns($tbl_2, "tbl_2", "tbl_2", $this->db);
//        arrprint($produkCols);
//        matiHere(__LINE__);
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
//            $this->db->where($criteria2);
        }
//        $this->db->where($criteria);
        $this->db->select("$produkCols, $supplierCols");
        $this->db->from("$tbl_1 AS tbl_1");
        $this->db->join("$tbl_2 AS tbl_2", "tbl_1.no_spk = tbl_2.no_spk", 'inner');
        $this->db->order_by("tbl_1.id", "asc");

        return $this->db->get();

//        return $query->row(); // bisa diganti ->result() jika ingin semua baris
    }

    public function lookupJoin($tbl_1, $tbl_2)
    {
        $subquery = $this->db
            ->select('*')
            ->from('project_tasklist')
            ->where(array("progress_id" => "3", "post_return_id>" => "0", "post_return_cli" => "0", "trash" => "0"))
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get_compiled_select();

        $this->db->select('*');
        $this->db->from("($subquery) AS p"); // <-- penting: tambahkan alias
        $this->db->join('project_sub_tasklist_komposisi AS s', 'p.no_spk = s.no_spk', 'inner');
        $this->db->where(array("s.cli" => "0", "s.jenis" => "produk", "s.qty_saldo>" => "0"));
// $this->db->where('s.jenis', 'produk'); // tinggal aktifkan kalau perlu

        return $this->db->get();

//        return $query->row(); // bisa diganti ->result() jika ingin semua baris
    }

    public function lookupJoinSupplies($tbl_1, $tbl_2)
    {
        $subquery = $this->db
            ->select('*')
            ->from('project_tasklist')
            ->where(
                array(
                    "progress_id" => "3",
                    "post_return_id>" => "0",
                    "post_return_cli" => "1",
                    "trash" => "0"
//                    "no_spk" => "046/SPK-INT/979/046/X/2024"
                )
            )
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get_compiled_select();

        $this->db->select('*');
        $this->db->from("($subquery) AS p"); // <-- penting: tambahkan alias
        $this->db->join('project_sub_tasklist_komposisi AS s', 'p.no_spk = s.no_spk', 'inner');
        $this->db->where(array("s.cli" => "0", "s.jenis" => "supplies", "s.qty_saldo>" => "0"));
// $this->db->where('s.jenis', 'produk'); // tinggal aktifkan kalau perlu

        return $this->db->get();

//        return $query->row(); // bisa diganti ->result() jika ingin semua baris
    }

}