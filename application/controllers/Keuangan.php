<?php


class Keuangan extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        $this->load->config("heAccounting");
        $this->load->helper("he_menu");
        $this->load->model("Coms/ComLedger");
        $this->koloms = array(
            "nama rekening", "debet", "kredit",
        );


        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        validateUserSession($this->session->login['id']);//

    }

    public function index()
    {
        $this->load->model("MdlBalanceSheet");
        $arrAccountBehavior = $this->config->item("accountBehavior");
        $bs = new MdlBalanceSheet();
        //        $arrRekening = $bs->lookupRekening();
        //        arrPrint($arrAccountBehavior);
        //        lookupRekening
    }

    public function viewBalanceSheet()
    {

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $accountAkumulasiPenyusutan = $this->config->item("accountAkumulasiPenyusutan") != null ? $this->config->item("accountAkumulasiPenyusutan") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {

                $struktureRekening[] = $itemRekening;
            }
        }


        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $tmp = $r->fetchAllBalances();

        $akumulasiPenyusutan = array();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($row['rekening'], $accountAkumulasiPenyusutan)) {

                    $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                    $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                    if (isset($accountChilds[$row['rekening']])) {

                        $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                        $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                    }
                    else {
                        $rekening_name_l = $rekening_name;
                    }
                    $rekening_name_l .= $linkH;

                    $tmpCol = array(
                        "rekening_orig" => $row['rekening'],
                        "rekening" => $rekening_name_l,
                        "debet" => $row['debet'] * 1,
                        "kredit" => $row['kredit'] * 1,
                        "link" => "",
                    );


                    $rekenings[] = $tmpCol;
//                    $totals['debet'] += $row['debet'];
//                    $totals['kredit'] += $row['kredit'];

                }
                else {
//                    cekHere($row['rekening'] . " :: " . $row['debet'] . " :: " . $row['kredit']);
                    if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                        $akumulasiPenyusutan['aktiva tetap'] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
//                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['debet'] * 1);
//                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['kredit'] * 1);
                }

            }
        }
//        arrPrint($rekenings);
//        arrPrint($akumulasiPenyusutan);
//        arrPrint($struktureRekening);

        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
//                arrPrint($spec);
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening_orig'], $accountAkumulasiPenyusutan)) {
                    if ($rek == $spec['rekening_orig']) {
                        $no++;

                        // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                        if (isset($akumulasiPenyusutan[$spec['rekening_orig']]) && sizeof($akumulasiPenyusutan[$spec['rekening_orig']]) > 0) {
//                            cekHere($spec['rekening_orig']);
//                            arrPrint($akumulasiPenyusutan[$spec['rekening_orig']]);
                            $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening_orig']]['debet'];
                            $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening_orig']]['kredit'];
                        }

                        $totals['debet'] += $spec['debet'];
                        $totals['kredit'] += $spec['kredit'];
//                        $rekenings_sort[$no] = $spec;
                        $rekenings_sort[$rek] = $spec;
                    }
                }
            }
        }
//        arrPrint($rekenings_sort);

        $data = array(
            "mode" => $this->uri->segment(2),
//            "items" => $rekenings,
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                // "link" => "",
            ),

            "title" => "balance",
            "subTitle" => "trial balance",

            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",

            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,
        );

        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetBulanan()
    {
        //        $previousMonth = previousMonth();
        $previousMonth = date("Y-m");
        //        cekHere($previousMonth);
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $thn = $defaultDate_ex[0];
        $bln = $defaultDate_ex[1];

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");


        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {

                $struktureRekening[] = $itemRekening;
            }
        }


        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        //        $tmp = $r->fetchAllBalances();
        //        $r->addFilter("bln<='$bln'");
        //        $r->addFilter("thn<='$thn'");
        $tmp = $r->fetchAllBalancesBulanan($defaultDate);
        //        cekHijau($this->db->last_query());
        //        arrPrint($tmp);


        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $rekening_name_orig = $row['rekening'];
                $periode = $row['periode'];
                $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/$rekening_name_orig?periode=$periode&date=$defaultDate&disabled=disabled'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                if (isset($accountChilds[$row['rekening']])) {
                    // $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";

                    $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                    $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                }
                else {
                    $rekening_name_l = $rekening_name;
                }
                $rekening_name_l .= $linkH;


                $tmpCol = array(
                    "rekening_orig" => $row['rekening'],
                    "rekening" => $rekening_name_l,
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );


                $rekenings[] = $tmpCol;
                $totals['debet'] += $row['debet'];
                $totals['kredit'] += $row['kredit'];

            }
        }


        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
                if ($rek == $spec['rekening_orig']) {
                    $no++;
                    $rekenings_sort[$no] = $spec;
                }
            }
        }


        $oldDate = "2019-09";
        $data = array(
            "mode" => $this->uri->segment(2),
            //            "items" => $rekenings,
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                // "link" => "",
            ),

            "title" => "Mutasi",
            "subTitle" => "Mutasi bulan " . lgTranslateTime($defaultDate),
            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,

            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)
        );

        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetMonthly()
    {

        $previousMonth = date("Y-m", strtotime("-1 month"));
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $accountAkumulasiPenyusutan = $this->config->item("accountAkumulasiPenyusutan") != null ? $this->config->item("accountAkumulasiPenyusutan") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {

                $struktureRekening[] = $itemRekening;
            }
        }

        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $tmp = $r->fetchAllBalancesMonthly($defaultDate);

        $akumulasiPenyusutan = array();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($row['rekening'], $accountAkumulasiPenyusutan)) {

                    $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                    $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                    if (isset($accountChilds[$row['rekening']])) {

                        $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                        $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                    }
                    else {
                        $rekening_name_l = $rekening_name;
                    }
                    $rekening_name_l .= $linkH;

                    $tmpCol = array(
                        "rekening_orig" => $row['rekening'],
                        "rekening" => $rekening_name_l,
                        "debet" => $row['debet'] * 1,
                        "kredit" => $row['kredit'] * 1,
                        "link" => "",
                    );


                    $rekenings[] = $tmpCol;
//                    $totals['debet'] += $row['debet'];
//                    $totals['kredit'] += $row['kredit'];

                }
                else {
//                    cekHere($row['rekening'] . " :: " . $row['debet'] . " :: " . $row['kredit']);
                    if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                        $akumulasiPenyusutan['aktiva tetap'] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
//                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['debet'] * 1);
//                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['kredit'] * 1);
                }
            }
        }


        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
//                arrPrint($spec);
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening_orig'], $accountAkumulasiPenyusutan)) {
                    if ($rek == $spec['rekening_orig']) {
                        $no++;

                        // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                        if (isset($akumulasiPenyusutan[$spec['rekening_orig']]) && sizeof($akumulasiPenyusutan[$spec['rekening_orig']]) > 0) {
//                            cekHere($spec['rekening_orig']);
//                            arrPrint($akumulasiPenyusutan[$spec['rekening_orig']]);
                            $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening_orig']]['debet'];
                            $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening_orig']]['kredit'];
                        }

                        $totals['debet'] += $spec['debet'];
                        $totals['kredit'] += $spec['kredit'];
//                        $rekenings_sort[$no] = $spec;
                        $rekenings_sort[$rek] = $spec;
                    }
                }
            }
        }
//        arrPrint($rekenings_sort);

        $awalBulanPilih = "01 " . date("F Y", strtotime($defaultDate));
        $toDate = date("t F Y", strtotime($awalBulanPilih));

        $txtPeriode = "$awalBulanPilih - $toDate";

        $oldDate = "2019-09";
        $data = array(
            "mode" => $this->uri->segment(2),
//            "items" => $rekenings,
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                // "link" => "",
            ),

            "title" => "balance",
            "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE MONTHLY</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",

            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",

            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,

            "oldDate" => $oldDate,
            "defaultDate" => $defaultDate,
        );

        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetMonthToDate()
    {

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $accountAkumulasiPenyusutan = $this->config->item("accountAkumulasiPenyusutan") != null ? $this->config->item("accountAkumulasiPenyusutan") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {
                $struktureRekening[] = $itemRekening;
            }
        }

        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tmp = $r->fetchAllBalancesMonthToDate();

        $akumulasiPenyusutan = array();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($row['rekening'], $accountAkumulasiPenyusutan)) {

                    $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                    $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                    if (isset($accountChilds[$row['rekening']])) {

                        $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                        $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                    }
                    else {
                        $rekening_name_l = $rekening_name;
                    }
                    $rekening_name_l .= $linkH;

                    $tmpCol = array(
                        "rekening_orig" => $row['rekening'],
                        "rekening" => $rekening_name_l,
                        "debet" => $row['debet'] * 1,
                        "kredit" => $row['kredit'] * 1,
                        "link" => "",
                    );


                    $rekenings[] = $tmpCol;
//                    $totals['debet'] += $row['debet'];
//                    $totals['kredit'] += $row['kredit'];

                }
                else {
//                    cekHere($row['rekening'] . " :: " . $row['debet'] . " :: " . $row['kredit']);
                    if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                        $akumulasiPenyusutan['aktiva tetap'] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
//                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['debet'] * 1);
//                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['kredit'] * 1);
                }

            }
        }

        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening_orig'], $accountAkumulasiPenyusutan)) {
                    if ($rek == $spec['rekening_orig']) {
                        $no++;

                        // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                        if (isset($akumulasiPenyusutan[$spec['rekening_orig']]) && sizeof($akumulasiPenyusutan[$spec['rekening_orig']]) > 0) {
//                            cekHere($spec['rekening_orig']);
//                            arrPrint($akumulasiPenyusutan[$spec['rekening_orig']]);
                            $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening_orig']]['debet'];
                            $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening_orig']]['kredit'];
                        }

                        $totals['debet'] += $spec['debet'];
                        $totals['kredit'] += $spec['kredit'];
//                        $rekenings_sort[$no] = $spec;
                        $rekenings_sort[$rek] = $spec;
                    }
                }
            }
        }

        $awalBulanIni = "01 " . date("F Y");
        $toDate = date("d F Y");

        $txtPeriode = "$awalBulanIni - $toDate";

        $defaultDate = date("Y-m");

        $data = array(
            "mode" => $this->uri->segment(2),
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
            ),
            "title" => "balance",
            "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE MONTH-TO-DATE</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",
            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,
            "defaultDate" => $defaultDate,
        );
        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetYearly()
    {

        $previousMonth = date("Y", strtotime('-1 year'));
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $accountAkumulasiPenyusutan = $this->config->item("accountAkumulasiPenyusutan") != null ? $this->config->item("accountAkumulasiPenyusutan") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {

                $struktureRekening[] = $itemRekening;
            }
        }


        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $tmp = $r->fetchAllBalancesYearly($defaultDate);

        $akumulasiPenyusutan = array();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($row['rekening'], $accountAkumulasiPenyusutan)) {

                    $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                    $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                    if (isset($accountChilds[$row['rekening']])) {

                        $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                        $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                    }
                    else {
                        $rekening_name_l = $rekening_name;
                    }
                    $rekening_name_l .= $linkH;

                    $tmpCol = array(
                        "rekening_orig" => $row['rekening'],
                        "rekening" => $rekening_name_l,
                        "debet" => $row['debet'] * 1,
                        "kredit" => $row['kredit'] * 1,
                        "link" => "",
                    );


                    $rekenings[] = $tmpCol;
//                    $totals['debet'] += $row['debet'];
//                    $totals['kredit'] += $row['kredit'];

                }
                else {
//                    cekHere($row['rekening'] . " :: " . $row['debet'] . " :: " . $row['kredit']);
                    if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                        $akumulasiPenyusutan['aktiva tetap'] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
//                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['debet'] * 1);
//                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['kredit'] * 1);
                }

            }
        }
//        arrPrint($rekenings);
//        arrPrint($akumulasiPenyusutan);
//        arrPrint($struktureRekening);

        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
//                arrPrint($spec);
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening_orig'], $accountAkumulasiPenyusutan)) {
                    if ($rek == $spec['rekening_orig']) {
                        $no++;

                        // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                        if (isset($akumulasiPenyusutan[$spec['rekening_orig']]) && sizeof($akumulasiPenyusutan[$spec['rekening_orig']]) > 0) {
//                            cekHere($spec['rekening_orig']);
//                            arrPrint($akumulasiPenyusutan[$spec['rekening_orig']]);
                            $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening_orig']]['debet'];
                            $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening_orig']]['kredit'];
                        }

                        $totals['debet'] += $spec['debet'];
                        $totals['kredit'] += $spec['kredit'];
//                        $rekenings_sort[$no] = $spec;
                        $rekenings_sort[$rek] = $spec;
                    }
                }
            }
        }
//        arrPrint($rekenings_sort);
        $awalTahun = "01 January " . $defaultDate;
        $toDate = "31 December " . $defaultDate;

        $txtPeriode = "$awalTahun - $toDate";

        $oldDate = "2019";
        $data = array(
            "mode" => $this->uri->segment(2),
//            "items" => $rekenings,
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                // "link" => "",
            ),

            "title" => "balance",
            "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE YEARLY</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",

            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",

            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,

            "oldDate" => $oldDate,
            "defaultDate" => $defaultDate,
        );

        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetYearToDate()
    {
        $previousMonth = date("Y");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $accountAkumulasiPenyusutan = $this->config->item("accountAkumulasiPenyusutan") != null ? $this->config->item("accountAkumulasiPenyusutan") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {
                $struktureRekening[] = $itemRekening;
            }
        }

        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $tmp = $r->fetchAllBalancesYearToDate($defaultDate);

        $akumulasiPenyusutan = array();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($row['rekening'], $accountAkumulasiPenyusutan)) {

                    $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];

                    $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                    if (isset($accountChilds[$row['rekening']])) {

                        $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];

                        $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                    }
                    else {
                        $rekening_name_l = $rekening_name;
                    }
                    $rekening_name_l .= $linkH;

                    $tmpCol = array(
                        "rekening_orig" => $row['rekening'],
                        "rekening" => $rekening_name_l,
                        "debet" => $row['debet'] * 1,
                        "kredit" => $row['kredit'] * 1,
                        "link" => "",
                    );


                    $rekenings[] = $tmpCol;
//                    $totals['debet'] += $row['debet'];
//                    $totals['kredit'] += $row['kredit'];

                }
                else {
//                    cekHere($row['rekening'] . " :: " . $row['debet'] . " :: " . $row['kredit']);
                    if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                        $akumulasiPenyusutan['aktiva tetap'] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
//                    $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['debet'] * 1);
//                    $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['kredit'] * 1);
                }

            }
        }

        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
//                arrPrint($spec);
                if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening_orig'], $accountAkumulasiPenyusutan)) {
                    if ($rek == $spec['rekening_orig']) {
                        $no++;

                        // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                        if (isset($akumulasiPenyusutan[$spec['rekening_orig']]) && sizeof($akumulasiPenyusutan[$spec['rekening_orig']]) > 0) {
//                            cekHere($spec['rekening_orig']);
//                            arrPrint($akumulasiPenyusutan[$spec['rekening_orig']]);
                            $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening_orig']]['debet'];
                            $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening_orig']]['kredit'];
                        }

                        $totals['debet'] += $spec['debet'];
                        $totals['kredit'] += $spec['kredit'];
