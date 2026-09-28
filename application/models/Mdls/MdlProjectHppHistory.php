<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');

class MdlProjectHppHistory extends MdlMother
{
    protected $tableName = "project_hpp_history";
    protected $indexFields = "id";
    protected $indexFielddasar_s = "id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(
        "produk_nama"  => "project_hpp_history.produk_nama",
        "produk_kode"  => "project_hpp_history.produk_kode",
        "project_nama" => "project_hpp_history.project_nama",
    );
    protected $search;
    protected $filters = array();
    protected $sortBy = array(
        "kolom" => "id",
        "mode"  => "DESC",
    );
    protected $validationRules = array(
        "project_id" => array("required"),
        "produk_id"  => array("required"),
    );

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    protected $listedFieldsView = array("produk_nama");
    protected $fields = array(
        "id" => array(
            "label"     => "id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "id",
            "inputType" => "hidden",
        ),
        "project_id" => array(
            "label"     => "project id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "project_id",
            "inputType" => "hidden",
        ),
        "project_nama" => array(
            "label"     => "nama project",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "project_nama",
            "inputType" => "text",
        ),
        "workorder_id" => array(
            "label"     => "workorder id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "workorder_id",
            "inputType" => "hidden",
        ),
        "komposisi_id" => array(
            "label"     => "komposisi id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "komposisi_id",
            "inputType" => "hidden",
        ),
        "produk_id" => array(
            "label"     => "produk id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "produk_id",
            "inputType" => "hidden",
        ),
        "produk_kode" => array(
            "label"     => "kode produk",
            "type"      => "varchar",
            "length"    => "100",
            "kolom"     => "produk_kode",
            "inputType" => "text",
        ),
        "produk_nama" => array(
            "label"     => "nama produk",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "produk_nama",
            "inputType" => "text",
        ),
        "hpp_lama" => array(
            "label"     => "hpp lama",
            "type"      => "decimal",
            "length"    => "24,10",
            "kolom"     => "hpp_lama",
            "inputType" => "text",
        ),
        "hpp_baru" => array(
            "label"     => "hpp baru",
            "type"      => "decimal",
            "length"    => "24,10",
            "kolom"     => "hpp_baru",
            "inputType" => "text",
        ),
        "selisih_hpp" => array(
            "label"     => "selisih hpp",
            "type"      => "decimal",
            "length"    => "24,10",
            "kolom"     => "selisih_hpp",
            "inputType" => "text",
        ),
        "sumber_perubahan" => array(
            "label"     => "sumber perubahan",
            "type"      => "varchar",
            "length"    => "50",
            "kolom"     => "sumber_perubahan",
            "inputType" => "text",
        ),
        "ref_transaksi_nomer" => array(
            "label"     => "nomor referensi",
            "type"      => "varchar",
            "length"    => "100",
            "kolom"     => "ref_transaksi_nomer",
            "inputType" => "text",
        ),
        "oleh_id" => array(
            "label"     => "oleh id",
            "type"      => "bigint",
            "length"    => "20",
            "kolom"     => "oleh_id",
            "inputType" => "hidden",
        ),
        "oleh_nama" => array(
            "label"     => "oleh nama",
            "type"      => "varchar",
            "length"    => "255",
            "kolom"     => "oleh_nama",
            "inputType" => "text",
        ),
        "dtime" => array(
            "label"     => "waktu",
            "type"      => "datetime",
            "kolom"     => "dtime",
            "inputType" => "text",
        ),
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Catat riwayat perubahan HPP project ke project_hpp_history
     *
     * @param array $param
     * @return int|bool
     */
    public function recordHistory($param)
    {
        $projectId      = isset($param['project_id']) ? (int)$param['project_id'] : 0;
        $projectNama    = isset($param['project_nama']) ? $param['project_nama'] : '';
        $workorderId    = isset($param['workorder_id']) ? (int)$param['workorder_id'] : 0;
        $komposisiId    = isset($param['komposisi_id']) ? (int)$param['komposisi_id'] : 0;
        $produkId       = isset($param['produk_id']) ? (int)$param['produk_id'] : 0;
        $produkKode     = isset($param['produk_kode']) ? $param['produk_kode'] : '';
        $produkNama     = isset($param['produk_nama']) ? $param['produk_nama'] : '';
        $hppLama        = isset($param['hpp_lama']) ? (float)$param['hpp_lama'] : 0;
        $hppBaru        = isset($param['hpp_baru']) ? (float)$param['hpp_baru'] : 0;
        $sumber         = isset($param['sumber_perubahan']) ? $param['sumber_perubahan'] : 'PO_PEMBELIAN_1466';
        $refNomer       = isset($param['ref_transaksi_nomer']) ? $param['ref_transaksi_nomer'] : '';
        $olehId         = isset($param['oleh_id']) ? (int)$param['oleh_id'] : (function_exists('my_id') ? (int)my_id() : 0);
        $olehNama       = isset($param['oleh_nama']) ? $param['oleh_nama'] : (function_exists('my_name') ? my_name() : 'System');
        $dtime          = isset($param['dtime']) ? $param['dtime'] : date('Y-m-d H:i:s');

        $selisih = $hppBaru - $hppLama;

        $insertData = array(
            'project_id'          => $projectId,
            'project_nama'        => $projectNama,
            'workorder_id'        => $workorderId,
            'komposisi_id'        => $komposisiId,
            'produk_id'           => $produkId,
            'produk_kode'         => $produkKode,
            'produk_nama'         => $produkNama,
            'hpp_lama'            => $hppLama,
            'hpp_baru'            => $hppBaru,
            'selisih_hpp'         => $selisih,
            'sumber_perubahan'    => $sumber,
            'ref_transaksi_nomer' => $refNomer,
            'oleh_id'             => $olehId,
            'oleh_nama'           => $olehNama,
            'dtime'               => $dtime,
        );

        $this->db->insert($this->tableName, $insertData);
        return $this->db->insert_id();
    }

    /**
     * Ambil riwayat perubahan HPP per project
     *
     * @param int $projectId
     * @return array
     */
    public function getHistoryByProject($projectId)
    {
        $this->db->where('project_id', (int)$projectId);
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->tableName)->result();
    }
}
// END OF COMPLETE REPEATED LOGIC
