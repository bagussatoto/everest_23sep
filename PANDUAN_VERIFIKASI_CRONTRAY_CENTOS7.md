# Panduan Teknis & Verifikasi Deployment CronTray (CentOS 7 Server)

Dokumen ini ditujukan sebagai panduan kerja (*runbook / agent guide*) untuk memeriksa, menyiapkan skema database, memvalidasi file, dan menguji eksekusi `CronTray` langsung pada server Linux CentOS 7 (PHP 5.6 + MariaDB / MySQL).

---

## 1. Ringkasan Arsitektur & Perubahan

| Komponen | Peran & Mekanisme |
| :--- | :--- |
| **`CronTray.php`** | Controller orkestrator regenerasi cache tray. Menggunakan **Murni DML (Active Version Pointer)** + `session_write_close()` untuk menjamin Zero-Downtime dan non-blocking. |
| **`he_tray_helper.php`** | Helper pembaca cache tray di sisi aplikasi client. Membaca data dengan filter `batch_id = active_batch`. |
| **`sys_cache_tray_version`** | Tabel pointer penyimpan ID batch aktif (`active_batch`). Diperbarui secara atomik (< 1 ms). |
| **Tabel Cache Tray** | `sys_cache_tray_transaksi`, `sys_cache_tray_duedate`, `sys_cache_tray_payment`. |

---

## 2. Persiapan Skema Database (Dijalankan 1 Kali oleh DBA/Agent)

Buka shell MySQL/MariaDB pada server:
```bash
mysql -u <db_user> -p <db_name>
```

Jalankan kueri berikut untuk memastikan tabel pointer dan kolom `batch_id` telah siap:

```sql
-- 1. Buat tabel pointer versi aktif
CREATE TABLE IF NOT EXISTS `sys_cache_tray_version` (
  `id` int(11) NOT NULL,
  `active_batch` int(11) DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `sys_cache_tray_version` (`id`, `active_batch`, `updated_at`) 
VALUES (1, 0, NOW());

-- 2. Pastikan tabel cache transaksi ada & memiliki kolom batch_id
CREATE TABLE IF NOT EXISTS `sys_cache_tray_transaksi` (
  `batch_id` int(11) DEFAULT NULL,
  `cabang_id` varchar(50) DEFAULT NULL,
  `cabang2_id` varchar(50) DEFAULT NULL,
  `jenis_master` varchar(50) DEFAULT NULL,
  `next_step_num` int(11) DEFAULT NULL,
  `next_step_code` varchar(50) DEFAULT NULL,
  `next_substep_num` int(11) DEFAULT NULL,
  `jenis_label` varchar(100) DEFAULT NULL,
  `oleh_id` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  INDEX `idx_batch` (`batch_id`),
  INDEX `idx_cabang` (`cabang_id`),
  INDEX `idx_cabang2` (`cabang2_id`),
  INDEX `idx_jenis_master` (`jenis_master`),
  INDEX `idx_batch_cabang_jenis` (`batch_id`, `cabang_id`, `jenis_master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Pastikan tabel cache due date ada & memiliki kolom batch_id
