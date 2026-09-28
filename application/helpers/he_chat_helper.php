<?php
// START OF COMPLETE REPEATED LOGIC

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Helper Chatbox & AI Copilot Hub (Two-Pane Messenger)
 * Menyediakan antarmuka messenger enterprise modern (Two-Pane Layout):
 * - Panel Kiri: Daftar kontak visual, pencarian instan, filter pills, pinned AI Copilot, indikator online 🟢/⚪, unread badges.
 * - Panel Kanan: Jendela obrolan bergaya bubble chat (#d9fdd3 / #ffffff), tanda centang ✓✓, stempel waktu, dan lampiran transaksi.
 * - Dukungan Mode Senyap (Stealth / Appear Offline) untuk level Eksekutif.
 * - Responsif Mobile (< 768px) dengan transisi slide halus dan tombol kembali [←].
 * - Proteksi anti-iframe, deduplikasi ID pesan mutlak, dan non-blocking polling session.
 */

if (!function_exists('render_chatbox_widget')) {
    function render_chatbox_widget()
    {
        // 1. Guard agar widget hanya di-render satu kali per siklus request halaman
        static $is_rendered = false;
        if ($is_rendered) {
            return "";
        }

        $CI = &get_instance();

        // 2. Hanya aktif jika pengguna sedang login
        if (!isset($CI->session->login['id']) || empty($CI->session->login['id'])) {
            return "";
        }

        $is_rendered = true;

        $my_id = (int)my_id();
        $my_name = htmlspecialchars(my_name(), ENT_QUOTES, 'UTF-8');
        $my_cabang_id = (int)my_cabang_id();
        $my_cabang_nama = htmlspecialchars(my_cabang_nama(), ENT_QUOTES, 'UTF-8');
        $is_center = ($my_cabang_id == -1) ? 1 : 0;
        $base = base_url();

        // Inisial nama saya untuk avatar profil
        $words = preg_split('/\s+/', trim(my_name()));
        $my_initials = '';
        if (count($words) >= 2) {
            $my_initials = strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1));
        } elseif (count($words) == 1 && strlen($words[0]) > 0) {
            $my_initials = strtoupper(substr($words[0], 0, 2));
        } else {
            $my_initials = 'ME';
        }

        // Baca branding terpusat dari config ai_chat.php
        $CI->load->config('ai_chat', TRUE);
        $ai_cfg = $CI->config->item('ai_chat');
        $bot_name = !empty($ai_cfg['bot_name']) ? htmlspecialchars($ai_cfg['bot_name'], ENT_QUOTES, 'UTF-8') : 'Everest AI Copilot';
        $company_name = !empty($ai_cfg['company_name']) ? htmlspecialchars($ai_cfg['company_name'], ENT_QUOTES, 'UTF-8') : 'PT. Everest Megantara';

        // Deteksi hak akses eksekutif (Pusat atau Admin) untuk fitur Stealth Mode (Appear Offline)
        $is_admin = (isset($CI->session->login['is_admin']) && $CI->session->login['is_admin'] == 1) ? 1 : 0;
        $is_executive = ($is_center == 1 || $is_admin == 1) ? 1 : 0;
        $is_stealth = (isset($CI->session->login['is_stealth']) && $CI->session->login['is_stealth'] == 1) ? 1 : 0;

        // Resolusi Hak Akses & Peran Pengguna untuk AI Quick Prompts Chips (Hanya tampilkan menu yang diizinkan)
        $raw_jenis = isset($CI->session->login['jenis']) ? strtolower(trim($CI->session->login['jenis'])) : '';
        $emp_type = isset($CI->session->login['employee_type']) ? strtolower(trim($CI->session->login['employee_type'])) : '';
        $my_memberships = (isset($CI->session->login['membership']) && is_array($CI->session->login['membership'])) ? $CI->session->login['membership'] : array();

        $execRoles = array('superman', 'admin', 'super_admin', 'administrator', 'direksi', 'owner', 'c_level', 'komisaris');
        $has_exec_membership = (in_array('c_owner', $my_memberships) || in_array('root', $my_memberships) || in_array('superman', $my_memberships));
        $is_ai_executive = ($is_admin == 1 || $emp_type === 'root' || in_array($raw_jenis, $execRoles) || $has_exec_membership);

        $spvRoles = array('spv_penj', 'spv', 'supervisor', 'head', 'manager', 'kabag', 'accounting', 'finance', 'audit', 'keuangan');
        $has_spv_membership = false;
        foreach ($my_memberships as $mGroup) {
            if (strpos($mGroup, '_spv') !== false || strpos($mGroup, '_mgr') !== false || in_array($mGroup, array('c_laporan', 'c_audit', 'o_audit'))) {
                $has_spv_membership = true;
                break;
            }
        }
        $is_ai_supervisor = ($is_ai_executive || in_array($raw_jenis, $spvRoles) || $has_spv_membership);

        // Identifikasi Domain Kerja Pengguna
        $is_finance_dept = false;
        $is_warehouse_dept = false;
        $is_sales_dept = false;
        $is_project_dept = false;

        foreach ($my_memberships as $mGroup) {
            if (strpos($mGroup, 'finance') !== false || in_array($raw_jenis, array('finance', 'accounting', 'keuangan'))) {
                $is_finance_dept = true;
            }
            if (strpos($mGroup, 'gudang') !== false || strpos($mGroup, 'purchasing') !== false || in_array($raw_jenis, array('spv_gudang', 'gudang', 'purchasing')) || $emp_type === 'gudang') {
                $is_warehouse_dept = true;
            }
            if (strpos($mGroup, 'seller') !== false || strpos($mGroup, 'kasir') !== false || in_array($raw_jenis, array('seller', 'kasir_penj', 'kasir_in'))) {
                $is_sales_dept = true;
            }
            if (strpos($mGroup, 'project') !== false || in_array($raw_jenis, array('pm', 'project', 'site_manager'))) {
                $is_project_dept = true;
            }
        }

        // Susun Saran Prompt Cepat Sesuai Hak Akses RBAC (Hanya fitur/menu yang diizinkan untuk role user ini)
        $chips_items = array();

        if ($is_ai_executive) {
            // Level 1: Eksekutif (Direksi, Owner, Super Admin)
            $chips_items[] = array('type' => 'quick', 'label' => '💰 Kas &amp; Bank', 'prompt' => 'Berapa posisi kas dan saldo seluruh bank kita hari ini?');
            $chips_items[] = array('type' => 'quick', 'label' => '🏢 Omset Konsolidasi', 'prompt' => 'Tampilkan rekap omset penjualan konsolidasi bulan ini');
            $chips_items[] = array('type' => 'quick', 'label' => '⚖️ Piutang vs Hutang', 'prompt' => 'Bagaimana perbandingan piutang vs hutang supplier?');
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '⏳ Stok Macet', 'prompt' => 'Tampilkan daftar stok macet atau dead stock gudang');
            $chips_items[] = array('type' => 'quick', 'label' => '📂 Portofolio Proyek', 'prompt' => 'Bagaimana ikhtisar portofolio dan laba seluruh proyek?');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
        } elseif ($is_ai_supervisor) {
            // Level 2: Supervisor / Manager / Kepala Bagian
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'input', 'label' => '📑 Plafon Customer', 'prompt' => 'Cek plafon dan riwayat piutang customer: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⏳ Stok Macet', 'prompt' => 'Tampilkan daftar stok macet atau dead stock di gudang');
            $chips_items[] = array('type' => 'quick', 'label' => '📊 Rekap Penjualan', 'prompt' => 'Tampilkan rekap transaksi penjualan cabang hari ini');
            if ($is_project_dept) {
                $chips_items[] = array('type' => 'quick', 'label' => '📂 Status SPK Proyek', 'prompt' => 'Tampilkan daftar status SPK dan tugas proyek pending');
            }
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
        } elseif ($is_project_dept) {
            // Level 3: Proyek & Lapangan
            $chips_items[] = array('type' => 'quick', 'label' => '📂 Status SPK Proyek', 'prompt' => 'Tampilkan daftar status SPK dan tugas proyek pending');
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Material', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Distribusi Proyek', 'prompt' => 'Bagaimana langkah pengajuan material dan distribusi untuk proyek lapangan?');
        } elseif ($is_warehouse_dept) {
            // Level 4: Gudang & Logistik / Purchasing
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis (Reorder)', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '⏳ Stok Macet (Dead Stock)', 'prompt' => 'Tampilkan daftar stok macet atau dead stock di gudang');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
            $chips_items[] = array('type' => 'quick', 'label' => '💡 Penerimaan Barang', 'prompt' => 'Bagaimana alur penerimaan barang dari supplier dan mutasi antar gudang?');
        } elseif ($is_finance_dept) {
            // Level 5: Staf Keuangan & Pembukuan (contoh: user indah)
            $chips_items[] = array('type' => 'input', 'label' => '📑 Plafon &amp; Piutang', 'prompt' => 'Cek plafon dan riwayat piutang customer: ');
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '⏳ Stok Macet Gudang', 'prompt' => 'Tampilkan daftar stok macet atau dead stock gudang');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
            $chips_items[] = array('type' => 'quick', 'label' => '💡 Alur Piutang', 'prompt' => 'Bagaimana alur transaksi penjualan kredit dan penagihan piutang di Everest ERP?');
        } elseif ($is_sales_dept) {
            // Level 6: Sales & Kasir Penjualan
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'input', 'label' => '📑 Plafon &amp; Kredit', 'prompt' => 'Cek plafon dan riwayat piutang customer: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
            $chips_items[] = array('type' => 'quick', 'label' => '💡 Ketentuan Diskon', 'prompt' => 'Bagaimana ketentuan pemberian diskon dan syarat kredit konsumen di Everest ERP?');
        } else {
            // Level 7: Staf Operasional Umum
            $chips_items[] = array('type' => 'input', 'label' => '📦 Cek Stok Barang', 'prompt' => 'Cari stok produk: ');
            $chips_items[] = array('type' => 'input', 'label' => '📑 Plafon Customer', 'prompt' => 'Cek plafon dan riwayat piutang customer: ');
            $chips_items[] = array('type' => 'quick', 'label' => '⚠️ Stok Kritis', 'prompt' => 'Tampilkan stok kritis yang harus segera di-reorder');
            $chips_items[] = array('type' => 'quick', 'label' => '⏳ Stok Macet', 'prompt' => 'Tampilkan daftar stok macet atau dead stock di gudang');
            $chips_items[] = array('type' => 'quick', 'label' => '❓ SOP Batal/Retur', 'prompt' => 'Jelaskan langkah SOP proses retur atau pembatalan transaksi di Everest ERP');
        }

        $chips_html = '';
        foreach ($chips_items as $cItem) {
            $func = ($cItem['type'] === 'input') ? 'evChatPromptInput' : 'evChatQuickPrompt';
            $jsParam = str_replace("'", "\\'", $cItem['prompt']);
            $chips_html .= '<button type="button" class="ev-chip-btn" onclick="' . $func . '(\'' . $jsParam . '\')">' . $cItem['label'] . '</button>' . "\n                ";
        }

        $html = "
<!-- START ERP MESSENGER & AI HUB WIDGET -->
<style>
/* ==========================================================================
   ERP MESSENGER STYLES (TWO-PANE LAYOUT)
   ========================================================================== */
#chat_ai_ds,
#chat_ai_mb {
    display: inline !important;
}

#chat_ai_ds #ev-chat-trigger,
#chat_ai_mb #ev-chat-trigger,
.btn__trigger-bawah-ai-ds {
    position: relative !important;
    top: auto !important;
    bottom: auto !important;
    left: auto !important;
    right: auto !important;
    margin: 0 !important;
    width: 36px !important;
    height: 36px !important;
    padding: 0 !important;
    border-radius: 50px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
    background: #008069 !important;
    color: #ffffff !important;
    font-size: 1.4rem !important;
    flex-shrink: 0 !important;
    border: none !important;
    cursor: pointer !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease !important;
}

#chat_ai_ds #ev-chat-trigger:hover,
#chat_ai_mb #ev-chat-trigger:hover,
.btn__trigger-bawah-ai-ds:hover {
    transform: scale(1.08) !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4) !important;
    background: #00a884 !important;
}

#chat_ai_ds #ev-chat-trigger span.ev-chat-label,
#chat_ai_mb #ev-chat-trigger span.ev-chat-label,
.btn__trigger-bawah-ai-ds span.ev-chat-label {
    display: none !important;
}

#chat_ai_ds #ev-chat-trigger #ev-chat-badge,
#chat_ai_mb #ev-chat-trigger #ev-chat-badge,
.btn__trigger-bawah-ai-ds #ev-chat-badge {
    position: absolute !important;
    top: -5px !important;
    right: -5px !important;
    background: #dd4b39 !important;
    color: #ffffff !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
    line-height: 1 !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
}

/* Standalone fallback jika halaman tidak memuat navbar-2nd */
body > #ev-chat-trigger {
    position: fixed !important;
    bottom: 28px !important;
    right: 9rem !important;
    width: 3rem !important;
    height: 3rem !important;
    padding: 0 !important;
    margin: 0 !important;
    border-radius: 50px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 99998 !important;
    background: #008069 !important;
    color: #ffffff !important;
    box-shadow: 0 1px 3px #444, 0 1px 2px #444 !important;
    font-size: 1.4rem !important;
    border: none !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
}
body > #ev-chat-trigger span.ev-chat-label {
    display: none !important;
}
body > #ev-chat-trigger #ev-chat-badge {
    position: absolute !important;
    top: -5px !important;
    right: -5px !important;
    background: #dd4b39 !important;
    color: #ffffff !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
}

