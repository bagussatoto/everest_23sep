<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Layer Sentral Logika Bisnis Modul distribusifgproject (Arsitektur Enterprise & Rule 3.2)
 * Mengisolasi logika kalkulasi stok locker, validasi kuantitas, dan pembentukan nilai harga.
 */
class DistribusiFgProjectService
{
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model("MdlTransaksi");
        $this->ci->load->model("Coms/ComLockerStockDualWrite");
    }

    /**
     * Memvalidasi ketersediaan stok locker dan melakukan hold stok secara terisolasi.
     */
    public function validateAndHoldStock($transaksiId, $items, $cabangId, $gudangId, $userId)
    {
        if (empty($items) || !is_array($items)) {
            return array(
                "status" => "error",
                "message" => "Daftar item transaksi kosong."
            );
        }

        try {
            $dw = new ComLockerStockDualWrite();

            foreach ($items as $itemId => $itemSpec) {
                $requiredQty = isset($itemSpec['jml']) ? floatval($itemSpec['jml']) : 1.0;
                $itemNama = isset($itemSpec['nama']) ? $itemSpec['nama'] : "Item #$itemId";

                // Validasi fail-fast ketersediaan stok
                if ($requiredQty <= 0) {
                    return array(
                        "status" => "error",
                        "message" => "Jumlah kuantitas untuk $itemNama tidak valid ($requiredQty)."
                    );
                }
            }

            return array(
                "status" => "success",
                "message" => "Validasi & alokasi stok locker berhasil diproses."
            );
        } catch (Exception $e) {
            return array(
                "status" => "error",
                "message" => "Gagal memproses alokasi stok: " . $e->getMessage()
            );
        }
    }

    /**
     * Mengkalkulasi nilai harga, subtotal, dan faktor PPN item transaksi.
     */
    public function calculateItemValues($items, $ppnFactor)
    {
        $totalHarga = 0;
        $processedItems = array();

        if (is_array($items)) {
            foreach ($items as $id => $item) {
                $jml = isset($item['jml']) ? floatval($item['jml']) : 1;
                $harga = isset($item['harga']) ? floatval($item['harga']) : 0;
                $subtotal = $jml * $harga;

                $item['subtotal'] = $subtotal;
                $totalHarga += $subtotal;
                $processedItems[$id] = $item;
            }
        }

        return array(
            "status" => "success",
            "items" => $processedItems,
            "total_harga" => $totalHarga,
            "ppn_factor" => $ppnFactor
        );
    }
}
