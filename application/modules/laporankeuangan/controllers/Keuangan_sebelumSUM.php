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

    //NERACA-----------------------------------------------
    public function viewNeraca()
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

        //arrPrint($rekenings);
        //arrPrint($rekeningsNew);
        //arrPrint($accountRekeningSort);
        //arrPrint($rekeningsName);
        //arrPrint($rekeningsNameNew);

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
            //            "accountConsolidation" => $accountConsolidation,
//            "linkExcel" => base_url() . "ExcelWriter/neraca",
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
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "tahunan";

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
//        $bulan = $defaultDate_ex[1];
        $prevYear = $tahun - 1;
        $arrTahun = array(
            "last_year" => $prevYear,
            "this_year" => $tahun,
        );
        foreach ($arrTahun as $thn_ex) {
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='$periode'");
//        $fc->addFilter("bln='$bulan'");
            $fc->addFilter("thn='$thn_ex'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {

                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        foreach ($arrTahun as $tahun_ex) {

            $ner = new MdlNeraca();
            $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $ner->addFilter("periode='$periode'");
            $tmp[$tahun_ex] = $ner->fetchBalances($tahun_ex);
//            showLast_query("biru");
        }
        $dates = $ner->fetchDates();


        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
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
                                    "rekening" => $row->rekening,
                                    "debet_" . $thn_ex => ($row->kredit * -1),
                                    "kredit_" . $thn_ex => ($row->debet * -1),
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
                                    "debet_" . $thn_ex => $debet,
                                    "kredit_" . $thn_ex => $kredit,
                                    "link" => "",
                                );
                            }
                            if (isset($accountChilds[$row->rekening])) {
                                $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                            }

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
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {

                    if (isset($rekeningsName[$cat])) {
                        if (in_array($rekName, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rekName] = $rekName;
                        }
                    }
                }
            }
        }

        //arrPrint($rekenings);
        //arrPrint($rekeningsNew);
        //arrPrint($accountRekeningSort);
        //arrPrint($rekeningsName);
        //arrPrint($rekeningsNameNew);

        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet ($tahun)",
                "debet_" . $prevYear => "debet ($prevYear)",
                "kredit_" . $tahun => "kredit ($tahun)",
                "kredit_" . $prevYear => "kredit ($prevYear)",
                "link" => "",
            );
        }
        else {
//            $views_mode = $this->uri->segment(3);
            $views_mode = "viewNeraca";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }
        $data = array(
            "mode" => $views_mode,
            "title" => "balance (final)",
            "subTitle" => "balance (final) per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            //            "accountConsolidation" => $accountConsolidation,
//            "linkExcel" => base_url() . "ExcelWriter/neraca",
            "dateSelector" => true,
            "rekeningKeterangan" => $rekeningKeterangan,
            "buttonMode" => array(
                "enabled" => true,
                "label" => "neraca (internal)",
                "link" => base_url() . get_class($this) . "/viewNeracaKoreksi",
            ),
            "arrTahun" => $arrTahun,
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

        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_ytd";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }
        else {
            $views_mode = "viewNeraca";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }

        $data = array(
            "mode" => "$views_mode",
            "title" => "balance Year to Date",
            "subTitle" => "balance Year to Date " . lgTranslateTime(date("Y")),
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "headers" => $headerss,
            "defaultDate" => isset($defaultDate) ? $defaultDate : "",
            "oldDate" => isset($oldDate) ? $oldDate : "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => false,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("$views", $data);


    }

    public function viewNeracaYearlyAdj(){
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlNeracaAdj");
        $this->load->model("Mdls/MdlNeracaAdjTmp");
        $this->load->model("Mdls/MdlCabang");

        $tahun = "2021";
        /*
         * 3 block arraya yang diperlukan untuk UI
         */
        $prevBalance = array();
        $adjBalance = array();
        $curentBalance = array();

        $pn = new MdlNeraca();//tabel asli neraca
        $an = new MdlNeracaAdjTmp();//tmp adjustment
        $cn = new MdlNeracaAdj();//hasil neraca


        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }

        //panggil punya pusat buat trial
        $pn->addFilter("periode='tahunan'");
        $tempNeraca  = $pn->fetchBalances2($tahun);

        $an->addFilter("periode='tahunan'");
        $tempAdjustment = $an->fetchBalance3();


        $cn->addFilter("periode='tahunan'");
        $cn->addFilter("rebuild='0'");
        $tempCurentNeraca = $cn->fetchBalance3($tahun);

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();
        $rekeningCoa = rekening_coa_he_accounting();
        $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
        $accountRekeningSort = rekening_coa_sort_he_accounting();


        $tmp = $tempNeraca;
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        $headerNeracaCurent = array();
        $masterHeader = array();
        $i=0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    $masterHeader["prev"] =array(
                        "label"=>"Neraca saldo<br>( ".$rowSpec[0]->thn." )",
                        "rekening"=>array(
                            "debet","kredit",
                        ),
                    );
                    foreach ($rowSpec as $row) {
                        $i++;

                        $defPos = detectRekDefaultPosition($row->rekening);
// matiHere($defPos." || ".$row->rekening);
                        if (strlen($row->kategori) > 1) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
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


                            //region data per-cabang
                            if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                $rekenings[$row->cabang_id][$row->kategori] = array();
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            //endregion
                            //region data konsolidasian
                            if (!isset($rekeningsKonsolidasiNilai["prev"][$row->kategori])) {
                                $rekeningsKonsolidasiNilai["prev"][$row->kategori] = array();
                            }
                            if (!isset($rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['debet'])) {
                                $rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['kredit'])) {
                                $rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            //endregion


                            if (sizeof($accountCatException) > 0) {
                                foreach ($accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        //region data per-cabang
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                        // }
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['debet'])) {
                                            $rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['debet'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['kredit'])) {
                                            $rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['kredit'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['link'])) {
                                            $rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["prev"][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    }
                                    else {
                                        //region data per-cabang
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        $rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    }
                                }
                            }
                            else {
                                $rekenings[$row->kategori][] = $rekenings;
                                $rekeningsKonsolidasiNilai["prev"][$row->kategori][] = $rekeningsKonsolidasiNilai;
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
                            $rekeningsKonsolidasiNilai["prev"][$row->kategori][$row->rekening]['link'] = "";
                        }
                    }

                }

            }

        }
        // arrPrint($rekeningsKonsolidasiNilai);
//         arrPrint($masterHeader);
// matiHEre();
        //data adjustment

        $headerAdjustment = array();
        if(sizeof($tempAdjustment)>0){
            foreach($tempAdjustment as $CID =>$adjustmentData){
                foreach ($adjustmentData as $rowSpec) {
                    $masterHeader["adj"] =array(
                        "label"=>"jurnal koreksi per <br>".formatTanggal($rowSpec[0]->dtime,"d-m-Y"),
                        "rekening"=>array(
                            "debet","kredit",
                        ),
                    );
                    foreach ($rowSpec as $adjCount =>$row) {

                        $i++;

                        $defPos = detectRekDefaultPosition($row->rekening);
                        // matiHere($defPos." || ".$row->rekening);
                        if (strlen($row->kategori) > 1) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
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


                            //region data per-cabang
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                            //     $rekenings[$row->cabang_id][$row->kategori] = array();
                            // }
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                            //     $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                            // }
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                            //     $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                            // }
                            //endregion
                            //region data konsolidasian
                            if (!isset($rekeningsKonsolidasiNilai["adj"][$row->kategori])) {
                                $rekeningsKonsolidasiNilai["adj"][$row->kategori] = array();
                            }
                            if (!isset($rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['debet'])) {
                                $rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['kredit'])) {
                                $rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            //endregion


                            if (sizeof($accountCatException) > 0) {
                                foreach ($accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        //region data per-cabang
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                        // }
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['debet'])) {
                                            $rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['debet'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['kredit'])) {
                                            $rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['kredit'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['link'])) {
                                            $rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["adj"][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        // $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    }
                                    else {
                                        //region data per-cabang
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        $rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        // $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    }
                                }
                            }
                            else {
                                // $rekenings[$row->kategori][] = $rekenings;
                                $rekeningsKonsolidasiNilai["adj"][$row->kategori][] = $rekeningsKonsolidasiNilai;
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
                            $rekeningsKonsolidasiNilai["adj"][$row->kategori][$row->rekening]['link'] = "";
                        }
                    }
                }
            }
        }
        $headerNeracaAhkir = array();
        $hitungCID = 0;
        if(sizeof($tempCurentNeraca)>0){
            foreach ($tempNeraca as $CIDAll =>$tempNeraca_0){

            }
            foreach($tempCurentNeraca as $CID =>$tempCurentNeraca_0){
                $hitungCID++;

                foreach ($tempCurentNeraca_0 as $rowSpec) {
                    $masterHeader["curent"] =array(
                        "label"=>"Neraca koreksi(".$rowSpec[0]->thn.")  <br>per".formatTanggal($rowSpec[0]->dtime,"d-m-Y"),
                        "rekening"=>array(
                            "debet","kredit",
                        ),
                    );
                    foreach ($rowSpec as $row) {
                        $i++;

                        $defPos = detectRekDefaultPosition($row->rekening);
                        // matiHere($defPos." || ".$row->rekening);
                        if (strlen($row->kategori) > 1) {
                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                            if (!in_array($row->kategori, $categories)) {
                                $categories[] = $row->kategori;
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


                            //region data per-cabang
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                            //     $rekenings[$row->cabang_id][$row->kategori] = array();
                            // }
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                            //     $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                            // }
                            // if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                            //     $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                            // }
                            //endregion
                            //region data konsolidasian
                            if (!isset($rekeningsKonsolidasiNilai["curent"][$row->kategori])) {
                                $rekeningsKonsolidasiNilai["curent"][$row->kategori] = array();
                            }
                            if (!isset($rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['debet'])) {
                                $rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['debet'] = 0;
                            }
                            if (!isset($rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['kredit'])) {
                                $rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['kredit'] = 0;
                            }
                            //endregion


                            if (sizeof($accountCatException) > 0) {
                                foreach ($accountCatException as $cat => $c_rekName) {
                                    if (in_array($row->rekening, $c_rekName)) {
                                        //region data per-cabang
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] = 0;
                                        // }
                                        // if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                        //     $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                        // }
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['debet'])) {
                                            $rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['debet'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['kredit'])) {
                                            $rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['kredit'] = 0;
                                        }
                                        if (!isset($rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['link'])) {
                                            $rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['link'] = "";
                                        }
                                        $rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["curent"][$cat][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        // $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                    }
                                    else {
                                        //region data per-cabang
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                        // $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion
                                        //region data konsolidasian
                                        $rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['debet'] += $debet;
                                        $rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                        //endregion

                                        // $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                    }
                                }
                            }
                            else {
                                // $rekenings[$row->kategori][] = $rekenings;
                                $rekeningsKonsolidasiNilai["curent"][$row->kategori][] = $rekeningsKonsolidasiNilai;
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
                            $rekeningsKonsolidasiNilai["curent"][$row->kategori][$row->rekening]['link'] = "";
                        }
                    }

                }
            }
        }

matiHEre($hitungCID);
        //region gabungan adjustment dan hasilnya jadi satu block array
        /*
         * tujuan supaya satu kali foreach master header ngumpul adjustment daengan hasilnya
         */

        // if(sizeof($rekeningsKonsolidasiNilai)>0){
        //     arrPrint($rekeningsKonsolidasiNilai);
        // }
        // arrPrint($masterHeader);
        // matiHere();

        //endregion

// arrPrint($headerAdjustment);
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

        // arrPrint($rekeningsKonsolidasiNilai);
        // arrPrint($tempNeraca);
        // cekBiru($this->db->last_query());
        // matiHere(__LINE__);
        $views_mode = "viewNeracaAdj";
        $views = "finance";

        // $masterHeader = $headerNeracaCurent+$headerAdjustment+$headerNeracaAhkir;
        $headerss = array(
            "debet" => "debet",
            "kredit" => "kredit",
            "link" => "",
        );
// arrprintWEbs($masterHeader);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan keuangan Koreksi",
            // "subTitle" => "Laporan keuangan Koreksi " . lgTranslateTime(date("Y")),
            "subTitle" => "Laporan keuangan Koreksi " . $tahun,
            "categories" => $arrCatView,
            "rekenings" => $rekeningsNew,
            "dataRekenening"=>$rekeningsKonsolidasiNilai,
            "headers" => $headerss,
            "defaultDate" => isset($defaultDate) ? $defaultDate : "",
            "oldDate" => isset($oldDate) ? $oldDate : "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => true,
            "rekeningKeterangan" => $rekeningKeterangan,
            "masterHeader" =>$masterHeader,
        );
        $this->load->view("$views", $data);


    }

    public function viewNeracaTriwulan()
    {
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();

        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $defaultBulan = $defaultDate_ex[1];

        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $tahun = $defaultDate;
        $prevYear = $defaultDate - 1;
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
        $arrThnBln = array();
        foreach ($arrTahun as $thn) {
            $arrThnBln[] = "$thn-$defaultBulan";
        }

        foreach ($arrThnBln as $thn_ex) {
            $thn_expld = explode("-", $thn_ex);
            $bln_explode = $thn_expld[1];
            $thn_explode = $thn_expld[0];
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='bulanan'");
            $fc->addFilter("bln='$bln_explode'");
            $fc->addFilter("thn='$thn_explode'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
//            showLast_query("biru");
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $this->load->model("Mdls/MdlNeraca");


        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='bulanan'");
            $ner->addFilter("cabang_id='" . my_cabang_id() . "'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);
//            showLast_query("biru");
        }

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
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

                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

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
                                    }

                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion

                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                    }

                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                    $childLink = "";
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link'] = "";
                                }
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
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
//                arrPrintPink($accountRekeningSort_spec);
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {
//                    if (!in_array($rekName, $accountConsolidation)) {
                    if (isset($rekeningsName[$cat])) {
                        if (in_array($rekName, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rekName] = $rekName;
                        }
                    }
//                    }
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca";
            $views = "finance";
            foreach ($arrThnBln as $thn_ex) {
                $headerss["debet_" . $thn_ex] = "debet ($thn_ex)";
                $headerss["kredit_" . $thn_ex] = "kredit ($thn_ex)";
            }
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }

        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca  " . $_GET['label'],
            "subTitle" => "Laporan Neraca  " . $_GET['label'],
            "categories" => $arrCatView,
            "rekenings" => $rekenings[my_cabang_id()],
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
            "arrTahun" => $arrTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewNeracaTtm()
    {
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");

        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $defaultBulan = $defaultDate_ex[1];
        $arrThnBln = backCustomMonths($defaultDate, 12);
        $this->load->model("Mdls/MdlFinanceConfig");
        $tahun = $defaultDate;
        $prevYear = $defaultDate - 1;
//        $arrTahun = array(
//            "this_year" => $tahun,
//            "last_year" => $prevYear,
//        );
//        $arrThnBln = array();
//        foreach ($arrTahun as $thn) {
//            $arrThnBln[] = "$thn-$defaultBulan";
//        }

        foreach ($arrThnBln as $thn_ex) {
            $thn_expld = explode("-", $thn_ex);
            $bln_explode = $thn_expld[1];
            $thn_explode = $thn_expld[0];
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='bulanan'");
            $fc->addFilter("bln='$bln_explode'");
            $fc->addFilter("thn='$thn_explode'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
//            showLast_query("biru");
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $this->load->model("Mdls/MdlNeraca");


        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='bulanan'");
            $ner->addFilter("cabang_id='" . my_cabang_id() . "'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);
//            showLast_query("biru");
        }

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


//        $this->load->model("Mdls/MdlCabang");
//        $cb = new MdlCabang();
//        $arrCabangData = $cb->lookupAll()->result();
//        $arrCabangs['-1'] = "Center";
//        if (sizeof($arrCabangData) > 0) {
//            foreach ($arrCabangData as $cabSpec) {
//                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
//            }
//        }


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
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

                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian total kanan
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] = 0;
                                    }
                                    //endregion

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
                                    }

                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian total kanan
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] += $kredit;
                                                //endregion
                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian total kanan
                                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] += $kredit;
                                                //endregion
                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                        $rekeningsKonsolidasiKanan[$row->kategori][] = $rekeningsKonsolidasiKanan;
                                    }

                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                    $childLink = "";
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link_' . $thn_ex] = "";
                                    $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['link_'] = "";
                                }
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
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {
//                    if (!in_array($rekName, $accountConsolidation)) {
                    if (isset($rekeningsName[$cat])) {
                        if (in_array($rekName, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rekName] = $rekName;
                        }
                    }
//                    }
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi";
            $views = "finance";
//            $headerss = array(
//                "debet_" . $tahun => "debet ($tahun)",
//                "debet_" . $prevYear => "debet ($prevYear)",
//                "kredit_" . $tahun => "kredit ($tahun)",
//                "kredit_" . $prevYear => "kredit ($prevYear)",
//            );
            foreach ($arrThnBln as $thn_ex) {
                $headerss["debet_" . $thn_ex] = "debet ($thn_ex)";
                $headerss["kredit_" . $thn_ex] = "kredit ($thn_ex)";
            }
            $headersKanan = array(
                "0" => "total",
            );
            $subHeadersKanan = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }


        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca TTM " . $_GET['label'],
            "subTitle" => "Laporan Neraca TTM " . $_GET['label'],
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "rekeningsKonsolidasiKanan" => isset($rekeningsKonsolidasiKanan) ? $rekeningsKonsolidasiKanan : array(),
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => array(),//$accountConsolidation
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
            "arrTahun" => $arrTahun,
            "headersKanan" => isset($headersKanan) ? $headersKanan : array(),
            "subHeadersKanan" => isset($subHeadersKanan) ? $subHeadersKanan : array(),
        );
        $this->load->view("$views", $data);

    }

    // bulanan update viewer
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
//        showLast_query("biru");
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


        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );
        if (sizeof($rekeningsKonsolidasi) == 0) {
            unset($arrCabangs[0]);
        }
        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_monthly_konsolidasi";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
            $rekeningsNameNew = array();
            foreach ($arrCatView as $cat) {
                foreach ($accountRekeningSort[$cat] as $rekName) {
                    if (!in_array($rekName, $accountConsolidation)) {
                        if (isset($rekeningsName[$cat])) {
                            if (in_array($rekName, $rekeningsName[$cat])) {
                                $rekeningsNameNew[$cat][$rekName] = $rekName;
                            }
                        }
                    }
                }
            }
        }
        else {
            $views_mode = $this->uri->segment(2);
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
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
        }
        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca Konsolidasi $periode ",
            "subTitle" => "Laporan Neraca Konsolidasi per-" . lgTranslateTime($defaultDate),
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => $headerss,
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
        $this->load->view("$views", $data);

    }

    // tahunan update viewer
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
        $ner->addFilter("periode='tahunan'");
        $tmp = $ner->fetchBalances2($defaultDate);
        //cekKuning($this->db->last_query());

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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }
        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca Konsolidasi Tahunan ",
            //            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "subTitle" => "Laporan Neraca Konsolidasi per- $defaultDate",
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "headers" => $headerss,
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
        $this->load->view("$views", $data);

    }

    // tahunan update vewer
    public function viewNeraca_consolidatedTahunan_lap()
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
        $tahun = $defaultDate;
        $prevYear = $defaultDate - 1;
        $arrTahun = array(
            "last_year" => $prevYear,
            "this_year" => $tahun,
        );
        foreach ($arrTahun as $thn_ex) {

            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='tahunan'");
            $fc->addFilter("thn='$thn_ex'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
//            showLast_query("biru");
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();
// arrPrintPink($accountRekeningSort);
// arrPrintPink($accountConsolidation);
        $this->load->model("Mdls/MdlNeraca");

        foreach ($arrTahun as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='tahunan'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);
//            showLast_query("biru");
        }
        //cekKuning($this->db->last_query());

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


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
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

                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion
                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

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
                                    }

                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion

                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                    }

                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                    $childLink = "";
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link'] = "";
                                }
                            }
                        }

                    }
                }
            }
            reset($dates);
            $oldDate = key($dates);
        }

