/*
  EXECUTE SQL KOREKSI LOCKER VS REKENING
  Granularitas : per transaksi_id + per diskon_id
  ------------------------------------------------------------------
  Acuan bisnis : rekening 1010020030 adalah sumber kebenaran.

  MODE EKSEKUSI:
  - @do_commit = 0  => DRY-RUN EKSEKUSI (jalankan UPDATE/INSERT lalu ROLLBACK)
  - @do_commit = 1  => REAL EKSEKUSI (jalankan UPDATE/INSERT lalu COMMIT)

  Catatan penting:
  - Default script ini @do_commit = 0 (aman untuk test).
  - Tabel backup per-run dibuat otomatis: stock_locker_diskon_backup_rekon_YYYYMMDD_HHMMSS
  - Jika mode dry-run, tabel backup per-run akan di-drop otomatis di akhir.
*/

-- ================================================================
-- SESSION CHARSET / COLLATION GUARD
-- ================================================================
SET NAMES latin1 COLLATE latin1_swedish_ci;
SET collation_connection = latin1_swedish_ci;

-- ================================================================
-- PARAMETER
-- ================================================================
SET @supplier_id := 4;
SET @rekening := '1010020030';
SET @cabang_id := -1;
SET @gudang_id := -1;
SET @epsilon := 0.01;
SET @do_commit := 1; -- ubah ke 1 untuk real commit

SET @run_label := DATE_FORMAT(NOW(), '%Y%m%d_%H%i%s');
SET @backup_table := CONCAT('stock_locker_diskon_backup_rekon_', @run_label);

-- ================================================================
-- BUAT TABEL BACKUP PER-RUN (DDL di luar transaksi)
-- ================================================================
SET @sql_create_backup := CONCAT('CREATE TABLE ', @backup_table, ' LIKE stock_locker_diskon');
PREPARE stmt_create_backup FROM @sql_create_backup;
EXECUTE stmt_create_backup;
DEALLOCATE PREPARE stmt_create_backup;

SELECT CONCAT('backup_table=', @backup_table) AS info_backup_table;
SELECT CONCAT('do_commit=', @do_commit, ' (0=rollback, 1=commit)') AS info_mode;


-- ================================================================
-- PREPARE PHASE (NO TRANSACTION)
-- NOTE:
-- - DDL seperti ALTER TABLE (termasuk temporary table) bisa implicit commit.
-- - Karena itu, semua DDL/temporary-table disiapkan dulu.
-- - START TRANSACTION dipindah ke fase APPLY (sebelum backup + update/insert).
-- ================================================================

-- ================================================================
-- 0) MAP TRANSAKSI AKTIF (exclude transaksi dibatalkan / trash_4=1)
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_transaksi_active;
CREATE TEMPORARY TABLE tmp_transaksi_active ENGINE=InnoDB AS
SELECT t.id AS transaksi_id
FROM transaksi t
WHERE IFNULL(t.trash_4, 0) <> 1;

ALTER TABLE tmp_transaksi_active
    ADD PRIMARY KEY (transaksi_id);


-- ================================================================
-- 1) SOURCE REKENING PER transaksi_id + diskon_id
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_rek_diskon;
CREATE TEMPORARY TABLE tmp_rek_diskon ENGINE=InnoDB AS
SELECT
    r.transaksi_id,
    r.extern3_id AS supplier_id,
    r.extern2_id AS diskon_id,
    MAX(r.extern2_nama) AS diskon_nama,
    SUM(r.debet) AS debet_rek
FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
JOIN tmp_transaksi_active ta
  ON ta.transaksi_id = r.transaksi_id
WHERE r.rekening = @rekening
  AND r.cabang_id = @cabang_id
  AND r.extern3_id = @supplier_id
  AND r.debet > 0
GROUP BY
    r.transaksi_id,
    r.extern3_id,
    r.extern2_id;

ALTER TABLE tmp_rek_diskon
    ADD INDEX idx_tmp_rek_key (transaksi_id, supplier_id, diskon_id);


