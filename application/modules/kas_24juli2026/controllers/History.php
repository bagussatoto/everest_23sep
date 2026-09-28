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
        $shortHistoryFieldsReplacer = isset($this->configUi[$jenisTr]['shortHistoryFieldsReplacer']) ? $this->configUi[$jenisTr]['shortHistoryFieldsReplacer'] : array();
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
//                            $referenceNumber_current_explode = substr($tmpReg_result[$regRow->transaksi_id]["main"]["referenceNumber_current"], 0, 4);
//                            if ($referenceNumber_current_explode == "4464") {
//                                if (sizeof($historyFieldsReplacer) > 0) {
//                                    foreach ($historyFieldsReplacer as $gate_hasil => $gate_sumber) {
//                                        $tmpReg_result[$regRow->transaksi_id]["main"][$gate_hasil] = makeValue($gate_sumber, $tmpReg_result[$regRow->transaksi_id]["main"], $tmpReg_result[$regRow->transaksi_id]["main"], 0);
//                                    }
//                                }
                            foreach ($tmpReg_result[$regRow->transaksi_id]["items"] as $sub_ref_id => $item_params) {
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
//                            }

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
                // arrPrintHijau($row);
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
//                                    if (!isset($row->$k)) {
                                    if ($k != NULL) {
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
                                            if (($k1 != NULL) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, $eeReg[$kolom]);
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != NULL) && !isset($row->$k1)) {
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
                            if (isset($row->$fName) && (is_numeric($row->$fName)) && ($row->$fName < 0)) {
                                $kolomValues = 0;
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
                        if (isset($fLabel["key_his"])) {
                            if (is_array($fLabel["key_his"])) {
                                foreach ($fLabel["key_his"] as $key_his) {
                                    $data = isset($row->$key_his) ? $row->$key_his : NULL;
                                    if ($data != NULL) {
                                        $format = isset($fLabel["format"]) ? $fLabel["format"] : $data;
                                        if (isset(uangMukaLabel()[$data])) {
                                            $colValue = formatField_he_format($format, uangMukaLabel()[$data]);
                                        }
                                        else {
                                            $colValue = formatField_he_format($format, $data);
                                        }
                                        break;
                                    }
                                }
                            }
                            else {
                                $data = isset($row->$fLabel["key_his"]) ? $row->$fLabel["key_his"] : "";
                                $format = isset($fLabel["format"]) ? $fLabel["format"] : $data;
                                $colValue = isset(uangMukaLabel()[$data]) ? formatField_he_format($format, uangMukaLabel()[$data]) : $data;
                                if (isset(uangMukaLabel()[$data])) {
                                    $colValue = formatField_he_format($format, uangMukaLabel()[$data]);
                                }
                                else {
                                    if (isset($fLabel["no_key_his"])) {
                                        $colValue = formatField_he_format($format, $fLabel["no_key_his"]);
                                    }
                                    else {
                                        $colValue = formatField_he_format($format, $data);
                                    }
                                }
                            }
                        }
                        else {
                            if (isset($fLabel["no_key_his"])) {
                                $data = $fLabel["no_key_his"];
                                $format = isset($fLabel["format"]) ? $fLabel["format"] : $data;
                                $colValue = formatField_he_format($format, $data);
                            }
                        }
                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }
                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }
                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;

                    if ($fName == "cash_account__label") {
                        if ($row->fulldate < "2024-08-01") {
                            if (isset($row->uangMukaTanpaPpnSource) && ($row->uangMukaTanpaPpnSource > 0)) {
                                $tmp[$fName] = "Uang muka tanpa PPN";
                            }
                        }
                    }
                }
                //---------
                if (sizeof($shortHistoryFieldsReplacer) > 0) {
//                    arrPrint($shortHistoryFieldsReplacer);
                    foreach ($shortHistoryFieldsReplacer as $iii => $kspec) {
                        if ($row->$kspec["kolomCek"] === $kspec["paymentMethod"]) {
                            foreach ($kspec["kolom"] as $k_rep => $v_rep) {
                                $tmp[$k_rep] = isset($row->$v_rep) ? formatField_he_format($k_rep, $row->$v_rep) : "-";
                            }
                        }
                    }
                }
                //---------

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
                    $addKeterangan_0 = "CANCELED";
                    //                    $addKeterangan .= $row->cancel_dtime != NULL ? "&nl2br; date: " . $row->cancel_dtime : "";
                    //                    $addKeterangan .= $row->cancel_name != NULL ? "&nl2br; by: " . $row->cancel_name : "";
                    $addKeterangan_0 .= $row->cancel_dtime != NULL ? nl2br(" date: " . $row->cancel_dtime) : "";
                    $addKeterangan_0 .= $row->cancel_name != NULL ? nl2br(" by: " . $row->cancel_name) : "";
                    $addKeterangan_0 .= $row->deskripsi != NULL ? nl2br(" " . $row->deskripsi) : "";

                    $addKeterangan = $addKeterangan_0;
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

                // $tmp['keterangan'] = $addKeterangan;
                $tmp['status_tambahan'] = $addKeterangan;

                $arrayHistory[$ii] = $tmp;
                $arrayHistory_ids[$ii] = $tmp1;
            }
        }
        //endregion
        // arrPrintHijau($arrayHistory);
        $histories = array();
        foreach ($arrayHistory as $item) {
            $cash_account__label = str_replace(",", "", $item['cash_account__label']);

            $histories[$cash_account__label][] = $item;
        }