// arrPrint($rekeningsName);
//         matiHEre();
        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {
                    if (!in_array($rekName, $accountConsolidation)) {
                        if (isset($rekeningsName[$cat])) {
                            if (in_array($rekName, $rekeningsName[$cat])) {
                                $rekeningsNameNew[$cat][$rekName] = $rekName;
                            }
                        }
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet ($tahun)",
                "debet_" . $prevYear => "debet ($prevYear)",
                "kredit_" . $tahun => "kredit ($tahun)",
                "kredit_" . $prevYear => "kredit ($prevYear)",
            );
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }

        // arrPrint($rekeningsNameNew);

        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca Konsolidasi Tahunan ",
            //            "subTitle" => "balance per-" . lgTranslateTime($defaultDate),
            "subTitle" => "Laporan Neraca Konsolidasi Tahun $defaultDate",
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
            "arrTahun" => $arrTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewNeraca_consolidatedTriwulan_lap()
    {
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();

        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $defaultBulan = $defaultDate_ex[1];

        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $tahun = $defaultDate;
        $prevYear = $defaultDate - 1;
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
        $arrThnBln = array();
        foreach ($arrTahun as $thn) {
            $arrThnBln[] = "$thn-$defaultBulan";
        }

        foreach ($arrThnBln as $thn_ex) {
            $thn_expld = explode("-", $thn_ex);
            $bln_explode = $thn_expld[1];
            $thn_explode = $thn_expld[0];
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='bulanan'");
            $fc->addFilter("bln='$bln_explode'");
            $fc->addFilter("thn='$thn_explode'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
//            showLast_query("biru");
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $this->load->model("Mdls/MdlNeraca");


        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='bulanan'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);
//            showLast_query("biru");
        }

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


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
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

                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

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
                                    }

                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion

                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                    }

                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                    $childLink = "";
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link'] = "";
                                }
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
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
//                arrPrintPink($accountRekeningSort_spec);
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {
                    if (!in_array($rekName, $accountConsolidation)) {
                        if (isset($rekeningsName[$cat])) {
                            if (in_array($rekName, $rekeningsName[$cat])) {
                                $rekeningsNameNew[$cat][$rekName] = $rekName;
                            }
                        }
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi";
            $views = "finance";
//            $headerss = array(
//                "debet_" . $tahun => "debet ($tahun)",
//                "debet_" . $prevYear => "debet ($prevYear)",
//                "kredit_" . $tahun => "kredit ($tahun)",
//                "kredit_" . $prevYear => "kredit ($prevYear)",
//            );
            foreach ($arrThnBln as $thn_ex) {
                $headerss["debet_" . $thn_ex] = "debet ($thn_ex)";
                $headerss["kredit_" . $thn_ex] = "kredit ($thn_ex)";
            }
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }

        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca Konsolidasi " . $_GET['label'],
            "subTitle" => "Laporan Neraca Konsolidasi " . $_GET['label'],
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
            "arrTahun" => $arrTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewNeraca_consolidatedTtm_lap()
    {
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");

        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $defaultBulan = $defaultDate_ex[1];
        $arrThnBln = backCustomMonths($defaultDate, 12);
        $this->load->model("Mdls/MdlFinanceConfig");
        $tahun = $defaultDate;
        $prevYear = $defaultDate - 1;
//        $arrTahun = array(
//            "this_year" => $tahun,
//            "last_year" => $prevYear,
//        );
//        $arrThnBln = array();
//        foreach ($arrTahun as $thn) {
//            $arrThnBln[] = "$thn-$defaultBulan";
//        }

        foreach ($arrThnBln as $thn_ex) {
            $thn_expld = explode("-", $thn_ex);
            $bln_explode = $thn_expld[1];
            $thn_explode = $thn_expld[0];
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='bulanan'");
            $fc->addFilter("bln='$bln_explode'");
            $fc->addFilter("thn='$thn_explode'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
//            showLast_query("biru");
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();

        $this->load->model("Mdls/MdlNeraca");


        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlNeraca();
            $ner->addFilter("periode='bulanan'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);
//            showLast_query("biru");
        }

        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");


//        $this->load->model("Mdls/MdlCabang");
//        $cb = new MdlCabang();
//        $arrCabangData = $cb->lookupAll()->result();
//        $arrCabangs['-1'] = "Center";
//        if (sizeof($arrCabangData) > 0) {
//            foreach ($arrCabangData as $cabSpec) {
//                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
//            }
//        }


        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasi = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        $i = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
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

                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                    }
                                    //endregion

                                    //region data konsolidasian total kanan
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'])) {
                                        $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] = 0;
                                    }
                                    //endregion

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
                                    }

                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian total kanan
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiKanan[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiKanan[$cat][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiKanan[$cat][$row->rekening]['kredit'] += $kredit;
                                                //endregion
                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                //endregion
                                                //region data konsolidasian total kanan
                                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['kredit'] += $kredit;
                                                //endregion
                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
                                        $rekeningsKonsolidasiKanan[$row->kategori][] = $rekeningsKonsolidasiKanan;
                                    }

                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                    $childLink = "";
                                    if (isset($accountChilds[$row->rekening])) {
                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='fa fa-clone'></span></a>";
                                    }
                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                        <span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link_' . $thn_ex] = "";
                                    $rekeningsKonsolidasiKanan[$row->kategori][$row->rekening]['link_'] = "";
                                }
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
            foreach ($accountRekeningSort as $thn_ex => $accountRekeningSort_spec) {
//                arrPrintPink($accountRekeningSort_spec);
                foreach ($accountRekeningSort_spec[$cat] as $rekName) {
                    if (!in_array($rekName, $accountConsolidation)) {
                        if (isset($rekeningsName[$cat])) {
                            if (in_array($rekName, $rekeningsName[$cat])) {
                                $rekeningsNameNew[$cat][$rekName] = $rekName;
                            }
                        }
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi";
            $views = "finance";
//            $headerss = array(
//                "debet_" . $tahun => "debet ($tahun)",
//                "debet_" . $prevYear => "debet ($prevYear)",
//                "kredit_" . $tahun => "kredit ($tahun)",
//                "kredit_" . $prevYear => "kredit ($prevYear)",
//            );
            foreach ($arrThnBln as $thn_ex) {
                $headerss["debet_" . $thn_ex] = "debet ($thn_ex)";
                $headerss["kredit_" . $thn_ex] = "kredit ($thn_ex)";
            }
            $headersKanan = array(
                "0" => "total",
            );
            $subHeadersKanan = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = "viewNeraca_consolidated";
            $views = "finance";
            $headerss = array(
                "debet_" . $tahun => "debet",
                "kredit_" . $tahun => "kredit",
                "link" => "",
            );
        }


        $data = array(
            "mode" => $views_mode,
            "title" => "Laporan Neraca Konsolidasi TTM " . $_GET['label'],
            "subTitle" => "Laporan Neraca Konsolidasi TTM " . $_GET['label'],
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "rekeningsKonsolidasiKanan" => isset($rekeningsKonsolidasiKanan) ? $rekeningsKonsolidasiKanan : array(),
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
            "rekeningKeterangan" => $rekeningKeterangan,
            "arrTahun" => $arrTahun,
            "headersKanan" => isset($headersKanan) ? $headersKanan : array(),
            "subHeadersKanan" => isset($subHeadersKanan) ? $subHeadersKanan : array(),
        );
        $this->load->view("$views", $data);

    }

    public function viewNeracaYearToDate_consolidated()
    {
        $pakai_ini = 0;
        if($pakai_ini == 1){

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
            // endregion rl year to date

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                // region monthly
                $defaultDateMonthly = array(
                    "2022-05"
                );
                $tmpMonthly = array();
                $rekeningsMonthly = array();
                $tmpMonthly = array();
                foreach ($defaultDateMonthly as $defaultDateMonthly) {
                    $ner = new MdlNeraca();
                    $ner->addFilter("periode='bulanan'");
                    $tmpMonthly[$defaultDateMonthly] = $ner->fetchBalances2($defaultDateMonthly);
                    //            showLast_query("biru");
                }
                //        arrPrintPink($tmpMonthly);
                if (sizeof($tmpMonthly) > 0) {
                    foreach ($tmpMonthly as $bln_ex => $bln_ex_spec) {
                        foreach ($bln_ex_spec as $cabID => $nerSpec) {
                            foreach ($nerSpec as $rowSpec) {
                                foreach ($rowSpec as $row) {
                                    $i++;
                                    $defPos = detectRekDefaultPosition($row->rekening);
                                    if (($row->tipe == "konsolidasi_cost") || ($row->tipe == "konsolidasi_riil")) {
                                        $rekeningsMonthly[$row->rekening] = $row->rekening;
                                    }
                                    //
                                    if (strlen($row->kategori) > 1) {
                                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                                            //                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";
                                            //                                    if (!in_array($row->kategori, $categories)) {
                                            //                                        $categories[] = $row->kategori;
                                            //                                    }

                                            //region data per-cabang
                                            //                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                            //                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                            //                                    }
                                            //                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex])) {
                                            //                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] = 0;
                                            //                                    }
                                            //                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex])) {
                                            //                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] = 0;
                                            //                                    }
                                            //endregion

                                            //region data konsolidasian
                                            if (!isset($rekeningsMonthly[$bln_ex][$row->kategori])) {
                                                $rekeningsMonthly[$bln_ex][$row->kategori] = array();
                                            }
                                            if (!isset($rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['debet_'])) {
                                                $rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['debet_'] = 0;
                                            }
                                            if (!isset($rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['kredit_'])) {
                                                $rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['kredit_'] = 0;
                                            }
                                            //endregion

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
                                            }

                                            if (sizeof($accountCatException) > 0) {
                                                foreach ($accountCatException as $cat => $c_rekName) {
                                                    if (in_array($row->rekening, $c_rekName)) {
                                                        //region data per-cabang
                                                        //                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex])) {
                                                        //                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] = 0;
                                                        //                                                }
                                                        //                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex])) {
                                                        //                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] = 0;
                                                        //                                                }
                                                        //                                                if (!isset($rekenings[$row->cabang_id][$cat][$row->rekening]['link'])) {
                                                        //                                                    $rekenings[$row->cabang_id][$cat][$row->rekening]['link'] = "";
                                                        //                                                }
                                                        //                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                        //                                                $rekenings[$row->cabang_id][$cat][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                        //endregion

                                                        //region data konsolidasian
                                                        if (!isset($rekeningsMonthly[$bln_ex][$cat][$row->rekening]['debet_'])) {
                                                            $rekeningsMonthly[$bln_ex][$cat][$row->rekening]['debet_'] = 0;
                                                        }
                                                        if (!isset($rekeningsMonthly[$bln_ex][$cat][$row->rekening]['kredit_'])) {
                                                            $rekeningsMonthly[$bln_ex][$cat][$row->rekening]['kredit_'] = 0;
                                                        }
                                                        if (!isset($rekeningsMonthly[$bln_ex][$cat][$row->rekening]['link'])) {
                                                            $rekeningsMonthly[$bln_ex][$cat][$row->rekening]['link'] = "";
                                                        }
                                                        $rekeningsMonthly[$bln_ex][$cat][$row->rekening]['debet_'] += $debet;
                                                        $rekeningsMonthly[$bln_ex][$cat][$row->rekening]['kredit_'] += $kredit;
                                                        //endregion

                                                        $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                                    }
                                                    else {
                                                        //region data per-cabang
                                                        //                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet_' . $thn_ex] += $debet;
                                                        //                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit_' . $thn_ex] += $kredit;
                                                        //endregion

                                                        //region data konsolidasian
                                                        $rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['debet_'] += $debet;
                                                        $rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['kredit_'] += $kredit;
                                                        //endregion

                                                        $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                                    }
                                                }
                                            }
                                            else {
                                                $rekenings[$row->kategori][] = $rekenings;
                                                $rekeningsMonthly[$bln_ex][$row->kategori][] = $rekeningsMonthly;
                                            }
                                            //
                                            //                                    $whID = getDefaultWarehouseID($row->cabang_id);
                                            //                                    $childLink = "";
                                            //                                    if (isset($accountChilds[$row->rekening])) {
                                            //                                        $childLink = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                            //                                        <span class='fa fa-clone'></span></a>";
                                            //                                    }
                                            //                                    $childLink2 = "$childLink <span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&w=" . $whID['gudang_id'] . "'>
                                            //                                        <span class='glyphicon glyphicon-time'></span></a></span>";
                                            //
                                            //                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['link'] = $childLink2;
                                            $rekeningsMonthly[$bln_ex][$row->kategori][$row->rekening]['link'] = "";
                                        }
                                    }
                                }

                            }
                        }
                    }
                    reset($dates);
                    $oldDate = key($dates);
                }
                // endregion
                //arrPrintHijau($rekeningsMonthly);
            }

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
            $rekeningsKonsolidasiNilai = array();
            $i = 0;
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
                            $i++;
                            $defPos = detectRekDefaultPosition($row->rekening);

                            if (strlen($row->kategori) > 1) {
                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!in_array($row->kategori, $categories)) {
                                    $categories[] = $row->kategori;
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


                                //region data per-cabang
                                if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                    $rekenings[$row->cabang_id][$row->kategori] = array();
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                    $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                }
                                //endregion
                                //region data konsolidasian
                                if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                    $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                }
                                if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'])) {
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'] = 0;
                                }
                                if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'])) {
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'] = 0;
                                }
                                //endregion


                                if (sizeof($accountCatException) > 0) {
                                    foreach ($accountCatException as $cat => $c_rekName) {
                                        if (in_array($row->rekening, $c_rekName)) {
                                            //region data per-cabang
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
                                            //endregion
                                            //region data konsolidasian
                                            if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'])) {
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'] = 0;
                                            }
                                            if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'])) {
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'] = 0;
                                            }
                                            if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                            }
                                            $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'] += $debet;
                                            $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'] += $kredit;
                                            //endregion

                                            $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                        }
                                        else {
                                            //region data per-cabang
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                            //endregion
                                            //region data konsolidasian
                                            $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'] += $debet;
                                            $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'] += $kredit;
                                            //endregion

                                            $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                        }
                                    }
                                }
                                else {
                                    $rekenings[$row->kategori][] = $rekenings;
                                    $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
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
                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link'] = "";
                            }
                        }

                    }

                }

            }
        }
        else{
            $_GET['tm'] = 1;
            if ($_GET['tm'] == 1) {
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
                $date1 = date("Y-01-01");
                $date2 = date("Y-m-d");
                $dateNow = date("Y-m-d");
                $dateTimeNow = date("Y-m-d H:i:s");
                $dateExp = explode("-", $dateNow);
                $tgl = $dateExp[2];
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0] - 1;
                $resultNeracaByCabang = array();
                foreach ($arrCabangs as $cabangID => $cabangName) {
                    $static = array(
                        "static" => array(
                            "cabang_id" => $cabangID,
                            "dtime" => $dateTimeNow,
                            "fulldate" => $dateNow,
                            //                        "bln" => $bulan,
                            "thn" => $tahun,
                            "periode" => $periode,
                        ),
                    );
                    $filters = array(
                        "periode" => $periode,
                        "cabang_id" => $cabangID,
                        //                    "bln" => $bulan,
                        "thn" => $tahun,
                    );
                    $filters2 = array(
                        "periode=" => $periode,
                        "cabang_id=" => $cabangID,
                        //                    "date(dtime)<=" => $date2,
                        "thn" => $tahun,
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
                    //                showlast_query("biru");
                    $arrLajurNew = array();
                    foreach ($tmp as $spec) {
                        $rek = $spec['rekening'];
                        if (!in_array($rek, $arrRekBlacklist)) {
                            $arrLajurNew[$rek] = $spec;
                        }
                    }
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

                // arrPrint($resultNeracaByCabang);

                $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
                $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
                $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
                $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
                $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
                $accountConsolidation = $this->config->item("accountBalanceConsolidation") != null ? $this->config->item("accountBalanceConsolidation") : array();
                $rekeningCoa = rekening_coa_he_accounting();
                $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
                $accountRekeningSort = rekening_coa_sort_he_accounting();

                $tmp = $resultNeracaByCabang;
                $arrCabang = array();
                $categories = array();
                $rekenings = array();
                $rekeningsName = array();
                $rekeningsKonsolidasiNilai = array();
                $i = 0;
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $cabID => $nerSpec) {
                        foreach ($nerSpec as $rowSpec) {
                            foreach ($rowSpec as $row) {
                                $i++;
                                $defPos = detectRekDefaultPosition($row->rekening);

                                if (strlen($row->kategori) > 1) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    if (!in_array($row->kategori, $categories)) {
                                        $categories[] = $row->kategori;
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


                                    //region data per-cabang
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori])) {
                                        $rekenings[$row->cabang_id][$row->kategori] = array();
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] = 0;
                                    }
                                    if (!isset($rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'])) {
                                        $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] = 0;
                                    }
                                    //endregion
                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori] = array();
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'] = 0;
                                    }
                                    if (!isset($rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'])) {
                                        $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'] = 0;
                                    }
                                    //endregion


                                    if (sizeof($accountCatException) > 0) {
                                        foreach ($accountCatException as $cat => $c_rekName) {
                                            if (in_array($row->rekening, $c_rekName)) {
                                                //region data per-cabang
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
                                                //endregion
                                                //region data konsolidasian
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'] = 0;
                                                }
                                                if (!isset($rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'])) {
                                                    $rekeningsKonsolidasiNilai[$cat][$row->rekening]['link'] = "";
                                                }
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiNilai[$cat][$row->rekening]['kredit'] += $kredit;
                                                //endregion

                                                $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                            }
                                            else {
                                                //region data per-cabang
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['debet'] += $debet;
                                                $rekenings[$row->cabang_id][$row->kategori][$row->rekening]['kredit'] += $kredit;
                                                //endregion
                                                //region data konsolidasian
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['debet'] += $debet;
                                                $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['kredit'] += $kredit;
                                                //endregion

                                                $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                            }
                                        }
                                    }
                                    else {
                                        $rekenings[$row->kategori][] = $rekenings;
                                        $rekeningsKonsolidasiNilai[$row->kategori][] = $rekeningsKonsolidasiNilai;
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
                                    $rekeningsKonsolidasiNilai[$row->kategori][$row->rekening]['link'] = "";
                                }
                            }

                        }

                    }

                }
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (!in_array($rekName, $accountConsolidation)) {

                    if (isset($rekeningsName[$cat])) {
                        if (in_array($rekName, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rekName] = $rekName;
                        }
                    }
                }
            }
        }


        $rekeningKeterangan = array(
            "piutang ke pusat" => "uang muka dari konsumen belum menjadi hak kita untuk melunasi hutang ke pusat",
        );

        $oldDate = $date1;
        $defaultDate = date("Y");
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_neraca_konsolidasi_ytd";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = $this->uri->segment(2);
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
        }
        //        $data = array(
        //            "mode" => $this->uri->segment(2),
        //            "title" => "Neraca Konsolidasi Year to Date ",
        //            "subTitle" => "Neraca Konsolidasi per-" . lgTranslateTime($defaultDate),
        //            "categories" => $arrCatView,
        //            "rekenings" => $rekenings,
        //            "headers" => array(
        //                //                "rekening" => "rekening",
        //                "debet" => "debet",
        //                "kredit" => "kredit",
        //                "link" => "",
        //            ),
        //            "defaultDate" => $defaultDate,
        //            "oldDate" => $oldDate,
        //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
        //            //            "cabang" => $arrCabang,
        //            "cabang" => $arrCabangs,
        //            "rekeningsName" => $rekeningsNameNew,
        //            "rekeningsNameAlias" => $accountAlias,
        //            "accountConsolidation" => $accountConsolidation,
        //            "rekeningKeterangan" => $rekeningKeterangan,
        //        );

        //        arrPrintHijau($rekeningsKonsolidasiNilai);
        $data = array(
            "mode" => $views_mode,
            "title" => "Neraca Konsolidasi Year to Date ",
            "subTitle" => "Neraca Konsolidasi Year to Date " . formatTanggal(date("Y-m-d"), 'd F Y'),
            "categories" => $arrCatView,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilai,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            //            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "accountConsolidation" => $accountConsolidation,
            "rekeningKeterangan" => $rekeningKeterangan,
        );
        $this->load->view("$views", $data);


    }

    //RUGILABA-----------------------------------------------
    public function viewPL()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $rekException = array("rugilaba");
        $previousMonth = previousMonth();
        // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $d_start = "$tahun-$bulan-01";
        $d_last = formatTanggal($d_start, "t");
        $d_stop = "$tahun-$bulan-$d_last";

        $periode = "bulanan";
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
        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

        $cabangIDsession = $this->session->login['cabang_id'];
        $ner = new MdlRugilaba();
        $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);

        $dates = $ner->fetchDates();

        $oldDate = date("Y-m");

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                //                if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                foreach ($categoryRL as $k => $catSpec) {
                    if (array_key_exists($row->rekening, $catSpec)) {

                        if (!isset($rekenings[$k])) {
                            $rekenings[$k] = array();
                        }
                        if (!isset($rekeningsName[$k])) {
                            $rekeningsName[$k] = array();
                        }
                        if (!in_array($row->rekening, $rekException)) {

                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                $value = $value > 0 ? $value * -1 : $value;
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                $value = $value < 0 ? $value * -1 : $value;
                            }
                        }
                        else {
                            if ($row->debet > 0) {
                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                            }
                            else {
                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                            }
                        }

                        $rek_nama_alias = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                        $tmpCol = array(
                            //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                            "rek_id" => "",
                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                            "values" => $value,
                            "link" => "",
                        );
                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop&periode=bulanan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                        }
                        //                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";

                        $rekenings[$k][$row->rekening] = $tmpCol;
                    }
                }
                //                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        ksort($rekenings);
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        foreach ($categoriesAll as $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {

                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }

            }
        }

        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "rugi laba final",
            "subTitle" => "rugi laba final " . lgTranslateTime2($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "values" => "balance(IDR)",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "linkExcel" => base_url() . "ExcelWriter/rugiLaba",
            "dateSelector" => true,
            "rekeningBlacklist" => $rekException,
            "buttonMode" => array(
                "enabled" => true,
                "label" => "laporan rugilaba (internal)",
                "link" => base_url() . get_class($this) . "/viewPLKoreksi",
            ),
        );
        $this->load->view("finance", $data);

    }

    public function viewPLTahunan()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $rekException = array("rugilaba");
        $previousMonth = previousYear();
        // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;
        $d_start = "$tahun-$bulan-01";
        $d_last = formatTanggal($d_start, "t");
        $d_stop = "$tahun-$bulan-$d_last";

        $periode = "tahunan";
        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        // $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        // showLast_query("lime");
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

        $cabangIDsession = $this->session->login['cabang_id'];
        $arrTahun = array(
            "last_year" => $prevYear,
            "this_year" => $tahun,
        );
        foreach ($arrTahun as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
            $ner->addFilter("periode='$periode'");
            $tmp[$tahun_ex] = $ner->fetchBalances($tahun_ex);//$defaultDate
            // showLast_query("kuning");
        }
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");
//arrPrintPink($tmp);
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $row) {
                    foreach ($categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {

                            if (!isset($rekenings[$thn_ex][$k])) {
                                $rekenings[$thn_ex][$k] = array();
                            }
                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }
                            if (!in_array($row->rekening, $rekException)) {

                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }

                            $rek_nama_alias = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
//                                "values_".$thn_ex => $value,
                                "$thn_ex" => $value,
                                "link" => "",
                            );
                            if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                                $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop&periode=tahunan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                            }
                            //                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";
                            $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";

                            $rekenings[$thn_ex][$k][$row->rekening] = $tmpCol;
                        }
                    }

                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        ksort($rekenings);
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        foreach ($categoriesAll as $cat) {
            if (array_key_exists($cat, $rekenings[$tahun])) {
                $categories[] = $cat;
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {

                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }

            }
        }

