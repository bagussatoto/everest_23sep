<!-- [AGENT_LOG]
ROLE      : Software Engineer Agent
PURPOSE   : Form UI untuk amandemen, dengan pencegahan XSS dan Smart-Detect separator
COMPLIANCE: Frontend Safety Rules (AGENTS.md)
LOG_EXPIRE: 2026-11-07
[/AGENT_LOG] -->
<!DOCTYPE html>
<html>
<head>
    <title>Diskon / Adjustment Project</title>
    <!-- Load jQuery & Bootstrap secara mandiri karena ini halaman popup tanpa layout induk -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables Core + Buttons + ColVis -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .drag-handle { cursor: grab; font-size: 1.2em; color: #888; margin-right: 5px; user-select: none; }
        .drag-handle:active { cursor: grabbing; }
        .draggable-row { transition: background-color 0.2s; }
        .draggable-row.dragging { opacity: 0.5; background-color: #e9ecef; }
        .btn { padding: 8px 15px; border: none; cursor: pointer; color: white; margin-bottom: 10px; }
        .btn-warning { background-color: #ff7700; }
        .btn-primary { background-color: #007bff; }
        .btn-danger { background-color: #dc3545; padding: 5px 10px; }
        .table th.text-right, .table td.text-right, .text-right { text-align: right !important; }
    </style>
</head>
<body>
    <div>
        <?php if (isset($_GET['saved']) && $_GET['saved'] == '1'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="box-shadow: 0 4px 12px rgba(40,167,69,0.25); border-left: 6px solid #28a745; font-size: 1.05em; padding: 15px 20px; margin-bottom: 20px;">
            <h4 style="margin-top:0; font-weight:bold; color:#155724; font-size:1.2em;">
                ✅ Diskon / Adjustment Berhasil Disimpan &amp; Dibukukan!
            </h4>
            <p style="margin-bottom:0; color:#1e7e34;">
                Data diskon / adjustment terbaru telah resmi tercatat di sistem. Halaman telah dimuat ulang dengan nilai tagihan dan mutasi akuntansi versi terkini.
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            if (window.history.replaceState) {
                var cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: cleanUrl}, '', cleanUrl);
            }
        </script>
        <?php elseif (isset($_GET['restored']) && $_GET['restored'] == '1'): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="box-shadow: 0 4px 12px rgba(23,162,184,0.25); border-left: 6px solid #17a2b8; font-size: 1.05em; padding: 15px 20px; margin-bottom: 20px;">
            <h4 style="margin-top:0; font-weight:bold; color:#0c5460; font-size:1.2em;">
                🔄 Pemulihan Versi Berhasil!
            </h4>
            <p style="margin-bottom:0; color:#117a8b;">
                Invoice telah dipulihkan ke versi histori yang dipilih. Data transaksi dan akuntansi telah diselaraskan.
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            if (window.history.replaceState) {
                var cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: cleanUrl}, '', cleanUrl);
            }
        </script>
        <?php endif; ?>

        <?php if (!empty($is_tax_approved_djp) && !empty($absorb_tax)): ?>
        <div style="background-color: #fffaf0; border: 2px solid #dd6b20; border-left: 8px solid #c05621; border-radius: 6px; padding: 16px 20px; margin-bottom: 20px; color: #7b341e; box-shadow: 0 4px 14px rgba(221,107,32,0.15);">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="font-size: 28px; line-height: 1;">⚠️</div>
                <div style="flex: 1;">
                    <strong style="font-size: 1.1em; color: #9c4221;">PERINGATAN KHUSUS: E-FAKTUR SUDAH APPROVED DJP (PENYERAPAN SELISIH PPN OLEH PERUSAHAAN)</strong>
                    <p style="margin: 6px 0 10px 0; font-size: 0.95em; line-height: 1.5;">
                        Invoice ini telah memiliki <b>e-Faktur Resmi Approved DJP</b>: <code style="background:#fed7d7; padding:2px 6px; border-radius:4px; font-weight:bold; color:#9b2c2c;"><?php echo htmlspecialchars(isset($tax_info['efaktur']) ? $tax_info['efaktur'] : '-'); ?></code> (Tanggal DJP: <?php echo htmlspecialchars(isset($tax_info['efaktur_dtime']) ? $tax_info['efaktur_dtime'] : '-'); ?>).<br>
                        Anda membuka akses diskon / adjustment dengan klausul: <b>Perusahaan secara sadar menyerap selisih PPN ke Biaya Usaha (6010 / 601000036 Beban Penjualan Lainnya)</b> tanpa membatalkan Faktur Pajak Resmi di DJP. Kewajiban PPN Keluaran ke Kas Negara tetap mengacu pada e-Faktur resmi.
                    </p>
                    <div style="background: #ffffff; border: 1px solid #ecc94b; border-radius: 5px; padding: 10px 14px; margin-bottom: 10px;">
                        <label style="margin: 0; font-weight: bold; color: #b7791f; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="chk_absorb_tax_confirm" id="chk_absorb_tax" value="1" required style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Saya mengonfirmasi bahwa manajemen/perusahaan MENYETUJUI penyerapan selisih PPN ke Biaya Usaha (Beban Penjualan Lainnya) dan mengabaikan pembatalan e-Faktur di DJP.</span>
                        </label>
                    </div>
                    <small style="color: #c53030; font-weight: 600;">* Penyerapan ini hanya sah jika nilai tagihan baru &le; nilai tagihan awal. Jika tagihan bertambah, Faktur Pengganti (011) di DJP tetap wajib diterbitkan.</small>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div style="background-color: #d4edda; border-left: 5px solid #28a745; padding: 12px; margin-bottom: 20px; color: #155724;">
            <strong>ℹ️ STATUS PAJAK (e-Faktur DJP):</strong> DRAFT / PRE-UPLOAD.<br>
            <span style="font-size: 0.9em; color: #1e7e34;">Invoice ini aman disesuaikan karena e-Faktur belum diterbitkan/disetujui DJP.</span>
        </div>
        <?php endif; ?>

        <div style="background-color: #ffe6e6; border-left: 5px solid #ff0000; padding: 15px; margin-bottom: 20px;">
            <strong style="color: #cc0000; font-size: 1.1em;">⚠️ PERHATIAN:</strong><br>
            <span style="color: #990000;">
                Transaksi ini, transaksi luar biasa.<br>
                Transaksi yang penuh resiko.<br>
                Karena itu tidak bisa diwakilkan kepada siapapun kecuali orang-orang kepercayaan perusahaan.
            </span>
        </div>
        
        <h3>Form Diskon / Adjustment Project #<?php echo $invoice_no; ?> (Project #<?php echo $project_id; ?>)</h3>
        <p>Gunakan form ini untuk melakukan penyesuaian nilai tagihan (Diskon / Scope Adjustment) secara item-per-item.</p>

        <?php
        $num_amd = isset($count_amandemen) ? (int)$count_amandemen : (!empty($history_amandemen) ? count($history_amandemen) : 0);
        $last_amd = (!empty($history_amandemen) && isset($history_amandemen[0])) ? $history_amandemen[0] : null;
        ?>

        <?php if ($num_amd === 0): ?>
            <div style="background-color: #f1f5f9; border: 1.5px solid #cbd5e1; border-left: 6px solid #64748b; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span class="badge badge-secondary" style="font-size: 0.9em; padding: 5px 10px;">📄 VERSI ASLI (DOKUMEN TERBIT AWAL)</span>
                        <span style="font-weight: bold; color: #334155; margin-left: 8px; font-size: 1.05em;">Status: Belum Pernah Disesuaikan</span>
                    </div>
                    <span class="badge badge-info" style="font-size: 0.85em; padding: 5px 10px; background-color:#0284c7;">Kuota Tersedia: 2x Diskon / Adjustment</span>
                </div>
                <p style="margin-top: 6px; margin-bottom: 0; font-size: 0.9em; color: #475569;">
                    Dokumen invoice ini masih dalam kondisi murni saat pertama kali diterbitkan. Jika disimpan, tindakan ini akan dicatat resmi sebagai <b>Revisi ke-1 (ADJ-01)</b>.
                </p>
            </div>
        <?php elseif ($num_amd === 1 && $last_amd): ?>
            <div style="background-color: #eff6ff; border: 1.5px solid #bfdbfe; border-left: 6px solid #2563eb; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(37,99,235,0.08);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span class="badge badge-primary" style="font-size: 0.95em; padding: 6px 12px; background-color: #2563eb;">
                            📝 DISKON / ADJUSTMENT #1 AKTIF (<?php echo htmlspecialchars($last_amd['No. Amandemen']); ?>)
                        </span>
                        <span style="font-weight: bold; color: #1e3a8a; margin-left: 8px; font-size: 1.05em;">
                            Status: Diskon / Adjustment ke-1 Sudah Tersimpan
                        </span>
                    </div>
                    <div>
                        <span class="badge badge-warning" style="font-size: 0.85em; padding: 6px 10px; background-color: #f59e0b; color: #ffffff;">
                            Sisa Kuota: 1x Lagi (Maks. 2x ISO 9001)
                        </span>
                        <a href="#sectionRiwayatAmandemen" class="badge badge-outline-primary" style="font-size: 0.85em; padding: 5px 10px; border: 1px solid #2563eb; color: #2563eb; background: #ffffff; text-decoration: none;">
                            📜 Riwayat Lengkap &amp; Rollback ↓
                        </a>
                    </div>
                </div>
                <div style="margin-top: 10px; padding: 10px 14px; background: #ffffff; border: 1px solid #dbeafe; border-radius: 4px; font-size: 0.9em;">
                    <table style="width: 100%; border: none; font-size: 0.95em;">
                        <tr>
                            <td style="width: 25%; color: #64748b;">📅 Waktu Penyesuaian:</td>
                            <td style="width: 35%; font-weight: bold; color: #1e293b;"><?php echo htmlspecialchars($last_amd['Waktu']); ?></td>
                            <td style="width: 20%; color: #64748b;">👤 Oleh Petugas:</td>
                            <td style="width: 20%; font-weight: bold; color: #1e293b;"><?php echo htmlspecialchars($last_amd['Oleh']); ?></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">💰 Nilai Tagihan Aktif:</td>
                            <td style="font-weight: bold; color: #1d4ed8; font-size: 1.05em;">
                                Rp <?php echo number_format($orig_gt, 0, ',', '.'); ?>
                                <span style="font-size: 0.85em; font-weight: normal; color: #475569;">(Delta: <?php echo htmlspecialchars($last_amd['Nilai Delta']); ?>)</span>
                            </td>
                            <td style="color: #64748b;">📝 Catatan Revisi #1:</td>
                            <td style="font-weight: 500; color: #334155; font-style: italic;"><?php echo htmlspecialchars($last_amd['Keterangan']); ?></td>
                        </tr>
                    </table>
                </div>
                <p style="margin-top: 8px; margin-bottom: 0; font-size: 0.88em; color: #1e40af;">
                    ℹ️ <b>Formulir di bawah ini siap untuk Diskon / Adjustment ke-2 (Revisi Terakhir)</b> jika masih ada penyesuaian yang dibutuhkan. Jika tidak ada perubahan, Anda dapat menutup jendela ini tanpa menekan simpan.
                </p>
            </div>
        <?php elseif ($num_amd >= 2): ?>
            <div style="background-color: #fef2f2; border: 1.5px solid #fecaca; border-left: 6px solid #dc2626; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <div>
                        <span class="badge badge-danger" style="font-size: 0.95em; padding: 6px 12px; background-color: #dc2626;">
                            🔒 REVISI #2 (FINAL) — TERKUNCI
                        </span>
                        <span style="font-weight: bold; color: #991b1b; margin-left: 8px; font-size: 1.05em;">
                            Status: Kuota Diskon / Adjustment Telah Habis (Maksimal 2x)
                        </span>
                    </div>
                    <a href="#sectionRiwayatAmandemen" class="badge badge-secondary" style="font-size: 0.85em; padding: 5px 10px;">
                        📜 Riwayat Lengkap ↓
                    </a>
                </div>
                <p style="margin-top: 8px; margin-bottom: 0; font-size: 0.9em; color: #7f1d1d;">
                    Invoice ini sudah pernah disesuaikan sebanyak 2 kali. Sesuai dengan protokol Anti-Fraud ISO 9001, form penagihan telah dikunci secara permanen dan tidak dapat diedit kembali.
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($project_info)): ?>
        <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; color: #0056b3;">Detail Project: <?php echo htmlspecialchars($project_info['project_nama']); ?></h4>
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 25%; color: #6c757d;">Total Nilai RAB:</td>
                    <td style="width: 25%; font-weight: bold;">Rp <?php echo number_format($project_info['nilai_project'], 0, ',', '.'); ?></td>
                    <td style="width: 25%; color: #6c757d;">Total SPK Aktif:</td>
                    <td style="width: 25%; font-weight: bold;"><?php echo number_format($project_info['total_spk']); ?> SPK</td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Total Macam Item:</td>
                    <td style="font-weight: bold;"><?php echo number_format($project_info['total_items']); ?> Item</td>
                    <td style="color: #6c757d;">Rincian Item:</td>
                    <td style="font-weight: bold;">
                        <span style="color:#28a745;"><?php echo number_format($project_info['total_produk']); ?> Unit</span> / 
                        <span style="color:#ffc107;"><?php echo number_format($project_info['total_supplies']); ?> Jasa & Material</span>
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
        
        <form id="frmAmandemen" action="<?php echo base_url(); ?>amandemen_invoice/FollowUp/validate_and_save/<?php echo $invoice_id; ?>" method="POST" target="result" onsubmit="return validateBeforeSubmit()">
            <input type="hidden" name="absorb_tax_difference" id="inp_absorb_tax_difference" value="<?php echo (!empty($absorb_tax) ? '1' : '0'); ?>">
            <div style="margin-bottom: 15px; background-color: #fff3cd; border: 1px solid #ffeba2; border-radius: 4px; padding: 10px;">
                <label style="display:block; font-weight: bold; color: #856404; margin-bottom: 5px;">
                    🔒 Catatan Diskon / Adjustment (Khusus Internal Perusahaan):
                </label>
                <span style="font-size: 0.85em; color: #555; display: block; margin-bottom: 5px;">
                    Catatan khusus internal mengenai alasan diskon / penyesuaian ini. <b>TIDAK</b> akan dicetak di Invoice Client.
                </span>
                <textarea id="catatan_amandemen_input" name="catatan_amandemen" placeholder="Alasan diskon / penyesuaian nilai untuk audit internal..." style="width:100%; height:50px; padding: 5px; border: 1px solid #ced4da; border-radius: 4px;"></textarea>
            </div>

            <h4>Rincian Produk (Jembatan Khusus SPK)</h4>
            <?php if ($project_id > 0): ?>
                <button type="button" class="btn btn-primary" onclick="openSpkPicker()">+ Pilih Produk dari SPK/RAB</button>
            <?php endif; ?>
            <button type="button" class="btn btn-success" onclick="addCustomRow()">+ Tambah Baris Kustom (Non-Stok)</button>
            <button type="button" class="btn btn-danger" onclick="removeAllRows()">🗑️ Kosongkan Tabel (Hapus Semua Baris)</button>

            <?php if (!empty($spk_info_list)): ?>
                <div style="margin-top: 12px; margin-bottom: 15px; background: #eef6ff; border: 1px solid #b6d4fe; border-radius: 6px; padding: 12px 16px;">
                    <h5 style="margin: 0 0 8px 0; color: #084298; font-weight: bold; font-size: 0.95em;">
                        📋 DAFTAR SPK & GUDANG WO TERLIBAT DALAM INVOICE INI (<?php echo count($spk_info_list); ?> SPK)
                    </h5>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <?php foreach ($spk_info_list as $spk_idx => $spk): 
                            $no_spk = !empty($spk['no_spk']) ? $spk['no_spk'] : '-';
                            $g_wo_raw = !empty($spk['gudang_wo']) ? $spk['gudang_wo'] : '';
                            $gudang_wo_val = !empty($spk['gudang_wo_nama']) ? $spk['gudang_wo_nama'] : (!empty($g_wo_raw) ? $g_wo_raw : 'Gudang Virtual WO (Auto)');
                            $paket_nama = !empty($spk['produk_paket_nama']) ? $spk['produk_paket_nama'] : '-';
                            $spk_nama_val = !empty($spk['spk_nama']) ? $spk['spk_nama'] : '-';
                            $cur_locker_stocks = (!empty($g_wo_raw) && isset($locker_stock_list[$g_wo_raw])) ? $locker_stock_list[$g_wo_raw] : array();
                        ?>
                            <div class="spk-card-container" data-nospk="<?php echo htmlspecialchars($no_spk); ?>" style="display: none; background: #ffffff; border: 1px solid #9ec5fe; border-radius: 5px; padding: 8px 14px; font-size: 0.85em; flex: 1 1 320px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                <span class="badge badge-primary" style="font-size:0.85em; margin-bottom:4px;">SPK #<?php echo ($spk_idx + 1); ?></span>
                                <div style="font-family: monospace; font-weight: bold; color: #084298; font-size:1.05em; margin-top:2px;">
                                    <?php echo htmlspecialchars($no_spk); ?>
                                </div>
                                <div style="color: #495057; margin-top: 4px;">
                                    <b>📦 Gudang Virtual WO:</b> <span class="badge badge-warning" style="font-size:0.9em; font-weight:bold; background-color:#fff3cd; color:#856404; border:1px solid #ffeba2;"><?php echo htmlspecialchars($gudang_wo_val); ?></span>
                                </div>
                                <div style="color: #495057; margin-top: 3px;">
                                    <b>📌 Nama Pekerjaan:</b> <?php echo htmlspecialchars($spk_nama_val); ?>
                                </div>
                                <div style="color: #495057; margin-top: 3px;">
                                    <b>📌 Paket/Fase:</b> <?php echo htmlspecialchars($paket_nama); ?>
                                </div>

                                <!-- INFORMASI STOK TERKUNCI DI STOCK LOCKER -->
                                <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #cbd5e0;">
                                    <b style="color: #2b6cb0;">🔒 Stok Bahan Terikat di Stock Locker:</b>
                                    <?php if (!empty($cur_locker_stocks)): ?>
                                        <ul style="margin: 4px 0 0 0; padding-left: 18px; color: #2d3748; font-size: 0.9em;">
                                            <?php foreach ($cur_locker_stocks as $stk): 
                                                $stk_pid = (int)(isset($stk['produk_id']) ? $stk['produk_id'] : (isset($stk['produk_dasar_id']) ? $stk['produk_dasar_id'] : 0));
                                                $stk_nama = htmlspecialchars(isset($stk['nama']) ? $stk['nama'] : (isset($stk['produk_nama']) ? $stk['produk_nama'] : 'Item'));
                                                $stk_state = htmlspecialchars(isset($stk['state']) ? $stk['state'] : 'hold');
                                                $stk_qty = (float)(isset($stk['jumlah']) ? $stk['jumlah'] : 0);
                                                $stk_satuan = htmlspecialchars(isset($stk['satuan']) ? $stk['satuan'] : '');
                                                $stk_tbl = htmlspecialchars($stk['_source_table']);
                                            ?>
                                                <li>
                                                    <b><?php echo $stk_nama; ?></b>
                                                    <?php if ($stk_pid > 0): ?>
                                                        <span class="badge badge-info" style="font-size:0.8em; font-family:monospace; background-color:#17a2b8;">PID: <?php echo $stk_pid; ?></span>
                                                    <?php endif; ?>
                                                    - Qty: <span class="badge badge-success"><?php echo $stk_qty; ?> <?php echo $stk_satuan; ?></span>
                                                    <span style="font-size:0.8em; color:#718096;">(State: <b><?php echo $stk_state; ?></b> | Table: <?php echo $stk_tbl; ?>)</span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div style="font-style: italic; color: #718096; margin-top: 2px;">(Belum ada item aktif terkunci di stock_locker untuk gudang WO ini)</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="no_spk_selected_msg" style="display: none; color: #666; font-style: italic; padding: 10px; text-align: center;">Belum ada item produk dari SPK yang masuk ke dalam invoice ini.</div>
                </div>
            <?php endif; ?>

            <table class="table" id="tableItems">
                <thead>
                    <tr style="background-color:#f8f9fa;">
                        <th width="50">NO</th>
                        <th>DESCRIPTION (NAMA PENAGIHAN)</th>
                        <th width="110" class="text-right">LIMIT SPK</th>
                        <th width="125" class="text-right" style="color:#d9534f; background-color:#fff3cd; border-bottom:2px solid #d9534f;">📦 QTY RETUR (FISIK)</th>
                        <th width="110" class="text-right" style="color:#28a745;">QTY PENAGIHAN</th>
                        <th width="130" class="text-right">HARGA</th>
                        <th width="140" class="text-right">JUMLAH</th>
                        <th width="50" style="text-align:center;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal = 0;
                    $rowNumber = 1;
                    if (!empty($items5_sum)): 
                        foreach ($items5_sum as $idx => $item): 
                            $qty = isset($item['jml']) ? (float)$item['jml'] : 0;
                            $harga = isset($item['harga']) ? (float)$item['harga'] : 0;
                            $subtotal = $qty * $harga;
                            $grandTotal += $subtotal;
                            $stok_aktif = isset($item['stok_aktif']) ? (float)$item['stok_aktif'] : 0;
                            $item_pid = (int)(isset($item['produk_dasar_id']) ? $item['produk_dasar_id'] : (isset($item['id']) ? $item['id'] : 0));

                            $nama_lower = isset($item['nama']) ? strtolower($item['nama']) : '';
                            $is_jasa = (strpos($nama_lower, 'jasa') !== false) || (strpos($nama_lower, 'biaya') !== false) || ($item_pid === 0);
                    ?>
                    <tr id="row_<?php echo $idx; ?>" class="draggable-row" data-no-spk="<?php echo htmlspecialchars(isset($item['no_spk']) ? $item['no_spk'] : ''); ?>" draggable="true">
                        <td class="row-no" style="display:flex; align-items:center;">
                            <span class="drag-handle" title="Tahan dan geser untuk memindahkan urutan">☰</span> 
                            <span class="nomor-urut"><?php echo $rowNumber++; ?></span>
                        </td>
                        <td>
                            <input type="hidden" name="items[<?php echo $idx; ?>][id]" value="<?php echo isset($item['id']) ? htmlspecialchars($item['id']) : ''; ?>">
                            <input type="hidden" name="items[<?php echo $idx; ?>][no_spk]" value="<?php echo htmlspecialchars(isset($item['no_spk']) ? $item['no_spk'] : ''); ?>">
                            <input type="hidden" name="items[<?php echo $idx; ?>][produk_dasar_id]" value="<?php echo isset($item['produk_dasar_id']) ? htmlspecialchars($item['produk_dasar_id']) : ''; ?>">
                            <input type="hidden" name="items[<?php echo $idx; ?>][satuan]" value="<?php echo isset($item['satuan']) ? htmlspecialchars($item['satuan']) : ''; ?>">
                            <input type="text" name="items[<?php echo $idx; ?>][nama]" class="inp-nama" data-orig-nama="<?php echo isset($item['nama']) ? htmlspecialchars($item['nama']) : ''; ?>" value="<?php echo isset($item['nama']) ? htmlspecialchars($item['nama']) : ''; ?>" style="width:100%; padding:4px; border:1px solid #ced4da; border-radius:3px;" onkeyup="checkNameChange(this)">
                            <div class="name-info" style="font-size:0.8em; color:#0056b3; margin-top:2px;"></div>
                            <?php if ($item_pid > 0): ?>
                                <div style="font-size:0.8em; color:#0056b3; font-family:monospace; font-weight:bold; margin-top:2px;">
                                    🆔 PID (ID Produk): <?php echo $item_pid; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['jenis'])): ?>
                                <?php 
                                    $jcolor = '#17a2b8';
                                    if (strtolower($item['jenis']) == 'jasa' || strtolower($item['jenis']) == 'biaya') $jcolor = '#e83e8c';
                                    else if (strtolower($item['jenis']) == 'supplies') $jcolor = '#fd7e14';
                                ?>
                                <div style="margin-top: 3px;">
                                    <span class="badge" style="font-size:0.75em; background-color:<?php echo $jcolor; ?>; color:#fff;">
                                        <i class="fa fa-cube"></i> <?php echo strtoupper(htmlspecialchars($item['jenis'])); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($item['keterangan'])): ?>
                                <div style="margin-top:6px; font-size:0.8em; color:#6c757d; background:#f8f9fa; padding:4px; border-radius:4px; border:1px solid #dee2e6; white-space:pre-wrap; line-height:1.2;"><b>Bahan Baku / Keterangan:</b><br><?php echo htmlspecialchars($item['keterangan']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($item['spk_nama'])): ?>
                                <div style="font-size:0.8em; color:#6c757d; font-style:italic; margin-top:2px;">
                                    📌 Peruntukan SPK: <b><?php echo htmlspecialchars($item['spk_nama']); ?></b>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- LIMIT SPK & STOK AKTIF -->
                        <td class="text-right" style="font-size:0.88em;">
                            <div style="color:#cc0000; font-weight:bold;">Limit: <?php echo (float)$qty; ?></div>
                            <?php if ($stok_aktif > 0): ?>
                                <div style="margin-top:3px;">
                                    <span class="badge badge-success" style="font-size:0.8em; font-weight:bold; padding:2px 5px;" title="Stok Aktif di stock_locker yang dapat di-retur">
                                        📦 Stok: <?php echo $stok_aktif; ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <div style="margin-top:3px;">
                                    <span class="badge badge-secondary" style="font-size:0.8em; background-color:#6c757d; color:#ffffff; padding:2px 5px;" title="Stok Aktif 0 di stock_locker">
                                        ⚠️ Stok: 0
                                    </span>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- KOLOM EKSPLISIT: QTY RETUR (FISIK) DITETAPKAN KONSEN OLEH USER -->
                        <td class="text-right" style="background-color:#fff8f8;">
                            <?php if ($is_jasa): ?>
                                <input type="text" name="items[<?php echo $idx; ?>][retur_qty]" class="inp-retur-qty" value="0" readonly disabled style="width:100%; text-align:right; background-color:#e9ecef; color:#6c757d; font-size:0.9em;" title="Jasa / Non-Stok tidak memiliki mutasi retur fisik">
                                <div style="font-size:0.75em; color:#6c757d; margin-top:2px; font-weight:bold;">📝 Non-Stok</div>
                            <?php else: ?>
                                <input type="text" name="items[<?php echo $idx; ?>][retur_qty]" class="inp-retur-qty" value="0" data-orig-qty="<?php echo (float)$qty; ?>" data-stok-aktif="<?php echo $stok_aktif; ?>" style="width:100%; text-align:right; border:1.5px solid #d9534f; color:#d9534f; font-weight:bold; padding:4px;" onchange="onReturQtyChange(this)" onkeyup="onReturQtyChange(this)">
                                <div class="retur-status-lbl" style="font-size:0.78em; color:#28a745; margin-top:2px; font-weight:bold;">✅ 0 Retur</div>
                            <?php endif; ?>
                        </td>

                        <!-- QTY PENAGIHAN NETT (SETELAH RETUR) -->
                        <td class="text-right">
                            <input type="text" name="items[<?php echo $idx; ?>][jml]" class="inp-qty" value="<?php echo (float)$qty; ?>" style="width:100%; text-align:right; font-weight:bold;" onchange="onQtyPenagihanChange(this)" onkeyup="onQtyPenagihanChange(this)">
                        </td>

                        <td class="text-right">
                            <input type="text" name="items[<?php echo $idx; ?>][harga]" class="inp-harga" value="<?php echo number_format($harga, 0, ',', '.'); ?>" style="width:100%; text-align:right; font-weight:bold;" onkeyup="formatCurrencyInput(this); calcSub(this);" onchange="formatCurrencyInput(this); calcSub(this);">
                        </td>
                        <td class="text-right subtotal-val"><?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    endif; 
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total DPP</th>
                        <th class="text-right" id="dppVal"><?php echo number_format($grandTotal, 0, ',', '.'); ?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">PPN 11%</th>
                        <th class="text-right" id="ppnVal"><?php echo number_format($grandTotal * 0.11, 0, ',', '.'); ?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">GRAND TOTAL</th>
                        <th class="text-right" id="grandTotalVal" style="font-size:1.1em; color:#0056b3;"><?php echo number_format($grandTotal * 1.11, 0, ',', '.'); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <!-- NOTES CLIENT DENGAN STATUS REVIEW & SAFEGUARD -->
            <div id="containerNotesClient" style="margin-top: 20px; margin-bottom: 20px; background-color: #fff8e6; border: 1.5px solid #f6ad55; border-radius: 6px; padding: 14px; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px; margin-bottom: 5px;">
                    <label style="font-weight: bold; color: #744210; margin-bottom: 0; font-size: 1.05em;">
                        📝 Notes Client (Dicetak di Invoice Penagihan):
                    </label>
                    <span id="badgeNotesClientStatus" class="badge badge-warning" style="font-size: 0.85em; padding: 5px 10px; background-color: #dd6b20; color: #ffffff;">
                        ⚠️ WAJIB DITINJAU (Catatan Masih Teks Lama)
                    </span>
                </div>
                <span style="font-size: 0.85em; color: #555; display: block; margin-bottom: 8px;">
                    Catatan ini akan tercetak langsung pada lembar Invoice penagihan untuk Client (tampil di bagian <b>N O T E S</b> Invoice). Pastikan nominal termin / keterangan pekerjaan sudah sesuai dengan tagihan baru.
                </span>
                <?php 
                    $desc_for_textarea = isset($current_description) ? $current_description : '';
                    $desc_for_textarea = str_ireplace(array('<br>', '<br/>', '<br />'), "\n", $desc_for_textarea);
                ?>
                <textarea id="main_description_input" name="description" data-orig-desc="<?php echo htmlspecialchars($desc_for_textarea); ?>" placeholder="Catatan untuk penagihan Client..." style="width:100%; height:75px; padding: 8px; border: 2px solid #ed8936; border-radius: 4px; font-size: 0.95em; line-height: 1.4; transition: border-color 0.3s;" oninput="checkNotesClientChange()"><?php echo htmlspecialchars($desc_for_textarea); ?></textarea>
                <div id="msgNotesClientWarning" style="margin-top: 6px; font-size: 0.85em; color: #c05621; font-weight: bold;">
                    ⚠️ Anda belum memperbarui Notes Client. Mohon sesuaikan teks catatan di atas atau centang konfirmasi sebelum menyimpan.
                </div>

                <div id="boxNotesClientConfirm" style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #cbd5e0; display: flex; align-items: center;">
                    <input type="checkbox" id="chkNotesClientConfirmed" name="notes_client_confirmed" value="1" onchange="checkNotesClientChange()" style="width: 17px; height: 17px; cursor: pointer;">
                    <label for="chkNotesClientConfirmed" style="margin-left: 8px; margin-bottom: 0; font-size: 0.88em; color: #4a5568; cursor: pointer; font-weight: bold;">
                        Catatan di atas sudah saya tinjau dan menyatakan teks ini sudah benar (tidak perlu diubah).
                    </label>
                </div>
            </div>
            <div id="boxJurnalPreview" style="margin-top: 20px; margin-bottom: 20px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 5px; padding: 15px;">
                <h4 style="margin-top: 0; color: #0056b3;">📊 Live Preview: Jurnal Perbaikan Akuntansi (Clean Replaced Journal)</h4>
                <p style="font-size: 0.85em; color: #6c757d; margin-bottom: 10px;">
                    Simulasi pembukuan jurnal perbaikan akuntansi yang akan dituliskan secara bersih oleh sistem sesuai nilai UI perbaikan saat diskon / adjustment disimpan:
                    <?php if(!empty($termin_nomer)): ?>
                    <br><span style="color:#0056b3; font-weight:bold;">📌 Target Transaksi: Memperbarui Jurnal Penagihan <?php echo $termin_nomer; ?></span>
                    <?php endif; ?>
                </p>
                <div id="jurnalStatusInfo" style="margin-bottom: 10px; font-weight: bold; color: #17a2b8;">
                    ℹ️ Nominal tagihan belum berubah.
                </div>
                <table class="table" id="tableJurnalPreview" style="display: none; background-color: #ffffff;">
                    <thead>
                        <tr style="background-color: #e9ecef;">
                            <th width="120">Kode Akun</th>
                            <th>Nama Rekening Akuntansi</th>
                            <th width="150" class="text-right">DEBIT (Rp)</th>
                            <th width="150" class="text-right">KREDIT (Rp)</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyJurnalPreview">
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background-color: #f1f3f5;">
                            <td colspan="2" class="text-right">TOTAL SEIMBANG (BALANCED):</td>
                            <td class="text-right" id="jurnalTotalDebit" style="color: #28a745;">Rp 0</td>
                            <td class="text-right" id="jurnalTotalKredit" style="color: #28a745;">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- TABEL 2: LIVE PREVIEW MUTASI & SALDO REKENING (DELTA & DAMPAK BUKU BESAR) -->
            <div id="boxRekeningMutasiPreview" style="margin-top: 20px; margin-bottom: 20px; background-color: #f0f4f8; border: 1px solid #b8daff; border-radius: 5px; padding: 15px; display: none;">
                <h4 style="margin-top: 0; color: #004085;">📈 Live Preview: Mutasi &amp; Saldo Rekening (Delta Penyesuaian Buku Besar)</h4>
                <p style="font-size: 0.85em; color: #495057; margin-bottom: 10px;">
                    Rincian komparasi nilai dokumen asal vs nilai baru setelah diskon / adjustment, mutasi koreksi delta, serta dampaknya terhadap saldo Buku Besar dan Kartu Piutang:
                </p>
                <table class="table table-bordered table-sm" id="tableRekeningMutasiPreview" style="background-color: #ffffff; font-size: 0.88em; margin-bottom: 0;">
                    <thead>
                        <tr style="background-color: #e2e8f0;">
                            <th width="120">Kode Akun</th>
                            <th>Rekening / Buku Besar &amp; Kartu Piutang</th>
                            <th width="150" class="text-right">Dokumen Asal (Rp)</th>
                            <th width="170" class="text-right">Mutasi Koreksi Delta (Rp)</th>
                            <th width="150" class="text-right">Dokumen Baru (Rp)</th>
                            <th width="200">Dampak Buku Besar &amp; Kartu Piutang</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRekeningMutasiPreview">
                    </tbody>
                    <tfoot>
                        <tr style="font-weight: bold; background-color: #f8f9fa;">
                            <td colspan="3" class="text-right">TOTAL PERUBAHAN TAGIHAN (NETTO):</td>
                            <td class="text-right" id="rekeningTotalDelta" style="color: #0056b3; font-weight: bold;">Rp 0</td>
                            <td colspan="2" style="color: #28a745; font-size: 0.85em; font-style: italic;">
                                * Mutasi otomatis diperbarui ke buku besar &amp; kartu piutang
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- TABEL 3: LIVE PREVIEW KARTU PIUTANG & STATUS PEMBAYARAN KASIR (SAFEGUARD) -->
            <div id="boxPaymentSourcePreview" style="margin-top: 20px; margin-bottom: 20px; background-color: #f8fafc; border: 1.5px solid #94a3b8; border-radius: 6px; padding: 15px;">
                <h4 style="margin-top: 0; color: #1e293b; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                    <span>💳 Live Preview: Status Piutang &amp; Pembayaran Kasir (Safeguard)</span>
                    <span id="badgePaymentStatus" class="badge badge-success" style="font-size: 0.85em; padding: 6px 12px;">✅ AMAN (SIAP DISKON / ADJUSTMENT)</span>
                </h4>
                <p style="font-size: 0.85em; color: #475569; margin-bottom: 12px;">
                    Simulasi real-time dampak nilai tagihan baru terhadap pembayaran kasir yang sudah masuk dan sisa piutang konsumen:
                </p>

                <!-- 4 Kotak Komparasi Ringkas -->
                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                    <div style="flex: 1 1 160px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 10px; text-align: center;">
                        <span style="font-size: 0.78em; color: #64748b; font-weight: bold; text-transform: uppercase;">Tagihan Awal (Terbit)</span>
                        <div id="lblPsTagihanAsal" style="font-size: 1.15em; font-weight: bold; color: #334155; margin-top: 3px;">Rp 0</div>
                    </div>
                    <div style="flex: 1 1 160px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 10px; text-align: center;">
                        <span style="font-size: 0.78em; color: #1e40af; font-weight: bold; text-transform: uppercase;">Tagihan Baru (Penyesuaian)</span>
                        <div id="lblPsTagihanBaru" style="font-size: 1.2em; font-weight: bold; color: #1d4ed8; margin-top: 3px;">Rp 0</div>
                    </div>
                    <div style="flex: 1 1 160px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px; padding: 10px; text-align: center;">
                        <span style="font-size: 0.78em; color: #92400e; font-weight: bold; text-transform: uppercase;">Sudah Terbayar Kasir</span>
                        <div id="lblPsTerbayar" style="font-size: 1.15em; font-weight: bold; color: #d97706; margin-top: 3px;">Rp 0</div>
                    </div>
                    <div style="flex: 1 1 160px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; padding: 10px; text-align: center;" id="cardPsSisaBaru">
                        <span id="lblPsTitleSisa" style="font-size: 0.78em; color: #166534; font-weight: bold; text-transform: uppercase;">Sisa Piutang Baru</span>
                        <div id="lblPsSisaBaru" style="font-size: 1.25em; font-weight: bold; color: #16a34a; margin-top: 3px;">Rp 0</div>
                    </div>
                </div>

                <!-- Alert Pesan Safeguard -->
                <div id="alertPaymentSource" style="padding: 10px 14px; border-radius: 4px; font-size: 0.88em; font-weight: bold; margin-bottom: 0;">
                </div>
            </div>

            <!-- RINGKASAN RETUR FISIK DARI KOLOM TABEL -->
            <div id="boxActiveReturSummary" style="margin-top: 15px; margin-bottom: 20px; background-color: #f8d7da; border: 1.5px solid #f5c6cb; border-radius: 5px; padding: 15px; display: none;">
                <strong style="color: #721c24; font-size: 1.05em;">📦 Ringkasan Mutasi Retur Fisik yang Ditetapkan (Akan Diproses ke Modul Distribusi saat Disimpan):</strong>
                <p style="margin-top: 4px; margin-bottom: 8px; font-size: 0.85em; color: #721c24;">
                    Item di bawah ini memiliki kuantitas retur fisik (> 0) dan akan langsung dipotong stoknya dari gudang WO serta diterbitkan dokumen retur resminya ke modul distribusi:
                </p>
                <ul id="listActiveReturSummary" style="margin-bottom: 0; padding-left: 20px; color: #721c24; font-weight: bold;">
                </ul>
            </div>

            <div id="boxDeletedItems" style="margin-top: 15px; margin-bottom: 20px; background-color: #fff3cd; border: 1px solid #ffeba2; border-radius: 5px; padding: 15px; display: none;">
                <strong style="color: #856404; font-size: 1.05em;">🔄 Rincian Baris yang Dihapus dari Tampilan:</strong>
                <p style="margin-top: 4px; margin-bottom: 8px; font-size: 0.85em; color: #856404;">
                    Daftar baris yang dihapus dari formulir penagihan:
                </p>
                <ul id="listDeletedItems" style="margin-bottom: 0; padding-left: 20px; color: #856404;">
                </ul>
            </div>

            <?php
            $num_amd = isset($count_amandemen) ? (int)$count_amandemen : (!empty($history_amandemen) ? count($history_amandemen) : 0);
            ?>
            <?php if ($num_amd === 0): ?>
                <button type="submit" id="btnSubmitAmandemen" class="btn btn-warning" style="font-size:16px; font-weight:bold;">
                    💾 Simpan Diskon / Adjustment (Revisi ke-1)
                </button>
            <?php elseif ($num_amd === 1): ?>
                <button type="submit" id="btnSubmitAmandemen" class="btn btn-warning" style="font-size:16px; font-weight:bold; background-color:#e65100; border-color:#d84315; color:#ffffff;">
                    💾 Simpan Diskon / Adjustment ke-2 (Revisi Terakhir / Final)
                </button>
                <div id="warningLastRevision" style="font-size:0.88em; color:#c62828; font-weight:bold; margin-top:6px;">
                    ⚠️ Perhatian: Ini adalah diskon / adjustment ke-2 (kesempatan revisi terakhir yang diizinkan). Setelah disimpan, invoice ini akan dikunci permanen sesuai kepatuhan ISO 9001.
                </div>
            <?php else: ?>
                <button type="button" id="btnSubmitAmandemen" class="btn btn-secondary" disabled style="font-size:16px; cursor:not-allowed;">
                    🔒 Batas Maksimal Diskon / Adjustment Tercapai (2x Selesai)
                </button>
                <div style="font-size:0.88em; color:#6c757d; font-weight:bold; margin-top:6px;">
                    🔒 Invoice ini telah mencapai batas maksimal 2x penyesuaian dan terkunci permanen.
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tempat hasil submit (sesuai arsitektur iframe #result ERP) -->
    <div style="margin-top:20px;">
        <iframe id="result" name="result" style="width:100%; height:80px; border:1px solid #ccc; background-color:#f9f9f9; display:none;"></iframe>
    </div>

    <!-- HISTORY SECTION -->
    <div id="sectionRiwayatAmandemen" style="margin-top:30px; border-top:2px solid #ccc; padding-top:20px;">
        <h3>📜 Riwayat Aktivitas Invoice #<?php echo $invoice_no; ?></h3>
        <p style="color:#666;">Data di bawah ini merupakan catatan sistem mengenai Diskon / Adjustment, Retur, dan Jurnal Akuntansi yang pernah terjadi pada tagihan ini.</p>

        <div style="margin-bottom: 20px;">
            <h4 style="color:#856404;">🔄 Riwayat Diskon / Adjustment Project</h4>
            <?php if (!empty($history_amandemen)): ?>
                <table class="table" style="font-size:0.85em;">
                    <thead>
                        <tr style="background-color:#fff3cd; color:#856404;">
                            <th>NO. PENYESUAIAN</th>
                            <th>WAKTU</th>
                            <th>OLEH</th>
                            <th>KETERANGAN / ALASAN</th>
                            <th class="text-right">NILAI SEBELUM</th>
                            <th class="text-right">NILAI SESUDAH</th>
                            <th class="text-right">NILAI DELTA</th>
                            <th style="text-align:center;">AKSI PEMULIHAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history_amandemen as $row): ?>
                            <tr>
                                <td><b><?php echo htmlspecialchars($row['No. Amandemen']); ?></b></td>
                                <td><?php echo htmlspecialchars($row['Waktu']); ?></td>
                                <td><?php echo htmlspecialchars($row['Oleh']); ?></td>
                                <td><?php echo htmlspecialchars($row['Keterangan']); ?></td>
                                <td class="text-right"><?php echo htmlspecialchars($row['Nilai Sebelum']); ?></td>
                                <td class="text-right"><?php echo htmlspecialchars($row['Nilai Sesudah']); ?></td>
                                <td class="text-right"><b><?php echo htmlspecialchars($row['Nilai Delta']); ?></b></td>
                                <td style="text-align:center;">
                                    <a href="<?php echo base_url(); ?>amandemen_invoice/FollowUp/restore_history/<?php echo $invoice_id; ?>/<?php echo $row['history_id']; ?>" class="btn btn-sm btn-info" style="font-size:0.8em; padding:3px 8px;" onclick="return confirm('Apakah Anda yakin ingin memulihkan (Rollback) tagihan ke versi <?php echo htmlspecialchars($row['No. Amandemen']); ?> ini?');" target="result">🔄 Pulihkan Versi Ini</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#888;">Belum ada riwayat diskon / adjustment.</p>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 20px;">
            <h4 style="color:#d9534f;">📦 Riwayat Pembuatan Retur (Otomatis)</h4>
            <?php if (!empty($history_return)): ?>
                <table class="table" style="font-size:0.85em;">
                    <thead><tr>
                        <th>ID</th><th>Nomer</th><th>Jenis</th><th>Waktu</th><th>Keterangan</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($history_return as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(isset($row['id']) ? $row['id'] : '-'); ?></td>
                                <td><?php echo htmlspecialchars(isset($row['nomer']) ? $row['nomer'] : '-'); ?></td>
                                <td><?php echo htmlspecialchars(isset($row['transaksi_jenis']) ? $row['transaksi_jenis'] : (isset($row['jenisTr']) ? $row['jenisTr'] : '-')); ?></td>
                                <td><?php echo htmlspecialchars(isset($row['dtime']) ? $row['dtime'] : (isset($row['waktu_dibuat']) ? $row['waktu_dibuat'] : '-')); ?></td>
                                <td><?php echo htmlspecialchars(isset($row['keterangan']) ? $row['keterangan'] : '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:#888;">Belum ada riwayat retur untuk invoice ini.</p>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 20px;">
            <h4 style="color:#0056b3;">📓 Jurnal Aktif di Database Saat Ini (Sebelum Diskon / Adjustment)</h4>
            <span style="font-size: 0.85em; color: #6c757d; display: block; margin-bottom: 10px;">
                Tabel di bawah menampilkan ayat jurnal yang sedang aktif di database sebelum penyesuaian disimpan. Sesuai prinsip Audit Trail ISO 9001 &amp; ISO 27001, jurnal awal di bawah ini tetap dipertahankan sebagai bukti terbit awal, dan sistem akan membukukan ayat jurnal penyesuaian (koreksi delta) secara resmi saat tombol <b>"Simpan Diskon / Adjustment"</b> ditekan.
            </span>
            <?php if (!empty($history_jurnal)): ?>
                <table class="table table-bordered table-striped compact" id="tableRiwayatJurnal" style="font-size:0.85em; width:100%;">
                    <thead>
                        <tr style="background-color:#e9ecef;">
                            <th style="width: 130px; text-align: center;">Tipe Jurnal</th>
                            <th style="width: 140px; text-align: center;">Waktu (dtime)</th>
                            <th style="width: 125px; text-align: center;">No. Transaksi</th>
                            <th style="width: 95px; text-align: center;">Kode Akun</th>
                            <th style="width: 210px;">Nama Akun (COA)</th>
                            <th style="width: 110px; text-align: right;">Debet</th>
                            <th style="width: 110px; text-align: right;">Kredit</th>
                            <th>Keterangan / Audit Trail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tot_debet = 0;
                        $tot_kredit = 0;
                        foreach ($history_jurnal as $row): 
                            $deb = isset($row['debet']) ? (float)$row['debet'] : 0;
                            $krd = isset($row['kredit']) ? (float)$row['kredit'] : 0;
                            $tot_debet += $deb;
                            $tot_kredit += $krd;
                            $is_adj = (strpos(isset($row['keterangan']) ? $row['keterangan'] : '', '[Amandemen') !== false || strpos(isset($row['keterangan']) ? $row['keterangan'] : '', '[Diskon') !== false || (isset($row['koreksi_number']) && (int)$row['koreksi_number'] > 0));
                            $rek_code = isset($row['rekening']) ? $row['rekening'] : '';
                            $rek_nama = !empty($row['rekening_2']) ? $row['rekening_2'] : (!empty($row['rekening_nama']) ? $row['rekening_nama'] : '-');
                            $trx_no = !empty($row['transaksi_no']) ? $row['transaksi_no'] : '-';
                            $dtime = !empty($row['dtime']) ? $row['dtime'] : '-';
                            $ket = !empty($row['keterangan']) ? $row['keterangan'] : '-';
                        ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php if ($is_adj): ?>
                                        <span class="badge" style="background-color:#fff3cd; color:#856404; border:1px solid #ffeeba; font-size:0.85em; padding:4px 6px;">Koreksi Delta</span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color:#cce5ff; color:#004085; border:1px solid #b8daff; font-size:0.85em; padding:4px 6px;">Penerbitan Awal</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; color:#555;"><?php echo htmlspecialchars($dtime); ?></td>
                                <td style="text-align: center; font-weight:bold;"><?php echo htmlspecialchars($trx_no); ?></td>
                                <td style="text-align: center; font-family:monospace;"><?php echo htmlspecialchars($rek_code); ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($rek_nama); ?></td>
                                <td class="text-right" style="font-weight:<?php echo $deb > 0 ? 'bold' : 'normal'; ?>; color:<?php echo $deb > 0 ? '#28a745' : '#888'; ?>;">
                                    <?php echo number_format($deb, 0, ',', '.'); ?>
                                </td>
                                <td class="text-right" style="font-weight:<?php echo $krd > 0 ? 'bold' : 'normal'; ?>; color:<?php echo $krd > 0 ? '#0056b3' : '#888'; ?>;">
                                    <?php echo number_format($krd, 0, ',', '.'); ?>
                                </td>
                                <td style="color:#555;"><?php echo htmlspecialchars($ket); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color:#f8f9fa; font-weight:bold; font-size:1.05em;">
                            <td colspan="5" class="text-right">TOTAL SEIMBANG (BALANCED):</td>
                            <td class="text-right" style="color:#28a745;">Rp <?php echo number_format($tot_debet, 0, ',', '.'); ?></td>
                            <td class="text-right" style="color:#0056b3;">Rp <?php echo number_format($tot_kredit, 0, ',', '.'); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            <?php else: ?>
                <p style="color:#888;">Belum ada riwayat jurnal akuntansi.</p>
            <?php endif; ?>
        </div>

        <!-- SACLAR TOGGLE JURNAL PENYESUAIAN MANUAL -->
        <div style="margin-bottom: 20px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 4px; padding: 15px;">
            <div style="display:flex; align-items:center;">
                <input type="checkbox" id="toggle_manual_jurnal" name="enable_manual_jurnal" value="1" onchange="toggleManualJurnal(this)" style="width:18px; height:18px; cursor:pointer;">
                <label for="toggle_manual_jurnal" style="margin-left:10px; margin-bottom:0; font-size:1.05em; font-weight:bold; color:#0056b3; cursor:pointer;">
                    ⚙️ Aktifkan Pengisian Jurnal Penyesuaian Manual (Custom COA Override)
                </label>
            </div>
            <span style="font-size:0.85em; color:#6c757d; display:block; margin-top:4px; margin-left:28px;">
                Centang opsi ini jika Anda ingin meng-override/menyesuaikan sendiri susunan Rekening COA (Debet/Kredit) untuk transaksi diskon / adjustment ini.
            </span>

            <div id="box_manual_jurnal_container" style="display:none; margin-top:15px; border-top:1px dashed #ccc; padding-top:15px;">
                <h5 style="color:#0056b3; font-weight:bold; margin-bottom:10px;">📐 Builder Jurnal Penyesuaian Manual (Harus Seimbang/Balance)</h5>
                
                <table class="table table-bordered table-sm" id="tblManualJurnal" style="font-size:0.9em; background:#ffffff;">
                    <thead>
                        <tr style="background:#e9ecef; color:#495057;">
                            <th width="150">KODE COA</th>
                            <th>NAMA REKENING COA</th>
                            <th width="180" class="text-right">DEBET (RP)</th>
                            <th width="180" class="text-right">KREDIT (RP)</th>
                            <th width="50" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyManualJurnal">
                        <!-- Default Row 1: Piutang Usaha -->
                        <tr>
                            <td>
                                <input type="text" name="jurnal_custom[0][rekening]" class="form-control form-control-sm inp-coa-code" list="dl_coa_list" value="749.1.171.50" oninput="onCoaSelected(this)" required>
                            </td>
                            <td>
                                <input type="text" name="jurnal_custom[0][rekening_nama]" class="form-control form-control-sm inp-coa-nama" value="Piutang Usaha (A/R)" readonly>
                            </td>
                            <td>
                                <input type="number" name="jurnal_custom[0][debet]" class="form-control form-control-sm text-right inp-debet" value="0" onkeyup="calcManualJurnalBalance()" onchange="calcManualJurnalBalance()">
                            </td>
                            <td>
                                <input type="number" name="jurnal_custom[0][kredit]" class="form-control form-control-sm text-right inp-kredit" value="0" onkeyup="calcManualJurnalBalance()" onchange="calcManualJurnalBalance()">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeManualJurnalRow(this)">X</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8f9fa; font-weight:bold;">
                            <td colspan="2" class="text-right">TOTAL JURNAL MANUAL:</td>
                            <td class="text-right" id="lblTotDebetManual" style="color:#0056b3;">Rp 0</td>
                            <td class="text-right" id="lblTotKreditManual" style="color:#0056b3;">Rp 0</td>
                            <td></td>
                        </tr>
                        <tr style="font-weight:bold;">
                            <td colspan="5" class="text-center" id="lblBalanceStatus" style="font-size:1.1em; color:#dc3545;">
                                ❌ KESEIMBANGAN: DUA SISI HARUS SEIMBANG (DEBET == KREDIT)
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" class="btn btn-sm btn-success" onclick="addManualJurnalRow()"><i class="fa fa-plus"></i> + Tambah Baris Jurnal Manual</button>
            </div>
        </div>
    </div>

    <script>
    var project_id = <?php echo (int)$project_id; ?>;
    var invoice_id = <?php echo (int)$invoice_id; ?>;
    var numAmandemen = <?php echo (int)(isset($count_amandemen) ? $count_amandemen : (!empty($history_amandemen) ? count($history_amandemen) : 0)); ?>;
    var rowIdx = <?php echo count($items5_sum); ?>;
    var customRowCounter = 1;

    // Nilai Tagihan Amandemen Aktif Saat Ini
    var origDPP = <?php echo (float)$orig_dpp; ?>;
    var origPPN = <?php echo (float)$orig_ppn; ?>;
    var origGrandTotal = <?php echo (float)$orig_gt; ?>;

    // Nilai Tagihan Terbit Awal Murni (Sebelum Amandemen Pertama)
    var initialDPP = <?php echo (float)(isset($dpp_awal_murni) ? $dpp_awal_murni : $orig_dpp); ?>;
    var initialPPN = <?php echo (float)(isset($ppn_awal_murni) ? $ppn_awal_murni : $orig_ppn); ?>;
    var initialGrandTotal = <?php echo (float)(isset($tagihan_awal_murni) ? $tagihan_awal_murni : $orig_gt); ?>;

    // Flag Penyerapan Selisih Pajak
    var isTaxApprovedDjp = <?php echo (!empty($is_tax_approved_djp) ? 'true' : 'false'); ?>;
    var absorbTax = <?php echo (!empty($absorb_tax) ? 'true' : 'false'); ?>;

    // Data Payment Source (Kartu Piutang & Terbayar Kasir) untuk Live Preview Safeguard
    var psTagihanAwal = initialGrandTotal;
    var psTagihanAktif = origGrandTotal;
    var psTerbayarKasir = <?php echo (float)(isset($payment_source['terbayar']) ? $payment_source['terbayar'] : 0); ?>;
    // Bawa data jurnal ke JS
    var originalJurnal = <?php echo json_encode(isset($history_jurnal) ? $history_jurnal : array()); ?>;
    var jurnalTerbitAwal = <?php echo json_encode(isset($jurnal_terbit_awal) ? $jurnal_terbit_awal : array()); ?>;
    var jurnalPenyesuaianHistory = <?php echo json_encode(isset($jurnal_penyesuaian_history) ? $jurnal_penyesuaian_history : array()); ?>;

    // Self-healing: jika orig values belum terisi atau 0, pulihkan dari originalJurnal
    if ((origDPP <= 0 || origGrandTotal <= 0) && originalJurnal && originalJurnal.length > 0) {
        for (var j_orig = 0; j_orig < originalJurnal.length; j_orig++) {
            var r_orig = originalJurnal[j_orig];
            var rc_orig = (r_orig.rekening || '').toString().trim();
            var d_orig = parseFloat(r_orig.debet) || 0;
            var k_orig = parseFloat(r_orig.kredit) || 0;
            var nm_orig = (r_orig.rekening_nama || r_orig.rekening_2 || '').toLowerCase();
            if ((rc_orig === '4010' || rc_orig === '4010030' || rc_orig === '411.1.171.01' || nm_orig.indexOf('penjualan') !== -1) && k_orig > 0 && origDPP <= 0) {
                origDPP = k_orig;
            }
            if ((rc_orig === '2030060' || rc_orig === '211.1.171.01' || nm_orig.indexOf('ppn') !== -1) && k_orig > 0 && origPPN <= 0) {
                origPPN = k_orig;
            }
            if ((rc_orig === '1010020010' || rc_orig === '1010070030' || rc_orig === '4030' || rc_orig === '749.1.171.50' || nm_orig.indexOf('piutang') !== -1) && d_orig > 0 && origGrandTotal <= 0) {
                origGrandTotal = d_orig;
            }
        }
        if (origGrandTotal <= 0 && origDPP > 0) {
            origGrandTotal = origDPP + origPPN;
        }
    }

    function openSpkPicker() {
        if (project_id <= 0) {
            alert('Project ID tidak valid, tidak dapat membuka SPK.');
            return;
        }
        var url = '<?php echo base_url(); ?>amandemen_invoice/Selector/spk_items/' + project_id + '/' + invoice_id;
        window.open(url, "PickerSPK", "width=800,height=600,scrollbars=yes");
    }

    function loadSpkItems(no_spk, project_id, current_invoice_id) {
        var base_url = "<?php echo base_url(); ?>";
        var formData = new FormData();
        formData.append('no_spk', no_spk);

        fetch(base_url + "amandemen_invoice/Selector/get_spk_items_ajax/" + project_id + "/" + current_invoice_id, {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            var data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                alert("Gagal memproses respon dari server:\n" + text.substring(0, 300));
                return;
            }
            if (data.status && data.items) {
                // Hapus baris lama yang menginduk ke no_spk ini (jika pernah di-load sebelumnya)
                if (no_spk) {
                    var oldTrs = document.querySelectorAll('#tableItems tbody tr');
                    oldTrs.forEach(function(tr) {
                        if (tr.getAttribute('data-no-spk') === no_spk) {
                            tr.parentNode.removeChild(tr);
                        }
                    });
                }

                data.items.forEach(function(itemData) {
                    var tbody = document.querySelector('#tableItems tbody');
                    var tr = document.createElement('tr');
                    tr.className = 'draggable-row';
                    tr.setAttribute('draggable', 'true');
                    tr.setAttribute('data-jenis', itemData.jenis);
                    tr.setAttribute('data-no-spk', no_spk);
                    var maxVal = (itemData.sisa_qty > 0) ? itemData.sisa_qty : '-';
                    
                    var subtotal = parseFloat(itemData.sisa_qty) * parseFloat(itemData.harga);
                    var pid = itemData.produk_dasar_id || itemData.id || 0;
                    var stokAktif = parseFloat(itemData.stok_aktif || 0);

                    var isJasa = (itemData.jenis === 'biaya' || itemData.nama.toLowerCase().includes('jasa') || itemData.nama.toLowerCase().includes('bongkar') || itemData.nama.toLowerCase().includes('penarikan') || itemData.nama.toLowerCase().includes('instalasi') || itemData.nama.toLowerCase().includes('biaya'));

                    var stokBadge = '';
                    if (isJasa) {
                        stokBadge = `<div style="margin-top:3px;"><span class="badge badge-warning" style="font-size:0.8em; background-color:#ffc107; color:#212529; padding:2px 5px;" title="Item Jasa / Biaya">⚠️ Stok: 0</span></div>`;
                    } else if (stokAktif > 0) {
                        stokBadge = `<div style="margin-top:3px;"><span class="badge badge-success" style="font-size:0.8em; font-weight:bold; padding:2px 5px;" title="Stok Aktif di stock_locker yang dapat di-retur">📦 Stok: ${stokAktif}</span></div>`;
                    } else {
                        stokBadge = `<div style="margin-top:3px;"><span class="badge badge-secondary" style="font-size:0.8em; background-color:#6c757d; color:#ffffff; padding:2px 5px;" title="Stok Aktif 0 di stock_locker">⚠️ Stok: 0</span></div>`;
                    }

                    var returColHtml = '';
                    if (isJasa) {
                        returColHtml = `
                            <input type="number" step="0.01" name="items[${rowIdx}][retur_qty]" class="inp-retur-qty" value="0" readonly disabled style="width:100%; text-align:right; background-color:#e9ecef; color:#6c757d; font-size:0.9em;" title="Jasa / Non-Stok tidak memiliki mutasi retur fisik">
                            <div style="font-size:0.75em; color:#6c757d; margin-top:2px; font-weight:bold;">📝 Non-Stok</div>
                        `;
                    } else {
                        returColHtml = `
                            <input type="number" step="0.01" min="0" max="${parseFloat(itemData.sisa_qty)}" name="items[${rowIdx}][retur_qty]" class="inp-retur-qty" value="0" data-orig-qty="${parseFloat(itemData.sisa_qty)}" data-stok-aktif="${stokAktif}" style="width:100%; text-align:right; border:1.5px solid #d9534f; color:#d9534f; font-weight:bold; padding:4px;" onchange="onReturQtyChange(this)" onkeyup="onReturQtyChange(this)">
                            <div class="retur-status-lbl" style="font-size:0.78em; color:#28a745; margin-top:2px; font-weight:bold;">✅ 0 Retur</div>
                        `;
                    }

                    var html = `
                        <td class="row-no" style="display:flex; align-items:center;">
                            <span class="drag-handle" title="Tahan dan geser untuk memindahkan urutan">☰</span> 
                            <span class="nomor-urut"></span>
                        </td>
                        <td>
                            <input type="hidden" name="items[${rowIdx}][id]" value="${itemData.id}">
                            <input type="hidden" name="items[${rowIdx}][no_spk]" value="${no_spk}">
                            <input type="hidden" name="items[${rowIdx}][produk_dasar_id]" value="${itemData.produk_dasar_id}">
                            <input type="hidden" name="items[${rowIdx}][satuan]" value="${itemData.satuan}">
                            <input type="text" name="items[${rowIdx}][nama]" class="inp-nama" data-orig-nama="${itemData.nama}" value="${itemData.nama}" style="width:100%; padding:4px; border:1px solid #ced4da; border-radius:3px;" onkeyup="checkNameChange(this)">
                            <div class="name-info" style="font-size:0.8em; color:#0056b3; margin-top:2px;"></div>
                            <div style="font-size:0.8em; color:#0056b3; font-family:monospace; font-weight:bold; margin-top:2px;">🆔 PID (ID Produk): ${pid}</div>
                        </td>
                        <td class="text-right" style="font-size:0.88em;">
                            <div style="color:#cc0000; font-weight:bold;">Limit: ${maxVal}</div>
                            ${stokBadge}
                        </td>
                        <td class="text-right" style="background-color:#fff8f8;">
                            ${returColHtml}
                        </td>
                        <td class="text-right">
                            <input type="text" name="items[${rowIdx}][jml]" class="inp-qty" value="${parseFloat(itemData.sisa_qty)}" style="width:100%; text-align:right; font-weight:bold;" onchange="onQtyPenagihanChange(this)" onkeyup="onQtyPenagihanChange(this)">
                        </td>
                        <td class="text-right">
                            <input type="text" class="inp-harga" name="items[${rowIdx}][harga]" value="${Math.round(parseFloat(itemData.harga)).toLocaleString('id-ID')}" onkeyup="formatCurrencyInput(this); calcSub(this);" onchange="formatCurrencyInput(this); calcSub(this);" style="width:100%; text-align:right; font-weight:bold;">
                        </td>
                        <td class="text-right subtotal-val">${subtotal.toLocaleString('id-ID')}</td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button>
                        </td>
                    `;
                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                    rowIdx++;
                });
                
                recalcGrandTotal();
                updateRowNumbers();
            } else {
                alert("Gagal memuat item dari SPK ini: " + (data.message || "Unknown error"));
            }
        })
        .catch(error => {
            alert("Gagal menghubungi server: " + error);
            console.error(error);
        });
    }

    function addCustomRow() {
        var tbody = document.querySelector('#tableItems tbody');
        var tr = document.createElement('tr');
        tr.className = 'draggable-row';
        tr.setAttribute('data-jenis', 'biaya');
        tr.setAttribute('draggable', 'true');
        var customId = 'custom_' + customRowCounter;
        customRowCounter++;
        
        var idx = document.querySelectorAll('#tableItems tbody tr').length + 1;

        tr.innerHTML = `
            <td class="row-no" style="display:flex; align-items:center;">
                <span class="drag-handle" title="Tahan dan geser untuk memindahkan urutan">☰</span> 
                <span class="nomor-urut">${idx}</span>
            </td>
            <td>
                <input type="text" class="form-control inp-nama" name="items[${customId}][nama]" value="Jasa / Tambahan Kustom" placeholder="Nama Jasa/Produk" style="width:100%; padding:4px; border:1px solid #ced4da; border-radius:3px;">
                <input type="hidden" name="items[${customId}][produk_dasar_id]" value="0">
                <input type="hidden" name="items[${customId}][id]" value="0">
                <div style="font-size:0.8em; color:#6c757d; font-style:italic; margin-top:2px;">📝 Baris Kustom (Non-Stok)</div>
            </td>
            <td class="text-right" style="font-size:0.88em; color:#6c757d;">-</td>
            <td class="text-right" style="background-color:#fff8f8;">
                <input type="text" name="items[${customId}][retur_qty]" class="inp-retur-qty" value="0" readonly disabled style="width:100%; text-align:right; background-color:#e9ecef; color:#6c757d;">
                <div style="font-size:0.75em; color:#6c757d; margin-top:2px;">📝 Non-Stok</div>
            </td>
            <td class="text-right">
                <input type="text" class="inp-qty" name="items[${customId}][jml]" value="1" style="width:100%; text-align:right; font-weight:bold;" onkeyup="onQtyPenagihanChange(this)" onchange="onQtyPenagihanChange(this)">
            </td>
            <td class="text-right">
                <input type="text" class="inp-harga" name="items[${customId}][harga]" value="0" style="width:100%; text-align:right; font-weight:bold;" onkeyup="formatCurrencyInput(this); calcSub(this);" onchange="formatCurrencyInput(this); calcSub(this);">
            </td>
            <td class="text-right subtotal-val">0</td>
            <td style="text-align:center;">
                <button type="button" class="btn btn-danger" onclick="removeRow(this)">X</button>
            </td>
        `;
        tbody.appendChild(tr);
        recalcGrandTotal();
        updateRowNumbers();
    }

    function parseNumber(val) {
        if (val === null || val === undefined || val === '') return 0;
        if (typeof val === 'number') return val;
        var clean = val.toString().replace(/\./g, '').replace(/,/g, '.');
        var num = parseFloat(clean);
        return isNaN(num) ? 0 : num;
    }

    function formatCurrencyInput(input) {
        if (!input) return;
        var cursorPosition = input.selectionStart;
        var originalLength = input.value.length;
        var rawVal = input.value.replace(/[^0-9]/g, '');
        if (rawVal === '') {
            input.value = '0';
            return;
        }
        var num = parseInt(rawVal, 10);
        var formatted = num.toLocaleString('id-ID');
        input.value = formatted;
        var newLength = formatted.length;
        if (cursorPosition !== null) {
            var newPos = cursorPosition + (newLength - originalLength);
            if (newPos < 0) newPos = 0;
            input.setSelectionRange(newPos, newPos);
        }
    }

    function calcSub(input) {
        if (!input) return;
        var tr = input.closest('tr');
        if (!tr) return;
        var qtyInput = tr.querySelector('.inp-qty');
        var hargaInput = tr.querySelector('.inp-harga');
        var subtotalCell = tr.querySelector('.subtotal-val');
        
        var qty = parseNumber(qtyInput ? qtyInput.value : 0);
        var harga = parseNumber(hargaInput ? hargaInput.value : 0);
        var subtotal = qty * harga;
        
        if (subtotalCell) {
            subtotalCell.innerText = subtotal.toLocaleString('id-ID');
        }
        
        recalcGrandTotal();
    }

    function onReturQtyChange(returInput) {
        var tr = returInput.closest('tr');
        var qtyInput = tr.querySelector('.inp-qty');
        var maxQty = parseNumber(returInput.getAttribute('data-orig-qty'));
        var stokAktif = parseNumber(returInput.getAttribute('data-stok-aktif'));
        var returVal = parseNumber(returInput.value);

        if (returVal < 0) {
            returVal = 0;
            returInput.value = 0;
        }

        if (returVal > maxQty) {
            alert('Jumlah retur tidak boleh melebihi Limit SPK (' + maxQty + ').');
            returVal = maxQty;
            returInput.value = returVal;
        }

        if (stokAktif <= 0 && returVal > 0) {
            alert('PERINGATAN LOGISTIK:\nStok Aktif di stock_locker bernilai 0.\nTidak ada persediaan fisik tersisa yang dapat di-retur ke gudang.');
            returVal = 0;
            returInput.value = 0;
        } else if (returVal > stokAktif && stokAktif > 0) {
            alert('Jumlah retur melebihi Stok Aktif tersisa (' + stokAktif + '). Diturunkan ke ' + stokAktif + '.');
            returVal = stokAktif;
            returInput.value = returVal;
        }

        var netQty = maxQty - returVal;
        if (netQty < 0) netQty = 0;
        if (qtyInput) qtyInput.value = netQty;

        var lbl = tr.querySelector('.retur-status-lbl');
        if (lbl) {
            if (returVal > 0) {
                lbl.innerHTML = '🔄 User Menetapkan ' + returVal + ' Retur';
                lbl.style.color = '#d9534f';
            } else {
                lbl.innerHTML = '✅ 0 Retur';
                lbl.style.color = '#28a745';
            }
        }

        if (qtyInput) calcSub(qtyInput);
    }

    function onQtyPenagihanChange(qtyInput) {
        var tr = qtyInput.closest('tr');
        var returInput = tr.querySelector('.inp-retur-qty');
        if (!returInput || returInput.disabled) {
            calcSub(qtyInput);
            return;
        }
        var maxQty = parseNumber(returInput.getAttribute('data-orig-qty'));
        var currQty = parseNumber(qtyInput.value);

        if (currQty < 0) {
            currQty = 0;
            qtyInput.value = 0;
        }

        if (currQty > maxQty) {
            alert('Qty Penagihan tidak boleh melebihi Limit SPK (' + maxQty + ').');
            currQty = maxQty;
            qtyInput.value = currQty;
        }

        var returVal = maxQty - currQty;
        if (returVal < 0) returVal = 0;
        returInput.value = returVal;
        
        onReturQtyChange(returInput);
    }

    function removeRow(btn) {
        var tr = btn.closest('tr');
        if (!tr) return;
        var nameInput = tr.querySelector('.inp-nama');
        var returInput = tr.querySelector('.inp-retur-qty');
        var pdInput = tr.querySelector('input[name*="[produk_dasar_id]"]');
        
        var nama = nameInput ? nameInput.value : 'Item Produk';
        var maxQty = returInput ? (parseNumber(returInput.getAttribute('data-orig-qty')) || 0) : 0;
        var stokAktif = returInput ? (parseNumber(returInput.getAttribute('data-stok-aktif')) || 0) : 0;
        var pdId = pdInput ? pdInput.value : '';

        var namaLower = nama.toLowerCase();
        var isJasa = (namaLower.indexOf('jasa') !== -1) || 
                     (namaLower.indexOf('biaya') !== -1) || 
                     (namaLower.indexOf('penarikan') !== -1) || 
                     (namaLower.indexOf('bongkar') !== -1) ||
                     (namaLower.indexOf('instalasi') !== -1) ||
                     (namaLower.indexOf('588st') !== -1) ||
                     (namaLower.indexOf('749') !== -1) ||
                     (namaLower.indexOf('termin') !== -1) ||
                     (namaLower.indexOf('progres') !== -1) ||
                     (tr.getAttribute('data-jenis') === 'biaya') ||
                     (stokAktif <= 0);

        var isDuplicate = false;
        if (pdId !== '' && pdId !== '0') {
            var allPdInputs = document.querySelectorAll('#tableItems tbody input[name*="[produk_dasar_id]"]');
            allPdInputs.forEach(function(input) {
                if (input !== pdInput && input.value === pdId) {
                    isDuplicate = true;
                }
            });
        }

        var list = document.getElementById('listDeletedItems');
        var box = document.getElementById('boxDeletedItems');
        if (list && box) {
            var li = document.createElement('li');
            li.style.marginBottom = "6px";
            if (isDuplicate) {
                li.innerHTML = "📌 <strong>" + escapeHtml(nama) + "</strong> ➔ <span style='color:#0056b3; font-weight:bold;'>Baris Ganda Dihapus (Koreksi Tampilan)</span>";
            } else if (namaLower.indexOf('588st') !== -1 || namaLower.indexOf('749') !== -1) {
                li.innerHTML = "📌 <strong>" + escapeHtml(nama) + "</strong> ➔ <span style='color:#6c757d; font-weight:bold;'>Dihapus (Header Realisasi SPK digantikan rincian item SPK baru)</span>";
            } else if (isJasa || stokAktif <= 0) {
                li.innerHTML = "📌 <strong>" + escapeHtml(nama) + "</strong> ➔ <span style='color:#6c757d; font-weight:bold;'>Dihapus (Item Non-Stok / Jasa, Tidak Ada Mutasi Fisik)</span>";
            } else {
                li.innerHTML = "📌 <strong>" + escapeHtml(nama) + "</strong> (Qty Awal: <strong>" + maxQty + " Unit</strong> | Stok: <strong>" + stokAktif + "</strong>) ➔ <span style='color:#d9534f; font-weight:bold;'>100% Menjadi Retur Fisik ke Gudang</span>";
            }
            list.appendChild(li);
            box.style.display = 'block';
        }

        tr.parentNode.removeChild(tr);
        recalcGrandTotal();
        updateRowNumbers();
    }

    function removeAllRows() {
        if (!confirm("Apakah Anda yakin ingin MENGHAPUS SEMUA BARIS di tabel penagihan ini?\nTabel akan dikosongkan agar Anda dapat memuat data segar dari SPK.")) {
            return;
        }
        var tbody = document.querySelector('#tableItems tbody');
        if (tbody) {
            tbody.innerHTML = '';
        }
        recalcGrandTotal();
        updateRowNumbers();
    }

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function updateRowNumbers() {
        var rows = document.querySelectorAll('#tableItems tbody tr');
        rows.forEach(function(row, index) {
            var numSpan = row.querySelector('.nomor-urut');
            if (numSpan) {
                numSpan.innerText = index + 1;
            }
        });
    }
    function updateSpkCardVisibility() {
        var activeSpks = {};
        document.querySelectorAll('#tableItems tbody tr.draggable-row').forEach(function(tr) {
            var val = tr.getAttribute('data-no-spk');
            if (val && val.trim() !== "") {
                activeSpks[val.trim()] = true;
            }
        });
        
        var anyVisible = false;
        document.querySelectorAll('.spk-card-container').forEach(function(card) {
            var noSpk = card.getAttribute('data-nospk');
            if (activeSpks[noSpk]) {
                card.style.display = 'block';
                anyVisible = true;
            } else {
                card.style.display = 'none';
            }
        });
        
        var noSpkMsg = document.getElementById('no_spk_selected_msg');
        if (noSpkMsg) {
            noSpkMsg.style.display = anyVisible ? 'none' : 'block';
        }
    }

    function recalcGrandTotal() {
        var sum = 0;
        var trs = document.querySelectorAll('#tableItems tbody tr');
        trs.forEach(function(tr) {
            var qty = parseNumber(tr.querySelector('.inp-qty') ? tr.querySelector('.inp-qty').value : 0);
            var harga = parseNumber(tr.querySelector('.inp-harga') ? tr.querySelector('.inp-harga').value : 0);
            sum += (qty * harga);
        });
        
        var ppn = Math.round(sum * 0.11);
        var grandTotal = sum + ppn;

        var dppElem = document.getElementById('dppVal');
        var ppnElem = document.getElementById('ppnVal');
        var gtElem = document.getElementById('grandTotalVal');

        if (dppElem) dppElem.innerText = sum.toLocaleString('id-ID');
        if (ppnElem) ppnElem.innerText = ppn.toLocaleString('id-ID');
        if (gtElem) gtElem.innerText = grandTotal.toLocaleString('id-ID');

        // Auto-update nominal Inc.PPn di dalam textarea Notes Client (Smart Helper)
        var notesElem = document.getElementById('main_description_input');
        if (notesElem && notesElem.value.indexOf('Inc.PPn') !== -1) {
            var oldVal = notesElem.value;
            notesElem.value = notesElem.value.replace(/\([0-9\.\,]+\)\s*Inc\.PPn/i, '(' + grandTotal.toLocaleString('id-ID') + ') Inc.PPn');
            if (oldVal !== notesElem.value) {
                notesElem.style.borderColor = '#0056b3';
            }
        }
        checkNotesClientChange();

        updateJurnalPreview(sum, ppn, grandTotal);
        updatePaymentSourcePreview(grandTotal);
        updateReturSummary();
        updateSpkCardVisibility();
    }

    function updateReturSummary() {
        var box = document.getElementById('boxActiveReturSummary');
        var list = document.getElementById('listActiveReturSummary');
        if (!box || !list) return;

        var rows = document.querySelectorAll('#tableItems tbody tr');
        var returItems = [];
        rows.forEach(function(tr) {
            var nameInput = tr.querySelector('.inp-nama');
            var returInput = tr.querySelector('.inp-retur-qty');
            var returVal = parseNumber(returInput ? returInput.value : 0);
            if (returVal > 0) {
                var nama = nameInput ? nameInput.value : 'Item Produk';
                returItems.push({ nama: nama, qty: returVal });
            }
        });

        if (returItems.length > 0) {
            var html = '';
            returItems.forEach(function(it) {
                html += '<li>🚚 <strong>' + escapeHtml(it.nama) + '</strong> ➔ Retur Fisik: <strong>' + it.qty + ' Unit</strong> ke Gudang WO</li>';
            });
            list.innerHTML = html;
            box.style.display = 'block';
        } else {
            list.innerHTML = '';
            box.style.display = 'none';
        }
    }

    function updateJurnalPreview(currDPP, currPPN, currGrandTotal) {
        var boxTable = document.getElementById('tableJurnalPreview');
        var statusInfo = document.getElementById('jurnalStatusInfo');
        var tbody = document.getElementById('tbodyJurnalPreview');

        boxTable.style.display = 'table';
        var html = '';

        // Status keterangan di atas tabel jurnal bersih
        var deltaFormFromActive = currGrandTotal - origGrandTotal;
        if (Math.abs(deltaFormFromActive) >= 0.01) {
            statusInfo.innerHTML = '📊 <b>Simulasi Jurnal Bersih Aktif (Clean Replaced Journal)</b>: Nilai jurnal akan disesuaikan menjadi Grand Total: <b>Rp ' + currGrandTotal.toLocaleString('id-ID') + '</b> (DPP: Rp ' + currDPP.toLocaleString('id-ID') + ', PPN: Rp ' + currPPN.toLocaleString('id-ID') + ') saat diskon / adjustment disimpan.';
            statusInfo.style.color = '#0056b3';
        } else {
            statusInfo.innerHTML = 'ℹ️ <b>Jurnal Bersih Aktif (Clean Replaced Journal)</b>: Struktur pembukuan sah merefleksikan nilai tagihan aktif saat ini (Grand Total: <b>Rp ' + currGrandTotal.toLocaleString('id-ID') + '</b>).';
            statusInfo.style.color = '#28a745';
        }

        // =========================================================================
        // 1. TABEL 1: 5 AKUN STANDAR RESMI TERMIN PROYEK EVEREST ERP (BERSIH & SEIMBANG)
        // =========================================================================
        var deltaTaxAbsorbed = 0;
        if (absorbTax && (initialPPN - currPPN) > 0) {
            deltaTaxAbsorbed = (initialPPN - currPPN);
        }

        var cleanJournalEntries = [
            { rek: '1010020010', nama: 'Piutang Dagang (A/R)', debet: currGrandTotal, kredit: 0 },
            { rek: '4030', nama: 'Penjualan Belum Realisasi', debet: currGrandTotal, kredit: 0 },
            { rek: '1010070030', nama: 'Piutang Usaha Belum Realisasi Project', debet: 0, kredit: currGrandTotal },
            { rek: '4010', nama: 'Penjualan', debet: 0, kredit: currDPP }
        ];

        if (absorbTax && deltaTaxAbsorbed > 0) {
            cleanJournalEntries.push({ rek: '2030060', nama: 'PPN Out (Keluaran - Mengacu SPT DJP)', debet: 0, kredit: initialPPN });
            cleanJournalEntries.push({ rek: '6010', nama: 'Biaya Usaha (Beban Penjualan Lainnya - 601000036)', debet: deltaTaxAbsorbed, kredit: 0 });
        } else {
            cleanJournalEntries.push({ rek: '2030060', nama: 'PPN Out (Keluaran)', debet: 0, kredit: currPPN });
        }

        var totalDebit = 0;
        var totalKredit = 0;

        for (var i = 0; i < cleanJournalEntries.length; i++) {
            var cj = cleanJournalEntries[i];
            totalDebit += cj.debet;
            totalKredit += cj.kredit;

            html += '<tr>';
            html += '<td><code>' + cj.rek + '</code></td>';
            html += '<td><b>' + escapeHtml(cj.nama) + '</b></td>';
            if (cj.debet > 0) {
                html += '<td class="text-right font-weight-bold" style="color:#28a745;">' + cj.debet.toLocaleString('id-ID') + '</td>';
                html += '<td class="text-right text-muted">-</td>';
            } else {
                html += '<td class="text-right text-muted">-</td>';
                html += '<td class="text-right font-weight-bold" style="color:#0056b3;">' + cj.kredit.toLocaleString('id-ID') + '</td>';
            }
            html += '</tr>';
        }

        tbody.innerHTML = html;
        var debElem = document.getElementById('jurnalTotalDebit');
        var krdElem = document.getElementById('jurnalTotalKredit');
        if (debElem) debElem.innerText = 'Rp ' + totalDebit.toLocaleString('id-ID');
        if (krdElem) krdElem.innerText = 'Rp ' + totalKredit.toLocaleString('id-ID');

        // =========================================================================
        // 2. TABEL 2: LIVE PREVIEW MUTASI & SALDO REKENING (KOMPARASI AWAL VS BARU)
        // =========================================================================
        var boxRekening = document.getElementById('boxRekeningMutasiPreview');
        var tbodyRekening = document.getElementById('tbodyRekeningMutasiPreview');
        var rekeningTotalDeltaEl = document.getElementById('rekeningTotalDelta');

        if (boxRekening && tbodyRekening) {
            boxRekening.style.display = 'block';
            var htmlR = '';

            var deltaGT_fromInit = currGrandTotal - initialGrandTotal;
            var deltaDPP_fromInit = currDPP - initialDPP;
            var deltaPPN_fromInit = currPPN - initialPPN;

            var ppnMutasiRow;
            if (absorbTax && deltaPPN_fromInit < 0) {
                ppnMutasiRow = {
                    rek: '2030060',
                    nama: 'PPN Out (Keluaran)',
                    posAsal: '(K)',
                    oldVal: initialPPN,
                    newVal: initialPPN,
                    delta: 0,
                    posisi: 'TETAP',
                    dampak: 'Hutang PPN DJP tidak berubah (tetap sesuai e-Faktur resmi DJP)'
                };
            } else {
                ppnMutasiRow = {
                    rek: '2030060',
                    nama: 'PPN Out (Keluaran)',
                    posAsal: '(K)',
                    oldVal: initialPPN,
                    newVal: currPPN,
                    delta: deltaPPN_fromInit,
                    posisi: (deltaPPN_fromInit < 0 ? 'DEBET' : 'KREDIT'),
                    dampak: (deltaPPN_fromInit < 0 ? 'Koreksi penurunan hutang PPN (DJP)' : (deltaPPN_fromInit > 0 ? 'Penambahan hutang PPN (DJP)' : 'Nilai tetap, tidak ada pergeseran'))
                };
            }

            var mutasiRows = [
                {
                    rek: '1010020010',
                    nama: 'Piutang Dagang (A/R)',
                    posAsal: '(D)',
                    oldVal: initialGrandTotal,
                    newVal: currGrandTotal,
                    delta: deltaGT_fromInit,
                    posisi: (deltaGT_fromInit < 0 ? 'KREDIT' : 'DEBET'),
                    dampak: (deltaGT_fromInit < 0 ? 'Koreksi pengurangan piutang customer' : (deltaGT_fromInit > 0 ? 'Penambahan piutang customer' : 'Nilai tetap, tidak ada pergeseran'))
                },
                {
                    rek: '4010',
                    nama: 'Penjualan',
                    posAsal: '(K)',
                    oldVal: initialDPP,
                    newVal: currDPP,
                    delta: deltaDPP_fromInit,
                    posisi: (deltaDPP_fromInit < 0 ? 'DEBET' : 'KREDIT'),
                    dampak: (deltaDPP_fromInit < 0 ? 'Koreksi penurunan pendapatan penjualan' : (deltaDPP_fromInit > 0 ? 'Penambahan pendapatan penjualan' : 'Nilai tetap, tidak ada pergeseran'))
                },
                ppnMutasiRow
            ];

            if (absorbTax && deltaPPN_fromInit < 0) {
                mutasiRows.push({
                    rek: '6010',
                    nama: 'Biaya Usaha (Pembantu: 601000036 Beban Penjualan Lainnya)',
                    posAsal: '(D)',
                    oldVal: 0,
                    newVal: Math.abs(deltaPPN_fromInit),
                    delta: Math.abs(deltaPPN_fromInit),
                    posisi: 'DEBET',
                    dampak: 'Perusahaan menyerap selisih PPN ke Biaya Usaha (Beban Penjualan Lainnya) tanpa membatalkan e-Faktur DJP'
                });
            }

            mutasiRows.push({
                rek: '4030',
                nama: 'Penjualan Belum Realisasi',
                posAsal: '(D)',
                oldVal: initialGrandTotal,
                newVal: currGrandTotal,
                delta: deltaGT_fromInit,
                posisi: (deltaGT_fromInit < 0 ? 'KREDIT' : 'DEBET'),
                dampak: (deltaGT_fromInit != 0 ? 'Penyesuaian kontra termin proyek' : 'Nilai tetap, tidak ada pergeseran')
            });
            mutasiRows.push({
                rek: '1010070030',
                nama: 'Piutang Usaha Belum Realisasi Project',
                posAsal: '(K)',
                oldVal: initialGrandTotal,
                newVal: currGrandTotal,
                delta: deltaGT_fromInit,
                posisi: (deltaGT_fromInit < 0 ? 'DEBET' : 'KREDIT'),
                dampak: (deltaGT_fromInit != 0 ? 'Penyesuaian kontra termin proyek' : 'Nilai tetap, tidak ada pergeseran')
            });

            for (var m = 0; m < mutasiRows.length; m++) {
                var mr = mutasiRows[m];
                var deltaBadge = '<span class="text-muted">- Tetap -</span>';
                if (Math.abs(mr.delta) >= 0.01) {
                    var badgeStyle = (mr.posisi === 'DEBET') ? 'background-color:#28a745;' : 'background-color:#007bff;';
                    deltaBadge = '<span class="badge" style="' + badgeStyle + ' color:#ffffff; padding:4px 8px; font-size:0.88em;">' + mr.posisi + ' Rp ' + Math.abs(mr.delta).toLocaleString('id-ID') + '</span>';
                }

                htmlR += '<tr>';
                htmlR += '<td><code>' + mr.rek + '</code></td>';
                htmlR += '<td><b>' + escapeHtml(mr.nama) + '</b></td>';
                htmlR += '<td class="text-right">' + mr.oldVal.toLocaleString('id-ID') + ' ' + mr.posAsal + '</td>';
                htmlR += '<td class="text-right">' + deltaBadge + '</td>';
                htmlR += '<td class="text-right font-weight-bold" style="color:#0056b3;">' + mr.newVal.toLocaleString('id-ID') + ' ' + mr.posAsal + '</td>';
                htmlR += '<td><small style="color:#555;">' + escapeHtml(mr.dampak) + '</small></td>';
                htmlR += '</tr>';
            }

            tbodyRekening.innerHTML = htmlR;

            if (rekeningTotalDeltaEl) {
                if (Math.abs(deltaGT_fromInit) >= 0.01) {
                    var signGt = deltaGT_fromInit >= 0 ? '+ Rp ' : '- Rp ';
                    var statusTeks = deltaGT_fromInit < 0 ? ' (Turun)' : ' (Naik)';
                    rekeningTotalDeltaEl.innerHTML = '<span style="color:' + (deltaGT_fromInit < 0 ? '#dc3545' : '#28a745') + '; font-weight:bold;">' + signGt + Math.abs(deltaGT_fromInit).toLocaleString('id-ID') + statusTeks + '</span>';
                } else {
                    rekeningTotalDeltaEl.innerHTML = '<span style="color:#6c757d; font-weight:bold;">Rp 0 (Tetap)</span>';
                }
            }
        }
    }

    function updatePaymentSourcePreview(currGrandTotal) {
        var box = document.getElementById('boxPaymentSourcePreview');
        if (!box) return;

        var sisaBaru = currGrandTotal - psTerbayarKasir;
        var badge = document.getElementById('badgePaymentStatus');
        var alertBox = document.getElementById('alertPaymentSource');
        var btnSubmit = document.getElementById('btnSubmitAmandemen');
        var cardSisa = document.getElementById('cardPsSisaBaru');
        var titleSisa = document.getElementById('lblPsTitleSisa');

        var lblTagihanAsal = document.getElementById('lblPsTagihanAsal');
        var lblTagihanBaru = document.getElementById('lblPsTagihanBaru');
        var lblTerbayar = document.getElementById('lblPsTerbayar');
        var lblSisaBaru = document.getElementById('lblPsSisaBaru');

        if (lblTagihanAsal) lblTagihanAsal.innerText = 'Rp ' + psTagihanAwal.toLocaleString('id-ID');
        if (lblTagihanBaru) lblTagihanBaru.innerText = 'Rp ' + currGrandTotal.toLocaleString('id-ID');
        if (lblTerbayar) lblTerbayar.innerText = 'Rp ' + psTerbayarKasir.toLocaleString('id-ID');

        if (sisaBaru < -0.01) {
            // Kondisi DEFISIT / LEBIH BAYAR KONSUMEN (Tagihan baru lebih kecil dari yang sudah dibayar)
            var defisit = Math.abs(sisaBaru);
            if (titleSisa) titleSisa.innerText = 'LEBIH BAYAR KONSUMEN';
            if (lblSisaBaru) {
                lblSisaBaru.innerText = '- Rp ' + defisit.toLocaleString('id-ID');
                lblSisaBaru.style.color = '#dc3545';
            }
            if (cardSisa) {
                cardSisa.style.background = '#fef2f2';
                cardSisa.style.borderColor = '#fca5a5';
            }
            if (badge) {
                badge.className = 'badge badge-danger';
                badge.style.backgroundColor = '#dc3545';
                badge.innerHTML = '⛔ DITOLAK: TAGIHAN KURANG DARI PEMBAYARAN';
            }
            if (alertBox) {
                alertBox.style.display = 'block';
                alertBox.style.backgroundColor = '#fee2e2';
                alertBox.style.border = '1.5px solid #fca5a5';
                alertBox.style.color = '#991b1b';
                alertBox.innerHTML = '⛔ <b>PERINGATAN DEFISIT (LEBIH BAYAR KONSUMEN)</b>:<br>' +
                    'Nominal tagihan baru (<b>Rp ' + currGrandTotal.toLocaleString('id-ID') + '</b>) lebih kecil dari pembayaran yang sudah diterima oleh kasir (<b>Rp ' + psTerbayarKasir.toLocaleString('id-ID') + '</b>).<br>' +
                    'Terdapat <b>Kelebihan Bayar Konsumen sebesar Rp ' + defisit.toLocaleString('id-ID') + '</b>.<br>' +
                    '<span style="font-size:0.9em; font-weight:normal;">Sistem mengunci tombol simpan agar tidak terjadi anomali pembukuan kasir. Harap batalkan transaksi penerimaan kas terlebih dahulu jika ada penyesuaian nilai.</span>';
            }
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.className = 'btn btn-secondary';
                btnSubmit.style.cursor = 'not-allowed';
                btnSubmit.innerHTML = '⛔ Ditolak: Tagihan Lebih Kecil Dari Pembayaran Kasir';
            }
        } else {
            // Kondisi NORMAL / AMAN (Tagihan baru >= Terbayar)
            var isLunasPenuh = (Math.abs(sisaBaru) < 0.01 && psTerbayarKasir > 0);
            if (titleSisa) titleSisa.innerText = 'SISA PIUTANG BARU';
            if (lblSisaBaru) {
                lblSisaBaru.innerText = 'Rp ' + Math.max(0, sisaBaru).toLocaleString('id-ID');
                lblSisaBaru.style.color = isLunasPenuh ? '#0056b3' : '#16a34a';
            }
            if (cardSisa) {
                cardSisa.style.background = isLunasPenuh ? '#e8f4fd' : '#f0fdf4';
                cardSisa.style.borderColor = isLunasPenuh ? '#b8daff' : '#bbf7d0';
            }
            if (badge) {
                badge.className = 'badge badge-success';
                badge.style.backgroundColor = '#28a745';
                badge.innerHTML = isLunasPenuh ? '✅ LUNAS PENUH' : '✅ AMAN (SIAP DISKON / ADJUSTMENT)';
            }
            if (alertBox) {
                alertBox.style.display = 'block';
                if (isLunasPenuh) {
                    alertBox.style.backgroundColor = '#d4edda';
                    alertBox.style.border = '1.5px solid #c3e6cb';
                    alertBox.style.color = '#155724';
                    alertBox.innerHTML = '✅ <b>Status Piutang Valid &amp; Lunas Penuh</b>: Konsumen telah melunasi pembayaran kasir sebesar <b>Rp ' + psTerbayarKasir.toLocaleString('id-ID') + '</b> secara penuh (100%). Sisa piutang konsumen di kasir adalah <b>Rp 0 (LUNAS)</b>.';
                } else if (psTerbayarKasir > 0) {
                    alertBox.style.backgroundColor = '#d1ecf1';
                    alertBox.style.border = '1.5px solid #bee5eb';
                    alertBox.style.color = '#0c5460';
                    alertBox.innerHTML = 'ℹ️ <b>Status Piutang Valid</b>: Konsumen sudah mencicil pembayaran sebesar <b>Rp ' + psTerbayarKasir.toLocaleString('id-ID') + '</b>. ' +
                        'Setelah diskon / adjustment disimpan, sisa piutang konsumen di kasir akan disesuaikan menjadi <b>Rp ' + sisaBaru.toLocaleString('id-ID') + '</b>.';
                } else {
                    alertBox.style.backgroundColor = '#d4edda';
                    alertBox.style.border = '1.5px solid #c3e6cb';
                    alertBox.style.color = '#155724';
                    alertBox.innerHTML = '✅ <b>Status Piutang Valid</b>: Belum ada pembayaran kasir yang masuk (Terbayar: Rp 0). ' +
                        'Seluruh nominal tagihan baru sebesar <b>Rp ' + currGrandTotal.toLocaleString('id-ID') + '</b> akan menjadi saldo piutang aktif konsumen di kasir.';
                }
            }
            if (btnSubmit) {
                var chkManual = document.getElementById('toggle_manual_jurnal');
                if (!chkManual || !chkManual.checked) {
                    if (numAmandemen >= 2) {
                        btnSubmit.disabled = true;
                        btnSubmit.className = 'btn btn-secondary';
                        btnSubmit.style.cursor = 'not-allowed';
                        btnSubmit.innerHTML = '🔒 Batas Maksimal Diskon / Adjustment Tercapai (2x Selesai)';
                    } else {
                        btnSubmit.disabled = false;
                        btnSubmit.className = 'btn btn-warning';
                        btnSubmit.style.cursor = 'pointer';
                        if (numAmandemen === 1) {
                            btnSubmit.style.backgroundColor = '#e65100';
                            btnSubmit.style.borderColor = '#d84315';
                            btnSubmit.style.color = '#ffffff';
                            btnSubmit.innerHTML = '💾 Simpan Diskon / Adjustment ke-2 (Revisi Terakhir / Final)';
                        } else {
                            btnSubmit.innerHTML = '💾 Simpan Diskon / Adjustment (Revisi ke-1)';
                        }
                    }
                }
            }
        }
    }

    function checkNotesClientChange() {
        var textarea = document.getElementById('main_description_input');
        if (!textarea) return true;

        var origDesc = (textarea.getAttribute('data-orig-desc') || '').trim();
        var currDesc = textarea.value.trim();
        var badge = document.getElementById('badgeNotesClientStatus');
        var container = document.getElementById('containerNotesClient');
        var msgWarning = document.getElementById('msgNotesClientWarning');
        var chkConfirm = document.getElementById('chkNotesClientConfirmed');
        var isConfirmed = chkConfirm && chkConfirm.checked;

        var isModified = (currDesc !== origDesc);

        if (isModified) {
            // Teks sudah diubah / diperbarui oleh user
            textarea.style.borderColor = '#28a745';
            if (container) {
                container.style.backgroundColor = '#f0fdf4';
                container.style.borderColor = '#86efac';
            }
            if (badge) {
                badge.className = 'badge badge-success';
                badge.style.backgroundColor = '#28a745';
                badge.innerHTML = '✅ TELAH DIPERBARUI';
            }
            if (msgWarning) {
                msgWarning.style.color = '#155724';
                msgWarning.innerHTML = '✅ Catatan telah disesuaikan dan siap dicetak ke invoice.';
            }
            return true;
        } else if (isConfirmed) {
            // Teks sama, tapi user sudah mencentang konfirmasi sadar
            textarea.style.borderColor = '#0056b3';
            if (container) {
                container.style.backgroundColor = '#eff6ff';
                container.style.borderColor = '#93c5fd';
            }
            if (badge) {
                badge.className = 'badge badge-info';
                badge.style.backgroundColor = '#0056b3';
                badge.innerHTML = '✅ DIKONFIRMASI (SUDAH DITINJAU)';
            }
            if (msgWarning) {
                msgWarning.style.color = '#004085';
                msgWarning.innerHTML = 'ℹ️ Anda mengonfirmasi tetap menggunakan catatan asal.';
            }
            return true;
        } else {
            // Belum diubah dan belum dikonfirmasi
            textarea.style.borderColor = '#ed8936';
            if (container) {
                container.style.backgroundColor = '#fff8e6';
                container.style.borderColor = '#f6ad55';
            }
            if (badge) {
                badge.className = 'badge badge-warning';
                badge.style.backgroundColor = '#dd6b20';
                badge.innerHTML = '⚠️ WAJIB DITINJAU (Catatan Masih Teks Lama)';
            }
            if (msgWarning) {
                msgWarning.style.color = '#c05621';
                msgWarning.innerHTML = '⚠️ Anda belum memperbarui Notes Client. Mohon sesuaikan teks catatan di atas atau centang konfirmasi sebelum menyimpan.';
            }
            return false;
        }
    }

    function validateBeforeSubmit() {
        if (numAmandemen >= 2) {
            alert('AKSES DITOLAK:\nInvoice ini sudah pernah disesuaikan sebanyak 2 kali.\nSesuai protokol Anti-Fraud ISO 9001, batas maksimal penyesuaian adalah 2 kali.');
            return false;
        }

        var curDPP = parseNumber(document.getElementById('dppVal').innerText);
        var curPPN = parseNumber(document.getElementById('ppnVal').innerText);
        var curGT = curDPP + curPPN;

        if (isTaxApprovedDjp && absorbTax) {
            var chkAbsorb = document.getElementById('chk_absorb_tax');
            if (chkAbsorb && !chkAbsorb.checked) {
                alert('PERSETUJUAN DIBUTUHKAN:\n\nInvoice ini telah memiliki e-Faktur Approved DJP.\nUntuk memproses diskon / adjustment tanpa pembatalan e-Faktur di DJP, Anda WAJIB mencentang kotak persetujuan: "Saya mengonfirmasi bahwa perusahaan MENYETUJUI penyerapan selisih PPN ke Beban Pajak internal".');
                chkAbsorb.focus();
                return false;
            }
            if (curGT > (initialGrandTotal + 0.01)) {
                alert('DISKON / ADJUSTMENT DITOLAK (ANTI-UNDERREPORTING GUARD):\n\nPenyerapan selisih pajak tanpa pembatalan e-Faktur HANYA DIPERBOLEHKAN jika nominal tagihan baru LEBIH KECIL ATAU SAMA dengan tagihan awal (<= Rp ' + initialGrandTotal.toLocaleString('id-ID') + ').\n\nKarena tagihan baru (Rp ' + curGT.toLocaleString('id-ID') + ') mengalami kenaikan, Anda WAJIB membatalkan e-Faktur lama / menerbitkan Faktur Pajak Pengganti (011) di DJP untuk menghindari tindak pidana kurang setor PPN.');
                return false;
            }
            var inpAbsorb = document.getElementById('inp_absorb_tax_difference');
            if (inpAbsorb) inpAbsorb.value = '1';
        }

        if (psTerbayarKasir > 0 && curGT < psTerbayarKasir) {
            alert('DISKON / ADJUSTMENT DITOLAK:\nNominal tagihan baru (Rp ' + curGT.toLocaleString('id-ID') + ') lebih kecil dari pembayaran yang sudah diterima kasir (Rp ' + psTerbayarKasir.toLocaleString('id-ID') + ').\nTerdapat kelebihan bayar konsumen sebesar Rp ' + (psTerbayarKasir - curGT).toLocaleString('id-ID') + '.\nHarap batalkan transaksi kasir terlebih dahulu jika ingin menurunkan tagihan.');
            return false;
        }

        // Safeguard Wajib Tinjaunya Notes Client (Anti-Lupa)
        if (!checkNotesClientChange()) {
            var textarea = document.getElementById('main_description_input');
            if (textarea) {
                textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                textarea.focus();
                textarea.style.boxShadow = '0 0 10px #dc3545';
                setTimeout(function() {
                    textarea.style.boxShadow = 'none';
                }, 2500);
            }
            alert('DISKON / ADJUSTMENT DITAHAN:\n\nBagian "Notes Client (Dicetak di Invoice Penagihan)" belum Anda tinjau / perbarui.\n\nSesuai standar penagihan, pastikan teks catatan invoice sudah sesuai dengan nominal/kondisi baru, ATAU centang konfirmasi bahwa catatan tidak perlu diubah.');
            return false;
        }

        showAmandemenLoader();
        return true;
    }

    // HTML5 Drag and Drop Reordering Logic
    var dragSrcEl = null;

    function handleDragStart(e) {
        if (e.target.tagName !== 'TR') return;
        dragSrcEl = e.target;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', e.target.outerHTML);
        setTimeout(function() {
            dragSrcEl.classList.add('dragging');
        }, 0);
    }

    function handleDragOver(e) {
        if (e.preventDefault) { e.preventDefault(); }
        var targetTr = e.target.closest('tr');
        if (targetTr && targetTr !== dragSrcEl && targetTr.parentNode.tagName === 'TBODY') {
            targetTr.style.borderTop = "2px dashed #0056b3";
        }
        return false;
    }

    function handleDragEnter(e) {
        // ...
    }

    function handleDragLeave(e) {
        var targetTr = e.target.closest('tr');
        if (targetTr && targetTr.parentNode.tagName === 'TBODY') {
            targetTr.style.borderTop = "";
            targetTr.style.borderBottom = "";
        }
    }

    function handleDrop(e) {
        if (e.stopPropagation) { e.stopPropagation(); }
        var targetTr = e.target.closest('tr');
        if (targetTr && dragSrcEl !== targetTr && targetTr.parentNode.tagName === 'TBODY') {
            targetTr.style.borderTop = "";
            var tbody = targetTr.parentNode;
            
            // Reorder the DOM nodes
            var draggedIndex = Array.from(tbody.children).indexOf(dragSrcEl);
            var targetIndex = Array.from(tbody.children).indexOf(targetTr);
            
            if (draggedIndex < targetIndex) {
                tbody.insertBefore(dragSrcEl, targetTr.nextSibling);
            } else {
                tbody.insertBefore(dragSrcEl, targetTr);
            }
            
            updateRowNumbers();
        }
        return false;
    }

    function handleDragEnd(e) {
        if (dragSrcEl) {
            dragSrcEl.classList.remove('dragging');
        }
        var trs = document.querySelectorAll('#tableItems tbody tr');
        trs.forEach(function (tr) {
            tr.style.borderTop = "";
            tr.style.borderBottom = "";
        });
    }

    var tbody = document.querySelector('#tableItems tbody');
    tbody.addEventListener('dragstart', handleDragStart, false);
    tbody.addEventListener('dragenter', handleDragEnter, false);
    tbody.addEventListener('dragover', handleDragOver, false);
    tbody.addEventListener('dragleave', handleDragLeave, false);
    tbody.addEventListener('drop', handleDrop, false);
    tbody.addEventListener('dragend', handleDragEnd, false);
