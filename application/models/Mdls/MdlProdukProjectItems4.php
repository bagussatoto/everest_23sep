<?php

//--include_once "MdlHistoriData.php";
class MdlProdukProjectItems4 extends MdlMother
{
    protected $tableName = "project_produk_items4";
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
        "transaksi_no" => "nomer order",
        "customer_nama" => "konsumen",
        "npwp" => "npwp",
        "nama" => "nama projek",
        "spek" => "spesifikasi",
        "keterangan" => "keterangan",
        "nomor_kontrak" => "No Kontrak",
        "harga" => "nilai projek(tanpa pajak)",
        // "start_dtime" => "mulai pengerjaan",
        "end_dtime" => "Tenggat waktu",
        "lock" => "project status",
        "project_start" => "project start",
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

}