// arrPrintPink($history);
        /* ------------------------------------------------------------------------------
         * memanggil account cash in
         * ------------------------------------------------------------------------------*/
        $this->load->model("Mdls/MdlBankAccount_cash");
        $bai = new MdlBankAccount_cash();
        $srcbais = $bai->lookupAll()->result();
        foreach ($srcbais as $item) {
            $cashAccounts[$item->id] = (array)$item;
        }
        $this->load->model("Mdls/MdlBankAccount_cash_and_in");
        $bai = new MdlBankAccount_cash_and_in();
        $srcbais = $bai->lookupAll()->result();
        // arrPrintHijau($srcbais);
        foreach ($srcbais as $item) {
            $cashAccounts[$item->id] = (array)$item;
        }

        // arrPrintHijau($cashAccounts);

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
        $linkMenuHistory = isset($this->configUi[$jenisTr]['linkMenuHistory']) ? $this->configUi[$jenisTr]['linkMenuHistory'] : array();

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
            "histories" => $histories,
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
            "cashAccounts" => $cashAccounts,
            "linkMenuHistory" => $linkMenuHistory,
        );

        $endtime = microtime(true); // Bottom of page
        $valTimeEnd = $endtime - $starttime;
        //        cekBiru("load time start $starttime ||  end $endtime =>" . "$valTimeEnd");

        $this->load->view("history", $data);
    }

    public function showData()
    {
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
        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFieldsExt = isset($this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields"][$selectedSTep] : array();
        $extHistoryFields2 = isset($this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep]) ? $this->configUi[$jenisTr]["extHistoryFields2"][$selectedSTep] : array();
        $customButton = isset($this->configLayout[$jenisTr]["customButton"][$selectedSTep]) ? $this->configLayout[$jenisTr]["customButton"][$selectedSTep] : array();
        $printValas = isset($this->configLayout[$jenisTr]["print_nvalas"]) ? $this->configLayout[$jenisTr]["print_nvalas"] : array();
        $pairTransaksi = isset($this->configUi[$jenisTr]['pairTransaksi']) ? $this->configUi[$jenisTr]['pairTransaksi'] : array();
        $extHistoryKeterangan = isset($this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep]) ? $this->configUi[$jenisTr]['extHistoryKeterangan'][$selectedSTep] : array();
        $shortHistoryFieldsReplacer = isset($this->configUi[$jenisTr]['shortHistoryFieldsReplacer']) ? $this->configUi[$jenisTr]['shortHistoryFieldsReplacer'] : array();
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
                            $pakai_ini = 1;
                            if ($pakai_ini == 1) {
//                            $referenceNumber_current_explode = substr($tmpReg_result[$regRow->transaksi_id]["main"]["referenceNumber_current"], 0, 4);
//                            if ($referenceNumber_current_explode == "4464") {
//                                if (sizeof($historyFieldsReplacer) > 0) {
//                                    foreach ($historyFieldsReplacer as $gate_hasil => $gate_sumber) {
//                                        $tmpReg_result[$regRow->transaksi_id]["main"][$gate_hasil] = makeValue($gate_sumber, $tmpReg_result[$regRow->transaksi_id]["main"], $tmpReg_result[$regRow->transaksi_id]["main"], 0);
//                                    }
//                                }
                                foreach ($tmpReg_result[$regRow->transaksi_id]["items"] as $sub_ref_id => $item_params) {
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
//                            }

                            }
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
                            if ($param == "main") {
                                foreach ($eReg as $k => $v) {
//                                    if (!isset($row->$k) || ($row->$k == NULL)) {
                                    if ($k != NULL) {
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
                                            if (($k1 != NULL) && !isset($row->$k1)) {
                                                $tmpDetail = "";
                                                foreach ($eReg as $eeReg) {
                                                    $valDetail = formatField_he_format($format, (isset($eeReg[$kolom]) ? $eeReg[$kolom] : ''));
                                                    $tmpDetail .= "<span>$valDetail</span><br>";
                                                }
                                                $row->$k1 = $tmpDetail;
                                            }
                                        }
                                        else {

                                            if (($k1 != NULL) && !isset($row->$k1)) {
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
                            $kolomValues = str_replace("</span>", "&#x2011;" . $cGlobal_spo, $kolomValue_0s);
                        }
                        else {
//                                                        cekHitam(";; $fName -- " . $row->$fName);
                            $kolomValues = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : "-";
                            if (isset($row->$fName) && (is_numeric($row->$fName)) && ($row->$fName < 0)) {
                                $kolomValues = 0;
                            }
                        }
                        //endregion

                        // $colValue = isset($row->$fName) ? formatField($fName, $row->$fName) : "";

                        $colValue = isset($row->$fName) ? $kolomValues : "";
                        //                        cekLime("$colValue"." ".$fName);

                    }


                    if (is_array($fLabel)) {
                        if (is_array($fLabel["key_his"])) {
                            foreach ($fLabel["key_his"] as $key_his) {
                                $data = isset($row->$key_his) ? $row->$key_his : NULL;
                                if ($data != NULL) {
                                    $format = isset($fLabel["format"]) ? $fLabel["format"] : $data;
                                    if (isset(uangMukaLabel()[$data])) {
                                        $colValue = formatField_he_format($format, uangMukaLabel()[$data]);
                                    }
                                    else {
                                        $colValue = formatField_he_format($format, $data);
                                    }
                                    break;
                                }
                            }
                        }
                        else {
                            $hisStep = $fLabel['step'];
                            $hisKey = $fLabel['key'];
                            if ($hisKey == "nomer") {
                                $colValue = isset($row->ids_his) ? showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $this->jenisTr) : "";
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
                            if (isset($fLabel["key_his"])) {
                                $data = isset($row->$fLabel["key_his"]) ? $row->$fLabel["key_his"] : "";
                                $format = isset($fLabel["format"]) ? $fLabel["format"] : $data;
                                $colValue = isset(uangMukaLabel()[$data]) ? formatField_he_format($format, uangMukaLabel()[$data]) : $data;
                                if (isset(uangMukaLabel()[$data])) {
                                    $colValue = formatField_he_format($format, uangMukaLabel()[$data]);
                                }
                                else {
                                    if (isset($fLabel["no_key_his"])) {
                                        $colValue = formatField_he_format($format, $fLabel["no_key_his"]);
                                    }
                                    else {
                                        $colValue = formatField_he_format($format, $data);
                                    }
                                }
                            }
                        }

                        $logistic = "";
                        if (isset($arrTransMainHist[$row->id][$hisStep]['main'])) {
                            $main = $arrTransMainHist[$row->id][$hisStep]['main'];
                            $logistic = $main['oleh_nama'];
                        }
                    }

                    if ($fName == "no") {
                        $colValue = formatField_he_format($fName, $numb);
                    }


                    $tmp['logistic'] = isset($logistic) && $logistic != null ? $logistic : 'undefined';
                    $tmp['sales_name'] = $salesName;
                    $tmp[$fName] = $colValue;
                    $tmp1["id"] = $row->id;
//cekHere("[$fName] || $colValue");
                    if ($fName == "cash_account__label") {
                        if ($row->fulldate < "2024-08-01") {
                            if (isset($row->uangMukaTanpaPpnSource) && ($row->uangMukaTanpaPpnSource > 0)) {
                                $tmp[$fName] = "Uang muka tanpa PPN";
                            }
                        }
                    }
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
                //---------
                if (sizeof($shortHistoryFieldsReplacer) > 0) {
//                    arrPrint($shortHistoryFieldsReplacer);
                    foreach ($shortHistoryFieldsReplacer as $iii => $kspec) {
                        if ($row->$kspec["kolomCek"] === $kspec["paymentMethod"]) {
                            foreach ($kspec["kolom"] as $k_rep => $v_rep) {
                                $tmp[$k_rep] = isset($row->$v_rep) ? formatField_he_format($k_rep, $row->$v_rep) : "-";
                            }
                        }
                    }
                }
                //---------
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
                    $addKeterangan .= $row->deskripsi != NULL ? nl2br("<br>" . $row->deskripsi) : "";
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


                // $tmp['keterangan'] = $addKeterangan;
                $tmp['status_tambahan'] = $addKeterangan;
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
//arrPrint($arrayHistory);
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
}