//                        $rekenings_sort[$no] = $spec;
                        $rekenings_sort[$rek] = $spec;
                    }
                }
            }
        }

        $awalTahun = "01 January " . date("Y");
        $toDate = date("d F Y");

        $txtPeriode = "$awalTahun - $toDate";

        $data = array(
            "mode" => $this->uri->segment(2),
            "items" => $rekenings_sort,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                // "link" => "",
            ),
            "title" => "balance",
            "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE YEAR-TO-DATE</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",
            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
            "rekening_name" => $struktureRekening,
            "rekening_alias" => $accountAlias,
        );

        $this->load->view("finance", $data);

    }

    public function viewBalanceSheetTmp()
    {

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();


        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();

        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $defaultDate = date("2019-09");
        $tmp = $r->fetchAllBalancesTmp($defaultDate);


        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {

                $tmpCol = array(
                    "rekening" => isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'],
                    //                    "debet" => $row['debet'] * 1,
                    //                    "kredit" => $row['kredit'] * 1,
                    "debet" => $row['debet'],
                    "kredit" => $row['kredit'],
                    "link" => "",
                );


                if (isset($accountChilds[$row['rekening']])) {
                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'] . "'><span class='fa fa-clone'></span></a>";
                }
                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                $rekenings[] = $tmpCol;
                $totals['debet'] += $row['debet'];
                $totals['kredit'] += $row['kredit'];

            }
        }


        $data = array(
            "mode" => $this->uri->segment(2),
            "items" => $rekenings,
            "totals" => $totals,
            "array_header" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),

            "title" => "balance",
            "subTitle" => "trial balance",

            "accountChilds" => $accountChilds,
            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
        );

        $this->load->view("finance", $data);

    }

    public function viewNeraca()
    {
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);
        $dates = $ner->fetchDates();


        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        $oldDate = "2019-09";
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "balance (final)",
            "subTitle" => "balance (final) per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => true,
            "rekeningKeterangan" => $rekeningKeterangan,
            "buttonMode" => array(
                "enabled" => true,
                "label" => "neraca (internal)",
                "link" => base_url() . get_class($this) . "/viewNeracaKoreksi",
            ),
        );
        $this->load->view("finance", $data);

    }

    public function viewNeracaTahunan()
    {
        $previousMonth = previousYear();

        $tahun_pilihan = isset($_GET['date']) ? $_GET['date'] : dtimeNow("Y");
        $aa = New Layout();
        $aa->setOnClickTarget(current_url());
        $pilih_tahun = $aa->selectTahun($tahun_pilihan, "date");

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();

        $this->load->model("Mdls/" . "MdlNeraca");
        $ner = new MdlNeraca();

        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='tahunan'");
        $tmp = $ner->fetchBalances($defaultDate);
        //cekkuning($this->db->last_query() . "<br>$defaultDate");

        $dates = $ner->fetchDates();
        //arrPrint($dates);
        //        $oldDate = date("Y-m-d");
        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            //            arrPrint($tmp);
            foreach ($tmp as $row) {

                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "debet" => $row->debet,
                                "kredit" => $row->kredit,
                                "link" => "",
                            );
                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn";
            }
            //            reset($dates);
            //            $oldDate = array_values($dates);
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");


        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
        }

        $categories = array();
        foreach ($arrCat as $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
            }
        }


        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        $oldDate = "2019-01";
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "balance",
            "subTitle" => "balance per-" . $defaultDate,
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "rek_id" => "code",
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",

            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "pilih_tahun" => $pilih_tahun,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeracaRealtime()
    {

        $this->load->model("Coms/" . "ComRugiLaba_forever");
        $this->load->model("Coms/" . "ComNeraca_forever");
        $tahun = date("Y");
        $static = array(
            "static" => array(
                "cabang_id" => $this->session->login['cabang_id'],
                //                "periode" => "forever",
                "periode" => "tahunan",
                "tahun" => $tahun,
            ),
        );

        $rl = New ComRugiLaba_forever();
        $rl->pair($static);
        $resultRL = $rl->exec();
        //arrPrint($resultRL);
        //arrPrint($resultRL['neraca']);
        //mati_disini();
        $ner = New ComNeraca_forever();
        $ner->setInParams2($resultRL['neraca']);
        $ner->pair($static);
        $resultNeraca = $ner->exec();
        //arrPrint($resultNeraca);
        //mati_disini();

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();


        $tmp = array();
        if (sizeof($resultNeraca) > 0) {
            foreach ($resultNeraca as $nn => $nSpec) {
                $temp = array();
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                    //                    if($val != "laba ditahan"){
                    ////                        $temp[$nn][$key] = $val;
                    //                        $temp[$key] = $val;
                    //                    }
                    //                    else{
                    //
                    //                    }
                }
                $tmp[$nn] = (object)$temp;
            }
        }
        //arrPrint($temp);
        //arrPrint($tmp);
        //mati_disini();

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {

                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "debet" => $row->debet,
                                "kredit" => $row->kredit,
                                "link" => "",
                            );
                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
        }

        $categories = array();
        foreach ($arrCat as $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
            }
        }


        $data = array(
            "mode" => $this->uri->segment(2),
            //            "mode" => "viewNeraca",
            "title" => "balance",
            //            "subTitle" => "balance  " . lgTranslateTime($defaultDate),
            "subTitle" => "realtime balance  " . lgTranslateTime(date("Y")),
            //            "categories" => array("aktiva", "hutang", "modal", "lain-lain-kredit"),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "rek_id" => "code",
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",

            ),
            "defaultDate" => isset($defaultDate) ? $defaultDate : "",
            "oldDate" => isset($oldDate) ? $oldDate : "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)
        );
        $this->load->view("finance", $data);

    }

    public function viewNeraca_consolidated()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $periode = "bulanan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
//        showLast_query("biru");
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $this->load->model("Mdls/MdlNeraca");
        $ner = new MdlNeraca();
//        $ner->addFilter("tipe!='konsolidasi_riil'");
        $where = "(tipe is NULL OR tipe!='konsolidasi_riil')";
        $this->db->where($where);
        $tmp = $ner->fetchBalances2($defaultDate);

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }

        $arrCabangs[0] = "Konsolidasi";

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        $defPos = detectRekDefaultPosition($row->rekening);

                        if (($row->tipe == "konsolidasi_cost") || ($row->tipe == "konsolidasi_riil")) {
                            $rekeningsKonsolidasi[$row->rekening] = $row->rekening;
                        }
                        if (strlen($row->kategori) > 1) {
                            if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!in_array($row->kategori, $categories)) {
                                    $categories[] = $row->kategori;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                    $rekenings[$row->cabang_id][$row->kategori] = array();
                                }


                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                }

                                if (in_array($row->rekening, $accountException)) {
                                    $debet = $row->kredit * -1;
                                    $kredit = $row->debet * -1;
                                }
                                else {
                                    switch ($defPos) {
                                        case "debet":
                                            if ($row->kredit > 0) {
                                                $debet = $row->kredit * -1;
                                                $kredit = 0;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        case "kredit":
                                            if ($row->debet > 0) {
                                                $debet = 0;
                                                $kredit = $row->debet * -1;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        default:
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                            break;
                                    }
                                    //                                    $debet = $row->debet;
                                    //                                    $kredit = $row->kredit;
                                }


                                if (sizeof($accountCatException) > 0) {
                                    foreach ($accountCatException as $cat => $c_rekName) {
                                        if (in_array($row->rekening, $c_rekName)) {
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                            }

                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$cat][$row->id] = $row->rekening;
                                        }
                                        else {
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$row->kategori][$row->id] = $row->rekening;
                                        }
                                    }
                                }
                                else {
                                    $rekenings[$row->kategori][] = $rekenings;
                                }


                                $whID = getDefaultWarehouseID($row->cabang_id);
                                $childLink = "";
                                if ($row->cabang_id != 0) {
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
//                                    $childLink2 = "$childLink <span class='pull-right'>
//                                        <a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
//                                        <span class='glyphicon glyphicon-time'></span></a>
//                                        </span>";
                                    $childLink2 = "$childLink <span class='pull-right'>
                                        <a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a>
                                        </span>";
                                }
                                else {
                                    $childLink2 = "";
                                }
//
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            }
                        }
                    }

                }

            }
            reset($dates);
            $oldDate = key($dates);
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        if (sizeof($rekeningsKonsolidasi) == 0) {
            unset($arrCabangs[0]);
        }
        $oldDate = "2019-09";
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Neraca Konsolidasi $periode ",
            "subTitle" => "Neraca Konsolidasi per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeraca_consolidatedTahunan()
    {


        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
//        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
//        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
//        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
//        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
//        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
//        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();
        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];

        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='tahunan'");
//        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$defaultDate'");
        $fcTmp = $fc->lookupAll()->result();
        showLast_query("biru");
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
//        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();


        $this->load->model("Mdls/" . "MdlNeraca");
        $ner = new MdlNeraca();
        $ner->addFilter("periode='tahunan'");
        $tmp = $ner->fetchBalances2($defaultDate);
        //cekKuning($this->db->last_query());

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        $defPos = detectRekDefaultPosition($row->rekening);

                        if (($row->tipe == "konsolidasi_cost") || ($row->tipe == "konsolidasi_riil")) {
                            $rekeningsKonsolidasi[$row->rekening] = $row->rekening;
                        }
                        if (strlen($row->kategori) > 1) {
                            if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!in_array($row->kategori, $categories)) {
                                    $categories[] = $row->kategori;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                    $rekenings[$row->cabang_id][$row->kategori] = array();
                                }


                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                }

                                if (in_array($row->rekening, $accountException)) {
                                    $debet = $row->kredit * -1;
                                    $kredit = $row->debet * -1;
                                }
                                else {
                                    switch ($defPos) {
                                        case "debet":
                                            if ($row->kredit > 0) {
                                                $debet = $row->kredit * -1;
                                                $kredit = 0;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        case "kredit":
                                            if ($row->debet > 0) {
                                                $debet = 0;
                                                $kredit = $row->debet * -1;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        default:
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                            break;
                                    }
                                    //                                    $debet = $row->debet;
                                    //                                    $kredit = $row->kredit;
                                }


                                if (sizeof($accountCatException) > 0) {
                                    foreach ($accountCatException as $cat => $c_rekName) {
                                        if (in_array($row->rekening, $c_rekName)) {
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                            }

                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$cat][$row->id] = $row->rekening;
                                        }
                                        else {
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$row->kategori][$row->id] = $row->rekening;
                                        }
                                    }
                                }
                                else {
                                    $rekenings[$row->kategori][] = $rekenings;
                                }


                                $whID = getDefaultWarehouseID($row->cabang_id);
                                $childLink = "";
                                if (isset($accountChilds[$row->rekening])) {
                                    $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                }
