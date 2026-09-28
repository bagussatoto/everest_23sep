<?php
/**
 * Created by PhpStorm.
 * User: jasmanto
 * Date: 24/08/2018
 * Time: 10.49
 */


function formatAngka($angka, $lang = 0)
{

//    $angka = empty($angka) ? $angka : 0;
    if ($angka == "") {
        $angkaku = 0;
    }
    else {
        $desi = explode(".", $angka);

        if (sizeof($desi) > 0) {

            if (((int)$desi[1]) == 0) {
                $jml_desi = 1;
            }
            else {

                $jml_desi = count($desi) + 1;
            }
        }

        $tampil_desi = $jml_desi > 2 ? 2 : $jml_desi - 1;
        if ($lang == 0) {
            $angkaku = number_format($angka, "$tampil_desi", ",", ".");
        }
        else {
            $angkaku = number_format($angka, "$tampil_desi", ".", "");
        }
    }


    return $angkaku;
}

function formatAngkaDesimal($angka, $desimal = 2)
{
    $angkaku = number_format($angka, "$desimal", ",", ".");
    return $angkaku;
}

if (!function_exists('kekata')) {
    // cekHere("kosong");
    function kekata($x)
    {
        $x = abs($x);
        $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($x < 12) {
            $temp = " " . $angka[$x];
        }
        else {
            if ($x < 20) {
                $temp = kekata($x - 10) . " belas";
            }
            else {
                if ($x < 100) {
                    $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
                }
                else {
                    if ($x < 200) {
                        $temp = " seratus" . kekata($x - 100);
                    }
                    else {
                        if ($x < 1000) {
                            $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
                        }
                        else {
                            if ($x < 2000) {
                                $temp = " seribu" . kekata($x - 1000);
                            }
                            else {
                                if ($x < 1000000) {
                                    $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
                                }
                                else {
                                    if ($x < 1000000000) {
                                        $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
                                    }
                                    else {
                                        if ($x < 1000000000000) {
                                            $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
                                        }
                                        else {
                                            if ($x < 1000000000000000) {
                                                $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $temp;
    }
}
else {
    // cekHere("ada");
}
function kekata__($x)
{
    $x = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    }
    else {
        if ($x < 20) {
            $temp = kekata($x - 10) . " belas";
        }
        else {
            if ($x < 100) {
                $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
            }
            else {
                if ($x < 200) {
                    $temp = " seratus" . kekata($x - 100);
                }
                else {
                    if ($x < 1000) {
                        $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
                    }
                    else {
                        if ($x < 2000) {
                            $temp = " seribu" . kekata($x - 1000);
                        }
                        else {
                            if ($x < 1000000) {
                                $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
                            }
                            else {
                                if ($x < 1000000000) {
                                    $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
                                }
                                else {
                                    if ($x < 1000000000000) {
                                        $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
                                    }
                                    else {
                                        if ($x < 1000000000000000) {
                                            $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $temp;
}

function kekataEng($x)
{
    $x = abs($x);
    $angka = array(
        "",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan",
        "sepuluh",
        "sebelas",
    );
    $temp = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    }
    else {
        if ($x < 20) {
            $temp = kekata($x - 10) . " belas";
        }
        else {
            if ($x < 100) {
                $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
            }
            else {
                if ($x < 200) {
                    $temp = " seratus" . kekata($x - 100);
                }
                else {
                    if ($x < 1000) {
                        $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
                    }
                    else {
                        if ($x < 2000) {
                            $temp = " seribu" . kekata($x - 1000);
                        }
                        else {
                            if ($x < 1000000) {
                                $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
                            }
                            else {
                                if ($x < 1000000000) {
                                    $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
                                }
                                else {
                                    if ($x < 1000000000000) {
                                        $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
                                    }
                                    else {
                                        if ($x < 1000000000000000) {
                                            $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return $temp;
}

function tkoma__($x)
{
//    echo "$x";
    $str = stristr($x, ".");
    $ex = explode('.', $x);
//print_r($ex);
    if (($ex[1] / 10) >= 1) {
        $a = abs($ex[1]);
    }

    $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";

    $a2 = $ex[1] / 10;
    $pjg = strlen($str);
    $i = 1;


    if ($a >= 1 && $a < 12) {
        $temp .= " " . $string[$a];
    }
    else {
        if ($a > 12 && $a < 20) {
            $temp .= kekata($a - 10) . " belas";
        }
        else {
            if ($a > 20 && $a < 100) {
                $temp .= kekata($a / 10) . " puluh" . kekata($a % 10);
            }
            else {
                if ($a2 < 1) {

                    while ($i < $pjg) {
                        $char = substr($str, $i, 1);
                        $i++;
                        $temp .= " " . $string[$char];
                    }
                }
            }
        }
    }
    return $temp;
}

function tkomaEng($x)
{
    //    echo "$x";
    $str = stristr($x, ".");
    $ex = explode('.', $x);
    //print_r($ex);
    if (($ex[1] / 10) >= 1) {
        $a = abs($ex[1]);
    }

    $string = array(
        "nol",
        "satu",
        "dua",
        "tiga",
        "empat",
        "lima",
        "enam",
        "tujuh",
        "delapan",
        "sembilan",
        "sepuluh",
        "sebelas",
    );
    $temp = "";

    $a2 = $ex[1] / 10;
    $pjg = strlen($str);
    $i = 1;


    if ($a >= 1 && $a < 12) {
        $temp .= " " . $string[$a];
    }
    else {
        if ($a > 12 && $a < 20) {
            $temp .= kekata($a - 10) . " belas";
        }
        else {
            if ($a > 20 && $a < 100) {
                $temp .= kekata($a / 10) . " puluh" . kekata($a % 10);
            }
            else {
                if ($a2 < 1) {

                    while ($i < $pjg) {
                        $char = substr($str, $i, 1);
                        $i++;
                        $temp .= " " . $string[$char];
                    }
                }
            }
        }
    }

    return $temp;
}

function terbilang($x, $style = 4)
{
    if ($x < 0) {
        $hasil = "minus " . trim(kekata($x));
    }
    else {
        $poin = trim(tkoma($x));
        $hasil = trim(kekata($x));
    }
//$poin = "99";
    if ($poin) {
        $hasil = $hasil . " koma " . $poin . " rupiah";
    }
    else {
        $hasil = $hasil . " rupiah";
    }

    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }
    return $hasil;
}

function terbilang2($x, $style = 4)
{
    if ($x < 0) {
        $hasil = "minus " . trim(kekata($x));
    }
    else {
        $hasil = trim(kekata($x)) . " rupiah";
    }
    switch ($style) {
        case 1:
            $hasil = strtoupper($hasil);
            break;
        case 2:
            $hasil = strtolower($hasil);
            break;
        case 3:
            $hasil = ucwords($hasil);
            break;
        default:
            $hasil = ucfirst($hasil);
            break;
    }
    /*
     * strtolower()
     * 1=uppercase,
     * 2= lowercase,
     * 3= uppercase untuk huruf pertama tiap kata
     * 4=uppercase untuk huruf pertama
     */
    return $hasil;
}

function convert_number_to_words($number, $style = 4)
{
    $hyphen = '-';
    $conjunctions = ' ';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion',
    );
    $number = number_format($number, 2, '.', '');

    if (!is_numeric($number)) {
        return false;
    }
    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);

        return false;
    }
    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    $string = $fraction = null;
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    if (null !== $fraction && is_numeric($fraction)) {
        if ($fraction > 0) { // membuabg output point zero-zero

            $string .= $decimal;
            $words = array();

            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

    }

    switch ($style) {
        case 1:
            $string = strtoupper($string);
            break;
        case 2:
            $string = strtolower($string);
            break;
        case 3:
            $string = ucwords($string);
            break;
        default:
            $string = ucfirst($string);
            break;
    }

    return $string;
}

function unFormatAngka($angka)
{
    $unf = str_replace(",", "", $angka);
    $unf = str_replace(",", ".", $unf);
    return $unf;
}

//function formatNumbering($numbering, $arrKomponen)
//{
////    $arrKomponen = array($arrKomponen);
//    $explNumb = explode("-", $numbering);
//    foreach ($arrKomponen as $v) {
//
//        if ($hasil == "") {
//
//            $hasil = $explNumb[$v];
//        }
//        else {
//
//            $hasil = "$hasil-" . $explNumb[$v];
//        }
//
//    }
//    $numbering_f = $hasil;
//
//
//    return $numbering_f;
//}

function fileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    $result = "";
    foreach ($arBytes as $arItem) {
        if ($bytes >= $arItem["VALUE"]) {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function pembulatanInt($angka)
{

    $var = round($angka, 0);

    return $var;
}


function convTime($fr, $to, $val)
{
    // $arrMast = array(
    //     "Y" => 0.0001140771,
    //     "M" => 0.0013689254,
    //     "w" => 0.005952381,
    //     "D" => 0.0416666667,
    //     "H" => 1,
    //     "i" => 60,
    //     "s" => 3600,
    //     "ms" => 3600000,
    //     "us" => 3600000000,
    // );

    // $arrMast = array(
    //     "Y" => 0.0027378508,
    //     "M" => 0.0328542094,
    //     "w" => 0.1428571429,
    //     "D" => 1,
    //     "H" => 24,
    //     "i" => 1440,
    //     "s" => 86400,
    //     "ms" => 86400000,
    //     "us" => 86400000000,
    // );

    $arrMast = array(
        "Y" => 0.0191649555,
        "M" => 0.2299794661,
        "w" => 1,
        "D" => 7,
        "H" => 168,
        "i" => 10080,
        "s" => 604800,
        "ms" => 604800000,
        "us" => 604800000000,
    );

    $mast = $arrMast[$fr];
    $slave = $arrMast[$to];

    $var = $slave / $mast;

    $var_c = $var * $val;

    // cekHijau("val: $val dr($fr): $mast ==== jd($to): $slave ===== $var_c");
    return $var_c;

}

function convVolume($fr, $to, $val)
{
    $arrMast = array(
        "km" => 0.000000000001,
        "hm" => 0.000000001,
        "dam" => 0.000001,
        "m" => 0.001,
        "dm" => 1,
        // "in" => 39.37007874,
        "cm" => 1000,
        "mm" => 1000000,
    );

    $mast = $arrMast[$fr];
    $slave = $arrMast[$to];

    $var = $slave / $mast;

    $var_c = $var * $val;

    // cekHijau("val: $val dr($fr): $mast ==== jd($to): $slave ===== $var_c");
    return $var_c;

}

function convDistance($fr, $to, $val)
{
    $arrMast = array(
        "km" => 0.001,
        "hm" => 0.01,
        "dam" => 0.1,
        "m" => 1,
        "dm" => 10,
        "in" => 39.37007874,
        "cm" => 100,
        "mm" => 1000,
    );

    $mast = $arrMast[$fr];
    $slave = $arrMast[$to];

    $var = $slave / $mast;

    $var_c = $var * $val;

    // cekHijau("val: $val dr($fr): $mast ==== jd($to): $slave ===== $var_c");
    return $var_c;

}

function convWeight($fr, $to, $val)
{
    $arrMast = array(
        "kg" => 0.001,
        "hg" => 0.01,
        "dag" => 0.1,
        "g" => 1,
        "dg" => 10,
        "cg" => 100,
        "mg" => 1000,
    );

    $mast = $arrMast[$fr];
    $slave = $arrMast[$to];

    $var = $slave / $mast;

    $var_c = $var * $val;

    // cekHijau("val: $val dr($fr): $mast ==== jd($to): $slave ===== $var_c");
    return $var_c;

}

function conv_mm_m($nilai)
{
    $var = convDistance("mm", "m", $nilai);

    return $var;
}

function conv_g_kg($nilai)
{
    $var = convWeight("g", "kg", $nilai);

    return $var;
}

function conv_mmc_dmc($nilai)
{
    $var = convVolume("mm", "dm", $nilai);

    return $var;
}

function conv_mmc_mc($nilai)
{
    $var = convVolume("mm", "m", $nilai);

    return $var;
}

function mround($angka, $pengali)
{
    return round($angka / $pengali, 0) * $pengali;

}

// region start pembulatan bilangan
// harus ada key untuk memanggil hasilnya
// $angka_hasil = pembulatan_puluhan($angka)["hasil"];
function membilang($angka)
{
    // cekMErah($angka);
    $expTransaksi_net = explode(".", $angka);
    $integer = $expTransaksi_net[0];
    $desimal = isset($expTransaksi_net[1]) ? "0." . $expTransaksi_net[1] : "";

    $satuan = substr($integer, -1);
    $puluhan = substr($integer, -2);
    $ribuan = substr($integer, -3);
    // arrPrintWebs($expTransaksi_net);
    $var['int'] = $integer * 1;
    $var['satuan'] = $satuan * 1;
    $var['puluhan'] = $puluhan * 1;
    $var['ribuan'] = $ribuan * 1;
    $var['desimal'] = $desimal * 1;

    return $var;
}

function pembulatan_koma($angka)
{
    $arr = membilang($angka);
    // arrPrint($arr);
    $pecahan = $arr['desimal'];
    if ($pecahan >= 0.5) {
        $faktor = (1 - $pecahan);
        $pembulatan = $angka + $faktor;
    }
    else {
        $faktor = -$pecahan;
        $pembulatan = $angka + $faktor;
    }

    // cekHijau("$pembulatan = 10 - $pecahan");

    $var["pecahan"] = $pecahan;
    $var["faktor"] = $faktor;
    $var["hasil"] = $pembulatan;

    return $var;
}

function pembulatan_puluhan($angka)
{
    $arr = membilang($angka);
//    cekHere(":: pembulatan puluhan ::");
//    arrPrint($arr);

    $pecahan = $arr['satuan'] + $arr['desimal'];
    // $pecahan = 0;


    if ($pecahan >= 5) {
        $faktor = (10 - $pecahan);
        $pembulatan = $angka + $faktor;
    }
    else {
        $faktor = -$pecahan;
        $pembulatan = $angka + $faktor;
    }


    $var["pecahan"] = $pecahan;
    $var["faktor"] = $faktor;
    $var["hasil"] = $pembulatan;

    return $var;
}

function pembulatan_ratusan($angka)
{
    $arr = membilang($angka);
    // arrPrint($arr);
    $pecahan = $arr['puluhan'] + $arr['desimal'];
    if ($pecahan >= 50) {
        $faktor = (100 - $pecahan);
        $pembulatan = $angka + $faktor;
    }
    else {
        $faktor = -$pecahan;
        $pembulatan = $angka + $faktor;
    }

    // cekHijau("$pembulatan = 10 - $pecahan");

    $var["pecahan"] = $pecahan;
    $var["faktor"] = $faktor;
    $var["hasil"] = $pembulatan;

    return $var;
}

function pembulatan_ribuan($angka)
{
    $arr = membilang($angka);
    // arrPrint($arr);
    $pecahan = $arr['ribuan'] + $arr['desimal'];
    if ($pecahan >= 500) {
        $faktor = (1000 - $pecahan);
        $pembulatan = $angka + $faktor;
    }
    else {
        $faktor = -$pecahan;
        $pembulatan = $angka + $faktor;
    }

    // cekHijau("$pembulatan = 1000 - $pecahan");

    $var["pecahan"] = $pecahan;
    $var["faktor"] = $faktor;
    $var["hasil"] = $pembulatan;

    return $var;
}


//diround down ke 0 contoh 219 --->210,211->210,235->230 dioff kan karena ganti ke ppn 11 persen
// function pembulatan_pajak($angka, $ppnFactor)
// {
//     cekHitam($angka);
//     $arr = membilang($angka);
//     $pecahan = $arr['satuan'] + $arr['desimal'];
//
//     $faktor = $pecahan;
//     $pembulatan = $angka - $faktor;
//     $ppn = $pembulatan * $ppnFactor / 100;
//     $grandtotal = $angka + $ppn;
//     $var["pecahan"] = $pecahan;
//     $var["faktor"] = $faktor;
//     $var["hasil"] = $pembulatan;
//     $var['ppn'] = $ppn;
//     $var['dppPpn'] = $pembulatan;
//     $var['grandTotal'] = $grandtotal;
//
//
//     return $var;
// }

//ppn versi baru 11% dpp dibulatkan keatas, ppn dibuang komanya
function pembulatan_pajak($angka, $ppnFactor)//dpp,ppnfactor
{
    /*
     * NOTES
     * $angka =dpp
     * $ppnFactor = % tarif ppn
     * $pakaiini
     * 1 = apaadanya tidak dibulatkan
     * 0 = hasilnya jadi bulat
     */
    cekMErah($angka);

    $pakaiini = 1;
    if ($pakaiini == "1") {
        $dpp = $angka;
        $ppn = $angka * $ppnFactor / 100;
        $grandtotal = $dpp + $ppn;
        $var['ppn'] = $ppn;
        $var['dppPpn'] = $dpp;
        $var['grandTotal'] = $grandtotal;
    }
    else {
        $arr = membilang($angka);

        $dpp = round($angka, 0);
        $ppn = (int)($dpp * $ppnFactor / 100);
        $grandtotal = $dpp + $ppn;
        $var['ppn'] = $ppn;
        $var['dppPpn'] = $dpp;
        $var['grandTotal'] = $grandtotal;
        // matiHere($angka."||".$ppnFactor);
        // arrPrint($var);


    }
    return $var;
}


//pembulatan angka dibelakang selalu 0
function pembulatanDiskon($angka)
{
    $expTransaksi_net = explode(".", $angka);
    $integer = $expTransaksi_net[0];
    return $integer;
}

function makeDppBulat($value, $ppn_opt = null)
{
    if ($ppn_opt === null) {
        $ppn_opt = function_exists('my_ppn_factor') ? my_ppn_factor() : 10;
    }
    $val = pembulatan_puluhan($value);

    $val_tmp = $val['faktor'];
    $hasil = $val['hasil'];
    $ppn = $hasil * $ppn_opt / 100;

    $tmp['hasil'] = $hasil;
    $tmp['hasil_child'] = $ppn;
    $tmp['pembulatan'] = $val_tmp;
    $tmp['hasil_total'] = $ppn + $hasil;
    $tmp['new_tagihan'] = $ppn + $hasil;

    return $tmp;

}

function breakdownPpn($nilaiIncludeppn, $ppnFactor)
{
    $preDpp = $nilaiIncludeppn * (100 / (100 + $ppnFactor));
    $dppnew = ceil($preDpp);
    $ppn = floor($dppnew * $ppnFactor / 100);
    // cekMErah("dpp :".$dppnew." ppn ".$ppn);
    $data = array(
        "source_nilai" => $nilaiIncludeppn,
        "dpp" => $dppnew,
        "ppn" => $ppn,
    );
    return $data;
    // arrPrint($preDpp);
    // matiHEre();
}

// endregion stop

//handling exponent
function reformatExponent($input)
{
    if (preg_match('/[0-9]+\.[0-9]+[Ee][-+]?[0-9]+/', $input)) {
//        echo "Ini exponent.". $input;
        $input = number_format($input, 10);
    }
    else {
        $input = $input;
//        cekMErah("bukan exponent lolos aja");
    }
    return $input;
}

function pembulatanKebawah($angka)
{
//    cekBiru(floor($angka));
//    cekHitam($angka);
    $arr = membilang($angka);
//    arrPrint($arr);
    $pecahan = $arr['desimal'];
    $faktor = -$pecahan;
//    $pembulatan = $angka + $faktor;


    $var["pecahan"] = $pecahan;
    $var["faktor"] = $faktor;
    $var["hasil"] = floor($angka);
//    arrPrintWebs($var);
//    matiHere();
    return $var;
}

/**
 * Menerapkan prinsip materialitas pada nilai keuangan.
 * Jika nilai di bawah ambang batas (threshold), nilai akan disesuaikan ke ambang batas.
 * Selisih kurang atau lebih akan dihitung untuk dialokasikan ke Laba/Rugi Lain-lain.
 *
 * @param float $originalValue Nilai asli yang akan diperiksa
 * @param float $threshold Ambang batas materialitas (default: 1.0)
 * @return array Hasil penyesuaian dan nilai selisih laba/rugi
 */
function applyMaterialityAdjustment($originalValue, $threshold = 1)
{
    // 1. Jika nilai sudah memenuhi atau di atas ambang batas, tidak ada perubahan
    if (abs($originalValue) >= $threshold) {
        return [
            'adjusted_value' => $originalValue,
            'miscellaneous_profit_loss' => 0.0,
            'is_adjusted' => false
        ];
    }

    // 2. Tentukan arah penyesuaian (mendukung nilai positif dan negatif)
    // Jika input 0 atau positif di bawah 1 -> disesuaikan ke 1
    // Jika input negatif di atas -1 (misal -0.5) -> disesuaikan ke -1
    $adjustedValue = ($originalValue >= 0) ? $threshold : -$threshold;

    // 3. Hitung selisih untuk Laba/Rugi Lain-lain
    // Rumus: Nilai Baru - Nilai Lama
    $miscellaneousProfitLoss = $adjustedValue - $originalValue;

    return array(
        'adjusted_value' => (float)$adjustedValue,
        'miscellaneous_profit_loss' => (float)$miscellaneousProfitLoss,
        'is_adjusted' => true
    );
}



?>