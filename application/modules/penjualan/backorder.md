# Blueprint Spesifikasi Fungsional: Multi-Enterprise Backorder Management Cockpit

## 1. Ringkasan Sistem (System Overview)
Modul ini berfungsi sebagai *Control Tower* terpusat untuk memantau, mengelola, dan mengeksekusi pemenuhan pesanan yang tertunda (*backorder*) lintas entitas bisnis (subsidiari), gudang global, dan mitra logistik pihak ketiga (3PL). Sistem menggunakan prinsip data atomik untuk memastikan kalkulasi alokasi inventaris berjalan secara *real-time* dan instan.

## 2. Arsitektur Data & Struktur Atomik
Untuk memastikan kecepatan indeksasi dan performa kueri yang tinggi, data transaksi dipecah menjadi entitas terkecil (Aturan Normalisasi Pertama / 1NF). Sistem dilarang menyimpan data agregat atau data logistik kompleks dalam bentuk kontainer biner (BLOB) pada tabel operasional inti.

### 2.1 Skema Kolom Tabel Utama (Data Atomik)
* `order_id` (VARCHAR) - Kunci Utama / Nomor Dokumen *Sales Order*
* `product_sku` (VARCHAR) - Kode Unik Produk (Struktur Terkecil)
* `enterprise_entity_id` (VARCHAR) - Kode Perusahaan Anak yang Memesan
* `qty_ordered` (INT) - Total Kuantitas yang Dipesan Pelanggan
* `qty_backordered` (INT) - Kuantitas Macet (Kurang)
* `sla_tier` (ENUM) - Tingkat Prioritas Pelanggan (`TIER_1_PLATINUM`, `TIER_2_GOLD`, `TIER_3_NORMAL`)
* `required_delivery_date` (DATE) - Batas Akhir Pengiriman Sesuai Kontrak

---

## 3. Komponen Antarmuka Pengguna (UI Components)

### 3.1 Ringkasan Eksekutif (Global Enterprise KPIs)
Diletakkan pada bagian paling atas halaman menggunakan komponen *Card Metrics* dengan kemampuan *drill-down* per entitas hukum:
* **Total Backorder Value**: Mengalkulasi nilai finansial total dari seluruh `qty_backordered` secara global.
* **Impacted Enterprise Entities**: Jumlah anak perusahaan yang operasionalnya terganggu oleh *backorder*.
* **Aging Backorder Average**: Rata-rata durasi (hari) keterlambatan pesanan dihitung dari `required_delivery_date`.
* **Revenue at Risk**: Total nilai uang dari pesanan yang terancam batal akibat penalti SLA.

### 3.2 Tabel Matriks Alokasi Inventaris Pintar (Smart Allocation Matrix)
Komponen tabel utama yang mendukung fitur pengelompokan (*grouping*) berbasis `product_sku` jika terdapat produk yang sama pada kode pesanan berbeda.

#### Struktur Tampilan Tabel (UI Layout)

| SKU / Produk | Kode Pesanan | Entitas Pemesan | Qty Ordered / Backordered | Rekomendasi Solusi Otomatis (Advanced Cross-Fulfillment) | Qty Available (Source) | Status Eksekusi |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **PROD-CHIP-V2** <br>*(Chip Semikonduktor)* | **ORD-9921** | *PT Elektronika Global* | 10.000 / **5.000** | **Inter-company Transfer**: Ambil surplus stok dari *Subsidiary Tech Corp* di Johor (MY) karena pesanan ini berstatus **SLA Critical (Tier-1)**. | 7.500 unit *(Johor Hub)* | `PENDING_APPROVAL` |
| | **ORD-9925** | *PT Otomotif Perkasa* | 3.000 / **3.000** | **Supplier Drop-ship Direct**: Alihkan rute langsung dari pabrik vendor di Shanghai. Pesanan berstatus **SLA Normal (Tier-2)**. | 3.000 unit *(Pabrik Vendor)* | `IN_PROGRESS_API` |
| **PROD-TURB-X1** <br>*(Komponen Turbin)* | **ORD-8830** | *PT Energi Maju* | 150 / **150** | **Production Re-prioritization**: Naikkan prioritas jadwal produksi Batch #402 di Pabrik Utama Bandung sebesar 2 hari. | 150 unit *(Sched. Batch)* | `APPROVED_MES` |

---

## 4. Logika Bisnis & Fitur Otomasi Otomatis (Backend Rules)

### 4.1 Logika Alokasi Berbasis Prioritas (SLA-Driven Rule)
Sistem dilarang menggunakan metode FIFO (First-In, First-Out) murni saat stok baru tiba. Alokasi wajib mengikuti urutan:
1. `sla_tier = 'TIER_1_PLATINUM'` dengan nilai penalti finansial tertinggi.
2. `aging_backorder` dengan durasi terlama.
3. Kedekatan geografis antara lokasi stok cadangan (`Source`) dengan lokasi pelanggan untuk menekan ongkos kirim.

### 4.2 Faktur Antar-Perusahaan Otomatis (Automated Intercompany Billing)
Ketika aksi **Inter-company Transfer** dieksekusi:
* Sistem secara otomatis menerbitkan dokumen *Intercompany Purchase Order* (PO) dari entitas pemesan ke entitas penyedia stok.
* Sistem mengunci kuantiti pada gudang penyedia (`Qty Available`) agar tidak terjadi duplikasi alokasi (*Anti-Cannibalization*).
* Nilai transfer menggunakan skema harga internal (*Transfer Pricing*) yang telah terindeks di sistem ERP utama.

### 4.3 Logika Pemenuhan Parsial (Partial Fulfillment)
* Jika `Qty Available (Source)` < `qty_backordered`, sistem menawarkan opsi *Split Shipment*.
* Pengiriman parsial hanya diizinkan jika kuantiti minimal memenuhi ambang batas operasional kritis pelanggan (misal: minimal 30% dari total pesanan).

---

## 5. Alur Integrasi Sistem (System Integration Pipeline)
Halaman ini harus terintegrasi secara *real-time* menggunakan RESTful API atau Event-Driven Architecture (Kafka) dengan sistem eksternal berikut:
1. **ERP Core (SAP/Oracle)**: Untuk sinkronisasi status dokumen *Sales Order* dan struktur organisasi multi-enterprise.
2. **WMS (Warehouse Management System)**: Untuk mendapatkan data `Qty Available` yang akurat dari setiap gudang fisik dan 3PL.
3. **MES (Manufacturing Execution System)**: Untuk mengirimkan instruksi re-prioritas jadwal produksi jika solusi yang dipilih adalah *Production Re-prioritization*.
