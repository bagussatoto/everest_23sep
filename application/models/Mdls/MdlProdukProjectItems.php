<?php

//--include_once "MdlHistoriData.php";
class MdlProdukProjectItems extends MdlMother
{
    protected $tableName = "project_produk";
    protected $indexFields = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
        "kategori" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "kategori" => array(
            "label" => "Konsumen",
            "type" => "int", "length" => "255", "kolom" => "customer_id",
            "inputType" => "combo",
            "reference" => "MdlCustomer_and_pre",
            "strField" => "nama",
            "editable" => false,
            "kolom_nama" => "customer_nama",
        ),
        //tambah npwp dini
        "kode" => array(
            "label" => "kode",
            "type" => "varchar", "length" => "100", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "nama" => array(
            "label" => "nama projek",
            "type" => "int", "length" => "100", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "nomor_kontrak" => array(
            "label" => "No Kontrak",
            "type" => "int", "length" => "100", "kolom" => "nomor_kontrak",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "spek" => array(
            "label" => "spesifikasi",
            "type" => "text", "length" => "5", "kolom" => "spek",
            "inputType" => "textarea",
        ),
        "harga" => array(
            "label" => "harga",
            "type" => "text", "length" => "5", "kolom" => "harga",
            "inputType" => "textarea",
        ),
        "keterangan" => array(
            "label" => "catatan lain-lain",
            "type" => "text", "length" => "5", "kolom" => "keterangan",
            "inputType" => "textarea",
            //--"inputName" => "",
        ),
        "alamat" => array(
            "label" => "lokasi projek",
            "type" => "int", "length" => "5", "kolom" => "alamat",
            "inputType" => "textarea",
            //--"inputName" => "",
        ),
        "start_dtime" => array(
            "label" => "mulai pengerjaan",
            "type" => "date", "length" => "100", "kolom" => "startdtime",
            "inputType" => "date",
            //--"inputName" => "",
        ),
        "end_dtime" => array(
            "label" => "tenggat",
            "type" => "date", "length" => "100", "kolom" => "end_dtime",
            "inputType" => "date",
            //--"inputName" => "",
        ),
        "garansi" => array(
            "label" => "garansi (%)",
            "type" => "int", "length" => "24", "kolom" => "garansi",
            "inputType" => "number",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
            //--"inputName" => "status",
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
        "transaksi_id" => "trid",
        "cabang_nama" => "cabang",
        "transaksi_no" => "nomer<br>order",
        "customer_nama" => "konsumen",
        "dtime" => "create",
//        "npwp" => "npwp",
        "nama" => "nama projek",
        "spek" => "spesifikasi",
        "keterangan" => "keterangan",
        "nomor_kontrak" => "No Kontrak",
        "harga" => "nilai projek<br>(tanpa pajak)",
        // "start_dtime" => "mulai pengerjaan",
        "end_dtime" => "Tenggat<br>waktu",
        "lock" => "project<br>status",
        "project_start" => "project<br>start",
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

    public function paramSyncNamaNama()
    {
        $mdls = array(
            // "MdlSatuan" => array(
            //     "id"         => "satuan_id",    // kolom_src => kolom_target (berisi id src)
            //     // "str" => "folders_nama",
            //     "kolomDatas" => array(
            //         "satuan" => "satuan",       // kolom_data => kolom_target (berisi nama)
            //     ),
            // ),
            "MdlCustomer_and_pre" => array(
                "id" => "customer_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "customer_nama",
                ),
            ),
//            "MdlProdukKategori" => array(
//                "id"         => "kategori_id",
//                // "str" => "merek_nama",
//                "kolomDatas" => array(
//                    "nama" => "kategori_nama",
//                ),
//            ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kendaraan_nama",
            //     ),
            // ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
        );

        return $mdls;

    }

    public function fectDataProject()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            //            arrPrint($this->filters);
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
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);

        }

        $res = $this->db->get($this->tableName);

        return $res;


    }