#ev-chat-badge {
    background: #dd4b39 !important;
    color: #ffffff !important;
    font-size: 11px !important;
    font-weight: bold !important;
    padding: 2px 7px !important;
    border-radius: 10px !important;
    display: none;
}

/* WINDOW CONTAINER */
#ev-chat-window {
    position: fixed !important;
    bottom: 52px !important;
    right: 20px !important;
    width: 820px;
    height: 580px;
    max-width: 96vw;
    max-height: 88vh;
    z-index: 99999 !important;
    display: none;
    flex-direction: column;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 16px 42px rgba(0, 0, 0, 0.35);
    border: 1px solid #cbd5e1;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    transition: box-shadow 0.2s ease;
}
#ev-chat-window.ev-chat-expanded {
    width: 1040px !important;
    height: 720px !important;
    max-width: 98vw !important;
    max-height: 94vh !important;
}
#ev-chat-window.ev-chat-docked {
    top: 0 !important;
    right: 0 !important;
    left: auto !important;
    bottom: 0 !important;
    height: 100vh !important;
    max-height: 100vh !important;
    max-width: 95vw !important;
    border-radius: 0 !important;
    box-shadow: -6px 0 28px rgba(15, 23, 42, 0.22) !important;
    border-top: none !important;
    border-right: none !important;
    border-bottom: none !important;
    border-left: 3px solid #94a3b8 !important;
    width: 620px; /* Lebar default lapang, bisa di-resize bebas */
    min-width: 380px;
}
#ev-chat-window.ev-chat-docked .ev-wa-sidebar {
    width: 230px;
    min-width: 200px;
    max-width: 260px;
}
#ev-chat-window.ev-sidebar-collapsed .ev-wa-sidebar {
    display: none !important;
}
#ev-chat-window.ev-sidebar-collapsed .ev-wa-main {
    width: 100% !important;
    flex: 1 !important;
}
#ev-chat-resizer {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 8px;
    cursor: ew-resize;
    z-index: 10000;
    background: transparent;
    transition: background 0.15s ease;
}
#ev-chat-resizer:hover, #ev-chat-resizer.is-resizing {
    background: #059669;
    box-shadow: -2px 0 8px rgba(5, 150, 105, 0.4);
}

/* TOP APP BAR */
.ev-wa-window-header {
    background: linear-gradient(135deg, #064e3b 0%, #047857 100%);
    color: #ffffff;
    padding: 8px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #065f46;
    user-select: none;
}
.ev-wa-brand {
    font-size: 13.5px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 0.3px;
}
.ev-wa-brand i {
    font-size: 15px;
    color: #34d399;
}
.ev-wa-window-tools {
    display: flex;
    align-items: center;
    gap: 4px;
}
.ev-wa-window-tools button {
    background: transparent;
    border: none;
    color: #e2e8f0;
    padding: 4px 6px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12.5px;
    transition: background 0.15s ease, color 0.15s ease;
}
.ev-wa-window-tools button:hover {
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff;
}

/* TWO-PANE BODY CONTAINER */
.ev-wa-container {
    flex: 1;
    display: flex;
    overflow: hidden;
    position: relative;
    background: #ffffff;
}

/* LEFT PANE: SIDEBAR CONTACTS */
.ev-wa-sidebar {
    width: 280px;
    min-width: 240px;
    max-width: 330px;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    height: 100%;
    flex-shrink: 0;
}

/* User profile header inside sidebar */
.ev-wa-profile-bar {
    background: #f0f2f5;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
}
.ev-wa-my-info {
    display: flex;
    align-items: center;
    gap: 9px;
    overflow: hidden;
}
.ev-wa-my-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #008069;
    color: #ffffff;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}
.ev-wa-my-text {
    overflow: hidden;
    line-height: 1.25;
}
.ev-wa-my-name {
    font-size: 12.5px;
    font-weight: bold;
    color: #111b21;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ev-wa-my-sub {
    font-size: 10.5px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Stealth toggle button (for executives) */
.ev-wa-stealth-btn {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 14px;
    font-size: 10.5px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.ev-wa-stealth-btn.online {
    color: #166534;
    border-color: #86efac;
    background: #f0fdf4;
}
.ev-wa-stealth-btn.stealth {
    color: #64748b;
    border-color: #cbd5e1;
    background: #f8fafc;
}

/* Search Box */
.ev-wa-search-wrap {
    padding: 8px 10px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.ev-wa-search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f2f5;
    border-radius: 8px;
    padding: 5px 10px;
    border: 1px solid transparent;
    transition: border-color 0.2s;
}
.ev-wa-search-box:focus-within {
    border-color: #008069;
    background: #ffffff;
}
.ev-wa-search-box i {
    color: #64748b;
    font-size: 12px;
}
#ev-wa-search-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 11.5px;
    color: #1e293b;
}
#ev-wa-search-clear {
    cursor: pointer;
    color: #94a3b8;
    font-size: 12px;
}
#ev-wa-search-clear:hover {
    color: #475569;
}

/* Filter Pills */
.ev-wa-filter-pills {
    display: flex;
    gap: 5px;
    padding: 6px 10px;
    overflow-x: auto;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    scrollbar-width: none;
}
.ev-wa-filter-pills::-webkit-scrollbar {
    display: none;
}
.ev-pill {
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    font-size: 10.5px;
    font-weight: 600;
    padding: 2.5px 8px;
    border-radius: 12px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.ev-pill.active {
    background: #008069;
    color: #ffffff;
    border-color: #008069;
}
.ev-pill:hover:not(.active) {
    background: #e2e8f0;
}

/* Contacts Stream */
.ev-wa-contacts-stream {
    flex: 1;
    overflow-y: auto;
    background: #ffffff;
}
.ev-wa-group-header {
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    padding: 8px 12px 4px 12px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}
.ev-wa-contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
    position: relative;
    user-select: none;
}
.ev-wa-contact-item:hover {
    background: #f0f2f5;
}
.ev-wa-contact-item.active {
    background: #e7f5ed;
}
.ev-wa-avatar-wrap {
    position: relative;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
}
.ev-wa-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 13px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.ev-wa-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    border: 2px solid #ffffff;
}
.ev-wa-dot.online {
    background: #25d366;
    box-shadow: 0 0 4px rgba(37, 211, 102, 0.8);
}
.ev-wa-dot.offline {
    background: #94a3b8;
}
.ev-wa-contact-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.3;
}
.ev-wa-contact-row-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ev-wa-contact-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #111b21;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ev-wa-contact-row-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 2px;
    gap: 4px;
}
.ev-wa-contact-sub {
    font-size: 11px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.ev-wa-contact-snippet {
    font-size: 10.5px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.ev-wa-contact-snippet.urgent {
    color: #ef4444;
    font-weight: bold;
}
.ev-wa-contact-time {
    font-size: 9.5px;
    color: #94a3b8;
    margin-left: 4px;
    flex-shrink: 0;
}
.ev-wa-item-badge {
    background: #25d366;
    color: #ffffff;
    font-size: 10px;
    font-weight: bold;
    padding: 1px 6px;
    border-radius: 10px;
    margin-left: 4px;
    display: none;
    flex-shrink: 0;
}
.ev-wa-pinned-tag {
    font-size: 10px;
    color: #008069;
    margin-left: 4px;
}

/* RIGHT PANE: ACTIVE CHAT AREA */
.ev-wa-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #efeae2;
    position: relative;
    height: 100%;
    min-width: 0;
}

/* Chat Header */
.ev-wa-chat-header {
    background: #f0f2f5;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #d1d7db;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    z-index: 2;
}
.ev-wa-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.ev-wa-mobile-back-btn {
    background: transparent;
    border: none;
    color: #475569;
    font-size: 15px;
    padding: 4px 6px;
    cursor: pointer;
    border-radius: 4px;
    display: none;
}
.ev-wa-sidebar-toggle-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    cursor: pointer;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
    flex-shrink: 0;
}
.ev-wa-sidebar-toggle-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #94a3b8;
}
.ev-wa-header-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #ffffff;
    font-size: 13px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ev-wa-header-info {
    min-width: 0;
    line-height: 1.25;
}
.ev-wa-header-title {
    font-size: 13px;
    font-weight: bold;
    color: #111b21;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ev-wa-header-status {
    font-size: 10.5px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ev-wa-header-status.online {
    color: #059669;
    font-weight: 600;
}
.ev-wa-header-right {
    display: flex;
    align-items: center;
    gap: 6px;
}
.ev-btn-session-reset {
    background: #ffffff;
    border: 1px solid #10b981;
    color: #047857;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    cursor: pointer;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.ev-btn-session-reset:hover {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
}
.ev-chat-session-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 12px 0;
    text-align: center;
}
.ev-chat-session-divider span {
    background: #ecfdf5;
    color: #065f46;
    border: 1px dashed #a7f3d0;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 12px;
}

/* Chat Message Body */
.ev-wa-chat-body {
    flex: 1;
    padding: 14px 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background-color: #f8fafc;
    background-image: none;
}

/* Chat Message Bubbles */
.ev-chat-msg {
    max-width: 82%;
    padding: 8px 12px;
    font-size: 12.5px;
    line-height: 1.5;
    word-break: break-word;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    position: relative;
    border-radius: 12px;
}
.ev-chat-msg-mine {
    align-self: flex-end;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #0f172a;
    border-radius: 12px 12px 2px 12px;
}
.ev-chat-msg-other {
    align-self: flex-start;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    border-radius: 12px 12px 12px 2px;
}
.ev-chat-msg-ai {
    max-width: 95%;
    align-self: flex-start;
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #cbd5e1;
    border-radius: 12px 12px 12px 2px;
    border-left: 4px solid #047857;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
}
.ev-msg-actions {
    position: absolute;
    top: 4px;
    right: 6px;
    display: none;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 1px 3px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    z-index: 5;
    gap: 2px;
}
.ev-chat-msg:hover .ev-msg-actions {
    display: flex;
}
.ev-msg-act-btn {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 11px;
    padding: 2px 5px;
    cursor: pointer;
    border-radius: 3px;
    transition: all 0.15s ease;
}
.ev-msg-act-btn:hover {
    color: #0284c7;
    background: #f1f5f9;
}
.ev-table-header-tools {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 6px 0 3px 0;
    font-size: 11px;
    color: #64748b;
}
.ev-table-copy-btn {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 10.5px;
    font-weight: 600;
    color: #0f766e;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.ev-table-copy-btn:hover {
    background: #ccfbf1;
    border-color: #14b8a6;
    color: #0f766e;
}
.ev-chat-msg-sender {
    font-size: 10.5px;
    font-weight: bold;
    color: #047857;
    margin-bottom: 2px;
}
.ev-chat-msg-content {
    font-size: 12px;
    line-height: 1.5;
}
.ev-chat-msg-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 3px;
    margin-top: 3px;
    font-size: 9.5px;
    color: #667781;
}
.ev-chat-tick {
    font-size: 11px;
    line-height: 1;
    letter-spacing: -2px;
}
.ev-chat-tick.read {
    color: #53bdeb;
}
.ev-chat-tick.unread {
    color: #8696a0;
}
.ev-chat-msg.urgent {
    background: #fff1f2 !important;
    border: 1.5px solid #ef4444 !important;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.18) !important;
}
.ev-chat-urgent-banner {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ef4444;
    color: #ffffff;
    font-size: 9px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 4px;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.ev-chat-trx-card {
    margin-top: 6px;
    padding: 7px 9px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-left: 3px solid #0284c7;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ev-chat-trx-card-head {
    font-size: 10px;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 5px;
    text-transform: uppercase;
}
.ev-chat-trx-card-body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}
.ev-chat-trx-card-num {
    font-size: 11.5px;
    font-weight: bold;
    color: #0f172a;
    font-family: monospace;
}
.ev-chat-trx-btn-resume {
    background: #0284c7;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    padding: 3px 8px;
    font-size: 10.5px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background 0.15s ease;
}
.ev-chat-trx-btn-resume:hover {
    background: #0369a1;
}
.ev-chat-msg-ref {
    margin-top: 4px;
    padding-top: 3px;
    border-top: 1px dashed rgba(0, 0, 0, 0.15);
    font-size: 11px;
    font-weight: bold;
    color: #0369a1;
}
.ev-chat-msg-ref a {
    color: #0284c7;
    text-decoration: underline;
    cursor: pointer;
}

