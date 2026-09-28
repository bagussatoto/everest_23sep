<?php

// START OF COMPLETE REPEATED LOGIC
/**
 * Controller SpkPaymentSource
 * Tool untuk mencari, mengaudit, dan mendiagnosa SPK yang Payment Source (3675)
 * belum/tidak diterbitkan, serta dilengkapi tombol aksi untuk menerbitkannya.
 * 
 * Kompatibel penuh dengan PHP 5.6 & CodeIgniter 3 HMVC (Wiredesignz)
 */
class SpkPaymentSource extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper("he_misc");
        $this->load->helper("url");
    }

    /**
     * Halaman utama tool audit SPK & Payment Source 3675
     * Mendukung tampilan HTML interaktif, export CSV, dan format JSON
     */
    public function index()
    {
        // Parameter filter dari GET
        $search_spk      = isset($_GET['spk']) ? trim($_GET['spk']) : "";
        $search_vendor   = isset($_GET['vendor']) ? trim($_GET['vendor']) : "";
        $filter_tipe     = isset($_GET['tipe']) ? trim($_GET['tipe']) : "all";             // all, reguler, tambahan
        $filter_pelaksana= isset($_GET['pelaksana']) ? trim($_GET['pelaksana']) : "vendor"; // vendor (22), internal (11), all
        $filter_status   = isset($_GET['status_3675']) ? trim($_GET['status_3675']) : "missing"; // missing, exists, all
        $filter_kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : "all";       // all, approved_skip, not_approved, no_post_biaya, not_qc
        $filter_progress = isset($_GET['progress']) ? trim($_GET['progress']) : "qc_selesai"; // qc_selesai (3), all
        $format          = isset($_GET['format']) ? trim($_GET['format']) : "html";          // html, json, csv

        // 1. Ambil peta pembayaran yang sudah terdaftar di transaksi_payment_source (target_jenis = 3675)
        $this->db->select("id, jenis, target_jenis, extern4_nama, tagihan, sisa, nomer, dtime");
        $this->db->where("target_jenis", "3675");
        $q_ps = $this->db->get("transaksi_payment_source");
        $rows_ps = $q_ps->result_array();

        $map_ps = array();
        foreach ($rows_ps as $ps_row) {
            $spk_key = trim($ps_row['extern4_nama']);
            if ($spk_key !== "") {
                $map_ps[$spk_key] = $ps_row;
            }
        }

        // 2. Ambil peta transaksi Approval 3674 untuk menghubungkan ke 3674r
        $this->db->select("id, nomer, jenis, dtime, ids_his, oleh_id, oleh_nama");
        $this->db->where("jenis", "3674");
        $this->db->where("trash", 0);
        $q_app = $this->db->get("transaksi");
        $rows_app = $q_app->result_array();

        $map_app = array();
        foreach ($rows_app as $app_row) {
            if (!empty($app_row['ids_his'])) {
                $his = @unserialize(base64_decode($app_row['ids_his']));
                if (is_array($his) && isset($his[1]['trID'])) {
                    $tr_id_step1 = (int)$his[1]['trID'];
                    $map_app[$tr_id_step1] = array(
                        "approval_id"    => $app_row['id'],
                        "approval_nomer" => $app_row['nomer'],
                        "approval_dtime" => $app_row['dtime'],
                        "oleh_id"        => $app_row['oleh_id'],
                        "oleh_nama"      => $app_row['oleh_nama'],
                    );
                }
            }
        }

        // 3. Tentukan tabel yang akan dipindai
        $target_tables = array();
        if ($filter_tipe === "all" || $filter_tipe === "reguler") {
            $target_tables["Reguler"] = "project_tasklist";
        }
        if ($filter_tipe === "all" || $filter_tipe === "tambahan") {
            $target_tables["Tambahan"] = "project_tasklist_tambahan";
        }

        // 4. Kumpulkan seluruh data SPK
        $raw_spk_list = array();
        $post_biaya_ids = array();

        foreach ($target_tables as $tipe_label => $tbl_name) {
            $this->db->select("pt.id, pt.no_spk, pt.employee_id, pt.employee_nama, pt.type_pelaksana, "
                . "pt.produk_id, pt.nama as project_nama, pt.produk_nama, pt.owner_nama, pt.produk_paket_nama, "
                . "pt.progress_id, pt.progress_nama, pt.qc_dtime, pt.qc_auth_nama, "
                . "pt.post_biaya_id, pt.post_biaya_no, pt.post_biaya_dtime, pt.nilai_sub_fase");
            $this->db->where("pt.status", 1);
            $this->db->where("pt.trash", 0);

            if ($filter_pelaksana === "vendor") {
                $this->db->where("pt.type_pelaksana", 22);
            } elseif ($filter_pelaksana === "internal") {
                $this->db->where("pt.type_pelaksana", 11);
            }

            if ($filter_progress === "qc_selesai") {
                $this->db->where("pt.progress_id", 3);
            }

            if ($search_spk !== "") {
                $this->db->like("pt.no_spk", $search_spk);
            }

            if ($search_vendor !== "") {
                $this->db->like("pt.employee_nama", $search_vendor);
            }

            $this->db->order_by("pt.id", "DESC");
            $q_tasks = $this->db->get($tbl_name . " pt");
            $rows_task = $q_tasks->result_array();

            foreach ($rows_task as $t_row) {
                $t_row['tipe_spk'] = $tipe_label;
                $t_row['tabel_sumber'] = $tbl_name;
                $raw_spk_list[] = $t_row;

                if (!empty($t_row['post_biaya_id']) && (int)$t_row['post_biaya_id'] > 0) {
                    $post_biaya_ids[] = (int)$t_row['post_biaya_id'];
                }
            }
        }

        // 5. Ambil nilai riil transaksi 3674r secara massal (bulk)
        $map_nilai_3674r = array();
        if (count($post_biaya_ids) > 0) {
            $post_biaya_ids = array_unique($post_biaya_ids);
            $this->db->select("id, nomer, transaksi_nilai, jenis, trash");
            $this->db->where_in("id", $post_biaya_ids);
            $q_tr_nilai = $this->db->get("transaksi");
            $rows_tr_nilai = $q_tr_nilai->result_array();
            foreach ($rows_tr_nilai as $trn) {
                $map_nilai_3674r[(int)$trn['id']] = (float)$trn['transaksi_nilai'];
            }
        }

        // 6. Analisa dan kategorisasi masing-masing SPK
        $summary = array(
            "total_spk"             => 0,
            "has_3675"              => 0,
            "missing_3675"          => 0,
            "cat_approved_skip"     => 0,
            "cat_not_approved"      => 0,
            "cat_no_post_biaya"     => 0,
            "cat_not_qc"            => 0,
            "total_nilai_missing"   => 0,
            "total_nilai_has"       => 0,
        );

        $processed_list = array();

        foreach ($raw_spk_list as $spk_item) {
            $summary["total_spk"]++;
            $no_spk = trim($spk_item['no_spk']);
            $has_3675 = isset($map_ps[$no_spk]);

            $p_biaya_id = (int)$spk_item['post_biaya_id'];
            $has_approval = ($p_biaya_id > 0 && isset($map_app[$p_biaya_id]));
            $approval_info = $has_approval ? $map_app[$p_biaya_id] : null;

            // Hitung estimasi nilai upah
            $nilai_upah = 0;
            if ($p_biaya_id > 0 && isset($map_nilai_3674r[$p_biaya_id])) {
                $nilai_upah = $map_nilai_3674r[$p_biaya_id];
            } else {
                $nilai_upah = (float)$spk_item['nilai_sub_fase'];
            }

            // Klasifikasi diagnosa
            $kategori = "";
            $diagnosa_txt = "";
            $solusi_txt = "";

            if ($has_3675) {
                $kategori = "exists";
                $diagnosa_txt = "Payment Source 3675 sudah terbit di sistem pembayaran.";
                $solusi_txt = "Tidak ada tindakan yang diperlukan.";
                $summary["has_3675"]++;
                $summary["total_nilai_has"] += $nilai_upah;
            } else {
                $summary["missing_3675"]++;
                $summary["total_nilai_missing"] += $nilai_upah;

                if ($p_biaya_id > 0 && $has_approval) {
                    $kategori = "approved_skip";
                    $diagnosa_txt = "3674r & Approval selesai, namun 3675 TIDAK DIBUAT (piutang_tambah = 0 saat proses approval).";
                    $solusi_txt = "Perlu penerbitan record payment source 3675 dengan tagihan senilai upah SPK.";
                    $summary["cat_approved_skip"]++;
                } elseif ($p_biaya_id > 0 && !$has_approval) {
                    $kategori = "not_approved";
                    $diagnosa_txt = "Dokumen 3674r sudah terbit, namun approval 3674 belum dijalankan (menggantung di Step 1).";
                    $solusi_txt = "Lanjutkan proses approval transaksi 3674.";
                    $summary["cat_not_approved"]++;
                } elseif ($p_biaya_id === 0 && (int)$spk_item['progress_id'] === 3) {
                    $kategori = "no_post_biaya";
                    $diagnosa_txt = "QC sudah selesai, namun AutoPostingBiaya gagal atau belum pernah menerbitkan dokumen 3674r.";
                    $solusi_txt = "Panggil ulang fungsi AutoPostingBiaya untuk no_spk ini.";
                    $summary["cat_no_post_biaya"]++;
                } else {
                    $kategori = "not_qc";
                    $diagnosa_txt = "Pekerjaan belum melalui tahap Quality Control (progress_id < 3).";
                    $solusi_txt = "Lakukan QC pada modul master project terlebih dahulu.";
                    $summary["cat_not_qc"]++;
                }
            }

            // Filter berdasarkan status 3675
            if ($filter_status === "missing" && $has_3675) {
                continue;
            }
            if ($filter_status === "exists" && !$has_3675) {
                continue;
            }

            // Filter berdasarkan kategori diagnosa
            if ($filter_kategori !== "all" && $kategori !== $filter_kategori) {
                continue;
            }

            $spk_item['has_3675']       = $has_3675;
            $spk_item['ps_data']        = $has_3675 ? $map_ps[$no_spk] : null;
            $spk_item['has_approval']   = $has_approval;
            $spk_item['approval_data']  = $approval_info;
            $spk_item['nilai_upah']     = $nilai_upah;
            $spk_item['kategori']       = $kategori;
            $spk_item['diagnosa_txt']   = $diagnosa_txt;
            $spk_item['solusi_txt']     = $solusi_txt;

            $processed_list[] = $spk_item;
        }

        // 7. Output handler: JSON
        if ($format === "json") {
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode(array(
                "status"   => 1,
                "summary"  => $summary,
                "filter"   => array(
                    "spk"         => $search_spk,
                    "vendor"      => $search_vendor,
                    "tipe"        => $filter_tipe,
                    "pelaksana"   => $filter_pelaksana,
                    "status_3675" => $filter_status,
                    "kategori"    => $filter_kategori,
                    "progress"    => $filter_progress,
                ),
                "total_rows" => count($processed_list),
                "data"       => $processed_list,
            ));
            exit();
        }

        // 8. Output handler: CSV
        if ($format === "csv") {
            header("Content-Type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=audit_spk_missing_3675_" . date("Ymd_His") . ".csv");
            $out = fopen("php://output", "w");
            fputcsv($out, array(
                "No",
                "Tipe SPK",
                "No SPK",
                "Nama Pelaksana / Vendor",
                "Tipe Pelaksana",
                "Proyek",
                "Paket / Tugas",
                "Status Progress",
                "Tanggal QC",
                "Estimasi Upah SPK (Rp)",
                "No 3674r (Request)",
                "ID 3674r",
                "No 3674 (Approval)",
                "ID Approval",
                "Status 3675",
                "No Payment Source (3675)",
                "Tagihan 3675 (Rp)",
                "Kategori Diagnosa",
                "Analisa Penyebab Masalah"
            ));

            $idx = 0;
            foreach ($processed_list as $row) {
                $idx++;
                fputcsv($out, array(
                    $idx,
                    $row['tipe_spk'],
                    $row['no_spk'],
                    $row['employee_nama'],
                    ($row['type_pelaksana'] == 22 ? "Vendor" : "Internal"),
                    $row['produk_nama'],
                    $row['produk_paket_nama'],
                    $row['progress_nama'],
                    $row['qc_dtime'],
                    $row['nilai_upah'],
                    $row['post_biaya_no'],
                    $row['post_biaya_id'],
                    $row['has_approval'] ? $row['approval_data']['approval_nomer'] : "-",
                    $row['has_approval'] ? $row['approval_data']['approval_id'] : "-",
                    $row['has_3675'] ? "SUDAH ADA" : "MISSING",
                    $row['has_3675'] ? $row['ps_data']['nomer'] : "-",
                    $row['has_3675'] ? $row['ps_data']['tagihan'] : 0,
                    $row['kategori'],
                    $row['diagnosa_txt']
                ));
            }
            fclose($out);
            exit();
        }

        // 9. Output handler: HTML View
        $this->renderHtmlView($summary, $processed_list, array(
            "spk"         => $search_spk,
            "vendor"      => $search_vendor,
            "tipe"        => $filter_tipe,
            "pelaksana"   => $filter_pelaksana,
            "status_3675" => $filter_status,
            "kategori"    => $filter_kategori,
            "progress"    => $filter_progress,
        ));
    }

    /**
     * Endpoint untuk menerbitkan Payment Source 3675 untuk 1 SPK spesifik
     */
    public function publishSingle()
    {
        // START OF COMPLETE REPEATED LOGIC
        $no_spk = isset($_REQUEST['spk']) ? trim($_REQUEST['spk']) : "";
        if ($no_spk === "") {
            echo json_encode(array("status" => 0, "message" => "Parameter nomor SPK kosong."));
            exit();
        }

        // 1. Ambil data SPK
        $this->db->where("no_spk", $no_spk);
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $spk = $this->db->get("project_tasklist")->row_array();
        $tipe = "Reguler";
        $tbl_spk = "project_tasklist";

        if (empty($spk)) {
            $this->db->where("no_spk", $no_spk);
            $this->db->where("status", 1);
            $this->db->where("trash", 0);
            $spk = $this->db->get("project_tasklist_tambahan")->row_array();
            $tipe = "Tambahan";
            $tbl_spk = "project_tasklist_tambahan";
        }

        if (empty($spk)) {
            echo json_encode(array("status" => 0, "message" => "SPK [$no_spk] tidak ditemukan di database aktif."));
            exit();
        }

        // 2. Cek apakah Payment Source 3675 sudah pernah dibuat untuk SPK ini
        $this->db->where("target_jenis", "3675");
        $this->db->where("extern4_nama", $no_spk);
        $exist_ps = $this->db->get("transaksi_payment_source")->row_array();
        if (!empty($exist_ps)) {
            echo json_encode(array(
                "status"  => 0,
                "message" => "Payment Source 3675 untuk SPK [$no_spk] sudah ada di database (ID: " . $exist_ps['id'] . ", Nomer: " . $exist_ps['nomer'] . ").",
            ));
            exit();
        }

        $p_biaya_id = (int)$spk['post_biaya_id'];

        // SKENARIO A: 3674r SUDAH ADA (post_biaya_id > 0)
        if ($p_biaya_id > 0) {
            // Ambil data transaksi 3674r
            $this->db->where("id", $p_biaya_id);
            $tr_request = $this->db->get("transaksi")->row_array();
            if (empty($tr_request)) {
                echo json_encode(array("status" => 0, "message" => "Dokumen request 3674r (ID: $p_biaya_id) tidak ditemukan."));
                exit();
            }

            // Cari transaksi Approval 3674
            $this->db->where("jenis", "3674");
            $this->db->where("trash", 0);
            $all_apps = $this->db->get("transaksi")->result_array();
            $tr_approval = null;
            foreach ($all_apps as $app) {
                if (!empty($app['ids_his'])) {
                    $his = @unserialize(base64_decode($app['ids_his']));
                    if (is_array($his) && isset($his[1]['trID']) && (int)$his[1]['trID'] === $p_biaya_id) {
                        $tr_approval = $app;
                        break;
                    }
                }
            }

            if (empty($tr_approval)) {
                echo json_encode(array(
                    "status"  => 0,
                    "message" => "Dokumen 3674r (ID: $p_biaya_id) belum diapprove menjadi 3674. Silahkan lakukan approval 3674 terlebih dahulu di modul biaya.",
                ));
                exit();
            }

            // Hitung nilai upah riil
            $nilai_upah = (float)$tr_request['transaksi_nilai'];
            if ($nilai_upah <= 0) {
                $nilai_upah = (float)$spk['nilai_sub_fase'];
            }

            // Tentukan PIC penanggung jawab: WAJIB gunakan dari data transaksi approval 3674
            $oleh_id = !empty($tr_approval['oleh_id']) ? $tr_approval['oleh_id'] : "979";
            $oleh_nama = !empty($tr_approval['oleh_nama']) ? $tr_approval['oleh_nama'] : "Zahra by system";

            // Tentukan Nama Proyek riil (dari $spk['nama'] yang merupakan nama proyek, fallback ke master project jika kosong)
            $project_nama = !empty($spk['nama']) ? $spk['nama'] : "";
            if ($project_nama === "" && !empty($spk['produk_id'])) {
                $this->db->where("id", $spk['produk_id']);
                $q_pp = $this->db->get("project_produk")->row_array();
                if (!empty($q_pp['nama'])) {
                    $project_nama = $q_pp['nama'];
                }
            }
            if ($project_nama === "") {
                $project_nama = $spk['produk_nama'];
            }

            // Bentuk array data transaksi_payment_source sesuai standar sistem
            $arrPymSrc = array(
                "jenis"           => "3674",
                "target_jenis"    => "3675",
                "reference_jenis" => "3674",
                "transaksi_id"    => $tr_approval['id'],
                "extern_id"       => $spk['employee_id'],
                "extern_nama"     => $spk['employee_nama'],
                "nomer"           => $tr_approval['nomer'],
                "label"           => "budget project",
                "tagihan"         => $nilai_upah,
                "terbayar"        => 0,
                "sisa"            => $nilai_upah,
                "cabang_id"       => "-1",
                "cabang_nama"     => "pusat",
                "oleh_id"         => $oleh_id,
                "oleh_nama"       => $oleh_nama,
                "dtime"           => date("Y-m-d H:i:s"),
                "fulldate"        => date("Y-m-d"),
                "project_id"      => $spk['produk_id'],
                "project_nama"    => $project_nama,
                "extern2_id"      => ".1",
                "extern2_nama"    => ".dipotong",
                "extern3_id"      => $spk['id'],
                "extern3_nama"    => $spk['produk_nama'],
                "extern4_nama"    => $no_spk,
                "extern5_id"      => "-1",
                "extern5_nama"    => "pusat",
                "customers_id"    => 0,
                "customers_nama"  => !empty($spk['owner_nama']) ? $spk['owner_nama'] : "",
            );

            // Eksekusi transaksi database secara aman
            $this->db->trans_start();

            $this->db->insert("transaksi_payment_source", $arrPymSrc);
            $new_ps_id = $this->db->insert_id();

            // Update piutang_tambah di transaksi_data_registry agar data sesi konsisten
            foreach (array($p_biaya_id, (int)$tr_approval['id']) as $tr_reg_id) {
                $this->db->where("transaksi_id", $tr_reg_id);
                $q_reg = $this->db->get("transaksi_data_registry")->row_array();
                if (!empty($q_reg['main'])) {
                    $main_arr = @unserialize(base64_decode($q_reg['main']));
                    if (is_array($main_arr)) {
                        $main_arr['piutang_tambah'] = $nilai_upah;
                        $main_arr['biaya_tambahan'] = ($tipe === "Tambahan") ? $nilai_upah : 0;
                        $update_blob = base64_encode(serialize($main_arr));
                        $this->db->where("transaksi_id", $tr_reg_id);
                        $this->db->update("transaksi_data_registry", array("main" => $update_blob));
                    }
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                echo json_encode(array(
                    "status"  => 0,
                    "message" => "Gagal menulis payment source ke database.",
                ));
                exit();
            }

            echo json_encode(array(
                "status"   => 1,
                "message"  => "Berhasil menerbitkan Payment Source 3675 untuk SPK [$no_spk] (PS ID: $new_ps_id, Tagihan: Rp " . number_format($nilai_upah, 0, ',', '.') . ").",
                "ps_id"    => $new_ps_id,
                "nomer_ps" => $tr_approval['nomer'],
                "tagihan"  => $nilai_upah,
            ));
            exit();
        }

        // SKENARIO B: 3674r BELUM ADA (post_biaya_id == 0) dan QC Selesai
        if ((int)$spk['progress_id'] === 3 && $p_biaya_id === 0) {
            // Panggil API AutoPostingBiaya untuk menerbitkan 3674r, approval, dan 3675 secara terintegrasi
            $this->load->library("Curl");
            $curl = new Curl();

            $login_data = isset($this->session->login) ? $this->session->login : array(
                'id'        => '979',
                'nama'      => 'Zahra by system',
                'cabang_id' => '1',
            );

            $apiConnect = array(
                "jenistr"       => "3674",
                "no_spk"        => $no_spk,
                "debuger"       => 0,
                "login_connect" => $login_data,
            );

            $urlConnect = base_url() . "biaya/AutoPostingBiaya/index/3674?debuger=0";
            $api_raw = $curl->_simple_call("post", $urlConnect, $apiConnect);
            $api_res = @json_decode($api_raw, true);

            if (isset($api_res['status']) && $api_res['status'] == 1) {
                echo json_encode(array(
                    "status"  => 1,
                    "message" => "Berhasil memposting biaya dan menerbitkan Payment Source 3675 untuk SPK [$no_spk] melalui AutoPostingBiaya.",
                    "detail"  => $api_res,
                ));
                exit();
            } else {
                $reason = isset($api_res['reason']) ? $api_res['reason'] : "Gagal memanggil AutoPostingBiaya ($api_raw)";
                echo json_encode(array(
                    "status"  => 0,
                    "message" => "Gagal auto posting biaya: " . $reason,
                ));
                exit();
            }
        }

        echo json_encode(array(
            "status"  => 0,
            "message" => "SPK [$no_spk] belum melalui tahap Quality Control (progress_id < 3). Selesaikan QC terlebih dahulu.",
        ));
        exit();
        // END OF COMPLETE REPEATED LOGIC
    }

    /**
     * Endpoint untuk menerbitkan Payment Source 3675 secara massal (Batch)
     */
    public function publishBatch()
    {
        $spk_list = isset($_POST['spks']) ? $_POST['spks'] : array();
        if (empty($spk_list) || !is_array($spk_list)) {
            echo json_encode(array("status" => 0, "message" => "Daftar SPK untuk diproses kosong."));
            exit();
        }

        $success_count = 0;
        $failed_count = 0;
        $results = array();

        foreach ($spk_list as $no_spk) {
            $no_spk = trim($no_spk);
            if ($no_spk === "") continue;

            $_REQUEST['spk'] = $no_spk;
            ob_start();
            $this->publishSingle();
            $out = ob_get_clean();
            $res = @json_decode($out, true);

            if (isset($res['status']) && $res['status'] == 1) {
                $success_count++;
                $results[$no_spk] = array("status" => 1, "message" => $res['message']);
            } else {
                $failed_count++;
                $results[$no_spk] = array("status" => 0, "message" => isset($res['message']) ? $res['message'] : "Gagal");
            }
        }

        echo json_encode(array(
            "status"        => ($success_count > 0) ? 1 : 0,
            "success_count" => $success_count,
            "failed_count"  => $failed_count,
            "total"         => count($spk_list),
            "results"       => $results,
        ));
        exit();
    }

    /**
     * Endpoint detail diagnosa 1 SPK secara mendalam
     */
    public function detail()
    {
        $no_spk = isset($_GET['spk']) ? trim($_GET['spk']) : "";
        if ($no_spk === "") {
            echo "Parameter ?spk=... tidak boleh kosong.";
            return;
        }

        // 1. Cari di project_tasklist
        $this->db->where("no_spk", $no_spk);
        $this->db->where("status", 1);
        $this->db->where("trash", 0);
        $task = $this->db->get("project_tasklist")->row_array();
        $tipe = "Reguler";

        if (empty($task)) {
            $this->db->where("no_spk", $no_spk);
            $this->db->where("status", 1);
            $this->db->where("trash", 0);
            $task = $this->db->get("project_tasklist_tambahan")->row_array();
            $tipe = "Tambahan";
        }

        if (empty($task)) {
            echo "SPK ($no_spk) tidak ditemukan di database.";
            return;
        }

        // 2. Transaksi 3674r
        $tr_3674r = null;
        if ((int)$task['post_biaya_id'] > 0) {
            $this->db->where("id", (int)$task['post_biaya_id']);
            $tr_3674r = $this->db->get("transaksi")->row_array();
        }

        // 3. Approval 3674
        $tr_approval = null;
        if (!empty($tr_3674r)) {
            $this->db->where("jenis", "3674");
            $this->db->where("trash", 0);
            $all_apps = $this->db->get("transaksi")->result_array();
            foreach ($all_apps as $app) {
                if (!empty($app['ids_his'])) {
                    $his = @unserialize(base64_decode($app['ids_his']));
                    if (is_array($his) && isset($his[1]['trID']) && (int)$his[1]['trID'] === (int)$tr_3674r['id']) {
                        $tr_approval = $app;
                        break;
                    }
                }
            }
        }

        // 4. Payment Source 3675
        $this->db->where("target_jenis", "3675");
        $this->db->where("extern4_nama", $no_spk);
        $ps = $this->db->get("transaksi_payment_source")->result_array();

        // 5. Registry Main 3674r
        $reg_main_3674r = null;
        if (!empty($tr_3674r)) {
            $this->db->where("transaksi_id", $tr_3674r['id']);
            $reg = $this->db->get("transaksi_data_registry")->row_array();
            if (!empty($reg['main'])) {
                $reg_main_3674r = @unserialize(base64_decode($reg['main']));
            }
        }

        // Tampilkan format JSON
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(array(
            "tipe_spk"             => $tipe,
            "spk_tasklist"         => $task,
            "transaksi_3674r"      => $tr_3674r,
            "transaksi_approval"   => $tr_approval,
            "payment_source_3675"  => $ps,
            "has_3675"             => (count($ps) > 0),
            "registry_main_3674r"  => $reg_main_3674r,
        ));
        exit();
    }

    /**
     * Render antarmuka visual (HTML) yang modern, interaktif, dan dilengkapi aksi penerbitan
     */
    private function renderHtmlView($summary, $list, $filters)
    {
        $base = base_url();
        $total_rows = count($list);

        // Bentuk URL export dengan filter aktif
        $filter_params = http_build_query($filters);
        $url_json = $base . "tools/SpkPaymentSource?format=json&" . $filter_params;
        $url_csv  = $base . "tools/SpkPaymentSource?format=csv&" . $filter_params;
        $url_reset= $base . "tools/SpkPaymentSource";

        ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tool Penerbitan Payment Source 3675 - Everest ERP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            padding-bottom: 60px;
        }
        .page-header-box {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 22px 28px;
            margin-bottom: 22px;
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .page-header-box h2 { margin: 0 0 8px 0; font-size: 23px; font-weight: 700; }
        .page-header-box p { margin: 0; opacity: 0.9; font-size: 13.5px; }
        .kpi-card {
            background: #fff;
            border-radius: 6px;
            padding: 16px 18px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 5px solid #ccc;
        }
        .kpi-card.danger { border-left-color: #d9534f; }
        .kpi-card.success { border-left-color: #5cb85c; }
        .kpi-card.warning { border-left-color: #f0ad4e; }
        .kpi-card.info { border-left-color: #5bc0de; }
        .kpi-card .number { font-size: 24px; font-weight: 700; margin: 4px 0; }
        .kpi-card .title { font-size: 11.5px; text-transform: uppercase; color: #777; font-weight: 600; }
        .kpi-card .subtext { font-size: 11px; color: #999; margin-top: 3px; }
        .panel-filter {
            background: #fff;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
            padding: 18px 20px;
            margin-bottom: 22px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }
        .table-container {
            background: #fff;
            border-radius: 6px;
            border: 1px solid #e1e4e8;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .table > thead > tr > th {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            vertical-align: middle;
            border: none;
            padding: 9px 8px;
        }
        .table > tbody > tr > td {
            vertical-align: middle;
            font-size: 12px;
            padding: 8px;
        }
        .badge-missing {
            background-color: #d9534f;
            color: #fff;
            font-size: 10.5px;
            padding: 4px 7px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-exists {
            background-color: #5cb85c;
            color: #fff;
            font-size: 10.5px;
            padding: 4px 7px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-tipe {
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
        }
        .badge-reguler { background-color: #337ab7; color: #fff; }
        .badge-tambahan { background-color: #f0ad4e; color: #fff; }
        .text-spk { font-weight: 700; color: #1e3c72; font-size: 12.5px; }
        .text-rupiah { font-weight: 700; color: #2e7d32; }
        .diagnosa-box {
            font-size: 11px;
            line-height: 1.4;
            color: #555;
            background: #fdf7e7;
            padding: 4px 7px;
            border-left: 3px solid #f0ad4e;
            border-radius: 2px;
        }
        .diagnosa-box.critical {
            background: #fdeeee;
            border-left-color: #d9534f;
            color: #a94442;
        }
        .diagnosa-box.ok {
            background: #eef9ee;
            border-left-color: #5cb85c;
            color: #3c763d;
        }
        .table-hover tbody tr:hover { background-color: #f9fbfd; }
        .btn-publish {
            font-weight: 600;
            font-size: 11px;
            padding: 4px 8px;
        }
    </style>
</head>
<body>

<div class="container-fluid" style="max-width: 1580px; margin-top: 18px;">
    <!-- HEADER -->
    <div class="page-header-box">
        <div class="row">
            <div class="col-md-7">
                <h2><i class="fa fa-money"></i> Tool Penerbitan Payment Source (3675) untuk SPK</h2>
                <p>Mendeteksi SPK pekerjaan vendor yang belum masuk ke modul pembayaran (3675), serta menyediakan tombol untuk menerbitkan payment source secara satuan maupun massal.</p>
            </div>
            <div class="col-md-5 text-right" style="margin-top: 8px;">
                <a href="<?php echo $base; ?>tools/SpkRekonsiliasi" class="btn btn-primary" target="_blank" title="Cek apakah SPK sudah dibayar lewat modul Penerimaan Jasa 463/462"><i class="fa fa-balance-scale"></i> Audit & Rekonsiliasi Pembayaran</a>
                <button type="button" id="btn-batch-publish" class="btn btn-warning" onclick="batchPublishMissing()"><i class="fa fa-bolt"></i> Terbitkan Semua Yang Missing (Batch)</button>
                <a href="<?php echo $url_csv; ?>" class="btn btn-success"><i class="fa fa-download"></i> Export Excel</a>
                <a href="<?php echo $url_json; ?>" target="_blank" class="btn btn-info"><i class="fa fa-code"></i> JSON</a>
                <a href="<?php echo $url_reset; ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
            </div>
        </div>
    </div>

    <!-- ALERT BANNER: REKONSILIASI PEMBAYARAN JASA -->
    <div class="alert alert-warning" style="border-left: 5px solid #8a6d3b; margin-bottom: 20px;">
        <i class="fa fa-exclamation-triangle fa-lg" style="margin-right: 8px;"></i>
        <strong>PERINGATAN AUDIT PEMBAYARAN:</strong> Sebagian besar SPK pekerjaan vendor (seperti <em>CV .JAYA SINAR PERKASA</em>) dioperasikan dan dibayarkan melalui modul <strong>Penerimaan Jasa (463) &rarr; Pembayaran Bank BCA (462)</strong>.
        Sebelum menerbitkan Payment Source 3675, silakan buka <a href="<?php echo $base; ?>tools/SpkRekonsiliasi" target="_blank" class="alert-link"><strong>Tool Rekonsiliasi Pembayaran SPK</strong></a> untuk memastikan SPK tidak dibayarkan dua kali (<em>Double Payment</em>).
    </div>

    <!-- METRICS CARDS -->
    <div class="row">
        <div class="col-md-3">
            <div class="kpi-card danger">
                <div class="title">SPK 3675 Missing (Belum Terbit)</div>
                <div class="number text-danger"><?php echo number_format($summary['missing_3675'], 0, ',', '.'); ?> SPK</div>
                <div class="subtext">Belum ada di modul pembayaran 3675</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card danger">
                <div class="title">Total Nominal Upah Tertahan</div>
                <div class="number text-danger">Rp <?php echo number_format($summary['total_nilai_missing'], 0, ',', '.'); ?></div>
                <div class="subtext">Estimasi tagihan vendor yang belum terbit</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card warning">
                <div class="title">3674r Approved, 3675 Skip</div>
                <div class="number text-warning"><?php echo number_format($summary['cat_approved_skip'], 0, ',', '.'); ?> SPK</div>
                <div class="subtext">Siap diterbitkan langsung ke payment source</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card info">
                <div class="title">QC Selesai, 3674r Belum Terbit</div>
                <div class="number text-info"><?php echo number_format($summary['cat_no_post_biaya'], 0, ',', '.'); ?> SPK</div>
                <div class="subtext">Diproses lewat pemicu AutoPostingBiaya</div>
            </div>
        </div>
    </div>

    <!-- FILTER PANEL -->
    <div class="panel-filter">
        <form method="GET" action="<?php echo $base; ?>tools/SpkPaymentSource" class="form-horizontal">
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label" style="text-align: left; font-size: 11px;">CARI NOMOR SPK</label>
                    <input type="text" name="spk" class="form-control input-sm" placeholder="Contoh: 497 atau SPK-INT" value="<?php echo htmlspecialchars($filters['spk']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="control-label" style="text-align: left; font-size: 11px;">NAMA VENDOR / PELAKSANA</label>
                    <input type="text" name="vendor" class="form-control input-sm" placeholder="Contoh: JAYA SINAR" value="<?php echo htmlspecialchars($filters['vendor']); ?>">
                </div>
                <div class="col-md-2">
                    <label class="control-label" style="text-align: left; font-size: 11px;">TIPE SPK</label>
                    <select name="tipe" class="form-control input-sm">
                        <option value="all" <?php echo $filters['tipe'] === 'all' ? 'selected' : ''; ?>>Semua Tipe SPK</option>
                        <option value="reguler" <?php echo $filters['tipe'] === 'reguler' ? 'selected' : ''; ?>>SPK Reguler (SPK-INT)</option>
                        <option value="tambahan" <?php echo $filters['tipe'] === 'tambahan' ? 'selected' : ''; ?>>SPK Tambahan (SPK-TMB)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label" style="text-align: left; font-size: 11px;">STATUS 3675</label>
                    <select name="status_3675" class="form-control input-sm">
                        <option value="missing" <?php echo $filters['status_3675'] === 'missing' ? 'selected' : ''; ?>>Hanya Missing (Belum Terbit)</option>
                        <option value="exists" <?php echo $filters['status_3675'] === 'exists' ? 'selected' : ''; ?>>Hanya Yang Sudah Terbit</option>
                        <option value="all" <?php echo $filters['status_3675'] === 'all' ? 'selected' : ''; ?>>Semua Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="control-label" style="text-align: left; font-size: 11px;">KATEGORI DIAGNOSA</label>
                    <select name="kategori" class="form-control input-sm">
                        <option value="all" <?php echo $filters['kategori'] === 'all' ? 'selected' : ''; ?>>Semua Kategori</option>
                        <option value="approved_skip" <?php echo $filters['kategori'] === 'approved_skip' ? 'selected' : ''; ?>>3674r Approved tapi 3675 Skip</option>
                        <option value="no_post_biaya" <?php echo $filters['kategori'] === 'no_post_biaya' ? 'selected' : ''; ?>>QC Selesai tapi 3674r Belum Ada</option>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-3">
                    <label class="control-label" style="text-align: left; font-size: 11px;">TIPE PELAKSANA</label>
                    <select name="pelaksana" class="form-control input-sm">
                        <option value="vendor" <?php echo $filters['pelaksana'] === 'vendor' ? 'selected' : ''; ?>>Vendor Saja (type=22)</option>
                        <option value="internal" <?php echo $filters['pelaksana'] === 'internal' ? 'selected' : ''; ?>>Internal Saja (type=11)</option>
                        <option value="all" <?php echo $filters['pelaksana'] === 'all' ? 'selected' : ''; ?>>Semua Pelaksana</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="control-label" style="text-align: left; font-size: 11px;">STATUS QC</label>
                    <select name="progress" class="form-control input-sm">
                        <option value="qc_selesai" <?php echo $filters['progress'] === 'qc_selesai' ? 'selected' : ''; ?>>Hanya QC Selesai (progress_id=3)</option>
                        <option value="all" <?php echo $filters['progress'] === 'all' ? 'selected' : ''; ?>>Semua Status Progress</option>
                    </select>
                </div>
                <div class="col-md-6 text-right" style="margin-top: 23px;">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Terapkan Filter</button>
                    <a href="<?php echo $url_reset; ?>" class="btn btn-default btn-sm"><i class="fa fa-undo"></i> Reset Filter</a>
                </div>
            </div>
        </form>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <div class="table-container">
        <div class="row" style="margin-bottom: 12px;">
            <div class="col-md-6">
                <span style="font-weight: 700; font-size: 14.5px;">
                    Daftar SPK: <span class="text-primary"><?php echo $total_rows; ?> data ditemukan</span>
                </span>
            </div>
            <div class="col-md-6 text-right text-muted" style="font-size: 12px;">
                * Klik tombol hijau <b>Terbitkan 3675</b> untuk memproses SPK ke modul pembayaran
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="table-spk">
                <thead>
                    <tr>
                        <th style="width: 35px;" class="text-center">No</th>
                        <th style="width: 70px;" class="text-center">Tipe</th>
                        <th style="width: 210px;">Nomor SPK</th>
                        <th style="width: 160px;">Pelaksana / Vendor</th>
                        <th style="width: 170px;">Proyek & Konsumen</th>
                        <th style="width: 110px;" class="text-right">Upah SPK</th>
                        <th style="width: 125px;">Status QC</th>
                        <th style="width: 130px;">Dokumen 3674r</th>
                        <th style="width: 125px;">Approval 3674</th>
                        <th style="width: 120px;" class="text-center">Status 3675</th>
                        <th>Diagnosa & Keterangan</th>
                        <th style="width: 145px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_rows === 0): ?>
                        <tr>
                            <td colspan="12" class="text-center" style="padding: 40px; color: #888;">
                                <i class="fa fa-check-circle text-success" style="font-size: 32px;"></i><br>
                                <b style="font-size: 15px; margin-top: 10px; display: inline-block;">Tidak ada data SPK yang sesuai dengan kriteria filter saat ini.</b>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $nomer_urut = 0; ?>
                        <?php foreach ($list as $row): ?>
                            <?php 
                                $nomer_urut++; 
                                $is_missing = !$row['has_3675'];
                                $badge_tipe = ($row['tipe_spk'] === 'Reguler') ? 'badge-reguler' : 'badge-tambahan';
                                $box_class = $is_missing ? ($row['kategori'] === 'approved_skip' ? 'critical' : '') : 'ok';
                                $row_id = "row-spk-" . $row['id'];
                            ?>
                            <tr id="<?php echo $row_id; ?>" data-spk="<?php echo htmlspecialchars($row['no_spk']); ?>" data-missing="<?php echo $is_missing ? '1' : '0'; ?>">
                                <td class="text-center"><?php echo $nomer_urut; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-tipe <?php echo $badge_tipe; ?>"><?php echo $row['tipe_spk']; ?></span>
                                </td>
                                <td>
                                    <span class="text-spk"><?php echo htmlspecialchars($row['no_spk']); ?></span><br>
                                    <small class="text-muted">Paket: <?php echo htmlspecialchars($row['produk_paket_nama']); ?></small>
                                </td>
                                <td>
                                    <b><?php echo htmlspecialchars($row['employee_nama']); ?></b><br>
                                    <small class="text-muted">ID: <?php echo $row['employee_id']; ?> (<?php echo ($row['type_pelaksana'] == 22 ? 'Vendor' : 'Internal'); ?>)</small>
                                </td>
                                <td>
                                    <b><?php echo htmlspecialchars(!empty($row['project_nama']) ? $row['project_nama'] : $row['produk_nama']); ?></b><br>
                                    <small class="text-muted">Unit: <?php echo htmlspecialchars($row['produk_nama']); ?><?php echo !empty($row['owner_nama']) ? ' | ' . htmlspecialchars($row['owner_nama']) : ''; ?></small>
                                </td>
                                <td class="text-right">
                                    <span class="text-rupiah">Rp <?php echo number_format($row['nilai_upah'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <span class="text-success"><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($row['progress_nama']); ?></span><br>
                                    <small class="text-muted"><?php echo !empty($row['qc_dtime']) ? substr($row['qc_dtime'], 0, 10) : '-'; ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($row['post_biaya_no'])): ?>
                                        <b class="text-primary"><?php echo htmlspecialchars($row['post_biaya_no']); ?></b><br>
                                        <small class="text-muted">ID: <?php echo $row['post_biaya_id']; ?></small>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fa fa-times-circle"></i> Belum Dibuat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['has_approval']): ?>
                                        <b class="text-success"><?php echo htmlspecialchars($row['approval_data']['approval_nomer']); ?></b><br>
                                        <small class="text-muted"><?php echo substr($row['approval_data']['approval_dtime'], 0, 10); ?></small>
                                    <?php elseif (!empty($row['post_biaya_no'])): ?>
                                        <span class="text-warning"><i class="fa fa-clock-o"></i> Menunggu Approval</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center status-col">
                                    <?php if ($is_missing): ?>
                                        <span class="badge-missing"><i class="fa fa-exclamation-triangle"></i> MISSING</span>
                                    <?php else: ?>
                                        <span class="badge-exists"><i class="fa fa-check"></i> SUDAH TERBIT</span><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['ps_data']['nomer']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="diagnosa-box <?php echo $box_class; ?>">
                                        <b><?php echo htmlspecialchars($row['diagnosa_txt']); ?></b><br>
                                        <span class="text-muted">Solusi: <?php echo htmlspecialchars($row['solusi_txt']); ?></span>
                                    </div>
                                </td>
                                <td class="text-center action-col">
                                    <?php if ($is_missing): ?>
                                        <button type="button" class="btn btn-success btn-xs btn-publish" onclick="publishPaymentSource('<?php echo htmlspecialchars(addslashes($row['no_spk'])); ?>', '<?php echo $row_id; ?>')">
                                            <i class="fa fa-plus-circle"></i> Terbitkan 3675
                                        </button>
                                    <?php else: ?>
                                        <span class="text-success" style="font-size: 11px;"><i class="fa fa-check-circle"></i> Selesai</span>
                                    <?php endif; ?>
                                    <a href="<?php echo $base; ?>tools/SpkPaymentSource/detail?spk=<?php echo urlencode($row['no_spk']); ?>" target="_blank" class="btn btn-default btn-xs" title="Lihat JSON Jejak Database">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var publishUrl = "<?php echo $base; ?>tools/SpkPaymentSource/publishSingle";
var batchPublishUrl = "<?php echo $base; ?>tools/SpkPaymentSource/publishBatch";

function publishPaymentSource(spkNo, rowId) {
    if (!confirm("Apakah Anda yakin ingin menerbitkan Payment Source 3675 untuk SPK:\n" + spkNo + " ?")) {
        return;
    }

    var $row = $("#" + rowId);
    var $btn = $row.find(".btn-publish");
    var origHtml = $btn.html();
    $btn.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Proses...");

    $.ajax({
        url: publishUrl,
        type: "POST",
        dataType: "json",
        data: { spk: spkNo },
        success: function(res) {
            if (res.status == 1) {
                alert(res.message);
                $row.find(".status-col").html("<span class='badge-exists'><i class='fa fa-check'></i> SUDAH TERBIT</span><br><small class='text-muted'>" + (res.nomer_ps || '3674') + "</small>");
                $row.find(".action-col").html("<span class='text-success' style='font-size:11px;'><i class='fa fa-check-circle'></i> Selesai</span> <a href='<?php echo $base; ?>tools/SpkPaymentSource/detail?spk=" + encodeURIComponent(spkNo) + "' target='_blank' class='btn btn-default btn-xs'><i class='fa fa-search'></i></a>");
                $row.attr("data-missing", "0");
                $row.find(".diagnosa-box").removeClass("critical").addClass("ok").html("<b>Payment Source 3675 berhasil diterbitkan!</b><br><span class='text-muted'>Tagihan siap dibayar di modul 3675.</span>");
            } else {
                alert("Gagal: " + res.message);
                $btn.prop("disabled", false).html(origHtml);
            }
        },
        error: function(xhr, status, error) {
            alert("Error saat memproses SPK: " + error);
            $btn.prop("disabled", false).html(origHtml);
        }
    });
}

function batchPublishMissing() {
    var spks = [];
    $("#table-spk tbody tr[data-missing='1']").each(function() {
        var spk = $(this).attr("data-spk");
        if (spk) {
            spks.push(spk);
        }
    });

    if (spks.length === 0) {
        alert("Tidak ada SPK dengan status MISSING pada tabel filter saat ini.");
        return;
    }

    if (!confirm("Peringatan: Anda akan menerbitkan Payment Source 3675 secara massal untuk " + spks.length + " SPK sekaligus.\n\nLanjutkan?")) {
        return;
    }

    var $btnBatch = $("#btn-batch-publish");
    var origBatchHtml = $btnBatch.html();
    $btnBatch.prop("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Memproses " + spks.length + " SPK...");

    $.ajax({
        url: batchPublishUrl,
        type: "POST",
        dataType: "json",
        data: { spks: spks },
        success: function(res) {
            $btnBatch.prop("disabled", false).html(origBatchHtml);
            alert("Selesai diproses!\n\nBerhasil: " + res.success_count + " SPK\nGagal: " + res.failed_count + " SPK\n\nHalaman akan dimuat ulang untuk memperbarui status.");
            location.reload();
        },
        error: function(xhr, status, error) {
            $btnBatch.prop("disabled", false).html(origBatchHtml);
            alert("Error batch processing: " + error);
        }
    });
}
</script>

</body>
</html>
        <?php
    }
}
// END OF COMPLETE REPEATED LOGIC
