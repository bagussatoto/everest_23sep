<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
class CronTray extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Lepas session lock seketika agar request non-blocking dan tidak saling tunggu di browser
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Pengaturan execution timeout untuk proses background / browser
        @set_time_limit(0);
        @ignore_user_abort(true);

        // Pengaman akses: izinkan dari CLI atau URL dengan parameter secure_key
        if (!is_cli() && $this->input->get('secure_key') !== 'cron_tray_cache_123') {
            // Uncomment baris berikut jika ingin proteksi penuh via key
            // show_error('Access Denied');
        }
    }

    public function generate_cache($clientParam = null)
    {
        // ---------------------------------------------------------
        // Identifikasi Client / Project & Format Header Log
        // ---------------------------------------------------------
        $clientName = 'MAIN_CLIENT';
        $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '/var/www';
        $basePath = rtrim(str_replace('\\', '/', BASEPATH), '/');
        preg_match('#/([^/]+)/application#', $basePath, $matches);
        if (isset($matches[1])) {
            $clientName = strtoupper($matches[1]);
        }
        $timestamp = date('Y-m-d H:i:s');
        echo "[$timestamp] CronTray::generate_cache() -> Client: $clientName\n";

        // ---------------------------------------------------------
        // Advisory Mutex Lock (Non-Blocking)
        // Mencegah 2 proses cron berjalan paralel di server yang sama.
        // ---------------------------------------------------------
        $lockResult = $this->db->query("SELECT GET_LOCK('cron_tray_cache_lock', 0) AS got_lock")->row();
        if (!$lockResult || $lockResult->got_lock != 1) {
            echo "[$timestamp] SKIP: Proses cron lain masih berjalan (mutex aktif).\n";
            return;
        }

        $tStart = microtime(true);

        // ---------------------------------------------------------
        // Fase 1: Siapkan Batch ID Baru (Atomik)
        // ---------------------------------------------------------
        $newBatch = time();

        // Gunakan isolasi READ UNCOMMITTED agar tidak terkena row-lock
        // dari transaksi kasir yang sedang berjalan.
        $this->db->query("SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED");

        // ---------------------------------------------------------
        // 1. Kueri Agregasi 1: Transaksi Pokok dengan batch_id baru (Murni INSERT)
        // ---------------------------------------------------------
        $sqlInsert1 = "
        INSERT INTO sys_cache_tray_transaksi 
            (batch_id, cabang_id, cabang2_id, jenis_master, next_step_num, next_step_code, next_substep_num, jenis_label, oleh_id, qty)
        SELECT 
            $newBatch, t.cabang_id, t.cabang2_id, t.jenis_master, t.next_step_num, t.next_step_code, 
            d.next_substep_num, t.jenis_label, t.oleh_id, COUNT(1) as qty
        FROM (
            SELECT DISTINCT transaksi_id, next_substep_num 
            FROM transaksi_data 
            WHERE trash = '0' AND sub_step_number > 0 AND valid_qty > 0
        ) d
        JOIN transaksi t ON t.id = d.transaksi_id
        WHERE t.status = '1' AND t.trash = '0' AND t.link_id = '0'
        GROUP BY t.cabang_id, t.cabang2_id, t.jenis_master, t.next_step_num, t.next_step_code, 
                 d.next_substep_num, t.jenis_label, t.oleh_id
        ";
        $this->db->query($sqlInsert1);

        // ---------------------------------------------------------
        // 2. Kueri Agregasi 2: Due Date dengan batch_id baru (Murni INSERT)
        // ---------------------------------------------------------
        $sqlInsert2 = "
        INSERT INTO sys_cache_tray_duedate (batch_id, cabang_id, dtime, due_date, customers_id)
        SELECT $newBatch, cabang_id, dtime, due_date, customers_id
        FROM transaksi_due_date
        WHERE status = '1' AND trash = '0'
        ";
        $this->db->query($sqlInsert2);

        // ---------------------------------------------------------
        // 3. Kueri Agregasi 3: Tagihan / Sumber Pembayaran Bersih & Teragregasi (Pre-Aggregation)
        // Filter target_jenis dan extern_id cacat (0, 0000, minus) dan GROUP BY per vendor
        // ---------------------------------------------------------
        $sqlInsert3 = "
        INSERT INTO sys_cache_tray_payment (batch_id, cabang_id, target_jenis, extern_id, sisa)
        SELECT 
            $newBatch, cabang_id, target_jenis, extern_id, SUM(sisa) as sisa
        FROM transaksi_payment_source
        WHERE sisa > 1000
          AND target_jenis NOT IN ('0', '0000', '')
          AND CAST(target_jenis AS SIGNED) > 0
          AND extern_id NOT IN ('0', '0000', '')
          AND CAST(extern_id AS SIGNED) > 0
        GROUP BY cabang_id, target_jenis, extern_id
        ";
        $this->db->query($sqlInsert3);

        // ---------------------------------------------------------
        // 4. Update Pointer Versi Aktif (Murni DML UPDATE, instan < 1 ms)
        // ---------------------------------------------------------
        $dtimeNow = date('Y-m-d H:i:s');
        $this->db->query("
            INSERT INTO sys_cache_tray_version (id, active_batch, updated_at)
            VALUES (1, $newBatch, '$dtimeNow')
            ON DUPLICATE KEY UPDATE active_batch = $newBatch, updated_at = '$dtimeNow'
        ");

        // ---------------------------------------------------------
        // 5. Cleanup Batch Lama (Chunked Delete Ramah Replica Server)
        // ---------------------------------------------------------
        $cleanTables = array('sys_cache_tray_transaksi', 'sys_cache_tray_duedate', 'sys_cache_tray_payment');
        foreach ($cleanTables as $cTbl) {
            do {
                $this->db->query("DELETE FROM `$cTbl` WHERE batch_id != ? LIMIT 5000", array($newBatch));
                $aff = $this->db->affected_rows();
            } while ($aff >= 5000);
        }

        // ---------------------------------------------------------
        // 6. Lepas Advisory Mutex Lock
        // ---------------------------------------------------------
        $this->db->query("SELECT RELEASE_LOCK('cron_tray_cache_lock')");

        $tEnd = microtime(true);
        $durasi = round($tEnd - $tStart, 4);

        echo "[$timestamp] Selesai generate cache (Batch $newBatch). Durasi: {$durasi} detik.\n";
    }

    public function verify_cache()
    {
        // Ambil active batch pointer dari tabel sys_cache_tray_version
        $verRow = $this->db->query("SELECT * FROM sys_cache_tray_version WHERE id = 1")->row();
        $activeBatch = 0;
        if ($verRow && isset($verRow->active_batch)) {
            $activeBatch = (int)$verRow->active_batch;
        }

        $now = date('Y-m-d H:i:s');
        $updatedAt = ($verRow && isset($verRow->updated_at)) ? $verRow->updated_at : '-';
        $selisihDetik = ($verRow && isset($verRow->updated_at)) ? (time() - strtotime($verRow->updated_at)) : 0;
        $selisihMenit = round($selisihDetik / 60, 1);

        echo "========================================================\n";
        echo "STATUS KEAKTIFAN CRON / CACHE:\n";
        echo " - Waktu Server Saat Ini : $now\n";
        echo " - Terakhir Diperbarui   : $updatedAt\n";
        echo " - Selisih Waktu         : {$selisihDetik} detik lalu ({$selisihMenit} menit lalu)\n";
        echo " - Active Batch Pointer  : " . ($activeBatch > 0 ? $activeBatch : "Semua / None") . "\n";
        echo "========================================================\n";
        echo "1. ISI TABEL CACHE (sys_cache_tray_transaksi) JENIS 110:\n";
        echo "========================================================\n";
        $batchCondition = ($activeBatch > 0) ? " AND batch_id = $activeBatch " : "";
        $qCache = $this->db->query("
            SELECT next_step_num, next_step_code, next_substep_num, jenis_label, SUM(qty) as total_qty
            FROM sys_cache_tray_transaksi 
            WHERE jenis_master = '110' $batchCondition
            GROUP BY next_step_num, next_step_code, next_substep_num, jenis_label
            ORDER BY next_step_num ASC
        ")->result();

        $totalCache = 0;
        foreach ($qCache as $r) {
            echo "Step {$r->next_step_num} | Substep {$r->next_substep_num} | Code: {$r->next_step_code} | Label: {$r->jenis_label} => {$r->total_qty}\n";
            $totalCache += $r->total_qty;
        }
        echo "TOTAL CACHE (Badge Kiri / Sidebar): $totalCache\n\n";

        echo "========================================================\n";
        echo "2. DATA REAL DARI TABEL TRANSAKSI & TRANSAKSI_DATA JENIS 110:\n";
        echo "========================================================\n";
        $qReal = $this->db->query("
            SELECT 
                d.sub_step_number,
                COUNT(DISTINCT t.id) as total_transaksi
            FROM transaksi t
            JOIN transaksi_data d ON d.transaksi_id = t.id
            WHERE t.status = '1' AND t.trash = '0' AND t.link_id = '0'
              AND d.trash = '0' AND d.sub_step_number > 0 AND d.valid_qty > 0
              AND t.jenis_master = '110'
            GROUP BY d.sub_step_number
            ORDER BY d.sub_step_number ASC
        ")->result();

        $totalReal = 0;
        foreach ($qReal as $r) {
            echo "Step/Tab {$r->sub_step_number} => {$r->total_transaksi} transaksi\n";
            $totalReal += $r->total_transaksi;
        }
        echo "TOTAL TRANSAKSI RIIL (Penjumlahan 3 Tab): $totalReal\n";
        echo "========================================================\n";

        echo "\n========================================================\n";
        echo "3. VERIFIKASI CACHE PEMBAYARAN (JENIS 489 - FG A/P):\n";
        echo "========================================================\n";
        $qPay = $this->db->query("
            SELECT extern_id, count(1) as inv_count, sum(sisa) as total_sisa
            FROM sys_cache_tray_payment
            WHERE target_jenis = '489' AND cabang_id = '-1' AND sisa > 1000 $batchCondition
            GROUP BY extern_id
        ")->result();
        echo "Total Vendor di sys_cache_tray_payment (Cabang -1 / Pusat): " . count($qPay) . "\n";
        foreach ($qPay as $p) {
            echo " - Vendor ID: {$p->extern_id} | Invoices: {$p->inv_count} | Sisa Total: " . number_format($p->total_sisa) . "\n";
        }

        echo "\n========================================================\n";
        echo "4. VERIFIKASI SEMUA JENIS PEMBAYARAN DI CACHE (Cabang -1):\n";
        echo "========================================================\n";
        $qAllPay = $this->db->query("
            SELECT target_jenis, count(distinct extern_id) as total_vendors, count(1) as total_invoices, sum(sisa) as grand_sisa
            FROM sys_cache_tray_payment
            WHERE cabang_id = '-1' AND sisa > 1000 $batchCondition
            GROUP BY target_jenis
        ")->result();
        $totalAllPaymentBadge = 0;
        foreach ($qAllPay as $ap) {
            echo "Jenis {$ap->target_jenis} => Unique Vendors: {$ap->total_vendors} (Total Sisa: " . number_format($ap->grand_sisa) . ")\n";
            $totalAllPaymentBadge += $ap->total_vendors;
        }
        echo "TOTAL VENDOR SEMUA PEMBAYARAN: $totalAllPaymentBadge\n";
        echo "========================================================\n";
    }

    public function setup_tables()
    {
        // Hanya dijalankan sekali saat inisialisasi awal database baru
        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_version (
                id INT PRIMARY KEY,
                active_batch INT,
                updated_at DATETIME
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_transaksi (
                batch_id INT,
                cabang_id VARCHAR(50),
                cabang2_id VARCHAR(50),
                jenis_master VARCHAR(50),
                next_step_num INT,
                next_step_code VARCHAR(50),
                next_substep_num INT,
                jenis_label VARCHAR(100),
                oleh_id VARCHAR(50),
                qty INT,
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_cabang2 (cabang2_id),
                INDEX idx_jenis_master (jenis_master),
                INDEX idx_batch_cabang_jenis (batch_id, cabang_id, jenis_master)
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_duedate (
                batch_id INT,
                cabang_id VARCHAR(50),
                dtime DATETIME,
                due_date DATETIME,
                customers_id VARCHAR(50),
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_customer (customers_id)
            ) ENGINE=InnoDB
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS sys_cache_tray_payment (
                batch_id INT,
                cabang_id VARCHAR(50),
                target_jenis VARCHAR(50),
                extern_id VARCHAR(50),
                sisa DOUBLE,
                INDEX idx_batch (batch_id),
                INDEX idx_cabang (cabang_id),
                INDEX idx_target_jenis (target_jenis),
                INDEX idx_extern (extern_id)
            ) ENGINE=InnoDB
        ");

        // Adaptasi tabel warisan lama (jika tabel sudah ada tetapi belum memiliki kolom batch_id)
        $this->db->query("ALTER TABLE sys_cache_tray_transaksi ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id), ADD INDEX IF NOT EXISTS idx_batch_cabang_jenis (batch_id, cabang_id, jenis_master)");
        $this->db->query("ALTER TABLE sys_cache_tray_duedate ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id)");
        $this->db->query("ALTER TABLE sys_cache_tray_payment ADD COLUMN IF NOT EXISTS batch_id INT FIRST, ADD INDEX IF NOT EXISTS idx_batch (batch_id)");

        echo "Struktur tabel cache berhasil diinisialisasi.\n";
    }
}
// END OF COMPLETE REPEATED LOGIC