/* Rich Content & Tables in AI Bubble */
.ev-chat-table-wrap {
    overflow-x: auto;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.ev-chat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    text-align: left;
    background: #ffffff;
    margin: 0;
}
.ev-chat-table th {
    background: #f1f5f9;
    color: #0f172a;
    font-weight: bold;
    padding: 6px 10px;
    border-bottom: 2px solid #cbd5e1;
    border-right: 1px solid #e2e8f0;
    white-space: nowrap;
}
.ev-chat-table td {
    padding: 6px 10px;
    border-bottom: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    color: #334155;
}
.ev-chat-table tr:last-child td {
    border-bottom: none;
}
.ev-chat-table tr:nth-child(even) {
    background: #f8fafc;
}
.ev-chat-msg-ai table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    text-align: left;
    background: #ffffff;
    margin: 8px 0;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
}
.ev-chat-msg-ai table th {
    background: #f1f5f9;
    color: #0f172a;
    font-weight: bold;
    padding: 6px 10px;
    border: 1px solid #cbd5e1;
    white-space: nowrap;
}
.ev-chat-msg-ai table td {
    padding: 6px 10px;
    border: 1px solid #e2e8f0;
    color: #334155;
}
.ev-chat-msg-ai table tr:nth-child(even) {
    background: #f8fafc;
}
.ev-chat-msg-ai a {
    color: #0284c7;
    font-weight: 600;
    text-decoration: underline;
}
.ev-chat-msg-ai a:hover {
    color: #0369a1;
}
.ev-chat-msg-ai ul, .ev-chat-msg-ai ol {
    margin: 6px 0;
    padding-left: 20px;
}
.ev-chat-msg-ai li {
    margin-bottom: 3px;
}
.ev-chat-msg-ai p {
    margin: 0 0 6px 0;
}
.ev-chat-msg-ai p:last-child {
    margin-bottom: 0;
}

/* AI Prompt Chips */
#ev-chat-prompt-chips {
    display: none;
    padding: 6px 10px;
    background: #f0f2f5;
    border-top: 1px solid #e2e8f0;
    overflow-x: auto;
    white-space: nowrap;
    gap: 6px;
    align-items: center;
    scrollbar-width: thin;
}
.ev-chip-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 3px 9px;
    font-size: 10.5px;
    font-weight: 600;
    color: #0369a1;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
    user-select: none;
}
.ev-chip-btn:hover {
    background: #e0f2fe;
    border-color: #0284c7;
    color: #0284c7;
    transform: translateY(-1px);
}

/* Ref Toolbar (Attachment indicator) */
#ev-chat-ref-toolbar {
    display: none;
    font-size: 11px;
    background: #e2e8f0;
    padding: 4px 10px;
    align-items: center;
    justify-content: space-between;
    color: #1e293b;
    border-top: 1px solid #cbd5e1;
}
#ev-chat-ref-toolbar a {
    color: #dd4b39;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
}

/* Typing Indicator */
#ev-chat-typing-indicator {
    display: none;
    align-self: flex-start;
    background: #ffffff;
    color: #008069;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 11px;
    font-style: italic;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

/* Chat Input Footer */
.ev-wa-chat-footer {
    background: #f0f2f5;
    border-top: 1px solid #d1d7db;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
#ev-chat-btn-attach {
    background: transparent;
    border: none;
    color: #54656f;
    font-size: 17px;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 50%;
    transition: color 0.15s;
}
#ev-chat-btn-attach:hover {
    color: #111b21;
}
#ev-chat-btn-urgent {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 16px;
    cursor: pointer;
    padding: 4px 6px;
    border-radius: 50%;
    transition: all 0.2s ease;
}
#ev-chat-btn-urgent:hover {
    color: #ef4444;
    background: #fee2e2;
}
#ev-chat-btn-urgent.active {
    color: #ef4444;
    background: #fee2e2;
}
#ev-chat-input {
    flex: 1;
    height: 36px;
    border-radius: 18px;
    border: 1px solid #ffffff;
    background: #ffffff;
    padding: 6px 14px;
    font-size: 12px;
    outline: none;
    color: #111b21;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    transition: border-color 0.2s;
}
#ev-chat-input:focus {
    border-color: #008069;
}
#ev-chat-btn-send {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #00a884;
    color: #ffffff;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: background 0.15s, transform 0.1s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
}
#ev-chat-btn-send:hover {
    background: #008069;
    transform: scale(1.05);
}

/* Empty State Hint */
#ev-chat-empty-hint {
    text-align: center;
    color: #64748b;
    font-size: 12px;
    margin: auto 0;
    padding: 24px 16px;
}

/* ==========================================================================
   MOBILE RESPONSIVE RULES (< 768px)
   ========================================================================== */
@media (max-width: 767px) {
    #ev-chat-window {
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        max-width: 100vw !important;
        max-height: 100vh !important;
        border-radius: 0 !important;
        border: none !important;
    }
    .ev-wa-sidebar {
        width: 100% !important;
        max-width: 100% !important;
        border-right: none !important;
    }
    .ev-wa-main {
        width: 100% !important;
    }
    .ev-wa-mobile-back-btn {
        display: inline-flex !important;
    }
}
</style>

<!-- TRIGGER BUTTON -->
<script>
if (typeof window.evChatToggleWindow !== 'function') {
    window.evChatToggleWindow = function() {
        try {
            if (window.top && window.top !== window && typeof window.top.evChatToggleWindow === 'function') {
                window.top.evChatToggleWindow();
                return;
            }
        } catch(e){}
    };
}
</script>
<a class=\"btn btn__trigger-bawah-ai-ds btn__trigger--views-bawah-ds\" id=\"ev-chat-trigger\" onclick=\"if(typeof window.evChatToggleWindow==='function'){window.evChatToggleWindow();}else if(window.top&&typeof window.top.evChatToggleWindow==='function'){window.top.evChatToggleWindow();}\" title=\"Buka Chat Messenger &amp; Asisten AI\">
    <i class=\"fa fa-comments\" style=\"font-size:1.4rem;\"></i>
    <span class=\"ev-chat-label\">Messenger &amp; AI</span>
    <span id=\"ev-chat-badge\" style=\"display:none;\"></span>
</a>
<script>
(function(){
    try {
        var trg = document.getElementById('ev-chat-trigger');
        if (!trg) return;
        var isMob = (window.innerWidth < 768);
        var host = isMob ? (document.getElementById('chat_ai_mb') || document.getElementById('chat_ai_ds')) : (document.getElementById('chat_ai_ds') || document.getElementById('chat_ai_mb'));
        if (host && trg.parentNode !== host) {
            host.appendChild(trg);
        }
    } catch(e){}
})();
</script>

<!-- CHAT WINDOW (TWO-PANE MESSENGER) -->
<div id=\"ev-chat-window\">
    <!-- DRAGGABLE RESIZE HANDLE (KIRI) -->
    <div id=\"ev-chat-resizer\" title=\"Tarik ke kiri/kanan untuk atur lebar obrolan (Klik 2x untuk Mode Lebar)\"></div>

    <!-- TOP APP BAR -->
    <div class=\"ev-wa-window-header\">
        <div class=\"ev-wa-brand\">
            <i class=\"fa fa-comments\"></i>
            <span>{$bot_name} &bull; Chat Hub</span>
        </div>
        <div class=\"ev-wa-window-tools\">
            <button id=\"ev-chat-btn-sidebar\" onclick=\"evChatToggleSidebar()\" title=\"Tampilkan / Sembunyikan Kontak (Beri Ruang Luas Chat)\"><i class=\"fa fa-bars\"></i></button>
            <button id=\"ev-chat-btn-dock\" onclick=\"evChatToggleDock()\" title=\"Docking Sidebar Kanan (Split-Screen Mode)\"><i class=\"fa fa-columns\"></i></button>
            <button id=\"ev-chat-btn-expand\" onclick=\"evChatToggleExpand()\" title=\"Perbesar / Perkecil Jendela\"><i class=\"fa fa-expand\"></i></button>
            <button onclick=\"evChatLoadMessages(true)\" title=\"Refresh Pesan\"><i class=\"fa fa-refresh\"></i></button>
            <button onclick=\"evChatToggleWindow()\" title=\"Tutup\"><i class=\"fa fa-times\"></i></button>
        </div>
    </div>

    <!-- TWO-PANE BODY -->
    <div class=\"ev-wa-container\">
        <!-- LEFT PANE: CONTACTS & CHANNELS -->
        <div class=\"ev-wa-sidebar\" id=\"ev-wa-sidebar\">
            <!-- Profile & Status Bar -->
            <div class=\"ev-wa-profile-bar\">
                <div class=\"ev-wa-my-info\">
                    <div class=\"ev-wa-my-avatar\">{$my_initials}</div>
                    <div class=\"ev-wa-my-text\">
                        <div class=\"ev-wa-my-name\">{$my_name}</div>
                        <div class=\"ev-wa-my-sub\">{$my_cabang_nama}</div>
                    </div>
                </div>";

        if ($is_executive == 1) {
            $stealthClass = ($is_stealth == 1) ? 'stealth' : 'online';
            $stealthText = ($is_stealth == 1) ? '⚪ Senyap' : '🟢 Online';
            $stealthTitle = ($is_stealth == 1) ? 'Status Anda disembunyikan (tampak offline bagi staf)' : 'Status Anda terlihat online oleh rekan kerja';

            $html .= "
                <button id=\"ev-btn-stealth-toggle\" class=\"ev-wa-stealth-btn {$stealthClass}\" onclick=\"evChatToggleStealth()\" title=\"{$stealthTitle}\">
                    <span id=\"ev-stealth-label\">{$stealthText}</span>
                </button>";
        }

        $html .= "
            </div>

            <!-- Search Bar -->
            <div class=\"ev-wa-search-wrap\">
                <div class=\"ev-wa-search-box\">
                    <i class=\"fa fa-search\"></i>
                    <input type=\"text\" id=\"ev-wa-search-input\" placeholder=\"Cari rekan atau cabang...\" oninput=\"evChatFilterContacts(this.value)\">
                    <span id=\"ev-wa-search-clear\" onclick=\"evChatClearSearch()\" style=\"display:none;\"><i class=\"fa fa-times-circle\"></i></span>
                </div>
            </div>

            <!-- Filter Pills -->
            <div class=\"ev-wa-filter-pills\">
                <button class=\"ev-pill active\" data-cat=\"all\" onclick=\"evChatFilterCategory('all')\">Semua</button>
                <button class=\"ev-pill\" data-cat=\"online\" onclick=\"evChatFilterCategory('online')\">🟢 Online</button>
                <button class=\"ev-pill\" data-cat=\"cabang\" onclick=\"evChatFilterCategory('cabang')\">🏢 Cabang</button>
                <button class=\"ev-pill\" data-cat=\"pusat\" onclick=\"evChatFilterCategory('pusat')\">Kantor Pusat</button>
            </div>

            <!-- Contacts Stream -->
            <div class=\"ev-wa-contacts-stream\" id=\"ev-wa-contacts-stream\">
                <!-- Pinned AI Copilot -->
                <div class=\"ev-wa-contact-item ev-contact-ai active\" id=\"ev-item-ai\" data-type=\"ai\" data-cat=\"ai\" onclick=\"evChatSelectTarget('ai', 0, '{$bot_name}', '🤖 Asisten Cerdas ERP &amp; Analisis', 1)\">
                    <div class=\"ev-wa-avatar-wrap\">
                        <div class=\"ev-wa-avatar\" style=\"background: linear-gradient(135deg, #008069, #00a884);\">
                            <i class=\"fa fa-android\"></i>
                        </div>
                        <span class=\"ev-wa-dot online\"></span>
                    </div>
                    <div class=\"ev-wa-contact-content\">
                        <div class=\"ev-wa-contact-row-top\">
                            <div class=\"ev-wa-contact-name\">{$bot_name} <span class=\"ev-wa-pinned-tag\" title=\"Disematkan\"><i class=\"fa fa-thumb-tack\"></i></span></div>
                            <span class=\"ev-wa-item-badge\" id=\"ev-badge-ai\">0</span>
                        </div>
                        <div class=\"ev-wa-contact-row-bottom\">
                            <div class=\"ev-wa-contact-sub\">Asisten Cerdas ERP &amp; Analisis</div>
                        </div>
                    </div>
                </div>

                <!-- Section Cabang -->
                <div class=\"ev-wa-group-header ev-grp-cabang\">🏢 Saluran Koordinasi Cabang</div>
                <div id=\"ev-wa-branch-list\"></div>

                <!-- Section Rekan Kerja -->
                <div class=\"ev-wa-group-header ev-grp-users\">👥 Rekan Kerja (Online Teratas)</div>
                <div id=\"ev-wa-user-list\"></div>
            </div>
        </div>

        <!-- RIGHT PANE: ACTIVE CHAT -->
        <div class=\"ev-wa-main\" id=\"ev-wa-main\">
            <!-- Header Obrolan -->
            <div class=\"ev-wa-chat-header\">
                <div class=\"ev-wa-header-left\">
                    <button type=\"button\" class=\"ev-wa-sidebar-toggle-btn\" id=\"ev-wa-sidebar-toggle-btn\" onclick=\"evChatToggleSidebar()\" title=\"Tampilkan / Sembunyikan Kontak (Beri Ruang Luas Chat)\">
                        <i class=\"fa fa-bars\"></i> <span class=\"ev-sb-txt\">Tutup Kontak</span>
                    </button>
                    <button class=\"ev-wa-mobile-back-btn\" id=\"ev-wa-mobile-back\" onclick=\"evChatMobileBack()\" title=\"Kembali ke Kontak\">
                        <i class=\"fa fa-arrow-left\"></i>
                    </button>
                    <div class=\"ev-wa-header-avatar\" id=\"ev-header-avatar\" style=\"background:#008069;\">
                        <i class=\"fa fa-android\"></i>
                    </div>
                    <div class=\"ev-wa-header-info\">
                        <div class=\"ev-wa-header-title\" id=\"ev-header-title\">{$bot_name}</div>
                        <div class=\"ev-wa-header-status online\" id=\"ev-header-status\">🤖 Asisten Cerdas ERP &amp; Analisis</div>
                    </div>
                </div>
                <div class=\"ev-wa-header-right\" id=\"ev-header-ai-tools\">
                    <button type=\"button\" id=\"ev-btn-new-ai-session\" class=\"ev-btn-session-reset\" onclick=\"evChatResetAiSession()\" title=\"Mulai Sesi Percakapan Baru (Reset Memori AI)\">
                        <i class=\"fa fa-plus-circle\"></i> <span>Sesi Baru</span>
                    </button>
                </div>
            </div>

            <!-- Chat Messages Stream -->
            <div class=\"ev-wa-chat-body\" id=\"ev-chat-body\">
                <div id=\"ev-chat-empty-hint\">Pilih rekan kerja atau asisten AI dari daftar sebelah kiri untuk memulai percakapan.</div>
                <div id=\"ev-chat-typing-indicator\"><i class=\"fa fa-spinner fa-spin\"></i> AI sedang berpikir...</div>
            </div>

            <!-- Quick Prompts Chips (AI Copilot) -->
            <div id=\"ev-chat-prompt-chips\">
                {$chips_html}
            </div>

            <!-- Ref Trx Toolbar -->
            <div id=\"ev-chat-ref-toolbar\">
                <span><i class=\"fa fa-paperclip\"></i> Lampiran Transaksi: <strong id=\"ev-chat-ref-label\">-</strong></span>
                <a onclick=\"evChatClearRef()\" title=\"Batal lampirkan\">&times; Batal</a>
            </div>

            <!-- Chat Input Footer -->
            <div class=\"ev-wa-chat-footer\" id=\"ev-chat-footer\">
                <button id=\"ev-chat-btn-attach\" onclick=\"evChatAttachCurrentTrx()\" title=\"Lampirkan Dokumen Transaksi Aktif\">
                    <i class=\"fa fa-paperclip\"></i>
                </button>
                <button id=\"ev-chat-btn-urgent\" type=\"button\" onclick=\"evChatToggleUrgent()\" title=\"Tandai Pesan Mendesak / Urgent (Prioritas)\">
                    <i class=\"fa fa-exclamation-circle\"></i>
                </button>
                <input type=\"text\" id=\"ev-chat-input\" placeholder=\"Tanyakan apapun ke {$bot_name}...\" maxlength=\"1000\" autocomplete=\"off\">
                <button id=\"ev-chat-btn-send\" onclick=\"evChatSendMessage()\" title=\"Kirim (Enter)\">
                    <i class=\"fa fa-paper-plane\"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var _evChatLastToggleTime = 0;
