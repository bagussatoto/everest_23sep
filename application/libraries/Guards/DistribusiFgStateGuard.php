<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guard Sentral Transisi Status & Validation Guard (Rule 3.8 & Arsitektur Senior)
 * Memastikan transisi antar-step transaksi sah dan mencegah eksekusi pada transaksi dibatalkan.
 */
class DistribusiFgStateGuard
{
    // Matriks Transisi Status yang Diizinkan (Current Step => Allowed Target Steps)
    private static $allowedTransitions = array(
        1 => array(2),       // Step 1 (Draft/Pre-packing) -> Step 2 (Packinglist)
        2 => array(3, 1),    // Step 2 (Packinglist) -> Step 3 (Pengiriman) atau Revert ke 1
        3 => array(4, 2),    // Step 3 (Pengiriman) -> Step 4 (Penerimaan) atau Revert ke 2
        4 => array()         // Step 4 (Penerimaan) -> Terminal State (Selesai)
    );

    /**
     * Memvalidasi apakah permintaan transisi status sah secara aturan bisnis.
     */
    public static function validateTransition($transaksiId, $currentStep, $targetStep, $isTrash4 = 0, $isTrash = 0)
    {
        $currentStep = intval($currentStep);
        $targetStep = intval($targetStep);

        // 1. Fail-Fast: Cek pembatalan/penghapusan
        if (intval($isTrash4) === 1) {
            return array(
                "valid" => false,
                "title" => "Transaksi Dibatalkan",
                "message" => "Transaksi ID $transaksiId telah dibatalkan (trash_4 = 1). Operasi dihentikan."
            );
        }

        if (intval($isTrash) === 1) {
            return array(
                "valid" => false,
                "title" => "Transaksi Dihapus",
                "message" => "Transaksi ID $transaksiId telah dibuang ke sampah (trash = 1). Operasi dihentikan."
            );
        }

        // 2. Fail-Fast: Cek matriks transisi step
        if ($currentStep > 0 && $targetStep > 0) {
            if (!isset(self::$allowedTransitions[$currentStep]) || !in_array($targetStep, self::$allowedTransitions[$currentStep])) {
                return array(
                    "valid" => false,
                    "title" => "Transisi Status Dilarang",
                    "message" => "Transisi dari Step $currentStep ke Step $targetStep tidak diizinkan."
                );
            }
        }

        return array("valid" => true);
    }
}
