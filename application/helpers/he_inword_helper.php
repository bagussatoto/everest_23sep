<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 7/17/2019
 * Time: 8:06 PM
 */

function convert_number($number)
{
    if (($number < 0) || ($number > 999999999999999999999)) {
        throw new Exception("Number is out of range");
    }
    $Qn = floor($number / 1000000000000000);  /* Quadrillion (quad) */
    $number -= $Qn * 1000000000000000;
    $Tn = floor($number / 1000000000000);  /* Trillions (teta) */
    $number -= $Tn * 1000000000000;
    $Bn = floor($number / 1000000000);  /* Billions (tera) */
    $number -= $Bn * 1000000000;
    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */
    $res = "";

    if ($Qn) {
        $res .= convert_number($Qn) . " Quadrillion";
    }

    if ($Tn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Tn) . " Trillion";
    }

    if ($Bn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Bn) . " Billion";
    }

    if ($Gn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Gn) . " Million";
    }
    if ($kn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($kn) . " Thousand";
    }
    if ($Hn) {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Hn) . " Hundred";
    }
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {
        if (!empty($res)) {
            $res .= " and ";
        }
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        }
        else {
            $res .= $tens[$Dn];
            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }
    if (empty($res)) {
        $res = "zero";
    }

    return $res;
}

function inWordEng($c, $currency = 'IDR')
{

    $ci = &get_instance();
    $ci->load->Model("Mdls/MdlCurrency");
    $ci->load->library("MataUang");
    $cc = new MdlCurrency();
    $mu = new MataUang();

    $arrMataUang = $mu->getMataUang($currency);

    $f1 = isset($arrMataUang['f1']) ? ucwords($arrMataUang['f1']) : "";
    $f2 = isset($arrMataUang['f2']) ? ucwords($arrMataUang['f2']) : "";

    $num2 = 0;
    if (strpos($c, ".") == TRUE) {
        $d = explode(".", $c);

        if ($d[1] > 10) {
            //add 0 to front
            $r = round($c, 2);
            $d1 = explode(".", $r);
            if (empty($d1[1])) {
                $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                $num2 = convert_number($d1[0]) . " " . $f1 . $f1J;
            }
            else {
                if (strlen($d1[1]) == 1) {
                    $fd = $d1[1] . "0";
                    $f2J = $fd > 0 && $fd > 1 ? 's' : '';
                    $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                    $num2 = convert_number($d1[0]) . " " . $f1 . $f1J . " " . convert_number($fd) . " $f2$f2J ";
                }
                else {
                    $f2J = $d1[1] > 0 && $d1[1] > 1 ? 's' : '';
                    $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                    $num2 = convert_number($d1[0]) . " " . $f1 . $f1J . " " . convert_number($d1[1]) . " $f2$f2J";
                }
            }
        }
        else {
            //add 0 to back
            $r = round($c, 2);
            $d1 = explode(".", $r);
            if (empty($d1[1])) {
                $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                $num2 = convert_number($d1[0]) . " " . $f1 . $f1J;
            }
            else {
                if (strlen($d1[1]) == 1) {
                    $num2 = $d1[0] . "." . $d1[1] . "0";
                    $fd = $d1[1] . "0";
                    $f2J = $fd > 0 && $fd > 1 ? 's' : '';
                    $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                    $num2 = convert_number($d1[0]) . " " . $f1 . $f1J . " " . convert_number($fd) . " $f2$f2J";
                }
                else {
                    $num2 = $d1[0] . "." . $d1[1];
                    $f2J = $d1[1] > 0 && $d1[1] > 1 ? 's' : '';
                    $f1J = $d1[0] > 0 && $d1[0] > 1 ? 's' : '';
                    $num2 = convert_number($d1[0]) . " " . $d1[0] . $f1 . $f1J . "  point " . convert_number($d1[1]) . " $f2$f2J";
                }
            }
        }
    }
    else {
        $f1J = $c > 0 && $c > 1 ? 's' : '';
        $num2 = convert_number($c) . " " . $f1 . $f1J;
    }
    return $num2;
}

function inWordInd($x, $style = 4)
{
    $x = $x * 1;
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

if( ! function_exists('kekata'))
{
    // cekHijau("kosong");
    function kekata($x)
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
        else if ($x < 20) {
            $temp = kekata($x - 10) . " belas";
        }
        else if ($x < 100) {
            $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
        }
        else if ($x < 200) {
            $temp = " seratus" . kekata($x - 100);
        }
        else if ($x < 1000) {
            $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
        }
        else if ($x < 2000) {
            $temp = " seribu" . kekata($x - 1000);
        }
        else if ($x < 1000000) {
            $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
        }
        else if ($x < 1000000000) {
            $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
        }
        else if ($x < 1000000000000) {
            $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
        }
        else if ($x < 1000000000000000) {
            $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
        }

        return $temp;
    }
}
else{
    // cekHijau("ada");
}


function tkoma($x)
{
    //    echo "$x";
    $str = stristr($x, ".");
    $ex = explode('.', $x);
//    print_r($ex);
    $a = 0;
    if (($ex[0] / 10) >= 1) {
        $a = abs($ex[0]);
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

    $a2 = $ex[0] / 10;
    $pjg = strlen($str);
    $i = 1;


    if ($a >= 1 && $a < 12) {
        $temp .= " " . $string[$a];
    }
    else if ($a > 12 && $a < 20) {
        $temp .= kekata($a - 10) . " belas";
    }
    else if ($a > 20 && $a < 100) {
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

    return $temp;
}