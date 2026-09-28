<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Create extends Modul_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* ----------------------------------------------------------------------------------
         * loader dari main CI
         * ----------------------------------------------------------------------------------*/
        //arrPrintWebs($this->session->login);
        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');

        $this->load->config("heWebs");
        $maintenanceTransaksi = $this->config->item("maintenanceTransaksi");
        $this->transaksiMaintenance = $maintenanceTransaksi != null && $maintenanceTransaksi == true ? true : false;
        $maintenanceOption = $this->config->item("maintenanceOptions");
        $this->transaksiMaintenanceMsg = isset($maintenanceOption[1]) ? $maintenanceOption[1] : array();

        $this->load->model("Mdls/MdlMongoMother");
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_data_registry",
        );

        $this->load->model("MdlTransaksi");
    }

    /* -------------------------------------------------------------------------------------
     * create_form ada di index
     * lanjut_ke -> preview -> save
     * -------------------------------------------------------------------------------------*/
    public function index()
    {
        $configUi = $this->configUi;
        $jenisTr = $this->jenisTr;

        $this->jenisTrName = isset($configUi[$this->jenisTr]['steps'][1]['label']) ? $configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";
        $this->allSteps = isset($configUi[$this->jenisTr]['steps']) ? $configUi[$this->jenisTr]['steps'] : array();
        $heTransaksi_ui = (null != $configUi) ? $configUi : array();
        if (sizeof($heTransaksi_ui) > 0) {
            $this->template = isset($heTransaksi_ui[$this->jenisTr]) ? base_url() . "template/" . $heTransaksi_ui[$this->jenisTr]['template'] . ".html" : "";
        }
        else {
            die("konfigurasi transaksi belum ditentukan @" . __LINE__);
        }

        // arrPrint($configUi);
        //        arrPrint($this->session->login);

        //===pageView options
        if (!isset($_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'])) {
            $viewModeConfig = isset($configUi[$this->jenisTr]['defaultViewMode']) ? $configUi[$this->jenisTr]['defaultViewMode'] : "list";
            $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'] = isset($_GET['viewMode']) ? $_GET['viewMode'] : $viewModeConfig;
        }
        $viewModeSpec = array(
            "icon" => "",
            "url" => "",
        );
        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3);
        switch ($_SESSION["opt_" . $this->jenisTr]['opt']['viewMode']) {
            case "list":
                $viewModeSpec = array(
                    "icon" => "	glyphicon glyphicon-th-large",
                    "url" => $thisPage . "?viewMode=thumbnail",
                );
                break;
            case "thumbnail":
                $viewModeSpec = array(
                    "icon" => "glyphicon glyphicon-align-justify",
                    "url" => $thisPage . "?viewMode=list",
                );
                break;
        }
        $viewModeSwitch = "<a href='javascript:void(0)' onclick=\"location.href='" . $viewModeSpec['url'] . "';\"><span class='" . $viewModeSpec['icon'] . "'></span></a>";


        // if (!isset($this->session->login['id'])) {
        //     redirect(base_url() . "Login");
        // }
        //        arrprint($this->uri->segment_array());
        //        arrprint($this->session->login);

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();


        $cCode = $this->cCode;
        if (isset($_SESSION[$cCode]['mode']) && array_key_exists("edit", $_SESSION[$cCode]['mode'])) {
            $selectedID = $_SESSION[$cCode]['mode']['edit'];
            $title = "";
            $subTitle = "Edit " . $this->configUi[$jenisTr]["steps"][1]['label'];
            //            cekHitam("biruuu");
        }
        elseif (isset($_SESSION[$cCode]['mode']) && array_key_exists("cancel", $_SESSION[$cCode]['mode'])) {
            $selectedID = $_SESSION[$cCode]['mode']['cancel'];
            $title = "" . $this->configUi[$jenisTr]["steps"][1]['label'];
            $subTitle = "" . $this->configUi[$jenisTr]["steps"][1]['label'];
        }
        else {
            $title = $this->configUi[$jenisTr]["label"];
            $subTitle = $this->configUi[$jenisTr]["steps"][1]['label'];
        }

        //-----------------------------------------------------
        $arrJenisTrCek = array("587", "687", "1587", "1687");
        if (in_array($jenisTr, $arrJenisTrCek)) {
            $transaksiName = isset($this->configUi[$jenisTr]['label']) ? $this->configUi[$jenisTr]['label'] : NULL;
            $subplace = isset($this->configUi[$jenisTr]['steps'][1]['subplace']) ? $this->configUi[$jenisTr]['steps'][1]['subplace'] : NULL;
            if ($subplace != NULL) {
                if (($subplace == "warehouse") && ($this->session->login['gudang_id'] < 0)) {
                    //                    cekPink("SESUAI");
                }
                elseif (($subplace == "warehouse_ng") && ($this->session->login['gudang_id'] > 0)) {
                    //                    cekPink("SESUAI NOT GOOD");
                }
                else {
                    $msg = "Anda tidak memiliki kewenangan untuk membuat request $transaksiName";
                    $alerts = array(
                        "type" => "warning",
                        "html" => "$msg",
                    );
                    echo swalAlert($alerts);
                    //                    echo toAlert($msg);
                    redirecResult(base_url() . get_class($this) . "/index/" . $this->uri->segment(3));
                }
            }
        }

        //-----------------------------------------------------


        /* ---------------------------------------------------------------------
        * nilai dikirim ke he_cart_helper
        * ---------------------------------------------------------------------*/
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "tokoID" => my_toko_id(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $configUi[$this->jenisTr]['steps'][1]['target'],
            "jenisTrName" => $configUi[$this->jenisTr]['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $configUi[$this->jenisTr]['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            "ppnFactor" => my_ppn_factor(),
        );

        heInitGates_he_cart($this->jenisTr, $initMasterValues);

        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $globalTemplate = "";
        $globalTemplate .= "<span>";
        $globalTemplateUrl = base_url() . "Selectors/_globalTemplate/globalTemplate/$cCode";
        if (isset($_SESSION[$cCode]['globalTemplate']) && $_SESSION[$cCode]['globalTemplate'] == 1) {
            $globalTemplate .= "
                <label class='radio-inline'>
                  <input type='radio' onchange=\"console.log('$globalTemplateUrl/0');top.$('#result').load('$globalTemplateUrl/0');\" name='globalTemplate'>Default
                </label>
                <label class='radio-inline'>
                  <input type='radio' onchange=\"console.log('$globalTemplateUrl/1');top.$('#result').load('$globalTemplateUrl/1');\" name='globalTemplate' checked>WideView
                </label>
                ";
        }
        else {
            $globalTemplate .= "
                <label class='radio-inline'>
                  <input type='radio' onchange=\"console.log('$globalTemplateUrl/0');top.$('#result').load('$globalTemplateUrl/0');\" name='globalTemplate' checked>Default
                </label>
                <label class='radio-inline'>
                  <input type='radio' onchange=\"console.log('$globalTemplateUrl/1');top.$('#result').load('$globalTemplateUrl/1');\" name='globalTemplate'>WideView
                </label>
                ";
        }
        $globalTemplate .= "</span>";
// arrPrint($_SESSION[$cCode]["main"]);
        // //         matiHere();

        $connectTo = isset($this->configUi[$jenisTr]['connectTo']) ? $this->configUi[$jenisTr]['connectTo'] : "";
        $stepHistoryFields = isset($this->configUi[$jenisTr]['shortStepHistoryFields']) ? $this->configUi[$jenisTr]['shortStepHistoryFields'] : array();


        $pairRegistries = isset($this->configUi[$jenisTr]['pairRegistries']) ? $this->configUi[$jenisTr]['pairRegistries'] : array();
        $historyFields = $this->configUi[$jenisTr]['shortHistoryFields'];
        $glanceHistoryFields = isset($this->configUi[$jenisTr]['glanceHistoryFields']) ? $this->configUi[$jenisTr]['glanceHistoryFields'] : array("nomer" => "receipt no");
        $itemAddConfig = isset($this->configUi[$jenisTr]['itemAddConfig']) ? $this->configUi[$jenisTr]['itemAddConfig'] : true;
        $glanceHistoryFields2 = array("nomer" => "receipt no"); //init;
        $selectorProcessor = MODUL_PATH . $this->configUi[$jenisTr]['selectorProcessor'] . "/$jenisTr";
        $uploadConfig = isset($this->configUi[$jenisTr]['uploadFields']) ? $this->configUi[$jenisTr]['uploadFields'] : array();
//                arrPrint($selectorProcessor);
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $historyFields = isset($this->configUi[$jenisTr]['compactHistoryFields']) ? $this->configUi[$jenisTr]['compactHistoryFields'] : array();
        }

        //region lookup on-going transactions
        $arrayOnprogress = array();
        $progressFields = $historyFields;
        $progressFields['state'] = "status";
        $progressFields['action'] = "action";
        $steps = $this->configUi[$jenisTr]['steps'];
        //endregion

        //region menambah supplier/customer (kalau berwenang)
        $pihakModel = isset($this->configUi[$this->jenisTr]['pihakModel']) ? $this->configUi[$this->jenisTr]['pihakModel'] : "";
        $pihakMainModel = isset($this->configUi[$this->jenisTr]['pihakModelMain']) ? $this->configUi[$this->jenisTr]['pihakModelMain'] : "";
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
                                        <a href='javascript:void(0)' title='add $dataLabel' data-toggle='tooltip' data-placement='right' class='btn btn-default' onclick=\"$addClick\">
                                            <span class='fa fa-user-plus'>                                            
                                             </span>
                                        </a>
                                </span>";
            }
        }
        //endregion

        //region menambah item/produk (kalau berwenang)
        $pihakModel = $this->configUi[$this->jenisTr]['selectorModel'];
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
        $addItemStr = "";
        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $pihakModel);
        //        arrprint($dataAccess['creators']);
        $relPihak = "?cCode=$cCode&pId=yes";
        // cekHere($relPihak);
        if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
            if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $dataLabel . "',
                                        message: $('<div></div>').load('" . $addLink . $relPihak . "'),
                                        draggable:true,
                                        closable:true,
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        }
                                        );";
                $addItemStr = "<span class='input-group-btn'>
                                        <a href='javascript:void(0)' title='add $dataLabel' data-toggle='tooltip' data-placement='right' class='btn btn-default' onclick=\"$addClick\">
                                            <span class='fa fa-plus-circle'>                                            
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
        $needToClear = false;
        //endregion


        //region incomplete step antar cabang
        $pakai_ini = 0;
        $arrayOnProgressView = array();
        if ($pakai_ini == 1) {

            $arrayOnProgressView = $this->viewIncompleteStepAntarCabang();
            if (sizeof($stepHistoryFields) > 0) {
                $stepHistoryFields['state'] = "status";
            }
        }
        //endregion


        //
        //region lookup histories
        $pakai_ini = 0;
        $arrayHistory = array();
        if ($pakai_ini == 1) {

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("transaksi.gudang_id='" . $this->session->login['gudang_id'] . "'");
            $tr->addFilter("transaksi.jenis_master='" . $this->jenisTr . "'");
            if ($this->session->login['employee_type'] == "employee_freelance") {
                $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
            }
            //        $tr->addFilter("transaksi.next_step_code=''");
            $tmpHist = $tr->lookupRecentHistories(5)->result();

            if (sizeof($tmpHist) > 0) {
                $arrTransID = array();
                $arrTransTopID = array();
                $arrIdsHist = array();
                $arrTransHist = array();
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
                $trReg->addFilter("param='main'");
                $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                $tmpReg = $trReg->lookupRegistries()->result();

                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $regRow) {
                        $param = $regRow->param;
                        $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
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

                    foreach ($arrIdsHist as $trID => $histSpec) {
                        foreach ($histSpec as $step => $detailSpec) {
                            if (array_key_exists($detailSpec['trID'], $tmpTransHist_result)) {
                                $detailSpec['main'] = $tmpTransHist_result[$detailSpec['trID']];
                            }
                            $arrTransMainHist[$trID][$step] = $detailSpec;
                        }
                    }
                }
                $numb = 0;
                foreach ($tmpHist as $row) {
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
                    }
                    $tmp = array();
                    $numb++;
                    foreach ($historyFields as $fName => $fLabel) {
                        if (is_array($fLabel)) {
                            $hisStep = $fLabel['step'];
                            $hisKey = $fLabel['key'];

                            if (isset($row->ids_his)) {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $tmp[$fName] = "-";
                            }
                        }
                        else {
                            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
                        }

                        if ($fName == "no") {
                            $tmp[$fName] = formatField($fName, $numb);
                        }
                    }
                    $arrayHistory[] = $tmp;
                }
            }
        }
        //endregion


        //region lookup histories (swapJenis)
        $pakai_ini = 0;
        $arrayHistoryR = array();
        if ($pakai_ini == 1) {
            $swapJenisTr = isset($this->configUi[$this->jenisTr]['requestCode']['masterCode']) ? $this->configUi[$this->jenisTr]['requestCode']['masterCode'] : array();
            if (sizeof($swapJenisTr)) {

                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();
                //            $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
                //            $tr->addFilter("transaksi.gudang_id='" . $this->session->login['gudang_id'] . "'");
                $tr->addFilter("transaksi.jenis_master='" . $swapJenisTr . "'");

                $tmpHist = $tr->lookupRecentHistories(5)->result();

                if (sizeof($tmpHist) > 0) {
                    $arrTransID = array();
                    $arrTransTopID = array();
                    $arrIdsHist = array();
                    $arrTransHist = array();
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
                    $trReg->addFilter("param='main'");
                    $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTransID) . "')");
                    $tmpReg = $trReg->lookupRegistries()->result();

                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $regRow) {
                            $param = $regRow->param;
                            $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
                        }
                    }
                    foreach ($tmpHist as $row) {
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
                        }
                        $tmp = array();
                        foreach ($historyFields as $fName => $fLabel) {
                            $tmp[$fName] = isset($row->$fName) ? formatField($fName, $row->$fName) : formatField($fName, 0);
                        }
                        $arrayHistoryR[] = $tmp;
                    }
                }
            }
        }

        //endregion
        //

        //cekHitam();
        $barcodeSettings = isset($this->configUi[$this->jenisTr]['barcodeSettings']) ? $this->configUi[$this->jenisTr]['barcodeSettings'] : array();

        //==mobile scan (if possible)
        $mobScanStr = sizeof($barcodeSettings) ? "<span class='input-group-btn'>
                                        <a href='javascript:void(0)' class='btn btn-default' onclick=\"getScan()\">
                                            <span class='glyphicon glyphicon-qrcode'>                                            
                                             </span>
                                        </a>
                                </span>" : "";


        //region external tool
        if (isset($this->configUi[$jenisTr]['extTool'])) {
            $extToolConfig = $this->configUi[$jenisTr]['extTool'];
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
            //arrPrint($rawParam);die();
            if (sizeof($rawParam) > 0) {
                $param = base64_encode(serialize($rawParam));
                $backUrl = base64_encode(serialize($backUrl));
                $extTool = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"window.open('" . $extToolConfig['url'] . "?param=$param&extern=" . $_SESSION[$cCode]['main'][$externSrc] . "&back=$backUrl','wTool');\" >" . $extToolConfig['label'] . "</a>";
            }
            else {
                $extTool = "<a class='btn btn-default' disabled>" . $extToolConfig['label'] . "</a>";
            }

        }
        else {
            //cekMerah("extTool doesnt exist");
            $extTool = "";
        }
        //endregion

        $allowTmpSave = isset($this->configUi[$jenisTr]['allowTmpSave']) ? $this->configUi[$jenisTr]['allowTmpSave'] : false;
        //region loader history
        $link_historyList = MODUL_PATH . "History/showData/$jenisTr/?limit=10";
        $scriptBottom = "";
        $scriptBottom .= "<script>
                   top.$('#historyList').load('$link_historyList')
         </script>
        ";
        //endregion
        //region prepare params to viewer

        $data = array(
            "mode" => "createForm",
            "modeedit" => isset($_SESSION[$cCode]['mode']) && array_key_exists("edit", $_SESSION[$cCode]['mode']) || isset($_SESSION[$cCode]['mode']) && array_key_exists("cancel", $_SESSION[$cCode]['mode']) ? "_off" : "",
            "modeeditopt" => isset($_SESSION[$cCode]['mode']) && array_key_exists("edit", $_SESSION[$cCode]['mode']) || isset($_SESSION[$cCode]['mode']) && array_key_exists("cancel", $_SESSION[$cCode]['mode']) ? "data-toggle='tooltip' data-placement='top' data-original-title='tidak bisa ganti CUSTOMER pada mode EDIT/CANCEL'" : "",
            "isMobile" => $isMob,
            "errMsg" => $this->session->errMsg,
            "globalTemplate" => isset($globalTemplate) ? $globalTemplate : "",
            "template" => MODUL_TEMPLATE_PATH . $this->configUi[$jenisTr]["template"],

            //            "title" => $this->configUi[$jenisTr]["label"],
            "title" => $title,
            //            "subTitle" => $this->configUi[$jenisTr]["steps"][1]['label'],
            "subTitle" => $subTitle,
            "jenisTr" => $jenisTr,
            "jenisTransaksi" => $jenisTr,
            "trName" => $this->configUi[$jenisTr]["label"],
            //            "selectorCaller" => base_url() . $this->modul . DIRECTORY_SEPARATOR . "_selectorItem/" . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel"],
            "selectorCaller" => isset($_SESSION[$cCode]['main']['pihakMdlName']) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $_SESSION[$cCode]['main']['pihakMdlName'] : base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel"],
            //            "selectorCaller" => base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["selectorCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel"],
            "selectorLabel" => $this->configUi[$jenisTr]["selectorLabel"],
            "selectorCaller2" => isset($this->configUi[$jenisTr]["selectorCaller2"]) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["selectorCaller2"] . "/$jenisTr/" . $this->configUi[$jenisTr]["selectorModel2"] : "",
            "selectorLabel2" => isset($this->configUi[$jenisTr]["selectorLabel2"]) ? $this->configUi[$jenisTr]["selectorLabel2"] : "",
            "selectorExternLabel" => isset($this->configUi[$jenisTr]["selectorLabel2"]) ? $this->configUi[$jenisTr]["selectorLabel2"] : "",
            "pihakCaller" => base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["pihakCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModel"],
            "pihakCaller2" => isset($this->configUi[$jenisTr]["pihakCaller2"]) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["pihakCaller2"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModel2"] : "",
            "pihakCaller3" => isset($this->configUi[$jenisTr]["pihakCaller3"]) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["pihakCaller3"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModel3"] : "",
            "pihakExternCaller" => isset($this->configUi[$jenisTr]["pihakExternCaller"]) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["pihakExternCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModelExtern"] : "",
            "pihakCallerDelete" => base_url() . $this->modul . DIRECTORY_SEPARATOR . "_processPihak/remove/$jenisTr",
            "pihakMainCallerDelete" => base_url() . $this->modul . DIRECTORY_SEPARATOR . "_processPihakMain/remove/$jenisTr",
            "pihakMainCallerRulesDelete" => base_url() . $this->modul . DIRECTORY_SEPARATOR . "_processPihakMainRules/remove/$jenisTr",
            //tambahan pihak rules misal ppn optional didepan
            "pihakMainCallerRules" => isset($this->configUi[$jenisTr]["pihakMainCallerRules"]) ? base_url() . $this->modul . DIRECTORY_SEPARATOR . $this->configUi[$jenisTr]["pihakMainCallerRules"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModelMainRules"] : "",
            "pihakMainLabelRules" => isset($this->configUi[$jenisTr]["pihakMainLabelRules"]) ? $this->configUi[$jenisTr]["pihakMainLabelRules"] : "",
            //tambahan pihak main
            "pihakMainCaller" => isset($this->configUi[$jenisTr]["pihakMainCaller"]) ? base_url() . $this->modul . "/" . $this->configUi[$jenisTr]["pihakMainCaller"] . "/$jenisTr/" . $this->configUi[$jenisTr]["pihakModelMain"] : "",
            "pihakMainLabel" => isset($this->configUi[$jenisTr]["pihakMainLabel"]) ? $this->configUi[$jenisTr]["pihakMainLabel"] : "",
            "pihakLabel" => isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] . "(" . $this->configUi[$jenisTr]["pihakLabel"] . ")" : $this->configUi[$jenisTr]["pihakLabel"],
            "pihakLabel2" => isset($_SESSION[$cCode]['main']['pihak2Name']) ? $_SESSION[$cCode]['main']['pihak2Name'] . "(" . $this->configUi[$jenisTr]["pihakLabel2"] . ")" : (isset($this->configUi[$jenisTr]["pihakLabel2"]) ? $this->configUi[$jenisTr]["pihakLabel2"] : ""),
            "pihakLabel3" => isset($_SESSION[$cCode]['main']['pihak3Name']) ? $_SESSION[$cCode]['main']['pihak3Name'] . "(" . $this->configUi[$jenisTr]["pihakLabel3"] . ")" : (isset($this->configUi[$jenisTr]["pihakLabel3"]) ? $this->configUi[$jenisTr]["pihakLabel3"] : ""),
            "pihakExternLabel" => isset($_SESSION[$cCode]['main']['pihakExternLabel']) ? $_SESSION[$cCode]['main']['pihakExternName'] . "(" . $this->configUi[$jenisTr]["pihakExternLabel"] . ")" : isset($this->configUi[$jenisTr]["pihakExternLabel"]) ? $this->configUi[$jenisTr]["pihakExternLabel"] : "",
            // history
            "arrayHistoryLabels" => $historyFields,
            "arrayHistory" => $arrayHistory + $arrayHistoryR,
            "arrayProgressLabels" => $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'] == "list" ? $progressFields : $glanceHistoryFields,
            "arrayProgress2Labels" => $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'] == "list" ? $progress2Fields : $glanceHistoryFields2,
            "arrayOnProgress" => $arrayOnprogress,
            "arrayOnProgress2" => $arrayOnprogress2,
            "reqFormTarget" => isset($reqFormTarget) ? $reqFormTarget : "",
            //            "strPaymentMethod" => $strPaymentMethod,
            "extTool" => $extTool,
            "columnRecorderTarget" => MODUL_PATH . get_class($this) . "/recordColumn/" . $this->jenisTr . "/",
            "defaultDescription" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "allowJoin" => isset($this->configUi[$jenisTr]["steps"][1]['allowJoin']) && $this->configUi[$jenisTr]["steps"][1]['allowJoin'] == true ? $this->configUi[$jenisTr]["steps"][1]['allowJoin'] : false,
            "allowTmpSave" => $allowTmpSave,
            "needToClear" => $needToClear,
            "addPihakStr" => $addPihakStr,
            "addItemStr" => $addItemStr,
            "mobScanStr" => $mobScanStr,
            "thisPage" => MODUL_PATH . get_class($this) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4),
            "viewMode" => $_SESSION["opt_" . $this->jenisTr]['opt']['viewMode'],
            "viewModeSwitch" => $viewModeSwitch,
            "barcodeSettings" => $barcodeSettings,
            "selectorProcessor" => $selectorProcessor,
            // ----------------------
            "onprogressViewTitle" => "<span class='fa fa-eye'></span> show incomplete step " . $this->configUi[$jenisTr]["label"],
            "onprogressViewSubTitle" => "<span class='text-black'>(daftar transaksi yang masih stanby di cabang tujuan)</span>",
            "arrayOnProgressView" => $arrayOnProgressView,
            "stepHistoryFields" => $stepHistoryFields,
            "uploadConfig" => $uploadConfig,
            "allSteps" => $this->allSteps,
            "submit_button_target" => $this->modul . "/Transaksi/validate/",
            "scriptBottom" => $scriptBottom,
        );
        //endregion

        $this->load->view("transaksi", $data);

    }

    public function preview()
    {

        // $this->jenisTr = $this->uri->segment(3);
        $jenisTr = $this->jenisTr;
        $cCode = $this->cCode;
        $configUi = $this->configUi;

        $stepNumber = isset($_SESSION[$cCode]['tableIn_master']['step_number']) ? $_SESSION[$cCode]['tableIn_master']['step_number'] : 1;
        $itemLabels = isset($configUi[$this->jenisTr]['shoppingCartFields'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartFields'][$stepNumber] : array();
        $itemLabels2 = isset($configUi[$this->jenisTr]['shoppingCartFields2'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartFields2'][$stepNumber] : array();
        $itemLabels3 = isset($configUi[$this->jenisTr]['shoppingCartFields3'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartFields3'][$stepNumber] : array();
        $itemSrcLabels = isset($configUi[$this->jenisTr]['shoppingCartFieldItemSrc'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartFieldItemSrc'][$stepNumber] : array();
        $itemNumLabels = isset($configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartNumFields'][$stepNumber] : array();
        $itemNumLabels2 = isset($configUi[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartNumFields2'][$stepNumber] : array();
        $itemNumLabels3 = isset($configUi[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartNumFields3'][$stepNumber] : array();
        $itemSrcNumLabels = isset($configUi[$this->jenisTr]['shoppingCartNumFieldItemSrc'][$stepNumber]) ? $configUi[$this->jenisTr]['shoppingCartNumFieldItemSrc'][$stepNumber] : array();
        $fieldSrcs = isset($this->configUi[$this->jenisTr]['shoppingCartFieldSrc']) ? $this->configUi[$this->jenisTr]['shoppingCartFieldSrc'] : array();

        $appletConfigs = isset($configUi[$this->jenisTr]['applets']) ? $configUi[$this->jenisTr]['applets'] : array();
        $elementConfigs = isset($configUi[$this->jenisTr]['receiptElements']) ? $configUi[$this->jenisTr]['receiptElements'] : array();
        $receiptElementsDeleter = isset($configUi[$this->jenisTr]['receiptElementsDeleter']) ? $configUi[$this->jenisTr]['receiptElementsDeleter'] : array();
        $relElementConfigs = isset($configUi[$this->jenisTr]['relativeElements']) ? $configUi[$this->jenisTr]['relativeElements'] : array();
        $relOptionConfigs = isset($configUi[$this->jenisTr]['relativeOptions']) ? $configUi[$this->jenisTr]['relativeOptions'] : array();
        $noteEnabled = isset($configUi[$this->jenisTr]['shoppingCartNoteEnabled']) && $configUi[$this->jenisTr]['shoppingCartNoteEnabled'] == true ? true : false;
        $imageEnabled = isset($configUi[$this->jenisTr]['shoppingCartImageEnabled']) && $configUi[$this->jenisTr]['shoppingCartImageEnabled'] == true ? true : false;
        $addRowsConfigs = isset($configUi[$this->jenisTr]['additionalRows']) ? $configUi[$this->jenisTr]['additionalRows'] : array();
        $inputLabels = array();
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        //
        //region lookup items from shopping cart sessions

        //        cekbiru($rawPrevURL);

        $main = array();
        $items = array();
        $items2 = array();
        $items2_sum = array();
        $items3_sum = array();
        $itemsSrc = array();
        //        arrPrint($_SESSION[$cCode]['itemSrc']);
        //        matiHEre();
        if (isset($_SESSION[$cCode])) {
            if (isset($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $iSpec) {
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "n/a",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "n/a",

                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "merk" => isset($iSpec['merk']) ? $iSpec['merk'] : "",
                        "serial_no" => isset($iSpec['serial_no']) ? $iSpec['serial_no'] : "",
                        "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        "extern_date2" => isset($iSpec['extern_date2']) ? $iSpec['extern_date2'] : "",
                        "extern_label2" => isset($iSpec['extern_label2']) ? $iSpec['extern_label2'] : "",
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
                    //-------------------------------------
                    if (sizeof($fieldSrcs) > 0) {
                        foreach ($fieldSrcs as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                    }
                    //-------------------------------------
                    if ($noteEnabled) {
                        if (isset($iSpec['note'])) {
                            $tmp['note'] = $iSpec['note'];
                        }
                    }
                    if ($imageEnabled) {
                        if (isset($iSpec['images'])) {
                            $tmp['images'] = $iSpec['images'];
                        }
                    }
                    $tmp['subtotal'] = $iSpec['subtotal'];


                    $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                    $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];

                    $items[] = $tmp;
                }
            }

            if (isset($_SESSION[$cCode]['items2'])) {
                foreach ($_SESSION[$cCode]['items2'] as $aSpec) {
                    //                    arrPrint($iSpec);
                    foreach ($aSpec as $iSpec) {
                        if (isset($iSpec['id'])) {

                            $tmp = array(
                                "id" => $iSpec['id'],
                                "nama" => $iSpec['nama'],
                                "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                                "jml" => $iSpec['jml'],
                                "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "n/a",
                                "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "n/a",

                                "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "n/a",
                                "disc_value" => isset($iSpec['disc_value']) ? $iSpec['disc_value'] : "0",
                                "jual" => isset($iSpec['jual']) ? $iSpec['jual'] : 0,
                                "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : 0,
                                "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : 0,
                                "harga_ori" => isset($iSpec['harga_ori']) ? $iSpec['harga_ori'] : "",
                                "harga" => isset($iSpec['harga']) ? $iSpec['harga'] : "",

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
                            if ($imageEnabled) {
                                if (isset($iSpec['images'])) {
                                    $tmp['images'] = $iSpec['images'];
                                }
                            }
                            $tmp['subtotal'] = $iSpec['subtotal'];

                            if (isset($iSpec['handler'])) {
                                $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                                $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];
                            }
                            else {
                                $tmp["editTarget"] = "#";
                                $tmp["removeTarget"] = "#";
                            }

                            $items2[] = $tmp;
                        }
                    }


                }
            }

            if (isset($_SESSION[$cCode]['items2_sum'])) {
                foreach ($_SESSION[$cCode]['items2_sum'] as $iSpec) {
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "n/a",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "n/a",

                        "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "n/a",
                        "harga" => isset($iSpec['harga_ori']) ? $iSpec['harga_ori'] : isset($iSpec['harga']) ? $iSpec['harga'] : "0",
                        "harga_ori" => isset($iSpec['harga_ori']) ? $iSpec['harga_ori'] : "",
                        "referensi" => isset($iSpec['pihakName']) ? $iSpec['pihakName'] : "",
                        "disc_value" => isset($iSpec['disc_value']) ? $iSpec['disc_value'] : 0,
                        "jual" => isset($iSpec['jual']) ? $iSpec['jual'] : 0,

                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "sisa" => isset($iSpec['stok']) ? $iSpec['stok'] - $iSpec['jml'] : "",
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
                    if ($imageEnabled) {
                        if (isset($iSpec['images'])) {
                            $tmp['images'] = $iSpec['images'];
                        }
                    }
                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;

                    if (isset($iSpec['handler'])) {

                        $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                        $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];
                    }
                    else {
                        $tmp["editTarget"] = "#";
                        $tmp["removeTarget"] = "#";
                    }

                    $items2_sum[] = $tmp;
                }
            }

            if (isset($_SESSION[$cCode]['items3_sum'])) {
                foreach ($_SESSION[$cCode]['items3_sum'] as $iSpec) {
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "n/a",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "n/a",

                        "harga" => isset($iSpec['harga']) ? $iSpec['harga'] : "",
                        "referensi" => isset($iSpec['pihakName']) ? $iSpec['pihakName'] : "",
                        "sub_nilai" => isset($iSpec['sub_nilai']) ? $iSpec['sub_nilai'] : "",

                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "sisa" => isset($iSpec['stok']) ? $iSpec['stok'] - $iSpec['jml'] : "",
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
                    if ($imageEnabled) {
                        if (isset($iSpec['images'])) {
                            $tmp['images'] = $iSpec['images'];
                        }
                    }
                    $tmp['subtotal'] = isset($iSpec['subtotal']) ? $iSpec['subtotal'] : 0;

                    if (isset($iSpec['handler'])) {

                        $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                        $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];
                    }
                    else {
                        $tmp["editTarget"] = "#";
                        $tmp["removeTarget"] = "#";
                    }

                    $items3_sum[] = $tmp;
                }
            }
            if (isset($_SESSION[$cCode]['itemSrc'])) {
                foreach ($_SESSION[$cCode]['itemSrc'] as $iSpec) {
                    $tmp = array(
                        "id" => $iSpec['id'],
                        "nama" => $iSpec['nama'],
                        "satuan" => isset($iSpec['satuan']) ? $iSpec['satuan'] : "n/a",
                        "jml" => $iSpec['jml'],
                        "produk_kode" => isset($iSpec['produk_kode']) ? $iSpec['produk_kode'] : "n/a",
                        "no_part" => isset($iSpec['no_part']) ? $iSpec['no_part'] : "n/a",

                        "stok" => isset($iSpec['stok']) ? $iSpec['stok'] : "",
                        "stok_center" => isset($iSpec['stok_center']) ? $iSpec['stok_center'] : "0",
                        "merk" => isset($iSpec['merk']) ? $iSpec['merk'] : "",
                        "serial_no" => isset($iSpec['serial_no']) ? $iSpec['serial_no'] : "",
                        "kode" => isset($iSpec['kode']) ? $iSpec['kode'] : "",
                        "extern_date2" => isset($iSpec['extern_date2']) ? $iSpec['extern_date2'] : "",
                        "extern_label2" => isset($iSpec['extern_label2']) ? $iSpec['extern_label2'] : "",
                        "extern_nama" => isset($iSpec['extern_nama']) ? $iSpec['extern_nama'] : "",
                    );
                    if (sizeof($itemSrcNumLabels) > 0) {
                        foreach ($itemSrcNumLabels as $key => $label) {
                            $tmp[$key] = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if (!isset($main[$key])) {
                                $main[$key] = 0;
                            }
                            //                            $main[$key] += isset($iSpec[$key]) ? ($iSpec['jml'] * $iSpec[$key]) : 0;

                        }
                    }
                    if ($noteEnabled) {
                        if (isset($iSpec['note'])) {
                            $tmp['note'] = $iSpec['note'];
                        }
                    }
                    if ($imageEnabled) {
                        if (isset($iSpec['images'])) {
                            $tmp['images'] = $iSpec['images'];
                        }
                    }
                    $tmp['subtotal'] = $iSpec['subtotal'];


                    $tmp["editTarget"] = base_url() . $iSpec['handler'] . "/select/" . $this->jenisTr . "?id=" . $iSpec['id'] . "&newQty=";
                    $tmp["removeTarget"] = base_url() . $iSpec['handler'] . "/remove/" . $this->jenisTr . "?id=" . $iSpec['id'];

                    $itemsSrc[] = $tmp;
                }
            }
        }
        //endregion

        //
        //region labels for preview's bottom elements
        // cekBiru($jenisTr);
        // arrPrintBlue($configUi);
        $buttonLabel = $configUi[$jenisTr]["steps"][1]['actionLabel'];
        if (sizeof($configUi[$this->jenisTr]['steps']) > 1) {
            $saveWarning = "clicking <strong>$buttonLabel</strong> button will make transaction state to: <strong class='badge bg-grey text-white'>" . $configUi[$this->jenisTr]['steps'][1]['stateLabel'] . "</strong>";
            $saveWarning .= "<br>It would need to be authorized by <strong class='text-blue'>" . $configUi[$this->jenisTr]['steps'][2]['userGroup'] . "</strong>";
        }
        else {
            $saveWarning = "clicking <strong>$buttonLabel</strong> button will instantly make transaction state to <strong class='text-blue'>" . $configUi[$this->jenisTr]['steps'][1]['stateLabel'] . "</strong>";
        }
        //endregion


        $stepLabels = array();
        foreach ($configUi[$this->jenisTr]['steps'] as $num => $sSpec) {
            $stepLabels[$num] = $sSpec['label'];
        }


        //region prepare params to viewer

        $tmpTableIn_master = isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array();
        $tmpTableIn_masterValues = isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array();
        $main = array_merge(array_filter($main), array_filter($tmpTableIn_master), array_filter($tmpTableIn_masterValues), array_filter($_SESSION[$cCode]['main']));
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
                    //                    cekmerah($currentValue);
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        //                        cekhijau("memenuhi syarat");
                        //===daftarkan ke elementConfig
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            //                            cekmerah("memeriksa $eName, $currentValue");
                            //                            $rcCtr = 0;
                            foreach ($relElementConfigs[$eName][$currentValue] as $rcID => $rcSpec) {
                                //                                $elKey = $eName . "_" . $currentValue . "_" . $rcID;
                                $elKey = $rcID;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rcID];
                                //                                $rcCtr++;
                            }
                        }
                    }
                    else {
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
                    }
                    else {
                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                    }

                }
                else {
                    //					cekKuning("$eName TIDAK terdaftar pada relInputs");
                }
            }
        }

        if (isset($_SESSION[$cCode]['main']['tipe_penjualan']) && ($_SESSION[$cCode]['main']['tipe_penjualan'] == 1)) {
            $itemLabels = isset($this->configUi[$this->jenisTr]['shoppingCartFieldsMarketplace'][$stepNumber]) ? $this->configUi[$this->jenisTr]['shoppingCartFieldsMarketplace'][$stepNumber] : $itemLabels;
        }

        $itemLabels = $itemLabels + $itemNumLabels + array("subtotal" => "sub-amount");
        $itemLabels2 = $itemLabels2 + $itemNumLabels2 + array("subtotal" => "sub-amount");
        $itemLabels3 = $itemLabels3 + $itemNumLabels3 + array("subtotal" => "sub-amount");
        $itemSrcLabels = $itemSrcLabels + $itemSrcNumLabels + array("subtotal" => "sub-amount");
        if (isset($configUi[$this->jenisTr]['shoppingCartHideSubamount']) && $configUi[$this->jenisTr]['shoppingCartHideSubamount'][$stepNumber] == true) {
            unset($itemLabels['subtotal']);
            unset($itemLabels2['subtotal']);
            unset($itemLabels3['subtotal']);
        }

        //--------------------
        $additionalRowsDeleter = isset($this->configUi[$this->jenisTr]['additionalRowsDeleter']) ? $this->configUi[$this->jenisTr]['additionalRowsDeleter'] : array();
        if (sizeof($additionalRowsDeleter) > 0) {
            if (isset($additionalRowsDeleter["enabled"]) && ($additionalRowsDeleter["enabled"] == true)) {
                if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == $additionalRowsDeleter["tipe_penjualan"])) {
                    foreach ($additionalRowsDeleter["additionalRows"] as $elementNama) {
                        if (isset($addRowsConfigs["dummyElement"]["yes"][$elementNama])) {
                            $addRowsConfigs["dummyElement"]["yes"][$elementNama] = NULL;
                            unset($addRowsConfigs["dummyElement"]["yes"][$elementNama]);
                        }
                    }
                }
            }
        }
        //--------------------

        //region tambah additional rows tampil
        $addRowLabels = array();
        if (sizeof($elementConfigs) > 0) {
            if (sizeof($receiptElementsDeleter) > 0) {
                if (isset($receiptElementsDeleter["enabled"]) && ($receiptElementsDeleter["enabled"] == true)) {
                    if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == $receiptElementsDeleter["tipe_penjualan"])) {
                        foreach ($receiptElementsDeleter["element"] as $elementNama) {
                            if (isset($elementConfigs[$elementNama])) {
                                $elementConfigs[$elementNama] = NULL;
                                unset($elementConfigs[$elementNama]);
//                                $elementConfigs[$elementNama]["inputType"] = "hidden";
                            }
                        }
                    }
                }
            }
            foreach ($elementConfigs as $eName => $eSpec) {
                if (array_key_exists($eName, $addRowsConfigs)) {
                    switch ($elementConfigs[$eName]['elementType']) {
                        case "dataModel":
                            $currentValue = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : "";
                            break;
                        case "dataField":
                            $currentValue = $_SESSION[$cCode]['main_elements'][$eName]['value'];
                            break;
                    }
                    if (isset($addRowsConfigs[$eName][$currentValue])) {
                        if (sizeof($addRowsConfigs[$eName][$currentValue]) > 0) {
                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                    if (!isset($oValSpec['hideRow'])) {
                                        $addRowLabels[$oValueName] = $oValSpec['label'];
                                    }
                                    if (isset($oValSpec['showPreview']) && ($oValSpec['showPreview'] == true)) {
                                        $addRowLabels[$oValueName] = $oValSpec['label'];
                                    }
                                }
                            }
                        }
                    }
                    else {
                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        //                        cekmerah("aturan untuk $currentValue TIDAK ada");
                    }
                }
                else {
                    //					cekKuning("$eName TIDAK terdaftar pada relInputs");
                }
            }
        }
        //endregion

        //endregion

        $kelebihanBayarMarketplace = isset($this->configUi[$this->jenisTr]['kelebihanBayarMarketplace']) ? $this->configUi[$this->jenisTr]['kelebihanBayarMarketplace'] : array();
        if (sizeof($kelebihanBayarMarketplace) > 0) {
            if (isset($_SESSION[$cCode]["main"]["tipe_penjualan"]) && ($_SESSION[$cCode]["main"]["tipe_penjualan"] == 1)) {
                if (isset($kelebihanBayarMarketplace["enabled"]) && ($kelebihanBayarMarketplace["enabled"] == true)) {
                    if ($_SESSION[$cCode]["main"]["lebih_bayar"] > 0) {
                        $kelebihanBayarMarketplaceParams = array(
                            "gateData" => $_SESSION[$cCode][$kelebihanBayarMarketplace["gateSource"]],
                            "headers" => $kelebihanBayarMarketplace["header"],
                            "keyLebihBayar" => $kelebihanBayarMarketplace["keyLebihBayar"],
                            "editableFields" => $kelebihanBayarMarketplace["editableFields"],
                            "summaryHeader" => $kelebihanBayarMarketplace["summaryHeader"],

                        );
                    }
                }
            }
        }
        $editableFieldsOptions = isset($this->configUi[$this->jenisTr]['kelebihanBayarMarketplace']['editableFieldsOptions'][$stepNumber]) ? $this->configUi[$this->jenisTr]['kelebihanBayarMarketplace']['editableFieldsOptions'][$stepNumber] : array();

        $pairedItemTarget = isset($configUi[$this->jenisTr]['shoppingCartPairedItem']['targetGateName']) ? $configUi[$this->jenisTr]['shoppingCartPairedItem']['targetGateName'] : "items2";

        $shopingCartFaktur = isset($this->configUi[$this->jenisTr]['addMainSource'][1]) ? $this->configUi[$this->jenisTr]['addMainSource'][1] : array();
        if (count($shopingCartFaktur) > 0) {
            if (isset($shopingCartFaktur["gateTarget"])) {
                $gateTarget = $shopingCartFaktur["gateTarget"];

            }
        }

        $data = array(
            "mode" => $this->uri->segment(3),
            "template" => $configUi[$jenisTr]["template"],
            "title" => $configUi[$jenisTr]["label"],
            "subTitle" => $configUi[$jenisTr]["steps"][1]['label'],
            "jenisTr" => $jenisTr,
            "configUi" => $this->configUi,
            "pihakLabel" => $configUi[$jenisTr]["pihakLabel"],
            "itemLabels" => $itemLabels,
            "itemLabels2" => $itemLabels2,
            "itemLabels3" => $itemLabels3,
            "itemSrcLabels" => $itemSrcLabels,
            "noteEnabled" => $noteEnabled,
            "imageEnabled" => $imageEnabled,
            "main" => $main,
            "items" => $items,
            //            "items2" => $items2,
            "items2" => $$pairedItemTarget,
            "items3" => $items3_sum,
            "itemsSrc" => $itemsSrc,
            "buttonLabel" => $buttonLabel,
            "saveWarning" => $saveWarning,
            //            "sumRows" => $this->configLayout[$this->jenisTr]['receiptSumFields'][$stepNumber],
            "sumRows" => isset($configUi[$this->jenisTr]['shoppingCartSumFields'][1]) ? $configUi[$this->jenisTr]['shoppingCartSumFields'][1] : $this->configLayout[$this->jenisTr]['receiptSumFields'][1],
            "sumAddRows" => $addRowLabels,
            "sumRows3" => isset($configUi[$this->jenisTr]['shoppingCartSumFields3'][1]) ? $configUi[$this->jenisTr]['shoppingCartSumFields3'][1] : isset($this->configLayout[$this->jenisTr]['receiptSumFields3'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields3'][1] : array(),
            "extValueLabels" => isset($this->configCore[$this->jenisTr]['externalValues']) ? $this->configCore[$this->jenisTr]['externalValues'] : array(),
            //            "mainAddValues" => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
            "mainAddValues" => $mainAddValues,
            "mainAddFields" => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
            //            "mainApplets"    => isset($_SESSION[$cCode]['main_applets']) ? $_SESSION[$cCode]['main_applets'] : array(),
            "mainElements" => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
            "actionTarget" => base_url() . $this->modul . "/Create/save/" . $this->jenisTr . "?rawPrev=$rawPrevURL",
            "appletConfig" => $appletConfigs,
            "elementConfig" => $elementConfigs,
            "mainInputs" => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
            "grandTotal" => isset($_SESSION[$cCode]['main']['grand_total']) ? $_SESSION[$cCode]['main']['grand_total'] : 0,
            "headerRows" => isset($this->configLayout[$this->jenisTr]['receiptMainFields'][$stepNumber]) ? $this->configLayout[$this->jenisTr]['receiptMainFields'][$stepNumber] : array(),
            "description" => isset($_SESSION[$cCode]['main']['description']) ? $_SESSION[$cCode]['main']['description'] : "",
            "stepLabels" => $stepLabels,
            "currentStep" => 1,
            "pairedValue" => isset($_SESSION[$cCode]['pairs']) ? $_SESSION[$cCode]['pairs'] : array(),
            //----
            "formulirFaktur" => isset($_SESSION[$cCode][$gateTarget]) ? $_SESSION[$cCode][$gateTarget] : array(),
            "fakturParam" => $shopingCartFaktur,
            "kelebihanBayarMarketplaceParams" => isset($kelebihanBayarMarketplaceParams) ? $kelebihanBayarMarketplaceParams : array(),
            "inputOptions" => $editableFieldsOptions,
        );

        if (isset($_SESSION[$cCode]['main'])) {
            $data['pihakID'] = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : 0;
            $data['pihakName'] = isset($_SESSION[$cCode]['main']['pihakName']) ? $_SESSION[$cCode]['main']['pihakName'] : "";
        }

        //endregion

        $this->load->view("transaksi", $data);
    }

    public function save()
    {
        echo "<script>top.writeProgress('MEMULAI PROSES SAVING....');</script>";

        if ($this->transaksiMaintenance === true) {
            $msg = $this->transaksiMaintenanceMsg['title'] . "<br>" . $this->transaksiMaintenanceMsg['mesage'];
            mati_disini($msg);
        }

//        $this->jenisTr = $this->uri->segment(3);
        $cCode = $this->cCode;
        $configUi = $this->configUi;

        $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
        $modul_transaksi = $this->modul;
        $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUi[$this->jenisTr]["steps"][1]["target"]) ? $this->configUi[$this->jenisTr]["steps"][1]["target"] : NULL;
        $relOptionConfigs = isset($this->configUi[$this->jenisTr]['relativeOptions']) ? $this->configUi[$this->jenisTr]['relativeOptions'] : array();
        $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : $this->session->login["ppnFactor"];
        $_SESSION[$cCode]["main"]["ppnFactor"] = $ppnFactor;
        $inputLabels = array();
        $inputAuthConfigs = array();

        $this->load->model("MdlTransaksi");
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $prevUrl = blobDecode($rawPrevURL);
        $mongoList = array();
        $mongRegID = array();
        if (isset($_SESSION[$cCode])) {

            if (!isset($_SESSION[$cCode]["main"]["bookingNumber"])) {
                $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                mati_disini($msg);
            }
            elseif ($_SESSION[$cCode]["main"]["bookingNumber"] == null) {
                $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                mati_disini($msg);
            }
            $nomerBookingTransaksi = $_SESSION[$cCode]["main"]["bookingNumber"];

            if (!isset($_SESSION[$cCode]['items'])) {
                die("belum ada item yang dipilih");
            }
            else {
                if (sizeof($_SESSION[$cCode]['items']) < 1) {
                    die("belum ada item yang dipilih");
                }
            }
            $this->assertNoMixedProjectNotes($cCode);
            echo("now processing your transaction..<br>");


            //region build table rekening
            $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master'] : array();
            $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail'] : array();
            $addMasterTables = array(
                "rugilaba",
                "laba ditahan",
                "rugilaba lain lain",
            );
            foreach ($addMasterTables as $trek) {
                $buildTablesMaster[] = array(
                    "comName" => "RugiLaba",
                    "loop" => array(
                        "$trek" => .0,
                    ),
                );
            }
            if (sizeof($buildTablesMaster) > 0) {
                //                arrprint($buildTablesMaster);
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {

                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    if (substr($mdlName, 0, 1) == "{") {
                        //                        cekkuning("mengandung kurawal");
                        $mdlName = trim($mdlName, "{");
                        $mdlName = trim($mdlName, "}");
                        $mdlName = str_replace($mdlName, $_SESSION[$cCode]['main'][$mdlName], $mdlName);
                    }
                    else {
                        cekkuning("TIDAK mengandung kurawal");
                    }

                    $mdlName = "Com" . $mdlName;

                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();


                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                cekHere($oldParam);
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }


                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                        }
                    }
                }
            }
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($_SESSION[$cCode]['items'] as $itemSpec) {
                        //arrPrint($itemSpec);
                        $mdlName = $buildTablesDetail_specs['comName'];
                        cekLime($mdlName);
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            //                        $mdlName = str_replace($mdlName, $_SESSION[$cCode]['main'][$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        cekbiru("model: $mdlName");
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                            }
                        }
                    }
                }
            }
            //endregion


            $this->db->trans_start();

            cekMerah("start pre-processor...");

            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['preProc']['detail']) ? $_SESSION[$cCode]['revert']['preProc']['detail'] : array();
                cekMerah(":: iterator preprocc dari gerbang revert ::");
                arrPrintWebs($iterator);
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['detail'] : array();
            }

            if (sizeof($iterator) > 0) {


                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        echo "sub-preproc: $comName, initializing values <br>";

                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();

                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }


                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            //                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                            //                            arrPrint($subParams);
                            //mati_disini();
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;


                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                //                                echo "sub preproc #$it: $comName, sending values <br>";

                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);


                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();

                                    cekmerah("gotparams dari pre-proc $comName");
                                    arrprint($gotParams);


                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            cekBiru(":: getParams inject ke $gateName ::");
                                            if (!isset($_SESSION[$cCode][$gateName])) {
                                                $_SESSION[$cCode][$gateName] = array();
                                                //                                    cekhijau("building the session: $gateName");
                                            }
                                            else {
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
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
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
                                                    }
                                                }
                                            }
                                            //                                    arrPrint($items);die();
                                        }


                                    }

                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }


                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);


                //region injector gerbang value untuk pembatalan ppv dan selisih
                if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                    $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                    $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                    switch ($jenisTrReference) {
                        case "460":
                            $tempCalculate = array(
                                //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                            );
                            break;
                        default:
                            $tempCalculate = array(
                                "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                            );
                            break;
                    }
                    //                    $tempCalculate = array(
                    //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                    //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                    //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                    //                    );

                    //arrPrintWebs($tempCalculate);
                    foreach ($replace['recalculate'] as $iKey => $gate) {
                        $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                    }

                    cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                }

                //endregion


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['preProc']['master']) ? $_SESSION[$cCode]['revert']['preProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {

                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();


                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {

                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;

                                //                                cekPink2("$comName == $value || $realValue");
                                //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                            }

                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);


                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
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

                                    if ($switchResultParams == true) {

                                        foreach ($gSpec as $id => $ggSpec) {
                                            if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                $_SESSION[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $_SESSION[$cCode][$gateName][$id][$key] = $val;
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
                                                }
                                            }

                                        }
                                    }
                                    else {
                                        if (isset($_SESSION[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode]['main'][$key] = $val;
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
                                                cekHere("$id === $key => $label");
                                                if (isset($_SESSION[$cCode]['main'][$key])) {
                                                    $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                        cekPink2("fillvalue setelah $comName");
                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            $this->load->library("Validator");
            $vd = new Validator();
            $vd->setCCode($this->cCode);
            $vd->setConfigUiJenis($this->configUiJenis);
            $step = $_SESSION[$cCode]['main']['step_number'];
            $vd->midValidate($step);
            $vd->unionValidate();

            //region penomoran receipt
            //<editor-fold desc="==========penomoran">
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);

            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
            arrPrintWebs($counterForNumber);
            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                die(__LINE__ . " Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";
            //arrPrintWebs($paramSpec);
            //mati_disini("hahaha");


            $stepNumber = 1;

            $tmpNomorNota = $paramSpec['paramString'];
            $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);

            if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }

            //</editor-fold>
            //endregion

            //region dynamic counters
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
            $configCustomParams[] = "stepCode";
            //arrPrint($configCustomParams);
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
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
            //mati_disini();

            //
            //region addition on master


            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'nomer2' => $tmpNomorNotaAlias,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],


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
                "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],


            );
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }
            //endregion
            // </editor-fold>
            //endregion

            //region numbering tambahan
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($this->cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($_SESSION[$cCode]['tableIn_master']);
            $ccn->setMainGate($_SESSION[$cCode]['main']);
            $ccn->setItemsGate($_SESSION[$cCode]['items']);
            $ccn->setItems2SumGate($_SESSION[$cCode]['items2_sum']);
            $new_counter = $ccn->getCounterNumber();
            cekHitam("jenistr yang disett dari create " . $this->jenisTr);


            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                foreach ($new_counter['main'] as $ckey => $cval) {
                    $_SESSION[$cCode]['tableIn_master'][$ckey] = $cval;
                    $_SESSION[$cCode]['main'][$ckey] = $cval;
                }
            }
            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                foreach ($new_counter['items'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $_SESSION[$cCode]['items'][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $_SESSION[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion

            $pakai_ini_cli = 0;
            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                $_SESSION[$cCode]['tableIn_master']['status_4'] = 11;
                $_SESSION[$cCode]['tableIn_master']['trash_4'] = 0;
                if ($pakai_ini_cli == 0) {
                    $_SESSION[$cCode]['tableIn_master']['cli'] = 1;
                }
                else {
                    $_SESSION[$cCode]['tableIn_master']['cli'] = 0;
                }

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                cekHitam($this->db->last_query());
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $_SESSION[$cCode]['tableIn_master']);
                $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }
                $mongoList['main'] = array($insertID, $epID);
                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => $tmpNomorNotaAlias,
                );
                $arrInjectorsTarget = array(
                    "items",
                    "items2_sum",
                    "rsltItems",
                );
                foreach ($injectors as $key => $val) {
                    $_SESSION[$cCode]['main'][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($_SESSION[$cCode][$target])) {
                            foreach ($_SESSION[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                if (isset($_SESSION[$cCode][$target][$id])) {
                                    $_SESSION[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $_SESSION[$cCode]['main']['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                    "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => $this->session->login['id'],
                    "oleh_nama" => $this->session->login['nama'],
                    "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'],
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $_SESSION[$cCode]['main']['olehID'],
                        "olehName" => $_SESSION[$cCode]['main']['olehName'],
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "nomer2" => $tmpNomorNotaAlias,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
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
                    "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->db->last_query());

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }

            }
            if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                    //matiHEre("hooppp");
                    $inserMainValues = array();
                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        if (isset($_SESSION[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $_SESSION[$cCode]['tableIn_master_values'][$key],
                            ));

                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                    }
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = ? WHERE id = ?", array($arrBlob, $insertID));
                }

            }
            if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = ? WHERE id = ?", array($arrBlob, $insertID));
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
                    ));
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
//                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                    ));


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
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }

                    }

                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {

                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($insertDetailID < 1) {
                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        if ($insertEpID < 1) {
                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                            $mongoList['detail'][] = $insertEpID;
                        }
                    }
                    cekUngu($this->db->last_query());
                }


                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }

                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = ? WHERE id = ?", array($arrBlob, $k));
                        cekOrange($this->db->last_query());
                    }
                }
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                    }
                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) && sizeof($_SESSION[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $dd;
                    $mongoList['detil'][] = $dd;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detil'] = $insertIDs;
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {

                $insertIDs = array();
                foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            if (isset($_SESSION[$cCode]['tableIn_detail'][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;

                            }
                        }
                    }
                }

                if (sizeof($insertIDs) > 0) {
                    $arrBlob = blobEncode($insertIDs);
                    $this->db->query("UPDATE transaksi SET indexing_detail_values = ? WHERE id = ?", array($arrBlob, $insertID));
                }

            }
            if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            //endregion


            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUi[$this->jenisTr]['steps'];

            $filterNeeded = false;

            //region processing main components, if in single step

            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['master']) ? $_SESSION[$cCode]['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                $componentConfig['master'] = $iterator;
                $cCtr = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component # $cCtr: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                            }

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                            if ($comName == "RekeningPembantuKas") {
                                cekKuning("LOOP $key rumus $value diisi dengan $realValue");
                            }
//                            cekKuning("LOOP $key diisi dengan $realValue");
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }


                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (isset($tmpOutParams["static"]["validate"]) && ($tmpOutParams["static"]["validate"] == 1)) {
                        $tobeExecuted = true;
                    }
                    else {
                        if (in_array($mdlName, $compValidators)) {
                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }
                        }
                    }


                    if ($tobeExecuted) {

                        //cekBiru("kiriman komponem $comName");
                        //                        arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }

                    $componentGate['master'][$cCtr] = $tmpOutParams;
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion

            //region processing sub-components, if in single step geser ke CLI
            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['detail']) ? $_SESSION[$cCode]['revert']['jurnal']['detail'] : array();
                $revertedTarget = $_SESSION[$cCode]['main']['pihakExternID'];
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['components'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
            }
            $componentConfig['detail'] = $iterator;
            if ($pakai_ini_cli == 0) {
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            echo "sub-component: $comName, initializing values <br>";

                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                        $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
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

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {

                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                            }
                        }
                        echo "sub component: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
            }

            //endregion

            cekHitam(":: START POST PROCC DETAIL... ::");

            //region processing sub-post-processors, always
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['detail'] : array();
                cekHitam("post procc pakai revert");
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['detail'] : array();
                cekHitam("post procc pakai config core");
            }
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                    $tmpOutParams[$cCtr] = array();
                    if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    cekHitam("gate: $srcGateName, dengan key $id");
                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($subParams['static'][$k])) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    $jenis = $_SESSION[$cCode]['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                }

                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                            }

                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($_SESSION[$cCode][$srcGateName])) {
                        echo "[$cCtr] sub-postProcessor: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        //arrPrint($tmpOutParams[$cCtr]);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekPink($this->db->last_query());
                    }

                }
            }
            //endregion


            //region relesaese connected payment source dan marking trash transaksi
            //            arrPrint($_SESSION[$cCode]["revert"]);
            if (isset($_SESSION[$cCode]["revert"]["connectedPaymentsource"]) && $_SESSION[$cCode]["revert"]["connectedPaymentsource"] == true) {
                $keyRel = $_SESSION[$cCode]["main"]["referenceID"];
                $keyRelRef = $_SESSION[$cCode]["main"]["pihakExternID"];
                $relPaymentSrc = isset($this->config->item("payment_source")[$keyRelRef]) ? $this->config->item("payment_source")[$keyRelRef] : array();
                if (sizeof($relPaymentSrc) > 0) {
                    $this->load->model("Mdls/MdlPaymentSource");
                    $m = new MdlPaymentSource();
                    $m->addFilter("transaksi_id='$keyRel'");
                    $tmpRelPay = $m->lookupAll()->result();
                    $paymentRelUsed = array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "label" => ".hutang biaya",
                        "target_jenis" => "jenisTr",
                        "transaksi_id" => "refID",
                        "terbayar" => "nilai_bayar",
                        "sisa" => "new_sisa",
                        "ppn" => "valid_ppn",
                        "extern_nilai2" => "valid_dpp",
                    );
                    if (sizeof($tmpRelPay) > 0) {
                        $tmpOutParams = array();
                        $iterator = array();
                        foreach ($tmpRelPay as $indexKey => $relData) {
                            $tmp = array();
                            foreach ($paymentRelUsed as $key => $keyGate) {
                                if ($key == "terbayar") {
                                    $val = $relData->sisa;
                                }
                                else {
                                    if ($key == "sisa") {
                                        $val = "-" . $relData->sisa;
                                    }
                                    else {
                                        $val = $relData->$key;
                                    }
                                }
                                $tmp["static"][$key] = $val;
                                $tmp["static"]["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];

                            }
                            $iterator[$indexKey]["loop"] = array();
                            $iterator[$indexKey]["comName"] = "PaymentSrcItem";
                            if (sizeof($tmp) > 0) {
                                $tmpOutParams[$indexKey][] = $tmp;
                            }
                        }
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            //                            $srcGateName = $tComSpec['srcGateName'];
                            //                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            echo "sub-postProcessor: $comName, sending values <br>";

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            //                            cekHitam($this->db->last_query());
                        }
                    }
                    //                    foreach()
                    cekLime("yuk direset paymentsource**");
                }


            }
            //endregion


            //region processing main-post-processors, always
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>LINE: " . __LINE__;

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                            }
                        }

                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    // cekBiru("kiriman komponem $comName");
                    // arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekHitam($this->db->last_query());
                }
            }
            else {

            }
            //endregion


            //region updater main transaksi rejection jurnal next step exist
            $mongUpdateList = array();
            if (isset($this->configCore[$this->jenisTr]['relativeComponets']) && $this->configCore[$this->jenisTr]['relativeComponets'] == true) {

                $tr->setFilters(array());
                $tr->addFilter("id='" . $_SESSION[$cCode]["main"]["referenceID"] . "'");
                $tempData = $tr->lookupAll()->result();
                $refMasterID = $tempData[0]->id_master;
                $refMasterJenis = $tempData[0]->jenis_master;
                $nextStepCode = $tempData[0]->next_step_code;
                $mainStepCode = $tempData[0]->jenis;
                $stepnum = $tempData[0]->step_number;
                $stepnumAvail = $tempData[0]->step_avail;
                if (($stepnumAvail - $stepnum) > 0) {
                    //                    matiHere("masukk".$nextStepCode);
                    $this->load->model("Coms/ComTransaksi_jurnal_revert");
                    $r = new ComTransaksi_jurnal_revert();
                    $outParams = array(
                        "refID" => $refMasterID,
                        "main_code" => $mainStepCode,
                        "next_code" => $nextStepCode,
                        "step_num" => $stepnum,
                    );
                    $r->pair($outParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $r->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);

                    //marking main transaksi trash4
                    $udpate = array(
                        "trash_4" => "1",
                    );
                    $tr->setFilters(array());
                    //                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array(
                        //                "id" => $no,
                        "id" => $_SESSION[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $_SESSION[$cCode]["main"]["referenceID"]),
                        "value" => array(
                            "trash_4" => "1",
                        ),
                    );
                    cekHijau("UPDATE transaksi step sebelumnya...");
                    cekHijau($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                    //update validqty 0 supaya gak bisa difollowup
                    //                    $arrData_detail["valid_qty"] = 0;
                    $td = new MdlTransaksi();
                    $td->setFilters(array());
                    $rslt = $td->lookupJoinedByID($_SESSION[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $_SESSION[$cCode]["items"])) {
                                //                                $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                                //                                //                            cekHitam(":: $prevID :: $rsltSpec->valid_qty :: " . $items[$rsltSpec->produk_id]['qty'] . " :: $new_valid_qty ::");
                                //
                                //                                if ($new_valid_qty > $rsltSpec->produk_ord_jml) {
                                //                                    $new_valid_qty = $rsltSpec->valid_qty;
                                //                                    //                                mati_disini("undo/reject/delete gagal karena valid_qty melebihi produk_ord_jml");
                                //                                }
                                //                                else {
                                //                                    $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                                //                                }
                                $arrData_detail["valid_qty"] = 0;
                                $tr = new MdlTransaksi();
                                $tr->setFilters(array());
                                $tr->setTableName($tr->getTableNames()['detail']);
                                $dupState = $tr->updateData(array(
                                    "transaksi_id" => $_SESSION[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $_SESSION[$cCode]["main"]["referenceID"],
                                        "produk_id" => $rsltSpec->produk_id,
                                    ),
                                    "value" => $arrData_detail,
                                );
                                cekKuning("UPDATE transaksi data...");
                                cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                            }
                        }
                    }

                    $dwsign = $tr->writeSignature($refMasterID, array(
                        "prev_id" => "",
                        "nomer" => "pembatalan jurnal",
                        "step_number" => "-" . $stepnum, // ini minus step number
                        "step_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['target'],
                        "step_name" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'],
                        "group_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['userGroup'],
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh " . $this->session->login['nama'],
                        //            "transaksi_id" => $no,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                    //                    matiHere();
                }
                else {

                    //marking main transaksi trash4
                    $udpate = array(
                        "trash_4" => "1",
                    );
                    $tr->setFilters(array());
                    //                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array(
                        //                "id" => $no,
                        "id" => $_SESSION[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    cekHijau("UPDATE transaksi step sebelumnya...");
                    cekHijau($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $_SESSION[$cCode]["main"]["referenceID"]),
                        "value" => array(
                            "trash_4" => "1",
                        ),
                    );

                    //update validqty 0 supaya gak bisa difollowup
                    $arrData_detail["valid_qty"] = $new_valid_qty;
                    $td = new MdlTransaksi();
                    $td->setFilters(array());
                    $rslt = $td->lookupJoinedByID($_SESSION[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $_SESSION[$cCode]["items"])) {
                                //                                $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                                //                                //                            cekHitam(":: $prevID :: $rsltSpec->valid_qty :: " . $items[$rsltSpec->produk_id]['qty'] . " :: $new_valid_qty ::");
                                //
                                //                                if ($new_valid_qty > $rsltSpec->produk_ord_jml) {
                                //                                    $new_valid_qty = $rsltSpec->valid_qty;
                                //                                    //                                mati_disini("undo/reject/delete gagal karena valid_qty melebihi produk_ord_jml");
                                //                                }
                                //                                else {
                                //                                    $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                                //                                }
                                $arrData_detail["valid_qty"] = 0;
                                $tr = new MdlTransaksi();
                                $tr->setFilters(array());
                                $tr->setTableName($tr->getTableNames()['detail']);
                                $dupState = $tr->updateData(array(
                                    "transaksi_id" => $_SESSION[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                cekKuning("UPDATE transaksi data...");
                                cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $_SESSION[$cCode]["main"]["referenceID"],
                                        "produk_id" => $rsltSpec->produk_id,
                                    ),
                                    "value" => $arrData_detail,
                                );
                            }
                        }
                    }

                    $dwsign = $tr->writeSignature($refMasterID, array(
                        "prev_id" => "",
                        "nomer" => "pembatalan jurnal",
                        "step_number" => "-" . $stepnum, // ini minus step number
                        "step_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['target'],
                        "step_name" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'],
                        "group_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['userGroup'],
                        "oleh_id" => $this->session->login['id'],
                        "oleh_nama" => $this->session->login['nama'],
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh " . $this->session->login['nama'],
                        //            "transaksi_id" => $no,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    cekKuning($this->db->last_query() . " [" . $this->db->affected_rows() . "]");

                    //                    matiHEre("uhuu");
                }
            }

            //endregion

            // region berlaku pembatalan transaksi bila ada config revertStep di model MdlRevertJurnal (true)
            if (isset($_SESSION[$cCode]['main']['pihakExternRevertStep']) && ($_SESSION[$cCode]['main']['pihakExternRevertStep'] == true)) {
                $referenceNextProp = (isset($_SESSION[$cCode]['main']['referenceNextProp']) && (sizeof($_SESSION[$cCode]['main']['referenceNextProp']) > 0)) ? $_SESSION[$cCode]['main']['referenceNextProp'] : array();
                if (sizeof($referenceNextProp) > 0) {
                    // update transaksi reference, step sebelumnya menjadi aktif lagi
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $dupState = $tr->updateData(array("id" => $referenceNextProp['trID']), array(
                        "next_step_code" => $referenceNextProp['code'],
                        "next_step_label" => $referenceNextProp['label'],
                        "next_group_code" => $referenceNextProp['groupID'],
                        "next_step_num" => $referenceNextProp['num'],
                        "step_current" => $referenceNextProp['step_num'],

                    )) or die("Failed to update tr next-state!");
                    cekHijau("BATAL :: " . $this->db->last_query() . " -- " . $this->db->affected_rows());
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $referenceNextProp['trID']),
                        "value" => array(
                            "next_step_code" => $referenceNextProp['code'],
                            "next_step_label" => $referenceNextProp['label'],
                            "next_group_code" => $referenceNextProp['groupID'],
                            "next_step_num" => $referenceNextProp['num'],
                            "step_current" => $referenceNextProp['step_num'],
                        ),
                    );


                    // update transaksi data reference, step sebelumnya menjadi aktif lagi
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("trash='0'");
                    $tr->addFilter("transaksi_id='" . $referenceNextProp['trID'] . "'");
                    $tr->setTableName($tr->getTableNames()['detail']);
                    $detailTmp = $tr->lookupAll()->result();
                    $detailData = array();
                    foreach ($detailTmp as $dTmpSpec) {
                        $detailData[$dTmpSpec->produk_id] = array(
                            "valid_qty" => $dTmpSpec->valid_qty,
                        );
                    }
                    cekOrange($referenceNextProp['detailGate']);
                    if (isset($_SESSION[$cCode][$referenceNextProp['detailGate']]) && ($_SESSION[$cCode][$referenceNextProp['detailGate']] != NULL)) {

                        foreach ($_SESSION[$cCode][$referenceNextProp['detailGate']] as $itemsSpec) {
                            //                        arrPrint($itemsSpec);
                            $valid_qty = isset($detailData[$itemsSpec['id']]['valid_qty']) ? $detailData[$itemsSpec['id']]['valid_qty'] : 0;
                            $valid_qty_new = $valid_qty + $itemsSpec['qty'];

                            $tr = new MdlTransaksi();
                            $tr->setFilters(array());
                            $tr->setTableName($tr->getTableNames()['detail']);
                            $ddupState = $tr->updateData(
                                array(
                                    "transaksi_id" => $referenceNextProp['trID'],
                                    "trash" => 0,
                                    "produk_id" => $itemsSpec['id'],
                                ), array(
                                "next_substep_code" => $referenceNextProp['code'],
                                "next_substep_label" => $referenceNextProp['label'],
                                "next_subgroup_code" => $referenceNextProp['groupID'],
                                "next_substep_num" => $referenceNextProp['num'],
                                "sub_step_current" => $referenceNextProp['step_num'],
                                "valid_qty" => $valid_qty_new,

                            )) or die("Failed to update tr next-state!");
                            cekHijau("BATAL :: " . $this->db->last_query() . " -- " . $this->db->affected_rows());
                            $mongUpdateList['update']['detail'][] = array(
                                "where" => array(
                                    "transaksi_id" => $referenceNextProp['trID'],
                                    "trash" => 0,
                                    "produk_id" => $itemsSpec['id'],
                                ),
                                "value" => array(
                                    "next_substep_code" => $referenceNextProp['code'],
                                    "next_substep_label" => $referenceNextProp['label'],
                                    "next_subgroup_code" => $referenceNextProp['groupID'],
                                    "next_substep_num" => $referenceNextProp['num'],
                                    "sub_step_current" => $referenceNextProp['step_num'],
                                    "valid_qty" => $valid_qty_new,
                                ),
                            );

                        }
                    }
                }
            }
            // endregion


            //region nulis paymentSource
            $stepCode = $this->configUi[$this->jenisTr]['steps'][1]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {

                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs[1] as $paymentSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $paymentMethod = isset($paymentSrcConfig['method']) ? $paymentSrcConfig['method'] : "insert";

                        if ($paymentMethod == "update") {
                            $filters = array(
                                "extern_id" => ""
                            );
                            //                            arrPrint($externSrc);
                            $tr->setFilters(array());
                            $tmpData = $tr->lookupPaymentSrcByJenis($paymentSrcConfig['jenisTarget'])->result();
                            if (sizeof($tmpData) > 0) {
                                //sudah ada update aja gak perlu insert
                                $prevID = $tmpData[0]->id;
                                $preValue = $tmpData[0]->sisa;
                                $currValue = isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0;
                                $newValue = $preValue + $currValue;
                                $where = array(
                                    "id" => $prevID,
                                    //                                    "transaksi_id" => $pSpec->transaksi_id,
                                );
                                $data = array(
                                    "tagihan" => $newValue,
                                    "sisa" => $newValue,
                                );
                                $tr->updatePaymentSrc($where, $data);
                                //                                cekHitam($this->db->last_query());
                            }
                            else {
                                //di insert baru
                                $tr->writePaymentSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                    "terbayar" => 0,
                                    "sisa" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                    "cabang_id" => (isset($externSrc['cabang_id']) && isset($_SESSION[$cCode]['main'][$externSrc['cabang_id']])) ? $_SESSION[$cCode]['main'][$externSrc['cabang_id']] : $_SESSION[$cCode]['main']['placeID'],
                                    "cabang_nama" => (isset($externSrc['cabang_nama']) && isset($_SESSION[$cCode]['main'][$externSrc['cabang_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cabang_nama']] : $_SESSION[$cCode]['main']['placeName'],
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "valas_id" => (isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']])) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => (isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']])) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => (isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']])) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : 0,
                                    "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                    "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                    "sisa_valas" => (isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']])) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                    "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",
                                    "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                ));
                            }


                        }
                        else {
                            //region cek duplikasi paymentsource
                            $tr->setFilters(array());
                            $tr->addFilter("transaksi_id='$insertID'");
                            $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                            // $tr->addFilter("target_jenis='759'");
                            $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                            if (sizeof($validateIsInserted) > 0) {
                                matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                            }
                            //endregion
                            $arrDataPym = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                "terbayar" => 0,
                                "sisa" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                "cabang_id" => (isset($externSrc['cabang_id']) && isset($_SESSION[$cCode]['main'][$externSrc['cabang_id']])) ? $_SESSION[$cCode]['main'][$externSrc['cabang_id']] : $_SESSION[$cCode]['main']['placeID'],
                                "cabang_nama" => (isset($externSrc['cabang_nama']) && isset($_SESSION[$cCode]['main'][$externSrc['cabang_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cabang_nama']] : $_SESSION[$cCode]['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => (isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']])) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => (isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']])) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => (isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']])) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : 0,
                                "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                "sisa_valas" => (isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']])) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                "extern_date2" => (isset($externSrc['extern_date2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_date2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_date2']] : "",
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($_SESSION[$cCode]['main'][$externSrc['payment_locked']])) ? $_SESSION[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : 0,
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : 0,
                            );
                            $tr->writePaymentSrc($insertID, $arrDataPym);
                        }

                        cekMerah($this->db->last_query());
                    }
                }


            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion


            //region nulis paymentAntiSource
            $stepCode = $this->configUi[$this->jenisTr]['steps'][1]['target'];
            $paymentSources = $this->config->item("payment_antiSource") != null ? $this->config->item("payment_antiSource") : array();
            if (array_key_exists($stepCode, $paymentSources)) {
                cekHitam(":: starting PAYMENT ANTI SOURCE");
                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $tr->writePaymentAntiSrc($insertID, array(
                            "jenis" => $stepCode,
                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                            "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                            "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                            "nomer" => $_SESSION[$cCode]['main']['nomer'],
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                            "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                            "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "valas_id" => isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                            "valas_nama" => isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                            "valas_nilai" => isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',
                            "tagihan_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                            "terbayar_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : '',
                            "sisa_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',

                        ));
                        //cekMerah($this->db->last_query());
                    }
                }


            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion


            //====registri value-gate
            if (isset($this->configCore[$this->jenisTr]['components'][$jenisTrTarget]) && sizeof($this->configCore[$this->jenisTr]['components'][$jenisTrTarget])) {
                $jurnalIndex = $this->configCore[$this->jenisTr]['components'][$jenisTrTarget];
            }
            else {
                if (isset($_SESSION[$cCode]["revert"]["jurnal"]) && sizeof($_SESSION[$cCode]["revert"]["jurnal"]) > 0) {
                    $jurnalIndex = $_SESSION[$cCode]["revert"]["jurnal"];
                }
                else {
                    $jurnalIndex = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget]) && sizeof($this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget])) {
                $jurnalPostProc = $this->configCore[$this->jenisTr]['postProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($_SESSION[$cCode]["revert"]["postProc"]) && sizeof($_SESSION[$cCode]["revert"]["postProc"]) > 0) {
                    $jurnalPostProc = $_SESSION[$cCode]["revert"]["postProc"];
                }
                else {
                    $jurnalPostProc = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget]) && sizeof($this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget])) {
                $jurnalPreProc = $this->configCore[$this->jenisTr]['preProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($_SESSION[$cCode]["revert"]["preProc"]) && sizeof($_SESSION[$cCode]["revert"]["preProc"]) > 0) {
                    $jurnalPreProc = $_SESSION[$cCode]["revert"]["preProc"];
                }
                else {
                    $jurnalPreProc = array();
                }
            }
            //---------------------------------------------------


            $baseRegistries = array(
                'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                'itemSrc' => isset($_SESSION[$cCode]['itemSrc']) ? $_SESSION[$cCode]['itemSrc'] : array(),
                'itemSrc_sum' => isset($_SESSION[$cCode]['itemSrc_sum']) ? $_SESSION[$cCode]['itemSrc_sum'] : array(),
                'items3' => isset($_SESSION[$cCode]['items3']) ? $_SESSION[$cCode]['items3'] : array(),
                'items3_sum' => isset($_SESSION[$cCode]['items3_sum']) ? $_SESSION[$cCode]['items3_sum'] : array(),
                'items4' => isset($_SESSION[$cCode]['items4']) ? $_SESSION[$cCode]['items4'] : array(),
                'items4_sum' => isset($_SESSION[$cCode]['items4_sum']) ? $_SESSION[$cCode]['items4_sum'] : array(),
                'items5_sum' => isset($_SESSION[$cCode]['items5_sum']) ? $_SESSION[$cCode]['items5_sum'] : array(),
                'items6_sum' => isset($_SESSION[$cCode]['items6_sum']) ? $_SESSION[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($_SESSION[$cCode]['items8_sum']) ? $_SESSION[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($_SESSION[$cCode]['items10_sum']) ? $_SESSION[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($_SESSION[$cCode]['rsltItems3']) ? $_SESSION[$cCode]['rsltItems3'] : array(),
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
                "receiptDetailFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayout[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailSrcFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayout[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),
                "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($_SESSION[$cCode]['items_noapprove']) ? $_SESSION[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $mongRegID = $doWriteReg;
            //            cekHitam($this->db->last_query());

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
                                        }
                                        else {
                                            cekhijau($paymentSrcConfig['label'] . " pada $stepCode $insertID BELUM ada, ditulis sekarang");
                                            $tr->writePaymentSrc($insertID, array(
                                                "_key" => $iKey,
                                                "jenis" => $stepCode,
                                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => $_SESSION[$cCode]['main_inputs'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $_SESSION[$cCode]['main_inputs'][$valueSrc],
                                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                                "oleh_id" => $this->session->login['id'],
                                                "oleh_nama" => $this->session->login['nama'],
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',
                                                "tagihan_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                                "terbayar_valas" => 0,
                                                "sisa_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',
                                            ));
                                        }
                                        //									cekMerah("paySrc: ".$this->db->last_query());

                                    }
                                    else {
                                        cekmerah($paymentSrcConfig['valueSrc'] . "/$iKey tidak untuk dieksekusi");
                                    }
                                }
                            }

                        }
                        else {
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
                                }
                                else {
                                    cekhijau("extStep belum terdaftar, sekarang hendak ditulis");
                                    $insertNew = $trA->writeExtStep($insertID, array(
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
                                    $mongoList['extras'][] = $insertNew;
                                    cekhijau($this->db->last_query());
                                }
                            }

                        }
                    }

                }
            }
            //endregion


            //==================================================================================================
            //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
            // bila step lebih dari 1
            if ($nextProp['num'] > 1) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->execLocker($_SESSION[$cCode]['main'], $nextProp['num'], NULL, $insertID);
            }
            // merelease locker payment source yang dicentang dari gerbang items
            if (sizeof($_SESSION[$cCode]['items']) > 0) {
                $selectorNotaLocker = isset($this->configUi[$this->jenisTr]['selectorNotaLocker']) ? $this->configUi[$this->jenisTr]['selectorNotaLocker'] : array();
                if ((sizeof($selectorNotaLocker) > 0)) {
                    if (isset($selectorNotaLocker["enabled"]) && ($selectorNotaLocker["enabled"] == true)) {
                        $this->load->model("Mdls/MdlLockerTransaksi");
                        foreach ($_SESSION[$cCode]['items'] as $tridd => $xx) {
                            $lt = New MdlLockerTransaksi();
                            $lt->execLocker($_SESSION[$cCode]['main'], 0, $tridd, NULL);
                        }
                    }
                }
            }
            //==========================================================================================================

            $masterID = $insertID;
