<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 24/08/2018
 * Time: 10.14
 */

//  region date

function dtimeNow($format = "Y-m-d H:i:s")
{
    return date("$format");
}

function namaBulan()
{
    $arrBulan = array(
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Maret",
        "04" => "April",
        "05" => "Mei",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Agustus",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    );

    return $arrBulan;
}

function namaBulan2()
{
    $arrBulan = array(
        "1" => "Januari",
        "2" => "Februari",
        "3" => "Maret",
        "4" => "April",
        "5" => "Mei",
        "6" => "Juni",
        "7" => "Juli",
        "8" => "Agustus",
        "9" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember",
    );

    return $arrBulan;
}

function namaBulanSingkat()
{
    $arrBulan = array(
        "01" => "Jan",
        "02" => "Feb",
        "03" => "Mar",
        "04" => "Apr",
        "05" => "Mei",
        "06" => "Jun",
        "07" => "Jul",
        "08" => "Agu",
        "09" => "Sep",
        "10" => "Okt",
        "11" => "Nov",
        "12" => "Des",
    );

    return $arrBulan;
}

function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB')
{
    //    !isset($timestamp) ? die(toAlert("tidak terdeteksi tanggal")) : "";
    if (!isset($timestamp)) {
        return "error: dateTime";
    }

    if (trim($timestamp) == '') {
        $timestamp = time();
    }
    elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/',
        '/Tue[^sday]/',
        '/Wed[^nesday]/',
        '/Thu[^rsday]/',
        '/Fri[^day]/',
        '/Sat[^urday]/',
        '/Sun[^day]/',
        '/Monday/',
        '/Tuesday/',
        '/Wednesday/',
        '/Thursday/',
        '/Friday/',
        '/Saturday/',
        '/Sunday/',
        '/Jan[^uary]/',
        '/Feb[^ruary]/',
        '/Mar[^ch]/',
        '/Apr[^il]/',
        '/May/',
        '/Jun[^e]/',
        '/Jul[^y]/',
        '/Aug[^ust]/',
        '/Sep[^tember]/',
        '/Oct[^ober]/',
        '/Nov[^ember]/',
        '/Dec[^ember]/',
        '/January/',
        '/February/',
        '/March/',
        '/April/',
        '/June/',
        '/July/',
        '/August/',
        '/September/',
        '/October/',
        '/November/',
        '/December/',
    );
    $replace = array(
        'Sen',
        'Sel',
        'Rab',
        'Kam',
        'Jum',
        'Sab',
        'Min',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Ags',
        'Sep',
        'Okt',
        'Nov',
        'Des',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date} {$suffix}";

    return $date;
}

function formatTanggal($timestamp = '', $date_format = 'F d, Y')
{

    if (!isset($timestamp)) {
        return "error: dateTime";
    }

    if (trim($timestamp) == '') {
        $timestamp = time();
    }
    elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/',
        '/Tue[^sday]/',
        '/Wed[^nesday]/',
        '/Thu[^rsday]/',
        '/Fri[^day]/',
        '/Sat[^urday]/',
        '/Sun[^day]/',
        '/Monday/',
        '/Tuesday/',
        '/Wednesday/',
        '/Thursday/',
        '/Friday/',
        '/Saturday/',
        '/Sunday/',
        '/Jan[^uary]/',
        '/Feb[^ruary]/',
        '/Mar[^ch]/',
        '/Apr[^il]/',
        '/May/',
        '/Jun[^e]/',
        '/Jul[^y]/',
        '/Aug[^ust]/',
        '/Sep[^tember]/',
        '/Oct[^ober]/',
        '/Nov[^ember]/',
        '/Dec[^ember]/',
        '/January/',
        '/February/',
        '/March/',
        '/April/',
        '/June/',
        '/July/',
        '/August/',
        '/September/',
        '/October/',
        '/November/',
        '/December/',
    );
    $replace = array(
        'Sen',
        'Sel',
        'Rab',
        'Kam',
        'Jum',
        'Sab',
        'Min',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Ags',
        'Sep',
        'Okt',
        'Nov',
        'Des',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
    $date = date($date_format, $timestamp);
    /*---------------------------
     * untuk menjadikan format indonesia hidupkan saja  $data dibawah ini untuk merelace jadi terjemahan indonesia raya
     * --------------*/
    // $date = preg_replace($pattern, $replace, $date);
    $date = "{$date}";

    return $date;
}

