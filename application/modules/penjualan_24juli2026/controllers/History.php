<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class History extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlCurrency");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $this->configUiModul = loadConfigUiModul_he_misc();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->bgTransaksiColor = bgTransaksiColor();
    }

    public function index()
    {
    }

    public function viewHistory__()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 100;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        //arrPrint($extHistoryKeterangan);
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        // cekHere($date1." ******* ".$date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $searchForm = isset($_GET['search']) ? "&search=" . $_GET['search'] : "";
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2$searchForm";
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
                $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->configUi[$jenisTr]['steps'][1]['target'];
            }
        }
        //endregion


        //region lookup histories

        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']

                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
            if (isset($_GET["cc"]) && ($_GET["cc"] == 1)) {
                $filters = array(
//                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }

        }
        if (isset($filters) && (sizeof($filters) > 0)) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        //region date filter
        // $this->db->where("fulldate>='" . $date1 . "'");
        // $this->db->where("fulldate<='" . $date2 . "'");
        //endregion

        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        if (isset($_GET['date1'])) {
            $limit = "";
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        showLast_query("biru");
        // arrPrintWebs($tmpHist);
        //matiHEre();
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

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
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

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();

                $trReg->setFilters(array());

                // $trReg->setFields($pairRegistries);
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }

                    }
                }


                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksi();
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();
                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
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

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksi();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
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

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
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
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
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
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    // $kolomValue_0s = formatField($fName, $row->$fName);
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            // $kolomValue_0s = formatField($fName, $row->$fName);
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            // $kolomValues = isset($row->$fName) ? formatField($fName, $row->$fName) : "-";
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                // $colValue = isset($row->$getKey) ? formatField($getKey, $row->$getKey) : "";
                                // $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey,$this->jenisTr,$modul_path) : "";
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksi();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        // $colValue = formatField($fName, $numb);
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
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
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . base_url() . "Transaksi/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . MODUL_PATH . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    $addKeterangan = "CANCELED";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;
                    }
                }
                // menambahkan keterangan fullfillment
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:orange;color:#000000;",
                    );
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(3),
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion
        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function viewHistoryOld()
    {
        // $sanhistory = ""
        if (!isset($this->session->login['id'])) {
            // redirect(base_url() . "Login");
            gotoLogin();
        }
        // arrPrint($this->uri->segment_array());
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(5);
        $allStep = $this->config->item("heTransaksi_ui")[$jenisTr]['steps'];
//                arrPrint($allStep);
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
            //            arrPrint($tempStep);
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        // cekHere($jenisTrsub);

        $customButton = isset($this->config->item("heTransaksi_layout")[$jenisTr]["customButton"][$selectedSTep]) ? $this->config->item("heTransaksi_layout")[$jenisTr]["customButton"][$selectedSTep] : array();
        // arrPrint($customButton);

        $eId = isset($_SESSION['login']) ? $_SESSION['login']['id'] : "0";
        $limit = 1000;
        //        $limit = 18;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;
        $historyFields_0 = $this->configUi[$jenisTr]['shortHistoryFields'];
        $remFields = array(
            "harga",
            "disc",
            "ppn",
            "nett2",
        );
        $addFields = array(
            "transaksi_nilai" => "bruto (IDR)",
            "diskon_nilai" => "discount (IDR)",
            "ppn_nilai" => "vat (IDR)",
            "transaksi_net" => "netto (IDR)",
        );
        $historyFields_1 = array_diff_key($historyFields_0, array_flip($remFields));
        $historyFields = array_replace($historyFields_1, $addFields);
        // arrPrint($historyFields_0);
        // arrPrint($historyFields);

        $this->load->helper("he_versi_history_old");
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['compactHistoryFields'] : array();
        }
        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";

        $oldHists = ConversiHistoryOld();
        $jmlOldHist = isset($oldHists[$jenisTr]) ? sizeof($oldHists[$jenisTr]) : 0;
        // arrprint($oldHists[$jenisTr]);