var _evChatInternalToggle = null;

// Global Window Toggle - Selalu tersedia langsung di top scope (aman dari iframe / late-binding)
window.evChatToggleWindow = function() {
    var now = new Date().getTime();
    if (now - _evChatLastToggleTime < 250) {
        return;
    }
    _evChatLastToggleTime = now;

    // Jika dipanggil dari dalam iframe, teruskan ke window top/parent
    if (window.self !== window.top) {
        try {
            var tWin = window.top || (typeof top !== 'undefined' ? top : null);
            if (tWin && typeof tWin.evChatToggleWindow === 'function') {
                tWin.evChatToggleWindow();
                return;
            }
            var pWin = window.parent || (typeof parent !== 'undefined' ? parent : null);
            if (pWin && typeof pWin.evChatToggleWindow === 'function') {
                pWin.evChatToggleWindow();
                return;
            }
        } catch(e) {}
    }

    if (typeof _evChatInternalToggle === 'function') {
        _evChatInternalToggle();
        return;
    }

    // Fallback jika dipanggil sebelum inisialisasi lengkap
    var w = document.getElementById('ev-chat-window');
    if (!w) {
        try {
            var tWinFallback = window.top || (typeof top !== 'undefined' ? top : null);
            if (tWinFallback && tWinFallback !== window && typeof tWinFallback.evChatToggleWindow === 'function') {
                tWinFallback.evChatToggleWindow();
                return;
            }
        } catch(e) {}
        return;
    }

    if (w.style.display === 'flex') {
        w.style.display = 'none';
    } else {
        w.style.display = 'flex';
    }
};

