<?php

// START OF COMPLETE REPEATED LOGIC
/**
 * Controller SpkRekonsiliasi
 * Tool Rekonsiliasi & Audit Pembayaran SPK Vendor:
 * Menyandingkan data SPK yang telah selesai QC dengan Realisasi Pengeluaran Kas/Bank
 * baik melalui Modul Penerimaan Jasa (463 -> 462) maupun Jalur Biaya Project (3674 -> 3675),
 * serta mengidentifikasi SPK yang berstatus Outstanding Murni.
 * 
 * Kompatibel penuh dengan PHP 5.6 & CodeIgniter 3 HMVC (Wiredesignz)
 */
class SpkRekonsiliasi extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper("url");
        $this->load->helper("he_misc");
    }

    /**
     * Halaman Utama Tool Rekonsiliasi SPK
     */
    public function index()
    {
        // Parameter Filter dari GET
        $filter_vendor   = isset($_GET['vendor']) ? trim($_GET['vendor']) : "76"; // Default pilot project ID 76
        $filter_status   = isset($_GET['status']) ? trim($_GET['status']) : "all"; // all, lunas_jasa, biaya_project, outstanding, belum_qc
        $filter_tipe     = isset($_GET['tipe']) ? trim($_GET['tipe']) : "all"; // all, reguler, tambahan
        $filter_search   = isset($_GET['q']) ? trim($_GET['q']) : "";
        $filter_periode  = isset($_GET['periode']) ? trim($_GET['periode']) : "";
        $format          = isset($_GET['format']) ? trim($_GET['format']) : "html";

        // 1. Ambil Daftar Seluruh Vendor untuk Dropdown Filter
        $sqlVendors = "SELECT DISTINCT employee_id, employee_nama 
                       FROM (
                           SELECT employee_id, employee_nama FROM project_tasklist WHERE type_pelaksana = 22 AND status = 1 AND trash = 0
                           UNION 
                           SELECT employee_id, employee_nama FROM project_tasklist_tambahan WHERE type_pelaksana = 22 AND status = 1 AND trash = 0
                       ) v
                       WHERE employee_nama != '' ORDER BY employee_nama ASC";
        $vendorList = $this->db->query($sqlVendors)->result_array();

        // 2. Ambil Transaksi 463 (Service Received Note) & 462 (A/P Payment)
        $this->db->select("t.id, t.nomer, t.dtime, t.keterangan, ps.extern_id, ps.extern_nama, ps.tagihan, ps.terbayar, ps.sisa, ps.lunas");
        $this->db->from("transaksi t");
        $this->db->join("transaksi_payment_source ps", "ps.transaksi_id = t.id");
        $this->db->where("ps.jenis", "463");
        if ($filter_vendor !== "all" && $filter_vendor !== "") {
            $this->db->where("ps.extern_id", $filter_vendor);
        }
        $this->db->order_by("t.dtime", "ASC");
        $raw463 = $this->db->get()->result_array();

        // Ambil Data Pembayaran 462 Terkait dengan 463 tersebut
        $map462 = array();
        if (!empty($raw463)) {
            $tx463Ids = array();
            foreach ($raw463 as $r463) {
                $tx463Ids[] = (int)$r463['id'];
            }
            if (!empty($tx463Ids)) {
                $this->db->select("td.transaksi_id as trx_462_id, td.produk_id as trx_463_id, td.produk_ord_hrg as bayar_nilai, "
                    . "t.nomer as no_462, t.dtime as tgl_462, t.bank_nama, t.bank_rekening_nama, t.keterangan as ket_462");
                $this->db->from("transaksi_data td");
                $this->db->join("transaksi t", "t.id = td.transaksi_id");
                $this->db->where_in("td.produk_id", $tx463Ids);
                $this->db->where("td.produk_jenis", "invoice");
                $this->db->where("t.jenis", "462");
                $this->db->where("t.status", 1);
                $this->db->where("t.trash", 0);
                $q462 = $this->db->get()->result_array();
                foreach ($q462 as $b462) {
                    $map462[$b462['trx_463_id']][] = $b462;
                }
            }
        }

        // Siapkan Index Pencarian Teks dari 463
        $index463 = array();
        foreach ($raw463 as $r463) {
            $r463['payments_462'] = isset($map462[$r463['id']]) ? $map462[$r463['id']] : array();
            $normKet = $this->normalizeString($r463['keterangan']);
            $index463[] = array(
                'data'     => $r463,
                'norm_ket' => $normKet,
            );
        }

        // 3. Ambil Transaksi 3674 (Biaya Project) dari Payment Source
        $this->db->select("ps.id, ps.transaksi_id, ps.nomer, ps.extern_id, ps.extern_nama, ps.extern4_nama, "
            . "ps.tagihan, ps.terbayar, ps.sisa, ps.lunas, ps.fulldate, t.keterangan, t.project_nama");
        $this->db->from("transaksi_payment_source ps");
        $this->db->join("transaksi t", "t.id = ps.transaksi_id", "left");
        $this->db->where("ps.target_jenis", "3675");
        if ($filter_vendor !== "all" && $filter_vendor !== "") {
            $this->db->where("ps.extern_id", $filter_vendor);
        }
        $raw3674 = $this->db->get()->result_array();

        $map3674BySpk = array();
        foreach ($raw3674 as $r3674) {
            $spkKey = trim($r3674['extern4_nama']);
            if ($spkKey !== "") {
                $map3674BySpk[$spkKey] = $r3674;
            }
        }

        // 4. Ambil Nilai Upah Hak Vendor dari RAB Sub (QC Riil)
        $mapUpah = array();
        // Dari project_komponen_biaya_details_rab_sub (Reguler)
        $sqlUpahReg = "SELECT no_spk, SUM(debet) as total_upah 
                       FROM project_komponen_biaya_details_rab_sub 
                       WHERE jenis = 'biaya' AND status = 1 AND trash = 0 
                       GROUP BY no_spk";
        $qUpahReg = $this->db->query($sqlUpahReg)->result_array();
        foreach ($qUpahReg as $uReg) {
            $mapUpah[trim($uReg['no_spk'])] = (float)$uReg['total_upah'];
        }

        // Dari project_komponen_biaya_details_rab_sub_tambahan (Tambahan)
        $sqlUpahTmb = "SELECT no_spk, SUM(debet) as total_upah 
                       FROM project_komponen_biaya_details_rab_sub_tambahan 
                       WHERE jenis = 'biaya' AND status = 1 AND trash = 0 
                       GROUP BY no_spk";
        $qUpahTmb = $this->db->query($sqlUpahTmb)->result_array();
        foreach ($qUpahTmb as $uTmb) {
            $mapUpah[trim($uTmb['no_spk'])] = (float)$uTmb['total_upah'];
        }

        // 5. Ambil Daftar SPK
        $tables = array();
        if ($filter_tipe === "all" || $filter_tipe === "reguler") {
            $tables["Reguler"] = "project_tasklist";
        }
        if ($filter_tipe === "all" || $filter_tipe === "tambahan") {
            $tables["Tambahan"] = "project_tasklist_tambahan";
        }

        $allSpk = array();
        foreach ($tables as $tipeLabel => $tbl) {
            $this->db->select("id, no_spk, employee_id, employee_nama, type_pelaksana, "
                . "produk_id, produk_nama, nama as project_nama, "
                . "progress_id, progress_nama, qc_dtime, qc_auth_nama, "
                . "post_biaya_id, post_biaya_no, post_biaya_dtime, dtime");
            $this->db->from($tbl);
            $this->db->where("status", 1);
            $this->db->where("trash", 0);
            $this->db->where("type_pelaksana", 22); // Hanya Vendor

            if ($filter_vendor !== "all" && $filter_vendor !== "") {
                $this->db->where("employee_id", $filter_vendor);
            }
            if ($filter_search !== "") {
                $this->db->group_start();
                $this->db->like("no_spk", $filter_search);
                $this->db->or_like("produk_nama", $filter_search);
                $this->db->group_end();
            }

            $spks = $this->db->get()->result_array();
            foreach ($spks as $s) {
                $s['tipe_spk'] = $tipeLabel;
                $allSpk[] = $s;
            }
        }

        // 6. Rekonsiliasi Setiap SPK
        $reconciled = array();
        $summary = array(
            'total_spk'            => count($allSpk),
            'total_qc'             => 0,
            'total_belum_qc'       => 0,
            'total_upah_qc'        => 0,
            'count_lunas_jasa'     => 0,
            'nominal_lunas_jasa'   => 0,
            'count_biaya_project'  => 0,
            'nominal_biaya_project'=> 0,
            'count_outstanding'    => 0,
            'nominal_outstanding'  => 0,
        );

        foreach ($allSpk as $spk) {
            $no_spk     = trim($spk['no_spk']);
            $unitNorm   = $this->normalizeString($spk['produk_nama']);
            $isQcDone   = ((int)$spk['progress_id'] === 3);
            $upahNilai  = isset($mapUpah[$no_spk]) ? $mapUpah[$no_spk] : 0;
            $spk['nilai_upah'] = $upahNilai;

            if ($isQcDone) {
                $summary['total_qc']++;
                $summary['total_upah_qc'] += $upahNilai;
            } else {
                $summary['total_belum_qc']++;
            }

            // A. Cek apakah ada di Jalur Jasa (463 -> 462)
            $matched463 = array();
            if ($unitNorm !== "" && strlen($unitNorm) >= 4) {
                foreach ($index463 as $idx) {
                    if (strpos($idx['norm_ket'], $unitNorm) !== false) {
                        $matched463[] = $idx['data'];
                    }
                }
            }
            // Jika belum match unit, cek jika no_spk tertulis di memo
            if (empty($matched463) && $no_spk !== "") {
                $cleanSpk = $this->normalizeString($no_spk);
                foreach ($index463 as $idx) {
                    if (strpos($idx['norm_ket'], $cleanSpk) !== false) {
                        $matched463[] = $idx['data'];
                    }
                }
            }

            // B. Cek apakah ada di Jalur Biaya Project (3674 -> 3675)
            $matched3674 = isset($map3674BySpk[$no_spk]) ? $map3674BySpk[$no_spk] : null;

            // Tentukan Status Rekonsiliasi
            $reconcile_status = "outstanding";
            $status_label     = "Outstanding (Belum Ditagih/Dibayar)";
            $status_badge     = "warning";

            if (!$isQcDone) {
                $reconcile_status = "belum_qc";
                $status_label     = "Pengerjaan (Belum QC)";
                $status_badge     = "default";
            } elseif (!empty($matched463)) {
                $reconcile_status = "lunas_jasa";
                $status_label     = "Lunas via Modul Jasa (463/462)";
                $status_badge     = "success";
                $summary['count_lunas_jasa']++;
                $summary['nominal_lunas_jasa'] += $upahNilai;
            } elseif (!empty($matched3674)) {
                $reconcile_status = "biaya_project";
                $status_label     = "Masuk Jalur Biaya Project (3674)";
                $status_badge     = "primary";
                $summary['count_biaya_project']++;
                $summary['nominal_biaya_project'] += (float)$matched3674['tagihan'];
            } else {
                $summary['count_outstanding']++;
                $summary['nominal_outstanding'] += $upahNilai;
            }

            $spk['reconcile_status'] = $reconcile_status;
            $spk['status_label']     = $status_label;
            $spk['status_badge']     = $status_badge;
            $spk['matched_463']       = $matched463;
            $spk['matched_3674']      = $matched3674;

            // Filter status jika dipilih
            if ($filter_status !== "all") {
                if ($spk['reconcile_status'] !== $filter_status) {
                    continue;
                }
            }

            // Filter periode jika dipilih (format YYYY-MM)
            if ($filter_periode !== "") {
                $qcMonth = !empty($spk['qc_dtime']) ? substr($spk['qc_dtime'], 0, 7) : "";
                if ($qcMonth !== $filter_periode) {
                    continue;
                }
            }

            $reconciled[] = $spk;
        }

        // Urutkan: Outstanding & Terkini di atas
        usort($reconciled, function ($a, $b) {
            return strcmp($b['qc_dtime'], $a['qc_dtime']);
        });

        // 7. Output Format
        if ($format === "json") {
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode(array(
                "summary" => $summary,
                "data"    => $reconciled,
            ));
            exit();
        }

        if ($format === "csv") {
            $this->exportCsv($reconciled);
            exit();
        }

        $this->renderHtmlView($summary, $reconciled, $vendorList, array(
            'vendor'  => $filter_vendor,
            'status'  => $filter_status,
            'tipe'    => $filter_tipe,
            'q'       => $filter_search,
            'periode' => $filter_periode,
        ));
    }

    /**
     * Detail Modal / AJAX untuk 1 SPK
     */
    public function detail()
    {
        $no_spk = isset($_GET['spk']) ? trim($_GET['spk']) : "";
        if ($no_spk === "") {
            echo json_encode(array("status" => 0, "message" => "Parameter spk kosong."));
            exit();
        }

        // Ambil Data SPK
        $this->db->where("no_spk", $no_spk);
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $spk = $this->db->get("project_tasklist")->row_array();
        $tipe = "Reguler";

        if (empty($spk)) {
            $this->db->where("no_spk", $no_spk);
            $this->db->where("status", 1);
            $this->db->where("trash", 0);
            $spk = $this->db->get("project_tasklist_tambahan")->row_array();
            $tipe = "Tambahan";
        }

        if (empty($spk)) {
            echo json_encode(array("status" => 0, "message" => "SPK tidak ditemukan."));
            exit();
        }

        // Rincian Biaya Upah Hak Vendor dari RAB Sub (QC Riil)
        $rabTbl = ($tipe === "Reguler") ? "project_komponen_biaya_details_rab_sub" : "project_komponen_biaya_details_rab_sub_tambahan";
        $this->db->select("biaya_nama, biaya_dasar_nama, jml, harga, debet, cat_nama");
        $this->db->where("no_spk", $no_spk);
        $this->db->where("jenis", "biaya");
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $komposisi = $this->db->get($rabTbl)->result_array();

        // Cari transaksi 463 yang cocok dengan unit
        $unitNorm = $this->normalizeString($spk['produk_nama']);
        $matched463 = array();

        $this->db->select("t.id, t.nomer, t.dtime, t.keterangan, ps.tagihan, ps.terbayar, ps.sisa");
        $this->db->from("transaksi t");
        $this->db->join("transaksi_payment_source ps", "ps.transaksi_id = t.id");
        $this->db->where("ps.jenis", "463");
        $this->db->where("ps.extern_id", $spk['employee_id']);
        $raw463 = $this->db->get()->result_array();

        foreach ($raw463 as $r) {
            $normKet = $this->normalizeString($r['keterangan']);
            if (strlen($unitNorm) >= 4 && strpos($normKet, $unitNorm) !== false) {
                // Ambil transfer 462
                $this->db->select("t.id, t.nomer, t.dtime, t.bank_nama, t.bank_rekening_nama, t.keterangan, td.produk_ord_hrg");
                $this->db->from("transaksi_data td");
                $this->db->join("transaksi t", "t.id = td.transaksi_id");
                $this->db->where("td.produk_id", (int)$r['id']);
                $this->db->where("t.jenis", "462");
                $this->db->where("t.status", 1);
                $this->db->where("t.trash", 0);
                $r['transfer_462'] = $this->db->get()->result_array();
                $matched463[] = $r;
            }
        }

        // Cari transaksi 3674
        $this->db->where("target_jenis", "3675");
        $this->db->where("extern4_nama", $no_spk);
        $matched3674 = $this->db->get("transaksi_payment_source")->row_array();

        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(array(
            "status"       => 1,
            "spk"          => $spk,
            "tipe"         => $tipe,
            "komposisi"    => $komposisi,
            "matched_463"  => $matched463,
            "matched_3674" => $matched3674,
        ));
        exit();
    }

    /**
     * Normalisasi string untuk pencocokan teks nama unit yang bebas tanda baca & spasi berlebih
     */
    private function normalizeString($str)
    {
        $str = strtoupper($str);
        $str = preg_replace('/[^A-Z0-9]/', ' ', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    }

    /**
     * Export Hasil Rekonsiliasi ke CSV
     */
    private function exportCsv($list)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=rekonsiliasi_spk_' . date('Ymd_His') . '.csv');
        $fp = fopen('php://output', 'w');

        // Header CSV
        fputcsv($fp, array(
            'No SPK',
            'Tipe SPK',
            'ID Vendor',
            'Nama Vendor',
            'Unit / Lokasi Proyek',
            'Status QC',
            'Tanggal QC',
            'Approver QC',
            'Upah Vendor (RAB Sub QC)',
            'Status Rekonsiliasi',
            'Bukti Pembayaran / Referensi',
            'Tanggal Bayar Kas/Bank',
            'Bank Sumber'
        ));

        foreach ($list as $r) {
            $buktiBayar = "-";
            $tglBayar   = "-";
            $bankSumber = "-";

            if (!empty($r['matched_463'])) {
                $f = $r['matched_463'][0];
                $buktiBayar = "Faktur 463: " . $f['nomer'];
                if (!empty($f['payments_462'])) {
                    $p = $f['payments_462'][0];
                    $buktiBayar .= " -> Transfer 462: " . $p['no_462'];
                    $tglBayar   = $p['tgl_462'];
                    $bankSumber = $p['bank_nama'] . " (" . $p['bank_rekening_nama'] . ")";
                }
            } elseif (!empty($r['matched_3674'])) {
                $buktiBayar = "Biaya Project 3674: " . $r['matched_3674']['nomer'];
                $tglBayar   = $r['matched_3674']['fulldate'];
            }

            fputcsv($fp, array(
                $r['no_spk'],
                $r['tipe_spk'],
                $r['employee_id'],
                $r['employee_nama'],
                $r['produk_nama'],
                ($r['progress_id'] == 3 ? 'QC Selesai' : 'Pengerjaan'),
                $r['qc_dtime'],
                $r['qc_auth_nama'],
                $r['nilai_upah'],
                $r['status_label'],
                $buktiBayar,
                $tglBayar,
                $bankSumber
            ));
        }

        fclose($fp);
        exit();
    }

    /**
     * Render UI HTML Bootstrap yang Modern & Responsif
     */
    private function renderHtmlView($summary, $list, $vendorList, $filters)
    {
        $base = base_url();
        $filterParams = http_build_query($filters);
        $urlCsv  = $base . "tools/SpkRekonsiliasi?format=csv&" . $filterParams;
        $urlJson = $base . "tools/SpkRekonsiliasi?format=json&" . $filterParams;
        $urlReset= $base . "tools/SpkRekonsiliasi";
        ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekonsiliasi Pembayaran SPK Vendor - Everest ERP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            padding-bottom: 60px;
        }
        .header-box {
            background: linear-gradient(135deg, #0d324d 0%, #7f5a83 100%);
            color: #fff;
            padding: 24px 28px;
            margin-bottom: 22px;
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        }
        .header-box h2 { margin: 0 0 6px 0; font-size: 23px; font-weight: 700; }
        .header-box p { margin: 0; opacity: 0.9; font-size: 13.5px; }
        .kpi-card {
            background: #fff;
            border-radius: 6px;
            padding: 16px 18px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 5px solid #ccc;
        }
        .kpi-card.success { border-left-color: #28a745; }
        .kpi-card.primary { border-left-color: #007bff; }
        .kpi-card.warning { border-left-color: #ffc107; }
        .kpi-card.info    { border-left-color: #17a2b8; }
        .kpi-card .number { font-size: 22px; font-weight: 700; margin: 4px 0; }
        .kpi-card .title  { font-size: 11px; text-transform: uppercase; color: #777; font-weight: 600; letter-spacing: 0.5px; }
        .kpi-card .subtext { font-size: 11.5px; color: #888; margin-top: 3px; }
        .panel-filter {
            background: #fff;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 18px 20px;
            margin-bottom: 22px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .table-container {
            background: #fff;
            border-radius: 6px;
            border: 1px solid #e1e4e8;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .table > tbody > tr > td { vertical-align: middle; font-size: 12.5px; }
        .table > thead > tr > th { font-size: 12px; text-transform: uppercase; background: #fafbfc; color: #555; }
        .badge-pill { border-radius: 12px; padding: 4px 9px; font-size: 11px; font-weight: 600; }
        .badge-success { background-color: #28a745; }
        .badge-primary { background-color: #007bff; }
        .badge-warning { background-color: #f39c12; }
        .badge-default { background-color: #777; }
        .memo-snippet {
            font-size: 11px;
            color: #555;
            background: #f8f9fa;
            padding: 4px 7px;
            border-radius: 4px;
            border-left: 3px solid #17a2b8;
            margin-top: 3px;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .modal-body dl { margin-bottom: 0; }
        .modal-body dt { color: #666; font-size: 12px; }
        .modal-body dd { font-size: 13px; margin-bottom: 8px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container-fluid" style="max-width: 1440px; margin: 0 auto; padding-top: 20px;">

    <!-- HEADER -->
    <div class="header-box">
        <div class="row">
            <div class="col-md-8">
                <h2><i class="fa fa-balance-scale"></i> Audit & Rekonsiliasi Pembayaran SPK Vendor</h2>
                <p>Menyandingkan Komitmen Upah SPK QC dengan Realisasi Pengeluaran Kas/Bank (Jalur Jasa 463/462 vs Jalur Biaya Project 3674/3675 vs Outstanding)</p>
            </div>
            <div class="col-md-4 text-right" style="padding-top: 10px;">
                <a href="<?php echo $base; ?>tools/SpkPaymentSource" class="btn btn-sm btn-info" target="_blank">
                    <i class="fa fa-external-link"></i> Buka Tool Penerbitan 3675
                </a>
                <a href="<?php echo $urlCsv; ?>" class="btn btn-sm btn-success">
                    <i class="fa fa-file-excel-o"></i> Export CSV
                </a>
                <a href="<?php echo $urlJson; ?>" class="btn btn-sm btn-warning" target="_blank">
                    <i class="fa fa-code"></i> JSON
                </a>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS -->
    <div class="row">
        <div class="col-md-3">
            <div class="kpi-card info">
                <div class="title"><i class="fa fa-tasks"></i> Total SPK Di-QC</div>
                <div class="number text-info"><?php echo number_format($summary['total_qc']); ?> <small style="font-size:14px; color:#777;">SPK</small></div>
                <div class="subtext">Total Upah Hak Vendor (QC): <strong>Rp <?php echo number_format($summary['total_upah_qc'], 0); ?></strong></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card success">
                <div class="title"><i class="fa fa-check-circle"></i> Lunas via Modul Jasa (463/462)</div>
                <div class="number text-success"><?php echo number_format($summary['count_lunas_jasa']); ?> <small style="font-size:14px; color:#777;">SPK</small></div>
                <div class="subtext">Tercatat di Faktur Jasa: <strong>Rp <?php echo number_format($summary['nominal_lunas_jasa'], 0); ?></strong></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card primary">
                <div class="title"><i class="fa fa-folder-open"></i> Jalur Biaya Project (3674)</div>
                <div class="number text-primary"><?php echo number_format($summary['count_biaya_project']); ?> <small style="font-size:14px; color:#777;">SPK</small></div>
                <div class="subtext">Tagihan Terbit 3674: <strong>Rp <?php echo number_format($summary['nominal_biaya_project'], 0); ?></strong></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card warning">
                <div class="title"><i class="fa fa-clock-o"></i> Outstanding (Belum Ditagih)</div>
                <div class="number text-warning"><?php echo number_format($summary['count_outstanding']); ?> <small style="font-size:14px; color:#777;">SPK</small></div>
                <div class="subtext">Estimasi Upah Belum Ditagih: <strong>Rp <?php echo number_format($summary['nominal_outstanding'], 0); ?></strong></div>
            </div>
        </div>
    </div>

    <!-- FILTER PANEL -->
    <div class="panel-filter">
        <form method="GET" action="<?php echo $base; ?>tools/SpkRekonsiliasi" class="form-inline">
            <div class="form-group" style="margin-right: 12px;">
                <label style="font-size: 12px; margin-right: 5px;">Pilih Vendor:</label>
                <select name="vendor" class="form-control input-sm" style="min-width: 220px;">
                    <option value="all" <?php echo ($filters['vendor'] === 'all') ? 'selected' : ''; ?>>-- Semua Vendor --</option>
                    <?php foreach ($vendorList as $v): ?>
                        <option value="<?php echo $v['employee_id']; ?>" <?php echo ($filters['vendor'] == $v['employee_id']) ? 'selected' : ''; ?>>
                            [ID: <?php echo $v['employee_id']; ?>] <?php echo htmlspecialchars($v['employee_nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-right: 12px;">
                <label style="font-size: 12px; margin-right: 5px;">Status Rekonsiliasi:</label>
                <select name="status" class="form-control input-sm">
                    <option value="all" <?php echo ($filters['status'] === 'all') ? 'selected' : ''; ?>>-- Semua Status --</option>
                    <option value="lunas_jasa" <?php echo ($filters['status'] === 'lunas_jasa') ? 'selected' : ''; ?>>Lunas via Modul Jasa (463/462)</option>
                    <option value="biaya_project" <?php echo ($filters['status'] === 'biaya_project') ? 'selected' : ''; ?>>Jalur Biaya Project (3674)</option>
                    <option value="outstanding" <?php echo ($filters['status'] === 'outstanding') ? 'selected' : ''; ?>>Outstanding (Belum Ditagih/Dibayar)</option>
                    <option value="belum_qc" <?php echo ($filters['status'] === 'belum_qc') ? 'selected' : ''; ?>>Belum QC (Masih Berjalan)</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 12px;">
                <label style="font-size: 12px; margin-right: 5px;">Tipe SPK:</label>
                <select name="tipe" class="form-control input-sm">
                    <option value="all" <?php echo ($filters['tipe'] === 'all') ? 'selected' : ''; ?>>Semua Tipe</option>
                    <option value="reguler" <?php echo ($filters['tipe'] === 'reguler') ? 'selected' : ''; ?>>Reguler Saja</option>
                    <option value="tambahan" <?php echo ($filters['tipe'] === 'tambahan') ? 'selected' : ''; ?>>Tambahan Saja</option>
                </select>
            </div>

            <div class="form-group" style="margin-right: 12px;">
                <input type="text" name="q" value="<?php echo htmlspecialchars($filters['q']); ?>" placeholder="Cari No SPK / Nama Unit..." class="form-control input-sm" style="width: 200px;">
            </div>

            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Terapkan Filter</button>
            <a href="<?php echo $urlReset; ?>" class="btn btn-sm btn-default" style="margin-left: 5px;"><i class="fa fa-refresh"></i> Reset</a>
        </form>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="table-container">
        <div class="row" style="margin-bottom: 12px;">
            <div class="col-md-6">
                <span style="font-size: 14px; font-weight: 700;">Daftar Hasil Rekonsiliasi SPK</span>
                <span class="badge badge-info" style="margin-left: 6px;"><?php echo number_format(count($list)); ?> Baris</span>
            </div>
            <div class="col-md-6 text-right">
                <span style="font-size: 11.5px; color: #777;">
                    <i class="fa fa-info-circle text-info"></i> SPK yang bertanda <span class="badge badge-success">Hijau</span> telah dibayarkan melalui modul Jasa (463 &rarr; 462). <strong>Dilarang menerbitkan 3675</strong> untuk mencegah pembayaran ganda.
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="35" class="text-center">No</th>
                        <th width="190">No SPK & Tipe</th>
                        <th width="180">Vendor / Pelaksana</th>
                        <th>Unit / Lokasi Proyek</th>
                        <th width="120" class="text-center">Tgl QC</th>
                        <th width="130" class="text-right">Upah Vendor (QC)</th>
                        <th width="160" class="text-center">Status Rekonsiliasi</th>
                        <th>Bukti Realisasi Pembayaran / Jalur Uang</th>
                        <th width="70" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 30px; color: #999;">
                            <i class="fa fa-folder-open-o" style="font-size: 30px; margin-bottom: 8px;"></i><br>
                            Tidak ada data SPK yang sesuai dengan kriteria filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($list as $r): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($r['no_spk']); ?></strong><br>
                                <span class="label <?php echo ($r['tipe_spk'] === 'Reguler') ? 'label-primary' : 'label-warning'; ?>" style="font-size: 9.5px;">
                                    <?php echo $r['tipe_spk']; ?>
                                </span>
                                <?php if (!empty($r['post_biaya_no'])): ?>
                                    <span class="label label-default" style="font-size: 9.5px; margin-left: 2px;" title="Terbit 3674r"><?php echo $r['post_biaya_no']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($r['employee_nama']); ?></strong><br>
                                <small class="text-muted">ID: <?php echo $r['employee_id']; ?></small>
                            </td>
                            <td>
                                <strong style="color: #2c3e50;"><?php echo htmlspecialchars($r['produk_nama']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($r['project_nama']); ?></small>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($r['qc_dtime'])): ?>
                                    <span style="font-size: 11.5px;"><?php echo date('d/m/Y', strtotime($r['qc_dtime'])); ?></span><br>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($r['qc_dtime'])); ?> by <?php echo htmlspecialchars($r['qc_auth_nama']); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <strong>Rp <?php echo number_format($r['nilai_upah'], 0); ?></strong>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-<?php echo $r['status_badge']; ?>">
                                    <?php echo $r['status_label']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($r['matched_463'])): ?>
                                    <?php foreach ($r['matched_463'] as $f): ?>
                                        <div>
                                            <i class="fa fa-file-text-o text-success"></i> <strong>Faktur Jasa: <?php echo $f['nomer']; ?></strong> (Rp <?php echo number_format($f['tagihan'], 0); ?>)
                                            <?php if (!empty($f['payments_462'])): ?>
                                                <br><i class="fa fa-money text-primary"></i> <small>Transfer <?php echo $f['payments_462'][0]['no_462']; ?> via <?php echo $f['payments_462'][0]['bank_nama']; ?> (<?php echo date('d/m/Y', strtotime($f['payments_462'][0]['tgl_462'])); ?>)</small>
                                            <?php endif; ?>
                                            <div class="memo-snippet" title="<?php echo htmlspecialchars($f['keterangan']); ?>">
                                                <?php echo htmlspecialchars($f['keterangan']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (!empty($r['matched_3674'])): ?>
                                    <div>
                                        <i class="fa fa-exchange text-primary"></i> <strong>3674: <?php echo $r['matched_3674']['nomer']; ?></strong>
                                        <br><small>Tagihan: Rp <?php echo number_format($r['matched_3674']['tagihan'], 0); ?> | Terbayar: Rp <?php echo number_format($r['matched_3674']['terbayar'], 0); ?></small>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size: 11.5px;">
                                        <i class="fa fa-hourglass-half text-warning"></i> Belum ada rekaman uang keluar di modul jasa / biaya project.
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-default btn-detail" data-spk="<?php echo htmlspecialchars($r['no_spk']); ?>" title="Lihat Rincian Lengkap">
                                    <i class="fa fa-eye text-primary"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAIL AUDIT -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #0d324d; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-search"></i> Rincian Audit SPK: <span id="modalSpkTitle">-</span></h4>
            </div>
            <div class="modal-body" id="modalBodyContent" style="padding: 20px;">
                <div class="text-center" style="padding: 40px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-detail').on('click', function() {
        var spk = $(this).data('spk');
        $('#modalSpkTitle').text(spk);
        $('#modalBodyContent').html('<div class="text-center" style="padding: 40px;"><i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data audit...</div>');
        $('#modalDetail').modal('show');

        $.ajax({
            url: '<?php echo $base; ?>tools/SpkRekonsiliasi/detail',
            type: 'GET',
            data: { spk: spk },
            dataType: 'json',
            success: function(res) {
                if (res.status === 1) {
                    var s = res.spk;
                    var html = '<div class="row">';
                    
                    // Kolom Kiri: Info SPK
                    html += '<div class="col-md-6">';
                    html += '<div class="well well-sm" style="background:#fff;">';
                    html += '<h5 style="margin-top:0; font-weight:700; color:#0d324d;"><i class="fa fa-info-circle"></i> Informasi SPK & Proyek</h5>';
                    html += '<dl class="dl-horizontal" style="margin-left:-20px;">';
                    html += '<dt>No SPK:</dt><dd>' + s.no_spk + ' (' + res.tipe + ')</dd>';
                    html += '<dt>Vendor:</dt><dd>' + s.employee_nama + ' [ID:' + s.employee_id + ']</dd>';
                    html += '<dt>Unit Proyek:</dt><dd>' + s.produk_nama + '</dd>';
                    html += '<dt>Nama Proyek:</dt><dd>' + (s.project_nama || '-') + '</dd>';
                    html += '<dt>Status QC:</dt><dd>' + (s.progress_id == 3 ? '<span class="label label-success">Selesai QC</span>' : '<span class="label label-default">Belum QC</span>') + '</dd>';
                    html += '<dt>Waktu QC:</dt><dd>' + (s.qc_dtime || '-') + ' by ' + (s.qc_auth_nama || '-') + '</dd>';
                    html += '</dl>';
                    html += '</div>';

                    // Rincian Biaya Upah Hak Vendor (QC / RAB Sub)
                    html += '<h5 style="font-weight:700;"><i class="fa fa-list"></i> Rincian Biaya Upah Hak Vendor (QC / RAB Sub):</h5>';
                    if (res.komposisi && res.komposisi.length > 0) {
                        html += '<table class="table table-condensed table-bordered">';
                        html += '<tr style="background:#f8f9fa;"><th>Biaya Upah</th><th class="text-center">Kategori</th><th class="text-center">Qty</th><th class="text-right">Harga</th><th class="text-right">Subtotal</th></tr>';
                        var tot = 0;
                        for (var i = 0; i < res.komposisi.length; i++) {
                            var k = res.komposisi[i];
                            var subt = parseFloat(k.debet) || 0;
                            tot += subt;
                            var namaBiaya = (k.biaya_nama || 'Biaya Upah');
                            if (k.biaya_dasar_nama) {
                                namaBiaya += ' <small class="text-muted">(' + k.biaya_dasar_nama + ')</small>';
                            }
                            html += '<tr><td>' + namaBiaya + '</td><td class="text-center"><span class="badge" style="font-size:10px;">' + (k.cat_nama || '-') + '</span></td><td class="text-center">' + k.jml + '</td><td class="text-right">Rp ' + Number(k.harga).toLocaleString('id-ID') + '</td><td class="text-right">Rp ' + Number(subt).toLocaleString('id-ID') + '</td></tr>';
                        }
                        html += '<tr style="background:#f1f3f5; font-weight:700;"><td colspan="4">Total Upah Hak Vendor</td><td class="text-right">Rp ' + Number(tot).toLocaleString('id-ID') + '</td></tr>';
                        html += '</table>';
                    } else {
                        html += '<p class="text-muted" style="font-size:12px;">Belum ada rincian upah yang disetujui (SPK belum QC atau belum ada komponen biaya disetujui).</p>';
                    }
                    html += '</div>';

                    // Kolom Kanan: Realisasi Uang Keluar
                    html += '<div class="col-md-6">';
                    html += '<h5 style="margin-top:0; font-weight:700; color:#28a745;"><i class="fa fa-money"></i> Realisasi di Modul Penerimaan Jasa (463 &rarr; 462):</h5>';
                    if (res.matched_463 && res.matched_463.length > 0) {
                        for (var j = 0; j < res.matched_463.length; j++) {
                            var m = res.matched_463[j];
                            html += '<div class="panel panel-success" style="margin-bottom:12px;">';
                            html += '<div class="panel-heading" style="padding:6px 12px; font-size:12px;"><strong>Faktur Jasa: ' + m.nomer + '</strong> (' + m.dtime + ')</div>';
                            html += '<div class="panel-body" style="padding:10px; font-size:12px;">';
                            html += '<div><strong>Total Faktur:</strong> Rp ' + Number(m.tagihan).toLocaleString('id-ID') + ' | <strong>Terbayar:</strong> Rp ' + Number(m.terbayar).toLocaleString('id-ID') + '</div>';
                            html += '<div style="margin-top:6px; background:#f8f9fa; padding:6px; border-radius:4px; font-size:11.5px; border-left:3px solid #28a745;"><strong>Memo Tagihan:</strong><br>' + (m.keterangan || '-').replace(/\\n/g, '<br>') + '</div>';
                            
                            if (m.transfer_462 && m.transfer_462.length > 0) {
                                html += '<div style="margin-top:8px;"><strong>Histori Transfer Bank (462):</strong>';
                                for (var t = 0; t < m.transfer_462.length; t++) {
                                    var tr = m.transfer_462[t];
                                    html += '<div style="font-size:11.5px; color:#0d324d; margin-top:2px;">• <strong>' + tr.no_462 + '</strong> | Tgl: ' + tr.tgl_462 + ' | Bank: ' + tr.bank_nama + ' (' + tr.bank_rekening_nama + ') | Ket: ' + (tr.keterangan || '-') + '</div>';
                                }
                                html += '</div>';
                            }
                            html += '</div></div>';
                        }
                    } else {
                        html += '<div class="alert alert-warning" style="font-size:12px;"><i class="fa fa-warning"></i> Tidak ditemukan transaksi Penerimaan Jasa (463) yang mencantumkan unit rumah ini.</div>';
                    }

                    // Status Jalur Biaya Project
                    html += '<h5 style="font-weight:700; color:#007bff;"><i class="fa fa-folder"></i> Status Jalur Biaya Project (3674 &rarr; 3675):</h5>';
                    if (res.matched_3674) {
                        var p3 = res.matched_3674;
                        html += '<div class="well well-sm" style="font-size:12px;">';
                        html += '<div><strong>No Payment Source:</strong> ' + p3.nomer + '</div>';
                        html += '<div><strong>Tagihan 3674:</strong> Rp ' + Number(p3.tagihan).toLocaleString('id-ID') + ' | <strong>Terbayar via 3675:</strong> Rp ' + Number(p3.terbayar).toLocaleString('id-ID') + '</div>';
                        html += '<div><strong>Status Lunas:</strong> ' + (p3.lunas == 1 ? 'Lunas' : 'Belum Lunas') + '</div>';
                        html += '</div>';
                    } else {
                        html += '<p class="text-muted" style="font-size:12px;">Tidak ada transaksi di payment source 3674/3675.</p>';
                    }

                    html += '</div>'; // End col-6
                    html += '</div>'; // End row
                    $('#modalBodyContent').html(html);
                } else {
                    $('#modalBodyContent').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
            },
            error: function() {
                $('#modalBodyContent').html('<div class="alert alert-danger">Gagal menghubungi server.</div>');
            }
        });
    });
});
</script>
</body>
</html>
        <?php
    }
}
// END OF COMPLETE REPEATED LOGIC