//            mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");
//            cekMerah("MULAI LAST_VALIDATE");
//            $this->lastValidate($insertID);

            cekHitam(":::: MULAI JURNAL SETOR ::::");
            cekHitam(":::: MULAI JURNAL SETOR ::::");
            //==========================================================================================================
            $this->load->model("Mdls/MdlCompany");
            $mc = New MdlCompany();
            $mcTmp = $mc->lookupAll()->result();
            $autoJurnalSetor = 0;
            if (sizeof($mcTmp) > 0) {
                $autoJurnalSetor = $mcTmp[0]->setor;
            }
            if ($autoJurnalSetor == 1) {

                //region pre-processors auto (item)
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {


                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                    echo "ITEM NUM LABELS";

                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];

                            echo "sub-preproc: $comName, initializing values <br>";

                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $tmpOutParams[$cCtr] = array();

                                //                            $id = $dSpec['id'];
                                $id = $xid;
                                $subParams = array();

                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }

                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        //									$subParams['static']["transaksi_id"] = $masterID;
                                    }


                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                }
                                //                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                //                            arrPrint($subParams);
                                //mati_disini();
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;


                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                    //                                echo "sub preproc #$it: $comName, sending values <br>";

                                    $mdlName = "Pre" . ucfirst($comName);
                                    $this->load->model("Preprocs/" . $mdlName);
                                    $m = new $mdlName($resultParams);


                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                        $tobeExecuted = true;
                                    }
                                    else {
                                        $tobeExecuted = false;
                                    }

                                    if ($tobeExecuted) {
                                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $gotParams = $m->exec();

                                        cekmerah("gotparams dari pre-proc $comName");
                                        arrprint($gotParams);


                                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                            foreach ($gotParams as $gateName => $paramSpec) {
                                                cekBiru(":: getParams inject ke $gateName ::");
                                                if (!isset($_SESSION[$cCode][$gateName])) {
                                                    $_SESSION[$cCode][$gateName] = array();
                                                    //                                    cekhijau("building the session: $gateName");
                                                }
                                                else {
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
                                                                cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                            }

                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    cekHitam("srcGateName = $srcGateName :: " . __LINE__);
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
                                                        }
                                                    }
                                                }
                                                //                                    arrPrint($items);die();
                                            }


                                        }

                                    }
                                    else {
                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                    }


                                }
                            }
                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }


                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);


                    //region injector gerbang value untuk pembatalan ppv dan selisih
                    if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                        $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                        $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                        switch ($jenisTrReference) {
                            case "460":
                                $tempCalculate = array(
                                    //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                    //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                    //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                    //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                );
                                break;
                            default:
                                $tempCalculate = array(
                                    "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                    "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                    "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                );
                                break;
                        }
                        //                    $tempCalculate = array(
                        //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                        //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                        //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                        //                    );

                        //arrPrintWebs($tempCalculate);
                        foreach ($replace['recalculate'] as $iKey => $gate) {
                            $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                        }

                        cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                    }

                    //endregion


                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                //region pre-processors auto (master)
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['preProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {

                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();


                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {

                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                            $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    $subParams['static'][$key] = $realValue;

                                    //                                cekPink2("$comName == $value || $realValue");
                                    //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                    //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            $tmpOutParams[$cCtr] = $subParams;

                            $mdlName = "Pre" . ucfirst($comName);
                            $this->load->model("Preprocs/" . $mdlName);
                            $m = new $mdlName($resultParams);


                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
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

                                        if ($switchResultParams == true) {

                                            foreach ($gSpec as $id => $ggSpec) {
                                                if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                    $_SESSION[$cCode][$gateName][$id] = array();
                                                }

                                                if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                    if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                        foreach ($ggSpec as $key => $val) {
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
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
                                                    }
                                                }

                                            }
                                        }
                                        else {
                                            if (isset($_SESSION[$cCode]['main'])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $_SESSION[$cCode]['main'][$key] = $val;
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
                                                    cekHere("$id === $key => $label");
                                                    if (isset($_SESSION[$cCode]['main'][$key])) {
                                                        $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }

                            cekPink2("fillvalue setelah $comName");
                            $this->load->helper("he_value_builder");
                            fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }

                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);
                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                //region processing sub-components auto
//            $componentGate['detail'] = array();
//            $componentConfig['detail'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                $iterator = isset($this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
//            $componentConfig['detail'] = $iterator;
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            echo "sub-component: $comName, initializing values <br>";
                            //                        cekHitam(__LINE__);
                            //                        $tmpOutParams[$cCtr] = array();

                            //                        cekhitam("$comName filterneeded: $filterNeeded");
                            //                        cekhitam("mau mengiterasi $srcGateName");
                            //                        cekhitam("telah mengiterasi $srcGateName");
                            //
                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                        $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
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

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                    //cekHitam("cetak tmpOutParams");

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {

                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                            }
                        }
                        echo "sub component: $comName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        //                    arrPrint($tmpOutParams[$cCtr]);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    //cekKuning("subcomponents is not set");
                }
                //endregion

                //region processing main components auto