function formatBulan($timestamp = '', $date_format = 'F Y')
{

    if (!isset($timestamp)) {
        return "error: dateTime";
    }

    if (trim($timestamp) == '') {
        $timestamp = time();
    }
    elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/',
        '/Tue[^sday]/',
        '/Wed[^nesday]/',
        '/Thu[^rsday]/',
        '/Fri[^day]/',
        '/Sat[^urday]/',
        '/Sun[^day]/',
        '/Monday/',
        '/Tuesday/',
        '/Wednesday/',
        '/Thursday/',
        '/Friday/',
        '/Saturday/',
        '/Sunday/',
        '/Jan[^uary]/',
        '/Feb[^ruary]/',
        '/Mar[^ch]/',
        '/Apr[^il]/',
        '/May/',
        '/Jun[^e]/',
        '/Jul[^y]/',
        '/Aug[^ust]/',
        '/Sep[^tember]/',
        '/Oct[^ober]/',
        '/Nov[^ember]/',
        '/Dec[^ember]/',
        '/January/',
        '/February/',
        '/March/',
        '/April/',
        '/June/',
        '/July/',
        '/August/',
        '/September/',
        '/October/',
        '/November/',
        '/December/',
    );
    $replace = array(
        'Sen',
        'Sel',
        'Rab',
        'Kam',
        'Jum',
        'Sab',
        'Min',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Ags',
        'Sep',
        'Okt',
        'Nov',
        'Des',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
    $date = date($date_format, $timestamp);
    /*---------------------------
     * untuk menjadikan format indonesia hidupkan saja  $data dibawah ini untuk merelace jadi terjemahan indonesia raya
     * --------------*/
    // $date = preg_replace($pattern, $replace, $date);
    $date = "{$date}";

    return $date;
}

function formatTanggalInvoice($timestamp = '', $date_format = 'Y-m-d')
{

    if (!isset($timestamp)) {
        return "error: dateTime";
    }

    if (trim($timestamp) == '') {
        $timestamp = time();
    }
    elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }

    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/',
        '/Tue[^sday]/',
        '/Wed[^nesday]/',
        '/Thu[^rsday]/',
        '/Fri[^day]/',
        '/Sat[^urday]/',
        '/Sun[^day]/',
        '/Monday/',
        '/Tuesday/',
        '/Wednesday/',
        '/Thursday/',
        '/Friday/',
        '/Saturday/',
        '/Sunday/',
        '/Jan[^uary]/',
        '/Feb[^ruary]/',
        '/Mar[^ch]/',
        '/Apr[^il]/',
        '/May/',
        '/Jun[^e]/',
        '/Jul[^y]/',
        '/Aug[^ust]/',
        '/Sep[^tember]/',
        '/Oct[^ober]/',
        '/Nov[^ember]/',
        '/Dec[^ember]/',
        '/January/',
        '/February/',
        '/March/',
        '/April/',
        '/June/',
        '/July/',
        '/August/',
        '/September/',
        '/October/',
        '/November/',
        '/December/',
    );
    $replace = array(
        'Sen',
        'Sel',
        'Rab',
        'Kam',
        'Jum',
        'Sab',
        'Min',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Ags',
        'Sep',
        'Okt',
        'Nov',
        'Des',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date}";

    return $date;
}

function defTanggal($timestamp)
{
    //    echo "$timestamp";
    if ($timestamp == 0) {
        //        echo "xx";
        return "";
    }
    else {
        //        echo "yy";
        return formatTanggal($timestamp, 'Y-m-d');
    }
}

function previousDate($date_now = "")
{
    $date_now = $date_now == "" ? dtimeNow('Y-m-d') : $date_now;
    $previous = date('Y-m-d', strtotime('yesterday', strtotime($date_now)));

    return $previous;
}

