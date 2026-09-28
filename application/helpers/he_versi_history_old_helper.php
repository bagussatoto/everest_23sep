<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 7/17/2019
 * Time: 8:06 PM
 */

function ConversiHistoryOld()
{

    $historyOld = array(
        // penjualan
        "582" => array(
            1 => array(
                "label" => "Sales Order",
                "target" => "582r",
            ),
            2 => array(
                "label" => "Paking List",
                "target" => "582k",
            ),
            3 => array(
                "label" => "Invoicing",
                "target" => "582",
            ),
        ),
        // Pembelian produk jadi goods
        "466" => array(
            1 => array(
                "label" => "Purchasing Order",
                "target" => "466",
            ),
            2 => array(
                "label" => "Goods Receive Note",
                "target" => "467",
            ),
        ),
        // adjusment
        "235" => array(
            1 => array(
                "label" => "Adjusment",
                "target" => "235",
            ),
        ),
        // konversi produk
        "334" => array(
            1 => array(
                "label" => "Request Komversi",
                "target" => "334r",
            ),
            2 => array(
                "label" => "Konversi",
                "target" => "334",
            ),
        ),
        // Supplies / row materials
        "461" => array(
            1 => array(
                "label" => "Request PO Row Material",
                "target" => "461r",
            ),
            2 => array(
                "label" => "PO Row Material",
                "target" => "582k",
            ),
        ),
        // distribusi ke cabang
        "583" => array(
            1 => array(
                "label" => "Order Distribusi",
                "target" => "583r",
            ),
            2 => array(
                "label" => "Distribusi",
                "target" => "583",
            ),
        ),

        "489" => array(
            1 => array(
                "label" => "Pembayaran Hutang",
                "target" => "489",
            ),
            // 2 => array(
            //     "label"  => "Distribusi",
            //     "target" => "583",
            // ),
        ),
        "463" => array(
            1 => array(
                "label" => "Hutang Jasa",
                "target" => "461r",
            ),
            2 => array(
                "label" => "pembayaran",
                "target" => "461",
            ),
        ),
        // petty cash
        "671" => array(
            1 => array(
                "label" => "Request Petty Cash",
                "target" => "671r",
            ),
            2 => array(
                "label" => "Petty Cash",
                "target" => "671",
            ),
        ),
        "749" => array(
            1 => array(
                "label" => "penerimaan piutang",
                "target" => "749",
            ),
        ),
        "776" => array(
            1 => array(
                "label" => "req. proses",
                "target" => "776r",
            ),
            2 => array(
                "label" => "proses",
                "target" => "776",
            ),
        ),
        "779" => array(
            1 => array(
                "label" => "req. penerimaan setoran",
                "target" => "779r",
            ),
            2 => array(
                "label" => "penerimaan setoran",
                "target" => "779",
            ),
        ),
        "787" => array(
            1 => array(
                "label" => "rusak",
                "target" => "787",
            ),
        ),
        "788" => array(
            1 => array(
                "label" => "pembayaran hutang kontainer",
                "target" => "788",
            ),
        ),

        "888" => array(
            1 => array(
                "label" => "penyesuaian",
                "target" => "888",
            ),
        ),
        "945" => array(
            1 => array(
                "label" => "Request return distribusi",
                "target" => "945r",
            ),
            2 => array(
                "label" => "return distribusi",
                "target" => "945",
            ),
        ),
        "949" => array(
            1 => array(
                "label" => "Req. pengembalian deposit konsumen",
                "target" => "949",
            ),
        ),
        "961" => array(
            1 => array(
                "label" => "Req. return pembelian order",
                "target" => "961r",
            ),
            2 => array(
                "label" => "return pembelian supplies",
                "target" => "961",
            ),
        ),
        "967" => array(
            1 => array(
                "label" => "Req. return pembelian",
                "target" => "982r",
            ),
            2 => array(
                "label" => "return pembelian",
                "target" => "982",
            ),
        ),
        "983" => array(
            1 => array(
                "label" => "Req. return distribusi",
                "target" => "983r",
            ),
            2 => array(
                "label" => "return distribusi",
                "target" => "983",
            ),
        ),
        "982" => array(
            1 => array(
                "label" => "Req. return penjualan",
                "target" => "982r",
            ),
            2 => array(
                "label" => "return penjualan",
                "target" => "982",
            ),
        ),
        "970" => array(
            1 => array(
                "label" => "Req. pengurangan plafon pettycash",
                "target" => "970r",
            ),
            2 => array(
                "label" => "pengurangan plafon pettycash",
                "target" => "970",
            ),

        ),
        "947" => array(
            1 => array(
                "label" => "penyesuaian piutang",
                "target" => "947",
            ),

        ),

        "382" => array(
            1 => array(
                "label" => "Export Order",
                "target" => "382r",
            ),
            2 => array(
                "label" => "Paking List",
                "target" => "382k",
            ),
            3 => array(
                "label" => "Invoicing",
                "target" => "382",
            ),
        ),

    );

    return $historyOld;
}

