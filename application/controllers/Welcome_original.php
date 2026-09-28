<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("MobileDetect");
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");
        $this->accessList = alowedAccess($this->session->login['id']);
        if (!isset($this->session->login['id'])) {
            // redirect(base_url() . "Login");
            gotoLogin();
        }
        $this->configUiModul = loadConfigUiModul_he_misc();
        // matiHere(__FILE__);
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");

    }

    public function index()
    {
        // arrprint($this->configUiModul);
        // matiHEre();
        if (!isset($this->session->login['id'])) {
            // redirect(base_url() . "Login");
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);//
        //        arrPrint($this->accessList);
        $replaceSession = replaceSession();

        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        //        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id" => $row->_id,
                        "label" => $row->mdl_label,
                        "origID" => $row->orig_id,
                        "proposer" => $row->proposed_by_name,
                        "date" => $row->proposed_date,
                        "content" => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>


        //<editor-fold desc="tampilan approval data">


        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {
            foreach ($dataProposals as $mdlName => $pSpec) {

                //region relation translator
                $className = $mdlName;
                $this->relations = array();
                $this->relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$className.php")) {
                    $this->load->model("Mdls/" . $className);
                    $o = new $className();
                    $fields = $o->getFields();
                    foreach ($fields as $fName => $f2Spec) {
                        //                echo $f2Spec["label"]."-".$f2Spec["reference"]."<br>";
                        if (isset($f2Spec['reference'])) {
                            //                    cekbiru("mendeteksi relasi milik $fName");
                            //if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                            $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                            $this->load->model("Mdls/" . $f2Spec['reference']);
                            $o3 = new $f2Spec['reference']();
                            $tmp3 = $o3->lookupAll()->result();
                            //                    cekkuning($this->db->last_query());

                            if (sizeof($tmp3) > 0) {
                                //                        cekbiru("$fName ketemu data relasinya");
                                $mdlName2 = $f2Spec['kolom'];
                                $this->relationPairs[$mdlName2] = array();
                                foreach ($tmp3 as $row3) {
                                    $idxField = (null != $o3->getIndexFields()) ? $o3->getIndexFields() : "id";
                                    $id = isset($row3->$idxField) ? $row3->$idxField : 0;
                                    $name = isset($row3->nama) ? $row3->nama : "unknown";
                                    if (isset($row3->name)) {
                                        $name = $row3->name;
                                    }
                                    $this->relationPairs[$mdlName2][$id] = $name;
                                }
                            }
                            else {
                                //                        cekmerah("$fName TIDAK ketemu data relasinya");
                            }
                            //}

                        }
                    }
                }

                //endregion


                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $listedFields = $o2->getListedFields();
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";

                    //                    $tmpItemTmp["date"] = $dSpec['date'];
                    //                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    //                    $tmpItemTmp["proposed_by"] = $dSpec['proposer'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    //                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }
        //</editor-fold>


        //region allowedGroup
        $allowedGroup = array();
        $trans = $this->configUiModul;
        foreach ($trans as $jenisTr => $cSpec) {
            // arrPrint($cSpec['steps']);
            $tmp = array();
            if (is_array($cSpec['steps'])) {
                foreach ($cSpec['steps'] as $step => $sSpec) {
                    $group = $sSpec['userGroup'];
                    $tmp[$group] = $group;
                }
            }
            $allowedGroup[$jenisTr] = $tmp;

        }
        //        arrPrint($allowedGroup);
        //endregion


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        //region firstSteps untuk common histories
        //===ngumpulin daftar kode step pertama, untuk keperluan ngindeks history
        $firstSteps = array();
        if (null != $this->configUiModul && sizeof($this->configUiModul) > 0) {
            foreach ($this->configUiModul as $jenis => $jSpec) {
                if (isset($jSpec['steps'][1])) {
                    $firstSteps[] = "'" . $jSpec['steps'][1]['target'] . "'";
                }
            }
        }
        //endregion

        $arrFilters = array();
        if (sizeof($this->accessList) > 0) {
            //            $arrFilters = array();
            $indsteps = "(";
            foreach ($this->accessList as $jenisMaster => $masterData) {
                foreach ($masterData as $stepNumber => $stepSpec) {
                    foreach ($stepSpec as $targetCode => $filters) {
                        $indsteps .= "'$targetCode',";
                        $stepCodes[] = $targetCode;
                        if ($filters['allowFollowUp'] == "true") {
                            $arrFilters["allowFollowUp"][] = $targetCode;
                        }
                    }
                }
            }
            $indsteps = rtrim($indsteps, ",");
            $indsteps .= ")";

        }
        else {

        }
        //dimatiakn dulu karena berat saat banyak data
        //$tmpHist = $tr->lookupUndoneEntries_joined($replaceSession)->result();
        $tmpHist = array();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();

        $progressFields = array();
        $arrayOnprogress = array();
        //endregion


        //region  lookup histories

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        if (sizeof($replaceSession) > 0) {
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        }

// arrPrint($indsteps);
        if (sizeof($firstSteps) > 0) {
            $tr->addFilter("jenis in (" . implode(",", $firstSteps) . ")");
        }
        if (sizeof($arrFilters) > 0) {
            $tr->addFilter("next_step_code in $indsteps");//dimatiin dulu sementara karena coTransakiUi belum siap
            // $tr->addFilter("next_step_code in ('466','466r','467','111')");
        }
        else {
            $tr->addFilter("transaksi.oleh_id='" . $this->session->login['id'] . "'");
        }
        $tr->addFilter("link_id='0'");

        $tmpHist = $tr->lookupRecentHistories(10)->result();
        // arrPrint($tmpHist);
//                cekMerah($this->db->last_query());
// matiHEre();
        //arrPrint($tmpHist);
        $arrayHistory = array();
        $unionHistoryFields = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                //                arrPrint($row);
                $tmp["jenis_label"] = $row->jenis_label;
                $jenisTr = $row->jenis_master;
                $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
                $modul_path = base_url() . $modul . "/";
                $historyFields = isset($this->configUiModul[$jenisTr]['shortHistoryFields']) ? $this->configUiModul[$jenisTr]['shortHistoryFields'] : array();
                $pairRegistries = isset($this->configUiModul[$jenisTr]['pairRegistries']) ? $this->configUiModul[$jenisTr]['pairRegistries'] : array("main", "items");
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
// arrPrint($pairRegistries);
//                 matiHEre($jenisTr);
                $mb = New MobileDetect();
                $isMob = $mb->isMobile();
                if ($isMob) {
                    $historyFields = isset($this->configUiModul[$jenisTr]['compactHistoryFields']) ? $this->configUiModul[$jenisTr]['compactHistoryFields'] : array();
                }
                $aallowedView = isset($allowedGroup[$jenisTr]) ? $allowedGroup[$jenisTr] : array();
                $tmp = array();

                if (sizeof($this->accessList) > 0) {
                    if (sizeof($pairRegistries) > 0) {
                        $trReg = new MdlTransaksi();
                        $trReg->setFilters(array());
                        $trReg->setJointSelectFields($selectKolom);
                        $trReg->addFilter("transaksi_id='" . $row->id . "'");
                        $tmpReg = $trReg->lookupDataRegistries()->result();
                        // cekHitam($this->db->last_query());
                        if (sizeof($tmpReg) > 0) {
                            foreach ($tmpReg as $regRow) {
                                foreach ($pairRegistries as $param) {
                                    $$param = blobDecode($regRow->$param);

                                }
                                // $param = $regRow->param;

                            }
                            foreach ($pairRegistries as $eReg) {
                                foreach ($$eReg as $k => $v) {
                                    if (($k != NULL) && !isset($row->$k)) {

                                        $row->$k = $v;
                                    }
                                }
                            }
                        }
                    }
                    foreach ($historyFields as $fName => $fLabel) {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName, $jenisTr, $modul_path) : "";
                    }
                    $tmp['list'] = "<a class='btn btn-block btn-default' href='" . $modul_path . "History/viewHistory/$jenisTr'><span class='glyphicon glyphicon-th-list'></span></a>";
                    $unionHistoryFields = array_merge($unionHistoryFields, $historyFields);
                    $arrayHistory[] = $tmp;
                }
                else {
                    if (array_intersect($aallowedView, $mems)) {
                        if (sizeof($pairRegistries) > 0) {
                            $trReg = new MdlTransaksi();
                            $trReg->setFilters(array());
                            $trReg->setJointSelectFields($selectKolom);
                            $trReg->addFilter("transaksi_id='" . $row->id . "'");
                            $tmpReg = $trReg->lookupDataRegistries()->result();
                            if (sizeof($tmpReg) > 0) {
                                foreach ($tmpReg as $regRow) {
                                    foreach ($pairRegistries as $param) {
                                        $$param = blobDecode($regRow->$param);

                                    }
                                    // $param = $regRow->param;

                                }
                                foreach ($pairRegistries as $eReg) {
                                    foreach ($$eReg as $k => $v) {
                                        if (!isset($row->$k)) {

                                            $row->$k = $v;
                                        }
                                    }
                                }
                            }
                        }
                        foreach ($historyFields as $fName => $fLabel) {
                            $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName, $jenisTr, $modul_path) : "";
                        }
                        $tmp['list'] = "<a class='btn btn-block btn-default' href='" . $modul_path . "History/viewHistory/$jenisTr'><span class='glyphicon glyphicon-th-list'></span></a>";
                        $unionHistoryFields = array_merge($unionHistoryFields, $historyFields);
                        $arrayHistory[] = $tmp;
                    }
                }
            }
        }

        //endregion


        $videos = $this->config->item('videos') != null ? $this->config->item('videos') : array();

        /* --------------------------------------------------------------------------
         * BEFORE OPNAME
         * --------------------------------------------------------------------------*/
        $tgl_now = dtimeNow("Y-m-d");
        $tgl_min = "2022-12-18";
