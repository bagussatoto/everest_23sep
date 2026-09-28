<!-- START OF COMPLETE REPEATED LOGIC -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "Backorder Cockpit"; ?></title>
    
    <!-- Bootstrap 3.3.7 & AdminLTE 2.4 CSS (Loaded from CDN) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.18/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">

    <!-- jQuery 3.7.0, Bootstrap JS & DataTables JS (Loaded from CDN) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>

    <script>
        var currentViewMode = 'nota';
        var jenisTr = '<?php echo isset($jenisTr) ? $jenisTr : "5822"; ?>';
        var rawRowsData = <?php echo isset($rawRowsJson) ? $rawRowsJson : "[]"; ?>;
        var dtInstance = null;

        function toggleGroup(groupId) {
            var rows = document.getElementsByClassName(groupId);
            for (var i = 0; i < rows.length; i++) {
                if (rows[i].style.display === "none") {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

        function switchViewMode(mode) {
            currentViewMode = mode;
            renderMatrixTable(rawRowsData);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function renderMatrixTable(rows) {
            if (dtInstance) {
                dtInstance.destroy();
                dtInstance = null;
            }

            var thead = document.getElementById('thead-matrix');
            var tbody = document.getElementById('tbody-matrix');
            if (!thead || !tbody) return;

            var headerHtml = '';
            var html = '';

            if (!rows || rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="14" class="text-center text-muted" style="padding: 25px;">Tidak ada data backorder outstanding.</td></tr>';
                return;
            }

            if (currentViewMode === 'nota') {
                // ------------------------------------------------------------------
                // Mode Datar Per-Nota: 1 Kolom = 1 Data Murni (Lengkap Stok Booking Kolom Terpisah)
                // ------------------------------------------------------------------
                headerHtml = '<tr style="background-color: #f8fafc; color: #334155; border-bottom: 2px solid #cbd5e1;">' +
                             '<th style="width: 3%; text-align: center;">No</th>' +
                             '<th style="width: 10%;">Date</th>' +
                             '<th style="width: 11%;">SO Number</th>' +
                             '<th style="width: 9%;">Branch</th>' +
                             '<th style="width: 11%;">Customer</th>' +
                             '<th style="width: 9%;">SKU</th>' +
                             '<th style="width: 13%;">Nama Produk</th>' +
                             '<th style="width: 5%; text-align: right;">Qty Order</th>' +
                             '<th style="width: 5%; text-align: right;">Outstanding</th>' +
                             '<th style="width: 6%; text-align: center;">Stok Fisik</th>' +
                             '<th style="width: 6%; text-align: center;">Stok Booking</th>' +
                             '<th style="width: 6%; text-align: center;">Stok Bebas</th>' +
                             '<th style="width: 8%; text-align: center;">Stok Perusahaan</th>' +
                             '<th style="width: 4%; text-align: center;">Aging</th>' +
                             '</tr>';

                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var slaTier = "TIER_3_NORMAL";
                    var aging = parseInt(row.aging_days) || 0;
                    if (aging > 14) slaTier = "TIER_1_PLATINUM";
                    else if (aging > 7) slaTier = "TIER_2_GOLD";

                    var badgeClass = 'badge-sla-normal';
                    if (slaTier === 'TIER_1_PLATINUM') badgeClass = 'badge-sla-platinum';
                    else if (slaTier === 'TIER_2_GOLD') badgeClass = 'badge-sla-gold';

                    var stokCabangFisik = parseInt(row.stok_cabang_fisik) || 0;
                    var stokCabangBooking = parseInt(row.stok_cabang_booking) || 0;
                    var stokCabangNet = parseInt(row.stok_cabang_net) || 0;

                    var stokPerusahaanNet = parseInt(row.stok_perusahaan_net) || 0;
                    var stokPerusahaanFisik = parseInt(row.stok_perusahaan_fisik) || 0;

                    var outstanding = parseInt(row.outstanding) || 0;

                    var badgeNet = stokCabangNet >= outstanding 
                        ? '<span class="badge" style="background-color:#10b981; font-size:11px;">' + stokCabangNet + ' unit</span>'
                        : (stokCabangNet > 0 
                            ? '<span class="badge" style="background-color:#f59e0b; color:#000; font-size:11px;">' + stokCabangNet + ' unit</span>' 
                            : '<span class="badge" style="background-color:#ef4444; font-size:11px;">0 unit</span>');

                    var badgeBooking = stokCabangBooking > 0
                        ? '<span class="badge" style="background-color:#f97316; font-size:11px;">' + stokCabangBooking + ' unit</span>'
                        : '<span class="badge" style="background-color:#94a3b8; font-size:11px;">0 unit</span>';

                    var badgePerusahaan = stokPerusahaanNet >= outstanding 
                        ? '<span class="badge" style="background-color:#3b82f6; font-size:11px;">' + stokPerusahaanNet + ' unit</span>'
                        : (stokPerusahaanNet > 0 
                            ? '<span class="badge" style="background-color:#f59e0b; color:#000; font-size:11px;">' + stokPerusahaanNet + ' unit</span>' 
                            : '<span class="badge" style="background-color:#ef4444; font-size:11px;">0 unit</span>');

                    html += '<tr style="border-bottom: 1px solid #f1f5f9;">';
                    html += '<td style="text-align: center;">' + (i + 1) + '</td>';
                    html += '<td>' + escapeHtml(row.tanggal) + '</td>';
                    html += '<td><strong style="color:#2563eb;">' + escapeHtml(row.pre_so_nomer) + '</strong></td>';
                    html += '<td>' + escapeHtml(row.cabang_nama) + '</td>';
                    html += '<td><strong>' + escapeHtml(row.customers_nama) + '</strong></td>';
                    html += '<td><code>' + escapeHtml(row.sku) + '</code></td>';
                    html += '<td>' + escapeHtml(row.produk_nama) + '</td>';
                    html += '<td style="text-align: right;">' + row.qty_order + '</td>';
                    html += '<td style="text-align: right;"><strong class="text-red">' + row.outstanding + '</strong></td>';
                    html += '<td style="text-align: center;"><span class="badge bg-gray">' + stokCabangFisik + ' unit</span></td>';
                    html += '<td style="text-align: center;">' + badgeBooking + '</td>';
                    html += '<td style="text-align: center;">' + badgeNet + '</td>';
                    html += '<td style="text-align: center;">' + badgePerusahaan + '</td>';
                    html += '<td style="text-align: center;"><span class="badge-sla ' + badgeClass + '">' + aging + ' Hari</span></td>';
                    html += '</tr>';
                }

            } else if (currentViewMode === 'product') {
                // ------------------------------------------------------------------
                // Mode Rekap Per-Produk: Akumulasi Stok Booking Terpisah
                // ------------------------------------------------------------------
                headerHtml = '<tr style="background-color: #f8fafc; color: #334155; border-bottom: 2px solid #cbd5e1;">' +
                             '<th style="width: 4%; text-align: center;">No</th>' +
                             '<th style="width: 13%;">SKU</th>' +
                             '<th style="width: 25%;">Nama Produk</th>' +
                             '<th style="width: 10%; text-align: center;">Jumlah Nota</th>' +
                             '<th style="width: 8%; text-align: right;">Total Order</th>' +
                             '<th style="width: 8%; text-align: right;">Total Macet</th>' +
                             '<th style="width: 8%; text-align: center;">Stok Fisik</th>' +
                             '<th style="width: 8%; text-align: center;">Stok Booking</th>' +
                             '<th style="width: 8%; text-align: center;">Stok Bebas</th>' +
                             '<th style="width: 8%; text-align: center;">Stok Perusahaan</th>' +
                             '</tr>';

                var groupedProd = {};
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var sku = row.sku;
                    if (!groupedProd[sku]) {
                        groupedProd[sku] = {
                            sku: row.sku,
                            produk_nama: row.produk_nama,
                            orders_count: 0,
                            total_qty_order: 0,
                            total_outstanding: 0,
                            stok_cabang_fisik: parseInt(row.stok_cabang_fisik) || 0,
                            stok_cabang_booking: parseInt(row.stok_cabang_booking) || 0,
                            stok_cabang_net: parseInt(row.stok_cabang_net) || 0,
                            stok_perusahaan_net: parseInt(row.stok_perusahaan_net) || 0
                        };
                    }
                    groupedProd[sku].orders_count += 1;
                    groupedProd[sku].total_qty_order += (parseInt(row.qty_order) || 0);
                    groupedProd[sku].total_outstanding += (parseInt(row.outstanding) || 0);
                }

                var idx = 1;
                for (var key in groupedProd) {
                    if (!groupedProd.hasOwnProperty(key)) continue;
                    var item = groupedProd[key];

                    var badgeBooking = item.stok_cabang_booking > 0
                        ? '<span class="badge" style="background-color:#f97316;">' + item.stok_cabang_booking + ' unit</span>'
                        : '<span class="badge bg-gray">0 unit</span>';

                    var badgeNet = item.stok_cabang_net >= item.total_outstanding 
                        ? '<span class="badge" style="background-color:#10b981;">' + item.stok_cabang_net + ' unit</span>'
                        : (item.stok_cabang_net > 0 
                            ? '<span class="badge" style="background-color:#f59e0b; color:#000;">' + item.stok_cabang_net + ' unit</span>' 
                            : '<span class="badge" style="background-color:#ef4444;">0 unit</span>');

                    var badgePerusahaan = item.stok_perusahaan_net >= item.total_outstanding 
                        ? '<span class="badge" style="background-color:#3b82f6;">' + item.stok_perusahaan_net + ' unit</span>'
                        : (item.stok_perusahaan_net > 0 
                            ? '<span class="badge" style="background-color:#f59e0b; color:#000;">' + item.stok_perusahaan_net + ' unit</span>' 
                            : '<span class="badge" style="background-color:#ef4444;">0 unit</span>');

                    html += '<tr style="border-bottom: 1px solid #f1f5f9;">';
                    html += '<td style="text-align: center;">' + idx + '</td>';
                    html += '<td><code>' + escapeHtml(item.sku) + '</code></td>';
                    html += '<td><strong>' + escapeHtml(item.produk_nama) + '</strong></td>';
                    html += '<td style="text-align: center;"><span class="badge bg-blue">' + item.orders_count + ' Nota</span></td>';
                    html += '<td style="text-align: right;">' + item.total_qty_order + ' unit</td>';
                    html += '<td style="text-align: right;"><strong class="text-red" style="font-size: 14px;">' + item.total_outstanding + ' unit</strong></td>';
                    html += '<td style="text-align: center;"><span class="badge bg-gray">' + item.stok_cabang_fisik + ' unit</span></td>';
                    html += '<td style="text-align: center;">' + badgeBooking + '</td>';
                    html += '<td style="text-align: center;">' + badgeNet + '</td>';
                    html += '<td style="text-align: center;">' + badgePerusahaan + '</td>';
                    html += '</tr>';
                    idx++;
                }

            } else if (currentViewMode === 'hybrid') {
                // ------------------------------------------------------------------
                // Mode Hibrid: Collapsible Grouping dengan Kolom Booking Terpisah
                // ------------------------------------------------------------------
                headerHtml = '<tr style="background-color: #f8fafc; color: #334155; border-bottom: 2px solid #cbd5e1;">' +
                             '<th style="width: 23%;">Kode Pesanan / Tanggal</th>' +
                             '<th style="width: 23%;">Branch / Customer</th>' +
                             '<th style="width: 22%;">SKU & Nama Produk</th>' +
                             '<th style="width: 7%; text-align: right;">Qty Order</th>' +
                             '<th style="width: 7%; text-align: right;">Outstanding</th>' +
                             '<th style="width: 6%; text-align: center;">Booking</th>' +
                             '<th style="width: 6%; text-align: center;">Stok Bebas</th>' +
                             '<th style="width: 6%; text-align: center;">Perusahaan</th>' +
                             '</tr>';

                var grouped = {};
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var sku = row.sku;
                    if (!grouped[sku]) {
                        grouped[sku] = {
                            sku: row.sku,
                            produk_nama: row.produk_nama,
                            stok_cabang_booking: parseInt(row.stok_cabang_booking) || 0,
                            stok_cabang_net: parseInt(row.stok_cabang_net) || 0,
                            stok_perusahaan_net: parseInt(row.stok_perusahaan_net) || 0,
                            total_outstanding: 0,
                            orders: []
                        };
                    }
                    grouped[sku].total_outstanding += (parseInt(row.outstanding) || 0);
                    grouped[sku].orders.push({
                        detail_id: row.detail_id,
                        pre_so_nomer: row.pre_so_nomer,
                        tanggal: row.tanggal,
                        customers_nama: row.customers_nama,
                        cabang_nama: row.cabang_nama,
                        qty_order: row.qty_order,
                        outstanding: row.outstanding,
                        stok_cabang_booking: row.stok_cabang_booking,
                        stok_cabang_net: row.stok_cabang_net,
                        stok_perusahaan_net: row.stok_perusahaan_net
                    });
                }

                var idx = 0;
                for (var key in grouped) {
                    if (!grouped.hasOwnProperty(key)) continue;
                    var group = grouped[key];
                    var groupId = 'group-' + idx;

                    html += '<tr class="group-header" style="cursor:pointer; background-color:#f1f5f9; border-top: 2px solid #cbd5e1;" onclick="toggleGroup(\'' + groupId + '\');">';
                    html += '<td colspan="3" style="padding: 10px 15px;"><i class="fa fa-chevron-down text-blue">&nbsp;</i> <code>' + escapeHtml(group.sku) + '</code> — <strong>' + escapeHtml(group.produk_nama) + '</strong></td>';
                    html += '<td colspan="5" class="text-right" style="padding: 10px 15px;"><span class="label label-danger" style="font-size: 11px; padding: 4px 8px;">Backorder: ' + group.total_outstanding + ' unit</span> <span class="label label-warning" style="font-size: 11px; padding: 4px 8px; margin-left: 5px;">Booking: ' + group.stok_cabang_booking + ' unit</span> <span class="label label-success" style="font-size: 11px; padding: 4px 8px; margin-left: 5px;">Stok Bebas: ' + group.stok_cabang_net + ' unit</span> <span class="badge bg-blue" style="margin-left: 8px;">' + group.orders.length + ' Nota</span></td>';
                    html += '</tr>';

                    for (var j = 0; j < group.orders.length; j++) {
                        var ord = group.orders[j];

                        html += '<tr class="child-row ' + groupId + '" style="border-bottom: 1px solid #f1f5f9;">';
                        html += '<td style="padding-left: 30px;"><i class="fa fa-angle-right text-gray">&nbsp;</i> <strong style="color:#2563eb;">' + escapeHtml(ord.pre_so_nomer) + '</strong><br><small class="text-muted">' + escapeHtml(ord.tanggal) + '</small></td>';
                        html += '<td>' + escapeHtml(ord.customers_nama) + '<br><small class="text-muted">' + escapeHtml(ord.cabang_nama) + '</small></td>';
                        html += '<td><code>' + escapeHtml(group.sku) + '</code> - <small>' + escapeHtml(group.produk_nama) + '</small></td>';
                        html += '<td class="text-right">' + ord.qty_order + ' unit</td>';
                        html += '<td class="text-right"><strong class="text-red">' + ord.outstanding + ' unit</strong></td>';
                        html += '<td class="text-center"><span class="badge bg-orange">' + ord.stok_cabang_booking + '</span></td>';
                        html += '<td class="text-center"><span class="badge bg-green">' + ord.stok_cabang_net + '</span></td>';
                        html += '<td class="text-center"><span class="badge bg-blue">' + ord.stok_perusahaan_net + '</span></td>';
                        html += '</tr>';
                    }
                    idx++;
                }
            }

            thead.innerHTML = headerHtml;
            tbody.innerHTML = html;

            if (currentViewMode !== 'hybrid' && typeof $ !== 'undefined' && $.fn.dataTable) {
                dtInstance = $('#table-backorder').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "pageLength": 25,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "order": [],
                    "language": {
                        "search": "Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "zeroRecords": "Tidak ada data backorder yang cocok"
                    }
                });
            }
        }

        window.onload = function() {
            renderMatrixTable(rawRowsData);
        };
    </script>

    <style>
        .cockpit-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .cockpit-title {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .cockpit-subtitle {
            margin-top: 5px;
            font-size: 13px;
            color: #94a3b8;
        }
        .kpi-row-container {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .kpi-item-col {
            flex: 1;
            min-width: 0;
        }
        .kpi-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 16px 18px;
            margin-bottom: 0;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 5px solid #3b82f6;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .kpi-card.danger { border-left-color: #ef4444; }
        .kpi-card.warning { border-left-color: #f59e0b; }
        .kpi-card.success { border-left-color: #10b981; }
        .kpi-card.info { border-left-color: #06b6d4; }
        
        .kpi-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .kpi-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
        }
        .kpi-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mode-switcher-toolbar {
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badge-sla {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-sla-platinum { background-color: #fee2e2; color: #991b1b; }
        .badge-sla-gold { background-color: #fef3c7; color: #92400e; }
        .badge-sla-normal { background-color: #e0f2fe; color: #075985; }

        .table-matrix > tbody > tr {
            transition: background-color 0.15s ease;
        }
        .table-matrix > tbody > tr:hover {
            background-color: #f8fafc !important;
        }
        .table-matrix > tbody > tr.group-header {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            color: #1e293b;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background-color: #f8fafc; padding: 15px;">

    <!-- Header Section -->
    <div class="cockpit-header">
        <div class="row">
            <div class="col-md-8">
                <h1 class="cockpit-title"><i class="fa fa-tachometer">&nbsp;</i> <?php echo isset($title) ? $title : "Backorder Cockpit"; ?></h1>
                <div class="cockpit-subtitle"><?php echo isset($subTitle) ? $subTitle : "Control Tower Pemenuhan Pesanan Tertunda"; ?> | Modul Penjualan (Jenis TR: <?php echo isset($jenisTr) ? $jenisTr : "5822"; ?>)</div>
            </div>
            <div class="col-md-4 text-right" style="margin-top: 10px;">
                <span class="label label-danger" style="font-size: 12px; padding: 6px 12px;"><i class="fa fa-shield">&nbsp;</i> ISO 9001 / ISO 25010 Certified</span>
            </div>
        </div>
    </div>

    <!-- Executive KPI Cards (Flexbox Single Horizontal Row) -->
    <div class="kpi-row-container">
        <div class="kpi-item-col">
            <div class="kpi-card danger">
                <div class="kpi-title"><i class="fa fa-cubes">&nbsp;</i> Total Backorder Qty</div>
                <div class="kpi-value"><?php echo isset($kpiData['total_backorder_qty']) ? number_format($kpiData['total_backorder_qty']) : "0"; ?> Unit</div>
                <div class="kpi-sub">Total barang macet nasional</div>
            </div>
        </div>

        <div class="kpi-item-col">
            <div class="kpi-card warning">
                <div class="kpi-title"><i class="fa fa-building">&nbsp;</i> Impacted Entities</div>
                <div class="kpi-value"><?php echo isset($kpiData['total_impacted_entities']) ? $kpiData['total_impacted_entities'] : "0"; ?> Pelanggan</div>
                <div class="kpi-sub">Entitas/Pelanggan terdampak</div>
            </div>
        </div>

        <div class="kpi-item-col">
            <div class="kpi-card info">
                <div class="kpi-title"><i class="fa fa-clock-o">&nbsp;</i> Aging Backorder Avg</div>
                <div class="kpi-value"><?php echo isset($kpiData['avg_aging_days']) ? $kpiData['avg_aging_days'] : "0"; ?> Hari</div>
                <div class="kpi-sub">Rata-rata keterlambatan</div>
            </div>
        </div>

        <div class="kpi-item-col">
            <div class="kpi-card danger">
                <div class="kpi-title"><i class="fa fa-exclamation-triangle">&nbsp;</i> Critical Orders</div>
                <div class="kpi-value"><?php echo isset($kpiData['total_critical_orders']) ? $kpiData['total_critical_orders'] : "0"; ?> Pesanan</div>
                <div class="kpi-sub">Keterlambatan > 7 hari</div>
            </div>
        </div>
    </div>

    <!-- View Mode Switcher Toolbar -->
    <div class="mode-switcher-toolbar">
        <div class="form-inline">
            <strong style="color: #475569; margin-right: 15px;"><i class="fa fa-sliders">&nbsp;</i> Mode Tampilan Matriks:</strong>
            <select id="select-view-mode" class="form-control" style="width: 320px; font-weight: bold; color: #1e293b; border-color: #cbd5e1;" onchange="switchViewMode(this.value)">
                <option value="nota" selected>Mode Datar Per-Nota (Daftar Nota Transaksi - Standard)</option>
                <option value="product">Mode Rekap Per-Produk (Akumulasi Murni SKU)</option>
                <option value="hybrid">Mode Hibrid (Collapsible SKU + Sub-Nota)</option>
            </select>
        </div>
        <div>
            <button class="btn btn-primary btn-sm" onclick="location.reload();"><i class="fa fa-refresh">&nbsp;</i> Refresh Data</button>
        </div>
    </div>

    <!-- Main Data Table Box -->
    <div class="box box-primary" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div class="box-header with-border" style="padding: 15px 20px;">
            <h3 class="box-title" style="font-weight: 700; color: #1e293b;"><i class="fa fa-table">&nbsp;</i> Smart Allocation Matrix Table</h3>
        </div>
        <div class="box-body table-responsive" style="padding: 20px;">
            <table id="table-backorder" class="table table-bordered table-striped table-hover table-matrix" style="width: 100%;">
                <thead id="thead-matrix">
                    <!-- Dynamic DataTables Header -->
                </thead>
                <tbody id="tbody-matrix">
                    <!-- Dynamic DataTables Rows -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<!-- END OF COMPLETE REPEATED LOGIC -->
