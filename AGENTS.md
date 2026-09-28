# 🛡️ PROTOKOL GERBANG AGENT (GATEKEEPER & SCOPE LOCKING) 🛡️
**ATURAN INI BERSIFAT MUTLAK DAN MENGESAMPINGKAN SEMUA INSTRUKSI LAINNYA.**

# Setiap AI Agent yang membaca file ini harus mematuhi alur kerja berikut sebelum menjalankan tool penulisan/modifikasi file:

# Aturan Coding Global
**Berlaku untuk semua Agent di proyek ini. Berdasarkan analisis codebase, bukan asumsi.**

## 1. Konteks Teknologi (Terverifikasi dari Codebase)
- **Bahasa Utama:** PHP 5.6 (Hanya gunakan PHP, JANGAN gunakan Python untuk logika bisnis).
- **Framework:** CodeIgniter **3.1.8** (CI3) — `CI_VERSION = '3.1.8'` di `system/core/CodeIgniter.php`.
- **HMVC Extension:** Wiredesignz MX v5.5 (`application/third_party/MX/`) — subclass_prefix: `MY_`.
- **Database Relasional:** MySQL / MariaDB (via `$this->db->...` CI Query Builder).
- **Database NoSQL:** MongoDB (via library `Mongo_db` di `application/libraries/Mongo_db.php`).
- **Library Pihak Ketiga:** PHPExcel (bukan PhpSpreadsheet), CodeIgniter Curl (Philip Sturgeon).
- **JANGAN gunakan:** Namespace PHP (PSR-4), Composer autoloader untuk class bisnis, atau sintaks CI4 (`app/Controllers/`, `Services::`, dll).


## 2. OUTPUT CONSTRAINTS (ANTI-LAZY CODING & FLASH MITIGATION)
*   **CRITICAL: ZERO PLACEHOLDERS ALLOWED**: Gemini Flash is STRICTLY FORBIDDEN from hiding, truncating, or omitting any line of code. Do NOT use `// ...`, `/* existing code */`, or `// TODO`.
*   **MANDATORY COMPLETE REWRITE**: You must output the entire function from the structural opening bracket `{` to the closing bracket `}`. If a file is under 300 lines, rewrite the WHOLE file.
*   **TOKEN PRE-ALLOCATION RULE**: Prioritize code generation over explanations. If you run out of tokens, stop exactly at a valid syntax boundary, do not rush by omitting inner logic.
*   **EXPLICIT CODE BOUNDARIES**: Every code output must be wrapped in markdown blocks AND started with a comment: `// START OF COMPLETE REPEATED LOGIC` and ended with `// END OF COMPLETE REPEATED LOGIC`.
### 2.1 Struktur HMVC Modul
```
application/
├── core/              # MY_Loader.php, MY_Router.php (extend MX)
├── config/            # config.php, database.php, routes.php
├── controllers/       # Controller non-modul
├── helpers/           # he_*_helper.php (custom helpers)
├── libraries/         # Library custom (Layout, Curl, Mongo_db, PHPExcel, dll)
├── models/
│   └── Coms/          # Model bisnis — prefix Com* (extends CI_Model)
├── modules/
│   └── [nama_modul]/
│       ├── config/    # coTransaksiCore.php, coTransaksiUi.php, coTransaksiLayout.php, coTransaksiValues.php
│       └── controllers/
│           ├── Modul_Controller.php   # Base controller modul (extends MX_Controller)
│           ├── _shoppingCart.php      # extends Modul_Controller
│           ├── _processSelect*.php    # extends Modul_Controller
│           ├── _selectorItem.php      # extends Modul_Controller
│           ├── Create.php             # extends Modul_Controller
│           ├── FollowUp.php           # extends Modul_Controller
│           └── ...
├── third_party/MX/    # Wiredesignz HMVC
└── views/
```

### 2.2 Controller Hierarchy
```
CI_Controller (system/core/Controller.php)
  └── MX_Controller (third_party/MX/Controller.php)
      └── Modul_Controller (modules/[modul]/controllers/Modul_Controller.php)
          └── _shoppingCart, _processSelect*, Create, FollowUp, dll
```

