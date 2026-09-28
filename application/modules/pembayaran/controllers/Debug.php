<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        require_once "_construct_file.php";

    }


    public function index()
    {

        $cCode = $this->cCode;
        if (isset($_SESSION[$cCode])) {
            arrprint($_SESSION[$cCode]);

            if (isset($_SESSION[$cCode]["itemSrc"])) {
                $sisa_total = 0;
                foreach ($_SESSION[$cCode]["itemSrc"] as $ii => $iiSpec) {
                    $sisa_total += $iiSpec["sisa"];
                }
                cekHere("total ceklist ItemSrc: $sisa_total");
                cekHere(count($_SESSION[$cCode]["itemSrc"]));
            }


        }
        else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

    public function report_mismatch()
    {
        $supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : '';
        $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

        // 1. Fetch distinct supplier list for dropdown filter
        $sql_suppliers = "
            SELECT DISTINCT 
                cache.extern_id AS supplier_id,
                IFNULL(c.nama, cache.extern_id) AS supplier_nama
            FROM _rek_pembantu_uang_muka_reference_cache cache
            LEFT JOIN per_supplier c ON c.id = cache.extern_id
            WHERE cache.rekening = '1010050010'
              AND cache.debet > 0
              AND cache.periode = 'forever'
            ORDER BY supplier_nama ASC
        ";
        $query_suppliers = $this->db->query($sql_suppliers);
        $supplier_list = $query_suppliers->result_array();

        // 2. Fetch suppliers that specifically have MISMATCH records (for quick action buttons)
        $sql_mismatch_suppliers = "
            SELECT 
                cache.extern_id AS supplier_id,
                IFNULL(c.nama, cache.extern_id) AS supplier_nama,
                COUNT(cache.extern2_id) AS total_mismatch_po,
                SUM(cache.debet - IFNULL(tum.sisa, 0)) AS total_selisih
            FROM _rek_pembantu_uang_muka_reference_cache cache
            LEFT JOIN transaksi_uang_muka_source tum 
                ON tum.extern_id = cache.extern_id 
               AND tum.extern2_id = cache.extern2_id
            LEFT JOIN per_supplier c ON c.id = cache.extern_id
            WHERE cache.rekening = '1010050010'
              AND cache.debet > 0
              AND cache.periode = 'forever'
              AND (tum.id IS NULL OR tum.sisa IS NULL OR cache.debet != tum.sisa)
            GROUP BY cache.extern_id, supplier_nama
            ORDER BY total_selisih DESC, supplier_nama ASC
        ";
        $query_mismatch_suppliers = $this->db->query($sql_mismatch_suppliers);
        $mismatch_supplier_list = $query_mismatch_suppliers->result_array();

        // 3. Fetch all titipan PO records (both Match and Mismatch)
        $sql = "
            SELECT 
                cache.extern_id AS supplier_id,
                IFNULL(c.nama, cache.extern_id) AS supplier_nama,
                cache.extern2_id AS po_id,
                cache.extern2_nama AS nomer_po,
                cache.debet AS saldo_di_modal_selector,
                IFNULL(tum.sisa, 0) AS saldo_di_form_pembayaran,
                (cache.debet - IFNULL(tum.sisa, 0)) AS selisih_saldo,
                CASE 
                    WHEN tum.id IS NULL OR tum.sisa IS NULL OR (cache.debet != tum.sisa) THEN 'MISMATCH'
                    ELSE 'MATCH'
                END AS status_match
            FROM _rek_pembantu_uang_muka_reference_cache cache
            LEFT JOIN transaksi_uang_muka_source tum 
                ON tum.extern_id = cache.extern_id 
               AND tum.extern2_id = cache.extern2_id
            LEFT JOIN per_supplier c ON c.id = cache.extern_id
            WHERE cache.rekening = '1010050010'
              AND cache.debet > 0
              AND cache.periode = 'forever'
        ";

        $binds = array();
        if (!empty($supplier_id)) {
            $sql .= " AND cache.extern_id = ? ";
            $binds[] = $supplier_id;
        }

        if (!empty($status_filter)) {
            if ($status_filter === 'MISMATCH') {
                $sql .= " AND (tum.id IS NULL OR tum.sisa IS NULL OR cache.debet != tum.sisa) ";
            }
            else if ($status_filter === 'MATCH') {
                $sql .= " AND (tum.id IS NOT NULL AND tum.sisa IS NOT NULL AND cache.debet = tum.sisa) ";
            }
        }

        $sql .= " ORDER BY (cache.debet - IFNULL(tum.sisa, 0)) DESC, cache.debet DESC ";

        $query = $this->db->query($sql, $binds);
        $data['mismatches'] = $query->result_array();
        $data['supplier_list'] = $supplier_list;
        $data['mismatch_supplier_list'] = $mismatch_supplier_list;
        $data['supplier_id'] = $supplier_id;
        $data['status_filter'] = $status_filter;

        $this->load->view("report_titipan_mismatch", $data);
    }
}
