<?php
function createRekCode($rek, $extID = 0)
{
    $emptyChar = "00000000000000000000";
    $fLength = 10;
    if (strlen($extID) < 1) {
        $extID = "000";
    }
    else {
        if (strlen($extID) < 2) {
            $extID = "00" . $extID;
        }
        else {
            if (strlen($extID) < 3) {
                $extID = "0" . $extID;
            }
        }
    }
    $rawCode = "";
    if (NULL != config_item('accountStructure')) {
        foreach (config_item('accountStructure') as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                foreach ($cSpec as $code => $name) {
                    if ($name == $rek) {
                        $rawCode = $code;
                    }
                }
            }
        }
    }

    $theCode = $rawCode . $extID;
    if (strlen($theCode) < $fLength) {
        $sisLength = ($fLength - strlen($theCode));
        $theCode = $theCode . substr($emptyChar, 1, $sisLength);
    }
    return $theCode;
}

function getRekOpponent($posisi)
{
    switch ($posisi) {
        case "kredit":
            return "debit";
            break;
        case "debit":
            return "kredit";
            break;
        default:
            return NULL;
            break;
    }
}

function detectRekCategory_OLD($rek)
{

    $usedCat = NULL;
    if (NULL != config_item('accountStructure')) {
        foreach (config_item('accountStructure') as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;
                break;
            }
        }
        return $usedCat;
    }
    else {
        return NULL;
    }
}

function detectRekCategory($rek)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();
    $prefData = $ac->lookUpTransactionStructure();
    $usedCat = NULL;
    //    if (NULL != config_item('accountStructure')) {
    if (sizeof($prefData) > 0) {
        //        foreach (config_item('accountStructure') as $catName => $cSpec) {
        foreach ($prefData as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;
                break;
            }
        }
        return $usedCat;
    }
    else {
        return NULL;
    }
}

function detectRekPosition_OLD($rek, $value)
{

    $usedCat = NULL;
    if (NULL != config_item('accountStructure')) {
        foreach (config_item('accountStructure') as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;

                break;
            }
        }
        if ($usedCat != NULL) {
            if (config_item('accountBehavior')[$usedCat] != NULL) {
                if ($value >= 0) {
                    $result = config_item('accountBehavior')[$usedCat][0]; //==kolom kiri
                }
                else {
                    $result = config_item('accountBehavior')[$usedCat][1]; //==kolom kanan
                }

                return $result;
            }
            else {
                return NULL;
            }
        }
        else {
            //echo "usedCat for $rek is NULL<br>";
            return NULL;
        }
    }
    else {
        return NULL;
    }
}

function detectRekPosition($rek, $value)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();

    $prefData = $ac->lookUpTransactionStructure();
//     cekLime($ci->db->last_query());
//         arrPrint($prefData);
//    matiHEre();
    $usedCat = NULL;
    if (sizeof($prefData) > 0) {
        foreach ($prefData as $catName => $cSpec) {
//            cekKuning($catName);
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;
//cekHitam(":: $rek :: $catName ::");
                break;
            }
        }
//        cekMErah("[$rek] [$value] :: $usedCat");
//         matiHEre($rek);
//                arrprint( config_item('accountBehavior') );
        if ($usedCat != NULL) {
            if (config_item('accountBehavior')[$usedCat] != NULL) {
                if ($value > 0) {
                    $result = config_item('accountBehavior')[$usedCat][0]; //==kolom kiri
                }
                elseif ($value == 0) {
                    $result = config_item('accountBehavior')[$usedCat][0]; //==kolom kiri
                }
                else {
                    $result = config_item('accountBehavior')[$usedCat][1]; //==kolom kanan
                }

                return $result;
            }
            else {
                return NULL;
            }
        }
        else {
            //echo "usedCat for $rek is NULL<br>";
            return NULL;
        }
    }
    else {
        return NULL;
    }
}


function detectRekByPosition_OLD($rek, $value, $position)
{
    $usedCat = NULL;
    if (NULL != config_item('accountStructure')) {
        foreach (config_item('accountStructure') as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;
                break;
            }
        }

        if ($usedCat != NULL) {
            if (config_item('accountBehavior')[$usedCat] != NULL) {
                $urutNumber = array_search($position, config_item('accountBehavior')[$usedCat]);
//                cekBiru("[$position] $rek, $value, $usedCat, $urutNumber");
                switch ($urutNumber) {
                    case 0://==kiri
                        $result = $value;
                        break;
                    case 1://kanan
                        $result = -($value);
                        break;
                    default:
                        $result = NULL;
                        break;
                }
                return $result;
            }
            else {
                return NULL;
            }


        }
        else {
            //echo "usedCat for $rek is NULL<br>";
            return NULL;
        }
    }
    else {
        return NULL;
    }
}

function detectRekByPosition($rek, $value, $position)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();
    $prefData = $ac->lookUpTransactionStructure();
    $usedCat = NULL;
    //    if (NULL != config_item('accountStructure')) {
    if (sizeof($prefData) > 0) {
        //        foreach (config_item('accountStructure') as $catName => $cSpec) {
        foreach ($prefData as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;
                break;
            }
        }
// ceklIme($usedCat);
        if ($usedCat != NULL) {
            if (config_item('accountBehavior')[$usedCat] != NULL) {
                $urutNumber = array_search($position, config_item('accountBehavior')[$usedCat]);
                // cekBiru("[$position] $rek, $value, $usedCat, $urutNumber");
                switch ($urutNumber) {
                    case 0://==kiri
                        $result = $value;
                        break;
                    case 1://kanan
                        $result = -($value);
                        break;
                    default:
                        $result = NULL;
                        break;
                }
                return $result;
            }
            else {
                return NULL;
            }


        }
        else {
            //echo "usedCat for $rek is NULL<br>";
            return NULL;
        }
    }
    else {
        return NULL;
    }
}


function detectRekDefaultPosition_OLD($rek)
{
    $cat = detectRekCategory($rek);
    if (NULL != config_item('accountBehavior')) {
        $thisBehav = config_item('accountBehavior');
        if (isset($thisBehav[$cat])) {
            return $thisBehav[$cat][0];
        }
        else {
            return NULL;
        }
    }
    else {
        return NULL;
    }
}

function detectRekDefaultPosition($rek)
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();
    $prefData = $ac->lookUpTransactionStructure();

    $usedCat = NULL;
    if (sizeof($prefData) > 0) {
        foreach ($prefData as $catName => $cSpec) {
            if (in_array($rek, $cSpec)) {
                $usedCat = $catName;

                break;
            }
        }
        if ($usedCat != NULL) {
            if (config_item('accountBehavior')[$usedCat] != NULL) {
                $result = config_item('accountBehavior')[$usedCat][0]; //==kolom default kategori position

                return $result;
            }
            else {
                return NULL;
            }
        }
        else {
            return NULL;
        }
    }
    else {
        return NULL;
    }
}

function validateAllBalances($cab = "")
{

    $ci = &get_instance();
    $accountChilds = $ci->config->item("accountChilds");
    $ci->load->model("Coms/ComRekening");
    $r = new ComRekening();

    if ($cab == "") {
        $cab = $ci->session->login['cabang_id'];
    }
    $r->addFilter("cabang_id='" . $cab . "'");

    $r->setSortBy(array("mode" => "ASC", "kolom" => "rekening"));
    $tmp = $r->fetchAllBalances();
//showLast_query("biru");
    $rekenings = array();
    $totals = array(
        "rekening" => "total",
        "debet" => 0,
        "kredit" => 0,
    );
    if (sizeof($tmp) > 0) {
        /*
         * diofkan kuntuk hemat waktu execute dari 22 detik ke 14 detik
         */
//        echo "<table rules='all' style='border:1px solid black;'>";
//        echo "<tr>";
//        echo "<th align='center'>coa</th>";
//        echo "<th align='center'>rekening</th>";
//        echo "<th align='center'>debet</th>";
//        echo "<th align='center'>kredit</th>";
//        echo "</tr>";
//        foreach ($tmp as $row) {
//            if (is_numeric($row['rekening'])) {
//
//                $tmpCol = array(
//                    "rekening" => $row['rekening'],
//                    "debet" => $row['debet'] * 1,
//                    "kredit" => $row['kredit'] * 1,
//                    "link" => "",
//                );
//                if (isset($accountChilds[$row['rekening']])) {
//                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
//                }
//                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";
//
//                $rekenings[] = $tmpCol;
//                $totals["debet"] += $row['debet'];
//                $totals["kredit"] += $row['kredit'];
//
//                $rekening_alias = isset(fetchAccountStructureAlias()[$row['rekening']]) ? fetchAccountStructureAlias()[$row['rekening']] : "";
//                echo "<tr>";
//                echo "<td align='left'>" . $tmpCol['rekening'] . "</td>";
//                echo "<td align='left'>" . $rekening_alias . "</td>";
//                echo "<td align='right'>" . number_format($tmpCol['debet'], "2", ",", ".") . "</td>";
//                echo "<td align='right'>" . number_format($tmpCol['kredit'], "2", ",", ".") . "</td>";
//                echo "</tr>";
//
//            }
//        }
//        echo "<tr>";
//        echo "<td align='left'></td>";
//        echo "<td align='left'></td>";
//        echo "<td align='right'>" . number_format($totals['debet'], "2", ",", ".") . "</td>";
//        echo "<td align='right'>" . number_format($totals['kredit'], "2", ",", ".") . "</td>";
//        echo "</tr>";
//        echo "</table>";
    }
    if (floor($totals['debet']) == floor($totals['kredit'])) {
        return true;
    }
    else {
        if (round($totals['debet'], 2) == round($totals['kredit'], 2)) {

            return true;
        }
        else {
            if (round($totals['debet'], 1) == round($totals['kredit'], 1)) {
//                mati_disini(__LINE__);
                return true;
            }
            else {
                if (round($totals['debet'], 0) == round($totals['kredit'], 0)) {
//                mati_disini(__LINE__);
                    return true;
                }
                else {
                    $selisih = ($totals['debet'] - $totals['kredit']);
                    $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
                    if ($selisih > 1) {
                        die(lgShowAlert("Unbalance trial balances (" . floor($totals['debet']) . " vs. " . floor($totals['kredit']) . "). Please retry after two minutes or contact your system administrator."));
                        return false;
                    }
                    else {
                        return true;
                    }
                }
            }
        }


    }


}

function validateAllBalancesPeriode($cab = "")
{

    $ci = &get_instance();
    $accountChilds = $ci->config->item("accountChilds");
    $ci->load->model("Coms/ComRekening");
    $r = new ComRekening();

    if ($cab == "") {
        $cab = $ci->session->login['cabang_id'];
    }
    $r->addFilter("cabang_id='" . $cab . "'");


    $tmp = $r->fetchAllBalancesPeriode();

    cekHitam(":: " . $ci->db->last_query() . " ::");


//    $rekenings = array();

    if (sizeof($tmp) > 0) {
        foreach ($tmp as $periode => $tSpec) {

            cekBiru(":: validate periode $periode ::");
            echo "<table rules='all' style='border:1px solid black;' width='50%'>";

            if (!isset($totals[$periode])) {
                $totals[$periode] = array(
                    "rekening" => "total",
                    "debet" => 0,
                    "kredit" => 0,
                );
            }
            foreach ($tSpec as $row) {

                $tmpCol = array(
                    "rekening" => $row['rekening'],
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );

//                if (isset($accountChilds[$row['rekening']])) {
//                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
//                }
//                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";
//
//                $rekenings[] = $tmpCol;
                $totals[$periode]["debet"] += $row['debet'];
                $totals[$periode]["kredit"] += $row['kredit'];

                echo "<tr>";
                echo "<td align='left'>" . $tmpCol['rekening'] . "</td>";
                echo "<td align='right'>" . $tmpCol['debet'] . "</td>";
                echo "<td align='right'>" . $tmpCol['kredit'] . "</td>";
                echo "</tr>";
            }
            $selisih = $totals[$periode]['debet'] - $totals[$periode]['kredit'];

            echo "<tr>";
            echo "<td align='left' style='color:red;font-size: smaller;'>$selisih</td>";
            echo "<td align='right'>" . $totals[$periode]['debet'] . "</td>";
            echo "<td align='right'>" . $totals[$periode]['kredit'] . "</td>";
            echo "</tr>";
            echo "</table>";


            if (round($totals[$periode]['debet'], 2) == round($totals[$periode]['kredit'], 2)) {

            }
            else {
                //        $ci->db->query("unlock tables");
                mati_disini("Unbalance trial balances $periode (" . round($totals[$periode]['debet'], 2) . " vs. " . round($totals[$periode]['kredit'], 2) . ").");
//                die(lgShowAlert("Unbalance trial balances (" . round($totals['debet'], 2) . " vs. " . round($totals['kredit'], 2) . ")."));

            }

        }
    }


//    mati_disini();
}

/*
 * cek pre jurnal untuk menghitung selisih untuk dilempar ke rekening rugi laba jika nilai dibawah Rp 1000
 */
function validateAllBalancesAutoAdjust($cab = "", $jn = "", $insertID = "", $insertNum = "")
{

    $ci = &get_instance();
    $accountChilds = $ci->config->item("accountChilds");
    $ci->load->model("Coms/ComRekening");
    $r = new ComRekening();

    if ($cab == "") {
        $cab = $ci->session->login['cabang_id'];
    }
    $r->addFilter("cabang_id='" . $cab . "'");


    $tmp = $r->fetchAllBalances();

    $rekenings = array();
    $totals = array(
        "rekening" => "total",
        "debet" => 0,
        "kredit" => 0,
    );
    if (sizeof($tmp) > 0) {
        echo "<h4>Sebelum adjusment</h4>";
        echo "<table>";
        foreach ($tmp as $row) {
            if (is_numeric($row['rekening'])) {

                $tmpCol = array(
                    "rekening" => $row['rekening'],
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );

                if (isset($accountChilds[$row['rekening']])) {
                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
                }
                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                $rekenings[] = $tmpCol;
                $totals["debet"] += $row['debet'];
                $totals["kredit"] += $row['kredit'];


                echo "<tr>";
                echo "<td align='left'>" . $tmpCol['rekening'] . "</td>";
                echo "<td align='right'>" . $tmpCol['debet'] . "</td>";
                echo "<td align='right'>" . $tmpCol['kredit'] . "</td>";
                echo "</tr>";

            }
        }
        echo "<tr>";
        echo "<td align='left'></td>";
        echo "<td align='right'>" . $totals['debet'] . "</td>";
        echo "<td align='right'>" . $totals['kredit'] . "</td>";
        echo "</tr>";
        echo "</table>";
    }

    // cekHitam($totals['debet'] . " == " . $totals['kredit']);
    if ($totals['debet'] == $totals['kredit']) {
        return true;
    }
    else {
        //auto adjustment ke rugilaba pembulatan jurnal 1 sisi 03/10/2022
        $selisih = $totals['debet'] - $totals['kredit'];
        $validateNilai = $selisih < 0 ? $selisih * -1 : $selisih;
        if ($validateNilai > 1000) {
            die(lgShowAlert("failed to validate trial balances . Please retry after two minutes or contact your system administrator. Error code: he_acc " . __LINE__));
            return false;
        }
        cekMerah($selisih);
        $ci->load->model("Coms/ComJurnal");
        $ci->load->model("Coms/ComRekening");
        $dataLoopJurnal = array(
            "loop" => array(
                "7010180" => $selisih,
            ),
            "static" => array(
                "cabang_id" => "$cab",
                "jenis" => $jn,
                "transaksi_no" => "$insertNum",
                "transaksi_id" => "$insertID",
                "urut" => "1",
                "fulldate" => date("Y-m-d"),
                "dtime" => date("Y-m-d H:i:s"),
                "keterangan" => "auto adjusment pembulatan",
                "balance" => 0,// untuk menjalankan pembersih selisih
            ),
        );
        $j = new ComJurnal();
        $m = new ComRekening();
        $j->pair($dataLoopJurnal) or die("Tidak berhasil memasang  values pada komponen auto adjusment: $j/" . $jn . "/" . __FUNCTION__ . "/" . __LINE__);
        $j->exec() or die("Gagal saat berusaha  exec values pada komponen auto adjusment: ComJurnal/" . $jn . "/" . __FUNCTION__ . "/" . __LINE__);

        $m->pair($dataLoopJurnal) or die("Tidak berhasil memasang  values pada komponen auto adjusment: $j/" . $jn . "/" . __FUNCTION__ . "/" . __LINE__);
        $m->exec() or die("Gagal saat berusaha  exec values pada komponen auto adjusment: ComRekening/" . $jn . "/" . __FUNCTION__ . "/" . __LINE__);

        $r = new ComRekening();
        $r->addFilter("cabang_id='" . $cab . "'");


        $tmp = $r->fetchAllBalances();
//        arrprint($tmp);

        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            echo "<table>";
            foreach ($tmp as $row) {
                $tmpCol = array(
                    "rekening" => $row['rekening'],
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );

                if (isset($accountChilds[$row['rekening']])) {
                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
                }
                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                $rekenings[] = $tmpCol;
                $totals["debet"] += $row['debet'];
                $totals["kredit"] += $row['kredit'];


                // echo "<tr>";
                // echo "<td align='left'>" . $tmpCol['rekening'] . "</td>";
                // echo "<td align='right'>" . $tmpCol['debet'] . "</td>";
                // echo "<td align='right'>" . $tmpCol['kredit'] . "</td>";
                // echo "</tr>";
            }
            // echo "<tr>";
            // echo "<td align='left'></td>";
            // echo "<td align='right'>" . $totals['debet'] . "</td>";
            // echo "<td align='right'>" . $totals['kredit'] . "</td>";
            // echo "</tr>";
            // echo "</table>";
        }

//        cekMerah($totals['debet'] . " == " . $totals['kredit']);
        if (floor($totals['debet']) == floor($totals['kredit'])) {
            return true;
        }
        else {
//            mati_disini($totals['debet'] - $totals['kredit']);
            die(lgShowAlert("failed to validate trial balances . Please retry after two minutes or contact your system administrator. Error code: he_acc " . __LINE__));
            return false;
        }
        // matiHEre("unbalance");


    }


}

