/*
  DRAFT SQL KOREKSI LOCKER VS REKENING (DRY-RUN ONLY)
  ------------------------------------------------------------------
  Tujuan:
  - Menyiapkan draft koreksi saldo locker per transaksi_id.
  - Acuan kebenaran: rekening pembantu 1010020030.

  Penting:
  - Script ini TIDAK melakukan UPDATE/INSERT/DELETE.
  - Output script berupa preview dan generator statement SQL saja.
  - Jalankan dulu semua bagian SELECT untuk validasi hasil.
*/

-- ================================================================
-- SESSION CHARSET / COLLATION GUARD
-- Menghindari ERROR 1267 (illegal mix of collations) dari client Windows
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
-- 1) BASELINE REKONSILIASI PER TRANSAKSI_ID
-- ================================================================
WITH
rek AS (
    SELECT
        r.transaksi_id,
        r.extern_id AS supplier_id,
        SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_piutangsupplier__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern_id = @supplier_id
      AND r.debet > 0
    GROUP BY r.transaksi_id, r.extern_id
),
lk AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        SUM(l.nilai) AS locker_nilai,
        SUM(l.nilai_diklaim) AS locker_nilai_diklaim,
        SUM(l.nilai + l.nilai_diklaim) AS locker_awal
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
    GROUP BY l.transaksi_id, l.supplier_id
),
base AS (
    SELECT
        rek.transaksi_id,
        rek.supplier_id,
        rek.debet_rek,
        IFNULL(lk.locker_awal, 0) AS locker_awal,
        IFNULL(lk.locker_nilai, 0) AS locker_nilai,
        IFNULL(lk.locker_nilai_diklaim, 0) AS locker_nilai_diklaim,
        rek.debet_rek - IFNULL(lk.locker_awal, 0) AS delta_awal
    FROM rek
    LEFT JOIN lk
           ON lk.transaksi_id = rek.transaksi_id
          AND lk.supplier_id = rek.supplier_id
)
SELECT
    b.transaksi_id,
    t.jenis,
    t.nomer,
    t.fulldate,
    b.debet_rek,
    b.locker_awal,
    b.locker_nilai,
    b.locker_nilai_diklaim,
    b.delta_awal,
    CASE
        WHEN ABS(b.delta_awal) < @epsilon THEN 'MATCH'
        WHEN b.locker_awal = 0 THEN 'LOCKER_ZERO'
        WHEN b.delta_awal > 0 THEN 'LOCKER_UNDER'
        ELSE 'LOCKER_OVER'
    END AS status_rekonsiliasi
FROM base b
LEFT JOIN transaksi t ON t.id = b.transaksi_id
ORDER BY ABS(b.delta_awal) DESC, b.transaksi_id;


-- ================================================================
-- 2) DRY-RUN TARGET NILAI LOCKER PER TRANSAKSI_ID
--    target_locker_awal = debet_rek
--    target_nilai_active = debet_rek - locker_nilai_diklaim
-- ================================================================
WITH
rek AS (
    SELECT r.transaksi_id, r.extern_id AS supplier_id, SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_piutangsupplier__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern_id = @supplier_id
      AND r.debet > 0
    GROUP BY r.transaksi_id, r.extern_id
),
lk AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        SUM(l.nilai) AS locker_nilai,
        SUM(l.nilai_diklaim) AS locker_nilai_diklaim,
        SUM(l.nilai + l.nilai_diklaim) AS locker_awal
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
    GROUP BY l.transaksi_id, l.supplier_id
)
SELECT
    rek.transaksi_id,
    t.jenis,
    t.nomer,
    rek.debet_rek AS target_locker_awal,
    IFNULL(lk.locker_awal, 0) AS locker_awal_saat_ini,
    IFNULL(lk.locker_nilai, 0) AS locker_nilai_active_saat_ini,
    IFNULL(lk.locker_nilai_diklaim, 0) AS locker_nilai_diklaim_saat_ini,
    (rek.debet_rek - IFNULL(lk.locker_awal, 0)) AS delta_locker_awal,
    (rek.debet_rek - IFNULL(lk.locker_nilai_diklaim, 0)) AS target_locker_nilai_active,
    ((rek.debet_rek - IFNULL(lk.locker_nilai_diklaim, 0)) - IFNULL(lk.locker_nilai, 0)) AS delta_locker_nilai_active
FROM rek
LEFT JOIN lk
       ON lk.transaksi_id = rek.transaksi_id
      AND lk.supplier_id = rek.supplier_id