//arrPrintPink($rekenings);
        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headersTahun = array(
                "$tahun" => "$tahun",
                "$prevYear" => "$prevYear",
            );
            $rekeningSelected = $rekenings;
        }
        else {
            $views_mode = "viewRugiLabaTahunan";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => "rugi laba",
            "subTitle" => "rugi laba tahun " . $defaultDate,
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "headers" => $headersTahun,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "linkExcel" => base_url() . "ExcelWriter/rugiLaba",
            "dateSelector" => true,
            "rekeningBlacklist" => $rekException,
            "gr" => isset($_GET['gr']) ? $_GET['gr'] : "",
            "tahunDipilih" => $defaultDate,
            "headersTahun" => $headersTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewPLYearToDate()
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
                $mts->addFilter("date(dtime)>='$date1'");
                $mts->addFilter("date(dtime)<='$date2'");
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
        $tmpLastNeraca = $ner->fetchBalances($tahunLast);
        //        cekKuning($this->db->last_query());


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

        //region lajur...
        $totalDebet = 0;
        $totalKredit = 0;
        $str = "";
        $str .= "<table rules='all' border='1px solid black;'>";
        foreach ($resultRL['rugilaba'] as $rek => $spec) {

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
        //        echo "<br>RL<br>$str";
        //endregion


        //        $defaultDate=isset($_GET['date'])?$_GET['date']:date("Y-m-d");
        $defaultDate = "$tahun-$bulan";
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $this->load->model("Mdls/" . "MdlRugilaba");
        $rekException = array("rugilaba");
        //        $rekException = array();


        $tmp = array();
        if (sizeof($resultRL['rugilaba']) > 0) {
            foreach ($resultRL['rugilaba'] as $nn => $nSpec) {
                $temp = array();
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                }
                $tmp[$nn] = (object)$temp;
            }
        }


        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                    foreach ($categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {

                            if (!isset($rekenings[$k])) {
                                $rekenings[$k] = array();
                            }
                            if (!isset($rekeningsName[$k])) {
                                $rekeningsName[$k] = array();
                            }
                            if (!in_array($row->rekening, $rekException)) {

                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    $value = $value > 0 ? $value * -1 : $value;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    $value = $value < 0 ? $value * -1 : $value;
                                }
                            }
                            else {
                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                }
                            }

                            //arrprint($row);
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "values" => $value,
                                "link" => "",
                            );
                            if (isset($accountChilds[$row->rekening])) {
                                $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "'><span class='fa fa-clone'></span></a>";
                            }

                            $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";
                            //                            $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=-1&date1=$date1&date2=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                            $rekenings[$k][$row->rekening] = $tmpCol;
                        }
                    }
                }
            }
        }
        ksort($rekenings);
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        foreach ($categoriesAll as $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }

        //        cekHijau(blobDecode($_GET['gr']));
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu();
            //            cekHere($title);
        }
        else {
            $title = "profit & loss report (year to date)";
        }
        $oldDate = "2019-09";

        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_ytd";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }
        else {
            $views_mode = "viewRugiLaba2";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }


        $data = array(
            "mode" => "$views_mode",
            "title" => "$title",
            "subTitle" => strtoupper(my_cabang_nama()) . " " . lgTranslateTime2($date1) . " - " . lgTranslateTime2($date2),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rek_id" => "code",
                //                "rekening" => "rekening",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "values" => "balance(IDR)",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => $categoryRLBottom,

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => false,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),

        );
        $this->load->view("$views", $data);
    }

    public function viewPLTriwulan()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;//previousYear($tahun);
//cekHere("[$tahun] [$prevYear]");
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

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $periode = "tahunan";
        $rekException = array("rugilaba");
        $enBulan = blobDecode($_GET['enbln']);
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
        $arrThnBln = array();
        foreach ($arrTahun as $thnxx) {
            foreach ($enBulan as $blnxx) {
                $arrThnBln[] = "$thnxx-$blnxx";
            }
        }
//        $arrThnBln = array(
//            "2022-01",
//            "2022-02",
//            "2022-03",
//            "2021-01",
//            "2021-02",
//            "2021-03",
//        );
//        arrPrint($arrThnBln);
        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("periode='bulanan'");
            $ner->addFilter("cabang_id='" . my_cabang_id() . "'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//            showLast_query("kuning");
        }
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");

        //region cabang
        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }
        //endregion

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
                            $thn_ex = $row->thn;
                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    //region data per-cabang
                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                                    }
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                    //endregion


                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                    //endregion

                                }
                            }
                        }
                    }
                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }
//arrPrintHijau($rekenings);
//arrPrintHijau($rekeningsKonsolidasiNilai);

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings[$tahun])) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }
//mati_disini();
        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headersTahun = array(
                "$tahun" => $_GET['label'] . " $tahun",
                "$prevYear" => $_GET['label'] . " $prevYear",
            );
            $rekeningSelected = $rekeningsKonsolidasiNilai;
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai[$tahun];
        }
//arrPrintHijau($rekenings);
//arrPrintPink($headerss);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba  " . $_GET['label'],
            "subTitle" => "Laporan Rugilaba  " . $_GET['label'],
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "headersTahun" => $headersTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewPLTtm()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;//previousYear($tahun);

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

//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $defaultDate_ex = explode("-", $defaultDate);
//        $defaultDate = $defaultDate_ex[0];
        $periode = "tahunan";
        $rekException = array("rugilaba");