//            $componentGate['master'] = array();
//            $componentConfig['master'] = array();
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $iterator = isset($this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['componentsAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "component # $cCtr: $comName<br>";

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                }

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                                //                            cekKuning("LOOP $key diisi dengan $realValue");
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                        }
                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                        }
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
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    //cekKuning("components is not set");
                }
                //endregion

                //region processing sub-post-processors auto, always
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['detail'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();
                        if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        cekHitam("gate: $srcGateName, dengan key $id");
                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $_SESSION[$cCode]['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                        $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                    }

                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                }

                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                        }
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($_SESSION[$cCode][$srcGateName])) {
                            echo "[$cCtr] sub-postProcessor: $comName, sending values <br>";

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            //arrPrint($tmpOutParams[$cCtr]);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekPink($this->db->last_query());
                        }

                    }
                }
                //endregion

                //region processing main-post-processors auto, always
                $iterator = isset($this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['postProcessorAuto'][$jenisTrTarget]['master'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "post-processor: $comName<br>LINE: " . __LINE__;

                        $dSpec = $_SESSION[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($tmpOutParams['static'][$k])) {
                                        $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $_SESSION[$cCode]['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                }
                            }

                            $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (isset($tComSpec['static2'])) {
                            //cekHere("DISINI OIII");
                            foreach ($tComSpec['static2'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;

                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }

                            $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                            $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                            $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                        }

                        //lgShowError("Ada kesalahan",);
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        // cekBiru("kiriman komponem $comName");
                        // arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
                else {

                }
                //endregion
            }

            //==========================================================================================================

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


            // dimatikan karena tidak otomatis jurnal pengembalian uang ke konsumen
            $pakai_pengembalian_kekonsumen = 0;
            if ($pakai_pengembalian_kekonsumen == 1) {
                cekHijau("<h3>MULAI JURNAL PENGEMBALIAN DEPOSIT KE KONSUMEN</h3>");
                if ($_SESSION[$cCode]["main"]["nilai_pengembalian_deposit"] > 0) {

                    //region pre-processors Deposit (item)
                    $iterator = isset($this->configCore[$this->jenisTr]['preProcessorDeposit'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['preProcessorDeposit'][$jenisTrTarget]['detail'] : array();
                    if (sizeof($iterator) > 0) {


                        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                        echo "ITEM NUM LABELS";

                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];

                                echo "sub-preproc: $comName, initializing values <br>";

                                foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                    $tmpOutParams[$cCtr] = array();

                                    //                            $id = $dSpec['id'];
                                    $id = $xid;
                                    $subParams = array();

                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;

                                        }

                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            //									$subParams['static']["transaksi_id"] = $masterID;
                                        }


                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                    }
                                    //                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                    //                            arrPrint($subParams);
                                    //mati_disini();
                                    if (sizeof($subParams) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;


                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
                                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                        //                                echo "sub preproc #$it: $comName, sending values <br>";

                                        $mdlName = "Pre" . ucfirst($comName);
                                        $this->load->model("Preprocs/" . $mdlName);
                                        $m = new $mdlName($resultParams);


                                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                            $tobeExecuted = true;
                                        }
                                        else {
                                            $tobeExecuted = false;
                                        }

                                        if ($tobeExecuted) {
                                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                            $gotParams = $m->exec();

                                            cekmerah("gotparams dari pre-proc $comName");
                                            arrprint($gotParams);


                                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                                foreach ($gotParams as $gateName => $paramSpec) {
                                                    cekBiru(":: getParams inject ke $gateName ::");
                                                    if (!isset($_SESSION[$cCode][$gateName])) {
                                                        $_SESSION[$cCode][$gateName] = array();
                                                        //                                    cekhijau("building the session: $gateName");
                                                    }
                                                    else {
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
                                                                    cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                    $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                                }

                                                            }
                                                        }
                                                        //==inject gotParams to child gate
                                                        cekHitam("srcGateName = $srcGateName :: " . __LINE__);
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
                                                            }
                                                        }
                                                    }
                                                    //                                    arrPrint($items);die();
                                                }


                                            }

                                        }
                                        else {
                                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                        }


                                    }
                                }
                            }
                        }
                        else {
                            //cekKuning("sub-preproc is not set");
                        }


                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);


                        //region injector gerbang value untuk pembatalan ppv dan selisih
                        if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                            $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                            $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                            switch ($jenisTrReference) {
                                case "460":
                                    $tempCalculate = array(
                                        //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                        //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                        //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                        //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                    );
                                    break;
                                default:
                                    $tempCalculate = array(
                                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                    );
                                    break;
                            }
                            //                    $tempCalculate = array(
                            //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                            //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                            //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                            //                    );

                            //arrPrintWebs($tempCalculate);
                            foreach ($replace['recalculate'] as $iKey => $gate) {
                                $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                            }

                            cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                        }

                        //endregion


                    }
                    else {
                        echo("no processor defined. skipping preprocessor..<br>");
                    }
                    //endregion

                    //region pre-processors Deposit (master)
                    $iterator = isset($this->configCore[$this->jenisTr]['preProcessorDeposit'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['preProcessorDeposit'][$jenisTrTarget]['master'] : array();
                    if (sizeof($iterator) > 0) {

                        $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();


                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {

                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                                $subParams = array();

                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                        $subParams['static'][$key] = $realValue;

                                        //                                cekPink2("$comName == $value || $realValue");
                                        //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                        //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                                    }

                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        //									$subParams['static']["transaksi_id"] = $masterID;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                }
                                $tmpOutParams[$cCtr] = $subParams;

                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);


                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
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

                                            if ($switchResultParams == true) {

                                                foreach ($gSpec as $id => $ggSpec) {
                                                    if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                        $_SESSION[$cCode][$gateName][$id] = array();
                                                    }

                                                    if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                        if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                            foreach ($ggSpec as $key => $val) {
                                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
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
                                                        }
                                                    }

                                                }
                                            }
                                            else {
                                                if (isset($_SESSION[$cCode]['main'])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode]['main'][$key] = $val;
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
                                                        cekHere("$id === $key => $label");
                                                        if (isset($_SESSION[$cCode]['main'][$key])) {
                                                            $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                        }
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }

                                cekPink2("fillvalue setelah $comName");
                                $this->load->helper("he_value_builder");
                                fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis);


                            }
                        }
                        else {
                            //cekKuning("sub-preproc is not set");
                        }

                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor);
                    }
                    else {
                        echo("no processor defined. skipping preprocessor..<br>");
                    }
                    //endregion

                    //region processing sub-components Deposit
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $filterNeeded = false;
                    $iterator = isset($this->configCore[$this->jenisTr]['componentsDeposit'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['componentsDeposit'][$jenisTrTarget]['detail'] : array();
                    $revertedTarget = "";
                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $gg = 0;
                            $srcGateName = $tComSpec['srcGateName'];
                            foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    //                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                    cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                    $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                }
                                cekHitam(":: $comName ::");
                                $mdlName = "Com" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    //cekLime($mdlName. "line");
                                    $filterNeeded = true;
                                }
                                else {
                                    cekLime($mdlName . "like");
                                    $filterNeeded = false;
                                }
                                echo "sub-component: $comName, initializing values <br>";
                                //                        cekHitam(__LINE__);
                                //                        $tmpOutParams[$cCtr] = array();

                                //                        cekhitam("$comName filterneeded: $filterNeeded");
                                //                        cekhitam("mau mengiterasi $srcGateName");
                                //                        cekhitam("telah mengiterasi $srcGateName");
                                //
                                $subParams = array();
                                //arrPrint($tComSpec);
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        cekMerah(":: $key => $value ::");
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                            $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                                        }

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
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

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                //arrPrint($subParams);
                                if (sizeof($subParams) > 0) {
                                    //                            arrprint($subParams);
                                    cekhitam("subparam ada isinya");
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                        //                                CekHijiau("asem" .$gg++);
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }

                            $componentGate['detail'][$cCtr] = $subParams;
                        }
                        //cekHitam("cetak tmpOutParams");

                        foreach ($iterator as $cCtr => $tComSpec) {
                            $srcGateName = $tComSpec['srcGateName'];
                            foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {

                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                                    //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                }
                            }
                            echo "sub component: $comName, sending values <br>";

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            //===filter value nol, jika harus difilter
                            //                    arrPrint($tmpOutParams[$cCtr]);
                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                cekMerah("$comName dieksekusiii");
                                arrPrint($tmpOutParams[$cCtr]);
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                cekBiru($this->db->last_query());
                            }
                            else {
                                cekMerah("$comName tidak eksekusi");
                            }

                        }
                    }
                    else {
                        //cekKuning("subcomponents is not set");
                    }
                    //endregion

                    //region processing main components Deposit
                    $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                    $iterator = isset($this->configCore[$this->jenisTr]['componentsDeposit'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['componentsDeposit'][$jenisTrTarget]['master'] : array();
                    if (sizeof($iterator) > 0) {
                        $componentConfig['master'] = $iterator;
                        $cCtr = 0;
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $cCtr++;
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                            }
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            echo "component # $cCtr: $comName<br>";

                            $dSpec = $_SESSION[$cCode][$srcGateName];
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                    }

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                    $tmpOutParams['loop'][$key] = $realValue;
                                    //                            cekKuning("LOOP $key diisi dengan $realValue");
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                    $tmpOutParams['static'][$key] = $realValue;

                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                }
                                $tmpOutParams['static']["urut"] = $cCtr;
                                $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                            }
                            if (isset($tComSpec['static2'])) {
                                //cekHere("DISINI OIII");
                                foreach ($tComSpec['static2'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                    $tmpOutParams['static2'][$key] = $realValue;

                                }
                                if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                    $tmpOutParams['static2']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                    $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                                }

                                $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                            }
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
                            $componentGate['master'][$cCtr] = $tmpOutParams;
                        }
                    }
                    else {
                        //cekKuning("components is not set");
                    }
                    //endregion

                    //region processing sub-post-processors Deposit, always
                    $iterator = isset($this->configCore[$this->jenisTr]['postProcessorDeposit'][$jenisTrTarget]['detail']) ? $this->configCore[$this->jenisTr]['postProcessorDeposit'][$jenisTrTarget]['detail'] : array();
                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                            $tmpOutParams[$cCtr] = array();
                            if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                                foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                    $id = $xid;
                                    $subParams = array();
                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {

                                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                            $subParams['loop'][$key] = $realValue;

                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            cekHitam("gate: $srcGateName, dengan key $id");
                                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;

                                        }
                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            $subParams['static']["transaksi_id"] = $insertID;
                                        }
                                        if (!isset($subParams['static']["transaksi_no"])) {
                                            $subParams['static']["transaksi_no"] = $insertNum;
                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($subParams['static'][$k])) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }

                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                            $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                        }

                                        $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                    }

                                    if (sizeof($subParams) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                            }
                        }

                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            if (isset($_SESSION[$cCode][$srcGateName])) {
                                echo "[$cCtr] sub-postProcessor: $comName, sending values <br>";

                                $mdlName = "Com" . ucfirst($comName);
                                $this->load->model("Coms/" . $mdlName);
                                //arrPrint($tmpOutParams[$cCtr]);
                                $m = new $mdlName();
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                cekPink($this->db->last_query());
                            }

                        }
                    }
                    //endregion

                    //region processing main-post-processors Deposit, always
                    $iterator = isset($this->configCore[$this->jenisTr]['postProcessorDeposit'][$jenisTrTarget]['master']) ? $this->configCore[$this->jenisTr]['postProcessorDeposit'][$jenisTrTarget]['master'] : array();
                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            echo "post-processor: $comName<br>LINE: " . __LINE__;

                            $dSpec = $_SESSION[$cCode][$srcGateName];
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                    $tmpOutParams['loop'][$key] = $realValue;

                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                }
                                if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                    foreach ($paramPatchers[$comName] as $k => $v) {
                                        if (!isset($tmpOutParams['static'][$k])) {
                                            $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                }
                                if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                    $jenis = $_SESSION[$cCode]['main']['jenis'];
                                    foreach ($paramForceFillers[$comName] as $k => $v) {
                                        $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }

                                $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                            }
                            if (isset($tComSpec['static2'])) {
                                //cekHere("DISINI OIII");
                                foreach ($tComSpec['static2'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                    $tmpOutParams['static2'][$key] = $realValue;

                                }
                                if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                    $tmpOutParams['static2']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                    $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                                }

                                $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static2']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                            }

                            //lgShowError("Ada kesalahan",);
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            // cekBiru("kiriman komponem $comName");
                            // arrPrint($tmpOutParams);
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekHitam($this->db->last_query());
                        }
                    }
                    else {

                    }
                    //endregion

                }
                cekHijau("<h3>SELESAI JURNAL PENGEMBALIAN DEPOSIT KE KONSUMEN</h3>");
            }
            else {
                if ($_SESSION[$cCode]["main"]["nilai_pengembalian_deposit"] > 0) {
                    $autoRequest = isset($this->configUi[$this->jenisTr]["kelebihanBayarMarketplace"]["autoRequestPengembalian"]) ? $this->configUi[$this->jenisTr]["kelebihanBayarMarketplace"]["autoRequestPengembalian"] : array();
                    if (isset($autoRequest["enabled"]) && ($autoRequest["enabled"] == true)) {
                        $jenisTr = $autoRequest["connectTo"];
                        $no = 0;
                        $stepNum = 1;
                        $stepNumCurrent = 1;
                        $itemsReplacer = array();
                        $arrPelakuTransaksi = array();
                        $sessionData = $_SESSION["_TR_" . $this->jenisTr];
                        $this->createRequest($jenisTr, $no, $stepNum, $stepNumCurrent, $itemsReplacer, $arrPelakuTransaksi, $sessionData);

                    }
                }
            }


            cekKuning(":: mulai cek rek besar dan rek pembantu");
            $cabangID_validate = $this->session->login['cabang_id'];
            validateAllBalances();

            if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72" || $_SERVER['REMOTE_ADDR'] == "192.168.5.1") {
                $from_ip = $_SERVER['REMOTE_ADDR'];
                mati_disini("LINE: " . __LINE__ . " FROM:IP ($from_ip) <br><br>TRANSAKSI BERHASIL, saat ini sedang maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

            }
