<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
class ToolCheckUangMuka extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('MdlTransaksi');
        $this->load->helper('he_angka');
        $this->load->helper('url');
    }

    public function index()
    {
        $ajaxUrl = site_url('ToolCheckUangMuka/getDataAjax');
        $detailUrl = site_url('ToolCheckUangMuka/detailProjectAjax');

        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit & Deteksi Anomali Saldo Uang Muka Proyek (Target Jenis 04467)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <style>
        body { background-color: #f4f6f9; font-size: 13px; color: #333; }
        .card-header { font-weight: bold; }
        .stat-card { border-radius: 8px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.08); }
        .stat-icon { font-size: 2.2rem; opacity: 0.3; }
        .table td, .table th { vertical-align: middle; }
        .badge-urgent { background-color: #dc3545; color: #fff; font-size: 11px; }
        .badge-warning-custom { background-color: #fd7e14; color: #fff; font-size: 11px; }
        .badge-info-custom { background-color: #17a2b8; color: #fff; font-size: 11px; }
        .badge-normal { background-color: #28a745; color: #fff; font-size: 11px; }
        .money { text-align: right; font-family: "Courier New", Courier, monospace; font-weight: bold; }
        .table-hover tbody tr:hover { background-color: #f1f8ff; }
        .select2-container .select2-selection--single { height: 38px; border: 1px solid #ced4da; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    </style>
</head>
<body>
<div class="container-fluid py-4 px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary font-weight-bold"><i class="fas fa-search-dollar mr-2"></i>Audit Deteksi Anomali Saldo Uang Muka (Target Jenis: 04467)</h3>
            <p class="text-muted mb-0">Hanya mendeteksi proyek yang <strong>Uang Mukanya SUDAH DIGUNAKAN/LUNAS di Faktur DP</strong> tetapi baris <code>target_jenis: 04467</code> di <code>transaksi_payment_source</code> masih mencatat sisa saldo aktif.</p>
        </div>
        <div>
            <button id="btnReload" class="btn btn-outline-primary shadow-sm"><i class="fas fa-sync-alt mr-1"></i> Refresh Data</button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Total Proyek Terdeteksi</h6>
                        <h3 id="statTotalProject" class="font-weight-bold mb-0">0</h3>
                    </div>
                    <i class="fas fa-project-diagram stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">🚨 DP Terpakai tapi Saldo 04467 Aktif</h6>
                        <h3 id="statTotalUrgent" class="font-weight-bold mb-0">0</h3>
                    </div>
                    <i class="fas fa-exclamation-triangle stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">🔥 Total Saldo Semu 04467</h6>
                        <h3 id="statTotalSisaSemu" class="font-weight-bold mb-0">Rp 0</h3>
                    </div>
                    <i class="fas fa-ghost stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Saldo Buku Pembantu (2010050)</h6>
                        <h3 id="statTotalSaldoBuku" class="font-weight-bold mb-0">Rp 0</h3>
                    </div>
                    <i class="fas fa-book stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Audit Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="font-weight-bold text-muted small mb-1">Filter Customer:</label>
                    <select id="filterCustomer" class="form-control select2" style="width: 100%;">
                        <option value="">-- Semua Customer --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold text-muted small mb-1">Filter Tingkat Isu:</label>
                    <select id="filterIssue" class="form-control">
                        <option value="">-- Semua Kasus --</option>
                        <option value="URGENT">🚨 URGENT (DP Terpakai tapi Saldo 04467 Aktif)</option>
                        <option value="BUKU_GANTUNG">⚠️ Saldo Gantung di Buku Pembantu</option>
                        <option value="NORMAL">✅ Normal / Selaras</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold text-muted small mb-1">Filter Status Proyek:</label>
                    <select id="filterProjectStatus" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif">Aktif (Belum Closing)</option>
                        <option value="Closing">Sudah Closing</option>
                        <option value="Canceled">Canceled / Dihapus</option>
                    </select>
                </div>
                <div class="col-md-3 text-right pt-3">
                    <span class="badge badge-light border px-2 py-1"><i class="fas fa-info-circle text-primary"></i> Data dihitung silang presisi antara Realisasi Kasir & 04467</span>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tblAudit" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead class="thead-dark small">
                        <tr>
                            <th width="3%">#</th>
                            <th width="15%">Customer</th>
                            <th width="17%">Proyek</th>
                            <th width="10%">Tagihan DP (04467)</th>
                            <th width="10%">Realisasi DP (Kwitansi)</th>
                            <th width="10%">Sisa Saldo 04467</th>
                            <th width="10%">Saldo Rek. 2010050</th>
                            <th width="17%">Bukti Pemakaian DP / Faktur DP</th>
                            <th width="9%">Status Audit</th>
                            <th width="3%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="small"></tbody>
                    <tfoot class="bg-light font-weight-bold small">
                        <tr>
                            <th colspan="3" class="text-right">TOTAL:</th>
                            <th class="money" id="footTagihanDP">0</th>
                            <th class="money text-success" id="footRealisasiDP">0</th>
                            <th class="money text-danger" id="footSisaPym">0</th>
                            <th class="money text-info" id="footBuku">0</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Proyek -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-2"></i>Rincian Alur Transaksi Proyek</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalDetailContent">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><br>Memuat rincian transaksi...</div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $(".select2").select2({ placeholder: "-- Semua Customer --", allowClear: true });

    var table = $("#tblAudit").DataTable({
        "ajax": {
            "url": "' . $ajaxUrl . '",
            "type": "GET",
            "dataSrc": function(json) {
                if (!json || !json.kpi) {
                    return [];
                }
                $("#statTotalProject").text(json.kpi.total_project);
                $("#statTotalUrgent").text(json.kpi.total_urgent);
                $("#statTotalSisaSemu").text(json.kpi.total_sisa_semu_f);
                $("#statTotalSaldoBuku").text(json.kpi.total_saldo_buku_f);
                return json.data;
            },
            "error": function(xhr, error, thrown) {
                console.error("AJAX Error:", xhr.responseText);
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "customer_display" },
            { "data": "project_display" },
            { "data": "dp_alokasi", "className": "money", render: $.fn.dataTable.render.number(",", ".", 0, "") },
            { "data": "dp_realisasi", "className": "money text-success", render: function(d, t, r) {
                var html = $.fn.dataTable.render.number(",", ".", 0, "").display(d);
                if (r.kwitansi_no && r.kwitansi_no !== "-") {
                    html += "<br><small class=\"text-muted\">Kw: " + r.kwitansi_no + "</small>";
                }
                return html;
            }},
            { "data": "pym_sisa_display", "className": "money" },
            { "data": "buku_pembantu_kredit", "className": "money", render: function(d, t, r) {
                var html = $.fn.dataTable.render.number(",", ".", 0, "").display(d);
                if (d > 0) {
                    html = "<span class=\"text-info font-weight-bold\">" + html + "</span>";
                }
                return html;
            }},
            { "data": "penggunaan_display" },
            { "data": "status_badge" },
            { "data": "action_btn", "orderable": false }
        ],
        "order": [[8, "desc"], [5, "desc"]],
        "pageLength": 25,
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            var intVal = function (i) {
                return typeof i === "string" ? i.replace(/[\$,]/g, "")*1 : typeof i === "number" ? i : 0;
            };

            var totalAlokasiDP = api.column(3, { search: "applied" }).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);
            var totalRealisasiDP = api.column(4, { search: "applied" }).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);
            
            var apiData = api.rows({ search: "applied" }).data().toArray();
            var totalPymSisa = 0;
            apiData.forEach(function(item) {
                if (item.is_urgent) {
                    totalPymSisa += (item.pym_sisa ? item.pym_sisa : 0);
                }
            });

            var totalBuku = api.column(6, { search: "applied" }).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);

            $("#footTagihanDP").html($.fn.dataTable.render.number(",", ".", 0, "").display(totalAlokasiDP));
            $("#footRealisasiDP").html($.fn.dataTable.render.number(",", ".", 0, "").display(totalRealisasiDP));
            $("#footSisaPym").html($.fn.dataTable.render.number(",", ".", 0, "").display(totalPymSisa));
            $("#footBuku").html($.fn.dataTable.render.number(",", ".", 0, "").display(totalBuku));
        },
        "initComplete": function(settings, json) {
            if (!json || !json.data) return;
            var api = this.api();
            var select = $("#filterCustomer");
            var custMap = {};
            json.data.forEach(function(item) {
                if (item.customer_raw && !custMap[item.customer_raw]) {
                    custMap[item.customer_raw] = true;
                    select.append("<option value=\"" + item.customer_raw + "\">" + item.customer_raw + "</option>");
                }
            });
        }
    });

    $("#filterCustomer").on("change", function() {
        var val = $(this).val();
        table.column(1).search(val ? val : "", false, false).draw();
    });

    $("#filterIssue").on("change", function() {
        var val = $(this).val();
        table.column(8).search(val ? val : "", false, false).draw();
    });

    $("#filterProjectStatus").on("change", function() {
        var val = $(this).val();
        table.column(2).search(val ? val : "", false, false).draw();
    });

    $("#btnReload").on("click", function() {
        table.ajax.reload(null, false);
    });

    $(document).on("click", ".btn-view-detail", function() {
        var projectId = $(this).data("project-id");
        var customerId = $(this).data("customer-id");
        var soNomer = $(this).data("so-nomer");
        $("#modalDetail").modal("show");
        $("#modalDetailContent").html("<div class=\"text-center py-5\"><i class=\"fas fa-spinner fa-spin fa-2x text-primary\"></i><br>Memuat rincian proyek #" + projectId + "...</div>");
        $.ajax({
            url: "' . $detailUrl . '/" + projectId + "/" + customerId + "?so=" + encodeURIComponent(soNomer),
            type: "GET",
            success: function(res) {
                $("#modalDetailContent").html(res);
            },
            error: function() {
                $("#modalDetailContent").html("<div class=\"alert alert-danger\">Gagal memuat rincian transaksi proyek.</div>");
            }
        });
    });
});
</script>
</body>
</html>';
        echo $html;
    }

    public function getDataAjax()
    {
        try {
            // 1. Ambil seluruh data project_produk
            $this->db->select("p.id, p.nama, p.status, p.trash, p.closing_status, p.harga, p.quot_id, p.transaksi_id, p.project_start_nomer");
            $this->db->order_by("p.id", "desc");
            $projects = $this->db->get("project_produk p")->result_array();

            // 2. Ambil seluruh data payment_source
            $sqlPym = "SELECT * FROM `transaksi_payment_source` WHERE `project_id` > 0";
            $pymQuery = $this->db->query($sqlPym);
            $pymRows = $pymQuery ? $pymQuery->result_array() : array();
            $mapPym = array();
            $mapProjectCustomer = array();

            foreach ($pymRows as $r) {
                $pId = $r['project_id'];
                if (!isset($mapPym[$pId])) {
                    $mapPym[$pId] = array();
                }
                $mapPym[$pId][] = $r;
                if (!empty($r['extern_id'])) {
                    $mapProjectCustomer[$pId] = array(
                        'id' => $r['extern_id'],
                        'nama' => isset($r['extern_nama']) ? $r['extern_nama'] : ('Customer #' . $r['extern_id'])
                    );
                }
            }

            // 3. Ambil data saldo buku pembantu per transaksi_no (ST Number) dan per extern_id
            $mapBukuPerST = array();
            $mapBukuCust = array();
            if ($this->db->table_exists('__rek_pembantu_subcustomer__2010050')) {
                $sqlBuku = "SELECT extern_id, transaksi_no, debet, kredit, keterangan FROM `__rek_pembantu_subcustomer__2010050`";
                $bukuQuery = $this->db->query($sqlBuku);
                if ($bukuQuery) {
                    $bRows = $bukuQuery->result_array();
                    foreach ($bRows as $b) {
                        if (!empty($b['transaksi_no'])) {
                            if (!isset($mapBukuPerST[$b['transaksi_no']])) {
                                $mapBukuPerST[$b['transaksi_no']] = 0;
                            }
                            $mapBukuPerST[$b['transaksi_no']] += ((float)$b['kredit'] - (float)$b['debet']);
                        }
                        if (!isset($mapBukuCust[$b['extern_id']])) {
                            $mapBukuCust[$b['extern_id']] = 0;
                        }
                        $mapBukuCust[$b['extern_id']] += ((float)$b['kredit'] - (float)$b['debet']);
                    }
                }
            }

            $auditData = array();
            $no = 0;
            $totalProject = 0;
            $totalUrgent = 0;
            $totalSisaSemu = 0;
            $totalSaldoBuku = 0;

            foreach ($projects as $p) {
                $pId = $p['id'];
                $custInfo = isset($mapProjectCustomer[$pId]) ? $mapProjectCustomer[$pId] : array('id' => '-', 'nama' => 'Tanpa Customer');
                $custRaw = $custInfo['nama'] . ' (#' . $custInfo['id'] . ')';

                $soNomer = !empty($p['project_start_nomer']) ? $p['project_start_nomer'] : '';
                
                // Saldo Buku Pembantu
                $saldoBukuProyek = 0;
                if (!empty($soNomer) && isset($mapBukuPerST[$soNomer])) {
                    $saldoBukuProyek = abs($mapBukuPerST[$soNomer]);
                } else {
                    $saldoBukuProyek = isset($mapBukuCust[$custInfo['id']]) ? $mapBukuCust[$custInfo['id']] : 0;
                }

                // Cek keberadaan baris 04467 dan Faktur DP di payment_source proyek ini
                $has04467 = false;
                $row04467 = null;
                $hasFakturDP = false;
                $rowFakturDP = null;
                $isFakturDPLunas = false;
                $kwitansiNo = '-';
                $dpRealisasi = 0;

                if (isset($mapPym[$pId])) {
                    foreach ($mapPym[$pId] as $pym) {
                        $targetJ = isset($pym['target_jenis']) ? trim($pym['target_jenis']) : '';
                        $jenis = isset($pym['jenis']) ? trim($pym['jenis']) : '';
                        $key = isset($pym['_key']) ? trim($pym['_key']) : '';

                        // Baris Uang Muka 04467
                        if ($targetJ === '04467' || $targetJ === '4467' || $jenis === '4467') {
                            $has04467 = true;
                            $row04467 = $pym;
                            $kwitansiNo = $pym['nomer'];
                            $dpRealisasi = (float)$pym['tagihan'];
                            if (isset($pym['ppn']) && (float)$pym['ppn'] > 0) {
                                $dpRealisasi += (float)$pym['ppn'];
                            }
                        }

                        // Baris Faktur DP (jenis 7499 dengan key dp atau target 749)
                        if ($key === 'dp' || ($jenis === '7499' && stripos($pym['label'], 'uang muka') !== false)) {
                            $hasFakturDP = true;
                            $rowFakturDP = $pym;
                            if ((float)$pym['terbayar'] > 0 || (float)$pym['sisa'] < 100) {
                                $isFakturDPLunas = true;
                            }
                        }
                    }
                }

                if (!$has04467 && $saldoBukuProyek <= 0) {
                    continue;
                }

                $totalProject++;

                $dpTagihan04467 = $row04467 ? (float)$row04467['tagihan'] : 0;
                $sisa04467 = $row04467 ? (float)$row04467['sisa'] : 0;

                // Format Bukti Penggunaan DP
                $penggunaanHtml = '-';
                if ($hasFakturDP && $rowFakturDP) {
                    $statusFakturDP = $isFakturDPLunas ? '<span class="badge badge-success">LUNAS</span>' : '<span class="badge badge-warning">BELUM LUNAS</span>';
                    $penggunaanHtml = '<div><strong>' . htmlspecialchars($rowFakturDP['nomer']) . '</strong> <span class="badge badge-primary">Faktur DP</span> ' . $statusFakturDP . '<br>';
                    $penggunaanHtml .= '<small class="text-muted">Terbayar: Rp ' . number_format($rowFakturDP['terbayar'], 0, '.', ',') . ' | Sisa: Rp ' . number_format($rowFakturDP['sisa'], 0, '.', ',') . '</small></div>';
                } elseif ($has04467 && $row04467) {
                    $penggunaanHtml = '<small class="text-muted"><i class="fas fa-receipt"></i> Kwitansi: ' . htmlspecialchars($row04467['nomer']) . ' (' . date('d/m/Y', strtotime($row04467['dtime'])) . ')</small>';
                }

                $isUrgent = false;
                $isBukuGantung = false;
                $statusBadge = '';
                $issueCode = 'NORMAL';

                // KRITERIA MUTLAK ANOMALI 04467:
                // Uang Muka 04467 masih ada saldo (sisa > 100) DAN Faktur DP sudah lunas / sudah digunakan di kasir!
                if ($has04467 && $sisa04467 > 100 && $isFakturDPLunas) {
                    $isUrgent = true;
                    $issueCode = 'URGENT';
                    $statusBadge = '<span class="badge badge-urgent px-2 py-1"><i class="fas fa-exclamation-triangle"></i> URGENT (Double Claim Risk)</span><br><small class="text-danger font-weight-bold">04467 Sisa Rp ' . number_format($sisa04467, 0, '.', ',') . '</small>';
                    $totalUrgent++;
                    $totalSisaSemu += $sisa04467;
                }
                elseif ($saldoBukuProyek > 1000) {
                    $isBukuGantung = true;
                    $issueCode = 'BUKU_GANTUNG';
                    $statusBadge = '<span class="badge badge-warning-custom px-2 py-1"><i class="fas fa-book"></i> Saldo Gantung Buku Pembantu</span><br><small class="text-muted">Kredit Rek. 2010050: Rp ' . number_format($saldoBukuProyek, 0, '.', ',') . '</small>';
                    $totalSaldoBuku += $saldoBukuProyek;
                }
                else {
                    $statusBadge = '<span class="badge badge-normal px-2 py-1"><i class="fas fa-check-circle"></i> Normal / Selaras</span>';
                }

                $projStatusLabel = '';
                if ($p['trash'] == 1) {
                    $projStatusLabel = '<span class="badge badge-secondary">Canceled</span>';
                } elseif ($p['closing_status'] == 1) {
                    $projStatusLabel = '<span class="badge badge-dark">Closing</span>';
                } else {
                    $projStatusLabel = '<span class="badge badge-success">Aktif</span>';
                }

                $pymSisaHtml = number_format($sisa04467, 0, '.', ',');
                if ($isUrgent) {
                    $pymSisaHtml = '<span class="text-danger font-weight-bold">' . $pymSisaHtml . '</span><br><small class="text-muted">Target: <code>04467</code></small>';
                } else {
                    $pymSisaHtml = '<span>' . $pymSisaHtml . '</span>';
                }

                $no++;
                $auditData[] = array(
                    'no'                    => $no,
                    'project_id'            => $pId,
                    'customer_id'           => $custInfo['id'],
                    'customer_raw'          => $custRaw,
                    'customer_display'      => '<span class="font-weight-bold text-dark">' . htmlspecialchars($custInfo['nama']) . '</span><br><small class="text-muted">ID: #' . $custInfo['id'] . '</small>',
                    'project_display'       => '<strong>' . htmlspecialchars($p['nama']) . '</strong><br><small class="text-muted">ID: #' . $pId . ' | ST: ' . ($p['project_start_nomer'] ? $p['project_start_nomer'] : '-') . '</small> ' . $projStatusLabel,
                    'dp_alokasi'            => $dpTagihan04467 > 0 ? $dpTagihan04467 : $dpRealisasi,
                    'dp_realisasi'          => $dpRealisasi,
                    'kwitansi_no'           => $kwitansiNo,
                    'pym_sisa'              => $sisa04467,
                    'pym_sisa_display'      => $pymSisaHtml,
                    'buku_pembantu_kredit'  => $saldoBukuProyek,
                    'penggunaan_display'    => $penggunaanHtml,
                    'is_urgent'             => $isUrgent,
                    'issue_code'            => $issueCode,
                    'status_badge'          => $statusBadge . '<span class="d-none">' . $issueCode . ' ' . ($p['closing_status'] == 1 ? 'Closing' : ($p['trash'] == 1 ? 'Canceled' : 'Aktif')) . '</span>',
                    'action_btn'            => '<button class="btn btn-xs btn-outline-info btn-view-detail" data-project-id="' . $pId . '" data-customer-id="' . $custInfo['id'] . '" data-so-nomer="' . htmlspecialchars($soNomer) . '" title="Lihat Rincian"><i class="fas fa-eye"></i></button>'
                );
            }

            $output = array(
                'kpi' => array(
                    'total_project'         => $totalProject,
                    'total_urgent'          => $totalUrgent,
                    'total_sisa_semu'       => $totalSisaSemu,
                    'total_sisa_semu_f'     => 'Rp ' . number_format($totalSisaSemu, 0, '.', ','),
                    'total_saldo_buku'      => $totalSaldoBuku,
                    'total_saldo_buku_f'    => 'Rp ' . number_format($totalSaldoBuku, 0, '.', ',')
                ),
                'data' => $auditData
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } catch (Exception $e) {
            $output = array(
                'error' => $e->getMessage(),
                'kpi' => array('total_project' => 0, 'total_urgent' => 0, 'total_sisa_semu' => 0, 'total_sisa_semu_f' => 'Rp 0', 'total_saldo_buku' => 0, 'total_saldo_buku_f' => 'Rp 0'),
                'data' => array()
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function detailProjectAjax($projectId = 0, $customerId = 0)
    {
        $projectId = (int)$projectId;
        $customerId = (int)$customerId;
        $soNomer = isset($_GET['so']) ? $this->input->get('so', TRUE) : '';

        $this->db->where('id', $projectId);
        $proj = $this->db->get('project_produk')->row_array();

        // 1. Ambil Payment Source
        $this->db->where('project_id', $projectId);
        $pym = $this->db->get('transaksi_payment_source')->result_array();

        // 2. Ambil seluruh transaksi kwitansi 4467 dan faktur 7499 milik customer ini
        $this->db->where('customers_id', $customerId);
        $this->db->where_in('jenis', array('4467', '7499', '749'));
        $this->db->order_by('id', 'ASC');
        $custAllTrx = $this->db->get('transaksi')->result_array();

        $kwitansiList = array();
        $fakturList = array();
        $pelunasanList = array();

        foreach ($custAllTrx as $t) {
            if ($t['jenis'] === '4467') {
                $kwitansiList[] = $t;
            } elseif ($t['jenis'] === '7499') {
                $fakturList[] = $t;
            } elseif ($t['jenis'] === '749') {
                $pelunasanList[] = $t;
            }
        }

        // 3. Analisis metode pembayaran setiap transaksi 749 (Kas/Bank vs Potong Uang Muka Non-Kas)
        $pelunasanMethodMap = array();
        foreach ($pelunasanList as $pTrx) {
            $tId = $pTrx['id'];
            $isNonKasUm = false;
            $methodDetail = "Kas / Bank Masuk";

            // Cek elemen pembayaran
            $this->db->where('transaksi_id', $tId);
            $elemRows = $this->db->get('transaksi_element')->result_array();
            $cashAccId = '';
            foreach ($elemRows as $er) {
                if ($er['name'] === 'cash_account') {
                    $cashAccId = (string)$er['key'];
                }
                if ($er['name'] === 'paymentMethod' && ($er['key'] === 'potong_uang_muka' || $er['key'] === 'uang_muka_proyek')) {
                    $isNonKasUm = true;
                }
            }

            $ketLower = strtolower($pTrx['keterangan']);
            if (strpos($ketLower, 'potong uang muka') !== false || strpos($ketLower, 'potong dp') !== false || strpos($ketLower, 'uang muka proyek') !== false) {
                $isNonKasUm = true;
                $methodDetail = "Potong Uang Muka Proyek (Non-Kas)";
            } elseif (strpos($ketLower, 'bca') !== false || $cashAccId === '1160') {
                $methodDetail = "Transfer BCA (8830713132) [Uang Masuk Baru]";
            } elseif (strpos($ketLower, 'mandiri') !== false) {
                $methodDetail = "Transfer Bank Mandiri [Uang Masuk Baru]";
            } elseif ($cashAccId !== '') {
                $methodDetail = "Kas / Bank Masuk (Akun #" . $cashAccId . ") [Uang Masuk Baru]";
            }

            $pelunasanMethodMap[$tId] = array(
                'is_non_kas_um' => $isNonKasUm,
                'method_label'  => $methodDetail,
                'nominal'       => (float)(isset($pTrx['transaksi_nilai']) && $pTrx['transaksi_nilai'] > 0 ? $pTrx['transaksi_nilai'] : (isset($pTrx['transaksi_net']) ? $pTrx['transaksi_net'] : 0)),
                'trx'           => $pTrx
            );
        }

        // 4. Hitung Rekonsiliasi Kwitansi 4467
        $totalDpMasuk = 0;
        $totalDipotongKeFaktur = 0;
        $kwitansiRecon = array();

        foreach ($kwitansiList as $kw) {
            $kwNominal = (float)(isset($kw['transaksi_nilai']) && $kw['transaksi_nilai'] > 0 ? $kw['transaksi_nilai'] : (isset($kw['transaksi_net']) ? $kw['transaksi_net'] : 0));
            $totalDpMasuk += $kwNominal;
            $kwitansiRecon[$kw['id']] = array(
                'trx'             => $kw,
                'nilai_diterima'  => $kwNominal,
                'nilai_digunakan' => 0,
                'nilai_batal'     => 0,
                'sisa_saldo'      => $kwNominal,
                'alokasi_note'    => '-'
            );
        }

        // 5. Alokasikan HANYA transaksi pelunasan NON-KAS ke Kwitansi 4467
        foreach ($pelunasanMethodMap as $pIdKey => $pInfo) {
            if ($pInfo['is_non_kas_um']) {
                $potongSisa = $pInfo['nominal'];
                $totalDipotongKeFaktur += $potongSisa;
                foreach ($kwitansiRecon as $kwId => &$kwData) {
                    if ($kwData['sisa_saldo'] > 0 && $potongSisa > 0) {
                        $pakai = min($kwData['sisa_saldo'], $potongSisa);
                        $kwData['nilai_digunakan'] += $pakai;
                        $kwData['sisa_saldo'] -= $pakai;
                        $potongSisa -= $pakai;
                        $kwData['alokasi_note'] = 'Dipakai di Faktur Termin (Non-Kas)';
                    }
                }
            }
        }
        unset($kwData);

        $totalSisaSaldoDp = $totalDpMasuk - $totalDipotongKeFaktur;
        if ($totalSisaSaldoDp < 0) {
            $totalSisaSaldoDp = 0;
        }

        // 6. RENDER HTML INTERAKTIF
        $html = '<div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><td width="35%" class="text-muted">Nama Proyek:</td><td class="font-weight-bold">' . (isset($proj['nama']) ? htmlspecialchars($proj['nama']) : '-') . '</td></tr>
                    <tr><td class="text-muted">Project ID:</td><td>#' . $projectId . '</td></tr>
                    <tr><td class="text-muted">Customer ID:</td><td>Cust #' . $customerId . '</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><td width="35%" class="text-muted">Nilai Kontrak Proyek:</td><td class="font-weight-bold text-primary">Rp ' . (isset($proj['harga']) ? number_format($proj['harga'], 0, '.', ',') : '0') . '</td></tr>
                    <tr><td class="text-muted">No. Mulai (ST):</td><td>' . (isset($proj['project_start_nomer']) ? $proj['project_start_nomer'] : '-') . '</td></tr>
                    <tr><td class="text-muted">Ref. SO:</td><td>' . (!empty($soNomer) ? $soNomer : '-') . '</td></tr>
                </table>
            </div>
        </div>';

        // ALUR REKONSILIASI PENERIMAAN UANG MUKA (HEADER BOX)
        $html .= '<div class="recon-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-exchange-alt mr-2"></i>ALUR REKONSILIASI PENERIMAAN UANG MUKA:</h6>
                <div class="d-flex align-items-center">
                    <div class="recon-val-card mr-2">
                        <small class="text-muted d-block text-uppercase" style="font-size:10px;">Total DP Masuk</small>
                        <span class="text-success font-weight-bold" style="font-size:14px;">Rp ' . number_format($totalDpMasuk, 0, '.', ',') . '</span>
                    </div>
                    <div class="recon-step-arrow mr-2"><i class="fas fa-arrow-right"></i></div>
                    <div class="recon-val-card mr-2">
                        <small class="text-muted d-block text-uppercase" style="font-size:10px;">Dipotong ke Faktur (Non-Kas)</small>
                        <span class="text-primary font-weight-bold" style="font-size:14px;">Rp ' . number_format($totalDipotongKeFaktur, 0, '.', ',') . '</span>
                    </div>
                    <div class="recon-step-arrow mr-2"><i class="fas fa-arrow-right"></i></div>
                    <div class="recon-val-card">
                        <small class="text-muted d-block text-uppercase" style="font-size:10px;">Sisa Saldo Titipan/DP</small>
                        <span class="text-danger font-weight-bold" style="font-size:14px;">Rp ' . number_format($totalSisaSaldoDp, 0, '.', ',') . '</span>
                    </div>
                </div>
            </div>';

        // TABEL 1: Bukti Kwitansi Penerimaan Uang Muka (4467)
        $html .= '<h6 class="font-weight-bold text-dark mt-3 mb-2"><i class="fas fa-file-invoice-dollar text-info mr-1"></i> Bukti Kwitansi Penerimaan Uang Muka (4467):</h6>';
        $html .= '<div class="table-responsive mb-3"><table class="table table-sm table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Tgl Terima</th>
                    <th>No. Kwitansi</th>
                    <th>Petugas Kasir</th>
                    <th class="text-right">Nilai Diterima</th>
                    <th class="text-right">Nilai Digunakan (A/R)</th>
                    <th class="text-right">Nilai Dibatalkan</th>
                    <th class="text-right">Sisa Saldo DP</th>
                </tr>
            </thead><tbody>';

        if (count($kwitansiRecon) > 0) {
            foreach ($kwitansiRecon as $kRow) {
                $kwTrx = $kRow['trx'];
                $sisaClass = $kRow['sisa_saldo'] > 0 ? 'text-danger font-weight-bold' : 'text-success';
                $html .= '<tr>
                    <td>' . date('Y-m-d H:i:s', strtotime($kwTrx['dtime'])) . '</td>
                    <td><strong>' . htmlspecialchars($kwTrx['nomer']) . '</strong> <span class="badge badge-info">Khusus Proyek #' . $projectId . '</span></td>
                    <td>' . (!empty($kwTrx['oleh_nama']) ? htmlspecialchars($kwTrx['oleh_nama']) : ('User #' . $kwTrx['oleh_id'])) . '</td>
                    <td class="money text-dark">Rp ' . number_format($kRow['nilai_diterima'], 0, '.', ',') . '</td>
                    <td class="money text-primary">Rp ' . number_format($kRow['nilai_digunakan'], 0, '.', ',') . '<br><small class="text-muted">' . $kRow['alokasi_note'] . '</small></td>
                    <td class="money text-muted">-</td>
                    <td class="money ' . $sisaClass . '">Rp ' . number_format($kRow['sisa_saldo'], 0, '.', ',') . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="7" class="text-center text-muted">Belum ada bukti kwitansi uang muka 4467 untuk proyek ini.</td></tr>';
        }
        $html .= '</tbody></table></div>';

        // TABEL 2: Daftar Faktur Penagihan & Bukti Pelunasan (A/R)
        $html .= '<h6 class="font-weight-bold text-dark mt-4 mb-2"><i class="fas fa-receipt text-success mr-1"></i> Daftar Faktur Penagihan & Bukti Pelunasan (A/R):</h6>';
        $html .= '<div class="table-responsive mb-3"><table class="table table-sm table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th width="8%">Tipe Faktur</th>
                    <th width="15%">No. Faktur</th>
                    <th width="12%" class="text-right">Nilai Faktur</th>
                    <th width="12%" class="text-right">Pelunasan</th>
                    <th width="12%" class="text-right">Sisa Piutang</th>
                    <th width="15%">Status</th>
                    <th width="26%">Bukti Pelunasan (A/R) & Rincian Metode</th>
                </tr>
            </thead><tbody>';

        if (count($fakturList) > 0) {
            foreach ($fakturList as $fRow) {
                $fNilai = (float)(isset($fRow['transaksi_nilai']) && $fRow['transaksi_nilai'] > 0 ? $fRow['transaksi_nilai'] : (isset($fRow['transaksi_net']) ? $fRow['transaksi_net'] : 0));
                
                // Cari pelunasan 749 yang terhubung ke faktur ini
                $fPelunasanTotal = 0;
                $fPelunasanHtml = '';

                foreach ($pelunasanMethodMap as $pIdKey => $pInfo) {
                    $pTrx = $pInfo['trx'];
                    $idsRefRaw = isset($pTrx['ids_ref']) ? blobDecode($pTrx['ids_ref']) : array();
                    $isLinked = false;
                    if (is_array($idsRefRaw) && (in_array($fRow['id'], $idsRefRaw) || in_array((string)$fRow['id'], $idsRefRaw))) {
                        $isLinked = true;
                    } elseif (strpos($pTrx['keterangan'], $fRow['nomer']) !== false) {
                        $isLinked = true;
                    }

                    if ($isLinked) {
                        $fPelunasanTotal += $pInfo['nominal'];
                        $badgeMethod = $pInfo['is_non_kas_um'] 
                            ? '<span class="badge badge-primary">Potong Uang Muka (Non-Kas)</span>' 
                            : '<span class="badge badge-success">Transfer Bank (Uang Masuk Baru)</span>';
                        $fPelunasanHtml .= '<div class="border rounded p-1 mb-1 bg-white">
                            <strong>' . htmlspecialchars($pTrx['nomer']) . '</strong>: <span class="font-weight-bold">Rp ' . number_format($pInfo['nominal'], 0, '.', ',') . '</span> ' . $badgeMethod . '<br>
                            <small class="text-muted"><i class="fas fa-info-circle"></i> ' . $pInfo['method_label'] . '</small>
                        </div>';
                    }
                }

                $fSisa = $fNilai - $fPelunasanTotal;
                if ($fSisa < 0) {
                    $fSisa = 0;
                }

                $tipeLabel = (stripos($fRow['keterangan'], 'dp') !== false || stripos($fRow['nomer'], 'dp') !== false) 
                    ? '<span class="badge badge-primary">DP</span>' 
                    : '<span class="badge badge-secondary">TERMIN</span>';

                $statusFaktur = ($fSisa < 100 && $fPelunasanTotal > 0)
                    ? '<span class="badge badge-success">LUNAS</span>'
                    : '<span class="badge badge-warning">BELUM LUNAS / MENUNGGU SETTLEMENT</span>';

                $html .= '<tr>
                    <td>' . $tipeLabel . '</td>
                    <td><strong>' . htmlspecialchars($fRow['nomer']) . '</strong></td>
                    <td class="money">Rp ' . number_format($fNilai, 0, '.', ',') . '</td>
                    <td class="money text-success">Rp ' . number_format($fPelunasanTotal, 0, '.', ',') . '</td>
                    <td class="money ' . ($fSisa > 0 ? 'text-danger font-weight-bold' : '') . '">Rp ' . number_format($fSisa, 0, '.', ',') . '</td>
                    <td>' . $statusFaktur . '</td>
                    <td>' . ($fPelunasanHtml !== '' ? $fPelunasanHtml : '<small class="text-muted">- Belum ada pelunasan -</small>') . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="7" class="text-center text-muted">Belum ada faktur penagihan 7499 untuk customer ini.</td></tr>';
        }

        $html .= '</tbody></table></div>';
        $html .= '</div>'; // End recon-box

        // Section 3: Payment Source (TPS)
        $html .= '<h6 class="font-weight-bold text-dark border-bottom pb-2 mt-4"><i class="fas fa-layer-group text-primary mr-1"></i> Catatan Transaksi Payment Source (TPS)</h6>';
        $html .= '<div class="table-responsive mb-3"><table class="table table-sm table-bordered table-striped">
            <thead class="thead-light">
                <tr><th>ID</th><th>Key</th><th>Jenis</th><th>Target</th><th>Tagihan</th><th>Terbayar</th><th>Sisa</th><th>Status</th></tr>
            </thead><tbody>';
        if (count($pym) > 0) {
            foreach ($pym as $pRow) {
                $isTarget = (isset($pRow['target_jenis']) && ($pRow['target_jenis'] === '04467' || $pRow['target_jenis'] === '4467'));
                $hasGhostBalance = ($isTarget && isset($pRow['sisa']) && (float)$pRow['sisa'] > 0);
                $diag = $hasGhostBalance ? '<span class="badge badge-danger">Sisa Aktif</span>' : '<span class="badge badge-success">Lunas</span>';

                $html .= '<tr>
                    <td>' . $pRow['id'] . '</td>
                    <td><code>' . (isset($pRow['_key']) ? $pRow['_key'] : '-') . '</code></td>
                    <td>' . (isset($pRow['jenis']) ? $pRow['jenis'] : '-') . '</td>
                    <td><span class="badge ' . ($isTarget ? 'badge-primary' : 'badge-secondary') . '">' . (isset($pRow['target_jenis']) ? $pRow['target_jenis'] : '-') . '</span></td>
                    <td class="money">' . (isset($pRow['tagihan']) ? number_format($pRow['tagihan'], 0, '.', ',') : 0) . '</td>
                    <td class="money">' . (isset($pRow['terbayar']) ? number_format($pRow['terbayar'], 0, '.', ',') : 0) . '</td>
                    <td class="money ' . (isset($pRow['sisa']) && $pRow['sisa'] > 0 ? 'text-danger font-weight-bold' : '') . '">' . (isset($pRow['sisa']) ? number_format($pRow['sisa'], 0, '.', ',') : 0) . '</td>
                    <td>' . $diag . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="8" class="text-center text-muted">Tidak ada data payment source.</td></tr>';
        }
        $html .= '</tbody></table></div>';

        echo $html;
    }
}
// END OF COMPLETE REPEATED LOGIC