### 2.3 Konvensi Penamaan File Controller (per Modul)
| Prefix | Peran |
|--------|-------|
| `Modul_Controller.php` | Base controller — session validation, load 4 config |
| `_shoppingCart.php` | Cart management + stock locker hold/active |
| `_processSelect*.php` | Add/edit/remove item + hold stock |
| `_selectorItem.php` | Product/supplies picker modal |
| `_processPihak*.php` | Party (customer/supplier) selector |
| `Create.php` | Create transaction (draft) |
| `FollowUp.php` / `__FollowUp.php` | Approval / follow-up |
| `ActivityReport.php` | Transaction report |
| `Printing.php` | Print document |
| `Transaksi.php` | Transaction list |

### 2.4 Konfigurasi Modul (4 Config Wajib)
| File | Isi |
|------|-----|
| `coTransaksiCore.php` | Gateway mapping, component (Com*) registration, FIFO |
| `coTransaksiUi.php` | Step definitions, UI labels, form fields |
| `coTransaksiLayout.php` | Table columns, layout rendering |
| `coTransaksiValues.php` | Value mapping, calculation rules |

## 3. Aturan Coding

### 3.1 Keamanan
- Semua query database WAJIB menggunakan **Query Binding** (`$this->db->query($sql, $binds)`) atau **Query Builder** (`$this->db->where()->get()`).
- JANGAN gunakan query mentah (raw string) tanpa binding.
- **Pengecualian yang ada:** Beberapa `UPDATE transaksi SET indexing_*` di modul `produksi` menggunakan raw string untuk JSON blob — ini technical debt, JANGAN ditiru di kode baru.

### 3.2 Model Pattern
- Model bisnis prefix `Com*` extends `CI_Model`, berlokasi di `application/models/Coms/`.
- Load dengan: `$this->load->model("Coms/ComLockerStockDualWrite");` lalu `$dw = new ComLockerStockDualWrite();`
- Dual-write stock locker **WAJIB** lewat `ComLockerStockDualWrite` — bukan tulis manual ke 2 tabel.

### 3.3 Helper Pattern
- Custom helper prefix `he_` di `application/helpers/`.
- Load dengan: `$this->load->helper("he_url");`
- Helper pairs di `application/helpers/Pairs/` — format `he_cek_*_helper.php`, `he_pair_*_helper.php`.
- Penamaan file WAJIB berakhiran `_helper.php` (konvensi CI3).

### 3.4 Transaksi Database
- Selalu gunakan `$this->db->trans_start()` dan `$this->db->trans_complete()` untuk wrapping operasi multi-query.
- JANGAN manual `BEGIN`/`COMMIT`/`ROLLBACK`.

### 3.5 Komentar & Komunikasi
- Komentar kode berbahasa Indonesia pada logika yang kompleks.
- Langsung ke kode — jangan penjelasan teori panjang.
- Jika instruksi kurang jelas atau berpotensi merusak struktur database, tanyakan dulu sebelum menulis kode.

### 3.6 PHP 5.6 Compatibility (JANGAN Gunakan)
- JANGAN gunakan: `...` spread operator, null coalescing `??`, anonymous classes, return type declarations, scalar type hints, `match` expression, named arguments.
- Gunakan: `isset($x) ? $x : $default` (bukan `$x ?? $default`).
- Gunakan: `array()` syntax (bukan `[]` short array — meskipun beberapa kode baru sudah pakai, tetap pakai `array()` untuk konsistensi).
- Gunakan: `function($x) { ... }` untuk closure (bukan arrow function `fn($x) => ...`).

- jangan pernah menawarkan perbaikan, tanpa diminta. nanti saya akan menginstruksikan perbaikan apa yang harus di lakukan

### 3.7 Konsistensi Data Sesi (`main_elements`) saat Transisi Modul
- Saat membuat atau memodifikasi transisi antar-langkah/modul transaksi di file `FollowUp.php` (atau controller sejenis), Agent **WAJIB** memastikan array `main_elements` ikut disalin dari sesi lama (`$oldCode`) ke sesi baru (`$cCode`). Hal ini penting agar elemen formulir bawaan (seperti pilihan gudang default) tidak hilang.
- Gunakan penulisan aman yang kompatibel dengan PHP 5.6:
  `"main_elements" => isset($_SESSION[$oldCode]['main_elements']) ? $_SESSION[$oldCode]['main_elements'] : array(),`