//         cekHijau($jmlOldHist);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        // $steps = isset(conversiHistoryOld()[$jenisTr]);
        $steps = $jmlOldHist > 0 ? $oldHists[$jenisTr] : array();
        // arrPrint($steps);
        //die();
        $stepLabels = array(//            "0" => "all"
        );
        $stepLinks = array(//            "0" => base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3)
        );
        $currentState = "";
        if (sizeof($steps) > 0) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);


            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    //                    $stepLabels[$stepNumber] = $stepSpec['stateLabel'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }

            }


            //            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->jenisTr;
            //            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $this->config->item("heTransaksi_ui")[$jenisTr]['steps'][1]['target'];
            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : $steps[1]['target'];
        }
        //endregion

        //
        //region lookup histories
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        //        $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
        //        $tr->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        //        $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
        $tr->addFilter("transaksi.jenis='" . $this->jenisTr . "'");
        // $tr->addFilter("transaksi.status ='0'");
        $tr->setFilters(array());

        //region date filter
        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("dtime>='" . $date1 . "'");
            $this->db->where("dtime<='" . $date2 . "'");
        }
        //endregion

        if (isset($currentState)) {
            $tr->addFilter("transaksi.jenis='" . $currentState . "'");
        }

        //        $tr->addFilter("next_substep_code=''");


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


        $action = array(
            "viewDetails" => MODUL_PATH . "/" . get_class($this) . "/viewDetails",
        );

        $this->db->where("dtime>='" . $date1 . "'");
        $this->db->where("dtime<='" . $date2 . "'");
        //        $tmpHist = $tr->lookupHistories_joined($jmlData, $limit, $page)->result();

        //        $tmpHist = $tr->lookupHistories_joined_all()->result();
        $jmlData = $tr->lookupDataCount();
        // cekHitam($this->db->last_query());
        //        $limit=10;
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));
        //        cekmerah($jmlData);
        //        $tmpHist = $tr->lookupHistories_joined_all()->result();


        //        $this->db->join($tr->getTableNames()['registry'], $tr->getTableNames()['registry'] . ".transaksi_id = " . $tr->getTableNames()['main'] . ".id and param='main' ");

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


        $action = array(
            "viewDetails" => MODUL_PATH . "/" . get_class($this) . "/viewDetails",
        );

        // $tr->addFilter("transaksi.jenis = '" . $this->jenisTr . "'");

        //region date filter
        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("dtime>='" . $date1 . "'");
            $this->db->where("dtime<='" . $date2 . "'");
        }
        //endregion
        // $this->db->where("oleh_nama" . $searchStr . "'");
        // $this->db->where("dtime>='" . $date1 . "'");
        // $this->db->where("dtime<='" . $date2 . "'");
        $this->db->where("id_master is null");
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        // cekKuning($this->db->last_query());
//        arrPrint($historyFields);
        $arrayHistory = array();
        $arrayHistory_ids = array();
        if (sizeof($tmpHist) > 0) {
            foreach ($tmpHist as $row) {
                $tmp = array();
                $tmp1 = array();
                foreach ($historyFields as $fName => $fLabel) {
                    //cekHitam($fName);

                    if (strpos($fName, '+') !== false) {//==mengandung penggabungan (+)
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            $colValue .= isset($row->$key) ? formatField($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        if ($fName == "nomer") {
                            $valTemp = formatTransNomer($row->$fName, $currentState, "-1");
                            //                            $valTemp = formatTransNomer($row->nomer, $row->jenis, "-1");
                            //                            cekHitam($valTemp);
                            //                            $val = "showModal('" . base_url() . "Transaksi/viewResume/".$row->$fName."','view resume for $valTemp')";
                            $val = url_sanhistory() . "public/pembelian/printnable_ci.php?Mode=$currentState&eid=$eId&no=" . $row->$fName;
                            // onclick=\"window.open('". $val .",'popup','width=900,height=600'); return false;\"
                            // $str = "<a href=\"$val\" target='blank' title='open $valTemp' data-toggle='tooltip' data-placement='auto' >";

                            $str = "<a href=\"$val\" title='open $valTemp' data-toggle='tooltip' data-placement='auto' onclick=\"window.open('" . $val . "','popup','width=900,height=600'); return false;\">";
                            $str .= "<span style='color:#000000;text-align:center;' class=''>";
                            $str .= $valTemp;
                            $str .= "</span>";
                            $str .= "</a>";
                            $colValue = $str;
                        }
                        else {
                            if (is_array($fLabel)) {
                                $val = isset($row->$fLabel['key']) ? $row->$fLabel['key'] : "";
                                $colValue = formatField($fLabel['key'], $val);
                            }
                            else {

                                $val = isset($row->$fName) ? $row->$fName : "";
                                $colValue = formatField($fName, $val);
                            }
                        }


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
//        arrPrintPink($arrayHistory);

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

        //region prepare params for viewer
        $data = array(
            "mode" => $this->uri->segment(3),
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            //            "title"                => $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            //            "pageCount"            => $numPages,
            //            "page"                 => $page,
            //            "pages"                => $pages,
            "customButton" => $customButton,
            "customButtonTarget" => "$currentState/old?date1=$date1&date2=$date2",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . "Transaksi/viewIncomplete/" . $this->uri->segment(4),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

        );
        //endregion


        $this->load->view("history", $data);
    }


    public function showData__()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        // cekHere($date1." ******* ".$date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }
            }
            if (isset($_GET['stID'])) {
                $currentState = $_GET['stID'];
            }
            else {
                $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->configUi[$jenisTr]['steps'][1]['target'];
            }
        }
        //endregion


        //region lookup histories

        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
        }
        if (isset($filters) && (sizeof($filters) > 0)) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
        if (isset($currentState)) {
            $tr->addFilter("jenis='" . $currentState . "'");
        }

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
//showLast_query("biru");
//cekBiru(sizeof($tmpHist));
        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $this->load->model("Mdls/MdlCurrency");
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
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

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();
                $trReg->setFilters(array());

                // foreach ($pairRegistries as $param) {
                // $trReg->setParam("transaksi_id");
                // $trReg->setInParam($arrTransID);
                // $trReg->setFilters(array("param" => $param));
                // arrPrint($arrTransID);
                // matiHere();
                if (sizeof($pairRegistries) > 0) {
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();
                    // ceklIme($this->db->last_query());
                    // matiHEre();

                    //                                        arrPrint($tmpReg);
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            foreach ($pairRegistries as $param) {
                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                            }
                            // $tmpReg_result[$regRow->transaksi_id][$regRow->param] = blobDecode($regRow->values);
                        }
                    }
                }

                // }

                $tr->setFilters(array());
                $tr = new MdlTransaksi();
                // $tr->setParam("id");
                // $tr->setInParam($arrTransTopID);
                $tr->addFilter("id in ('" . implode(",", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr->setFilters(array());
                    $tr = new MdlTransaksi();
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->addFilter("id in ('" . implode(",", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();

                    //                    $tr = new MdlTransaksi();
                    //                    $tr->setFilters(array());
                    //                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    //                    $tmpTransHist = $tr->lookupAll()->result();

                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
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

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $this->placeId = $cabang_id = $row->cabang_id;
                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksi();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
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

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
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
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
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
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                //                                cekHere(":: $getKey ::");
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
                            }
                            else {

                                $colValue = "-";
                            }
                        }


                        //                            $tr = new MdlTransaksi();
                        //                            $tr->setFilters(array());
                        //                            $tr->addFilter("param='main'");
                        //                            $tmpReg = $tr->lookupRegistriesByNumber($tNomer)->result();
                        ////                            cekHere($this->db->last_query());
                        //
                        //                            $logistic = $tmpReg[0]->oleh_nama;
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    //                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
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
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        // "bgcolor" => "background-color:red;color:#cccccc;",
                        "bgcolor" => "background-color:red !important;color:#ffc107;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField_he_format($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField_he_format($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;


                    }
                }
                // menambahkan keterangan fullfillment
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:orange;color:#000000;",
                    );
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode" => "showData",
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            //            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            //            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "alternateLink" => "",
            "alternateLinkCaption" => "",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function viewHistoryOriginal()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