/** formater numbering (nomer nota) untuk old aplikasi */
function formatNumbering($numbering, $arrKomponen)
{
    //    $arrKomponen = array($arrKomponen);
    $explNumb = explode("-", $numbering);
    $hasil = "";
    foreach ($arrKomponen as $v) {

        if ($hasil == "") {

            $hasil = isset($explNumb[$v]) ? $explNumb[$v] : "";
        }
        else {

            $hasil = "$hasil-" . $explNumb[$v];
        }

    }
    $numbering_f = $hasil;


    return $numbering_f;
}

function getPrintablePages()
{
    $arrSlash = $_SERVER["SCRIPT_NAME"];
    $count = explode('/', $arrSlash);
    $max = count($count) - 1;

    return $count[$max];
}

function arrConfInv()
{

    $arrCf = array(
        "582k" => array(5, 6, 1),
        "382k" => array(5, 6, 1),
        // "582k" => array(3, 4, 1),
        "382r" => array(4, 5, 6, 1),
        "582r" => array(4, 5, 6, 1),
        "582" => array(5, 6, 1),
        "382" => array(5, 6, 1),
        "583" => array(5, 6, 1),
        "349" => array(6, 7, 9, 2),

        "467" => array(5, 6, 8, 1),
        "461r" => array(2, 3, 6, 1),
        "461" => array(2, 3, 6, 1),
        // 462-1-17-1--1 -1-118-1
        // 0   1 2  3 4 5 6 7   8
        "462" => array(2, 3, 6, 1),
        // 489-1--1-1-17-1-151-1

        "489" => array(2, 3, 6, 1),

        "465r" => array(2, 3, 6, 1),
        "465" => array(2, 3, 6, 1),
        "466" => array(2, 3, 6, 1),


        "761r" => array(2, 3, 6, 1),
        "761" => array(2, 3, 6, 1),
        "776r" => array(2, 3, 6, 1),
        "776" => array(2, 3, 6, 1),
        "722r" => array(2, 3, 6, 1),
        "722" => array(2, 3, 6, 1),
        "749" => array(6, 7, 9, 2),
        "922r" => array(2, 3, 6, 1),
        "922" => array(2, 3, 6, 1),
        "921r" => array(2, 3, 6, 1),
        "921" => array(2, 3, 6, 1),
        "967r" => array(2, 3, 6, 1),
        "967" => array(2, 3, 6, 1),
        "982r" => array(5, 4, 8, 1),
        "982" => array(5, 4, 8, 1),
        "983r" => array(4, 5, 3, 1),
        "983" => array(4, 5, 3, 1),
        "331" => array(5, 4, 8, 1),
        "332" => array(5, 4, 8, 1),
        "333" => array(5, 4, 8, 1),
        "932" => array(5, 4, 8, 1),
    );


    return $arrCf;
}