</script>

<script>
    // Inisialisasi DataTables untuk tabel Riwayat Jurnal
    $(document).ready(function() {
        if ($('#tableRiwayatJurnal').length > 0) {
            $('#tableRiwayatJurnal').DataTable({
                "paging": false,
                "searching": false,
                "info": false,
                "order": [[1, "desc"]], // Default urut kolom Waktu (dtime) descending
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
                }
            });
        }
    });

    // --- JS LOGIC UNTUK CUSTOM MANUAL JOURNAL OVERRIDE ---
    function toggleManualJurnal(chk) {
        var box = document.getElementById('box_manual_jurnal_container');
        if (box) {
            if (chk.checked) {
                box.style.display = 'block';
                if (document.querySelectorAll('#tbodyManualJurnal tr').length === 0) {
                    initManualJurnalRows();
                }
            } else {
                box.style.display = 'none';
                document.querySelector('#frmAmandemen button[type="submit"]').disabled = false;
            }
        }
    }

    var manualJurnalIdx = 0;
    var coaMap = <?php echo json_encode(!empty($coa_list) ? $coa_list : array()); ?>;

    function initManualJurnalRows() {
        var tbody = document.getElementById('tbodyManualJurnal');
        if (!tbody) return;
        tbody.innerHTML = '';
        
        var curDPP = parseFloat(document.getElementById('dppVal').innerText.replace(/\./g, '').replace(/,/g, '.')) || 0;
        var curPPN = parseFloat(document.getElementById('ppnVal').innerText.replace(/\./g, '').replace(/,/g, '.')) || 0;
        var curGT = curDPP + curPPN;

        // Default Row 1: Debet Piutang Usaha Proyek (1010070030)
        addManualJurnalRow('1010070030', 'Piutang Usaha Kontijensi / Proyek', curGT, 0);
        // Default Row 2: Kredit Penjualan Project (4010030)
        addManualJurnalRow('4010030', 'Penjualan Project', 0, curDPP);
        // Default Row 3: Kredit PPN Keluaran (2030060)
        addManualJurnalRow('2030060', 'PPN Keluaran (Belum Faktur)', 0, curPPN);
    }

    function addManualJurnalRow(code, name, debet, kredit) {
        code = code || '';
        name = name || '';
        debet = debet || 0;
        kredit = kredit || 0;
        
        var idx = manualJurnalIdx++;
        var tbody = document.getElementById('tbodyManualJurnal');
        if (!tbody) return;
        var tr = document.createElement('tr');
        tr.id = 'jrow_' + idx;
        tr.innerHTML = '<td><input type="text" list="dl_coa_list" name="jurnal_custom[' + idx + '][rekening]" value="' + code + '" style="width:100%; padding:3px; font-family:monospace;" placeholder="Pilih/Ketik COA..." onchange="onCoaSelected(this)" required></td>' +
                       '<td><input type="text" name="jurnal_custom[' + idx + '][rekening_nama]" value="' + name + '" style="width:100%; padding:3px;" placeholder="Nama Rekening Akuntansi"></td>' +
                       '<td><input type="number" step="0.01" name="jurnal_custom[' + idx + '][debet]" class="inp-debet-manual" value="' + debet + '" style="width:100%; text-align:right;" onkeyup="calcManualJurnalBalance()" onchange="calcManualJurnalBalance()"></td>' +
                       '<td><input type="number" step="0.01" name="jurnal_custom[' + idx + '][kredit]" class="inp-kredit-manual" value="' + kredit + '" style="width:100%; text-align:right;" onkeyup="calcManualJurnalBalance()" onchange="calcManualJurnalBalance()"></td>' +
                       '<td style="text-align:center;"><button type="button" class="btn btn-sm btn-danger" onclick="removeManualJurnalRow(this)">X</button></td>';
        tbody.appendChild(tr);
        calcManualJurnalBalance();
    }

    function onCoaSelected(inp) {
        var val = inp.value.trim();
        if (!val) return;
        var tr = inp.closest('tr');
        var nameInp = tr.querySelector('input[name*="[rekening_nama]"]');
        if (nameInp && coaMap && coaMap.length > 0) {
            for (var i = 0; i < coaMap.length; i++) {
                if (coaMap[i].kode == val) {
                    nameInp.value = coaMap[i].nama;
                    break;
                }
            }
        }
    }

    function removeManualJurnalRow(btn) {
        var tr = btn.closest('tr');
        if (tr) tr.parentNode.removeChild(tr);
        calcManualJurnalBalance();
    }

    function calcManualJurnalBalance() {
        var totDebet = 0;
        var totKredit = 0;
        document.querySelectorAll('.inp-debet-manual').forEach(function(inp) {
            totDebet += parseFloat(inp.value) || 0;
        });
        document.querySelectorAll('.inp-kredit-manual').forEach(function(inp) {
            totKredit += parseFloat(inp.value) || 0;
        });

        var lblDebet = document.getElementById('lblTotDebetManual');
        var lblKredit = document.getElementById('lblTotKreditManual');
        if (lblDebet) lblDebet.innerText = 'Rp ' + totDebet.toLocaleString('id-ID');
        if (lblKredit) lblKredit.innerText = 'Rp ' + totKredit.toLocaleString('id-ID');

        var statusLbl = document.getElementById('lblBalanceStatus');
        var diff = Math.abs(totDebet - totKredit);

        if (statusLbl) {
            if (diff <= 1 && (totDebet > 0 || totKredit > 0)) {
                statusLbl.style.color = '#28a745';
                statusLbl.innerHTML = '✓ KESEIMBANGAN JURNAL MANUAL SEIMBANG (DEBET == KREDIT)';
                var btnSubmit = document.querySelector('#frmAmandemen button[type="submit"]');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                statusLbl.style.color = '#dc3545';
                statusLbl.innerHTML = '❌ DUA SISI HARUS SEIMBANG! (Selisih: Rp ' + diff.toLocaleString('id-ID') + ')';
                var chk = document.getElementById('toggle_manual_jurnal');
                if (chk && chk.checked) {
                    var btnSubmit = document.querySelector('#frmAmandemen button[type="submit"]');
                    if (btnSubmit) btnSubmit.disabled = true;
                }
            }
        }
    }

    function checkNameChange(inp) {
        var origName = inp.getAttribute('data-orig-nama') || '';
        var curName = inp.value || '';
        var tr = inp.closest('tr');
        if (!tr) return;
        var infoDiv = tr.querySelector('.name-info');
        if (infoDiv) {
            if (curName.trim() !== origName.trim()) {
                infoDiv.innerHTML = '✏️ Nama dikustomisasi (Original: ' + origName + ')';
            } else {
                infoDiv.innerHTML = '';
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        recalcGrandTotal();
    });

    function showAmandemenLoader() {
        var btn = document.querySelector('#frmAmandemen button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sedang Menyimpan...';
            btn.style.opacity = '0.7';
        }
        var iframe = document.getElementById('result');
        if (iframe) {
            iframe.style.display = 'block';
        }
    }
</script>
</body>
</html>
