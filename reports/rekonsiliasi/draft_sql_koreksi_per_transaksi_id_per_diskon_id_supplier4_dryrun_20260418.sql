/*
  DRAFT SQL KOREKSI LOCKER VS REKENING (DRY-RUN ONLY)
  Granularitas: per transaksi_id + per diskon_id
  ------------------------------------------------------------------
  Tujuan:
  - Menyiapkan draft koreksi saldo locker diskon agar selaras dengan rekening.
  - Acuan kebenaran: rekening pembantu 1010020030.

  Penting:
  - Script ini TIDAK melakukan UPDATE/INSERT/DELETE langsung.
  - Output hanya berupa SELECT analisa + sql_preview (teks SQL).
  - Review dulu hasil preview sebelum eksekusi real.
*/

-- ================================================================
-- SESSION CHARSET / COLLATION GUARD
-- Menghindari ERROR 1267 (illegal mix of collations)
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


-- ================================================================
-- 1) BASELINE REKONSILIASI PER transaksi_id + diskon_id
-- ================================================================
WITH
rek_diskon AS (
    SELECT
        r.transaksi_id,
        r.extern3_id AS supplier_id,
        r.extern2_id AS diskon_id,
        MAX(r.extern2_nama) AS diskon_nama,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern3_id = @supplier_id
      AND r.debet > 0
    GROUP BY
        r.transaksi_id,
        r.extern3_id,
        r.extern2_id
),
lk_diskon AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        l.produk_id AS diskon_id,
        MAX(l.nama) AS diskon_nama,
        SUM(l.nilai) AS locker_nilai,
        SUM(l.nilai_diklaim) AS locker_nilai_diklaim,
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
),
key_union AS (
    SELECT transaksi_id, supplier_id, diskon_id FROM rek_diskon
    UNION
    SELECT transaksi_id, supplier_id, diskon_id FROM lk_diskon
)
SELECT
    ku.transaksi_id,
    t.jenis,
    t.nomer,
    t.fulldate,
    ku.supplier_id,
    COALESCE(rk.diskon_id, lk.diskon_id) AS diskon_id,
    COALESCE(rk.diskon_nama, lk.diskon_nama, CONCAT('diskon_', COALESCE(rk.diskon_id, lk.diskon_id))) AS diskon_nama,
    IFNULL(rk.debet_rek, 0) AS debet_rek,
    IFNULL(lk.locker_awal, 0) AS locker_awal,
    IFNULL(lk.locker_nilai, 0) AS locker_nilai_active,
    IFNULL(lk.locker_nilai_diklaim, 0) AS locker_nilai_diklaim,
    (IFNULL(rk.debet_rek, 0) - IFNULL(lk.locker_awal, 0)) AS selisih_awal,
    CASE
        WHEN rk.transaksi_id IS NULL THEN 'LOCKER_ORPHAN'
        WHEN lk.transaksi_id IS NULL THEN 'LOCKER_MISSING'
        WHEN ABS(IFNULL(rk.debet_rek, 0) - IFNULL(lk.locker_awal, 0)) < @epsilon THEN 'MATCH'
        WHEN IFNULL(lk.locker_awal, 0) = 0 THEN 'LOCKER_ZERO'
        WHEN IFNULL(rk.debet_rek, 0) > IFNULL(lk.locker_awal, 0) THEN 'LOCKER_UNDER'
        ELSE 'LOCKER_OVER'
    END AS status_rekonsiliasi
FROM key_union ku
LEFT JOIN rek_diskon rk
       ON rk.transaksi_id = ku.transaksi_id
      AND rk.supplier_id = ku.supplier_id
      AND rk.diskon_id = ku.diskon_id
LEFT JOIN lk_diskon lk
       ON lk.transaksi_id = ku.transaksi_id
      AND lk.supplier_id = ku.supplier_id
      AND lk.diskon_id = ku.diskon_id
LEFT JOIN transaksi t
       ON t.id = ku.transaksi_id
