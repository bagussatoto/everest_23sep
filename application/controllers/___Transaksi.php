<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:42 PM
 */
class Transaksi extends CI_Controller
{
    private $template;
    private $jenisTr;
    private $jenisTrName;
    private $trConfig;
    private $tableInConfig;
    private $tableInConfig_static;

    private $arrButtonAction;


    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        sesAuth($this->session->login['id']);//tendanger untuk testing di off dulu


        $tmpJenis = $this->uri->segment(3);
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;

//            $membership = is_array($this->session->login['membership'])?$this->session->login['membership']:array();
//            $steps=$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
//            $jmlAllowed=0;
//            if(sizeof($steps)>0){
//                foreach($steps as $num=>$sSpec){
//                    if(in_array($sSpec['userGroup'],$membership)){
//                        $jmlAllowed++;
//                    }
//                }
//            }
//            if($jmlAllowed<1){
//                //cekMerah("__ILLEGAL ACCESS ATTEMPT__");die();
//            }

//            //cekMerah("bikin jenisTR ". $this->jenisTr);
//            $this->jenisTr = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'] : $tmpJenis;
            $this->jenisTrName = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] : "unnamed";

            $heTransaksi_ui = (null != $this->config->item("heTransaksi_ui")) ? $this->config->item("heTransaksi_ui") : array();
            if (sizeof($heTransaksi_ui) > 0) {
                $this->template = isset($heTransaksi_ui[$this->jenisTr]) ? base_url() . "template/" . $heTransaksi_ui[$this->jenisTr]['template'] . ".html" : "";
            } else {
                die("konfigurasi transaksi belum ditentukan");
            }
//            $this->trConfig = (null != $this->config->item("heTransaksi_ui")[$this->jenisTr]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
            $this->trConfig = (isset($this->config->item("heTransaksi_ui")[$this->jenisTr])) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
        } else {
            // die("trJenis required!");

        }

        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
        $this->arrButtonAction = $this->config->item("button");


    }

    public function index()
    {
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }

        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields'] : array();

        //
        //region lookup on-going transactions
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
        if (sizeof($steps) > 1) {
            $stepCodes = array();
            $jmlStep = count($steps);
            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber < $jmlStep) {
                    $stepCodes[] = $stepSpec['target'];
                }

            }

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
//            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
//            $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
            $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
            $tr->addFilter("next_substep_code<>''");

//            $this->db->group_start();         
//            $this->db->group_end();

            $tmpHist = $tr->lookupUndoneEntries_joined($this->session->login['cabang_id'],$this->session->login['gudang_id'])->result();
//            cekHere($this->db->last_query());
//             print_r($tmpHist);die();
            $arrayOnprogress = array();
            if (sizeof($tmpHist) > 0) {
                foreach ($tmpHist as $row) {
                    $tmp = array();
                    foreach ($historyFields as $fName => $fLabel) {
                        //$tmp[$fName] = $row->$fName;
                        $tmp[$fName] = formatField($fName, $row->$fName);
                    }
//                    $tmp['state'] = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->step_current]['stateLabel'];
                    if ($row->sub_step_number > 0) {
                        $tmp['state'] = "<span style='color:" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                        $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail);
                    } else {
                        $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                    }

                    $tmp['action'] = "";
                    $nextStepNum = ($row->next_substep_num);
                    $currentStepNum = ($row->sub_step_number);
//                    echo $currentStepNum." vs ".$nextStepNum."<br>";
                    $allowFollowup = false;
                    if ($row->sub_step_number > 0) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = "cancel " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }

                    if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum])) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['actionLabel'];

                        }
                    }

                    if ($allowFollowup) {
                        $allowJoin = isset($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                        $stepLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['label'];

                        $followupLink = "document.getElementById('result').src=('" . base_url() . "Transaksi/followupPrePreview/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                        $tmp['action'] = "<div class='input-group'>";
                        $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</a>";
                        if ($allowJoin) {
                            $tmp['action'] .= "<span class='input-group-addon'>";
                            $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                            $tmp['action'] .= "</span class='input-group-addon'>";
                        }
                        $tmp['action'] .= "</div class='input-group'>";
                    }
                    $arrayOnprogress[] = $tmp;
                }
            }
        } else {
            $arrayOnprogress = array();
        }
        //endregion
        //
        //region lookup histories
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("jenis='" . $steps[1]['target'] . "'");
        $tr->addFilter("next_step_code=''");
        $tmpHist = $tr->lookupRecentHistories()->result();

        // print_r($tmpHist);die();
        $arrayHistory = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                $tmp = array();
                $tmp['dtime'] = $row->dtime;
                foreach ($historyFields as $fName => $fLabel) {
                    $tmp[$fName] = formatField($fName, $row->$fName);
                }
                $arrayHistory[] = $tmp;
            }
        }

        //endregion
        //
        //region link to add new transaction
        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            } else {
                $targetUrl = base_url() . "Transaksi/createForm/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            );
        } else {
            $addLink = null;
        }
        //endregion


        //
        //region generate activity reports
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("month(dtime)='" . date("m") . "'");
        $tr->addFilter("year(dtime)='" . date("Y") . "'");

        $tmpRecap = $tr->lookupAll()->result();
//        cekHijau($this->db->last_query());
//        arrprint($tmpRecap);die();

        $sumFields = $this->config->item("heTransaksi_layout")[$this->jenisTr]['reportSumFields'];
        $arrayRecap = array();

        foreach ($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'] as $stepSpec) {
            $recapCodes[$stepSpec['target']] = "<a href='" . base_url() . get_class($this) . "/viewHistory/" . $this->jenisTr . "/" . $stepSpec['target'] . "'>" . $stepSpec['label'] . "</a>";
        }
        $recapProp = array();
        $entities = array();
        if (sizeof($tmpRecap) > 0) {
            foreach ($tmpRecap as $row) {


                if (isset($sumFields) && sizeof($sumFields) > 0) {
                    foreach ($sumFields as $idField => $nameField) {
                        if (!array_key_exists($row->$idField, $entities)) {
                            $entities[$row->$idField] = $row->$nameField;
                        }
                        if (!isset($recapProp[$row->jenis][$row->$idField])) {
                            $recapProp[$row->jenis][$row->$idField] = array(
                                "freq" => 0,
                                "value" => 0,
                            );
                        }
                        $recapProp[$row->jenis][$row->$idField]['freq'] += 1;
                        $recapProp[$row->jenis][$row->$idField]['value'] += $row->transaksi_nilai;
                    }
                }
            }
        }


        if (sizeof($entities) > 0) {
            foreach ($entities as $eID => $eName) {
                $tmp = array();
                $tmp["name"] = $eName;
                foreach ($recapCodes as $cID => $cName) {
                    $fieldValue = isset($recapProp[$cID][$eID]['value']) ? $recapProp[$cID][$eID]['value'] : 0;
                    $tmp[$cID] = "<span class='btn-block text-right'>" . number_format($fieldValue) . "</span>";
                }
                $arrayRecap[] = $tmp;
            }
        }

        $recapLabels['name'] = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['pihakLabel']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['pihakLabel'] : "label";
        $recapLabels = $recapLabels + $recapCodes;
        //endregion

//        arrprint($recapLabels);die();
        //
        //region prepare params to viewer
        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "subTitle" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            'addLink' => $addLink,
            "historyTitle" => "<span class='glyphicon glyphicon-time'></span> " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            "arrayHistoryLabels" => array("dtime" => "time") + $historyFields,
            "arrayHistory" => $arrayHistory,
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "entities" => $entities,
            "recapTitle" => "<span class='fa fa-newspaper-o'></span> monthly " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " reports (" . date("F Y") . ")",
            "arrayRecapLabels" => $recapLabels,
            "arrayRecap" => $arrayRecap,

        );
        //endregion

        $this->load->view("transaksi", $data);

    }

    public function createForm(){

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }



        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "items" => array(),
                "main" => array(),
                "out_master" => array(),
            );
        }
        if (!isset($_SESSION[$cCode]['main'])) {
            $_SESSION[$cCode]['main'] = array();
        }
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }
        if (!isset($_SESSION[$cCode]['out_master'])) {
            $_SESSION[$cCode]['out_master'] = array();
        }


        $initMaster = array(
            "olehID" => $this->session->login['id'],
            "olehName" => $this->session->login['nama'],
            "placeID" => $this->session->login['cabang_id'],
            "placeName" => $this->session->login['cabang_nama'],
            "cabangID" => $this->session->login['cabang_id'],
            "cabangName" => $this->session->login['cabang_nama'],
            "gudangID" => $this->session->login['gudang_id'],
            "gudangName" => $this->session->login['gudang_nama'],
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "jenisTrName" => $this->jenisTrName,
            "stepNumber" => 1,
            "stepCode" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "dtime" => date("Y-m-d H:i:s"),
        );


        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
            $_SESSION[$cCode]['out_master'][$key] = $val;
        }


        $historyFields = $this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields'];

        //
        //region lookup on-going transactions


        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
        if (sizeof($steps) > 1) {
            $stepCodes = array();
            $jmlStep = count($steps);
            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber < $jmlStep) {
                    $stepCodes[] = $stepSpec['target'];
                }

            }


            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
//            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
//            $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
            $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
            $tr->addFilter("next_substep_code<>''");
//            $tmpHist = $tr->lookupUndoneEntries_joined()->result();
            $tmpHist = $tr->lookupUndoneEntries_joined($this->session->login['cabang_id'], $this->session->login['gudang_id'])->result();
//            print_r($this->db->last_query());

//             print_r($tmpHist);die();
            $arrayOnprogress = array();
            if (sizeof($tmpHist) > 0) {
                foreach ($tmpHist as $row) {
                    $tmp = array();
                    foreach ($historyFields as $fName => $fLabel) {
                        //$tmp[$fName] = $row->$fName;
                        $tmp[$fName] = formatField($fName, $row->$fName);
                    }
//                    $tmp['state'] = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->step_current]['stateLabel'];
                    if ($row->sub_step_number > 0) {
                        $tmp['state'] = "<span style='color:" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                        $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail);
                    } else {
                        $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                    }

                    $tmp['action'] = "";
                    $nextStepNum = ($row->next_substep_num);
                    $currentStepNum = ($row->sub_step_number);
//                    echo $currentStepNum." vs ".$nextStepNum."<br>";
                    $allowJoin = isset($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;


                    $allowFollowup = false;
                    if ($row->sub_step_number > 0) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = "cancel " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }

                    if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum])) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['actionLabel'];

                        }
                    }

                    if ($allowFollowup) {
                        $stepLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['label'];

                        $followupLink = "document.getElementById('result').src=('" . base_url() . "Transaksi/followupPrePreview/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
//                        $tmp['action'] = "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</a>";
                        $tmp['action'] = "<div class='input-group'>";
                        $tmp['action'] .= "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</a>";
                        if ($allowJoin) {
                            $tmp['action'] .= "<span class='input-group-addon'>";
                            $tmp['action'] .= "<a title='process many items at once' href='" . base_url() . "Transaksi/viewIncomplete/" . $this->jenisTr . "/$currentStepNum'><span class='fa fa-dedent'></span></a>";
                            $tmp['action'] .= "</span class='input-group-addon'>";
                        }
                    }
                    $arrayOnprogress[] = $tmp;
                }
            }
        } else {
            $arrayOnprogress = array();
        }
        //endregion

        //

        //region menambah supplier/customer (kalau berwenang)
        $pihakModel = $this->config->item('heTransaksi_ui')[$this->jenisTr]['pihakModel'];
        $dataAccess = isset($this->config->item('heDataBehaviour')[$pihakModel]) ? $this->config->item('heDataBehaviour')[$pihakModel] : array(
            "viewers" => array(),
            "creators" => array(),
            "creatorAdmins" => array(),
            "updaters" => array(),
            "updaterAdmins" => array(),
            "deleters" => array(),
            "deleterAdmins" => array(),
            "historyViewers" => array(),
        );
        $dataLabel = isset($this->config->item('heDataBehaviour')[$pihakModel]) ? $this->config->item('heDataBehaviour')[$pihakModel]['label'] : $pihakModel;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $addPihakStr = "";
        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $pihakModel);
//        arrprint($dataAccess['creators']);
        if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
            if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $dataLabel . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        }
                                        );";
                $addPihakStr = "<span class='input-group-btn'>
                                        <a href='javascript:void(0)' class='btn btn-default' onclick=\"$addClick\">
                                            <span class='fa fa-user-plus'>                                            
                                             </span>
                                        </a>
                                </span>";
            }
        }
        //endregion


        //
        //region lookup on-going from connected requests
        $progress2Fields = array();
        $arrayOnprogress2 = array();
        $reqFormTaret = "";
        $needToClear = false;
        if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['requestCode'])) {
            $masterRefCode = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['masterCode'];
            $stateRefCode = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['stateCode'];
            $stateRefNum = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['stepNumber'];
            $progress2Fields = array("select" => "select") + $this->config->item("heTransaksi_ui")[$masterRefCode]['shortHistoryFields'];
            $reqFormTarget = base_url() . get_class($this) . "/swapFrom/$jenisTr";
//            $progress2Fields['state'] = "status";
//            $progress2Fields['action'] = "action";

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("jenis_master='" . $masterRefCode . "'");
            $tr->addFilter("jenis='" . $stateRefCode . "'");
            $tr->addFilter("step_number='" . $stateRefNum . "'");
//            $tr->addFilter("next_step_code<>''");
            $tmpByReq = $tr->lookupRecentHistories()->result();
//            print_r($this->db->last_query());die();


            if (sizeof($tmpByReq) > 0) {

                $allowMultiSelect = isset($this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['allowMultiSelect']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['allowMultiSelect'] : false;

                foreach ($tmpByReq as $row) {
                    $tmp = array();
                    $inputType = $allowMultiSelect == true ? "checkbox" : "radio";
                    $tmp['select'] = "<label><input type=$inputType name='trID[]' value='" . $row->id . "'> select</label>";
                    foreach ($historyFields as $fName => $fLabel) {
                        $tmp[$fName] = formatField($fName, $row->$fName);
                    }


                    $arrayOnprogress2[] = $tmp;
                }
            }
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $needToClear = true;
            }

        }

//        arrprint($arrayOnprogress2);

        //endregion

        //
        //region lookup histories
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("jenis='" . $this->jenisTr . "'");
        $tr->addFilter("next_step_code=''");
        $tmpHist = $tr->lookupRecentHistories()->result();

        // print_r($tmpHist);die();
        $arrayHistory = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    $tmp[$fName] = formatField($fName, $row->$fName);
                }
                $arrayHistory[] = $tmp;
            }
        }

        //endregion
        //

        //
        //region payment method
        $strPaymentMethod = "";
        $availPayments = isset($this->config->item("heTransaksi_ui")[$jenisTr]['availPayments']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['availPayments'] : array();

        if (sizeof($availPayments) > 1) {
            $strPaymentMethod .= "<div class='input-group'>";
            $strPaymentMethod .= "<span class='input-group-addon' style='background:#f0f0f0;'><span class='fa fa-credit-card'></span> payment method</span>";
            $strPaymentMethod .= "<select id='pm' class='form-control' onchange=\"document.getElementById('result').src='" . base_url() . "ValueGate/fillByPaymentMethod/" . $this->jenisTr . "?val='+this.value;\">";
            $strPaymentMethod .= "<option value=''>-select-</option>";
            foreach ($availPayments as $key => $pSpec) {
                $valueSrc = $availPayments[$key]['valueSrc'];
                $defValueSrc = $availPayments[$key]['valueGate'];
                $selected = isset($_SESSION[$cCode]['out_master']['paymentMethod']) && $key == $_SESSION[$cCode]['out_master']['paymentMethod'] && (isset($_SESSION[$cCode]['out_master'][$defValueSrc]) && $_SESSION[$cCode]['out_master'][$defValueSrc] > 0) ? "selected" : "";
                if ($selected == "selected") {
                    $_SESSION[$cCode]['out_master'][$defValueSrc] = isset($_SESSION[$cCode]['out_master'][$valueSrc]) && $_SESSION[$cCode]['out_master'][$valueSrc] > 0 ? $_SESSION[$cCode]['out_master'][$valueSrc] : 0;
                }
                $strPaymentMethod .= "<option value='$key' $selected>" . $pSpec['label'] . "</option>";
            }
            $strPaymentMethod .= "</select>";
            $strPaymentMethod .= "</div class='input-group'>";
        } else {
            if (sizeof($availPayments) == 1 && array_key_exists("inherit", $availPayments)) {
                $strPaymentMethod .= "<span id='pm' class='form-control'></span>";
            }

        }
        //endregion

        //
        //region external tool
        if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['extTool'])) {
            $extToolConfig = $this->config->item("heTransaksi_ui")[$jenisTr]['extTool'];
            $srcVar = $extToolConfig['sentParam'];
            $srcField = $extToolConfig['sentField'];
            $externSrc = $extToolConfig['externSrc'];
            $rawParam = array();
            $backUrl = base_url() . $extToolConfig['backUrl'];
            if (isset($_SESSION[$cCode][$srcVar]) && sizeof($_SESSION[$cCode][$srcVar]) > 0) {

                foreach ($_SESSION[$cCode][$srcVar] as $id => $rSpec) {
                    if (isset($rSpec[$srcField])) {
                        $rawParam[$id] = $rSpec[$srcField];
                    }
                }
            }
//            arrPrint($rawParam);die();
            if (sizeof($rawParam) > 0) {
                $param = base64_encode(serialize($rawParam));
                $backUrl = base64_encode(serialize($backUrl));
                $extTool = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"window.open('" . $extToolConfig['url'] . "?param=$param&extern=" . $_SESSION[$cCode]['main'][$externSrc] . "&back=$backUrl','wTool');\" >" . $extToolConfig['label'] . "</a>";
            } else {
                $extTool = "<a class='btn btn-default' disabled>" . $extToolConfig['label'] . "</a>";
            }

        } else {
//            //cekMerah("extTool doesnt exist");
            $extTool = "";
        }
        //endregion

        $allowTmpSave = isset($this->config->item("heTransaksi_ui")[$jenisTr]['allowTmpSave']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['allowTmpSave'] : false;

        //
        //region prepare params to viewer

//		arrprint($arrayOnprogress2);

        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "subTitle" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "jenisTransaksi" => $jenisTr,
            //            "selectorCaller" => base_url() . "_selectorItem/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorModel"],
            "selectorCaller" => base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorModel"],
            "selectorLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["selectorLabel"],
            //            "pihakCaller" => base_url() . "_selectorPihak/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakModel"],
            "pihakCaller" => base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakModel"],
            "pihakCallerDelete" => base_url() . "_processPihak/remove/$jenisTr",
            // "pihakLabel"=>$this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "pihakLabel" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] . "(" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"] . ")" : $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayProgress2Labels" => $progress2Fields,
            "reqFormTarget" => isset($reqFormTarget) ? $reqFormTarget : "",
            "arrayOnProgress2" => $arrayOnprogress2,
            "strPaymentMethod" => $strPaymentMethod,
            "extTool" => $extTool,
            "columnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/",
            "defaultDescription" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "allowJoin" => isset($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['allowJoin']) && $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['allowJoin'] == true ? $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['allowJoin'] : false,
            "allowTmpSave" => $allowTmpSave,
            "needToClear" => $needToClear,
            "addPihakStr" => $addPihakStr
        );
        //endregion
//arrPrint($data);
        $this->load->view("transaksi", $data);
//        $this->session->errMsg = "";
    }

    public function viewIncomplete()
    {


        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        $historyFields = $this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields'];

        //
        //region lookup on-going transactions
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
        $stepLabels = array(
            "0" => "all",
        );
        $stepLinks = array(
            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3),
        );
        if (sizeof($steps) > 1) {
            $stepCodes = array();

            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber < $jmlStep) {
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['stateLabel'];
//                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepNumber;
                }

            }

            if ($steps[1]['userGroup'] == $this->session->login['jenis']) {
                $stepLabels[-1] = "<span class='glyphicon glyphicon-plus'></span>";
                $stepLinks[-1] = base_url() . $this->uri->segment(1) . "/createForm/" . $this->uri->segment(3);
            }


            $currentState = $this->uri->segment(4) > 0 ? $this->uri->segment(4) : 0;


            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
//            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
//            $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
//            $tr->addFilter("jenis='" . $steps[1]['target'] . "'");
            $tr->addFilter("jenis_top='" . $steps[1]['target'] . "'");
            $tr->addFilter("next_substep_code<>''");
            if ($currentState > 0) {
                $tr->addFilter("sub_step_number='$currentState'");
                $tr->addFilter("valid_qty>0");
            }
//            $tmpHist = $tr->lookupUndoneEntries_joined()->result();
            $tmpHist = $tr->lookupUndoneEntries_joined($this->session->login['cabang_id'],$this->session->login['gudang_id'])->result();
//            print_r($this->db->last_query());

//             print_r($tmpHist);die();
            $selectedTopID = isset($_GET['topID']) ? $_GET['topID'] : 0;

            $arrayOnprogress = array();
            $arrayOnprogressbyState = array();
            $rowCtr = 0;
            if (sizeof($tmpHist) > 0) {
                foreach ($tmpHist as $row) {
                    $rowCtr++;
                    $tmp = array();
                    foreach ($historyFields as $fName => $fLabel) {
//                        $tmp[$fName] = $row->$fName;
                        $tmp[$fName] = formatField($fName, $row->$fName);
                    }
                    if ($row->sub_step_number > 0) {
                        $tmp['state'] = "<span style='color:" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateColor'] . "'>" . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$row->sub_step_number]['stateLabel'] . "</span>";
                        $tmp['state'] .= "<br>" . createStateSign($row->sub_step_number, $row->step_avail);
                    } else {
                        $tmp['state'] = "<span style='color:#777777'>canceled</span>";
                    }

                    $tmp['action'] = "";
                    $nextStepNum = ($row->next_substep_num);
                    $currentStepNum = ($row->sub_step_number);
//                    echo $currentStepNum." vs ".$nextStepNum."<br>";
                    $allowJoin = isset($this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin']) && $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] == true ? $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$nextStepNum]['allowJoin'] : false;
                    $allowFollowup = false;
                    if ($row->sub_step_number > 0) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = "cancel " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['label'];
                        }
                    }

                    if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum])) {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                            $actionLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['actionLabel'];

                        }
                    }

                    if ($allowFollowup) {
                        $stepLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['label'];

                        $followupLink = "document.getElementById('result').src=('" . base_url() . "Transaksi/followupPrePreview/" . $row->transaksi_id . "/$nextStepNum/" . $row->sub_step_number . "');";
                        $tmp['action'] = "<a class='btn btn-primary btn-block' title='turn this entry into $stepLabel' href='javascript:void(0)' onClick =\"$followupLink\">" . $actionLabel . "</a>";
                    }


                    if ($currentState > 0) {
                        if (isset($tmp['jenis_label'])) {
                            $clickEvent = "";
                            if ($row->id_top == $selectedTopID) {
                                $checked = "checked";
                            } else {
                                $clickEvent = "location.href='" . base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?topID=" . $row->id_top . "';";
                                $checked = "";
                            }

                            $tmp['jenis_label'] = "<label><input type='checkbox' name='oID[]' $checked onclick=\"$clickEvent\" value='" . $row->transaksi_id . "'>" .
                                $tmp['jenis_label'] . "</label>";
//                                echo "topID:".$row->id_top."<br>";
                        }
                    }

                    $arrayOnprogress[] = $tmp;
                    $arrayOnprogressbyState[$row->sub_step_number][] = $tmp;


                }
            }
        } else {
            $arrayOnprogress = array();
        }


        //endregion

        //
        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "subTitle" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " with status '" . $stepLabels[$currentState] . "'",
            "jenisTr" => $jenisTr,
            "jenisTransaksi" => $jenisTr,
            //            "selectorCaller" => base_url() . "_selectorItem/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorModel"],
            "selectorCaller" => base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["selectorModel"],
            "selectorLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["selectorLabel"],
            //            "pihakCaller" => base_url() . "_selectorPihak/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakModel"],
            "pihakCaller" => base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->config->item("heTransaksi_ui")[$jenisTr]["pihakModel"],
            "pihakCallerDelete" => base_url() . "_processPihak/remove/$jenisTr",
            // "pihakLabel"=>$this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "pihakLabel" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],

            "arrayProgressLabels" => $progressFields,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayOnProgressByState" => $arrayOnprogressbyState,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "currentState" => $currentState,
            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewHistory/" . $this->uri->segment(3),
            "alternateLinkCaption" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories " . "<span class='glyphicon glyphicon-arrow-right'></span>",
            //==untuk keperluan followup many-to-one
            "allowJoin" => isset($allowJoin) ? $allowJoin : false,
            "actionLabel" => isset($actionLabel) ? $actionLabel : "",
            "_nextStepNum" => isset($nextStepNum) ? $nextStepNum : "",
            "_currentStepNum" => $currentState,
            "followupBase" => base_url() . "Transaksi/followupPrePreview/",
        );
        //endregion


        $this->load->view("history", $data);
    }

    public function viewHistory()
    {

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $limit = 18;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
//        $historyFields = $this->config->item("heTransaksi_ui")[$jenisTr]['shortHistoryFields'];
        $historyFields = isset($this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields']) ? $this->config->item("heTransaksi_report")[$jenisTr]['longHistoryFields'] : array(
            "produk_nama" => "item name",
            "produk_ord_jml" => "qty",
            "produk_ord_hrg" => "@price",

            "nomer_top+nomer" => "receipt number",
            "oleh_nama+dtime" => "person",
        );

//        arrprint($historyFields);
        //
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


            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
        }
        //endregion

        //
        //region lookup histories
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }

        $tr->addFilter("next_substep_code=''");


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

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $jmlData = $tr->lookupHistoryCount_joined();
//        cekHijau($this->db->last_query());

        $numPages = ceil($jmlData / $limit);
        $pages = array();

        if ($jmlData > 0) {
            $factor = ($maxPageNum / 2);
            $selisihDepan = ($page - $factor);
            $selisihBelakang = ($page + $factor);

            $firstNum = 0;
            $lastNum = 0;

            if ($selisihDepan >= 0) {
                $pages["<span class='glyphicon glyphicon-home'></span> "] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?page=1";
                $firstNum = $selisihDepan;
                $lastNum = $selisihBelakang;
            } else {
                $firstNum = 1;
                $lastNum = abs($selisihDepan) + $selisihBelakang;
            }
            if ($lastNum > $numPages) {
                $lastNum = $numPages;
            }

            for ($i = $firstNum; $i <= $lastNum; $i++) {
                $pages[$i] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?page=$i";
            }
            if ($lastNum <= $numPages) {
                $pages[" <span class='glyphicon glyphicon-fire'></span>"] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?page=$numPages";
            }
        }


        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        $tmpHist = $tr->lookupHistories_joined($jmlData, $limit, $page)->result();

