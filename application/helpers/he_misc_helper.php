<?php
/**
 * [AGENT_LOG]
 * ROLE      : Software Engineer Agent
 * PURPOSE   : Modifikasi is_silent_or_json_request dan matiDisini agar kompatibel dengan AJAX/JSON request.
 * COMPLIANCE: Standar keandalan output API/AJAX.
 * LOG_EXPIRE: 2026-11-07
 * [/AGENT_LOG]
 */
function str_clean_chars($string)
{
    $str = htmlspecialchars($string, ENT_QUOTES);
    $str = trim($str);

    return $str;
}

function validate_cabang_transaksi($cCode, $configUi, $jenisTr)
{
    if (isset($configUi[$jenisTr]["cabangValidator"]["enabled"]) && ($configUi[$jenisTr]["cabangValidator"]["enabled"] == true)) {

        $tableInCabang = isset($_SESSION[$cCode]["tableIn_master"]["cabang_id"]) ? $_SESSION[$cCode]["tableIn_master"]["cabang_id"] : null;
        $mainPlaceID = isset($_SESSION[$cCode]["main"]["placeID"]) ? $_SESSION[$cCode]["main"]["placeID"] : null;
        $myCabang = my_cabang_id();

        $isInvalid = false;
        if ($tableInCabang !== null && $tableInCabang != CB_ID_PUSAT) {
            $isInvalid = true;
        }
        elseif ($mainPlaceID !== null && $mainPlaceID != CB_ID_PUSAT) {
            $isInvalid = true;
        }
        elseif ($myCabang != CB_ID_PUSAT) {
            $isInvalid = true;
        }

        if ($isInvalid) {
            $userCabangNama = my_cabang_nama() ? my_cabang_nama() : 'Cabang Tidak Dikenal';
            $label_warning = $configUi[$jenisTr]["cabangValidator"]["label"];
            $pesanDetail = "<br><br><span style='color:red;'>Anda terdeteksi login di " . $userCabangNama . "</span>. Apakah anda menggunakan 2 tab atau lebih? Silahkan relogin dengan akun yang sesuai.";

            $arrAlert = array(
                "type" => "error",
                "title" => "Transaction rejected",
                "html" => $label_warning . $pesanDetail,
                "timer" => "5000",
                "showConfirmButton" => true,
                "allowOutsideClick" => false,
            );

            $targetRedirect = base_url();
            echo swalAlert($arrAlert);
            echo "<script>";
            echo "setTimeout(function(){ top.location.href = '" . $targetRedirect . "'; }, 5000);";
            echo "top.swal.close = function(){ top.location.href = '" . $targetRedirect . "'; };";
            echo "</script>";
            die();
        }
    }
}

function validate_cabang_detection($configUi, $jenisTr)
{
    $tableInCabang = "";
    $mainPlaceID = "";
    $myCabang = my_cabang_id();

    $isInvalid = false;
    $tipeHalaman = "";
    if (isset($configUi[$jenisTr]["place"])) {
        if ($configUi[$jenisTr]["place"] == "branch" && $myCabang == CB_ID_PUSAT) {
            $isInvalid = true;
            $tipeHalaman = "branch";
        }
        elseif (($configUi[$jenisTr]["place"] == "center") && $myCabang != CB_ID_PUSAT) {
            $isInvalid = true;
            $tipeHalaman = "center";
        }
    }

    if ($isInvalid) {
        $userCabangNama = my_cabang_nama() ? my_cabang_nama() : 'Cabang Tidak Dikenal';
        $targetRedirect = base_url();
        $cdn = function_exists('cdn_suport') ? cdn_suport() : "https://cdn.mayagrahakencana.com/assets/suport/";

        if ($tipeHalaman == "center") {
            $judul = "Halaman Ini Khusus Kantor Pusat";
            $pesanAlasan = "Saat ini Anda sedang masuk menggunakan akun <b>" . $userCabangNama . "</b>.<br>Halaman yang Anda buka ini hanya bisa diakses oleh akun <b>KANTOR PUSAT</b>.";
            $panduan1 = "Jika Anda staf Cabang: Silakan buka menu <b>Pettycash Cabang</b>.";
            $panduan2 = "Jika Anda staf Pusat: Silakan <b>Keluar (Logout)</b> lalu masuk kembali dengan akun Pusat Anda.";
        }
        else {
            $judul = "Halaman Ini Khusus Cabang";
            $pesanAlasan = "Saat ini Anda sedang masuk menggunakan akun <b>" . $userCabangNama . "</b>.<br>Halaman yang Anda buka ini hanya bisa diakses oleh akun <b>CABANG</b>.";
            $panduan1 = "Jika Anda staf Pusat: Silakan buka menu <b>Pettycash Pusat</b>.";
            $panduan2 = "Jika Anda staf Cabang: Silakan <b>Keluar (Logout)</b> lalu masuk kembali dengan akun Cabang Anda.";
        }

        echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <!-- Fail-safe redirect jika JS mati/error -->
    <meta http-equiv='refresh' content='5;url=" . $targetRedirect . "'>
    <title>" . $judul . "</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <!-- CSS Bootstrap & SweetAlert2 lokal/CDN -->
    <link rel='stylesheet' href='" . $cdn . "adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css'>
    <link rel='stylesheet' href='" . $cdn . "sweetalert2-master/dist/sweetalert2.min.css'>
    <style>
        body { background-color: #f4f6f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        .reject-container { margin-top: 8%; }
        .card-reject { background: #fff; border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); padding: 30px; text-align: center; }
        .alert-box-info { background-color: #fcf8e3; border: 1px solid #faebcc; border-radius: 6px; padding: 15px; margin: 20px 0; color: #8a6d3b; font-size: 14px; text-align: left; line-height: 1.6; }
    </style>
</head>
<body>
    <!-- Fallback visual jika popup belum/tidak muncul -->
    <div class='container reject-container'>
        <div class='row'>
            <div class='col-md-6 col-md-offset-3 card-reject'>
                <div style='font-size: 44px; margin-bottom: 5px;'>✋</div>
                <h3 style='font-weight:bold; color: #d9534f; margin-top: 5px;'>" . $judul . "</h3>
                <div class='alert-box-info'>
                    <p style='margin-bottom: 10px;'>" . $pesanAlasan . "</p>
                    <b>Yang perlu Anda lakukan:</b>
                    <ol style='margin-bottom: 0; padding-left: 20px;'>
                        <li>" . $panduan1 . "</li>
                        <li>" . $panduan2 . "</li>
                    </ol>
                </div>
                <p class='text-muted' style='font-size: 13px; margin-bottom: 15px;'>Halaman otomatis kembali ke menu utama dalam <b><span id='timerDetik'>5</span> detik</b>...</p>
                <a href='" . $targetRedirect . "' class='btn btn-danger btn-block btn-lg' style='font-weight: bold; border-radius: 5px;'>Kembali ke Menu Utama</a>
            </div>
        </div>
    </div>

    <!-- JS Lokal/CDN -->
    <script src='" . $cdn . "adminlte/bower_components/jquery/dist/jquery.min.js'></script>
    <script src='" . $cdn . "sweetalert2-master/dist/sweetalert2.min.js'></script>
    <script>
        var detikSisa = 5;
        var countdownTimer = setInterval(function(){
            detikSisa--;
            var spanTimer = document.getElementById('timerDetik');
            if (spanTimer) {
                spanTimer.innerText = detikSisa;
            }
            if (detikSisa <= 0) {
                clearInterval(countdownTimer);
            }
        }, 1000);

        $(document).ready(function() {
            if (typeof swal === 'function') {
                swal({
                    type: 'warning',
                    title: '" . $judul . "',
                    html: \"<div style='text-align:left; font-size:14px; line-height:1.6;'>\" +
                          \"<p>" . addslashes($pesanAlasan) . "</p>\" +
                          \"<b>Yang perlu Anda lakukan:</b>\" +
                          \"<ol style='margin-bottom:12px; padding-left:20px;'>\" +
                          \"<li>" . addslashes($panduan1) . "</li>\" +
                          \"<li>" . addslashes($panduan2) . "</li>\" +
                          \"</ol>\" +
                          \"<p style='color:#777; font-size:12px; margin-bottom:0; text-align:center;'>Otomatis kembali dalam 5 detik...</p>\" +
                          \"</div>\",
                    timer: 5000,
                    showConfirmButton: true,
                    confirmButtonText: 'Kembali ke Menu Utama',
                    confirmButtonColor: '#d9534f',
                    allowOutsideClick: false
                }).then(function() {
                    window.location.href = '" . $targetRedirect . "';
                });
            }
            setTimeout(function() {
                window.location.href = '" . $targetRedirect . "';
            }, 5000);
        });
    </script>
</body>
</html>";
        die();
    }

}


if (!function_exists('is_silent_or_json_request')) {
    function is_silent_or_json_request()
    {
        $ci =& get_instance();
        $isAjax = ($ci && isset($ci->input) && $ci->input->is_ajax_request());
        return ((defined('SILENT_TRANSAKSIONAL') && SILENT_TRANSAKSIONAL) ||
            (isset($_GET['return_json']) && $_GET['return_json'] == 1) ||
            (isset($_GET['json']) && $_GET['json'] == 1) ||
            $isAjax);
    }
}

if (!function_exists('sanitize_output_script')) {
    function sanitize_output_script($str)
    {
        if (empty($str)) return "";
        return trim(preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $str));
    }
}

// region untuk keperluan debuger
function show_debuger()
{
    if (is_silent_or_json_request()) {
        return 0;
    }
    //    $debuger = true;
    $debuger = false;
    if ($debuger == true) {
        $_GET['debuger'] = 1;
    }
    else {
        if (isset($_SESSION['debuger'])) {
            // echo ("ada session debuger");
        }
        else {
            // echo("tidak ada session debuger");
            $_GET['debuger'] = 0;
        }
    }
    // echo("$debuger");
    // arrPrint($_SESSION['debuger']);

    $sesDebug = !isset($_SESSION['debuger']) ? "" : $_SESSION['debuger'];
    $getDebug = !isset($_GET['debuger']) ? "" : $_GET['debuger'];

    // print_r($_SESSION['login']['debuger']);
    //    echo sesEmployeeJenis() . "$sesDebug === $getDebug ****";

    $var = 0;
    if (!isset($_GET['debuger'])) {
        if (($sesDebug == 1)) {
            $var = 1;
            //            echo "tampil";
        }
        //        cekHere("noget");
        if (isset($_SESSION['login']['debuger'])) {
            $_SESSION["debuger"] = $_SESSION['login']['debuger'];
        }
        // echo("ok");
    }
    elseif (isset($_GET['debuger'])) {
        if ($getDebug == 1) {
            if (!isset($_SESSION['login'])) {

                $_SESSION['debuger'] = 1;
            }
            else {
                $_SESSION['login']['debuger'] = 1;
                $_SESSION['debuger'] = $_SESSION['login']['debuger'];
            }
        }
        else {
            if (!isset($_SESSION['login'])) {

                $_SESSION['debuger'] = 0;
            }
            else {
                $_SESSION['login']['debuger'] = 0;
                $_SESSION['debuger'] = $_SESSION['login']['debuger'];
            }
        }

        if (($sesDebug == 1) and ($getDebug != 1)) {
            $var = 0;
        }
        elseif (($sesDebug == 1) or ($getDebug == 1)) {
            $var = 1;
        }
        //        cekHere("ada getnya");
        //        echo "ada getnya";
    }
    else {
        $var = 0;
        //        cekHere("lainnya");
    }

    /* -------------------------------------------------------------
     * debuger tidak bisa hidup diluar maya
     * -------------------------------------------------------------*/
    //    arrPrint($_SESSION['login']['ghost']);
    if ($_SERVER['REMOTE_ADDR'] != "202.65.117.72") {
        //    if($_SERVER['REMOTE_ADDR'] != "202.65.117.799"){
        $CI = $ci = &get_instance();
        //        $ghost = $CI->session->login["ghost"];
        //                 $var = 1;
        //        cekbiru($ghost);
        //        if ($sesDebug == 1) {
        //                    $var = 1;
        //        }else{
        //             $var = 0;
        //        }
    }
    if ($_SERVER['REMOTE_ADDR'] == "202.65.117.80") {
        $var = 0;
    }
    /* ----------------------------------------------------------
     * hanya berlakuku untuk network maya
     * ----------------------------------------------------------*/
    if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
        $CI = $ci = &get_instance();
        //        $ghost = $CI->session->login["ghost"];
        // $var = 0;
        //        cekbiru($ghost);
        // if ($sesDebug == 1) {
        //     $var = 0;
        // }
        // else {
        //     $var = 0;
        // }
        //        $var = 1;
    }
    if ($_SERVER['REMOTE_ADDR'] == "192.168.5.7") {
        // $var = 0;
    }
    if ($_SERVER['REMOTE_ADDR'] == "192.168.5.3") {
        //        $var = 1;
        //        $var = 0;
    }
    if ($_SERVER['REMOTE_ADDR'] == "192.168.5.4") {
        //        $var = 1;
    }
    if ($_SERVER['REMOTE_ADDR'] == "192.168.5.1") {
        //        $var = 1;
    }

    // ------------------------------------------------------
    // debuger diaktifkan di masing-masing ip
    // tidak di dluar, karena semua kena
    //        $var = 0;
    //   $var = 1;

    return $var;
}

function matiDisini($param = "------ mati disini ------")
{
    $dtime_now = dtimeNow() . " " . my_host();

    //    if (is_silent_or_json_request()) {
    //        header('Content-Type: application/json');
    //        echo json_encode(array(
    //            "success" => false,
    //            "commit" => 0,
    //            "error" => $param,
    //            "dtime" => $dtime_now
    //        ));
    //        exit();
    //    }

    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlert($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    return die($param);
}

function matiDisiniOriginal($param = "------ mati disini ------")
{
    $dtime_now = dtimeNow();
    $host = $_SERVER['HTTP_HOST'];

    //    if (is_silent_or_json_request()) {
    //        header('Content-Type: application/json');
    //        echo json_encode(array(
    //            "success" => false,
    //            "commit" => 0,
    //            "error" => $param,
    //            "dtime" => $dtime_now,
    //            "host" => $host
    //        ));
    //        exit();
    //    }

    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now $host</small>",
    );
    echo swalAlertOriginal($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    return die($param);
}

function matiAlert($param = "------ mati disini ------", $line = "")
{
    $dtime_now = dtimeNow();
    $host = $_SERVER['HTTP_HOST'];
    $lines = $line != "" ? "@" . $line : "";
    $swalAlert = array(
        "html" => $param . "<br><small class='meta'>$dtime_now $host $lines</small>",
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
    );
    echo swalAlertOriginal($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param $lines</div>";

    if (show_debuger() == 1) {
        echo $param;
        flush();
        ob_flush();
    }
    return die();
}

function mati_disini($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow() ." ". my_host();
    // $swalAlert = array(
    //     "html" => $param . "<br><small>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);
    //
    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisini($param);
}

function opnameWhiteboard($param = "------ mati disini ------", $param2 = "")
{
    $dtime_now = dtimeNow() . " " . my_host();
    $swalAlert = array(
        "type" => "warning",
        "title" => $param,
        "width" => "600px",
        "allowOutsideClick" => "",
        //        "background" => "#E4080A",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#E4080A",
        "html" => nl2br($param2) . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlertWhitebord($swalAlert);
}

function matiWhiteboard($param = "------ mati disini ------", $param2 = "")
{
    $dtime_now = dtimeNow() . " " . my_host();
    $swalAlert = array(
        "type" => "warning",
        "title" => $param,
        "width" => "600px",

        "allowOutsideClick" => "",
        //        "background" => "#E4080A",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#E4080A",

        "html" => nl2br($param2) . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlertWhitebord($swalAlert);
    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    return die($param);
}

function swalAlertWhitebord($arrAlert)
{
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlert);
    //    }
    $hasil = "";
    foreach ($arrAlert as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }
    return ("<script>top.close_holdon();top.swal({" . $hasil . "});top.$('button.btn.btn-success').prop('disabled', false);</script>");
}

function mati_disini_linkmutasi($param = "------ mati disini ------", $link = "")
{
    $dtime_now = dtimeNow() . " " . my_host();
    $param_new = $param;
    $param_new .= " Klik untuk melihat mutasi <a href='$link' target='_blank'><span class='fa fa-clock-o'></span></a>";
    $swalAlert = array(
        "html" => $param_new . "<br><small class='meta'>$dtime_now</small>",
    );
    echo swalAlert($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param_new</div>";

    return die($param);
}

function mati_disini_original($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow();
    // $swalAlert = array(
    //     "html" => $param . "<br><small>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);
    //
    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisiniOriginal($param);
}

function matiHere($param = "------ mati disini ------")
{
    // $dtime_now = dtimeNow() ." ". my_host();
    //
    // $swalAlert = array(
    //     "html" => $param . "<br><small class='meta'>$dtime_now</small>",
    // );
    // echo swalAlert($swalAlert);

    // $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
    //
    // return die($param);
    return matiDisini($param);
}

function cekAlert($param = "------ alert ------")
{
    $swalAlert = array(
        "html" => $param,
    );
    echo swalAlertOriginal($swalAlert);

    $param = "<div style='border: 1px dashed #ff4b33;background:#FDF5CE;padding: 10px 5px;color: red;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

    // return die($param);
}

function cekHere($param = "halooo.")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #FFF0F0;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {

        $param = "<div style='border: 1px dashed #fff;background:#FFF0F0;padding: 10px 5px;color: red;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekHijau($param = "halooo ijau(s)")
{
    $array = is_array($param) ? $param : "";

    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: #EDFBF1;padding: 5px;color: #008700;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed #B6E5B8;background:#EDFBF1;padding: 5px;
        color: #008700;margin: 10px auto;text-align: center;
        font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }

        flush();
        ob_flush();
    }
}

function cekBiru($param = "halooo biru(s)")
{

    if (show_debuger() == 1) {
        if (is_array($param)) {
            echo "<pre style='background: #39b3d7;padding: 5px;color: #ffffcc;'>";
            print_r($param);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed #ffffff;background:#39b3d7;padding: 5px;color: #ffffcc;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
            echo $param;
        }

        flush();
        ob_flush();
    }
}

function cekMerah($param = "halooo merah")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #990000;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #B6E5B8;background:#990000;padding: 10px 5px;color: #ffffff;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekOrange($param = "halooo orange")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #ff9900;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #ff7701;background:#ff9900;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekKuning($param = "halooo kuning(s)")
{
    $array = is_array($param) ? $param : "";

    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: yellow;padding: 5px;color: #000000;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed yellow;background:yellow;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }
        flush();
        ob_flush();
    }
}

function cekLime($param = "halooo lime")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: greenyellow;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed greenyellow;background:greenyellow;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekPink($param = "halooo pink(s)")
{
    $array = is_array($param) ? $param : "";
    if (show_debuger() == 1) {
        if ($array != "") {
            echo "<pre style='background: pink;padding: 5px;color: #008700;'>";
            print_r($array);
            echo "</pre>";
        }
        else {
            $param = "<div style='border: 0.5px dashed pink;background:pink;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";

            echo $param;
        }
        flush();
        ob_flush();
    }
}

function cekPink2($param = "halooo pink")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #FF55FF;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #FF55FF;background:#FF55FF;padding: 10px 5px;color: #000000;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekHitam($param = "halooo hitam")
{
    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #000000;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {

        $param = "<div style='border: 0.5px dashed black;background:black;padding: 10px 5px;color: #ffffff;;margin: 10px auto;text-align: center;font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function cekUngu($param = "halooo unguuuu unyu unyu XD")
{


    if (is_array($param)) {
        if (show_debuger() == 1) {
            echo "<div style='margin-left: 100px'>";
            echo "<pre style='background: #8D38C9;'>";
            print_r($param);
            echo "</pre>";
            echo "</div>";
        }
    }
    else {
        $param = "<div style='border: 0.5px dashed #8D38C9;background:#8D38C9;padding: 10px 5px;
        color: #ffffff;margin: 10px auto;text-align: center;
        font-family: consolas;text-wrap: normal;'>$param</div>";
        if (show_debuger() == 1) {

            echo $param;
            flush();
            ob_flush();
        }
    }
}

function jejeran($array)
{
    echo "<div style='float: left;overflow: hidden;'>";
    //    arrPrint($array);
    echo "</div>";
}

function jejeranPink($array)
{
    echo "<div style='float: left;overflow: hidden;'>";
    //    arrPrintPink($array);
    echo "</div>";
}

function showLast_query($warna, $msg = "")
{
    $ci = &get_instance();
    $warna_f = ucwords($warna);
    $cek = "cek" . $warna_f;

    $msg_f = strlen($msg) > 1 ? "<hr style='margin: 5px;'>$msg" : "";
    $cek($ci->db->last_query() . $msg_f);
}

function arrPrint($array)
{
    if (show_debuger() == 1) {

        echo "<pre style='background: #ffffee;'>";
        print_r($array);
        echo "</pre>";
    }
}

function arrPrintPink($array)
{
    if (show_debuger() == 1) {

        echo "<pre style='background: pink;'>";
        print_r($array);
        echo "</pre>";
    }
}

function arrPrintWebs($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #c4e3f3;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintKuning($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #f1d40f;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintHijau($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #55FF2A;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintHitam($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #000000;color:#ffffff;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintCyan($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background: #32fae3;color:#000000;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function arrPrintOrange($array)
{
    if (show_debuger() == 1) {
        echo "<div style='margin-left: 100px'>";
        echo "<pre style='background:orange;color:#000000;'>";
        print_r($array);
        echo "</pre>";
        echo "</div>";
    }
}

function info_debuger()
{
    //    global $template, $anu;
    // $sf = $this->config->item('default');
    // $host = gethostname();                // sub_domain demo.mayagraha.....
    // $ip_server_2 = gethostbyname($host);

    $ci = &get_instance();
    $ci->load->config("heWebs");
    $logins = $ci->config->item('logins');
    $loginMuties = $logins['allowedMultySession'];
    $jenis = $ci->uri->segment(3);
    $trCode = "_TR_" . $jenis;
    // cekHijau($_SESSION[$trCode]);
    if (isset($_SESSION[$trCode])) {
        $gateUrl = base_url() . "Transaksi/debug/$jenis";
    }
    else {
        $gateUrl = "";
    }
    $ci->load->database();
    $ar = $ci->config->item("mongo_db");
    $arrInfo = array(
        "Domain" => $_SERVER['SERVER_NAME'],
        // "serverName" => gethostname(),
        "serverRoot" => $_SERVER['DOCUMENT_ROOT'],
        "path" => $_SERVER['REQUEST_URI'],
        "serverIp" => $_SERVER['SERVER_ADDR'],
        "dbHost" => array($ci->db->hostname, "text-red"
        ),
        "dbName" => array($ci->db->database,
            "text-red"
        ),
        "dbUser" => $ci->db->username,
        "clentIp" => $_SERVER['REMOTE_ADDR'],
        "mongoHost" => isset($ar['default']['hostname']) ? $ar['default']['hostname'] : "none",
        "mongoDB" => isset($ar['default']['database']) ? $ar['default']['database'] : "none",
        "multiSess" => print_r($loginMuties, true),
        //        "template" => $anu,
    );
    // $sf = $ci->db->hostname;
    // $sf = $ci->db->database;
    // arrPrint($_SERVER);
    // arrPrint($_SESSION);


    $var = "<div class='col-md-4 pull-right'>";
    $var .= "<div class='border-cekk overflow-h'>";
    $var .= "<button type='button' class='btn btn-link btn-sm pull-left' onclick=\"btn_result('" . base_url() . "auth/Login/removeDebuger');\" 
                title='Turn off debuger mode' data-toggle='tooltip'>
            <i class='fa fa-power-off text-red'></i></button>";
    $var .= "<h3 class='pull-left'>Info Debuger</h3>";
    $var .= "</div>";
    $var .= "<ul class='list-unstyled'>";
    foreach ($arrInfo as $item => $nilai_0) {
        if (is_array($nilai_0)) {
            $nilai = $nilai_0[0];
            $cls = $nilai_0[1];
        }
        else {
            $nilai = $nilai_0;
            $cls = "";
        }

        $var .= "<li>$item: <span class='text-bold font-size-1-2 $cls'>$nilai</span></li>";
    }
    $var .= "</ul>";
    $var .= "</div>";

    return $var;
}

function underMaintenance($ref = "", $reload = "10")
{
    /* -----------------------------------
     * img_maintenace ada di url_helper
     * -----------------------------*/
    //    $ref = referer();
    $undermaintenace = "<div align='center'>";
    // $undermaintenace .= "<img src='../../assets/images/under-maintenance.png'>";
    $undermaintenace .= "<img src='" . img_maintenace() . "'>";
    $undermaintenace .= "</div>";
    if (strlen($ref) > 10) {
        $undermaintenace .= "<div class='text-center' align='center'>";
        // $undermaintenace .= "<button class='btn btn-block btn-warning' type='button' onclick=\"location.href='$ref'\">Back</button>";
        $undermaintenace .= dtimeNow() . " $ref ";
        $undermaintenace .= "</div>";
        // $undermaintenace .= "<script><meta http-equiv='refresh' content='3' URL='".$_SERVER['PHP_SELF']."'></script>";
        $undermaintenace .= "<meta http-equiv='refresh' content='$reload' URL='" . $_SERVER['PHP_SELF'] . "'>";
        // $undermaintenace .= "meta http-equiv='refresh' content=30 URL='".$_SERVER['PHP_SELF']."'";
    }

    return $undermaintenace;
}

function underConstruction()
{
    /* -----------------------------------
         * img_maintenace ada di url_helper
         * -----------------------------*/
    $undermaintenace = "<div align='center'>";
    $undermaintenace .= "<img src='" . base_url() . "assets/images/construction.png'>";
    $undermaintenace .= "</div>";

    return $undermaintenace;
}

function anjingPenjaga()
{

    $undermaintenace = "<div align='center'>";
    $undermaintenace .= "<img src='../../assets/images/bitzer.png'>";
    $undermaintenace .= "</div>";

    return $undermaintenace;
}

// endregion untuk keperluan debuger

function loading_progres($str_peringatan = "")
{
    if ($str_peringatan == "") {
        $warning = "Harap menunggu, transaksi sedang diproses...";
    }
    else {
        $warning = $str_peringatan;
    }
    $var = "<link rel='stylesheet' href='../../assets/templates/pure/css/custom_web.css'>";
    $var .= "<style type='text/css'>
    .layer{
//    background: #ff1493;
    }
    body{

    margin: 0;
    }
    </style>";
    //    $var .= "<div style='display: none;' id='tutup'>";
    $var .= "<div style='display: block;' id='tutup'>";
    $var .= "<div class='layer'></div>";
    $var .= "<div class='layer-3'>";
    $var .= "<div style='border: 0px solid red;text-align: center;font-family: consolas;padding: 5px;'>$warning</div>";
    $var .= "</div>";
    $var .= "</div>";

    return $var;
}

function valIsset($field)
{
    $var = isset($field) ? $field : "";

    return $var;
}

function setDataClean($data)
{
    $var = trim(htmlspecialchars($data, ENT_QUOTES));

    return $var;
}

function swalAlertGoTo($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => true,
        "allowOutsideClick" => true,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }
    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.close_holdon();top.swal({" . $hasil . "}).then(function() {
//        console.log('$executor');
        window.location = '$executor';  
    });</script>");

}

function swalAlertGoToBlock($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;

    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.close_holdon();top.swal({" . $hasil . "}).then(function() {
//        console.log('$executor');
        window.location = '$executor';  
    });</script>");

}


function swalAlertGoTo2($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }
    return ("<script>top.swal({" . $hasil . "}).then(function() {
        document.getElementById('result').src='$executor'
    });</script>");
}

function swalAlertGoTo3($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }
    return ("<script>top.swal({" . $hasil . "}).then(function() {
        window.location = '$executor'        
    });</script>");
}

function swalAlertGoToOption($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "DO IT NOW" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : ($btn_cancel == false ? "" : $btn_cancel);
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "type" => "warning",
        "title" => "<span style=color: red;>Perhatian</span>",
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
        //        "background" => "#34abeb",
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.swal({" . $hasil . "}).then(function() {
        document.getElementById('result').src='$executor'
    });</script>");
}

function swalAlertGoToReload($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "RELOAD" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : $btn_cancel;
    //    $show_cancel_btn = $btn_cancel == "" ? false : true;
    $show_cancel_btn = true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => true,
        "allowOutsideClick" => true,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.swal({" . $hasil . "}).then(function() {
         top.$('#result').load('$link_to');
    });
    </script>");
}

function swalAlertGoToReload2($arrAlert, $link_to, $btn_label = "", $btn_cancel = "")
{
    $label_btn = $btn_label == "" ? "RELOAD" : $btn_label;
    $cancel_btn = $btn_cancel == "" ? "CLOSE" : $btn_cancel;
    $show_cancel_btn = $btn_cancel == "" ? false : true;
    //    $show_cancel_btn = true;
    $executor = $linkSettlement = "$link_to";
    $arrAlertFix = array(
        "showCloseButton" => false,
        "allowOutsideClick" => false,
        "allowEscapeKey" => false,
        "confirmButtonText" => "$label_btn",
        "cancelButtonText" => "$cancel_btn",
        "showCancelButton" => $show_cancel_btn,
    );
    $arrAlertFinal = $arrAlert + $arrAlertFix;
    //
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlertFinal);
    //    }

    $hasil = "";
    foreach ($arrAlertFinal as $ky => $val) {
        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    return ("<script>top.close_holdon();top.swal({" . $hasil . "}).then(function() {
         top.location.reload();
    });
    </script>");
}

// START OF COMPLETE REPEATED LOGIC
function swalAlert($arrAlert)
{
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlert);
    //    }
    $hasil = "";
    foreach ($arrAlert as $ky => $val) {

        if ($ky == "onOpen") {
            $var = "$ky: $val";
        }
        elseif ($val === false || $val === "false") {
            $var = "$ky: false";
        }
        elseif ($val === true || $val === "true") {
            $var = "$ky: true";
        }
        elseif (is_numeric($val) && in_array($ky, array("timer", "width"))) {
            $var = "$ky: $val";
        }
        else {
            $var = "$ky: \"$val\"";
        }

        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    //    echo toAlert($hasil);
    return ("<script>top.close_holdon();top.swal({" . $hasil . "});top.$('button.btn.btn-success').prop('disabled', false);</script>");
}

// END OF COMPLETE REPEATED LOGIC

function swalAlertOriginal($arrAlert)
{
    //    if (is_silent_or_json_request()) {
    //        return json_encode($arrAlert);
    //    }
    $hasil = "";
    foreach ($arrAlert as $ky => $val) {

        $var = "$ky: \"$val\"";
        if ($hasil == "") {
            $hasil = "$var";
        }
        else {
            $hasil = "$hasil, $var";
        }
    }

    //    echo toAlert($hasil);
    return ("<script>top.close_holdon();top.swal({" . $hasil . "});</script>");

    //    echo("<script>top.swal({, type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    echo("<script>top.swal({title: 'Good job', text: 'Data Sudah tersimpan, klik OK untuk mereload halaman!', type: 'success'}).then(function(){top.location.reload(true);})</script>");
    //    die("<script>window.setTimeout(function(){
    //            top.location.reload();
    //        } ,2000);</script>");
}

function modalDialogFormSubmit($headingTitle, $form_id, $linkAction, $submit_label = false)
{
    if ($submit_label != false) {
        $btn_submit = "{
                    label: '$submit_label',
                     cssClass: 'btn-primary pull-right',
                    action: function(){
                        document.getElementById('$form_id').submit();
                    },
            
                }";
    };
    $actionTarget = "top.BootstrapDialog.closeAll();
                            top.BootstrapDialog.show(
                                   {
                                       title:'$headingTitle',
                                       message: " . '$' . "('<div></div>').load('$linkAction'),
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        draggable:false,
                                        closable:true,
                                        buttons: [{                                            
                                            label: 'Close',
                                             cssClass: 'pull-left',
                                            action: function(dialogItself){
                                                    dialogItself.close();
                                                }
                                            }," .
        $btn_submit .

        "]
                                    }
                                );
                                    ";

    return $actionTarget;
}

function modalDialogBtn($headingTitle, $linkAction, $auto_close = 1, $pf = null)
{

    $actionTarget = "";

    if ($auto_close) {
        $actionTarget .= "top.BootstrapDialog.closeAll();";
    }

    $prefix = $pf != null ? "id=$pf" : "";

    $actionTarget .= "top.BootstrapDialog.show({
                          title:'" . str_replace('+', ' ', htmlspecialchars($headingTitle)) . "',
                          message: " . '$' . "('<div $prefix></div>').load('" . $linkAction . "'),
                          size:top.BootstrapDialog.SIZE_WIDE,
                          draggable:true,
                          closable:true,
                          buttons: [{
                              label: 'Close',
                              cssClass: 'pull-left',
                              action: function(dialogItself){
                                  dialogItself.close();
                              }
                          }]
                       });";

    return $actionTarget;
}

function modalDialogKhusus($headingTitle, $linkContent, $linkAction)
{

    $actionTarget = "top.BootstrapDialog.closeAll();
                            top.BootstrapDialog.show(
                                   {
                                       title:'$headingTitle',
                                       message: " . '$' . "('<div></div>').load('$linkContent'),
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        draggable:false,
                                        closable:false,
                                        buttons: [{                                            
                                            label: 'Back To Home',
                                             cssClass: 'pull-left',
                                            action: function(){
                                                    location.href='$linkAction';
                                                }
                                        }]
                                    }
                                );
                                    ";

    return $actionTarget;
}

function lgShowWarning($caption, $errMsg)
{
    $dtime_now = dtimeNow() . " " . my_host();
    return swalAlert(array(
        //        "width"             => '600px',
        "type" => "warning",
        "title" => "$caption",
        // "html" => "<span style='font-size:1.4em;'>" . htmlspecialchars($errMsg) . "</span><br><small>$dtime_now</small>",
        "html" => "<span style='font-size:1.1em;'>" . ($errMsg) . "</span><br><small class='meta'>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "showLoaderOnConfirm" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgShowSuccess($caption, $errMsg)
{
    return swalAlert(array(
        //        "width"             => '600px',
        "type" => "success",
        "title" => "$caption",
        "html" => "<span style='font-size:1.4em;'>$errMsg</span>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "showLoaderOnConfirm" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgShowAlert($errMsg)
{
    $dtime_now = dtimeNow() . " " . my_host();
    return swalAlert(array(
        "type" => "warning",
        "title" => $judul,
        "html" => "<span style='font-size:1.2em;'>$errMsg</span><br><small class='meta'>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
    ));
}

function lgShowAlertBiru($newWarningLabel)
{
    $dtime_now = dtimeNow();
    $arrSwals = array(
        "type" => "warning",
        "title" => "<span style='color: red;'>Perhatian</span>",
        "html" => "<span style='color: yellow;'>$newWarningLabel</span><br><small>$dtime_now</small>",
        "allowOutsideClick" => false,
        "background" => "#34abeb",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#ff0055",
    );

    return swalAlert($arrSwals);

}

function lgShowAlertMerah($newWarningLabel)
{
    $dtime_now = dtimeNow();
    $arrSwals = array(
        "type" => "warning",
        "title" => "<span style='color:#ffffff;font-weight: bold;'>Perhatian</span>",
        "html" => "<span style='color:#ffffff;font-weight: bold;'>$newWarningLabel</span><br><small style='color:#ffffff;'>$dtime_now</small>",
        "allowOutsideClick" => false,
        "background" => "#E4080A",
        "confirmButtonText" => "Close",
        "confirmButtonColor" => "#000000",
    );

    return swalAlert($arrSwals);

}

function downloadXlsx($link_excel, $dataUrl, $file_nama)
{
    // dataUrl = "data=$strItems&item2=$strItems2";
    $var = "<script>
    
                        var download_excel = function(){
    
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '$link_excel', true);
                           xhr.responseType = 'blob';
    //                        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onload = function(e) {
                                if (this.status == 200) {
                                    var blob = new Blob([this.response], {type: 'application/vnd.ms-excel'});
                                    var downloadUrl = URL.createObjectURL(blob);
                                    var a = document.createElement(\"a\");
                                    a.href = downloadUrl;
                                    a.download = \"$file_nama.xlsx\";
                                    document.body.appendChild(a);
                                    a.click();
                                } else {
                                    alert('Unable to download excel.')
                                }
                            };
                            xhr.send('$dataUrl');
    
                        }
                    </script>";

    return $var;
}

function lgShowError($caption, $errMsg)
{
    $dtime_now = dtimeNow();

    return swalAlert(array(
        "type" => "error",
        "title" => $caption,
        "html" => $errMsg . "<br><small>$dtime_now</small>",
        "showConfirmButton" => true,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function lgWait($errMsg = "Please wait ... ...")
{
    return swalAlert(array(
        // "type" => "error",
        // "title" => "Pleas wait ... ...",
        "html" => $errMsg,
        "showConfirmButton" => false,
        "allowOutsideClick" => true,
        "showCloseButton" => true,
        // "imageUrl"            => "http://inn.com"
    ));
}

function reloaded($time_ms = "2000")
{
    if (is_silent_or_json_request()) {
        return "";
    }
    die("<script>window.setTimeout(function(){
            location.reload();
        } ,$time_ms);</script>");
}

function redirecResult($target_url)
{
    if (is_silent_or_json_request()) {
        return "";
    }
    die("<script>
        // document.getElementById('result').src = $target_url
        location.href='$target_url';
        </script>");
}

function topReload($time_ms = "2000")
{
    if (is_silent_or_json_request()) {
        return "";
    }
    die("<script>window.setTimeout(function(){
            top.location.reload();
        } ,$time_ms);</script>");
}

function topRedirect($target_url, $time_ms = 0)
{
    if (is_silent_or_json_request()) {
        return "";
    }
    die("<script>window.setTimeout(function(){
            top.location.href='$target_url';
        } ,$time_ms);</script>");
}

function session()
{
    // if (strlen($key) > 0) {
    //     $obj = (object)($_SESSION[$key]);
    // }
    // else {
    $obj = (object)$_SESSION;
    // }

    // return $_SESSION;
    return $obj;
}

//region webs session
function sessionWebs()
{
    return isset($_SESSION['webs']) ? $_SESSION['webs'] : array();
}

function produk_spec_webs()
{
    $var = sessionWebs();

    return isset($var['produk_spec']) ? $var['produk_spec'] : array();
}

function produk_folder_webs()
{
    $var = sessionWebs();

    return isset($var['produk_folders']) ? $var['produk_folders'] : array();
}

function produk_harga_webs()
{
    $var = sessionWebs();

    return isset($var['produk_hargas']) ? $var['produk_hargas'] : array();
}

function cart_webs()
{
    $var = sessionWebs();

    return isset($var['cart']) ? $var['cart'] : array();
}

function login_webs()
{
    $var = sessionWebs();

    return isset($var['login']) ? $var['login'] : array();
}

//endregion

function ion_icon($label)
{
    $label = strtolower($label);
    $arrIon_icon = array(
        "data" => "fa-database",
        "pembelian" => "fa-money",
        "produksi" => "ion-ios-gear",
        "distribusi" => "fa-truck",
        "penjualan" => "ion-ribbon-b",
        "laporan" => "ion-clipboard",
        "setting" => "ion-settings",
        "tools" => "ion-briefcase",
        "login" => "fa-key",
        "logout" => "fa-lock",
        "kategori" => "fa-folder-open",
    );

    return $arrIon_icon[$label];
}

function fa_icon($label = null)
{
    $label = strtolower($label);
    $arrIon_icon = array(
        "menu utama" => "fa-cube",
        "sub menu" => "fa-cubes",
        "warehouses" => "fa-cube",
        "supplies & equipments" => "fa-cubes",
        "web" => "fa-globe",
        "merk" => "fa-tag",
        "home" => "fa-home",
        "po" => "fa-rocket",
        "konsumen" => "fa-users",
        "customers" => "fa-users",
        "wewenang" => "fa-fire",
        "access" => "fa-fire",
        "email" => "fa-envelope-o",
        "distribusi" => "fa-truck",
        "couriers" => "fa-motorcycle",
        "vendors" => "fa-truck",
        "banks" => "fa-bank",
        "company profiles" => "fa-industry",
        // "banks"               => "fa-building-o",
        "bank accounts" => "fa-male",
        "produk" => "fa-diamond",
        "products" => "fa-diamond",
        "product prices" => "fa-tag",
        "kategori" => "fa-folder-open",
        "product folders" => "fa-briefcase",
        "item" => "fa-tag",

        "composition productses" => "fa-file-text-o",
        "assembled product folders" => "fa-briefcase",
        "assembled products" => "fa-puzzle-piece",

        "karyawan" => "fa-user",
        "user" => "fa-user",
        "users" => "fa-user",
        "employees" => "fa-user-secret",
        "warehouse employees" => "fa-user-secret",

        "profile" => "fa-user",
        "penjualan" => "ion-ribbon-b",
        "cabang" => "fa-puzzle-piece",
        "branches" => "fa-leaf",
        "laporan" => "ion-clipboard",
        "laporans" => " fa-pie-chart",
        "data" => "fa-database",
        "pembelian" => "fa-money",
        "purchasing" => "fa-money",
        "shoping cart" => "fa-shopping-cart",
        "produksi" => "ion-ios-gear",
        "setting" => "ion-settings",
        "settings" => "fa-gears",
        "tools" => "ion-briefcase",
        "login" => "fa-key",
        "logout" => "fa-lock",
        "shipping addresses" => "fa-map-pin",
        "billing addresses" => "fa-map",
        "my shipping addresses" => "fa-map-marker",
        "transaksi" => "fa-exchange",
        "connected productses" => "fa-link",
        "activity logs" => "fa-suitcase",
        "term of services" => "fa-bookmark-o",
        "term of payments" => "fa-bookmark-o",
        "capacities" => "fa-archive",
        "capacityes" => "fa-archive",
        "advertises" => "fa-bullhorn",
    );

    if ($label == null) {
        return $arrIon_icon;
    }
    else {
        return $arrIon_icon[$label];
    }
}

function my_id()
{
    if (isset($_SESSION['login']['id'])) {

        return $_SESSION['login']['id'];
    }
    else {
        return 0;
    }
}

function my_name()
{
    if (isset($_SESSION['login']['nama'])) {

        return $_SESSION['login']['nama'];
    }
    else {
        return 0;
    }
}

function my_cabang_id()
{
    if (isset($_SESSION['login']['cabang_id'])) {
        return $_SESSION['login']['cabang_id'];
    }
    else {
        return 0;
    }
}

function my_cabang_nama()
{
    if (isset($_SESSION['login']['cabang_nama'])) {
        return $_SESSION['login']['cabang_nama'];
    }
    else {
        return 0;
    }
}

function my_ppn_factor()
{
    if (isset($_SESSION['login']['ppnFactor'])) {
        return $_SESSION['login']['ppnFactor'];
    }
    else {
        return 0;
    }
}

function my_toko_id()
{
    if (isset($_SESSION['login']['toko_id'])) {
        return $_SESSION['login']['toko_id'];
    }
    else {
        return 0;
    }
}

function my_toko_nama()
{
    return isset($_SESSION['login']['toko_nama']) ? $_SESSION['login']['toko_nama'] : "-";
}

function my_gudang_id()
{
    if (isset($_SESSION['login']['gudang_id'])) {
        return $_SESSION['login']['gudang_id'];
    }
    else {
        return 0;
    }
}

function my_gudang_nama()
{
    if (isset($_SESSION['login']['gudang_nama'])) {
        return $_SESSION['login']['gudang_nama'];
    }
    else {
        return 0;
    }
}

function my_div_id()
{
    return isset($_SESSION['login']['div_id']) ? $_SESSION['login']['div_id'] : 0;
}

function my_div_nama()
{
    return isset($_SESSION['login']['div_nama']) ? $_SESSION['login']['div_nama'] : "-";
}

function my_jenis_usaha()
{
    return isset($_SESSION['login']['jenis_usaha']) ? $_SESSION['login']['jenis_usaha'] : "-";
}

function my_menu($cabang_id)
{
    $ci = &get_instance();
    $ci->load->config('menu_link.php');


    if ($cabang_id > 0) {
        $menu = $ci->config->item('menu')['cabang'];
    }
    elseif ($cabang_id < 0) {
        $menu = $ci->config->item('menu')['pusat'];
    }
    else {
        $menu = array();
    }

    return $menu;
}

function my_memberships()
{
    return $_SESSION['login']['membership'];
}

function my_type()
{
    return isset($_SESSION['login']['employee_type']) ? $_SESSION['login']['employee_type'] : false;
}

function my_place_label()
{
    $cabang_id = $_SESSION['login']['cabang_id'];
    if ($cabang_id > 0) {
        $my_place_label = "branch";
    }
    elseif ($cabang_id < 0) {
        $my_place_label = "center";
    }
    else {
        $my_place_label = NULL;
    }
    return $my_place_label;
}

define('CB_ID_PUSAT', '-1');
define('CB_NAME_PUSAT', 'DC/PUSAT');


function createStateSign($current, $totalAvail, $jenisTr = NULL)
{
    $ci = &get_instance();
    $array_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'] : array();
    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            $str .= "<span class='fa fa-check-circle text-black'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }
    // tambahan next activity
    if ($jenisTr != NULL) {
        $next_step_number = $current + 1;
        $ci = &get_instance();
        $next_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        $str .= "<br>";
        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }

    return $str;
}

function createStateSignNext($current, $totalAvail, $jenisTr = NULL)
{
    $next_step_number = $current + 1;
    $ci = &get_instance();
    $array_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'] : array();
    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i < $current) {
            $str .= "<span class='fa fa-check-circle text-grey'> $step_label</span><br>";
        }
        elseif ($i == $next_step_number) {
            $str .= "<span class='fa fa-check-circle text-black text-bold'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }
    // tambahan next activity
    //    if ($jenisTr != NULL) {
    //        $next_step_number = $current + 1;
    //        $ci = &get_instance();
    //        $next_step_label = isset($ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label']) ? $ci->config->item("heTransaksi_ui")[$jenisTr]['steps'][$next_step_number]['label'] : "-";
    //
    //        $str .= "<br>";
    //        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
    //        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    //    }

    return $str;
}

// START OF COMPLETE REPEATED LOGIC
function createStateHorizontal($current, $totalAvail, $jenisTr = NULL, $showLabel = true)
{
    $ci = &get_instance();
    $transUI = $ci->config->item("coTransaksiUi");
    if (empty($transUI)) {
        $transUI = $ci->config->item("heTransaksi_ui");
    }
    if (empty($transUI) && isset($ci->configUi)) {
        $transUI = $ci->configUi;
    }
    $transUI = $ci->config->item("heTransaksi_ui");
    $arrJenisTr = array();

    $array_step_label = isset($transUI[$jenisTr]['steps']) ? $transUI[$jenisTr]['steps'] : array();
    foreach ($array_step_label as $stp => $stpSpec) {
        $arrJenisTr[] = $stpSpec["target"];
    }

    $array_step_label_connect = array();
    if (isset($transUI[$jenisTr]['connectTo'])) {
        $connect_to = $transUI[$jenisTr]['connectTo'];
        $array_step_label_connect = isset($transUI[$connect_to]['steps']) ? $transUI[$connect_to]['steps'] : array();
        foreach ($array_step_label_connect as $stp => $stpSpec) {
            $arrJenisTr[] = $stpSpec["target"];
        }
    }

    $array_step_label_payment = array();
    if (isset($transUI[$jenisTr]['connectToPayment']['connectTo'])) {
        $connect_to = $transUI[$jenisTr]['connectToPayment']['connectTo'];
        $array_step_label_payment = isset($transUI[$connect_to]['steps']) ? $transUI[$connect_to]['steps'] : array();
        foreach ($array_step_label_payment as $stp => $stpSpec) {
            $arrJenisTr[] = $stpSpec["target"];
        }
    }

    $array_step_label_warehouse = array();
    if (isset($transUI[$jenisTr]['connectToWarehouse']['connectTo'])) {
        $connect_to = $transUI[$jenisTr]['connectToWarehouse']['connectTo'];
        $array_step_label_warehouse = isset($transUI[$connect_to]['steps']) ? $transUI[$connect_to]['steps'] : array();
        foreach ($array_step_label_warehouse as $stp => $stpSpec) {
            $arrJenisTr[] = $stpSpec["target"];
        }
    }


    //    cekHijau("[$jenisTr]");
    //    arrPrintHitam($arrJenisTr);
    $bulkUserMap = array();
    if ($jenisTr != NULL) {
        $cabangId = function_exists("my_cabang_id") ? my_cabang_id() : (isset($_SESSION['login']['cabang_id']) ? $_SESSION['login']['cabang_id'] : 0);
        $ci->load->model("Mdls/MdlAccessRight");
        $mdlAccessRight = new MdlAccessRight();
        //        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, $cabangId);
        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($arrJenisTr, NULL);
        //        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($arrJenisTr, $cabangId);
    }
    //    arrPrintCyan($bulkUserMap);

    $str = "";

    // reguler
    $str .= "<ul class='timeline-kecil'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        //        arrPrintPink($array_step_label[$i]);
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        $step_target = isset($array_step_label[$i]['target']) ? $array_step_label[$i]['target'] : "-";
        if ($i <= $current) {
            $bg_warna = "done-tl";
        }
        elseif ($i == ($current + 1)) {
            $bg_warna = "now-tl";
        }
        else {
            $bg_warna = "none";
        }
        $labelDisplay = $showLabel == true ? "" : "style='display:none;'";

        $userBadge = "";
        $titleAttr = "";
        if (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
            $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
            $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
            $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
        }
        else {
            $titleAttr = " title='Hak Akses Belum Diatur.' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses Belum Diatur.'";
            $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i>Hak Akses Belum Diatur.</small>";
        }

        $str .= "<li class='$bg_warna no-margin' $titleAttr>";
        $str .= "<span $labelDisplay>$step_label $userBadge</span>";
        $str .= "</li>";
    }

    // connectTo 1
    $count_connect_to = count($array_step_label_connect);
    for ($i = 1; $i <= $count_connect_to; $i++) {
        if (isset($array_step_label_connect[$i]["userGroup"]) && $array_step_label_connect[$i]["userGroup"] != "sys") {
            $step_label = isset($array_step_label_connect[$i]['label']) ? $array_step_label_connect[$i]['label'] : "-";
            $step_target = isset($array_step_label_connect[$i]['target']) ? $array_step_label_connect[$i]['target'] : "-";
            if ($i <= $current) {
                $bg_warna = "done-tl";
            }
            elseif ($i == ($current + 1)) {
                $bg_warna = "now-tl";
            }
            else {
                $bg_warna = "none";
            }
            $labelDisplay = $showLabel == true ? "" : "style='display:none;'";

            $stepIndex = $totalAvail + $i;
            $userBadge = "";
            $titleAttr = "";
            if (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            elseif (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            else {
                $titleAttr = " title='Hak Akses Belum Diatur.' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses Belum Diatur.'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i>Hak Akses Belum Diatur.</small>";
            }

            $str .= "<li class='$bg_warna no-margin' $titleAttr>";
            $str .= "<span $labelDisplay>$step_label $userBadge</span>";
            $str .= "</li>";
        }
    }

    // connectTo 2
    $count_connect_to = count($array_step_label_payment);
    for ($i = 1; $i <= $count_connect_to; $i++) {
        if (isset($array_step_label_payment[$i]["userGroup"]) && $array_step_label_payment[$i]["userGroup"] != "sys") {
            $step_label = isset($array_step_label_payment[$i]['label']) ? $array_step_label_payment[$i]['label'] : "-";
            $step_target = isset($array_step_label_payment[$i]['target']) ? $array_step_label_payment[$i]['target'] : "-";
            if ($i <= $current) {
                $bg_warna = "done-tl";
            }
            elseif ($i == ($current + 1)) {
                $bg_warna = "now-tl";
            }
            else {
                $bg_warna = "none";
            }
            $labelDisplay = $showLabel == true ? "" : "style='display:none;'";

            $stepIndex = $totalAvail + $i;
            $userBadge = "";
            $titleAttr = "";
            if (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            elseif (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            else {
                $titleAttr = " title='Hak Akses Belum Diatur.' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses Belum Diatur.'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i>Hak Akses Belum Diatur.</small>";
            }

            $str .= "<li class='$bg_warna no-margin' $titleAttr>";
            $str .= "<span $labelDisplay>$step_label $userBadge</span>";
            $str .= "</li>";
        }
    }

    // connectTo 3
    $count_connect_to = count($array_step_label_warehouse);
    for ($i = 1; $i <= $count_connect_to; $i++) {
        if (isset($array_step_label_warehouse[$i]["userGroup"]) && $array_step_label_warehouse[$i]["userGroup"] != "sys") {
            $step_label = isset($array_step_label_warehouse[$i]['label']) ? $array_step_label_warehouse[$i]['label'] : "-";
            $step_target = isset($array_step_label_warehouse[$i]['target']) ? $array_step_label_warehouse[$i]['target'] : "-";
            if ($i <= $current) {
                $bg_warna = "done-tl";
            }
            elseif ($i == ($current + 1)) {
                $bg_warna = "now-tl";
            }
            else {
                $bg_warna = "none";
            }
            $labelDisplay = $showLabel == true ? "" : "style='display:none;'";

            $stepIndex = $totalAvail + $i;
            $userBadge = "";
            $titleAttr = "";
            if (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            elseif (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
                $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
                $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
            }
            else {
                $titleAttr = " title='Hak Akses Belum Diatur.' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses Belum Diatur.'";
                $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i>Hak Akses Belum Diatur.</small>";
            }

            $str .= "<li class='$bg_warna no-margin' $titleAttr>";
            $str .= "<span $labelDisplay>$step_label $userBadge</span>";
            $str .= "</li>";
        }
    }


    $str .= "</ul>";

    return $str;
}

// END OF COMPLETE REPEATED LOGIC

function createStateMap($current, $totalAvail, $steps, $jenisTr)
{
    $ci = &get_instance();
    $str = "";

    $str .= "<span style='font-family:georgia;'>";
    $trConfig = $ci->config->item("heTransaksi_ui")[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;

        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
            }
        }
        if ($i < $totalAvail) {
            $str .= " <span class='fa fa-angle-double-right'> ";
        }

    }
    $str .= "</span>";

    return $str;
}

// START OF COMPLETE REPEATED LOGIC
function createStateHorizontalMap($current, $totalAvail, $steps, $jenisTr)
{
    $ci = &get_instance();
    $str = "";
    $trConfig = isset($ci->config->item("heTransaksi_ui")[$jenisTr]) ? $ci->config->item("heTransaksi_ui")[$jenisTr] : array();
    $arrJenisTr = array();
    foreach ($trConfig["steps"] as $ii => $iiSpec) {
        $arrJenisTr[] = $iiSpec["target"];
    }
    $bulkUserMap = array();
    if ($jenisTr != NULL) {
        $cabangId = function_exists("my_cabang_id") ? my_cabang_id() : (isset($_SESSION['login']['cabang_id']) ? $_SESSION['login']['cabang_id'] : 0);
        $ci->load->model("Mdls/MdlAccessRight");
        $mdlAccessRight = new MdlAccessRight();
        //        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, $cabangId);
        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($arrJenisTr, NULL);
    }

    $stepIcon = isset($trConfig['icon']) ? $trConfig['icon'] : '';
    $iCtr = 0;
    $str .= "<ul class='timeline'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;
        $step_label = isset($steps[$i]) ? $steps[$i] : '';
        $step_target = isset($trConfig['steps'][$i]['target']) ? $trConfig['steps'][$i]['target'] : "-";
        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $bg_warna = "now-tl";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $bg_warna = "done-tl";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $bg_warna = "none";
            }
        }

        $userBadge = "";
        $titleAttr = "";
        if (isset($bulkUserMap[$step_target]) && !empty($bulkUserMap[$step_target])) {
            $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$step_target]));
            $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
            $userBadge = "<br><small style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
        }
        else {
            $titleAttr = " title='Hak Akses Belum Diatur.' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses Belum Diatur.'";
            $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i>Hak Akses Belum Diatur.</small>";
        }

        $str .= "<li class='$bg_warna no-margin' $titleAttr>";
        $str .= "<span>$step_label $userBadge</span>";
        $str .= "</li>";
    }
    $str .= "</ul>";

    return $str;
}

// END OF COMPLETE REPEATED LOGIC

function createStateHorizontalMapAuto($current = 0, $totalAvail = 0, $steps = array(), $jenisTr)
{
    $ci = &get_instance();
    $str = "";

    $trConfig = $ci->config->item("heTransaksi_ui")[$jenisTr];
    $totalAvail = sizeof($trConfig["steps"]);
    foreach ($trConfig["steps"] as $ii => $iiSpec) {
        $steps[$ii] = $iiSpec["label"];
    }
    $stepIcon = $trConfig['icon'];
    $iCtr = 0;
    $str .= "<ul class='timeline'>";
    for ($i = $current; $i <= $totalAvail; $i++) {
        $iCtr++;
        $step_label = $steps[$i];
        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $bg_warna = "none";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $bg_warna = "none";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $bg_warna = "none";
            }
        }

        $str .= "<li class='$bg_warna no-margin'>";
        $str .= "<span>AUTO $step_label</span>";
        $str .= "</li>";

    }
    $str .= "</ul>";

    return $str;
}

function heReturnDeviceName($dev)
{
    $splitKurung = explode("(", $dev);
    if (sizeof($splitKurung) > 1) {
        $rawDeviceName = $splitKurung[1];
        //        $rawBrowserName = $splitKurung[2];


        // <editor-fold defaultstate="collapsed" desc="cek nama device">
        if (preg_match("/Android/i", $rawDeviceName)) {
            $deviceName = "Android";
        }
        else {
            if (preg_match("/iPad/i", $rawDeviceName)) {
                $deviceName = "iPad";
            }
            else {
                if (preg_match("/iPhone/i", $rawDeviceName)) {
                    $deviceName = "iPhone";
                }
                else {
                    if (preg_match("/Windows/i", $rawDeviceName)) {
                        $deviceName = "PC";
                    }
                    else {
                        if (preg_match("/Mac/i", $rawDeviceName)) {
                            $deviceName = "Mac";
                        }
                        else {
                            $deviceName = "Ghost";
                        }
                    }
                }
            }
        }// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="cek nama browser">
        if (preg_match("/Firefox/i", $dev)) {
            $browserName = "Firefox";
        }
        else {
            if (preg_match("/Opera/i", $dev)) {
                $browserName = "Opera";
            }
            else {
                if (preg_match("/Edg/i", $dev)) {
                    $browserName = "Edge";
                }
                else {
                    if (preg_match("/Explorer/i", $dev)) {
                        $browserName = "IE";
                    }
                    else {
                        if (preg_match("/Chrome/i", $dev)) {
                            $browserName = "Chrome";
                        }
                        else {
                            if (preg_match("/Edg/i", $dev)) {
                                $browserName = "Edge";
                            }
                            else {
                                $browserName = "Ghost";
                            }
                        }
                    }
                }
            }
        }// </editor-fold>
        //echo "device: $deviceName<br>";
        //echo "browser: $browserName<br>";
        return array(
            "device" => $deviceName,
            "browser" => $browserName,
        );
    }
    else {
        return array(
            "device" => "unknown",
            "browser" => "unknown",
        );
    }

}

function writeLog($title, $sub_title, $category, $jenis = "", $uid = 0, $uname = "", $deskripsi_new = "", $deskripsi_old = "", $transaksi_id = 0)
{
    //    $session_login = $_SESSION['login'];
    // arrPrint($session_login);
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/" . "MdlActivityLog");
    $hTmp = new MdlActivityLog();
    $hTmp->setFilters(array());
    $tmpHData = array(
        "title" => $title,
        "sub_title" => $sub_title,
        "uid" => isset($session_login['id']) ? $session_login['id'] : $uid,
        "uname" => isset($session_login['nama']) ? $session_login['nama'] : $uname,
        "dtime" => date("Y-m-d H:i:s"),
        "transaksi_id" => $transaksi_id,
        "deskripsi_old" => base64_encode(serialize($deskripsi_old)),
        "deskripsi_new" => base64_encode(serialize($deskripsi_new)),
        "jenis" => $jenis,
        "ipadd" => $_SERVER['REMOTE_ADDR'],
        "devices" => $_SERVER['HTTP_USER_AGENT'],
        "category" => $category,
        "controller" => $ci->uri->segment(1),
        "method" => $ci->uri->segment(2),
        "url" => current_url(),
        "referer_url" => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "none",
        "ghost" => isset($session_login['ghost']) ? $session_login['ghost'] : "0",
    );
    $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
}

function writeLogToFile($directory, $file_name, $txt_message)
{

    $myfile = file_put_contents($directory . '/' . $file_name . '.txt', $txt_message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

//function heReturnISPName($ip = '')
//{
//    if ($ip == '')
//        $ip = $_SERVER['REMOTE_ADDR'];
//    $longisp = @gethostbyaddr($ip);
//    $isp = explode('.', $longisp);
//    $isp = array_reverse($isp);
//    $tmp = $isp[1];
//    if (preg_match("/\<(org?|com?|net)\>/i", $tmp)) {
//        $myisp = $isp[2] . '.' . $isp[1] . '.' . $isp[0];
//    } else {
//        $myisp = $isp[1] . '.' . $isp[0];
//    }
//    if (preg_match("/[0-9]{1,3}\.[0-9]{1,3}/", $myisp)) {
//        return 'Unknown';
//    } else {
//        return $myisp;
//    }
//}
function tplNoData($str = "")
{
    $var = "<div class='text-center font-size-2 margin-top-20 text-grey-1'>";
    $var .= "Tidak ada yang bisa ditampilkan";
    $var .= "<div class='bottom-space-20 font-size-0-7'>";
    if ($str != "") {
        $var .= "$str";
    }
    $var .= "</div>";
    $var .= "</div>";

    return $var;
}

function tplTableNoData($jml_kolom = "1", $str = "tidak ada data")
{
    $var = "";
    $var .= "<tr>";
    $var .= "<td colspan='$jml_kolom' align='center' class='text-grey-b'>$str</td>";
    $var .= "</tr>";

    return $var;
}

function headTpl()
{
    $css_version = defined('FCPATH') && file_exists(FCPATH . 'assets/custom/custom.min.css') ? @filemtime(FCPATH . 'assets/custom/custom.min.css') : time();
    $var = "<link rel=\"icon\" type=\"image/png\" href=\"" . base_url() . "public/images/sys/head_top.ico\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap-3.3.7-dist/css/bootstrap.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "Font-Awesome-master/css/font-awesome.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "ionicons-master/css/ionicons.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "simple-line-icons-master/css/simple-line-icons.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "AdminLTE-2.3.11/dist/css/AdminLTE.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap3-dialog-master/dist/css/bootstrap-dialog.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "AdminLTE-2.3.11/dist/css/skins/_all-skins.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "assets/bootstrap-fileinput-master/css/fileinput.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "/assets/bootstrap-toggle-master/css/bootstrap-toggle.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "/assets/custom/dialog.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "sweetalert2-master/dist/sweetalert2.min.css\">
        <link rel=\"stylesheet\" href=\"" . local_suport() . "bootstrap-xlgrid-master/bootstrap-xlgrid.min.css\">
        <link rel=\"stylesheet\" href=\"" . base_url() . "assets/custom/custom.min.css?v=" . $css_version . "\">
                
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>
        <script src=\"" . local_suport() . "bootstrap-3.3.7-dist/js/bootstrap.min.js\"></script>
        <script src=\"" . local_suport() . "sweetalert2-master/dist/sweetalert2.min.js\"></script>
        <script src=\"" . local_suport() . "bootstrap3-dialog-master/dist/js/bootstrap-dialog.min.js\"></script>
        <script src=\"" . base_url() . "/assets/custom/custom.js\"></script>
        
        <link rel=\"stylesheet\" href=\"" . local_suport() . "jquery-ui-themes-1.11.0/themes/smoothness/jquery-ui.css\"/>
        <script src=\"" . local_suport() . "jquery-ui-1.11.0/jquery-ui.min.js\"></script>
        ";

    return $var;
}

function footTpl()
{
    $var = "
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/plugins/slimScroll/jquery.slimscroll.min.js\"></script>
        <script src=\"" . local_suport() . "AdminLTE-2.3.11/dist/js/app.min.js\"></script>
        <script src=\"" . local_suport() . "multi-step-modal-master/multi-step-modal\"></script>
        <script src=\"" . base_url() . "assets/bootstrap-fileinput-master/js/fileinput.min.js\"></script>
        <script src=\"" . base_url() . "assets/bootstrap-toggle-master/js/bootstrap-toggle.min.js\"></script>
        <script src=\"" . base_url() . "assets/custom/hippo_ajax.js\"></script>
    ";

    return $var;
}

function blobDecode($blob)
{
    return blobDecodeSafe($blob, null, false);
}

function blobEncode($array)
{

    return base64_encode(serialize($array));
}

function encodeRequestPayload($payload)
{
    $json = json_encode($payload);
    if ($json === false || $json === null) {
        return "";
    }

    $b64 = base64_encode($json);
    $safe = strtr($b64, '+/', '-_');

    return rtrim($safe, '=');
}

function decodeRequestPayload($payload)
{
    if (!is_string($payload) || strlen($payload) < 1) {
        return null;
    }

    $normalized = strtr($payload, '-_', '+/');
    $padLength = strlen($normalized) % 4;
    if ($padLength > 0) {
        $normalized .= str_repeat('=', 4 - $padLength);
    }

    $decoded = base64_decode($normalized, true);
    if ($decoded === false) {
        return null;
    }

    return json_decode($decoded, true);
}

function blobDecodeSafe($blob, $default = array(), $arrayOnly = true)
{
    if (!is_string($blob) || $blob === "") {
        return $default;
    }

    $decoded = base64_decode($blob, true);
    if ($decoded === false) {
        return $default;
    }

    $unserialized = false;
    if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
        $unserialized = @unserialize($decoded, array('allowed_classes' => false));
    }
    else {
        // PHP 5.6 fallback: block serialized object payloads before unserialize.
        if (preg_match('/(^|[;{}])(?:O|C):\\d+:"/m', $decoded)) {
            return $default;
        }
        $unserialized = @unserialize($decoded);
    }
    if ($unserialized === false && $decoded !== 'b:0;') {
        $jsonDecoded = json_decode($decoded, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $unserialized = $jsonDecoded;
        }
        else {
            return $default;
        }
    }

    if ($arrayOnly && !is_array($unserialized)) {
        return $default;
    }

    return $unserialized;
}

function blobDecodeGetArray($key, $default = array())
{
    if (!isset($_GET[$key])) {
        return $default;
    }

    return blobDecodeSafe($_GET[$key], $default, true);
}

function sanitizeBlobToken($token)
{
    if (!is_string($token)) {
        return '';
    }

    return preg_replace('/[^A-Za-z0-9+\/=]/', '', $token);
}

function sanitizeInternalRelativeUrl($url, $fallback = '')
{
    if (!is_string($url)) {
        return $fallback;
    }

    $url = trim($url);
    if ($url === '') {
        return $fallback;
    }

    if (strpos($url, "\0") !== false) {
        return $fallback;
    }

    if (preg_match('/^(?:javascript|data|vbscript|file):/i', $url)) {
        return $fallback;
    }

    if (strpos($url, '//') === 0) {
        return $fallback;
    }

    $parts = @parse_url($url);
    if ($parts === false) {
        return $fallback;
    }

    if (isset($parts['scheme']) || isset($parts['host'])) {
        return $fallback;
    }

    return $url;
}

function blobDecodeInternalUrl($blob, $fallback = '')
{
    $rawUrl = blobDecodeSafe($blob, '', false);
    if (!is_string($rawUrl) || $rawUrl === '') {
        return $fallback;
    }

    return sanitizeInternalRelativeUrl($rawUrl, $fallback);
}

function sanitizeModelClassName($name, $default = '')
{
    if (!is_string($name)) {
        return $default;
    }

    $name = trim($name);
    if ($name === '') {
        return $default;
    }

    if (!preg_match('/^Mdl[A-Za-z0-9_]+$/', $name)) {
        return $default;
    }

    return $name;
}

function isSafeSqlFilterFragment($filter)
{
    if (!is_string($filter)) {
        return false;
    }

    $filter = trim($filter);
    if ($filter === '') {
        return false;
    }

    if (preg_match('/(;|--|\/\*|\*\/|#|\x00)/', $filter)) {
        return false;
    }

    return true;
}

function sanitizeSearchKeyword($keyword, $maxLen = 100)
{
    if (!is_string($keyword)) {
        return '';
    }

    $keyword = trim($keyword);
    $keyword = str_replace(array("\0", "\r", "\n"), '', $keyword);

    $maxLen = (int)$maxLen;
    if ($maxLen < 1) {
        $maxLen = 100;
    }
    if (strlen($keyword) > $maxLen) {
        $keyword = substr($keyword, 0, $maxLen);
    }

    return $keyword;
}

function sanitizeFieldKey($key)
{
    if (!is_string($key)) {
        return '';
    }

    $key = trim($key);
    if ($key === '') {
        return '';
    }

    return preg_replace('/[^A-Za-z0-9_]/', '', $key);
}

function sanitizeInputValue($value, $maxLen = 1000)
{
    if (is_array($value) || is_object($value)) {
        return '';
    }

    $value = (string)$value;
    $value = str_replace(array("\0", "\r", "\n"), '', $value);

    $maxLen = (int)$maxLen;
    if ($maxLen < 1) {
        $maxLen = 1000;
    }
    if (strlen($value) > $maxLen) {
        $value = substr($value, 0, $maxLen);
    }

    return $value;
}

function gotoLogin()
{
    // START OF COMPLETE REPEATED LOGIC
    $curent = $_SERVER['REQUEST_URI'];
    $curent_e = blobEncode($curent);

    // Deteksi apakah request berasal dari tablet scanner (ismob=1)
    // Jika ya, tampilkan halaman sesi habis yang ramah — bukan form login biasa
    $ismob = isset($_GET['ismob']) ? $_GET['ismob'] : 0;
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $isScaner = (isset($_SESSION['login']['jenis']) && $_SESSION['login']['jenis'] == 'scaner');
    $dariMobile = ($ismob == 1 || strpos($referer, 'followupDariHp') !== false || strpos($referer, 'followupPreviewMobile') !== false || $isScaner);

    if ($dariMobile) {
        // Tampilkan halaman sesi habis dengan dua pilihan untuk scanner tablet
        $refreshUrl = base_url() . ltrim($curent, '/');
        $homeUrl = base_url();
        $html = "<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Sesi Habis</title>
<style>
  body { font-family: Arial, sans-serif; background: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
  .card { background: #fff; border-radius: 12px; padding: 32px 24px; max-width: 420px; width: 90%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
  .icon { font-size: 56px; margin-bottom: 12px; }
  h2 { color: #e74c3c; margin: 0 0 10px; font-size: 22px; }
  p { color: #555; font-size: 15px; line-height: 1.6; margin: 0 0 24px; }
  .divider { font-size: 12px; color: #bbb; margin: 14px 0; text-transform: uppercase; letter-spacing: 1px; }
  .btn-primary { display: block; background: #27ae60; color: #fff; border: none; border-radius: 8px; padding: 15px 24px; font-size: 16px; font-weight: bold; cursor: pointer; width: 100%; box-sizing: border-box; margin-bottom: 4px; }
  .btn-primary:active { background: #219150; }
  .btn-secondary { display: block; background: #ecf0f1; color: #555; border: 1px solid #ccc; border-radius: 8px; padding: 13px 24px; font-size: 15px; cursor: pointer; width: 100%; box-sizing: border-box; }
  .btn-secondary:active { background: #dde1e2; }
  .note { font-size: 12px; color: #aaa; margin-top: 6px; margin-bottom: 16px; }
</style>
</head>
<body>
<div class='card'>
  <div class='icon'>&#x23F0;</div>
  <h2>Sesi Anda Telah Habis</h2>
  <p>Anda tidak aktif terlalu lama sehingga sesi transaksi otomatis berakhir.<br>Pilih salah satu pilihan di bawah:</p>

  <button class='btn-primary' onclick=\"location.href='$refreshUrl'\">&#x21BA; Lanjutkan Data Lama</button>
  <div class='note'>Data yang sudah diinput sebelumnya akan tetap tersimpan</div>

  <div class='divider'>atau</div>

  <button class='btn-secondary' onclick=\"location.href='$homeUrl'\">&#x2B55; Mulai dari Awal</button>
  <div class='note'>Kembali ke halaman utama dan mulai transaksi baru</div>
</div>
</body>
</html>";
        die($html);
    }

    // Request dari desktop/browser biasa: redirect ke halaman login
    redirect(base_url() . "auth/Login?xxx=$curent_e");
    //    redirect(base_url() . "Login/authLogout?xxx=$curent_e");
    mati_disini(__LINE__ . "");
    // END OF COMPLETE REPEATED LOGIC
}

function imageSizeAllow()
{
    $max_size = 10000000;

    return $max_size;
}

function rename_array_key($array, $old_key, $new_key)
{
    if (!array_key_exists($old_key, $array)) {
        return $array;
    }
    $new_array = array();
    foreach ($array as $key => $value) {
        $new_key = $old_key === $key ? $new_key : $key;
        $new_array[$new_key] = $value;
    }

    return $new_array;
}

// START OF COMPLETE REPEATED LOGIC
function validateUserSession($att)
{

    $ci = &get_instance();

    // region check transaction locker to prevent concurrent processing by different users
    $controller = $ci->uri->segment(2);
    if ((strpos(strtolower($controller), 'followup') !== false) && !empty($ci->session->login['id']) && $ci->session->login['ghost'] == 0) {
        $no = $ci->uri->segment(5);
        if (!empty($no)) {
            $no = rtrim($no, "-");
            $ci->load->model("Mdls/MdlLockerTransaksi");
            $lt = new MdlLockerTransaksi();
            $lt->setFilters(array());
            $lt->addFilter("transaksi_id='$no'");
            $ltTmp = $lt->lookupAll()->result();

            // Deteksi durasi locker timeout dari konfigurasi modul
            $moduleLockerTimeout = 300; // Default global 300 detik (5 menit)
            $modul = $ci->uri->segment(1);
            $jenisTr = $ci->uri->segment(4);
            $targetStepNum = $ci->uri->segment(6);

            $coUi = $ci->config->item("coTransaksiUi");
            if (empty($coUi) && !empty($modul)) {
                $uiConfigFile = APPPATH . "modules/" . $modul . "/config/coTransaksiUi.php";
                if (file_exists($uiConfigFile)) {
                    $ci->load->config("../../modules/" . $modul . "/config/coTransaksiUi", false, true);
                    $coUi = $ci->config->item("coTransaksiUi");
                }
            }

            if (!empty($coUi) && !empty($jenisTr) && isset($coUi[$jenisTr])) {
                if (!empty($targetStepNum) && isset($coUi[$jenisTr]['steps'][$targetStepNum]['lockerTimeout'])) {
                    $moduleLockerTimeout = intval($coUi[$jenisTr]['steps'][$targetStepNum]['lockerTimeout']);
                }
                elseif (isset($coUi[$jenisTr]['lockerTimeout'])) {
                    $moduleLockerTimeout = intval($coUi[$jenisTr]['lockerTimeout']);
                }
                elseif (!empty($targetStepNum) && !empty($coUi[$jenisTr]['steps'][$targetStepNum]['allowScaner'])) {
                    $moduleLockerTimeout = 1800; // Default 30 menit jika step bertipe scanner
                }
            }

            // Batas waktu tidak aktif (idle threshold) sebelum kunci dapat diambil alih pengguna lain
            // Default 5 menit, atau mengikuti konfigurasi modul jika diset lebih lama
            $idleThresholdMinutes = 5;
            if ($moduleLockerTimeout >= 60) {
                $idleThresholdMinutes = ceil($moduleLockerTimeout / 60);
            }

            $is_held_by_other = false;
            $holder_name = "";
            $holder_id = "";
            $holder_lock_time = "";
            $holder_expires_at = "";
            $currentTime = dtimeNow();
            $myId = $ci->session->login['id'];

            foreach ($ltTmp as $ltSpec) {
                if ($ltSpec->state == "hold") {
                    if ($ltSpec->oleh_id == $myId) {
                        // Kunci dipegang oleh user aktif saat ini.
                        // Selalu refresh keaktifan (heartbeat/last_access) dan pastikan status hold aktif (jumlah = '1')
                        // TIDAK ADA AUTO-RELEASE PASIF: Kunci tidak boleh dilepas otomatis oleh sistem
                        $ci->db->where(array(
                            "id" => $ltSpec->id,
                            "transaksi_id" => $no,
                            "state" => "hold",
                        ))->update("stock_locker_transaksi", array(
                            "jumlah" => "1",
                            "locked_at" => !empty($ltSpec->locked_at) ? $ltSpec->locked_at : $currentTime,
                            "last_access" => $currentTime,
                            "heartbeat_at" => $currentTime,
                            "owner_session_id" => session_id(),
                            "owner_ip" => $ci->input->ip_address(),
                            "released_at" => null,
                            "released_by" => null,
                        ));
                    }
                    elseif ($ltSpec->jumlah == "1") {
                        // Kunci dipegang oleh user lain yang masih aktif
                        // TIDAK ADA AUTO-RELEASE: Kunci hanya bisa dilepas bila user kedua mengambil alih (forceRetakeLock)
                        $is_held_by_other = true;
                        $holder_name = $ltSpec->oleh_nama;
                        $holder_id = $ltSpec->oleh_id;
                        $holder_lock_time = !empty($ltSpec->last_access) ? $ltSpec->last_access : (!empty($ltSpec->locked_at) ? $ltSpec->locked_at : (!empty($ltSpec->dtime) ? $ltSpec->dtime : ""));
                        $holder_expires_at = !empty($ltSpec->expires_at) ? $ltSpec->expires_at : "";
                    }
                }
            }

            if ($is_held_by_other) {
                $holder_ip = "-";
                $holder_device = "-";
                $holder_last_active = "-";
                $inactive_minutes = 0;

                if (!empty($holder_id)) {
                    $ci->load->model("Mdls/MdlEmployee");
                    $empObj = new MdlEmployee();
                    $empObj->setFilters(array());
                    $empQuery = $empObj->lookupByCondition(array("id" => $holder_id))->result();
                    if (sizeof($empQuery) > 0) {
                        if (!empty($empQuery[0]->nama)) {
                            $holder_name = $empQuery[0]->nama;
                        }
                        if (!empty($empQuery[0]->ipadd)) {
                            $holder_ip = $empQuery[0]->ipadd;
                        }
                        if (!empty($empQuery[0]->devices)) {
                            $deviceInfo = heReturnDeviceName($empQuery[0]->devices);
                            $holder_device = $deviceInfo['device'] . " / " . $deviceInfo['browser'];
                        }
                        if (!empty($empQuery[0]->last_dtime_active)) {
                            $holder_last_active = $empQuery[0]->last_dtime_active;
                        }
                    }
                }

                // Perhitungan waktu idle berdasarkan kapan transaksi dikunci (fallback ke keaktifan user)
                $lock_benchmark_time = !empty($holder_lock_time) ? $holder_lock_time : $holder_last_active;
                $lock_duration_text = "-";
                if (!empty($lock_benchmark_time) && $lock_benchmark_time !== "-") {
                    $inactive_seconds = timeDiff($lock_benchmark_time, dtimeNow());
                    $inactive_minutes = floor($inactive_seconds / 60);
                    $lock_duration_text = ($inactive_minutes > 0) ? $inactive_minutes . " menit yang lalu" : "baru saja";
                }

                // START OF COMPLETE REPEATED LOGIC
                // Region deteksi pemanggilan JSON API murni vs fragment HTML / direct page
                $isPureJson = false;
                if (isset($_GET['format']) && strtolower($_GET['format']) === 'json') {
                    $isPureJson = true;
                }
                elseif (isset($_GET['ajax']) && ($_GET['ajax'] == '1' || strtolower($_GET['ajax']) === 'true')) {
                    $isPureJson = true;
                }
                elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                    $isPureJson = true;
                }
                elseif (in_array($ci->uri->segment(3), array('followupMultiPrePreview', 'doFollowupMulti', 'previewGunggung')) && !isset($_GET['forceRetakeLock'])) {
                    $isPureJson = true;
                }

                // If force retake is requested AND user/transaction has been inactive for at least threshold minutes
                if (isset($_GET['forceRetakeLock']) && $_GET['forceRetakeLock'] == 1 && $inactive_minutes >= $idleThresholdMinutes) {
                    $ci->load->model("Coms/ComLockerTransaksi");
                    $subParams = array(
                        "static" => array(
                            "cabang_id" => $ci->session->login['cabang_id'],
                            "gudang_id" => 0,
                            "jenis" => "transaksi",
                            "jenis_locker" => "transaksi",
                            "state" => "hold",
                            "jumlah" => -1,
                            "produk_id" => $no,
                            "oleh_id" => $holder_id,
                            "transaksi_id" => $no,
                        ),
                    );
                    $ss = new ComLockerTransaksi();
                    $ss->pair(array($subParams)) or die("Unable to pair locker for retaking");
                    $ss->exec();

                    // Force set to 0 in db
                    $lt->setFilters(array());
                    $lt->updateData(
                        array(
                            "transaksi_id" => $no,
                            "state" => "hold",
                            "oleh_id" => $holder_id
                        ),
                        array(
                            "jumlah" => "0",
                            "released_at" => dtimeNow(),
                            "released_by" => $ci->session->login['id'],
                        )
                    );

                    if ($isPureJson) {
                        if (!headers_sent()) {
                            header("Content-Type: application/json; charset=UTF-8");
                            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                            header("Pragma: no-cache");
                        }
                        echo json_encode(array(
                            "success" => 1,
                            "hasData" => 0,
                            "message" => "Kunci transaksi berhasil diambil alih. Silakan coba kembali."
                        ));
                        exit();
                    }

                    // Redirect back clean jika diakses langsung lewat browser (bukan AJAX)
                    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                    if (!empty($referer) && strpos($referer, 'followupMultiPrePreview') === false) {
                        header("Location: " . $referer);
                        exit();
                    }
                    else {
                        $targetModul = !empty($modul) ? $modul : 'taxes';
                        $targetJenis = !empty($jenisTr) ? $jenisTr : '110';
                        header("Location: " . base_url() . $targetModul . "/Transaksi/viewUndoneItemsIndex2/" . $targetJenis);
                        exit();
                    }
                }

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $fullUrl = $protocol . $host . $_SERVER['REQUEST_URI'];
                if (strpos($fullUrl, '?') !== false) {
                    $retakeUrl = $fullUrl . '&forceRetakeLock=1';
                }
                else {
                    $retakeUrl = $fullUrl . '?forceRetakeLock=1';
                }
                $displayNomer = $no;
                $ci->load->model("MdlTransaksi");
                $trObj = new MdlTransaksi();
                $trObj->setFilters(array());
                $trObj->addFilter("id='$no'");
                $trRes = $trObj->lookupAll()->result();
                if (sizeof($trRes) > 0 && !empty($trRes[0]->nomer)) {
                    $displayNomer = $trRes[0]->nomer;
                }

                // START OF COMPLETE REPEATED LOGIC
                if ($inactive_minutes >= $idleThresholdMinutes) {
                    $retakeBtnHtml = "<button type='button' id='btn_force_retake_lock' class='btn_force_retake_lock' data-url='" . htmlspecialchars($retakeUrl) . "' onclick='(top.handleRetakeLock||window.handleRetakeLock||function(b){})(this)' style='display: inline-block; flex: 1; padding: 12px 16px; background-color: #d97706; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; box-sizing: border-box; cursor: pointer;'>⚠️ Ambil Alih Tugas/Penyelesaian Ini</button>";
                }
                else {
                    $remaining = $idleThresholdMinutes - $inactive_minutes;
                    $retakeBtnHtml = "<button type='button' disabled style='display: inline-block; flex: 1; padding: 12px 16px; background-color: #e5e7eb; color: #9ca3af; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; text-align: center; box-sizing: border-box; cursor: not-allowed;'>Ambil Alih Tersedia dalam " . $remaining . " Menit</button>";
                }
                // END OF COMPLETE REPEATED LOGIC

                $lockedCardHtml = "<div id='locked_card_container' style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; text-align: center; padding: 15px 10px; color: #1f2937; box-sizing: border-box; max-width: 480px; margin: 0 auto;'>
    <div style='width: 60px; height: 60px; background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 28px;'>
        🔒
    </div>
    <h3 style='margin: 0 0 8px 0; font-size: 22px; font-weight: 700; color: #92400e; letter-spacing: -0.5px;'>
        Tugas/Penyelesaian Terkunci
    </h3>
    <p style='margin: 0 0 16px 0; font-size: 14px; color: #4b5563; line-height: 1.5;'>
        Tugas/penyelesaian dengan nomor <b style='color: #111827;'>" . htmlspecialchars($displayNomer) . "</b> sedang diproses oleh <br><b style='color: #111827;text-transform: uppercase;font-size: 18px;'>" . htmlspecialchars($holder_name) . "</b>.
    </p>

    <p style='margin: 0 0 16px 0; font-size: 18px; color: #181ab8ff; line-height: 1.5;'>
        Klik tombol di bawah untuk mengambil alih tugas/penyelesaian
    </p>

    <details style='margin-bottom: 20px; text-align: left;'>
        <summary style='cursor: pointer; color: #6b7280; font-size: 12px; font-weight: 600; padding: 6px 10px; border-radius: 6px; background-color: #f3f4f6; text-align: center; list-style: none; outline: none; user-select: none;'>
            ℹ️ Lihat Informasi Detail ▾
        </summary>
        <table style='width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-top: 10px; text-align: left; font-size: 13px; background-color: #ffffff;'>
            <tr style='background-color: #f9fafb;'>
                <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; width: 38%; border-bottom: 1px solid #e5e7eb;'>Petugas Aktif</td>
                <td style='padding: 10px 14px; font-weight: 700; color: #111827; border-bottom: 1px solid #e5e7eb;'>" . htmlspecialchars($holder_name) . " <span style='font-weight: 400; color: #6b7280;'>(ID: " . htmlspecialchars($holder_id) . ")</span></td>
            </tr>
            <tr>
                <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e5e7eb;'>Mulai Terkunci</td>
                <td style='padding: 10px 14px; font-weight: 600; color: #b45309; border-bottom: 1px solid #e5e7eb;'>" . htmlspecialchars($holder_lock_time) . " <span style='font-weight: 400; color: #6b7280;'>(" . htmlspecialchars($lock_duration_text) . ")</span></td>
            </tr>
            <tr style='background-color: #f9fafb;'>
                <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e5e7eb;'>Aktif Terakhir User</td>
                <td style='padding: 10px 14px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;'>" . htmlspecialchars($holder_last_active) . "</td>
            </tr>
            <tr>
                <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e5e7eb;'>IP Address</td>
                <td style='padding: 10px 14px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;'>" . htmlspecialchars($holder_ip) . "</td>
            </tr>
            <tr style='background-color: #f9fafb;'>
                <td style='padding: 10px 14px; font-weight: 600; color: #6b7280;'>Perangkat</td>
                <td style='padding: 10px 14px; font-weight: 600; color: #374151;'>" . htmlspecialchars($holder_device) . "</td>
            </tr>
        </table>
    </details>

    <div style='display: flex; flex-direction: row; gap: 10px; justify-content: center;'>        
        <button type='button' id='btn_locked_modal_close' class='btn_locked_modal_close' style='display: inline-block; flex: 1; padding: 12px 16px; background-color: #969aa0ff; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; box-sizing: border-box; cursor: pointer;'>
            ↺ Batal (Lihat Tugas/Penyelesaian Lain)
        </button>
        " . $retakeBtnHtml . "
    </div>
</div>";

                if ($isPureJson) {
                    if (!headers_sent()) {
                        header("Content-Type: application/json; charset=UTF-8");
                        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                        header("Pragma: no-cache");
                    }
                    $jsonResponse = array(
                        "hasData" => 0,
                        "success" => 0,
                        "locked" => 1,
                        "data" => $no,
                        "nomer" => $displayNomer,
                        "holder_name" => $holder_name,
                        "holder_id" => $holder_id,
                        "holder_ip" => $holder_ip,
                        "holder_device" => $holder_device,
                        "holder_last_active" => $holder_last_active,
                        "lock_time" => $lock_benchmark_time,
                        "inactive_minutes" => $inactive_minutes,
                        "can_retake" => ($inactive_minutes >= 5) ? 1 : 0,
                        "retake_url" => isset($retakeUrl) ? $retakeUrl : "",
                        "reason" => "Transaksi dengan nomor \"" . $displayNomer . "\" sedang diproses oleh " . $holder_name . " (terkunci sejak " . $lock_duration_text . ").",
                        "html" => $lockedCardHtml,
                    );
                    echo json_encode($jsonResponse);
                    exit();
                }

                $html = "<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<title>Transaksi Sedang Diproses</title>
</head>
<body style='margin:0; padding:20px; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif; background:#f9fafb;'>
" . $lockedCardHtml . "
<script type='text/javascript'>
    function closeLockedModalNow() {
        if (typeof top !== 'undefined' && top.swal && typeof top.swal.close === 'function') {
            try { top.swal.close(); } catch(e){}
        }
        if (typeof top !== 'undefined' && top.BootstrapDialog && typeof top.BootstrapDialog.closeAll === 'function') {
            try { top.BootstrapDialog.closeAll(); } catch(e){}
        }
        var targets = [window.top, window.parent, window];
        for (var i = 0; i < targets.length; i++) {
            if (targets[i] && targets[i].$) {
                targets[i].$('.sweet-alert, .sweet-overlay, .swal2-container, .modal-backdrop').hide().remove();
                targets[i].$('body').removeClass('stop-scrolling modal-open swal2-shown');
            }
        }
    }

    (function() {
        function showLockedModal() {
            try {
                if (typeof top !== 'undefined' && top && top.HoldOn && typeof top.HoldOn.close === 'function') {
                    top.HoldOn.close();
                }
                if (typeof top !== 'undefined' && top && typeof top.close_holdon === 'function') {
                    top.close_holdon();
                }
                var targets = [window.top, window.parent, window];
                for (var i = 0; i < targets.length; i++) {
                    if (targets[i] && targets[i].$) {
                        targets[i].$('.holdon-overlay, .holdon, #holdon-overlay, .loading-overlay, .modal-backdrop').remove();
                        targets[i].$('body').removeClass('holdon-loading');
                    }
                }
            } catch (e) {}

            var cardElement = document.getElementById('locked_card_container');
            if (cardElement) {
                var contentHtml = cardElement.outerHTML;

                // Clear inline #result so background page is NOT polluted or shifted
                var resultElem = document.getElementById('result');
                if (resultElem) {
                    resultElem.innerHTML = '';
                }

                if (typeof top !== 'undefined' && top.swal) {
                    top.swal({
                        title: '',
                        html: contentHtml,
                        showConfirmButton: false,
                        width: '500px'
                    });
                } else if (typeof swal !== 'undefined') {
                    swal({
                        title: '',
                        html: contentHtml,
                        showConfirmButton: false,
                        width: '500px'
                    });
                } else if (typeof top !== 'undefined' && top.BootstrapDialog) {
                    top.BootstrapDialog.show({
                        title: '⚠️ Transaksi Terkunci',
                        message: $(contentHtml),
                        type: top.BootstrapDialog.TYPE_WARNING,
                        size: top.BootstrapDialog.SIZE_NORMAL
                    });
                }
            }
        }

        showLockedModal();
        setTimeout(showLockedModal, 100);
    })();
</script>
</body>
</html>";
                die($html);
                // END OF COMPLETE REPEATED LOGIC
            }
        }
    }
    // endregion

    $ci->load->model("Mdls/" . "MdlEmployee");
    $ci->load->config("heWebs");
    $webLogin = $ci->config->item('logins');
    $o = new MdlEmployee();

    /* =================================================================================================
     * menambahakn id yg diperbolehkan multy session diluar user ghost di config heWebs ['multySessionLogin']
     * =================================================================================================*/

    $multiSessions = $webLogin['allowedMultySession'];
    $maintenance = $ci->config->item('maintenance');

    // arrPrint($multiSessions);
    $o->setFilters(array());
    $empKoloms = array(
        "id",
        "ghost",
        "nama",
        "last_dtime_active",
        "phpsessid",
        "ipadd",
        "devices",
    );

    $ci->db->select($empKoloms);
    $tmpUser = $o->lookupByCondition(array(
        "id" => $att,

    ))->result();

    if ((show_debuger() != 1) && ($maintenance != 0) && ($tmpUser[0]->ghost != 1)) {
        $logout = base_url() . "auth/Login/authLogout";
        // echo "tanpa debuger $logout";
        echo "<meta http-equiv='refresh' content=\"3;URL='$logout'\">";
    }
    else {
        /* =================================================
         * special id ghost tidak akan dibuat logout
         * ================================================*/
    }

    // arrPrint($tmpUser->ghost);
    // arrPrint($tmpUser[0]);

    /* ==========================================================================================
     * membuat timeStamp setiap kali terjadi reload aplikasi, untuk diperhitungakan idel timenya
     * =========================================================================================*/
    // region update last active
    $o->setFilters(array());
    $o->updateData(array("id" => $att), array("last_dtime_active" => dtimeNow()));
    // endregion update last active

    if ((key_exists($att, $multiSessions)) or ($tmpUser[0]->ghost == 1)) {
        /* ===========================================================================================
         * multy session dikenakan untuk user ghost
         * regular user jika menghendaki multy session harus ditambahan di config -> heWebs -> multySessionLogin
         * ===========================================================================================*/
    }
    else {

        if (sizeof($tmpUser) > 0) {
            $sesDef = $tmpUser[0]->phpsessid;
            $ipadd = $tmpUser[0]->ipadd;
            $devices = $tmpUser[0]->devices;
            $sesNow = isset($ci->session->userdata['login']['phpsessid']) ? $ci->session->userdata['login']['phpsessid'] : '';

            if ($sesDef != $sesNow) {
                if (empty($sesNow)) {
                    $ci->session->sess_destroy();
                    $unregList = array("PROED",
                        "BHED"
                    );
                    foreach ($unregList as $s) {
                        $_SESSION[$s] = NULL;
                        unset($_SESSION[$s]);
                    }
                    $warning = "Session is invalid.";
                    $_SESSION['errMsg'] = $warning;
                    $curent = $_SERVER['REQUEST_URI'];
                    $curent_e = blobEncode($curent);
                    $errMsg = blobEncode($warning);
                    redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
                    return;
                }

                // Jika user memilih melanjutkan di browser saat ini
                if (isset($_GET['continueSession']) && $_GET['continueSession'] == 1) {
                    $o->setFilters(array());
                    $o->updateData(
                        array("id" => $att),
                        array(
                            "last_dtime_active" => dtimeNow(),
                            "phpsessid" => $sesNow
                        )
                    );
                    writeLog("session extended", "User memilih Lanjutkan Data Lama via Refresh — phpsessid disinkronkan ke $sesNow", "auth");

                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                    $host = $_SERVER['HTTP_HOST'];
                    $fullUrl = $protocol . $host . $_SERVER['REQUEST_URI'];
                    $fullCleanUrl = str_replace(array('?continueSession=1&', '&continueSession=1', '?continueSession=1'), array('?', '', ''), $fullUrl);
                    header("Location: " . $fullCleanUrl);
                    exit();
                }

                // Tampilkan halaman konfirmasi perbedaan sesi
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $fullUrl = $protocol . $host . $_SERVER['REQUEST_URI'];
                if (strpos($fullUrl, '?') !== false) {
                    $continueUrl = $fullUrl . '&continueSession=1';
                }
                else {
                    $continueUrl = $fullUrl . '?continueSession=1';
                }

                $logoutUrl = base_url() . "auth/Login/authLogout";
                $anggota_nama = $tmpUser[0]->nama;
                $browser_info = heReturnDeviceName($devices)['device'] . " / " . heReturnDeviceName($devices)['browser'];

                $html = "<!DOCTYPE html>
<html lang='id'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Perbedaan Sesi Terdeteksi</title>
<link href='https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap' rel='stylesheet'>
<style>
  body {
    font-family: 'Outfit', sans-serif !important;
    background: #0f172a !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 100vh !important;
    margin: 0 !important;
    overflow: auto !important;
  }
  .glass-card {
    background: #1e293b !important;
    border: 2px solid #334155 !important;
    border-radius: 20px !important;
    padding: 30px 25px !important;
    max-width: 480px !important;
    width: 95% !important;
    text-align: center !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
    animation: fadeIn 0.8s ease-out !important;
    box-sizing: border-box !important;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .icon-wrapper {
    width: 80px !important;
    height: 80px !important;
    background: rgba(231, 76, 60, 0.2) !important;
    border: 2px solid #e74c3c !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 20px !important;
    color: #e74c3c !important;
    font-size: 36px !important;
  }
  h2 {
    font-weight: 700 !important;
    font-size: 24px !important;
    margin: 0 0 15px !important;
    color: #f39c12 !important;
    letter-spacing: 0.5px !important;
  }
  p {
    color: #f8fafc !important;
    font-size: 15px !important;
    line-height: 1.6 !important;
    margin: 0 0 30px !important;
    font-weight: 500 !important;
  }
  .device-info {
    background: #0f172a !important;
    border-radius: 10px !important;
    padding: 12px 15px !important;
    margin-bottom: 25px !important;
    font-size: 13px !important;
    color: #ffffff !important;
    border-left: 4px solid #f39c12 !important;
    border-top: 1px solid #334155 !important;
    border-right: 1px solid #334155 !important;
    border-bottom: 1px solid #334155 !important;
    text-align: left !important;
  }
  .device-info strong {
    color: #f39c12 !important;
  }
  .btn-container {
    display: flex !important;
    flex-direction: column !important;
    gap: 12px !important;
  }
  .btn {
    width: 100% !important;
    padding: 15px 25px !important;
    border: none !important;
    border-radius: 10px !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    box-sizing: border-box !important;
  }
  .btn-primary {
    background: #27ae60 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4) !important;
  }
  .btn-primary:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(39, 174, 96, 0.6) !important;
    background: #219653 !important;
  }
  .btn-secondary {
    background: #334155 !important;
    color: #ffffff !important;
    border: 1px solid #475569 !important;
  }
  .btn-secondary:hover {
    background: #475569 !important;
    color: #ffffff !important;
    transform: translateY(-2px) !important;
  }
  .sub-note {
    font-size: 11px !important;
    color: #cbd5e1 !important;
    margin-top: -6px !important;
    margin-bottom: 12px !important;
    font-weight: 500 !important;
  }
</style>
</head>
<body>
<div class='glass-card'>
  <div class='icon-wrapper'>
    &#x26A0;
  </div>
  <h2>Perbedaan Sesi Terdeteksi</h2>
  <p>Hai <b>$anggota_nama</b>, terdeteksi perbedaan sesi login (Sesi Anda telah digunakan di perangkat/browser lain atau telah expired).</p>
  
  <div class='device-info'>
    <strong>Detail Login Terakhir:</strong><br>
    IP Address: $ipadd <br>
    Perangkat: $browser_info
  </div>

  <div class='btn-container'>
    <button class='btn btn-primary' onclick=\"location.href='$continueUrl'\">
      &#x21BA; Lanjutkan di Browser Ini
    </button>
    <div class='sub-note'>Sesi di browser ini akan menjadi sesi aktif utama Anda kembali</div>

    <button class='btn btn-secondary' onclick=\"location.href='$logoutUrl'\">
      &#x2B55; Mulai dari Awal
    </button>
    <div class='sub-note'>Keluar dari sistem dan hapus sesi saat ini</div>
  </div>
</div>
</body>
</html>";
                die($html);
            }
            else {
                //        die("else");
                return true;
            }
        }
        else {

            $warning = "";
            $warning .= "You have been kicked out because you session is no longer valid";
            $_SESSION['errMsg'] = $warning;
            $curent = $_SERVER['REQUEST_URI'];
            $curent_e = blobEncode($curent);
            $errMsg = blobEncode($warning);
            redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
        }
    }
}

// END OF COMPLETE REPEATED LOGIC

function validateUserPageSession($att)
{
    $timeLimit = 20;//menit;
    $ci = &get_instance();
    $ci->load->model("Mdls/" . "MdlEmployee");
    $o = new MdlEmployee();

    $o->setFilters(array());
    $tmpUser = $o->lookupByCondition(array(
        "id" => $att,

    ))->result();


    $ci->load->model("Mdls/" . "MdlActivityLog");
    $hTmp = new MdlActivityLog();
    $hTmp->setFilters(array());
    $hTmp->addFilter("uid='" . $att . "'");
    $ci->db->order_by("id", "desc");
    $ci->db->limit(1);
    $tmp = $hTmp->lookupAll()->result();

    $result = array(
        "page" => "",
        "time" => "",
        "url" => "",
    );
    if (sizeof($tmp) > 0) {
        $result = array(
            "page" => $tmp[0]->controller . "/" . $tmp[0]->method,
            "time" => $tmp[0]->dtime,
            "url" => $tmp[0]->url,
        );
    }


    $limitedPageConfig = $ci->config->item("validatedPages");
    if (in_array($result['page'], $limitedPageConfig)) {

        $awal = date_create($result['time']);
        $akhir = date_create(); // waktu sekarang
        $diff = date_diff($awal, $akhir);
        $selisih = $diff->i;
        //        echo "selisih: $selisih";
        //        echo lgShowAlert("page ".$result['page']." needs to be validated each $timeLimit (now $selisih)");
        if ($selisih >= $timeLimit) {

            $warning = "";
            $warning .= "[" . date("H:i") . "]. Your idle time in " . $result['page'] . " has reached the limit of " . ($timeLimit) . " minutes. Please re-login to use the system again.";
            $_SESSION['errMsg'] = $warning;
            $curent = $result['url'];
            $curent_e = blobEncode($curent);
            $errMsg = blobEncode($warning);
            redirect(base_url() . "auth/Login/authLogout?xxx=$curent_e&e=$errMsg");
        }
    }
    else {
        //        echo lgShowAlert("halaman TIDAK ini perlu divalidasi");
    }


    return $result;


}

function getDefaultWarehouseID($cabangID, $deviceID = 0)
{

    if ($cabangID == "-1") {
        $gudangID = "-1";
        //        $gudangName = "default center warehouse";
        $gudangName = "Gudang Reguler";
    }
    else {
        if ($deviceID > 0) {
            $gudangID = ($cabangID * -10) - ($deviceID);
            $gudangName = "sub-branch #$cabangID/$gudangID";
        }
        else {
            $gudangID = ($cabangID * -10);
            //            $gudangName = "default warehouse at branch #$cabangID";
            $gudangName = "Gudang Reguler Cabang #$cabangID";
        }
    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function getPOSWarehouseID($posID, $cabangID, $deviceID = 0)
{

    if ($cabangID == "-1") {
        $gudangID = "-1";
        $gudangName = "default center warehouse";
    }
    else {
        if ($deviceID > 0) {
            //            $gudangID = ($cabangID * -10)-($deviceID);
            //            $gudangName = "sub-branch #$cabangID/$gudangID";

            $gudangID = ($cabangID * -10) . ($posID);
            $gudangName = "sbr #$gudangID";
        }
        else {
            $gudangID = ($cabangID * -10);
            $gudangName = "default warehouse at branch #$cabangID";
        }


        //        $gudangID = ($cabangID * -10);
        //        $gudangName = "default warehouse at branch #$cabangID";

    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}

function randomNumber($length)
{
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

function validate_EAN13Barcode($barcode)
{
    // check to see if barcode is 13 digits long
    if (!preg_match("/^[0-9]{13}$/", $barcode)) {
        return false;
    }

    $digits = $barcode;

    // 1. Add the values of the digits in the
    // even-numbered positions: 2, 4, 6, etc.
    $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];

    // 2. Multiply this result by 3.
    $even_sum_three = $even_sum * 3;

    // 3. Add the values of the digits in the
    // odd-numbered positions: 1, 3, 5, etc.
    $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];

    // 4. Sum the results of steps 2 and 3.
    $total_sum = $even_sum_three + $odd_sum;

    // 5. The check character is the smallest number which,
    // when added to the result in step 4, produces a multiple of 10.
    $next_ten = (ceil($total_sum / 10)) * 10;
    $check_digit = $next_ten - $total_sum;

    // if the check digit and the last digit of the
    // barcode are OK return true;
    if ($check_digit == $digits[12]) {
        return true;
    }

    return false;
}

function reversePpn($dp_nilai, $ppn_persen)
{
    $pokok_nilai = (($dp_nilai * 100) / (100 + $ppn_persen));
    $ppn_nilai = $pokok_nilai * ($ppn_persen / 100);

    $arrTemp = array(
        "ppn" => $ppn_nilai,
        "pokok" => $pokok_nilai,
    );

    return $arrTemp;


}

function dueDate($dtime, $top)
{
    $tanggal_detik = dtimeToSecond($dtime);
    $default_jatuh_tempo = dayToSecond($top);
    $hari_detik = $default_jatuh_tempo;

    $tanggal_hari_detik = $tanggal_detik + $hari_detik;
    $tgl_jatuh_tempo = date("Y-m-d", $tanggal_hari_detik);

    return $tgl_jatuh_tempo;
}

function print_r_reverse($in)
{
    $lines = explode("\n", trim($in));
    if (trim($lines[0]) != 'Array') {
        // bottomed out to something that isn't an array
        return trim($in);
    }
    else {
        // this is an array, lets parse it
        if (preg_match("/(\\s{5,})\\(/", $lines[1], $match)) {
            // this is a tested array/recursive call to this function
            // take a set of spaces off the beginning
            $spaces = $match[1];
            $spaces_length = strlen($spaces);
            $lines_total = count($lines);
            for ($i = 0; $i < $lines_total; $i++) {
                if (substr($lines[$i], 0, $spaces_length) == $spaces) {
                    $lines[$i] = substr($lines[$i], $spaces_length);
                }
            }
        }
        array_shift($lines);
        // Array
        array_shift($lines);
        // (
        array_pop($lines);
        // )
        $in = implode("\n", $lines);
        // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
        preg_match_all("/^\\s{4}\\[(.+?)\\] \\=\\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        $pos = array();
        $previous_key = '';
        $in_length = strlen($in);
        // store the following in $pos:
        // array with key = key of the parsed array's item
        // value = array(start position in $in, $end position in $in)
        foreach ($matches as $match) {
            $key = $match[1][0];
            $start = $match[0][1] + strlen($match[0][0]);
            $pos[$key] = array($start,
                $in_length
            );
            if ($previous_key != '') {
                $pos[$previous_key][1] = $match[0][1] - 1;
            }
            $previous_key = $key;
        }
        $ret = array();
        foreach ($pos as $key => $where) {
            // recursively see if the parsed out value is an array too
            $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
        }

        return $ret;
    }
}

function digit_2($num)
{
    $ncGlobal = sprintf("%02d", $num);
    return $ncGlobal;
}

function digit_3($num)
{
    $digits = array(
        1 => "00",
        2 => "0"
    );
    $dGlobal = sizeof(str_split($num * 1, 1));
    $ncGlobal = "";
    $ncGlobal = sprintf("%03d", $num);
    return $ncGlobal;
}

function digit_4($num)
{
    $digits = array(
        1 => "000",
        2 => "00",
        3 => "0",
    );
    $dGlobal = sizeof(str_split($num * 1, 1));
    $ncGlobal = "";
    $ncGlobal = sprintf("%04d", $num);
    return $ncGlobal;
}

function digit_5($num)
{
    $digits = array(
        1 => "0000",
        2 => "000",
        3 => "00",
        4 => "0",
        // 5 => "0",
    );
    // $explode = explode("-",$fieldValue);

    // $cGlobal = end($explode);
    $dGlobal = sizeof(str_split($num * 1, 1));
    // cekHere($dGlobal);
    $ncGlobal = "";
    // $ncGlobal = key_exists($dGlobal,$digits) ? $digits[$dGlobal].$num : $num;
    $ncGlobal = sprintf("%05d", $num);

    // $newFormat = str_replace($cGlobal,$ncGlobal,$fieldValue);
    return $ncGlobal;
}

function customCounters($tmps, $code)
{
    if (sizeof($tmps)) {
        if (isset($tmps[0]->counters)) {
            $counterTop = blobDecode($tmps[0]->counters);
            // arrPrint($counterTop);
            $vars = array();
            foreach ($counterTop as $c_key => $c_val) {
                foreach ($c_val as $cc_key => $cc_val) {
                    $vars[$cc_key] = $cc_val;
                }
            }
        }
    }
    //    arrPrint($code);
    if (is_array($code)) {
        $hasil = "";
        foreach ($code as $ky => $item) {

            $var = "$item";
            if ($hasil == "") {
                $hasil = "$var";
            }
            else {
                $hasil = $hasil . "|" . $var;
            }


            $key = $hasil;
        }
        //        arrPrintWebs($key);

        return $vars[$key];
    }
    else {
        $key = $code;
        //        arrPrint($key);

        return $vars[$key];
    }


}

function validateDueDate($id, $dtime)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCustomer");
    $ci->load->model("MdlTransaksi");
    $c = new MdlCustomer();
    //    $c->addFilter("id='$id'");
    $customersData = $c->lookupByID($id)->result();
    $maxLimit = $customersData[0]->kredit_limit;
    //paggil due date transaksi
    $tr = new MdlTransaksi();

    $tr->setFilters(array());
    $trDue = $tr->lookupDueDate($id);
    if (isset($trDue["transaksi_id"]) && $trDue["transaksi_id"] > 0) {
        $tr->setFilters(array());
        $tr->addFilter("id='" . $trDue['transaksi_id'] . "'");
        $topTransaction = $tr->lookupMainTransaksi()->result();
        $nomer_top = formatField("nama", $topTransaction[0]->nomer_top);
    }

    //    arrPrint($topTransaction);

    $data = array();
    if ($trDue['due_date'] > 0) {
        $due = strtotime($trDue['due_date']);
        $now = strtotime($dtime);
        $nomer = formatField("nama", $trDue['nomer']);
        //        cekHere($trDue['due_date']."||$due||$now");
        if ($now < $due) {
            $data = array(
                "allow_create" => "true",
                "error" => "",
            );
        }
        else {
            $data = array(
                "allow_create" => "false",
                "error" => "transaksi tidak dapat dilanjutkan karena, <br> $nomer_top ($nomer) <br>Over due <br> silahkan hubungi bapak Hindra untuk minta otorisasi overdue",
            );
        }


    }
    else {
        $data = array(
            "allow_create" => "true",
            "error" => "",
        );
    }

    return $data;
    //    arrPrint($trDue);
}

function validateOverDue($id)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlOverDuePass");
    $o = new MdlOverDuePass();
    $o->addFilter("customers_id='$id'");
    $o->addFilter("jenis='forever'");
    $temp = $o->lookupAll()->result();
    // cekLime($ci->db->last_query());
    // arrPrint($temp);
    $status = 0;
    if (sizeof($temp) > 0) {
        $status = $temp['0']->status;
    }

    $data = array();
    if ($status > 0) {
        $data = array(
            "status" => "allowed",

        );
    }
    else {
        $data = array(
            "status" => "suspended",

        );
    }
    return $data;

}

/* ==========================================================================================================
    bila parameter step dan format tidak diisi akan mengeluarkan seluruh step dan nomernya dalam format array
 * ==========================================================================================================*/
function showHistoriGlobalNumbers($ids_his, $step = "", $format = false, $jenis_master = "")
{
    $ci = &get_instance();
    $masterConfigUi = $ci->config->item("heTransaksi_ui");
    $modul = isset($masterConfigUi[$jenis_master]['modul']) ? $masterConfigUi[$jenis_master]['modul'] : "";
    $modul_path = base_url() . "$modul/";

    $idHist = blobDecode($ids_his);


    $allNumbers = array();
    if (is_array($idHist)) {

        foreach ($idHist as $stepnya => $items) {
            $nomer = $items['nomer'];
            // arrPrint($items);
            // cekMerah($step . ":: $nomer");
            $cgHist = showGlobalNumber($items['counters'], $nomer);


            if ($format == true) {
                // $nomer_global = formatField("nomer", $nomer) . "&#x2011;" . $cgHist;
                $nomer_global = formatField_he_format("nomer", $nomer, $jenis_master, $modul_path) . "&#x2011;" . $cgHist;
            }
            else {

                $nomer_global = $nomer . "&#x2011;" . $cgHist;
            }

            // cekHijau($nomer_global);

            $allNumbers[$stepnya] = $nomer_global;
        }
    }

    $var = "";
    if ($step == "") {
        // cekHijau(__METHOD__);
        $var = $allNumbers;
        // $var = (object)$allNumbers;
    }
    else {
        $var = isset($allNumbers[$step]) ? $allNumbers[$step] : "";
    }

    return $var;
}

/* ==========================================================================================================
 * $counters,$nomer ngambil apaadanya dari isi kolom dB
 * ==========================================================================================================*/
function showGlobalNumber($counters, $nomer)
{
    is_array($counters) ? matiHere("var counters terdeteksi array :: " . __FILE__ . " @" . __LINE__) : "";

    $counters = blobDecode($counters);

    $cNomerExpl = explode(".", $nomer);
    $cTrcode = $cNomerExpl[0];
    $cTrplace = $cNomerExpl[1];
    $cgJenis = "$cTrcode|$cTrplace";

    $var = digit_5($counters["stepCode|placeID"]["$cgJenis"]);

    return $var;
}

function sendToSession($hippo_link, $wadah)
{
    $var = "getData('$hippo_link?v='+this.value+'&n='+this.name,'$wadah')";
    return $var;
}

function definitionButton()
{
    $result = array(
        "safely delete" => "transaksi mundur 1 langkah dan dilakukan oleh pembuat transaksi.",
        "undo" => "transaksi mundur 1 langkah dan dilakukan oleh otorisator (yang melakukan otorisasi) transaksi.",
        "reject 1 step" => "transaksi mundur 1 langkah dan dilakukan oleh pihak selanjutnya atau yang lebih tinggi dari rangkaian suatu transaksi.",
        "reject all steps" => "transaksi akan dimatikan sampai dengan requestnya dan tidak bisa digunakan lagi.",
        "close/fullfillment" => "menutup outstanding item untuk transaksi ini.",
    );

    return $result;
}

function sendNotif($TRid, $jenis, $msg)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlKirimNotif");
    $c = new MdlKirimNotif();
    $c->addFilter("tr_id='$TRid'");
    $c->addFilter("jenis='$jenis'");
    $tmp = $c->lookUpAll()->result();
    if (sizeof($tmp) > 0) {
        return true;
    }
    $payload = array(
        "tr_id" => $TRid,
        "jenis" => $jenis,
        "msg" => $msg,
        "tanggal" => date("Y-m-d"),
        "dtime" => date("Y-m-d H:i:s"),
        "ipadd" => isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "",
        "status" => 1,
    );
    $c = new MdlKirimNotif();
    $c->addData($payload);
    return true;
    //    arrPrint($trDue);
}

// ================================================= =================================================
function unbalanceNotif($trID, $mode, $data = array())
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlKirimNotifUnbalance");
    $c = new MdlKirimNotifUnbalance();
    $c->addFilter("transaksi_id='$trID'");
    $c->addFilter("mode='$mode'");
    $tmp = $c->lookUpAll()->result();
    if (sizeof($tmp) > 0) {
        return true;
    }
    if (sizeof($data) > 0) {
        $payload = array_merge(
            array(
                "transaksi_id" => $trID,
                "mode" => $mode,
                "tanggal" => date("Y-m-d"),
                "dtime" => date("Y-m-d H:i:s"),
                "ipadd" => isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "",
                "status" => 1,
            ),
            $data
        );
        $c = new MdlKirimNotifUnbalance();
        $c->addData($payload);
    }

    return true;
}

function niceDecimal($fieldVal)
{
    return str_replace('.00', '', number_format($fieldVal, 2));
}

function nestedLowercase($value)
{
    if (is_array($value)) {
        return array_map('nestedLowercase', $value);
    }
    return strtolower($value);
}

//----------------------------
function notifTransaksi()
{
    $str = "";
    $str .= "<span class='badge bg-red'>#</span>";
    $str .= "<span>&nbsp; notif merah: transaksi yang membutuhkan tindak lanjut dari id yang sedang login.</span>";
    $str .= "<br>";
    $str .= "<span class='badge bg-yellow'>#</span>";
    $str .= "<span>&nbsp; notif kuning: transaksi yang membutuhkan tindak lanjut dari pihak lain.</span>";

    return $str;
}

function createTree($datas)
{
}

// versi MODUL-----------------
function cCodeBuilderMisc($jenisTr)
{
    $var = "_TR_" . $jenisTr;

    return $var;
}

function createStateSign_he_misc($current, $totalAvail, $jenisTr = NULL, $configUi)
{
    $ci = &get_instance();
    $array_step_label = isset($configUi[$jenisTr]['steps']) ? $configUi[$jenisTr]['steps'] : array();


    $str = "";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            $str .= "<span class='fa fa-check-circle text-black'> $step_label</span><br>";
        }
        else {
            $str .= "<span class='fa fa-circle text-grey'> $step_label</span><br>";
        }
    }

    // tambahan next activity
    if ($jenisTr != NULL) {
        $next_step_number = $current + 1;
        $ci = &get_instance();
        $next_step_label = isset($configUi[$jenisTr]['steps'][$next_step_number]['label']) ? $configUi[$jenisTr]['steps'][$next_step_number]['label'] : "-";

        $str .= "<br>";
        $str .= "<span class='text-black' style='font-weight:bold;font-size:12px;'>Next Step:</span><br>";
        $str .= "<span class='text-red' style='font-weight:bold;font-size:12px;'>$next_step_label</span>";
    }


    return $str;
}

// START OF COMPLETE REPEATED LOGIC
function createStateHorizontal_he_misc($current, $totalAvail, $jenisTr = NULL, $showLabel = true, $configUi = array())
{
    $ci = &get_instance();
    $array_step_label = isset($configUi[$jenisTr]['steps']) ? $configUi[$jenisTr]['steps'] : array();

    $bulkUserMap = array();
    if ($jenisTr != NULL) {
        $cabangId = function_exists("my_cabang_id") ? my_cabang_id() : (isset($_SESSION['login']['cabang_id']) ? $_SESSION['login']['cabang_id'] : 0);
        $ci->load->model("Mdls/MdlAccessRight");
        $mdlAccessRight = new MdlAccessRight();
        //        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, $cabangId);
        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, NULL);
    }

    $str = "";
    $str .= "<ul class='timeline-kecil'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $step_label = isset($array_step_label[$i]['label']) ? $array_step_label[$i]['label'] : "-";
        if ($i <= $current) {
            $bg_warna = "done-tl";
        }
        elseif ($i == ($current + 1)) {
            $bg_warna = "now-tl";
        }
        else {
            $bg_warna = "none";
        }
        $labelDisplay = $showLabel == true ? "" : "style='display:none;'";

        $userBadge = "";
        $titleAttr = "";
        if (isset($bulkUserMap[$i]) && !empty($bulkUserMap[$i])) {
            $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$i]));
            $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
            $userBadge = "<br><small class='user-access-label' style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
        }

        $str .= "<li class='$bg_warna no-margin' $titleAttr>";
        $str .= "<span $labelDisplay>$step_label $userBadge</span>";
        $str .= "</li>";
    }
    $str .= "</ul>";

    return $str;
}

// END OF COMPLETE REPEATED LOGIC

function createStateMap_he_misc($current, $totalAvail, $steps, $jenisTr, $configUi)
{
    $ci = &get_instance();
    $str = "";

    $str .= "<span style='font-family:georgia;'>";
    $trConfig = $configUi[$jenisTr];
    $stepIcon = $trConfig['icon'];
    //    $str.="<span class='$stepIcon' style='font-size:1.3em;color:#ff7700;'></span> ";
    $iCtr = 0;
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;

        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $str .= "<span class='text-blue'>$numIcon " . $steps[$i] . "</span>";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $str .= "<span class='' style='color:#345678;'>$numIcon " . $steps[$i] . "</span>";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $str .= "<span class='text-grey'>$numIcon " . $steps[$i] . "</span>";
            }
        }
        if ($i < $totalAvail) {
            $str .= " <span class='fa fa-angle-double-right'> ";
        }

    }
    $str .= "</span>";

    return $str;
}

// START OF COMPLETE REPEATED LOGIC
function createStateHorizontalMap_he_misc($current, $totalAvail, $steps, $jenisTr, $configUi)
{
    $ci = &get_instance();
    $str = "";

    $bulkUserMap = array();
    if ($jenisTr != NULL) {
        $cabangId = function_exists("my_cabang_id") ? my_cabang_id() : (isset($_SESSION['login']['cabang_id']) ? $_SESSION['login']['cabang_id'] : 0);
        $ci->load->model("Mdls/MdlAccessRight");
        $mdlAccessRight = new MdlAccessRight();
        //        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, $cabangId);
        $bulkUserMap = $mdlAccessRight->getBulkUsersByJenisAndCabang($jenisTr, NULL);
    }

    $trConfig = isset($configUi[$jenisTr]) ? $configUi[$jenisTr] : array();
    $stepIcon = isset($trConfig['icon']) ? $trConfig['icon'] : '';
    $iCtr = 0;
    $str .= "<ul class='timeline'>";
    for ($i = 1; $i <= $totalAvail; $i++) {
        $iCtr++;
        $step_label = isset($steps[$i]) ? $steps[$i] : '';
        if ($i == $current) {
            $numIcon = "<span class='badge text-white bg-blue'>$i</span>";
            $bg_warna = "now-tl";
        }
        else {
            if ($i < $current) {
                $numIcon = "<span class='badge text-white' style='background:#345678;'>$i</span>";
                $bg_warna = "done-tl";
            }
            else {
                $numIcon = "<span class='badge text-white bg-gray'>$i</span>";
                $bg_warna = "none";
            }
        }

        $userBadge = "";
        $titleAttr = "";
        if (isset($bulkUserMap[$i]) && !empty($bulkUserMap[$i])) {
            $userNamesStr = htmlspecialchars(implode(", ", $bulkUserMap[$i]));
            $titleAttr = " title='Hak Akses: " . $userNamesStr . "' data-toggle='tooltip' data-placement='bottom' data-original-title='Hak Akses: " . $userNamesStr . "'";
            $userBadge = "<br><small style='font-size:10px; color:#555; font-weight:normal; display:inline-block;'><i class='fa fa-user'></i> " . $userNamesStr . "</small>";
        }

        $str .= "<li class='$bg_warna no-margin' $titleAttr>";
        $str .= "<span>$step_label $userBadge</span>";
        $str .= "</li>";
    }
    $str .= "</ul>";

    return $str;
}

// END OF COMPLETE REPEATED LOGIC

function loadConfigModulJenis_he_misc($jenisTransaksi, $fileConfig)
{
    $CI = &get_instance();
    $heTransaksi_ui = $CI->config->item("heTransaksi_ui")[$jenisTransaksi];

    if (isset($heTransaksi_ui["modul"])) {
        $modul_path = $heTransaksi_ui["modul"];
        $conf = "../../modules/$modul_path/config/";
        $CI->load->config($conf . "$fileConfig");
        $configData = $CI->config->item("$fileConfig")[$jenisTransaksi];
    }
    else {
        // matiHEre("modul config coTransksiCore belum diseting/ jenismaster dan target berbeda");
        $configData = $heTransaksi_ui;
    }

    return $configData;
}

//panggil semua configui yang ada di modul
function loadConfigUiModul_he_misc()
{
    $CI = &get_instance();

    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {
            //cekHitam($jn);
            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                // cekHere("$conf :: $jn");
                $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = isset($CI->config->item("coTransaksiUi")[$jn]) ? $CI->config->item("coTransaksiUi")[$jn] : cekMerah("heTransaksi_ui sudah siap modul, sayangnya config modul " . $jnData['modul'] . " untuk jenisTr $jn tidak ditemukan, tolong dilengkapi yak");
            }

            // arrPrint($modul_path);

        }
        // arrPrint($modul_path);
        // matiHEre();
        return $modul_path;
    }
    else {
        return array();
    }
    // matiHere();
    // $conf = "../../modules/$modul_path/config/";
}

//panggil semua configui yang ada di modul
function loadConfigUiModul()
{
    $CI = &get_instance();
    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {

            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = $CI->config->item("coTransaksiUi")[$jn];
            }

            // arrPrint($modul_path);

        }
        return $modul_path;
    }
    else {
        return array();
    }
    matiHere();
    $conf = "../../modules/$modul_path/config/";
}

function loadConfigPathModul()
{
    $CI = &get_instance();
    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (sizeof($modul_pathSrc) > 0) {
        $modul_path = array();
        foreach ($modul_pathSrc as $jn => $jnData) {

            if (isset($jnData["modul"])) {
                $conf = "../../modules/" . $jnData['modul'] . "/config/";
                // $CI->load->config($conf . "coTransaksiUi");
                $modul_path[$jn] = $conf;
            }
            // arrPrint($modul_path);
        }
        return $modul_path;
    }
    else {
        return array();
    }
    // matiHere();
    // $conf = "../../modules/$modul_path/config/";
}

function loadConfigUiMaster()
{
    $ci = &get_instance();
    $masterConfigUi = $ci->config->item("heTransaksi_ui");

    return $masterConfigUi;
}

function loadConfigUiMasterLabel()
{
    $ci = &get_instance();
    $arrTransaksiLabel = array();
    $masterConfigUi = $ci->config->item("heTransaksi_ui");
    if (sizeof($masterConfigUi) > 0) {
        foreach ($masterConfigUi as $jenisMaster => $hSpec) {
            $arrTransaksiLabel[$jenisMaster] = $hSpec["label"];
            foreach ($hSpec["steps"] as $step => $sSpec) {
                $arrTransaksiLabel[$sSpec["target"]] = $sSpec["label"];
            }
        }
    }

    return $arrTransaksiLabel;
}

function getFileList($dir, $recurse = FALSE)
{
    $retval = array();

    // add trailing slash if missing
    if (substr($dir, -1) != "/") {
        $dir .= "/";
    }

    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
    while (FALSE !== ($entry = $d->read())) {
        // skip hidden files
        if ($entry[0] == ".") {
            continue;
        }
        if (is_dir("{$dir}{$entry}")) {
            $retval[] = array(
                'path' => "{$dir}{$entry}/",
                'name' => "$entry",
                'type' => filetype("{$dir}{$entry}"),
                'size' => 0,
                'lastmod' => filemtime("{$dir}{$entry}")
            );
            if ($recurse && is_readable("{$dir}{$entry}/")) {
                $retval = array_merge($retval, getFileList("{$dir}{$entry}/", TRUE));
            }
        }
        elseif (is_readable("{$dir}{$entry}")) {
            $retval[] = array(
                'path' => "{$dir}{$entry}",
                'name' => "$entry",
                'type' => mime_content_type("{$dir}{$entry}"),
                'size' => filesize("{$dir}{$entry}"),
                'lastmod' => filemtime("{$dir}{$entry}")
            );
        }
    }
    $d->close();

    return $retval;
}

function loadAllActiveGudang($cabangID)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlGudangDefault_center");
    $ci->load->model("Mdls/MdlGudangDefault");
    $ci->load->model("Mdls/MdlGudang");
    $selectFields = array("id", "nama");
    //gcd gudang default (* yg pakai minus)
    $gcd = new MdlGudangDefault_center();
    $gdb = new MdlGudangDefault();
    $g = new MdlGudang();
    $gcd->addFilter("cabang_id='-1'");
    $tempCenterDefaultGudang = $gcd->lookUpAll()->result();
    $allWareHouse = array();
    foreach ($tempCenterDefaultGudang as $gCenter) {
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $gCenter->$iField;
        }
        $allWareHouse[] = $temp;

    }
    // arrprint($tempCenterDefaultGudang);
    $gdb->addFilter("cabang_id='$cabangID'");
    $tempBranchGudang = $gdb->lookUpAll()->result();
    foreach ($tempBranchGudang as $gdefBranch) {
        // $allWareHouse[$gdefBranch->id]=$gdefBranch->nama;
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $gdefBranch->$iField;
        }
        $allWareHouse[] = $temp;
    }
    // arrprint($tempBranchGudang);
    $g->addFilter("cabang_id='$cabangID'");
    $g->addFilter("id > '0'");
    $tempGudang = $g->lookUpAll()->result();
    foreach ($tempGudang as $tempGudang_o) {
        $temp = array();
        foreach ($selectFields as $i => $iField) {
            $temp[$iField] = $tempGudang_o->$iField;
        }
        $allWareHouse[] = $temp;
        // $allWareHouse[$tempGudang_o->id]=$tempGudang_o->nama;
    }
    return $allWareHouse;
    // arrprint($allWareHouse);

}

function test_table($datas)
{
    $td = "";
    foreach ($datas as $item) {
        // arrPrintHijau($item);
        $hd = "";
        $td .= "<tr>";
        foreach ($item as $kolom => $nilai) {
            $hd .= "<td>$kolom</td>";

            $td .= "<td>$nilai</td>";
        }
        $td .= "</tr>";

        $rekening = $item->rekening;

    }

    // echo "<button onClick=\"SelfCopy(this.id)\"  id=\"1\">1</button>";
    echo "<div id='2'>";
    echo "<table border='1' rules='all'>";
    echo "<tr>$hd</tr>";
    echo "$td";
    echo "</table>";
    echo "<div>";
}

//loader prebiaya coa
function produkrakitanPrebiaya()
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukRakitanPreBiaya");
    $b = new MdlProdukRakitanPreBiaya();
    $temp = $b->lookUpAll()->result();
    $data = array();
    foreach ($temp as $tempData) {
        $data[$tempData->id] = array(
            "nama" => $tempData->nama,
            "coa_code" => $tempData->coa_code,
            "coa_code_2" => $tempData->coa_code_2,
        );
    }
    return $data;
    // arrPrint($data);
    // matiHEre();

}

function isMobile_he_misc()
{
    /* ----------------------------------------------------------------------
     * deteksi mobile auto atau hanya orang tertentu,
     * diatur di heWeb ::mobile
     * $ci->load->library('user_agent'); masuk list autoload
     * ----------------------------------------------------------------------*/

    $ci = &get_instance();
    $conf_mobile = $ci->config->item("mobile");

    $mobileAutoDetect = $conf_mobile['autoDetect'];
    $forcedMobile = $conf_mobile['forcedMobile'];
    $disallowedMobile = $conf_mobile['disallowedMobile'];

    $mobile = "";
    if (key_exists(my_id(), $disallowedMobile)) {
        $mobile = false;
    }
    else {
        if ($mobileAutoDetect == true) {
            if ($ci->agent->is_mobile()) {
                $mobile = true;
            }
            else {
                $mobile = false;
            }
        }
        else {
            if (isset($ci->session->login['forceMobile']) && $ci->session->login['forceMobile'] == 1 && $ci->agent->is_mobile()) {
                $mobile = true;
            }
        }
        if (key_exists(my_id(), $forcedMobile)) {
            $mobile = true;
        }
    }

    // $ci->agent->is_browser();
    // $ff = $ci->agent->browser().' '.$ci->agent->version();
    // arrPrintWebs($conf_mobile);

    return $mobile;
}

function addCommas($a)
{
    if (preg_match("/^[0-9,]+$/", $a)) {
        $a = str_replace(",", "", $a);
    }

    return number_format($a * 1);
}

function removeCommas($a)
{

    if (preg_match("/^[0-9,]+$/", $a)) {
        $a = str_replace(",", "", $a);
    }

    return $a * 1;
}

//-------------------------------
function my_fase_id()
{
    if (isset($_SESSION['login']['fase_id'])) {
        return $_SESSION['login']['fase_id'];
    }
    else {
        return 0;
    }
}

function my_fase_name()
{
    if (isset($_SESSION['login']['fase_nama'])) {
        return $_SESSION['login']['fase_nama'];
    }
    else {
        return 0;
    }
}

//-------------------------------
function getDefaultWarehouseProject($projectID, $projectNama, $deviceID = 0)
{

    if ($deviceID > 0) {
        $gudangID = ($projectID * -10) - ($deviceID);
        $gudangKey = "project:$projectID:device:$deviceID";
        $gudangName = "sub-branch #$projectID/$gudangID";
    }
    else {
        $gudangID = ($projectID * -10);
        $gudangKey = "project:$projectID";
        $gudangName = "gudang project $projectNama";
    }

    return array(
        "gudang_id" => $gudangID,
        "gudang_key" => $gudangKey,
        "gudang_nama" => $gudangName,
    );

}

function getDefaultWarehouseProjectWorkorder($projectID, $workorderID, $workorderNama)
{
    $gudangID = "$projectID" . "$workorderID";
    $gudangKey = "project:$projectID:workorder:$workorderID";
    $gudangName = "gudang workorder $workorderNama";

    return array(
        "gudang_id" => $gudangID,
        "gudang_key" => $gudangKey,
        "gudang_nama" => $gudangName,
    );

}

function getDefaultWarehouseProjectSubWorkorder($projectID, $workorderID, $subWorkorderID, $subWorkorderNama, $source_spk)
{

    $ambil_angka_depan_spk = explode("/", $source_spk)[0];

    $gudangID = "$projectID" . "$workorderID" . "$subWorkorderID" . "$ambil_angka_depan_spk";
    $gudangName = "gudang workorder $subWorkorderNama";

    return array(
        "gudang_id" => $gudangID,
        "gudang_nama" => $gudangName,
    );

}


function projectBom($projectID, $handler = null)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukKomposisi");
    $b = new MdlProdukKomposisi();
    $b->setFilters(array());
    $b->addFilter("status='1'");
    $b->addFilter("trash='0'");
    $b->addFilter("produk_id='$projectID'");
    $temp = $b->lookUpAll()->result();
    $data = array();
    foreach ($temp as $tempData) {
        //        arrPrintWebs($tempData);
        $data[$tempData->jenis][$tempData->produk_dasar_id] = array(
            "handler" => $handler,
            "id" => $tempData->produk_dasar_id,
            "nama" => $tempData->produk_dasar_nama,
            "produk_nama" => $tempData->produk_dasar_nama,
            "name" => $tempData->produk_dasar_nama,
            //            "jml" => $tempData->jml,
            //            "qty" => $tempData->jml,
            //            "valid_qty" => $tempData->jml,
            //            "produk_ord_jml" => $tempData->jml,
            //            "harga" => $tempData->nilai,
            //            "subtotal" => $tempData->jml * $tempData->nilai,
            "jml" => 1,
            "qty" => 1,
            "valid_qty" => 1,
            "produk_ord_jml" => 1,
            "harga" => $tempData->nilai,
            "subtotal" => 1 * $tempData->nilai,
        );
    }
    //arrPrintHijau($data);

    return $data;


}

function aksesProject()
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProjectAccess");
    $m = new MdlProjectAccess();
    $fields = $m->getListedFields();
    $temp = $m->lookUpAll()->result();
    $data = array();
    if (count($temp) > 0) {
        foreach ($temp as $temp_0) {
            $data[$temp_0->id] = (array)$temp_0;
        }
    }
    // $data = array(
    //     "1"=>array(
    //         "id"=>"1",
    //         "nama"=>"admin",
    //         "keterangan"=>"full akses",
    //     ),
    //     "2"=>array(
    //         "id"=>"2",
    //         "nama"=>"supervisi",
    //         "keterangan"=>"melakukan monitoring dan pemeriksaan project",
    //     ),
    //     "3"=>array(
    //         "id"=>"3",
    //         "nama"=>"anggota",
    //         "keterangan"=>"melakukan pembaharuan progres",
    //     ),
    //     "4"=>array(
    //         "id"=>"4",
    //         "nama"=>"gudang site",
    //         "keterangan"=>"menerima dan mengeluarkan material dari gudang site"
    //     ),
    //     "5"=>array(
    //         "id"=>"5",
    //         "nama"=>"pihak lain",
    //         "keterangan"=>"pihak atau sub kontraktor"
    //     ),
    // );
    return $data;
}

function createTaskListForm($project, $timWork, $workOrder, $link)
{
    /*
     *data projek sebagai title
     *data teamwork untuk dipilih
     *data work order untuk dipilih
     */
    $title = "DAFTAR TUGAS ( " . $project[0]->nama . " )";

    $data = "<div class='box box-warning'>";
    $data .= "<div class='box-header with-border'><h3>$title</h3></div>";
    $data .= "<div class='box-body'>";
    $data .= "<form class='form-control' target='result' method='post' action='$link'>";
    $data .= "<div class='form-group'>";
    $data .= "<label>Rencana kerja</label>";
    $data .= "<input>";
    $data .= "</div>";

    $data .= "</form >";
    $data .= "</div >";
    $data .= "</div >";


    // matiHere();
    return $data;
}

function showRincian_original($trID, $modeShow, $print_metode)
{

    $ci =  &get_instance();
    $ci->load->model("Mdls/MdlProdukProject");
    $ci->load->model("Mdls/MdlProjectWorkOrder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorderSub");
    $p = new MdlProdukProject();
    $w = new MdlProjectWorkOrder();
    $k = new MdlProjectKomposisiWorkorder();
    $kw = new MdlProjectKomposisiWorkorderSub();


    $p->addFilter("transaksi_id='$trID'");
    $tempProduk = $p->lookUpAll()->result();
    //    cekHitam($ci->db->last_query());
    $pid = $tempProduk[0]->id;

    //ambil data actuive fase /wo
    $w->addFilter("produk_id='$pid'");
    $tempWo = $w->lookUpAll()->result();
    $activeWo = $tempWo[0]->id;
    $womasterData = array();
    foreach ($tempWo as $tempWo_0) {
        $womasterData[$tempWo_0->id] = $tempWo_0;
    }
    //    cekHitam($ci->db->last_query());
    $k->addFilter("produk_id='$pid'");
    $k->addFilter("bahan_utama='1'");
    $tempKomposisiWo = $k->lookUpKomposisiFase();
    //    arrPrint($tempKomposisiWo);
    $k->setFilters(array());
    $k->addFilter("produk_id='$pid'");
    $k->addFilter("jenis='jual'");
    $tempKomposisiJualWo = $k->lookupAll()->result();
    //    cekHitam($ci->db->last_query());
    $hrgJualKomp = array();
    if (!empty($tempKomposisiJualWo)) {
        $selectField = array(
            "id",
            "produk_dasar_id",
            "produk_id",
            "bahan_utama",
            "produk_nama",
            "jml",
            "jenis",
            "produk_nama",
            "produk_dasar_nama",
            "nilai",
            "harga",
            "fase_id",
            "gudang_id",
            "gudang2_id",
            "cat_id",
            "cat_nama",
            "satuan",
            "satuan_id",
            "fase_id",
            "jenis",
            "status",
            "trash"
        );
        foreach ($tempKomposisiJualWo as $tmp_0) {
            $tmp = array();
            foreach ($selectField as $i => $field) {
                $tmp[$field] = $tmp_0->$field;
            }
            $hrgJualKomp[$tmp_0->fase_id] = $tmp;
        }
    }

    $komposisiHeader = array("jml" => "qty", "nama", "subtotal");
    $detil = array();
    foreach ($tempKomposisiWo as $woID => $woData) {
        if (isset($woData["produk"])) {
            foreach ($woData["produk"] as $produkDatas) {
                $detil[$produkDatas["fase_id"]]["produk"][$produkDatas["id"]] = $produkDatas["produk_dasar_nama"];
                $detil[$produkDatas["fase_id"]]["pjml"][$produkDatas["id"]] = $produkDatas["jml"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["nama"]     = $produkDatas["produk_dasar_nama"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["jml"]      = $produkDatas["jml"];
                //                $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["satuan"]   = $produkDatas["satuan"];
                if (isset($hrgJualKomp[$produkDatas["fase_id"]])) {
                    $detil[$produkDatas["fase_id"]]["harga_jual"] = $hrgJualKomp[$produkDatas["fase_id"]]["harga"];
                    //                    $detil[$produkDatas["fase_id"]][$produkDatas["id"]]["harga"] = $hrgJualKomp[$produkDatas["fase_id"]]["harga"];
                }
            }
        }
    }

    $komposisiSubWorkOrder = array();
    $kw->setFilters(array());
    $kw->addFilter("produk_id='$pid'");
    $kw->addFilter("jenis_transaksi=5582");
    $kw->addFilter("fase_id=$activeWo");
    $kw->addFilter("trash='0'");
    $kw->addFilter("status='1'");
    $tmp = $kw->lookUpAll()->result();

    $selectShowFields = array(
        "produk_dasar_nama" => "item",
        "satuan" => "satuan",
        "jml" => "jml",
        "harga" => "harga satuan",
    );
    $itemKomposisi = array();
    if (count($tmp) > 0) {
        foreach ($tmp as $ii => $tmp_0) {
            foreach ($selectShowFields as $kol => $alias) {
                $itemKomposisi[$ii][$kol] = $tmp_0->$kol;
            }
        }
    }
    //    arrPrint($itemKomposisi);
    //    cekBiru($ci->db->last_query());

    //arrPrintKuning($womasterData);
    //arrPrintWebs($hrgJualKomp);
    switch ($print_metode) {
        case "simple":
            $tblData = "<table class='table table-responsive table-bordered'>";
            $tblData .= "<thead>";
            $tblData .= "<tr>";
            $tblData .= "<th>No</th>";
            $tblData .= "<th>Pekerjaan</th>";
            $tblData .= "<th>Lokasi</th>";
            //    $tblData .= "<th>room</th>";
            //    $tblData .= "<th>item</th>";
            //    $tblData .= "<th>jml</th>";
            //    $tblData .= "<th>satuan</th>";
            //    $tblData .= "<th>Harga</th>";
            $tblData .= "<th>Nilai Project</th>";
            $tblData .= "</tr>";
            $tblData .= "</thead>";
            $tblData .= "<tbody>";
            $i = 0;
            $grandTotal = 0;


            foreach ($womasterData as $idWo => $woLabel) {
                $i++;
                $nama = $woLabel->nama;
                $jumlah_room = $woLabel->qty;
                $lokasi = strlen($woLabel->lokasi) > 5 ? $woLabel->lokasi : "-";
                $rowspan = count($detil);
                $rowspan = 0;
                $tblData .= "<tr>";
                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'>$jumlah_room</td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "<td rowspan='$rowspan' class='text-right'></td>";
                //        $tblData .= "</tr>";

                $nama_produk = "";
                //        foreach ($detil[$idWo]['produk'] as $k => $nm) {
                //            $nama_produk .= $nm . "<br>";
                //        }

                $jml_produk = "";
                //        foreach ($detil[$idWo]['pjml'] as $k => $nm) {
                //            $jml_produk .= "<div class='text-bold text-center text-primary'>" . $nm * $jumlah_room . "</div>";
                //        }

                //        $rowspans = count($detil[$idWo]['produk']);
                $rowspans = 0;
                //        $tblData .= "<tr>";
                //        $tblData .= "<td srowspan='$rowspans'>" . $nama_produk . "</td>";
                $tblData .= "<td srowspan='$rowspans'>" . $lokasi . "</td>";
                //        $tblData .= "<td srowspan='$rowspans' class='text-right'>" . $jml_produk . "</td>";
                //        $tblData .= "<td srowspan='$rowspans'>unit</td>";
                //        $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($detil[$idWo]["harga_jual"]) . "</td>";
                $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($hrgJualKomp[$idWo]["harga"] * $jumlah_room) . "</td>";
                $tblData .= "</tr>";
                //
                //        $tblData .= "<tr></tr>";


                $grandTotal += $hrgJualKomp[$idWo]["harga"] * $jumlah_room;

            }

            //    arrPrint($tempKomposisiWo[1]['produk']);

            $tblData .= "<tr>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "</tr>";

            $unitNo = 0;
            foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                if ($unitData['harga'] * 1 > 500000) {
                    $unitNo++;
                    $unitNama = $unitData['produk_dasar_nama'];
                    $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                    $tblData .= "<tr>";
                    $tblData .= "<td colspan='3' style=''>$unitNo. $unitNama</td>";
                    $tblData .= "<td colspan='3' class='text-right'>$unitTotalHrg</td>";
                    $tblData .= "</tr>";
                }
            }

            $tblData .= "<tr>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "<td colspan='3'>&nbsp;</td>";
            $tblData .= "</tr>";

            $tblData .= "</tbody>";

            $tblData .= "<tfoot>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='3' class='text-right'>PPN (11%)</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal * 0.11) . "</th>";
            $tblData .= "</tr>";

            $tblData .= "<tr>";
            $tblData .= "<th colspan='3' class='text-right'>Grand Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal + ($grandTotal * 0.11)) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "</tfoot>";
            //    $tblData .= "</tr>";
            $tblData .= "</table>";
            break;
        case "detail":
            $tblData = "<table class='table table-responsive table-bordered'>";
            $tblData .= "<thead>";
            $tblData .= "<tr >";
            $tblData .= "<th class='text-center'>No</th>";
            foreach ($selectShowFields as $key => $label) {
                $tblData .= "<th class='text-center'>$label</th>";
            }
            $tblData .= "<th>Total harga</th>";
            $tblData .= "</tr>";
            $tblData .= "</thead>";
            $tblData .= "<tbody>";
            $i = 0;
            $grandTotal = 0;
            foreach ($itemKomposisi as $ix => $woLabel) {
                $i++;
                //                $nama = $woLabel->nama;
                //                $jumlah_room = $woLabel->jml;
                //                $lokasi = strlen($woLabel->lokasi) > 5 ? $woLabel->lokasi:"-";
                //                $rowspan = count($detil);
                //                $rowspan = 0;
                //                $tblData .= "<tr>";
                //                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                //                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                //                $nama_produk = "";
                //                $jml_produk = "";
                //                $rowspans = 0;
                //                $tblData .= "<td srowspan='$rowspans'>" . $lokasi . "</td>";
                //                $tblData .= "<td srowspan='$rowspans' class='text-right'>" . number_format($hrgJualKomp[$idWo]["harga"] * $jumlah_room) . "</td>";
                //                $tblData .= "</tr>";
                //                $tblData .= "<tr>";
                //                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                //                $tblData .= "<td srowspan='$rowspan'>$nama</td>";
                $tblData .= "<td srowspan='$rowspan'>$i</td>";
                foreach ($selectShowFields as $key => $label) {
                    $tblData .= "<td srowspan='$rowspans' class='text-left'>" . formatField($key, $woLabel[$key]) . "</td>";
                }
                $tblData .= "<td srowspan='$rowspan'>" . formatField("subtotal", $woLabel["harga"] * $woLabel["jml"]) . "</td>";
                $tblData .= "</tr>";
                $grandTotal += $woLabel["harga"] * $woLabel["jml"];

            }

            //    arrPrint($tempKomposisiWo[1]['produk']);

            $tblData .= "<tr>";
            $tblData .= "<td colspan='5'>&nbsp;</td>";
            $tblData .= "<td colspan='5'>&nbsp;</td>";
            $tblData .= "</tr>";

            $unitNo = 0;
            foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                if ($unitData['harga'] * 1 > 500000) {
                    $unitNo++;
                    $unitNama = $unitData['produk_dasar_nama'];
                    $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                    $tblData .= "<tr>";
                    $tblData .= "<td colspan='3' style=''>$unitNo. $unitNama</td>";
                    $tblData .= "<td colspan='3' class='text-right'>$unitTotalHrg</td>";
                    $tblData .= "</tr>";
                }
            }

            //            $tblData .= "<tr>";
            //            $tblData .= "<td colspan='5'>&nbsp;</td>";
            //            $tblData .= "<td colspan='5'>&nbsp;</td>";
            //            $tblData .= "</tr>";

            $tblData .= "</tbody>";

            $tblData .= "<tfoot>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>PPN (11%)</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal * 0.11) . "</th>";
            $tblData .= "</tr>";

            $tblData .= "<tr>";
            $tblData .= "<th colspan='5' class='text-right'>Grand Total</th>";
            $tblData .= "<th colspan='' class='text-right'>" . number_format($grandTotal + ($grandTotal * 0.11)) . "</th>";
            $tblData .= "</tr>";
            $tblData .= "</tfoot>";
            //    $tblData .= "</tr>";
            $tblData .= "</table>";
            break;
        default:
            matiHere("unknown metode print");
            break;
    }


    //    cekMErah($mode);
    if ($modeShow == "show") {
        echo $tblData;
    }
    else {
        return $tblData;
    }

}

function showRincian($trID, $modeShow, $print_metode = 'detail')
{

    //    $print_metode='detail';

    $ci =  &get_instance();
    $ci->load->model("MdlTransaksi");
    $ci->load->model("Mdls/MdlProdukProject");
    $ci->load->model("Mdls/MdlProjectWorkOrder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorder");
    $ci->load->model("Mdls/MdlProjectKomposisiWorkorderSub");

    $p = new MdlProdukProject();
    $w = new MdlProjectWorkOrder();
    $k = new MdlProjectKomposisiWorkorder();
    $kw = new MdlProjectKomposisiWorkorderSub();

    $p->addFilter("transaksi_id='$trID'");
    $tempProduk = $p->lookUpAll()->result();
    $pid = $tempProduk[0]->id;
    $quot_id = $tempProduk[0]->quot_id;
    $start_id = $tempProduk[0]->project_start_id;

    // ambil data active fase / wo
    $w->addFilter("produk_id='$pid'");
    $tempWo = $w->lookUpAll()->result();

    // WO BELUM APPROVE → baca hanya items2 (produk+biaya)
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $mongoFields = $tr->getFields()["dataRegistry"];
    $tmpReg = $tr->lookupDataRegistriesByMasterID($trID)->result();

    $tr->setFilters(array());
    $tr->addFilter("transaksi_id='$quot_id'");
    $tmpRegMain = $tr->lookupDataRegistries()->result();

    if (empty($tempWo)) {

        /*
         * NOTA SEMENTARA: WO BELUM APPROVE → ambil data dari registry
         */

        if (sizeof($tmpReg) === 0) {
            die("Cannot read the registry entries from transaksi_id=$trID");
        }

        // decode items2
        $items2_Registries = [];
        foreach ($tmpReg as $row) {
            if (!empty($row->items2)) {
                $decoded = unserialize(base64_decode($row->items2));
                if (is_array($decoded)) {
                    $items2_Registries = $decoded;
                    break;
                }
            }
        }
        if (empty($items2_Registries)) {
            die("items2 registry is empty");
        }

        // normalisasi: bentuk bisa [0 => ['produk'=>..., 'biaya'=>...]] atau langsung ['produk'=>..., 'biaya'=>...]
        $bucket = isset($items2_Registries[0]) ? $items2_Registries[0] : $items2_Registries;
        $produk = isset($bucket['produk']) && is_array($bucket['produk']) ? $bucket['produk'] : [];
        $biaya = isset($bucket['biaya']) && is_array($bucket['biaya']) ? $bucket['biaya'] : [];

        //        arrPrint($produk);
        //        arrPrint($biaya);
        //matiHerE(__LINE__);
        // siapkan rows gabungan
        $rows = [];

        // helper push
        $push = function (array $r, bool $isBiaya) use (&$rows) {
            $nama = isset($r['nama']) ? $r['nama'] : (isset($r['produk_nama']) ? $r['produk_nama'] : '');
            $satuan = isset($r['satuan']) && $r['satuan'] !== '' ? $r['satuan'] : 'n/a';
            $jml = isset($r['jml']) ? (float)$r['jml'] : (isset($r['qty']) ? (float)$r['qty'] : 1.0);
            $harga = isset($r['harga']) ? (float)$r['harga'] : 0.0;

            // subtotal jual: pakai 'subtotal' bila ada, fallback jml*harga
            $subtotal = isset($r['subtotal']) ? (float)$r['subtotal'] : ($jml * $harga);

            // anggaran: hanya ada di biaya; produk = 0
            $anggaran = 0.0;
            if ($isBiaya) {
                if (isset($r['sub_anggaran']) && is_numeric($r['sub_anggaran'])) {
                    $anggaran = (float)$r['sub_anggaran'];
                }
                elseif (isset($r['anggaran']) && is_numeric($r['anggaran'])) {
                    $anggaran = (float)$r['anggaran'] * ($jml > 0 ? $jml : 1.0);
                }
            }
            else {
                if (isset($r['sub_hpp_supplier']) && is_numeric($r['sub_hpp_supplier'])) {
                    $anggaran = (float)$r['sub_hpp_supplier'];
                }
                elseif (isset($r['hpp_supplier']) && is_numeric($r['hpp_supplier'])) {
                    $anggaran = (float)$r['hpp_supplier'] * ($jml > 0 ? $jml : 1.0);
                }
            }

            $rows[] = [
                'nama' => $nama,
                'satuan' => $satuan,
                'jml' => $jml,
                'harga' => $harga,
                'subtotal' => $subtotal,
                'anggaran' => $anggaran,
                // lokasi tidak ada di items2_Registries → isi “-” atau tarik dari MdlTransaksi jika diperlukan
                'lokasi' => '-',
            ];
        };

        // gabungkan produk & biaya
        foreach ($produk as $r) {
            $push((array)$r, false);
        }
        foreach ($biaya as $r) {
            $push((array)$r, true);
        }

        // Rendering sesuai $print_metode (tanpa string concatenation)

        ob_start();
        ?>
        <style>
            .wm-na {
                position: relative;
                overflow: hidden;
            }

            .wm-na .wm-multi {
                /* atur kepadatan via CSS variables */
                --wm-cols: 6; /* jumlah kolom */
                --wm-row: 90px; /* tinggi baris */
                --wm-size: 18px; /* ukuran font */

                position: absolute;
                inset: 0;
                display: grid;
                grid-template-columns: repeat(var(--wm-cols), 1fr);
                grid-auto-rows: var(--wm-row);
                align-content: stretch;
                justify-items: center;
                align-items: center;
                transform: rotate(-5deg);
                pointer-events: none;
                z-index: 2;
            }

            .wm-na .wm-multi span {
                font-size: var(--wm-size);
                font-weight: 800;
                letter-spacing: 0.1em;
                white-space: nowrap;
                color: rgba(0, 0, 0, 0.25); /* redup di layar */
            }

            @media print {
                .wm-na .wm-multi span {
                    color: rgba(0, 0, 0, 0.25) !important; /* lebih redup saat cetak */
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }

            .wm-na table {
                position: relative;
                z-index: 1;
                background: transparent;
            }
        </style>

        <div class="wm-na">
            <div class="wm-multi" aria-hidden="true">
                <?php for ($i = 0; $i < 30; $i++): ?>  <!-- jumlah elemen; naikkan bila area sangat panjang -->
                    <span>BELUM APPROVE&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <?php endfor; ?>
            </div>

            <table class="table table-responsive table-bordered">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">item</th>
                    <th class="text-center">satuan</th>
                    <th class="text-center">jml</th>
                    <th class="text-center">harga project (satuan)</th>
                    <th class="text-center">Total harga project</th>
                    <!--                    <th class="text-center">Anggaran (satuan)</th>-->
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $totalJual = 0;
                $totalAnggaran = 0;
                foreach ($rows as $row) {
                    $i++;
                    $jual = $row['subtotal'] > 0 ? $row['subtotal'] : ($row['jml'] * $row['harga']);
                    $ang = $row['anggaran'];
                    $totalJual += $jual;
                    $totalAnggaran += $ang;
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td class="text-left"><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-left"><?= htmlspecialchars($row['satuan'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-right"><?= htmlspecialchars((string)$row['jml'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-right"><?= number_format($row['harga']) ?></td>
                        <td class="text-right"><?= number_format($jual) ?></td>
                        <!--                        <td class="text-right">-->
                        <?php //= number_format($ang) ?><!--</td>-->
                    </tr>
                    <?php
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total</th>
                    <th class="text-right"><?= number_format($totalJual) ?></th>
                </tr>
                <!--                <tr>-->
                <!--                    <th colspan="6" class="text-right">Total Anggaran</th>-->
                <!--                    <th class="text-right">--><?php //= number_format($totalAnggaran)
                ?><!--</th>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <th colspan="6" class="text-right">Perkiraan R/L</th>-->
                <!--                    <th class="text-right">--><?php //= number_format($totalJual-$totalAnggaran)
                ?><!--</th>-->
                <!--                </tr>-->
                <tr>
                    <th colspan="5" class="text-right">PPN (11%)</th>
                    <th class="text-right"><?= number_format($totalJual * 0.11) ?></th>
                </tr>
                <tr>
                    <th colspan="5" class="text-right">Grand Total</th>
                    <th class="text-right"><?= number_format($totalJual + ($totalJual * 0.11)) ?></th>
                </tr>
                </tfoot>
            </table>
        </div>

        <script>
            top.$('#stamp').html("(BELUM APPROVE)");
        </script>
        <?php
        $tblData = ob_get_clean();


    }
    else {

        /*
         * NOTA KOMPOSISI PROJECT HANYA KELUAR JIKA SUDAH APPROVE
         * (AREA APPROVED — TIDAK DIUBAH)
         */

        //        arrPrint( blobDecode($tmpRegMain[0]->main) );

        $diskon_pembulatan = isset(blobDecode($tmpRegMain[0]->main)['diskon_pembulatan']) ? blobDecode($tmpRegMain[0]->main)['diskon_pembulatan'] : 0;
        $harga_items2 = blobDecode($tmpRegMain[0]->main)['harga_items2'];
        $hargaBruto = $harga_items2 + $diskon_pembulatan;

        $activeWo = $tempWo[0]->id;
        $womasterData = array();
        foreach ($tempWo as $tempWo_0) {
            $womasterData[$tempWo_0->id] = $tempWo_0;
        }

        $k->addFilter("produk_id='$pid'");
        $k->addFilter("bahan_utama='1'");
        $tempKomposisiWo = $k->lookUpKomposisiFase();

        $k->setFilters(array());
        $k->addFilter("produk_id='$pid'");
        $k->addFilter("jenis='jual'");
        $tempKomposisiJualWo = $k->lookupAll()->result();
        $hrgJualKomp = array();
        if (!empty($tempKomposisiJualWo)) {
            $selectField = array(
                "id",
                "produk_dasar_id",
                "produk_id",
                "bahan_utama",
                "produk_nama",
                "jml",
                "jenis",
                "produk_nama",
                "produk_dasar_nama",
                "nilai",
                "harga",
                "fase_id",
                "gudang_id",
                "gudang2_id",
                "cat_id",
                "cat_nama",
                "satuan",
                "satuan_id",
                "fase_id",
                "jenis",
                "status",
                "trash"
            );
            foreach ($tempKomposisiJualWo as $tmp_0) {
                $tmp = array();
                foreach ($selectField as $i => $field) {
                    $tmp[$field] = $tmp_0->$field;
                }
                $hrgJualKomp[$tmp_0->fase_id] = $tmp;
            }
        }

        $komposisiHeader = array("jml" => "qty", "nama", "subtotal");
        $detil = array();
        foreach ($tempKomposisiWo as $woID => $woData) {
            if (isset($woData["produk"])) {
                foreach ($woData["produk"] as $produkDatas) {
                    $detil[$produkDatas["fase_id"]]["produk"][$produkDatas["id"]] = $produkDatas["produk_dasar_nama"];
                    $detil[$produkDatas["fase_id"]]["pjml"][$produkDatas["id"]] = $produkDatas["jml"];
                    if (isset($hrgJualKomp[$produkDatas["fase_id"]])) {
                        $detil[$produkDatas["fase_id"]]["harga_jual"] = $hrgJualKomp[$produkDatas["fase_id"]]["harga"];
                    }
                }
            }
        }

        $komposisiSubWorkOrder = array();
        $kw->setFilters(array());
        $kw->addFilter("produk_id='$pid'");
        $kw->addFilter("jenis_transaksi=5582");
        $kw->addFilter("fase_id=$activeWo");
        $kw->addFilter("trash='0'");
        $kw->addFilter("status='1'");
        $tmp = $kw->lookUpAll()->result();

        $selectShowFields = array(
            "produk_dasar_nama" => "item",
            "satuan" => "satuan",
            "jml" => "jml",
            "harga" => "harga satuan",
        );
        $itemKomposisi = array();
        if (count($tmp) > 0) {
            foreach ($tmp as $ii => $tmp_0) {
                foreach ($selectShowFields as $kol => $alias) {
                    $itemKomposisi[$ii][$kol] = $tmp_0->$kol;
                }
            }
        }

        switch ($print_metode) {
            case "simple":
                ob_start();
                ?>
                <table class='table table-responsive table-bordered'>
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Pekerjaan</th>
                        <th>Lokasi</th>
                        <th>Nilai Project</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $grandTotal = 0;
                    foreach ($womasterData as $idWo => $woLabel) {
                        $i++;
                        $nama = $woLabel->nama;
                        $jumlah_room = $woLabel->qty;
                        $lokasi = strlen($woLabel->lokasi) > 5 ? $woLabel->lokasi : "-";
                        $rowspan = 0;
                        $rowspans = 0;
                        ?>
                        <tr>
                            <td srowspan="<?= $rowspan ?>"><?= $i ?></td>
                            <td srowspan="<?= $rowspan ?>"><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></td>
                            <td srowspan="<?= $rowspans ?>"><?= htmlspecialchars($lokasi, ENT_QUOTES, 'UTF-8') ?></td>
                            <td srowspan="<?= $rowspans ?>" class='text-right'>
                                <?= number_format($hrgJualKomp[$idWo]["harga"] * $jumlah_room) ?>
                            </td>
                        </tr>
                        <?php
                        $grandTotal += $hrgJualKomp[$idWo]["harga"] * $jumlah_room;
                    }
                    ?>
                    <tr>
                        <td colspan='3'>&nbsp;</td>
                        <td colspan='3'>&nbsp;</td>
                    </tr>
                    <?php
                    $unitNo = 0;
                    if (isset($tempKomposisiWo[1]['produk'])) {
                        foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                            if ($unitData['harga'] * 1 > 500000) {
                                $unitNo++;
                                $unitNama = $unitData['produk_dasar_nama'];
                                $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                                ?>
                                <tr>
                                    <td colspan='3'><?= $unitNo ?>
                                        . <?= htmlspecialchars($unitNama, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td colspan='3' class='text-right'><?= $unitTotalHrg ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td colspan='3'>&nbsp;</td>
                        <td colspan='3'>&nbsp;</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan='3' class='text-right'>DISKON/PEMBULATAN</th>
                        <th class='text-right'><?= number_format($diskon_pembulatan) ?></th>
                    </tr>
                    <tr>
                        <th colspan='3' class='text-right'>PENJUALAN NETT</th>
                        <th class='text-right'><?= number_format($harga_items2) ?></th>
                    </tr>
                    <tr>
                        <th colspan='3' class='text-right'>PPN (11%)</th>
                        <th class='text-right'><?= number_format($harga_items2 * 0.11) ?></th>
                    </tr>
                    <tr>
                        <th colspan='3' class='text-right'>Grand Total</th>
                        <th class='text-right'><?= number_format($harga_items2 + ($harga_items2 * 0.11)) ?></th>
                    </tr>
                    </tfoot>
                </table>
                <?php
                $tblData = ob_get_clean();
                break;
            case "detail":
                ob_start();
                ?>
                <table class='table table-responsive table-bordered'>
                    <thead>
                    <tr>
                        <th class='text-center'>No</th>
                        <?php foreach ($selectShowFields as $key => $label): ?>
                            <th class='text-center'><?= $label ?></th>
                        <?php endforeach; ?>
                        <th>Total harga</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    $grandTotal = 0;
                    $rowspan = 0;   // mengikuti kode asli yang menggunakan srowspan/rowspan=0
                    $rowspans = 0;
                    foreach ($itemKomposisi as $ix => $woLabel) {
                        $i++;
                        $subtotal_ = ($woLabel["harga"] * $woLabel["jml"]);
                        ?>
                        <tr>
                            <td srowspan="<?= $rowspan ?>"><?= $i ?></td>
                            <?php foreach ($selectShowFields as $key => $label): ?>
                                <td srowspan="<?= $rowspans ?>" class='text-left'>
                                    <?= formatField($key, $woLabel[$key]) ?>
                                </td>
                            <?php endforeach; ?>
                            <td srowspan="<?= $rowspan ?>">
                                <?= formatField("subtotal", $subtotal_) ?>
                            </td>
                        </tr>
                        <?php
                        $grandTotal += $woLabel["harga"] * $woLabel["jml"];
                    }
                    ?>
                    <tr>
                        <td colspan='5'>&nbsp;</td>
                        <td colspan='5'>&nbsp;</td>
                    </tr>
                    <?php
                    $unitNo = 0;
                    if (isset($tempKomposisiWo[1]['produk'])) {
                        foreach ($tempKomposisiWo[1]['produk'] as $k => $unitData) {
                            if ($unitData['harga'] * 1 > 500000) {
                                $unitNo++;
                                $unitNama = $unitData['produk_dasar_nama'];
                                $unitTotalHrg = number_format($unitData['jml'] * $unitData['harga']);
                                ?>
                                <tr>
                                    <td colspan='3'><?= $unitNo ?>
                                        . <?= htmlspecialchars($unitNama, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td colspan='3' class='text-right'><?= $unitTotalHrg ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <!--                    <tr>-->
                    <!--                        <th colspan='5' class='text-right'>Total</th>-->
                    <!--                        <th class='text-right'>--><?//= number_format($grandTotal) 
                    ?><!--</th>-->
                    <!--                    </tr>-->
                    <!--                    <tr>-->
                    <!--                        <th colspan='5' class='text-right'>PPN (11%)</th>-->
                    <!--                        <th class='text-right'>--><?//= number_format($grandTotal * 0.11) 
                    ?><!--</th>-->
                    <!--                    </tr>-->
                    <!--                    <tr>-->
                    <!--                        <th colspan='5' class='text-right'>Grand Total</th>-->
                    <!--                        <th class='text-right'>-->
                    <?//= number_format($grandTotal + ($grandTotal * 0.11)) 
                    ?><!--</th>-->
                    <!--                    </tr>-->
                    <tr>
                        <th colspan='5' class='text-right'>DISKON/PEMBULATAN</th>
                        <th class='text-right'><?= number_format($diskon_pembulatan) ?></th>
                    </tr>
                    <tr>
                        <th colspan='5' class='text-right'>PENJUALAN NETT</th>
                        <th class='text-right'><?= number_format($harga_items2) ?></th>
                    </tr>
                    <tr>
                        <th colspan='5' class='text-right'>PPN (11%)</th>
                        <th class='text-right'><?= number_format($harga_items2 * 0.11) ?></th>
                    </tr>
                    <tr>
                        <th colspan='5' class='text-right'>Grand Total</th>
                        <th class='text-right'><?= number_format($harga_items2 + ($harga_items2 * 0.11)) ?></th>
                    </tr>
                    </tfoot>
                </table>
                <?php
                $tblData = ob_get_clean();
                break;
            default:
                matiHere("unknown metode print");
                break;
        }

    }


    if ($modeShow == "show") {
        echo $tblData;
    }
    else {
        return $tblData;
    }
}

function getRomawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}


//-------------------------------
function loadCreateTransaksi($jenisTr, $employeeID)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $m = new MdlAccessRight();
    $m->addFilter("menu_category='$jenisTr'");
    $m->addFilter("employee_id='$employeeID'");
    $mTmp = $m->lookupAll()->result();

    return $mTmp;
}


//-------------------------------
function viewDetailTransaksi($arrDataTransaksi, $arrItemShow, $mode = "1336", $show = true, $tambahan = array(), $gate = "items", $headers_sub = array())
{
    $ci = &get_instance();
    $show_css = $show == true ? "o" : "hidden";
    $tblItems = "";

    switch ($mode) {
        case "1336_":
            $colspan = count($arrItemShow) - 2;
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
//                $tblItems .= "<tr>";
//                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
////                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
//                $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "<th>hasil</th>";
            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            foreach ($arrDataTransaksi["items"] as $iSpec) {
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }
            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $tblItems .= "<tr>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "1336":
            $back_asal = "#f4a7fa";
            $back_hasil = "#b1faa7";
            $colspan = count($arrItemShow);
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
            $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_asal;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            $noa = 0;
            foreach ($arrDataTransaksi["items"] as $iSpec) {
                $noa++;
                $tblItems .= "<td style='vertical-align:top;'>" . $noa . "</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";

            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_hasil;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $noh = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $noh++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td>" . $noh . "</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "4464":
        case "4822":
            if (isset($arrDataTransaksi["items2"]) && is_array($arrDataTransaksi["items2"]) && sizeof($arrDataTransaksi["items2"]) > 0) {
                foreach ($arrDataTransaksi["items2"] as $nomer => $specc) {
                    $f_nomer = str_replace('.', '', $nomer);
                    $tblItems .= "<div class='pull-left' style='margin-bottom: 2px;min-width: 150px;'><span width='50px' class='text-left text-bold text-uppercase'>$nomer</span> <i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i></div>";
                    $tblItems .= "<table name='tableHistoryShow' id='showTable_$f_nomer' class='table table-bordered dataTable compact hidden' style='margin-top:0px;'>";
                    $tblItems .= "<tr>";
                    $tblItems .= "<th>No</th>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        if (is_array($iLabel)) {
                            $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                        }
                        else {
                            $tblItems .= "<th >$iLabel</th>";
                        }
                    }
                    $tblItems .= "</tr>";
                    $numb = 0;
                    //                    foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    foreach ($specc as $pid => $iSpec) {
                        $numb++;
                        $tblItems .= "<tr>";
                        $tblItems .= "<td class='text-right'>$numb</td>";
                        foreach ($arrItemShow as $k => $iLabel) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $tblItems .= "<td>" . $val . "</td>";
                            if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                                if (!isset($summary[$k])) {
                                    $summary[$k] = 0;
                                }
                                $summary[$k] += $iSpec[$k];
                            }
                        }
                        $tblItems .= "</tr>";
                    }

                    $tblItems .= "<tr class='bg-gray'>";
                    $tblItems .= "<td class='text-right'></td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                        if ($k == "nama") {
                            $val = "total";
                        }
                        $tblItems .= "<td>" . $val . "</td>";
                    }
                    $tblItems .= "</tr>";
                    $tblItems .= "</table><br>";

                }
            }
            else {
                $f_nomer = str_replace('.', '', '');
                // $tblItems .= "<i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i>";

                // id='showTable_$f_nomer'
                $tblItems .= "<table name='tableHistoryShow'  class='table table-bordered $show_css' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                $itemsList = isset($arrDataTransaksi["items"]) && is_array($arrDataTransaksi["items"]) ? $arrDataTransaksi["items"] : array();
                foreach ($itemsList as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $tblItems .= "<td>" . $val . "</td>";
                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    if ($k == "nama") {
                        $val = "total";
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            break;
        /* --------------------------------------
         * ngabil dari item2
         * --------------------------------------*/
        case "5834":
        case "5834r":
        case "9834":
        case "9834r":
        case "9856":
        case "9856r":

            $items2_sum = array();
            $aliasBiaya = array();
            if (isset($arrDataTransaksi["items2"]) && is_array($arrDataTransaksi["items2"])) {
                foreach ($arrDataTransaksi["items2"] as $biy => $rItems2) {
                    if (!is_array($rItems2)) {
                        continue;
                    }
                    $aliasBiaya[$biy] = isset($rItems2['biaya_nama']) ? $rItems2['biaya_nama'] : "";
                    foreach ($rItems2 as $bdi => $iRow) {
                        if (!is_array($iRow)) {
                            continue;
                        }
                        if (!isset($items2_sum[$bdi]['jml'])) {
                            $items2_sum[$bdi]['jml'] = 0;
                        }
                        $items2_sum[$bdi]['jml'] += isset($iRow['jml']) ? $iRow['jml'] : 0;
                        $items2_sum[$bdi]['produk_kode'] = isset($iRow['produk_kode']) ? $iRow['produk_kode'] : "-";
                        $items2_sum[$bdi]['barcode'] = isset($iRow['barcode']) ? $iRow['barcode'] : "-";
                        $iName = !empty($iRow['biaya_dasar_nama']) ? $iRow['biaya_dasar_nama'] : (!empty($iRow['nama']) ? $iRow['nama'] : (!empty($iRow['produk_dasar_nama']) ? $iRow['produk_dasar_nama'] : "-"));
                        $items2_sum[$bdi]['nama'] = $iName;
                        $items2_sum[$bdi]['satuan'] = isset($iRow['satuan']) ? $iRow['satuan'] : "-";
                    }
                }
            }

            $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";

            $numb = 0;
            foreach ($items2_sum as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $tblItems .= "<td>" . $val . "</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }

            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "463":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
                    //                    if (is_array($iLabel)) {
                    //                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
                    //                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                //                if ($k == "nama") {
                //                    $val = "total";
                //                }
                switch ($k) {
                    case "nama":
                        $val = "total";
                        break;
                    case "nett":
                    case "subtotal":

                        break;
                    default:
                        $val = "";
                        break;
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        /* --------------------------------------
         * ngabil dari item
         * --------------------------------------*/
        case "5833":
        case "5833r":
        case "9833":
        case "9833r":
        case "9855":
        case "9855r":
        case "3333":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                //                arrPrintPink($iSpec);
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
                    //                    if (is_array($iLabel)) {
                    //                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
                    //                    }
                    //

                    if (($k == "diskon_nama") && ($iSpec["diskon_id"] == 7)) {// pree produk, tampilkan barang bonusnya
                        $val = isset($iSpec["extern_nama"]) ? formatField_he_format($k, $iSpec["extern_nama"]) : "-";
                    }

                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;

        case "1485":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "5822":
        case "5823":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                if (sizeof($tambahan) > 0) {
                    foreach ($tambahan as $tSpec) {
                        if ($tSpec->produk_id == $pid) {
                            foreach ($tSpec as $tkey => $tval) {
                                if (!array_key_exists($tkey, $iSpec)) {
                                    $iSpec[$tkey] = $tval;
                                }
                            }
                        }
                    }
                }
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if ($k == 'produk_ord_diterima') {
                        $iSpec[$k] = $iSpec["produk_ord_jml"] - $iSpec["valid_qty"];
                    }

                    if (is_array($iLabel)) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;

        case "3675":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$iSpec["jenis_master"]]["modul"] . "/";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], $iSpec["jenis_master"], $modul_path) : "-";
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        }
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "771":
        case "1771":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
//                arrPrint($iSpec);
                $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : (isset($iSpec["reference_jenis"]) ? $iSpec["reference_jenis"] : "");//untuk pengenal antar modul
//                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$iSpec["jenis_ref_po"]]["modul"] . "/";
                $modul_path = ($key_jenis_tr != "" && isset($ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"])) ? base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/" : "";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : "", $modul_path) : NULL;
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format(isset($iLabel["key"]) ? $iLabel["key"] : "", $iSpec[$k]) : NULL;
                        }
                        if ($val == NULL) {
                            $val = (isset($iLabel["key_cadangan"]) && isset($iSpec[$iLabel["key_cadangan"]])) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                        }

                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
//            $tblItems .= "<br>";
            //-----------------------------------------------
            if (sizeof($headers_sub) > 1 && isset($arrDataTransaksi["items2_sum"]) && sizeof($arrDataTransaksi["items2_sum"]) > 0) {

                $totalBiayaCount = count($arrDataTransaksi["items2_sum"]);
                $trxIdVal = isset($arrDataTransaksi['id']) ? $arrDataTransaksi['id'] : (isset($arrDataTransaksi['transaksi_id']) ? $arrDataTransaksi['transaksi_id'] : rand(1000, 9999));
                $collapseUniqId = "collapse_biaya_" . $trxIdVal . "_" . rand(100, 999);

                $tblItems .= "<div style='margin-top: 5px; margin-bottom: 4px;'>";
                $tblItems .= "<button type='button' class='btn btn-xs' data-toggle='collapse' data-target='#" . $collapseUniqId . "' aria-expanded='false' aria-controls='" . $collapseUniqId . "' style='font-size:10.5px; font-weight:700; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:3px 9px; border-radius:4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);'>";
                $tblItems .= "<i class='fa fa-list-alt' style='margin-right:3px;'></i> Rincian Biaya (" . $totalBiayaCount . " Item) <i class='fa fa-chevron-down' style='font-size:9px; margin-left:3px;'></i>";
                $tblItems .= "</button>";
                $tblItems .= "</div>";

                $tblItems .= "<div class='collapse' id='" . $collapseUniqId . "' style='margin-top: 6px; margin-bottom: 4px;'>";

                $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($headers_sub as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                $summary = array();
                foreach ($arrDataTransaksi["items2_sum"] as $pid => $iSpec) {
                    $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : (isset($iSpec["reference_jenis"]) ? $iSpec["reference_jenis"] : "");//untuk pengenal antar modul
                    $modul_path = ($key_jenis_tr != "" && isset($ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"])) ? base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/" : "";
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($headers_sub as $k => $iLabel) {
                        if (is_array($iLabel)) {
                            if (isset($iLabel["format"])) {
                                $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : "", $modul_path) : NULL;
                            }
                            else {
                                $val = isset($iSpec[$k]) ? formatField_he_format(isset($iLabel["key"]) ? $iLabel["key"] : "", $iSpec[$k]) : NULL;
                            }
                            if ($val == NULL) {
                                $val = (isset($iLabel["key_cadangan"]) && isset($iSpec[$iLabel["key_cadangan"]])) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                            }
                            $addKey = "";
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $addKey = "";
                        }
                        $tblItems .= "<td>" . $val . "$addKey</td>";
                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($headers_sub as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    if ($k == "nama") {
                        $val = "total";
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";

                $tblItems .= "</div>";
            }

            break;

        case "759":
        case "758":
            $summableKeys = array("nilai_setor", "nilai_bayar", "sisa", "subtotal", "nilai_entry", "valid_qty", "qty", "produk_ord_jml", "nominal");
            if (sizeof($headers_sub) > 1) {
                $summary = array();
                $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px; margin-bottom:6px;'>";
                $tblItems .= "<thead><tr style='background:#f8fafc;'>";
                $tblItems .= "<th style='width:30px; text-align:center;'>No</th>";
                foreach ($headers_sub as $k => $iLabel) {
                    $thLabel = is_array($iLabel) ? $iLabel["label"] : $iLabel;
                    $alignTh = (in_array($k, $summableKeys) || $k == "nilai_setor") ? "text-align:right;" : "text-align:left;";
                    $tblItems .= "<th style='font-size:11px; font-weight:700; color:#334155; $alignTh'>" . $thLabel . "</th>";
                }
                $tblItems .= "</tr></thead>";
                $tblItems .= "<tbody>";
                $numb = 0;
                if (isset($arrDataTransaksi["items8_sum"]) && is_array($arrDataTransaksi["items8_sum"])) {
                    foreach ($arrDataTransaksi["items8_sum"] as $pid => $iSpec) {
                        $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : (isset($iSpec["reference_jenis"]) ? $iSpec["reference_jenis"] : "");
                        $modul_path = ($key_jenis_tr != "" && isset($ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"])) ? base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/" : "";
                        $numb++;
                        $tblItems .= "<tr>";
                        $tblItems .= "<td class='text-right' style='width:30px; vertical-align:middle;'>$numb</td>";
                        foreach ($headers_sub as $k => $iLabel) {
                            if ($k == "cash_account_nama") {
                                // Raw string agar nomor rekening bank tidak diformat sebagai rupiah/angka ribuan
                                $rawAcc = isset($iSpec[$k]) ? trim((string)$iSpec[$k]) : "-";
                                if (stripos($rawAcc, "tunai") !== false) {
                                    $renderedVal = "<span class='label label-success' style='font-size:10.5px; padding:2px 6px; font-weight:600;'><i class='fa fa-money'></i> " . htmlspecialchars($rawAcc) . "</span>";
                                }
                                elseif ($rawAcc != "" && $rawAcc != "-") {
                                    $labelAcc = (stripos($rawAcc, "rek") !== false) ? $rawAcc : "Rek: " . $rawAcc;
                                    $renderedVal = "<span class='label label-info' style='font-size:10.5px; padding:2px 6px; font-weight:600;'><i class='fa fa-bank'></i> " . htmlspecialchars($labelAcc) . "</span>";
                                }
                                else {
                                    $renderedVal = "-";
                                }
                                $addKey = "";
                            }
                            else {
                                if (is_array($iLabel)) {
                                    if (isset($iLabel["format"])) {
                                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : "", $modul_path) : NULL;
                                    }
                                    else {
                                        $val = isset($iSpec[$k]) ? formatField_he_format(isset($iLabel["key"]) ? $iLabel["key"] : "", $iSpec[$k]) : NULL;
                                    }
                                    if ($val == NULL) {
                                        $val = (isset($iLabel["key_cadangan"]) && isset($iSpec[$iLabel["key_cadangan"]])) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                                    }
                                    $addKey = "";
                                }
                                else {
                                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                                    $addKey = "";
                                }
                                $renderedVal = $val;
                            }

                            $isSummableCol = in_array($k, $summableKeys);
                            $tdAlign = ($isSummableCol || $k == "nilai_setor") ? "class='text-right'" : "";
                            $tblItems .= "<td " . $tdAlign . " style='vertical-align:middle;'>" . $renderedVal . "$addKey</td>";

                            // Hanya akumulasikan jika kolom numerik keuangan yang diizinkan
                            if ($isSummableCol && isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                                if (!isset($summary[$k])) {
                                    $summary[$k] = 0;
                                }
                                $summary[$k] += $iSpec[$k];
                            }
                        }
                        $tblItems .= "</tr>";
                    }
                }
                $tblItems .= "</tbody>";
                $tblItems .= "<tfoot><tr style='background:#f1f5f9; font-weight:bold;'>";
                $tblItems .= "<td class='text-right' style='vertical-align:middle;'></td>";
                $firstTextColDone = false;
                foreach ($headers_sub as $k => $iLabel) {
                    $isSummableCol = in_array($k, $summableKeys);
                    if (!$isSummableCol && !$firstTextColDone) {
                        $val = "<span style='font-size:11px; font-weight:700; color:#1e293b; text-transform:uppercase;'>Total Setoran</span>";
                        $firstTextColDone = true;
                    }
                    elseif ($isSummableCol && isset($summary[$k])) {
                        $val = "<span style='font-size:11.5px; font-weight:700; color:#0f172a;'>" . formatField_he_format($k, $summary[$k]) . "</span>";
                    }
                    else {
                        $val = "-";
                    }
                    $tdAlign = ($isSummableCol || $k == "nilai_setor") ? "class='text-right'" : "";
                    $tblItems .= "<td " . $tdAlign . " style='vertical-align:middle;'>" . $val . "</td>";
                }
                $tblItems .= "</tr></tfoot>";
                $tblItems .= "</table>";
            }

            // Normalisasi dan unpacking rincian nota konsumen
            $notaItems = array();
            if (isset($arrDataTransaksi["items"]) && is_array($arrDataTransaksi["items"])) {
                foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    $itemNama = isset($iSpec["nama"]) ? (string)$iSpec["nama"] : "";
                    $itemId = !empty($iSpec["id"]) ? $iSpec["id"] : $pid;

                    // Deteksi jika item merupakan transaksi setor tunai (756) yang merangkum nota-nota penerimaan kasir
                    $isSetorTunai = (strpos($itemNama, "756.") === 0 || (isset($iSpec["reference_jenis"]) && $iSpec["reference_jenis"] == "756") || (isset($iSpec["jenis_source"]) && $iSpec["jenis_source"] == "756"));

                    $unpacked = false;
                    if ($isSetorTunai && !empty($itemId)) {
                        // Ambil rincian nota konsumen riil dari items3_sum milik transaksi 756
                        $qReg = $ci->db->select("items3_sum")->where("transaksi_id", intval($itemId))->get("transaksi_data_registry");
                        if ($qReg && $qReg->num_rows() > 0) {
                            $rReg = $qReg->row();
                            $subItems = blobDecode($rReg->items3_sum);
                            if (is_array($subItems) && sizeof($subItems) > 0) {
                                foreach ($subItems as $subKey => $subSpec) {
                                    $subNota = $iSpec;
                                    $subNota["id"] = !empty($subSpec["id"]) ? $subSpec["id"] : $subKey;
                                    $subNota["nama"] = !empty($subSpec["refNum"]) ? trim($subSpec["refNum"]) : (!empty($subSpec["nomer"]) ? trim($subSpec["nomer"]) : $itemNama);
                                    $subNota["extern2_nama"] = !empty($subSpec["extern2_nama"]) ? trim($subSpec["extern2_nama"]) : (!empty($subSpec["customers_nama"]) ? trim($subSpec["customers_nama"]) : "-");

                                    $nilai = 0;
                                    if (isset($subSpec["sisa"]) && is_numeric($subSpec["sisa"]) && $subSpec["sisa"] > 0) {
                                        $nilai = $subSpec["sisa"];
                                    }
                                    elseif (isset($subSpec["tagihan"]) && is_numeric($subSpec["tagihan"]) && $subSpec["tagihan"] > 0) {
                                        $nilai = $subSpec["tagihan"];
                                    }
                                    elseif (isset($subSpec["nilai_setor"]) && is_numeric($subSpec["nilai_setor"])) {
                                        $nilai = $subSpec["nilai_setor"];
                                    }

                                    $subNota["nilai_bayar"] = $nilai;
                                    $subNota["nilai_setor"] = $nilai;
                                    $subNota["subtotal"] = $nilai;
                                    $subNota["sisa"] = $nilai;
                                    if (isset($subSpec["keterangan"])) {
                                        $subNota["keterangan"] = $subSpec["keterangan"];
                                    }

                                    $notaItems[] = $subNota;
                                }
                                $unpacked = true;
                            }
                        }
                    }

                    if (!$unpacked) {
                        if (isset($iSpec["extern2_nama"]) && strtolower($iSpec["extern2_nama"]) == "sys_shadow") {
                            $iSpec["extern2_nama"] = "Setor Tunai (Internal)";
                        }
                        $notaItems[] = $iSpec;
                    }
                }
            }

            $totalNotaCount = count($notaItems);
            $trxIdVal = isset($arrDataTransaksi['id']) ? $arrDataTransaksi['id'] : (isset($arrDataTransaksi['transaksi_id']) ? $arrDataTransaksi['transaksi_id'] : rand(1000, 9999));
            $collapseUniqId = "collapse_nota_" . $trxIdVal . "_" . rand(100, 999);

            $tblItems .= "<div style='margin-top: 4px; margin-bottom: 4px;'>";
            $tblItems .= "<button type='button' class='btn btn-xs' data-toggle='collapse' data-target='#" . $collapseUniqId . "' aria-expanded='false' aria-controls='" . $collapseUniqId . "' style='font-size:10.5px; font-weight:700; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:3px 9px; border-radius:4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);'>";
            $tblItems .= "<i class='fa fa-list-alt' style='margin-right:3px;'></i> Rincian Nota Konsumen (" . $totalNotaCount . " Nota) <i class='fa fa-chevron-down' style='font-size:9px; margin-left:3px;'></i>";
            $tblItems .= "</button>";
            $tblItems .= "</div>";

            $tblItems .= "<div class='collapse' id='" . $collapseUniqId . "' style='margin-top: 6px; margin-bottom: 4px;'>";
            $tblItems .= "<div style='font-size: 11px; font-weight: 700; color: #475569; margin-bottom: 4px; padding: 2px 4px;'><i class='fa fa-users'></i> Daftar Nota Konsumen yang Disetor:</div>";

            $summary = array(); // Reset summary agar tidak terakumulasi ganda dari tabel sebelumnya
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px; margin-bottom:2px;'>";
            $tblItems .= "<thead><tr style='background:#f8fafc;'>";
            $tblItems .= "<th style='width:30px; text-align:center;'>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                $thLabel = is_array($iLabel) ? $iLabel["label"] : $iLabel;
                $alignTh = (in_array($k, $summableKeys) || $k == "nilai_setor" || $k == "nilai_bayar") ? "text-align:right;" : "text-align:left;";
                $tblItems .= "<th style='font-size:11px; font-weight:700; color:#334155; $alignTh'>" . $thLabel . "</th>";
            }
            $tblItems .= "</tr></thead>";
            $tblItems .= "<tbody>";
            $numb = 0;
            foreach ($notaItems as $pid => $iSpec) {
                $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : (isset($iSpec["reference_jenis"]) ? $iSpec["reference_jenis"] : "");
                $modul_path = ($key_jenis_tr != "" && isset($ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"])) ? base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/" : "";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right' style='width:30px; vertical-align:middle;'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if ($k == "cash_account_nama") {
                        // Raw string agar nomor rekening bank tidak diformat sebagai rupiah/angka ribuan
                        $rawAcc = isset($iSpec[$k]) ? trim((string)$iSpec[$k]) : "-";
                        if (stripos($rawAcc, "tunai") !== false) {
                            $renderedVal = "<span class='label label-success' style='font-size:10.5px; padding:2px 6px; font-weight:600;'><i class='fa fa-money'></i> " . htmlspecialchars($rawAcc) . "</span>";
                        }
                        elseif ($rawAcc != "" && $rawAcc != "-") {
                            $labelAcc = (stripos($rawAcc, "rek") !== false) ? $rawAcc : "Rek: " . $rawAcc;
                            $renderedVal = "<span class='label label-info' style='font-size:10.5px; padding:2px 6px; font-weight:600;'><i class='fa fa-bank'></i> " . htmlspecialchars($labelAcc) . "</span>";
                        }
                        else {
                            $renderedVal = "-";
                        }
                        $addKey = "";
                    }
                    else {
                        if (is_array($iLabel)) {
                            if (isset($iLabel["format"])) {
                                $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : "", $modul_path) : NULL;
                            }
                            else {
                                $val = isset($iSpec[$k]) ? formatField_he_format(isset($iLabel["key"]) ? $iLabel["key"] : "", $iSpec[$k]) : NULL;
                            }
                            if ($val == NULL) {
                                $val = (isset($iLabel["key_cadangan"]) && isset($iSpec[$iLabel["key_cadangan"]])) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                            }
                            $addKey = (isset($iLabel["addKey"]) && !empty($iSpec[$iLabel["addKey"]])) ? "<br><span style='font-size:10px; color:#64748b;'>" . htmlspecialchars($iSpec[$iLabel["addKey"]]) . "</span>" : "";
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $addKey = "";
                        }
                        $renderedVal = $val;
                    }

                    $isSummableCol = in_array($k, $summableKeys);
                    $tdAlign = ($isSummableCol || $k == "nilai_setor" || $k == "nilai_bayar") ? "class='text-right'" : "";
                    $tblItems .= "<td " . $tdAlign . " style='vertical-align:middle;'>" . $renderedVal . "$addKey</td>";

                    // Hanya akumulasikan jika kolom numerik yang diizinkan
                    if ($isSummableCol && isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "</tbody>";
            $tblItems .= "<tfoot><tr style='background:#f1f5f9; font-weight:bold;'>";
            $tblItems .= "<td class='text-right' style='vertical-align:middle;'></td>";
            $firstTextColDone = false;
            foreach ($arrItemShow as $k => $iLabel) {
                $isSummableCol = in_array($k, $summableKeys);
                if (!$isSummableCol && !$firstTextColDone) {
                    $val = "<span style='font-size:11px; font-weight:700; color:#1e293b; text-transform:uppercase;'>Total Nilai Nota</span>";
                    $firstTextColDone = true;
                }
                elseif ($isSummableCol && isset($summary[$k])) {
                    $val = "<span style='font-size:11.5px; font-weight:700; color:#0f172a;'>" . formatField_he_format($k, $summary[$k]) . "</span>";
                }
                else {
                    $val = "-";
                }
                $tdAlign = ($isSummableCol || $k == "nilai_setor" || $k == "nilai_bayar") ? "class='text-right'" : "";
                $tblItems .= "<td " . $tdAlign . " style='vertical-align:middle;'>" . $val . "</td>";
            }
            $tblItems .= "</tr></tfoot>";
            $tblItems .= "</table>";
            $tblItems .= "</div>";
            break;

        default:

            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
//                arrPrint($iSpec);
                $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : (isset($iSpec["reference_jenis"]) ? $iSpec["reference_jenis"] : "");//untuk pengenal antar modul
//                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$iSpec["jenis_ref_po"]]["modul"] . "/";
                $modul_path = ($key_jenis_tr != "" && isset($ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"])) ? base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/" : "";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : "", $modul_path) : NULL;
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format(isset($iLabel["key"]) ? $iLabel["key"] : "", $iSpec[$k]) : NULL;
                        }
                        if ($val == NULL) {
                            $val = (isset($iLabel["key_cadangan"]) && isset($iSpec[$iLabel["key_cadangan"]])) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                        }

                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
    }


    return $tblItems;

}

//-------------------------------
function resolveReturnProjectRowFields(&$row)
{
    if (!is_object($row)) {
        return;
    }
    $jenisMaster = isset($row->jenis_master) ? $row->jenis_master : (isset($row->jenis) ? $row->jenis : "");
    if (empty($jenisMaster) && isset($row->nomer)) {
        $parts = explode(".", $row->nomer);
        $jenisMaster = $parts[0];
    }

    $isReturnMod = in_array($jenisMaster, array("9834", "9834r", "9856", "9856r", "9833", "9833r", "9855", "9855r", "5834", "5834r", "5856", "5833", "5833r", "5855"));
    if (!$isReturnMod) {
        return;
    }

    // SPK No detection
    if (!isset($row->pihakProjekNoSpk) || empty($row->pihakProjekNoSpk) || $row->pihakProjekNoSpk == "-") {
        if (!empty($row->pihakProjekTasklistSPK) && $row->pihakProjekTasklistSPK != "-") {
            $row->pihakProjekNoSpk = $row->pihakProjekTasklistSPK;
        }
        elseif (!empty($row->projectTasklist__no_spk) && $row->projectTasklist__no_spk != "-") {
            $row->pihakProjekNoSpk = $row->projectTasklist__no_spk;
        }
        elseif (!empty($row->referensi_nomer)) {
            $row->pihakProjekNoSpk = $row->referensi_nomer;
        }
    }

    $tId = !empty($row->projectTasklist__id) ? $row->projectTasklist__id : (!empty($row->pihakProjekTasklistID) ? $row->pihakProjekTasklistID : 0);
    $spkNo = !empty($row->pihakProjekNoSpk) ? $row->pihakProjekNoSpk : "";

    // Query tasklist if we need either Proyek, WO, or Task Name
    $needQuery = (empty($row->pihakProjekName) || $row->pihakProjekName == "Projek" || $row->pihakProjekName == "-") ||
        (empty($row->pihakProjekWorkOrderNama) || $row->pihakProjekWorkOrderNama == "-") ||
        (empty($row->pihakProjekTasklistName) || $row->pihakProjekTasklistName == "-" || (isset($row->pihakProjekNoSpk) && $row->pihakProjekTasklistName == $row->pihakProjekNoSpk));

    if ($needQuery && (!empty($tId) || !empty($spkNo))) {
        $ci = &get_instance();
        $rTsk = null;
        if (!empty($tId)) {
            $ci->db->select("nama, produk_paket_nama, produk_nama")->from("project_tasklist")->where("id", intval($tId))->limit(1);
            $rTsk = $ci->db->get()->row();
            if (empty($rTsk)) {
                $ci->db->select("nama, produk_paket_nama, produk_nama")->from("project_tasklist_tambahan")->where("id", intval($tId))->limit(1);
                $rTsk = $ci->db->get()->row();
            }
        }
        if (empty($rTsk) && !empty($spkNo)) {
            $ci->db->select("nama, produk_paket_nama, produk_nama")->from("project_tasklist")->where("no_spk", $spkNo)->limit(1);
            $rTsk = $ci->db->get()->row();
            if (empty($rTsk)) {
                $ci->db->select("nama, produk_paket_nama, produk_nama")->from("project_tasklist_tambahan")->where("no_spk", $spkNo)->limit(1);
                $rTsk = $ci->db->get()->row();
            }
        }

        if (!empty($rTsk)) {
            if ((empty($row->pihakProjekName) || $row->pihakProjekName == "Projek" || $row->pihakProjekName == "-") && !empty($rTsk->nama)) {
                $row->pihakProjekName = $rTsk->nama;
            }
            if ((empty($row->pihakProjekWorkOrderNama) || $row->pihakProjekWorkOrderNama == "-") && !empty($rTsk->produk_paket_nama)) {
                $row->pihakProjekWorkOrderNama = $rTsk->produk_paket_nama;
            }
            if ((empty($row->pihakProjekTasklistName) || $row->pihakProjekTasklistName == "-" || $row->pihakProjekTasklistName == $row->pihakProjekNoSpk) && !empty($rTsk->produk_nama)) {
                $row->pihakProjekTasklistName = $rTsk->produk_nama;
            }
        }
    }

    // Fallback Proyek Nama
    if (!isset($row->pihakProjekName) || empty($row->pihakProjekName) || $row->pihakProjekName == "Projek" || $row->pihakProjekName == "-") {
        if (!empty($row->projectName) && $row->projectName != "Projek") {
            $row->pihakProjekName = $row->projectName;
        }
        elseif (!empty($row->produkProjek__nama) && $row->produkProjek__nama != "Projek") {
            $row->pihakProjekName = $row->produkProjek__nama;
        }
        elseif (!empty($row->projectID) || !empty($row->pihakProjekID) || !empty($row->produkProjek)) {
            $pId = !empty($row->projectID) ? $row->projectID : (!empty($row->pihakProjekID) ? $row->pihakProjekID : $row->produkProjek);
            $ci = &get_instance();
            $ci->db->select("nama")->from("project_produk")->where("id", intval($pId))->limit(1);
            $rPrj = $ci->db->get()->row();
            if (!empty($rPrj) && !empty($rPrj->nama)) {
                $row->pihakProjekName = $rPrj->nama;
            }
        }
    }

    if (!isset($row->pihakProjekName) || empty($row->pihakProjekName)) {
        $row->pihakProjekName = "-";
    }

    if (!isset($row->pihakProjekNoSpk) || empty($row->pihakProjekNoSpk)) {
        $row->pihakProjekNoSpk = "-";
    }

    // Fallback Work Order
    if (!isset($row->pihakProjekWorkOrderNama) || empty($row->pihakProjekWorkOrderNama) || $row->pihakProjekWorkOrderNama == "-") {
        if (!empty($row->pihakProjekWorkOrderSubNama) && $row->pihakProjekWorkOrderSubNama != "-") {
            $row->pihakProjekWorkOrderNama = $row->pihakProjekWorkOrderSubNama;
        }
        elseif (!empty($row->gudangWorkOrderTargetName)) {
            $row->pihakProjekWorkOrderNama = str_replace("gudang workorder ", "", $row->gudangWorkOrderTargetName);
        }
        elseif (!empty($row->pihakProjekWorkorderGudangName)) {
            $row->pihakProjekWorkOrderNama = str_replace("gudang workorder ", "", $row->pihakProjekWorkorderGudangName);
        }
        else {
            $row->pihakProjekWorkOrderNama = "-";
        }
    }

    // Fallback Tasklist Name
    if (empty($row->pihakProjekTasklistName)) {
        $row->pihakProjekTasklistName = "-";
    }

    // Cabang Asal vs Cabang Tujuan (DC)
    if (!isset($row->cabang_nama) || empty($row->cabang_nama) || $row->cabang_nama == "-") {
        if (!empty($row->place_nama)) {
            $row->cabang_nama = $row->place_nama;
        }
        elseif (!empty($row->cabangName)) {
            $row->cabang_nama = $row->cabangName;
        }
    }
    if (!isset($row->cabang2_nama) || empty($row->cabang2_nama) || $row->cabang2_nama == "-") {
        if (!empty($row->place2_nama)) {
            $row->cabang2_nama = $row->place2_nama;
        }
        elseif (!empty($row->cabang2Name)) {
            $row->cabang2_nama = $row->cabang2Name;
        }
        else {
            $row->cabang2_nama = "DC/PUSAT";
        }
    }
}

function viewDetailTransaksiMarketplace($arrDataTransaksi, $arrDataTransaksiMain, $arrItemShow = array(), $mode = "1336", $show = true, $tambahan = array())
{
    $ci = &get_instance();
    $show_css = $show == true ? "o" : "hidden";
    $tblItems = "";
    $arrItemShow = array(
        "extern_nama" => array(
            "label" => "nama",
            //            "addKey" => "keterangan",
        ),
        "referenceNomer__2" => "sales order",
        "nama" => "packinglist",
        "nilai_bayar" => "diterima",
    );
    $arrSumShow = array(
        "nilai_biaya" => "biaya suport/biaya merchant",
        "pph23" => "potongan pph23",
        "nilai_entry" => "uang diterima",
    );

    switch ($mode) {
        default:
            $tblItems .= "<table name='tableHistoryShow___' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi as $pid => $iSpec) {
                $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : $iSpec["reference_jenis"];//untuk pengenal antar modul
                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], $iSpec["jenis_ref_po"], $modul_path) : NULL;
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : NULL;
                        }
                        if ($val == NULL) {
                            $val = isset($iSpec[$iLabel["key_cadangan"]]) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                        }

                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "jumlah";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            foreach ($arrSumShow as $k => $iLabel) {
                $val = isset($arrDataTransaksiMain[$k]) ? formatField_he_format($k, $arrDataTransaksiMain[$k]) : "-";
                $tblItems .= "<tr>";
                $tblItems .= "<td colspan='4'>$iLabel</td>";
                $tblItems .= "<td>" . $val . "</td>";
                $tblItems .= "</tr>";
            }
            $tblItems .= "</table >";
            break;
    }


    return $tblItems;

}

function viewDetailTransaksiRebate($arrDataTransaksi, $arrItemShow, $mode = "1336", $show = true, $tambahan = array(), $gate = "items")
{
    $ci = &get_instance();
    $show_css = $show == true ? "o" : "hidden";
    $tblItems = "";

    switch ($mode) {
        case "1336_":
            $colspan = count($arrItemShow) - 2;
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
            //                $tblItems .= "<tr>";
            //                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
            ////                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
            //                $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "<th>hasil</th>";
            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            foreach ($arrDataTransaksi["items"] as $iSpec) {
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }
            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
            $tblItems .= "<tr>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $tblItems .= "<tr>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "1336":
            $back_asal = "#f4a7fa";
            $back_hasil = "#b1faa7";
            $colspan = count($arrItemShow);
            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
            $tblItems .= "</tr>";

            $tblItems .= "<tr>";

            //// asal konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_asal;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "<tr>";
            $noa = 0;
            foreach ($arrDataTransaksi["items"] as $iSpec) {
                $noa++;
                $tblItems .= "<td style='vertical-align:top;'>" . $noa . "</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $class = "";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        $class = "text-center";
                    }
                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
                }
            }

            $tblItems .= "</tr>";
            $tblItems .= "</table>";
            $tblItems .= "</td>";

            //// hasil konversi
            $tblItems .= "<td style='vertical-align:top;'>";
            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_hasil;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th >No.</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $noh = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
                    $noh++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td>" . $noh . "</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
                    }
                    $tblItems .= "</tr>";
                }
            }
            $tblItems .= "</table>";
            $tblItems .= "</td>";


            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "4464":
        case "4822":
            if (sizeof($arrDataTransaksi["items2"]) > 0) {
                foreach ($arrDataTransaksi["items2"] as $nomer => $specc) {
                    $f_nomer = str_replace('.', '', $nomer);
                    $tblItems .= "<div class='pull-left' style='margin-bottom: 2px;min-width: 150px;'><span width='50px' class='text-left text-bold text-uppercase'>$nomer</span> <i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i></div>";
                    $tblItems .= "<table name='tableHistoryShow' id='showTable_$f_nomer' class='table table-bordered dataTable compact hidden' style='margin-top:0px;'>";
                    $tblItems .= "<tr>";
                    $tblItems .= "<th>No</th>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        if (is_array($iLabel)) {
                            $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                        }
                        else {
                            $tblItems .= "<th >$iLabel</th>";
                        }
                    }
                    $tblItems .= "</tr>";
                    $numb = 0;
                    //                    foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    foreach ($specc as $pid => $iSpec) {
                        $numb++;
                        $tblItems .= "<tr>";
                        $tblItems .= "<td class='text-right'>$numb</td>";
                        foreach ($arrItemShow as $k => $iLabel) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $tblItems .= "<td>" . $val . "</td>";
                            if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                                if (!isset($summary[$k])) {
                                    $summary[$k] = 0;
                                }
                                $summary[$k] += $iSpec[$k];
                            }
                        }
                        $tblItems .= "</tr>";
                    }

                    $tblItems .= "<tr class='bg-gray'>";
                    $tblItems .= "<td class='text-right'></td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                        if ($k == "nama") {
                            $val = "total";
                        }
                        $tblItems .= "<td>" . $val . "</td>";
                    }
                    $tblItems .= "</tr>";
                    $tblItems .= "</table><br>";

                }
            }
            else {
                $f_nomer = str_replace('.', '', '');
                // $tblItems .= "<i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i>";

                // id='showTable_$f_nomer'
                $tblItems .= "<table name='tableHistoryShow'  class='table table-bordered $show_css' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $tblItems .= "<td>" . $val . "</td>";
                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    if ($k == "nama") {
                        $val = "total";
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            break;
        /* --------------------------------------
         * ngabil dari item2
         * --------------------------------------*/
        case "5834":
            //        case "9833":
        case "9834":
        case "9856":

            //            echo "##############################";
            //            echo json_encode($arrDataTransaksi);
            //            echo "==============================<br><br>";

            //            arrPrintWebs($arrDataTransaksi);

            $items2_sum = array();
            $aliasBiaya = array();
            if (isset($arrDataTransaksi["items2"])) {
                foreach ($arrDataTransaksi["items2"] as $biy => $rItems2) {
                    $aliasBiaya[$biy] = $rItems2['biaya_nama'];
                    foreach ($rItems2 as $bdi => $iRow) {
                        //                        arrPrintWebs($iRow);
                        $items2_sum[$bdi]['jml'] += $iRow['jml'];
                        $items2_sum[$bdi]['produk_kode'] = "-";
                        $items2_sum[$bdi]['barcode'] = "-";
                        $items2_sum[$bdi]['nama'] = $iRow['biaya_dasar_nama'];
                    }
                }
            }

            $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";

            $numb = 0;
            foreach ($items2_sum as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $tblItems .= "<td>" . $val . "</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }

            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "463":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
                    //                    if (is_array($iLabel)) {
                    //                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
                    //                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                //                if ($k == "nama") {
                //                    $val = "total";
                //                }
                switch ($k) {
                    case "nama":
                        $val = "total";
                        break;
                    case "nett":
                    case "subtotal":

                        break;
                    default:
                        $val = "";
                        break;
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        /* --------------------------------------
         * ngabil dari item
         * --------------------------------------*/
        case "5833":
        case "3333":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                //                arrPrintPink($iSpec);
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                    $addKey = "";
                    //                    if (is_array($iLabel)) {
                    //                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
                    //                    }
                    //

                    if (($k == "diskon_nama") && ($iSpec["diskon_id"] == 7)) {// pree produk, tampilkan barang bonusnya
                        $val = isset($iSpec["extern_nama"]) ? formatField_he_format($k, $iSpec["extern_nama"]) : "-";
                    }

                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;

        case "1485":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
        case "5822":
        case "5823":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                if (sizeof($tambahan) > 0) {
                    foreach ($tambahan as $tSpec) {
                        if ($tSpec->produk_id == $pid) {
                            foreach ($tSpec as $tkey => $tval) {
                                if (!array_key_exists($tkey, $iSpec)) {
                                    $iSpec[$tkey] = $tval;
                                }
                            }
                        }
                    }
                }
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if ($k == 'produk_ord_diterima') {
                        $iSpec[$k] = $iSpec["produk_ord_jml"] - $iSpec["valid_qty"];
                    }

                    if (is_array($iLabel)) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;

        case "3675":
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$iSpec["jenis_master"]]["modul"] . "/";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], $iSpec["jenis_master"], $modul_path) : "-";
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                        }
                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;

        default:
            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
            $tblItems .= "<tr>";
            $tblItems .= "<th>No</th>";
            foreach ($arrItemShow as $k => $iLabel) {
                if (is_array($iLabel)) {
                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                }
                else {
                    $tblItems .= "<th >$iLabel</th>";
                }
            }
            $tblItems .= "</tr>";
            $numb = 0;
            //            cekHere("[$gate]");
            //            arrPrintWebs($arrDataTransaksi["items10_sum"]);
            //            foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
                $key_jenis_tr = isset($iSpec["jenis_ref_po"]) ? $iSpec["jenis_ref_po"] : $iSpec["reference_jenis"];//untuk pengenal antar modul
                $modul_path = base_url() . $ci->config->item("heTransaksi_ui")[$key_jenis_tr]["modul"] . "/";
                $numb++;
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'>$numb</td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        if (isset($iLabel["format"])) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["format"], $iSpec[$k], $iSpec["jenis_ref_po"], $modul_path) : NULL;
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : NULL;
                        }
                        if ($val == NULL) {
                            $val = isset($iSpec[$iLabel["key_cadangan"]]) ? formatField_he_format($iLabel["key_cadangan"], $iSpec[$iLabel["key_cadangan"]]) : "-";
                        }

                        $addKey = "";
                    }
                    else {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $addKey = "";
                    }
                    $tblItems .= "<td>" . $val . "$addKey</td>";
                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        if (!isset($summary[$k])) {
                            $summary[$k] = 0;
                        }
                        $summary[$k] += $iSpec[$k];
                    }
                }
                $tblItems .= "</tr>";
            }
            $tblItems .= "<tr>";
            $tblItems .= "<td class='text-right'></td>";
            foreach ($arrItemShow as $k => $iLabel) {
                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                if ($k == "nama") {
                    $val = "total";
                }
                $tblItems .= "<td>" . $val . "</td>";
            }
            $tblItems .= "</tr>";
            $tblItems .= "</table >";
            break;
    }

    //        echo $tblItems;
    return $tblItems;

}

function viewDetailFaktur($arrDataTransaksi, $arrItemShow, $mode = "1336", $show = true, $gate)
{
    $show_css = $show == true ? "o" : "hidden";
    $tblItems = "";

    switch ($mode) {
        //        case "1336_":
        //            $colspan = count($arrItemShow) - 2;
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
        ////                $tblItems .= "<tr>";
        ////                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
        //////                $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
        ////                $tblItems .= "</tr>";
        //
        //            $tblItems .= "<tr>";
        //
        //            //// asal konversi
        //            $tblItems .= "<td style='vertical-align:top;'>";
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
        //            $tblItems .= "<tr>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "<th>hasil</th>";
        //            $tblItems .= "</tr>";
        //            $tblItems .= "<tr>";
        //            foreach ($arrDataTransaksi[$gate] as $iSpec) {
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $class = "";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        $class = "text-center";
        //                    }
        //                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
        //                }
        //            }
        //            //// hasil konversi
        //            $tblItems .= "<td style='vertical-align:top;'>";
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;'>";
        //            $tblItems .= "<tr>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
        //                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
        //                    $tblItems .= "<tr>";
        //                    foreach ($arrItemShow as $k => $iLabel) {
        //                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
        //                    }
        //                    $tblItems .= "</tr>";
        //                }
        //            }
        //            $tblItems .= "</table>";
        //            $tblItems .= "</td>";
        //
        //
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table>";
        //            $tblItems .= "</td>";
        //
        //
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;
        //        case "1336":
        //            $back_asal = "#f4a7fa";
        //            $back_hasil = "#b1faa7";
        //            $colspan = count($arrItemShow);
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin-top:-5px;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Dari</th>";
        //            $tblItems .= "<th ccolspan='$colspan' style='vertical-align:top;'>Ke/hasil</th>";
        //            $tblItems .= "</tr>";
        //
        //            $tblItems .= "<tr>";
        //
        //            //// asal konversi
        //            $tblItems .= "<td style='vertical-align:top;'>";
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_asal;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th >No.</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //
        //            $tblItems .= "</tr>";
        //            $tblItems .= "<tr>";
        //            $noa = 0;
        //            foreach ($arrDataTransaksi[$gate] as $iSpec) {
        //                $noa++;
        //                $tblItems .= "<td style='vertical-align:top;'>" . $noa . "</td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $class = "";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        $class = "text-center";
        //                    }
        //                    $tblItems .= "<td style='vertical-align:top;'>" . $iSpec[$k] . "</td>";
        //                }
        //            }
        //
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table>";
        //            $tblItems .= "</td>";
        //
        //            //// hasil konversi
        //            $tblItems .= "<td style='vertical-align:top;'>";
        //            $tblItems .= "<table class='table table-bordered no-padding' style='margin:-5px;background-color:$back_hasil;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th >No.</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //            $noh = 0;
        //            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
        //                foreach ($arrDataTransaksi["items4"][$pid] as $iSpec) {
        //                    $noh++;
        //                    $tblItems .= "<tr>";
        //                    $tblItems .= "<td>" . $noh . "</td>";
        //                    foreach ($arrItemShow as $k => $iLabel) {
        //                        $tblItems .= "<td>" . $iSpec[$k] . "</td>";
        //                    }
        //                    $tblItems .= "</tr>";
        //                }
        //            }
        //            $tblItems .= "</table>";
        //            $tblItems .= "</td>";
        //
        //
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;
        //        case "4464":
        //        case "4822":
        //            if (sizeof($arrDataTransaksi["items2"]) > 0) {
        //                foreach ($arrDataTransaksi["items2"] as $nomer => $specc) {
        //                    $f_nomer = str_replace('.', '', $nomer);
        //                    $tblItems .= "<div class='pull-left' style='margin-bottom: 2px;min-width: 150px;'><span width='50px' class='text-left text-bold text-uppercase'>$nomer</span> <i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i></div>";
        //                    $tblItems .= "<table name='tableHistoryShow' id='showTable_$f_nomer' class='table table-bordered dataTable compact hidden' style='margin-top:0px;'>";
        //                    $tblItems .= "<tr>";
        //                    $tblItems .= "<th>No</th>";
        //                    foreach ($arrItemShow as $k => $iLabel) {
        //                        if (is_array($iLabel)) {
        //                            $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                        }
        //                        else {
        //                            $tblItems .= "<th >$iLabel</th>";
        //                        }
        //                    }
        //                    $tblItems .= "</tr>";
        //                    $numb = 0;
        ////                    foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
        //                    foreach ($specc as $pid => $iSpec) {
        //                        $numb++;
        //                        $tblItems .= "<tr>";
        //                        $tblItems .= "<td class='text-right'>$numb</td>";
        //                        foreach ($arrItemShow as $k => $iLabel) {
        //                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                            $tblItems .= "<td>" . $val . "</td>";
        //                            if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                                if (!isset($summary[$k])) {
        //                                    $summary[$k] = 0;
        //                                }
        //                                $summary[$k] += $iSpec[$k];
        //                            }
        //                        }
        //                        $tblItems .= "</tr>";
        //                    }
        //
        //                    $tblItems .= "<tr class='bg-gray'>";
        //                    $tblItems .= "<td class='text-right'></td>";
        //                    foreach ($arrItemShow as $k => $iLabel) {
        //                        $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        //                        if ($k == "nama") {
        //                            $val = "total";
        //                        }
        //                        $tblItems .= "<td>" . $val . "</td>";
        //                    }
        //                    $tblItems .= "</tr>";
        //                    $tblItems .= "</table><br>";
        //
        //                }
        //            }
        //            else {
        //                $f_nomer = str_replace('.', '', '');
        //                // $tblItems .= "<i id='tombolMata_$f_nomer' title='klik untuk lihat produk' onclick=\"showTableHistory('$f_nomer')\" class='fa fa-eye text-link text-bold pull-right'></i>";
        //
        //                // id='showTable_$f_nomer'
        //                $tblItems .= "<table name='tableHistoryShow'  class='table table-bordered $show_css' style='margin-top:0px;'>";
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<th>No</th>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    if (is_array($iLabel)) {
        //                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                    }
        //                    else {
        //                        $tblItems .= "<th >$iLabel</th>";
        //                    }
        //                }
        //                $tblItems .= "</tr>";
        //                $numb = 0;
        //                foreach ($arrDataTransaksi["items"] as $pid => $iSpec) {
        //                    $numb++;
        //                    $tblItems .= "<tr>";
        //                    $tblItems .= "<td class='text-right'>$numb</td>";
        //                    foreach ($arrItemShow as $k => $iLabel) {
        //                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                        $tblItems .= "<td>" . $val . "</td>";
        //                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                            if (!isset($summary[$k])) {
        //                                $summary[$k] = 0;
        //                            }
        //                            $summary[$k] += $iSpec[$k];
        //                        }
        //                    }
        //                    $tblItems .= "</tr>";
        //                }
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<td class='text-right'></td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        //                    if ($k == "nama") {
        //                        $val = "total";
        //                    }
        //                    $tblItems .= "<td>" . $val . "</td>";
        //                }
        //                $tblItems .= "</tr>";
        //                $tblItems .= "</table >";
        //            }
        //            break;
        //        /* --------------------------------------
        //         * ngabil dari item2
        //         * --------------------------------------*/
        //        case "5834":
        ////        case "9833":
        //        case "9834":
        //
        ////            echo "##############################";
        ////            echo json_encode($arrDataTransaksi);
        ////            echo "==============================<br><br>";
        //
        //            arrPrintWebs($arrDataTransaksi);
        //
        //            $items2_sum = array();
        //            $aliasBiaya = array();
        //            if (isset($arrDataTransaksi["items2"])) {
        //                foreach ($arrDataTransaksi["items2"] as $biy => $rItems2) {
        //                    $aliasBiaya[$biy] = $rItems2['biaya_nama'];
        //                    foreach ($rItems2 as $bdi => $iRow) {
        ////                        arrPrintWebs($iRow);
        //                        $items2_sum[$bdi]['jml'] += $iRow['jml'];
        //                        $items2_sum[$bdi]['produk_kode'] = "-";
        //                        $items2_sum[$bdi]['barcode'] = "-";
        //                        $items2_sum[$bdi]['nama'] = $iRow['biaya_dasar_nama'];
        //                    }
        //                }
        //            }
        //
        //            $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th>No</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //
        //            $numb = 0;
        //            foreach ($items2_sum as $pid => $iSpec) {
        //                $numb++;
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<td class='text-right'>$numb</td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                    $tblItems .= "<td>" . $val . "</td>";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        if (!isset($summary[$k])) {
        //                            $summary[$k] = 0;
        //                        }
        //                        $summary[$k] += $iSpec[$k];
        //                    }
        //                }
        //                $tblItems .= "</tr>";
        //            }
        //
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<td class='text-right'></td>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        //                if ($k == "nama") {
        //                    $val = "total";
        //                }
        //                $tblItems .= "<td>" . $val . "</td>";
        //            }
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;
        //        case "463":
        //            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th>No</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //            $numb = 0;
        //            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
        //                $numb++;
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<td class='text-right'>$numb</td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                    $addKey = "";
        ////                    if (is_array($iLabel)) {
        ////                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
        ////                    }
        //                    $tblItems .= "<td>" . $val . "$addKey</td>";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        if (!isset($summary[$k])) {
        //                            $summary[$k] = 0;
        //                        }
        //                        $summary[$k] += $iSpec[$k];
        //                    }
        //                }
        //                $tblItems .= "</tr>";
        //            }
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<td class='text-right'></td>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        ////                if ($k == "nama") {
        ////                    $val = "total";
        ////                }
        //                switch ($k) {
        //                    case "nama":
        //                        $val = "total";
        //                        break;
        //                    case "nett":
        //                    case "subtotal":
        //
        //                        break;
        //                    default:
        //                        $val = "";
        //                        break;
        //                }
        //                $tblItems .= "<td>" . $val . "</td>";
        //            }
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;
        //        /* --------------------------------------
        //         * ngabil dari item
        //         * --------------------------------------*/
        //        case "5833":
        //        case "3333":
        //            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th>No</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //            $numb = 0;
        //            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
        ////                arrPrintPink($iSpec);
        //                $numb++;
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<td class='text-right'>$numb</td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                    $addKey = "";
        ////                    if (is_array($iLabel)) {
        ////                        $addKey = isset($iSpec[$iLabel["addKey"]]) ? "<br>".formatField_he_format($k, $iSpec[$iLabel["addKey"]]) : "";
        ////                    }
        ////
        //
        //                    if (($k == "diskon_nama") && ($iSpec["diskon_id"] == 7)) {// pree produk, tampilkan barang bonusnya
        //                        $val = isset($iSpec["extern_nama"]) ? formatField_he_format($k, $iSpec["extern_nama"]) : "-";
        //                    }
        //
        //                    $tblItems .= "<td>" . $val . "$addKey</td>";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        if (!isset($summary[$k])) {
        //                            $summary[$k] = 0;
        //                        }
        //                        $summary[$k] += $iSpec[$k];
        //                    }
        //                }
        //                $tblItems .= "</tr>";
        //            }
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<td class='text-right'></td>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        //                if ($k == "nama") {
        //                    $val = "total";
        //                }
        //                $tblItems .= "<td>" . $val . "</td>";
        //            }
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;
        //
        //        case "1485":
        //            $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<th>No</th>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                if (is_array($iLabel)) {
        //                    $tblItems .= "<th >" . $iLabel["label"] . "</th>";
        //                }
        //                else {
        //                    $tblItems .= "<th >$iLabel</th>";
        //                }
        //            }
        //            $tblItems .= "</tr>";
        //            $numb = 0;
        //            foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
        //                $numb++;
        //                $tblItems .= "<tr>";
        //                $tblItems .= "<td class='text-right'>$numb</td>";
        //                foreach ($arrItemShow as $k => $iLabel) {
        //                    if (is_array($iLabel)) {
        //                        $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
        //                        $addKey = "";
        //                    }
        //                    else {
        //                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
        //                        $addKey = "";
        //                    }
        //                    $tblItems .= "<td>" . $val . "$addKey</td>";
        //                    if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
        //                        if (!isset($summary[$k])) {
        //                            $summary[$k] = 0;
        //                        }
        //                        $summary[$k] += $iSpec[$k];
        //                    }
        //                }
        //                $tblItems .= "</tr>";
        //            }
        //            $tblItems .= "<tr>";
        //            $tblItems .= "<td class='text-right'></td>";
        //            foreach ($arrItemShow as $k => $iLabel) {
        //                $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
        //                if ($k == "nama") {
        //                    $val = "total";
        //                }
        //                $tblItems .= "<td>" . $val . "</td>";
        //            }
        //            $tblItems .= "</tr>";
        //            $tblItems .= "</table >";
        //            break;

        default:
            if (isset($arrDataTransaksi[$gate]) && sizeof($arrDataTransaksi[$gate]) > 0) {
                $tblItems .= "<table name='tableHistoryShow' class='table table-bordered $show_css' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                foreach ($arrDataTransaksi[$gate] as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        if (is_array($iLabel)) {
                            $val = isset($iSpec[$k]) ? formatField_he_format($iLabel["key"], $iSpec[$k]) : "-";
                            $addKey = "";
                        }
                        else {
                            $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                            $addKey = "";
                        }
                        $tblItems .= "<td>" . $val . "$addKey</td>";
                        //                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                        //                            if (!isset($summary[$k])) {
                        //                                $summary[$k] = 0;
                        //                            }
                        //                            $summary[$k] += $iSpec[$k];
                        //                        }
                    }
                    $tblItems .= "</tr>";
                }
                //                $tblItems .= "<tr>";
                //                $tblItems .= "<td class='text-right'></td>";
                //                foreach ($arrItemShow as $k => $iLabel) {
                //                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                //                    if ($k == "nama") {
                //                        $val = "total";
                //                    }
                //                    $tblItems .= "<td>" . $val . "</td>";
                //                }
                //                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            break;
    }


    return $tblItems;

}

function selectKurir($selected_kurir = 0, $linkSave)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlEmployeeKirim");

    $k = New MdlEmployeeKirim();
    $kTmp = $k->lookupAll()->result();

    $str = "<select id='idselect' class='btn btn-warning' onchange=\"$('#result').load('$linkSave?kid='+this.value)\">";
    $str .= "<option value='0'> pilih kurir/driver </option>";
    if (sizeof($kTmp) > 0) {
        foreach ($kTmp as $kSpec) {
            $kurir_id = $kSpec->id;
            $kurir_nama = $kSpec->nama;
            $selected = ($kurir_id == $selected_kurir) ? "selected" : "";
            $str .= "<option value='$kurir_id,$kurir_nama' data-nama='$kurir_nama' $selected> $kurir_nama </option>";
        }
    }
    $str .= "</select>";
    return $str;
}

function selectWorker($selected_id = 0, $linkSave)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlEmployeeWorker");

    $k = New MdlEmployeeWorker();
    $kTmp = $k->lookupAll()->result();

    $str = "<select id='idselect' class='btn btn-info text-uppercase' onchange=\"$('#result').load('$linkSave?kid='+this.value)\">";
    $str .= "<option value='0'> pilih teknisi </option>";
    if (sizeof($kTmp) > 0) {
        foreach ($kTmp as $kSpec) {
            $kurir_id = $kSpec->id;
            $kurir_nama = $kSpec->nama;
            $selected = ($kurir_id == $selected_id) ? "selected" : "";
            $str .= "<option value='$kurir_id,$kurir_nama' data-nama='$kurir_nama' $selected> $kurir_nama </option>";
        }
    }
    $str .= "</select>";
    return $str;
}

function viewFreeProduk($arrDataTransaksi, $arrItemShow, $mode = null)
{
    $tblItems = "";
    switch ($mode) {
        default:
            if (sizeof($arrDataTransaksi["items5_sum"]) > 0) {

                $tblItems .= "<table class='table table-bordered' style='margin-top:0px;'>";
                $tblItems .= "<tr>";
                $tblItems .= "<th>No</th>";
                foreach ($arrItemShow as $k => $iLabel) {
                    if (is_array($iLabel)) {
                        $tblItems .= "<th >" . $iLabel["label"] . "</th>";
                    }
                    else {
                        $tblItems .= "<th >$iLabel</th>";
                    }
                }
                $tblItems .= "</tr>";
                $numb = 0;
                foreach ($arrDataTransaksi["items5_sum"] as $pid => $iSpec) {
                    $numb++;
                    $tblItems .= "<tr>";
                    $tblItems .= "<td class='text-right'>$numb</td>";
                    foreach ($arrItemShow as $k => $iLabel) {
                        $val = isset($iSpec[$k]) ? formatField_he_format($k, $iSpec[$k]) : "-";
                        $tblItems .= "<td>" . $val . "</td>";
                        if (isset($iSpec[$k]) && is_numeric($iSpec[$k])) {
                            if (!isset($summary[$k])) {
                                $summary[$k] = 0;
                            }
                            $summary[$k] += $iSpec[$k];
                        }
                    }
                    $tblItems .= "</tr>";
                }
                $tblItems .= "<tr>";
                $tblItems .= "<td class='text-right'></td>";
                foreach ($arrItemShow as $k => $iLabel) {
                    $val = isset($summary[$k]) ? formatField_he_format($k, $summary[$k]) : "-";
                    switch ($k) {
                        case "produk_rel_nama":
                            $val = "total";
                            break;
                        case "qty":
                        case "subtotal":
                            $val = $val;
                            break;
                        default:
                            $val = "-";
                            break;
                    }
                    $tblItems .= "<td>" . $val . "</td>";
                }
                $tblItems .= "</tr>";
                $tblItems .= "</table >";
            }
            else {
                $tblItems .= "";
            }
            break;
    }

    //        echo $tblItems;
    return $tblItems;

}

//-------------------------------
function reloadDataProduk($arrDataTambahan, $arrDataTambahanValidate, $items, $items2)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProduk2");
    $m = new MdlProduk2();
    $arrTidakLengkap = array();

    $arrItemsKey = array_keys($items);
    $m->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $m->lookupAll()->result();
    if (sizeof($tmpB) > 0) {
        foreach ($tmpB as $row) {
            $rows = $row;
            $tmp = (array)$row;
            $produk_id = $idp = $row->id;
            if (!isset($items2[$idp])) {
                $items2[$idp] = array();
            }
            //handle serial 1
            $jml_serial = $rows->jml_serial;
            $items[$produk_id]['jml_serial'] = $jml_serial;
            if (($jml_serial * 1) == 1) {
                $d_kode = $rows->kode;
                $items2[$produk_id][$d_kode] = array();
                $items[$produk_id][$d_kode] = $items[$produk_id]['jml'];
                //-----
                foreach ($arrDataTambahanValidate["non_unit"] as $key_cek) {
                    if ($rows->$key_cek == NULL) {
                        $arrTidakLengkap[$rows_id] = $rows->nama;
                    }
                }
            }
            elseif (($jml_serial) > 1) {
                foreach ($arrDataTambahanValidate["unit"] as $key_cek) {
                    if ($rows->$key_cek == NULL) {
                        $arrTidakLengkap[$rows->id] = $rows->nama;
                    }
                }
            }

            //
            //arrPrintWebs($arrDataTambahan);
            //arrPrintKuning($arrDataTambahanValidate);
            //arrPrintHijau($arrTidakLengkap);

            $arrCat = array();
            $arrCode = array();
            foreach ($arrDataTambahan as $cat => $catSpec) {
                foreach ($catSpec as $dkey => $dval) {
                    if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                        $items2[$produk_id][$rows->$dval] = array();
                        if (!isset($arrCat[$cat])) {
                            $arrCat[$cat] = 0;
                        }
                        $arrCat[$cat] += 1;
                        if (!isset($arrCode[$rows->$dval])) {
                            $arrCode[$rows->$dval] = 0;
                        }
                        $arrCode[$rows->$dval] += 1;
                    }
                    //------------

                }
            }
            $keterangan = "";
            $static_keterangan = "";
            if (!empty($arrCat)) {
                foreach ($arrCat as $kcat => $vcat) {
                    $new_vcat = $vcat * $items[$idp]["jml"];
                    if ($keterangan == "") {
                        $keterangan = " $new_vcat $kcat";
                    }
                    else {
                        $keterangan .= "<br> $new_vcat $kcat";
                    }
                    if ($static_keterangan == "") {
                        $static_keterangan = " $vcat $kcat";
                    }
                    else {
                        $static_keterangan .= "<br> $vcat $kcat";
                    }
                    $new_keyy = "qty_" . $kcat;
                    $items[$idp][$new_keyy] = $vcat;
                }
            }
            if (!empty($arrCode)) {
                foreach ($arrCode as $kcat => $vcat) {
                    $new_vcat = $vcat * $items[$idp]["jml"];
                    $items[$idp][$kcat] = $new_vcat;
                }
            }
            $items[$idp]['keterangan'] = $keterangan;
            $items[$idp]['static_keterangan'] = $static_keterangan;


        }

        $result = array(
            "status" => (sizeof($arrTidakLengkap) > 0) ? 0 : 1,// 0 (tidak lengkap), 1 (lengkap)
            "items" => $items,
            "items2" => $items2,
            //-----
            "produk" => $arrTidakLengkap,
        );

        return $result;
    }
}

function reloadDataAset($items, $arrDataTambahan)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAsetDetail");
    $m = new MdlAsetDetail();
    $arrItemsKey = array_keys($items);
    $m->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $m->lookupAll()->result();
    $items = array();
    if (count($tmpB) > 0) {
        foreach ($tmpB as $tmpB_0) {
            foreach ($arrDataTambahan as $k_target => $key_src) {
                $items[$tmpB_0->id][$k_target] = $tmpB_0->$key_src;
            }
        }
    }
    $result = array(
        "items" => $items,
    );
    return $result;

}

//-------------------------------
function pisahBarangJasa($sesiData)
{

    $itemsRegulerBarang = array();
    $itemsRegulerJasa = array();
    $itemsPaketBarang = array();
    $itemsPaketJasa = array();

    if (sizeof($sesiData["items"]) > 0) {
        foreach ($sesiData["items"] as $pid => $iSpec) {
            $jenis = $iSpec["jenis"];
            $kategori_id = $iSpec["kategori_id"];
            switch ($jenis) {
                case "item_komposit":
                    break;
                case "item":
                    if ($kategori_id == 4) {// jasa
                        $itemsRegulerJasa["items4"][$pid] = $iSpec;
                    }
                    else {// barang
                        $itemsRegulerBarang["items9_sum"][$pid] = $iSpec;
                    }

                    break;
            }

        }
    }

    if (is_array($sesiData["items6"]) && (sizeof($sesiData["items6"]) > 0)) {
        foreach ($sesiData["items6"] as $paket_id => $iSpec) {
            foreach ($iSpec as $pid => $iiSpec) {
                $kategori_id = $iiSpec["kategori_id"];
                if ($kategori_id == 4) {// jasa
                    $itemsPaketJasa["items4_sum"][$paket_id][$pid] = $iiSpec;
                }
                else {// barang
                    $itemsPaketBarang["items10_sum"][$paket_id][$pid] = $iiSpec;
                }
            }
        }
    }


    $result = array(
        "items4" => sizeof($itemsRegulerJasa["items4"]) > 0 ? $itemsRegulerJasa["items4"] : array(),// jasa reguler
        "items9_sum" => sizeof($itemsRegulerBarang["items9_sum"]) > 0 ? $itemsRegulerBarang["items9_sum"] : array(),// barang reguler
        "items4_sum" => sizeof($itemsPaketJasa["items4_sum"]) > 0 ? $itemsPaketJasa["items4_sum"] : array(),// jasa dari paket
        "items10_sum" => sizeof($itemsPaketBarang["items10_sum"]) > 0 ? $itemsPaketBarang["items10_sum"] : array(),// barang dari paket
    );
    return $result;
}

function pisahProdukSupplies($sesiData)
{

    $itemsSupplies = array();
    $itemsProduk = array();
    $arrKey = array(
        "supplies" => array(
            "harga" => "harga_supplies",
            "hpp" => "hpp_supplies",
            "hpp_nppn" => "hpp_nppn_supplies",
            "hpp_nppv" => "hpp_nppv_supplies",
            "hpp_nppn_nppv" => "hpp_nppn_nppv_supplies",
            "nett" => "nett_supplies",
            "ppn" => "ppn_supplies",
        ),
        "item" => array(
            "harga" => "harga_produk",
            "hpp" => "hpp_produk",
            "hpp_nppn" => "hpp_nppn_produk",
            "hpp_nppv" => "hpp_nppv_produk",
            "hpp_nppn_nppv" => "hpp_nppn_nppv_produk",
            "nett" => "nett_produk",
            "ppn" => "ppn_produk",
        ),
    );

    if (sizeof($sesiData["items"]) > 0) {
        foreach ($sesiData["items"] as $pid => $iSpec) {
            $jenis_barang = isset($iSpec["jenis_barang"]) ? $iSpec["jenis_barang"] : "none";
            switch ($jenis_barang) {
                case "supplies":
                    foreach ($arrKey[$jenis_barang] as $kk => $vv) {
                        $iSpec[$vv] = isset($iSpec[$kk]) ? $iSpec[$kk] : 0;
                        $sub_kk = "sub_" . $kk;
                        unset($iSpec[$kk]);
                        unset($iSpec[$sub_kk]);
                    }
                    $itemsSupplies["items9_sum"][$pid] = $iSpec;
                    break;

                default:
                case "item":
                    foreach ($arrKey[$jenis_barang] as $kk => $vv) {
                        $iSpec[$vv] = isset($iSpec[$kk]) ? $iSpec[$kk] : 0;
                        $sub_kk = "sub_" . $kk;
                        unset($iSpec[$kk]);
                        unset($iSpec[$sub_kk]);
                    }
                    $itemsProduk["items10_sum"][$pid] = $iSpec;
                    break;
                //                default:
                //                    $msg = "jenis barang tidak diketahui. silahkan cek data barang anda.";
                //                    mati_disini($msg);
                //                    break;
            }

        }
    }
    //arrPrintKuning($itemsProduk["items10_sum"]);
    //mati_disini(__LINE__);

    $result = array(
        "items9_sum" => sizeof($itemsSupplies["items9_sum"]) > 0 ? $itemsSupplies["items9_sum"] : array(),// supplies
        "items10_sum" => sizeof($itemsProduk["items10_sum"]) > 0 ? $itemsProduk["items10_sum"] : array(),// produk
    );
    return $result;
}

function isTransaksiIdExist_misc($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");
    $tr = new MdlTransaksi();

    $ci->db->select("id");
    $condites = array(
        "id" => $transaksi_id
    );
    $tr->setFilters(array());
    $srcs = $tr->lookupByCondition($condites)->num_rows();

    return $srcs;
}

function uangMukaLabel()
{
    $arrData = array(
        "uang_muka_produk" => "uang muka produk",
        "uang_muka_jasa" => "uang muka jasa",
        "include_ppn" => "termasuk ppn",
        "exclude_ppn" => "belum termasuk ppn",
    );


    return $arrData;
}

//-------------------------------
function bgTransaksiColor()
{
    $bgColor = array(
        "orange" => "Transaksi Close/Fullfillment.",
        "yellow" => "Transaksi pernah diedit/dirubah.",
        "red" => "Transaksi dibatalkan/reject.",
    );

    return $bgColor;

}

//generate voucher
function getVoucherNumber($setType = 'tasklist', $trAlias = 'VC', $dt, $step_code, $modul = "pembelian")
{
    $ci = &get_instance();
    $arr = array();
    //cekMerah($setType);
    //    matiHEre($modul);
    //region penomoran receipt
    $ci->load->model("CustomCounter");
    $cn = new CustomCounter("transaksi");
    $cn->setType($setType);
    $cn->setModul($modul);
    $cn->setStepCode($step_code);

    $numBatch = array(
        "$trAlias" => array(
            "counters" => array(
                "stepCode",
                //                "stepCode|oleh_id",
                //                "stepCode|produk_id",
                //                "stepCode|fase_id",
                //                "stepCode|employee_id",
                //                "stepCode|month",
                //                "stepCode|years",
                //                "stepCode|month",
                //                "stepCode|day",
            ),
            //          "formatNota" => "stepCode|employee_id,.stepCode,.employee_id,stepCode|employee_id,.month,.years",
            "formatNota" => "stepCode,.month,.years,.",
        )
    );

    if (isset($dt['dtime'])) {
        $month_select = date("m", strtotime($dt['dtime']));
        $arr['day'] = date("d", strtotime($dt['dtime']));
        $arr['month'] = getRomawi($month_select);
        $arr['years'] = date("Y", strtotime($dt['dtime']));
    }

    $arr['stepCode'] = $trAlias;
    //    $arr['oleh_id']     = $dt['oleh_id'];
    //    $arr['produk_id']   = $dt['produk_id'];
    //    $arr['fase_id']     = $dt['fase_id'];
    //    $arr['employee_id'] = $dt['employee_id'];

    $counterForNumber = array($numBatch[$trAlias]['formatNota']);
    $configCustomParams = $numBatch[$trAlias]['counters'];

    if (sizeof($configCustomParams) > 0) {
        $cContent = array();
        foreach ($configCustomParams as $i => $cRawParams) {
            $cParams = explode("|", $cRawParams);
            $cValues = array();
            foreach ($cParams as $param) {
                $cValues[$i][$param] = $arr[$param];
            }
            $cRawValues = implode("|", $cValues[$i]);
            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
            //            arrprintwebs($paramSpec);
            switch ($paramSpec['id']) {
                case 0: //===counter type is new
                    //                    $addData = array(
                    //                        "toko_id" => $dt['toko_id'],
                    //                        "toko_nama" => $dt['toko_nama'],
                    //                    );
                    $paramKeyRaw = print_r($cParams, true);
                    $paramValuesRaw = print_r($cValues[$i], true);
                    $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                    break;
                default: //===counter to be updated
                    $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                    break;
            }
        }
    }
    $appliedCounters = base64_encode(serialize($cContent));
    $appliedCounters_inText = print_r($cContent, true);

    //cekMErah($paramSpec["paramString"]);
    $paramNum = (explode(".", $paramSpec["paramString"]));
    //matiHEre();

    $tmpNomorNota = array(
        "paramEncodeVoucher" => md5($paramNum[0] . "" . date("Ymd") . "." . $paramSpec["paramString"] . "" . $paramNum[1]),
        "paramVoucher" => $paramNum[0] . "" . date("Ymd") . "" . "" . $paramNum[1],
    );

    //arrPrint($tmpNomorNota);
    //cekHitam($tmpNomorNota);
    //matiHere();
    return $tmpNomorNota;
}

function getSerialGenerate($transaksi_id = NULL)
{
    if ($transaksi_id == NULL) {
        $msg = "Validasi nomer serial membutuhkan id transaksi saat ini. Silahkan cek lagi. code: " . __LINE__;
        mati_disini($msg);
    }
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukPerSerialNumber");
    $psn = New MdlProdukPerSerialNumber();
    $psn->addFilter("transaksi_id='$transaksi_id'");
    $psnTmp = $psn->lookupAll()->result();
    $hasil = array();
    if (sizeof($psnTmp) > 0) {
        foreach ($psnTmp as $psnSpec) {
            $pid = $psnSpec->produk_id;
            $pserial = $psnSpec->produk_serial_number_2;
            $pskupart = $psnSpec->produk_sku_part_nama;
            $hasil[$pid][$pskupart][$pserial] = $pserial;
        }
    }
    return $hasil;
}

function getTransaksiImageReference($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlTransaksiImageReference");
    $ti = new MdlTransaksiImageReference();
    if (is_array($transaksi_id)) {
        $ti->addFilter("id_master in ('" . implode("','", $transaksi_id) . "')");
    }
    else {
        $ti->addFilter("id_master='$transaksi_id'");
    }

    $tmpImages = $ti->lookUpAll()->result();
    $relImages = array();
    if (count($tmpImages) > 0) {
        foreach ($tmpImages as $tmpImages_0) {
            $relImages[$tmpImages_0->id_master] = array(
                "nama" => $tmpImages_0->nama,
                "link_img" => $tmpImages_0->cdn_link,
                "dtime" => $tmpImages_0->dtime,
            );
        }
    }
    return $relImages;

}


function validateRelasiUangMuka($transaksi_id)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");

    // region cek titipan relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("extern2_id='$transaksi_id'");
    $tr->addFilter("sisa>'0'");
    $trTmp = $tr->lookupAllUangMukaSrc()->result();
    //    cekMerah($ci->db->last_query());
    if (sizeof($trTmp) > 0) {
        $result = (array)$trTmp[0];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("id=" . $result["transaksi_id"]);
        $trTmpUm = $tr->lookupAll()->result();
        $result["nomer_um"] = $trTmpUm[0]->nomer;
        //        $result["nomer_po"] = $trTmpUm[0]->extern2_nama;
        $result["oleh_nama_um"] = $trTmpUm[0]->oleh_nama;
        $result["jenis"] = "titipan";


    }
    else {
        $result = array();
    }
    // endregion cek uang muka relasi po ada ppnnya

    // region cek uang muka relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("extern2_id='$transaksi_id'");
    $tr->addFilter("sisa>'0'");
    $tr->addFilter("label='uang muka supplier'");
    $trTmp = $tr->lookUpAllPaymentSrc()->result();
    //    cekHitam($ci->db->last_query());
    if (sizeof($trTmp) > 0) {
        $result = (array)$trTmp[0];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("id=" . $result["transaksi_id"]);
        $trTmpUm = $tr->lookupAll()->result();
        $result["nomer_um"] = $trTmpUm[0]->nomer;
        $result["oleh_nama_um"] = $trTmpUm[0]->oleh_nama;
        $result["jenis"] = "uangmuka";
    }
    // endregion cek uang muka relasi po

    //arrPrint($result);
    //    matiHere();
    return $result;

}

function validateRelasiPaymentSrc($masterID, $jenis, $traget_jenis, $label)
{
    $ci = &get_instance();
    $ci->load->model("MdlTransaksi");

    // region cek uang muka relasi po
    $tr = new MdlTransaksi();
    $tr->setFilters(array());
    $tr->addFilter("transaksi.link_id='0'");
    $tr->addFilter("transaksi.id_master='$masterID'");
    $tr->addFilter("transaksi.jenis='$jenis'");
    $tr->addFilter("transaksi_payment_source.sisa>'1000'");
    $tr->addFilter("transaksi_payment_source.target_jenis='$traget_jenis'");
    $tr->addFilter("transaksi_payment_source.label='$label'");

    //    matiHEre(__LINE__);
    $trTmp = $tr->paymentSrcRelasiMaster()->result();
    //    cekBiru($ci->db->last_query());
    //    arrprint($trTmp);
    //    matiHere(__LINE__);

    // endregion cek uang muka relasi po


    return $trTmp;

}

//-----------------------------
function cekOpnameAktif($jenisTr, $configUi, $cabang_id)
{
    $ci = &get_instance();
    $ci->load->library("Transaksional");
    $lt = New Transaksional();
    $ltTmp = $lt->cekOpnameAktive($cabang_id);
    //    showLast_query("biru");
    //    arrPrintCyan($ltTmp);
    if ($ltTmp["jml"] > 0) {
        $jenisTransaksiLabel = isset($configUi[$jenisTr]['label']) ? $configUi[$jenisTr]['label'] : false;
        $msg = "Transaksi $jenisTransaksiLabel belum bisa digunakan karena masih ada stok opname yang belum selesai.";
        $alerts = array(
            "type" => "warning",
            "html" => "$msg",
        );
        $link = base_url();
        $btn_label = "OKE";
        $btn_cancel = NULL;
        $arrWarningPlaceTrans = array(
            "alert" => array(
                "type" => "warning",
                "html" => "$msg",
            ),
            "link" => $link,
            "btn_label" => $btn_label,
            "btn_cancel" => $btn_cancel,
        );
    }

    if (isset($arrWarningPlaceTrans) && (sizeof($arrWarningPlaceTrans) > 0)) {
        return $arrWarningPlaceTrans;
    }
    else {
        return array();
    }
}

//-----------------------------
function reloadProdukData($sessionData, $configUiJenis, $renew = false)
{
    $ci = &get_instance();

    // region reload data produk sesuai config dari shoppingcart
    $arrItemsKey = array_keys($sessionData["items"]);
    $arrDataTambahan = isset($configUiJenis['produkUnitPart']) ? $configUiJenis['produkUnitPart'] : array();

    $selectorSrcModel = isset($sessionData['main']['pihakMdlNameSrc']) ? $sessionData['main']['pihakMdlNameSrc'] : $configUiJenis['selectorSrcModel'];
    $fieldSrcs = isset($configUiJenis['shoppingCartFieldSrc']) ? $configUiJenis['shoppingCartFieldSrc'] : array("nama" => "nama");

    $ci->load->model("Mdls/" . $selectorSrcModel);
    $b = new $selectorSrcModel();
    $b->addFilter("id in ('" . implode("','", $arrItemsKey) . "')");
    $tmpB = $b->lookupAll()->result();
    if (sizeof($tmpB) > 0) {
        foreach ($tmpB as $row) {
            $rowsArray = (array)$row;
            $rowsArrayTrim = trimArray($rowsArray);
            $row = $rows = (object)$rowsArrayTrim;
            $tmp = (array)$rows;
            $produk_id = $idp = $row->id;
            $produk_nama = $row->nama;
            $produk_jenis = $row->kategori_nama;
            $jenis_barang = $row->jenis;
            $jml_serial = $row->jml_serial;


            if ($renew == true) {
                if (!isset($sessionData['items2'][$idp])) {
                    $sessionData['items2'][$idp] = array();
                }
            }
            //            arrPrintPink($fieldSrcs);
            foreach ($fieldSrcs as $key => $src) {
                if (!isset($sessionData['items'][$idp][$key])) {
                    if (is_array($src) && sizeof($src) > 0) {
                        foreach ($src as $srcSpec) {
                            if (isset($tmp[$srcSpec]) || isset($rows->$srcSpec)) {
                                //                                cekPink("masuk disini $produk_nama $key => $src");
                                $sessionData['items'][$idp][$key] = makeValue($srcSpec, $tmp, $tmp, isset($rows->$srcSpec) ? $rows->$srcSpec : "-");
                            }
                            else {
                                //                                cekHitam("masuk disini $produk_nama $key => $src");
                            }
                        }
                    }
                    else {
                        //                        cekHijau("masuk disini $produk_nama $key => $src");
                        $sessionData['items'][$idp][$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                    }
                }
                else {
                    //                    cekKuning("masuk disini $produk_nama $key => $src");
                    $sessionData['items'][$idp][$key] = makeValue($src, $tmp, $tmp, isset($rows->$src) ? $rows->$src : 0);
                }
            }
            // memasukkan kolom sku ke items2
            $arrCat = array();
            $arrCode = array();
            if ($produk_jenis == "unit") {
                foreach ($arrDataTambahan as $cat => $catSpec) {
                    foreach ($catSpec as $dkey => $dval) {
                        if (isset($rows->$dval) && ($rows->$dval != NULL)) {

                            if ($renew == true) {
                                $sessionData['items2'][$produk_id][$rows->$dval] = array();
                            }


                            //--------------
                            if (!isset($arrCat[$cat])) {
                                $arrCat[$cat] = 0;
                            }
                            $arrCat[$cat] += 1;
                            //--------------
                            if (!isset($arrCode[$rows->$dval])) {
                                $arrCode[$rows->$dval] = 0;
                            }
                            $arrCode[$rows->$dval] += 1;
                            //--------------
                        }
                    }
                }


                if (sizeof($arrCode) < 2) {
                    $produkUnitNoPart[$produk_id] = array(
                        "id" => $produk_id,
                        "nama" => $produk_nama,
                        "jml_serial" => $jml_serial,
                    );
                }
                $produkUnitAda = true;
            }
            else {
                //handle serial 1
                $tmp['jml_serial'] = $jml_serial;
                $tmp['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                if (($jml_serial * 1) == 1) {
                    $d_kode = $rows->kode;


                    if ($renew == true) {
                        $sessionData['items2'][$produk_id][$d_kode] = array();
                    }


                    $sessionData['items'][$idp][$d_kode] = $sessionData['items'][$idp]["jml"];

                    //-------
                    $produkNonUnitSerialAda = true;
                    if ($d_kode == NULL) {
                        $produkNonUnitNoPart[$produk_id] = array(
                            "id" => $produk_id,
                            "nama" => $produk_nama,
                            "jml_serial" => $jml_serial,
                        );
                    }
                    //-------

                }
                elseif ($jml_serial == 0) {
                    $d_kode = $rows->kode;
                    $sessionData['items'][$idp][$d_kode] = 0;
                    $sessionData['items'][$idp]["jml_serial"] = 0;
                    unset($sessionData['items2'][$idp]);//reset items2nya karena gak ada serialnya
                }
                else {

                }
            }
            $keterangan = "";
            $static_keterangan = "";
            if (!empty($arrCat)) {
                foreach ($arrCat as $kcat => $vcat) {
                    $new_vcat = $vcat * $sessionData['items'][$idp]["jml"];
                    if ($keterangan == "") {
                        $keterangan = " $new_vcat $kcat";
                    }
                    else {
                        $keterangan .= "<br> $new_vcat $kcat";
                    }
                    if ($static_keterangan == "") {
                        $static_keterangan = " $vcat $kcat";
                    }
                    else {
                        $static_keterangan .= "<br> $vcat $kcat";
                    }
                    $new_keyy = "qty_" . $kcat;
                    $sessionData['items'][$idp][$new_keyy] = $vcat;
                }
            }
            if (!empty($arrCode)) {
                foreach ($arrCode as $kcat => $vcat) {
                    $new_vcat = $vcat * $sessionData['items'][$idp]["jml"];
                    $sessionData['items'][$idp][$kcat] = $new_vcat;
                }
            }
            $sessionData['items'][$idp]['keterangan'] = $keterangan;
            $sessionData['items'][$idp]['static_keterangan'] = $static_keterangan;
            $sessionData['items'][$idp]['jenis_barang'] = $jenis_barang;
        }
    }
    // endregion reload data produk sesuai config dari shoppingcart


    $peringatan = NULL;
    $produk_tidak_lengkap = false;
    if (isset($produkUnitAda) && ($produkUnitAda == true)) {
        if (sizeof($produkUnitNoPart) > 0) {
            $hasil = "";
            foreach ($produkUnitNoPart as $pid => $pSpec) {
                $nama = $pSpec["nama"];
                $jml_serial = $pSpec["jml_serial"];
                if ($hasil == "") {
                    $hasil = "$nama ($jml_serial part)";
                }
                else {
                    $hasil .= ", $nama ($jml_serial part)";
                }
            }
            $peringatan .= "Data Produk $hasil dengan kategori unit tidak lengkap. Pastikan part sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            $produk_tidak_lengkap = true;
        }
        else {
            $produk_tidak_lengkap = false;
        }
    }


    if (isset($produkNonUnitSerialAda) && ($produkNonUnitSerialAda == true)) {
        if (sizeof($produkNonUnitNoPart) > 0) {
            $hasil = "";
            foreach ($produkNonUnitNoPart as $pid => $pSpec) {
                $nama = $pSpec["nama"];
                $jml_serial = $pSpec["jml_serial"];
                if ($hasil == "") {
                    $hasil = "$nama";
                }
                else {
                    $hasil .= ", $nama";
                }
            }
            $peringatan .= "<br>Data Kode/SKU Produk $hasil dengan kategori non unit tidak lengkap. Pastikan sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            $produk_nonunit_tidak_lengkap = true;
        }
        else {
            $produk_nonunit_tidak_lengkap = false;
        }
    }


    $result = array(
        "items" => $sessionData['items'],
        "produk_unit_ada" => $produkUnitAda,
        "produk_unit_no_part" => $produkUnitNoPart,
        "warning" => isset($peringatan) ? $peringatan : NULL,
        "produk_tidak_lengkap" => $produk_tidak_lengkap,
        "produk_nonunit_tidak_lengkap" => $produk_nonunit_tidak_lengkap,

    );
    return $result;

}

function checkerProdukData($row, $arrDataTambahan)
{
    $rowsArray = (array)$row;
    $rowsArrayTrim = trimArray($rowsArray);
    $row = $rows = (object)$rowsArrayTrim;
    $tmp = (array)$rows;
    $produk_id = $idp = $row->id;
    $produk_nama = $row->nama;
    $produk_kode = $row->kode;
    $produk_jenis = $row->kategori_nama;
    $jenis_barang = $row->jenis;
    $jml_serial = $row->jml_serial;


    $arrCat = array();
    $arrCode = array();
    if ($produk_jenis == "unit") {
        foreach ($arrDataTambahan as $cat => $catSpec) {
            foreach ($catSpec as $dkey => $dval) {
                if (isset($rows->$dval) && ($rows->$dval != NULL)) {
                    //--------------
                    if (!isset($arrCat[$cat])) {
                        $arrCat[$cat] = 0;
                    }
                    $arrCat[$cat] += 1;
                    //--------------
                    if (!isset($arrCode[$rows->$dval])) {
                        $arrCode[$rows->$dval] = 0;
                    }
                    $arrCode[$rows->$dval] += 1;
                    //--------------
                }
            }
        }
        //        arrPrint($arrCode);
        //        cekHere(sizeof($arrCode) . " || $jml_serial");
        if (sizeof($arrCode) < $jml_serial) {
            $nn_total = 0;
            foreach ($arrCode as $nn) {
                $nn_total += $nn;
            }
            //            cekHitam("[$nn_total] [$jml_serial]");
            if ($nn_total < $jml_serial) {
                $peringatan = "Data Produk ($produk_kode) $produk_nama dengan kategori unit tidak lengkap. Pastikan part sudah disetting. ";
                $peringatan .= "Silahkan perbarui data produk anda. ";
                die(lgShowAlertMerah($peringatan));
            }
        }
        // cek produk SKU (kosong STOP, ada SKU maka lanjut)
        if ($produk_kode == NULL) {
            $peringatan = "Data Kode/SKU Produk $produk_nama dengan kategori unit tidak lengkap. Pastikan SKU produk sudah disetting. ";
            $peringatan .= "Silahkan perbarui data produk anda. ";
            die(lgShowAlertMerah($peringatan));
        }

    }
    else {
        //handle serial 1
        if (($jml_serial * 1) == 1) {
            $d_kode = $rows->kode;
            if ($d_kode == NULL) {

                $peringatan = "Data Kode/SKU Produk $produk_nama dengan kategori non unit tidak lengkap. Pastikan sudah disetting. ";
                $peringatan .= "Silahkan perbarui data produk anda. ";
                die(lgShowAlertMerah($peringatan));
            }
        }
        elseif ($jml_serial == 0) {

        }
        else {

        }


    }

    //    mati_disini(__LINE__);
}

define('MAX_DISKON_PEMBULATAN', '10');//konstanta dibaca sebagai persen
define('MAX_PREMI_PEMBULATAN', '1000');//konstanta dibaca sebagai nominal

function get_full_url()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
        || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    return $protocol . $host . $request_uri;
}

function pisahGerbangKoreksi($configUiJenis, $sessionData)
{
    //--------
    $additionalDetailByCabang = isset($configUiJenis["additionalDetailByCabang"]) ? $configUiJenis["additionalDetailByCabang"] : array();
    //    arrPrint($additionalDetailByCabang);
    if (sizeof($additionalDetailByCabang) > 0) {
        if (isset($additionalDetailByCabang["enabled"]) && ($additionalDetailByCabang["enabled"] == true)) {
            //            $gateTarget = $additionalDetailByCabang["gateTarget"];
            //            $gateTarget_sum = $additionalDetailByCabang["gateTarget_sum"];
            $gateSource = $additionalDetailByCabang["gateSource"];
            $gateSummary = $additionalDetailByCabang["gateSummary"];
            $gateSummaryCabang = $additionalDetailByCabang["gateSummaryCabang"];
            $copyKey = $additionalDetailByCabang["copyKey"];
            $copyKeyCabang = $additionalDetailByCabang["copyKeyCabang"];
            //            arrPrintWebs($gateSummaryCabang);
            //            matiHere(__LINE__);
            //            $sessionData[$gateTarget] = array();
            $sessionData["items7"] = array();
            $sessionData["items7_sum"] = array();
            $sessionData["items8"] = array();
            $sessionData["items8_sum"] = array();
            switch ($gateSource) {
                case "items":
                    foreach ($sessionData[$gateSource] as $ii => $iiSpec) {
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey = "nilai_" . $iiSpec[$cKey];
                            $sessionData[$gateSource][$ii][$new_ckey] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
                            //                            $sessionData[$gateSource][$ii]["hutang_pph"] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
                            $iiSpec[$new_ckey] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;
                            $iiSpec["hutang_pph"] = isset($iiSpec[$cVal]) ? $iiSpec[$cVal] : 0;

                        }
                        //                        arrPrintWebs($iiSpec);
                        // dikelompokkan per-cabang
                        $cabang2_id = $iiSpec["cabang2_id"];
                        if ($cabang2_id > 0) {
                            $gateTarget = "items8";
                            // gerbang cabang, items8
                            foreach ($copyKeyCabang[0] as $cKey => $cVal) {
                                $sessionData[$gateSource][$ii][$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                                $iiSpec[$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                            }
                        }
                        else {
                            $gateTarget = "items7";
                            // gerbang dc/pusat, items7
                            foreach ($copyKeyCabang[1] as $cKey => $cVal) {
                                $sessionData[$gateSource][$ii][$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                                $iiSpec[$cKey] = makeValue($cVal, $iiSpec, $iiSpec, 0);
                            }
                        }


                        if (!isset($sessionData[$gateTarget][$cabang2_id])) {
                            $sessionData[$gateTarget][$cabang2_id] = $iiSpec;
                        }
                        else {
                            foreach ($gateSummary as $cKey => $cVal) {

                                $new_ckeys = "nilai_" . $iiSpec[$cKey];

                                if (!isset($sessionData[$gateTarget][$cabang2_id]["hutang_pph"])) {
                                    $sessionData[$gateTarget][$cabang2_id]["hutang_pph"] = 0;
                                }
                                $sessionData[$gateTarget][$cabang2_id][$new_ckeys] += $iiSpec[$cVal];
                                $sessionData[$gateTarget][$cabang2_id]["hutang_pph"] += $iiSpec[$cVal];
                            }
                            foreach ($gateSummaryCabang as $sVal) {
                                //                                cekHitam($sVal);
                                //                                matiHere(__LINE__);
                                $sessionData[$gateTarget][$cabang2_id][$sVal] += makeValue($sVal, $iiSpec, $iiSpec, 0);
                            }
                        }
                    }
                    //                    arrPrint($sessionData["items7"]);
                    //matiHere(__LINE__);
                    foreach ($sessionData["items7"] as $cbID => $cbSpec) {
                        //untuk handling pusat
                        $sessionData["items7_sum"][$cbID] = $cbSpec;
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey_ = "nilai_" . $iiSpec[$cKey];
                            $sessionData["items7_sum"][$cbID][$new_ckey_] = isset($cbSpec[$new_ckey_]) ? $cbSpec[$new_ckey_] : 0;
                            $sessionData["items7_sum"][$cbID]["hutang_pph"] = isset($cbSpec["hutang_pph"]) ? $cbSpec["hutang_pph"] : 0;
                        }
                    }

                    foreach ($sessionData["items8"] as $cbID => $cbSpec) {
                        //untuk handling biaya terjadi dicabang
                        $sessionData["items8_sum"][$cbID] = $cbSpec;
                        foreach ($copyKey as $cKey => $cVal) {
                            $new_ckey_1 = "nilai_" . $iiSpec[$cKey];
                            $sessionData["items8_sum"][$cbID][$new_ckey_1] = isset($cbSpec[$new_ckey_1]) ? $cbSpec[$new_ckey_1] : 0;
                            //                            $sessionData["items8_sum"][$cbID]["hutang_kepusat"] = isset($cbSpec[$new_ckey]) ? $cbSpec[$new_ckey] : 0;
                            //                            $sessionData["items8_sum"][$cbID]["piutang_cabang"] = isset($cbSpec[$new_ckey]) ? $cbSpec[$new_ckey] : 0;
                            $sessionData["items8_sum"][$cbID]["hutang_pph"] = isset($cbSpec["hutang_pph"]) ? $cbSpec["hutang_pph"] : 0;
                        }
                    }

                    break;
                case "main":

                    break;
            }

        }
    }
    //--------
    //
    //    arrPrintCyan($sessionData["items"]);
    //    arrPrintPink($sessionData["items7"]);
    //    arrPrintKuning($sessionData["items7_sum"]);
    //matiHere(__LINE__);
    return $sessionData;

}

/** ------------------------------------------------------
 * untuk ngecek session modul
 * ------------------------------------------------------*/
function cekSessionTransaksi($cCode)
{
    $balikan = false;
    if (isset($_SESSION[$cCode]["items"]) && count($_SESSION[$cCode]["items"])) {
        $balikan = true;
    }
    else {
        $balikan = false;
    }

    return $balikan;
}

function _showNotifActiveSessionSimpel($cCode)
{
    $sessmain = $_SESSION[$cCode]['main'];
    $sessitems = $_SESSION[$cCode]['items'];
    $dtime = $sessmain['dtime'];
    $jenisTrName = $sessmain['jenisTrName'];
    $main = "";
    if (isset($sessmain['requestReferenceNomer'])) {
        $requestReferenceNomer = $sessmain['requestReferenceNomer'];
        $customerName = $sessmain['customerName'];


        $main .= "<div><strong>NO SO:</strong> $requestReferenceNomer</div>";
        $main .= "<div><strong>Konsumen:</strong> $customerName</div>";
    }
    else {


        $main .= "<div class='text-capitalize'><strong>Transaksi:</strong> $jenisTrName</div>";

    }

    $items = "";
    $items .= "<div>";
    // $items .= "<strong>Daftar Barang:</strong>";
    $items .= "<ul class='text-left'>";
    foreach ($sessitems as $prodid => $sessitem) {
        // arrPrint($sessitem);

        $items .= "<li>";
        $items .= $sessitem['jml'] . " ";
        $items .= $sessitem['kategori_nama'] . " - ";
        $items .= "<strong>" . $sessitem['nama'] . "</strong> ";
        $items .= $sessitem['produk_kode'];
        $items .= "</li>";
    }
    $items .= "</ul>";
    $items .= "</div>";

    $content = "<div><r>Anda masih ada transaksi " . strtoupper($jenisTrName) . " yang terbuka (belum diselesaikan), Harap diselesaikan terlebih dahulu</r></div>";

    $content .= "<hr>" . $main;
    $content .= "<hr>" . $items;
    $content .= "<span class=meta>Transaksi dibuka pada: " . indonesian_date($dtime) . "</span>";
    $content .= "<div class='alert alert-warning'>Untuk membersihkan sesi, Anda bisa melakukan login ulang terlebih dahulu</div>";

    return $content;
}

function getTargetTransaksi()
{
    $ci = &get_instance();
    $misc = $ci->config->item("payment_source");
    $result = array();
    if (sizeof($misc) > 0) {
        foreach ($misc as $jenisTr => $spec) {
            foreach ($spec as $subSpec) {
                foreach ($subSpec as $xxSpec) {
                    $jenisTarget = $xxSpec["jenisTarget"];
                    if (!isset($result[$jenisTarget])) {
                        $result[$jenisTarget] = array();
                    }
                    $result[$jenisTarget][] = $jenisTr;
                }
            }
        }
    }

    return $result;

}

function checkerSession($jenisTr, $warningcek = 0)
{
    $ci = &get_instance();
    $cCode = "_TR_" . $jenisTr;
    $main = isset($_SESSION[$cCode]["main"]) ? $_SESSION[$cCode]["main"] : array();
    $items = isset($_SESSION[$cCode]["items"]) ? $_SESSION[$cCode]["items"] : array();
    $checkerSession = isset($ci->config->item("heTransaksi_checkerSession")[$jenisTr]) ? $ci->config->item("heTransaksi_checkerSession")[$jenisTr] : array();
    $errMsg = array();
    //    unset($main["gudangStatusDetails"]);
    //    unset($main["pihakMain2ID"]);
    //    unset($main["pihakID"]);

    switch ($jenisTr) {
        // region penjualan
        case "5822":
        case "5823":
        case "9822":
            $arrMainCek = isset($checkerSession["main"]) ? $checkerSession["main"] : array();
            if (sizeof($arrMainCek) > 0) {
                foreach ($arrMainCek as $key => $label) {
                    if (!isset($main[$key]) || ($main[$key] == NULL)) {
                        $errMsg[] = $label;
                    }
                }
            }

            if (sizeof($items) == 0) {
                $label = "Sesi transaksi anda habis/transaksi yang sedang anda kerjakan sudah kadaluarsa.";
                $errMsg[] = $label;
            }

            break;
        // endregion penjualan

        // region distribusi
        case "583":
        case "983":
            $arrMainCek = isset($checkerSession["main_reference"]) ? $checkerSession["main_reference"] : array();
            $arrMainCekDC = isset($checkerSession["main"]) ? $checkerSession["main"] : array();
            if ($_SESSION[$cCode]["main"]["requestReferenceID"] > 0) {
                foreach ($arrMainCek as $key => $label) {
                    if (!isset($main[$key]) || ($main[$key] == NULL)) {
                        $errMsg[] = $label;
                    }
                }
            }
            else {
                if (sizeof($arrMainCekDC) > 0) {
                    foreach ($arrMainCekDC as $key => $label) {
                        if (!isset($main[$key]) || ($main[$key] == NULL)) {
                            $errMsg[] = $label;
                        }
                        elseif ($main[$key] == 0) {
                            $errMsg[] = $label;
                        }
                    }
                }
            }

            if (sizeof($items) == 0) {
                $label = "Sesi transaksi anda habis/transaksi yang sedang anda kerjakan sudah kadaluarsa.";
                $errMsg[] = $label;
            }

            break;
        // endregion distribusi

        default:
            $arrMainCek = isset($checkerSession["main"]) ? $checkerSession["main"] : array();
            if (sizeof($arrMainCek) > 0) {
                foreach ($arrMainCek as $key => $label) {
                    if (!isset($main[$key])) {
                        $errMsg[] = $label;
                    }
                    elseif ($main[$key] == NULL) {
                        $errMsg[] = $label;
                    }
                }
            }
            if (sizeof($items) == 0) {
                $label = "Sesi transaksi anda habis/transaksi yang sedang anda kerjakan sudah kadaluarsa.";
                $errMsg[] = $label;
            }
            break;
    }

    if ($warningcek == 1) {
        if (sizeof($errMsg) > 0) {
            $msg = "";
            foreach ($errMsg as $label) {
                $msg .= $label . "<br>";
            }
            $msg .= "Silahkan reload transaksi yang anda pilih/kerjakan.";
            matiWhiteboard($msg);

        }
    }
    else {

        if (sizeof($errMsg) > 0) {
            $errMsg[] = "Silahkan reload transaksi yang anda pilih/kerjakan.";
        }
        //        arrPrintHitam($errMsg);

        return $errMsg;
    }

}

function getCurrentUrl()
{
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = $scheme . '://' . $host . $uri;
    return $url;
}

function releaserLockerTrans($trid)
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("produk_id='$trid'");
    $tr->addFilter("jumlah>'0'");
    $trTmp = $tr->lookupAll()->result();
    $result = false;
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $idtbl = $trSpec->id;
            $where = array(
                "id" => $idtbl,
            );
            $data = array(
                "jumlah" => 0,
            );
            $tr = New MdlLockerTransaksi();
            $tr->setFilters(array());
            $tr->updateData($where, $data);
        }
        $result = true;
    }
    return $result;
}

function releaserLockerTransByLockerID($trid, $userLockerID = NULL)
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("produk_id='$trid'");
    if ($userLockerID != NULL) {
        $tr->addFilter("oleh_id='$userLockerID'");
    }
    $tr->addFilter("jumlah>'0'");
    $trTmp = $tr->lookupAll()->result();
    $result = false;
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $idtbl = $trSpec->id;
            $where = array(
                "id" => $idtbl,
            );
            $data = array(
                "jumlah" => 0,
            );
            $tr = New MdlLockerTransaksi();
            $tr->setFilters(array());
            $tr->updateData($where, $data);

        }
        $result = true;
    }

    return $result;

}

//--------------------
function checkerLockerHoldTrans($trid)
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $result = array();
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("jumlah>'0'");
    if (is_array($trid)) {
        $tr->addFilter("produk_id in ('" . implode("','", $trid) . "')");
    }
    else {
        $tr->addFilter("produk_id='$trid'");
    }
    $trTmp = $tr->lookupAll()->result();
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $result[$trSpec->produk_id] = array(
                "oleh_id" => $trSpec->oleh_id,
                "oleh_nama" => $trSpec->oleh_nama,
                "dtime" => $trSpec->last_access,
                "transaksi_no" => $trSpec->transaksi_no,
            );
        }
    }
    return $result;
}

function checkerLockerHoldTransByID($trid)
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $result = array();
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("oleh_id>'0'");
    $tr->addFilter("jumlah>'0'");
    if (is_array($trid)) {
        $tr->addFilter("produk_id in ('" . implode("','", $trid) . "')");
    }
    else {
        $tr->addFilter("produk_id='$trid'");
    }
    $trTmp = $tr->lookupAll()->result();
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $result[$trSpec->produk_id] = array(
                "oleh_id" => $trSpec->oleh_id,
                "oleh_nama" => $trSpec->oleh_nama,
                "dtime" => $trSpec->last_access,
                "transaksi_no" => $trSpec->transaksi_no,
            );
        }
    }
    return $result;
}

function checkerLockerHoldTransByTransID($trid)
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $result = array();
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("oleh_id='0'");
    $tr->addFilter("jumlah>'0'");
    if (is_array($trid)) {
        $tr->addFilter("produk_id in ('" . implode("','", $trid) . "')");
    }
    else {
        $tr->addFilter("produk_id='$trid'");
    }
    $trTmp = $tr->lookupAll()->result();
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $result[$trSpec->produk_id] = array(
                "oleh_id" => $trSpec->oleh_id,
                "oleh_nama" => $trSpec->oleh_nama,
                "dtime" => $trSpec->last_access,
                "transaksi_no" => $trSpec->transaksi_no,
            );
        }
    }
    return $result;
}

function checkerLockerHoldTransAll()
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $result = array();
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("jumlah>'0'");
    //    if(is_array($trid)){
    //        $tr->addFilter("produk_id in ('".implode("','", $trid)."')");
    //    }
    //    else{
    //        $tr->addFilter("produk_id='$trid'");
    //    }
    $trTmp = $tr->lookupAll()->result();
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $result[$trSpec->produk_id] = array(
                "oleh_id" => $trSpec->oleh_id,
                "oleh_nama" => $trSpec->oleh_nama,
                "dtime" => $trSpec->last_access,
                "transaksi_no" => $trSpec->transaksi_no,
            );
        }
    }
    return $result;
}

function checkerLockerHoldTransAllByID()
{
    $ci = &get_instance();
    $session_login = $ci->session->login;
    $ci->load->model("Mdls/MdlLockerTransaksi");
    $result = array();
    $tr = New MdlLockerTransaksi();
    $tr->addFilter("state='hold'");
    $tr->addFilter("oleh_id>'0'");
    $tr->addFilter("jumlah>'0'");
    //    if(is_array($trid)){
    //        $tr->addFilter("produk_id in ('".implode("','", $trid)."')");
    //    }
    //    else{
    //        $tr->addFilter("produk_id='$trid'");
    //    }
    $trTmp = $tr->lookupAll()->result();
    if (sizeof($trTmp) > 0) {
        foreach ($trTmp as $trSpec) {
            $result[$trSpec->produk_id] = array(
                "oleh_id" => $trSpec->oleh_id,
                "oleh_nama" => $trSpec->oleh_nama,
                "dtime" => $trSpec->last_access,
                "last_access" => $trSpec->last_access,
                "transaksi_no" => $trSpec->transaksi_no,
            );
        }
    }
    return $result;
}

//--------------------
function kodePajak($kodePajak, $nomer_faktur, $nilai_pajak)
{

    $kode_faktur = substr($nomer_faktur, 0, 2);
    $result = array();
    if (array_key_exists($kode_faktur, $kodePajak)) {
        $result["nilai_ppn_" . $kode_faktur] = $nilai_pajak;
    }
    else {
        $result["nilai_ppn_gunggung"] = $nilai_pajak;
    }

    return $result;
}

function checkerKodePajak($nomer_faktur)
{
    $ci = &get_instance();
    $kodePajak = $ci->config->item("kodePajak");
    $kode_faktur = substr($nomer_faktur, 0, 2);
    $result = array();
    if (array_key_exists($kode_faktur, $kodePajak)) {
        $result = array(
            "nilai" => 1,
            "label" => "",
        );
    }
    else {
        $result = array(
            "nilai" => 0,
            "label" => "Nomer Faktur yang Anda isi salah/tidak sesuai dengan Kode Faktur Pajak dari Direktorat Jenderal Pajak (DJP). Silahkan dikoreksi dahulu.",
        );
    }

    return $result;
}

function checkerNomerNpwp($nomer_npwp)
{
    // hapus karakter spesial
    $nomer_npwp = str_replace(",", "", $nomer_npwp);
    $nomer_npwp = str_replace(".", "", $nomer_npwp);
    $nomer_npwp = str_replace("-", "", $nomer_npwp);

    // cek digit nomer npwp (15 digit dan 16 digit)
    if (strlen($nomer_npwp) < 15) {
        $msg = "Nomer NPWP yang anda isikan salah. Isikan Nomer NPWP 15 atau 16 digit. ";
        $result = array(
            "nilai" => 0,
            "label" => $msg,
        );
    }
    elseif (strlen($nomer_npwp) > 16) {
        $msg = "Nomer NPWP yang anda isikan salah. Isikan Nomer NPWP 15 atau 16 digit. ";
        $result = array(
            "nilai" => 0,
            "label" => $msg,
        );
    }
    else {
        $result = array(
            "nilai" => 1,
            "label" => NULL,
        );
    }

    return $result;
}


//--------------------
function builderKodeBooking()
{
    $dtime_now = dtimeNow();
    $dtime_now_ex = explode(" ", $dtime_now);
    $date_now = str_replace("-", "", $dtime_now_ex[0]);
    $time_now = str_replace(":", "", $dtime_now_ex[1]);
    $bookingNumber = "$date_now" . "$time_now";

    return $bookingNumber;
}

function checkerKodeBooking($sessionData, $nomerBookingTransaksi = NULL, $transaksi_id = 0, $connector = "", $current = "")
{
    $msg = NULL;
    if (!isset($sessionData["main"]["bookingNumber"])) {
        $msg = "Nomor booking Anda sudah kedaluwarsa.<br>
Mohon buka kembali transaksi untuk memperbarui data ";
        //        mati_disini($msg);
    }
    elseif ($sessionData["main"]["bookingNumber"] == NULL) {
        $msg = "Nomor booking Anda sudah kedaluwarsa.<br>
Mohon buka kembali transaksi untuk memperbarui data";
        //        mati_disini($msg);
    }
    if (($nomerBookingTransaksi != NULL) && ($nomerBookingTransaksi != $sessionData["main"]["bookingNumber"])) {
        $msg = "Nomor booking Anda sudah kedaluwarsa.<br>
Mohon buka kembali transaksi untuk memperbarui data.";
        //        mati_disini($msg);
    }
    if ($msg != NULL) {
        $transaksi_id = 0;
        if ($transaksi_id > 0) {
            $ci = &get_instance();
            $modul_transaksi = $ci->config->item("heTransaksi_ui")[$connector]["modul"];
            $next = $current + 1;
            $linkreopen = base_url() . "$modul_transaksi" . "/FollowUp/followupPrePreview/$connector/$transaksi_id/$next/$current";
            //            $link = "top.$('#result').load('$linkreopen');";
            $link = "document.getElementById(&quot;result&quot;).src='$linkreopen';";
            $msg .= "<br>";
            $msg .= "Silahkan reload halaman ini atau <a href='javascript:void(0)' onclick='$link'>klik disini</a>.";
        }
        else {
            $link = "top.location.reload();";
            $msg .= "<br>";
            $msg .= "Silahkan reload halaman ini atau <a href='javascript:void(0)' onclick='$link'>klik disini</a>.";
        }
        //        mati_disini($msg);
        matiWhiteboard("Peringatan", $msg);
    }

    return $msg;
}

//--------------------
function checkerPembatalan($tr_dibatalkan, $pembatalanValidateConfig, $pembatalanCheckerReference)
{
    $ci = &get_instance();
    $tr_no_dibatalkan = isset($tr_dibatalkan["tr_no_dibatalkan"]) ? $tr_dibatalkan["tr_no_dibatalkan"] : "";
    $tr_jenis_dibatalkan = isset($tr_dibatalkan["tr_jenis_dibatalkan"]) ? $tr_dibatalkan["tr_jenis_dibatalkan"] : "";

    if (sizeof($pembatalanValidateConfig) > 0) {
        foreach ($pembatalanValidateConfig as $pembatalanSpec) {
            $mdlNameValidate = $pembatalanSpec['mdlName'];
            $mdlFilterValidate = isset($pembatalanSpec['mdlFilter']) ? $pembatalanSpec['mdlFilter'] : array();

            $ci->load->model("Mdls/$mdlNameValidate");
            $mdl_v = New $mdlNameValidate();
            $mdl_v->setFilters(array());
            if (sizeof($mdlFilterValidate) > 0) {
                $rslt = makeFilter($mdlFilterValidate, (array)$tmpB[0], $mdl_v);
            }
            $validateTmp = $mdl_v->lookupAll()->result();
            //                    showLast_query("pink");
            //                    arrPrint($validateTmp);
            //                    cekPink(count($validateTmp));
            if (sizeof($validateTmp) > 0) {

                if ($mdlNameValidate == "MdlPaymentSource") {
                    $ci->load->model("Mdls/MdlRevertJurnalCabang");
                    $ci->load->model("Mdls/MdlRevertJurnal");
                    $pp = new MdlRevertJurnal();
                    $pc = new MdlRevertJurnalCabang();
                    $dataTmpAliasPusat = $pp->lookUpAll()->result();
                    $dataTmpAliascabang = $pc->lookUpAll()->result();
                    $aliasData = array();
                    foreach ($dataTmpAliasPusat as $dataTmpAliasPusat_0) {
                        $aliasData[$dataTmpAliasPusat_0->id] = $dataTmpAliasPusat_0->nama;
                    }
                    foreach ($dataTmpAliascabang as $dataTmpAliascabang_0) {
                        $aliasData[$dataTmpAliascabang_0->id] = $dataTmpAliascabang_0->nama;
                    }

                    switch ($tr_jenis_dibatalkan) {
                        case "677":
                        case "675":
                        case "2677":
                        case "2675":
                            $filter_produk_jenis = "expense";
                            break;
                        default:
                            $filter_produk_jenis = "invoice";
                            break;
                    }

                    $produk_id = $validateTmp[0]->transaksi_id;
                    $trs->setFilters(array());
                    $trs->addFilter("transaksi_data.produk_id='$produk_id'");
                    $trs->addFilter("transaksi_data.produk_jenis='$filter_produk_jenis'");
                    $trs->addFilter("transaksi.link_id='0'");
                    $tempPayment = $trs->lookupJoined_OLD()->result();
                    if (sizeof($tempPayment) > 1) {
                        $arrPayment_no = array();
                        $arrPayment_alias = array();
                        foreach ($tempPayment as $ix => $tempSpec) {
                            $arrPayment_no[$ix] = $tempSpec->nomer;
                            $arrPayment_alias[$tempSpec->jenis] = $aliasData[$tempSpec->jenis];
                        }
                        $payment_no = implode(", ", $arrPayment_no);
                        $aliasDataTransaksi = implode(", ", $arrPayment_alias);
                    }
                    elseif (sizeof($tempPayment == 1)) {
                        $payment_no = $tempPayment[0]->nomer;
                        $payment_jenis = $tempPayment[0]->jenis;
                        $aliasDataTransaksi = $aliasData[$payment_jenis];
                    }
                    else {
                        $payment_no = "";
                        $payment_jenis = "";
                        $aliasDataTransaksi = "";
                    }
                    $cabang_id = $tempPayment[0]->cabang_id;
                    $title_cabang = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                    $tr_cabang_dibatalkan2 = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                    $payment_no_f = formatField_he_format("nomer_nolink", $payment_no);
                    $tr_no_dibatalkan_f = formatField_he_format("nomer_nolink", $tr_no_dibatalkan);
                    $sw_title = "Transaksi Tidak dapat dibatalkan";
                    $msg2 = "<div class='text-left'>";
                    $msg2 .= $pembatalanSpec['label'] . "<br>";
                    $msg2 .= "<div class='text-bold text-red'>Nomer  " . $aliasDataTransaksi . " ($payment_no_f) </div>";
                    $msg2 .= "<div class='text-bold text-red'>Berhubung akan direject ($tr_no_dibatalkan_f) maka yang harus dilakukan:</div>";

                    //region untuk dinamis pake tempalte ini setiap row
                    $msg2 .= "<ol>";
                    $msg2 .= "<li>Batalkan " . $aliasData[$payment_jenis] . " Nomer <strong>$payment_no_f</strong><br> " . $title_cabang . " dari menu pembatalan transaksi.</li>";
                    $msg2 .= "<li>Batalkan " . $aliasData[$tr_jenis_dibatalkan] . " Nomer <strong>$tr_no_dibatalkan_f</strong>" . $tr_cabang_dibatalkan2 . " dari menu pembatalan transaksi.</li>";
                    $msg2 .= "</ol>";
                    //endregion untuk dinamis pake tempalte ini setiap row

                    $msg2 .= "</div>";
                    matiWhiteboard($sw_title, $msg2);
                }
                else {
                    $msg = $pembatalanSpec['label'];
                    die(lgShowAlertMerah($msg));
                    mati_disini(($msg));
                }

            }
            if (isset($pembatalanSpec['detailCekQty']) && ($pembatalanSpec['detailCekQty'] == true)) {
                $trs->setFilters(array());
                $dTr = $trs->lookupDetailTransaksi($id);
                $totalOrdJml = 0;
                $totalValidQty = 0;
                foreach ($dTr[$id] as $dTrSpec) {
                    $totalOrdJml += $dTrSpec->produk_ord_jml;
                    $totalValidQty += $dTrSpec->valid_qty;
                }
                if ($totalOrdJml != $totalValidQty) {
                    die(lgShowAlertMerah($pembatalanSpec['label']));
                    mati_disini($pembatalanSpec['label']);
                }
            }
        }

    }

    if (sizeof($pembatalanCheckerReference) > 0) {
        foreach ($pembatalanCheckerReference as $mode_cek => $cekSpec) {
            switch ($mode_cek) {
                case "serial":
                    $mdlNameLoc = $cekSpec["mdlNameLoc"];
                    $mdlName = $cekSpec["mdlName"];
                    $mdlFilter = $cekSpec["mdlFilter"];
                    if (isset($cekSpec["pairedModel"])) {
                        $pairedMdlNameLoc = $cekSpec["pairedModel"]["mdlNameLoc"];
                        $pairedMdlName = $cekSpec["pairedModel"]["mdlName"];
                        $pairedMdlFilter = $cekSpec["pairedModel"]["mdlFilter"];
                        $pairedMdlFilterIn = $cekSpec["pairedModel"]["mdlFilterIn"];
                        $mdlFilterInSrc = $cekSpec["pairedModel"]["mdlFilterInSrc"];
                    }
                    $mdlFilterInSrcData = array();
                    $mdlFilterInTargetData = array();
                    $mdlFilterInSrcDataProduk = array();
                    $mdlFilterInTargetDataProduk = array();
                    $arrResultDiffProduk = array();
                    $arrResultAllProduk = array();
                    $mdlResultAllProduk = array();

                    $ci->load->model("$mdlNameLoc/$mdlName");
                    $md = New $mdlName();
                    if (sizeof($mdlFilter) > 0) {
                        makeFilter($mdlFilter, $masterMainFields, $md);
                        //                        arrPrint($mdlFilter);
                        //                        arrPrint($masterMainFields);
                        $mdTmp = $md->lookupAll()->result();
                        if (sizeof($mdTmp) > 0) {
                            foreach ($mdTmp as $mdSpec) {
                                $mdlFilterInSrcData[] = $mdSpec->$mdlFilterInSrc;
                                $mdlFilterInSrcDataProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec->$mdlFilterInSrc;
                                $mdlResultAllProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec;
                            }
                        }

                        if (isset($cekSpec["pairedModel"])) {
                            $ci->load->model("$pairedMdlNameLoc/$pairedMdlName");
                            $mdp = New $pairedMdlName();
                            if (sizeof($pairedMdlFilter) > 0) {
                                if (sizeof($mdlFilterInSrcData) > 0) {
                                    $mdp->addFilter("$pairedMdlFilterIn in ('" . implode("','", $mdlFilterInSrcData) . "')");
                                }
                                makeFilter($pairedMdlFilter, $masterMainFields, $mdp);
                                $mdpTmp = $mdp->lookupAll()->result();
                                //                                        showLast_query("biru");
                                //                                    cekHere(count($mdpTmp));
                                if (sizeof($mdpTmp) > 0) {
                                    foreach ($mdpTmp as $mdpSpec) {
                                        //                                                arrPrint($mdpSpec);
                                        $mdlFilterInTargetData[] = $mdpSpec->$pairedMdlFilterIn;
                                        $mdlFilterInTargetDataProduk[$mdpSpec->produk_id][$mdpSpec->extern2_nama][] = $mdpSpec->$pairedMdlFilterIn;
                                    }
                                }
                            }
                        }
                    }

                    $arrResultDiff = array_diff($mdlFilterInSrcData, $mdlFilterInTargetData);
                    foreach ($mdlFilterInSrcDataProduk as $pid => $pSpec) {
                        foreach ($pSpec as $psku => $skuSpec) {
                            $avaliSerial = isset($mdlFilterInTargetDataProduk[$pid][$psku]) ? $mdlFilterInTargetDataProduk[$pid][$psku] : array();
                            $avaliSerialDiff = array_diff($skuSpec, $avaliSerial);
                            if (sizeof($avaliSerialDiff) > 0) {
                                $arrResultDiffProduk[$pid][$psku] = $avaliSerialDiff;
                            }
                            $arrResultAllProduk[$pid][$psku] = $skuSpec;
                        }
                    }


                    break;
                case "packinglist":
                    $arrRefMasterIDs = array();
                    $arrCekTrIDs = array();
                    if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                    }
                    else {
                        foreach ($itemsFields as $sspec) {
                            if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                $arrCekTrIDs[] = $sspec["refID"];
                            }
                            else {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                        }
                    }
                    $ci->load->model("MdlTransaksi");
                    $trc = New MdlTransaksi();
                    $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                    $trcTmp = $trc->lookupAll()->result();
                    if (sizeof($trcTmp) > 0) {
                        foreach ($trcTmp as $specc) {
                            $arrRefMasterIDs[] = $specc->id_master;
                        }

                        $trc = New MdlTransaksi();
                        $trc->addFilter("id_master in ('" . implode("','", $arrRefMasterIDs) . "')");
                        $trc->addFilter("trash_4='0'");
                        $this->db->where_in('jenis', array("5822spd", "5823spd"));
                        $trcTmp = $trc->lookupAll()->result();
                        //                                arrPrint($arrRefMasterIDs);
                        //                                matiHere(__LINE__."::");
                        //                        if (in_array("91399", $arrRefMasterIDs)) {
                        //                            //untuk lolosin transaki lama (mei 2024) yang kena sabotase
                        //                            if ($tr_id_dibatalkan == "91441") {
                        //                                $trcTmp = array();
                        //                            }
                        //
                        //                        }

                        if (sizeof($trcTmp) > 0) {
                            $arrdatas = array();
                            foreach ($trcTmp as $speccc) {
                                $arrdatas[] = $speccc->nomer;
                                $customer_nama = $speccc->customers_nama;
                            }
                            $nomerss = implode(",", $arrdatas);

                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Pembatalan tidak bisa dilanjutkan karena Sales Order sudah dikirim ke konsumen $customer_nama, nomer kirim: $nomerss <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";

                            //region untuk dinamis pake tempalte ini setiap row
                            //                            $msg2 .="<ol>";
                            //                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
                            //                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
                            //                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
                            //                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }

                    break;
                case "salesorder":
                    $arrRefMasterIDs = array();
                    $arrCekTrIDs = array();
                    if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                    }
                    else {
                        foreach ($itemsFields as $sspec) {
                            if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                $arrCekTrIDs[] = $sspec["refID"];
                            }
                            else {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                        }
                    }
                    $ci->load->model("MdlTransaksi");
                    $trc = New MdlTransaksi();
                    $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                    $trc->addFilter("trash_4='1'");
                    $trcTmp = $trc->lookupAll()->result();
                    if (sizeof($trcTmp) > 0) {
                        $arrdataslunas = array();
                        foreach ($trcTmp as $speccc) {
                            //                                    arrPrint($speccc);
                            $arrdataslunas[$speccc->id] = $speccc->nomer;
                            $customer_nama = $speccc->customers_nama;
                            $cancel_nama = $speccc->cancel_name;
                            $trid = $speccc->id;
                            //------
                            $jenis = "4464";// penerimaan penjualan tunai
                            $trcc = New MdlTransaksi();
                            $trcc->setFilters(array());
                            $trcc->addFilter("transaksi_id='$trid'");
                            $trccTmp = $trcc->lookupPaymentSrcByJenis($jenis)->result();
                            $total_terbayar_lunas = 0;
                            if (sizeof($trccTmp) > 0) {
                                foreach ($trccTmp as $trccSpec) {
                                    //                                            arrPrintWebs($trccSpec);
                                    $total_terbayar_lunas += $trccSpec->terbayar;// jumlah yang sudah dibayar, masing-masing nota
                                }
                            }
                        }
                        //                                $nomerss = implode(",", $arrdataslunas);
                        //                                $msg = "Pembatalan tidak bisa dilanjutkan karena Sales Order nomer $nomerss konsumen $customer_nama sudah di REJECT/CANCEL oleh $cancel_nama. Silahkan diperiksa kembali. code: " . __LINE__;
                        //                                mati_disini($msg);

                    }
                    break;
            }
        }
    }


}

function watermarkRejected($kode = 1, $statusInvoice = 0)
{
    switch ($kode) {
        case "3":
            $label = "CANCELLED";
            break;
        case "2":
            $label = "VOID";
            break;
        case "1":
        default:
            $label = "REJECTED";
            break;
    }
    $option = "
                <style>
                /* Watermark default tampil DI LAYAR */
                .watermark-rejected {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-30deg);
                    font-size: 160px;
                    letter-spacing: 10px;
                    color: rgba(255, 0, 0, 0.15);
                    font-weight: 900;
                    z-index: 0;
                    pointer-events: none;
                    white-space: nowrap;
                    user-select: none;
                }
        
                /* Watermark juga tampil saat PRINT (lebih kuat opacitynya) */
                @media print {
                    .watermark-rejected {
                        color: rgba(255, 0, 0, 0.22);
                        z-index: 9999 !important;
                        position: fixed !important;
                    }
                }
                </style>
        <script>
        
            document.addEventListener(\"DOMContentLoaded\", function () {

                // ======================================
                // GANTI DENGAN STATUS DARI DATABASE ANDA
                // ======================================
                const statusInvoice = \"$statusInvoice\"; 
                // contoh: \"approved\", \"cancel\", \"draft\", dll
    
                if (statusInvoice === \"1\") {
            
                    // Membuat elemen watermark
                    const wm = document.createElement(\"div\");
                    wm.className = \"watermark-rejected\";
                    wm.innerText = \"$label\";
            
                    // Sisipkan watermark ke halaman
                    document.body.appendChild(wm);
                }
    
                });
        </script>";

    return $option;
}

function getCabangCode()
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCabang");
    $c = new MdlCabang();
    $temp = $c->lookUpAll()->result();
    $result = array();
    foreach ($temp as $temp_0) {
        $result[$temp_0->id] = $temp_0->kode_cabang;
    }
    return $result;
}

function getCabangData()
{
    $selectField = array(
        "id", "nama", "kode_cabang",
    );
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlCabang");
    $c = new MdlCabang();
    $temp = $c->lookUpAll()->result();
    $result = array();
    foreach ($temp as $temp_0) {
        $aaData = array();
        foreach ($selectField as $field) {
            $aaData[$field] = $temp_0->$field;
        }
        $result[$temp_0->id] = $aaData;
    }
    return $result;
}

function loadMapTransaction_he_misc($jenisTr = NULL)
{
    $CI = &get_instance();
    $arrMap = array();
    $modul_pathSrc = $CI->config->item("heTransaksi_ui");
    if (isset($modul_pathSrc[$jenisTr]["steps"]) && (sizeof($modul_pathSrc[$jenisTr]["steps"]) > 0)) {
        foreach ($modul_pathSrc[$jenisTr]["steps"] as $step => $stepSpec) {
            $arrMap[$step] = array(
                //                "step" => $step,
                "label" => $stepSpec["label"],
                "target" => $stepSpec["target"],
                "timeline" => isset($stepSpec["timeline"]) ? $stepSpec["timeline"] : "parent",
            );
        }

        if (isset($modul_pathSrc[$jenisTr]["nextStepTo"]) && (sizeof($modul_pathSrc[$jenisTr]["nextStepTo"]) > 0)) {
            foreach ($modul_pathSrc[$jenisTr]["nextStepTo"] as $xx => $next) {
                if (isset($modul_pathSrc[$next]["steps"]) && (sizeof($modul_pathSrc[$next]["steps"]) > 0)) {
                    foreach ($modul_pathSrc[$next]["steps"] as $yy => $stepSpec) {
                        $arrMap[] = array(
                            //                            "step" => $step,
                            "label" => $stepSpec["label"],
                            "target" => $stepSpec["target"],
                            "timeline" => isset($stepSpec["timeline"]) ? $stepSpec["timeline"] : "parent",
                        );
                    }
                }
            }
        }
    }

    return $arrMap;
}

function checkerValidKolom($transaksi_id, $lastTransaksiData)
{
    $CI = &get_instance();
    $CI->load->model("MdlTransaksi");
    $koloms = array(
        "id_master",
        "id_top",
        "jenis",
        "jenis_top",
        "nomer",
        "nomer_top",
        "jenis_master",
        "customers_id",
        "cabang_id",
        "gudang_id",
    );
    $trTmp = array();
    if ($transaksi_id > 0) {
        $tr = New MdlTransaksi();
        $tr->addFilter("id='$transaksi_id'");
        $trTmp = $tr->lookupMainTransaksi()->result();
    }
    if (sizeof($trTmp) > 0) {
        foreach ($koloms as $kolom) {
            $kolom_1 = $trTmp[0]->$kolom;
            $kolom_2 = $lastTransaksiData[$kolom];
            if ($kolom_1 != $kolom_2) {
                $msg = "isi kolom berubah. [$kolom] [$kolom_1] [$kolom_2]";
                mati_disini($msg);
            }
        }
    }

    return true;
}

if (!function_exists('compareRoleByDivision')) {
    function compareRoleByDivision(array $rolesA, array $rolesB, $division)
    {
        $A = filterRolesByDivision($rolesA, $division);
        $B = filterRolesByDivision($rolesB, $division);

        // jika tidak punya role di divisi ini → anggap level 0
        if (empty($A) && empty($B)) {
            return 0;
        }
        if (empty($A)) {
            return -1;
        }
        if (empty($B)) {
            return +1;
        }

        return compareRole($A, $B);
    }

}

if (!function_exists('getRoleHierarchy')) {
    function getRoleHierarchy()
    {
        $CI =& get_instance();
        $CI->load->config('usergroup');
        return $CI->config->item('roleHierarchy');
    }
}

if (!function_exists('filterRolesByDivision')) {
    function filterRolesByDivision(array $roles, $division)
    {
        $roleDivision = getRoleDivision();
        $filtered = [];
        foreach ($roles as $r) {
            if (isset($roleDivision[$r]) && $roleDivision[$r] === $division) {
                $filtered[] = $r;
            }
        }
        return $filtered;
    }
}

if (!function_exists('getRoleDivision')) {
    function getRoleDivision()
    {
        $CI =& get_instance();
        $CI->load->config('usergroup');
        return $CI->config->item('roleDivision');
    }
}

if (!function_exists('getHighestRole')) {
    function getHighestRole(array $roles)
    {
        $hierarchy = getRoleHierarchy();
        $bestRole = null;
        $bestLevel = PHP_INT_MAX;

        foreach ($roles as $r) {
            if (isset($hierarchy[$r]) && $hierarchy[$r] < $bestLevel) {
                $bestLevel = $hierarchy[$r];
                $bestRole = $r;
            }
        }

        return [
            "role" => $bestRole,
            "level" => $bestLevel
        ];
    }
}

if (!function_exists('compareRole')) {
    function compareRole(array $rolesA, array $rolesB)
    {
        $a = getHighestRole($rolesA);
        $b = getHighestRole($rolesB);

        if ($a['level'] > $b['level']) {
            return 1;
        }   // A lebih tinggi
        if ($a['level'] < $b['level']) {
            return -1;
        }  // A lebih rendah
        return 0;                                   // sama level
    }
}

function getMembership($user_id)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlUser");
    $result = array();
    $tr = New MdlUser();
    $tr->addFilter("id=$user_id");
    $trTmp = $tr->lookupAll()->result();

    if (!empty($trTmp)) {
        foreach ($trTmp as $trSpec) {
            $result = blobDecode($trSpec->membership);
        }
    }
    return $result;
}


function kembalikanStokIntransit($arrKiriman)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlLockerStock");
    $ci->load->model("Coms/ComLockerStock");

    $gudangID = $arrKiriman["gudang_id"];
    $cabangID = $arrKiriman["cabang_id"];
    $tabelID = $arrKiriman["tabel_id"];
    $produkID = $arrKiriman["produk_id"];

    $st = new MdlLockerStock();
    $st->setFilters(array());
    $st->addFilter("cabang_id=$cabangID");
    $st->addFilter("gudang_id=$gudangID");
    $st->addFilter("id='$tabelID'");
    $stTmp = $st->lookupAll()->result();
    if (sizeof($stTmp) > 0) {
        $jml = $stTmp[0]->jumlah;
        $olehID = $stTmp[0]->oleh_id;
        $olehNama = $stTmp[0]->oleh_nama;
        $produkNama = $stTmp[0]->produk_nama;

        // mengembalikan hold
        $dataHold[0] = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "jenis" => "produk",
                "state" => "hold",
                "jumlah" => "-$jml",
                "produk_id" => $produkID,
                "nama" => $produkNama,
                "satuan" => "",
                "oleh_id" => $olehID,
                "oleh_nama" => $olehNama,
                "transaksi_id" => "0",
                "nomer" => "0",
                "gudang_id" => $gudangID,
            )
        );
        $dataAktive[0] = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "jenis" => "produk",
                "state" => "active",
                "jumlah" => "$jml",
                "produk_id" => $produkID,
                "nama" => $produkNama,
                "satuan" => "",
                "oleh_id" => "0",
                "oleh_nama" => "",
                "transaksi_id" => "0",
                "nomer" => "0",
                "gudang_id" => $gudangID,
            )
        );

        $cls = New ComLockerStock();
        $cls->pair($dataHold);
        $cls->exec();

        // menambahkan ke active
        $cls = New ComLockerStock();
        $cls->pair($dataAktive);
        $cls->exec();
    }

    return true;
}

function infoIntransit($kiriman)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlLockerStock");
    $ci->load->model("Mdls/MdlCabang");
    $ci->load->model("Mdls/MdlGudangDefault_center");
    $ci->load->model("Mdls/MdlGudangDefault");

    $gudangID = $kiriman["gudang_id"];
    $cabangID = $kiriman["cabang_id"];

    $st = new MdlLockerStock();
    $ca = new MdlCabang();
    $gd_center = new MdlGudangDefault_center();
    $gd_cabang = new MdlGudangDefault();
    $selectedGudangFields = array("id", "name", "nama");

    $tempDefGudangCenter = $gd_center->lookUpAll()->result();
    $tempDefGudang = $gd_cabang->lookUpAll()->result();
    $tempCabang = $ca->lookUpAll()->result();

    //region call all cabang
    $cabangData = array();
    $inCabId = array();
    $allCabang = array();
    foreach ($tempCabang as $tempCabang_0) {
        $inCabId[] = $tempCabang_0->id;
        $allCabang[$tempCabang_0->id] = $tempCabang_0->nama;
        foreach ($selectedGudangFields as $iIndex_key => $iFields) {
            $cabangData[$tempCabang_0->id][$iFields] = isset($tempCabang_0->$iFields) ? $tempCabang_0->$iFields : "";
        }
        // arrPrint($tempCabang_0);
    }
    // arrPrintHijau($cabangData);

    //endregion

    $allGudangDefault = array();
    $allGudangMember = array();
    foreach ($tempDefGudangCenter as $iiCenterData) {
        $allGudangDefault[$iiCenterData->id] = $iiCenterData->id;
        foreach ($selectedGudangFields as $i => $fieldsGud) {
            $gudangDefaultCabang[$iiCenterData->id][$iiCenterData->id][$fieldsGud] = isset($iiCenterData->$fieldsGud) ? $iiCenterData->$fieldsGud : "";
        }
        $allGudangMember[$iiCenterData->id][] = $iiCenterData->id;
    }
    foreach ($tempDefGudang as $iiCabangData) {
        $allGudangDefault[$iiCabangData->id] = $iiCabangData->id;
        foreach ($selectedGudangFields as $i => $fieldsGud) {
            $gudangDefaultCabang[$iiCabangData->cabang_id][$iiCabangData->id][$fieldsGud] = isset($iiCabangData->$fieldsGud) ? $iiCabangData->$fieldsGud : "";
        }
        $allGudangMember[$iiCabangData->cabang_id][] = $iiCabangData->id;
    }


    $allGudangDefault = array();
    $gudangDefaultCabang = array();
    $allGudangDefault[9] = 9;
    $gudangDefaultCabang[-1][9] = array(
        "id" => 9,
        "name" => "Gudang Project Pusat",
        "nama" => "Gudang Project Pusat",
    );

    $tbl_001 = "stock_locker";
    $st->setFilters(array());
    //        $st->addFilter("$tbl_001.gudang_id in ('" . implode("','", $allGudangDefault) . "')");
    $st->addFilter("$tbl_001.cabang_id=$cabangID");
    $st->addFilter("$tbl_001.gudang_id=$gudangID");
    $st->addFilter("$tbl_001.jenis='produk'");
    $st->addFilter("$tbl_001.jenis_locker='stock'");
    $st->addFilter("$tbl_001.state='hold'");
    $st->addFilter("$tbl_001.jumlah>'0'");

    $produk_id = isset($kiriman['id']) ? $kiriman['id'] : "";
    if ($produk_id > 0) {
        $st->addFilter("$tbl_001.produk_id='$produk_id'");
    }
    $tmp_2s_0 = $st->callLockerTransaksi();
    //        cekHitam($ci->db->last_query());
    //        arrPrint($tmp_2s_0);

    foreach ($tmp_2s_0 as $item) {

        /** --------------------------------------------------------------
         * pairing data
         * --------------------------------------------------------------*/
        $item->cabang_nama = isset($cabangData[$item->cabang_id]['nama']) ? $cabangData[$item->cabang_id]['nama'] : "";
        $item->gudang_nama = isset($gudangDefaultCabang[$item->cabang_id][$item->gudang_id]['nama']) ? $gudangDefaultCabang[$item->cabang_id][$item->gudang_id]['nama'] : "";
        $item->oleh_nama = isset($item->tr_oleh_nama) ? $item->tr_oleh_nama : $item->oleh_nama;
        $item->locker_umur = isset($item->last_dtime) ? umurRelatif($item->last_dtime) : '-';

        $tmp_2s[] = $item;
    }

    return $tmp_2s;

}

function infoIntransitView($data, $config = [])
{
    /* -----------------------------------------------------------
         * 1. VALIDASI DATA AWAL
         * ----------------------------------------------------------- */

    // Jika data benar-benar kosong
    if (empty($data) || !is_array($data)) {
        return "<p style='color:red;font-weight:bold;'>Tidak ada data ditemukan.</p>";
    }

    // Jika elemen pertama bukan object
    if (!is_object($data[0])) {
        return "<p style='color:red;font-weight:bold;'>Format data tidak valid.</p>";
    }

    // Periksa apakah semua field penting null (berarti data tidak layak ditampilkan)
    $hasNonEmptyValue = false;
    foreach ($data as $row) {
        foreach ((array)$row as $k => $v) {
            if (!is_null($v) && $v !== "") {
                $hasNonEmptyValue = true;
                break 2;
            }
        }
    }

    if (!$hasNonEmptyValue) {
        return "<p style='color:red;font-weight:bold;'>Data ada, tetapi tidak memiliki nilai yang dapat ditampilkan.</p>";
    }

    // Debug jika diperlukan
    // arrPrint($data);


    /* -----------------------------------------------------------
     * DEFAULT CONFIGURATION
     * ----------------------------------------------------------- */
    $defaultConfig = [
        'title' => 'Data Produk Berdasarkan Cabang',
        'group_by' => ['cabang_id', 'transaksi_id'],
        'headers' => [
            'cabang_nama' => [
                'label' => 'Cabang',
                'type' => 'group',
                'class' => 'cabang-header',
                'formatter' => function ($value) {
                    return htmlspecialchars($value);
                }
            ],

            'gudang_nama' => [
                'label' => 'Gudang',
                'type' => 'group',
                'class' => 'cabang-header',
                'formatter' => function ($value) {
                    return htmlspecialchars($value);
                }
            ],

            // 'tr_jenis' => [
            //     'label' => 'Jenis',
            //     'type'  => 'group',
            //     'class' => 'cabang-header',
            //     'formatter' => function ($value) {
            //         return htmlspecialchars($value);
            //     }
            // ],

            'transaksi_id' => [
                'label' => 'Transaksi',
                'type' => 'group',
                'class' => 'transaksi-header',
                'formatter' => function ($value, $item) {
                    // return ($value == 0) ? "Booking" : "Transaksi #" . $value;

                    if ($value == 0) {
                        return "Booking";
                    }

                    // ambil kolom lain: $item->kode_transaksi
                    $kode = isset($item->tr_jenis_label) ? $item->tr_jenis_label : '(tanpa kode)';
                    $nomer = isset($item->tr_nomer) ? $item->tr_nomer : '(tanpa kode)';

                    return "Transaksi #" . $value . " / " . htmlspecialchars($kode) . " / " . htmlspecialchars($nomer);
                }
            ],

            'jumlah' => [
                'label' => 'Jumlah',
                'class' => function ($value) {
                    return $value > 0 ? 'stok-ada' : 'stok-habis';
                },
                'formatter' => function ($value, $item) {
                    return htmlspecialchars($value) . ' ' . htmlspecialchars($item->satuan);
                },
                'align' => 'right'
            ],

            'last_dtime' => [
                'label' => 'Update Terakhir',
                'formatter' => function ($value) {
                    return date('d M Y H:i', strtotime($value));
                }
            ],
            'locker_umur' => [
                'label' => 'Umur',
                'formatter' => function ($value) {
                    return $value;
                }
            ],

            'oleh_nama' => [
                'label' => 'Pengunci',
                'formatter' => function ($value) {
                    return htmlspecialchars($value);
                }
            ],
            'action' => [
                'label' => 'Action',
                //                    'formatter' => function ($value) {
                //                        return htmlspecialchars($value);
                //                    }
            ],
        ],
        'show_summary' => true,
        'summary_fields' => ['nama', 'produk_id', 'satuan'],
        'exclude_fields' => [],
        'custom_css' => ''
    ];

    $config = array_merge_recursive($defaultConfig, $config);

    // Filter header berdasarkan yang ingin dihapus
    $headers = array_diff_key($config['headers'], array_flip($config['exclude_fields']));

    /* -----------------------------------------------------------
     * CSS
     * ----------------------------------------------------------- */
    $css = '
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #4CAF50; color: white; }
        .cabang-header { background: #2196F3; color: white; }
        .transaksi-header { background: #bbdefb; }
        .stok-ada { color: green; font-weight: bold; }
        .stok-habis { color: red; font-weight: bold; }
        .summary { background: #e8f5e9; padding: 15px; margin-bottom: 20px; }
        ' . $config['custom_css'] . '
    </style>';

    $output = $css;


    /* -----------------------------------------------------------
     * SUMMARY
     * ----------------------------------------------------------- */
    if ($config['show_summary']) {

        // NEW ERROR HANDLING → cek apakah summary fields tersedia
        $summaryData = new stdClass();
        $summaryCollected = false;

        foreach ($data as $item) {

            if (!$summaryCollected) {
                foreach ($config['summary_fields'] as $field) {
                    if (isset($item->$field) && $item->$field !== "") {
                        $summaryData->$field = htmlspecialchars($item->$field);
                    }
                    else {
                        $summaryData->$field = "-";
                    }
                }
                $summaryCollected = true;
            }
        }

        if (!$summaryCollected) {
            $output .= "<p style='color:red;'>Summary tidak dapat dibuat: data tidak lengkap.</p>";
        }
        else {
            $total = 0;
            $branches = [];

            foreach ($data as $item) {
                if (isset($item->jumlah)) {
                    $total += (float)$item->jumlah;
                }
                if (isset($item->cabang_id)) {
                    $branches[$item->cabang_id] = true;
                }
            }

            $output .= "
            <div class='summary'>
                <h2 class='no-padding no-margin'>{$summaryData->nama}</h2>
                <p><strong>Produk ID:</strong> {$summaryData->produk_id} |
                   <strong>Satuan:</strong> {$summaryData->satuan}</p>

                <p><strong>Total Stok Intransit:</strong> {$total} {$summaryData->satuan}</p>
                <p><strong>Jumlah Cabang:</strong> " . count($branches) . "</p>
            </div>";
        }
    }


    /* -----------------------------------------------------------
     * TABLE HEADER
     * ----------------------------------------------------------- */
    $output .= '<table>';
    $output .= '<tr>';

    foreach ($headers as $field => $headerConfig) {
        $label = isset($headerConfig['label']) ? $headerConfig['label'] : ucfirst($field);
        $align = isset($headerConfig['align']) ? " style='text-align: {$headerConfig['align']};'" : '';
        $output .= "<th{$align}>{$label}</th>";
    }

    $output .= '</tr>';


    /* -----------------------------------------------------------
     * GROUPING
     * ----------------------------------------------------------- */
    $grouped = [];

    if (sizeof($data) > 0) {

        foreach ($data as $item) {

            // Safety: jika item bukan object skip
            if (!is_object($item)) {
                continue;
            }

            $keyParts = [];
            foreach ($config['group_by'] as $groupField) {
                $keyParts[] = isset($item->$groupField) ? $item->$groupField : 'null';
            }

            $key = implode('-', $keyParts);
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $item;
        }

        if (empty($grouped)) {
            return "<p style='color:red;'>Data tidak dapat digrouping.</p>";
        }

        ksort($grouped);

        /* -----------------------------------------------------------
         * TABLE CONTENT
         * ----------------------------------------------------------- */
        foreach ($grouped as $key => $items) {
            $rowspan = count($items);

            foreach ($items as $index => $item) {
                $output .= '<tr>';

                foreach ($headers as $field => $headerConfig) {

                    $value = isset($item->$field) ? $item->$field : '';

                    $isGroupField = in_array($field, $config['group_by']);
                    $attributes = '';

                    // rowspan untuk group field
                    if ($isGroupField && $index === 0) {
                        $attributes .= " rowspan='{$rowspan}'";
                    }

                    // Class handling
                    $classes = [];
                    if (isset($headerConfig['class'])) {
                        if (is_callable($headerConfig['class'])) {
                            $className = $headerConfig['class']($value, $item);
                            if ($className) {
                                $classes[] = $className;
                            }
                        }
                        else {
                            $classes[] = $headerConfig['class'];
                        }
                    }

                    if (!empty($classes)) {
                        $attributes .= " class='" . implode(' ', $classes) . "'";
                    }

                    // Style handling
                    if (isset($headerConfig['align'])) {
                        $attributes .= " style='text-align:{$headerConfig['align']};'";
                    }

                    // Formatter
                    if (isset($headerConfig['formatter']) && is_callable($headerConfig['formatter'])) {
                        $displayValue = $headerConfig['formatter']($value, $item);
                    }
                    else {
                        $displayValue = htmlspecialchars($value);
                    }

                    // Jika value kosong total → tampilkan "-"
                    if ($displayValue === "" || $displayValue === null) {
                        $displayValue = "<span style='color:#999;'>-</span>";
                    }

                    //                    $output .= "<td{$attributes}>{$displayValue}</td>";

                    if (($field == "action") && ($item->transaksi_id == 0)) {
                        $gudang_id = $item->gudang_id;
                        $cabang_id = $item->cabang_id;
                        $produk_id = $item->produk_id;
                        $tabel_id = $item->id;
                        $link_kembalikan_stok = MODUL_PATH . get_class($this) . "/kembalikanStok?gudang_id=$gudang_id&cabang_id=$cabang_id&produk_id=$produk_id&tabel_id=$tabel_id";
                        $output .= "<td>";
                        $output .= "<button class='btn btn-warning btn-sm'
                        onclick=\"$('#result').load('$link_kembalikan_stok');\"
                        >Kembalikan Stok";
                        $output .= "</button>";
                        $output .= "</td>";
                    }
                    else {
                        $output .= "<td{$attributes}>{$displayValue}</td>";
                    }

                }

                $output .= '</tr>';
            }
        }
    }

    $output .= '</table>';

    return $output;

}

function konvertGerbangTaxes($cCode, $sessionMain)
{
    $arrGerbang = array();
    $arrGerbang = array(
        "dpp_taxes" => "nett1_bulat",
        "dpp_pengganti_taxes" => "(11/12)*nett1_bulat",
        "ppn_taxes" => "grand_ppn",
        "grand_total_taxes" => "grand_pembulatan",
    );
    // konverter gerbang nilai dpp_taxes, dpp_pengganti_taxes, ppn_taxes, grand_total_taxes
    if (isset($sessionMain["projectID"]) && ($sessionMain["projectID"] > 0)) {
        $arrGerbang = array(
            "dpp_taxes" => "nilai_bayar",
            "dpp_pengganti_taxes" => "dpp_pengganti",
            "ppn_taxes" => "grand_ppn",
            "grand_total_taxes" => "grandTotal",
        );
    }
    if (isset($sessionMain["gunggunganMode"]) && ($sessionMain["gunggunganMode"] > 0)) {
        $arrGerbang = array(
            "dpp_taxes" => "nett1_bulat",
            "dpp_pengganti_taxes" => "(11/12)*nett1_bulat",
            "ppn_taxes" => "new_grand_ppn",
            "grand_total_taxes" => "grand_pembulatan",
        );
    }
    if (isset($sessionMain["pihakMainID_coa"]) && ($sessionMain["pihakMainID_coa"] > 0)) {
        $arrGerbang = array(
            "dpp_taxes" => "new_net3",
            "dpp_pengganti_taxes" => "(11/12)*new_net3",
            "ppn_taxes" => "grand_ppn",
            "grand_total_taxes" => "dpp_nppn",
        );
    }

    if (sizeof($arrGerbang) > 0) {
        foreach ($arrGerbang as $key => $val) {
            if (!isset($_SESSION[$cCode]["main"][$key])) {
                $_SESSION[$cCode]["main"][$key] = makeValue($val, $sessionMain, $sessionMain, 0);
            }
        }
    }

}

function notifTransaksiError($file, $line)
{
    $msg = "Ada transaksi yang tidak sesuai. Mohon mengirim pesan ini ke Admin.<br><br>";
    $msg .= "$file line $line";

    return $msg;
}


function he_clone_transaction_session($oldCode, $newCode)
{
    // START OF COMPLETE REPEATED LOGIC
    $standardKeys = array(
        "main", "items", "items2", "items2_sum", "itemSrc", "itemSrc_sum", "items3", "items3_sum", "items4", "items4_sum",
        "items_noapprove", "rsltItems", "rsltItems2", "rsltItems3", "tableIn_master", "tableIn_detail", "tableIn_detail2_sum",
        "tableIn_detail_rsltItems", "tableIn_detail_rsltItems2", "tableIn_master_values", "tableIn_detail_values",
        "tableIn_detail_values_rsltItems", "tableIn_detail_values_rsltItems2", "tableIn_detail_values2_sum", "main_add_values", "main_add_fields",
        "main_elements", "main_inputs", "main_inputs_orig", "receiptDetailFields", "receiptSumFields", "receiptDetailFields2",
        "receiptDetailSrcFields", "receiptSumFields2", "jurnal_index", "postProcessor", "preProcessor", "revert",
        "items_komposisi", "jurnalItems", "componentsBuilder", "items5_sum", "items6_sum", "items7_sum", "items8_sum",
        "items9_sum", "items10_sum", "trash", "rsltItems3_sub", "rsltItems_revert", "rsltItems2_revert", "mainOriginal",
        "itemsOriginal", "items6", "items7", "items5", "itemsTarget1", "itemsSrc1", "itemsSrc1_sum", "revertAuto"
    );

    if (isset($_SESSION[$newCode])) {
        $_SESSION[$newCode] = null;
        unset($_SESSION[$newCode]);
    }
    $_SESSION[$newCode] = array();

    foreach ($standardKeys as $key) {
        $_SESSION[$newCode][$key] = isset($_SESSION[$oldCode][$key]) ? $_SESSION[$oldCode][$key] : array();
    }
    // END OF COMPLETE REPEATED LOGIC
}

function renderDynamicHistoryInfoBox__($configUi, $jenisMasterTrans, $stepNumActive, $mergedData)
{
    $historyInfoStr = "";
    $stepNum = $stepNumActive > 0 ? $stepNumActive : 1;
    $activeHistoryFields = isset($configUi[$jenisMasterTrans]['historyFields'][$stepNum])
        ? $configUi[$jenisMasterTrans]['historyFields'][$stepNum]
        : (isset($configUi[$jenisMasterTrans]['shortHistoryFields'])
            ? $configUi[$jenisMasterTrans]['shortHistoryFields']
            : array());

    if (sizeof($activeHistoryFields) > 0) {
        $blacklistedFields = array('no', 'tool', 'details', 'print_label', 'item_fields');
        $validFields = array();
        foreach ($activeHistoryFields as $fKey => $fLabel) {
            if (!in_array($fKey, $blacklistedFields)) {
                $validFields[$fKey] = $fLabel;
            }
        }

        if (sizeof($validFields) > 0) {
            $longTextFields = array();
            $gridFields = array();

            foreach ($validFields as $fKey => $fLabel) {
                if (in_array($fKey, array('description', 'catatan', 'keterangan'))) {
                    $longTextFields[$fKey] = $fLabel;
                }
                else {
                    $gridFields[$fKey] = $fLabel;
                }
            }

            $half = ceil(sizeof($gridFields) / 2);
            $chunks = array_chunk($gridFields, $half, true);

            $historyInfoStr .= "<div class='panel panel-default' style='margin-top:15px; margin-bottom:15px;'>";
            $historyInfoStr .= "<div class='panel-heading' style='background-color:#f5f5f5;'><h4 class='panel-title text-blue' style='font-size:14px; font-weight:bold;'><span class='fa fa-list-alt'></span> INFORMASI UTAMA TRANSAKSI</h4></div>";
            $historyInfoStr .= "<div class='panel-body'><div class='row'>";

            foreach ($chunks as $chunk) {
                $historyInfoStr .= "<div class='col-md-6 col-sm-6'><table class='table table-condensed table-borderless' style='margin-bottom:0; width:100%; table-layout:fixed;'>";
                foreach ($chunk as $fKey => $fLabel) {
                    $fVal = "-";

                    // 1. Direct key match
                    if (isset($mergedData[$fKey]) && strlen(trim($mergedData[$fKey])) > 0) {
                        $fVal = $mergedData[$fKey];
                    }
                    else {
                        // 2. Fallback aliases for composite keys across CI3 modules
                        switch ($fKey) {
                            case "nomer_top":
                            case "nomer":
                                $fVal = isset($mergedData['nomer']) ? formatField_he_format("nomer_nolink", $mergedData['nomer']) : (isset($mergedData['nomer_top']) ? $mergedData['nomer_top'] : "-");
                                break;
                            case "nett":
                            case "nilai_entry":
                            case "nilai_bayar":
                                if (isset($mergedData['nett']) && $mergedData['nett'] > 0) {
                                    $fVal = number_format($mergedData['nett'], 0, ',', '.');
                                }
                                elseif (isset($mergedData['nilai_entry']) && $mergedData['nilai_entry'] > 0) {
                                    $fVal = number_format($mergedData['nilai_entry'], 0, ',', '.');
                                }
                                elseif (isset($mergedData['nilai_bayar']) && $mergedData['nilai_bayar'] > 0) {
                                    $fVal = number_format($mergedData['nilai_bayar'], 0, ',', '.');
                                }
                                elseif (isset($mergedData['harga']) && $mergedData['harga'] > 0) {
                                    $fVal = number_format($mergedData['harga'], 0, ',', '.');
                                }
                                break;
                            case "referensi_so__nomer":
                                $fVal = isset($mergedData['referensi_so__nomer']) ? $mergedData['referensi_so__nomer'] : (isset($mergedData['ref_nomer']) ? $mergedData['ref_nomer'] : (isset($mergedData['nomer_top']) ? $mergedData['nomer_top'] : "-"));
                                break;
                            case "referensi_so__project_nama":
                                $fVal = isset($mergedData['referensi_so__project_nama']) ? $mergedData['referensi_so__project_nama'] : (isset($mergedData['project_nama']) ? $mergedData['project_nama'] : "-");
                                break;
                            case "cash_account__folders_nama":
                                $fVal = isset($mergedData['cash_account__folders_nama']) ? $mergedData['cash_account__folders_nama'] : (isset($mergedData['bank_nama']) ? $mergedData['bank_nama'] : (isset($mergedData['cash_account_source__label']) ? $mergedData['cash_account_source__label'] : "-"));
                                break;
                            case "cash_account__label":
                                $fVal = isset($mergedData['cash_account__label']) ? $mergedData['cash_account__label'] : (isset($mergedData['bank_rekening_no']) ? $mergedData['bank_rekening_no'] : (isset($mergedData['bank_rekening_nama']) ? $mergedData['bank_rekening_nama'] : "-"));
                                break;
                            case "referensiNota__label":
                                $fVal = isset($mergedData['referensiNota__label']) ? $mergedData['referensiNota__label'] : (isset($mergedData['titipan_info']) ? $mergedData['titipan_info'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                                break;
                        }
                    }

                    // Format numeric amounts if raw number
                    if (in_array($fKey, array('nett', 'nilai_entry', 'nilai_bayar', 'harga', 'lebih_bayar', 'uang_muka_dipakai', 'nilai_biaya')) && is_numeric($fVal)) {
                        $fVal = number_format((float)$fVal, 0, ',', '.');
                    }

                    // Clean up newlines from link elements
                    if ($fKey == 'nomer_top' || $fKey == 'nomer') {
                        $fVal = str_replace(array("<br>", "<br/>", "<br />", "\n", "\r"), " ", $fVal);
                    }

                    // Check if referensiNota__label is a very long string, move to longTextFields
                    if ($fKey == 'referensiNota__label' && strlen(strip_tags($fVal)) > 35) {
                        $longTextFields[$fKey] = $fLabel;
                        continue;
                    }

                    $historyInfoStr .= "<tr>";
                    $historyInfoStr .= "<td class='text-muted' style='width:32%; font-weight:600; vertical-align:top; padding:4px 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;'>" . strip_tags($fLabel) . "</td>";
                    $historyInfoStr .= "<td style='width:15px; font-weight:bold; vertical-align:top; padding:4px 0;'>:</td>";
                    $historyInfoStr .= "<td style='vertical-align:top; padding:4px 0; word-break:break-word; word-wrap:break-word;'>" . $fVal . "</td>";
                    $historyInfoStr .= "</tr>";
                }
                $historyInfoStr .= "</table></div>";
            }

            // Render full width long text notes if present
            if (sizeof($longTextFields) > 0) {
                $historyInfoStr .= "<div class='col-md-12 col-sm-12' style='margin-top:10px;'>";
                foreach ($longTextFields as $lfKey => $lfLabel) {
                    $lfVal = "-";
                    if (isset($mergedData[$lfKey]) && strlen(trim($mergedData[$lfKey])) > 0) {
                        $lfVal = $mergedData[$lfKey];
                    }
                    else {
                        if ($lfKey == 'referensiNota__label') {
                            $lfVal = isset($mergedData['referensiNota__label']) ? $mergedData['referensiNota__label'] : (isset($mergedData['titipan_info']) ? $mergedData['titipan_info'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                        }
                        else {
                            $lfVal = isset($mergedData['description']) ? $mergedData['description'] : (isset($mergedData['catatan']) ? $mergedData['catatan'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                        }
                    }

                    $historyInfoStr .= "<div style='background-color:#f8f9fa; border:1px solid #e9ecef; border-radius:4px; padding:8px 12px; margin-bottom:6px; word-break:break-word; word-wrap:break-word;'>";
                    $historyInfoStr .= "<span class='text-muted' style='font-weight:600;'>" . strip_tags($lfLabel) . ": </span>";
                    $historyInfoStr .= "<span>" . $lfVal . "</span>";
                    $historyInfoStr .= "</div>";
                }
                $historyInfoStr .= "</div>";
            }

            $historyInfoStr .= "</div></div></div>";
        }
    }

    return $historyInfoStr;
}

function renderDynamicHistoryInfoBox($configUi, $jenisMasterTrans, $stepNumActive, $mergedData)
{
    $historyInfoStr = "";
    $stepNum = $stepNumActive > 0 ? $stepNumActive : 1;
    $activeHistoryFields = isset($configUi[$jenisMasterTrans]['historyFields'][$stepNum])
        ? $configUi[$jenisMasterTrans]['historyFields'][$stepNum]
        : (isset($configUi[$jenisMasterTrans]['shortHistoryFields'])
            ? $configUi[$jenisMasterTrans]['shortHistoryFields']
            : array());

    if (sizeof($activeHistoryFields) > 0) {
        $blacklistedFields = array('no', 'tool', 'details', 'print_label', 'item_fields');
        $validFields = array();
        foreach ($activeHistoryFields as $fKey => $fLabel) {
            if (!in_array($fKey, $blacklistedFields)) {
                $validFields[$fKey] = $fLabel;
            }
        }

        if (sizeof($validFields) > 0) {
            $longTextFields = array();
            $gridFields = array();

            foreach ($validFields as $fKey => $fLabel) {
                if (in_array($fKey, array('description', 'catatan', 'keterangan'))) {
                    $longTextFields[$fKey] = $fLabel;
                }
                else {
                    $gridFields[$fKey] = $fLabel;
                }
            }

            // START OF COMPLETE REPEATED LOGIC
            // Detect settlement payment source accurately across all CI3 deposit keys
            $checkDepositKeys = array(
                'uang_muka_dipakai_ppn',
                'uang_muka_dipakai',
                'uang_muka_tanpa_ppn',
                'credit_note_dipakai',
                'uangMukaProject',
                'uangMuka',
                'creditNote',
                'uang_muka_project',
                'uang_muka',
                'credit_note'
            );

            $totalDepositUsed = 0;
            foreach ($checkDepositKeys as $dpKey) {
                if (isset($mergedData[$dpKey]) && !is_bool($mergedData[$dpKey]) && !empty($mergedData[$dpKey])) {
                    $cleanedDP = is_numeric($mergedData[$dpKey]) ? floatval($mergedData[$dpKey]) : floatval(str_replace('.', '', $mergedData[$dpKey]));
                    if ($cleanedDP > 0) {
                        $totalDepositUsed += $cleanedDP;
                    }
                }
            }

            // Extract Tagihan Amount
            $valTagihan = 0;
            $checkTagihanKeys = array('tagihan', 'harus_bayar', 'sisa_tagihan');
            foreach ($checkTagihanKeys as $tKey) {
                if (isset($mergedData[$tKey]) && !is_bool($mergedData[$tKey]) && !empty($mergedData[$tKey])) {
                    $cleanedT = is_numeric($mergedData[$tKey]) ? floatval($mergedData[$tKey]) : floatval(str_replace('.', '', $mergedData[$tKey]));
                    if ($cleanedT > $valTagihan) {
                        $valTagihan = $cleanedT;
                    }
                }
            }

            // Extract Gross Payment Amount (from kas/bank entry)
            $valGrossPayment = 0;
            $checkKasBankKeys = array('diterima', 'uang_diterima', 'nilai_entry', 'nilai_bayar', 'harga', 'nett');
            foreach ($checkKasBankKeys as $cKey) {
                if (isset($mergedData[$cKey]) && !is_bool($mergedData[$cKey]) && !empty($mergedData[$cKey])) {
                    $rawK = is_numeric($mergedData[$cKey]) ? floatval($mergedData[$cKey]) : floatval(str_replace('.', '', $mergedData[$cKey]));
                    if ($rawK > $valGrossPayment) {
                        $valGrossPayment = $rawK;
                    }
                }
            }

            // Kelebihan bayar yang TERBENTUK (Overpayment generated - deposit_konsumen / lebih_bayar)
            $valLebihBayarGen = 0;
            $checkLebihBayarKeys = array('deposit_konsumen', 'lebih_bayar', 'kelebihan_bayar_nilai', 'kelebihan_bayar', 'pendapatan_lain_lain');
            foreach ($checkLebihBayarKeys as $lKey) {
                if (isset($mergedData[$lKey]) && !is_bool($mergedData[$lKey]) && !empty($mergedData[$lKey])) {
                    $cleanedL = is_numeric($mergedData[$lKey]) ? floatval($mergedData[$lKey]) : floatval(str_replace('.', '', $mergedData[$lKey]));
                    if ($cleanedL > $valLebihBayarGen) {
                        $valLebihBayarGen = $cleanedL;
                    }
                }
            }

            if ($valLebihBayarGen == 0 && $valGrossPayment > $valTagihan && $valTagihan > 0 && $totalDepositUsed == 0) {
                $valLebihBayarGen = $valGrossPayment - $valTagihan;
            }

            // Net Kas/Bank Received = If total deposit used covers tagihan, kas/bank nett is 0!
            if ($totalDepositUsed >= $valTagihan && $valTagihan > 0) {
                $valKasBankNett = 0;
            }
            else {
                $valKasBankNett = max(0, $valGrossPayment - $totalDepositUsed);
            }

            // Determine exact scenario
            $isDepositOnly = ($totalDepositUsed > 0 && $valKasBankNett == 0);
            $isKasBankOverpayment = ($valKasBankNett > 0 && $valLebihBayarGen > 0 && $totalDepositUsed == 0);
            $isCombined = ($valKasBankNett > 0 && $totalDepositUsed > 0);

            $half = ceil(sizeof($gridFields) / 2);
            $chunks = array_chunk($gridFields, $half, true);

            $historyInfoStr .= "<div class='panel panel-default' style='margin-top:15px; margin-bottom:15px;'>";

            // Add Dynamic Settlement Badge Header Banner
            if ($isDepositOnly) {
                $historyInfoStr .= "<div style='background-color:#e8f4f8; border:1px solid #bce8f1; border-left:5px solid #31708f; color:#31708f; padding:10px 15px; font-size:13px; font-weight:600;'>";
                $historyInfoStr .= "<span class='fa fa-credit-card'></span> METODE PELUNASAN: POTONG UANG MUKA / DEPOSIT KONSUMEN<br>";
                $historyInfoStr .= "<span style='font-size:11px; font-weight:normal;'><i class='fa fa-check-circle'></i> Status: LUNAS (Tidak ada mutasi penerimaan kas/bank baru)</span>";
                $historyInfoStr .= "</div>";
            }
            elseif ($isKasBankOverpayment) {
                $historyInfoStr .= "<div style='background-color:#fcf8e3; border:1px solid #faebcc; border-left:5px solid #8a6d3b; color:#8a6d3b; padding:10px 15px; font-size:13px; font-weight:600;'>";
                $historyInfoStr .= "<span class='fa fa-university'></span> METODE PELUNASAN: TRANSFER KAS / BANK (TERDAPAT KELEBIHAN BAYAR)<br>";
                $historyInfoStr .= "<span style='font-size:11px; font-weight:normal;'><i class='fa fa-info-circle'></i> Uang Diterima: Rp " . number_format($valGrossPayment, 0, ',', '.') . " | Tercipta Deposit Kelebihan Bayar: Rp " . number_format($valLebihBayarGen, 0, ',', '.') . "</span>";
                $historyInfoStr .= "</div>";
            }
            elseif ($isCombined) {
                $historyInfoStr .= "<div style='background-color:#f0f7e6; border:1px solid #d6e9c6; border-left:5px solid #3c763d; color:#3c763d; padding:10px 15px; font-size:13px; font-weight:600;'>";
                $historyInfoStr .= "<span class='fa fa-exchange'></span> METODE PELUNASAN: KOMBINASI (KAS/BANK + POTONG DEPOSIT)<br>";
                $historyInfoStr .= "<span style='font-size:11px; font-weight:normal;'><i class='fa fa-check-circle'></i> Kas/Bank: Rp " . number_format($valKasBankNett, 0, ',', '.') . " | Deposit: Rp " . number_format($totalDepositUsed, 0, ',', '.') . "</span>";
                $historyInfoStr .= "</div>";
            }
            else {
                $historyInfoStr .= "<div style='background-color:#f0f7e6; border:1px solid #d6e9c6; border-left:5px solid #3c763d; color:#3c763d; padding:10px 15px; font-size:13px; font-weight:600;'>";
                $historyInfoStr .= "<span class='fa fa-university'></span> METODE PELUNASAN: KAS / BANK<br>";
                $historyInfoStr .= "<span style='font-size:11px; font-weight:normal;'><i class='fa fa-check-circle'></i> Status: LUNAS (Pembayaran Kas/Bank)</span>";
                $historyInfoStr .= "</div>";
            }
            // END OF COMPLETE REPEATED LOGIC

            $historyInfoStr .= "<div class='panel-heading' style='background-color:#f5f5f5;'><h4 class='panel-title text-blue' style='font-size:14px; font-weight:bold;'><span class='fa fa-list-alt'></span> INFORMASI UTAMA TRANSAKSI</h4></div>";
            $historyInfoStr .= "<div class='panel-body'><div class='row'>";

            foreach ($chunks as $chunk) {
                $historyInfoStr .= "<div class='col-md-6 col-sm-6'><table class='table table-condensed table-borderless' style='margin-bottom:0; width:100%; table-layout:fixed;'>";
                foreach ($chunk as $fKey => $fLabel) {
                    $fVal = "-";

                    // Override values for deposit-only settlement to prevent user confusion
                    if ($isDepositOnly) {
                        if ($fKey == 'paymentMethod' || strpos($fKey, 'cash_account_source') !== false) {
                            $fVal = "<span class='label label-info'>Potong Uang Muka / Deposit</span>";
                        }
                        elseif ($fKey == 'cash_account__folders_nama' || $fKey == 'cash_account__label') {
                            $fVal = "<span class='text-muted'>- (Tanpa Mutasi Kas/Bank)</span>";
                        }
                        elseif ($fKey == 'diterima' || $fKey == 'nilai_entry') {
                            $fVal = "<span class='text-muted'>Rp 0</span>";
                        }
                    }

                    if ($fVal === "-") {
                        // 1. Direct key match
                        if (isset($mergedData[$fKey]) && strlen(trim($mergedData[$fKey])) > 0) {
                            $fVal = $mergedData[$fKey];
                        }
                        else {
                            // 2. Fallback aliases for composite keys across CI3 modules
                            switch ($fKey) {
                                case "nomer_top":
                                case "nomer":
                                    $fVal = isset($mergedData['nomer']) ? formatField_he_format("nomer_nolink", $mergedData['nomer']) : (isset($mergedData['nomer_top']) ? $mergedData['nomer_top'] : "-");
                                    break;
                                case "nett":
                                case "nilai_entry":
                                case "nilai_bayar":
                                    if (isset($mergedData['nett']) && $mergedData['nett'] > 0) {
                                        $fVal = number_format($mergedData['nett'], 0, ',', '.');
                                    }
                                    elseif (isset($mergedData['nilai_entry']) && $mergedData['nilai_entry'] > 0) {
                                        $fVal = number_format($mergedData['nilai_entry'], 0, ',', '.');
                                    }
                                    elseif (isset($mergedData['nilai_bayar']) && $mergedData['nilai_bayar'] > 0) {
                                        $fVal = number_format($mergedData['nilai_bayar'], 0, ',', '.');
                                    }
                                    elseif (isset($mergedData['harga']) && $mergedData['harga'] > 0) {
                                        $fVal = number_format($mergedData['harga'], 0, ',', '.');
                                    }
                                    break;
                                case "referensi_so__nomer":
                                    $fVal = isset($mergedData['referensi_so__nomer']) ? $mergedData['referensi_so__nomer'] : (isset($mergedData['ref_nomer']) ? $mergedData['ref_nomer'] : (isset($mergedData['nomer_top']) ? $mergedData['nomer_top'] : "-"));
                                    break;
                                case "referensi_so__project_nama":
                                    $fVal = isset($mergedData['referensi_so__project_nama']) ? $mergedData['referensi_so__project_nama'] : (isset($mergedData['project_nama']) ? $mergedData['project_nama'] : "-");
                                    break;
                                case "cash_account__folders_nama":
                                    $fVal = isset($mergedData['cash_account__folders_nama']) ? $mergedData['cash_account__folders_nama'] : (isset($mergedData['bank_nama']) ? $mergedData['bank_nama'] : (isset($mergedData['cash_account_source__label']) ? $mergedData['cash_account_source__label'] : "-"));
                                    break;
                                case "cash_account__label":
                                    $fVal = isset($mergedData['cash_account__label']) ? $mergedData['cash_account__label'] : (isset($mergedData['bank_rekening_no']) ? $mergedData['bank_rekening_no'] : (isset($mergedData['bank_rekening_nama']) ? $mergedData['bank_rekening_nama'] : "-"));
                                    break;
                                case "referensiNota__label":
                                    $fVal = isset($mergedData['referensiNota__label']) ? $mergedData['referensiNota__label'] : (isset($mergedData['titipan_info']) ? $mergedData['titipan_info'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                                    break;
                            }
                        }
                    }

                    // Format numeric amounts if raw number
                    if (in_array($fKey, array('nett', 'nilai_entry', 'nilai_bayar', 'harga', 'lebih_bayar', 'uang_muka_dipakai', 'nilai_biaya')) && is_numeric($fVal)) {
                        $fVal = number_format((float)$fVal, 0, ',', '.');
                    }

                    // Clean up newlines from link elements
                    if ($fKey == 'nomer_top' || $fKey == 'nomer') {
                        $fVal = str_replace(array("<br>", "<br/>", "<br />", "\n", "\r"), " ", $fVal);
                    }

                    // Check if referensiNota__label is a very long string, move to longTextFields
                    if ($fKey == 'referensiNota__label' && strlen(strip_tags($fVal)) > 35) {
                        $longTextFields[$fKey] = $fLabel;
                        continue;
                    }

                    $historyInfoStr .= "<tr>";
                    $historyInfoStr .= "<td class='text-muted' style='width:32%; font-weight:600; vertical-align:top; padding:4px 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;'>" . strip_tags($fLabel) . "</td>";
                    $historyInfoStr .= "<td style='width:15px; font-weight:bold; vertical-align:top; padding:4px 0;'>:</td>";
                    $historyInfoStr .= "<td style='vertical-align:top; padding:4px 0; word-break:break-word; word-wrap:break-word;'>" . $fVal . "</td>";
                    $historyInfoStr .= "</tr>";
                }
                $historyInfoStr .= "</table></div>";
            }

            // Render full width long text notes if present
            if (sizeof($longTextFields) > 0) {
                $historyInfoStr .= "<div class='col-md-12 col-sm-12' style='margin-top:10px;'>";
                foreach ($longTextFields as $lfKey => $lfLabel) {
                    $lfVal = "-";
                    if (isset($mergedData[$lfKey]) && strlen(trim($mergedData[$lfKey])) > 0) {
                        $lfVal = $mergedData[$lfKey];
                    }
                    else {
                        if ($lfKey == 'referensiNota__label') {
                            $lfVal = isset($mergedData['referensiNota__label']) ? $mergedData['referensiNota__label'] : (isset($mergedData['titipan_info']) ? $mergedData['titipan_info'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                        }
                        else {
                            $lfVal = isset($mergedData['description']) ? $mergedData['description'] : (isset($mergedData['catatan']) ? $mergedData['catatan'] : (isset($mergedData['keterangan']) ? $mergedData['keterangan'] : "-"));
                        }
                    }

                    $historyInfoStr .= "<div style='background-color:#f8f9fa; border:1px solid #e9ecef; border-radius:4px; padding:8px 12px; margin-bottom:6px; word-break:break-word; word-wrap:break-word;'>";
                    $historyInfoStr .= "<span class='text-muted' style='font-weight:600;'>" . strip_tags($lfLabel) . ": </span>";
                    $historyInfoStr .= "<span>" . $lfVal . "</span>";
                    $historyInfoStr .= "</div>";
                }
                $historyInfoStr .= "</div>";
            }

            $historyInfoStr .= "</div></div></div>";
        }
    }

    return $historyInfoStr;
}


?>