(function(){
    // Guard anti-iframe: Di dalam iframe, bersihkan elemen lokal agar tidak render ganda
    if (window.self !== window.top) {
        var trg = document.getElementById('ev-chat-trigger');
        if (trg && trg.parentNode) trg.parentNode.removeChild(trg);
        var win = document.getElementById('ev-chat-window');
        if (win && win.parentNode) win.parentNode.removeChild(win);
        return;
    }

    // Deduplikasi mutlak DOM di top window
    var allTrg = document.querySelectorAll('#ev-chat-trigger');
    if (allTrg.length > 1) {
        for (var dt = 1; dt < allTrg.length; dt++) {
            if (allTrg[dt].parentNode) allTrg[dt].parentNode.removeChild(allTrg[dt]);
        }
    }
    var allWin = document.querySelectorAll('#ev-chat-window');
    if (allWin.length > 1) {
        for (var dw = 1; dw < allWin.length; dw++) {
            if (allWin[dw].parentNode) allWin[dw].parentNode.removeChild(allWin[dw]);
        }
    }

    // Docking otomatis Chat Trigger ke taskbar navbar-2nd (#chat_ai_ds / #chat_ai_mb)
    function attachChatTrigger() {
        var trg = document.getElementById('ev-chat-trigger');
        if (!trg) return;

        var isMob = (window.innerWidth < 768);
        var host = isMob ? document.getElementById('chat_ai_mb') : document.getElementById('chat_ai_ds');
        if (!host) {
            host = document.getElementById('chat_ai_ds') || document.getElementById('chat_ai_mb');
        }

        // Fallback: jika #chat_ai_ds belum ada di DOM, cari taskbar container (.col-md-2 / .col-lg-2)
        if (!host) {
            var navContainer = document.querySelector('.navbar-2nd .col-md-2, .navbar-2nd .col-lg-2, .navbar-2nd .col-sm-3');
            if (navContainer) {
                var newHost = document.createElement('div');
                newHost.id = isMob ? 'chat_ai_mb' : 'chat_ai_ds';
                newHost.className = isMob ? 'hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl' : 'hidden-xs';
                navContainer.appendChild(newHost);
                host = newHost;
            }
        }

        if (host && trg.parentNode !== host) {
            host.appendChild(trg);
        }
    }
    attachChatTrigger();
    document.addEventListener('DOMContentLoaded', attachChatTrigger);
    window.addEventListener('load', attachChatTrigger);
    if (typeof $ !== 'undefined') {
        $(document).ready(function() { attachChatTrigger(); });
    }
    setTimeout(attachChatTrigger, 100);
    setTimeout(attachChatTrigger, 300);
    setTimeout(attachChatTrigger, 800);
    setTimeout(attachChatTrigger, 1500);

    // MutationObserver untuk mengantisipasi re-render taskbar atau DOM replacement
    if (typeof MutationObserver !== 'undefined' && document.body) {
        var _obs = new MutationObserver(function() {
            var trg = document.getElementById('ev-chat-trigger');
            var host = (window.innerWidth < 768) ? (document.getElementById('chat_ai_mb') || document.getElementById('chat_ai_ds')) : (document.getElementById('chat_ai_ds') || document.getElementById('chat_ai_mb'));
            if (host && trg && trg.parentNode !== host) {
                attachChatTrigger();
            }
        });
        _obs.observe(document.body, { childList: true, subtree: true });
    }

    // Responsive Resize Listener
    window.addEventListener('resize', function() {
        attachChatTrigger();
        handleResponsiveLayout();
    });

    var isCenter = $is_center;
    var myCabangId = $my_cabang_id;
    var myId = $my_id;
    var defaultBotName = '{$bot_name}';

    var activeTab = localStorage.getItem('ev_wa_active_tab') || 'ai';
    var activeTargetId = parseInt(localStorage.getItem('ev_wa_target_id')) || 0;
    var activeTargetName = localStorage.getItem('ev_wa_target_name') || defaultBotName;
    var activeTargetStatus = localStorage.getItem('ev_wa_target_status') || '🤖 Asisten Cerdas ERP & Analisis';
    var activeTargetIsOnline = parseInt(localStorage.getItem('ev_wa_target_online')) || 1;

    var lastMessageId = 0;
    var isWindowOpen = false;
    var isFetching = false;
    var isStealthMode = ($is_stealth == 1);
    var mobileView = 'contacts'; // 'contacts' atau 'chat'
    var currentFilterCategory = 'all';

    var pollingTimer = null;
    var unreadTimer = null;
    var attachedRefTrx = '';

    var cachedCabangList = [];
    var cachedUserList = [];
    var cachedUnreadBreakdown = {};
    var cachedSnippets = { direct: {}, branch: {} };

    // Generate inisial nama
    function getInitials(name) {
        if (!name) return '?';
        var parts = name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
        return (parts[0].substring(0, 1) + parts[parts.length - 1].substring(0, 1)).toUpperCase();
    }

    // Generate avatar color konsisten
    function getAvatarColor(name) {
        var colors = ['#008069', '#0284c7', '#6366f1', '#8b5cf6', '#d97706', '#059669', '#e11d48', '#475569', '#0891b2', '#4f46e5'];
        var hash = 0;
        for (var i = 0; i < (name || '').length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        var idx = Math.abs(hash) % colors.length;
        return colors[idx];
    }

    var isUrgentActive = false;

    function evChatToggleUrgent() {
        evChatSetUrgent(!isUrgentActive);
    }
    window.evChatToggleUrgent = evChatToggleUrgent;

    function evChatSetUrgent(active) {
        isUrgentActive = !!active;
        var btn = document.getElementById('ev-chat-btn-urgent');
        var inp = document.getElementById('ev-chat-input');
        if (btn) {
            if (isUrgentActive) {
                btn.className = 'active';
                btn.title = 'Mode Mendesak Aktif (Klik untuk matikan)';
            } else {
                btn.className = '';
                btn.title = 'Tandai Pesan Mendesak / Urgent (Prioritas)';
            }
        }
        if (inp) {
            if (isUrgentActive) {
                inp.placeholder = 'Ketik pesan MENDESAK (Prioritas Tinggi)...';
                inp.style.borderColor = '#ef4444';
                inp.focus();
            } else {
                inp.placeholder = (activeTab === 'ai') ? 'Tanyakan apapun ke ' + defaultBotName + '...' : 'Ketik pesan ke ' + activeTargetName + '...';
                inp.style.borderColor = '#ffffff';
            }
        }
    }
    window.evChatSetUrgent = evChatSetUrgent;

    function evChatPromptInput(prefix) {
        var inp = document.getElementById('ev-chat-input');
        if (inp) {
            inp.value = prefix;
            inp.focus();
            if (typeof inp.setSelectionRange === 'function') {
                var len = inp.value.length;
                inp.setSelectionRange(len, len);
            }
        }
    }
    window.evChatPromptInput = evChatPromptInput;

    function evChatResetAiSession() {
        var confirmMsg = 'Mulai sesi percakapan baru dengan ' + defaultBotName + '?' + String.fromCharCode(10) + String.fromCharCode(10) + 'Konteks obrolan sebelumnya akan diarsipkan dan AI akan memulai memori sesi baru.';
        if (!confirm(confirmMsg)) {
            return;
        }
        $.ajax({
            url: '$base' + 'Chat/reset_ai_session',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    var body = document.getElementById('ev-chat-body');
                    if (body) {
                        var notice = document.createElement('div');
                        notice.className = 'ev-chat-session-divider';
                        notice.innerHTML = '<span>✨ Sesi Baru Dimulai (' + (new Date()).toLocaleTimeString().substring(0, 5) + ')</span>';
                        body.appendChild(notice);
                        body.scrollTop = body.scrollHeight;
                    }
                    var inp = document.getElementById('ev-chat-input');
                    if (inp) {
                        inp.focus();
                    }
                } else {
                    alert('Gagal mereset sesi AI.');
                }
            },
            error: function() {
                alert('Kendala koneksi saat mereset sesi AI.');
            }
        });
    }
    window.evChatResetAiSession = evChatResetAiSession;

    // Notifikasi suara halus Web Audio API
    function playChimeSound() {
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            var ctx = new AudioCtx();
            var osc = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1760, ctx.currentTime + 0.12);
            gain.gain.setValueAtTime(0.07, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.25);
        } catch(e) {}
    }

    // Notifikasi suara prioritas mendesak
    function playUrgentSound() {
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            var ctx = new AudioCtx();

            var osc1 = ctx.createOscillator();
            var gain1 = ctx.createGain();
            osc1.type = 'triangle';
            osc1.frequency.setValueAtTime(880, ctx.currentTime);
            gain1.gain.setValueAtTime(0.09, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.14);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start();
            osc1.stop(ctx.currentTime + 0.14);

            var osc2 = ctx.createOscillator();
            var gain2 = ctx.createGain();
            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(1174.66, ctx.currentTime + 0.12);
            gain2.gain.setValueAtTime(0.12, ctx.currentTime + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(ctx.currentTime + 0.12);
            osc2.stop(ctx.currentTime + 0.35);
        } catch(e) {}
    }

    // Toggle Tampilan Jendela (Internal Handler)
    _evChatInternalToggle = function() {
        var w = document.getElementById('ev-chat-window');
        if (!w) return;

        if (w.style.display === 'flex') {
            w.style.display = 'none';
            isWindowOpen = false;
        } else {
            w.style.display = 'flex';
            isWindowOpen = true;

            // Pulihkan status docking jika sebelumnya aktif
            if (localStorage.getItem('ev_chat_docked') === '1') {
                w.classList.add('ev-chat-docked');
                var savedDockW = parseInt(localStorage.getItem('ev_chat_dock_width')) || 620;
                w.style.width = savedDockW + 'px';
            } else {
                var savedFloatW = parseInt(localStorage.getItem('ev_chat_float_width')) || '';
                if (savedFloatW) w.style.width = savedFloatW + 'px';
            }

            // Pulihkan status sidebar collapsed jika sebelumnya aktif
            if (localStorage.getItem('ev_chat_sidebar_collapsed') === '1') {
                w.classList.add('ev-sidebar-collapsed');
                updateSidebarToggleBtn(true);
            } else {
                w.classList.remove('ev-sidebar-collapsed');
                updateSidebarToggleBtn(false);
            }

            handleResponsiveLayout();

            if (cachedCabangList.length === 0 || cachedUserList.length === 0) {
                evChatLoadContacts();
            }

            // Sembunyikan badge utama saat jendela aktif
            var b = document.getElementById('ev-chat-badge');
            if (b) b.style.display = 'none';

            // Pulihkan target aktif terakhir
            evChatSelectTarget(activeTab, activeTargetId, activeTargetName, activeTargetStatus, activeTargetIsOnline, true);

            setTimeout(function(){
                var inp = document.getElementById('ev-chat-input');
                if (inp && window.innerWidth >= 768) inp.focus();
            }, 150);
        }
    };

    // Toggle Sembunyikan / Tampilkan Daftar Kontak (Sidebar Collapse)
    function evChatToggleSidebar() {
        var w = document.getElementById('ev-chat-window');
        if (!w) return;

        var isCollapsed = w.classList.toggle('ev-sidebar-collapsed');
        localStorage.setItem('ev_chat_sidebar_collapsed', isCollapsed ? '1' : '0');
        updateSidebarToggleBtn(isCollapsed);
        handleResponsiveLayout();
    }
    window.evChatToggleSidebar = evChatToggleSidebar;

    function updateSidebarToggleBtn(isCollapsed) {
        var txts = document.querySelectorAll('.ev-sb-txt');
        for (var i = 0; i < txts.length; i++) {
            txts[i].textContent = isCollapsed ? 'Buka Kontak' : 'Tutup Kontak';
        }
        var topBtn = document.getElementById('ev-chat-btn-sidebar');
        if (topBtn) {
            topBtn.title = isCollapsed ? 'Tampilkan Daftar Kontak' : 'Sembunyikan Daftar Kontak (Beri Ruang Luas Chat)';
        }
    }

    // Toggle Docking Sidebar Kanan (Split-Screen Mode)
    function evChatToggleDock() {
        var w = document.getElementById('ev-chat-window');
        var btn = document.getElementById('ev-chat-btn-dock');
        var iconExpand = document.querySelector('#ev-chat-btn-expand i');
        if (!w) return;

        var isDocked = w.classList.toggle('ev-chat-docked');
        if (isDocked) {
            w.classList.remove('ev-chat-expanded');
            localStorage.setItem('ev_chat_docked', '1');
            if (btn) btn.title = 'Kembalikan Mode Mengambang (Floating Window)';

            var savedDockW = parseInt(localStorage.getItem('ev_chat_dock_width')) || 620;
            w.style.width = savedDockW + 'px';
            if (iconExpand) iconExpand.className = (savedDockW > 750) ? 'fa fa-compress' : 'fa fa-expand';
        } else {
            localStorage.setItem('ev_chat_docked', '0');
            if (btn) btn.title = 'Docking Sidebar Kanan (Split-Screen Mode)';

            var savedFloatW = parseInt(localStorage.getItem('ev_chat_float_width')) || '';
            w.style.width = savedFloatW ? (savedFloatW + 'px') : '';
            if (iconExpand) iconExpand.className = w.classList.contains('ev-chat-expanded') ? 'fa fa-compress' : 'fa fa-expand';
        }
        handleResponsiveLayout();
    }
    window.evChatToggleDock = evChatToggleDock;

    // Toggle Ukuran Jendela (Perbesar/Normal)
    function evChatToggleExpand() {
        var w = document.getElementById('ev-chat-window');
        var icon = document.querySelector('#ev-chat-btn-expand i');
        if (!w) return;

        if (w.classList.contains('ev-chat-docked')) {
            // Dalam mode docked: toggle antara Standard Dock (~620px) dan Wide Dock (~55% layar)!
            var curW = w.offsetWidth;
            if (curW < 750) {
                var wideW = Math.min(Math.round(window.innerWidth * 0.55), 1050);
                if (wideW < 780) wideW = 780;
                w.style.width = wideW + 'px';
                localStorage.setItem('ev_chat_dock_width', wideW);
                if (icon) icon.className = 'fa fa-compress';
            } else {
                w.style.width = '620px';
                localStorage.setItem('ev_chat_dock_width', '620');
                if (icon) icon.className = 'fa fa-expand';
            }
            return;
        }

        // Dalam mode floating biasa:
        w.classList.toggle('ev-chat-expanded');
        if (w.classList.contains('ev-chat-expanded')) {
            w.style.width = '';
            if (icon) icon.className = 'fa fa-compress';
        } else {
            w.style.width = '';
            if (icon) icon.className = 'fa fa-expand';
        }
    }
    window.evChatToggleExpand = evChatToggleExpand;

    // Resizer Drag Handler untuk Mengatur Lebar Jendela (Docked & Floating)
    function initWindowResizer() {
        var resizer = document.getElementById('ev-chat-resizer');
        var win = document.getElementById('ev-chat-window');
        if (!resizer || !win) return;

        var isDragging = false;
        var startX = 0;
        var startW = 0;

        resizer.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.clientX;
            startW = win.offsetWidth;
            resizer.classList.add('is-resizing');
            document.body.style.userSelect = 'none';
            document.body.style.cursor = 'ew-resize';
            e.preventDefault();
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            var delta = startX - e.clientX;
            var newW = startW + delta;
            var minW = 380;
            var maxW = Math.min(window.innerWidth - 40, 1300);

            if (newW < minW) newW = minW;
            if (newW > maxW) newW = maxW;

            win.style.width = newW + 'px';
            if (win.classList.contains('ev-chat-docked')) {
                localStorage.setItem('ev_chat_dock_width', newW);
            } else {
                localStorage.setItem('ev_chat_float_width', newW);
            }
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                resizer.classList.remove('is-resizing');
                document.body.style.userSelect = '';
                document.body.style.cursor = '';
            }
        });

        // Dobel-klik resizer untuk berganti cepat antara Compact vs Lebar Maksimal
        resizer.addEventListener('dblclick', function() {
            if (win.classList.contains('ev-chat-docked')) {
                var curW = win.offsetWidth;
                var targetW = (curW < 750) ? Math.min(Math.round(window.innerWidth * 0.55), 1050) : 620;
                win.style.width = targetW + 'px';
                localStorage.setItem('ev_chat_dock_width', targetW);
            }
        });
    }

    // Salin Teks Pesan ke Clipboard
    function evChatCopyMsg(btn) {
        var bubble = $(btn).closest('.ev-chat-msg')[0];
        if (!bubble) return;
        var contentEl = bubble.querySelector('.ev-chat-msg-content');
        if (!contentEl) return;
        var text = (contentEl.innerText || contentEl.textContent || '').trim();
        evChatCopyToClipboard(text, btn, false);
    }
    window.evChatCopyMsg = evChatCopyMsg;

    // Kutip / Balas Pesan ke Kolom Input
    function evChatQuoteMsg(btn) {
        var bubble = $(btn).closest('.ev-chat-msg')[0];
        if (!bubble) return;
        var contentEl = bubble.querySelector('.ev-chat-msg-content');
        var senderEl = bubble.querySelector('.ev-chat-msg-sender');
        var sender = senderEl ? (senderEl.innerText || senderEl.textContent || '').trim() : 'Pesan';
        var text = contentEl ? (contentEl.innerText || contentEl.textContent || '').trim() : '';
        if (text.length > 80) text = text.substring(0, 77) + '...';
        var quotePrefix = '> ' + sender + ': \"' + text.replace(/\\r?\\n/g, ' ') + '\"\\n';
        evChatPromptInput(quotePrefix);
    }
    window.evChatQuoteMsg = evChatQuoteMsg;

    // 1-Click Salin Seluruh Tabel ke Clipboard (Format TSV untuk Excel / Spreadsheet)
    function evChatCopyTable(btn) {
        var wrap = $(btn).closest('.ev-chat-table-wrap')[0];
        if (!wrap) return;
        var tbl = wrap.querySelector('table');
        if (!tbl) return;

        var rows = tbl.querySelectorAll('tr');
        var tsvLines = [];
        for (var r = 0; r < rows.length; r++) {
            var cells = rows[r].querySelectorAll('th, td');
            var cellTexts = [];
            for (var c = 0; c < cells.length; c++) {
                var val = (cells[c].innerText || cells[c].textContent || '').trim();
                val = val.replace(/\\t/g, ' ').replace(/\\r?\\n/g, ' ');
                cellTexts.push(val);
            }
            tsvLines.push(cellTexts.join('\\t'));
        }

        var tsv = tsvLines.join('\\n');
        evChatCopyToClipboard(tsv, btn, true);
    }
    window.evChatCopyTable = evChatCopyTable;

    function evChatCopyToClipboard(text, btn, isTable) {
        if (!text) return;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopyFeedback(btn, isTable);
            }).catch(function() {
                fallbackCopy(text, btn, isTable);
            });
        } else {
            fallbackCopy(text, btn, isTable);
        }
    }

    function fallbackCopy(text, btn, isTable) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.top = '-9999px';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            showCopyFeedback(btn, isTable);
        } catch(e) {
            alert('Gagal menyalin ke clipboard.');
        }
        document.body.removeChild(ta);
    }

    function showCopyFeedback(btn, isTable) {
        if (!btn) return;
        var origHtml = btn.innerHTML;
        if (isTable) {
            btn.innerHTML = '<i class=\"fa fa-check\" style=\"color:#059669;\"></i> Tersalin!';
            btn.style.background = '#d1fae5';
            btn.style.borderColor = '#10b981';
            setTimeout(function() {
                btn.innerHTML = origHtml;
                btn.style.background = '';
                btn.style.borderColor = '';
            }, 2000);
        } else {
            btn.innerHTML = '<i class=\"fa fa-check\" style=\"color:#059669;\"></i>';
            setTimeout(function() {
                btn.innerHTML = origHtml;
            }, 1500);
        }
    }

    // Responsif Mobile: Transisi Daftar Kontak vs Obrolan
    function handleResponsiveLayout() {
        var isMob = (window.innerWidth < 768);
        var w = document.getElementById('ev-chat-window');
        var sidebar = document.getElementById('ev-wa-sidebar');
        var main = document.getElementById('ev-wa-main');
        var backBtn = document.getElementById('ev-wa-mobile-back');
        var toggleBtn = document.getElementById('ev-wa-sidebar-toggle-btn');

        if (!sidebar || !main) return;

        if (isMob) {
            if (toggleBtn) toggleBtn.style.display = 'none';
            if (mobileView === 'contacts') {
                sidebar.style.display = 'flex';
                sidebar.style.width = '100%';
                main.style.display = 'none';
                if (backBtn) backBtn.style.display = 'none';
            } else {
                sidebar.style.display = 'none';
                main.style.display = 'flex';
                main.style.width = '100%';
                if (backBtn) backBtn.style.display = 'inline-flex';
            }
        } else {
            if (backBtn) backBtn.style.display = 'none';
            if (toggleBtn) toggleBtn.style.display = 'inline-flex';

            var isCollapsed = w && w.classList.contains('ev-sidebar-collapsed');
            sidebar.style.display = isCollapsed ? 'none' : 'flex';
            sidebar.style.width = ''; // Biarkan aturan CSS kelas yang menentukan lebar!
            main.style.display = 'flex';
            main.style.width = '';
        }
    }

    function evChatMobileBack() {
        mobileView = 'contacts';
        handleResponsiveLayout();
    }
    window.evChatMobileBack = evChatMobileBack;

    // Toggle Stealth Mode untuk Eksekutif
    function evChatToggleStealth() {
        $.ajax({
            url: '$base' + 'Chat/toggle_presence_mode',
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    isStealthMode = (res.is_stealth == 1);
                    var btn = document.getElementById('ev-btn-stealth-toggle');
                    var lbl = document.getElementById('ev-stealth-label');
                    if (btn && lbl) {
                        if (isStealthMode) {
                            btn.className = 'ev-wa-stealth-btn stealth';
                            lbl.textContent = '⚪ Senyap';
                            btn.title = 'Status Anda disembunyikan (tampak offline bagi staf)';
                        } else {
                            btn.className = 'ev-wa-stealth-btn online';
                            lbl.textContent = '🟢 Online';
                            btn.title = 'Status Anda terlihat online oleh rekan kerja';
                        }
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.info(res.message);
                    } else {
                        alert(res.message);
                    }
                }
            }
        });
    }
    window.evChatToggleStealth = evChatToggleStealth;

    // Filter Kategori Kontak (Semua / Online / Cabang / Kantor Pusat)
    function evChatFilterCategory(cat) {
        currentFilterCategory = cat;
        $('.ev-wa-filter-pills .ev-pill').removeClass('active');
        $('.ev-wa-filter-pills .ev-pill[data-cat=\"' + cat + '\"]').addClass('active');
        applyContactFilters();
    }
    window.evChatFilterCategory = evChatFilterCategory;

    // Filter Pencarian Instan Kontak
    function evChatFilterContacts(q) {
        var clearBtn = document.getElementById('ev-wa-search-clear');
        if (clearBtn) {
            clearBtn.style.display = (q && q.trim().length > 0) ? 'inline-block' : 'none';
        }
        applyContactFilters();
    }
    window.evChatFilterContacts = evChatFilterContacts;

    function evChatClearSearch() {
        var inp = document.getElementById('ev-wa-search-input');
        if (inp) {
            inp.value = '';
            evChatFilterContacts('');
            inp.focus();
        }
    }
    window.evChatClearSearch = evChatClearSearch;

    function applyContactFilters() {
        var q = (document.getElementById('ev-wa-search-input') ? document.getElementById('ev-wa-search-input').value : '').toLowerCase().trim();
        var cat = currentFilterCategory;

        var items = document.querySelectorAll('.ev-wa-contact-item');
        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            var type = item.getAttribute('data-type');
            var isOnline = (item.getAttribute('data-online') === '1');
            var isPusat = (item.getAttribute('data-pusat') === '1');
            var text = (item.textContent || '').toLowerCase();

            var matchSearch = (q === '' || text.indexOf(q) !== -1);
            var matchCat = true;

            if (cat === 'online') {
                matchCat = (type === 'ai' || isOnline);
            } else if (cat === 'cabang') {
                matchCat = (type === 'branch');
            } else if (cat === 'pusat') {
                matchCat = isPusat;
            }

            if (matchSearch && matchCat) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        }
    }

    // Pilih Target Percakapan
    function evChatSelectTarget(tabType, targetId, targetName, targetStatus, isOnline, isInit) {
        activeTab = tabType;
        activeTargetId = parseInt(targetId) || 0;
        activeTargetName = targetName;
        activeTargetStatus = targetStatus;
        activeTargetIsOnline = isOnline ? 1 : 0;

        localStorage.setItem('ev_wa_active_tab', activeTab);
        localStorage.setItem('ev_wa_target_id', activeTargetId);
        localStorage.setItem('ev_wa_target_name', activeTargetName);
        localStorage.setItem('ev_wa_target_status', activeTargetStatus);
        localStorage.setItem('ev_wa_target_online', activeTargetIsOnline);

        // Highlight active item di sidebar
        $('.ev-wa-contact-item').removeClass('active');
        if (tabType === 'ai') {
            $('#ev-item-ai').addClass('active');
        } else if (tabType === 'branch') {
            $('#ev-item-branch-' + activeTargetId).addClass('active');
        } else if (tabType === 'direct') {
            $('#ev-item-user-' + activeTargetId).addClass('active');
        }

        // Update header obrolan kanan
        var headAvatar = document.getElementById('ev-header-avatar');
        var headTitle = document.getElementById('ev-header-title');
        var headStatus = document.getElementById('ev-header-status');
        var inp = document.getElementById('ev-chat-input');

        if (headTitle) headTitle.textContent = targetName;

        if (tabType === 'ai') {
            if (headAvatar) {
                headAvatar.style.background = '#008069';
                headAvatar.innerHTML = '<i class=\"fa fa-android\"></i>';
            }
            if (headStatus) {
                headStatus.className = 'ev-wa-header-status online';
                headStatus.textContent = '🤖 Asisten Cerdas ERP & Analisis';
            }
            $('#ev-chat-prompt-chips').css('display', 'flex');
            $('#ev-header-ai-tools').css('display', 'inline-flex');
            if (inp) inp.placeholder = 'Tanyakan apapun ke ' + defaultBotName + '...';
        } else if (tabType === 'branch') {
            if (headAvatar) {
                headAvatar.style.background = '#0284c7';
                headAvatar.innerHTML = '<i class=\"fa fa-building-o\"></i>';
            }
            if (headStatus) {
                headStatus.className = 'ev-wa-header-status';
                headStatus.textContent = '🏢 Saluran Koordinasi Cabang';
            }
            $('#ev-chat-prompt-chips').hide();
            $('#ev-header-ai-tools').hide();
            if (inp) inp.placeholder = 'Ketik pesan untuk ' + targetName + '...';
        } else {
            // Direct User
            if (headAvatar) {
                headAvatar.style.background = getAvatarColor(targetName);
                headAvatar.innerHTML = getInitials(targetName);
            }
            if (headStatus) {
                if (isOnline) {
                    headStatus.className = 'ev-wa-header-status online';
                    headStatus.textContent = '🟢 Online';
                } else {
                    headStatus.className = 'ev-wa-header-status';
                    headStatus.textContent = targetStatus || '⚪ Offline';
                }
            }
            $('#ev-chat-prompt-chips').hide();
            $('#ev-header-ai-tools').hide();
            if (inp) inp.placeholder = 'Ketik pesan ke ' + targetName + '...';
        }

        // Pindah tampilan mobile ke panel chat
        mobileView = 'chat';
        handleResponsiveLayout();

        // Reset & muat percakapan
        lastMessageId = 0;
        var body = document.getElementById('ev-chat-body');
        if (body) {
            body.innerHTML = '<div id=\"ev-chat-empty-hint\"><i class=\"fa fa-spinner fa-spin\"></i> Memuat percakapan...</div>' +
                             '<div id=\"ev-chat-typing-indicator\"><i class=\"fa fa-spinner fa-spin\"></i> AI sedang berpikir...</div>';
        }

        evChatLoadMessages(true);

        if (!isInit && inp && window.innerWidth >= 768) {
            inp.focus();
        }
    }
    window.evChatSelectTarget = evChatSelectTarget;

    // Muat Daftar Kontak & Cabang dari Server
    function evChatLoadContacts() {
        $.ajax({
            url: '$base' + 'Chat/get_contacts',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    cachedCabangList = res.cabang || [];
                    cachedUserList = res.users || [];
                    cachedSnippets = res.snippets || { direct: {}, branch: {} };

                    renderBranchContacts(cachedCabangList);
                    renderUserContacts(cachedUserList);
                    applyContactFilters();
                    evChatCheckUnread();
                }
            }
        });
    }
    window.evChatLoadContacts = evChatLoadContacts;

    window.evChatSelectBranch = function(id) {
        if (id === -1) {
            evChatSelectTarget('branch', -1, 'Kantor Pusat', 'Saluran Koordinasi Pusat', 1);
            return;
        }
        for (var i = 0; i < cachedCabangList.length; i++) {
            if (parseInt(cachedCabangList[i].id) === parseInt(id)) {
                var isMy = (parseInt(id) === myCabangId);
                evChatSelectTarget('branch', parseInt(id), cachedCabangList[i].nama, isMy ? '🏢 Cabang Saya' : 'Saluran Cabang', 1);
                break;
            }
        }
    };

    window.evChatSelectUser = function(id) {
        for (var i = 0; i < cachedUserList.length; i++) {
            if (parseInt(cachedUserList[i].id) === parseInt(id)) {
                var u = cachedUserList[i];
                var isOnline = (parseInt(u.is_online) === 1);
                var sub = isOnline ? '🟢 Online' : (u.last_dtime_active ? '⚪ Aktif ' + u.last_dtime_active.substring(11, 16) : '⚪ Offline');
                evChatSelectTarget('direct', parseInt(id), u.nama, sub, isOnline ? 1 : 0);
                break;
            }
        }
    };

    function renderBranchContacts(cabangs) {
        var container = document.getElementById('ev-wa-branch-list');
        if (!container) return;
        var html = '';

        // Item Kantor Pusat jika saya bukan orang pusat
        if (isCenter == 0) {
            var activeClass = (activeTab === 'branch' && activeTargetId === -1) ? ' active' : '';
            var snipPusat = (cachedSnippets && cachedSnippets.branch) ? cachedSnippets.branch[-1] : null;
            var snipTextPusat = '<div class=\"ev-wa-contact-sub\">Saluran Koordinasi Pusat</div>';
            var snipTimePusat = '';
            if (snipPusat) {
                var pMsg = snipPusat.message || '';
                var isPUrgent = !!snipPusat.is_urgent;
                var cleanPMsg = pMsg.replace(/\\[🚨 URGENT\\]\\s*/g, '').replace(/\\[URGENT\\]\\s*/g, '');
                var pSender = snipPusat.sender_name ? (escapeHtml(snipPusat.sender_name) + ': ') : '';
                var pIcon = isPUrgent ? '🚨 ' : '';
                var pClass = isPUrgent ? ' urgent' : '';
                snipTextPusat = '<div class=\"ev-wa-contact-snippet' + pClass + '\" title=\"' + escapeHtml(cleanPMsg) + '\">' + pIcon + pSender + escapeHtml(cleanPMsg) + '</div>';
                if (snipPusat.created_at) {
                    snipTimePusat = '<span class=\"ev-wa-contact-time\">' + snipPusat.created_at.substring(11, 16) + '</span>';
                }
            }

            html += '<div class=\"ev-wa-contact-item ev-contact-branch' + activeClass + '\" id=\"ev-item-branch--1\" data-type=\"branch\" data-pusat=\"1\" data-online=\"1\" onclick=\"evChatSelectBranch(-1)\">' +
                        '<div class=\"ev-wa-avatar-wrap\">' +
                            '<div class=\"ev-wa-avatar\" style=\"background:#475569;\"><i class=\"fa fa-building\"></i></div>' +
                            '<span class=\"ev-wa-dot online\"></span>' +
                        '</div>' +
                        '<div class=\"ev-wa-contact-content\">' +
                            '<div class=\"ev-wa-contact-row-top\">' +
                                '<div class=\"ev-wa-contact-name\">Kantor Pusat</div>' +
                                '<div style=\"display:flex;align-items:center;\">' +
                                    snipTimePusat +
                                    '<span class=\"ev-wa-item-badge\" id=\"ev-badge-branch--1\">0</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class=\"ev-wa-contact-row-bottom\">' +
                                snipTextPusat +
                            '</div>' +
                        '</div>' +
                    '</div>';
        }

        for (var i = 0; i < cabangs.length; i++) {
            var c = cabangs[i];
            var cId = parseInt(c.id);
            var activeClass = (activeTab === 'branch' && activeTargetId === cId) ? ' active' : '';
            var isMyBranch = (cId === myCabangId);
            var subLabel = isMyBranch ? '🏢 Cabang Saya' : 'Saluran Cabang';

            var snip = (cachedSnippets && cachedSnippets.branch) ? cachedSnippets.branch[cId] : null;
            var snipText = '<div class=\"ev-wa-contact-sub\">' + subLabel + '</div>';
            var snipTime = '';
            if (snip) {
                var sMsg = snip.message || '';
                var isSnipUrgent = !!snip.is_urgent;
                var cleanSMsg = sMsg.replace(/\\[🚨 URGENT\\]\\s*/g, '').replace(/\\[URGENT\\]\\s*/g, '');
                var senderPrefix = snip.sender_name ? (escapeHtml(snip.sender_name) + ': ') : '';
                var urgentIcon = isSnipUrgent ? '🚨 ' : '';
                var urgentClass = isSnipUrgent ? ' urgent' : '';
                snipText = '<div class=\"ev-wa-contact-snippet' + urgentClass + '\" title=\"' + escapeHtml(cleanSMsg) + '\">' + urgentIcon + senderPrefix + escapeHtml(cleanSMsg) + '</div>';
                if (snip.created_at) {
                    snipTime = '<span class=\"ev-wa-contact-time\">' + snip.created_at.substring(11, 16) + '</span>';
                }
            }

            html += '<div class=\"ev-wa-contact-item ev-contact-branch' + activeClass + '\" id=\"ev-item-branch-' + cId + '\" data-type=\"branch\" data-pusat=\"0\" data-online=\"1\" onclick=\"evChatSelectBranch(' + cId + ')\">' +
                        '<div class=\"ev-wa-avatar-wrap\">' +
                            '<div class=\"ev-wa-avatar\" style=\"background:#0284c7;\"><i class=\"fa fa-building-o\"></i></div>' +
                            '<span class=\"ev-wa-dot online\"></span>' +
                        '</div>' +
                        '<div class=\"ev-wa-contact-content\">' +
                            '<div class=\"ev-wa-contact-row-top\">' +
                                '<div class=\"ev-wa-contact-name\">' + escapeHtml(c.nama) + '</div>' +
                                '<div style=\"display:flex;align-items:center;\">' +
                                    snipTime +
                                    '<span class=\"ev-wa-item-badge\" id=\"ev-badge-branch-' + cId + '\">0</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class=\"ev-wa-contact-row-bottom\">' +
                                snipText +
                            '</div>' +
                        '</div>' +
                    '</div>';
        }

        container.innerHTML = html;
    }

    function renderUserContacts(users) {
        var container = document.getElementById('ev-wa-user-list');
        if (!container) return;
        var html = '';

        for (var i = 0; i < users.length; i++) {
            var u = users[i];
            var uId = parseInt(u.id);
            var isOnline = (parseInt(u.is_online) === 1);
            var isPusat = (parseInt(u.cabang_id) === -1) ? 1 : 0;
            var activeClass = (activeTab === 'direct' && activeTargetId === uId) ? ' active' : '';
            var initial = getInitials(u.nama);
            var col = getAvatarColor(u.nama);

            var lastActiveStr = '';
            if (isOnline) {
                lastActiveStr = '🟢 Online';
            } else if (u.last_dtime_active) {
                lastActiveStr = '⚪ Aktif ' + u.last_dtime_active.substring(11, 16);
            } else {
                lastActiveStr = '⚪ Offline';
            }

            var subTitle = escapeHtml(u.cabang_nama || 'Cabang');

            var snip = (cachedSnippets && cachedSnippets.direct) ? cachedSnippets.direct[uId] : null;
            var snipText = '<div class=\"ev-wa-contact-sub\">' + subTitle + '</div>';
            var snipTime = '';
            if (snip) {
                var sMsg = snip.message || '';
                var isSnipUrgent = !!snip.is_urgent;
                var cleanSMsg = sMsg.replace(/\\[🚨 URGENT\\]\\s*/g, '').replace(/\\[URGENT\\]\\s*/g, '');
                var prefix = snip.is_mine ? 'Anda: ' : '';
                var urgentIcon = isSnipUrgent ? '🚨 ' : '';
                var urgentClass = isSnipUrgent ? ' urgent' : '';
                snipText = '<div class=\"ev-wa-contact-snippet' + urgentClass + '\" title=\"' + escapeHtml(cleanSMsg) + '\">' + urgentIcon + prefix + escapeHtml(cleanSMsg) + '</div>';
                if (snip.created_at) {
                    snipTime = '<span class=\"ev-wa-contact-time\">' + snip.created_at.substring(11, 16) + '</span>';
                }
            }

            html += '<div class=\"ev-wa-contact-item ev-contact-user' + activeClass + '\" id=\"ev-item-user-' + uId + '\" data-type=\"direct\" data-pusat=\"' + isPusat + '\" data-online=\"' + (isOnline ? '1' : '0') + '\" onclick=\"evChatSelectUser(' + uId + ')\">' +
                        '<div class=\"ev-wa-avatar-wrap\">' +
                            '<div class=\"ev-wa-avatar\" style=\"background:' + col + ';\">' + initial + '</div>' +
                            '<span class=\"ev-wa-dot ' + (isOnline ? 'online' : 'offline') + '\"></span>' +
                        '</div>' +
                        '<div class=\"ev-wa-contact-content\">' +
                            '<div class=\"ev-wa-contact-row-top\">' +
                                '<div class=\"ev-wa-contact-name\">' + escapeHtml(u.nama) + '</div>' +
                                '<div style=\"display:flex;align-items:center;\">' +
                                    snipTime +
                                    '<span class=\"ev-wa-item-badge\" id=\"ev-badge-user-' + uId + '\">0</span>' +
                                '</div>' +
                            '</div>' +
                            '<div class=\"ev-wa-contact-row-bottom\">' +
                                snipText +
                            '</div>' +
                        '</div>' +
                    '</div>';
        }

        container.innerHTML = html;
    }

    // Quick Prompts Click
    function evChatQuickPrompt(text) {
        var inp = document.getElementById('ev-chat-input');
        if (inp) {
            inp.value = text;
            evChatSendMessage();
        }
    }
    window.evChatQuickPrompt = evChatQuickPrompt;

    // Muat Pesan Obrolan
    function evChatLoadMessages(forceScroll) {
        if (isFetching) return;

        var targetId = activeTargetId;
        if (activeTab === 'branch') {
            if (targetId === 0 && isCenter == 0) {
                targetId = myCabangId;
                activeTargetId = targetId;
            }
        } else if (activeTab === 'direct') {
            if (targetId === 0) {
                var body = document.getElementById('ev-chat-body');
                if (body && !document.getElementById('ev-chat-empty-hint')) {
                    body.innerHTML = '<div id=\"ev-chat-empty-hint\">Silakan pilih rekan kerja terlebih dahulu.</div>';
                }
                return;
            }
        }

        isFetching = true;
        var url = '$base' + 'Chat/fetch_messages?chat_type=' + activeTab + '&target_id=' + targetId + '&last_id=' + lastMessageId;
        if (isWindowOpen) {
            url += '&mark_read=1';
        }

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                isFetching = false;
                if (res && res.status === 'success' && res.messages) {
                    var body = document.getElementById('ev-chat-body');
                    if (!body) return;

                    var hint = document.getElementById('ev-chat-empty-hint');
                    if (hint && res.messages.length > 0) {
                        hint.parentNode.removeChild(hint);
                    }

                    var hasNewIncoming = false;
                    var hasNewUrgent = false;
                    var hasNewAny = false;

                    for (var i = 0; i < res.messages.length; i++) {
                        var m = res.messages[i];
                        var mId = parseInt(m.id);

                        if (mId > lastMessageId) {
                            lastMessageId = mId;
                        }

                        // Deduplikasi mutlak DOM
                        if (document.getElementById('ev-msg-' + mId)) {
                            // Update read status jika berubah
                            var tickEl = document.querySelector('#ev-msg-' + mId + ' .ev-chat-tick');
                            if (tickEl && parseInt(m.is_read) === 1) {
                                tickEl.className = 'ev-chat-tick read';
                            }
                            continue;
                        }

                        appendMessageBubble(m, body);
                        hasNewAny = true;

                        if (parseInt(m.sender_id) !== myId) {
                            hasNewIncoming = true;
                            var mText = m.message || '';
                            if (mText.indexOf('[🚨 URGENT]') !== -1 || mText.indexOf('[URGENT]') !== -1) {
                                hasNewUrgent = true;
                            }
                        }
                    }

                    if (hasNewIncoming && !forceScroll) {
                        if (hasNewUrgent) {
                            playUrgentSound();
                        } else {
                            playChimeSound();
                        }
                    }

                    if (hasNewAny || forceScroll) {
                        body.scrollTop = body.scrollHeight;
                    }
                }
            },
            error: function() {
                isFetching = false;
            }
        });
    }
    window.evChatLoadMessages = evChatLoadMessages;

    function appendMessageBubble(m, container) {
        if (!m || !m.id) return;
        var mId = parseInt(m.id);

        if (document.getElementById('ev-msg-' + mId)) {
            return;
        }

        var isMine = (parseInt(m.sender_id) === myId);
        var isAi = (m.chat_type === 'ai' && parseInt(m.sender_id) === 0);

        var rawMsg = m.message || '';
        var isUrgent = (rawMsg.indexOf('[🚨 URGENT]') !== -1 || rawMsg.indexOf('[URGENT]') !== -1);
        var cleanMsg = rawMsg.replace(/\[🚨 URGENT\]\s*/g, '').replace(/\[URGENT\]\s*/g, '');

        var urgentBanner = '';
        if (isUrgent) {
            urgentBanner = '<div class=\"ev-chat-urgent-banner\"><i class=\"fa fa-bell\"></i> PRIORITAS MENDESAK</div>';
        }

        var div = document.createElement('div');
        div.id = 'ev-msg-' + mId;

        var urgentClass = isUrgent ? ' urgent' : '';
        if (isAi) {
            div.className = 'ev-chat-msg ev-chat-msg-ai' + urgentClass;
        } else {
            div.className = 'ev-chat-msg ' + (isMine ? 'ev-chat-msg-mine' : 'ev-chat-msg-other') + urgentClass;
        }

        var senderHtml = '';
        if (isAi) {
            senderHtml = '<div class=\"ev-chat-msg-sender\"><i class=\"fa fa-android\"></i> ' + escapeHtml(defaultBotName) + '</div>';
        } else if (!isMine && (m.chat_type === 'branch' || !m.chat_type)) {
            senderHtml = '<div class=\"ev-chat-msg-sender\">' + escapeHtml(m.sender_name) + ' (' + escapeHtml(m.sender_cabang_nama || '') + ')</div>';
        }

        var refHtml = '';
        if (m.ref_transaksi && m.ref_transaksi.trim() !== '') {
            var refSafe = escapeHtml(m.ref_transaksi.trim());
            refHtml = '<div class=\"ev-chat-trx-card\">' +
                          '<div class=\"ev-chat-trx-card-head\"><i class=\"fa fa-file-text-o\"></i> Dokumen ERP Terlampir</div>' +
                          '<div class=\"ev-chat-trx-card-body\">' +
                              '<span class=\"ev-chat-trx-card-num\">' + refSafe + '</span>' +
                              '<button type=\"button\" class=\"ev-chat-trx-btn-resume\" onclick=\"evChatOpenResume(&quot;' + refSafe + '&quot;)\" title=\"Buka Resume Resmi Transaksi\">' +
                                  '<i class=\"fa fa-external-link\"></i> Resume Transaksi' +
                              '</button>' +
                          '</div>' +
                      '</div>';
        }

        var timeOnly = (m.created_at || '').substring(11, 16);
        var formattedMsg = isAi ? renderAiHtmlMessage(cleanMsg) : escapeHtml(cleanMsg).replace(/\\n/g, '<br>');

        var tickHtml = '';
        if (isMine) {
            var isRead = (parseInt(m.is_read) === 1);
            tickHtml = '<span class=\"ev-chat-tick ' + (isRead ? 'read' : 'unread') + '\" title=\"' + (isRead ? 'Sudah dibaca' : 'Terkirim') + '\">&#10003;&#10003;</span>';
        }

        var actionsHtml = '<div class=\"ev-msg-actions\">' +
                              '<button type=\"button\" class=\"ev-msg-act-btn\" onclick=\"evChatCopyMsg(this)\" title=\"Salin Pesan\"><i class=\"fa fa-clone\"></i></button>' +
                              '<button type=\"button\" class=\"ev-msg-act-btn\" onclick=\"evChatQuoteMsg(this)\" title=\"Kutip / Balas Pesan\"><i class=\"fa fa-reply\"></i></button>' +
                          '</div>';

        div.innerHTML = actionsHtml +
                        urgentBanner +
                        senderHtml +
                        '<div class=\"ev-chat-msg-content\">' + formattedMsg + '</div>' +
                        refHtml +
                        '<div class=\"ev-chat-msg-meta\">' +
                            '<span>' + timeOnly + '</span>' +
                            tickHtml +
                        '</div>';

        var typing = document.getElementById('ev-chat-typing-indicator');
        if (typing && container.contains(typing)) {
            container.insertBefore(div, typing);
        } else {
            container.appendChild(div);
        }
    }

    // Kirim Pesan
    function evChatSendMessage() {
        var inp = document.getElementById('ev-chat-input');
        if (!inp) return;
        var text = (inp.value || '').trim();
        if (text === '') return;

        // Cek dukungan Quick Slash Commands
        if (text.indexOf('/urgent ') === 0) {
            isUrgentActive = true;
            text = text.substring(8).trim();
        } else if (text.indexOf('/trx ') === 0) {
            var trxNum = text.substring(5).trim();
            if (trxNum) {
                attachedRefTrx = trxNum;
                var tb = document.getElementById('ev-chat-ref-toolbar');
                var lbl = document.getElementById('ev-chat-ref-label');
                if (tb && lbl) {
                    lbl.textContent = attachedRefTrx;
                    tb.style.display = 'flex';
                }
            }
            inp.value = '';
            return;
        } else if (text.indexOf('/resume ') === 0) {
            var rNum = text.substring(8).trim();
            if (rNum) {
                evChatOpenResume(rNum);
            }
            inp.value = '';
            return;
        } else if (text === '/clear') {
            evChatClearRef();
            inp.value = '';
            return;
        }

        if (text === '') return;

        if (isUrgentActive) {
            text = '[🚨 URGENT] ' + text;
            evChatSetUrgent(false);
        }

        var targetId = activeTargetId;
        var targetName = activeTargetName;

        if (activeTab === 'branch') {
            if (targetId === 0 && isCenter == 0) {
                targetId = myCabangId;
            }
            if (targetId === 0) {
                alert('Silakan pilih cabang tujuan terlebih dahulu.');
                return;
            }
        } else if (activeTab === 'direct') {
            if (targetId === 0) {
                alert('Silakan pilih rekan kerja tujuan terlebih dahulu.');
                return;
            }
        }

        inp.value = '';
        inp.disabled = true;

        if (activeTab === 'ai') {
            var typing = document.getElementById('ev-chat-typing-indicator');
            if (typing) typing.style.display = 'block';

            var body = document.getElementById('ev-chat-body');
            if (body) body.scrollTop = body.scrollHeight;

            $.ajax({
                url: '$base' + 'Chat/send_ai_message',
                type: 'POST',
                data: { message: text, ref_transaksi: attachedRefTrx },
                dataType: 'json',
                success: function(res) {
                    inp.disabled = false;
                    inp.focus();
                    evChatClearRef();
                    if (typing) typing.style.display = 'none';

                    if (res && res.status === 'success') {
                        var hint = document.getElementById('ev-chat-empty-hint');
                        if (hint && hint.parentNode) hint.parentNode.removeChild(hint);

                        if (res.user_message) {
                            lastMessageId = Math.max(lastMessageId, parseInt(res.user_message.id));
                            appendMessageBubble(res.user_message, body);
                        }
                        if (res.ai_message) {
                            lastMessageId = Math.max(lastMessageId, parseInt(res.ai_message.id));
                            appendMessageBubble(res.ai_message, body);
                            playChimeSound();
                        }
                        if (body) body.scrollTop = body.scrollHeight;
                    } else if (res && res.message) {
                        alert(res.message);
                    }
                },
                error: function() {
                    inp.disabled = false;
                    inp.focus();
                    if (typing) typing.style.display = 'none';
                    alert('Gagal menghubungi ' + defaultBotName + '.');
                }
            });
        } else {
            var payload = {
                chat_type: activeTab,
                target_id: targetId,
                target_name: targetName,
                message: text,
                ref_transaksi: attachedRefTrx
            };

            $.ajax({
                url: '$base' + 'Chat/send_message',
                type: 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    inp.disabled = false;
                    inp.focus();
                    evChatClearRef();

                    if (res && res.status === 'success' && res.data) {
                        var body = document.getElementById('ev-chat-body');
                        var hint = document.getElementById('ev-chat-empty-hint');
                        if (hint && hint.parentNode) hint.parentNode.removeChild(hint);

                        lastMessageId = Math.max(lastMessageId, parseInt(res.data.id));
                        appendMessageBubble(res.data, body);
                        if (body) body.scrollTop = body.scrollHeight;
                    } else if (res && res.message) {
                        alert(res.message);
                    }
                },
                error: function() {
                    inp.disabled = false;
                    inp.focus();
                    alert('Gagal mengirim pesan.');
                }
            });
        }
    }
    window.evChatSendMessage = evChatSendMessage;

    // Polling Unread Count
    function evChatCheckUnread() {
        $.ajax({
            url: '$base' + 'Chat/unread_count',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success' && res.data) {
                    var d = res.data;
                    var total = parseInt(d.total_unread) || 0;

                    var b = document.getElementById('ev-chat-badge');
                    if (b) {
                        if (total > 0 && !isWindowOpen) {
                            b.textContent = total;
                            b.style.display = 'inline-flex';
                        } else {
                            b.style.display = 'none';
                            b.textContent = '';
                        }
                    }

                    // Update AI badge
                    updateContactBadge('ai', d.ai_total);

                    // Update Direct users unread badges
                    if (d.direct_breakdown) {
                        for (var uId in d.direct_breakdown) {
                            updateContactBadge('user-' + uId, d.direct_breakdown[uId]);
                        }
                    }

                    // Update Branch unread badges
                    if (d.branch_breakdown) {
                        for (var bId in d.branch_breakdown) {
                            updateContactBadge('branch-' + bId, d.branch_breakdown[bId]);
                        }
                    }
                }
            }
        });
    }
    window.evChatCheckUnread = evChatCheckUnread;

    function updateContactBadge(key, count) {
        var el = document.getElementById('ev-badge-' + key);
        if (!el) return;
        var cnt = parseInt(count) || 0;
        if (cnt > 0) {
            el.textContent = cnt;
            el.style.display = 'inline-block';
        } else {
            el.style.display = 'none';
        }
    }

    // Lampirkan Transaksi Aktif
    function evChatAttachCurrentTrx() {
        var docUrl = window.location.href;
        var foundTrx = '';

        var match = docUrl.match(/(\\d{3,4}\\.\\d{3,8})/);
        if (match && match[1]) {
            foundTrx = match[1];
        } else {
            var el = document.getElementById('bookingNumber') || document.getElementById('nomerTransaksi') || document.querySelector('[name=\"nomer\"]');
            if (el && el.value && el.value.indexOf('.') !== -1) {
                foundTrx = el.value.trim();
            }
        }

        if (!foundTrx) {
            foundTrx = prompt('Masukkan Nomor Dokumen / Transaksi:');
        }

        if (foundTrx && foundTrx.trim() !== '') {
            attachedRefTrx = foundTrx.trim();
            var tb = document.getElementById('ev-chat-ref-toolbar');
            var lbl = document.getElementById('ev-chat-ref-label');
            if (tb && lbl) {
                lbl.textContent = attachedRefTrx;
                tb.style.display = 'flex';
            }
        }
    }
    window.evChatAttachCurrentTrx = evChatAttachCurrentTrx;

    function evChatClearRef() {
        attachedRefTrx = '';
        var tb = document.getElementById('ev-chat-ref-toolbar');
        if (tb) tb.style.display = 'none';
    }
    window.evChatClearRef = evChatClearRef;

    function evChatOpenResume(nomer) {
        if (typeof showModal === 'function') {
            showModal('$base' + 'Transaksi/viewResume/' + encodeURIComponent(nomer), 'Resume ' + nomer);
        } else if (typeof top.BootstrapDialog !== 'undefined') {
            top.BootstrapDialog.show({
                title: 'Resume Transaksi ' + nomer,
                message: '<iframe src=\"$base' + 'Transaksi/viewResume/' + encodeURIComponent(nomer) + '\" style=\"width:100%; height:450px; border:none;\"></iframe>',
                size: top.BootstrapDialog.SIZE_WIDE
            });
        } else {
            window.open('$base' + 'Transaksi/viewResume/' + encodeURIComponent(nomer), '_blank');
        }
    }
    window.evChatOpenResume = evChatOpenResume;

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Render Balasan AI: Mendukung format HTML normal langsung dan normalisasi fallback sintaks Markdown
    function renderAiHtmlMessage(raw) {
        if (!raw) return '';
        var str = String(raw).trim();

        // 1. Normalisasi Deep Link markdown [teks](url) menjadi tag <a> jika masih ada
        str = str.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href=\"$2\" target=\"_blank\">$1</a>');

        // 2. Deteksi apakah balasan mengandung struktur HTML utama
        var hasHtml = /<(table|thead|tbody|tr|th|td|b|strong|i|em|u|ul|ol|li|p|br|div|span|h[1-6]|a|hr)[\s>\/]/i.test(str);

        if (hasHtml) {
            // Normalisasi sisa sintaks bold *teks* atau **teks** di luar tag HTML
            str = str.replace(/\\*\\*([^*<\\r\\n]+)\\*\\*/g, '<b>$1</b>');
            str = str.replace(/(^|[^\\*<\\w])\\*([^*<\\r\\n]+)\\*([^\\*<\\w]|$)/g, '$1<b>$2</b>$3');
            // Normalisasi sisa sintaks italic _teks_
            str = str.replace(/(^|[^_<\\w])_([^_<\\r\\n]+)_([^_<\\w]|$)/g, '$1<i>$2</i>$3');

            // Bungkus tabel HTML dengan container toolbar Salin ke Excel
            if (str.indexOf('<table') !== -1 && str.indexOf('ev-chat-table-wrap') === -1) {
                var toolBarHtml = '<div class=\"ev-chat-table-wrap\"><div class=\"ev-table-header-tools\"><span><i class=\"fa fa-table\"></i> Data Tabel</span><button type=\"button\" class=\"ev-table-copy-btn\" onclick=\"evChatCopyTable(this)\" title=\"Salin ke Clipboard (Format Excel/Spreadsheet)\"><i class=\"fa fa-file-excel-o\"></i> Salin ke Excel</button></div>';
                str = str.replace(/<table([^>]*)>/gi, toolBarHtml + '<table class=\"ev-chat-table\"$1>');
                str = str.replace(/<\\/table>/gi, '</table></div>');
            }

            // Konversi baris baru menjadi tag br jika pesan bukan dokumen tabel atau list HTML
            if (str.indexOf('<table') === -1 && str.indexOf('<ul') === -1 && str.indexOf('<ol') === -1 && str.indexOf('<p') === -1) {
                str = str.replace(/\\n/g, '<br>');
            }
            return str;
        }

        // 3. Fallback jika AI masih mengembalikan teks polos atau format markdown/tabel teks lama:
        var lines = str.split('\\n');
        var outHtml = [];
        var inTable = false;
        var tableRows = [];
        var inList = false;
        var listType = 'ul';

        function flushTable() {
            if (!inTable || tableRows.length === 0) return;
            var tblHtml = '<div class=\"ev-chat-table-wrap\">' +
                              '<div class=\"ev-table-header-tools\">' +
                                  '<span><i class=\"fa fa-table\"></i> Data Tabel</span>' +
                                  '<button type=\"button\" class=\"ev-table-copy-btn\" onclick=\"evChatCopyTable(this)\" title=\"Salin ke Clipboard (Format Excel/Spreadsheet)\">' +
                                      '<i class=\"fa fa-file-excel-o\"></i> Salin ke Excel' +
                                  '</button>' +
                              '</div>' +
                              '<table class=\"ev-chat-table\">';
            var isHeader = true;

            for (var i = 0; i < tableRows.length; i++) {
                var row = tableRows[i].trim();
                if (row.indexOf('|') === 0) row = row.substring(1);
                if (row.lastIndexOf('|') === row.length - 1) row = row.substring(0, row.length - 1);
                var cells = row.split('|');

                var isSep = true;
                for (var c = 0; c < cells.length; c++) {
                    if (!cells[c].trim().match(/^:?-+:?$/)) {
                        isSep = false;
                        break;
                    }
                }
                if (isSep) {
                    isHeader = false;
                    continue;
                }

                if (isHeader) {
                    tblHtml += '<thead><tr>';
                    for (var h = 0; h < cells.length; h++) {
                        tblHtml += '<th>' + formatInline(cells[h].trim()) + '</th>';
                    }
                    tblHtml += '</tr></thead><tbody>';
                    isHeader = false;
                } else {
                    tblHtml += '<tr>';
                    for (var d = 0; d < cells.length; d++) {
                        tblHtml += '<td>' + formatInline(cells[d].trim()) + '</td>';
                    }
                    tblHtml += '</tr>';
                }
            }
            tblHtml += '</tbody></table></div>';
            outHtml.push(tblHtml);
            inTable = false;
            tableRows = [];
        }

        function flushList() {
            if (!inList) return;
            outHtml.push('</' + listType + '>');
            inList = false;
        }

        function formatInline(text) {
            var s = escapeHtml(text);
            s = s.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href=\"$2\" target=\"_blank\">$1</a>');
            s = s.replace(/\\*\\*([^*]+)\\*\\*/g, '<b>$1</b>');
            s = s.replace(/(^|[^\\*])\\*([^\\*\\r\\n]+)\\*([^\\*]|$)/g, '$1<b>$2</b>$3');
            s = s.replace(/(^|[^_])_([^\\r\\n_]+)_([^_]|$)/g, '$1<i>$2</i>$3');
            s = s.replace(/`([^`]+)`/g, '<code style=\"background:#e2e8f0; padding:1px 4px; border-radius:3px; font-size:11px;\">$1</code>');
            return s;
        }

        for (var i = 0; i < lines.length; i++) {
            var rawLine = lines[i];
            var trimmed = rawLine.trim();

            if (trimmed.indexOf('|') !== -1 && trimmed.length >= 3) {
                flushList();
                inTable = true;
                tableRows.push(trimmed);
                continue;
            } else {
                flushTable();
            }

            if (trimmed === '---' || trimmed === '***' || trimmed === '___') {
                flushList();
                outHtml.push('<hr style=\"margin:8px 0; border:0; border-top:1px solid #cbd5e1;\">');
                continue;
            }

            var headingMatch = trimmed.match(/^(#{1,4})\\s+(.+)$/);
            if (headingMatch) {
                flushList();
                outHtml.push('<h4 style=\"margin:8px 0 4px 0; font-weight:bold; color:#008069; font-size:12.5px;\">' + formatInline(headingMatch[2]) + '</h4>');
                continue;
            }

            var ulMatch = trimmed.match(/^[-*]\\s+(.+)$/);
            if (ulMatch) {
                if (!inList || listType !== 'ul') {
                    flushList();
                    inList = true;
                    listType = 'ul';
                    outHtml.push('<ul style=\"margin:4px 0; padding-left:18px;\">');
                }
                outHtml.push('<li>' + formatInline(ulMatch[1]) + '</li>');
                continue;
            }

            var olMatch = trimmed.match(/^(\\d+)\\.\\s+(.+)$/);
            if (olMatch) {
                if (!inList || listType !== 'ol') {
                    flushList();
                    inList = true;
                    listType = 'ol';
                    outHtml.push('<ol style=\"margin:4px 0; padding-left:18px;\">');
                }
                outHtml.push('<li>' + formatInline(olMatch[2]) + '</li>');
                continue;
            }

            flushList();

            if (trimmed === '') {
                outHtml.push('<div style=\"height:6px;\"></div>');
            } else {
                outHtml.push('<div>' + formatInline(trimmed) + '</div>');
            }
        }

        flushTable();
        flushList();

        return outHtml.join('');
    }

    $(document).ready(function() {
        // Pastikan Chat Trigger terpasang rapi di taskbar (#chat_ai_ds / #chat_ai_mb)
        attachChatTrigger();

        // Inisialisasi Resizer Drag Handler untuk mengatur lebar obrolan
        if (typeof initWindowResizer === 'function') {
            initWindowResizer();
        }

        var inp = document.getElementById('ev-chat-input');
        if (inp) {
            inp.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    evChatSendMessage();
                }
            });
        }

        // Cek unread awal
        setTimeout(function() {
            if (typeof evChatCheckUnread === 'function') evChatCheckUnread();
        }, 1000);

        // Polling loop unread count tiap 3 detik
        unreadTimer = setInterval(function() {
            if (typeof evChatCheckUnread === 'function') evChatCheckUnread();
        }, 3000);

        // Polling loop pesan realtime tiap 1.5 detik jika jendela terbuka
        pollingTimer = setInterval(function() {
            if (isWindowOpen && typeof evChatLoadMessages === 'function' && (activeTab === 'ai' || (activeTab === 'branch' && activeTargetId !== 0) || (activeTab === 'direct' && activeTargetId !== 0))) {
                evChatLoadMessages(false);
            }
        }, 1500);
    });
})();
</script>
<!-- END ERP MESSENGER & AI HUB WIDGET -->
";

        return $html;
    }
}

// END OF COMPLETE REPEATED LOGIC