-- ================================================================
-- 2) SOURCE LOCKER AGG PER transaksi_id + diskon_id
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_locker_key_agg;
CREATE TEMPORARY TABLE tmp_locker_key_agg ENGINE=InnoDB AS
SELECT
    l.transaksi_id,
    l.supplier_id,
    l.produk_id AS diskon_id,
    MAX(l.nama) AS diskon_nama,
    COUNT(*) AS locker_row_cnt,
    SUM(l.nilai) AS locker_nilai,
    SUM(l.nilai_diklaim) AS locker_nilai_diklaim,
    SUM(l.nilai + l.nilai_diklaim) AS locker_awal
FROM stock_locker_diskon l
JOIN tmp_transaksi_active ta
  ON ta.transaksi_id = l.transaksi_id
WHERE l.jenis = 'diskon'
  AND l.jenis_locker = 'stock'
  AND l.cabang_id = @cabang_id
  AND l.gudang_id = @gudang_id
  AND l.supplier_id = @supplier_id
GROUP BY
    l.transaksi_id,
    l.supplier_id,
    l.produk_id;

ALTER TABLE tmp_locker_key_agg
    ADD INDEX idx_tmp_locker_key (transaksi_id, supplier_id, diskon_id);


-- ================================================================
-- 3) MISMATCH KEY PER transaksi_id + diskon_id
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_mismatch_key;
CREATE TEMPORARY TABLE tmp_mismatch_key ENGINE=InnoDB AS
SELECT
    rk.transaksi_id,
    rk.supplier_id,
    rk.diskon_id,
    rk.diskon_nama,
    rk.debet_rek,
    IFNULL(lk.locker_awal, 0) AS locker_awal,
    IFNULL(lk.locker_nilai, 0) AS locker_nilai,
    IFNULL(lk.locker_nilai_diklaim, 0) AS locker_nilai_diklaim,
    IFNULL(lk.locker_row_cnt, 0) AS locker_row_cnt,
    (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_awal,
    CASE
        WHEN IFNULL(lk.locker_row_cnt, 0) = 0 THEN 'INSERT_KEY'
        WHEN ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon THEN 'UPDATE_KEY'
        ELSE 'MATCH'
    END AS action_type
FROM tmp_rek_diskon rk
LEFT JOIN tmp_locker_key_agg lk
       ON lk.transaksi_id = rk.transaksi_id
      AND lk.supplier_id = rk.supplier_id
      AND lk.diskon_id = rk.diskon_id;

ALTER TABLE tmp_mismatch_key
    ADD INDEX idx_tmp_mismatch_action (action_type),
    ADD INDEX idx_tmp_mismatch_key (transaksi_id, supplier_id, diskon_id);

SELECT action_type, COUNT(*) AS jumlah_key,
       SUM(debet_rek) AS sum_debet_rek,
       SUM(locker_awal) AS sum_locker_awal,
       SUM(delta_awal) AS sum_delta
FROM tmp_mismatch_key
GROUP BY action_type;


-- ================================================================
-- 4) RENCANA UPDATE ROW LOCKER (KEY EXISTING)
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_locker_rows;
CREATE TEMPORARY TABLE tmp_locker_rows ENGINE=InnoDB AS
SELECT
    l.id AS locker_id,
    l.transaksi_id,
    l.supplier_id,
    l.produk_id AS diskon_id,
    l.nilai,
    l.nilai_diklaim,
    (l.nilai + l.nilai_diklaim) AS row_awal
FROM stock_locker_diskon l
JOIN tmp_mismatch_key mk
  ON mk.transaksi_id = l.transaksi_id
 AND mk.supplier_id = l.supplier_id
 AND mk.diskon_id = l.produk_id
WHERE mk.action_type = 'UPDATE_KEY'
  AND l.jenis = 'diskon'
  AND l.jenis_locker = 'stock'
  AND l.cabang_id = @cabang_id
  AND l.gudang_id = @gudang_id
  AND l.supplier_id = @supplier_id;

