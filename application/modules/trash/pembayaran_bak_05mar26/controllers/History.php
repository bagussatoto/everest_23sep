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
    }

    public function index()
    {
    }

    public function viewHistory()
    {
        $batal = isset($_GET["cek"]) ? $_GET["cek"] : NULL;


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

        $dpakai = 0;
        //region lookup histories
        if ($dpakai == 1) {
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
            }
            if (sizeof($filters) > 0) {
                foreach ($filters as $kf => $vf) {
                    $tr->addFilter("$kf=$vf");
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
//                                        if (!isset($row->$k)) {
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


                    $tmp['keterangan'] = $addKeterangan;
                    $arrayHistory[$ii] = $tmp;
                    $arrayHistory_ids[$ii] = $tmp1;


                }
            }
        } // dipakai
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

        $this_methode = $this->uri->segment(3);

        if ($batal != NULL) {
            $getBatal = "&cek=$batal";
        }
        else {
            $getBatal = "";
        }


        $data = array(
            "mode" => $this_methode,
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
            // "arrayHistory"         => $arrayHistory,
            "conten_null" => MODUL_PATH . get_class($this) . "/showData/$jenisTr/$this_methode?date1=$date1&date2=$date2" . $getBatal,
            "arrayHistorySumField" => $sumValue,
            "arrayHistoryId" => $arrayHistory_ids,
            "action" => $action,
            "steps" => $steps,
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "addParams" => isset($_GET['addParams']) ? $_GET['addParams'] : null,
            "currentState" => isset($currentState) ? $currentState : "all states",
            "alternateLink" => MODUL_PATH . "/Transaksi/viewIncomplete/" . $this_methode,
            "alternateLinkCaption" => "incomplete " . $this->configUi[$jenisTr]["label"] . " <span class='glyphicon glyphicon-arrow-right'></span>",
            "addLink" => $addLink,
            "filters" => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this_methode . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "thisPage"             => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) ,

            "history_canceled" => isset($arrayHistory_canceled) ? $arrayHistory_canceled : array(),
            "history_keterangan" => isset($arrayHistory_keterangan) ? $arrayHistory_keterangan : array(),
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function showData()
    {
        session_write_close();

        $batal = isset($_GET["cek"]) ? $_GET["cek"] : NULL;

        session_write_close();
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
        $historyFieldsLink = isset($this->configUi[$jenisTr]['historyFieldsLink']) ? $this->configUi[$jenisTr]['historyFieldsLink'] : array();
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

        $historyRelasiLink = isset($this->configUi[$jenisTr]['historyRelasiLink']) ? $this->configUi[$jenisTr]['historyRelasiLink'] : array();


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
            $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
            $tr->addFilter("link_id='0'");
            $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");

        }
        else {
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $tr->addFilter("cabang_id='" . $this->placeId . "'");
                $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
                $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
                $tr->addFilter("link_id='0'");
                $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");

            }
            else {
                $tr->addFilter("cabang_id='" . $this->placeId . "'");
                $tr->addFilter("jenis_master='" . $this->jenisTr . "'");
                $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
                $tr->addFilter("link_id='0'");
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
            if (isset($_GET['date1'])) {
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");
            }

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
            // $this->db->where("fulldate>='" . $date1 . "'");
            // $this->db->where("fulldate<='" . $date2 . "'");
            if (isset($_GET['date1'])) {
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");
                $limit = "";
            }
        }

        //-------------------
        if ($batal != NULL) {
            $this->db->where("trash_4=0");

        }
        //-------------------


        $tmpHist = $tr->lookupHistories($jmlData, $limit, $page)->result();
        showLast_query("biru");

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

        $umpresoDatas = $this->get464Data();
        if ($jenisTr == "489") {
            $umsoDatas = $this->get466aso();
            $grnDatas = $this->get467Data();
            $soDatas = $this->get466Data();

        }
        else {
            $umsoDatas = array();
            $grnDatas = array();
            $soDatas = array();

        }


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

            $numb = 0;
            foreach ($tmpHist as $ii => $row) {

                $trans_id = $row->id;
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
                            // arrprint($eReg);
                            //                                                        cekLime($param);
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
//                                    if (!isset($row->$k)) {
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
                                            // data connecting
                                            if (($k1 != null) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    // arrPrint($eeReg);
                                                    if ($k1 == "details_id") {
                                                        $valDetail = $eeReg[$v1];

                                                        $id_master = $grnDatas[$valDetail]->id_master;
                                                        $row->details_id_master = $id_master;

                                                        $row->referensi_so_nomer = isset($soDatas[$id_master]) ? formatField_he_format("nomer", $soDatas[$id_master]->nomer) : "-";

                                                        $so_id = $soDatas[$id_master]->id;
                                                        $row->referensi_so_id = isset($soDatas[$id_master]) ? $so_id : "***";

                                                        $row->referensi_so__id_master = isset($umsoDatas[$id_master]) ? $umsoDatas[$id_master]["referensi_so__id_master"] : "**";
                                                        $row->referensi_so__label = isset($umsoDatas[$id_master]) ? $umsoDatas[$id_master]["referensi_so__label"] : "****";
                                                        $row->eFaktur_um = isset($umsoDatas[$id_master]) ? $umsoDatas[$id_master]["eFaktur"] : "-";

                                                        // $row->referensi_so__id_master_preum = isset($umpresoDatas[$id_master]) ? $umpresoDatas[$id_master]->referensi_so__id_master : "*/-";
                                                        // $row->referensi_so_preum = isset($umpresoDatas[$id_master]) ? $umpresoDatas[$id_master]->referensi_so : "/-";
                                                        // $row->eFaktur_preum = isset($umpresoDatas[$id_master]) ? "belum otorisasi $id_master" : "-";
                                                        // if(isset($umpresoDatas[$id_master])){
                                                        //     $row->eFaktur_um = "<r>belum otorisasi</r>";
                                                        // }

                                                        $id_466 = $umpresoDatas[$so_id]->id;
                                                        $row->referensi_so__id_master_preum = isset($umpresoDatas[$so_id]) ? $umpresoDatas[$so_id]->referensi_so__id_master : "*/-";
                                                        $row->referensi_so_preum = isset($umpresoDatas[$so_id]) ? $umpresoDatas[$so_id]->referensi_so : "/-";
                                                        $row->eFaktur_preum = isset($umpresoDatas[$so_id]) ? "belum otorisasi $id_master $so_id" : "-";
                                                        if (isset($umpresoDatas[$so_id])) {
                                                            $row->eFaktur_um = "<r title='$id_466'>belum otorisasi</r>";
                                                        }

                                                        $row->dateFaktur_um = isset($umsoDatas[$id_master]) ? date('d M Y', strtotime($umsoDatas[$id_master]["dateFaktur"])) : "-";
                                                        $row->dpp_um = isset($umsoDatas[$id_master]) ? $umsoDatas[$id_master]["extern_nilai2"] : "-";
                                                        $row->ppn_um = isset($umsoDatas[$id_master]) ? $umsoDatas[$id_master]["ppn"] : "-";

                                                        $nomer_top = $grnDatas[$valDetail]->nomer_top;
                                                        $row->details_id_top = formatField_he_format("nomer", $nomer_top);
                                                        // $row->details_id_top = $nomer_top;
                                                    }
                                                    else {

                                                        $valDetail = formatField_he_format("nomer", $eeReg[$v1]);
                                                    }
                                                    // $valDetail = formatField("nomer", $eeReg[$v1]);
                                                    // $tmpDetail .= "<span>$valDetail</span><br>";
                                                    $tmpDetail .= "$valDetail";
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


//                    arrPrint($tmpReg_result[$row->id]["items"]);
                    foreach ($tmpReg_result[$row->id]["items"] as $iiiSpec) {
                        $arrCekBayarReq[$row->id] = array(
                            "id" => $iiiSpec["id"],
                            "nama" => $iiiSpec["nama"],
                        );
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
                            // cekHitam($kolomValue_0s);
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
                            // cekHitam(";; $fName");
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                        }
                        //endregion

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                    }

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
                                // cekHere(":: $getKey ::");
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

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }

                    if ($fName == "dateFaktur") {
                        // cekHijau("okok $fName  " . $row->dateFaktur );
                        $colValue = ((isset($row->dateFaktur)) && (($row->dateFaktur != NULL) || ($row->dateFaktur != 0))) ? date("d M Y", strtotime($row->dateFaktur)) : "-";
                    }

                    if (array_key_exists($fName, $historyFieldsLink)) {
//                        cekHere("[$fName] || " . $row->cash_account);
                        $sub_rek = $row->cash_account;
                        $sub_rek_nama = $row->cash_account__folders_nama . " " . $row->cash_account__nama;
                        $blob_ext = blobEncode($sub_rek_nama);
                        $date_1 = $row->fulldate;
                        $date_2 = $row->fulldate;
                        $link_mutasi = base_url() . $historyFieldsLink[$fName]['link'] . "$sub_rek?o=" . $this->session->login['cabang_id'] . "&main_ext2_id=$sub_rek&blob_ext=$blob_ext&date1=$date_1&date2=$date_2&trID=" . $row->id;
                        $modalDialog = modalDialogBtn('&nbsp;', $link_mutasi, $auto_close = 0, 'saldo');
                        $colValue = "<a href='javascript:void(0)' onclick=\"$modalDialog\" ttarget='_blank'>" . $row->$fName . "</a>";
                    }

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


                $tmp['keterangan'] = $addKeterangan;
                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;

                if ($batal != NULL) {
                    break;
                }
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