function arrConfNomer()
{
    $arrCf = array(
        // 20190116-582k-61--1-61-32-37-105-1
        // 20181101-582k-11--1-11-31-11-57-2
        // 0         1    2 3 4 5  6  7  8 9
        // 20181217-582-8--1-8-85-1-91-1
        // 20181219-461-24-9-11--1-8-119-1

        // "582k" => array(4, 5, 2),
        // "582k" => array(5, 6, 7, 2),
        //20181217-582-8--1-8-85-1-91-1
        //20190405-583r-3-1-3-17-3
        "334r" => array(4, 2),
        "334" => array(4, 2),
        "583r" => array(4, 6, 2),
        "583" => array(4, 6, 2),
        "582k" => array(6, 7, 2),
        "382k" => array(6, 7, 2),
        "582r" => array(5, 6, 7, 2),
        "382r" => array(5, 6, 7, 2),
        "582" => array(6, 7, 2),
        "382" => array(6, 7, 2),

        // "582" => array(5, 6, 7, 2),

        "467" => array(6, 7, 9, 2),
        "461r" => array(3, 4, 2),
        "461" => array(3, 4, 2),
        "462" => array(3, 4, 7, 2),
        "465r" => array(3, 4, 7, 2),
        "465" => array(3, 4, 7, 2),
        "466" => array(3, 4, 7, 2),
        // 20190208-489-1-17-1--1-1-89-1
        "489" => array(3, 4, 7, 2),

        // 20190125-749-9--1-1-17-1-113-1
        "749" => array(6, 7, 9, 2),
        "349" => array(6, 7, 9, 2),

        "761r" => array(3, 4, 7),
        "761" => array(3, 4, 7),
        "762r" => array(3, 4, 7),
        "762" => array(3, 4, 7),
        "776r" => array(3, 4, 7, 2),
        "776" => array(3, 4, 7, 2),
        "722r" => array(3, 4, 7, 2),
        "722" => array(3, 4, 7, 2),

        "922r" => array(3, 4, 7, 2),
        "922" => array(3, 4, 7, 2),
        "921r" => array(3, 4, 7, 2),
        "921" => array(3, 4, 7, 2),
        "961r" => array(3, 4, 7, 2),
        "961" => array(3, 4, 7, 2),
        "967r" => array(3, 4, 7, 2),
        "967" => array(3, 4, 7, 2),
        // 20181217-582-8--1-8-85-1-91-1
        // 20190113-982r-1--1-1-17-1-67-1
        // 0         1   23 4 5  6 7 8  9
        // "582" => array(6, 7, 2),
        // 20190116-663-6-1-8
        "663" => array(3, 4, 2),
        "982r" => array(6, 5, 9, 2),
        "982" => array(6, 5, 9, 2),
        // 20190531-983r-1-1-1-185-1-1
        // 20190531-983r-2-1-2-181-1-2
        // 20190601-983-6-1-3-17-6
        "983r" => array(5, 6, 4, 2),
        "983" => array(5, 6, 4, 2),
        "331" => array(3, 5, 6, 2),
        "332" => array(3, 5, 6, 2),
        "333" => array(3, 5, 6, 2),
        "932" => array(3, 5, 6, 2),
    );

    return $arrCf;
}