function afterDate($date_now = "")
{
    $date_now = $date_now == "" ? dtimeNow('Y-m-d') : $date_now;
    $previous = date('Y-m-d', strtotime('tomorrow', strtotime($date_now)));

    return $previous;
}

function after_x_Date($date_now = "", $jml_hari)
{
    $date_now = $date_now == "" ? dtimeNow('Y-m-d') : $date_now;
    $previous = date('Y-m-d', strtotime("$jml_hari day", strtotime($date_now)));

    return $previous;
}

function previousMonth($date_now = "")
{
    $date_now = $date_now == "" ? dtimeNow('Y-m-d') : $date_now;
    $previousMonth = date('Y-m', strtotime('first day of last month', strtotime($date_now)));

    return $previousMonth;
}

function aftersMonth($date_now = "")
{
    $date_now = $date_now == "" ? dtimeNow('Y-m-d') : $date_now;
    $previousMonth = date('Y-m', strtotime('first day of +1 month', strtotime($date_now)));

    return $previousMonth;
}

function previousYear($year_now = "")
{
    $year = $year_now == "" ? date("Y") : $year_now;

    $previousYear = $year - 1;

    return $previousYear;
}

function dateDigit($date)
{
    if (strlen($date) == 1) {
        $date = "0$date";
    }

    return $date;
}

//  endregion date

//  region time
function isValidDate($postedDate)
{
    if (ereg("^[0-9]{4}-[01][0-9]-[0-3][0-9]$", $postedDate)) {
        list($year, $month, $day) = explode('-', $postedDate);

        return (checkdate($month, $day, $year));
    }
    else {
        return (false);
    }
}

function isDate($string)
{
    return strtotime($string) !== false;
}

function getDayName($tgl)
{
    $tmp = date('l', strtotime($tgl));

    //return $arrNamaHari[$tmp];
    return $tmp;
}

function hoursToMinutes($hours)
{
    if (strstr($hours, ':')) {
        # Split hours and minutes.
        $separatedData = split(':', $hours);

        $minutesInHours = $separatedData[0] * 60;
        $minutesInDecimals = $separatedData[1];

        $totalMinutes = $minutesInHours + $minutesInDecimals;
    }
    else {
        $totalMinutes = $hours * 60;
    }

    return $totalMinutes;
}

function minutesToHours($minutes)
{
    $hours = floor($minutes / 60);
    $decimalMinutes = $minutes - floor($minutes / 60) * 60;

    # Put it together.
    $hoursMinutes = sprintf("%d:%02.0f", $hours, $decimalMinutes);

    return $hoursMinutes;
}

function getDayDifference($tgl1, $tgl2)
{
    $day_diff = (strtotime($tgl2) - strtotime($tgl1)) / 86400; // dias de cartelera

    return $day_diff;
}

function getTimeDifference($start, $end)
{
    $day_diff = getDayDifference($start, $end);
    if ($day_diff > 0) {

    }
    else {

    }
}

/**
 * Function to calculate date or time difference.
 * Function to calculate date or time difference. Returns an array or
 * false on error.
 * @param string $start
 * @param string $end
 * @return       array
 * @author       J de Silva                             <giddomains@gmail.phpom>
 * @copyright    Copyright &copy; 2005, J de Silva
 * @link         http://www.gidnetwork.phpom/b-16.html    Get the date / time difference with PHP
 */
function get_time_difference($start, $end)
{
    $uts['start'] = strtotime($start);
    $uts['end'] = strtotime($end);
    if ($uts['start'] !== -1 && $uts['end'] !== -1) {
        if ($uts['end'] >= $uts['start']) {
            $diff = $uts['end'] - $uts['start'];
            if ($days = intval((floor($diff / 86400)))) {
                $diff = $diff % 86400;
            }
            if ($hours = intval((floor($diff / 3600)))) {
                $diff = $diff % 3600;
            }
            if ($minutes = intval((floor($diff / 60)))) {
                $diff = $diff % 60;
            }
            $diff = intval($diff);

            return (array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $diff));
        }
        else {
            trigger_error("Ending date/time is earlier than the start date/time", E_USER_WARNING);
        }
    }
    else {
        trigger_error("Invalid date/time data detected", E_USER_WARNING);
    }

    return (false);
}

