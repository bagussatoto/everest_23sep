<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 2/14/2019
 * Time: 12:50 PM
 */
class ActivityReport extends CI_Controller
{
    private $jenisTr;
    private $steps = array();
    private $dates = array();


    public function __construct()
    {
        parent::__construct();
        $tmpJenis = $this->uri->segment(3);
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;
        }

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);//
        // arrPrint($this->session->login);

        $this->load->library("MobileDetect");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlEmployeeCabang");
        $this->load->model("Mdls/MdlReport");

        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        $this->placeId = $this->session->login['cabang_id'];

        $this->sID_alias = array(
            "oleh_id" => "olehID",
            "customers_id" => "pihakID",
            "cabang_id" => "cabangID",
            "produk_id" => "id",
            "suppliers_id" => "pihakID",
            "seller_id" => "sellerID",
        );
        $this->configUiModul = loadConfigUiModul();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");

    }

    public function viewMonthly()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        //        cekHitam($sID);
        // $subj
        $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            $selectedCabang = "transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'";
        }
        else {
            $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter($selectedCabang);
        $tr->addFilter("transaksi.trash_4='0'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("jenis='" . $stID . "'");
        $tr->addFilterJoin("transaksi_data.trash='0'");
        // if (isset($currentState)) {
        //     $tr->addFilter("jenis='" . $currentState . "'");
        // }
        //        $this->db->like('transaksi.dtime', $dateStr, 'after');
        $this->db->where("year(transaksi.dtime)='$currYear'");
        // $this->db->where("month(transaksi.dtime)='$currMonth'");
        $tmp = $tr->lookupJoined();
//        showLast_query("biru");
        //        arrPrint($tmp);
        $masterID = array();
        $masmedID = array();
        foreach ($tmp as $df) {
            $masterID[] = $df->id_master;

        }
        $listedSeller = array();
        $listedSellerLabel = array();
        if (sizeof($masterID) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in '(" . implode(",", $masterID) . ")'");
            $tempMaster = $tr->lookupAll()->result();
            foreach ($tempMaster as $tempMaster_0) {
                $listedSeller[$tempMaster_0->id] = $tempMaster_0->oleh_id;
                $listedSellerLabel[$tempMaster_0->oleh_id] = $tempMaster_0->oleh_nama;
            }
        }

        // cekbiru($this->db->last_query());
        $trIds_ = array();
        $trIdDts = array();
        //region data dari registry untuk mendapatkan nilai nett1
        foreach ($tmp as $item) {
            $trIds_[$item->transaksi_id] = 1;
            $trIdDts[$item->transaksi_id]['dtime'] = $item->dtime;
            $trIdDts[$item->transaksi_id]['olehID'] = $item->oleh_id;
            $trIdDts[$item->transaksi_id]['sellerID'] = $item->seller_id;
            $trIdDts[$item->transaksi_id]['cabangID'] = $item->cabang_id;
            $trIdDts[$item->transaksi_id]['pihakID'] = ($item->suppliers_id < 1 ? $item->customers_id : $item->suppliers_id);
            $masmedID[$item->transaksi_id] = $item->id_master;
        }
        //        arrPrint($trIdDts);
        $trIds = array_keys($trIds_);
        $idList = implode(",", $trIds);
        if (strlen($idList) > 3) {

            $tr_2 = new MdlTransaksi();
            $tr_2->setFilters(array());
            $tr_2->addFilter("transaksi_id in '(" . $idList . ")'");
            //            $tr_2->addFilter("param ='items'");
            $fields = array("items");
            $tr_2->setJointSelectFields(implode(",", $fields) . ", transaksi_id");
            $registries = $tr_2->lookupDataRegistries()->result();
            // cekHitam($this->db->last_query());
            // arrPrint($registries);
            $subNett = array();
            $subHarga = array();
            $subNettRekap = array();
            foreach ($registries as $registry) {
                foreach ($registry as $key_reg => $val_reg) {
                    if ($key_reg != "transaksi_id") {
                        $trDtime = $trIdDts[$registry->transaksi_id]["dtime"];
                        $trDtime_m = formatTanggal($trDtime, "Y-m");
                        $master_id = $masmedID[$registry->transaksi_id];
                        $sellers_id = $listedSeller[$master_id];

                        $regs = blobDecode($val_reg);
                        $pihakIdDatasValues = array();
                        foreach ($regs as $reg) {
                            $xsID = key_exists($sID, $trIdDts[$registry->transaksi_id]) ? $trIdDts[$registry->transaksi_id][$sID] : $reg[$sID];
                            if (!isset($subNett[$trDtime_m][$xsID])) {
                                $subNett[$trDtime_m][$xsID] = 0;
                                $subNettRekap[$trDtime_m][$sellers_id][$xsID] = 0;
                            }
                            if (isset($reg['nett1'])) {
                                $subNett[$trDtime_m][$xsID] += ($reg['nett1'] * $reg['jml']);
                                $subNettRekap[$trDtime_m][$sellers_id][$xsID] += ($reg['nett1'] * $reg['jml']);
                            }
                            elseif (isset($reg['nett'])) {
                                $subNett[$trDtime_m][$xsID] += ($reg['nett'] * $reg['jml']);
                                $subNettRekap[$trDtime_m][$sellers_id][$xsID] += ($reg['nett'] * $reg['jml']);

                            }
                            else {
                                $subNett[$trDtime_m][$xsID] += (0);
                                $subNettRekap[$trDtime_m][$sellers_id][$xsID] += (0);
                            }
                        }
                    }
                }


            }
        }
        //endregion

        $identifiers = $tr->fetchIdentifiers();
        //        arrprint($identifiers);

        $recaps = array();
        $reacpList = array();

        $dates = array();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($identifiers) > 0) {
                    $fulldate = substr($row->dtime, 0, 7);
                    $dates[$fulldate] = $fulldate;
                    $jenis = $row->jenis;
                    $sellerID = $listedSeller[$row->id_master];
                    $nameMasterAlias[$sellerID] = $listedSellerLabel[$sellerID];
                    //                    $nameMasterAlias[$row->$permanentFilter] = $row->oleh_nama;

                    foreach ($identifiers as $iID => $iName) {
                        // cekLime("iName". $iName." iid".$iID);
                        if (isset($row->$iID)) {
                            $recapChild[$jenis][$permanentFilter][$iID][$sellerID][$row->$iID] = $row->$iName;
                            if (strlen($row->$iID) > 0 && strlen($row->$iName) > 0) {
                                //                                                        cekkuning("$iID/$iName");

                                if (!isset($names[$iID])) {
                                    $names[$iID] = array();
                                }


                                $names[$iID][$row->$iID] = $row->$iName;

                                if (!isset($recaps[$jenis][$iID][$fulldate][$row->$iID])) {
                                    $recaps[$jenis][$iID][$fulldate][$row->$iID] = array(
                                        "qty" => 0,
                                        "value" => 0,
                                    );
                                }

                                if (!isset($nameMaster[$permanentFilter][$iID])) {
                                    $nameMaster[$permanentFilter][$sellerID][$iID] = array();
                                }
                                $nameMaster[$permanentFilter][$sellerID][$iID] = $row->$iName;

                                if (!isset($reacpList[$jenis][$permanentFilter][$iID][$fulldate][$sellerID][$row->$iID])) {
                                    $reacpList[$jenis][$permanentFilter][$iID][$fulldate][$sellerID][$row->$iID] = array(
                                        "qty" => 0,
                                        "value" => 0,
                                    );
                                }
                                if (!isset($subNett[$fulldate])) {
                                    $subNett[$fulldate][$row->$iID] = 0;
                                }
                                if (!isset($subNettRekap[$fulldate][$sellerID])) {
                                    $subNettRekap[$fulldate][$sellerID][$row->$iID] = 0;
                                }
                                $recaps[$jenis][$iID][$fulldate][$row->$iID]['qty'] += $row->produk_ord_jml;
                                // $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = ($row->produk_ord_jml * $row->produk_ord_hrg);
                                // $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = array_key_exists($row->$iID, $subNett[$fulldate]) ? $subNett[$fulldate][$row->$iID] : 0;
                                $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = array_key_exists($row->$iID, $subNett[$fulldate]) ? $subNett[$fulldate][$row->$iID] : 0;
                                $reacpList[$jenis][$permanentFilter][$iID][$fulldate][$sellerID][$row->$iID]['qty'] += $row->produk_ord_jml;
                                $reacpList[$jenis][$permanentFilter][$iID][$fulldate][$sellerID][$row->$iID]['value'] = array_key_exists($row->$iID, $subNettRekap[$fulldate][$sellerID]) ? $subNettRekap[$fulldate][$sellerID][$row->$iID] : 0;

                            }

                        }

                    }

                }
            }
        }
        //arrPrint($recaps);
        //arrPrint($reacpList);

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        $configUijenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        // if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $configUijenis)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link" => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $configUijenis["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        //endregion

        $data = array(
            "mode" => "recap",
            "title" => $configUijenis['label'] . " report",
            "subTitle" => "monthly, " . $currYear,
            "times" => $months,
            "timeLabel" => "months",
            "names" => isset($names) ? $names : array(),
            "recaps" => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $configUijenis["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . "/viewDaily/" . $this->jenisTr,
            "historyPage" => base_url() . "penjualan/History/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => $reacpList,
            "recapName" => $nameMaster,
            "recapNameLabel" => $nameMasterAlias,
            "recapChild" => $recapChild,
        );
        $this->load->view("activityReports", $data);

    }

    public function viewMonthly__()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        //        cekHitam($sID);
        // $subj
        $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            $selectedCabang = "transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'";
        }
        else {
            $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter($selectedCabang);
        $tr->addFilter("transaksi.trash_4='0'");
        $tr->addFilter("transaksi_data.trash='0'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("jenis='" . $stID . "'");
        // if (isset($currentState)) {
        //     $tr->addFilter("jenis='" . $currentState . "'");
        // }
        //        $this->db->like('transaksi.dtime', $dateStr, 'after');
        $this->db->where("year(transaksi.dtime)='$currYear'");
        // $this->db->where("month(transaksi.dtime)='$currMonth'");
        $tmp = $tr->lookupJoined()->result();

        // cekbiru($this->db->last_query());
        $trIds_ = array();
        $trIdDts = array();
        //region data dari registry untuk mendapatkan nilai nett1
        foreach ($tmp as $item) {
            $trIds_[$item->transaksi_id] = 1;
            $trIdDts[$item->transaksi_id]['dtime'] = $item->dtime;
            $trIdDts[$item->transaksi_id]['olehID'] = $item->oleh_id;
            $trIdDts[$item->transaksi_id]['sellerID'] = $item->seller_id;
            $trIdDts[$item->transaksi_id]['cabangID'] = $item->cabang_id;
            $trIdDts[$item->transaksi_id]['pihakID'] = ($item->suppliers_id < 1 ? $item->customers_id : $item->suppliers_id);
        }
        // arrPrint($trIdDtimes_);
        $trIds = array_keys($trIds_);
        $idList = implode(",", $trIds);
        if (strlen($idList) > 3) {

            $tr_2 = new MdlTransaksi();
            $tr_2->setFilters(array());
            $tr_2->addFilter("transaksi_id in '(" . $idList . ")'");
            //            $tr_2->addFilter("param ='items'");
            $tr_2->setJointSelectFields("items,transaksi_id");
            $registries = $tr_2->lookupDataRegistries()->result();
            // cekHitam($this->db->last_query());
            // arrPrint($registries);
            $subNett = array();
            $subHarga = array();
            foreach ($registries as $registry) {
                // cekMerah($registry->transaksi_id);
                $trDtime = $trIdDts[$registry->transaksi_id]["dtime"];
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                // cekMerah("$trDtime *** $trDtime_M");
                $regs = blobDecode($registry->items);
                // arrPrint($regs);
                // cekHijau($trDtime);
                // arrPrint($registry);

                $pihakIdDatasValues = array();
                foreach ($regs as $reg) {
                    $xsID = key_exists($sID, $trIdDts[$registry->transaksi_id]) ? $trIdDts[$registry->transaksi_id][$sID] : $reg[$sID];
                    //                    cekLime($xsID);
                    // cekLime($xsID . " $trDtime_m" );
                    // arrPrint($reg);
                    // $pihakIdDatasValues[$reg['pihakID']] += $reg['nett1'];
                    // cekHitam($reg['pihakName'] . " " . $reg['pihakID'] . " == " . $reg['nett1']);
                    if (!isset($subNett[$trDtime_m][$xsID])) {
                        $subNett[$trDtime_m][$xsID] = 0;
                    }
                    if (isset($reg['nett1'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett1'] * $reg['jml']);
                    }
                    elseif (isset($reg['nett'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett'] * $reg['jml']);

                    }
                    else {
                        $subNett[$trDtime_m][$xsID] += (0);
                    }

                    // $subNett[$reg['pihakName']] += $reg['nett1'];

                    // cekHijau($reg['nett1'] . " net" );
                }
                // $pihakIdDatas['pihakID']
            }
        }
        //endregion

        $identifiers = $tr->fetchIdentifiers();
        //arrprint($identifiers);

        $recaps = array();

        $dates = array();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($identifiers) > 0) {
                    $fulldate = substr($row->dtime, 0, 7);
                    $dates[$fulldate] = $fulldate;
                    $jenis = $row->jenis;
                    foreach ($identifiers as $iID => $iName) {


                        if (strlen($row->$iID) > 0 && strlen($row->$iName) > 0) {
                            //                            cekkuning("$iID/$iName");

                            if (!isset($names[$iID])) {
                                $names[$iID] = array();
                            }

                            $names[$iID][$row->$iID] = $row->$iName;
                            if (!isset($recaps[$jenis][$iID][$fulldate][$row->$iID])) {
                                $recaps[$jenis][$iID][$fulldate][$row->$iID] = array(
                                    "qty" => 0,
                                    "value" => 0,
                                );
                            }
                            // cekHitam($row->$iID);
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['qty'] += $row->produk_ord_jml;
                            // $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = ($row->produk_ord_jml * $row->produk_ord_hrg);
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = array_key_exists($row->$iID, $subNett[$fulldate]) ? $subNett[$fulldate][$row->$iID] : 0;
                        }


                    }

                }
            }
        }

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        $data = array(
            "mode" => "recap",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "subTitle" => "monthly, " . $currYear,
            "times" => $months,
            "timeLabel" => "months",
            "names" => isset($names) ? $names : array(),
            "recaps" => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . "/viewDaily/" . $this->jenisTr,
            "historyPage" => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "?stID=" . $stID,
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
        );
        $this->load->view("activityReports", $data);

    }

    public function viewWeekly()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;


        $currYear = isset($_GET['y']) ? $_GET['y'] : date("Y");
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $currDate = isset($_GET['d']) ? $_GET['d'] : date("d");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }

        if ($this->session->login['cabang_id'] > 0) {
            $selectedCabang = "transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'";
        }
        else {
            $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter($selectedCabang);
        $tr->addFilter("transaksi.trash_4='0'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("jenis='" . $stID . "'");
        //        if (isset($currentState)) {
        //            $tr->addFilter("jenis='" . $currentState . "'");
        //        }
        //

        $this->db->like('transaksi.dtime', $dateStr, 'after');
        $tmp = $tr->lookupJoined()->result();

        //        cekbiru($this->db->last_query());

        $identifiers = $tr->fetchIdentifiers();

        $recaps = array();

        $dates = array();


        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($identifiers) > 0) {
                    //                    $fulldate = substr($row->dtime, 0, 7);
                    $fulldate = substr($row->dtime, 0, 10);
                    //                    echo "date $fulldate<br>";
                    $dates[$fulldate] = $fulldate;
                    $jenis = $row->jenis;
                    foreach ($identifiers as $iID => $iName) {


                        if (strlen($row->$iID) > 0 && strlen($row->$iName) > 0) {

                            if (!isset($names[$iID])) {
                                $names[$iID] = array();
                            }

                            $names[$iID][$row->$iID] = $row->$iName;
                            if (!isset($recaps[$jenis][$iID][$fulldate][$row->$iID])) {
                                $recaps[$jenis][$iID][$fulldate][$row->$iID] = array(
                                    "qty" => 0,
                                    "value" => 0,
                                );
                            }

                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['qty'] += $row->produk_ord_jml;
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] += ($row->produk_ord_jml * $row->produk_ord_hrg);
                        }
                    }

                }
            }
        }


        $months = array();
        for ($i = 1; $i <= 31; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $currMonth . "-" . $i;
            $months[$key] = $i;

        }

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        $data = array(
            "jenisTr" => $this->jenisTr,
            "mode" => "recap",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " reports",
            "subTitle" => "daily, " . lgTranslateTime($dateStr),
            "times" => $months,
            "timeLabel" => "dates",
            "names" => isset($names) ? $names : array(),
            "recaps" => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "historyPage" => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/" . $selectedStep,
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
        );
        $this->load->view("activityReports", $data);

    }

    public function viewDaily()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $currYear = isset($_GET['y']) ? $_GET['y'] : date("Y");
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $currDate = isset($_GET['d']) ? $_GET['d'] : date("d");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        //        cekHitam($sID);
        $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }
        if ($this->session->login['cabang_id'] > 0) {
            $selectedCabang = "transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'";
        }
        else {
            $selectedCabang = "transaksi.cabang_id<>-1";
        }

        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter($selectedCabang);
        $tr->addFilter("transaksi.trash_4='0'");
        $tr->addFilterJoin("transaksi_data.trash='0'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("jenis='" . $stID . "'");
        $tr->addFilter("month(transaksi.dtime)='" . $currMonth . "'");
        $tr->addFilter("year(transaksi.dtime)='" . $currYear . "'");
        $tmp = $tr->lookupJoined();

        //        cekbiru($this->db->last_query());
        $trIds_ = array();
        $trIdDts = array();
        //region data dari registry untuk mendapatkan nilai nett1
        foreach ($tmp as $item) {
            $trIds_[$item->transaksi_id] = 1;
            $trIdDts[$item->transaksi_id]['dtime'] = $item->dtime;
            $trIdDts[$item->transaksi_id]['olehID'] = $item->oleh_id;
            $trIdDts[$item->transaksi_id]['sellerID'] = $item->seller_id;
            $trIdDts[$item->transaksi_id]['cabangID'] = $item->cabang_id;
            $trIdDts[$item->transaksi_id]['pihakID'] = ($item->suppliers_id < 1 ? $item->customers_id : $item->suppliers_id);
        }
        // arrPrint($trIdDtimes_);
        $trIds = array_keys($trIds_);
        $idList = implode(",", $trIds);
        if (strlen($idList) > 3) {

            $tr_2 = new MdlTransaksi();
            $tr_2->setFilters(array());
            $tr_2->addFilter("transaksi_id in '(" . $idList . ")'");
            //            $tr_2->addFilter("param ='items'");
            $tr_2->setJointSelectFields("items,transaksi_id");
            $registries = $tr_2->lookupDataRegistries()->result();
            // cekHitam($this->db->last_query());
            // arrPrint($registries);
            $subNett = array();
            $subHarga = array();
            foreach ($registries as $registry) {
                // cekMerah($registry->transaksi_id);
                $trDtime = $trIdDts[$registry->transaksi_id]["dtime"];
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                // cekMerah("$trDtime *** $trDtime_M");
                $regs = blobDecode($registry->items);
                // arrPrint($regs);
                // cekHijau($trDtime);
                // arrPrint($registry);

                $pihakIdDatasValues = array();
                foreach ($regs as $reg) {
                    $xsID = key_exists($sID, $trIdDts[$registry->transaksi_id]) ? $trIdDts[$registry->transaksi_id][$sID] : $reg[$sID];
                    //                    cekLime($xsID);
                    // cekLime($xsID . " $trDtime_m" );
                    // arrPrint($reg);
                    // $pihakIdDatasValues[$reg['pihakID']] += $reg['nett1'];
                    // cekHitam($reg['pihakName'] . " " . $reg['pihakID'] . " == " . $reg['nett1']);
                    if (!isset($subNett[$trDtime_m][$xsID])) {
                        $subNett[$trDtime_m][$xsID] = 0;
                    }
                    if (isset($reg['nett1'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett1'] * $reg['jml']);
                    }
                    elseif (isset($reg['nett'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett'] * $reg['jml']);

                    }
                    else {
                        $subNett[$trDtime_m][$xsID] += (0);
                    }

                    // $subNett[$reg['pihakName']] += $reg['nett1'];

                    // cekHijau($reg['nett1'] . " net" );
                }
                // $pihakIdDatas['pihakID']
            }
        }
        //endregion

        $identifiers = $tr->fetchIdentifiers();

        $recaps = array();

        $dates = array();


        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($identifiers) > 0) {
                    //                    $fulldate = substr($row->dtime, 0, 7);
                    $fulldate = substr($row->dtime, 0, 10);
                    //                    echo "date $fulldate<br>";
                    $dates[$fulldate] = $fulldate;
                    $jenis = $row->jenis;
                    foreach ($identifiers as $iID => $iName) {


                        if (strlen($row->$iID) > 0 && strlen($row->$iName) > 0) {

                            if (!isset($names[$iID])) {
                                $names[$iID] = array();
                            }

                            $names[$iID][$row->$iID] = $row->$iName;
                            if (!isset($recaps[$jenis][$iID][$fulldate][$row->$iID])) {
                                $recaps[$jenis][$iID][$fulldate][$row->$iID] = array(
                                    "qty" => 0,
                                    "value" => 0,
                                );
                            }

                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['qty'] += $row->produk_ord_jml;
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] += ($row->produk_ord_jml * $row->produk_ord_hrg);
                        }
                    }

                }
            }
        }


        $months = array();
        for ($i = 1; $i <= 31; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $currMonth . "-" . $i;
            $months[$key] = $i;

        }

        //        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        //        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        //        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        $configUijenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link" => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        //endregion

        $data = array(
            "jenisTr" => $this->jenisTr,
            "mode" => "recap",
            "title" => $configUijenis['label'] . " reports",
            "subTitle" => "daily, " . lgTranslateTime($dateStr),
            "times" => $months,
            "timeLabel" => "dates",
            "names" => isset($names) ? $names : array(),
            "recaps" => $recaps,
            "trName" => $configUijenis["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "historyPage" => base_url() . "penjualan/History/viewHistory/" . $this->jenisTr . "/" . $selectedStep . "?",
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
        );
        $this->load->view("activityReports", $data);

    }

    public function viewMySettlement_blmJadi()
    {

        $jenisTr = $this->uri->segment(3);
        //
        $trIDs_allRoutes = array();
        $trIDs_mine = array();
        $trIDs_followers = array();
        $trIDs_referers = array();

        //
        $trItems = array();
        $myItems = array();
        $followerItems = array();

        //
        $trans = array();
        $trans_mine = array();
        $trans_followers = array();

        $this->jenisTrName = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] : "unnamed";

        $settlConfig = $this->config->item("heTransaksi_settlementGroups");
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $routeFollowers = null != ($this->config->item("heTransaksi_routeFollowers")) ? $this->config->item("heTransaksi_routeFollowers") : array();

        //region preparing ERP step labels for top link
        $steps = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    //                    $stepLabels[$stepNumber] = $stepSpec['stateLabel'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepSpec['target'];
                }

            }

            //            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        }
        //endregion

        $historyFields = isset($this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields']) ? $this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields'] : array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",
        );
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields'] : array();
        }


        $trHeaders = array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "jenis_label" => "activity",
            "transaksi_nilai" => "orig. value",
            "add_disc" => "discount",
            "grand_total" => "nett",
        );

        $trHeaders_related = array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "jenis_label" => "activity",
            "transaksi_nilai" => "amount",
            //            "add_disc"=>"discount",
            //            "grand_total"=>"nett",
            "oleh_nama" => "person",
        );


        //
        //region getting trans-IDs
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());

        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        if (isset($routeFollowers[$this->jenisTr]) && sizeof($routeFollowers[$this->jenisTr]) > 0) {
            $tr->addFilter("jenis_master in ('" . $this->jenisTr . "','" . $routeFollowers[$this->jenisTr] . "')");
        }
        else {
            $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        }

        //        $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");

        $tr->addFilter("transaksi_registry.param in ('tableIn_master_values','main')");
        //        $tr->addFilter("transaksi.oleh_id='" . $uID . "'");

        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion

        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }
        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion

        $arrayHistory = array();
        $arrayHistory_ids = array();

        $trIDs = array();
        $tr0 = $tr;

        //        $trLabels=array();
        //        arrprint($tr0->getFilters());
        $this->db->order_by("transaksi.id");


        $tmpHist0 = $tr0->lookupRegistries_joined()->result();
        //        cekbiru($this->db->last_query());
        if (sizeof($tmpHist0) > 0) {
            foreach ($tmpHist0 as $row) {
                if (!in_array($row->transaksi_id, $trIDs)) {

                    if (!array_key_exists($row->jenis, $trans)) {
                        $trans[$row->jenis] = array(
                            "label" => $row->jenis_label,
                            "qty" => 0,
                            "subtotal" => 0,
                        );
                    }


                    $trans[$row->jenis]['qty']++;
                    $trans[$row->jenis]['subtotal'] += $row->transaksi_nilai;

                    $trIDs[] = $row->transaksi_id;

                    $tmpCol = array();
                    $tmpCol['oleh_nama'] = $row->oleh_nama;
                    $content = blobDecode($row->values);
                    //                    arrprint($content);
                    foreach ($trHeaders as $colName => $colLabel) {
                        if (isset($content[$colName])) {
                            $tmpCol[$colName] = $content[$colName];
                        }
                        else {
                            $tmpCol[$colName] = isset($row->$colName) ? $row->$colName : 0;
                        }
                    }

                    //====ID registrars
                    $trIDs_allRoutes[] = $row->transaksi_id;
                    if ($row->oleh_id == $this->session->login['id'] && $row->jenis == $this->jenisTr) {
                        $trIDs_mine[] = $row->transaksi_id;
                        $myItems[] = $tmpCol;
                        $trItems[] = $tmpCol;
                        if (!array_key_exists($row->jenis, $trans_mine)) {
                            $trans_mine[$row->jenis] = array(
                                "label" => $row->jenis_label,
                                "qty" => 0,
                                "subtotal" => 0,
                            );
                        }
                        $trans_mine[$row->jenis]['qty']++;
                        $trans_mine[$row->jenis]['subtotal'] += $row->transaksi_nilai;
                    }
                    if (isset($content['referenceID']) && $content['referenceID'] > 0) {
                        $trIDs_followers[] = $row->transaksi_id;
                        $followerItems[] = $tmpCol;

                        if (!array_key_exists($row->jenis, $trans_followers)) {
                            $trans_followers[$row->jenis] = array(
                                "label" => $row->jenis_label,
                                "qty" => 0,
                                "subtotal" => 0,
                            );
                        }
                        $trans_followers[$row->jenis]['qty']++;
                        $trans_followers[$row->jenis]['subtotal'] += $row->transaksi_nilai;

                    }
                    //====

                    $trItems[] = $tmpCol;
                }
            }
        }
        //endregion


        //        arrprint($trIDs_allRoutes);
        //        arrprint($trIDs_mine);
        //        arrprint($trIDs_followers);
        //        die();
        $accounts = array();
        $rels = array();
        $accountRecaps = array();

        $this->load->helper("he_mass_table");

        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($settlConfig[$gID]) && sizeof($settlConfig[$gID]) > 0) {
                    foreach ($settlConfig[$gID] as $accID => $relPair) {
                        $accounts[$accID] = $accID;
                        foreach ($relPair as $comID => $comLabel) {
                            $rels[$accID][$comID] = $comLabel;
                        }
                    }
                }
            }
        }

        $names = array();
        $recaps = array();

        $columns = array();

        if (sizeof($trIDs_allRoutes) > 0) {
            if (sizeof($accounts) > 0) {
                foreach ($accounts as $accID => $rekName) {
                    if (isset($rels[$accID]) && sizeof($rels[$accID]) > 0) {
                        foreach ($rels[$accID] as $comID => $comLabel) {
                            if (!isset($names[$accID])) {
                                $names[$accID] = array();
                            }
                            if (!isset($recaps[$accID])) {
                                $recaps[$accID] = array();
                            }
                            $mdlName = "Com" . $comID;
                            $this->load->model("Coms/" . $mdlName);
                            $com = new $mdlName();

                            $com->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");

                            $defPosition = detectRekDefaultPosition($rekName);
                            $opPosition = $defPosition == "kredit" ? "debet" : "kredit";
                            $tmp = $com->fetchMovesByTransIDs($accID, $trIDs_allRoutes);

                            //                            arrprint($tmp);

                            //                            cekbiru($this->db->last_query());
                            if (sizeof($tmp) > 0) {
                                foreach ($tmp as $row) {
                                    $jenis = $row->jenis;

                                    if (!isset($columns[$accID])) {
                                        $columns[$accID] = array();
                                    }

                                    if (!isset($columns[$accID][$jenis])) {
                                        $columns[$accID][$jenis] = array(
                                            "extern_nama" => "item names",
                                        );
                                    }
                                    if (!isset($recaps[$accID][$jenis])) {
                                        $recaps[$accID][$jenis] = array();
                                    }
                                    if (!isset($recaps[$accID]["total"])) {
                                        $recaps[$accID]["total"] = array();
                                    }
                                    if (!isset($names[$accID][$jenis])) {
                                        $names[$accID][$jenis] = array();
                                    }
                                    if (!array_key_exists($row->extern_id, $names[$accID][$jenis])) {
                                        $names[$accID][$jenis][$row->extern_id] = $row->extern_nama;
                                    }
                                    if (!isset($recaps[$accID][$jenis][$row->extern_id])) {
                                        $recaps[$accID][$jenis][$row->extern_id] = array(
                                            "extern_nama" => $row->extern_nama,
                                            "qty_in" => 0,
                                            "value_in" => 0,
                                            "qty_out" => 0,
                                            "value_out" => 0,
                                            "qty_saldo" => 0,
                                            "value_saldo" => 0,
                                            "qty" => 0,
                                            "value" => 0,
                                        );
                                    }
                                    $recaps[$accID][$jenis][$row->extern_id]['value_in'] += $row->$defPosition;
                                    $recaps[$accID][$jenis][$row->extern_id]['value_out'] += $row->$opPosition;
                                    $recaps[$accID][$jenis][$row->extern_id]['value_saldo'] = $row->$opPosition . "_akhir";

                                    $qtyField = "qty_" . $defPosition;
                                    $qtyOpField = "qty_" . $opPosition;
                                    if (isset($row->$qtyField)) {
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_in'] += $row->$qtyField;
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_out'] += $row->$qtyOpField;
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_saldo'] = $row->$qtyOpField . "_akhir";
                                    }

                                    //==identifying needed columns
                                    $srcs = array(
                                        "qty_in" => "in",
                                        "value_in" => "in (IDR)",
                                        "qty_out" => "out",
                                        "value_out" => "out (IDR)",

                                    );
                                    foreach ($srcs as $key => $label) {
                                        if (isset($recaps[$accID][$jenis][$row->extern_id][$key]) && $recaps[$accID][$jenis][$row->extern_id][$key] > 0) {
                                            $columns[$accID][$jenis][$key] = $label;
                                        }
                                    }

                                    //===total
                                    $jenis = "total";
                                    if (!isset($columns[$accID][$jenis])) {
                                        $columns[$accID][$jenis] = array(
                                            "extern_nama" => "item names",
                                        );
                                    }
                                    if (!isset($recaps[$accID][$jenis][$row->extern_id])) {
                                        $recaps[$accID][$jenis][$row->extern_id] = array(
                                            "extern_nama" => $row->extern_nama,
                                            "qty_in" => 0,
                                            "value_in" => 0,
                                            "qty_out" => 0,
                                            "value_out" => 0,
                                            "qty_saldo" => 0,
                                            "value_saldo" => 0,
                                            "qty" => 0,
                                            "value" => 0,
                                        );
                                    }
                                    $recaps[$accID][$jenis][$row->extern_id]['value_in'] += $row->$defPosition;
                                    $recaps[$accID][$jenis][$row->extern_id]['value_out'] += $row->$opPosition;
                                    $recaps[$accID][$jenis][$row->extern_id]['value_saldo'] = $row->$opPosition . "_akhir";

                                    $recaps[$accID][$jenis][$row->extern_id]['value'] = abs($recaps[$accID][$jenis][$row->extern_id]['value_in'] - $recaps[$accID][$jenis][$row->extern_id]['value_out']);

                                    $qtyField = "qty_" . $defPosition;
                                    $qtyOpField = "qty_" . $opPosition;
                                    if (isset($row->$qtyField)) {
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_in'] += $row->$qtyField;
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_out'] += $row->$qtyOpField;
                                        $recaps[$accID][$jenis][$row->extern_id]['qty_saldo'] = $row->$qtyOpField . "_akhir";
                                        $recaps[$accID][$jenis][$row->extern_id]['qty'] = abs($recaps[$accID][$jenis][$row->extern_id]['qty_in'] - $recaps[$accID][$jenis][$row->extern_id]['qty_out']);
                                    }

                                    //==identifying columns
                                    $srcs = array(
                                        "qty" => "qty",
                                        "value" => "value (IDR)",
                                    );
                                    foreach ($srcs as $key => $label) {
                                        if (isset($recaps[$accID][$jenis][$row->extern_id][$key]) && $recaps[$accID][$jenis][$row->extern_id][$key] > 0) {
                                            $columns[$accID][$jenis][$key] = $label;
                                        }
                                    }


                                }
                                //                                echo "ada mutasinya<br>";
                            }
                            else {
                                //                                echo "TAK ada mutasinya<br>";
                            }
                        }
                    }
                }
            }

        }

        //        arrprint($srcs);
        //        arrprint($columns);
        //        die();
        //        arrprint($recaps);
        //        die();
        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //
        //region prepare params for viewer
        $subTitle = lgTranslateTime($date1) . " to " . lgTranslateTime($date2);
        if ($date1 == $date2) {
            $subTitle = lgTranslateTime($date1);
        }


        $thisTr = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
        $thisTrName = isset($row->jenis_label) ? $row->jenis_label : $this->config->item("heTransaksi_ui")[$jenisTr]["label"];
        $data = array(
            "mode" => $this->uri->segment(2),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "subJenisTr" => strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $jenisTr,
            //            "trName"               => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "trName" => $thisTrName,
            "errMsg" => $this->session->errMsg,
            "title" => (isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName) . " (" . $this->session->login['nama'] . ")",
            "subTitle" => $subTitle,
            //            "pageCount"            => $numPages,
            //            "page"                 => $page,
            //            "pages"                => $pages,
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,

            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4),
            //
            //            "availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),"availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),
            "availFilters" => isset($availFilters) ? $availFilters : array("oleh_id" => "person"),
            "names" => $names,
            "recaps" => $recaps,

            "trHeaders" => $trHeaders,
            "trHeaders_related" => $trHeaders_related,
            "trItems" => $trItems,
            "myItems" => $myItems,
            "followerItems" => $followerItems,


            "transLabels" => array(
                "label" => "activity name",
                "qty" => "times",
                "subtotal" => "amount",
            ),
            "trans" => $trans,
            "trans_mine" => $trans_mine,
            "trans_followers" => $trans_followers,
            //
            "columns" => $columns,

        );
        //endregion

        $this->load->view("history", $data);
    }

    public function viewMySettlement()
    {

        $jenisTr = $this->uri->segment(3);

        $this->jenisTrName = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] : "unnamed";

        $settlConfig = $this->config->item("heTransaksi_settlementGroups");
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        //region preparing ERP step labels for top link
        $steps = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    //                    $stepLabels[$stepNumber] = $stepSpec['stateLabel'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepSpec['target'];
                }

            }

            //            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        }
        //endregion

        $historyFields = isset($this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields']) ? $this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields'] : array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",
        );
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields'] : array();
        }


        $trHeaders = isset($this->config->item("heTransaksi_ui")[$jenisTr]['settlementHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['settlementHistoryFields'] : array(
            "dtime" => "time",
            "nomer" => "receipt number",
            "jenis_label" => "activity",
            //            "transaksi_nilai" => "orig. value",
            //            "add_disc" => "discount",
            //            "grand_total" => "nett",
            "harga" => "orig. value",
            "disc" => "discount",
            "nett1" => "nett",
            "ppn" => "ppn",
            "nett2" => "total",
        );
        $trItems = array();

        //
        //region getting trans-IDs
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());

        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");

        $tr->addFilter("transaksi_registry.param='tableIn_master_values'");
        //        $tr->addFilter("transaksi.oleh_id='" . $uID . "'");

        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion

        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }
        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            //            arrprint($addParams);
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        //region date filter
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        //endregion


        $arrayHistory = array();
        $arrayHistory_ids = array();


        $trIDs = array();
        $tr0 = $tr;

        $this->db->order_by("transaksi.id");

        //        $tmpHist0 = $tr0->lookupHistories_joined_all()->result();
        $tmpHist0 = $tr0->lookupRegistries_joined()->result();
        cekmerah($this->db->last_query());
        //                 arrPrint($tmpHist0);
        if (sizeof($tmpHist0) > 0) {
            foreach ($tmpHist0 as $row) {
                if (!in_array($row->transaksi_id, $trIDs)) {
                    $trIDs[] = $row->transaksi_id;
                    $tmpCol = array();
                    $content = blobDecode($row->values);
                    //                                        arrPrint($content);
                    foreach ($trHeaders as $colName => $colLabel) {
                        if (isset($content[$colName])) {
                            $tmpCol[$colName] = $content[$colName];
                        }
                        else {
                            $tmpCol[$colName] = isset($row->$colName) ? $row->$colName : 0;
                        }

                    }
                    $trItems[] = $tmpCol;
                }
            }
        }
        //endregion
        //arrPrint($trItems);

        $accounts = array();
        $rels = array();
        $accountRecaps = array();

        $this->load->helper("he_mass_table");
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($settlConfig[$gID]) && sizeof($settlConfig[$gID]) > 0) {
                    foreach ($settlConfig[$gID] as $accID => $relPair) {
                        $accounts[$accID] = $accID;
                        foreach ($relPair as $comID => $comLabel) {
                            $rels[$accID][$comID] = $comLabel;
                        }
                    }
                }
            }
        }


        $names = array();
        $recaps = array();
        if (sizeof($trIDs) > 0) {
            if (sizeof($accounts) > 0) {
                foreach ($accounts as $accID => $rekName) {

                    if (isset($rels[$accID]) && sizeof($rels[$accID]) > 0) {

                        foreach ($rels[$accID] as $comID => $comLabel) {

                            if (!isset($names[$accID])) {
                                $names[$accID] = array();
                            }
                            if (!isset($recaps[$accID])) {
                                $recaps[$accID] = array();
                            }
                            $mdlName = "Com" . $comID;
                            $this->load->model("Coms/" . $mdlName);
                            $com = new $mdlName();

                            $com->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");


                            $defPosition = detectRekDefaultPosition($rekName);
                            $opPosition = $defPosition == "kredit" ? "debet" : "kredit";
                            $tmp = $com->fetchMovesByTransIDs($accID, $trIDs);

                            //                            echo $this->db->last_query();
                            if (sizeof($tmp) > 0) {
                                foreach ($tmp as $row) {
                                    if (!array_key_exists($row->extern_id, $names[$accID])) {
                                        $names[$accID][$row->extern_id] = $row->extern_nama;
                                    }
                                    if (!isset($recaps[$accID][$row->extern_id])) {
                                        $recaps[$accID][$row->extern_id] = array(
                                            "qty_in" => 0,
                                            "value_in" => 0,
                                            "qty_out" => 0,
                                            "value_out" => 0,
                                            "qty_saldo" => 0,
                                            "value_saldo" => 0,
                                        );
                                    }
                                    $recaps[$accID][$row->extern_id]['value_in'] += $row->$defPosition;
                                    $recaps[$accID][$row->extern_id]['value_out'] += $row->$opPosition;
                                    $recaps[$accID][$row->extern_id]['value_saldo'] = $row->$opPosition . "_akhir";

                                    $qtyField = "qty_" . $defPosition;
                                    $qtyOpField = "qty_" . $opPosition;
                                    if (isset($row->$qtyField)) {
                                        $recaps[$accID][$row->extern_id]['qty_in'] += $row->$qtyField;
                                        $recaps[$accID][$row->extern_id]['qty_out'] += $row->$qtyOpField;
                                        $recaps[$accID][$row->extern_id]['qty_saldo'] = $row->$qtyOpField . "_akhir";
                                    }

                                }
                                //                                echo "ada mutasinya<br>";
                            }
                            else {
                                //                                echo "TAK ada mutasinya<br>";
                            }
                        }
                    }


                }
            }

        }


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //
        //region prepare params for viewer
        $subTitle = lgTranslateTime($date1) . " to " . lgTranslateTime($date2);
        if ($date1 == $date2) {
            $subTitle = lgTranslateTime($date1);
        }


        $thisTr = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
        $thisTrName = isset($row->jenis_label) ? $row->jenis_label : $this->config->item("heTransaksi_ui")[$jenisTr]["label"];

        $data = array(
            "mode" => $this->uri->segment(2),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "subJenisTr" => strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $jenisTr,
            //            "trName"               => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "trName" => $thisTrName,
            "errMsg" => $this->session->errMsg,
            "title" => (isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName) . " (" . $this->session->login['nama'] . ")",
            "subTitle" => $subTitle,
            //            "pageCount"            => $numPages,
            //            "page"                 => $page,
            //            "pages"                => $pages,
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4),
            //            "availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),"availFilters"=>isset($this->config->item("heTransaksi_report")[$jenisTr]["availFilters"])?$this->config->item("heTransaksi_report")[$jenisTr]["availFilters"]:array(),
            "availFilters" => isset($availFilters) ? $availFilters : array("oleh_id" => "person"),
            "names" => $names,
            "recaps" => $recaps,
            "trHeaders" => $trHeaders,
            "trItems" => $trItems,
        );
        //endregion

        $this->load->view("history", $data);
    }

    public function viewHistory2()
    {

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $currYear = isset($_GET['y']) ? $_GET['y'] : date("Y");
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $subj
        // cekHitam($defaultStep . " " . $this->jenisTr);
        $steps = $this->config->item('heTransaksi_layout')[$this->jenisTr]['historicalReport'][$defaultStep]['tabs'];
        // arrPrint($steps);
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }
        // arrPrint($stepNames);
        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("jenis='" . $stID . "'");
        // if (isset($currentState)) {
        //     $tr->addFilter("jenis='" . $currentState . "'");
        // }

        //        $this->db->like('transaksi.dtime', $dateStr, 'after');
        $this->db->where("year(transaksi.dtime)='$currYear'");
        // $this->db->where("month(transaksi.dtime)='$currMonth'");
        $tmp = $tr->lookupJoined()->result();

        cekbiru($this->db->last_query());
        mati_disini();
        $trIds_ = array();
        $trIdDts = array();
        //region data dari registry untuk mendapatkan nilai nett1
        foreach ($tmp as $item) {
            $trIds_[$item->transaksi_id] = 1;
            $trIdDts[$item->transaksi_id]['dtime'] = $item->dtime;
            $trIdDts[$item->transaksi_id]['olehID'] = $item->oleh_id;
            $trIdDts[$item->transaksi_id]['cabangID'] = $item->cabang_id;
            $trIdDts[$item->transaksi_id]['pihakID'] = ($item->suppliers_id < 1 ? $item->customers_id : $item->suppliers_id);
        }
        // arrPrint($trIdDtimes_);
        $trIds = array_keys($trIds_);
        $idList = implode(",", $trIds);
        if (strlen($idList) > 3) {

            $tr_2 = new MdlTransaksi();
            $tr_2->setFilters(array());
            $tr_2->addFilter("transaksi_id in '(" . $idList . ")'");
            $tr_2->addFilter("param ='items'");
            $registries = $tr_2->lookupRegistries()->result();
            // cekHitam($this->db->last_query());
            // arrPrint($registries);
            $subNett = array();
            $subHarga = array();
            foreach ($registries as $registry) {
                // cekMerah($registry->transaksi_id);
                $trDtime = $trIdDts[$registry->transaksi_id]["dtime"];
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                // cekMerah("$trDtime *** $trDtime_M");
                $regs = blobDecode($registry->values);
                // arrPrint($regs);
                // cekHijau($trDtime);
                // arrPrint($registry);

                $pihakIdDatasValues = array();
                foreach ($regs as $reg) {
                    $xsID = key_exists($sID, $trIdDts[$registry->transaksi_id]) ? $trIdDts[$registry->transaksi_id][$sID] : $reg[$sID];
                    // cekLime($xsID . " $trDtime_m" );
                    // arrPrint($reg);
                    // $pihakIdDatasValues[$reg['pihakID']] += $reg['nett1'];
                    // cekHitam($reg['pihakName'] . " " . $reg['pihakID'] . " == " . $reg['nett1']);
                    if (!isset($subNett[$trDtime_m][$xsID])) {
                        $subNett[$trDtime_m][$xsID] = 0;
                    }
                    if (isset($reg['nett1'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett1'] * $reg['jml']);
                    }
                    elseif (isset($reg['nett'])) {
                        $subNett[$trDtime_m][$xsID] += ($reg['nett'] * $reg['jml']);

                    }
                    else {
                        $subNett[$trDtime_m][$xsID] += (0);
                    }

                    // $subNett[$reg['pihakName']] += $reg['nett1'];

                    // cekHijau($reg['nett1'] . " net" );
                }
                // $pihakIdDatas['pihakID']
            }
        }
        //endregion

        $identifiers = $tr->fetchIdentifiers();


        $recaps = array();

        $dates = array();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($identifiers) > 0) {
                    $fulldate = substr($row->dtime, 0, 7);
                    $dates[$fulldate] = $fulldate;
                    $jenis = $row->jenis;
                    foreach ($identifiers as $iID => $iName) {


                        if (strlen($row->$iID) > 0 && strlen($row->$iName) > 0) {
                            //                            cekkuning("$iID/$iName");

                            if (!isset($names[$iID])) {
                                $names[$iID] = array();
                            }

                            $names[$iID][$row->$iID] = $row->$iName;
                            if (!isset($recaps[$jenis][$iID][$fulldate][$row->$iID])) {
                                $recaps[$jenis][$iID][$fulldate][$row->$iID] = array(
                                    "qty" => 0,
                                    "value" => 0,
                                );
                            }
                            // cekHitam($row->$iID);
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['qty'] += $row->produk_ord_jml;
                            // $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = ($row->produk_ord_jml * $row->produk_ord_hrg);
                            $recaps[$jenis][$iID][$fulldate][$row->$iID]['value'] = array_key_exists($row->$iID, $subNett[$fulldate]) ? $subNett[$fulldate][$row->$iID] : 0;
                        }


                    }

                }
            }
        }

        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        $data = array(
            "mode" => "recap",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "subTitle" => "monthly, " . $currYear,
            "times" => $months,
            "timeLabel" => "months",
            "names" => isset($names) ? $names : array(),
            "recaps" => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . "/viewDaily/" . $this->jenisTr,
            "historyPage" => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "?stID=" . $stID,
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
        );
        $this->load->view("activityReports", $data);

    }

    // function index(){
    //     // Load the member list view
    //     $this->load->view('activityReports');
    // }
    public function viewMonth()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        $reportingNetts = $this->config->item('heTransaksi_report')[$this->jenisTr]['reportingNett'];
        $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['fields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            $headers[] = array();
            $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        $steps = $reportingNetts['tabs'];
        foreach ($steps as $step) {
            isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fields);
        // arrPrint($steps);
        if ($this->session->login['cabang_id'] > 0) {
            $selectedCabang = "transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'";
        }
        else {
            $selectedCabang = "transaksi.cabang_id<>-1";
        }

        $stID = $this->uri->segment(3);
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        //        $defaultFilter = "year(dtime) = '$year' AND month(dtime) = '$month' AND cabang_id='" . $this->session->login['cabang_id'] . "' AND id_master>'0'";
        $defaultFilter = "year(dtime) = '$year' AND month(dtime) = '$month' AND $selectedCabang AND id_master>'0'";
        //region straight

        $tr->setFilters(array());
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->db->where("$defaultFilter");
        $tmps = $tr->lookupAll()->result();
        cekHijau($this->db->last_query());
        // arrPrint($fields);
        // arrPrint($fieldToshows);
        $lurusDatas = array();
        $trMainIds = array();
        if (sizeof($tmps) > 0) {
            foreach ($tmps as $tmp) {
                foreach ($fields as $kolom) {
                    $specs[$kolom] = $tmp->$kolom;
                }
                // foreach ($fieldToshows as $kolom => $fieldToshow) {
                //     $specs[$kolom] = $tmp->$kolom;
                // }

                if (in_array($tmp->jenis, $fieldJenis)) {
                    $lurusDatas[$tmp->jenis][$tmp->id] = $specs;
                    $trMainIds[] = $tmp->id;
                }

            }
        }
        //endregion

        // arrPrint($tmps);
        // arrPrint($lurusDatas);
        // mati_disini();

        // region return
        $tr->setFilters(array());
        $tr->addFilter("jenis_master ='" . $rJmaster . "'");
        $tr->addFilter("jenis ='" . $rJmaster . "'");
        $tr->db->where("$defaultFilter");
        $rTmps = $tr->lookupAll()->result();
        // cekMerah($this->db->last_query());

        $retDatas = array();
        $trRetIds = array();
        if (sizeof($rTmps) > 0) {
            foreach ($rTmps as $tmp) {
                foreach ($fields as $kolom => $fieldies) {

                }
                foreach ($fieldToshows as $kolom => $fieldToshow) {
                    $specs[$kolom] = $tmp->$kolom;
                }

                if ($tmp->jenis == $rJmaster) {
                    $retDatas[$tmp->jenis][$tmp->id] = $specs;
                    $trRetIds[] = $tmp->id;
                }
            }
        }
        // endregion return
        // arrPrint($rTmps);
        // arrPrint($retDatas);

        //region kompilasi data
        $jmlMain = sizeof($trMainIds);
        // $compDatas = array_merge($lurusDatas, $retDatas);
        $compDatas = ($lurusDatas + $retDatas);
        $jmlRet = sizeof($trRetIds);
        $jmlComp = sizeof($compDatas);
        // cekBiru("$jmlMain + $jmlRet = $jmlComp");
        //endregion kompilasi data
        // arrPrint($compDatas);

        $trIds = array_merge($trMainIds, $trRetIds);
        //region registries
        $listIds = implode("','", $trIds);
        // $tr->addFilter()
        // cekBiru($listIds);
        $tr->setFilters(array());
        $tr->addFilter("transaksi_id in ('" . $listIds . "')");
        //        $tr->addFilter("param ='main'");
        $tr_2->setJointSelectFields("main,transaksi_id");
        $regs = $tr->lookupDataRegistries()->result();
        // cekLime($this->db->last_query());
        // arrPrint($regs);
        $regDatas = array();
        foreach ($regs as $reg) {
            $trId = $reg->transaksi_id;
            // $refId = $reg->referenceID;
            $regValues = blobDecode($reg->main);
            // arrPrint($regValues);
            $regDatas[$trId]['nett1'] = $regValues['nett1'];
            $regDatas[$trId]['referenceID'] = isset($regValues['referenceID']) ? $regValues['referenceID'] : 0;
            // arrPrint($regValues);
        }
        //endregion
        // arrPrint($regDatas);
        /*
         * [13504] => Array
            (
                [nett1] => 16200000
            )
        */

        // mati_disini();


        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        $header['sales'] = "class='bg-info text-center'";
        $header['returns'] = "class='bg-info text-center'";
        $header['netto'] = "class='bg-info text-center'";


        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
            $headers[$num] = $header;
        }
        // endregion header
        // arrPrint($headers);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();
                foreach ($items as $transaksi_id => $item) {

                    // arrPrint($item);
                    // $step_number = $item->step_number;
                    // $tail_code = $item->tail_code;
                    // $tail_number = $item->tail_number;
                    $no++;
                    $specs['no']['value'] = $no;
                    $specs['no']['attr'] = "class='text-right'";
                    foreach ($fieldToshows as $kolom => $kolomAlias) {

                        if (isset($fieldFormat[$kolom])) {
                            $fValue = $fieldFormat[$kolom]($kolom, $item[$kolom]);
                        }
                        else {
                            $fValue = $item[$kolom];
                        }


                        if (isset($fieldLink[$kolom])) {
                            $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $item['id'] . "' > " . $fValue . "</a > ";
                        }
                        else {

                            $specs[$kolom]['value'] = $fValue;
                        }

                        $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                        $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                    }

                    $referenceID = $regDatas[$transaksi_id]['referenceID'];
                    $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                    //region builder saldo berjalan
                    $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                    //endregion

                    // region sales
                    $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                    $specs['sales']['attr'] = "class='text-right'";
                    $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                    // endregion sales

                    // region return
                    $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                    $specs['return']['attr'] = "class='text-right'";
                    $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                    // endregion return

                    // region netto berjalan
                    $specs['netto']['value'] = formatField("number", $netto);
                    $specs['netto']['attr'] = "class='text-right'";
                    // endregion netto berjalan

                    // arrPrint($specs);
                    // arrPrint($steps);
                    if ($trJenis == "982") {
                        $rSpecs[] = $specs;
                    }
                    if ($trJenis == "582spd") {
                        $spdSpecs[] = $specs;
                    }
                    // $compSpecs = array();
                    // foreach ($steps as $num => $nSpec) {
                    //     // arrPrint($nSpec);
                    //     $nJenis = $nSpec['jenis'];
                    //     if ($nJenis == $trJenis) {
                    //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                    //         if($nJenis == "582spd"){
                    //             $spdSpecs = $specs;
                    //             // arrPrint($compSpecs);
                    //             arrPrint($rSpecs);
                    //             cekHere();
                    //         }
                    //         else{
                    //             $compSpecs = $specs;
                    //         }
                    //
                    //         $compSpecs2 = $rSpecs + $spdSpecs;
                    //         $bodies[$num][] = $compSpecs2;
                    //     }
                    //     // else{
                    //     //     $bodies[$num][] = $specs;
                    //     // }
                    //     arrPrint($rSpecs);
                    // }
                }
            }
            //endregion


            foreach ($steps as $num => $nSpec) {
                $bodies[$num] = array_merge($spdSpecs, $rSpecs);
            }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        $data = array(
            "mode" => "reporting",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "subTitle" => "",
            // "times"            => $months,
            "tblHeadings" => $headers,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . " / " . $this->uri->segment(2) . " / " . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . " / viewDaily / " . $this->jenisTr,
            "historyPage" => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = " . $stID,
            "stepNames" => $stepNames,
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewHistory()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $historicalReports = $this->config->item('heTransaksi_report')[$this->jenisTr]['historicalReport'][$defaultStep];

        $fields = array();
        $headers = array();
        $bodies = array();
        foreach ($historicalReports['fields'] as $field => $fChilds) {
            $headers[] = array();
            $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }
        }
        $steps = $historicalReports['tabs'];

        // arrPrint($historicalReports['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fields);
        // arrPrint($steps);


        $stID = $this->uri->segment(4);
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        // $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->setFilters(array());
        $tr->db->where("id_master>'0' AND jenis='" . $stID . "'");

        $data = $row = array();

        // Fetch member's records
        $tmps = $tr->lookupAll()->result();
        // cekHijau($this->db->last_query());

        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
            $headers[$num] = $header;
        }
        // $headers[2] = $header;
        // endregion header

        // arrPrintWebs($tmps);
        //region bodies
        $no = 0;
        foreach ($tmps as $item) {
            $step_avail = $item->step_avail;
            $step_number = $item->step_number;
            $tail_code = $item->tail_code;
            $tail_number = $item->tail_number;
            $no++;
            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            foreach ($fieldToshows as $kolom => $kolomAlias) {

                if (isset($fieldFormat[$kolom])) {
                    $fValue = $fieldFormat[$kolom]($kolom, $item->$kolom);
                }
                else {
                    $fValue = $item->$kolom;
                }


                if (isset($fieldLink[$kolom])) {
                    $specs[$kolom]['value'] = "<a href='" . base_url() . $fieldLink[$kolom] . $item->id . "'>" . $fValue . "</a>";
                }
                else {

                    $specs[$kolom]['value'] = $fValue;
                }

                $warna = (($kolom == "trash") && ($fValue == 0)) ? "text-red" : "";

                $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";
            }

            $bodies[1][] = $specs;

            /*-----------------------------------
             * transaksi yg sukses
             * ---------------------------------*/
            if (($item->step_current > 0) && ($item->step_current <= $step_avail)) {
                $bodies[2][] = $specs;
            }

            /*-----------------------------------
             * transaksi yg dihapus
             * ---------------------------------*/
            if ($step_number == 0) {
                $bodies[3][] = $specs;
            }

            /*-----------------------------------
             * transaksi yg telah selesai
             * ---------------------------------*/
            if ($item->step_current == $step_avail) {
                // if (($tail_number == $step_avail)) {
                $bodies[4][] = $specs;
            }
            /*-----------------------------------
             * transaksi yg belum selesai
            * ---------------------------------*/
            if (($item->step_current > 0) && ($tail_number == $step_avail)) {
                // if (($tail_number == $step_avail)) {
                $bodies[5][] = $specs;
            }


        }
        //endregion
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        $data = array(
            "mode" => "historical",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "subTitle" => "",
            // "times"            => $months,
            "tblHeadings" => $headers,
            "tblBodies" => $bodies,
            "names" => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . "/viewDaily/" . $this->jenisTr,
            "historyPage" => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "?stID=" . $stID,
            "stepNames" => $stepNames,
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewSales()
    {
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        $reportingNetts = $this->config->item('report')['penjualan'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            $headers[] = array();
            $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        // $steps = $reportingNetts['tabs'];
        // foreach ($steps as $step) {
        //     isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        // }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fieldAttrHeader);
        // arrPrint($fields);
        // arrPrint($steps);
        // matiHere(__METHOD__ . " @" . __LINE__);

        $stID = $this->uri->segment(3);
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        $db2 = $this->load->database('report', TRUE);
        // region sales

        // $rp->setDebug(true);
        // $condites = array(
        //   "periode" => "bulanan",
        // );
        // $db2->where($condites);
        $rp->setCondites(array("subject_id" => $this->session->login['id'],
            "th" => $year,
            "bl" => $month
        ));
        $srScr = $rp->lookupSalesMonthly()->result();
        // arrPrint($srScr);

        foreach ($srScr as $dSources) {

            !isset($sumCabang) ? $sumCabang = array() : $sumCabang[$cabang_id] - +$nilai_af;
            !isset($sumSubject) ? $sumSubject = array() : $sumSubject[$subject_id] - +$nilai_af;
        }
        // endregion sales

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header
        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        $data = array(
            "mode" => "viewSales",
            "title" => key($this->config->item("report")),
            "subTitle" => "",
            // "times"            => $months,
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => "",
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            // "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            // "thisPage"         => base_url() . get_class($this) . " / " . $this->uri->segment(2) . " / " . $this->jenisTr,
            // "subPage"          => base_url() . get_class($this) . " / viewDaily / " . $this->jenisTr,
            // "historyPage"      => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = " . $stID,
            // "stepNames"        => $stepNames,
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewPreSalesAll()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');

            $condite = array("th" => $year,
                "bl" => $month
            );

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];

            $condite = array("th" => $year);
            $bulan = "$year-$month";
            $bulan_f = $year;
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');

            $condite = array("th" => $year,
                "bl" => $month
            );
            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }

        $reportingNetts = $this->config->item('report')['pre_penjualan'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['pre_penjualan_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['pre_penjualan_seller'];
        $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['pre_penjualan_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        // $steps = $reportingNetts['tabs'];
        // foreach ($steps as $step) {
        //     isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        // }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fieldAttrHeader);
        // arrPrint($fields);
        // arrPrint($steps);
        // matiHere(__METHOD__ . " @" . __LINE__);

        $stID = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        // $db2 = $this->load->database('report', TRUE);
        // region sales
        $srcSm = $sm->lookupSeller();
//        showLast_query("lime");
        $sMans = $srcSm->raws->result();
//        arrPrintWebs($sMans);
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }
        // arrPrint($srcSm);
        // arrPrint($slFields);
        // arrPrint($sellerIds);
        // arrPrint($sMans);
        // arrPrint($sellers);

        // $rp->setDebug(true);
        // $condite = array("th" => $year, "bl" => $month);
        // $condite = "th >= '$year' and bl >= '$month' ";
        // $condite = "th >= '$year' and bl >= '11' ";
        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : "";
        // arrPrint($condite);

        $rp->setCondites($condite);
        // $rp->setDebug(true);
        $srScr = $rp->lookupPreSalesMonthly()->result();
        // showLast_query("lime");
//        cekMerah($this->db->last_query());
        $cancelScr = $rp->lookupPreSalesCanceledMonthly()->result();
        // showLast_query("lime");
        // cekMerah($this->db->last_query());
        // endregion sales
        arrPrint($srScr);
        // arrPrint($cancelScr);
        // arrPrint($sFields);
        // arrPrint($cbFields);
        // arrPrint($pFieldSumrows);
        $koloms = array_unique(array_merge($cbFields, $sFields, $pFields));
        $subjects = array();
        $cabangs = array();
        $sumSubjects = array();

        // arrPrint($dSources);
        // arrPrint($koloms);
        //region pre So
        foreach ($srScr as $dSources) {
            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }

            //region summary cabang
            if (!isset($sumCabang[$cabang_id]["nilai_in"])) {
                $sumCabang[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_in"] += $nilai_in;

            if (!isset($sumCabang[$cabang_id]["nilai_ot"])) {
                $sumCabang[$cabang_id]["nilai_ot"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabang[$cabang_id]["nilai_af"])) {
                $sumCabang[$cabang_id]["nilai_af"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary subject
            if (!isset($sumSubject[$subject_id]["nilai_in"])) {
                $sumSubject[$subject_id]["nilai_in"] = 0;
            }
            $sumSubject[$subject_id]["nilai_in"] += $nilai_in;

            if (!isset($sumSubject[$subject_id]["nilai_ot"])) {
                $sumSubject[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubject[$subject_id]["nilai_af"])) {
                $sumSubject[$subject_id]["nilai_af"] = 0;
            }
            $sumSubject[$subject_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary object
            foreach ($pFieldSumrows as $sumField => $sumStat) {

                if (!isset($sumObject[$object_id][$sumField])) {
                    $sumObject[$object_id][$sumField] = 0;
                }
                $sumObject[$object_id][$sumField] += $$sumField;

            }
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject[$object_id]["object_kode"] = $object_kode;
            $sumObject[$object_id]["object_nama"] = $object_nama;
        }

        foreach ($sellers as $sId => $sNama) {
            $sumSubject[$sId]["subject_nama"] = $sNama;
        }
        //endregion


        $revertCancel = array(
            "unit_ot" => "unit_in",
            "unit_in" => "unit_ot",
            "nilai_in" => "nilai_ot",
            "nilai_ot" => "nilai_in",
            "nilai_af" => "nilai_afc",
            "unit_af" => "unit_afc",
        );
        // region caceled
        foreach ($cancelScr as $dSources) {
            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }

            //region summary cabang
            if (!isset($sumCabangCancel[$cabang_id]["nilai_in"])) {
                $sumCabangCancel[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabangCancel[$cabang_id]["nilai_in"] += $nilai_in;

            if (!isset($sumCabangCancel[$cabang_id]["nilai_ot"])) {
                $sumCabangCancel[$cabang_id]["nilai_ot"] = 0;
            }
            $sumCabangCancel[$cabang_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabang[$cabang_id]["nilai_in"])) {
                $sumCabang[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_in"] += $nilai_ot;

            if (!isset($sumCabangCancel[$cabang_id]["nilai_af"])) {
                $sumCabangCancel[$cabang_id]["nilai_af"] = 0;
            }
            $sumCabangCancel[$cabang_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary subject
            if (!isset($sumSubjectCancel[$subject_id]["nilai_in"])) {
                $sumSubjectCancel[$subject_id]["nilai_in"] = 0;
            }
            $sumSubjectCancel[$subject_id]["nilai_in"] += $nilai_in;

            if (!isset($sumSubject[$subject_id]["nilai_in"])) {
                $sumSubject[$subject_id]["nilai_in"] = 0;
            }
            $sumSubject[$subject_id]["nilai_in"] += $nilai_ot;

            // ==================

            if (!isset($sumSubjectCancel[$subject_id]["nilai_ot"])) {
                $sumSubjectCancel[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubjectCancel[$subject_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumSubject[$subject_id]["nilai_ot"])) {
                $sumSubject[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot"] += $nilai_in;

            // ==============

            if (!isset($sumSubjectCancel[$subject_id]["nilai_af"])) {
                $sumSubjectCancel[$subject_id]["nilai_af"] = 0;
            }
            $sumSubjectCancel[$subject_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary object
            foreach ($pFieldSumrows as $sumField_0 => $sumStat) {

                if (array_key_exists($sumField_0, $revertCancel)) {
                    $sumField = $revertCancel[$sumField_0];
                }
                else {
                    $sumField = $sumField_0;
                }

                if (!isset($sumObject_1[$object_id][$sumField])) {
                    $sumObject_1[$object_id][$sumField] = 0;
                }

                $sumObject_1[$object_id][$sumField] += $$sumField_0;

            }
            //endregion

            $sumObject_1[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumObject_1[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject_1[$object_id]["object_kode"] = $object_kode;
            $sumObject_1[$object_id]["object_nama"] = $object_nama . " " . $subject_id;
        }

        foreach ($sumObject_1 as $proId => $proSpecs) {
            $sumObject[$proId] = $proSpecs;
        }
        // endregion caceled

        foreach ($sumCabang as $cbId => $cbItems) {
            $sumNett = $cbItems["nilai_ot"] - $cbItems['nilai_in'];

            // cekHitam("$sumNett = ".$cbItems["nilai_in"]." - ". $cbItems['nilai_ot']);

            $sumCabang_2[$cbId] = $cbItems;
            $sumCabang_2[$cbId]["nilai_af"] = $sumNett;
        }

        $sumSubject_2 = array();
        foreach ($sumSubject as $subjId => $subjItems) {
            $nilai_ot = isset($subjItems["nilai_ot"]) ? $subjItems["nilai_ot"] : 0;
            $nilai_in = isset($subjItems['nilai_in']) ? $subjItems['nilai_in'] : 0;

            $sumSubjNett = $nilai_ot - $nilai_in;
            // $sumSubjNett = $subjItems["nilai_ot"] - $subjItems['nilai_in'];

            // cekHitam("$sumSubjNett = $nilai_ot - $nilai_in");

            $sumSubject_2[$subjId] = $subjItems;
            $sumSubject_2[$subjId]["nilai_af"] = $sumSubjNett;
        }

        $sumObject_2 = array();
        foreach ($sumObject as $objId => $objItems) {

            $unit_ot = isset($objItems["unit_ot"]) ? $objItems["unit_ot"] : 0;
            $unit_in = isset($objItems['unit_in']) ? $objItems['unit_in'] : 0;
            $nilai_ot = isset($objItems["nilai_ot"]) ? $objItems["nilai_ot"] : 0;
            $nilai_in = isset($objItems['nilai_in']) ? $objItems['nilai_in'] : 0;

            $sumObjNett = $nilai_ot - $nilai_in;
            $sumObjUnitNett = $unit_ot - $unit_in;
            // $sumSubjNett = $subjItems["nilai_ot"] - $subjItems['nilai_in'];

            // cekHitam("$sumObjUnitNett = $unit_ot - $unit_in");

            $sumObject_2[$objId] = $objItems;
            $sumObject_2[$objId]["nilai_af"] = $sumObjNett;
            $sumObject_2[$objId]["unit_af"] = $sumObjUnitNett;
        }

        // arrPrint($sumSubject);
        // arrPrintWebs($sumObject_1);
        // arrPrintWebs($sumObject);
        // arrPrintWebs($sumCabang);
        // arrPrint($sumCabang_2);
        // arrPrint($sumObjectCancel);
        // arrPrintWebs($sumObject_2);
        // arrPrint($sumObject);
        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        // arrPrint($sumCabang);
        // arrPrint($sumCabangCancel);
        // arrPrint($sumSubject);
        // matiHere(__METHOD__);
        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header
        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);
        $data = array(
            "mode" => "viewSales",
            "title" => $reportingNetts['title'],
            "subTitle" => "$bulan_f",
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "confReportObject" => $confReportObject,
            // "sumCabang"         => isset($sumCabang) ? $sumCabang : array(),
            "sumCabang" => isset($sumCabang_2) ? $sumCabang_2 : array(),
            // "sumSubject"        => isset($sumSubject) ? $sumSubject : array(),
            "sumSubject" => isset($sumSubject_2) ? $sumSubject_2 : array(),
            "sumObject" => isset($sumObject_2) ? array_filter($sumObject_2) : array(),
            // "sumObject"         => isset($sumObject) ? $sumObject : array(),
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewSalesAll()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');

            $condite = array("th" => $year,
                "bl" => $month
            );

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];

            $condite = array("th" => $year);
            $bulan = "$year-$month";
            $bulan_f = $year;
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');

            $condite = array("th" => $year,
                "bl" => $month
            );
            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }

        $reportingNetts = $this->config->item('report')['penjualan'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['penjualan_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['penjualan_seller'];
        $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['penjualan_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }
        // $steps = $reportingNetts['tabs'];
        // foreach ($steps as $step) {
        //     isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        // }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fieldAttrHeader);
        // arrPrint($fields);
        // arrPrint($steps);
        // matiHere(__METHOD__ . " @" . __LINE__);

        $stID = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        // $db2 = $this->load->database('report', TRUE);
        // region sales
        $srcSm = $sm->lookupSeller();
        // showLast_query("lime");
        $sMans = $srcSm->raws->result();
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }
        // arrPrint($srcSm);
        // arrPrint($slFields);
        // arrPrint($sellerIds);
        // arrPrint($sMans);
        // arrPrint($sellers);

        $rp->setDebug(true);
        // $condite = array("th" => $year, "bl" => $month);
        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : "";
        // arrPrint($condite);

        $rp->setCondites($condite);
        $srScr = $rp->lookupSalesMonthly()->result();
        // showLast_query("lime");
        // cekMerah($this->db->last_query());
        // endregion sales
        // arrPrint($srScr);
        // arrPrint($sFields);
        // arrPrint($cbFields);
        // arrPrint($pFieldSumrows);
        $koloms = array_unique(array_merge($cbFields, $sFields, $pFields));
        $subjects = array();
        $cabangs = array();
        $sumSubjects = array();

        // arrPrint($dSources);
        // arrPrint($koloms);
        foreach ($srScr as $dSources) {
            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }

            //region summary cabang
            if (!isset($sumCabang[$cabang_id]["nilai_in"])) {
                $sumCabang[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_in"] += $nilai_in;

            if (!isset($sumCabang[$cabang_id]["nilai_ot"])) {
                $sumCabang[$cabang_id]["nilai_ot"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabang[$cabang_id]["nilai_af"])) {
                $sumCabang[$cabang_id]["nilai_af"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary subject
            if (!isset($sumSubject[$subject_id]["nilai_in"])) {
                $sumSubject[$subject_id]["nilai_in"] = 0;
            }
            $sumSubject[$subject_id]["nilai_in"] += $nilai_in;

            if (!isset($sumSubject[$subject_id]["nilai_ot"])) {
                $sumSubject[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubject[$subject_id]["nilai_af"])) {
                $sumSubject[$subject_id]["nilai_af"] = 0;
            }
            $sumSubject[$subject_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary object
            foreach ($pFieldSumrows as $sumField => $sumStat) {

                if (!isset($sumObject[$object_id][$sumField])) {
                    $sumObject[$object_id][$sumField] = 0;
                }
                $sumObject[$object_id][$sumField] += $$sumField;

            }
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject[$object_id]["object_kode"] = $object_kode;
            $sumObject[$object_id]["object_nama"] = $object_nama;
        }

        foreach ($sellers as $sId => $sNama) {
            $sumSubject[$sId]["subject_nama"] = $sNama;
        }

        // arrPrint($sumSubject);
        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        // arrPrint($sumCabang);
        // arrPrint($sumSubject);

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header
        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);
        $data = array(
            "mode" => "viewSales",
            "title" => $reportingNetts['title'],
            "subTitle" => "$bulan_f",
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "confReportObject" => $confReportObject,
            "sumCabang" => $sumCabang,
            "sumSubject" => $sumSubject,
            "sumObject" => $sumObject,
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewPurchasingSpAll()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');

            $condite = array("th" => $year,
                "bl" => $month
            );

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];

            $condite = array("th" => $year);
            $bulan = "$year-$month";
            $bulan_f = $year;
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');

            $condite = array("th" => $year,
                "bl" => $month
            );
            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }
        $reportingNetts = $this->config->item('report')['pembelian_supplies'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['pembelian_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['pembelian_vendor'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }
        // $steps = $reportingNetts['tabs'];
        // foreach ($steps as $step) {
        //     isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        // }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fieldAttrHeader);
        // arrPrint($fields);
        // arrPrint($steps);
        // matiHere(__METHOD__ . " @" . __LINE__);

        $stID = $this->uri->segment(3);
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        $db2 = $this->load->database('report', TRUE);
        // region sales
        // $rp->setDebug(true);
        // $rp->setCondites(array("th" => $year, "bl" => $month));
        $rp->setCondites($condite);
        $srScr = $rp->lookupPurchasingSpMonthly()->result();
        // endregion sales
        // arrPrint($srScr);
        // arrPrint($sFields);
        // arrPrint($cbFields);
        $koloms = array_unique(array_merge($cbFields, $sFields));
        if (sizeof($srScr) < 1) {
            $sumCabang = array();
            $sumSubject = array();
        }
        $subjects = array();
        $cabangs = array();
        $sumSubjects = array();
        foreach ($srScr as $dSources) {
            // arrPrint($dSources);
            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }

            //region summary cabang
            if (!isset($sumCabang[$cabang_id]["nilai_in"])) {
                $sumCabang[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_in"] += $nilai_in;

            if (!isset($sumCabang[$cabang_id]["nilai_ot"])) {
                $sumCabang[$cabang_id]["nilai_ot"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabang[$cabang_id]["nilai_af"])) {
                $sumCabang[$cabang_id]["nilai_af"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary cabang
            if (!isset($sumSubject[$subject_id]["nilai_in"])) {
                $sumSubject[$subject_id]["nilai_in"] = 0;
            }
            $sumSubject[$subject_id]["nilai_in"] += $nilai_in;

            if (!isset($sumSubject[$subject_id]["nilai_ot"])) {
                $sumSubject[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubject[$subject_id]["nilai_af"])) {
                $sumSubject[$subject_id]["nilai_af"] = 0;
            }
            $sumSubject[$subject_id]["nilai_af"] += $nilai_af;
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
        }

        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        // arrPrint($sumCabang);
        // arrPrint($sumSubject);

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header
        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }

        // arrPrint($reportingNetts);
        // arrPrint($this->config->item("report"));
        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        $data = array(
            "mode" => "viewSales",
            "title" => $reportingNetts['title'],
            "subTitle" => $bulan_f,
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "sumCabang" => $sumCabang,
            "sumSubject" => $sumSubject,
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewPurchasingFgAll()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');

            $condite = array("th" => $year,
                "bl" => $month
            );

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];

            $condite = array("th" => $year);
            $bulan = "$year-$month";
            $bulan_f = $year;
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');

            $condite = array("th" => $year,
                "bl" => $month
            );
            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
        }

        $reportingNetts = $this->config->item('report')['pembelian_produk'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['pembelian_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['pembelian_vendor'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }
        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }
        // $steps = $reportingNetts['tabs'];
        // foreach ($steps as $step) {
        //     isset($step['jenis']) ? $fieldJenis[] = $step['jenis'] : "";
        // }

        // arrPrint($fieldJenis);
        // arrPrint($reportingNetts['fields']);
        // arrPrint($fieldToshows);
        // arrPrint($fieldAttr);
        // arrPrint($fieldAttrHeader);
        // arrPrint($fields);
        // arrPrint($steps);
        // matiHere(__METHOD__ . " @" . __LINE__);

        $stID = $this->uri->segment(3);
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        $db2 = $this->load->database('report', TRUE);
        // region sales
        // $rp->setDebug(true);
        // $rp->setCondites(array("th" => $year, "bl" => $month));
        $rp->setCondites($condite);
        $srScr = $rp->lookupPurchasingFgMonthly()->result();
        // endregion sales
        // arrPrint($srScr);
        // arrPrint($sFields);
        // arrPrint($cbFields);
        $koloms = array_unique(array_merge($cbFields, $sFields));

        if (sizeof($srScr) < 1) {
            $sumCabang = array();
            $sumSubject = array();
        }
        $subjects = array();
        $cabangs = array();
        $sumSubjects = array();
        foreach ($srScr as $dSources) {
            // arrPrint($dSources);
            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }

            //region summary cabang
            if (!isset($sumCabang[$cabang_id]["nilai_in"])) {
                $sumCabang[$cabang_id]["nilai_in"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_in"] += $nilai_in;

            if (!isset($sumCabang[$cabang_id]["nilai_ot"])) {
                $sumCabang[$cabang_id]["nilai_ot"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabang[$cabang_id]["nilai_af"])) {
                $sumCabang[$cabang_id]["nilai_af"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_af"] += $nilai_af;
            //endregion
            //region summary cabang
            if (!isset($sumSubject[$subject_id]["nilai_in"])) {
                $sumSubject[$subject_id]["nilai_in"] = 0;
            }
            $sumSubject[$subject_id]["nilai_in"] += $nilai_in;

            if (!isset($sumSubject[$subject_id]["nilai_ot"])) {
                $sumSubject[$subject_id]["nilai_ot"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubject[$subject_id]["nilai_af"])) {
                $sumSubject[$subject_id]["nilai_af"] = 0;
            }
            $sumSubject[$subject_id]["nilai_af"] += $nilai_af;
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
        }

        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        // arrPrint($sumCabang);
        // arrPrint($sumSubject);

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header
        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));
        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";
        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        // arrPrint($header);
        // arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        $data = array(
            "mode" => "viewSales",
            "title" => $reportingNetts['title'],
            "subTitle" => $bulan_f,
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "sumCabang" => $sumCabang,
            "sumSubject" => $sumSubject,
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewInvoice()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }

        $reportingNetts = $this->config->item('history')['invoice'];
        $this->load->model("Mdls/MdlCurrency");
        $jenisTr = "582";
        $selectedSTep = 5;
        $configUijenis = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiUi");
        $configLayoutjenis = loadConfigModulJenis_he_misc($jenisTr, "coTransaksiLayout");
        $historyFields = isset($configUijenis['historyFields'][$selectedSTep]) ? $configUijenis['historyFields'][$selectedSTep] : $configUijenis['shortHistoryFields'];
        $pairRegistries = isset($configUijenis['pairRegistries']) ? $configUijenis['pairRegistries'] : array();
        $pairTransaksi = isset($configUijenis['pairTransaksi']) ? $configUijenis['pairTransaksi'] : array();

        $historyFieldsExt = isset($configUijenis["extHistoryFields"][$selectedSTep]) ? $configUijenis["extHistoryFields"][$selectedSTep] : array();
        $customButton = isset($configLayoutjenis["customButton"][$selectedSTep]) ? $configLayoutjenis["customButton"][$selectedSTep] : array();
        $printValas = isset($configLayoutjenis["print_nvalas"]) ? $configLayoutjenis["print_nvalas"] : array();
        // arrPrint($reportingNetts['mdlFields']);
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['penjualan_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['penjualan_seller'];
        //        arrPrint($historyFields);

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {

            $headers[] = array();
            $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sumrow'])) {
                $fieldSumrow[$field] = $field;
            }

        }

        $header['no'] = "class='bg-info text-center'";
        foreach ($historyFields as $kolom => $kolomAlias) {
            // cekMerah("$kolom");
            // arrPrint($kolomAlias);
            if (is_array($kolomAlias)) {

                $header[$kolomAlias["label"]] = "class='bg-info text-center'";
            }
            else {

                $header[$kolomAlias] = "class='bg-info text-center'";
            }

        }

        $stID = $this->uri->segment(3);
        $tr = new MdlTransaksi();

        $cabang_id = $this->placeId;
        $jenis = "582";
        $jenisReturn = "982";

        $wheres = "month(dtime) = '$month' AND year(dtime) = '$year'";
        $this->db->where($wheres);
        if ($this->placeId != CB_ID_PUSAT) {

            $tr->addFilter("cabang_id='" . $this->placeId . "'");
        }
        $tr->addFilter("jenis='$jenis'");
        $tmpHist = $srcHi = $tr->lookupHistories(1000, 500, 1)->result();
        // showLast_query("lime");-

        //---referensi return penjualan---------------------------------
        $tr = New MdlTransaksi();
        $this->db->select(array("id",
            "indexing_registry"
        ));
        $tr->addFilter("id_master>0");
        $tr->addFilter("jenis='$jenisReturn'");
        $trTmpReturn = $tr->lookupAll()->result();
        $arrTransID_return = array();
        $arrTransID_return_reg = array();
        if (sizeof($trTmpReturn) > 0) {
            foreach ($trTmpReturn as $spec) {
                $id_return = $spec->id;
                $indexRegDecode = blobDecode($spec->indexing_registry);
                //                arrPrintPink($indexRegDecode);
                $arrTransID_return[] = $id_return;
                $arrTransID_return_reg[$id_return] = $indexRegDecode['main'];
            }
            //            arrPrintPink($arrTransID_return_reg);
            $tmpReg_return = array();
            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            //            $trReg->addFilter("id in ('" . implode("','", $arrTransID_return_reg) . "')");
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID_return) . "')");
            $trReg->setJointSelectFields("main,transaksi_id");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            $arrReturnData = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $rSpec) {
                    $val_decode = blobDecode($rSpec->main);
                    $arrReturnData[$val_decode['referenceID']] = array(
                        "referenceID_return" => $val_decode['referenceID'],
                        "referenceNomer_return" => $val_decode['referenceNomer'],
                        "nett1_return" => $val_decode['nett1'],
                        "nett2_return" => $val_decode['nett2'],
                        "ppn_return" => $val_decode['ppn'],
                        "disc_return" => $val_decode['disc'],
                        "jual_return" => $val_decode['jual'],
                    );
                    //                    arrPrint($val_decode);
                }
            }
        }
        //arrPrintPink($arrReturnData);

        //---referensi return penjualan---------------------------------


        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $sumValue = array();
        $sumValueManual = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransID_inv = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransID_inv[$row->id] = $row->id;
                    $arrTransTopID[] = $row->id_top;
                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        foreach ($hist as $hisSpec) {
                            $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                "step" => $hisSpec['step'],
                                "trID" => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }
                //arrprint($arrTransID);

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());
                //                $trReg->addFilter("param='main'");
                $trReg->setJointSelectFields("main,transaksi_id");
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                //                cekHere($this->db->last_query());
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        //                        $param = $regRow->param;
                        $tmpReg_result[$regRow->transaksi_id]['main'] = blobDecode($regRow->main);
                    }
                }


                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookupAll()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }


                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookupAll()->result();


                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    mati_disini();
                    foreach ($arrIdsHist as $trID => $histSpec) {
                        foreach ($histSpec as $step => $detailSpec) {
                            if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                            }
                            $arrTransMainHist[$trID][$step] = $detailSpec;
                        }
                    }
                }

            }

            $numb = 0;
            foreach ($tmpHist as $row) {

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // $id_hist2 = blobDecode($row->ids_his)[2];
                //                arrPrint($id_hist);
                // endregion ids_his

                $thisNomer = $row->nomer;
                $cabang_id = explode(".", $thisNomer)[1];
                $counterjenis = "$jenis|$cabang_id";

                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";

                //region memangil global counter
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrintWebs($counters);
                // cekBiru("$counterjenis");
                $counterGlobal = isset($counters['stepCode|placeID'][$counterjenis]) ? $counters['stepCode|placeID'][$counterjenis] : "9";
                // $counterIds_his_global = $counterIds_his['stepCode|placeID']["582spo|$cabang_id"];
                $cGlobals = digit_5($counterGlobal);
                // $cGlobal_spo = digit_5($counterIds_his_global);
                //endregion

                // arrPrint($counters);
                // cekHijau($row->id);
                // arrPrint($counterIds_his);

                if (sizeof($pairRegistries) > 0) {

                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            foreach ($eReg as $k => $v) {
                                if (!isset($row->$k)) {
                                    $row->$k = $v;
                                }
                            }
                        }
                    }
                    //                        cekHijau(":: SRN $row->referenceID ::");
                    //                        cekHijau(":: SRN $row->referenceNomer ::");
                    if (sizeof($pairTransaksi) > 0) {
                        if ($row->referenceID > 0) {
                            $trPair = new MdlTransaksi();
                            $trPair->addFilter("id='" . $row->referenceID . "'");
                            $trPairTmp = $trPair->lookupMainTransaksi()->result();
                            if (sizeof($trPairTmp) > 0) {
                                $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                        $keyPairs = $keyPair . "_" . $step;
                                        $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                    }
                                }
                            }
                        }
                    }
                }
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    // cekHijau("$fName");
                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            if (is_numeric($row->$key)) {
                                if (!isset($sumValue[$key])) {
                                    $sumValue[$key] = 0;
                                }
                                $sumValue[$key] += $row->$key;
                            }
                            $colValue .= isset($row->$key) ? formatField($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {

                        if (is_numeric(isset($row->$fName) ? $row->$fName : "")) {
                            if (!isset($sumValue[$fName])) {
                                $sumValue[$fName] = 0;
                            }
                            $sumValue[$fName] += $row->$fName;
                        }

                        //region nomer dengan global counter
                        if ($fName == "nomer") {
                            $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                        }
                        elseif ($fName == "nomer_top") {

                            $kolomValue_0s = formatField($fName, $row->$fName);
                            // $kolomValues = str_replace("</span>","-".$cGlobal_spo,$kolomValue_0s);
                            $kolomValues = showHistoriGlobalNumbers($row->ids_his, 1, true);;
                        }
                        else {
                            $kolomValues = ($fName != "no") && (isset($row->$fName)) ? formatField($fName, $row->$fName) : "";
                        }
                        //endregion

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                    }

                    if (is_array($fLabel)) {

                        if (isset($fLabel['step'])) {
                            $hisStep = $fLabel['step'];
                            $hisKey = $fLabel['key'];
                            $tNomer = $id_hist[$hisStep][$hisKey];

                            if ($hisKey == "nomer") {
                                $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true) : "";
                            }
                            else {
                                if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                    $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                    //                                    cekHitam(":: $getKey ::");
                                    $colValue = isset($row->$getKey) ? formatField("amount", $row->$getKey) : "";
                                }
                                else {
                                    $colValue = "-";
                                }
                            }
                            $logistic = "";
                            if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                                $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                                $logistic = $main['oleh_nama'];
                            }
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                $colValue = isset($row->$getKey) ? formatField($getKey, $row->$getKey) : "";
                            }
                            else {
                                $colValue = "-";
                            }
                        }
                    }

                    //                    $val_return = 0;
                    if ($fName == "return") {
                        $refStep = 4;
                        $refPL_id = $id_hist[$refStep]["trID"];
                        $val_return = isset($arrReturnData[$refPL_id]['nett1_return']) ? $arrReturnData[$refPL_id]['nett1_return'] : 0;
                        //                        cekKuning("$refPL_id :: $val_return");
                        $colValue = formatField("debet", $val_return);
                        if (!isset($sumValue[$fName])) {
                            $sumValue[$fName] = 0;
                        }
                        $sumValue[$fName] += $val_return;
                    }
                    if ($fName == "netto_return") {
                        $refStep = 4;
                        $refPL_id = $id_hist[$refStep]["trID"];
                        $val_return = isset($arrReturnData[$refPL_id]['nett1_return']) ? $arrReturnData[$refPL_id]['nett1_return'] : 0;
                        //                        cekHere($val_return);
                        $val_nett0 = $row->nett1 - $val_return;
                        $colValue = formatField("debet", $val_nett0);
                        if (!isset($sumValue[$fName])) {
                            $sumValue[$fName] = 0;
                        }
                        $sumValue[$fName] += $val_nett0;
                    }

                    if ($fName == "ids_his") {
                        // cekHitam($fName);
                        if (is_array($fLabel)) {
                            // arrPrint($fLabel);
                            $hisStep = $fLabel['step'];
                            $hisKey = $fLabel['key'];
                            // $hisLabel = $fLabel['label'];
                            // $tNomer = $id_hist[$hisStep][$hisKey];
                            // $jenisTrsub = explode(".", $tNomer)[0];
                            // $counterjenis = "$jenisTrsub|" . $this->placeId;
                            //
                            // $counters = blobDecode($id_hist[$hisStep]['counters']);
                            // // arrPrint($counterIds_his);
                            // // $counters = blobDecode($row->counters);
                            // $counterGlobal = $counters['stepCode|placeID'][$counterjenis];
                            // $cGcounter = digit_5($counterGlobal);

                            // $colValue_f = formatField($hisKey, $id_hist[$hisStep][$hisKey]);
                            // $colValue = str_replace("</span>", "-" . $cGcounter, $colValue_f);
                            $colValue = showHistoriGlobalNumbers($row->ids_his, $hisStep, true);;
                            // cekMerah("$counterjenis $placeId $jenisTrsub $cabang_id $counterGlobal");

                            $logistic = "";
                            if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                                $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                                $logistic = $main['oleh_nama'];
                            }
                        }
                    }

                    if ($fName == "no") {
                        $colValue = formatField($fName, $numb);
                    }

                    $tmp['logistic'] = (isset($logistic)) && ($logistic != null) ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;

                    $tmp[$fName] = $colValue;

                    $tmp1["id"] = $row->id;
                }

                if (sizeof($arrCurrency) > 0) {
                    $valas = "";
                    $valas .= "<div class='btn-group'>";
                    $valas .= "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                    $valas .= "<i class='fa fa-print'></i>";
                    $valas .= "<span class='caret'></span>";
                    $valas .= "</button>";

                    $valas .= "<ul style='background:#cde8ff;' class='dropdown-menu dropdown-menu-right'>";
                    foreach ($arrCurrency as $arrV) {
                        $nama = $arrV->nama;
                        $nomer = $row->nomer;
                        $nilai = number_format($arrV->exchange, 0);
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . base_url() . "Transaksi/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }
                $arrayHistory[] = $tmp;
                $arrayHistory_ids[] = $tmp1;
            }
            // arrPrint($sumValue);
        }
        // showLast_query("lime");

        //         arrPrint($sumValueManual);
        //         arrPrint($sumValue);
        // arrPrint($srcHi);

        if (sizeof($srcHi) > 0) {
            $no = 0;
            $specs = array();
            // foreach ($srcHi as $items) {
            foreach ($arrayHistory as $items) {
                $items = (object)$items;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($historyFields as $kolom => $kolomAlias) {
                    // arrPrint($kolomAlias);
                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }
                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {
                        $specs[$kolom]['value'] = $fValue;
                    }
                    //region summary footer
                    if (in_array($kolom, $fieldSumrow)) {
                        if (!isset($sum[$kolom])) {
                            $sum[$kolom] = 0;
                        }
                        $sum[$kolom] += $fValue;
                    }
                    //endregion
                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";
                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "style='white-space: nowrap;' class='text-left $warna'";
                }

                $bodies[] = $specs;
            }
        }
        else {
            $bodies = array();
        }
        // arrPrint($bodies);
        // arrPrint($sumValue);

        $footers = array();
        // $sumNetto = $sumSale - $sumReturn;
        // $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        // $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        // $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        // $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        // $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";

        $strMonth = strtoupper(namaBulan()[$month]);
        $data = array(
            "mode" => "viewInvoice",
            "title" => "Log / Urut " . key($this->config->item("history")),
            "subTitle" => $year . "-" . $strMonth,
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            // "times"            => $months,
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "sumValue" => $sumValue,
            "historyFields" => $historyFields,
            "names" => isset($names) ? $names : array(),
            // "recaps"           => $recaps,
            "jenisTr" => $this->jenisTr,
            "trName" => "",
            // "availFilters"     => $availFilters,
            // "defaultFilter"    => $defaultFilter,
            // "selectedFilter"   => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            // "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            // "thisPage"         => base_url() . get_class($this) . " / " . $this->uri->segment(2) . " / " . $this->jenisTr,
            // "subPage"          => base_url() . get_class($this) . " / viewDaily / " . $this->jenisTr,
            // "historyPage"      => base_url() . "Transaksi / viewHistory / " . $this->jenisTr . " ? stID = " . $stID,
            // "stepNames"        => $stepNames,
            // "defaultStep"      => $defaultStep,
            // "selectedStep"     => $selectedStep,
            // "addLink"          => $addLink,
        );
        $this->load->view("activityReports", $data);
    }

    public function viewSalesRealization()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        $reportingNetts = $this->config->item('report')['realisasi_so'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['realisasi_so_cabang'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['realisasi_so_seller'];
        $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['realisasi_so_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $fieldSumrows[$field] = $fChilds['sum_rows'];
            }

        }
        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }
        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }

        $stID = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();

        //region seller datas
        $srcSm = $sm->lookupSeller();
        // showLast_query("lime");
        $sMans = $srcSm->raws->result();
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }
        //endregion

        // $rp->setDebug(true);
        $condite = array("th" => $year,
            "bl" => $month
        );
        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : "";
        // arrPrint($condite);

        $rp->setCondites($condite);
        $rp->setDebug(true);
        // $rp->db2->limit(2);
        // $rp->setLimit(1);
        $srScr = $rp->lookupPreSalesMonthly()->result();
        // showLast_query('merah');
        $cancelScr = $rp->lookupPreSalesCanceledMonthly()->result();
        // showLast_query('kuning');
        $saleScr = $rp->lookupSalesMonthly()->result();
        // showLast_query('hijau');

        // arrPrint($srScr);
        // arrPrint($cancelScr);
        // arrPrint($saleScr);
        //region prepare data tahp 2
        //region spo
        $replacecer = array(
            "unit_ot" => "unit_ot_spo",
            "unit_in" => "unit_in_spo",
            "unit_af" => "unit_af_spo",
            "nilai_ot" => "nilai_ot_spo",
            "nilai_in" => "nilai_in_spo",
            "nilai_af" => "nilai_af_spo",
        );
        $srScr1 = array();
        foreach ($srScr as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $srScr1[] = (object)$items2;
        }
        //endregion

        //region cancel so
        $replacecer = array(
            "unit_ot" => "unit_ot_cl",
            "unit_in" => "unit_in_cl",
            "unit_af" => "unit_af_cl",
            "nilai_ot" => "nilai_ot_cl",
            "nilai_in" => "nilai_in_cl",
            "nilai_af" => "nilai_af_cl",
        );
        $cancelScr1 = array();
        foreach ($cancelScr as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $cancelScr1[] = (object)$items2;
        }
        //endregion

        //region spd
        $replacecer = array(
            "unit_ot" => "unit_ot_spd",
            "unit_in" => "unit_in_spd",
            "unit_af" => "unit_af_spd",
            "nilai_ot" => "nilai_ot_spd",
            "nilai_in" => "nilai_in_spd",
            "nilai_af" => "nilai_af_spd",
        );
        $saleScr1 = array();
        foreach ($saleScr as $items) {
            $items2 = array();
            foreach ($items as $item_key => $item_value) {
                $new_key = array_key_exists($item_key, $replacecer) ? $replacecer[$item_key] : $item_key;
                $items2[$new_key] = $item_value;
            }
            $saleScr1[] = (object)$items2;
        }
        //endregion

        // arrPrintWebs($srScr1);
        // arrPrint($cancelScr1);
        // arrPrintWebs($saleScr1);
        //
        // matiHere();
        $koloms = array_unique(array_merge($cbFields, $sFields, $pFields));
        $allDatas = array_merge($srScr1, $cancelScr1, $saleScr1);

        foreach ($allDatas as $item) {

            $subjectNamas[$item->subject_id] = $item->subject_nama;
            $objects[$item->object_id] = $item->object_nama;
            $objectKodes[$item->object_id] = $item->object_kode;
            $cabangs[$item->cabang_id] = $item->cabang_nama;
        }

        // arrPrint($cbFields);
        // arrPrint($koloms);
        // arrPrint($allDatas);

        foreach ($allDatas as $dSources) {
            // arrPrintWebs($dSources);
            foreach ($koloms as $kolom) {
                $$kolom = isset($dSources->$kolom) ? trim($dSources->$kolom) : "";
            }

            //region summary cabang
            if (!isset($sumCabang[$cabang_id]["nilai_ot_spo"])) {
                $sumCabang[$cabang_id]["nilai_ot_spo"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot_spo"] += $nilai_ot_spo;

            if (!isset($sumCabang[$cabang_id]["nilai_ot_cl"])) {
                $sumCabang[$cabang_id]["nilai_ot_cl"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot_cl"] += $nilai_ot_cl;

            if (!isset($sumCabang[$cabang_id]["nilai_ot_spd"])) {
                $sumCabang[$cabang_id]["nilai_ot_spd"] = 0;
            }
            $sumCabang[$cabang_id]["nilai_ot_spd"] += $nilai_ot_spd;
            //endregion
            //region summary subject
            if (!isset($sumSubject[$subject_id]["nilai_ot_spo"])) {
                $sumSubject[$subject_id]["nilai_ot_spo"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot_spo"] += $nilai_ot_spo;

            if (!isset($sumSubject[$subject_id]["nilai_ot_cl"])) {
                $sumSubject[$subject_id]["nilai_ot_cl"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot_cl"] += $nilai_ot_cl;

            if (!isset($sumSubject[$subject_id]["nilai_ot_spd"])) {
                $sumSubject[$subject_id]["nilai_ot_spd"] = 0;
            }
            $sumSubject[$subject_id]["nilai_ot_spd"] += $nilai_ot_spd;
            //endregion
            //region summary object
            foreach ($pFieldSumrows as $sumField => $sumStat) {

                if (!isset($sumObject[$object_id][$sumField])) {
                    $sumObject[$object_id][$sumField] = 0;
                }
                $sumObject[$object_id][$sumField] += $$sumField;

            }
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject[$object_id]["object_kode"] = $object_kode;
            $sumObject[$object_id]["object_nama"] = $object_nama;
        }

        // arrPrint($sumCabang);
        foreach ($sumCabang as $cbId => $cbItems) {
            $sumNettSpo = $cbItems["nilai_ot_spo"] - $cbItems['nilai_ot_cl'];
            $sumNett = $sumNettSpo - $cbItems['nilai_ot_spd'];

            // cekHitam("$sumNett = ".$cbItems["nilai_ot_spo"]." - ". $cbItems['nilai_ot_cl']." - ". $cbItems['nilai_ot_spd']);

            $sumCabang_2[$cbId] = $cbItems;
            $sumCabang_2[$cbId]["nilai_ne_spo"] = $sumNettSpo;
            $sumCabang_2[$cbId]["nilai_af_spo"] = $sumNett;
        }

        foreach ($sumSubject as $suId => $suItems) {
            $sumNettSpo = $suItems["nilai_ot_spo"] - $suItems['nilai_ot_cl'];
            $sumNett = $sumNettSpo - $suItems['nilai_ot_spd'];

            $sumSubject_2[$suId] = $suItems;
            $sumSubject_2[$suId]["nilai_ne_spo"] = $sumNettSpo;
            $sumSubject_2[$suId]["nilai_af_spo"] = $sumNett;
        }

        foreach ($sumObject as $obId => $obItems) {
            $sumNettSpo = $obItems["nilai_ot_spo"] - $obItems['nilai_ot_cl'];
            $sumNett = $sumNettSpo - $obItems['nilai_ot_spd'];

            $sumUnitNettSpo = $obItems["unit_ot_spo"] - $obItems['unit_ot_cl'];
            $sumUnitNett = $sumUnitNettSpo - $obItems['unit_ot_spd'];

            $sumObject_2[$obId] = $obItems;
            $sumObject_2[$obId]["nilai_ne_spo"] = $sumNettSpo;
            $sumObject_2[$obId]["nilai_af_spo"] = $sumNett;
            $sumObject_2[$obId]["unit_ne_spo"] = $sumUnitNettSpo;
            $sumObject_2[$obId]["unit_af_spo"] = $sumUnitNett;
        }

        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";

        }
        // endregion header


        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header


        if (sizeof($allDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($allDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        // $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                        $fValue = $fieldFormat[$kolom]($kolom, isset($items->$kolom) ? $items->$kolom : 0);
                    }
                    else {
                        $fValue = isset($items->$kolom) ? $items->$kolom : 0;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {
                        // cekHitam("$kolom");
                        if ($kolom == "unit_af_spo") {
                            $fValue = (isset($items->unit_ot_spo) ? $items->unit_ot_spo : 0) - (isset($items->unit_ot_cl) ? $items->unit_ot_cl : 0) - (isset($items->unit_ot_spd) ? $items->unit_ot_spd : 0);

                            $fValue = formatField($kolom, $fValue);
                        }
                        if ($kolom == "nilai_af_spo") {
                            $fValue = (isset($items->nilai_ot_spo) ? $items->nilai_ot_spo : 0) - (isset($items->nilai_ot_cl) ? $items->nilai_ot_cl : 0) - (isset($items->nilai_ot_spd) ? $items->nilai_ot_spd : 0);
                            $fValue = formatField($kolom, $fValue);
                        }
                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";

        // arrPrint($bodies);
        foreach ($bodies as $obId => $obItems) {
            // $sumNettSpo = $obItems["nilai_ot_spo"] - $obItems['nilai_ot_cl'];
            // $sumNett = $sumNettSpo - $obItems['nilai_ot_spd'];
            //
            // $sumUnitNettSpo = $obItems["unit_ot_spo"] - $obItems['unit_ot_cl'];
            // $sumUnitNett = $sumUnitNettSpo - $obItems['unit_ot_spd'];
            //
            // $sumObject_2[$obId] = $obItems;
            // $sumObject_2[$obId]["nilai_ne_spo"] = $sumNettSpo;
            // $sumObject_2[$obId]["nilai_af_spo"] = $sumNett;
            // $sumObject_2[$obId]["unit_ne_spo"] = $sumUnitNettSpo;
            // $sumObject_2[$obId]["unit_af_spo"] = $sumUnitNett;
        }


        $bulan = "$year-$month";
        $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);
        $data = array(
            "mode" => "viewSales",
            "title" => $reportingNetts['title'],
            "subTitle" => "$bulan_f",
            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "confReportObject" => $confReportObject,
            "sumCabang" => isset($sumCabang_2) ? $sumCabang_2 : array(),
            "sumSubject" => isset($sumSubject_2) ? $sumSubject_2 : array(),
            "sumObject" => isset($sumObject_2) ? $sumObject_2 : array(),
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewSalesRealizations()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        // $reportingNetts = $this->config->item('report')['realisasi_so'];
        $confAllReport = $this->config->item('report')['realisasi_allso_movement'];
        $confReportSubject = $this->config->item('report')['realisasi_so_movement'];
        // $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['realisasi_so_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        // foreach ($confReportSubject['mdlFields'] as $field => $fChilds) {
        //     // arrPrint($fChilds);
        //     // $headers[] = array();
        //     // $bodies[] = array();
        //     $fields[] = $field;
        //     if (isset($fChilds['label'])) {
        //         $fieldToshows[$field] = $fChilds['label'];
        //     }
        //     isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
        //     if (isset($fChilds['attr'])) {
        //         $fieldAttr[$field] = $fChilds['attr'];
        //     }
        //     if (isset($fChilds['link'])) {
        //         $fieldLink[$field] = $fChilds['link'];
        //     }
        //     if (isset($fChilds['format'])) {
        //         $fieldFormat[$field] = $fChilds['format'];
        //     }
        //     if (isset($fChilds['sum_rows'])) {
        //         $fieldSumrows[$field] = $fChilds['sum_rows'];
        //     }
        //
        // }
        foreach ($confReportSubject['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }
        // foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
        //     // arrPrint($fChilds);
        //     // $headers[] = array();
        //     // $bodies[] = array();
        //     $sFields[] = $field;
        //     if (isset($fChilds['label'])) {
        //         $sFieldToshows[$field] = $fChilds['label'];
        //     }
        //     isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
        //     if (isset($fChilds['attr'])) {
        //         $sFieldAttr[$field] = $fChilds['attr'];
        //     }
        //     if (isset($fChilds['link'])) {
        //         $sFieldLink[$field] = $fChilds['link'];
        //     }
        //     if (isset($fChilds['format'])) {
        //         $sFieldFormat[$field] = $fChilds['format'];
        //     }
        // }
        foreach ($confAllReport['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $fieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }

        $stSubject = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();

        //region seller datas
        $srcSm = $sm->lookupSeller();
        // showLast_query("lime");
        $sMans = $srcSm->raws->result();
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }
        //endregion

        // $rp->setDebug(true);
        $condite = array("th" => $year,
            "bl" => $month
        );
        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : "";
        // arrPrint($condite);

        // $rp->setCondites($condite);
        // $rp->setDebug(true);
        // $rp->db2->limit(2);
        // $rp->setLimit(1);
        // $srScr = $rp->lookupPrePenjualanMovement("cabang")->result();
        $srScr = $rp->lookupPrePenjualanMovement($stSubject)->result();
        showLast_query("lime");
        // ==========================================================

        $rp->setCondites($condite);

        $srScr2 = $rp->lookupPrePenjualanMovement($stSubject)->result();
        // arrPrintLime($srScr2);
        foreach ($srScr2 as $cbId => $cbItems) {
            // arrPrint($cbItems);
            foreach ($cbFieldToshows as $field => $flabel) {
                $$field = $cbItems->$field;

                // cekMerah("$field");
                $cbSpecs[$field] = $cbItems->$field;
            }
            // $sumNettSpo = $cbItems["nilai_ot_spo"] - $cbItems['nilai_ot_cl'];
            // $sumNett = $sumNettSpo - $cbItems['nilai_ot_spd'];

            // cekHitam("$sumNett = ".$cbItems["nilai_ot_spo"]." - ". $cbItems['nilai_ot_cl']." - ". $cbItems['nilai_ot_spd']);

            $sumCabang_2[$cbId] = $cbSpecs;
            $sumCabang_2[$cbId]["subject_nama"] = $subject_nama;
        }
        // arrPrint($sumCabang_2);
        // ==========================================================
        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header

        if (sizeof($srScr) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($srScr as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        // $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                        $fValue = $fieldFormat[$kolom]($kolom, isset($items->$kolom) ? $items->$kolom : 0);
                    }
                    else {
                        $fValue = isset($items->$kolom) ? $items->$kolom : 0;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {
                        // cekHitam("$kolom");
                        if ($kolom == "unit_af_spo") {
                            $fValue = (isset($items->unit_ot_spo) ? $items->unit_ot_spo : 0) - (isset($items->unit_ot_cl) ? $items->unit_ot_cl : 0) - (isset($items->unit_ot_spd) ? $items->unit_ot_spd : 0);

                            $fValue = formatField($kolom, $fValue);
                        }
                        if ($kolom == "nilai_af_spo") {
                            $fValue = (isset($items->nilai_ot_spo) ? $items->nilai_ot_spo : 0) - (isset($items->nilai_ot_cl) ? $items->nilai_ot_cl : 0) - (isset($items->nilai_ot_spd) ? $items->nilai_ot_spd : 0);
                            $fValue = formatField($kolom, $fValue);
                        }
                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }

                $bodies[] = $specs;

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";

        $confReport_ = "";
        $confReportObject = array();
        // $confReportSubject = array();

        $bulan = "$year-$month";
        $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);
        // arrPrintLime($confReport);
        $data = array(
            "mode" => "viewMovement",
            "title" => $confAllReport['title'],
            "subTitle" => "$bulan_f",
            "confAllReport" => $confAllReport,
            "confReportCabang" => $confReportSubject,
            "confReportSubject" => $confReportSubject,
            "confReportObject" => $confReportObject,
            "sumCabang" => isset($sumCabang_2) ? $sumCabang_2 : array(),
            "sumSubject" => isset($sumSubject_2) ? $sumSubject_2 : array(),
            "sumObject" => isset($sumObject_2) ? $sumObject_2 : array(),
            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,
            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewSalesRel()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        //arrPrintWebs($this->session->login);
        $fieldSelected = $this->config->item("realisasi");
        $valueGate = $fieldSelected["valueGate"];
        //        arrPrint($valueGate = $fieldSelected["valueGate"]);

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 5) ? $_GET['date2'] : date("Y-m-d");

        $date1_f = formatField("fulldate", $date1);
        $date2_f = formatField("fulldate", $date2);
        $dateRange = $date1_f . "  <b>s/d</b>  " . $date2_f;

        // cekBiru("$date2 $date2_f // $date1 ** $dateRange");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi_jenis='582spo'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        //----------------------------------------------
        if ($this->session->login['cabang_id'] == CB_ID_PUSAT) {

        }
        else {
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            //            $tr->addFilter("oleh_id='" . $this->session->login['id'] . "'");
        }
        //----------------------------------------------
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        $temp = $tr->lookupAll()->result();
        // showLast_query("merah");
        // arrPrintPink($temp);
        $listIDMaster = array();
        $listedMain = array();
        $listID = array();
        $mainValues = array();
        if (sizeof($temp) > 0) {
            foreach ($temp as $temp0) {
                $tmp = array();
                foreach ($fieldSelected["main"] as $field => $alias) {
                    $tmp[$field] = $temp0->$field;
                }
                $modul = isset($this->masterConfigUi[$temp0->jenis_master]['modul']) ? $this->masterConfigUi[$temp0->jenis_master]['modul'] : NULL;
                $tmp['modul'] = $modul;
                $tmp['modul_path'] = base_url() . "$modul/";
                $tmp['jenis_master'] = $temp0->jenis_master;

                $mainValues[$temp0->id] = $tmp;

                $listID[$temp0->id] = strlen($temp0->indexing_registry) > 10 ? blobDecode($temp0->indexing_registry) : array();
                $listedMain[] = $temp0->id;
            }
        }
        $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("id_top in ('" . implode("','", $listedMain) . "')");

        $childData = $tr->lookupAll()->result();
        // $tr->setJointSelectFields("transaksi_id, main");
        // $childData = $tr->lookupBaseDataRegistries($listedMain)->result();
        // showLast_query("lime");
        // arrPrint($childData);


        if (sizeof($childData) > 0) {
            if (sizeof($valueGate) > 0) {
                $gate = $valueGate['prosessing_time'];
                $gateArray = explode("-", $gate);
                //                arrPrint($gateArray);
            }
            $baseData = array();
            $regIDs = array();
            foreach ($childData as $childData0) {
                $tmp = array();
                foreach ($fieldSelected["fields"] as $field => $fieldLabels) {
                    if ($field == "indexing_registry") {
                        $val = blobDecode($childData0->$field)['main'];
                        $regIDs[] = $val;
                    }
                    else {
                        $val = isset($childData0->$field) ? $childData0->$field : "";
                    }

                    $tmp[$field] = $val;
                }
                // $val = blobDecode($childData0->main);


                $baseData[$childData0->id_top][$childData0->transaksi_jenis][] = $tmp;
            }

        }
        // arrPrint($baseData);
        //region hitung prosessing time
        $tmpProce = array();
        $dtimeArray = array();
        foreach ($baseData as $idTop => $jenisTmp) {
            foreach ($jenisTmp as $jn => $tmpValue) {
                $tmpVal = array();
                foreach ($tmpValue as $iData) {
                    //                    arrPrint($iData);
                    $tmpProce[$idTop][$jn]["dtime"] = $iData['fulldate'];

                    if (isset($iData['fulldate'])) {
                        $lbale = $jn . "_dtime";
                        $dtimeArray[$idTop][$lbale] = $iData['fulldate'];
                    }

                    //                    $tmpProce[$idTop][$jn."_dtime"] = array(
                    //                        "dtime" => $iData['fulldate'],
                    //                        "nomer" => $iData['nomer'],
                    //                    );
                }
                //                $tmpProce[$idTop] = $tmpVal;
            }
        }
        //        arrPrint($dtimeArray);
        //         arrPrint($tmpProce);
        $diferDays = array();
        foreach ($tmpProce as $mainID => $mainValuesday) {
            //            arrPrint($mainValuesday);
            $strtTime = isset($mainValuesday["582so"]) ? $mainValuesday["582so"]["dtime"] : "--";
            //            cekLime($strtTime);
            $endTime = isset($mainValuesday["582spd"]["dtime"]) && strlen($mainValuesday["582spd"]["dtime"]) > 5 ? $mainValuesday["582spd"]["dtime"] : $strtTime;
            $difere = getDayDifference($strtTime, $endTime);

            $diferDays[$mainID]['prosessing_time'] = $difere;

        }
        //arrPrint($diferDays);

        //endregion

        //region lookup registry
        //        $tr->setFilters(array());
        //        $tr->addFilter("id in '(".implode(",",array_filter($regIDs)).")'");
        //        $tmpReg = $tr->lookupRegistries()->result();
        //        $regMain = array();
        //        foreach($tmpReg as $tmpReg0){
        //            $tmpVal = blobDecode($tmpReg0->values);
        //            $tmp =array();
        //            foreach($fieldSelected["pair_registry"] as $field =>$fieldLabel){
        //                $tmp[$field] =  $tmpVal[$field];
        //            }
        //            $regMain[$tmpReg0->transaksi_id]=$tmp;
        //        }
        //endregion

        //region call all employee
        $this->load->model("Mdls/MdlEmployee_all");
        $e = new MdlEmployee_all();
        $tempEmployee = $e->lookupAll()->result();
        $employeeData = array();
        foreach ($tempEmployee as $empData) {
            $employeeData[$empData->id] = $empData->nama;
        }
        //        arrPrint($tempEmployee);
        //arrPrint($regMain);
        //arrPrint($regMain);
        //endregion

        $mainLabel = $fieldSelected["main"] + $fieldSelected["main_transaksi"];
        $mainValuese = array();
        foreach ($mainValues as $idx => $temp) {
            //             arrPrint($temp);
            //             mati_disini();
            // arrPrintWebs($diferDays[$idx]);
            // arrPrint($dtimeArray[$idx]);

            $difdays = isset($diferDays[$idx]) ? $diferDays[$idx] : array();
            $dtimesAr = isset($dtimeArray[$idx]) ? $dtimeArray[$idx] : array();

            $mainValuese[$idx] = $temp + $difdays + $dtimesAr;
        }
        // arrPrint($mainValuese);

        $strMonth = strtoupper(namaBulan()[$month]);

        $data = array(
            "mode" => "viewRel",
            "title" => "Sales Performance",
            "subTitle" => $dateRange,
            "mainLabel" => $mainLabel,
            "main_values" => $mainValuese,
            //            "main_values" =>$mainValues,
            "main_detail" => $baseData,
            //            "main_registry" =>$regMain,
            "main_registry" => array(),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "employeeData" => $employeeData,
            "deferenceDay" => $diferDays,

        );
        $this->load->view("activityReports", $data);
    }

    public function viewSalesRel2()
    {
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
        }
        else {
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
        }
        //cekLime($year."". $month);
        $fieldSelected = $this->config->item("realisasi");
        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi_jenis='582spo'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        $temp = $tr->lookupAll()->result();
        cekMerah($this->db->last_query());
        $listIDMaster = array();
        $listedMain = array();
        $listID = array();
        $mainValues = array();
        if (sizeof($temp) > 0) {
            foreach ($temp as $temp0) {
                //                arrPrint($temp0);
                $tmp = array();
                foreach ($fieldSelected["main"] as $field => $alias) {
                    $tmp[$field] = $temp0->$field;
                }
                $mainValues[$temp0->id] = $tmp;
                //                if( strlen($temp0->indexing_registry) >10){
                //                    $listID[$temp0->id] = blobDecode($temp0->indexing_registry);
                //                }
                $listID[$temp0->id] = strlen($temp0->indexing_registry) > 10 ? blobDecode($temp0->indexing_registry) : array();
                $listedMain[] = $temp0->id;
            }
        }
        $tr->setFilters(array());
        $tr->addFilter("id_top in '(" . implode(",", $listedMain) . ")'");
        $childData = $tr->lookupAll()->result();

        if (sizeof($childData) > 0) {
            $baseData = array();
            $regIDs = array();
            foreach ($childData as $childData0) {
                $tmp = array();
                foreach ($fieldSelected["fields"] as $field => $fieldLabels) {
                    if ($field == "indexing_registry") {
                        $val = blobDecode($childData0->$field)['main'];
                        $regIDs[] = $val;
                    }
                    else {
                        $val = $childData0->$field;
                    }
                    $tmp[$field] = $val;
                }
                $baseData[$childData0->id_top][$childData0->transaksi_jenis][] = $tmp;
            }
            //            arrPrint($baseData);
        }
        //arrPrint($regIDs);
        //        matiHere();
        //region lookup registry
        $tr->setFilters(array());
        $tr->addFilter("id in '(" . implode(",", array_filter($regIDs)) . ")'");

        $tmpReg = $tr->lookupRegistries()->result();
        //        cekLime($this->db->last_query());
        $regMain = array();
        foreach ($tmpReg as $tmpReg0) {
            $tmpVal = blobDecode($tmpReg0->values);
            $tmp = array();
            foreach ($fieldSelected["pair_registry"] as $field => $fieldLabel) {
                $tmp[$field] = $tmpVal[$field];
            }
            $regMain[$tmpReg0->transaksi_id] = $tmp;
        }

        //region call all employee
        $this->load->model("Mdls/MdlEmployee_all");
        $e = new MdlEmployee_all();
        $tempEmployee = $e->lookupAll()->result();
        $employeeData = array();
        foreach ($tempEmployee as $empData) {
            $employeeData[$empData->id] = $empData->nama;
        }
        //        arrPrint($tempEmployee);
        //arrPrint($regMain);
        //arrPrint($regMain);
        //endregion

        $mainLabel = $fieldSelected["main"] + $fieldSelected["main_transaksi"];
        //        arrPrint($mainLabel);
        $strMonth = strtoupper(namaBulan()[$month]);
        $data = array(
            "mode" => "viewRel2",
            "title" => "realisasi SO",
            "subTitle" => $year . "-" . $strMonth,
            "mainLabel" => $mainLabel,
            "main_values" => $mainValues,
            "main_detail" => $baseData,
            "main_registry" => $regMain,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "employeeData" => $employeeData,

        );
        $this->load->view("activityReports", $data);
    }

    // sama dengan view sales all yang dicompare dengan last year
    public function viewSalesAllCompared()
    {
        $compFields = array();
        if (isset($_GET['date'])) {
            $year = formatTanggal($_GET['date'], 'Y');
            $month = formatTanggal($_GET['date'], 'm');
            $last_year = $year - 1;
            $last_month = formatTanggal($_GET['date'], 'm');

            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan" => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        elseif (isset($_GET['year'])) {
            $year = $_GET['year'];
            $last_year = $year - 1;

            $conditeCompared = "((th='$year') or (th='$last_year'))";

            $bulan = "$year";
            $bulan_f = $year;

            $last_bulan = "$last_year";
            $last_bulan_f = $last_bulan;

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan" => "$bulan_f",
            );
            $comp = "th";
            $compFields[] = $comp;
            $sub_title = "tahun $bulan_f";
        }
        else {
            //            mati_disini("disini");
            $year = dtimeNow('Y');
            $month = dtimeNow('m');
            $last_year = $year - 1;
            $last_month = $month;

            $conditeCompared = "((th='$year' and bl='$month') or (th='$last_year' and bl='$last_month'))";

            $bulan = "$year-$month";
            $bulan_f = formatTanggal($bulan, 'Y F');
            $last_bulan = "$last_year-$last_month";
            $last_bulan_f = formatTanggal($last_bulan, 'Y F');

            $top_header = array(
                "$last_bulan" => "$last_bulan_f",
                "$bulan" => "$bulan_f",
            );
            $comp = "th-bl";
            $compFields[] = $comp;
            $sub_title = "bulan $bulan_f";
        }
        //        cekHitam("tahun $year, bulan $month :: last_tahun $last_year, last_bulan $last_month");


        $reportingNetts = $this->config->item('report')['penjualan'];
        $confReportCabang = $reportingSumCabang = $this->config->item('report')['penjualan_cabang_compared'];
        $confReportSubject = $reportingSumSubject = $reportingSumSeller = $this->config->item('report')['penjualan_seller_compared'];
        $confReportObject = $reportingSumObject = $reportingSumProduct = $this->config->item('report')['penjualan_produk'];
        // $rJmaster = $reportingNetts["returns"]["jenis_master"];

        $fields = array();
        $headers = array();
        $bodies = array();
        $fieldJenis = array();
        foreach ($reportingNetts['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $fields[] = $field;
            if (isset($fChilds['label'])) {
                $fieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $fieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $fieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $fieldFormat[$field] = $fChilds['format'];
            }

        }

        foreach ($reportingSumCabang['mdlFields'] as $field => $fChilds) {
            $cbFields[] = $field;
            if (isset($fChilds['label'])) {
                $cbFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $cbFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $cbFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $cbFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $cbFieldFormat[$field] = $fChilds['format'];
            }
        }


        foreach ($reportingSumSeller['mdlFields'] as $field => $fChilds) {
            // arrPrint($fChilds);
            // $headers[] = array();
            // $bodies[] = array();
            $sFields[] = $field;
            if (isset($fChilds['label'])) {
                $sFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $sFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $sFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $sFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $sFieldFormat[$field] = $fChilds['format'];
            }
        }


        foreach ($reportingSumObject['mdlFields'] as $field => $fChilds) {
            $pFields[] = $field;
            if (isset($fChilds['label'])) {
                $pFieldToshows[$field] = $fChilds['label'];
            }
            isset($fChilds['attrHeader']) ? $pFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            if (isset($fChilds['attr'])) {
                $pFieldAttr[$field] = $fChilds['attr'];
            }
            if (isset($fChilds['link'])) {
                $pFieldLink[$field] = $fChilds['link'];
            }
            if (isset($fChilds['format'])) {
                $pFieldFormat[$field] = $fChilds['format'];
            }
            if (isset($fChilds['sum_rows'])) {
                $pFieldSumrows[$field] = $fChilds['sum_rows'];
            }
        }


        $stID = $this->uri->segment(3);
        $sm = new MdlEmployeeCabang();
        $tr = new MdlTransaksi();
        $rp = new MdlReport();
        // $db2 = $this->load->database('report', TRUE);

        // region sales
        $srcSm = $sm->lookupSeller();
        // showLast_query("lime");
        $sMans = $srcSm->raws->result();
        $sKoloms = $srcSm->koloms;
        $slFields = array();
        foreach ($sKoloms as $field => $fieldParams) {
            $slFields[] = $field;
        }

        foreach ($sMans as $sMan) {
            foreach ($slFields as $kolom) {
                $$kolom = $sMan->$kolom;
            }

            $sellers[$id] = $nama;
        }


        $rp->setDebug(true);

        my_cabang_id() > 0 ? $condite["cabang_id"] = my_cabang_id() : $condite = array();


        $rp->setCondites($condite);
        $rp->setConditesCompared($conditeCompared);
        $srScr = $rp->lookupSalesMonthly()->result();
        //        cekKuning(sizeof($srScr));
        //         cekMerah($this->db->last_query());
        // endregion sales

        //         arrPrint($srScr);
        //         arrPrint($sFields);
        //         arrPrint($cbFields);
        //        arrPrint($compFields);
        // arrPrint($pFieldSumrows);
        $koloms = array_unique(array_merge($cbFields, $sFields, $pFields, $compFields));
        $subjects = array();
        $cabangs = array();
        $sumCabang = array();
        $sumCabangs = array();
        $sumSubject = array();
        $sumSubjects = array();
        $sumObject = array();
        $sumObjects = array();
        foreach ($srScr as $dSources) {
            $compEx = explode("-", $comp);
            if (sizeof($compEx) > 0) {
                if (sizeof($compEx) > 1) {
                    $val_add = $dSources->$compEx[0] . "-" . dateDigit($dSources->$compEx[1]);
                }
                else {
                    $val_add = $dSources->$compEx[0];
                }
            }
            else {
                $val_add = "none";
            }
            $dSources->$comp = $val_add;
            //            cekKuning("$comp -- $val_add");
            //            arrPrint($dSources);

            foreach ($koloms as $kolom) {
                $$kolom = trim($dSources->$kolom);
            }


            //region summary cabang
            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_in"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_in"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_in"] += $nilai_in;

            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_ot"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_ot"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_ot"] += $nilai_ot;

            if (!isset($sumCabangs[$cabang_id][$$comp]["nilai_af"])) {
                $sumCabangs[$cabang_id][$$comp]["nilai_af"] = 0;
            }
            $sumCabangs[$cabang_id][$$comp]["nilai_af"] += $nilai_af;
            //endregion


            //region summary subject
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_in"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_in"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_in"] += $nilai_in;
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_ot"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_ot"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_ot"] += $nilai_ot;
            if (!isset($sumSubjects[$subject_id][$$comp]["nilai_af"])) {
                $sumSubjects[$subject_id][$$comp]["nilai_af"] = 0;
            }
            $sumSubjects[$subject_id][$$comp]["nilai_af"] += $nilai_af;
            //endregion

            //region summary object
            foreach ($pFieldSumrows as $sumField => $sumStat) {

                if (!isset($sumObject[$object_id][$sumField])) {
                    $sumObject[$object_id][$sumField] = 0;
                }
                $sumObject[$object_id][$sumField] += $$sumField;

            }
            //endregion

            $sumCabang[$cabang_id]["cabang_nama"] = $cabang_nama;
            $sumSubject[$subject_id]["subject_nama"] = $subject_nama;
            $sumObject[$object_id]["object_kode"] = $object_kode;
            $sumObject[$object_id]["object_nama"] = $object_nama;
        }

        foreach ($sellers as $sId => $sNama) {
            $sumSubject[$sId]["subject_nama"] = $sNama;
        }

        // arrPrint($sumSubject);

        // region headerCabang
        $cbHeader['no'] = "class='bg-info text-center'";
        foreach ($cbFieldToshows as $kolom => $kolomAlias) {
            $cbHeader[$kolomAlias] = "class='bg-info text-center'";
        }

        // endregion header

        // $summaryCabang = array(
        //   "cabangs" => $cabangs,
        //   "cabangs" => $cabangs,
        // );
        // arrPrint($cabangs);
        //         arrPrint($sumCabang);
        //         arrPrint($sumCabangs);
        // arrPrint($sumSubject);

        $compDatas = $srScr;
        // arrPrint($compDatas);
        // mati_disini();

        // arrPrintWebs($fieldToshows );

        // region header
        $header['no'] = "class='bg-info text-center'";
        foreach ($fieldToshows as $kolom => $kolomAlias) {
            $header[$kolomAlias] = "class='bg-info text-center'";

        }
        // $header['sales'] = "class='bg-info text-center'";
        // $header['returns'] = "class='bg-info text-center'";
        // $header['netto'] = "class='bg-info text-center'";


        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        //     $headers[$num] = $header;
        // }
        // endregion header

        // arrPrint($header);

        // arrPrintWebs($fieldAttr);
        // arrPrintWebs($tmps);
        // mati_disini(__LINE__);
        // arrPrint($fieldToshows);
        // cekHitam(sizeof($compDatas));

        if (sizeof($compDatas) > 0) {
            //region bodies
            $no = 0;
            $netto = 0;
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
            $rSpecs = array();
            foreach ($compDatas as $trJenis => $items) {
                $step_avail = $trJenis;
                $specs = array();


                // arrPrint($item);
                // $step_number = $item->step_number;
                // $tail_code = $item->tail_code;
                // $tail_number = $item->tail_number;
                $no++;
                $specs['no']['value'] = $no;
                $specs['no']['attr'] = "class='text-right'";
                foreach ($fieldToshows as $kolom => $kolomAlias) {

                    if (isset($fieldFormat[$kolom])) {
                        $fValue = $fieldFormat[$kolom]($kolom, $items->$kolom);
                    }
                    else {
                        $fValue = $items->$kolom;
                    }


                    if (isset($fieldLink[$kolom])) {
                        $specs[$kolom] = " < a href = '" . base_url() . $fieldLink[$kolom] . $items['id'] . "' > " . $fValue . "</a > ";
                    }
                    else {

                        $specs[$kolom]['value'] = $fValue;
                    }

                    $warna = (($kolom == "trash") && ($fValue == 0)) ? "text - red" : "";

                    $specs[$kolom]['attr'] = isset($fieldAttr[$kolom]) ? $fieldAttr[$kolom] : "class='text-left $warna'";

                }

                // $referenceID = $regDatas[$transaksi_id]['referenceID'];
                // $nett1 = round($regDatas[$transaksi_id]['nett1'], 0);
                // //region builder saldo berjalan
                // $referenceID == 0 ? $netto += $nett1 : $netto -= $nett1;
                // //endregion

                // // region sales
                // $specs['sales']['value'] = $referenceID == 0 ? formatField("number", $nett1) : 0;
                // $specs['sales']['attr'] = "class='text-right'";
                // $referenceID == 0 ? $sumSale += $nett1 : $sumSale = 0;
                // // endregion sales

                // // region return
                // $specs['return']['value'] = $referenceID > 0 ? formatField("number", $nett1) : 0;
                // $specs['return']['attr'] = "class='text-right'";
                // $referenceID > 0 ? $sumReturn += $nett1 : $sumReturn = 0;
                // // endregion return

                // // region netto berjalan
                // $specs['netto']['value'] = formatField("number", $netto);
                // $specs['netto']['attr'] = "class='text-right'";
                // // endregion netto berjalan

                // arrPrint($specs);
                // arrPrint($steps);
                if ($trJenis == "982") {
                    $rSpecs[] = $specs;
                }
                if ($trJenis == "582spd") {
                    $spdSpecs[] = $specs;
                }
                // $compSpecs = array();
                // foreach ($steps as $num => $nSpec) {
                //     // arrPrint($nSpec);
                //     $nJenis = $nSpec['jenis'];
                //     if ($nJenis == $trJenis) {
                //     // if (($nJenis == $trJenis) && ($trJenis == "582spd") || ($trJenis == "982")) {
                //         if($nJenis == "582spd"){
                //             $spdSpecs = $specs;
                //             // arrPrint($compSpecs);
                //             arrPrint($rSpecs);
                //             cekHere();
                //         }
                //         else{
                //             $compSpecs = $specs;
                //         }
                //
                //         $compSpecs2 = $rSpecs + $spdSpecs;
                //         $bodies[$num][] = $compSpecs2;
                //     }
                //     // else{
                $bodies[] = $specs;
                //     // }
                //     arrPrint($rSpecs);
                // }

            }
            //endregion


            // foreach ($steps as $num => $nSpec) {
            //     $bodies[] = array_merge($spdSpecs, $rSpecs);
            // }
        }
        else {
            $sumSale = 0;
            $sumReturn = 0;
            $bodies = array();
        }
        $footers = array();
        $sumNetto = $sumSale - $sumReturn;
        $jmlFieldToshowa = sizeof($fieldToshows) + 1;
        $footers['summary'] = "class='bg-info text-center' colspan='$jmlFieldToshowa'";
        $footers[formatField('number', $sumSale)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumReturn)] = "class='bg-info text-right'";
        $footers[formatField('number', $sumNetto . ",0")] = "class='bg-info text-right'";

        // arrPrint($rSpecs);
        // arrPrint($spdSpecs);
        // arrPrint($bodies);
        // arrPrint($footers);
        // cekHijau($sumNetto);
        // mati_disini();
        // if(sizeof($bodies[3]) < 1){
        //
        //     $bodies = array();
        // }

        // arrPrint($bodies);
        //         mati_disini();

        // arrPrint($tmps);
        //        arrPrint($top_header);
        //         arrPrint($header);
        //         arrPrint($bodies);
        // foreach ($f?? as $item) {
        //
        // }


        // mati_disini();
        // $bulan = "$year-$month";
        // $bulan_f = formatTanggal($bulan, 'Y F');
        // cekMerah($bulan_f ." ". $bulan);

        //arrPrint($sumSubject);
        //arrPrint($sumSubjects);
        $data = array(
            "mode" => "viewSalesCompared",
            "title" => $reportingNetts['title'],
            "subTitle" => "$sub_title",

            "confReportCabang" => $confReportCabang,
            "confReportSubject" => $confReportSubject,
            "confReportObject" => $confReportObject,

            "sumCabang" => $sumCabang,
            "sumSubject" => $sumSubject,
            "sumObject" => $sumObject,

            "sumCabangs" => $sumCabangs,
            "sumSubjects" => $sumSubjects,
            "sumObjects" => $sumObjects,

            "tblTopHeadings" => isset($top_header) ? $top_header : array(),

            "tblHeadings" => $header,
            "tblBodies" => $bodies,
            "tblFooters" => $footers,

            "names" => isset($names) ? $names : array(),
            "jenisTr" => $this->jenisTr,
            "trName" => "",
        );
        $this->load->view("activityReports", $data);
    }

    public function viewPembelian()
    {
        $getDate = isset($_GET['date']) ? $_GET['date'] : dtimeNow('Y-m');
        $reg_bl = formatTanggal($getDate, 'm');
        $reg_th = formatTanggal($getDate, 'Y');


        $this->load->model("Mdls/MdlSupplier");
        $vd = new MdlSupplier();
        $koloms = array(
            "id",
            "nama",
        );

        $this->db->select($koloms);
        $this->db->order_by('nama', 'asc');
        // if (ipadd() == "202.65.117.72") {
        // $this->db->limit(10);
        // }
        $src_vendor = $vd->lookupAll()->result();
        // arrPrint($src_vendor);

        $this->load->model("Mdls/MdlReportSql");
        $rp = new MdlReportSql();

        $dStart = "$reg_th-01-01";
        $dStop = $reg_th != dtimeNow('Y') ? "$reg_th-12-31" : $reg_th . dtimeNow('-m-d');
        $jml_bln = $reg_th != dtimeNow('Y') ? 12 : dtimeNow('m');
        $condites = array(
            'date(dtime) >=' => $dStart,
            "date(dtime) <=" => $dStop,
        );
        $this->db->where($condites);
        $src_pembelian = $rp->callPembelianVendor();
        $this->db->where($condites);
        $src_pembelian_return = $rp->callPembelianVendorReturn();


        // showLast_query("lime");
        // cekKuning(sizeof($src_pembelian));
        // arrPrintPink($src_pembelian);
        // arrPrintPink($src_pembelian_return);
        $src_pembelians = array();
        foreach ($src_pembelian as $item) {
            $thn = $item->th;
            $bln = $item->bl;
            $ext_id = $item->extern_id;
            $src_pembelians[$ext_id][$thn][$bln] = $item;
        }
        $src_pembelian_returns = array();
        foreach ($src_pembelian_return as $item) {
            $thn = $item->th;
            $bln = $item->bl;
            $ext_id = $item->extern_id;
            $src_pembelian_returns[$ext_id][$thn][$bln] = $item;
        }
        // arrPrint($src_pembelians);
        // arrPrint($src_pembelian_returns);

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Pembelian per vendor",
            "subTitle" => "",
            "src_vendor" => $src_vendor,
            "src_pembelians" => $src_pembelians,
            "src_pembelian_returns" => $src_pembelian_returns,
            "getDate" => $getDate,
            "jml_bln" => $jml_bln,
            "thn" => $reg_th,
        );

        $this->load->view("activityReports", $data);
    }

    public function viewDetile()
    {
        $p = New Layout("jdl", "subJdl", "application/template/settlement.html");
        // $mainId = $this->uri->segment(3);
        $kolom = $this->uri->segment(3);
        $$kolom = $this->uri->segment(4);
        $trJenis = $this->uri->segment(5);
        $thn = $this->uri->segment(6);
        $bln = $this->uri->segment(7);

        $heTransaksiUi = $this->config->item("heTransaksi_ui");
        $jenisParams = array();
        foreach ($heTransaksiUi as $cJenis => $cParams) {
            // $jenisLabel[$cJenis] = $cParams["label"];
            $steps = $cParams['steps'];
            foreach ($steps as $cStep) {

                $jenisParams[$cStep['target']]['label'] = $cStep['label'];
                $jenisParams[$cStep['target']]['settlementMainFields'] = isset($cStep['settlementMainFields']) ? $cStep['settlementMainFields'] : array();
                $jenisParams[$cStep['target']]['settlementItemFields'] = isset($cStep['settlementItemFields']) ? $cStep['settlementItemFields'] : array();
            }
        }

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $condites = array(
            "$kolom" => $$kolom,
            "jenis" => $trJenis,
            "year(dtime)" => $thn,
            "month(dtime)" => $bln,
        );
        $srcTr = $tr->lookupByCondition($condites)->result();
        // showLast_query("lime");
        // matiHere(__LINE__);
        foreach ($srcTr as $item_transaksi) {
            $id = $item_transaksi->id;
            $jenis = $item_transaksi->jenis;
            $regItems = blobDecode($item_transaksi->indexing_registry)['items'];
            $regMains = blobDecode($item_transaksi->indexing_registry)['main'];
            // cekBiru($regItems);
            $trRegMainId[$id] = $regMains;
            $trRegItemsId[$id] = $regItems;
            $trJenisId[$jenis][] = $id;
        }
        $srcRegMain = $tr->lookupBaseRegistries($trRegMainId)->result();

        $srcRegItem = $tr->lookupBaseRegistries($trRegItemsId)->result();
        // showLast_query("kuning");
        // arrPrintWebs($trRegMainId);
        // arrPrintWebs($srcReg);
        // arrPrintWebs($srcRegMain);
        // arrPrintWebs($trJenisId);

        foreach ($srcRegItem as $i => $itemReg) {
            $itrId = $itemReg->transaksi_id;
            $mainReg = $srcRegMain[$i];
            $mtrId = $mainReg->transaksi_id;
            // arrPrintPink($mainReg);
            // mati_disini();
            $itemValues = blobDecode($itemReg->values);
            $mainValues = blobDecode($mainReg->values);
            // cekLime("$itrId $mtrId");
            // cekKuning($mainValues);
            // cekPink($itemValues);
            if ($mtrId == $itrId) {

                $mainRegistries[$mtrId] = $mainValues;
                $itemRegistries[$mtrId] = $itemValues;
            }
        }

        $conten_str = "";
        $strData = "";
        foreach ($trJenisId as $trJenis => $transaksiIds) {

            $trJenis_fields = isset($jenisParams[$trJenis]["settlementMainFields"]) ? $jenisParams[$trJenis]["settlementMainFields"] : array();
            $trJenis_items = isset($jenisParams[$trJenis]["settlementItemFields"]) ? $jenisParams[$trJenis]["settlementItemFields"] : array();
            $jmlKolomItem = sizeof($trJenis_items);

            /* --------------------------------------------------
             * header
             * --------------------------------------------------*/
            $tbHead = "";
            $sumBawah = array();
            if (sizeof($trJenis_fields) > 0) {
                $tbHead .= "<tr class='text-uppercase'>";
                $tbHead .= "<th class='text-center bg-info' rowspan='2'>no</th>";
                foreach ($trJenis_fields as $trField => $trFieldAttr) {
                    $hLabel = $trFieldAttr['label'];
                    $tbHead .= "<th class='text-center bg-info' rowspan='2'>$hLabel</thclas>";

                    $sumBawah[$trJenis] = isset($trFieldAttr['format']) ? 1 : 0;
                }
                if (sizeof($trJenis_items) > 0) {

                    // foreach ($trJenis_items as $trField => $trFieldAttr) {
                    //     $hLabel = $trFieldAttr['label'];
                    $tbHead .= "<th class='text-center bg-info' colspan='$jmlKolomItem'>rincian</th>";
                    // }
                }
                $tbHead .= "</tr>";

                $tbHead .= "<tr class='bg-info text-uppercase'>";
                foreach ($trJenis_items as $trJenis_item) {
                    $label = $trJenis_item['label'];
                    $attr = isset($trJenis_item['attr']) ? $trJenis_item['attr'] : "";

                    $tbHead .= "<th $attr>$label</th>";
                }
                $tbHead .= "</tr>";

            }
            // cekPink($sumBawah);
            /* --------------------------------------------------
             * body
             * --------------------------------------------------*/
            $tbBodi = "";
            $no = 0;
            $sumFields = array();
            foreach ($transaksiIds as $transaksiId) {
                $no++;
                $mainSpeks = $mainRegistries[$transaksiId];
                $trItems = $itemRegistries[$transaksiId];
                $tbBodi .= "<tr>";
                if (sizeof($trJenis_fields) > 0) {
                    $tbBodi .= "<td class='text-right' title='$transaksiId'>$no</td>";
                }
                /* ------------------------------------------------
                 * main
                 * ------------------------------------------------*/
                foreach ($trJenis_fields as $trField => $trFieldAttr) {
                    $mainValue = isset($mainSpeks[$trField]) ? $mainSpeks[$trField] : null;
                    $mainValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField, $mainValue) : $mainValue;
                    $attr = isset($trFieldAttr['attr']) ? $trFieldAttr['attr'] : "";

                    $tbBodi .= "<td $attr>$mainValue_f</td>";

                    if (isset($trFieldAttr['sumFields'])) {
                        if (!isset($sumFields[$trField])) {
                            $sumFields[$trField] = 0;
                        }
                        $sumFields[$trField] += $mainValue;
                    }
                    // sumFields
                }

                /* ------------------------------------------------
                 * items
                 * ------------------------------------------------*/
                $tbBodi .= "<td colspan='$jmlKolomItem'>";
                if (sizeof($trJenis_fields) > 0) {
                    $tbBodi .= "<table class='table table-condensed table-striped no-margin'>";
                    /* ------------------------------------------------
                     * items-body
                     * ------------------------------------------------*/
                    foreach ($trItems as $iKey => $trItem) {
                        if (sizeof($trJenis_items) > 0) {
                            $tbBodi .= "<tr>";
                            foreach ($trJenis_items as $trField => $trFieldAttr) {
                                $itemValue = isset($trItem[$trField]) ? $trItem[$trField] : 0;
                                $attr = isset($trFieldAttr['attr']) ? $trFieldAttr['attr'] : "";
                                // cekBiru($trFieldAttr['format'] . " $trField");
                                $itemValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField, $itemValue) : $itemValue;
                                // $itemValue_f = isset($trFieldAttr['format']) ? $trFieldAttr['format']($trField,$fValue) : $fValue;

                                $tbBodi .= "<td $attr>$itemValue_f</td>";
                            }
                            $tbBodi .= "</tr>";
                        }
                    }
                    $tbBodi .= "</table>";
                }
                else {
                    $tbBodi .= sizeof($trItems) . " <i> config ui $trJenis belum terdefinisi</i>";
                }
                $tbBodi .= "</td>";

                $tbBodi .= "</tr>";
            }
            // arrPrint($sumFields);
            /* ------------------------------------------------
             * items-footer
             * ------------------------------------------------*/
            $tbFooter = "";
            if (isset($sumBawah[$trJenis]) && ($sumBawah[$trJenis] > 0)) {
                $tbFooter .= "<tr>";
                $tbFooter .= "<th>-</th>";
                foreach ($trJenis_fields as $trField => $trFieldAttr) {
                    $fValue = isset($sumFields[$trField]) ? $sumFields[$trField] : "";

                    $fValue_f = isset($trFieldAttr['format']) && !isset($trFieldAttr['format_footer']) ? $trFieldAttr['format']($trField, $fValue) : $fValue;

                    $tbFooter .= "<th>$fValue_f</th>";
                }
                $tbFooter .= "<th colspan='$jmlKolomItem'>-</th>";
                $tbFooter .= "</tr>";
            }

            $tbInduk = "<table class='table table-condensed table-striped no-margin table-bordered'>";
            $tbInduk .= $tbHead;
            $tbInduk .= $tbBodi;
            $tbInduk .= $tbFooter;
            $tbInduk .= "</table>";

            // $link_cetak = base_url() . "Printing/settlement/$mainId/$trJenis";
            $btn_box = "<button type='button' class='btn btn-sm pull-right' data-widget='collapse' data-toggle='tooltip' title='' style='margin-right: 5px;' data-original-title='Collapse'><i class='fa fa-minus'></i></button>";
            // $btn_box .= "<button type='button' class='btn btn-sm pull-right' data-toggle='tooltip' title='cetak' style='margin-right: 5px;' data-original-title='cetak' onclick=\"window.open('$link_cetak','status=1,width=600');\"><i class='fa fa-print'></i></button>";
            $trJenis_label = isset($jenisParams[$trJenis]["label"]) ? $jenisParams[$trJenis]["label"] : $trJenis;
            $trJenis_debug = show_debuger() == 1 ? " :: $trJenis" : "";
            $p->setLayoutBoxHeadingProperty("title='$trJenis'");
            $p->setLayoutBoxHeadingCss("text-uppercase");
            $p->setLayoutBoxHeading($trJenis_label . $trJenis_debug, $btn_box);
            $p->setLayoutBoxCss("box-info");
            $p->setLayoutBoxBody(true);
            $p->setLayoutBoxBodyCss("no-padding");
            $strData .= $p->layout_box($tbInduk);
        }

        echo $strData;
    }

    public function viewPembelianProduk()
    {
        $getDate = isset($_GET['date']) ? $_GET['date'] : dtimeNow('Y-m');
        $reg_bl = formatTanggal($getDate, 'm');
        $reg_th = formatTanggal($getDate, 'Y');


        $this->load->model("Mdls/MdlProduk");
        $vd = new MdlProduk();
        $koloms = array(
            "id",
            "nama",
        );

        $this->db->select($koloms);
        $this->db->order_by('nama', 'asc');
        if (ipadd() == "202.65.117.72") {
            // $this->db->limit(20);
        }
        $src_vendor = $vd->lookupAll()->result();
        // arrPrint($src_vendor);
        // matiHere(__LINE__);

        $this->load->model("Mdls/MdlReport");
        $rp = new MdlReport();

        $dStart = "$reg_th-01-01";
        $dStop = $reg_th != dtimeNow('Y') ? "$reg_th-12-31" : $reg_th . dtimeNow('-m-d');
        $jml_bln = $reg_th != dtimeNow('Y') ? 12 : dtimeNow('m');
        $condites = array(
            'date(dtime) >=' => $dStart,
            "date(dtime) <=" => $dStop,
        );
        $rp->setCondites($condites);
        // $rp->setDebug(true);
        $rp->setPeriode("bulanan");
        $src_pembelian = $rp->lookupPembelianProdukAll()->result();
        // $this->db->where($condites);
        // $src_pembelian_return = $rp->callPembelianVendorReturn();


        // showLast_query("lime");
        // cekKuning(sizeof($src_pembelian));
        // arrPrintPink($src_pembelian);
        // arrPrintPink($src_pembelian_return);

        // matiHere(__LINE__);
        $src_pembelian_datas = array();
        foreach ($src_pembelian as $item) {
            $thn = $item->th;
            $bln = $item->bl;
            $ext_id = $item->subject_id;
            $src_pembelians[$ext_id][$thn][$bln] = $item;
            $src_pembelian_datas[$ext_id] = $item;
        }

        // foreach ($src_pembelian_return as $item) {
        //     $thn = $item->th;
        //     $bln = $item->bl;
        //     $ext_id = $item->extern_id;
        //     $src_pembelian_returns[$ext_id][$thn][$bln] = $item;
        // }
        // arrPrint($src_pembelians);
        // arrPrint($src_pembelian_returns);

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Pembelian per vendor",
            "subTitle" => "",
            "src_vendor" => $src_vendor,
            "src_pembelians" => $src_pembelians,
            // "src_pembelian_returns" => $src_pembelian_returns,
            "getDate" => $getDate,
            "jml_bln" => $jml_bln,
            "thn" => $reg_th,
        );

        // $this->load->view("activityReports", $data);

        // excelllll----------------
        $this->load->library('Excel');
        $ex = new Excel();

        $kolom_datas = array(
            "unit_in" => "qty beli",
            "unit_ot" => "qty return",
            "unit_af" => "qty net",
            "nilai_nppn_in" => "nilai beli",
            "nilai_nppn_ot" => "nilai return",
            "nilai_nppn_af" => "nilai nett",
        );
        $this->file = $_SERVER['SERVER_NAME'] . " pemelian produk bulanan tahun $reg_th";
        // region pairing data
        // $xLabels = $this->x['entries'];
        // $yLabels = $this->y['entries'];
        // $zLabels = $this->z['entries'];
        // arrPrint($zLabels);
        $number = 0;
        $datas = array();
        // $this->ix = "cabang";
        // $this->iy = "produk";
        // $this->iz = "hargaProduk";
        $dataSpec = array();
        // for ($i = 1; $i <= $jml_bln; $i++){
        //     foreach ($kolom_datas as $kolom_data => $labeling) {
        //         $key = "$kolom_data-$reg_th-$i";
        //
        //         $dataSpec[$key] = 0;
        //     }
        // }

        foreach ($src_vendor as $item) {
            // foreach ($src_pembelians as $item) {
            $pId = $item->id;
            $pNama = $item->nama;
            // $yId =>
            $yNames = $pNama;
            $number++;
            // cekHijau("$yNames");
            $yNames_f = html_entity_decode($yNames);
            $dataSpec['nama'] = "$yNames_f";

            for ($i = 1; $i <= $jml_bln; $i++) {
                foreach ($kolom_datas as $kolom_data => $labeling) {
                    $lbl = "$labeling $reg_th-$i";
                    $key = "$kolom_data-$reg_th-$i";

                    $dataSpec[$key] = 0;

                    $arrHeaderRel[$key] = array(
                        "label" => $lbl,
                        "type" => "integer",
                    );
                }
            }
            if (isset($src_pembelians[$pId])) {
                foreach ($src_pembelians[$pId] as $tahun => $src_pembelian_bl) {
                    foreach ($src_pembelian_bl as $bulan => $item) {
                        foreach ($kolom_datas as $kolom_data => $labeling) {

                            $lbl = "$labeling $tahun-$bulan";
                            $key = "$kolom_data-$tahun-$bulan";

                            $dataSpec[$key] = isset($item->$kolom_data) ? $item->$kolom_data : 0;

                            // $arrHeaderRel[$key] = array(
                            //     "label" => $lbl,
                            //     "type"  => "integer",
                            // );
                        }
                    }

                }
            }
            else {
                //    kosong
            }


            $datas[] = (object)$dataSpec;
        }
        // endregion pairing data

        // cekBiru($arrHeaderRel);
        // cekBiru(sizeof($arrHeaderRel));
        // arrPrint($datas);
        // matiHere(__LINE__);

        // $this->load->model('MdlEmployee');
        // $tm = new MdlEmployee();
        // $datasX = $tmpX = $tm->lookupAll()->result();
        // arrPrint($datasX);
        // matiHere(__LINE__);
        $headers = array(
                "nama" => array(
                    "label" => "Nama Produk",
                    "type" => "string",
                ),

            ) + $arrHeaderRel;
        // arrPrint($datas);
        // matiHere();
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
        // excelllll----------------
        echo lgShowSuccess("ok", "boss");
    }

    public function viewOutstandingSalesMonthly()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        cekLime($prevYear);
        $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        foreach ($steps as $num => $nSpec) {
            $stepNames[$nSpec['target']] = $nSpec['label'];
        }

        // $date = "2021-05-01";


        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",
            "1982" => "fullfill",
            "pending" => "out standing"
        );


        //endregion
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            $filter = array(
                "cabang_id" => $selectedCabang,
            );
            $tr->addFilter("cabang_id='$selectedCabang'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //region prevYear
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)<='$prevYear'");
        $pmp = $tr->lookupMainTransaksi()->result();
        $pRegIDS = array();
        $pMasterID = array();
        $oldData = 0;
        $strOld = array();
        if (sizeof($pmp) > 0) {
            foreach ($pmp as $pmp_0) {
                $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
                if (strlen($pIndexingMain) == 0) {
                    $oldData++;
                    $strOld[] = $pmp_0->id;
                }
                // arrPrint($pIndexingMain);
                // arrprint($indexingMain);
                $pRegIDS[] = "$pIndexingMain";
                $pMasterID[] = $pmp_0->id_master;
                $pTrIds_[$pmp_0->id] = 1;
                $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
                $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
                $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
                $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
                $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
                // arrPrint($indexingMain);
            }
            // arrprint($strOld);
            //auto updater indexing registry
            if (sizeof($strOld) > 0) {
                // matiHere();
                $m->setFilters(array());
                $m->setParam("transaksi_id");
                $m->setInParam($strOld);
                $m->setFields(array("id",
                    "transaksi_id",
                    "param",
                    "values"
                ));
                $m->setTableName("transaksi_registry");
                $tReg = $m->lookUpAll();
                $tRegID = array();
                if (sizeof($tReg) > 0) {
                    foreach ($tReg as $tReg_0) {
                        $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                        // arrPrint($regEntries);

                    }
                }
                $mong = new MdlMongoMother();
                $mong->setFilters(array());
                foreach ($strOld as $ii => $updID) {
                    // $mongListUpadte['update']['main'][] = array(
                    //     "where" => array("id" => $no,),
                    //     "value" => $arrData,
                    // );
                    $mong->setTableName("transaksi");
                    $valueRe = blobencode($tRegID[$updID]);
                    $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                    $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                }

            }


        }
        // matiHEre();
        if (sizeof($pMasterID) > 0) {

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($pMasterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $pListedSeller = array();
            $pListedSellerLabel = array();
            $pListedCustomer = array();
            $plistedCustomerLabel = array();
            foreach ($tempMaster as $tempMaster_0) {
                $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
            }


            // arrPrint($listedSeller);
        }
        $m->setFilters(array());
        $m->setParam("id");
        $m->setInParam($pRegIDS);
        $m->setFields(array("transaksi_id",
            "param",
            "values"
        ));
        $m->setTableName("transaksi_registry");
        $pReg = $m->lookUpAll();
        $pRegEntries = array();
        if (sizeof($pReg) > 0) {
            foreach ($pReg as $pParamReg) {
                $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
                // arrPrint($regEntries);

            }
        }
        // arrPrint($regEntries);
        $recaplist = array();
        $recaplistCust = array();
        $recaplistAll = array();
        foreach ($pmp as $pmp_1) {

            $pSellID = $pListedCustomer[$pmp_1->id_master];

            $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
            // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
            //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
            //
            // }
            // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
            //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
            // }

            // if(!isset($names['customers_id'][$sellID])){
            //     $names['customers_id'][$sellID]=
            // }
            foreach ($selectedTrans as $jj => $jjLabel) {
                if (!isset($recaplistAll[$pSellID]['prev'][$jj])) {
                    $recaplistAll[$pSellID]['prev'][$jj] = 0;
                }
            }
            // if (!isset($pRecaplistAll[$pSellID]['prev'][$pmp_1->jenis])) {
            //     $pRecaplistAll[$pSellID]['prev'][$pmp_1->jenis] = 0;
            // }
            // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
            // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
            $recaplistAll[$pSellID]['prev'][$pmp_1->jenis] += $valNet;
        }
        //endregion
        // arrPrint($pRecaplistAll);


        //region current year
        // $tr->addFilter($selectedCabang);
        $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        $tmp = $tr->lookupMainTransaksi()->result();
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $regIDS = array();
        $masterID = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $regIDS[] = "$indexingMain";
            $masterID[] = $tmp_0->id_master;
            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            // arrPrint($indexingMain);
        }

        if (sizeof($masterID) > 0) {

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
            }
            // arrPrint($listedSeller);
        }

        //region lihat registry main
        $m->setFilters(array());
        $m->setParam("id");
        $m->setInParam($regIDS);
        $m->setFields(array("transaksi_id",
            "param",
            "values"
        ));
        $m->setTableName("transaksi_registry");
        $reg = $m->lookUpAll();
        $regEntries = array();
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                // arrPrint($regEntries);

            }
        }
        // arrPrint($regEntries);
        // $recaplist = array();
        // $recaplistCust = array();

        foreach ($tmp as $row) {
            // $fulldate = substr($row->dtime, 0, 7);
            // $dates[$fulldate] = $fulldate;

            $sellID = $listedCustomer[$row->id_master];
            // $sellID = $row->customers_id;
            // cekHitam($row->id);
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");
            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;
            // if(!isset($recaplist[$row->jenis][$trDtime_m])){
            //     $recaplist[$row->jenis][$trDtime_m] =0;
            //
            // }
            // if(!isset($recaplistCust[$row->jenis][$trDtime_m][$sellID])){
            //     $recaplistCust[$trDtime_m][$row->jenis][$sellID] =0;
            // }

            if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            // $recaplist[$row->jenis][$trDtime_m] +=$valNet;
            // $recaplistCust[$trDtime_m][$row->jenis][$sellID] +=$valNet;
            $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
            // $linkData[$sellID][$trDtime_m][$row->jenis] =
        }
        //region outstanding per bulan
        $netPending = array();
        foreach ($recaplistAll as $sID => $sidData) {
            foreach ($sidData as $time => $timeData) {
                $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
                $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
                $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
                $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
                $net = $src - ($srcF1 - $srcF2 - $srcF3);
                $recaplistAll[$sID][$time]['pending'] = $net;
                foreach ($selectedTrans as $jenis => $alias) {
                    if (!isset($recaplistAll[$sID][$time][$jenis])) {
                        $recaplistAll[$sID][$time][$jenis] = 0;
                    }
                }
            }

            // arrPrint($sidData);
        }
        // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion
        // arrPrint(sizeof($listedCustomer));


        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = array("prev" => "prev" . "($prevYear)") + $months;
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        asort($listedCustomerLabel);
        $data = array(
            "mode" => "recap_ext",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "times" => $finalMonths,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "recaps" => $recaplistAll,
            "jenisTr" => $this->jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$this->jenisTr]["label"],
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subPage" => base_url() . get_class($this) . "/viewDaily/" . $this->jenisTr,
            "historyPage" => base_url() . get_class($this) . "/viewDetail/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => $stepNames,
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthly()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        // arrPrintWebs($_GET);
        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";


        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",
            "1982" => "closed",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        // showLast_query("lime");
        //endregion
        // cekHitam();

        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $regIDS[] = $tmp_0->id;
            $masterID[] = $tmp_0->id_master;
            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }
        // arrPrint($prevIDS);
        // matiHere(__LINE__);
        // arrPrint($indListPrev);

        if (sizeof($masterID) > 0) {

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // $kolom = array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama");
            // $this->db->select($kolom);
            // $this->db->where_in("id",$masterID);
            // $tempMaster = $tr->lookupAll()->result();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
            }

            // foreach ($tempMaster as $tempMaster_0) {
            //     // arrPrint($tempMaster_0);
            //     $listedSeller[$tempMaster_0->id] = $tempMaster_0->oleh_id;
            //     $listedSellerLabel[$tempMaster_0->oleh_id] = $tempMaster_0->oleh_nama;
            //     $listedCustomer[$tempMaster_0->id] = $tempMaster_0->customers_id;
            //     $listedCustomerLabel[$tempMaster_0->customers_id] = $tempMaster_0->customers_nama;
            // }
            // arrPrint($listedSeller);
        }
        // matiHere(__LINE__);
        //region lihat registry main
        // $m->setFilters(array());
        // $m->setParam("id");
        // $m->setInParam($regIDS);
        // $m->setFields(array("transaksi_id", "param", "values"));
        // $m->setTableName("transaksi_registry");
        // $reg = $m->lookUpAll();

        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        $regEntries = array();
        // arrPrint($reg);
        // matiHere(__LINE__);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {

                // $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);

            }
            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($test);
        // matiHEre();
        foreach ($tmp as $row) {
            $sellID = isset($listedCustomer[$row->id_master]) ? $listedCustomer[$row->id_master] : "";
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");
            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;
            if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }


        //region outstanding per bulan
        // $netPending = array();
        // $summaryData = array();
        // foreach ($recaplistAll as $sID => $sidData) {
        //     foreach ($sidData as $time => $timeData) {
        //         $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
        //         $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
        //         $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
        //         $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
        //         $net = $src - ($srcF1 - $srcF2-$srcF3);
        //         $recaplistAll[$sID][$time]['pending'] = $net;
        //         // foreach ($selectedTrans as $jenis => $alias) {
        //         //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
        //         //         $recaplistAll[$sID][$time][$jenis] = 0;
        //         //     }
        //         // }
        //     }
        //
        //     // arrPrint($sidData);
        // }
        // // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion

        // arrPrint($prevIDS);
        //         matiHere(__LINE__);
        if (sizeof($prevIDS) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("year(dtime)<='$prevYear'");
            // $this->db->where("year(dtime)='$currYear'");
            $prevData = $tr->lookupMainTransaksi()->result();
            // cekLime($this->db->last_query());
            $idsRelPrev = array();
            $cuID = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
            }

            // arrPrint($cuID);
            // matiHere();
            // foreach($idsRelPrev as $pID){
            foreach ($cuID as $customers_id => $dtaID) {
                $val = 0;
                foreach ($dtaID as $PID) {
                    if (isset($indListPrev[$PID])) {
                        foreach ($indListPrev[$PID] as $iuid) {
                            // cekHitam($iuid);
                            $val += $regEntries[$iuid]['nett1'];
                        }
                        // arrPrint($regEntries);
                    }
                }
                $prevOutsanding[$customers_id]['prev'] = $val;
            }


            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        asort($listedCustomerLabel);
        // arrPrint($prevOutsanding);

        /* -----------------------------------------------------------
         * exporter excel
         * -----------------------------------------------------------*/
        if (isset($_GET['ayo'])) {
            // matiHere("cek boss");
            // arrPrintPink($listedCustomerLabel);
            // arrPrintPink($finalMonths);
            // arrPrint($selectedTrans);
            // arrPrintPink($recaplistAll);
            $dataSrcs_0 = $listedCustomerLabel + array("total" => "total bawah");
            $dataSrcs = array();
            foreach ($dataSrcs_0 as $oID => $dataSrc) {

                $dataSrcPlus['customer'] = $dataSrc;
                $dataSrcSumBawah['customer'] = "JUMLAH TOTAL";
                foreach ($prevMonths as $pk => $pLabel) {

                    $addLinkParam['dtime'] = $prevYear;
                    $val = isset($prevOutsanding[$oID][$pk]) ? $prevOutsanding[$oID][$pk] : 0;

                    $dataSrcPlus['prev'] = $val;

                    if (!isset($dataSrcSumBawah['prev'])) {
                        $dataSrcSumBawah['prev'] = 0;
                    }
                    $dataSrcSumBawah['prev'] += $val;
                }
                /* ---------------------------------------------
                 * pembentuk nilai-nilai summary
                 * ---------------------------------------------*/
                $arrHeaderData = array();
                foreach ($finalMonths as $pID => $pName) {
                    foreach ($selectedTrans as $j => $al) {
                        $val = isset($recaplistAll[$oID][$pID][$j]) ? $recaplistAll[$oID][$pID][$j] : 0;

                        $key_nilai = $j . "_" . $pID;
                        $dataSrcPlus[$key_nilai] = $val;

                        if (!isset($dataSrcSumBawah[$key_nilai])) {
                            $dataSrcSumBawah[$key_nilai] = 0;
                        }
                        $dataSrcSumBawah[$key_nilai] += $val;

                        if (!isset($arrSum[$oID][$j])) {
                            $arrSum[$oID][$j] = 0;
                        }
                        $arrSum[$oID][$j] += $val;


                        $arrHeaderData[$key_nilai] = array(
                            "label" => "$pName $al",
                            "type" => "integer",
                        );

                        if (!isset($arrSumBawah[$j])) {
                            $arrSumBawah[$j] = 0;
                        }
                        $arrSumBawah[$j] += $val;
                    }

                }
                /* ---------------------------------------------
                 * penampil summary
                 * ---------------------------------------------*/
                $dataSrcSum = array();
                foreach ($arrSum as $ob_id => $ob_items) {
                    // $dataSrcPlus['sum_kanan'] =
                    foreach ($ob_items as $jTr => $ob_item) {
                        $key_nilai_sum = "sum_" . $jTr;

                        /* ---------------------------------------------
                         * summari kanan
                         * ---------------------------------------------*/
                        $dataSrcSum[$key_nilai_sum] = $ob_item;
                        $outstanding_nilai = $dataSrcPlus['prev'] + $ob_items['582so'] - ($ob_items['582spd'] + $ob_items['1982']);
                        $dataSrcSum["outstanding"] = $outstanding_nilai;

                        $arrHeaderData[$key_nilai_sum] = array(
                            "label" => "sum " . $selectedTrans[$jTr],
                            "type" => "integer",
                        );

                        /* ---------------------------------------------
                         * summari bawah
                         * ---------------------------------------------*/
                        if (!isset($dataSrcSumBawah[$key_nilai_sum])) {
                            $dataSrcSumBawah[$key_nilai_sum] = 0;
                        }
                        $dataSrcSumBawah[$key_nilai_sum] = $arrSumBawah[$jTr];
                    }

                }

                $dataSrcSumBawah["outstanding"] = $dataSrcSumBawah['prev'] + $dataSrcSumBawah['sum_582so'] - ($dataSrcSumBawah['sum_582spd'] + $dataSrcSumBawah['sum_1982']);
                $arrHeaderData["outstanding"] = array(
                    "label" => "outstanding",
                    "type" => "integer",
                );


                $dataSrc_plus = $dataSrcPlus + $dataSrcSum;

                if ($oID == "total") {
                    $dataSrcs[$oID] = (object)$dataSrcSumBawah;
                }
                else {
                    $dataSrcs[$oID] = (object)$dataSrc_plus;
                }
            }

            $this->load->library('Excel');
            $ex = new Excel();

            // region pairing data
            $no = 0;
            $datas = array();
            $dataSpec = array();
            $namaFile = "Laporan Sales Order bulanan per customer $currYear";
            $souceFields = $headers = array(
                    "customer" => array(
                        "label" => "nama konsumen",
                        "type" => "string",
                    ),
                    "prev" => array(
                        "label" => "prev",
                        "type" => "integer",
                    ),

                ) + $arrHeaderData;
            // $dataSrcs = $recaplistAll;
            // arrPrintWebs($dataSrcs);
            foreach ($dataSrcs as $pId => $itemffs) {
                foreach ($souceFields as $kolom => $stokSpeks) {
                    $no++;

                    $dataSpec[$kolom] = $itemffs->$kolom;
                }

                $datas[] = (object)$dataSpec;
            }
            // endregion pairing data
            if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
                // arrPrintWebs($arrSum);
                // arrPrintWebs($dataSrcs);
                // arrPrintWebs($headers);
                // arrPrint($datas);
                // matiHere(__LINE__ . " $namaFile");
            }

            $ex->setTitleFile($namaFile);
            $ex->setDatas($datas);
            $ex->setHeaders($headers);

            return $ex->writer();
        }
        // else{
        //     cekAlert("ok");
        // }

        $data = array(
            "mode" => "recap_ext1",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthlyExport()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";


        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            "382so" => "sales order",
            "382spd" => "packing list",
            "3982" => "return",
            "3981" => "closed",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='$selectedCabang'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }
        //region current year
        // $tr->addFilter($selectedCabang);
        $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('382so','382spd','3982','3981')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        if (sizeof($tmp) > 0) {
            $regIDS = array();
            $masterID = array();
            $fnReg = array();
            $prevIDS = array();
            $getReg = array();
            foreach ($tmp as $tmp_0) {
                // arrPrint($tmp_0);
                // cekBiru($tmp_0->id." ".$tmp_0->nomer." ".$tmp_0->customers_id);

                // arrprint($indexingMain);

                if (strlen($tmp_0->indexing_registry) > 10) {
                    // $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : "";
                    $regIDS[] = blobDecode($tmp_0->indexing_registry)['main'];
                }
                else {
                    $getReg[] = $tmp_0->id;
                }
                $masterID[] = $tmp_0->id_master;
                $trIds_[$tmp_0->id] = 1;
                $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
                $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
                $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
                $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
                $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
                if ($tmp_0->jenis == "382spd") {
                    $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                    $prevIDS[] = $idPrev;
                    $indListPrev[$idPrev][] = $tmp_0->id;
                }
                if ($tmp_0->jenis == "3981") {
                    $fnReg[] = $tmp_0->id;

                }

                // arrPrint($indexingMain);
            }
            if (sizeof($getReg) > 0) {
                //auto update indexing transaksi
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('" . implode("','", $getReg) . "')");
                $updateReg = $tr->lookupDataRegistries()->result();
                $dataToUpdate = array();
                foreach ($updateReg as $dataParam) {
                    foreach ($dataParam as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            //                        $dataToUpdate[$dataParam->transaksi_id][$dataParam->param] = $dataParam->id;
                            $dataToUpdate[$dataParam->transaksi_id][$key_reg] = $dataParam->id;
                        }
                    }
                }
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('" . implode("','", $getReg) . "')");
                $mainValues = $tr->lookupMainValues()->result();
                $mainValuesData = array();
                foreach ($mainValues as $values_0) {
                    $mainValuesData[$values_0->transaksi_id][] = $values_0->id;
                }
                // arrPrint($dataToUpdate);
                // matiHEre();
                $tr->setFilters(array());
                $this->db->trans_start();
                if (sizeof($dataToUpdate) > 0) {
                    foreach ($dataToUpdate as $trid => $tridData) {
                        $indexingMainValues = "";
                        if (isset($mainValuesData[$trid])) {
                            $indexingMainValues = blobEncode($mainValuesData[$trid]);
                        }
                        $indexingReg = blobEncode($tridData);
                        $tr->updateData(array("id" => $trid), array(
                            "indexing_registry" => $indexingReg,
                            "indexing_main_values" => $indexingMainValues,

                        )) or die("Failed to update tr next-state!");
                        cekHitam($this->db->last_query());
                    }
                }
                // matiHEre();
                $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
                cekBiru($this->db->last_query());
                // arrPrint($updateReg);
            }
            if (sizeof($masterID) > 0) {

                $m->setFilters(array());
                $m->setParam("id");
                $m->setInParam($masterID);
                $m->setTableName("transaksi");
                $m->setFields(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "customers_id",
                    "customers_nama"
                ));
                $tempMaster = $m->lookUpAll();
                // arrPrint($tempMaster);
                // matiHEre();
                $listedSeller = array();
                $listedSellerLabel = array();
                $listedCustomer = array();
                $listedCustomerLabel = array();
                foreach ($tempMaster as $tempMaster_0) {
                    // arrPrint($tempMaster_0);
                    $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                    $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                    $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                    $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
                }
                // arrPrint($listedSeller);
            }
            //region lihat registry main
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($regIDS);
            $m->setFields(array("transaksi_id",
                "param",
                "values"
            ));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            // matiHere();
            $regEntries = array();
            // arrPrint($reg);
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);

                }
                $test = array();
                if (sizeof($fnReg) > 0) {
                    foreach ($fnReg as $idsFn) {
                        $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : $regEntries[$idsFn]['referenceID'];
                        $prevIDS[] = $idx;
                        $test[] = $idx;
                        $indListPrev[$idx][] = $idsFn;

                    }
                }
            }
            foreach ($tmp as $row) {
                $sellID = $listedCustomer[$row->id_master];
                $trDtime = $trIdDts[$row->id]["dtime"];
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;
                if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                    $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
                }
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
            }

            /*
             * hanya manggil main transaksi joint registry untuk penampil master
             */
            //endregion
            if (sizeof($prevIDS) > 0) {

                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
                $tr->addFilter("year(dtime)<='$prevYear'");
                $prevData = $tr->lookupMainTransaksi()->result();
                $idsRelPrev = array();
                foreach ($prevData as $dtaTmpPRev) {
                    $idsRelPrev[] = $dtaTmpPRev->id;
                    $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
                }
                foreach ($cuID as $customers_id => $dtaID) {
                    $val = 0;
                    foreach ($dtaID as $PID) {
                        if (isset($indListPrev[$PID])) {
                            foreach ($indListPrev[$PID] as $iuid) {
                                // cekHitam($iuid);
                                $val += $regEntries[$iuid]['nett1'];
                            }
                            // arrPrint($regEntries);
                        }
                    }
                    $prevOutsanding[$customers_id]['prev'] = $val;
                }
            }
            else {
                $prevOutsanding = array();
            }
            // arrPrint($prevOutsanding);
        }
        else {
            $recaplistAll = array();
            $prevOutsanding = array();
            $listedCustomerLabel = array();

        }

        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        if (sizeof($listedCustomerLabel) > 0) {
            asort($listedCustomerLabel);
        }


        $data = array(
            "mode" => "recap_extport",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewdetail_def()
    {
        // arrPrint($this->uri->segment_array());
        arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['addParams']);
        $cID = $addParam['customers_id'];
        arrPRint($addParam);
        $jn = $this->uri->segment(4);
        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }

        }
        // $tr->addFilter();
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        cekLime($this->db->last_query());
        $ids = array();
        $arrNoIndexReg = array();
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[] = $tmp_0->id;
                }

                // arrPrint($regIndex);
            }
        }
        if (sizeof($arrNoIndexReg) > 0) {
            $mong = new MdlMongoMother();
            $mong->setFilters(array());
            $mong->setFilters(array());
            $mong->setParam("transaksi_id");
            $mong->setInParam($arrNoIndexReg);
            $mong->setFields(array("id",
                "transaksi_id",
                "param",
                "values"
            ));
            $mong->setTableName("transaksi_registry");
            $tReg = $mong->lookUpAll();
            $tRegID = array();
            if (sizeof($tReg) > 0) {
                foreach ($tReg as $tReg_0) {
                    $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                    // arrPrint($regEntries);

                }
            }
            foreach ($arrNoIndexReg as $ii => $updID) {
                // $mongListUpadte['update']['main'][] = array(
                //     "where" => array("id" => $no,),
                //     "value" => $arrData,
                // );
                $mong->setTableName("transaksi");
                $valueRe = blobencode($tRegID[$updID]);
                $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
            }
        }

        //region lihat gerbang value dari registry
        if (sizeof($ids) > 0) {
            $m = new MdlMongoMother();
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($ids);
            $m->setFields(array("transaksi_id",
                "param",
                "values"
            ));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            $regEntries = array();
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                }
            }

        }
        //endregion

        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();
        foreach ($tmp as $tmp0) {
            $val = isset($regEntries[$tmp0->id]['nett1']) ? $regEntries[$tmp0->id]['nett1'] : 0;

            //reseter
            if (!isset($arrAll['harga'])) {
                $arrAll['harga'] = 0;
            }
            if (!isset($arrAll['nett'])) {
                $arrAll['nett'] = 0;
            }
            if (!isset($arrAll['harga_disc'])) {
                $arrAll['harga_disc'] = 0;
            }


            if ($tmp0->trash_4 == "0") {
                // $arrAll['harga'] += $val;
                $harga = $val;
                $harga_rej = 0;
            }
            else {
                $arrAll['harga_disc'] += $val;
                $harga = 0;
                $harga_rej = $val;
            }

            $data[$tmp0->id] = array(
                "dtime" => $tmp0->fulldate,
                "nomer" => $tmp0->nomer,
                "harga" => $val,
                // "valid" =>$harga,
                "harga_disc" => $harga_rej,
                "nett" => $val - $harga_rej,
            );

            $arrAll['harga'] += $val;
            $arrAll['nett'] += $val - $harga_rej;
        }
        // arrPrint($this->uri->rsegment_array());
        // matiHere();
        $header = array("dtime" => "date",
            "nomer" => "receipt",
            "harga" => "amount",
            "harga_disc" => " amount reject",
            "nett" => "netto"
        );
        $dataTmp = array(
            "mode" => "recapDetil",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subTitle" => "monthly",
            "items" => $data,
            "subtotal" => $arrAll,
            "headerFields" => $header,
        );
        $this->load->view("activityReports", $dataTmp);
        arrPrint($data);
        arrPrint($arrAll);
    }

    public function viewdetail_OLD()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['addParams']);
        $cID = $addParam['customers_id'];
        // arrPRint($addParam);

        $jn = $this->uri->segment(3);

        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }

        }
        // $title = $this->config->item("heTransaksi_ui")[$jn]['label'];

        // $tr->addFilter();
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        // cekLime($this->db->last_query());
        // matiHEre();
        $ids = array();
        $arrNoIndexReg = array();
        $customer = "";
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $customer = $tmp_0->customers_nama;
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[] = $tmp_0->id;
                }

                // arrPrint($regIndex);
            }
        }
        if (sizeof($arrNoIndexReg) > 0) {
            $mong = new MdlMongoMother();
            $mong->setFilters(array());
            $mong->setFilters(array());
            $mong->setParam("transaksi_id");
            $mong->setInParam($arrNoIndexReg);
            $mong->setFields(array("id",
                "transaksi_id",
                "param",
                "values"
            ));
            $mong->setTableName("transaksi_registry");
            $tReg = $mong->lookUpAll();
            $tRegID = array();
            if (sizeof($tReg) > 0) {
                foreach ($tReg as $tReg_0) {
                    $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                    // arrPrint($regEntries);

                }
            }
            foreach ($arrNoIndexReg as $ii => $updID) {
                // $mongListUpadte['update']['main'][] = array(
                //     "where" => array("id" => $no,),
                //     "value" => $arrData,
                // );
                $mong->setTableName("transaksi");
                $valueRe = blobencode($tRegID[$updID]);
                $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
            }
        }

        //region lihat gerbang value dari registry
        if (sizeof($ids) > 0) {
            $m = new MdlMongoMother();
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($ids);
            $m->setFields(array("transaksi_id",
                "param",
                "values"
            ));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            $regEntries = array();
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                }
            }

        }
        //endregion

        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();

        foreach ($tmp as $tmp0) {
            $val = isset($regEntries[$tmp0->id]['nett1']) ? $regEntries[$tmp0->id]['nett1'] : 0;

            //reseter
            if (!isset($arrAll['harga'])) {
                $arrAll['harga'] = 0;
            }
            if (!isset($arrAll['nett'])) {
                $arrAll['nett'] = 0;
            }
            if (!isset($arrAll['total'])) {
                $arrAll['total'] = 0;
            }


            if ($tmp0->trash_4 == "0") {
                // $arrAll['harga'] += $val;
                $harga = $val;
                $harga_rej = 0;
            }
            else {
                $arrAll['total'] += $val;
                $harga = 0;
                $harga_rej = $val;
            }

            $data[$tmp0->id] = array(
                "dtime" => $tmp0->fulldate,
                // "nomer_top"=>$tmp0->nomer_top,
                // "id"=>$tmp0->id,
                "nomer" => $tmp0->nomer,
                "harga" => $val,
                // "valid" =>$harga,
                "total" => $harga_rej,
                "nett" => $val - $harga_rej,
            );
            $sum = $val - $harga_rej;
            $arrAll['harga'] += $val;
            $arrAll['total'] += $harga_rej;
            $arrAll['nett'] += $sum;
        }
        // arrPrint($this->uri->rsegment_array());
        // matiHere();
        $header = array("dtime" => "date",
            "nomer" => "receipt",
            "harga" => "amount",
            "total" => " amount reject",
            "nett" => "netto"
        );
        $dataTmp = array(
            "mode" => "recapDetil",
            "title" => $this->config->item("heTransaksi_ui")[$jn]['label'] . $customer . " report",
            "sub_title" => "detil " . $this->config->item("heTransaksi_ui")[$jn]['label'] . " by customer $customer",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subTitle" => "monthly",
            "items" => $data,
            "subtotal" => $arrAll,
            "headerFields" => $header,
        );
        $this->load->view("activityReports", $dataTmp);
        // arrPrint($data);
        // arrPrint($arrAll);
    }

    public function viewdetailPrev()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['addParams']);
        $cID = $addParam['customers_id'];
        // arrPRint($addParam);
        $jn = $this->uri->segment(3);
        // $jn = "582so";
        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }

        }

        $tr->addFilter();
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        cekLime($this->db->last_query());
        $ids = array();
        $arrNoIndexReg = array();
        $customer = "";
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $customer = $tmp_0->customers_nama;
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[] = $tmp_0->id;
                }

                // arrPrint($regIndex);
            }
        }
        if (sizeof($arrNoIndexReg) > 0) {
            $mong = new MdlMongoMother();
            $mong->setFilters(array());
            $mong->setFilters(array());
            $mong->setParam("transaksi_id");
            $mong->setInParam($arrNoIndexReg);
            $mong->setFields(array("id",
                "transaksi_id",
                "param",
                "values"
            ));
            $mong->setTableName("transaksi_registry");
            $tReg = $mong->lookUpAll();
            $tRegID = array();
            if (sizeof($tReg) > 0) {
                foreach ($tReg as $tReg_0) {
                    $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                    // arrPrint($regEntries);

                }
            }
            foreach ($arrNoIndexReg as $ii => $updID) {
                // $mongListUpadte['update']['main'][] = array(
                //     "where" => array("id" => $no,),
                //     "value" => $arrData,
                // );
                $mong->setTableName("transaksi");
                $valueRe = blobencode($tRegID[$updID]);
                $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
            }
        }

        //region lihat gerbang value dari registry
        if (sizeof($ids) > 0) {
            $m = new MdlMongoMother();
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($ids);
            $m->setFields(array("transaksi_id",
                "param",
                "values"
            ));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            $regEntries = array();
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                }
            }

        }
        //endregion

        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();

        foreach ($tmp as $tmp0) {
            $val = isset($regEntries[$tmp0->id]['nett1']) ? $regEntries[$tmp0->id]['nett1'] : 0;

            //reseter
            if (!isset($arrAll['harga'])) {
                $arrAll['harga'] = 0;
            }
            if (!isset($arrAll['nett'])) {
                $arrAll['nett'] = 0;
            }
            if (!isset($arrAll['harga_disc'])) {
                $arrAll['harga_disc'] = 0;
            }


            if ($tmp0->trash_4 == "0") {
                // $arrAll['harga'] += $val;
                $harga = $val;
                $harga_rej = 0;
            }
            else {
                $arrAll['harga_disc'] += $val;
                $harga = 0;
                $harga_rej = $val;
            }

            $data[$tmp0->id] = array(
                "dtime" => $tmp0->fulldate,
                "nomer" => $tmp0->nomer,
                "harga" => $val,
                // "valid" =>$harga,
                "harga_disc" => $harga_rej,
                "nett" => $val - $harga_rej,
            );

            $arrAll['harga'] += $val;
            $arrAll['nett'] += $val - $harga_rej;
        }
        // arrPrint($this->uri->rsegment_array());
        // matiHere();
        $header = array("dtime" => "date",
            "nomer" => "receipt",
            "harga" => "amount",
            "harga_disc" => " amount reject",
            "nett" => "netto"
        );
        $dataTmp = array(
            "mode" => "recapDetil",
            "title" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . $customer . " report",
            "sub_title" => "detil " . $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " by customer $customer",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subTitle" => "monthly",
            "items" => $data,
            "subtotal" => $arrAll,
            "headerFields" => $header,
        );
        $this->load->view("activityReports", $dataTmp);
        // arrPrint($data);
        // arrPrint($arrAll);
    }

    public function viewSalesMonthly_e()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Mdls/MdlCabang");
        $this->load->helper("he_mass_table");
        $m = new MdlMongoMother();

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;


        //datacabang
        $c = new MdlCabang();
        $c->addfilter("id>0");
        $cabangData = $c->lookUpAll()->result();
        foreach ($cabangData as $cabangData_0) {
            $cabangAlias[$cabangData_0->id] = $cabangData_0->nama;
        }

        // arrPrint($cabangAlias);
        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";

        //region lihat rekening penjualan mutasi berdasarkan periode waktu
        //penjualan reguler
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }

        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpPenjualan = $r->fetchMoves("penjualan");
        cekHitam($this->db->last_query());
        $tempData = array();
        $listedRekID = array();
        if (sizeof($tmpPenjualan) > 0) {
            foreach ($tmpPenjualan as $tmp) {
                $time_index = formatTanggal($tmp->dtime, "m");
                if ($tmp->jenis == "582spd") {
                    $nilai = $tmp->kredit;
                }
                else {
                    $nilai = $tmp->debet;
                }
                // $tempData[$time_index][$tmp->jenis][] = array(
                //     "transaksi_id" => $tmp->transaksi_id,
                //     "transaksi_no" => $tmp->transaksi_no,
                //     "nilai" => $nilai,
                // );
                $tempData[$tmp->transaksi_id] = $nilai;
                $listedRekID[] = $tmp->transaksi_id;
            }
        }

        //region lihat rekening besar
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        $r->addFilter("year(dtime)='$currYear'");
        $r->addFilter("periode='bulanan'");
        $r->addFilter("rekening in ('penjualan','return penjualan')");
        $r->setTableName("_rek_master_cache");
        $rekValuesTmp = $r->lookUpAll()->result();
        cekLime($this->db->last_query());
        $rekValues = array();
        foreach ($rekValuesTmp as $tempRek) {
            $valRek = $tempRek->rekening == "penjualan" ? $tempRek->kredit : $tempRek->debet;
            $rekValues[$tempRek->cabang_id][$tempRek->bln][$tempRek->rekening] = $valRek;
            if (!isset($rekValues[$tempRek->cabang_id][$tempRek->bln]["penjualan"])) {
                $rekValues[$tempRek->cabang_id][$tempRek->bln]["penjualan"] = 0;
            }
            if (!isset($rekValues[$tempRek->cabang_id][$tempRek->bln]["return penjualan"])) {
                $rekValues[$tempRek->cabang_id][$tempRek->bln]["return penjualan"] = 0;
            }
        }
        // arrPrint($rekValues);
        $sumValidateValuesRek = array();
        $sumValidatecabang = array();
        foreach ($rekValues as $cabang => $cabang_data) {
            // arrPrint($cabang_data);
            foreach ($cabang_data as $timeRek => $timeValues) {
                if (!isset($sumValidateValuesRek[$timeRek]['penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['penjualan'] = 0;
                }
                if (!isset($sumValidateValuesRek[$timeRek]['return penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['return penjualan'] = 0;
                }
                if (!isset($sumValidatecabang[$cabang]['penjualan'])) {
                    $sumValidatecabang[$cabang]['penjualan'] = 0;
                }
                if (!isset($sumValidatecabang[$cabang]['return penjualan'])) {
                    $sumValidatecabang[$cabang]['return penjualan'] = 0;

                }
                if (!isset($sumValidatecabangH['penjualan'])) {
                    $sumValidatecabangH['penjualan'] = 0;

                }
                if (!isset($sumValidatecabangH['return penjualan'])) {
                    $sumValidatecabangH['return penjualan'] = 0;

                }

                if (isset($timeValues['penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['penjualan'] += $timeValues['penjualan'];
                    $sumValidatecabang[$cabang]['penjualan'] += $timeValues['penjualan'];
                    $sumValidatecabangH['penjualan'] += $timeValues['penjualan'];

                }
                if (isset($timeValues['return penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['return penjualan'] += $timeValues['return penjualan'];
                    $sumValidatecabang[$cabang]['return penjualan'] += $timeValues['return penjualan'];
                    $sumValidatecabangH['return penjualan'] += $timeValues['return penjualan'];
                }

            }
        }
        //endregion

        //return penjualan
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpReturnPenjualan = $r->fetchMoves("return penjualan");

        cekHitam($this->db->last_query());
        $tempDataReturn = array();
        $listedRekReturnID = array();
        if (sizeof($tmpReturnPenjualan) > 0) {
            foreach ($tmpReturnPenjualan as $tmpReturnPenjualan_0) {
                $time_index = formatTanggal($tmpReturnPenjualan_0->dtime, "m");
                // $tempDataReturn[$time_index][$tmpReturnPenjualan_0->jenis][] = array(
                //     "transaksi_id" => $tmpReturnPenjualan_0->transaksi_id,
                //     "transaksi_no" => $tmpReturnPenjualan_0->transaksi_no,
                //     "nilai" => $tmpReturnPenjualan_0->debet,
                // );
                $tempDataReturn[$tmpReturnPenjualan_0->transaksi_id] = $tmpReturnPenjualan_0->debet;
                $listedRekReturnID[] = $tmpReturnPenjualan_0->transaksi_id;
            }
        }
        // arrPrint($listedRekID);
        //endregion
        // arrPrint($tempDataReturn);
        $fianlDatas = $tempData + $tempDataReturn;
        $finalIDS = array_merge($listedRekID, $listedRekReturnID);
        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            // "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",
            "9912" => "cancel",
            "999" => "adjustment",
            // "1982"=>"closed",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //region current year
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . implode("','", $finalIDS) . "')");
        $tmp = $tr->lookupMainTransaksi()->result();
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // // arrprint($indexingMain);
            // $regIDS[] = "$indexingMain";
            $masterID[] = $tmp_0->id_master;
            // $trIds_[$tmp_0->id] = 1;
            // $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            // $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            // $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            // arrPrint($indexingMain);
        }
        if (sizeof($masterID) > 0) {
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                // if($tempMaster_0['customers_id']!='0'){
                // ceklime($tempMaster_0['id']);
                $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
                // }

            }
        }
        // arrPrint($listedCustomer);
        //region lihat registry main
        foreach ($tmp as $row) {
            if (isset($listedCustomer[$row->id_master])) {
                $sellID = $listedCustomer[$row->id_master];
                $trDtime = $row->dtime;
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                $valNet = isset($fianlDatas[$row->id]) ? $fianlDatas[$row->id] : 0;
                if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                    $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
                }
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
            }


        }
        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */
        //region outstanding per bulan
        $netPending = array();
        $summaryData = array();
        foreach ($recaplistAll as $sID => $sidData) {
            foreach ($sidData as $time => $timeData) {
                $src = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
                $srcF1 = isset($timeData['982']) ? $timeData['982'] : 0;
                $srcF2 = isset($timeData['9912']) ? $timeData['9912'] : 0;
                $net = $src - ($srcF1 - $srcF2);
                $recaplistAll[$sID][$time]['netto'] = $net;
                // foreach ($selectedTrans as $jenis => $alias) {
                //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
                //         $recaplistAll[$sID][$time][$jenis] = 0;
                //     }
                // }
            }

            // arrPrint($sidData);
        }
        // arrPrint($recaplistAll);
        //endregion

        //endregion
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        // $prevMonths = array("prev" => "prev");
        $sumTimes = $months + array("netto" => "netto");
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        asort($listedCustomerLabel);
        // arrPrint($prevOutsanding);

        $data = array(
            "mode" => "recap_ext2",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => array(),
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => array(),
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans + array("netto" => "netto"),
            "headerListSum" => $selectedTrans + array("netto" => "netto"),
            // "masterRekeningHeader" =>$masterRekeningHeader+$finalMonths,
            "masterRekeningData" => $rekValues,
            "sumMasterRekeningData" => $sumValidateValuesRek,
            "sumValidatecabang" => $sumValidatecabang,
            "masterCabang" => $cabangAlias,
            "masterGrandTotal" => $sumValidatecabangH,
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesMonthly()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("Mdls/MdlCabang");
        $this->load->helper("he_mass_table");
        $m = new MdlMongoMother();

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;


        //datacabang
        $c = new MdlCabang();
        $c->addfilter("id>0");
        $cabangData = $c->lookUpAll()->result();
        foreach ($cabangData as $cabangData_0) {
            $cabangAlias[$cabangData_0->id] = $cabangData_0->nama;
        }

        // arrPrint($cabangAlias);
        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";

        //region lihat rekening penjualan mutasi berdasarkan periode waktu
        //penjualan reguler
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }

        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpPenjualan = $r->fetchMoves("penjualan");
        cekHitam($this->db->last_query());
        $tempData = array();
        $listedRekID = array();
        if (sizeof($tmpPenjualan) > 0) {
            foreach ($tmpPenjualan as $tmp) {
                $time_index = formatTanggal($tmp->dtime, "m");
                if ($tmp->jenis == "582spd") {
                    $nilai = $tmp->kredit;
                }
                else {
                    if ($tmp->jenis == "382spd") {
                        $nilai = $tmp->kredit;
                    }
                    else {
                        //                        if ($tmp->jenis == "999_0") {
                        //
                        //                        }
                        //                        else{

                        $nilai = $tmp->debet;
                        //                        }
                    }

                }
                // $tempData[$time_index][$tmp->jenis][] = array(
                //     "transaksi_id" => $tmp->transaksi_id,
                //     "transaksi_no" => $tmp->transaksi_no,
                //     "nilai" => $nilai,
                // );
                $tempData[$tmp->transaksi_id] = $nilai;
                $listedRekID[] = $tmp->transaksi_id;
            }
        }

        //region lihat rekening besar
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        $r->addFilter("year(dtime)='$currYear'");
        $r->addFilter("periode='bulanan'");
        $r->addFilter("rekening in ('penjualan','return penjualan')");
        $r->setTableName("_rek_master_cache");
        $rekValuesTmp = $r->lookUpAll()->result();
        cekLime($this->db->last_query());
        $rekValues = array();
        foreach ($rekValuesTmp as $tempRek) {
            $valRek = $tempRek->rekening == "penjualan" ? $tempRek->kredit : $tempRek->debet;
            $rekValues[$tempRek->cabang_id][$tempRek->bln][$tempRek->rekening] = $valRek;
            if (!isset($rekValues[$tempRek->cabang_id][$tempRek->bln]["penjualan"])) {
                $rekValues[$tempRek->cabang_id][$tempRek->bln]["penjualan"] = 0;
            }
            if (!isset($rekValues[$tempRek->cabang_id][$tempRek->bln]["return penjualan"])) {
                $rekValues[$tempRek->cabang_id][$tempRek->bln]["return penjualan"] = 0;
            }
        }
        // arrPrint($rekValues);
        $sumValidateValuesRek = array();
        $sumValidatecabang = array();
        foreach ($rekValues as $cabang => $cabang_data) {
            // arrPrint($cabang_data);
            foreach ($cabang_data as $timeRek => $timeValues) {
                if (!isset($sumValidateValuesRek[$timeRek]['penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['penjualan'] = 0;
                }
                if (!isset($sumValidateValuesRek[$timeRek]['return penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['return penjualan'] = 0;
                }
                if (!isset($sumValidatecabang[$cabang]['penjualan'])) {
                    $sumValidatecabang[$cabang]['penjualan'] = 0;
                }
                if (!isset($sumValidatecabang[$cabang]['return penjualan'])) {
                    $sumValidatecabang[$cabang]['return penjualan'] = 0;

                }
                if (!isset($sumValidatecabangH['penjualan'])) {
                    $sumValidatecabangH['penjualan'] = 0;

                }
                if (!isset($sumValidatecabangH['return penjualan'])) {
                    $sumValidatecabangH['return penjualan'] = 0;

                }

                if (isset($timeValues['penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['penjualan'] += $timeValues['penjualan'];
                    $sumValidatecabang[$cabang]['penjualan'] += $timeValues['penjualan'];
                    $sumValidatecabangH['penjualan'] += $timeValues['penjualan'];

                }
                if (isset($timeValues['return penjualan'])) {
                    $sumValidateValuesRek[$timeRek]['return penjualan'] += $timeValues['return penjualan'];
                    $sumValidatecabang[$cabang]['return penjualan'] += $timeValues['return penjualan'];
                    $sumValidatecabangH['return penjualan'] += $timeValues['return penjualan'];
                }

            }
        }
        //endregion
        // arrPrint($sumValidateValuesRek);
        //return penjualan
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpReturnPenjualan = $r->fetchMoves("return penjualan");

        cekHitam($this->db->last_query());
        $tempDataReturn = array();
        $listedRekReturnID = array();
        if (sizeof($tmpReturnPenjualan) > 0) {
            foreach ($tmpReturnPenjualan as $tmpReturnPenjualan_0) {
                $time_index = formatTanggal($tmpReturnPenjualan_0->dtime, "m");
                // $tempDataReturn[$time_index][$tmpReturnPenjualan_0->jenis][] = array(
                //     "transaksi_id" => $tmpReturnPenjualan_0->transaksi_id,
                //     "transaksi_no" => $tmpReturnPenjualan_0->transaksi_no,
                //     "nilai" => $tmpReturnPenjualan_0->debet,
                // );
                $tempDataReturn[$tmpReturnPenjualan_0->transaksi_id] = $tmpReturnPenjualan_0->debet;
                $listedRekReturnID[] = $tmpReturnPenjualan_0->transaksi_id;
            }
        }
        // arrPrint($listedRekID);
        //endregion
        // arrPrint($tempDataReturn);
        $fianlDatas = $tempData + $tempDataReturn;
        $finalIDS = array_merge($listedRekID, $listedRekReturnID);
        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            // "582so" => "sales order",
            "582spd" => "PL lokal",
            "382spd" => "PL export",
            "982" => "return",
            "9912" => "cancel",
            "999" => "adjustment",
            // "1982"=>"closed",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //region current year
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . implode("','", $finalIDS) . "')");
        $tmp = $tr->lookupMainTransaksi()->result();
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // // arrprint($indexingMain);
            // $regIDS[] = "$indexingMain";
            $masterID[] = $tmp_0->id_master;
            // $trIds_[$tmp_0->id] = 1;
            // $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            // $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            // $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            // arrPrint($indexingMain);
        }
        if (sizeof($masterID) > 0) {
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                // if($tempMaster_0['customers_id']!='0'){
                // ceklime($tempMaster_0['id']);
                $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
                // }

            }
        }
        // arrPrint($listedCustomer);
        //region lihat mastertransaksi
        foreach ($tmp as $row) {
            if (isset($listedCustomer[$row->id_master])) {
                $sellID = $listedCustomer[$row->id_master];
                $trDtime = $row->dtime;
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                $valNet = isset($fianlDatas[$row->id]) ? $fianlDatas[$row->id] : 0;
                if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                    $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
                }
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
            }


        }
        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */
        //region outstanding per bulan
        $netPending = array();
        $summaryData = array();
        foreach ($recaplistAll as $sID => $sidData) {
            foreach ($sidData as $time => $timeData) {
                $src = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
                $src2 = isset($timeData['382spd']) ? $timeData['382spd'] : 0;
                $srcF1 = isset($timeData['982']) ? $timeData['982'] : 0;
                $srcF2 = isset($timeData['9912']) ? $timeData['9912'] : 0;
                $srcF3 = isset($timeData['999']) ? $timeData['999'] : 0;
                $net = ($src + $src2) - $srcF1 - $srcF2 - $srcF3;
                $recaplistAll[$sID][$time]['netto'] = $net;
                // foreach ($selectedTrans as $jenis => $alias) {
                //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
                //         $recaplistAll[$sID][$time][$jenis] = 0;
                //     }
                // }
            }

            // arrPrint($sidData);
        }
        // arrPrint($recaplistAll);
        //endregion

        //endregion
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        // $prevMonths = array("prev" => "prev");
        $sumTimes = $months + array("netto" => "netto");
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        asort($listedCustomerLabel);
        // arrPrint($prevOutsanding);

        $data = array(
            "mode" => "recap_ext2",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => array(),
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => array(),
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans + array("netto" => "netto"),
            "headerListSum" => $selectedTrans + array("netto" => "netto"),
            // "masterRekeningHeader" =>$masterRekeningHeader+$finalMonths,
            "masterRekeningData" => $rekValues,
            "sumMasterRekeningData" => $sumValidateValuesRek,
            "sumValidatecabang" => $sumValidatecabang,
            "masterCabang" => $cabangAlias,
            "masterGrandTotal" => $sumValidatecabangH,
        );
        $this->load->view("activityReports", $data);


    }

    //---PEMBELIAN---------------------------
    public function viewPurchaseOrderMonthly()
    {
        $modeItem = $this->uri->segment(3);

        $this->load->model("Mdls/MdlMongoMother");
        $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";


        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        switch ($modeItem) {
            case "produk":
                $selectedTrans = array(
                    "466" => "purchase order",
                    "467" => "grn",
                    "967" => "return",
                    "1967" => "closed",
                    // "pending" => "out standing"
                );
                $listTrJenis = array("466",
                    "467",
                    "967",
                    "1967"
                );
                $kolom_nilai = "harga";
                $title_report = "monthly product purchase order report by vendor";
                break;
            case "produkImport":
                $selectedTrans = array(
                    "460a" => "purchase order",
                    "460" => "grn",
                    "960a" => "return",
                    "1960a" => "closed",
                    // "pending" => "out standing"
                );
                $listTrJenis = array("460a",
                    "460",
                    "960a",
                    "1960a"
                );
                $kolom_nilai = "exchange__harga";
                $title_report = "monthly product purchase order import report by vendor";
                break;
            case "supplies":
                $selectedTrans = array(
                    "461r" => "purchase order",
                    "461" => "grn",
                    "961" => "return",
                    "1961" => "closed",
                    // "pending" => "out standing"
                );
                $listTrJenis = array("461r",
                    "461",
                    "961",
                    "1961"
                );
                $kolom_nilai = "harga";
                $title_report = "monthly supplies purchase order report by vendor";
                break;
            default:
                $msg = "jenis laporan belum ditentukan (produk atau supplies)";
                die(lgShowAlert($msg));
                break;
        }


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('" . implode("','", $listTrJenis) . "')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            //             arrPrint($tmp_0);
            $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $regIDS[] = "$indexingMain";
            $masterID[] = $tmp_0->id_master;
            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            //---------------------------
            if ($tmp_0->jenis == "467") {//582spd
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1967") {//1982
                $fnReg[] = $tmp_0->id;
            }
            //---------------------------
            if ($tmp_0->jenis == "461") {//582spd
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1961") {//1982
                $fnReg[] = $tmp_0->id;
            }
            //---------------------------
            if ($tmp_0->jenis == "460a") {//582spd
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1960a") {//1982
                $fnReg[] = $tmp_0->id;
            }
            //---------------------------


        }
        // arrPrint($prevIDS);

        // arrPrint($indListPrev);

        if (sizeof($masterID) > 0) {

            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama",
                "suppliers_id",
                "suppliers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                //                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                //                $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
                $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['suppliers_id'];
                $listedCustomerLabel[$tempMaster_0['suppliers_id']] = $tempMaster_0['suppliers_nama'];
            }
            // arrPrint($listedSeller);
        }

        //region lihat registry main
        $m->setFilters(array());
        $m->setParam("id");
        $m->setInParam($regIDS);
        $m->setFields(array("transaksi_id",
            "param",
            "values"
        ));
        $m->setTableName("transaksi_registry");
        $reg = $m->lookUpAll();
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);

            }
            //            arrPrintPink($regEntries);
            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : $regEntries[$idsFn]['referenceID'];
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($test);
        // matiHEre();
        foreach ($tmp as $row) {
            $sellID = $listedCustomer[$row->id_master];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");
            //            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;
            $valNet = isset($regEntries[$row->id][$kolom_nilai]) ? $regEntries[$row->id][$kolom_nilai] : 0;
            if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }


        //region outstanding per bulan
        // $netPending = array();
        // $summaryData = array();
        // foreach ($recaplistAll as $sID => $sidData) {
        //     foreach ($sidData as $time => $timeData) {
        //         $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
        //         $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
        //         $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
        //         $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
        //         $net = $src - ($srcF1 - $srcF2-$srcF3);
        //         $recaplistAll[$sID][$time]['pending'] = $net;
        //         // foreach ($selectedTrans as $jenis => $alias) {
        //         //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
        //         //         $recaplistAll[$sID][$time][$jenis] = 0;
        //         //     }
        //         // }
        //     }
        //
        //     // arrPrint($sidData);
        // }
        // // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("year(dtime)<='$prevYear'");
            // $this->db->where("year(dtime)='$currYear'");
            $prevData = $tr->lookupMainTransaksi()->result();
            //            arrPrintPink($prevData);
            // cekLime($this->db->last_query());
            $idsRelPrev = array();
            $cuID = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->suppliers_id][] = $dtaTmpPRev->id;
            }

            // arrPrint($cuID);
            // matiHere();
            // foreach($idsRelPrev as $pID){
            foreach ($cuID as $suppliers_id => $dtaID) {
                $val = 0;
                foreach ($dtaID as $PID) {
                    if (isset($indListPrev[$PID])) {
                        foreach ($indListPrev[$PID] as $iuid) {
                            // cekHitam($iuid);
                            $val += $regEntries[$iuid][$kolom_nilai];
                        }
                        // arrPrint($regEntries);
                    }
                }
                $prevOutsanding[$suppliers_id]['prev'] = $val;
            }


            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //                arrprint($sumTimes);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;

        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        asort($listedCustomerLabel);
        //         arrPrint($listedCustomerLabel);

        $data = array(
            "mode" => "recap_ext1_vendor",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewdetail_vendor",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
            "modeItem" => $modeItem,
            "titleReport" => $title_report,
        );
        $this->load->view("activityReports", $data);


    }

    public function viewdetail_vendor()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['addParams']);
        $cID = $addParam['suppliers_id'];
        // arrPRint($addParam);

        $jn = $this->uri->segment(3);

        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }

        }
        // $title = $this->config->item("heTransaksi_ui")[$jn]['label'];

        // $tr->addFilter();
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        // cekLime($this->db->last_query());
        // matiHEre();
        $ids = array();
        $arrNoIndexReg = array();
        $customer = "";
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $customer = $tmp_0->suppliers_nama;
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[] = $tmp_0->id;
                }

                // arrPrint($regIndex);
            }
        }
        if (sizeof($arrNoIndexReg) > 0) {
            $mong = new MdlMongoMother();
            $mong->setFilters(array());
            $mong->setFilters(array());
            $mong->setParam("transaksi_id");
            $mong->setInParam($arrNoIndexReg);
            $mong->setFields(array("id",
                "transaksi_id",
                "param",
                "values"
            ));
            $mong->setTableName("transaksi_registry");
            $tReg = $mong->lookUpAll();
            $tRegID = array();
            if (sizeof($tReg) > 0) {
                foreach ($tReg as $tReg_0) {
                    $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                    // arrPrint($regEntries);

                }
            }
            foreach ($arrNoIndexReg as $ii => $updID) {
                // $mongListUpadte['update']['main'][] = array(
                //     "where" => array("id" => $no,),
                //     "value" => $arrData,
                // );
                $mong->setTableName("transaksi");
                $valueRe = blobencode($tRegID[$updID]);
                $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
            }
        }

        //region lihat gerbang value dari registry
        if (sizeof($ids) > 0) {
            $m = new MdlMongoMother();
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($ids);
            $m->setFields(array("transaksi_id",
                "param",
                "values"
            ));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            $regEntries = array();
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                }
            }

        }
        //endregion

        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();

        foreach ($tmp as $tmp0) {
            $val = isset($regEntries[$tmp0->id]['harga']) ? $regEntries[$tmp0->id]['harga'] : 0;

            //reseter
            if (!isset($arrAll['harga'])) {
                $arrAll['harga'] = 0;
            }
            if (!isset($arrAll['nett'])) {
                $arrAll['nett'] = 0;
            }
            if (!isset($arrAll['total'])) {
                $arrAll['total'] = 0;
            }


            if ($tmp0->trash_4 == "0") {
                // $arrAll['harga'] += $val;
                $harga = $val;
                $harga_rej = 0;
            }
            else {
                //                $arrAll['total'] += $val;
                $harga = 0;
                $harga_rej = $val;
            }

            $data[$tmp0->id] = array(
                "dtime" => $tmp0->fulldate,
                // "nomer_top"=>$tmp0->nomer_top,
                // "id"=>$tmp0->id,
                "nomer" => $tmp0->nomer,
                "harga" => $val,
                // "valid" =>$harga,
                "total" => $harga_rej,
                "nett" => $val - $harga_rej,
            );
            $sum = $val - $harga_rej;
            $arrAll['harga'] += $val;
            $arrAll['total'] += $harga_rej;
            $arrAll['nett'] += $sum;
        }
        // arrPrint($this->uri->rsegment_array());
        //        arrPrintPink($arrAll);
        // matiHere();
        $header = array(
            "dtime" => "date",
            "nomer" => "receipt",
            "harga" => "amount",
            "total" => " amount reject",
            "nett" => "netto"
        );
        $dataTmp = array(
            "mode" => "recapDetil",
            "title" => $this->config->item("heTransaksi_ui")[$jn]['label'] . $customer . " report",
            "sub_title" => "detil " . $this->config->item("heTransaksi_ui")[$jn]['label'] . " by vendor $customer",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subTitle" => "monthly",
            "items" => $data,
            "subtotal" => $arrAll,
            "headerFields" => $header,
        );
        $this->load->view("activityReports", $dataTmp);
        // arrPrint($data);
        // arrPrint($arrAll);
    }

    public function viewPurchaseMonthly()
    {
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->helper("he_mass_table");
        $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";
        // $steps = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
        // foreach ($steps as $num => $nSpec) {
        //     $stepNames[$nSpec['target']] = $nSpec['label'];
        // }

        // $date = "2021-05-01";

        //region lihat rekening penjualan mutasi berdasarkan periode waktu
        //penjualan reguler
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpPenjualan = $r->fetchMoves("penjualan");
        cekHitam($this->db->last_query());
        $tempData = array();
        $listedRekID = array();
        if (sizeof($tmpPenjualan) > 0) {
            foreach ($tmpPenjualan as $tmp) {
                $time_index = formatTanggal($tmp->dtime, "m");
                if ($tmp->jenis == "582spd") {
                    $nilai = $tmp->kredit;
                }
                else {
                    $nilai = $tmp->debet;
                }
                // $tempData[$time_index][$tmp->jenis][] = array(
                //     "transaksi_id" => $tmp->transaksi_id,
                //     "transaksi_no" => $tmp->transaksi_no,
                //     "nilai" => $nilai,
                // );
                $tempData[$tmp->transaksi_id] = $nilai;
                $listedRekID[] = $tmp->transaksi_id;
            }
        }

        //return penjualan
        $r->setFilters(array());
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }
        $r->addFilter("year(dtime)='$currYear''");
        $r->addFilter("transaksi_id>'0'");
        $tmpReturnPenjualan = $r->fetchMoves("return penjualan");

        cekHitam($this->db->last_query());
        $tempDataReturn = array();
        $listedRekReturnID = array();
        if (sizeof($tmpReturnPenjualan) > 0) {
            foreach ($tmpReturnPenjualan as $tmpReturnPenjualan_0) {
                $time_index = formatTanggal($tmpReturnPenjualan_0->dtime, "m");
                // $tempDataReturn[$time_index][$tmpReturnPenjualan_0->jenis][] = array(
                //     "transaksi_id" => $tmpReturnPenjualan_0->transaksi_id,
                //     "transaksi_no" => $tmpReturnPenjualan_0->transaksi_no,
                //     "nilai" => $tmpReturnPenjualan_0->debet,
                // );
                $tempDataReturn[$tmpReturnPenjualan_0->transaksi_id] = $tmpReturnPenjualan_0->debet;
                $listedRekReturnID[] = $tmpReturnPenjualan_0->transaksi_id;
            }
        }
        // arrPrint($listedRekID);
        //endregion
        //        arrPrint($tempDataReturn);
        $fianlDatas = $tempData + $tempDataReturn;
        $finalIDS = array_merge($listedRekID, $listedRekReturnID);
        /*
         * 582so trash4=0 netto SO
         * 582spd trash4=0 netto PL
         * 982 trash4=0 return penjualan
         * so|pl|return|outstanding
         * GLOBAL CABANG
         * GLOBAL CUSTOMER
         * GLOBAL Salesman
         */
        //region list transaksi
        $selectedTrans = array(
            // "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",
            "9912" => "cancel",
            // "1982"=>"closed",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //region current year
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . implode("','", $finalIDS) . "')");
        $tmp = $tr->lookupMainTransaksi()->result();
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // // arrprint($indexingMain);
            // $regIDS[] = "$indexingMain";
            $masterID[] = $tmp_0->id_master;
            // $trIds_[$tmp_0->id] = 1;
            // $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            // $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            // $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            // $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);
            // arrPrint($indexingMain);
        }
        if (sizeof($masterID) > 0) {
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($masterID);
            $m->setTableName("transaksi");
            $m->setFields(array("id",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama"
            ));
            $tempMaster = $m->lookUpAll();
            // arrPrint($tempMaster);
            // matiHEre();
            $listedSeller = array();
            $listedSellerLabel = array();
            $listedCustomer = array();
            foreach ($tempMaster as $tempMaster_0) {
                // arrPrint($tempMaster_0);
                if ($tempMaster_0['customers_id'] != '0') {
                    // ceklime($tempMaster_0['id']);
                    $listedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
                    $listedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
                    $listedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
                    $listedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
                }

            }
        }
        // arrPrint($listedCustomer);
        //region lihat registry main
        foreach ($tmp as $row) {
            if (isset($listedCustomer[$row->id_master])) {
                $sellID = $listedCustomer[$row->id_master];
                $trDtime = $row->dtime;
                $trDtime_m = formatTanggal($trDtime, "Y-m");
                $valNet = isset($fianlDatas[$row->id]) ? $fianlDatas[$row->id] : 0;
                if (!isset($recaplistAll[$sellID][$trDtime_m][$row->jenis])) {
                    $recaplistAll[$sellID][$trDtime_m][$row->jenis] = 0;
                }
                $recaplistAll[$sellID][$trDtime_m][$row->jenis] += $valNet;
            }


        }
        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */
        //region outstanding per bulan
        $netPending = array();
        $summaryData = array();
        foreach ($recaplistAll as $sID => $sidData) {
            foreach ($sidData as $time => $timeData) {
                $src = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
                $srcF1 = isset($timeData['982']) ? $timeData['982'] : 0;
                $srcF2 = isset($timeData['9912']) ? $timeData['9912'] : 0;
                $net = $src - ($srcF1 - $srcF2);
                $recaplistAll[$sID][$time]['netto'] = $net;
                // foreach ($selectedTrans as $jenis => $alias) {
                //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
                //         $recaplistAll[$sID][$time][$jenis] = 0;
                //     }
                // }
            }

            // arrPrint($sidData);
        }
        // arrPrint($recaplistAll);
        //endregion

        //endregion
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        // $prevMonths = array("prev" => "prev");
        $sumTimes = $months + array("netto" => "netto");
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        asort($listedCustomerLabel);
        // arrPrint($prevOutsanding);

        $data = array(
            "mode" => "recap_ext2_vendor",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => array(),
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => array(),
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans + array("netto" => "netto"),
            "headerListSum" => $selectedTrans + array("netto" => "netto"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewPembelians()
    {
        $getDate = isset($_GET['date']) ? $_GET['date'] : dtimeNow('Y-m');
        $reg_bl = formatTanggal($getDate, 'm');
        $reg_th = formatTanggal($getDate, 'Y');


        $this->load->model("Mdls/MdlSupplier");
        $vd = new MdlSupplier();
        $koloms = array(
            "id",
            "nama",
        );


        $this->db->select($koloms);
        $this->db->order_by('nama', 'asc');
        if (ipadd() == "202.65.117.72") {
            // $this->db->limit(10);
        }
        $src_vendor_0 = $vd->lookupAll()->result();
        // showLast_query("kuning");
        // arrPrint($src_vendor);

        $src_vendor_incative = $vd->lookupAllInactive()->result();
        // showLast_query("kuning");
        // arrPrint($src_vendor_incative);

        $report_mode = "on_the_fly";
        if (ipadd() == "202.65.117.72") {
            $report_mode = "db_report";
        }
        switch ($report_mode) {
            case "on_the_fly":
                /* ----------------------------------------------------
                 * report on the fly
                 * ----------------------------------------------------*/
                $this->load->model("Mdls/MdlReportSql");
                $rp = new MdlReportSql();

                $dStart = "$reg_th-01-01";
                $dStop = $reg_th != dtimeNow('Y') ? "$reg_th-12-31" : $reg_th . dtimeNow('-m-d');
                $jml_bln = $reg_th != dtimeNow('Y') ? 12 : dtimeNow('m');
                $condites = array(
                    'date(dtime) >=' => $dStart,
                    "date(dtime) <=" => $dStop,
                );
                $this->db->where($condites);
                $src_pembelian = $rp->callPembelianVendor();
                // showLast_query("lime");
                $this->db->where($condites);
                $src_pembelian_return = $rp->callPembelianVendorReturn();
                // showLast_query("lime");
                // cekKuning(sizeof($src_pembelian));

                // arrPrintPink($src_pembelian);
                // arrPrintPink($src_pembelian_return);
                foreach ($src_pembelian as $item) {
                    $thn = $item->th;
                    $bln = $item->bl;
                    $ext_id = $item->extern_id;
                    $src_pembelians[$ext_id][$thn][$bln] = $item;
                }

                foreach ($src_pembelian_return as $item) {
                    $thn = $item->th;
                    $bln = $item->bl;
                    $ext_id = $item->extern_id;
                    $src_pembelian_returns[$ext_id][$thn][$bln] = $item;
                }
                // arrPrint($src_pembelians);
                // arrPrint($src_pembelian_returns);

                /* ----------------------------------------------
                 * mendapatkan data vendor yg dinonativkan tapi sudah pernah ada transaksi
                 * ----------------------------------------------*/
                $suppPembelian = array_keys($src_pembelians);
                $suppPembelianReturn = array_keys($src_pembelian_returns);
                $suppPembelianNetto = array_merge($suppPembelianReturn, $suppPembelian);

                // cekHijau(sizeof($suppPembelian));
                // cekHijau(sizeof($suppPembelianReturn));
                break;
            case "db_report":
                /* ----------------------------------------------------
                 * report on db_report
                 * ----------------------------------------------------*/
                $this->db2 = $this->load->database('report', TRUE);
                $this->load->model("Mdls/MdlReport");
                $rp = new MdlReport();

                $dStart = "$reg_th-01-01";
                $dStop = $reg_th != dtimeNow('Y') ? "$reg_th-12-31" : $reg_th . dtimeNow('-m-d');
                $jml_bln = $reg_th != dtimeNow('Y') ? 12 : dtimeNow('m');

                // $rp->setDebug(true);
                $rp->setPeriode("bulanan");
                // $condites = array(
                //     'th' => $reg_th,
                // );
                // $this->db2->where($condites);
                $rp->setTahun($reg_th);
                $src_pembelian = $rp->callPembelianVendor();
                // cekKuning($this->db2->last_query());
                // cekHijau($src_pembelian);
                $src_pembelians_netto = array();
                foreach ($src_pembelian as $item) {
                    $thn = $item->th;
                    $bln = $item->bl;
                    $ext_id = $item->subject_id;
                    $src_pembelians_netto[$ext_id][$thn][$bln] = $item;
                }

                // cekPink($src_pembelians_netto);
                $src_pembelians = $src_pembelians_netto;
                $src_pembelian_returns = array();

                $suppPembelianNetto = array_keys($src_pembelians_netto);
                // $suppPembelianReturn = array_keys($src_pembelian_returns);
                // $suppPembelianNetto = array_merge($suppPembelianReturn, $suppPembelian);
                break;
            default:
                matiHere("report mode harap ditentukan");
                break;
        }

        // cekBiru($suppPembelianNetto);

        $iniIkut = array();
        foreach ($src_vendor_incative as $item) {
            $suppInactiveId = $item->id;
            if (in_array($suppInactiveId, $suppPembelianNetto)) {
                $iniIkut[] = $item;
            }
        }
        // cekPink($iniIkut);
        $src_vendor = array_merge($src_vendor_0, $iniIkut);

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Pembelian per vendor",
            "subTitle" => "",
            "src_vendor" => $src_vendor,
            "src_pembelians" => $src_pembelians,
            "src_pembelian_returns" => $src_pembelian_returns,
            "getDate" => $getDate,
            "jml_bln" => $jml_bln,
            "thn" => $reg_th,
            "report_mode" => $report_mode,
            "kolom" => "suppliers_id",
            "trJenis" => "467",
            "kolomNilai" => "nilai_in",
            "trJenisContra" => "967",
            "kolomNilaiContra" => "nilai_ot",
        );

        $this->load->view("activityReports", $data);
    }

    public function viewDetail()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrint($_GET);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['addParams']);
        $addParam_00 = blobDecode($_GET['addParams_00']);
        //        $cID = $addParam['customers_id'];
        $seller_id = $addParam_00['seller_id'];
        $seller_nama = $addParam_00['seller_nama'];
        //        arrPrint($addParam);
        //        arrPrint($addParam_00);

        $jn = $this->uri->segment(3);
        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }
        }
        // $title = $this->config->item("heTransaksi_ui")[$jn]['label'];
        // $tr->addFilter();
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        cekLime($this->db->last_query());
        // matiHEre();

        $ids = array();
        $arrNoIndexReg = array();
        $arrMasterID = array();
        $arrMasterID_result = array();
        $arrReferenceID = array();
        $arrTrID = array();
        //        $customer = "";
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                //                $customer = $tmp_0->customers_nama;

                $trID = $tmp_0->id;
                $arrTrID[$trID] = $tmp_0->id;
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[$trID] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[$trID] = $tmp_0->id;
                }
                //-------------
                $arrMasterID[$trID] = $tmp_0->id_master;// spo bagi 582,582spd,
            }

            switch ($jn) {
                case "582so":
                case "582spd":
                    if (sizeof($arrMasterID) > 0) {
                        $tr = new MdlTransaksi();
                        $this->db->select(array("id",
                            "id_master",
                            "id_top"
                        ));
                        $tr->setFilters(array());
                        $tr->addFilter("oleh_id='$seller_id'");
                        $tr->addFilter("id in ('" . implode("','", $arrMasterID) . "')");
                        $trTmp = $tr->lookupAll()->result();
                        showLast_query("biru");
                        cekBiru(sizeof($trTmp));
                        foreach ($trTmp as $spec) {
                            $arrMasterID_result[] = $spec->id_master;
                        }
                    }

                    break;
                case "982":
                case "1982":
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id in ('" . implode("','", $arrTrID) . "')");
                    //                    $tr->addFilter("param='main'");
                    $tr->setJointSelectFields("main,transaksi_id");
                    $tReg = $tr->lookupDataRegistries()->result();
                    showLast_query("kuning");
                    foreach ($tReg as $spec) {
                        $valuesDecode = blobDecode($spec->main);
                        //                            arrPrintWebs($valuesDecode);
                        if ($jn == "982") {
                            $ref_SPD[$spec->transaksi_id] = $valuesDecode['referenceID'];           // shipment
                            $ref_SPD_reverse[$valuesDecode['referenceID']][] = $spec->transaksi_id; // shipment => id 982
                        }
                        if ($jn == "1982") {
                            $ref_ID[$spec->transaksi_id] = $valuesDecode['masterID'];
                            $ref_ID_reverse[$valuesDecode['masterID']] = $spec->transaksi_id;
                        }
                    }

                    if ((isset($ref_SPD)) && (sizeof($ref_SPD) > 0)) {
                        $tr = new MdlTransaksi();
                        $this->db->select(array("id",
                            "id_master",
                            "id_top"
                        ));
                        $tr->setFilters(array());
                        $tr->addFilter("id in ('" . implode("','", $ref_SPD) . "')");
                        $trTmp = $tr->lookupAll()->result();
                        showLast_query("kuning");
                        $arrMaster__ = array();
                        $arrMaster__reverse = array();
                        foreach ($trTmp as $spec) {
                            $arrMaster__[$spec->id] = $spec->id_master;         // id 582spd => id_master
                            $arrMaster__reverse[$spec->id_master][] = $spec->id;// id_master => 582spd
                        }

                        $tr = new MdlTransaksi();
                        $this->db->select(array("id",
                            "id_master",
                            "id_top"
                        ));
                        $tr->setFilters(array());
                        $tr->addFilter("oleh_id='$seller_id'");
                        $tr->addFilter("id in ('" . implode("','", $arrMaster__) . "')");
                        $trTmp = $tr->lookupAll()->result();
                        $arr_SPO = array();
                        foreach ($trTmp as $spec) {
                            $arr_SPO[] = $spec->id_master;
                            if (isset($arrMaster__reverse[$spec->id_master])) {
                                $arr_id = $arrMaster__reverse[$spec->id_master];
                                //                                arrPrint($arr_id);
                                foreach ($arr_id as $iid) {
                                    if (isset($ref_SPD_reverse[$iid])) {
                                        //                                        arrPrintWebs($ref_SPD_reverse[$iid]);
                                        foreach ($ref_SPD_reverse[$iid] as $idx) {
                                            $arrMasterID_result[$idx] = $idx;
                                        }
                                    }
                                }

                            }
                        }

                    }

                    if ((isset($ref_ID)) && (sizeof($ref_ID) > 0)) {
                        $tr = new MdlTransaksi();
                        $this->db->select(array("id",
                            "id_master",
                            "id_top"
                        ));
                        $tr->setFilters(array());
                        $tr->addFilter("oleh_id='$seller_id'");
                        $tr->addFilter("id in ('" . implode("','", $ref_ID) . "')");
                        $trTmp = $tr->lookupAll()->result();
                        showLast_query("kuning");
                        foreach ($trTmp as $spec) {
                            $arrMasterID_result[$spec->id_master] = $spec->id_master;// id 582spo
                            if ($ref_ID_reverse[$spec->id_master]) {
                                $arrMasterID_result[$ref_ID_reverse[$spec->id_master]] = $ref_ID_reverse[$spec->id_master];
                            }
                        }
                    }

                    break;
            }
            //            arrPrint($arrMasterID_result);
            foreach ($tmp as $tmp0) {
                //                arrPrint($tmp0);
                if ($jn == "982") {
                    $id_cek = $tmp0->id;
                }
                else {
                    $id_cek = $tmp0->id_master;
                }
                if (in_array($id_cek, $arrMasterID_result)) {
                    $regIndex = blobDecode($tmp0->indexing_registry);
                    $ids_by_seller[] = $tmp_0->id;
                    if (isset($regIndex['main'])) {
                        //                        $ids_by_seller[] = $regIndex['main'];
                    }
                    else {
                        $arrNoIndexReg_by_seller[] = $tmp_0->id;
                    }
                }
            }

        }
        //arrPrint($arrNoIndexReg_by_seller);
        //        arrPrintWebs($ids_by_seller);

        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            if (isset($arrNoIndexReg_by_seller) && (sizeof($arrNoIndexReg_by_seller) > 0)) {

                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('" . implode("','", $arrNoIndexReg_by_seller) . "')");
                $tReg = $tr->lookupDataRegistries()->result();
                $tRegID = array();
                if (sizeof($tReg) > 0) {
                    foreach ($tReg as $tReg_0) {
                        foreach ($tReg_0 as $key_reg => $val_reg) {
                            if ($key_reg != "transaksi_id") {
                                $tRegID[$tReg_0->transaksi_id][$key_reg] = $tReg_0->id;
                            }
                        }

                    }
                }
                foreach ($arrNoIndexReg_by_seller as $ii => $updID) {
                    //                    $mong->setTableName("transaksi");
                    $valueRe = blobencode($tRegID[$updID]);
                    //                    $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                    $tr = new MdlTransaksi();
                    //                    $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                }
            }

            //region lihat gerbang value dari registry

            if (sizeof($ids_by_seller) > 0) {

                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                //                $tr->addFilter("id in ('" . implode("','", $ids_by_seller) . "')");
                $tr->addFilter("transaksi_id in ('" . implode("','", $ids_by_seller) . "')");
                $tr->setJointSelectFields("main,transaksi_id");
                $trReg = $tr->lookupDataRegistries()->result();
                //                showLast_query("orange");
                foreach ($trReg as $paramReg) {
                    foreach ($paramReg as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $regEntries[$paramReg->transaksi_id] = blobdecode($val_reg);
                        }
                    }
                }
            }

            //endregion
        }


        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();

        foreach ($tmp as $tmp0) {
            if ($jn == "982") {
                $id_cek = $tmp0->id;
            }
            else {
                $id_cek = $tmp0->id_master;
            }
            if (in_array($id_cek, $arrMasterID_result)) {
                $val = isset($regEntries[$tmp0->id]['nett1']) ? $regEntries[$tmp0->id]['nett1'] : 0;
                //reseter
                if (!isset($arrAll['harga'])) {
                    $arrAll['harga'] = 0;
                }
                if (!isset($arrAll['nett'])) {
                    $arrAll['nett'] = 0;
                }
                if (!isset($arrAll['total'])) {
                    $arrAll['total'] = 0;
                }

                if ($tmp0->trash_4 == "0") {
                    // $arrAll['harga'] += $val;
                    $harga = $val;
                    $harga_rej = 0;
                }
                else {
                    $arrAll['total'] += $val;
                    $harga = 0;
                    $harga_rej = $val;
                }

                $data[$tmp0->id] = array(
                    "dtime" => $tmp0->fulldate,
                    // "nomer_top"=>$tmp0->nomer_top,
                    // "id"=>$tmp0->id,
                    "nomer" => $tmp0->nomer,
                    "harga" => $val,
                    // "valid" =>$harga,
                    "total" => $harga_rej,
                    "nett" => $val - $harga_rej,
                );
                $sum = $val - $harga_rej;
                $arrAll['harga'] += $val;
                $arrAll['total'] += $harga_rej;
                $arrAll['nett'] += $sum;
            }
        }
        $header = array(
            "dtime" => "date",
            "nomer" => "receipt",
            "harga" => "amount",
            "total" => " amount reject",
            "nett" => "netto"
        );
        $dataTmp = array(
            "mode" => "recapDetil",
            //            "title" => $this->config->item("heTransaksi_ui")[$jn]['label'] . $seller_nama . " report",
            //            "sub_title" => "detail " . $this->config->item("heTransaksi_ui")[$jn]['label'] . " by salesman $seller_nama",
            "title" => $seller_nama . " report",
            "sub_title" => "detail by salesman $seller_nama",
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->jenisTr,
            "subTitle" => "monthly",
            "items" => $data,
            "subtotal" => $arrAll,
            "headerFields" => $header,
        );
        $this->load->view("activityReports", $dataTmp);
    }

    // ini script laporan sales order per-salesman
    public function viewSalesOrderMonthlySql_ori()
    {
        // $this->load->model("Mdls/MdlMongoMother");
        // $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",

            "1982" => "closed",
            "5982" => "so&nbsp;net",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        //        showLast_query("lime");
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $arrTrjenisPenjualan = array(
            "582so",
            "582spd"
        );
        $arrTrjenisLainnya = array(
            "982",
            "1982"
        );
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $d_indexingMain = $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $d_id = $tmp_0->id;
            $regIDS[] = $d_id;
            $masterID[] = $tmp_0->id_master;
            $d_idMaster = $tmp_0->id_master;
            $d_jenis = $tmp_0->jenis;


            //id_master, sudah daapt ID 582SPO
            if (in_array($d_jenis, $arrTrjenisPenjualan)) {
                $arrPenjualan_idMaster[$d_id] = $d_idMaster;
            }
            if (in_array($d_jenis, $arrTrjenisLainnya)) {
                // $arrPenjualan_lainya[$d_id] = $d_indexingMain;
                $arrPenjualan_lainya[$d_id] = $d_id;
            }

            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);

            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }
        // arrPrint($prevIDS);
        //        arrPrint($arrPenjualan_lainya);
        //        cekLime("total=" . sizeof($tmp) . " pejualan=" . sizeof($arrPenjualan_idMaster) . " lain=" . sizeof($arrPenjualan_lainya));
        //        arrPrint($arrPenjualan_idMaster); // ini transaksiID => id_spo
        //        arrPrint($arrPenjualan_lainya); // ini transaksiID => id registry

        // arrPrint($indListPrev);
        if (sizeof($arrPenjualan_lainya) > 0) {
            $regReferensi = $tr->lookupBaseDataRegistries($arrPenjualan_lainya)->result();
            //            showLast_query("biru");
            //            arrPrintWebs($regReferensi);
            foreach ($regReferensi as $item) {
                // $regId = $item->id; // id registry main
                $regTrId = $item->transaksi_id; // id transaksi 982 atau 1982

                $regValues = blobDecode($item->main);
                $regRefId = isset($regValues['referenceID']) ? $regValues['referenceID'] : $regValues['masterID'];
                //                arrPrintPink($regValues);
                //                $regReferensiId[$regId] = $regRefId;
                //                $regReferensiId[$regTrId] = $regRefId;// masih berisi 582spo dan 582spd

                if ($regValues['jenisTr'] == "982") {
                    $regReferensiId_SPD[$regTrId] = $regRefId;
                }
                else {
                    $regReferensiId[$regTrId] = $regRefId;
                }

            }
            if (sizeof($regReferensiId_SPD) > 0) {
                //                arrPrintWebs($regReferensiId_SPD);
                $tr->setFilters(array());
                $this->db->select(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "id_master"
                ));
                $this->db->where_in('id', $regReferensiId_SPD);
                $spo_tmp = $tr->lookupAll()->result();
                foreach ($spo_tmp as $item) {
                    $result[$item->id] = $item->id_master;
                }
                foreach ($regReferensiId_SPD as $trID => $refID) {
                    //                    cekKuning("$trID => " . $result[$refID]);
                    $regReferensiId[$trID] = $result[$refID];
                }
            }

            //mati_disini();
        }
        $arrSpoId = $arrPenjualan_idMaster + $regReferensiId;

        //        cekLime(sizeof($arrSpoId));
        //        arrPrintWebs($arrSpoId);

        if (sizeof($arrPenjualan_idMaster) > 0) {
            $tr->setFilters(array());
            $this->db->select(array("id",
                "oleh_id",
                "oleh_nama"
            ));
            $this->db->where_in('id', $arrSpoId);
            $spo_0 = $tr->lookupAll()->result();
            //            showLast_query("merah");
            foreach ($spo_0 as $item) {
                $spo_id = $item->id;
                $arrSpo[$spo_id] = $item->oleh_id;
                $spo_oleh_namas[$item->oleh_id] = $item->oleh_nama;
            }
        }
        $listedSellerLabel = $spo_oleh_namas;
        foreach ($arrSpoId as $trId => $dt_spo_id) {
            $spo_olehId = $arrSpo[$dt_spo_id];
            $listedSeller[$trId] = $spo_olehId;
            // $listedSellerLabel[$spo_olehId] =
        }

        // arrPrintWebs($listRegMain);
        // // arrPrint($listedSeller);
        $listedCustomer = $listedSeller;
        $listedCustomerLabel = $listedSellerLabel;
        // arrPrint($listedCustomer);
        // matiHere(__LINE__);

        //region lihat registry main
        // $m->setFilters(array());
        // $m->setParam("id");
        // $m->setInParam($regIDS);
        // $m->setFields(array("transaksi_id", "param", "values"));
        // $m->setTableName("transaksi_registry");
        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        // showLast_query("orange");
        // arrPrint($reg);
        // matiHere(__LINE__);
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);

            }
            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($listedCustomer);
        //         arrPrintWebs($tmp);
        //matiHEre();
        foreach ($tmp as $row) {
            //            arrPrintWebs($row);
            //            $sellID = $listedCustomer[$row->id_master];
            $sellID = $listedCustomer[$row->id];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");

            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;

            //----------
            //            if($sellID == "65"){
            //                cekKuning("$trDtime_m => $valNet");
            //            }
            //----------
            if (!isset($recaplistAll_0[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }
        //        arrPrintWebs($recaplistAll_0['65']);
        //mati_disini();
        foreach ($recaplistAll_0 as $sb_id => $recaplistDates) {
            foreach ($recaplistDates as $sb_date => $recaplistJenis) {
                $so_nilai = isset($recaplistJenis['582so']) ? $recaplistJenis['582so'] : 0;
                $rt_nilai = isset($recaplistJenis['982']) ? $recaplistJenis['982'] : 0;
                $cl_nilai = isset($recaplistJenis['1982']) ? $recaplistJenis['1982'] : 0;
                $sonet_nilai = $so_nilai - $rt_nilai - $cl_nilai;

                $recaplistAll[$sb_id][$sb_date] = $recaplistJenis + array("5982" => $sonet_nilai);
            }
        }

        // arrPrint($recaplistAll_0);
        // arrPrint($recaplistAll);
        //         matiHere();
        //region outstanding per bulan
        // $netPending = array();
        // $summaryData = array();
        // foreach ($recaplistAll as $sID => $sidData) {
        //     foreach ($sidData as $time => $timeData) {
        //         $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
        //         $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
        //         $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
        //         $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
        //         $net = $src - ($srcF1 - $srcF2-$srcF3);
        //         $recaplistAll[$sID][$time]['pending'] = $net;
        //         // foreach ($selectedTrans as $jenis => $alias) {
        //         //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
        //         //         $recaplistAll[$sID][$time][$jenis] = 0;
        //         //     }
        //         // }
        //     }
        //
        //     // arrPrint($sidData);
        // }
        // // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("year(dtime)<='$prevYear'");
            // $this->db->where("year(dtime)='$currYear'");
            $prevData = $tr->lookupMainTransaksi()->result();
            // cekLime($this->db->last_query());
            $idsRelPrev = array();
            $cuID = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
            }

            // arrPrint($cuID);
            // matiHere();
            // foreach($idsRelPrev as $pID){
            foreach ($cuID as $customers_id => $dtaID) {
                $val = 0;
                foreach ($dtaID as $PID) {
                    if (isset($indListPrev[$PID])) {
                        foreach ($indListPrev[$PID] as $iuid) {
                            // cekHitam($iuid);
                            $val += $regEntries[$iuid]['nett1'];
                        }
                        // arrPrint($regEntries);
                    }
                }
                $prevOutsanding[$customers_id]['prev'] = $val;
            }


            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        // arrPrint($listedCustomerLabel);
        asort($listedCustomerLabel);
        // arrPrintWebs($listedCustomerLabel);
        // arrPrint($prevOutsanding);
        // matiHere();
        $data = array(
            //            "mode" => "recap_ext1",
            "mode" => "recap_ext1_new",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "currYear" => $currYear,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthlySql()
    {
        // $this->load->model("Mdls/MdlMongoMother");
        // $m = new MdlMongoMother();
        $this->load->model("Mdls/MdlEmployee");
        $slr = New MdlEmployee();
        $slr->setFilters(array());
        $slrTmp = $slr->lookupAll()->result();
        $arrSellers = array();
        if(sizeof($slrTmp)>0){
            foreach($slrTmp as $spec){
                $arrSellers[$spec->id] = $spec->nama;
            }
        }
//arrPrintPink($arrSellers);

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",

            "1982" => "closed",
            "5982" => "so&nbsp;net",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
//                showLast_query("lime");
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $arrTrjenisPenjualan = array(
            "582so",
            "582spd"
        );
        $arrTrjenisLainnya = array(
            "982",
            "1982"
        );
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $d_indexingMain = $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $d_id = $tmp_0->id;
            $regIDS[] = $d_id;
            $masterID[] = $tmp_0->id_master;
            $d_idMaster = $tmp_0->id_master;
            $d_jenis = $tmp_0->jenis;


            //id_master, sudah daapt ID 582SPO
            if (in_array($d_jenis, $arrTrjenisPenjualan)) {
                $arrPenjualan_idMaster[$d_id] = $d_idMaster;
            }
            if (in_array($d_jenis, $arrTrjenisLainnya)) {
                // $arrPenjualan_lainya[$d_id] = $d_indexingMain;
                $arrPenjualan_lainya[$d_id] = $d_id;
            }

            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);

            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }
        // arrPrint($prevIDS);
        //        arrPrint($arrPenjualan_lainya);
        //        cekLime("total=" . sizeof($tmp) . " pejualan=" . sizeof($arrPenjualan_idMaster) . " lain=" . sizeof($arrPenjualan_lainya));
        //        arrPrint($arrPenjualan_idMaster); // ini transaksiID => id_spo
        //        arrPrint($arrPenjualan_lainya); // ini transaksiID => id registry

        // arrPrint($indListPrev);
        $regReferensiId = array();
        if (isset($arrPenjualan_lainya) && sizeof($arrPenjualan_lainya) > 0) {
            $regReferensi = $tr->lookupBaseDataRegistries($arrPenjualan_lainya)->result();
            //            showLast_query("biru");
            //            arrPrintWebs($regReferensi);
            foreach ($regReferensi as $item) {
                // $regId = $item->id; // id registry main
                $regTrId = $item->transaksi_id; // id transaksi 982 atau 1982

                $regValues = blobDecode($item->main);
                $regRefId = isset($regValues['referenceID']) ? $regValues['referenceID'] : $regValues['masterID'];
                //                arrPrintPink($regValues);
                //                $regReferensiId[$regId] = $regRefId;
                //                $regReferensiId[$regTrId] = $regRefId;// masih berisi 582spo dan 582spd

                if ($regValues['jenisTr'] == "982") {
                    $regReferensiId_SPD[$regTrId] = $regRefId;
                }
                else {
                    $regReferensiId[$regTrId] = $regRefId;
                }

            }
            if (sizeof($regReferensiId_SPD) > 0) {
                //                arrPrintWebs($regReferensiId_SPD);
                $tr->setFilters(array());
                $this->db->select(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "id_master"
                ));
                $this->db->where_in('id', $regReferensiId_SPD);
                $spo_tmp = $tr->lookupAll()->result();
                foreach ($spo_tmp as $item) {
                    $result[$item->id] = $item->id_master;
                }
                foreach ($regReferensiId_SPD as $trID => $refID) {
                    //                    cekKuning("$trID => " . $result[$refID]);
                    $regReferensiId[$trID] = $result[$refID];
                }
            }

            //mati_disini();
        }
        $arrSpoId = $arrPenjualan_idMaster + $regReferensiId;

        //        cekLime(sizeof($arrSpoId));
        //        arrPrintWebs($arrSpoId);

        if (sizeof($arrPenjualan_idMaster) > 0) {
            $tr->setFilters(array());
            $this->db->select(array("id",
                "oleh_id",
                "oleh_nama"
            ));
            $this->db->where_in('id', $arrSpoId);
            $spo_0 = $tr->lookupAll()->result();
            //            showLast_query("merah");
            foreach ($spo_0 as $item) {
                $spo_id = $item->id;
                $arrSpo[$spo_id] = $item->oleh_id;
                $spo_oleh_namas[$item->oleh_id] = $item->oleh_nama;
            }
        }
        $listedSellerLabel = $spo_oleh_namas;
        foreach ($arrSpoId as $trId => $dt_spo_id) {
            $spo_olehId = $arrSpo[$dt_spo_id];
            $listedSeller[$trId] = $spo_olehId;
            // $listedSellerLabel[$spo_olehId] =
        }

        // arrPrintWebs($listRegMain);
        // // arrPrint($listedSeller);
        $listedCustomer = $listedSeller;
        $listedCustomerLabel = $listedSellerLabel;
        // arrPrint($listedCustomer);
        // matiHere(__LINE__);

        //region lihat registry main
        // $m->setFilters(array());
        // $m->setParam("id");
        // $m->setInParam($regIDS);
        // $m->setFields(array("transaksi_id", "param", "values"));
        // $m->setTableName("transaksi_registry");
        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        // showLast_query("orange");
        // arrPrint($reg);
        // matiHere(__LINE__);
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);

            }
            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($listedCustomer);
        //         arrPrintWebs($tmp);
        //matiHEre();
        foreach ($tmp as $row) {
            //            arrPrintWebs($row);
            $listedCustomeres[$row->seller_id] = $row->seller_nama;
            $sellID = $listedCustomer[$row->id];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");

            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;

            //----------
            //            if($sellID == "65"){
            //                cekKuning("$trDtime_m => $valNet");
            //            }
            //----------
            if (!isset($recaplistAll_0[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }
        // cekBiru($listedCustomeres);
        //        arrPrintWebs($recaplistAll_0['65']);
        //mati_disini();
        foreach ($recaplistAll_0 as $sb_id => $recaplistDates) {
            foreach ($recaplistDates as $sb_date => $recaplistJenis) {
                $so_nilai = isset($recaplistJenis['582so']) ? $recaplistJenis['582so'] : 0;
                $rt_nilai = isset($recaplistJenis['982']) ? $recaplistJenis['982'] : 0;
                $cl_nilai = isset($recaplistJenis['1982']) ? $recaplistJenis['1982'] : 0;
                $sonet_nilai = $so_nilai - $rt_nilai - $cl_nilai;

                $recaplistAll[$sb_id][$sb_date] = $recaplistJenis + array("5982" => $sonet_nilai);

                if (!isset($closePerId[$sb_id])) {
                    $closePerId[$sb_id] = 0;
                }
                $closePerId[$sb_id] += $cl_nilai;
            }
        }

        // arrPrint($recaplistAll_0);
        // arrPrint($recaplistAll);
        //         matiHere();
        //region outstanding per bulan
        // $netPending = array();
        // $summaryData = array();
        // foreach ($recaplistAll as $sID => $sidData) {
        //     foreach ($sidData as $time => $timeData) {
        //         $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
        //         $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
        //         $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
        //         $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
        //         $net = $src - ($srcF1 - $srcF2-$srcF3);
        //         $recaplistAll[$sID][$time]['pending'] = $net;
        //         // foreach ($selectedTrans as $jenis => $alias) {
        //         //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
        //         //         $recaplistAll[$sID][$time][$jenis] = 0;
        //         //     }
        //         // }
        //     }
        //
        //     // arrPrint($sidData);
        // }
        // // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("year(dtime)<='$prevYear'");
            // $this->db->where("year(dtime)='$currYear'");
            $prevData = $tr->lookupMainTransaksi()->result();
//            cekLime($this->db->last_query());
            $idsRelPrev = array();
            $cuID = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
            }

            // region woles
            /* ------------------------------------
           * mengambil data so
           * ------------------------------------*/
            $tr->setFilters(array());
            // $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("jenis='582so'");
            $tr->addFilter("link_id='0'");
            $tr->addFilter("year(transaksi.dtime)<='$prevYear'");
            $tr->addFilter("valid_qty>'0'");
            // $tr->addFilter("((transaksi_data.valid_qty>'0') or (transaksi_data.cancel_qty>'0'))");
            // $tr->addFilter("transaksi_data.transaksi_id=transaksi.id");
            // $this->db->where("year(dtime)='$currYear'");
            // $this->db->limit(10);
            // $where_custom = "(valid_qty>'0' OR cancel_qty>'0')";
            // $this->db->where($where_custom);
            $prevData = $tr->lookupJoined_OLD()->result();
//            cekKuning($this->db->last_query());
//            cekHere(sizeof($prevData));
            // arrPrint($prevData);
            // // id_master
            // matiHere(__LINE__);

            $idsRelPrev = array();
            $cuID_x = array();
            $validQty = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID_x[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
                // $cuID[$dtaTmpPRev->oleh_id][] = $dtaTmpPRev->id;
                $idTransaksiSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->transaksi_id;
                $idSellerSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->seller_id;
                $namaSellerSo[$dtaTmpPRev->id_master]['seller_nama'] = $dtaTmpPRev->seller_nama;

                $valid_qty = isset($dtaTmpPRev->valid_qty) ? $dtaTmpPRev->valid_qty : 0;
                // $cancel_qty = isset($dtaTmpPRev->cancel_qty) ? $dtaTmpPRev->cancel_qty : 0;
                $validQty[$dtaTmpPRev->transaksi_id][$dtaTmpPRev->produk_id] = ($valid_qty);
            }

            // $this->db->select("main");
            $tr->setJointSelectFields("transaksi_id,items");
            // $regPrevs = $tr->lookupBaseDataRegistries("16373")->result();
            $regPrevs = $tr->lookupBaseDataRegistries($idTransaksiSo)->result();
//            showLast_query("merah");
            // arrPrint($regPrevs);
            $arrPrev = array(
                77 => 0,
                718 => 0,
                664 => 0,
                719 => 0,
//                65 => 108215690,
                65 => 30237954,
                57 => 0,
                551 => 0,
                712 => 0,
//                73 => 2849999,
                73 => 31213718,
                274 => 0,
                182 => 0,
//                61 => 172796535,
                61 => 34766344,
                205 => 0,
//                576 => 56079964,
                576 => 449897307,
                567 => 0,
//                69 => 35999986,
                69 => 10126383744,
                663 => 0,
                664 => 1362000012,
                // 77	0
                // 718	0
                // 664	0
            );



            $items = array();
            foreach ($regPrevs as $regPrev) {
                $transaksi_id_2 = $regPrev->transaksi_id;
                $items = blobDecode($regPrev->items);
                // cekBiru($transaksi_id_2);
                // arrPrintWebs($items);
                $sellerId_nya = $idSellerSo[$transaksi_id_2];

                // if(isset($validQty[$transaksi_id_2])){
                if (sizeof($items) > 0) {
                    $valunya = 0;
                    foreach ($items as $produk_id_nya => $qty_datas) {
                        if (isset($validQty[$transaksi_id_2][$produk_id_nya])) {

                            $valunya += $qty_datas['nett1'] * $validQty[$transaksi_id_2][$produk_id_nya];
                        }
                        else {
                            $arrayIds[$transaksi_id_2] = $transaksi_id_2;
                            // matiHere($transaksi_id_2 . " " . __LINE__);
                        }
                    }
                }

                // }

                // arrPrint($items);
                // arrPrint($items);

                $closeForPrev = isset($closePerId[$sellerId_nya]) ? $closePerId[$sellerId_nya] : 0;
                // $closeForPrev = 0;

                // $prevOutsanding_x[$sellerId_nya] = ($valunya);
                // $prevOutsanding[$sellerId_nya]['prev'] = ($valunya + $closeForPrev);
                $valunya = isset($arrPrev[$sellerId_nya]) ? $arrPrev[$sellerId_nya] : $sellerId_nya;
                $prevOutsanding[$sellerId_nya]['prev'] = ($valunya);
                // $prevOutsanding[$sellerId_nya]['prev'] = 0;
            }
            // endregion

            // arrPrint($prevOutsanding_x);

            // arrPrint($cuID);
            // matiHere();
            // foreach($idsRelPrev as $pID){
            foreach ($cuID as $customers_id => $dtaID) {
                $val = 0;
                foreach ($dtaID as $PID) {
                    if (isset($indListPrev[$PID])) {
                        foreach ($indListPrev[$PID] as $iuid) {
                            // cekHitam($iuid);
                            $val2 = isset($prevOutsanding_x[$iuid]) ? $prevOutsanding_x[$iuid] : 0;

                            $val += ($regEntries[$iuid]['nett1'] + $val2);
                        }
                        // arrPrint($regEntries);
                    }
                }


                // $prevOutsanding[$customers_id]['prev'] = $val;
            }

            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        // arrPrint($listedCustomerLabel);
        asort($listedCustomerLabel);
        // arrPrintWebs($listedCustomerLabel);
        // arrPrint($prevOutsanding);

//arrPrintWebs($arrPrev);
        foreach($arrPrev as $sellerID => $xxxxxxxx){
            if(!array_key_exists($sellerID, $listedCustomerLabel)){
                $listedCustomerLabel[$sellerID] = isset($arrSellers[$sellerID]) ? $arrSellers[$sellerID] : "-";
            }
        }
//        arrPrint($listedCustomerLabel);


        $data = array(
            //            "mode" => "recap_ext1",
            "mode" => "recap_ext1_new",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "Laporan Sales Order",
            "subTitle" => "<small>diakui outstanding sebelum packing list</small>",
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "currYear" => $currYear,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthlySql_modif()
    {
        // $this->load->model("Mdls/MdlMongoMother");
        // $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",

            "1982" => "closed",
            "5982" => "so&nbsp;net",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        // $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        // showLast_query("lime");
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $arrTrjenisPenjualan = array(
            "582so",
            "582spd"
        );
        $arrTrjenisLainnya = array(
            "982",
            "1982"
        );
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $d_indexingMain = $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $d_id = $tmp_0->id;
            $regIDS[] = $d_id;
            $masterID[] = $tmp_0->id_master;
            $d_idMaster = $tmp_0->id_master;
            $d_jenis = $tmp_0->jenis;


            //id_master, sudah daapt ID 582SPO
            if (in_array($d_jenis, $arrTrjenisPenjualan)) {
                $arrPenjualan_idMaster[$d_id] = $d_idMaster;
            }
            if (in_array($d_jenis, $arrTrjenisLainnya)) {
                // $arrPenjualan_lainya[$d_id] = $d_indexingMain;
                $arrPenjualan_lainya[$d_id] = $d_id;
            }

            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);

            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }
        // arrPrint($prevIDS);
        //        arrPrint($arrPenjualan_lainya);
        //        cekLime("total=" . sizeof($tmp) . " pejualan=" . sizeof($arrPenjualan_idMaster) . " lain=" . sizeof($arrPenjualan_lainya));
        //        arrPrint($arrPenjualan_idMaster); // ini transaksiID => id_spo
        //        arrPrint($arrPenjualan_lainya); // ini transaksiID => id registry

        // arrPrint($indListPrev);
        if (sizeof($arrPenjualan_lainya) > 0) {
            $regReferensi = $tr->lookupBaseDataRegistries($arrPenjualan_lainya)->result();
            //            showLast_query("biru");
            //            arrPrintWebs($regReferensi);
            foreach ($regReferensi as $item) {
                // $regId = $item->id; // id registry main
                $regTrId = $item->transaksi_id; // id transaksi 982 atau 1982

                $regValues = blobDecode($item->main);
                $regRefId = isset($regValues['referenceID']) ? $regValues['referenceID'] : $regValues['masterID'];
                //                arrPrintPink($regValues);
                //                $regReferensiId[$regId] = $regRefId;
                //                $regReferensiId[$regTrId] = $regRefId;// masih berisi 582spo dan 582spd

                if ($regValues['jenisTr'] == "982") {
                    $regReferensiId_SPD[$regTrId] = $regRefId;
                }
                else {
                    $regReferensiId[$regTrId] = $regRefId;
                }

            }
            if (sizeof($regReferensiId_SPD) > 0) {
                //                arrPrintWebs($regReferensiId_SPD);
                $tr->setFilters(array());
                $this->db->select(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "id_master"
                ));
                $this->db->where_in('id', $regReferensiId_SPD);
                $spo_tmp = $tr->lookupAll()->result();
                foreach ($spo_tmp as $item) {
                    $result[$item->id] = $item->id_master;
                }
                foreach ($regReferensiId_SPD as $trID => $refID) {
                    //                    cekKuning("$trID => " . $result[$refID]);
                    $regReferensiId[$trID] = $result[$refID];
                }
            }

            //mati_disini();
        }
        $arrSpoId = $arrPenjualan_idMaster + $regReferensiId;

        //        cekLime(sizeof($arrSpoId));
        //        arrPrintWebs($arrSpoId);

        if (sizeof($arrPenjualan_idMaster) > 0) {
            $tr->setFilters(array());
            $this->db->select(array("id",
                "oleh_id",
                "oleh_nama"
            ));
            $this->db->where_in('id', $arrSpoId);
            $spo_0 = $tr->lookupAll()->result();
            //            showLast_query("merah");
            foreach ($spo_0 as $item) {
                $spo_id = $item->id;
                $arrSpo[$spo_id] = $item->oleh_id;
                $spo_oleh_namas[$item->oleh_id] = $item->oleh_nama;
            }
        }
        $listedSellerLabel = $spo_oleh_namas;
        foreach ($arrSpoId as $trId => $dt_spo_id) {
            $spo_olehId = $arrSpo[$dt_spo_id];
            $listedSeller[$trId] = $spo_olehId;
            // $listedSellerLabel[$spo_olehId] =
        }

        // arrPrintWebs($listRegMain);
        // // arrPrint($listedSeller);
        $listedCustomer = $listedSeller;
        $listedCustomerLabel = $listedSellerLabel;
        // arrPrint($listedCustomer);
        // matiHere(__LINE__);

        //region lihat registry main
        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        // showLast_query("orange");
        // arrPrint($reg);
        // matiHere(__LINE__);
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);
            }

            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($listedCustomer);
        //         arrPrintWebs($tmp);
        //matiHEre();
        foreach ($tmp as $row) {
            //            arrPrintWebs($row);
            //            $sellID = $listedCustomer[$row->id_master];
            $sellID = $listedCustomer[$row->id];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");

            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;

            //----------
            //            if($sellID == "65"){
            //                cekKuning("$trDtime_m => $valNet");
            //            }
            //----------
            if (!isset($recaplistAll_0[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }
        //        arrPrintWebs($recaplistAll_0['65']);
        //mati_disini();
        foreach ($recaplistAll_0 as $sb_id => $recaplistDates) {
            foreach ($recaplistDates as $sb_date => $recaplistJenis) {
                $so_nilai = isset($recaplistJenis['582so']) ? $recaplistJenis['582so'] : 0;
                $rt_nilai = isset($recaplistJenis['982']) ? $recaplistJenis['982'] : 0;
                $cl_nilai = isset($recaplistJenis['1982']) ? $recaplistJenis['1982'] : 0;
                $sonet_nilai = $so_nilai - $rt_nilai - $cl_nilai;

                $recaplistAll[$sb_id][$sb_date] = $recaplistJenis + array("5982" => $sonet_nilai);

                if (!isset($closePerId[$sb_id])) {
                    $closePerId[$sb_id] = 0;
                }
                $closePerId[$sb_id] += $cl_nilai;
            }
        }

        // arrPrint($recaplistAll_0);
        // arrPrint($closePerId);
        //         matiHere();
        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            /* ------------------------------------
             * mengambil data so
             * ------------------------------------*/
            $tr->setFilters(array());
            // $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("jenis='582so'");
            $tr->addFilter("link_id='0'");
            $tr->addFilter("year(transaksi.dtime)<='$prevYear'");
            $tr->addFilter("valid_qty>'0'");
            // $tr->addFilter("((transaksi_data.valid_qty>'0') or (transaksi_data.cancel_qty>'0'))");
            // $tr->addFilter("transaksi_data.transaksi_id=transaksi.id");
            // $this->db->where("year(dtime)='$currYear'");
            // $this->db->limit(10);
            // $where_custom = "(valid_qty>'0' OR cancel_qty>'0')";
            // $this->db->where($where_custom);
            $prevData = $tr->lookupJoined_OLD()->result();
            // cekKuning($this->db->last_query());
            // cekHere(sizeof($prevData));
            // arrPrint($prevData);
            // // id_master
            // matiHere(__LINE__);

            $idsRelPrev = array();
            $cuID = array();
            $validQty = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
                // $cuID[$dtaTmpPRev->oleh_id][] = $dtaTmpPRev->id;
                $idTransaksiSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->transaksi_id;
                $idSellerSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->seller_id;
                $namaSellerSo[$dtaTmpPRev->id_master]['seller_nama'] = $dtaTmpPRev->seller_nama;

                $valid_qty = isset($dtaTmpPRev->valid_qty) ? $dtaTmpPRev->valid_qty : 0;
                $cancel_qty = isset($dtaTmpPRev->cancel_qty) ? $dtaTmpPRev->cancel_qty : 0;
                $validQty[$dtaTmpPRev->transaksi_id][$dtaTmpPRev->produk_id] = ($valid_qty);
            }

            // $this->db->select("main");
            $tr->setJointSelectFields("transaksi_id,items");
            // $regPrevs = $tr->lookupBaseDataRegistries("16373")->result();
            $regPrevs = $tr->lookupBaseDataRegistries($idTransaksiSo)->result();
            // showLast_query("merah");
            // arrPrint($regPrevs);

            $items = array();
            foreach ($regPrevs as $regPrev) {
                $transaksi_id_2 = $regPrev->transaksi_id;
                $items = blobDecode($regPrev->items);
                // cekBiru($transaksi_id_2);
                // arrPrintWebs($items);
                $sellerId_nya = $idSellerSo[$transaksi_id_2];

                // if(isset($validQty[$transaksi_id_2])){
                if (sizeof($items) > 0) {
                    $valunya = 0;
                    foreach ($items as $produk_id_nya => $qty_datas) {
                        if (isset($validQty[$transaksi_id_2][$produk_id_nya])) {

                            $valunya += $qty_datas['nett1'] * $validQty[$transaksi_id_2][$produk_id_nya];
                        }
                        else {
                            $arrayIds[$transaksi_id_2] = $transaksi_id_2;
                            // matiHere($transaksi_id_2 . " " . __LINE__);
                        }
                    }
                }

                // }

                // arrPrint($items);
                // arrPrint($items);

                $closeForPrev = isset($closePerId[$sellerId_nya]) ? $closePerId[$sellerId_nya] : 0;
                // $closeForPrev = 0;

                $prevOutsanding[$sellerId_nya]['prev'] = ($valunya + $closeForPrev);
            }


            // arrPrintPink($arrayIds);
            // arrPrintPink($prevOutsanding);
            // matiHere();
            // // arrPrintPink($cuID);
            // arrPrint($regEntries);
            // // arrPrint($prevData);
            // // cekHijau(sizeof($prevData));
            // // cekMerah(sizeof($cuID));
            // matiHere();
            // // foreach($idsRelPrev as $pID){
            //
            // // foreach ($cuID as $customers_id => $dtaID) {
            // foreach ($idSellerSpo as $transaksi_id_nya => $dtaID) {
            //     $val = 0;
            //     // foreach ($dtaID as $PID) {
            //     //     if (isset($indListPrev[$PID])) {
            //     //         foreach ($indListPrev[$PID] as $iuid) {
            //     // cekHitam($iuid);
            //     $val += $regEntries[$iuid]['nett1'];
            //     // }
            //     // arrPrint($regEntries);
            //     // }
            //     // }
            //     $prevOutsanding[$customers_id]['prev'] = $val;
            // }


            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        // arrPrint($listedCustomerLabel);
        asort($listedCustomerLabel);
        // arrPrintWebs($listedCustomerLabel);
        // arrPrint($prevOutsanding);
        // matiHere();
        $data = array(
            //            "mode" => "recap_ext1",
            "mode" => "recap_ext1_new",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "currYear" => $currYear,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthlySql2()
    {
        // $this->load->model("Mdls/MdlMongoMother");
        // $m = new MdlMongoMother();
        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",

            "1982" => "closed",
            "5982" => "so&nbsp;net",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        // $tr->addFilter("trash_4='0'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(dtime)='$currYear'");
        // $this->db->limit(20);
        $tmp = $tr->lookupMainTransaksi()->result();
        // showLast_query("lime");
        //endregion


        // $m->setParam("jenis");
        // $m->setInParam(array("582so","582spd","982"));
        // $m->addFilter($filter);
        // $this->mongo_db->like("dtime", "
        //");
        // $tmp =$m->lookUpMainTransaksi();
        $arrTrjenisPenjualan = array(
            "582so",
            "582spd"
        );
        $arrTrjenisLainnya = array(
            "982",
            "1982"
        );
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $d_indexingMain = $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $d_id = $tmp_0->id;
            $regIDS[] = $d_id;
            $masterID[] = $tmp_0->id_master;
            $d_idMaster = $tmp_0->id_master;
            $d_jenis = $tmp_0->jenis;


            //id_master, sudah daapt ID 582SPO
            if (in_array($d_jenis, $arrTrjenisPenjualan)) {
                $arrPenjualan_idMaster[$d_id] = $d_idMaster;
            }
            if (in_array($d_jenis, $arrTrjenisLainnya)) {
                // $arrPenjualan_lainya[$d_id] = $d_indexingMain;
                $arrPenjualan_lainya[$d_id] = $d_id;
            }

            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);

            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }
        // arrPrint($prevIDS);
        //        arrPrint($arrPenjualan_lainya);
        //        cekLime("total=" . sizeof($tmp) . " pejualan=" . sizeof($arrPenjualan_idMaster) . " lain=" . sizeof($arrPenjualan_lainya));
        //        arrPrint($arrPenjualan_idMaster); // ini transaksiID => id_spo
        //        arrPrint($arrPenjualan_lainya); // ini transaksiID => id registry

        // arrPrint($indListPrev);
        if (sizeof($arrPenjualan_lainya) > 0) {
            $regReferensi = $tr->lookupBaseDataRegistries($arrPenjualan_lainya)->result();
            //            showLast_query("biru");
            //            arrPrintWebs($regReferensi);
            foreach ($regReferensi as $item) {
                // $regId = $item->id; // id registry main
                $regTrId = $item->transaksi_id; // id transaksi 982 atau 1982

                $regValues = blobDecode($item->main);
                $regRefId = isset($regValues['referenceID']) ? $regValues['referenceID'] : $regValues['masterID'];
                //                arrPrintPink($regValues);
                //                $regReferensiId[$regId] = $regRefId;
                //                $regReferensiId[$regTrId] = $regRefId;// masih berisi 582spo dan 582spd

                if ($regValues['jenisTr'] == "982") {
                    $regReferensiId_SPD[$regTrId] = $regRefId;
                }
                else {
                    $regReferensiId[$regTrId] = $regRefId;
                }

            }
            if (sizeof($regReferensiId_SPD) > 0) {
                //                arrPrintWebs($regReferensiId_SPD);
                $tr->setFilters(array());
                $this->db->select(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "id_master"
                ));
                $this->db->where_in('id', $regReferensiId_SPD);
                $spo_tmp = $tr->lookupAll()->result();
                foreach ($spo_tmp as $item) {
                    $result[$item->id] = $item->id_master;
                }
                foreach ($regReferensiId_SPD as $trID => $refID) {
                    //                    cekKuning("$trID => " . $result[$refID]);
                    $regReferensiId[$trID] = $result[$refID];
                }
            }

            //mati_disini();
        }
        $arrSpoId = $arrPenjualan_idMaster + $regReferensiId;

        //        cekLime(sizeof($arrSpoId));
        //        arrPrintWebs($arrSpoId);

        if (sizeof($arrPenjualan_idMaster) > 0) {
            $tr->setFilters(array());
            $this->db->select(array("id",
                "oleh_id",
                "oleh_nama"
            ));
            $this->db->where_in('id', $arrSpoId);
            $spo_0 = $tr->lookupAll()->result();
            //            showLast_query("merah");
            foreach ($spo_0 as $item) {
                $spo_id = $item->id;
                $arrSpo[$spo_id] = $item->oleh_id;
                $spo_oleh_namas[$item->oleh_id] = $item->oleh_nama;
            }
        }
        $listedSellerLabel = $spo_oleh_namas;
        foreach ($arrSpoId as $trId => $dt_spo_id) {
            $spo_olehId = $arrSpo[$dt_spo_id];
            $listedSeller[$trId] = $spo_olehId;
            // $listedSellerLabel[$spo_olehId] =
        }

        // arrPrintWebs($listRegMain);
        // // arrPrint($listedSeller);
        $listedCustomer = $listedSeller;
        $listedCustomerLabel = $listedSellerLabel;
        // arrPrint($listedCustomer);
        // matiHere(__LINE__);

        //region lihat registry main
        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        // showLast_query("orange");
        // arrPrint($reg);
        // matiHere(__LINE__);
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);
            }

            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($listedCustomer);
        //         arrPrintWebs($tmp);
        //matiHEre();
        foreach ($tmp as $row) {
            //            arrPrintWebs($row);
            //            $sellID = $listedCustomer[$row->id_master];
            $sellID = $listedCustomer[$row->id];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");

            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;

            //----------
            //            if($sellID == "65"){
            //                cekKuning("$trDtime_m => $valNet");
            //            }
            //----------
            if (!isset($recaplistAll_0[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }
        //        arrPrintWebs($recaplistAll_0['65']);
        //mati_disini();
        foreach ($recaplistAll_0 as $sb_id => $recaplistDates) {
            foreach ($recaplistDates as $sb_date => $recaplistJenis) {
                $so_nilai = isset($recaplistJenis['582so']) ? $recaplistJenis['582so'] : 0;
                $rt_nilai = isset($recaplistJenis['982']) ? $recaplistJenis['982'] : 0;
                $cl_nilai = isset($recaplistJenis['1982']) ? $recaplistJenis['1982'] : 0;
                $sonet_nilai = $so_nilai - $rt_nilai - $cl_nilai;

                $recaplistAll[$sb_id][$sb_date] = $recaplistJenis + array("5982" => $sonet_nilai);

                if (!isset($closePerId[$sb_id])) {
                    $closePerId[$sb_id] = 0;
                }
                $closePerId[$sb_id] += $cl_nilai;
            }
        }

        // arrPrint($recaplistAll_0);
        // arrPrint($closePerId);
        //         matiHere();
        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            /* ------------------------------------
             * mengambil data so
             * ------------------------------------*/
            $tr->setFilters(array());
            // $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("jenis='582so'");
            $tr->addFilter("link_id='0'");
            $tr->addFilter("year(transaksi.dtime)<='$prevYear'");
            $tr->addFilter("valid_qty>'0'");
            // $tr->addFilter("((transaksi_data.valid_qty>'0') or (transaksi_data.cancel_qty>'0'))");
            // $tr->addFilter("transaksi_data.transaksi_id=transaksi.id");
            // $this->db->where("year(dtime)='$currYear'");
            // $this->db->limit(10);
            // $where_custom = "(valid_qty>'0' OR cancel_qty>'0')";
            // $this->db->where($where_custom);
            $prevData = $tr->lookupJoined_OLD()->result();
            // cekKuning($this->db->last_query());
            // cekHere(sizeof($prevData));
            // arrPrint($prevData);
            // // id_master
            // matiHere(__LINE__);

            $idsRelPrev = array();
            $cuID = array();
            $validQty = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
                // $cuID[$dtaTmpPRev->oleh_id][] = $dtaTmpPRev->id;
                $idTransaksiSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->transaksi_id;
                $idSellerSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->seller_id;
                $namaSellerSo[$dtaTmpPRev->id_master]['seller_nama'] = $dtaTmpPRev->seller_nama;

                $valid_qty = isset($dtaTmpPRev->valid_qty) ? $dtaTmpPRev->valid_qty : 0;
                $cancel_qty = isset($dtaTmpPRev->cancel_qty) ? $dtaTmpPRev->cancel_qty : 0;
                $validQty[$dtaTmpPRev->transaksi_id][$dtaTmpPRev->produk_id] = ($valid_qty);
            }

            // $this->db->select("main");
            $tr->setJointSelectFields("transaksi_id,items");
            // $regPrevs = $tr->lookupBaseDataRegistries("16373")->result();
            $regPrevs = $tr->lookupBaseDataRegistries($idTransaksiSo)->result();
            // showLast_query("merah");
            // arrPrint($regPrevs);

            $items = array();
            foreach ($regPrevs as $regPrev) {
                $transaksi_id_2 = $regPrev->transaksi_id;
                $items = blobDecode($regPrev->items);
                // cekBiru($transaksi_id_2);
                // arrPrintWebs($items);
                $sellerId_nya = $idSellerSo[$transaksi_id_2];

                // if(isset($validQty[$transaksi_id_2])){
                if (sizeof($items) > 0) {
                    $valunya = 0;
                    foreach ($items as $produk_id_nya => $qty_datas) {
                        if (isset($validQty[$transaksi_id_2][$produk_id_nya])) {

                            $valunya += $qty_datas['nett1'] * $validQty[$transaksi_id_2][$produk_id_nya];
                        }
                        else {
                            $arrayIds[$transaksi_id_2] = $transaksi_id_2;
                            // matiHere($transaksi_id_2 . " " . __LINE__);
                        }
                    }
                }

                // }

                // arrPrint($items);
                // arrPrint($items);

                $closeForPrev = isset($closePerId[$sellerId_nya]) ? $closePerId[$sellerId_nya] : 0;
                // $closeForPrev = 0;

                // $prevOutsanding[$sellerId_nya]['prev'] = ($valunya + $closeForPrev);
                $prevOutsanding[$sellerId_nya]['prev'] = 0;
            }


            // arrPrintPink($arrayIds);
            // arrPrintPink($prevOutsanding);
            // matiHere();
            // // arrPrintPink($cuID);
            // arrPrint($regEntries);
            // // arrPrint($prevData);
            // // cekHijau(sizeof($prevData));
            // // cekMerah(sizeof($cuID));
            // matiHere();
            // // foreach($idsRelPrev as $pID){
            //
            // // foreach ($cuID as $customers_id => $dtaID) {
            // foreach ($idSellerSpo as $transaksi_id_nya => $dtaID) {
            //     $val = 0;
            //     // foreach ($dtaID as $PID) {
            //     //     if (isset($indListPrev[$PID])) {
            //     //         foreach ($indListPrev[$PID] as $iuid) {
            //     // cekHitam($iuid);
            //     $val += $regEntries[$iuid]['nett1'];
            //     // }
            //     // arrPrint($regEntries);
            //     // }
            //     // }
            //     $prevOutsanding[$customers_id]['prev'] = $val;
            // }


            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        // arrPrint($listedCustomerLabel);
        asort($listedCustomerLabel);
        // arrPrintWebs($listedCustomerLabel);
        // arrPrint($prevOutsanding);
        // matiHere();
        $data = array(
            //            "mode" => "recap_ext1",
            "mode" => "recap_ext1_new",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "",
            "subTitle" => "monthly, " . $currYear,
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "currYear" => $currYear,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }

    public function viewSalesOrderMonthlySql3()
    {
        // $this->load->model("Mdls/MdlMongoMother");
        // $m = new MdlMongoMother();
        $this->load->model("Mdls/MdlEmployee");
        $slr = New MdlEmployee();
        $slr->setFilters(array());
        $slrTmp = $slr->lookupAll()->result();
        $arrSellers = array();
        if(sizeof($slrTmp)>0){
            foreach($slrTmp as $spec){
                $arrSellers[$spec->id] = $spec->nama;
            }
        }
//arrPrintPink($arrSellers);

        $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;

        $permanentFilter = "oleh_id";
        $currYear = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $prevYear = $currYear - 1;
        // cekLime($prevYear);
        if ($currYear == date("Y")) {
            $currMonth = date("m");
        }
        else {
            $currMonth = "12";
        }
        // $currMonth = isset($_GET['m']) ? $_GET['m'] : date("m");
        $dateStr = $currYear . "-" . $currMonth;
        $stID = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        $sID = isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter;
        //        cekHitam($sID);
        $sID = isset($this->sID_alias[$sID]) ? $this->sID_alias[$sID] : "";

        //region list transaksi
        $selectedTrans = array(
            "582so" => "sales order",
            "582spd" => "packing list",
            "982" => "return",

            "1982" => "closed",
            "5982" => "so&nbsp;net",
            // "pending" => "out standing"
        );


        //endregion
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        // arrPrint($stepNames);
        if ($this->session->login['cabang_id'] > 0) {
            // $filter = array(
            //     "cabang_id" => $selectedCabang,
            // );
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }
        else {
            $selectedCabang = array();
            // "cabang_id"=>,
            // $selectedCabang = "transaksi.cabang_id<>-1";
        }

        // $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
        //
        // //region prevYear
        // $tr->addFilter("transaksi.trash_4='0'");
        // $tr->addFilter("transaksi.link_id='0'");
        // $tr->addFilter("transaksi.jenis='582so'");
        // $tr->addFilter("transaksi_data.valid_qty>0");
        // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // // $this->db->where("year(transaksi.dtime)<='$prevYear'");
        // $pmp = $tr->lookupJoined()->result();
        // // cekbiru($this->db->last_query());
        // if(sizeof($pmp)>0){
        //     matiHere("under maintenance");
        //     $pRegIDS = array();
        //     $pMasterID = array();
        //     $oldData = 0;
        //     $strOld = array();
        //     if (sizeof($pmp) > 0) {
        //         foreach ($pmp as $pmp_0) {
        //             $pIndexingMain = strlen($pmp_0->indexing_registry) > 10 ? blobDecode($pmp_0->indexing_registry)['main'] : "";
        //             if (strlen($pIndexingMain) == 0) {
        //                 $oldData++;
        //                 $strOld[] = $pmp_0->id;
        //             }
        //             // arrPrint($pIndexingMain);
        //             // arrprint($indexingMain);
        //             $pRegIDS[] = "$pIndexingMain";
        //             $pMasterID[] = $pmp_0->id_master;
        //             $pTrIds_[$pmp_0->id] = 1;
        //             $pTrIdDts[$pmp_0->id]['dtime'] = $pmp_0->dtime;
        //             $pTrIdDts[$pmp_0->id]['olehID'] = $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['sellerID'] = isset($pmp_0->seller_id) ? $pmp_0->seller_id : $pmp_0->oleh_id;
        //             $pTrIdDts[$pmp_0->id]['cabangID'] = $pmp_0->cabang_id;
        //             $pTrIdDts[$pmp_0->id]['pihakID'] = ($pmp_0->suppliers_id < 1 ? $pmp_0->customers_id : $pmp_0->suppliers_id);
        //             // arrPrint($indexingMain);
        //         }
        //         // arrprint($strOld);
        //         //auto updater indexing registry
        //         if (sizeof($strOld) > 0) {
        //             // matiHere();
        //             $m->setFilters(array());
        //             $m->setParam("transaksi_id");
        //             $m->setInParam($strOld);
        //             $m->setFields(array("id", "transaksi_id", "param", "values"));
        //             $m->setTableName("transaksi_registry");
        //             $tReg = $m->lookUpAll();
        //             $tRegID = array();
        //             if (sizeof($tReg) > 0) {
        //                 foreach ($tReg as $tReg_0) {
        //                     $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
        //                     // arrPrint($regEntries);
        //
        //                 }
        //             }
        //             $mong = new MdlMongoMother();
        //             $mong->setFilters(array());
        //             foreach ($strOld as $ii => $updID) {
        //                 // $mongListUpadte['update']['main'][] = array(
        //                 //     "where" => array("id" => $no,),
        //                 //     "value" => $arrData,
        //                 // );
        //                 $mong->setTableName("transaksi");
        //                 $valueRe = blobencode($tRegID[$updID]);
        //                 $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //                 $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
        //             }
        //
        //         }
        //
        //
        //     }
        //
        //     if (sizeof($pMasterID) > 0) {
        //
        //         $m->setFilters(array());
        //         $m->setParam("id");
        //         $m->setInParam($pMasterID);
        //         $m->setTableName("transaksi");
        //         $m->setFields(array("id", "oleh_id", "oleh_nama", "customers_id", "customers_nama"));
        //         $tempMaster = $m->lookUpAll();
        //         // arrPrint($tempMaster);
        //         // matiHEre();
        //         $pListedSeller = array();
        //         $pListedSellerLabel = array();
        //         $pListedCustomer = array();
        //         $plistedCustomerLabel = array();
        //         foreach ($tempMaster as $tempMaster_0) {
        //             $pListedSeller[$tempMaster_0['id']] = $tempMaster_0['oleh_id'];
        //             $pListedSellerLabel[$tempMaster_0['oleh_id']] = $tempMaster_0['oleh_nama'];
        //             $pListedCustomer[$tempMaster_0['id']] = $tempMaster_0['customers_id'];
        //             $pListedCustomerLabel[$tempMaster_0['customers_id']] = $tempMaster_0['customers_nama'];
        //         }
        //
        //
        //         // arrPrint($listedSeller);
        //     }
        //     $m->setFilters(array());
        //     $m->setParam("id");
        //     $m->setInParam($pRegIDS);
        //     $m->setFields(array("transaksi_id", "param", "values"));
        //     $m->setTableName("transaksi_registry");
        //     $pReg = $m->lookUpAll();
        //     $pRegEntries = array();
        //     if (sizeof($pReg) > 0) {
        //         foreach ($pReg as $pParamReg) {
        //             $pRegEntries[$pParamReg['transaksi_id']] = blobdecode($pParamReg['values']);
        //             // arrPrint($regEntries);
        //
        //         }
        //     }
        //     // arrPrint($regEntries);
        //     $recaplist = array();
        //     $recaplistCust = array();
        //     $recaplistPrev = array();
        //     foreach ($pmp as $pmp_1) {
        //         $pSellID = $pListedCustomer[$pmp_1->id_master];
        //
        //         $valNet = isset($pRegEntries[$pmp_1->id]['nett1']) ? $pRegEntries[$pmp_1->id]['nett1'] : 0;
        //         // if(!isset($recaplist[$pmp_1->jenis][$trDtime_m])){
        //         //     $recaplist[$pmp_1->jenis][$trDtime_m] =0;
        //         //
        //         // }
        //         // if(!isset($recaplistCust[$pmp_1->jenis][$trDtime_m][$sellID])){
        //         //     $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] =0;
        //         // }
        //
        //         // if(!isset($names['customers_id'][$sellID])){
        //         //     $names['customers_id'][$sellID]=
        //         // }
        //         foreach ($selectedTrans as $jj => $jjLabel) {
        //             if (!isset($recaplistPrev[$pSellID][$jj])) {
        //                 $recaplistPrev[$pSellID][$jj] = 0;
        //             }
        //         }
        //         if (!isset($pRecaplistAll[$pSellID][$pmp_1->jenis])) {
        //             $pRecaplistAll[$pSellID][$pmp_1->jenis] = 0;
        //         }
        //         // $recaplist[$pmp_1->jenis][$trDtime_m] +=$valNet;
        //         // $recaplistCust[$trDtime_m][$pmp_1->jenis][$sellID] +=$valNet;
        //         $recaplistPrev[$pSellID][$pmp_1->jenis] += $valNet;
        //     }
        //     //endregion
        //     $prevOutsanding = array();
        //     foreach($recaplistPrev as $custID =>$custData){
        //         $val = $custData['582so'] - ($custData['582spd'] -$custData['982']-$custData['1982']);
        //         $prevOutsanding[$custID]['prev']=$val;
        //     }
        // }
        // matiHEre("lolos gak ada outstanding");

        // arrPrint($recaplistAll);
        // matiHere();

        // arrPrint($recaplistAll);

        // matiHere();
        //region current year
        // $tr->addFilter($selectedCabang);
        // $tr->setFilters(array());
        $this->db->where("transaksi.trash_4='0'");
        $this->db->where("transaksi.link_id='0'");
        $this->db->where("transaksi.jenis in ('582so','582spd','982','1982')");
        $this->db->where("year(transaksi.dtime)='$currYear'");
        // $this->db->limit(20);
//        $tmp = $tr->lookupMainTransaksi()->result();
        $tmp = $tr->lookupTransaksiDataRegistries()->result();
        showLast_query("lime");
        //endregion

        $tmpResult = array();
        $tmpResultSummarySeller = array();
        if(sizeof($tmp)>0){
            foreach($tmp as $ii => $tmpSpec){
//                arrPrintWebs($tmpSpec);
                $main = addPrefixKeyM_he_format(blobDecode($tmpSpec->main));
                $tahun_bulan = formatTanggal($tmpSpec->dtime, "Y-m");
                $id_his = blobDecode($tmpSpec->ids_his);
//                arrPrintWebs($id_his);
                $arrSellerTransaksi = array(
                    "m_sellerID" => $id_his[1]["olehID"],
                    "m_sellerName" => $id_his[1]["olehName"],
                );

                $tmpResult[$tmpSpec->id] = (array)$tmpSpec + $main + array("m_tahun_bulan" => $tahun_bulan) + $arrSellerTransaksi;

//                break;
            }

            foreach ($tmpResult as $trID => $trData){
//                $xxxx[sellerID][bulan/tahun][jenisTR] = nilainya
                $sellerID = $trData['m_sellerID'];
                $tahun_bulan = $trData['m_tahun_bulan'];
                $jenisTr = $trData['m_jenisTr'];
                $nett1 = $trData['m_nett1'];
                if(!isset($tmpResultSummarySeller[$sellerID][$tahun_bulan][$jenisTr])){
                    $tmpResultSummarySeller[$sellerID][$tahun_bulan][$jenisTr] = 0;
                }
                $tmpResultSummarySeller[$sellerID][$tahun_bulan][$jenisTr] += $nett1;

            }
        }
arrPrintPink($tmpResultSummarySeller);
mati_disini(__LINE__);


        $arrTrjenisPenjualan = array(
            "582so",
            "582spd"
        );
        $arrTrjenisLainnya = array(
            "982",
            "1982"
        );
        $regIDS = array();
        $masterID = array();
        $fnReg = array();
        foreach ($tmp as $tmp_0) {
            // arrPrint($tmp_0);
            // $d_indexingMain = $indexingMain = strlen($tmp_0->indexing_registry) > 10 ? blobDecode($tmp_0->indexing_registry)['main'] : array();
            // arrprint($indexingMain);
            $d_id = $tmp_0->id;
            $regIDS[] = $d_id;
            $masterID[] = $tmp_0->id_master;
            $d_idMaster = $tmp_0->id_master;
            $d_jenis = $tmp_0->jenis;


            //id_master, sudah daapt ID 582SPO
            if (in_array($d_jenis, $arrTrjenisPenjualan)) {
                $arrPenjualan_idMaster[$d_id] = $d_idMaster;
            }
            if (in_array($d_jenis, $arrTrjenisLainnya)) {
                // $arrPenjualan_lainya[$d_id] = $d_indexingMain;
                $arrPenjualan_lainya[$d_id] = $d_id;
            }

            $trIds_[$tmp_0->id] = 1;
            $trIdDts[$tmp_0->id]['dtime'] = $tmp_0->dtime;
            $trIdDts[$tmp_0->id]['olehID'] = $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['sellerID'] = isset($tmp_0->seller_id) ? $tmp_0->seller_id : $tmp_0->oleh_id;
            $trIdDts[$tmp_0->id]['cabangID'] = $tmp_0->cabang_id;
            $trIdDts[$tmp_0->id]['pihakID'] = ($tmp_0->suppliers_id < 1 ? $tmp_0->customers_id : $tmp_0->suppliers_id);

            if ($tmp_0->jenis == "582spd") {
                // $prevIDS[$tmp_0->id] = blobdecode($tmp_0->ids_his)['2']['trID'];
                $idPrev = blobdecode($tmp_0->ids_his)['2']['trID'];
                $prevIDS[] = $idPrev;
                $indListPrev[$idPrev][] = $tmp_0->id;
            }
            if ($tmp_0->jenis == "1982") {
                $fnReg[] = $tmp_0->id;

            }

            // arrPrint($indexingMain);
        }



        $regReferensiId = array();
        if (isset($arrPenjualan_lainya) && sizeof($arrPenjualan_lainya) > 0) {
            $regReferensi = $tr->lookupBaseDataRegistries($arrPenjualan_lainya)->result();
            //            showLast_query("biru");
            //            arrPrintWebs($regReferensi);
            foreach ($regReferensi as $item) {
                // $regId = $item->id; // id registry main
                $regTrId = $item->transaksi_id; // id transaksi 982 atau 1982

                $regValues = blobDecode($item->main);
                $regRefId = isset($regValues['referenceID']) ? $regValues['referenceID'] : $regValues['masterID'];
                //                arrPrintPink($regValues);
                //                $regReferensiId[$regId] = $regRefId;
                //                $regReferensiId[$regTrId] = $regRefId;// masih berisi 582spo dan 582spd

                if ($regValues['jenisTr'] == "982") {
                    $regReferensiId_SPD[$regTrId] = $regRefId;
                }
                else {
                    $regReferensiId[$regTrId] = $regRefId;
                }

            }
            if (sizeof($regReferensiId_SPD) > 0) {
                //                arrPrintWebs($regReferensiId_SPD);
                $tr->setFilters(array());
                $this->db->select(array("id",
                    "oleh_id",
                    "oleh_nama",
                    "id_master"
                ));
                $this->db->where_in('id', $regReferensiId_SPD);
                $spo_tmp = $tr->lookupAll()->result();
                foreach ($spo_tmp as $item) {
                    $result[$item->id] = $item->id_master;
                }
                foreach ($regReferensiId_SPD as $trID => $refID) {
                    //                    cekKuning("$trID => " . $result[$refID]);
                    $regReferensiId[$trID] = $result[$refID];
                }
            }

            //mati_disini();
        }
        $arrSpoId = $arrPenjualan_idMaster + $regReferensiId;

        //        cekLime(sizeof($arrSpoId));
        //        arrPrintWebs($arrSpoId);

        if (sizeof($arrPenjualan_idMaster) > 0) {
            $tr->setFilters(array());
            $this->db->select(array("id",
                "oleh_id",
                "oleh_nama"
            ));
            $this->db->where_in('id', $arrSpoId);
            $spo_0 = $tr->lookupAll()->result();
            //            showLast_query("merah");
            foreach ($spo_0 as $item) {
                $spo_id = $item->id;
                $arrSpo[$spo_id] = $item->oleh_id;
                $spo_oleh_namas[$item->oleh_id] = $item->oleh_nama;
            }
        }
        $listedSellerLabel = $spo_oleh_namas;
        foreach ($arrSpoId as $trId => $dt_spo_id) {
            $spo_olehId = $arrSpo[$dt_spo_id];
            $listedSeller[$trId] = $spo_olehId;
            // $listedSellerLabel[$spo_olehId] =
        }

        // arrPrintWebs($listRegMain);
        // // arrPrint($listedSeller);
        $listedCustomer = $listedSeller;
        $listedCustomerLabel = $listedSellerLabel;
        // arrPrint($listedCustomer);
        // matiHere(__LINE__);

        //region lihat registry main
        // $m->setFilters(array());
        // $m->setParam("id");
        // $m->setInParam($regIDS);
        // $m->setFields(array("transaksi_id", "param", "values"));
        // $m->setTableName("transaksi_registry");
        $reg = $tr->lookupBaseDataRegistries($regIDS)->result();
        // showLast_query("orange");
        // arrPrint($reg);
        // matiHere(__LINE__);
        $regEntries = array();
        // arrPrint($reg);
        if (sizeof($reg) > 0) {
            foreach ($reg as $paramReg) {
                $regEntries[$paramReg->transaksi_id] = blobdecode($paramReg->main);

            }
            $test = array();
            if (sizeof($fnReg) > 0) {
                foreach ($fnReg as $idsFn) {
                    // arrPrint($regEntries[$idsFn]);
                    $idx = isset($regEntries[$idsFn]['transaksiDatas']) ? $regEntries[$idsFn]['transaksiDatas'] : (isset($regEntries[$idsFn]['referenceID']) ? $regEntries[$idsFn]['referenceID'] : "");
                    $prevIDS[] = $idx;
                    $test[] = $idx;
                    // cekMerah($idx." ->".$regEntries[$idsFn]['pihakName']);
                    $indListPrev[$idx][] = $idsFn;
                    // $regEntries[$idx]['nett1']= $regEntries[$idsFn]['nett1'];
                    // cekHitam($regEntries[$idsFn]['transaksiDatas']);
                    // arrPrint($regEntries[$idx]);
                }
            }
        }
        // arrPrint($listedCustomer);
        //         arrPrintWebs($tmp);
        //matiHEre();
        foreach ($tmp as $row) {
            //            arrPrintWebs($row);
            $listedCustomeres[$row->seller_id] = $row->seller_nama;
            $sellID = $listedCustomer[$row->id];
            $trDtime = $trIdDts[$row->id]["dtime"];
            $trDtime_m = formatTanggal($trDtime, "Y-m");

            $valNet = isset($regEntries[$row->id]['nett1']) ? $regEntries[$row->id]['nett1'] : 0;

            //----------
            //            if($sellID == "65"){
            //                cekKuning("$trDtime_m => $valNet");
            //            }
            //----------
            if (!isset($recaplistAll_0[$sellID][$trDtime_m][$row->jenis])) {
                $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] = 0;
            }
            $recaplistAll_0[$sellID][$trDtime_m][$row->jenis] += $valNet;
        }
        // cekBiru($listedCustomeres);
        //        arrPrintWebs($recaplistAll_0['65']);
        //mati_disini();
        foreach ($recaplistAll_0 as $sb_id => $recaplistDates) {
            foreach ($recaplistDates as $sb_date => $recaplistJenis) {
                $so_nilai = isset($recaplistJenis['582so']) ? $recaplistJenis['582so'] : 0;
                $rt_nilai = isset($recaplistJenis['982']) ? $recaplistJenis['982'] : 0;
                $cl_nilai = isset($recaplistJenis['1982']) ? $recaplistJenis['1982'] : 0;
                $sonet_nilai = $so_nilai - $rt_nilai - $cl_nilai;

                $recaplistAll[$sb_id][$sb_date] = $recaplistJenis + array("5982" => $sonet_nilai);

                if (!isset($closePerId[$sb_id])) {
                    $closePerId[$sb_id] = 0;
                }
                $closePerId[$sb_id] += $cl_nilai;
            }
        }

        // arrPrint($recaplistAll_0);
        // arrPrint($recaplistAll);
        //         matiHere();
        //region outstanding per bulan
        // $netPending = array();
        // $summaryData = array();
        // foreach ($recaplistAll as $sID => $sidData) {
        //     foreach ($sidData as $time => $timeData) {
        //         $src = isset($timeData['582so']) ? $timeData['582so'] : 0;
        //         $srcF1 = isset($timeData['582spd']) ? $timeData['582spd'] : 0;
        //         $srcF2 = isset($timeData['982']) ? $timeData['982'] : 0;
        //         $srcF3 = isset($timeData['1982']) ? $timeData['1982'] : 0;
        //         $net = $src - ($srcF1 - $srcF2-$srcF3);
        //         $recaplistAll[$sID][$time]['pending'] = $net;
        //         // foreach ($selectedTrans as $jenis => $alias) {
        //         //     if (!isset($recaplistAll[$sID][$time][$jenis])) {
        //         //         $recaplistAll[$sID][$time][$jenis] = 0;
        //         //     }
        //         // }
        //     }
        //
        //     // arrPrint($sidData);
        // }
        // // arrPrint($recaplistAll);
        //endregion

        //netto
        /*
         * hanya manggil main transaksi joint registry untuk penampil master
         */

        //endregion

        // arrPrint($prevIDS);
        // matiHere();
        if (sizeof($prevIDS) > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("year(dtime)<='$prevYear'");
            // $this->db->where("year(dtime)='$currYear'");
            $prevData = $tr->lookupMainTransaksi()->result();
//            cekLime($this->db->last_query());
            $idsRelPrev = array();
            $cuID = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
            }

            // region woles
            /* ------------------------------------
           * mengambil data so
           * ------------------------------------*/
            $tr->setFilters(array());
            // $tr->addFilter("id in ('" . implode("','", $prevIDS) . "')");
            $tr->addFilter("jenis='582so'");
            $tr->addFilter("link_id='0'");
            $tr->addFilter("year(transaksi.dtime)<='$prevYear'");
            $tr->addFilter("valid_qty>'0'");
            // $tr->addFilter("((transaksi_data.valid_qty>'0') or (transaksi_data.cancel_qty>'0'))");
            // $tr->addFilter("transaksi_data.transaksi_id=transaksi.id");
            // $this->db->where("year(dtime)='$currYear'");
            // $this->db->limit(10);
            // $where_custom = "(valid_qty>'0' OR cancel_qty>'0')";
            // $this->db->where($where_custom);
            $prevData = $tr->lookupJoined_OLD()->result();
//            cekKuning($this->db->last_query());
//            cekHere(sizeof($prevData));
            // arrPrint($prevData);
            // // id_master
            // matiHere(__LINE__);

            $idsRelPrev = array();
            $cuID_x = array();
            $validQty = array();
            foreach ($prevData as $dtaTmpPRev) {
                $idsRelPrev[] = $dtaTmpPRev->id;
                $cuID_x[$dtaTmpPRev->customers_id][] = $dtaTmpPRev->id;
                // $cuID[$dtaTmpPRev->oleh_id][] = $dtaTmpPRev->id;
                $idTransaksiSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->transaksi_id;
                $idSellerSo[$dtaTmpPRev->transaksi_id] = $dtaTmpPRev->seller_id;
                $namaSellerSo[$dtaTmpPRev->id_master]['seller_nama'] = $dtaTmpPRev->seller_nama;

                $valid_qty = isset($dtaTmpPRev->valid_qty) ? $dtaTmpPRev->valid_qty : 0;
                // $cancel_qty = isset($dtaTmpPRev->cancel_qty) ? $dtaTmpPRev->cancel_qty : 0;
                $validQty[$dtaTmpPRev->transaksi_id][$dtaTmpPRev->produk_id] = ($valid_qty);
            }

            // $this->db->select("main");
            $tr->setJointSelectFields("transaksi_id,items");
            // $regPrevs = $tr->lookupBaseDataRegistries("16373")->result();
            $regPrevs = $tr->lookupBaseDataRegistries($idTransaksiSo)->result();
//            showLast_query("merah");
            // arrPrint($regPrevs);
            $arrPrev = array(
                77 => 0,
                718 => 0,
                664 => 0,
                719 => 0,
//                65 => 108215690,
                65 => 30237954,
                57 => 0,
                551 => 0,
                712 => 0,
//                73 => 2849999,
                73 => 31213718,
                274 => 0,
                182 => 0,
//                61 => 172796535,
                61 => 34766344,
                205 => 0,
//                576 => 56079964,
                576 => 449897307,
                567 => 0,
//                69 => 35999986,
                69 => 10126383744,
                663 => 0,
                664 => 1362000012,
                // 77	0
                // 718	0
                // 664	0
            );



            $items = array();
            foreach ($regPrevs as $regPrev) {
                $transaksi_id_2 = $regPrev->transaksi_id;
                $items = blobDecode($regPrev->items);
                // cekBiru($transaksi_id_2);
                // arrPrintWebs($items);
                $sellerId_nya = $idSellerSo[$transaksi_id_2];

                // if(isset($validQty[$transaksi_id_2])){
                if (sizeof($items) > 0) {
                    $valunya = 0;
                    foreach ($items as $produk_id_nya => $qty_datas) {
                        if (isset($validQty[$transaksi_id_2][$produk_id_nya])) {

                            $valunya += $qty_datas['nett1'] * $validQty[$transaksi_id_2][$produk_id_nya];
                        }
                        else {
                            $arrayIds[$transaksi_id_2] = $transaksi_id_2;
                            // matiHere($transaksi_id_2 . " " . __LINE__);
                        }
                    }
                }

                // }

                // arrPrint($items);
                // arrPrint($items);

                $closeForPrev = isset($closePerId[$sellerId_nya]) ? $closePerId[$sellerId_nya] : 0;
                // $closeForPrev = 0;

                // $prevOutsanding_x[$sellerId_nya] = ($valunya);
                // $prevOutsanding[$sellerId_nya]['prev'] = ($valunya + $closeForPrev);
                $valunya = isset($arrPrev[$sellerId_nya]) ? $arrPrev[$sellerId_nya] : $sellerId_nya;
                $prevOutsanding[$sellerId_nya]['prev'] = ($valunya);
                // $prevOutsanding[$sellerId_nya]['prev'] = 0;
            }
            // endregion

            // arrPrint($prevOutsanding_x);

            // arrPrint($cuID);
            // matiHere();
            // foreach($idsRelPrev as $pID){
            foreach ($cuID as $customers_id => $dtaID) {
                $val = 0;
                foreach ($dtaID as $PID) {
                    if (isset($indListPrev[$PID])) {
                        foreach ($indListPrev[$PID] as $iuid) {
                            // cekHitam($iuid);
                            $val2 = isset($prevOutsanding_x[$iuid]) ? $prevOutsanding_x[$iuid] : 0;

                            $val += ($regEntries[$iuid]['nett1'] + $val2);
                        }
                        // arrPrint($regEntries);
                    }
                }


                // $prevOutsanding[$customers_id]['prev'] = $val;
            }

            // }
            //         arrPrint($idsRelPrev);
            // cekLime($this->db->last_query());
            // arrPrint($prevData);
        }
        // arrPrint($prevOutsanding);
        $months = array();
        for ($i = 1; $i <= $currMonth; $i++) {
            if (strlen($i) < 2) {
                $i = "0" . $i;
            }
            $key = $currYear . "-" . $i;
            //            echo $i."<br>";
            //            $months[$i]=date("F", strtotime("Y-".$i."-d"));
            $months[$key] = $i;

        }
        $finalMonths = $months;
        $prevMonths = array("prev" => "prev");
        $sumTimes = array("prev" => "prev") + $months + array("pending" => "outstanding");
        //        arrprint($months);


        // $availFilters = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['availFilters'] : array();
        // $defaultFilter = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultFilter'] : "oleh_id";
        // $defaultStep = isset($this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep']) ? $this->config->item("heTransaksi_report")[$this->jenisTr]['defaultStep'] : $this->jenisTr;
        $selectedStep = isset($_GET['stID']) ? $_GET['stID'] : $defaultStep;
        //region link to add new transaction
        // if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
        //     //        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
        //     $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
        //     if (array_key_exists($this->jenisTr, $createIndexes)) {
        //         $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
        //     }
        //     else {
        //         $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
        //     }
        //     $addLink = array(
        //         "link"  => $targetUrl,
        //         "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$this->jenisTr]["steps"][1]['label'],
        //     );
        // }
        // else {
        //     $addLink = null;
        // }
        $addLink = null;
        //endregion

        // arrPrint($listedCustomerLabel);
        asort($listedCustomerLabel);
        // arrPrintWebs($listedCustomerLabel);
        // arrPrint($prevOutsanding);

//arrPrintWebs($arrPrev);
        foreach($arrPrev as $sellerID => $xxxxxxxx){
            if(!array_key_exists($sellerID, $listedCustomerLabel)){
                $listedCustomerLabel[$sellerID] = isset($arrSellers[$sellerID]) ? $arrSellers[$sellerID] : "-";
            }
        }
//        arrPrint($listedCustomerLabel);


        $data = array(
            //            "mode" => "recap_ext1",
            "mode" => "recap_ext1_new",
            // "title"            => $this->config->item("heTransaksi_ui")[$this->jenisTr]['label'] . " report",
            "title" => "Laporan Sales Order",
            "subTitle" => "<small>diakui outstanding sebelum packing list</small>",
            // "times"            => $months,
            "prevTimes" => $prevMonths,
            "times" => $finalMonths,
            "sumTimes" => $sumTimes,
            "timeLabel" => "months",
            "names" => isset($listedCustomerLabel) ? $listedCustomerLabel : array(),
            "prevRecaps" => $prevOutsanding,
            "recaps" => $recaplistAll,
            "jenisTr" => "",
            "trName" => "",
            "availFilters" => $availFilters,
            "defaultFilter" => $defaultFilter,
            "selectedFilter" => isset($_GET['sID']) ? $_GET['sID'] : $defaultFilter,
            "identifierLabels" => $this->config->item("heTransaksi_report_identifiers"),
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/",
            "subPage" => base_url() . get_class($this) . "/viewDaily",
            "historyPage" => base_url() . get_class($this) . "/viewDetail",
            // "historyPage"      => base_url() . "Transaksi/viewHistory/" . $this->jenisTr . "/$stID" . "?stID=" . $stID,
            "stepNames" => "",
            "defaultStep" => $defaultStep,
            "selectedStep" => $selectedStep,
            "addLink" => $addLink,
            "recapList" => array(),
            "recapName" => array(),
            "recapNameLabel" => array(),
            "recapChild" => array(),
            "headerList" => $selectedTrans,
            "currYear" => $currYear,
            "headerListSum" => array("prev" => "prev") + $selectedTrans + array("outstanding" => "outstanding"),
        );
        $this->load->view("activityReports", $data);


    }
}