//        cekKuning($this->db->last_query());

        $arrayHistory = array();
        $arrayHistory_ids = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                $tmp = array();
                $tmp1 = array();
                foreach ($historyFields as $fName => $fLabel) {


                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            $colValue .= isset($row->$key) ? formatField($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    } else {
                        $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";
                    }

//                    $tmp[$fName] = formatField($fName, $row->$fName);
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
                }
                $arrayHistory[] = $tmp;
                $arrayHistory_ids[] = $tmp1;

            }
        }
        //endregion
        //
        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            "subTitle" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] . " histories" : $this->jenisTrName . " histories",
            "pageCount" => $numPages,
            "page" => $page,
            "pages" => $pages,
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",

        );
        //endregion


        $this->load->view("history", $data);
    }

    public function debug() //print values from sessions
    {
        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        if (isset($_SESSION[$cCode])) {
            cekKuning("shopping-cart (creator)");
            arrprint($_SESSION[$cCode]);
        } else {
            die("the gate index you want to debug has not been formed yet!");
        }

    }

    public function viewReceiptOLD()
    {
//

        $no = $this->uri->segment(3);
//        $targetStepNum = $this->uri->segment(4);
//        $currentStepNum = $this->uri->segment(5);
//        $afterTargetStepNum = ($targetStepNum + 1);
        $cCode = "_TR_" . $this->jenisTr;


        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion


        $signNumbers = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        $signValues = array();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues[$row->step_number] = array(
                    "caption" => isset($this->config->item("heTransaksi_ui")[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->config->item("heTransaksi_ui")[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "caption_nama" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }


        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $itemLabels = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
//            arrprint($itemLabels);
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();
            $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;
//            $masterID = isset($tmpTr[0]->referensi_id) && $tmpTr[0]->referensi_id > 0 ? $tmpTr[0]->referensi_id : $tmpTr[0]->transaksi_id;

//            $afterTargetStepNum = ($currentStepNum + 1);

            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
//            //cekMerah($this->db->last_query());
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }
//            arrPrint($detailValues);
            //endregion

//            arrPrint($mainValues);die();

            //region take from registries
            //==ambil value-gate
            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
//            $mainApplets = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {
                        case "out_master"://
                            $masterGates = unserialize(base64_decode($row->values));
                            break;
                        case "out_detail"://
                            $childGates = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
//                        case "main_applets"://
//                            $mainApplets = unserialize(base64_decode($row->values));
//                            break;
                    }
                }

            } else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            foreach ($tmpTr as $row) {
                $id = $row->produk_id;
                $tmp = array();
                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
//                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
                            $fieldValue = $detailValues[$row->produk_id][$key];
                        } else {
                            if (isset($row->$key)) {
                                $fieldValue = $row->$key;
                            }
                        }

//                        $tmp[$key] = formatField($key, $fieldValue);
                        $tmp[$key] = $fieldValue;
                    }
                }

                //region calculate subtotal
//                arrPrint($childGates[$id]);
                //===perhitungan subtotal
                $this->load->library("FieldCalculator");
                $cal = new FieldCalculator();


                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("harga", "produk_ord_hrg", $subAmountConfig);
//                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $tmpEx = $cal->multiExplode($subAmountConfig);
                    if (sizeof($tmpEx) > 1) {
                        $newSrc = $subAmountConfig;
                        foreach ($tmpEx as $key2 => $val2) {
                            if (isset($childGates[$id][$val2])) {
                                $newSrc = str_replace($val2, $childGates[$id][$val2], $newSrc);
//                                //cekKuning("$val2 direplace dengan " . $childGates[$id][$val2]);
                            } else {
                                if (isset($tmp[$val2])) {
                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                    //cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                } else {
                                    $newSrc = str_replace($val2, "0", $newSrc);
//                                    //cekKuning("$val2 direplace dengan NOL");
                                }
                            }


                        }
                        $subtotal = $cal->calculate($newSrc);
//                        //cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                    } else {
                        $subtotal = 0;
//                        //cekHijau("subtotal dari perhitungan yang gak ada");
                    }
                } else {
                    $subtotal = 0;
//                    //cekHijau("subtotal NOL");
                }
                $tmp["subtotal"] = $subtotal;
                //endregion

                $rawItems[$row->produk_id] = $tmp;


            }

        } else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";

            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";

            echo "</div class='alert alert-danger'>";
            die();
        }
        //endregion


        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;


        //
        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        //endregion


        //  region company profile
        $this->load->helper("company_profiles");
        $arrCompanyProfile = companyProfile();
        arrPrint($arrCompanyProfile);
        //  endregion

        //region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) && sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $targetStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        } else {

        }

        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $mainValues,
            "detailValues" => $detailValues,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "extValueLabels" => isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] : array(),
            //            "extEditableFields" => $editableAddVals,
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            //            "mainApplets"    => $mainApplets,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "grandTotal" => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => "",
        );
//        if (isset($_SESSION[$cCode]['main'])) {
//            $data['pihakID'] = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0;
//            $data['pihakName'] = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
//        }

        //endregion

        $this->load->view("transaksi", $data);

    }

    public function viewReceipt()
    {
        $globalVars = array();

        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;


        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no . "'");
        $tmpTr = $tr->lookupJoined()->result();
        //endregion


        //region signatures
        $signNumbers = array();
        $signValues = array();
        $signExtValues = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($tmpTr[0]->id_master)->result();
        if (sizeof($tmpSign) > 0) {
            foreach ($tmpSign as $row) {
                $signValues['sign_' . $row->step_number] = array(
                    "label" => isset($this->config->item("heTransaksi_ui")[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption']) ? $this->config->item("heTransaksi_ui")[$tmpTr[0]->jenis_master]['steps'][$row->step_number]['stateCaption'] : "",
                    "contents" => isset($row->oleh_nama) ? $row->oleh_nama : "",
                    "caption_department" => isset($this->config->item("userGroup")[$row->group_code]) ? $this->config->item("userGroup")[$row->group_code] : "",
                );
            }
        }


        //endregion


        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;

            $itemLabels = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$currentStepNum]) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$currentStepNum] : array();
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$currentStepNum] : array();
            $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$currentStepNum] : null;


            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();

            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }

            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }
//            arrPrint($detailValues);
            //endregion

//            arrPrint($mainValues);die();

            //region take from registries
            //==ambil value-gate
            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {
                        case "out_master"://
                            $masterGates = unserialize(base64_decode($row->values));
                            break;
                        case "out_detail"://
                            $childGates = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                    }
                }
            } else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            foreach ($tmpTr as $row) {
                $id = $row->produk_id;
                $tmp = array();
                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
//                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
                            $fieldValue = $detailValues[$row->produk_id][$key];
                        } else {
                            if (isset($row->$key)) {
                                $fieldValue = $row->$key;
                            }
                        }

//                        $tmp[$key] = formatField($key, $fieldValue);
                        $tmp[$key] = $fieldValue;
                    }
                }

                //region calculate subtotal
//                arrPrint($childGates[$id]);
                //===perhitungan subtotal
                $this->load->library("FieldCalculator");
                $cal = new FieldCalculator();


                if ($subAmountConfig != null) {
                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("harga", "produk_ord_hrg", $subAmountConfig);
//                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $tmpEx = $cal->multiExplode($subAmountConfig);
                    if (sizeof($tmpEx) > 1) {
                        $newSrc = $subAmountConfig;
                        foreach ($tmpEx as $key2 => $val2) {
                            if (isset($childGates[$id][$val2])) {
                                $newSrc = str_replace($val2, $childGates[$id][$val2], $newSrc);
//                                //cekKuning("$val2 direplace dengan " . $childGates[$id][$val2]);
                            } else {
                                if (isset($tmp[$val2])) {
                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                    //cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                } else {
                                    $newSrc = str_replace($val2, "0", $newSrc);
//                                    //cekKuning("$val2 direplace dengan NOL");
                                }
                            }


                        }
                        $subtotal = $cal->calculate($newSrc);
//                        //cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                    } else {
                        $subtotal = 0;
//                        //cekHijau("subtotal dari perhitungan yang gak ada");
                    }
                } else {
                    $subtotal = 0;
//                    //cekHijau("subtotal NOL");
                }
                $tmp["subtotal"] = $subtotal;
                //endregion

                $rawItems[$row->produk_id] = $tmp;


            }
        } else {
            echo "<div class='alert alert-warning text-center'>";
            echo "the entry you are trying to access does not exist.<br>";
            echo "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            echo "if this error re-occurs, please contact system developer.<br>";

            echo "<a class='btn' data-dismiss='modal'>okay, got it</a>";

            echo "</div class='alert alert-danger'>";
            die();
        }
        //endregion


        $items = array();
        $items2 = array();
        $jenisTr = $this->jenisTr;
        $items = $rawItems;

        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
        $globalVars = $globalVars + (array)$mainProp;
        //endregion


        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion


        $elements = array();
        if (sizeof($mainElements) > 0) {
            foreach ($mainElements as $eKey => $eSpec) {
                $elementType = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'][$eKey]['elementType']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'][$eKey]['elementType'] : array();
                switch ($elementType) {
                    case "dataModel":
                        foreach ($eSpec as $key => $val) {
                            if ($key == "contents") {
                                $vTmp = $val != null ? unserialize(base64_decode($val)) : "-";
                            } else {
                                $vTmp = $val != null ? $val : "-";
                            }
                            $eTmp[$key] = $vTmp;


                            if (is_array($vTmp)) {
                                foreach ($vTmp as $key => $val) {
                                    $elementsGate[$eKey . "_$key"] = $val;
                                }
                            } else {
                                $elementsGate[$eKey . "_$key"] = $vTmp;
                            }
                        }

                        break;
                    case "dataField":
                        $eTmp = array();
                        foreach ($eSpec as $key => $val) {
                            $vTmp = $val != null ? $val : "-";

                            $eTmp[$key] = $vTmp;
                            $eTmp['contents']['nama'] = $eSpec['value'];

                            $elementsGate[$eKey . "_$key"] = $vTmp;
                        }
                        break;
                }
                $elements[$eKey] = $eTmp;
            }
        }

//        $globalVars = $globalVars + $elementsGate + $arrCompanyProfile;
        $globalVars = $globalVars + $arrCompanyProfile;
        if (isset($elementsGate)) {
            $globalVars = $globalVars + $elementsGate;
        }


        //region fixed signature
        $fixedSignConfig = isset($this->config->item("heTransaksi_layout")[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum]) ? $this->config->item("heTransaksi_layout")[$tmpTr[0]->jenis_master]['fixedSignatures'][$currentStepNum] : array();
        if (sizeof($fixedSignConfig) > 0) {
            foreach ($fixedSignConfig as $key => $eSpec) {
                if (substr($eSpec['label'], 0, 1) == ".") {
                    $label = str_replace(".", "", $eSpec['label']);
                } else {
                    $label = "";
                }
                $signValues[$key . 'Signitures']['label'] = $label;
                $signValues[$key . 'Signitures']['contents'] = isset($globalVars[$eSpec['contents']]) ? $globalVars[$eSpec['contents']] : "";
                $signValues[$key . 'Signitures']['caption_department'] = "";
            }
        }
//arrPrint($signValues);
        //endregion


        //region fixed element
        $elementFixedConfig = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedElements'][$currentStepNum]) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['fixedElements'][$currentStepNum] : array();
        if (sizeof($elementFixedConfig) > 0) {
            foreach ($elementFixedConfig as $key => $label) {
                $fixedElements['fixedElements']['contents'][$label] = isset($globalVars[$key]) ? $globalVars[$key] : "";
                $fixedElements['fixedElements']['label'] = $globalVars['jenis_label'];
            }
        } else {
            $fixedElements = array();
        }

        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($globalVars as $key => $val) {
                $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
            }
            $companyProfile['companyProfile']['contents'][] = $companyStr;
        }
        //endregion

        //region notes element
        $fixedElements['noteDetails'] = array();
        $fixedElements['noteDetails']['contents'][] = isset($globalVars['keterangan']) ? $globalVars['keterangan'] : "-";
        $fixedElements['noteDetails']['label'] = "NOTES";
        //endregion notes element


        $elements = $elements + $fixedElements + $companyProfile;
        $footer = isset($this->config->item("heTransaksi_layout")[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number]) ? $this->config->item("heTransaksi_layout")[$tmpTr[0]->jenis_master]['staticFooter'][$row->step_number] : "";
//        die($footer);

        //region prepare params for viewer
        $editableAddVals = array();
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) && sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $currentStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        } else {

        }

//        arrprint($elements);die();


        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $temp = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $mainValues,
            "detailValues" => $detailValues,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "extValueLabels" => isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "footer" => $footer,
            //            "fixedElements" => $fixedElements,
        );

        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$currentStepNum]["label"],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            "mainValues" => $mainValues,
            "detailValues" => $detailValues,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "items" => $items,
            "items2" => $items2,
            "sumRows" => $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$currentStepNum],
            "extValueLabels" => isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] : array(),
            "mainAddValues" => $masterAddValues,
            "mainAddFields" => $masterAddFields,
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            //            "grandTotal"     => isset($masterGates['grand_total']) ? $masterGates['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "signature" => $signValues,
            "companyProfile" => $arrCompanyProfile,
            "mainElements" => $elements,
            "elementConfigs" => $elementConfigs,
            //            "fixedElements" => $fixedElements,
            "footer" => $footer,
            "temp" => $temp,
        );


        //endregion

        $this->load->view("transaksi", $data);

    }

    public function viewJembreng()
    {

        $no = $this->uri->segment(3);

        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='$no'");
        $tmp = $tr->lookupAll()->result();

        $tr->setFilters(array());
        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tr->addFilter("transaksi.id_master='" . $tmp[0]->id_master . "'");
        $tmpTr = $tr->lookupJoined()->result();
        cekKuning($this->db->last_query());
        //endregion

        //region re-arrange array...
        $trans = array();
        if (sizeof($tmpTr) > 0) {
            foreach ($tmpTr as $iSpec) {
                if (!isset($trans['main'][$iSpec->step_number])) {
                    $trans['main'][$iSpec->step_number] = array();
                }
                if (!isset($trans['items'][$iSpec->step_number])) {
                    $trans['items'][$iSpec->step_number] = array();
                }

                $trans['main'][$iSpec->step_number] = array(
                    "nomer" => $iSpec->nomer,
                    "jenis_label" => $iSpec->jenis_label,
                    "nama" => $iSpec->oleh_nama,
                );

                $trans['items'][$iSpec->step_number][] = array(
                    "nama" => $iSpec->produk_nama,
                    "jml" => $iSpec->produk_ord_jml,
                );
            }
        }
        //endregion

        arrprint($trans);
        die();
        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_layout")[$tmpTr[0]->jenis_master]["receiptTemplate"][$tmpTr[0]->step_number],
            "title" => $this->config->item("heTransaksi_ui")[$tmpTr[0]->jenis_master]["steps"][$tmpTr[0]->step_number]["label"],
            "main" => $trans['main'],
            "items" => $trans['items'],
        );

        //endregion

        $this->load->view("transaksi", $data);

    }

    public function validate()
    {
        $fieldValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldValidators"] : array();
        $rowValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowValidators"] : array();
        $appletConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['applets']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['applets'] : array();
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements'] : array();
        $availPayments = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['availPayments']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['availPayments'] : array();
        $cCode = "_TR_" . $this->jenisTr;

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        //==iterasi untuk memasukkan element relatif
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
//            cekbiru("hendak memeriksa relative impacts");
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
//                cekbiru("memeriksa $eName:");
                if (array_key_exists($eName, $relElementConfigs)) {
//                    cekhijau("$eName memiliki relative impacts");
                    $currentValue = "";
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
//                        cekhijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
//                            cekmerah("memeriksa $eName, $currentValue");
//                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
//                                $rcCtr++;
                            }
                        }

                    } else {
//                        cekmerah("TIDAK memenuhi syarat");
                    }
                }
            }
        }


        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowValidatorRules) > 0) {

            foreach ($rowValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";

                    $errRows[] = $field;
                }

            }

        }
        if (sizeof($fieldValidatorRules) > 0) {

            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "$label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "$label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 1) {
                                $errMsgs[] = "$label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }

                }
            }

        }
//        if (sizeof($appletConfigs) > 0) {
//            foreach ($appletConfigs as $aaa => $aSpec) {
//                $amdl = $aSpec['mdlName'];
//                if (!isset($_SESSION[$cCode]['main_applets'][$aSpec['mdlName']])) {
//                    $errMsgs[] = ($aSpec['label'] . " must be filled first!");
//                    echo "<script>";
//                    echo "top.document.getElementById('divapl$amdl').style.backgroundColor='#ffff00';";
//                    echo "</script>";
//                } else {
//                    if ($_SESSION[$cCode]['main_applets'][$aSpec['mdlName']]['key'] == 0) {
//                        $errMsgs[] = ($aSpec['label'] . " must be filled with one key!");
//                        echo "<script>";
//                        echo "top.document.getElementById('divapl$amdl').style.backgroundColor='#ffff00';";
//                        echo "</script>";
//                    }
//                }
//            }
//        }
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $aSpec) {
//                $amdl = $aSpec['mdlName'];
                if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                    $errMsgs[] = ($aSpec['label'] . " must be filled first!");
                    echo "<script>";
//                    echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                    echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                    echo "</script>";
                } else {
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['key']) < 1) {
                                $errMsgs[] = ("element " . $aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
//                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                                echo "</script>";
                            } else {
                                echo "<script>";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';";
                                echo "</script>";
                            }
                            break;
                        case "dataField":
                            if (strlen($_SESSION[$cCode]['main_elements'][$eName]['value']) < 1) {
                                $errMsgs[] = ($aSpec['label'] . " must be filled with one entry!");
                                echo "<script>";
//                                echo "top.document.getElementById('divel_$eName').style.backgroundColor='#ffff00';";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers text-red text-left';";
                                echo "</script>";
                            } else {
                                echo "<script>";
                                echo "top.document.getElementById('elTitle_$eName').className='box-headers bg-grey text-left';";
                                echo "</script>";
                            }
                            break;
                    }

                }
            }
        }
//        if (sizeof($availPayments) > 1) {
//            if (!isset($_SESSION[$cCode]['out_master']['paymentMethod']) || strlen($_SESSION[$cCode]['out_master']['paymentMethod']) < 2) {
//                $errMsgs[] = ("please select payment method");
//            } else {
//                //cekMerah("sudah terpilih paymentmethod");
//                $key = $_SESSION[$cCode]['out_master']['paymentMethod'];
//                $defValueSrc = $availPayments[$key]['valueGate'];
//                if (!isset($_SESSION[$cCode]['out_master'][$defValueSrc]) || $_SESSION[$cCode]['out_master'][$defValueSrc] < 1) {
//                    $errMsgs[] = ("please select payment method once again");
//                    echo "<script>";
//                    echo "top.document.getElementById('pm').value='';";
//                    echo "</script>";
//                    //cekMerah("tidak berisi nilai");
//                } else {
//                    //cekMerah("berisi nilai");
//                }
//            }
//        } else {
//            if (sizeof($availPayments) == 1 && array_key_exists("inherit", $availPayments)) {
//                if (!isset($_SESSION[$cCode]['out_master']['paymentMethod']) || strlen($_SESSION[$cCode]['out_master']['paymentMethod']) < 2) {
//                    $errMsgs[] = ("please select payment method");
//                }
//            }
//        }
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
//            print_r($_SESSION['errMsg']);
//            echo "<script>";
//            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
//            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);

        } else {

            $actionTarget = "top.BootstrapDialog.show(                                   {
                                       title:'preview',
                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
                                        draggable:false,
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        closable:true,
                                        }
                                        );";

            echo "<html>";
            echo "<head>";
            echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";
            echo "</body>";

        }
    }

    public function inspect()
    {
        $no = $this->uri->segment(3);
        $tr = new MdlTransaksi();
        $tmp = $tr->lookupJoinedInspectionByMasterID($no)->result();
        //cekKuning($this->db->last_query());
    }

    public function followupPrePreview()
    {


        $no = rtrim($this->uri->segment(3), "-");

//        $targetStepNum = $this->uri->segment(4);
        $stepNumber = $this->uri->segment(4);
        $currentStepNum = $this->uri->segment(5);

        $rawBuilderURL = blobEncode(current_url());

        //
        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
//        $tr->addFilter("step_number='" . $currentStepNum . "'");
        $tmpTr = $tr->lookupJoined()->result();
        cekBiru($this->db->last_query());
        arrprint($tr->getFilters());
        //endregion

//        die(lgShowAlert($no));

        $signNumbers = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($no)->result();
//        cekKuning($this->db->last_query());
        if (sizeof($tmpSign) > 0) {
            $sCtr = 0;
            foreach ($tmpSign as $row) {

                $signNumbers[$sCtr] = "" . $row->step_number;
                $sCtr++;
            }
        }


        $rawItems = array();

        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = null;
                unset($_SESSION[$cCode]);
            }


//            $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
            //region session init
            if (!isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($_SESSION[$cCode]['main'])) {
                $_SESSION[$cCode]['main'] = array();
            }
            if (!isset($_SESSION[$cCode]['items'])) {
                $_SESSION[$cCode]['items'] = array();
            }
            //endregion

            $trID = $tmpTr[0]->transaksi_id;
            $itemLabels = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$stepNumber]) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$stepNumber] : array();
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();
            $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$stepNumber] : null;
//            $masterID = isset($tmpTr[0]->referensi_id) && $tmpTr[0]->referensi_id > 0 ? $tmpTr[0]->referensi_id : $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $afterTargetStepNum = ($currentStepNum + 1);


            //==periksa apakah ada ganjalan
            $trA = new MdlTransaksi();
            $extSteps = $trA->lookupExtSteps($masterID);
            if (sizeof($extSteps) > 0) {
                cekmerah("ada ganjalan step sebanyak " . sizeof($extSteps));
            } else {
                cekhijau("TAK ada ganjalan step");
            }
            $paySrcs = $trA->lookupPaymentSrcs($masterID, $this->jenisTr . "_");
            if (sizeof($paySrcs) > 0) {
                cekmerah("ada ganjalan paymentSrc sebanyak " . sizeof($paySrcs));
            } else {
                cekhijau("TAK ada ganjalan paymentSrc");
            }


            $allowEdit = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNumber]['allowEdit']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNumber]['allowEdit'] : false;
            $editableFields = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartEditableFields'][$stepNumber] : array();

            cekhitam("jenisTr " . $this->jenisTr);

            //region valid items
            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
//            $tr->addFilter("transaksi_id='" . $trID . "'");
            $tr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
//            $tr->addFilter("id_master='" . $masterID . "'");
//            $tr->addFilter("step_number='" . $currentStepNum . "'");
            $tr->addFilter("sub_step_number='" . $currentStepNum . "'");
            $tr->addFilter("next_substep_code='" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNumber]['target'] . "'");
            $tr->addFilter("next_substep_num='$stepNumber'");
            $tr->addFilter("valid_qty>0");

            $tmpTr = $tr->lookupJoined()->result();
            cekhitam($this->db->last_query());


            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            if (sizeof($tmpTr) > 0) {
                foreach ($tmpTr as $row) {
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    $validItems[$row->produk_id] += $row->valid_qty;

                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id] = array(
                        "id" => $row->id,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
                        "valid_qty" => $row->valid_qty,
                        "transaksi_id" => $row->transaksi_id,
                    );
                }
            }

            //endregion

//            arrprint($extractedItems);die();

            //
            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
//            //cekMerah($this->db->last_query());
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }
//            arrPrint($detailValues);
            //endregion

//            arrPrint($mainValues);die();

            //region take from registries
            //==ambil value-gate
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
//            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $tmpReg = $trr->lookupRegistries()->result();
//            $tmpReg = $tr->lookupRegistriesByMasterID($masterID)->result();
            cekKuning($this->db->last_query());
            $main = array();

            $items = array();
            $items2 = array();
            $items2_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
//            $mainApplets = array();
            $mainElements = array();
            $mainInputs = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {
                        case "main"://
                            $main = $main + unserialize(base64_decode($row->values));
                            break;
                        case "items"://
                            $items = $items + unserialize(base64_decode($row->values));
                            break;
                        case "items2"://
                            $items2 = $items2 + unserialize(base64_decode($row->values));
                            break;
                        case "rsltItems"://
                            $rsltItems = $rsltItems + unserialize(base64_decode($row->values));
                            break;
                        case "rsltItems2"://
                            $rsltItems2 = $rsltItems2 + unserialize(base64_decode($row->values));
                            break;
                        case "items2_sum"://
                            $items2_sum = $items2_sum + unserialize(base64_decode($row->values));
                            break;
                        case "out_master"://
                            $masterGates = $masterGates + unserialize(base64_decode($row->values));
                            break;
                        case "out_detail"://
                            $childGates = $childGates + unserialize(base64_decode($row->values));
                            break;
                        case "out_detail2"://
                            $childGates2 = $childGates2 + unserialize(base64_decode($row->values));
                            break;
                        case "out_detail2_sum"://
                            $childGates2_sum = $childGates2_sum + unserialize(base64_decode($row->values));
                            break;
                        case "out_detail_rsltItems"://
                            $childGatesRsltItems = $childGatesRsltItems + unserialize(base64_decode($row->values));
                            break;
                        case "out_detail_rsltItems2"://
                            $childGatesRsltItems2 = $childGatesRsltItems2 + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master"://
                            $masterTableInParams = $masterTableInParams + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = $childTableInParams + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_rsltItems"://
                            $childTableInParamsRsltItems = $childTableInParamsRsltItems + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_rsltItems2"://
                            $childTableInParamsRsltItems2 = $childTableInParamsRsltItems2 + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = $masterTableInValueParams + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = $childTableInValueParams + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values_rsltItems"://
                            $childTableInValueParamsRsltItems = $childTableInValueParamsRsltItems + unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values_rsltItems2"://
                            $childTableInValueParamsRsltItems2 = $childTableInValueParamsRsltItems2 + unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = $masterAddValues + unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = $masterAddFields + unserialize(base64_decode($row->values));
                            break;
//                        case "main_applets"://
//                            $mainApplets = unserialize(base64_decode($row->values));
//                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                        case "main_inputs"://
                            $mainInputs = unserialize(base64_decode($row->values));
                            break;
                    }
                }

            } else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion


            $masterReplacers = array(
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $masterTableInParams['jenis_top'],
                "harga" => 0,
                "masterID" => $masterID,
            );
            foreach ($masterReplacers as $key => $src) {
//                $main[$key] = $src;
                $mainValues[$key] = $src;
                $masterGates[$key] = $src;
            }


            //==revalidate items
            $this->load->library("FieldCalculator");
            $cal = new FieldCalculator();

            cekKuning("valid items");
            arrprint($validItems);


            if (sizeof($items) > 0) {
                foreach ($items as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if (array_key_exists($id, $validItems)) {
                        $items[$id]['jml'] = $validItems[$id];
                        $items[$id]['max_jml'] = $validItems[$id];
                        if (sizeof($editableFields) > 0) {
                            foreach ($editableFields as $fName) {
                                $items[$id]["max_$fName"] = isset($iSpec[$fName]) ? $iSpec[$fName] : 0;
                            }
                        }


                        if ($subAmountConfig != null) {
                            $tmpEx = $cal->multiExplode($subAmountConfig);
                            if (sizeof($tmpEx) > 1) {
//                            echo lgShowAlert("menghitung subtotal pakai rumus $subAmountConfig di step ke # $stepNumber");
                                $newSrc = $subAmountConfig;
                                foreach ($tmpEx as $key2 => $val2) {
                                    if (isset($items[$id][$val2])) {
                                        $newSrc = str_replace($val2, $items[$id][$val2], $newSrc);

                                    } else {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $items[$val2], $newSrc);

                                        } else {
                                            $newSrc = str_replace($val2, "0", $newSrc);

                                        }
                                    }


                                }
                                $subtotal = $cal->calculate($newSrc);


                            } else {
//                            echo lgShowAlert("memasang subtotal dari $subAmountConfig");
                                $subtotal = $items[$id][$subAmountConfig];

                            }
                        } else {
//                        echo lgShowAlert("tidak mengapa-apakan subtotal");
                            $subtotal = 0;

                        }
//                    echo lgShowAlert("isi subtotal: $subtotal");
                        $items[$id]['subtotal'] = $subtotal;
                    } else {
                        unset($items[$id]);
                        unset($childGates[$id]);
                        unset($childTableInParams[$id]);
                        unset($childTableInValueParams[$id]);

                    }


                }
            }

            cekKuning("items");
            arrprint($items);


