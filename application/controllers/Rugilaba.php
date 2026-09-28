<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class Rugilaba extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("he_menu");
        $this->load->model("Coms/ComLedger");
        $this->koloms = array(
            "nama rekening",
            "debet",
            "kredit",
        );
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }


    public function viewPLOLD()
    {


        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
        $accountChilds = $this->config->item("accountChilds");
        $this->load->model("Mdls/" . "MdlRugilaba");
        $ner = new MdlRugilaba();

        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

        $tmp = $ner->fetchBalances($defaultDate);
        $dates = $ner->fetchDates();
        //        cekkuning($this->db->last_query());
        //        arrprint($tmp);


        $oldDate = date("Y-m-d");

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (!in_array($row->kategori, $categories)) {
                    $categories[] = $row->kategori;
                }
                if (!isset($rekenings[$row->kategori])) {
                    $rekenings[$row->kategori] = array();
                }
                $tmpCol = array(
                    "rekening" => $row->rekening,
                    "debet" => $row->debet,
                    "kredit" => $row->kredit,
                    "link" => "",
                );
                if (isset($accountChilds[$row->rekening])) {
                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "'><span class='fa fa-clone'></span></a>";
                }
                $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                $rekenings[$row->kategori][$row->rekening] = $tmpCol;

            }
            reset($dates);
            $oldDate = key($dates);
        }


        $data = array(
            "mode" => "viewNeraca",
            "title" => "rugi laba",
            "subTitle" => "rugi laba per-" . lgTranslateTime($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "rekening" => "rekening",
                "debet" => "debet",
                "kredit" => "kredit",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)

        );
        $this->load->view("finance", $data);

    }

    public function viewPL()
    {



        $pakai_ini = 0;
        if($pakai_ini == 1){
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
            $categoryRL = $this->config->item("categoryRL");
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
        }
        else{
            $_GET['tm'] = 1;
            if ($_GET['tm'] == 1) {
                $this->load->model("Mdls/MdlRugilaba");
                $this->load->model("Mdls/MdlFinanceConfig");
                $rekException = array("9010");
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
                $categoryRL = $this->config->item("categoryRL");
                $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
                $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

                $rekeningCoa = rekening_coa_he_accounting();
                $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
                $accountRekeningSort = rekening_coa_sort_he_accounting();
                $categoryRL_OLD = $categoryRL;
                $categoryRL = array();
                foreach ($categoryRL_OLD as $cat => $catSpec) {
                    foreach ($catSpec as $key => $val) {
                        if (isset($rekeningCoa[$key])) {
                            $key_new = $rekeningCoa[$key];
                            $categoryRL[$cat][$key_new] = $val;
                        }
                    }
                }

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
                            // bila rekening bukan kode coa maka diberi kode coa
                            if(!is_numeric($row->rekening)){
                                $row->rekening = $rekeningCoa[$row->rekening];
                            }
//                        cekHere($row->rekening);

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
            }
        }

        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "rugi laba final ",
            "subTitle" => "rugi laba final " . my_cabang_nama() . " " . lgTranslateTime2($defaultDate),
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
        $pakai_ini = 0;
        if($pakai_ini == 1){
            $this->load->model("Mdls/MdlRugilaba");
            $this->load->model("Mdls/MdlFinanceConfig");
            $rekException = array("rugilaba");
            $previousMonth = previousYear();
            // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
            $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
            $defaultDate_ex = explode("-", $defaultDate);
            $tahun = $defaultDate_ex[0];
            $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";

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
            $categoryRL = $this->config->item("categoryRL");
            $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
            $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

            $cabangIDsession = $this->session->login['cabang_id'];
            $ner = new MdlRugilaba();
            $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
            $ner->addFilter("periode='$periode'");
            $tmp = $ner->fetchBalances($defaultDate);
            // showLast_query("kuning");

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
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                                $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop&periode=tahunan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
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
        }
        else{
            $_GET['tm'] = 1;
            if ($_GET['tm'] == 1) {
                $this->load->model("Mdls/MdlRugilaba");
                $this->load->model("Mdls/MdlFinanceConfig");
                $rekException = array("9010");
                $previousMonth = previousYear();
                // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
                $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
                $defaultDate_ex = explode("-", $defaultDate);
                $tahun = $defaultDate_ex[0];
                $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";

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
                $categoryRL = $this->config->item("categoryRL");
                $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
                $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

                $rekeningCoa = rekening_coa_he_accounting();
                $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
                $accountRekeningSort = rekening_coa_sort_he_accounting();
                $categoryRL_OLD = $categoryRL;
                $categoryRL = array();
                foreach ($categoryRL_OLD as $cat => $catSpec) {
                    foreach ($catSpec as $key => $val) {
                        if (isset($rekeningCoa[$key])) {
                            $key_new = $rekeningCoa[$key];
                            $categoryRL[$cat][$key_new] = $val;
                        }
                    }
                }


                $cabangIDsession = $this->session->login['cabang_id'];
                $ner = new MdlRugilaba();
                $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
                $ner->addFilter("periode='$periode'");
                $tmp = $ner->fetchBalances($defaultDate);
                // showLast_query("kuning");

                $dates = $ner->fetchDates();

                $oldDate = date("Y-m");

                $categories = array();
                $rekenings = array();
                $rekeningsName = array();
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $row) {
                        //                if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                        foreach ($categoryRL as $k => $catSpec) {
                            // bila rekening bukan kode coa maka diberi kode coa
                            if(!is_numeric($row->rekening)){
                                $row->rekening = $rekeningCoa[$row->rekening];
                            }
//                        cekHere($row->rekening);

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
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                                    $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1_periode/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop&periode=tahunan' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
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
            }
        }


        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLabaTahunan",
            "title" => "rugi laba " . my_cabang_nama() . " tahun $defaultDate",
            "subTitle" => "rugi laba " . my_cabang_nama() . " tahun " . $defaultDate,
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
            "gr" => isset($_GET['gr']) ? $_GET['gr'] : "",
            "tahunDipilih" => $defaultDate,
        );
        $this->load->view("finance", $data);

    }

    public function viewPLTahunan_()
    {
        //        $defaultDate=isset($_GET['date'])?$_GET['date']:date("Y-m-d");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $this->load->model("Mdls/" . "MdlRugilaba");
        $rekException = array("rugilaba");
        if ($defaultDate != null) {
            $defaultDate_ex = explode("-", $defaultDate);
            $defaultDate_bal = $defaultDate_ex[0];
        }
        else {
            $defaultDate_ex = explode("-", $defaultDate);
            $defaultDate_bal = $defaultDate_ex[0];
        }

        $ner = new MdlRugilaba();
        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='tahunan'");
        $tmp = $ner->fetchBalances($defaultDate_bal);
        //        cekKuning($this->db->last_query());
        $dates = $ner->fetchDates();
        //        $oldDate=date("Y-m-d");
        $oldDate = date("Y-m");

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                    foreach ($categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {

                            if (!isset($rekenings[$k])) {
                                $rekenings[$k] = array();
                            }

                            if (!in_array($row->rekening, $rekException)) {

                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
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

                            $rekenings[$k][$row->rekening] = $tmpCol;
                        }
                    }
                }
            }
            reset($dates);
            $oldDate = key($dates);
        }
        ksort($rekenings);


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


        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "rugi laba",
            "subTitle" => "rugi laba " . lgTranslateTime3($defaultDate_bal),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "rek_id" => "code",
                "rekening" => "rekening",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "values" => "balance(IDR)",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)

        );
        $this->load->view("finance", $data);

    }

    public function viewPLRealtime()
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

        $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $this->load->model("Mdls/" . "MdlRugilaba");
        $rekException = array("rugilaba");


        $tmp = array();
        if (sizeof($resultRL['rugilaba']) > 0) {
            foreach ($resultRL['rugilaba'] as $nn => $nSpec) {
                foreach ($nSpec as $key => $val) {
                    $temp[$key] = $val;
                }
                $tmp[$nn] = (object)$temp;
            }
        }


        $categories = array();
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

                            if (!in_array($row->rekening, $rekException)) {

                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
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

                            //cekHitam(" $row->rekening :: $value");
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

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "rugi laba",
            "subTitle" => "realtime rugi laba " . lgTranslateTime2("2019"),
            //            "subTitle" => "rugi laba ",
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
            "defaultDate" => isset($defaultDate) ? $defaultDate : "",
            "oldDate" => isset($oldDate) ? $oldDate : "",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "categoryRLBottom" => $categoryRLBottom,

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
        );
        $this->load->view("finance", $data);

    }

    public function viewPL_consolidated__()
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
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $ner = new MdlRugilaba();
        $tmp = $ner->fetchBalances2($defaultDate);
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
        $data = array(
            "mode" => "viewPL_consolidated",
            "title" => "rugi laba konsolidasi bulanan ",
            "subTitle" => "rugi laba konsolidasi bulanan " . lgTranslateTime2($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                //                "rekening" => "rekening",
                //                "debet" => "debet",
                //                "kredit" => "kredit",
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            //            "categoryRLBottom" => $categoriesSubBottom,
            "rekeningBlacklist" => $rekException,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("finance", $data);

    }

    // bulanan update viewer
    public function viewPL_consolidated()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $periode = "bulanan";
        $rekException = array("9010");
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
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

        $rekeningCoa = rekening_coa_he_accounting();
        $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
        $accountRekeningSort = rekening_coa_sort_he_accounting();
//        arrPrint($accountAlias);
//        arrPrintPink($categoryRL);
        $categoryRL_OLD = $categoryRL;
        $categoryRL = array();
        foreach ($categoryRL_OLD as $cat => $catSpec) {
            foreach ($catSpec as $key => $val) {
                if(isset($rekeningCoa[$key])){
                    $key_new = $rekeningCoa[$key];
//                    cekHijau("$key --- $key_new");
                    $categoryRL[$cat][$key_new] = $val;
                }
            }
        }
//        arrPrintHijau($rekeningCoa);
//        arrPrint($categoryRL);
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
                        // bila rekening bukan kode coa maka diberi kode coa
                        if(!is_numeric($row->rekening)){
                            $row->rekening = $rekeningCoa[$row->rekening];
                        }
//                        cekHere($row->rekening);

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


        $categoriesAll = array(
            1,
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
//arrPrintPink($rekenings);
//arrPrint($rekeningsName);
//arrPrint($categories);
//arrPrint($rekeningsNameNew);
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

//        arrPrintPink($rekenings);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi (internal) " . lgTranslateTime2($defaultDate),
            "subTitle" => "Laporan Rugilaba Konsolidasi (internal) " . lgTranslateTime2($defaultDate),
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
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $periode = "tahunan";
        $rekException = array("9010");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
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
//        showLast_query("biru");
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }
//arrPrintPink($fcResult);
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
//        arrPrint($categoryRL);
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

//arrPrint($categoryRL);

        $arrCabang = array();
        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $thn_ex => $thn_ex_spec) {
                if (sizeof($thn_ex_spec) > 0) {
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

                                    }
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
//arrPrintHijau($categoryRL);
//cekHitam(":: $tahun ::");

        $categoriesAll = array(1,
            2,
            3,
            4
        );
        $categories = array();
        $categoriesSubBottom = array();


        $oldDate = "2019-09";
        if (isset($_GET['mode']) && ($_GET['mode'] == 'lapkeuangan')) {
            $views_mode = "keuangan_rugilaba_konsolidasi";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
//                "link_detail" => "",
//                "link" => "",
            );
            $headersTahun = array(
                "$tahun" => "$tahun",
                "$prevYear" => "$prevYear",
            );
            $rekeningSelected = $rekenings;
            foreach ($categoriesAll as $ctr => $cat) {
                if (sizeof($rekenings[$tahun]) > 0) {
                    if (array_key_exists($cat, $rekenings[$tahun])) {
                        $categories[] = $cat;
                        $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                    }
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
        else {
            $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
//            cekHere($defaultDate);
            $views_mode = "viewPL_consolidated";
            $views = "finance";
            $headerss = array(
                "values" => "balance(IDR)",
                "link_detail" => "",
                "link" => "",
            );
            $headersTahun = array();
            $rekeningSelected = $rekenings[$defaultDate];
            foreach ($categoriesAll as $ctr => $cat) {
                if (sizeof($rekenings[$defaultDate]) > 0) {
                    if (array_key_exists($cat, $rekenings[$defaultDate])) {
                        $categories[] = $cat;
                        $categoriesSubBottom[] = isset($categoryRLBottom[$ctr]) ? $categoryRLBottom[$ctr] : "";
                    }
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
//arrPrintHijau($rekeningSelected);
        $data = array(
            "mode" => "$views_mode",
            "title" => "Laporan Rugilaba Konsolidasi tahunan (internal)",
//            "subTitle" => "Laporan Rugilaba Konsolidasi tahunan " . lgTranslateTime3($defaultDate),
            "subTitle" => "Laporan Rugilaba Konsolidasi tahunan (internal) $defaultDate",
            "categories" => $categories,
            "rekenings" => $rekeningSelected,
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
            "periode" => "tahunan",
        );
        $this->load->view("$views", $data);

    }

    public function viewPLMonthToDate()
    {
        $maintenance = true;
        if ($maintenance == false) {

            $this->load->model("Mdls/MdlNeraca");
            $this->load->model("Mdls/MdlNeracaLajur");
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

            $periode = "bulanan";
            $cabangID = $this->session->login['cabang_id'];
            //        $cabangID = "-1";
            $date1 = date("Y-m-01");
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
            $this->load->model("Mdls/MdlRugilaba");
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
        }

        $_GET['tm'] = 1;
        if ($_GET['tm'] == 1) {
            $this->load->model("Mdls/MdlNeraca");
            $this->load->model("Mdls/MdlNeracaLajur");
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

            $periode = "bulanan";
            $cabangID = $this->session->login['cabang_id'];
            //        $cabangID = "-1";
            $date1 = date("Y-m-01");
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
//                "date(dtime)<=" => $date2,
                "bln" => $bulan,
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
//            cekKuning($this->db->last_query());
//            arrPrintKuning($tmp);
            $arrLajurNew = array();
            foreach ($tmp as $spec) {
                $rek = $spec['rekening'];
                if (!in_array($rek, $arrRekBlacklist)) {
                    $arrLajurNew[$rek] = $spec;
                }
            }
//            arrPrintKuning($arrLajurNew);

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $arrLajurNew);
            $resultRL = $rl->execNoCut_view();
//            arrPrintPink($resultRL);


            $defaultDate = "$tahun-$bulan";
            $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
            $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
            $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
            $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
            $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
            $this->load->model("Mdls/MdlRugilaba");
            $rekException = array("9010");
            $rekeningCoa = rekening_coa_he_accounting();
            $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
            $accountRekeningSort = rekening_coa_sort_he_accounting();
//arrPrintKuning($categoryRL);
            $categoryRL_OLD = $categoryRL;
            $categoryRL = array();
            foreach ($categoryRL_OLD as $cat => $catSpec) {
                foreach ($catSpec as $key => $val) {
                    if (isset($rekeningCoa[$key])) {
                        $key_new = $rekeningCoa[$key];
                        $categoryRL[$cat][$key_new] = $val;
                    }
                }
            }


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
//arrPrintKuning($rekeningCoa);
//            arrPrintKuning($categoryRL);
//            arrPrintKuning($rekeningsName);
//            arrPrintKuning($rekeningsNameNew);

            $maintenance = false;
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
        $data = array(
            "mode" => "viewRugiLaba2",
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

            "underMaintenanceView" => $maintenance,
            "underMaintenance" => underMaintenance(),
        );
        $this->load->view("finance", $data);
    }

    public function viewPLYearToDate()
    {
        $maintenance = true;
        if ($maintenance == false) {

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
        }

        $_GET['tm'] = 1;
        if ($_GET['tm'] == 1) {
            $this->load->model("Mdls/MdlNeraca");
            $this->load->model("Mdls/MdlNeracaLajur");
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
            $date1 = date("Y-m-01");
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
//                    "bln" => $bulan,
                    "thn" => $tahun,
                    "periode" => $periode,
                ),
            );
            $filters = array(
                "periode" => $periode,
                "cabang_id" => $cabangID,
//                "bln" => $bulan,
                "thn" => $tahun,
            );
            $filters2 = array(
                "periode=" => $periode,
                "cabang_id=" => $cabangID,
//                "date(dtime)<=" => $date2,
//                "bln" => $bulan,
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
//            cekKuning($this->db->last_query());
//            mati_disini();
//            arrPrintKuning($tmp);
            $arrLajurNew = array();
            foreach ($tmp as $spec) {
                $rek = $spec['rekening'];
                if (!in_array($rek, $arrRekBlacklist)) {
                    $arrLajurNew[$rek] = $spec;
                }
            }
//            arrPrintKuning($arrLajurNew);

            $rl->setFilters2($filters2);
            $rl->setFilters($filters);
            $rl->pairNoCut_view($static, $arrLajurNew);
            $resultRL = $rl->execNoCut_view();
//            arrPrintPink($resultRL);


            $defaultDate = "$tahun-$bulan";
            $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
            $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
            $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
            $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
            $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
            $this->load->model("Mdls/MdlRugilaba");
            $rekException = array("9010");
            $rekeningCoa = rekening_coa_he_accounting();
            $accountAlias = $rekeningCoaAlias = fetchAccountStructureAlias();
            $accountRekeningSort = rekening_coa_sort_he_accounting();
//arrPrintKuning($categoryRL);
            $categoryRL_OLD = $categoryRL;
            $categoryRL = array();
            foreach ($categoryRL_OLD as $cat => $catSpec) {
                foreach ($catSpec as $key => $val) {
                    if (isset($rekeningCoa[$key])) {
                        $key_new = $rekeningCoa[$key];
                        $categoryRL[$cat][$key_new] = $val;
                    }
                }
            }


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
//arrPrintKuning($rekeningCoa);
//            arrPrintKuning($categoryRL);
//            arrPrintKuning($rekeningsName);
//            arrPrintKuning($rekeningsNameNew);

            $maintenance = false;
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
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "$title",
//            "subTitle" => "$title " . strtoupper(my_cabang_nama()) . " " . lgTranslateTime2($date1) . " - " . lgTranslateTime2($date2),
            "subTitle" => "$title " . strtoupper(my_cabang_nama()) . " " . lgTranslateTime2($date2),
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
            "periode" => "ytd",

            "underMaintenanceView" => $maintenance,
            "underMaintenance" => underMaintenance(),
        );
        $this->load->view("finance", $data);
    }

    /* ----------------------------
     * MTD-YTD masuk sini
     * ----------------------------*/
    public function viewPLConsolidated_old()
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
        switch ($mode) {
            case "mtd":
                $periode = "bulanan";
                $date1 = date("Y-m-01");
                $mode_report = formatTanggal($date1, 'd') . " - " . formatTanggal($date2, 'd F Y');
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0];
                $blt = "bln";
                break;
            default:
                $periode = "tahunan";
                $date1 = date("Y-01-01");
                $mode_report = formatTanggal($date1, 'd F') . " - " . formatTanggal($date2, 'd F Y');
                $bulan = $dateExp[1];
                $tahun = $dateExp[0];
                $tahunLast = $dateExp[0];
                break;
        }
        //ini gak dipakai
        // $bulan = $dateExp[1];
        // $tahun = $dateExp[0];
        // $tahunLast = $dateExp[0] - 1;

// cekHEre($periode);
// cekHitam($date1);

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
                    // cekLime($this->db->last_query());
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

// arrPrint($arrMutasiResult);
            // mengambil neraca terakhir....
            $ner = new MdlNeraca();
            $ner->addFilter("cabang_id='" . $cabangID . "'");
            if (isset($blt)) {
                $ner->addFilter("bln='" . $bulan . "'");
            }
            $ner->addFilter("periode='$periode'");
            $tmpLastNeraca = $ner->fetchBalances($tahunLast);
// cekHitam($this->db->last_query());
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
                            // cekKuning(" $rek ::: ".$kredit);
                        }
                    }
                    else {
                        $debet = $lnSpec->debet;
                        $kredit = $lnSpec->kredit;
                        // cekBiru(" $rek ::: ".$kredit);
                    }
                    $tmpLastNeracaResult[$rek]["rek_id"] = $lnSpec->rek_id;
                    $tmpLastNeracaResult[$rek]["rekening"] = $lnSpec->rekening;
                    $tmpLastNeracaResult[$rek]["debet"] += $debet;
                    $tmpLastNeracaResult[$rek]["kredit"] += $kredit;
                    $tmpLastNeracaResult[$rek]["periode"] = $lnSpec->periode;

                    $tmpRekNeraca[$rek] = $rek;
                }
            }