ALTER TABLE tmp_locker_rows
    ADD INDEX idx_tmp_locker_rows_key (transaksi_id, supplier_id, diskon_id),
    ADD INDEX idx_tmp_locker_rows_id (locker_id);

DROP TEMPORARY TABLE IF EXISTS tmp_locker_dist_base;
CREATE TEMPORARY TABLE tmp_locker_dist_base ENGINE=InnoDB AS
SELECT
    transaksi_id,
    supplier_id,
    diskon_id,
    MIN(locker_id) AS first_locker_id,
    SUM(row_awal) AS total_awal_key
FROM tmp_locker_rows
GROUP BY transaksi_id, supplier_id, diskon_id;

ALTER TABLE tmp_locker_dist_base
    ADD INDEX idx_tmp_dist_key (transaksi_id, supplier_id, diskon_id);

DROP TEMPORARY TABLE IF EXISTS tmp_update_plan;
CREATE TEMPORARY TABLE tmp_update_plan ENGINE=InnoDB AS
SELECT
    lr.locker_id,
    lr.transaksi_id,
    lr.supplier_id,
    lr.diskon_id,
    lr.nilai AS old_nilai,
    lr.nilai_diklaim,
    mk.delta_awal,
    db.total_awal_key,
    db.first_locker_id,
    CASE
        WHEN db.total_awal_key > 0 THEN (lr.row_awal / db.total_awal_key) * mk.delta_awal
        WHEN db.total_awal_key = 0 AND lr.locker_id = db.first_locker_id THEN mk.delta_awal
        ELSE 0
    END AS delta_nilai,
    CASE
        WHEN db.total_awal_key > 0 THEN (lr.nilai + ((lr.row_awal / db.total_awal_key) * mk.delta_awal))
        WHEN db.total_awal_key = 0 AND lr.locker_id = db.first_locker_id THEN (lr.nilai + mk.delta_awal)
        ELSE lr.nilai
    END AS new_nilai
FROM tmp_locker_rows lr
JOIN tmp_mismatch_key mk
  ON mk.transaksi_id = lr.transaksi_id
 AND mk.supplier_id = lr.supplier_id
 AND mk.diskon_id = lr.diskon_id
JOIN tmp_locker_dist_base db
  ON db.transaksi_id = lr.transaksi_id
 AND db.supplier_id = lr.supplier_id
 AND db.diskon_id = lr.diskon_id
WHERE ABS(mk.delta_awal) >= @epsilon;

ALTER TABLE tmp_update_plan
    ADD INDEX idx_tmp_update_plan_id (locker_id),
    ADD INDEX idx_tmp_update_plan_key (transaksi_id, supplier_id, diskon_id);

SELECT
    COUNT(*) AS rows_update_plan,
    SUM(old_nilai) AS sum_old_nilai,
    SUM(new_nilai) AS sum_new_nilai,
    SUM(delta_nilai) AS sum_delta_nilai
FROM tmp_update_plan;

SELECT COUNT(*) AS negative_new_nilai_count
INTO @negative_new_nilai_count
FROM tmp_update_plan
WHERE new_nilai < -@epsilon;

SELECT @negative_new_nilai_count AS negative_new_nilai_count;


-- ================================================================
-- 5) RENCANA INSERT KEY LOCKER BARU (KEY MISSING)
-- ================================================================
DROP TEMPORARY TABLE IF EXISTS tmp_insert_plan;
CREATE TEMPORARY TABLE tmp_insert_plan ENGINE=InnoDB AS
SELECT
    s.transaksi_id,
    s.extern2_id AS supplier_id,
    s.extern_id AS diskon_id,
    MAX(s.extern_nama) AS diskon_nama,
    s.extern3_id AS produk_beli_id,
    MAX(s.extern3_nama) AS produk_beli_nama,
    SUM(s.debet) AS nilai_insert
FROM __rek_pembantu_subpiutangsuppliertransproduk__1010020030 s
JOIN tmp_transaksi_active ta
  ON ta.transaksi_id = s.transaksi_id