//                                $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
//                                        <span class='glyphicon glyphicon-time'></span></a></span>";
                                $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            }
                        }
                    }

                }

            }
            reset($dates);
            $oldDate = key($dates);
        }

        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        if (sizeof($rekeningsKonsolidasi) == 0) {
            unset($arrCabangs[0]);
        }
        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewNeraca_consolidated",
            "title" => "Neraca Konsolidasi Tahunan ",
            //            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "subTitle" => "Neraca Konsolidasi per- $defaultDate",
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("finance", $data);

    }

    public function viewDivNeraca_consolidated()
    {

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $accountChilds = $this->config->item("accountChilds");
        $this->load->model("Mdls/" . "MdlNeraca_div");
        $ner = new MdlNeraca_div();
        $ner->addFilter("cabang_id='$cabangID'");
        $tmp = $ner->fetchBalances2($defaultDate);

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m-d");


        $this->load->model("Mdls/" . "MdlDiv");
        $cb = new MdlDiv();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs = array();
        //        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }
        //arrPrint($tmp);

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        if (strlen($row->kategori) > 1) {
                            $arrCabang[$row->div_id] = isset($arrCabangs[$row->div_id]) ? $arrCabangs[$row->div_id] : "";

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }

                            if (!isset($rekenings[$row->div_id][$row->kategori])) {
                                $rekenings[$row->div_id][$row->kategori] = array();
                            }

                            //                            $tmpCol = array(
                            //                                "debet"    => $row->debet,
                            //                                "kredit"   => $row->kredit,
                            //                            );
                            //                            $childLink = "";
                            //                            if (isset($accountChilds[$row->rekening])) {
                            //                                $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "'><span class='fa fa-clone'></span></a>";
                            //                            }
                            //                            $tmpCol['link'] = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
                            //
                            //                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening] = $tmpCol;
                            //                            $rekeningsName[$row->kategori][$row->rek_id] = $row->rekening;


                            if (!isset($rekenings[$row->div_id][$row->kategori][$row->rekening]['debet'])) {
                                $rekenings[$row->div_id][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekenings[$row->div_id][$row->kategori][$row->rekening]['kredit'])) {
                                $rekenings[$row->div_id][$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            $rekenings[$row->div_id][$row->kategori][$row->rekening]['debet'] += $row->debet;
                            $rekenings[$row->div_id][$row->kategori][$row->rekening]['kredit'] += $row->kredit;

                            $childLink = "";
                            if (isset($accountChilds[$row->rekening])) {
                                $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&d=" . $row->div_id . "'><span class='fa fa-clone'></span></a>";
                            }
                            $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&d=" . $row->div_id . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                            $rekenings[$row->div_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            $rekeningsName[$row->kategori][$row->rek_id] = $row->rekening;
                        }
                    }

                }

            }
            reset($dates);
            $oldDate = key($dates);
        }


        //        arrprint($rekeningsName);
        //        arrprint($rekenings);
        //        mati_disini();

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "balance",
            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "categories" => array("aktiva", "hutang", "modal"),
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabang,
            "rekeningsName" => $rekeningsName,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeracaYearToDate()
    {

        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeracaLajur");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Coms/ComRekening_cli");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");


        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        $arrRekBlacklist = array(
            "rugilaba",
        );

        $periode = "tahunan";
        $cabangID = $this->session->login['cabang_id'];
        //        $cabangID = "-1";
        $date1 = date("Y-01-01");
        $date2 = date("Y-m-d");
        $dateNow = date("Y-m-d");
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateExp = explode("-", $dateNow);
        $bulan = $dateExp[1];
        $tahun = $dateExp[0];
        $tahunLast = $dateExp[0] - 1;

        $static = array(
            "static" => array(
                "cabang_id" => $cabangID,
                "dtime" => $dateTimeNow,
                "fulldate" => $dateNow,
                "bln" => $bulan,
                "thn" => $tahun,
                "periode" => $periode,
            ),
        );
        $filters = array(
            "periode" => $periode,
            "cabang_id" => $cabangID,
            "bln" => $bulan,
            "thn" => $tahun,
        );
        $filters2 = array(
            "periode=" => $periode,
            "cabang_id=" => $cabangID,
            "date(dtime)<=" => $date2,
        );


        $cr->setFilters(array());
        $cr->setFilters2(array());
        $cr->setFilters($filters);
        $cr->setFilters2($filters2);
        $cr->addFilter("cabang_id='" . $cabangID . "'");
        if (isset($this->filters)) {
            $setFilters = $this->filters;
            foreach ($this->filters as $kf => $vf) {
                $cr->addFilter("$kf='$vf'");
            }
        }
        if (isset($this->filters2)) {
            $cr->setFilters2($this->filters2);
        }
        $tmp = $cr->fetchAllBalances2();
        //        cekKuning($this->db->last_query());
        if (sizeof($tmp) > 0) {
            $arrRek = array();
            $arrRekSaldo = array();
            foreach ($tmp as $rek => $rSpec) {
                $arrRek[] = $rek;

                $rSpec['debet'] = 0;
                $rSpec['kredit'] = 0;
                $arrRekSaldo[$rek] = $rSpec;
            }
        }
        // membaca in/out mutasi masing-masing rekening...
        if (sizeof($arrRek) > 0) {
            $arrMutasi = array();
            foreach ($arrRek as $rek) {

                $mts = New ComRekening_cli();
                $mts->addFilter("cabang_id='$cabangID'");
//                $mts->addFilter("date(dtime)>='$date1'");
//                $mts->addFilter("date(dtime)<='$date2'");
                $mts->addFilter("fulldate>='$date1'");
                $mts->addFilter("fulldate<='$date2'");
                $mts->addFilter("transaksi_id>'0'");
                $arrMutasi[$rek] = $mts->fetchMoves($rek);
                //                cekLime($this->db->last_query());
            }
            if (sizeof($arrMutasi) > 0) {

                $arrRekMutasi = array();
                $arrMutasiResult = array();
                foreach ($arrMutasi as $rek => $mSpec) {
                    foreach ($mSpec as $mmSpec) {

                        if (!isset($arrMutasiResult[$rek]["debet"])) {
                            $arrMutasiResult[$rek]["debet"] = 0;
                        }
                        if (!isset($arrMutasiResult[$rek]["kredit"])) {
                            $arrMutasiResult[$rek]["kredit"] = 0;
                        }

                        $arrMutasiResult[$rek]["rek_id"] = $mmSpec->rek_id;
                        $arrMutasiResult[$rek]["rekening"] = $mmSpec->rekening;
                        $arrMutasiResult[$rek]["debet"] += $mmSpec->debet;
                        $arrMutasiResult[$rek]["kredit"] += $mmSpec->kredit;
                        $arrMutasiResult[$rek]["periode"] = $periode;

                        $arrRekMutasi[$mmSpec->rekening] = $mmSpec->rekening;
                    }
                }
                //                arrPrint($arrMutasiResult);
            }
        }


        // mengambil neraca terakhir....
        $ner = new MdlNeraca();
        $ner->addFilter("cabang_id='" . $cabangID . "'");
        $ner->addFilter("periode='$periode'");
        $ner->addFilter("trash='0'");
        $tmpLastNeraca = $ner->fetchBalances($tahunLast);
        //        cekKuning($this->db->last_query());
        //        mati_disini();

        $tmpRekNeraca = array();
        $tmpLastNeracaResult = array();
        if (sizeof($tmpLastNeraca) > 0) {
            foreach ($tmpLastNeraca as $lnSpec) {
                $rek = $lnSpec->rekening;
                if (!isset($tmpLastNeracaResult[$rek]["debet"])) {
                    $tmpLastNeracaResult[$rek]["debet"] = 0;
                }
                if (!isset($tmpLastNeracaResult[$rek]["kredit"])) {
                    $tmpLastNeracaResult[$rek]["kredit"] = 0;
                }
                if (($lnSpec->debet > 0) && ($lnSpec->kredit > 0)) {
                    $val_detail = $lnSpec->debet - $lnSpec->kredit;
                    if ($val_detail > 0) {
                        $debet = $val_detail;
                        $kredit = 0;
                    }
                    else {
                        $debet = 0;
                        $kredit = $val_detail * -1;
                    }
                }
                else {
                    $debet = $lnSpec->debet;
                    $kredit = $lnSpec->kredit;
                }
                $tmpLastNeracaResult[$rek]["rek_id"] = $lnSpec->rek_id;
                $tmpLastNeracaResult[$rek]["rekening"] = $lnSpec->rekening;
                $tmpLastNeracaResult[$rek]["debet"] += $debet;
                $tmpLastNeracaResult[$rek]["kredit"] += $kredit;
                $tmpLastNeracaResult[$rek]["periode"] = $lnSpec->periode;

                $tmpRekNeraca[$rek] = $rek;
            }
        }

        $arrLajur = array();
        if (sizeof($tmpLastNeracaResult) > 0) {
            foreach ($tmpLastNeracaResult as $rek => $spec) {
                if ($spec['debet'] > 0 && $spec['kredit'] > 0) {
                    $value = $spec['debet'] - $spec['kredit'];
                    if ($value < 0) {
                        $debetLast = 0;
                        $kreditLast = $value * -1;
                    }
                    else {
                        $debetLast = $value;
                        $kreditLast = 0;
                    }
                }
                else {
                    $debetLast = $spec['debet'];
                    $kreditLast = $spec['kredit'];
                }

                if (isset($arrMutasiResult[$rek])) {
                    $debetMutasi = $arrMutasiResult[$rek]['debet'];
                    $kreditMutasi = $arrMutasiResult[$rek]['kredit'];
                }
                else {
                    $debetMutasi = 0;
                    $kreditMutasi = 0;
                }
                $defaultPosition = detectRekDefaultPosition($rek);
                if ($defaultPosition == "debet") {
                    if ($debetLast > 0) {
                        $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
                    }
                    else {
                        $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi;
                    }
                    $saldo_kredit = 0;
                }
                elseif ($defaultPosition == "kredit") {
                    if ($kreditLast > 0) {
                        $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                        $saldo_debet = 0;
                    }
                    else {
                        $saldo_kredit = -$debetLast + $kreditMutasi - $debetMutasi;
                        $saldo_debet = 0;
                    }
                }
                $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                $arrLajur[$rek]["rekening"] = $spec['rekening'];
                $arrLajur[$rek]["debet"] = $saldo_debet;
                $arrLajur[$rek]["kredit"] = $saldo_kredit;
                $arrLajur[$rek]["periode"] = $spec['periode'];
            }
        }
        if (sizeof($arrMutasiResult) > 0) {
            foreach ($arrMutasiResult as $rek => $spec) {
                if (!array_key_exists($rek, $tmpLastNeracaResult)) {
                    //                        cekKuning("memproses rekening $rek");
                    $debetMutasi = $spec['debet'];
                    $kreditMutasi = $spec['kredit'];
                    $debetLast = 0;
                    $kreditLast = 0;

                    $defaultPosition = detectRekDefaultPosition($rek);
                    if ($defaultPosition == "debet") {
                        $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
                        $saldo_kredit = 0;
                    }
                    elseif ($defaultPosition == "kredit") {
                        $saldo_debet = 0;
                        $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                    }
                    $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                    $arrLajur[$rek]["rekening"] = $spec['rekening'];
                    $arrLajur[$rek]["debet"] = $saldo_debet;
                    $arrLajur[$rek]["kredit"] = $saldo_kredit;
                    $arrLajur[$rek]["periode"] = $spec['periode'];
                }
            }
        }

        $arrLajurNew = array();
        foreach ($arrLajur as $rek => $spec) {
            if ($spec['debet'] < 0) {
                $spec['kredit'] = $spec['debet'] * -1;
                $spec['debet'] = 0;
            }
            if ($spec['kredit'] < 0) {
                $spec['debet'] = $spec['kredit'] * -1;
                $spec['kredit'] = 0;
            }
            if (!in_array($rek, $arrRekBlacklist)) {
                $arrLajurNew[$rek] = $spec;
            }
        }

        //region last neraca...
        $totalDebet = 0;
        $totalKredit = 0;
        $str = "";
        $str .= "<table rules='all' border='1px solid black;'>";
        foreach ($tmpLastNeracaResult as $rek => $spec) {

            $totalDebet += $spec['debet'];
            $totalKredit += $spec['kredit'];

            $str .= "<tr>";
            $str .= "<td>" . $spec['rekening'] . "</td>";
            $str .= "<td style='text-align: right;'>" . $spec['debet'] . "</td>";
            $str .= "<td style='text-align: right;'>" . $spec['kredit'] . "</td>";
            $str .= "</tr>";
        }
        $selisih = $totalDebet - $totalKredit;
        $str .= "<tr>";
        $str .= "<td>$selisih</td>";
        $str .= "<td style='text-align: right;'>" . $totalDebet . "</td>";
        $str .= "<td style='text-align: right;'>" . $totalKredit . "</td>";
        $str .= "</tr>";
        $str .= "</table>";
        //        echo "<br>LAST NERACA<br>$str";
        //endregion

        //region lajur...
        $totalDebet = 0;
        $totalKredit = 0;
        $str = "";
        $str .= "<table rules='all' border='1px solid black;'>";
        foreach ($arrLajurNew as $rek => $spec) {

            $totalDebet += $spec['debet'];
            $totalKredit += $spec['kredit'];

            $str .= "<tr>";
            $str .= "<td>" . $spec['rekening'] . "</td>";
            $str .= "<td style='text-align: right;'>" . $spec['debet'] . "</td>";
            $str .= "<td style='text-align: right;'>" . $spec['kredit'] . "</td>";
            $str .= "</tr>";
        }
        $selisih = $totalDebet - $totalKredit;
        $str .= "<tr>";
        $str .= "<td>$selisih</td>";
        $str .= "<td style='text-align: right;'>" . $totalDebet . "</td>";
        $str .= "<td style='text-align: right;'>" . $totalKredit . "</td>";
        $str .= "</tr>";
        $str .= "</table>";
        //        echo "<br>LAJUR<br>$str";
        //endregion


        $rl->setFilters2($filters2);
        $rl->setFilters($filters);
        $rl->pairNoCut_view($static, $arrLajurNew);
        $resultRL = $rl->execNoCut_view();
        //        arrPrint($resultRL);


        $n->setFilters2($filters2);
        $n->setFilters($filters);
        $n->pairNoCut_view($static, $resultRL['neraca']);
        $resultNeraca = $n->execNoCut_view();

        // =======================================
        // =======================================
        // =======================================
        // ==== view neraca year to date...
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();

        $tmp = array();
        if (sizeof($resultNeraca) > 0) {
            foreach ($resultNeraca as $nn => $nSpec) {
                $temp = array();
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                    //                    if($val != "laba ditahan"){
                    ////                        $temp[$nn][$key] = $val;
                    //                        $temp[$key] = $val;
                    //                    }
                    //                    else{
                    //
                    //                    }
                }
                $tmp[$nn] = (object)$temp;
            }
        }

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );
                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }
            }
        }

        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        $data = array(
            //            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca",
            "title" => "balance",
            "subTitle" => "balance  " . lgTranslateTime(date("Y")),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",

            ),
            "defaultDate" => isset($defaultDate) ? $defaultDate : "",
            "oldDate" => isset($oldDate) ? $oldDate : "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => false,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("finance", $data);


    }

    public function viewNeracaYearToDate_consolidated()
    {

        // region rl year to date
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeracaLajur");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Coms/ComRekening_cli");
        $this->load->model("Mdls/" . "MdlCabang");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        $this->load->library("Rekening");


        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        $arrRekBlacklist = array(
            "rugilaba",
        );
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        $periode = "tahunan";
//        $periode = "forever";
        $date1 = date("Y-01-01");
        $date2 = date("Y-m-d");
        $dateNow = date("Y-m-d");
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateExp = explode("-", $dateNow);
        $tgl = $dateExp[2];
        $bulan = $dateExp[1];
        $tahun = $dateExp[0];
        $tahunLast = $dateExp[0] - 1;

        $pakai_ini = 1;
        $resultNeracaByCabang = array();
        foreach ($arrCabangs as $cabangID => $cabangName) {

            $static = array(
                "static" => array(
                    "cabang_id" => $cabangID,
                    "dtime" => $dateTimeNow,
                    "fulldate" => $dateNow,
                    "bln" => $bulan,
                    "thn" => $tahun,
                    "periode" => $periode,
                ),
            );
            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cabangID,
                "bln" => $bulan,
                "thn" => $tahun,
            );
            $filters2 = array(
                "periode=" => $periode,
                "cabang_id=" => $cabangID,
                "date(dtime)<=" => $date2,
            );
            if ($pakai_ini == 1) {
                $cr->setFilters(array());
                $cr->setFilters2(array());
                $cr->setFilters($filters);
                $cr->setFilters2($filters2);
                $cr->addFilter("cabang_id='" . $cabangID . "'");
                if (isset($this->filters)) {
                    $setFilters = $this->filters;
                    foreach ($this->filters as $kf => $vf) {
                        $cr->addFilter("$kf='$vf'");
                    }
                }
                if (isset($this->filters2)) {
                    $cr->setFilters2($this->filters2);
                }
                $tmp = $cr->fetchAllBalances2();
                //            cekKuning($this->db->last_query());
                if (sizeof($tmp) > 0) {
                    $arrRek = array();
                    $arrRekSaldo = array();
                    foreach ($tmp as $rek => $rSpec) {
                        $arrRek[] = $rek;

                        $rSpec['debet'] = 0;
                        $rSpec['kredit'] = 0;
                        $arrRekSaldo[$rek] = $rSpec;
                    }
                }
                // membaca in/out mutasi masing-masing rekening...
                if (sizeof($arrRek) > 0) {
                    $arrMutasi = array();
                    foreach ($arrRek as $rek) {

                        $mts = New ComRekening_cli();
                        $mts->addFilter("cabang_id='$cabangID'");
//                        $mts->addFilter("date(dtime)>='$date1'");
//                        $mts->addFilter("date(dtime)<='$date2'");
                        $mts->addFilter("fulldate>='$date1'");
                        $mts->addFilter("fulldate<='$date2'");
                        $mts->addFilter("transaksi_id>'0'");
                        $arrMutasi[$rek] = $mts->fetchMoves($rek);
//                        cekLime($this->db->last_query());
                    }
                    if (sizeof($arrMutasi) > 0) {

                        $arrRekMutasi = array();
                        $arrMutasiResult = array();
                        foreach ($arrMutasi as $rek => $mSpec) {
                            foreach ($mSpec as $mmSpec) {

                                if (!isset($arrMutasiResult[$rek]["debet"])) {
                                    $arrMutasiResult[$rek]["debet"] = 0;
                                }
                                if (!isset($arrMutasiResult[$rek]["kredit"])) {
                                    $arrMutasiResult[$rek]["kredit"] = 0;
                                }

                                $arrMutasiResult[$rek]["rek_id"] = $mmSpec->rek_id;
                                $arrMutasiResult[$rek]["rekening"] = $mmSpec->rekening;
                                $arrMutasiResult[$rek]["debet"] += $mmSpec->debet;
                                $arrMutasiResult[$rek]["kredit"] += $mmSpec->kredit;
                                $arrMutasiResult[$rek]["periode"] = $periode;

                                $arrRekMutasi[$mmSpec->rekening] = $mmSpec->rekening;
                            }
                        }
                        //                arrPrint($arrMutasiResult);
                    }
                }


                // mengambil neraca terakhir....
                $ner = new MdlNeraca();
                $ner->addFilter("cabang_id='" . $cabangID . "'");
                $ner->addFilter("periode='$periode'");
                $ner->addFilter("trash='0'");
                $tmpLastNeraca = $ner->fetchBalances($tahunLast);
//showLast_query("biru");
//arrPrintWebs($tmpLastNeraca);
                $tmpRekNeraca = array();
                $tmpLastNeracaResult = array();
                if (sizeof($tmpLastNeraca) > 0) {
                    foreach ($tmpLastNeraca as $lnSpec) {
                        $rek = $lnSpec->rekening;
                        if (!isset($tmpLastNeracaResult[$rek]["debet"])) {
                            $tmpLastNeracaResult[$rek]["debet"] = 0;
                        }
                        if (!isset($tmpLastNeracaResult[$rek]["kredit"])) {
                            $tmpLastNeracaResult[$rek]["kredit"] = 0;
                        }
                        if (($lnSpec->debet > 0) && ($lnSpec->kredit > 0)) {
                            $val_detail = $lnSpec->debet - $lnSpec->kredit;
                            if ($val_detail > 0) {
                                $debet = $val_detail;
                                $kredit = 0;
                            }
                            else {
                                $debet = 0;
                                $kredit = $val_detail * -1;
                            }
                        }
                        else {
                            $debet = $lnSpec->debet;
                            $kredit = $lnSpec->kredit;
                        }
                        $tmpLastNeracaResult[$rek]["rek_id"] = $lnSpec->rek_id;
                        $tmpLastNeracaResult[$rek]["rekening"] = $lnSpec->rekening;
                        $tmpLastNeracaResult[$rek]["debet"] += $debet;
                        $tmpLastNeracaResult[$rek]["kredit"] += $kredit;
                        $tmpLastNeracaResult[$rek]["periode"] = $lnSpec->periode;

                        $tmpRekNeraca[$rek] = $rek;
                    }
                }

                $arrLajur = array();
                if (sizeof($tmpLastNeracaResult) > 0) {
                    foreach ($tmpLastNeracaResult as $rek => $spec) {
                        if ($spec['debet'] > 0 && $spec['kredit'] > 0) {
                            $value = $spec['debet'] - $spec['kredit'];
                            if ($value < 0) {
                                $debetLast = 0;
                                $kreditLast = $value * -1;
                            }
                            else {
                                $debetLast = $value;
                                $kreditLast = 0;
                            }
                        }
                        else {
                            $debetLast = $spec['debet'];
                            $kreditLast = $spec['kredit'];
                        }

                        if (isset($arrMutasiResult[$rek])) {
                            $debetMutasi = $arrMutasiResult[$rek]['debet'];
                            $kreditMutasi = $arrMutasiResult[$rek]['kredit'];
                        }
                        else {
                            $debetMutasi = 0;
                            $kreditMutasi = 0;
                        }
                        $defaultPosition = detectRekDefaultPosition($rek);
                        if ($defaultPosition == "debet") {
                            if ($debetLast > 0) {
                                $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
                            }
                            else {
                                $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi;
                            }
                            $saldo_kredit = 0;
                        }
                        elseif ($defaultPosition == "kredit") {
                            if ($kreditLast > 0) {
                                $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                                $saldo_debet = 0;
                            }
                            else {
                                $saldo_kredit = -$debetLast + $kreditMutasi - $debetMutasi;
                                $saldo_debet = 0;
                            }
                        }
                        $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                        $arrLajur[$rek]["rekening"] = $spec['rekening'];
                        $arrLajur[$rek]["debet"] = $saldo_debet;
                        $arrLajur[$rek]["kredit"] = $saldo_kredit;
                        $arrLajur[$rek]["periode"] = $spec['periode'];
                    }
                }
                if (sizeof($arrMutasiResult) > 0) {
                    foreach ($arrMutasiResult as $rek => $spec) {
                        if (!array_key_exists($rek, $tmpLastNeracaResult)) {
                            //                        cekKuning("memproses rekening $rek");
                            $debetMutasi = $spec['debet'];
                            $kreditMutasi = $spec['kredit'];
                            $debetLast = 0;
                            $kreditLast = 0;

                            $defaultPosition = detectRekDefaultPosition($rek);
                            if ($defaultPosition == "debet") {
                                $saldo_debet = $debetLast + $debetMutasi - $kreditMutasi;
                                $saldo_kredit = 0;
                            }
                            elseif ($defaultPosition == "kredit") {
                                $saldo_debet = 0;
                                $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                            }
                            $arrLajur[$rek]["rek_id"] = $spec['rek_id'];
                            $arrLajur[$rek]["rekening"] = $spec['rekening'];
                            $arrLajur[$rek]["debet"] = $saldo_debet;
                            $arrLajur[$rek]["kredit"] = $saldo_kredit;
                            $arrLajur[$rek]["periode"] = $spec['periode'];
                        }
                    }
                }

                $arrLajurNew = array();
                foreach ($arrLajur as $rek => $spec) {
                    if ($spec['debet'] < 0) {
                        $spec['kredit'] = $spec['debet'] * -1;
                        $spec['debet'] = 0;
                    }
                    if ($spec['kredit'] < 0) {
                        $spec['debet'] = $spec['kredit'] * -1;
                        $spec['kredit'] = 0;
                    }
                    if (!in_array($rek, $arrRekBlacklist)) {
                        $arrLajurNew[$rek] = $spec;
                    }
                }
//arrPrintWebs($arrLajurNew);
                $rl->setFilters2($filters2);
                $rl->setFilters($filters);
                $rl->pairNoCut_view($static, $arrLajurNew);
                $resultRL = $rl->execNoCut_view();

                $n->setFilters2($filters2);
                $n->setFilters($filters);
                $n->pairNoCut_view($static, $resultRL['neraca']);
                $resultNeraca = $n->execNoCut_view();


                $result_object = array();
                foreach ($resultNeraca as $ii => $rSpec) {
                    $result_object[$ii] = (object)$rSpec;
                }
                $resultNeracaByCabang[$cabangID][] = $result_object;
            }
            if ($pakai_ini == 2) {
                $r = New Rekening();
                $fulldate = "$tahun-$bulan-$tgl";
                $arrLajurNew = $r->saldoForever($cabangID, $periode, $fulldate);

                $rl->setFilters2($filters2);
                $rl->setFilters($filters);
                $rl->pairNoCut_view($static, $arrLajurNew);
                $resultRL = $rl->execNoCut_view();

                $n->setFilters2($filters2);
                $n->setFilters($filters);
                $n->pairNoCut_view($static, $resultRL['neraca']);
                $resultNeraca = $n->execNoCut_view();

                $result_object = array();
                foreach ($resultNeraca as $ii => $rSpec) {
                    $result_object[$ii] = (object)$rSpec;
                }
                $resultNeracaByCabang[$cabangID][] = $result_object;
            }
        }
        //        arrPrint($resultNeracaByCabang);
        // endregion rl year to date


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $tmp = $resultNeracaByCabang;
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        $defPos = detectRekDefaultPosition($row->rekening);

                        if (strlen($row->kategori) > 1) {
                            //                            if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                $rekenings[$row->cabang_id][$row->kategori] = array();
                            }


                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                            }

                            if (in_array($row->rekening, $accountException)) {
                                $debet = $row->kredit * -1;
                                $kredit = $row->debet * -1;
                            }
                            else {
                                switch ($defPos) {
                                    case "debet":
                                        if ($row->kredit > 0) {
                                            $debet = $row->kredit * -1;
                                            $kredit = 0;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    case "kredit":
                                        if ($row->debet > 0) {
                                            $debet = 0;
                                            $kredit = $row->debet * -1;
                                        }
                                        else {
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                        }
                                        break;
                                    default:
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                        break;
                                }
                                //                                    $debet = $row->debet;
                                //                                    $kredit = $row->kredit;
                            }


                            if (sizeof($accountCatException) > 0) {
                                foreach ($accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                        }
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                        }
                                        if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                        }

                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                        $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;

                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        //                                            $rekeningsName[$cat][$row->id] = $row->rekening;
                                    }
                                    else {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;

                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        //                                            $rekeningsName[$row->kategori][$row->id] = $row->rekening;
                                    }
                                }
                            }
                            else {
                                $rekenings[$row->kategori][] = $rekenings;
                            }


                            $whID = getDefaultWarehouseID($row->cabang_id);
                            $childLink = "";
                            if (isset($accountChilds[$row->rekening])) {
                                $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'
                                        target='_blank'>
                                        <span class='fa fa-clone'></span></a>";
                            }
                            $childLink2 = "$childLink <span class='pull-right'>
                                    <a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'
                                        target='_blank'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            //                            }
                        }
                    }

                }

            }
            //            reset($dates);
            //            $oldDate = key($dates);
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