//        arrPrint($this->uri->segment_array());
        $starttime = microtime(true);
        $limit = 100;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $requestCode = isset($this->configUi[$jenisTr]['requestCode']) ? $this->configUi[$jenisTr]['requestCode'] :array();
        $availRegCode=array();
        if(count($requestCode)>0){
            $master_rcode=$requestCode["masterCode"];
            $availRegCode = $this->configUi[$master_rcode]['steps'];
        }

        //arrPrint($extHistoryKeterangan);

        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $date1 = mySQL_escape_string($date1);
        $date2 = mySQL_escape_string($date2);

//        matiHere($date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        $pairRegCode = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {

                    $searchForm = isset($_GET['search']) ? "&search=" . $_GET['search'] : "";
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2$searchForm";
                }
                if(count($availRegCode)>0){
//                    matiHere(__LINE__);
                    $reqSubCodeStep[$stepSpec['target']]=$availRegCode[$stepNumber]["target"];
                }

            }
//            arrprint($reqSubCodeStep);
            if (isset($_GET['stID'])) {
                $currentStateActive = $_GET['stID'];
                if(count($reqSubCodeStep)>0){
                    $currentState = "jenis in ('".$_GET['stID']."','".$reqSubCodeStep[$_GET['stID']]."')";
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $currentState="jenis='".$_GET['stID']."'";
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $reqCode = blobEncode($currentState);
                }

            }
            else {
                $currentStateActive = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5):$this->configUi[$jenisTr]['steps'][1]['target'];
                if(count($reqSubCodeStep)>0){
                    $defCurent = $this->configUi[$jenisTr]['steps'][1]['target'];
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis in ('".$this->uri->segment(5)."','".$reqSubCodeStep[$this->uri->segment(5)]."')"  : "jenis in ('".$this->configUi[$jenisTr]['steps'][1]['target']."','".$reqSubCodeStep[$this->configUi[$jenisTr]['steps'][1]['target']]."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis='".$this->uri->segment(5)."'" : "jenis='".$this->configUi[$jenisTr]['steps'][1]['target']."'";
                    $reqCode = blobEncode($currentState);
                }

            }
        }
        //endregion
//matiHEre($jenis_master);

        //region lookup histories

        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
//                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']

                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