function timeDiff($firstTime, $lastTime)
{

    // convert to unix timestamps
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);

    // perform subtraction to get the difference (in seconds) between times
    $timeDiff = $lastTime - $firstTime;

    // return the difference
    return $timeDiff;
}

function createTimeDescription($time)
{
    $dulu = $time;
    $sekarang = date("Y-m-d H:i:s");
    $jeda = timeDiff($dulu, $sekarang);
    $strJam = substr($dulu, 11, 8);

    $jedaMenit = intval($jeda / 60);
    $jedaJam = intval($jeda / 3600);
    $jedaHari = intval($jeda / 86400);
    $jedaBulan = intval($jeda / 2592000);
    $jedaTahun = intval($jeda / 31104000);
    if ($jedaMenit < 60) {
        if ($jedaMenit > 1) {
            $time = "$jedaMenit menit yang lalu ";
        }
        else {
            $time = "semenit yang lalu ";
        }
    }
    elseif ($jedaJam < 24) {
        if ($jedaJam > 1) {
            $time = "$jedaJam jam yang lalu ";
        }
        else {
            $time = "sejam yang lalu ";
        }

    }
    elseif ($jedaHari < 30) {
        if ($jedaHari > 1) {
            $time = "$jedaHari hari yang lalu, $strJam ";
        }
        else {
            $time = "kemarin, $strJam ";
        }
    }
    elseif ($jedaBulan < 12) {
        if ($jedaBulan > 1) {
            $time = "$jedaBulan bulan yang lalu, $strJam ";
        }
        else {
            $time = "sebulan yang lalu, $strJam ";
        }

    }
    elseif ($jedaTahun < 100) {
        if ($jedaTahun > 1) {
            $time = "$jedaTahun tahun yang lalu, $strJam ";
        }
        else {
            $time = "setahun yang lalu, $strJam ";
        }

        //$time="long time ago";
    }

    return $time;
}

function createTimeDescSoon($time)
{
    $dulu = $time;
    $sekarang = date("Y-m-d H:i:s");
    $jeda = timeDiff($sekarang, $dulu);
    $jeda_min = timeDiff($dulu, $sekarang);
    $strJam = substr($dulu, 11, 8);

    $jedaMenit = intval($jeda / 60);
    $jedaJam = intval($jeda / 3600);
    $jedaHari = intval($jeda / 86400);
    $jedaBulan = intval($jeda / 2592000);
    $jedaTahun = intval($jeda / 31104000);

    if ($jeda <= 0) {
        if ($jedaMenit < 60) {
            if ($jedaMenit > 1) {
                $time = "expired $jedaMenit menit yang lalu ";
            }
            else {
                $time = "expired semenit yang lalu ";
            }
        }
        else {
            if ($jedaJam < 24) {
                if ($jedaJam > 1) {
                    $time = "expired $jedaJam jam yang lalu ";
                }
                else {
                    $time = "expired sejam yang lalu ";
                }
            }
            else {
                if ($jedaHari < 30) {
                    if ($jedaHari > 1) {
                        $time = "expired $jedaHari hari yang lalu ";
                    }
                    else {
                        $time = "expired kemarin ";
                    }
                }
                else {
                    if ($jedaBulan < 12) {
                        if ($jedaBulan > 1) {
                            $time = "expired $jedaBulan bulan yang lalu, $strJam ";
                        }
                        else {
                            $time = "expired sebulan yang lalu, $strJam ";
                        }
                    }
                    else {
                        if ($jedaTahun < 100) {
                            if ($jedaTahun > 1) {
                                $time = "expired $jedaTahun tahun yang lalu, $strJam ";
                            }
                            else {
                                $time = "expired setahun yang lalu, $strJam ";
                            }
                        }
                    }
                }
            }
        }
    }

    //jika $jeda > 0
    else {
        if ($jedaMenit < 60) {
            if ($jedaMenit > 1) {
                $time = "$jedaMenit menit lagi ";
            }
            else {
                $time = "semenit lagi ";
            }
        }
        else {
            if ($jedaJam < 24) {
                if ($jedaJam > 1) {
                    $time = "$jedaJam jam lagi ";
                }
                else {
                    $time = "sejam lagi ";
                }
            }
            else {
                if ($jedaHari < 30) {
                    if ($jedaHari > 1) {
                        $time = "$jedaHari hari lagi ";
                    }
                    else {
                        $time = "kemarin ";
                    }
                }
                else {
                    if ($jedaBulan < 12) {
                        if ($jedaBulan > 1) {
                            $time = "$jedaBulan bulan lagi ";
//                $jedaMenit = intval($jeda/60);
//                $jedaJam   = intval($jeda/3600);
//                $jedaHari  = intval($jeda/86400);
//                $jedaBulan = intval($jeda/2592000);
//                $jedaTahun = intval($jeda/31104000);

//                $time = "$dulu<br>
//                T:$jedaTahun|<br>
//                B:$jedaBulan|<br>
//                H:$jedaHari|<br>
//                J:$jedaJam|<br>
//                M:$jedaMenit<br>";
                        }
                        else {
                            $time = "sebulan lagi ";
                        }
                    }
                    else {
                        if ($jedaTahun < 100) {
                            if ($jedaTahun > 1) {
                                $time = "$jedaTahun tahun lagi ";
                            }
                            else {
                                $time = "setahun lagi ";
                            }
                        }
                    }
                }
            }
        }
    }

//    $time .= "#$jeda#";

    return $time;
}