LEFT JOIN transaksi t ON t.id = rek.transaksi_id
WHERE ABS(rek.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon
ORDER BY ABS(rek.debet_rek - IFNULL(lk.locker_awal, 0)) DESC, rek.transaksi_id;


-- ================================================================
-- 3) GENERATOR UPDATE PREVIEW (HANYA OUTPUT SQL TEXT)
--    Logika distribusi delta_awal:
--    - Jika total_awal transaksi > 0 => proporsional per row_awal.
--    - Jika total_awal transaksi = 0 => seluruh delta ke row id terkecil.
-- ================================================================
WITH
rek AS (
    SELECT r.transaksi_id, r.extern_id AS supplier_id, SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_piutangsupplier__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern_id = @supplier_id
      AND r.debet > 0
    GROUP BY r.transaksi_id, r.extern_id
),
lk_agg AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        SUM(l.nilai + l.nilai_diklaim) AS locker_awal
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
    GROUP BY l.transaksi_id, l.supplier_id
),
mismatch AS (
    SELECT
        rek.transaksi_id,
        rek.supplier_id,
        (rek.debet_rek - IFNULL(lk_agg.locker_awal, 0)) AS delta_awal
    FROM rek
    LEFT JOIN lk_agg
           ON lk_agg.transaksi_id = rek.transaksi_id
          AND lk_agg.supplier_id = rek.supplier_id
    WHERE ABS(rek.debet_rek - IFNULL(lk_agg.locker_awal, 0)) >= @epsilon
),
locker_rows AS (
    SELECT
        l.id,
        l.transaksi_id,
        l.supplier_id,
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
        MIN(lr.id) AS first_id,
        SUM(lr.row_awal) AS total_awal
    FROM locker_rows lr
    GROUP BY lr.transaksi_id, lr.supplier_id
),
preview_dist AS (
    SELECT
        m.transaksi_id,
        lr.id,
        lr.nilai,
        lr.nilai_diklaim,
        lr.row_awal,
        ldb.total_awal,
        ldb.first_id,
        m.delta_awal,
        CASE
            WHEN ldb.total_awal > 0 THEN (lr.row_awal / ldb.total_awal) * m.delta_awal
            WHEN ldb.total_awal = 0 AND lr.id = ldb.first_id THEN m.delta_awal
            ELSE 0
        END AS delta_nilai
    FROM mismatch m
    JOIN locker_rows lr
      ON lr.transaksi_id = m.transaksi_id
     AND lr.supplier_id = m.supplier_id
    JOIN locker_dist_base ldb
      ON ldb.transaksi_id = lr.transaksi_id
     AND ldb.supplier_id = lr.supplier_id
)
SELECT
    pd.transaksi_id,
    pd.id AS locker_id,
    ROUND(pd.nilai, 10) AS old_nilai,
    ROUND(pd.delta_nilai, 10) AS delta_nilai,
    ROUND(pd.nilai + pd.delta_nilai, 10) AS new_nilai,
    CASE
        WHEN (pd.nilai + pd.delta_nilai) < 0 THEN 'NEGATIVE_WARNING'
        ELSE 'OK'
    END AS validation_new_nilai,
    CONCAT(
        'UPDATE stock_locker_diskon SET nilai = ', ROUND(pd.nilai + pd.delta_nilai, 10),
        ' WHERE id = ', pd.id, ';'
    ) AS sql_preview
FROM preview_dist pd
WHERE ABS(pd.delta_nilai) >= @epsilon
ORDER BY ABS(pd.delta_nilai) DESC, pd.transaksi_id, pd.id;


