<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Inisiasi amandemen, menerapkan Early Validation Pattern
 * COMPLIANCE: ISO 9001 (Audit Trail), Early Validation DJP
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "modules/amandemen_invoice/controllers/Modul_Controller.php";

class Create extends Modul_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('he_angka');
        $this->load->model('Coms/ComAmandemenInvoice');
    }

    public function index($invoice_id = 0) {
        $invoice_id = (int) $invoice_id;
        
        if ($invoice_id <= 0) {
            show_error('Invalid Invoice ID');
        }
        
        if (isset($_GET['debug870225'])) {
            $this->db->where('transaksi_id', $invoice_id);
            $this->db->order_by('dtime', 'DESC');
            $this->db->limit(1);
            $reg = $this->db->get('transaksi_data_registry')->row_array();
            echo "<pre>";
            echo "ITEMS5_SUM:\n";
            print_r(@unserialize(base64_decode($reg['items5_sum'])));
            echo "\nITEMS:\n";
            print_r(@unserialize(base64_decode($reg['items'])));
            echo "</pre>";
            exit;
        }

        if (isset($_GET['debugHistory'])) {
            echo "<pre>";
            echo "--- AMANDEMEN HISTORY ---\n";
            $this->db->where('transaksi_id', $invoice_id);
            $this->db->order_by('dtime', 'DESC');
            print_r($this->db->get('transaksi_amandemen_history')->result_array());

            echo "\n--- RETURN HISTORY ---\n";
            $this->db->where('nomer_referensi', $invoice_no);
            print_r($this->db->get('transaksi')->result_array());

            echo "\n--- JURNAL HISTORY ---\n";
            $this->db->where('transaksi_id', $invoice_id);
            print_r($this->db->get('jurnal')->result_array());
            echo "</pre>";
            exit;
        }

        $invoice_id = (int)$invoice_id;
        $this->db->select('id, nomer, keterangan, reference_id, reference_nomer');
        $this->db->where('id', $invoice_id);
        $trx = $this->db->get('transaksi')->row_array();
        $invoice_no = ($trx && !empty($trx['nomer'])) ? $trx['nomer'] : (string)$invoice_id;

        // Cek riwayat amandemen sebelumnya & flag redirect
        $this->db->where('transaksi_id', $invoice_id);
        $count_amandemen = (int)$this->db->count_all_results('transaksi_amandemen_history');
        $has_previous_amendment = ($count_amandemen > 0);
        $is_saved_redirect = (isset($_GET['saved']) && $_GET['saved'] == '1');
        $is_restored_redirect = (isset($_GET['restored']) && $_GET['restored'] == '1');

        // Early Validation Pattern: Cek status pajak
        $tax_info = $this->ComAmandemenInvoice->checkTaxStatus($invoice_id);
        $absorb_tax_request = (isset($_GET['absorb_tax']) && (int)$_GET['absorb_tax'] === 1);

        if ($tax_info['status'] === 'APPROVED_DJP') {
            if ($absorb_tax_request || $has_previous_amendment || $is_saved_redirect || $is_restored_redirect) {
                // User memilih untuk menyerap selisih PPN, ATAU sudah pernah melakukan amandemen sebelumnya (kesepakatan penyerapan pajak sudah sah)
                $data['is_tax_approved_djp'] = true;
                $data['absorb_tax'] = 1;
                $data['tax_info'] = $tax_info;
            } else {
                $efaktur_no = htmlspecialchars(isset($tax_info['efaktur']) ? $tax_info['efaktur'] : '-');
                $efaktur_date = htmlspecialchars(isset($tax_info['efaktur_dtime']) ? $tax_info['efaktur_dtime'] : '-');
                $dpp_fmt = number_format((float)(isset($tax_info['dpp']) ? $tax_info['dpp'] : 0), 0, ',', '.');
                $ppn_fmt = number_format((float)(isset($tax_info['ppn']) ? $tax_info['ppn'] : 0), 0, ',', '.');
                $gt_fmt = number_format((float)(isset($tax_info['nilai']) ? $tax_info['nilai'] : 0), 0, ',', '.');
                $base_url = base_url();

                $user_id = function_exists('my_id') ? my_id() : (isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : 0);
                $user_name = isset($_SESSION['login']['nama']) ? $_SESSION['login']['nama'] : 'User';
                $is_superadmin = ($user_id == 1 || (isset($_SESSION['login']['level']) && strtolower($_SESSION['login']['level']) === 'superadmin') || (isset($_SESSION['login']['is_admin']) && $_SESSION['login']['is_admin'] == 1));

                // Periksa hak akses user ke modul taxes dan pembatalan
                $has_taxes_access = $is_superadmin;
                $has_pembatalan_access = $is_superadmin;

                if (!$is_superadmin && function_exists('alowedAccess')) {
                    $user_access = alowedAccess($user_id);
                    if (isset($user_access['taxes']) || isset($user_access['110'])) {
                        $has_taxes_access = true;
                    }
                    if (isset($user_access['pembatalan'])) {
                        $has_pembatalan_access = true;
                    }
                }

                $ticket_text = "[PERMINTAAN FAKTUR PENGGANTI / NOTA RETUR]\n"
                             . "Halo Tim Pajak & Accounting,\n"
                             . "Mohon bantuan untuk memproses Faktur Pajak Pengganti (Kode 011) / Nota Retur pada transaksi berikut:\n"
                             . "• No. Invoice: " . $invoice_no . "\n"
                             . "• No. e-Faktur DJP: " . $efaktur_no . " (Dokumen ERP: " . (isset($tax_info['tax_nomer']) ? $tax_info['tax_nomer'] : '-') . ")\n"
                             . "• Tanggal Approval DJP: " . $efaktur_date . "\n"
                             . "• Nilai Tagihan Baru: Rp " . $gt_fmt . " (DPP: Rp " . $dpp_fmt . ", PPN: Rp " . $ppn_fmt . ")\n"
                             . "• Keperluan: Penyesuaian diskon / adjustment nilai invoice proyek.\n\n"
                             . "Setelah status Faktur Pengganti atau Pembatalan selesai diproses di Modul Taxes, akses diskon / adjustment invoice ini akan otomatis terbuka (unlocked). Terima kasih.\n"
                             . "Pemohon: " . $user_name . " (" . date('d/m/Y H:i') . ")";

                $ticket_text_json = json_encode($ticket_text);

                $html = '
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Akses Ditolak - e-Faktur Approved DJP</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                    <style>
                        .btn-action-tax { transition: all 0.2s; }
                        .btn-action-tax:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(0,0,0,0.12); }
                    </style>
                </head>
                <body style="background-color:#f4f6f9; font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;">
                    <div style="max-width:880px; margin: 35px auto; background:#ffffff; border:1px solid #dc3545; border-radius:8px; box-shadow:0 4px 18px rgba(0,0,0,0.08); overflow:hidden;">
                        <div style="background:#dc3545; color:#ffffff; padding:18px 25px; border-bottom:1px solid #bd2130;">
                            <h3 style="margin:0; font-size:1.3em; font-weight:bold;">🛑 AKSES DITOLAK: INVOICE #' . $invoice_no . ' SUDAH DI-FAKTURKAN (APPROVED DJP)</h3>
                            <p style="margin:5px 0 0 0; font-size:0.9em; opacity:0.9;">Protokol Kepatuhan Pajak DJP (PER-03/PJ/2022) & Anti-Fraud Control</p>
                        </div>
                        
                        <div style="padding:25px;">
                            <div style="background:#fff5f5; border:1px solid #feb2b2; padding:14px 18px; border-radius:6px; margin-bottom:20px;">
                                <p style="margin:0; color:#c53030; font-weight:bold; font-size:0.95em;">
                                    Invoice <b>#' . $invoice_no . '</b> ini <u>tidak dapat disesuaikan secara langsung</u> karena telah terbit Faktur Pajak Resmi yang telah disetujui (<i>Approved</i>) oleh DJP.
                                </p>
                            </div>

                            <!-- METADATA E-FAKTUR -->
                            <table style="width:100%; border-collapse:collapse; margin-bottom:20px; font-size:0.9em; background:#f8f9fa; border:1px solid #e2e8f0; border-radius:6px;">
                                <tr>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; width:30%; font-weight:bold; color:#4a5568;">Nomor Invoice:</td>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; font-family:monospace; font-weight:bold; color:#2d3748;">' . $invoice_no . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; font-weight:bold; color:#4a5568;">Nomor e-Faktur:</td>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; font-family:monospace; font-weight:bold; font-size:1.1em; color:#2b6cb0;">' . $efaktur_no . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; font-weight:bold; color:#4a5568;">Dokumen Pajak ERP:</td>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; color:#2d3748;">
                                        <b>' . (isset($tax_info['tax_nomer']) ? htmlspecialchars($tax_info['tax_nomer']) : '-') . '</b>
                                        ' . (!empty($tax_info['tax_nomer']) ? '<a href="javascript:void(0)" onclick="var u=\'' . $base_url . 'taxes/Printing/viewReceiptReg/110/' . htmlspecialchars($tax_info['tax_nomer']) . '\'; if(window.top && window.top.popBig){ top.popBig(u); } else { window.open(u, \'_blank\'); }" style="margin-left:8px; font-size:0.85em; color:#3182ce; text-decoration:underline; font-weight:bold; cursor:pointer;">📄 Lihat Lembar Faktur Pajak</a>' : '') . '
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; font-weight:bold; color:#4a5568;">Tanggal Approval DJP:</td>
                                    <td style="padding:10px 15px; border-bottom:1px solid #e2e8f0; color:#2d3748;">' . $efaktur_date . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 15px; font-weight:bold; color:#4a5568;">Nilai DPP & PPN Aktif:</td>
                                    <td style="padding:10px 15px; color:#2d3748;">DPP: Rp ' . $dpp_fmt . ' | PPN (11%): Rp ' . $ppn_fmt . ' | Total: <b>Rp ' . $gt_fmt . '</b></td>
                                </tr>
                            </table>

                            <h4 style="color:#2b6cb0; margin-bottom:12px; border-bottom:2px solid #ebf8ff; padding-bottom:6px; font-size:1.1em; font-weight:bold;">📋 Panduan & Prosedur Penyelesaian</h4>
                            
                            <div style="font-size:0.9em; color:#2d3748; line-height:1.6;">
                                <p style="margin-bottom:12px;">Untuk memproses perubahan invoice ini, pilih salah satu dari opsi prosedur berikut:</p>
                                
                                <div style="background:#edf2f7; border-left:4px solid #3182ce; padding:12px 15px; margin-bottom:12px; border-radius:0 4px 4px 0;">
                                    <strong style="color:#2b6cb0;">🔹 OPSI 1: Menerbitkan Nota Retur Pajak / Faktur Pengganti (Kode 011)</strong><br>
                                    1. Bagian Pajak membuka transaksi pajak terkait di modul <b>Taxes (110)</b>.<br>
                                    2. Input Dokumen <b>Nota Retur Pajak</b> atau terbitkan <b>Faktur Pajak Pengganti (Kode 011)</b> untuk e-Faktur nomor <code>' . $efaktur_no . '</code>.<br>
                                    3. Setelah status e-Faktur seri lama berubah menjadi non-aktif/pengganti, akses diskon / adjustment invoice ini akan otomatis <b>TERBUKA (UNLOCKED)</b>.
                                </div>

                                <div style="background:#edf2f7; border-left:4px solid #dd6b20; padding:12px 15px; margin-bottom:12px; border-radius:0 4px 4px 0;">
                                    <strong style="color:#c05621;">🔹 OPSI 2: Pembatalan Transaksi / e-Faktur di DJP</strong><br>
                                    1. Jika e-Faktur belum dilaporkan dalam SPT Masa, lakukan pembatalan via modul <b>Pembatalan</b> atau pembatalan e-Faktur di DJP.<br>
                                    2. Setelah status e-Faktur berubah menjadi <i>CANCELED</i>, akses diskon / adjustment invoice ini akan otomatis <b>TERBUKA (UNLOCKED)</b>.
                                </div>

                                <div style="background:#fffaf0; border:1.5px solid #dd6b20; border-left:5px solid #c05621; padding:14px 18px; margin-bottom:15px; border-radius:0 6px 6px 0;">
                                    <strong style="color:#9c4221; font-size:1.05em;">💡 OPSI 3: Buka Akses Langsung (Perusahaan Menyerap Selisih PPN Internal)</strong><br>
                                    <span style="color:#7b341e;">
                                        Jika perusahaan memutuskan tidak ingin repot membatalkan e-Faktur di DJP karena risiko operasional, perusahaan dapat <b>menyerap selisih PPN</b> ke akun <b>Biaya Usaha (Beban Penjualan Lainnya)</b>. Nilai e-Faktur resmi di DJP tetap sah dan tidak diubah.<br>
                                        <small style="font-weight:bold; color:#c53030;">* Syarat Fiskal: Hanya sah jika nilai tagihan baru &le; nilai tagihan awal. Jika tagihan bertambah, e-Faktur Pengganti (011) di DJP tetap wajib diterbitkan.</small>
                                    </span>
                                    <div style="margin-top:10px;">
                                        <a href="' . $base_url . 'amandemen_invoice/Create/index/' . $invoice_id . '?absorb_tax=1" class="btn btn-danger btn-action-tax" style="background:#c53030; border-color:#9b2c2c; font-weight:bold; font-size:0.9em;">
                                            ⚠️ Buka Akses &amp; Tanggung Selisih PPN (Tanpa Pembatalan DJP) &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 1-CLICK DISPOSISI UNTUK TIM PAJAK -->
                            <div style="background:#f0fff4; border:1px solid #c6f6d5; border-radius:6px; padding:15px; margin-top:20px; margin-bottom:20px;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <strong style="color:#22543d; font-size:1em;">📢 Bukan Petugas Pajak? Disposisikan ke Tim Pajak / Accounting:</strong>
                                        <p style="margin:4px 0 0 0; font-size:0.85em; color:#276749;">
                                            Klik tombol di samping untuk menyalin ringkasan data permintaan faktur pengganti dan kirimkan ke WhatsApp / Email tim Pajak.
                                        </p>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-success btn-sm btn-action-tax" onclick="copyTicketText()" style="font-weight:bold; white-space:nowrap; padding:8px 14px;">
                                            📋 Salin Disposisi Tim Pajak
                                        </button>
                                    </div>
                                </div>
                                <div id="copySuccessMsg" style="display:none; margin-top:10px; padding:8px 12px; background:#c6f6d5; color:#22543d; border-radius:4px; font-size:0.85em; font-weight:bold;">
                                    ✅ Rincian permintaan berhasil disalin ke Clipboard! Silakan paste ke WhatsApp / Email Tim Pajak.
                                </div>
                            </div>

                            <!-- ACTION BUTTONS DENGAN CEK RBAC -->
                            <div style="margin-top:25px; padding-top:15px; border-top:1px solid #e2e8f0; text-align:right;">';

                if ($has_taxes_access) {
                    $html .= '
                        <a href="' . $base_url . 'taxes/History/viewHistory/110/110?date1=' . (!empty($tax_info['efaktur_dtime']) ? substr($tax_info['efaktur_dtime'], 0, 4) : date('Y')) . '-01-01&date2=' . (!empty($tax_info['efaktur_dtime']) ? substr($tax_info['efaktur_dtime'], 0, 4) : date('Y')) . '-12-31&search=' . urlencode(isset($tax_info['tax_nomer']) ? $tax_info['tax_nomer'] : '') . '" target="_blank" class="btn btn-primary btn-action-tax" style="font-weight:bold; font-size:0.9em; margin-right:8px;">📄 Buka Riwayat Transaksi #' . (isset($tax_info['tax_nomer']) ? htmlspecialchars($tax_info['tax_nomer']) : '110') . '</a>';
                } else {
                    $html .= '
                        <button class="btn btn-secondary" disabled title="Anda tidak memiliki hak akses ke modul Taxes" style="font-size:0.9em; margin-right:8px; opacity:0.6; cursor:not-allowed;">🔒 Modul Taxes (Izin Terbatas)</button>';
                }

                if ($has_pembatalan_access) {
                    $html .= '
                        <a href="' . $base_url . 'pembatalan/Transaksi/index?gr=cGFqYWs&topGr=110&md=transaksi" target="_blank" class="btn btn-warning btn-action-tax" style="color:#ffffff; background:#dd6b20; border-color:#c05621; font-weight:bold; font-size:0.9em; margin-right:8px;">🚫 Buka Modul Pembatalan</a>';
                } else {
                    $html .= '
                        <button class="btn btn-secondary" disabled title="Anda tidak memiliki hak akses ke modul Pembatalan" style="font-size:0.9em; margin-right:8px; opacity:0.6; cursor:not-allowed;">🔒 Modul Pembatalan (Izin Terbatas)</button>';
                }

                $html .= '
                                <a href="javascript:history.back()" class="btn btn-light" style="background:#e2e8f0; color:#4a5568; font-weight:bold; font-size:0.9em;">⬅️ Kembali</a>
                            </div>
                        </div>
                    </div>

                    <script>
                        function copyTicketText() {
                            var text = ' . $ticket_text_json . ';
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(text).then(function() {
                                    showCopyFeedback();
                                });
                            } else {
                                var textArea = document.createElement("textarea");
                                textArea.value = text;
                                document.body.appendChild(textArea);
                                textArea.select();
                                document.execCommand("copy");
                                document.body.removeChild(textArea);
                                showCopyFeedback();
                            }
                        }

                        function showCopyFeedback() {
                            var msg = document.getElementById("copySuccessMsg");
                            if (msg) {
                                msg.style.display = "block";
                                setTimeout(function() {
                                    msg.style.display = "none";
                                }, 4000);
                            }
                        }
                    </script>
                </body>
                </html>';
                echo $html;
                exit;
            }
        } else {
            $data['is_tax_approved_djp'] = false;
            $data['absorb_tax'] = 0;
            $data['tax_info'] = $tax_info;
        }

        // Lapis 1 Blocker: Cek apakah tagihan sudah dibayar (dicicil/lunas)
        $this->db->select('transaksi_dibayar');
        $this->db->where('id', $invoice_id);
        $cek_bayar = $this->db->get('transaksi')->row_array();
        if ($cek_bayar && (float)$cek_bayar['transaksi_dibayar'] > 0) {
            $msg = '<b>AKSES DITOLAK: INVOICE SUDAH DIPROSES PENERIMAAN</b><br><br>';
            $msg .= 'Sesuai dengan protokol Akuntansi (Anti-Fraud), invoice tidak dapat disesuaikan karena sistem mendeteksi sudah ada uang masuk (A/R Receipt) sebesar Rp ' . number_format($cek_bayar['transaksi_dibayar'], 0, ',', '.') . '.<br><br>';
            $msg .= 'Harap <b>Void / Batalkan</b> transaksi Penerimaan tersebut terlebih dahulu di modul A/R Receipt, barulah Invoice ini bisa disesuaikan.';
            show_error($msg);
        }