function arrConfInitial()
{
    $arrInitial = array(
        //        "467" => "PENERIMAAN-",
        "461r" => "PO-",
        // "461" => "PO-",
        //        "465r" => "PRE-PO-",
        "465r" => "PR",
        "465" => "",
        "466" => "PO-",
        "461" => "GRN-",
        "467" => "BM-",
        "489" => "PYM-",
        "462" => "PYM-",
        "582k" => "PL-",
        "382k" => "PL-EXP-",
        "382r" => "SO-EXP-",
        "349" => "PYM-EXP-",
        "582r" => "SO-",
        "582" => "INV-",
        "382" => "INV-EXP-",
        "749" => "PYM-",
        "761r" => "PR-",
        "762r" => "B-",
        "762" => "B-",
        "961r" => "RR-",
        "961" => "RGRN-",
        "967" => "RBM-",
        "967r" => "RRBM-",
        "982r" => "RE-",
        "982" => "RET-",
        "983r" => "RRD-",
        "983" => "RD-",
        "663" => "MD-",
        "583r" => "RDST-",
        "583" => "DST-",
        "334r" => "RCON-",
        "334" => "CON-",
        "331" => "ADJ-",
        "332" => "ADJ-PRDPL-",
        "333" => "ADJ-HJA-",
        "932" => "ADJ-PRDMN-",
        //        "761" => "PEMINDAHAN-S-",
        //        "776r" => "PRODUCTION-",
        //        "776" => "PRODUCTION-",
        //        "722r" => "PEMINDAHAN-P-",
        //        "722" => "PEMINDAHAN-P-",
        //        "922r" => "R-PEMINDAHAN-P-",
        //        "922" => "R-PEMINDAHAN-P-",
        //        "921r" => "R-PEMINDAHAN-S-",
        //        "921" => "R-PEMINDAHAN-S-"
    );

    return $arrInitial;
}

function oldArrConfName()
{
    $arrInitial = array(
        //        "467" => "PENERIMAAN-",
        "461r" => "PO-",
        // "461" => "PO-",
        //        "465r" => "PRE-PO-",
        "465r" => "PR",
        "465" => "",
        "466" => "PO-",
        "461" => "GRN-",
        "467" => "BM-",
        "489" => "PYM-",
        "462" => "PYM-",
        "582k" => "PL-",
        "382k" => "PL-EXP-",
        "382r" => "SO-EXP-",
        "349" => "PYM-EXP-",
        "582r" => "SO-",
        "582" => "INV-",
        "382" => "INV-EXP-",
        "749" => "PYM-",
        "761r" => "PR-",
        "762r" => "B-",
        "762" => "B-",
        "961r" => "RR-",
        "961" => "RGRN-",
        "967" => "RBM-",
        "967r" => "RRBM-",
        "982r" => "RE-",
        "982" => "RET-",
        "983r" => "RRD-",
        "983" => "RD-",
        "663" => "MD-",
        "583r" => "RDST-",
        "583" => "DST-",
        "334r" => "RCON-",
        "334" => "CON-",
        "331" => "ADJ-",
        "332" => "ADJ-PRDPL-",
        "333" => "ADJ-HJA-",
        "932" => "ADJ-PRDMN-",
        //        "761" => "PEMINDAHAN-S-",
        //        "776r" => "PRODUCTION-",
        //        "776" => "PRODUCTION-",
        //        "722r" => "PEMINDAHAN-P-",
        //        "722" => "PEMINDAHAN-P-",
        //        "922r" => "R-PEMINDAHAN-P-",
        //        "922" => "R-PEMINDAHAN-P-",
        //        "921r" => "R-PEMINDAHAN-S-",
        //        "921" => "R-PEMINDAHAN-S-"
    );

    return $arrInitial;
}