JOIN tmp_mismatch_key mk
  ON mk.transaksi_id = s.transaksi_id
 AND mk.supplier_id = s.extern2_id
 AND mk.diskon_id = s.extern_id
WHERE mk.action_type = 'INSERT_KEY'
  AND s.cabang_id = @cabang_id
  AND s.debet > 0
GROUP BY
    s.transaksi_id,
    s.extern2_id,
    s.extern_id,
    s.extern3_id;

ALTER TABLE tmp_insert_plan
    ADD INDEX idx_tmp_insert_plan_key (transaksi_id, supplier_id, diskon_id);

SELECT
    COUNT(*) AS rows_insert_plan,
    SUM(nilai_insert) AS sum_nilai_insert
FROM tmp_insert_plan;


-- ================================================================
-- 6) BACKUP DATA YANG AKAN DIUPDATE
-- ================================================================
START TRANSACTION;

SET @sql_backup_rows := CONCAT(
    'INSERT INTO ', @backup_table, ' ',
    'SELECT * FROM stock_locker_diskon WHERE id IN (SELECT locker_id FROM tmp_update_plan)'
);
PREPARE stmt_backup_rows FROM @sql_backup_rows;
EXECUTE stmt_backup_rows;
DEALLOCATE PREPARE stmt_backup_rows;

SELECT ROW_COUNT() AS backup_rows_inserted;


-- ================================================================
-- 7) EKSEKUSI UPDATE (hanya jika tidak ada nilai negatif)
-- ================================================================
SET @sql_apply_update := IF(
    @negative_new_nilai_count = 0,
    'UPDATE stock_locker_diskon l JOIN tmp_update_plan p ON p.locker_id = l.id SET l.nilai = p.new_nilai',
    'SELECT ''SKIP UPDATE: negative_new_nilai_count > 0'' AS warning_msg'
);

PREPARE stmt_apply_update FROM @sql_apply_update;
EXECUTE stmt_apply_update;
DEALLOCATE PREPARE stmt_apply_update;

SELECT ROW_COUNT() AS updated_rows;


-- ================================================================
-- 8) EKSEKUSI INSERT KEY MISSING
-- ================================================================
INSERT INTO stock_locker_diskon (
    supplier_id,
    supplier_nama,
    jenis,
    jenis_locker,
    cabang_id,
    gudang_id,
    produk_id,
    nama,
    state,
    jumlah,
    fulldate,
    oleh_id,
    transaksi_id,
    nomer,
    jumlah_tmp,
    oleh2_id,
    extern_id,
    extern_nama,
    extern2_id,
    extern2_nama,
    nilai_unit,
    nilai,
    nilai_diklaim,
    periode,
    tgl,
    bln,
    thn
)
SELECT
    ip.supplier_id,
    IFNULL(t.suppliers_nama, ''),
    'diskon',
    'stock',
    @cabang_id,
    @gudang_id,
    ip.diskon_id,
    IFNULL(ip.diskon_nama, CONCAT('diskon_', ip.diskon_id)),
    'active',
    0,
    CONCAT(t.fulldate, ' 00:00:00'),
    0,
    ip.transaksi_id,
    IFNULL(t.nomer, ''),
    0,
    0,
    ip.diskon_id,
    IFNULL(ip.diskon_nama, CONCAT('diskon_', ip.diskon_id)),
    IFNULL(ip.produk_beli_id, 0),
    IFNULL(ip.produk_beli_nama, ''),
    0,
    ip.nilai_insert,
    0,
    'forever',
    DAY(t.fulldate),
    MONTH(t.fulldate),
    YEAR(t.fulldate)
FROM tmp_insert_plan ip
JOIN tmp_transaksi_active ta
  ON ta.transaksi_id = ip.transaksi_id
JOIN transaksi t
  ON t.id = ip.transaksi_id
 AND IFNULL(t.trash_4, 0) <> 1;

SELECT ROW_COUNT() AS inserted_rows;


