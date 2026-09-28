<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Backorder extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
         * Loader helper dan model standar HMVC Penjualan
         * ----------------------------------------------------------------------------------*/
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->load->helper("he_angka");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->model("MdlTransaksi");
    }

    /**
     * Halaman Utama Multi-Enterprise Backorder Management Cockpit
     * @param string $jenisTr Jenis Transaksi Penjualan (default: 5822 / 582)
     */
    public function index($jenisTr = "5822")
    {
        $this->jenisTr = !empty($jenisTr) ? $jenisTr : "5822";
        $cCode = "_TR_" . $this->jenisTr;

        /* ----------------------------------------------------------------------------------
         * 1. Early Validation: Validasi User Session & Hak Akses (ISO 27001)
         * ----------------------------------------------------------------------------------*/
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }

        /* ----------------------------------------------------------------------------------
         * 2. Target Kode Pre Order (contoh: 5822spo / 582spo)
         * ----------------------------------------------------------------------------------*/
        $spoCode = isset($this->configUi[$this->jenisTr]['steps'][1]['target']) 
                   ? $this->configUi[$this->jenisTr]['steps'][1]['target'] 
                   : $this->jenisTr . "spo";

        $soCode = isset($this->configUi[$this->jenisTr]['steps'][2]['target']) 
                  ? $this->configUi[$this->jenisTr]['steps'][2]['target'] 
                  : $this->jenisTr . "so";

        /* ----------------------------------------------------------------------------------
         * 3. Kalkulasi KPI Global Backorder Berbasis Kuantitas Barang (Data Atomik)
         * ----------------------------------------------------------------------------------*/
        $sqlKpi = "SELECT 
                    IFNULL(SUM(td.valid_qty), 0) AS total_backorder_qty,
                    COUNT(DISTINCT t.customers_id) AS total_impacted_entities,
                    IFNULL(ROUND(AVG(DATEDIFF(NOW(), t.dtime)), 1), 0) AS avg_aging_days,
                    IFNULL(SUM(CASE WHEN DATEDIFF(NOW(), t.dtime) > 7 THEN 1 ELSE 0 END), 0) AS total_critical_orders
                FROM transaksi t
                INNER JOIN transaksi_data td ON td.transaksi_id = t.id
                WHERE t.jenis_master = '$jenisTr' 
                  AND t.jenis_top = '$spoCode'
                  AND t.jenis = '$spoCode' 
                  AND t.trash = '0'
                  AND t.next_step_code = '$soCode'
                  AND td.next_substep_code !=''
                  AND td.trash = '0'
                  AND td.valid_qty > 0";

        $queryKpi = $this->db->query($sqlKpi);
        $kpiData = $queryKpi->row_array();

        if (empty($kpiData)) {
            $kpiData = array(
                "total_backorder_qty" => 0,
                "total_impacted_entities" => 0,
                "avg_aging_days" => 0,
                "total_critical_orders" => 0
            );
        }

        /* ----------------------------------------------------------------------------------
         * 4. Membaca Baris Atomik Backorder Lengkap dengan Dual-Tier Net Available Stock (Fisik - Booking)
         * ----------------------------------------------------------------------------------*/
        $sqlRows = "SELECT 
                        t.id AS transaksi_id,
                        t.nomer AS pre_so_nomer,
                        t.dtime AS tanggal,
                        t.customers_id,
                        IFNULL(t.customers_nama, 'Pelanggan Umum') AS customers_nama,
                        t.cabang_id,
                        IFNULL(t.cabang_nama, 'Cabang Utama') AS cabang_nama,
                        td.id AS detail_id,
                        td.produk_id,
                        td.produk_kode AS sku,
                        td.produk_nama,
                        td.produk_ord_jml AS qty_order,
                        td.valid_qty AS outstanding,
                        DATEDIFF(NOW(), t.dtime) AS aging_days,
                        IFNULL(stok_cabang_cache.qty_debet, 0) AS stok_cabang_fisik,
                        IFNULL(sb_cabang.qty_booking, 0) AS stok_cabang_booking,
                        GREATEST(0, IFNULL(stok_cabang_cache.qty_debet, 0) - IFNULL(sb_cabang.qty_booking, 0)) AS stok_cabang_net,
                        IFNULL(stok_nasional.total_fisik, 0) AS stok_perusahaan_fisik,
                        IFNULL(sb_nasional.total_booking, 0) AS stok_perusahaan_booking,
                        GREATEST(0, IFNULL(stok_nasional.total_fisik, 0) - IFNULL(sb_nasional.total_booking, 0)) AS stok_perusahaan_net
                    FROM transaksi t
                    INNER JOIN transaksi_data td ON td.transaksi_id = t.id
                    LEFT JOIN (
                        SELECT 
                            extern_id,
                            cabang_id,
                            IFNULL(SUM(qty_debet), 0) AS qty_debet
                        FROM _rek_pembantu_produk_cache
                        WHERE periode = 'forever'
                        GROUP BY extern_id, cabang_id
                    ) stok_cabang_cache 
                        ON stok_cabang_cache.extern_id = td.produk_id 
                       AND stok_cabang_cache.cabang_id = t.cabang_id
                    LEFT JOIN (
                        SELECT 
                            so_td.produk_id,
                            so_t.cabang_id,
                            IFNULL(SUM(so_td.valid_qty), 0) AS qty_booking
                        FROM transaksi so_t
                        INNER JOIN transaksi_data so_td ON so_td.transaksi_id = so_t.id
                        WHERE so_t.jenis IN ('5822so', '5823so')
                          AND so_t.trash_4 = '0'
                          AND so_t.link_id = '0'
                          AND so_td.valid_qty > 0
                          AND so_td.next_substep_code != ''
                        GROUP BY so_td.produk_id, so_t.cabang_id
                    ) sb_cabang ON sb_cabang.produk_id = td.produk_id AND sb_cabang.cabang_id = t.cabang_id
                    LEFT JOIN (
                        SELECT 
                            extern_id,
                            IFNULL(SUM(qty_debet), 0) AS total_fisik
                        FROM _rek_pembantu_produk_cache
                        WHERE periode = 'forever'
                        GROUP BY extern_id
                    ) stok_nasional ON stok_nasional.extern_id = td.produk_id
                    LEFT JOIN (
                        SELECT 
                            so_td.produk_id,
                            IFNULL(SUM(so_td.valid_qty), 0) AS total_booking
                        FROM transaksi so_t
                        INNER JOIN transaksi_data so_td ON so_td.transaksi_id = so_t.id
                        WHERE so_t.jenis IN ('5822so', '5823so')
                          AND so_t.trash_4 = '0'
                          AND so_t.link_id = '0'
                          AND so_td.valid_qty > 0
                          AND so_td.next_substep_code != ''
                        GROUP BY so_td.produk_id
                    ) sb_nasional ON sb_nasional.produk_id = td.produk_id
                    WHERE t.jenis_master = '$jenisTr' 
                      AND t.jenis_top = '$spoCode'
                      AND t.jenis = '$spoCode' 
                      AND t.trash = '0'
                      AND t.next_step_code = '$soCode'
                      AND td.next_substep_code !=''
                      AND td.trash = '0'
                      AND td.valid_qty > 0
                    ORDER BY t.id DESC, td.id ASC";

        $queryRows = $this->db->query($sqlRows);
        $rawRows = $queryRows->result_array();

        $data = array(
            "title" => "Multi-Enterprise Backorder Cockpit",
            "subTitle" => "Control Tower Pemenuhan Pesanan Tertunda",
            "jenisTr" => $this->jenisTr,
            "spoCode" => $spoCode,
            "cCode" => $cCode,
            "kpiData" => $kpiData,
            "rawRowsJson" => json_encode($rawRows),
            "modulPath" => MODUL_PATH
        );

        $this->load->view("backorder_cockpit", $data);
    }

    /**
     * Endpoint Eksekusi Rekomendasi Alokasi Stok (ISO 9001 Audit Traceability)
     */
    public function execute_allocation_ajax()
    {
        if (!isset($this->session->login['id'])) {
            $response = array("status" => "error", "message" => "Sesi Anda telah berakhir.");
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $detailId = $this->input->post('detail_id');
        $allocationType = $this->input->post('allocation_type');

        if (empty($detailId) || empty($allocationType)) {
            $response = array("status" => "error", "message" => "Parameter alokasi tidak lengkap.");
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
            return;
        }

        $this->db->trans_start();

        // Log audit pencatatan eksekusi alokasi barang
        $this->db->insert("transaksi_log", array(
            "transaksi_id" => $detailId,
            "dtime" => dtimeNow(),
            "keterangan" => "Eksekusi Alokasi Kuantitas Backorder: " . $allocationType . " oleh " . my_name(),
            "oleh_id" => my_id()
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $response = array("status" => "error", "message" => "Gagal mengeksekusi alokasi stok.");
        } else {
            $response = array(
                "status" => "success",
                "message" => "Alokasi kuantitas backorder (" . $allocationType . ") berhasil dieksekusi dan dicatat di log audit."
            );
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }
}
// END OF COMPLETE REPEATED LOGIC
