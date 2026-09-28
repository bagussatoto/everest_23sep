<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Laporan Audit'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">
    <style>
        .status-gagal { background-color: #f2dede; color: #a94442; font-weight: bold; }
        .status-potensi { background-color: #fcf8e3; color: #8a6d3b; font-weight: bold; }
        .status-aman { background-color: #dff0d8; color: #3c763d; font-weight: bold; }
        .table-audit th { background-color: #337ab7; color: white; text-align: center; vertical-align: middle; }
    </style>
</head>
<body style="padding: 20px; background-color: #f5f5f5;">

<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-dashboard"></i> Laporan Audit Kesesuaian Saldo DP Project vs Buku Besar GL 2010050</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="tableAudit" class="table table-bordered table-striped table-hover table-audit">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cabang</th>
                            <th>Nama Konsumen (Customer)</th>
                            <th>PID</th>
                            <th>Nomer Nota</th>
                            <th>ID Project</th>
                            <th>Nama Project</th>
                            <th>Sisa Tagihan Nota</th>
                            <th>Saldo DP Project (UI)</th>
                            <th>Saldo Riil GL 2010050</th>
                            <th>Status Audit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($audit_data) && is_array($audit_data) && sizeof($audit_data) > 0) {
                            $no = 1;
                            foreach ($audit_data as $row) {
                                $class_status = 'status-aman';
                                $label_status = '<span class="label label-success">AMAN</span>';

                                if ($row['status_audit'] == 'GAGAL_DEFISIT' || $row['status_audit'] == 'GAGAL_SALDO_NOL') {
                                    $class_status = 'status-gagal';
                                    $label_status = '<span class="label label-danger">PASTI GAGAL</span>';
                                } elseif ($row['saldo_riil_gl_2010050'] == 0 && $row['dpp_uang_muka_dibutuhkan'] > 0) {
                                    $class_status = 'status-potensi';
                                    $label_status = '<span class="label label-warning">BERPOTENSI GAGAL</span>';
                                }
                        ?>
                            <tr class="<?php echo $class_status; ?>">
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['cabang_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['customer_nama']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['customer_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nomer_nota']); ?></td>
                                <td><?php echo htmlspecialchars($row['project_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['project_nama']); ?></td>
                                <td class="text-right">Rp <?php echo number_format($row['sisa_tagihan_nota'], 2, ',', '.'); ?></td>
                                <td class="text-right">Rp <?php echo number_format($row['total_uang_muka_ui'], 2, ',', '.'); ?></td>
                                <td class="text-right">Rp <?php echo number_format($row['saldo_riil_gl_2010050'], 2, ',', '.'); ?></td>
                                <td class="text-center"><?php echo $label_status; ?></td>
                            </tr>
                        <?php 
                            }
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableAudit').DataTable({
            "pageLength": 50,
            "ordering": true,
            "language": {
                "search": "Cari Project / Customer:",
                "lengthMenu": "Tampilkan _MENU_ baris",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ project"
            }
        });
    });
</script>
</body>
</html>