-- ================================================================
-- 9) VALIDASI HASIL DALAM TRANSAKSI BERJALAN
-- ================================================================
SELECT
    COUNT(*) AS total_key,
    SUM(CASE WHEN ABS(delta_awal) < @epsilon THEN 1 ELSE 0 END) AS key_match,
    SUM(CASE WHEN ABS(delta_awal) >= @epsilon THEN 1 ELSE 0 END) AS key_mismatch,
    SUM(debet_rek) AS sum_debet_rek,
    SUM(locker_awal) AS sum_locker_awal,
    SUM(delta_awal) AS sum_delta_awal
FROM (
    SELECT
        rk.transaksi_id,
        rk.supplier_id,
        rk.diskon_id,
        rk.debet_rek,
        IFNULL(lk.locker_awal, 0) AS locker_awal,
        (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_awal
    FROM tmp_rek_diskon rk
    LEFT JOIN (
        SELECT
            l.transaksi_id,
            l.supplier_id,
            l.produk_id AS diskon_id,
            SUM(l.nilai + l.nilai_diklaim) AS locker_awal
        FROM stock_locker_diskon l
        WHERE l.jenis = 'diskon'
          AND l.jenis_locker = 'stock'
          AND l.cabang_id = @cabang_id
          AND l.gudang_id = @gudang_id
          AND l.supplier_id = @supplier_id
        GROUP BY
            l.transaksi_id,
            l.supplier_id,
            l.produk_id
    ) lk
      ON lk.transaksi_id = rk.transaksi_id
     AND lk.supplier_id = rk.supplier_id
     AND lk.diskon_id = rk.diskon_id
) pc;

SELECT
    pc.transaksi_id,
    t.jenis,
    t.nomer,
    t.trash_4,
    pc.diskon_id,
    pc.debet_rek,
    pc.locker_awal,
    pc.delta_awal
FROM (
    SELECT
        rk.transaksi_id,
        rk.supplier_id,
        rk.diskon_id,
        rk.debet_rek,
        IFNULL(lk.locker_awal, 0) AS locker_awal,
        (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_awal
    FROM tmp_rek_diskon rk
    LEFT JOIN (
        SELECT
            l.transaksi_id,
            l.supplier_id,
            l.produk_id AS diskon_id,
            SUM(l.nilai + l.nilai_diklaim) AS locker_awal
        FROM stock_locker_diskon l
        WHERE l.jenis = 'diskon'
          AND l.jenis_locker = 'stock'
          AND l.cabang_id = @cabang_id
          AND l.gudang_id = @gudang_id
          AND l.supplier_id = @supplier_id
        GROUP BY
            l.transaksi_id,
            l.supplier_id,
            l.produk_id
    ) lk
      ON lk.transaksi_id = rk.transaksi_id
     AND lk.supplier_id = rk.supplier_id
     AND lk.diskon_id = rk.diskon_id
) pc
LEFT JOIN transaksi t ON t.id = pc.transaksi_id
WHERE ABS(pc.delta_awal) >= @epsilon
ORDER BY ABS(pc.delta_awal) DESC, pc.transaksi_id, pc.diskon_id
LIMIT 200;


-- ================================================================
-- 10) FINAL ACTION (COMMIT / ROLLBACK)
-- ================================================================
SET @final_action_sql := IF(@do_commit = 1, 'COMMIT', 'ROLLBACK');
SELECT CONCAT('final_action=', @final_action_sql) AS info_final_action;

PREPARE stmt_final_action FROM @final_action_sql;
EXECUTE stmt_final_action;
DEALLOCATE PREPARE stmt_final_action;


-- ================================================================
-- 11) CLEANUP BACKUP TABLE SAAT DRY-RUN
-- ================================================================
SET @cleanup_sql := IF(
    @do_commit = 0,
    CONCAT('DROP TABLE ', @backup_table),
    CONCAT('SELECT ''backup table kept: ', @backup_table, ''' AS info_backup_kept')
);

PREPARE stmt_cleanup FROM @cleanup_sql;
EXECUTE stmt_cleanup;
DEALLOCATE PREPARE stmt_cleanup;
