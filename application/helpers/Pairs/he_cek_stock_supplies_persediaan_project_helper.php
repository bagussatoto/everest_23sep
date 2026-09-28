<?php

function cekStockSuppliesPersediaanProject($tr, $stepNumber, $paramsFilter = array(), $gate)
{
    $cCode = "_TR_" . $tr;
    $rekening = "1010030010";

    $ci =& get_instance();
    $ci->load->model("Coms/ComRekeningPembantuSupplies");
    $ci->load->model("Mdls/MdlLockerStockSupplies");

//    $bIDs = array();
//    $pIDs = array();
    $tmpResult = array();
    $tmpResultLocker = array();
    $tmpResultLockerTujuan = array();
    $resultTmp = array();
    $resultTmpLocker = array();
    $result = array();
    if (isset($_SESSION[$cCode][$gate]) && (sizeof($_SESSION[$cCode][$gate]) > 0)) {
        foreach ($_SESSION[$cCode][$gate] as $byID => $dSpec) {
            foreach ($dSpec as $iSpec) {
                $pIDs = isset($iSpec['biaya_dasar_id']) ? $iSpec['biaya_dasar_id'] : $iSpec['id'];
                $bIDs = $byID;
                cekUngu("[gate: $gate] [byID: $byID] [pIDs: $pIDs]");
                // stok rekening
                $cst = New ComRekeningPembantuSupplies();
                $cs = New ComRekeningPembantuSupplies();
                // stok locker (active, hold, ....), gudang penyerah/asal
                $lcs = New MdlLockerStockSupplies();
                // stok locker (active, hold, ....), gudang penerima/tujuan
                $tcs = New MdlLockerStockSupplies();

//                if (sizeof($pIDs) > 0) {
                // stok rekening
                $cst->addFilter("extern_id='$pIDs'");
                $cs->addFilter("extern_id='$pIDs'");
                // stok locker (active, hold, ....)
                $lcs->addFilter("produk_id='$pIDs");
                $lcs->addFilter("state in ('active','hold')");
                // $lcs->addFilter("jumlah>'0'");
                $tcs->addFilter("produk_id='$pIDs'");
//                }

                if (sizeof($paramsFilter) > 0) {
                    foreach ($paramsFilter as $key => $val) {
                        $realVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                        $cs->addFilter("$key='$realVal'");
                        $lcs->addFilter("$key='$realVal'");
                    }
                    $tmpResult = $cs->fetchBalances($rekening);
                    showLast_query("biru");
                    $tmpResultLocker = $lcs->lookupAll()->result();
                    showLast_query("kuning");
                }

                if (sizeof($tmpResult) > 0) {
                    foreach ($tmpResult as $eSpec) {
//                        $resultTmp[$eSpec->extern_id] = $eSpec->qty_debet;
                        $result[$byID][$pIDs]["current_stok"] = $eSpec->qty_debet;
                    }
                }

                if (sizeof($tmpResultLocker) > 0) {
//                    arrPrintHitam($tmpResultLocker);
                    foreach ($tmpResultLocker as $eSpec) {
                        $state = $eSpec->state;
//                        cekHere("[$state] [$pIDs] [$bIDs] || " . $eSpec->nama . " || " . $eSpec->jumlah);
                        switch ($state) {
                            case "hold":
                                $tblIDs = $eSpec->id;
//                                $bIDs = $eSpec->biaya_id;
                                // intransit milik sendiri [biaya_id]
                                if ($byID == $bIDs) {
                                    if (!isset($result[$bIDs][$pIDs]["jml_intransit"])) {
                                        $result[$bIDs][$pIDs]["jml_intransit"] = 0;
                                    }
                                    $result[$bIDs][$pIDs]["jml_intransit"] += $eSpec->jumlah;
                                }

//                                if (!isset($result[$pIDs]["jml_intransit_total"])) {
//                                    $result[$pIDs]["jml_intransit_total"] = 0;
//                                }
//                                $result[$pIDs]["jml_intransit_total"] = $eSpec->jumlah;

                                break;
                            case "active":
                                if (!isset($result[$bIDs][$pIDs]["jml_available"])) {
                                    $result[$bIDs][$pIDs]["jml_available"] = 0;
                                }
                                $result[$bIDs][$pIDs]["jml_available"] += $eSpec->jumlah;
                                break;
                        }
                    }
                }

                $tmpTotalResult = $cst->fetchBalances($rekening);
                showLast_query("ungu");
                if (sizeof($tmpTotalResult) > 0) {
                    foreach ($tmpTotalResult as $cstSpec) {
                        if (!isset($result[$byID][$pIDs]["company_stok"])) {
                            $result[$byID][$pIDs]["company_stok"] = 0;
                        }
                        // [biayaID] [suppliesID]
                        $result[$byID][$pIDs]["company_stok"] += $cstSpec->qty_debet;
                    }
                }

            }
        }
    }


    arrPrint($result);
//    mati_disini(__LINE__);

    return $result;
}