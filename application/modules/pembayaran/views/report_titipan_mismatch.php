<?php
// START OF COMPLETE REPEATED LOGIC
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Saldo Titipan (Relasi PO)</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .report-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 22px 25px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .report-header h2 {
            margin: 0 0 8px 0;
            font-weight: 600;
            font-size: 24px;
        }
        .report-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 13px;
        }
        .filter-panel {
            background: #ffffff;
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .mismatch-buttons-panel {
            background: #fff0f0;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .mismatch-buttons-panel h4 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: bold;
            color: #c81e1e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-supplier-mismatch {
            margin-right: 8px;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 12px;
            border-radius: 20px;
            padding: 6px 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            transition: all 0.2s ease-in-out;
        }
        .btn-supplier-mismatch:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .summary-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid #2a5298;
        }
        .summary-card.success {
            border-left-color: #27ae60;
        }
        .summary-card.warning {
            border-left-color: #f39c12;
        }
        .summary-card.danger {
            border-left-color: #e74c3c;
        }
        .summary-card .title {
            font-size: 11px;
            text-transform: uppercase;
            color: #777;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .summary-card .value {
            font-size: 22px;
            font-weight: bold;
            color: #222;
            margin-top: 4px;
        }
        .panel-custom {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            background: #fff;
        }
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom > thead > tr > th {
            background-color: #2c3e50;
            color: #ffffff;
            border: none;
            padding: 12px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.3px;
        }
        .table-custom > tbody > tr > td {
            padding: 10px 15px;
            vertical-align: middle;
            border-top: 1px solid #edf2f7;
            font-size: 13px;
        }
        .row-mismatch {
            background-color: #fff5f5 !important;
        }
        .row-match {
            background-color: #f6ffed !important;
        }
        .badge-status {
            padding: 5px 10px;
            font-size: 11px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-status.danger {
            background-color: #fde8e8;
            color: #c81e1e;
            border: 1px solid #f8b4b4;
        }
        .badge-status.success {
            background-color: #def7ec;
            color: #03543f;
            border: 1px solid #84e1bc;
        }
        .num-col {
            text-align: right;
            font-family: 'Consolas', 'Courier New', monospace;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container-fluid" style="max-width: 1350px;">
    
    <!-- Header -->
    <div class="report-header">
        <div class="row">
            <div class="col-md-8">
                <h2><i class="fa fa-exchange"></i> Laporan Monitoring Saldo Titipan (Relasi PO)</h2>
                <p>Perbandingan Sinkronisasi Data Antara <b>Modal Selector (_rek_pembantu_uang_muka_reference_cache)</b> dan <b>Form Pembayaran (transaksi_uang_muka_source)</b></p>
            </div>
            <div class="col-md-4 text-right" style="margin-top: 10px;">
                <a href="javascript:void(0);" onclick="window.print();" class="btn btn-default"><i class="fa fa-print"></i> Cetak</a>
                <a href="<?php echo base_url(); ?>pembayaran/Debug/report_mismatch" class="btn btn-primary"><i class="fa fa-refresh"></i> Reset Filter</a>
            </div>
        </div>
    </div>

    <!-- Dedicated Tombol Khusus Supplier Mismatch -->
    <?php if (isset($mismatch_supplier_list) && count($mismatch_supplier_list) > 0): ?>
        <div class="mismatch-buttons-panel">
            <h4><i class="fa fa-exclamation-triangle"></i> Tombol Khusus Supplier Terdeteksi Mismatch (<?php echo count($mismatch_supplier_list); ?> Supplier):</h4>
            <div>
                <?php foreach ($mismatch_supplier_list as $msup): ?>
                    <?php 
                        $is_active = ($supplier_id == $msup['supplier_id']); 
                        $btn_class = $is_active ? 'btn-danger' : 'btn-outline btn-default';
                        $style_custom = $is_active ? 'background-color: #d9534f; color: #fff;' : 'background-color: #fff; color: #d9534f; border: 1px solid #d9534f;';
                    ?>
                    <a href="<?php echo base_url(); ?>pembayaran/Debug/report_mismatch?supplier_id=<?php echo urlencode($msup['supplier_id']); ?>&status_filter=MISMATCH" 
                       class="btn btn-supplier-mismatch" 
                       style="<?php echo $style_custom; ?>">
                        <i class="fa fa-warning"></i> 
                        <b><?php echo htmlspecialchars($msup['supplier_nama']); ?></b> 
                        <span class="badge" style="background-color: #c81e1e; color: #fff; margin-left: 5px;"><?php echo $msup['total_mismatch_po']; ?> PO</span>
                        <small style="margin-left: 5px;">(Rp <?php echo number_format(floatval($msup['total_selisih']), 0, ',', '.'); ?>)</small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Panel Filter Form -->
    <div class="filter-panel">
        <form method="GET" action="<?php echo base_url(); ?>pembayaran/Debug/report_mismatch" class="form-inline">
            <div class="form-group" style="margin-right: 15px;">
                <label for="supplier_id" style="margin-right: 8px;"><i class="fa fa-building"></i> Pilihan Supplier / Vendor:</label>
                <select name="supplier_id" id="supplier_id" class="form-control" style="min-width: 320px;" onchange="this.form.submit()">
                    <option value="">-- Tampilkan Semua Supplier --</option>
                    <?php if (isset($supplier_list) && count($supplier_list) > 0): ?>
                        <?php foreach ($supplier_list as $sup): ?>
                            <option value="<?php echo htmlspecialchars($sup['supplier_id']); ?>" <?php echo ($supplier_id == $sup['supplier_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sup['supplier_nama']); ?> (ID: <?php echo htmlspecialchars($sup['supplier_id']); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group" style="margin-right: 15px;">
                <label for="status_filter" style="margin-right: 8px;"><i class="fa fa-filter"></i> Status Data:</label>
                <select name="status_filter" id="status_filter" class="form-control" onchange="this.form.submit()">
                    <option value="" <?php echo ($status_filter === '') ? 'selected' : ''; ?>>-- Semua Status (Match & Mismatch) --</option>
                    <option value="MISMATCH" <?php echo ($status_filter === 'MISMATCH') ? 'selected' : ''; ?>>⚠️ Hanya Mismatch (Kosong / Beda)</option>
                    <option value="MATCH" <?php echo ($status_filter === 'MATCH') ? 'selected' : ''; ?>>✅ Hanya Match (Sinkron)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Terapkan Filter</button>
        </form>
    </div>

    <?php
    $total_records = count($mismatches);
    $count_mismatch = 0;
    $count_match = 0;
    $total_cache = 0;
    $total_source = 0;
    $total_selisih = 0;

    foreach ($mismatches as $row) {
        $total_cache += floatval($row['saldo_di_modal_selector']);
        $total_source += floatval($row['saldo_di_form_pembayaran']);
        $total_selisih += floatval($row['selisih_saldo']);
        if ($row['status_match'] === 'MISMATCH') {
            $count_mismatch++;
        } else {
            $count_match++;
        }
    }
    ?>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="summary-card">
                <div class="title">Total Data PO Titipan</div>
                <div class="value"><?php echo number_format($total_records, 0, ',', '.'); ?> <small style="font-size: 13px; color: #777;">Dokumen</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="summary-card danger">
                <div class="title">Total PO Mismatch (Beda / Kosong)</div>
                <div class="value text-danger"><?php echo number_format($count_mismatch, 0, ',', '.'); ?> <small style="font-size: 13px; color: #e74c3c;">Dokumen</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="summary-card success">
                <div class="title">Total PO Match (Sinkron)</div>
                <div class="value text-success"><?php echo number_format($count_match, 0, ',', '.'); ?> <small style="font-size: 13px; color: #27ae60;">Dokumen</small></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="summary-card warning">
                <div class="title">Total Selisih Saldo</div>
                <div class="value" style="color: #d35400;">Rp <?php echo number_format($total_selisih, 0, ',', '.'); ?></div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="panel panel-custom">
        <div class="panel-body" style="padding: 20px;">
            
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari berdasarkan nama supplier atau nomor PO di tabel...">
                    </div>
                </div>
                <div class="col-md-6 text-right" style="padding-top: 8px;">
                    <span class="text-muted">Menampilkan <b><?php echo $total_records; ?></b> baris data</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom" id="mismatchTable">
                    <thead>
                        <tr>
                            <th style="width: 45px; text-align: center;">No</th>
                            <th style="width: 90px;">Supplier ID</th>
                            <th>Nama Supplier / Vendor</th>
                            <th style="width: 170px;">Nomor PO (Acuan)</th>
                            <th class="num-col" style="width: 200px;">Saldo Modal Selector<br><small style="color:#ddd; font-weight:normal;">(_rek_pembantu_uang_muka_...)</small></th>
                            <th class="num-col" style="width: 200px;">Saldo Form Pembayaran<br><small style="color:#ddd; font-weight:normal;">(transaksi_uang_muka_source)</small></th>
                            <th class="num-col" style="width: 180px;">Selisih Saldo</th>
                            <th style="width: 180px; text-align: center;">Status Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_records > 0): ?>
                            <?php $no = 1; foreach ($mismatches as $item): ?>
                                <?php $is_mismatch = ($item['status_match'] === 'MISMATCH'); ?>
                                <tr class="<?php echo $is_mismatch ? 'row-mismatch' : 'row-match'; ?>">
                                    <td style="text-align: center; font-weight: bold;"><?php echo $no++; ?></td>
                                    <td><code><?php echo htmlspecialchars($item['supplier_id']); ?></code></td>
                                    <td><b><?php echo htmlspecialchars($item['supplier_nama']); ?></b></td>
                                    <td><span class="label label-info" style="font-size: 11px; padding: 4px 8px;"><?php echo htmlspecialchars($item['nomer_po']); ?></span></td>
                                    <td class="num-col text-primary">Rp <?php echo number_format(floatval($item['saldo_di_modal_selector']), 0, ',', '.'); ?></td>
                                    <td class="num-col text-muted">Rp <?php echo number_format(floatval($item['saldo_di_form_pembayaran']), 0, ',', '.'); ?></td>
                                    <td class="num-col <?php echo $is_mismatch ? 'text-danger' : 'text-success'; ?>">
                                        Rp <?php echo number_format(floatval($item['selisih_saldo']), 0, ',', '.'); ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($is_mismatch): ?>
                                            <span class="badge-status danger"><i class="fa fa-exclamation-triangle"></i> MISMATCH (BEDA/KOSONG)</span>
                                        <?php else: ?>
                                            <span class="badge-status success"><i class="fa fa-check-circle"></i> SINKRON (MATCH)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center" style="padding: 40px; color: #777;">
                                    <i class="fa fa-info-circle" style="font-size: 48px; margin-bottom: 10px;"></i><br>
                                    <h4>Tidak Ada Data Yang Sesuai Filter</h4>
                                    <p class="text-muted">Silahkan ganti pilihan Supplier atau Status Data pada pilihan filter di atas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
function filterTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("mismatchTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdSupplier = tr[i].getElementsByTagName("td")[2];
        var tdPo = tr[i].getElementsByTagName("td")[3];
        if (tdSupplier || tdPo) {
            var txtSupplier = tdSupplier.textContent || tdSupplier.innerText;
            var txtPo = tdPo.textContent || tdPo.innerText;
            if (txtSupplier.toUpperCase().indexOf(filter) > -1 || txtPo.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

</body>
</html>
// END OF COMPLETE REPEATED LOGIC