//-------
function validatePreAllBalances($cab = "")
{

    $ci = &get_instance();
    $accountChilds = $ci->config->item("accountChilds");
    $ci->load->model("Coms/ComRekening");
    $r = new ComRekening();

    if ($cab == "") {
        $cab = $ci->session->login['cabang_id'];
    }
    $r->addFilter("cabang_id='" . $cab . "'");


    $tmp = $r->fetchAllBalances();

    $rekenings = array();
    $totals = array(
        "rekening" => "total",
        "debet" => 0,
        "kredit" => 0,
    );
    if (sizeof($tmp) > 0) {
        // echo "<table>";
        foreach ($tmp as $row) {
            $tmpCol = array(
                "rekening" => $row['rekening'],
                "debet" => $row['debet'] * 1,
                "kredit" => $row['kredit'] * 1,
                "link" => "",
            );

            if (isset($accountChilds[$row['rekening']])) {
                $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
            }
            $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";

            $rekenings[] = $tmpCol;
            $totals["debet"] += $row['debet'];
            $totals["kredit"] += $row['kredit'];


            // echo "<tr>";
            // echo "<td align='left'>" . $tmpCol['rekening'] . "</td>";
            // echo "<td align='right'>" . $tmpCol['debet'] . "</td>";
            // echo "<td align='right'>" . $tmpCol['kredit'] . "</td>";
            // echo "</tr>";
        }
        // echo "<tr>";
        // echo "<td align='left'></td>";
        // echo "<td align='right'>" . $totals['debet'] . "</td>";
        // echo "<td align='right'>" . $totals['kredit'] . "</td>";
        // echo "</tr>";
        // echo "</table>";
    }


//    if (round($totals['debet'], 1) == round($totals['kredit'], 1)) {
    cekHitam(floor($totals['debet']) . " == " . floor($totals['kredit']));
    if (floor($totals['debet']) == floor($totals['kredit'])) {
//        $ci->db->query("unlock tables");
        return true;
    }
//    elseif (round($totals['debet'], 2) == round($totals['kredit'], 2)) {
//        return true;
//    }
//
    else {
        if (round($totals['debet'], 2) == round($totals['kredit'], 2)) {

            return true;
        }
        else {
            if (round($totals['debet'], 1) == round($totals['kredit'], 1)) {
//                mati_disini(__LINE__);
                return true;
            }
            else {
                if (round($totals['debet'], 0) == round($totals['kredit'], 0)) {
//                mati_disini(__LINE__);
                    return true;
                }
                else {
                    $selisih = $totals['debet'] - $totals['kredit'];
                    $selisih = ($selisih < 0) ? $selisih * -1 : $selisih;
                    if ($selisih > 1) {
                        // die(lgShowAlert("Unbalance trial balances (" . floor($totals['debet']) . " vs. " . floor($totals['kredit']) . "). Please retry after two minutes or contact your system administrator."));
                        $data = array(
                            "rugi_laba_pembulatan_ganjil" => $totals['debet'] - $totals['kredit'],
                        );
                        return $data;
                    }
                    else {
                        cekHijau("-- LANJUT --");
                        return true;
                    }
                }
            }
        }


    }


}