    public function pairMember($prID)
    {


    }

    public function pairBomData()
    {
    }

//    public function lookupAll()
//    {
//        $criteria  = [];
//        $criteria2 = "";
//
//        if (!empty($this->filters)) {
//            $this->fetchCriteria();
//            $criteria  = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
//        }
//
//        // SELECT kolom utama + kolom dari tabel join
//        $this->db->select($this->tableName . '.*');
//        $this->db->select('p3.harga_termin1, p3.harga_termin2, p3.harga_termin3, p3.harga_termin4, p3.harga_termin5,
//                       p3.harga_termin6, p3.harga_termin7, p3.harga_termin8, p3.harga_termin9, p3.harga_termin10');
//        $this->db->select('p4.harga_uang_muka');
//        $this->db->select('p5.harga_retensi');
//
//        // LEFT JOIN ke 3 tabel
//        // Asumsi: fk = item_id pada tabel 3/4/5 → pk = id pada tabel utama
//        $this->db->from($this->tableName);
//        $this->db->join('project_produk_items3 AS p3', 'p3.item_id = ' . $this->tableName . '.id', 'left');
//        $this->db->join('project_produk_items4 AS p4', 'p4.item_id = ' . $this->tableName . '.id', 'left');
//        $this->db->join('project_produk_items5 AS p5', 'p5.item_id = ' . $this->tableName . '.id', 'left');
//
//        if (!empty($criteria))  $this->db->where($criteria);
//        if ($criteria2 !== "")  $this->db->where($criteria2);
//
//        if (isset($this->sortBy) && !empty($this->sortBy)) {
//            $this->db->order_by($this->tableName . '.' . $this->sortBy['kolom'], $this->sortBy['mode']);
//        }
//
//        $res = $this->db->get(); // from() sudah dipanggil di atas
//        return $res;
//    }

    public function lookupAll()
    {
        $criteria  = [];
        $criteria2 = "";

        if (!empty($this->filters)) {
            $this->fetchCriteria();
            $criteria  = $this->getCriteria();   // asumsi: array kolom dasar (pakai alias p)
            $criteria2 = $this->getCriteria2();  // asumsi: string bebas (SQL)
        }

        // --- Subquery: ranking termin per transaksi_id berdasar id ASC (tanpa window function) ---
        $ranked = "
          SELECT 
            s.transaksi_id,
            s.jumlah,
            CASE WHEN s.transaksi_id = @cur THEN @rn := @rn + 1 ELSE @rn := 1 END AS rn,
            @cur := s.transaksi_id AS _
          FROM (
            SELECT i3.transaksi_id, i3.id, i3.jumlah
            FROM project_produk_items3 i3
            ORDER BY i3.transaksi_id, i3.id
          ) AS s
          CROSS JOIN (SELECT @cur := NULL, @rn := 0) AS vars
          ORDER BY s.transaksi_id, s.id
        ";

        // --- Pivot termin 1..10 (bruto/termasuk PPN) ---
        $pivot3 = "
          SELECT
            transaksi_id,
            MAX(CASE WHEN rn=1  THEN jumlah END) AS termin1_rp,
            MAX(CASE WHEN rn=2  THEN jumlah END) AS termin2_rp,
            MAX(CASE WHEN rn=3  THEN jumlah END) AS termin3_rp,
            MAX(CASE WHEN rn=4  THEN jumlah END) AS termin4_rp,
            MAX(CASE WHEN rn=5  THEN jumlah END) AS termin5_rp,
            MAX(CASE WHEN rn=6  THEN jumlah END) AS termin6_rp,
            MAX(CASE WHEN rn=7  THEN jumlah END) AS termin7_rp,
            MAX(CASE WHEN rn=8  THEN jumlah END) AS termin8_rp,
            MAX(CASE WHEN rn=9  THEN jumlah END) AS termin9_rp,
            MAX(CASE WHEN rn=10 THEN jumlah END) AS termin10_rp
          FROM ($ranked) r
          GROUP BY transaksi_id
        ";

        // --- Uang muka & Retensi (bruto) ---
        $subUM = "SELECT transaksi_id, SUM(jumlah) AS uangmuka_rp FROM project_produk_items4 GROUP BY transaksi_id";
        $subRT = "SELECT transaksi_id, SUM(jumlah) AS retensi_rp  FROM project_produk_items5 GROUP BY transaksi_id";

        // SELECT kolom (nilai_project terlebih dahulu, lalu UM/Retensi/Termin dalam Rp dan Persen)
        $this->db->select("
            p.*, 
            p.harga AS nilai_project,
    
            um.uangmuka_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0) THEN NULL
                 ELSE (um.uangmuka_rp / (p.harga * 1.11)) * 100 END AS uangmuka_persen,
    
            rt.retensi_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0) THEN NULL
                 ELSE (rt.retensi_rp / (p.harga * 1.11)) * 100 END AS retensi_persen,
    
