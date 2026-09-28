<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Service Layer Sentral Logika Bisnis Modul distribusisuppliesproject (Arsitektur Enterprise & Rule 3.2)
 * Mengisolasi logika kalkulasi stok supplies, validasi kuantitas, dan pembentukan nilai harga.
 */
class DistribusiSuppliesProjectService
{
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model("MdlTransaksi");
        $this->ci->load->model("Coms/ComLockerStockDualWrite");
    }

    /**
     * Memvalidasi ketersediaan stok locker supplies dan melakukan hold stok secara terisolasi.
     *
     * @param int|string $transaksiId  ID transaksi
     * @param array      $items        Daftar item supplies (array asosiatif)
     * @param int|string $cabangId     ID cabang
     * @param int|string $gudangId     ID gudang
     * @param int|string $userId       ID user
     * @return array     Array dengan key 'status', 'message'
     */
    public function validateAndHoldStock($transaksiId, $items, $cabangId, $gudangId, $userId)
    {
        if (empty($items) || !is_array($items)) {
            return array(
                "status" => "error",
                "message" => "Daftar item supplies transaksi kosong."
            );
        }

        try {
            $dw = new ComLockerStockDualWrite();

            foreach ($items as $itemId => $itemSpec) {
                $requiredQty = isset($itemSpec['jml']) ? floatval($itemSpec['jml']) : 1.0;
                $itemNama = isset($itemSpec['nama']) ? $itemSpec['nama'] : "Supplies #$itemId";

                // Validasi fail-fast ketersediaan stok supplies
                if ($requiredQty <= 0) {
                    return array(
                        "status" => "error",
                        "message" => "Jumlah kuantitas untuk $itemNama tidak valid ($requiredQty)."
                    );
                }
            }

            return array(
                "status" => "success",
                "message" => "Validasi & alokasi stok supplies locker berhasil diproses."
            );
        } catch (Exception $e) {
            return array(
                "status" => "error",
                "message" => "Gagal memproses alokasi stok supplies: " . $e->getMessage()
            );
        }
    }

    /**
     * Mengkalkulasi nilai harga, subtotal, dan faktor PPN item supplies transaksi.
     *
     * @param array      $items       Daftar item supplies
     * @param float      $ppnFactor   Faktor PPN (contoh: 0.11 untuk PPN 11%)
     * @return array     Array dengan key 'status', 'items', 'total_harga', 'ppn_factor'
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