//arrPrint($rekenings);
//arrPrint($rekeningsNameNew);

        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );

        $oldDate = $date1;
        $defaultDate = date("Y");
        $data = array(
            "mode" => $this->uri->segment(2),
            //            "mode" => "viewNeraca_consolidated",
            "title" => "Neraca Konsolidasi Year to Date ",
            "subTitle" => "Neraca Konsolidasi per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("finance", $data);


    }

    /* ---------------------------------
     *  JURNAL
     * ------------------------*/
    public function viewJurnal()
    {

        $this->load->model("Mdls/MdlPaymentSource");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("MdlTransaksi");

        $ju = new ComJurnal();
        $tr = new MdlTransaksi();
        $psrc = new MdlPaymentSource();
        //-------------------------------
        $rekeningAlias = $this->config->item("accountAlias") != NULL ? $this->config->item("accountAlias") : array();
        $codes_0 = arrCodeAliasing(my_cabang_id());
        $codeJurnals = arrCodeJurnalAliasing();
        foreach ($codeJurnals as $t_kode => $t_nama) {
            if (!isset($codes_0[$t_kode])) {
                $codes_0[$t_kode] = $t_nama;
            }
        }
        $codes = $codeJurnalAlias = $codes_0;
//        $codes = $codeJurnalAlias = array_intersect_key($codes_0, $codeJurnals);
        //-------------------------------
//arrPrintWebs($codes_0);
//arrPrintPink($codeJurnals);
//arrPrintPink($codes);

        $begin_date = $vd_start = isset($_GET['d_start']) ? $_GET['d_start'] : dtimeNow("Y-m-") . "01";
        $end_date = $vd_stop = isset($_GET['d_stop']) ? $_GET['d_stop'] : dtimeNow("Y-m-d");
        $jenis_jurnal = $vfx = isset($_GET['fx']) && ($_GET['fx'] != 'semua') ? $_GET['fx'] : "";
        $cabang_id = my_cabang_id();


        if ($cabang_id == CB_ID_PUSAT) {

            if (isset($_GET['fx']) && ($_GET['fx'] != 'semua')) {
                $condites = array(
                    "jenis" => $jenis_jurnal,
//                    "cabang_id" => $cabang_id,
                );
            }
            else {
                $condites = array(
                    "jenis !=" => "",
//                    "cabang_id" => $cabang_id,
                );
            }
        }
        else {
            if (isset($_GET['fx']) && ($_GET['fx'] != 'semua')) {
                $condites = array(
                    "jenis" => $jenis_jurnal,
                    "cabang_id" => $cabang_id,
                );
            }
            else {
                $condites = array(
                    "jenis !=" => "",
                    "cabang_id" => $cabang_id,
                );
            }
        }

        $this->db->where(
            array(
                "DATE(dtime)>=" => $begin_date,
                "DATE(dtime)<=" => $end_date,
            )
        );
        $this->db->order_by("id", "DESC");
        $juTmps = $ju->lookupByCondition($condites);
        $juJml = sizeof($juTmps->result());

        /* --------------------------------------
         * pengelompokan jurnal by jenis
         * ------------------------------------*/
        $jenisTrEfaktur = array(
            "110", "111", // ambil dari payment source
        );
        $regDatas = array(
            "description",
            "description_additional",
            "description_main_followup",
            "eFaktur",
        );
        $rekeningGetDatas = array(
            "hutang dagang" => "description_main_followup",
            "ppn in realisasi" => "eFaktur",
        );
        $jurnals = array();
        $djurnals = array();
        $kjurnals = array();
        $mainDatas = array();
        $addDatas = array();
        $trIDs = array();
        $regIDs = array();
        $pymSrcDatas = array();
        if (sizeof($juTmps->result()) > 0) {
            foreach ($juTmps->result() as $item) {
                $values['debet'] = $item->debet;
                $values['kredit'] = $item->kredit;
                $transaksi_id = $item->transaksi_id;
                $urut = $item->urut;
                $cabangID = $item->cabang_id;
                $rekening = $item->rekening;
                //---------------------------
//                if(array_key_exists($item->jenis, $jenisTrEfaktur)){
//
//                }
                //---------------------------

                $jurnals[$item->jenis][$transaksi_id][$urut][$item->rekening] = $values;
                if ($item->debet > 0) {
                    $djurnals[$item->jenis][$transaksi_id][$urut][$item->rekening]['debet'] = $item->debet;
                    //-----------------------
                    $addDatas[$item->jenis][$transaksi_id][$item->rekening] = array(
                        "link" => base_url() . "Ledger/viewMoveDetails_1/Rekening/$rekening/?o=$cabangID&date1=$begin_date&date2=$end_date&trID=$transaksi_id",

                    );
                }

                if ($item->kredit > 0) {
                    $kjurnals[$item->jenis][$transaksi_id][$urut][$item->rekening]['kredit'] = $item->kredit;
                    //-----------------------
                    $addDatas[$item->jenis][$transaksi_id][$item->rekening] = array(
                        "link" => base_url() . "Ledger/viewMoveDetails_1/Rekening/$rekening/?o=$cabangID&date1=$begin_date&date2=$end_date&trID=$transaksi_id",

                    );
                }

                $mainDatas[$transaksi_id] = $item;
            }
        }
        $transaksi_ids = array_keys($mainDatas);

        //------------------------------------
        $psrc->setFilters(array());
        $psrc->addFilter("jenis in ('" . implode("','", $jenisTrEfaktur) . "')");
        $psrcTmp = $psrc->lookupAll()->result();
//        showLast_query("kuning");
//        arrPrintPink($psrcTmp);
        if (sizeof($psrcTmp) > 0) {
            foreach ($psrcTmp as $psrcSpec) {
                $pymSrcDatas[$psrcSpec->transaksi_id] = array(
                    "efaktur" => $psrcSpec->extern_label2,
                );
            }
        }
//        arrPrintPink($pymSrcDatas);
        //------------------------------------


        $trRegDatas = array();
        $trDatas = array();
        if (sizeof($transaksi_ids) > 0) {
//            arrPrint($transaksi_ids);
            $selectedFields = array(
                "id",
                "dtime",
                "nomer",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama",
                "suppliers_id",
                "suppliers_nama",
                "cabang_id",
                "cabang_nama",
                "gudang_id",
                "gudang_nama",
                "counters",
                "ids_his",
                "jenis",
                "indexing_registry",
            );
            $tr->setFilters(array());
            $this->db->select($selectedFields);
            $this->db->where_in("id", $transaksi_ids);
            $trTmps_0 = $tr->lookupAll();
            $trTmps = $trTmps_0->result();
            // showLast_query("orange");
            $trDatas = array();
            if (sizeof($trTmps) > 0) {
                foreach ($trTmps as $trTmp) {
                    $trDatas[$trTmp->id] = $trTmp;
                    if ($trTmp->indexing_registry != NULL) {
                        $index_regDecode = blobDecode($trTmp->indexing_registry);
                        $regIDs[$index_regDecode['main']] = $index_regDecode['main'];
                    }
                }
            }


            // membaca registry MAIN, sesuai transaksiID
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
//            $tr->addFilter("id in ('" . implode("','", $regIDs) . "')");
//            $regTmp = $tr->lookupRegistries()->result();
            $fields = array("main");
            $tr->setJointSelectFields(implode(",", $fields) . ", transaksi_id");
            $tr->addFilter("transaksi_id in ('" . implode("','", $transaksi_ids) . "')");
            $regTmp = $tr->lookupDataRegistries()->result();
//            showLast_query("biru");
//            mati_disini();

            if (sizeof($regTmp) > 0) {
                foreach ($regTmp as $regSpec) {
//                    arrPrintWebs($regSpec);
                    foreach ($regSpec as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $regValues = blobDecode($val_reg);
                            foreach ($regDatas as $kolom) {
                                $trRegDatas[$regSpec->transaksi_id][$kolom] = isset($regValues[$kolom]) ? $regValues[$kolom] : "";
                            }

                        }
                    }
//                    arrPrintPink($trRegDatas);
//                    mati_disini();
                }
            }
//            arrPrintPink($trRegDatas);
            foreach ($addDatas as $jenisTr => $dSpec) {
                foreach ($dSpec as $trID => $ddSpec) {
                    foreach ($ddSpec as $rekName => $anu) {
                        if (array_key_exists($rekName, $rekeningGetDatas)) {
                            $kolom = $rekeningGetDatas[$rekName];
                            $add_data = isset($trRegDatas[$trID][$kolom]) ? $trRegDatas[$trID][$kolom] : "";
                            $addDatas[$jenisTr][$trID][$rekName]['referensi'] = $add_data;
                        }
                        else {
                            $addDatas[$jenisTr][$trID][$rekName]['referensi'] = "-";
                        }
                    }
                }
            }
        }

        $srcTrId_e = blobEncode($transaksi_ids);
        $srcDatas_e = blobEncode($juTmps);
        $srcTrans_e = blobEncode($trTmps_0);
        $wadahData["jurnal"] = $srcDatas_e;
        $wadahData["transaksi"] = $srcTrans_e;
        $wadahData_e = blobEncode($wadahData);
        $dateRange = isset($_GET['d_start']) ? "&d_start=$begin_date&d_stop=$end_date" : "";
        $fxJurnal = isset($_GET['fx']) ? "&fx=$_GET[fx]" : "";
        $strRangeDate = isset($_GET['d_start']) ? (strlen($begin_date) == 0 ? "-00-$end_date" : "-$begin_date-$end_date") : "";
        $strJenis_jurnal = strlen($jenis_jurnal) == 0 ? "semua" : $codeJurnalAlias[$jenis_jurnal];

        //---------------------------------
        $excel_link = base_url() . "ExcelWriter/jurnal?cb=$cabang_id" . $dateRange . $fxJurnal;
        $excel_data = "cb=$cabang_id" . $dateRange . $fxJurnal;
        $excel_nama = "jurnal-$strJenis_jurnal" . $strRangeDate . "-" . dtimeNow('His');
        //---------------------------------

        //---------------------------------
        foreach ($codes as $jenis_code => $jenis_nama) {
            $codes[$jenis_code] = strtoupper($jenis_nama);
        }
        //---------------------------------

        // $excel_link = base_url() . "ExcelWriter/jurnal/$wadahData_e";
        $data = array(
            //            "mode" => $this->uri->segment(2),
            "mode" => "jurnal",
            "title" => "Jurnal",
            "subTitle" => "",
            "transaksiDatas" => $trDatas,
            "mainDatas" => $mainDatas,
            "jurnalJmlRow" => $juJml,
            "jurnalDatas" => $jurnals,
            "djurnalDatas" => $djurnals,
            "kjurnalDatas" => $kjurnals,
            "jenisAlias" => $codes,
            "jenisAliasing" => $codeJurnals,
            "beginDate" => $begin_date,
            "endDate" => $end_date,
            "jenisJurnal" => $jenis_jurnal,
            "excel_link" => $excel_link,
            "excel_data" => $excel_data,
            "excel_nama" => $excel_nama,
            "rekeningAlias" => $rekeningAlias,
            //----------------------
            "addDatas" => $addDatas,
        );
        $this->load->view("jurnal", $data);
    }

    //==============================================================================================================
    public function viewEfisiensiBiayaOLD()
    {

        $this->placeID = $this->session->login['cabang_id'];
        $this->gudangID = $this->session->login['gudang_id'];
        $this->gudangName = $this->session->login['gudang_nama'];

        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $item_detail = $this->uri->segment(4);
        $cabangID = $this->placeID;


        $mdlNameS = $this->config->item('heEfisiensi') ? $this->config->item('heEfisiensi') : array();
//        $tbl_bl = ((sizeof($mdlNameS) > 0) && (isset($mdlNameS[$item]['tblMutasi']))) ? $mdlNameS[$item]['tblMutasi'] : "";
        if ((sizeof($mdlNameS) > 0)) {
            if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                if ((isset($mdlNameS[$item]['tblMutasiDetail']))) {
                    $tbl_bl = $mdlNameS[$item]['tblMutasiDetail'];
                }
                else {
                    $tbl_bl = "";
                }
            }
            else {
                if ((isset($mdlNameS[$item]['tblMutasi']))) {
                    $tbl_bl = $mdlNameS[$item]['tblMutasi'];
                }
                else {
                    $tbl_bl = "";
                }
            }
        }
        else {
            $tbl_bl = "";
        }


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $additionalRek = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['additionalRek'] : array();


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            $dRange = "";
            $dateRange = "$dRange";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            $dRange = formatField("auth_dtime", $date1) . " s/d " . formatField("auth_dtime", $date2);
            $dateRange = "($dRange)";
        }


        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();

        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {

            foreach ($mdlNameS[$item]['mdlFieldsDetail'] as $field => $fChilds) {
                $fields[] = $field;
                isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
                isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
                isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
                isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
            }
        }
        else {

            foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
                $fields[] = $field;
                isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
                isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
                isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
                isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
            }
        }

        $mainHeaders_1 = array();
        if (isset($headers) && sizeof($headers) > 0) {
            foreach ($headers as $hKey => $hSpecs) {
                $mSpecs[$hSpecs["label"]] = $hSpecs['attr'];
                $mainHeaders_1 = $mSpecs;
            }
        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$field] = array(
                "attr" => $fAttr,
                "label" => $fieldToshow,
            );

            $mainHeaders_00 = $specs_0;

        }
        $mainHeaders = $mainHeaders_00;
        //endregion


        //region ambil mutasi periode saat ini
        $list_produkId = "";
