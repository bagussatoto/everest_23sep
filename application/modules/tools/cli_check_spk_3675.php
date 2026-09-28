<?php

// START OF COMPLETE REPEATED LOGIC
/**
 * CLI Tool: Audit & Penerbitan SPK yang Payment Source (3675) Belum Terbit
 *
 * Penggunaan via Terminal / CMD:
 *   1. Tampilkan daftar SPK missing:
 *      C:\xampp\php\php.exe application/modules/tools/cli_check_spk_3675.php
 *   2. Filter SPK tertentu:
 *      C:\xampp\php\php.exe application/modules/tools/cli_check_spk_3675.php --spk=497
 *   3. Filter vendor:
 *      C:\xampp\php\php.exe application/modules/tools/cli_check_spk_3675.php --vendor="JAYA SINAR"
 *   4. Terbitkan Payment Source 3675 untuk SPK tertentu:
 *      C:\xampp\php\php.exe application/modules/tools/cli_check_spk_3675.php --publish="497/SPK-INT/108/979/497/VI/2026"
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// Ambil konfigurasi database CodeIgniter
define('BASEPATH', true);
$db_file = dirname(dirname(dirname(__FILE__))) . '/config/database.php';
if (!file_exists($db_file)) {
    die("Error: File database config tidak ditemukan di $db_file\n");
}
require_once $db_file;

$active_db = isset($db[$active_group]) ? $db[$active_group] : $db['default'];
$mysqli = new mysqli(
    $active_db['hostname'],
    $active_db['username'],
    $active_db['password'],
    $active_db['database']
);

if ($mysqli->connect_errno) {
    die("Koneksi database gagal: " . $mysqli->connect_error . "\n");
}

// Parsing opsi argumen CLI
$options = getopt("", array("spk:", "vendor:", "tipe:", "status:", "kategori:", "format:", "publish:"));
$filter_spk      = isset($options['spk']) ? trim($options['spk']) : "";
$filter_vendor   = isset($options['vendor']) ? trim($options['vendor']) : "";
$filter_tipe     = isset($options['tipe']) ? trim($options['tipe']) : "all"; // all, reguler, tambahan
$filter_status   = isset($options['status']) ? trim($options['status']) : "missing"; // missing, exists, all
$filter_kategori = isset($options['kategori']) ? trim($options['kategori']) : "all";
$format          = isset($options['format']) ? trim($options['format']) : "text"; // text, json
$publish_spk     = isset($options['publish']) ? trim($options['publish']) : "";

// EKSEKUSI PENERBITAN VIA CLI
if ($publish_spk !== "") {
    echo "================================================================================\n";
    echo "   MEMPROSES PENERBITAN PAYMENT SOURCE 3675 UNTUK SPK: $publish_spk\n";
    echo "================================================================================\n";

    // 1. Cari SPK
    $res_spk = $mysqli->query("SELECT * FROM project_tasklist WHERE no_spk = '" . $mysqli->real_escape_string($publish_spk) . "' AND status = 1 AND trash = 0");
    $spk = $res_spk->fetch_assoc();
    $tipe = "Reguler";

    if (!$spk) {
        $res_spk = $mysqli->query("SELECT * FROM project_tasklist_tambahan WHERE no_spk = '" . $mysqli->real_escape_string($publish_spk) . "' AND status = 1 AND trash = 0");
        $spk = $res_spk->fetch_assoc();
        $tipe = "Tambahan";
    }

    if (!$spk) {
        die("Error: SPK [$publish_spk] tidak ditemukan di database aktif.\n");
    }

    // 2. Cek apakah sudah ada 3675
    $res_chk = $mysqli->query("SELECT id, nomer FROM transaksi_payment_source WHERE target_jenis = '3675' AND extern4_nama = '" . $mysqli->real_escape_string($publish_spk) . "'");
    $chk_ps = $res_chk->fetch_assoc();
    if ($chk_ps) {
        die("Info: Payment source 3675 untuk SPK [$publish_spk] sudah ada sebelumnya (ID: {$chk_ps['id']}, Nomer: {$chk_ps['nomer']}).\n");
    }

    $p_biaya_id = (int)$spk['post_biaya_id'];
    if ($p_biaya_id > 0) {
        // Ambil data transaksi 3674r
        $res_tr = $mysqli->query("SELECT * FROM transaksi WHERE id = $p_biaya_id");
        $tr_req = $res_tr->fetch_assoc();
        if (!$tr_req) {
            die("Error: Transaksi request 3674r (ID: $p_biaya_id) tidak ditemukan.\n");
        }

        // Cari transaksi approval 3674
        $res_app = $mysqli->query("SELECT id, nomer, jenis, dtime, ids_his, oleh_id, oleh_nama FROM transaksi WHERE jenis = '3674' AND trash = 0");
        $tr_app = null;
        while ($r = $res_app->fetch_assoc()) {
            if (!empty($r['ids_his'])) {
                $his = @unserialize(base64_decode($r['ids_his']));
                if (is_array($his) && isset($his[1]['trID']) && (int)$his[1]['trID'] === $p_biaya_id) {
                    $tr_app = $r;
                    break;
                }
            }
        }

        if (!$tr_app) {
            die("Error: Transaksi 3674r (ID: $p_biaya_id) belum diapprove menjadi 3674. Approval diperlukan sebelum payment source diterbitkan.\n");
        }

        $nilai_upah = (float)$tr_req['transaksi_nilai'];
        if ($nilai_upah <= 0) {
            $nilai_upah = (float)$spk['nilai_sub_fase'];
        }

        // START OF COMPLETE REPEATED LOGIC
        // Susun data payment source
        $dtime_now = date("Y-m-d H:i:s");
        $date_now  = date("Y-m-d");
        $oleh_id   = !empty($tr_app['oleh_id']) ? $tr_app['oleh_id'] : "979";
        $oleh_nama = !empty($tr_app['oleh_nama']) ? $mysqli->real_escape_string($tr_app['oleh_nama']) : "Zahra by system";
        $vendor_id = $spk['employee_id'];
        $vendor_nm = $mysqli->real_escape_string($spk['employee_nama']);
        $proj_id   = $spk['produk_id'];
        $proj_nm   = !empty($spk['nama']) ? $mysqli->real_escape_string($spk['nama']) : "";
        if ($proj_nm === "" && !empty($proj_id)) {
            $res_pp = $mysqli->query("SELECT nama FROM project_produk WHERE id = " . (int)$proj_id);
            if ($res_pp && $row_pp = $res_pp->fetch_assoc()) {
                $proj_nm = $mysqli->real_escape_string($row_pp['nama']);
            }
        }
        if ($proj_nm === "") {
            $proj_nm = $mysqli->real_escape_string($spk['produk_nama']);
        }
        $wo_nm     = $mysqli->real_escape_string($spk['produk_nama']);
        $owner_nm  = $mysqli->real_escape_string($spk['owner_nama']);
        $app_nomer = $mysqli->real_escape_string($tr_app['nomer']);
        $app_id    = (int)$tr_app['id'];
        $spk_id    = (int)$spk['id'];
        $spk_escaped = $mysqli->real_escape_string($publish_spk);

        $sql_insert = "INSERT INTO transaksi_payment_source (
            jenis, target_jenis, reference_jenis, transaksi_id, extern_id, extern_nama,
            nomer, label, tagihan, terbayar, sisa, cabang_id, cabang_nama, oleh_id, oleh_nama,
            dtime, fulldate, project_id, project_nama, extern2_id, extern2_nama, extern3_id,
            extern3_nama, extern4_nama, extern5_id, extern5_nama, customers_id, customers_nama
        ) VALUES (
            '3674', '3675', '3674', $app_id, '$vendor_id', '$vendor_nm',
            '$app_nomer', 'budget project', $nilai_upah, 0, $nilai_upah, -1, 'pusat', '$oleh_id', '$oleh_nama',
            '$dtime_now', '$date_now', '$proj_id', '$proj_nm', '.1', '.dipotong', '$spk_id',
            '$wo_nm', '$spk_escaped', -1, 'pusat', 0, '$owner_nm'
        )";
        // END OF COMPLETE REPEATED LOGIC

        $mysqli->query("START TRANSACTION");
        $ins = $mysqli->query($sql_insert);
        if (!$ins) {
            $mysqli->query("ROLLBACK");
            die("Error insert: " . $mysqli->error . "\n");
        }
        $new_ps_id = $mysqli->insert_id;

        // Update registry main
        foreach (array($p_biaya_id, $app_id) as $upd_tr_id) {
            $res_reg = $mysqli->query("SELECT main FROM transaksi_data_registry WHERE transaksi_id = $upd_tr_id");
            $row_reg = $res_reg->fetch_assoc();
            if ($row_reg && !empty($row_reg['main'])) {
                $main_arr = @unserialize(base64_decode($row_reg['main']));
                if (is_array($main_arr)) {
                    $main_arr['piutang_tambah'] = $nilai_upah;
                    $main_arr['biaya_tambahan'] = ($tipe === "Tambahan") ? $nilai_upah : 0;
                    $upd_blob = $mysqli->real_escape_string(base64_encode(serialize($main_arr)));
                    $mysqli->query("UPDATE transaksi_data_registry SET main = '$upd_blob' WHERE transaksi_id = $upd_tr_id");
                }
            }
        }

        $mysqli->query("COMMIT");
        echo "SUKSES: Payment Source 3675 berhasil diterbitkan!\n";
        echo "  - Payment Source ID : $new_ps_id\n";
        echo "  - Transaksi ID (3674): $app_id\n";
        echo "  - Nomer Nota        : $app_nomer\n";
        echo "  - Tagihan Upah (Rp) : " . number_format($nilai_upah, 0, ',', '.') . "\n";
        echo "  - Vendor            : " . $spk['employee_nama'] . "\n";
        echo "  - SPK               : $publish_spk\n";
        exit(0);
    } else {
        die("Peringatan: SPK [$publish_spk] belum memiliki post_biaya_id. Silahkan posting biaya terlebih dahulu via Web Tool atau QC.\n");
    }
}

// 1. Ambil seluruh data 3675 di transaksi_payment_source
$q_ps = $mysqli->query("SELECT id, jenis, target_jenis, extern4_nama, tagihan, sisa, nomer, dtime FROM transaksi_payment_source WHERE target_jenis = '3675'");
$map_ps = array();
while ($row = $q_ps->fetch_assoc()) {
    $k = trim($row['extern4_nama']);
    if ($k !== "") {
        $map_ps[$k] = $row;
    }
}

// 2. Ambil seluruh transaksi approval 3674
$q_app = $mysqli->query("SELECT id, nomer, jenis, dtime, ids_his FROM transaksi WHERE jenis = '3674' AND trash = 0");
$map_app = array();
while ($row = $q_app->fetch_assoc()) {
    if (!empty($row['ids_his'])) {
        $his = @unserialize(base64_decode($row['ids_his']));
        if (is_array($his) && isset($his[1]['trID'])) {
            $map_app[(int)$his[1]['trID']] = array(
                "approval_id"    => $row['id'],
                "approval_nomer" => $row['nomer'],
                "approval_dtime" => $row['dtime'],
            );
        }
    }
}

// 3. Tentukan tabel yang diperiksa
$target_tables = array();
if ($filter_tipe === "all" || $filter_tipe === "reguler") {
    $target_tables["Reguler"] = "project_tasklist";
}
if ($filter_tipe === "all" || $filter_tipe === "tambahan") {
    $target_tables["Tambahan"] = "project_tasklist_tambahan";
}

$summary = array(
    "total_spk"           => 0,
    "has_3675"            => 0,
    "missing_3675"        => 0,
    "cat_approved_skip"   => 0,
    "cat_not_approved"    => 0,
    "cat_no_post_biaya"   => 0,
    "cat_not_qc"          => 0,
    "total_nilai_missing" => 0,
);

$list_output = array();

foreach ($target_tables as $tipe_label => $tbl) {
    $sql = "SELECT pt.id, pt.no_spk, pt.employee_id, pt.employee_nama, pt.type_pelaksana, "
        . "pt.produk_id, pt.produk_nama, pt.owner_nama, pt.produk_paket_nama, "
        . "pt.progress_id, pt.progress_nama, pt.qc_dtime, pt.qc_auth_nama, "
        . "pt.post_biaya_id, pt.post_biaya_no, pt.nilai_sub_fase, "
        . "(SELECT t.transaksi_nilai FROM transaksi t WHERE t.id = pt.post_biaya_id LIMIT 1) as nilai_3674r "
        . "FROM $tbl pt "
        . "WHERE pt.type_pelaksana = 22 AND pt.status = 1 AND pt.trash = 0 AND pt.progress_id = 3 ";

    if ($filter_spk !== "") {
        $sql .= "AND pt.no_spk LIKE '%" . $mysqli->real_escape_string($filter_spk) . "%' ";
    }
    if ($filter_vendor !== "") {
        $sql .= "AND pt.employee_nama LIKE '%" . $mysqli->real_escape_string($filter_vendor) . "%' ";
    }

    $sql .= "ORDER BY pt.id DESC";

    $res = $mysqli->query($sql);
    while ($row = $res->fetch_assoc()) {
        $summary['total_spk']++;
        $no_spk = trim($row['no_spk']);
        $has_3675 = isset($map_ps[$no_spk]);

        $p_id = (int)$row['post_biaya_id'];
        $has_app = ($p_id > 0 && isset($map_app[$p_id]));
        $app_info = $has_app ? $map_app[$p_id] : null;

        $nilai = ($row['nilai_3674r'] > 0) ? (float)$row['nilai_3674r'] : (float)$row['nilai_sub_fase'];

        $kategori = "";
        $diagnosa = "";

        if ($has_3675) {
            $summary['has_3675']++;
            $kategori = "exists";
            $diagnosa = "Payment Source 3675 SUDAH TERBIT";
        } else {
            $summary['missing_3675']++;
            $summary['total_nilai_missing'] += $nilai;

            if ($p_id > 0 && $has_app) {
                $kategori = "approved_skip";
                $summary['cat_approved_skip']++;
                $diagnosa = "3674r & Approval ADA, tapi 3675 TIDAK DIBUAT (piutang_tambah=0)";
            } elseif ($p_id > 0 && !$has_app) {
                $kategori = "not_approved";
                $summary['cat_not_approved']++;
                $diagnosa = "3674r ADA tapi BELUM DIAPPROVE (Step 1 Pending)";
            } elseif ($p_id === 0 && (int)$row['progress_id'] === 3) {
                $kategori = "no_post_biaya";
                $summary['cat_no_post_biaya']++;
                $diagnosa = "QC Selesai tapi 3674r BELUM DIBUAT";
            } else {
                $kategori = "not_qc";
                $summary['cat_not_qc']++;
                $diagnosa = "Belum QC Selesai";
            }
        }

        if ($filter_status === "missing" && $has_3675) {
            continue;
        }
        if ($filter_status === "exists" && !$has_3675) {
            continue;
        }
        if ($filter_kategori !== "all" && $kategori !== $filter_kategori) {
            continue;
        }

        $row['tipe_spk']     = $tipe_label;
        $row['has_3675']     = $has_3675;
        $row['ps_data']      = $has_3675 ? $map_ps[$no_spk] : null;
        $row['has_approval'] = $has_app;
        $row['approval_data']= $app_info;
        $row['nilai_upah']   = $nilai;
        $row['kategori']     = $kategori;
        $row['diagnosa']     = $diagnosa;

        $list_output[] = $row;
    }
}

if ($format === "json") {
    echo json_encode(array(
        "summary" => $summary,
        "total"   => count($list_output),
        "data"    => $list_output
    ), JSON_PRETTY_PRINT);
    exit();
}

// Output text CLI format
echo "=========================================================================================================\n";
echo "              TOOL AUDIT SPK: PEMBAYARAN VENDOR (3675) BELUM TERBIT - EVEREST ERP                       \n";
echo "=========================================================================================================\n";
echo "Total SPK Vendor QC Selesai: " . $summary['total_spk'] . "\n";
echo "  - 3675 Sudah Terbit       : " . $summary['has_3675'] . "\n";
echo "  - 3675 MISSING (Belum)    : " . $summary['missing_3675'] . " SPK\n";
echo "  - Total Nominal Tertahan  : Rp " . number_format($summary['total_nilai_missing'], 0, ',', '.') . "\n";
echo "Breakdown Penyebab Missing:\n";
echo "  * Kasus A (3674r Approved, 3675 Skip)  : " . $summary['cat_approved_skip'] . " SPK\n";
echo "  * Kasus B (3674r Ada Belum Diapprove)  : " . $summary['cat_not_approved'] . " SPK\n";
echo "  * Kasus C (QC Selesai, 3674r Belum Ada): " . $summary['cat_no_post_biaya'] . " SPK\n";
echo "=========================================================================================================\n\n";

printf("%-4s | %-8s | %-32s | %-22s | %-14s | %-12s | %-12s | %-8s\n",
    "No", "Tipe", "Nomor SPK", "Vendor", "Nilai (Rp)", "3674r", "Approval", "Status 3675");
echo str_repeat("-", 125) . "\n";

$no = 0;
foreach ($list_output as $item) {
    $no++;
    $status_str = $item['has_3675'] ? "[OK]" : "[MISSING]";
    $post_no = !empty($item['post_biaya_no']) ? $item['post_biaya_no'] : "BELUM ADA";
    $app_no = $item['has_approval'] ? $item['approval_data']['approval_nomer'] : "-";

    printf("%-4d | %-8s | %-32s | %-22s | %14s | %-12s | %-12s | %-8s\n",
        $no,
        $item['tipe_spk'],
        substr($item['no_spk'], 0, 32),
        substr($item['employee_nama'], 0, 22),
        number_format($item['nilai_upah'], 0, ',', '.'),
        substr($post_no, 0, 12),
        substr($app_no, 0, 12),
        $status_str
    );
}

echo str_repeat("-", 125) . "\n";
echo "Total data yang ditampilkan: " . count($list_output) . " SPK\n";
echo "Petunjuk: Untuk menerbitkan 3675 via CLI, jalankan dengan parameter: --publish=\"<NOMOR_SPK>\"\n";

// END OF COMPLETE REPEATED LOGIC
