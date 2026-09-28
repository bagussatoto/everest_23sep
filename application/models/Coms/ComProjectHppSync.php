<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ComProjectHppSync extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Sinkronisasi HPP Project dari PO Pembelian (Modul 1466 / Manual Revisi)
     * dan secara otomatis mencatat riwayat ke tabel audit project_hpp_history.
     *
     * @param int    $projectId     ID Project (project_produk.id)
     * @param int    $itemId        ID Item/Produk FG (produk.id)
     * @param float  $newHpp        Harga HPP Baru (dari PO)
     * @param string $refDocNo      Nomor Dokumen PO / Referensi
     * @param int    $userId        ID Petugas
     * @param string $userName      Nama Petugas
     * @param string $sumber        Sumber Perubahan (default: 'PO 1466')
     * @param string $keterangan    Catatan Tambahan
     * @return array [status => bool, msg => string, old_hpp => float, new_hpp => float, selisih => float]
     */
    public function syncHppFromPo($projectId, $itemId, $newHpp, $refDocNo = "", $userId = 0, $userName = "", $sumber = "PO 1466", $keterangan = "")
    {
        $projectId = (int)$projectId;
        $itemId    = (int)$itemId;
        $newHpp    = (float)$newHpp;

        if ($projectId <= 0 || $itemId <= 0) {
            return array(
                "status" => false,
                "msg"    => "Parameter Project ID atau Item ID tidak valid."
            );
        }

        // 1. Ambil data HPP lama dari project_komposisi
        // Catatan: Di tabel project_komposisi, produk_id = project_id dan produk_dasar_id = item_id
        $this->db->where("produk_id", $projectId);
        $this->db->where("produk_dasar_id", $itemId);
        $komposisiRow = $this->db->get("project_komposisi")->row_array();

        if (empty($komposisiRow)) {
            // Cek alternatif jika produk_id langsung berisi item_id
            $this->db->where("produk_dasar_id", $projectId);
            $this->db->where("produk_id", $itemId);
            $komposisiRow = $this->db->get("project_komposisi")->row_array();
        }

        $oldHpp      = isset($komposisiRow['hrg_hpp']) ? (float)$komposisiRow['hrg_hpp'] : 0.0;
        $komposisiId = isset($komposisiRow['id']) ? (int)$komposisiRow['id'] : 0;
        $itemNama    = isset($komposisiRow['produk_dasar_nama']) ? $komposisiRow['produk_dasar_nama'] : "";
        $itemKode    = "";

        // Ambil kode & nama produk dari master produk jika belum lengkap
        $prodMaster = $this->db->query("SELECT id, nama, kode FROM produk WHERE id = ? LIMIT 1", array($itemId))->row_array();
        if (!empty($prodMaster)) {
            $itemKode = isset($prodMaster['kode']) ? $prodMaster['kode'] : "";
            if (empty($itemNama)) {
                $itemNama = isset($prodMaster['nama']) ? $prodMaster['nama'] : "";
            }
        }

        $selisihHpp = $newHpp - $oldHpp;

        // Mulai Transaksi Database
        $this->db->trans_start();

        // 2. Update HPP di project_komposisi
        $this->db->query(
            "UPDATE project_komposisi SET hrg_hpp = ?, last_update = NOW() WHERE (produk_id = ? AND produk_dasar_id = ?) OR (produk_dasar_id = ? AND produk_id = ?)",
            array($newHpp, $projectId, $itemId, $projectId, $itemId)
        );

        // 3. Update HPP di project_komposisi_workoder
        $this->db->query(
            "UPDATE project_komposisi_workoder SET hrg_hpp = ?, last_update = NOW() WHERE (produk_id = ? AND produk_dasar_id = ?) OR (produk_dasar_id = ? AND produk_id = ?)",
            array($newHpp, $projectId, $itemId, $projectId, $itemId)
        );

        // 4. Update HPP di project_komposisi_sub_workoder
        $this->db->query(
            "UPDATE project_komposisi_sub_workoder SET hrg_hpp = ?, last_update = NOW() WHERE (produk_id = ? AND produk_dasar_id = ?) OR (produk_dasar_id = ? AND produk_id = ?)",
            array($newHpp, $projectId, $itemId, $projectId, $itemId)
        );

        // 5. Update est_harga_beli di project_purchase_pool
        $this->db->query(
            "UPDATE project_purchase_pool SET est_harga_beli = ?, last_updated_dtime = NOW() WHERE project_id = ? AND produk_id = ?",
            array($newHpp, $projectId, $itemId)
        );

        // Ambil nama project dari project_produk
        $projRow = $this->db->query("SELECT id, nama FROM project_produk WHERE id = ? LIMIT 1", array($projectId))->row_array();
        $projectNama = isset($projRow['nama']) ? $projRow['nama'] : "";

        // 6. Catat ke Tabel Audit Trail project_hpp_history
        $auditData = array(
            "project_id"          => $projectId,
            "project_nama"        => $projectNama,
            "komposisi_id"        => $komposisiId,
            "produk_id"           => $itemId,
            "produk_kode"         => $itemKode,
            "produk_nama"         => $itemNama,
            "hpp_lama"            => $oldHpp,
            "hpp_baru"            => $newHpp,
            "selisih_hpp"         => $selisihHpp,
            "sumber_perubahan"    => $sumber,
            "ref_transaksi_nomer" => $refDocNo,
            "keterangan"          => $keterangan,
            "oleh_id"             => $userId,
            "oleh_nama"           => !empty($userName) ? $userName : "System",
            "dtime"               => date("Y-m-d H:i:s")
        );
        $this->db->insert("project_hpp_history", $auditData);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return array(
                "status" => false,
                "msg"    => "Gagal melakukan sinkronisasi HPP ke database."
            );
        }

        return array(
            "status"      => true,
            "msg"         => "Berhasil memperbarui HPP dan mencatat ke audit trail.",
            "old_hpp"     => $oldHpp,
            "new_hpp"     => $newHpp,
            "selisih_hpp" => $selisihHpp
        );
    }

    /**
     * Mengambil seluruh riwayat perubahan HPP untuk suatu project
     *
     * @param int $projectId
     * @return array
     */
    public function getHppHistory($projectId)
    {
        $projectId = (int)$projectId;
        $this->db->where("project_id", $projectId);
        $this->db->order_by("id", "DESC");
        return $this->db->get("project_hpp_history")->result_array();
    }
}