//                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }

        }

        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }

        if (strlen($jenis_master)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($jenis_master);
        }
        if (strlen($currentState)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($currentState);
        }

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        if (isset($_GET['date1'])) {
            $limit = "";
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
//cekLime($this->db->last_query());
        // arrPrintWebs($tmpHist);
//        matiHEre();
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

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
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

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();

                $trReg->setFilters(array());

                // $trReg->setFields($pairRegistries);
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            if ($regRow->$param == null) {
                                $regRow->$param = blobEncode(array());
                            }
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }

                    }
                }


                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksi();
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();
                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
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

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                // cekHijau($row->kirim_metode_id);
                $transaksi_id = $row->id;
                $this->placeId = $cabang_id = $row->cabang_id;
                $supplier_id = $row->suppliers_id;

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksi();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
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

                    $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
                    $detail = viewDetailTransaksi($tmpReg_result[$row->id], $arrItemShow, $row->jenis_master);
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    // cekHere($fLabel);
                    //                    cekHitam($fName);
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
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
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
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    // $kolomValue_0s = formatField($fName, $row->$fName);
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            // $kolomValue_0s = formatField($fName, $row->$fName);
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            // $kolomValues = isset($row->$fName) ? formatField($fName, $row->$fName) : "-";
                            if($fName=="jenis_master"){
                                $kolomValues = $this->masterConfigUi[$row->$fName]["label"];
                            }
                            else{
                                $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                            }

                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }


                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        elseif ($hisKey == "pengirim_nama") {
                            if ($row->kirim_metode_id == 1) {
                                $link = MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/$transaksi_id/";
                                $colValue = selectKurir($row->pengirim_id, $link);
//                            if ($row->pengirim_id == 0) {
//                                $colValue = "<button class='btn btn-warning'>pilih driver/kurir</button>";
//                            }
//                            else {
//                                $pengirim_nama = $row->pengirim_nama;
//                                $colValue = "<button class='btn btn-success'>$pengirim_nama</button>";
//                            }
                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        elseif ($hisKey == "worker_nama"){
                            if ($row->kirim_metode_id == 3) {
                                $link = MODUL_PATH . "FollowUp/doFollowupWorker/$jenisTr/$transaksi_id/";
                                $colValue = selectWorker($row->worker_id, $link);

                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
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

// if()
                    if ($fName == "no") {
                        // $colValue = formatField($fName, $numb);
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
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
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . base_url() . "Transaksi/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . MODUL_PATH . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['item_fields'] = $detail;
                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    $addKeterangan = "CANCELED";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;
                    }
                }
                // menambahkan keterangan fullfillment
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:orange;color:#000000;",
                    );
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion
// arrPrintHijau(url_segment());
//         cekHere("$currentState?date1=$date1&date2=$date2");
        $data = array(
            "mode" => $this->uri->segment(3),
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentStateActive) ? "$currentStateActive?date1=$date1&date2=$date2&reqCode=$reqCode" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentStateActive) ? $currentStateActive : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),

            "bgTransaksiColor" => $this->bgTransaksiColor,
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function viewHistory()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
//        arrPrint($this->uri->segment_array());
        $starttime = microtime(true);
        $limit = 100;
        $maxPageNum = 20;
        $jenisTr = $this->uri->segment(4);
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $requestCode = isset($this->configUi[$jenisTr]['requestCode']) ? $this->configUi[$jenisTr]['requestCode'] :array();
        $availRegCode=array();
        if(count($requestCode)>0){
            $master_rcode=$requestCode["masterCode"];
            $availRegCode = $this->configUi[$master_rcode]['steps'];
        }

        //arrPrint($extHistoryKeterangan);

        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $date1 = mySQL_escape_string($date1);
        $date2 = mySQL_escape_string($date2);

//        matiHere($date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        $pairRegCode = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {

                    $searchForm = isset($_GET['search']) ? "&search=" . $_GET['search'] : "";
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->jenisTr . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2$searchForm";
                }
                if(count($availRegCode)>0){
//                    matiHere(__LINE__);
                    $reqSubCodeStep[$stepSpec['target']]=$availRegCode[$stepNumber]["target"];
                }

            }
//            arrprint($reqSubCodeStep);
            if (isset($_GET['stID'])) {
                $currentStateActive = $_GET['stID'];
                if(count($reqSubCodeStep)>0){
                    $currentState = "jenis in ('".$_GET['stID']."','".$reqSubCodeStep[$_GET['stID']]."')";
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $currentState="jenis='".$_GET['stID']."'";
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $reqCode = blobEncode($currentState);
                }

            }
            else {
                $currentStateActive = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5):$this->configUi[$jenisTr]['steps'][1]['target'];
                if(count($reqSubCodeStep)>0){
                    $defCurent = $this->configUi[$jenisTr]['steps'][1]['target'];
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis in ('".$this->uri->segment(5)."','".$reqSubCodeStep[$this->uri->segment(5)]."')"  : "jenis in ('".$this->configUi[$jenisTr]['steps'][1]['target']."','".$reqSubCodeStep[$this->configUi[$jenisTr]['steps'][1]['target']]."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis='".$this->uri->segment(5)."'" : "jenis='".$this->configUi[$jenisTr]['steps'][1]['target']."'";
                    $reqCode = blobEncode($currentState);
                }

            }
        }
        //endregion
//matiHEre($jenis_master);

        //region lookup histories

        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();


        /** -----------------------------------------------------------------------------------
         * filtering data
         * -----------------------------------------------------------------------------------*/
        $getkw = $gets = $_GET;
        unset($getkw['kw']);
        $kwHref = http_build_query($getkw);
        unset($gets['page']);
        $xtraHref = http_build_query($gets);
        $limitDb = url_segment(5);
        $urutanDb = url_segment(6);
        /* --------------------------------------- ------------------------------------------
         * urutan dan limit
         * -----------------------------------------------------------------------------------*/
        if (isset($limitDb)) {
            $this->db->limit($limitDb);
        }
        if (isset($urutanDb)) {
            $this->db->order_by("transaksi.id", "$urutanDb");
        }
        // ------------------------------------------------------------------------------
        if (isset($_GET['kw'])) {
            $kw = $_GET['kw'];
            $tr->setKeyWord($kw);
            $page = 1;
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array("transaksi.customers_nama", "transaksi.oleh_nama", "transaksi.suppliers_nama","nomer"));
        }
        // ------------------------------------------------------------------------------

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
//                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']

                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
//                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }

        }

        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }

        if (strlen($jenis_master)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($jenis_master);
        }
        if (strlen($currentState)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($currentState);
        }

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);

        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
        }
        if (isset($_GET['date1'])) {
            $limit = "";
            cekMerah("limit" . $limit);
        }
        $tmpHist_00 = $tr->lookupHistories($jmlData, $limit, $page)->result();