//        $enBulan = blobDecode($_GET['enbln']);
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
//        $arrThnBln = array();
//        foreach ($arrTahun as $thnxx) {
//            foreach ($enBulan as $blnxx) {
//                $arrThnBln[] = "$thnxx-$blnxx";
//            }
//        }
//        $arrThnBln = array(
//            "2022-01",
//            "2022-02",
//            "2022-03",
//            "2021-01",
//            "2021-02",
//            "2021-03",
//        );
//        arrPrint($arrThnBln);
        $arrThnBln = backCustomMonths($defaultDate, 12);
        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("periode='bulanan'");
            $ner->addFilter("cabang_id='" . my_cabang_id() . "'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//            showLast_query("kuning");
        }
//        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");

        //region cabang
        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }
        //endregion

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {

                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    //region data per-cabang
                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                                    }
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                    //endregion

                                    //region data konsolidasian total kanan
                                    if (!isset($rekeningsKonsolidasiKanan[$k])) {
                                        $rekeningsKonsolidasiKanan[$k] = array();
                                    }

//                                    if (!isset($rekeningsName[$k])) {
//                                        $rekeningsName[$k] = array();
//                                    }
//
//                                    if (!in_array($row->rekening, $rekException)) {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
//                                            $value = $value > 0 ? $value * -1 : $value;
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                            $value = $value < 0 ? $value * -1 : $value;
//                                        }
//                                    }
//                                    else {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                        }
//                                    }

                                    if (!isset($rekeningsKonsolidasiKanan[$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link_detail'] = "";
                                    //endregion
                                }
                            }
                        }
                    }
                }
            }
//            reset($dates);
//            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }
//arrPrintWebs($rekenings);
//arrPrintHijau($rekeningsName);
//arrPrintHijau($rekeningsKonsolidasiNilai);
//arrPrintHijau($rekeningsKonsolidasiKanan);
//mati_disini();
        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings[$arrThnBln[0]])) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }

        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }

        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headerssKanan = array(
                "values" => "total(IDR)",
            );
            foreach ($arrThnBln as $ii) {
                $headersTahun[$ii] = $ii;
            }
            $rekeningSelected = $rekenings;
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
            $rekeningsKonsolidasiKananNilai["values"] = $rekeningsKonsolidasiKanan;
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai[$tahun];
        }
//arrPrintHijau($rekeningsKonsolidasiNilaiSelected);
//arrPrintPink($headerss);
//arrPrintPink($rekeningsNameNew);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba TTM ",
            "subTitle" => "Laporan Rugilaba TTM ",
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "rekeningsKonsolidasiKanan" => isset($rekeningsKonsolidasiKananNilai) ? $rekeningsKonsolidasiKananNilai : array(),
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
//            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "headersTahun" => $headersTahun,
            "headersKanan" => isset($headerssKanan) ? $headerssKanan : array(),
//            "headersKanan" => array(),
        );
        $this->load->view("$views", $data);

    }

    // bulanan update viewer
    public function viewPL_consolidated()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
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

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $ner = new MdlRugilaba();
        $tmp = $ner->fetchBalances2($defaultDate);
//        showLast_query("kuning");
        //        cekkuning($this->db->last_query());
        $ner->addFilter("periode='$periode'");
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
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        //                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                        foreach ($categoryRL as $k => $catSpec) {
                            if (array_key_exists($row->rekening, $catSpec)) {
                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!isset($rekenings[$k][$row->cabang_id])) {
                                    $rekenings[$k][$row->cabang_id] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!in_array($row->rekening, $rekException)) {
                                    if ($row->debet > 0) {
                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        $value = $value > 0 ? $value * -1 : $value;
                                    }
                                    else {
                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        $value = $value < 0 ? $value * -1 : $value;
                                    }
                                }
                                else {
                                    if ($row->debet > 0) {
                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    }
                                    else {
                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    }
                                }
                                $debett = $row->debet;
                                $kreditt = $row->kredit;

                                $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";


                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";
//                                $link_detail = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>";

                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;

                            }
                        }
                        //                        }
                    }
                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }


        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }

        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_monthly_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
//                "link_detail" => "",
//                "link" => "",
            );
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi ",
            "subTitle" => "Laporan Rugilaba Konsolidasi " . lgTranslateTime2($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("$views", $data);

    }

    // tahunan update viewer
    public function viewPL_consolidatedTahunan()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $periode = "tahunan";
        $rekException = array("9010");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;//previousYear($tahun);
//cekKuning("$tahun -- $prevYear");
        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
//        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }

        $pakai_ini = 0;
        if($pakai_ini == 1){
            $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
            $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
            $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

            $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
            $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

            $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
            $defaultDate_ex = explode("-", $defaultDate);
            $defaultDate = $defaultDate_ex[0];
            $periode = "tahunan";
            $rekException = array("9010");
            $arrTahun = array(
                "last_year" => $prevYear,
                "this_year" => $tahun,
            );
            foreach ($arrTahun as $tahun_ex) {
                $ner = new MdlRugilaba();
                $ner->addFilter("periode='$periode'");
                $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//            showLast_query("kuning");
            }
            $dates = $ner->fetchDates();
            $oldDate = date("Y-m");

            //region cabang
            $this->load->model("Mdls/" . "MdlCabang");
            $cb = new MdlCabang();
            $arrCabangData = $cb->lookupAll()->result();
            $arrCabangs['-1'] = "Center";
            if (sizeof($arrCabangData) > 0) {
                foreach ($arrCabangData as $cabSpec) {
                    $arrCabangs[$cabSpec->id] = $cabSpec->nama;
                }
            }
            //endregion

            $arrCabang = array();
            $categories = array();
            $rekenings = array();
            $rekeningsName = array();
            $rekeningsKonsolidasiNilai = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $thn_ex => $thn_ex_spec) {
                    foreach ($thn_ex_spec as $cabID => $nerSpec) {
                        foreach ($nerSpec as $rowSpec) {
                            foreach ($rowSpec as $row) {
                                foreach ($categoryRL as $k => $catSpec) {
                                    if (array_key_exists($row->rekening, $catSpec)) {
                                        $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                        //region data per-cabang
                                        if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                            $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                        }
                                        if (!isset($rekeningsName[$k])) {
                                            $rekeningsName[$k] = array();
                                        }

                                        if (!in_array($row->rekening, $rekException)) {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                                $value = $value > 0 ? $value * -1 : $value;
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                $value = $value < 0 ? $value * -1 : $value;
                                            }
                                        }
                                        else {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            }
                                        }

                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                        $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                        $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                        //endregion


                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                        }
                                        if (!isset($rekeningsName[$k])) {
                                            $rekeningsName[$k] = array();
                                        }

                                        if (!in_array($row->rekening, $rekException)) {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                                $value = $value > 0 ? $value * -1 : $value;
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                $value = $value < 0 ? $value * -1 : $value;
                                            }
                                        }
                                        else {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            }
                                        }

                                        if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                        }
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                        //endregion

                                    }
                                }
                            }
                        }
                    }
                }
                reset($dates);
                $oldDate = key($dates);
            }
            $rekeningsName = array();
            if (sizeof($categoryRL) > 0) {
                foreach ($categoryRL as $l => $rlSpec) {
                    foreach ($rlSpec as $k_rek => $v_rek) {
                        $rekeningsName[$l][$k_rek] = $k_rek;
                    }
                }
            }

            $categoriesAll = array(1,
                2,
                3,
                4
            );
            $categories = array();
            $categoriesSubBottom = array();
            foreach ($categoriesAll as $ctr => $cat) {
                if (array_key_exists($cat, $rekenings[$tahun])) {
                    $categories[] = $cat;
                    $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                }
            }
            $rekeningsNameNew = array();
            foreach ($categories as $cat) {
                foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                    if (in_array($rek_key, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                    }
                }
            }
        }
        else{
            $_GET['tm'] = 1;
            if ($_GET['tm'] == 1) {
                $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
                $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
                $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
                $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
                $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
                $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

                $rekeningCoa = rekening_coa_he_accounting();
                $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
                $accountRekeningSort = rekening_coa_sort_he_accounting();
                $categoryRL_OLD = $categoryRL;
                $categoryRL = array();
                foreach ($categoryRL_OLD as $cat => $catSpec) {
                    foreach ($catSpec as $key => $val) {
                        if(isset($rekeningCoa[$key])){
                            $key_new = $rekeningCoa[$key];
                            $categoryRL[$cat][$key_new] = $val;
                        }
                    }
                }


                $defaultDate_ex = explode("-", $defaultDate);
                $defaultDate = $defaultDate_ex[0];
                $tahun = $defaultDate_ex[0];
                $prevYear = $tahun - 1;//previousYear($tahun);
                $periode = "tahunan";
                $rekException = array("9010");
                $arrTahun = array(
                    "last_year" => $prevYear,
                    "this_year" => $tahun,
                );
                foreach ($arrTahun as $tahun_ex) {
                    $ner = new MdlRugilaba();
                    $ner->addFilter("periode='$periode'");
                    $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//                    showLast_query("kuning");
                }
                $dates = $ner->fetchDates();
                $oldDate = date("Y-m");

                //region cabang
                $this->load->model("Mdls/" . "MdlCabang");
                $cb = new MdlCabang();
                $arrCabangData = $cb->lookupAll()->result();
                $arrCabangs['-1'] = "Center";
                if (sizeof($arrCabangData) > 0) {
                    foreach ($arrCabangData as $cabSpec) {
                        $arrCabangs[$cabSpec->id] = $cabSpec->nama;
                    }
                }
                //endregion

                $arrCabang = array();
                $categories = array();
                $rekenings = array();
                $rekeningsName = array();
                $rekeningsKonsolidasiNilai = array();
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $thn_ex => $thn_ex_spec) {
                        foreach ($thn_ex_spec as $cabID => $nerSpec) {
                            foreach ($nerSpec as $rowSpec) {
                                foreach ($rowSpec as $row) {
                                    foreach ($categoryRL as $k => $catSpec) {
                                        // bila rekening bukan kode coa maka diberi kode coa
                                        if(!is_numeric($row->rekening)){
                                            $row->rekening = $rekeningCoa[$row->rekening];
                                        }
//                        cekHere($row->rekening);

                                        if (array_key_exists($row->rekening, $catSpec)) {
                                            $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                            //region data per-cabang
                                            if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                                $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                            }
                                            if (!isset($rekeningsName[$k])) {
                                                $rekeningsName[$k] = array();
                                            }

                                            if (!in_array($row->rekening, $rekException)) {
                                                if ($row->debet > 0) {
                                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                                    $value = $value > 0 ? $value * -1 : $value;
                                                }
                                                else {
                                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                    $value = $value < 0 ? $value * -1 : $value;
                                                }
                                            }
                                            else {
                                                if ($row->debet > 0) {
                                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                                }
                                                else {
                                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                }
                                            }

                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                            $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                            $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                            $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                            //endregion


                                            //region data konsolidasian
                                            if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                                $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                            }
                                            if (!isset($rekeningsName[$k])) {
                                                $rekeningsName[$k] = array();
                                            }

                                            if (!in_array($row->rekening, $rekException)) {
                                                if ($row->debet > 0) {
                                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                                    $value = $value > 0 ? $value * -1 : $value;
                                                }
                                                else {
                                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                    $value = $value < 0 ? $value * -1 : $value;
                                                }
                                            }
                                            else {
                                                if ($row->debet > 0) {
                                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                                }
                                                else {
                                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                }
                                            }

                                            if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                                $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                            }
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                            $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                            //endregion

                                        }
                                    }
                                }
                            }
                        }
                    }
                    reset($dates);
                    $oldDate = key($dates);
                }
                $rekeningsName = array();
                if (sizeof($categoryRL) > 0) {
                    foreach ($categoryRL as $l => $rlSpec) {
                        foreach ($rlSpec as $k_rek => $v_rek) {
                            $rekeningsName[$l][$k_rek] = $k_rek;
                        }
                    }
                }
//cekHere($tahun);
//arrPrint($rekenings);
//arrPrint($rekenings[$tahun]);
//arrPrint($categoryRL);
//arrPrintWebs($rekeningsName);
                $categoriesAll = array(
                    1,
                    2,
                    3,
                    4
                );
                $categories = array();
                $categoriesSubBottom = array();
                foreach ($categoriesAll as $ctr => $cat) {
                    if (array_key_exists($cat, $rekenings[$tahun])) {
                        $categories[] = $cat;
                        $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                    }
                }
//arrPrint($categories);
                $rekeningsNameNew = array();
                foreach ($categories as $cat) {
                    foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                        if (in_array($rek_key, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                        }
                    }
                }
//arrPrintPink($rekeningsNameNew);
            }
        }

        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headersTahun = array(
                "$tahun" => "$tahun",
                "$prevYear" => "$prevYear",
            );
            $rekeningSelected = $rekenings;
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai[$tahun];
        }

        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi tahunan ",
            "subTitle" => "Laporan Rugilaba Konsolidasi tahunan " . lgTranslateTime3($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "headersTahun" => $headersTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewPL_consolidatedTriwulan()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;//previousYear($tahun);
//cekHere("[$tahun] [$prevYear]");
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

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $periode = "tahunan";
        $rekException = array("rugilaba");
        $enBulan = blobDecode($_GET['enbln']);
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
        $arrThnBln = array();
        foreach ($arrTahun as $thnxx) {
            foreach ($enBulan as $blnxx) {
                $arrThnBln[] = "$thnxx-$blnxx";
            }
        }
//        $arrThnBln = array(
//            "2022-01",
//            "2022-02",
//            "2022-03",
//            "2021-01",
//            "2021-02",
//            "2021-03",
//        );
//        arrPrint($arrThnBln);
        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("periode='bulanan'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//            showLast_query("kuning");
        }
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");

        //region cabang
        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }
        //endregion

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
                            $thn_ex = $row->thn;
                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    //region data per-cabang
                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                                    }
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                    //endregion


                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                    //endregion

                                }
                            }
                        }
                    }
                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }
//arrPrintHijau($rekenings);
//arrPrintHijau($rekeningsKonsolidasiNilai);

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings[$tahun])) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }
//mati_disini();
        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headersTahun = array(
                "$tahun" => $_GET['label'] . " $tahun",
                "$prevYear" => $_GET['label'] . " $prevYear",
            );
            $rekeningSelected = $rekenings;
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai[$tahun];
        }
//arrPrintHijau($rekeningsKonsolidasiNilaiSelected);
//arrPrintPink($headerss);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi " . $_GET['label'],
            "subTitle" => "Laporan Rugilaba Konsolidasi " . $_GET['label'],
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
//            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "headersTahun" => $headersTahun,
        );
        $this->load->view("$views", $data);

    }

    public function viewPL_consolidatedTtm()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $prevYear = $tahun - 1;//previousYear($tahun);
        $arrThnBln = backCustomMonths($defaultDate, 12);

        foreach ($arrThnBln as $thn_ex) {
            $thn_expld = explode("-", $thn_ex);
            $bln_explode = $thn_expld[1];
            $thn_explode = $thn_expld[0];
            $fc = New MdlFinanceConfig();
            $fc->addFilter("periode='$periode'");
            $fc->addFilter("bln='$bln_explode'");
            $fc->addFilter("thn='$thn_explode'");
            $fcTmp[$thn_ex] = $fc->lookupAll()->result();
        }
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $fcSpec) {
                    $fcResult[$fcSpec->param][$thn_ex] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
                }
            }
        }


//        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL'][previousMonth()]) && ($fcResult['categoryRL'][previousMonth()] != NULL)) ? $fcResult['categoryRL'][previousMonth()] : $this->config->item("categoryRL");
        $categoryRLAll = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $defaultDate_ex = explode("-", $defaultDate);
//        $defaultDate = $defaultDate_ex[0];
        $periode = "tahunan";
        $rekException = array("rugilaba");
//        $enBulan = blobDecode($_GET['enbln']);
        $arrTahun = array(
            "this_year" => $tahun,
            "last_year" => $prevYear,
        );
//        $arrThnBln = array();
//        foreach ($arrTahun as $thnxx) {
//            foreach ($enBulan as $blnxx) {
//                $arrThnBln[] = "$thnxx-$blnxx";
//            }
//        }
//        $arrThnBln = array(
//            "2022-01",
//            "2022-02",
//            "2022-03",
//            "2021-01",
//            "2021-02",
//            "2021-03",
//        );
//        arrPrint($arrThnBln);

        foreach ($arrThnBln as $tahun_ex) {
            $ner = new MdlRugilaba();
            $ner->addFilter("periode='bulanan'");
            $tmp[$tahun_ex] = $ner->fetchBalances2($tahun_ex);//$defaultDate
//            showLast_query("kuning");
        }