// START OF COMPLETE REPEATED LOGIC
        // [COMPLIANCE]: ISO 9001 Fraud Prevention - Batas Penyesuaian (Gembok 3)
        // Maksimal penyesuaian adalah 2 kali untuk mencegah perombakan tak terkendali.
        $this->db->where('transaksi_id', $invoice_id);
        $count_amandemen = $this->db->count_all_results('transaksi_amandemen_history');
        
        if ($count_amandemen >= 2) {
            $msg = '<b>AKSES DITOLAK: BATAS MAKSIMAL DISKON / ADJUSTMENT TERCAPAI</b><br><br>';
            $msg .= 'Invoice ini sudah pernah disesuaikan sebanyak ' . $count_amandemen . ' kali.<br>';
            $msg .= 'Sesuai dengan protokol Anti-Fraud ISO 9001, satu invoice hanya boleh disesuaikan maksimal 2 kali.<br><br>';
            $msg .= 'Silakan hubungi pihak Direksi/Owner jika Anda benar-benar memerlukan perubahan lebih lanjut.';
            show_error($msg, 403, 'Akses Diblokir (Proteksi Repetisi)');
        }

        // Ambil data registry terakhir
        $this->db->where('transaksi_id', $invoice_id);
        $this->db->order_by('dtime', 'DESC');
        $this->db->limit(1);
        $registry = $this->db->get('transaksi_data_registry')->row_array();