//            die();

            //region session-swapper
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,
                "out_master" => $masterGates,
                "out_detail" => $childGates,
                "out_detail_rsltItems" => $childGatesRsltItems,
                "out_detail_rsltItems2" => $childGatesRsltItems2,
                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                //        ""=>$childAddValues ,
                "main_add_fields" => $masterAddFields,
                //                "main_applets"          => $mainApplets,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                //
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
            );
//            cekKuning("swapping $cCode");
            foreach ($swappers as $targetVar => $src) {
                $_SESSION[$cCode][$targetVar] = $src;

            }
            //endregion
//cekHitam("cetak session :::: ");
//arrPrint($_SESSION[$cCode]['out_detail']);


            //==init replacer


            //==recover nilai HARGA master
            $_SESSION[$cCode]['main']['harga'] = 0;
            $_SESSION[$cCode]['out_master']['harga'] = 0;
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                }
            }


//            die();
            $this->load->helper("he_value_builder");
            resetValues($this->jenisTr);

            fillValues($this->jenisTr, $this->uri->segment(5), $this->uri->segment(4));
//            die();


            $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
//        echo "</script>";

            echo "<html>";
            echo "<head>";
            echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";

            echo "</body>";
            echo "</html>";
        } else {
            die(lgShowAlert("No such transaction. You may want to refresh the browser to re-fetch actual content."));
        }


    }

    public function followupPreview()
    {
//
        //arrprint($_GET);
        $no = rtrim($this->uri->segment(3), "-");
//        $no = $this->uri->segment(3);
        $targetStepNum = $this->uri->segment(4);
        $currentStepNum = $this->uri->segment(5);
        $afterTargetStepNum = ($targetStepNum + 1);

        $rawPrevURL = blobEncode(current_url());
        $rawBuilderURL = $_GET['rawBuilderURL'];

        //
        //region read items from existing model
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
//        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
//        $tr->addFilter("id_top='" . $no . "'");
//        $tr->addFilter("transaksi_id='" . $no . "'");
        $tr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
//        $tr->addFilter("id_master='" . $no . "'");
//        $tr->addFilter("step_number='" . $currentStepNum . "'");
        $tr->addFilter("sub_step_number='" . $currentStepNum . "'");

        $tmpTr = $tr->lookupJoined()->result();
//        cekKuning($this->db->last_query());die();
        //endregion


        $signNumbers = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($no)->result();
//        cekKuning($this->db->last_query());die();
        if (sizeof($tmpSign) > 0) {
            $sCtr = 0;
            foreach ($tmpSign as $row) {

                $signNumbers[$sCtr] = "" . $row->step_number;
                $sCtr++;
            }
        }


        $rawItems = array();
        //region detail elements of preview
        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;


            $cCode = "_TR_" . $this->jenisTr;


            //region session init
            if (!isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($_SESSION[$cCode]['main'])) {
                $_SESSION[$cCode]['main'] = array();
            }
            if (!isset($_SESSION[$cCode]['items'])) {
                $_SESSION[$cCode]['items'] = array();
            }
            //endregion

            $trID = $tmpTr[0]->transaksi_id;
            $itemLabels = isset($this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$targetStepNum]) ? $this->config->item('heTransaksi_layout')[$this->jenisTr]['receiptDetailFields'][$targetStepNum] : array();
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$targetStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$targetStepNum] : array();
            $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$targetStepNum]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$targetStepNum] : null;
//            $masterID = isset($tmpTr[0]->referensi_id) && $tmpTr[0]->referensi_id > 0 ? $tmpTr[0]->referensi_id : $tmpTr[0]->transaksi_id;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $afterTargetStepNum = ($currentStepNum + 1);


            //
            //region tabel2 tarikan untuk kolom2 nilai (hpp, ppn, dll)
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
//            //cekMerah($this->db->last_query());
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }
//            arrPrint($detailValues);
            //endregion

//            arrPrint($mainValues);die();

            //region take from registries
            //==ambil value-gate
//            $tmpReg = $tr->lookupRegistriesByMasterID($trID)->result();
            $tmpReg = $tr->lookupRegistriesByMasterID($masterID)->result();
//            cekKuning($this->db->last_query());
            $main = array();
            $items = array();
            $masterGates = array();
            $childGates = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $masterAddValues = array();
            $masterAddFields = array();
//            $mainApplets = array();
            $mainElements = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    switch ($row->param) {
                        case "main"://
                            $main = unserialize(base64_decode($row->values));
                            break;
                        case "items"://
                            $items = unserialize(base64_decode($row->values));
                            break;
                        case "out_master"://
                            $masterGates = unserialize(base64_decode($row->values));
                            break;
                        case "out_detail"://
                            $childGates = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master"://
                            $masterTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail"://
                            $childTableInParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_master_values"://
                            $masterTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "tableIn_detail_values"://
                            $childTableInValueParams = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_values"://
                            $masterAddValues = unserialize(base64_decode($row->values));
                            break;
                        case "main_add_fields"://
                            $masterAddFields = unserialize(base64_decode($row->values));
                            break;
//                        case "main_applets"://
//                            $mainApplets = unserialize(base64_decode($row->values));
//                            break;
                        case "main_elements"://
                            $mainElements = unserialize(base64_decode($row->values));
                            break;
                    }
                }

            } else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion


            foreach ($tmpTr as $row) {
                $id = $row->produk_id;
                $tmp = array();
                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $val) {
                        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$row->produk_id][$key])) {
                            $fieldValue = $_SESSION[$cCode]['tableIn_detail_values'][$row->produk_id][$key];
                        } else {
                            if (isset($row->$key)) {
                                $fieldValue = $row->$key;
                            }
                        }
                        $tmp[$key] = $fieldValue;
                    }
                }

                //region calculate subtotal
//                arrPrint($childGates[$id]);
                //===perhitungan subtotal
                $this->load->library("FieldCalculator");
                $cal = new FieldCalculator();


                if ($subAmountConfig != null) {
//                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
//                    $subAmountConfig = str_replace("harga", "produk_ord_hrg", $subAmountConfig);
//                    $subAmountConfig = str_replace("produk_ord_produk_ord_jml", "produk_ord_jml", $subAmountConfig);
                    $tmpEx = $cal->multiExplode($subAmountConfig);
                    if (sizeof($tmpEx) > 1) {
                        $newSrc = $subAmountConfig;
                        foreach ($tmpEx as $key2 => $val2) {
                            if (isset($childGates[$id][$val2])) {
                                $newSrc = str_replace($val2, $childGates[$id][$val2], $newSrc);
//                                //cekKuning("$val2 direplace dengan " . $childGates[$id][$val2]);
                            } else {
                                if (isset($tmp[$val2])) {
                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
//                                    //cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                } else {
                                    $newSrc = str_replace($val2, "0", $newSrc);
//                                    //cekKuning("$val2 direplace dengan NOL");
                                }
                            }


                        }
                        $subtotal = $cal->calculate($newSrc);
//                        //cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                    } else {
                        $subtotal = 0;
//                        //cekHijau("subtotal dari perhitungan yang gak ada");
                    }
                } else {
                    $subtotal = 0;
//                    //cekHijau("subtotal NOL");
                }
                $tmp["subtotal"] = $subtotal;
                //endregion

                $rawItems[$row->produk_id] = $tmp;
            }
//            arrprint($rawItems);die();

        } else {

            $errMsg = "the entry you are trying to access does not exist.<br>";
            $errMsg .= "you may try to refresh the browser by pressing F5 button on your keyboard.<br>";
            $errMsg .= "if this error re-occurs, please contact system developer.<br>";


            echo "<script>top.BootstrapDialog.closeAll();</script>";
            die(lgShowAlert($errMsg));
        }
        //endregion


        $items = array();
        $items2 = array();

        $jenisTr = $this->jenisTr;


        //region deteksi tombol2 followup

//        echo $currentStepNum . " vs " . $afterTargetStepNum . " | $currentStepNum vs $targetStepNum  <br>";


        $allowFollowup = false;
        $allowRevert = false;
        $revertLabel = "can not revert";
        $revertTarget = "";
        $acceptLabel = "can not accept";
        $acceptTarget = "";
        $rejectionLabel = "can not reject";
        $rejectionTarget = "";

        $currentStepIndex = array_search($currentStepNum, $signNumbers, true);

//        arrprint($signNumbers);
//        cekkuning($currentStepIndex."/".$currentStepNum."/".$targetStepNum);

        if (sizeof($signNumbers) > 0) {

            if ($currentStepIndex > 0) {
                $beforeCurrentIndex = ($currentStepIndex - 1);
                $beforeCurrentStep = $signNumbers[$beforeCurrentIndex];
            } else {

                $beforeCurrentStep = 0;
            }

            $masterRevertStep = $beforeCurrentStep;

//            die($jenisTr);
            //region check if i may revert back from current state
            if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['userGroup'], $this->session->login['membership'])) {
                $mComponents = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$currentStepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$currentStepNum]['master'] : array();

                if (sizeof($mComponents) > 0) {
                    $comNames = array();
                    foreach ($mComponents as $cSpec) {
                        $comNames[] = $cSpec['comName'];
                    }
                    if (in_array("Jurnal", $comNames)) {
                        //==tidak bisa di-revert
                    } else {
                        $allowRevert = true;
//                        $revertLabel = $masterRevertStep . "undo " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['label'];
                        $revertLabel = "undo";
                        $childRevertStep = -($currentStepNum);
                        $revertTarget = base_url() . $this->uri->segment(1) . "/doRevert/$no/$jenisTr/$masterRevertStep/$childRevertStep/$currentStepNum";

                    }
                } else {
                    $allowRevert = true;
//                    $revertLabel = $masterRevertStep . "undo " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$currentStepNum]['label'];
                    $revertLabel = "undo";
                    $childRevertStep = -($currentStepNum);
                    $revertTarget = base_url() . $this->uri->segment(1) . "/doRevert/$no/$jenisTr/$masterRevertStep/$childRevertStep/$currentStepNum";
                }

            }
            //endregion
        }


        //region check if i may approve or unapprove current transaction

//        if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$afterTargetStepNum])) {
//            if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$afterTargetStepNum]['userGroup'], $this->session->login['membership'])) {

        if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$targetStepNum])) {
            if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$targetStepNum]['userGroup'], $this->session->login['membership'])) {
                $allowFollowup = true;

                $acceptLabel = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$targetStepNum]['actionLabel'];
                $acceptTarget = base_url() . $this->uri->segment(1) . "/doFollowup/$no/$targetStepNum/$currentStepNum";

                if (isset($masterRevertStep)) {

                    $mComponents = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$currentStepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$currentStepNum]['master'] : array();

                    if (sizeof($mComponents) > 0) {
                        $comNames = array();
                        foreach ($mComponents as $cSpec) {
                            $comNames[] = $cSpec['comName'];
                        }
                        if (in_array("Jurnal", $comNames)) {
                            //==tidak bisa dicancel
                        } else {
                            $childRevertStep = -($currentStepNum);
//                            $rejectionLabel = $masterRevertStep . "reject " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$targetStepNum]['label'];
                            $rejectionLabel = "reject";
                            $rejectionTarget = base_url() . $this->uri->segment(1) . "/dontFollowup/$no/$jenisTr/$masterRevertStep/$childRevertStep/$currentStepNum";

                        }
                    } else {
                        $childRevertStep = -($currentStepNum);
//                        $rejectionLabel = $masterRevertStep . "reject " . $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$targetStepNum]['label'];
                        $rejectionLabel = "reject";
                        $rejectionTarget = base_url() . $this->uri->segment(1) . "/dontFollowup/$no/$jenisTr/$masterRevertStep/$childRevertStep/$currentStepNum";
                    }


                }

            }
        }
        //endregion
        //endregion


        //
        //region prevalidator
        $items = $rawItems;
        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preValidator'][$targetStepNum])) {
            $procList = $this->config->item('heTransaksi_core')[$this->jenisTr]['preValidator'][$targetStepNum];

            if (sizeof($procList) > 0) {
//                //cekBiru("now processing prevalidator..<br>");
                foreach ($procList as $procName) {
                    $mdlProcName = "PreValidator" . $procName;
                    $this->load->model($mdlProcName);
//                        cekHijau("preval name: $mdlProcName<br>");
                    if (isset($childGates) && sizeof($childGates) > 0) {
                        $prc = new $mdlProcName();
                        $requiredParams = $prc->getRequiredParams();
                        if (sizeof($requiredParams) > 0) {
                            $sentParams = array();
                            foreach ($childGates as $odSpec) {
                                $tmp = array();
                                foreach ($requiredParams as $key) {
                                    $tmp[$key] = $odSpec[$key];
                                }

                                $sentParams[] = $tmp;
                            }

                            $prc->pair($masterID, $sentParams);
                            $gotParams = $prc->exec();
//                                cekHijau("gotParams");
//                                arrprint($gotParams);
//                            print_r($gotParams);die();
                            if (isset($gotParams['items2']) && sizeof($gotParams['items2']) > 0) {

                                $items2 = $rawItems;
                                foreach ($gotParams['items2'] as $id => $iSpec) {
//										$id=$iSpec['id'];
                                    if (isset($_SESSION[$cCode]['items'][$id])) {

                                        $items2[$id]['produk_ord_jml'] = $iSpec['produk_ord_jml'];
                                        $items[$id]['produk_ord_jml'] -= $iSpec['produk_ord_jml'];
                                        $_SESSION[$cCode]['items'][$id]['jml'] -= $iSpec['produk_ord_jml'];
                                        if ($items[$id]['produk_ord_jml'] < 1) {
                                            unset($items[$id]);
                                        }
                                        if ($_SESSION[$cCode]['items'][$id]['jml'] < 1) {
                                            unset($_SESSION[$cCode]['items'][$id]);
                                        }
                                    }
                                }
                            }

                        } else {
                            die("preval $procName does not have requiredParams!");
                        }

                    } else {
                        die("out_detail contains nothing!. skipping preprocessor");
                    }
                }
            }


            //<editor-fold desc="re-build values">


            //</editor-fold>

        } else {
//                echo("no preval defined. skipping preval..<br>");
        }

        //endregion

//        print_r($items);die();

        //
        //region preview bottom elements

        $saveWarning = "";
        $buttonLabel = $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][$targetStepNum]['actionLabel'];
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$afterTargetStepNum])) {
            $saveWarning .= "clicking <strong>$buttonLabel</strong> button will make transaction state to: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['stateLabel'] . "</strong>";
            $saveWarning .= "<br>It would need to be authorized by <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$afterTargetStepNum]['userGroup'] . "</strong>";
        } else {
            $saveWarning .= "clicking <strong>$buttonLabel</strong> button will instantly make transaction state to <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['stateLabel'] . "</strong>";
        }

        //endregion

        //region warn if irreverseble
        $mComponents = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$targetStepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$targetStepNum]['master'] : array();
        if (sizeof($mComponents) > 0) {
            $comNames = array();
            $reks = array();
            foreach ($mComponents as $cSpec) {
                $comNames[] = $cSpec['comName'];
                if ($cSpec['comName'] == "Jurnal") {
                    if (isset($cSpec['loop']) && sizeof($cSpec['loop']) > 0) {
                        foreach ($cSpec['loop'] as $rek => $v) {
                            $reks[$rek] = $rek;
                        }
                    }
                }
            }
            if (in_array("Jurnal", $comNames)) {
                //==tidak bisa di-revert
                $saveWarning .= "<br><span class='text-danger'>starting from this point, you can not undo the change made on this entry.</span>";
                if (sizeof($reks) > 0) {
                    $saveWarning .= "<br><h6><span class='text-blue'>these accounts will be affected at journal: <strong>" . implode(", ", $reks) . ".</strong></span></h6>";
                }
            }
        }
        //endregion

        //
        //region replace main labels with properties from future/next step
        $mainProp = $tmpTr[0];
//        $mainProp = (object)$_SESSION[$cCode]['main'];
        $mainPropReplacers = array(//===replace khusus kebutuhan preview next step
            "jenis_label" => $tmpTr[0]->next_step_label,
            "nomer" => "(to be generated)",
            "dtime" => date("Y-m-d H:i:s"),
        );
        foreach ($mainPropReplacers as $key => $val) {
            $mainProp->$key = $val;
        }
        //endregion
        //
        //region prepare params for viewer

        $editableAddVals = array();
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) && sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] as $exName => $exSpec) {
                if ($exSpec['useAt'] == $targetStepNum) {
                    $editableAddVals[] = $exName;
                }
            }
        } else {

        }


        $editableElements = array();
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
//            $appletConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['applets']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['applets'] : array();
        if (sizeof($_SESSION[$cCode]['main_elements']) > 0) {

            if (sizeof($elementConfigs) > 0) {
                foreach ($elementConfigs as $aKey => $aSpec) {
                    if (array_key_exists($aKey, $mainElements) && in_array($targetStepNum, $aSpec['editPoints'])) {
                        $editableElements[] = $aKey;
                    }
                }
            }

        }

        //region elements editor

        $elStr = array();
        $elements = array();

        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : "";

                        $elStr[$eName] = "";
                        $this->load->model("Mdls/" . $amdlName);
                        $labelSrc = $eSpec['labelSrc'];
                        $oo = new $amdlName();
                        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);
                        $realFilter = "";
                        if (strlen($aFilter) > 2) {
                            $exFilter = explode("=", $aFilter);
                            if (sizeof($exFilter) > 1) {
                                if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                    $realFilter = $exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'";
                                    $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                }

                            }
                        }
                        $addClick = "";
                        $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array(
                            "viewers" => array(),
                            "creators" => array(),
                            "creatorAdmins" => array(),
                            "updaters" => array(),
                            "updaterAdmins" => array(),
                            "deleters" => array(),
                            "deleterAdmins" => array(),
                            "historyViewers" => array(),
                        );
                        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                        if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
                            if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                                $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                                $addStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                            }
                        }
                        if (strlen($realFilter) > 0) {
                            $oo->addFilter($realFilter);
                        }
                        $tmpo = $oo->lookupAll()->result();
                        $elPair[$amdlName] = array();
                        $selectorTarget = base_url() . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";
//                        $elStr[$eName] .= "<div class='box'>";
                        $elStr[$eName] .= "<div class='box-body'>";
                        $elStr[$eName] .= "<select class='form-control' onchange=\"document.getElementById('result').src='$selectorTarget';\">";
                        $elStr[$eName] .= "<option value=''>-select-</option>";
                        if (sizeof($tmpo) > 0) {
                            foreach ($tmpo as $row) {
                                $elPair[$amdlName][$row->id] = $row->$labelSrc;
                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->id ? "selected" : "";
                                $elStr[$eName] .= "<option value='" . $row->id . "' $selected>" . $row->$labelSrc . "</option>";
                            }
                        }
                        $elStr[$eName] .= "</select>";
                        $elStr[$eName] .= "</div class='box-header'>";

                        $defKey = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : 0;
                        $defValue = "";
                        if (isset($_SESSION[$cCode]['main_elements'][$eName]['key']) && $_SESSION[$cCode]['main_elements'][$eName]['contents']) {
                            if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
                                $defValue .= "<table class='table table-condensed no-padding' style='padding:0px;margin:0px;'>";
                                $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['main_elements'][$eName]['contents']));
                                foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                    $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                    $defValue .= "<tr>";
                                    $defValue .= "<td align='left'>$label";
                                    $defValue .= "</td>";
                                    $defValue .= "<td align='left'>" . $fieldLabel;
                                    $defValue .= "</td>";
                                    $defValue .= "</tr>";
                                }
                                $defValue .= "</table>";
                            }
                        }


                        if ($defKey > 0) {
                            if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                    $editClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $editLink . "'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        }
                                        );";

                                    $editStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                }
                            }
                        }

                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                        $elStr[$eName] .= "</div id='el$amdlName'>";
                        $elStr[$eName] .= "<div class='box-footer'>";

                        $elStr[$eName] .= "<span class='pull-right'>$editStr $addStr</span>";
                        $elStr[$eName] .= "</div class='box-footer'>";

                        $elements[$eName] = array(
                            "mdlName" => $eSpec['mdlName'],
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                        );


                        break;
                    case "dataField":
                        $elStr[$eName] = "";
                        $defaultValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";
                        $selectorTarget = "'" . base_url() . get_class($this) . "/recordFieldElement/" . $this->jenisTr . "/$eName/$amdlName/?val='+this.value";
//                        $elStr[$eName] .="<div class='box'>";

                        $elStr[$eName] .= "<div class='box-body'>";
                        switch ($eSpec['inputType']) {
                            case "text":
                                $elStr[$eName] .= "<input type=text class='form-control' value='$defaultValue' onblur=\"document.getElementById('result').src=$selectorTarget;\">";
                                break;
                            case "date":
                                $elStr[$eName] .= "<input type=date class='form-control' value='$defaultValue' onblur=\"document.getElementById('result').src=$selectorTarget;\">";
                                break;
                        }
                        $elStr[$eName] .= "</div class='box-body'>";

                        $elements[] = array(
                            "mdlName" => null,
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                        );
                        break;
                }
            }
        }

        //endregion


        $stepLabels = array();
        foreach ($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'] as $num => $sSpec) {
            $stepLabels[$num] = $sSpec['label'];
        }

        $tmpTableIn_master = isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array();
        $tmpTableIn_masterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        $main = array_merge(array_filter($main), array_filter($tmpTableIn_master), array_filter($tmpTableIn_masterValues));
        $mainAddValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
            $mainAddValues = array_merge(array_filter($mainAddValues), array_filter($_SESSION[$cCode]['main_add_values']));
        }


        //==iterasi untuk memasukkan element relatif
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
//            cekbiru("hendak memeriksa relative impacts");
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
//                cekbiru("memeriksa $eName:");
                if (array_key_exists($eName, $relElementConfigs)) {
//                    cekhijau("$eName memiliki relative impacts");
                    $currentValue = "";
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
//                        cekhijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
//                            cekmerah("memeriksa $eName, $currentValue");
//                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
//                                $rcCtr++;
                            }
                        }
                    } else {
//                        cekmerah("TIDAK memenuhi syarat");
                    }
                }
            }
        }


        //==ini dua benda ini dibikin ulang di sini karena nantinya harus selalu refresh tanpa memanggil PrePreview lagi
        $trA = new MdlTransaksi();
        $extSteps = $trA->lookupExtSteps($masterID);
        $paySrcs = $trA->lookupPaymentSrcs($masterID, $this->jenisTr . "_");


        $_SESSION[$cCode]['extSteps'] = $extSteps;
        $_SESSION[$cCode]['paySrcs'] = $paySrcs;

        //create followup buttons for relative inputs
        //region buttons for relative inputs
        $extBtns = array();
        $extNewBtns = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($_SESSION[$cCode]['extSteps']) && sizeof($_SESSION[$cCode]['extSteps']) > 0) {
            foreach ($_SESSION[$cCode]['extSteps'] as $xSpec) {
                if (in_array($xSpec['groupID'], $mems)) {
                    $actionTarget = "
                                    top.BootstrapDialog.show(
                                   {
                                       title:'review " . $xSpec['label'] . "',
                                       message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/previewValue/" . $this->jenisTr . "/$masterID/" . $xSpec['id'] . "/" . $this->uri->segment(5) . "?rawPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
                    $extBtns[$xSpec['key']] = "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"$actionTarget\"><span class='glyphicon glyphicon-triangle-right'></span> review " . $xSpec['label'] . "</a>";
                } else {
                    $extBtns[$xSpec['key']] = "<a class='btn btn-default text-muted'><i>unreviewable " . $xSpec['label'] . "</i></a>";
                }
            }
        }
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
                if (array_key_exists($eName, $relOptionConfigs)) {
//                    cekhijau("$eName terdaftar pada relInputs");
                    if (isset($relOptionConfigs[$eName][$currentValue]) && sizeof($relOptionConfigs[$eName][$currentValue]) > 0) {
                        foreach ($relOptionConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                            if (isset($oValSpec['addPoints']) && in_array($targetStepNum, $oValSpec['addPoints'])) {
                                if (in_array($oValSpec['auth']['groupID'], $mems)) {
                                    $actionTarget = "
                                    top.BootstrapDialog.show(
                                   {
                                       title:'add " . $oValSpec['label'] . "',
                                       message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/addValue/" . $this->jenisTr . "/$masterID/" . "0" . "/" . $this->uri->segment(5) . "?rawPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
                                    $extNewBtns[$oValueName] = "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"$actionTarget\"><span class='glyphicon glyphicon-triangle-right'></span> add " . $oValSpec['label'] . "</a>";
                                } else {
//                                    cekmerah("you are not allowed");
                                }
                            } else {
//                                cekhijau("$oValueName TIDAK memenuhi syarat");
                            }
                        }
                    }
                }
            }
        }
        //endregion

        //create followup buttons for paymentSrc-related
        //region buttons for paymentSrc-related
        $payBtns = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($_SESSION[$cCode]['paySrcs']) && sizeof($_SESSION[$cCode]['paySrcs']) > 0) {
            foreach ($_SESSION[$cCode]['paySrcs'] as $xSpec) {
                if ($xSpec['sisa'] > 0) {

//                arrprint($xSpec);
                    $xSpec['groupID'] = $this->config->item("heTransaksi_ui")[$xSpec['targetJenis']]['steps'][1]['userGroup'];
                    if (in_array($xSpec['groupID'], $mems)) {
                        $actionTarget = "
                                    top.BootstrapDialog.show(
                                   {
                                       title:'do " . $xSpec['label'] . "',
                                       message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/selectPaymentSrc/" . $xSpec['targetJenis'] . "/" . $xSpec['extID'] . "/$masterID?rawPrev=$rawPrevURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
//                        $payBtns[$xSpec['targetJenis']] = "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"$actionTarget\"><span class='glyphicon glyphicon-triangle-right'></span> do " . $xSpec['label'] . "</a>";
                    } else {
//                        $payBtns[$xSpec['targetJenis']] = "<a class='btn btn-default text-muted'><i>" . $xSpec['label'] . "</i></a>";
                    }
                }
            }
        }
        //endregion

        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "subTitle" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "mainLabels" => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptMainFields"],
            "main" => $mainProp,
            //            "mainValues"        => $mainValues,
            "mainValues" => $_SESSION[$cCode]['main'],
            //            "detailValues"      => $detailValues,
            "detailValues" => $_SESSION[$cCode]['tableIn_detail_values'],
            //            "itemLabels" => $itemLabels,
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "itemLabels2" => array
            (
                "produk_nama" => "item name",
                "produk_ord_jml" => "qty",
                "satuan" => "satuan",

            ),
            "items" => $_SESSION[$cCode]['items'],
            "items2" => $items2,
            "buttonLabel" => $buttonLabel,
            "saveWarning" => $saveWarning,
            "sumRows" => $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$targetStepNum],
            "extValueLabels" => isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] : array(),
            "extEditableFields" => $editableAddVals,
            //            "mainAddValues" => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
            //            "mainAddFields" => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
            "mainAddValues" => $mainAddValues,
            "mainAddFields" => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
            //            "mainApplets"       => $mainApplets,
            //            "editableApplets"   => $editableApplets,
            "mainElements" => $_SESSION[$cCode]['main_elements'],
            "elements" => $elements,
            "editableElements" => $editableElements,
            "elementConfig" => $elementConfigs,
            "mainInputs" => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
            "elementEditTarget" => base_url() . "_followupLiveEdit/selectElement/" . $this->jenisTr . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/",
            //            "actionTarget" => base_url() . $this->uri->segment(1) . "/doFollowup/$no/$targetStepNum",
            "actionTarget" => base_url() . $this->uri->segment(1) . "/doFollowup/$no/$targetStepNum/$currentStepNum",
            "paymentMethod" => isset($tmpTr[0]->pembayaran) ? $tmpTr[0]->pembayaran : "",
            "grandTotal" => isset($_SESSION[$cCode]['out_master']['grand_total']) ? $_SESSION[$cCode]['out_master']['grand_total'] : 0,
            "description" => isset($masterGates['description']) ? $masterGates['description'] : "",
            "revertLabel" => $revertLabel,
            "revertTarget" => $revertTarget,
            "acceptLabel" => $acceptLabel,
            "acceptTarget" => $acceptTarget,
            "rejectionLabel" => $rejectionLabel,
            "rejectionTarget" => $rejectionTarget,
            "allowEdit" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['allowEdit']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['allowEdit'] : false,
            "allowIncrement" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['allowIncrement']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$targetStepNum]['allowIncrement'] : false,
            "editableFields" => isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartEditableFields'][$targetStepNum]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['shoppingCartEditableFields'][$targetStepNum] : array(),
            "stepLabels" => $stepLabels,
            "currentStep" => $targetStepNum,
            "extSteps" => isset($_SESSION[$cCode]['extSteps']) ? $_SESSION[$cCode]['extSteps'] : array(),
            "paySrcs" => isset($_SESSION[$cCode]['paySrcs']) ? $_SESSION[$cCode]['paySrcs'] : array(),
            "extBtns" => $extBtns,
            "extNewBtns" => $extNewBtns,
            "payBtns" => $payBtns,
            //==================liveEdit========================
            "followupSegments" => array(
                $this->uri->segment(3),
                $this->uri->segment(4),
                $this->uri->segment(5),
            ),
            "removeItemTarget" => base_url() . "_followupLiveEdit/removeItem/" . $this->jenisTr . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/",
            "updateItemFieldTarget" => base_url() . "_followupLiveEdit/updateItemField/" . $this->jenisTr . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/",
        );


        if (isset($_SESSION[$cCode]['main'])) {
            $data['pihakID'] = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0;
            $data['pihakName'] = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
        }
        //endregion


        $this->load->view("transaksi", $data);

    }

    public function preview()
    {

        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
        $itemLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartFields'][$stepNumber] : array();
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();

        $appletConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['applets']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['applets'] : array();
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
        $noteEnabled = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteEnabled']) && $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;

        $inputLabels = array();
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        //
        //region lookup items from shopping cart sessions

//        cekbiru($rawPrevURL);

        $main = array();
        $items = array();
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $iSpec) {
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => $iSpec['satuan'],
                        "jml" => $iSpec['jml'],

                    );
                    if (sizeof($itemNumLabels) > 0) {
                        foreach ($itemNumLabels as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if (!isset($main[$key])) {
                                $main[$key] = 0;
                            }
                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;

                        }
                    }
                    if ($noteEnabled) {
                        if (isset($iSpec['note'])) {
                            $tmp['note'] = $iSpec['note'];
                        }
                    }
                    $tmp['subtotal'] = $iSpec['subtotal'];


                    $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                    $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];

                    $items[] = $tmp;
                }
            }
        }
        //endregion

        //
        //region labels for preview's bottom elements
        $jenisTr = $this->jenisTr;
        $buttonLabel = $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['actionLabel'];
        if (sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']) > 1) {
            $saveWarning = "clicking <strong>$buttonLabel</strong> button will make transaction state to: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['stateLabel'] . "</strong>";
            $saveWarning .= "<br>It would need to be authorized by <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][2]['userGroup'] . "</strong>";
        } else {
            $saveWarning = "clicking <strong>$buttonLabel</strong> button will instantly make transaction state to <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['stateLabel'] . "</strong>";
        }
        //endregion