//        cekLime($this->db->last_query());
//        cekPink(count($tmpHist_00));
        // arrPrintWebs($tmpHist_00);


        /**paging boss*/
        // -------------------------------------------------------paging
//        arrPrintHijau($_GET);
        $segmen = url_segment();
        $segmenUrl = implode("/", $segmen);
        $step = isset($_GET['step']) ? $_GET['step'] : "1";
        $gr = isset($_GET['gr']) ? $_GET['gr'] : "";

        $items = $tmpHist_00;
        $itemJml = count($items);
        $jmlPerhalaman = 16;

        $this->load->library("pagination");
        $pg = new Pagination();

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $pg->setTotalPages($itemJml);
        $pg->setPages($page);
        $pg->setLimit($jmlPerhalaman); // perhalaman
        $pg->setExtraHref("&$xtraHref"); // perhalaman

        $link_halaman = base_url() . $segmenUrl . "?page=";
        $pg->setWebUrlPage($link_halaman); // perhalaman
        $loaderPages = array(
            "id" => "undoneList",
            "link" => $link_halaman
        );
        // $pg->setLoaderPages($loaderPages);

        $paginationData = $pg->build();
        $btnPage = $paginationData['pagination'];
        $jmlHalaman = $paginationData['lastpage'];
        $keyPage = $page - 1;
        // -------------------------------------------------------paging
        $tmpHist = array_chunk($items, $jmlPerhalaman, true);
//        cekKuning(count($tmpHist));
//        cekHijau($keyPage);
        // arrPrintKuning($tmpHist[$keyPage]);

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

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist[$keyPage] as $row) {
                    $arrTransID[] = $row->id;
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

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();

                $trReg->setFilters(array());

                // $trReg->setFields($pairRegistries);
                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            if ($regRow->$param == null) {
                                $regRow->$param = blobEncode(array());
                            }
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }

                    }
                }


                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id in ('" . implode("','", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr = new MdlTransaksi();
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->setFilters(array());
                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();
                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
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

            }
            $endtime = microtime(true);
            $exectime = round(($endtime-$starttime),2);
//            matiHere("exectime :: $exectime detik");
            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = $page == 1 ? 0 : (($jmlPerhalaman * $page) - $jmlPerhalaman);

            foreach ($tmpHist[$keyPage] as $ii => $row) {

                // cekHijau($row->kirim_metode_id);
                $transaksi_id = $row->id;
                $this->placeId = $cabang_id = $row->cabang_id;
                $supplier_id = $row->suppliers_id;

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksi();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];


                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
//                                                    $valDetail = $eeReg[$kolom];
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
//                                                    $valDetail = $eeReg[$v1];
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
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

                    $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
                    $detail = viewDetailTransaksi($tmpReg_result[$row->id], $arrItemShow, $row->jenis_master);
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();
                //arrPrint($row);
                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    // cekHere($fLabel);
                    //                    cekHitam($fName);
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
//                            $colValue .= isset($row->$key) ? $row->$key . "<br>" : "";
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
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
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
//                                    $kolomValue_0s =$row->$fName;
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {

                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
//                            $kolomValue_0s = $row->$fName;
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            // $kolomValues = isset($row->$fName) ? formatField($fName, $row->$fName) : "-";
                            if($fName=="jenis_master"){
                                $kolomValues = $this->masterConfigUi[$row->$fName]["label"];
                            }
                            else{
                                $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
//                                $kolomValues = isset($row->$fName) ? $row->$fName : "-";
                            }

                        }
                        //endregion

                        $colValue = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "";