//        $tgl_max = "2022-12-30";
        $tgl_max = "2022-12-31";
        $notif_opname = "";
//        cekHere("$tgl_now >= $tgl_min && $tgl_now <= $tgl_max");
        /* -----------------------------------------------------------------------
         * transaksi gantung
         * -----------------------------------------------------------------------*/
        $this->load->library("Transaksional");
        $ts = new Transaksional();
//        if($tgl_now >= $tgl_min && $tgl_now <= $tgl_max){
//            $this->load->library("Transaksional");
//            $ts = new Transaksional();
        $src_ts = $ts->callTransaksiBeforeOpname();

        $jml_gantungan = $src_ts['jml'];
        $jml_gantungan = 0;
        $jenis_gantung = $src_ts['datas'];
        $link_gantungan = $src_ts['link'];

        if ($jml_gantungan > 0) {
            $link_gantung = base_url() . $link_gantungan;
            $notif_opname = modalDialogBtn("Transaksi yang harus diselesaikan sebelum stok opname", "$link_gantung");
        }
        //cekHere("masuk disini");
//        }

        $view_opname = false;
        // $ts->setCabangId(false);
        $src_opname = $ts->cekOpnameAktive(false);
        if ($src_opname["jml"] > 0) {
            $view_opname = base_url() . "opname/Opname/viewOpnameAktive";
        }
        /* --------------------------------------------------------------------------*/

        //        arrprint($arrayOnprogress);

        //        print_r($unionHistoryFields);die();
        $unionHistoryFields['list'] = "more..";
        $data = array(
            "mode" => "welcome",
            "isMobile" => $isMob,

            "title" => "Welcome",
            "subTitle" => "Welcome, " . $this->session->login['nama'],
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> action needed #welcome",
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "dataProposals" => $arrItemTmp,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> recent activities",
            //            "arrayHistoryLabels" => $historyFields,
            "arrayHistoryLabels" => $unionHistoryFields,
            "arrayHistory" => $arrayHistory,
            "videos" => $videos,
            "notif_opname" => $notif_opname,
            "view_opname" => $view_opname,
        );


        $this->load->view("welcome", $data);


        //endregion
    }

    /*--pindah ke modul dashboard---*/
    // public function summary()
    // {
    //     $cabang_id = my_cabang_id();
    //     switch ($cabang_id) {
    //         case "-1":
    //             $boards = array(
    //                 "1" => array(
    //                     "rekening" => "kas",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $kas,
    //                     "label" => "saldo kas",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-green",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "5" => array(
    //                     "rekening"        => "penjualan",
    //                     "rekening_posisi" => "kredit",
    //                     // "nilai"      => $hutangKepusat,
    //                     "label"           => "penjualan",
    //                     "icon"            => "fa-money",
    //                     "bg"              => "bg-teal",
    //                     // "link"            => "Neraca/viewBalanceSheet",
    //                     // "link_label"      => "Balance Sheet",
    //                 ),
    //                 "6" => array(
    //                     "rekening"        => "piutang dagang",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $hutangKepusat,
    //                     "label"           => "piutang dagang",
    //                     "icon"            => "fa-money",
    //                     "bg"              => "bg-purple",
    //                     // "link"            => "Neraca/viewBalanceSheet",
    //                     // "link_label"      => "Balance Sheet",
    //                 ),
    //                 "2" => array(
    //                     "rekening" => "piutang cabang",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $kas,
    //                     "label" => "piutang cabang",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-red",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "3" => array(
    //                     "rekening" => "hutang dagang",
    //                     "rekening_posisi" => "kredit",
    //                     // "nilai"      => $piutang,
    //                     "label" => "hutang dagang",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-aqua",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "4" => array(
    //                     "rekening" => "persediaan produk",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $hutangKepusat,
    //                     "label" => "persediaan produk",
    //                     "icon" => "fa-money",
    //                     "bg"              => "bg-olive",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //             );
    //             break;
    //         default:
    //             $boards = array(
    //                 "1" => array(
    //                     "rekening" => "kas",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $kas,
    //                     "label" => "Saldo Kas (IDR)",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-green",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "2" => array(
    //                     "rekening" => "piutang dagang",
    //                     "rekening_posisi" => "debet",
    //                     // "nilai"      => $piutang,
    //                     "label" => "piutang konsumen",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-red",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "3" => array(
    //                     "rekening" => "hutang ke pusat",
    //                     "rekening_posisi" => "kredit",
    //                     // "nilai"      => $hutangKepusat,
    //                     "label" => "hutang ke pusat",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-aqua",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //                 "4" => array(
    //                     "rekening" => "hutang ke konsumen",
    //                     "rekening_posisi" => "kredit",
    //                     // "nilai"      => $hutangKepusat,
    //                     "label" => "hutang ke konsumen",
    //                     "icon" => "fa-money",
    //                     "bg" => "bg-yellow",
    //                     "link" => "Neraca/viewBalanceSheet",
    //                     "link_label" => "Balance Sheet",
    //                 ),
    //             );
    //             break;
    //     }
    //
    //     /*ngambil data neraca lajur pusat*/
    //     $this->load->model("Coms/ComRekening");
    //     $r = new ComRekening();
    //
    //     $r->addFilter("cabang_id='" . $cabang_id . "'");
    //
    //     $tmp = $r->fetchAllBalances();
    //     // showLast_query("merah");
    //     // arrPrint($tmp);
    //     //         matiHere(__LINE__);
    //
    //     /*PENJUALAN*/
    //     //<editor-fold desc="hanya mengambil total penjualan dari seluruh cabang">
    //     $penjualan = array();
    //     $koloms = array(
    //         "rekening",
    //         "rek_id",
    //         "debet",
    //         "sum(kredit) as kredit"
    //     );
    //     $this->db->select($koloms);
    //     $condites = array(
    //         "rekening" => "penjualan",
    //         "periode"  => "forever"
    //     );
    //     $this->db->where($condites);
    //     $tableName = "_rek_master_cache";
    //     $src_pls = $this->db->get($tableName)->result();
    //
    //     foreach ($src_pls as $items) {
    //         $rek_id = $items->rek_id;
    //         $rekening = $items->rekening;
    //         $debet = $items->debet;
    //         $kredit = $items->kredit;
    //
    //         $penjualan[$rekening]["debet"] = $debet;
    //         $penjualan[$rekening]["kredit"] = $kredit;
    //         $penjualan[$rekening]["rek_id"] = $rek_id;
    //     }
    //     //</editor-fold>
    //
    //     /*PIUTANG DAGANG*/
    //     $piutang_dagang = array();
    //     $koloms = array(
    //         "rekening",
    //         "rek_id",
    //         "kredit",
    //         "sum(debet) as debet"
    //     );
    //     $this->db->select($koloms);
    //     $condites = array(
    //         "rekening" => "piutang dagang",
    //         "periode"  => "forever"
    //     );
    //     $this->db->where($condites);
    //     $tableName = "_rek_master_cache";
    //     $src_pls = $this->db->get($tableName)->result();
    //
    //     foreach ($src_pls as $items) {
    //         $rek_id = $items->rek_id;
    //         $rekening = $items->rekening;
    //         $debet = $items->debet;
    //         $kredit = $items->kredit;
    //
    //         $penjualan[$rekening]["debet"] = $debet;
    //         $penjualan[$rekening]["kredit"] = $kredit;
    //         $penjualan[$rekening]["rek_id"] = $rek_id;
    //     }
    //
    //     $tmps = $tmp + $penjualan;
    //     // arrPrint($tmps);
    //     // $kas = $tmp['kas']['debet'];
    //     // $piutang = $tmp['piutang dagang']['debet'];
    //     // $hutangKepusat = $tmp['hutang ke pusat']['kredit'];
    //     $jml = sizeof($boards);
    //     $var = "";
    //     $var .= "<style type='text/css'>
    //                 .small-box .icon {
    //                     top: -25px;
    //                 }
    //             </style>";
    //     foreach ($boards as $board) {
    //
    //         $nilai = $tmps[$board["rekening"]][$board["rekening_posisi"]];
    //         // $nilai = $board["nilai"];
    //         $nilai_f = formatField2("debet", $nilai);
    //         $label = $board["label"];
    //         $box_warna = $board["bg"];
    //         $link_lager = "#";
    //         if(isset($board["link"])){
    //             $link_lager = isset($board["link"]) ? $board["link"] : "#";
    //         }
    //         $link_label = isset($board["link_label"]) ? $board["link_label"] : "";
    //         $fa_icon = $board["icon"];
    //         $lebar_col = 12 / $jml;
    //         $lebar = $lebar_col > 4 ? "4" : $lebar_col;
    //         $var .= "<div class='col-lg-$lebar col-xs-6'>
    //                         <div class='small-box $box_warna'>
    //                             <div class='inner'>
    //                                 <h3 style='font-size: 1.5em;'>$nilai_f</h3>
    //                                 <p class='text-uppercase' style='font-size: .9em;'>$label</p>
    //                             </div>
    //                             <div class='icon'>
    //                                 <i class='fa $fa_icon ' style='font-size: .5em;' aria-hidden='true'></i>
    //                             </div>
    //                             <a href='$link_lager' class='small-box-footer' target='_blank'>$link_label <i class='fa fa-hand-o-right'></i></a>
    //                         </div>
    //                     </div>";
    //     }
    //
    //     echo $var;
    // }
}