function formatTransNomerInv($nomer, $transaksi_jenis, $cabang_id = "")
{

    if (($cabang_id == "") or ($cabang_id == CB_ID_PUSAT)) {
        $pre = "p";
    }
    else {
        $pre = $cabang_id;
    }
    //cekHere("$transaksi_jenis, $nomer");
    $arrCf = oldArrConfName();

    $arrPart = array(
        "467" => array(1, 4, 5, 6, 7, 8),
        "461r" => array('cab.', 'dua', 'tiga', 'empat'),
        "461" => array(3, 4, 7, 2),
        "465r" => array(3, 4, 7, 2),
        "465" => array(3, 4, 7, 2),

        "582k" => array(1, 2, 5, 6, 7, 8, 9),
        "382k" => array(1, 2, 5, 6, 7, 8, 9),
        "582r" => array(1, 4, 5, 6, 7, 8),
        "382r" => array(1, 4, 5, 6, 7, 8),
        "582" => array(1, 4, 5, 6, 7, 8),
        "382" => array(1, 4, 5, 6, 7, 8),

        "761r" => array(3, 4, 7, 2),
        "761" => array(3, 4, 7, 2),

        "776r" => array(3, 4, 7, 2),
        "776" => array(3, 4, 7, 2),
        "722r" => array(3, 4, 7, 2),
        "722" => array(3, 4, 7, 2),

        "922r" => array(3, 4, 7, 2),
        "922" => array(3, 4, 7, 2),
        "921r" => array(3, 4, 7, 2),
        "921" => array(3, 4, 7, 2),
        "331" => array(3, 4, 7, 2),
        "332" => array(3, 4, 7, 2),
        "333" => array(3, 4, 7, 2),
        "932" => array(3, 4, 7, 2),
    );

    $arrInitial = arrConfInitial();

    if (in_array($transaksi_jenis, array_keys($arrCf))) {
        if (in_array($transaksi_jenis, array_keys($arrInitial))) {
            $initial = $arrInitial[$transaksi_jenis];
        }
        else {
            $initial = "";
        }
        // $var = "<div class=\"no-padding no-margin col-md-8\">";
        //
        // $var .= "<span class=\"no-padding no-margin col-xs-2\">";
        // $var .= "<span style=\"font-size: 7px;\" class=\"no-padding no-margin col-xs-12 text-center\">&nbsp;</span>";
        // $var .= "<span style=\"line-height: 1;\" class=\"no-padding no-margin col-xs-12 text-bold\"><small>No: </small></span>";
        // $var .= "</span>";
        //
        // $var .= "<span class=\"no-padding no-margin col-xs-2\">";
        // $var .= "<span style=\"font-size: 7px;border-bottom: 1px #33333347 solid;\" class=\"no-padding no-margin col-xs-12 text-left\">initial</span>";
        // $var .= "<span style=\"line-height: 1;\" class=\"no-padding no-margin col-xs-12 text-left text-bold\">$initial</span>";
        // $var .= "</span>";

        // $var = formatNumberingText($nomer, $arrCf[$transaksi_jenis], $arrPart[$transaksi_jenis], $pre);
        $var = strtoupper($initial . $pre . "-" . formatNumbering($nomer, $arrCf[$transaksi_jenis]));
        // $var .= "</div>";
    }
    else {
        $var = $nomer;
    }

    $test = "<span class='meta'>$nomer</span>";

    return $var;
}