function fetchRevertJurnal($jenis, $step, $component = array(), $jenisTr_reference = "")
{
    $ci = &get_instance();

    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["components"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["components"][$step] : array();
    }

    $mainGateReplacer = isset($ci->config->item("heTransaksi_revertMainGateReplacer")[$jenis]) ? $ci->config->item("heTransaksi_revertMainGateReplacer")[$jenis] : array();

    $coreLockerValue = array();
    if (sizeof($coreConfig) > 0) {
        $selectedMainFields = array(
            "produk_qty",
            "jml",
            "jumlah",
            "unit",
//            "produk_nilai",
            "produk_hpp",
            "hpp",
            "produk_harga",
            "qty",
        );
        foreach ($coreConfig as $mainGate => $gateData) {
            foreach ($gateData as $iGate => $tempGate) {
                if (isset($tempGate['loop'])) {
                    $tmp = array();
                    foreach ($tempGate['loop'] as $rekName => $rekVal) {
                        //----------------------------
                        if (substr($rekVal, 0, 1) == "-") {
                            $tmpEx = explode("-", $rekVal);
                            if (sizeof($tmpEx) > 1) {
                                $str = str_replace("-", "", $rekVal);
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $str = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                            }
                            else {
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $rekVal = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                                $str = "-" . $rekVal;
                            }
                        }
                        else {
                            $anu1 = strpos($rekVal, "*-");
                            $anu2 = strpos($rekVal, "*+");
                            $anu1 = $anu1 > 0 ? $anu1 : 0;
                            $anu2 = $anu2 > 0 ? $anu2 : 0;
                            $anu3 = $anu1 + $anu2;
//                            mati_disini(":: [$anu1] :: [$anu2] [$anu3] ::");
                            if ($anu3 == 0) {
                                $tmpEx = explode("-", $rekVal);
                            }
                            if (sizeof($tmpEx) > 1) {
                                $str = "-" . str_replace("-", "+", $rekVal);
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $str = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                            }
                            else {
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $rekVal = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                                $str = "-" . $rekVal;
                            }
                        }


//                        if (substr($str, 0, 2) == "--") {
//                            $str = str_replace("--", "", $str);
//                        }
//                        if (substr($str, 0, 2) == "+-") {
//                            $str = str_replace("+-", "-", $str);
//                        }
//                        if (substr($str, 0, 2) == "-+") {
//                            $str = str_replace("-+", "-", $str);
//                        }

                        $tmp[$rekName] = $str;

                    }
                    $coreConfig[$mainGate][$iGate]["loop"] = $tmp;

                    if ($jenisTr_reference != "999") {
                        $coreLockerValue[$mainGate][$iGate]["loop"] = $tmp;
                    }

                }

                if (isset($tempGate['static'])) {
                    foreach ($tempGate['static'] as $ks => $val_s) {
                        if (in_array($ks, $selectedMainFields)) {
                            if (substr($val_s, 0, 1) == ".") {
                                $val_s = str_replace(".", "", $val_s);
                                $tmpEx = explode("-", $val_s);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal_0 = str_replace("-", "", $val_s);
                                }
                                else {
                                    $strVal_0 = "-" . $val_s;
                                }
                                $strVal_0 = "." . $strVal_0;
                            }
                            else {
                                $tmpEx = explode("-", $val_s);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal_0 = str_replace("-", "", $val_s);
                                }
                                else {
                                    $strVal_0 = "-" . $val_s;
                                }
                            }
                        }
                        else {
                            $strVal_0 = $val_s;
                        }

                        $coreConfig[$mainGate][$iGate]["static"][$ks] = $strVal_0;
                    }
                }
                else {
                    $coreConfig[$mainGate][$iGate]["static"] = array();
                }

                if ($jenisTr_reference != "999") {
                    $coreLockerValue[$mainGate][$iGate]["static"] = isset($tempGate['static']) ? $tempGate['static'] : array();
                    $coreLockerValue[$mainGate][$iGate]["comName"] = isset($tempGate['comName']) ? $tempGate['comName'] : array();
                    $coreLockerValue[$mainGate][$iGate]["srcGateName"] = isset($tempGate['srcGateName']) ? $tempGate['srcGateName'] : array();
                    $coreLockerValue[$mainGate][$iGate]["srcRawGateName"] = isset($tempGate['srcRawGateName']) ? $tempGate['srcRawGateName'] : array();
                }

            }
        }

    }


    $selectedFields = array(
        "produk_qty", "jml", "jumlah", "unit",
    );

    $validateComSwap = array(
        "FifoAverage",
        "FifoProdukJadi",
        "FifoProdukJadiRakitan",
        "FifoSupplies",

    );

    $rekExceptionLockervalue = array(
        "kas",
    );
    //---PREPROCC SETUP----------------
    $aliasing = array(
        "produk_id" => "extern_id",
        "nama" => "extern_nama",
        "jml" => "produk_qty",
        "unit" => "produk_qty",
        "produk_nama" => "extern_nama",
    );//untuk replacer preprocesor
    $comNameReplacer = array(
        "supplies" => "FifoAverageSupplies",
        "produk" => "FifoAverage",
    );
    $resultParams = array(
        "supplies" => array(
            "FifoAverageSupplies" => array(
                "resultParams" => array(
                    "items" => array(
                        "harga_disc" => "hpp",
                        "hpp" => "hpp",
                        "sub_harga_disc" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        "produk" => array(
            "FifoAverage" => array(
                "resultParams" => array(
                    "items" => array(
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "sub_hpp_nppv" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        "none" => array(
            "FifoProdukJadi" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
            "FifoSupplies" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),

            "FifoProdukJadiRakitan" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
            "FifoAverage" => array(
                "resultParams" => array(
                    "items" => array(
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "sub_hpp_nppv" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        //------------------------
//        "valas" => array(
//            "FifoValasExternAverage" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//            "FifoValasExtern" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//        ),
        //------------------------
    );
    $replacerParams = array(
        "recalculate" => array(
            "selisih",
            "hpp_nppv",
            "hpp_nppn",
        ),
    );
    $replacerSrcGate = array(
        "srcGateName" => "items",
        "srcRawGateName" => "items",
    );
    $replacerSrcGate_revert = array(
        "srcGateName" => array(
            "rsltItems" => "rsltItems_revert",
            "rsltItems2" => "rsltItems2_revert",
        ),
        "srcRawGateName" => array(
            "rsltItems" => "rsltItems_revert",
            "rsltItems2" => "rsltItems2_revert",
        ),
    );
    $comNameOrig = array(
        "RekeningPembantuProduk",
        "RekeningPembantuSupplies",
        "RekeningPembantuLogamMuliaItem",
        "RekeningPembantuValas",
    );
    //------------------------
    $srcGateNameReplacer = array(
        "rsltItems" => "rsltItems_revert",
    );
    //------------------------
    $coreConfigAdd = array();

    if (isset($coreConfig["detail"])) {
        foreach ($coreConfig["detail"] as $iKey => $detailVal) {
            $tmpStatic = array();
            if (in_array($detailVal["comName"], $validateComSwap)) {
                $calonPreprocc = $coreConfig["detail"][$iKey];
                unset($coreConfig["detail"][$iKey]);
                //---PRE-PROCC----------
//                cekHitam("PRE-PROCC");
//                arrPrintWebs($calonPreprocc);

                foreach ($replacerSrcGate_revert as $srcGate => $rSpec) {
                    if (array_key_exists($calonPreprocc["srcGateName"], $rSpec)) {
                        $calonPreprocc[$srcGate] = $rSpec[$calonPreprocc["srcGateName"]];
//                    cekHitam("$srcGate -- $rval");
                    }
                    if (array_key_exists($calonPreprocc["srcRawGateName"], $rSpec)) {
                        $calonPreprocc[$srcGate] = $rSpec[$calonPreprocc["srcRawGateName"]];
//                    cekKuning("$srcGate -- $rval");
                    }
                }
                if (isset($calonPreprocc["static"]) && isset($calonPreprocc["static"]["jenis"])) {
                    $jenis = str_replace(".", "", $calonPreprocc["static"]["jenis"]);
                }
                else {
                    $jenis = "none";
                }
                // mereplace comName supplies dan produk....
                if (array_key_exists($jenis, $comNameReplacer)) {
                    $calonPreprocc["comName"] = $comNameReplacer[$jenis];
                }
                if (isset($calonPreprocc["static"])) {
                    foreach ($calonPreprocc["static"] as $sKey => $val) {
                        if (isset($aliasing[$sKey])) {
                            $tKey = $aliasing[$sKey];
                            $calonPreprocc["static"][$tKey] = str_replace("-", "", $val);
                            unset($calonPreprocc["static"][$sKey]);
                        }
                        else {
                            $calonPreprocc["static"][$sKey] = str_replace("-", "", $val);
//                            cekHijau(":: $sKey => $val ::");
                        }
                    }
                }


                if (isset($resultParams[$jenis][$calonPreprocc["comName"]])) {
                    $calonPreprocc2 = $resultParams[$jenis][$calonPreprocc["comName"]];
//                cekHijau("ini");
                }
                else {
                    $calonPreprocc2 = array();
//                cekHijau("itu");
                }
//cekHijau($calonPreprocc2);
                $coreConfig["preProcc"]["detail"][] = $calonPreprocc + $calonPreprocc2;
            }
            else {
                if (isset($detailVal["static"])) {
                    foreach ($detailVal["static"] as $ikey2 => $ival) {
                        if (in_array($ikey2, $selectedFields)) {
                            if (substr($ival, 0, 1) == ".") {
                                $ival = str_replace(".", "", $ival);
                                $tmpEx = explode("-", $ival);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal = str_replace("-", "", $ival);
                                }
                                else {
                                    $strVal = "-" . $ival;
                                }
                                $strVal = "." . $strVal;
                            }
                            else {

                                $tmpEx = explode("-", $ival);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal = str_replace("-", "", $ival);
                                }
                                else {
                                    $strVal = "-" . $ival;
                                }
                            }
                        }
                        else {
                            $strVal = $ival;
                        }
                        $tmpStatic[$ikey2] = $strVal;
//                        $coreConfig["detail"][$iKey]["static"][$ikey2] = $strVal;
                    }
                }
                switch ($jenisTr_reference) {
                    case "582spd":
                        if (in_array($detailVal["comName"], $comNameOrig)) {
                            $coreConfig["detail"][$iKey]["srcGateName"] = "rsltItems_revert";
                            $coreConfig["detail"][$iKey]["srcRawGateName"] = "rsltItems_revert";
                        }
                        break;
                    //jangan di overwrite gerbang jadi tidak ditemukan. menyebabkan stok tidak kembali kerekening persediaan
//                        case "967":
//                        if (in_array($detailVal["comName"], $comNameOrig)) {
//                            $coreConfig["detail"][$iKey]["srcGateName"] = "rsltItems_revert";
//                            $coreConfig["detail"][$iKey]["srcRawGateName"] = "rsltItems_revert";
//                        }
//                        break;
                    case "334":
                    case "1334":
                    case "6677":
                    case "16677":
                    case "1676":
                        if (in_array($detailVal["comName"], $comNameOrig)) {
//                        $coreConfig["detail"][$iKey]["srcGateName"] = "rsltItems_revert";
//                        $coreConfig["detail"][$iKey]["srcRawGateName"] = "rsltItems_revert";

                            if (array_key_exists($detailVal["srcGateName"], $srcGateNameReplacer)) {
                                $coreConfig["detail"][$iKey]["srcGateName"] = $srcGateNameReplacer[$detailVal["srcGateName"]];
                            }
                            if (array_key_exists($detailVal["srcRawGateName"], $srcGateNameReplacer)) {
                                $coreConfig["detail"][$iKey]["srcRawGateName"] = $srcGateNameReplacer[$detailVal["srcGateName"]];
                            }
                        }
                        break;
                    default:
                        break;
                }
            }

            //// =============================== =============================== =============================== ////
            //// =============================== =============================== =============================== ////

//            cekBiru("deteksi dari config state HOLD, jumlah -QTY");
            if (isset($detailVal['static']['state']) && ($detailVal['static']['state'] == ".hold")) {
                if (isset($detailVal['static']['jumlah']) && ($detailVal['static']['jumlah'] == "-qty")) {
//                    arrPrint($detailVal);
                    $iKeyNum = 1;
                    $coreConfigAdd["detail"][$iKeyNum] = $detailVal;
                    $tmpStaticAdd = array();
                    foreach ($detailVal["static"] as $ikey2 => $ival) {
                        $strVal = $ival;
                        $tmpStaticAdd[$ikey2] = $strVal;
                        $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                    }

                    $iKeyNum++;
                    $coreConfigAdd["detail"][$iKeyNum] = $detailVal;
                    $tmpStaticAdd = array();
                    foreach ($detailVal["static"] as $ikey2 => $ival) {
                        if (in_array($ikey2, $selectedFields)) {
                            if (substr($ival, 0, 1) == ".") {
                                $ival = str_replace(".", "", $ival);
                                $tmpEx = explode("-", $ival);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal = str_replace("-", "", $ival);
                                }
                                else {
                                    $strVal = "-" . $ival;
                                }
                                $strVal = "." . $strVal;
                            }
                            else {

                                $tmpEx = explode("-", $ival);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal = str_replace("-", "", $ival);
                                }
                                else {
                                    $strVal = "-" . $ival;
                                }
                            }
                        }
                        else {
                            $strVal = $ival;
                        }
                        $strVal = ($strVal == ".hold") ? ".active" : $strVal;
                        $strVal = ($ikey2 == "transaksi_id") ? ".0" : $strVal;
                        $tmpStaticAdd[$ikey2] = $strVal;
                        $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                    }
                }
            }
        }
    }


    if (sizeof($coreLockerValue) > 0) {
        switch ($jenisTr_reference) {
            case "582spd":
                break;
            case "487":
            case "489":
                break;
            case "749":
                break;
            case "6677":
            case "66771":
            case "16677":
                break;
            default:
                $keyReplacer = array(
                    "extern_id" => "produk_id",
                    "extern_nama" => "produk_nama",
                );
                foreach ($coreLockerValue as $gate => $gSpec) {
                    if ($gate == "master") {
                        foreach ($gSpec as $index => $iSpec) {
                            if (isset($iSpec['loop']) && sizeof($iSpec['loop']) > 0) {
                                foreach ($iSpec['loop'] as $key => $val) {
                                    if (!in_array($key, $rekExceptionLockervalue)) {
                                        $new_val = str_replace("-", "", $val);

                                        $lockVal[$gate][$key]['static']['state'] = ".active";
                                        $lockVal[$gate][$key]['static']['jenis'] = "." . str_replace(" ", "_", $key);
                                        $lockVal[$gate][$key]['static']['nilai'] = $new_val;
                                        $lockVal[$gate][$key]['static']['transaksi_id'] = ".0";
                                        $lockVal[$gate][$key]['static']['oleh_id'] = ".0";
//                            $lockVal[$gate][$key]['static']['produk_id'] = ".0";
                                        $lockVal[$gate][$key]['static']['gudang_id'] = ".0";
                                        $lockVal[$gate][$key]['srcGateName'] = isset($iSpec['srcGateName']) ? $iSpec['srcGateName'] : "";
                                        $lockVal[$gate][$key]['srcRawGateName'] = isset($iSpec['srcRawGateName']) ? $iSpec['srcRawGateName'] : "";
                                        $lockVal[$gate][$key]['comName'] = "LockerValue";

                                        if (isset($iSpec['static']) && sizeof($iSpec['static']) > 0) {
                                            foreach ($iSpec['static'] as $skey => $sval) {

                                                if (!isset($lockVal[$gate][$key]['static'][$skey])) {
                                                    $lockVal[$gate][$key]['static'][$skey] = $sval;
                                                }
                                                // replace key
                                                if (array_key_exists($skey, $keyReplacer)) {
                                                    unset($lockVal[$gate][$key]['static'][$skey]);
                                                    $lockVal[$gate][$key]['static'][$keyReplacer[$skey]] = $sval;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if (sizeof($lockVal) > 0) {
                    foreach ($lockVal as $gate => $gSpec) {
                        foreach ($gSpec as $iSpec) {
                            $coreConfig[$gate][] = $iSpec;
                        }
                    }
                }
                break;
        }

    }

    if (sizeof($coreConfigAdd) > 0) {
        foreach ($coreConfigAdd as $gate => $gateSpec) {
            foreach ($gateSpec as $gSpec) {
                $coreConfig[$gate][] = $gSpec;
            }
        }
    }


//    cekKuning($coreConfig);
    return $coreConfig;

}

function fetchRevertPostProc($jenis, $step, $postProcc = array(), $jenisSrc = "", $jenisTr_reference = "")
{
    $ci = &get_instance();

    if (sizeof($postProcc) > 0) {
        $coreConfig = $postProcc;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step] : array();
    }


    $ci->load->model("Mdls/MdlRevertJurnal");
    $ci->load->model("Mdls/MdlRevertJurnalCabang");

    $validatePostSwap = array(
        "FifoAverage",
        "FifoAverageSupplies",
        "FifoProdukJadi",
        "FifoProdukJadiRakitan",
        "FifoSupplies",

        "FifoValas",
        "FifoValasAverage",

        "FifoValasExtern",
        "FifoValasExternAverage",

        "FifoSuppliesProduksi",
        "FifoSuppliesProses",

    );
    $selectedFields = array(
        "produk_qty", "jml", "jumlah", "unit", "qty_debet",
    );
    $terbayarFields = array("terbayar", "bayar_valas");
    $sisaFields = array("sisa", "sisa_valas");
    $arrRekName = array(
        "nilai",
        "nilai_valas",
        "terbayar",
        "terbayar_valas",
        "jumlah",
        "valid_qty",
        "tambah",
    );
    $noCoreConfigJenis = array("9911", "9912");
    $selectedFieldsAdd = array(
        "LockerValueItem" => array(
            "nilai",
        ),
    );

    switch ($jenisSrc) {
        case "467":
        case "582spd":
        case "461":
        case "585":
        case "3585":
        case "460":
        case "761":
        case "763":
        case "9763":
        case "2985":
        case "773":
        case "982":
        case "1982":
        case "588":
        case "2485":
        case "1119":
        case "2229":
        case "2228":
        case "2227":
        case "335":
        case "2334":
        case "2335":
        case "2336":
        case "2337":
        case "960":
        case "1960":
        case "3339":
        case "5559":
        case "9585":
            $arrMasterKeyReplace = array(
                "produk_id" => "referenceID",
            );
            break;
        default:
            $arrMasterKeyReplace = array(// "produk_id" => "referenceID",
            );
            break;
    }

    if (sizeof($coreConfig) > 0) {
        //-------------------------
        $rj = New MdlRevertJurnal();
        $rjc = New MdlRevertJurnalCabang();

        $rjData = $rj->getStaticData();
        $rjcData = $rjc->getStaticData();
        $revertData = array();
        if (sizeof($rjData) > 0) {
            foreach ($rjData as $rjSpec) {
                if (isset($rjSpec["revertStep"]) && ($rjSpec["revertStep"] == 1)) {
                    $revertData[$rjSpec["id"]] = $rjSpec;
                }
            }
        }
        if (sizeof($rjcData) > 0) {
            foreach ($rjcData as $rjcSpec) {
                if (isset($rjcSpec["revertStep"]) && ($rjcSpec["revertStep"] == 1)) {
                    $revertData[$rjcSpec["id"]] = $rjcSpec;
                }
            }
        }
        //-------------------------
//arrPrintPink($revertData);

arrPrintHitam($selectedFields);
arrPrintHitam($selectedFieldsAdd);

        $coreConfigAdd = array();
        foreach ($coreConfig as $mainGate => $gateData) {
            if ($mainGate == "master") {
                foreach ($gateData as $iGate => $tempGate) {
                    if (isset($tempGate['static'])) {
                        $tmp = array();
                        foreach ($tempGate['static'] as $rekName => $rekVal) {
                            if (in_array($rekName, $arrRekName)) {
                                if (substr($rekVal, 0, 1) == ".") {
                                    $rekVal = str_replace(".", "", $rekVal);
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                    $str = "." . $str;
                                }
                                else {
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                }
                            }
                            else {
                                if (array_key_exists($rekName, $arrMasterKeyReplace)) {
                                    $str = $arrMasterKeyReplace[$rekName];
                                }
                                else {
                                    if (in_array($rekName, $sisaFields)) {
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
//                                            $str = $rekVal . "+" . $tempGate['static']['terbayar'];
                                        }
                                    }
                                    else {
                                        $str = $rekVal;
                                    }
                                }
                            }

                            $tmp[$rekName] = $str;
                        }
                        $coreConfig[$mainGate][$iGate]["static"] = $tmp;
                    }
                    if (isset($tempGate['loop'])) {
                        $tmpLoop = array();
                        foreach ($tempGate['loop'] as $rekName => $rekVal) {
                            if (substr($rekVal, 0, 1) == ".") {
                                $rekVal = str_replace(".", "", $rekVal);
                                $tmpEx = explode("-", $rekVal);
                                if (sizeof($tmpEx) > 1) {
                                    $str = str_replace("-", "", $rekVal);
                                }
                                else {
                                    $str = "-" . $rekVal;
                                }
                                $str = "." . $str;
                            }
                            else {
                                $tmpEx = explode("-", $rekVal);
                                if (sizeof($tmpEx) > 1) {
                                    $str = str_replace("-", "", $rekVal);
                                }
                                else {
                                    $str = "-" . $rekVal;
                                }
                            }

                            $tmpLoop[$rekName] = $str;
                        }
                        $coreConfig[$mainGate][$iGate]["loop"] = $tmpLoop;
                    }
                }

            }
            else {
//                arrPrintWebs($gateData);
                foreach ($gateData as $iGate => $tempGate) {
                    if (!in_array($tempGate['comName'], $validatePostSwap)) {
//                        cekKuning($tempGate['comName']);
                        //----
                        if ($tempGate['comName'] == "LockerValueExternItem") {
                            $selectedFields[99] = "nilai";
                        }
                        //----
                        if (isset($tempGate['static'])) {
                            $tmp = array();
                            foreach ($tempGate['static'] as $rekName => $rekVal) {
                                if (in_array($rekName, $selectedFields)) {
                                    if (substr($rekVal, 0, 1) == ".") {
                                        $rekVal = str_replace(".", "", $rekVal);
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                        $str = "." . $str;
                                    }
                                    else {
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                    }
                                }
                                else {
//                                    if ($rekName == "terbayar") {
                                    if (in_array($rekName, $terbayarFields)) {
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                    }
                                    else {
//                                        if ($rekName == "sisa") {
                                        if (in_array($rekName, $sisaFields)) {
                                            $tmpEx = explode("-", $rekVal);
                                            if (sizeof($tmpEx) > 1) {
                                                $str = str_replace("-", "", $rekVal);
                                            }
                                            else {
                                                $str = $rekVal . "+" . $tempGate['static']['terbayar'];
                                            }
                                        }
                                        else {
                                            cekPink($tempGate['comName'] . " || $rekName => $rekVal");
                                            switch($jenisTr_reference){
                                                case "421":
                                                case "423":
                                                case "5844":
                                                    if(array_key_exists($tempGate['comName'], $selectedFieldsAdd)){
                                                        if(in_array($rekName, $selectedFieldsAdd[$tempGate['comName']])){
                                                            $tmpEx = explode("-", $rekVal);
                                                            if (sizeof($tmpEx) > 1) {
                                                                $str = str_replace("-", "", $rekVal);
                                                            }
                                                            else {
                                                                $str = "-" . $rekVal;
                                                            }
                                                        }
                                                        else{
                                                            $str = $rekVal;
                                                        }
                                                    }
                                                    else{
                                                        $str = $rekVal;
                                                    }
                                                    break;
                                                default:
                                                    $str = $rekVal;
                                                    break;
                                            }
//                                            $str = $rekVal;
                                        }
                                    }
                                }


                                $tmp[$rekName] = $str;
                                if ($rekName == "target_jenis") {
                                    $tmp[$rekName] = "jenisTr_reference";
                                }
                            }
                            $coreConfig[$mainGate][$iGate]["static"] = $tmp;
                            // ================================ ================================ ================================
                            // ================================ ================================ ================================
                            if (!in_array($jenis, $noCoreConfigJenis)) {
                                if (isset($tempGate['static']['state']) && ($tempGate['static']['state'] == ".hold")) {
                                    if (isset($tempGate['static']['jumlah']) && ($tempGate['static']['jumlah'] == "-qty")) {

//                                        if ($jenisSrc != "582spd") {
//                                        switch($jenisSrc) {
//                                            case "582spd":
//                                            case "5822spd":
//
//                                                break;
//                                            default:
//
//                                                break;
//
//                                        }

                                        if (!array_key_exists($jenisSrc, $revertData)) {

                                            $iKeyNum = 1;
                                            $coreConfigAdd[$mainGate][$iKeyNum] = $tempGate;
                                            foreach ($tempGate["static"] as $ikey2 => $ival) {
                                                $strVal = $ival;
                                                $coreConfigAdd[$mainGate][$iKeyNum]["static"][$ikey2] = $strVal;
                                            }


                                            $iKeyNum++;
                                            $coreConfigAdd[$mainGate][$iKeyNum] = $tempGate;
                                            foreach ($tempGate["static"] as $ikey2 => $ival) {
                                                if (in_array($ikey2, $selectedFields)) {
                                                    $tmpEx = explode("-", $ival);
                                                    if (sizeof($tmpEx) > 1) {
                                                        $strVal = str_replace("-", "", $ival);
                                                    }
                                                    else {
                                                        $strVal = "-" . $ival;
                                                    }
                                                }
                                                else {
                                                    $strVal = $ival;
                                                }
                                                $strVal = ($strVal == ".hold") ? ".active" : $strVal;
                                                $strVal = ($ikey2 == "transaksi_id") ? ".0" : $strVal;
                                                $coreConfigAdd[$mainGate][$iKeyNum]["static"][$ikey2] = $strVal;
                                            }

                                        }
                                    }
                                }
                            }
                        }

                        if (isset($tempGate['loop'])) {
                            $tmpLoop = array();
                            foreach ($tempGate['loop'] as $rekName => $rekVal) {
                                if (substr($rekVal, 0, 1) == ".") {
                                    $rekVal = str_replace(".", "", $rekVal);
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                    $str = "." . $str;
                                }
                                else {
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                }

                                $tmpLoop[$rekName] = $str;
                            }
                            $coreConfig[$mainGate][$iGate]["loop"] = $tmpLoop;
                        }
                    }
                    else {
                        unset($coreConfig[$mainGate][$iGate]);
                    }

                }
            }
        }


        if (sizeof($coreConfigAdd) > 0) {
            foreach ($coreConfigAdd as $gate => $gateSpec) {
                foreach ($gateSpec as $gSpec) {
                    $coreConfig[$gate][] = $gSpec;
                }
            }
        }

    }

//    cekKuning($coreConfig);
//    mati_disini(__LINE__);

    return $coreConfig;
}

function fetchRevertPaymentSrc($jenis, $step, $transaksiID)
{
    $ci = &get_instance();
    $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step] : array();
    $coreConfigMisc = isset($ci->config->item("payment_source")[$jenis][$step]) ? $ci->config->item("payment_source")[$jenis][$step] : array();
    if (sizeof($coreConfigMisc) > 0) {
        $reverted = "true";
    }
    else {
        $reverted = "false";
    }

    return $reverted;
}

function fetchRevertPaymentSrcUangMuka($jenis, $step, $transaksiID)
{
    $ci = &get_instance();
    $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step] : array();
    $coreConfigMisc = isset($ci->config->item("uang_muka")[$jenis][$step]) ? $ci->config->item("uang_muka")[$jenis][$step] : array();
    if (sizeof($coreConfigMisc) > 0) {
        $reverted = true;
    }
    else {
        $reverted = false;
    }

    return $reverted;
}

// comFifo dari postprocc
function fetchSwapComFifo($jenis, $step, $component = array(), $jenisSrc = "")
{
    $jenisTr = $jenis;
    $ci = &get_instance();
    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]["detail"]) ? $ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]["detail"] : array();
    }
//    cekUngu($coreConfig);


    $validateComSwap = array(
        "FifoAverage" => "FifoAverage",
//        "FifoProdukJadi" => "FifoProdukJadi_reverse",
        "FifoProdukJadi" => "FifoProdukJadi",
        "FifoProdukJadiRakitan" => "FifoProdukJadiRakitan",

        "FifoAverageSupplies" => "FifoAverageSupplies",
        "FifoSupplies" => "FifoSupplies",

        "FifoSuppliesProduksi" => "FifoSuppliesProduksi",
        "FifoSuppliesProses" => "FifoSuppliesProses",

        "FifoValas" => "FifoValas",
        "FifoValasAverage" => "FifoValasAverage",

        "FifoValasExtern" => "FifoValasExtern",
        "FifoValasExternAverage" => "FifoValasExternAverage",
    );
    $aliasing = array(
        "produk_id" => "extern_id",
        "nama" => "extern_nama",
        "jml" => "produk_qty",
        "unit" => "produk_qty",
        "produk_nama" => "extern_nama",
    );//untuk replacer preprocesor

    $comNameReplacer = array(
        "supplies" => "FifoAverageSupplies",
        "produk" => "FifoAverage",
    );
    $resultParams = array(
        "supplies" => array(
            "FifoAverageSupplies" => array(
                "resultParams" => array(
                    "items" => array(
                        "harga_disc" => "hpp",
                        "hpp" => "hpp",
                        "sub_harga_disc" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        "produk" => array(
            "FifoAverage" => array(
                "resultParams" => array(
                    "items" => array(
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "sub_hpp_nppv" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        "none" => array(
            "FifoProdukJadi" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
            "FifoProdukJadiRakitan" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
            "FifoSupplies" => array(
                "resultParams" => array(
                    "rsltItems" => array(
                        "id" => "produk_id",
                        "nama" => "nama",
                        "name" => "nama",
//                        "harga" => "hpp",
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "jml" => "qty",
                        "qty" => "qty",
                        "subtotal" => "subtotal",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
            "FifoAverage" => array(
                "resultParams" => array(
                    "items" => array(
                        "hpp_nppv" => "hpp",
                        "hpp" => "hpp",
                        "sub_hpp_nppv" => "hpp",
                        "hpp_riil" => "hpp_riil",
                        "ppv_riil" => "ppv_riil",
                    ),
                ),
            ),
        ),
        //------------------------

//        "valas" => array(
//            "FifoValasExternAverage" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//            "FifoValasExtern" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//        ),

        //------------------------

    );
    $replacerParams = array(
        "recalculate" => array(
            "selisih",
            "hpp_nppv",
            "hpp_nppn",
        ),
    );
    $replacerSrcGate = array(
        "srcGateName" => "items",
        "srcRawGateName" => "items",
    );
    $replacerSrcGate_revert = array(
        "srcGateName" => array(
            "rsltItems" => "rsltItems_revert",
            "rsltItems2" => "rsltItems2_revert",
        ),
        "srcRawGateName" => array(
            "rsltItems" => "rsltItems_revert",
            "rsltItems2" => "rsltItems2_revert",
        ),
    );
    $coreSwap = array();
    $staticAdditonal = array(
        "transaksi_id_ref" => "referenceID",
    );
    $fifoValasRevert = array(
        "extern_id" => "pihak2ID",
        "extern_nama" => "pihak2Name",
        "extern2_id" => "pihakID",
        "extern2_nama" => "pihakName",
    );
    foreach ($coreConfig as $iKey => $iVal) {

        // replace srcGateName
        $normal = 0;
        if ($normal == 1) {
            foreach ($replacerSrcGate as $rkey => $rval) {
                $iVal[$rkey] = $rval;
            }
        }
        else {
            if (array_key_exists($iVal["comName"], $validateComSwap)) {
                cekPink("HAHAH HAHAHA MASUK DISINI");
                foreach ($replacerSrcGate_revert as $srcGate => $rSpec) {
                    cekPink($iVal["comName"]);
                    cekPink($srcGate);
                    arrPrintPink($rSpec);
                    if (array_key_exists($iVal["srcGateName"], $rSpec)) {
                        $iVal[$srcGate] = $rSpec[$iVal["srcGateName"]];
                    }
                    if (array_key_exists($iVal["srcRawGateName"], $rSpec)) {
                        $iVal[$srcGate] = $rSpec[$iVal["srcRawGateName"]];
                    }
                }
            }
            else {
                cekHitam("HAHAH HAHAHA MASUK DISINI");
                cekHitam($iVal["comName"]);
                foreach ($replacerSrcGate as $rkey => $rval) {
                    $iVal[$rkey] = $rval;
                }
            }
        }
        //---- mereplace srcGate

        $comName = $iVal["comName"];
        if (array_key_exists($comName, $validateComSwap)) {
            //-------------------------
//            arrPrintPink($iVal);

            switch ($jenisTr) {
                case "460":// return pembelian import
//                case "1985":// return distribusi
//                case "3685":// transfer stock
//                case "585":// penerimaan distribusi
//                case "334":
//                    cekKuning("HEHE :: $comName :: $jenisTr ::");
                    $iVal["comName"] = isset($validateComSwap[$iVal["comName"]]) ? $validateComSwap[$iVal["comName"]] : $iVal["comName"];

                    if (isset($iVal["static"]) && isset($iVal["static"]["jenis"])) {
                        $jenis = str_replace(".", "", $iVal["static"]["jenis"]);
                    }
                    else {
                        $jenis = "none";
                    }

                    // mereplace comName supplies dan produk....
                    if (array_key_exists($jenis, $comNameReplacer)) {
                        $iVal["comName"] = $comNameReplacer[$jenis];
                        //                cekHitam("replace comNam: " . $comNameReplacer[$jenis]);
                    }

                    foreach ($staticAdditonal as $key => $val) {
                        $iVal["static"][$key] = $val;
                    }

                    break;
                default:
                    cekBiru("HEHE :: $comName :: $jenisTr ::");

                    if (isset($iVal["static"]) && isset($iVal["static"]["jenis"])) {
                        $jenis = str_replace(".", "", $iVal["static"]["jenis"]);
                    }
                    else {
                        $jenis = "none";
                    }
                    // mereplace comName supplies dan produk....
                    if (array_key_exists($jenis, $comNameReplacer)) {
                        $iVal["comName"] = $comNameReplacer[$jenis];
//                cekHitam("replace comNam: " . $comNameReplacer[$jenis]);
                    }


                    break;
            }
            //-------------------------
//            arrPrintWebs($iVal);
            if (isset($iVal["static"])) {
                foreach ($iVal["static"] as $sKey => $val) {
                    if (isset($aliasing[$sKey])) {
                        $tKey = $aliasing[$sKey];
                        $iVal["static"][$tKey] = $val;
                        unset($iVal["static"][$sKey]);
                    }
                    else {
                        cekKuning("HAHAAH HOHOHO :: $sKey => $val");
                        $iVal["static"][$sKey] = $val;
                        switch ($jenisSrc) {
                            case "4466":
                                foreach ($fifoValasRevert as $xx => $vv) {
                                    $iVal["static"][$xx] = $vv;
                                }
                                break;
                        }

                    }
                }
            }

//mati_disini("HAAAAAAAAAAAAAA");
//cekHijau("$jenis " . $iVal["comName"]);
            if (isset($resultParams[$jenis][$iVal["comName"]])) {
                $iVal2 = $resultParams[$jenis][$iVal["comName"]];
//                cekHijau("ini");
            }
            else {
                $iVal2 = array();
//                cekHijau("itu");
            }
//cekkuning($iVal["comName"]);
//cekHijau($iVal2);

            $coreSwap["detail"][] = $iVal + $iVal2;


        }
        else {
            unset($coreConfig[$iKey]);
//            cekHitam($iKey);
        }
    }

//-------------------------------
//    arrPrintWebs($coreSwap["detail"]);

    $coreSwap["replacer"] = $replacerParams;


//cekKuning($coreSwap["detail"]);
//matiHere();
    return $coreSwap;

}

function fetchSwapPreFifo($jenis, $step, $component = array(), $jenisTr_reference = "")
{
    // arrPrint($component);
    // matiHere("88");
    $ci = &get_instance();
    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["detail"]) ? $ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["detail"] : array();
    }


    $validateComSwap = array(
        // preProcc (comName) => component (comName)
        "FifoAverage" => array(
            "jenis" => "produk",
            "comName" => "FifoAverage",
        ),
        "FifoProdukJadi" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoProdukJadi",
        ),
        //-----------------------------
        "FifoProdukJadiRakitan" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoProdukJadiRakitan",
        ),
        //-----------------------------
        "FifoAverageSupplies" => array(
            "jenis" => "supplies",
            "comName" => "FifoAverage",
        ),
        "FifoSupplies" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoSupplies",
        ),
        //-----------------------------
        "FifoAverageConvertion" => array(
            "jenis" => "produk",
            "comName" => "FifoAverage",
        ),
        "FifoLogamMulia" => array(
            "jenis" => "logam_mulia",
            "comName" => "FifoLogamMuliaItem",
        ),
    );
    $aliasing = array(
//        "produk_id" => "extern_id",
//        "nama" => "extern_nama",
//        "jml" => "produk_qty",
//        "unit" => "produk_qty",
//        "produk_nama" => "extern_nama",
        //--
        "extern_id" => "produk_id",
        "extern_nama" => "nama",
        "produk_qty" => "unit",
        "extern_nama" => "produk_nama",
    );//untuk replacer preprocesor
    $addStatic = array(
        "hpp" => "hpp",
        "jml_nilai" => "sub_hpp",
        "jml" => "qty",
    );
    $addStaticPPv = array(
        "hpp_riil" => "hpp_riil",
        "jml_nilai_riil" => "sub_hpp_riil",
        "ppv_riil" => "ppv_riil",
        "ppv_nilai_riil" => "sub_ppv_riil",
        "subtotal" => "subtotal",
        "ppn_in" => "ppn_in",
        "ppn_in_nilai" => "ppn_in_nilai",
        "suppliers_id" => "suppliers_id",
        "suppliers_nama" => "suppliers_nama",
    );

    $comNameReplacer = array(
        "supplies" => "FifoAverageSupplies",
        "produk" => "FifoAverage",
    );

    // setup untuk Com Fifo-nya
    $resultParams = array(
        "supplies" => array(
            "FifoAverage" => array(
                "jenis" => ".supplies",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),

        ),
        "produk" => array(
            "FifoAverage" => array(
                "jenis" => ".produk",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
        ),
        "none" => array(
            "FifoSupplies" => array(
                "unit" => "qty",
                "produk_id" => "id",
                "produk_nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
            "FifoProdukJadi" => array(
                "unit" => "qty",
                "produk_id" => "id",
                "produk_nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
        ),
        "logam_mulia" => array(
            "FifoLogamMulia" => array(
                "unit" => "qty",
                "produk_id" => "id",
                "produk_nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
        ),
    );


    $coreSwap = array();
    if (sizeof($coreConfig) > 0) {
        foreach ($coreConfig as $iKey => $iVal) {
            if (array_key_exists($iVal["comName"], $validateComSwap)) {
//            cekKuning("cetak old iVals");
//            arrPrint($iVal);
//            cekBiru($iVal["comName"]);
//            cekBiru("preFifo di swab ke comFifo");

                $comName = isset($validateComSwap[$iVal["comName"]]['comName']) ? $validateComSwap[$iVal["comName"]]['comName'] : "";
                $jenis = isset($validateComSwap[$iVal["comName"]]['jenis']) ? $validateComSwap[$iVal["comName"]]['jenis'] : "none";
//            cekKuning($jenis);
// matiHere($jenis);
                // replacer ComName
                $origComName = $iVal["comName"];
                $iVal["comName"] = $comName;
                // replacer srcGateName dan srcRawGateName
                if (isset($iVal['srcGateName']) || isset($iVal['srcRawGateName'])) {
                    switch ($jenisTr_reference) {
//                        case "582spd":
//                            $iVal['srcGateName'] = "rsltItems_revert";
//                            $iVal['srcRawGateName'] = "rsltItems_revert";
//                            break;
//                        case "967":
//                            $iVal['srcGateName'] = "rsltItems_revert";
//                            $iVal['srcRawGateName'] = "rsltItems_revert";
//                            break;
//                        default:
//                            $iVal['srcGateName'] = "rsltItems";
//                            $iVal['srcRawGateName'] = "rsltItems";
//                            break;
                        case "582spd":
                            $iVal['srcGateName'] = "items";
                            $iVal['srcRawGateName'] = "items";
                            break;
                        case "967":
                            $iVal['srcGateName'] = "items";
                            $iVal['srcRawGateName'] = "items";
                            break;
                        case "1676":
                            $iVal['srcGateName'] = "rsltItems_revert";
                            $iVal['srcRawGateName'] = "rsltItems_revert";
                            break;
                        default:
//                            $iVal['srcGateName'] = "items";
//                            $iVal['srcRawGateName'] = "items";
                            $iVal['srcGateName'] = $iVal['srcGateName'];
                            $iVal['srcRawGateName'] = $iVal['srcRawGateName'];
                            break;
                    }
                }
                // replacer static
//                cekBiru("[$jenis][$comName]");
//                cekBiru($iVal['static']);
//                if (isset($resultParams[$jenis][$comName])) {
//                    foreach ($resultParams[$jenis][$comName] as $key => $val) {
//                    cekPink("$key => $val [$jenis][$comName]");
//                        $iVal['static'][$key] = $val;
//                    }
//                }
//                 arrPrint($iVal["static"]);
//                 matiHere($jenis);
                if (isset($iVal["static"])) {
                    foreach ($iVal["static"] as $sKey => $val) {
                        if (isset($aliasing[$sKey])) {
                            $tKey = $aliasing[$sKey];
                            $iVal["static"][$tKey] = $val;
                            unset($iVal["static"][$sKey]);
                        }
                        else {
                            $iVal["static"][$sKey] = $val;
                        }
                        foreach ($addStatic as $add_key => $add_val) {
                            $iVal["static"][$add_key] = $add_val;
                        }
                    }
                    if (isset($validateComSwap[$iVal["comName"]]['jenis'])) {
//                        $iVal["static"]["jenis"] = "." . $validateComSwap[$iVal["comName"]]['jenis'];
                        $iVal["static"]["jenis"] = "." . $validateComSwap[$origComName]['jenis'];
//                        cekHitam(":::: " . $iVal["static"]["jenis"]);
                    }
                    //-------
                    foreach ($addStaticPPv as $addppv_key => $addppv_val) {
                        $iVal["static"][$addppv_key] = $addppv_val;
                    }
                    //-------
                }
                // unset resultParams
                if (isset($iVal['resultParams'])) {
                    $iVal['resultParams'] = NULL;
                    unset($iVal['resultParams']);
                }
                $coreSwap["detail"][$iKey] = $iVal;
            }
        }
    }


    return $coreSwap;
}

function fetchSwapPreSubFifo($jenis, $step, $component = array(), $jenisTr_reference = "")
{
    // arrPrint($component);
    // matiHere("88");
    cekHere("jenisTr_reference: $jenisTr_reference");

    $ci = &get_instance();
    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["sub_detail"]) ? $ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["sub_detail"] : array();
    }


    $validateComSwap = array(
        // preProcc (comName) => component (comName)
        "FifoAverage" => array(
            "jenis" => "produk",
            "comName" => "FifoAverage",
        ),
        "FifoProdukJadi" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoProdukJadi",
        ),
        //-----------------------------
        "FifoProdukJadiRakitan" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoProdukJadiRakitan",
        ),
        //-----------------------------

        "FifoAverageSupplies" => array(
            "jenis" => "supplies",
            "comName" => "FifoAverage",
        ),
        "FifoSupplies" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoSupplies",
        ),

    );
    $aliasing = array(
//        "produk_id" => "extern_id",
//        "nama" => "extern_nama",
//        "jml" => "produk_qty",
//        "unit" => "produk_qty",
//        "produk_nama" => "extern_nama",
        //--
        "extern_id" => "produk_id",
        "extern_nama" => "nama",
        "produk_qty" => "unit",
        "extern_nama" => "produk_nama",
    );//untuk replacer preprocesor
    $addStatic = array(
        "hpp" => "hpp",
        "jml_nilai" => "sub_hpp",
        "jml" => "qty",
        "hpp_paket" => "hpp_paket",
    );
    $addStaticPPv = array(
        "hpp_riil" => "hpp_riil",
        "jml_nilai_riil" => "sub_hpp_riil",
        "ppv_riil" => "ppv_riil",
        "ppv_nilai_riil" => "sub_ppv_riil",
        "subtotal" => "subtotal",
        "ppn_in" => "ppn_in",
        "ppn_in_nilai" => "ppn_in_nilai",
        "suppliers_id" => "suppliers_id",
        "suppliers_nama" => "suppliers_nama",
        "hpp_riil_paket" => "hpp_riil_paket",
        "ppn_in_paket" => "ppn_in_paket",
        "ppn_in_nilai_paket" => "ppn_in_nilai_paket",
        "suppliers_id_paket" => "suppliers_id_paket",
        "suppliers_nama_paket" => "suppliers_nama_paket",
    );
    $addStaticReplacer = array(
        "hpp" => "hpp_paket",
        "jml_nilai" => "sub_hpp_paket",
    );

    $comNameReplacer = array(
        "supplies" => "FifoAverageSupplies",
        "produk" => "FifoAverage",
    );

    // setup untuk Com Fifo-nya
    $resultParams = array(
        "supplies" => array(
            "FifoAverage" => array(
                "jenis" => ".supplies",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),

        ),
        "produk" => array(
            "FifoAverage" => array(
                "jenis" => ".produk",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
        ),
        "none" => array(
            "FifoSupplies" => array(
                "unit" => "qty",
                "produk_id" => "id",
                "produk_nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
            "FifoProdukJadi" => array(
                "unit" => "qty",
                "produk_id" => "id",
                "produk_nama" => "name",
                "hpp" => "hpp",
                "jml_nilai" => "sub_hpp",
//                "cabang_id" => "placeID",
//                "gudang_id" => "gudangID",
            ),
        ),
    );


    $coreSwap = array();
    if (sizeof($coreConfig) > 0) {
        foreach ($coreConfig as $iKey => $iVal) {
            if (array_key_exists($iVal["comName"], $validateComSwap)) {
//            cekKuning("cetak old iVals");
//            arrPrint($iVal);
//            cekBiru($iVal["comName"]);
//            cekBiru("preFifo di swab ke comFifo");

                $comName = isset($validateComSwap[$iVal["comName"]]['comName']) ? $validateComSwap[$iVal["comName"]]['comName'] : "";
                $jenis = isset($validateComSwap[$iVal["comName"]]['jenis']) ? $validateComSwap[$iVal["comName"]]['jenis'] : "none";
//            cekKuning($jenis);
// matiHere($jenis);
                // replacer ComName
                $iVal["comName"] = $comName;


                // replacer srcGateName dan srcRawGateName
                if (isset($iVal['srcGateName']) || isset($iVal['srcRawGateName'])) {
                    switch ($jenisTr_reference) {
//                        case "582spd":
//                            $iVal['srcGateName'] = "rsltItems_revert";
//                            $iVal['srcRawGateName'] = "rsltItems_revert";
//                            break;
//                        case "967":
//                            $iVal['srcGateName'] = "rsltItems_revert";
//                            $iVal['srcRawGateName'] = "rsltItems_revert";
//                            break;
//                        default:
//                            $iVal['srcGateName'] = "rsltItems";
//                            $iVal['srcRawGateName'] = "rsltItems";
//                            break;
                        case "582spd":
                            $iVal['srcGateName'] = "items";
                            $iVal['srcRawGateName'] = "items";
                            break;
                        case "967":
                            $iVal['srcGateName'] = "items";
                            $iVal['srcRawGateName'] = "items";
                            break;
                        default:
                            $iVal['srcGateName'] = $iVal['srcGateName'];
                            $iVal['srcRawGateName'] = $iVal['srcRawGateName'];
                            break;
                    }
                }

                // replacer static
//                cekBiru("[$jenis][$comName]");
//                cekBiru($iVal['static']);
//                if (isset($resultParams[$jenis][$comName])) {
//                    foreach ($resultParams[$jenis][$comName] as $key => $val) {
//                    cekPink("$key => $val [$jenis][$comName]");
//                        $iVal['static'][$key] = $val;
//                    }
//                }
//                 arrPrint($iVal["static"]);
//                 matiHere($jenis);

                if (isset($iVal["static"])) {
                    foreach ($iVal["static"] as $sKey => $val) {
                        if (isset($aliasing[$sKey])) {
                            $tKey = $aliasing[$sKey];
                            $iVal["static"][$tKey] = $val;
                            unset($iVal["static"][$sKey]);
                        }
                        else {
                            $iVal["static"][$sKey] = $val;
                        }
                        foreach ($addStatic as $add_key => $add_val) {
                            $iVal["static"][$add_key] = $add_val;
                        }
                    }
                    if (isset($validateComSwap[$iVal["comName"]]['jenis'])) {
                        $iVal["static"]["jenis"] = "." . $validateComSwap[$iVal["comName"]]['jenis'];
                    }
                    //-------
                    foreach ($addStaticPPv as $addppv_key => $addppv_val) {
                        $iVal["static"][$addppv_key] = $addppv_val;
                    }
                    //-------
                    switch ($jenisTr_reference) {
                        case "5822spd":
                            foreach ($addStaticReplacer as $add_key => $add_val) {
                                $iVal["static"][$add_key] = $add_val;
                            }
                            break;
                    }
                }

                // unset resultParams
                if (isset($iVal['resultParams'])) {
                    $iVal['resultParams'] = NULL;
                    unset($iVal['resultParams']);
                }
//
//
//            cekUngu("cetak iVal baru");
//            arrPrint($iVal);

                $coreSwap["sub_detail"][$iKey] = $iVal;
            }
        }
    }

    arrPrintHijau($coreSwap);
//    mati_disini(__LINE__);
    return $coreSwap;
}

function fetchSwapPreProc($jenis, $step, $component = array(), $jenisTr_reference = "")
{
    $ci = &get_instance();
    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["detail"]) ? $ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["detail"] : array();
    }

    $validateComSwap = array(

        "LockerStockFreeProduk" => array(
            "jenis" => ".freeproduk",
            "comName" => "LockerStock",
        ),


    );
    $aliasing = array(
        "extern_id" => "produk_id",
        "extern_nama" => "nama",
//        "produk_qty" => "unit",
        "extern_nama" => "nama",
//        "jenis2" => ".freeproduk",

    );//untuk replacer preprocesor
    $addStatic = array(
        "jenis" => ".freeproduk",
        "jenis2" => ".freeproduk",
    );
    $addStaticPPv = array(
        "produk_id" => "produk_rel_id",
        "produk_nama" => "produk_rel_nama",
        "jumlah" => "qty",
//        "hpp_riil" => "hpp_riil",
//        "jml_nilai_riil" => "sub_hpp_riil",
//        "ppv_riil" => "ppv_riil",
//        "ppv_nilai_riil" => "sub_ppv_riil",
//        "subtotal" => "subtotal",
//        "ppn_in" => "ppn_in",
//        "ppn_in_nilai" => "ppn_in_nilai",
//        "suppliers_id" => "suppliers_id",
//        "suppliers_nama" => "suppliers_nama",
    );
    $comNameReplacer = array(
        "supplies" => "FifoAverageSupplies",
        "produk" => "FifoAverage",
    );
    // setup untuk Com Fifo-nya
    $resultParams = array(
//        "supplies" => array(
//            "FifoAverage" => array(
//                "jenis" => ".supplies",
//                "jml" => "qty",
//                "produk_id" => "id",
//                "nama" => "name",
//                "hpp" => "hpp",
//                "jml_nilai" => "sub_hpp",
////                "cabang_id" => "placeID",
////                "gudang_id" => "gudangID",
//            ),
//
//        ),
//        "produk" => array(
//            "FifoAverage" => array(
//                "jenis" => ".produk",
//                "jml" => "qty",
//                "produk_id" => "id",
//                "nama" => "name",
//                "hpp" => "hpp",
//                "jml_nilai" => "sub_hpp",
////                "cabang_id" => "placeID",
////                "gudang_id" => "gudangID",
//            ),
//        ),
//        "none" => array(
//            "FifoSupplies" => array(
//                "unit" => "qty",
//                "produk_id" => "id",
//                "produk_nama" => "name",
//                "hpp" => "hpp",
//                "jml_nilai" => "sub_hpp",
////                "cabang_id" => "placeID",
////                "gudang_id" => "gudangID",
//            ),
//            "FifoProdukJadi" => array(
//                "unit" => "qty",
//                "produk_id" => "id",
//                "produk_nama" => "name",
//                "hpp" => "hpp",
//                "jml_nilai" => "sub_hpp",
////                "cabang_id" => "placeID",
////                "gudang_id" => "gudangID",
//            ),
//        ),
    );

    $coreSwap = array();
    if (sizeof($coreConfig) > 0) {
        foreach ($coreConfig as $iKey => $iVal) {
            if (array_key_exists($iVal["comName"], $validateComSwap)) {

                $comName = isset($validateComSwap[$iVal["comName"]]['comName']) ? $validateComSwap[$iVal["comName"]]['comName'] : "";
                $jenis = isset($validateComSwap[$iVal["comName"]]['jenis']) ? $validateComSwap[$iVal["comName"]]['jenis'] : "none";

                // replacer ComName
                $origComName = $iVal["comName"];
                $iVal["comName"] = $comName;
                // replacer srcGateName dan srcRawGateName
                if (isset($iVal['srcGateName']) || isset($iVal['srcRawGateName'])) {
                    switch ($jenisTr_reference) {
                        case "967":
                            $iVal['srcGateName'] = "items";
                            $iVal['srcRawGateName'] = "items";
                            break;
                        default:
                            $iVal['srcGateName'] = $iVal['srcGateName'];
                            $iVal['srcRawGateName'] = $iVal['srcRawGateName'];
                            break;
                    }
                }

                if (isset($iVal["static"])) {
                    foreach ($iVal["static"] as $sKey => $val) {
                        if (isset($aliasing[$sKey])) {
                            $tKey = $aliasing[$sKey];
                            $iVal["static"][$tKey] = $val;
                            unset($iVal["static"][$sKey]);
                        }
                        else {
                            $iVal["static"][$sKey] = $val;
                        }
                        foreach ($addStatic as $add_key => $add_val) {
                            $iVal["static"][$add_key] = $add_val;
                        }
                    }
                    if (isset($validateComSwap[$iVal["comName"]]['jenis'])) {
                        $iVal["static"]["jenis"] = "." . $validateComSwap[$origComName]['jenis'];
                    }
                    //-------
                    foreach ($addStaticPPv as $addppv_key => $addppv_val) {
                        $iVal["static"][$addppv_key] = $addppv_val;
                    }
                    //-------
                }
                // unset resultParams
                if (isset($iVal['resultParams'])) {
                    $iVal['resultParams'] = NULL;
                    unset($iVal['resultParams']);
                }
                $coreSwap["detail"][$iKey] = $iVal;
            }
        }
    }

    return $coreSwap;
}

//-------------------------------------
function fetchSwapPreFifoMain($jenis, $step, $component = array())
{
    $ci = &get_instance();

    if (sizeof($component) > 0) {
        $coreConfig = $component;
//        cekPink("masuk sini, pakai preprocc registry");
    }
    else {
        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["master"]) ? $ci->config->item("heTransaksi_core")[$jenis]["preProcessor"][$step]["master"] : array();
//        cekPink2("masuk sini, pakai preprocc config");
    }

    cekHitam(__LINE__ . " || cek config core preprocc... [$jenis] [$step]");
//    arrPrint($coreConfig);

    $validateComSwap = array(
        // preProcc (comName) => component (comName)
        //---- stock valas
        "FifoValasAverageMain" => array(
            "jenis" => "valas",
            "comName" => "FifoValasAverage",// sumber dari rsltItems
//            "srcGateName" => "rsltItems",
//            "srcRawGateName" => "rsltItems",
            "srcGateName" => "rsltItems_revert",
            "srcRawGateName" => "rsltItems_revert",
        ),
        "FifoValasMain" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoValas",// sumber dari rsltItems
//            "srcGateName" => "rsltItems",
//            "srcRawGateName" => "rsltItems",
            "srcGateName" => "rsltItems_revert",
            "srcRawGateName" => "rsltItems_revert",
        ),
        //---- uang muka valas
        "FifoValasExternAverageMain" => array(
            "jenis" => "valas",
            "comName" => "FifoValasExternAverage",// sumber dari rsltItems2
            "srcGateName" => "rsltItems2",
            "srcRawGateName" => "rsltItems2",
        ),
        "FifoValasExternMain" => array(
            // fifo real harus none
            "jenis" => "none",
            "comName" => "FifoValasExtern",// sumber dari rsltItems2
            "srcGateName" => "rsltItems2",
            "srcRawGateName" => "rsltItems2",
        ),

    );
    $aliasing = array(
        "produk_id" => "extern_id",
        "nama" => "extern_nama",
        "jml" => "produk_qty",
        "unit" => "produk_qty",
    );//untuk replacer preprocesor

    $comNameReplacer = array();

    // setup untuk Com Fifo-nya
    $resultParams = array(
        "valas" => array(
            // stok valas
            "FifoValasAverage" => array(
                "jenis" => ".valas",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "valas_hpp",
                "jml_nilai" => "sub_valas_hpp",
            ),
//            "FifoValas" => array(
//                "jenis" => ".valas",
//                "jml" => "qty",
//                "produk_id" => "id",
//                "nama" => "name",
//                "hpp" => "valas_hpp",
//                "jml_nilai" => "sub_valas_hpp",
//            ),

            // uang muka valas
            "FifoValasExternAverage" => array(
                "jenis" => ".valas",
                "jml" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "uang_muka_valas_hpp",
                "jml_nilai" => "sub_uang_muka_valas_hpp",
                "extern_id" => "pihakID",
                "extern_nama" => "pihakName",
            ),
//            "FifoValasExtern" => array(
//                "jenis" => ".valas",
//                "jml" => "qty",
//                "produk_id" => "id",
//                "nama" => "name",
//                "hpp" => "uang_muka_valas_hpp",
//                "jml_nilai" => "sub_uang_muka_valas_hpp",
//            ),
        ),
        "none" => array(
            "FifoValas" => array(
                "jenis" => ".valas",
                "jml" => "qty",
                "unit" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "valas_hpp",
                "jml_nilai" => "sub_valas_hpp",
            ),
            "FifoValasExtern" => array(
                "jenis" => ".valas",
                "jml" => "qty",
                "unit" => "qty",
                "produk_id" => "id",
                "nama" => "name",
                "hpp" => "uang_muka_valas_hpp",
                "jml_nilai" => "sub_uang_muka_valas_hpp",
                "extern_id" => "pihakID",
                "extern_nama" => "pihakName",
            ),
        ),
    );
//arrPrintWebs($resultParams);

    $coreSwap = array();
    if (sizeof($coreConfig) > 0) {
        foreach ($coreConfig as $iKey => $iVal) {
            if (array_key_exists($iVal["comName"], $validateComSwap)) {
//            cekKuning("cetak old iVals");
//            arrPrint($iVal);
//            cekBiru($iVal["comName"]);
//            cekBiru("preFifo di swab ke comFifo");
//                arrPrint($validateComSwap[$iVal["comName"]]);
                $comName = isset($validateComSwap[$iVal["comName"]]['comName']) ? $validateComSwap[$iVal["comName"]]['comName'] : "";
                $jenis = isset($validateComSwap[$iVal["comName"]]['jenis']) ? $validateComSwap[$iVal["comName"]]['jenis'] : "none";
                cekKuning(":: $jenis === $comName ::");

                // replacer srcGateName dan srcRawGateName
                $iVal['srcGateName'] = isset($validateComSwap[$iVal["comName"]]['srcGateName']) ? $validateComSwap[$iVal["comName"]]['srcGateName'] : "";;
                $iVal['srcRawGateName'] = isset($validateComSwap[$iVal["comName"]]['srcRawGateName']) ? $validateComSwap[$iVal["comName"]]['srcRawGateName'] : "";;
//                if (isset($iVal['srcGateName']) || isset($iVal['srcRawGateName'])) {
//                    $iVal['srcGateName'] = "rsltItems";
//                    $iVal['srcRawGateName'] = "rsltItems";
//                }

                // replacer ComName
                $iVal["comName"] = $comName;


                // replacer static
                if (isset($resultParams[$jenis][$comName])) {
//                    cekHitam("replace static: $comName");
                    foreach ($resultParams[$jenis][$comName] as $key => $val) {
//                    cekPink("$key => $val");
                        $iVal['static'][$key] = $val;
                    }
                }

                // unset resultParams
                if (isset($iVal['resultParams'])) {
                    $iVal['resultParams'] = NULL;
                    unset($iVal['resultParams']);
                }
//
//
//            cekUngu("cetak iVal baru");
//            arrPrint($iVal);

                $coreSwap["detail"][$iKey] = $iVal;
            }
        }
    }

//
//cekPink2("cetak coreswap " . __FUNCTION__);
arrPrintWebs($coreSwap);
//mati_disini();

    return $coreSwap;
}

function validateBalancesComparisonOLD($cab = "", $componentConfig)
{
    $pakai_ini = 0;
    if ($pakai_ini == 1) {

        $ci = &get_instance();
        $ci->load->model("Coms/ComRekening");
        $accountChilds = $ci->config->item("accountChilds");
        $accountAktiva = $ci->config->item("accountStructure")["aktiva"];
        $accountHutang = $ci->config->item("accountStructure")["hutang"];
        $accountCheck = array_merge($accountAktiva, $accountHutang);

        if ($cab == "") {
            $cab = $ci->session->login['cabang_id'];
        }
        if (sizeof($componentConfig) > 0) {
            foreach ($componentConfig as $key => $cSpec) {
                if (sizeof($cSpec) > 0) {
                    foreach ($cSpec as $ccSpec) {
                        if (isset($ccSpec['loop']) && sizeof($ccSpec['loop']) > 0) {
                            foreach ($ccSpec['loop'] as $rek => $val) {
//                            $arrRekening[$key][$rek] = $val;
                                $arrRekening[$rek] = $val;
                            }
                        }
                    }
                }
            }

            if (sizeof($arrRekening) > 0) {

                $rm = New ComRekening();
                $rm->setFilters(array());
                $rm->addFilter("periode='forever'");
                $rm->addFilter("cabang_id='$cab'");
                $tmpMaster = $rm->fetchAllBalances();
                $arrMaster_sum = array();
                if (sizeof($tmpMaster) > 0) {
                    foreach ($tmpMaster as $tmpSpec) {
                        $value = $tmpSpec['debet'] > 0 ? $tmpSpec['debet'] : $tmpSpec['kredit'];
                        $arrMaster_sum[$tmpSpec['rekening']] = $value;
                    }
                }

                foreach ($arrRekening as $rek => $ii) {
                    if (array_key_exists($rek, $accountChilds) && in_array($rek, $accountCheck)) {
                        $comName = "Com" . $accountChilds[$rek];

                        $ci->load->model("Coms/$comName");
                        $default_position = detectRekDefaultPosition($rek);

                        $c = New $comName();
                        $c->setFilters(array());
                        $c->addFilter("cabang_id='$cab'");
                        $c->addFilter("rekening='$rek'");
                        $c->addFilter("periode='forever'");

//                    cekHitam("mulasi cek $comName");
                        $tmpDetail = $c->lookupAll()->result();
//                    cekHitam($comName . " :: " . $ci->db->last_query());
//                    arrprint($tmpDetail);
                        $detail_sum = 0;
                        if (sizeof($tmpDetail) > 0) {
                            foreach ($tmpDetail as $tmpSpec) {
                                $detail_sum += $tmpSpec->$default_position;
                            }
                        }
//                        $master_sum = isset($arrMaster_sum[$rek]) ? $arrMaster_sum[$rek] : 0;
//                    cekHitam("total $rek: (detail -> $detail_sum) :: (master -> $master_sum)");
                        if (floor($arrMaster_sum[$rek]) != floor($detail_sum)) {
                            cekOrange("MAIN " . floor($arrMaster_sum[$rek]));
                            cekOrange("DETAIL " . floor($detail_sum));
                            $msg = "transaksi gagal disimpan karena rekening $rek buku besar dan pembantu tidak sama. Silahkan cek ulang transaksi ini.";
                            mati_disini($msg);
                            die(lgShowAlert($msg));
                        }
                    }
                }

            }
        }

    }

}

function validateBalancesComparison($cab = "", $componentGate, $componentConfig, $mode = "master", $trID, $trNomer) // cabangID, componentGate, componentConfig, mode(master/detail)
{
    $pakai_ini = 1;
    if ($pakai_ini == 1) {
        $arrCabangID = array();
        $arrStaticKey = array("cabang_id", "cabang2_id");

        cekMerah(":: cetak component config :: dapatnya hanya MASTER atau DETAIL ::");
//        arrPrintWebs($componentConfig);


        $ci = &get_instance();
        $ci->load->model("Coms/ComRekening");
        $accountChilds = $ci->config->item("accountChilds");
        $accountAktiva = $ci->config->item("accountStructure")["aktiva"];
        $accountHutang = $ci->config->item("accountStructure")["hutang"];
        $accountRekeningWhitelist = $ci->config->item("accountRekeningBypass");
        $accountCheck = array_merge($accountAktiva, $accountHutang);
        // baypass validasi rekening besar dan rekening pembantunya...
        foreach ($accountCheck as $ii => $rekName) {
            if (in_array($rekName, $accountRekeningWhitelist)) {
                unset($accountCheck[$ii]);
            }
        }


        if (sizeof($componentGate) > 0) {
            $arrRekeningGateMode = array();
            foreach ($componentGate as $key => $cSpec) {
                if (sizeof($cSpec) > 0) {
                    foreach ($cSpec as $ccSpec) {
                        if (isset($ccSpec['loop']) && sizeof($ccSpec['loop']) > 0) {
                            foreach ($ccSpec['loop'] as $rek => $val) {
                                $arrRekeningGateMode[$key][$rek] = $val;
                            }
                        }

                        if (isset($ccSpec['static']) && sizeof($ccSpec['static']) > 0) {
                            foreach ($ccSpec['static'] as $rek => $val) {
                                foreach ($arrStaticKey as $sKey) {
                                    if ($sKey == $rek) {
                                        $arrCabangID[$val] = $val;
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $arrCabangID[$cab] = $cab;
            if (sizeof($arrCabangID) == 0) {
                mati_disini("validasi tidak bisa dilakukan karena cabangID tidak ditemukan.");
            }


            $arrRekeningConfigMode = array();
            foreach ($componentConfig as $key => $cSpec) {
                if (sizeof($cSpec) > 0) {
                    foreach ($cSpec as $ccSpec) {
//                        arrPrintWebs($ccSpec['static']);
                        if (isset($ccSpec['loop']) && sizeof($ccSpec['loop']) > 0) {
                            foreach ($ccSpec['loop'] as $rek => $val) {
                                $arrRekeningConfigMode[$key][$rek] = $val;
                            }
                        }
                    }
                }
            }

//
//            arrPrintWebs($componentConfig);
//            arrPrintWebs($arrRekeningGateMode);
//            arrPrintWebs($arrRekeningConfigMode);
//            arrPrintWebs($arrCabangID);
//            mati_disini($mode);

            if (sizeof($arrRekeningGateMode) > 0) {

                switch ($mode) {
                    case "master":

                        cekPink(":: validasi $mode ::");
                        $rekeningDetail = isset($arrRekeningConfigMode['detail']) ? $arrRekeningConfigMode['detail'] : array();
                        foreach ($arrRekeningGateMode[$mode] as $key => $val) {
                            if (!array_key_exists($key, $rekeningDetail)) {

                                $arrRekening[$key] = $val;
                            }
                        }

                        break;
                    case "detail":

                        cekPink(":: validasi $mode ::");
                        $rekeningMaster = isset($arrRekeningConfigMode['master']) ? $arrRekeningConfigMode['master'] : array();
                        foreach ($arrRekeningGateMode[$mode] as $key => $val) {
                            if (array_key_exists($key, $rekeningMaster)) {

                                $arrRekening[$key] = $val;
                            }
                        }


                        break;
                    default:
                        $msg = "validasi gagal karena mode validasi tidak ditemukan.";
                        mati_disini($msg);
                        break;
                }

                $arrMaster_sum = array();
                foreach ($arrCabangID as $cab) {

                    $rm = New ComRekening();
                    $rm->setFilters(array());
                    $rm->addFilter("periode='forever'");
                    $rm->addFilter("cabang_id='$cab'");
                    $tmpMaster = $rm->fetchAllBalances();
                    if (sizeof($tmpMaster) > 0) {
                        foreach ($tmpMaster as $tmpSpec) {
                            $value = $tmpSpec['debet'] > 0 ? $tmpSpec['debet'] : $tmpSpec['kredit'];
                            $arrMaster_sum[$cab][$tmpSpec['rekening']] = $value;
                        }
                    }


                }


//                arrPrintWebs($arrRekening);
//                arrPrintWebs($arrMaster_sum);
//                mati_disini();
                foreach ($arrCabangID as $cab) {

                    foreach ($arrRekening as $rek => $ii) {
                        if (array_key_exists($rek, $accountChilds) && in_array($rek, $accountCheck)) {

                            $comName = "Com" . $accountChilds[$rek];

//                            cekKuning("ada rekening yang divalidasi yaitu $rek pada $comName");

                            $ci->load->model("Coms/$comName");
                            $default_position = detectRekDefaultPosition($rek);
                            $opposite_position = $default_position == "debet" ? "kredit" : "debet";

                            $c = New $comName();
                            $c->setFilters(array());
                            $c->addFilter("cabang_id='$cab'");
//                            $c->addFilter("rekening='$rek'");
                            $c->addFilter("periode='forever'");
//                            $tmpDetail = $c->lookupAll()->result();
                            $tmpDetail = $c->fetchBalances($rek);
                            $detail_sum = 0;
                            if (sizeof($tmpDetail) > 0) {
                                foreach ($tmpDetail as $tmpSpec) {
                                    $value = $tmpSpec->$default_position > 0 ? $tmpSpec->$default_position : $tmpSpec->$opposite_position * -1;
                                    $detail_sum += $value;
                                }
                            }

//                            cekKuning(":: cek validasi rekening $rek cabang $cab selesai");

                            if (floor($arrMaster_sum[$cab][$rek]) != floor($detail_sum)) {
                                cekOrange("MAIN " . floor($arrMaster_sum[$cab][$rek]));
                                cekOrange("DETAIL " . floor($detail_sum));

                                $main_value = floor($arrMaster_sum[$cab][$rek]);
                                $detail_value = floor($detail_sum);
                                $selisih = $arrMaster_sum[$cab][$rek] - $detail_sum;
                                $msg = "transaksi gagal disimpan karena rekening $rek buku besar dan pembantu tidak sama. Silahkan cek ulang transaksi ini. ";
                                $msg .= "cabang $cab, main: $main_value, detail: $detail_value selisih: $selisih";
//                                mati_disini($msg);
//                                die(lgShowAlert($msg));
                                $data = array(
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "date" => date("Y-m-d"),
                                    "time" => date("H:i:s"),
                                    "jenis" => "rekening",
                                    "ipadd" => $_SERVER['REMOTE_ADDR'],
                                    "msg" => $msg,
                                    "transaksi_id" => $trID,
                                    "nomer" => $trNomer,
                                    "rekening" => $rek,
                                    "mode" => $mode,
                                    "cabang_id" => $cab,
                                );
                                unbalanceNotif($trID, $mode, $data);
                            }
                            else {
                                cekHijau("rekening besar vs pembantu BALANCE");
                            }
                        }
                    }
                    cekOrange(":: cek validasi rekening cabang $cab selesai");
                }

            }
        }

//        mati_disini(__FUNCTION__ . " == DONE");
    }

}

function validateJurnal($transaksiID, $componentConfig, $targetJenisTr = "")
{
    $pakai_ini = 1;
    if ($pakai_ini == 1) {
        $ci = &get_instance();
        $arrComJurnal = array(
            "Jurnal", "JurnalItem", "JurnalValuesItem",
        );
        $cekJurnalDB = false;
        $cekComponent = true;

        switch ($targetJenisTr) {
            case "467":
            case "1467":
                $cekComponent = true;
                break;
            default:
                $cekComponent = false;
                break;
        }

        if ($cekComponent == true) {

            if (isset($componentConfig) && (sizeof($componentConfig) > 0)) {
                if (isset($componentConfig['master'])) {
                    foreach ($componentConfig['master'] as $specs) {
                        if (in_array($specs['comName'], $arrComJurnal)) {
                            $cekJurnalDB = true;
                            break;
                        }
                    }
                }
            }

        }

        $msg = array();
        if ($cekJurnalDB == true) {
            $ci->load->model("Coms/ComJurnal");
            $jj = New ComJurnal();
            $jj->addFilter("transaksi_id='$transaksiID'");
            $jjTmp = $jj->lookupAll()->result();
//            showLast_query("biru");
//            $jjTmp = array();
            if (sizeof($jjTmp) > 0) {
                $jjTotal = array();
                foreach ($jjTmp as $jjSpec) {
                    if (!isset($jjTotal[$jjSpec->cabang_id]['debet'])) {
                        $jjTotal[$jjSpec->cabang_id]['debet'] = 0;
                    }
                    $jjTotal[$jjSpec->cabang_id]['debet'] += $jjSpec->debet;

                    if (!isset($jjTotal[$jjSpec->cabang_id]['kredit'])) {
                        $jjTotal[$jjSpec->cabang_id]['kredit'] = 0;
                    }
                    $jjTotal[$jjSpec->cabang_id]['kredit'] += $jjSpec->kredit;

                }
//                arrPrintKuning($jjTotal);
                if (sizeof($jjTotal) > 0) {
                    foreach ($jjTotal as $cabangID => $spec) {
                        $totalDebet = $spec['debet'];
                        $totalKredit = $spec['kredit'];
//                        cekHere("[debet] = $totalDebet, [kredit] = $totalKredit");
                        if ((round($totalDebet, 2) == 0) && (round($totalKredit, 2) == 0)) {
                            $msg[] = "Transaksi gagal disimpan karena penjurnalan bernilai 0. <span style='color:transparent;font-size: 12px;'>cabang $cabangID</span>";
                        }
                    }
                }
            }
            else {
                $msg[] = "Transaksi gagal disimpan karena gagal melakukan penjurnalan. <br><span style='color:transparent;font-size: 12px;'>" . __FUNCTION__ . "</span>";
            }
        }

        //----------
        if (sizeof($msg) > 0) {
            $msg_msg = implode("<br>", $msg);
            echo lgShowAlert($msg_msg);
            die();
        }
    }
}

function previewJurnalModul($jenis, $configUi, $configCore)
{
    $ci = &get_instance();
    $cCode = "_TR_" . $jenis;

//    $accountAlias = $ci->config->item("accountAlias") != null ? $ci->config->item("accountAlias") : array();
    $accountAlias = fetchAccountStructureAlias();

    $previewJurnalConfig = $configUi["previewJurnal"];

    $jurnalConfig = $configCore["components"][$jenis]["master"];

    $ci->load->model("Mdls/MdlCabang");
    $cb = New MdlCabang();
    $cbTmp = $cb->lookupAll()->result();
    $arrCabang = array();
    if (sizeof($cbTmp) > 0) {
        foreach ($cbTmp as $cbSpec) {
            $arrCabang[$cbSpec->id] = $cbSpec->nama;
        }
    }

    $arrJurnalResult = array();
    if (sizeof($jurnalConfig) > 0) {

        $lCounter = 0;
        foreach ($jurnalConfig as $i => $jSpec) {
            $comName = $jSpec['comName'];
            $srcGateName = $jSpec['srcGateName'];
            $srcRawGateName = $jSpec['srcRawGateName'];

            if (substr($comName, 0, 1) == "{") {
                $comName = trim($comName, "{");
                $comName = trim($comName, "}");
                $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
            }

            if ($comName == $previewJurnalConfig["comName"]) {
                //                cekOrange(":: $comName ::");
                foreach ($jSpec["loop"] as $rek => $value) {
                    //                    cekOrange("$rek");
                    $lCounter++;
                    if (substr($rek, 0, 1) == "{") {
                        $rek = trim($rek, "{");
                        $rek = trim($rek, "}");
                        if (isset($_SESSION[$cCode]['main'][$rek])) {
                            $rek = str_replace($rek, $_SESSION[$cCode]['main'][$rek], $rek);
                        }
                        else {
                            $rek = NULL;
                        }
                    }
                    if ($rek != NULL) {

                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        $position = detectRekPosition($rek, $realValue);
                        $rek_f = isset($accountAlias[$rek]) ? $accountAlias[$rek] : $rek;

                        if (!isset($arrJurnal[$lCounter])) {
                            $arrJurnal[$lCounter] = array();
                        }
                        if ($position == "debet") {
                            $arrJurnal[$lCounter]["kredit"] = 0;
                        }
                        elseif ($position == "kredit") {
                            $arrJurnal[$lCounter]["debet"] = 0;
                        }
                        else {

                        }
                        $arrJurnal[$lCounter][$position] = abs($realValue);
                        //                        cekHere("$rek => $position => $realValue");
                        $arrJurnal[$lCounter]['rekening'] = (isset($arrJurnal[$lCounter]["debet"]) && ($arrJurnal[$lCounter]["debet"] > 0)) ? $rek_f : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rek_f;
                        foreach ($jSpec['static'] as $key => $value_static) {
                            $arrJurnal[$lCounter][$key] = makeValue($value_static, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        }
                    }
                }
            }
        }
        //arrPrintWebs($arrJurnal);
        if (sizeof($arrJurnal) > 0) {
            $arrDebet = array();
            $arrKredit = array();
            foreach ($arrJurnal as $ii => $jSpec) {
                $jSpec['cabang_nama'] = isset($arrCabang[$jSpec['cabang_id']]) ? $arrCabang[$jSpec['cabang_id']] : "";
                if (isset($jSpec['debet']) && ($jSpec['debet'] > 0)) {
                    $arrDebet[] = $jSpec;
                }
                else {
                    $arrKredit[] = $jSpec;
                }
            }
            $arrJurnalTmp = array_merge($arrDebet, $arrKredit);
            foreach ($arrJurnalTmp as $ii => $tmpSpec) {
                if ((isset($tmpSpec['debet']) && ($tmpSpec['debet'] > 0)) || (isset($tmpSpec['kredit']) && ($tmpSpec['kredit'] > 0))) {

                    $arrJurnalResult[$tmpSpec['cabang_id']][] = $tmpSpec;
                }
            }
        }
    }
    //     arrPrint($arrJurnalResult);
    // matiHere("uhuk ".$jenis);
    $result = array(
        "jurnal" => $arrJurnalResult,
        "cabang" => $arrCabang,
    );

    return $result;
}

function previewJurnal($jenis)
{
    $ci = &get_instance();
    $cCode = "_TR_" . $jenis;

    $accountAlias = $ci->config->item("accountAlias") != null ? $ci->config->item("accountAlias") : array();
    $previewJurnalConfig = isset($ci->config->item("heTransaksi_ui")[$jenis]["previewJurnal"]) ? $ci->config->item("heTransaksi_ui")[$jenis]["previewJurnal"] : array();
    $jurnalConfig = isset($_SESSION[$cCode][$previewJurnalConfig["src"]]["jurnal"][$previewJurnalConfig["mainGate"]]) ? $_SESSION[$cCode][$previewJurnalConfig["src"]]["jurnal"][$previewJurnalConfig["mainGate"]] : array();

    $ci->load->model("Mdls/MdlCabang");
    $cb = New MdlCabang();
    $cbTmp = $cb->lookupAll()->result();
    $arrCabang = array();
    if (sizeof($cbTmp) > 0) {
        foreach ($cbTmp as $cbSpec) {
            $arrCabang[$cbSpec->id] = $cbSpec->nama;
        }
    }

    $arrJurnalResult = array();
    if (sizeof($jurnalConfig) > 0) {

        $lCounter = 0;
        foreach ($jurnalConfig as $i => $jSpec) {
            $comName = $jSpec['comName'];
            $srcGateName = $jSpec['srcGateName'];
            $srcRawGateName = $jSpec['srcRawGateName'];

            if (substr($comName, 0, 1) == "{") {
                $comName = trim($comName, "{");
                $comName = trim($comName, "}");
                $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
            }

            if ($comName == $previewJurnalConfig["comName"]) {
//                cekOrange(":: $comName ::");
                foreach ($jSpec["loop"] as $rek => $value) {
//                    cekOrange("$rek");
                    $lCounter++;
                    if (substr($rek, 0, 1) == "{") {
                        $rek = trim($rek, "{");
                        $rek = trim($rek, "}");
                        if (isset($_SESSION[$cCode]['main'][$rek])) {
                            $rek = str_replace($rek, $_SESSION[$cCode]['main'][$rek], $rek);
                        }
                        else {
                            $rek = NULL;
                        }
                    }
                    if ($rek != NULL) {

                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        $position = detectRekPosition($rek, $realValue);
                        $rek_f = isset($accountAlias[$rek]) ? $accountAlias[$rek] : $rek;

                        if (!isset($arrJurnal[$lCounter])) {
                            $arrJurnal[$lCounter] = array();
                        }
                        if ($position == "debet") {
                            $arrJurnal[$lCounter]["kredit"] = 0;
                        }
                        elseif ($position == "kredit") {
                            $arrJurnal[$lCounter]["debet"] = 0;
                        }
                        else {

                        }
                        $arrJurnal[$lCounter][$position] = abs($realValue);
//                        cekHere("$rek => $position => $realValue");
                        $arrJurnal[$lCounter]['rekening'] = (isset($arrJurnal[$lCounter]["debet"]) && ($arrJurnal[$lCounter]["debet"] > 0)) ? $rek_f : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rek_f;
                        foreach ($jSpec['static'] as $key => $value_static) {
                            $arrJurnal[$lCounter][$key] = makeValue($value_static, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        }
                    }
                }
            }
        }
//arrPrintWebs($arrJurnal);
        if (sizeof($arrJurnal) > 0) {
            foreach ($arrJurnal as $ii => $jSpec) {
                $jSpec['cabang_nama'] = isset($arrCabang[$jSpec['cabang_id']]) ? $arrCabang[$jSpec['cabang_id']] : "";
                if (isset($jSpec['debet']) && ($jSpec['debet'] > 0)) {
                    $arrDebet[] = $jSpec;
                }
                else {
                    $arrKredit[] = $jSpec;
                }
            }
            $arrJurnalTmp = array_merge($arrDebet, $arrKredit);
            foreach ($arrJurnalTmp as $ii => $tmpSpec) {
                if ((isset($tmpSpec['debet']) && ($tmpSpec['debet'] > 0)) || (isset($tmpSpec['kredit']) && ($tmpSpec['kredit'] > 0))) {

                    $arrJurnalResult[$tmpSpec['cabang_id']][] = $tmpSpec;
                }
            }
        }
    }

    $result = array(
        "jurnal" => $arrJurnalResult,
        "cabang" => $arrCabang,
    );

    return $result;
}

function previewJurnal_he_accounting($jenis, $configUiJenis)
{
    $ci = &get_instance();
    $cCode = "_TR_" . $jenis;

//    $accountAlias = $ci->config->item("accountAlias") != null ? $ci->config->item("accountAlias") : array();
    $accountAlias = fetchAccountStructureAlias();
    $previewJurnalConfig = isset($configUiJenis["previewJurnal"]) ? $configUiJenis["previewJurnal"] : array();
    $jurnalConfig = isset($_SESSION[$cCode][$previewJurnalConfig["src"]]["jurnal"][$previewJurnalConfig["mainGate"]]) ? $_SESSION[$cCode][$previewJurnalConfig["src"]]["jurnal"][$previewJurnalConfig["mainGate"]] : array();
//    arrPrintPink($jurnalConfig);
    $ci->load->model("Mdls/MdlCabang");
    $cb = New MdlCabang();
    $cbTmp = $cb->lookupAll()->result();
    $arrCabang = array();
    if (sizeof($cbTmp) > 0) {
        foreach ($cbTmp as $cbSpec) {
            $arrCabang[$cbSpec->id] = $cbSpec->nama;
        }
    }

    $arrJurnalResult = array();
    if (sizeof($jurnalConfig) > 0) {

        $lCounter = 0;
        foreach ($jurnalConfig as $i => $jSpec) {
            $comName = $jSpec['comName'];
            $srcGateName = $jSpec['srcGateName'];
            $srcRawGateName = $jSpec['srcRawGateName'];

            if (substr($comName, 0, 1) == "{") {
                $comName = trim($comName, "{");
                $comName = trim($comName, "}");
                $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
            }

            if ($comName == $previewJurnalConfig["comName"]) {
                //                cekOrange(":: $comName ::");
                foreach ($jSpec["loop"] as $rek => $value) {
//                    cekOrange("$rek");
                    $lCounter++;
                    if (substr($rek, 0, 1) == "{") {
                        $rek = trim($rek, "{");
                        $rek = trim($rek, "}");
                        if (isset($_SESSION[$cCode]['main'][$rek])) {
                            $rek = str_replace($rek, $_SESSION[$cCode]['main'][$rek], $rek);
                        }
                        else {
                            $rek = NULL;
                        }
                    }
                    if ($rek != NULL) {

                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        //----------
                        if (substr($realValue, 0, 2) == "--") {
                            $realValue = str_replace("--", "+", $realValue);
                        }
                        //----------
//                        cekKuning(":: $rek :: $value :: $realValue ::");
                        $position = detectRekPosition($rek, $realValue);
                        $rek_f = isset($accountAlias[$rek]) ? $accountAlias[$rek] : $rek;

                        if (!isset($arrJurnal[$lCounter])) {
                            $arrJurnal[$lCounter] = array();
                        }
                        if ($position == "debet") {
                            $arrJurnal[$lCounter]["kredit"] = 0;
                        }
                        elseif ($position == "kredit") {
                            $arrJurnal[$lCounter]["debet"] = 0;
                        }
                        else {

                        }
                        $arrJurnal[$lCounter][$position] = abs($realValue);
                        $arrJurnal[$lCounter]['rekening'] = (isset($arrJurnal[$lCounter]["debet"]) && ($arrJurnal[$lCounter]["debet"] > 0)) ? $rek_f : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rek_f;
                        foreach ($jSpec['static'] as $key => $value_static) {
                            $arrJurnal[$lCounter][$key] = makeValue($value_static, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                        }
                    }
                }
            }
        }
        //arrPrintWebs($arrJurnal);
        if (sizeof($arrJurnal) > 0) {
            foreach ($arrJurnal as $ii => $jSpec) {
                $jSpec['cabang_nama'] = isset($arrCabang[$jSpec['cabang_id']]) ? $arrCabang[$jSpec['cabang_id']] : "";
                if (isset($jSpec['debet']) && ($jSpec['debet'] > 0)) {
                    $arrDebet[] = $jSpec;
                }
                else {
                    $arrKredit[] = $jSpec;
                }
            }
            $arrJurnalTmp = array_merge($arrDebet, $arrKredit);
            foreach ($arrJurnalTmp as $ii => $tmpSpec) {
                if ((isset($tmpSpec['debet']) && ($tmpSpec['debet'] > 0)) || (isset($tmpSpec['kredit']) && ($tmpSpec['kredit'] > 0))) {

                    $arrJurnalResult[$tmpSpec['cabang_id']][] = $tmpSpec;
                }
            }
        }
    }

//arrPrintPink($arrJurnalResult);
    $result = array(
        "jurnal" => $arrJurnalResult,
        "cabang" => $arrCabang,
    );

    return $result;
}

function rekening_coa_he_accounting($rekening_key = "")
{
    $ci = &get_instance();

    $ci->load->model("Mdls/MdlAccounts");
    $co = new MdlAccounts();
    $srcs = $co->get_userlist();
    foreach ($srcs as $src) {
        if (isset($src->rekening)) {
            $vars[$src->rekening] = $src->head_code;
        }
    }
    return $vars;
}


//ambil data rekening dari tabel acc_coa
function fetchAccountStructureAlias()
{

    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();
    $ac->addFilter("is_rekening_pembantu='0'");
    $prefData = $ac->lookUpTransactionStructureLabel();
    return $prefData;

}

function fetchAccountStructureAlias_old()
{

    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $ac = new MdlAccounts();
    $ac->addFilter("is_rekening_pembantu='0'");
    $prefData = $ac->lookUpTransactionStructureLabel_old();
    return $prefData;

}

function rekening_coa_sort_he_accounting($rekening_key = "")
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccounts");
    $co = new MdlAccounts();
    $srcs = $co->lookUpTransactionStructure();

    return $srcs;

}

function rekening_neraca_coa_sort_he_accounting($rekening_key = "")
{
    $ci = &get_instance();
    $masterLabel = fetchAccountStructureAlias();
    $arrLabel = array("aktiva", "hutang", "modal");
    $ci->load->model("Mdls/MdlAccounts");
    $co = new MdlAccounts();
    $srcs = $co->lookUpTransactionStructure();
    $groupNeraca = array();
    foreach ($arrLabel as $src_key) {
        if (isset($srcs[$src_key])) {
            $groupNeraca[$src_key] = $srcs[$src_key];
        }
    }
    return $groupNeraca;


}

//-----------------------
function fetchRekeningSerialNumberSmartSearch($serial_number, $cabang_id, $gudang_id)
{
    $ci = &get_instance();
    $ci->load->model("Coms/ComRekeningPembantuProdukPerSerial");
    $ac = new ComRekeningPembantuProdukPerSerial();
    // $ac->addFilter("extern_nama='$serial_number'");
    $targetKoloms = array(
        "extern_nama",
        "extern3_nama",
    );
    $ac->createSmartSearch($serial_number, $targetKoloms);
    $ac->addFilter("cabang_id='$cabang_id'");
    $ac->addFilter("gudang_id='$gudang_id'");
    $ac->addFilter("qty_debet>0");
    $acTmp = $ac->lookupAll()->result();
    if (sizeof($acTmp) > 0) {
        $hasil = true;// serial ada, jadi bisa dipakai
    }
    else {
        $hasil = false;// serial tidak ada, jadi tidak bisa dipakai
    }
    return $hasil;
}

function fetchRekeningSerialNumber($serial_number, $cabang_id, $gudang_id)
{
    $ci = &get_instance();
    $ci->load->model("Coms/ComRekeningPembantuProdukPerSerial");
    $ac = new ComRekeningPembantuProdukPerSerial();
    // $ac->addFilter("extern_nama='$serial_number'");
    $targetKoloms = array(
        "extern_nama",
        "extern3_nama",
    );
//    $ac->createSmartSearch($serial_number, $targetKoloms);
//    $this->db->or_where('extern_nama', $serial_number);
//    $this->db->or_where('extern3_nama', $serial_number);
    $ac->addFilter("extern_nama='$serial_number'");
    $ac->addFilter("cabang_id='$cabang_id'");
    $ac->addFilter("gudang_id='$gudang_id'");
    $ac->addFilter("qty_debet>0");
    $acTmp = $ac->lookupAll()->result();
    if (sizeof($acTmp) > 0) {
        $hasil = true;// serial ada, jadi bisa dipakai
    }
    else {
        $hasil = false;// serial tidak ada, jadi tidak bisa dipakai
    }
    return $hasil;
}

function fetchReferenceSerialNumber($scode, $arr_serial_1, $arr_serial_2)
{
    $acTmp = array();
    $scode = trim($scode);
    if (sizeof($arr_serial_1) > 0) {
        foreach ($arr_serial_1 as $sSpec) {
            if ($scode == $sSpec["serial_number"]) {
                $acTmp[] = $sSpec;
                break;
            }
        }
    }
    if (sizeof($arr_serial_2) > 0) {
        foreach ($arr_serial_2 as $sSpec) {
            if ($scode == $sSpec["serial_number"]) {
                $acTmp[] = $sSpec;
                break;
            }
        }
    }
    arrPrintHitam($acTmp);
    if (sizeof($acTmp) > 0) {
        $hasil = true;// serial ada, jadi bisa dipakai
    }
    else {
        $hasil = false;// serial tidak ada, jadi tidak bisa dipakai
    }
    return $hasil;
}


//-----------------------
function fetchRevertJurnalAuto($jenis, $step, $component = array(), $jenisTr_reference = "")
{
    $ci = &get_instance();

    if (sizeof($component) > 0) {
        $coreConfig = $component;
    }
//    else {
//        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["components"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["components"][$step] : array();
//    }
//

    $mainGateReplacer = isset($ci->config->item("heTransaksi_revertMainGateReplacer")[$jenis]) ? $ci->config->item("heTransaksi_revertMainGateReplacer")[$jenis] : array();

    $coreLockerValue = array();
    if (sizeof($coreConfig) > 0) {
        $selectedMainFields = array(
            "produk_qty",
            "jml",
            "jumlah",
            "unit",
//            "produk_nilai",
            "produk_hpp",
            "hpp",
            "produk_harga",
            "qty",
        );
        foreach ($coreConfig as $mainGate => $gateData) {
            foreach ($gateData as $iGate => $tempGate) {
                if (isset($tempGate['loop'])) {
                    $tmp = array();
                    foreach ($tempGate['loop'] as $rekName => $rekVal) {
                        //----------------------------
                        if (substr($rekVal, 0, 1) == "-") {
                            $tmpEx = explode("-", $rekVal);
                            if (sizeof($tmpEx) > 1) {
                                $str = str_replace("-", "", $rekVal);
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $str = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                            }
                            else {
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $rekVal = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                                $str = "-" . $rekVal;
                            }
                        }
                        else {
                            $anu1 = strpos($rekVal, "*-");
                            $anu2 = strpos($rekVal, "*+");
                            $anu1 = $anu1 > 0 ? $anu1 : 0;
                            $anu2 = $anu2 > 0 ? $anu2 : 0;
                            $anu3 = $anu1 + $anu2;
//                            mati_disini(":: [$anu1] :: [$anu2] [$anu3] ::");
                            if ($anu3 == 0) {
                                $tmpEx = explode("-", $rekVal);
                            }
                            if (sizeof($tmpEx) > 1) {
                                $str = "-" . str_replace("-", "+", $rekVal);
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $str = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                            }
                            else {
                                //----------------------------
                                if (array_key_exists($rekName, $mainGateReplacer)) {
                                    $rekVal = $mainGateReplacer[$rekName];
                                }
                                //----------------------------
                                $str = "-" . $rekVal;
                            }
                        }

                        $tmp[$rekName] = $str;
                    }
                    $coreConfig[$mainGate][$iGate]["loop"] = $tmp;

                    if ($jenisTr_reference != "999") {
                        $coreLockerValue[$mainGate][$iGate]["loop"] = $tmp;
                    }

                }

                if (isset($tempGate['static'])) {
                    foreach ($tempGate['static'] as $ks => $val_s) {
                        if (in_array($ks, $selectedMainFields)) {
                            if (substr($val_s, 0, 1) == ".") {
                                $val_s = str_replace(".", "", $val_s);
                                $tmpEx = explode("-", $val_s);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal_0 = str_replace("-", "", $val_s);
                                }
                                else {
                                    $strVal_0 = "-" . $val_s;
                                }
//                                cekBiru("$ks => $val_s :: $strVal_0");
                                $strVal_0 = "." . $strVal_0;
                            }
                            else {
                                $tmpEx = explode("-", $val_s);
                                if (sizeof($tmpEx) > 1) {
                                    $strVal_0 = str_replace("-", "", $val_s);
                                }
                                else {
                                    $strVal_0 = "-" . $val_s;
                                }
//                                cekHijau("$ks => $val_s :: $strVal_0");
                            }
                        }
                        else {
                            $strVal_0 = $val_s;
                        }

                        $coreConfig[$mainGate][$iGate]["static"][$ks] = $strVal_0;
                    }
                }
                else {
                    $coreConfig[$mainGate][$iGate]["static"] = array();
                }

                if ($jenisTr_reference != "999") {
                    $coreLockerValue[$mainGate][$iGate]["static"] = isset($tempGate['static']) ? $tempGate['static'] : array();
                    $coreLockerValue[$mainGate][$iGate]["comName"] = isset($tempGate['comName']) ? $tempGate['comName'] : array();
                    $coreLockerValue[$mainGate][$iGate]["srcGateName"] = isset($tempGate['srcGateName']) ? $tempGate['srcGateName'] : array();
                    $coreLockerValue[$mainGate][$iGate]["srcRawGateName"] = isset($tempGate['srcRawGateName']) ? $tempGate['srcRawGateName'] : array();
                }

            }
        }

        $selectedFields = array(
            "produk_qty", "jml", "jumlah", "unit",
        );

        $validateComSwap = array(
            "FifoAverage",
            "FifoProdukJadi",
            "FifoProdukJadiRakitan",
            "FifoSupplies",

        );

        $rekExceptionLockervalue = array(
            "kas",
        );
        //---PREPROCC SETUP----------------
        $aliasing = array(
            "produk_id" => "extern_id",
            "nama" => "extern_nama",
            "jml" => "produk_qty",
            "unit" => "produk_qty",
            "produk_nama" => "extern_nama",
        );//untuk replacer preprocesor
        $comNameReplacer = array(
            "supplies" => "FifoAverageSupplies",
            "produk" => "FifoAverage",
        );
        $resultParams = array(
            "supplies" => array(
                "FifoAverageSupplies" => array(
                    "resultParams" => array(
                        "items" => array(
                            "harga_disc" => "hpp",
                            "hpp" => "hpp",
                            "sub_harga_disc" => "hpp",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),
            ),
            "produk" => array(
                "FifoAverage" => array(
                    "resultParams" => array(
                        "items" => array(
                            "hpp_nppv" => "hpp",
                            "hpp" => "hpp",
                            "sub_hpp_nppv" => "hpp",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),
            ),
            "none" => array(
                "FifoProdukJadi" => array(
                    "resultParams" => array(
                        "rsltItems" => array(
                            "id" => "produk_id",
                            "nama" => "nama",
                            "name" => "nama",
//                        "harga" => "hpp",
                            "hpp_nppv" => "hpp",
                            "hpp" => "hpp",
                            "jml" => "qty",
                            "qty" => "qty",
                            "subtotal" => "subtotal",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),
                "FifoSupplies" => array(
                    "resultParams" => array(
                        "rsltItems" => array(
                            "id" => "produk_id",
                            "nama" => "nama",
                            "name" => "nama",
//                        "harga" => "hpp",
                            "hpp_nppv" => "hpp",
                            "hpp" => "hpp",
                            "jml" => "qty",
                            "qty" => "qty",
                            "subtotal" => "subtotal",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),

                "FifoProdukJadiRakitan" => array(
                    "resultParams" => array(
                        "rsltItems" => array(
                            "id" => "produk_id",
                            "nama" => "nama",
                            "name" => "nama",
//                        "harga" => "hpp",
                            "hpp_nppv" => "hpp",
                            "hpp" => "hpp",
                            "jml" => "qty",
                            "qty" => "qty",
                            "subtotal" => "subtotal",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),
                "FifoAverage" => array(
                    "resultParams" => array(
                        "items" => array(
                            "hpp_nppv" => "hpp",
                            "hpp" => "hpp",
                            "sub_hpp_nppv" => "hpp",
                            "hpp_riil" => "hpp_riil",
                            "ppv_riil" => "ppv_riil",
                        ),
                    ),
                ),
            ),
            //------------------------
//        "valas" => array(
//            "FifoValasExternAverage" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//            "FifoValasExtern" => array(
//                "resultParams" => array(
//                    "rsltItems" => array(
//
//                    ),
//                ),
//            ),
//        ),
            //------------------------
        );
        $replacerParams = array(
            "recalculate" => array(
                "selisih",
                "hpp_nppv",
                "hpp_nppn",
            ),
        );
        $replacerSrcGate = array(
            "srcGateName" => "items",
            "srcRawGateName" => "items",
        );
        $replacerSrcGate_revert = array(
            "srcGateName" => array(
                "rsltItems" => "rsltItems_revert",
                "rsltItems2" => "rsltItems2_revert",
            ),
            "srcRawGateName" => array(
                "rsltItems" => "rsltItems_revert",
                "rsltItems2" => "rsltItems2_revert",
            ),
        );
        $comNameOrig = array(
            "RekeningPembantuProduk"
        );
        //------------------------
        $coreConfigAdd = array();

        if (isset($coreConfig["detail"])) {
            foreach ($coreConfig["detail"] as $iKey => $detailVal) {
                $tmpStatic = array();
                if (in_array($detailVal["comName"], $validateComSwap)) {
                    $calonPreprocc = $coreConfig["detail"][$iKey];
                    unset($coreConfig["detail"][$iKey]);
                    //---PRE-PROCC----------
//                cekHitam("PRE-PROCC");
//                arrPrintWebs($calonPreprocc);

                    foreach ($replacerSrcGate_revert as $srcGate => $rSpec) {
                        if (array_key_exists($calonPreprocc["srcGateName"], $rSpec)) {
                            $calonPreprocc[$srcGate] = $rSpec[$calonPreprocc["srcGateName"]];
//                    cekHitam("$srcGate -- $rval");
                        }
                        if (array_key_exists($calonPreprocc["srcRawGateName"], $rSpec)) {
                            $calonPreprocc[$srcGate] = $rSpec[$calonPreprocc["srcRawGateName"]];
//                    cekKuning("$srcGate -- $rval");
                        }
                    }
                    if (isset($calonPreprocc["static"]) && isset($calonPreprocc["static"]["jenis"])) {
                        $jenis = str_replace(".", "", $calonPreprocc["static"]["jenis"]);
                    }
                    else {
                        $jenis = "none";
                    }
                    // mereplace comName supplies dan produk....
                    if (array_key_exists($jenis, $comNameReplacer)) {
                        $calonPreprocc["comName"] = $comNameReplacer[$jenis];
                    }
                    if (isset($calonPreprocc["static"])) {
                        foreach ($calonPreprocc["static"] as $sKey => $val) {
                            if (isset($aliasing[$sKey])) {
                                $tKey = $aliasing[$sKey];
                                $calonPreprocc["static"][$tKey] = str_replace("-", "", $val);
                                unset($calonPreprocc["static"][$sKey]);
                            }
                            else {
                                $calonPreprocc["static"][$sKey] = str_replace("-", "", $val);
//                            cekHijau(":: $sKey => $val ::");
                            }
                        }
                    }


                    if (isset($resultParams[$jenis][$calonPreprocc["comName"]])) {
                        $calonPreprocc2 = $resultParams[$jenis][$calonPreprocc["comName"]];
//                cekHijau("ini");
                    }
                    else {
                        $calonPreprocc2 = array();
//                cekHijau("itu");
                    }
//cekHijau($calonPreprocc2);
                    $coreConfig["preProcc"]["detail"][] = $calonPreprocc + $calonPreprocc2;
                }
                else {
                    if (isset($detailVal["static"])) {
                        foreach ($detailVal["static"] as $ikey2 => $ival) {
                            if (in_array($ikey2, $selectedFields)) {
                                if (substr($ival, 0, 1) == ".") {
                                    $ival = str_replace(".", "", $ival);
                                    $tmpEx = explode("-", $ival);
                                    if (sizeof($tmpEx) > 1) {
                                        $strVal = str_replace("-", "", $ival);
                                    }
                                    else {
                                        $strVal = "-" . $ival;
                                    }
                                    $strVal = "." . $strVal;
                                }
                                else {

                                    $tmpEx = explode("-", $ival);
                                    if (sizeof($tmpEx) > 1) {
                                        $strVal = str_replace("-", "", $ival);
                                    }
                                    else {
                                        $strVal = "-" . $ival;
                                    }
                                }
                            }
                            else {
                                $strVal = $ival;
                            }
                            $tmpStatic[$ikey2] = $strVal;
//                        $coreConfig["detail"][$iKey]["static"][$ikey2] = $strVal;
                        }
                    }
                    switch ($jenisTr_reference) {
                        case "582spd":
                            if (in_array($detailVal["comName"], $comNameOrig)) {
                                $coreConfig["detail"][$iKey]["srcGateName"] = "rsltItems_revert";
                                $coreConfig["detail"][$iKey]["srcRawGateName"] = "rsltItems_revert";
                            }
                            break;
                        case "967":
                            if (in_array($detailVal["comName"], $comNameOrig)) {
                                $coreConfig["detail"][$iKey]["srcGateName"] = "rsltItems_revert";
                                $coreConfig["detail"][$iKey]["srcRawGateName"] = "rsltItems_revert";
                            }
                            break;
                        default:
                            break;
                    }
                }

                //// =============================== =============================== =============================== ////
                //// =============================== =============================== =============================== ////

//            cekBiru("deteksi dari config state HOLD, jumlah -QTY");
                if (isset($detailVal['static']['state']) && ($detailVal['static']['state'] == ".hold")) {
                    if (isset($detailVal['static']['jumlah']) && ($detailVal['static']['jumlah'] == "-qty")) {
//                    arrPrint($detailVal);
                        $iKeyNum = 1;
                        $coreConfigAdd["detail"][$iKeyNum] = $detailVal;
                        $tmpStaticAdd = array();
                        foreach ($detailVal["static"] as $ikey2 => $ival) {
                            $strVal = $ival;
                            $tmpStaticAdd[$ikey2] = $strVal;
                            $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                        }

                        $iKeyNum++;
                        $coreConfigAdd["detail"][$iKeyNum] = $detailVal;
                        $tmpStaticAdd = array();
                        foreach ($detailVal["static"] as $ikey2 => $ival) {
                            if (in_array($ikey2, $selectedFields)) {
                                if (substr($ival, 0, 1) == ".") {
                                    $ival = str_replace(".", "", $ival);
                                    $tmpEx = explode("-", $ival);
                                    if (sizeof($tmpEx) > 1) {
                                        $strVal = str_replace("-", "", $ival);
                                    }
                                    else {
                                        $strVal = "-" . $ival;
                                    }
                                    $strVal = "." . $strVal;
                                }
                                else {

                                    $tmpEx = explode("-", $ival);
                                    if (sizeof($tmpEx) > 1) {
                                        $strVal = str_replace("-", "", $ival);
                                    }
                                    else {
                                        $strVal = "-" . $ival;
                                    }
                                }
                            }
                            else {
                                $strVal = $ival;
                            }
                            $strVal = ($strVal == ".hold") ? ".active" : $strVal;
                            $strVal = ($ikey2 == "transaksi_id") ? ".0" : $strVal;
                            $tmpStaticAdd[$ikey2] = $strVal;
                            $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                        }
                    }
                }
            }
        }

        if (sizeof($coreLockerValue) > 0) {
            $keyReplacer = array(
                "extern_id" => "produk_id",
                "extern_nama" => "produk_nama",
            );
            foreach ($coreLockerValue as $gate => $gSpec) {
                if ($gate == "master") {
                    foreach ($gSpec as $index => $iSpec) {
                        if (isset($iSpec['loop']) && sizeof($iSpec['loop']) > 0) {
                            foreach ($iSpec['loop'] as $key => $val) {
                                if (!in_array($key, $rekExceptionLockervalue)) {
                                    $new_val = str_replace("-", "", $val);

                                    $lockVal[$gate][$key]['static']['state'] = ".active";
                                    $lockVal[$gate][$key]['static']['jenis'] = "." . str_replace(" ", "_", $key);
                                    $lockVal[$gate][$key]['static']['nilai'] = $new_val;
                                    $lockVal[$gate][$key]['static']['transaksi_id'] = ".0";
                                    $lockVal[$gate][$key]['static']['oleh_id'] = ".0";
//                            $lockVal[$gate][$key]['static']['produk_id'] = ".0";
                                    $lockVal[$gate][$key]['static']['gudang_id'] = ".0";
                                    $lockVal[$gate][$key]['srcGateName'] = isset($iSpec['srcGateName']) ? $iSpec['srcGateName'] : "";
                                    $lockVal[$gate][$key]['srcRawGateName'] = isset($iSpec['srcRawGateName']) ? $iSpec['srcRawGateName'] : "";
                                    $lockVal[$gate][$key]['comName'] = "LockerValue";

                                    if (isset($iSpec['static']) && sizeof($iSpec['static']) > 0) {
                                        foreach ($iSpec['static'] as $skey => $sval) {

                                            if (!isset($lockVal[$gate][$key]['static'][$skey])) {
                                                $lockVal[$gate][$key]['static'][$skey] = $sval;
                                            }
                                            // replace key
                                            if (array_key_exists($skey, $keyReplacer)) {
                                                unset($lockVal[$gate][$key]['static'][$skey]);
                                                $lockVal[$gate][$key]['static'][$keyReplacer[$skey]] = $sval;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (sizeof($lockVal) > 0) {
                foreach ($lockVal as $gate => $gSpec) {
                    foreach ($gSpec as $iSpec) {
                        $coreConfig[$gate][] = $iSpec;
                    }
                }
            }
        }

        if (sizeof($coreConfigAdd) > 0) {
            foreach ($coreConfigAdd as $gate => $gateSpec) {
                foreach ($gateSpec as $gSpec) {
                    $coreConfig[$gate][] = $gSpec;
                }
            }
        }

    }


//    cekKuning($coreConfig);
    return $coreConfig;

}

function fetchRevertPostProcAuto($jenis, $step, $postProcc = array(), $jenisSrc = "")
{
    $ci = &get_instance();

    if (sizeof($postProcc) > 0) {
        $coreConfig = $postProcc;
    }
//    else {
//        $coreConfig = isset($ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step]) ? $ci->config->item("heTransaksi_core")[$jenis]["postProcessor"][$step] : array();
//    }
//

    $validatePostSwap = array(
        "FifoAverage",
        "FifoAverageSupplies",
        "FifoProdukJadi",
        "FifoProdukJadiRakitan",
        "FifoSupplies",

        "FifoValas",
        "FifoValasAverage",

        "FifoValasExtern",
        "FifoValasExternAverage",

        "FifoSuppliesProduksi",
        "FifoSuppliesProses",

    );
    $selectedFields = array(
        "produk_qty", "jml", "jumlah", "unit", "qty_debet",
    );
    $terbayarFields = array("terbayar", "bayar_valas");
    $sisaFields = array("sisa", "sisa_valas");
    $arrRekName = array(
        "nilai",
        "nilai_valas",
        "terbayar",
        "terbayar_valas",
        "jumlah",
        "valid_qty",
    );
    $noCoreConfigJenis = array("9911", "9912");

    switch ($jenisSrc) {
        case "467":
        case "582spd":
        case "461":
        case "585":
        case "3585":
        case "460":
        case "761":
        case "763":
        case "9763":
        case "2985":
        case "773":
        case "982":
        case "1982":
        case "588":
        case "2485":
        case "1119":
        case "2229":
        case "2228":
        case "2227":
        case "335":
        case "2334":
        case "2335":
        case "2336":
        case "2337":
        case "960":
        case "1960":
        case "3339":
        case "5559":
        case "9585":
            $arrMasterKeyReplace = array(
                "produk_id" => "referenceID",
            );
            break;
        default:
            $arrMasterKeyReplace = array(// "produk_id" => "referenceID",
            );
            break;
    }

    if (sizeof($coreConfig) > 0) {
        $coreConfigAdd = array();
        foreach ($coreConfig as $mainGate => $gateData) {
            if ($mainGate == "master") {
                foreach ($gateData as $iGate => $tempGate) {
                    if (isset($tempGate['static'])) {
                        $tmp = array();
                        foreach ($tempGate['static'] as $rekName => $rekVal) {
                            if (in_array($rekName, $arrRekName)) {
                                if (substr($rekVal, 0, 1) == ".") {
                                    $rekVal = str_replace(".", "", $rekVal);
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                    $str = "." . $str;
                                }
                                else {
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                }
                            }
                            else {
                                if (array_key_exists($rekName, $arrMasterKeyReplace)) {
                                    $str = $arrMasterKeyReplace[$rekName];
                                }
                                else {
                                    $str = $rekVal;
                                }
                            }

                            $tmp[$rekName] = $str;
                        }
                        $coreConfig[$mainGate][$iGate]["static"] = $tmp;
                    }
                    if (isset($tempGate['loop'])) {
                        $tmpLoop = array();
                        foreach ($tempGate['loop'] as $rekName => $rekVal) {
                            if (substr($rekVal, 0, 1) == ".") {
                                $rekVal = str_replace(".", "", $rekVal);
                                $tmpEx = explode("-", $rekVal);
                                if (sizeof($tmpEx) > 1) {
                                    $str = str_replace("-", "", $rekVal);
                                }
                                else {
                                    $str = "-" . $rekVal;
                                }
                                $str = "." . $str;
                            }
                            else {
                                $tmpEx = explode("-", $rekVal);
                                if (sizeof($tmpEx) > 1) {
                                    $str = str_replace("-", "", $rekVal);
                                }
                                else {
                                    $str = "-" . $rekVal;
                                }
                            }

                            $tmpLoop[$rekName] = $str;
                        }
                        $coreConfig[$mainGate][$iGate]["loop"] = $tmpLoop;
                    }
                }

            }
            else {
//                arrPrintWebs($gateData);
                foreach ($gateData as $iGate => $tempGate) {
                    if (!in_array($tempGate['comName'], $validatePostSwap)) {
//                        cekKuning($tempGate['comName']);
                        //----
                        if ($tempGate['comName'] == "LockerValueExternItem") {
                            $selectedFields[99] = "nilai";
                        }
                        //----
                        if (isset($tempGate['static'])) {
                            $tmp = array();
                            foreach ($tempGate['static'] as $rekName => $rekVal) {
                                if (in_array($rekName, $selectedFields)) {
                                    if (substr($rekVal, 0, 1) == ".") {
                                        $rekVal = str_replace(".", "", $rekVal);
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                        $str = "." . $str;
                                    }
                                    else {

                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                    }

                                }
                                else {
//                                    if ($rekName == "terbayar") {
                                    if (in_array($rekName, $terbayarFields)) {
                                        $tmpEx = explode("-", $rekVal);
                                        if (sizeof($tmpEx) > 1) {
                                            $str = str_replace("-", "", $rekVal);
                                        }
                                        else {
                                            $str = "-" . $rekVal;
                                        }
                                    }
                                    else {
//                                        if ($rekName == "sisa") {
                                        if (in_array($rekName, $sisaFields)) {

                                            $tmpEx = explode("-", $rekVal);
                                            if (sizeof($tmpEx) > 1) {
                                                $str = str_replace("-", "", $rekVal);
                                            }
                                            else {
                                                $str = $rekVal . "+" . $tempGate['static']['terbayar'];
                                            }
                                        }
                                        else {
                                            $str = $rekVal;
                                        }
                                    }
                                }


                                $tmp[$rekName] = $str;
                                if ($rekName == "target_jenis") {
                                    $tmp[$rekName] = "jenisTr_reference";
                                }
                            }
                            $coreConfig[$mainGate][$iGate]["static"] = $tmp;
                            // ================================ ================================ ================================
                            // ================================ ================================ ================================
                            if (!in_array($jenis, $noCoreConfigJenis)) {
                                if (isset($tempGate['static']['state']) && ($tempGate['static']['state'] == ".hold")) {
                                    if (isset($tempGate['static']['jumlah']) && ($tempGate['static']['jumlah'] == "-qty")) {

                                        if ($jenisSrc != "582spd") {

                                            $iKeyNum = 1;
                                            $coreConfigAdd["detail"][$iKeyNum] = $tempGate;
                                            foreach ($tempGate["static"] as $ikey2 => $ival) {
                                                $strVal = $ival;
                                                $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                                            }

                                            $iKeyNum++;
                                            $coreConfigAdd["detail"][$iKeyNum] = $tempGate;

                                            foreach ($tempGate["static"] as $ikey2 => $ival) {
                                                if (in_array($ikey2, $selectedFields)) {
                                                    $tmpEx = explode("-", $ival);
                                                    if (sizeof($tmpEx) > 1) {
                                                        $strVal = str_replace("-", "", $ival);
                                                    }
                                                    else {
                                                        $strVal = "-" . $ival;
                                                    }
                                                }
                                                else {
                                                    $strVal = $ival;
                                                }
                                                $strVal = ($strVal == ".hold") ? ".active" : $strVal;
                                                $strVal = ($ikey2 == "transaksi_id") ? ".0" : $strVal;
                                                $coreConfigAdd["detail"][$iKeyNum]["static"][$ikey2] = $strVal;
                                            }

                                        }
                                    }
                                }
                            }
                        }

                        if (isset($tempGate['loop'])) {
                            $tmpLoop = array();
                            foreach ($tempGate['loop'] as $rekName => $rekVal) {
                                if (substr($rekVal, 0, 1) == ".") {
                                    $rekVal = str_replace(".", "", $rekVal);
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                    $str = "." . $str;
                                }
                                else {
                                    $tmpEx = explode("-", $rekVal);
                                    if (sizeof($tmpEx) > 1) {
                                        $str = str_replace("-", "", $rekVal);
                                    }
                                    else {
                                        $str = "-" . $rekVal;
                                    }
                                }

                                $tmpLoop[$rekName] = $str;
                            }
                            $coreConfig[$mainGate][$iGate]["loop"] = $tmpLoop;
                        }
                    }
                    else {
                        unset($coreConfig[$mainGate][$iGate]);
                    }

                }
            }
        }


        if (sizeof($coreConfigAdd) > 0) {
            foreach ($coreConfigAdd as $gate => $gateSpec) {
                foreach ($gateSpec as $gSpec) {
                    $coreConfig[$gate][] = $gSpec;
                }
            }
        }

    }


    return $coreConfig;
}

/**
 * Fungsi untuk mendapatkan langkah-langkah yang perlu di-reject berdasarkan transaksi yang dibatalkan.
 *
 * @param string $jenisTr Transaksi yang menjadi trigger atau transaksi yang dibatalkan.
 * @param string $master_jenis Master jenis transaksi yang terkait.
 * @param array $listHistoriID Array yang berisi ID transaksi yang sudah tercatat di setiap langkah.
 *
 * @return array Mengembalikan array dengan langkah-langkah yang perlu di-reject beserta histori ID-nya.
 */
function fetchAutoRevertJenisTr($jenisTr, $master_jenis, $listHistoriID)
{
    // Validasi parameter input
    if (empty($jenisTr) || empty($master_jenis) || !is_array($listHistoriID)) {
        throw new InvalidArgumentException("Parameter tidak valid.");
    }

    // Mendapatkan data langkah-langkah untuk transaksi master
    $masterStep = getMasterStep($master_jenis);
    $stepsToReject = getStepsToReject($jenisTr, $masterStep);

    // Mendapatkan histori ID transaksi yang perlu di-reject
    $stepsHistoryID = getStepsHistoryID($stepsToReject, $listHistoriID);

    // Mengembalikan hasil
    return [
        'stepsToReject' => $stepsToReject,
        'stepsHistoryID' => $stepsHistoryID
    ];
}

/**
 * Mendapatkan langkah-langkah berdasarkan jenis transaksi master
 *
 * @param string $master_jenis Jenis transaksi master
 *
 * @return array Daftar langkah-langkah untuk jenis transaksi master
 */
function getMasterStep($master_jenis)
{
    // Master UI berdasarkan jenis transaksi (dapat diambil dari konfigurasi atau database)
    $masterUI = array(
        "5822" => array(
            1 => array("target" => "5822spo"),
            2 => array("target" => "5822so"),
            3 => array("target" => "5822pkd"),
            4 => array("target" => "5822spd")
        )
    );

    // Mengembalikan langkah-langkah untuk master jenis transaksi
    return isset($masterUI[$master_jenis]) ? $masterUI[$master_jenis] : [];
}

/**
 * Mendapatkan langkah-langkah yang perlu di-reject berdasarkan transaksi yang dibatalkan
 *
 * @param string $jenisTr Transaksi yang dibatalkan
 * @param array $masterStep Langkah-langkah transaksi master
 *
 * @return array Langkah-langkah yang perlu di-reject
 */
function getStepsToReject($jenisTr, $masterStep)
{
    $stepsToReject = [];

    // Mencari langkah-langkah yang perlu di-reject berdasarkan transaksi yang dibatalkan
    foreach ($masterStep as $step => $stepData) {
        if ($stepData['target'] == $jenisTr) {
            // Ambil langkah-langkah sebelumnya untuk di-reject, kecuali step 1
            for ($i = $step - 1; $i >= 1; $i--) {
                if ($i != 1) {  // Cegah menambahkan step 1
                    $stepsToReject[] = $masterStep[$i]['target'];
                }
            }
        }
    }

    return $stepsToReject;
}

/**
 * Mendapatkan ID histori transaksi untuk langkah-langkah yang perlu di-reject
 *
 * @param array $stepsToReject Langkah-langkah yang perlu di-reject
 * @param array $listHistoriID Array yang berisi ID transaksi untuk langkah-langkah yang terlibat
 *
 * @return array ID transaksi yang terkait dengan langkah-langkah yang perlu di-reject
 */
function getStepsHistoryID($stepsToReject, $listHistoriID)
{
    $stepsHistoryID = [];

    // Memasukkan ID transaksi untuk setiap langkah yang perlu di-reject
    foreach ($stepsToReject as $stepTarget) {
        if (isset($listHistoriID[$stepTarget])) {
            $stepsHistoryID[$stepTarget] = $listHistoriID[$stepTarget];
        }
    }
    arrPrint($listHistoriID);
    matiHere(__LINE__);

    return $stepsHistoryID;
}


?>