ORDER BY ABS(IFNULL(rk.debet_rek, 0) - IFNULL(lk.locker_awal, 0)) DESC, ku.transaksi_id, ku.diskon_id;


-- ================================================================
-- 2) DRY-RUN TARGET NILAI PER transaksi_id + diskon_id
--    target_locker_awal = debet_rek
--    target_nilai_active = debet_rek - locker_nilai_diklaim
-- ================================================================
WITH
rek_diskon AS (
    SELECT
        r.transaksi_id,
        r.extern3_id AS supplier_id,
        r.extern2_id AS diskon_id,
        MAX(r.extern2_nama) AS diskon_nama,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern3_id = @supplier_id
      AND r.debet > 0
    GROUP BY
        r.transaksi_id,
        r.extern3_id,
        r.extern2_id
),
lk_diskon AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        l.produk_id AS diskon_id,
        MAX(l.nama) AS diskon_nama,
        SUM(l.nilai) AS locker_nilai,
        SUM(l.nilai_diklaim) AS locker_nilai_diklaim,
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
)
SELECT
    rk.transaksi_id,
    t.jenis,
    t.nomer,
    rk.diskon_id,
    rk.diskon_nama,
    rk.debet_rek AS target_locker_awal,
    IFNULL(lk.locker_awal, 0) AS locker_awal_saat_ini,
    IFNULL(lk.locker_nilai, 0) AS locker_nilai_active_saat_ini,
    IFNULL(lk.locker_nilai_diklaim, 0) AS locker_nilai_diklaim_saat_ini,
    (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_locker_awal,
    (rk.debet_rek - IFNULL(lk.locker_nilai_diklaim, 0)) AS target_locker_nilai_active,
    ((rk.debet_rek - IFNULL(lk.locker_nilai_diklaim, 0)) - IFNULL(lk.locker_nilai, 0)) AS delta_locker_nilai_active
FROM rek_diskon rk
LEFT JOIN lk_diskon lk
       ON lk.transaksi_id = rk.transaksi_id
      AND lk.supplier_id = rk.supplier_id
      AND lk.diskon_id = rk.diskon_id
LEFT JOIN transaksi t
       ON t.id = rk.transaksi_id
WHERE ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon
ORDER BY ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) DESC, rk.transaksi_id, rk.diskon_id;


