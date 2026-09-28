<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model ComOpnameAuditTrail
 * Digunakan untuk mencatat, menyimpan, dan menampilkan riwayat perubahan (audit trail)
 * pada modul Stock Opname mulai dari upload Excel, revisi di sesi Manager 1, hingga otorisasi Manager 2.
 */
class ComOpnameAuditTrail extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Merekam satu aksi perubahan ke dalam session transaksi aktif.
     *
     * @param string $cCode Kode sesi transaksi (contoh: _TR_1119)
     * @param string $actionType Jenis aksi (EDIT_QTY, CHECK_SERIAL, UNCHECK_SERIAL, EDIT_NOTE, CHECKLIST_OPNAME, EXCEL_UPLOAD)
     * @param array $data Data detail perubahan
     * @return array Data event yang baru dicatat
     */
    public function recordSessionEvent($cCode, $actionType, $data = array())
    {
        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array();
        }
        if (!isset($_SESSION[$cCode]['audit_trails']) || !is_array($_SESSION[$cCode]['audit_trails'])) {
            $_SESSION[$cCode]['audit_trails'] = array();
        }

        $actorId = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : (function_exists('my_id') ? my_id() : 0);
        $actorNama = isset($_SESSION['login']['nama']) ? $_SESSION['login']['nama'] : (function_exists('my_nama') ? my_nama() : 'Petugas');

        $event = array(
            'id' => count($_SESSION[$cCode]['audit_trails']) + 1,
            'timestamp' => date('Y-m-d H:i:s'),
            'actor_id' => $actorId,
            'actor_nama' => $actorNama,
            'action_type' => $actionType,
            'item_id' => isset($data['item_id']) ? $data['item_id'] : '',
            'item_kode' => isset($data['item_kode']) ? $data['item_kode'] : '',
            'item_nama' => isset($data['item_nama']) ? $data['item_nama'] : '',
            'serial_number' => isset($data['serial_number']) ? $data['serial_number'] : '',
            'field' => isset($data['field']) ? $data['field'] : '',
            'old_val' => isset($data['old_val']) ? $data['old_val'] : '',
            'new_val' => isset($data['new_val']) ? $data['new_val'] : '',
            'diff' => isset($data['diff']) ? $data['diff'] : '',
            'note' => isset($data['note']) ? $data['note'] : '',
        );

        $_SESSION[$cCode]['audit_trails'][] = $event;
        return $event;
    }

    /**
     * Menyimpan seluruh log audit dari sesi ke tabel so_audit_logs secara permanen saat commit step.
     *
     * @param int $transaksiID ID Transaksi yang baru di-commit / di-approve
     * @param string $cCode Kode sesi transaksi
     * @param int $headerStagingId ID Header staging jika ada
     * @return bool
     */
    public function saveAuditLogsToDb($transaksiID, $cCode, $headerStagingId = 0)
    {
        $auditTrails = isset($_SESSION[$cCode]['audit_trails']) ? $_SESSION[$cCode]['audit_trails'] : array();
        if (empty($auditTrails) || !is_array($auditTrails)) {
            return false;
        }

        $actorId = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : (function_exists('my_id') ? my_id() : 0);

        $insertData = array(
            'header_staging_id' => $headerStagingId > 0 ? $headerStagingId : 0,
            'transaksi_id' => $transaksiID,
            'event_type' => 'SESSION_REVISION',
            'actor_type' => 'USER',
            'actor_id' => $actorId,
            'details' => json_encode($auditTrails),
            'created_at' => date('Y-m-d H:i:s'),
        );

        return $this->db->insert('so_audit_logs', $insertData);
    }

    /**
     * Mengambil daftar log audit baik dari session aktif maupun dari database so_audit_logs.
     *
     * @param int $transaksiID ID Transaksi
     * @param string $cCode Kode sesi transaksi (opsional)
     * @return array
     */
    public function getAuditLogs($transaksiID = 0, $cCode = '')
    {
        // 1. Prioritaskan data di session jika ada
        if (!empty($cCode) && isset($_SESSION[$cCode]['audit_trails']) && is_array($_SESSION[$cCode]['audit_trails']) && count($_SESSION[$cCode]['audit_trails']) > 0) {
            return $_SESSION[$cCode]['audit_trails'];
        }

        // 2. Ambil dari database jika transaksi_id valid
        if ($transaksiID > 0) {
            $query = $this->db->where('transaksi_id', $transaksiID)
                ->where('event_type', 'SESSION_REVISION')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get('so_audit_logs');

            if ($query && $query->num_rows() > 0) {
                $row = $query->row();
                if (!empty($row->details)) {
                    $decoded = json_decode($row->details, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                }
            }
        }

        return array();
    }

    /**
     * Merender komponen HTML Collapsible Box untuk Audit Trail Opname.
     *
     * @param array $auditLogs Daftar entri riwayat audit
     * @param int $transaksiID ID Transaksi untuk referensi ID DOM
     * @param bool $defaultExpanded Default apakah box terbuka (true) atau terlipat (false)
     * @return string HTML
     */
    public function renderAuditTrailBox($auditLogs = array(), $transaksiID = 0, $defaultExpanded = true)
    {
        if (empty($auditLogs) || !is_array($auditLogs)) {
            return '';
        }

        $boxId = 'boxAuditTrailOpname_' . ($transaksiID > 0 ? $transaksiID : 'session');
        $collapseClass = $defaultExpanded ? 'in' : '';
        $ariaExpanded = $defaultExpanded ? 'true' : 'false';
        $totalLogs = count($auditLogs);

        $html = '';
        $html .= '<div class="panel panel-info" style="margin-top: 15px; border: 1px solid #bce8f1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
        $html .= '  <div class="panel-heading" style="background-color: #d9edf7; color: #31708f; padding: 10px 15px; border-bottom: 1px solid #bce8f1;">';
        $html .= '    <h4 class="panel-title" style="margin: 0; font-size: 14px; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">';
        $html .= '      <span><i class="glyphicon glyphicon-list-alt" style="margin-right: 6px;"></i> Audit Trail & Riwayat Penyesuaian Opname <span class="badge" style="background-color: #31708f; color: #fff; margin-left: 5px;">' . $totalLogs . ' perubahan</span></span>';
        $html .= '      <a data-toggle="collapse" href="#' . $boxId . '" aria-expanded="' . $ariaExpanded . '" class="btn btn-xs btn-default pull-right" style="font-weight: normal; color: #31708f; border-color: #bce8f1;">';
        $html .= '        <i class="glyphicon glyphicon-resize-full"></i> Toggle Riwayat';
        $html .= '      </a>';
        $html .= '    </h4>';
        $html .= '  </div>';

        $html .= '  <div id="' . $boxId . '" class="panel-collapse collapse ' . $collapseClass . '">';
        $html .= '    <div class="panel-body" style="padding: 0;">';
        $html .= '      <div class="table-responsive" style="margin: 0;">';
        $html .= '        <table class="table table-bordered table-striped table-hover table-condensed" style="margin-bottom: 0; font-size: 12px;">';
        $html .= '          <thead>';
        $html .= '            <tr style="background-color: #f5f5f5; color: #555;">';
        $html .= '              <th style="width: 40px; text-align: center;">No.</th>';
        $html .= '              <th style="width: 140px;">Waktu</th>';
        $html .= '              <th style="width: 140px;">Pelaksana</th>';
        $html .= '              <th>Barang / No. Serial</th>';
        $html .= '              <th style="width: 130px; text-align: center;">Aksi</th>';
        $html .= '              <th style="width: 110px; text-align: right;">Sebelum</th>';
        $html .= '              <th style="width: 110px; text-align: right;">Sesudah</th>';
        $html .= '              <th>Keterangan / Selisih</th>';
        $html .= '            </tr>';
        $html .= '          </thead>';
        $html .= '          <tbody>';

        $no = 0;
        foreach ($auditLogs as $log) {
            $no++;
            $timeStr = isset($log['timestamp']) ? date('d/m/Y H:i:s', strtotime($log['timestamp'])) : '-';
            $actorStr = isset($log['actor_nama']) ? htmlspecialchars($log['actor_nama']) : 'User';
            $actionType = isset($log['action_type']) ? $log['action_type'] : '';

            $badgeClass = 'label-default';
            $actionLabel = $actionType;
            if ($actionType === 'EDIT_QTY') {
                $badgeClass = 'label-warning';
                $actionLabel = 'Ubah Qty Fisik';
            } elseif ($actionType === 'CHECK_SERIAL') {
                $badgeClass = 'label-success';
                $actionLabel = 'Checklist Serial';
            } elseif ($actionType === 'UNCHECK_SERIAL') {
                $badgeClass = 'label-danger';
                $actionLabel = 'Uncheck Serial';
            } elseif ($actionType === 'EDIT_NOTE') {
                $badgeClass = 'label-info';
                $actionLabel = 'Ubah Catatan';
            } elseif ($actionType === 'CHECKLIST_OPNAME') {
                $badgeClass = 'label-primary';
                $actionLabel = 'Checklist Fisik';
            } elseif ($actionType === 'EXCEL_UPLOAD') {
                $badgeClass = 'label-info';
                $actionLabel = 'Upload Excel';
            }

            $itemStr = '';
            if (!empty($log['item_nama'])) {
                $itemStr .= '<strong>' . htmlspecialchars($log['item_nama']) . '</strong>';
            }
            if (!empty($log['item_kode'])) {
                $itemStr .= ' <span class="text-muted">(' . htmlspecialchars($log['item_kode']) . ')</span>';
            }
            if (!empty($log['serial_number'])) {
                $itemStr .= ($itemStr !== '' ? '<br>' : '') . '<span class="text-primary"><i class="glyphicon glyphicon-barcode"></i> SN: ' . htmlspecialchars($log['serial_number']) . '</span>';
            }
            if (empty($itemStr)) {
                $itemStr = '-';
            }

            $oldValStr = isset($log['old_val']) && $log['old_val'] !== '' ? htmlspecialchars($log['old_val']) : '-';
            $newValStr = isset($log['new_val']) && $log['new_val'] !== '' ? htmlspecialchars($log['new_val']) : '-';

            $noteStr = isset($log['note']) ? htmlspecialchars($log['note']) : '';
            if (!empty($log['diff'])) {
                $diffVal = (float)$log['diff'];
                $diffBadge = $diffVal > 0 ? '<span class="label label-success">+' . $diffVal . '</span>' : '<span class="label label-danger">' . $diffVal . '</span>';
                $noteStr = $diffBadge . ' ' . $noteStr;
            }

            $html .= '            <tr>';
            $html .= '              <td align="center">' . $no . '.</td>';
            $html .= '              <td><small>' . $timeStr . '</small></td>';
            $html .= '              <td><strong>' . $actorStr . '</strong></td>';
            $html .= '              <td>' . $itemStr . '</td>';
            $html .= '              <td align="center"><span class="label ' . $badgeClass . '">' . $actionLabel . '</span></td>';
            $html .= '              <td align="right">' . $oldValStr . '</td>';
            $html .= '              <td align="right"><strong>' . $newValStr . '</strong></td>';
            $html .= '              <td>' . $noteStr . '</td>';
            $html .= '            </tr>';
        }

        $html .= '          </tbody>';
        $html .= '        </table>';
        $html .= '      </div>';
        $html .= '    </div>';
        $html .= '  </div>';
        $html .= '</div>';

        return $html;
    }
}