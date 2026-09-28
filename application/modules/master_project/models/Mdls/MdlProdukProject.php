<?php

//--include_once "MdlHistoriData.php";
class MdlProdukProject extends MdlMother
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

    protected $validateData = array("customer_id", "spek");

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

    public function getRekapKomposisi($project_id)
    {
        // total_digunakan: dari project_komposisi_sub_workoder (ditulis saat SPK diterbitkan)
        // total_return: dari project_sub_tasklist_komposisi (ditulis saat pelaksana menyelesaikan SPK)
        $sql = "
            SELECT 
              k.produk_dasar_id,
              k.harga,
              k.debet,
              MAX(k.produk_dasar_nama) AS produk_dasar_nama,
              k.jenis,
              MAX(k.jml) AS total_komposisi,
                (MAX(k.jml) * MAX(k.harga)) AS total_komposisi_rp,
              IFNULL(SUM(p.jml), 0) AS total_digunakan,
              IFNULL(SUM(p.jml)* MAX(k.harga), 0) AS total_digunakan_rp,
              IFNULL(MAX(ret.sum_return), 0) AS total_return,
              IFNULL(MAX(ret.sum_return) * MAX(k.harga), 0) AS total_return_rp,
              (MAX(k.jml) - IFNULL(SUM(p.jml), 0)) AS sisa,
              ((MAX(k.jml) - IFNULL(SUM(p.jml), 0)) * MAX(k.harga)) AS sisa_rp

            FROM project_komposisi_workoder k

            LEFT JOIN project_komposisi_sub_workoder p
              ON p.produk_id       = k.produk_id
              AND p.produk_dasar_id = k.produk_dasar_id
              AND (p.jenis = k.jenis OR (k.jenis = 'produk' AND p.jenis IN ('item_komposit', 'supplies')))
              AND p.status           = 1
              AND p.trash           = 0
              AND p.jenis_transaksi = 'sub_wo'

            LEFT JOIN (
                SELECT produk_id, produk_dasar_id, SUM(jml_return) AS sum_return
                FROM project_sub_tasklist_komposisi
                WHERE status = 1
                GROUP BY produk_id, produk_dasar_id
            ) ret ON ret.produk_id = k.produk_id
              AND ret.produk_dasar_id = k.produk_dasar_id

            WHERE k.produk_id = ?
              AND k.status = 1
              AND k.trash = 0
              AND k.fase_id > 0
              AND k.jenis IN ('biaya','produk')
            GROUP BY k.produk_dasar_id, k.jenis
            ORDER BY k.jenis desc,k.qty_saldo asc
        ";
        return $this->db->query($sql, array($project_id))->result();
    }

    // START OF COMPLETE REPEATED LOGIC
    public function checkDuplicateProject($cabangId, $customerId, $spek, $excludeId = 0)
    {
        $sql = "SELECT id, nama, spek FROM project_produk 
                WHERE cabang_id = ? 
                  AND customer_id = ? 
                  AND spek = ? 
                  AND id != ? 
                  AND status = 1 
                  AND trash = 0 
                LIMIT 1";
        return $this->db->query($sql, array($cabangId, $customerId, $spek, $excludeId))->row();
    }
    // END OF COMPLETE REPEATED LOGIC

}