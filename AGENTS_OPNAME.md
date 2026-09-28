# 🛡️ PROTOKOL GERBANG AGENT OPNAME (GATEKEEPER & SCOPE LOCKING) 🛡️
**ATURAN INI BERSIFAT MUTLAK UNTUK MODUL OPNAME DAN MENGESAMPINGKAN SEMUA INSTRUKSI LAINNYA.**

# Setiap AI Agent yang membaca file ini harus mematuhi alur kerja berikut sebelum menjalankan tool penulisan/modifikasi file terkait Opname:

# Aturan Coding Global Opname
**Berlaku untuk semua Agent di proyek ini pada modul Opname. Berdasarkan analisis codebase.**

## 1. Konteks Teknologi & Gateway (Terverifikasi dari Codebase)
- **Bahasa Utama:** PHP 5.6 (Hanya gunakan PHP, JANGAN gunakan Python untuk logika bisnis).
- **Framework:** CodeIgniter **3.1.8** (CI3).
- **HMVC Extension:** Wiredesignz MX v5.5 subclass_prefix: `MY_`.
- **Database Relasional:** MySQL / MariaDB (via `$this->db->...` CI Query Builder).
- **Gateway Kode Transaksi:**
  - **`1119`**: Stok Opname Produk Pusat (Complete/Final)
  - **`1119r` / `1119ro`**: Status Draft & Review (Pusat)
  - **`2229`**: Stok Opname Produk Cabang (Complete/Final)
  - **`2229r` / `2229ro`**: Status Draft & Review (Cabang)
- **JANGAN gunakan:** Namespace PHP (PSR-4), Composer autoloader untuk class bisnis, atau sintaks CI4.

## 2. OUTPUT CONSTRAINTS (ANTI-LAZY CODING & FLASH MITIGATION)
*   **CRITICAL: ZERO PLACEHOLDERS ALLOWED**: Gemini Flash is STRICTLY FORBIDDEN from hiding, truncating, or omitting any line of code. Do NOT use `// ...`, `/* existing code */`, or `// TODO`.
*   **MANDATORY COMPLETE REWRITE**: You must output the entire function from the structural opening bracket `{` to the closing bracket `}`. If a file is under 300 lines, rewrite the WHOLE file.
*   **TOKEN PRE-ALLOCATION RULE**: Prioritize code generation over explanations.
*   **EXPLICIT CODE BOUNDARIES**: Every code output must be wrapped in markdown blocks AND started with a comment: `// START OF COMPLETE REPEATED LOGIC` and ended with `// END OF COMPLETE REPEATED LOGIC`.

## 3. Aturan Coding Khusus Opname

### 3.1 Pengecekan Kunci Transaksi Gantung (Sebelum Opname)
- Sebelum memulai proses opname, sistem **WAJIB** mengecek dan memastikan tidak ada transaksi gantung (mutasi yang belum diselesaikan) di cabang/gudang yang bersangkutan.
- Pengecekan dilakukan dengan memanggil `$this->opname_model->callTransaksiBeforeOpname()` atau library terkait. Jika ditemukan transaksi gantung, proses opname **harus diblokir**.

### 3.2 Valuasi HPP FIFO/Average & Jurnal Akuntansi
- Selisih opname (`qty_opname` - `stok_sistem`) wajib dinilai berdasarkan HPP riil menggunakan library/komponen `FifoAverageOpname`.
- **Penjurnalan Otomatis (Step 3 - Complete):**
  - **Selisih Kurang (Loss/Kerugian):**
    - Debet: `7020020` (Kerugian/Beban Selisih Persediaan)
    - Kredit: `1010030030` (Persediaan Produk)
  - **Selisih Lebih (Profit/Laba):**
    - Debet: `1010030030` (Persediaan Produk)
    - Kredit: `7010150` (Laba lain-lain / Selisih Persediaan)

### 3.3 Penanganan Serial Number (S/N) Otomatis
- Jika produk memiliki serial number dan terjadi **selisih lebih** tanpa input scanner manual (misal via upload Excel), sistem **WAJIB** men-generate **System Serial Number** secara otomatis (disimpan pada `produk_serial_number_2`).
- Format Auto-Generate System Serial:
  `[Tanggal_PO]:[Urutan_PO]:[Urutan_GRN]:[Urutan_GRN_PO]:[ID_Produk]:[Urutan_Serial_Baru]:[Kode_Part]`

### 3.4 Validasi Sesi & Integritas Main Elements
- Selama transisi antar-langkah (Step 1 -> Step 2 -> Step 3) di `FollowUp.php` atau `__FollowUp.php`, array `main_elements` **WAJIB** dipertahankan dengan menyalinnya dari session lama ke session baru:
  ```php
  "main_elements" => isset($_SESSION[$oldCode]['main_elements']) ? $_SESSION[$oldCode]['main_elements'] : array(),
  ```
