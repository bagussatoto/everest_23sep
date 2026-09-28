<?php
// START OF COMPLETE REPEATED LOGIC

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * =========================================================================================
 * KONFIGURASI PUSAT INTEGRASI AI COPILOT & ERP ASSISTANT (MULTI-TENANT / WHITE-LABEL READY)
 * =========================================================================================
 * 
 * Berkas ini adalah SATU-SATUNYA tempat konfigurasi seluruh aspek AI Chatbot di aplikasi ERP.
 * Ketika Anda menginstalasi atau menerapkan sistem ERP ini ke perusahaan/klien baru,
 * Anda CUKUP mengubah nilai-nilai di dalam berkas ini tanpa perlu mengubah kode program sama sekali.
 *
 * Pengaturan mencakup:
 * 1. Identitas Perusahaan & Branding AI (White-Label)
 * 2. Infrastruktur Database & Read-Replica Server
 * 3. Penyedia AI (LLM Provider: Local Engine, Groq, Gemini, OpenRouter, OpenAI, Local Ollama)
 * 4. Toggle Fitur Analitik Aktif (Feature Flags) per Modul Bisnis
 * 5. Tautan URL Modul Transaksi (Actionable Deep Links)
 * 6. Karakter & Standar Komunikasi Asisten AI (System Prompt Template)
 */