//        $bl = formatTanggal($date, "n");
//        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            "cabang_id" => $cabangID,
        );
        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $this->db->order_by("id", "ASC");
        }
        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
            $this->db->where("extern2_id='" . $item_detail . "'");
        }
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
//        showLast_query("merah");
//        arrPrint($srcDatas);
        //endregion
        $rekID_detailLink = array(999);


        $items = array();
        // region ambil mutasi additional
        if (sizeof($additionalRek) > 0) {
            $addComs = isset($additionalRek['com']) ? $additionalRek['com'] : "";
//            $addTblName = isset($additionalRek['tblMutasi']) ? $additionalRek['tblMutasi'] : "";
            $addJenisTr = isset($additionalRek['jenisTransaksi']) ? $additionalRek['jenisTransaksi'] : array();
            $addRekening = isset($additionalRek['rekening']) ? $additionalRek['rekening'] : "";
            $addPosition = isset($additionalRek['position']) ? $additionalRek['position'] : "";

            $this->load->model("Coms/" . $addComs);


            if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                $addTblName = isset($additionalRek['tblMutasiDetail']) ? $additionalRek['tblMutasiDetail'] : "";
            }
            else {
                $addTblName = isset($additionalRek['tblMutasi']) ? $additionalRek['tblMutasi'] : "";
            }

            $arrWhere = array(
                "cabang_id" => $cabangID,
            );
            if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

            }
            else {
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");
                $this->db->order_by("id", "ASC");
            }
            if (sizeof($addJenisTr) > 0) {
                $this->db->where_in("jenis", implode(",", $addJenisTr));
            }

            $this->db->where($arrWhere);
            $addSrcDatas = $this->db->get($addTblName)->result();
            if (sizeof($addSrcDatas) > 0) {
                foreach ($addSrcDatas as $addSpec) {
                    if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                        $rekeningNama = "bom_" . $addSpec->extern_id;
                        $addRekenings = $addSpec->extern_nama;
                    }
                    else {
                        $rekeningNama = $addSpec->rekening;
                        $addRekenings = $addRekening;
                    }

                    if (!isset($items[$rekeningNama]['debet'])) {
                        $items[$rekeningNama]['debet'] = 0;
                    }
                    if (!isset($items[$rekeningNama]['kredit'])) {
                        $items[$rekeningNama]['kredit'] = 0;
                    }
                    if (!isset($items[$rekeningNama]['qty_debet'])) {
                        $items[$rekeningNama]['qty_debet'] = 0;
                    }
                    if (!isset($items[$rekeningNama]['qty_kredit'])) {
                        $items[$rekeningNama]['qty_kredit'] = 0;
                    }
                    switch ($addPosition) {
                        case "debet":
                            $debet = $addSpec->debet;
                            $kredit = 0;
                            $qty_debet = $addSpec->qty_debet;
                            $qty_kredit = 0;
                            break;
                        case "kredit":
                            $debet = 0;
                            $kredit = $addSpec->kredit;
                            $qty_debet = 0;
                            $qty_kredit = $addSpec->qty_kredit;
                            break;
                    }

                    $items[$rekeningNama]['nama'] = $addRekenings;
                    $items[$rekeningNama]['debet'] += $debet;
                    $items[$rekeningNama]['kredit'] += $kredit;
                    $items[$rekeningNama]['qty_debet'] += $qty_debet;
                    $items[$rekeningNama]['qty_kredit'] += $qty_kredit;
                    $items[$rekeningNama]['balance'] = 0;
                    $items[$rekeningNama]['qty_balance'] = 0;
                }
            }
        }
//        arrPrint($items);
        // endregion


        $arrSum = array(
            "debet",
            "kredit",
            "balance",
        );
        $arrSumView = array(
            "debet",
            "kredit",
            "balance",
            "qty_debet",
            "qty_kredit",
            "qty_balance",
        );

//arrPrint($srcDatas);
        if (sizeof($srcDatas) > 0) {
            foreach ($srcDatas as $ii => $dSpec) {


                $externID = $dSpec->extern_id;
                $cabangID = $dSpec->cabang_id;

                if (!isset($items[$externID]['debet'])) {
                    $items[$externID]['debet'] = 0;
                }
                if (!isset($items[$externID]['kredit'])) {
                    $items[$externID]['kredit'] = 0;
                }
                if (!isset($items[$externID]['balance'])) {
                    $items[$externID]['balance'] = 0;
                }
                if (!isset($items[$externID]['qty_debet'])) {
                    $items[$externID]['qty_debet'] = 0;
                }
                if (!isset($items[$externID]['qty_kredit'])) {
                    $items[$externID]['qty_kredit'] = 0;
                }
                if (!isset($items[$externID]['qty_balance'])) {
                    $items[$externID]['qty_balance'] = 0;
                }

                if (isset($fieldLink['nama'])) {
                    if (in_array($externID, $rekID_detailLink)) {

                        $items[$externID]['nama'] = "<a target=\"_blank\" href=\"" . base_url() . "Neraca/viewEfisiensiBiaya/bom/" . $externID . "/?o=$cabangID&m=detail\">" . $dSpec->extern_nama . "</a>";
                    }
                    else {

                        $items[$externID]['nama'] = $dSpec->extern_nama;
                    }

                    if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                        $items[$externID]['nama'] .= "<span class='pull-right'><a target=\"_blank\" href=\"" . base_url() . $fieldLink['nama'] . $externID . "/?o=$cabangID\"><span class='glyphicon glyphicon-time'></span></a></span>";
                    }
                    else {
                        $items[$externID]['nama'] .= "<span class='pull-right'><a target=\"_blank\" href=\"" . base_url() . $fieldLink['nama'] . $externID . "/?o=$cabangID\"><span class='glyphicon glyphicon-time'></span></a></span>";
//                    $items[$externID]['nama'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink['nama'] . $externID . "/?o=$cabangID\">" . $dSpec->extern_nama . "</a>";
                    }
                }
                else {
                    $items[$externID]['nama'] = $dSpec->extern_nama;
                }


                $items[$externID]['id'] = $externID;
                $items[$externID]['rekening'] = $dSpec->rekening;
                $items[$externID]['debet'] += $dSpec->debet;
                $items[$externID]['kredit'] += $dSpec->kredit;
                $items[$externID]['qty_debet'] += $dSpec->qty_debet;
                $items[$externID]['qty_kredit'] += $dSpec->qty_kredit;
                $items[$externID]['balance'] += ($dSpec->kredit - $dSpec->debet);
                $items[$externID]['qty_balance'] += ($dSpec->qty_kredit - $dSpec->qty_debet);

            }
        }


        $lastDay = formatTanggal($date1, "t");


        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        // region footers
        $footers = array(
            "total" => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers


        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $items_new = array();
            if (sizeof($items) > 0) {
                if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                    foreach ($items as $key => $spec) {
                        $opname_kredit = 0;
                        $opname_debet = 0;

                        if (substr($key, 0, 4) == "bom_") {
                            $anggaran = $spec['kredit'];
                            $items[$key] = NULL;
                            unset($items[$key]);

                            $key_new = str_replace("bom_", "", $key);

                            if (!isset($items_new[$key_new]['kredit'])) {
                                $items_new[$key_new]['kredit'] = 0;
                            }
                            if (!isset($items_new[$key_new]['qty_kredit'])) {
                                $items_new[$key_new]['qty_kredit'] = 0;
                            }

                            $items_new[$key_new]['id'] = isset($spec['id']) ? $spec['id'] : "";
                            $items_new[$key_new]['rekening'] = isset($spec['rekening']) ? $spec['rekening'] : "";
                            $items_new[$key_new]['balance'] = isset($spec['balance']) ? $spec['balance'] : "";
                            $items_new[$key_new]['qty_balance'] = isset($spec['qty_balance']) ? $spec['qty_balance'] : "";
                            $items_new[$key_new]['nama'] = $spec['nama'];
                            $items_new[$key_new]['kredit'] += $spec['kredit'];
                            $items_new[$key_new]['qty_kredit'] += $spec['qty_kredit'];
                        }
                        else {
                            if (!isset($items_new[$key]['opname_kredit'])) {
                                $items_new[$key]['opname_kredit'] = 0;
                            }
                            if (!isset($items_new[$key]['qty_opname_kredit'])) {
                                $items_new[$key]['qty_opname_kredit'] = 0;
                            }
                            if (!isset($items_new[$key]['opname_debet'])) {
                                $items_new[$key]['opname_debet'] = 0;
                            }
                            if (!isset($items_new[$key]['qty_opname_debet'])) {
                                $items_new[$key]['qty_opname_debet'] = 0;
                            }

                            $items_new[$key]['id'] = isset($spec['id']) ? $spec['id'] : "";
                            $items_new[$key]['rekening'] = isset($spec['rekening']) ? $spec['rekening'] : "";
                            $items_new[$key]['balance'] = isset($spec['balance']) ? $spec['balance'] : "";
                            $items_new[$key]['qty_balance'] = isset($spec['qty_balance']) ? $spec['qty_balance'] : "";
                            $items_new[$key]['opname_kredit'] += $spec['kredit'];
                            $items_new[$key]['qty_opname_kredit'] += $spec['qty_kredit'];
                            $items_new[$key]['opname_debet'] += $spec['debet'];
                            $items_new[$key]['qty_opname_debet'] += $spec['qty_debet'];
                            $items_new[$key]['nama'] = $spec['nama'];
                        }
                    }

                    $items = array();
                    foreach ($items_new as $pID => $spec) {
                        $kredit = isset($spec['kredit']) ? $spec['kredit'] : 0;
                        $opname_kredit = isset($spec['opname_kredit']) ? $spec['opname_kredit'] : 0;
                        $opname_debet = isset($spec['opname_debet']) ? $spec['opname_debet'] : 0;

                        $qty_kredit = isset($spec['qty_kredit']) ? $spec['qty_kredit'] : 0;
                        $qty_opname_kredit = isset($spec['qty_opname_kredit']) ? $spec['qty_opname_kredit'] : 0;
                        $qty_opname_debet = isset($spec['qty_opname_debet']) ? $spec['qty_opname_debet'] : 0;

                        $spec['debet'] = $kredit - $opname_kredit + $opname_debet;
                        $spec['qty_debet'] = $qty_kredit - $qty_opname_kredit + $qty_opname_debet;

                        $items[$pID] = $spec;
                    }


                }
                else {
                    $anggaran = 0;
                    $opname_kredit = 0;
                    $opname_debet = 0;
                    if (isset($items['persediaan supplies'])) {
                        $anggaran = isset($items['persediaan supplies']['kredit']) ? $items['persediaan supplies']['kredit'] : 0;
                    }
                    if (isset($items['999'])) {
                        $opname_kredit = isset($items['999']['kredit']) ? $items['999']['kredit'] : 0;
                        $opname_debet = isset($items['999']['debet']) ? $items['999']['debet'] : 0;
                    }
                    $riil = $anggaran - $opname_kredit + $opname_debet;
//                cekPink("$anggaran -- $opname_kredit -- $opname_debet -- $riil");

                    // replace array...
                    if (isset($items['persediaan supplies'])) {
                        $items['persediaan supplies'] = NULL;
                        unset($items['persediaan supplies']);
                    }
                    $items['999']['debet'] = $riil;
                    $items['999']['kredit'] = $anggaran;
                }
            }
        }
//        arrPrint($items);


        $btnGroups = array();
        $data = array(
            "mode" => "efisiensi",
            "title" => "$itemLabel &nbsp; $dateRange",
            "subTitle" => "",
            "date1" => $date1,
            "date2" => $date2,

            "mainHeaders" => $mainHeaders,
            "items" => $items,
            "footers" => $footers,
            "sumfooters" => $arrSumView,
            "footersBlacklist" => array("no", "id", "kode", "nama"),
            "mdlFields" => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),
            "filters" => array(
                "dates" => array(
                    "end" => $date2,
                ),
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );


        $this->load->view("ledger", $data);
    }

    public function viewEfisiensiBiaya()
    {

        $this->placeID = $this->session->login['cabang_id'];
        $this->gudangID = $this->session->login['gudang_id'];
        $this->gudangName = $this->session->login['gudang_nama'];

        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $item_detail = $this->uri->segment(4);
        $cabangID = isset($_GET['o']) ? $_GET['o'] : $this->placeID;


        $mdlNameS = $this->config->item('heEfisiensi') ? $this->config->item('heEfisiensi') : array();
        if ((sizeof($mdlNameS) > 0)) {
            if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                if ((isset($mdlNameS[$item]['tblMutasiDetail']))) {
                    $tbl_bl = $mdlNameS[$item]['tblMutasiDetail'];
                }
                else {
                    $tbl_bl = "";
                }
            }
            else {
                if ((isset($mdlNameS[$item]['tblMutasi']))) {
                    $tbl_bl = $mdlNameS[$item]['tblMutasi'];
                }
                else {
                    $tbl_bl = "";
                }
            }
        }
        else {
            $tbl_bl = "";
        }


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $additionalRek = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['additionalRek'] : array();
        $additionalRek2 = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['additionalRek2'] : array();
        $positionRek = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['positionRek'] : array();
        $viewEfisiensi = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['view'] : array();
        $accountAlias = NULL != $this->config->item("accountAlias") ? $this->config->item("accountAlias") : array();
