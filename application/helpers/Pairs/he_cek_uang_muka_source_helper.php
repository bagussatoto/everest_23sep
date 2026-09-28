<?php

function cekUangMukaSource($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlPaymentUangMuka");
    $cs = New MdlPaymentUangMuka();
    if (sizeof($paramsFilter) > 0) {
//        arrPrintKuning($paramsFilter);
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
    }
    $tmpResult = $cs->lookupAll()->result();
//    showLast_query("biru");
//    arrPrintKuning($tmpResult);
//    arrPrintHijau($_SESSION[$cCode]['main']);

    //----
//    $sisa_ppn_uang_muka = 0;
//    $ppn_uang_muka_dipakai = 0;
//    $ppn_uang_muka_sisa_new = 0;
//    //----
//    $sisa_uang_muka = 0;
//    $sisa_uang_muka_dipakai = 0;
//    $sisa_uang_muka_new = 0;
    //----
    $result = array();
    $saldo_uang_muka=0;
    if (sizeof($tmpResult) > 0) {
        if (sizeof($tmpResult) == 1) {
//            $sisa_uang_muka = ($tmpResult[0]->sisa > 0) ? $tmpResult[0]->sisa : 0;
//            $sisa_ppn_uang_muka = ($tmpResult[0]->sisa_ppn > 0) ? $tmpResult[0]->sisa_ppn : 0;
//            $ppn_invoice = $_SESSION[$cCode]['main']['ppn_out_bulat'];
//            $total_invoice = $_SESSION[$cCode]['main']['grand_pembulatan'];
//            //-------
//            if ($sisa_ppn_uang_muka > 0) {
//                if ($sisa_ppn_uang_muka > $ppn_invoice) {
//                    $ppn_uang_muka_dipakai = $ppn_invoice;
//                }
//                else {
//                    $ppn_uang_muka_dipakai = $sisa_ppn_uang_muka;
//                }
//            }
//            else {
//                $ppn_uang_muka_dipakai = 0;
//            }
//            $ppn_uang_muka_sisa_new = $sisa_ppn_uang_muka - $ppn_uang_muka_dipakai;
//
//
//            //-------
//            if ($sisa_uang_muka > 0) {
//                $total_invoice_after_ppn_uang_muka = $total_invoice - $ppn_uang_muka_dipakai;
//                if ($sisa_uang_muka > $total_invoice_after_ppn_uang_muka) {
//                    $sisa_uang_muka_dipakai = $total_invoice_after_ppn_uang_muka;
//                }
//                else {
//                    $sisa_uang_muka_dipakai = $sisa_uang_muka;
//                }
//            }
//            else {
//                $sisa_uang_muka_dipakai = 0;
//            }
//            $sisa_uang_muka_new = $sisa_uang_muka - $sisa_uang_muka_dipakai;
//            //-------

            $saldo_uang_muka = $tmpResult[0]->sisa;
        }
        elseif (sizeof($tmpResult) > 1) {
            foreach ($tmpResult as $result_0){
                $saldo_uang_muka +=$result_0->sisa;
            }
//            $msg = "Transaksi gagal disimpan karena data uang muka tidak valid. Silahkan hubungi admin errcode " . __FUNCTION__ . "::" . __LINE__;
//            mati_disini($msg);
        }
    }
    else {
        $saldo_uang_muka = 0;
    }

    $result = array(
//
////        "ppn dari invoice" => $ppn_invoice,
//        "ppn_uang_muka" => $sisa_ppn_uang_muka,
//        "ppn_uang_muka_dipakai" => $ppn_uang_muka_dipakai,
//        "ppn_uang_muka_sisa_new" => $ppn_uang_muka_sisa_new,
//        //----
////        "tagihan dari invoice" => $total_invoice,
////        "tagihan dari invoice after ppn uang muka" => $total_invoice_after_ppn_uang_muka,
//        "uang_muka" => $sisa_uang_muka,
//        "uang_muka_dipakai" => $sisa_uang_muka_dipakai,
//        "uang_muka_sisa_new" => $sisa_uang_muka_new,
//        //----
//        "piutang_dagang_dipakai" => $sisa_uang_muka_dipakai + $ppn_uang_muka_dipakai,
//        "piutang_dagang_pym_src" => $total_invoice - ($sisa_uang_muka_dipakai + $ppn_uang_muka_dipakai),
//        //----
//        "ppn_invoice_netto" => $ppn_invoice - $ppn_uang_muka_dipakai,
////        "dpp_invoice_netto" => ".0",
////        "total_invoice_netto" => ".0",
        "saldo_uang_muka" => $saldo_uang_muka,
    );
//    arrPrint($result);
//    mati_disini();
    if ($gate == "items") {
        foreach ($_SESSION[$cCode][$gate] as $pid => $spec) {
            foreach ($result as $kyy => $vll) {
                $spec[$kyy] = $vll;
            }
            $_SESSION[$cCode][$gate][$pid] = $spec;
        }
    }

//
//    foreach ($result as $key => $val) {
//        $_SESSION[$cCode]['main'][$key] = $val;
//    }

//    return true;
    return $result;
}

function cekUangMukaSourceRelasi($tr, $stepNumber, $paramsFilter = array(), $gate)
{

    $cCode = "_TR_" . $tr;

    $ci =& get_instance();
    $ci->load->model("Mdls/MdlPaymentUangMuka");
    $cs = New MdlPaymentUangMuka();
    if (sizeof($paramsFilter) > 0) {
        foreach ($paramsFilter as $key => $val) {
            $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
            $cs->addFilter("$key='$realVal'");
        }
    }
    $tmpResult = $cs->lookupAll()->result();
//    arrprint($tmpResult);
//cekHere($ci->db->last_query());
//cekBiru(sizeof($tmpResult));
    $saldo_uang_muka = 0;
    $result = array();
    if (sizeof($tmpResult) > 0) {
        if (sizeof($tmpResult) == 1) {
            $saldo_uang_muka += $tmpResult[0]->sisa;
        }
        elseif (sizeof($tmpResult) > 1) {
            foreach ($tmpResult as $result_0){
                $saldo_uang_muka +=$result_0->sisa;
            }
            /**
             * validasi > 1
             * dimatikan karena saat select pihak / supplier macet, belum memilih nota
             * contoh kasus hasil select dapat 9 tetapi yang ada saldo/sisa hanya 1 baris/2 baris
             * 2025-12-12
             */
//            $msg = "Transaksi gagal disimpan karena data uang muka tidak valid. Silahkan hubungi admin errcode " . __FUNCTION__ . "::" . __LINE__;
//            mati_disini($msg);
        }
    }
    else {
        $saldo_uang_muka = 0;
    }

    $result = array(
        "saldo_uang_muka_relasi" => $saldo_uang_muka,
    );
    if ($gate == "items") {
        foreach ($_SESSION[$cCode][$gate] as $pid => $spec) {
            foreach ($result as $kyy => $vll) {
                $spec[$kyy] = $vll;
            }
            $_SESSION[$cCode][$gate][$pid] = $spec;
        }
    }

    return $result;
}