            p3.termin1_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin1_rp IS NULL) THEN NULL
                 ELSE (p3.termin1_rp / (p.harga * 1.11)) * 100 END AS termin1_persen,
    
            p3.termin2_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin2_rp IS NULL) THEN NULL
                 ELSE (p3.termin2_rp / (p.harga * 1.11)) * 100 END AS termin2_persen,
    
            p3.termin3_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin3_rp IS NULL) THEN NULL
                 ELSE (p3.termin3_rp / (p.harga * 1.11)) * 100 END AS termin3_persen,
    
            p3.termin4_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin4_rp IS NULL) THEN NULL
                 ELSE (p3.termin4_rp / (p.harga * 1.11)) * 100 END AS termin4_persen,
    
            p3.termin5_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin5_rp IS NULL) THEN NULL
                 ELSE (p3.termin5_rp / (p.harga * 1.11)) * 100 END AS termin5_persen,
    
            p3.termin6_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin6_rp IS NULL) THEN NULL
                 ELSE (p3.termin6_rp / (p.harga * 1.11)) * 100 END AS termin6_persen,
    
            p3.termin7_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin7_rp IS NULL) THEN NULL
                 ELSE (p3.termin7_rp / (p.harga * 1.11)) * 100 END AS termin7_persen,
    
            p3.termin8_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin8_rp IS NULL) THEN NULL
                 ELSE (p3.termin8_rp / (p.harga * 1.11)) * 100 END AS termin8_persen,
    
            p3.termin9_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin9_rp IS NULL) THEN NULL
                 ELSE (p3.termin9_rp / (p.harga * 1.11)) * 100 END AS termin9_persen,
    
            p3.termin10_rp,
            CASE WHEN (p.harga IS NULL OR p.harga = 0 OR p3.termin10_rp IS NULL) THEN NULL
                 ELSE (p3.termin10_rp / (p.harga * 1.11)) * 100 END AS termin10_persen
        ", false);

        $this->db->from('project_produk p');
        $this->db->where("p.quot_id>0"); //keluarkan hanya project yang sudah approve
        $this->db->join("($subUM) um", 'um.transaksi_id = p.transaksi_id', 'left', false);
        $this->db->join("($subRT) rt", 'rt.transaksi_id = p.transaksi_id', 'left', false);
        $this->db->join("($pivot3) p3", 'p3.transaksi_id = p.transaksi_id', 'left', false);

        if (!empty($criteria))  $this->db->where($criteria);   // pastikan key kolom pakai alias p.***
        if ($criteria2 !== "")  $this->db->where($criteria2);

        if (isset($this->sortBy) && !empty($this->sortBy)) {
            $this->db->order_by('p.' . $this->sortBy['kolom'], $this->sortBy['mode']);
        }

        $res = $this->db->get();
//        $res = $this->db->get()->result();
//        arrPrint( $res->result() );
//        showLast_query("hijau");
//        matiHere(__LINE__);

        return $res;
    }

}