//        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");

        //region cabang
        $this->load->model("Mdls/" . "MdlCabang");
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }
        //endregion

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        $rekeningsKonsolidasiNilai = array();
        $rekeningsKonsolidasiKanan = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                foreach ($thn_ex_spec as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {

                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    //region data per-cabang
                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = 0;
                                    }
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_detail'] = $link_detail;
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k] = array();
                                    }
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }

                                    if (!isset($rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiNilai[$thn_ex][$k][$row->rekening]['link_detail'] = "";
                                    //endregion

                                    //region data konsolidasian total kanan
                                    if (!isset($rekeningsKonsolidasiKanan[$k])) {
                                        $rekeningsKonsolidasiKanan[$k] = array();
                                    }

//                                    if (!isset($rekeningsName[$k])) {
//                                        $rekeningsName[$k] = array();
//                                    }
//
//                                    if (!in_array($row->rekening, $rekException)) {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
//                                            $value = $value > 0 ? $value * -1 : $value;
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                            $value = $value < 0 ? $value * -1 : $value;
//                                        }
//                                    }
//                                    else {
//                                        if ($row->debet > 0) {
//                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
//                                        }
//                                        else {
//                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
//                                        }
//                                    }

                                    if (!isset($rekeningsKonsolidasiKanan[$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";

//                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                                    $link_detail = isset($accountChilds[$row->rekening]) ? "<span class='pull-right'><a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate&date1=$defaultDate' target='_blank'><span class='fa fa-clone'></span></a></span>" : "";

                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link'] = "";
                                    $rekeningsKonsolidasiKanan[$k][$row->rekening]['link_detail'] = "";
                                    //endregion
                                }
                            }
                        }
                    }
                }
            }
//            reset($dates);
//            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }
//arrPrintWebs($rekenings);
//arrPrintHijau($rekeningsName);
//arrPrintHijau($rekeningsKonsolidasiNilai);
//arrPrintHijau($rekeningsKonsolidasiKanan);
//mati_disini();
        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings[$arrThnBln[0]])) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
//arrPrintPink($categoryRLAll);
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
//            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
//                if (in_array($rek_key, $rekeningsName[$cat])) {
//                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
//                }
//            }
            foreach($categoryRLAll as $thn_xx => $categoryRLSpec){
                foreach ($categoryRLSpec[$cat] as $rek_key => $rekName) {
                    if (in_array($rek_key, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                    }
                }
            }
        }

        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $headerssKanan = array(
                "values" => "total(IDR)",
            );
            foreach ($arrThnBln as $ii) {
                $headersTahun[$ii] = $ii;
            }
            $rekeningSelected = $rekenings;
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
            $rekeningsKonsolidasiKananNilai["values"] = $rekeningsKonsolidasiKanan;
        }
        else {
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$tahun];
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai[$tahun];
        }
//arrPrintHijau($rekeningsKonsolidasiNilaiSelected);
//arrPrintPink($headerss);
//arrPrintPink($rekeningsNameNew);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi TTM ",
            "subTitle" => "Laporan Rugilaba Konsolidasi TTM ",
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "rekeningsKonsolidasiKanan" => isset($rekeningsKonsolidasiKananNilai) ? $rekeningsKonsolidasiKananNilai : array(),
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
//            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "headersTahun" => $headersTahun,
            "headersKanan" => isset($headerssKanan) ? $headerssKanan : array(),
//            "headersKanan" => array(),
        );
        $this->load->view("$views", $data);

    }

    public function viewPLConsolidated()
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

        $mode = url_segment(3);

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


        // $periode = "tahunan";
        // $date1 = date("Y-m-01");
        $date2 = date("Y-m-d");
        $dateNow = date("Y-m-d");
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateExp = explode("-", $dateNow);
//        arrPrint($dateExp);
        switch ($mode) {
            case "mtd":
                $periode = "bulanan";
                $date1 = date("Y-m-01");
                $mode_report = formatTanggal($date1, 'd') . " - " . formatTanggal($date2, 'd F Y');
                $tgl = $dateExp[2];
                $last_bulan = $bulan = $dateExp[1];
                $last_tahun = $tahun = $dateExp[0];
                $tahunLast = $dateExp[0];
                if ($bulan == 1) {
                    $last_bulan = 12;
                    $last_tahun = $dateExp[0] - 1;
                }
                else {
                    $last_bulan = $bulan - 1;
                }
                $tahunLast = "$last_tahun-$last_bulan";
                break;
            default:
                $periode = "tahunan";
                $date1 = date("Y-01-01");
                $mode_report = formatTanggal($date1, 'd F') . " - " . formatTanggal($date2, 'd F Y');
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0] - 1;
                break;
        }
//        $bulan = $dateExp[1];
//        $tahun = $dateExp[0];
//        $tahunLast = $dateExp[0] - 1;

        $resultRLByCabang = array();
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
                    $mts->addFilter("date(dtime)>='$date1'");
                    $mts->addFilter("date(dtime)<='$date2'");
                    $mts->addFilter("transaksi_id>'0'");
                    $arrMutasi[$rek] = $mts->fetchMoves($rek);
//                    cekLime($this->db->last_query());
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
            $tmpLastNeraca = $ner->fetchBalances($tahunLast);
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
            // arrPrintPink($tmpLastNeracaResult);
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

            $str = "<br><table rules='all' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<th>rekening || cabangID [$cabangID]</th>";
            $str .= "<th>debet</th>";
            $str .= "<th>kredit</th>";
            $str .= "</tr>";
            $total_debet = 0;
            $total_kredit = 0;
            foreach ($arrLajurNew as $rekening => $spec) {
                $total_debet += $spec['debet'];
                $total_kredit += $spec['kredit'];

                $str .= "<tr>";
                $str .= "<td style='text-align: left;'>$rekening</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                $str .= "</tr>";
            }
            $str .= "<tr>";
            $str .= "<td style='text-align: left;'>-</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
            $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
            $str .= "</tr>";

            $str .= "</table>";
            $str .= "<br>";
            if (isset($_GET['debuger']) && ($_GET['debuger'] == 1)) {
                echo $str;
            }
            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $arrLajurNew);
            $resultRL = $rl->execNoCut_view();
            $result_object = array();
            foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                $result_object[$ii] = (object)$rSpec;
            }
            $resultRLByCabang[$cabangID][] = $result_object;
        }
        // endregion rl year to date


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $rekException = array("rugilaba");

        $tmp = $resultRLByCabang;
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $cabID => $nerSpec) {
                foreach ($nerSpec as $rowSpec) {
                    foreach ($rowSpec as $row) {
                        //                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                        foreach ($categoryRL as $k => $catSpec) {
                            if (array_key_exists($row->rekening, $catSpec)) {
                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                if (!isset($rekenings[$k][$row->cabang_id])) {
                                    $rekenings[$k][$row->cabang_id] = array();
                                }
                                if (!isset($rekeningsName[$k])) {
                                    $rekeningsName[$k] = array();
                                }

                                if (!in_array($row->rekening, $rekException)) {
                                    if ($row->debet > 0) {
                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        $value = $value > 0 ? $value * -1 : $value;
                                        //                                        cekHere($row->rekening . " " . $row->debet . " -> $value");
                                    }
                                    else {
                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        $value = $value < 0 ? $value * -1 : $value;
                                    }
                                }
                                else {
                                    if ($row->debet > 0) {
                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                    }
                                    else {
                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                    }
                                }
                                $debett = $row->debet;
                                $kreditt = $row->kredit;
                                //cekHere($row->rekening . " debet( $debett ), kredit( $kreditt ), :: $value");
                                $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";

                                //                                if (isset($accountChilds[$row->rekening])) {
                                //                                    $link = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "'><span class='fa fa-clone'></span></a>";
                                //                                }
                                //                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                                if (($row->rekening == "efisiensi cabang") || ($row->rekening == "efisiensi biaya")) {
                                    // tembak cabang id solo yaitu 25
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Neraca/viewEfisiensiBiaya/bom?o=25&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";
                                }
                                else {
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";
                                }

                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                //                                    $rekeningsName[$k][$row->rekening] = $row->rekening;
                            }
                        }
                        //                        }
                    }
                }
            }
            //            reset($dates);
            //            $oldDate = key($dates);
        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }


        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings)) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }

        $oldDate = "2019-09";
        $defaultDate = "";
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu();
            // cekHere($title);
        }
        else {
            $title = "consolidated profit & loss report (year to date)";
        }
        // arrPrint($rekenings);
        $data = array(
            //            "mode" => "viewPL_consolidated",
            "mode" => "viewPLYearToDate_consolidated",
            "title" => "$title",
            "subTitle" => "$title : $mode_report",
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "values" => "balance(IDR)",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("finance", $data);

    }

    public function viewPLYearToDate_consolidated()
    {
        $pakai_ini = 0;
        if($pakai_ini == 1){
            // region rl year to date
            $this->load->model("Mdls/" . "MdlNeraca");
            $this->load->model("Mdls/" . "MdlNeracaLajur");
            $this->load->model("Coms/ComRugiLaba_cli");
            $this->load->model("Coms/ComNeraca_cli");
            $this->load->model("Coms/ComRekening_cli");
            $this->load->model("Mdls/" . "MdlCabang");

            $this->load->helper("he_mass_table");
            $this->load->helper("he_misc");


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
            $date1 = date("Y-01-01");
            $date2 = date("Y-m-d");
            $dateNow = date("Y-m-d");
            $dateTimeNow = date("Y-m-d H:i:s");
            $dateExp = explode("-", $dateNow);
            $bulan = $dateExp[1];
            $tahun = $dateExp[0];
            $tahunLast = $dateExp[0] - 1;


            $resultRLByCabang = array();
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
                        $mts->addFilter("date(dtime)>='$date1'");
                        $mts->addFilter("date(dtime)<='$date2'");
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
                $tmpLastNeraca = $ner->fetchBalances($tahunLast);

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

                $rl->setFilters2($filters2);
                $rl->setFilters($filters);
                $rl->pairNoCut_view($static, $arrLajurNew);
                $resultRL = $rl->execNoCut_view();
                $result_object = array();
                foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                    $result_object[$ii] = (object)$rSpec;
                }
                $resultRLByCabang[$cabangID][] = $result_object;
            }
            // endregion rl year to date
            $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
            $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
            $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
            $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
            $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
            $rekException = array("rugilaba");

            $tmp = $resultRLByCabang;
            $arrCabang = array();
            $categories = array();
            $rekenings = array();
            $rekeningsName = array();
            $rekeningsKonsolidasiNilai = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {

                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                            $value = $value > 0 ? $value * -1 : $value;
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }
                                    $debett = $row->debet;
                                    $kreditt = $row->kredit;

                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }

                                    //region data per-cabang
                                    if (!isset($rekenings[$k][$row->cabang_id])) {
                                        $rekenings[$k][$row->cabang_id] = array();
                                    }

                                    $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                    $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                    //endregion

                                    //region data konsolidasian
                                    if (!isset($rekeningsKonsolidasiNilai[$k])) {
                                        $rekeningsKonsolidasiNilai[$k] = array();
                                    }

                                    if (!isset($rekeningsKonsolidasiNilai[$k][$row->rekening]['values'])) {
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['values'] = 0;
                                    }
                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['rek_id'] = "";
                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['link'] = $link;
                                    $rekeningsKonsolidasiNilai[$k][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                    //endregion
                                }
                            }

                        }
                    }
                }
                //            reset($dates);
                //            $oldDate = key($dates);
            }
            $rekeningsName = array();
            if (sizeof($categoryRL) > 0) {
                foreach ($categoryRL as $l => $rlSpec) {
                    foreach ($rlSpec as $k_rek => $v_rek) {
                        $rekeningsName[$l][$k_rek] = $k_rek;
                    }
                }
            }


            $categoriesAll = array(1,
                2,
                3,
                4
            );
            $categories = array();
            $categoriesSubBottom = array();
            foreach ($categoriesAll as $ctr => $cat) {
                if (array_key_exists($cat, $rekenings)) {
                    $categories[] = $cat;
                    $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                }
            }
            $rekeningsNameNew = array();
            foreach ($categories as $cat) {
                foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                    if (in_array($rek_key, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                    }
                }
            }
        }
        else{
            $_GET['tm'] = 1;
            if ($_GET['tm'] == 1) {
                $this->load->model("Mdls/" . "MdlNeraca");
                $this->load->model("Mdls/" . "MdlNeracaLajur");
                $this->load->model("Coms/ComRugiLaba_cli");
                $this->load->model("Coms/ComNeraca_cli");
                $this->load->model("Coms/ComRekening_cli");
                $this->load->model("Mdls/" . "MdlCabang");

                $this->load->helper("he_mass_table");
                $this->load->helper("he_misc");


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
                $date1 = date("Y-01-01");
                $date2 = date("Y-m-d");
                $dateNow = date("Y-m-d");
                $dateTimeNow = date("Y-m-d H:i:s");
                $dateExp = explode("-", $dateNow);
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0] - 1;

                $resultRLByCabang = array();
                foreach ($arrCabangs as $cabangID => $cabangName) {
                    $static = array(
                        "static" => array(
                            "cabang_id" => $cabangID,
                            "dtime" => $dateTimeNow,
                            "fulldate" => $dateNow,
//                        "bln" => $bulan,
                            "thn" => $tahun,
                            "periode" => $periode,
                        ),
                    );
                    $filters = array(
                        "periode" => $periode,
                        "cabang_id" => $cabangID,
//                    "bln" => $bulan,
                        "thn" => $tahun,
                    );
                    $filters2 = array(
                        "periode=" => $periode,
                        "cabang_id=" => $cabangID,
//                    "date(dtime)<=" => $date2,
                        "thn" => $tahun,
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

                    $arrLajurNew = array();
//                foreach ($arrLajur as $rek => $spec) {
//                    if ($spec['debet'] < 0) {
//                        $spec['kredit'] = $spec['debet'] * -1;
//                        $spec['debet'] = 0;
//                    }
//                    if ($spec['kredit'] < 0) {
//                        $spec['debet'] = $spec['kredit'] * -1;
//                        $spec['kredit'] = 0;
//                    }
//                    if (!in_array($rek, $arrRekBlacklist)) {
//                        $arrLajurNew[$rek] = $spec;
//                    }
//                }
                    foreach ($tmp as $spec) {
                        $rek = $spec['rekening'];
                        if (!in_array($rek, $arrRekBlacklist)) {
                            $arrLajurNew[$rek] = $spec;
                        }
                    }
//arrPrintWebs($arrLajurNew);
                    $rl->setFilters2($filters2);
                    $rl->setFilters($filters);
                    $rl->pairNoCut_view($static, $arrLajurNew);
                    $resultRL = $rl->execNoCut_view();
                    $result_object = array();
                    foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                        $result_object[$ii] = (object)$rSpec;
                    }
//                arrPrintWebs($result_object);
                    $resultRLByCabang[$cabangID][] = $result_object;
                }

                $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
                $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
                $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
                $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
                $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
                $rekException = array("9010");
                $rekeningCoa = rekening_coa_he_accounting();
                $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
                $accountRekeningSort = rekening_coa_sort_he_accounting();
                $categoryRL_OLD = $categoryRL;
                $categoryRL = array();
                foreach ($categoryRL_OLD as $cat => $catSpec) {
                    foreach ($catSpec as $key => $val) {
                        if(isset($rekeningCoa[$key])){
                            $key_new = $rekeningCoa[$key];
                            $categoryRL[$cat][$key_new] = $val;
                        }
                    }
                }


                $tmp = $resultRLByCabang;
                $arrCabang = array();
                $categories = array();
                $rekenings = array();
                $rekeningsName = array();
                $rekeningsKonsolidasiNilai = array();
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $cabID => $nerSpec) {
                        foreach ($nerSpec as $rowSpec) {
                            foreach ($rowSpec as $row) {

                                foreach ($categoryRL as $k => $catSpec) {
                                    if (array_key_exists($row->rekening, $catSpec)) {
                                        $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";

                                        if (!in_array($row->rekening, $rekException)) {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                                $value = $value > 0 ? $value * -1 : $value;
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                                $value = $value < 0 ? $value * -1 : $value;
                                            }
                                        }
                                        else {
                                            if ($row->debet > 0) {
                                                $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                            }
                                            else {
                                                $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            }
                                        }
                                        $debett = $row->debet;
                                        $kreditt = $row->kredit;

                                        if (!isset($rekeningsName[$k])) {
                                            $rekeningsName[$k] = array();
                                        }

                                        //region data per-cabang
                                        if (!isset($rekenings[$k][$row->cabang_id])) {
                                            $rekenings[$k][$row->cabang_id] = array();
                                        }

                                        $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                        $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                        $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                        $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";

                                        $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                        $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                        $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                        //endregion

                                        //region data konsolidasian
                                        if (!isset($rekeningsKonsolidasiNilai[$k])) {
                                            $rekeningsKonsolidasiNilai[$k] = array();
                                        }

                                        if (!isset($rekeningsKonsolidasiNilai[$k][$row->rekening]['values'])) {
                                            $rekeningsKonsolidasiNilai[$k][$row->rekening]['values'] = 0;
                                        }
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['rek_id'] = "";
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['values'] += $value != null ? $value : 0;
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['link'] = "";

                                        $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['link'] = $link;
                                        $rekeningsKonsolidasiNilai[$k][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                        //endregion
                                    }
                                }

                            }
                        }
                    }
                    //            reset($dates);
                    //            $oldDate = key($dates);
                }
                $rekeningsName = array();
                if (sizeof($categoryRL) > 0) {
                    foreach ($categoryRL as $l => $rlSpec) {
                        foreach ($rlSpec as $k_rek => $v_rek) {
                            $rekeningsName[$l][$k_rek] = $k_rek;
                        }
                    }
                }


                $categoriesAll = array(1,
                    2,
                    3,
                    4
                );
                $categories = array();
                $categoriesSubBottom = array();
                foreach ($categoriesAll as $ctr => $cat) {
                    if (array_key_exists($cat, $rekenings)) {
                        $categories[] = $cat;
                        $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                    }
                }
                $rekeningsNameNew = array();
                foreach ($categories as $cat) {
                    foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                        if (in_array($rek_key, $rekeningsName[$cat])) {
                            $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                        }
                    }
                }
