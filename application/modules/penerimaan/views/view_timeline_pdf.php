<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Timeline & Rekonsiliasi Transaksi</title>
<style>
* {
    box-sizing: border-box;
}
body {
    font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
    font-size: 12px;
    color: #1e293b;
    line-height: 1.5;
    margin: 0;
    padding: 15px;
    background-color: #f1f5f9;
}
.container {
    max-width: 1280px;
    margin: 0 auto;
    background: #ffffff;
    padding: 25px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
.no-print {
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-print {
    background-color: #0284c7;
    color: #fff;
    padding: 8px 18px;
    border: none;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background-color 0.2s;
}
.btn-print:hover {
    background-color: #0369a1;
}
.doc-header-title {
    font-size: 18px;
    font-weight: bold;
    color: #0f172a;
    margin: 0 0 15px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #0284c7;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.badge-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 11px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.badge-lunas {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.badge-cicilan {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}
.badge-belum {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Excel Matrix Header Box */
.excel-info-card {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 12px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    overflow: hidden;
}
.excel-info-card th {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    padding: 8px 12px;
    text-align: left;
    border: 1px solid #cbd5e1;
    width: 18%;
}
.excel-info-card td {
    padding: 8px 12px;
    color: #1e293b;
    border: 1px solid #cbd5e1;
    width: 32%;
}

/* Excel-Style Data Table */
.excel-table-container {
    width: 100%;
    overflow-x: auto;
    margin-bottom: 25px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
}
.excel-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    background: #ffffff;
    white-space: normal;
}
.excel-table th {
    background: #1e293b;
    color: #f8fafc;
    font-weight: 600;
    text-align: left;
    padding: 8px 10px;
    border: 1px solid #334155;
    font-size: 11px;
    letter-spacing: 0.3px;
}
.excel-table th.text-center, .excel-table td.text-center {
    text-align: center;
}
.excel-table th.text-right, .excel-table td.text-right {
    text-align: right;
}
.excel-table td {
    padding: 7px 10px;
    border: 1px solid #e2e8f0;
    color: #1e293b;
    vertical-align: top;
}
.excel-table tr:nth-child(even) {
    background-color: #f8fafc;
}
.excel-table tr:hover {
    background-color: #f1f5f9;
}

/* Modul Badges */
.badge-modul {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: 600;
    font-family: monospace;
}
.modul-so { background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
.modul-inv { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.modul-dp { background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
.modul-tax { background-color: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
.modul-rec { background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.modul-cost { background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }

/* Specific Highlight Rows */
.row-receipt {
    background-color: #f0fdf4 !important;
}
.row-invoice {
    background-color: #f8fafc;
}
.num-font {
    font-family: "Consolas", "Courier New", monospace;
    font-size: 11.5px;
}

/* Summary Boxes */
.summary-matrix {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    overflow: hidden;
    margin-top: 15px;
}
.summary-matrix td {
    padding: 9px 14px;
    border: 1px solid #e2e8f0;
}
.summary-matrix tr.highlight-tot {
    background-color: #f1f5f9;
    font-weight: bold;
}
.summary-matrix tr.highlight-rec {
    background-color: #ecfdf5;
    font-weight: bold;
    color: #065f46;
}
.summary-matrix tr.highlight-sisa {
    background-color: #fff7ed;
    font-weight: bold;
    font-size: 13px;
}

.amandemen-box {
    margin-top: 4px;
    padding: 4px 8px;
    background-color: #fef3c7;
    border-left: 3px solid #f59e0b;
    border-radius: 2px;
    font-size: 10.5px;
    color: #92400e;
}

@media print {
    .no-print {
        display: none !important;
    }
    body {
        background-color: #fff;
        padding: 0;
        font-size: 10px;
    }
    .container {
        box-shadow: none;
        padding: 0;
        max-width: 100%;
    }
    .excel-table th {
        background: #334155 !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .excel-table td, .excel-table th {
        padding: 5px 6px;
        font-size: 9.5px;
    }
    .badge-status, .badge-modul {
        border: 1px solid #666;
    }
}
</style>
</head>
<body>

<div class="container">
<div class="no-print">
    <span style="color:#64748b; font-weight:600;">PT. Everest Sukses Mandiri &bull; Modul Pelaporan Transaksi</span>
    <button class="btn-print" onclick="window.print()">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
        Cetak / Simpan PDF (Excel View)
    </button>
</div>

<?php
if (!function_exists('formatTglIndo')) {
    function formatTglIndo($datetimeStr) {
        if (empty($datetimeStr) || $datetimeStr == '-' || $datetimeStr == '0000-00-00 00:00:00') {
            return '-';
        }
        $timestamp = strtotime($datetimeStr);
        if (!$timestamp) return $datetimeStr;
        $bulan = array(1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
        $tgl = date('d', $timestamp);
        $bln = $bulan[(int)date('n', $timestamp)];
        $thn = date('Y', $timestamp);
        $jam = date('H:i', $timestamp);
        return "$tgl $bln $thn $jam";
    }
}

$customerNama = isset($trx->customers_nama) ? $trx->customers_nama : '-';
$nomerUtama = isset($trx->nomer2) && !empty($trx->nomer2) ? $trx->nomer2 : (isset($trx->nomer) ? $trx->nomer : '-');

// Resolusi Data Proyek
$projectName = '';
if (!empty($trx->project_nama)) {
    $projectName = $trx->project_nama;
} elseif (!empty($trx->nama_proyek)) {
    $projectName = $trx->nama_proyek;
} elseif (!empty($trx->keterangan) && preg_match('/Project:\s*([^<,\n]+)/i', $trx->keterangan, $m)) {
    $projectName = trim($m[1]);
}

if (empty($projectName) && isset($timelineSteps) && is_array($timelineSteps)) {
    foreach ($timelineSteps as $s) {
        if (!empty($s->project_nama)) { $projectName = $s->project_nama; break; }
        if (!empty($s->nama_proyek)) { $projectName = $s->nama_proyek; break; }
        if (!empty($s->keterangan) && preg_match('/Project:\s*([^<,\n]+)/i', $s->keterangan, $m)) {
            $projectName = trim($m[1]); break;
        }
    }
}

$ci =& get_instance();
$projectProgress = '';
$projectNoKontrak = '';
$projectAlamat = '';
$projectId = '';
$projectSchemaItems3 = array();
$projectSchemaItems4 = array();
$projectSchemaItems5 = array();

// Query Detail Master Project
$pSearchIds = array();
if (isset($timelineSteps) && is_array($timelineSteps)) {
    foreach ($timelineSteps as $st) {
        if (!empty($st->id)) $pSearchIds[] = (int)$st->id;
    }
}

if (sizeof($pSearchIds) > 0) {
    $ci->db->select("id, nama, persen_progress, nomor_kontrak, alamat, quot_nomer, transaksi_id");
    $ci->db->from("project_produk");
    $ci->db->where("trash", 0);
    $ci->db->where("(transaksi_id IN (" . implode(",", $pSearchIds) . ") OR quot_id IN (" . implode(",", $pSearchIds) . ") OR project_start_id IN (" . implode(",", $pSearchIds) . "))", null, false);
    $pHeaderQ = $ci->db->get();
    $pHeaderRow = is_object($pHeaderQ) ? $pHeaderQ->row() : null;

    if (!empty($pHeaderRow->id)) {
        $projectId = $pHeaderRow->id;
        $pTrxId = $pHeaderRow->transaksi_id;
        $pQuotNo = $pHeaderRow->quot_nomer;

        if (empty($projectName) && !empty($pHeaderRow->nama)) {
            $projectName = $pHeaderRow->nama;
        }
        if ((float)$pHeaderRow->persen_progress > 0) {
            $projectProgress = number_format((float)$pHeaderRow->persen_progress, 0) . '% Selesai';
        }
        if (!empty($pHeaderRow->nomor_kontrak)) {
            $projectNoKontrak = $pHeaderRow->nomor_kontrak;
        }
        if (!empty($pHeaderRow->alamat)) {
            $projectAlamat = $pHeaderRow->alamat;
        }

        // Termin Schedule
        $ci->db->select("nama, persen, harga, jumlah");
        $ci->db->from("project_produk_items3");
        $ci->db->where("trash", 0);
        $ci->db->where("(transaksi_id = '$pTrxId' OR nomer = '$pQuotNo')", null, false);
        $qIt3 = $ci->db->get();
        if (is_object($qIt3)) $projectSchemaItems3 = $qIt3->result();

        // DP Schedule
        $ci->db->select("persen, harga, jumlah, keterangan_dp");
        $ci->db->from("project_produk_items4");
        $ci->db->where("trash", 0);
        $ci->db->where("(transaksi_id = '$pTrxId' OR nomer = '$pQuotNo')", null, false);
        $qIt4 = $ci->db->get();
        if (is_object($qIt4)) $projectSchemaItems4 = $qIt4->result();

        // Garansi Schedule
        $ci->db->select("persen, harga, tgl_akhir_garansi, keterangan_garansi");
        $ci->db->from("project_produk_items5");
        $ci->db->where("trash", 0);
        $ci->db->where("(transaksi_id = '$pTrxId' OR nomer = '$pQuotNo')", null, false);
        $qIt5 = $ci->db->get();
        if (is_object($qIt5)) $projectSchemaItems5 = $qIt5->result();
    }
}

$isProjectTrx = !empty($projectName) || !empty($projectId);

// Hitung Uang Muka DP
$totUangMuka = 0;
if (sizeof($projectSchemaItems4) > 0) {
    foreach ($projectSchemaItems4 as $dpR) {
        $j = isset($dpR->jumlah) ? (float)$dpR->jumlah : 0;
        $h = isset($dpR->harga) ? (float)$dpR->harga : 0;
        $totUangMuka += ($j > 0 ? $j : $h);
    }
}
if ($totUangMuka == 0 && isset($timelineSteps) && is_array($timelineSteps)) {
    foreach ($timelineSteps as $st) {
        if ($st->jenis == '4467' || $st->jenis == '4467_1' || $st->jenis == '583') {
            $net = (float)$st->transaksi_net;
            $nil = (float)$st->transaksi_nilai;
            $totUangMuka += ($net > 0 ? $net : $nil);
        }
    }
}

$totTag = isset($totalTagihan) ? (float)$totalTagihan : 0;
$totPpnVal = isset($totalPpn) ? (float)$totalPpn : ($totTag - ($totTag / 1.11));
$totDppVal = $totTag - $totPpnVal;
$totDiterimaVal = isset($totalDiterima) ? (float)$totalDiterima : 0;
$sisaTagVal = isset($sisaTagihan) ? (float)$sisaTagihan : ($totTag - $totDiterimaVal);
if ($sisaTagVal < 0) $sisaTagVal = 0;

$stPembayaran = isset($statusPembayaran) ? $statusPembayaran : 'BELUM_LUNAS';
$badgeClass = 'badge-belum';
$statusText = 'BELUM LUNAS';
if ($stPembayaran == 'LUNAS' || $sisaTagVal == 0) {
    $badgeClass = 'badge-lunas';
    $statusText = 'LUNAS';
} elseif ($totDiterimaVal > 0) {
    $badgeClass = 'badge-cicilan';
    $statusText = 'CICILAN / SEBAGIAN';
}
?>

<!-- Judul Laporan & Status -->
<div class="doc-header-title">
    <div>
        <span><?php echo $isProjectTrx ? 'LAPORAN TIMELINE & REKONSILIASI PROYEK' : 'LAPORAN TIMELINE RANTAI TRANSAKSI'; ?></span>
        <div style="font-size:12px; font-weight:normal; color:#64748b; margin-top:2px;">
            Dokumen Acuan: <strong><?php echo htmlspecialchars($nomerUtama); ?></strong> &bull; Dicetak pada: <?php echo date('d/m/Y H:i'); ?> WIB
        </div>
    </div>
    <div>
        <span class="badge-status <?php echo $badgeClass; ?>"><?php echo $statusText; ?></span>
    </div>
</div>

<!-- Informasi Profil Proyek / Konsumen (Excel Info Grid) -->
<table class="excel-info-card">
    <tr>
        <th>Pelanggan / Konsumen</th>
        <td><strong><?php echo htmlspecialchars($customerNama); ?></strong></td>
        <th>Nama Proyek</th>
        <td><strong style="color:#0284c7;"><?php echo htmlspecialchars(!empty($projectName) ? $projectName . (!empty($projectId) ? " ($projectId)" : "") : '-'); ?></strong></td>
    </tr>
    <tr>
        <th>Dokumen Acuan (Start)</th>
        <td><strong><?php echo htmlspecialchars($nomerUtama); ?></strong></td>
        <th>Nomor Kontrak (PO)</th>
        <td><?php echo htmlspecialchars(!empty($projectNoKontrak) ? $projectNoKontrak : '-'); ?></td>
    </tr>
    <?php if ($isProjectTrx): ?>
        <tr>
            <th>Total Nilai Proyek (Incl. PPN)</th>
            <td><strong class="num-font" style="color:#0f172a; font-size:13px;">Rp<?php echo number_format($totTag, 0, ',', '.'); ?></strong></td>
            <th>Progress Pekerjaan</th>
            <td><strong style="color:#16a34a;"><?php echo htmlspecialchars(!empty($projectProgress) ? $projectProgress : '-'); ?></strong></td>
        </tr>
        <tr>
            <th>Nilai Proyek DPP (Excl. PPN)</th>
            <td class="num-font">Rp<?php echo number_format($totDppVal, 0, ',', '.'); ?></td>
            <th>Uang Muka (DP Kontrak)</th>
            <td class="num-font" style="color:#0284c7; font-weight:bold;">Rp<?php echo number_format($totUangMuka, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <th>Total PPN (11%)</th>
            <td class="num-font" style="color:#dc2626; font-weight:bold;">Rp<?php echo number_format($totPpnVal, 0, ',', '.'); ?></td>
            <th>Lokasi Site / Proyek</th>
            <td><?php echo htmlspecialchars(!empty($projectAlamat) ? $projectAlamat : '-'); ?></td>
        </tr>
    <?php endif; ?>
</table>

<!-- Skema Kontrak & Jadwal Termin (Jika Ada) -->
<?php if ($isProjectTrx && (sizeof($projectSchemaItems3) > 0 || sizeof($projectSchemaItems4) > 0)): ?>
    <div style="margin-bottom:15px;">
        <div style="font-weight:bold; font-size:12px; color:#334155; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <span style="display:inline-block; width:4px; height:14px; background:#0284c7; border-radius:2px;"></span>
            Skema Kontrak & Jadwal Termin Proyek
        </div>
        <table class="excel-table" style="border:1px solid #cbd5e1;">
            <thead>
            <tr>
                <th style="width:35%;">Skema Pembayaran</th>
                <th style="width:15%;" class="text-center">Bobot Persen (%)</th>
                <th style="width:25%;" class="text-right">Nominal Acuan Kontrak (Rp)</th>
                <th style="width:25%;">Keterangan / Ketentuan</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($projectSchemaItems4 as $i4R): ?>
                <tr style="background-color:#eff6ff;">
                    <td><strong>Uang Muka (DP Project)</strong></td>
                    <td class="text-center font-bold" style="color:#0284c7;"><?php echo (int)$i4R->persen; ?>%</td>
                    <td class="text-right num-font" style="color:#0284c7; font-weight:bold;">Rp<?php echo number_format((float)$totUangMuka, 0, ',', '.'); ?></td>
                    <td><?php echo !empty($i4R->keterangan_dp) ? htmlspecialchars($i4R->keterangan_dp) : 'Pembayaran uang muka sebelum proyek dimulai'; ?></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($projectSchemaItems3 as $i3R): ?>
                <?php
                $tNom = (float)$i3R->harga > 0 ? (float)$i3R->harga : ((float)$totTag * ((float)$i3R->persen / 100));
                ?>
                <tr>
                    <td><strong><?php echo !empty($i3R->nama) ? htmlspecialchars($i3R->nama) : 'Termin Invoice'; ?></strong></td>
                    <td class="text-center font-bold"><?php echo (int)$i3R->persen; ?>%</td>
                    <td class="text-right num-font font-bold">Rp<?php echo number_format($tNom, 0, ',', '.'); ?></td>
                    <td>Tagihan termin progress fisik</td>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($projectSchemaItems5 as $i5R): ?>
                <?php if ((int)$i5R->persen > 0): ?>
                    <tr style="background-color:#fffbeb;">
                        <td style="color:#b45309;"><strong>Retensi / Garansi Pemeliharaan</strong></td>
                        <td class="text-center font-bold" style="color:#b45309;"><?php echo (int)$i5R->persen; ?>%</td>
                        <td class="text-right num-font font-bold" style="color:#b45309;">Rp<?php echo number_format((float)$totTag * ((float)$i5R->persen / 100), 0, ',', '.'); ?></td>
                        <td>
                            <?php echo !empty($i5R->keterangan_garansi) ? htmlspecialchars($i5R->keterangan_garansi) : 'Masa garansi pemeliharaan'; ?>
                            <?php if (!empty($i5R->tgl_akhir_garansi) && $i5R->tgl_akhir_garansi != '0000-00-00 00:00:00'): ?>
                                (s/d <?php echo formatTglIndo($i5R->tgl_akhir_garansi); ?>)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- TABEL UTAMA: GRID EXCEL RANTAI RIWAYAT TRANSAKSI LENGKAP -->
<div style="font-weight:bold; font-size:13px; color:#0f172a; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
    <div style="display:flex; align-items:center; gap:6px;">
        <span style="display:inline-block; width:4px; height:16px; background:#0f172a; border-radius:2px;"></span>
        Tabel Riwayat Rantai Transaksi (Lengkap: SPK, DP, Invoice, E-Faktur & Penerimaan Kas/Bank)
    </div>
    <div style="font-size:11px; color:#64748b; font-weight:normal;">
        Total: <strong><?php echo sizeof($timelineSteps); ?></strong> Dokumen Transaksi Terhubung
    </div>
</div>

<div class="excel-table-container">
    <table class="excel-table">
        <thead>
        <tr>
            <th style="width:30px;" class="text-center">No</th>
            <th style="width:115px;">Waktu & Tanggal</th>
            <th style="width:135px;">No. Dokumen Cetak</th>
            <th style="width:125px;">No. Transaksi Sistem</th>
            <th style="width:160px;">Tahapan / Modul</th>
            <th style="width:75px;">User</th>
            <th style="width:115px;" class="text-right">DPP (Excl. PPN)</th>
            <th style="width:105px;" class="text-right">PPN 11%</th>
            <th style="width:125px;" class="text-right">Total Nilai (Incl)</th>
            <th>Catatan / Keterangan</th>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($timelineSteps) && is_array($timelineSteps) && sizeof($timelineSteps) > 0): ?>
            <?php
            $sumDpp = 0;
            $sumPpn = 0;
            $sumTot = 0;
            ?>
            <?php foreach ($timelineSteps as $index => $step): ?>
                <?php
                $stepNo = $index + 1;
                $jSt = isset($step->jenis) ? (string)$step->jenis : '';
                $jmSt = isset($step->jenis_master) ? (string)$step->jenis_master : '';

                // Identifikasi tipe transaksi
                $isReceipt = ($jSt == '749' || $jSt == '749_1' || $jSt == '4467' || $jSt == '4467_1' || $jSt == '583' || $jSt == '583r' || (strpos($jSt, '749') !== false && strpos($jSt, '7499') === false) || strpos($jSt, '4467') !== false);
                $isTax = (strpos($jSt, '110') !== false || strpos($jmSt, '110') !== false);
                $isInvoice = ($jSt == '7499' || $jSt == '4822');
                $isDP = ($jSt == '4467' || $jSt == '4467_1' || $jSt == '583');
                $isProjectStart = (strpos($jSt, '588') !== false || strpos($jmSt, '588') !== false);

                // Nilai Finansial
                $net = isset($step->transaksi_net) ? (float)$step->transaksi_net : 0;
                $nilai = isset($step->transaksi_nilai) ? (float)$step->transaksi_nilai : 0;
                $ppnDb = isset($step->ppn_nilai) ? (float)$step->ppn_nilai : 0;

                if ($isReceipt) {
                    $rowTot = $net > 0 ? $net : $nilai;
                    $rowPpn = $ppnDb > 0 ? $ppnDb : ($rowTot - ($rowTot / 1.11));
                    $rowDpp = $rowTot - $rowPpn;
                } else {
                    if ($net > 0) {
                        $rowTot = $net;
                        $rowPpn = $ppnDb > 0 ? $ppnDb : ($net - ($net / 1.11));
                        $rowDpp = $rowTot - $rowPpn;
                    } elseif ($nilai > 0) {
                        $rowDpp = $nilai;
                        $rowPpn = $ppnDb > 0 ? $ppnDb : ($nilai * 0.11);
                        $rowTot = $rowDpp + $rowPpn;
                    } else {
                        $rowDpp = 0;
                        $rowPpn = 0;
                        $rowTot = 0;
                    }
                }

                // Akumulasi summary row
                if ($jSt == '7499' || ($isProject && $jSt == '4822' && $sumTot == 0)) {
                    $sumDpp += $rowDpp;
                    $sumPpn += $rowPpn;
                    $sumTot += $rowTot;
                }

                // Badge Modul
                $modulBadgeClass = 'modul-so';
                $modulTypeLabel = 'PROJECT / SPK';
                if ($isReceipt) {
                    $modulBadgeClass = 'modul-rec';
                    $modulTypeLabel = 'PENERIMAAN KAS/BANK';
                } elseif ($isTax) {
                    $modulBadgeClass = 'modul-tax';
                    $modulTypeLabel = 'E-FAKTUR PAJAK';
                } elseif ($isInvoice) {
                    $modulBadgeClass = 'modul-inv';
                    $modulTypeLabel = 'TAGIHAN INVOICE';
                } elseif ($isDP) {
                    $modulBadgeClass = 'modul-dp';
                    $modulTypeLabel = 'UANG MUKA DP';
                }

                // Format Nomor Dokumen Resmi
                $docNo = '-';
                if (!empty($step->nomer2) && $step->nomer2 != '-') {
                    $docNo = $step->nomer2;
                } elseif (!empty($step->nomer_top) && $step->nomer_top != '-') {
                    $docNo = $step->nomer_top;
                } elseif (!empty($step->nomer)) {
                    $docNo = $step->nomer;
                }

                $stepLabel = !empty($step->jenis_label) ? $step->jenis_label : strtoupper($step->jenis);
                $waktuText = !empty($step->dtime) ? formatTglIndo($step->dtime) : '-';
                $userText = !empty($step->oleh_nama) ? $step->oleh_nama : '-';
                $ketText = !empty($step->keterangan) ? str_replace(array("\r", "\n", "<br>", "<br/>"), " ", $step->keterangan) : '-';
                $rowClass = $isReceipt ? 'row-receipt' : ($isInvoice ? 'row-invoice' : '');
                ?>
                <tr class="<?php echo $rowClass; ?>">
                    <td class="text-center font-bold" style="color:#64748b;"><?php echo $stepNo; ?></td>
                    <td class="num-font" style="white-space:nowrap;"><?php echo $waktuText; ?></td>
                    <td>
                        <strong style="color:<?php echo $isReceipt ? '#166534' : ($isInvoice ? '#1d4ed8' : '#0f172a'); ?>;">
                            <?php echo htmlspecialchars($docNo); ?>
                        </strong>
                    </td>
                    <td class="num-font" style="color:#64748b; font-size:11px;">
                        <?php echo htmlspecialchars(!empty($step->nomer) ? $step->nomer : '-'); ?>
                    </td>
                    <td>
                        <span class="badge-modul <?php echo $modulBadgeClass; ?>"><?php echo strtoupper($step->jenis); ?></span>
                        <strong style="font-size:11.5px; color:#334155; margin-left:3px;"><?php echo htmlspecialchars($stepLabel); ?></strong>
                    </td>
                    <td style="color:#475569;"><?php echo htmlspecialchars($userText); ?></td>
                    <td class="text-right num-font">
                        <?php echo $rowDpp > 0 ? "Rp" . number_format($rowDpp, 0, ',', '.') : '-'; ?>
                    </td>
                    <td class="text-right num-font" style="color:<?php echo $rowPpn > 0 ? '#dc2626' : '#64748b'; ?>;">
                        <?php echo $rowPpn > 0 ? "Rp" . number_format($rowPpn, 0, ',', '.') : '-'; ?>
                    </td>
                    <td class="text-right num-font" style="font-weight:bold; color:<?php echo $isReceipt ? '#166534' : ($rowTot > 0 ? '#0f172a' : '#64748b'); ?>;">
                        <?php echo $rowTot > 0 ? "Rp" . number_format($rowTot, 0, ',', '.') : '-'; ?>
                    </td>
                    <td style="color:#334155; font-size:11px;">
                        <?php echo htmlspecialchars(substr($ketText, 0, 95) . (strlen($ketText) > 95 ? '...' : '')); ?>
                        <?php if (isset($amandemenEvents[$step->id]) && is_array($amandemenEvents[$step->id]) && sizeof($amandemenEvents[$step->id]) > 0): ?>
                            <?php foreach ($amandemenEvents[$step->id] as $amE): ?>
                                <div class="amandemen-box">
                                    &bull; <strong>Diskon / Adjustment [<?php echo formatTglIndo($amE->dtime_amandemen); ?>]:</strong>
                                    <em><?php echo !empty($amE->alasan) ? htmlspecialchars($amE->alasan) : 'Perubahan data'; ?></em>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10" class="text-center" style="padding:20px; color:#64748b;">
                    Tidak ada riwayat transaksi yang ditemukan.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- REKONSILIASI FINANSIAL & BUKTI PENERIMAAN KAS/BANK (EXCEL SUMMARY GRID) -->
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
    <!-- Box 1: Ringkasan Tagihan & Sisa Piutang -->
    <div>
        <div style="font-weight:bold; font-size:12px; color:#1e293b; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <span style="display:inline-block; width:4px; height:14px; background:#0284c7; border-radius:2px;"></span>
            Ringkasan Rekonsiliasi Finansial Proyek
        </div>
        <table class="summary-matrix">
            <tr>
                <td style="color:#475569; width:60%;">Total DPP Proyek (Excl. PPN):</td>
                <td class="text-right num-font" style="font-weight:600;">Rp<?php echo number_format($totDppVal, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td style="color:#dc2626; width:60%;">Total PPN (11%):</td>
                <td class="text-right num-font" style="font-weight:600; color:#dc2626;">Rp<?php echo number_format($totPpnVal, 0, ',', '.'); ?></td>
            </tr>
            <tr class="highlight-tot">
                <td style="color:#0f172a;">Total Nilai Kontrak Tagihan (Incl. PPN):</td>
                <td class="text-right num-font" style="color:#0f172a; font-size:13px;">Rp<?php echo number_format($totTag, 0, ',', '.'); ?></td>
            </tr>
            <tr class="highlight-rec">
                <td>Total Pembayaran Diterima (DP + Pelunasan):</td>
                <td class="text-right num-font" style="font-size:13px;">Rp<?php echo number_format($totDiterimaVal, 0, ',', '.'); ?></td>
            </tr>
            <tr class="highlight-sisa">
                <td style="color:<?php echo $sisaTagVal > 0 ? '#c2410c' : '#166534'; ?>;">
                    Sisa Piutang / Kurang Bayar (Termasuk Retensi):
                </td>
                <td class="text-right num-font" style="color:<?php echo $sisaTagVal > 0 ? '#c2410c' : '#166534'; ?>;">
                    Rp<?php echo number_format($sisaTagVal, 0, ',', '.'); ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- Box 2: Rincian Bukti Kuitansi Pembayaran Masuk (A/R Receipt) -->
    <div>
        <div style="font-weight:bold; font-size:12px; color:#1e293b; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
            <span style="display:inline-block; width:4px; height:14px; background:#16a34a; border-radius:2px;"></span>
            Rincian Bukti Pembayaran / Kuitansi Kas & Bank Diterima
        </div>
        <?php if (isset($paymentReceipts) && is_array($paymentReceipts) && sizeof($paymentReceipts) > 0): ?>
            <table class="excel-table" style="border:1px solid #cbd5e1;">
                <thead>
                <tr style="background:#065f46;">
                    <th style="width:30%;">No. Kuitansi</th>
                    <th style="width:25%;">Waktu</th>
                    <th style="width:20%;">Kasir/Bank</th>
                    <th style="width:25%;" class="text-right">Nominal Masuk</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($paymentReceipts as $pRec): ?>
                    <?php
                    $rDoc = !empty($pRec->nomer2) ? $pRec->nomer2 : (!empty($pRec->nomer) ? $pRec->nomer : '-');
                    $rWaktu = !empty($pRec->dtime) ? formatTglIndo($pRec->dtime) : '-';
                    $rKasir = !empty($pRec->oleh_nama) ? $pRec->oleh_nama : '-';
                    $rNet = isset($pRec->transaksi_net) ? (float)$pRec->transaksi_net : 0;
                    $rNilai = isset($pRec->transaksi_nilai) ? (float)$pRec->transaksi_nilai : 0;
                    $rNom = $rNet > 0 ? $rNet : $rNilai;
                    ?>
                    <tr style="background-color:#f0fdf4;">
                        <td><strong style="color:#166534;"><?php echo htmlspecialchars($rDoc); ?></strong></td>
                        <td class="num-font" style="font-size:11px;"><?php echo htmlspecialchars($rWaktu); ?></td>
                        <td><?php echo htmlspecialchars($rKasir); ?></td>
                        <td class="text-right num-font" style="font-weight:bold; color:#166534;">
                            Rp<?php echo number_format($rNom, 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding:15px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; color:#64748b; font-size:11.5px; text-align:center;">
                Belum ada riwayat transaksi pembayaran kas/bank yang tercatat.
            </div>
        <?php endif; ?>
    </div>
</div>

</div>

</body>
</html>