//
//                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }


                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        elseif ($hisKey == "pengirim_nama") {
                            if ($row->kirim_metode_id == 1) {
                                $link = MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/$transaksi_id/";
                                $colValue = selectKurir($row->pengirim_id, $link);
                                //                            if ($row->pengirim_id == 0) {
                                //                                $colValue = "<button class='btn btn-warning'>pilih driver/kurir</button>";
                                //                            }
                                //                            else {
                                //                                $pengirim_nama = $row->pengirim_nama;
                                //                                $colValue = "<button class='btn btn-success'>$pengirim_nama</button>";
                                //                            }
                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        elseif ($hisKey == "worker_nama"){
                            if ($row->kirim_metode_id == 3) {
                                $link = MODUL_PATH . "FollowUp/doFollowupWorker/$jenisTr/$transaksi_id/";
                                $colValue = selectWorker($row->worker_id, $link);

                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
//                                $colValue = isset($row->$getKey) ? $row->$getKey : "";
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

                    // if()
                    if ($fName == "no") {

                        $colValue = formatField_he_format($fName, $numb);
//                        $colValue = $numb;
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
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
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . base_url() . "Transaksi/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . MODUL_PATH . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['item_fields'] = $detail;
                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    $addKeterangan = "CANCELED";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $addKeterangan .= $row->deskripsi != NULL ? nl2br("  " . $row->deskripsi) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red;color:#cccccc;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;
                    }
                }
                // menambahkan keterangan fullfillment
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:orange;color:#000000;",
                    );
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }

            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion
// arrPrintHijau(url_segment());
        $data = array(
            "mode" => $this->uri->segment(3),
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentStateActive) ? "$currentStateActive?date1=$date1&date2=$date2&reqCode=$reqCode" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentStateActive) ? $currentStateActive : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),

            "bgTransaksiColor" => $this->bgTransaksiColor,
            // ------------------------------------------------ paging
            "btnPage" => $btnPage,
            "gPage" => $page,
            "jmlPerhalaman" => $jmlPerhalaman,
            "urutanDb" => $urutanDb,
            "segmenUrl" => $segmenUrl,
            "kwHref" => $kwHref,
            "kw" => $kw,
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function showData()
    {
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $limit = 20;
        $maxPageNum = 20;
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = $this->configUi[$jenisTr]['steps'];
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : $this->configUi[$jenisTr]['shortHistoryFields'];
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";
        $requestCode = isset($this->configUi[$jenisTr]['requestCode']) ? $this->configUi[$jenisTr]['requestCode'] :array();
        $availRegCode=array();
        if(count($requestCode)>0){

            $master_rcode=$requestCode["masterCode"];
            $availRegCode = $this->configUi[$master_rcode]['steps'];
//            arrprint($availRegCode);
//            matiHere(__LINE__);
        }
        //arrPrint($extHistoryKeterangan);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(30), 'Y-m-d');

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : $backdate_f;
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");


        // cekHere($date1." ******* ".$date2);

        //region preparing ERP step labels for top link
        $steps = $this->configUi[$jenisTr]['steps'];
        $stepLabels = array();
        $stepLinks = array();
        if (sizeof($steps) > 1) {
            $subCodes = array();
            $stepCodes = array();
            $jmlStep = count($steps);

            foreach ($steps as $stepNumber => $stepSpec) {
                if ($stepNumber <= $jmlStep) {
                    $subCodes[$stepSpec['target']] = $stepSpec['label'];
                    $stepCodes[] = $stepSpec['target'];
                    $stepLabels[$stepNumber] = $stepSpec['label'];
                    $stepLinks[$stepNumber] = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $stepSpec['target'] . "?date1=$date1&date2=$date2";
                }
                if(count($availRegCode)>0){
//                    matiHere(__LINE__);
                    $reqSubCodeStep[$stepSpec['target']]=$availRegCode[$stepNumber]["target"];
                }
            }
            if (isset($_GET['stID'])) {
//                $currentState = $_GET['stID'];
                $currentStateActive = $_GET['stID'];
                if(count($reqSubCodeStep)>0){
                    $currentState = "jenis in ('".$_GET['stID']."','".$reqSubCodeStep[$_GET['stID']]."')";
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $currentState="jenis='".$_GET['stID']."'";
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $reqCode = blobEncode($currentState);
                }
            }
            else {
//                $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : $this->configUi[$jenisTr]['steps'][1]['target'];
                $currentStateActive = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5):$this->configUi[$jenisTr]['steps'][1]['target'];
                if(count($reqSubCodeStep)>0){
                    $defCurent = $this->configUi[$jenisTr]['steps'][1]['target'];
                    $jenis_master = "jenis_master in ('".$this->jenisTr."','".$master_rcode."')";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis in ('".$this->uri->segment(5)."','".$reqSubCodeStep[$this->uri->segment(5)]."')"  : "jenis in ('".$this->configUi[$jenisTr]['steps'][1]['target']."','".$reqSubCodeStep[$this->configUi[$jenisTr]['steps'][1]['target']]."')";
                    $reqCode = blobEncode($currentState);
                }
                else{
                    $jenis_master ="jenis_master='".$this->jenisTr."'";
                    $currentState = strlen($this->uri->segment(5)) > 0 ? "jenis='".$this->uri->segment(5)."'" : "jenis='".$this->configUi[$jenisTr]['steps'][1]['target']."'";
                    $reqCode = blobEncode($currentState);
                }
            }
        }
        //endregion


        //region lookup histories

        $this->load->model("MdlTransaksi");

        $tr = new MdlTransaksi();

        $searchStr = isset($_GET['search']) ? $_GET['search'] : "";
        if (my_cabang_id() == "-1") {
            $filters = array(
                // "cabang_id" => $this->placeId,
//                "jenis_master" => $this->jenisTr,
                "link_id" => "0",
                "div_id" => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id" => $this->placeId,
//                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                    "seller_id" => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id" => $this->placeId,
//                    "jenis_master" => $this->jenisTr,
                    "link_id" => "0",
                    "div_id" => $this->session->login['div_id'],
                );
            }
        }
        if (sizeof($filters) > 0) {
            foreach ($filters as $key_f => $val_f) {
                $tr->addFilter("$key_f='" . $val_f . "'");
            }
        }
//        if (isset($currentState)) {
//            $tr->addFilter("jenis='" . $currentState . "'");
//        }
        if (strlen($jenis_master)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($jenis_master);
        }
        if (strlen($currentState)>1) {
//            $tr->addFilter("jenis='" . $currentState . "'");
            $tr->addFilter($currentState);
        }
        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }


        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            $this->db->where("fulldate>='" . $date1 . "'");
