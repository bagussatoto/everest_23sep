<?php
// START OF COMPLETE REPEATED LOGIC

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mdls/MdlChat');
        $this->load->model('Mdls/MdlSetting');
        $this->load->model('Coms/ComAiErpTools');
        $this->load->helper('he_misc');
        $this->load->config('ai_chat', TRUE);
    }

    private function check_auth_ajax($close_session = true)
    {
        if (!isset($this->session->login['id']) || empty($this->session->login['id'])) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Sesi login telah berakhir. Silakan login kembali.'
            ));
            exit();
        }

        // Heartbeat kehadiran online: Perbarui waktu aktif karyawan
        $my_id = (int)$this->session->login['id'];
        if ($my_id > 0) {
            $is_stealth = isset($this->session->login['is_stealth']) && $this->session->login['is_stealth'] == 1;
            if ($is_stealth) {
                $this->db->query("UPDATE per_employee SET last_dtime_active = NOW(), status_login = '0' WHERE id = ?", array($my_id));
            } else {
                $this->db->query("UPDATE per_employee SET last_dtime_active = NOW(), status_login = '1' WHERE id = ?", array($my_id));
            }
        }

        // Lepas file lock session agar request AJAX polling tidak saling mengantre (non-blocking)
        if ($close_session && session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    public function fetch_messages()
    {
        $this->check_auth_ajax();

        $my_id = (int)my_id();
        $my_cabang = (int)my_cabang_id();

        $chat_type = $this->input->get('chat_type');
        if (empty($chat_type)) {
            $chat_type = 'branch';
        }

        $target_id = (int)$this->input->get('target_id');

        // Jika tipe branch dan user adalah cabang biasa, default target_id ke cabang miliknya
        if ($chat_type === 'branch' && $my_cabang != -1 && $target_id <= 0) {
            $target_id = $my_cabang;
        }

        $last_id = (int)$this->input->get('last_id');
        $mark_read = (int)$this->input->get('mark_read');

        // Tandai sebagai dibaca jika jendela chat aktif terbuka
        if ($mark_read == 1) {
            $this->MdlChat->mark_as_read($chat_type, $target_id, $my_id);
        }

        $messages = $this->MdlChat->get_messages($chat_type, $target_id, $my_id, $last_id, 60);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'chat_type' => $chat_type,
            'target_id' => $target_id,
            'my_id' => $my_id,
            'my_cabang_id' => $my_cabang,
            'messages' => $messages
        ));
    }

    public function send_message()
    {
        $this->check_auth_ajax();

        $my_id = (int)my_id();
        $my_name = my_name();
        $my_cabang = (int)my_cabang_id();
        $my_cabang_nama = my_cabang_nama();

        $chat_type = $this->input->post('chat_type');
        if (empty($chat_type)) {
            $chat_type = 'branch';
        }

        $target_id = (int)$this->input->post('target_id');
        $target_name = trim(strip_tags($this->input->post('target_name')));
        $raw_message = $this->input->post('message');
        $message = trim(strip_tags($raw_message));
        $ref_transaksi = trim(strip_tags($this->input->post('ref_transaksi')));

        if (empty($message)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Pesan tidak boleh kosong'
            ));
            return;
        }

        if ($chat_type === 'branch' && $my_cabang != -1 && $target_id <= 0) {
            $target_id = $my_cabang;
        }

        if ($target_id <= 0) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Tujuan percakapan tidak valid'
            ));
            return;
        }

        $inserted = $this->MdlChat->send_message(
            $chat_type,
            $my_id,
            $my_name,
            $my_cabang,
            $my_cabang_nama,
            $target_id,
            $target_name,
            $message,
            $ref_transaksi
        );

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'data' => $inserted
        ));
    }

    public function send_ai_message()
    {
        $this->check_auth_ajax(false);

        $startTime = microtime(true);

        $my_id = (int)my_id();
        $my_name = my_name();
        $my_cabang = (int)my_cabang_id();
        $my_cabang_nama = my_cabang_nama();
        // Resolusi peran pengguna (RBAC) secara komprehensif dari session Everest ERP
        $raw_jenis = isset($this->session->login['jenis']) ? strtolower(trim($this->session->login['jenis'])) : '';
        $emp_type = isset($this->session->login['employee_type']) ? strtolower(trim($this->session->login['employee_type'])) : '';
        $memberships = (isset($this->session->login['membership']) && is_array($this->session->login['membership'])) ? $this->session->login['membership'] : array();
        $is_admin_flag = (isset($this->session->login['is_admin']) && $this->session->login['is_admin'] == 1);

        if ($raw_jenis === 'superman' || $emp_type === 'root' || in_array('root', $memberships) || in_array('superman', $memberships)) {
            $my_jenis = 'superman';
        } elseif ($raw_jenis === 'admin' || $is_admin_flag || in_array('admin', $memberships)) {
            $my_jenis = 'admin';
        } elseif (in_array('c_owner', $memberships)) {
            $my_jenis = 'owner';
        } elseif (!empty($raw_jenis)) {
            $my_jenis = $raw_jenis;
        } else {
            $is_spv = false;
            foreach ($memberships as $mG) {
                if (strpos($mG, '_spv') !== false || strpos($mG, '_mgr') !== false || in_array($mG, array('c_laporan', 'c_audit', 'o_audit'))) {
                    $is_spv = true;
                    break;
                }
            }
            if ($is_spv) {
                $my_jenis = 'supervisor';
            } elseif (in_array('c_finance', $memberships) || in_array('o_finance', $memberships)) {
                $my_jenis = 'finance';
            } elseif (in_array('o_project', $memberships)) {
                $my_jenis = 'project';
            } elseif (in_array('c_gudang', $memberships) || in_array('o_gudang', $memberships) || in_array('w_gudang', $memberships) || $emp_type === 'gudang') {
                $my_jenis = 'gudang';
            } elseif (in_array('c_purchasing', $memberships) || in_array('c_purchasing_adm', $memberships)) {
                $my_jenis = 'purchasing';
            } elseif (in_array('o_seller', $memberships) || in_array('o_seller_entry', $memberships) || in_array('o_export', $memberships)) {
                $my_jenis = 'seller';
            } elseif (in_array('o_kasir', $memberships)) {
                $my_jenis = 'kasir';
            } else {
                $my_jenis = 'staff';
            }
        }

        $raw_message = $this->input->post('message');
        $message = trim(strip_tags($raw_message));
        $ref_transaksi = trim(strip_tags($this->input->post('ref_transaksi')));

        if (empty($message)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Pertanyaan untuk AI tidak boleh kosong'
            ));
            return;
        }

        // 1. Dapatkan konfigurasi AI aktif (DB settings diutamakan, fallback ke config ai_chat.php)
        $ai_config = $this->get_effective_ai_config();

        // Variabel Identitas & Branding White-Label Dinamis
        $company_name = !empty($ai_config['company_name']) ? $ai_config['company_name'] : 'Everest ERP';
        $bot_name = !empty($ai_config['bot_name']) ? $ai_config['bot_name'] : 'Everest AI';
        $bot_title = !empty($ai_config['bot_title']) ? $ai_config['bot_title'] : 'Enterprise ERP Business Consultant & Operational Advisor';
        $business_sector = !empty($ai_config['business_sector']) ? $ai_config['business_sector'] : 'Perdagangan & Distribusi';
        $greeting_salutation = !empty($ai_config['greeting_salutation']) ? $ai_config['greeting_salutation'] : 'Bapak/Ibu';

        // 2. Deteksi Perintah Khusus Reset Sesi AI (/reset, /new, /clearsession, /clear)
        $is_reset_cmd = in_array(strtolower($message), array('/reset', '/new', '/clearsession', '/clear'));
        if ($is_reset_cmd) {
            $new_ai_session_id = 'ai_sess_' . $my_id . '_' . date('Ymd_His') . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
            $_SESSION['login']['ai_session_id'] = $new_ai_session_id;
            unset($_SESSION['login']['opencode_native_session_id']);

            $userMsg = $this->MdlChat->send_message(
                'ai',
                $my_id,
                $my_name,
                $my_cabang,
                $my_cabang_nama,
                0,
                $bot_name,
                $message,
                null,
                $new_ai_session_id
            );

            $botReply = "✨ <b>Sesi Percakapan Baru Telah Dibuka.</b><br>Konteks obrolan sebelumnya telah diarsipkan. Ada yang dapat saya bantu kembali seputar operasional ERP " . $company_name . ", " . $greeting_salutation . " " . $my_name . "?";

            $botMsg = $this->MdlChat->send_message(
                'ai',
                0,
                $bot_name,
                -1,
                'Sistem AI',
                $my_id,
                $my_name,
                $botReply,
                null,
                $new_ai_session_id
            );

            // Rekam audit trail reset sesi
            $this->MdlChat->record_ai_audit(array(
                'session_id'       => $new_ai_session_id,
                'user_id'          => $my_id,
                'user_name'        => $my_name,
                'user_jenis'       => $my_jenis,
                'cabang_id'        => $my_cabang,
                'cabang_nama'      => $my_cabang_nama,
                'provider'         => 'system',
                'model'            => 'internal',
                'intent'           => 'session_reset',
                'user_prompt'      => $message,
                'ref_transaksi'    => null,
                'grounding_data'   => null,
                'ai_response'      => $botReply,
                'duration_seconds' => round(microtime(true) - $startTime, 3),
                'status'           => 'success',
                'error_detail'     => null,
                'ip_address'       => $this->input->ip_address(),
                'user_agent'       => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : '',
                'created_at'       => date('Y-m-d H:i:s')
            ));

            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'success',
                'session_id' => $new_ai_session_id,
                'user_message' => $userMsg,
                'ai_message' => $botMsg
            ));
            return;
        }

        // 3. Kelola AI Session ID Persisten per Pengguna
        if (!isset($_SESSION['login']['ai_session_id']) || empty($_SESSION['login']['ai_session_id'])) {
            $_SESSION['login']['ai_session_id'] = 'ai_sess_' . $my_id . '_' . date('Ymd_His') . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
        }
        $ai_session_id = $_SESSION['login']['ai_session_id'];

        // 4. Simpan pesan pengguna ke database dengan session_id
        $userMsg = $this->MdlChat->send_message(
            'ai',
            $my_id,
            $my_name,
            $my_cabang,
            $my_cabang_nama,
            0,
            $bot_name,
            $message,
            $ref_transaksi,
            $ai_session_id
        );

        // 5. Ambil riwayat percakapan sebelumnya untuk memori multi-turn LLM
        $rawHistory = $this->MdlChat->get_ai_recent_history($my_id, $ai_session_id, 8);
        $conversationTurns = array();
        if (!empty($rawHistory) && is_array($rawHistory)) {
            foreach ($rawHistory as $hRow) {
                // Abaikan pesan pengguna saat ini agar tidak berulang
                if (isset($hRow['id']) && $hRow['id'] == $userMsg['id']) {
                    continue;
                }
                $isUserTurn = ((int)$hRow['sender_id'] === $my_id);
                $role = $isUserTurn ? 'user' : 'assistant';
                $cleanText = strip_tags($hRow['message']);
                if (mb_strlen($cleanText) > 800) {
                    $cleanText = mb_substr($cleanText, 0, 800) . '...';
                }
                $conversationTurns[] = array(
                    'role' => $role,
                    'content' => $cleanText
                );
            }
        }

        $provider = isset($ai_config['provider']) ? strtolower($ai_config['provider']) : 'opencode';
        $api_key = isset($ai_config['api_key']) ? trim($ai_config['api_key']) : '';
        $ai_reply = '';

        $is_local_provider = ($provider === 'opencode' || $provider === 'custom');

        if (empty($api_key) && !$is_local_provider) {
            $ai_reply = "Halo " . $my_name . "! Asisten " . $bot_name . " siap mendampingi operasional Anda.<br><br>Namun, fitur integrasi AI belum aktif karena API Key belum dikonfigurasi. Silakan tambahkan API Key melalui menu:<br>Settings -> Messenger & AI atau berkas konfigurasi application/config/ai_chat.php.<br><br>Setelah API Key disimpan, Anda dapat langsung berdiskusi dan meminta analisa data di sini.";
        } else {
            $raw_system_prompt = isset($ai_config['system_prompt']) ? $ai_config['system_prompt'] : 'Anda adalah konsultan ERP resmi.';

            // Interpolasi placeholder dinamis pada system prompt
            $system_prompt = str_replace(
                array('{bot_name}', '{company_name}', '{bot_title}', '{business_sector}', '{greeting_salutation}'),
                array($bot_name, $company_name, $bot_title, $business_sector, $greeting_salutation),
                $raw_system_prompt
            );

            $timeout = isset($ai_config['timeout']) ? (int)$ai_config['timeout'] : 60;

            // Tambahkan konteks identitas pengguna aktif saat ini agar AI selalu menyapa dengan sopan
            $system_prompt .= "\n\n[KONTEKS PENGGUNA AKTIF SAAT INI]\n";
            $system_prompt .= "- Nama Pengguna: " . $my_name . "\n";
            $system_prompt .= "- Jabatan/Peran: " . strtoupper($my_jenis) . "\n";
            $system_prompt .= "- Cabang Operasional: " . $my_cabang_nama . " (ID Cabang: " . $my_cabang . ")\n";
            $system_prompt .= "- ID Sesi Percakapan: " . $ai_session_id . "\n";
            $system_prompt .= "- Instruksi Sapaan: Awali jawaban dengan menyapa beliau secara ramah, santun, dan penuh hormat (contoh: 'Halo " . $greeting_salutation . " " . $my_name . ", senang dapat mendampingi operasional Anda hari ini.').\n";

            // Evaluasi Feature Flags yang diaktifkan perusahaan
            $features = (isset($ai_config['features']) && is_array($ai_config['features'])) ? $ai_config['features'] : array();
            $featActive = function($fName) use ($features) {
                return !isset($features[$fName]) || !empty($features[$fName]);
            };

            // Ekosistem Deterministic Tool Grounding & RBAC
            $erpTools = new ComAiErpTools();
            $intentData = $erpTools->detectUserIntent($message, $ref_transaksi);
            $groundingData = null;

            if ($intentData['intent'] === 'customer_credit_check' && $featActive('customer_credit')) {
                $groundingData = $erpTools->getCustomerCreditStatus($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'slow_moving_stock' && $featActive('slow_moving_stock')) {
                $groundingData = $erpTools->getSlowMovingStockAlert($my_cabang, $my_jenis, 90);
            } elseif ($intentData['intent'] === 'active_transaction_context' && $featActive('active_transaction_check')) {
                $groundingData = $erpTools->getTransactionContextSummary($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'project_spk_status' && $featActive('project_management')) {
                $groundingData = $erpTools->getProjectSpkTaskList($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'project_termin_ar' && $featActive('project_management')) {
                $groundingData = $erpTools->getProjectTerminReceivables($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'project_status' && $featActive('project_management')) {
                $groundingData = $erpTools->getProjectDetailAndMargin($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'cash_bank_liquidity' && $featActive('cash_bank_liquidity')) {
                $groundingData = $erpTools->getCashAndBankLiquidity($my_jenis);
            } elseif ($intentData['intent'] === 'ar_ap_working_capital' && $featActive('working_capital')) {
                $groundingData = $erpTools->getArApWorkingCapital($my_jenis);
            } elseif ($intentData['intent'] === 'low_stock_reorder' && $featActive('stock_check')) {
                $groundingData = $erpTools->getLowStockReorderAlert($my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'check_stock' && $featActive('stock_check')) {
                $groundingData = $erpTools->searchProductStock($intentData['keyword'], $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'division_summary') {
                $groundingData = $erpTools->getDivisionTransactionsSummary('penjualan', $my_cabang, $my_jenis);
            } elseif ($intentData['intent'] === 'executive_overview') {
                $groundingData = $erpTools->getExecutiveOverview($my_cabang, $my_jenis);
            }

            // Panduan Actionable Deep Links (Format Tautan HTML Normal)
            if ($featActive('deep_links')) {
                $baseUrlApp = base_url();
                $routes = (isset($ai_config['routes']) && is_array($ai_config['routes'])) ? $ai_config['routes'] : array();
                $poRoute = isset($routes['po_create']) ? $routes['po_create'] : 'pembelian/Create/index';
                $projekRoute = isset($routes['project_transaksi']) ? $routes['project_transaksi'] : 'master_project/Transaksi/index';
                $stockRoute = isset($routes['stock_adjust']) ? $routes['stock_adjust'] : 'inputstok/Transaksi/index';
                $custRoute = isset($routes['customer_master']) ? $routes['customer_master'] : 'statik/Data/index/MdlCustomer';

                $system_prompt .= "\n\n[PANDUAN ACTIONABLE DEEP-LINK TRANSAKSI]\n";
                $system_prompt .= "Sertakan tautan langsung HTML (tag <a> dengan target='_blank') agar staf dapat langsung mengeksekusi tindakan berikut:\n";
                $system_prompt .= "- Untuk Pengadaan / Reorder Stok Kritis/Habis: <a href=\"" . $baseUrlApp . $poRoute . "\" target=\"_blank\">🛒 Buat Pengajuan PO</a>\n";
                if ($featActive('project_management')) {
                    $system_prompt .= "- Untuk Rincian Proyek & SPK: <a href=\"" . $baseUrlApp . $projekRoute . "\" target=\"_blank\">📂 Buka Modul Proyek</a>\n";
                }
                if ($featActive('slow_moving_stock') || $featActive('stock_check')) {
                    $system_prompt .= "- Untuk Penyesuaian & Cuci Gudang Stok Macet: <a href=\"" . $baseUrlApp . $stockRoute . "\" target=\"_blank\">📦 Buka Manajemen Stok</a>\n";
                }
                if ($featActive('customer_credit')) {
                    $system_prompt .= "- Untuk Pengaturan Plafon & Profil Customer: <a href=\"" . $baseUrlApp . $custRoute . "\" target=\"_blank\">👤 Buka Master Customer</a>\n";
                }
            }

            if (!empty($groundingData)) {
                $groundingJson = json_encode($groundingData);
                $system_prompt .= "\n\n[DATA_FAKTUAL_DATABASE_INTERNAL_ERP]\n";
                $system_prompt .= "Berikut adalah data real-time resmi dari database internal " . $company_name . ". Gunakan sebagai kebenaran mutlak (Ground Truth). DILARANG keras mengarang atau mengubah angka faktual:\n";
                $system_prompt .= $groundingJson . "\n";
                $system_prompt .= "[PEDOMAN PENYAJIAN DATA (WAJIB FORMAT HTML NORMAL)]\n";
                $system_prompt .= "- DILARANG menggunakan format Markdown WhatsApp (seperti *teks tebal*, _miring_, atau garis tabel pipe |). Gunakan selalu format HTML standar langsung.\n";
                $system_prompt .= "- Gunakan tag <b> atau <strong> untuk teks tebal, <i> untuk teks miring, <p> untuk paragraf, <br> untuk baris baru, <ul>/<li> untuk daftar poin, dan <ol>/<li> untuk daftar bernomor.\n";
                $system_prompt .= "- Untuk setiap data tabular (angka, keuangan, daftar proyek, stok barang, atau transaksi), WAJIB disajikan menggunakan format tabel HTML yang rapi: <table class='ev-chat-table'><thead><tr><th>...</th></tr></thead><tbody><tr><td>...</td></tr></tbody></table>.\n";
                $system_prompt .= "- Jika data berstatus 'denied', sampaikan permohonan maaf secara sangat santun bahwa akses informasi finansial/laba konsolidasi/detail anggaran memerlukan otorisasi level Eksekutif atau Manajemen Proyek.\n";
                $system_prompt .= "- Untuk data PLAFON & KREDIT CUSTOMER, sajikan tabel customer (Kolom: Customer, Member ID, Tempo, Plafon Kredit, Piutang Berjalan, Sisa Plafon, % Terpakai, Status Kelayakan), lalu berikan rekomendasi jelas apakah order penjualan kredit disetujui atau ditahan.\n";
                $system_prompt .= "- Untuk data STOK MACET / SLOW-MOVING, sajikan tabel produk (Kolom: No, Kode, Nama Produk, Kategori, Sisa Qty, Hari Tidak Bergerak, Status Usia Stok, HPP Satuan, Total Modal Tertahan), serta sertakan saran aksi cuci gudang/promo bundling.\n";
                $system_prompt .= "- Untuk data TRANSAKSI AKTIF (Context-Aware), sajikan ringkasan transaksi (Nomor, Jenis, Nilai, Pembuat, Waktu, Evaluasi Kelayakan) dan tabel rincian item barang berserta margin laba.\n";
                $system_prompt .= "- Untuk data PROYEK, sajikan ringkasan portofolio atau rincian proyek menggunakan tabel HTML yang rapi (Kolom: No, Proyek, Customer, Nilai Kontrak, Target RAB, HPP Riil, Serapan RAB %, Laba Kotor, Margin %, Status Biaya), lalu sertakan ringkasan eksekutif kesehatan proyek.\n";
                $system_prompt .= "- Untuk data TERMIN PROYEK, tampilkan rincian customer, nama proyek, total tagihan, dibayar, dan sisa piutang dalam tabel HTML.\n";
                $system_prompt .= "- Untuk data SPK LAPANGAN, tampilkan tabel nomor SPK, nama proyek, unit pekerjaan, mandor pelaksana, dan status progres.\n";
                $system_prompt .= "- Untuk data KAS & BANK, sajikan total konsolidasi dana tunai dan rincian kas fisik vs rekening bank dalam tabel rapi.\n";
                $system_prompt .= "- Untuk data STOK KRITIS, sajikan produk yang habis atau mendekati batas MOQ agar bagian purchasing segera membuat PO.\n";
                $system_prompt .= "- Untuk data STOK BARANG, jelaskan nama produk, kode/barcode, stok fisik, stok di-hold (jika ada), dan stok efektif yang tersedia.\n";
                $system_prompt .= "- Jika data tidak ditemukan, sampaikan permohonan maaf dengan santun, jelaskan dengan ramah dan berikan saran pencarian dengan kata kunci yang lebih spesifik.\n";
                $system_prompt .= "- Jawablah dalam Bahasa Indonesia yang profesional, ramah, santun, dan rapi dalam format HTML normal.\n";
                $system_prompt .= "- Selalu akhiri respon dengan salam penutup hangat yang menawarkan bantuan lanjutan.\n";
            }

            if ($provider === 'gemini') {
                if (session_status() === PHP_SESSION_ACTIVE) { session_write_close(); }
                $ai_reply = $this->call_gemini_api($ai_config, $message, $system_prompt, $timeout, $conversationTurns);
            } elseif ($provider === 'opencode') {
                $ai_reply = $this->call_opencode_api($ai_config, $message, $system_prompt, $timeout, $conversationTurns, $ai_session_id);
            } else {
                // Groq, OpenRouter, OpenAI, Custom/Local menggunakan universal OpenAI-compatible endpoint
                if (session_status() === PHP_SESSION_ACTIVE) { session_write_close(); }
                $ai_reply = $this->call_openai_compatible_api($ai_config, $message, $system_prompt, $timeout, $conversationTurns);
            }
        }

        // 6. Simpan balasan AI ke database dengan session_id
        $botMsg = $this->MdlChat->send_message(
            'ai',
            0,
            $bot_name,
            -1,
            'Sistem AI',
            $my_id,
            $my_name,
            $ai_reply,
            null,
            $ai_session_id
        );

        // 7. Catat Rekaman Audit Trail Komprehensif (Audit Trail Chat & Sesi AI)
        $duration = round(microtime(true) - $startTime, 3);
        $isErr = (strpos($ai_reply, 'Maaf,') === 0 || strpos($ai_reply, 'Respon API') === 0 || strpos($ai_reply, 'Respon error') === 0 || strpos($ai_reply, 'Gagal terhubung') === 0 || strpos($ai_reply, 'Kendala') === 0);

        $auditData = array(
            'session_id'       => $ai_session_id,
            'user_id'          => $my_id,
            'user_name'        => $my_name,
            'user_jenis'       => $my_jenis,
            'cabang_id'        => $my_cabang,
            'cabang_nama'      => $my_cabang_nama,
            'provider'         => $provider,
            'model'            => !empty($ai_config['model']) ? $ai_config['model'] : 'default',
            'intent'           => isset($intentData['intent']) ? $intentData['intent'] : 'general',
            'user_prompt'      => $message,
            'ref_transaksi'    => !empty($ref_transaksi) ? $ref_transaksi : null,
            'grounding_data'   => !empty($groundingData) ? json_encode($groundingData) : null,
            'ai_response'      => $ai_reply,
            'duration_seconds' => $duration,
            'status'           => $isErr ? 'error' : 'success',
            'error_detail'     => $isErr ? $ai_reply : null,
            'ip_address'       => $this->input->ip_address(),
            'user_agent'       => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : '',
            'created_at'       => date('Y-m-d H:i:s')
        );
        $this->MdlChat->record_ai_audit($auditData);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'session_id' => $ai_session_id,
            'user_message' => $userMsg,
            'ai_message' => $botMsg
        ));
    }

    public function test_ai_connection()
    {
        $this->check_auth_ajax();

        $provider = $this->input->post('provider');
        $api_key = trim($this->input->post('api_key'));
        $model = trim($this->input->post('model'));
        $custom_endpoint = trim($this->input->post('custom_endpoint'));

        if (empty($provider)) {
            $provider = 'groq';
        }

        $config = array(
            'provider' => $provider,
            'api_key' => $api_key,
            'model' => $model,
            'custom_endpoint' => $custom_endpoint,
            'timeout' => 30
        );

        $saved = $this->get_effective_ai_config();

        // Jika api_key kosong pada form, coba ambil dari konfigurasi tersimpan
        if (empty($config['api_key'])) {
            $config['api_key'] = $saved['api_key'];
            if (empty($config['model'])) {
                $config['model'] = $saved['model'];
            }
        }

        if (empty($config['api_key']) && $provider !== 'opencode' && $provider !== 'custom') {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'API Key wajib diisi untuk menguji koneksi.'
            ));
            return;
        }

        $company_name = !empty($saved['company_name']) ? $saved['company_name'] : 'Everest ERP';
        $bot_name = !empty($saved['bot_name']) ? $saved['bot_name'] : 'AI Copilot';

        $prompt = "Halo! Mohon konfirmasi koneksi dengan membalas satu kalimat singkat bahwa sistem Anda siap digunakan untuk " . $company_name . ".";
        $system_prompt = "Anda adalah " . $bot_name . " untuk " . $company_name . ".";

        if ($provider === 'gemini') {
            $reply = $this->call_gemini_api($config, $prompt, $system_prompt, 20);
        } elseif ($provider === 'opencode') {
            $reply = $this->call_opencode_api($config, $prompt, $system_prompt, 30);
        } else {
            $reply = $this->call_openai_compatible_api($config, $prompt, $system_prompt, 20);
        }

        $isError = (strpos($reply, 'Maaf,') === 0 || strpos($reply, 'Respon API') === 0 || strpos($reply, 'Respon error') === 0 || strpos($reply, 'Gagal terhubung') === 0 || strpos($reply, 'Autentikasi') !== false || strpos($reply, 'Kendala') === 0);

        $reportedModel = !empty($config['model']) ? $config['model'] : (!empty($saved['model']) ? $saved['model'] : 'BP-MGK');

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => $isError ? 'error' : 'success',
            'provider' => $provider,
            'model' => $reportedModel,
            'reply' => $reply
        ));
    }

    public function get_effective_ai_config()
    {
        $st = new MdlSetting();
        $dbConfig = $st->get_all_ai_settings();

        $fileConfig = $this->config->item('ai_chat');
        if (empty($fileConfig)) {
            $fileConfig = array(
                'company_name'        => 'PT Everest Electronic',
                'bot_name'            => 'Everest AI Copilot',
                'bot_title'           => 'Enterprise ERP Business Consultant & Operational Advisor',
                'business_sector'     => 'Perdagangan, Ritel, & Distribusi Elektronik',
                'currency_symbol'     => 'Rp',
                'greeting_salutation' => 'Bapak/Ibu',
                'use_db_replica'      => true,
                'replica_db_group'    => 'ai_replica',
                'enabled'             => true,
                'provider'            => 'opencode',
                'api_key'             => 'opencode:rahasia13',
                'model'               => 'BP-MGK',
                'endpoints'           => array(
                    'opencode'   => 'http://192.168.5.10:4096',
                    'gemini'     => 'https://generativelanguage.googleapis.com/v1beta/models/',
                    'groq'       => 'https://api.groq.com/openai/v1/chat/completions',
                    'openrouter' => 'https://openrouter.ai/api/v1/chat/completions',
                    'openai'     => 'https://api.openai.com/v1/chat/completions',
                    'custom'     => 'http://localhost:11434/v1/chat/completions'
                ),
                'timeout'             => 60,
                'system_prompt'       => 'Anda adalah asisten cerdas ERP {company_name}.'
            );
        }

        // Pengaturan database meng-override pengaturan berkas jika ada isinya
        $effective = $fileConfig;
        if (!empty($dbConfig['ai_provider'])) {
            $effective['provider'] = $dbConfig['ai_provider'];
        }
        if (!empty($dbConfig['ai_api_key'])) {
            $effective['api_key'] = $dbConfig['ai_api_key'];
        }
        if (!empty($dbConfig['ai_model'])) {
            $effective['model'] = $dbConfig['ai_model'];
        }
        if (!empty($dbConfig['ai_endpoint'])) {
            $effective['custom_endpoint'] = $dbConfig['ai_endpoint'];
        }

        return $effective;
    }

    private function call_gemini_api($config, $prompt, $system_prompt, $timeout, $conversationTurns = array())
    {
        $api_key = $config['api_key'];
        $model = !empty($config['model']) ? $config['model'] : 'gemini-1.5-flash';
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/';
        $url = $endpoint . $model . ':generateContent?key=' . $api_key;

        $contents = array();
        $company_name = !empty($config['company_name']) ? $config['company_name'] : 'Everest ERP';

        // 1. Konteks instruksi sistem awal
        $contents[] = array(
            'role' => 'user',
            'parts' => array(array('text' => "[INSTRUKSI SISTEM & PEDOMAN OPERASIONAL]\n" . $system_prompt . "\n\nKonfirmasi bahwa Anda memahami instruksi dan siap mendampingi operasional."))
        );
        $contents[] = array(
            'role' => 'model',
            'parts' => array(array('text' => "Saya memahami seluruh instruksi sistem ERP " . $company_name . " dan siap mendampingi operasional."))
        );

        // 2. Masukkan riwayat multi-turn sebelumnya dalam sesi ini
        if (!empty($conversationTurns) && is_array($conversationTurns)) {
            foreach ($conversationTurns as $turn) {
                $gRole = ($turn['role'] === 'assistant') ? 'model' : 'user';
                $contents[] = array(
                    'role' => $gRole,
                    'parts' => array(array('text' => $turn['content']))
                );
            }
        }

        // 3. Pertanyaan pengguna saat ini
        $currentPrompt = "Pertanyaan pengguna (" . my_name() . ", " . my_cabang_nama() . "):\n" . $prompt;
        $contents[] = array(
            'role' => 'user',
            'parts' => array(array('text' => $currentPrompt))
        );

        $postData = array(
            'contents' => $contents
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return "Maaf, terjadi kendala saat menghubungi server Google Gemini: " . $err;
        }

        $resJson = json_decode($response, true);
        if (isset($resJson['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($resJson['candidates'][0]['content']['parts'][0]['text']);
        } elseif (isset($resJson['error']['message'])) {
            return "Respon API Gemini: " . $resJson['error']['message'];
        }

        return "Maaf, tidak menerima jawaban dari asisten Google Gemini. Silakan coba kembali.";
    }

    private function call_openai_compatible_api($config, $prompt, $system_prompt, $timeout, $conversationTurns = array())
    {
        $provider = isset($config['provider']) ? strtolower($config['provider']) : 'groq';
        $api_key = isset($config['api_key']) ? $config['api_key'] : '';

        // Tentukan endpoint & default model sesuai provider
        $endpoint = '';
        $defaultModel = 'llama-3.3-70b-versatile';

        if ($provider === 'groq') {
            $endpoint = 'https://api.groq.com/openai/v1/chat/completions';
            $defaultModel = 'llama-3.3-70b-versatile';
        } elseif ($provider === 'openrouter') {
            $endpoint = 'https://openrouter.ai/api/v1/chat/completions';
            $defaultModel = 'meta-llama/llama-3.3-70b-instruct:free';
        } elseif ($provider === 'openai') {
            $endpoint = 'https://api.openai.com/v1/chat/completions';
            $defaultModel = 'gpt-4o-mini';
        } else {
            // Custom / Local Ollama
            $endpoint = !empty($config['custom_endpoint']) ? $config['custom_endpoint'] : 'http://localhost:11434/v1/chat/completions';
            $defaultModel = 'llama3.1';
        }

        $model = !empty($config['model']) ? $config['model'] : $defaultModel;

        $messages = array();
        $messages[] = array('role' => 'system', 'content' => $system_prompt);

        if (!empty($conversationTurns) && is_array($conversationTurns)) {
            foreach ($conversationTurns as $turn) {
                $turnRole = ($turn['role'] === 'assistant') ? 'assistant' : 'user';
                $messages[] = array(
                    'role' => $turnRole,
                    'content' => $turn['content']
                );
            }
        }

        $messages[] = array(
            'role' => 'user',
            'content' => "Pertanyaan pengguna (" . my_name() . ", " . my_cabang_nama() . "):\n" . $prompt
        );

        $postData = array(
            'model' => $model,
            'messages' => $messages
        );

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        );

        // Header khusus untuk OpenRouter
        if ($provider === 'openrouter') {
            $botTitle = !empty($config['bot_name']) ? $config['bot_name'] : 'ERP Copilot';
            $headers[] = 'HTTP-Referer: ' . base_url();
            $headers[] = 'X-Title: ' . $botTitle;
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return "Maaf, terjadi kendala saat menghubungi server AI (" . strtoupper($provider) . "): " . $err;
        }

        $resJson = json_decode($response, true);
        if (isset($resJson['choices'][0]['message']['content'])) {
            return trim($resJson['choices'][0]['message']['content']);
        } elseif (isset($resJson['error']['message'])) {
            return "Respon API (" . strtoupper($provider) . "): " . $resJson['error']['message'];
        }

        return "Maaf, tidak menerima jawaban dari server AI (" . strtoupper($provider) . "). Silakan periksa kunci API dan kuota Anda.";
    }

    /**
     * Gateway untuk Server AI Lokal / On-Premise
     * Menggunakan protokol REST session native (POST /session -> POST /session/{id}/message)
     * dengan dukungan Sesi Persisten per User dan Pemulihan Otomatis jika Server Restart.
     *
     * @param array $config
     * @param string $prompt
     * @param string $system_prompt
     * @param int $timeout
     * @param array $conversationTurns
     * @param string $ai_session_id
     * @return string
     */
    private function call_opencode_api($config, $prompt, $system_prompt, $timeout, $conversationTurns = array(), $ai_session_id = '')
    {
        $baseEndpoint = !empty($config['custom_endpoint']) ? trim($config['custom_endpoint']) : 'http://192.168.5.10:4096';
        $baseEndpoint = rtrim($baseEndpoint, '/');
        $apiKey = isset($config['api_key']) ? trim($config['api_key']) : '';
        $model = !empty($config['model']) ? trim($config['model']) : 'BP-MGK';

        // Tentukan kredensial autentikasi HTTP
        $userPwd = '';
        $authHeader = '';
        if (!empty($apiKey)) {
            if (strpos($apiKey, ':') !== false) {
                $userPwd = $apiKey;
            } else {
                $authHeader = 'Authorization: Bearer ' . $apiKey;
            }
        } else {
            $userPwd = 'opencode:rahasia13';
        }

        $headers = array('Content-Type: application/json', 'Expect:');
        if (!empty($authHeader)) {
            $headers[] = $authHeader;
        }

        $botTitle = !empty($config['bot_name']) ? $config['bot_name'] : 'AI Copilot';

        // Cek apakah user sudah memiliki sesi OpenCode native aktif di $_SESSION
        $nativeSessionId = isset($_SESSION['login']['opencode_native_session_id']) ? $_SESSION['login']['opencode_native_session_id'] : '';
        $isNewSessionCreated = false;

        // 1. Jika belum ada native session, buat session baru di OpenCode (POST /session)
        if (empty($nativeSessionId)) {
        $sessionUrl = $baseEndpoint . '/session';
        $chSession = curl_init($sessionUrl);
        curl_setopt($chSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chSession, CURLOPT_POST, true);
            curl_setopt($chSession, CURLOPT_POSTFIELDS, json_encode(array('title' => $botTitle . ' - ' . my_name())));
        curl_setopt($chSession, CURLOPT_HTTPHEADER, $headers);
        if (!empty($userPwd)) {
            curl_setopt($chSession, CURLOPT_USERPWD, $userPwd);
        }
        curl_setopt($chSession, CURLOPT_TIMEOUT, 15);
        curl_setopt($chSession, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($chSession, CURLOPT_SSL_VERIFYPEER, false);

        $sessionRes = curl_exec($chSession);
        $sessionHttpCode = curl_getinfo($chSession, CURLINFO_HTTP_CODE);
        $sessionErr = curl_error($chSession);
        curl_close($chSession);

        if ($sessionErr || ($sessionHttpCode !== 200 && $sessionHttpCode !== 201)) {
            if ($sessionHttpCode === 401) {
                    return "Autentikasi Server AI (BP-MGK) Gagal (401 Unauthorized). Silakan periksa kredensial pada pengaturan.";
            }
            if ($sessionErr) {
                    return "Gagal terhubung ke Server AI (BP-MGK). Kendala jaringan koneksi.";
            }
                return "Respon error dari Server AI (BP-MGK) [HTTP {$sessionHttpCode}].";
        }

        $sessionData = json_decode($sessionRes, true);
        if (isset($sessionData['id'])) {
                $nativeSessionId = $sessionData['id'];
        } elseif (isset($sessionData['sessionID'])) {
                $nativeSessionId = $sessionData['sessionID'];
            }

            if (empty($nativeSessionId)) {
                return "Gagal membuat sesi di Server AI (BP-MGK): ID sesi tidak ditemukan dalam respon server.";
            }

            $_SESSION['login']['opencode_native_session_id'] = $nativeSessionId;
            $isNewSessionCreated = true;
        }

        // Tutup sesi setelah variabel disimpan agar AJAX lain tidak menunggu
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Siapkan prompt teks lengkap
        // Jika sesi baru dibuat tetapi ada percakapan sebelumnya di DB (misal habis restart server),
        // sematkan riwayat agar memori tidak terputus
        $historyText = '';
        if ($isNewSessionCreated && !empty($conversationTurns) && is_array($conversationTurns)) {
            $historyText = "\n[MEMORI PERCAKAPAN SEBELUMNYA DALAM SESI INI]\n";
            foreach ($conversationTurns as $cTurn) {
                $cRole = ($cTurn['role'] === 'assistant') ? 'Asisten AI' : my_name();
                $historyText .= "- " . $cRole . ": " . $cTurn['content'] . "\n";
            }
            $historyText .= "[LANJUTAN PERCAKAPAN SEKARANG]\n";
        }

        $fullPrompt = $system_prompt . "\n" . $historyText . "\nPertanyaan pengguna (" . my_name() . ", " . my_cabang_nama() . "):\n" . $prompt;

        // 2. Siapkan Payload Pesan (POST /session/{id}/message)
        $msgPayload = array(
            'system' => $system_prompt,
            'parts' => array(
                array(
                    'type' => 'text',
                    'text' => $fullPrompt
                )
            )
        );

        if (!empty($model) && $model !== 'default') {
            $providerID = 'opencode';
            // BP-MGK adalah alias pengguna untuk model default server (big-pickle)
            $modelID = ($model === 'BP-MGK') ? 'big-pickle' : $model;
            if (strpos($model, '/') !== false) {
                $mParts = explode('/', $model, 2);
                $providerID = $mParts[0];
                $modelID = $mParts[1];
            } elseif (strpos($model, 'nemotron-mini') !== false) {
                $providerID = 'ollama';
                $modelID = $model;
            }
            $msgPayload['model'] = array(
                'providerID' => $providerID,
                'modelID'    => $modelID
            );
        }

        $msgUrl = $baseEndpoint . '/session/' . rawurlencode($nativeSessionId) . '/message';
        $chMsg = curl_init($msgUrl);
        curl_setopt($chMsg, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chMsg, CURLOPT_POST, true);
        curl_setopt($chMsg, CURLOPT_POSTFIELDS, json_encode($msgPayload));
        curl_setopt($chMsg, CURLOPT_HTTPHEADER, $headers);
        if (!empty($userPwd)) {
            curl_setopt($chMsg, CURLOPT_USERPWD, $userPwd);
        }
        curl_setopt($chMsg, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($chMsg, CURLOPT_SSL_VERIFYPEER, false);

        $msgRes = curl_exec($chMsg);
        $msgHttpCode = curl_getinfo($chMsg, CURLINFO_HTTP_CODE);
        $msgErr = curl_error($chMsg);
        curl_close($chMsg);

        // Jika sesi lama sudah kedaluwarsa / 404 di server OpenCode, hapus cache sesi dan coba re-create 1 kali
        if ($msgHttpCode === 404 && !$isNewSessionCreated) {
            unset($_SESSION['login']['opencode_native_session_id']);
            return $this->call_opencode_api($config, $prompt, $system_prompt, $timeout, $conversationTurns, $ai_session_id);
        }

        if ($msgErr) {
            return "Kendala saat menunggu balasan Server AI (BP-MGK): " . preg_replace('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', 'server', $msgErr);
        }

        if ($msgHttpCode !== 200 && $msgHttpCode !== 201) {
            return "Server AI (BP-MGK) mengembalikan respon HTTP {$msgHttpCode}. Silakan periksa status server.";
        }

        $msgJson = json_decode($msgRes, true);
        if (is_array($msgJson)) {
            // Deteksi jika server mengembalikan objek error terstruktur
            $errDetail = '';
            if (isset($msgJson['info']['error']['data']['message'])) {
                $errDetail = $msgJson['info']['error']['data']['message'];
            } elseif (isset($msgJson['info']['error']['message'])) {
                $errDetail = $msgJson['info']['error']['message'];
            } elseif (isset($msgJson['error']['message'])) {
                $errDetail = $msgJson['error']['message'];
            } elseif (isset($msgJson['error']) && is_string($msgJson['error'])) {
                $errDetail = $msgJson['error'];
            }

            if (!empty($errDetail)) {
                // Bersihkan nama teknis opencode/big-pickle, path file, URL, dan IP dari pesan error
                $cleanedErr = preg_replace('/opencode/i', 'BP-MGK', $errDetail);
                $cleanedErr = preg_replace('/big-pickle/i', 'BP-MGK', $cleanedErr);
                $cleanedErr = preg_replace('/https?:\/\/[^\s\)\"]+/i', '', $cleanedErr);
                $cleanedErr = preg_replace('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(:\d+)?/', '', $cleanedErr);
                $cleanedErr = trim($cleanedErr);

                return "Maaf, Server AI (BP-MGK) sedang mengalami kendala pada penyedia model:<br><b>" . htmlspecialchars($cleanedErr, ENT_QUOTES, 'UTF-8') . "</b>.<br>Silakan hubungi administrator sistem untuk pembaruan atau perbaikan layanan server.";
            }

            // Ekstrak dari bagian parts (type = 'text')
            if (isset($msgJson['parts']) && is_array($msgJson['parts'])) {
                $textParts = array();
                foreach ($msgJson['parts'] as $p) {
                    if (isset($p['type']) && $p['type'] === 'text' && !empty($p['text'])) {
                        $textParts[] = $p['text'];
                    }
                }
                if (!empty($textParts)) {
                    return trim(implode("\n", $textParts));
                }
            }

            if (isset($msgJson['text']) && !empty($msgJson['text'])) {
                return trim($msgJson['text']);
            }
            if (isset($msgJson['content']) && !empty($msgJson['content'])) {
                return trim($msgJson['content']);
            }
            if (isset($msgJson['choices'][0]['message']['content'])) {
                return trim($msgJson['choices'][0]['message']['content']);
            }
        }

        // Jangan pernah membocorkan raw string JSON ke pengguna
        return "Maaf, Server AI (BP-MGK) tidak mengembalikan teks jawaban yang valid. Silakan coba tanyakan kembali beberapa saat lagi.";
    }

    /**
     * Mereset sesi percakapan AI pengguna aktif ke sesi baru
     */
    public function reset_ai_session()
    {
        $this->check_auth_ajax(false);

        $my_id = (int)my_id();
        $new_ai_session_id = 'ai_sess_' . $my_id . '_' . date('Ymd_His') . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 8);

        $_SESSION['login']['ai_session_id'] = $new_ai_session_id;
        unset($_SESSION['login']['opencode_native_session_id']);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'session_id' => $new_ai_session_id,
            'message' => 'Sesi percakapan AI berhasil direset ke sesi baru.'
        ));
        }

    /**
     * Menampilkan data log Audit Trail percakapan & sesi AI untuk pengawasan eksekutif
     */
    public function ai_audit_trail()
    {
        $this->check_auth_ajax();

        $my_cabang = (int)my_cabang_id();
        $is_admin = (isset($this->session->login['is_admin']) && $this->session->login['is_admin'] == 1) ? 1 : 0;
        $my_jenis = isset($this->session->login['jenis']) ? strtolower($this->session->login['jenis']) : 'staff';
        $is_authorized = ($is_admin == 1 || $my_cabang == -1 || in_array($my_jenis, array('supervisor', 'manager', 'direktur', 'executive')));

        if (!$is_authorized) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Akses ditolak. Log Audit Trail AI hanya dapat diakses oleh Manajemen / Administrator.'
            ));
            return;
        }

        $filters = array();
        $sId = $this->input->get_post('session_id');
        if (!empty($sId)) {
            $filters['session_id'] = trim($sId);
        }
        $uId = $this->input->get_post('user_id');
        if (!empty($uId)) {
            $filters['user_id'] = (int)$uId;
        }
        $cId = $this->input->get_post('cabang_id');
        if (!empty($cId)) {
            $filters['cabang_id'] = (int)$cId;
        }
        $stat = $this->input->get_post('status');
        if (!empty($stat)) {
            $filters['status'] = trim($stat);
        }
        $intent = $this->input->get_post('intent');
        if (!empty($intent)) {
            $filters['intent'] = trim($intent);
        }
        $df = $this->input->get_post('date_from');
        if (!empty($df)) {
            $filters['date_from'] = trim($df);
        }
        $dt = $this->input->get_post('date_to');
        if (!empty($dt)) {
            $filters['date_to'] = trim($dt);
        }

        $limit = (int)$this->input->get_post('limit');
        if ($limit <= 0) {
            $limit = 50;
        }
        $offset = (int)$this->input->get_post('offset');
        if ($offset < 0) {
            $offset = 0;
        }

        $result = $this->MdlChat->get_ai_audit_trail($filters, $limit, $offset);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'total'  => $result['total'],
            'limit'  => $limit,
            'offset' => $offset,
            'data'   => $result['rows']
        ));
    }

    public function unread_count()
    {
        $this->check_auth_ajax();

        $my_id = (int)my_id();
        $my_cabang = (int)my_cabang_id();

        $summary = $this->MdlChat->get_all_unread_counts($my_id, $my_cabang);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'data' => $summary
        ));
    }

    public function toggle_presence_mode()
    {
        if (!isset($this->session->login['id']) || empty($this->session->login['id'])) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Sesi login telah berakhir'
            ));
            return;
        }

        $my_id = (int)$this->session->login['id'];
        $current_stealth = isset($this->session->login['is_stealth']) ? (int)$this->session->login['is_stealth'] : 0;
        $new_stealth = ($current_stealth == 1) ? 0 : 1;

        // Update session login
        $loginData = $this->session->userdata('login');
        $loginData['is_stealth'] = $new_stealth;
        $this->session->set_userdata('login', $loginData);

        $new_status_login = ($new_stealth == 1) ? '0' : '1';
        $this->db->query("UPDATE per_employee SET status_login = ?, last_dtime_active = NOW() WHERE id = ?", array($new_status_login, $my_id));

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'is_stealth' => $new_stealth,
            'message' => ($new_stealth == 1) ? 'Mode Senyap aktif (Status Anda disembunyikan / tampak offline)' : 'Mode Online aktif (Status Anda terlihat online oleh rekan kerja)'
        ));
    }

    public function get_contacts()
    {
        $this->check_auth_ajax();

        $my_id = (int)my_id();
        $my_cabang = (int)my_cabang_id();
        $cabangs = $this->MdlChat->get_active_cabang_list();
        $users = $this->MdlChat->get_active_users($my_id);
        $snippets = $this->MdlChat->get_latest_snippets($my_id);
        $is_stealth = isset($this->session->login['is_stealth']) ? (int)$this->session->login['is_stealth'] : 0;

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'cabang' => $cabangs,
            'users' => $users,
            'snippets' => $snippets,
            'my_id' => $my_id,
            'my_cabang_id' => $my_cabang,
            'is_stealth' => $is_stealth
        ));
    }
}

// END OF COMPLETE REPEATED LOGIC