CREATE TABLE IF NOT EXISTS `sys_cache_tray_duedate` (
  `batch_id` int(11) DEFAULT NULL,
  `cabang_id` varchar(50) DEFAULT NULL,
  `dtime` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `customers_id` varchar(50) DEFAULT NULL,
  INDEX `idx_batch` (`batch_id`),
  INDEX `idx_cabang` (`cabang_id`),
  INDEX `idx_customer` (`customers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. Pastikan tabel cache payment ada & memiliki kolom batch_id
CREATE TABLE IF NOT EXISTS `sys_cache_tray_payment` (
  `batch_id` int(11) DEFAULT NULL,
  `cabang_id` varchar(50) DEFAULT NULL,
  `target_jenis` varchar(50) DEFAULT NULL,
  `extern_id` varchar(50) DEFAULT NULL,
  `sisa` double DEFAULT NULL,
  INDEX `idx_batch` (`batch_id`),
  INDEX `idx_cabang` (`cabang_id`),
  INDEX `idx_target_jenis` (`target_jenis`),
  INDEX `idx_extern` (`extern_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### Opsional: Indeks Optimasi Tambahan (Sangat Disarankan untuk Data Besar)
```sql
-- Mempercepat pemindaian transaksi_due_date (menghilangkan Full Table Scan)
ALTER TABLE `transaksi_due_date` 
  ADD INDEX `idx_due_status_trash` (`status`, `trash`, `due_date`, `customers_id`, `cabang_id`);

-- Covering Index untuk 1.7+ juta baris transaksi_data
ALTER TABLE `transaksi_data` 
  ADD INDEX `idx_tray_undone_fast` (`trash`, `sub_step_number`, `valid_qty`, `transaksi_id`, `next_substep_num`);
```

---

## 3. Langkah Verifikasi File di Server Linux

Masuk ke direktori web root project (misal: `/var/www/html/everest_13agus` atau sesuai path server):

```bash
cd /path/to/everest_13agus
```

### A. Uji Sintaks PHP 5.6 (Lint Test)
Pastikan kedua file bebas dari *syntax error*:
```bash
php -l application/controllers/CronTray.php
php -l application/helpers/he_tray_helper.php
```
*Ekspektasi Output:*
`No syntax errors detected in ...`

---

## 4. Eksekusi & Uji Coba dari CLI (Linux Terminal)

### A. Jalankan Inisialisasi Tabel Cache (Hanya 1x)
```bash
php index.php CronTray setup_tables
```

### B. Jalankan Regenerasi Cache Tray
```bash
php index.php CronTray generate_cache
```
*Ekspektasi Output:*
```text
Memulai proses agregasi cache tray (Murni DML: Active Version Pointer)...
Cache berhasil diperbarui (Active Batch: <timestamp>) secara Zero-Downtime DML dalam waktu ~15-20 detik.
```

### C. Verifikasi Sinkronisasi Angka Database vs Tab
```bash
php index.php CronTray check_db_stats
```
*Ekspektasi Output:*
* Menampilkan `ACTIVE BATCH POINTER: <timestamp>`
* Menampilkan rincian step 110 (Prepare E-Faktur `601` + Entry E-Faktur `39` = `640`).

---

## 5. Eksekusi & Uji Coba via HTTP / Webserver

### A. Via cURL di Local Server
```bash
curl -i "http://127.0.0.1/everest_13agus/CronTray/generate_cache?secure_key=cron_tray_cache_123"
```
*(Sesuaikan base URL dengan virtual host server).*

### B. Via Web Browser
Buka di browser:
```text
http://<domain_atau_ip>/CronTray/generate_cache?secure_key=cron_tray_cache_123
```
*Ekspektasi:* Halaman merespons dalam waktu $\approx$ **15–20 detik** tanpa memblokir tab aplikasi lain.

---

## 6. Konfigurasi Otomatisasi Crontab (CentOS 7)

Agar cache selalu terbarukan otomatis setiap 5 atau 10 menit di server CentOS:

1. Buka crontab editor:
   ```bash
   crontab -e
   ```
2. Tambahkan baris berikut:
   ```bash
   # Regenerasi cache tray setiap 5 menit via PHP CLI
   */5 * * * * /usr/bin/php /path/to/everest_13agus/index.php CronTray generate_cache >> /path/to/everest_13agus/logs/cron_tray.log 2>&1
   ```
3. Simpan dan pastikan service crond aktif:
   ```bash
   systemctl status crond
   ```

---

## 7. Diagnostik & Troubleshooting

Jika terjadi kelambatan kueri di server produksi:

1. **Periksa Kueri yang Sedang Berjalan & Lock Database**:
   ```bash
   mysql -e "SHOW FULL PROCESSLIST;"
   ```
   *Cek apakah ada status `Waiting for table metadata lock` atau `Locked`.*

2. **Periksa Log Eksekusi Cron**:
   ```bash
   tail -n 50 /path/to/everest_13agus/logs/cron_tray.log
   ```

3. **Periksa Error Log Webserver**:
   ```bash
   # Untuk Apache:
   tail -n 50 /var/log/httpd/error_log
   # Untuk Nginx & PHP-FPM:
   tail -n 50 /var/log/php-fpm/www-error.log
   ```
