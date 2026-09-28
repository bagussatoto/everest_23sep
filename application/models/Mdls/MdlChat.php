<?php
// START OF COMPLETE REPEATED LOGIC

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MdlChat extends CI_Model
{
    protected $tableName = "sys_chat_message";
    protected $auditTableName = "sys_ai_audit_trail";

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table_exists();
    }

    public function ensure_table_exists()
    {
        // 1. Buat tabel sys_chat_message jika belum ada
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `chat_type` varchar(20) NOT NULL DEFAULT 'branch',
            `sender_id` int(11) NOT NULL,
            `sender_name` varchar(100) NOT NULL,
            `sender_cabang_id` int(11) NOT NULL,
            `sender_cabang_nama` varchar(100) NOT NULL,
            `target_id` int(11) NOT NULL DEFAULT '0',
            `target_name` varchar(100) NOT NULL DEFAULT '',
            `cabang_id` int(11) NOT NULL DEFAULT '0',
            `message` text NOT NULL,
            `ref_transaksi` varchar(100) DEFAULT NULL,
            `session_id` varchar(100) DEFAULT NULL,
            `is_read` tinyint(1) DEFAULT 0,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_type_target` (`chat_type`, `target_id`, `id`),
            KEY `idx_sender` (`sender_id`, `id`),
            KEY `idx_session_id` (`session_id`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->db->query($sql);

        // 2. Auto-migration kolom sys_chat_message
        $fields = $this->db->list_fields($this->tableName);
        if (!in_array('chat_type', $fields)) {
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD `chat_type` VARCHAR(20) NOT NULL DEFAULT 'branch' AFTER `id`;");
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD KEY `idx_type_target` (`chat_type`, `target_id`, `id`);");
        }
        if (!in_array('target_id', $fields)) {
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD `target_id` INT(11) NOT NULL DEFAULT '0' AFTER `sender_cabang_nama`;");
        }
        if (!in_array('target_name', $fields)) {
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD `target_name` VARCHAR(100) NOT NULL DEFAULT '' AFTER `target_id`;");
        }
        if (!in_array('session_id', $fields)) {
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD `session_id` VARCHAR(100) DEFAULT NULL AFTER `ref_transaksi`;");
            $this->db->query("ALTER TABLE `" . $this->tableName . "` ADD KEY `idx_session_id` (`session_id`);");
        }

        // 3. Buat tabel audit trail sys_ai_audit_trail untuk mencatat jejak sesi & query AI
        $sqlAudit = "CREATE TABLE IF NOT EXISTS `" . $this->auditTableName . "` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `session_id` varchar(100) NOT NULL,
            `user_id` int(11) NOT NULL,
            `user_name` varchar(100) NOT NULL,
            `user_jenis` varchar(50) NOT NULL DEFAULT 'staff',
            `cabang_id` int(11) NOT NULL DEFAULT '0',
            `cabang_nama` varchar(100) NOT NULL DEFAULT '',
            `provider` varchar(50) NOT NULL DEFAULT 'opencode',
            `model` varchar(100) NOT NULL DEFAULT 'BP-MGK',
            `intent` varchar(60) DEFAULT 'general',
            `user_prompt` text NOT NULL,
            `ref_transaksi` varchar(100) DEFAULT NULL,
            `grounding_data` mediumtext DEFAULT NULL,
            `ai_response` mediumtext NOT NULL,
            `duration_seconds` decimal(6,3) DEFAULT '0.000',
            `status` varchar(20) DEFAULT 'success',
            `error_detail` text DEFAULT NULL,
            `ip_address` varchar(45) DEFAULT NULL,
            `user_agent` text DEFAULT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_session` (`session_id`),
            KEY `idx_user` (`user_id`, `created_at`),
            KEY `idx_cabang` (`cabang_id`, `created_at`),
            KEY `idx_intent` (`intent`),
            KEY `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->db->query($sqlAudit);
    }

    public function get_messages($chat_type, $target_id, $my_id, $last_id = 0, $limit = 60)
    {
        $target_id = (int)$target_id;
        $my_id = (int)$my_id;
        $last_id = (int)$last_id;
        $limit = (int)$limit;

        if ($chat_type === 'direct') {
            // Obrolan privat antar user: pesan dari A ke B atau dari B ke A
            if ($last_id > 0) {
                $sql = "SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, message, ref_transaksi, is_read, created_at 
                        FROM `" . $this->tableName . "` 
                        WHERE chat_type = 'direct' 
                          AND ((sender_id = ? AND target_id = ?) OR (sender_id = ? AND target_id = ?))
                          AND id > ? 
                        ORDER BY id ASC";
                $query = $this->db->query($sql, array($my_id, $target_id, $target_id, $my_id, $last_id));
                return $query->result_array();
            } else {
                $sql = "SELECT * FROM (
                            SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, message, ref_transaksi, is_read, created_at 
                            FROM `" . $this->tableName . "` 
                            WHERE chat_type = 'direct' 
                              AND ((sender_id = ? AND target_id = ?) OR (sender_id = ? AND target_id = ?))
                            ORDER BY id DESC 
                            LIMIT ?
                        ) as sub ORDER BY id ASC";
                $query = $this->db->query($sql, array($my_id, $target_id, $target_id, $my_id, $limit));
                return $query->result_array();
            }
        } elseif ($chat_type === 'ai') {
            // Obrolan dengan asisten AI (sender_id 0 = AI, target_id 0 = AI)
            if ($last_id > 0) {
                $sql = "SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, message, ref_transaksi, is_read, created_at 
                        FROM `" . $this->tableName . "` 
                        WHERE chat_type = 'ai' 
                          AND ((sender_id = ? AND target_id = 0) OR (sender_id = 0 AND target_id = ?))
                          AND id > ? 
                        ORDER BY id ASC";
                $query = $this->db->query($sql, array($my_id, $my_id, $last_id));
                return $query->result_array();
            } else {
                $sql = "SELECT * FROM (
                            SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, message, ref_transaksi, is_read, created_at 
                            FROM `" . $this->tableName . "` 
                            WHERE chat_type = 'ai' 
                              AND ((sender_id = ? AND target_id = 0) OR (sender_id = 0 AND target_id = ?))
                            ORDER BY id DESC 
                            LIMIT ?
                        ) as sub ORDER BY id ASC";
                $query = $this->db->query($sql, array($my_id, $my_id, $limit));
                return $query->result_array();
            }
        } else {
            // Default: branch (antar cabang atau cabang ke pusat)
            if ($last_id > 0) {
                $sql = "SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, cabang_id, message, ref_transaksi, is_read, created_at 
                        FROM `" . $this->tableName . "` 
                        WHERE (chat_type = 'branch' OR chat_type IS NULL)
                          AND (target_id = ? OR cabang_id = ?)
                          AND id > ? 
                        ORDER BY id ASC";
                $query = $this->db->query($sql, array($target_id, $target_id, $last_id));
                return $query->result_array();
            } else {
                $sql = "SELECT * FROM (
                            SELECT id, chat_type, sender_id, sender_name, sender_cabang_id, sender_cabang_nama, target_id, target_name, cabang_id, message, ref_transaksi, is_read, created_at 
                            FROM `" . $this->tableName . "` 
                            WHERE (chat_type = 'branch' OR chat_type IS NULL)
                              AND (target_id = ? OR cabang_id = ?)
                            ORDER BY id DESC 
                            LIMIT ?
                        ) as sub ORDER BY id ASC";
                $query = $this->db->query($sql, array($target_id, $target_id, $limit));
                return $query->result_array();
            }
        }
    }

    public function send_message($chat_type, $sender_id, $sender_name, $sender_cabang_id, $sender_cabang_nama, $target_id, $target_name, $message, $ref_transaksi = null, $session_id = null)
    {
        $data = array(
            'chat_type' => $chat_type,
            'sender_id' => (int)$sender_id,
            'sender_name' => $sender_name,
            'sender_cabang_id' => (int)$sender_cabang_id,
            'sender_cabang_nama' => $sender_cabang_nama,
            'target_id' => (int)$target_id,
            'target_name' => $target_name,
            'cabang_id' => ($chat_type === 'branch') ? (int)$target_id : 0,
            'message' => $message,
            'ref_transaksi' => !empty($ref_transaksi) ? $ref_transaksi : null,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($session_id)) {
            $data['session_id'] = $session_id;
        }

        $this->db->insert($this->tableName, $data);
        $insert_id = $this->db->insert_id();

        $data['id'] = $insert_id;
        return $data;
    }

    public function mark_as_read($chat_type, $target_id, $my_id)
    {
        $target_id = (int)$target_id;
        $my_id = (int)$my_id;

        if ($chat_type === 'direct') {
            // Tandai pesan dari lawan bicara ke saya sebagai read
            $sql = "UPDATE `" . $this->tableName . "` 
                    SET is_read = 1 
                    WHERE chat_type = 'direct' AND sender_id = ? AND target_id = ? AND is_read = 0";
            $this->db->query($sql, array($target_id, $my_id));
        } elseif ($chat_type === 'ai') {
            // Tandai pesan AI ke saya sebagai read
            $sql = "UPDATE `" . $this->tableName . "` 
                    SET is_read = 1 
                    WHERE chat_type = 'ai' AND sender_id = 0 AND target_id = ? AND is_read = 0";
            $this->db->query($sql, array($my_id));
        } else {
            // Branch: Tandai pesan orang lain di channel cabang ini sebagai read
            $sql = "UPDATE `" . $this->tableName . "` 
                    SET is_read = 1 
                    WHERE (chat_type = 'branch' OR chat_type IS NULL) 
                      AND (target_id = ? OR cabang_id = ?) 
                      AND sender_id != ? 
                      AND is_read = 0";
            $this->db->query($sql, array($target_id, $target_id, $my_id));
        }
    }

    public function get_all_unread_counts($my_id, $my_cabang_id)
    {
        $my_id = (int)$my_id;
        $my_cabang_id = (int)$my_cabang_id;

        // 1. Hitung unread Direct Messages (pesan dari user lain untuk saya)
        $sqlDirect = "SELECT sender_id, COUNT(*) as total 
                      FROM `" . $this->tableName . "` 
                      WHERE chat_type = 'direct' AND target_id = ? AND is_read = 0 
                      GROUP BY sender_id";
        $qDirect = $this->db->query($sqlDirect, array($my_id));
        $rDirect = $qDirect->result_array();

        $directBySender = array();
        $totalDirect = 0;
        if (is_array($rDirect)) {
            foreach ($rDirect as $row) {
                $cnt = (int)$row['total'];
                $directBySender[$row['sender_id']] = $cnt;
                $totalDirect += $cnt;
            }
        }

        // 2. Hitung unread Branch Messages
        $branchByTarget = array();
        $totalBranch = 0;
        if ($my_cabang_id == -1) {
            // Kantor Pusat melihat unread pesan dari semua cabang
            $sqlBranch = "SELECT IF(target_id > 0, target_id, cabang_id) as ch_id, COUNT(*) as total 
                          FROM `" . $this->tableName . "` 
                          WHERE (chat_type = 'branch' OR chat_type IS NULL) 
                            AND sender_cabang_id != -1 
                            AND is_read = 0 
                          GROUP BY ch_id";
            $qBranch = $this->db->query($sqlBranch);
            $rBranch = $qBranch->result_array();
            if (is_array($rBranch)) {
                foreach ($rBranch as $row) {
                    $cnt = (int)$row['total'];
                    $branchByTarget[$row['ch_id']] = $cnt;
                    $totalBranch += $cnt;
                }
            }
        } else {
            // Cabang hanya melihat pesan dari Pusat untuk cabangnya
            $sqlBranch = "SELECT COUNT(*) as total 
                          FROM `" . $this->tableName . "` 
                          WHERE (chat_type = 'branch' OR chat_type IS NULL) 
                            AND (target_id = ? OR cabang_id = ?) 
                            AND sender_cabang_id = -1 
                            AND is_read = 0";
            $qBranch = $this->db->query($sqlBranch, array($my_cabang_id, $my_cabang_id));
            $rBranch = $qBranch->row_array();
            $cnt = isset($rBranch['total']) ? (int)$rBranch['total'] : 0;
            $branchByTarget[$my_cabang_id] = $cnt;
            $totalBranch = $cnt;
        }

        // 3. Hitung unread AI Messages
        $sqlAi = "SELECT COUNT(*) as total 
                  FROM `" . $this->tableName . "` 
                  WHERE chat_type = 'ai' AND sender_id = 0 AND target_id = ? AND is_read = 0";
        $qAi = $this->db->query($sqlAi, array($my_id));
        $rAi = $qAi->row_array();
        $totalAi = isset($rAi['total']) ? (int)$rAi['total'] : 0;

        return array(
            'total_unread' => ($totalDirect + $totalBranch + $totalAi),
            'branch_total' => $totalBranch,
            'branch_breakdown' => $branchByTarget,
            'direct_total' => $totalDirect,
            'direct_breakdown' => $directBySender,
            'ai_total' => $totalAi
        );
    }

    public function get_active_users($my_id)
    {
        $my_id = (int)$my_id;
        $sql = "SELECT e.id, e.nama, e.nama_login, e.cabang_id, e.last_dtime_active, e.status_login,
                       IF(e.status_login = '1' AND e.last_dtime_active >= DATE_SUB(NOW(), INTERVAL 15 MINUTE), 1, 0) as is_online,
                       CASE 
                           WHEN e.cabang_id = -1 THEN 'Kantor Pusat' 
                           ELSE COALESCE(c.nama, 'Cabang Lain') 
                       END as cabang_nama 
                FROM per_employee e 
                LEFT JOIN per_cabang c ON e.cabang_id = c.id 
                WHERE e.status = '1' AND e.trash = '0' AND e.ghost = '0' AND e.id != ? 
                ORDER BY is_online DESC, e.nama ASC";
        $query = $this->db->query($sql, array($my_id));
        return $query->result_array();
    }

    public function get_active_cabang_list()
    {
        $sql = "SELECT id, kode_cabang, nama 
                FROM per_cabang 
                WHERE status = '1' AND trash = '0' AND jenis = 'cabang' 
                ORDER BY nama ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_latest_snippets($my_id)
    {
        $my_id = (int)$my_id;

        // Ambil pesan terakhir per lawan bicara (direct)
        $sqlDirect = "SELECT m.id, m.sender_id, m.target_id, m.message, m.created_at 
                      FROM `" . $this->tableName . "` m
                      INNER JOIN (
                          SELECT MAX(id) as max_id 
                          FROM `" . $this->tableName . "` 
                          WHERE chat_type = 'direct' AND (sender_id = ? OR target_id = ?)
                          GROUP BY IF(sender_id = ?, target_id, sender_id)
                      ) sub ON m.id = sub.max_id";
        $qDirect = $this->db->query($sqlDirect, array($my_id, $my_id, $my_id));
        $directSnippets = array();
        if ($qDirect) {
            $rows = $qDirect->result_array();
            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $peerId = ((int)$row['sender_id'] === $my_id) ? (int)$row['target_id'] : (int)$row['sender_id'];
                    $rawText = strip_tags($row['message']);
                    if (mb_strlen($rawText) > 42) {
                        $rawText = mb_substr($rawText, 0, 40) . '...';
                    }
                    $directSnippets[$peerId] = array(
                        'text' => $rawText,
                        'time' => substr($row['created_at'], 11, 5),
                        'is_urgent' => (strpos($row['message'], '[🚨 URGENT]') !== false || strpos($row['message'], '[URGENT]') !== false) ? 1 : 0
                    );
                }
            }
        }

        // Ambil pesan terakhir per cabang (branch)
        $sqlBranch = "SELECT m.id, IF(m.target_id > 0, m.target_id, m.cabang_id) as ch_id, m.message, m.created_at 
                      FROM `" . $this->tableName . "` m
                      INNER JOIN (
                          SELECT MAX(id) as max_id 
                          FROM `" . $this->tableName . "` 
                          WHERE (chat_type = 'branch' OR chat_type IS NULL)
                          GROUP BY IF(target_id > 0, target_id, cabang_id)
                      ) sub ON m.id = sub.max_id";
        $qBranch = $this->db->query($sqlBranch);
        $branchSnippets = array();
        if ($qBranch) {
            $rowsB = $qBranch->result_array();
            if (is_array($rowsB)) {
                foreach ($rowsB as $row) {
                    $chId = (int)$row['ch_id'];
                    $rawText = strip_tags($row['message']);
                    if (mb_strlen($rawText) > 42) {
                        $rawText = mb_substr($rawText, 0, 40) . '...';
                    }
                    $branchSnippets[$chId] = array(
                        'text' => $rawText,
                        'time' => substr($row['created_at'], 11, 5),
                        'is_urgent' => (strpos($row['message'], '[🚨 URGENT]') !== false || strpos($row['message'], '[URGENT]') !== false) ? 1 : 0
                    );
                }
            }
        }

        return array(
            'direct' => $directSnippets,
            'branch' => $branchSnippets
        );
    }

    /**
     * Mencatat jejak audit interaksi AI ke tabel sys_ai_audit_trail
     *
     * @param array $auditData
     * @return int|bool ID baris log audit yang tersimpan
     */
    public function record_ai_audit($auditData)
    {
        if (!is_array($auditData)) {
            return false;
        }

        $insertData = array(
            'session_id'       => isset($auditData['session_id']) ? $auditData['session_id'] : '',
            'user_id'          => isset($auditData['user_id']) ? (int)$auditData['user_id'] : 0,
            'user_name'        => isset($auditData['user_name']) ? $auditData['user_name'] : '',
            'user_jenis'       => isset($auditData['user_jenis']) ? $auditData['user_jenis'] : 'staff',
            'cabang_id'        => isset($auditData['cabang_id']) ? (int)$auditData['cabang_id'] : 0,
            'cabang_nama'      => isset($auditData['cabang_nama']) ? $auditData['cabang_nama'] : '',
            'provider'         => isset($auditData['provider']) ? $auditData['provider'] : 'opencode',
            'model'            => isset($auditData['model']) ? $auditData['model'] : 'BP-MGK',
            'intent'           => isset($auditData['intent']) ? $auditData['intent'] : 'general',
            'user_prompt'      => isset($auditData['user_prompt']) ? $auditData['user_prompt'] : '',
            'ref_transaksi'    => isset($auditData['ref_transaksi']) ? $auditData['ref_transaksi'] : null,
            'grounding_data'   => isset($auditData['grounding_data']) ? $auditData['grounding_data'] : null,
            'ai_response'      => isset($auditData['ai_response']) ? $auditData['ai_response'] : '',
            'duration_seconds' => isset($auditData['duration_seconds']) ? (float)$auditData['duration_seconds'] : 0.000,
            'status'           => isset($auditData['status']) ? $auditData['status'] : 'success',
            'error_detail'     => isset($auditData['error_detail']) ? $auditData['error_detail'] : null,
            'ip_address'       => isset($auditData['ip_address']) ? $auditData['ip_address'] : null,
            'user_agent'       => isset($auditData['user_agent']) ? $auditData['user_agent'] : null,
            'created_at'       => isset($auditData['created_at']) ? $auditData['created_at'] : date('Y-m-d H:i:s')
        );

        $this->db->insert($this->auditTableName, $insertData);
        return $this->db->insert_id();
    }

    /**
     * Mengambil riwayat percakapan AI terakhir (Multi-Turn History)
     * Mengambil N pesan dalam urutan kronologis (ASC) untuk dijadikan konteks memori LLM.
     *
     * @param int $my_id
     * @param string|null $session_id
     * @param int $limit
     * @return array
     */
    public function get_ai_recent_history($my_id, $session_id = null, $limit = 8)
    {
        $my_id = (int)$my_id;
        $limit = (int)$limit;
        if ($limit <= 0) {
            $limit = 8;
        }

        if (!empty($session_id)) {
            $sql = "SELECT * FROM (
                        SELECT id, sender_id, sender_name, message, ref_transaksi, created_at 
                        FROM `" . $this->tableName . "` 
                        WHERE chat_type = 'ai' 
                          AND session_id = ? 
                          AND ((sender_id = ? AND target_id = 0) OR (sender_id = 0 AND target_id = ?))
                        ORDER BY id DESC 
                        LIMIT ?
                    ) as sub ORDER BY id ASC";
            $query = $this->db->query($sql, array($session_id, $my_id, $my_id, $limit));
            $res = $query->result_array();
            if (!empty($res)) {
                return $res;
            }
        }

        // Fallback jika session_id baru atau pesan sebelumnya belum ber-session_id
        $sqlFallback = "SELECT * FROM (
                            SELECT id, sender_id, sender_name, message, ref_transaksi, created_at 
                            FROM `" . $this->tableName . "` 
                            WHERE chat_type = 'ai' 
                              AND ((sender_id = ? AND target_id = 0) OR (sender_id = 0 AND target_id = ?))
                            ORDER BY id DESC 
                            LIMIT ?
                        ) as sub ORDER BY id ASC";
        $queryFallback = $this->db->query($sqlFallback, array($my_id, $my_id, $limit));
        return $queryFallback->result_array();
    }

    /**
     * Mengambil daftar log audit percakapan AI dengan filter
     *
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_ai_audit_trail($filters = array(), $limit = 50, $offset = 0)
    {
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit <= 0) {
            $limit = 50;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $where = array();
        $binds = array();

        if (!empty($filters['session_id'])) {
            $where[] = "session_id = ?";
            $binds[] = $filters['session_id'];
        }
        if (!empty($filters['user_id'])) {
            $where[] = "user_id = ?";
            $binds[] = (int)$filters['user_id'];
        }
        if (!empty($filters['cabang_id'])) {
            $where[] = "cabang_id = ?";
            $binds[] = (int)$filters['cabang_id'];
        }
        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $binds[] = $filters['status'];
        }
        if (!empty($filters['intent'])) {
            $where[] = "intent = ?";
            $binds[] = $filters['intent'];
        }
        if (!empty($filters['date_from'])) {
            $where[] = "created_at >= ?";
            $binds[] = $filters['date_from'] . ' 00:00:00';
        }
        if (!empty($filters['date_to'])) {
            $where[] = "created_at <= ?";
            $binds[] = $filters['date_to'] . ' 23:59:59';
        }

        $whereSql = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

        // Total count
        $countSql = "SELECT COUNT(*) as total FROM `" . $this->auditTableName . "`" . $whereSql;
        $qCount = $this->db->query($countSql, $binds);
        $rowCount = $qCount->row_array();
        $total = isset($rowCount['total']) ? (int)$rowCount['total'] : 0;

        // Rows
        $dataSql = "SELECT id, session_id, user_id, user_name, user_jenis, cabang_id, cabang_nama, provider, model, intent, user_prompt, ref_transaksi, duration_seconds, status, error_detail, ip_address, created_at 
                    FROM `" . $this->auditTableName . "`" . $whereSql . " 
                    ORDER BY id DESC 
                    LIMIT ? OFFSET ?";
        $bindsWithLimit = array_merge($binds, array($limit, $offset));
        $qData = $this->db->query($dataSql, $bindsWithLimit);
        $rows = $qData->result_array();

        return array(
            'total' => $total,
            'rows'  => is_array($rows) ? $rows : array()
        );
    }
}

// END OF COMPLETE REPEATED LOGIC