function reverseDate($tanggal)
{
    $arr = explode("-", $tanggal);
    $arr = array_reverse($arr);
    $strTanggal = implode($arr, "-");

    return $strTanggal;
}

function translateDate($tanggal)
{
    global $arrBln;
    $arr = explode("-", $tanggal);
    $tmp = $arr[1];
    $arr[1] = $arrBln[$tmp];
    $strTanggal = implode($arr, "-");

    return $strTanggal;
}

function translateMonthYear($month)
{
    global $arrBln;
    $arr = explode("-", $month);

    //$strTanggal = implode($arr, "-");
    $strTanggal = $arrBln[$arr[1]] . " " . $arr[0];

    return $strTanggal;
}


function getPrevMonthString($thn, $bln)
{
    return date('Y-m', mktime(1, 1, 1, $bln - 1, 1, $thn));
}

function getPrevMonthPair($thn, $bln)
{
    //return date('Y-m', mktime(1, 1, 1, $bln-1, 1, $thn));
    $thnR = date('Y', mktime(1, 1, 1, $bln - 1, 1, $thn));
    $blnR = date('m', mktime(1, 1, 1, $bln - 1, 1, $thn));

    return array('thn' => $thnR, 'bln' => $blnR);
}

function getTempo($s, $format = "date")
{
    $today_Y = date("Y"); // tahun 2012
    $today_n = date("n"); // bulan 1-31
    $today_j = date("j"); // tanggal 1-31
    $today_H = date("H"); // jam 00-23
    $today_i = date("i"); // menit 00-5
    $today_s = date("s"); // second 00-59

    $now_mkt = mktime($today_H, $today_i, $today_s, $today_n, $today_j, $today_Y);
    $dateTempo = $now_mkt + $s;
    if ($format == "date") {

        $tempo = date('Y-m-d H:i:s', $dateTempo);
    }
    else {
        $tempo = $dateTempo;
    }

    return $tempo;

}

function dtimeToSecond($dateTime)
{
    $date_time_jm = date('H', strtotime($dateTime));
    $date_time_mn = date('i', strtotime($dateTime));
    $date_time_dt = date('s', strtotime($dateTime));
    $date_time_bl = date('n', strtotime($dateTime));
    $date_time_hr = date('j', strtotime($dateTime));
    $date_time_th = date('Y', strtotime($dateTime));

    //    cekHere("$dateTime :: $date_time_jm, $date_time_mn, $date_time_dt, $date_time_bl, $date_time_hr, $date_time_th");

    $orders_dtime_mkt = mktime($date_time_jm, $date_time_mn, $date_time_dt, $date_time_bl, $date_time_hr, $date_time_th);

    //    return $orders_dtime_mkt;
    return $orders_dtime_mkt;
}

