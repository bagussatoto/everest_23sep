<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class History extends Modul_Controller
{
    public function __construct()
    {
        // START OF COMPLETE REPEATED LOGIC
        parent::__construct();
        $this->load->model("Mdls/MdlCurrency");
        $this->load->model("MdlTransaksi");
        /* ----------------------------------------------------------------------------------
          * loader cunstruk yg wajib ada
          * variabel-variabel bisa langsung dipangil, apa saja yang ada bisa dilihat didalamnya
          * ----------------------------------------------------------------------------------*/
        // require_once "_construct_file.php";
        $this->configUiModul = loadConfigUiModul_he_misc();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->bgTransaksiColor = bgTransaksiColor();
        // END OF COMPLETE REPEATED LOGIC
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
                "link_id"      => "0",
                "div_id"       => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
                    "seller_id"    => $this->session->login['id']

                );
            }
            else {
                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
                );
            }
            if (isset($_GET["cc"]) && ($_GET["cc"] == 1)) {
                $filters = array(
                    //                    "cabang_id" => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
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
                                "step"  => $hisSpec['step'],
                                "trID"  => $hisSpec['trID'],
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
                                "oleh_id"   => $histSpec->oleh_id,
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
                $row = $this->normalizeRowTotalAmount($row);
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
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode"                 => $this->uri->segment(3),
            //            "mode" => "viewHistory",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $this->configUi[$jenisTr]["label"],
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle"             => "histories",
            "customButton"         => $customButton,
            "customButtonTarget"   => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels"   => $historyFields,
            "arrayHistory"         => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId"       => $arrayHistory_ids,
            "action"               => $action,
            "steps"                => $steps,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled"   => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
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
            "diskon_nilai"    => "discount (IDR)",
            "ppn_nilai"       => "vat (IDR)",
            "transaksi_net"   => "netto (IDR)",
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
                $row = $this->normalizeRowTotalAmount($row);
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
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->config->item("heTransaksi_ui")[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode"                 => $this->uri->segment(3),
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $this->config->item("heTransaksi_ui")[$jenisTr]["label"],
            "errMsg"               => $this->session->errMsg,
            //            "title"                => $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " histories",
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle"             => "histories",
            //            "pageCount"            => $numPages,
            //            "page"                 => $page,
            //            "pages"                => $pages,
            "customButton"         => $customButton,
            "customButtonTarget"   => "$currentState/old?date1=$date1&date2=$date2",
            "arrayHistoryLabels"   => $historyFields,
            "arrayHistory"         => $arrayHistory,
            "arrayHistoryId"       => $arrayHistory_ids,
            "action"               => $action,
            "steps"                => $steps,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => MODUL_PATH . "Transaksi/viewIncomplete/" . $this->uri->segment(4),
            "alternateLinkCaption" => "incomplete " . $this->config->item("heTransaksi_ui")[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

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
                "link_id"      => "0",
                "div_id"       => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
                    "seller_id"    => $this->session->login['id']
                );
            }
            else {
                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
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
                                "step"  => $hisSpec['step'],
                                "trID"  => $hisSpec['trID'],
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
                                "oleh_id"   => $histSpec->oleh_id,
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
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        //endregion

        //region prepare params for viewer
        $data = array(
            "mode"                 => "showData",
            //            "mode" => "viewHistory",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $this->configUi[$jenisTr]["label"],
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : "riwayat " . $this->jenisTrName,
            "subTitle"             => "histories",
            "customButton"         => $customButton,
            "customButtonTarget"   => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels"   => $historyFields,
            "arrayHistory"         => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId"       => $arrayHistory_ids,
            "action"               => $action,
            "steps"                => $steps,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            //            "alternateLink" => base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $this->uri->segment(3),
            //            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "alternateLink"        => "",
            "alternateLinkCaption" => "",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),

            "history_canceled"   => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );
        //endregion

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function viewHistory()
    {
        session_write_close();
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
        $historyFieldsReplacer = isset($this->configUi[$jenisTr]['historyFieldsReplacer']) ? $this->configUi[$jenisTr]['historyFieldsReplacer'] : array();
        //arrPrint($extHistoryKeterangan);
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        $backdate_f = formatTanggal(backDate(15), 'Y-m-d');

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
                "link_id"      => "0",
                "div_id"       => $this->session->login['div_id'],
            );
        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                // $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
                    "seller_id"    => $this->session->login['id']

                );
            }
            else {

                $filters = array(
                    "cabang_id"    => $this->placeId,
                    "jenis_master" => $this->jenisTr,
                    "link_id"      => "0",
                    "div_id"       => $this->session->login['div_id'],
                );
            }

        }

        if (sizeof($filters) > 0) {
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
        if (isset($_GET['date1'])) {
            $limit = "";
        }
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();

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
                                "step"  => $hisSpec['step'],
                                "trID"  => $hisSpec['trID'],
                                "nomer" => $hisSpec['nomer'],
                            );
                            $arrTransHist[] = $hisSpec['trID'];
                        }
                    }
                }

                $tmpReg_result = array();
                $trReg = new MdlTransaksi();

                $trReg->setFilters(array());

                $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                $trReg->setJointSelectFields($selectKolom);
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupDataRegistries()->result();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        foreach ($pairRegistries as $param) {
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                        }
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            $referenceNumber_current_explode = substr($tmpReg_result[$regRow->transaksi_id]["main"]["referenceNumber_current"], 0, 4);
                            if ($referenceNumber_current_explode == "4464") {
                                //                            cekHijau("[$referenceNumber_current_explode]");
                                if (sizeof($historyFieldsReplacer) > 0) {
                                    foreach ($historyFieldsReplacer as $gate_hasil => $gate_sumber) {
                                        $tmpReg_result[$regRow->transaksi_id]["main"][$gate_hasil] = makeValue($gate_sumber, $tmpReg_result[$regRow->transaksi_id]["main"], $tmpReg_result[$regRow->transaksi_id]["main"], 0);
                                    }
                                }
                                foreach ($tmpReg_result[$regRow->transaksi_id]["items"] as $sub_ref_id => $item_params) {
                                    //                                    arrPrintPink($item_params);
                                    $gate = array("items");
                                    $selectKolom = implode(",", $gate) . ",transaksi_id";
                                    $trReg->setJointSelectFields($selectKolom);
                                    $trReg->setFilters(array());
                                    $trReg->addFilter("transaksi_id='$sub_ref_id'");
                                    $tmpReg_00 = $trReg->lookupDataRegistries()->result();
                                    if (sizeof($tmpReg_00) > 0) {
                                        foreach ($tmpReg_00 as $row) {
                                            foreach ($gate as $param) {
                                                //                                                $tmpReg_result[$regRow->transaksi_id]["items2"][$sub_ref_id] = blobDecode($row->$param);
                                                $tmpReg_result[$regRow->transaksi_id]["items2"][$item_params["nama"]] = blobDecode($row->$param);
                                            }
                                        }
                                    }
                                }
                            }
                            //                            arrPrintPink($tmpReg_result[$regRow->transaksi_id]["items2"]);
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
                                "oleh_id"   => $histSpec->oleh_id,
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

            $this->load->model("Mdls/MdlSetting");
            $st = new MdlSetting();
            $settingDatas = $st->callInvoicing();
            //arrPrint($tmpReg_result);
            //            matiHere();
            $allow_edit = $settingDatas->nilai;
            $linkEditorBilling = base_url() . "statik/Data/addBillForm/CustomerBillAddress";
            $this_url = MODUL_PATH . get_class($this) . "/viewHistory";
            $this_url_e = $this_url;
            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $transaksi_id = $row->id;
                $this->placeId = $cabang_id = $row->cabang_id;
                $supplier_id = $row->suppliers_id;
                $customer_id = $row->customers_id;

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

                    $show = false;
                    $detail = viewDetailTransaksi($tmpReg_result[$row->id], $arrItemShow, $row->jenis_master, $show);
                }

                //arrPrintWebs($row);
                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        $row->$alias = $row->$colom;
                    }
                }
                $row = $this->normalizeRowTotalAmount($row);
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

                            /* ----- -----------------------------------
                             * editor bill konsumen
                             * ------- -------------------------------*/
                            if ($allow_edit == true) {

                                $kolomValues .= "<a href='$linkEditorBilling?tr=$transaksi_id&id=$customer_id&jn=bill_invoice&tp=customer&reload=$this_url_e' data-toggle='modal' data-target='#myModal' class='text-red text-uppercase'>edit bill konsumen</a>";
                            }
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
                    $valas .= "<div class='btn-group' title='99'>";
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
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . $this->configUi[$jenisTr]["steps"][1]['label'],
            );
        }
        else {
            $addLink = null;
        }
        // $addLink = null;
        //endregion
        // arrPrintHijau(url_segment());
        // cekHere($this->jenisTrName);
        $data = array(
            "mode"                 => $this->uri->segment(3), // viewHistory
            //            "mode" => "viewHistory",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => $this->configUi[$jenisTr]["label"],
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) ? $subCodes[$currentState] : "riwayat" . $this->jenisTrName,
            "subTitle"             => "histories",
            "customButton"         => $customButton,
            "customButtonTarget"   => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels"   => $historyFields,
            "arrayHistory"         => $arrayHistory,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId"       => $arrayHistory_ids,
            "action"               => $action,
            "steps"                => $steps,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this->uri->segment(3),
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled"   => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
            "link_allow_edit"    => base_url() . "invoicing/" . get_class($this) . "/allowEdit",
            "allow_edit"         => $allow_edit,
            "bgTransaksiColor" => $this->bgTransaksiColor,
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function showData()
    {
        // START OF COMPLETE REPEATED LOGIC
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $starttime = microtime(true);
        $jenisTr = $this->jenisTr;
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = isset($this->configUi[$jenisTr]['steps']) ? $this->configUi[$jenisTr]['steps'] : array();
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : (isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array());
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $modul = isset($this->masterConfigUi[$jenisTr]["modul"]) ? $this->masterConfigUi[$jenisTr]["modul"] : false;
        $modul_path = base_url() . $modul . "/";

        $mb = new MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : $historyFields;
        }

        $date1 = isset($_GET['date1']) ? $_GET['date1'] : '';
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : '';

        // Preparing ERP step labels for top link
        $steps = isset($this->configUi[$jenisTr]['steps']) ? $this->configUi[$jenisTr]['steps'] : array();
        $stepLabels = array();
        $stepLinks = array();
        $subCodes = array();
        $stepCodes = array();
        if (sizeof($steps) > 1) {
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
                $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : (isset($this->configUi[$jenisTr]['steps'][1]['target']) ? $this->configUi[$jenisTr]['steps'][1]['target'] : null);
            }
        }
        else {
            $currentState = isset($this->configUi[$jenisTr]['steps'][1]['target']) ? $this->configUi[$jenisTr]['steps'][1]['target'] : null;
        }

        $action = array(
            "viewDetails" => base_url() . get_class($this) . "/viewDetails",
        );

        // Region link to add new transaction
        if (placeCanMakeTrans_he_menu($this->session->login['membership'], $this->session->login['cabang_id'], $this->session->login['gudang_id'], $this->jenisTr, $this->configUiJenis)) {
            $createIndexes = (null != $this->config->item("transaksi_createIndex")) ? $this->config->item("transaksi_createIndex") : array();
            if (array_key_exists($this->jenisTr, $createIndexes)) {
                $targetUrl = MODUL_PATH . $createIndexes[$this->jenisTr] . "/" . $this->jenisTr;
            }
            else {
                $targetUrl = MODUL_PATH . "Create/index/" . $this->jenisTr;
            }
            $addLink = array(
                "link"  => $targetUrl,
                "label" => "<span class='glyphicon glyphicon-plus'></span> create new " . (isset($this->configUi[$jenisTr]["steps"][1]['label']) ? $this->configUi[$jenisTr]["steps"][1]['label'] : ""),
            );
        }
        else {
            $addLink = null;
        }

        $data = array(
            "mode"                 => "showData",
            "isMobile"             => $isMob,
            "jenisTr"              => $jenisTr,
            "trName"               => isset($this->configUi[$jenisTr]["label"]) ? $this->configUi[$jenisTr]["label"] : "",
            "errMsg"               => $this->session->errMsg,
            "title"                => isset($subCodes) && isset($currentState) && isset($subCodes[$currentState]) ? $subCodes[$currentState] : $this->jenisTrName,
            "subTitle"             => "histories",
            "customButton"         => $customButton,
            "customButtonTarget"   => isset($currentState) ? "$currentState?date1=$date1&date2=$date2" : "",
            "arrayHistoryLabels"   => $historyFields,
            "arrayHistory"         => array(),
            "arrayHistorySumField" => array(),
            "arrayHistoryId"       => array(),
            "action"               => $action,
            "steps"                => $steps,
            "stepLabels"           => $stepLabels,
            "stepLinks"            => $stepLinks,
            "addParams"            => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState"         => isset($currentState) ? $currentState : "all states",
            "alternateLink"        => "",
            "alternateLinkCaption" => "",
            "addLink"              => $addLink,
            "filters"              => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"             => MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6),
            "history_canceled"     => array(),
            "history_keterangan"   => array(),
            "bgTransaksiColor"     => $this->bgTransaksiColor,
        );

        $this->load->view("history", $data);
        // END OF COMPLETE REPEATED LOGIC
    }

    public function showDataAjax()
    {
        // START OF COMPLETE REPEATED LOGIC
        if (!isset($this->session->login['id'])) {
            $response = array(
                "draw"            => 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => array(),
                "error"           => "Session expired",
            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }

        $this->load->model("MdlTransaksi");

        $jenisTr = $this->jenisTr;
        if (empty($jenisTr)) {
            $jenisTr = $this->uri->segment(4);
        }
        $jenisTrsub = $this->uri->segment(5);
        $cCode = $this->cCode;
        $allStep = isset($this->configUi[$jenisTr]['steps']) ? $this->configUi[$jenisTr]['steps'] : array();
        $availSteps = array();
        foreach ($allStep as $step => $tempStep) {
            $availSteps[$tempStep['target']] = $step;
        }
        $selectedSTep = isset($availSteps[$jenisTrsub]) ? $availSteps[$jenisTrsub] : 1;
        $historyFields = isset($this->configUi[$jenisTr]['historyFields'][$selectedSTep]) ? $this->configUi[$jenisTr]['historyFields'][$selectedSTep] : (isset($this->configUi[$jenisTr]['shortHistoryFields']) ? $this->configUi[$jenisTr]['shortHistoryFields'] : array());
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $historyFieldsReplacer = isset($this->configUi[$jenisTr]['historyFieldsReplacer']) ? $this->configUi[$jenisTr]['historyFieldsReplacer'] : array();

        $mb = new MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : $historyFields;
        }

        // DataTables parameters
        $draw = isset($_REQUEST['draw']) ? (int)$_REQUEST['draw'] : 1;
        $start = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;
        $length = isset($_REQUEST['length']) ? (int)$_REQUEST['length'] : 10;
        if ($length < 1) {
            $length = 10;
        }

        // START OF COMPLETE REPEATED LOGIC
        $searchValue = '';
        if (isset($_REQUEST['search']['value']) && trim($_REQUEST['search']['value']) !== '') {
            $searchValue = trim($_REQUEST['search']['value']);
        }
        elseif (isset($_REQUEST['search']) && is_string($_REQUEST['search']) && trim($_REQUEST['search']) !== '') {
            $searchValue = trim($_REQUEST['search']);
        }

        // 1. Decode HTML entities jika ada (misal &#x2011; atau &#8209;)
        if (!empty($searchValue)) {
            $searchValue = html_entity_decode($searchValue, ENT_QUOTES, 'UTF-8');
        }

        // 2. Normalisasi karakter khusus Unicode (non-breaking hyphen U+2011, en-dash, em-dash, non-breaking space)
        $searchValue = str_replace(
            array("\xE2\x80\x91", "\xE2\x80\x90", "\xE2\x80\x92", "\xE2\x80\x93", "\xE2\x80\x94", "\xE2\x80\x95", "\xC2\xA0", "&#x2011;", "&#8209;", "&minus;"),
            array("-", "-", "-", "-", "-", "-", " ", "-", "-", "-"),
            $searchValue
        );
        $searchValue = trim($searchValue);

        // 3. Transliterasi ke latin1/ASCII untuk mencegah 'Illegal mix of collations (latin1_swedish_ci,IMPLICIT)'
        if (!empty($searchValue) && function_exists('iconv')) {
            $transliterated = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $searchValue);
            if ($transliterated !== false && strlen($transliterated) > 0) {
                $searchValue = $transliterated;
            }
        }

        // 4. Bangun variasi kata kunci pencarian (search terms expansion)
        $searchTerms = array();
        if (!empty($searchValue)) {
            $searchTerms[] = $searchValue;

            // A. Deteksi jika ada akhiran counter global (misal "INV.1.3601.14-27397" atau "INV.1.3601.14 27397")
            $withoutCounter = '';
            if (preg_match('/^(.+?)[\-\s](\d{4,6})$/', $searchValue, $matches)) {
                if (isset($matches[1]) && strlen(trim($matches[1])) >= 2) {
                    $withoutCounter = trim($matches[1]);
                    $searchTerms[] = $withoutCounter;
                }
            }

            // B. Deteksi prefix tipe transaksi (misal "INV.1.3601.14" -> mapping "INV" => "4822" dan "1.3601.14")
            $candidates = array($searchValue);
            if (!empty($withoutCounter)) {
                $candidates[] = $withoutCounter;
            }

            $confNames = function_exists('arrConfName') ? arrConfName() : array();
            $revMap = array();
            if (is_array($confNames) && count($confNames) > 0) {
                foreach ($confNames as $kCode => $vLabel) {
                    $vLabelUpper = strtoupper(trim($vLabel));
                    if (!empty($vLabelUpper) && !isset($revMap[$vLabelUpper])) {
                        $revMap[$vLabelUpper] = $kCode;
                    }
                }
            }

            foreach ($candidates as $cand) {
                // Cek format prefix dengan titik, misal "INV.1.3601.14" atau "SO.1.3601.14"
                if (preg_match('/^([a-zA-Z0-9\-_]+)\.(.+)$/', $cand, $pMatches)) {
                    $prefix = strtoupper(trim($pMatches[1]));
                    $rest = trim($pMatches[2]);

                    // Term tanpa prefix (misal "1.3601.14")
                    if (strlen($rest) >= 2) {
                        $searchTerms[] = $rest;
                    }

                    // Term dengan prefix ditukar kode transaksi dari reverse map (misal "INV" -> "4822.1.3601.14")
                    if (isset($revMap[$prefix])) {
                        $searchTerms[] = $revMap[$prefix] . '.' . $rest;
                    }

                    // Term dengan jenisTr aktif modul saat ini (misal "4822.1.3601.14")
                    if (!empty($jenisTr) && $jenisTr !== $prefix) {
                        $searchTerms[] = $jenisTr . '.' . $rest;
                    }
                }
                else {
                    // Cek pola angka dengan titik langsung tanpa prefix teks (misal "1.3601.14")
                    if (!empty($jenisTr) && preg_match('/^\d+\.\d+/', $cand)) {
                        $searchTerms[] = $jenisTr . '.' . $cand;
                    }
                }

                // Cek format tanggal d/m/Y atau d-m-Y -> Y-m-d
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $cand, $dMatches)) {
                    $searchTerms[] = sprintf('%04d-%02d-%02d', $dMatches[3], $dMatches[2], $dMatches[1]);
                }
            }

            $cleanSearchTerms = array();
            foreach ($searchTerms as $st) {
                $stTrim = trim($st);
                if (function_exists('iconv')) {
                    $stAscii = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $stTrim);
                    if ($stAscii !== false && strlen($stAscii) > 0) {
                        $stTrim = $stAscii;
                    }
                }
                if (strlen($stTrim) >= 2 && !in_array($stTrim, $cleanSearchTerms)) {
                    $cleanSearchTerms[] = $stTrim;
                }
            }
            $searchTerms = $cleanSearchTerms;
        }

        if (isset($_REQUEST['stID']) && $_REQUEST['stID'] != '') {
            $currentState = $_REQUEST['stID'];
        }
        elseif (isset($_REQUEST['currentState']) && $_REQUEST['currentState'] != '' && $_REQUEST['currentState'] != 'all states') {
            $currentState = $_REQUEST['currentState'];
        }
        else {
            $currentState = strlen($this->uri->segment(5)) > 0 ? $this->uri->segment(5) : (isset($this->configUi[$jenisTr]['steps'][1]['target']) ? $this->configUi[$jenisTr]['steps'][1]['target'] : null);
        }

        // Build Base Filters
        $baseFilters = array();
        if (my_cabang_id() == "-1") {
            $baseFilters["transaksi.jenis_master"] = $jenisTr;
            $baseFilters["transaksi.link_id"] = "0";
            if (isset($this->session->login['div_id'])) {
                $baseFilters["transaksi.div_id"] = $this->session->login['div_id'];
            }
        }
        else {
            if (isset($this->session->login['employee_type']) && $this->session->login['employee_type'] == "employee_freelance") {
                $baseFilters["transaksi.cabang_id"] = $this->placeId;
                $baseFilters["transaksi.jenis_master"] = $jenisTr;
                $baseFilters["transaksi.link_id"] = "0";
                if (isset($this->session->login['div_id'])) {
                    $baseFilters["transaksi.div_id"] = $this->session->login['div_id'];
                }
                $baseFilters["transaksi.seller_id"] = $this->session->login['id'];
            }
            else {
                $baseFilters["transaksi.cabang_id"] = $this->placeId;
                $baseFilters["transaksi.jenis_master"] = $jenisTr;
                $baseFilters["transaksi.link_id"] = "0";
                if (isset($this->session->login['div_id'])) {
                    $baseFilters["transaksi.div_id"] = $this->session->login['div_id'];
                }
            }
        }

        if (isset($currentState) && $currentState != null) {
            $baseFilters["transaksi.jenis"] = $currentState;
        }

        $date1 = isset($_REQUEST['date1']) ? trim($_REQUEST['date1']) : '';
        $date2 = isset($_REQUEST['date2']) ? trim($_REQUEST['date2']) : '';

        // 1. Total records without search filter
        $this->db->from('transaksi');
        $this->db->where($baseFilters);
        $this->db->where('transaksi.status', '1');
        $this->db->where('transaksi.trash', '0');
        if (!empty($date1) && !empty($date2) && sizeof($searchTerms) == 0) {
            $this->db->where('transaksi.fulldate >=', $date1);
            $this->db->where('transaksi.fulldate <=', $date2);
        }
        $recordsTotal = $this->db->count_all_results();

        // 2. Filtered records with search keyword
        $this->db->from('transaksi');
        $this->db->where($baseFilters);
        $this->db->where('transaksi.status', '1');
        $this->db->where('transaksi.trash', '0');
        if (sizeof($searchTerms) > 0) {
            // Pencarian menyeluruh ke seluruh database tanpa batasan tanggal
            $this->db->group_start();
            $isFirst = true;
            foreach ($searchTerms as $term) {
                if ($isFirst) {
                    $this->db->like('transaksi.nomer', $term);
                    $isFirst = false;
                }
                else {
                    $this->db->or_like('transaksi.nomer', $term);
                }
                $this->db->or_like('transaksi.nomer_top', $term);
                $this->db->or_like('transaksi.nomer_new', $term);
                $this->db->or_like('transaksi.nomer2', $term);
                $this->db->or_like('transaksi.referensi_nomer', $term);
                $this->db->or_like('transaksi.extern_nomer', $term);
                $this->db->or_like('transaksi.customers_nama', $term);
                $this->db->or_like('transaksi.oleh_nama', $term);
                $this->db->or_like('transaksi.keterangan', $term);
                $this->db->or_like('transaksi.deskripsi', $term);
                $this->db->or_like('transaksi.fulldate', $term);
            }
            $this->db->group_end();
        }
        elseif (!empty($date1) && !empty($date2)) {
            $this->db->where('transaksi.fulldate >=', $date1);
            $this->db->where('transaksi.fulldate <=', $date2);
        }
        $recordsFiltered = $this->db->count_all_results();

        // 3. Fetch paginated data
        $this->db->select("*, transaksi.oleh_id as oleh_id, transaksi.oleh_nama as oleh_nama, transaksi.id as tid");
        $this->db->from('transaksi');
        $this->db->where($baseFilters);
        $this->db->where('transaksi.status', '1');
        $this->db->where('transaksi.trash', '0');
        if (sizeof($searchTerms) > 0) {
            // Pencarian menyeluruh ke seluruh database tanpa batasan tanggal
            $this->db->group_start();
            $isFirst = true;
            foreach ($searchTerms as $term) {
                if ($isFirst) {
                    $this->db->like('transaksi.nomer', $term);
                    $isFirst = false;
                }
                else {
                    $this->db->or_like('transaksi.nomer', $term);
                }
                $this->db->or_like('transaksi.nomer_top', $term);
                $this->db->or_like('transaksi.nomer_new', $term);
                $this->db->or_like('transaksi.nomer2', $term);
                $this->db->or_like('transaksi.referensi_nomer', $term);
                $this->db->or_like('transaksi.extern_nomer', $term);
                $this->db->or_like('transaksi.customers_nama', $term);
                $this->db->or_like('transaksi.oleh_nama', $term);
                $this->db->or_like('transaksi.keterangan', $term);
                $this->db->or_like('transaksi.deskripsi', $term);
                $this->db->or_like('transaksi.fulldate', $term);
            }
            $this->db->group_end();
        }
        elseif (!empty($date1) && !empty($date2)) {
            $this->db->where('transaksi.fulldate >=', $date1);
            $this->db->where('transaksi.fulldate <=', $date2);
        }
        // END OF COMPLETE REPEATED LOGIC

        // Sorting mapping
        $availCols = array_keys($historyFields);
        $orderColIdx = isset($_REQUEST['order'][0]['column']) ? (int)$_REQUEST['order'][0]['column'] : null;
        $orderDir = isset($_REQUEST['order'][0]['dir']) && strtolower($_REQUEST['order'][0]['dir']) === 'asc' ? 'asc' : 'desc';

        $safeDbSortCols = array(
            'dtime'            => 'dtime',
            'customers_nama'   => 'customers_nama',
            'nomer_top'        => 'nomer_top',
            'nomer'            => 'nomer',
            'oleh_nama'        => 'oleh_nama',
            'jual'             => 'jual',
            'disc'             => 'disc',
            'ppn'              => 'ppn',
            'grand_pembulatan' => 'grand_pembulatan',
            'id'               => 'id',
        );

        if ($orderColIdx !== null && isset($availCols[$orderColIdx])) {
            $reqColName = $availCols[$orderColIdx];
            if (isset($safeDbSortCols[$reqColName])) {
                $this->db->order_by('transaksi.' . $safeDbSortCols[$reqColName], $orderDir);
            }
            else {
                $this->db->order_by('transaksi.id', 'desc');
            }
        }
        else {
            $this->db->order_by('transaksi.id', 'desc');
        }

        $this->db->limit($length, $start);
        $tmpHist = $this->db->get()->result();

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
        $dataRows = array();

        if (sizeof($tmpHist) > 0) {
            $tmpReg_result = array();
            $arrSalesName = array();
            $arrTransMainHist = array();

            if (sizeof($pairRegistries) > 0) {
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIdsHist = array();
                $arrTransHist = array();

                foreach ($tmpHist as $row) {
                    $arrTransID[] = $row->id;
                    $arrTransTopID[] = $row->id_top;

                    if ($row->ids_his != "") {
                        $hist = blobDecode($row->ids_his);
                        if (is_array($hist)) {
                            foreach ($hist as $hisSpec) {
                                if (isset($hisSpec['step']) && isset($hisSpec['trID'])) {
                                    $arrIdsHist[$row->id][$hisSpec['step']] = array(
                                        "step"  => $hisSpec['step'],
                                        "trID"  => $hisSpec['trID'],
                                        "nomer" => isset($hisSpec['nomer']) ? $hisSpec['nomer'] : '',
                                    );
                                    $arrTransHist[] = $hisSpec['trID'];
                                }
                            }
                        }
                    }
                }

                if (sizeof($arrTransID) > 0) {
                    $trReg = new MdlTransaksi();
                    $trReg->setFilters(array());
                    $selectKolom = implode(",", $pairRegistries) . ",transaksi_id";
                    $trReg->setJointSelectFields($selectKolom);
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupDataRegistries()->result();
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            foreach ($pairRegistries as $param) {
                                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->$param);
                            }
                            if (isset($tmpReg_result[$regRow->transaksi_id]["main"]["referenceNumber_current"])) {
                                $referenceNumber_current_explode = substr($tmpReg_result[$regRow->transaksi_id]["main"]["referenceNumber_current"], 0, 4);
                                if ($referenceNumber_current_explode == "4464") {
                                    if (sizeof($historyFieldsReplacer) > 0) {
                                        foreach ($historyFieldsReplacer as $gate_hasil => $gate_sumber) {
                                            $tmpReg_result[$regRow->transaksi_id]["main"][$gate_hasil] = makeValue($gate_sumber, $tmpReg_result[$regRow->transaksi_id]["main"], $tmpReg_result[$regRow->transaksi_id]["main"], 0);
                                        }
                                    }
                                    if (isset($tmpReg_result[$regRow->transaksi_id]["items"]) && is_array($tmpReg_result[$regRow->transaksi_id]["items"])) {
                                        foreach ($tmpReg_result[$regRow->transaksi_id]["items"] as $sub_ref_id => $item_params) {
                                            $gate = array("items");
                                            $selectKolom2 = implode(",", $gate) . ",transaksi_id";
                                            $trReg->setJointSelectFields($selectKolom2);
                                            $trReg->setFilters(array());
                                            $trReg->addFilter("transaksi_id='$sub_ref_id'");
                                            $tmpReg_00 = $trReg->lookupDataRegistries()->result();
                                            if (sizeof($tmpReg_00) > 0) {
                                                foreach ($tmpReg_00 as $rowItemReg) {
                                                    foreach ($gate as $param) {
                                                        $tmpReg_result[$regRow->transaksi_id]["items2"][$item_params["nama"]] = blobDecode($rowItemReg->$param);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (sizeof($arrTransTopID) > 0) {
                    $cleanTopIds = array_filter($arrTransTopID);
                    if (sizeof($cleanTopIds) > 0) {
                        $trTop = new MdlTransaksi();
                        $trTop->addFilter("id in ('" . implode("','", $cleanTopIds) . "')");
                        $tmpTrTop = $trTop->lookUpMainTransaksi()->result();
                        if (sizeof($tmpTrTop) > 0) {
                            foreach ($tmpTrTop as $topSpec) {
                                $arrSalesName[$topSpec->id_top] = $topSpec->oleh_nama;
                            }
                        }
                    }
                }

                if (sizeof($arrTransHist) > 0) {
                    $cleanTransHist = array_filter($arrTransHist);
                    if (sizeof($cleanTransHist) > 0) {
                        $trHistModel = new MdlTransaksi();
                        $trHistModel->addFilter("id in ('" . implode("','", $cleanTransHist) . "')");
                        $tmpTransHist = $trHistModel->lookUpMainTransaksi()->result();

                        $tmpTransHist_result = array();
                        if (sizeof($tmpTransHist) > 0) {
                            foreach ($tmpTransHist as $histSpec) {
                                $tmpTransHist_result[$histSpec->id] = array(
                                    "oleh_id"   => $histSpec->oleh_id,
                                    "oleh_nama" => $histSpec->oleh_nama,
                                );
                            }
                        }

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
            }

            $numb = $start;
            foreach ($tmpHist as $ii => $row) {
                $this->placeId = $cabang_id = $row->cabang_id;
                $salesName = isset($arrSalesName[$row->id_top]) ? $arrSalesName[$row->id_top] : "-";

                $tNomer_top = $row->nomer_top;
                $tNomer = $row->nomer;
                $arrExpTop = explode(".", $tNomer_top);
                $jenisTrtop = isset($arrExpTop[0]) ? $arrExpTop[0] : "";
                $arrExpNom = explode(".", $tNomer);
                $jenisTrsub = isset($arrExpNom[0]) ? $arrExpNom[0] : "";
                $counterjenis = "$jenisTrsub|" . $this->placeId;

                $counterIds_his_raw = blobDecode($row->ids_his);
                $counterIds_his = isset($counterIds_his_raw[1]['counters']) ? blobDecode($counterIds_his_raw[1]['counters']) : array();
                $counters = blobDecode($row->counters);

                $counterGlobal = isset($counters['stepCode|placeID'][$counterjenis]) ? $counters['stepCode|placeID'][$counterjenis] : 0;
                $counterIds_his_global = isset($counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"]) ? $counterIds_his['stepCode|placeID']["$jenisTrtop|$cabang_id"] : "";
                $cGlobals = digit_5($counterGlobal);
                $cGlobal_spo = digit_5($counterIds_his_global);

                if (sizeof($pairRegistries) > 0) {
                    if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                        foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                            if ($param == "main" && is_array($eReg)) {
                                foreach ($eReg as $k => $v) {
                                    if (($k != null) && !isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                            }
                            else {
                                if (sizeof($extHistoryFields2) > 0 && is_array($eReg)) {
                                    foreach ($extHistoryFields2 as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $kolom = $v1['kolom'];
                                            $format = $v1['format'];
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    if (isset($eeReg[$kolom])) {
                                                        $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    if (isset($eeReg[$v1])) {
                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                        $tmpDetail .= "<span>$valDetail</span><br>";
                                                    }
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (sizeof($pairTransaksi) > 0 && isset($row->referenceID) && $row->referenceID > 0) {
                        $trPair = new MdlTransaksi();
                        $trPair->addFilter("id='" . $row->referenceID . "'");
                        $trPairTmp = $trPair->lookupMainTransaksi()->result();
                        if (sizeof($trPairTmp) > 0) {
                            $hisTr = isset($trPairTmp[0]->ids_his) ? blobDecode($trPairTmp[0]->ids_his) : array();
                            if (is_array($hisTr)) {
                                foreach ($hisTr as $step => $hisTrSpec) {
                                    if (isset($pairTransaksi['kolom']) && is_array($pairTransaksi['kolom'])) {
                                        foreach ($pairTransaksi['kolom'] as $keyPair => $labelPair) {
                                            $keyPairs = $keyPair . "_" . $step;
                                            $row->$keyPairs = isset($hisTrSpec[$labelPair]) ? $hisTrSpec[$labelPair] : "--";
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $arrItemShow = isset($this->configUi[$jenisTr]["shortItemsFields"]) ? $this->configUi[$jenisTr]["shortItemsFields"] : array();
                    $show = false;
                    $regForDetail = isset($tmpReg_result[$row->id]) ? $tmpReg_result[$row->id] : array();
                    if (!isset($regForDetail["items2"])) {
                        $regForDetail["items2"] = array();
                    }
                    if (!isset($regForDetail["items"])) {
                        $regForDetail["items"] = array();
                    }
                    $detail = viewDetailTransaksi($regForDetail, $arrItemShow, $row->jenis_master, $show);
                }
                else {
                    $detail = "-";
                }

                if (sizeof($historyFieldsExt) > 0) {
                    foreach ($historyFieldsExt as $alias => $colom) {
                        if (isset($row->$colom)) {
                            $row->$alias = $row->$colom;
                        }
                    }
                }
                $row = $this->normalizeRowTotalAmount($row);
                $tmp = array();

                $numb++;
                foreach ($historyFields as $fName => $fLabel) {
                    if (strpos($fName, '+') !== false) {
                        $chars = explode("+", $fName);
                        $colValue = "";
                        foreach ($chars as $key) {
                            $colValue .= isset($row->$key) ? formatField_he_format($key, $row->$key) . "<br>" : "";
                        }
                        $colValue = rtrim($colValue, "<br>");
                    }
                    else {
                        if ($fName == "nomer") {
                            switch ($jenisTrsub) {
                                case "582s":
                                    $kolomValues = $row->$fName . "&#x2011;$cGlobals";
                                    break;
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
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        $colValue = isset($row->$fName) ? $kolomValues : "";
                    }

                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        if ($hisKey == "nomer") {
                            $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
                        }
                        else {
                            if (isset($row->transaksi_jenis2) && isset($fLabel['transaksi_jenis2'][$row->transaksi_jenis2])) {
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
                            $logistic = isset($main['oleh_nama']) ? $main['oleh_nama'] : "";
                        }
                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
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
                        $valas .= " <li class='text-bold'><a class='dropdown-item' href='javascript:void(0);' onclick=\"top.popBig('" . MODUL_PATH . "Printing/viewReceipt/" . $this->jenisTr . "/$nomer?type=" . blobEncode($nama) . "&f=" . blobEncode($arrV->exchange) . "')\"> <i class='fa fa-print'></i> in $nama - ($nilai) </a></li>";
                    }
                    $valas .= "</ul>";
                    $valas .= "</div>";
                    $tmp["print_nvalas"] = $valas;
                }

                $tmp['item_fields'] = $detail;
                $tmp['next_pic'] = "-";

                // Styling row (canceled, edited, fulfillment)
                $rslt_style = "";
                $addKeterangan = "";
                if (isset($row->trash_4) && ($row->trash_4 == 1)) {
                    $addKeterangan = "CANCELED";
                    $addKeterangan .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $rslt_style = "background-color:red !important;color:#ffc107;";
                }
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
                                if (isset($modeSpec['style'])) {
                                    $color = $modeSpec['style']['color'];
                                    $bgcolor = $modeSpec['style']['bgcolor'];
                                    $rslt_style = "background-color:$bgcolor;color:$color;";
                                }
                            }
                        }
                        $addKeterangan .= $mode_result;
                    }
                }
                if (isset($row->fullfillment_id) && ($row->fullfillment_id > 0)) {
                    $addKeterangan = "CLOSE/FULLFILLMENT ";
                    $addKeterangan .= $row->fullfillment_dtime != NULL ? nl2br(" date: " . $row->fullfillment_dtime) : "";
                    $addKeterangan .= $row->fullfillment_oleh_nama != NULL ? nl2br(" by: " . $row->fullfillment_oleh_nama) : "";
                    $rslt_style = "background-color:orange;color:#000000;";
                }

                $tmp['keterangan'] = $addKeterangan;

                // Build row for DataTables
                $rowItem = array(
                    "DT_RowId" => "tr" . $row->id,
                );
                if (!empty($rslt_style)) {
                    $rowItem["DT_RowAttr"] = array("style" => $rslt_style);
                }
                $cIdx = 0;
                foreach ($historyFields as $fName => $fLabel) {
                    $rowItem[$cIdx] = isset($tmp[$fName]) ? $tmp[$fName] : "";
                    $cIdx++;
                }
                $dataRows[] = $rowItem;
            }
        }

        $response = array(
            "draw"            => $draw,
            "recordsTotal"    => (int)$recordsTotal,
            "recordsFiltered" => (int)$recordsFiltered,
            "data"            => $dataRows,
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
        // END OF COMPLETE REPEATED LOGIC
    }

    public function authUserForm()
    {
        arrPrint($_REQUEST);
        arrPrintPink(url_segment());
        $trid = url_segment(5);
        $link_post = MODUL_PATH . get_class($this) . "/doAuthUserOtp";
        $var = "";
        // $var .= "<form method='post' enctype='application/x-www-form-urlencoded' action='$link_post' target='_blank'>";
        $var .= "<form method='post' action='$link_post' target='result'>";
        $var .= "<div class='input-group input-group-sm'>";
        $var .= "<input type='password' name='password' class='form-control' required placeholder='masukan otp'>";
        $var .= "<input type='hidden' name='trid' class='form-control' value='$trid'>";
        $var .= "<span class='input-group-btn'><button type='submit' class='btn btn-danger'>Simpan</button></span>";
        $var .= "</div>";
        $var .= "</form>";

        echo $var;
    }

    public function doAuthUserOtp()
    {
        $defPassword = md5(123456);

        arrPrintPink($_POST);
        $password = md5($_POST['password']);
        $trid = $_POST['trid'];
        cekBiru($password);
        if ($defPassword == $password) {
            cekHijau("cocok");

            $this->load->model("Mdls/MdlPrintLog");
            $pl = new MdlPrintLog();

            $pl->allowPrint($trid);

            topReload(1);
        }
        else {
            cekMerah("tidak cocok");
            // echo lgShowError("Tidak Valid", "OTP sudah tidak valid");
            matiHere("Tidak valid");
        }
    }

    /* ---------------------------------------------
     * editor pada histori billing detail
     * ---------------------------------------------*/
    public function allowEdit()
    {
        // arrPrint($_REQUEST);

        $this->load->model("Mdls/MdlSetting");
        $st = new MdlSetting();

        $this->db->trans_begin();

        $nilai_new = $st->updateInvoicing();

        $this->db->trans_complete();

        if ($nilai_new) {
            echo lgShowSuccess("Berhasil", "editor bill sudah dibuka");

            topReload();
        }
        else {
            echo lgShowWarning("Berhasil", "editor bill sudah ditutup");

            topReload();
        }
    }

    private function normalizeRowTotalAmount($row)
    {
        if (!isset($row->new_net3) || (is_numeric($row->new_net3) && (float)$row->new_net3 == 0)) {
            if (isset($row->grand_pembulatan) && (float)$row->grand_pembulatan > 0) {
                $row->new_net3 = $row->grand_pembulatan;
            } elseif (isset($row->grandTotal) && (float)$row->grandTotal > 0) {
                $row->new_net3 = $row->grandTotal;
            } elseif (isset($row->tagihan) && (float)$row->tagihan > 0) {
                $row->new_net3 = $row->tagihan;
            } else {
                $nett1 = isset($row->nett1) ? (float)$row->nett1 : 0;
                $grand_ppn = isset($row->grand_ppn) ? (float)$row->grand_ppn : (isset($row->ppn) ? (float)$row->ppn : 0);
                $ongkir = isset($row->ongkir) ? (float)$row->ongkir : 0;
                $calcTotal = $nett1 + $grand_ppn + $ongkir;
                if ($calcTotal > 0) {
                    $row->new_net3 = $calcTotal;
                }
            }
        }
        return $row;
    }
}