//            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $jmlData = $tr->lookupDataCount();
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? ($_GET['page']) : 1;
        $offset = ($limit * ($page - 1));

        $addParams = array();
        if (isset($_GET['addParams'])) {
            $addParams = unserialize(base64_decode($_GET['addParams']));
        }
        if ($addParams != null && sizeof($addParams) > 0) {
            foreach ($addParams as $f) {
                $tr->addFilter($f);
            }
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        if (isset($_GET['search'])) {
            $tr->setKeyWord($searchStr);
        }
        else {
//            $this->db->where("fulldate>='" . $date1 . "'");
//            $this->db->where("fulldate<='" . $date2 . "'");
        }

        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();

        // arrPrintWebs($tmpHist);
        //matiHEre();
        $arrCurrency = array();
        if (sizeof($printValas) > 0) {
            $this->load->model("Mdls/MdlCurrency");
            $trv = new MdlCurrency();
            $tmpCurrency = $trv->lookupAll()->result();
            if (sizeof($tmpCurrency) > 0) {
                foreach ($tmpCurrency as $key => $value) {
                    $arrCurrency[$key] = $value;
                }
            }
        }

        $cabang_id = $this->placeId;

        $arrayHistory = array();
        $arrayHistory_ids = array();
        $arrayHistory_canceled = array();
        $sumValue = array();
        if (sizeof($tmpHist) > 0) {
            if (sizeof($pairRegistries) > 0) {
                $arrSalesName = array();
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIndexID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
                $arrTransMainHist = array();
                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
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

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();

                $trReg->setFilters(array());

                // foreach ($pairRegistries as $param) {
                // $trReg->setParam("transaksi_id");
                // $trReg->setInParam($arrTransID);
                // $trReg->setFilters(array("param" => $param));
                // arrPrint($arrTransID);
                // matiHere();
                if (sizeof($pairRegistries) > 0) {
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    // matiHEre(__LINE__);
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();
                    // ceklIme($this->db->last_query());
                    // matiHEre();

                    //                                        arrPrint($tmpReg);
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            foreach ($pairRegistries as $param) {
                                if ($regRow->$param == null) {
                                    $regRow->$param = blobEncode(array());
                                }
                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                            }
                            // $tmpReg_result[$regRow->transaksi_id][$regRow->param] = blobDecode($regRow->values);
                        }

                    }
                }

                // }

                $tr->setFilters(array());
                $tr = new MdlTransaksi();
                // $tr->setParam("id");
                // $tr->setInParam($arrTransTopID);
                $tr->addFilter("id in ('" . implode(",", $arrTransTopID) . "')");
                $tmpTrTop = $tr->lookUpMainTransaksi()->result();
                if (sizeof($tmpTrTop) > 0) {
                    foreach ($tmpTrTop as $topSpec) {
                        $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                    }
                }

                if (sizeof($arrIdsHist) > 0) {
                    $tr->setFilters(array());
                    $tr = new MdlTransaksi();
                    // $tr->setParam("id");
                    // $tr->setInParam($arrTransHist);
                    $tr->addFilter("id in ('" . implode(",", $arrTransHist) . "')");
                    $tmpTransHist = $tr->lookUpMainTransaksi()->result();

                    //                    $tr = new MdlTransaksi();
                    //                    $tr->setFilters(array());
                    //                    $tr->addFilter("id in ('" . implode("','", $arrTransHist) . "')");
                    //                    $tmpTransHist = $tr->lookupAll()->result();

                    $tmpTransHist_result = array();
                    if (sizeof($tmpTransHist) > 0) {
                        foreach ($tmpTransHist as $histSpec) {
                            $tmpTransHist_result[$histSpec->id] = array(
                                "oleh_id" => $histSpec->oleh_id,
                                "oleh_nama" => $histSpec->oleh_nama,
                            );
                        }
                    }
                    //                    arrPrint($tmpTransHist_result);
                    //                    matiHEre();
                    if (sizeof($arrIdsHist) > 0) {
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

            }

            //arrPrint($tmpReg_result);
            //            matiHere();
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $transaksi_id = $row->id;
                $this->placeId = $cabang_id = $row->cabang_id;
                $supplier_id = $row->suppliers_id;

                // region ids_his
                $id_hist = blobDecode($row->ids_his);
                // endregion ids_his

                //region memangil global counter
                $tNomer_top = $row->nomer_top;
                //                $tr = new MdlTransaksi();
                //                $tr->addFilter("param='main'");
                //                $tmpReg = $tr->lookupRegistriesByNumber($tNomer_top)->result();
                //
                //                $arrSalesName = "";
                //                foreach ($tmpReg as $tmpRowReg) {
                //                    $arrSalesName = $tmpRowReg->oleh_nama;
                //                }
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";


                $tNomer = $row->nomer;
                $jenisTrtop = explode(".", $tNomer_top)[0];
                $jenisTrsub = explode(".", $tNomer)[0];
                $counterjenis = "$jenisTrsub|" . $this->placeId;
                // $counterjenis = my_cabang_id()=="-1"? "$jenisTrsub":"$jenisTrsub|" . $this->placeId;

                // matiHEre($jenisTrsub);
                $counterIds_his = blobDecode(blobDecode($row->ids_his)[1]['counters']);
                $counters = blobDecode($row->counters);
                // arrPrint($counters);
                // matiHere();

                $counterGlobal = $counters['stepCode|placeID'][$counterjenis];

                //-------------counter global------------
                $counterGlobalOverride = null;
                if (!empty($this->configUiJenis['counter_global'])) {
                    $key = $this->configUiJenis['counter_global'];
                    $counterGlobalOverride = isset($row->$key) ? $row->$key : null;
                }

                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = $counterGlobalOverride != null ? digit_5($counterGlobalOverride) : digit_5($counterIds_his_global);

                //endregion

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            //                                                cekHitam(":: $kolom :: $format ::");
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                    //                                                arrPrint($eeReg);
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        //                                        else{
                                        //                                            mati_disini("sudah ada $k1");
                                        //                                        }
                                        //
                                    }
                                }
                            }
                        }
                    }
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

                    $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
                    $detail = viewDetailTransaksi($tmpReg_result[$row->id], $arrItemShow, $row->jenis_master);
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $tmp = array();
                $tmp1 = array();

                //                 break;
                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    //                    cekHitam($fName);
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
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
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
                            // switch ($jenisTr) {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
                                // case "582spd":
                                default:
                                    $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                                    $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobals, $kolomValue_0s);
                                    break;
                            }
                        }
                        elseif ($fName == "nomer_top") {
                            $kolomValue_0s = formatField_he_format($fName, $row->$fName);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            //                            cekHitam(";; $fName");
                            if($fName=="jenis_master"){
                                $kolomValues = $this->masterConfigUi[$row->$fName]["label"];
                            }
                            else{
                                $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                            }
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }

                    //                    if ($fName == "ids_his") {
                    if (is_array($fLabel)) {

                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        //                        $tNomer = $id_hist[$hisStep][$hisKey];


                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        elseif ($hisKey == "pengirim_nama") {
                            if ($row->kirim_metode_id == 1) {
                                $link = MODUL_PATH . "FollowUp/doFollowupPengirim/$jenisTr/$transaksi_id/";
                                $colValue = selectKurir($row->pengirim_id, $link);
//                            if ($row->pengirim_id == 0) {
//                                $colValue = "<button class='btn btn-warning'>pilih driver/kurir</button>";
//                            }
//                            else {
//                                $pengirim_nama = $row->pengirim_nama;
//                                $colValue = "<button class='btn btn-success'>$pengirim_nama</button>";
//                            }
                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        elseif ($hisKey == "worker_nama"){
                            if ($row->kirim_metode_id == 3) {
                                $link = MODUL_PATH . "FollowUp/doFollowupWorker/$jenisTr/$transaksi_id/";
                                $colValue = selectWorker($row->worker_id, $link);
                            }
                            else {
                                $colValue = "-";
                            }
                        }
                        else {
                            if (isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
                                $getKey = $fLabel['transaksi_jenis2'][$row->transaksi_jenis2];
                                $colValue = isset($row->$getKey) ? formatField_he_format($getKey, $row->$getKey) : "";
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
                    //                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    //                    cekHere($logistic);
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
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
                        // $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }

                    // $valas .= " <li><a class='btn btn-xs btn-warning' href='javascript:void(0);' onclick=\"top.location.href='" . base_url() . "data/view/Currency'\"> <i class='fa fa-plus'></i> tambah currency </a></li>";
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }


                $tmp['item_fields'] = $detail;
                $tmp['next_pic'] = "-";

                // menambah background-color karena dicancel/reject/undo, atau yang dibatalkan
                //                $tmp['keterangan'] = "-";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    //                    $tmp['keterangan'] = "CANCELED";
                    $addKeterangan = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $addKeterangan .= $row->deskripsi != NULL ? nl2br("  " . $row->deskripsi) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:red !important;color:#ffc107;",
                    );
                }
                // menambah background-color karena diedit...
                if (sizeof($extHistoryKeterangan) > 0) {
                    foreach ($extHistoryKeterangan as $mode => $modeSpec) {
                        $mode_result = "";
                        if (sizeof($modeSpec) > 0) {
                            if (isset($row->$modeSpec['kolom']) && ($row->$modeSpec['kolom'] == $modeSpec['value'])) {
                                $addBr = $addKeterangan != NULL ? "<hr>" : "";
                                $l_result = "";
                                if (is_array($modeSpec['labels'])) {
                                    foreach ($modeSpec['labels'] as $l) {
                                        if ($l_result == "") {
                                            $l_result = "$addBr $mode by: " . formatField_he_format($l, $row->$l);
                                        }
                                        else {
                                            $l_result .= ", " . formatField_he_format($l, $row->$l);
                                        }
                                    }
                                }
                                else {
                                    $l_result = $modeSpec['labels'];
                                }
                                $mode_result .= $l_result;

                                //----------------------------------------
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $arrayHistory_keterangan[$ii] = array(
                                        "bgcolor" => "background-color:$bgcolor;color:$color;",
                                    );
                                }
                            }

                        }
                        $addKeterangan .= $mode_result;
                    }
                }
                // menambahkan keterangan fullfillment
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $arrayHistory_canceled[$ii] = array(
                        "bgcolor" => "background-color:orange;color:#000000;",
                    );
                }


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;


            }
        }
        //endregion


        //region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            //        if (in_array($this->configUi[$jenisTr]["steps"][1]['userGroup'], $this->session->login['membership'])) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link" => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode" => "showData",
            //            "mode" => "viewHistory",
            "isMobile" => $isMob,
            "jenisTr" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            "errMsg" => $this->session->errMsg,
            "title" => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle" => "histories",
            "customButton" => $customButton,
            "customButtonTarget" => isset($currentStateActive) ? "$currentStateActive?date1=$date1&date2=$date2&reqCode=$reqCode" : "",
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentStateActive) ? $currentStateActive : "all states",
            //            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            //            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "alternateLink" => "",
            "alternateLinkCaption" => "",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
            "limit" => $limit,
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }
}