// arrPrint($tmpLastNeracaResult);
            $arrLajur = array();
            if (sizeof($tmpLastNeracaResult) > 0) {
                foreach ($tmpLastNeracaResult as $rek => $spec) {
                    if ($spec['debet'] > 0 && $spec['kredit'] > 0) {
                        $value = $spec['debet'] - $spec['kredit'];
                        if ($value < 0) {
                            $debetLast = 0;
                            $kreditLast = $value * -1;
                            // cekBiru(" $rek ::: ".$kreditLast);
                        }
                        else {
                            $debetLast = $value;
                            $kreditLast = 0;
                        }
                    }
                    else {
                        $debetLast = $spec['debet'];
                        $kreditLast = $spec['kredit'];
                        // cekHijau(" $rek ::: ".$kreditLast);
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
                            // cekMerah("**saldo  debet $rek::: debetLast + $debetMutasi - $kreditMutasi =".$saldo_debet);
                        }
                        else {
                            $saldo_debet = -$kreditLast + $debetMutasi - $kreditMutasi;
                            // cekHitam("saldo  kredit $rek::: -$kreditLast + $debetMutasi - $kreditMutasi=".$saldo_debet);
                        }
                        $saldo_kredit = 0;
                    }
                    elseif ($defaultPosition == "kredit") {
                        if ($kreditLast > 0) {
                            // cekMerah();
                            $saldo_kredit = $kreditLast + $kreditMutasi - $debetMutasi;
                            $saldo_debet = 0;
                            // cekMerah("saldo  kredit $rek::: -$debetLast + $kreditMutasi - $debetMutasi =".$saldo_kredit);
                        }
                        else {

                            $saldo_kredit = -$debetLast + $kreditMutasi - $debetMutasi;
                            $saldo_debet = 0;
                            // cekMerah("saldo  kredit $rek::: -$debetLast + $kreditMutasi - $debetMutasi =".$saldo_kredit);
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

            // arrPrint($arrLajur);
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

            // ceklIme($this->db->last_query());

            $result_object = array();
            foreach ($resultRL['rugilaba'] as $ii => $rSpec) {
                $result_object[$ii] = (object)$rSpec;
            }
            $resultRLByCabang[$cabangID][] = $result_object;
        }
        // endregion rl year to date

        // arrPrint($resultRLByCabang);

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
                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

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
        // arrPrint($rekenings);
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
        );
        $this->load->view("finance", $data);

    }

    public function viewPLConsolidated()
    {
        $pakai_ini = 0;
        if ($pakai_ini == 1) {

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
            $rekeningsNameCek = array();
            if (sizeof($tmp) > 0) {
//            arrPrintWebs($tmp['-1']);
                foreach ($tmp as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
//                        arrPrint($row);
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
                                    if (!isset($rekeningsNameCek[$k])) {
                                        $rekeningsNameCek[$k] = array();
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
                                    $rekeningsNameCek[$k][$row->rekening] = $row->rekening;
                                }
//                            else{
//                                cekHere(":: $row->rekening ::");
//                            }
                            }
                            //                        }
                        }
                    }
                }
                //            reset($dates);
                //            $oldDate = key($dates);
            }