### 3.8 Validasi Awal Proses (Early Validation Pattern)
- Agent **WAJIB** menempatkan pemeriksaan validitas data/state di bagian awal fungsi transaksi (fail-fast) sebelum menjalankan query penulisan database, transaksi DB (`$this->db->trans_start()`), atau pembentukan struktur accounting.
- **Daftar Validasi yang Disarankan di Awal Proses:**
  1. **Validasi Sesi & Parameter URL:** Periksa kecocokan `bookingNumber`, `stepCode`, serta segmen-segmen URL transaksi di session.
  2. **Validasi Indikasi Transaksi Ganda (Locker Check):** Lakukan query lock pada `MdlLockerTransaksi` (state='hold', jumlah='1', oleh_id=my_id()) untuk memvalidasi bahwa lock aktif masih dipegang oleh user sebelum proses berjalan.
  3. **Validasi Relasi Transaksi Induk:** Periksa apakah transaksi induk/referensi (seperti SO atau Request) tidak dalam status reject/cancel (`trash_4 = 1`).
  4. **Validasi Qty Valid (Item State Check):** Periksa apakah barang-barang dalam transaksi masih memiliki jumlah yang valid (`valid_qty > 0`) dan belum diproses oleh alur otorisasi paralel lain.

### 3.9 Pola Sesi Mobile / Tablet Scanner (Session Stability Pattern)
Berlaku untuk **semua modul transaksi** yang memiliki akses via perangkat mobile/tablet scanner (fungsi `followupDariHp()` atau sejenisnya).

#### 3.9.1 Larangan Penggunaan `<iframe>` untuk Inisiasi Sesi Scanner
- **DILARANG** menggunakan tag `<iframe>` tersembunyi untuk memanggil `createSimpleSessionLogin`, `forceMobile`, atau endpoint sesi lainnya dari halaman yang diakses tablet.
- **Alasan:** Browser Apple Safari (iOS/iPadOS) menerapkan ITP (*Intelligent Tracking Prevention*) yang **memblokir cookie PHP (`PHPSESSID`) di dalam iframe** sebagai third-party context, sehingga sesi tidak terbentuk dengan benar.
- **Solusi WAJIB:** Gunakan redirect langsung (`location.href`) di jendela utama dengan pola redirect berantai:
  ```
  followupDariHp (ismob=1)
      ↓ redirect (bukan iframe)
  createSimpleSessionLogin  ← hanya jika $_SESSION['login'] belum ada
      ↓ via parameter goto
  forceMobile               ← hanya jika forceMobile belum diset
      ↓ via parameter goto
  followupPrePreview        ← tujuan akhir, sesi transaksi dibangun dari DB
  ```

#### 3.9.2 Implementasi Redirect Berantai di `followupDariHp()`
Pola standar yang **WAJIB** diikuti di semua modul yang memiliki fungsi `followupDariHp()`:
```php
if ($ismob == 1) {
    $finalUrl = MODUL_PATH . "FollowUp/followupPrePreview/$trjenis/$transaksiID_reference/$stepNum/$stepNumCurrent?ismob=1";

    if (!isset($_SESSION['login'])) {
        // Langkah 1: buat sesi login dulu
        $gotoAfterForceMobile = base_url() . "auth/Login/forceMobile?forceMobile=1&goto=" . urlencode($finalUrl);
        $data = array('id' => '555', 'nama_login' => 'John Doe', 'jenis' => 'scaner',
                      'cabang_id' => my_cabang_id(), 'gudang_id' => my_gudang_id());
        $queryString = http_build_query($data);
        $CreateSessionLogin = base_url() . "auth/Login/createSimpleSessionLogin?" . $queryString . "&goto=" . urlencode($gotoAfterForceMobile);
        echo "<script>location.href='" . $CreateSessionLogin . "';</script>";
    }
    elseif (!isset($_SESSION['login']['forceMobile']) || $_SESSION['login']['forceMobile'] != 1) {
        // Langkah 2: aktifkan mode mobile
        $forceMobileUrl = base_url() . "auth/Login/forceMobile?forceMobile=1&goto=" . urlencode($finalUrl);
        echo "<script>location.href='" . $forceMobileUrl . "';</script>";
    }
    else {
        // Langkah 3: semua siap, langsung ke tujuan akhir
        echo "<script>location.href='" . $finalUrl . "';</script>";
    }
}
```