//        arrPrint($main);die();

        $stepLabels = array();
        foreach ($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'] as $num => $sSpec) {
            $stepLabels[$num] = $sSpec['label'];
        }

        //
        //region prepare params to viewer

        $tmpTableIn_master = isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array();
        $tmpTableIn_masterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        $main = array_merge(array_filter($main), array_filter($tmpTableIn_master), array_filter($tmpTableIn_masterValues));
        $mainAddValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
            $mainAddValues = array_merge(array_filter($mainAddValues), array_filter($_SESSION[$cCode]['main_add_values']));
        }


        //==iterasi untuk memasukkan element relatif
        if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
//            cekbiru("hendak memeriksa relative impacts");
            foreach ($_SESSION[$cCode]['main_elements'] as $eName => $eSpec) {
//                cekbiru("memeriksa $eName:");
                if (array_key_exists($eName, $relElementConfigs)) {
//                    cekhijau("$eName memiliki relative impacts");
                    $currentValue = "";
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
//                        cekhijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
//                            cekmerah("memeriksa $eName, $currentValue");
//                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
//                                $rcCtr++;
                            }
                        }
                    } else {
//                        cekmerah("TIDAK memenuhi syarat");
                    }
                }

                if (array_key_exists($eName, $relOptionConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");


                    if (isset($relOptionConfigs[$eName][$currentValue])) {
                        if (sizeof($relOptionConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relOptionConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                $inputLabels[$oValueName] = $oValSpec['label'];
                            }
                        }
                    } else {
//						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                    }

                } else {
//					cekKuning("$eName TIDAK terdaftar pada relInputs");
                }
            }
        }

//		arrprint($inputLabels);die();
        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "subTitle" => $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "pihakLabel" => $this->config->item("heTransaksi_ui")[$jenisTr]["pihakLabel"],
            "itemLabels" => $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount"),
            "noteEnabled" => $noteEnabled,
            "main" => $main,
            "items" => $items,
            "buttonLabel" => $buttonLabel,
            "saveWarning" => $saveWarning,
            "sumRows" => $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][$stepNumber],
            "extValueLabels" => isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] : array(),
            //            "mainAddValues" => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
            "mainAddValues" => $mainAddValues,
            "mainAddFields" => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
            //            "mainApplets"    => isset($_SESSION[$cCode]['main_applets']) ? $_SESSION[$cCode]['main_applets'] : array(),
            "mainElements" => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
            "actionTarget" => base_url() . $this->uri->segment(1) . "/save/" . $this->uri->segment(3) . "?rawPrev=$rawPrevURL",
            "appletConfig" => $appletConfigs,
            "elementConfig" => $elementConfigs,
            "mainInputs" => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
            "grandTotal" => isset($_SESSION[$cCode]['out_master']['grand_total']) ? $_SESSION[$cCode]['out_master']['grand_total'] : 0,
            "headerRows" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptMainFields'][$stepNumber]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptMainFields'][$stepNumber] : array(),
            "description" => isset($_SESSION[$cCode]['out_master']['description']) ? $_SESSION[$cCode]['out_master']['description'] : "",
            "stepLabels" => $stepLabels,
            "currentStep" => 1,

        );
//        arrprint($main);die();
//        arrprint($appletConfigs);
//        echo "<hr>";
//        arrprint($_SESSION[$cCode]['main_applets']);
//        die();
        if (isset($_SESSION[$cCode]['main'])) {
            $data['pihakID'] = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0;
            $data['pihakName'] = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
        }
        //endregion
        $this->load->view("transaksi", $data);
    }

    public function swapFrom()
    {
//        die("swapping................");
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;
        if (sizeof($_POST['trID']) > 0) {

//            arrprint($_POST['trID']);
//            foreach($_POST['trID'] as $trID){
//
//            }

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi_id in (" . implode(",", $_POST['trID']) . ")");
            $tmpTr = $tr->lookupJoined()->result();
//            cekKuning($this->db->last_query());
            if (sizeof($tmpTr) > 0) {
                $items = array();
                $trIDs = array();
                foreach ($tmpTr as $row) {
                    if (!array_key_exists($row->produk_id, $items)) {
                        $items[$row->produk_id] = 0;
                    }
                    $items[$row->produk_id] += $row->produk_ord_jml;
                    if (!in_array($row->id_master, $trIDs)) {
                        $trIDs[] = $row->id_master;
                    }
                }
                arrprint($items);
//                die();
                arrprint($trIDs);

                //==ambil value-gate
                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                $trr->addFilter("transaksi_id in (" . implode(",", $_POST['trID']) . ")");
                $trr->addFilter("param='main'");
                $tmpReg = $trr->lookupRegistries()->result();
                $swappedKeys = isset($this->config->item("heTransaksi_ui")[$jenisTr]['swappedKeys']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['swappedKeys'] : array();
                if (sizeof($tmpReg) > 0 && sizeof($swappedKeys) > 0) {
                    $oldMain = unserialize(base64_decode($tmpReg[0]->values));
                    foreach ($tmpReg as $rowr) {
                        foreach ($swappedKeys as $kr) {
                            $_SESSION[$cCode]['main'][$kr] = $oldMain[$kr];
                        }
                    }
                }


//                die();
                $targetProc = base_url() . $this->config->item("heTransaksi_ui")[$jenisTr]['itemSwapper'] . "/$jenisTr/?items=" . base64_encode(serialize($items)) . "&main=" . base64_encode(serialize($oldMain)) . "&trs=" . base64_encode(serialize($trIDs));
                echo "<script>";
                echo "top.document.getElementById('result').src='$targetProc';";
                echo "</script>";
            }


        } else {
            echo(lgShowAlert("please select at least one item"));
            die();
        }


        $no = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;


    }

    public function selectPaymentExternSrc()
    {
        $targetJenis = $this->uri->segment(3);
        //==dapatkan srcJenis
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();
        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $mainSpecs) {

                if (sizeof($mainSpecs) > 0) {
                    $sCtr = 0;
                    foreach ($mainSpecs as $sSpec) {
//						arrprint($sSpec);
                        if ($sSpec['jenisTarget'] == $targetJenis) {
                            $srcJenis = $sSpec['jenisSrc'];
                            $rawSrcJenis = $src;
                            $srcIndex = $sCtr;
//                            cekhijau($srcJenis . " memenuhi syarat");
                        } else {
                            $srcJenis = $sSpec['jenisSrc'];
//                            cekmerah($srcJenis . " TIDAK memenuhi syarat");
                        }
                        $sCtr++;
                    }
                }
            }
        }


//		die();
        //==dapatkan daftar kolom dari srcJenis
//        $historyFields = $this->config->item("heTransaksi_ui")[$srcJenis]['shortHistoryFields'];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>0");
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
        $items = array();
        $externs = array();
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $tmp = array();
//                foreach($historyFields as $fName=>$label){
//                    $tmp[$fName]=$row->$fName;
//                }
                if (!in_array($row->extern_id, $externs)) {
                    $tmp = (array)$row;
                    $tmp["link"] = base_url() . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                    $items[] = $tmp;
                    $externs[] = $row->extern_id;
                    $externName = $row->extern_nama;
                }

            }
        }


//		cekkuning("rawSrcJenis: ".$rawSrcJenis);
        $data = array(
            "mode" => $this->uri->segment(2),
            //            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$targetJenis]["label"],
            "subTitle" => "select " . $paymentSources[$rawSrcJenis][$srcIndex]['externSrc']['extLabel'] . " listed below",
            "items" => $items,
            "itemLabels" => array(
                "extern_nama" => $paymentSources[$rawSrcJenis][$srcIndex]['externSrc']['extLabel'],
                //                "nomer"=>"receipt number",
                //                "fulldate"=>"date",
                //                "tagihan"=>"due amount",
                //                "terbayar"=>"paid",
                //                "sisa"=>"due remain",
            ),
            "jenisTr" => $this->jenisTr,
        );
//        arrprint($data);
        $this->load->view("transaksi", $data);

//        die("selecting payment src...");
    }

    public function selectPaymentSrc()
    {
        $targetJenis = $this->uri->segment(3);
        $externID = $this->uri->segment(4);
        $selectedTrID = $this->uri->segment(5);
        $cCode = "_TR_" . $targetJenis;

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;


        if (!isset($_SESSION[$cCode])) {
            $_SESSION[$cCode] = array(
                "items" => array(),
                "main" => array(),
                "out_master" => array(),
            );
        }
        if (!isset($_SESSION[$cCode]['main'])) {
            $_SESSION[$cCode]['main'] = array();
        }
        if (!isset($_SESSION[$cCode]['items'])) {
            $_SESSION[$cCode]['items'] = array();
        }
        if (!isset($_SESSION[$cCode]['out_master'])) {
            $_SESSION[$cCode]['out_master'] = array();
        }


        //==dapatkan srcJenis
        $stepCode = $this->config->item("heTransaksi_ui")[$targetJenis]['steps'][1]['target'];
        $paymentSources = null != ($this->config->item("payment_source")) ? $this->config->item("payment_source") : array();

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

//		arrprint($paymentSources);die();
        if (sizeof($paymentSources) > 0) {
            foreach ($paymentSources as $src => $sSpec) {
                $payConfigs = $paymentSources[$src];
                if (sizeof($payConfigs) > 0) {
                    $sCtr = 0;
                    foreach ($payConfigs as $paymentSrcConfig) {

                        if ($paymentSrcConfig['jenisTarget'] == $targetJenis) {
                            $srcJenis = $paymentSrcConfig['jenisSrc'];
                            $rawSrcJenis = $src;
                            $srcIndex = $sCtr;
                        }
                        $sCtr++;
                    }
                }
            }
        }


        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();

        //region elements

        $elStr = array();
        $elements = array();

        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : "";

                        $elStr[$eName] = "";
                        $this->load->model("Mdls/" . $amdlName);
                        $labelSrc = $eSpec['labelSrc'];
                        $oo = new $amdlName();
                        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);
                        $realFilter = "";
                        if (strlen($aFilter) > 2) {
                            $exFilter = explode("=", $aFilter);
                            if (sizeof($exFilter) > 1) {
                                if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                    $realFilter = $exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'";
                                    $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                }

                            }
                        }
                        $addClick = "";
                        $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array(
                            "viewers" => array(),
                            "creators" => array(),
                            "creatorAdmins" => array(),
                            "updaters" => array(),
                            "updaterAdmins" => array(),
                            "deleters" => array(),
                            "deleterAdmins" => array(),
                            "historyViewers" => array(),
                        );
                        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                        if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
                            if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                                $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
                                $addStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                            }
                        }
                        if (strlen($realFilter) > 0) {
                            $oo->addFilter($realFilter);
                        }
                        $tmpo = $oo->lookupAll()->result();
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . base_url() . "_shoppingCart/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";
//                        $elStr[$eName] .= "<div class='box'>";
                        $elStr[$eName] .= "<div class='box-body'>";
                        $elStr[$eName] .= "<select class='form-control' onchange=\"document.getElementById('result').src=$selectorTarget;\">";
                        $elStr[$eName] .= "<option value=''>-select-</option>";
                        if (sizeof($tmpo) > 0) {
                            foreach ($tmpo as $row) {
                                $elPair[$amdlName][$row->id] = $row->$labelSrc;
                                $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->id ? "selected" : "";
                                $elStr[$eName] .= "<option value='" . $row->id . "' $selected>" . $row->$labelSrc . "</option>";
                            }
                        }
                        $elStr[$eName] .= "</select>";
                        $elStr[$eName] .= "</div class='box-header'>";

                        $defKey = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : 0;
                        $defValue = "";
                        if (isset($_SESSION[$cCode]['main_elements'][$eName]['key']) && $_SESSION[$cCode]['main_elements'][$eName]['contents']) {
                            if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
                                $defValue .= "<table class='table table-condensed no-padding' style='padding:0px;margin:0px;'>";
                                $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['main_elements'][$eName]['contents']));
                                foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                    $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                    $defValue .= "<tr>";
                                    $defValue .= "<td align='left'>$label";
                                    $defValue .= "</td>";
                                    $defValue .= "<td align='left'>" . $fieldLabel;
                                    $defValue .= "</td>";
                                    $defValue .= "</tr>";
                                }
                                $defValue .= "</table>";
                            }
                        }


                        if ($defKey > 0) {
                            if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                    $editClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $editLink . "'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

                                    $editStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                }
                            }
                        }

                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                        $elStr[$eName] .= "</div id='el$amdlName'>";
                        $elStr[$eName] .= "<div class='box-footer'>";

                        $elStr[$eName] .= "<span class='pull-right'>$editStr $addStr</span>";
                        $elStr[$eName] .= "</div class='box-footer'>";

                        $elements[$eName] = array(
                            "mdlName" => $eSpec['mdlName'],
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                        );


                        break;
                    case "dataField":
                        $elStr[$eName] = "";
                        $initValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";
                        $defaultValue = isset($_SESSION[$cCode]['main_elements'][$eName]['value']) ? $_SESSION[$cCode]['main_elements'][$eName]['value'] : "";
                        $selectorTarget = "'" . base_url() . "_shoppingCart/recordFieldElement/" . $this->jenisTr . "/$eName/$amdlName/?val='+this.value";
//                        $elStr[$eName] .="<div class='box'>";

                        $elStr[$eName] .= "<div class='box-body'>";
                        switch ($eSpec['inputType']) {
                            case "text":
                                $elStr[$eName] .= "<input type=text class='form-control' value='$defaultValue' oonclick=\"this.value='$defaultValue';\" onblur=\"if(this.value.length<1){this.value='$initValue'};document.getElementById('result').src=$selectorTarget;\">";
                                break;
                            case "date":
                                $elStr[$eName] .= "<input type=date class='form-control' value='$defaultValue'  oonclick=\"this.value='$defaultValue';\" onblur=\"if(this.value.length<1){this.value='$initValue'};document.getElementById('result').src=$selectorTarget;\">";
                                break;
                        }
                        $elStr[$eName] .= "</div class='box-body'>";

//                        $elStr[$eName] .="<div class='box-body'>";
//                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
//                        $elStr[$eName] .= "</div id='el$amdlName'>";
//                        $elStr[$eName] .="</div class='box-body'>";

//                        $elStr[$eName] .="<div class='box-footer'>";
//                        $elStr[$eName] .="</div class='box-footer'>";
//                        $elStr[$eName] .="</div class='box'>";

                        $elements[$eName] = array(
                            "mdlName" => null,
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                        );
                        break;
                }
            }
        }

        //endregion

        //==dapatkan daftar kolom dari srcJenis
//        $historyFields = $this->config->item("heTransaksi_ui")[$srcJenis]['shortHistoryFields'];

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("extern_id='$externID'");
        $tr->addFilter("sisa>0");

        if ($selectedTrID > 0) {
            $tr->addFilter("transaksi_id='$selectedTrID'");
        }
        $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();
//        cekKuning($this->db->last_query());
        $items = array();
        if (sizeof($tmpSrc) > 0) {
            foreach ($tmpSrc as $row) {
                $tmp = array();
//                foreach($historyFields as $fName=>$label){
//                    $tmp[$fName]=$row->$fName;
//                }
                $items[] = (array)$row;
            }
        }

        $cCode = "_TR_" . $targetJenis;

        $this->load->model("Coms/ComRekeningPembantuKas");
        $k = new ComRekeningPembantuKas();
        $tmpCurrKas = $k->fetchBalances("kas");
        $saldos = array();
        if (sizeof($tmpCurrKas) > 0) {
            foreach ($tmpCurrKas as $row) {
                $saldos[$row->extern_id] = $row->debet;
            }
        }

//        arrprint($saldos);
        //region rekening kas
        $bankAccounts = array();
        $this->load->model("Mdls/" . "MdlBankAccount");
        $rek = new MdlBankAccount();
        $tmpRek = $rek->lookupAll()->result();
        if (sizeof($tmpRek) > 0) {
            foreach ($tmpRek as $row) {
                $nameStr = isset($saldos[$row->id]) ? $row->nama . "  (current balance: " . number_format($saldos[$row->id]) . ")" : $row->nama;
                $bankAccounts[$row->id] = $nameStr;
            }
        }
        //endregion

        $data = array(
            "mode" => $this->uri->segment(2),
            //            "template" => $this->config->item("heTransaksi_ui")[$jenisTr]["template"],
            "title" => $this->config->item("heTransaksi_ui")[$targetJenis]["label"],
            "subTitle" => "select source",
            "jenisTr" => $targetJenis,
            "items" => $items,
            "itemLabels" => array(
                "extern_nama" => $paymentSources[$rawSrcJenis][$srcIndex]['externSrc']['extLabel'],
                "nomer" => "receipt number",
                "fulldate" => "date",
                "tagihan" => "due amount",
                "terbayar" => "paid",
                "sisa" => "due remain",
            ),
            "selectProcessor" => $this->config->item("heTransaksi_ui")[$targetJenis]["selectorProcessor"],
            "paymentSubtitle" => "details for " . $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'],
            "btnLabel" => "continue " . $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'],
            "ses_outMaster" => isset($_SESSION[$cCode]['out_master']) ? $_SESSION[$cCode]['out_master'] : array(),
            "ses_items" => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
            //            "actionTarget"             => "top.BootstrapDialog.show(                                   {
            //                                       title:'preview',
            //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $targetJenis . "'),
            //                                        draggable:false,
            //                                        size:top.BootstrapDialog.SIZE_WIDE,
            //                                        closable:true,
            //                                        }
            //                                        );",

            "actionTarget" => "top.document.getElementById('result').src=('" . base_url() . "Transaksi/validate/" . $targetJenis . "?rawPrev=$rawPrevURL');",
            "columnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/nilai_bayar",
            "bankColumnRecorderTarget" => base_url() . "ValueGate/recordColumn/" . $this->jenisTr . "/paymentMethod_cash",
            "bankAccounts" => $bankAccounts,
            "selectedBankID" => isset($_SESSION[$cCode]['out_master']['paymentMethod_cash']) ? $_SESSION[$cCode]['out_master']['paymentMethod_cash'] : 0,
            "elements" => $elements,
        );
//        arrprint($data);
        $this->load->view("transaksi", $data);
//        die("selecting payment src...");
    }

    public function doRevert()
    {
        $no = $this->uri->segment(3);
        $jenisTr = $this->uri->segment(4);
        $masterTargetStepNum = $this->uri->segment(5);
        $childTargetStepNum = $this->uri->segment(6);
        $stepNumCurrent = $this->uri->segment(7);
        $cCode = "_TR_" . $this->jenisTr;

        echo "no: $no<br>";
        echo "masterTargetStepNum: $masterTargetStepNum<br>";
        echo "childTargetStepNum: $childTargetStepNum<br>";
        echo "stepNumCurrent: $stepNumCurrent<br>";


        $this->db->trans_start();

        //
        //region update step2an di masternya
//        $nextMasterStep=($masterTargetStepNum+1);
        $nextMasterStep = $stepNumCurrent;
        echo "nextMasterStep: $nextMasterStep<br>";
        $nextProp = array(
            "num" => $nextMasterStep,
            "code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['target'],
            "label" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['label'],
            "groupID" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['userGroup'],
        );
        $tr = new MdlTransaksi();
        $dupState = $tr->updateData(array("id" => $no), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $masterTargetStepNum,

            )
        ) or die("Failed to update tr next-state!");
        cekHijau($this->db->last_query());
        //endregion

        //region update step yang sebelumnya aktif
        $tr = new MdlTransaksi();
        $dupState = $tr->updateData(array("id_master" => $no, "step_number" => $stepNumCurrent), array(
                "step_number" => $childTargetStepNum,

            )
        ) or die("Failed to update tr next-state!");
        cekHijau($this->db->last_query());
        //endregion

        //
        //region update nomor step di cloningan jadi minus current
        //endregion
        //
        //region sesuaikan signature untuk cloningan, ikut minus
        $dwsign = $tr->writeSignature($no, array(
                "nomer" => "rejection",
                "step_number" => $childTargetStepNum,
                "step_code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['target'],
                "step_name" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['label'],
                "group_code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['label'] . " oleh " . $this->session->login['nama'],
            )
        ) or die("Failed to write signature");
        cekKuning($this->db->last_query());
        //endregion

        //==tampilkan receipt
        cekOrange("-- DONE --");
//        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//        if (isset($_SESSION[$cCode])) {
//            unset($_SESSION[$cCode]);
//        }
//        if (isset($oldCode)) {
//            if (isset($_SESSION[$oldCode])) {
//                unset($_SESSION[$oldCode]);
//            }
//        }
//        echo "<script>";
//        echo "top.window.open('" . base_url() . "Transaksi/viewReceipt/$tmpNomorNota');";
//        echo "top.location.reload();";
//        echo "</script>";
    }

    public function dontFollowup()
    {
        $no = $this->uri->segment(3);
        $jenisTr = $this->uri->segment(4);
        $masterTargetStepNum = $this->uri->segment(5);
        $childTargetStepNum = $this->uri->segment(6);
        $stepNumCurrent = $this->uri->segment(7);
        $cCode = "_TR_" . $this->jenisTr;

        echo "no: $no<br>";
        echo "masterTargetStepNum: $masterTargetStepNum<br>";
        echo "childTargetStepNum: $childTargetStepNum<br>";
        echo "stepNumCurrent: $stepNumCurrent<br>";


        $this->db->trans_start();

        //
        //region update step2an di masternya

        $nextMasterStep = $stepNumCurrent;
        echo "nextMasterStep: $nextMasterStep<br>";
        $nextProp = array(
            "num" => $nextMasterStep,
            "code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['target'],
            "label" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['label'],
            "groupID" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextMasterStep]['userGroup'],
        );
        $tr = new MdlTransaksi();
        $dupState = $tr->updateData(array("id" => $no), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $masterTargetStepNum,

            )
        ) or die("Failed to update tr next-state!");
        cekHijau($this->db->last_query());
        //endregion

        //region update step yang sebelumnya aktif
        $tr = new MdlTransaksi();
        $dupState = $tr->updateData(array("id_master" => $no, "step_number" => $stepNumCurrent), array(
                "step_number" => $childTargetStepNum,

            )
        ) or die("Failed to update tr next-state!");
        cekHijau($this->db->last_query());
        //endregion

        //
        //region update nomor step di cloningan jadi minus current
        //endregion
        //
        //region sesuaikan signature untuk cloningan, ikut minus
        $dwsign = $tr->writeSignature($no, array(
                "nomer" => "rejection",
                "step_number" => $childTargetStepNum,
                "step_code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['target'],
                "step_name" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['label'],
                "group_code" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][abs($childTargetStepNum)]['label'] . " oleh " . $this->session->login['nama'],
            )
        ) or die("Failed to write signature");
        cekKuning($this->db->last_query());
        //endregion

        //==tampilkan receipt
        cekOrange("-- DONE --");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        if (isset($_SESSION[$cCode])) {
            unset($_SESSION[$cCode]);
        }
        if (isset($oldCode)) {
            if (isset($_SESSION[$oldCode])) {
                unset($_SESSION[$oldCode]);
            }
        }
        //region feedback msg
        $this->session->errMsg = "transaction entry has been rejected<br>";
        $nextNum = $nextProp["num"];
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum])) {
            $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
            $this->session->errMsg .= "This entry needs to followed-up by <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['userGroup'] . "</strong><br>";
            $trBackLink = base_url() . get_class($this) . "/viewIncomplete/" . $this->jenisTr;

        } else {
            $this->session->errMsg .= "cannot detect transaction state<br>";
            $trBackLink = base_url() . get_class($this) . "/viewHistory/" . $this->jenisTr;

        }
        $trBackClick = "location.href='$trBackLink'";
        $this->session->errMsg .= "<a href='javascript:void(0)' onclick=\"$trBackClick\">view entry</a><br>";
        //endregion
        echo "<script>";