//        arrPrintCyan(getTargetTransaksi()[$this->jenisTr]);
        if (sizeof(getTargetTransaksi()[$this->jenisTr]) > 0) {
            $historyLink = getTargetTransaksi()[$this->jenisTr];
            foreach ($historyLink as $xx => $jenisTr_target) {
                $modul_history = $this->config->item("heTransaksi_ui")[$jenisTr_target]["modul"];
                $label_history = $this->config->item("heTransaksi_ui")[$jenisTr_target]["label"];
                $link_history = base_url() . "$modul_history/History/viewHistory/" . $jenisTr_target;
                if ($label_history != NULL) {
                    $arrHistoryLink[$jenisTr_target] = "<a href='$link_history'  target='_blank'>Klik untuk melihat history " . $label_history . "</a>";
//                    cekHere("[$jenisTr_target] [$label_history]");
                }
            }
        }


//        if (sizeof($historyRelasiLink) > 0) {
//            foreach ($historyRelasiLink as $xx => $xxSpec) {
//                $modul_history = $this->config->item("heTransaksi_ui")[$xxSpec["jenisTr"]]["modul"];
//                $link_history = base_url() . "$modul_history/History/viewHistory/" . $xxSpec["jenisTr"];
//                $arrHistoryLink[$xxSpec["jenisTr"]] = "<a href='$link_history'  target='_blank'>" . $xxSpec["label"] . "</a>";
//            }
//        }
//        arrPrintHitam($arrHistoryLink);

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
            "arrHistoryLink" => isset($arrHistoryLink) ? $arrHistoryLink : array(),
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;

        //----
        if ($batal != NULL) {
            foreach ($arrCekBayarReq as $tr_id_bayar => $dibayarSpec) {
                $tr_id_dibayar = $dibayarSpec["id"];
                $tr_nomer_dibayar = $dibayarSpec["nama"];
                $trr = New MdlTransaksi();
                $trr->addFilter("id='$tr_id_dibayar'");
                $trr->addFilter("trash_4='1'");
                $trrTmp = $trr->lookupAll()->result();
                if (sizeof($trrTmp) > 0) {

                }
            }
        }
        //----


        $this->load->view("history", $data);
    }

    public function get466aso()
    {
        $tbl_1 = "transaksi_payment_source";
        $tbl_2 = "transaksi_data_registry";
        // $condites = array(
        //     "jenis" => "464a",
        //     "label" => "ppn realisasi",
        // );
        // $this->db->where($condites);
        // $produkData = $this->db->get($tbl_1)->result();
        // showLast_query("hijau", count($produkData));
        //
        // $transPayments = array();
        // foreach ($produkData as $produkDatum) {
        //     $transaksi_id = $produkDatum->transaksi_id;
        //
        //     $transPayments[$transaksi_id] = $produkDatum;
        // }

        // cekHijau(count($transPayments));

        $koloms = array(
            "$tbl_1.*",
            "$tbl_2.main",
        );
        $condites = array(
            "$tbl_1.jenis" => "464a",

        );
        $this->db->select($koloms);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_2.transaksi_id = $tbl_1.transaksi_id");
        $this->db->where($condites);
        $query = $this->db->get();

        $produkData = $query->result();

        // showLast_query("pink", count($produkData));

        $kolom_regmain = array(
            "eFaktur",
            "dateFaktur",
            "referensi_so",             // id approve pre-po/po
            "referensi_so__label",
            "referensi_so__id_master", // id pre-po
            "referensi_so__fulldate",
        );
        $kolom_payment = array(
            "extern_nilai2", // dpp um
            "ppn", // ppn um
        );
        $transPayments = array();
        foreach ($produkData as $produkDatum) {
            // arrPrintKuning($produkDatum);
            $transaksi_id = $produkDatum->transaksi_id;
            $main = $produkDatum->main;
            $mains = blobDecode($main);
            // arrPrintHijau($mains);

            $eFaktur = $mains->eFaktur;
            $referensi_so = $mains->referensi_so;
            $regmains = array();
            foreach ($kolom_regmain as $kolom) {

                $regmains[$kolom] = isset($mains[$kolom]) ? $mains[$kolom] : 0;
            }
            foreach ($kolom_payment as $itemKolom) {
                $regmains[$itemKolom] = isset($produkDatum->$itemKolom) ? $produkDatum->$itemKolom : 0;
            }
            $transPayments[$mains['referensi_so__id_master']] = $regmains;
            // $transPayments[$transaksi_id] = $regmains;

        }

        // arrPrintPink($transPayments);

        return $transPayments;
    }

    public function get467Data($date1 = "", $date2 = "")
    {
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : $date1;
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : $date2;


        $tbl_1 = "transaksi";

        $koloms = array(
            "$tbl_1.id",
            "$tbl_1.id_master",
            "$tbl_1.nomer",
            "$tbl_1.nomer_top",
            // "$tbl_2.main",
        );
        $condites = array(
            "$tbl_1.jenis" => "467",
            // "$tbl_1.fulldate>=" => $date1,
            // "$tbl_1.fulldate<=" => $date2,

        );
        $this->db->select($koloms);
        $this->db->from($tbl_1);
        // $this->db->join($tbl_2, "$tbl_2.transaksi_id = $tbl_1.transaksi_id");
        $this->db->where($condites);
        $query = $this->db->get();

        $produkData = $query->result();
        // showLast_query("biru");

        foreach ($produkData as $produkDatum) {
            $datas[$produkDatum->id] = $produkDatum;
        }

        // arrPrintHijau($datas);

        return $datas;
    }

    public function get466Data($date1 = "", $date2 = "")
    {
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : $date1;
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : $date2;


        $tbl_1 = "transaksi";

        $koloms = array(
            "$tbl_1.id",
            "$tbl_1.id_master",
            "$tbl_1.nomer",
            "$tbl_1.nomer_top",
            // "$tbl_2.main",
        );
        $condites = array(
            "$tbl_1.jenis" => "466",
            "$tbl_1.link_id" => "0",
            // "$tbl_1.fulldate>=" => $date1,
            // "$tbl_1.fulldate<=" => $date2,

        );
        $this->db->select($koloms);
        $this->db->from($tbl_1);
        // $this->db->join($tbl_2, "$tbl_2.transaksi_id = $tbl_1.transaksi_id");
        $this->db->where($condites);
        $query = $this->db->get();

        $produkData = $query->result();
        // showLast_query("biru");

        foreach ($produkData as $produkDatum) {
            $datas[$produkDatum->id_master] = $produkDatum;
        }

        // arrPrintHijau($datas);

        return $datas;
    }

    public function get464Data($date1 = "", $date2 = "")
    {
        // $date1 = isset($_GET['date1']) ? $_GET['date1'] : $date1;
        // $date2 = isset($_GET['date2']) ? $_GET['date2'] : $date2;


        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data";
        $tbl_3 = "transaksi_data_registry";

        $koloms = array(
            "$tbl_1.id",
            "$tbl_1.id_master",
            "$tbl_1.nomer",
            "$tbl_1.nomer_top",
            "$tbl_1.jenis_top",
            "$tbl_1.status",
            "$tbl_1.next_step_code",
            "$tbl_2.valid_qty",
            "$tbl_2.produk_id",
            "$tbl_3.main",
        );
        $condites = array(
            "$tbl_1.jenis" => "464",
            "$tbl_1.link_id" => "0",
            "$tbl_1.next_step_code" => "464a",
            "$tbl_2.valid_qty>" => 0,

        );
        // $incondites = array(
        //     '464a',
        // );
        $this->db->select($koloms);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_2.transaksi_id = $tbl_1.id");
        $this->db->join($tbl_3, "$tbl_3.transaksi_id = $tbl_1.id");
        $this->db->where($condites);
        // $this->db->where_in($incondites);
        $query = $this->db->get();

        $produkData = $query->result();
        // showLast_query("biru", count($produkData));
        $kolom_regmain = array(
            // "eFaktur",
            // "dateFaktur",
            "referensi_so",             // id approve pre-po/po
            "referensi_so__label",
            "referensi_so__id_master", // id pre-po
            "referensi_so__fulldate",
        );
        foreach ($produkData as $produkDatum) {
            $main = $produkDatum->main;
            $mains = blobDecode($main);
            $regmains = array();
            foreach ($kolom_regmain as $kolom) {

                $produkDatum->$kolom = isset($mains[$kolom]) ? $mains[$kolom] : 0;
            }
            $datas[$produkDatum->referensi_so] = $produkDatum;
            // $datas[$produkDatum->referensi_so__id_master] = $produkDatum;
            // $datas[$produkDatum->id_master] = $produkDatum;
        }

        // arrPrintHijau($datas);

        return $datas;
    }
}
