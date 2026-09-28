# DOKUMENTASI KONTEKS & ANALISIS DASHBOARD MASTER PROJECT
**Modul Target:** `master_project` (`Transaksi.php`, `views/transaksi.php`)
**Waktu Penyimpanan:** 2026-08-27
**Status:** Siap dilanjutkan setelah perpindahan workspace

---

## 1. RIWAYAT PERBAIKAN SEBELUMNYA (SUDAH SELESAI & TERVERIFIKASI)

### A. Masalah Progress 151% & Double Counting di Ringkasan Proyek
- **Gejala:**
  - Cabang Surabaya: Total Proyek = 18, Selesai = 5, Dalam Proses = 8, Tertunda = 10, Progress = 151%.
  - Cabang Pinang: Total Proyek = 151, Selesai = 97, Dalam Proses = 29, Tertunda = 53, Progress = 86%.
  - Penjumlahan kartu tidak klop ($5 + 8 + 10 = 23 \neq 18$ di Surabaya, $97 + 29 + 53 = 179 \neq 151$ di Pinang).
- **Akar Masalah:**
  - Di frontend [`views/transaksi.php`](file:///z:/everest/application/modules/master_project/views/transaksi.php): tidak ada *capping* $\le 100\%$ pada `parseFloat(project.persen_progress)` dan rata-rata. Kondisi `delayed++` terpicu dua kali (jika progress=0 dan deadline lewat).
  - Di database: ada data `persen_progress` proyek yang bernilai ratusan persen akibat pembagian biaya vs harga jual atau akumulasi inkremental berulang di `MasterData.php`.
- **Solusi yang Sudah Diterapkan (Opsi A - Frontend Fix):**
  - Menerapkan `Math.min(100, Math.max(0, rawProgress))` per proyek dan rata-rata keseluruhan.
  - Memperbaiki partisi status *mutually exclusive*:
    - **Selesai**: `progress >= 100`
    - **Tertunda / Terlambat**: `progress === 0 || isOverdue`
    - **Dalam Proses**: `progress > 0 && progress < 100 && !isOverdue`
  - Hasil di Surabaya menjadi konsisten: **Total 18 = Selesai 13 + Proses 0 + Tertunda 5, Progress = 72%**.

---

## 2. ANALISIS RINGKASAN TASKLIST (TOPIK AKTIF SAAT INI)

### A. Gejala Permasalahan
- Pada antarmuka Cabang Surabaya (Login: Nurul), kartu **Ringkasan Tasklist** menampilkan nilai serba 0 (`Total: 0, Selesai: 0, Proses: 0, Terlambat: 0, Tertunda: 0, Batal: 0, Progress: 0%`).
- Padahal di bawahnya pada tabel **Tasklist Menunggu Proses QC** terdapat daftar tugas aktif dengan Petugas `NURUL`.

### B. Akar Masalah di Backend ([`master_project/controllers/Transaksi.php:L4448-L4486`](file:///z:/everest/application/modules/master_project/controllers/Transaksi.php#L4448-L4486))
1. **Query Terkunci ke `employee_id = login['id']`:**
   ```php
   $tmpTasklist->addFilter("employee_id=" . $this->session->login['id']);
   $this->db->where("cabang_id", $this->session->login['cabang_id']);
   $arrTask = $tmpTasklist->lookupAll()->result();
   ```
   Karena Nurul bertindak sebagai pembuat SPK / Manajemen / PIC dan bukan sebagai teknisi pelaksana yang dipilih di SPK, kueri menghasilkan 0 baris data.
2. **Inkonsistensi Scope:**
   Ringkasan Proyek menampilkan seluruh cabang (18 proyek), tetapi Ringkasan Tasklist di-hardcode personal.
3. **Pengabaian SPK Tambahan:**
   Hanya membaca `MdlTasklistProject` (`project_tasklist`), mengabaikan `MdlTasklistProjectTambahan` (`project_tasklist_tambahan`).

### C. Keputusan Aturan Bisnis dari User (Firm Requirement)
> **"Jika login memiliki `in_array('o_project_spv', $mems) || in_array('c_holding', $mems) || in_array('c_owner', $mems) || in_array('o_project_mgr', $mems)` maka task list muncul untuk seluruh cabang. Di luar itu hanya punya dia sendiri."**

---

## 3. RANCANGAN IMPLEMENTASI YANG AKAN DIEKSEKUSI

### File Target: [`master_project/controllers/Transaksi.php`](file:///z:/everest/application/modules/master_project/controllers/Transaksi.php) (sekitar baris 4445 - 4486)

```php
$isManagement = in_array("o_project_spv", $mems) || in_array("c_holding", $mems) || in_array("c_owner", $mems) || in_array("o_project_mgr", $mems);

if (!$isManagement) {
    // 1. PELAKSANA (PERSONAL / TEKNISI BIASA)
    // Ambil tasklist personal (Utama & Tambahan)
    $tmpTasklist->setFilters(array());
    $tmpTasklist->addFilter("employee_id=" . $this->session->login['id']);
    $this->db->where("cabang_id", $this->session->login['cabang_id']);
    $arrTaskMain = $tmpTasklist->lookupAll()->result();

    $this->load->model('Mdls/MdlTasklistProjectTambahan');
    $tmpTasklistTmb = new MdlTasklistProjectTambahan();
    $tmpTasklistTmb->setFilters(array());
    $tmpTasklistTmb->addFilter("employee_id=" . $this->session->login['id']);
    $this->db->where("cabang_id", $this->session->login['cabang_id']);
    $arrTaskTmb = $tmpTasklistTmb->lookupAll()->result();

    $arrTask = array_merge($arrTaskMain, $arrTaskTmb);

    // Kunci hak akses proyek hanya yang ditugaskan ke dirinya
    $vTask = array();
    foreach ($arrTask as $dts) {
        if ($dts->status == 1) $vTask[$dts->produk_id] = $dts->produk_id;
    }
    if (!empty($vTask)) {
        $this->db->where_in("id", $vTask);
    } else {
        $this->db->where("id", "-99999");
    }
    $this->db->where("closing_status", 0);
    $tmpDataProject = $pr->fectDataProject()->result();

} else {
    // 2. TINGKAT MANAJEMEN (CABANG)
    // Ambil seluruh proyek aktif di cabang
    $this->db->where("closing_status", 0);
    $this->db->where("cabang_id", $this->session->login['cabang_id']);
    $tmpDataProject = $pr->fectDataProject()->result();

    $activeProjectIds = array();
    foreach ($tmpDataProject as $p) {
        $activeProjectIds[] = $p->id;
    }

    if (!empty($activeProjectIds)) {
        // Ambil seluruh tasklist (Utama & Tambahan) dari proyek aktif tersebut
        $tmpTasklist->setFilters(array());
        $this->db->where_in("produk_id", $activeProjectIds);
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $arrTaskMain = $tmpTasklist->lookupAll()->result();

        $this->load->model('Mdls/MdlTasklistProjectTambahan');
        $tmpTasklistTmb = new MdlTasklistProjectTambahan();
        $tmpTasklistTmb->setFilters(array());
        $this->db->where_in("produk_id", $activeProjectIds);
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $arrTaskTmb = $tmpTasklistTmb->lookupAll()->result();

        $arrTask = array_merge($arrTaskMain, $arrTaskTmb);
    } else {
        $arrTask = array();
    }
}
```

---

## 4. CHECKLIST VERIFIKASI SETELAH EKSEKUSI DI WORKSPACE TUJUAN
- [ ] Login sebagai Nurul di Cabang Surabaya:
  - Ringkasan Proyek menampilkan 18 proyek.
  - Ringkasan Tasklist menampilkan akumulasi tasklist dari 18 proyek tersebut (angka tidak lagi 0).
- [ ] Login sebagai teknisi biasa:
  - Ringkasan Proyek & Tasklist hanya menampilkan data penugasan miliknya sendiri.
- [ ] Tidak ada regresi / gangguan pada tabel AJAX di bawahnya (`#qcProject`, `#undoneList`).
