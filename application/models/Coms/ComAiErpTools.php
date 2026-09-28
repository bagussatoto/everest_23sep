<?php
// START OF COMPLETE REPEATED LOGIC

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Model Domain ComAiErpTools
 * 
 * Bertanggung jawab menyediakan data faktual (Ground Truth Data) dari database Everest ERP
 * untuk disintesis oleh LLM Assistant dengan penerapan ketat Role-Based Access Control (RBAC)
 * dan kepatuhan terhadap standar ISO/IEC 25010 & AGENTS.md Pasal 3.13 (Anti-Overselling / Locker Stock)
 * serta Pasal 3.14 (Strict No-Global-State di Model).
 */
class ComAiErpTools extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        if (isset($this->load)) {
            $ci = &get_instance();
            if (isset($ci->db_ai_replica) && is_object($ci->db_ai_replica)) {
                $this->db = $ci->db_ai_replica;
            } else {
                // Baca konfigurasi ai_chat untuk menentukan penggunaan server database replika
                $ci->load->config('ai_chat', TRUE);
                $aiConfig = $ci->config->item('ai_chat');

                $useReplica = isset($aiConfig['use_db_replica']) ? (bool)$aiConfig['use_db_replica'] : true;
                $replicaGroup = !empty($aiConfig['replica_db_group']) ? $aiConfig['replica_db_group'] : 'ai_replica';

                if ($useReplica) {
                    $replicaDb = null;
                    try {
                        $replicaDb = $this->load->database($replicaGroup, TRUE);
                    } catch (Exception $e) {
                        $replicaDb = null;
                    }

                    if ($replicaDb && is_object($replicaDb) && isset($replicaDb->conn_id) && $replicaDb->conn_id) {
                        $this->db = $replicaDb;
                        $ci->db_ai_replica = $replicaDb;
                    } else {
                        // Fallback otomatis ke database default jika replika tidak aktif / offline
                        $this->load->database();
                    }
                } else {
                    // Jika instalasi perusahaan hanya memiliki 1 server database tunggal (use_db_replica = false)
                    $this->load->database();
                }
            }
        }
    }

    /**
     * Memeriksa apakah tipe pengguna termasuk level Eksekutif (Direksi / Owner / Super Admin).
     * 
     * @param string $userJenis
     * @return bool
     */
    public function isExecutive($userJenis)
    {
        $execRoles = array('superman', 'admin', 'super_admin', 'administrator', 'direksi', 'owner', 'c_level', 'komisaris');
        return in_array(strtolower(trim($userJenis)), $execRoles);
    }

    /**
     * Memeriksa apakah tipe pengguna termasuk level Supervisor / Kepala Bagian.
     * 
     * @param string $userJenis
     * @return bool
     */
    public function isSupervisor($userJenis)
    {
        $spvRoles = array('spv_penj', 'spv', 'supervisor', 'head', 'manager', 'kabag', 'accounting', 'finance', 'audit', 'keuangan');
        return in_array(strtolower(trim($userJenis)), $spvRoles) || $this->isExecutive($userJenis);
    }

    /**
     * Memeriksa apakah tipe pengguna memiliki wewenang mengelola proyek (Project Manager / Site Supervisor / Eksekutif).
     * 
     * @param string $userJenis
     * @return bool
     */
    public function isProjectRole($userJenis)
    {
        $projRoles = array('pm', 'project', 'project_manager', 'site_manager', 'spv_project', 'teknisi_project', 'accounting', 'finance', 'audit', 'keuangan', 'staff', 'teknisi');
        return in_array(strtolower(trim($userJenis)), $projRoles) || $this->isSupervisor($userJenis);
    }

    /**
     * Mendeteksi maksud/intent pengguna dari pertanyaan teks dan mengekstrak kata kunci pencarian.
     * 
     * @param string $message
     * @return array
     */
    public function detectUserIntent($message, $refTransaksi = null)
    {
        $msgLower = strtolower($message);

        // 0. Deteksi Intent Konteks Transaksi Aktif (Context-Aware via ref_transaksi atau keyword 'transaksi ini')
        $txContextKeywords = array('transaksi ini', 'draf ini', 'order ini', 'pesanan ini', 'faktur ini', 'dokumen ini', 'cek order', 'cek pesanan', 'evaluasi draf', 'analisis transaksi');
        $hasContextMention = false;
        foreach ($txContextKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                $hasContextMention = true;
                break;
            }
        }
        if (!empty($refTransaksi) && ($hasContextMention || strpos($msgLower, 'transaksi') !== false || strpos($msgLower, 'apakah') !== false || strpos($msgLower, 'margin') !== false)) {
            return array(
                'intent' => 'active_transaction_context',
                'keyword' => $refTransaksi
            );
        }

        // 1. Deteksi Intent Cek Plafon & Kredit Konsumen (Customer Credit & Overdue Guard)
        $creditKeywords = array('plafon', 'kredit customer', 'kredit konsumen', 'limit customer', 'limit kredit', 'piutang customer', 'piutang konsumen', 'boleh tempo', 'boleh kredit', 'status kredit', 'cek kredit', 'plafon customer');
        foreach ($creditKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                $cleanQuery = preg_replace('/(apakah|ada|plafon|kredit|customer|konsumen|limit|piutang|boleh|tempo|status|cek|tampilkan|sisa|berapa|rekap|toko|pt|cv|\?|\!)/i', ' ', $message);
                $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
                return array(
                    'intent' => 'customer_credit_check',
                    'keyword' => $cleanQuery
                );
            }
        }

        // 2. Deteksi Intent Stok Macet / Slow-Moving & Dead-Stock (Inventory Aging)
        $slowKeywords = array('slow moving', 'slow-moving', 'dead stock', 'dead-stock', 'stok macet', 'barang macet', 'tidak bergerak', 'tidak laku', 'stok mengendap', 'cuci gudang', 'umur barang', 'aging stock', 'stok tua');
        foreach ($slowKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'slow_moving_stock',
                    'keyword' => ''
                );
            }
        }

        // 3. Deteksi Intent SPK / Work Order Lapangan Proyek
        $spkKeywords = array('spk', 'work order', 'pre spk', 'mandor', 'pelaksana', 'tasklist proyek', 'tugas proyek');
        foreach ($spkKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                $cleanQuery = preg_replace('/(apakah|ada|spk|work|order|pre|mandor|pelaksana|tugas|tasklist|lapangan|proyek|project|tolong|cek|tampilkan|daftar|status|yang|masih|tertunda|pending|di|semua|seluruh|rekap|\?|\!)/i', ' ', $message);
                $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
                return array(
                    'intent' => 'project_spk_status',
                    'keyword' => $cleanQuery
                );
            }
        }

        // 4. Deteksi Intent Piutang Termin Proyek (Customer Project Receivables)
        $terminKeywords = array('termin proyek', 'piutang proyek', 'tagihan proyek', 'termin', 'invoicing proyek', 'billing proyek');
        foreach ($terminKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                $cleanQuery = preg_replace('/(apakah|ada|termin|piutang|tagihan|invoicing|billing|proyek|project|customer|konsumen|tolong|cek|tampilkan|sisa|berapa|rekap|per|seluruh|semua|daftar|ikhtisar|\?|\!)/i', ' ', $message);
                $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
                return array(
                    'intent' => 'project_termin_ar',
                    'keyword' => $cleanQuery
                );
            }
        }

        // 5. Deteksi Intent Analisis Biaya, Laba & Serapan RAB Proyek
        $projKeywords = array('proyek', 'project', 'rab', 'hpp proyek', 'budget proyek', 'margin proyek', 'laba proyek', 'kontrak proyek', 'burn rate', 'over budget');
        foreach ($projKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                $cleanQuery = preg_replace('/(apakah|ada|proyek|project|rab|hpp|budget|margin|laba|kontrak|burn|rate|over|serapan|biaya|tolong|cek|tampilkan|bagaimana|status|portofolio|ikhtisar|rekap|seluruh|semua|daftar|overview|dan|kotor|bersih|\?|\!)/i', ' ', $message);
                $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
                return array(
                    'intent' => 'project_status',
                    'keyword' => $cleanQuery
                );
            }
        }

        // 6. Deteksi Intent Posisi Likuiditas Kas & Bank Eksekutif
        $cashKeywords = array('kas bank', 'kas dan bank', 'saldo kas', 'saldo bank', 'posisi kas', 'posisi bank', 'likuiditas', 'dana tunai', 'petty cash', 'rekening koran');
        foreach ($cashKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'cash_bank_liquidity',
                    'keyword' => ''
                );
            }
        }

        // 7. Deteksi Intent Working Capital & Komparasi Piutang vs Hutang
        $arApKeywords = array('piutang vs hutang', 'hutang piutang', 'piutang dan hutang', 'working capital', 'modal kerja', 'arus kas', 'hutang supplier', 'jatuh tempo hutang');
        foreach ($arApKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'ar_ap_working_capital',
                    'keyword' => ''
                );
            }
        }

        // 8. Deteksi Intent Peringatan Stok Kritis / Reorder Point (Purchasing & Warehouse)
        $lowStockKeywords = array('stok kritis', 'stok menipis', 'barang habis', 'reorder', 'moq', 'perlu order', 'rop', 'minimum stok', 'kritis');
        foreach ($lowStockKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'low_stock_reorder',
                    'keyword' => ''
                );
            }
        }

        // 9. Deteksi Intent Eksekutif / Finansial Global
        $execKeywords = array('laba rugi', 'profit', 'laba bersih', 'laba kotor', 'omset konsolidasi', 'performa cabang', 'kinerja keuangan', 'executive summary', 'ikhtisar eksekutif');
        foreach ($execKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'executive_overview',
                    'keyword' => ''
                );
            }
        }

        // 10. Deteksi Intent Rekap Transaksi Divisi
        $divKeywords = array('rekap penjualan', 'rekap order', 'pesanan pending', 'antrean po', 'antrean so', 'rekap transaksi', 'transaksi hari ini');
        foreach ($divKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                return array(
                    'intent' => 'division_summary',
                    'keyword' => ''
                );
            }
        }

        // 11. Deteksi Intent Cek Stok / Ketersediaan Barang
        $stockKeywords = array('stok', 'stock', 'sisa barang', 'ada barang', 'cek barang', 'cek produk', 'ketersediaan', 'berapa unit');
        foreach ($stockKeywords as $kw) {
            if (strpos($msgLower, $kw) !== false) {
                // Bersihkan query dari kata tanya umum untuk mengambil nama/kode barang
                $cleanQuery = preg_replace('/(apakah|ada|stok|stock|barang|produk|item|sisa|ketersediaan|tolong|cek|berapa|unit|pcs|gudang|cabang|kita|di|masih|untuk|nya|\?|\!)/i', ' ', $message);
                $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));

                return array(
                    'intent' => 'check_stock',
                    'keyword' => $cleanQuery
                );
            }
        }

        return array(
            'intent' => 'general_chat',
            'keyword' => ''
        );
    }

    /**
     * Mencari ketersediaan stok produk secara deterministik di database.
     * Menerapkan perhitungan Aturan 3.13: Stok Efektif = Saldo Riil - Saldo Locker Aktif (hold).
     * Serta mengunci informasi HPP/modal beli bagi non-eksekutif.
     * 
     * @param string $keyword
     * @param int $cabangId
     * @param string $userJenis
     * @param int|null $gudangId
     * @return array
     */
    public function searchProductStock($keyword, $cabangId, $userJenis, $gudangId = null)
    {
        $isExec = $this->isExecutive($userJenis);
        $cleanKeyword = trim(strip_tags($keyword));

        if (empty($cleanKeyword)) {
            return array(
                'status' => 'empty_query',
                'message' => 'Kata kunci nama atau kode barang tidak ditemukan dalam pertanyaan.',
                'data' => array()
            );
        }

        // 1. Cari produk berdasarkan kode, barcode, atau nama
        $sqlProduk = "SELECT id, kode, barcode, nama, kategori_nama, satuan, cabang_id 
                      FROM produk 
                      WHERE (nama LIKE ? OR kode LIKE ? OR barcode LIKE ?) 
                        AND status = '1' AND trash = '0' 
                      LIMIT 5";
        $likePattern = '%' . $cleanKeyword . '%';
        $queryProduk = $this->db->query($sqlProduk, array($likePattern, $likePattern, $likePattern));
        $products = $queryProduk->result_array();

        if (empty($products)) {
            return array(
                'status' => 'not_found',
                'message' => "Tidak ditemukan produk dengan kata kunci '{$cleanKeyword}' di database.",
                'data' => array()
            );
        }

        $results = array();

        foreach ($products as $p) {
            $pId = (int)$p['id'];

            // 2. Ambil saldo fisik riil terkini dari cache rekening pembantu produk (hanya saldo terakhir per cabang)
            $sqlSaldo = "SELECT c1.cabang_id, c1.gudang_id, c1.saldo_qty_debet, c1.saldo_qty_kredit, 
                                (c1.saldo_qty_debet - c1.saldo_qty_kredit) as sisa_qty, 
                                c1.harga, c1.harga_avg, c1.dtime 
                         FROM _rek_pembantu_produk_cache c1
                         INNER JOIN (
                             SELECT MAX(id) as max_id 
                             FROM _rek_pembantu_produk_cache 
                             WHERE extern_id = ? " . ((!$isExec && $cabangId > 0) ? " AND cabang_id = ? " : "") . "
                             GROUP BY cabang_id
                         ) c2 ON c1.id = c2.max_id ";
            $paramsSaldo = array($pId);

            if (!$isExec && $cabangId > 0) {
                $paramsSaldo[] = (int)$cabangId;
            }

            $sqlSaldo .= " ORDER BY c1.cabang_id ASC";
            $querySaldo = $this->db->query($sqlSaldo, $paramsSaldo);
            $saldoRows = $querySaldo->result_array();

            // 3. Ambil total hold dari stock_locker
            $sqlLocker = "SELECT cabang_id, SUM(jumlah) as total_hold 
                          FROM stock_locker 
                          WHERE produk_id = ? AND state = 'hold' ";
            $paramsLocker = array($pId);

            if (!$isExec && $cabangId > 0) {
                $sqlLocker .= " AND cabang_id = ? ";
                $paramsLocker[] = (int)$cabangId;
            }

            $sqlLocker .= " GROUP BY cabang_id";
            $queryLocker = $this->db->query($sqlLocker, $paramsLocker);
            $lockerRows = $queryLocker->result_array();

            $holdPerCabang = array();
            foreach ($lockerRows as $lr) {
                $holdPerCabang[$lr['cabang_id']] = (int)$lr['total_hold'];
            }

            // Rangkum per cabang
            $stokPerLokasi = array();
            if (!empty($saldoRows)) {
                foreach ($saldoRows as $sr) {
                    $cId = (int)$sr['cabang_id'];
                    $sisaFisik = (int)$sr['sisa_qty'];
                    $hold = isset($holdPerCabang[$cId]) ? $holdPerCabang[$cId] : 0;
                    $stokTersedia = max(0, $sisaFisik - $hold);

                    // Ambil nama cabang
                    $cNama = "Pusat / Global";
                    if ($cId > 0) {
                        $qCab = $this->db->query("SELECT nama FROM per_cabang WHERE id = ? LIMIT 1", array($cId));
                        if ($qCab->num_rows() > 0) {
                            $cNama = $qCab->row()->nama;
                        }
                    }

                    $lokasiItem = array(
                        'cabang_id' => $cId,
                        'cabang_nama' => $cNama,
                        'stok_fisik' => $sisaFisik,
                        'stok_di_hold' => $hold,
                        'stok_tersedia' => $stokTersedia,
                    );

                    // Proteksi RBAC: HPP hanya disertakan untuk Eksekutif
                    if ($isExec) {
                        $lokasiItem['hpp_modal'] = (float)$sr['harga'];
                        $lokasiItem['nilai_persediaan_rp'] = (float)$sr['harga'] * $stokTersedia;
                    } else {
                        $lokasiItem['hpp_modal'] = 'RAHASIA (Hanya Eksekutif)';
                    }

                    $stokPerLokasi[] = $lokasiItem;
                }
            } else {
                $stokPerLokasi[] = array(
                    'cabang_id' => $cabangId,
                    'cabang_nama' => 'Cabang Anda',
                    'stok_fisik' => 0,
                    'stok_di_hold' => 0,
                    'stok_tersedia' => 0,
                    'keterangan' => 'Belum ada mutasi masuk / stok kosong'
                );
            }

            $results[] = array(
                'produk_id' => $pId,
                'kode' => $p['kode'],
                'barcode' => $p['barcode'],
                'nama' => $p['nama'],
                'kategori' => $p['kategori_nama'],
                'satuan' => !empty($p['satuan']) ? $p['satuan'] : 'Unit',
                'lokasi_stok' => $stokPerLokasi
            );
        }

        return array(
            'status' => 'success',
            'query' => $cleanKeyword,
            'is_executive' => $isExec,
            'data' => $results
        );
    }

    /**
     * Mengambil ringkasan transaksi divisi untuk level Supervisor / Kepala Bagian.
     * 
     * @param string $divisi
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getDivisionTransactionsSummary($divisi, $cabangId, $userJenis)
    {
        $isSpv = $this->isSupervisor($userJenis);
        $thn = date('Y');
        $bln = date('m');
        $tgl = date('d');

        // Query penjualan hari ini dan bulan ini dari _rek_pembantu_penjualan_cache
        $sqlOmset = "SELECT cabang_id, 
                            SUM(CASE WHEN tgl = ? THEN (kredit - debet) ELSE 0 END) as omset_hari_ini,
                            SUM(kredit - debet) as omset_bulan_ini,
                            COUNT(*) as total_transaksi
                     FROM _rek_pembantu_penjualan_cache 
                     WHERE thn = ? AND bln = ? ";
        $params = array($tgl, $thn, $bln);

        if (!$this->isExecutive($userJenis) && $cabangId > 0) {
            $sqlOmset .= " AND cabang_id = ? ";
            $params[] = (int)$cabangId;
        }

        $sqlOmset .= " GROUP BY cabang_id";
        $q = $this->db->query($sqlOmset, $params);
        $rows = $q->result_array();

        $summary = array();
        foreach ($rows as $r) {
            $cNama = 'Pusat';
            $cId = (int)$r['cabang_id'];
            if ($cId > 0) {
                $qc = $this->db->query("SELECT nama FROM per_cabang WHERE id = ? LIMIT 1", array($cId));
                if ($qc->num_rows() > 0) {
                    $cNama = $qc->row()->nama;
                }
            }

            $summary[] = array(
                'cabang_id' => $cId,
                'cabang_nama' => $cNama,
                'omset_hari_ini' => (float)$r['omset_hari_ini'],
                'omset_bulan_ini' => (float)$r['omset_bulan_ini'],
                'total_mutasi' => (int)$r['total_transaksi']
            );
        }

        return array(
            'status' => 'success',
            'periode' => "Bulan {$bln}-{$thn}, Tanggal {$tgl}",
            'is_supervisor' => $isSpv,
            'data' => $summary
        );
    }

    /**
     * Mengambil ikhtisar finansial dan performa cabang untuk level Eksekutif.
     * Menerapkan pembatasan izin ketat (Strict Authorization Guard).
     * 
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getExecutiveOverview($cabangId, $userJenis)
    {
        // 1. STRICT ACCESS GUARD: Tolak jika bukan Eksekutif
        if (!$this->isExecutive($userJenis)) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Laporan kinerja eksekutif, laba rugi konsolidasi, dan analisis keuangan strategis hanya dapat diakses oleh Direksi, Owner, atau Super Admin.'
            );
        }

        $thn = date('Y');
        $bln = date('m');
        $tgl = date('d');

        // 2. Query Total Omset Konsolidasi Bulan Berjalan
        $sqlKonsolidasi = "SELECT cabang_id, 
                                  SUM(kredit - debet) as omset_bulan_ini,
                                  SUM(CASE WHEN tgl = ? THEN (kredit - debet) ELSE 0 END) as omset_hari_ini
                           FROM _rek_pembantu_penjualan_cache 
                           WHERE thn = ? AND bln = ? 
                           GROUP BY cabang_id 
                           ORDER BY omset_bulan_ini DESC";
        $q = $this->db->query($sqlKonsolidasi, array($tgl, $thn, $bln));
        $cabangSales = $q->result_array();

        $totalOmsetBulan = 0;
        $totalOmsetHari = 0;
        $performaCabang = array();

        foreach ($cabangSales as $cs) {
            $cId = (int)$cs['cabang_id'];
            $cNama = 'Kantor Pusat';
            if ($cId > 0) {
                $qc = $this->db->query("SELECT nama FROM per_cabang WHERE id = ? LIMIT 1", array($cId));
                if ($qc->num_rows() > 0) {
                    $cNama = $qc->row()->nama;
                }
            }

            $omsetBulan = (float)$cs['omset_bulan_ini'];
            $omsetHari = (float)$cs['omset_hari_ini'];

            $totalOmsetBulan += $omsetBulan;
            $totalOmsetHari += $omsetHari;

            $performaCabang[] = array(
                'cabang_id' => $cId,
                'cabang_nama' => $cNama,
                'omset_bulan_ini_rp' => $omsetBulan,
                'omset_hari_ini_rp' => $omsetHari
            );
        }

        return array(
            'status' => 'success',
            'periode' => date('F Y'),
            'total_omset_konsolidasi_rp' => $totalOmsetBulan,
            'total_omset_hari_ini_rp' => $totalOmsetHari,
            'performa_cabang' => $performaCabang,
            'catatan_analitis' => 'Data real-time dari buku besar pembantu penjualan Everest ERP.'
        );
    }

    /**
     * Mengambil portofolio proyek lengkap (Project Portfolio Overview) untuk Eksekutif dan Project Manager.
     * Mengagregasi Nilai Kontrak, Target RAB, Realisasi HPP Riil, Gross Profit, dan Status Burn Rate.
     * 
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getProjectPortfolioOverview($cabangId, $userJenis)
    {
        $isExec = $this->isExecutive($userJenis);
        $isProj = $this->isProjectRole($userJenis);

        // RBAC Guard: Hanya Eksekutif & Manajemen Proyek
        if (!$isExec && !$isProj) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Portofolio kontrak proyek, rincian biaya HPP, dan margin laba proyek hanya dapat diakses oleh Manajemen Proyek atau Eksekutif.'
            );
        }

        // Ambil proyek aktif (trash = 0) dengan nilai kontrak > 0
        $sql = "SELECT p.id, p.kode, p.nama, p.customer_nama, p.nomor_kontrak, 
                       p.harga as nilai_kontrak, 
                       p.harga_hpp_so as budget_rab, 
                       p.persen_progress, p.start_dtime, p.end_dtime, p.closing_status,
                       COALESCE(h.total_hpp, 0) as hpp_riil,
                       COALESCE(t.total_spk, 0) as total_spk
                FROM project_produk p
                LEFT JOIN (
                    SELECT extern_id, SUM(debet - kredit) as total_hpp
                    FROM _rek_pembantu_hpp_project_cache
                    GROUP BY extern_id
                ) h ON p.id = h.extern_id
                LEFT JOIN (
                    SELECT produk_id, COUNT(*) as total_spk
                    FROM project_tasklist
                    WHERE trash = 0
                    GROUP BY produk_id
                ) t ON p.id = t.produk_id
                WHERE p.trash = 0 AND p.harga > 0 ";

        $params = array();
        if (!$isExec && $cabangId > 0) {
            $sql .= " AND p.cabang_id = ? ";
            $params[] = (int)$cabangId;
        }

        $sql .= " ORDER BY p.id DESC LIMIT 15";
        $query = $this->db->query($sql, $params);
        $rows = $query->result_array();

        $totalKontrakPortofolio = 0;
        $totalHppRiilPortofolio = 0;
        $totalRabPortofolio = 0;
        $proyekList = array();

        foreach ($rows as $r) {
            $kontrak = (float)$r['nilai_kontrak'];
            $hppRiil = (float)$r['hpp_riil'];
            $rab = (float)$r['budget_rab'] > 0 ? (float)$r['budget_rab'] : 0;
            $grossProfit = $kontrak - $hppRiil;
            $grossMarginPct = $kontrak > 0 ? ($grossProfit / $kontrak) * 100 : 0;
            $burnRatePct = $rab > 0 ? ($hppRiil / $rab) * 100 : 0;

            // Penentuan Status Peringatan Biaya (Early Warning System)
            $statusBiaya = '🟢 Aman';
            if ($burnRatePct > 95) {
                $statusBiaya = '🔴 Over-Budget / Kritis';
            } elseif ($burnRatePct >= 80) {
                $statusBiaya = '🟡 Waspada (>80% RAB)';
            }

            $totalKontrakPortofolio += $kontrak;
            $totalHppRiilPortofolio += $hppRiil;
            $totalRabPortofolio += $rab;

            $proyekList[] = array(
                'project_id' => (int)$r['id'],
                'nama_proyek' => $r['nama'],
                'customer' => $r['customer_nama'],
                'nomor_kontrak' => !empty($r['nomor_kontrak']) ? $r['nomor_kontrak'] : '-',
                'nilai_kontrak_rp' => $kontrak,
                'target_rab_rp' => $rab,
                'hpp_aktual_rp' => $hppRiil,
                'serapan_rab_pct' => round($burnRatePct, 1),
                'estimasi_laba_rp' => $grossProfit,
                'gross_margin_pct' => round($grossMarginPct, 1),
                'progress_fisik_pct' => round((float)$r['persen_progress'], 1),
                'total_spk' => (int)$r['total_spk'],
                'status_biaya' => $statusBiaya,
                'status_proyek' => ($r['closing_status'] == 1) ? 'Closed / Selesai' : 'Ongoing / Aktif'
            );
        }

        $totalLabaPortofolio = $totalKontrakPortofolio - $totalHppRiilPortofolio;
        $rataMarginPortofolio = $totalKontrakPortofolio > 0 ? ($totalLabaPortofolio / $totalKontrakPortofolio) * 100 : 0;

        return array(
            'status' => 'success',
            'is_executive' => $isExec,
            'ringkasan_portofolio' => array(
                'total_nilai_kontrak_rp' => $totalKontrakPortofolio,
                'total_target_rab_rp' => $totalRabPortofolio,
                'total_hpp_aktual_rp' => $totalHppRiilPortofolio,
                'total_estimasi_laba_rp' => $totalLabaPortofolio,
                'rata_margin_laba_pct' => round($rataMarginPortofolio, 1),
                'jumlah_proyek_termonitor' => count($proyekList)
            ),
            'daftar_proyek' => $proyekList
        );
    }

    /**
     * Mencari detail status biaya, laba kotor, dan serapan RAB untuk proyek tertentu berdasarkan kata kunci.
     * 
     * @param string $keyword
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getProjectDetailAndMargin($keyword, $cabangId, $userJenis)
    {
        $cleanKeyword = trim(strip_tags($keyword));
        if (empty($cleanKeyword)) {
            // Jika tidak ada kata kunci spesifik, panggil ikhtisar portofolio
            return $this->getProjectPortfolioOverview($cabangId, $userJenis);
        }

        $isExec = $this->isExecutive($userJenis);
        $isProj = $this->isProjectRole($userJenis);

        if (!$isExec && !$isProj) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Detail RAB dan margin laba proyek memerlukan wewenang Manajemen Proyek atau Eksekutif.'
            );
        }

        $sql = "SELECT p.id, p.kode, p.nama, p.customer_nama, p.nomor_kontrak, 
                       p.harga as nilai_kontrak, 
                       p.harga_hpp_so as budget_rab, 
                       p.persen_progress, p.start_dtime, p.end_dtime, p.closing_status,
                       COALESCE(h.total_hpp, 0) as hpp_riil
                FROM project_produk p
                LEFT JOIN (
                    SELECT extern_id, SUM(debet - kredit) as total_hpp
                    FROM _rek_pembantu_hpp_project_cache
                    GROUP BY extern_id
                ) h ON p.id = h.extern_id
                WHERE p.trash = 0 
                  AND (p.nama LIKE ? OR p.nomor_kontrak LIKE ? OR p.customer_nama LIKE ?)
                ORDER BY p.id DESC LIMIT 5";
        $likePattern = '%' . $cleanKeyword . '%';
        $query = $this->db->query($sql, array($likePattern, $likePattern, $likePattern));
        $rows = $query->result_array();

        if (empty($rows)) {
            // Jika pencarian dengan keyword spesifik tidak ditemukan, tampilkan ikhtisar portofolio seluruh proyek aktif
            $fallback = $this->getProjectPortfolioOverview($cabangId, $userJenis);
            $fallback['catatan'] = "Kata kunci '{$cleanKeyword}' tidak cocok dengan nama proyek tertentu. Berikut adalah ikhtisar portofolio proyek aktif saat ini:";
            return $fallback;
        }

        $detailProyek = array();
        foreach ($rows as $r) {
            $pId = (int)$r['id'];
            $kontrak = (float)$r['nilai_kontrak'];
            $hppRiil = (float)$r['hpp_riil'];
            $rab = (float)$r['budget_rab'] > 0 ? (float)$r['budget_rab'] : 0;
            $grossProfit = $kontrak - $hppRiil;
            $grossMarginPct = $kontrak > 0 ? ($grossProfit / $kontrak) * 100 : 0;
            $burnRatePct = $rab > 0 ? ($hppRiil / $rab) * 100 : 0;

            // Ambil SPK terkait
            $sqlSpk = "SELECT id, no_spk, no_pre_spk, produk_nama, employee_nama, progress_nama, progress_percent, dtime_start, dtime_end
                       FROM project_tasklist
                       WHERE produk_id = ? AND trash = 0
                       ORDER BY id DESC LIMIT 5";
            $querySpk = $this->db->query($sqlSpk, array($pId));
            $spkList = $querySpk->result_array();

            $statusBiaya = '🟢 Aman (Dalam Anggaran)';
            if ($burnRatePct > 95) {
                $statusBiaya = '🔴 Over-Budget (Melebihi Batas RAB)';
            } elseif ($burnRatePct >= 80) {
                $statusBiaya = '🟡 Waspada (Mendekati Batas RAB)';
            }

            $detailProyek[] = array(
                'project_id' => $pId,
                'nama' => $r['nama'],
                'customer' => $r['customer_nama'],
                'nomor_kontrak' => $r['nomor_kontrak'],
                'nilai_kontrak_rp' => $kontrak,
                'target_rab_rp' => $rab,
                'hpp_aktual_rp' => $hppRiil,
                'serapan_rab_pct' => round($burnRatePct, 1),
                'estimasi_laba_rp' => $grossProfit,
                'gross_margin_pct' => round($grossMarginPct, 1),
                'progress_fisik_pct' => round((float)$r['persen_progress'], 1),
                'periode' => "{$r['start_dtime']} s/d {$r['end_dtime']}",
                'status_biaya' => $statusBiaya,
                'daftar_spk' => $spkList
            );
        }

        return array(
            'status' => 'success',
            'query' => $cleanKeyword,
            'is_executive' => $isExec,
            'data' => $detailProyek
        );
    }

    /**
     * Memonitor penagihan termin piutang proyek dari _rek_pembantu_customer_project_cache.
     * 
     * @param string $keyword
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getProjectTerminReceivables($keyword, $cabangId, $userJenis)
    {
        $cleanKeyword = trim(strip_tags($keyword));
        $isExec = $this->isExecutive($userJenis);
        $isProj = $this->isProjectRole($userJenis);

        if (!$isExec && !$isProj) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Data piutang termin proyek hanya dapat diakses oleh Manajemen Proyek, Keuangan, atau Eksekutif.'
            );
        }

        $sql = "SELECT extern_id as customer_id, extern_nama as customer_nama, extern2_nama as nama_proyek, 
                       SUM(debet) as total_tagihan_termin, 
                       SUM(kredit) as total_dibayar, 
                       (SUM(debet) - SUM(kredit)) as sisa_piutang_termin
                FROM _rek_pembantu_customer_project_cache ";

        $params = array();
        if (!empty($cleanKeyword)) {
            $sql .= " WHERE (extern_nama LIKE ? OR extern2_nama LIKE ?) ";
            $like = '%' . $cleanKeyword . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " GROUP BY extern_id, extern2_nama 
                  HAVING sisa_piutang_termin > 0 
                  ORDER BY sisa_piutang_termin DESC 
                  LIMIT 15";
        $query = $this->db->query($sql, $params);
        $rows = $query->result_array();

        $totalSisaPiutang = 0;
        $terminList = array();

        foreach ($rows as $r) {
            $sisa = (float)$r['sisa_piutang_termin'];
            $totalSisaPiutang += $sisa;

            $terminList[] = array(
                'customer_nama' => $r['customer_nama'],
                'nama_proyek' => !empty($r['nama_proyek']) ? $r['nama_proyek'] : 'Semua Proyek Terkait',
                'total_tagihan_rp' => (float)$r['total_tagihan_termin'],
                'total_dibayar_rp' => (float)$r['total_dibayar'],
                'sisa_piutang_rp' => $sisa
            );
        }

        return array(
            'status' => 'success',
            'query' => $cleanKeyword,
            'total_piutang_termin_berjalan_rp' => $totalSisaPiutang,
            'daftar_termin' => $terminList
        );
    }

    /**
     * Memonitor daftar Surat Perintah Kerja (SPK) dan pelaksana lapangan dari project_tasklist.
     * 
     * @param string $keyword
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getProjectSpkTaskList($keyword, $cabangId, $userJenis)
    {
        $cleanKeyword = trim(strip_tags($keyword));

        $sql = "SELECT id, no_spk, no_pre_spk, nama as project_nama, produk_nama, 
                       employee_nama as mandor_pelaksana, progress_nama, progress_percent, 
                       dtime_start, dtime_end, nilai as rincian_pekerjaan
                FROM project_tasklist 
                WHERE trash = 0 ";

        $params = array();
        if (!empty($cleanKeyword)) {
            $sql .= " AND (nama LIKE ? OR produk_nama LIKE ? OR employee_nama LIKE ? OR no_spk LIKE ?) ";
            $like = '%' . $cleanKeyword . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= " ORDER BY id DESC LIMIT 15";
        $query = $this->db->query($sql, $params);
        $rows = $query->result_array();

        $spkList = array();
        foreach ($rows as $r) {
            $spkList[] = array(
                'spk_id' => (int)$r['id'],
                'no_spk' => !empty($r['no_spk']) ? $r['no_spk'] : $r['no_pre_spk'],
                'proyek' => $r['project_nama'],
                'unit_pekerjaan' => $r['produk_nama'],
                'mandor_pelaksana' => !empty($r['mandor_pelaksana']) ? $r['mandor_pelaksana'] : 'Belum Ditugaskan',
                'status_pengerjaan' => !empty($r['progress_nama']) ? $r['progress_nama'] : 'Dalam Proses',
                'progress_pct' => (int)$r['progress_percent'],
                'jadwal' => "{$r['dtime_start']} s/d {$r['dtime_end']}",
                'deskripsi' => $r['rincian_pekerjaan']
            );
        }

        return array(
            'status' => 'success',
            'query' => $cleanKeyword,
            'total_spk_ditemukan' => count($spkList),
            'daftar_spk' => $spkList
        );
    }

    /**
     * Mengambil data likuiditas kas fisik cabang dan seluruh rekening bank aktif.
     * Khusus level Eksekutif (Strict RBAC Guard).
     * 
     * @param string $userJenis
     * @return array
     */
    public function getCashAndBankLiquidity($userJenis)
    {
        if (!$this->isExecutive($userJenis)) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Rincian saldo kas dan rekening bank perusahaan hanya dapat diakses oleh Direksi, Owner, atau CFO.'
            );
        }

        $sql = "SELECT b.id, b.nama, b.jenis, b.cabang_nama, SUM(s.debet - s.kredit) as saldo
                FROM bank b
                JOIN _rek_pembantu_subkas_cache s ON b.id = s.extern_id
                WHERE b.trash = 0
                GROUP BY b.id, b.nama
                HAVING saldo != 0
                ORDER BY saldo DESC";
        $query = $this->db->query($sql);
        $rows = $query->result_array();

        $totalLikuiditas = 0;
        $totalKasFisik = 0;
        $totalBank = 0;
        $rincianAkun = array();

        foreach ($rows as $r) {
            $saldo = (float)$r['saldo'];
            $totalLikuiditas += $saldo;

            $kategori = 'Rekening Bank';
            if ($r['jenis'] === 'account_cash' || strpos(strtolower($r['nama']), 'tunai') !== false || strpos(strtolower($r['nama']), 'pettycash') !== false) {
                $kategori = 'Kas Fisik / Petty Cash';
                $totalKasFisik += $saldo;
            } else {
                $totalBank += $saldo;
            }

            $rincianAkun[] = array(
                'akun_id' => (int)$r['id'],
                'nama_akun' => $r['nama'],
                'kategori' => $kategori,
                'cabang' => !empty($r['cabang_nama']) ? $r['cabang_nama'] : 'Pusat',
                'saldo_rp' => $saldo
            );
        }

        return array(
            'status' => 'success',
            'total_likuiditas_konsolidasi_rp' => $totalLikuiditas,
            'total_kas_fisik_rp' => $totalKasFisik,
            'total_rekening_bank_rp' => $totalBank,
            'rincian_akun' => $rincianAkun
        );
    }

    /**
     * Mengambil analisis modal kerja (Working Capital) dengan mengomparasikan total piutang vs total hutang supplier.
     * Khusus level Eksekutif.
     * 
     * @param string $userJenis
     * @return array
     */
    public function getArApWorkingCapital($userJenis)
    {
        if (!$this->isExecutive($userJenis)) {
            return array(
                'status' => 'denied',
                'message' => 'Akses Ditolak: Laporan modal kerja (piutang vs hutang) hanya dapat diakses oleh jajaran Eksekutif.'
            );
        }

        // 1. Piutang Customer Proyek
        $qProjAr = $this->db->query("SELECT SUM(debet - kredit) as total FROM _rek_pembantu_customer_project_cache");
        $projAr = (float)$qProjAr->row()->total;

        // 2. Piutang Customer Regular
        $qCustAr = $this->db->query("SELECT SUM(debet - kredit) as total FROM _rek_pembantu_customer_cache");
        $custAr = (float)$qCustAr->row()->total;

        // 3. Hutang Supplier
        $qSuppAp = $this->db->query("SELECT SUM(kredit - debet) as total FROM _rek_pembantu_supplier_cache");
        $suppAp = (float)$qSuppAp->row()->total;

        $totalPiutang = $projAr + $custAr;
        $gapWorkingCapital = $totalPiutang - $suppAp;

        return array(
            'status' => 'success',
            'total_piutang_customer_rp' => $totalPiutang,
            'piutang_proyek_rp' => $projAr,
            'piutang_reguler_rp' => $custAr,
            'total_hutang_supplier_rp' => $suppAp,
            'net_working_capital_gap_rp' => $gapWorkingCapital,
            'rasio_piutang_terhadap_hutang' => ($suppAp > 0) ? round(($totalPiutang / $suppAp), 2) : 0,
            'catatan' => 'Working capital dihitung dari piutang customer aktif dikurangi kewajiban hutang supplier.'
        );
    }

    /**
     * Mengambil daftar produk dengan stok kritis atau di bawah MOQ untuk kebutuhan Purchasing dan Gudang.
     * 
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getLowStockReorderAlert($cabangId, $userJenis)
    {
        $sql = "SELECT c1.extern_id as produk_id, p.kode, p.nama, p.satuan, p.moq, 
                       (c1.saldo_qty_debet - c1.saldo_qty_kredit) as sisa_qty
                FROM _rek_pembantu_produk_cache c1
                INNER JOIN (
                    SELECT MAX(id) as max_id 
                    FROM _rek_pembantu_produk_cache 
                    WHERE cabang_id = ? 
                    GROUP BY extern_id
                ) c2 ON c1.id = c2.max_id
                JOIN produk p ON c1.extern_id = p.id
                WHERE p.status = '1' AND p.trash = '0' 
                  AND (c1.saldo_qty_debet - c1.saldo_qty_kredit) <= 5
                  AND (c1.saldo_qty_debet - c1.saldo_qty_kredit) >= 0
                ORDER BY sisa_qty ASC 
                LIMIT 15";

        $cId = ($cabangId > 0) ? (int)$cabangId : 1;
        $query = $this->db->query($sql, array($cId));
        $rows = $query->result_array();

        $kritisList = array();
        foreach ($rows as $r) {
            $sisa = (int)$r['sisa_qty'];
            $moq = (int)$r['moq'];

            $kritisList[] = array(
                'produk_id' => (int)$r['produk_id'],
                'kode' => $r['kode'],
                'nama' => $r['nama'],
                'satuan' => !empty($r['satuan']) ? $r['satuan'] : 'Unit',
                'sisa_stok_efektif' => $sisa,
                'reorder_moq' => $moq,
                'status' => ($sisa <= 0) ? '🔴 Habis (Kosong)' : '🟡 Kritis (Sisa <= 5)'
            );
        }

        return array(
            'status' => 'success',
            'cabang_id' => $cId,
            'total_item_kritis' => count($kritisList),
            'daftar_item' => $kritisList
        );
    }

    /**
     * Mengambil status plafon kredit, total piutang berjalan, dan kelayakan tempo konsumen.
     * Menerapkan rekomendasi Guha et al. (2021) untuk proteksi sales & kasir.
     * 
     * @param string $keyword
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getCustomerCreditStatus($keyword, $cabangId, $userJenis)
    {
        $cleanKeyword = trim(strip_tags($keyword));
        if (empty($cleanKeyword)) {
            // Ambil 10 customer dengan piutang tertinggi atau yang memiliki plafon
            $sqlCust = "SELECT id, nama, member_id, kredit_limit, jatuh_tempo, due_days, tlp_1, cabang_nama
                        FROM per_customers
                        WHERE status = 1 AND trash = 0 AND kredit_limit > 0
                        ORDER BY kredit_limit DESC LIMIT 10";
            $qCust = $this->db->query($sqlCust);
        } else {
            $sqlCust = "SELECT id, nama, member_id, kredit_limit, jatuh_tempo, due_days, tlp_1, cabang_nama
                        FROM per_customers
                        WHERE status = 1 AND trash = 0
                          AND (nama LIKE ? OR member_id LIKE ?)
                        LIMIT 5";
            $like = '%' . $cleanKeyword . '%';
            $qCust = $this->db->query($sqlCust, array($like, $like));
        }

        $customers = $qCust->result_array();
        if (empty($customers)) {
            return array(
                'status' => 'not_found',
                'message' => "Tidak ditemukan data customer dengan kata kunci '{$cleanKeyword}'.",
                'data' => array()
            );
        }

        $results = array();
        foreach ($customers as $c) {
            $cId = (int)$c['id'];
            $plafon = (float)$c['kredit_limit'];
            $tempoHari = (int)$c['due_days'] > 0 ? (int)$c['due_days'] : (int)$c['jatuh_tempo'];

            // Query total piutang berjalan dari rekening pembantu customer
            $qAr = $this->db->query("SELECT SUM(debet - kredit) as total_piutang 
                                     FROM _rek_pembantu_customer_cache 
                                     WHERE extern_id = ?", array($cId));
            $piutangRow = $qAr->row_array();
            $piutangBerjalan = isset($piutangRow['total_piutang']) ? (float)$piutangRow['total_piutang'] : 0;
            if ($piutangBerjalan < 0) {
                $piutangBerjalan = 0; // Lebih bayar / saldo deposit
            }

            // Sisa plafon yang masih bisa digunakan
            $sisaPlafon = $plafon - $piutangBerjalan;
            $persenTerpakai = ($plafon > 0) ? round(($piutangBerjalan / $plafon) * 100, 1) : 0;

            // Status evaluasi kelayakan kredit
            $statusKelayakan = '🟢 Aman (Boleh Kredit)';
            $rekomendasi = 'Order penjualan kredit disetujui, sisa limit mencukupi.';

            if ($plafon <= 0) {
                $statusKelayakan = '🟡 Khusus Tunai (Tanpa Plafon)';
                $rekomendasi = 'Customer tidak memiliki plafon kredit. Wajib pembayaran tunai/transfer di depan.';
            } elseif ($sisaPlafon <= 0) {
                $statusKelayakan = '🔴 Over-Limit / Melebihi Plafon';
                $rekomendasi = 'Tahan order tempo! Plafon telah habis terpakai. Wajib pelunasan piutang lama atau approval direksi.';
            } elseif ($persenTerpakai >= 85) {
                $statusKelayakan = '🟡 Peringatan (Mendekati Limit >85%)';
                $rekomendasi = 'Sisa limit tipis. Pastikan nilai order baru tidak melebihi sisa plafon yang tersedia.';
            }

            $results[] = array(
                'customer_id' => $cId,
                'nama_customer' => $c['nama'],
                'member_id' => !empty($c['member_id']) ? $c['member_id'] : '-',
                'tempo_pembayaran_hari' => $tempoHari > 0 ? $tempoHari . ' Hari' : 'Tunai',
                'plafon_kredit_rp' => $plafon,
                'piutang_berjalan_rp' => $piutangBerjalan,
                'sisa_plafon_rp' => max(0, $sisaPlafon),
                'persen_plafon_terpakai' => $persenTerpakai,
                'status_kelayakan' => $statusKelayakan,
                'rekomendasi_tindakan' => $rekomendasi,
                'kontak' => !empty($c['tlp_1']) ? $c['tlp_1'] : '-'
            );
        }

        return array(
            'status' => 'success',
            'query' => $cleanKeyword,
            'total_customer' => count($results),
            'daftar_customer' => $results
        );
    }

    /**
     * Mengambil daftar produk slow-moving & dead-stock (stok mengendap >= 90 hari) untuk bisnis elektronik.
     * Menerapkan rekomendasi Toorajipour et al. (2021) untuk mitigasi depresiasi persediaan elektronik.
     * 
     * @param int $cabangId
     * @param string $userJenis
     * @param int $minDays
     * @return array
     */
    public function getSlowMovingStockAlert($cabangId, $userJenis, $minDays = 90)
    {
        $isExec = $this->isExecutive($userJenis);
        $minDays = ($minDays > 0) ? (int)$minDays : 90;

        $sql = "SELECT c1.extern_id as produk_id, p.kode, p.nama, p.kategori_nama, p.satuan,
                       (c1.saldo_qty_debet - c1.saldo_qty_kredit) as sisa_qty,
                       c1.harga as hpp,
                       ((c1.saldo_qty_debet - c1.saldo_qty_kredit) * c1.harga) as nilai_mengendap,
                       c1.dtime as mutasi_terakhir,
                       DATEDIFF(NOW(), c1.dtime) as hari_mengendap,
                       c1.cabang_id
                FROM _rek_pembantu_produk_cache c1
                INNER JOIN (
                    SELECT MAX(id) as max_id
                    FROM _rek_pembantu_produk_cache
                    WHERE 1 = 1 " . ((!$isExec && $cabangId > 0) ? " AND cabang_id = ? " : "") . "
                    GROUP BY extern_id
                ) c2 ON c1.id = c2.max_id
                JOIN produk p ON c1.extern_id = p.id
                WHERE p.status = '1' AND p.trash = '0'
                  AND (c1.saldo_qty_debet - c1.saldo_qty_kredit) > 0
                  AND DATEDIFF(NOW(), c1.dtime) >= ?
                ORDER BY hari_mengendap DESC, nilai_mengendap DESC
                LIMIT 15";

        $binds = array();
        if (!$isExec && $cabangId > 0) {
            $binds[] = (int)$cabangId;
        }
        $binds[] = $minDays;

        $q = $this->db->query($sql, $binds);
        $rows = $q->result_array();

        $totalItem = count($rows);
        $totalNilaiTertahan = 0;
        $items = array();

        foreach ($rows as $r) {
            $qty = (int)$r['sisa_qty'];
            $hpp = (float)$r['hpp'];
            $nilaiMengendap = (float)$r['nilai_mengendap'];
            $hariMengendap = (int)$r['hari_mengendap'];

            $totalNilaiTertahan += $nilaiMengendap;

            // Klasifikasi Usia Stok
            $statusUsia = '🟡 Slow-Moving (90-180 Hari)';
            $saranTindakan = 'Jadwalkan promo bundling dengan item fast-moving atau diskon retail.';
            if ($hariMengendap > 365) {
                $statusUsia = '🔴 Dead-Stock (> 1 Tahun)';
                $saranTindakan = 'Prioritaskan cuci gudang / lelang / return ke supplier sebelum harga pasar jatuh.';
            } elseif ($hariMengendap > 180) {
                $statusUsia = '🟠 Stagnan (180-365 Hari)';
                $saranTindakan = 'Berikan insentif/komisi tambahan kepada sales untuk menghabiskan unit ini.';
            }

            $itemData = array(
                'produk_id' => (int)$r['produk_id'],
                'kode' => $r['kode'],
                'nama' => $r['nama'],
                'kategori' => $r['kategori_nama'],
                'satuan' => !empty($r['satuan']) ? $r['satuan'] : 'Unit',
                'sisa_qty_mengendap' => $qty,
                'hari_tidak_bergerak' => $hariMengendap,
                'status_usia' => $statusUsia,
                'rekomendasi_tindakan' => $saranTindakan
            );

            if ($isExec) {
                $itemData['hpp_satuan_rp'] = $hpp;
                $itemData['total_nilai_modal_tertahan_rp'] = $nilaiMengendap;
            } else {
                $itemData['hpp_satuan_rp'] = 'RAHASIA (Hanya Eksekutif)';
                $itemData['total_nilai_modal_tertahan_rp'] = 'RAHASIA (Hanya Eksekutif)';
            }

            $items[] = $itemData;
        }

        return array(
            'status' => 'success',
            'ambang_batas_hari' => $minDays,
            'total_produk_stagnan' => $totalItem,
            'total_nilai_modal_tertahan_rp' => $isExec ? $totalNilaiTertahan : 'RAHASIA (Hanya Eksekutif)',
            'daftar_produk' => $items
        );
    }

    /**
     * Mengambil ikhtisar transaksi aktif yang sedang dibuka pengguna di sistem (Context-Aware).
     * Menerapkan prinsip Human-AI Symbiosis pada transaksi in-flight.
     * 
     * @param string|int $refTransaksi
     * @param int $cabangId
     * @param string $userJenis
     * @return array
     */
    public function getTransactionContextSummary($refTransaksi, $cabangId, $userJenis)
    {
        $refClean = trim(strip_tags($refTransaksi));
        if (empty($refClean)) {
            return array(
                'status' => 'empty_ref',
                'message' => 'Tidak ada referensi transaksi aktif yang terdeteksi di layar.'
            );
        }

        $isExec = $this->isExecutive($userJenis);
        $isSpv = $this->isSupervisor($userJenis);

        // Cari transaksi berdasarkan ID atau nomer
        $sql = "SELECT id, nomer, nomer2, dtime, jenis_label, transaksi_jenis, cabang_nama, oleh_nama, 
                       transaksi_nilai, status, indexing_details
                FROM transaksi
                WHERE (id = ? OR nomer = ? OR nomer2 = ?)
                LIMIT 1";
        $q = $this->db->query($sql, array($refClean, $refClean, $refClean));
        $tx = $q->row_array();

        if (empty($tx)) {
            return array(
                'status' => 'not_found',
                'message' => "Transaksi dengan referensi '{$refClean}' tidak ditemukan di database."
            );
        }

        $txId = (int)$tx['id'];

        // Ambil rincian barang dari transaksi_data
        $sqlItems = "SELECT id, produk_id, produk_nama, produk_kode, satuan, produk_ord_jml, 
                            produk_ord_hrg, hpp, produk_ord_diskon, keterangan
                     FROM transaksi_data
                     WHERE transaksi_id = ? AND trash = 0
                     ORDER BY id ASC LIMIT 20";
        $qItems = $this->db->query($sqlItems, array($txId));
        $itemRows = $qItems->result_array();

        $itemsSummary = array();
        $totalModalHpp = 0;
        $totalPenjualan = 0;

        foreach ($itemRows as $it) {
            $qty = (int)$it['produk_ord_jml'];
            $hrg = (float)$it['produk_ord_hrg'];
            $hpp = (float)$it['hpp'];
            $subtotal = $qty * $hrg;
            $modal = $qty * $hpp;

            $totalPenjualan += $subtotal;
            $totalModalHpp += $modal;

            $itemData = array(
                'nama_barang' => $it['produk_nama'],
                'kode' => $it['produk_kode'],
                'qty' => $qty,
                'satuan' => !empty($it['satuan']) ? $it['satuan'] : 'Unit',
                'harga_satuan_rp' => $hrg,
                'subtotal_rp' => $subtotal
            );

            if ($isExec || $isSpv) {
                $itemData['hpp_satuan_rp'] = $hpp;
                $itemData['laba_kotor_rp'] = $subtotal - $modal;
                $itemData['margin_pct'] = ($subtotal > 0) ? round((($subtotal - $modal) / $subtotal) * 100, 1) : 0;
            }

            $itemsSummary[] = $itemData;
        }

        $totalLabaKotor = $totalPenjualan - $totalModalHpp;
        $overallMarginPct = ($totalPenjualan > 0) ? round(($totalLabaKotor / $totalPenjualan) * 100, 1) : 0;

        // Evaluasi Kesehatan Transaksi
        $evaluasi = '🟢 Transaksi Normal & Sesuai Prosedur';
        if ($overallMarginPct < 5 && ($isExec || $isSpv)) {
            $evaluasi = '🟡 Margin Sangat Tipis (< 5%), periksa kembali diskon yang diberikan.';
        } elseif ($totalLabaKotor < 0 && ($isExec || $isSpv)) {
            $evaluasi = '🔴 Rugi / Jual di Bawah HPP! Wajib otorisasi khusus dari Direksi.';
        }

        $result = array(
            'status' => 'success',
            'transaksi_id' => $txId,
            'nomor_transaksi' => !empty($tx['nomer2']) ? $tx['nomer2'] : $tx['nomer'],
            'jenis_transaksi' => $tx['jenis_label'],
            'cabang' => $tx['cabang_nama'],
            'petugas_pembuat' => $tx['oleh_nama'],
            'waktu_transaksi' => $tx['dtime'],
            'total_nilai_transaksi_rp' => (float)$tx['transaksi_nilai'],
            'total_item_barang' => count($itemsSummary),
            'daftar_barang' => $itemsSummary,
            'evaluasi_kelayakan' => $evaluasi
        );

        if ($isExec || $isSpv) {
            $result['total_modal_hpp_rp'] = $totalModalHpp;
            $result['estimasi_laba_kotor_rp'] = $totalLabaKotor;
            $result['overall_margin_pct'] = $overallMarginPct;
        }

        return $result;
    }
}

// END OF COMPLETE REPEATED LOGIC