function dayToSecond($jml_hari)
{
    $jam = $jml_hari * 24;
    $menit = $jam * 60;
    $detik = $menit * 60;

    return $detik;
}

function backDate($jml_d)
{
    $s_now = dtimeToSecond(dtimeNow());
    $s_jml_d = dayToSecond($jml_d);
    $s_back = $s_now - $s_jml_d;

    return date("Y-m-d H:i:s", $s_back);
}

function secondToDay($jml_detik)
{
    $menit = $jml_detik / 60;
    $jam = $menit / 60;
    $hari = $jam / 24;

    return floor($hari);
}

function jatuhTempo($dateTime, $tempo)
{

    $date_time_jm = date('H', strtotime($dateTime));
    $date_time_mn = date('i', strtotime($dateTime));
    $date_time_dt = date('s', strtotime($dateTime));
    $date_time_bl = date('n', strtotime($dateTime));
    $date_time_hr = date('j', strtotime($dateTime));
    $date_time_th = date('Y', strtotime($dateTime));

    $orders_dtime_mkt = mktime($date_time_jm, $date_time_mn, $date_time_dt, $date_time_bl, $date_time_hr, $date_time_th);
    $akan_jatuh_tempo = $orders_dtime_mkt + $tempo;

    return $akan_jatuh_tempo;
}

function umurHour($dateTime, $format = "H")
{
    $hh = strtolower($format);
    $today_Y = date("Y"); // tahun 2012
    $today_n = date("n"); // bulan 1-31
    $today_j = date("j"); // tanggal 1-31
    $today_H = date("H"); // jam 00-23
    $today_i = date("i"); // menit 00-5
    $today_s = date("s"); // second 00-59

    $date_time_jm = date('H', strtotime($dateTime));
    $date_time_mn = date('i', strtotime($dateTime));
    $date_time_dt = date('s', strtotime($dateTime));
    $date_time_bl = date('n', strtotime($dateTime));
    $date_time_hr = date('j', strtotime($dateTime));
    $date_time_th = date('Y', strtotime($dateTime));

    $orders_dtime_mkt = mktime($date_time_jm, $date_time_mn, $date_time_dt, $date_time_bl, $date_time_hr, $date_time_th);

    $now_mkt = mktime($today_H, $today_i, $today_s, $today_n, $today_j, $today_Y);
    $umur_dt = $now_mkt - $orders_dtime_mkt; // detik

    // $umur_dt = floor($umur_dt % 3600 / 60); // menit
    $umur_mn = floor($umur_dt / 60); // menit

    $umur_jm = floor($umur_dt / 3600); // jam
    $umur_jm_sisa = floor($umur_dt % 3600 / 60); // jam

    if ($hh == "h") {
        return $umur_jm;
    }
    elseif ($hh == "i") {
        return $umur_mn;
    }
    elseif ($hh == "s") {
        return $umur_dt;
    }
    elseif ($hh == "H:i") {
        $jam = "$umur_jm:$umur_jm_sisa";
        return $jam;
    }
}

function umurDay($dateTime)
{
    $umurDmlJam = umurHour($dateTime);

    $umurDmlHari = floor($umurDmlJam / 24);

    return $umurDmlHari;
}

function wibTowit($dtime)
{
    //    $original_datetime = '04/01/2013 03:08 PM';
    $original_datetime = $dtime;
    $original_timezone = new DateTimeZone('Asia/Bangkok');

    // Instantiate the DateTime object, setting it's date, time and time zone.
    $datetime = new DateTime($original_datetime, $original_timezone);

    // Set the DateTime object's time zone to convert the time appropriately.
    $target_timezone = new DateTimeZone('Asia/Hong_Kong');
    $datetime->setTimeZone($target_timezone);

    // Outputs a date/time string based on the time zone you've set on the object.
    $triggerOn = $datetime->format('Y-m-d H:i:s');

    return $triggerOn;
}