$config['ai_chat'] = array(

    // =========================================================================
    // 1. IDENTITAS KORPORAT & BRANDING (WHITE-LABEL)
    // =========================================================================
    // Sesuaikan nama perusahaan dan branding asisten dengan profil klien baru:
    'company_name'        => 'PT Everest Electronic',                  // Nama resmi perusahaan pengguna ERP
    'bot_name'            => 'Everest-AI',                     // Nama asisten AI di jendela chat
    'bot_title'           => 'Enterprise ERP Business Consultant & Operational Advisor', // Jabatan/peran formal AI
    'business_sector'     => 'Perdagangan, Ritel, & Distribusi Elektronik', // Sektor industri bisnis utama
    'currency_symbol'     => 'Rp',                                     // Format simbol mata uang
    'greeting_salutation' => 'Bapak/Ibu',                              // Panggilan sapaan formal ke staf pengguna

    // =========================================================================
    // 2. INFRASTRUKTUR DATABASE & READ-REPLICA
    // =========================================================================
    // Untuk menjaga performa server utama (OLTP) agar tidak terbebani kueri analitik AI:
    'use_db_replica'      => true,          // TRUE: Gunakan server replica | FALSE: Gunakan database default (single server)
    'replica_db_group'    => 'ai_replica',  // Nama grup koneksi di application/config/database.php
    // Catatan: Jika use_db_replica = true namun server replika offline/tidak dapat dijangkau,
    // sistem memiliki failover otomatis yang akan mengalihkan kueri ke database default secara aman.
    // Riwayat percakapan (chat message) SELALU disimpan di database default master.

    // =========================================================================
    // 3. PENYEDIA AI & MODEL (LLM ENGINE)
    // =========================================================================
    'enabled'             => true,          // Status aktifasi fitur AI secara global
    'provider'            => 'opencode',    // Pilihan: 'opencode', 'groq', 'gemini', 'openrouter', 'openai', 'custom'
    'api_key'             => 'opencode:rahasia13', // Kredensial autentikasi API
    'model'               => 'BP-MGK',      // Model AI default yang digunakan (Alias: BP-MGK)
    'timeout'             => 60,            // Batas waktu respon AI dalam detik
    'custom_endpoint'     => 'http://192.168.5.10:4096', // URL endpoint server AI lokal/on-premise

    // Daftar endpoint standar penyedia AI internasional & lokal
    'endpoints'           => array(
        'opencode'        => 'http://192.168.5.10:4096',
        'gemini'          => 'https://generativelanguage.googleapis.com/v1beta/models/',
        'groq'            => 'https://api.groq.com/openai/v1/chat/completions',
        'openrouter'      => 'https://openrouter.ai/api/v1/chat/completions',
        'openai'          => 'https://api.openai.com/v1/chat/completions',
        'custom'          => 'http://localhost:11434/v1/chat/completions',
    ),

    // =========================================================================
    // 4. TOGGLE FITUR ANALITIK & TOOLS (FEATURE FLAGS)
    // =========================================================================
    // Matikan (false) fitur tertentu jika klien baru tidak menggunakan modul tersebut:
    'features'            => array(
        'stock_check'              => true, // Pengecekan stok fisik, locker, dan batas kritis reorder (PO)
        'customer_credit'          => true, // Pemeriksaan plafon kredit, AR jatuh tempo, & evaluasi kelayakan order
        'slow_moving_stock'        => true, // Deteksi barang macet (>90 hari) & nilai modal tertahan (HPP)
        'active_transaction_check' => true, // Analisis context-aware terhadap transaksi/draf yang sedang aktif
        'project_management'       => true, // Portofolio proyek, serapan RAB, SPK mandor, & piutang termin
        'cash_bank_liquidity'      => true, // Likuiditas konsolidasi dana kas & bank (Khusus Direksi/Owner)
        'working_capital'          => true, // Analisis rasio modal kerja AR vs AP (Khusus Direksi/Owner)
        'deep_links'               => true, // Menampilkan tautan langsung (Deep Link) ke form input transaksi
    ),

    // =========================================================================
    // 5. TAUTAN RUTE TRANSAKSI (ACTIONABLE DEEP-LINK ROUTES)
    // =========================================================================
    // Path URL relatif aplikasi yang disematkan asisten AI pada rekomendasi tindakan:
    'routes'              => array(
        'po_create'         => 'pembelian/Create/index',    // Buat pengajuan Purchase Order baru
        'project_transaksi' => 'projek/Transaksi/index',     // Modul proyek dan monitoring SPK
        'stock_adjust'      => 'inputstok/Transaksi/index',  // Penyesuaian stok / cuci gudang
        'customer_master'   => 'customer/Transaksi/index',   // Pengaturan plafon & data customer
    ),

    // =========================================================================
    // 6. SYSTEM PROMPT & PEDOMAN ETIKA KOMUNIKASI
    // =========================================================================
    // Template instruksi dasar kepribadian konsultan korporat (Mendukung placeholder dinamis):
    'system_prompt'       => "Anda adalah {bot_name}, {bot_title} resmi untuk seluruh jajaran staf, manajemen, dan direksi pengguna sistem {company_name} ({business_sector}).\n\n"
        . "[PERAN & IDENTITAS RESMI]\n"
        . "- Nama Asisten: {bot_name}.\n"
        . "- Posisi: {bot_title}.\n"
        . "- Karakter: Ahli, solutif, santun, hangat, berwibawa, bijaksana, dan sangat menghormati pengguna.\n"
        . "- Domain Keahlian: Manajemen rantai pasok (supply chain), persediaan gudang & mutasi stok, modul penjualan & penagihan (AR), pembelian & hutang supplier (AP), operasional proyek & SPK lapangan, likuiditas kas & bank, perpajakan, serta akuntansi keuangan terpadu di lingkungan bisnis {business_sector}.\n\n"
        . "[ETIKA & ATURAN BERKOMUNIKASI (SANGAT KETAT)]\n"
        . "1. DILARANG KERAS bersikap ketus, dingin, sarkastik, kasar ('barbar'), atau memberikan jawaban yang buntung tanpa penjelasan ramah.\n"
        . "2. SELALU sapa pengguna dengan penuh hormat di awal jawaban, sebutkan nama atau peran pengguna secara sopan (contoh: 'Halo {greeting_salutation} [Nama], senang dapat mendampingi operasional Anda hari ini.').\n"
        . "3. Gunakan Bahasa Indonesia formal yang elegan, ramah, dan profesional yang mencerminkan standar konsultan korporat tingkat tinggi.\n"
        . "4. Jika data atau kata kunci tidak ditemukan, JANGAN PERNAH hanya menjawab 'data tidak ditemukan'. Sampaikan permohonan maaf dengan sangat santun, jelaskan kemungkinan penyebabnya (misal perbedaan penulisan nama/kode), dan berikan saran solusi atau kata kunci alternatif yang dapat dicoba.\n"
        . "5. FORMAT BALASAN (WAJIB HTML NORMAL): DILARANG menggunakan format Markdown WhatsApp (seperti *teks tebal*, _miring_, atau tabel markdown dengan garis |). Untuk data angka, keuangan, daftar proyek, stok barang, atau transaksi, WAJIB disajikan menggunakan format HTML normal yang rapi (tag <table>, <thead>, <tbody>, <tr>, <th>, <td>, <b>, <i>, <ul>, <ol>, <li>, <p>, <br>). Setiap data tabular wajib diikuti ringkasan eksekutif (Executive Summary) atau poin-poin analisis yang bernilai bisnis tinggi.\n"
        . "6. Selalu tutup jawaban dengan salam penutup yang hangat dan tawarkan kesiapan Anda untuk memberikan bantuan lanjutan."
);

// END OF COMPLETE REPEATED LOGIC
