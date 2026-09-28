# KONTEKS ARSITEKTUR & ANALISIS BUG: STATUS PEMBAYARAN PADA ALUR TRANSAKSI BATAL / REVERT KE SO

Dokumen ini mendokumentasikan konteks analisis akar masalah, mekanisme akuntansi piutang, serta solusi perbaikan terkait kasus anomali status **"LUNAS"** pada transaksi penjualan yang proses lanjutannya (Packing List & Invoice) telah dibatalkan / di-retur sehingga alur operasionalnya kembali ke tahap **Sales Order (SO)**.

---

## 1. Latar Belakang Kasus

### Gejala Anomali
- Pada halaman histori penjualan (`application/modules/penjualan/controllers/History.php`):
  Nomor transaksi **`5822spo.1.4905.10`** (Customer: THEDY, Cabang Pinang) menampilkan status kolom **`LUNAS`** (badge hijau) dengan baris berwarna **Kuning** (*"Transaksi pernah diedit/dirubah"*).
- Pada modal *view resume* (`application/modules/penjualan/controllers/Transaksi.php::viewResumeDetails`):
  Di bagian atas tertera badge **`LUNAS (Melalui A/R Receipt)`**.
- **Kontradiksi Lapangan:** Customer THEDY **sama sekali belum pernah membayar uang**, dan secara riil faktur sebelumnya telah dibatalkan/di-reject, Pre-Order telah direvisi, dan dokumen aktif saat ini telah kembali ke tahap **Sales Order (SO)** yaitu `5822so.1.4905.12`.

---

## 2. Data Aktual Rantai Transaksi di Database

### A. Tabel `transaksi` (Alur Dokumen Multi-Step)
| ID Transaksi | Nomor Transaksi | Jenis / Tahap | Status Data | Keterangan Dokumen |
| :--- | :--- | :--- | :--- | :--- |
| **955840** | `5822spo.1.4905.10` | `5822spo` | `trash = 0`, `trash_4 = 0`, `status_edit = 1` | Pre-Order awal (Pernah Diedit / Baris Kuning) |
| **955858** | `5822so.1.4905.11` | `5822so` | `trash = 0`, **`trash_4 = 1`** | SO awal (**Dibatalkan / Reject**) |
| **956288** | `5822pkd.1.4905.10` | `5822pkd` | `trash = 0`, **`trash_4 = 1`** | Pre-Packing (**Dibatalkan / Reject**) |
| **956290** | `5822spd.1.4905.10` | `5822spd` | `trash = 0`, **`trash_4 = 1`** | Packing List / Invoice (**Dibatalkan / Reject**) |
| **956379** | `4822rj.1.4905.1` | `4822rj` | `trash = 0`, `trash_4 = 1` | Retur Jurnal Penjualan |
| **956381** | `5822pkdrj.1.4905.1` | `5822pkdrj` | `trash = 0`, `trash_4 = 1` | Retur Pre-Packing |
| **956397** | `5822sorj.1.4905.2` | `5822sorj` | `trash = 0`, `trash_4 = 1` | Retur Sales Order |
| **956421** | `5822spoe.1.4905.5` | `5822spoe` | `trash = 0`, `trash_4 = 0` | Dokumen Revisi Pre-Order |
| **956442** | `5822so.1.4905.12` | `5822so` | `trash = 0`, **`trash_4 = 0`**, `status = 1` | **DOKUMEN OPERASIONAL AKTIF SAAT INI (SO)** |

### B. Tabel `transaksi_payment_source` (Kartu Piutang Dagang)
Satu-satunya catatan piutang yang terbit pada alur ini adalah untuk invoice lama `5822spd.1.4905.10` (ID: `956290`):
- `tagihan` = `44.400.000,00`
- `terbayar` = `0,00`
- **`returned` = `44.400.000,00`** *(di-retur penuh karena pembatalan packing list)*
- **`sisa` = `0,00`** *(rumus piutang: `tagihan - terbayar - returned = 0`)*
- `dihapus` = `0`

---

## 3. Analisis Mekanisme Akuntansi: Mengapa `returned` Diisi Saat Pembatalan Packing List?

1. Ketika Packing List (`5822spd`) dibatalkan melalui modul pembatalan / reject:
   - Barang fisik batal dikirim keluar dari gudang.
   - Jurnal penjualan di-reverse via `4822rj`.
   - Piutang dagang sebesar Rp 44.400.000 pada `transaksi_payment_source` **harus dinihilkan** agar customer tidak ditagih di kartu piutang (A/R Ledger).
2. Cara sistem menihilkan piutang adalah dengan mencatat nilai pembatalan ke kolom **`returned`** (`returned = 44.400.000`), sehingga sisa piutang menjadi **`sisa = 0`**.
3. **Mekanisme ini SUDAH BENAR secara akuntansi piutang dan TIDAK BOLEH diubah**, karena jika `returned` tidak diisi, piutang Rp 44.400.000 akan terus menggantung sebagai kewajiban customer yang belum tertagih.

---

## 4. Analisis Akar Masalah Sistem (*Blind Spots*)

### Celah 1: Kueri Controller "Buta" Terhadap Kolom `returned`
Pada `application/modules/penjualan/controllers/History.php`:
- Kueri sebelumnya hanya melakukan:
  `SELECT transaksi_id, tagihan, terbayar, sisa FROM transaksi_payment_source`
- Kolom `returned` tidak dibaca. Helper finansial hanya melihat `tagihan = 44.4jt, terbayar = 0, sisa = 0`. Karena hanya memeriksa kondisi `sisa <= 0`, sistem menyimpulkan transaksi telah **LUNAS**.