function umurRelatif($datetime) {
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->getTimestamp() - $past->getTimestamp();

    if ($diff < 60) {
        return $diff . " detik";
    }

    if ($diff < 3600) {
        return floor($diff / 60) . " menit";
    }

    if ($diff < 86400) {
        return floor($diff / 3600) . " jam";
    }

    if ($diff < 2629800) { // 30 hari
        return floor($diff / 86400) . " hari";
    }

    if ($diff < 31557600) { // 12 bulan
        return floor($diff / 2629800) . " bulan";
    }

    return floor($diff / 31557600) . " tahun";
}

function btn_harian()
{
    global $Mode, $mm, $mn, $smo, $cm, $date_now_use, $date_now, $tempo, $oId;

    $time_now = dtimeToSecond($date_now_use);
    $time = $time_now;
    $tempo_s = dayToSecond($tempo);
    $date_awal = getTempo(-$tempo_s, "date");
    $date_sebelum = date("Y-m-d", mktime(0, 0, 0, date("n", $time), date("j", $time) - 1, date("Y", $time)));
    $date_sesudah = date("Y-m-d", mktime(0, 0, 0, date("n", $time), date("j", $time) + 1, date("Y", $time)));
    $disabled = $date_sesudah >= $date_now ? "disabled" : "";

    //    $strPilih_tanggal_2 = "<link rel=\"stylesheet\" href='../../assets/suport/bootstrap-datepicker-1.5.0-dist/css/bootstrap-datepicker.css'>";
    //    $strPilih_tanggal_2 .= "<script src='../../assets/suport/autocomplete/jquery-1.8.2.min.js'></script>";
    //    $strPilih_tanggal_2 .= "<script src='../../assets/suport/bootstrap-datepicker-1.5.0-dist/js/bootstrap-datepicker.js'></script>";
    //
    //    $strPilih_tanggal_2 .= "<script type=\"text/javascript\">
    //                    // When the document is ready
    //                    $(document).ready(function () {
    //
    //                    $('#example1').datepicker({
    //                            format: \"yyyy/mm/dd\"
    //                    });
    //
    //                });
    //            </script>";

    $strPer_hari = "$strPilih_tanggal_2 <div class='btn-group btn-group-justified'>";
    $strPer_hari .= "<a href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sebelum' class='btn btn-primary'><span class='glyphicon glyphicon-menu-left'></span> Sebelum $date_sebelum</a>";
    $strPer_hari .= "<a href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_now' class='btn btn-info'>Hari ini $date_now</a>";
    //    $strPer_hari .= "<input  type='text' placeholder='pilih tanggal' class='btn btn-warning' name='req_dtime' value='$reg_dtime' id='example1'>";
    //    $strPer_hari .= "<a href='#' class='btn btn-success' id='example1'><span class='glyphicon glyphicon-calendar'>&nbsp;</span>jump</a>";
    $strPer_hari .= "<a $disabled href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sesudah' class='btn btn-primary'>Sesudah $date_sesudah <span class='glyphicon glyphicon-menu-right'></span></a>";
    $strPer_hari .= "</div>";

    return $strPer_hari;
}

function btn_bulanan()
{
    global $Mode, $mm, $mn, $smo, $cm, $date_now_use, $date_now, $date_bl, $oId;


    //                $time = time();
    $time_now = dtimeToSecond($date_now_use);
    $time = $time_now;

    $date_sebelum = date("Y-m", mktime(0, 0, 0, date("n", $time) - 1, date("j", $time), date("Y", $time)));
    $date_sesudah = date("Y-m", mktime(0, 0, 0, date("n", $time) + 1, date("j", $time), date("Y", $time)));
    $disabled = $date_now_use >= $date_bl ? "disabled" : "";
    $strPer_hari = "<div class='btn-group btn-group-justified'>";
    $strPer_hari .= "<a href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sebelum' class='btn btn-primary'><span class='glyphicon glyphicon-menu-left'></span> Sebelum $date_sebelum</a>";
    $strPer_hari .= "<a $disabled href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_bl' class='btn btn-success'>Bulan ini $date_bl</a>";
    $strPer_hari .= "<a $disabled href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sesudah' class='btn btn-primary'>Sesudah $date_sesudah <span class='glyphicon glyphicon-menu-right'></span></a>";
    $strPer_hari .= "</div>";

    return $strPer_hari;
}