//            mati_disini("LINE: " . __LINE__ . " TRANSAKSI BERHASIL, saat ini sedang maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");


            // cek ulang sesi bookingnumber masih ada atau hilang atau kosong... bila hilang/kosong maka hentikan.
            if (!isset($_SESSION[$cCode]["main"]["bookingNumber"])) {
                $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                mati_disini($msg);
            }
            elseif ($_SESSION[$cCode]["main"]["bookingNumber"] == null) {
                $msg = "Nomer Booking transaksi baru belum terdaftar. code: " . __LINE__;
                mati_disini($msg);
            }
            if ($nomerBookingTransaksi != $_SESSION[$cCode]["main"]["bookingNumber"]) {
                $msg = "Transaksi gagal disimpan karena terdeteksi ganda, silahkan refresh halaman ini. code: " . __LINE__;
                mati_disini($msg);
            }
            cekHitam("HAPUS KODE BOOKING...");
            $_SESSION[$cCode]["main"]["bookingNumber"] = null;
            unset($_SESSION[$cCode]["main"]["bookingNumber"]);
            cekHitam("HAPUS KODE BOOKING DONE...");


            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

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
            if (isset($this->configUi[$this->jenisTr]['steps'][$nextNum])) {
                $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->configUi[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
                $this->session->errMsg .= "This entry needs to be authorized by <strong class='text-blue'>" . $this->configUi[$this->jenisTr]['steps'][$nextNum]['userGroup'] . "</strong><br>";
                $trBackLink = base_url() . get_class($this) . "/viewIncomplete/" . $this->jenisTr;

            }
            else {
                $this->session->errMsg .= "transaction state: <strong class='badge bg-grey text-white'>" . $this->configUi[$this->jenisTr]['steps'][$nextNum]['stateLabel'] . "</strong><br>";
                $trBackLink = base_url() . get_class($this) . "/viewHistory/" . $this->jenisTr;

            }
            $trBackClick = "location.href='$trBackLink'";
            //            $this->session->errMsg .= "<a href='javascript:void(0)' onclick=\"$trBackClick\">view entry</a><br>";
            //endregion


            if (strlen($rawPrevURL) > 3) {
                $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div><img class=\"text-center text-bold\" width=\"35%\" src=\"//cdn.mayagrahakencana.com/assets/images/d60eb1v-79212624-e842-4e55-8d58-4ac7514ca8e4.gif\"><br/><h2>MOHON TUNGGU, SYSTEM SEDANG MEMUAT DATA...</h2></div>').load('$prevUrl'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";


                echo "<script>top.open_holdon();$actionTarget</script>";

                //                echo "</body>";
                //                echo "</html>";
            }
            else {
                if (isset($this->configLayout[$this->jenisTr]['allowPrint']['1'])) {
                    $printSettings = isset($this->configLayout[$this->jenisTr]['allowPrint']['1']) ? $this->configLayout[$this->jenisTr]['allowPrint']['1'] : array();
                    $printLocation = $this->configLayout[$this->jenisTr]['printLocation'];
                    if (isset($printSettings['size'])) {
                        cekkuning("detecting printing size");

                        switch ($printSettings['size']) {
                            case "normal":

                                //                                cekkuning("size: NORMAL");
                                echo "<script>";
                                echo "top.popBig('" . MODUL_PATH . $printLocation . "/" . $this->jenisTr . "/" . "$tmpNomorNota');";
                                echo "top.location.reload();";
                                echo "</script>";
                                break;
                            case "small":
                                //                                cekkuning("size: SMALL");
                                echo "<script>";
                                echo "top.popSmall('" . MODUL_PATH . $printLocation . "/" . $this->jenisTr . "/" . "$tmpNomorNota');";
                                echo "top.location.reload();";
                                echo "</script>";
                                break;
                            default:
                                //                                cekkuning("size: UNKNOWN");
                                break;
                        }


                    }
                    else {
                        cekkuning("not printing");
                        $arrAlert = array(
                            "type" => "success",
                            "title" => "Transaction saved",
                            //                        "html" => "your order has been saved and ready to process",
                            "html" => $this->session->errMsg,
                            "timer" => "1500",
                            "showConfirmButton" => false,
                            "allowOutsideClick" => false,
                        );
                        echo swalAlert($arrAlert);
                        echo "<script>topReload(1500)</script>";
                        echo topReload();
                        echo "</script>";
                        unset($this->session->errMsg);
                    }

                }
                else {
                    //                    cekkuning("settings dont exist, not printing");
                    $arrAlert = array(
                        "type" => "success",
                        "title" => "Transaction saved",
                        //                        "html" => "your order has been saved and ready to process",
                        "html" => $this->session->errMsg,
                        "timer" => "1500",
                        "showConfirmButton" => false,
                        "allowOutsideClick" => false,
                    );
                    echo swalAlert($arrAlert);
                    echo "<script>topReload(1500)</script>";
                    echo topReload();
                    echo "</script>";
                    unset($this->session->errMsg);
                }

            }

        }
        else {
            die("the gate index you want to debug has not been formed yet!");
        }
    }

    public function recordColumn()
    {

        $cCode = $this->cCode;
        $colName = $this->uri->segment(5);
        $val = urldecode($_GET['val']);
        $valCol = isset($_GET['valCol']) ? $_GET['valCol'] : null;
        $valValue = isset($_GET['valValue']) ? urldecode($_GET['valValue']) : null;


        $_SESSION[$cCode]['main'][$colName] = $val;
        $_SESSION[$cCode]['main'][$colName] = $val;
        if ($valValue != null && $valCol != null) {
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
            $_SESSION[$cCode]['main'][$valCol] = $valValue;
        }

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());
        $initMasterValues = array(
            "olehID" => my_id(),
            "olehName" => my_name(),
            "sellerID" => my_id(),
            "sellerName" => my_name(),
            "placeID" => my_cabang_id(),
            "placeName" => my_cabang_nama(),
            "divID" => my_div_id(),
            "divName" => my_div_nama(),
            "cabangID" => my_cabang_id(),
            "cabangName" => my_cabang_nama(),
            "gudangID" => my_gudang_id(),
            "gudangName" => my_gudang_nama(),
            "jenis_usaha" => my_jenis_usaha(),
            "tokoID" => my_toko_id(),
            "tokoNama" => my_toko_nama(),
            "jenisTr" => $this->jenisTr,
            "jenisTrMaster" => $this->jenisTr,
            "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
            "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
            "stepNumber" => 1,
            "stepCode" => $this->configUiJenis['steps'][1]['target'],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),
            // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        );
        $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);

        /* --------------------------------------------------
         * ngereload shoping cart dlm modul
         * --------------------------------------------------*/
        echo "<script>";
        echo "  if(top.document.getElementById('shopping_cart')){";
        echo "  top.$('#shopping_cart').load('" . base_url() . $this->modul . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
        echo "  }";
        echo "</script>";