### Celah 2: Syarat Pelunasan di Helper Finansial Kurang Ketat
Pada `application/helpers/he_finansial_helper.php::fin_evaluate_payment_status()`:
- Kondisi awal:
  `if ($totSisa <= 0.0 || ($totTerbayar > 0.0 && $totSisa <= $tol))`
- Evaluasi ini tidak memvalidasi apakah `sisa == 0` terjadi karena **uang masuk (`terbayar > 0`)** atau karena **faktur dibatalkan/retur total (`returned >= tagihan`)**.
- Jika `terbayar == 0` dan `sisa == 0`, status seharusnya adalah **`BATAL` / `DIRETUR`**, bukan `LUNAS`.

### Celah 3: Kueri Rantai Transaksi Mengabaikan Status `trash_4`
Pada `History.php` dan `he_finansial_helper.php::fin_get_payment_status()`:
- Kueri pelacakan rantai (`id_top = $allTopIDs`) sebelumnya hanya mengecek:
  `$this->db->where("trash", 0);`
- Di sistem Everest:
  - `trash = 0`: Tempat sampah biasa (soft-delete).
  - `trash_4 = 1`: **Transaksi dibatalkan / reject oleh alur revisi**.
- Tanpa filter `trash_4 = 0`, faktur mati `956290` tetap terseret dan dihubungkan ke SO baru `956442`.

### Celah 4: Modal Resume Mengirimkan Seluruh Riwayat Entry Points
Pada `application/modules/penjualan/controllers/Transaksi.php::viewResumeDetails()`:
- Variabel `$searchIDs` mengumpulkan seluruh ID yang muncul di tabel riwayat:
  `$searchIDs = array(955840, 955858, 956288, 956290, 956442)`
- ID faktur yang dibatalkan (`956290`) ikut dikirim ke `fin_get_payment_status()`.
- Karena membaca kartu piutang milik `956290` (`returned = 44.4 jt`), helper mengembalikan status `is_batal = true`.
- Kondisi ini mencegat alur sebelum sistem dapat menetapkan badge yang semestinya:
  **`BELUM MENERIMA PELUNASAN (Tahap Pesanan SO)`**.

---

## 5. Rincian Perbaikan yang Telah Diterapkan

### 1. `application/helpers/he_finansial_helper.php`
- **Pengecekan Pembayaran Riil:**
  Status `LUNAS` kini wajib memiliki pembayaran riil:
  `if ($totTerbayar > 0.0 && ($totSisa <= 0.0 || $totSisa <= $tol))`
- **Penanganan Status Batal/Retur:**
  Jika tagihan > 0, uang terbayar = 0, dan sisa <= 0 (retur penuh), status ditetapkan sebagai `BATAL` dengan badge `⚪ BATAL/RETUR`.
- **Kueri Rantai Steril Dokumen Reject:**
  Pada fungsi `fin_get_payment_status()`, kueri pelacakan alur rantai (`$qChain` dan `$qSiblings`) ditambahkan:
  `$CI->db->where("trash_4", 0);`
  Serta memilih kolom `returned` dari `transaksi_payment_source`.

### 2. `application/modules/penjualan/controllers/History.php`
- Pada kueri `$chainTrans`:
  Ditambahkan `$this->db->where("trash_4", 0);` agar invoice yang sudah di-reject (`trash_4 = 1`) tidak masuk ke daftar transaksi aktif.
- Pada kueri `transaksi_payment_source`:
  Mengambil kolom `returned` dan meneruskannya ke perhitungan status pembayaran.
- **Hasil:** Baris `5822spo.1.4905.10` di tabel histori berubah dari `🟢 LUNAS` menjadi **`🟡 BELUM LUNAS`**.

### 3. `application/modules/penjualan/controllers/Transaksi.php`
- Pada modal `viewResumeDetails()`:
  - Dokumen yang berstatus reject (`trash_4 == 1` atau terdaftar di `$epDetail`) tidak lagi mengaktifkan flag `$hasInvoice` atau `$hasPackingList`.
  - Teks `LUNAS (Melalui A/R Receipt)` hanya muncul jika benar-benar ada bukti penerimaan A/R Receipt (`$hasArPayment == true`).

---

## 6. Langkah Penyempurnaan untuk Modal Resume (`viewResumeDetails`)

Agar modal resume untuk alur transaksi yang kembali ke SO menampilkan secara presisi:
> **`🟡 BELUM MENERIMA PELUNASAN (Tahap Pesanan SO)`**

Penyesuaian yang perlu diterapkan pada `application/modules/penjualan/controllers/Transaksi.php` sebelum memanggil `fin_get_payment_status($searchIDs)`:

```php
// Pastikan $searchIDs hanya berisi ID transaksi yang aktif (tidak reject/batal)
if (sizeof($searchIDs) > 0) {
    $qActiveIds = $this->db->select("id")
        ->from("transaksi")
        ->where_in("id", $searchIDs)
        ->where("trash", 0)
        ->where("trash_4", 0)
        ->get()->result();
    $cleanActiveSearchIDs = array();
    if (sizeof($qActiveIds) > 0) {
        foreach ($qActiveIds as $actRow) {
            $cleanActiveSearchIDs[] = intval($actRow->id);
        }
    }
    $searchIDs = $cleanActiveSearchIDs;
}
```

Serta pada `application/helpers/he_finansial_helper.php::fin_get_payment_status()`:
Memastikan parameter masukan `$cleanIDs` juga memvalidasi `trash_4 = 0` terhadap tabel `transaksi`.
