<?php
// START OF COMPLETE REPEATED LOGIC

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id']) || empty($this->session->login['id'])) {
            gotoLogin();
        }

        $this->load->model('Mdls/MdlSetting');
        $this->load->library('Layout');
        $this->load->helper('he_menu');
        $this->load->helper('he_url');
        $this->load->config('ai_chat', TRUE);

        $memberships = isset($this->session->login['membership']) && is_array($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $isDev = isset($this->session->login['debuger']) && $this->session->login['debuger'] == 1;
        $isAllowed = in_array('c_holding', $memberships) || in_array('root', $memberships) || in_array('superman', $memberships) || $isDev;
        if (!$isAllowed) {
            show_error("Akses ditolak. Pengaturan Master Sistem hanya dapat diakses oleh Manajemen Pusat (Holding) dan Administrator Sistem.", 403, "Akses Terbatas");
    }
    }

    public function index($activeTabParam = null)
    {
        $st = new MdlSetting();

        // 1. Data Pengaturan Master Proyek
        $projectSettings = $st->get_all_project_settings();
        $swMaster = $projectSettings['switch_pelaksana_enabled'];
        $swIntExt = $projectSettings['allow_internal_to_external'];
        $swExtInt = $projectSettings['allow_external_to_internal'];

        // 2. Data Pengaturan Messenger & AI
        $isActiveMessenger = $st->get_messenger_ai_status();
        $aiSettings = $st->get_all_ai_settings();
        $fileConfig = $this->config->item('ai_chat');

        $companyName = !empty($fileConfig['company_name']) ? $fileConfig['company_name'] : 'Everest ERP';
        $botName = !empty($fileConfig['bot_name']) ? $fileConfig['bot_name'] : 'Everest AI Copilot';

        $activeProvider = !empty($aiSettings['ai_provider']) ? $aiSettings['ai_provider'] : (isset($fileConfig['provider']) ? $fileConfig['provider'] : 'groq');
        $activeApiKey = !empty($aiSettings['ai_api_key']) ? $aiSettings['ai_api_key'] : (isset($fileConfig['api_key']) ? $fileConfig['api_key'] : '');
        $activeModel = !empty($aiSettings['ai_model']) ? $aiSettings['ai_model'] : (isset($fileConfig['model']) ? $fileConfig['model'] : 'llama-3.3-70b-versatile');
        $activeEndpoint = !empty($aiSettings['ai_endpoint']) ? $aiSettings['ai_endpoint'] : (isset($fileConfig['custom_endpoint']) ? $fileConfig['custom_endpoint'] : '');

        // Tab aktif awal
        $initialTab = 'project';
        if ($activeTabParam === 'messenger' || (isset($_GET['tab']) && $_GET['tab'] === 'messenger')) {
            $initialTab = 'messenger';
        }

        $html = "
        <style>
            .setting-card {
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                transition: all 0.2s ease;
            }
            .setting-card:hover {
                border-color: #cbd5e1;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            }
            .setting-header-flex {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 15px;
            }
            .custom-switch {
                position: relative;
                display: inline-block;
                width: 54px;
                height: 30px;
                margin: 0;
                flex-shrink: 0;
            }
            .custom-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .custom-slider {
                position: absolute;
                cursor: pointer;
                top: 0; left: 0; right: 0; bottom: 0;
                background-color: #cbd5e1;
                transition: .3s;
                border-radius: 34px;
            }
            .custom-slider:before {
                position: absolute;
                content: '';
                height: 22px;
                width: 22px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .3s;
                border-radius: 50%;
                box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            }
            input:checked + .custom-slider {
                background-color: #00a65a;
            }
            input:checked + .custom-slider:before {
                transform: translateX(24px);
            }
            input:disabled + .custom-slider {
                opacity: 0.5;
                cursor: not-allowed;
            }
            .category-nav-item {
                display: block;
                padding: 12px 16px;
                color: #334155;
                font-weight: 600;
                font-size: 14px;
                text-decoration: none;
                border-left: 3px solid transparent;
                transition: all 0.2s;
                cursor: pointer;
            }
            .category-nav-item:hover {
                background: #f1f5f9;
                color: #0284c7;
                text-decoration: none;
            }
            .category-nav-item.active {
                background: #e0f2fe;
                color: #0284c7;
                border-left-color: #0284c7;
            }
            .badge-status {
                font-size: 11.5px;
                padding: 4px 8px;
                border-radius: 4px;
                font-weight: 600;
                letter-spacing: 0.5px;
            }
            .badge-active {
                background: #dcfce7;
                color: #166534;
                border: 1px solid #bbf7d0;
            }
            .badge-inactive {
                background: #f1f5f9;
                color: #64748b;
                border: 1px solid #e2e8f0;
            }
            .badge-danger {
                background: #fee2e2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }
            .impact-box {
                background: #f8fafc;
                border-left: 4px solid #3b82f6;
                padding: 10px 14px;
                margin-top: 12px;
                font-size: 12px;
                color: #475569;
                border-radius: 0 6px 6px 0;
            }
            .btn-circle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        </style>

        <div class='row' style='margin: 0;'>
            <!-- HEADER UTAMA & PENCARIAN SETTING -->
            <div class='col-xs-12' style='margin-bottom: 18px;'>
                <div class='box box-solid' style='background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; border-radius: 8px; padding: 18px 24px;'>
                    <div class='row' style='display: flex; align-items: center; flex-wrap: wrap;'>
                        <div class='col-md-7 col-xs-12'>
                            <h3 style='margin: 0 0 6px 0; font-size: 20px; font-weight: bold;'>
                                <i class='fa fa-sliders text-info'></i> Pusat Pengaturan Sistem (Master Settings Hub)
                            </h3>
                            <p style='margin: 0; font-size: 13px; color: #94a3b8;'>
                                Kelola seluruh preferensi operasional, hak alur transaksi modul, dan konfigurasi integrasi sistem secara terpusat.
                            </p>
                        </div>
                        <div class='col-md-5 col-xs-12' style='margin-top: 10px;'>
                            <div class='input-group'>
                                <span class='input-group-addon' style='background: #334155; border: 1px solid #475569; color: #cbd5e1;'><i class='fa fa-search'></i></span>
                                <input type='text' id='setting-search' class='form-control' placeholder='Cari pengaturan atau kata kunci...' onkeyup='filterSettings(this.value)' style='background: #1e293b; border: 1px solid #475569; color: #fff;'>
                            </div>
                        </div>
                            </div>
                        </div>
            </div>

            <!-- KOLOM KIRI: NAVIGASI KATEGORI / MODUL -->
            <div class='col-md-3 col-xs-12'>
                <div class='box box-solid' style='border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;'>
                    <div class='box-header with-border' style='background: #f8fafc; padding: 14px 16px;'>
                        <h3 class='box-title' style='font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b;'>
                            <i class='fa fa-folder-open-o'></i> Kategori Pengaturan
                        </h3>
                    </div>
                    <div class='box-body no-padding'>
                        <nav id='category-nav'>
                            <a class='category-nav-item " . ($initialTab === 'project' ? 'active' : '') . "' data-target='tab-project' onclick=\"switchCategory('tab-project', this)\">
                                <i class='fa fa-briefcase' style='width: 20px;'></i> Master Proyek
                                <span class='pull-right label label-primary' style='font-size: 11px;'>3 Saklar</span>
                            </a>
                            <a class='category-nav-item " . ($initialTab === 'messenger' ? 'active' : '') . "' data-target='tab-messenger' onclick=\"switchCategory('tab-messenger', this)\">
                                <i class='fa fa-comments' style='width: 20px;'></i> Sistem &amp; AI Copilot
                                <span class='pull-right label label-info' style='font-size: 11px;'>Multi-AI</span>
                            </a>
                        </nav>
                    </div>
                    <div class='box-footer' style='background: #f8fafc; font-size: 11.5px; color: #94a3b8; padding: 12px 16px;'>
                        <i class='fa fa-info-circle'></i> Modul pengaturan baru akan otomatis dikelompokkan ke dalam kategori di atas.
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: KONTEN KARTU PENGATURAN -->
            <div class='col-md-9 col-xs-12'>
                <!-- ============================================================ -->
                <!-- TAB 1: MASTER PROYEK -->
                <!-- ============================================================ -->
                <div id='tab-project' class='setting-tab-content' style='display: " . ($initialTab === 'project' ? 'block' : 'none') . ";'>
                    <div style='margin-bottom: 16px;'>
                        <h4 style='margin: 0; font-weight: bold; color: #1e293b;'><i class='fa fa-briefcase text-primary'></i> Pengaturan Alur Pelaksana Proyek</h4>
                        <p style='margin: 4px 0 0 0; font-size: 13px; color: #64748b;'>
                            Atur izin penggantian pelaksana pekerjaan SPK antara tim internal perusahaan dan vendor pihak ketiga (eksternal).
                        </p>
                    </div>

                    <!-- KARTU 1: MASTER SAKLAR FITUR SWITCH -->
                    <div class='setting-card searchable-card'>
                        <div class='setting-header-flex'>
                            <div>
                                <div style='display: flex; align-items: center; gap: 8px; margin-bottom: 6px;'>
                                    <h4 style='margin: 0; font-weight: bold; color: #0f172a; font-size: 15px;'>
                                        1. Saklar Utama: Fitur Alih Pelaksana Proyek
                                    </h4>
                                    <span id='badge-switch_pelaksana_enabled' class='badge-status " . ($swMaster ? "badge-active" : "badge-inactive") . "'>
                                        " . ($swMaster ? "<i class='fa fa-check-circle'></i> AKTIF" : "<i class='fa fa-power-off'></i> NONAKTIF") . "
                                    </span>
                                </div>
                                <p style='margin: 0; font-size: 13px; color: #475569; line-height: 1.5;'>
                                    Saklar induk yang mengendalikan ketersediaan tombol <strong>Ganti Pelaksana</strong> pada tabel SPK modul Proyek.
                                </p>
                            </div>
                            <div>
                                <label class='custom-switch'>
                                    <input type='checkbox' id='sw_switch_pelaksana_enabled' " . ($swMaster ? "checked" : "") . " onchange=\"toggleProjectSetting('switch_pelaksana_enabled', this.checked)\">
                                    <span class='custom-slider'></span>
                                </label>
                            </div>
                        </div>
                        <div class='impact-box' style='border-left-color: #0284c7;'>
                            <strong>Dampak Operasional:</strong>
                            <ul style='margin: 4px 0 0 0; padding-left: 18px;'>
                                <li><strong>Saat AKTIF:</strong> Staf yang berwenang dapat mengakses tombol pengalihan pelaksana di kolom tabel SPK.</li>
                                <li><strong>Saat NONAKTIF:</strong> Tombol alih pelaksana akan dinonaktifkan secara global di seluruh modul proyek, menjaga pelaksana pekerjaan tetap terkunci.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- KARTU 2: INTERNAL KE EKSTERNAL -->
                    <div class='setting-card searchable-card' id='card-int-ext' style='" . ($swMaster ? "" : "opacity: 0.6;") . "'>
                        <div class='setting-header-flex'>
                            <div>
                                <div style='display: flex; align-items: center; gap: 8px; margin-bottom: 6px;'>
                                    <h4 style='margin: 0; font-weight: bold; color: #0f172a; font-size: 15px;'>
                                        2. Izin Alih: Internal &rarr; Vendor (Pihak Ketiga)
                                    </h4>
                                    <span id='badge-allow_internal_to_external' class='badge-status " . ($swIntExt ? "badge-active" : "badge-danger") . "'>
                                        " . ($swIntExt ? "<i class='fa fa-check'></i> DIIZINKAN" : "<i class='fa fa-ban'></i> DILARANG") . "
                                    </span>
                                </div>
                                <p style='margin: 0; font-size: 13px; color: #475569; line-height: 1.5;'>
                                    Mengizinkan pekerjaan SPK yang awalnya ditugaskan kepada staf internal dialihkan ke pihak vendor eksternal.
                                </p>
                            </div>
                            <div>
                                <label class='custom-switch'>
                                    <input type='checkbox' id='sw_allow_internal_to_external' " . ($swIntExt ? "checked" : "") . " " . ($swMaster ? "" : "disabled") . " onchange=\"toggleProjectSetting('allow_internal_to_external', this.checked)\">
                                    <span class='custom-slider'></span>
                                </label>
                            </div>
                        </div>
                        <div class='impact-box' style='border-left-color: #10b981;'>
                            <strong>Dampak Operasional:</strong>
                            <ul style='margin: 4px 0 0 0; padding-left: 18px;'>
                                <li><strong>Saat DIIZINKAN:</strong> Pilihan Vendor dapat dipilih pada modal alih pelaksana, dan sistem akan mengizinkan pembuatan tagihan biaya vendor baru (3674).</li>
                                <li><strong>Saat DILARANG:</strong> Pilihan ke Vendor akan dinonaktifkan dengan peringatan bahwa pengalihan ke luar sedang dibatasi.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- KARTU 3: EKSTERNAL KE INTERNAL -->
                    <div class='setting-card searchable-card' id='card-ext-int' style='" . ($swMaster ? "" : "opacity: 0.6;") . "'>
                        <div class='setting-header-flex'>
                            <div>
                                <div style='display: flex; align-items: center; gap: 8px; margin-bottom: 6px;'>
                                    <h4 style='margin: 0; font-weight: bold; color: #0f172a; font-size: 15px;'>
                                        3. Izin Alih: Vendor (Pihak Ketiga) &rarr; Internal Perusahaan
                                    </h4>
                                    <span id='badge-allow_external_to_internal' class='badge-status " . ($swExtInt ? "badge-active" : "badge-danger") . "'>
                                        " . ($swExtInt ? "<i class='fa fa-check'></i> DIIZINKAN" : "<i class='fa fa-ban'></i> DILARANG") . "
                                    </span>
                                </div>
                                <p style='margin: 0; font-size: 13px; color: #475569; line-height: 1.5;'>
                                    Mengizinkan pekerjaan SPK yang awalnya dipegang oleh vendor ditarik kembali untuk dikerjakan oleh tim internal perusahaan.
                            </p>
                            </div>
                            <div>
                                <label class='custom-switch'>
                                    <input type='checkbox' id='sw_allow_external_to_internal' " . ($swExtInt ? "checked" : "") . " " . ($swMaster ? "" : "disabled") . " onchange=\"toggleProjectSetting('allow_external_to_internal', this.checked)\">
                                    <span class='custom-slider'></span>
                                </label>
                            </div>
                        </div>
                        <div class='impact-box' style='border-left-color: #f59e0b;'>
                            <strong>Mekanisme Pembatalan Otomatis (Opsi 2 &amp; Proteksi Kasir):</strong>
                            <ul style='margin: 4px 0 0 0; padding-left: 18px;'>
                                <li>Jika pekerjaan vendor dialihkan ke internal dan memiliki tagihan biaya (3674) yang <strong>belum dibayar</strong>, sistem akan secara otomatis membatalkan tagihan tersebut melalui transaksi pembatalan resmi (9911) serta membalik jurnal hutang/biayanya di akuntansi.</li>
                                <li>Jika tagihan vendor <strong>sudah dibayarkan oleh kasir</strong>, peralihan akan otomatis <strong>DITOLAK</strong> oleh sistem demi menjaga integritas kas perusahaan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- TAB 2: SISTEM & AI COPILOT -->
                <!-- ============================================================ -->
                <div id='tab-messenger' class='setting-tab-content' style='display: " . ($initialTab === 'messenger' ? 'block' : 'none') . ";'>
                    <div style='margin-bottom: 16px;'>
                        <h4 style='margin: 0; font-weight: bold; color: #1e293b;'><i class='fa fa-comments text-info'></i> Enterprise Messenger &amp; Multi-Provider AI</h4>
                        <p style='margin: 4px 0 0 0; font-size: 13px; color: #64748b;'>
                            Konfigurasi widget komunikasi staf dan mesin kecerdasan buatan terpadu ERP.
                        </p>
                    </div>

                    <div class='row'>
                        <div class='col-md-6 col-xs-12'>
                            <!-- SAKLAR MASTER ON/OFF MESSENGER -->
                            <div class='setting-card searchable-card'>
                                <div class='setting-header-flex'>
                                <div>
                                        <div style='display: flex; align-items: center; gap: 8px; margin-bottom: 6px;'>
                                            <h4 style='margin: 0; font-weight: bold; color: #0f172a; font-size: 15px;'>
                                                Saklar Widget Messenger &amp; AI
                                            </h4>
                                            <span id='status-badge-messenger' class='badge-status " . ($isActiveMessenger ? "badge-active" : "badge-inactive") . "'>
                                                " . ($isActiveMessenger ? "<i class='fa fa-check-circle'></i> AKTIF" : "<i class='fa fa-power-off'></i> NONAKTIF") . "
                                            </span>
                                        </div>
                                        <p style='margin: 0; font-size: 13px; color: #475569;'>
                                            Mengendalikan floating button chat di pojok kanan bawah seluruh halaman ERP.
                                        </p>
                                </div>
                                <div>
                                        <label class='custom-switch'>
                                            <input type='checkbox' id='toggle-messenger-switch' " . ($isActiveMessenger ? "checked" : "") . " onchange='toggleMessengerStatus(this.checked)'>
                                            <span class='custom-slider'></span>
                                    </label>
                                </div>
                            </div>
                                <div class='impact-box' style='border-left-color: #00a65a;'>
                                    <strong>Status:</strong>
                                    <span id='switch-desc-messenger' style='display: block; margin-top: 4px;'>
                                        " . ($isActiveMessenger ? "Widget obrolan sedang aktif dan dapat digunakan pengguna." : "Widget obrolan sedang dimatikan secara global.") . "
                                    </span>
                        </div>
                    </div>

                            <!-- SALURAN FITUR -->
                    <div class='box box-info'>
                        <div class='box-header with-border'>
                            <h3 class='box-title'><i class='fa fa-info-circle text-info'></i> Saluran Komunikasi Terpadu</h3>
                        </div>
                        <div class='box-body'>
                            <div class='media' style='margin-bottom: 12px;'>
                                <div class='media-left'>
                                    <span class='btn btn-primary btn-circle' style='width: 38px; height: 38px; border-radius: 50%; font-size: 16px; padding: 6px 0; text-align: center;'><i class='fa fa-android'></i></span>
                                </div>
                                <div class='media-body'>
                                            <h4 class='media-heading text-bold' style='margin-bottom: 2px;'>" . htmlspecialchars($botName, ENT_QUOTES, 'UTF-8') . "</h4>
                                    <p style='font-size: 12px; color: #666;'>Asisten pintar untuk konsultasi alur transaksi, rumus akuntansi, dan panduan penggunaan modul ERP.</p>
                                </div>
                            </div>
                            <div class='media' style='margin-bottom: 12px;'>
                                <div class='media-left'>
                                    <span class='btn btn-warning btn-circle' style='width: 38px; height: 38px; border-radius: 50%; font-size: 16px; padding: 6px 0; text-align: center;'><i class='fa fa-building'></i></span>
                                </div>
                                <div class='media-body'>
                                    <h4 class='media-heading text-bold' style='margin-bottom: 2px;'>Komunikasi Antar Cabang</h4>
                                    <p style='font-size: 12px; color: #666;'>Saluran koordinasi operasional cabang dan kantor pusat untuk mutasi stok, konfirmasi kirim, dan otorisasi.</p>
                                </div>
                            </div>
                            <div class='media'>
                                <div class='media-left'>
                                    <span class='btn btn-success btn-circle' style='width: 38px; height: 38px; border-radius: 50%; font-size: 16px; padding: 6px 0; text-align: center;'><i class='fa fa-user'></i></span>
                                </div>
                                <div class='media-body'>
                                    <h4 class='media-heading text-bold' style='margin-bottom: 2px;'>Direct Message Antar Staf</h4>
                                    <p style='font-size: 12px; color: #666;'>Komunikasi 1-on-1 langsung antar karyawan dan petugas operasional lintas divisi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- PENGATURAN MULTI-PROVIDER AI -->
                <div class='col-md-6 col-xs-12'>
                    <div class='box box-success'>
                        <div class='box-header with-border'>
                            <h3 class='box-title'><i class='fa fa-cubes text-success'></i> Konfigurasi Multi-Provider AI (Copilot Engine)</h3>
                        </div>
                        <div class='box-body' style='padding: 20px;'>
                            <form id='form-ai-config' onsubmit='saveAiConfig(event)'>
                                <div class='form-group'>
                                    <label><i class='fa fa-server'></i> Pilih Penyedia AI (AI Provider)</label>
                                    <select id='ai_provider' class='form-control' onchange='onProviderChange(this.value)'>
                                                <option value='opencode' " . ($activeProvider === 'opencode' ? 'selected' : '') . ">OpenCode Server (IP 192.168.5.10:4096 - Server AI Kantor)</option>
                                        <option value='groq' " . ($activeProvider === 'groq' ? 'selected' : '') . ">Groq Cloud (100% Gratis - Super Kencang)</option>
                                        <option value='gemini' " . ($activeProvider === 'gemini' ? 'selected' : '') . ">Google Gemini (Gratis di Google AI Studio)</option>
                                        <option value='openrouter' " . ($activeProvider === 'openrouter' ? 'selected' : '') . ">OpenRouter (Banyak Model Free :free)</option>
                                        <option value='openai' " . ($activeProvider === 'openai' ? 'selected' : '') . ">OpenAI Official (GPT-4o Mini / Berbayar)</option>
                                        <option value='custom' " . ($activeProvider === 'custom' ? 'selected' : '') . ">Custom / Local Server (Ollama / On-Premise)</option>
                                    </select>
                                    <span id='provider-hint' class='help-block' style='font-size: 11.5px; color: #0284c7;'>
                                        Groq Cloud menyediakan akses LLaMA 3.3 70B gratis dengan kecepatan hingga 500 token/detik.
                                    </span>
                                </div>

                                <div class='form-group'>
                                    <label><i class='fa fa-cube'></i> Nama Model AI</label>
                                    <div class='input-group'>
                                                <input type='text' id='ai_model' class='form-control' value='" . htmlspecialchars($activeModel, ENT_QUOTES, 'UTF-8') . "' placeholder='Contoh: big-pickle'>
                                        <div class='input-group-btn'>
                                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                                                Pilih Model <span class='fa fa-caret-down'></span>
                                            </button>
                                            <ul class='dropdown-menu dropdown-menu-right' id='model-preset-list'>
                                                        <!-- Dimuat dinamis -->
                                            </ul>
                                        </div>
                                    </div>
                                    <span class='help-block' style='font-size: 11.5px; color: #64748b;'>Anda dapat memilih rekomendasi model gratis atau mengetik nama model secara manual.</span>
                                </div>

                                <div class='form-group'>
                                            <label><i class='fa fa-key'></i> Kunci API / Autentikasi (API Key / Auth)</label>
                                    <div class='input-group'>
                                                <input type='password' id='ai_api_key' class='form-control' value='" . htmlspecialchars($activeApiKey, ENT_QUOTES, 'UTF-8') . "' placeholder='opencode:rahasia13'>
                                        <span class='input-group-btn'>
                                            <button class='btn btn-default' type='button' onclick='toggleApiKeyVisibility()'><i id='eye-icon' class='fa fa-eye'></i></button>
                                        </span>
                                    </div>
                                    <span id='api-key-link' class='help-block' style='font-size: 11.5px;'>
                                        Dapatkan kunci API gratis di: <a id='api-provider-url' href='https://console.groq.com/keys' target='_blank'>console.groq.com/keys</a>
                                    </span>
                                </div>

                                        <div class='form-group' id='custom-endpoint-group' style='display: " . ($activeProvider === 'custom' || $activeProvider === 'opencode' ? 'block' : 'none') . ";'>
                                            <label><i class='fa fa-link'></i> URL Endpoint OpenCode / Server AI</label>
                                            <input type='text' id='ai_endpoint' class='form-control' value='" . htmlspecialchars($activeEndpoint, ENT_QUOTES, 'UTF-8') . "' placeholder='http://192.168.5.10:4096'>
                                            <span class='help-block' style='font-size: 11.5px; color: #64748b;'>Gunakan http://192.168.5.10:4096 untuk OpenCode Server atau port server lokal lainnya.</span>
                                </div>

                                <hr style='margin: 20px 0;'>

                                <div style='display: flex; gap: 10px;'>
                                    <button type='button' id='btn-test-ai' class='btn btn-info' onclick='testAiConnection()'>
                                        <i class='fa fa-bolt'></i> Test Koneksi AI
                                    </button>
                                    <button type='submit' id='btn-save-ai' class='btn btn-success'>
                                        <i class='fa fa-save'></i> Simpan Konfigurasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                </div>

            </div>
        </div>

        <script>
        // Navigasi Kategori
        function switchCategory(targetTabId, el) {
            $('.category-nav-item').removeClass('active');
            $(el).addClass('active');

            $('.setting-tab-content').hide();
            $('#' + targetTabId).fadeIn(200);

            // Update URL query tanpa reload
            var tabName = (targetTabId === 'tab-messenger') ? 'messenger' : 'project';
            if (window.history.pushState) {
                var newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '?tab=' + tabName;
                window.history.pushState({path: newUrl}, '', newUrl);
            }
        }

        // Live Search Filter
        function filterSettings(keyword) {
            keyword = keyword.toLowerCase().trim();
            $('.searchable-card').each(function() {
                var text = $(this).text().toLowerCase();
                if (keyword === '' || text.indexOf(keyword) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Toggle Saklar Master Proyek
        function toggleProjectSetting(key, checked) {
            var val = checked ? 1 : 0;
            var payload = { key: key, value: val };

            $.ajax({
                url: '" . base_url() . "Setting/save_project_setting_ajax',
                method: 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        // Update visual badge
                        var badge = $('#badge-' + key);
                        if (key === 'switch_pelaksana_enabled') {
                            if (val === 1) {
                                badge.removeClass('badge-inactive').addClass('badge-active').html(\"<i class='fa fa-check-circle'></i> AKTIF\");
                                $('#sw_allow_internal_to_external, #sw_allow_external_to_internal').prop('disabled', false);
                                $('#card-int-ext, #card-ext-int').css('opacity', '1');
                            } else {
                                badge.removeClass('badge-active').addClass('badge-inactive').html(\"<i class='fa fa-power-off'></i> NONAKTIF\");
                                $('#sw_allow_internal_to_external, #sw_allow_external_to_internal').prop('disabled', true);
                                $('#card-int-ext, #card-ext-int').css('opacity', '0.6');
                            }
                        } else {
                            if (val === 1) {
                                badge.removeClass('badge-danger').addClass('badge-active').html(\"<i class='fa fa-check'></i> DIIZINKAN\");
                            } else {
                                badge.removeClass('badge-active').addClass('badge-danger').html(\"<i class='fa fa-ban'></i> DILARANG\");
                            }
                        }

                        if (typeof swal === 'function') {
                            swal('Tersimpan!', res.message, 'success');
                        }
                    } else {
                        if (typeof swal === 'function') {
                            swal('Gagal!', res.message || 'Gagal menyimpan pengaturan.', 'error');
                        }
                    }
                },
                error: function() {
                    if (typeof swal === 'function') {
                        swal('Galat!', 'Gagal menghubungi server.', 'error');
                    }
                }
            });
        }

        // Presets Multi-Provider AI
        var providerPresets = {
            'opencode': {
                url: 'http://192.168.5.10:4096',
                hint: 'OpenCode Server AI di jaringan lokal (http://192.168.5.10:4096). Inferensi cepat tanpa biaya API Cloud.',
                models: [
                    { name: 'big-pickle', label: 'Big Pickle (Default OpenCode Model)' },
                    { name: 'nemotron-3-ultra-free', label: 'Nemotron 3 Ultra Free' },
                    { name: 'nemotron-3.5-lightning-free', label: 'Nemotron 3.5 Lightning Free' },
                    { name: 'muse-spark-1.3-contributor-free', label: 'Muse Spark 1.3 Free' },
                    { name: 'mimo-v2.5-free', label: 'MiMo V2.5 Free' },
                    { name: 'ling-3.0-flash-fin-free', label: 'Ling 3.0 Flash Fin Free' },
                    { name: 'ollama/nemotron-mini:latest', label: 'Ollama Nemotron Mini (Lokal)' }
                ]
            },
            'groq': {
                url: 'https://console.groq.com/keys',
                hint: 'Groq Cloud menyediakan inferensi gratis super cepat untuk model Groq Compound, GPT-OSS, Qwen, dll.',
                models: [
                    { name: 'groq/compound', label: 'Groq Compound (Sistem Compound AI Terpadu)' },
                    { name: 'groq/compound-mini', label: 'Groq Compound Mini (Ringan & Cepat)' },
                    { name: 'openai/gpt-oss-120b', label: 'OpenAI GPT-OSS 120B (High Capability)' },
                    { name: 'openai/gpt-oss-20b', label: 'OpenAI GPT-OSS 20B (Cepat & Seimbang)' },
                    { name: 'qwen/qwen3.8-27b', label: 'Qwen 3.8 27B' },
                    { name: 'qwen/qwen3.6-27b', label: 'Qwen 3.6 27B' },
                    { name: 'llama-3.3-70b-versatile', label: 'LLaMA 3.3 70B (Sangat Cerdas & Cepat)' },
                    { name: 'llama-3.1-8b-instant', label: 'LLaMA 3.1 8B Instant (Model Ringan)' }
                ]
            },
            'gemini': {
                url: 'https://aistudio.google.com/app/apikey',
                hint: 'Google Gemini menyediakan Free Tier gratis 15 RPM tanpa kartu kredit dengan pemahaman akuntansi luar biasa.',
                models: [
                    { name: 'gemini-1.5-flash', label: 'Gemini 1.5 Flash (Rekomendasi - Cepat & 1M Token Context)' },
                    { name: 'gemini-2.0-flash', label: 'Gemini 2.0 Flash (Model Generasi Baru)' },
                    { name: 'gemini-1.5-pro', label: 'Gemini 1.5 Pro (Penalaran Kompleks)' }
                ]
            },
            'openrouter': {
                url: 'https://openrouter.ai/keys',
                hint: 'OpenRouter menghubungkan puluhan lab AI dengan model bertanda :free yang dapat dipakai tanpa biaya.',
                models: [
                    { name: 'meta-llama/llama-3.3-70b-instruct:free', label: 'LLaMA 3.3 70B Free (:free)' },
                    { name: 'deepseek/deepseek-chat:free', label: 'DeepSeek Chat Free (:free)' },
                    { name: 'qwen/qwen-2.5-72b-instruct:free', label: 'Qwen 2.5 72B Free (:free)' }
                ]
            },
            'openai': {
                url: 'https://platform.openai.com/api-keys',
                hint: 'OpenAI memerlukan saldo akun berbayar (Pay-As-You-Go).',
                models: [
                    { name: 'gpt-4o-mini', label: 'GPT-4o Mini (Hemat Kuota & Cerdas)' },
                    { name: 'gpt-4o', label: 'GPT-4o (Model Flagship)' }
                ]
            },
            'custom': {
                url: 'https://ollama.com/',
                hint: 'Dapat diarahkan ke Ollama lokal di server kantor (cth: http://localhost:11434/v1/chat/completions).',
                models: [
                    { name: 'llama3.1', label: 'Ollama LLaMA 3.1' },
                    { name: 'qwen2.5', label: 'Ollama Qwen 2.5' },
                    { name: 'mistral', label: 'Ollama Mistral' }
                ]
            }
        };

        function onProviderChange(prov) {
            var data = providerPresets[prov] || providerPresets['opencode'];
            $('#provider-hint').text(data.hint);
            $('#api-provider-url').attr('href', data.url).text(data.url.replace('https://', '').replace('http://', ''));

            if (prov === 'custom' || prov === 'opencode') {
                $('#custom-endpoint-group').slideDown(200);
                if (prov === 'opencode') {
                    if (!$('#ai_endpoint').val() || $('#ai_endpoint').val().indexOf('11434') !== -1) {
                        $('#ai_endpoint').val('http://192.168.5.10:4096');
                    }
                    $('#ai_endpoint').attr('placeholder', 'http://192.168.5.10:4096');
                } else {
                    $('#ai_endpoint').attr('placeholder', 'http://localhost:11434/v1/chat/completions');
                }
            } else {
                $('#custom-endpoint-group').slideUp(200);
            }

            var listHtml = '';
            for (var i = 0; i < data.models.length; i++) {
                var m = data.models[i];
                listHtml += '<li><a href=\"javascript:void(0)\" onclick=\"selectPresetModel(\'' + m.name + '\')\"><strong>' + m.name + '</strong><br><small class=\"text-muted\">' + m.label + '</small></a></li>';
            }
            $('#model-preset-list').html(listHtml);

            if (data.models.length > 0) {
                $('#ai_model').val(data.models[0].name);
            }
        }

        function selectPresetModel(modelName) {
            $('#ai_model').val(modelName);
        }

        function toggleApiKeyVisibility() {
            var input = document.getElementById('ai_api_key');
            var icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }

        function toggleMessengerStatus(checked) {
            var statusVal = checked ? 1 : 0;
            var badge = $('#status-badge-messenger');

            $.ajax({
                url: '" . base_url() . "Setting/toggle_messenger_ajax',
                method: 'POST',
                data: { status: statusVal },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        if (statusVal === 1) {
                            badge.removeClass('badge-inactive').addClass('badge-active').html(\"<i class='fa fa-check-circle'></i> AKTIF\");
                            $('#switch-desc-messenger').text('Widget obrolan sedang aktif dan dapat digunakan pengguna.');
                            swal('Berhasil!', 'Fitur Messenger & AI Copilot telah DIAKTIFKAN.', 'success');
                        } else {
                            badge.removeClass('badge-active').addClass('badge-inactive').html(\"<i class='fa fa-power-off'></i> NONAKTIF\");
                            $('#switch-desc-messenger').text('Widget obrolan sedang dimatikan secara global.');
                            swal('Dinonaktifkan!', 'Fitur Messenger & AI Copilot telah DINONAKTIFKAN.', 'info');
                        }
                    } else {
                        swal('Gagal', res.message || 'Gagal mengubah status.', 'error');
                    }
                },
                error: function() {
                    swal('Galat', 'Gagal menghubungi server.', 'error');
                }
            });
        }

        function saveAiConfig(e) {
            e.preventDefault();
            var btn = $('#btn-save-ai');
            var originalText = btn.html();
            btn.prop('disabled', true).html(\"<i class='fa fa-spinner fa-spin'></i> Menyimpan...\");

            var payload = {
                provider: $('#ai_provider').val(),
                model: $('#ai_model').val(),
                api_key: $('#ai_api_key').val(),
                custom_endpoint: $('#ai_endpoint').val()
            };

            $.ajax({
                url: '" . base_url() . "Setting/save_ai_config_ajax',
                method: 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html(originalText);
                    if (res.status === 'success') {
                        swal('Tersimpan!', res.message || 'Konfigurasi AI berhasil disimpan.', 'success');
                    } else {
                        swal('Gagal', res.message || 'Gagal menyimpan.', 'error');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html(originalText);
                    swal('Galat', 'Gagal menghubungi server.', 'error');
                }
            });
        }

        function testAiConnection() {
            var btn = $('#btn-test-ai');
            var originalText = btn.html();
            btn.prop('disabled', true).html(\"<i class='fa fa-spinner fa-spin'></i> Menguji Koneksi...\");

            var payload = {
                provider: $('#ai_provider').val(),
                model: $('#ai_model').val(),
                api_key: $('#ai_api_key').val(),
                custom_endpoint: $('#ai_endpoint').val()
            };

            $.ajax({
                url: '" . base_url() . "Chat/test_ai_connection',
                method: 'POST',
                data: payload,
                dataType: 'json',
                success: function(res) {
                    btn.prop('disabled', false).html(originalText);
                    if (res.status === 'success') {
                        swal({
                            title: 'Koneksi AI Berhasil!',
                            text: 'Provider: ' + (res.provider || '').toUpperCase() + '\\nModel: ' + (res.model || '') + '\\n\\nRespon AI:\\n\"' + res.reply + '\"',
                            type: 'success'
                        });
                    } else {
                        swal({
                            title: 'Uji Koneksi Gagal',
                            text: res.reply || res.message || 'Tidak ada balasan dari API.',
                            type: 'error'
                        });
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html(originalText);
                    swal('Galat', 'Gagal memanggil endpoint uji coba.', 'error');
                }
            });
        }

        $(document).ready(function(){
            var curProv = $('#ai_provider').val();
            onProviderChange(curProv);
            $('#ai_model').val('" . htmlspecialchars($activeModel, ENT_QUOTES, 'UTF-8') . "');
        });
        </script>
        ";

        $layout = new Layout("Pengaturan", "Pusat Pengaturan Sistem (Master Settings)", "application/template/default.html");
        $layout->addTags(array(
            "menu_left"        => function_exists('callMenuLeft') ? callMenuLeft() : '',
            "menu_taskbar"     => function_exists('callMenuTaskbar') ? callMenuTaskbar() : '',
            "float_menu_bawah" => function_exists('callFloatMenu') ? callFloatMenu('bawah') : '',
            "float_menu_atas"  => function_exists('callFloatMenu') ? callFloatMenu('atas') : '',
            "btn_back"         => function_exists('callBackNav') ? callBackNav() : '',
            "content"          => $html,
            "head"             => "",
            "isi_modal"        => "",
            "lebar_modal"      => "",
            "bg_modal"         => ""
        ));
        $layout->setContent($html);
        $layout->render();
    }

    public function messenger()
    {
        $this->index('messenger');
    }

    public function save_project_setting_ajax()
    {
        $key = trim(strip_tags($this->input->post('key')));
        $val = (int)$this->input->post('value');

        $allowedKeys = array(
            'switch_pelaksana_enabled',
            'allow_internal_to_external',
            'allow_external_to_internal'
        );

        if (!in_array($key, $allowedKeys)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Kunci pengaturan tidak valid'
            ));
            return;
        }

        $st = new MdlSetting();
        $newVal = $st->set_project_setting($key, $val);

        $labels = array(
            'switch_pelaksana_enabled' => 'Saklar Utama Alih Pelaksana Proyek',
            'allow_internal_to_external' => 'Izin Alih Internal ke Vendor',
            'allow_external_to_internal' => 'Izin Alih Vendor ke Internal'
        );

        $label = isset($labels[$key]) ? $labels[$key] : $key;
        $statusText = ($newVal === 1) ? 'diaktifkan / diizinkan' : 'dinonaktifkan / dilarang';

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'key' => $key,
            'value' => $newVal,
            'message' => "Pengaturan {$label} berhasil {$statusText}."
        ));
    }

    public function toggle_messenger_ajax()
    {
        $status = (int)$this->input->post('status');
        $st = new MdlSetting();
        $newVal = $st->set_messenger_ai_status($status);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'is_active' => ($newVal === 1),
            'message' => ($newVal === 1) ? 'Fitur Messenger & AI berhasil diaktifkan' : 'Fitur Messenger & AI berhasil dinonaktifkan'
        ));
    }

    public function save_ai_config_ajax()
    {
        $provider = trim(strip_tags($this->input->post('provider')));
        $model = trim(strip_tags($this->input->post('model')));
        $api_key = trim($this->input->post('api_key'));
        $custom_endpoint = trim(strip_tags($this->input->post('custom_endpoint')));

        if (empty($provider)) {
            $provider = 'groq';
        }

        $st = new MdlSetting();
        $st->set_ai_setting('ai_provider', $provider);
        $st->set_ai_setting('ai_model', $model);
        $st->set_ai_setting('ai_api_key', $api_key);
        $st->set_ai_setting('ai_endpoint', $custom_endpoint);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'message' => 'Konfigurasi penyedia AI berhasil diperbarui!'
        ));
    }
}

// END OF COMPLETE REPEATED LOGIC