//                arrPrint($categories);
            }
        }




        $oldDate = "2019-09";
        $defaultDate = "";
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu();
            // cekHere($title);
        }
        else {
            $title = "consolidated profit & loss report (year to date)";
        }
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi_ytd";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
            );
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
        }
        else {
            $views_mode = $this->uri->segment(2);
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
//                "link_detail" => "",
                "link" => "",
            );
            $rekeningsKonsolidasiNilaiSelected = $rekeningsKonsolidasiNilai;
        }
//arrPrintHijau($rekeningsKonsolidasiNilaiSelected);
//        cekHere($views_mode);
        $data = array(
            "mode" => $views_mode,
            "title" => "$title",
            "subTitle" => "$title " . formatTanggal($date1, 'd F Y') . " - " . formatTanggal($date2, 'd F Y'),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "rekeningsKonsolidasiNilai" => $rekeningsKonsolidasiNilaiSelected,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
            "cabang" => array(),
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("$views", $data);

    }

    public function viewPLConsolidatedNewKomparasi()
    {

        // region rl year to date
        $this->load->model("Mdls/MdlNeraca");
        $this->load->model("Mdls/MdlNeracaLajur");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Coms/ComRekening_cli");
        $this->load->model("Mdls/MdlCabang");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        $this->load->library("Rekening");

        $accounts = $this->config->item("accountStructure");
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $rekException = array("rugilaba");

//        $no = 0;
        $arrAccounts = array();
        foreach ($accounts as $accountSpec) {
            foreach ($accountSpec as $account_rekening) {
                $rekening_replacer = str_replace(" ", "_", $account_rekening);
                $tabel_master = "__rek_master__" . $rekening_replacer;
                if ($this->db->table_exists($tabel_master)) {
                    $arrAccounts[$account_rekening] = $tabel_master;
                }
            }
        }
//        arrPrint($arrAccounts);


        $mode = url_segment(3);

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


        // $periode = "tahunan";
        // $date1 = date("Y-m-01");
        $date2 = date("Y-m-d");
        $dateNow = date("Y-m-d");
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateExp = explode("-", $dateNow);
//        arrPrint($dateExp);
        switch ($mode) {
            case "mtd":
                $periode = "bulanan";
                $date1 = date("Y-m-01");
                $mode_report = formatTanggal($date1, 'd') . " - " . formatTanggal($date2, 'd F Y');
                $tgl = $dateExp[2];
                $last_bulan = $bulan = $dateExp[1];
                $last_tahun = $tahun = $dateExp[0];
                $tahunLast = $dateExp[0];
                if ($bulan == 1) {
                    $last_bulan = 12;
                    $last_tahun = $dateExp[0] - 1;
                }
                else {
                    $last_bulan = $bulan - 1;
                }
                $tahunLast = "$last_tahun-$last_bulan";
                break;
            default:
//                $periode = "tahunan";
                $periode = "forever";
                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-01-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                $mode_report = formatTanggal($date1, 'd F') . " - " . formatTanggal($date2, 'd F Y');
                $tgl = $dateExp[2];
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0] - 1;
                break;
        }
//        $tgl = $dateExp[2];
//        $bulan = $dateExp[1];
//        $tahun = $dateExp[0];
//        $tahunLast = $dateExp[0] - 1;
        $fulldate = "$tahun-$bulan-$tgl";
        $fulldate_now = "$tahun-$bulan-$tgl";
        $fulldate_last = "$tahunLast-$bulan-$tgl";
        $arrFulldateSelect = array(
            "$tahun" => $fulldate_now,
            "$tahunLast" => $fulldate_last,
        );
//arrPrintPink($arrFulldateSelect);
//mati_disini(":: $fulldate_now :: $fulldate_last ::");

        $pakai_ini = 2;
        $resultRLByCabang = array();
        foreach($arrFulldateSelect as $tahuns => $fulldates){
            foreach ($arrCabangs as $cabangID => $cabangName) {
//                $explode = explode("-", $fulldates);
//                $thn_explode = $explode[0];
                $static = array(
                    "static" => array(
                        "cabang_id" => $cabangID,
                        "dtime" => $dateTimeNow,
                        "fulldate" => $dateNow,
                        "bln" => $bulan,
                        "thn" => $tahuns,
//                        "thn" => $tahun,
                        "periode" => $periode,
                    ),
                );
                $filters = array(
                    "periode" => $periode,
                    "cabang_id" => $cabangID,
                    "bln" => $bulan,
                    "thn" => $tahuns,
//                    "thn" => $tahun,
                );
                $filters2 = array(
                    "periode=" => $periode,
                    "cabang_id=" => $cabangID,
                    "date(dtime)<=" => $fulldates,
//                    "date(dtime)<=" => $date2,
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
                            $mts->addFilter("date(dtime)>='$date1'");
                            $mts->addFilter("date(dtime)<='$date2'");
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
                    $tmpLastNeraca = $ner->fetchBalances($tahunLast);

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

                    // arrPrintPink($tmpLastNeracaResult);
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

                    $str = "<table rules='all' style='border:1px solid black;'>";
                    $str .= "<tr>";
                    $str .= "<th>rekening || cabangID [$cabangID]</th>";
                    $str .= "<th>debet</th>";
                    $str .= "<th>kredit</th>";
                    $str .= "</tr>";
                    $total_debet = 0;
                    $total_kredit = 0;
                    foreach ($arrLajurNew as $rekening => $spec) {
                        $total_debet += $spec['debet'];
                        $total_kredit += $spec['kredit'];

                        $str .= "<tr>";
                        $str .= "<td style='text-align: left;'>$rekening</td>";
                        $str .= "<td style='text-align: right;'>" . number_format($spec['debet']) . "</td>";
                        $str .= "<td style='text-align: right;'>" . number_format($spec['kredit']) . "</td>";
                        $str .= "</tr>";
                    }
                    $str .= "<tr>";
                    $str .= "<td style='text-align: left;'>-</td>";
                    $str .= "<td style='text-align: right;'>" . number_format($total_debet) . "</td>";
                    $str .= "<td style='text-align: right;'>" . number_format($total_kredit) . "</td>";
                    $str .= "</tr>";

                    $str .= "</table>";
                    $str .= "<br>";
//                echo $str;


                    $rl->setFilters2($filters2);
                    $rl->setFilters($filters);
                    $rl->pairNoCut_view($static, $arrLajurNew);
                    $resultRL = $rl->execNoCut_view();
                    $result_object = array();
                    foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                        $result_object[$ii] = (object)$rSpec;
                    }
                    $resultRLByCabang[$cabangID][] = $result_object;
                }
                /* cabang_id
                 * rekening
                 * periode
                 * date/thn/bln/tgl
                 * */
                if ($pakai_ini == 2) {
                    $r = New Rekening();
                    switch ($mode) {
                        case "mtd":
                            $arrLajurNew = $r->saldoMonthToDate($cabangID, $periode, $fulldate);
                            break;
                        default:
//                        $arrLajurNew = $r->saldoYearToDate($cabangID, $periode, $fulldate);
//                            $arrLajurNew = $r->saldoForever($cabangID, $periode, $fulldates);
                            $fulldates1 = $tahuns . "-01-01";
                            $date_noww = date("Y-m-d");
                            $date_noww_ex = explode("-", $date_noww);
                            $bln_noww = $date_noww_ex[1];
                            $tgl_noww = $date_noww_ex[2];
                            $date1 = isset($_GET['date1']) ? $_GET['date1'] : $tahuns ."01-01";
                            $date2 = isset($_GET['date2']) ? $_GET['date2'] : $tahuns ."$bln_noww-$tgl_noww";
                            $date1_ex = explode("-", $date1);
                            $date2_ex = explode("-", $date2);
                            $date1_new = $tahuns ."-".$date1_ex[1]."-".$date1_ex[2];
                            $date2_new = $tahuns. "-".$date2_ex[1]."-".$date2_ex[2];
//                            $arrLajurNew = $r->saldoForeverRange($cabangID, $periode, $fulldates1, $fulldates);
                            $arrLajurNew = $r->saldoForeverRange($cabangID, $periode, $date1_new, $date2_new);
                            break;
                    }
                    $rl->setFilters2($filters2);
                    $rl->setFilters($filters);
                    $rl->pairNoCut_view($static, $arrLajurNew);
                    $resultRL = $rl->execNoCut_view();
                    $result_object = array();
                    foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                        $result_object[$ii] = (object)$rSpec;
                    }
                    $resultRLByCabang[$tahuns][$cabangID][] = $result_object;
                }

            }
        }

//arrPrintWebs($resultRLByCabang[2021]);
//mati_disini(":: $periode ::");
        // endregion rl year to date

        $tmp = $resultRLByCabang;
        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_exSpec){
                foreach ($thn_exSpec as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
                            foreach ($categoryRL as $k => $catSpec) {
                                if (array_key_exists($row->rekening, $catSpec)) {
                                    $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";
                                    if (!isset($rekeningsName[$k])) {
                                        $rekeningsName[$k] = array();
                                    }


                                    if (!in_array($row->rekening, $rekException)) {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                            $value = $value > 0 ? $value * -1 : $value;
                                            //                                        cekHere($row->rekening . " " . $row->debet . " -> $value");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                            $value = $value < 0 ? $value * -1 : $value;
                                        }
                                    }
                                    else {
                                        if ($row->debet > 0) {
                                            $value = detectRekByPosition($row->rekening, $row->debet, "debet");
                                        }
                                        else {
                                            $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
                                        }
                                    }
                                    $debett = $row->debet;
                                    $kreditt = $row->kredit;


                                    //region data per-cabang
                                    if (!isset($rekenings[$thn_ex][$k][$row->cabang_id])) {
                                        $rekenings[$thn_ex][$k][$row->cabang_id] = array();
                                    }
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = "";

                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$thn_ex][$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                    //endregion

                                }
                            }

                        }
                    }
                }
            }

        }
        $rekeningsName = array();
        if (sizeof($categoryRL) > 0) {
            foreach ($categoryRL as $l => $rlSpec) {
                foreach ($rlSpec as $k_rek => $v_rek) {
                    $rekeningsName[$l][$k_rek] = $k_rek;
                }
            }
        }

//mati_disini();

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();
        foreach ($categoriesAll as $ctr => $cat) {
            if (array_key_exists($cat, $rekenings[$tahun])) {
                $categories[] = $cat;
                $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
            }
        }
        $rekeningsNameNew = array();
        foreach ($categories as $cat) {
            foreach ($categoryRL[$cat] as $rek_key => $rekName) {
                if (in_array($rek_key, $rekeningsName[$cat])) {
                    $rekeningsNameNew[$cat][$rek_key] = $rek_key;
                }
            }
        }

        $oldDate = "2019-09";
        $defaultDate = "";
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu();
            // cekHere($title);
        }
        else {
            $title = "consolidated profit & loss report<br>ytd comparation";
        }
        // arrPrint($rekenings);
        $data = array(
            //            "mode" => "viewPL_consolidated",
            "mode" => "viewPLYearToDate_consolidatedKomparasi",
            "title" => "$title",
            "subTitle" => "$title : $mode_report",
            "categories" => $categories,
            "rekenings" => $rekenings,
//            "rekenings" => array(),
            "headers" => array(
                "values" => "balance(IDR)",
//                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) ."?",
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
            "mode_report" => $mode_report,
            "headerTahun" => $arrFulldateSelect,
            "rangeDate" => true,
            "date1" => isset($date1) ? $date1 : date("Y") . "-01-01",
            "date2" => isset($date2) ? $date2 : date("Y-m-d"),
            "minDate" => isset($minDate) ? $minDate : date("Y") . "-01-01",
            "maxDate" => isset($maxDate) ? $maxDate : date("Y-m-d"),
        );
        $this->load->view("finance", $data);

    }

    //-----------------------------------------------
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
        $previousMonth = previousMonth();
        $date1 = isset($_GET['date']) ? $_GET['date'] . "-01" : date("Y-m") . "-01";
        $date2 = isset($_GET['date']) ? $_GET['date'] . "-31" : date("Y-m") . "-31";
        $defaultDate = $dateNow = isset($_GET['date']) ? $_GET['date'] : date("Y-m");

        // membaca tabel setting cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
//cekHere(date("Y-m-t"));
//cekHere(date("2022-02-t"));
//cekHere(date(previousMonth()."-t"));
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
        $topHeader[72] = "kas dan setara kas awal periode ";
        $topHeader[73] = "kas dan setara kas akhir periode ";

        //---------------------
        $rekening = "1010010010";
//        $rekening = "kas";
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
                case "999":
                    // tembakan dulu karena tidak bisa relatif, jenis tr adjustment
                    $last_p_head_code = $midHeaderHeadCodeFlip['01'];
                    $next_p_head_code = $last_p_head_code + 2;
                    $midHeader['01'][$next_p_head_code] = "Adjustment";
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
//            arrPrintHijau($midHeaderHeadCodeFlip);
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_cashflow_monthly_konsolidasi";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = "cashflow";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Cashflow Konsolidasi",
            "subTitle" => "Laporan Cashflow Konsolidasi " . lgTranslateTime($defaultDate),
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

//        $data = array(
//            "mode" => "cashflow",
//            "title" => "undermaintenace",
//            "underMaintenance" => underMaintenance(),
//
//        );
        $this->load->view("$views", $data);

    }

    public function viewCashflowTahunan()
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
        $getyear = explode("-", $_GET['date'])[0];
        $tahun = $year = $getyear == date("Y") ? $getyear - 1 : $getyear;
        $prevYear = $year - 1;
        $date1 = isset($_GET['date']) ? $year . "-01-01" : date("Y") . "-01-01";
        $date2 = isset($_GET['date']) ? $year . "-12-31" : date("Y") . "-12-31";
        $prev_date1 = isset($_GET['date']) ? $prevYear . "-01-01" : date("Y") . "-01-01";
        $prev_date2 = isset($_GET['date']) ? $prevYear . "-12-31" : date("Y") . "-12-31";
        $defaultDate = $dateNow = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $arrTahun = array(
            "last_year" => $prevYear,
            "this_year" => $tahun,
        );

        //region settingan cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
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
        $topHeader[72] = "kas dan setara kas awal periode ";
        $topHeader[73] = "kas dan setara kas akhir periode ";
        //endregion

        //---------------------
        $rekening = "1010010010";
//        $rekening = "kas";
        //---------------------
        $cb = New MdlCabang();
        switch ($_GET['c']) {
            case "cabang":
                $cb->addFilter("id=" . my_cabang_id());
                break;
            case "konsolidasi":
                break;
        }
        $cbTmp = $cb->lookupAll()->result();