#### 3.9.3 Dukungan Parameter `goto` di `auth/Login`
- Fungsi `forceMobile()` dan `createSimpleSessionLogin()` di `application/modules/auth/controllers/Login.php` **WAJIB** mendukung parameter `goto`.
- Jika `$_GET['goto']` ada dan tidak kosong, lakukan `header("Location: ...")` dan `exit()` setelah menyelesaikan tugasnya.
- Ini sudah diimplementasikan — **JANGAN dihapus atau diubah tanpa pertimbangan**.

#### 3.9.4 Penanganan Sesi Habis (Session Expired) untuk Scanner
- Fungsi `gotoLogin()` di `application/helpers/he_misc_helper.php` **WAJIB** mendeteksi request dari scanner sebelum melakukan redirect ke halaman login biasa.
- Deteksi scanner menggunakan 4 kondisi (OR):
  1. `$_GET['ismob'] == 1`
  2. `HTTP_REFERER` mengandung string `followupDariHp`
  3. `HTTP_REFERER` mengandung string `followupPreviewMobile`
  4. `$_SESSION['login']['jenis'] == 'scaner'`
- Jika terdeteksi sebagai scanner: **tampilkan halaman HTML "Sesi Habis"** dengan tombol "Mulai Ulang Sesi" — **BUKAN redirect ke form login umum**.
- Halaman sesi habis **TIDAK BOLEH** memiliki countdown otomatis — user yang mengontrol kapan menekan tombol.
- Saat tombol ditekan, alur kembali ke `followupDariHp()` dan redirect berantai (3.9.2) akan membangun ulang sesi dari database secara otomatis.

#### 3.9.5 Modul yang Terdampak
Rule ini berlaku untuk **semua** modul berikut yang memiliki fungsi `followupDariHp()` atau akses scanner sejenis:
- `distribusifg`, `distribusifg_non_paket`, `distribusifgproject`
- `distribusijasa`, `distribusisupplies`, `distribusisuppliesproject`
- `penerimaan`, `penerimaanprojek`
- `inputstok`, `inputstokunit`
- Semua modul lain yang menambahkan akses scanner di masa depan

### 3.10 Pola Pengunci Transaksi Ganda (Locker Transaksi)
Berlaku untuk memastikan transaksi penting tidak diproses secara paralel oleh pengguna lain di sesi lain (concurrency control).

#### 3.10.1 Preservasi Lock Transaksi Saat Login Baru
- Locker transaksi (`state = 'hold'`, `jumlah = '1'`) **TIDAK BOLEH** dilepas otomatis jika user yang memiliki lock tersebut melakukan login baru di browser/perangkat lain (karena ID user masih sama).
- Pemanggilan `$lls->normalisasiStok()` saat login manual diatur agar default-nya tidak membersihkan hold locker transaksi (`$lls->normalisasiStok(false)`).

#### 3.10.2 Pelepasan Lock Transaksi Secara Sadar (Logout)
- Ketika user secara sadar menekan tombol Logout, seluruh hold locker transaksi milik user tersebut **WAJIB** segera dilepas/dibersihkan.
- Di dalam controller `Login::authLogout()`, panggil `$lls->normalisasiStok(true)` agar lock transaksi yang menggantung langsung dihapus dari database.
- Pembersihan berkala melalui scheduler/cron job otomatis tetap memanggil `normalisasiStok(true)` untuk membersihkan lock dari petugas yang benar-benar tidak aktif.

#### 3.10.3 Mekanisme Ambil Alih Transaksi (Petugas Idle)
- Jika transaksi sedang dikunci oleh user lain, sistem **WAJIB** memblokir akses dan menampilkan halaman interaktif **"Transaksi Terkunci"** dengan informasi petugas aktif (Nama, ID, IP Address, Perangkat, dan Waktu Terakhir Aktif).
- **Syarat Ambil Alih**: Jika petugas aktif terdeteksi tidak aktif (idle) selama **minimal 5 menit**, tampilkan tombol **"Ambil Alih Transaksi (Petugas Idle)"** dengan parameter URL `?forceRetakeLock=1`.
- Jika diklik, sistem melepaskan lock petugas lama (`jumlah = 0` via library `ComLockerTransaksi`) dan melakukan redirect bersih agar user baru dapat memproses transaksi tersebut.
- Jika kurang dari 5 menit, tombol ambil alih wajib di-disable untuk menjaga pekerjaan petugas pertama.

