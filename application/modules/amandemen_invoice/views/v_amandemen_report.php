<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : View Laporan Audit Khusus untuk Diskon / Adjustment Project
 * COMPLIANCE: ISO 9001 (Audit Trail)
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit Diskon / Adjustment Project</title>
    <link href="<?= base_url(); ?>assets/bootstrap3/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { padding: 20px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .table-custom th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h2 class="page-header text-danger"><b>Laporan Audit: Riwayat Diskon / Adjustment Project</b></h2>
        <p class="text-muted">Sesuai protokol Anti-Fraud ISO 9001, halaman ini mencatat seluruh aktivitas pengubahan nilai / penyesuaian (Diskon / Adjustment) pada transaksi tagihan.</p>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-custom">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="15%">Waktu Penyesuaian</th>
                        <th class="text-center" width="20%">No. Invoice</th>
                        <th class="text-center" width="15%">Total Nilai Saat Ini</th>
                        <th class="text-center" width="25%">Direvisi Oleh (User ID)</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat diskon / adjustment.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($riwayat as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?>.</td>
                            <td class="text-center"><?= date('d-M-Y H:i', strtotime($row['dtime'])); ?></td>
                            <td class="text-center"><b><?= $row['invoice_no']; ?></b></td>
                            <td class="text-right text-bold">Rp <?= number_format($row['total_rp'], 0, ',', '.'); ?></td>
                            <td><?= $row['user_name'] ? $row['user_name'] : 'Unknown User'; ?> (ID: <?= $row['oleh_id']; ?>)</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-xs btn-info" onclick="alert('Ini akan menampilkan snapshot data lama (Versi <?= $row['registry_patch']; ?>) sesuai ISO 9001. Fitur view snapshot detail dalam pengembangan.'); return false;">Lihat Snapshot Data Lama</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