-- ================================================================
-- 3) GENERATOR UPDATE PREVIEW (HANYA OUTPUT SQL TEXT)
--    Distribusi delta dilakukan per transaksi_id + diskon_id:
--    - Jika total_awal_key > 0 => proporsional per row_awal.
--    - Jika total_awal_key = 0 => semua delta ke row id terkecil.
-- ================================================================
WITH
rek_diskon AS (
    SELECT
        r.transaksi_id,
        r.extern3_id AS supplier_id,
        r.extern2_id AS diskon_id,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern3_id = @supplier_id
      AND r.debet > 0
    GROUP BY
        r.transaksi_id,
        r.extern3_id,
        r.extern2_id
),
lk_diskon AS (
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
),
mismatch_key AS (
    SELECT
        rk.transaksi_id,
        rk.supplier_id,
        rk.diskon_id,
        (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_awal
    FROM rek_diskon rk
    LEFT JOIN lk_diskon lk
           ON lk.transaksi_id = rk.transaksi_id
          AND lk.supplier_id = rk.supplier_id
          AND lk.diskon_id = rk.diskon_id
    WHERE lk.transaksi_id IS NOT NULL
      AND ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon
),
locker_rows AS (
    SELECT
        l.id,
        l.transaksi_id,
        l.supplier_id,
        l.produk_id AS diskon_id,
        l.nilai,
        l.nilai_diklaim,
        (l.nilai + l.nilai_diklaim) AS row_awal
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
),
locker_dist_base AS (
    SELECT
        lr.transaksi_id,
        lr.supplier_id,
        lr.diskon_id,
        MIN(lr.id) AS first_id,
        SUM(lr.row_awal) AS total_awal_key
    FROM locker_rows lr
    GROUP BY
        lr.transaksi_id,
        lr.supplier_id,
        lr.diskon_id
),
preview_update AS (
    SELECT
        mk.transaksi_id,
        mk.supplier_id,
        mk.diskon_id,
        lr.id,
        lr.nilai,
        lr.nilai_diklaim,
        lr.row_awal,
        ldb.total_awal_key,
        ldb.first_id,
        mk.delta_awal,
        CASE
            WHEN ldb.total_awal_key > 0 THEN (lr.row_awal / ldb.total_awal_key) * mk.delta_awal
            WHEN ldb.total_awal_key = 0 AND lr.id = ldb.first_id THEN mk.delta_awal
            ELSE 0
        END AS delta_nilai
    FROM mismatch_key mk
    JOIN locker_rows lr
      ON lr.transaksi_id = mk.transaksi_id
     AND lr.supplier_id = mk.supplier_id
     AND lr.diskon_id = mk.diskon_id
    JOIN locker_dist_base ldb
      ON ldb.transaksi_id = lr.transaksi_id
     AND ldb.supplier_id = lr.supplier_id
     AND ldb.diskon_id = lr.diskon_id
)
SELECT
    pu.transaksi_id,
    pu.diskon_id,
    pu.id AS locker_id,
    ROUND(pu.nilai, 10) AS old_nilai,
    ROUND(pu.delta_nilai, 10) AS delta_nilai,
    ROUND(pu.nilai + pu.delta_nilai, 10) AS new_nilai,
    CASE
        WHEN (pu.nilai + pu.delta_nilai) < 0 THEN 'NEGATIVE_WARNING'
        ELSE 'OK'
    END AS validation_new_nilai,
    CONCAT(
        'UPDATE stock_locker_diskon SET nilai = ', ROUND(pu.nilai + pu.delta_nilai, 10),
        ' WHERE id = ', pu.id, ';'
    ) AS sql_preview
FROM preview_update pu
WHERE ABS(pu.delta_nilai) >= @epsilon
ORDER BY ABS(pu.delta_nilai) DESC, pu.transaksi_id, pu.diskon_id, pu.id;


-- ================================================================
-- 4) GENERATOR INSERT PREVIEW UNTUK KEY YANG BELUM ADA DI LOCKER
--    (HANYA OUTPUT SQL TEXT)
--    Insert disiapkan per transaksi_id + diskon_id, dibagi per produk.
-- ================================================================
WITH
rek_diskon AS (
    SELECT
        r.transaksi_id,
        r.extern3_id AS supplier_id,
        r.extern2_id AS diskon_id,
        MAX(r.extern2_nama) AS diskon_nama,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern3_id = @supplier_id
      AND r.debet > 0
    GROUP BY
        r.transaksi_id,
        r.extern3_id,
        r.extern2_id
),
lk_diskon AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        l.produk_id AS diskon_id
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
),
missing_key AS (
    SELECT
        rk.transaksi_id,
        rk.supplier_id,
        rk.diskon_id,
        rk.diskon_nama
    FROM rek_diskon rk
    LEFT JOIN lk_diskon lk
           ON lk.transaksi_id = rk.transaksi_id
          AND lk.supplier_id = rk.supplier_id
          AND lk.diskon_id = rk.diskon_id
    WHERE lk.transaksi_id IS NULL
),
src_split AS (
    SELECT
        s.transaksi_id,
        s.extern2_id AS supplier_id,
        s.extern_id AS diskon_id,
        MAX(s.extern_nama) AS diskon_nama,
        s.extern3_id AS produk_beli_id,
        MAX(s.extern3_nama) AS produk_beli_nama,
        SUM(s.debet) AS debet_line
    FROM __rek_pembantu_subpiutangsuppliertransproduk__1010020030 s
    JOIN missing_key mk
      ON mk.transaksi_id = s.transaksi_id
     AND mk.supplier_id = s.extern2_id
     AND mk.diskon_id = s.extern_id
    WHERE s.cabang_id = @cabang_id
      AND s.debet > 0
    GROUP BY
        s.transaksi_id,
        s.extern2_id,
        s.extern_id,
        s.extern3_id
)
SELECT
    ss.transaksi_id,
    t.jenis,
    t.nomer,
    ss.diskon_id,
    IFNULL(ss.diskon_nama, CONCAT('diskon_', ss.diskon_id)) AS diskon_nama,
    ss.produk_beli_id,
    IFNULL(ss.produk_beli_nama, '') AS produk_beli_nama,
    ROUND(ss.debet_line, 10) AS suggested_nilai,
    CONCAT(
        'INSERT INTO stock_locker_diskon (',
        'supplier_id,supplier_nama,jenis,jenis_locker,cabang_id,gudang_id,',
        'produk_id,nama,state,jumlah,fulldate,oleh_id,transaksi_id,nomer,jumlah_tmp,oleh2_id,',
        'extern_id,extern_nama,extern2_id,extern2_nama,nilai_unit,nilai,nilai_diklaim,periode,tgl,bln,thn',
        ') VALUES (',
        ss.supplier_id, ',', QUOTE(IFNULL(t.suppliers_nama, '')), ',''diskon'',''stock'',',
        @cabang_id, ',', @gudang_id, ',',
        ss.diskon_id, ',', QUOTE(IFNULL(ss.diskon_nama, CONCAT('diskon_', ss.diskon_id))), ',''active'',0,',
        QUOTE(CONCAT(t.fulldate, ' 00:00:00')), ',0,',
        ss.transaksi_id, ',', QUOTE(IFNULL(t.nomer, '')), ',0,0,',
        ss.diskon_id, ',', QUOTE(IFNULL(ss.diskon_nama, CONCAT('diskon_', ss.diskon_id))), ',',
        IFNULL(ss.produk_beli_id, 0), ',', QUOTE(IFNULL(ss.produk_beli_nama, '')), ',',
        '0,', ROUND(ss.debet_line, 10), ',0,''forever'',',
        DAY(t.fulldate), ',', MONTH(t.fulldate), ',', YEAR(t.fulldate),
        ');'
    ) AS sql_preview