//        echo "top.window.open('" . base_url() . "Transaksi/viewReceipt/$tmpNomorNota');";
        echo "top.location.reload();";
        echo "</script>";

    }

    public function previewValue()
    {

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $jenisTr = $this->uri->segment(3);
        $masterID = $this->uri->segment(4);
        $valID = $this->uri->segment(5);
        $this->load->model("MdlTransaksi");
        $trA = new MdlTransaksi();
        $extSteps = $trA->lookupExtStepByID($valID);
        $rawPrevURL = $_GET['rawPrev'];
        $rawBuilderURL = $_GET['rawBuilderURL'];

        //arrprint($_GET);

        if (sizeof($extSteps) > 0) {
//            cekmerah("ada ganjalan step sebanyak " . sizeof($extSteps));
            $xSpec = $extSteps[0];
            echo "<h4 class='text-center'>" . $xSpec['label'] . " has been proposed at " . formatField("dtime", $xSpec['proposed']['time']);
            echo "</h4>";

            echo "<h6 class='text-center'>here are the details</h6>";


            echo "<table class='table table-condensed'>";

//            foreach ($extSteps as $xSpec) {
            echo "<tr>";
            echo "<td bbgcolor='#f0f0f0'>value name</td>";
            echo "<td>" . $xSpec['label'] . "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td bbgcolor='#f0f0f0'>amount</td>";
            echo "<td align='right'><input type='text' class='form-control text-right' readonly value='" . number_format($xSpec['value']) . "'></td>";
            echo "</tr>";

            if (in_array($xSpec['groupID'], $mems)) {
                echo "<tr>";
                echo "<td>";
                echo "<a class='btn btn-warning btn-block' onclick=\"if(confirm('Rejecting this " . $xSpec['label'] . " will delete this value and can not be undone. \\nContinue rejecting?')==1){document.getElementById('result').src='" . base_url() . get_class($this) . "/dontSaveValue/$jenisTr/$masterID/$valID/" . $xSpec['key'] . "?rawPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';}\">reject " . $xSpec['label'] . "</a>";
                echo "</td>";
                echo "<td align='right'>";
                echo "<a class='btn btn-success btn-block' onclick=\"if(confirm('Approving this " . $xSpec['label'] . " value will add this value into this transaction. \\nContinue approving?')==1){document.getElementById('result').src='" . base_url() . get_class($this) . "/saveValue/$jenisTr/$masterID/$valID/" . $xSpec['value'] . "?rawPrev=$rawPrevURL';}\">approve " . $xSpec['label'] . "</a>";
                echo "</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<td colspan='2' align='center'>you are not allowed to approve this value</td>";
                echo "</tr>";
            }

//            }
            echo "</table>";
        } else {
            die("no such value!");
        }

    }

    public function saveValue()
    {

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $jenisTr = $this->uri->segment(3);
        $masterID = $this->uri->segment(4);
        $valID = $this->uri->segment(5);
        $valValue = $this->uri->segment(6);

        $rawPrevURL = $_GET['rawPrev'];
        $prevUrl = blobDecode($rawPrevURL);

//        cekmerah($rawPrevURL);


        $this->load->model("MdlTransaksi");
        $trA = new MdlTransaksi();
//        cekmerah("$masterID / $valID / $valValue");

        $xProp = $trA->lookupExtStepByID($valID);

//        cekbiru($xProp[0]['key'].": $valValue");

        $this->db->trans_start();

        $trA->approveExtStepByID($valID) or die(lgShowAlert("unable to mark the approval sign into this entry"));

        cekhijau($this->db->last_query());

        //write signature
        //==tulis signature

//        print_r($xProp);

        $dwsign = $trA->writeSignature($masterID, array(
                "nomer" => "0.0.0.0",
                "step_number" => "99",
                "step_code" => $jenisTr . "_",
                "step_name" => $xProp[0]['label'] . " approval",
                "group_code" => $xProp[0]['groupID'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $xProp[0]['label'] . " approval by " . $this->session->login['nama'],
            )
        ) or die("Failed to write signature");
        cekKuning($this->db->last_query());

        cekOrange("-- DONE --");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . '$' . "('<div></div>').load('$prevUrl'),
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
//        echo "</script>";


//==cek, perlukah langsung membuka payment untuk nilai yang bersangkutan
        $paySrcs = $trA->lookupPaymentSrcs($masterID, $jenisTr . "_", $xProp[0]['key']);
        cekkuning("paySrcs");
        arrprint($paySrcs);
        if (sizeof($paySrcs) > 0) {
            foreach ($paySrcs as $xSpec) {
                if ($xSpec['sisa'] > 0) {
                    $xSpec['groupID'] = $this->config->item("heTransaksi_ui")[$xSpec['targetJenis']]['steps'][1]['userGroup'];
                    if (in_array($xSpec['groupID'], $mems)) {
                        $actionTarget .= "
                                    top.BootstrapDialog.show(
                                   {
                                       title:'do " . $xSpec['label'] . "',
                                       message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/selectPaymentSrc/" . $xSpec['targetJenis'] . "/" . $xSpec['extID'] . "/$masterID?rawPrev=$rawPrevURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";
                        $payBtns[$xSpec['targetJenis']] = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"$actionTarget\">do " . $xSpec['label'] . "</a>";
                    }
                }
            }
        }

        echo "<html>";
        echo "<head>";
        echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";

        echo "</body>";
        echo "</html>";


    }

    public function addValue()
    {

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $jenisTr = $this->uri->segment(3);
        $masterID = $this->uri->segment(4);
        $valID = $this->uri->segment(5);
        $this->load->model("MdlTransaksi");
        $trA = new MdlTransaksi();
        $extSteps = $trA->lookupExtStepByID($valID);
        $rawPrevURL = $_GET['rawPrev'];
        $rawBuilderURL = $_GET['rawBuilderURL'];

        //arrprint($_GET);

        if (sizeof($extSteps) > 0) {
//            cekmerah("ada ganjalan step sebanyak " . sizeof($extSteps));
            $xSpec = $extSteps[0];
            echo "<h4 class='text-center'>" . $xSpec['label'] . " has been proposed at " . formatField("dtime", $xSpec['proposed']['time']);
            echo "</h4>";

            echo "<h6 class='text-center'>here are the details</h6>";


            echo "<table class='table table-condensed'>";

//            foreach ($extSteps as $xSpec) {
            echo "<tr>";
            echo "<td bbgcolor='#f0f0f0'>value name</td>";
            echo "<td>" . $xSpec['label'] . "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td bbgcolor='#f0f0f0'>amount</td>";
            echo "<td align='right'><input type='text' class='form-control text-right' readonly value='" . number_format($xSpec['value']) . "'></td>";
            echo "</tr>";

            if (in_array($xSpec['groupID'], $mems)) {
                echo "<tr>";
                echo "<td>";
                echo "<a class='btn btn-warning btn-block' onclick=\"if(confirm('Rejecting this " . $xSpec['label'] . " will delete this value and can not be undone. \\nContinue rejecting?')==1){document.getElementById('result').src='" . base_url() . get_class($this) . "/dontSaveValue/$jenisTr/$masterID/$valID/" . $xSpec['key'] . "?rawPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';}\">reject " . $xSpec['label'] . "</a>";
                echo "</td>";
                echo "<td align='right'>";
                echo "<a class='btn btn-success btn-block' onclick=\"if(confirm('Approving this " . $xSpec['label'] . " value will add this value into this transaction. \\nContinue approving?')==1){document.getElementById('result').src='" . base_url() . get_class($this) . "/saveValue/$jenisTr/$masterID/$valID/" . $xSpec['value'] . "?rawPrev=$rawPrevURL';}\">approve " . $xSpec['label'] . "</a>";
                echo "</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo "<td colspan='2' align='center'>you are not allowed to approve this value</td>";
                echo "</tr>";
            }

//            }
            echo "</table>";
        } else {
            die("no such value!");
        }

    }

    public function dontSaveValue()
    {

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $jenisTr = $this->uri->segment(3);
        $masterID = $this->uri->segment(4);
        $valID = $this->uri->segment(5);
        $key = $this->uri->segment(6);

        $rawPrevURL = $_GET['rawPrev'];
        $prevUrl = blobDecode($rawPrevURL);
        $builderUrl = blobDecode($_GET['rawBuilderURL']);

//        cekmerah($rawPrevURL);


        $this->load->model("MdlTransaksi");
        $trA = new MdlTransaksi();
//        cekmerah("$masterID / $valID / $valValue");

        $xProp = $trA->lookupExtStepByID($valID);

//        cekbiru($xProp[0]['key'].": $valValue");

        $this->db->trans_start();

        $trA->rejectExtStepByID($valID) or die(lgShowAlert("unable to mark the approval sign into this entry"));

        cekhijau($this->db->last_query());

        //write signature
        //==tulis signature

//        print_r($xProp);

        $dwsign = $trA->writeSignature($masterID, array(
                "nomer" => "0.0.0.0",
                "step_number" => "99",
                "step_code" => $jenisTr . "_",
                "step_name" => $xProp[0]['label'] . " rejection",
                "group_code" => $xProp[0]['groupID'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $xProp[0]['label'] . " rejection by " . $this->session->login['nama'],
            )
        ) or die("Failed to write signature");
        cekKuning($this->db->last_query());


//        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
//                                   {
//                                       title:'Followup preview',
//                                       message: " . '$' . "('<div></div>').load('$prevUrl'),
//                                        size:top.BootstrapDialog.SIZE_WIDE,
//                                        draggable:false,
//                                        closable:true,
//                                        }
//                                        );";

        $actionTarget = "top.document.getElementById('result').src='$builderUrl';";

//        echo "</script>";


        //==menghapus payment untuk nilai yang bersangkutan (if any)
        $paySrcs = $trA->lookupPaymentSrcs($masterID, $jenisTr . "_", $xProp[0]['key']);
        cekkuning("paySrcs");
        arrprint($paySrcs);
        if (sizeof($paySrcs) > 0) {
            foreach ($paySrcs as $xSpec) {
                $this->load->model("Mdls/MdlPaymentSource");
                $l = new MdlPaymentSource();
                $insertIDs[] = $l->updateData(array(
                    "id" => $xSpec['id'],
                ),
                    array("sisa" => 0,)
                );
                cekHijau($this->db->last_query());
            }
        }

        //==update nilai2 yang dihapus ini dari registri
        $trr = new MdlTransaksi();
        $trr->setFilters(array());
        $trr->addFilter("transaksi_id='$masterID'");
        $trr->addFilter("param='main_inputs'");
        $tmpReg = $trr->lookupRegistries()->result();
        if (sizeof($tmpReg) > 0) {
            cekkuning("ada registri milik $key yang perlu didepak!");

            foreach ($tmpReg as $row) {
                $oldValues = unserialize(base64_decode($row->values));
                $baseRegistries['main_inputs'] = $oldValues;
                $trr->updateRegistry(
                    array(
                        "id" => $row->id
                    ),
                    array(
                        "transaksi_id" => -($masterID),
                    )
                );
                $baseRegistries['main_inputs'][$key] = 0;


            }
            $doWriteReg = $trr->writeRegistries($masterID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
        } else {
            cekkuning("registri: no depak depak club!");
        }

        cekOrange("-- DONE --");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        echo "<html>";
        echo "<head>";
        echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";

        echo "</body>";
        echo "</html>";


    }

    public function doFollowup()
    {

//        $no = $this->uri->segment(3);
        $no = rtrim($this->uri->segment(3), "-");
        $stepNum = $this->uri->segment(4);
        $stepNumCurrent = $this->uri->segment(5);


        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        //==ambil datanya
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
//        $tr->addFilter("id_top='" . $no . "'");
//        $tr->addFilter("transaksi_id='" . $no . "'");
        $tr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
        $tr->addFilter("step_number='" . $stepNumCurrent . "'");
        $tmpTr = $tr->lookupJoined()->result();

        cekkuning($this->db->last_query());
//        die();

        if (sizeof($tmpTr) > 0) {
            $this->jenisTr = $tmpTr[0]->jenis_master;

            $cCode = "_TR_" . $this->jenisTr;
            //region session init
            if (!isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($_SESSION[$cCode]['main'])) {
                $_SESSION[$cCode]['main'] = array();
            }
            if (!isset($_SESSION[$cCode]['items'])) {
                $_SESSION[$cCode]['items'] = array();
            }
            //endregion

            $detailValuesConfig = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] : array();
//            //cekBiru("CEK : " . $this->jenisTr);
//            $masterID = isset($tmpTr[0]->referensi_id) && $tmpTr[0]->referensi_id > 0 ? $tmpTr[0]->referensi_id : $tmpTr[0]->transaksi_id;
//            $masterID = $tmpTr[0]->transaksi_id;
            $masterID = $_SESSION[$cCode]['out_master']['masterID'];
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;

//            $trID = $tmpTr[0]->id_master;
            $trID = $tmpTr[0]->transaksi_id;


            //==references, previous entry
            $prevProp = array(
                "id" => $tmpTr[0]->transaksi_id,
                "jenis" => $tmpTr[0]->jenis,
                "nomer" => $tmpTr[0]->nomer,
            );


            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }


            $main = array();
            $items = array();
            $prevIDs = array();
            $prevNos = array();
            foreach ($tmpTr as $row) {
                $items[$row->produk_id] = array(
                    "id" => $row->produk_id,
                    "nama" => $row->produk_nama,
                    "jml" => $row->produk_ord_jml,
                    "harga" => $row->produk_ord_hrg,
                    "valid_qty" => $row->valid_qty,
                    "transaksi_id" => $row->transaksi_id,
                    "nomer" => $row->nomer,
                );
                if (!in_array($row->transaksi_id, $prevIDs)) {
                    $prevIDs[] = $row->transaksi_id;
                }
                if (!in_array($row->nomer, $prevNos)) {
                    $prevNos[] = $row->nomer;
                }
                if (sizeof($detailValuesConfig) > 0) {
                    echo "detail values ada..<br>";
                    foreach ($detailValuesConfig as $key => $src) {
                        echo "$key akan ambil nilai dari $src<br>";
//                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
//                            $tmp[$key] = formatField($key, $detailValues[$row->produk_id][$key]);
                            $items[$row->produk_id][$key] = $detailValues[$row->produk_id][$key];
                        } else {
                            if (isset($row->$key)) {
//                                $tmp[$key] = formatField($key, $row->$key);
                                $items[$row->produk_id][$key] = $row->$key;
                            }
                        }
                        echo "dan sekarang nilainya: " . $items[$row->produk_id][$key] . "<br>";
                    }
                }
            }

        } else {
            $masterID = 0;
            $tmpNomorNota = "XXXX";
            $origJenis = 0;
            $topID = 0;
            die(lgShowAlert("No such receipt ID: $no!"));
        }

//        cekMerah("items items items");
//        arrprint($items);
//
//        cekHitam("CHILD GATES BEFORE");
//        arrprint($_SESSION[$cCode]['out_detail']);

        //==ongkir dll (additional fees) harus diselipkan kedalam masterGates aktual
        //region ongkir dll diselipkan ke masterGates
        if (isset($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) && sizeof($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues']) > 0) {
            foreach ($this->config->item("heTransaksi_core")[$this->jenisTr]['externalValues'] as $exKey => $exSpec) {
                if ($exSpec['useAt'] == $stepNum) {
                    if (isset($exSpec['mdlName']) && strlen($exSpec['mdlName']) > 3) {
                        $_SESSION[$cCode]['out_master'][$exKey . "_src"] = $_POST[$exKey . "_src"];
                        if (isset($_SESSION[$cCode]['main_add_values'][$exKey . "_src"])) {
                            $_SESSION[$cCode]['main_add_values'][$exKey . "_src"] = $_POST[$exKey . "_src"];
                        }
                    }
                    $_SESSION[$cCode]['out_master'][$exKey] = $_POST[$exKey];
                    if (isset($_SESSION[$cCode]['main_add_values'][$exKey])) {
                        $_SESSION[$cCode]['main_add_values'][$exKey] = $_POST[$exKey];
                    }

                    if ($exSpec['taxFactor'] > 0) {
                        $_SESSION[$cCode]['out_master'][$exKey . "_tax"] = $_POST[$exKey . "_tax"];
                        if (isset($_SESSION[$cCode]['main_add_values'][$exKey . "_tax"])) {
                            $_SESSION[$cCode]['main_add_values'][$exKey . "_tax"] = $_POST[$exKey . "_tax"];
                        }
                    }
                }
            }
        }
        //endregion


//        arrPrint($_SESSION[$cCode]['out_master']);
//        die();

        //region baca seting optional step
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNum]['optCriteriaField']) && strlen($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNum]['optCriteriaField']) > 0) {
//            //cekBiru("OPT ditentukan");
            $criteriaField = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNum]['optCriteriaField'];
            if (isset($_SESSION[$cCode]['out_master'][$criteriaField]) && $_SESSION[$cCode]['out_master'][$criteriaField] > 0) {
                echo "nextstepnum normal<br>";
                $nextStepNum = ($stepNum + 1);
                $useAdditionalStep = true;
            } else {
                echo "nextstepnum hampir normal<br>";
                $useAdditionalStep = false;
                $nextStepNum = ($stepNum + 2);
            }
        } else {
//            //cekBiru("OPT TIDAK ditentukan");
            echo "nextstepnum TIDAK normal<br>";
            $useAdditionalStep = false;
            $nextStepNum = ($stepNum + 1);
            echo "yaitu $nextStepNum<br>";
        }
        //endregion


//        die($nextStepNum);


        //region build table rekening
        $buildTablesMaster = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['master'] : array();
        $buildTablesDetail = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['detail'] : array();


        $addMasterTables = array(
            "rugilaba",
            "laba ditahan",
        );
        foreach ($addMasterTables as $trek) {
            $buildTablesMaster[] = array(
                "comName" => "RugiLaba",
                "loop" => array(
                    "$trek" => .0,
                ),
            );
        }
        arrPrint($buildTablesMaster);
//mati_disini();
        if (sizeof($buildTablesMaster) > 0) {
            foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                $mdlName = $buildTablesMaster_specs['comName'];
                $mdlName = "Com" . $mdlName;
                //cekHitam("model: $mdlName");
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();
                if (method_exists($m, "getTableNameMaster")) {
                    if (sizeof($m->getTableNameMaster())) {
                        $m->buildTables($buildTablesMaster_specs);
                    }
                }
            }
        }

        if (sizeof($buildTablesDetail) > 0) {
            foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                $mdlName = $buildTablesDetail_specs['comName'];
                $mdlName = "Com" . $mdlName;
                //cekHitam("model: $mdlName");
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();
                if (method_exists($m, "getTableNameMaster")) {
                    if (sizeof($m->getTableNameMaster())) {
                        $m->buildTables($buildTablesDetail_specs);
                    }
                }
            }
        }
        //endregion


//mati_disini();
        $this->db->trans_start();


        //
        //region pre-processors (master)
        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['master'])) {
            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['master'] : array();
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
            echo "ITEM NUM LABELS";

            if (sizeof($iterator) > 0) {

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "master-preproc: $comName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();


                    $subParams = array();

                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            if (substr($value, 0, 1) == ".") {
                                //cekMerah("$key => $value APA ADANYA");
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {


                                $tmpEx = $cal->multiExplode($value);
                                if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                    //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                    $newSrc = $value;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($_SESSION[$cCode]['main'][$val2])) {
                                            $newSrc = str_replace($val2, $_SESSION[$cCode]['main'][$val2], $newSrc);
                                        } else {
                                            if (isset($tmp[$val2])) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            } else {
                                                $newSrc = str_replace($val2, "0", $newSrc);
                                            }
                                        }
                                    }
                                    $realValue = $cal->calculate($newSrc);
                                } else {

                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");

                                        $realValue = isset($_SESSION[$cCode]['main'][$realCol]) ? -($_SESSION[$cCode]['main'][$realCol]) : -($dSpec[$realCol]);
                                    } else {
                                        $realCol = $value;
                                        if (!is_numeric($realCol)) {
                                            $realValue = isset($_SESSION[$cCode]['main'][$realCol]) ? ($_SESSION[$cCode]['main'][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                        } else {
                                            $realValue = isset($_SESSION[$cCode]['main'][$realCol]) ? ($_SESSION[$cCode]['main'][$realCol]) : 0;
                                        }
                                    }
                                }


                            }
                            $subParams['static'][$key] = $realValue;
                        }
//                            if (!isset($subParams['static']["transaksi_id"])) {
//                                $subParams['static']["transaksi_id"] = $masterID;
//                            }
                        if (!isset($subParams['static']["transaksi_id"])) {
                            $subParams['static']["transaksi_id"] = $masterID;
                        }


                        $subParams['static']["fulldate"] = date("Y-m-d");
                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                    }

                    if (sizeof($subParams) > 0) {
//								if ($filterNeeded) {
//									if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
//										$tmpOutParams[$cCtr][] = $subParams;
//									}
//								} else {
//
//									$tmpOutParams[$cCtr][] = $subParams;
//								}
                        $tmpOutParams[$cCtr] = $subParams;
                    }

                }


                $it = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $it++;


                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                    echo "master-preproc #$it: $comName, sending values <br>";

                    $mdlName = "Pre" . ucfirst($comName);
                    $this->load->model("Preprocs/" . $mdlName);
                    $m = new $mdlName($resultParams);


                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    } else {
                        $tobeExecuted = false;
                    }

                    if ($tobeExecuted) {
                        $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $gotParams = $m->exec();

                        cekbiru("gotparams");
                        arrprint($gotParams);


                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor


                            foreach ($gotParams as $gateName => $gSpec) {


                                if (isset($_SESSION[$cCode]['out_master'])) {
                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                        foreach ($gSpec as $key => $val) {
                                            $_SESSION[$cCode]['out_master'][$key] = $val;
                                        }

                                    }
                                }
                                //==inject gotParams to child gate
                                if (isset($_SESSION[$cCode]['main'])) {
                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                        foreach ($gSpec as $key => $val) {
                                            $_SESSION[$cCode]['main'][$key] = $val;
                                        }

                                    }
                                }

                                //cekMerah("REBUILDING VALUES..");
                                if (sizeof($itemNumLabels) > 0) {
                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                    foreach ($itemNumLabels as $key => $label) {
                                        //cekHere("$id === $key => $label");
                                        $_SESSION[$cCode]['out_master']['sub_' . $key] = ($_SESSION[$cCode]['out_master']['jml'] * $_SESSION[$cCode]['out_master'][$key]);
//                                        die();
                                    }
                                }

                            }


                        }

                    } else {
                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                    }
                }
            } else {
                //cekKuning("sub-preproc is not set");
            }


            $this->load->helper("he_value_builder");
            fillValues($this->jenisTr, $stepNumCurrent, $stepNum);


        } else {
            echo("no processor defined. skipping preprocessor..<br>");
        }

        //endregion


        //
        //region pre-processors (item)
        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['detail'])) {
//                $procList = $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum];
            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][$stepNum]['detail'] : array();
            $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
            echo "ITEM NUM LABELS";

            if (sizeof($iterator) > 0) {

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-preproc: $comName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
                    foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                        $id = $dSpec['id'];
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {
                                    //cekMerah("$key => $value APA ADANYA");
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;
                                } else {


                                    $tmpEx = $cal->multiExplode($value);
                                    if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                        //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                        $newSrc = $value;
                                        foreach ($tmpEx as $key2 => $val2) {
                                            if (isset($_SESSION[$cCode][$srcGateName][$id][$val2])) {
                                                $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$id][$val2], $newSrc);
                                            } else {
                                                if (isset($tmp[$val2])) {
                                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                                } else {
                                                    $newSrc = str_replace($val2, "0", $newSrc);
                                                }
                                            }
                                        }
                                        $realValue = $cal->calculate($newSrc);
                                    } else {

                                        if (substr($value, 0, 1) == "-") {
                                            $realCol = ltrim($value, "-");

                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                        } else {
                                            $realCol = $value;
                                            if (!is_numeric($realCol)) {
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                            } else {
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : 0;
                                            }
                                        }
                                    }


                                }
                                $subParams['static'][$key] = $realValue;
                            }
//                            if (!isset($subParams['static']["transaksi_id"])) {
//                                $subParams['static']["transaksi_id"] = $masterID;
//                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                $subParams['static']["transaksi_id"] = $masterID;
                            }


                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
//								if ($filterNeeded) {
//									if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
//										$tmpOutParams[$cCtr][] = $subParams;
//									}
//								} else {
//
//									$tmpOutParams[$cCtr][] = $subParams;
//								}
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
                }


                $it = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $it++;


                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                    echo "sub preproc #$it: $comName, sending values <br>";

                    $mdlName = "Pre" . ucfirst($comName);
                    $this->load->model("Preprocs/" . $mdlName);
                    $m = new $mdlName($resultParams);


                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    } else {
                        $tobeExecuted = false;
                    }

                    if ($tobeExecuted) {
                        $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $gotParams = $m->exec();

                        cekbiru("gotparams");
                        arrprint($gotParams);


                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor


                            foreach ($gotParams as $gateName => $paramSpec) {

                                if (!isset($_SESSION[$cCode][$gateName])) {
                                    $_SESSION[$cCode][$gateName] = array();
//                                    cekhijau("building the session: $gateName");
                                } else {
//                                    cekhijau("NOT building the session: $gateName");
                                }

                                foreach ($paramSpec as $id => $gSpec) {
//                                        $id = $gSpec['id'];

                                    if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                        $_SESSION[$cCode][$gateName][$id] = array();
                                    }

                                    if (isset($_SESSION[$cCode][$gateName][$id])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                            }

                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                            }

                                        }
                                    }

                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
//                                        die();
                                        }
                                    }
                                }
//                                    arrPrint($_SESSION[$cCode][$gateName]);die();
                            }


                        }

                    } else {
                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                    }
                }
            } else {
                //cekKuning("sub-preproc is not set");
            }


            $this->load->helper("he_value_builder");
            fillValues($this->jenisTr, $stepNumCurrent, $stepNum);


        } else {
            echo("no processor defined. skipping preprocessor..<br>");
        }

        //endregion


        //==tulis komponen, if any
