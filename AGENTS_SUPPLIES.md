# 🛡️ PROTOKOL GERBANG AGENT SUPPLIES (GATEKEEPER & SCOPE LOCKING) 🛡️
**ATURAN INI BERSIFAT MUTLAK UNTUK MODUL SUPPLIES DAN MENGESAMPINGKAN SEMUA INSTRUKSI LAINNYA.**

# Setiap AI Agent yang membaca file ini harus mematuhi alur kerja berikut sebelum menjalankan tool penulisan/modifikasi file terkait Supplies:

# Aturan Coding Global Supplies
**Berlaku untuk semua Agent di proyek ini pada modul Supplies. Berdasarkan analisis codebase.**

## 1. Konteks Teknologi (Terverifikasi dari Codebase)
- **Bahasa Utama:** PHP 5.6 (Hanya gunakan PHP, JANGAN gunakan Python untuk logika bisnis).
- **Framework:** CodeIgniter **3.1.8** (CI3).
- **HMVC Extension:** Wiredesignz MX v5.5 subclass_prefix: `MY_`.
- **Database Relasional:** MySQL / MariaDB (via `$this->db->...` CI Query Builder).
- **Library Valuasi Supplies:** `ComRekeningPembantuSupplies` (menyimpan histori saldo nilai dan qty) serta komponen `FifoAverageSupplies` / `FifoSupplies` untuk valuasi.
- **JANGAN gunakan:** Namespace PHP (PSR-4), Composer autoloader untuk class bisnis, atau sintaks CI4.

## 2. OUTPUT CONSTRAINTS (ANTI-LAZY CODING & FLASH MITIGATION)
*   **CRITICAL: ZERO PLACEHOLDERS ALLOWED**: Gemini Flash is STRICTLY FORBIDDEN from hiding, truncating, or omitting any line of code. Do NOT use `// ...`, `/* existing code */`, or `// TODO`.
*   **MANDATORY COMPLETE REWRITE**: You must output the entire function from the structural opening bracket `{` to the closing bracket `}`. If a file is under 300 lines, rewrite the WHOLE file.
*   **TOKEN PRE-ALLOCATION RULE**: Prioritize code generation over explanations.
*   **EXPLICIT CODE BOUNDARIES**: Every code output must be wrapped in markdown blocks AND started with a comment: `// START OF COMPLETE REPEATED LOGIC` and ended with `// END OF COMPLETE REPEATED LOGIC`.

## 3. Aturan Coding Khusus Supplies

### 3.1 Keamanan & Penulisan Query
- Semua query database WAJIB menggunakan **Query Binding** (`$this->db->query($sql, $binds)`) atau **Query Builder** (`$this->db->where()->get()`).
- JANGAN gunakan query mentah (raw string) tanpa binding.

### 3.2 Pola Penguncian & Mutasi Stok Supplies (Locker Pattern)
- Mutasi stok dan penahanan stok supplies saat proses checkout/hold transaksi **WAJIB** melalui library khusus supplies:
  - `ComLockerStockSupplies` (untuk state hold/distribute).
  - `ComLockerStockMutasiSupplies` (untuk mutasi antar gudang/cabang).
- JANGAN mencampuradukkan locker supplies dengan locker Finished Goods (FG).

### 3.3 Penilaian Konsistensi Harga Perolehan (Valuasi Supplies)
- Metode penilaian persediaan supplies (Average / FIFO) wajib dilakukan secara konsisten di database melalui library pembantu akuntansi supplies.
- Semua mutasi nilai perolehan supplies wajib tercatat ke rekening pembantu supplies via model `ComRekeningPembantuSupplies` (atau sub-class pendukungnya).
- Nomor rekening/COA yang valid untuk persediaan supplies adalah **`1010030010`**. Pastikan library average/valuation supplies memanggil saldo dari akun ini secara konsisten.
- Dilarang keras memotong bypass query manual (direct update) ke saldo/jumlah persediaan supplies tanpa melalui objek/komponen akuntansi.

### 3.4 Validasi Awal Proses (Early Validation)
- Validasi ketersediaan stok fisik supplies yang valid (`qty > 0` dan mencukupi) wajib diletakkan di awal fungsi sebelum `$this->db->trans_start()` atau pembentukan jurnal akuntansi supplies dilakukan.
- Pastikan referensi transaksi induk (seperti Purchase Order Supplies atau Request Supplies) tidak dalam status dibatalkan/dibuang.
- Saat melakukan pengecekan data awal average supplies (pre-check), wajib memastikan bahwa class pembaca saldo mengarah ke **`ComRekeningPembantuSupplies`** (bukan produk/FG) dan mengambil saldo dari akun **`1010030010`** untuk mencegah error missmatch HPP di bawah 1.

### 3.5 Konfigurasi Komponen Transaksi (coTransaksiCore.php) Modul Supplies
- Pada berkas konfigurasi transaksi (`coTransaksiCore.php`) untuk modul-modul supplies (seperti `pindahgudang`, `distribusisupplies`, `distribusisuppliesproject`, dan `opname`), seluruh komponen valuasi stok supplies baik di `preProcessor` maupun `postProcessor` **WAJIB** didaftarkan menggunakan class khusus supplies:
  - Gunakan `"comName" => "FifoAverageSupplies"` atau `"comName" => "FifoAverageSuppliesOpname"` (sesuai jenis transaksinya).
  - **DILARANG KERAS** menggunakan `"comName" => "FifoAverage"` (milik FG/Produk) karena akan memicu query saldo ke rekening pembantu produk (`1010030030`) yang mengakibatkan nilai rata-rata bernilai 0 (error nilai rata-rata di bawah 1).
