<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/31/2019
 * Time: 10:00 PM
 */

class Ledger2 extends CI_Controller
{

    private $dates = array();

    public function __construct()
    {

        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
            // redirect(base_url() . "Login");
        }
        $this->load->model("MdlTransaksi");
        $trd = new MdlTransaksi();
        //        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        validateUserSession($this->session->login['id']);//
        $this->placeID = $this->session->login['cabang_id'];
        $this->gudangID = $this->session->login['gudang_id'];
        $this->gudangName = $this->session->login['gudang_nama'];
        // arrPrint($this->session);
        $this->summaryKey = array(
            "in",
            "out",
            "in_qty",
            "out_qty",
            "balance",
        );
        $this->configUiModul = loadConfigUiModul();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->rekAlias = fetchAccountStructureAlias();

    }

    public function index()
    {

        die("_");
    }

    public function viewBalances_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $defPosition = detectRekDefaultPosition($rekName);


        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountRekDetailAdditional = isset($this->config->item('accountRekDetailAdditional')[$rekName]) ? $this->config->item('accountRekDetailAdditional')[$rekName] : array();
        $accountBalanceAdditionalColumns = isset($this->config->item('accountBalanceAdditionalColumns')[$rekName]) ? $this->config->item('accountBalanceAdditionalColumns')[$rekName] : array();
        $accountSubChilds = ($this->config->item('accountSubChilds') != NULL) ? $this->config->item('accountSubChilds') : array();
        $accountBalanceLocker = isset($this->config->item('accountBalanceColumLocker')[$relName]) ? $this->config->item('accountBalanceColumLocker')[$relName] : array();
        $accountSuperSubChilds = ($this->config->item('accountSuperSubChilds') != NULL) ? $this->config->item('accountSuperSubChilds') : array();
        $accountBalanceAdvanceColumns = isset($this->config->item('accountBalanceAdvanceColumns')[$rekName]) ? $this->config->item('accountBalanceAdvanceColumns')[$rekName] : array();
        $accountSuperSubChildsNonRekening = ($this->config->item('accountSuperSubChildsNonRekening') != NULL) ? $this->config->item('accountSuperSubChildsNonRekening') : array();
        $customLink = isset($balConfig["customLink"]) ? $balConfig["customLink"] : array();//rencana untuk link custom link pergudang
        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";
        $getExternID = isset($_GET['ext_id']) && strlen($_GET['ext_id']) ? $_GET['ext_id'] : NULL;
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";

        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();

        //region model reguler / yang utama
        if (isset($balConfig['mdlData'])) {
            $mdlData = $balConfig['mdlData'];
            $this->load->model("Mdls/$mdlData");
            $sp = new $mdlData();

            //region data dari MdlSupplies
            $tmpSp = $sp->lookupAll()->result();
            //            showLast_query("biru");
            $keySp = $balConfig['mdlDataKeys'];
            foreach ($tmpSp as $itemSp) {
                $dataSps = array();
                foreach ($keySp as $kolomSp) {
                    $dataSps[$kolomSp] = $itemSp->$kolomSp;
                }

                $itemSps[$itemSp->id] = $dataSps;
            }
            //endregion


        }
        $com->addFilter("cabang_id='$cabangID'");

        if ($getExtern2ID != NULL) {
            $com->addFilter("extern2_id='$getExtern2ID'");
        }
        if ($getExternID != NULL) {
            $com->addFilter("extern_id='$getExternID'");
        }

        if (sizeof($accountFilters) > 0) {
            foreach ($accountFilters as $f) {
                $f_ex = explode("=", $f);
                if (!isset($f_ex[1])) {
                    $f_ey = explode(">", $f_ex[0]);
                    if (substr($f_ey[1], 0, 1) == ".") {
                        $com->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                    }
                }
                else {
                    if (substr($f_ex[1], 0, 1) == ".") {
                        $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    }
                    else {
                        $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    }
                }
            }
        }

        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='" . $_GET['w'] . "'");
        }

        // if(ipadd() == "202.65.117.72"){
        //
        //            $this->db->limit(1);
        // }


        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        showLast_query("biru");
        // cekMErah($mdlName);
        //        arrPrintWebs($tmp);
        //endregion

        //------------------------------------------------------
        if (sizeof($accountBalanceAdvanceColumns) > 0) {
            $advanceSpec = $accountBalanceAdvanceColumns;
            $advHeader = $advanceSpec['header'];

            $this->load->model($advanceSpec['loadModel']);
            $adv = New $advanceSpec['model']();
            if (isset($advanceSpec['filter']) && sizeof($advanceSpec['filter']) > 0) {
                $adv->setFilters(array());
                foreach ($advanceSpec['filter'] as $filter) {
                    $adv->addFilter($filter);
                }
                $adv->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            }
            $tmpSrcDue = $adv->$advanceSpec['method']()->result();
            $tempDataDues = array();
            foreach ($tmpSrcDue as $tmpSrcDue_tmp) {
                $tempDataDues[$tmpSrcDue_tmp->customers_id][] = array(
                    "due_date" => $tmpSrcDue_tmp->due_date,
                    "aging_dtime" => $tmpSrcDue_tmp->dtime,
                );
            }

            $dtime_now = strtotime(date("Y-m-d"));
            foreach ($tempDataDues as $cus_id => $tempDataDues_0) {
                $dueVal = array();
                $dtimeVal = array();
                foreach ($tempDataDues_0 as $dtime_val) {
                    $keyIndex = strtotime($dtime_val['due_date']);
                    $dueVal[] = $keyIndex;
                    $dtimeVal[$keyIndex] = array(
                        "due_date" => $dtime_val['due_date'],
                        "aging" => $dtime_val['aging_dtime'],
                    );
                }
                asort($dueVal);
                $key_index = $dueVal['0'];
                $date_due = $dtimeVal[$key_index]['due_date'];
                $aging = $dtimeVal[$key_index]['aging'];
                if ($dtime_now > $key_index) {
                    $dueEmployee[$cus_id] = array(
                        "due_date" => formatField_he_format("dtime", $date_due),
                        "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                        "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                    );
                }
            }
        }
        //------------------------------------------------------

        $com_sub_nonRekening = array();
        if (sizeof($accountSuperSubChildsNonRekening) > 0) {
            if (isset($accountSuperSubChildsNonRekening[$rekName])) {
                $mdl_sub = "Com" . $accountSuperSubChildsNonRekening[$rekName];
                $this->load->model("Coms/" . $mdl_sub);
                $com_sub = new $mdl_sub();
                $com_sub->addFilter("cabang_id='$cabangID'");
                $com_subTmp = $com_sub->fetchBalances($rekName);
                //                showLast_query("biru");
                //                arrPrintWebs($com_subTmp);
                if (sizeof($com_subTmp) > 0) {
                    foreach ($com_subTmp as $com_subSpec) {
                        $com_sub_nonRekening[$com_subSpec->extern_id] = $com_subSpec->extern_nama;
                    }
                }
            }
        }
        //------------------------------------------------------


        $pairedResult = array();
        $pairedResult_add = array();
        if (isset($balConfig['pairedModel']) && sizeof($balConfig['pairedModel'])) {
            $mdlName = $balConfig['pairedModel']['mdlName'];
            $this->load->model("Mdls/" . $mdlName);
            $mdl = New $mdlName();
            if (isset($balConfig['pairedModel']['filters']) && (sizeof($balConfig['pairedModel']['filters']) > 0)) {
                $mdl->setFilters(array());
                foreach ($balConfig['pairedModel']['filters'] as $filter) {
                    $mdl->addFilter($filter);
                }
            }
            $mdlResult = $mdl->$balConfig['pairedModel']['mdlMethod']()->result();
            //            cekHere($this->db->last_query());
            //            arrPrint($mdlResult);

            if (sizeof($mdlResult) > 0) {
                foreach ($mdlResult as $rSpec) {
                    //                    arrPrintWebs($rSpec);
                    foreach ($balConfig['pairedModel']['fieldName'] as $key => $val) {
                        $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = isset($rSpec->$val) ? $rSpec->$val : "";
                        if ($key == "tipe_produk") {
                            $jml_tipe = isset($rSpec->$val) ? $rSpec->$val : "0";
                            $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = ($jml_tipe > 0) ? "serial" : "non serial";
                        }
                    }
                    //-----
                    $status = isset($rSpec->status) ? $rSpec->status : 0;
                    $trash = isset($rSpec->trash) ? $rSpec->trash : 0;
                    if (($status == 0) && ($trash == 1)) {
                        $keterangan = "<span style='font-size: 12px;color:red;font-style: italic;'>item telah dinonaktifkan</span>";
                    }
                    elseif (($status == 1) && ($trash == 1)) {
                        $keterangan = "<span style='font-size: 12px;color:red;font-style: italic;'>item telah dinonaktifkan</span>";
                    }
                    elseif (($status == 0) && ($trash == 0)) {
                        $keterangan = "<span style='font-size: 12px;color:red;font-style: italic;'>item telah dinonaktifkan</span>";
                    }
                    else {
                        $keterangan = NULL;
                    }
                    if (isset($balConfig['pairedModel']['jenisItems'])) {
                        $ctrlName_history = isset($balConfig['pairedModel']['jenisItems'][$rSpec->jenis]) ? $balConfig['pairedModel']['jenisItems'][$rSpec->jenis] : "";
                        $linkHistory = base_url() . "Data/viewHistories/$ctrlName_history/" . $rSpec->id;
                        $kode = isset($rSpec->kode) ? htmlspecialchars($rSpec->kode, ENT_QUOTES) : "";
                        //                        $nama = isset($rSpec->nama) ? $rSpec->nama : "";
                        $nama = isset($rSpec->nama) ? htmlspecialchars($rSpec->nama, ENT_QUOTES) : "";
                        $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName_history change histories $kode $nama ',
                                        message: $('<div></div>').load('" . $linkHistory . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    }
                    $pairedResult_add[$rSpec->id] = array(
                        "keterangan" => $keterangan,
                        "link_history" => $historyClick,
                    );
                }
            }
        }

        $dataSerial = array();

        if (isset($balConfig["additionalPairSerial"])) {
            $mdlName = $balConfig['additionalPairSerial']['mdlName'];
            $mdlName2 = $balConfig['additionalPairSerial']['mdlName2'];
            $ctrlName_serial = isset($balConfig['additionalPairSerial']['ctrlMethode']) ? $balConfig['additionalPairSerial']['ctrlMethode'] : "";
            //            matiHere($mdlName);
            $mdlSparator = $balConfig['additionalPairSerial']['mdlSparator'];
            $this->load->model($mdlSparator . "/" . $mdlName);
            $this->load->model($mdlSparator . "/" . $mdlName2);
            $mdl = New $mdlName();

            $mdl->addFilter("cabang_id='$cabangID'");
            $mdl->addFilter("qty_debet>0");
            //            if(isset($balConfig['additionalPairSerial']['filter'])){
            //                foreach ($balConfig['additionalPairSerial'] as $fff){
            //                    $mdl->addFilter("$fff");
            //                }
            //            }
            $tmpResult = $mdl->$balConfig['additionalPairSerial']['mdlMethod']($balConfig['additionalPairSerial']['rekening']);
//            showLast_query("biru");
            $mdl2 = New $mdlName2();
            $mdl2->addFilter("cabang_id='$cabangID'");
            $mdl2->addFilter("qty_debet>0");
            $tmpResult2 = $mdl2->$balConfig['additionalPairSerial']['mdlMethod']($balConfig['additionalPairSerial']['rekening']);
//            showLast_query("biru");
//            cekMerah($this->db->last_query()); // _rek_pembantu_produk_perserial_cache
//            // matiHere(__LINE__);
//            // arrPrint($tmpResult);
//            // matiHere();
//            arrPrint($tmpResult2);
//            matiHere(__LINE__);
            $arrGd = array();
            $arrSerialData = array();
            $pairedSerial_add = array();
            if ((count($tmpResult) > 0) || (count($tmpResult2) > 0)) {
                $temp = array();
                $tempLabel = array();
                if (count($tmpResult2) > 0) {
                    foreach ($tmpResult as $arrDatas_0) {
                        if ($arrDatas_0->gudang_id > 0) {
                            $key_serial = "ng_qty_debet";
                        }
                        else {
                            $key_serial = "qty_debet";
                        }
                        $temp[$arrDatas_0->produk_id][$key_serial][] = $arrDatas_0->extern_nama;
                        $arrGd[$arrDatas_0->produk_id][$key_serial] = $arrDatas_0->gudang_id;
                        $tempLabel[$arrDatas_0->produk_id] = $arrDatas_0->produk_nama;
                    }
                }

                if (count($tmpResult2) > 0) {
                    $temp2 = array();
                    $tempLabel2 = array();
                    foreach ($tmpResult2 as $arrDatas_2) {
                        if ($arrDatas_2->gudang_id > 0) {
                            $key_serial = "ng_qty_debet";
                        }
                        else {
                            $key_serial = "qty_debet";
                        }
                        $temp2[$arrDatas_2->produk_id][$key_serial][] = $arrDatas_2->extern_nama;
                        $arrGd2[$arrDatas_2->produk_id][$key_serial] = $arrDatas_2->gudang_id;
                        $tempLabel2[$arrDatas_2->produk_id] = $arrDatas_2->produk_nama;
                    }
                }
//arrPrintPink($arrGd2);

                foreach ($arrGd as $produk_id => $arrDatas_0) {
                    foreach ($arrDatas_0 as $ky_keys => $gudang_id) {
                        $nama = htmlspecialchars($arrDatas_0->produk_nama, ENT_QUOTES);
                        $linkHistory_serial = base_url() . "Ledger/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_transit = base_url() . "Ledger/viewSerialTransit/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_qr = base_url() . "addons/Qr/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_barcode = base_url() . "addons/BarcodePrinter/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $link_qr = "top.popBig('$linkHistory_qr')";
                        $link_barcode = "top.popBig('$linkHistory_barcode')";
                        $link_serial_transit = "BootstrapDialog.closeAll();
                                        BootstrapDialog.show({
                                        title:'DETAIL SERIAL INTRANSIT  $nama ',
                                        message: $('<div></div>').load('" . $linkHistory_transit . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                    });";
                        $link_serial = "BootstrapDialog.closeAll();
                                        BootstrapDialog.show({
                                        title:'DETAIL SERIAL   $nama ',
                                        message: $('<div></div>').load('" . $linkHistory_serial . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                    });";
                        $pairedSerial_add[$produk_id][$ky_keys] = array(
                            "jml_serial_transit" => isset($temp2[$produk_id][$ky_keys]) ? count($temp2[$produk_id][$ky_keys]) : 0,
                            "jml_serial" => isset($temp[$produk_id][$ky_keys]) ? count($temp[$produk_id][$ky_keys]) : 0,
                            "link_qr_transit" => $link_serial_transit,
                            "link_barcode" => $link_barcode,
                            "link_qr" => $link_qr,
                            "link_serial" => $link_serial,
                            //                            "print_serial_qr" => $link_serial,
                            //                            "print_serial_barcode" => $link_serial,
                        );
                    }
                }
                foreach ($arrGd2 as $produk_id => $arrDatas_0) {
                    foreach ($arrDatas_0 as $ky_keys => $gudang_id) {
                        $nama = htmlspecialchars($arrDatas_0->produk_nama, ENT_QUOTES);
                        $linkHistory_serial = base_url() . "Ledger/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_transit = base_url() . "Ledger/viewSerialTransit/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_qr = base_url() . "addons/Qr/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $linkHistory_barcode = base_url() . "addons/BarcodePrinter/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID&gudang_id=$gudang_id";
                        $link_qr = "top.popBig('$linkHistory_qr')";
                        $link_barcode = "top.popBig('$linkHistory_barcode')";
                        $link_serial_transit = "BootstrapDialog.closeAll();
                                        BootstrapDialog.show({
                                        title:'DETAIL SERIAL INTRANSIT  $nama ',
                                        message: $('<div></div>').load('" . $linkHistory_transit . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                    });";
                        $link_serial = "BootstrapDialog.closeAll();
                                        BootstrapDialog.show({
                                        title:'DETAIL SERIAL   $nama ',
                                        message: $('<div></div>').load('" . $linkHistory_serial . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                    });";
                        $pairedSerial_add[$produk_id][$ky_keys] = array(
                            "jml_serial_transit" => isset($temp2[$produk_id][$ky_keys]) ? count($temp2[$produk_id][$ky_keys]) : 0,
                            "link_qr_transit" => $link_serial_transit,

                            "jml_serial" => isset($temp[$produk_id][$ky_keys]) ? count($temp[$produk_id][$ky_keys]) : 0,
                            "link_barcode" => $link_barcode,
                            "link_qr" => $link_qr,
                            "link_serial" => $link_serial,

                            //                            "print_serial_qr" => $link_serial,
                            //                            "print_serial_barcode" => $link_serial,
                        );
                    }
                }
//                arrPrintWebs($pairedSerial_add);
            }
            //                        arrPrint($pairedSerial_add);
            //            cekMerah($this->db->last_query());
            //            matiHere($mdlName);
        }


        $rkTempResult = array();
        if (sizeof($accountRekDetailAdditional) > 0) {
            foreach ($accountRekDetailAdditional as $rekeningNama => $spec) {
                $detailRelRekening = str_replace("akum penyu ", "", $rekeningNama);

                $this->load->model("Coms/ComRekening");
                $rk = New ComRekening();
                $rk->setFilters(array());
                $rk->addFilter("cabang_id='$cabangID'");
                $rkTemp = $rk->fetchBalances($rekeningNama);

                if (sizeof($rkTemp) > 0) {
                    $rkTempResult[$detailRelRekening] = $rkTemp[0];
                }
            }
        }

        $addCustomLink = array();
        if (isset($balConfig['additionalPairedModel']) && sizeof($balConfig['additionalPairedModel']) > 0) {
            //buat data sumber dari query pertama
            $oldBalance = array();
            if (count($tmp) > 0) {
                foreach ($tmp as $tmp_rr) {
                    $oldBalance[$tmp_rr->extern_id] = (array)$tmp_rr;
                }
            }

            //            arrPrint($oldTmp);
            //            matiHEre();
            $addMdlNameRek = $balConfig['additionalPairedModel']['mdlNameRek'];
            $addMethodRek = $balConfig['additionalPairedModel']['mdlMethodRek'];
            $addPrefix = $balConfig['additionalPairedModel']['prefix'];

            $addMdlNameData = $balConfig['additionalPairedModel']['mdlNameData'];
            $addMethodData = $balConfig['additionalPairedModel']['mdlMethodData'];

            // cekHere("$addMdlNameData");
            $this->load->model("Mdls/$addMdlNameData");
            $dt = New $addMdlNameData();
            $dt->setFilters(array());
            $dt->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $dt->addFilter("trash=0");
            $dt->addFilter("status=1");
            $dt->addFilter("id>0");
            $tmpData = $dt->$addMethodData()->result();
            if (sizeof($tmpData) > 0) {

                $ids = array();
                foreach ($tmpData as $spec) {
                    $ids[] = $spec->id;
                }

                $dtr = New $addMdlNameRek();
                $dtr->setFilters(array());
                $dtr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                $dtr->addFilter("gudang_id in ('" . implode("','", $ids) . "')");
                $tmpData = $dtr->$addMethodRek($rekName, $q, $sortBy, $sortMode);
                //                matiHere($this->db->last_query());
                $addDataResult = array();
                $addBalance = array();
                if (sizeof($tmpData) > 0) {
                    foreach ($tmpData as $tmpSpec) {
                        $datasOld = (array)$tmpSpec;
                        unset($datasOld["qty_kredit"]);
                        unset($datasOld["kredit"]);
                        unset($datasOld["qty_debet"]);
                        unset($datasOld["debet"]);
                        unset($datasOld["gudang_id"]);
                        $addBalance[$tmpSpec->extern_id] = $datasOld + array("qty_kredit" => "0", "kredit" => "0", "qty_debet" => "0", "debet" => "0", "gudang_id" => $this->session->login['gudang_id']);
                        if (sizeof($balConfig['additionalViewedColumns']) > 0) {
                            foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                                $addKey_fix = str_replace($addPrefix, "", $addKey);
                                //                                cekMErah($addKey_fix);
                                $addDataResult[$tmpSpec->extern_id][$addKey] = $tmpSpec->$addKey_fix;
                                if (count($customLink) > 0) {
                                    $addCustomLink[$tmpSpec->extern_id]["customLink"][$addKey] = $tmpSpec->gudang_id;
                                }
                            }

                        }
                    }
                    //                    arrPrint($addDataResult);
                }
                //                matiHere(__LINE__);
                $jointTmp = array();
                if (count($addBalance) > 0) {
                    //$oldBalance
                    foreach ($addBalance as $xID => $xDatas) {
                        if (!isset($oldBalance[$xID])) {
                            $oldBalance[$xID] = $xDatas;
                        }


                    }
                    $tmp = array();
                    foreach ($oldBalance as $aa => $aaDatas) {
                        $tmp[] = (object)$aaDatas;
                    }

                }
                //                arrPrintwebs(count($oldBalance));
                //                matiHere();
            }
        }

        //region show locker value
        $finalLocker = array();
        if (sizeof($accountBalanceLocker) > 0) {
            if ($accountBalanceLocker['enabledView'] == true) {
                $mdlLocker = $accountBalanceLocker['mdlName'];
                $this->load->model("Mdls/" . $mdlLocker);
                $lo = new $mdlLocker();
                $stateTmp = $accountBalanceLocker['state'];
//                showLast_query("pink");
                if (sizeof($stateTmp) > 0) {

                    $finalLocker = array();
                    foreach ($stateTmp as $state => $state_0) {

                        $lo->setFilters(array());
                        $lo->addFilter("cabang_id=$cabangID");
                        $modelFilter = $state_0['filters'];
                        if (sizeof($modelFilter) > 0) {
                            foreach ($modelFilter as $f) {
                                $f_ex = explode("=", $f);
                                if (!isset($f_ex[1])) {
                                    $f_ey = explode(">", $f_ex[0]);
                                    if (substr($f_ey[1], 0, 1) == ".") {
                                        $lo->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                                    }
                                    else {
                                        $lo->addFilter($f_ey[0] . ">0");

                                    }
                                }
                                else {
                                    if (substr($f_ex[1], 0, 1) == ".") {
                                        $lo->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                                    }
                                    else {

                                        $lo->addFilter($f_ex[0] . "=''");


                                    }
                                }
                            }
                        }
                        $tmpData = $lo->lookUpAll()->result();
                        if (sizeof($tmpData) > 0) {
                            $lockerValue = array();
                            foreach ($tmpData as $tmpLocker) {
                                if (!isset($lockerValue[$tmpLocker->produk_id])) {
                                    $lockerValue[$tmpLocker->produk_id] = 0;
                                }
                                foreach ($state_0['viewedColums'] as $co => $aliasCol) {
                                    $lockerValue[$tmpLocker->produk_id] += $tmpLocker->$co;
                                }
                                $finalLocker[$state] = $lockerValue;
                            }
                        }
                    }

                }
            }
        }
        //endregion


        $arrExternName = array();
        $items = array();
        $items2 = array();
        $items_blok = array();
        $no = 0;
        if (sizeof($tmp) > 0) {
            $tmpRow = array();
            foreach ($tmp as $row) {
                if (isset($dueEmployee) && (sizeof($dueEmployee) > 0)) {
                    if (isset($dueEmployee[$row->extern_id])) {
                        foreach ($dueEmployee[$row->extern_id] as $advKey => $valKey) {
                            $row->$advKey = $valKey;
                        }
                    }
                }
                // arrPrintWebs($row);

                $rekening = $row->rekening;
                $extern_nama = $row->extern_nama;
                $extern_nama = htmlspecialchars($extern_nama);
                $position = detectRekDefaultPosition($rekening);

                //                $rekening_relasi_tmp = isset($rkTempResult[$row->extern_nama]) ? $rkTempResult[$row->extern_nama] : array();
                $rekening_relasi_tmp = isset($rkTempResult[$extern_nama]) ? $rkTempResult[$extern_nama] : array();
                $rekening_relasi = sizeof($rekening_relasi_tmp) && isset($rekening_relasi_tmp->rekening) ? $rekening_relasi_tmp->rekening : NULL;

                switch ($position) {
                    case "debet":
                        if ($row->kredit > 0) {
                            $row->debet = $row->kredit * -1;
                            $row->kredit = 0;
                        }
                        if ($row->qty_kredit > 0) {
                            $row->qty_debet = $row->qty_kredit * -1;
                            $row->qty_kredit = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet > 0) {
                            $row->kredit = $row->debet * -1;
                            $row->debet = 0;
                        }
                        if ($row->qty_debet > 0) {
                            $row->qty_kredit = $row->qty_debet * -1;
                            $row->qty_debet = 0;
                        }
                        break;
                }

                foreach ($balConfig['viewedColumns'] as $key => $label) {
                    $tmpRow[$key] = isset($row->$key) ? $row->$key : "";

                    if (sizeof($pairedResult) > 0) {
                        if (array_key_exists($row->extern_id, $pairedResult)) {
                            foreach ($pairedResult[$row->extern_id] as $pkey => $pval) {
                                $tmpRow[$pkey] = $pval;
                            }
                        }
                    }
                }
                if (count($customLink) > 0) {
                    foreach ($customLink as $cl_i => $cl) {
                        if (isset($row->$cl)) {
                            $addCustomLink[$row->extern_id]["customLink"][$cl] = $row->gudang_id;
                        }

                    }

                }

                //                $tmpRow['satuan'] = isset($itemSps) && sizeof($itemSps) > 0 ? $itemSps[$row->extern_id]['satuan'] : "-";
                if (isset($itemSps) && sizeof($itemSps) > 0) {
                    if (isset($itemSps[$row->extern_id]['satuan'])) {
                        $satuan = $itemSps[$row->extern_id]['satuan'];
                    }
                    else {
                        $satuan = "-";
                    }
                }
                else {
                    $satuan = "-";
                }
                $tmpRow['satuan'] = $satuan;
                $tmpRow['pId'] = isset($row->extern_id) ? $row->extern_id : 0;

                // pembantu tingkat 1
                if (isset($accountSubChilds[$extern_nama])) {
//                    cekHijau("ada relasi dengan accountSubChilds, $extern_nama, $rekening");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$extern_nama] . "/" . $extern_nama;
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";

                } // pembantu tingkat 3
                elseif (isset($accountSuperSubChilds[$extern_nama])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekOrange("ada relasi dengan accountSuperSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSuperSubChilds[$extern_nama] . "/" . $extern_nama . "?ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";

                } // pembantu tingkat 2
                elseif (isset($accountSubChilds[$rekening])) {
                    $subExternID = $getExtern2ID;
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
//                    cekPink("ada relasi dengan accountSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening] . "/" . $rekening . "?ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "/$subExternID?o=$cabangID" . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    //                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    //cekKuning("$relName/$rekName/");
                } //
                elseif (array_key_exists($row->extern_id, $com_sub_nonRekening)) {
                    cekMerah(":: non rekening, masuk ke pembantu lagi ::");
                    if ($getExternID != NULL) {
                        $text_pair = $blob_ext . " " . $extern_nama;
                        $text = blobEncode($text_pair);
                        $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/" . $accountSuperSubChildsNonRekening[$rekening] . "/" . $rekening . "/" . $row->extern_id . "?ext2_id=" . $row->extern2_id . "&blob_ext=$text";
                        //                        $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $text_pair = $blob_ext . " " . $extern_nama;
                        $text = blobEncode($text_pair);
                        $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSuperSubChildsNonRekening[$rekening] . "/" . $rekening . "?ext_id=" . $row->extern_id . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&blob_ext=$text";
                    }

                } // tidak ada pembantu
                else {
                    //                    cekOrange("TIDAK ada relasi dengan accountSubChilds, $extern_nama, $rekening");
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    if ($rekening != "laba ditahan") {
                        //baca config custom link dari configaccounting

                        $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getExtern2ID . "&main_ext2_id=" . $getExtern2ID . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $tmpRow['link'] = "#";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }

                }
                //arrPrintKuning($tmpRow);
                if (isset($balConfig['additionalPairSerialTransit']['viewedColumns']) && sizeof($balConfig['additionalPairSerialTransit']['viewedColumns']) > 0) {
                    foreach ($balConfig['additionalPairSerialTransit']['viewedColumns'] as $addKey => $addVal) {
                        $tmpRow[$addKey] = isset($dataSerial[$row->extern_id]) ? $dataSerial[$row->extern_id] : 0;
                    }
                }
                if (isset($balConfig['additionalPairSerial']['viewedColumns']) && sizeof($balConfig['additionalPairSerial']['viewedColumns']) > 0) {
                    foreach ($balConfig['additionalPairSerial']['viewedColumns'] as $addKey => $addVal) {
                        $tmpRow[$addKey] = isset($dataSerial[$row->extern_id]) ? $dataSerial[$row->extern_id] : 0;
                    }
                }
                if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
                    foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                        $tmpRow[$addKey] = isset($addDataResult[$row->extern_id][$addKey]) ? $addDataResult[$row->extern_id][$addKey] : 0;
                    }
                }
                if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
                    $src_qty = "qty_" . $position;
                    $src_ng_qty = "ng_qty_" . $position;
                    $src_val = $position;
                    $src_ng_val = "ng_" . $position;
                    $tmpRow['total_qty_' . $position] = $tmpRow[$src_qty] + $tmpRow[$src_ng_qty];
                    $tmpRow['total_' . $position] = $tmpRow[$src_val] + $tmpRow[$src_ng_val];
                }

                // arrPrintKuning($tmpRow);
                $no++;
                $no_main = $no;
                $items[$no] = $tmpRow;
                $arrExternName[$row->extern_id] = $extern_nama;

                $saldo_rek_utama = $row->$position;


                // ================================================================================================
                if ($rekening_relasi != NULL) {

                    $positionRel = detectRekDefaultPosition($rekening_relasi);

                    $rekening_relasi_tmp->extern_nama = $rekening_relasi_tmp->rekening;

                    switch ($positionRel) {
                        case "debet":
                            if ($rekening_relasi_tmp->kredit > 0) {
                                $rekening_relasi_tmp->debet = $rekening_relasi_tmp->kredit * -1;
                                $rekening_relasi_tmp->kredit = 0;
                            }
                            if ($rekening_relasi_tmp->qty_kredit > 0) {
                                $rekening_relasi_tmp->qty_debet = $rekening_relasi_tmp->qty_kredit * -1;
                                $rekening_relasi_tmp->qty_kredit = 0;
                            }
                            break;
                        case "kredit":
                            if ($rekening_relasi_tmp->debet > 0) {
                                $rekening_relasi_tmp->kredit = $rekening_relasi_tmp->debet * -1;
                                $rekening_relasi_tmp->debet = 0;
                            }
                            if ($rekening_relasi_tmp->qty_debet > 0) {
                                $rekening_relasi_tmp->qty_kredit = $rekening_relasi_tmp->qty_debet * -1;
                                $rekening_relasi_tmp->qty_debet = 0;
                            }
                            break;
                    }
                    foreach ($balConfig['viewedColumns'] as $key => $label) {
                        $tmpRowRel[$key] = isset($rekening_relasi_tmp->$key) ? $rekening_relasi_tmp->$key : "";
                        if (sizeof($pairedResult) > 0) {
                            if (array_key_exists($rekening_relasi_tmp->extern_id, $pairedResult)) {
                                foreach ($pairedResult[$rekening_relasi_tmp->extern_id] as $pkey => $pval) {
                                    $tmpRowRel[$pkey] = $pval;
                                }
                            }
                        }
                    }

                    $tmpRowRel['satuan'] = isset($itemSps) && sizeof($itemSps) > 0 ? $itemSps[$rekening_relasi_tmp->extern_id]['satuan'] : "-";
                    $tmpRowRel['pId'] = 0;
                    $mainLink = isset($accountRekDetailAdditional[$rekening_relasi_tmp->rekening]['mainLink']) ? $accountRekDetailAdditional[$rekening_relasi_tmp->rekening]['mainLink'] : "";


                    if (isset($accountSubChilds[$rekening_relasi])) {

                        $tmpRowRel['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening_relasi] . "/" . $rekening_relasi;
                        $tmpRowRel['link_main']['extern_nama'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;
                    }
                    else {

                        $tmpRowRel['link'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;
                        $tmpRowRel['link_main']['extern_nama'] = NULL;
                    }

                    //                    $tmpRowRel['link'] = base_url() . "$mainLink" . $rekening_relasi_tmp->rekening;

                    $no++;
                    $no_relasi = $no;
                    $items[$no] = $tmpRowRel;

                    $saldo_rek_relasi = $rekening_relasi_tmp->$positionRel;
                }
                else {
                    $no_relasi = NULL;
                    $saldo_rek_relasi = 0;
                }

                // ================================================================================================
                if (sizeof($accountRekDetailAdditional) > 0) {
                    $items_blok[] = array(
                        "main" => $no_main,
                        "relasi" => $no_relasi,
                    );
                }

                if (sizeof($accountBalanceAdditionalColumns) > 0) {

                    $items[$no]['netto'] = $saldo_rek_utama - $saldo_rek_relasi;
                }
                // ================================================================================================

                if (sizeof($finalLocker) > 0) {
                    foreach ($finalLocker as $stateX => $valState) {
                        $items[$no][$stateX] = isset($valState[$row->extern_id]) ? $valState[$row->extern_id] : "";
                    }
                }
                // ================================================================================================
                // arrPrintHijau($advHeader);
                if (isset($advHeader) && sizeof($advHeader) > 0) {
                    foreach ($advHeader as $advKey => $advVal) {
                        $items[$no][$advKey] = isset($row->$advKey) ? $row->$advKey : "-";
                    }
                }

                // timestamp download
                $items[$no]['stamp'] = dtimeNow('ymd-His');
            }
        }


        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            "pId" => "pID",
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        if (isset($balConfig['viewed2Columns']) && sizeof($balConfig['viewed2Columns'])) {
            unset($headerFields["pId"]);
            unset($headerFields["extern_nama"]);

            foreach ($balConfig['viewed2Columns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["size_nama"] = "UOM";


        $headerQtyFields = array();
        $headerValueFields = array();
        if (isset($balConfig['viewedColumnsStatus']) && ($balConfig['viewedColumnsStatus'] == true)) {
            foreach ($balConfig['viewedColumns'] as $key => $val) {
                $headerQtyFields['qty_' . $key] = $val;
                $headerValueFields[$key] = $val;
            }
        }
        else {
            $headerQtyFields = array(
                "qty_" . $defPosition => "gudang reguler (QTY)",
            );
            $headerValueFields = array(
                $defPosition => "gudang reguler (IDR)",
            );
        }

        $headerValue = isset($balConfig['header']) ? $balConfig['header'] : $headerValueFields;

        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValue;
        }
        if (sizeof($accountBalanceAdditionalColumns) > 0) {
            $headerFields = $headerFields + $accountBalanceAdditionalColumns;
        }
        if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
            foreach ($balConfig['additionalViewedColumns'] as $key => $val) {
                $addViewedColumns[$key] = $val;
            }
            $headerFields = $headerFields + $addViewedColumns;
        }

        if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
            foreach ($balConfig['additionalTotalViewedColumns'] as $key => $val) {
                $addTotalViewedColumns[$key] = $val;
            }
            $headerFields = $headerFields + $addTotalViewedColumns;
        }

        if (sizeof($finalLocker) > 0) {
            foreach ($accountBalanceLocker['state'] as $stateCol => $tmpLabel) {
                $headerFields[$stateCol] = $tmpLabel['label'];
            }
        }
        if (isset($advHeader) && sizeof($advHeader) > 0) {
            foreach ($advHeader as $key => $val) {
                $headerFields[$key] = $val;
            }
        }


        $subTitle = "balances ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }

        $summaryAllowed = array("debet", "kredit", "qty_debet", "qty_kredit", "netto");
        if (isset($balConfig['additionalViewedColumns']) && sizeof($balConfig['additionalViewedColumns']) > 0) {
            $addKey = array_keys($balConfig['additionalViewedColumns']);
            $summaryAllowed = array_merge($summaryAllowed, $addKey);
        }
        if (isset($balConfig['additionalTotalViewedColumns']) && sizeof($balConfig['additionalTotalViewedColumns']) > 0) {
            $addKey = array_keys($balConfig['additionalTotalViewedColumns']);
            $summaryAllowed = array_merge($summaryAllowed, $addKey);
        }

        $param_to_excel = array(
            "mdl" => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo" => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            // "mdl_data"  => $mdlData,
            "cabang_id" => $cabangID,
        );
        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        $headerFields["stamp"] = "timestamp";

        if (ipadd() == "202.65.117.72") {
            // matiHere(__LINE__);
        }

        $rekName_f = isset(fetchAccountStructureAlias()[$rekName]) ? fetchAccountStructureAlias()[$rekName] : $rekName;
        $data = array(
            "mode" => "saldo_2",
            "title" => "$rekName_f",
            "subTitle" => "$subTitle $blob_ext",
            "items" => $items,
            "headerFields" => $headerFields,
            "thisPage" => $thisPage,
            "thisURL" => $thisURL,
            "q" => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
            "summary" => $summaryAllowed,
            "items_blok" => $items_blok,
            "param_to_excel" => $param_to_excel_e,
            "pairedResult_add" => isset($pairedResult_add) ? $pairedResult_add : array(),
            "pairedSerial_add" => isset($pairedSerial_add) ? $pairedSerial_add : array(),
            //            "customLinkAdd"=>$customLink,
            "customLinkAdd" => $addCustomLink,
            "linkRemoveSerial" => base_url() . get_class($this) . "/doRemoveSerial",

        );

        $this->load->view("ledger", $data);


    }

}