//        $nextStepNum = ($stepNum + 1);
        //


        //region update step2an
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
            echo "authorizing to next step..<br>";
            $nextProp = array(
                "num" => $nextStepNum,
                "code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextStepNum]['target'],
                "label" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextStepNum]['label'],
                "groupID" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextStepNum]['userGroup'],
            );
        } else {//==ini step terakhir, tulis komponen jika ada

            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
        }
        //endregion

//        print_r($nextProp);die();


        echo "checking components..<br>";

        //referensi_id pada steps (dimatikan)
//        $masterReplacers = array(
//            "referensi_id" => $masterID,
//        );
//        $childReplacers = array(
//            "referensi_id" => $masterID,
//        );
//        foreach ($masterReplacers as $key => $val) {
//            $_SESSION[$cCode]['out_master'][$key] = $val;
//        }
//        foreach ($childReplacers as $key => $val) {
//            foreach ($_SESSION[$cCode]['out_detail'] as $xid => $cSpec) {
//                $id = $cSpec['id'];
//                $_SESSION[$cCode]['out_detail'][$id][$key] = $val;
//            }
//
//        }


        cekHitam("CHILD GATES AFTER");
        arrprint($_SESSION[$cCode]['out_detail']);


        //==tulis signature
//        arrPrint($_SESSION[$cCode]['out_master']);die();
        $dwsign = $tr->writeSignature($masterID, array(
                "nomer" => $tmpNomorNota,
                "step_number" => $stepNum,
                "step_code" => $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['target'],
                "step_name" => $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['label'],
                "group_code" => $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['label'] . " oleh " . $this->session->login['nama'],
            )
        ) or die("Failed to write signature");
        //cekKuning($this->db->last_query());

        //region update step terdahulu
        $tr = new MdlTransaksi();
        $dupState = $tr->updateData(array("id" => $topID), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $stepNum,

            )
        ) or die("Failed to update tr next-state!");
        cekHijau($this->db->last_query());


//        $dupState = $tr->updateData(array("id" => $topID), array(
//            "next_step_code"  => $nextProp['code'],
//            "next_step_label" => $nextProp['label'],
//            "next_group_code" => $nextProp['groupID'],
//            "next_step_num"   => $nextProp['num'],
//            "step_current"    => $stepNum,
//
//        )) or die("Failed to update tr next-state!");
//        cekHijau($this->db->last_query());
        //endregion


//        arrprint($_SESSION[$cCode]['tableIn_master']);die();
        //==prepare kloningan
        $tCode = $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['target'];
        $tCodeName = $this->config->item("heTransaksi_ui")[$origJenis]['steps'][$stepNum]['label'];
        $masterReplacers = array(
//            "referensi_id" => $masterID, (dimatikan)
//            "id_master"       => $masterID,
//            "id_top"          => $topID,
            "inv" => $tmpNomorNota,
//            "jenis_top"           => $tCode,
            "jenis" => $tCode,
            "jenis_label" => $tCodeName,
            "transaksi_jenis" => $tCode,
            "cabang_id" => $this->session->login['cabang_id'],
            "cabang_nama" => $this->session->login['cabang_nama'],
            "oleh_id" => $this->session->login['id'],
            "oleh_nama" => $this->session->login['nama'],
            "step_current" => "0",
            "step_number" => $stepNum,
//            "next_step_code"      => "",
//            "next_step_label"     => "",
//            "next_group_code"     => "",
            "next_step_code" => $nextProp['code'],
            "next_step_label" => $nextProp['label'],
            "next_group_code" => $nextProp['groupID'],
//===references
            "id_master" => $masterID,
            "id_top" => $topID,
            "ids_prev" => base64_encode(serialize($prevIDs)),
            "ids_prev_intext" => print_r($prevIDs, true),
            "nomer_top" => $_SESSION[$cCode]['tableIn_master']['nomer_top'],
            "nomers_prev" => base64_encode(serialize($prevNos)),
            "nomers_prev_intext" => print_r($prevNos, true),
//            "jenis_top"           => $this->jenisTr,
            "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
            "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
        );
        foreach ($masterReplacers as $key => $val) {
            $_SESSION[$cCode]['tableIn_master'][$key] = $val;
        }

        $childTableRepaclers = array(
            "sub_step_number" => $stepNum,
            "sub_step_current" => $stepNum,
            "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
            "next_substep_num" => $nextProp['num'],
            "next_substep_code" => $nextProp['code'],
            "next_substep_label" => $nextProp['label'],
            "next_subgroup_code" => $nextProp['groupID'],
        );
        foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
//			$id = $dSpec['id'];
            foreach ($childTableRepaclers as $key => $val) {
                $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
            }
        }

//        arrprint($_SESSION[$cCode]['tableIn_detail']);die();
        $masterReplacersO = array(

            "jenisTr" => $tCode,
            "jenisTrName" => $tCodeName,
            "olehID" => $this->session->login['id'],
            "olehName" => $this->session->login['id'],
            "stepNumber" => $stepNum,
            "stepCode" => $tCode,
        );
        foreach ($masterReplacersO as $key => $val) {
            $_SESSION[$cCode]['out_master'][$key] = $val;
        }

        //region menimbulkan nilai tagihan
        $unpaidList = null != $this->config->item('tr_unpaidList') ? $this->config->item('tr_unpaidList') : array();
//        arrprint($_SESSION[$cCode]['tableIn_master']);
        if (in_array($tCode, $unpaidList)) {
            $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $_SESSION[$cCode]['tableIn_master']['transaksi_nilai'];
            $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
            $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($_SESSION[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $_SESSION[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
            //cekMerah("NULIS TAGIHANN");
        } else {
            //cekMerah("TIDAK NULIS TAGIHANN");
        }
        //endregion

//        arrprint($prevProp);cekKuning("");
//        arrprint($_SESSION[$cCode]['tableIn_master']);
//
//        die();

//        print_r($_SESSION[$cCode]['out_master']);die();


        //region penomoran receipt #1
        //<editor-fold desc="==========penomoran">
        $this->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");

        $counterForNumber = array($this->config->item('heTransaksi_core')[$origJenis]['formatNota']);
        if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$origJenis]['counters'])) {
            die("Used number should be registered in 'counters' config as well");
        }

        foreach ($counterForNumber as $i => $cRawParams) {
            $cParams = explode("|", $cRawParams);
            foreach ($cParams as $param) {
                $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
            }
            $cRawValues = implode("|", $cValues[$i]);
            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
        }
        $tmpNomorNota2 = $paramSpec['paramString'];


        //</editor-fold>
        //endregion

        //region dynamic counters #1
        // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");
        $configCustomParams = $this->config->item('heTransaksi_core')[$origJenis]['counters'];
//        $configCustomParams=array("stepCode|placeID", "stepCode|olehID", "stepCode|placeID|olehID", "stepCode|supplierID");
        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                switch ($paramSpec['id']) {
                    case 0: //===counter type is new
                        $paramKeyRaw = print_r($cParams, true);
                        $paramValuesRaw = print_r($cValues[$i], true);
                        $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                        break;
                    default: //===counter to be updated
                        $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                        break;
                }
                //echo "<hr>";
            }
        }
        $appliedCounters2 = base64_encode(serialize($cContent));
        $appliedCounters_inText2 = print_r($cContent, true);


        $masterReplacers = array(
            "nomer" => $tmpNomorNota2,
            "counters" => $appliedCounters2,
            "counters_intext" => $appliedCounters_inText2,
        );
        foreach ($masterReplacers as $key => $val) {
            $_SESSION[$cCode]['tableIn_master'][$key] = $val;
        }

        $addValues = array(
            'counters' => $appliedCounters2,
            'counters_intext' => $appliedCounters_inText2,
            'nomer' => $tmpNomorNota2,
            'dtime' => date("Y-m-d H:i:s"),
            'fulldate' => date("Y-m-d"),
        );
        foreach ($addValues as $key => $val) {
            $_SESSION[$cCode]['tableIn_master'][$key] = $val;
        }

        // </editor-fold>
        //endregion


        //==tulis kloningan transaksi
        //region write entries
        if (sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
            $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
            if ($insertID < 1) {
                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
            }

            if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }


            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
//                cekMerah("ada mainElements $cCode");
//                arrprint($_SESSION[$cCode]['main_elements']);die();
                foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                        )
                    );
                }
            } else {
//                cekMerah("TAK ada mainElements");
            }

            if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                        }
                    }


                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                        }
                    }


                }
            }

            //region update validQty pada step sebelumnya yang di-refer
            if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail'] as $iID => $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);


                    if (isset($_SESSION[$cCode]['extractedItems'])) {
                        if (array_key_exists($iID, $_SESSION[$cCode]['extractedItems'])) {

                            $itemFulfilledJml = 0;
                            foreach ($_SESSION[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {

                                $tru = new MdlTransaksi();
                                $tru->setFilters(array());
                                $tru->setTableName($tru->getTableNames()['detail']);

                                if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                    $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                } else {
                                    $newValidQty = ($triSpec['valid_qty'] - $triSpec['valid_qty']);
                                }
                                $itemFulfilledJml += $newValidQty;
//                                $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                $updateContents = array(
                                    "valid_qty" => $newValidQty,
                                );
                                if ($newValidQty < 1) {
                                    $childPrevRepaclers = array(
                                        "next_substep_code" => "",
                                        "next_substep_label" => "",
                                        "next_subgroup_code" => "",
                                    );
                                    foreach ($childPrevRepaclers as $key => $val) {
                                        $updateContents[$key] = $val;
                                    }
                                }
                                $dupState = $tru->updateData(
                                    array(
                                        "produk_id" => $iID,
                                        "id" => $triID,
                                        //                            "transaksi_id" => $trID,
                                        "transaksi_id" => $triSpec['transaksi_id'],
                                    ),
                                    $updateContents
                                ) or die("Failed to update previous detail entries!");
                                cekHijau($this->db->last_query());
                                unset($tru);
                            }
                        }
                    }

                }
            }

            if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                }
            }
            //endregion
            $baseRegistries = array(
                'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                'out_master' => isset($_SESSION[$cCode]['out_master']) ? $_SESSION[$cCode]['out_master'] : array(),
                'out_detail' => isset($_SESSION[$cCode]['out_detail']) ? $_SESSION[$cCode]['out_detail'] : array(),
                'out_detail2' => isset($_SESSION[$cCode]['out_detail2']) ? $_SESSION[$cCode]['out_detail2'] : array(),
                'out_detail2_sum' => isset($_SESSION[$cCode]['out_detail2_sum']) ? $_SESSION[$cCode]['out_detail2_sum'] : array(),
                'out_detail_rsltItems' => isset($_SESSION[$cCode]['out_detail_rsltItems']) ? $_SESSION[$cCode]['out_detail_rsltItems'] : array(),
                'out_detail_rsltItems2' => isset($_SESSION[$cCode]['out_detail_rsltItems2']) ? $_SESSION[$cCode]['out_detail_rsltItems2'] : array(),
                'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
                'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
            );


            $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
        }
        //endregion


        //
        //region processing sub-post-processors, always
        //<editor-fold desc="----------sub postProc">

        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][$stepNum]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][$stepNum]['detail'] : array();
        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                echo "sub-postProcessor: $comName, initializing values <br>";
                $tmpOutParams[$cCtr] = array();
                foreach ($_SESSION[$cCode][$srcGateName] as $cnt => $dSpec) {
                    $subParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($value, 0, 1) == ".") {
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;

                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($dSpec[$realCol]) ? -($dSpec[$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                } else {
                                    if (preg_match("/\+/i", $value)) {
                                        $pecahans = explode("+", $value);
                                        $realValue = 0;
                                        foreach ($pecahans as $val) {
                                            if (substr($val, 0, 1) == "-") {
                                                $realCol = ltrim($val, "-");
                                                $realValue += isset($dSpec[$realCol]) ? -($dSpec[$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                            } else {
                                                $realCol = $val;
                                                $realValue += isset($dSpec[$realCol]) ? ($dSpec[$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                            }
                                        }
                                    } else {
                                        if (substr($value, 0, 1) == "-") {
                                            $realCol = ltrim($value, "-");
                                            $realValue = isset($dSpec[$realCol]) ? -($dSpec[$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                        } else {
                                            $realCol = $value;
                                            $realValue = isset($dSpec[$realCol]) ? ($dSpec[$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                        }
                                    }
                                }
                            }
                            $subParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                                echo "$key apa adanya: $realCol<br>";
                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($dSpec[$realCol]) ? -($dSpec[$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                } else {
                                    $realCol = $value;
                                    $realValue = isset($dSpec[$realCol]) ? ($dSpec[$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                }
                            }
                            $subParams['static'][$key] = $realValue;
                        }
//                            $subParams['static']["transaksi_id"] = $insertID;
                        $subParams['static']["fulldate"] = date("Y-m-d");
                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                    }

                    if (sizeof($subParams) > 0) {
                        $tmpOutParams[$cCtr][] = $subParams;
                    }
                }
            }

            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                echo "sub-postProcessor: $comName, sending values <br>";

                $mdlName = "Com" . ucfirst($comName);
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            }
        }


        //</editor-fold>
        //endregion

        //
        //region processing main-post-processors, always
        //<editor-fold desc="----------postProc">

        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][$stepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][$stepNum]['master'] : array();
        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                echo "post-processor: $comName<br>";

                $dSpec = $_SESSION[$cCode][$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                        if (substr($value, 0, 1) == ".") {
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                        } else {
                            if (substr($value, 0, 1) == "-") {
                                $realCol = ltrim($value, "-");
//                                        $realValue = -($dSpec[$realCol]);
                                $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                            } else {
//                                    $realCol = $value;
//                                    $realValue = $dSpec[$realCol];
                                if (preg_match("/\+/i", $value)) {
                                    $pecahans = explode("+", $value);
                                    $realValue = 0;
                                    foreach ($pecahans as $val) {
                                        //$realValue+=$dSpec[$val];
                                        if (substr($val, 0, 1) == "-") {
                                            $realCol = ltrim($val, "-");
//                                                    $realValue += -($dSpec[$realCol]);
                                            $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                        } else {
                                            $realCol = $val;
//                                                    $realValue += $dSpec[$realCol];
                                            $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                        }
                                    }
                                } else {
                                    //$realValue = $dSpec[$value];
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                    } else {
                                        $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                    }
                                }
                            }
                        }

                        $tmpOutParams['loop'][$key] = $realValue;
                        //$tmpOutParams['loop'][$key] = $dSpec[$value];
                    }
                }
                if (isset($tComSpec['static'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                        if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                        } else {
                            if (substr($value, 0, 1) == "-") {
                                $realCol = ltrim($value, "-");
                                $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                            } else {
                                $realCol = $value;
//                                        $realValue = $dSpec[$realCol];
                                $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                            }
                        }
                        $tmpOutParams['static'][$key] = $realValue;
                        //$tmpOutParams['static'][$key] = $dSpec[$value];
                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }

                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                }
                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                        if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                        } else {
                            if (substr($value, 0, 1) == "-") {
                                $realCol = ltrim($value, "-");
                                $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                            } else {
                                $realCol = $value;
//                                        $realValue = $dSpec[$realCol];
                                $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                            }
                        }
                        $tmpOutParams['static2'][$key] = $realValue;
                        //$tmpOutParams['static'][$key] = $dSpec[$value];
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                }

                //lgShowError("Ada kesalahan",);
                $mdlName = "Com" . ucfirst($comName);
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                //cekBiru("kiriman komponem $comName");
//                    arrPrint($tmpOutParams);
                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


            }
        }


        //</editor-fold>
        //endregion


        //
        //region ----------subcomponents
        //<editor-fold desc="----------subcomponents">


        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['detail'] : array();
        if (sizeof($iterator) > 0) {
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                $filterNeeded = true;
            }
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                echo "sub-component: $comName, initializing values <br>";
                $tmpOutParams[$cCtr] = array();
                foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                    cekmerah("mengevaluasi $srcGateName..");
//						$id = $dSpec['id'];
                    $subParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($value, 0, 1) == ".") {
                                //cekMerah("$key => $value APA ADANYA");
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {


                                $tmpEx = $cal->multiExplode($value);
                                if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                    //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                    $newSrc = $value;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($_SESSION[$cCode][$srcGateName][$id][$val2])) {
                                            $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$id][$val2], $newSrc);
                                        } else {
                                            if (isset($tmp[$val2])) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            } else {
                                                $newSrc = str_replace($val2, "0", $newSrc);
                                            }
                                        }
                                    }
                                    $realValue = $cal->calculate($newSrc);
                                } else {//===tidak pakai perhitungan
                                    //cekMerah("$key => $value TIDAK PAKAI PERHITUNGAN");
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                    } else {
                                        $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                        if (!is_numeric($realCol)) {
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                        } else {
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : 0;
                                        }

                                    }
                                }


                            }
                            $subParams['loop'][$key] = $realValue;
                            if ($filterNeeded) {
                                if ($subParams['loop'][$key] == 0) {
                                    unset($subParams['loop'][$key]);
                                }
                            }
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            if (substr($value, 0, 1) == ".") {
                                //cekMerah("$key => $value APA ADANYA");
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {


                                $tmpEx = $cal->multiExplode($value);
                                if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                    //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                    $newSrc = $value;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($_SESSION[$cCode][$srcGateName][$id][$val2])) {
                                            $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$id][$val2], $newSrc);
                                        } else {
                                            if (isset($tmp[$val2])) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            } else {
                                                $newSrc = str_replace($val2, "0", $newSrc);
                                            }
                                        }
                                    }
                                    $realValue = $cal->calculate($newSrc);
                                } else {//===tidak pakai perhitungan
                                    //cekMerah("$key => $value TIDAK PAKAI PERHITUNGAN");
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                    } else {
                                        $realCol = $value;
                                        if (!is_numeric($realCol)) {
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                        } else {
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : 0;
                                        }
                                    }
                                }


                            }
                            $subParams['static'][$key] = $realValue;
                        }
//                            if (!isset($subParams['static']["transaksi_id"])) {
//                                $subParams['static']["transaksi_id"] = $masterID;
//                            }
                        if (!isset($subParams['static']["transaksi_id"])) {
                            $subParams['static']["transaksi_id"] = $insertID;
                        }


                        $subParams['static']["fulldate"] = date("Y-m-d");
                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                    }

                    if (sizeof($subParams) > 0) {
                        if ($filterNeeded) {
                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        } else {

                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
                }
            }


            $it = 0;
            foreach ($iterator as $cCtr => $tComSpec) {
                $it++;


                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
//                    cekbiru("outparamnya komponen $comName");
//                    arrprint($tmpOutParams[$cCtr]);
//                    cekbiru("EO outparamnya komponen $comName");

                echo "sub component #$it: $comName, sending values <br>";

                $mdlName = "Com" . ucfirst($comName);
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();


                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                    $tobeExecuted = true;
                } else {
                    $tobeExecuted = false;
                }


                //cekHitam("kiriman sub-component $comName");
//                    cekMerah($comName . ", tobe-executed: $tobeExecuted");
//                    arrPri nt($tmpOutParams[$cCtr]);
                if ($tobeExecuted) {
                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                } else {
                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                }
            }
        } else {
            //cekKuning("subcomponents is not set");
        }


        //</editor-fold>
        //endregion

        //
        //region ----------components
        //<editor-fold desc="----------components">


        $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][$stepNum]['master'] : array();

        if (sizeof($iterator) > 0) {
            $it = 0;


            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

            foreach ($iterator as $cCtr => $tComSpec) {
                $it++;
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                echo "component #$it: $comName<br>";

                $dSpec = $_SESSION[$cCode][$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";


                        if (substr($value, 0, 1) == ".") {
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                            //cekMerah("$key => $value APA ADANYA");
                        } else {

                            $tmpEx = $cal->multiExplode($value);
                            if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                //cekMerah("======================  $key => $value PERHITUNGAN =========");
                                $newSrc = $value;
                                //cekBiru("$key => $value");
                                foreach ($tmpEx as $key2 => $val2) {
                                    if (isset($_SESSION[$cCode][$srcGateName][$val2])) {
                                        $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$val2], $newSrc);
                                        //cekBiru("$key2 $val2 newSrc diisi ".$_SESSION[$cCode][$srcGateName][$val2]);
                                    } else {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            //cekBiru("$key2 $val2 newSrc diisi ".$tmp[$val2]);
                                        } else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            //cekBiru("$key2 $val2 newSrc diisi 0");
                                        }
                                    }

                                }
                                $realValue = $cal->calculate($newSrc);
                                cekkuning("mencoba mengisi $key dengan src $value = $realValue (jalan kalkulasi)");

                            } else {//===tidak pakai perhitungan
                                //cekMerah("$key => $value BUKAN PERHITUNGAN");
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -(0);
                                    cekkuning("mencoba mengisi $key dengan src $value = $realValue (jalan minus)");
                                } else {
                                    $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : (0);
                                    cekkuning("mencoba mengisi $key dengan src $value = $realValue (jalan normal)");
                                }
                            }


                        }

                        //cekMerah("==================================================");
                        //cekMerah("$key -> $value");
                        //cekMerah("$newSrc = $realValue");
                        //cekMerah("==================================================");
                        cekkuning("$key diisi dengan $realValue");
                        $tmpOutParams['loop'][$key] = $realValue;
                        //$tmpOutParams['loop'][$key] = $dSpec[$value];
                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                        if (substr($value, 0, 1) == ".") {
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                            //cekMerah("$key => $value APA ADANYA");
                        } else {

                            $tmpEx = $cal->multiExplode($value);
                            if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                //cekMerah("$key => $value PERHITUNGAN");
                                $newSrc = $value;
                                foreach ($tmpEx as $key2 => $val2) {
                                    if (isset($_SESSION[$cCode][$srcGateName][$val2])) {
                                        $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$val2], $newSrc);
                                    } else {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                        } else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                        }
                                    }
                                }
                                $realValue = $cal->calculate($newSrc);
                            } else {//===tidak pakai perhitungan
                                //cekMerah("$key => $value BUKAN PERHITUNGAN");
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
                                    $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                }
                            }
                        }
                        $tmpOutParams['static'][$key] = $realValue;
                        //$tmpOutParams['static'][$key] = $dSpec[$value];
                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }

                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                }
                if (isset($tComSpec['static2'])) {
                    foreach ($tComSpec['static2'] as $key => $value) {
                        //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                        if (substr($value, 0, 1) == ".") {
                            $realCol = ltrim($value, ".");
                            $realValue = $realCol;
                            //cekMerah("$key => $value APA ADANYA");
                        } else {

                            $tmpEx = $cal->multiExplode($value);
                            if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                //cekMerah("$key => $value PERHITUNGAN");
                                $newSrc = $value;
                                foreach ($tmpEx as $key2 => $val2) {
                                    if (isset($_SESSION[$cCode][$srcGateName][$val2])) {
                                        $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$val2], $newSrc);
                                    } else {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                        } else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                        }
                                    }
                                }
                                $realValue = $cal->calculate($newSrc);
                            } else {//===tidak pakai perhitungan
                                //cekMerah("$key => $value BUKAN PERHITUNGAN");
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
                                    $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                }
                            }
                        }
                        $tmpOutParams['static'][$key] = $realValue;
                        //$tmpOutParams['static'][$key] = $dSpec[$value];
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                }

                //lgShowError("Ada kesalahan",);
                $mdlName = "Com" . ucfirst($comName);
                $this->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                //===filter value nol, jika harus difilter
                $tobeExecuted = true;

                if (in_array($mdlName, $compValidators)) {

                    $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            cekmerah("$comName : $key = $val ");
                            if ($val == 0) {
                                unset($tmpOutParams['loop'][$key]);
                            }
                        }
                    }
                    if (sizeof($tmpOutParams['loop']) < 1) {
                        $tobeExecuted = false;
                    }

                }


                if ($tobeExecuted) {
                    cekBiru("kiriman komponen $comName");
                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                } else {
                    cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                }


            }
        } else {
            //cekKuning("components is not set");
        }


        //</editor-fold>
        //endregion
//mati_disini("End of ...");

