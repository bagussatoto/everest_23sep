<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * [AGENT_LOG]
 * ROLE      : Lead Architect Agent & Software Engineer Agent
 * PURPOSE   : Model metadata-driven relasi antara produk dan rak penyimpanan dengan validasi kapasitas dan keunikan relasi.
 * COMPLIANCE: Standar ISO 9001 (Manajemen Mutu Gudang), PSAK 14 (Persediaan), & MdlMother Metadata.
 * LOG_EXPIRE: 2026-11-13
 * [/AGENT_LOG]
 */
class MdlProdukRakRelasi extends MdlMother
{
    protected $tableName = "produk_rak_relasi";
    protected $indexFields = "id";
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "produk_id" => array("required"),
        "rak_id"    => array("required"),
    );

    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",
        ),
        "produk" => array(
            "label"      => "Produk",
            "type"       => "int", "length" => "24", "kolom" => "produk_id",
            "inputType"  => "combo",
            "reference"  => "MdlProduk",
            "strField"   => "nama",
            "kolom_nama" => "produk_nama",
            "editable"   => true,
        ),
        "produk_nama" => array(
            "label"     => "Produk",
            "type"      => "varchar", "length" => "255", "kolom" => "produk_nama",
            "inputType" => "hidden",
        ),
        "rak" => array(
            "label"      => "Rak",
            "type"       => "int", "length" => "24", "kolom" => "rak_id",
            "inputType"  => "combo",
            "reference"  => "MdlGudangRak",
            "strField"   => "nama",
            "kolom_nama" => "rak_nama",
            "editable"   => true,
        ),
        "rak_nama" => array(
            "label"     => "Rak",
            "type"      => "varchar", "length" => "255", "kolom" => "rak_nama",
            "inputType" => "hidden",
        ),
        "kapasitas_maks" => array(
            "label"        => "Kapasitas Maksimal",
            "type"         => "int", "length" => "11", "kolom" => "kapasitas_maks",
            "inputType"    => "number",
            "defaultValue" => 1,
        ),
    );

    protected $listedFields = array(
        "produk_nama"    => "nama produk",
        "rak_nama"       => "posisi rak",
        "kapasitas_maks" => "kapasitas maksimal",
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
        return array(
            "MdlProduk" => array(
                "id"         => "produk_id",
                "kolomDatas" => array(
                    "nama" => "produk_nama",
                ),
            ),
            "MdlGudangRak" => array(
                "id"         => "rak_id",
                "kolomDatas" => array(
                    "nama" => "rak_nama",
                ),
            ),
        );
    }

    /**
     * Memeriksa apakah kombinasi produk_id dan rak_id sudah terdaftar (mencegah duplikasi).
     */
    public function cekDuplikatRelasi($produk_id, $rak_id, $id = 0)
    {
        $this->db->where('produk_id', $produk_id);
        $this->db->where('rak_id', $rak_id);
        $this->db->where('trash', '0');
        if ($id > 0) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get($this->tableName);
        return ($query->num_rows() > 0);
    }

    /**
     * Mengarahkan tombol Add Data (+) ke halaman kustom dedicated.
     */
    public function linkAddData()
    {
        return "Data/addProdukRakRelasi/" . substr(get_class(), 3);
    }
}
// END OF COMPLETE REPEATED LOGIC