function btn_tahunan()
{
    global $Mode, $mm, $mn, $smo, $cm, $date_now_use, $date_now, $date_bl, $oId;

    $time_now = dtimeToSecond($date_now_use);
    $time = $time_now;
    $date_th = date("Y");

    $date_sebelum = date("Y-m", mktime(0, 0, 0, date("n", $time), date("j", $time), date("Y", $time) - 1));
    $date_sesudah = date("Y-m", mktime(0, 0, 0, date("n", $time), date("j", $time), date("Y", $time) + 1));
    $date_sebelum_f = date("Y", mktime(0, 0, 0, date("n", $time), date("j", $time), date("Y", $time) - 1));
    $date_sesudah_f = date("Y", mktime(0, 0, 0, date("n", $time), date("j", $time), date("Y", $time) + 1));

    //cekHere("$date_sebelum_f // $date_sesudah_f [$date_now_use ** $date_bl]");

    $disabled = $date_now_use >= $date_bl ? "disabled" : "";
    $strPer_hari = "<div class='btn-group btn-group-justified'>";
    $strPer_hari .= "<a href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sebelum' class='btn btn-primary'><span class='glyphicon glyphicon-menu-left'></span> Sebelum $date_sebelum_f</a>";
    $strPer_hari .= "<a $disabled href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_bl' class='btn btn-success'>Tahun ini $date_th</a>";
    $strPer_hari .= "<a $disabled href='$_SERVER[PHP_SELF]?Mode=$Mode$cm$mm$smo$mn$oId&req_dtime=$date_sesudah' class='btn btn-primary'>Sesudah $date_sesudah_f <span class='glyphicon glyphicon-menu-right'></span></a>";
    $strPer_hari .= "</div>";

    return $strPer_hari;
}

//  endregion time

function microSecond()
{
    return microtime(true);
}

function stopTime($time)
{
    return microSecond() - $time;
}

function backCustomDate($jml_day, $dtime)
{
    $s_now = dtimeToSecond($dtime);
    $s_jml_d = dayToSecond($jml_day);
    $s_back = $s_now - $s_jml_d;

    return date("Y-m-d", $s_back);
}

function backCustomMonths($date, $jml_bulan)
{
//    $nextMonth = date('Y-m', strtotime('1 month'));
//    mati_disini(":: $nextMonth ::");

    $date_now = strlen($date) > 4 ? $date : date("Y-m");// minimal format (tahun-bulan)
    $arrMonths = array();
    for ($i = 1; $i <= $jml_bulan; $i++) {
        $tes = $i * -1;
        $previousMonth = date('Y-m', strtotime($tes . ' month'));
        $arrMonths[] = $previousMonth;

    }
    krsort($arrMonths);

    return $arrMonths;
}

function backCustomThisMonths($date, $jml_bulan)
{
//    $nextMonth = date('Y-m', strtotime('1 month'));
//    mati_disini(":: $nextMonth ::");

    $date_now = strlen($date) > 4 ? $date : date("Y-m");// minimal format (tahun-bulan)
    $arrMonths = array();
    for ($i = 0; $i <= $jml_bulan; $i++) {
        $tes = $i * -1;
        $previousMonth = date('Y-m', strtotime($tes . ' month'));
        $arrMonths[] = $previousMonth;

    }
    krsort($arrMonths);

    return $arrMonths;
}

function periodeAging()
{
    $temp = array(
        "3" => array(
            "label" => "1 - 3",
            "member" => array(0, 1, 2, 3),
        ),
        "7" => array(
            "label" => "4 - 7",
//            "member"=>array(4,5,6,7),
            "member" => array(4, 5, 6, 7),
        ),
        "30" => array(
            "label" => "8 - 30",
            "member" => array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
        ),

//        "30"=>array(
//            "label"=>"1 - 30",
//            "member"=>array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30),
//        ),

        "60" => array(
            "label" => "31 - 60",
            "member" => array(31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60),
        ),
        "90" => array(
            "label" => "61 - 90",
            "member" => array(61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90),
        ),
        "120" => array(
            "label" => " > 91",
            "member" => array(91),
        ),
    );
    return $temp;

}

?>