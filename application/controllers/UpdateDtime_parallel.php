<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateDtime_parallel extends CI_Controller {

    // Konfigurasi Target
//    private $target_table = 'reg_dummy'; // Ganti ke 'transaksi_data_registry' untuk live
    private $target_table = 'transaksi_data_registry'; // Ganti ke 'transaksi_data_registry' untuk live
    private $master_table = 'transaksi';
    private $batch_update_size = 500; // Jumlah row per update_batch query

    public function __construct() {
        parent::__construct();
        $this->load->database();

        // Pastikan helper yang berisi fungsi blobDecode() diload disini
        // $this->load->helper('nama_helper_anda');

        // Set PHP limits
        set_time_limit(0);
        ini_set('memory_limit', '4000M');
    }

    /**
     * GENERATOR: Membuat Script Bash berdasarkan ID Range
     * Command: php index.php UpdateDtime_parallel save_script 8
     */
    public function save_script($threads = 8) {
        echo "=== GENERATING SCRIPT (ID RANGE STRATEGY) ===\n";

        // 1. Ambil Min dan Max ID untuk menentukan boundaries
        $range = $this->db->query("SELECT MIN(transaksi_id) as min_id, MAX(transaksi_id) as max_id FROM {$this->target_table}")->row();

        $min_id = (int)$range->min_id;
        $max_id = (int)$range->max_id;
        $total_ids = $max_id - $min_id;

        if ($total_ids <= 0) {
            echo "Error: Table seems empty or invalid IDs.\n";
            return;
        }

        echo "Min ID: $min_id | Max ID: $max_id\n";
        echo "Total ID Range: " . number_format($total_ids) . "\n";
        echo "Threads: $threads\n\n";

        // 2. Hitung range per thread
        $step = ceil($total_ids / $threads);

        // 3. Generate Content Script
        $script = "#!/bin/bash\n\n";
        $script .= "echo 'Starting Parallel Process with ID Range Strategy...'\n";
        $script .= "mkdir -p " . FCPATH . "logs\n\n";

        $current_min = $min_id;

        for ($i = 0; $i < $threads; $i++) {
            $current_max = $current_min + $step;
            if ($current_max > $max_id) $current_max = $max_id + 1; // Safety buffer

            $log_file = FCPATH . "logs/thread_{$i}.log";

            // Command menjalankan worker dengan parameter START_ID dan END_ID
            $cmd = "php index.php UpdateDtime_parallel process_range {$current_min} {$current_max} > {$log_file} 2>&1 &";

            $script .= "echo 'Starting Thread $i (ID: $current_min - $current_max)'\n";
            $script .= $cmd . "\n";
            $script .= "echo $! > " . FCPATH . "logs/thread_{$i}.pid\n"; // Save PID

            $current_min = $current_max;
        }

        $script .= "\necho 'All threads started. Monitor logs in /logs folder.'\n";

        // 4. Save File
        $filename = FCPATH . 'run_parallel.sh';
        file_put_contents($filename, $script);
        chmod($filename, 0755);

        echo "Script generated: $filename\n";
        echo "Run with: ./run_parallel.sh\n";
    }

    /**
     * WORKER: Memproses data berdasarkan Range ID
     * Dipanggil oleh script bash
     */
    public function process_range($start_id, $end_id) {
        $start_time = microtime(true);
        echo "=== WORKER STARTED (Range: $start_id - $end_id) ===\n";

        $processed = 0;
        $updated = 0;

        // Loop internal di dalam range thread ini untuk hemat memori
        // Kita jalan per 1000 ID agar tidak load memori sekaligus
        $chunk_step = 2000;
        $current_cursor = $start_id;

        while ($current_cursor < $end_id) {
            $next_cursor = $current_cursor + $chunk_step;
            if ($next_cursor > $end_id) $next_cursor = $end_id;

            // 1. Ambil Data Mentah (Hanya yang dtime-nya invalid)
            // Mengambil kolom 'main' untuk didecode PHP dan join ke 'transaksi'
            $sql = "
                SELECT 
                    tdr.transaksi_id, 
                    tdr.main, 
                    t.dtime as master_dtime
                FROM {$this->target_table} tdr
                LEFT JOIN {$this->master_table} t ON t.id = tdr.transaksi_id
                WHERE tdr.transaksi_id >= ? AND tdr.transaksi_id < ?
                AND (
                    tdr.dtime IS NULL 
                    OR tdr.dtime = '' 
                    OR tdr.dtime = '0000-00-00 00:00:00'
                    OR tdr.dtime = '0000-00-00'
                )
            ";

            $query = $this->db->query($sql, [$current_cursor, $next_cursor]);
            $rows = $query->result_array();
            $query->free_result(); // Hapus dari memori query

            if (!empty($rows)) {
                $batch_data = [];

                foreach ($rows as $row) {
                    // === LOGIC PENENTUAN TANGGAL DI PHP ===
                    $final_dtime = $this->_determine_date($row);

                    $batch_data[] = [
                        'transaksi_id' => $row['transaksi_id'],
                        'dtime'        => $final_dtime
                    ];
                }

                // 2. Eksekusi Update Batch
                if (!empty($batch_data)) {
                    $this->db->update_batch($this->target_table, $batch_data, 'transaksi_id');
                    $updated += count($batch_data);
                    $processed += count($rows);

                    echo "Cursor $current_cursor: Updated " . count($batch_data) . " rows.\n";
                }
            }

            // Geser cursor
            $current_cursor = $next_cursor;

            // Jaga memori
            if ($processed % 5000 == 0) {
                gc_collect_cycles();
            }
        }

        $duration = microtime(true) - $start_time;
        echo "=== WORKER COMPLETED ===\n";
        echo "Updated: $updated rows\n";
        echo "Time: " . number_format($duration, 2) . "s\n";
    }

    /**
     * LOGIC UTAMA (PHP Side)
     * Menentukan tanggal terbaik dari berbagai sumber
     */
    private function _determine_date($row) {
        // Prioritas 1: Cek Master Table (transaksi)
        if ($this->_is_valid_date($row['master_dtime'])) {
            return $row['master_dtime'];
        }

        // Prioritas 2: Cek BLOB/JSON di kolom main
        // Gunakan fungsi blobDecode bawaan user jika ada, atau fallback manual
        $blob_data = [];
        if (!empty($row['main'])) {
            if (function_exists('blobDecode')) {
                $blob_data = blobDecode($row['main']);
            } else {
                // Fallback jika fungsi helper belum di load
                $blob_data = $this->_manual_decode($row['main']);
            }
        }

        // Cek dalam array hasil decode
        if (!empty($blob_data) && is_array($blob_data)) {
            // Cek key 'fulldate' (Format biasanya: 2024-01-01 10:10:10)
            if (isset($blob_data['fulldate']) && $this->_is_valid_date($blob_data['fulldate'])) {
                return $blob_data['fulldate'];
            }

            // Cek key 'dtime' dalam blob
            if (isset($blob_data['dtime']) && $this->_is_valid_date($blob_data['dtime'])) {
                return $blob_data['dtime'];
            }

            // Cek key 'dateFaktur' (Biasanya tanggal saja, tambahkan jam)
            if (isset($blob_data['dateFaktur']) && !empty($blob_data['dateFaktur'])) {
                $candidate = $blob_data['dateFaktur'] . ' 00:00:00';
                if ($this->_is_valid_date($candidate)) {
                    return $candidate;
                }
            }
        }

        // Prioritas 3 (Final): Gunakan Tanggal Sekarang (Sesuai request user)
        return date('Y-m-d H:i:s');
    }

    /**
     * Helper: Validasi format tanggal MySQL
     */
    private function _is_valid_date($date) {
        if (empty($date)) return false;
        if ($date == '0000-00-00 00:00:00' || $date == '0000-00-00') return false;

        // Cek format pattern sederhana
        if (!preg_match("/^\d{4}-\d{2}-\d{2}/", $date)) return false;

        return true;
    }

    /**
     * Helper Manual Decode (Safe Fallback)
     * Mencoba JSON decode, jika gagal coba Unserialize
     */
    private function _manual_decode($raw_data) {
        // Coba JSON dulu
        $json = json_decode($raw_data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }

        // Coba Unserialize (CodeIgniter lama sering pakai ini untuk BLOB)
        // Suppress error notice jika data korup
        $serialized = @unserialize($raw_data);
        if ($serialized !== false) {
            return $serialized;
        }

        return [];
    }

    /**
     * MONITOR progress semua threads
     */
    public function monitor() {
        echo "<pre>";
        echo "=== PARALLEL PROCESS MONITOR ===\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("-", 50) . "\n";

        header("refresh:5");

        // Check if logs directory exists
        $logs_dir = FCPATH . 'logs';
        if (!is_dir($logs_dir)) {
            echo "No logs directory found.\n";
            echo "Run generate_commands() first.\n";
            echo "</pre>";
            return;
        }

        // Scan for log files
        $log_files = glob($logs_dir . '/thread_*.log');

        if (empty($log_files)) {
            echo "No thread logs found.\n";
        }
        else {
            echo "Found " . count($log_files) . " thread logs:\n\n";

            foreach ($log_files as $log_file) {
                $thread_num = preg_replace('/.*thread_(\d+)\.log/', '$1', $log_file);
                $pid_file = $logs_dir . '/thread_' . $thread_num . '.pid';

                echo "Thread {$thread_num}:\n";

                // Check if process is still running
                if (file_exists($pid_file)) {
                    $pid = trim(file_get_contents($pid_file));
                    if (posix_getpgid($pid)) {
                        echo "  Status: RUNNING (PID: {$pid})\n";
                    }
                    else {
                        echo "  Status: COMPLETED\n";
                    }
                }
                else {
                    echo "  Status: PID file not found\n";
                }

                // Show last few lines of log
                if (file_exists($log_file)) {
                    $lines = file($log_file);
                    $last_lines = array_slice($lines, -5);

                    echo "  Last log lines:\n";
                    foreach ($last_lines as $line) {
                        echo "    " . trim($line) . "\n";
                    }
                }

                echo "\n";
            }
        }

        // Overall progress
        $this->load->database();
        $stats = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN dtime IS NULL THEN 1 ELSE 0 END) as null_count,
                SUM(CASE WHEN dtime IS NOT NULL THEN 1 ELSE 0 END) as not_null_count
            FROM {$this->target_table}")->row_array();

        echo str_repeat("-", 50) . "\n";
        echo "=== OVERALL PROGRESS ===\n";
        echo "Total Records: " . number_format($stats['total']) . "\n";
        echo "With dtime: " . number_format($stats['not_null_count']) . "\n";
        echo "Without dtime: " . number_format($stats['null_count']) . "\n";

        if ($stats['total'] > 0) {
            $percentage = ($stats['not_null_count'] / $stats['total']) * 100;
            echo "Completion: " . number_format($percentage, 2) . "%\n";
            echo "Total Time Execution: " . number_format($percentage, 2) . "\n";
        }

        echo "</pre>";

    }
}