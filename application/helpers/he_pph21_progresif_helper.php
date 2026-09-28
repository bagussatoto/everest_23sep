<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
if (!function_exists('hitung_pph21_progresif_uuhpp')) {
    /**
     * Hitung PPh 21 Progresif UU HPP
     *
     * @param float $bruto Nilai bruto komisi/rebate
     * @param boolean $has_fasilitas True jika omset < 50M (dapat potongan 50%), False jika omset > 50M (tanpa potongan 50%)
     * @return array Array berisi bruto, total_pph, nilai_kas_cn, tarif_efektif_total, dan rincian per layer
     */
    function hitung_pph21_progresif_uuhpp($bruto, $has_fasilitas) {
        $bruto = floatval($bruto);
        $dpp_factor = $has_fasilitas ? 0.50 : 1.00;

        // Layering tarif dasar PPh 21 UU HPP
        $base_layers = array(
            array('max' => 60000000, 'tarif' => 0.05, 'nama_layer' => '5%'),
            array('max' => 250000000, 'tarif' => 0.15, 'nama_layer' => '15%'),
            array('max' => 500000000, 'tarif' => 0.25, 'nama_layer' => '25%'),
            array('max' => 5000000000, 'tarif' => 0.30, 'nama_layer' => '30%'),
            array('max' => 2147483647, 'tarif' => 0.35, 'nama_layer' => '35%'),
        );

        $sisa_bruto = $bruto;
        $prev_max = 0;
        $total_pph = 0;
        $detail_layers = array();

        foreach ($base_layers as $layer) {
            if ($sisa_bruto <= 0) {
                break;
            }

            $cap = $layer['max'] - $prev_max;
            $kena_pajak = ($sisa_bruto > $cap) ? $cap : $sisa_bruto;
            $tarif_efektif_layer = $layer['tarif'] * $dpp_factor;
            $pph_layer = $kena_pajak * $tarif_efektif_layer;

            $total_pph += $pph_layer;
            $sisa_bruto -= $kena_pajak;
            $prev_max = $layer['max'];

            $detail_layers[] = array(
                'label' => $has_fasilitas 
                    ? '50% * ' . $layer['nama_layer'] . ' (' . ($tarif_efektif_layer * 100) . '%)'
                    : '100% * ' . $layer['nama_layer'] . ' (' . ($tarif_efektif_layer * 100) . '%)',
                'bruto_layer' => $kena_pajak,
                'pph_layer' => $pph_layer,
                'tarif_efektif' => $tarif_efektif_layer,
            );
        }

        $tarif_efektif_total = ($bruto > 0) ? ($total_pph / $bruto) * 100 : 0;
        $nilai_kas_cn = $bruto - $total_pph;

        return array(
            'bruto' => $bruto,
            'has_fasilitas' => $has_fasilitas,
            'dpp_factor' => $dpp_factor,
            'total_pph' => $total_pph,
            'nilai_kas_cn' => $nilai_kas_cn,
            'tarif_efektif_total' => $tarif_efektif_total,
            'detail' => $detail_layers,
        );
    }
}

if (!function_exists('hitung_pph21_progresif_dibawah_50m')) {
    /**
     * Helper khusus: Omset < 50M (Dapat Fasilitas Potongan 50% UU HPP)
     */
    function hitung_pph21_progresif_dibawah_50m($bruto) {
        return hitung_pph21_progresif_uuhpp($bruto, true);
    }
}

if (!function_exists('hitung_pph21_progresif_diatas_50m')) {
    /**
     * Helper khusus: Omset > 50M (Tanpa Fasilitas Potongan 50% UU HPP)
     */
    function hitung_pph21_progresif_diatas_50m($bruto) {
        return hitung_pph21_progresif_uuhpp($bruto, false);
    }
}
// END OF COMPLETE REPEATED LOGIC