FROM src_split ss
LEFT JOIN transaksi t
       ON t.id = ss.transaksi_id
ORDER BY ss.transaksi_id, ss.diskon_id, ss.produk_beli_id;


-- ================================================================
-- 5) VALIDASI PASCA-KOREKSI (DRAFT QUERY CEK ULANG)
--    Jalankan ini setelah SQL koreksi benar-benar dieksekusi.
-- ================================================================
WITH
rek_diskon AS (
    SELECT
        r.transaksi_id,
        r.extern3_id AS supplier_id,
        r.extern2_id AS diskon_id,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_subpiutangsuppliertrans__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern3_id = @supplier_id
      AND r.debet > 0
    GROUP BY
        r.transaksi_id,
        r.extern3_id,
        r.extern2_id
),
lk_diskon AS (
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
)
SELECT
    rk.transaksi_id,
    t.jenis,
    t.nomer,
    rk.diskon_id,
    rk.debet_rek,
    IFNULL(lk.locker_awal, 0) AS locker_awal,
    (rk.debet_rek - IFNULL(lk.locker_awal, 0)) AS selisih
FROM rek_diskon rk
LEFT JOIN lk_diskon lk
       ON lk.transaksi_id = rk.transaksi_id
      AND lk.supplier_id = rk.supplier_id
      AND lk.diskon_id = rk.diskon_id
LEFT JOIN transaksi t
       ON t.id = rk.transaksi_id
WHERE ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon
ORDER BY ABS(rk.debet_rek - IFNULL(lk.locker_awal, 0)) DESC, rk.transaksi_id, rk.diskon_id;