//arrPrintWebs($accountAlias);

        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            $dRange = "";
            $dateRange = "$dRange";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
            $dRange = formatField("auth_dtime", $date1) . " s/d " . formatField("auth_dtime", $date2);
            $dateRange = "($dRange)";
        }


        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();

        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {

            foreach ($mdlNameS[$item]['mdlFieldsDetail'] as $field => $fChilds) {
                $fields[] = $field;
                isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
                isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
                isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
                isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
            }
        }
        else {

            foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
                $fields[] = $field;
                isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
                isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
                isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
                isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
                isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
            }
        }

        $mainHeaders_1 = array();
        if (isset($headers) && sizeof($headers) > 0) {
            foreach ($headers as $hKey => $hSpecs) {
                $mSpecs[$hSpecs["label"]] = $hSpecs['attr'];
                $mainHeaders_1 = $mSpecs;
            }
        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$field] = array(
                "attr" => $fAttr,
                "label" => $fieldToshow,
            );

            $mainHeaders_00 = $specs_0;

        }
        $mainHeaders = $mainHeaders_00;
        //endregion


        //region ambil mutasi periode saat ini
        $list_produkId = "";
        $arrWhere = array(
            "cabang_id" => $cabangID,
        );
        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $this->db->order_by("id", "ASC");
        }
        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
            $this->db->where("extern2_id='" . $item_detail . "'");
        }
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
        showLast_query("merah");
//        arrPrint($srcDatas);
        //endregion

        $rekID_detailLink = array(999);


        $items = array();
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            // region ambil mutasi additional
            if (sizeof($additionalRek) > 0) {
                $addComs = isset($additionalRek['com']) ? $additionalRek['com'] : "";
                $addJenisTr = isset($additionalRek['jenisTransaksi']) ? $additionalRek['jenisTransaksi'] : array();
                $addRekening = isset($additionalRek['rekening']) ? $additionalRek['rekening'] : "";
                $addPosition = isset($additionalRek['position']) ? $additionalRek['position'] : "";

                $this->load->model("Coms/" . $addComs);


                if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                    $addTblName = isset($additionalRek['tblMutasiDetail']) ? $additionalRek['tblMutasiDetail'] : "";
                }
                else {
                    $addTblName = isset($additionalRek['tblMutasi']) ? $additionalRek['tblMutasi'] : "";
                }

                $arrWhere = array(
                    "cabang_id" => $cabangID,
                );
                if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

                }
                else {
                    $this->db->where("fulldate>='" . $date1 . "'");
                    $this->db->where("fulldate<='" . $date2 . "'");
                    $this->db->order_by("id", "ASC");
                }
                if (sizeof($addJenisTr) > 0) {
                    $this->db->where_in("jenis", implode(",", $addJenisTr));
                }

                $this->db->where($arrWhere);
                $addSrcDatas = $this->db->get($addTblName)->result();
                showLast_query("pink2");
                if (sizeof($addSrcDatas) > 0) {
                    foreach ($addSrcDatas as $addSpec) {
                        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                            $rekeningNama = "bom_" . $addSpec->extern_id;
                            $addRekenings = $addSpec->extern_nama;
                        }
                        else {
                            $rekeningNama = $addSpec->rekening;
                            $addRekenings = $addRekening;
                        }

                        if (!isset($items[$rekeningNama]['debet'])) {
                            $items[$rekeningNama]['debet'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['kredit'])) {
                            $items[$rekeningNama]['kredit'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['qty_debet'])) {
                            $items[$rekeningNama]['qty_debet'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['qty_kredit'])) {
                            $items[$rekeningNama]['qty_kredit'] = 0;
                        }
                        switch ($addPosition) {
                            case "debet":
                                $debet = $addSpec->debet;
                                $kredit = 0;
                                $qty_debet = $addSpec->qty_debet;
                                $qty_kredit = 0;
                                break;
                            case "kredit":
                                $debet = 0;
                                $kredit = $addSpec->kredit;
                                $qty_debet = 0;
                                $qty_kredit = $addSpec->qty_kredit;
                                break;
                        }

                        $items[$rekeningNama]['nama'] = $addRekenings;
                        $items[$rekeningNama]['debet'] += $debet;
                        $items[$rekeningNama]['kredit'] += $kredit;
                        $items[$rekeningNama]['qty_debet'] += $qty_debet;
                        $items[$rekeningNama]['qty_kredit'] += $qty_kredit;
                        $items[$rekeningNama]['balance'] = 0;
                        $items[$rekeningNama]['qty_balance'] = 0;
                    }
                }
            }

            if (sizeof($additionalRek2) > 0) {
                $addComs = isset($additionalRek2['com']) ? $additionalRek2['com'] : "";
                $addJenisTr = isset($additionalRek2['jenisTransaksi']) ? $additionalRek2['jenisTransaksi'] : array();
                $addRekening = isset($additionalRek2['rekening']) ? $additionalRek2['rekening'] : "";
                $addPosition = isset($additionalRek2['position']) ? $additionalRek2['position'] : "";

                $this->load->model("Coms/" . $addComs);


                if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                    $addTblName = isset($additionalRek2['tblMutasiDetail']) ? $additionalRek2['tblMutasiDetail'] : "";
                }
                else {
                    $addTblName = isset($additionalRek2['tblMutasi']) ? $additionalRek2['tblMutasi'] : "";
                }

                $arrWhere = array(
                    "cabang_id" => $cabangID,
                );
                if (!isset($_GET['date1']) && !isset($_GET['date2'])) {

                }
                else {
                    $this->db->where("fulldate>='" . $date1 . "'");
                    $this->db->where("fulldate<='" . $date2 . "'");
                    $this->db->order_by("id", "ASC");
                }
                if (sizeof($addJenisTr) > 0) {
                    $this->db->where_in("jenis", implode(",", $addJenisTr));
                }

                $this->db->where($arrWhere);
                $addSrcDatas = $this->db->get($addTblName)->result();
                showLast_query("pink2");
                if (sizeof($addSrcDatas) > 0) {
                    foreach ($addSrcDatas as $addSpec) {
//                        arrPrintWebs($addSpec);
                        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                            $rekeningNama = "bom_" . $addSpec->extern_id;
                            $addRekenings = $addSpec->extern_nama;
                            $addRekenings_orig = "";
                        }
                        else {
                            $rekeningNama = $addSpec->rekening == "persediaan supplies proses" ? "persediaan supplies" : $addSpec->rekening;
                            $addRekenings = $rekeningNama;
//                            $addRekenings = $addRekening;
                            $addRekenings_orig = $addSpec->rekening;
                        }

                        if (!isset($items[$rekeningNama]['debet'])) {
                            $items[$rekeningNama]['debet'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['kredit'])) {
                            $items[$rekeningNama]['kredit'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['qty_debet'])) {
                            $items[$rekeningNama]['qty_debet'] = 0;
                        }
                        if (!isset($items[$rekeningNama]['qty_kredit'])) {
                            $items[$rekeningNama]['qty_kredit'] = 0;
                        }
                        switch ($addPosition) {
                            case "debet":
                                $debet = $addSpec->debet;
                                $kredit = 0;
                                $qty_debet = $addSpec->qty_debet;
                                $qty_kredit = 0;
                                break;
                            case "kredit":
                                $debet = 0;
                                $kredit = $addSpec->kredit;
                                $qty_debet = 0;
                                $qty_kredit = $addSpec->qty_kredit;
                                break;
                        }

                        $items[$rekeningNama]['nama_orig'] = $addRekenings_orig;
                        $items[$rekeningNama]['nama'] = $addRekenings;
                        $items[$rekeningNama]['debet'] += $debet;
                        $items[$rekeningNama]['kredit'] += $kredit;
                        $items[$rekeningNama]['qty_debet'] += $qty_debet;
                        $items[$rekeningNama]['qty_kredit'] += $qty_kredit;
                        $items[$rekeningNama]['balance'] = 0;
                        $items[$rekeningNama]['qty_balance'] = 0;
                    }
                }
            }
            // endregion

        }
//arrPrintWebs($items);

        $arrSum = array(
            "debet",
            "kredit",
            "balance",
        );
        $arrSumView = array(
            "debet",
            "kredit",
            "balance",
            "qty_debet",
            "qty_kredit",
            "qty_balance",
        );

        if (sizeof($srcDatas) > 0) {
            foreach ($srcDatas as $ii => $dSpec) {

                $externID = $dSpec->extern_id;
                $cabangID = $dSpec->cabang_id;
                if (!in_array($externID, $rekID_detailLink)) {

                    if (!isset($items[$externID]['debet'])) {
                        $items[$externID]['debet'] = 0;
                    }
                    if (!isset($items[$externID]['kredit'])) {
                        $items[$externID]['kredit'] = 0;
                    }
                    if (!isset($items[$externID]['balance'])) {
                        $items[$externID]['balance'] = 0;
                    }
                    if (!isset($items[$externID]['qty_debet'])) {
                        $items[$externID]['qty_debet'] = 0;
                    }
                    if (!isset($items[$externID]['qty_kredit'])) {
                        $items[$externID]['qty_kredit'] = 0;
                    }
                    if (!isset($items[$externID]['qty_balance'])) {
                        $items[$externID]['qty_balance'] = 0;
                    }

                    if (isset($fieldLink['nama'])) {
                        if (in_array($externID, $rekID_detailLink)) {

                            $items[$externID]['nama'] = "<a target=\"_blank\" href=\"" . base_url() . "Neraca/viewEfisiensiBiaya/bom/" . $externID . "/?o=$cabangID&m=detail\">" . $dSpec->extern_nama . "</a>";
                        }
                        else {

                            $items[$externID]['nama'] = $dSpec->extern_nama;
                        }

                        if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                            $items[$externID]['link'] = "<span class='pull-right'><a target=\"_blank\" href=\"" . base_url() . $fieldLink['nama'] . $externID . "/?o=$cabangID\"><span class='glyphicon glyphicon-time'></span></a></span>";
                        }
                        else {
                            $items[$externID]['link'] = "<span class='pull-right'><a target=\"_blank\" href=\"" . base_url() . $fieldLink['nama'] . $externID . "/?o=$cabangID\"><span class='glyphicon glyphicon-time'></span></a></span>";

                        }
                    }
                    else {
                        $items[$externID]['nama'] = $dSpec->extern_nama;
                    }


                    $items[$externID]['id'] = $externID;
                    $items[$externID]['rekening'] = $dSpec->rekening;
                    $items[$externID]['debet'] += $dSpec->debet;
                    $items[$externID]['kredit'] += $dSpec->kredit;
                    $items[$externID]['qty_debet'] += $dSpec->qty_debet;
                    $items[$externID]['qty_kredit'] += $dSpec->qty_kredit;
                    $items[$externID]['balance'] += ($dSpec->kredit - $dSpec->debet);
                    $items[$externID]['qty_balance'] += ($dSpec->qty_kredit - $dSpec->qty_debet);
                }

            }
        }
//        arrPrintWebs($items);

        $lastDay = formatTanggal($date1, "t");


        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        // region footers
        $footers = array(
            "total" => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers

        //---------------------------------
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $items_new = array();
            if (sizeof($items) > 0) {
                if (isset($_GET['m']) && ($_GET['m'] == "detail")) {
                    foreach ($items as $key => $spec) {
                        $opname_kredit = 0;
                        $opname_debet = 0;

                        if (substr($key, 0, 4) == "bom_") {
                            $anggaran = $spec['kredit'];
                            $items[$key] = NULL;
                            unset($items[$key]);

                            $key_new = str_replace("bom_", "", $key);

                            if (!isset($items_new[$key_new]['kredit'])) {
                                $items_new[$key_new]['kredit'] = 0;
                            }
                            if (!isset($items_new[$key_new]['qty_kredit'])) {
                                $items_new[$key_new]['qty_kredit'] = 0;
                            }

                            $items_new[$key_new]['id'] = isset($spec['id']) ? $spec['id'] : "";
                            $items_new[$key_new]['rekening'] = isset($spec['rekening']) ? $spec['rekening'] : "";
                            $items_new[$key_new]['balance'] = isset($spec['balance']) ? $spec['balance'] : "";
                            $items_new[$key_new]['qty_balance'] = isset($spec['qty_balance']) ? $spec['qty_balance'] : "";
                            $items_new[$key_new]['nama'] = $spec['nama'];
                            $items_new[$key_new]['kredit'] += $spec['kredit'];
                            $items_new[$key_new]['qty_kredit'] += $spec['qty_kredit'];
                        }
                        else {
                            if (!isset($items_new[$key]['opname_kredit'])) {
                                $items_new[$key]['opname_kredit'] = 0;
                            }
                            if (!isset($items_new[$key]['qty_opname_kredit'])) {
                                $items_new[$key]['qty_opname_kredit'] = 0;
                            }
                            if (!isset($items_new[$key]['opname_debet'])) {
                                $items_new[$key]['opname_debet'] = 0;
                            }
                            if (!isset($items_new[$key]['qty_opname_debet'])) {
                                $items_new[$key]['qty_opname_debet'] = 0;
                            }

                            $items_new[$key]['id'] = isset($spec['id']) ? $spec['id'] : "";
                            $items_new[$key]['rekening'] = isset($spec['rekening']) ? $spec['rekening'] : "";
                            $items_new[$key]['balance'] = isset($spec['balance']) ? $spec['balance'] : "";
                            $items_new[$key]['qty_balance'] = isset($spec['qty_balance']) ? $spec['qty_balance'] : "";
                            $items_new[$key]['opname_kredit'] += $spec['kredit'];
                            $items_new[$key]['qty_opname_kredit'] += $spec['qty_kredit'];
                            $items_new[$key]['opname_debet'] += $spec['debet'];
                            $items_new[$key]['qty_opname_debet'] += $spec['qty_debet'];
                            $items_new[$key]['nama'] = $spec['nama'];
                        }
                    }

                    $items = array();
                    foreach ($items_new as $pID => $spec) {
                        $kredit = isset($spec['kredit']) ? $spec['kredit'] : 0;
                        $opname_kredit = isset($spec['opname_kredit']) ? $spec['opname_kredit'] : 0;
                        $opname_debet = isset($spec['opname_debet']) ? $spec['opname_debet'] : 0;

                        $qty_kredit = isset($spec['qty_kredit']) ? $spec['qty_kredit'] : 0;
                        $qty_opname_kredit = isset($spec['qty_opname_kredit']) ? $spec['qty_opname_kredit'] : 0;
                        $qty_opname_debet = isset($spec['qty_opname_debet']) ? $spec['qty_opname_debet'] : 0;

                        $spec['debet'] = $kredit - $opname_kredit + $opname_debet;
                        $spec['qty_debet'] = $qty_kredit - $qty_opname_kredit + $qty_opname_debet;

                        $items[$pID] = $spec;
                    }


                }
                else {
                    $anggaran = 0;
//                    $opname_kredit = 0;
//                    $opname_debet = 0;
                    if (isset($items['persediaan supplies'])) {
                        $anggaran = isset($items['persediaan supplies']['kredit']) ? $items['persediaan supplies']['kredit'] : 0;
                        $items['persediaan supplies']['debet'] = $anggaran;
                    }
//                    if (isset($items['999'])) {
//                        $opname_kredit = isset($items['999']['kredit']) ? $items['999']['kredit'] : 0;
//                        $opname_debet = isset($items['999']['debet']) ? $items['999']['debet'] : 0;
//                    }
//                    $riil = $anggaran - $opname_kredit + $opname_debet;
//                cekPink("$anggaran -- $opname_kredit -- $opname_debet -- $riil");
//
                    // replace array...
//                    if (isset($items['persediaan supplies'])) {
//                        $items['persediaan supplies'] = NULL;
//                        unset($items['persediaan supplies']);
//                    }
//                    $items['999']['debet'] = $riil;
//                    $items['999']['kredit'] = $anggaran;

//                    cekKuning($positionRek);
                    foreach ($items as $rekID => $rekSpec) {
                        $nama = $rekSpec['nama'];
                        $position = isset($positionRek[$nama]) ? $positionRek[$nama] : NULL;
                        $debet = $rekSpec['debet'];
                        $kredit = $rekSpec['kredit'];
//                        cekHere("$nama :: $debet :: $kredit");
                        //-------
                        if (isset($positionRek[$nama])) {
                            $selisih = $kredit - $debet;
                            if ($position == "kredit") {
                                $debet = 0;
                                $kredit = $selisih;
                            }
                            else {
                                $debet = $selisih < 0 ? $selisih * -1 : $selisih;
                                $kredit = 0;
                            }

                        }
                        //-------

                        switch ($position) {
                            case "debet":
                                if ($rekSpec['debet'] > 0) {
                                    $items[$rekID]['debet'] = $debet;
                                    $items[$rekID]['kredit'] = 0;
                                }
                                else {
                                    $items[$rekID]['debet'] = $kredit * -1;
                                    $items[$rekID]['kredit'] = 0;
                                }
                                break;
                            case "kredit":
                                if ($rekSpec['kredit'] > 0) {
                                    $items[$rekID]['kredit'] = $kredit;
                                    $items[$rekID]['debet'] = 0;
                                }
                                else {
                                    $items[$rekID]['kredit'] = $debet * -1;
                                    $items[$rekID]['debet'] = 0;
                                }
                                break;
                        }
                    }
                }
            }
        }
        //---------------------------------
