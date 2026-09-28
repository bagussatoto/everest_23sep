<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Guard Sentral Transisi Status & Validation Guard untuk Modul distribusisuppliesproject
 * Memastikan transisi antar-step transaksi supplies sah dan mencegah eksekusi pada transaksi dibatalkan.
 * Berdasarkan Rule 3.8 (Early Validation Pattern) & Arsitektur ISO 9001.
 */
class DistribusiSuppliesStateGuard
{
    // Matriks Transisi Status yang Diizinkan (Current Step => Allowed Target Steps)
    // Step 1 (Draft/Distribusi Supplies Project) -> Step 2 (Otorisasi)
    // Step 2 (Otorisasi) -> Terminal State (Selesai)
    private static $allowedTransitions = array(
        1 => array(2),       // Step 1 (Draft/Distribusi) -> Step 2 (Otorisasi)
        2 => array()         // Step 2 (Otorisasi) -> Terminal State (Selesai)
    );

    /**
     * Memvalidasi apakah permintaan transisi status sah secara aturan bisnis.
     *
     * @param int|string $transaksiId  ID transaksi
     * @param int|string $currentStep  Step saat ini
     * @param int|string $targetStep   Step target
     * @param int        $isTrash4     Flag pembatalan (trash_4)
     * @param int        $isTrash      Flag penghapusan (trash)
     * @return array     Array dengan key 'valid' (bool), 'title', 'message'
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
                    "message" => "Transisi dari Step $currentStep ke Step $targetStep tidak diizinkan untuk transaksi distribusi supplies project."
                );
            }
        }

        return array("valid" => true);
    }
}