### 3.11 Pola Penyimpanan Draf Transaksi Global (Global Transaction Draft Pattern)
Berlaku untuk seluruh modul transaksi multi-langkah yang memerlukan penyimpanan input sementara pengguna secara berkala (auto-save).

#### 3.11.1 Sentralisasi Penyimpanan Draf (NoSQL MongoDB)
- Penyimpanan draf belanja/transaksi sementara wajib menggunakan MongoDB (bukan tabel relational MySQL transaksional) untuk menghindari beban performa server dan menjaga skema dinamis.
- Endpoint penyimpanan draf diletakkan pada level base controller (`Modul_Controller::saveDraftAjax()`) agar dapat diwarisi dan diakses secara AJAX oleh seluruh sub-controller modul (`_shoppingCart`, `Create`, dll).

#### 3.11.2 Hak Akses & Keamanan Draf (Draft Authorization Check)
- Draf yang disimpan wajib mencantumkan key pemilik draf (`user_id` dan `session_code`).
- Pemulihan (*Restore*) dan Penghapusan (*Delete*) draf hanya diperbolehkan jika pemilik draf adalah pengguna aktif yang sedang login (`user_id === my_id()`). Pengguna lain dilarang keras memulihkan draf milik pengguna lain demi keamanan transaksi.
- Jika terjadi pengambilalihan kunci transaksi resmi (*Retake Lock*) oleh Supervisor setelah petugas pertama idle minimal 5 menit, kepemilikan draf dipindahkan ke Supervisor aktif dan tindakan tersebut dicatat dalam log audit MongoDB secara rinci.

#### 3.11.3 Pembersihan Draf Usang (Cleanup Policy)
- Draf bersifat sementara. Scheduler / Cron job otomatis berhak melakukan pembersihan (*cleanup*) draf yang terdeteksi usang (berumur lebih dari 24 jam tanpa aktivitas perubahan status) demi efisiensi kapasitas database.



## 4. Protokol Pembaruan Aturan & Blueprint (Rule Evolution Protocol)
- Jika Agent mendeteksi adanya pola bug sistemik atau ketidaklengkapan aturan yang berpotensi terjadi di modul lain, Agent **WAJIB** mengusulkan draf penambahan/perbaikan aturan baru pada `AGENTS.md` atau dokumen blueprint terkait (seperti `distribusi.md`) kepada Developer.
- Agent **DILARANG** memperbarui aturan atau blueprint secara otomatis tanpa persetujuan eksplisit dari Developer. Langkah pembaruan berkas aturan hanya boleh dieksekusi setelah Developer memberikan konfirmasi persetujuan ("setuju" atau sejenisnya).


[SYSTEM NOTICE: ACTIVE_PROFILE = LEGACY_PHP_STACK]

Task: Create the initial database entry and stock verification layer for the checkout function.

CRITICAL INSTRUCTIONS FOR GEMINI 3.5 FLASH (LOW):
1. Refer strictly to Hierarchy 1 (agents.md) for output constraints: DO NOT use any code placeholders, shortcuts, or '// ... existing code ...' comments. Write out the function completely.
2. Refer strictly to Hierarchy 2 (stack_profiles.md) for syntax constraints: Ensure 100% compatibility with PHP 5.6. Do NOT use null coalescing operators (??), short array syntax ([]), or arrow functions (fn()=>).
3. Refer strictly to Hierarchy 3 (sales_architecture.md): Enforce the exact 5-step transaction loop using MariaDB 10 row locking ('FOR UPDATE') and standard CodeIgniter 3 Active Record / Raw Queries.

Code to generate:
Write a function named verify_and_lock_invoice_stock($item_id, $requested_qty) inside a CI3 Model class.

OUTPUT FORMAT:
Start your response directly with the markdown code block. Do not include any introductory remarks, greetings, or conversational chatter