//arrPrintWebs($items);
//arrPrintPink($viewEfisiensi);
//arrPrintPink($mainHeaders);
        $itemsNew_show = array();
        if (sizeof($viewEfisiensi) > 0) {
            foreach ($viewEfisiensi as $key => $alias) {
                $itemsNew_show[$key] = isset($items[$key]) ? $items[$key] : array();
            }
        }

        $btnGroups = array();
        $data = array(
            "mode" => "efisiensi",
            "title" => "$itemLabel &nbsp; $dateRange",
            "subTitle" => "",
            "date1" => $date1,
            "date2" => $date2,
            "alias" => $accountAlias,
            "mainHeaders" => $mainHeaders,

            "items" => sizeof($itemsNew_show) > 0 ? $itemsNew_show : $items,
            "footers" => $footers,
            "sumfooters" => $arrSumView,
            "footersBlacklist" => array("no", "id", "kode", "nama"),
            "mdlFields" => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),
            "filters" => array(
                "dates" => array(
                    "end" => $date2,
                ),
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );


        $this->load->view("ledger", $data);
    }


    // ------VERSI MONGO---------- ----------------
    public function viewNeraca_mongo()
    {
        $this->load->model("Mdls/MdlMongoNeraca");
        $this->load->model("Mdls/MdlMongoFinanceConfig");
        $ner = new MdlMongoNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlMongoFinanceConfig();
        $fc->addFilter(
            array(
                "periode" => $periode,
                "bln" => $bulan,
                "thn" => $tahun,
            )
        );
        $fcTmp = $fc->lookupAll();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec_tmp) {
                $fcSpec = (object)$fcSpec_tmp;
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


        $ner->addFilter(
            array(
                "periode" => $periode,
                "cabang_id" => $this->session->login['cabang_id'],
            )
        );
        $tmp = $ner->fetchBalances("$defaultDate");
//        $dates = $ner->fetchDates();
//arrPrintWebs($tmp);
//mati_disini();

        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
//                arrPrintWebs($row);

                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $oldDate = "2019-09";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca",
            "title" => "balance",
            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
            "linkExcel" => base_url() . "ExcelWriter/neraca",
            "dateSelector" => true,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeracaTahunan_mongo()
    {
        $tahun_pilihan = $previousMonth = previousYear();

        $this->load->model("Mdls/MdlMongoFinanceConfig");
        $this->load->model("Mdls/MdlMongoNeraca");


        $aa = New Layout();
        $aa->setOnClickTarget(current_url());
        $pilih_tahun = $aa->selectTahun($tahun_pilihan, "date");
        $periode = "tahunan";

        $fc = New MdlMongoFinanceConfig();
        $fc->addFilter(
            array(
                "periode" => "$periode",
                "thn" => "$tahun_pilihan",
            )
        );
        $fcTmp = $fc->lookupAll();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec_tmp) {
                $fcSpec = (object)$fcSpec_tmp;
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }
//cekKuning("$tahun_pilihan :: $previousMonth");
//arrPrintWebs($fcResult);

        $defaultDate = isset($_GET['year']) ? $_GET['year'] : $previousMonth;
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


        $ner = new MdlMongoNeraca();
        $ner->addFilter(
            array(
                "cabang_id" => $this->session->login['cabang_id'],
                "periode" => "$periode",
            )
        );
        $tmp = $ner->fetchBalances("$defaultDate");
        //cekkuning($this->db->last_query() . "<br>$defaultDate");

        $dates = $ner->fetchDates();
        //arrPrint($dates);

        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            //            arrPrint($tmp);
            foreach ($tmp as $row) {

                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
//                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
//                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => $row->debet,
                                "kredit" => $row->kredit,
                                "link" => "",
                            );
                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn";
            }
            //            reset($dates);
            //            $oldDate = array_values($dates);
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $oldDate = "2019-01";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca",
            "title" => "balance",
            "subTitle" => "balance per-" . $defaultDate,
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
//                "rek_id" => "code",
//                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",

            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
//            "linkExcel" => base_url() . "ExcelWriter/neraca",
//            "dateSelector" => true,

            "pilih_tahun" => $pilih_tahun,
            "yearSelector" => true,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeraca_consolidated_mongo()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlMongoNeraca");
        $this->load->model("Mdls/MdlMongoFinanceConfig");
        $periode = "bulanan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlMongoFinanceConfig();
        $fc->addFilter(
            array(
                "periode" => $periode,
                "bln" => $bulan,
                "thn" => $tahun,
            )
        );
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();


        $ner = new MdlMongoNeraca();
        $tmp = $ner->fetchBalances2($defaultDate);
        //cekHere($this->db->last_query());
//arrPrintWebs($tmp);
//        $dates = $ner->fetchDates();
//        $oldDate = date("Y-m");


        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        $defPos = detectRekDefaultPosition($row->rekening);

                        if (strlen($row->kategori) > 1) {
                            if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!in_array($row->kategori, $categories)) {
                                    $categories[] = $row->kategori;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                    $rekenings[$row->cabang_id][$row->kategori] = array();
                                }


                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                }

                                if (in_array($row->rekening, $accountException)) {
                                    $debet = $row->kredit * -1;
                                    $kredit = $row->debet * -1;
                                }
                                else {
                                    switch ($defPos) {
                                        case "debet":
                                            if ($row->kredit > 0) {
                                                $debet = $row->kredit * -1;
                                                $kredit = 0;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        case "kredit":
                                            if ($row->debet > 0) {
                                                $debet = 0;
                                                $kredit = $row->debet * -1;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        default:
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                            break;
                                    }
                                    //                                    $debet = $row->debet;
                                    //                                    $kredit = $row->kredit;
                                }


                                if (sizeof($accountCatException) > 0) {
                                    foreach ($accountCatException as $cat => $c_rekName) {
                                        if (in_array($row->rekening, $c_rekName)) {
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                            }

                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$cat][$row->id] = $row->rekening;
                                        }
                                        else {
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$row->kategori][$row->id] = $row->rekening;
                                        }
                                    }
                                }
                                else {
                                    $rekenings[$row->kategori][] = $rekenings;
                                }


                                $whID = getDefaultWarehouseID($row->cabang_id);
                                $childLink = "";
                                if (isset($accountChilds[$row->rekening])) {
                                    $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                }
                                $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            }
                        }
                    }

                }

            }
//            reset($dates);
//            $oldDate = key($dates);
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $oldDate = "2019-09";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca_consolidated",
            "title" => "Neraca Konsolidasi $periode ",
            "subTitle" => "Neraca Konsolidasi per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeraca_consolidatedTahunan_mongo()
    {


        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();
        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];

        $this->load->model("Mdls/MdlMongoNeraca");
        $ner = new MdlMongoNeraca();
        $ner->addFilter(
            array(
                "periode" => "tahunan",
            )
        );
        $tmp = $ner->fetchBalances2($defaultDate);
        //cekKuning($this->db->last_query());

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        $i++;
                        $defPos = detectRekDefaultPosition($row->rekening);

                        if (strlen($row->kategori) > 1) {
                            if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!in_array($row->kategori, $categories)) {
                                    $categories[] = $row->kategori;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                    $rekenings[$row->cabang_id][$row->kategori] = array();
                                }


                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                }

                                if (in_array($row->rekening, $accountException)) {
                                    $debet = $row->kredit * -1;
                                    $kredit = $row->debet * -1;
                                }
                                else {
                                    switch ($defPos) {
                                        case "debet":
                                            if ($row->kredit > 0) {
                                                $debet = $row->kredit * -1;
                                                $kredit = 0;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        case "kredit":
                                            if ($row->debet > 0) {
                                                $debet = 0;
                                                $kredit = $row->debet * -1;
                                            }
                                            else {
                                                $debet = $row->debet;
                                                $kredit = $row->kredit;
                                            }
                                            break;
                                        default:
                                            $debet = $row->debet;
                                            $kredit = $row->kredit;
                                            break;
                                    }
                                    //                                    $debet = $row->debet;
                                    //                                    $kredit = $row->kredit;
                                }


                                if (sizeof($accountCatException) > 0) {
                                    foreach ($accountCatException as $cat => $c_rekName) {
                                        if (in_array($row->rekening, $c_rekName)) {
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                            }
                                            if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                            }

                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$cat][$row->id] = $row->rekening;
                                        }
                                        else {
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;

                                            $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            //                                            $rekeningsName[$row->kategori][$row->id] = $row->rekening;
                                        }
                                    }
                                }
                                else {
                                    $rekenings[$row->kategori][] = $rekenings;
                                }


                                $whID = getDefaultWarehouseID($row->cabang_id);
                                $childLink = "";
                                if (isset($accountChilds[$row->rekening])) {
                                    $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                }
                                $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                            }
                        }
                    }

                }

            }
            reset($dates);
            $oldDate = key($dates);
        }

        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewNeraca_consolidated",
            "title" => "Neraca Konsolidasi Tahunan ",
            //            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "subTitle" => "Neraca Konsolidasi  per- $defaultDate",
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
        );
        $this->load->view("finance", $data);

    }

    //-----------------------------------------------
    public function viewNeracaConsolidated_cost()
    {
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


//        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
        $ner->addFilter("tipe='konsolidasi_cost'");
//        $ner->addFilter("tipe='konsolidasi_riil'");
        $tmp = $ner->fetchBalances($defaultDate);
        showLast_query("biru");
        $dates = $ner->fetchDates();


        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

        //arrPrint($rekenings);
        //arrPrint($rekeningsNew);
        //arrPrint($accountRekeningSort);
        //arrPrint($rekeningsName);
        //arrPrint($rekeningsNameNew);

        $oldDate = "2019-09";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca",
            "title" => "balance Consolidated",
            "subTitle" => "balance Consolidated per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
//                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
            "linkExcel" => base_url() . "ExcelWriter/neraca",
            "dateSelector" => true,
        );
        $this->load->view("finance", $data);

    }

    public function viewNeracaConsolidated_riil()
    {
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


//        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
//        $ner->addFilter("tipe='konsolidasi_cost'");
        $ner->addFilter("tipe='konsolidasi_riil'");
        $tmp = $ner->fetchBalances($defaultDate);
        showLast_query("biru");
        $dates = $ner->fetchDates();


        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

        //arrPrint($rekenings);
        //arrPrint($rekeningsNew);
        //arrPrint($accountRekeningSort);
        //arrPrint($rekeningsName);
        //arrPrint($rekeningsNameNew);

        $oldDate = "2019-09";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeraca",
            "title" => "balance Consolidated",
            "subTitle" => "balance Consolidated per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
//                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
            "linkExcel" => base_url() . "ExcelWriter/neraca",
            "dateSelector" => true,
        );
        $this->load->view("finance", $data);
    }

    //-----------------------------------------------
    public function viewNeracaKoreksi()
    {
        $this->load->model("Mdls/MdlRekeningKoreksi");
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


        //region neraca aktual
        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);
        $dates = $ner->fetchDates();
        //endregion
        //region neraca sebelum koreksi
        $ner = new MdlNeraca();
        $ner->setFilters(array());
        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
        $ner->addFilter("status='1'");
        $ner->addFilter("trash='1'");
        $tmpBeforeKoreksi = $ner->fetchBalances($defaultDate);
        //endregion
        //region jurnal koreksi
        $jnl = New MdlRekeningKoreksi();
        $jnl->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $jnl->addFilter("koreksi_periode='$periode'");
        $jnl->addFilter("year(koreksi_dtime)='$tahun'");
        $jnl->addFilter("month(koreksi_dtime)='$bulan'");
        $jnlTmp = $jnl->lookupAll()->result();
//        showLast_query("biru");
//        arrPrintPink($jnlTmp);
        //endregion

        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsBeforeKoreksi = array();
        $rekeningsKoreksi = array();
        $rekeningsName = array();
        $total_koreksi = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }
        if (sizeof($tmpBeforeKoreksi) > 0) {
            foreach ($tmpBeforeKoreksi as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
//
//                        if (!in_array($row->kategori, $categories)) {
//                            $categories[] = $row->kategori;
//                        }
//                        if (!isset($rekenings[$row->kategori])) {
//                            $rekenings[$row->kategori] = array();
//                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet_before" => ($row->kredit * -1),
                                "kredit_before" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet_before" => $debet,
                                "kredit_before" => $kredit,
                                "link" => "",
                            );
                        }
//                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
//                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekeningsBeforeKoreksi[$cat][] = $tmpCol;
//                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekeningsBeforeKoreksi[$row->kategori][] = $tmpCol;
//                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekeningsBeforeKoreksi[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }
        if (sizeof($jnlTmp) > 0) {
            foreach ($jnlTmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
//
//                        if (!in_array($row->kategori, $categories)) {
//                            $categories[] = $row->kategori;
//                        }
//                        if (!isset($rekenings[$row->kategori])) {
//                            $rekenings[$row->kategori] = array();
//                        }

                        $debet_koreksi = "debet_koreksi_" . $row->koreksi_number;
                        $kredit_koreksi = "kredit_koreksi_" . $row->koreksi_number;
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "$debet_koreksi" => ($row->kredit * -1),
                                "$kredit_koreksi" => ($row->debet * -1),
                                "link" => "",
                            );
                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "$debet_koreksi" => $debet,
                                "$kredit_koreksi" => $kredit,
                                "link" => "",
                            );
                        }
//                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
//                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekeningsKoreksi[$cat][] = $tmpCol;
//                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekeningsKoreksi[$row->kategori][] = $tmpCol;
//                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekeningsKoreksi[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";

                if (!isset($total_koreksi[$row->koreksi_number])) {
                    $total_koreksi[$row->koreksi_number] = 0;
                }
                $total_koreksi[$row->koreksi_number] += 1;
            }
        }

        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }
        foreach ($rekeningsBeforeKoreksi as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekeningsBeforeKoreksi[$cat]);
            }
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
//                        else {
//                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
//                        }
//
                    }
                }
            }
        }
        foreach ($rekeningsKoreksi as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekeningsKoreksi[$cat]);
            }
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
//                        else {
//                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
//                        }
//
                    }
                }
            }
        }
//arrPrintPink($rekeningsKoreksi);

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }


        $mainHeaders = array(
            "b_koreksi" => "sebelum koreksi",
            "koreksi" => "koreksi",
            "af_koreksi" => "setelah koreksi",
            "link" => "",
        );

        $headers = array(
            "debet_before" => "debet",
            "kredit_before" => "kredit",
        );
        if (sizeof($total_koreksi) > 0) {
            foreach ($total_koreksi as $num => $xxxx) {
                $headers["debet_koreksi_" . $num] = "debet";
                $headers["kredit_koreksi_" . $num] = "kredit";
            }
        }
        $headers["debet"] = "debet";
        $headers["kredit"] = "kredit";
        $headers["link"] = "";
