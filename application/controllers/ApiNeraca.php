<?php

defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
class ApiNeraca extends REST_Controller
{
    function __construct($config = 'rest'){
        parent::__construct($config);
        $this->load->database();
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlEmployee");
    }
    function index(){
        return "inddexxx";
    }
    //region bulanan
    public function apiBalanceSheetMonthly_post()
    {
        //auth
        $token = $_POST['token'];
        $emp = new MdlEmployee();
        $emp->addFilter("token='$token'");
        $this->db->limit(1);
        $api_login = $emp->lookupAll()->result();
        if(empty($api_login)){
            $result = array(
                "status" => 0,
                "token" => $token,
                "reason" => "token tidak ditemukan",
            );
            $this->response($result, 200);
        }
        else{

            //data auth login
            $acc_id             = $api_login[0]->id;
            $acc_cabang_id      = $api_login[0]->cabang_id;
            $acc_gudang_id      = $api_login[0]->gudang_id;
            $acc_nama           = $api_login[0]->nama;
            $acc_nama_login     = $api_login[0]->nama_login;
            $acc_status         = $api_login[0]->status;
            $acc_trash          = $api_login[0]->trash;
            $acc_employee_type  = $api_login[0]->employee_type;
            $acc_membership     = $api_login[0]->membership;

            $cabangs = array();
            $this->load->model("Mdls/MdlCabang");
            $cab = new MdlCabang();
            $tmpc = $cab->lookupAll()->result();
            if (sizeof($tmpc) > 0) {
                foreach ($tmpc as $row) {
                    $_id = $row->id;
                    $cabangs[$_id] = $row->nama;
                }
            }

            $acc_cabang_nama = $cabangs[$acc_cabang_id];


            //=================================
            //=========data auth login=========

            $previousMonth = date("Y-m", strtotime("-1 month"));
            $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;

            $awalBulanPilih = "01 " . date("F Y", strtotime($defaultDate));
            $toDate = date("t F Y", strtotime($awalBulanPilih));
            $txtPeriode = "$awalBulanPilih - $toDate";
            $oldDate = "2019-09";


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

            $r->addFilter("cabang_id='$acc_cabang_id'");

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
//                        if (isset($accountChilds[$row['rekening']])) {
//                            $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];
//                            $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
//                        }
//                        else {
                            $rekening_name_l = $rekening_name;
//                        }
//                        $rekening_name_l .= $linkH;
                        $tmpCol = array(
//                            "rekening_orig" => $row['rekening'], //jika link aktif, aktifkan ini untuk mendapatkan nama asli dari rekening
                            "rekening" => $rekening_name_l,
                            "debet" => $row['debet'] * 1,
                            "kredit" => $row['kredit'] * 1,
//                            "link" => "",
                        );
                        $rekenings[] = $tmpCol;
                    }
                    else {
                        if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                            $akumulasiPenyusutan['aktiva tetap'] = array(
                                "debet" => 0,
                                "kredit" => 0,
                            );
                        }
                        $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                        $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
                    }
                }
            }

            $rekenings_sort = array();
            $no = -1;
            foreach ($struktureRekening as $rek) {
                foreach ($rekenings as $spec) {
                    if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening'], $accountAkumulasiPenyusutan)) {
                        if ($rek == $spec['rekening']) {
                            $no++;
                            // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                            if (isset($akumulasiPenyusutan[$spec['rekening']]) && sizeof($akumulasiPenyusutan[$spec['rekening']]) > 0) {
                                $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening']]['debet'];
                                $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening']]['kredit'];
                            }
                            $totals['debet'] += $spec['debet'];
                            $totals['kredit'] += $spec['kredit'];
                            $rekenings_sort[$rek] = $spec;
                        }
                    }
                }
            }


            //region writelog
            $this->load->model("Mdls/" . "MdlApiLog");
            $hTmp = new MdlApiLog();
            $hTmp->setFilters(array());
            $tmpHData = array(
                "title"         => "API Balance",
                "sub_title"     => "PERIODE MONTHLY ($txtPeriode) - ".$acc_cabang_nama."",
                "uid"           => $acc_id,
                "uname"         => $acc_nama,
                "dtime"         => date("Y-m-d H:i:s"),
                "transaksi_id"  => 0,
                "deskripsi_old" => base64_encode(serialize($rekenings_sort)),
                "deskripsi_new" => base64_encode(serialize($api_login[0])),
                "jenis"         => "",
                "ipadd"         => $_SERVER['REMOTE_ADDR'],
                "devices"       => $_SERVER['HTTP_USER_AGENT'],
                "category"      => "api_log",
                "controller"    => $this->uri->segment(1),
                "method"        => $this->uri->segment(2),
                "url"           => current_url(),
            );

            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

            $data = array(
                "mode" => $this->uri->segment(2),
                "items" => $rekenings_sort,
                "totals" => $totals,
                "cabang_nama" => $acc_cabang_nama,
                "array_header" => array(
                    "rekening" => "rekening",
                    "debet" => "debet",
                    "kredit" => "kredit",
                ),
                "title" => "TRIAL BALANCE",
                "dtime"         => date("Y-m-d H:i:s"),
//                "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE MONTHLY</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",
                "subTitle" => "PERIODE MONTHLY ($txtPeriode)",
//                "accountChilds" => $accountChilds,
//                "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
//                "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
//                "rekening_name" => $struktureRekening,
//                "rekening_alias" => $accountAlias,
//                "oldDate" => $oldDate,
//                "defaultDate" => $defaultDate,
            );

            $result = array(
                "status" => 1,
                "data" => $data,
//                "api_login" => $api_login,
            );
            $this->response($result, 200);
        }
    }
    //endregion bulanan
    //region tahunan
    public function apiBalanceSheetYearly_post()
    {
        //auth
        $token = $_POST['token'];
        $emp = new MdlEmployee();
        $emp->addFilter("token='$token'");
        $this->db->limit(1);
        $api_login = $emp->lookupAll()->result();
        if(empty($api_login)){
            $result = array(
                "status" => 0,
                "reason" => "token tidak ditemukan",
            );
            $this->response($result, 200);
        }
        else{

            //data auth login
            $acc_id             = $api_login[0]->id;
            $acc_cabang_id      = $api_login[0]->cabang_id;
            $acc_gudang_id      = $api_login[0]->gudang_id;
            $acc_nama           = $api_login[0]->nama;
            $acc_nama_login     = $api_login[0]->nama_login;
            $acc_status         = $api_login[0]->status;
            $acc_trash          = $api_login[0]->trash;
            $acc_employee_type  = $api_login[0]->employee_type;
            $acc_membership     = $api_login[0]->membership;

            $cabangs = array();
            $this->load->model("Mdls/MdlCabang");
            $cab = new MdlCabang();
            $tmpc = $cab->lookupAll()->result();
            if (sizeof($tmpc) > 0) {
                foreach ($tmpc as $row) {
                    $_id = $row->id;
                    $cabangs[$_id] = $row->nama;
                }
            }

            $acc_cabang_nama = $cabangs[$acc_cabang_id];


            //=================================
            //=========data auth login=========

            $previousMonth = date("Y", strtotime('-1 year'));
            $defaultDate = isset($_GET['date']) ? $_GET['date'] : $previousMonth;
            $awalTahun = "01 January " . $defaultDate;
            $toDate = "31 December " . $defaultDate;
            $txtPeriode = "$awalTahun - $toDate";
            $oldDate = "2019";

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

            $r->addFilter("cabang_id='$acc_cabang_id'");

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
//                        $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
//                        if (isset($accountChilds[$row['rekening']])) {
//                            $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];
//                            $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
//                        }
//                        else {
                            $rekening_name_l = $rekening_name;
//                        }
//                        $rekening_name_l .= $linkH;
                        $tmpCol = array(
//                            "rekening_orig" => $row['rekening'],
                            "rekening" => $rekening_name_l,
                            "debet" => $row['debet'] * 1,
                            "kredit" => $row['kredit'] * 1,
//                            "link" => "",
                        );
                        $rekenings[] = $tmpCol;
                    }
                    else {
                        if (!isset($akumulasiPenyusutan['aktiva tetap'])) {
                            $akumulasiPenyusutan['aktiva tetap'] = array(
                                "debet" => 0,
                                "kredit" => 0,
                            );
                        }
                        $akumulasiPenyusutan['aktiva tetap']['debet'] += ($row['kredit'] * -1);
                        $akumulasiPenyusutan['aktiva tetap']['kredit'] += ($row['debet'] * -1);
                    }
                }
            }

            $rekenings_sort = array();
            $no = -1;
            foreach ($struktureRekening as $rek) {
                foreach ($rekenings as $spec) {
                    if (sizeof($accountAkumulasiPenyusutan) > 0 && !in_array($spec['rekening'], $accountAkumulasiPenyusutan)) {
                        if ($rek == $spec['rekening']) {
                            $no++;
                            // menge NETTO kan rekening... contoh aktiva tetap - akumulasi penyusutan aktiva tetap
                            if (isset($akumulasiPenyusutan[$spec['rekening']]) && sizeof($akumulasiPenyusutan[$spec['rekening']]) > 0) {
                                $spec['debet'] = $spec['debet'] + $akumulasiPenyusutan[$spec['rekening']]['debet'];
                                $spec['kredit'] = $spec['kredit'] + $akumulasiPenyusutan[$spec['rekening']]['kredit'];
                            }
                            $totals['debet'] += $spec['debet'];
                            $totals['kredit'] += $spec['kredit'];
                            $rekenings_sort[$rek] = $spec;
                        }
                    }
                }
            }

            //region writelog
            $this->load->model("Mdls/" . "MdlApiLog");
            $hTmp = new MdlApiLog();
            $hTmp->setFilters(array());
            $tmpHData = array(
                "title"         => "API Balance",
                "sub_title"     => "PERIODE YEARLY ($txtPeriode) - " . $acc_cabang_nama . "",
                "uid"           => $acc_id,
                "uname"         => $acc_nama,
                "dtime"         => date("Y-m-d H:i:s"),
                "transaksi_id"  => 0,
                "deskripsi_old" => base64_encode(serialize($rekenings_sort)),
                "deskripsi_new" => base64_encode(serialize($api_login[0])),
                "jenis"         => "",
                "ipadd"         => $_SERVER['REMOTE_ADDR'],
                "devices"       => $_SERVER['HTTP_USER_AGENT'],
                "category"      => "api_log",
                "controller"    => $this->uri->segment(1),
                "method"        => $this->uri->segment(2),
                "url"           => current_url(),
            );

            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

            $data = array(
                "mode" => $this->uri->segment(2),
                "items" => $rekenings_sort,
                "totals" => $totals,
                "cabang_nama" => $acc_cabang_nama,
                "array_header" => array(
                    "rekening" => "rekening",
                    "debet" => "debet",
                    "kredit" => "kredit",
                ),
                "title" => "TRIAL BALANCE",
                "dtime"         => date("Y-m-d H:i:s"),
//                "subTitle" => "<div class='text-center'>trial balance</div><div class='text-center'>PERIODE YEARLY</div><div class='text-center text-bold'>($txtPeriode)</div> <div class='text-center'>" . $this->session->login['cabang_nama'] . "</div>",
                "subTitle" => "PERIODE YEARLY ($txtPeriode)",
    //            "accountChilds" => $accountChilds,
    //            "inspectTarget_rincian" => base_url() . "Ledger/viewBalances_l1/",
    //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l1/",
    //            "rekening_name" => $struktureRekening,
    //            "rekening_alias" => $accountAlias,
//                "oldDate" => $oldDate,
//                "defaultDate" => $defaultDate,
            );

            $result = array(
                "status" => 1,
                "data" => $data,
//                "api_login" => $api_login,
            );
            $this->response($result, 200);
        }
    }
    //endregion tahunan
    //mekanisme pembuatan token
    function tokenGenerate_get(){
        $token = sha1(mt_rand(1, 90000) . 'SALT');
        echo $token;
    }
}