-- ================================================================
-- 4) GENERATOR INSERT PREVIEW UNTUK TRANSAKSI TANPA BARIS LOCKER
--    (HANYA OUTPUT SQL TEXT)
--    Sumber nilai insert: __rek_pembantu_subpiutangsuppliertransproduk__1010020030
-- ================================================================
WITH
rek AS (
    SELECT r.transaksi_id, r.extern_id AS supplier_id, SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_piutangsupplier__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern_id = @supplier_id
      AND r.debet > 0
    GROUP BY r.transaksi_id, r.extern_id
),
locker_exists AS (
    SELECT l.transaksi_id, l.supplier_id, COUNT(*) AS row_cnt
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
    GROUP BY l.transaksi_id, l.supplier_id
),
trx_no_locker AS (
    SELECT rek.transaksi_id, rek.supplier_id, rek.debet_rek
    FROM rek
    LEFT JOIN locker_exists le
           ON le.transaksi_id = rek.transaksi_id
          AND le.supplier_id = rek.supplier_id
    WHERE IFNULL(le.row_cnt, 0) = 0
),
split_line AS (
    SELECT
        s.transaksi_id,
        s.extern2_id AS supplier_id,
        s.extern_id AS diskon_id,
        IFNULL(NULLIF(s.extern_nama, ''), CONCAT('diskon_', s.extern_id)) AS diskon_nama,
        IFNULL(s.extern3_id, 0) AS produk_beli_id,
        IFNULL(s.extern3_nama, '') AS produk_beli_nama,
        SUM(s.debet) AS debet_line
    FROM __rek_pembantu_subpiutangsuppliertransproduk__1010020030 s
    JOIN trx_no_locker x
      ON x.transaksi_id = s.transaksi_id
     AND x.supplier_id = s.extern2_id
    WHERE s.cabang_id = @cabang_id
      AND s.debet > 0
    GROUP BY
        s.transaksi_id,
        s.extern2_id,
        s.extern_id,
        s.extern_nama,
        s.extern3_id,
        s.extern3_nama
)
SELECT
    sl.transaksi_id,
    t.jenis,
    t.nomer,
    sl.diskon_id,
    sl.diskon_nama,
    sl.produk_beli_id,
    sl.produk_beli_nama,
    ROUND(sl.debet_line, 10) AS suggested_nilai,
    CONCAT(
        'INSERT INTO stock_locker_diskon (',
        'supplier_id,supplier_nama,jenis,jenis_locker,cabang_id,gudang_id,',
        'produk_id,nama,state,jumlah,fulldate,oleh_id,transaksi_id,nomer,',
        'extern_id,extern_nama,extern2_id,extern2_nama,nilai_unit,nilai,nilai_diklaim,periode,tgl,bln,thn',
        ') VALUES (',
        sl.supplier_id, ',', QUOTE(IFNULL(t.suppliers_nama, '')), ',''diskon'',''stock'',',
        @cabang_id, ',', @gudang_id, ',',
        sl.diskon_id, ',', QUOTE(sl.diskon_nama), ',''active'',0,',
        QUOTE(CONCAT(t.fulldate, ' 00:00:00')), ',0,',
        sl.transaksi_id, ',', QUOTE(IFNULL(t.nomer, '')), ',',
        sl.diskon_id, ',', QUOTE(sl.diskon_nama), ',',
        sl.produk_beli_id, ',', QUOTE(sl.produk_beli_nama), ',',
        '0,', ROUND(sl.debet_line, 10), ',0,''forever'',',
        DAY(t.fulldate), ',', MONTH(t.fulldate), ',', YEAR(t.fulldate),
        ');'
    ) AS sql_preview
FROM split_line sl
LEFT JOIN transaksi t ON t.id = sl.transaksi_id
ORDER BY sl.transaksi_id, sl.diskon_id, sl.produk_beli_id;


-- ================================================================
-- 5) VALIDASI PASCA-KOREKSI (DRAFT QUERY CEK ULANG)
--    Jalankan ini setelah SQL koreksi benar-benar dieksekusi.
-- ================================================================
WITH
rek AS (
    SELECT r.transaksi_id, r.extern_id AS supplier_id, SUM(r.debet) AS debet_rek
    FROM __rek_pembantu_piutangsupplier__1010020030 r
    WHERE r.rekening = @rekening
      AND r.cabang_id = @cabang_id
      AND r.extern_id = @supplier_id
      AND r.debet > 0
    GROUP BY r.transaksi_id, r.extern_id
),
lk AS (
    SELECT
        l.transaksi_id,
        l.supplier_id,
        SUM(l.nilai + l.nilai_diklaim) AS locker_awal
    FROM stock_locker_diskon l
    WHERE l.jenis = 'diskon'
      AND l.jenis_locker = 'stock'
      AND l.cabang_id = @cabang_id
      AND l.gudang_id = @gudang_id
      AND l.supplier_id = @supplier_id
    GROUP BY l.transaksi_id, l.supplier_id
)
SELECT
    rek.transaksi_id,
    t.jenis,
    t.nomer,
    rek.debet_rek,
    IFNULL(lk.locker_awal, 0) AS locker_awal,
    (rek.debet_rek - IFNULL(lk.locker_awal, 0)) AS selisih
FROM rek
LEFT JOIN lk
       ON lk.transaksi_id = rek.transaksi_id
      AND lk.supplier_id = rek.supplier_id
LEFT JOIN transaksi t ON t.id = rek.transaksi_id
WHERE ABS(rek.debet_rek - IFNULL(lk.locker_awal, 0)) >= @epsilon
ORDER BY ABS(rek.debet_rek - IFNULL(lk.locker_awal, 0)) DESC, rek.transaksi_id;