//        //
//        //  region //cek lajur dan neraca
////        if (sizeof($steps) == 1) {//==nggak pakai step2 lanjutan
//
//        $mdlName = "ComLedger";
//        $this->load->model("Mdls/".$mdlName);
//        $l = new ComLedger();
//        $l->addFilter("periode='forever'");
////        $date = date("Y-m-d");
//        $tmp = $l->getLastEntries();
////                arrPrint($tmp);
//        $arrHasilLajur = $l->getLajurBalance($tmp);
//        $arrHasilNeraca = $l->getNeracaBalance($tmp);
//
////            if (is_null($arrHasilLajur)) {
////                mati_disini("UN-BALANCE... LAJUR");
////            } else {
////                //cekBiru("BALANCE LAJUR...");
////            }
////
////            if (is_null($arrHasilNeraca)) {
////                mati_disini("UN-BALANCE... NERACA");
////            } else {
////                arrPrint($arrHasilNeraca);
////                //cekBiru("BALANCE NERACA...");
////            }
//
////        }
//        //  endregion //cek lajur dan neraca

        //
        //region nulis paymentSource
        $stepCode = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$stepNum]['target'];
        $paymentSources = $this->config->item("payment_source");
        if (array_key_exists($stepCode, $paymentSources)) {
            $payConfigs = $paymentSources[$stepCode];
            if (sizeof($payConfigs) > 0) {
                foreach ($payConfigs as $paymentSrcConfig) {
//					$paymentSrcConfig = $paymentSources[$stepCode];
                    $valueSrc = $paymentSrcConfig['valueSrc'];
                    $externSrc = $paymentSrcConfig['externSrc'];
                    $tr->writePaymentSrc($insertID, array(
                            "jenis" => $stepCode,
                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                            "extern_id" => $_SESSION[$cCode]['out_master'][$externSrc['id']],
                            "extern_nama" => $_SESSION[$cCode]['out_master'][$externSrc['nama']],
                            "nomer" => $tmpNomorNota2,
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $_SESSION[$cCode]['out_master'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $_SESSION[$cCode]['out_master'][$valueSrc],
                            "cabang_id" => $_SESSION[$cCode]['out_master']['placeID'],
                            "cabang_nama" => $_SESSION[$cCode]['out_master']['placeName'],
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        )
                    );
                    //cekMerah($this->db->last_query());
                }
            }

        } else {
            //cekMerah("TIDAK nulis paymentSrc");
        }
        //endregion


//        $masterID = $insertID;
        //
        //region connecting antar cabang
        //cekMerah("//cek connecting dari $origJenis========================================");
        $steps = isset($this->config->item("heTransaksi_ui")[$origJenis]['steps']) ? $this->config->item("heTransaksi_ui")[$origJenis]['steps'] : array();
        $connector = isset($this->config->item("heTransaksi_ui")[$origJenis]['connectTo']) ? $this->config->item("heTransaksi_ui")[$origJenis]['connectTo'] : "";
        if (strlen($connector) > 0) {
            cekMerah("to be connected to $connector");
            if ($stepNum == sizeof($steps)) {
                cekMerah("now connecting to $connector");
                if (!array_key_exists($connector, $this->config->item("heTransaksi_ui"))) {
                    die("kode connector tidak dikenali!");
                }
                if (sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']) < 2) {
                    die("konfigurasi connector harus memiliki step lebih dari satu!");
                }


                $oldCode = $cCode;
                $cCode = "_TR_" . $connector;

                $_SESSION[$cCode] = array();

                $_SESSION[$cCode] = array(
                    "main" => $_SESSION[$oldCode]['main'],
                    "items" => $_SESSION[$oldCode]['items'],
                    "out_master" => $_SESSION[$oldCode]['out_master'],
                    "out_detail" => $_SESSION[$oldCode]['out_detail'],
                    "tableIn_master" => $_SESSION[$oldCode]['tableIn_master'],
                    "tableIn_detail" => $_SESSION[$oldCode]['tableIn_detail'],
                    //
                    "rsltItems" => $_SESSION[$oldCode]['rsltItems'],
                    "out_detail_rsltItems" => $_SESSION[$oldCode]['out_detail_rsltItems'],
                    "tableIn_detail_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_rsltItems'],
                    //
                    "tableIn_master_values" => $_SESSION[$oldCode]['tableIn_master_values'],
                    "tableIn_detail_values" => $_SESSION[$oldCode]['tableIn_detail_values'],
                    "tableIn_detail_values_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_values_rsltItems'],
                );

//                    print_r($_SESSION[$cCode]);die();

                //==replace pertama
                $masterReplacersO = array(
                    "jenisTr" => $connector,
                    "jenisTrMaster" => $connector,
                    "jenisTrTop" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "jenis_label" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['label'],
                    "transaksi_jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "stepCode" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "placeID" => $_SESSION[$cCode]['out_master']['place2ID'],
                    "placeName" => $_SESSION[$cCode]['out_master']['place2Name'],
                    "place2ID" => $_SESSION[$cCode]['out_master']['placeID'],
                    "place2Name" => $_SESSION[$cCode]['out_master']['placeName'],
                    "cabangID" => $_SESSION[$cCode]['out_master']['place2ID'],
                    "cabangName" => $_SESSION[$cCode]['out_master']['place2Name'],
                    "cabang2ID" => $_SESSION[$cCode]['out_master']['placeID'],
                    "cabang2Name" => $_SESSION[$cCode]['out_master']['placeName'],
                    //
                    "gudang2ID" => $_SESSION[$cCode]['out_master']['gudangID'],
                    "gudang2Name" => $_SESSION[$cCode]['out_master']['gudangName'],
                    "gudangID" => $_SESSION[$cCode]['out_master']['gudang2ID'],
                    "gudangName" => $_SESSION[$cCode]['out_master']['gudang2Name'],

                );
                foreach ($masterReplacersO as $key => $val) {
                    $_SESSION[$cCode]['main'][$key] = $val;
                    $_SESSION[$cCode]['out_master'][$key] = $val;
                }
                $masterReplacers = array(
//                    "referensi_id" => $masterID, (dimatikan)
                    "inv" => $tmpNomorNota,
                    "jenis_master" => $connector,
                    "jenis_top" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "jenis_label" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['label'],
                    "transaksi_jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                    "cabang_id" => $_SESSION[$cCode]['tableIn_master']['cabang2_id'],
                    "cabang_nama" => $_SESSION[$cCode]['tableIn_master']['cabang2_nama'],
                    "cabang2_id" => $_SESSION[$cCode]['tableIn_master']['cabang_id'],
                    "cabang2_nama" => $_SESSION[$cCode]['tableIn_master']['cabang_nama'],
                    "gudang_id" => $_SESSION[$cCode]['tableIn_master']['gudang2_id'],
                    "gudang_nama" => $_SESSION[$cCode]['tableIn_master']['gudang2_nama'],
                    "gudang2_id" => $_SESSION[$cCode]['tableIn_master']['gudang_id'],
                    "gudang2_nama" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],
                    "step_avail" => sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']),
                    "step_current" => 1,
                    "step_number" => 1,
                    "next_step_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['target'] : "",
                    "next_step_label" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['label'] : "",
                    "next_group_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['userGroup'] : "",
//===references
//                    "id_master"            => $masterID,
//                    "id_top"               => $topID,
//                    "ids_prev"             => base64_encode(serialize(array($prevProp['id']))),
//                    "ids_prev_intext"      => print_r(array($prevProp['id'], true)),
//                    "nomer_top"            => $_SESSION[$cCode]['out_master']['nomer'],
//                    "nomers_prev"          => base64_encode(serialize(array($prevProp['nomer']))),
//                    "nomers_prev_intext"   => print_r(array($prevProp['nomer'], true)),
//                    "jenis_top"            => $this->jenisTr,
//                    "jenises_prev"        => base64_encode(serialize(array($prevProp['jenis']))),
//                    "jenises_prev_intext" => print_r(array($prevProp['jenis'], true)),
                );

                foreach ($masterReplacers as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }


                //region penomoran receipt #2
                //<editor-fold desc="==========penomoran">
                $this->load->model("CustomCounter");
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");

                $counterForNumber = array($this->config->item('heTransaksi_core')[$connector]['formatNota']);
                if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$connector]['counters'])) {
                    die("Used number should be registered in 'counters' config as well");
                }

                foreach ($counterForNumber as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    foreach ($cParams as $param) {
//                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                        $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['out_master'][$param] . "<br>";
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                }

                $tmpNomorNota2 = $paramSpec['paramString'];


                //</editor-fold>
                //endregion

                //region dynamic counters #2
                // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $configCustomParams = $this->config->item('heTransaksi_core')[$connector]['counters'];
                if (sizeof($configCustomParams) > 0) {
                    $cContent = array();
                    foreach ($configCustomParams as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                        switch ($paramSpec['id']) {
                            case 0: //===counter type is new
                                $paramKeyRaw = print_r($cParams, true);
                                $paramValuesRaw = print_r($cValues[$i], true);
                                $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                break;
                            default: //===counter to be updated
                                $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                break;
                        }
                        //echo "<hr>";
                    }
                }
                $appliedCounters2 = base64_encode(serialize($cContent));
                $appliedCounters_inText2 = print_r($cContent, true);
                // </editor-fold>
                //endregion


                $addValues = array(
                    'counters' => $appliedCounters2,
                    'counters_intext' => $appliedCounters_inText2,
                    'nomer' => $tmpNomorNota,
                    'dtime' => date("Y-m-d H:i:s"),
                    'fulldate' => date("Y-m-d"),
                );
                foreach ($addValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }

                //===cloning nota cab1 ke cab2
                //===daftar perbedaan
                //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,

                //==replace kedua
                $masterReplacers = array(
                    "nomer" => $tmpNomorNota2,
                    "counters" => $appliedCounters2,
                    "counters_intext" => $appliedCounters_inText2,
                );
                foreach ($masterReplacers as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }

                //===cloning detail/items cabang1 ke cabang2
                //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                $detailReplacers = array(
                    "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']),
                    "sub_step_current" => 1,
                    "sub_step_number" => 1,
                    "next_substep_num" => $_SESSION[$cCode]['tableIn_master']['next_step_num'],
                    "next_substep_code" => $_SESSION[$cCode]['tableIn_master']['next_step_code'],
                    "next_substep_label" => $_SESSION[$cCode]['tableIn_master']['next_step_label'],
                    "next_subgroup_code" => $_SESSION[$cCode]['tableIn_master']['next_group_code'],
//                    "next_substep_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['target'] : "",
//                    "next_substep_label" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['label'] : "",
//                    "next_subgroup_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['userGroup'] : "",
                );
                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $k => $dSpec) {
                        foreach ($dSpec as $key => $val){
                            $_SESSION[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                        }
                    }
                }

//                arrPrint($_SESSION[$cCode]['tableIn_detail']);die();
                //region ----------write transaksi & transaksi_data #2
                if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                    $tr = new MdlTransaksi();
                    $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                    $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                    if ($insertID < 1) {
                        die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                }

                if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    }
                }
                if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                    foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    }
                }

                if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
//                    cekMerah("ada mainElements");
                    foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                                "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                "name" => $aSpec['name'],
                                "label" => $aSpec['label'],
                                "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                            )
                        );
                    }
                } else {
//                    cekMerah("TAK ada mainElements");
                }

                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    }
                }

                if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    }
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                            foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));

                            }
                        }
                    }
                }


                if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                            foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));

                            }
                        }
                    }
                }

                //
                //region nulis paymentSource
                $stepCode = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
                $paymentSources = $this->config->item("payment_source");
                if (array_key_exists($stepCode, $paymentSources)) {

                    $payConfigs = $paymentSources[$stepCode];
                    if (sizeof($payConfigs) > 0) {
                        foreach ($payConfigs as $paymentSrcConfig) {
                            //					$paymentSrcConfig = $paymentSources[$stepCode];
                            $valueSrc = $paymentSrcConfig['valueSrc'];
                            $externSrc = $paymentSrcConfig['externSrc'];
                            $tr->writePaymentSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $_SESSION[$cCode]['out_master'][$externSrc['id']],
                                    "extern_nama" => $_SESSION[$cCode]['out_master'][$externSrc['nama']],
                                    "nomer" => $tmpNomorNota2,
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                    "terbayar" => 0,
                                    "sisa" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                    "cabang_id" => $_SESSION[$cCode]['out_master']['placeID'],
                                    "cabang_nama" => $_SESSION[$cCode]['out_master']['placeName'],
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                )
                            );
                        }
                    }


                    //cekMerah($this->db->last_query());

                } else {
                    //cekMerah("TIDAK nulis paymentSrc");
                }
                //endregion

                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                        "id_master" => $masterID,
                        "id_top" => $insertID,

                    )
                ) or die("Failed to update tr next-state!");
                //cekHijau($this->db->last_query());


                $baseRegistries = array(
                    'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                    'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                    'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                    'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                    'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                    'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                    'out_master' => isset($_SESSION[$cCode]['out_master']) ? $_SESSION[$cCode]['out_master'] : array(),
                    'out_detail' => isset($_SESSION[$cCode]['out_detail']) ? $_SESSION[$cCode]['out_detail'] : array(),
                    'out_detail2' => isset($_SESSION[$cCode]['out_detail2']) ? $_SESSION[$cCode]['out_detail2'] : array(),
                    'out_detail2_sum' => isset($_SESSION[$cCode]['out_detail2_sum']) ? $_SESSION[$cCode]['out_detail2_sum'] : array(),
                    'out_detail_rsltItems' => isset($_SESSION[$cCode]['out_detail_rsltItems']) ? $_SESSION[$cCode]['out_detail_rsltItems'] : array(),
                    'out_detail_rsltItems2' => isset($_SESSION[$cCode]['out_detail_rsltItems2']) ? $_SESSION[$cCode]['out_detail_rsltItems2'] : array(),
                    'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                    'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                    'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                    'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                    'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                    'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                    'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                    'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                    'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                    'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                    'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                    'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
                    'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
                    'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                    'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                );
                cekHitam("cetak transaksi $cCode");
                $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                //endregion

            } else {
                //cekMerah("to be delayed to connect to $connector");
            }
        } else {
            //cekKuning("not connecting to any tCode");
        }

        //endregion


        //==tampilkan receipt
        cekOrange("-- DONE --");
        //region writelog
        $this->load->model("Mdls/" . "MdlActivityLog");
        $hTmp = new MdlActivityLog();
        $tmpHData = array(
            "title" => $_SESSION[$cCode]['main']['jenisTrName'],
            "sub_title" => "Saving followup process",
            "uid" => $this->session->login['id'],
            "uname" => $this->session->login['nama'],
            "dtime" => date("Y-m-d H:i:s"),
            "transaksi_id" => $insertID,
            "deskripsi_old" => "",
            "deskripsi_new" => base64_encode(serialize($_SESSION[$cCode])),
            "jenis" => $this->jenisTr,
            "ipadd" => $_SERVER['REMOTE_ADDR'],
            "devices" => $_SERVER['HTTP_USER_AGENT'],
            "category" => "transaksi",
            "controller" => $this->uri->segment(1),
            "method" => $this->uri->segment(2),
            "url" => current_url(),

        );
        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        //endregion
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        if (isset($_SESSION[$cCode])) {
            unset($_SESSION[$cCode]);
        }
        if (isset($oldCode)) {
            if (isset($_SESSION[$oldCode])) {
                unset($_SESSION[$oldCode]);
            }
        }


        //region feedback msg
        $this->session->errMsg = "transaction entry has been saved<br>";
        $nextNum = $nextProp["num"];
        if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum])) {
            $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
            $this->session->errMsg .= "This entry needs to be authorized by <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['userGroup'] . "</strong><br>";
            $trBackLink = base_url() . get_class($this) . "/viewIncomplete/" . $this->jenisTr;

        } else {
            $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
            $trBackLink = base_url() . get_class($this) . "/viewHistory/" . $this->jenisTr;

        }
        $trBackClick = "location.href='$trBackLink'";
        $this->session->errMsg .= "<br><a href='javascript:void(0)' onclick=\"$trBackClick\">view entry</a><br>";
        //endregion

        echo "<script>";
        echo "top.window.open('" . base_url() . "Transaksi/viewReceipt/$tmpNomorNota2');";
        echo "top.location.reload();";
        echo "</script>";

    }

    public function tmpSave()
    {
        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;

        if (isset($_SESSION[$cCode])) {

            if(isset($_SESSION[$cCode]['main']) && sizeof($_SESSION[$cCode]['main'])>0 && isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items'])>0){

                $content=array(
                    "main"=>$_SESSION[$cCode]['main'],
                    "items"=>$_SESSION[$cCode]['items'],
                );

                $tr=new MdlTransaksi();
                $insertID = $tr->writeTmpEntries(
                    array(
                        "jenis"=>$this->jenisTr,
                        "cabang_id"=>$this->session->login['cabang_id'],
                        "gudang_id"=>$this->session->login['gudang_id'],
                        "date_created"=>date("Y-m-d H:i:s"),
                        "created_by"=>$this->session->login['id'],
                        "content"=>base64_encode(serialize($content)),
                        "content_intext"=>print_r($content,true),
                    )
                );
                cekkuning($this->db->last_query());
            }

            cekMerah("TRANSAKSI DONE");
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $_SESSION[$cCode]['main']['jenisTrName'],
                "sub_title" => "Saving new transaction",
                "uid" => $this->session->login['id'],
                "uname" => $this->session->login['nama'],
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => base64_encode(serialize($_SESSION[$cCode])),
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => $this->uri->segment(1),
                "method" => $this->uri->segment(2),
                "url" => current_url(),

            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //endregion
//            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//            if (isset($_SESSION[$cCode])) {
//                unset($_SESSION[$cCode]);
//            }
//            if (isset($oldCode)) {
//                if (isset($_SESSION[$oldCode])) {
//                    unset($_SESSION[$oldCode]);
//                }
//            }
//
//


        } else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

    public function save()
    {
        $this->jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $this->jenisTr;


        $relOptionConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeOptions'] : array();
        $inputLabels = array();
        $inputAuthConfigs = array();

        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $prevUrl = blobDecode($rawPrevURL);

        if (isset($_SESSION[$cCode])) {

            if (!isset($_SESSION[$cCode]['items'])) {
                die("belum ada item yang dipilih");
            } else {
                if (sizeof($_SESSION[$cCode]['items']) < 1) {
                    die("belum ada item yang dipilih");
                }
            }
            echo("now processing your transaction..<br>");

            $this->db->trans_start();


            //region pre-processors (master)
            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'])) {
                $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['master'] : array();
                $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();


                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];

                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {
                                    //cekMerah("$key => $value APA ADANYA");
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;
                                } else {


                                    $tmpEx = $cal->multiExplode($value);
                                    if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                        //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                        $newSrc = $value;
                                        foreach ($tmpEx as $key2 => $val2) {
                                            if (isset($_SESSION[$cCode]['out_master'][$val2])) {
                                                $newSrc = str_replace($val2, $_SESSION[$cCode]['out_master'][$val2], $newSrc);
                                            } else {
                                                if (isset($tmp[$val2])) {
                                                    $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                                } else {
                                                    $newSrc = str_replace($val2, "0", $newSrc);
                                                }
                                            }
                                        }
                                        $realValue = $cal->calculate($newSrc);
                                    } else {//===tidak pakai perhitungan
                                        //cekMerah("$key => $value TIDAK PAKAI PERHITUNGAN");
                                        if (substr($value, 0, 1) == "-") {
                                            $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                            $realValue = isset($_SESSION[$cCode]['out_master'][$realCol]) ? -($_SESSION[$cCode]['out_master'][$realCol]) : -($dSpec[$realCol]);
                                        } else {
                                            $realCol = $value;
                                            if (!is_numeric($realCol)) {
                                                $realValue = isset($_SESSION[$cCode]['out_master'][$realCol]) ? ($_SESSION[$cCode]['out_master'][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                            } else {
                                                $realValue = isset($_SESSION[$cCode]['out_master'][$realCol]) ? ($_SESSION[$cCode]['out_master'][$realCol]) : 0;
                                            }
                                        }
                                    }


                                }
                                $subParams['static'][$key] = $realValue;
                            }
//                            if (!isset($subParams['static']["transaksi_id"])) {
//                                $subParams['static']["transaksi_id"] = $masterID;
//                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
//									$subParams['static']["transaksi_id"] = $masterID;
                            }


                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                        }


                        $tmpOutParams[$cCtr] = $subParams;

                    }


                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;


                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                        echo "master-preproc #$it: $comName, sending values <br>";

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);


                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        } else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();

                            cekbiru("gotparams dari pre-proc $comName");
                            arrprint($gotParams);


                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                foreach ($gotParams as $gateName => $gSpec) {

//										$id=$gSpec['id'];
                                    if (isset($_SESSION[$cCode]['out_master'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['out_master'][$key] = $val;
                                            }

                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($_SESSION[$cCode]['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['main'][$key] = $val;
                                            }

                                        }
                                    }

                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($_SESSION[$cCode]['out_master'][$key])) {
                                                $_SESSION[$cCode]['out_master']['sub_' . $key] = ($_SESSION[$cCode]['out_master']['jml'] * $_SESSION[$cCode]['out_master'][$key]);
                                            }
//                                        die();
                                        }
                                    }

//                                    arrPrint($items);die();
                                }


                            }

                        } else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                } else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);


            } else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //
            //region pre-processors (item)
            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'])) {
//                $procList = $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1];

                $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $dSpec['id'];
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    if (substr($value, 0, 1) == ".") {
                                        //cekMerah("$key => $value APA ADANYA");
                                        $realCol = ltrim($value, ".");
                                        $realValue = $realCol;
                                    } else {


                                        $tmpEx = $cal->multiExplode($value);
                                        if (sizeof($tmpEx) > 1) {//===pakai perhitungan
                                            //cekMerah("$key => $value PAKAI PERHITUNGAN");
                                            $newSrc = $value;
                                            foreach ($tmpEx as $key2 => $val2) {
                                                if (isset($_SESSION[$cCode][$srcGateName][$id][$val2])) {
                                                    $newSrc = str_replace($val2, $_SESSION[$cCode][$srcGateName][$id][$val2], $newSrc);
                                                } else {
                                                    if (isset($tmp[$val2])) {
                                                        $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                                    } else {
                                                        $newSrc = str_replace($val2, "0", $newSrc);
                                                    }
                                                }
                                            }
                                            $realValue = $cal->calculate($newSrc);
                                        } else {//===tidak pakai perhitungan
                                            //cekMerah("$key => $value TIDAK PAKAI PERHITUNGAN");
                                            if (substr($value, 0, 1) == "-") {
                                                $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                            } else {
                                                $realCol = $value;
                                                if (!is_numeric($realCol)) {
                                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                                } else {
                                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : 0;
                                                }
                                            }
                                        }


                                    }
                                    $subParams['static'][$key] = $realValue;
                                }
//                            if (!isset($subParams['static']["transaksi_id"])) {
//                                $subParams['static']["transaksi_id"] = $masterID;
//                            }
                                if (!isset($subParams['static']["transaksi_id"])) {
//									$subParams['static']["transaksi_id"] = $masterID;
                                }


                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }

//							if (sizeof($subParams) > 0) {
//								if ($filterNeeded) {
//									if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
//										$tmpOutParams[$cCtr][] = $subParams;
//									}
//								} else {
//
//									$tmpOutParams[$cCtr][] = $subParams;
//								}
//							}
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
//arrPrint($tmpOutParams);
//arrPrint($iterator);

                    $it = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $it++;


                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                        echo "sub preproc #$it: $comName, sending values <br>";

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);


                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        } else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();

                            cekmerah("gotparams dari pre-proc $comName");
                            arrprint($gotParams);


                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                foreach ($gotParams as $gateName => $paramSpec) {

                                    if (!isset($_SESSION[$cCode][$gateName])) {
                                        $_SESSION[$cCode][$gateName] = array();
//                                    cekhijau("building the session: $gateName");
                                    } else {
//                                    cekhijau("NOT building the session: $gateName");
                                    }

                                    foreach ($paramSpec as $id => $gSpec) {
//										$id=$gSpec['id'];


                                        if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                            $_SESSION[$cCode][$gateName][$id] = array();
                                        }


                                        if (isset($_SESSION[$cCode][$gateName][$id])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                }

                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                }

                                            }
                                        }

                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                //cekHere("$id === $key => $label");
                                                if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                    $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                }
//                                        die();
                                            }
                                        }
                                    }
//                                    arrPrint($items);die();
                                }


                            }

                        } else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                } else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);


            } else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //===finalisasi sebelum masuk tabel beneran
            //===isinya ada pembentukan nomor nota dll

            //
            //region penomoran receipt
            //<editor-fold desc="==========penomoran">
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($this->config->item('heTransaksi_core')[$this->jenisTr]['formatNota']);
            if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'])) {
                die("Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                foreach ($cParams as $param) {
//                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                    $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['out_master'][$param] . "<br>";
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";

            $stepNumber = 1;

            $tmpNomorNota = $paramSpec['paramString'];

//            $_SESSION[$cCode]['tableIn_master']['nomer'] = $tmpNomorNota;


            if (isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
                );
            } else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }

            //</editor-fold>
            //endregion
            //
            //region dynamic counters
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'];
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                    switch ($paramSpec['id']) {
                        case 0: //===counter type is new
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                            break;
                    }
                    //echo "<hr>";
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);


            //
            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],


            );
            foreach ($addValues as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }
            //endregion

            //
            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],


            );
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }
            //endregion
            // </editor-fold>
            //endregion

            //
            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                $tr = new MdlTransaksi();
                $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }
                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                foreach ($injectors as $key => $val) {
                    $_SESSION[$cCode]['out_master'][$key] = $val;
                    foreach ($_SESSION[$cCode]['out_detail'] as $xid => $iSpec) {
                        $id = $iSpec['id'];
                        $_SESSION[$cCode]['out_detail'][$id][$key] = $val;
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                        "nomer" => $_SESSION[$cCode]['out_master']['nomer'],
                        "step_number" => 1,
                        "step_code" => $this->jenisTr,
                        "step_name" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'],
                        "group_code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['userGroup'],
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "keterangan" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'],
                    )
                ) or die("Failed to write signature");

                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                        "next_step_num" => $nextProp['num'],
                        "next_step_code" => $nextProp['code'],
                        "next_step_label" => $nextProp['label'],
                        "next_group_code" => $nextProp['groupID'],

                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $_SESSION[$cCode]['out_master']['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        //                    "jenis_top"           => $this->jenisTr,
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",

                    )
                ) or die("Failed to update tr next-state!");
                //cekHijau($this->db->last_query());

                $addValues = array(

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $_SESSION[$cCode]['out_master']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    //                    "jenis_top"           => $this->jenisTr,
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    //

                );
                foreach ($addValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }

            }
            if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                    foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $_SESSION[$cCode]['tableIn_master_values'][$key]));
                    }
                }
            }
            if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }


            if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
//                    cekkuning("making a clone for input key $key / $val");
//                    $tmpTableIn=$_SESSION[$cCode]['tableIn_master'];
//                    $replacers=array(
//                        "nomer"=>$_SESSION[$cCode]['tableIn_master']['nomer']."_$key",
//                    );
//                    foreach($replacers as $key=>$val){
//                        $tmpTableIn[$key]=$val;
//                    }
//                    $subInputInsertID = $tr->writeMainEntries($tmpTableIn);
                }
            }

            if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }

            if (isset($_SESSION[$cCode]['main_applets']) && sizeof($_SESSION[$cCode]['main_applets']) > 0) {
                foreach ($_SESSION[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                            "mdl_name" => $amdl,
                            "key" => $aSpec['key'],
                            "label" => $aSpec['labelValue'],
                            "description" => $aSpec['description'],
                        )
                    );
                }
            }

            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                        )
                    );


                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec['value'];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");


                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec['label'];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']['groupID'])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                        }
                                    }
                                }
                            }
                        } else {
//						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }

                    }

                }
            }


//            cekMerah("inputLabels");
//            arrprint($inputLabels);
//            cekMerah("inputAuths");
//            arrprint($inputAuthConfigs);


            if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                }
            }

            if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                        }
                    }


                }
            }

            if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                        }
                    }


                }
            }
            //endregion


            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'];
            //
            //region processing sub-components, if in single step
            //<editor-fold desc="----------subcomponents">

            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;

            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $mdlName = "Com" . ucfirst($comName);
                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                        $filterNeeded = true;
                    } else {
                        $filterNeeded = false;
                    }
                    echo "sub-component: $comName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
//                    arrPrint($_SESSION[$cCode][$srcGateName]);
//                    arrPrint($tComSpec);
//                    die();
                    cekhitam("$comName filterneeded: $filterNeeded");
                    cekhitam("mau mengiterasi $srcGateName");
                    foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                        cekhitam("telah mengiterasi $srcGateName");
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;
                                } else {
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                    } else {
                                        if (preg_match("/\+/i", $value)) {
                                            $pecahans = explode("+", $value);
                                            $realValue = 0;
                                            foreach ($pecahans as $val) {
                                                if (substr($val, 0, 1) == "-") {
                                                    $realCol = ltrim($val, "-");
                                                    $realValue += isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                                } else {
                                                    $realCol = $val;
                                                    $realValue += isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($dSpec[$realCol]);
                                                }
                                            }
                                        } else {
                                            if (substr($value, 0, 1) == "-") {
                                                $realCol = ltrim($value, "-");
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                            } else {
                                                $realCol = $value;
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($dSpec[$realCol]);
                                            }
                                        }
                                    }
                                }
                                $subParams['loop'][$key] = $realValue;
                                if ($filterNeeded) {
                                    if ($subParams['loop'][$key] == 0) {
                                        unset($subParams['loop'][$key]);
                                    }
                                }
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;
                                } else {
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($dSpec[$realCol]);
                                    } else {
//                                        cekMerah("$realCol ***");
//                                        arrPrint($_SESSION[$cCode][$srcGateName][$id]);
//                                        arrPrint($dSpec);
                                        $realCol = $value;
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($dSpec[$realCol]);
//                                        matiHere(" ".__LINE__);
                                    }
                                }
                                $subParams['static'][$key] = $realValue;
                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                $subParams['static']["transaksi_id"] = $insertID;
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
                            arrprint($subParams);
                            cekhitam("subparam ada isinya");
                            if ($filterNeeded) {
                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            } else {

                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        } else {
                            cekhitam("subparam TIDAK ada isinya");
                        }
                    }
                }