//        arrPrintHijau($rekeningsNameCek);

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
        else {
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

                $periode = "bulanan";
                $date1 = date("Y-m-01");
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
//                    "date(dtime)<=" => $date2,
                        "bln" => $bulan,
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
                    showLast_query("biru");

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
                        if (isset($rekeningCoa[$key])) {
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
                                        $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

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

//                arrPrint($tmp);
//                arrPrint($rekenings);
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
            $title = "consolidated profit & loss report ytd (internal)";
        }
        // arrPrint($rekenings);
        $data = array(
//                        "mode" => "viewPL_consolidated",
            "mode" => "viewPLYearToDate_consolidated",
            "title" => "$title",
            "subTitle" => "$title : $mode_report " . lgTranslateTime($date1) . " - " . lgTranslateTime($date2),
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
        $maintenance = true;
        if ($maintenance == false) {

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
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

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
        }

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
                    if (isset($rekeningCoa[$key])) {
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
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

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
            $maintenance = false;

//            arrPrintPink($categoryRL);
//            arrPrintKuning($rekeningsNameNew);
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
        $data = array(
            //            "mode" => "viewPL_consolidated",
            "mode" => $this->uri->segment(2),
            "title" => "$title",
            "subTitle" => "$title $date1 - $date2",
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

            "underMaintenanceView" => $maintenance,
            "underMaintenance" => underMaintenance(),
        );
        $this->load->view("finance", $data);

    }

    /* ----------------------------
     * MTD-YTD masuk sini
     * ----------------------------*/
    public function viewPLCabang()
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

        // cekBiru(url_segment());
        $mode = url_segment(3);

        $cr = New ComRekening_cli();
        $n = New ComNeraca_cli();
        $rl = New ComRugiLaba_cli();

        $arrRekBlacklist = array(
            "rugilaba",
        );
        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        // $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                if ($cabSpec->id > "-1") {
                    $arrCabangs[$cabSpec->id] = $cabSpec->nama;
                }
            }
        }
        // cekBiru($arrCabangs);

        $date2 = date("Y-m-d");
        $dateNow = date("Y-m-d");
        $dateTimeNow = date("Y-m-d H:i:s");
        $dateExp = explode("-", $dateNow);
        switch ($mode) {
            case "mtd":
                $periode = "bulanan";
                $date1 = date("Y-m-01");
                $mode_report = formatTanggal($date1, 'd') . " - " . formatTanggal($date2, 'd F Y');
                $mtd = "bln";
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
//        $tahunLast = $dateExp[0];


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
//                                    cekLime($this->db->last_query());
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
//            if(isset($mtd)){
//                $ner->addFilter("bln='$mtd'");
//            }
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
        // cekBiru($tmp);
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
                                $link = "<span class='pull-right link'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
//                                $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1#/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = "";
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
        $data = array(
            //            "mode" => "viewPL_consolidated",
            "mode" => "viewPLYearToDate_consolidated",
            "title" => "$title",
            "subTitle" => "$title :: $mode_report",
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


    public function viewPLInvoice()
    {
        $this->load->model("Coms/ComJurnal");
        $this->load->model("MdlTransaksi");

        $begin_date = $vd_start = isset($_GET['d_start']) ? $_GET['d_start'] : dtimeNow("Y-m-") . "01";
        $end_date = $vd_stop = isset($_GET['d_stop']) ? $_GET['d_stop'] : dtimeNow("Y-m-d");

        $jenisTransaksi = "582";
        $arrJenisTransaksi = array("582spd");
        $arrRekening = array("penjualan",
            "hpp"
        );
        $cabangID = my_cabang_id();
        $trIDs = array();
        $trDatas = array();
        $trKolom = array(
            "nomer",
            "nomer_top",
            "dtime",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "cabang_id",
            "cabang_nama",
        );


        // -------------------------------------------------
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenisTransaksi'");
        if ($cabangID == CB_ID_PUSAT) {
        }
        else {
            $tr->addFilter("cabang_id='$cabangID'");
        }
        $this->db->where(
            array(
                "DATE(dtime)>=" => $begin_date,
                "DATE(dtime)<=" => $end_date,
            )
        );
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $spec) {
                foreach ($spec as $key => $val) {

                    if (in_array($key, $trKolom)) {

                        $trDatas[$spec->id][$key] = $val;
                    }
                }

                $trIDs[$spec->id] = $spec->id;

                $prevIds = blobDecode($spec->ids_prev);
                $trPL_IDs[$spec->id] = $prevIds[0]; // ID transaksi PL
            }
        }


        // -------------------------------------------------
        $ju = New ComJurnal();
        if ($cabangID == CB_ID_PUSAT) {
        }
        else {
            $ju->addFilter("cabang_id='$cabangID'");
        }
        //        $ju->addFilter("jenis in ('" . implode("','", $arrJenisTransaksi) . "')");
        $ju->addFilter("transaksi_id in ('" . implode("','", $trPL_IDs) . "')");
        $tmp = $ju->lookupAll()->result();
        //        showLast_query("biru");

        $markRugi = array();
        $markRugiNew = array();
        $sumDatas = array();
        $arrJurnal = array();
        $arrJurnalNew = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {

                if (in_array($spec->rekening, $arrRekening)) {
                    if ($spec->debet > 0) {
                        $preNumber = detectRekByPosition($spec->rekening, $spec->debet, "debet");
                    }
                    else {
                        $preNumber = detectRekByPosition($spec->rekening, $spec->kredit, "kredit");
                    }
                    $arrJurnal[$spec->transaksi_id][$spec->rekening] = $preNumber;

                }
            }

            // kalkulasi rugi atau laba
            foreach ($arrJurnal as $plID => $spec) {
                $rugi_laba = $spec['penjualan'] - $spec['hpp'];

                //                if($rugi_laba >= 0){
                //                    $rek = "rugilaba";
                //                    $value = $rugi_laba;
                //                }
                //                else{
                //                    $rek = "rugilaba";
                //                    $value = $rugi_laba * -1;
                //                }
                $rek = "rugilaba";
                $value = $rugi_laba;
                $spec[$rek] = $value;

                // ----summary per-rekening
                foreach ($arrRekening as $rekening) {
                    if (!isset($sumDatas[$rekening])) {
                        $sumDatas[$rekening] = 0;
                    }
                    $sumDatas[$rekening] += $spec[$rekening];
                }
                if (!isset($sumDatas[$rek])) {
                    $sumDatas[$rek] = 0;
                }
                $sumDatas[$rek] += $spec[$rek];


                $arrJurnalNew[$plID] = $spec;
                if ($rugi_laba < 0) {
                    $markRugi[$plID] = "color:red;";
                }
            }

            // ----INJECT KE TR INV--------------------------------
            foreach ($trPL_IDs as $invID => $plID) {
                if (isset($arrJurnalNew[$plID])) {
                    foreach ($arrJurnalNew[$plID] as $key => $val) {
                        $trDatas[$invID][$key] = $val;
                    }
                }
                if (isset($markRugi[$plID])) {
                    $markRugiNew[$invID] = $markRugi[$plID];
                }
            }
        }
        //        arrPrintWebs($arrJurnalNew);
        //        arrPrintWebs($trDatas);
        //        arrPrintWebs($sumDatas);
        //

        $header = array(
            "dtime" => "tanggal",
            "nomer" => "inv number",
            "customers_nama" => "customer",
            "cabang_nama" => "cabang",
            "penjualan" => "penjualan",
            "hpp" => "hpp",
            "rugilaba" => "(rugi)laba kotor",
        );
        $data = array(
            "mode" => "viewRugiLabaInv",
            "title" => "Laporan Rugilaba per Invoice $vd_start - $vd_stop",
            "subTitle" => "",
            "headers" => $header,
            "items" => $trDatas,
            "sum_items" => $sumDatas,

            //            "linkExcel" => base_url() . "ExcelWriter/rugiLaba",
            //            "dateSelector" => true,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "vd_start" => $vd_start,
            "vd_stop" => $vd_stop,
            "marking" => $markRugiNew,
        );
        $this->load->view("finance", $data);


    }

    // -------------------------------------------------
    public function viewPL_mongo()
    {
        $this->load->model("Mdls/MdlMongoRugilaba");
        $this->load->model("Mdls/MdlMongoFinanceConfig");
        $rekException = array("rugilaba");
        $previousMonth = previousMonth();
        // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];
        $periode = "bulanan";


        $fc = New MdlMongoFinanceConfig();
        //        $fc->addFilter("periode='$periode'");
        //        $fc->addFilter("bln='$bulan'");
        //        $fc->addFilter("thn='$tahun'");
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
        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $ner = new MdlMongoRugilaba();
        $ner->addFilter(
            array(
                "periode" => $periode,
                "cabang_id" => $this->session->login['cabang_id'],
            )
        );
        $tmp = $ner->fetchBalances("$defaultDate");

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
        //        arrPrintWebs($categories);
        //        arrPrintWebs($rekenings);

        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "rugi laba",
            "subTitle" => "rugi laba " . lgTranslateTime2($defaultDate),
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
        );
        $this->load->view("finance", $data);

    }

    public function viewPLTahunan_mongo()
    {
        $previousYear = previousYear();
        $defaultDate = isset($_GET['year']) ? $_GET['year'] : $previousYear;
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $this->load->model("Mdls/MdlMongoRugilaba");
        $rekException = array("rugilaba");
        if ($defaultDate != null) {
            $defaultDate_ex = explode("-", $defaultDate);
            $defaultDate_bal = $defaultDate_ex[0];
        }
        else {
            $defaultDate = date("Y-m");
            $defaultDate_ex = explode("-", $defaultDate);
            $defaultDate_bal = $defaultDate_ex[0];
            $defaultDate_bal = $defaultDate_bal - 1;
        }

        $ner = new MdlMongoRugilaba();
        $ner->addFilter(
            array(
                "cabang_id" => $this->session->login['cabang_id'],
                "periode" => "tahunan",
            )
        );
        $tmp = $ner->fetchBalances("$defaultDate_bal");
        //        arrPrint($tmp);


        $dates = $ner->fetchDates();

        $oldDate = date("Y-m");

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
                    foreach ($categoryRL as $k => $catSpec) {
                        if (array_key_exists($row->rekening, $catSpec)) {

                            if (!isset($rekenings[$k])) {
                                $rekenings[$k] = array();
                            }

                            if (!in_array($row->rekening, $rekException)) {

                                if ($row->debet > 0) {
                                    $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
                                }
                                else {
                                    $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
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

                            $rekenings[$k][$row->rekening] = $tmpCol;
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
            "title" => "rugi laba",
            "subTitle" => "rugi laba " . lgTranslateTime3($defaultDate_bal),
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

            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "dateSelector" => false,
            "yearSelector" => true,
        );
        $this->load->view("finance", $data);

    }

    public function viewPL_consolidated_mongo()
    {
        $this->load->model("Mdls/MdlMongoRugilaba");
        $this->load->model("Mdls/MdlMongoFinanceConfig");
        $periode = "bulanan";
        $rekException = array("rugilaba");
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

        $categoryRL = (sizeof($fcResult) > 0 && isset($fcResult['categoryRL']) && ($fcResult['categoryRL'] != NULL)) ? $fcResult['categoryRL'] : $this->config->item("categoryRL");
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $ner = new MdlMongoRugilaba();
        $ner->addFilter(
            array(
                "periode" => $periode,
            )
        );
        $tmp = $ner->fetchBalances2($defaultDate);
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");
        //        arrPrintWebs($tmp);

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

                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;

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
        $data = array(
            "mode" => "viewPL_consolidated",
            "title" => "rugi laba",
            "subTitle" => "rugi laba " . lgTranslateTime2($defaultDate),
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
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            //            "categoryRLBottom" => $categoriesSubBottom,
            "rekeningBlacklist" => $rekException,
        );
        $this->load->view("finance", $data);

    }

    public function viewPL_consolidatedTahunan_mongo()
    {


        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $categoryRL = $this->config->item("categoryRL") != null ? $this->config->item("categoryRL") : array();
        $accountRekeningSort = $this->config->item("accountRekeningSort") != null ? $this->config->item("accountRekeningSort") : array();
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();
        $defaultDate_ex = explode("-", $defaultDate);
        $defaultDate = $defaultDate_ex[0];
        $periode = "tahunan";

        $this->load->model("Mdls/MdlMongoRugilaba");
        $rekException = array("rugilaba");

        $ner = new MdlMongoRugilaba();
        $ner->addFilter(
            array(
                "periode" => $periode,
            )
        );
        $tmp = $ner->fetchBalances2($defaultDate);


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
                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet") * -1;
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

                                //                                    $tmpCol = array(
                                //                                        "rek_id" => "",
                                //                                        "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                //                                        "values" => $value,
                                //                                        "link" => "",
                                //                                    );
                                $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
                                $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";


                                if (isset($accountChilds[$row->rekening])) {
                                    $link = "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "?o=" . $row->cabang_id . "'><span class='fa fa-clone'></span></a>";
                                }
                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";

                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                //                                    $rekeningsName[$k][$row->rekening] = $row->rekening;
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
        $data = array(
            "mode" => "viewPL_consolidated",
            "title" => "rugi laba",
            "subTitle" => "rugi laba " . lgTranslateTime3($defaultDate),
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
        );
        $this->load->view("finance", $data);
    }

    // -------------------------------------------------
    public function viewRlBulanan()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $this->load->model("Mdls/" . "MdlCabang");
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeracaLajur");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $periode = "bulanan";
        $rekException = array("rugilaba");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousMonth();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];
        $startBulan = "1";
        $stopBulan = $bulan;

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
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        // region --- BULAN INI

        $ner = new MdlRugilaba();
        $ner->addFilter("periode='$periode'");
        $this->db->where(array(
            "bln>=" => "$stopBulan",
            "bln<=" => "$stopBulan",
            "thn=" => "$tahun",
        ));
        $tmpBulan = $ner->fetchBalancesRange();
        //        cekkuning($this->db->last_query());
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");
        $rekenings = array();
        //        arrPrintWebs($tmpBulan);
        foreach ($tmpBulan as $rekening => $tmpSpec) {
            $rekenings[] = isset($accountAlias[$rekening]) ? $accountAlias[$rekening] : $rekening;
        }

        // endregion --- BULAN INI


        // region --- BULAN 1 SAMPAI BULAN INI
        $ner->addFilter("periode='$periode'");
        $this->db->where(array(
            "bln>=" => "$startBulan",
            "bln<=" => "$stopBulan",
            "thn=" => "$tahun",
        ));
        $tmpRange = $ner->fetchBalancesRange();
        //        cekBiru($this->db->last_query());
        // endregion --- BULAN 1 SAMPAI BULAN INI


        // region--- YEAR TO DATE, BULAN 1 sampai saat ini
        $tmpYeartodata = array();
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            $cr = New ComRekening_cli();
            $n = New ComNeraca_cli();
            $rl = New ComRugiLaba_cli();

            $arrRekBlacklist = array(
                "rugilaba",
            );
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
            //            arrPrintPink($resultRLByCabang);
            if (sizeof($resultRLByCabang) > 0) {
                foreach ($resultRLByCabang as $cabangID => $resultCabSpec) {
                    foreach ($resultCabSpec[0] as $ii => $rekSpec) {
                        //                        arrPrintPink($rekSpec);
                        $rekening = $rekSpec->rekening;
                        $debet = $rekSpec->debet;
                        $kredit = $rekSpec->kredit;
                        $saldo = $kredit - $debet;

                        if (!isset($tmpYeartodata[$rekening]['debet'])) {
                            $tmpYeartodata[$rekening]['debet'] = 0;
                        }
                        if (!isset($tmpYeartodata[$rekening]['kredit'])) {
                            $tmpYeartodata[$rekening]['kredit'] = 0;
                        }
                        if (!isset($tmpYeartodata[$rekening]['saldo'])) {
                            $tmpYeartodata[$rekening]['saldo'] = 0;
                        }
                        $tmpYeartodata[$rekening]['debet'] += $debet;
                        $tmpYeartodata[$rekening]['kredit'] += $kredit;
                        $tmpYeartodata[$rekening]['saldo'] += $saldo;


                    }
                }
            }
        }
        // endregion--- YEAR TO DATE, BULAN 1 sampai saat ini
        //arrPrintPink($tmpYeartodata);

        $arrCabang = array();
        $categories = array();
        //        $rekenings = array();
        $rekeningsName = array();
        //        if (sizeof($tmp) > 0) {
        //            foreach ($tmp as $cabID => $nerSpec) {
        //                foreach ($nerSpec as $rowSpec) {
        //                    foreach ($rowSpec as $row) {
        //                        //                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
        //                        foreach ($categoryRL as $k => $catSpec) {
        //                            if (array_key_exists($row->rekening, $catSpec)) {
        //                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";
        //
        //                                if (!isset($rekenings[$k][$row->cabang_id])) {
        //                                    $rekenings[$k][$row->cabang_id] = array();
        //                                }
        //                                if (!isset($rekeningsName[$k])) {
        //                                    $rekeningsName[$k] = array();
        //                                }
        //
        //                                if (!in_array($row->rekening, $rekException)) {
        //                                    if ($row->debet > 0) {
        //                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
        //                                        $value = $value > 0 ? $value * -1 : $value;
        //                                    }
        //                                    else {
        //                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
        //                                        $value = $value < 0 ? $value * -1 : $value;
        //                                    }
        //                                }
        //                                else {
        //                                    if ($row->debet > 0) {
        //                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
        //                                    }
        //                                    else {
        //                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
        //                                    }
        //                                }
        //                                $debett = $row->debet;
        //                                $kreditt = $row->kredit;
        //
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";
        //
        //
        //                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
        //
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
        //
        //                            }
        //                        }
        //                        //                        }
        //                    }
        //                }
        //            }
        //            reset($dates);
        //            $oldDate = key($dates);
        //        }


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
        //cekHere(lgTranslateTimeFirstMonth($defaultDate));
        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRlBulanan",
            "title" => "rugi laba konsolidasi bulanan ",
            //            "subTitle" => "rugi laba konsolidasi bulanan " . lgTranslateTime2($defaultDate),
            "subTitle" => "",
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "values_1" => lgTranslateTime2($defaultDate),
                "values_2" => lgTranslateTimeFirstMonth($defaultDate),
                "values_3" => "YTD",

            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            //            "categoryRLBottom" => $categoriesSubBottom,
            "rekeningBlacklist" => $rekException,

            "rugilabaBulanIni" => $tmpBulan,
            "rugilabaBulanRange" => $tmpRange,
            "rugilabaBulanYtd" => $tmpYeartodata,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("finance", $data);

    }

    public function viewRlTahunan()
    {
        $this->load->model("Mdls/" . "MdlRugilaba");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $this->load->model("Mdls/" . "MdlCabang");
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlNeracaLajur");
        $this->load->model("Coms/ComRugiLaba_cli");
        $this->load->model("Coms/ComNeraca_cli");
        $this->load->model("Coms/ComRekening_cli");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $periode = "tahunan";
        $rekException = array("rugilaba");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : previousYear();
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = isset($defaultDate_ex[1]) ? $defaultDate_ex[1] : "";
        $startBulan = "1";
        $stopBulan = $bulan;

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        // $fc->addFilter("bln='$bulan'");
        // $fc->addFilter("thn='$tahun'");
        // $fc->addFilter("bln='01'");
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
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");

        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();


        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }


        // region --- BULAN INI
        // $periode = "bulanan";
        $ner = new MdlRugilaba();
        $ner->addFilter("periode='$periode'");
        $this->db->where(array(
            // "bln>=" => "$stopBulan",
            // "bln<=" => "$stopBulan",
            "thn=" => "$tahun",
        ));
        $tmpBulan = $ner->fetchBalancesRange();
        // cekkuning($this->db->last_query());
        $dates = $ner->fetchDates();
        $oldDate = date("Y-m");
        $rekenings = array();
        //        arrPrintWebs($tmpBulan);
        foreach ($tmpBulan as $rekening => $tmpSpec) {
            $rekenings[] = isset($accountAlias[$rekening]) ? $accountAlias[$rekening] : $rekening;
        }

        // endregion --- BULAN INI


        // region --- BULAN 1 SAMPAI BULAN INI
        $ner->addFilter("periode='$periode'");
        $this->db->where(array(
            // "bln>=" => "$startBulan",
            // "bln<=" => "$stopBulan",
            "thn=" => "$tahun",
        ));
        $tmpRange = $ner->fetchBalancesRange();
        //        cekBiru($this->db->last_query());
        // endregion --- BULAN 1 SAMPAI BULAN INI


        // region--- YEAR TO DATE, BULAN 1 sampai saat ini
        $tmpYeartodata = array();
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            $cr = New ComRekening_cli();
            $n = New ComNeraca_cli();
            $rl = New ComRugiLaba_cli();

            $arrRekBlacklist = array(
                "rugilaba",
            );
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
            //            arrPrintPink($resultRLByCabang);
            if (sizeof($resultRLByCabang) > 0) {
                foreach ($resultRLByCabang as $cabangID => $resultCabSpec) {
                    foreach ($resultCabSpec[0] as $ii => $rekSpec) {
                        //                        arrPrintPink($rekSpec);
                        $rekening = $rekSpec->rekening;
                        $debet = $rekSpec->debet;
                        $kredit = $rekSpec->kredit;
                        $saldo = $kredit - $debet;

                        if (!isset($tmpYeartodata[$rekening]['debet'])) {
                            $tmpYeartodata[$rekening]['debet'] = 0;
                        }
                        if (!isset($tmpYeartodata[$rekening]['kredit'])) {
                            $tmpYeartodata[$rekening]['kredit'] = 0;
                        }
                        if (!isset($tmpYeartodata[$rekening]['saldo'])) {
                            $tmpYeartodata[$rekening]['saldo'] = 0;
                        }
                        $tmpYeartodata[$rekening]['debet'] += $debet;
                        $tmpYeartodata[$rekening]['kredit'] += $kredit;
                        $tmpYeartodata[$rekening]['saldo'] += $saldo;


                    }
                }
            }
        }
        // endregion--- YEAR TO DATE, BULAN 1 sampai saat ini
        //arrPrintPink($tmpYeartodata);

        $arrCabang = array();
        $categories = array();
        //        $rekenings = array();
        $rekeningsName = array();
        //        if (sizeof($tmp) > 0) {
        //            foreach ($tmp as $cabID => $nerSpec) {
        //                foreach ($nerSpec as $rowSpec) {
        //                    foreach ($rowSpec as $row) {
        //                        //                        if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {
        //                        foreach ($categoryRL as $k => $catSpec) {
        //                            if (array_key_exists($row->rekening, $catSpec)) {
        //                                $arrCabang[$row->cabang_id] = isset($arrCabangs[$row->cabang_id]) ? $arrCabangs[$row->cabang_id] : "";
        //
        //                                if (!isset($rekenings[$k][$row->cabang_id])) {
        //                                    $rekenings[$k][$row->cabang_id] = array();
        //                                }
        //                                if (!isset($rekeningsName[$k])) {
        //                                    $rekeningsName[$k] = array();
        //                                }
        //
        //                                if (!in_array($row->rekening, $rekException)) {
        //                                    if ($row->debet > 0) {
        //                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
        //                                        $value = $value > 0 ? $value * -1 : $value;
        //                                    }
        //                                    else {
        //                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
        //                                        $value = $value < 0 ? $value * -1 : $value;
        //                                    }
        //                                }
        //                                else {
        //                                    if ($row->debet > 0) {
        //                                        $value = detectRekByPosition($row->rekening, $row->debet, "debet");
        //                                    }
        //                                    else {
        //                                        $value = detectRekByPosition($row->rekening, $row->kredit, "kredit");
        //                                    }
        //                                }
        //                                $debett = $row->debet;
        //                                $kreditt = $row->kredit;
        //
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['rek_id'] = "";
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['rekening'] = isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening;
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['values'] = $value != null ? $value : 0;
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = "";
        //
        //
        //                                $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewDetail_l1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date=$defaultDate' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
        //
        //                                $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
        //
        //                            }
        //                        }
        //                        //                        }
        //                    }
        //                }
        //            }
        //            reset($dates);
        //            $oldDate = key($dates);
        //        }


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
        //cekHere(lgTranslateTimeFirstMonth($defaultDate));
        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRlTahunan",
            "title" => "rugi laba konsolidasi tahunan ",
            //            "subTitle" => "rugi laba konsolidasi bulanan " . lgTranslateTime2($defaultDate),
            "subTitle" => "",
            "categories" => $categories,
            "rekenings" => $rekenings,
            "headers" => array(
                "values_1" => $defaultDate,
                // "values_2" => lgTranslateTimeFirstMonth($defaultDate),
                "values_3" => "YTD " . dtimeNow('Y'),

            ),
            "defaultDate" => $defaultDate,
            "oldDate" => $oldDate,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            //            "cabang" => $arrCabang,
            "cabang" => $arrCabangs,
            "rekeningsName" => $rekeningsNameNew,
            "rekeningsNameAlias" => $accountAlias,
            "categoryRLBottom" => $categoryRLBottom,
            //            "categoryRLBottom" => $categoriesSubBottom,
            "rekeningBlacklist" => $rekException,

            "rugilabaBulanIni" => $tmpBulan,
            "rugilabaBulanRange" => $tmpRange,
            "rugilabaBulanYtd" => $tmpYeartodata,
            "gr" => isset($_GET['gr']) ? $_GET['gr'] : "",
            "tahunDipilih" => $defaultDate,
            "cabang_nama" => my_cabang_nama(),
        );
        $this->load->view("finance", $data);

    }

    // -------------------------------------------------
    public function viewPLConsolidatedNew()
    {
//        echo underMaintenance();
        $maintenance = true;


        if ($maintenance == false) {

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
                    $date1 = date("Y-01-01");
//                $mode_report = formatTanggal($date1, 'd F') . " - " . formatTanggal($date2, 'd F Y');
                    $mode_report = formatTanggal($date2, 'd F Y');
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

            $pakai_ini = 2;
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
                            $arrLajurNew = $r->saldoForever($cabangID, $periode, $fulldate);
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
                    $resultRLByCabang[$cabangID][] = $result_object;
                }

//            break;
            }