// END OF COMPLETE REPEATED LOGIC

        $invoice_no = "-";
        $current_description = "";
        $this->db->select('nomer, keterangan');
        $this->db->where('id', $invoice_id);
        $trx = $this->db->get('transaksi')->row_array();
        if ($trx) {
            $invoice_no = $trx['nomer'];
            $current_description = $trx['keterangan'];
        }

        $items5_sum = array();
        $project_id = 0;
        
// START OF COMPLETE REPEATED LOGIC
        if ($registry) {
            $raw_items5_sum = @unserialize(base64_decode($registry['items5_sum']));
            
            // Flatten the nested structure for UI rendering
            if (is_array($raw_items5_sum)) {
                foreach ($raw_items5_sum as $td_data) {
                    $has_nested = false;
                    if (isset($td_data['bahan_baku']['produk']) && is_array($td_data['bahan_baku']['produk'])) {
                        $has_nested = true;
                        foreach ($td_data['bahan_baku']['produk'] as $pd_id => $prd) {
                            if ((float)$prd['jml'] > 0) {
                                $items5_sum[] = array(
                                    'id' => isset($prd['id']) ? $prd['id'] : 0,
                                    'produk_dasar_id' => isset($prd['produk_dasar_id']) ? $prd['produk_dasar_id'] : $pd_id,
                                    'nama' => isset($prd['produk_dasar_nama']) ? $prd['produk_dasar_nama'] : (isset($prd['nama']) ? $prd['nama'] : ''),
                                    'satuan' => isset($prd['satuan']) ? $prd['satuan'] : '',
                                    'jml' => $prd['jml'],
                                    'harga' => $prd['harga']
                                );
                            }
                        }
                    }
                    if (isset($td_data['bahan_baku']['biaya']) && is_array($td_data['bahan_baku']['biaya'])) {
                        $has_nested = true;
                        foreach ($td_data['bahan_baku']['biaya'] as $pd_id => $biy) {
                            if ((float)$biy['jml'] > 0) {
                                $items5_sum[] = array(
                                    'id' => isset($biy['id']) ? $biy['id'] : 0,
                                    'produk_dasar_id' => isset($biy['produk_dasar_id']) ? $biy['produk_dasar_id'] : $pd_id,
                                    'nama' => isset($biy['produk_dasar_nama']) ? $biy['produk_dasar_nama'] : (isset($biy['nama']) ? $biy['nama'] : ''),
                                    'satuan' => isset($biy['satuan']) ? $biy['satuan'] : '',
                                    'jml' => $biy['jml'],
                                    'harga' => $biy['harga']
                                );
                            }
                        }
                    }
                    
                    if (!$has_nested && is_array($td_data)) {
                        if (isset($td_data['produk_dasar_id']) || isset($td_data['id'])) {
                            if ((float)(isset($td_data['jml']) ? $td_data['jml'] : 0) > 0) {
                                $items5_sum[] = array(
                                    'id' => isset($td_data['id']) ? $td_data['id'] : 0,
                                    'produk_dasar_id' => isset($td_data['produk_dasar_id']) ? $td_data['produk_dasar_id'] : (isset($td_data['id']) ? $td_data['id'] : 0),
                                    'nama' => isset($td_data['nama']) ? $td_data['nama'] : (isset($td_data['produk_dasar_nama']) ? $td_data['produk_dasar_nama'] : ''),
                                    'satuan' => isset($td_data['satuan']) ? $td_data['satuan'] : '',
                                    'jml' => isset($td_data['jml']) ? $td_data['jml'] : 0,
                                    'harga' => isset($td_data['harga']) ? $td_data['harga'] : 0
                                );
                            }
                        }
                    }
                }
            }

            // Self-Healing Fallback 1: Jika registry items5_sum kosong akibat bug sebelumnya, load dari transaksi_data
            if (empty($items5_sum)) {
                $this->db->where('transaksi_id', $invoice_id);
                $td_rows = $this->db->get('transaksi_data')->result_array();
                if ($td_rows) {
                    foreach ($td_rows as $tdr) {
                        if ((float)$tdr['produk_ord_jml'] > 0) {
                            $items5_sum[] = array(
                                'id' => $tdr['id'],
                                'produk_dasar_id' => $tdr['produk_id'],
                                'nama' => $tdr['produk_nama'],
                                'satuan' => isset($tdr['satuan']) ? $tdr['satuan'] : '',
                                'jml' => $tdr['produk_ord_jml'],
                                'harga' => $tdr['produk_ord_hrg']
                            );
                        }
                    }
                }
            }

            // Self-Healing Fallback 2: Jika masih kosong, load dari old_registry_data snapshot di history
            if (empty($items5_sum)) {
                $this->db->where('transaksi_id', $invoice_id);
                $this->db->order_by('id', 'ASC');
                $this->db->limit(1);
                $his = $this->db->get('transaksi_amandemen_history')->row_array();
                if ($his && !empty($his['old_registry_data'])) {
                    $old_reg = json_decode($his['old_registry_data'], true);
                    if ($old_reg && !empty($old_reg['items5_sum'])) {
                        $raw_items5_sum = @unserialize(base64_decode($old_reg['items5_sum']));
                        if (is_array($raw_items5_sum)) {
                            foreach ($raw_items5_sum as $td_data) {
                                $has_nested = false;
                                if (isset($td_data['bahan_baku']['produk']) && is_array($td_data['bahan_baku']['produk'])) {
                                    $has_nested = true;
                                    foreach ($td_data['bahan_baku']['produk'] as $pd_id => $prd) {
                                        if ((float)$prd['jml'] > 0) {
                                            $items5_sum[] = array(
                                                'id' => isset($prd['id']) ? $prd['id'] : 0,
                                                'produk_dasar_id' => isset($prd['produk_dasar_id']) ? $prd['produk_dasar_id'] : $pd_id,
                                                'nama' => isset($prd['produk_dasar_nama']) ? $prd['produk_dasar_nama'] : (isset($prd['nama']) ? $prd['nama'] : ''),
                                                'satuan' => isset($prd['satuan']) ? $prd['satuan'] : '',
                                                'jml' => $prd['jml'],
                                                'harga' => $prd['harga'],
                                                'jenis' => 'produk',
                                                'keterangan' => isset($prd['keterangan']) ? $prd['keterangan'] : ''
                                            );
                                        }
                                    }
                                }
                                if (isset($td_data['bahan_baku']['biaya']) && is_array($td_data['bahan_baku']['biaya'])) {
                                    $has_nested = true;
                                    foreach ($td_data['bahan_baku']['biaya'] as $pd_id => $biy) {
                                        if ((float)$biy['jml'] > 0) {
                                            $items5_sum[] = array(
                                                'id' => isset($biy['id']) ? $biy['id'] : 0,
                                                'produk_dasar_id' => isset($biy['produk_dasar_id']) ? $biy['produk_dasar_id'] : $pd_id,
                                                'nama' => isset($biy['produk_dasar_nama']) ? $biy['produk_dasar_nama'] : (isset($biy['nama']) ? $biy['nama'] : ''),
                                                'satuan' => isset($biy['satuan']) ? $biy['satuan'] : '',
                                                'jml' => $biy['jml'],
                                                'harga' => $biy['harga'],
                                                'jenis' => 'jasa',
                                                'keterangan' => isset($biy['keterangan']) ? $biy['keterangan'] : ''
                                            );
                                        }
                                    }
                                }
                                if (isset($td_data['bahan_baku']['supplies']) && is_array($td_data['bahan_baku']['supplies'])) {
                                    $has_nested = true;
                                    foreach ($td_data['bahan_baku']['supplies'] as $pd_id => $sup) {
                                        if ((float)$sup['jml'] > 0) {
                                            $items5_sum[] = array(
                                                'id' => isset($sup['id']) ? $sup['id'] : 0,
                                                'produk_dasar_id' => isset($sup['produk_dasar_id']) ? $sup['produk_dasar_id'] : $pd_id,
                                                'nama' => isset($sup['produk_dasar_nama']) ? $sup['produk_dasar_nama'] : (isset($sup['nama']) ? $sup['nama'] : ''),
                                                'satuan' => isset($sup['satuan']) ? $sup['satuan'] : '',
                                                'jml' => $sup['jml'],
                                                'harga' => $sup['harga'],
                                                'jenis' => 'supplies',
                                                'keterangan' => isset($sup['keterangan']) ? $sup['keterangan'] : ''
                                            );
                                        }
                                    }
                                }
                                
                                if (!$has_nested && is_array($td_data)) {
                                    if (isset($td_data['produk_dasar_id']) || isset($td_data['id'])) {
                                        if ((float)(isset($td_data['jml']) ? $td_data['jml'] : 0) > 0) {
                                            $items5_sum[] = array(
                                                'id' => isset($td_data['id']) ? $td_data['id'] : 0,
                                                'produk_dasar_id' => isset($td_data['produk_dasar_id']) ? $td_data['produk_dasar_id'] : (isset($td_data['id']) ? $td_data['id'] : 0),
                                                'nama' => isset($td_data['nama']) ? $td_data['nama'] : (isset($td_data['produk_dasar_nama']) ? $td_data['produk_dasar_nama'] : ''),
                                                'satuan' => isset($td_data['satuan']) ? $td_data['satuan'] : '',
                                                'jml' => isset($td_data['jml']) ? $td_data['jml'] : 0,
                                                'harga' => isset($td_data['harga']) ? $td_data['harga'] : 0,
                                                'jenis' => isset($td_data['jenis']) ? $td_data['jenis'] : 'produk',
                                                'keterangan' => isset($td_data['keterangan']) ? $td_data['keterangan'] : ''
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $items = @unserialize(base64_decode($registry['items']));
            if ($items && is_array($items)) {
                $first_item = reset($items);
                if (isset($first_item['project_id'])) {
                    $project_id = (int)$first_item['project_id'];
                } elseif (isset($first_item['projectID'])) {
                    $project_id = (int)$first_item['projectID'];
                }
                
                // --- JALUR OTOMATIS: Cari SPK yang terhubung ke invoice ini via inv_trx_id ---
                $original_spks = array();
                if ($transaksi_id > 0 && $this->db->table_exists('project_tasklist')) {
                    $this->db->select('no_spk');
                    $this->db->from('project_tasklist');
                    $this->db->like('inv_trx_id', (string)$transaksi_id);
                    $this->db->where('trash', 0);
                    $res = $this->db->get();
                    if ($res && $res->num_rows() > 0) {
                        foreach ($res->result_array() as $r) {
                            $original_spks[] = $r['no_spk'];
                        }
                    }
                }
                
                // --- JALUR OTOMATIS: Mapping no_spk ke items5_sum ---
                if (isset($items5_sum) && is_array($items5_sum)) {
                    $need_spk_injection = false;
                    foreach ($items5_sum as $itm) {
                        if (empty($itm['no_spk'])) {
                            $need_spk_injection = true;
                            break;
                        }
                    }

                    if ($need_spk_injection) {
                        $pd_ids = array();
                        foreach ($items5_sum as $itm) {
                            $pid = isset($itm['produk_dasar_id']) ? $itm['produk_dasar_id'] : (isset($itm['id']) ? $itm['id'] : 0);
                            if ($pid > 0) $pd_ids[] = $pid;
                        }
                        
                        $item_spk_map = array();
                        if (!empty($pd_ids) && $this->db->table_exists('project_sub_tasklist_komposisi')) {
                            $this->db->select('k.produk_dasar_id, k.no_spk');
                            $this->db->from('project_sub_tasklist_komposisi k');
                            if ($this->db->table_exists('project_tasklist')) {
                                $this->db->join('project_tasklist t', 'k.no_spk = t.no_spk', 'inner');
                                $this->db->where('t.produk_id', $project_id);
                            }
                            $this->db->where_in('k.produk_dasar_id', $pd_ids);
                            $this->db->where('k.trash', 0);
                            $res_komp = $this->db->get();
                            if ($res_komp && $res_komp->num_rows() > 0) {
                                foreach($res_komp->result_array() as $rk) {
                                    if (!empty($rk['no_spk'])) {
                                        $item_spk_map[$rk['produk_dasar_id']] = $rk['no_spk'];
                                    }
                                }
                            }
                        }

                        // Terapkan mapping ke array utama
                        foreach ($items5_sum as $k => $itm) {
                            if (empty($itm['no_spk'])) {
                                $pid = isset($itm['produk_dasar_id']) ? $itm['produk_dasar_id'] : (isset($itm['id']) ? $itm['id'] : 0);
                                if (isset($item_spk_map[$pid])) {
                                    $items5_sum[$k]['no_spk'] = $item_spk_map[$pid];
                                } elseif (count($original_spks) === 1) {
                                    // Fallback terakhir: jika gagal mapping tapi hanya ada 1 SPK yg tercatat
                                    $items5_sum[$k]['no_spk'] = $original_spks[0];
                                }
                            }
                        }
                    }
                }
                
                // Load informasi SPK unik & Gudang Virtual WO (gudang_wo) dari tabel project_sub_tasklist
                $spk_info_list = array();
                if ($project_id > 0 && $this->db->table_exists('project_sub_tasklist')) {
                    $pst_fields = $this->db->list_fields('project_sub_tasklist');
                    
                    $select_parts = array('DISTINCT s.no_spk');
                    if (in_array('gudang_wo', $pst_fields)) {
                        $select_parts[] = 's.gudang_wo';
                    }
                    if (in_array('produk_paket_nama', $pst_fields)) {
                        $select_parts[] = 's.produk_paket_nama';
                    }
                    if (in_array('produk_paket_id', $pst_fields)) {
                        $select_parts[] = 's.produk_paket_id';
                    }

                    if ($this->db->table_exists('project_tasklist')) {
                        $select_parts[] = 't.nama as spk_nama';
                    }

                    $this->db->select(implode(', ', $select_parts), false);
                    $this->db->from('project_sub_tasklist s');
                    if ($this->db->table_exists('project_tasklist')) {
                        $this->db->join('project_tasklist t', 's.no_spk = t.no_spk', 'left');
                    }
                    
                    if (in_array('gudang_wo', $pst_fields) && $this->db->table_exists('gudang')) {
                        $g_fields = $this->db->list_fields('gudang');
                        $this->db->select('g.nama as gudang_wo_nama');
                        if (in_array('nomer', $g_fields)) {
                            $this->db->join('gudang g', 'g.id = s.gudang_wo OR g.nomer = s.gudang_wo', 'left');
                        } elseif (in_array('kode', $g_fields)) {
                            $this->db->join('gudang g', 'g.id = s.gudang_wo OR g.kode = s.gudang_wo', 'left');
                        } else {
                            $this->db->join('gudang g', 'g.id = s.gudang_wo', 'left');
                        }
                    }

                    if (in_array('produk_id', $pst_fields)) {
                        $this->db->where('s.produk_id', $project_id);
                    } elseif ($this->db->table_exists('project_tasklist')) {
                        $this->db->where('t.produk_id', $project_id);
                    }
                    
                    if (in_array('trash', $pst_fields)) {
                        $this->db->where('s.trash', 0);
                    }

                    $this->db->where('s.no_spk IS NOT NULL', null, false);
                    $this->db->where("s.no_spk != ''", null, false);

                    $res_spk = $this->db->get();
                    if ($res_spk && $res_spk->num_rows() > 0) {
                        $spk_info_list = $res_spk->result_array();
                    }
                }
                $data['spk_info_list'] = $spk_info_list;

                // Cari produk yang terikat dengan gudang_wo di stock_locker (HANYA STATE = ACTIVE!)
                $locker_stock_list = array();
                $active_stock_map = array();
                if (!empty($spk_info_list)) {
                    foreach ($spk_info_list as $spk_item) {
                        $g_wo = isset($spk_item['gudang_wo']) ? $spk_item['gudang_wo'] : '';
                        if (!empty($g_wo)) {
                            // HANYA AMBIL STOK DENGAN STATE = 'active'
                            $stocks = $this->ComAmandemenInvoice->getLockerStockForGudangWo($g_wo, $project_id, 'active');
                            if (!empty($stocks)) {
                                $locker_stock_list[$g_wo] = $stocks;
                                foreach ($stocks as $stk) {
                                    $stk_state = isset($stk['state']) ? strtolower(trim($stk['state'])) : '';
                                    if ($stk_state === 'active') {
                                        $pid = (int)(isset($stk['produk_id']) ? $stk['produk_id'] : 0);
                                        $pd_id = (int)(isset($stk['produk_dasar_id']) ? $stk['produk_dasar_id'] : $pid);
                                        $jml = (float)(isset($stk['jumlah']) ? $stk['jumlah'] : 0);
                                        
                                        if ($pid > 0) {
                                            $active_stock_map[$pid] = isset($active_stock_map[$pid]) ? ($active_stock_map[$pid] + $jml) : $jml;
                                        }
                                        if ($pd_id > 0 && $pd_id != $pid) {
                                            $active_stock_map[$pd_id] = isset($active_stock_map[$pd_id]) ? ($active_stock_map[$pd_id] + $jml) : $jml;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $data['locker_stock_list'] = $locker_stock_list;
                $data['active_stock_map'] = $active_stock_map;
            }

            // Self-Healing Fallback 3: Rebuild dari project_sub_tasklist_komposisi
            // Ini adalah sumber kebenaran untuk invoice proyek 4822 yang items5_sum-nya
            // memang tidak pernah ter-capture saat pembuatan invoice asli.
            if (empty($items5_sum) && $project_id > 0 && $this->db->table_exists('project_sub_tasklist_komposisi')) {
                $this->db->select('k.id as komp_id, k.no_spk, k.jenis, k.produk_dasar_id, k.produk_dasar_nama, k.jml, k.harga as komp_harga, k.jml_return');
                if ($this->db->table_exists('project_tasklist')) {
                    $this->db->select('t.nama as spk_nama');
                    $this->db->join('project_tasklist t', 'k.no_spk = t.no_spk', 'inner');
                    $this->db->where('t.produk_id', $project_id);
                    if ($this->db->field_exists('trash', 'project_tasklist')) {
                        $this->db->where('t.trash', 0);
                    }
                }
                if ($this->db->field_exists('trash', 'project_sub_tasklist_komposisi')) {
                    $this->db->where('k.trash', 0);
                }
                if ($this->db->field_exists('progress_id', 'project_sub_tasklist_komposisi')) {
                    $this->db->where('k.progress_id !=', 3);
                }

                $res_rebuild = $this->db->get('project_sub_tasklist_komposisi k');
                if ($res_rebuild && $res_rebuild->num_rows() > 0) {
                    foreach ($res_rebuild->result_array() as $komp) {
                        $effective_qty = (float)$komp['jml'] - (float)$komp['jml_return'];
                        if ($effective_qty > 0) {
                            $items5_sum[] = array(
                                'id' => $komp['komp_id'],
                                'no_spk' => isset($komp['no_spk']) ? $komp['no_spk'] : '',
                                'produk_dasar_id' => $komp['produk_dasar_id'],
                                'nama' => $komp['produk_dasar_nama'],
                                'spk_nama' => isset($komp['spk_nama']) ? $komp['spk_nama'] : '',
                                'satuan' => '',
                                'jml' => $effective_qty,
                                'harga' => (float)$komp['komp_harga']
                            );
                        }
                    }
                }
            }
        }

        if ($project_id <= 0) {
            show_error('Modul Amandemen ini (Clean Invoice) khusus untuk transaksi berbasis Proyek. Untuk transaksi reguler (Grosir/Distribusi), silakan gunakan fitur Retur Penjualan biasa.', 403, 'Akses Ditolak (Bukan Proyek)');
        }

        $project_info = array();
        if ($project_id > 0) {
            $query = "
                SELECT p.produk_id, pr.nama as project_nama, pr.harga as nilai_project, COUNT(DISTINCT p.no_spk) as total_spk, 
                       COUNT(s.id) as total_items,
                       SUM(CASE WHEN s.jenis = 'biaya' OR s.jenis = 'supplies' THEN 1 ELSE 0 END) as total_supplies,
                       SUM(CASE WHEN s.jenis = 'produk' THEN 1 ELSE 0 END) as total_produk
                FROM project_tasklist p
                LEFT JOIN project_sub_tasklist_komposisi s ON p.no_spk = s.no_spk AND s.trash = 0 AND s.progress_id != 3
                INNER JOIN project_produk pr ON p.produk_id = pr.id
                WHERE p.produk_id = ? AND p.trash = 0
                GROUP BY p.produk_id
            ";
            $res = $this->db->query($query, array($project_id));
            if ($res && $res->num_rows() > 0) {
                $project_info = $res->row_array();
            }
        }

        // Pemetaan Stok Aktif (state = 'active') per PID (produk_dasar_id / produk_id)
        if (!empty($items5_sum)) {
            foreach ($items5_sum as &$it) {
                $pd_id = (int)(isset($it['produk_dasar_id']) ? $it['produk_dasar_id'] : 0);
                $p_id = (int)(isset($it['produk_id']) ? $it['produk_id'] : (isset($it['id']) ? $it['id'] : 0));

                $stok_aktif = 0;
                if ($pd_id > 0 && isset($active_stock_map[$pd_id])) {
                    $stok_aktif = $active_stock_map[$pd_id];
                } elseif ($p_id > 0 && isset($active_stock_map[$p_id])) {
                    $stok_aktif = $active_stock_map[$p_id];
                } else {
                    // Match by name or partial PID if active_stock_map missed
                    $p_nama = isset($it['nama']) ? strtolower(trim($it['nama'])) : '';
                    foreach ($locker_stock_list as $g_wo_key => $stk_arr) {
                        foreach ($stk_arr as $stk_row) {
                            $stk_state = isset($stk_row['state']) ? strtolower(trim($stk_row['state'])) : '';
                            if ($stk_state === 'active') {
                                $stk_pid = (int)(isset($stk_row['produk_id']) ? $stk_row['produk_id'] : 0);
                                $stk_nama = isset($stk_row['nama']) ? strtolower(trim($stk_row['nama'])) : (isset($stk_row['produk_nama']) ? strtolower(trim($stk_row['produk_nama'])) : '');
                                
                                if (($pd_id > 0 && $stk_pid == $pd_id) || ($p_id > 0 && $stk_pid == $p_id)) {
                                    $stok_aktif += (float)(isset($stk_row['jumlah']) ? $stk_row['jumlah'] : 0);
                                } elseif (!empty($p_nama) && !empty($stk_nama)) {
                                    $p_first = explode(' ', $p_nama)[0];
                                    $stk_first = explode(' ', $stk_nama)[0];
                                    if (strlen($p_first) >= 3 && $p_first == $stk_first) {
                                        $stok_aktif += (float)(isset($stk_row['jumlah']) ? $stk_row['jumlah'] : 0);
                                    }
                                }
                            }
                        }
                    }
                }
                $it['stok_aktif'] = $stok_aktif;
            }
            unset($it);
        }

        $data['invoice_id'] = $invoice_id;
        $data['invoice_no'] = $invoice_no;
        $data['cCode'] = "amd_" . $invoice_id;
        $data['items5_sum'] = $items5_sum;
        $data['project_id'] = $project_id;
        $data['project_info'] = $project_info;
        $data['registry_id'] = isset($registry['id']) ? $registry['id'] : 0;
        $data['current_description'] = $current_description;



        // Fetch History Amandemen (ASC for chronological processing)
        $this->db->where('transaksi_id', $invoice_id);
        $this->db->order_by('dtime', 'ASC');
        $raw_history = $this->db->get('transaksi_amandemen_history')->result_array();
        
        $history_amandemen_formatted = array();
        $counter = 1;
        foreach ($raw_history as $key => $row) {
            $old_data = json_decode($row['old_registry_data'], true);
            $nilai_sblm = 0;
            
            $main_encoded = null;
            if ($old_data && isset($old_data['registry']) && isset($old_data['registry']['main'])) {
                $main_encoded = $old_data['registry']['main'];
            } elseif ($old_data && isset($old_data['main'])) {
                $main_encoded = $old_data['main'];
            }
            
            if ($main_encoded) {
                $main = @unserialize(base64_decode($main_encoded));
                if ($main) {
                    if (isset($main['piutang_dagang']) && (float)$main['piutang_dagang'] > 0) {
                        $nilai_sblm = (float)$main['piutang_dagang'];
                    } elseif (isset($main['new_net3']) && (float)$main['new_net3'] > 0) {
                        $nilai_sblm = (float)$main['new_net3'];
                    } elseif (isset($main['grand_total'])) {
                        $nilai_sblm = (float)$main['grand_total'];
                    }
                }
            }
            
            // "Nilai Sesudah" is the "Nilai Sebelum" of the next amandemen
            $nilai_ssd = 0;
            if (isset($raw_history[$key + 1])) {
                $next_old_data = json_decode($raw_history[$key + 1]['old_registry_data'], true);
                
                $nmain_encoded = null;
                if ($next_old_data && isset($next_old_data['registry']) && isset($next_old_data['registry']['main'])) {
                    $nmain_encoded = $next_old_data['registry']['main'];
                } elseif ($next_old_data && isset($next_old_data['main'])) {
                    $nmain_encoded = $next_old_data['main'];
                }
                
                if ($nmain_encoded) {
                    $nmain = @unserialize(base64_decode($nmain_encoded));
                    if ($nmain) {
                        if (isset($nmain['piutang_dagang']) && (float)$nmain['piutang_dagang'] > 0) {
                            $nilai_ssd = (float)$nmain['piutang_dagang'];
                        } elseif (isset($nmain['new_net3']) && (float)$nmain['new_net3'] > 0) {
                            $nilai_ssd = (float)$nmain['new_net3'];
                        } elseif (isset($nmain['grand_total'])) {
                            $nilai_ssd = (float)$nmain['grand_total'];
                        }
                    }
                }
            } else {
                // If this is the last amandemen, the "Nilai Sesudah" is the current active invoice total
                $curr_inv = $this->db->select('transaksi_nilai')->where('id', $invoice_id)->get('transaksi')->row_array();
                if ($curr_inv) {
                    $nilai_ssd = (float)$curr_inv['transaksi_nilai'];
                }
            }
            
            $delta = $nilai_ssd - $nilai_sblm;
            $teks_delta = 'Rp 0 (Tetap)';
            if ($delta > 0) {
                $teks_delta = '+ Rp ' . number_format($delta, 0, ',', '.') . ' (Naik)';
            } elseif ($delta < 0) {
                $teks_delta = '- Rp ' . number_format(abs($delta), 0, ',', '.') . ' (Turun)';
            }
            
            $bytes = isset($row['old_registry_bytes']) && (int)$row['old_registry_bytes'] > 0 
                     ? (int)$row['old_registry_bytes'] 
                     : strlen($row['old_registry_data']);
            $size_kb = number_format($bytes / 1024, 1, ',', '.') . ' KB';

            $history_amandemen_formatted[] = array(
                'history_id' => $row['id'],
                'No. Amandemen' => $invoice_no . "-A" . str_pad($counter, 2, "0", STR_PAD_LEFT),
                'Waktu' => $row['dtime'],
                'Oleh' => $row['oleh_nama'],
                'Keterangan' => $row['keterangan'],
                'Nilai Sebelum' => 'Rp ' . number_format($nilai_sblm, 0, ',', '.'),
                'Nilai Sesudah' => 'Rp ' . number_format($nilai_ssd, 0, ',', '.'),
                'Nilai Delta' => $teks_delta,
                'Ukuran Snapshot' => $size_kb
            );
            $counter++;
        }
        // Reverse array so the newest is on top
        $data['history_amandemen'] = array_reverse($history_amandemen_formatted);
        $data['count_amandemen'] = count($raw_history);

        // Fetch History Return (Retur) yang dibuat oleh Amandemen ini
        // OPTIMASI & KUNCI EVEREST: link_id = 0 adalah transaksi primer (link_id > 0 hanya data bantu/tracing)
        $this->db->where('id >', $invoice_id);
        $this->db->where('link_id', 0);
        $this->db->group_start();
            $this->db->like('keterangan', 'Return', 'after'); 
            $this->db->or_like('keterangan', 'Retur', 'after'); 
        $this->db->group_end();
        $this->db->like('keterangan', $invoice_no);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(20);
        $data['history_return'] = $this->db->get('transaksi')->result_array();

        // Fetch History Jurnal
        // In Everest, Invoice 4822 often doesn't have journals, the journals are in its reference (e.g. 7499)
        $curr_trx = $this->db->get_where('transaksi', array('id' => $invoice_id))->row_array();
        $ref_id = ($curr_trx && isset($curr_trx['reference_id'])) ? (int)$curr_trx['reference_id'] : 0;
        $id_master = ($curr_trx && isset($curr_trx['id_master'])) ? (int)$curr_trx['id_master'] : 0;
        
        $termin_nomer = '';
        if ($ref_id > 0) {
            $ref_trx = $this->db->get_where('transaksi', array('id' => $ref_id))->row_array();
            if ($ref_trx) {
                $termin_nomer = $ref_trx['nomer'];
            }
        }
        
        // Build query untuk tabel jurnal (cakup invoice_id, reference_id, dan id_master)
        $target_ids = array_unique(array_filter(array((int)$invoice_id, $ref_id, $id_master)));
        if (!empty($target_ids)) {
            $this->db->where_in('transaksi_id', $target_ids);
        } else {
            $this->db->where('transaksi_id', $invoice_id);
        }
        if ($this->db->field_exists('trash', 'jurnal')) {
            $this->db->where('trash', 0);
        }
        $this->db->order_by('id', 'DESC');
        $raw_jurnal = $this->db->get('jurnal')->result_array();
        $data['termin_nomer'] = $termin_nomer;

        // Pemetaan nama akun standar termin proyek & pengisian field kosong pada histori jurnal
        $std_coa_names = array(
            '1010070030' => 'piutang usaha belum realisasi project',
            '4030'       => 'penjualan belum realisasi',
            '2030060'    => 'ppn out',
            '4010'       => 'penjualan',
            '1010020010' => 'piutang dagang'
        );

        $jurnal_terbit_awal = array();
        $jurnal_penyesuaian_history = array();

        foreach ($raw_jurnal as &$hj_item) {
            if (empty($hj_item['transaksi_no'])) {
                $hj_item['transaksi_no'] = $invoice_no;
            }
            $rek_code = trim($hj_item['rekening']);
            if (empty($hj_item['rekening_2']) && isset($std_coa_names[$rek_code])) {
                $hj_item['rekening_2'] = $std_coa_names[$rek_code];
            }
            if (empty($hj_item['rekening_nama'])) {
                $hj_item['rekening_nama'] = !empty($hj_item['rekening_2']) ? $hj_item['rekening_2'] : (isset($std_coa_names[$rek_code]) ? $std_coa_names[$rek_code] : 'Akun ' . $rek_code);
            }

            $is_adj = (strpos(isset($hj_item['keterangan']) ? $hj_item['keterangan'] : '', '[Amandemen') !== false);
            if ($is_adj) {
                $jurnal_penyesuaian_history[] = $hj_item;
            } else {
                $jurnal_terbit_awal[] = $hj_item;
            }
        }
        unset($hj_item);

        $data['history_jurnal'] = $raw_jurnal;
        $data['jurnal_terbit_awal'] = $jurnal_terbit_awal;
        $data['jurnal_penyesuaian_history'] = $jurnal_penyesuaian_history;
        
        // Ambil nilai asli dari registry['main'] atau history_jurnal (bukan dari header transaksi yang sering NULL pada termin proyek)
        $orig_dpp = 0;
        $orig_ppn = 0;
        $orig_gt  = 0;

        if ($registry && !empty($registry['main'])) {
            $reg_main = @unserialize(base64_decode($registry['main']));
            if ($reg_main && is_array($reg_main)) {
                $orig_gt  = isset($reg_main['piutang_dagang']) ? (float)$reg_main['piutang_dagang'] : (isset($reg_main['grand_total']) ? (float)$reg_main['grand_total'] : (isset($reg_main['new_net3']) ? (float)$reg_main['new_net3'] : 0));
                $orig_dpp = isset($reg_main['dpp_ppn']) ? (float)$reg_main['dpp_ppn'] : (isset($reg_main['penjualan']) ? (float)$reg_main['penjualan'] : (isset($reg_main['subtotal']) ? (float)$reg_main['subtotal'] : 0));
                $orig_ppn = isset($reg_main['ppn']) ? (float)$reg_main['ppn'] : (isset($reg_main['ppn_out_bulat']) ? (float)$reg_main['ppn_out_bulat'] : (isset($reg_main['grand_ppn']) ? (float)$reg_main['grand_ppn'] : 0));
            }
        }

        // Fallback ke history_jurnal jika registry main belum lengkap
        if (($orig_dpp <= 0 || $orig_gt <= 0) && !empty($data['history_jurnal'])) {
            foreach ($data['history_jurnal'] as $hj) {
                $rek = isset($hj['rekening']) ? (string)$hj['rekening'] : '';
                $deb = isset($hj['debet']) ? (float)$hj['debet'] : 0;
                $krd = isset($hj['kredit']) ? (float)$hj['kredit'] : 0;
                if ($rek === '4010' || strpos(strtolower(isset($hj['rekening_nama']) ? $hj['rekening_nama'] : ''), 'penjualan') !== false) {
                    if ($krd > 0 && $orig_dpp <= 0) $orig_dpp = $krd;
                } elseif ($rek === '2030060' || strpos(strtolower(isset($hj['rekening_nama']) ? $hj['rekening_nama'] : ''), 'ppn') !== false) {
                    if ($krd > 0 && $orig_ppn <= 0) $orig_ppn = $krd;
                } elseif ($rek === '1010020010' || strpos(strtolower(isset($hj['rekening_nama']) ? $hj['rekening_nama'] : ''), 'piutang') !== false) {
                    if ($deb > 0 && $orig_gt <= 0) $orig_gt = $deb;
                }
            }
        }

        // Fallback ke items5_sum jika tersedia
        if ($orig_dpp <= 0 && !empty($items5_sum) && is_array($items5_sum)) {
            $sum_dpp = 0;
            foreach ($items5_sum as $it5) {
                $it_qty = isset($it5['jml']) ? (float)$it5['jml'] : 0;
                $it_hrg = isset($it5['harga']) ? (float)$it5['harga'] : 0;
                $sum_dpp += ($it_qty * $it_hrg);
            }
            if ($sum_dpp > 0) {
                $orig_dpp = $sum_dpp;
                if ($orig_ppn <= 0) $orig_ppn = round($orig_dpp * 0.11);
                if ($orig_gt <= 0) $orig_gt = $orig_dpp + $orig_ppn;
            }
        }

        // Fallback terakhir ke curr_trx
        if ($orig_dpp <= 0 && $curr_trx && isset($curr_trx['transaksi_net']) && (float)$curr_trx['transaksi_net'] > 0) {
            $orig_dpp = (float)$curr_trx['transaksi_net'];
        }
        if ($orig_ppn <= 0 && $curr_trx && isset($curr_trx['ppn_nilai']) && (float)$curr_trx['ppn_nilai'] > 0) {
            $orig_ppn = (float)$curr_trx['ppn_nilai'];
        }
        if ($orig_gt <= 0 && $curr_trx && isset($curr_trx['transaksi_nilai']) && (float)$curr_trx['transaksi_nilai'] > 0) {
            $orig_gt = (float)$curr_trx['transaksi_nilai'];
        }
        if ($orig_gt <= $orig_dpp && $orig_ppn > 0) {
            $orig_gt = $orig_dpp + $orig_ppn;
        }
        
        $data['orig_dpp'] = $orig_dpp;
        $data['orig_ppn'] = $orig_ppn;
        $data['orig_gt'] = $orig_gt;

        // Tentukan Tagihan Awal Murni (Nilai Terbit Awal sebelum pernah diamandemen)
        $tagihan_awal_murni = 0;
        $dpp_awal_murni     = 0;
        $ppn_awal_murni     = 0;

        if (!empty($raw_history)) {
            $first_his = reset($raw_history);
            $old_snap = json_decode($first_his['old_registry_data'], true);
            if ($old_snap) {
                $first_main = null;
                if (isset($old_snap['registry']['main'])) {
                    $first_main = @unserialize(base64_decode($old_snap['registry']['main']));
                } elseif (isset($old_snap['main'])) {
                    $first_main = @unserialize(base64_decode($old_snap['main']));
                }
                if ($first_main && is_array($first_main)) {
                    $tagihan_awal_murni = isset($first_main['piutang_dagang']) ? (float)$first_main['piutang_dagang'] : (isset($first_main['grand_total']) ? (float)$first_main['grand_total'] : 0);
                    $dpp_awal_murni     = isset($first_main['dpp_ppn']) ? (float)$first_main['dpp_ppn'] : (isset($first_main['penjualan']) ? (float)$first_main['penjualan'] : 0);
                    $ppn_awal_murni     = isset($first_main['ppn']) ? (float)$first_main['ppn'] : (isset($first_main['ppn_out_bulat']) ? (float)$first_main['ppn_out_bulat'] : 0);
                }
            }
        }

        if ($tagihan_awal_murni <= 0 && !empty($jurnal_terbit_awal)) {
            foreach ($jurnal_terbit_awal as $jta) {
                $rek = trim($jta['rekening']);
                $deb = (float)$jta['debet'];
                $krd = (float)$jta['kredit'];
                if ($rek === '4010' && $krd > 0 && $dpp_awal_murni <= 0) $dpp_awal_murni = $krd;
                if ($rek === '2030060' && $krd > 0 && $ppn_awal_murni <= 0) $ppn_awal_murni = $krd;
                if ($rek === '1010020010' && $deb > 0 && $tagihan_awal_murni <= 0) $tagihan_awal_murni = $deb;
            }
        }

        if ($tagihan_awal_murni <= 0) {
            $tagihan_awal_murni = $orig_gt;
            $dpp_awal_murni     = $orig_dpp;
            $ppn_awal_murni     = $orig_ppn;
        }

        $data['tagihan_awal_murni'] = $tagihan_awal_murni;
        $data['dpp_awal_murni']     = $dpp_awal_murni;
        $data['ppn_awal_murni']     = $ppn_awal_murni;

        // Fetch Status Payment Source (Kartu Piutang & Terbayar Kasir)
        $payment_source_info = array(
            'tagihan'  => (float)$orig_gt,
            'terbayar' => 0,
            'sisa'     => (float)$orig_gt,
            'lunas'    => 0
        );
        if ($this->db->table_exists('transaksi_payment_source')) {
            $tps_target_ids = array_unique(array_filter(array((int)$invoice_id, $ref_id, $id_master)));
            if (!empty($tps_target_ids)) {
                $this->db->where_in('transaksi_id', $tps_target_ids);
                if ($this->db->field_exists('trash', 'transaksi_payment_source')) {
                    $this->db->where('trash', 0);
                }
                $tps_row = $this->db->order_by('id', 'DESC')->get('transaksi_payment_source')->row_array();
                if ($tps_row) {
                    $payment_source_info['tagihan']  = (float)$tps_row['tagihan'];
                    $payment_source_info['terbayar'] = (float)$tps_row['terbayar'];
                }
            }
        }
        if (isset($curr_trx['transaksi_dibayar']) && (float)$curr_trx['transaksi_dibayar'] > $payment_source_info['terbayar']) {
            $payment_source_info['terbayar'] = (float)$curr_trx['transaksi_dibayar'];
        }

        // Kalkulasi akurat sisa & status lunas terhadap nilai tagihan aktif
        $calc_sisa = max(0, (float)$orig_gt - (float)$payment_source_info['terbayar']);
        $payment_source_info['sisa'] = $calc_sisa;
        $payment_source_info['lunas'] = ($calc_sisa <= 0 && $payment_source_info['terbayar'] > 0) ? 1 : 0;

        $data['payment_source'] = $payment_source_info;

        // Load Daftar COA Rekening Akuntansi secara Dinamis (Presisi & Tahan Terhadap Perbedaan Skema DB)
        $coa_list = array();
        if ($this->db->table_exists('acc_coa')) {
            $fields = $this->db->list_fields('acc_coa');
            $code_col = in_array('head_code', $fields) ? 'head_code' : (in_array('rekening', $fields) ? 'rekening' : (in_array('kode', $fields) ? 'kode' : ''));
            $name_col = in_array('head_name', $fields) ? 'head_name' : (in_array('nama', $fields) ? 'nama' : '');

            if ($code_col && $name_col) {
                $this->db->select("{$code_col} AS kode, {$name_col} AS nama");
                if (in_array('is_active', $fields)) {
                    $this->db->where('is_active', 1);
                }
                $this->db->order_by($code_col, 'ASC');
                $coa_list = $this->db->get('acc_coa')->result_array();
            }
        } elseif ($this->db->table_exists('rekening')) {
            $fields = $this->db->list_fields('rekening');
            $code_col = in_array('id', $fields) ? 'id' : (in_array('kode', $fields) ? 'kode' : '');
            $name_col = in_array('nama', $fields) ? 'nama' : (in_array('name', $fields) ? 'name' : '');
            if ($code_col && $name_col) {
                $this->db->select("{$code_col} AS kode, {$name_col} AS nama");
                $this->db->order_by($code_col, 'ASC');
                $coa_list = $this->db->get('rekening')->result_array();
            }
        }
        $data['coa_list'] = $coa_list;

        $this->load->view('v_amandemen_form', $data);
    }
}