//                matiHere( " ".__LINE__);
//arrPrint($tmpOutParams);
//                matiHere();
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub component: $comName, sending values <br>";

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    //===filter value nol, jika harus difilter

                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    } else {
                        $tobeExecuted = false;
                    }


                    if ($tobeExecuted) {
                        cekMerah("$comName dieksekusiii");
                        arrPrint($tmpOutParams[$cCtr]);
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    } else {
                        cekMerah("$comName tidak eksekusi");
                    }
                }
            } else {
                //cekKuning("subcomponents is not set");
            }
//die();
            //</editor-fold>
            //endregion

            //
            //region processing main components, if in single step
            //<editor-fold desc="----------components">


            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['components'][1]['master'] : array();
            if (sizeof($iterator) > 0) {

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                            if (substr($value, 0, 1) == ".") {
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
//                                        $realValue = -($dSpec[$realCol]);
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
//                                    $realCol = $value;
//                                    $realValue = $dSpec[$realCol];
                                    if (preg_match("/\+/i", $value)) {
                                        $pecahans = explode("+", $value);
                                        $realValue = 0;
                                        foreach ($pecahans as $val) {
                                            //$realValue+=$dSpec[$val];
                                            if (substr($val, 0, 1) == "-") {
                                                $realCol = ltrim($val, "-");
//                                                    $realValue += -($dSpec[$realCol]);
                                                $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                            } else {
                                                $realCol = $val;
//                                                    $realValue += $dSpec[$realCol];
                                                $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                            }
                                        }
                                    } else {
                                        //$realValue = $dSpec[$value];
                                        if (substr($value, 0, 1) == "-") {
                                            $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                        } else {
                                            $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                        }
                                    }
                                }
                            }

                            $tmpOutParams['loop'][$key] = $realValue;
                            //$tmpOutParams['loop'][$key] = $dSpec[$value];
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                            if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
                                    $realCol = $value;
//                                        $realValue = $dSpec[$realCol];
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                }
                            }
                            $tmpOutParams['static'][$key] = $realValue;
                            //$tmpOutParams['static'][$key] = $dSpec[$value];
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }

                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;

                    if (in_array($mdlName, $compValidators)) {

                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }

                    }

                    if ($tobeExecuted) {

                        //cekBiru("kiriman komponem $comName");
//                        arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }


                }
            } else {
                //cekKuning("components is not set");
            }


            //</editor-fold>
            //endregion

            //
            //region processing sub-post-processors, always
            //<editor-fold desc="----------sub postProc">

            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['detail']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
                    foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                        $id = $dSpec['id'];
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;

                                } else {
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                    } else {
                                        if (preg_match("/\+/i", $value)) {
                                            $pecahans = explode("+", $value);
                                            $realValue = 0;
                                            foreach ($pecahans as $val) {
                                                if (substr($val, 0, 1) == "-") {
                                                    $realCol = ltrim($val, "-");
                                                    $realValue += isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                                } else {
                                                    $realCol = $val;
                                                    $realValue += isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                                }
                                            }
                                        } else {
                                            if (substr($value, 0, 1) == "-") {
                                                $realCol = ltrim($value, "-");
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                            } else {
                                                $realCol = $value;
                                                $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                            }
                                        }
                                    }
                                }
                                $subParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                                    $realCol = ltrim($value, ".");
                                    $realValue = $realCol;
                                    echo "$key apa adanya: $realCol<br>";
                                } else {
                                    if (substr($value, 0, 1) == "-") {
                                        $realCol = ltrim($value, "-");
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$id][$realCol]) : -($_SESSION[$cCode]['out_master'][$realCol]);
                                    } else {
                                        $realCol = $value;
                                        $realValue = isset($_SESSION[$cCode][$srcGateName][$id][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$id][$realCol]) : ($_SESSION[$cCode]['out_master'][$realCol]);
                                    }
                                }
                                $subParams['static'][$key] = $realValue;
                            }
//                            $subParams['static']["transaksi_id"] = $insertID;
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, sending values <br>";

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();


//                    arrprint($tmpOutParams[$cCtr]);

                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }
            }

            //</editor-fold>
            //endregion

            //
            //region processing main-post-processors, always
            //<editor-fold desc="----------postProc">
            $iterator = isset($this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['master']) ? $this->config->item('heTransaksi_core')[$this->jenisTr]['postProcessor'][1]['master'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                            if (substr($value, 0, 1) == ".") {
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
//                                        $realValue = -($dSpec[$realCol]);
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
//                                    $realCol = $value;
//                                    $realValue = $dSpec[$realCol];
                                    if (preg_match("/\+/i", $value)) {
                                        $pecahans = explode("+", $value);
                                        $realValue = 0;
                                        foreach ($pecahans as $val) {
                                            //$realValue+=$dSpec[$val];
                                            if (substr($val, 0, 1) == "-") {
                                                $realCol = ltrim($val, "-");
//                                                    $realValue += -($dSpec[$realCol]);
                                                $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                            } else {
                                                $realCol = $val;
//                                                    $realValue += $dSpec[$realCol];
                                                $realValue += isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                            }
                                        }
                                    } else {
                                        //$realValue = $dSpec[$value];
                                        if (substr($value, 0, 1) == "-") {
                                            $realCol = ltrim($value, "-");
//                                                $realValue = -($dSpec[$realCol]);
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                        } else {
                                            $realCol = $value;
//                                                $realValue = $dSpec[$realCol];
                                            $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                        }
                                    }
                                }
                            }

                            $tmpOutParams['loop'][$key] = $realValue;
                            //$tmpOutParams['loop'][$key] = $dSpec[$value];
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            //echo "- assigning $key into " . $dSpec[$value] . " <br>";
                            if (substr($value, 0, 1) == ".") {//==apa adanya, bukan variabel
                                $realCol = ltrim($value, ".");
                                $realValue = $realCol;
                            } else {
                                if (substr($value, 0, 1) == "-") {
                                    $realCol = ltrim($value, "-");
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? -($_SESSION[$cCode][$srcGateName][$realCol]) : -($dSpec[$realCol]);
                                } else {
                                    $realCol = $value;
//                                        $realValue = $dSpec[$realCol];
                                    $realValue = isset($_SESSION[$cCode][$srcGateName][$realCol]) ? ($_SESSION[$cCode][$srcGateName][$realCol]) : ($dSpec[$realCol]);
                                }
                            }
                            $tmpOutParams['static'][$key] = $realValue;
                            //$tmpOutParams['static'][$key] = $dSpec[$value];
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }

                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //cekBiru("kiriman komponem $comName");
//                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


                }
            } else {

            }


            //</editor-fold>
            //endregion

//            //
//            //  region //cek lajur dan neraca
//            if (sizeof($steps) == 1) {//==nggak pakai step2 lanjutan
//
//                $mdlName = "ComLedger";
//                $this->load->model("Mdls/".$mdlName);
//                $l = new ComLedger();
//                $l->addFilter("periode='forever'");
////        $date = date("Y-m-d");
//                $tmp = $l->getLastEntries();
////                arrPrint($tmp);
//                $arrHasilLajur = $l->getLajurBalance($tmp);
//                $arrHasilNeraca = $l->getNeracaBalance($tmp);
//
////                if (is_null($arrHasilLajur)) {
////                    mati_disini("UN-BALANCE... LAJUR");
////                } else {
////                    //cekBiru("BALANCE LAJUR...");
////                }
////
////                if (is_null($arrHasilNeraca)) {
////                    mati_disini("UN-BALANCE... NERACA");
////                } else {
////                    arrPrint($arrHasilNeraca);
////                    //cekBiru("BALANCE NERACA...");
////                }
//
//            }
//            //  endregion //cek lajur dan neraca


            //
            //region nulis paymentSource
            $stepCode = $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {

                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
//					$paymentSrcConfig = $paymentSources[$stepCode];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $tr->writePaymentSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $_SESSION[$cCode]['out_master'][$externSrc['id']],
                                "extern_nama" => $_SESSION[$cCode]['out_master'][$externSrc['nama']],
                                "nomer" => $_SESSION[$cCode]['out_master']['nomer'],
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                "cabang_id" => $_SESSION[$cCode]['out_master']['placeID'],
                                "cabang_nama" => $_SESSION[$cCode]['out_master']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                            )
                        );
                        //cekMerah($this->db->last_query());
                    }
                }


            } else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion


            //====registri value-gate
            $baseRegistries = array(
                'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                'out_master' => isset($_SESSION[$cCode]['out_master']) ? $_SESSION[$cCode]['out_master'] : array(),
                'out_detail' => isset($_SESSION[$cCode]['out_detail']) ? $_SESSION[$cCode]['out_detail'] : array(),
                'out_detail2' => isset($_SESSION[$cCode]['out_detail2']) ? $_SESSION[$cCode]['out_detail2'] : array(),
                'out_detail2_sum' => isset($_SESSION[$cCode]['out_detail2_sum']) ? $_SESSION[$cCode]['out_detail2_sum'] : array(),
                'out_detail_rsltItems' => isset($_SESSION[$cCode]['out_detail_rsltItems']) ? $_SESSION[$cCode]['out_detail_rsltItems'] : array(),
                'out_detail_rsltItems2' => isset($_SESSION[$cCode]['out_detail_rsltItems2']) ? $_SESSION[$cCode]['out_detail_rsltItems2'] : array(),
                'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
                'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
            );

            //===
            $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));


            //========extended steps (if any)
            //region extended steps
            if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                foreach ($_SESSION[$cCode]['main_inputs'] as $iKey => $iVal) {
                    if ($iVal > 0) {

                        cekbiru("evaluating $iKey ($iVal) for paymentSrc..");
                        $stepCode = $this->jenisTr . "_";
                        $paymentSources = $this->config->item("payment_source");


                        if (array_key_exists($stepCode, $paymentSources)) {
                            $payConfigs = $paymentSources[$stepCode];
                            cekbiru("$stepCode registered");


                            //===kalau melibatkan payment-source
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    if ($paymentSrcConfig['valueSrc'] == $iKey) {
                                        cekhijau($paymentSrcConfig['valueSrc'] . "/$iKey akan dieksekusi");
                                        $valueSrc = $paymentSrcConfig['valueSrc'];
                                        $externSrc = $paymentSrcConfig['externSrc'];
                                        if ($tr->paymentSrcExistsInMaster($insertID, $stepCode, $paymentSrcConfig['label'])) {
                                            cekhijau($paymentSrcConfig['label'] . " pada $stepCode $insertID sudah ada, tidak perlu ditulis");
                                        } else {
                                            cekhijau($paymentSrcConfig['label'] . " pada $stepCode $insertID BELUM ada, ditulis sekarang");
                                            $tr->writePaymentSrc($insertID, array(
                                                    "_key" => $iKey,
                                                    "jenis" => $stepCode,
                                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                    "extern_id" => $_SESSION[$cCode]['out_master'][$externSrc['id']],
                                                    "extern_nama" => $_SESSION[$cCode]['out_master'][$externSrc['nama']],
                                                    "nomer" => $_SESSION[$cCode]['out_master']['nomer'],
                                                    "label" => $paymentSrcConfig['label'],
                                                    "tagihan" => $_SESSION[$cCode]['main_inputs'][$valueSrc],
                                                    "terbayar" => 0,
                                                    "sisa" => $_SESSION[$cCode]['main_inputs'][$valueSrc],
                                                    "cabang_id" => $_SESSION[$cCode]['out_master']['placeID'],
                                                    "cabang_nama" => $_SESSION[$cCode]['out_master']['placeName'],
                                                    "oleh_id" => $this->session->login['id'],
                                                    "oleh_nama" => $this->session->login['nama'],
                                                    "dtime" => date("Y-m-d H:i:s"),
                                                    "fulldate" => date("Y-m-d"),
                                                )
                                            );
                                        }
//									cekMerah("paySrc: ".$this->db->last_query());

                                    } else {
                                        cekmerah($paymentSrcConfig['valueSrc'] . "/$iKey tidak untuk dieksekusi");
                                    }
                                }
                            }

                        } else {
                            cekbiru("$stepCode NOT registered");
                        }


                        //==periksa apakah mainInput memerlukan auth
                        if (array_key_exists($iKey, $inputAuthConfigs)) {
                            $gID = $inputAuthConfigs[$iKey];
                            if (strlen($gID) > 0) {
                                cekhijau("input $iKey bernilai $iVal memerlukan auth dari $gID");
                                $trA = new MdlTransaksi();
                                if ($trA->extStepExistsInMaster($insertID, $iKey)) {
                                    cekhijau("extStep SUDAH terdaftar, sekarang nggak akan ditulis");
                                } else {
                                    cekhijau("extStep belum terdaftar, sekarang hendak ditulis");
                                    $trA->writeExtStep($insertID, array(
                                        "master_id" => $insertID,
                                        "transaksi_id" => $insertID,
                                        "_key" => $iKey,
                                        "_label" => $inputLabels[$iKey],
                                        "_value" => $iVal,
                                        "group_id" => $gID,
                                        "state" => "0",
                                        "proposed_by" => $this->session->login['id'],
                                        "proposed_dtime" => date("Y-m-d H:i:s"),
                                        "done_by",
                                        "done_dtime",
                                    ));
                                    cekhijau($this->db->last_query());
                                }
                            }

                        }
                    }

                }
            }
            //endregion

            //==========================================================================================================
            $masterID = $insertID;
            //
            //region connecting antar cabang
            $connector = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['connectTo']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['connectTo'] : "";
            if (strlen($connector) > 0) {
                //cekMerah("to be connected to $connector");
                if (sizeof($steps) == 1) {
                    //cekMerah("now connecting to $connector");
                    if (!array_key_exists($connector, $this->config->item("heTransaksi_ui"))) {
                        die("kode connector tidak dikenali!");
                    }
                    if (sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']) < 2) {
                        die("konfigurasi connector harus memiliki step lebih dari satu!");
                    }


                    $oldCode = $cCode;
                    $cCode = "_TR_" . $connector;

//                    print_r($_SESSION[$cCode]);die();
//                    print_r($_SESSION[$oldCode]);die();
                    if (isset($_SESSION[$cCode])) {
                        $_SESSION[$cCode] = null;
                        unset($_SESSION[$cCode]);
                        $_SESSION[$cCode] = array();
                    }

                    $_SESSION[$cCode] = array(
                        "main" => $_SESSION[$oldCode]['main'],
                        "items" => $_SESSION[$oldCode]['items'],
                        //
                        "out_master" => $_SESSION[$oldCode]['out_master'],
                        "out_detail" => $_SESSION[$oldCode]['out_detail'],
                        "tableIn_master" => $_SESSION[$oldCode]['tableIn_master'],
                        "tableIn_detail" => $_SESSION[$oldCode]['tableIn_detail'],
                        //
                        "rsltItems" => $_SESSION[$oldCode]['rsltItems'],
                        "out_detail_rsltItems" => $_SESSION[$oldCode]['out_detail_rsltItems'],
                        "tableIn_detail_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_rsltItems'],
                        //
                        "tableIn_master_values" => $_SESSION[$oldCode]['tableIn_master_values'],
                        "tableIn_detail_values" => $_SESSION[$oldCode]['tableIn_detail_values'],
                        "tableIn_detail_values_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_values_rsltItems'],
                    );

//                    print_r($_SESSION[$cCode]);die();

                    //==replace pertama
                    $masterReplacersO = array(
                        "jenisTr" => $connector,
                        "jenisTrMaster" => $connector,
                        "jenisTrTop" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "jenis_label" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['label'],
                        "transaksi_jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "stepCode" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "placeID" => $_SESSION[$oldCode]['out_master']['place2ID'],
                        "placeName" => $_SESSION[$oldCode]['out_master']['place2Name'],
                        "place2ID" => $_SESSION[$oldCode]['out_master']['placeID'],
                        "place2Name" => $_SESSION[$oldCode]['out_master']['placeName'],
                        "cabangID" => $_SESSION[$oldCode]['out_master']['place2ID'],
                        "cabangName" => $_SESSION[$oldCode]['out_master']['place2Name'],
                        "cabang2ID" => $_SESSION[$oldCode]['out_master']['placeID'],
                        "cabang2Name" => $_SESSION[$oldCode]['out_master']['placeName'],
                        //
                        "gudang2ID" => $_SESSION[$cCode]['out_master']['gudangID'],
                        "gudang2Name" => $_SESSION[$cCode]['out_master']['gudangName'],
                        "gudangID" => $_SESSION[$cCode]['out_master']['gudang2ID'],
                        "gudangName" => $_SESSION[$cCode]['out_master']['gudang2Name'],
                    );
                    foreach ($masterReplacersO as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $val;
                        $_SESSION[$cCode]['out_master'][$key] = $val;
                    }
                    $masterReplacers = array(
//                        "referensi_id" => $masterID, (dimatikan)
                        "inv" => $tmpNomorNota,
//                        "jenis_master"    => $connector,

                        "jenis_master" => $connector,
                        "jenis_top" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
//                        "jenis_top" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "jenis_label" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['label'],
                        "transaksi_jenis" => $this->config->item("heTransaksi_ui")[$connector]['steps'][1]['target'],
                        "cabang_id" => $_SESSION[$oldCode]['tableIn_master']['cabang2_id'],
                        "cabang_nama" => $_SESSION[$oldCode]['tableIn_master']['cabang2_nama'],
                        "cabang2_id" => $_SESSION[$oldCode]['tableIn_master']['cabang_id'],
                        "cabang2_nama" => $_SESSION[$oldCode]['tableIn_master']['cabang_nama'],
                        "gudang_id" => $_SESSION[$oldCode]['tableIn_master']['gudang2_id'],
                        "gudang_nama" => $_SESSION[$oldCode]['tableIn_master']['gudang2_nama'],
                        "gudang2_id" => $_SESSION[$oldCode]['tableIn_master']['gudang_id'],
                        "gudang2_nama" => $_SESSION[$oldCode]['tableIn_master']['gudang_nama'],

                        "step_avail" => sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']),
                        "step_current" => 1,
                        "step_number" => 1,
                        "next_step_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['target'] : "",
                        "next_step_label" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['label'] : "",
                        "next_group_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['userGroup'] : "",
                    );
                    foreach ($masterReplacers as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }


                    //region penomoran receipt #2
                    //<editor-fold desc="==========penomoran">
                    $this->load->model("CustomCounter");
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");

                    $counterForNumber = array($this->config->item('heTransaksi_core')[$connector]['formatNota']);
                    if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$connector]['counters'])) {
                        die("Used number should be registered in 'counters' config as well");
                    }

                    foreach ($counterForNumber as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        foreach ($cParams as $param) {
//                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                            $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
//                    echo "filling $param with " . $_SESSION[$cCode]['out_master'][$param] . "<br>";
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }

                    $tmpNomorNota2 = $paramSpec['paramString'];


                    //</editor-fold>
                    //endregion

                    //region dynamic counters #2
                    // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $configCustomParams = $this->config->item('heTransaksi_core')[$connector]['counters'];
                    if (sizeof($configCustomParams) > 0) {
                        $cContent = array();
                        foreach ($configCustomParams as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $_SESSION[$cCode]['out_master'][$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                            $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                            switch ($paramSpec['id']) {
                                case 0: //===counter type is new
                                    $paramKeyRaw = print_r($cParams, true);
                                    $paramValuesRaw = print_r($cValues[$i], true);
                                    $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                    break;
                                default: //===counter to be updated
                                    $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                    break;
                            }
                            //echo "<hr>";
                        }
                    }
                    $appliedCounters2 = base64_encode(serialize($cContent));
                    $appliedCounters_inText2 = print_r($cContent, true);
                    // </editor-fold>
                    //endregion


                    $addValues = array(
                        'counters' => $appliedCounters,
                        'counters_intext' => $appliedCounters_inText,
                        'nomer' => $tmpNomorNota,
                        'dtime' => date("Y-m-d H:i:s"),
                        'fulldate' => date("Y-m-d"),
                    );
                    foreach ($addValues as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning nota cab1 ke cab2
                    //===daftar perbedaan
                    //== referensi_id, inv, jenis, nomer, counters, counters_inText, cabang_id, cabang_nama, cabang2_id, cabang2_nama,


                    //==replace kedua

                    $masterReplacers = array(
                        "nomer" => $tmpNomorNota2,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    foreach ($masterReplacers as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning detail/items cabang1 ke cabang2
                    //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                    $detailReplacers = array(
                        "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$connector]['steps']),
                        "sub_step_current" => 1,
                        "sub_step_number" => 1,
                        "next_substep_num" => $_SESSION[$cCode]['tableIn_master']['next_step_num'],
                        "next_substep_code" => $_SESSION[$cCode]['tableIn_master']['next_step_code'],
                        "next_substep_label" => $_SESSION[$cCode]['tableIn_master']['next_step_label'],
                        "next_subgroup_code" => $_SESSION[$cCode]['tableIn_master']['next_group_code'],
//                    "next_substep_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['target'] : "",
//                    "next_substep_label" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['label'] : "",
//                    "next_subgroup_code" => isset($this->config->item("heTransaksi_ui")[$connector]['steps'][2]) ? $this->config->item("heTransaksi_ui")[$connector]['steps'][2]['userGroup'] : "",
                    );
                    if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                        foreach ($_SESSION[$cCode]['tableIn_detail'] as $k => $dSpec) {
                            foreach ($dSpec as $key => $val){
                                $_SESSION[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                            }
                        }
                    }


                    //region ----------write transaksi & transaksi_data #2
                    if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                        $tr = new MdlTransaksi();
                        $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                        $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                        if ($insertID < 1) {
                            die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                        foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                            $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        }
                    }

                    if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                        foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                            $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        }
                    }

                    if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                        cekkuning("main_inputs detected");
                        foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                            $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
//                            cekkuning("making a clone for input key $key / $val");
//                            $subInputInsertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                        }
                    }

                    if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                        foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                            $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                        }
                    }


                    if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                        foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                            $tr->writeMainElements($insertID, array(
                                    "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                    "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                    "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                    "name" => $aSpec['name'],
                                    "label" => $aSpec['label'],
                                    "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                    "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                                )
                            );
                        }
                    }

                    if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                        $insertIDs = array();
                        foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                            $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                        }
                    }
                    if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                            $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                        }
                    }

                    if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                        foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                    $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                                }
                            }


                        }
                    }

                    if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                        foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                            if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                    $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                                }
                            }


                        }
                    }

                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                            "id_master" => $masterID,
                            "id_top" => $insertID,

                        )
                    ) or die("Failed to update tr next-state!");
                    //cekHijau($this->db->last_query());

                    $baseRegistries = array(
                        'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                        'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                        'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                        'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                        'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                        'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                        'out_master' => isset($_SESSION[$cCode]['out_master']) ? $_SESSION[$cCode]['out_master'] : array(),
                        'out_detail' => isset($_SESSION[$cCode]['out_detail']) ? $_SESSION[$cCode]['out_detail'] : array(),
                        'out_detail2' => isset($_SESSION[$cCode]['out_detail2']) ? $_SESSION[$cCode]['out_detail2'] : array(),
                        'out_detail2_sum' => isset($_SESSION[$cCode]['out_detail2_sum']) ? $_SESSION[$cCode]['out_detail2_sum'] : array(),
                        'out_detail_rsltItems' => isset($_SESSION[$cCode]['out_detail_rsltItems']) ? $_SESSION[$cCode]['out_detail_rsltItems'] : array(),
                        'out_detail_rsltItems2' => isset($_SESSION[$cCode]['out_detail_rsltItems2']) ? $_SESSION[$cCode]['out_detail_rsltItems2'] : array(),
                        'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                        'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                        'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                        'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                        'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                        'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                        'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                        'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                        'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                        'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                        'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                        'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
                        'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
                        'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                        'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                    );
                    cekHitam("cetak transaksi $cCode");
                    $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                    //endregion

                    //
                    //region nulis paymentSource
                    $stepCode = $connector;
                    $paymentSources = $this->config->item("payment_source");
                    if (array_key_exists($stepCode, $paymentSources)) {

                        $payConfigs = $paymentSources[$stepCode];
                        if (sizeof($payConfigs) > 0) {
                            foreach ($payConfigs as $paymentSrcConfig) {
                                //					$paymentSrcConfig = $paymentSources[$stepCode];
                                $valueSrc = $paymentSrcConfig['valueSrc'];
                                $externSrc = $paymentSrcConfig['externSrc'];
                                $tr->writePaymentSrc($insertID, array(
                                        "jenis" => $connector,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $_SESSION[$cCode]['out_master'][$externSrc['id']],
                                        "extern_nama" => $_SESSION[$cCode]['out_master'][$externSrc['nama']],
                                        "nomer" => $_SESSION[$cCode]['out_master']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $_SESSION[$cCode]['out_master'][$valueSrc],
                                        "cabang_id" => $_SESSION[$cCode]['out_master']['placeID'],
                                        "cabang_nama" => $_SESSION[$cCode]['out_master']['placeName'],
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                    )
                                );
                                //cekMerah($this->db->last_query());
                            }
                        }


                    } else {
                        //cekMerah("TIDAK nulis paymentSrc");
                    }
                    //endregion


                } else {
                    //cekMerah("to be delayed to connect to $connector");
                }
            } else {
                //cekKuning("not connecting to any tCode");
            }

            //endregion


            cekMerah("TRANSAKSI DONE");
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $_SESSION[$cCode]['main']['jenisTrName'],
                "sub_title" => "Saving new transaction",
                "uid" => $this->session->login['id'],
                "uname" => $this->session->login['nama'],
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => base64_encode(serialize($_SESSION[$cCode])),
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => $this->uri->segment(1),
                "method" => $this->uri->segment(2),
                "url" => current_url(),

            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //endregion
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            if (isset($_SESSION[$cCode])) {
                unset($_SESSION[$cCode]);
            }
            if (isset($oldCode)) {
                if (isset($_SESSION[$oldCode])) {
                    unset($_SESSION[$oldCode]);
                }
            }


            //region feedback msg
            $this->session->errMsg = "transaction entry has been saved<br>";
            $nextNum = $nextProp["num"];
            if (isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum])) {
                $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
                $this->session->errMsg .= "This entry needs to be authorized by <strong class='text-blue'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['userGroup'] . "</strong><br>";
                $trBackLink = base_url() . get_class($this) . "/viewIncomplete/" . $this->jenisTr;

            } else {
                $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
                $trBackLink = base_url() . get_class($this) . "/viewHistory/" . $this->jenisTr;

            }
            $trBackClick = "location.href='$trBackLink'";
            $this->session->errMsg .= "<a href='javascript:void(0)' onclick=\"$trBackClick\">view entry</a><br>";
            //endregion


            if (strlen($rawPrevURL) > 3) {
                $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . '$' . "('<div></div>').load('$prevUrl'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";


                echo "<html>";
                echo "<head>";
                echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
                echo "</head>";
                echo "<body onload=\"$actionTarget\">";

                echo "</body>";
                echo "</html>";
            } else {

                echo "<script>";
                echo "top.window.open('" . base_url() . "Transaksi/viewReceipt/$tmpNomorNota');";
                echo "top.location.reload();";
                echo "</script>";
            }

        } else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }


}