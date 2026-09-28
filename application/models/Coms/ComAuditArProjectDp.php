<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Model audit untuk memeriksa potensi kegagalan posting A/R Receipt akibat ketidakcocokan saldo DP Project vs GL 2010050.
 * COMPLIANCE: ISO 9001 (Mutu Audit) & ISO 27001 (Anti SQL-Injection Query Binding)
 * LOG_EXPIRE: 2026-11-05
 * [/AGENT_LOG]
 */
class ComAuditArProjectDp extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * [AGENT_LOG]
     * ROLE      : Software Engineer Agent
     * PURPOSE   : Mengambil daftar lengkap transaksi A/R Project dan membandingkan saldo DP vs Buku Besar 2010050.
     * COMPLIANCE: PHP 5.6 Compatible, Safe Query Binding
     * LOG_EXPIRE: 2026-11-05
     * [/AGENT_LOG]
     */
    public function get_audit_report($cabang_id = null) {
        $sql = "
            SELECT 
                t_inv.id AS payment_source_id,
                t_inv.cabang_id,
                t_inv.nomer AS nomer_nota,
                t_inv.transaksi_id AS invoice_transaksi_id,
                t_inv.extern_id AS customer_id,
                t_inv.extern_nama AS customer_nama,
                t_inv.project_id,
                t_inv.project_nama,
                t_inv.sisa AS sisa_tagihan_nota,
                COALESCE(t_dp.transaksi_id, 0) AS dp_transaksi_id,
    COALESCE(t_dp.sisa, 0) AS dpp_uang_muka_dibutuhkan,
    COALESCE(t_dp.ppn_sisa, 0) AS ppn_uang_muka,
    COALESCE((t_dp.sisa + t_dp.ppn_sisa), 0) AS total_uang_muka_ui,
    COALESCE(gl.saldo_buku_besar_2010050, 0) AS saldo_riil_gl_2010050,
    (COALESCE(gl.saldo_buku_besar_2010050, 0) - COALESCE(t_dp.sisa, 0)) AS selisih_defisit,
    CASE 
        WHEN COALESCE(t_dp.sisa, 0) > 0 AND COALESCE(gl.saldo_buku_besar_2010050, 0) < COALESCE(t_dp.sisa, 0) THEN 'GAGAL_DEFISIT'
        ELSE 'AMAN'
                END AS status_audit
            FROM transaksi_payment_source t_inv
            LEFT JOIN transaksi_payment_source t_dp 
                ON t_inv.extern_id = t_dp.extern_id 
               AND t_inv.project_id = t_dp.project_id 
               AND t_dp.label = 'uang muka konsumen' 
               AND t_dp.sisa > 0
            LEFT JOIN (
                SELECT 
                    extern_id, 
                    SUM(kredit) AS saldo_buku_besar_2010050
                FROM _rek_pembantu_subcustomer_cache
                WHERE rekening = '2010050' AND extern2_id = '2010050060' AND periode='forever'
                GROUP BY extern_id
            ) gl ON t_inv.extern_id = gl.extern_id
            WHERE t_inv.target_jenis = ? 
              AND t_inv.jenis = ? 
              AND t_inv.sisa > ?
        ";

        $binds = array('749', '7499', 1000);

        if ($cabang_id !== null && (int)$cabang_id > 0) {
            $sql .= " AND t_inv.cabang_id = ? ";
            $binds[] = (int)$cabang_id;
        }

        $sql .= " ORDER BY t_inv.cabang_id ASC, t_inv.extern_nama ASC";

        $query = $this->db->query($sql, $binds);
        return $query->result_array();
    }
}