//        arrPrintHijau($cbTmp);
//        showLast_query("biru");
//        mati_disini();
        $saldoAwal = array();
        foreach ($cbTmp as $cbSpec) {
            $cbID = $cbSpec->id;
            foreach ($arrTahun as $tahun_ex) {
                $date1 = $tahun_ex . "-01-01";
                $date2 = $tahun_ex . "-12-31";

                $cr = New ComRekening();
                $cr->addFilter("cabang_id=$cbID");
                $cr->addFilter("fulldate>=$date1");
                $cr->addFilter("fulldate<=$date2");
                $cr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
                $crCbTmp = $cr->fetchMoves($rekening);
//                showLast_query("biru");
//            cekHere($crCbTmp[0]->debet_awal . " :: " . $crCbTmp[0]->cabang_id);
                $debet_awal = isset($crCbTmp[0]->debet_awal) ? $crCbTmp[0]->debet_awal : 0;
                if (!isset($saldoAwal[$tahun_ex])) {
                    $saldoAwal[$tahun_ex] = 0;
                }
                $saldoAwal[$tahun_ex] += $debet_awal;

            }

        }
        //---------------------
//arrPrintPink($saldoAwal);
//mati_disini();
        foreach ($arrTahun as $tahun_ex) {
            $date1 = $tahun_ex . "-01-01";
            $date2 = $tahun_ex . "-12-31";
            $cr = New ComRekening();
            $cr->addFilter("fulldate>=$date1");
            $cr->addFilter("fulldate<=$date2");
            switch ($_GET['c']) {
                case "cabang":
                    $cr->addFilter("cabang_id=" . my_cabang_id());
                    break;
                case "konsolidasi":
                    break;
            }
            $crTmp[$tahun_ex] = $cr->fetchMoves($rekening);
//            showLast_query("biru");
        }
//arrPrintHijau($crTmp);
//mati_disini();
        $totalDebet = array();
        $totalKredit = array();
        $data_rekening = array();
        $data_rekening_jenisTr = array();
        $noGroup = array();
        $trInGroup = array();
        $detailTransaksiMutasi = array();
        foreach ($crTmp as $thn_ex => $thn_ex_spec) {
            foreach ($thn_ex_spec as $crTmpSpec) {
                $jenis = $crTmpSpec->jenis;
                $trID = $crTmpSpec->transaksi_id;
                //----------------
                $subFolder = isset($isiData[$jenis]) ? $isiData[$jenis] : 0;
                if (!isset($totalDebet[$thn_ex])) {
                    $totalDebet[$thn_ex] = 0;
                }
                if (!isset($totalKredit[$thn_ex])) {
                    $totalKredit[$thn_ex] = 0;
                }
                $totalDebet[$thn_ex] += $crTmpSpec->debet;
                $totalKredit[$thn_ex] += $crTmpSpec->kredit;
                //----------------
                if (!isset($data_rekening[$thn_ex][$subFolder]["debet"])) {
                    $data_rekening[$thn_ex][$subFolder]["debet"] = 0;
                }
                if (!isset($data_rekening[$thn_ex][$subFolder]["kredit"])) {
                    $data_rekening[$thn_ex][$subFolder]["kredit"] = 0;
                }
                $data_rekening[$thn_ex][$subFolder]["debet"] += $crTmpSpec->debet;
                $data_rekening[$thn_ex][$subFolder]["kredit"] += ($crTmpSpec->kredit);
                //----------------
                if (!isset($data_rekening_jenisTr[$thn_ex][$jenis]["debet"])) {
                    $data_rekening_jenisTr[$thn_ex][$jenis]["debet"] = 0;
                }
                if (!isset($data_rekening_jenisTr[$thn_ex][$jenis]["kredit"])) {
                    $data_rekening_jenisTr[$thn_ex][$jenis]["kredit"] = 0;
                }
                $data_rekening_jenisTr[$thn_ex][$jenis]["debet"] += $crTmpSpec->debet;
                $data_rekening_jenisTr[$thn_ex][$jenis]["kredit"] += ($crTmpSpec->kredit);
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
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["debet"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["debet"] = 0;
                        }
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["kredit"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["kredit"] = 0;
                        }
                        $data_rekening[$thn_ex][$next_p_head_code]["debet"] += $crTmpSpec->debet;
                        $data_rekening[$thn_ex][$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);

                        break;
                    case "999":
                        // tembakan dulu karena tidak bisa relatif, jenis tr adjustment
                        $last_p_head_code = $midHeaderHeadCodeFlip['01'];
                        $next_p_head_code = $last_p_head_code + 2;
                        $midHeader['01'][$next_p_head_code] = "Adjustment";
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["debet"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["debet"] = 0;
                        }
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["kredit"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["kredit"] = 0;
                        }
                        $data_rekening[$thn_ex][$next_p_head_code]["debet"] += $crTmpSpec->debet;
                        $data_rekening[$thn_ex][$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);
                        break;

                }
//            arrPrintHijau($midHeaderHeadCodeFlip);
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

            $kenaikanKas = $totalDebet[$thn_ex] - $totalKredit[$thn_ex];
            $topHeaderIsi[$thn_ex][71] = $kenaikanKas;
            $topHeaderIsi[$thn_ex][72] = $saldoAwal[$thn_ex];
            $topHeaderIsi[$thn_ex][73] = $saldoAwal[$thn_ex] + $totalDebet[$thn_ex] - $totalKredit[$thn_ex];
        }


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
                if (is_array($main)) {
                    foreach ($main as $key => $val) {
                        $new_key = "m_" . $key;
                        $dataMainRegByID[$regTrID][$new_key] = $val;
                    }
                }
                else {

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
        foreach ($data_rekening as $thn_ex => $data_spec) {
            foreach ($data_spec as $ii => $spec) {
                $netto = $spec["debet"] - $spec["kredit"];
                $data_rekening_new[$thn_ex][$ii]["values"] = $netto;
            }
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
//arrPrintHijau($topHeaderSummary);
//cekHere("default date: $defaultDate");
        $oldDate = "2019-08";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_cashflow_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "-",
//                "link" => "",
            );
            $headersTahun = array(
                "$tahun" => "$tahun",
                "$prevYear" => "$prevYear",
            );
            $topHeaderIsi_selected = $topHeaderIsi;// ada tahun
            $saldoAwal_selected = $saldoAwal;
            $kenaikanKas_selected = $kenaikanKas;
        }
        else {
            $views_mode = "cashflow";
            $views = "finance";
            $headerss = array(
                "values" => "-",
//                "link" => "",
            );
            $headersTahun = array();
            $topHeaderIsi_selected = $topHeaderIsi[$tahun];// ada tahun
            $saldoAwal_selected = $saldoAwal[$tahun];
            $kenaikanKas_selected = $kenaikanKas[$tahun];
        }
        switch ($_GET['c']) {
            case "cabang":
                $title = "Laporan Cashflow ";
                $subTitle = "Laporan Cashflow " . ($defaultDate);
                break;
            case "konsolidasi":
                $title = "Laporan Cashflow Konsolidasi";
                $subTitle = "Laporan Cashflow Konsolidasi " . ($defaultDate);
                break;
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => $title,
            "subTitle" => $subTitle,
            "categories" => $topHeader,
            "rekenings" => $midHeader,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => isset($categoryRLBottom) ? $categoryRLBottom : array(),
            "rekeningsName" => isset($rekeningsNameNew) ? $rekeningsNameNew : array(),
            "rekeningsNameAlias" => isset($accountAlias) ? $accountAlias : array(),
            "dateSelector" => true,
            "rekeningBlacklist" => isset($rekException) ? $rekException : array(),
            "dataRekening" => isset($data_rekening_new) ? $data_rekening_new : array(),
            "topHeaderIsi" => $topHeaderIsi_selected,// ada tahun
            "topHeaderSummary" => $topHeaderSummary,// ada tahun
            "saldoAwal" => $saldoAwal_selected,
            "selisihKas" => $kenaikanKas_selected,
            "headersTahun" => $headersTahun,
//            "buttonMode" => array(
//                "enabled" => true,
//                "label" => "laporan rugilaba (internal)",
//                "link" => base_url() . get_class($this) . "/viewPLKoreksi",
//            ),
        );

        $this->load->view("$views", $data);

    }

    public function viewCashflowYtd()
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
        $previousMonth = previousMonth();
        $getyear = explode("-", $_GET['date'])[0];
        $tahun = $year = $getyear == date("Y") ? $getyear : $getyear;
        $date1 = isset($_GET['date']) ? $year . "-01-01" : date("Y") . "-01-01";
        $date2 = isset($_GET['date']) ? $year . "-12-31" : date("Y") . "-12-31";
        $defaultDate = $dateNow = isset($_GET['date']) ? $_GET['date'] : date("Y-m");

        // membaca tabel setting cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
//cekHere(date("Y-m-t"));
//cekHere(date("2022-02-t"));
//cekHere(date(previousMonth()."-t"));
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
        $topHeader[72] = "kas dan setara kas awal periode ";
        $topHeader[73] = "kas dan setara kas akhir periode ";

        //---------------------
        $rekening = "1010010010";