//        $addQS = "";
//        if (isset($_GET['populate']) && $_GET['populate'] == 1) {
////            $addQS .= "&populate=1&popValue=" . $_GET['popValue'] . "&popAcuanSrc=" . $_GET['popAcuanSrc'] . "&popAcuanTarget=" . $_GET['popAcuanTarget'];
//
//            $this->populateValues();
//
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0$addQS';";
//            echo "</script>";
//        }
//        else {
//            echo "<script>";
//            echo "top.document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?selID=0&stopHere=1';";
//            echo "</script>";
//        }

    }

    public function testing()
    {
        $jenisTr = "9467";
        $no = 0;
        $stepNum = 1;
        $stepNumCurrent = 1;
        $itemsReplacer = array();
        $arrPelakuTransaksi = array();
        $sessionData = $_SESSION["_TR_749"];

        $this->db->trans_start();

        $this->createRequest($jenisTr, $no, $stepNum, $stepNumCurrent, $itemsReplacer, $arrPelakuTransaksi, $sessionData);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


    }

    public function createRequest($jenisTr, $no, $stepNum, $stepNumCurrent, $itemsReplacer = array(), $arrPelakuTransaksi = array(), $sessionData)
    {
        $this->jenisTr = $jenisTr;
        $this->cCode = $cCode = "_TR_" . $this->jenisTr;

        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
        $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");
        $this->modul = $modul_transaksi = $this->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];
        $swappedKeys = isset($configUiMasterModulJenis['swappedKeys']) ? $configUiMasterModulJenis['swappedKeys'] : array();
        $copyKolomAutoDistribusi = isset($configUiMasterModulJenis['copyKolomAutoDistribusi']) ? $configUiMasterModulJenis['copyKolomAutoDistribusi'] : array();
        $elementConfigs = isset($configUiMasterModulJenis['receiptElements']) ? $configUiMasterModulJenis['receiptElements'] : array();
        $receiptElementsAutoGenerate = isset($configUiMasterModulJenis['receiptElementsAutoGenerate']) ? $configUiMasterModulJenis['receiptElementsAutoGenerate'] : array();

        // region cek referensi so dan buat session create transaksi dulu
        if (sizeof($sessionData["items"]) > 0) {
            foreach ($sessionData["items"] as $idx => $idxSpec) {
                if ($idxSpec["nilai_pengembalian_deposit"] > 0) {
                    $nilai_pengembalian_deposit = $idxSpec["nilai_pengembalian_deposit"];
                    $extern_id = $idxSpec["extern_id"];
                    $extern_nama = $idxSpec["extern_nama"];

                    if (isset($_SESSION[$cCode])) {
                        $_SESSION[$cCode] = NULL;
                        unset($_SESSION[$cCode]);
                    }
                    $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $configUiMasterModulJenis);
                    heInitGates_he_cart($this->jenisTr, $initMasterValues);
                    //----
                    $arrMain = array(
                        "ppnFactor" => $this->session->login["ppnFactor"],
                        "pihakID" => $extern_id,
                        "pihakName" => $extern_nama,
                        "pihakName2" => $extern_nama,
                        "pihakDisc" => 0,
                        "customerID" => $extern_id,
                        "customerName" => $extern_nama,
                        "marketplaceID" => $sessionData["main"]["marketplaceID"],
                        "marketplaceNama" => $sessionData["main"]["marketplaceNama"],
                        "marketplaceName" => $sessionData["main"]["marketplaceName"],
                        "cash_account_id" => $sessionData["main"]["cash_account_id"],
                        "cash_account_nama" => $sessionData["main"]["cash_account_nama"],
                        "cash_account" => $sessionData["main"]["cash_account_id"],
                        "cash_account__label" => $sessionData["main"]["cash_account_nama"],
                        "cash_account__nama" => $sessionData["main"]["cash_account_nama"],
                        "cash_account__folders" => $sessionData["main"]["cash_account__folders"],
                        "cash_account__folders_nama" => $sessionData["main"]["cash_account__folders_nama"],
                    );
                    $arrDetail = array(
                        1 => array(
                            "handler" => "kas/_processSelectBiaya",
                            "id" => 1,
                            "jml" => 1,
                            "qty" => 1,
                            "harga" => $nilai_pengembalian_deposit,
                            "nilai_untung" => $nilai_pengembalian_deposit,
                            "nilai_rugi" => 0,
                            "nilai_final_rugilaba" => $nilai_pengembalian_deposit,
                            "subtotal" => $nilai_pengembalian_deposit,
                            "nama" => "uang muka tanpa ppn",
                            "coa_code" => 2010050050,
                            "jenis" => "uang muka",
                            "harga_2010050050" => $nilai_pengembalian_deposit,
                            "name" => "uang muka tanpa ppn",
                        ),
                    );
                    foreach ($arrMain as $key => $val) {
                        $_SESSION[$cCode]["main"][$key] = $val;
                    }
                    $_SESSION[$cCode]["items"] = $arrDetail;
                    //----
                    $this->load->model("MdlTransaksi");
                    //----
                    if (sizeof($elementConfigs) > 0) {
                        foreach ($elementConfigs as $eName => $eSpec) {
                            $elementConfigs[$eName]['autoSelect'] = false;
                            if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                                if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                                    $defValueSrc = $eSpec['defaultValue'];
                                    switch ($eSpec['elementType']) {
                                        case "dataModel":
                                            heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                            break;
                                        case "dataField":
                                            heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $configUiMasterModulJenis);
                                            break;
                                    }
                                    $elementConfigs[$eName]['autoSelect'] = true;
                                }
                                else {//==cek apakah pilihannya cuma satu
                                    if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {
                                    }
                                    else {
                                        switch ($eSpec['elementType']) {
                                            case "dataModel":
                                                $amdlName = $eSpec['mdlName'];
                                                $this->load->model("Mdls/" . $amdlName);
                                                $labelSrc = $eSpec['labelSrc'];
                                                $keySrc = $eSpec['key'];
                                                $oo = new $amdlName();
                                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                                if (sizeof($aFilter) > 0) {
                                                    $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                                }
                                                $tmpo = $oo->lookupAll()->result();
                                                if (sizeof($tmpo) == 1) {
                                                    $usedKey = $eSpec['key'];
                                                    $defValueSrc = $tmpo[0]->$usedKey;
                                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                                }
                                                break;
                                            case "dataField":
                                                break;
                                        }
                                    }
                                }
                            }
                            else {
                                if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {
                                }
                                else {
                                    switch ($eSpec['elementType']) {
                                        case "dataModel":
                                            $amdlName = $eSpec['mdlName'];
                                            $this->load->model("Mdls/" . $amdlName);
                                            $labelSrc = $eSpec['labelSrc'];
                                            $keySrc = $eSpec['key'];
                                            $oo = new $amdlName();
                                            $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                            if (sizeof($aFilter) > 0) {
                                                $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                            }
                                            $tmpo = $oo->lookupAll()->result();
                                            if (sizeof($tmpo) == 1) {
                                                $usedKey = $eSpec['key'];
                                                $defValueSrc = $tmpo[0]->$usedKey;
                                                heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                            }
                                            break;
                                        case "dataField":
                                            break;
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($receiptElementsAutoGenerate) > 0) {
                        foreach ($receiptElementsAutoGenerate as $eName => $eSpec) {
                            $receiptElementsAutoGenerate[$eName]['autoSelect'] = false;
                            if (!isset($_SESSION[$cCode]['main_elements'][$eName])) {
                                if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                                    $defValueSrc = $eSpec['defaultValue'];
                                    switch ($eSpec['elementType']) {
                                        case "dataModel":
                                            heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                            break;
                                        case "dataField":
                                            heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $configUiMasterModulJenis);
                                            break;
                                    }
                                    $receiptElementsAutoGenerate[$eName]['autoSelect'] = true;
                                }
                                else {//==cek apakah pilihannya cuma satu
                                    if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {
                                    }
                                    else {
                                        switch ($eSpec['elementType']) {
                                            case "dataModel":
                                                $amdlName = $eSpec['mdlName'];
                                                $this->load->model("Mdls/" . $amdlName);
                                                $labelSrc = $eSpec['labelSrc'];
                                                $keySrc = $eSpec['key'];
                                                $oo = new $amdlName();
                                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                                arrPrint($aFilter);
                                                if (sizeof($aFilter) > 0) {
                                                    $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                                }
                                                $tmpo = $oo->lookupAll()->result();
                                                // showLast_query("biru");
                                                if (sizeof($tmpo) == 1) {
                                                    $usedKey = $eSpec['key'];
                                                    $defValueSrc = $tmpo[0]->$usedKey;
                                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                                }
                                                break;
                                            case "dataField":
                                                break;
                                        }
                                    }
                                }
                            }
                            else {
                                if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {
                                }
                                else {
                                    switch ($eSpec['elementType']) {
                                        case "dataModel":
                                            $amdlName = $eSpec['mdlName'];
                                            $this->load->model("Mdls/" . $amdlName);
                                            $labelSrc = $eSpec['labelSrc'];
                                            $keySrc = $eSpec['key'];
                                            $oo = new $amdlName();
                                            $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                            if (sizeof($aFilter) > 0) {
                                                $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
                                            }
                                            $tmpo = $oo->lookupAll()->result();
                                            if (sizeof($tmpo) == 1) {
                                                $usedKey = $eSpec['key'];
                                                $defValueSrc = $tmpo[0]->$usedKey;
                                                heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiMasterModulJenis);
                                            }
                                            break;
                                        case "dataField":
                                            break;
                                    }
                                }
                            }
                        }
                    }
                    //----
                    $this->load->helper("he_value_builder");
                    fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis);


                    $jenisTrTarget = isset($configUiMasterModulJenis["steps"][1]["target"]) ? $configUiMasterModulJenis["steps"][1]["target"] : NULL;
                    $relOptionConfigs = isset($configUiMasterModulJenis['relativeOptions']) ? $configUiMasterModulJenis['relativeOptions'] : array();
                    $inputLabels = array();
                    $inputAuthConfigs = array();

                    $this->load->model("MdlTransaksi");
                    $this->load->library("FieldCalculator");
                    $cal = new FieldCalculator();

                    $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
                    $prevUrl = blobDecode($rawPrevURL);
                    $mongoList = array();
                    $mongRegID = array();
                    if (isset($_SESSION[$cCode])) {

                        if (!isset($_SESSION[$cCode]['items'])) {
                            die("belum ada item yang dipilih");
                        }
                        else {
                            if (sizeof($_SESSION[$cCode]['items']) < 1) {
                                die("belum ada item yang dipilih");
                            }
                        }
                        echo("now processing your transaction..<br>");

                        //region pre-processors (item)
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['preProc']['detail']) ? $_SESSION[$cCode]['revert']['preProc']['detail'] : array();
                            cekMerah(":: iterator preprocc dari gerbang revert ::");
                            arrPrintWebs($iterator);
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                        }

                        if (sizeof($iterator) > 0) {


                            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                            echo "ITEM NUM LABELS";

                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];

                                    echo "sub-preproc: $comName, initializing values <br>";

                                    foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                        $tmpOutParams[$cCtr] = array();

                                        //                            $id = $dSpec['id'];
                                        $id = $xid;
                                        $subParams = array();

                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {

                                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                                $subParams['static'][$key] = $realValue;

                                            }

                                            if (!isset($subParams['static']["transaksi_id"])) {
                                                //									$subParams['static']["transaksi_id"] = $masterID;
                                            }


                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                        }
                                        //                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                        //                            arrPrint($subParams);
                                        //mati_disini();
                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;


                                            $comName = $tComSpec['comName'];
                                            $srcGateName = $tComSpec['srcGateName'];
                                            $srcRawGateName = $tComSpec['srcRawGateName'];
                                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                            //                                echo "sub preproc #$it: $comName, sending values <br>";

                                            $mdlName = "Pre" . ucfirst($comName);
                                            $this->load->model("Preprocs/" . $mdlName);
                                            $m = new $mdlName($resultParams);


                                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                                $tobeExecuted = true;
                                            }
                                            else {
                                                $tobeExecuted = false;
                                            }

                                            if ($tobeExecuted) {
                                                $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                                $gotParams = $m->exec();

                                                cekmerah("gotparams dari pre-proc $comName");
                                                arrprint($gotParams);


                                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                                    foreach ($gotParams as $gateName => $paramSpec) {
                                                        cekBiru(":: getParams inject ke $gateName ::");
                                                        if (!isset($_SESSION[$cCode][$gateName])) {
                                                            $_SESSION[$cCode][$gateName] = array();
                                                            //                                    cekhijau("building the session: $gateName");
                                                        }
                                                        else {
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
                                                                        cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                        $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                                    }

                                                                }
                                                            }
                                                            //==inject gotParams to child gate
                                                            cekHitam("srcGateName = $srcGateName :: " . __LINE__);
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
                                                                }
                                                            }
                                                        }
                                                        //                                    arrPrint($items);die();
                                                    }


                                                }

                                            }
                                            else {
                                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                            }


                                        }
                                    }
                                }
                            }
                            else {
                                //cekKuning("sub-preproc is not set");
                            }

                            $this->load->helper("he_value_builder");
                            fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis);

                            //region injector gerbang value untuk pembatalan ppv dan selisih
                            if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                                $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                                $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                                switch ($jenisTrReference) {
                                    case "460":
                                        $tempCalculate = array(
                                            //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                            //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                            //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                            //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                        );
                                        break;
                                    default:
                                        $tempCalculate = array(
                                            "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                            "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                            "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                        );
                                        break;
                                }
                                //                    $tempCalculate = array(
                                //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                //                    );

                                //arrPrintWebs($tempCalculate);
                                foreach ($replace['recalculate'] as $iKey => $gate) {
                                    $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                                }

                                cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                            }

                            //endregion

                        }
                        else {
                            echo("no processor defined. skipping preprocessor..<br>");
                        }
                        //endregion

                        //region pre-processors (subitem)
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['preProc']['sub_detail']) ? $_SESSION[$cCode]['revert']['preProc']['sub_detail'] : array();
                            cekMerah(":: iterator preprocc dari gerbang revert ::");
                            arrPrintWebs($iterator);
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail'] : array();
                        }

                        if (sizeof($iterator) > 0) {


                            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                            echo "ITEM NUM LABELS";

                            if (sizeof($iterator) > 0) {
                                $tmpOutParams = array();
                                foreach ($iterator as $cCtr => $tComSpec) {
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];

                                    echo "sub-preproc subdetail: $comName, initializing values <br>";

                                    if (isset($_SESSION[$cCode][$srcGateName]) && count($_SESSION[$cCode][$srcGateName]) > 0) {
                                        foreach ($_SESSION[$cCode][$srcGateName] as $xiID => $aDSpec) {
                                            $cCtr = 0;
                                            foreach ($aDSpec as $xid => $dSpec) {
                                                $cCtr++;
                                                $tmpOutParams[$cCtr] = array();
                                                $id = $xid;
                                                $subParams = array();

                                                if (isset($tComSpec['static'])) {
                                                    foreach ($tComSpec['static'] as $key => $value) {

                                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$xid][$id], $_SESSION[$cCode][$srcGateName][$xid][$id], 0);
                                                        $subParams['static'][$key] = $realValue;

                                                    }

                                                    if (!isset($subParams['static']["transaksi_id"])) {
                                                        //									$subParams['static']["transaksi_id"] = $masterID;
                                                    }


                                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                                }
                                                if (sizeof($subParams) > 0) {
                                                    $tmpOutParams[$cCtr][] = $subParams;


                                                    $comName = $tComSpec['comName'];
                                                    $srcGateName = $tComSpec['srcGateName'];
                                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                                    //                                echo "sub preproc #$it: $comName, sending values <br>";

                                                    $mdlName = "Pre" . ucfirst($comName);
                                                    $this->load->model("Preprocs/" . $mdlName);
                                                    $m = new $mdlName($resultParams);


                                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                                        $tobeExecuted = true;
                                                    }
                                                    else {
                                                        $tobeExecuted = false;
                                                    }

                                                    if ($tobeExecuted) {
                                                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                                        $gotParams = $m->exec();

                                                        cekmerah("gotparams dari pre-proc $comName");
                                                        arrprint($gotParams);


                                                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                                            foreach ($gotParams as $gateName => $paramSpec) {
                                                                cekBiru(":: getParams inject ke $gateName ::");
                                                                if (!isset($_SESSION[$cCode][$gateName])) {
                                                                    $_SESSION[$cCode][$gateName] = array();
                                                                    //                                    cekhijau("building the session: $gateName");
                                                                }
                                                                else {
                                                                    //                                    cekhijau("NOT building the session: $gateName");
                                                                }

                                                                foreach ($paramSpec as $id => $gSpec) {
                                                                    //										$id=$gSpec['id'];


                                                                    if (!isset($_SESSION[$cCode][$gateName][$xiID][$id])) {
                                                                        $_SESSION[$cCode][$gateName][$xiID][$id] = array();
                                                                    }


                                                                    if (isset($_SESSION[$cCode][$gateName][$xiID][$xiID][$id])) {
                                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                            foreach ($gSpec as $key => $val) {
                                                                                cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                                $_SESSION[$cCode][$gateName][$xiID][$id][$key] = $val;
                                                                            }

                                                                        }
                                                                    }
                                                                    //==inject gotParams to child gate
                                                                    cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                                    if (isset($_SESSION[$cCode][$srcGateName][$xiID][$id])) {
                                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                            foreach ($gSpec as $key => $val) {
                                                                                $_SESSION[$cCode][$srcGateName][$xiID][$id][$key] = $val;
                                                                            }

                                                                        }
                                                                    }

                                                                    //cekMerah("REBUILDING VALUES..");
                                                                    if (sizeof($itemNumLabels) > 0) {
                                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                                        foreach ($itemNumLabels as $key => $label) {
                                                                            //cekHere("$id === $key => $label");
                                                                            if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                                                $_SESSION[$cCode][$gateName][$xiID][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$xiID][$id]['jml'] * $_SESSION[$cCode][$gateName][$xiID][$id][$key]);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                //                                    arrPrint($items);die();
                                                            }


                                                        }

                                                    }
                                                    else {
                                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                                    }


                                                }
                                            }


                                            //                            $id = $dSpec['id'];

                                        }
                                    }

                                }
                            }
                            else {
                                //cekKuning("sub-preproc is not set");
                            }


                            $this->load->helper("he_value_builder");
                            fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis);


                            //region injector gerbang value untuk pembatalan ppv dan selisih
                            if (isset($_SESSION[$cCode]["revert"]["preProc"]["replacer"])) {
                                $replace = $_SESSION[$cCode]["revert"]["preProc"]["replacer"];
                                $jenisTrReference = $_SESSION[$cCode]["main"]["jenisTr_reference"];
                                switch ($jenisTrReference) {
                                    case "460":
                                        $tempCalculate = array(
                                            //                                "selisih" => ($_SESSION[$cCode]["main"]["hpp_riil"] + $_SESSION[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($_SESSION[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                            //                                "exchange__harga" => $_SESSION[$cCode]["main"]["hpp_riil"],//riil
                                            //                                "exchange__hpp_nppv" => $_SESSION[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                            //                                "exchange__ppv" => $_SESSION[$cCode]["main"]["ppv_riil"],//riil+ppv
                                        );
                                        break;
                                    default:
                                        $tempCalculate = array(
                                            "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                            "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                            "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                        );
                                        break;
                                }
                                //                    $tempCalculate = array(
                                //                        "selisih" => ($_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"]) - ($_SESSION[$cCode]["main"]["nett"] + $_SESSION[$cCode]["main"]["ppv"]),
                                //                        "hpp_nppv" => $_SESSION[$cCode]["main"]["hpp"],
                                //                        "hpp_nppn" => $_SESSION[$cCode]["main"]["hpp"] + $_SESSION[$cCode]["main"]["ppn"],
                                //                    );

                                //arrPrintWebs($tempCalculate);
                                foreach ($replace['recalculate'] as $iKey => $gate) {
                                    $_SESSION[$cCode]["main"][$gate] = $tempCalculate[$gate];
                                }

                                cekLime($_SESSION[$cCode]["main"]["hpp"] . "+" . $_SESSION[$cCode]["main"]["ppn"] . "-" . $_SESSION[$cCode]["main"]["nett"]);

                            }

                            //endregion


                        }
                        else {
                            echo("no processor defined. skipping preprocessor..<br>");
                        }
                        //endregion

                        //region pre-processors (master)
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['preProc']['master']) ? $_SESSION[$cCode]['revert']['preProc']['master'] : array();
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                        }

                        if (sizeof($iterator) > 0) {
                            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                            if (sizeof($iterator) > 0) {
                                foreach ($iterator as $cCtr => $tComSpec) {

                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                    $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                                    $subParams = array();

                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                            $subParams['static'][$key] = $realValue;

                                            //                                cekPink2("$comName == $value || $realValue");
                                            //                                cekPink2("valas_harga " . $_SESSION[$cCode]['main']['valas_harga']);
                                            //                                cekPink2("uang_muka_valas_harga " . $_SESSION[$cCode]['main']['uang_muka_valas_harga']);
                                        }

                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            //									$subParams['static']["transaksi_id"] = $masterID;
                                        }

                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                    }
                                    $tmpOutParams[$cCtr] = $subParams;

                                    $mdlName = "Pre" . ucfirst($comName);
                                    $this->load->model("Preprocs/" . $mdlName);
                                    $m = new $mdlName($resultParams);


                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                        $tobeExecuted = true;
                                    }
                                    else {
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

                                                if ($switchResultParams == true) {

                                                    foreach ($gSpec as $id => $ggSpec) {
                                                        if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                            $_SESSION[$cCode][$gateName][$id] = array();
                                                        }

                                                        if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                            if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                                foreach ($ggSpec as $key => $val) {
                                                                    $_SESSION[$cCode][$gateName][$id][$key] = $val;
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
                                                            }
                                                        }

                                                    }
                                                }
                                                else {
                                                    if (isset($_SESSION[$cCode]['main'])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $_SESSION[$cCode]['main'][$key] = $val;
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
                                                            cekHere("$id === $key => $label");
                                                            if (isset($_SESSION[$cCode]['main'][$key])) {
                                                                $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                        }
                                    }
                                    else {
                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                    }

                                    cekPink2("fillvalue setelah $comName");
                                    $this->load->helper("he_value_builder");
                                    fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis);


                                }
                            }
                            else {
                                //cekKuning("sub-preproc is not set");
                            }

                            $this->load->helper("he_value_builder");
                            fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis);
                        }
                        else {
                            echo("no processor defined. skipping preprocessor..<br>");
                        }
                        //endregion

                        $this->load->library("Validator");
                        $vd = new Validator();
                        $vd->setCCode($this->cCode);
                        $vd->setConfigUiJenis($configUiMasterModulJenis);
                        $step = $_SESSION[$cCode]['main']['step_number'];
                        $vd->midValidate($step);
                        $vd->unionValidate();

                        //region penomoran receipt
                        $this->load->model("CustomCounter");
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");
                        $cn->setModul($this->modul);
                        $cn->setStepCode($jenisTrTarget);
                        $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                        arrPrintWebs($counterForNumber);
                        if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                            die(__LINE__ . " Used number should be registered in 'counters' config as well");
                        }
                        echo "<div style='background:#ff7766;'>";
                        foreach ($counterForNumber as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        }
                        echo "</div style='background:#ff7766;'>";

                        $stepNumber = 1;
                        $tmpNomorNota = $paramSpec['paramString'];
                        $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);

                        if (isset($configUiMasterModulJenis['steps'][2])) {
                            $nextProp = array(
                                "num" => 2,
                                "code" => $configUiMasterModulJenis['steps'][2]['target'],
                                "label" => $configUiMasterModulJenis['steps'][2]['label'],
                                "groupID" => $configUiMasterModulJenis['steps'][2]['userGroup'],
                            );
                        }
                        else {
                            $nextProp = array(
                                "num" => 0,
                                "code" => "",
                                "label" => "",
                                "groupID" => "",
                            );
                        }
                        //endregion

                        //region dynamic counters
                        $cn = new CustomCounter("transaksi");
                        $cn->setType("transaksi");
                        $cn->setModul($this->modul);
                        $cn->setStepCode($jenisTrTarget);
                        $configCustomParams = $configCoreMasterModulJenis['counters'];
                        $configCustomParams[] = "stepCode";
                        if (sizeof($configCustomParams) > 0) {
                            $cContent = array();
                            foreach ($configCustomParams as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
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
                        //mati_disini();

                        //
                        //region addition on master


                        $addValues = array(
                            'counters' => $appliedCounters,
                            'counters_intext' => $appliedCounters_inText,
                            'nomer' => $tmpNomorNota,
                            'nomer2' => $tmpNomorNotaAlias,
                            'dtime' => date("Y-m-d H:i:s"),
                            'fulldate' => date("Y-m-d"),
                            "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                            "step_number" => 1,
                            "step_current" => 1,
                            "next_step_num" => $nextProp['num'],
                            "next_step_code" => $nextProp['code'],
                            "next_step_label" => $nextProp['label'],
                            "next_group_code" => $nextProp['groupID'],
                            "tail_number" => 1,
                            "tail_code" => $configUiMasterModulJenis['steps'][1]['target'],


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
                            "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                            "next_substep_num" => $nextProp['num'],
                            "next_substep_code" => $nextProp['code'],
                            "next_substep_label" => $nextProp['label'],
                            "next_subgroup_code" => $nextProp['groupID'],
                            "sub_tail_number" => 1,
                            "sub_tail_code" => $configUiMasterModulJenis['steps'][1]['target'],


                        );
                        foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                            foreach ($addSubValues as $key => $val) {
                                $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                            }
                        }
                        //endregion
                        //endregion

                        //region tambahan counter
                        $this->load->library("CounterNumber");
                        $ccn = new CounterNumber();
                        $ccn->setCCode($this->cCode);
                        $ccn->setJenisTr($this->jenisTr);
                        $ccn->setTransaksiGate($_SESSION[$cCode]['tableIn_master']);
                        $ccn->setMainGate($_SESSION[$cCode]['main']);
                        $ccn->setItemsGate($_SESSION[$cCode]['items']);
                        $ccn->setItems2SumGate($_SESSION[$cCode]['items2_sum']);
                        $new_counter = $ccn->getCounterNumber();
                        cekHitam("jenistr yang disett dari create " . $this->jenisTr);


                        if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                            foreach ($new_counter['main'] as $ckey => $cval) {
                                $_SESSION[$cCode]['tableIn_master'][$ckey] = $cval;
                                $_SESSION[$cCode]['main'][$ckey] = $cval;
                            }
                        }
                        if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                            foreach ($new_counter['items'] as $ikey => $iSpec) {
                                foreach ($iSpec as $iikey => $iival) {
                                    $_SESSION[$cCode]['items'][$ikey][$iikey] = $iival;
                                }
                            }
                        }
                        if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                            foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                                foreach ($iSpec as $iikey => $iival) {
                                    $_SESSION[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                                }
                            }
                        }
                        //endregion

                        //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                        if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                            $_SESSION[$cCode]['tableIn_master']['status_4'] = 11;
                            $_SESSION[$cCode]['tableIn_master']['trash_4'] = 0;
                            $tr = new MdlTransaksi();
                            $insertTransaksiID = $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                            cekHitam($this->db->last_query());
                            $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $_SESSION[$cCode]['tableIn_master']);
                            $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                            $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                            if ($insertID < 1) {
                                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                            }

                            //==transaksi_id dan nomor nota diinject kan ke gate utama
                            $injectors = array(
                                "transaksi_id" => $insertID,
                                "nomer" => $tmpNomorNota,
                                "nomer2" => $tmpNomorNotaAlias,
                            );
                            $arrInjectorsTarget = array(
                                "items",
                                "items2_sum",
                                "rsltItems",
                            );
                            foreach ($injectors as $key => $val) {
                                $_SESSION[$cCode]['main'][$key] = $val;
                                foreach ($arrInjectorsTarget as $target) {
                                    if (isset($_SESSION[$cCode][$target])) {
                                        foreach ($_SESSION[$cCode][$target] as $xid => $iSpec) {
                                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                            if (isset($_SESSION[$cCode][$target][$id])) {
                                                $_SESSION[$cCode][$target][$id][$key] = $val;
                                            }
                                        }
                                    }
                                }
                            }

                            //===signature
                            $dwsign = $tr->writeSignature($insertID, array(
                                "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                "step_number" => 1,
                                "step_code" => $this->jenisTr,
                                "step_name" => $configUiMasterModulJenis['steps'][1]['label'],
                                "group_code" => $configUiMasterModulJenis['steps'][1]['userGroup'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "keterangan" => $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $this->session->login['nama'],
                                "transaksi_id" => $insertID,
                            )) or die("Failed to write signature");

                            $idHis = array(
                                $stepNumber => array(
                                    "olehID" => $_SESSION[$cCode]['main']['olehID'],
                                    "olehName" => $_SESSION[$cCode]['main']['olehName'],
                                    "step" => $stepNumber,
                                    "trID" => $insertID,
                                    "nomer" => $tmpNomorNota,
                                    "nomer2" => $tmpNomorNotaAlias,
                                    "counters" => $appliedCounters,
                                    "counters_intext" => $appliedCounters_inText,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                ),
                            );
                            $idHis_blob = blobEncode($idHis);
                            $idHis_intext = print_r($idHis, true);
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
                                "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                                "nomers_prev" => "",
                                "nomers_prev_intext" => "",
                                "jenises_prev" => "",
                                "jenises_prev_intext" => "",
                                "ids_his" => $idHis_blob,
                                "ids_his_intext" => $idHis_intext,

                            )) or die("Failed to update tr next-state!");
                            cekHijau($this->db->last_query());

                            $addValues = array(
                                //===references
                                "id_master" => $insertID,
                                "id_top" => $insertID,
                                "ids_prev" => "",
                                "ids_prev_intext" => "",
                                "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                                "nomers_prev" => "",
                                "nomers_prev_intext" => "",
                                "jenises_prev" => "",
                                "jenises_prev_intext" => "",
                                "ids_his" => $idHis_blob,
                                "ids_his_intext" => $idHis_intext,
                            );
                            foreach ($addValues as $key => $val) {
                                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                            }

                        }
                        if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                            $inserMainValues = array();
                            if (isset($configCoreMasterModulJenis['tableIn']['mainValues'])) {
                                //matiHEre("hooppp");
                                $inserMainValues = array();
                                foreach ($configCoreMasterModulJenis['tableIn']['mainValues'] as $key => $src) {
                                    if (isset($_SESSION[$cCode]['tableIn_master_values'][$key])) {
                                        $dd = $tr->writeMainValues($insertID, array(
                                            "key" => $key,
                                            "value" => $_SESSION[$cCode]['tableIn_master_values'][$key],
                                        ));

                                        $inserMainValues[] = $dd;

                                    }
                                }
                            }

                            if (sizeof($inserMainValues) > 0) {
                                $arrBlob = blobEncode($inserMainValues);
                                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                            }

                        }
                        if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                            $inserMainValues = array();
                            foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                $inserMainValues[] = $dd;

                            }

                            if (sizeof($inserMainValues) > 0) {
                                $arrBlob = blobEncode($inserMainValues);
                                $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
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
                                ));
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

                                ));


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
                                    }
                                    else {
                                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                                    }

                                }

                            }
                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {

                            $insertIDs = array();
                            $insertDeIDs = array();
                            foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                if ($insertDetailID < 1) {
                                    die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                                }
                                else {
                                    $insertIDs[] = $insertDetailID;
                                    $insertDeIDs[$insertID][] = $insertDetailID;

                                }
                                if ($epID != 999) {
                                    $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                    if ($insertEpID < 1) {
                                        die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                    }
                                    else {
                                        $insertIDs[] = $insertEpID;
                                        $insertDeIDs[$epID][] = $insertEpID;

                                    }
                                }
                                cekUngu($this->db->last_query());
                            }


                            if (sizeof($insertIDs) == 0) {
                                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                            }
                            else {
                                $indexing_details = array();
                                foreach ($insertDeIDs as $key => $numb) {
                                    $indexing_details[$key] = $numb;
                                }

                                foreach ($indexing_details as $k => $arrID) {
                                    $arrBlob = blobEncode($arrID);
                                    $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                                    cekOrange($this->db->last_query());
                                }
                            }
                        }
                        else {
                            die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                            $insertIDs = array();
                            foreach ($_SESSION[$cCode]['tableIn_detail2'] as $dSpec) {
                                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);

                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);

                                }
                                cekUngu($this->db->last_query());
                            }
                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                            $insertIDs = array();
                            foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                $insertIDs[] = $insertDetailID;

                                if ($epID != 999) {
                                    $dd = $tr->writeDetailEntries($epID, $dSpec);
                                    $insertIDs[] = $dd;

                                }
                            }
                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) && sizeof($_SESSION[$cCode]['tableIn_detail_rsltItems']) > 0) {
                            $insertIDs = array();
                            foreach ($_SESSION[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                                $dd = $tr->writeDetailEntries($insertID, $dSpec);
                                $insertIDs[] = $dd;

                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);

                                }
                                cekUngu($this->db->last_query());
                            }
                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {

                            $insertIDs = array();
                            foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                                    foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                        if (isset($_SESSION[$cCode]['tableIn_detail'][$pID])) {
                                            $dd = $tr->writeDetailValues($insertID, array(
                                                "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                                "produk_id" => $pID,
                                                "key" => $key,
                                                "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                            ));
                                            $insertIDs[$pID][] = $dd;


                                        }
                                    }
                                }
                            }

                            if (sizeof($insertIDs) > 0) {
                                $arrBlob = blobEncode($insertIDs);
                                $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                            }

                        }
                        if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                            foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                                    $insertIDs = array();
                                    foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                        $dd = $tr->writeDetailValues($insertID, array(
                                            "produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                            "produk_id" => $pID,
                                            "key" => $key,
                                            "value" => $dSpec[$src],
                                        ));
                                        $insertIDs[] = $dd;

                                    }
                                }
                            }
                        }
                        //endregion

                        //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
                        $steps = $configUiMasterModulJenis['steps'];

                        //region processing sub-components, if in single step geser ke CLI

                        $componentGate['detail'] = array();
                        $componentConfig['detail'] = array();
                        //            //==filter nilai, jika NOL tidak dikirim, sesuai config==
                        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                        $filterNeeded = false;
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['detail']) ? $_SESSION[$cCode]['revert']['jurnal']['detail'] : array();
                            $revertedTarget = $_SESSION[$cCode]['main']['pihakExternID'];
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
                            $revertedTarget = "";
                        }
                        $componentConfig['detail'] = $iterator;
                        //            //region processing sub-components
                        //            if (sizeof($iterator) > 0) {
                        //                foreach ($iterator as $cCtr => $tComSpec) {
                        //                    $tmpOutParams[$cCtr] = array();
                        //                    $gg = 0;
                        //                    $srcGateName = $tComSpec['srcGateName'];
                        //                    foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                        //                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        //                        $comName = $tComSpec['comName'];
                        //                        if (substr($comName, 0, 1) == "{") {
                        //                            $comName = trim($comName, "{");
                        //                            $comName = trim($comName, "}");
                        //                            //                            $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                        //                            cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                        //                            $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                        //                        }
                        //                        cekHitam(":: $comName ::");
                        //                        $mdlName = "Com" . ucfirst($comName);
                        //                        if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                        ////cekLime($mdlName. "line");
                        //                            $filterNeeded = true;
                        //                        }
                        //                        else {
                        //                            cekLime($mdlName . "like");
                        //                            $filterNeeded = false;
                        //                        }
                        //                        echo "sub-component: $comName, initializing values <br>";
                        //                        //                        cekHitam(__LINE__);
                        //                        //                        $tmpOutParams[$cCtr] = array();
                        //
                        //                        //                        cekhitam("$comName filterneeded: $filterNeeded");
                        //                        //                        cekhitam("mau mengiterasi $srcGateName");
                        //                        //                        cekhitam("telah mengiterasi $srcGateName");
                        //                        //
                        //                        $subParams = array();
                        ////arrPrint($tComSpec);
                        //                        if (isset($tComSpec['loop'])) {
                        //                            foreach ($tComSpec['loop'] as $key => $value) {
                        //                                cekMerah(":: $key => $value ::");
                        //                                if (substr($key, 0, 1) == "{") {
                        //                                    $key = trim($key, "{");
                        //                                    $key = trim($key, "}");
                        //                                    //                                    $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                        //                                    $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
                        //                                }
                        //
                        //                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                        //                                $subParams['loop'][$key] = $realValue;
                        //
                        //                                if ($filterNeeded) {
                        //                                    if ($subParams['loop'][$key] == 0) {
                        //                                        unset($subParams['loop'][$key]);
                        //                                    }
                        //                                }
                        //                            }
                        //                        }
                        //                        if (isset($tComSpec['static'])) {
                        //                            foreach ($tComSpec['static'] as $key => $value) {
                        //
                        //                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                        //                                $subParams['static'][$key] = $realValue;
                        //
                        //                            }
                        //                            if (!isset($subParams['static']["transaksi_id"])) {
                        //                                $subParams['static']["transaksi_id"] = $insertID;
                        //                            }
                        //                            if (!isset($subParams['static']["transaksi_no"])) {
                        //                                $subParams['static']["transaksi_no"] = $insertNum;
                        //                            }
                        //
                        //                            $subParams['static']["fulldate"] = date("Y-m-d");
                        //                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        //                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        //                            if (strlen($revertedTarget) > 1) {
                        //                                $subParams['static']['reverted_target'] = $revertedTarget;
                        //                            }
                        //                        }
                        //                        //arrPrint($subParams);
                        //                        if (sizeof($subParams) > 0) {
                        ////                            arrprint($subParams);
                        //                            cekhitam("subparam ada isinya");
                        //                            if ($filterNeeded) {
                        //                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                        //                                    $tmpOutParams[$cCtr][] = $subParams;
                        //                                }
                        //                            }
                        //                            else {
                        //                                $tmpOutParams[$cCtr][] = $subParams;
                        ////                                CekHijiau("asem" .$gg++);
                        //                            }
                        //                        }
                        //                        else {
                        //                            cekhitam("subparam TIDAK ada isinya");
                        //                        }
                        //                    }
                        //
                        //                    $componentGate['detail'][$cCtr] = $subParams;
                        //                }
                        //                //cekHitam("cetak tmpOutParams");
                        //
                        //                foreach ($iterator as $cCtr => $tComSpec) {
                        //                    $srcGateName = $tComSpec['srcGateName'];
                        //                    foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
                        //
                        //                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        //                        $comName = $tComSpec['comName'];
                        //                        if (substr($comName, 0, 1) == "{") {
                        //                            $comName = trim($comName, "{");
                        //                            $comName = trim($comName, "}");
                        //                            $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
                        //                            //                        $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                        //                        }
                        //                    }
                        //                    echo "sub component: $comName, sending values <br>";
                        //
                        //                    $mdlName = "Com" . ucfirst($comName);
                        //                    $this->load->model("Coms/" . $mdlName);
                        //                    $m = new $mdlName();
                        //                    //===filter value nol, jika harus difilter
                        ////                    arrPrint($tmpOutParams[$cCtr]);
                        //                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        //                        $tobeExecuted = true;
                        //                    }
                        //                    else {
                        //                        $tobeExecuted = false;
                        //                    }
                        //
                        //                    if ($tobeExecuted) {
                        //                        cekMerah("$comName dieksekusiii");
                        //                        arrPrint($tmpOutParams[$cCtr]);
                        //                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        //                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        //                        cekBiru($this->db->last_query());
                        //                    }
                        //                    else {
                        //                        cekMerah("$comName tidak eksekusi");
                        //                    }
                        //
                        //                }
                        //            }
                        //            else {
                        //                //cekKuning("subcomponents is not set");
                        //            }
                        //            //endregion

                        //region processing main components, if in single step

                        $componentGate['master'] = array();
                        $componentConfig['master'] = array();
                        //==filter nilai, jika NOL tidak dikirim, sesuai config==
                        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['jurnal']['master']) ? $_SESSION[$cCode]['revert']['jurnal']['master'] : array();
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'] : array();
                        }

                        if (sizeof($iterator) > 0) {
                            $componentConfig['master'] = $iterator;
                            $cCtr = 0;
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $cCtr++;
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $_SESSION[$cCode]['main'][$comName], $comName);
                                }
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                echo "component # $cCtr: $comName<br>";

                                $dSpec = $_SESSION[$cCode][$srcGateName];
                                $tmpOutParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                        }

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                        $tmpOutParams['loop'][$key] = $realValue;
                                        //                            cekKuning("LOOP $key diisi dengan $realValue");
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                        $tmpOutParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    $tmpOutParams['static']["urut"] = $cCtr;
                                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                                }

                                if (isset($tComSpec['static2'])) {
                                    //cekHere("DISINI OIII");
                                    foreach ($tComSpec['static2'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                        $tmpOutParams['static2'][$key] = $realValue;

                                    }
                                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                                    }

                                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                                }


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

                                $componentGate['master'][$cCtr] = $tmpOutParams;
                            }
                        }
                        else {
                            //cekKuning("components is not set");
                        }


                        //endregion

                        cekHitam(":: START POST PROCC DETAIL... ::");

                        //region processing sub-post-processors, always
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['detail'] : array();
                            cekHitam("post procc pakai revert");
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
                            cekHitam("post procc pakai config core");
                        }
                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                echo "[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>";
                                $tmpOutParams[$cCtr] = array();
                                if (isset($_SESSION[$cCode][$srcGateName]) && (sizeof($_SESSION[$cCode][$srcGateName]) > 0)) {
                                    arrPrint($_SESSION[$cCode][$srcGateName]);
                                    foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                        //                            $id = $dSpec['id'];
                                        $id = $xid;
                                        $subParams = array();
                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {

                                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                                $subParams['loop'][$key] = $realValue;

                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {
                                                cekHitam("gate: $srcGateName, dengan key $id");
                                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                                $subParams['static'][$key] = $realValue;

                                            }
                                            if (!isset($subParams['static']["transaksi_id"])) {
                                                $subParams['static']["transaksi_id"] = $insertID;
                                            }
                                            if (!isset($subParams['static']["transaksi_no"])) {
                                                $subParams['static']["transaksi_no"] = $insertNum;
                                            }
                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            if (isset($_SESSION[$cCode]['revert']['postProc']['detail'])) {
                                                $subParams['static']["reverted_target"] = $_SESSION[$cCode]['main']['pihakExternID'];
                                            }

                                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                                        }

                                        if (sizeof($subParams) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                }
                            }

                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                if (isset($_SESSION[$cCode][$srcGateName])) {
                                    echo "[$cCtr] sub-postProcessor: $comName, sending values <br>";

                                    $mdlName = "Com" . ucfirst($comName);
                                    $this->load->model("Coms/" . $mdlName);
                                    //arrPrint($tmpOutParams[$cCtr]);
                                    $m = new $mdlName();
                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    cekPink($this->db->last_query());
                                }

                            }
                        }
                        //endregion

                        //region relesaese connected payment source dan marking trash transaksi
                        //            arrPrint($_SESSION[$cCode]["revert"]);
                        if (isset($_SESSION[$cCode]["revert"]["connectedPaymentsource"]) && $_SESSION[$cCode]["revert"]["connectedPaymentsource"] == true) {
                            $keyRel = $_SESSION[$cCode]["main"]["referenceID"];
                            $keyRelRef = $_SESSION[$cCode]["main"]["pihakExternID"];
                            $relPaymentSrc = isset($this->config->item("payment_source")[$keyRelRef]) ? $this->config->item("payment_source")[$keyRelRef] : array();
                            if (sizeof($relPaymentSrc) > 0) {
                                $this->load->model("Mdls/MdlPaymentSource");
                                $m = new MdlPaymentSource();
                                $m->addFilter("transaksi_id='$keyRel'");
                                $tmpRelPay = $m->lookupAll()->result();
                                $paymentRelUsed = array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "id",
                                    "extern_nama" => "name",
                                    "label" => ".hutang biaya",
                                    "target_jenis" => "jenisTr",
                                    "transaksi_id" => "refID",
                                    "terbayar" => "nilai_bayar",
                                    "sisa" => "new_sisa",
                                    "ppn" => "valid_ppn",
                                    "extern_nilai2" => "valid_dpp",
                                );
                                if (sizeof($tmpRelPay) > 0) {
                                    $tmpOutParams = array();
                                    $iterator = array();
                                    foreach ($tmpRelPay as $indexKey => $relData) {
                                        $tmp = array();
                                        foreach ($paymentRelUsed as $key => $keyGate) {
                                            if ($key == "terbayar") {
                                                $val = $relData->sisa;
                                            }
                                            else {
                                                if ($key == "sisa") {
                                                    $val = "-" . $relData->sisa;
                                                }
                                                else {
                                                    $val = $relData->$key;
                                                }
                                            }
                                            $tmp["static"][$key] = $val;
                                            $tmp["static"]["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];

                                        }
                                        $iterator[$indexKey]["loop"] = array();
                                        $iterator[$indexKey]["comName"] = "PaymentSrcItem";
                                        if (sizeof($tmp) > 0) {
                                            $tmpOutParams[$indexKey][] = $tmp;
                                        }
                                    }
                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        //                            $srcGateName = $tComSpec['srcGateName'];
                                        //                            $srcRawGateName = $tComSpec['srcRawGateName'];
                                        echo "sub-postProcessor: $comName, sending values <br>";

                                        $mdlName = "Com" . ucfirst($comName);
                                        $this->load->model("Coms/" . $mdlName);
                                        $m = new $mdlName();
                                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        //                            cekHitam($this->db->last_query());
                                    }
                                }
                                //                    foreach()
                                cekLime("yuk direset paymentsource**");
                            }


                        }
                        //endregion

                        //region processing main-post-processors, always
                        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                            $iterator = isset($_SESSION[$cCode]['revert']['postProc']['detail']) ? $_SESSION[$cCode]['revert']['postProc']['master'] : array();
                        }
                        else {
                            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
                        }

                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                echo "post-processor: $comName<br>LINE: " . __LINE__;

                                $dSpec = $_SESSION[$cCode][$srcGateName];
                                $tmpOutParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                        $tmpOutParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                        $tmpOutParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                                }
                                if (isset($tComSpec['static2'])) {
                                    //cekHere("DISINI OIII");
                                    foreach ($tComSpec['static2'] as $key => $value) {

                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                                        $tmpOutParams['static2'][$key] = $realValue;

                                    }
                                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                                    }

                                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                                }

                                //lgShowError("Ada kesalahan",);
                                $mdlName = "Com" . ucfirst($comName);
                                $this->load->model("Coms/" . $mdlName);
                                $m = new $mdlName();

                                cekBiru("kiriman komponem $comName");
                                arrPrint($tmpOutParams);
                                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                //cekHitam($this->db->last_query());

                            }
                        }
                        else {

                        }
                        //endregion

                        //region nulis paymentSource
                        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_source");
                        if (array_key_exists($stepCode, $paymentSources)) {

                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs[1] as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $paymentMethod = isset($paymentSrcConfig['method']) ? $paymentSrcConfig['method'] : "insert";

                                    if ($paymentMethod == "update") {
                                        $filters = array(
                                            "extern_id" => ""
                                        );
                                        //                            arrPrint($externSrc);
                                        $tr->setFilters(array());
                                        $tmpData = $tr->lookupPaymentSrcByJenis($paymentSrcConfig['jenisTarget'])->result();
                                        if (sizeof($tmpData) > 0) {
                                            //sudah ada update aja gak perlu insert
                                            $prevID = $tmpData[0]->id;
                                            $preValue = $tmpData[0]->sisa;
                                            $currValue = isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0;
                                            $newValue = $preValue + $currValue;
                                            $where = array(
                                                "id" => $prevID,
                                                //                                    "transaksi_id" => $pSpec->transaksi_id,
                                            );
                                            $data = array(
                                                "tagihan" => $newValue,
                                                "sisa" => $newValue,
                                            );
                                            $tr->updatePaymentSrc($where, $data);
                                            //                                cekHitam($this->db->last_query());
                                        }
                                        else {
                                            //di insert baru
                                            $tr->writePaymentSrc($insertID, array(
                                                "jenis" => $stepCode,
                                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                                "terbayar" => 0,
                                                "sisa" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                                "oleh_id" => $this->session->login['id'],
                                                "oleh_nama" => $this->session->login['nama'],
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => (isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']])) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => (isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']])) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => (isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']])) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : 0,
                                                "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                                "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                                "sisa_valas" => (isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']])) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                                "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",
                                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                            ));
                                        }


                                    }
                                    else {
                                        //region cek duplikasi paymentsource
                                        $tr->setFilters(array());
                                        $tr->addFilter("transaksi_id='$insertID'");
                                        $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                                        // $tr->addFilter("target_jenis='759'");
                                        $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                                        if (sizeof($validateIsInserted) > 0) {
                                            matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                                        }
                                        //endregion

                                        $arrDataPym = array(
                                            "jenis" => $stepCode,
                                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                            "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                            "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                            "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                            "label" => $paymentSrcConfig['label'],
                                            "tagihan" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                            "terbayar" => 0,
                                            "sisa" => isset($_SESSION[$cCode]['main'][$valueSrc]) ? $_SESSION[$cCode]['main'][$valueSrc] : 0,
                                            "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                            "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "dtime" => date("Y-m-d H:i:s"),
                                            "fulldate" => date("Y-m-d"),
                                            "valas_id" => (isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']])) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                            "valas_nama" => (isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']])) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                            "valas_nilai" => (isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']])) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : 0,
                                            "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                            "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']])) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                            "sisa_valas" => (isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']])) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                            "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",
                                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                            "payment_locked" => (isset($externSrc['payment_locked']) && ($_SESSION[$cCode]['main'][$externSrc['payment_locked']])) ? $_SESSION[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                            "cash_account" => (isset($externSrc['cash_account']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account']] : 0,
                                            "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                                            "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : 0,
                                            "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : 0,
                                        );
                                        arrPrintWebs($arrDataPym);
                                        $tr->writePaymentSrc($insertID, $arrDataPym);
                                    }

                                    cekMerah($this->db->last_query());
                                }
                            }


                        }
                        else {
                            //cekMerah("TIDAK nulis paymentSrc");
                        }
                        //endregion

                        //region nulis paymentAntiSource
                        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_antiSource") != null ? $this->config->item("payment_antiSource") : array();
                        if (array_key_exists($stepCode, $paymentSources)) {
                            cekHitam(":: starting PAYMENT ANTI SOURCE");
                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $tr->writePaymentAntiSrc($insertID, array(
                                        "jenis" => $stepCode,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $_SESSION[$cCode]['main']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                        "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "valas_id" => isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                        "valas_nama" => isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                        "valas_nilai" => isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',
                                        "tagihan_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                        "terbayar_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTerbayar']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTerbayar']] : '',
                                        "sisa_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',

                                    ));
                                    //cekMerah($this->db->last_query());
                                }
                            }


                        }
                        else {
                            //cekMerah("TIDAK nulis paymentSrc");
                        }
                        //endregion

                        //====registri value-gate
                        if (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['components'][$jenisTrTarget])) {
                            $jurnalIndex = $configCoreMasterModulJenis['components'][$jenisTrTarget];
                        }
                        else {
                            if (isset($_SESSION[$cCode]["revert"]["jurnal"]) && sizeof($_SESSION[$cCode]["revert"]["jurnal"]) > 0) {
                                $jurnalIndex = $_SESSION[$cCode]["revert"]["jurnal"];
                            }
                            else {
                                $jurnalIndex = array();
                            }
                        }
                        //---------------------------------------------------
                        if (isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget])) {
                            $jurnalPostProc = $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget];
                        }
                        else {
                            if (isset($_SESSION[$cCode]["revert"]["postProc"]) && sizeof($_SESSION[$cCode]["revert"]["postProc"]) > 0) {
                                $jurnalPostProc = $_SESSION[$cCode]["revert"]["postProc"];
                            }
                            else {
                                $jurnalPostProc = array();
                            }
                        }
                        //---------------------------------------------------
                        if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget])) {
                            $jurnalPreProc = $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget];
                        }
                        else {
                            if (isset($_SESSION[$cCode]["revert"]["preProc"]) && sizeof($_SESSION[$cCode]["revert"]["preProc"]) > 0) {
                                $jurnalPreProc = $_SESSION[$cCode]["revert"]["preProc"];
                            }
                            else {
                                $jurnalPreProc = array();
                            }
                        }
                        //---------------------------------------------------

                        $baseRegistries = array(
                            'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                            'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                            'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                            'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                            'itemSrc' => isset($_SESSION[$cCode]['itemSrc']) ? $_SESSION[$cCode]['itemSrc'] : array(),
                            'itemSrc_sum' => isset($_SESSION[$cCode]['itemSrc_sum']) ? $_SESSION[$cCode]['itemSrc_sum'] : array(),
                            'items3' => isset($_SESSION[$cCode]['items3']) ? $_SESSION[$cCode]['items3'] : array(),
                            'items3_sum' => isset($_SESSION[$cCode]['items3_sum']) ? $_SESSION[$cCode]['items3_sum'] : array(),
                            'items4' => isset($_SESSION[$cCode]['items4']) ? $_SESSION[$cCode]['items4'] : array(),
                            'items4_sum' => isset($_SESSION[$cCode]['items4_sum']) ? $_SESSION[$cCode]['items4_sum'] : array(),
                            'items5_sum' => isset($_SESSION[$cCode]['items5_sum']) ? $_SESSION[$cCode]['items5_sum'] : array(),
                            'items6' => isset($_SESSION[$cCode]['items6']) ? $_SESSION[$cCode]['items6'] : array(),
                            'items6_sum' => isset($_SESSION[$cCode]['items6_sum']) ? $_SESSION[$cCode]['items6_sum'] : array(),
                            'items7' => isset($_SESSION[$cCode]['items7']) ? $_SESSION[$cCode]['items7'] : array(),
                            'items7_sum' => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
                            'items8_sum' => isset($_SESSION[$cCode]['items8_sum']) ? $_SESSION[$cCode]['items8_sum'] : array(),
                            'items9_sum' => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
                            'items10_sum' => isset($_SESSION[$cCode]['items10_sum']) ? $_SESSION[$cCode]['items10_sum'] : array(),
                            'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                            'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                            'rsltItems3' => isset($_SESSION[$cCode]['rsltItems3']) ? $_SESSION[$cCode]['rsltItems3'] : array(),
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
                            "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                            "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                            "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                            "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                            "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                            "jurnal_index" => $jurnalIndex,
                            "postProcessor" => $jurnalPostProc,
                            "preProcessor" => $jurnalPreProc,
                            "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),
                            "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                            "items_noapprove" => isset($_SESSION[$cCode]['items_noapprove']) ? $_SESSION[$cCode]['items_noapprove'] : array(),
                            "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),
                            "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
                        );
                        $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));

                        //==================================================================================================
                        //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                        // bila step lebih dari 1
                        if ($nextProp['num'] > 1) {
                            $this->load->model("Mdls/MdlLockerTransaksi");
                            $lt = New MdlLockerTransaksi();
                            $lt->execLocker($_SESSION[$cCode]['main'], $nextProp['num'], NULL, $insertID);
                            showLast_query("hitam");
                        }
                        //==========================================================================================================
                        $masterID = $insertID;

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

                        cekKuning(":: mulai cek rek besar dan rek pembantu");
                        $cabangID_validate = $this->session->login['cabang_id'];
                        validateAllBalances();


                        $result["transaksi_id"] = $insertID;
                        $result["transaksi_nomer"] = $insertNum;


                        cekHijau("<h3>MULAI CONNECTING ANTAR CABANG</h3>");

                        //region connecting antar cabang
                        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
                        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
                        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
                        $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");
                        $origJenis = $this->jenisTr;
                        $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
                        $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
                        $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");

                        $steps = isset($configUiMasterModulOrigJenis['steps']) ? $configUiMasterModulOrigJenis['steps'] : array();
                        $connector = isset($configUiMasterModulOrigJenis['connectTo']) ? $configUiMasterModulOrigJenis['connectTo'] : "";
                        $preReplacer = isset($configUiMasterModulJenis['replacerConnectTo']) ? $configUiMasterModulJenis['replacerConnectTo'] : array();
                        $validateValueConnector = isset($configUiMasterModulJenis['connectoValidate'][$stepNum]) ? $configUiMasterModulJenis['connectoValidate'][$stepNum] : array();
                        $mongoListConnect = array();
                        $insertConnectingID = 0;
                        if (strlen($connector) > 0) {
                            cekMerah("TO BE CONNECT TO $connector |$stepNum|" . sizeof($steps));
                            if (isset($configUiMasterModulJenis['connectoValidate'][$stepNum])) {
                                $validateValueConnector = $configUiMasterModulJenis['connectoValidate'][$stepNum];
                                $preVal = $_SESSION[$cCode]['main'][$validateValueConnector];
                                $stepNum = $preVal > 0 ? $stepNum : "1000";//1000 untuk nglewatin step biar gak jalan connectingnya karena nilai yang dicari 0 kasusnya cash in advance ppn sudah masuk pusat tidak perlu diterbitkan auto dorong ppn ke pusat
                            }
                            if ($stepNum == sizeof($steps)) {
                                cekMerah("NOW CONNECTING to $connector");

                                $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiUi");
                                $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiCore");
                                $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiLayout");
                                $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiValues");
                                $modul_transaksi = $this->config->item("heTransaksi_ui")[$connector]["modul"];

                                if (sizeof($configUiMasterModulJenis) == 0) {
                                    die("kode connector tidak dikenali!");
                                }
                                if (sizeof($configUiMasterModulJenis['steps']) < 2) {
                                    die("konfigurasi connector harus memiliki step lebih dari satu!");
                                }


                                $oldCode = $cCode;
                                $cCode = "_TR_" . $connector;

                                $_SESSION[$cCode] = array();
                                $_SESSION[$cCode] = array(
                                    "main" => $_SESSION[$oldCode]['main'],
                                    "items" => $_SESSION[$oldCode]['items'],
                                    "items2" => $_SESSION[$oldCode]['items2'],
                                    "items2_sum" => $_SESSION[$oldCode]['items2_sum'],
                                    "items3" => $_SESSION[$oldCode]['items3'],
                                    "items3_sum" => $_SESSION[$oldCode]['items3_sum'],
                                    "items4" => $_SESSION[$oldCode]['items4'],
                                    "items4_sum" => $_SESSION[$oldCode]['items4_sum'],
                                    "items6" => $_SESSION[$oldCode]['items6'],
                                    "items7" => $_SESSION[$oldCode]['items7'],
                                    "items8_sum" => $_SESSION[$oldCode]['items8_sum'],
                                    "items9_sum" => $_SESSION[$oldCode]['items9_sum'],
                                    "items10_sum" => $_SESSION[$oldCode]['items10_sum'],
                                    "items_noapprove" => $_SESSION[$oldCode]['items_noapprove'],

                                    "tableIn_master" => $_SESSION[$oldCode]['tableIn_master'],
                                    "tableIn_detail" => $_SESSION[$oldCode]['tableIn_detail'],

                                    "rsltItems" => $_SESSION[$oldCode]['rsltItems'],
                                    "tableIn_detail_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_rsltItems'],

                                    "tableIn_master_values" => $_SESSION[$oldCode]['tableIn_master_values'],
                                    "tableIn_detail_values" => $_SESSION[$oldCode]['tableIn_detail_values'],
                                    "tableIn_detail_values_rsltItems" => $_SESSION[$oldCode]['tableIn_detail_values_rsltItems'],
                                    "main_elements" => $_SESSION[$oldCode]['main_elements'],
                                );

                                //==replace pertama
                                $masterReplacersO = array(
                                    "jenisTr" => $connector,
                                    "jenisTrMaster" => $connector,
                                    "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $_SESSION[$cCode]['main']['place2ID'],
                                    "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $_SESSION[$cCode]['main']['place2Name'],
                                    "place2ID" => $_SESSION[$cCode]['main']['placeID'],
                                    "place2Name" => $_SESSION[$cCode]['main']['placeName'],
                                    "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $_SESSION[$cCode]['main']['place2ID'],
                                    "cabangName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $_SESSION[$cCode]['main']['place2Name'],
                                    "cabang2ID" => $_SESSION[$cCode]['main']['placeID'],
                                    "cabang2Name" => $_SESSION[$cCode]['main']['placeName'],
                                    //
                                    "gudang2ID" => $_SESSION[$cCode]['main']['gudangID'],
                                    "gudang2Name" => $_SESSION[$cCode]['main']['gudangName'],
                                    "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $_SESSION[$cCode]['main']['gudang2ID'],
                                    "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $_SESSION[$cCode]['main']['gudang2Name'],
                                    "pihakID" => isset($_SESSION[$cCode]['main']['placeID']) ? $_SESSION[$cCode]['main']['placeID'] : "",
                                    "pihakName" => isset($_SESSION[$cCode]['main']['placeName']) ? $_SESSION[$cCode]['main']['placeName'] : "",
                                    "pihakName2" => $_SESSION[$cCode]['main']['placeName'],
                                    "gudang" => $_SESSION[$cCode]['main']['gudangID'],
                                    "gudang__name" => $_SESSION[$cCode]['main']['gudangName'],
                                    "gudang__label" => $_SESSION[$cCode]['main']['gudangName'],
                                    "efaktur_source" => isset($preReplacer['efaktur_source']) ? $_SESSION[$cCode]['main']['nomer'] : "",

                                );
                                foreach ($masterReplacersO as $key => $val) {
                                    $_SESSION[$cCode]['main'][$key] = $val;
                                    //                    $_SESSION[$cCode]['main'][$key] = $val;
                                }
                                $masterReplacers = array(
                                    //                    "referensi_id" => $masterID, (dimatikan)
                                    "inv" => $tmpNomorNota,
                                    "jenis_master" => $connector,
                                    "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                                    "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                                    "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $_SESSION[$cCode]['tableIn_master']['cabang2_id'],
                                    "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $_SESSION[$cCode]['tableIn_master']['cabang2_nama'],
                                    "cabang2_id" => $_SESSION[$cCode]['tableIn_master']['cabang_id'],
                                    "cabang2_nama" => $_SESSION[$cCode]['tableIn_master']['cabang_nama'],
                                    "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $_SESSION[$cCode]['tableIn_master']['gudang2_id'],
                                    "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $_SESSION[$cCode]['tableIn_master']['gudang2_nama'],
                                    "gudang2_id" => $_SESSION[$cCode]['tableIn_master']['gudang_id'],
                                    "gudang2_nama" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],
                                    "gudang" => $_SESSION[$cCode]['tableIn_master']['gudang_id'],
                                    "gudang__name" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],
                                    "gudang__label" => $_SESSION[$cCode]['tableIn_master']['gudang_nama'],

                                    "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                    "step_current" => 1,
                                    "step_number" => 1,
                                    "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                                    "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                                    "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                                    "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                                    "efaktur_source" => isset($preReplacer['efaktur_source']) ? $_SESSION[$cCode]['main']['nomer'] : "",
                                    //===references
                                    //                    "id_master"            => $masterID,
                                    //                    "id_top"               => $topID,
                                    //                    "ids_prev"             => base64_encode(serialize(array($prevProp['id']))),
                                    //                    "ids_prev_intext"      => print_r(array($prevProp['id'], true)),
                                    //                    "nomer_top"            => $_SESSION[$cCode]['main']['nomer'],
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
                                $cn->setModul($modul_transaksi);
                                $cn->setStepCode($configUiMasterModulJenis['steps'][1]['target']);
                                $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                                if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                                    die(__LINE__ . " Used number should be registered in 'counters' config as well");
                                }

                                foreach ($counterForNumber as $i => $cRawParams) {
                                    $cParams = explode("|", $cRawParams);
                                    $cValues = array();
                                    foreach ($cParams as $param) {
                                        //                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                                        //                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                                        $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                                        //                    echo "filling $param with " . $_SESSION[$cCode]['main'][$param] . "<br>";
                                    }
                                    $cRawValues = implode("|", $cValues[$i]);
                                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                                }

                                $tmpNomorNotaConnecting = $tmpNomorNota2 = $paramSpec['paramString'];
                                $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);


                                //</editor-fold>
                                //endregion

                                //region dynamic counters #2
                                // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                                $cn = new CustomCounter("transaksi");
                                $cn->setType("transaksi");
                                $cn->setModul($modul_transaksi);
                                $cn->setStepCode($configUiMasterModulJenis['steps'][1]['target']);
                                $configCustomParams = $configCoreMasterModulJenis['counters'];
                                $configCustomParams[] = "stepCode";
                                if (sizeof($configCustomParams) > 0) {
                                    $cContent = array();
                                    foreach ($configCustomParams as $i => $cRawParams) {
                                        $cParams = explode("|", $cRawParams);
                                        $cValues = array();
                                        foreach ($cParams as $param) {
                                            $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
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

                                //region tambahan counter
                                $this->load->library("CounterNumber");
                                $ccn = new CounterNumber();
                                $ccn->setCCode($cCode);
                                $ccn->setJenisTr($connector);
                                $ccn->setTransaksiGate($_SESSION[$cCode]['tableIn_master']);
                                $ccn->setMainGate($_SESSION[$cCode]['main']);
                                $ccn->setItemsGate($_SESSION[$cCode]['items']);
                                $ccn->setItems2SumGate($_SESSION[$cCode]['items2_sum']);
                                $new_counter = $ccn->getCounterNumber();
                                cekHitam("jenistr yang disett dari create " . $this->jenisTr);


                                if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                                    foreach ($new_counter['main'] as $ckey => $cval) {
                                        $_SESSION[$cCode]['tableIn_master'][$ckey] = $cval;
                                        $_SESSION[$cCode]['main'][$ckey] = $cval;
                                    }
                                }
                                if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                                    foreach ($new_counter['items'] as $ikey => $iSpec) {
                                        foreach ($iSpec as $iikey => $iival) {
                                            $_SESSION[$cCode]['items'][$ikey][$iikey] = $iival;
                                        }
                                    }
                                }
                                if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                                    foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                                        foreach ($iSpec as $iikey => $iival) {
                                            $_SESSION[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                                        }
                                    }
                                }
                                //endregion


                                /** ----------------------------------------------------------------------------------
                                 * modul counter global
                                 * -----------------------------------------------------------------------------------*/
                                if (isset($configUiMasterModulJenis["counter_global"])) {
                                    $sessionMainCounter = $_SESSION[$cCode]['main'] + $new_counter['main'];
                                    $counter_global_part = $configUiMasterModulJenis["counter_global_part"];
                                    $counter_global_part[] = $configUiMasterModulJenis["counter_global"];
                                    $nomer_new = build_global_counter($sessionMainCounter, $counter_global_part);

                                    cekMerah($nomer_new . " @" . __LINE__);
                                    //matiHere(__LINE__);

                                    $_SESSION[$cCode]['tableIn_master']['nomer_new'] = $nomer_new;
                                }
                                // ---------------------------------------------------------------------------------------

                                $addValues = array(
                                    'counters' => $appliedCounters2,
                                    'counters_intext' => $appliedCounters_inText2,
                                    'nomer' => $tmpNomorNota2,
                                    'nomer2' => $tmpNomorNota2Alias,
                                    'dtime' => date("Y-m-d H:i:s"),
                                    'fulldate' => date("Y-m-d"),
                                );
                                foreach ($addValues as $key => $val) {
                                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                                }


                                $masterReplacers = array(
                                    "nomer" => $tmpNomorNota2,
                                    "nomer2" => $tmpNomorNota2Alias,
                                    "counters" => $appliedCounters2,
                                    "counters_intext" => $appliedCounters_inText2,
                                );
                                foreach ($masterReplacers as $key => $val) {
                                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                                }

                                $detailReplacers = array(
                                    "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                    "sub_step_current" => 1,
                                    "sub_step_number" => 1,
                                    "next_substep_num" => $_SESSION[$cCode]['tableIn_master']['next_step_num'],
                                    "next_substep_code" => $_SESSION[$cCode]['tableIn_master']['next_step_code'],
                                    "next_substep_label" => $_SESSION[$cCode]['tableIn_master']['next_step_label'],
                                    "next_subgroup_code" => $_SESSION[$cCode]['tableIn_master']['next_group_code'],
                                    //                    "next_substep_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                                    //                    "next_substep_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                                    //                    "next_subgroup_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                                );
                                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                                    //                    cekmerah("tulis rincian transaksi kedua");
                                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $k => $dSpec) {
                                        foreach ($dSpec as $key => $val) {
                                            $_SESSION[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                                        }
                                    }
                                }
                                else {
                                    //                    cekmerah("GAGAL tulis rincian transaksi kedua");
                                }


                                //region ----------write transaksi & transaksi_data #2
                                if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                                    $tr = new MdlTransaksi();
                                    $tr->addFilter("transaksi.cabang_id='" . $this->session->login['cabang_id'] . "'");
                                    $insertConnectingID = $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                                    cekUngu($this->db->last_query());
                                    $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $_SESSION[$cCode]['tableIn_master']);
                                    $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                                    $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                                    $mongoListConnect['main'] = array($insertID, $epID);
                                    //                        cekmerah("tulis transaksi kedua :: trID $insertID");
                                    //                        cekmerah($this->db->last_query());
                                    if ($insertID < 1) {
                                        die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                                    }
                                }
                                else {
                                    cekmerah("GAGAL tulis transaksi kedua");
                                }
                                if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                                    $inserMainValues = array();
                                    foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $inserMainValues[] = $dd;
                                        $mongoListConnect['mainValues'][] = $dd;
                                    }
                                    if (sizeof($inserMainValues) > 0) {
                                        $arrBlob = blobEncode($inserMainValues);
                                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                    }
                                }
                                if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                                    foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $mongoListConnect['mainValues'][] = $dd;
                                    }
                                }
                                if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                                    foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                        $inserMainValues[] = $dd;
                                        $mongoListConnect['mainValues'][] = $dd;
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

                                        ));
                                    }
                                }
                                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                                    $insertIDs = array();
                                    $insertDeIDs = array();
                                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                        if ($insertDetailID < 1) {
                                            die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                                        }
                                        else {
                                            $insertIDs[] = $insertDetailID;
                                            $insertDeIDs[$insertID][] = $insertDetailID;
                                            $mongoListConnect['detail'][] = $insertDetailID;
                                        }
                                        if ($epID != 999) {
                                            $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                            if ($insertEpID < 1) {
                                                die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                            }
                                            else {
                                                $insertIDs[] = $insertEpID;
                                                $insertDeIDs[$epID][] = $insertEpID;
                                                $mongoListConnect['detail'][] = $insertEpID;
                                            }
                                        }
                                    }
                                    if (sizeof($insertIDs) == 0) {
                                        die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                                    }
                                    else {
                                        $indexing_details = array();
                                        foreach ($insertDeIDs as $key => $numb) {
                                            $indexing_details[$key] = $numb;
                                        }

                                        foreach ($indexing_details as $k => $arrID) {
                                            $arrBlob = blobEncode($arrID);
                                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                                            //                                cekOrange($this->db->last_query());
                                        }
                                    }
                                }
                                if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                                    $insertIDs = array();
                                    foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                        $mongoListConnect['detail'] = $insertIDs;
                                        if ($epID != 999) {
                                            $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                            $mongoListConnect['detail'] = $mongoListConnect['detail'] = $insertIDs;;
                                        }
                                    }
                                }
                                if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                                    $insertIDs = array();
                                    foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                        if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                            foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                                //                                $insertIDs[$pID][] = $tr->writeDetailValues($insertID, array(
                                                //                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                                //                                    "produk_id" => $pID,
                                                //                                    "key" => $key,
                                                //                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                                //                                ));
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                                    "produk_id" => $pID,
                                                    "key" => $key,
                                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                                ));
                                                $insertIDs[] = $dd;
                                                $mongoListConnect['detailValues'][] = $dd;

                                            }
                                        }
                                    }
                                    if (sizeof($insertIDs) > 0) {
                                        $arrBlob = blobEncode($insertIDs);
                                        $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                                    }
                                }
                                if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                                    foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                        if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                            foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                                $insertIDs[] = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                                    "produk_id" => $pID,
                                                    "key" => $key,
                                                    "value" => $dSpec[$src],
                                                ));

                                            }
                                        }
                                    }
                                }


                                //region nulis paymentSource
                                //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
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
                                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $tmpNomorNota2,
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                                "oleh_id" => $pelaku_transaksi_id,
                                                "oleh_nama" => $pelaku_transaksi_nama,
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',
                                                "tagihan_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                                "terbayar_valas" => 0,
                                                "sisa_valas" => isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',
                                            ));
                                        }
                                    }


                                    //cekMerah($this->db->last_query());

                                }
                                else {
                                    //cekMerah("TIDAK nulis paymentSrc");
                                }
                                //endregion


                                //region nulis paymentAntiSource
                                //                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                                $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                                $paymentSources = $this->config->item("payment_antiSource");
                                if (array_key_exists($stepCode, $paymentSources)) {
                                    $payConfigs = $paymentSources[$stepCode];
                                    if (sizeof($payConfigs) > 0) {
                                        foreach ($payConfigs as $paymentSrcConfig) {
                                            //					$paymentSrcConfig = $paymentSources[$stepCode];
                                            $valueSrc = $paymentSrcConfig['valueSrc'];
                                            $externSrc = $paymentSrcConfig['externSrc'];
                                            $tr->writePaymentAntiSrc($insertID, array(
                                                "jenis" => $stepCode,
                                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $tmpNomorNota2,
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                                "oleh_id" => $pelaku_transaksi_id,
                                                "oleh_nama" => $pelaku_transaksi_nama,
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                            ));
                                        }
                                    }


                                    //cekMerah($this->db->last_query());

                                }
                                else {
                                    //cekMerah("TIDAK nulis paymentSrc");
                                }
                                //endregion


                                $idHis_decode[$stepNum] = array(
                                    "olehID" => $_SESSION[$cCode]['main']['olehID'],
                                    "olehName" => $_SESSION[$cCode]['main']['olehName'],
                                    "step" => $stepNum,
                                    "trID" => $insertID,
                                    "nomer" => $tmpNomorNota2,
                                    "nomer2" => $tmpNomorNota2Alias,
                                    "counters" => $appliedCounters2,
                                    "counters_intext" => $appliedCounters_inText2,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                );
                                $idHis_blob = blobEncode($idHis_decode);
                                $idHis_intext = print_r($idHis_decode, true);

                                $_SESSION[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                                $_SESSION[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;

                                $tr = new MdlTransaksi();
                                $dupState = $tr->updateData(array("id" => $insertID), array(
                                    "id_master" => $masterID,
                                    "id_top" => $insertID,

                                    "ids_his" => $idHis_blob,
                                    "ids_his_intext" => $idHis_intext,

                                )) or die("Failed to update tr next-state!");

                                $baseRegistries = array(
                                    'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                                    'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
                                    'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
                                    'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
                                    'itemSrc' => isset($_SESSION[$cCode]['itemSrc']) ? $_SESSION[$cCode]['itemSrc'] : array(),
                                    'itemSrc_sum' => isset($_SESSION[$cCode]['itemSrc_sum']) ? $_SESSION[$cCode]['itemSrc_sum'] : array(),
                                    'items3' => isset($_SESSION[$cCode]['items3']) ? $_SESSION[$cCode]['items3'] : array(),
                                    'items3_sum' => isset($_SESSION[$cCode]['items3_sum']) ? $_SESSION[$cCode]['items3_sum'] : array(),
                                    'items4' => isset($_SESSION[$cCode]['items4']) ? $_SESSION[$cCode]['items4'] : array(),
                                    'items4_sum' => isset($_SESSION[$cCode]['items4_sum']) ? $_SESSION[$cCode]['items4_sum'] : array(),
                                    'items5_sum' => isset($_SESSION[$cCode]['items5_sum']) ? $_SESSION[$cCode]['items5_sum'] : array(),
                                    'items6_sum' => isset($_SESSION[$cCode]['items6_sum']) ? $_SESSION[$cCode]['items6_sum'] : array(),
                                    'items6' => isset($_SESSION[$cCode]['items6']) ? $_SESSION[$cCode]['items6'] : array(),
                                    'items7' => isset($_SESSION[$cCode]['items7']) ? $_SESSION[$cCode]['items7'] : array(),
                                    'items7_sum' => isset($_SESSION[$cCode]['items7_sum']) ? $_SESSION[$cCode]['items7_sum'] : array(),
                                    'items8_sum' => isset($_SESSION[$cCode]['items8_sum']) ? $_SESSION[$cCode]['items8_sum'] : array(),
                                    'items9_sum' => isset($_SESSION[$cCode]['items9_sum']) ? $_SESSION[$cCode]['items9_sum'] : array(),
                                    'items10_sum' => isset($_SESSION[$cCode]['items10_sum']) ? $_SESSION[$cCode]['items10_sum'] : array(),
                                    'items_noapprove' => isset($_SESSION[$cCode]['items_noapprove']) ? $_SESSION[$cCode]['items_noapprove'] : array(),

                                    'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
                                    'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                                    'rsltItems3' => isset($_SESSION[$cCode]['rsltItems3']) ? $_SESSION[$cCode]['rsltItems3'] : array(),

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
                                    "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                                    "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                                    "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                                    "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                                    "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                                    "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                                    "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
                                    "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),
                                    "jurnal_index" => isset($_SESSION[$cCode]['jurnal_index']) ? $_SESSION[$cCode]['jurnal_index'] : array(),
                                    "postProcessor" => isset($_SESSION[$cCode]['postProcessor']) ? $_SESSION[$cCode]['postProcessor'] : array(),
                                    "preProcessor" => isset($_SESSION[$cCode]['preProcessor']) ? $_SESSION[$cCode]['preProcessor'] : array(),
                                    "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),

                                );
                                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                                //endregion

                                $result["transaksi_id_connecting"] = $insertID;
                                $result["transaksi_nomer_connecting"] = $insertNum;

                                arrPrintCyan($_SESSION[$cCode]['tableIn_master']);

                            }
                            else {
                                cekMerah("to be delayed to connect to $connector");
                            }
                        }
                        else {
                            cekKuning("not connecting to any tCode");
                        }
                        //endregion

                        cekHijau("<h3>SELESAI CONNECTING NATAR CABANG</h3>");

                    }


                }
            }

        }
        else {
            cekMerah($sessionData["main"]["requestReferenceSoID"]);
            $msg = "Transaksi Auto Distribusi gagal dilanjutkan karena tidak ada referensi Sales Order. Silahkan cek lagi transaksi anda.";
            mati_disini($msg . " code: " . __LINE__);
        }


        // endregion cek referensi so dan buat session create transaksi dulu

//        arrPrintCyan($_SESSION[$cCode]);
//        mati_disini(__LINE__ . " || tes request pengembalian uang ke konsumen");


    }


}
