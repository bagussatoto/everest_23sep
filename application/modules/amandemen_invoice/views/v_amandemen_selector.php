<!-- [AGENT_LOG]
ROLE      : Software Engineer Agent
PURPOSE   : View popup untuk memilih item SPK (Jembatan Khusus)
COMPLIANCE: Frontend Safety
LOG_EXPIRE: 2026-11-07
[/AGENT_LOG] -->
<!DOCTYPE html>
<html>
<head>
    <title>Pilih SPK</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; color: white; background-color: #28a745; text-decoration: none; border-radius: 3px; }
        .btn:hover { background-color: #218838; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Daftar SPK Lulus QC (Project #<?php echo $project_id; ?>)</h2>
    <p>Klik tombol Pilih untuk memasukkan <b>semua</b> produk/jasa dari SPK tersebut ke dalam invoice.</p>

    <table class="table">
        <thead>
            <tr>
                <th>No SPK</th>
                <th class="text-right">Total Macam Item</th>
                <th class="text-right">Total Qty (RAB)</th>
                <th class="text-right">Total Nilai (Rp)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($spk_list)): ?>
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada SPK yang Lulus QC untuk project ini.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($spk_list as $spk): ?>
                <?php 
                    $isInvoiced = false;
                    $invNumber = '';
                    if (isset($spk['invoiced']) && $spk['invoiced'] !== '0' && !empty($spk['invoiced'])) {
                        $isInvoiced = true;
                        $parts = explode(',', $spk['invoiced']);
                        $invNumber = end($parts);
                    }
                ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($spk['no_spk']); ?>
                        <?php if ($isInvoiced): ?>
                            <br><span style="background-color:#17a2b8; color:white; padding:3px 6px; font-size:11px; border-radius:3px; display:inline-block; margin-top:4px;" title="Telah ditagih pada dokumen: <?php echo htmlspecialchars($invNumber); ?>">Sudah Ditagih (<?php echo htmlspecialchars($invNumber); ?>)</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?php echo number_format($spk['total_items'], 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo number_format($spk['total_qty'], 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo number_format($spk['total_nilai'], 0, ',', '.'); ?></td>
                    <td style="text-align:center;">
                        <button id="btn_<?php echo md5($spk['no_spk']); ?>" class="btn" onclick="pilihSPK('<?php echo addslashes($spk['no_spk']); ?>', this)">Pilih Semua Item</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
    function pilihSPK(no_spk, btn) {
        if (window.opener && !window.opener.closed) {
            window.opener.loadSpkItems(no_spk, <?php echo $project_id; ?>, <?php echo $current_invoice_id; ?>);
            
            // Ubah tampilan tombol agar user tahu sudah ditambahkan
            btn.innerHTML = "✓ Ditambahkan";
            btn.style.backgroundColor = "#6c757d";
            btn.disabled = true;
            
        } else {
            alert("Jendela utama tidak ditemukan! Gagal memasukkan produk.");
        }
    }
    </script>
</body>
</html>