//arrPrintWebs($resultRLByCabang);
//mati_disini(":: $periode ::");
            // endregion rl year to date

            $tmp = $resultRLByCabang;
            $arrCabang = array();
            $categories = array();
            $rekenings = array();
            $rekeningsName = array();
            if (sizeof($tmp) > 0) {
                foreach ($tmp as $cabID => $nerSpec) {
                    foreach ($nerSpec as $rowSpec) {
                        foreach ($rowSpec as $row) {
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
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

                                    $rekenings[$k][$row->cabang_id][$row->rekening]['link'] = $link;
                                    $rekenings[$k][$row->cabang_id][$row->rekening]['link_values'] = base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2";
                                    //                                    $rekeningsName[$k][$row->rekening] = $row->rekening;
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
//showLast_query("biru");
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
//arrPrintPink($resultRLByCabang['-1']);
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
                    if (isset($rekeningCoa[$key])) {
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
                                    $link = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=" . $row->cabang_id . "&periode=$periode&date1=$date1&date2=$date2&date=$date2'><span class='glyphicon glyphicon-time'></span></a></span>";

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
            $maintenance = false;

//            arrPrintPink($categoryRL);
//            arrPrintKuning($rekeningsNameNew);
        }


        $oldDate = "2019-09";
        $defaultDate = "";
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu() . " (internal)";
            // cekHere($title);
        }
        else {
            $title = "consolidated profit & loss report ytd (internal)";
        }
        // arrPrint($rekenings);
        $data = array(
            //            "mode" => "viewPL_consolidated",
            "mode" => "viewPLYearToDate_consolidated",
            "title" => "$title $mode_report",
            "subTitle" => "$title : $mode_report " . lgTranslateTime($date1) . " - " . lgTranslateTime($date2),
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
            "mode_report" => $mode_report,
            "linkAllowedConsolidated" => array(
                "penjualan",
            ),
            "linkConsolidated" => base_url() . "Ledger/viewMoveDetails_1_konsolidasi/Rekening",
            "linkConsolidatedDate" => "?&periode=$periode&date1=$date1&date2=$date2&date=$date2",

            "underMaintenanceView" => $maintenance,
            "underMaintenance" => underMaintenance(),
        );
        $this->load->view("finance", $data);

    }

    // -------------------------------------------------
    public function viewPLConsolidatedNewKomparasi()
    {
        $maintenance = true;
        if ($maintenance == false) {

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
            foreach ($arrFulldateSelect as $tahuns => $fulldates) {
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
                                $date1 = isset($_GET['date1']) ? $_GET['date1'] : $tahuns . "01-01";
                                $date2 = isset($_GET['date2']) ? $_GET['date2'] : $tahuns . "$bln_noww-$tgl_noww";
                                $date1_ex = explode("-", $date1);
                                $date2_ex = explode("-", $date2);
                                $date1_new = $tahuns . "-" . $date1_ex[1] . "-" . $date1_ex[2];
                                $date2_new = $tahuns . "-" . $date2_ex[1] . "-" . $date2_ex[2];
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
                foreach ($tmp as $thn_ex => $thn_exSpec) {
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
        }

        $oldDate = "2019-09";
        $defaultDate = "";
        if (isset($_GET['gr'])) {
            $grEx = explode("-", blobDecode($_GET['gr']));
            $grEx_1 = $grEx[1];
            $title = callMenuLabel_he_menu() . " (internal)";
            // cekHere($title);
        }
        else {
            $title = "consolidated profit & loss report<br>ytd comparation (internal)";
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
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "?",
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


            "underMaintenanceView" => $maintenance,
            "underMaintenance" => underMaintenance(),
        );
        $this->load->view("finance", $data);

    }

    // -------------------------------------------------
    public function viewPLKoreksi()
    {
        $this->load->model("Mdls/MdlRekeningKoreksi");
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlFinanceConfig");
        $rekException = array("rugilaba");
        $previousMonth = previousMonth();
        // $defaultDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m");
        $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

//        cekmerah(":: $tahun == $bulan ::");

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
        $categoryRL = $this->config->item("categoryRL");
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");
        $categoryRLBottom = $this->config->item("categoryRLBottom") != null ? $this->config->item("categoryRLBottom") : array();

        $cabangIDsession = $this->session->login['cabang_id'];

        //region rugilaba setelah koreksi
        $ner = new MdlRugilaba();
        $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);
//        showLast_query("biru");
        $dates = $ner->fetchDates();
        //endregion
        //region rugilaba sebelum koreksi
        $ner = new MdlRugilaba();
        $ner->setFilters(array());
        $ner->addFilter("cabang_id='" . $cabangIDsession . "'");
        $ner->addFilter("periode='$periode'");
        $ner->addFilter("status='1'");
        $ner->addFilter("trash='1'");
        $ner->setSortBy(array("mode" => "DESC", "kolom" => "id"));
        $tmpBeforeKoreksi = $ner->fetchBalances($defaultDate);
//        showLast_query("biru");
        //endregion
        //region jurnal koreksi
        $jnl = New MdlRekeningKoreksi();
        $jnl->addFilter("cabang_id='$cabangIDsession'");
        $jnl->addFilter("koreksi_periode='$periode'");
        $jnl->addFilter("year(koreksi_dtime)='$tahun'");
        $jnl->addFilter("month(koreksi_dtime)='$bulan'");
        $jnlTmp = $jnl->lookupAll()->result();
//        showLast_query("biru");
        //endregion
//arrPrintPink($jnlTmp);

        $oldDate = date("Y-m");

        $categories = array();
        $rekenings = array();
        $rekeningsBeforeKoreksi = array();
        $rekeningsName = array();
        $total_koreksi = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
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
                            "rek_id" => "",
                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                            "values" => $value,
                            "link" => "",
                        );
                        if (isset($accountChilds[$row->rekening])) {

                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";
                        $rekenings[$k][$row->rekening] = $tmpCol;
                    }
                }

            }
            reset($dates);
            $oldDate = key($dates);
        }
        if (sizeof($tmpBeforeKoreksi) > 0) {
            $beforeKoreksi = array();
            foreach ($tmpBeforeKoreksi as $row) {
                $beforeKoreksi[$row->rekening] = $row;
            }
            foreach ($tmpBeforeKoreksi as $row) {
                foreach ($categoryRL as $k => $catSpec) {
                    if (array_key_exists($row->rekening, $catSpec)) {
                        if (!isset($rekeningsBeforeKoreksi[$k])) {
                            $rekeningsBeforeKoreksi[$k] = array();
                        }
//                        if (!isset($rekeningsName[$k])) {
//                            $rekeningsName[$k] = array();
//                        }
//
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
                            "rek_id" => "",
                            "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                            "values_before_koreksi" => $value,
                            "link" => "",
                        );
//                        if (isset($accountChilds[$row->rekening])) {
//                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "' title='view detail $rek_nama_alias'><span class='fa fa-clone'></span></a>";
//                        }
//                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row->rekening . "?o=$cabangIDsession&date1=$d_start&date2=$d_stop' title='view mutasi $rek_nama_alias'><span class='glyphicon glyphicon-time'></span></a></span>";
                        $rekeningsBeforeKoreksi[$k][$row->rekening] = $tmpCol;
                        $rekenings[$k][$row->rekening]["values_before_koreksi"] = $value;
                    }
                }

            }
        }
        if (sizeof($jnlTmp) > 0) {
            foreach ($jnlTmp as $jnlSpec) {
                foreach ($categoryRL as $k => $catSpec) {
                    if (array_key_exists($jnlSpec->rekening, $catSpec)) {
                        if (!in_array($jnlSpec->rekening, $rekException)) {
                            if ($jnlSpec->debet > 0) {
                                $value = detectRekByPosition($jnlSpec->rekening, $jnlSpec->debet, "debet");
                                $value = $value > 0 ? $value * -1 : $value;
                            }
                            else {
                                $value = detectRekByPosition($jnlSpec->rekening, $jnlSpec->kredit, "kredit");
                                $value = $value < 0 ? $value * -1 : $value;
                            }
                        }
                        else {
                            if ($jnlSpec->debet > 0) {
                                $value = detectRekByPosition($jnlSpec->rekening, $jnlSpec->debet, "debet");
                            }
                            else {
                                $value = detectRekByPosition($jnlSpec->rekening, $jnlSpec->kredit, "kredit");
                            }
                        }
                        $rekenings[$k][$jnlSpec->rekening]["values_koreksi_" . $jnlSpec->koreksi_number] = $value;

                        if (!isset($total_koreksi[$jnlSpec->koreksi_number])) {
                            $total_koreksi[$jnlSpec->koreksi_number] = 0;
                        }
                        $total_koreksi[$jnlSpec->koreksi_number] += $value;
                    }
                }
            }
//            foreach ($total_koreksi as $num => $val_koreksi) {
//                $rekenings[4]["rugilaba"]["values_koreksi_" . $num] = $val_koreksi;
//            }
//
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

        $categoriesAll = array(
            1,
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

        $headers = array(
            "values_before_koreksi" => "sebelum koreksi",
        );
        foreach ($total_koreksi as $num => $xxxx) {
            $headers["values_koreksi_" . $num] = "koreksi " . $num;
        }
        $headers["values"] = "setelah koreksi";
        $headers["link"] = "";
//arrPrintWebs($total_koreksi);
//arrPrintWebs($headers);
        $oldDate = "2019-09";
        $data = array(
            "mode" => "viewRugiLaba2",
            "title" => "rugi laba (internal)",
            "subTitle" => "rugi laba (internal) " . lgTranslateTime2($defaultDate),
            "categories" => $categories,
            "rekenings" => $rekenings,
            "rekeningsBeforeKoreksi" => $rekeningsBeforeKoreksi,
            "rekeningsKoreksi" => isset($rekeningsKoreksi) ? $rekeningsKoreksi : array(),
//            "headers" => array(
//                //                "rek_id" => "code",
//                //                "rekening" => "rekening",
//                //                "debet" => "debet",
//                //                "kredit" => "kredit",
////                "values" => "balance(IDR)",
////                "link" => "",
//
////                "values_before_koreksi" => "sebelum koreksi",
////                "values_koreksi" => "koreksi",
////                "values" => "setelah koreksi",
//            ),
            "headers" => $headers,
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
                "label" => "laporan rugilaba final",
                "link" => base_url() . get_class($this) . "/viewPL",
            ),
        );
        $this->load->view("finance", $data);

    }
}

?>