//        $rekening = "kas";
        //---------------------
        $cb = New MdlCabang();
        switch ($_GET['c']) {
            case "cabang":
                $cb->addFilter("id=" . my_cabang_id());
                break;
            case "konsolidasi":
                break;
        }
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
        switch ($_GET['c']) {
            case "cabang":
                $cr->addFilter("cabang_id=" . my_cabang_id());
                break;
            case "konsolidasi":
                break;
        }
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
                case "999":
                    // tembakan dulu karena tidak bisa relatif, jenis tr adjustment
                    $last_p_head_code = $midHeaderHeadCodeFlip['01'];
                    $next_p_head_code = $last_p_head_code + 2;
                    $midHeader['01'][$next_p_head_code] = "Adjustment";
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
//            arrPrintHijau($midHeaderHeadCodeFlip);
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
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_cashflow_monthly_konsolidasi";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
            );
        }
        else {
            $views_mode = "cashflow";
            $views = "finance";
            $headerss = array(
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            );
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Cashflow Konsolidasi Year to Date",
//            "subTitle" => "Laporan Cashflow Konsolidasi Year to Date " . lgTranslateTime($defaultDate),
            "subTitle" => "Laporan Cashflow Konsolidasi Year to Date " . formatTanggal(date("Y-m-d"), 'd F Y'),
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

//        $data = array(
//            "mode" => "cashflow",
//            "title" => "undermaintenace",
//            "underMaintenance" => underMaintenance(),
//
//        );
        $this->load->view("$views", $data);

    }

    public function viewCashflowTriwulan()
    {
//        arrPrintPink($_GET);
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
//        $getyear = explode("-", $_GET['date'])[0];
        $getyear = explode("-", $_GET['date_start'])[0];
//        $tahun = $year = $getyear == date("Y") ? $getyear - 1 : $getyear;
        $tahun = $year = $getyear;
        $prevYear = $tahun - 1;
        $date1 = isset($_GET['date']) ? $year . "-01-01" : date("Y") . "-01-01";
        $date2 = isset($_GET['date']) ? $year . "-12-31" : date("Y") . "-12-31";
        $prev_date1 = isset($_GET['date']) ? $prevYear . "-01-01" : date("Y") . "-01-01";
        $prev_date2 = isset($_GET['date']) ? $prevYear . "-12-31" : date("Y") . "-12-31";
        $defaultDate = $dateNow = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $arrTahun = array(
            "last_year" => $prevYear,
            "this_year" => $tahun,
        );
        $arrTahunRange = array(
            "start" => array(
                0 => $_GET['date_start'],
                1 => $_GET['date_start_prev'],
            ),
            "stop" => array(
                0 => $_GET['date_stop'],
                1 => $_GET['date_stop_prev'],
            ),
        );


        //region settingan cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
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
        $topHeader[72] = "kas dan setara kas awal periode ";
        $topHeader[73] = "kas dan setara kas akhir periode ";
        //endregion

        //---------------------
        $rekening = "1010010010";
//        $rekening = "kas";
        //---------------------
        $cb = New MdlCabang();
        switch ($_GET['c']) {
            case "cabang":
                $cb->addFilter("id=" . my_cabang_id());
                break;
            case "konsolidasi":
                break;
        }
        $cbTmp = $cb->lookupAll()->result();
//        arrPrintHijau($cbTmp);
//        showLast_query("biru");
//        mati_disini();
        $saldoAwal = array();
        foreach ($cbTmp as $cbSpec) {
            $cbID = $cbSpec->id;
            foreach ($arrTahunRange['start'] as $key => $date_start) {
                $date_stop = $arrTahunRange['stop'][$key];
                $date1 = $date_start;
                $date2 = $date_stop;
                $cr = New ComRekening();
                $cr->addFilter("cabang_id=$cbID");
                $cr->addFilter("fulldate>=$date1");
                $cr->addFilter("fulldate<=$date2");
                $cr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
                $crCbTmp = $cr->fetchMoves($rekening);
//                showLast_query("biru");

                $date_explode = explode("-", $date1);
                $tahun_ex_1 = $date_explode[0];
                $debet_awal = isset($crCbTmp[0]->debet_awal) ? $crCbTmp[0]->debet_awal : 0;
                if (!isset($saldoAwal[$tahun_ex_1])) {
                    $saldoAwal[$tahun_ex_1] = 0;
                }
                $saldoAwal[$tahun_ex_1] += $debet_awal;

            }

        }
        //---------------------
//arrPrintPink($saldoAwal);
//mati_disini();
        foreach ($arrTahunRange['start'] as $key => $date_start) {
            $date_stop = $arrTahunRange['stop'][$key];
            $date1 = $date_start;
            $date2 = $date_stop;
            $cr = New ComRekening();
            $cr->addFilter("fulldate>=$date1");
            $cr->addFilter("fulldate<=$date2");
            switch ($_GET['c']) {
                case "cabang":
                    $cr->addFilter("cabang_id=" . my_cabang_id());
                    break;
                case "konsolidasi":
                    break;
            }

            $date_explode = explode("-", $date1);
            $tahun_ex_1 = $date_explode[0];
            $debet_awal = isset($crCbTmp[0]->debet_awal) ? $crCbTmp[0]->debet_awal : 0;
            $crTmp[$tahun_ex_1] = $cr->fetchMoves($rekening);
//            showLast_query("kuning");
        }
//arrPrintHijau($crTmp);
//mati_disini();
        $totalDebet = array();
        $totalKredit = array();
        $data_rekening = array();
        $data_rekening_jenisTr = array();
        $noGroup = array();
        $trInGroup = array();
        $detailTransaksiMutasi = array();
        foreach ($crTmp as $thn_ex => $thn_ex_spec) {
            foreach ($thn_ex_spec as $crTmpSpec) {
                $jenis = $crTmpSpec->jenis;
                $trID = $crTmpSpec->transaksi_id;
                //----------------
                $subFolder = isset($isiData[$jenis]) ? $isiData[$jenis] : 0;
                if (!isset($totalDebet[$thn_ex])) {
                    $totalDebet[$thn_ex] = 0;
                }
                if (!isset($totalKredit[$thn_ex])) {
                    $totalKredit[$thn_ex] = 0;
                }
                $totalDebet[$thn_ex] += $crTmpSpec->debet;
                $totalKredit[$thn_ex] += $crTmpSpec->kredit;
                //----------------
                if (!isset($data_rekening[$thn_ex][$subFolder]["debet"])) {
                    $data_rekening[$thn_ex][$subFolder]["debet"] = 0;
                }
                if (!isset($data_rekening[$thn_ex][$subFolder]["kredit"])) {
                    $data_rekening[$thn_ex][$subFolder]["kredit"] = 0;
                }
                $data_rekening[$thn_ex][$subFolder]["debet"] += $crTmpSpec->debet;
                $data_rekening[$thn_ex][$subFolder]["kredit"] += ($crTmpSpec->kredit);
                //----------------
                if (!isset($data_rekening_jenisTr[$thn_ex][$jenis]["debet"])) {
                    $data_rekening_jenisTr[$thn_ex][$jenis]["debet"] = 0;
                }
                if (!isset($data_rekening_jenisTr[$thn_ex][$jenis]["kredit"])) {
                    $data_rekening_jenisTr[$thn_ex][$jenis]["kredit"] = 0;
                }
                $data_rekening_jenisTr[$thn_ex][$jenis]["debet"] += $crTmpSpec->debet;
                $data_rekening_jenisTr[$thn_ex][$jenis]["kredit"] += ($crTmpSpec->kredit);
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
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["debet"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["debet"] = 0;
                        }
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["kredit"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["kredit"] = 0;
                        }
                        $data_rekening[$thn_ex][$next_p_head_code]["debet"] += $crTmpSpec->debet;
                        $data_rekening[$thn_ex][$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);

                        break;
                    case "999":
                        // tembakan dulu karena tidak bisa relatif, jenis tr adjustment
                        $last_p_head_code = $midHeaderHeadCodeFlip['01'];
                        $next_p_head_code = $last_p_head_code + 2;
                        $midHeader['01'][$next_p_head_code] = "Adjustment";
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["debet"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["debet"] = 0;
                        }
                        if (!isset($data_rekening[$thn_ex][$next_p_head_code]["kredit"])) {
                            $data_rekening[$thn_ex][$next_p_head_code]["kredit"] = 0;
                        }
                        $data_rekening[$thn_ex][$next_p_head_code]["debet"] += $crTmpSpec->debet;
                        $data_rekening[$thn_ex][$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);
                        break;

                }
//            arrPrintHijau($midHeaderHeadCodeFlip);
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

            $kenaikanKas = $totalDebet[$thn_ex] - $totalKredit[$thn_ex];
            $topHeaderIsi[$thn_ex][71] = $kenaikanKas;
            $topHeaderIsi[$thn_ex][72] = $saldoAwal[$thn_ex];
            $topHeaderIsi[$thn_ex][73] = $saldoAwal[$thn_ex] + $totalDebet[$thn_ex] - $totalKredit[$thn_ex];
        }


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
                if (is_array($main)) {
                    foreach ($main as $key => $val) {
                        $new_key = "m_" . $key;
                        $dataMainRegByID[$regTrID][$new_key] = $val;
                    }
                }
                else {

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
        foreach ($data_rekening as $thn_ex => $data_spec) {
            foreach ($data_spec as $ii => $spec) {
                $netto = $spec["debet"] - $spec["kredit"];
                $data_rekening_new[$thn_ex][$ii]["values"] = $netto;
            }
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
//arrPrintHijau($topHeaderSummary);
//cekHere("default date: $defaultDate");
        $oldDate = "2019-08";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_cashflow_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "-",
//                "link" => "",
            );
            $headersTahun = array(
                "$tahun" => "$tahun",
                "$prevYear" => "$prevYear",
            );
            $headersTahunCashflow = array(
                "$tahun" => array(
                    $arrTahunRange['start'][0],
                    $arrTahunRange['stop'][0],
                ),
                "$prevYear" => array(
                    $arrTahunRange['start'][1],
                    $arrTahunRange['stop'][1],
                ),
            );
            $topHeaderIsi_selected = $topHeaderIsi;// ada tahun
            $saldoAwal_selected = $saldoAwal;
            $kenaikanKas_selected = $kenaikanKas;
        }
        else {
            $views_mode = "cashflow";
            $views = "finance";
            $headerss = array(
                "values" => "-",
//                "link" => "",
            );
            $headersTahun = array();
            $topHeaderIsi_selected = $topHeaderIsi[$tahun];// ada tahun
            $saldoAwal_selected = $saldoAwal[$tahun];
            $kenaikanKas_selected = $kenaikanKas[$tahun];
        }
        switch ($_GET['c']) {
            case "cabang":
                $title = "Laporan Cashflow ";
                $subTitle = "Laporan Cashflow " . ($arrTahunRange['start'][0] . " - " . $arrTahunRange['stop'][0]);
                break;
            case "konsolidasi":
                $title = "Laporan Cashflow Konsolidasi";
                $subTitle = "Laporan Cashflow Konsolidasi " . ($arrTahunRange['start'][0] . " - " . $arrTahunRange['stop'][0]);
                break;
        }
        $data = array(
            "mode" => "$views_mode",
            "title" => $title,
            "subTitle" => $subTitle,
            "categories" => $topHeader,
            "rekenings" => $midHeader,
            "headers" => $headerss,
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => isset($categoryRLBottom) ? $categoryRLBottom : array(),
            "rekeningsName" => isset($rekeningsNameNew) ? $rekeningsNameNew : array(),
            "rekeningsNameAlias" => isset($accountAlias) ? $accountAlias : array(),
            "dateSelector" => true,
            "rekeningBlacklist" => isset($rekException) ? $rekException : array(),
            "dataRekening" => isset($data_rekening_new) ? $data_rekening_new : array(),
            "topHeaderIsi" => $topHeaderIsi_selected,// ada tahun
            "topHeaderSummary" => $topHeaderSummary,// ada tahun
            "saldoAwal" => $saldoAwal_selected,
            "selisihKas" => $kenaikanKas_selected,
            "headersTahun" => $headersTahun,
            "date_start" => $arrTahunRange['start'][0],
            "date_stop" => $arrTahunRange['stop'][0],
            "prev_date_start" => $arrTahunRange['start'][1],
            "prev_date_stop" => $arrTahunRange['stop'][1],
            "dateDetailCashflow" => $headersTahunCashflow,

//            "buttonMode" => array(
//                "enabled" => true,
//                "label" => "laporan rugilaba (internal)",
//                "link" => base_url() . get_class($this) . "/viewPLKoreksi",
//            ),
        );

        $this->load->view("$views", $data);

    }

    public function detailCashflow()
    {
//        arrPrintPink($_REQUEST);
//        arrPrintPink(url_segment());
        $getDate = $_REQUEST["date"];
        $getDate1 = isset($_REQUEST["date1"]) ? $_REQUEST["date1"] : "";
        $getDate2 = isset($_REQUEST["date2"]) ? $_REQUEST["date2"] : "";
        $subGroup = url_segment()[3];
        $this->load->model("Mdls/MdlCashFlowBuilder");
        $cf = New MdlCashFlowBuilder();
        $cfTmp = $cf->getCashflow($getDate, $getDate1, $getDate2);
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
            "mode" => "lapKeuangan",
            "title" => "Laporan Keuangan $periode ",
            "subTitle" => "Laporan Keuangan per-" . ($tahun),
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,
            "neraca" => base_url() . "laporankeuangan/" . "Keuangan/viewNeracaTahunan?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "laporankeuangan/" . "Keuangan/viewPLTahunan?mode=lapkeuangan" . $getDate,
            "cashflow" => base_url() . "laporankeuangan/" . "Keuangan/viewCashflowTahunan?mode=lapkeuangan&c=cabang" . $getDate,
            "periode" => "tahunan",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganYtd()
    {

        $periode = "tahunan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0] == date("Y") ? $defaultDate_ex[0] - 1 : $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];


        $oldDate = "2019-09";
        $getDate = "&date=$tahun";

        $data = array(
            "mode" => "lapKeuangan",
            "title" => "Laporan Keuangan Year to Date ",
            "subTitle" => "Laporan Keuangan Year to Date " . ($tahun),
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,
            "neraca" => base_url() . "laporankeuangan/" . "Keuangan/viewNeracaYearToDate?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "laporankeuangan/" . "Keuangan/viewPLYearToDate?mode=lapkeuangan" . $getDate,
//            "cashflow" => base_url() . "laporankeuangan/" . "Keuangan/viewCashflowYtd?mode=lapkeuangan&c=cabang" . $getDate,
            "periode" => "none",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganTriwulan()
    {
        $periode = "tahunan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
//        $tahun = $defaultDate_ex[0] == date("Y") ? $defaultDate_ex[0] - 1 : $defaultDate_ex[0];
        $tahun = $defaultDate_ex[0];
        $prev_tahun = $defaultDate_ex[0] - 1;
        $bulan = $defaultDate_ex[1];

//        arrPrintPink($_GET);
        $arrQuarter = array(
            1 => array(
                "bulan" => array("01", "02", "03"),
                "neraca" => "03",
                "label" => "Q1",
            ),
            2 => array(
                "bulan" => array("04", "05", "06"),
                "neraca" => "06",
                "label" => "Q2",
            ),
            3 => array(
                "bulan" => array("07", "08", "09"),
                "neraca" => "09",
                "label" => "Q3",
            ),
            4 => array(
                "bulan" => array("10", "11", "12"),
                "neraca" => "12",
                "label" => "Q4",
            ),
        );
        $date_start = "$tahun-" . $arrQuarter[$_GET['quarter']]['bulan'][0] . "-01";
        $date_stop = "$tahun-" . $arrQuarter[$_GET['quarter']]['bulan'][2] . "-31";
        $date_start_prev = "$prev_tahun-" . $arrQuarter[$_GET['quarter']]['bulan'][0] . "-01";
        $date_stop_prev = "$prev_tahun-" . $arrQuarter[$_GET['quarter']]['bulan'][2] . "-31";
        $label = $arrQuarter[$_GET['quarter']]['label'];
        $enBulan = blobEncode($arrQuarter[$_GET['quarter']]['bulan']);
//cekHere("$date_start, $date_stop, $date_start_prev, $date_stop_prev");
        $oldDate = "2019-09";
        $getDateNeraca = "&date=$tahun-" . $arrQuarter[$_GET['quarter']]['neraca'] . "&label=$label";
        $getDateRL = "&date=$tahun&enbln=$enBulan&label=$label";
        $getDate = "&date=$tahun";
        $getDateCf = "&date_start=$date_start&date_stop=$date_stop";
        $getDateCfPrev = "&date_start_prev=$date_start_prev&date_stop_prev=$date_stop_prev&label=$label";
//mati_disini();
//cekHere($getDateNeraca);
        $data = array(
            "mode" => "lapKeuangan",
            "title" => "Laporan Keuangan $label ($date_start - $date_stop)",
            "subTitle" => "Laporan Keuangan $label ($date_start - $date_stop)",
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(3),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,

            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeracaTriwulan?mode=lapkeuangan" . $getDateNeraca,
            "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPLTriwulan?mode=lapkeuangan" . $getDateRL,
            "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowTriwulan?mode=lapkeuangan&c=cabang" . $getDate . $getDateCf . $getDateCfPrev,
            "periode" => "triwulan",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganTtm()
    {
        $periode = "year to date";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];


//        $date_now = date("Y-m");
//        $arrMonths = array();
//        for ($i = 1; $i <= 12; $i++) {
//            $tes = $i * -1;
//            $previousMonth = date('Y-m', strtotime($tes . ' month'));
//            $arrMonths[] = $previousMonth;
//        }
//        arrPrintHijau($arrMonths);
//        mati_disini(":: $date_now :: $previousMonth ::");

        $oldDate = "2019-09";
        $getDate = "&date=$defaultDate";

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan TTM ",
            "subTitle" => "Laporan Keuangan TTM ",
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(3),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,

            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeracaTtm?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPLTtm?mode=lapkeuangan" . $getDate,
//            "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowYtd?mode=lapkeuangan&c=konsolidasi" . $getDate,
            "periode" => "none",
        );
        $this->load->view("finance", $data);
    }
    //-----------------------------------------------

    //-----------------------------------------------
    public function laporanKeuanganKonsolidasiMonthly()
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

    public function laporanKeuanganKonsolidasiYearly()
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(2),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,
            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeraca_consolidatedTahunan_lap?mode=lapkeuangan" . $getDate,
            // "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPL_consolidatedTahunan?mode=lapkeuangan" . $getDate,
            // "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowTahunan?mode=lapkeuangan&c=konsolidasi" . $getDate,
            "periode" => "tahunan",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganKonsolidasiYtd()
    {

        $periode = "year to date";
        $defaultDate = date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
//        $bulan = $defaultDate_ex[1];


        $oldDate = "2019-09";
        $getDate = "&date=$tahun";

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan Konsolidasi YTD ",
            "subTitle" => "Laporan Keuangan Konsolidasi YTD " . formatTanggal(date("Y-m-d"), 'd F Y'),
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
            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeracaYearToDate_consolidated?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPLYearToDate_consolidated?mode=lapkeuangan" . $getDate,
            "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowYtd?mode=lapkeuangan&c=konsolidasi" . $getDate,
            "periode" => "ytd",
        );
        $this->load->view("finance", $data);

    }

    public function laporanKeuanganKonsolidasiTtm()
    {
        $periode = "year to date";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];


//        $date_now = date("Y-m");
//        $arrMonths = array();
//        for ($i = 1; $i <= 12; $i++) {
//            $tes = $i * -1;
//            $previousMonth = date('Y-m', strtotime($tes . ' month'));
//            $arrMonths[] = $previousMonth;
//        }
//        arrPrintHijau($arrMonths);
//        mati_disini(":: $date_now :: $previousMonth ::");

        $oldDate = "2019-09";
        $getDate = "&date=$defaultDate";

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan Konsolidasi TTM ",
            "subTitle" => "Laporan Keuangan Konsolidasi TTM ",
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(3),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,

            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeraca_consolidatedTtm_lap?mode=lapkeuangan" . $getDate,
            "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPL_consolidatedTtm?mode=lapkeuangan" . $getDate,
//            "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowYtd?mode=lapkeuangan&c=konsolidasi" . $getDate,
            "periode" => "none",
        );
        $this->load->view("finance", $data);
    }

    public function laporanKeuanganKonsolidasiTriwulan()
    {
        $periode = "tahunan";
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y");
        $defaultDate_ex = explode("-", $defaultDate);
//        $tahun = $defaultDate_ex[0] == date("Y") ? $defaultDate_ex[0] - 1 : $defaultDate_ex[0];
        $tahun = $defaultDate_ex[0];
        $prev_tahun = $defaultDate_ex[0] - 1;
        $bulan = $defaultDate_ex[1];
        $getQuarter = isset($_GET['quarter']) ? $_GET['quarter'] : 1;

//        arrPrintPink($_GET);
        $arrQuarter = array(
            1 => array(
                "bulan" => array("01", "02", "03"),
                "neraca" => "03",
                "label" => "Q1",
            ),
            2 => array(
                "bulan" => array("04", "05", "06"),
                "neraca" => "06",
                "label" => "Q2",
            ),
            3 => array(
                "bulan" => array("07", "08", "09"),
                "neraca" => "09",
                "label" => "Q3",
            ),
            4 => array(
                "bulan" => array("10", "11", "12"),
                "neraca" => "12",
                "label" => "Q4",
            ),
        );
        $date_start = "$tahun-" . $arrQuarter[$getQuarter]['bulan'][0] . "-01";
        $date_stop = "$tahun-" . $arrQuarter[$getQuarter]['bulan'][2] . "-31";
        $date_start_prev = "$prev_tahun-" . $arrQuarter[$getQuarter]['bulan'][0] . "-01";
        $date_stop_prev = "$prev_tahun-" . $arrQuarter[$getQuarter]['bulan'][2] . "-31";
        $label = $arrQuarter[$getQuarter]['label'];
        $enBulan = blobEncode($arrQuarter[$getQuarter]['bulan']);
//cekHere("$date_start, $date_stop, $date_start_prev, $date_stop_prev");

        $oldDate = "2019-09";
        $getDateNeraca = "&date=$tahun-" . $arrQuarter[$getQuarter]['neraca'] . "&label=$label";
        $getDateRL = "&date=$tahun&enbln=$enBulan&label=$label";
        $getDate = "&date=$tahun";
        $getDateCf = "&date_start=$date_start&date_stop=$date_stop";
        $getDateCfPrev = "&date_start_prev=$date_start_prev&date_stop_prev=$date_stop_prev&label=$label";
//mati_disini();
//cekHere($getDateNeraca);

        $data = array(
            "mode" => "lapKeuanganKonsolidasian",
            "title" => "Laporan Keuangan Konsolidasi $label ($date_start - $date_stop)",
            "subTitle" => "Laporan Keuangan Konsolidasi $label ($date_start - $date_stop)",
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
            "thisPage" => base_url() . "laporankeuangan/" . get_class($this) . "/" . $this->uri->segment(3),
//            "cabang" => $arrCabangs,
//            "rekeningsName" => $rekeningsNameNew,
//            "rekeningsNameAlias" => $accountAlias,
//            "accountConsolidation" => $accountConsolidation,
//            "pakai_konsolidasi" => sizeof($rekeningsKonsolidasi) > 0 ? 0 : 1,
//            "rekeningKeterangan" => $rekeningKeterangan,

            "neraca" => base_url() . "laporankeuangan/Keuangan/viewNeraca_consolidatedTriwulan_lap?mode=lapkeuangan" . $getDateNeraca,
            "rugilaba" => base_url() . "laporankeuangan/Keuangan/viewPL_consolidatedTriwulan?mode=lapkeuangan" . $getDateRL,
            "cashflow" => base_url() . "laporankeuangan/Keuangan/viewCashflowTriwulan?mode=lapkeuangan&c=konsolidasi" . $getDate . $getDateCf . $getDateCfPrev,
            "periode" => "triwulan",
        );
        $this->load->view("finance", $data);

    }
    //-----------------------------------------------
}