function formatTransNomer($nomer, $transaksi_jenis, $cabang_id = "")
{
//cekbiru("$nomer, $transaksi_jenis, $cabang_id ");
    //cek nama file yang akses
    $files = getPrintablePages();
    //switch printable grid
    $rules = FALSE;
    //show No:
    $txtNumber = False;

    if ($files == 'printnable.php' && $rules == TRUE) {

        if (($cabang_id == "") or ($cabang_id == CB_ID_PUSAT)) {
            $pre = "p";
        }
        else {
            $pre = $cabang_id;
        }

        $arrCf = $arrCf = arrConfNomer();

        $arrPart = array(
            //        "582k" => array(1, 2, 5, 6, 7, 8, 9),
            "582k" => array('UrutCab', 'PICID', 'UrutPL'),
            "582r" => array('PIC', 'UrutPIC', 'CustID', 'UrutSO'),
            "582" => array('PICID', 'UrutPIC', 'UrutINV'),
            "467" => array(6, 7, 9, 2),
            //            "461r"  => array('PICID', 'UrutPIC', 'SuppID', 'UrutPO'),
            "461r" => array('PICID', 'UrutPIC', 'UrutPO'),
            "461" => array('PICID', 'UrutPIC', 'UrutPO'),
            //            "465r"  => array(3, 4, 7, 2),
            //            "465"   => array(3, 4, 7, 2),
            "465r" => array('PICID', 'UrutPIC', 'SuppID', 'UrutPO'),
            "465" => array('PICID', 'UrutPIC', ''),
            "466" => array('PICID', 'UrutPIC', 'SuppID', 'UrutPO'),

            "761r" => array('PICID', 'UrutPIC', 'UrutPR'),
            "761" => array('PICID', 'UrutPIC', 'UrutPR'),
            "762r" => array('PICID', 'UrutPIC', 'UrutPR'),
            "762" => array('PICID', 'UrutPIC', 'UrutPR'),
            "776r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "776" => array('PICID', 'UrutPIC', 'UrutInv'),
            "722r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "722" => array('PICID', 'UrutPIC', 'UrutInv'),
            "749" => array('PICID', 'UrutPIC', 'UrutInv'),
            "349" => array('PICID', 'UrutPIC', 'UrutInv'),

            "922r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "922" => array('PICID', 'UrutPIC', 'UrutInv'),
            "921r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "921" => array('PICID', 'UrutPIC', 'UrutInv'),
            "961r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "961" => array('PICID', 'UrutPIC', 'UrutInv'),
            "967r" => array('PICID', 'UrutPIC', 'UrutInv'),
            "967" => array('PICID', 'UrutPIC', 'UrutInv'),
            "331" => array('PICID', 'UrutPIC', 'UrutInv'),
            "332" => array('PICID', 'UrutPIC', 'UrutInv'),
            "333" => array('PICID', 'UrutPIC', 'UrutInv'),
            "932" => array('PICID', 'UrutPIC', 'UrutInv'),
        );

        $arrInitial = arrConfInitial();

        if (in_array($transaksi_jenis, array_keys($arrCf))) {

            if (in_array($transaksi_jenis, array_keys($arrInitial))) {
                $initial = $arrInitial[$transaksi_jenis];
            }
            else {
                $initial = "";
            }

            $var = "<div class=\"no-padding no-margin\">";

            if ($txtNumber) {
                $var .= "<span class=\"no-padding no-margin col-xs-2\">";
                $var .= "<span style=\"font-size: 8px;\" class=\"no-padding no-margin col-xs-12 text-center\">&nbsp;</span>";
                $var .= "<span style=\"line-height: 1;\" class=\"no-padding no-margin col-xs-12 text-bold\"><small>No: </small></span>";
                $var .= "</span>";
            }

            if ($initial != '') {
                $var .= "<span style='padding-left:1px; padding-right: 1px;' class=\"col-xs-2\">";
                $var .= "<span style=\"font-size: 8px;border-bottom: 1px #33333347 solid;padding-left:1px; padding-right: 1px;\" class=\"col-xs-12 text-center\">initial</span>";
                $var .= "<span style=\"line-height: 1;\" class=\"no-padding no-margin col-xs-12 text-center text-bold\">" . str_replace('-', '', $initial) . "</span>";
                $var .= "</span>";
            }

            $var .= formatNumberingText($nomer, $arrCf[$transaksi_jenis], $arrPart[$transaksi_jenis], $pre);

            $var .= "</div>";
        }
        else {
            $var = $nomer;
        }

        //        $test = "<span class='meta'>$nomer</span>";
        return $var;

    }
    else {
        //        cekHEre("hoe hoe");
        if (($cabang_id == "") or ($cabang_id == CB_ID_PUSAT)) {
            $pre = "p";
        }
        else {
            $pre = $cabang_id;
        }

        // cekHijau($cabang_id);
        //         cekHijau($transaksi_jenis);
        $arrCf = arrConfNomer();
        $arrInitial = arrConfInitial();

        if (in_array($transaksi_jenis, array_keys($arrCf))) {
            if (in_array($transaksi_jenis, array_keys($arrInitial))) {
                $initial = $arrInitial[$transaksi_jenis];
            }
            else {
                $initial = "";
            }
            $var = strtoupper($initial . $pre . "-" . formatNumbering($nomer, $arrCf[$transaksi_jenis]));
        }
        else {
            $var = $nomer;
        }

        return $var;
    }

}