//arrPrintWebs($headers);
        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        $oldDate = "2019-09";
        $data = array(
//            "mode" => $this->uri->segment(2),
            "mode" => "viewNeracaKoreksi",
            "title" => "balance (internal)",
            "subTitle" => "balance (internal) per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
//            "headers" => array(
//                "debet_before" => "debet",
//                "kredit_before" => "kredit",
//
//                "debet_koreksi_1" => "debet",
//                "kredit_koreksi_1" => "kredit",
//                "debet_koreksi_2" => "debet",
//                "kredit_koreksi_2" => "kredit",
//
//                "debet" => "debet",
//                "kredit" => "kredit",
//
//                "link" => "",
//            ),
            "mainHeaders" => $mainHeaders,
            "headers" => $headers,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
//            "linkExcel" => base_url() . "ExcelWriter/neraca",
            "dateSelector" => true,
            "rekeningKeterangan" => $rekeningKeterangan,
            "totalKoreksi" => sizeof($total_koreksi) > 0 ? sizeof($total_koreksi) : 1,
            "buttonMode" => array(
                "enabled" => true,
                "label" => "neraca (final)",
                "link" => base_url() . get_class($this) . "/viewNeraca",
            ),
        );
        $this->load->view("finance", $data);

    }


    //-----------------------------------------------
    public function viewCashflow()
    {
        /* pembatalan yang melibatkan kas tetap dimunculkan
//        karena tidak bisa dinettokan, karena sifat pembatalan umum
        // salah prosedural sop pemakaian
         * */


        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Mdls/MdlCashFlow");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");
        $mode = isset($_GET['mode']) ? $_GET['mode'] : "";
        $date1 = isset($_GET['date']) ? $_GET['date'] . "-01" : date("Y-m") . "-01";
        $date2 = isset($_GET['date']) ? $_GET['date'] . "-31" : date("Y-m") . "-31";

        $defaultDate = $dateNow = isset($_GET['date']) ? $_GET['date'] : date("Y-m");

        // membaca tabel setting cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
//        arrPrintPink($cfTmp);
        $topHeader = array();
        $topHeaderSummary = array();
        $midHeader = array();
        $midHeaderHeadCode = array();
        $midHeaderHeadCodeFlip = array();
        $isiData = array();
        foreach ($cfTmp as $cfTmpSpec) {
            if ($cfTmpSpec->head_level == 1) {
                $topHeader[$cfTmpSpec->head_code] = $cfTmpSpec->head_name;
                $topHeaderSummary[$cfTmpSpec->head_code] = "";
            }
            if ($cfTmpSpec->head_level == 2) {
                $midHeader[$cfTmpSpec->p_head_code][$cfTmpSpec->head_code] = $cfTmpSpec->head_name;
                $midHeaderHeadCode[$cfTmpSpec->head_code] = $cfTmpSpec->p_head_code;
                $midHeaderHeadCodeFlip[$cfTmpSpec->p_head_code] = $cfTmpSpec->head_code;
            }
            if ($cfTmpSpec->head_level == 3) {
                $isiData[$cfTmpSpec->rekening] = $cfTmpSpec->p_head_code;
            }

        }
        $topHeader[71] = "kenaikan (penurunan) bersih kas dan setara kas";
        $topHeader[72] = "kas dan setara kas awal periode";
        $topHeader[73] = "kas dan setara kas akhir periode";

        //---------------------
//        $rekening = "010101";
        $rekening = "kas";
        //---------------------
        $cb = New MdlCabang();
        $cbTmp = $cb->lookupAll()->result();
        $saldoAwal = 0;
        foreach ($cbTmp as $cbSpec) {
            $cbID = $cbSpec->id;
            $cr = New ComRekening();
            $cr->addFilter("cabang_id=$cbID");
            $cr->addFilter("fulldate>=$date1");
            $cr->addFilter("fulldate<=$date2");
            $cr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
            $crCbTmp = $cr->fetchMoves($rekening);
//            showLast_query("biru");
//            cekHere($crCbTmp[0]->debet_awal . " :: " . $crCbTmp[0]->cabang_id);
            $debet_awal = isset($crCbTmp[0]->debet_awal) ? $crCbTmp[0]->debet_awal : 0;
            $saldoAwal += $debet_awal;

        }
        //---------------------


        $cr = New ComRekening();

        $cr->addFilter("fulldate>=$date1");
        $cr->addFilter("fulldate<=$date2");
        $crTmp = $cr->fetchMoves($rekening);
//        showLast_query("biru");
//        $saldoAwal = 0;
        $totalDebet = 0;
        $totalKredit = 0;
        $data_rekening = array();
        $data_rekening_jenisTr = array();
        $noGroup = array();
        $trInGroup = array();
        $detailTransaksiMutasi = array();
        foreach ($crTmp as $crTmpSpec) {
            $jenis = $crTmpSpec->jenis;
            $trID = $crTmpSpec->transaksi_id;
            //----------------
            $subFolder = isset($isiData[$jenis]) ? $isiData[$jenis] : 0;
            $totalDebet += $crTmpSpec->debet;
            $totalKredit += $crTmpSpec->kredit;
            //----------------
            if (!isset($data_rekening[$subFolder]["debet"])) {
                $data_rekening[$subFolder]["debet"] = 0;
            }
            if (!isset($data_rekening[$subFolder]["kredit"])) {
                $data_rekening[$subFolder]["kredit"] = 0;
            }
            $data_rekening[$subFolder]["debet"] += $crTmpSpec->debet;
            $data_rekening[$subFolder]["kredit"] += ($crTmpSpec->kredit);
            //----------------
            if (!isset($data_rekening_jenisTr[$jenis]["debet"])) {
                $data_rekening_jenisTr[$jenis]["debet"] = 0;
            }
            if (!isset($data_rekening_jenisTr[$jenis]["kredit"])) {
                $data_rekening_jenisTr[$jenis]["kredit"] = 0;
            }
            $data_rekening_jenisTr[$jenis]["debet"] += $crTmpSpec->debet;
            $data_rekening_jenisTr[$jenis]["kredit"] += ($crTmpSpec->kredit);
            //----------------
            // pembatalan 9911, 9912, mendeteksi transaksi yang dibatalkan
            switch ($jenis) {
                case "9911":
                case "9912":
//                    cekHitam(":: $trID ::");
                    $tr = New MdlTransaksi();
                    $tr->setJointSelectFields("main");
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=$trID");
                    $regTmp = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($regTmp[0]->main);
                    $jenisTr_reference = $main["jenisTr_reference"];
                    $rek_p_head_code = $isiData[$jenisTr_reference];
                    $master_head_code = $midHeaderHeadCode[$rek_p_head_code];
                    $last_p_head_code = $midHeaderHeadCodeFlip[$master_head_code];
                    $next_p_head_code = $last_p_head_code + 1;
                    $midHeader[$master_head_code][$next_p_head_code] = "Pembatalan";
//cekHere("$rek_p_head_code :: $master_head_code :: $next_p_head_code");
                    if (!isset($data_rekening[$next_p_head_code]["debet"])) {
                        $data_rekening[$next_p_head_code]["debet"] = 0;
                    }
                    if (!isset($data_rekening[$next_p_head_code]["kredit"])) {
                        $data_rekening[$next_p_head_code]["kredit"] = 0;
                    }
                    $data_rekening[$next_p_head_code]["debet"] += $crTmpSpec->debet;
                    $data_rekening[$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);
                    break;
            }
            //----------------
            if ($subFolder == 0) {
                $noGroup[$jenis] = $jenis;
            }
            else {
                $trInGroup[$trID] = $trID;
            }
            //----------------
            $arrTrID_sub[$subFolder][$trID] = $trID;
            foreach ($crTmpSpec as $key => $val) {
                $new_key = "mt_" . $key;
                $detailTransaksiMutasi[$trID][$new_key] = $val;
            }
            //----------------
        }


        $kenaikanKas = $totalDebet - $totalKredit;
        $topHeaderIsi[71] = $kenaikanKas;
        $topHeaderIsi[72] = $saldoAwal;
        $topHeaderIsi[73] = $saldoAwal + $totalDebet - $totalKredit;

        // region ke transaksi
        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $trInGroup) . "')");
        $trTmp = $tr->lookupAll()->result();
        $dataTransaksiByID = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                foreach ($trSpec as $key => $val) {
                    $new_key = "tr_" . $key;
                    $dataTransaksiByID[$trSpec->id][$new_key] = $val;
                }
            }
        }
        // endregion
        // region ke registry main
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, main");
        $tr->addFilter("transaksi_id in ('" . implode("','", $trInGroup) . "')");
        $regTmp = $tr->lookupDataRegistries()->result();
        $dataMainRegByID = array();
        if (sizeof($regTmp) > 0) {
            foreach ($regTmp as $regSpec) {
                $regTrID = $regSpec->transaksi_id;
                $main = blobDecode($regSpec->main);
                foreach ($main as $key => $val) {
                    $new_key = "m_" . $key;
                    $dataMainRegByID[$regTrID][$new_key] = $val;
                }
            }
        }
        // endregion

        // region menggabungkan detail mutasi, transaksi, registry main
        $detailData = array();
        foreach ($arrTrID_sub as $subGroup => $subSpec) {
            foreach ($subSpec as $tr_id) {
                $detailMutasi = isset($detailTransaksiMutasi[$tr_id]) ? $detailTransaksiMutasi[$tr_id] : array();
                $detailMainReg = isset($dataMainRegByID[$tr_id]) ? $dataMainRegByID[$tr_id] : array();
                $detailTransaksi = isset($dataTransaksiByID[$tr_id]) ? $dataTransaksiByID[$tr_id] : array();

                $detailData[$subGroup][$tr_id] = $detailTransaksi + $detailMutasi + $detailMainReg;
            }
        }
        // endregion menggabungkan detail mutasi, transaksi, registry main

        $data_rekening_new = array();
        foreach ($data_rekening as $ii => $spec) {
//            if($spec["debet"] > 0){
//                $data_rekening_new[$ii]["values"] = $spec["debet"];
//            }
//            else{
//                $data_rekening_new[$ii]["values"] = $spec["kredit"] * -1;
//            }
            $netto = $spec["debet"] - $spec["kredit"];
            $data_rekening_new[$ii]["values"] = $netto;
        }
        //------------------------------
        $topHeaderSummary["01"] = "Kas Bersih Diperoleh dari (digunakan untuk) dari Aktivitas Operasi";
        $topHeaderSummary["02"] = "Kas bersih digunakan untuk aktivitas investasi";
        $topHeaderSummary["03"] = "Kas bersih yang diperoleh (digunakan untuk) aktivitas pendanaan";
        //------------------------------
//        arrPrintPink($topHeaderSummary);

//        arrPrintHijau($arrTrID_sub);
//        arrPrintHijau($detailData);
//        arrPrintHijau($dataMainRegByID);
//        arrPrintHijau($dataTransaksiByID);
//        arrPrintHijau($detailTransaksi);
//        arrPrintHijau($data_rekening);
//        arrPrintHijau($data_rekening_new);
//        arrPrintWebs($data_rekening_jenisTr);
//        arrPrintPink($noGroup);
//        arrPrintPink($midHeader);
//        arrPrintPink($midHeaderHeadCode);
//cekHitam($saldoAwal);


        $oldDate = "2019-08";
        $data = array(
            "mode" => "cashflow",
            "title" => "laporan cashflow konsolidasi",
            "subTitle" => "laporan cashflow konsolidasi " . lgTranslateTime($defaultDate),
            "categories" => $topHeader,
            "rekenings" => $midHeader,
            "headers" => array(
                "values" => "-",
//                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => isset($categoryRLBottom) ? $categoryRLBottom : array(),
            "rekeningsName" => isset($rekeningsNameNew) ? $rekeningsNameNew : array(),
            "rekeningsNameAlias" => isset($accountAlias) ? $accountAlias : array(),
//            "linkExcel" => "",
            "dateSelector" => true,
            "rekeningBlacklist" => isset($rekException) ? $rekException : array(),
            "dataRekening" => isset($data_rekening_new) ? $data_rekening_new : array(),
            "topHeaderIsi" => $topHeaderIsi,
            "topHeaderSummary" => $topHeaderSummary,
            "saldoAwal" => $saldoAwal,
            "selisihKas" => $kenaikanKas,


//            "buttonMode" => array(
//                "enabled" => true,
//                "label" => "laporan rugilaba (internal)",
//                "link" => base_url() . get_class($this) . "/viewPLKoreksi",
//            ),
        );

        $data = array(
            "mode" => "cashflow",
            "title" => "undermaintenace",
            "underMaintenance" => underMaintenance(),

        );
        $this->load->view("finance", $data);

    }

    public function detailCashflow()
    {
//        arrPrintPink($_REQUEST);
//        arrPrintPink(url_segment());
        $getDate = $_REQUEST["date"];
        $subGroup = url_segment()[3];
        $this->load->model("Mdls/MdlCashFlowBuilder");
        $cf = New MdlCashFlowBuilder();
        $cfTmp = $cf->getCashflow($getDate);
//        arrPrintPink($cfTmp['detailData'][$subGroup]);
        $detail = isset($cfTmp['detailData'][$subGroup]) ? $cfTmp['detailData'][$subGroup] : array();
//cekHere(sizeof($detail));
//arrPrintPink($detail);
        $detailHeaders = array(
            "dtime" => "date",
            "jenis_label" => "note",
            "suppliers_nama" => "vendor",
            "customers_nama" => "customer",
            "oleh_nama" => "by/pic",
            "m_cabangName" => "branch",
            "nomer_top" => "reference number",
            "nomer" => "number",
            "_company_rekening_stepCode" => "urut",
            "m_cash_account__label" => "cash account",

            "mt_netto" => "nilai",

        );
        $detailHeaderBlacklist = array(
            "urut",
        );

        $data = array(
            "mode" => "detailCashflow",
            "headers" => $detailHeaders,
            "items" => $detail,
            "detailHeaderBlacklist" => $detailHeaderBlacklist,
        );
        $this->load->view("finance", $data);
    }

    //-----------------------------------------------
    public function laporanKeuanganMonthly()
    {

        $periode = "bulanan";
        $defaultDate = isset($_GET['date']) ? ($_GET['date'] == date("Y-m") ? previousMonth() : $_GET['date']) : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];


        $oldDate = "2019-09";
        $getDate = "&date=$tahun-$bulan";

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan Konsolidasi $periode ",
            "subTitle" => "Laporan Keuangan Konsolidasi per-" . lgTranslateTime($defaultDate),
//            "categories" => $arrCatView,
//            "rekenings" => $rekenings,
//            "headers" => array(
//                //                "rekening" => "rekening",
//                "debet" => "debet",
//                "kredit" => "kredit",
//                "link" => "",
//            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,
            "neraca" => base_url() . "Neraca/viewNeraca_consolidated?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "Rugilaba/viewPL_consolidated?mode=lapkeuangan" . $getDate,
            "cashflow" => base_url() . "Neraca/viewCashflow?mode=lapkeuangan" . $getDate,
            "periode" => "bulanan",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganYearly()
    {

        $periode = "tahunan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0] == date("Y") ? $defaultDate_ex[0] - 1 : $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];


        $oldDate = "2019-09";
        $getDate = "&date=$tahun";

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan Konsolidasi $periode ",
            "subTitle" => "Laporan Keuangan Konsolidasi per-" . ($tahun),
//            "subTitle" => "Laporan Keuangan Konsolidasi per-" . lgTranslateTime2($defaultDate),
//            "categories" => $arrCatView,
//            "rekenings" => $rekenings,
//            "headers" => array(
//                //                "rekening" => "rekening",
//                "debet" => "debet",
//                "kredit" => "kredit",
//                "link" => "",
//            ),
//            "defaultDate" => $defaultDate,
            "defaultDate" => $tahun,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,
            "neraca" => base_url() . "Neraca/viewNeraca_consolidatedTahunan_lap?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "Rugilaba/viewPL_consolidatedTahunan?mode=lapkeuangan" . $getDate,
            "cashflow" => base_url() . "Neraca/viewCashflowTahunan?mode=lapkeuangan" . $getDate,
            "periode" => "tahunan",
        );
        $this->load->view("finance", $data);

    }
    //-----------------------------------------------
}