<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/31/2019
 * Time: 10:00 PM
 */

class Ledger extends CI_Controller
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
        //         arrPrintKuning(url_segment());
        //         mati_disini();
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

        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        // showLast_query("biru");
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
                    "due_date"    => $tmpSrcDue_tmp->due_date,
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
                        "aging"    => $dtime_val['aging_dtime'],
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
                        "aging"    => umurDay($aging) > 0 ? umurDay($aging) : "0",
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
                        "keterangan"   => $keterangan,
                        "link_history" => $historyClick,
                    );
                }
            }
        }
        $pairedSerial_add = array();
        $dataSerial = array();
        if (isset($balConfig["additionalPairSerial"])) {
            $mdlName = $balConfig['additionalPairSerial']['mdlName'];
            $ctrlName_serial = isset($balConfig['additionalPairSerial']['ctrlMethode']) ? $balConfig['additionalPairSerial']['ctrlMethode'] : "";
            //            matiHere($mdlName);
            $mdlSparator = $balConfig['additionalPairSerial']['mdlSparator'];
            $this->load->model($mdlSparator . "/" . $mdlName);
            $mdl = New $mdlName();
            $mdl->addFilter("cabang_id='$cabangID'");
            $mdl->addFilter("qty_debet>0");
            //            if(isset($balConfig['additionalPairSerial']['filter'])){
            //                foreach ($balConfig['additionalPairSerial'] as $fff){
            //                    $mdl->addFilter("$fff");
            //                }
            //            }
            $tmpResult = $mdl->$balConfig['additionalPairSerial']['mdlMethod']($balConfig['additionalPairSerial']['rekening']);
            //            cekMerah($this->db->last_query());
            //            arrPrint($tmpResult);
            if (count($tmpResult) > 0) {
                $temp = array();
                $tempProdukSerialMster = array();
                foreach ($tmpResult as $tmpItems) {
                    $temp[$tmpItems->produk_id][] = $tmpItems->extern_nama;
                    $tempProdukSerialMster[$tmpItems->produk_id] = $tmpItems->produk_nama;
                }
                if (count($temp) > 0) {
                    foreach ($temp as $produk_id => $arrDatas) {
                        $nama = htmlspecialchars($tempProdukSerialMster[$produk_id], ENT_QUOTES);
                        $dataSerial[$produk_id] = count($arrDatas);
                        $linkHistory_serial = base_url() . "Ledger/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID";
                        $linkHistory_qr = base_url() . "addons/Qr/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID";
                        $linkHistory_barcode = base_url() . "addons/BarcodePrinter/viewSerial/?produk_id=" . $produk_id . "&cabang_id=$cabangID";
                        $link_qr = "top.popBig('$linkHistory_qr')";
                        $link_barcode = "top.popBig('$linkHistory_barcode')";
                        $link_serial = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'DETAIL SERIAL   $nama ',
                                        message: $('<div></div>').load('" . $linkHistory_serial . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                        $pairedSerial_add[$produk_id] = array(
                            "jml_serial"   => count($arrDatas),
                            "link_barcode" => $link_barcode,
                            "link_qr"      => $link_qr,
                            "link_serial"  => $link_serial,
                            //                            "print_serial_qr" => $link_serial,
                            //                            "print_serial_barcode" => $link_serial,
                        );
                        //                    }

                    }
                }
            }
            //            arrPrint($dataSerial);
            //            cekMerah($this->db->last_query());
            //            matiHere($mdlName);
        }
        //        arrprint($pairedSerial_add);
        //        arrPrintWebs($pairedResult_add);
        //                arrPrintWebs($pairedResult);

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
                //                showLast_query("merah");
                //                cekMerah(sizeof($tmpData));
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
                                $addDataResult[$tmpSpec->extern_id][$addKey] = $tmpSpec->$addKey_fix;
                                if (count($customLink) > 0) {
                                    $addCustomLink[$tmpSpec->extern_id]["customLink"][$addKey] = $tmpSpec->gudang_id;
                                }
                            }

                        }
                    }
                }
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
                //                                arrPrintWebs($row);

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
                    cekHijau("ada relasi dengan accountSubChilds, $extern_nama, $rekening");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$extern_nama] . "/" . $extern_nama;
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";

                }
                // pembantu tingkat 3
                elseif (isset($accountSuperSubChilds[$extern_nama])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekOrange("ada relasi dengan accountSuperSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSuperSubChilds[$extern_nama] . "/" . $extern_nama . "?ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";

                }
                // pembantu tingkat 2
                elseif (isset($accountSubChilds[$rekening])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    cekPink("ada relasi dengan accountSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening] . "/" . $rekening . "?ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    //                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";

                }
                //
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

                }
                // tidak ada pembantu
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
                        "main"   => $no_main,
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
                if (isset($advHeader) && sizeof($advHeader) > 0) {
                    foreach ($advHeader as $advKey => $advVal) {
                        $items[$no][$advKey] = isset($row->$advKey) ? $row->$advKey : "-";
                    }
                }
            }
        }


        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            "pId"    => "pID",
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
//        $headerFields["satuan"] = "UOM";
        $headerFields["size_nama"] = "UOM";

        if (isset($balConfig['additionalPairSerial']['viewedColumns']) && sizeof($balConfig['additionalPairSerial']['viewedColumns'])) {
            foreach ($balConfig['additionalPairSerial']['viewedColumns'] as $ke => $ve) {
                $headerFields[$ke] = $ve;
            }
        }

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
                "qty_" . $defPosition => "balance (QTY)",
            );
            $headerValueFields = array(
                $defPosition => "balance (IDR)",
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

        //arrPrintWebs($items);
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
            "mdl"       => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo"      => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            // "mdl_data"  => $mdlData,
            "cabang_id" => $cabangID,
        );
        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        //        arrPrintWebs($addCustomLink);
        $rekName_f = isset(fetchAccountStructureAlias()[$rekName]) ? fetchAccountStructureAlias()[$rekName] : $rekName;
        $data = array(
            "mode"             => "saldo",
            "title"            => "$rekName_f",
            "subTitle"         => "$subTitle $blob_ext",
            "items"            => $items,
            "headerFields"     => $headerFields,
            "thisPage"         => $thisPage,
            "thisURL"          => $thisURL,
            "q"                => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
            "summary"          => $summaryAllowed,
            "items_blok"       => $items_blok,
            "param_to_excel"   => $param_to_excel_e,
            "pairedResult_add" => isset($pairedResult_add) ? $pairedResult_add : array(),
            "pairedSerial_add" => isset($pairedSerial_add) ? $pairedSerial_add : array(),
            //            "customLinkAdd"=>$customLink,
            "customLinkAdd"    => $addCustomLink,
            "linkRemoveSerial" => base_url() . get_class($this) . "/doRemoveSerial",

        );

        $this->load->view("ledger", $data);


    }

    public function viewBalances_t1()
    {

        $relName = $this->uri->segment(3);
        $rekName = str_replace("_", " ", urldecode($this->uri->segment(4)));
        $defPosition = detectRekDefaultPosition($rekName);

        $balConfig = isset($this->config->item('accountBalanceColumns')[$relName]) ? $this->config->item('accountBalanceColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();


        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlGudang");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        $gd = new MdlGudang();

        $gd->addFilter("cabang_id='$cabangID'");
        $tmpGd = $gd->lookupAll()->result();
        // cekHere($this->db->last_query());

        //region gudangId
        $itemsCbNama = array();
        foreach ($tmpGd as $itemGd) {
            $itemsCbNama[$itemGd->id] = $itemGd->nama;
        }
        $itemsCbNama[$this->gudangID] = $this->gudangName;
        //endregion

        $com->addFilter("cabang_id='$cabangID'");

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
                    // if (substr($f_ex[1], 0, 1) == ".") {
                    //     $com->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                    // }
                    // else {
                    //     $com->addFilter($f_ex[0] . "='" . $this->session->login[$f_ex[1]] . "'");
                    // }
                    // arrPrint($f_ex);
                }
            }
        }

        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='" . $_GET['w'] . "'");
        }

        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        //        cekUngu(get_class($this) . "<br>" . $this->db->last_query());

        $pairedResult = array();
        if (isset($balConfig['pairedModel']) && sizeof($balConfig['pairedModel'])) {
            $mdlName = $balConfig['pairedModel']['mdlName'];
            $this->load->model("Mdls/" . $mdlName);
            $mdl = New $mdlName();
            $mdlResult = $mdl->$balConfig['pairedModel']['mdlMethod']()->result();

            if (sizeof($mdlResult) > 0) {
                foreach ($mdlResult as $rSpec) {
                    foreach ($balConfig['pairedModel']['fieldName'] as $key => $val) {
                        $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = isset($rSpec->$val) ? $rSpec->$val : "";
                    }
                }
            }
        }
        //arrPrint($pairedResult);

        $items = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpRow = array();
                foreach ($balConfig['viewedColumns'] as $key => $label) {
                    $tmpRow[$key] = isset($row->$key) ? $row->$key : "";
                    if ((sizeof($pairedResult) > 0) && (isset($pairedResult[$row->extern_id]))) {
                        //                        if (array_key_exists($row->extern_id, $pairedResult)) {
                        $tmpRow["pId"] = $row->extern_id;
                        foreach ($pairedResult[$row->extern_id] as $pkey => $pval) {
                            $tmpRow[$pkey] = $pval;
                        }
                        //                        }
                    }
                }

                $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID";
                $items[$row->gudang_id][$row->extern_id] = $tmpRow;
                $produks[$row->extern_id] = $tmpRow;

                $itemsCbId[$row->gudang_id]['gudang_nama'] = $itemsCbNama[$row->gudang_id];
                $itemsCbId[$row->gudang_id]['gudang_id'] = $row->gudang_id;
            }
        }
        //        arrPrintWebs($items);

        //ganti headerFields
        $headerFields = array(
            //            "rek_id" => "kode",
            //            "kode" => "product code",
            //            "extern_nama" => "item names",
            //
            //
        );
        if (isset($balConfig['pairedModel']['viewedColumns']) && sizeof($balConfig['pairedModel']['viewedColumns'])) {
            foreach ($balConfig['pairedModel']['viewedColumns'] as $k => $v) {
                $headerFields[$k] = $v;
            }
        }
        $headerFields["extern_nama"] = "item names";
        //        arrPrint($headerFields);

        $headerQtyFields = array(
            "qty_" . $defPosition => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $subTitle = "balances ";
        if ($q != "") {
            $subTitle .= " matched '$q'";
        }


        $fieldToshows = array(
            "pId"         => "pID",
            //            "rek_id"      => "kode",
            "kode"        => "product code",
            "extern_nama" => "item names",
        );
        $fieldAttrHeader = array(
            // "no" => "class='text-center text-uppercase bg-info' rowspan='2'",
            "pId"         => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "extern_nama" => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "rek_id"      => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
            "kode"        => "class='text-center text-uppercase bg-info valign-m' rowspan='2'",
        );

        $subHeaders = array(
            "balance (QTY)" => "class='text-center text-uppercase bg-info'",
            "balance (IDR)" => "class='text-center text-uppercase bg-info'",
        );
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        foreach ($itemsCbId as $itemCbs) {
            // $mainHeaders_1 = array("cabang" => "class='text-center bg-info' colspan='4'");
            $mainHeaders_1[$itemCbs['gudang_nama']] = "class='text-center bg-info' colspan='2'";
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);


        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);


        //endregion
        $subFields = array(
            "qty_debet" => array(
                "label"  => "balance (QTY)",
                "attr"   => "class='text-right'",
                "format" => "formatField2",
            ),
            "debet"     => array(
                "label"  => "balance (IDR)",
                "attr"   => "class='text-right'",
                "format" => "formatField2",
            ),
        );
        foreach ($subFields as $field => $fChilds) {
            $sfields[] = $field;
            isset($fChilds['label']) ? $sfieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $sfieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $sfieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $sfieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $sfieldFormat[$field] = $fChilds['format'] : "";
        }
        foreach ($items as $cb_id => $item) {

            foreach ($produks as $pId => $item2) {


                foreach ($sfieldToshows as $dField => $lField) {

                    $sattr = $sfieldAttr[$dField];
                    $svalue = isset($item[$pId][$dField]) ? $item[$pId][$dField] : 0;
                    $svalue_f = isset($sfieldFormat[$dField]) ? $sfieldFormat[$dField]($dField, $svalue) : $svalue;

                    $gspecs[$pId][$cb_id][$dField . "_$cb_id"]['value'] = $svalue_f;
                    $gspecs[$pId][$cb_id][$dField . "_$cb_id"]['attr'] = $sattr;
                }
            }
        }


        $no = 0;
        $specs = array();
        foreach ($produks as $proId => $item) {
            $no++;
            $specs["no"]['value'] = $no;
            $specs["no"]['attr'] = "class='text-right'";

            foreach ($fieldToshows as $field => $fieldToshow) {
                // cekHitam($field);
                $specs[$field]['value'] = isset($item[$field]) ? $item[$field] : "-";
                $specs[$field]['attr'] = "class='text-left'";
            }
            foreach ($gspecs[$proId] as $cId => $itemCbs) {

                foreach ($itemCbs as $filedCb => $itemCb) {

                    $gdSpecs[$filedCb] = $itemCb;
                }
                // arrPrint($itemCbs);
            }
            $bodies[] = $specs + $gdSpecs;
        }
        //cekHitam("cetaik Items");
        //arrPrintWebs($items);

        $footers = array();
        $data = array(
            // "mode" => "saldo",
            "mode"         => "persediaan",
            "title"        => "$rekName",
            "subTitle"     => $subTitle,
            "items"        => $items,
            "cabangs"      => $itemsCbId,
            //            "headerFields" => $balConfig['viewedColumns'],
            "rowLoop"      => sizeof($itemsCbId),
            "headerFields" => $headerFields,
            "mainHeaders"  => $mainHeaders,
            "subHeaders"   => $subHeaders,
            "bodies"       => $bodies,
            "footers"      => $footers,

            "thisPage" => $thisPage,
            "thisURL"  => $thisURL,
            "q"        => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
        );
        //endregion


        $this->load->view("ledger", $data);


    }

    public function viewMoves_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $transaksiID = (isset($_GET['trID']) && $_GET['trID'] > 0) ? "&trID=" . $_GET['trID'] : "";
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $linkKolomConfig = isset($this->config->item('accountMoveColumns')[$relName]['linkToDetail']) ? $this->config->item('accountMoveColumns')[$relName]['linkToDetail'] : array();
        $linkDetailConfig = isset($this->config->item('accountMoveColumns')[$relName]['baselink']) ? $this->config->item('accountMoveColumns')[$relName]['baselink'] : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlCabang");

        $cb = New MdlCabang();
        $tmpCb = $cb->lookupAll()->result();
        $arrCabang = array();
        if (sizeof($tmpCb) > 0) {
            foreach ($tmpCb as $spec) {
                $arrCabang[$spec->id] = $spec->nama;
            }
        }
        $cabangNama_f = isset($arrCabang[$cabangID]) ? "(" . $arrCabang[$cabangID] . ")" : "";

        $com = new $mdlName();
        $tr = new MdlTransaksi();


        $com->addFilter("cabang_id='$cabangID'");

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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $this->db->order_by("id", "DESC");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName);
        //        cekkuning($this->db->last_query());


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        foreach ($tmp as $temX) {
            $transaksi_id = $temX->transaksi_id;
            $position = $temX->debet > 0 ? "debet" : "kredit";
            $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
            $detectRek = detectRekByPosition($rekName, $val, $position);
            //            cekHere($temX->jenis);
            if ($detectRek > 0) {
                $arrMovedDetail["in"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }
            else {
                $arrMovedDetail["out"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }

            $arrProds[][$temX->jenis] = array(
                "$position"        => "$val",
                "qty_" . $position => $qtyVal,
            );

            $tmpTrIds[$transaksi_id] = 1;
        }
        $trIds = array_keys($tmpTrIds);
        //endregion

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime"          => "time",
                "jenis"          => "note",
                "nomer_top"      => "reference number",
                "transaksi_no"   => "receipt number",
                "review_details" => "&nbsp;",
                "suppliers_nama" => "vendor",
                "cabang_nama"    => "branch",
                "oleh_nama"      => "by",
            );
        }
        else {
            $headerFields = array(
                "dtime"          => "time",
                "jenis"          => "note",
                "nomer_top"      => "reference number",
                "transaksi_no"   => "receipt number",
                "review_details" => "&nbsp;",
                "customers_nama" => "customer",
                "cabang_nama"    => "branch",
                "oleh_nama"      => "by",
            );
        }
        $jenisAliases = arrCodeAliasing($cabangID);

        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        $trDatasLink = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            $datasLink = array();
            foreach ($headerFields as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    //                    $datas[$hField] = array_key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];
                    }
                    else {
                        $datas[$hField] = $tmpTr->$hField;
                    }
                }

                if (array_key_exists($hField, $linkKolomConfig)) {
                    $comPembantu = isset($linkKolomConfig[$hField]['rekening'][$rekName]['com']) ? $linkKolomConfig[$hField]['rekening'][$rekName]['com'] : $accountChilds[$rekName];
                    $rekName_new = isset($linkKolomConfig[$hField]['rekening'][$rekName]['rek']) ? $linkKolomConfig[$hField]['rekening'][$rekName]['rek'] : $rekName;
                    $link = base_url() . "$linkDetailConfig" . $comPembantu . "/$rekName_new/" . $tmpTr->$linkKolomConfig[$hField]['key'] . "?o=$cabangID&trID=" . $tmpTr->id;
                    $datasLink[$hField] = $link;
                }
            }
            $trDatas[$tmpTr->id] = $datas;
            $trDatasLink[$tmpTr->id] = $datasLink;
        }

        // endregion builder data transaksi yg perlu muncul


        $headerQtyFields = array(

            "qty_" . $defPosition . "_awal"  => "prev (QTY)",
            "qty_" . $defPosition            => "in (QTY)",
            "qty_" . $opPosition             => "out (QTY)",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)",

        );
        $headerValueFields = array(

            $defPosition . "_awal"  => "prev (IDR)",
            $defPosition            => "in (IDR)",
            $opPosition             => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $row) {
                //                arrPrintWebs($row);
                $subs = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        $subs[$key] = key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;
                    }
                    if ($key == "review_details") {
                        $subs[$key] = $row->transaksi_id;
                    }
                }
                $subs_r = array_replace($subs, $trDatas[$row->transaksi_id]);
                $subs_r["transaksi_id"] = $row->transaksi_id;
                $modul = isset($this->masterConfigUi[$row->jenis]["modul"]) ? $this->masterConfigUi[$row->jenis]["modul"] : false;
                $subs_r['modul'] = $modul;
                $subs_r['modul_path'] = base_url() . $modul . "/";
                $subs_r['jenis_master'] = $row->jenis;

                $items[] = $subs_r;


            }
        }


        $data = array(
            "mode"     => "mutasi",
            "title"    => isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName,
            "subTitle" => "  $subTitle_date &nbsp; $cabangNama_f $subSubTitle_date",
            "items"    => $items,

            "headerFields"  => $headerFields,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID$transaksiID",
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
            "addDetailLink" => sizeof($trDatasLink) > 0 ? $trDatasLink : array(),
        );
        $this->load->view("ledger", $data);


    }

    public function viewMoves_l2()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        //matiHEre($relName);
        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();

        $com->addFilter("cabang_id='$cabangID'");

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


        $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
        $this->db->where("fulldate>='" . $date1 . "'");
        $this->db->where("fulldate<='" . $date2 . "'");
        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekkuning($this->db->last_query());
        //        arrPrint($tmp);
        //        die();


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];


        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields = array(
            //            "extern_nama"=>"item names",
            "dtime"        => "time",
            "transaksi_no" => "receipt number",
            //            "keterangan"=>"description",
            //            "jenis"=>"activity",
        );
        $headerQtyFields = array(

            "qty_" . $defPosition . "_awal"  => "prev (QTY)",
            "qty_" . $defPosition            => "in (QTY)",
            "qty_" . $opPosition             => "out (QTY)",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)",

        );
        $headerValueFields = array(

            $defPosition . "_awal"  => "prev (IDR)",
            $defPosition            => "in (IDR)",
            $opPosition             => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }

        //        arrprint($headerFields);


        $title = "";
        $items = array();
        if (sizeof($tmp > 0)) {
            foreach ($tmp as $row) {
                $subs = array();
                foreach ($headerFields as $key => $label) {
                    $subs[$key] = $row->$key;
                }


                $items[] = $subs;
                $title = $row->extern_nama;
            }
        }


        //        arrprint($headerFields);

        //region prepare params to viewer
        $data = array(
            "mode"         => "mutasi",
            "title"        => "$title",
            "subTitle"     => "changes",
            "items"        => $items,
            //            "headerFields" => $balConfig['viewedColumns'],
            "headerFields" => $headerFields,
            "filters"      => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }

    /*
     * versi dipisah qty dan hpp juga ada saldo berjalan
     */
    public function viewMoveDetails_versi_qty_pisah_hpp()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;
        //        $rekName_f = isset($this->config->item('accountAlias')[$rekName]) ? $this->config->item('accountAlias')[$rekName] : null;
        $rekName_f = isset(fetchAccountStructureAlias()[$rekName]) ? fetchAccountStructureAlias()[$rekName] : null;
        $accountBalanceLocker = isset($this->config->item('accountBalanceColumLocker')[$relName]) ? $this->config->item('accountBalanceColumLocker')[$relName] : array();

        //region show locker value
        $finalLocker = array();
        if (sizeof($accountBalanceLocker) > 0) {
            //region locker rekening
            $mdlLocker = $accountBalanceLocker['mdlName'];
            $this->load->model("Mdls/" . $mdlLocker);
            $lo = new $mdlLocker();
            $stateTmp = $accountBalanceLocker['state'];
            if (sizeof($stateTmp) > 0) {
                $finalLocker = array();
                $trIDLocker = array();
                foreach ($stateTmp as $state => $state_0) {
                    $lo->setFilters(array());
                    $lo->addFilter("cabang_id=$cabangID");
                    $lo->addFilter("produk_id=$externID");
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
                            //                            arrPrintWebs($tmpLocker);
                            //                            if (!isset($lockerValue[$tmpLocker->produk_id])) {
                            //                                $lockerValue[$tmpLocker->produk_id] = 0;
                            //                            }
                            //                            foreach ($state_0['viewedColums'] as $co => $aliasCol) {
                            //                                $lockerValue[$tmpLocker->produk_id] += $tmpLocker->$co;
                            //                            }
                            //                            $finalLocker[$state] = $lockerValue;
                            if ($state == "hold") {
                                if (isset($tmpLocker->nilai) && ($tmpLocker->nilai > 0)) {
                                    $trIDLocker[$tmpLocker->transaksi_id]['nilai'] = isset($tmpLocker->nilai) ? $tmpLocker->nilai : 0;
                                    //                                    $trIDLocker[$tmpLocker->transaksi_id]['jumlah'] = isset($tmpLocker->jumlah) ? $tmpLocker->jumlah : 0;
                                }
                                if (isset($tmpLocker->jumlah) && ($tmpLocker->jumlah > 0)) {
                                    //                                    $trIDLocker[$tmpLocker->transaksi_id]['nilai'] = isset($tmpLocker->nilai) ? $tmpLocker->nilai : 0;
                                    $trIDLocker[$tmpLocker->transaksi_id]['jumlah'] = isset($tmpLocker->jumlah) ? $tmpLocker->jumlah : 0;
                                }
                            }
                            elseif ($state == "active") {
                                $finalLocker['debet_akhir'] = (isset($tmpLocker->nilai) && ($tmpLocker->nilai > 0)) ? $tmpLocker->nilai : 0;
                                $finalLocker['qty_debet_akhir'] = (isset($tmpLocker->jumlah) && ($tmpLocker->jumlah > 0)) ? $tmpLocker->jumlah : 0;
                            }
                        }
                    }
                }

                if ($trIDLocker) {
                    $arrKeyLocker = array_keys($trIDLocker);
                    //                    arrPrint($arrKeyLocker);

                    $this->load->model("MdlTransaksi");
                    $tr = New MdlTransaksi();
                    $tr->addFilter("id in ('" . implode("','", $arrKeyLocker) . "')");
                    $trTmp = $tr->lookupAll()->result();
                    $arrJenisTr = array();
                    foreach ($trTmp as $spec) {
                        $arrJenisTr[$spec->id] = $spec->jenis;
                    }
                }
                $additionalInOut = array();
                if (sizeof($arrJenisTr) > 0) {
                    foreach ($arrJenisTr as $trID => $jenisTr) {
                        //                        $jenisTr = $accountBalanceLocker['label'];
                        if (!isset($additionalInOut[1][$jenisTr][$opPosition])) {
                            $additionalInOut[1][$jenisTr][$opPosition] = 0;
                        }
                        if (!isset($additionalInOut[1][$jenisTr]["qty_" . $opPosition])) {
                            $additionalInOut[1][$jenisTr]["qty_" . $opPosition] = 0;
                        }
                        $additionalInOut[1][$jenisTr][$opPosition] += $trIDLocker[$trID]['nilai'];
                        $additionalInOut[1][$jenisTr]["qty_" . $opPosition] += $trIDLocker[$trID]['jumlah'];
                        $arrMovedDetail["out"][$jenisTr] = $opPosition;
                    }
                }
                //                cekHere("cetak trID locker");
                //                arrPrintWebs($trIDLocker);
                //                cekHere("cetak jenisTr");
                //                arrPrintWebs($arrJenisTr);
                //                cekHere("cetak additional in out");
                //                arrPrintWebs($additionalInOut);
            }
            //endregion
            //-------------------------------------------------
            //region rekening cache
            $mdlLockerRek = $accountBalanceLocker['mdlNameRekeningCache'];
            $mdlFilter = $accountBalanceLocker['filter'];
            $this->load->model("Coms/" . $mdlLockerRek);
            $lr = new $mdlLockerRek();
            $lr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $lr->addFilter("extern_id='" . $externID . "'");
            if (sizeof($mdlFilter) > 0) {
                foreach ($mdlFilter as $f) {
                    $lr->addFilter($f);
                }
            }
            $lrTmp = $lr->lookupAll()->result();
            $position = $defPosition;
            $qty_position = "qty_" . $defPosition;
            $finalLocker[$defPosition . "_awal"] = (sizeof($lrTmp) > 0) ? $lrTmp[0]->$defPosition : 0;
            $finalLocker[$qty_position . "_awal"] = (sizeof($lrTmp) > 0) ? $lrTmp[0]->$qty_position : 0;
            //endregion

        }
        //        cekHere("cetak finalLocker");
        //        arrPrintWebs($finalLocker);
        //endregion
        //arrPrintPink($balConfig);

        $mdlName = "Com" . $relName;
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();
        $com->addFilter("cabang_id='$cabangID'");
        //        $com->addFilter("jenis<>''");
        if ($getExtern2ID != NULL) {
            $com->addFilter("extern2_id='$getExtern2ID'");
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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //        showLast_query("biru");
        //arrPrintWebs($tmp);

        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();

            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $extern_nama = "";
        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $extern_nama = $temX->extern_nama;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                else {
                    // $arrMovedDetail["out"][$temX->jenis] = $position;
                    if ($rekName == '7010150' && $temX->jenis == "582spd") {
                        //belum ketemu yamg bikin jadi out jadi diunset manual dulu, nanti kalau sudah ketemu , pakai satu saja yang else
                    }
                    else {
                        $arrMovedDetail["out"][$temX->jenis] = $position;
                    }
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $defaultPositionRek = detectRekDefaultPosition($rekName);
                switch ($defaultPositionRek) {
                    case "debet":
                        $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                        $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                        break;
                    case "kredit":
                        $saldo_qty_berjalan += ($temX->qty_kredit - $temX->qty_debet);
                        $saldo_value_berjalan += ($temX->kredit - $temX->debet);
                        break;
                    default:
                        $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                        $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                        break;
                }
                //                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                //                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion


        if ((isset($additionalInOut)) && (sizeof($additionalInOut) > 0)) {
            foreach ($additionalInOut as $ii => $iiSpec) {
                $arrProds[] = $iiSpec;
            }
        }
        //        cekHere("cetak group in out, setelah diinject additional in out");


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        //        cekLime($this->db->last_query());


        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit",
                "debet",
                "qty_kredit",
                "qty_debet",
                "qty_kredit_akhir",
                "qty_debet_akhir",
                "kredit_akhir",
                "debet_akhir",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields["ids_his"] = "reference number";
        $headerFields["transaksi_no"] = "receipt number";
        $headerFields["referenceNomer"] = "cancelled number";
        $headerFields["description_main_followup"] = "vendor's number referral";
        $headerFields["review_details"] = "&nbsp;";


        $jenisAliases = arrCodeAliasing($cabangID);


        // region builder data transaksi yg perlu muncul
        $arrDecode = array("ids_his", "counters");
        $trDatas = array();
        $regDatas = array();
        $arrTrIDs = array();
        $arrDataTr = array(
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "oleh_id",
            "oleh_nama",
            "jenis_master",
        );
        foreach ($tmpTrs as $tmpTr) {

            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                //                $$hField = array_key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {

                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];

                    }
                    else {
                        if (isset($tmpTr->$hField) && !is_numeric($tmpTr->$hField)) {
                            //                            if ($tmpTr->$hField === base64_encode(base64_decode($tmpTr->$hField))) {
                            if (in_array($hField, $arrDecode)) {
                                //                                cekHitam("masuk disini, unBlob " . $tmpTr->$hField);
                                //                                $datas[$hField] = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : "";
                                $datas[$hField] = isset($tmpTr->$hField) ? blobDecode($tmpTr->$hField) : "";

                            }
                            else {
                                $datas[$hField] = $tmpTr->$hField;
                            }

                        }
                        else {
                            $datas[$hField] = $tmpTr->$hField;
                        }
                    }
                }
            }
            foreach ($arrDataTr as $kolomTr) {
                $datas[$kolomTr] = isset($tmpTr->$kolomTr) ? $tmpTr->$kolomTr : "";
            }
            $trDatas[$tmpTr->id] = $datas;
            $arrTrIDs[] = $tmpTr->id;
        }
        if (sizeof($arrTrIDs) > 0) {
            $trk = New MdlTransaksi();
            $trk->setFilters(array());
            $fields = array("main");
            $fieldString = implode(",", $fields);
            //            cekHere($fieldString);
            $trk->setJointSelectFields($fieldString . ",transaksi_id");
            //            $trk->addFilter("param='main'");
            $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $trkTmp = $trk->lookupDataRegistries()->result();
            //            showLast_query("kuning");
            if (sizeof($trkTmp) > 0) {
                foreach ($trkTmp as $trkSpec) {
                    //                    arrPrintPink($trkSpec);
                    foreach ($trkSpec as $key_reg => $val_reg) {
                        if (in_array($key_reg, $fields)) {
                            $regTrID = $trkSpec->transaksi_id;
                            $param = $key_reg;
                            $regData = blobDecode($val_reg);

                            if (sizeof($regData) > 0) {
                                foreach ($headerFields as $key => $val) {
                                    if (isset($regData[$key])) {
                                        $regDatas[$regTrID][$key] = $regData[$key];
                                    }
                                }
                                //                        $regDatas[$regTrID]['description_main_followup'] = isset($regData['description_main_followup']) ? $regData['description_main_followup'] : "";
                            }
                        }
                    }
                }
            }
        }
        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty"                        => "in qty",
            "out_qty"                       => "out qty",

            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (QTY)",
        );

        switch ($rekName) {
            case "2010050":
                $headerValueFields = array(
                    $defPosition . "_awal"  => "prev (IDR)",
                    "out"                   => "DEBET (IDR)",
                    "in"                    => "KREDIT (IDR)",
                    "saldo_berjalan"        => "nett (IDR)",
                    $defPosition . "_akhir" => "last (IDR)",
                );
                break;
            case "7010150":
                $headerValueFields = array(
                    $defPosition . "_awal"  => "prev (IDR)",
                    "out"                   => "DEBET (IDR)",
                    "in"                    => "KREDIT (IDR)",
                    "saldo_berjalan"        => "nett (IDR)",
                    $defPosition . "_akhir" => "last (IDR)",
                );
                break;
            default:
                $headerValueFields = array(
                    $defPosition . "_awal"  => "prev (IDR)",
                    "in"                    => "in (IDR)",
                    "out"                   => "out (IDR)",
                    "saldo_berjalan"        => "nett (IDR)",
                    $defPosition . "_akhir" => "last (IDR)",
                );
                break;
        }

        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }
        //arrPrint($headerFields);

        //region buld yIndex
        //        arrPrint($arrMovedDetail);
        $itemsCek = array();
        $items0 = array();
        //        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = isset($tempLabels["label_biaya"]) ? $tempLabels["label_biaya"] : $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = "";
        // data transaksi dab
        $subItemsAdditional = array();
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);

            foreach ($tmp as $row) {

                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }

                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                //                arrPrintWebs($row);
                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }

                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                            //                            if (in_array($key, $arrDecode)) {
                            ////                                $subs_r[$key] = unserialize(base64_decode($row->$key));
                            //                                $subs_r[$key] = blobDecode($row->$key);
                            //
                            //                            }
                            //                            else {
                            //                                $subs_r[$key] = $row->$key;
                            //                            }
                            $subs_r[$key] = $row->$key;
                        }
                        else {
                            $subs_r[$key] = $row->$key;
                        }
                    }
                }

                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;

                $subs_r['transaksi_id'] = $row->transaksi_id;
                $subs_r['review_details'] = $row->transaksi_no;
                //                $subs_r['review_details'] = $row->transaksi_id;

                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs_r['modul'] = $modul;
                $subs_r['modul_path'] = base_url() . $modul . "/";
                $subs_r['jenis_master'] = $row->jenis_master;


                $items[] = $subs_r;
                //arrPrint($subs_r);

                $title = isset($row->extern_nama) ? $row->extern_nama : "";
            }

            // ADDITIONAL ROW DARI LOCKER----------
            foreach ($headerFields as $key => $label) {
                $subItemsAdditional[$key] = isset($finalLocker[$key]) ? $finalLocker[$key] : "";
            }
            $items[] = $subItemsAdditional;
            //-------------------------------------

        }

        //arrPrintPink($items);
        $link_excel = "";
        // $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID";

        $data = array(
            "mode"     => "mutasiDetails",
            "title"    => $rekName_f . " $extern_nama &nbsp;" . $titleTop . "",
            "subTitle" => " $blob_ext $subTitle_date $subSubTitle_date",
            "items"    => $items,
            "items2"   => $items0,

            //----tambahan locker di bawah---------------
            //            "items_additional" => isset($itemsAdditional) ? $itemsAdditional : array(),
            //            "items2_additional" => isset($items0_additional) ? $items0_additional : array(),
            //-------------------------------------------

            "headerFields"  => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "link_excel"    => $link_excel,
            "itemsCek"      => $itemsCek,
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );

        $this->load->view("ledger", $data);

    }

    public function viewMoveDetails()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $rekName_extern_1 = $this->uri->segment(5) != NULL ? urldecode($this->uri->segment(5)) : NULL;
        $externID = $this->uri->segment(6) != NULL ? $this->uri->segment(6) : NULL;
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        // $tokoID = (isset($_GET['to']) && $_GET['to'] <> 0) ? $_GET['to'] : $this->session->login['toko_id'];
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $gudangID = (isset($_GET['w']) && $_GET['w'] <> 0) ? $_GET['w'] : $this->session->login['gudang_id'];

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;

        $mdlName = "Com" . $relName;
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();
        // $com->addFilter("toko_id='$tokoID'");

        $com->addFilter("cabang_id='$cabangID'");
        $com->addFilter("jenis<>''");
        if ($getExtern2ID != NULL) {
            $com->addFilter("extern2_id='$getExtern2ID'");
        }

        if (isset($_GET['w'])) {
            $com->addFilter("gudang_id='$gudangID'");
        }
        else {
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
        }


        //        $com->addFilter("gudang_id='9'");

        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }

        //        arrPrint($this->uri->segment_array());
        //        cekMErah($mdlName . " ::: " . $rekName . " ::: $rekName_extern_1 ::: " . $externID . " ");
        //        $tmp = $com->fetchMoves($rekName, $externID);
        $tmp = $com->fetchMoves($rekName, $rekName_extern_1, $externID);
        showLast_query("biru");

        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();

            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            // arrPrint($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                // cekBiru($detectRek);
                if ($detectRek > 0) {
                    $keyPosition = "in";
                    $keyPositionQty = "in_qty";
                    //                    cekHitam($detectRek);
                    // $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        // $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $qty_position = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        // cekPink($detectRek);
                        $qtyVal = 0;
                    }
                }
                else {
                    //                    cekMerah($detectRek);
                    $keyPosition = "out";
                    $keyPositionQty = "out_qty";
                    // $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        // $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $qty_position = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                $arrMovedDetail[$keyPosition][$temX->jenis] = array(
                    "$keyPosition"    => $position,
                    "$keyPositionQty" => $qty_position,

                );
                $arrProds[][$keyPosition][$temX->jenis] = array(
                    "qty_" . $position => $qtyVal,
                    "$position"        => "$val",

                );
                // arrprint($arrProds);
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        // $tr->addFilter("toko_id='$tokoID'");
        $tmpTrs = $tr->lookupAll()->result();
        //        cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit",
                "debet",
                "qty_kredit",
                "qty_debet",
                "qty_kredit_akhir",
                "qty_debet_akhir",
                "kredit_akhir",
                "debet_akhir",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }


        // $headerFields["ids_his"] = "reference number";
        //        $headerFields["nomers_prev"] = "referensi nota";
        $headerFields["transaksi_no"] = "nomer nota";
        // $headerFields["review_details"] = "&nbsp;";
        $headerFields["harga_avg"] = "hpp satuan";


        $jenisAliases = arrCodeAliasing($cabangID);


        // region builder data transaksi yg perlu muncul
        $arrDecode = array("ids_his", "counters");
        $trDatas = array();
        $regDatas = array();
        $arrTrIDs = array();
        $arrDataTr = array(
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "oleh_id",
            "oleh_nama",
            "jenis_master",
        );
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                //                $$hField = array_key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];
                    }
                    else {
                        if (isset($tmpTr->$hField) && !is_numeric($tmpTr->$hField)) {
                            if ($tmpTr->$hField === base64_encode(base64_decode($tmpTr->$hField))) {
                                $datas[$hField] = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : "";
                                //                                $rslt = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : NULL;
                                //                                if(strlen($tmpTr->$hField) > 50){
                                //                                    $datas[$hField] = $rslt;
                                //                                }
                                //                                else{
                                //                                    $datas[$hField] = $tmpTr->$hField;
                                //                                }
                            }
                            else {
                                $datas[$hField] = $tmpTr->$hField;
                            }

                        }
                        else {
                            $datas[$hField] = $tmpTr->$hField;
                            //                            cekPink("$hField diambil dari tmpTr $hField");

                        }
                    }
                }
            }
            foreach ($arrDataTr as $kolomTr) {
                $datas[$kolomTr] = isset($tmpTr->$kolomTr) ? $tmpTr->$kolomTr : "";
            }
            $trDatas[$tmpTr->id] = $datas;
            $arrTrIDs[] = $tmpTr->id;
        }

        if (sizeof($arrTrIDs) > 0) {

            $trk = New MdlTransaksi();
            $trk->setFilters(array());
            //            $trk->addFilter("param='main'");
            $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $trkTmp = $trk->lookupDataRegistries()->result();
            showLast_query("kuning");
            //            arrPrint($arrTrIDs);
            if (sizeof($trkTmp) > 0) {
                //                cekhitam(count($arrTrIDs));
                foreach ($trkTmp as $trkSpec) {
                    $regTrID = $trkSpec->transaksi_id;
                    foreach ($trkSpec as $key_reg => $val_reg) {
                        if ($key_reg == "main") {

                            $regTrID = $trkSpec->transaksi_id;
                            $param = $key_reg;
                            $regData = blobDecode($val_reg);

                            if (sizeof($regData) > 0) {
                                foreach ($headerFields as $key => $val) {
                                    if (isset($regData[$key])) {
                                        $regDatas[$regTrID][$key] = $regData[$key];
                                    }
                                }
                            }
                        }

                    }
                }
            }
        }

        // endregion builder data transaksi yg perlu muncul
        // arrprintWebs($regData);
        $headerPrev = array(
            "prev" => "saldo awal"
            // "qty_" . $defPosition . "_awal" => "prev (QTY)",
            // $defPosition . "_awal" => "prev (IDR)",
        );
        $headermove2 = array(
            "qty"     => array(
                "in"  => array(
                    "in_qty" => "qty",
                ),
                "out" => array(
                    "out_qty" => "qty",
                ),
            ),
            "value"   => array(
                "in"  => array(
                    "in" => "IDR",
                ),
                "out" => array(
                    "out" => "IDR",
                ),
            ),
            "balance" => array(
                "qty_" . $defPosition . "_akhir" => "balance (QTY)",
                $defPosition . "_akhir"          => "balance (IDR)",
            ),
        );
        $arrMovedDetailSrc2 = array(
            "qty"   => array(
                "prev"    => array(
                    "qty_" . $defPosition . "_awal" => "QTY"
                ),
                "balance" => array(
                    "qty_" . $defPosition . "_akhir" => "QTY"
                ),
            ),
            "value" => array(
                "prev"    => array(
                    $defPosition . "_awal" => "IDR"
                ),
                "balance" => array(
                    $defPosition . "_akhir" => "IDR"
                ),
            ),
        );
        // $arrMovedDetail2 = array(
        //     "prev"    => array(
        //         "qty_" . $defPosition . "_awal" => "QTY",
        //         $defPosition . "_awal"          => "IDR",
        //     ),
        //     "balance" => array(
        //         "qty_" . $defPosition . "_akhir" => "QTY",
        //         $defPosition . "_akhir"          => "IDR",
        //     ),
        //
        // );
        $headerMoveMaster = array(
            "in"  => "masuk",
            "out" => "keluar",
        );
        $headerBalance = array(
            "balance" => "saldo akhir",


        );
        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal"  => "prev (QTY)",
            "in_qty"                         => "in qty",
            "out_qty"                        => "out qty",
            "qty_" . $defPosition . "_akhir" => "balance (QTY)**",
        );
        $headerValueFields = array(
            $defPosition . "_awal"  => "prev (IDR)",
            "in"                    => "in (IDR)",
            "out"                   => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)**",
        );
        $arrMovedSubDetail = array();
        $arrMovedDetail2 = array();
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            foreach ($headermove2["qty"] as $keSub => $detilH) {
                foreach ($detilH as $xx => $yy) {
                    $arrMovedSubDetail[$keSub][$xx] = $yy;
                }

            }
            foreach ($arrMovedDetailSrc2["qty"] as $paramKey => $paramData) {
                foreach ($paramData as $y => $v) {
                    $arrMovedDetail2[$paramKey][$y] = $v;
                }

            }

        }

        //         matiHere();
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            foreach ($headermove2["value"] as $keSub1 => $detilH2) {
                foreach ($detilH2 as $xx1 => $yy1) {
                    $arrMovedSubDetail[$keSub1][$xx1] = $yy1;
                }

            }
            foreach ($arrMovedDetailSrc2["value"] as $paramValue => $paramDataValue) {
                foreach ($paramDataValue as $yv => $vv) {
                    $arrMovedDetail2[$paramValue][$yv] = $vv;
                }
            }
        }
        // arrPrint($arrMovedDetail2);
        $headerFields = $headerFields + $headerPrev + $headerMoveMaster + $headerBalance;

        //region buld yIndex
        //        arrPrintWebs($arrMovedSubDetail);
        //        arrPrint($arrProds);
        $items0 = array();
        $itemsCek = array();
        //        krsort($arrProds);
        // arrPrint($arrProds);
        // arrPrintWebs($arrMovedDetail);
        // matiHere();
        foreach ($arrProds as $rowKey => $row0) {
            foreach ($row0 as $mKey => $mData) {
                $temX = array();
                // arrPrint($mData);
                // cekHitam($mKey);
                // arrprint($arrMovedDetail[$mKey]);
                // matiHere();
                $subs0 = array();
                $subItemCek0 = array();
                foreach ($arrMovedDetail[$mKey] as $yparent => $ySpec) {
                    foreach ($ySpec as $jn => $colloumb) {
                        if (isset($mData[$yparent][$colloumb])) {
                            $val = $mData[$yparent][$colloumb];
                            // cekMErah($rowKey."|".$yparent."|$jn|".$val);
                            $subs0[$yparent][$jn] = $val;
                            $subItemCek[$yparent] = $val;
                        }
                        // else {
                        //     $val = 0;
                        // }

                        // $subs0[$jn] = $val;
                    }

                    $temX[$mKey] = $subs0;
                    $subItemCek0[$mKey] = $subItemCek;
                }
                // arrPrint($mData);
                // arrPrint($temX);
                // matiHere($yparent);
                $items2[] = $temX;
                $itemsCek[] = $subItemCek0;
                // arrprint($temX);
                // matiHere();
                // $tempData[]
            }
            // $temX = array();
            // foreach ($arrMovedDetail as $yparent => $ySpec) {
            //     $subs0 = array();
            //     foreach ($ySpec as $jn => $colloumb) {
            //
            //         if (isset($row0[$jn][$colloumb])) {
            //             $val = $row0[$jn][$colloumb];
            //         }
            //         else {
            //             $val = 0;
            //         }
            //         $subs0[$jn][$colloumb] = $val;
            //         // $subs0[$jn] = $val;
            //     }
            //     $temX[$yparent] = $subs0;
            // }
            // $items0[] = $temX;
        }
        //endregion
        // arrPrint($items0);
        // matiHEre();


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        // arrPrint($arrMovedDetail);
        $title = "";
        // data transaksi dab
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            // arrPrint($tmp);
            foreach ($tmp as $row) {
                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }

                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }
                // arrPrint($row);

                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }

                //                $subs = array();
                //                foreach ($headerFields as $key => $label) {
                //                    if (array_key_exists($key, $row)) {
                //                        $subs[$key] = array_key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;
                //                    }
                //                }
                //                $subs_r = array_replace($subs, isset($trDatas[$row->transaksi_id]) ? $trDatas[$row->transaksi_id] : array());
                // arrPrint($row);
                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            // cekHitam($row->$key);
                            if ($row->$key === base64_encode(base64_decode($row->$key))) {

                                $subs_r[$key] = unserialize(base64_decode($row->$key));

                            }
                            else {
                                $subs_r[$key] = $row->$key;
                            }
                        }
                        else {
                            $subs_r[$key] = $row->$key;
                        }
                    }
                    if (($key == "jenis") && (isset($jenisAliases[$row->$key]))) {
                        $subs_r[$key] = $jenisAliases[$row->$key];
                    }
                }
                foreach ($arrMovedDetail2 as $key1 => $srcBalanceData) {
                    foreach ($srcBalanceData as $srcBkey => $srcBLabel) {
                        if ((isset($row->$srcBkey)) && (!is_numeric($row->$srcBkey))) {
                            // cekHitam($row->$key);
                            if ($row->$srcBkey === base64_encode(base64_decode($row->$srcBkey))) {

                                $subs_r[$srcBkey] = unserialize(base64_decode($row->$srcBkey));

                            }
                            else {
                                $subs_r[$srcBkey] = $row->$srcBkey;
                            }
                        }
                        else {
                            $subs_r[$srcBkey] = $row->$srcBkey;
                        }
                    }
                }


                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;
                $subs_r['transaksi_id'] = $row->transaksi_id;
                $subs_r['review_details'] = $row->transaksi_id;
                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs_r['modul'] = $modul;
                $subs_r['modul_path'] = base_url() . $modul . "/";
                $subs_r['jenis_master'] = $row->jenis_master;
                $items[] = $subs_r;
                //arrPrint($subs_r);

                $title = $row->extern_nama;
            }
        }
        //arrPrint($items);
        $link_excel = "";
        // $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID";

        // arrprint($headerFields);
        // arrprint($arrMovedSubDetail);
        //        arrPrint($this->rekAlias);
        $rekNameAlias = isset($this->rekAlias[$rekName]) ? $this->rekAlias[$rekName] : $rekName;
        //        arrPrintKuning($_GET);
        //        cekHere(http_build_query($_GET));
        $geturl = "&" . http_build_query($_GET);
        $data = array(
            "mode"          => "viewMoveDetails",
            "title"         => $rekNameAlias . " &nbsp;" . $titleTop,
            "subTitle"      => " $blob_ext $subTitle_date $subSubTitle_date ",
            "items"         => $items,
            "items2"        => $items2,
            "headerFields"  => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "headerFields3" => $arrMovedSubDetail,
            "headerFields4" => $arrMovedDetail2,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?o=$cabangID",
            "link_excel"    => $link_excel,
            "itemsCek"      => $itemsCek,
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
            "geturl"        => isset($geturl) ? $geturl : "",
        );

        $this->load->view("ledger", $data);

    }

    // mutasi dengan qty, @price, total value...
    public function viewMoveDetails_v1_OLD()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        //cekHere(":: $cabangID ::");

        //arrPrint($this->session->login);
        $membership = $this->session->login['membership'];
        //arrPrintPink($membership);

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $headLooping = isset($this->config->item('accountMoveColumns')[$relName]['headerLooping']) ? $this->config->item('accountMoveColumns')[$relName]['headerLooping'] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();

        $com->addFilter("cabang_id='$cabangID'");

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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekHere($this->db->last_query() . "<hr> " . __METHOD__);
        //arrPrint($tmp);
        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();
            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                //            cekHere($temX->jenis);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }

                $arrProds[$temX->transaksi_id][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                    "harga"            => $temX->harga,
                );

                $tmpTrIds[$transaksi_id] = 1;
            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);
        $refDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $refDatas[$tmpTr->id]["nomer_top"] = $tmpTr->nomer_top;
            $refDatas[$tmpTr->id]["oleh_nama"] = $tmpTr->oleh_nama;
            $refDatas[$tmpTr->id]["cabang_nama"] = $tmpTr->cabang_nama;
            $refDatas[$tmpTr->id]["customers_nama"] = $tmpTr->customers_nama;
            $refDatas[$tmpTr->id]["suppliers_nama"] = $tmpTr->suppliers_nama;
        }
        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime"          => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis"          => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top"      => array(
                    "label"      => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    "format"     => "formatField_he_format",
                ),
                "transaksi_no"   => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    //                    "format" => "formatField_he_format",
                ),
                "suppliers_nama" => array(
                    "label" => "vendor",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "cabang_nama"    => array(
                    "label" => "branch",

                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "oleh_nama"      => array(
                    "label" => "pic by",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
            );
            $total_colspan = sizeof($headerFields);
        }
        else {
            $headerFields = array(
                "dtime"          => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis"          => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top"      => array(
                    "label"      => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "format"     => "formatField_he_format",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "transaksi_no"   => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    //                    "format" => "formatField_he_format",
                ),
                // "cabang_id"    => "branch",
                "oleh_nama"      => array(
                    "label"      => "pic by",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "customers_nama" => array(
                    "label"      => "customer",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                // "debet" => "values",
                // "kredit" => "values",
            );
            $total_colspan = sizeof($headerFields);
        }

        $jenisAliases = arrCodeAliasing($cabangID);
        $jmlLooping = sizeof($headLooping);

        // region penyusun / pemecah arai jadi array kecil-kecil
        foreach ($headerFields as $field => $fChilds) {
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
        // endregion penyusun 


        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($fieldToshows as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";

                if (array_key_exists($hField, $tmpTr)) {
                    $datas[$hField] = key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                }
            }
            $modul = isset($this->masterConfigUi[$tmpTr->jenis_master]["modul"]) ? $this->masterConfigUi[$tmpTr->jenis_master]["modul"] : false;
            $datas['modul'] = $modul;
            $datas['modul_path'] = base_url() . $modul . "/";
            $datas['jenis_master'] = $tmpTr->jenis_master;
            $trDatas[$tmpTr->id] = $datas;
        }
        //                arrPrint($trDatas);
        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(

            "qty_a" => array(
                "label" => "saldo awal",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),

            "in_qty"                         => array(
                "label" => "in",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            "out_qty"                        => array(
                "label" => "out",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            "qty_" . $defPosition . "_akhir" => array(
                "label" => "saldo",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            // "avg_price" => array(
            //     "label" => "avg price",
            //     "attr" => "class='text-center valign-m' rowspan='2'",
            // ),


        );
        $headerValueFields = array(
            $defPosition . "_awal"  => "prev (IDRs)",
            "in"                    => "in (IDR)",
            "out"                   => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        //region buld yIndex
        //        arrPrint($arrMovedDetail);
        //        arrPrint($arrProds);
        $itemsCek = array();
        $items0 = array();
        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //        krsort($items0);
        //        arrPrint($items0);
        //        arrPrint($itemsCek);
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        //        arrPrint($childHeaderLabels);
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            //            arrPrint($xxTemp);

            foreach ($xxTemp as $tempLabels) {

                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];

                $detailsLabel[$keyLabel] = $keyValue;
            }
        }

        //        arrPrint($detailsLabel);
        //endregion


        $arrCodeAliasing = arrCodeAliasing($cabangID);

        $title = "";
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            $mainDatas2 = array();
            foreach ($tmp as $row) {

                $subs = array();
                foreach ($fieldToshows as $key => $label) {

                    $refDatas_2 = $refDatas[$row->transaksi_id];
                    //arrPrint($refDatas_2);

                    if (array_key_exists($key, $row)) {
                        $subs[$key] = array_key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;

                    }
                    else {
                        //                        cekUngu("-- $key -- " . $row->$key);
                    }

                    if ($key == 'jenis') {
                        $mValue = $arrCodeAliasing[$row->$key];
                    }
                    else {
                        $mValue = isset($row->$key) ? $row->$key : 0;
                    }


                    $mainDatas[$key] = isset($row->$key) ? $mValue : $refDatas_2[$key];
                }

                //                $subs_r = array_replace($subs, isset($trDatas[$row->transaksi_id]) ? $trDatas[$row->transaksi_id] : array());

                $jenis = $row->jenis;
                $saldo = $row->qty_debet_akhir * $row->harga_avg;
                //                cekHitam($jenis);
                //                arrPrintWebs($row);
                // cekLime($row->qty_debet_awal);

                switch ($jenis) {

                    // in
                    // case "9999":
                    // case "19855":
                    //
                    //     $mainDatas2["qty_debet"] = $row->qty_debet;
                    //     $mainDatas2["harga_debet"] = $row->harga;
                    //     $mainDatas2["debet"] = $row->debet;
                    //
                    //     $mainDatas2["qty_kredit"] = 0;
                    //     $mainDatas2["harga_kredit"] = 0;
                    //     $mainDatas2["kredit"] = 0;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;
                    //
                    // // ot
                    // case "5855":
                    // case "3344":
                    // case "382spd":
                    //     $mainDatas2["qty_debet"] = 0;
                    //     $mainDatas2["harga_debet"] = 0;
                    //     $mainDatas2["debet"] = 0;
                    //
                    //     $mainDatas2["qty_kredit"] = $row->qty_kredit;
                    //     $mainDatas2["harga_kredit"] = $row->harga;
                    //     $mainDatas2["kredit"] = $row->kredit;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;

                    // saldo
                    default:
                        $mainDatas2["qty_debet_awal"] = $row->qty_debet_awal;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_debet_awal"] = $row->qty_debet_awal > 0 ? $row->harga_awal : 0;
                            $mainDatas2["debet_awal"] = $row->debet_awal;
                        }
                        else {
                            //                            $mainDatas2["harga_debet_awal"] = "no access authority";
                            //                            $mainDatas2["debet_awal"] = "no access authority";
                            $mainDatas2["harga_debet_awal"] = 0;
                            $mainDatas2["debet_awal"] = 0;
                        }

                        $mainDatas2["qty_debet"] = $row->qty_debet;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_debet"] = $row->qty_debet > 0 ? $row->harga : 0;
                            $mainDatas2["debet"] = $row->debet;
                        }
                        else {
                            $mainDatas2["harga_debet"] = 0;
                            $mainDatas2["debet"] = 0;
                        }

                        $mainDatas2["qty_kredit"] = $row->qty_kredit;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_kredit"] = $row->qty_kredit > 0 ? $row->harga : 0;
                            $mainDatas2["kredit"] = $row->kredit;
                        }
                        else {
                            $mainDatas2["harga_kredit"] = 0;
                            $mainDatas2["kredit"] = 0;
                        }

                        $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_saldo"] = $row->qty_debet_akhir > 0 ? $row->harga : 0;
                            $mainDatas2["saldo"] = $saldo;
                        }
                        else {
                            $mainDatas2["harga_saldo"] = 0;
                            $mainDatas2["saldo"] = 0;
                        }
                        // $mainDatas2["harga_avg"] = 0;
                        break;
                }

                $transaksi_id = $row->transaksi_id;
                //                arrPrintWebs($trDatas[$transaksi_id]);

                $mainDatas2['modul'] = isset($trDatas[$transaksi_id]['modul']) ? $trDatas[$transaksi_id]['modul'] : "";
                $mainDatas2['modul_path'] = isset($trDatas[$transaksi_id]['modul_path']) ? $trDatas[$transaksi_id]['modul_path'] : "";
                $mainDatas2['jenis_master'] = isset($trDatas[$transaksi_id]['jenis_master']) ? $trDatas[$transaksi_id]['jenis_master'] : "";
                $items[] = $mainDatas + $mainDatas2;

                $title = $row->extern_nama;
            }
        }
        //arrPrintPink($items);

        //region prepare params to viewer
        $propertyFields = array(
            "qty_debet_awal"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet_awal" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => false,
            ),
            "debet_awal"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_debet"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "debet"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_kredit"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_kredit" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "kredit"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_saldo"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),
            "harga_saldo" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),
            "saldo"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),

            "harga_avg"    => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "transaksi_no" => array(
                "format" => "formatField_he_format",
                // "attr" => "class='text-right'",
            ),
            "dtime"        => array(
                "format" => "formatField_he_format",
                // "attr" => "class='text-right'",
            ),
        );
        $summaryKey = array(
            //            "qty_debet_awal",
            //            "debet_awal",
            "qty_debet",
            "debet",
            "qty_kredit",
            "kredit",
        );

        $data = array(
            "mode"           => "mutasiDetails_v1",
            "title"          => $titleTop,
            "subTitle"       => "&nbsp; $subTitle_date $subSubTitle_date",
            "items"          => $items,
            "items2"         => $items0,
            "fieldToshows"   => $fieldToshows,
            "propertyFields" => $propertyFields,
            "headerFields"   => $headerFields,
            // "headerFields2" => $arrMovedDetail,
            "headerFields2"  => $headLooping,
            "total_colspan"  => $total_colspan,


            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",

            "itemsCek"   => $itemsCek,
            "summaryKey" => $summaryKey,
            "trDatas"    => isset($trDatas) ? $trDatas : array(),
            "notToShow"  => array(
                "modul", "modul_path", "jenis_master"
            ),
        );
        //endregion


        $this->load->view("ledger", $data);

    }

    /* -------------------------
     * MUTASI
     * ---------------------*/
    public function viewMoveDetails_1()
    {

        /*README PLEASE
         * header digeser, untuk angka/nominal digeser ke kiri by reguest dari meeting tanggal 2 november 2022
         * label header ppv biaya di replace di viewLeger untuk lagel accountPayable unuk jadi nama biaya
         */

        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getPeriode = (isset($_GET['periode'])) ? $_GET['periode'] : null;
        $getDate = (isset($_GET['date'])) ? $_GET['date'] : null;
        $getDisabled = (isset($_GET['disabled'])) ? $_GET['disabled'] : null;
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;background-color:yellow;") : array();


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        //        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = fetchAccountStructureAlias();

        $linkKolomConfig = isset($this->config->item('accountMoveColumns')[$relName]['linkToDetail']) ? $this->config->item('accountMoveColumns')[$relName]['linkToDetail'] : array();
        $linkDetailConfig = isset($this->config->item('accountMoveColumns')[$relName]['baselink']) ? $this->config->item('accountMoveColumns')[$relName]['baselink'] : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");

        $tr = new MdlTransaksi();
        $com = new $mdlName();
        $com->addFilter("cabang_id='$cabangID'");
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

        if ($getPeriode != null) {
            switch ($getPeriode) {
                //                case "harian":
                //                    $date1 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-01");
                //                    $date2 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                //                    break;
                case "bulanan":
                    $date1 = isset($getDate) ? $getDate . "-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate . "-31" : date("Y-m-d");
                    break;
                case "tahunan":
                    $getDate_ex = explode("-", $getDate);

                    $date1 = isset($getDate) ? $getDate_ex[0] . "-01-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate_ex[0] . "-12-31" : date("Y-m-d");
                    break;
                case "forever":
                    $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                    $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                    break;

            }
            $this->db->order_by("id", "DESC");
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        else {
            if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
                $limit = 20;
                $this->db->limit("$limit");
                $this->db->order_by("id", "DESC");
                //                $this->db->order_by("dtime", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

                $subTitle_date = "";
                $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
            }
            else {
                $this->db->order_by("id", "DESC");
                //                $this->db->order_by("dtime", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");

                $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
                $subSubTitle_date = "";
            }
        }

        $tmp = $com->fetchMoves($rekName, $externID);
        //        showLast_query("biru");

        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $tmpTrIds = array();
        $saldoBerjalan = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $transaksi_id = $temX->transaksi_id;
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                //                cekHere("::: $position :::");
                $defaultPositionRek = detectRekDefaultPosition($rekName);
                switch ($defaultPositionRek) {
                    case "debet":
                        $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                        $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                        break;
                    case "kredit":
                        $saldo_qty_berjalan += ($temX->qty_kredit - $temX->qty_debet);
                        $saldo_value_berjalan += ($temX->kredit - $temX->debet);
                        break;
                    default:
                        $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                        $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                        break;
                }


                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
        }
        //endregion

        // region ambil jurnal

        $contra_account = array();
        if (sizeof($tmpTrIds) > 0) {
            $ju = New ComJurnal();
            $ju->addFilter("cabang_id='$cabangID'");
            $ju->addFilter("transaksi_id in ('" . implode("','", $tmpTrIds) . "')");
            $juTmp = $ju->lookupAll()->result();
            if (sizeof($juTmp) > 0) {
                foreach ($juTmp as $juSpec) {
                    //                    arrPrintWebs($juSpec);
                    if ($juSpec->rekening != $rekName) {
                        //                        $contra_account[$juSpec->transaksi_id][] = $juSpec->rekening;
                        $contra_account[$juSpec->transaksi_id][] = isset($accountAlias[$juSpec->rekening]) ? $accountAlias[$juSpec->rekening] : $juSpec->rekening;
                    }
                }
            }
        }
        //        arrPrintWebs($contra_account);
        // endregion ambil jurnal


        if (sizeof($balConfig)) {
            $headerFields_conf = $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit", "debet", "qty_kredit", "qty_debet", //"jenis",
                "dtime", "urut"
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }

        $headerFieldsStatic = array(
            "ids_his"      => "reference number",
            "transaksi_no" => "receipt number",
        );
        foreach ($headerFieldsStatic as $hs_key => $hs_val) {
            if (!array_key_exists($hs_key, $headerFieldsStatic)) {
                $headerFields[$hs_key] = $hs_val;
            }
        }

        // arrPrint($headerFields);
        $headerFields_2 = $headerFields;
        $headerFields = array();
        $headerOnly = array(
            "dtime", "urut"
        );
        $headerFields = array_intersect_key($headerFields_conf, array_flip($headerOnly));
        // cekHere();
        if (isset($balConfig['viewedColumnsAdditional'][$rekName]['kolom'])) {
            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['kolom'] as $kolom_nama => $kolom_label) {
                $headerFields[$kolom_nama] = $kolom_label;
            }
        }

        $headerFields["review_details"] = "&nbsp;";

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty"                        => "in qty",
            "out_qty"                       => "out qty",

            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (YTD)",
        );
        switch ($rekName) {
            case "7010150":// laba lain-lain
                $headerValueFields = array(
                    $defPosition . "_awal"  => "prev (IDR)",
                    "out"                   => "DEBET (IDR)",
                    "in"                    => "KREDIT (IDR)",
                    "saldo_berjalan"        => "nett (IDR)",
                    $defPosition . "_akhir" => "last (YTD)",
                );
                break;
            case "2010090010":// hutang lain ppv
                $headerValueFields = array(
                    $defPosition . "_awal"  => "prev (IDR)",
                    "out"                   => "DEBET (IDR)",
                    "in"                    => "KREDIT (IDR)",
                    "saldo_berjalan"        => "nett (IDR)",
                    $defPosition . "_akhir" => "last (YTD)",
                );
                break;
            default:
                $headerValueFields = array(
                    $defPosition . "_awal" => "prev (IDR)",
                    "in"                   => "in (IDR)",
                    "out"                  => "out (IDR)",

                    "saldo_berjalan" => "saldo (IDR)",

                    $defPosition . "_akhir" => "balance (YTD)",
                );
                break;
        }

        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }
        $regFieldBlacklist = array(
            "dtime",
        );

        //region buld yIndex
        $items0 = array();
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {
                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;


                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
        }
        //endregion
        $headerFields = $headerFields + $headerFields_2;
        // arrPrintHijau($headerFields);
        //region label detil transaksi
        $arrDecode = array("ids_his", "counters");
        $jenisAliases = arrCodeAliasing($cabangID);
        $arrTransData = array();
        $trDatasLink = array();
        $arrTrIDs = array();
        $regDatas = array();
        //        $arrDataTr = array(
        //            "customers_id",
        //            "customers_nama",
        //            "suppliers_id",
        //            "suppliers_nama",
        //            "oleh_id",
        //            "oleh_nama",
        //            "jenis_master",
        //        );
        $listId = implode("','", $tmpTrIds);
        if (strlen($listId) > 3) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . $listId . "')");
            $tmpTrs = $tr->lookupAll()->result();
            foreach ($tmpTrs as $tmpTrsSpec) {
                $arrTrIDs[] = $tmpTrsSpec->id;
                $arrTransData[$tmpTrsSpec->id] = array(
                    "jenis"          => array_key_exists($tmpTrsSpec->jenis, $jenisAliases) ? $jenisAliases[$tmpTrsSpec->jenis] : $tmpTrsSpec->jenis,
                    "customers_id"   => $tmpTrsSpec->customers_id,
                    "customers_nama" => $tmpTrsSpec->customers_nama,
                    "suppliers_id"   => $tmpTrsSpec->suppliers_id,
                    "suppliers_nama" => $tmpTrsSpec->suppliers_nama,
                    "oleh_id"        => $tmpTrsSpec->oleh_id,
                    "oleh_nama"      => $tmpTrsSpec->oleh_nama,
                    "cabang_id"      => $tmpTrsSpec->cabang_id,
                    "cabang_nama"    => $tmpTrsSpec->cabang_nama,
                    "ids_his"        => blobDecode($tmpTrsSpec->ids_his),
                    "jenis_master"   => $tmpTrsSpec->jenis_master,
                );
                foreach ($tmpTrsSpec as $key => $val) {
                    if (array_key_exists($key, $linkKolomConfig)) {
                        $comPembantu = isset($linkKolomConfig[$key]['rekening'][$rekName]['com']) ? $linkKolomConfig[$key]['rekening'][$rekName]['com'] : isset($accountChilds[$rekName]) ? $accountChilds[$rekName] : "";
                        $rekName_new = isset($linkKolomConfig[$key]['rekening'][$rekName]['rek']) ? $linkKolomConfig[$key]['rekening'][$rekName]['rek'] : $rekName;
                        $link = base_url() . "$linkDetailConfig" . $comPembantu . "/$rekName_new/" . $tmpTrsSpec->$linkKolomConfig[$key]['key'] . "?o=$cabangID&trID=" . $tmpTrsSpec->id;
                        $trDatasLink[$tmpTrsSpec->id][$key] = $link;
                    }
                }
            }
            if (sizeof($arrTrIDs) > 0) {
                $trk = New MdlTransaksi();
                $trk->setFilters(array());
                $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
                $trkTmp = $trk->lookupDataRegistries()->result();
                //            showLast_query("kuning");
                if (sizeof($trkTmp) > 0) {
                    foreach ($trkTmp as $trkSpec) {
                        //                    arrPrintWebs($trkSpec);
                        foreach ($trkSpec as $key_reg => $val_reg) {
                            if ($key_reg == "main") {

                                $regTrID = $trkSpec->transaksi_id;
                                $param = $key_reg;
                                $regData = blobDecode($val_reg);

                                if (sizeof($regData) > 0) {
                                    foreach ($headerFields as $key => $val) {
                                        if ((isset($regData[$key])) && (!in_array($key, $regFieldBlacklist))) {

                                            $regDatas[$regTrID][$key] = $regData[$key];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = isset($tempLabels["label_biaya"]) ? $tempLabels["label_biaya"] : $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName;
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);

            $viewedColumnsAdditionalData = array();

            if (isset($balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries']) && ($balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries'] != NULL)) {
                $regGate = $balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries'];

                $arrTrIDs = array();
                foreach ($tmp as $row) {
                    $arrTrIDs[] = $row->transaksi_id;
                }

                $this->load->model("MdlTransaksi");
                $trk = New MdlTransaksi();
                $registryFields = $trk->getFields()["dataRegistry"];
                // arrPrint($registryFields);
                $trk->setFilters(array());
                $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
                $trkTmp = $trk->lookUpDataRegistries()->result();
                if (sizeof($trkTmp) > 0) {
                    foreach ($trkTmp as $trkSpec) {
                        $regTrID = $trkSpec->transaksi_id;
                        // $param = $trkSpec->param;

                        foreach ($registryFields as $param) {
                            if ($param == "transaksi_id") {
                                $regData = $trkSpec->$param;
                            }
                            else {
                                // cekHitam($regTrID." ".$param);
                                $regData = blobDecode($trkSpec->$param);
                            }

                            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['sourceGate'] as $kolom => $regGate) {
                                if ($regGate == $param) {
                                    if (!isset($arrRegData[$regGate])) {
                                        $arrRegData[$regTrID][$regGate] = array();
                                    }
                                    $arrRegData[$regTrID][$regGate] = $regData;
                                }
                            }
                        }


                    }
                    //                    arrPrintWebs($arrRegData);

                    foreach ($arrTrIDs as $transaksi_id) {
                        if (isset($arrRegData[$transaksi_id])) {
                            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['kolom_detail'] as $kolom => $arrKolom) {
                                $getGate = $balConfig['viewedColumnsAdditional'][$rekName]['sourceGate'][$kolom];
                                $data = array();
                                switch ($getGate) {
                                    case "main":
                                        foreach ($arrKolom as $key => $label) {
                                            if (isset($arrRegData[$transaksi_id][$getGate][$key])) {
                                                $data[$label] = $arrRegData[$transaksi_id][$getGate][$key];
                                            }
                                        }
                                        break;
                                    case "items":

                                        foreach ($arrKolom as $key => $label) {
                                            foreach ($arrRegData[$transaksi_id][$getGate] as $detail) {
                                                if (isset($detail[$key])) {
                                                    $data[] = formatField_he_format("nomer", $detail[$key]);
                                                }
                                            }
                                        }

                                        break;
                                }

                                $viewedColumnsAdditionalData[$transaksi_id][$kolom] = $data;
                            }
                        }
                        //                        else{
                        //                            cekHitam(":: $transaksi_id tidak ada ::");
                        //                        }
                    }

                }
            }
            //arrPrintPink($viewedColumnsAdditionalData);

            //arrPrintWebs($regDatas);
            foreach ($tmp as $row) {
                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }

                if (isset($arrTransData[$row->transaksi_id])) {
                    foreach ($arrTransData[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }


                $subs = array();
                foreach ($headerFields as $key => $label) {

                    if (array_key_exists($key, $row)) {
                        //                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                        //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                        //                                $subs[$key] = unserialize(base64_decode($row->$key));
                        //                            }
                        //                            else {
                        //                                $subs[$key] = $row->$key;
                        //                            }
                        //                        }
                        //                        else {
                        //                            $subs[$key] = $row->$key;
                        //                        }

                        //                        if ((isset($row->$key)) && (is_array($row->$key))) {
                        //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                        //                                $subs[$key] = unserialize(base64_decode($row->$key));
                        //                            }
                        //                            else {
                        //                                $subs[$key] = $row->$key;
                        //                            }
                        //                        }
                        //                        else {
                        $subs[$key] = $row->$key;
                        //                        }
                        if (($key == "jenis") && (isset($jenisAliases[$row->$key]))) {
                            //                            cekHere(" $key ");
                            $subs[$key] = $jenisAliases[$row->$key];
                        }
                    }


                    if (isset($viewedColumnsAdditionalData[$row->transaksi_id][$key])) {

                        $sub_hasil = "";
                        foreach ($viewedColumnsAdditionalData[$row->transaksi_id][$key] as $subKey => $subLabel) {
                            if ($sub_hasil == "") {
                                if (($subKey != NULL)) {
                                    $sub_hasil = "$subKey &nbsp; $subLabel";
                                }
                                else {
                                    $sub_hasil = "$subLabel";
                                }
                            }
                            else {
                                if (!is_numeric($subKey)) {
                                    $sub_hasil .= "<br>$subKey &nbsp; $subLabel";
                                }
                                else {
                                    $sub_hasil .= "<br>$subLabel";
                                }

                            }
                        }
                        $subs[$key] = $sub_hasil;
                    }

                }


                $subs['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;

                $subs['transaksi_id'] = $row->transaksi_id;
                $subs['review_details'] = $row->transaksi_id;

                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs['modul'] = $modul;
                $subs['modul_path'] = base_url() . $modul . "/";
                $subs['jenis_master'] = $row->jenis_master;
                if (isset($contra_account[$row->transaksi_id]) && sizeof($contra_account[$row->transaksi_id]) > 0) {
                    $subs['contra_account'] = $contra_account[$row->transaksi_id];
                }
                else {
                    $subs['contra_account'] = array();
                }

                $items[] = $subs;
            }
        }
        //        arrPrint($items);
        $date_1 = isset($_GET['date1']) ? "&date1=$date1" : "";
        $date_2 = isset($_GET['date2']) ? "&date2=$date2" : "";
        $file_excel = "$rekName-$date1-$date2";

        $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID" . $date_1 . $date_2;
        //arrPrintWebs($headerFields);
        // arrPrintKuning($arrMovedDetail);
        //arrPrintPink($items);
        $data = array(
            "mode"          => "mutasiDetails",
            "title"         => "$title",
            "subTitle"      => " $subTitle_date $subSubTitle_date",
            "items"         => $items,
            "items2"        => $items0,
            "headerFields"  => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "link_excel"    => $link_excel,
            "file_excel"    => $file_excel,
            "disabled"      => $getDisabled,
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addDetailLink" => sizeof($trDatasLink) > 0 ? $trDatasLink : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );
        $this->load->view("ledger", $data);

    }

    public function viewMoveDetails_1_konsolidasi()
    {
        //        arrPrintPink($_GET);
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getPeriode = (isset($_GET['periode'])) ? $_GET['periode'] : null;
        $getDate = (isset($_GET['date'])) ? $_GET['date'] : null;
        $getDisabled = (isset($_GET['disabled'])) ? $_GET['disabled'] : null;
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;background-color:yellow;") : array();


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $linkKolomConfig = isset($this->config->item('accountMoveColumns')[$relName]['linkToDetail']) ? $this->config->item('accountMoveColumns')[$relName]['linkToDetail'] : array();
        $linkDetailConfig = isset($this->config->item('accountMoveColumns')[$relName]['baselink']) ? $this->config->item('accountMoveColumns')[$relName]['baselink'] : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");

        $tr = new MdlTransaksi();
        $com = new $mdlName();
        //        $com->addFilter("cabang_id='$cabangID'");
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

        if ($getPeriode != null) {
            switch ($getPeriode) {
                //                case "harian":
                //                    $date1 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-01");
                //                    $date2 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                //                    break;
                case "bulanan":
                    $date1 = isset($getDate) ? $getDate . "-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate . "-31" : date("Y-m-d");
                    break;
                case "tahunan":
                    $getDate_ex = explode("-", $getDate);

                    $date1 = isset($getDate) ? $getDate_ex[0] . "-01-01" : date("Y-m-01");
                    $date2 = isset($getDate) ? $getDate_ex[0] . "-12-31" : date("Y-m-d");
                    break;
                case "forever":
                    $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                    $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                    break;

            }
            $this->db->order_by("id", "DESC");
            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");
            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        else {
            if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
                $limit = 20;
                $this->db->limit("$limit");
                $this->db->order_by("id", "DESC");
                //                $this->db->order_by("dtime", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

                $subTitle_date = "";
                $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
            }
            else {
                $this->db->order_by("id", "DESC");
                //                $this->db->order_by("dtime", "DESC");

                $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
                $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");
                $this->db->where("fulldate>='" . $date1 . "'");
                $this->db->where("fulldate<='" . $date2 . "'");

                $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
                $subSubTitle_date = "";
            }
        }

        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekHere(sizeof($tmp));
        //        showLast_query("biru");

        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $tmpTrIds = array();
        $saldoBerjalan = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {

                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $transaksi_id = $temX->transaksi_id;
                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;

            }
        }
        //endregion

        // region ambil jurnal
        $contra_account = array();
        if (sizeof($tmpTrIds) > 0) {
            $ju = New ComJurnal();
            $ju->addFilter("cabang_id='$cabangID'");
            $ju->addFilter("transaksi_id in ('" . implode("','", $tmpTrIds) . "')");
            $juTmp = $ju->lookupAll()->result();
            if (sizeof($juTmp) > 0) {
                foreach ($juTmp as $juSpec) {
                    //                    arrPrintWebs($juSpec);
                    if ($juSpec->rekening != $rekName) {

                        $contra_account[$juSpec->transaksi_id][] = $juSpec->rekening;
                    }
                }
            }
        }
        //        arrPrintWebs($contra_account);
        // endregion ambil jurnal


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit", "debet", "qty_kredit", "qty_debet", //"jenis",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }

        $headerFieldsStatic = array(
            "ids_his"      => "reference number",
            "transaksi_no" => "receipt number",
        );
        foreach ($headerFieldsStatic as $hs_key => $hs_val) {
            if (!array_key_exists($hs_key, $headerFieldsStatic)) {
                $headerFields[$hs_key] = $hs_val;
            }
        }


        if (isset($balConfig['viewedColumnsAdditional'][$rekName]['kolom'])) {
            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['kolom'] as $kolom_nama => $kolom_label) {
                $headerFields[$kolom_nama] = $kolom_label;
            }
        }

        $headerFields["review_details"] = "&nbsp;";

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty"                        => "in qty",
            "out_qty"                       => "out qty",

            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (YTD)",
        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDR)",
            "in"                   => "in (IDR)",
            "out"                  => "out (IDR)",

            "saldo_berjalan" => "saldo (IDR)",

            $defPosition . "_akhir" => "balance (YTD)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }
        $regFieldBlacklist = array(
            "dtime",
        );

        //region buld yIndex
        $items0 = array();
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {
                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;


                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
        }
        //endregion


        //region label detil transaksi
        $arrDecode = array("ids_his", "counters");
        $jenisAliases = arrCodeAliasing($cabangID);
        $arrTransData = array();
        $trDatasLink = array();
        $arrTrIDs = array();
        $regDatas = array();
        //        $arrDataTr = array(
        //            "customers_id",
        //            "customers_nama",
        //            "suppliers_id",
        //            "suppliers_nama",
        //            "oleh_id",
        //            "oleh_nama",
        //            "jenis_master",
        //        );
        $listId = implode("','", $tmpTrIds);
        if (strlen($listId) > 3) {
            $tr->setFilters(array());
            $tr->addFilter("id in ('" . $listId . "')");
            $tmpTrs = $tr->lookupAll()->result();
            foreach ($tmpTrs as $tmpTrsSpec) {
                $arrTrIDs[] = $tmpTrsSpec->id;
                $arrTransData[$tmpTrsSpec->id] = array(
                    "jenis"          => array_key_exists($tmpTrsSpec->jenis, $jenisAliases) ? $jenisAliases[$tmpTrsSpec->jenis] : $tmpTrsSpec->jenis,
                    "customers_id"   => $tmpTrsSpec->customers_id,
                    "customers_nama" => $tmpTrsSpec->customers_nama,
                    "suppliers_id"   => $tmpTrsSpec->suppliers_id,
                    "suppliers_nama" => $tmpTrsSpec->suppliers_nama,
                    "oleh_id"        => $tmpTrsSpec->oleh_id,
                    "oleh_nama"      => $tmpTrsSpec->oleh_nama,
                    "cabang_id"      => $tmpTrsSpec->cabang_id,
                    "cabang_nama"    => $tmpTrsSpec->cabang_nama,
                    "ids_his"        => blobDecode($tmpTrsSpec->ids_his),
                    "jenis_master"   => $tmpTrsSpec->jenis_master,
                );
                foreach ($tmpTrsSpec as $key => $val) {
                    if (array_key_exists($key, $linkKolomConfig)) {
                        $comPembantu = isset($linkKolomConfig[$key]['rekening'][$rekName]['com']) ? $linkKolomConfig[$key]['rekening'][$rekName]['com'] : isset($accountChilds[$rekName]) ? $accountChilds[$rekName] : "";
                        $rekName_new = isset($linkKolomConfig[$key]['rekening'][$rekName]['rek']) ? $linkKolomConfig[$key]['rekening'][$rekName]['rek'] : $rekName;
                        $link = base_url() . "$linkDetailConfig" . $comPembantu . "/$rekName_new/" . $tmpTrsSpec->$linkKolomConfig[$key]['key'] . "?o=$cabangID&trID=" . $tmpTrsSpec->id;
                        $trDatasLink[$tmpTrsSpec->id][$key] = $link;
                    }
                }
            }
            if (sizeof($arrTrIDs) > 0) {
                $trk = New MdlTransaksi();
                $trk->setFilters(array());
                $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
                $trkTmp = $trk->lookupDataRegistries()->result();
                //            showLast_query("kuning");
                if (sizeof($trkTmp) > 0) {
                    foreach ($trkTmp as $trkSpec) {
                        //                    arrPrintWebs($trkSpec);
                        foreach ($trkSpec as $key_reg => $val_reg) {
                            if ($key_reg == "main") {

                                $regTrID = $trkSpec->transaksi_id;
                                $param = $key_reg;
                                $regData = blobDecode($val_reg);

                                if (sizeof($regData) > 0) {
                                    foreach ($headerFields as $key => $val) {
                                        if ((isset($regData[$key])) && (!in_array($key, $regFieldBlacklist))) {

                                            $regDatas[$regTrID][$key] = $regData[$key];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName;
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);

            $viewedColumnsAdditionalData = array();

            if (isset($balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries']) && ($balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries'] != NULL)) {
                $regGate = $balConfig['viewedColumnsAdditional'][$rekName]['pairRegistries'];

                $arrTrIDs = array();
                foreach ($tmp as $row) {
                    $arrTrIDs[] = $row->transaksi_id;
                }

                $this->load->model("MdlTransaksi");
                $trk = New MdlTransaksi();
                $registryFields = $trk->getFields()["dataRegistry"];
                // arrPrint($registryFields);
                $trk->setFilters(array());
                $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
                $trkTmp = $trk->lookUpDataRegistries()->result();
                if (sizeof($trkTmp) > 0) {
                    foreach ($trkTmp as $trkSpec) {
                        $regTrID = $trkSpec->transaksi_id;
                        // $param = $trkSpec->param;

                        foreach ($registryFields as $param) {
                            if ($param == "transaksi_id") {
                                $regData = $trkSpec->$param;
                            }
                            else {
                                // cekHitam($regTrID." ".$param);
                                $regData = blobDecode($trkSpec->$param);
                            }

                            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['sourceGate'] as $kolom => $regGate) {
                                if ($regGate == $param) {
                                    if (!isset($arrRegData[$regGate])) {
                                        $arrRegData[$regTrID][$regGate] = array();
                                    }
                                    $arrRegData[$regTrID][$regGate] = $regData;
                                }
                            }
                        }


                    }
                    //                    arrPrintWebs($arrRegData);

                    foreach ($arrTrIDs as $transaksi_id) {
                        if (isset($arrRegData[$transaksi_id])) {
                            foreach ($balConfig['viewedColumnsAdditional'][$rekName]['kolom_detail'] as $kolom => $arrKolom) {
                                $getGate = $balConfig['viewedColumnsAdditional'][$rekName]['sourceGate'][$kolom];
                                $data = array();
                                switch ($getGate) {
                                    case "main":
                                        foreach ($arrKolom as $key => $label) {
                                            if (isset($arrRegData[$transaksi_id][$getGate][$key])) {
                                                $data[$label] = $arrRegData[$transaksi_id][$getGate][$key];
                                            }
                                        }
                                        break;
                                    case "items":

                                        foreach ($arrKolom as $key => $label) {
                                            foreach ($arrRegData[$transaksi_id][$getGate] as $detail) {
                                                if (isset($detail[$key])) {
                                                    $data[] = formatField_he_format("nomer", $detail[$key]);
                                                }
                                            }
                                        }

                                        break;
                                }

                                $viewedColumnsAdditionalData[$transaksi_id][$kolom] = $data;
                            }
                        }
                        //                        else{
                        //                            cekHitam(":: $transaksi_id tidak ada ::");
                        //                        }
                    }

                }
            }
            //arrPrintPink($viewedColumnsAdditionalData);
            //arrPrintWebs($regDatas);
            //arrPrintWebs($tmp);
            $lastKey = count($tmp) - 1;
            foreach ($tmp as $ctrr => $row) {
                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }
                if (isset($arrTransData[$row->transaksi_id])) {
                    foreach ($arrTransData[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                $debet = $row->debet;
                $kredit = $row->kredit;
                switch ($position) {
                    case "debet":
                        //                        if ($row->kredit_awal > 0) {
                        //                            $row->debet_awal = $row->kredit_awal * -1;
                        //                            $row->kredit_awal = 0;
                        //                        }
                        //                        if ($row->kredit_akhir > 0) {
                        //                            $row->debet_akhir = $row->kredit_akhir * -1;
                        //                            $row->kredit_akhir = 0;
                        //                        }
                        //----------------------
                        if ($ctrr == $lastKey) {
                            $row->debet_awal = 0;
                            $row->kredit_awal = 0;
                            $row->debet_akhir = $row->debet_awal + $debet - $kredit;
                            $row->kredit_akhir = 0;
                            $last_debet = $row->debet_akhir;
                        }
                        else {
                            $row->debet_awal = $last_debet;
                            $row->kredit_awal = 0;
                            $row->debet_akhir = $row->debet_awal + $debet - $kredit;
                            $row->kredit_akhir = 0;
                            $last_debet = $row->debet_akhir;
                        }

                        break;
                    case "kredit":
                        //                        if ($row->debet_awal > 0) {
                        //                            $row->kredit_awal = $row->debet_awal * -1;
                        //                            $row->debet_awal = 0;
                        //                        }
                        //                        if ($row->debet_akhir > 0) {
                        //                            $row->kredit_akhir = $row->debet_akhir * -1;
                        //                            $row->debet_akhir = 0;
                        //                        }
                        //----------------------
                        // baris pertama mutasi
                        if ($ctrr == $lastKey) {
                            $row->debet_awal = 0;
                            $row->kredit_awal = 0;
                            $row->debet_akhir = 0;
                            $row->kredit_akhir = $row->kredit_awal - $debet + $kredit;
                            $last_kredit = $row->kredit_akhir;
                        }
                        else {
                            $row->debet_awal = 0;
                            $row->kredit_awal = $last_kredit;
                            $row->debet_akhir = 0;
                            $row->kredit_akhir = $row->kredit_awal - $debet + $kredit;
                            $last_kredit = $row->kredit_akhir;
                        }

                        break;
                }


                $subs = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        //                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                        //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                        //                                $subs[$key] = unserialize(base64_decode($row->$key));
                        //                            }
                        //                            else {
                        //                                $subs[$key] = $row->$key;
                        //                            }
                        //                        }
                        //                        else {
                        //                            $subs[$key] = $row->$key;
                        //                        }

                        //                        if ((isset($row->$key)) && (is_array($row->$key))) {
                        //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                        //                                $subs[$key] = unserialize(base64_decode($row->$key));
                        //                            }
                        //                            else {
                        //                                $subs[$key] = $row->$key;
                        //                            }
                        //                        }
                        //                        else {
                        $subs[$key] = $row->$key;
                        //                        }
                    }


                    if (isset($viewedColumnsAdditionalData[$row->transaksi_id][$key])) {

                        $sub_hasil = "";
                        foreach ($viewedColumnsAdditionalData[$row->transaksi_id][$key] as $subKey => $subLabel) {
                            if ($sub_hasil == "") {
                                if (($subKey != NULL)) {
                                    $sub_hasil = "$subKey &nbsp; $subLabel";
                                }
                                else {
                                    $sub_hasil = "$subLabel";
                                }
                            }
                            else {
                                if (!is_numeric($subKey)) {
                                    $sub_hasil .= "<br>$subKey &nbsp; $subLabel";
                                }
                                else {
                                    $sub_hasil .= "<br>$subLabel";
                                }

                            }
                        }
                        $subs[$key] = $sub_hasil;
                    }

                }


                $subs['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;

                $subs['transaksi_id'] = $row->transaksi_id;
                $subs['review_details'] = $row->transaksi_id;

                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs['modul'] = $modul;
                $subs['modul_path'] = base_url() . $modul . "/";
                $subs['jenis_master'] = $row->jenis_master;
                if (isset($contra_account[$row->transaksi_id]) && sizeof($contra_account[$row->transaksi_id]) > 0) {
                    $subs['contra_account'] = $contra_account[$row->transaksi_id];
                }
                else {
                    $subs['contra_account'] = array();
                }

                $items[] = $subs;
            }
        }
        //        arrPrint($items);
        $date_1 = isset($_GET['date1']) ? "&date1=$date1" : "";
        $date_2 = isset($_GET['date2']) ? "&date2=$date2" : "";
        $file_excel = "$rekName-$date1-$date2";

        $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID" . $date_1 . $date_2;
        //arrPrintWebs($headerFields);
        //arrPrintPink($items);
        unset($headerFields["urut"]);
        unset($headerFields["saldo_berjalan"]);
        $data = array(
            "mode"          => "mutasiDetails",
            "title"         => "$title",
            "subTitle"      => " $subTitle_date $subSubTitle_date",
            "items"         => $items,
            "items2"        => $items0,
            "headerFields"  => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "link_excel"    => $link_excel,
            "file_excel"    => $file_excel,
            "disabled"      => $getDisabled,
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addDetailLink" => sizeof($trDatasLink) > 0 ? $trDatasLink : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );
        $this->load->view("ledger", $data);

    }


    public function viewMovementGroupOLD()
    {
        //        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        //        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_groupjenis_bl";
        //        $tbl_bl = "__rek_pembantu_produk__persediaan_produk";

        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m");

        $mdlNameS = $this->config->item('heMovementBaru') ? $this->config->item('heMovementBaru') : array();
        $tbl_bl = ((sizeof($mdlNameS) > 0) && (isset($mdlNameS[$item]['tblMutasi']))) ? $mdlNameS[$item]['tblMutasi'] : "";


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        $headers = isset($mdlNameS[$item]['headers']) ? $mdlNameS[$item]['headers'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // arrPrint($headers);
        $mainHeaders_1 = array();
        foreach ($headers as $hKey => $hSpecs) {
            $mSpecs[$hSpecs["label"]] = $hSpecs['attr'];
            $mainHeaders_1 = $mSpecs;

            foreach ($hSpecs["subHeader"] as $shKey => $shSpecs) {
                // cekHere($shKey);
                // arrPrint($shSpecs);
                $subHeaders[$hKey][$shSpecs['label']] = $shSpecs['attr'];
            }
        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        // $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        // $subHeaders = isset($headers['subHeaders']) ? $headers['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // arrPrint($subHeaders);
        //endregion

        $list_produkId = "";
        // $list_produkId = "76,386,80,34,36,37,38,39";
        // $list_produkId = "76,34";
        // $list_produkId = "102,37,87";
        //
        //        $jenisExternal = array(
        //            "982", "582spd", "382spd", "467", "967"
        //        );
        $jenisExternal = isset($mdlNameS[$item]['jenisTransaksiEksternal']) ? $mdlNameS[$item]['jenisTransaksiEksternal'] : array();

        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id"    => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            "month(dtime)" => $bl,
            // "thn"       => $th,
            "year(dtime)"  => $th,
            // "dtime "   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
        //        showLast_query("lime");
        //         arrPrint($srcDatas);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();


        //region source 2 query
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <"   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        //endregion
        //        showLast_query("hijau");
        // arrPrintHere($srcBlLast);
        $extrDatas2 = array();
        $intrDatas2 = array();
        $allDatas2 = array();
        foreach ($srcBlLast as $srcSpecs) {
            // $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
            $jenis = $srcSpecs->jenis;
            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            else {
                $intrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }

            $allDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        //arrPrint($allDatas2);
        // arrPrintWebs($subHeaders);
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());

            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        if (strlen($list_produkId) > 1) {
            $mdl->addFilter("id in ($list_produkId)");
        }
        $produks = $mdl->lookupAll()->result();

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        // arrPrint($srcDatas);
        $extrDatas0 = array();
        $intrDatas0 = array();
        $extrDatas = array();
        $intrDatas = array();
        foreach ($srcDatas as $srcSpecs) {
            // arrPrintHere($srcSpecs);
            $jenis = $srcSpecs->jenis;

            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas0[] = $srcSpecs;
            }
            else {
                $intrDatas0[] = $srcSpecs;
            }

        }
        // arrPrintWebs($extrDatas0);
        // matiHere();

        //region ngesum data


        $arrSum = array(
            // "debet_awal",
            "debet",
            // "debet_akhir",
            // "kredit_awal",
            "kredit",
            // "kredit_akhir",
            // "qty_debet_awal",
            "qty_debet",
            // "qty_debet_akhir",
            // "qty_kredit_awal",
            "qty_kredit",
            // "qty_kredit_akhir",
        );
        $arrSumView = array(
            "qty_debet_awal",
            "debet_awal",

            "qty_debet_int",
            "debet_int",
            "qty_debet",
            "debet",

            "qty_kredit_int",
            "kredit_int",
            "qty_kredit",
            "kredit",
            "qty_akhir",

            "qty_kredit",
            "akhir",
        );

        $allDatas = array();
        //region mengmbil data terakhir
        // arrPrintWebs($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            // cekHere($srcData->qty_debet_akhir . " $extern_id // $ky *** $ururt");
            $allDataAkhir[$extern_id]['qty_debet_akhir'] = $srcData->qty_debet_akhir;
            $allDataAkhir[$extern_id]['debet_akhir'] = $srcData->debet_akhir;
            $allDataAkhir[$extern_id]['harga_akhir'] = $srcData->harga;
        }
        //endregion
        // arrPrint($allDataAkhir);
        // matiHere();

        krsort($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($allDatasSum[$extern_id][$itemSum])) {
                    $allDatasSum[$extern_id][$itemSum] = 0;
                }
                $allDatasSum[$extern_id][$itemSum] += $srcData->$itemSum;
            }

            //region mengambil data awal
            $allDataAwal[$extern_id]['qty_debet_awal'] = $srcData->qty_debet_awal;
            $allDataAwal[$extern_id]['debet_awal'] = $srcData->debet_awal;
            $harga_satuan = $srcData->qty_debet_awal > 0 ? ($srcData->debet_awal / $srcData->qty_debet_awal) : 0;
            $allDataAwal[$extern_id]['harga_debet_awal'] = $harga_satuan;

            //            cekBiru(":: $extern_id :: qty awal: " . $srcData->qty_debet_awal . ", value awal: " . $srcData->debet_awal . ", harga: " . $harga_satuan);
            //endregion

            $allDatas[$extern_id] = (array)$srcData;
        }
        //        arrPrint($allDataAwal);


        $extrDatas = array();
        $extrDataAwal = array();
        $extrDataAkhir = array();
        //region mengambil data akhir external
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            $extrDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $extrDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion
        // arrPrintWebs($extrDatas0);
        krsort($extrDatas0);
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($extrDatasSum[$extern_id][$itemSum])) {
                    $extrDatasSum[$extern_id][$itemSum] = 0;
                }
                $extrDatasSum[$extern_id][$itemSum] += $items->$itemSum;
            }
            //region mengambil data awal external
            $extrDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $extrDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion
            $extrDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($extrDatasSum[$extern_id]['debet_harga'])) {
                    $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
        }
        // arrPrint($extrDatasSum);


        $intrDatas = array();
        $intrDataAwal = array();
        $intrDataAkhir = array();
        // arrPrintHere($intrDatas0);
        //region data akhir
        foreach ($intrDatas0 as $items) {
            $intern_id = $items->extern_id;
            $intrDataAkhir[$intern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $intrDataAkhir[$intern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($intrDatas0);
        foreach ($intrDatas0 as $items) {
            // arrPrint($items);
            $intern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($intrDatasSum[$intern_id][$itemSum])) {
                    $intrDatasSum[$intern_id][$itemSum] = 0;
                }
                $intrDatasSum[$intern_id][$itemSum] += $items->$itemSum;
            }
            // cekHere("$intern_id " . $items->qty_debet_awal);
            $intrDataAwal[$intern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $intrDataAwal[$intern_id]['debet_awal'] = $items->debet_awal;
            // arrPrint($intrDatasSum);
            $intrDatas[$intern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($intrDatasSum[$intern_id]['debet_harga'])) {
                    $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
            if ($items->qty_kredit > 0) {
                if (!isset($intrDatasSum[$intern_id]['kredit_harga'])) {
                    $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] += $items->qty_kredit;
            }
        }
        //endregion

        // arrPrintWebs($intrDatas0);
        // arrPrintWebs($intrDataAwal);
        // arrPrintWebs($extrDatas0);
        // arrPrint($extrDataAwal);
        // arrPrint($extrDatasSum);
        // arrPrintWebs($intrDatasSum);
        // arrPrint($extrDatas);
        // matiHere();

        // switch ($mv) {
        //     case "all":
        //         $dataSourceAwal = $allDataAwal;
        //         $dataSourceSum = $allDatasSum;
        //         $dataSource = $allDatas;
        //         $dataSourceAkhir = $allDataAkhir;
        //
        //         $dataSource2 = $allDatas2;
        //         break;
        //     case "external":
        //         $dataSourceAwal = $extrDataAwal;
        //         $dataSourceSum = $extrDatasSum;
        //         $dataSource = $extrDatas;
        //         $dataSourceAkhir = $extrDataAkhir;
        //         $dataSource2 = $extrDatas2;
        //         break;
        //     case "internal":
        //         $dataSourceAwal = $intrDataAwal;
        //         $dataSourceSum = $intrDatasSum;
        //         $dataSource = $intrDatas;
        //         $dataSourceAkhir = $intrDataAkhir;
        //         $dataSource2 = $intrDatas2;
        //         break;
        // }

        // arrPrint($extrDatas);
        // arrPrintWebs($intrDatas);
        //         matiHere(__METHOD__ . __LINE__);
        // arrPrint($arrQtyHarga);
        // matiHere();
        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // arrPrint($dataSource);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        // arrPrintWebs($allDataAkhir);
        //         arrPrintWebs($allDatas2);

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion


            //region begining
            $akhirLast = isset($allDatas2[$pId]['qty_debet_akhir']) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $akhirValueLast = isset($allDatas2[$pId]['debet_akhir']) ? $allDatas2[$pId]['debet_akhir'] : 0;


            $specs['qty_debet_awal']['value'] = isset($allDataAwal[$pId]['qty_debet_awal']) ? $allDataAwal[$pId]['qty_debet_awal'] : $akhirLast;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";

            $specs['harga_debet_awal']['value'] = isset($allDataAwal[$pId]['harga_debet_awal']) ? $allDataAwal[$pId]['harga_debet_awal'] : ($akhirLast > 0) ? $akhirValueLast / $akhirLast : 0;
            $specs['harga_debet_awal']['attr'] = "class='text-right'";

            $debetAwalLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : $debetAwalLast;
            $specs['debet_awal']['attr'] = "class='text-right'";
            //endregion


            //region incoming masuk
            //region internal
            $specs['qty_debet_int']['value'] = isset($intrDatasSum[$pId]['qty_debet']) ? $intrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet_int']['attr'] = "class='text-right'";

            // $specs['harga_debet_int']['value'] = isset($intrDatasSum[$pId]['harga_debet']) ? $intrDatasSum[$pId]['harga_debet'] : 0;
            if ($intrDatasSum[$pId]['qty_debet'] > 0) {
                $harga_satuan = $intrDatas[$pId]['harga'] * 1;
            }
            else {
                $harga_satuan = 0;
            }
            $specs['harga_debet_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet_int']['attr'] = "class='text-right'";

            $specs['debet_int']['value'] = isset($intrDatasSum[$pId]['debet']) ? $intrDatasSum[$pId]['debet'] : 0;
            $specs['debet_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            $specs['qty_debet']['value'] = isset($extrDatasSum[$pId]['qty_debet']) ? $extrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet']['attr'] = "class='text-right'";

            if ($extrDatasSum[$pId]['qty_debet'] > 0) {
                // $harga_satuan = $extrDatasSum[$pId]['harga'] *1 ;
                $harga_satuan = $extrDatasSum[$pId]['debet'] / $extrDatasSum[$pId]['qty_debet'];
            }
            else {
                $harga_satuan = 0;
            }

            // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
            $specs['harga_debet']['value'] = isset($extrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet']['attr'] = "class='text-right'";

            $specs['debet']['value'] = isset($extrDatasSum[$pId]['debet']) ? $extrDatasSum[$pId]['debet'] : 0;
            $specs['debet']['attr'] = "class='text-right'";
            //endregion
            //endregion


            //region outgoing keluar

            //region internal
            $specs['qty_kredit_int']['value'] = isset($intrDatasSum[$pId]['qty_kredit']) ? $intrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit_int']['attr'] = "class='text-right'";

            if ($intrDatasSum[$pId]['qty_kredit'] > 0) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $intrDatasSum[$pId]['kredit'] / $intrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit_int']['value'] = isset($intrDatasSum[$pId]['harga_kredit']) ? $intrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit_int']['attr'] = "class='text-right'";

            $specs['kredit_int']['value'] = isset($intrDatasSum[$pId]['kredit']) ? $intrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            $specs['qty_kredit']['value'] = isset($extrDatasSum[$pId]['qty_kredit']) ? $extrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit']['attr'] = "class='text-right'";

            if ($extrDatasSum[$pId]['qty_kredit'] > 0) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $extrDatasSum[$pId]['kredit'] / $extrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit']['value'] = isset($extrDatasSum[$pId]['harga_kredit']) ? $extrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit']['attr'] = "class='text-right'";

            $specs['kredit']['value'] = isset($extrDatasSum[$pId]['kredit']) ? $extrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit']['attr'] = "class='text-right'";
            //endregion

            //endregion


            //region ending

            $qtyDebetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['debet_akhir'] : 0;

            $specs['qty_akhir']['value'] = isset($allDataAkhir[$pId]['qty_debet_akhir']) ? $allDataAkhir[$pId]['qty_debet_akhir'] : $qtyDebetAkhirLast;
            $specs['qty_akhir']['attr'] = "class='text-right'";

            if (isset($allDataAkhir[$pId]['qty_debet_akhir'])) {
                if ($allDataAkhir[$pId]['qty_debet_akhir'] > 0) {
                    //                $harga_satuan = $allDataAkhir[$pId]['harga_akhir'] * 1;
                    $harga_satuan = $allDataAkhir[$pId]['debet_akhir'] / $allDataAkhir[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }
            else {
                if ((isset($allDatas2[$pId]['qty_debet_akhir'])) && ($allDatas2[$pId]['qty_debet_akhir'] > 0)) {
                    $harga_satuan = $allDatas2[$pId]['debet_akhir'] / $allDatas2[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }

            // $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $allDataAkhir[$pId]['harga_akhir'] : 0;
            //            $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $harga_satuan : "**";
            $specs['harga_akhir']['value'] = $harga_satuan;
            $specs['harga_akhir']['attr'] = "class='text-right'";

            // $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : $debetAkhirLast;
            $specs['akhir']['attr'] = "class='text-right'";

            //endregion


            $bodies[] = $specs;

            // if(!isset($sumvalues[]))
        }
        //endregion

        // arrPrintHere($allDataAkhir);
        // $sumQty = "";
        // arrPrint($sumQty);

        // region footers
        //        $footer_colspan = sizeof($mdlNameS[$item]['mdlFields'])-1;
        //        cekHere($footer_colspan);
        $footers = array(
            "total" => "colspan='3' class='text-right bg-info text-uppercase'",
            // formatField("qty_debet_awal", $sumQty['qty_debet_awal']) => "class='text-right bg-info text-uppercase'",
            // "-"                                                      => "class='text-right bg-info text-uppercase'",
            // formatField("debet_awal", $sumQty['debet_awal'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_debet", $sumQty['qty_debet']) => "class='text-right bg-info text-uppercase'",
            // "--"                                           => "class='text-right bg-info text-uppercase'",
            // formatField("debet", $sumQty['debet'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_avail", $sumQty['qty_avail']) => "class='text-right bg-info text-uppercase'",
            // "."                                            => "class='text-right bg-info text-uppercase'",
            // formatField("avail", $sumQty['avail'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_kredit", $sumQty['qty_kredit']) => "class='text-right bg-info text-uppercase'",
            // ".."                                             => "class='text-right bg-info text-uppercase'",
            // formatField("kredit", $sumQty['kredit'])         => "class='text-right bg-info text-uppercase'",
            //
            // formatField("qty_akhir", $sumQty['qty_akhir']) => "class='text-right bg-info text-uppercase'",
            // ""                                             => "class='text-right bg-info text-uppercase'",
            // formatField("akhir", $sumQty['akhir'])         => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers

        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        // $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField_he_format("dtime", $date1) . " s/d " . formatField_he_format("dtime", $date2);
        $dateRange = "($dRange)";

        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        $btnGroups = array(
            // "all"      => array(
            //     "label" => "All Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=all" . $strO . $strDate1,
            // ),
            // "external" => array(
            //     "label" => "external Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=external" . $strO . $strDate1,
            // ),
            // "internal" => array(
            //     "label" => "internal Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=internal" . $strO . $strDate1,
            // ),
        );
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode"     => "movementGroup",
            "title"    => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1"    => $date1,
            "date2"    => $date2,

            "rowLoop"     => 6,
            "mainHeaders" => $mainHeaders,
            "subHeaders"  => $subHeaders,
            "bodies"      => $bodies,
            "footers"     => $footers,
            "sumfooters"  => $arrSumView,
            "btnGroups"   => $btnGroups,
            "mdlFields"   => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),

            "detailsLabels" => "",
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovementGroup()
    {
        /* -------------------------------------------------------------
         * pembatasan hak akses, group seller dan sekleompoknya tidak boleh melihat HPP
         * -------------------------------------------------------------*/
        $notAllowed = array(
            // "o_kasir",
            "o_seller_entry",
            "o_seller",
            "o_seller_spv",
            // "o_export",
            // "o_export_spv",
            // "o_project",
        );

        $myMembers = my_memberships();

        $myName = my_name();
        $myCbName = my_cabang_nama();
        $alerter = "";
        foreach ($notAllowed as $itemGroup) {
            if (in_array($itemGroup, $myMembers)) {
                // arrPrint($myMembers);
                // cekHijau("$itemGroup");
                $alers = array(
                    "type"              => "warning",
                    // "title" => "info",
                    "html"              => "Id ($myName/$myCbName) anda tidak memiliki kewenangan untuk melihat isi menu ini<br>klik OK untuk kembali ke Home",
                    // "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                    // "timer" => 10,
                    "showCloseButton"   => false,
                );
                $home = base_url();
                $alerter = swalAlertGoTo($alers, $home, "OK");
                break;
            }
        }


        $mv = isset($_GET['mv']) ? $_GET['mv'] : "";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m");
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m");

        $mdlNameS = $this->config->item('heMovementBaru') ? $this->config->item('heMovementBaru') : array();
        $tbl_bl = ((sizeof($mdlNameS) > 0) && (isset($mdlNameS[$item]['tblMutasi']))) ? $mdlNameS[$item]['tblMutasi'] : "";


        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }


        $jenisExternal = isset($mdlNameS[$item]['jenisTransaksiEksternal']) ? $mdlNameS[$item]['jenisTransaksiEksternal'] : array();
        $jenisBom = isset($mdlNameS[$item]['jenisTransaksi_bom']) ? $mdlNameS[$item]['jenisTransaksi_bom'] : array();
        $jenisNG = isset($mdlNameS[$item]['jenisTransaksi_ng']) ? $mdlNameS[$item]['jenisTransaksi_ng'] : array();
        $jenisBiaya = isset($mdlNameS[$item]['jenisTransaksi_biaya']) ? $mdlNameS[$item]['jenisTransaksi_biaya'] : array();


        $headers = isset($mdlNameS[$item]['headers']) ? $mdlNameS[$item]['headers'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $additionalHeaders = isset($mdlNameS[$item]['additionalHeaders']) ? $mdlNameS[$item]['additionalHeaders'] : array();

        $mainHeaders_1 = array();
        foreach ($headers as $hKey => $hSpecs) {

            foreach ($hSpecs["subHeader"] as $shKey => $shSpecs) {
                $subHeaders[$hKey][$shSpecs['label']] = $shSpecs['attr'];
            }

            if (sizeof($additionalHeaders) > 0) {
                foreach ($additionalHeaders as $jenisTrLabel => $jSpec) {
                    if (array_key_exists($jenisTrLabel, $mdlNameS[$item]) && sizeof($mdlNameS[$item][$jenisTrLabel]) > 0) {
                        if (isset($jSpec['subHeader'][$hKey]) && sizeof($jSpec['subHeader'][$hKey]) > 0) {
                            foreach ($jSpec['subHeader'][$hKey] as $jhSpecs) {
                                $subHeaders[$hKey][$jhSpecs['label']] = $jhSpecs['attr'];
                            }
                        }
                    }
                }
            }

            $attr = str_replace("colspan", "colspan='" . sizeof($subHeaders[$hKey]) . "'", $hSpecs['attr']);
            $mSpecs[$hSpecs["label"]] = $attr;
            $mainHeaders_1 = $mSpecs;

        }

        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        //endregion

        /* -----------------------------------------------
         * == filter produk list id
         * ----------------------------------------------------*/
        // if(show_debuger() == 1){
        //     $list_produkId = "175,176";
        // }
        // else{
        $list_produkId = "";
        // }

        //region ambil mutasi periode saat ini
        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id"    => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            "month(dtime)" => $bl,
            // "thn"       => $th,
            "year(dtime)"  => $th,
            // "dtime "   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcDatas = $this->db->get($tbl_bl)->result();
        //        showLast_query("lime");
        //         arrPrint($srcDatas);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();
        //endregion

        //region source 2 query, ambil data mutasi sebelumnya...
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <"   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        // $this->db->where_in("jenis",$jenis);
        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        //        showLast_query("hijau");
        // arrPrintHere($srcBlLast);
        //endregion

        //region pengelompokan jenis transaksi ke internal, eksternal, bom, not good
        $extrDatas2 = array();
        $intrDatas2 = array();
        $bomDatas2 = array();
        $ngDatas2 = array();
        $expDatas2 = array();
        $allDatas2 = array();
        foreach ($srcBlLast as $srcSpecs) {
            // $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
            $jenis = $srcSpecs->jenis;
            // cekHere("$jenis");
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisBom)) {
                $bomDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisNG)) {
                $ngDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            elseif (in_array($jenis, $jenisBiaya)) {
                $expDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }
            else {
                $intrDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
            }

            $allDatas2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        //endregion

        //region data seluruh produk
        //        if ($this->placeID <= 0) {
        $mdl->setFilters(array());
        $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        //        }
        if (strlen($list_produkId) > 1) {
            $mdl->addFilter("id in ($list_produkId)");
        }
        $produks = $mdl->lookupAll()->result();
        //        showLast_query("kuning");
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion


        $extrDatas0 = array();
        $intrDatas0 = array();
        $bomDatas0 = array();
        $ngDatas0 = array();
        $expDatas0 = array();
        $extrDatas = array();
        $intrDatas = array();
        $bomDatas = array();
        $ngDatas = array();
        $expDatas = array();
        foreach ($srcDatas as $srcSpecs) {
            $jenis = $srcSpecs->jenis;
            if (in_array($jenis, $jenisExternal)) {
                $extrDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisBom)) {
                $bomDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisNG)) {
                $ngDatas0[] = $srcSpecs;
            }
            elseif (in_array($jenis, $jenisBiaya)) {
                $expDatas0[] = $srcSpecs;
            }
            else {
                $intrDatas0[] = $srcSpecs;
            }
        }

        //region ngesum data
        $arrSum = array(
            "debet",
            "kredit",
            "debet_bom",
            "kredit_bom",
            "debet_ng",
            "kredit_ng",

            "qty_debet",
            "qty_kredit",
            "qty_debet_bom",
            "qty_kredit_bom",
            "qty_debet_ng",
            "qty_kredit_ng",

        );
        $arrSumView = array(
            "qty_debet_awal",
            "debet_awal",

            "qty_debet_int",
            "debet_int",

            "qty_debet",
            "debet",

            "qty_debet_bom",
            "debet_bom",

            "qty_debet_ng",
            "debet_ng",

            "qty_kredit_int",
            "kredit_int",

            "qty_kredit",
            "kredit",

            "qty_kredit_bom",
            "kredit_bom",

            "qty_kredit_ng",
            "kredit_ng",

            "qty_akhir",
            "qty_kredit",
            "akhir",
        );

        $allDatas = array();
        //region mengmbil data terakhir
        // arrPrintWebs($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            // cekHere($srcData->qty_debet_akhir . " $extern_id // $ky *** $ururt");
            $allDataAkhir[$extern_id]['qty_debet_akhir'] = $srcData->qty_debet_akhir;
            $allDataAkhir[$extern_id]['debet_akhir'] = $srcData->debet_akhir;
            $allDataAkhir[$extern_id]['harga_akhir'] = $srcData->harga;
        }
        //endregion


        krsort($srcDatas);
        foreach ($srcDatas as $ky => $srcData) {
            $extern_id = $srcData->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($allDatasSum[$extern_id][$itemSum])) {
                    $allDatasSum[$extern_id][$itemSum] = 0;
                }
                $allDatasSum[$extern_id][$itemSum] += isset($srcData->$itemSum) ? $srcData->$itemSum : 0;
            }

            //region mengambil data awal
            $allDataAwal[$extern_id]['qty_debet_awal'] = $srcData->qty_debet_awal;
            $allDataAwal[$extern_id]['debet_awal'] = $srcData->debet_awal;
            $harga_satuan = $srcData->qty_debet_awal > 0 ? ($srcData->debet_awal / $srcData->qty_debet_awal) : 0;
            $allDataAwal[$extern_id]['harga_debet_awal'] = $harga_satuan;

            //            cekBiru(":: $extern_id :: qty awal: " . $srcData->qty_debet_awal . ", value awal: " . $srcData->debet_awal . ", harga: " . $harga_satuan);
            //endregion

            $allDatas[$extern_id] = (array)$srcData;
        }
        //        arrPrint($allDataAwal);


        $extrDatas = array();
        $extrDataAwal = array();
        $extrDataAkhir = array();
        //region mengambil data akhir external
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            $extrDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $extrDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($extrDatas0);
        foreach ($extrDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($extrDatasSum[$extern_id][$itemSum])) {
                    $extrDatasSum[$extern_id][$itemSum] = 0;
                }
                $extrDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $extrDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $extrDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $extrDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($extrDatasSum[$extern_id]['debet_harga'])) {
                    $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $extrDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
        }


        //region bom
        $bomDatas = array();
        $bomDataAwal = array();
        $bomDataAkhir = array();
        //region mengambil data akhir external
        foreach ($bomDatas0 as $items) {
            $extern_id = $items->extern_id;
            $bomDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $bomDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($bomDatas0);
        foreach ($bomDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($bomDatasSum[$extern_id][$itemSum])) {
                    $bomDatasSum[$extern_id][$itemSum] = 0;
                }
                $bomDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $bomDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $bomDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $bomDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $bomDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        //region not good
        $ngDatas = array();
        $ngDataAwal = array();
        $ngDataAkhir = array();
        //region mengambil data akhir external
        foreach ($ngDatas0 as $items) {
            $extern_id = $items->extern_id;
            $ngDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $ngDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($ngDatas0);
        foreach ($ngDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($ngDatasSum[$extern_id][$itemSum])) {
                    $ngDatasSum[$extern_id][$itemSum] = 0;
                }
                $ngDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $ngDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $ngDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $ngDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $ngDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        //region biayakan
        $expDatas = array();
        $expDataAwal = array();
        $expDataAkhir = array();
        //region mengambil data akhir external
        foreach ($expDatas0 as $items) {
            $extern_id = $items->extern_id;
            $expDataAkhir[$extern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $expDataAkhir[$extern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($expDatas0);
        foreach ($expDatas0 as $items) {
            $extern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($expDatasSum[$extern_id][$itemSum])) {
                    $expDatasSum[$extern_id][$itemSum] = 0;
                }
                $expDatasSum[$extern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }

            //region mengambil data awal external
            $expDataAwal[$extern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $expDataAwal[$extern_id]['debet_awal'] = $items->debet_awal;
            //endregion

            $expDatas[$extern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)])) {
                    $expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                //                cekHitam($items->harga);
                $expDatasSum[$extern_id]['debet_harga'][($items->harga * 1)] += isset($items->qty_debet) ? $items->qty_debet : 0;
            }
        }
        //endregion


        $intrDatas = array();
        $intrDataAwal = array();
        $intrDataAkhir = array();
        //region data akhir
        foreach ($intrDatas0 as $items) {
            $intern_id = $items->extern_id;
            $intrDataAkhir[$intern_id]['qty_debet_akhir'] = $items->qty_debet_akhir;
            $intrDataAkhir[$intern_id]['debet_akhir'] = $items->debet_akhir;
        }
        //endregion

        krsort($intrDatas0);
        foreach ($intrDatas0 as $items) {
            // arrPrint($items);
            $intern_id = $items->extern_id;
            foreach ($arrSum as $itemSum) {
                if (!isset($intrDatasSum[$intern_id][$itemSum])) {
                    $intrDatasSum[$intern_id][$itemSum] = 0;
                }
                $intrDatasSum[$intern_id][$itemSum] += isset($items->$itemSum) ? $items->$itemSum : 0;
            }
            // cekHere("$intern_id " . $items->qty_debet_awal);
            $intrDataAwal[$intern_id]['qty_debet_awal'] = $items->qty_debet_awal;
            $intrDataAwal[$intern_id]['debet_awal'] = $items->debet_awal;
            // arrPrint($intrDatasSum);
            $intrDatas[$intern_id] = (array)$items;

            if ($items->qty_debet > 0) {
                if (!isset($intrDatasSum[$intern_id]['debet_harga'])) {
                    $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['debet_harga'][($items->harga * 1)] += $items->qty_debet;
            }
            if ($items->qty_kredit > 0) {
                if (!isset($intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)])) {
                    $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] = 0;
                }
                $intrDatasSum[$intern_id]['kredit_harga'][($items->harga * 1)] += isset($items->qty_kredit) ? $items->qty_kredit : 0;
            }
        }
        //endregion


        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;


        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";

            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion


            //region begining
            $akhirLast = isset($allDatas2[$pId]['qty_debet_akhir']) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $akhirValueLast = isset($allDatas2[$pId]['debet_akhir']) ? $allDatas2[$pId]['debet_akhir'] : 0;


            $specs['qty_debet_awal']['value'] = isset($allDataAwal[$pId]['qty_debet_awal']) ? $allDataAwal[$pId]['qty_debet_awal'] : $akhirLast;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";

            $specs['harga_debet_awal']['value'] = isset($allDataAwal[$pId]['harga_debet_awal']) ? $allDataAwal[$pId]['harga_debet_awal'] : ($akhirLast > 0) ? $akhirValueLast / $akhirLast : 0;
            $specs['harga_debet_awal']['attr'] = "class='text-right'";
            // cekHijau($allDatas2[$pId]['harga_avg'] ." x ". $allDatas2[$pId]['qty_debet_akhir'] . " = ". $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir']);
            $debetAwalLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            //            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : 0;
            $specs['debet_awal']['value'] = isset($allDataAwal[$pId]['debet_awal']) ? $allDataAwal[$pId]['debet_awal'] : $debetAwalLast;
            $specs['debet_awal']['attr'] = "class='text-right'";
            //endregion


            //region incoming masuk
            //region internal
            $specs['qty_debet_int']['value'] = isset($intrDatasSum[$pId]['qty_debet']) ? $intrDatasSum[$pId]['qty_debet'] : 0;
            $specs['qty_debet_int']['attr'] = "class='text-right'";

            // $specs['harga_debet_int']['value'] = isset($intrDatasSum[$pId]['harga_debet']) ? $intrDatasSum[$pId]['harga_debet'] : 0;
            if ((isset($intrDatasSum[$pId]['qty_debet'])) && ($intrDatasSum[$pId]['qty_debet'] > 0)) {
                $harga_satuan = $intrDatas[$pId]['harga'] * 1;
            }
            else {
                $harga_satuan = 0;
            }
            $specs['harga_debet_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_debet_int']['attr'] = "class='text-right'";

            $specs['debet_int']['value'] = isset($intrDatasSum[$pId]['debet']) ? $intrDatasSum[$pId]['debet'] : 0;
            $specs['debet_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            if (sizeof($jenisExternal) > 0) {
                $specs['qty_debet']['value'] = isset($extrDatasSum[$pId]['qty_debet']) ? $extrDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet']['attr'] = "class='text-right'";

                if ((isset($extrDatasSum[$pId]['qty_debet'])) && ($extrDatasSum[$pId]['qty_debet'] > 0)) {
                    // $harga_satuan = $extrDatasSum[$pId]['harga'] *1 ;
                    $harga_satuan = $extrDatasSum[$pId]['debet'] / $extrDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet']['value'] = isset($extrDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet']['attr'] = "class='text-right'";

                $specs['debet']['value'] = isset($extrDatasSum[$pId]['debet']) ? $extrDatasSum[$pId]['debet'] : 0;
                $specs['debet']['attr'] = "class='text-right'";
            }
            //endregion

            //region bom
            if (sizeof($jenisBom) > 0) {
                $specs['qty_debet_bom']['value'] = isset($bomDatasSum[$pId]['qty_debet']) ? $bomDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_bom']['attr'] = "class='text-right'";

                if (isset($bomDatasSum[$pId]['qty_debet']) && ($bomDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $bomDatasSum[$pId]['debet'] / $bomDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet_bom']['value'] = isset($bomDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_bom']['attr'] = "class='text-right'";

                $specs['debet_bom']['value'] = isset($bomDatasSum[$pId]['debet']) ? $bomDatasSum[$pId]['debet'] : 0;
                $specs['debet_bom']['attr'] = "class='text-right'";
            }
            //endregion

            //region notgood
            if (sizeof($jenisNG) > 0) {
                $specs['qty_debet_ng']['value'] = isset($ngDatasSum[$pId]['qty_debet']) ? $ngDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_ng']['attr'] = "class='text-right'";

                if (isset($ngDatasSum[$pId]['qty_debet']) && ($ngDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $ngDatasSum[$pId]['debet'] / $ngDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                // $specs['harga_debet']['value'] = isset($extrDatasSum[$pId]['harga_debet']) ? $extrDatasSum[$pId]['harga_debet'] : 0;
                $specs['harga_debet_ng']['value'] = isset($ngDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_ng']['attr'] = "class='text-right'";

                $specs['debet_ng']['value'] = isset($ngDatasSum[$pId]['debet']) ? $ngDatasSum[$pId]['debet'] : 0;
                $specs['debet_ng']['attr'] = "class='text-right'";
            }
            //endregion

            //region biayakan
            if (sizeof($jenisBiaya) > 0) {
                $specs['qty_debet_exp']['value'] = isset($expDatasSum[$pId]['qty_debet']) ? $expDatasSum[$pId]['qty_debet'] : 0;
                $specs['qty_debet_exp']['attr'] = "class='text-right'";

                if (isset($expDatasSum[$pId]['qty_debet']) && ($expDatasSum[$pId]['qty_debet'] > 0)) {
                    $harga_satuan = $expDatasSum[$pId]['debet'] / $expDatasSum[$pId]['qty_debet'];
                }
                else {
                    $harga_satuan = 0;
                }

                $specs['harga_debet_exp']['value'] = isset($expDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_debet_exp']['attr'] = "class='text-right'";

                $specs['debet_exp']['value'] = isset($expDatasSum[$pId]['debet']) ? $expDatasSum[$pId]['debet'] : 0;
                $specs['debet_exp']['attr'] = "class='text-right'";
            }
            //endregion

            //endregion


            //region outgoing keluar

            //region internal
            $specs['qty_kredit_int']['value'] = isset($intrDatasSum[$pId]['qty_kredit']) ? $intrDatasSum[$pId]['qty_kredit'] : 0;
            $specs['qty_kredit_int']['attr'] = "class='text-right'";

            if ((isset($intrDatasSum[$pId]['qty_kredit'])) && ($intrDatasSum[$pId]['qty_kredit'] > 0)) {
                // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                $harga_satuan = $intrDatasSum[$pId]['kredit'] / $intrDatasSum[$pId]['qty_kredit'];
            }
            else {
                $harga_satuan = 0;
            }
            // $specs['harga_kredit_int']['value'] = isset($intrDatasSum[$pId]['harga_kredit']) ? $intrDatasSum[$pId]['harga_kredit'] : 0;
            $specs['harga_kredit_int']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
            $specs['harga_kredit_int']['attr'] = "class='text-right'";

            $specs['kredit_int']['value'] = isset($intrDatasSum[$pId]['kredit']) ? $intrDatasSum[$pId]['kredit'] : 0;
            $specs['kredit_int']['attr'] = "class='text-right'";
            //endregion

            //region external
            if (sizeof($jenisExternal) > 0) {
                $specs['qty_kredit']['value'] = isset($extrDatasSum[$pId]['qty_kredit']) ? $extrDatasSum[$pId]['qty_kredit'] : 0;
                $specs['qty_kredit']['attr'] = "class='text-right'";

                if ((isset($extrDatasSum[$pId]['qty_kredit'])) && ($extrDatasSum[$pId]['qty_kredit'] > 0)) {
                    // $harga_satuan = $intrDatas[$pId]['harga'] *1 ;
                    $harga_satuan = $extrDatasSum[$pId]['kredit'] / $extrDatasSum[$pId]['qty_kredit'];
                }
                else {
                    $harga_satuan = 0;
                }
                // $specs['harga_kredit']['value'] = isset($extrDatasSum[$pId]['harga_kredit']) ? $extrDatasSum[$pId]['harga_kredit'] : 0;
                $specs['harga_kredit']['value'] = isset($intrDatas[$pId]['harga']) ? $harga_satuan : 0;
                $specs['harga_kredit']['attr'] = "class='text-right'";

                $specs['kredit']['value'] = isset($extrDatasSum[$pId]['kredit']) ? $extrDatasSum[$pId]['kredit'] : 0;
                $specs['kredit']['attr'] = "class='text-right'";
            }
            //endregion

            //region bom
            if (sizeof($jenisBom) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_bom']['value'] = isset($bomDatasSum[$pId]['qty_kredit']) ? $bomDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_bom']['attr'] = "class='text-right'";

                    if ((isset($bomDatasSum[$pId]['qty_kredit'])) && ($bomDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $bomDatasSum[$pId]['kredit'] / $bomDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_bom']['value'] = isset($bomDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_bom']['attr'] = "class='text-right'";

                    $specs['kredit_bom']['value'] = isset($bomDatasSum[$pId]['kredit']) ? $bomDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_bom']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //region notgood
            if (sizeof($jenisNG) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_ng']['value'] = isset($ngDatasSum[$pId]['qty_kredit']) ? $ngDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_ng']['attr'] = "class='text-right'";

                    if ((isset($ngDatasSum[$pId]['qty_kredit'])) && ($ngDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $ngDatasSum[$pId]['kredit'] / $ngDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_ng']['value'] = isset($ngDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_ng']['attr'] = "class='text-right'";

                    $specs['kredit_ng']['value'] = isset($ngDatasSum[$pId]['kredit']) ? $ngDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_ng']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //region biayakan
            if (sizeof($jenisBiaya) > 0) {
                $on = 1;
                if ($on == 1) {

                    $specs['qty_kredit_exp']['value'] = isset($expDatasSum[$pId]['qty_kredit']) ? $expDatasSum[$pId]['qty_kredit'] : 0;
                    $specs['qty_kredit_exp']['attr'] = "class='text-right'";

                    if ((isset($expDatasSum[$pId]['qty_kredit'])) && ($expDatasSum[$pId]['qty_kredit'] > 0)) {
                        $harga_satuan = $expDatasSum[$pId]['kredit'] / $expDatasSum[$pId]['qty_kredit'];
                    }
                    else {
                        $harga_satuan = 0;
                    }
                    $specs['harga_kredit_exp']['value'] = isset($expDatas[$pId]['harga']) ? $harga_satuan : 0;
                    $specs['harga_kredit_exp']['attr'] = "class='text-right'";

                    $specs['kredit_exp']['value'] = isset($expDatasSum[$pId]['kredit']) ? $expDatasSum[$pId]['kredit'] : 0;
                    $specs['kredit_exp']['attr'] = "class='text-right'";
                }
            }
            //endregion

            //endregion


            //region ending

            $qtyDebetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['debet_akhir'] : 0;

            $specs['qty_akhir']['value'] = isset($allDataAkhir[$pId]['qty_debet_akhir']) ? $allDataAkhir[$pId]['qty_debet_akhir'] : $qtyDebetAkhirLast;
            $specs['qty_akhir']['attr'] = "class='text-right'";

            if (isset($allDataAkhir[$pId]['qty_debet_akhir'])) {
                if ($allDataAkhir[$pId]['qty_debet_akhir'] > 0) {
                    //                $harga_satuan = $allDataAkhir[$pId]['harga_akhir'] * 1;
                    $harga_satuan = $allDataAkhir[$pId]['debet_akhir'] / $allDataAkhir[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }
            else {
                if ((isset($allDatas2[$pId]['qty_debet_akhir'])) && ($allDatas2[$pId]['qty_debet_akhir'] > 0)) {
                    $harga_satuan = $allDatas2[$pId]['debet_akhir'] / $allDatas2[$pId]['qty_debet_akhir'];
                }
                else {
                    $harga_satuan = 0;
                }
            }

            // $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $allDataAkhir[$pId]['harga_akhir'] : 0;
            //            $specs['harga_akhir']['value'] = isset($allDataAkhir[$pId]['harga_akhir']) ? $harga_satuan : "**";
            $specs['harga_akhir']['value'] = $harga_satuan;
            $specs['harga_akhir']['attr'] = "class='text-right'";

            // $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : 0;
            $debetAkhirLast = isset($allDatas2[$pId]) ? $allDatas2[$pId]['harga_avg'] * $allDatas2[$pId]['qty_debet_akhir'] : 0;
            $specs['akhir']['value'] = isset($allDataAkhir[$pId]['debet_akhir']) ? $allDataAkhir[$pId]['debet_akhir'] : $debetAkhirLast;
            $specs['akhir']['attr'] = "class='text-right'";

            //endregion


            $bodies[] = $specs;

            // if(!isset($sumvalues[]))
        }
        //endregion


        // region footers
        $footers = array(
            "total" => "colspan='3' class='text-right bg-info text-uppercase'",
        );
        // endregion footers


        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField_he_format("dtime", $date1) . " s/d " . formatField_he_format("dtime", $date2);
        $dateRange = "($dRange)";

        $strO = isset($_GET['o']) ? "&o=" . $_GET['o'] : "";
        $strDate1 = isset($_GET['date1']) ? "&date1=" . $_GET['date1'] : "";

        $btnGroups = array(
            // "all"      => array(
            //     "label" => "All Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=all" . $strO . $strDate1,
            // ),
            // "external" => array(
            //     "label" => "external Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=external" . $strO . $strDate1,
            // ),
            // "internal" => array(
            //     "label" => "internal Movement",
            //     "link"  => "Ledger/viewMovementGroup/fg?mv=internal" . $strO . $strDate1,
            // ),
        );
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode"     => "movementGroup",
            "title"    => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1"    => $date1,
            "date2"    => $date2,

            "rowLoop"          => 6,
            "mainHeaders"      => $mainHeaders,
            "subHeaders"       => $subHeaders,
            "bodies"           => $bodies,
            "footers"          => $footers,
            "sumfooters"       => $arrSumView,
            "footersBlacklist" => array("no", "id", "kode", "nama"),
            "btnGroups"        => $btnGroups,
            "mdlFields"        => isset($mdlNameS[$item]['mdlFields']) ? $mdlNameS[$item]['mdlFields'] : array(),
            "alerter"          => $alerter,
            "detailsLabels"    => "",
            "thisPage"         => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovement()
    {
        $tbl_src = "__rek_pembantu_produk__persediaan_produk";
        $tbl_hr = "__rek_pembantu_produk__persediaan_produk_hr";
        $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl";
        // $tbl_bl = "__rek_pembantu_produk__persediaan_produk_bl_external";
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date = $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        $list_produkId = "";
        // $list_produkId = "76,386,80,34,36,37,38,39";
        // $list_produkId = "76,8";
        // $list_produkId = "102";

        $bl = formatTanggal($date, "n");
        $th = formatTanggal($date, "Y");
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            "bln"       => $bl,
            "thn"       => $th,
        );
        if (strlen($list_produkId) > 1) {

            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        $this->db->where($arrWhere);
        $srcBl = $this->db->get($tbl_bl)->result();
        // showLast_query("lime");
        // arrPrint($srcBl);
        // $srcDatas = sizeof($srcBl) > 0 ? $srcBl[0] : array();

        // if (sizeof($srcBl) == 0) {
        $arrWhere = array(
            // "extern_id" => $src_extern_id,
            "cabang_id" => $cabangID,
            // "gudang_id" => $src_gudang_id,
            // "bln"       => $bl,
            // "thn"       => $th,
            "dtime <"   => "$th-$bl-1",
        );
        if (strlen($list_produkId) > 1) {
            $this->db->where_in("extern_id", explode(",", $list_produkId));
        }
        // $this->db->limit(1);
        $this->db->order_by("id", "asc");

        $this->db->where($arrWhere);
        $srcBlLast = $this->db->get($tbl_bl)->result();
        // showLast_query("merah");
        // arrPrintWebs($srcBlLast);
        foreach ($srcBlLast as $srcSpecs) {
            $dataSource2[$srcSpecs->extern_id] = (array)$srcSpecs;
        }
        // }


        $srcDatas = sizeof($srcBl) > 0 ? $srcBl : array();

        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            if (strlen($list_produkId) > 1) {
                $mdl->addFilter("id in ($list_produkId)");
            }
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // limit untuk proses debuging saja
        //  $mdl->addFilter("id='41'");
        // $mdl->addFilter("id='80'");
        //         $this->db->limit(10);

        $produks = $mdl->lookupAll()->result();

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        foreach ($srcDatas as $srcSpecs) {
            $dataSource[$srcSpecs->extern_id] = (array)$srcSpecs;
        }


        // arrPrint($arrQtyHarga);
        // matiHere();
        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {

            // arrPrint($pSpecs);
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            $debet = "**";
            // arrPrintWebs($pSpecs);
            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion

            // arrPrint($dataSource);
            $dataMutasi = isset($dataSource[$pId]) ? $dataSource[$pId] : array();


            if (isset($dataSource[$pId])) {
                $harga_debet_awal = isset($dataSource[$pId]) && $dataSource[$pId]['qty_debet_awal'] > 0 ? $dataSource[$pId]['qty_debet_awal'] . "x&nbsp;" . formatField_he_format("number", $dataSource[$pId]['harga_awal'] * 1) : "-";
                $dataMutasi["harga_debet_awal"] = $harga_debet_awal;
            }
            else {
                // if (!isset($dataSource[$pId])) {
                // matiHere();
                // arrPrint($dataSource2);
                $harga_debet_awal = isset($dataSource2[$pId]) && $dataSource2[$pId]['qty_debet_akhir'] > 0 ? $dataSource2[$pId]['qty_debet_akhir'] . "x&nbsp;" . formatField_he_format("number", $dataSource2[$pId]['harga'] * 1) : "-";
                $dataMutasi["harga_debet_awal"] = isset($harga_debet_awal) ? $harga_debet_awal : 0;
                $dataMutasi["qty_debet_awal"] = isset($dataSource2[$pId]['qty_debet_akhir']) ? $dataSource2[$pId]['qty_debet_akhir'] : 0;
                $dataMutasi["debet_awal"] = isset($dataSource2[$pId]['debet_akhir']) ? $dataSource2[$pId]['debet_akhir'] : 0;
            }


            $harga_debet = isset($dataSource[$pId]) && $dataSource[$pId]['qty_debet'] > 0 ? $dataSource[$pId]['qty_debet'] . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            $dataMutasi["harga_debet"] = $harga_debet;
            if (isset($dataSource[$pId])) {
                $qty_avail = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_debet_awal'] + $dataSource[$pId]['qty_debet'] : 0;
                $harga_avail = isset($dataSource[$pId]) && $qty_avail > 0 ? $qty_avail . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            }
            else {
                $qty_avail = isset($dataSource2[$pId]) ? $dataSource2[$pId]['qty_debet_awal'] + $dataSource2[$pId]['qty_debet'] : 0;
                $harga_avail = isset($dataSource2[$pId]) && $qty_avail > 0 ? $qty_avail . "x&nbsp;" . number_format($dataSource2[$pId]['harga'] * 1, "0", "", ",") : "-";
            }

            $dataMutasi["harga_avail"] = $harga_avail;

            $harga_kredit = isset($dataSource[$pId]) && $dataSource[$pId]['qty_kredit'] > 0 ? $dataSource[$pId]['qty_kredit'] . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
            $dataMutasi["harga_kredit"] = $harga_kredit;

            // $qty_akhir = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_akhir'] + $dataSource[$pId]['qty_debet'] :0;
            // if (sizeof($dataSource) > 0) {
            if (isset($dataSource[$pId])) {
                $qty_akhir = isset($dataSource[$pId]) ? $dataSource[$pId]['qty_debet_akhir'] : 0;
                $harga_akhir = isset($dataSource[$pId]) && $qty_akhir > 0 ? $qty_akhir . "x&nbsp;" . number_format($dataSource[$pId]['harga'] * 1, "0", "", ",") : "-";
                $dataMutasi["harga_akhir"] = $harga_akhir;
            }
            else {
                $qty_akhir = isset($dataSource2[$pId]) ? $dataSource2[$pId]['qty_debet_akhir'] : 0;
                $harga_akhir = isset($dataSource2[$pId]) && $qty_akhir > 0 ? $qty_akhir . "x&nbsp;" . number_format($dataSource2[$pId]['harga'] * 1, "0", "", ",") : "-";
                $dataMutasi["harga_akhir"] = $harga_akhir;
                $dataMutasi["debet_akhir"] = $dataSource2[$pId]['debet_akhir'];
            }

            // arrPrint($mutasiKoloms);
            // cekMerah($pId . __METHOD__ . __LINE__);
            // arrPrint($dataMutasi);

            foreach ($mutasiKoloms as $mutasiKolom_0) {

                $mutasiKolom = isset($mutasiKolom_0) ? $mutasiKolom_0 : "--";
                $qtyDebetAwal = isset($dataMutasi['qty_debet_awal']) ? $dataMutasi['qty_debet_awal'] : 0;

                $debetAwal = isset($dataMutasi['debet_awal']) ? $dataMutasi['debet_awal'] : 0;
                $hargaAkhir = isset($dataMutasi['harga_akhir']) ? $dataMutasi['harga_akhir'] : 0;
                $debetAkhir = isset($dataMutasi['debet_akhir']) ? $dataMutasi['debet_akhir'] : 0;

                $qtyDebet = isset($dataMutasi['qty_debet']) ? $dataMutasi['qty_debet'] : 0;
                $qtyKredit = isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0;


                if (($mutasiKolom == "debet_awal") && ($qtyDebetAwal == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "debet") && ($qtyDebet == 0)) {
                    $val2 = 0;
                    $val2_debet = 0;
                }
                elseif (($mutasiKolom == "qty_avail")) {
                    $val2 = $qtyDebetAwal + $qtyDebet;
                }
                elseif (($mutasiKolom == "avail")) {
                    $val2 = $debetAwal + (($mutasiKolom == "debet") && ($qtyDebet == 0)) ? 0 : $debet;
                }
                elseif (($mutasiKolom == "kredit") && ((isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0) == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "qty_akhir")) {
                    $val2 = $qtyDebetAwal + $qtyDebet - $qtyKredit;
                }
                elseif (($mutasiKolom == "akhir")) {
                    // $val2 = number_format($debetAkhir, "0", ".", ",");
                    $val2 = $debetAkhir;
                }
                else {
                    $val2 = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : 0;
                }
                // $specs[$mutasiKolom]['value'] = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : "--";
                $specs[$mutasiKolom]['value'] = $val2;
                // $specs[$mutasiKolom]['value'] = "**";
                $specs[$mutasiKolom]['attr'] = "class='text-right'";

                // cekHijau("$mutasiKolom ** $val2");
                if (!isset($sumQty[$mutasiKolom])) {
                    $sumQty[$mutasiKolom] = 0;
                }
                $sumQty[$mutasiKolom] += $val2;
            }

            $bodies[] = $specs;
        }
        //endregion

        // arrPrint($sumQty);
        // region footers
        $footers = array(
            "total"                                                            => "colspan='3' class='text-right bg-info text-uppercase'",
            formatField_he_format("qty_debet_awal", $sumQty['qty_debet_awal']) => "class='text-right bg-info text-uppercase'",
            "-"                                                                => "class='text-right bg-info text-uppercase'",
            formatField_he_format("debet_awal", $sumQty['debet_awal'])         => "class='text-right bg-info text-uppercase'",

            formatField_he_format("qty_debet", $sumQty['qty_debet']) => "class='text-right bg-info text-uppercase'",
            "--"                                                     => "class='text-right bg-info text-uppercase'",
            formatField_he_format("debet", $sumQty['debet'])         => "class='text-right bg-info text-uppercase'",

            formatField_he_format("qty_avail", $sumQty['qty_avail']) => "class='text-right bg-info text-uppercase'",
            "."                                                      => "class='text-right bg-info text-uppercase'",
            formatField_he_format("avail", $sumQty['avail'])         => "class='text-right bg-info text-uppercase'",

            formatField_he_format("qty_kredit", $sumQty['qty_kredit']) => "class='text-right bg-info text-uppercase'",
            ".."                                                       => "class='text-right bg-info text-uppercase'",
            formatField_he_format("kredit", $sumQty['kredit'])         => "class='text-right bg-info text-uppercase'",

            formatField_he_format("qty_akhir", $sumQty['qty_akhir']) => "class='text-right bg-info text-uppercase'",
            ""                                                       => "class='text-right bg-info text-uppercase'",
            formatField_he_format("akhir", $sumQty['akhir'])         => "class='text-right bg-info text-uppercase'",
        );
        // endregion footers
        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        // $dRange = formatField("dtime", $date1) . " s/d " . formatField("dtime", $date2);
        $lastDay = formatTanggal($date1, "t");
        $date2 = "$th-$bl-$lastDay";
        $dRange = formatField_he_format("dtime", $date1) . " s/d " . formatField_he_format("dtime", $date2);
        $dateRange = "($dRange)";
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        // <span class='blink meta'>save mode data terlimit</span>
        $data = array(
            "mode"     => "movement",
            "title"    => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1"    => $date1,
            "date2"    => $date2,

            "rowLoop"     => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders"  => $subHeaders,
            "bodies"      => $bodies,
            "footers"     => $footers,
            "sumfooters"  => $sumQty,

            "detailsLabels" => "",
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);
    }

    public function viewMovement_v2()
    {
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        function nanMovement($qty, $value)
        {
            if (($qty <= 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty == 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty > 0) && ($value <= 0)) {
                $var = "Nan";
            }
            else {
                $var = $value;
            }

            return $var;
        }

        // $mdl = new MdlProduk();
        // $com = new ComRekeningPembantuProduk();

        $list_produkId = "";
        // $list_produkId = "76,386";
        // $list_produkId = "76,80";
        $bKoloms = array(
            "extern_id",
            "debet_awal",
            "debet",
            "kredit",
            "debet_akhir",
            // "kredit_awal",
            // "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_kredit",
            // "qty_kredit_akhir",
            // "qty_kredit_awal",
            "qty_debet_akhir",
            "harga",
            "harga_avg",
        );
        if (strlen($list_produkId) > 1) {
            $com->addFilter("extern_id in ($list_produkId)");
        }

        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->addFilter("gudang_id='" . $this->gudangID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpBegin = $com->fetchMovement($rekening);
        // cekBiru($this->db->last_query() . "<hr> row:: " . sizeof($tmpBegin));
        // arrPrint($tmpBegin);

        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) <= '$date2'");
        $tmpUnbegin_0 = $com->fetchMovement($rekening);
        $tmpUnbegin = array();
        foreach ($tmpUnbegin_0 as $itemSpecs) {
            $ubExtern_id = $itemSpecs->extern_id;
            $tmpUnbegin[$ubExtern_id] = $itemSpecs;
        }
        // cekMerah($this->db->last_query() . " <hr> row:: " . $comName . " ===== " . sizeof($tmpUnbegin));


        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpRange = $com->fetchMovement($rekening);
        // cekHijau($this->db->last_query() . " <hr>row:: " . $comName . " ====== " . sizeof($tmpRange));

        // arrPrint($tmpBegin);
        // arrPrintWebs($tmpUnbegin);
        // arrPrint($tmpRange);

        // $mdl->addFilter("jenis='" . "item_rakitan" . "'");
        // $pro->setFilters(array());
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            if (strlen($list_produkId) > 1) {
                $mdl->addFilter("id in ($list_produkId)");
            }
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // limit untuk proses debuging saja
        //  $mdl->addFilter("id='41'");
        // $mdl->addFilter("id='80'");
        //         $this->db->limit(10);

        $produks = $mdl->lookupAll()->result();
        // cekLime($this->db->last_query() . "<hr>$item ---- $mdlName");

        // arrPrint($produks);
        // arrPrint($mdlFields);
        // arrPrint($fieldToshows);
        // arrPrint($tmpBegin);
        // matiHere(__LINE__);

        /*---------------------------
         * mutasi tanpa batasan tanggal mulai
         * ------------------------*/
        // arrPrint($tmpUnbegin);
        $arrMutasi_3 = array();
        foreach ($tmpUnbegin as $rDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }
            //cekHere(":: $extern_id :: $qty_debet_akhir ::");
            //            $arrMutasi_3[$extern_id]['harga_debet_awal'] = number_format($harga, "0", ".", ",");
            $arrMutasi_3[$extern_id]['qty_debet_awal'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_debet'] = 0;
            $arrMutasi_3[$extern_id]['qty_kredit'] = 0;
            $arrMutasi_3[$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_harga_debet'] = 0;
            if ($qty_debet_akhir > 0) {
                if (!isset($arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga])) {
                    $arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga] = 0;
                }
                $arrMutasi_3[$extern_id]['qty_harga_debet_awal'][$harga] += $qty_debet_akhir;
            }

            $arrMutasi_3[$extern_id]['debet_awal'] = $debet_akhir;
            $arrMutasi_3[$extern_id]['debet'] = $debet;
            $arrMutasi_3[$extern_id]['kredit'] = $kredit;
            $arrMutasi_3[$extern_id]['debet_akhir'] = $debet_akhir;

            if ($qty_kredit > 0) {
                if (!isset($arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga])) {
                    $arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga] = 0;
                }
                $arrMutasi_3[$extern_id]['qty_harga_kredit'][$harga] += $qty_kredit;
            }
            // $arrMutasi_3[$produk_id]['on_hpp'] = $on_hpp;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp_3 = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                //                 cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                // $on_hpp_3 = ($kredit + $debet);
                $on_hpp_3 = ($kredit + $debet);
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            // if (($qty_debet + $qty_kredit) > 0) {
            //     $on_hpp_3 = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            // }
            // else {
            //     $on_hpp_3 = ($kredit + $debet);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet);
            // }
            if (!isset($arrMutasi_3[$extern_id]['on_hpp'])) {
                $arrMutasi_3[$extern_id]['on_hpp'] = 0;
            }
            $arrMutasi_3[$extern_id]['on_hpp'] += "999" . $on_hpp_3;
        }
        //         arrPrint($tmpUnbegin);
        //         arrPrint($arrMutasi_3);
        /*---------------------------
         * data stok awal dan akhir
         * $arrBeAf
         * ------------------------*/
        $spec = array();
        foreach ($tmpBegin as $bDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $bDatas->$bKolom;
            }
            $spec['qty_debet_awal'] = $qty_debet_awal;
            $spec['qty_debet_akhir'] = $qty_debet_akhir;
            if (($qty_debet + $qty_kredit) > 0) {
                $spec['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            }
            else {
                $spec['on_hpp'] = ($kredit + $debet);
            }
            $arrBeAf[$extern_id][] = $spec;
        }
        // arrPrintWebs($arrBeAf);
        //         arrPrint($tmpRange);
        /*---------------------------
         * mutasi dengan range tanggal
         * ------------------------*/
        $arrMutasi = array();
        $no = 0;
        //arrPrint($tmpRange);
        foreach ($tmpRange as $rDatas) {
            $no++;
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }
            $harga = $harga_avg;
            $jml_be = $arrBeAf[$extern_id][0]["qty_debet_awal"];
            //            cekHere(":: $jml_be ::");
            $arrEnd = end($arrBeAf[$extern_id])["qty_debet_akhir"];
            if ($jml_be > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = $jml_be;
            }
            // else{
            //     $arrMutasi[$extern_id]['qty_harga_debet_awal'][$harga] = "**";
            // }
            $arrMutasi[$extern_id]['qty_debet_awal'] = $jml_be;
            if (!isset($arrMutasi[$extern_id]['qty_debet'])) {
                $arrMutasi[$extern_id]['qty_debet'] = 0;
            }
            $arrMutasi[$extern_id]['qty_debet'] += $qty_debet;
            if ($qty_debet > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_debet'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_debet'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_debet'][$harga] += $qty_debet;
            }
            // else{
            //     $arrMutasi[$extern_id]['qty_harga_debet'][$harga] = 0;
            // }
            if (!isset($arrMutasi[$extern_id]['qty_kredit'])) {
                $arrMutasi[$extern_id]['qty_kredit'] = 0;
            }
            $arrMutasi[$extern_id]['qty_kredit'] += $qty_kredit;
            if ($qty_kredit > 0) {
                if (!isset($arrMutasi[$extern_id]['qty_harga_kredit'][$harga])) {
                    $arrMutasi[$extern_id]['qty_harga_kredit'][$harga] = 0;
                }
                $arrMutasi[$extern_id]['qty_harga_kredit'][$harga] += $qty_kredit;
            }
            $arrMutasi[$extern_id]['qty_debet_akhir'] = $arrEnd;
            $arrMutasi[$extern_id]['debet_awal'] = $jml_be * $harga;
            if (!isset($arrMutasi[$extern_id]['debet'])) {
                $arrMutasi[$extern_id]['debet'] = 0;
            }
            $arrMutasi[$extern_id]['debet'] += $debet;
            if (!isset($arrMutasi[$extern_id]['kredit'])) {
                $arrMutasi[$extern_id]['kredit'] = 0;
            }
            $arrMutasi[$extern_id]['kredit'] += $kredit;
            $arrMutasi[$extern_id]['debet_akhir'] = $debet_akhir;
            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp = ($kredit + $debet) / $on_qty;
            }
            else {
                $on_hpp = ($kredit + $debet);
            }
            if (!isset($hppId[$extern_id])) {
                $hppId[$extern_id] = 0;
            }
            $hppId[$extern_id] += $on_hpp;
            if (!isset($qtyId[$extern_id])) {
                $qtyId[$extern_id] = 0;
            }
            $qtyId[$extern_id] += $on_qty;
            $arrMutasi[$extern_id]['on_hpp'] = 88;
        }
        // arrPrintWebs($tmpRange);
        //         arrPrintWebs($arrMutasi);
        //         arrPrintWebs($qtyId);
        $jml_row = sizeof($tmpRange);
        foreach ($qtyId as $extern_id_2 => $sumQty) {
            // $hpp_avg = $hppId[$extern_id_2];
            // if($sumQty > 0){
            //     $hpp_avg = $hppId[$extern_id_2] / $sumQty;
            // }
            // else{
            $hpp_avg = $hppId[$extern_id_2];
            // }

            // cekLime("$extern_id_2 :: $hpp_avg / $sumQty");
            $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = "99".$hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg / $sumQty;
        }

        // $arrMutasi[$extern_id]['on_hpp'] = 0;

        // arrPrint($arrMutasi);

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion

        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // arrPrint($arrMutasi);
        // mati_disini();
        $mutasiKoloms = array(
            "qty_debet_awal",
            "harga_debet_awal",
            "debet_awal",

            "qty_debet",
            "harga_debet",
            "debet",

            "qty_avail",
            "harga_avail",
            "avail",

            "qty_kredit",
            "harga_kredit",
            "kredit",

            "qty_akhir",
            "harga_akhir",
            "akhir",
        );
        $specs = array();
        $bodies = array();
        $no = 0;

        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {
            // arrPrint($pSpecs);
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            if (isset($arrMutasi[$pId])) {
                $dataMutasi = $arrMutasi[$pId];
                // cekHitam(__LINE__);
            }
            elseif (isset($arrMutasi_3[$pId])) {
                // cekKuning(__LINE__);
                $dataMutasi = $arrMutasi_3[$pId];
            }
            else {
                // cekMerah(__LINE__);
                $dataMutasi = array();
            }

            //             cekHijau("id $pId");
            //             arrPrint($dataMutasi);
            // arrPrint($arrMutasi[$pId]);

            //region begining balance

            //            arrPrint($arrMutasi);
            //            arrPrint($arrMutasi_3);
            //            cekMerah($pId);
            //
            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_debet_awal'])) {
                $jmlAwal = count($arrMutasi[$pId]['qty_harga_debet_awal']);
                // arrPrint($ee);
                $cek = 0;
                foreach ($arrMutasi[$pId]['qty_harga_debet_awal'] as $hargaItem => $jmlItem) {
                    $cek++;
                    cekBiru($cek);
                    $hargaItem = 1 * $hargaItem;
                    if ($cek == 1) {

                        $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }

                }
            }
            elseif (isset($arrMutasi_3[$pId]['qty_harga_debet_awal'])) {
                $jmlAwal = count($arrMutasi_3[$pId]['qty_harga_debet_awal']);
                // arrPrint($ee);
                $cek = 0;
                foreach ($arrMutasi_3[$pId]['qty_harga_debet_awal'] as $hargaItem => $jmlItem) {
                    $cek++;
                    $hargaItem = 1 * $hargaItem;
                    //                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    $var = $arrMutasi_3[$pId]['qty_debet_awal'] . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                }
            }
            else {
                //                cekMerah(":: [$pId] ::");
                $hasil = "-";
                //                $arrMutasi[$pId]['qty_harga_debet_awal'] = array();
            }


            $dataMutasi['harga_debet_awal'] = $hasil;
            if ($dataMutasi["qty_debet_awal"] == 0) {
                $dataMutasi['harga_debet_awal'] = "-";
            }

            // $dataMutasi['harga_debet_awal'] = 321;
            //arrPrint($dataMutasi);
            //endregion begining balance


            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_debet'])) {
                // cekHijau();
                foreach ($arrMutasi[$pId]['qty_harga_debet'] as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;

                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
                // cekHijau("jj" .$hasil);
            }
            //            elseif (isset($arrMutasi_3[$pId]['qty_harga_debet'])) {
            //                // cekHijau();
            //                foreach ($arrMutasi_3[$pId]['qty_harga_debet'] as $hargaItem => $jmlItem) {
            //                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
            //                    $hargaItem = 1 * $hargaItem;
            //
            //                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
            //                    $var = $arrMutasi_3[$pId]['qty_debet'] . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
            //                    if ($hasil == "") {
            //                        $hasil .= "$var";
            //                    }
            //                    else {
            //                        $hasil = "$hasil<br>$var";
            //                    }
            //                    // }
            //                }
            //                // cekHijau("jj" .$hasil);
            //            }
            else {
                $hasil = 0;
                $arrMutasi[$pId]['qty_harga_debet'] = array();
            }
            //            cekMerah("qty_harga_debet");
            //             cekMerah($hasil);
            $dataMutasi['harga_debet'] = $hasil;
            // arrPrintWebs($dataMutasi);


            // arrPrint($arrMutasi[$pId]['qty_harga_debet_awal']);
            // arrPrintWebs($arrMutasi[$pId]['qty_harga_debet']);
            // matiHere(__LINE__);
            // region harga available
            if ((sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0) && (sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0)) {
                // matiHere(__LINE__);
                // $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            }
            elseif (sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0) {

                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'];
            }
            elseif (sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0) {
                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet'];
            }
            else {
                $arrHargaAvail = array();
            }
            // arrPrint($arrHargaAvail);
            // matiHere(__LINE__);

            $hasil = "";
            if (isset($arrHargaAvail)) {
                foreach ($arrHargaAvail as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;

                    // $var = $jmlItem . "x&nbsp;@$hargaItem";
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
            }
            else {
                $hasil = 0;
            }
            $dataMutasi['harga_avail'] = $hasil;
            if ($dataMutasi["qty_debet_awal"] > 0 && $dataMutasi["qty_debet"] == 0) {

                $dataMutasi['harga_avail'] = $dataMutasi['harga_debet_awal'];
            }
            // endregion harga available


            $hasil = "";
            if (isset($arrMutasi[$pId]['qty_harga_kredit'])) {
                foreach ($arrMutasi[$pId]['qty_harga_kredit'] as $hargaItem => $jmlItem) {
                    // foreach ($hargaItems as $hargaItem => $jmlItem) {
                    $hargaItem = 1 * $hargaItem;
                    $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                    // $qty = $items
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                    // }
                }
            }
            else {
                $hasil = "-";
            }
            $dataMutasi['harga_kredit'] = $hasil;


            //region harga akhir
            // $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            //            if(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'] + $arrMutasi[$pId]['qty_harga_debet'];
            //            }
            //            elseif(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) > 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) == 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet_awal'];
            //            }
            //            elseif(sizeof($arrMutasi[$pId]['qty_harga_debet_awal']) == 0 && sizeof($arrMutasi[$pId]['qty_harga_debet']) > 0){
            //                $arrHargaAvail = $arrMutasi[$pId]['qty_harga_debet'];
            //            }
            //            else{
            //                $arrHargaAvail = array();
            //            }
            //            arrPrint($arrHargaAvail);
            $hasil = "";
            if (isset($arrHargaAvail)) {
                foreach ($arrHargaAvail as $hargaItem => $jmlItem) {
                    //                    $jmlItem=0;
                    if (isset($arrMutasi[$pId]['qty_harga_kredit'])) {

                        foreach ($arrMutasi[$pId]['qty_harga_kredit'] as $hargaItem2 => $jmlItem2) {
                            if ($hargaItem == $hargaItem2) {
                                $jmlItem = $jmlItem - $jmlItem2;
                            }
                        }
                    }

                    if ($jmlItem > 0) {
                        $var = $jmlItem . "x&nbsp;&nbsp;" . number_format($hargaItem, "0", ".", ",");
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }
                    else {
                        $hasil = 0;
                    }
                }
            }
            else {
                $hasil = 0;
            }

            $dataMutasi['harga_akhir'] = $hasil;
            if ($dataMutasi["qty_debet_akhir"] > 0 && $dataMutasi["qty_debet"] == 0 && $dataMutasi["qty_kredit"] == 0) {
                $dataMutasi['harga_akhir'] = $dataMutasi["harga_debet_awal"];
            }

            //endregion harga akhir

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            //region data produk
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }
            //endregion

            // cekLime("pID:: $pId");
            // arrPrint($dataMutasi);
            // if (sizeof($dataMutasi) > 0) {
            //     $qty_be = $dataMutasi['qty_debet_awal'];
            //     $qty_in = $dataMutasi['qty_debet'];
            //     $qty_ot = $dataMutasi['qty_kredit'];
            //     $qty_af = $dataMutasi['qty_debet_akhir'];
            //     $harga = $dataMutasi['on_hpp'] * 1;
            //
            //     // $amount_be = $dataMutasi['debet_awal'];
            //     // $amount_in = $dataMutasi['debet'];
            //     // $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
            //     // $amount_ot = $dataMutasi['kredit'];
            //     // $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $dataMutasi['kredit'];
            // }
            // else {
            //     $qty_be = 0;
            //     $qty_in = 0;
            //     $qty_ot = 0;
            //     $qty_af = 0;
            //     $harga = 0;
            // }

            // arrPrint($mutasiKoloms);
            // cekMerah($pId);
            //            arrPrint($dataMutasi);

            foreach ($mutasiKoloms as $mutasiKolom_0) {

                $mutasiKolom = isset($mutasiKolom_0) ? $mutasiKolom_0 : "--";
                $qtyDebetAwal = isset($dataMutasi['qty_debet_awal']) ? $dataMutasi['qty_debet_awal'] : 0;

                $debetAwal = isset($dataMutasi['debet_awal']) ? $dataMutasi['debet_awal'] : 0;
                $hargaAkhir = isset($dataMutasi['harga_akhir']) ? $dataMutasi['harga_akhir'] : 0;
                $debetAkhir = isset($dataMutasi['debet_akhir']) ? $dataMutasi['debet_akhir'] : 0;

                $qtyDebet = isset($dataMutasi['qty_debet']) ? $dataMutasi['qty_debet'] : 0;
                $qtyKredit = isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0;


                if (($mutasiKolom == "debet_awal") && ($qtyDebetAwal == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "debet") && ($qtyDebet == 0)) {
                    $val2 = 0;
                    $val2_debet = 0;
                }
                elseif (($mutasiKolom == "qty_avail")) {
                    $val2 = $qtyDebetAwal + $qtyDebet;
                }
                elseif (($mutasiKolom == "avail")) {
                    $val2 = $debetAwal + (($mutasiKolom == "debet") && ($qtyDebet == 0)) ? 0 : $debet;
                }
                elseif (($mutasiKolom == "kredit") && ((isset($dataMutasi['qty_kredit']) ? $dataMutasi['qty_kredit'] : 0) == 0)) {
                    $val2 = 0;
                }
                elseif (($mutasiKolom == "qty_akhir")) {
                    $val2 = $qtyDebetAwal + $qtyDebet - $qtyKredit;
                }
                elseif (($mutasiKolom == "akhir")) {
                    $val2 = number_format($debetAkhir, "0", ".", ",");
                }
                else {
                    $val2 = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : 0;
                }
                // $specs[$mutasiKolom]['value'] = isset($dataMutasi[$mutasiKolom]) ? $dataMutasi[$mutasiKolom] : "--";
                $specs[$mutasiKolom]['value'] = $val2;
                // $specs[$mutasiKolom]['value'] = "**";
                $specs[$mutasiKolom]['attr'] = "class='text-right'";
            }

            $bodies[] = $specs;
        }
        //endregion

        // region footers
        $footers = array();
        // endregion footers
        //         arrPrint($bodies);
        // matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        $dRange = formatField_he_format("dtime", $date1) . " s/d " . formatField_he_format("dtime", $date2);
        $dateRange = "($dRange)";
        //        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        $data = array(
            "mode"     => "movement",
            "title"    => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1"    => $date1,
            "date2"    => $date2,

            "rowLoop"     => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders"  => $subHeaders,
            "bodies"      => $bodies,
            "footers"     => $footers,

            "detailsLabels" => "",
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }

    public function viewMovement_ori()
    {
        $this->load->config("heTransaksi_report");
        $item = $this->uri->segment(3);
        $cabangID = $this->placeID;
        $date1 = isset($_GET['date1']) ? $_GET['date1'] : dtimeNow("Y-m") . "-01";
        $date2 = isset($_GET['date2']) ? $_GET['date2'] : dtimeNow("Y-m-d");

        $mdlNameS = $this->config->item('heMovement') ? $this->config->item('heMovement') : array();

        // cekHitam($item);
        // arrPrint($mdlNameS[$item]);
        $comName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['com'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        // $itemLabel = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $itemLabel = isset($mdlNameS[$item]['label']) ? $mdlNameS[$item]['label'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mdlName = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['mdl'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $rekening = key_exists($item, $mdlNameS) ? $mdlNameS[$item]['rek'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $comName);
        $this->load->model("Mdls/" . $mdlName);
        $com = new $comName();
        // $com = new ComRekeningPembantuProduk();
        $mdl = new $mdlName();
        $mdlFields = key_exists($item, $mdlNameS) ? array_keys($mdlNameS[$item]['mdlFields']) : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");

        foreach ($mdlNameS[$item]['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }

        // arrPrint($fieldToshows);
        //region penyusun header table nganbil dari config
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        $mainHeaders_1 = isset($mdlNameS[$item]['mainHeaders_1']) ? $mdlNameS[$item]['mainHeaders_1'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        $mainHeaders = array_merge($mainHeaders_0, $mainHeaders_1);
        $subHeaders = isset($mdlNameS[$item]['subHeaders']) ? $mdlNameS[$item]['subHeaders'] : mati_disini(__LINE__ . " " . __FILE__ . " cekThis");
        //endregion

        function nanMovement($qty, $value)
        {
            if (($qty <= 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty == 0) && ($value > 0)) {
                $var = "Nan";
            }
            elseif (($qty > 0) && ($value <= 0)) {
                $var = "Nan";
            }
            else {
                $var = $value;
            }

            return $var;
        }

        // $mdl = new MdlProduk();
        // $com = new ComRekeningPembantuProduk();
        $bKoloms = array(
            "extern_id",
            "debet_awal",
            "debet",
            "kredit",
            "debet_akhir",
            // "kredit_awal",
            // "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_kredit",
            // "qty_kredit_akhir",
            // "qty_kredit_awal",
            "qty_debet_akhir",
            "harga",
            "harga_avg",
        );
        // $com->addFilter("extern_id='" . 328 . "'");
        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->addFilter("gudang_id='" . $this->gudangID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpBegin = $com->fetchMovement($rekening);
        // cekBiru($this->db->last_query());

        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) <= '$date2'");
        $tmpUnbegin = $com->fetchMovement($rekening);
        // cekMerah($this->db->last_query()) . " <hr>". $comName;
        // $com->addFilter("extern_id='" . 315 . "'");
        $com->addFilter("cabang_id='" . $this->placeID . "'");
        $com->db->where("DATE(dtime) >= '$date1' AND DATE(dtime) <= '$date2'");
        $tmpRange = $com->fetchMovement($rekening);
        // cekHijau($this->db->last_query()) . " <hr> 99". $comName;

        // $mdl->addFilter("jenis='" . "item_rakitan" . "'");
        // $pro->setFilters(array());
        if ($this->placeID <= 0) {
            $mdl->setFilters(array());
            $mdl->db->where("jenis in ('item_rakitan','item') AND `status` = '1' AND `trash` = '0'");
        }
        // $mdl->addFilter("id='222'");
        $produks = $mdl->lookupAll()->result();
        // cekLime($this->db->last_query() . "<hr>$item ---- $mdlName");

        // arrPrint($produks);
        // arrPrint($mdlFields);
        // arrPrint($fieldToshows);
        // arrPrint($tmpBegin);

        /*---------------------------
         * mutasi tanpa batasan tanggal mulai
         * ------------------------*/
        // arrPrint($tmpUnbegin);
        $arrMutasi_3 = array();
        foreach ($tmpUnbegin as $rDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }

            $arrMutasi_3[$extern_id]['qty_debet_awal'] = $qty_debet_akhir;
            $arrMutasi_3[$extern_id]['qty_debet'] = 0;
            $arrMutasi_3[$extern_id]['qty_kredit'] = 0;
            $arrMutasi_3[$extern_id]['qty_debet_akhir'] = $qty_debet_akhir;

            $arrMutasi_3[$extern_id]['debet_awal'] = $debet_akhir;
            $arrMutasi_3[$extern_id]['debet'] = $debet;
            $arrMutasi_3[$extern_id]['kredit'] = $kredit;
            $arrMutasi_3[$extern_id]['debet_akhir'] = $debet_akhir;
            // $arrMutasi_3[$produk_id]['on_hpp'] = $on_hpp;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp_3 = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                // cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                // $on_hpp_3 = ($kredit + $debet);
                $on_hpp_3 = ($kredit + $debet);
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            // if (($qty_debet + $qty_kredit) > 0) {
            //     $on_hpp_3 = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            // }
            // else {
            //     $on_hpp_3 = ($kredit + $debet);
            //     // $arrMutasi_3[$extern_id]['on_hpp'] = ($kredit + $debet);
            // }
            if (!isset($arrMutasi_3[$extern_id]['on_hpp'])) {
                $arrMutasi_3[$extern_id]['on_hpp'] = 0;
            }
            $arrMutasi_3[$extern_id]['on_hpp'] += "999" . $on_hpp_3;
        }
        // arrPrint($tmpUnbegin);
        // arrPrint($arrMutasi_3);
        /*---------------------------
         * data stok awal dan akhir
         * $arrBeAf
         * ------------------------*/
        $spec = array();
        foreach ($tmpBegin as $bDatas) {
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $bDatas->$bKolom;

            }
            $spec['qty_debet_awal'] = $qty_debet_awal;
            $spec['qty_debet_akhir'] = $qty_debet_akhir;
            if (($qty_debet + $qty_kredit) > 0) {

                $spec['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
            }
            else {
                $spec['on_hpp'] = ($kredit + $debet);
            }
            $arrBeAf[$extern_id][] = $spec;
        }
        // arrPrint($arrBeAf);

        // arrPrint($tmpRange);
        /*---------------------------
         * mutasi dengan range tanggal
         * ------------------------*/
        $arrMutasi = array();
        $no = 0;
        foreach ($tmpRange as $rDatas) {
            $no++;
            foreach ($bKoloms as $bKolom) {
                $$bKolom = $rDatas->$bKolom;
            }

            $jml_be = $arrBeAf[$extern_id][0]["qty_debet_awal"];
            $arrEnd = end($arrBeAf[$extern_id])["qty_debet_akhir"];

            $arrMutasi[$extern_id]['qty_debet_awal'] = $jml_be;

            if (!isset($arrMutasi[$extern_id]['qty_debet'])) {
                $arrMutasi[$extern_id]['qty_debet'] = 0;
            }
            $arrMutasi[$extern_id]['qty_debet'] += $qty_debet;

            if (!isset($arrMutasi[$extern_id]['qty_kredit'])) {
                $arrMutasi[$extern_id]['qty_kredit'] = 0;
            }
            $arrMutasi[$extern_id]['qty_kredit'] += $qty_kredit;

            $arrMutasi[$extern_id]['qty_debet_akhir'] = $arrEnd;

            if ($no == 1) {

                $arrMutasi[$extern_id]['debet_awal'] = $debet_awal;
            }

            if (!isset($arrMutasi[$extern_id]['debet'])) {
                $arrMutasi[$extern_id]['debet'] = 0;
            }
            $arrMutasi[$extern_id]['debet'] += $debet;

            if (!isset($arrMutasi[$extern_id]['kredit'])) {
                $arrMutasi[$extern_id]['kredit'] = 0;
            }
            $arrMutasi[$extern_id]['kredit'] += $kredit;

            $arrMutasi[$extern_id]['debet_akhir'] = $debet_akhir;

            $on_qty = $qty_debet + $qty_kredit;
            if ($on_qty > 0) {
                $on_hpp = ($kredit + $debet) / $on_qty;
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet) / ($qty_debet + $qty_kredit);
                // cekMerah("$extern_id = ($kredit + $debet) / ($qty_debet + $qty_kredit)");
            }
            else {
                // $arrMutasi[$extern_id]['on_hpp'] = ($kredit + $debet);
                $on_hpp = ($kredit + $debet);
                // $on_hpp = ($kredit + $debet) / $on_qty;
                // cekBiru("$extern_id = ($kredit + $debet) / 00001)");
            }

            if (!isset($hppId[$extern_id])) {
                $hppId[$extern_id] = 0;
            }
            $hppId[$extern_id] += $on_hpp;

            if (!isset($qtyId[$extern_id])) {
                $qtyId[$extern_id] = 0;
            }
            $qtyId[$extern_id] += $on_qty;

            // cekHitam("$extern_id:: $on_hpp / $on_qty");
            // $arrMutasi[$extern_id]['on_hpp'] = $harga_avg;
            $arrMutasi[$extern_id]['on_hpp'] = 88;

        }
        // arrPrintWebs($tmpRange);
        // arrPrintWebs($arrMutasi);
        $jml_row = sizeof($tmpRange);
        foreach ($qtyId as $extern_id_2 => $sumQty) {
            // $hpp_avg = $hppId[$extern_id_2];
            // if($sumQty > 0){
            //     $hpp_avg = $hppId[$extern_id_2] / $sumQty;
            // }
            // else{
            $hpp_avg = $hppId[$extern_id_2];
            // }

            // cekLime("$extern_id_2 :: $hpp_avg / $sumQty");
            $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = "99".$hpp_avg;
            // $arrMutasi[$extern_id_2]['on_hpp'] = $hpp_avg / $sumQty;
        }

        // $arrMutasi[$extern_id]['on_hpp'] = 0;

        // arrPrint($arrMutasi);

        //region data seluruh produk
        foreach ($produks as $pDatas) {
            foreach ($mdlFields as $mdlField) {
                $$mdlField = $pDatas->$mdlField;
            }
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pDatas->$mdlField;
                $specs[$mdlField] = $pDatas->$mdlField;
            }

            $arrProId[$id] = $specs;
        }
        //endregion


        // arrPrint($arrBeAf);
        // arrPrint($arrProId);
        // mati_disini();
        $specs = array();
        $bodies = array();
        $no = 0;
        //region body produk
        foreach ($arrProId as $pId => $pSpecs) {
            $no++;
            // $dataMutasi = isset($arrMutasi[$pId]) ? $arrMutasi[$pId] : $arrMutasi_3[$pId];
            if (isset($arrMutasi[$pId])) {
                $dataMutasi = $arrMutasi[$pId];
            }
            elseif (isset($arrMutasi_3[$pId])) {
                $dataMutasi = $arrMutasi_3[$pId];

            }
            else {
                $dataMutasi = array();
            }
            // cekHijau("id $pId");
            // arrPrint($dataMutasi);

            $specs['no']['value'] = $no;
            $specs['no']['attr'] = "class='text-right'";
            foreach ($fieldToshows as $mdlField => $label) {
                $$mdlField = $pSpecs[$mdlField];

                $value = $pSpecs[$mdlField];

                if (isset($fieldLink[$mdlField])) {
                    $specs[$mdlField]['value'] = "<a target=\"_blank\" href=\"" . base_url() . $fieldLink[$mdlField] . $pId . "/?o=$cabangID\">" . $value . "</a>";
                    // $specs[$mdlField]['value'] = "<a href=\"#\">$value</a>";
                }
                else {

                    $specs[$mdlField]['value'] = $value;
                }

                // $specs[$mdlField]['value'] = ;
                $specs[$mdlField]['attr'] = isset($fieldAttr[$mdlField]) ? $fieldAttr[$mdlField] : "class='text-left'";
            }

            if (sizeof($dataMutasi) > 0) {
                $qty_be = $dataMutasi['qty_debet_awal'];
                $qty_in = $dataMutasi['qty_debet'];
                $qty_ot = $dataMutasi['qty_kredit'];
                $qty_af = $dataMutasi['qty_debet_akhir'];
                $harga = $dataMutasi['on_hpp'] * 1;

                // $amount_be = $dataMutasi['debet_awal'];
                // $amount_in = $dataMutasi['debet'];
                // $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
                // $amount_ot = $dataMutasi['kredit'];
                // $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $dataMutasi['kredit'];
            }
            else {
                $qty_be = 0;
                $qty_in = 0;
                $qty_ot = 0;
                $qty_af = 0;
                $harga = 0;
            }

            // [223] => Array
            //         (
            //             [qty_debet_awal] => 0
            //             [qty_debet] => 1
            //             [qty_kredit] => 1
            //             [qty_debet_akhir] => 0
            //             [on_hpp] => 4800000
            //         )
            if ($pId == 315) {
                // cekHitam();
                // cekHijau($arrMutasi_3[$pId]['debet_awal']);
                // arrPrintWebs($arrMutasi[$pId]);
            }

            $qty_av = $qty_be + $qty_in;
            // $amount_be = $dataMutasi['debet_awal'];
            $amount_be = $arrMutasi[$pId]['debet_awal'];
            $amount_in = $dataMutasi['debet'];
            $amount_av = $dataMutasi['debet_awal'] + $dataMutasi['debet'];
            $amount_ot = $arrMutasi[$pId]['kredit'];
            $amount_af = $dataMutasi['debet_awal'] + $dataMutasi['debet'] - $arrMutasi[$pId]['kredit'];

            //region awal
            $specs['qty_debet_awal']['value'] = $qty_be;
            $specs['qty_debet_awal']['attr'] = "class='text-right'";
            // $specs['harga_be']['value'] = $harga;
            // $specs['harga_be']['attr'] = "class='text-right'";
            // $specs['sum_be']['value'] = (($qty_be > 0) && ($amount_be == 0)) ? "Nan" : $amount_be;
            $specs['sum_be']['value'] = nanMovement($qty_be, $amount_be);
            $specs['sum_be']['attr'] = "class='text-right'";
            //endregion

            //region in masuk
            $specs['qty_debet']['value'] = $qty_in;
            $specs['qty_debet']['attr'] = "class='text-right'";
            // $specs['harga_in']['value'] = $harga;
            // $specs['harga_in']['attr'] = "class='text-right'";
            $specs['sum_in']['value'] = nanMovement($qty_in, $amount_in);
            $specs['sum_in']['attr'] = "class='text-right'";
            //endregion

            //region tersedia available
            $specs['qty_kredit']['value'] = $qty_av;
            $specs['qty_kredit']['attr'] = "class='text-right'";
            // $specs['harga_ot']['value'] = $harga;
            // $specs['harga_ot']['attr'] = "class='text-right'";
            $specs['sum_ot']['value'] = (($qty_av > 0) && ($amount_av == 0)) ? "Nan" : $amount_av;
            $specs['sum_ot']['attr'] = "class='text-right'";
            //endregion

            //region ot output keluar
            $specs['qty_avail']['value'] = $qty_ot;
            $specs['qty_avail']['attr'] = "class='text-right'";
            // $specs['harga_av']['value'] = $harga;
            // $specs['harga_av']['attr'] = "class='text-right'";
            $specs['sum_av']['value'] = $amount_ot;
            $specs['sum_av']['attr'] = "class='text-right'";
            //endregion

            //region akhir
            $specs['qty_debet_akhir']['value'] = $qty_af;
            $specs['qty_debet_akhir']['attr'] = "class='text-right'";
            // $specs['harga_af']['value'] = $harga;
            // $specs['harga_af']['attr'] = "class='text-right'";
            $specs['sum_af']['value'] = (($qty_af > 0) && ($amount_af <= 0)) ? "Nan" : $amount_af;
            $specs['sum_af']['attr'] = "class='text-right'";
            //endregion

            $bodies[] = $specs;
        }
        //endregion

        // region footers
        $footers = array();
        // endregion footers
        // arrPrint($bodies);
        //                matiHere();
        // $month = date("Y-m",$date1);
        // cekHitam($month);
        $dRange = formatField_he_format("dtime", $date1) . " s/d " . formatField_he_format("dtime", $date2);
        $dateRange = "<span class='font-size-0-8 text-grey-2'>$dRange</span>";
        $data = array(
            "mode"     => "movement",
            "title"    => "$itemLabel movements $dateRange",
            "subTitle" => "",
            // "items"       => array(),
            // "items2"      => array(),
            "date1"    => $date1,
            "date2"    => $date2,

            "rowLoop"     => 5,
            "mainHeaders" => $mainHeaders,
            "subHeaders"  => $subHeaders,
            "bodies"      => $bodies,
            "footers"     => $footers,

            "detailsLabels" => "",
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?o=$cabangID",
        );
        //endregion

        //        arrprint($data);
        $this->load->view("ledger", $data);

    }


    public function viewDetail_l1()
    {
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        //arrPrintPink($_GET);
        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();


        $com->addFilter("cabang_id='$cabangID'");

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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $this->db->order_by("id", "DESC");
            switch ($_GET['periode']) {
                case "bulanan":
                    $date1 = isset($_GET['date']) ? $_GET['date'] . "-01" : date("Y-m-d");
                    $date2 = isset($_GET['date']) ? $_GET['date'] . "-31" : date("Y-m-d");
                    break;
                case "tahunan":
                    //                    cekHere("HAHAHA");
                    //                    $date1 = isset($_GET['date']) ? $_GET['date'] . "-01-01" : date("Y-m-d");
                    //                    $date2 = isset($_GET['date']) ? $_GET['date'] . "-12-31" : date("Y-m-d");
                    if (isset($_GET['date'])) {
                        $date1 = $_GET['date'] . "-01-01";
                        $date2 = $_GET['date'] . "-12-31";
                    }
                    else {
                        $date1 = date("Y") . "-01-01";
                        $date2 = date("Y") . "-12-31";
                    }
                    break;
                default:
                    $date1 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                    $date2 = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
                    break;
            }


            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
        }


        $tmp = $com->fetchMoves($rekName);
        //        cekkuning($this->db->last_query());


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        foreach ($tmp as $temX) {
            $transaksi_id = $temX->transaksi_id;
            $position = $temX->debet > 0 ? "debet" : "kredit";
            $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
            $detectRek = detectRekByPosition($rekName, $val, $position);
            if ($detectRek > 0) {
                $arrMovedDetail["in"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }
            else {
                $arrMovedDetail["out"][$temX->jenis] = $position;
                if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                    $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                    $newSelect = "qty_" . $position;
                    $qtyVal = $temX->$newSelect;
                }
                else {
                    $qtyVal = 0;
                }

            }

            $arrProds[][$temX->jenis] = array(
                "$position"        => "$val",
                "qty_" . $position => $qtyVal,
            );

            $tmpTrIds[$transaksi_id] = 1;
        }
        $trIds = array_keys($tmpTrIds);
        //endregion

        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);

        // endregion transaksi


        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime"          => "time",
                "jenis"          => "note",
                //                "nomer_top" => "reference number",
                "transaksi_no"   => "receipt number",
                "suppliers_nama" => "vendor",
                "cabang_nama"    => "branch",
                "oleh_nama"      => "by",
            );
        }
        else {
            $headerFields = array(
                "dtime"          => "time",
                "jenis"          => "note",
                //                "nomer_top" => "reference number",
                "transaksi_no"   => "receipt number",
                "customers_nama" => "customer",
                //                "cabang_nama" => "branch",
                "oleh_nama"      => "by",
            );
        }

        //        $headerFields["ids_his"] = "reference number";
        //        $headerFields["transaksi_no"] = "receipt number";
        //        $headerFields["referenceNomer"] = "cancelled number";
        //        $headerFields["description_main_followup"] = "vendor's number referral";
        //        $headerFields["review_details"] = "&nbsp;";


        $jenisAliases = arrCodeAliasing($cabangID);

        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {
                    $datas[$hField] = key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                }
            }
            $counters = blobDecode($tmpTr->counters);
            $datas['nomer'] = $tmpTr->nomer . "-" . digit_5($counters['stepCode|placeID'][$tmpTr->jenis . "|" . $tmpTr->cabang_id]);
            $datas['urut'] = "-" . digit_5($counters['stepCode|placeID'][$tmpTr->jenis . "|" . $tmpTr->cabang_id]);
            $datas['cabang_nama'] = $tmpTr->cabang_nama;
            $trDatas[$tmpTr->id] = $datas;
        }
        //         arrPrint($trDatas);
        // endregion builder data transaksi yg perlu muncul


        //        $headerQtyFields = array(
        //
        //            "qty_" . $defPosition . "_awal" => "prev (QTY)",
        //            "qty_" . $defPosition => "in (QTY)",
        //            "qty_" . $opPosition => "out (QTY)",
        //            "qty_" . $defPosition . "_akhir" => "balance (QTY)",
        //
        //        );

        switch ($rekName) {
            case "7010150":
                $headerValueFields = array(
                    //            $defPosition . "_awal" => "prev (IDR)",
                    $defPosition => "KREDIT (IDR)",
                    $opPosition  => "DEBET (IDR)",
                    //            $defPosition . "_akhir" => "balance (IDR)",
                );
                break;
            default:
                $headerValueFields = array(
                    //            $defPosition . "_awal" => "prev (IDR)",
                    $defPosition => "in (IDR)",
                    $opPosition  => "out (IDR)",
                    //            $defPosition . "_akhir" => "balance (IDR)",
                );
                break;
        }

        $headerValueFieldsView = array(
            //            $defPosition . "_awal" => "prev (IDR)",
            //            $defPosition => "in (IDR)",
            //            $opPosition => "out (IDR)",
            //
            "balance" => "balance (IDR)",
        );

        //        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
        //            $headerFields = $headerFields + $headerQtyFields;
        //        }
        //
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFieldsView = $headerFields + $headerValueFieldsView;
            $headerFields = $headerFields + $headerValueFields;
        }


        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            $subs = array();
            foreach ($tmp as $row) {

                if (($row->debet > 0) || ($row->kredit > 0)) {
                    //arrPrintWebs($row);
                    foreach ($headerFields as $key => $label) {
                        if (array_key_exists($key, $row)) {
                            if (key_exists($row->$key, $jenisAliases)) {
                                $subs[$key] = $jenisAliases[$row->$key];
                            }
                            else {
                                $subs[$key] = $row->$key;
                            }

                            //                            if((is_numeric($subs[$key])) && ($key != "jenis")){
                            //                                if(!isset($subs['balance'])){
                            //                                    $subs['balance'] = 0;
                            //                                }
                            //                                if($row->$defPosition > 0){
                            ////                                    cekMerah(":: +");
                            //                                    $nilai = $subs[$key];
                            //                                }
                            //                                elseif($row->$opPosition > 0){
                            ////                                    cekMerah(":: -");
                            //                                    $nilai = $subs[$key] *-1;
                            //                                }
                            //                                else{
                            ////                                    cekMerah(":: +/-");
                            //                                    $nilai = $subs[$key];
                            //                                }
                            //                                cekHere($nilai);
                            //                                $subs['balance'] = $nilai;
                            //                            }
                        }
                    }
                    if (!isset($subs['balance'])) {
                        $subs['balance'] = 0;
                    }
                    if ($row->$defPosition > 0) {
                        $nilai = $row->$defPosition;
                    }
                    elseif ($row->$opPosition > 0) {
                        $nilai = $row->$opPosition * -1;
                    }

                    $subs['balance'] = $nilai;
                    $modul = isset($this->masterConfigUi[$row->jenis]["modul"]) ? $this->masterConfigUi[$row->jenis]["modul"] : false;
                    $subs['modul'] = $modul;
                    $subs['modul_path'] = base_url() . $modul . "/";
                    $subs['jenis_master'] = $row->jenis;


                    $subs_r = array_replace($subs, $trDatas[$row->transaksi_id]);
                    $items[] = $subs_r;
                }
            }
        }

        //        arrprint($headerFieldsView);
        //region prepare params to viewer
        $headerFields['balance'] = "balance (IDR)";
        $data = array(
            "mode"         => "rlDetail",
            "title"        => isset($accountAlias[$rekName]) ? $accountAlias[$rekName] : $rekName,
            "subTitle"     => $datas['cabang_nama'] . " &nbsp;&nbsp;  $subTitle_date",
            "items"        => $items,
            //            "headerFields" => $headerFields,
            "headerFields" => $headerFieldsView,
            "filters"      => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "thisPage"     => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "summary"      => isset($this->summaryKey) ? $this->summaryKey : array(),
        );
        //endregion

        //arrPrintWebs($items);
        $this->load->view("ledger", $data);


    }

    public function viewBalances_l1_periode()
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

        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";

        $getExternID = isset($_GET['ext_id']) && strlen($_GET['ext_id']) ? $_GET['ext_id'] : NULL;
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $periode = isset($_GET['periode']) && strlen($_GET['periode']) ? ($_GET['periode']) : NULL;
        $date1 = isset($_GET['date1']) && strlen($_GET['date1']) ? ($_GET['date1']) : NULL;
        $date2 = isset($_GET['date2']) && strlen($_GET['date2']) ? ($_GET['date2']) : NULL;


        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $mdlName = "Com" . $relName;
        //cekBiru(":: $mdlName ::");
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
        //-----------------------------
        $date1_ex = explode("-", $date1);
        $tgl = $date1_ex[2];
        $bln = $date1_ex[1];
        $thn = $date1_ex[0];
        switch ($periode) {
            case "bulanan":
                $com->addFilter("bln='$bln'");
                $com->addFilter("thn='$thn'");
                break;
            case "tahunan":
                $com->addFilter("thn='$thn'");
                break;
            default:
                break;
        }

        //-----------------------------

        //        $tmp = $com->fetchBalances($rekName, $q, $sortBy, $sortMode);
        $tmp = $com->fetchBalancePeriode($rekName, $externID, $periode);
        showLast_query("biru");
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
            }
            $tmpSrcDue = $adv->$advanceSpec['method']()->result();
            $tempDataDues = array();
            foreach ($tmpSrcDue as $tmpSrcDue_tmp) {
                $tempDataDues[$tmpSrcDue_tmp->customers_id][] = array(
                    "due_date"    => $tmpSrcDue_tmp->due_date,
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
                        "aging"    => $dtime_val['aging_dtime'],
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
                        "aging"    => umurDay($aging) > 0 ? umurDay($aging) : "0",
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
                        $kode = isset($rSpec->kode) ? $rSpec->kode : "";
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
                        "keterangan"   => $keterangan,
                        "link_history" => $historyClick,
                    );
                }
            }
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


        if (isset($balConfig['additionalPairedModel']) && sizeof($balConfig['additionalPairedModel']) > 0) {
            $addMdlNameRek = $balConfig['additionalPairedModel']['mdlNameRek'];
            $addMethodRek = $balConfig['additionalPairedModel']['mdlMethodRek'];
            $addPrefix = $balConfig['additionalPairedModel']['prefix'];

            $addMdlNameData = $balConfig['additionalPairedModel']['mdlNameData'];
            $addMethodData = $balConfig['additionalPairedModel']['mdlMethodData'];

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
                //                showLast_query("merah");
                //                cekMerah(sizeof($tmpData));
                $addDataResult = array();
                if (sizeof($tmpData) > 0) {
                    foreach ($tmpData as $tmpSpec) {
                        if (sizeof($balConfig['additionalViewedColumns']) > 0) {
                            foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                                $addKey_fix = str_replace($addPrefix, "", $addKey);
                                $addDataResult[$tmpSpec->extern_id][$addKey] = $tmpSpec->$addKey_fix;
                            }
                        }
                    }
                }

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
                //                arrPrintWebs($row);
                if (isset($dueEmployee) && (sizeof($dueEmployee) > 0)) {
                    if (isset($dueEmployee[$row->extern_id])) {
                        foreach ($dueEmployee[$row->extern_id] as $advKey => $valKey) {
                            $row->$advKey = $valKey;
                        }
                    }
                }

                $rekening = $row->rekening;
                $extern_nama = $row->extern_nama;
                $position = detectRekDefaultPosition($rekening);

                $rekening_relasi_tmp = isset($rkTempResult[$row->extern_nama]) ? $rkTempResult[$row->extern_nama] : array();
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

                }
                // pembantu tingkat 3
                elseif (isset($accountSuperSubChilds[$extern_nama])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekOrange("ada relasi dengan accountSuperSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSuperSubChilds[$extern_nama] . "/" . $extern_nama . "?ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";

                }
                // pembantu tingkat 2
                elseif (isset($accountSubChilds[$rekening])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekPink("ada relasi dengan accountSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening] . "/" . $rekening . "?ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";

                }
                //
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

                }
                // tidak ada pembantu
                else {
                    //                    cekOrange("TIDAK ada relasi dengan accountSubChilds, $extern_nama, $rekening");
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    if ($rekening != "laba ditahan") {
                        $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getExtern2ID . "&main_ext2_id=" . $getExtern2ID . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $tmpRow['link'] = "#";
                        $tmpRow['link_main']['extern_nama'] = NULL;
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
                        "main"   => $no_main,
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
                if (isset($advHeader) && sizeof($advHeader) > 0) {
                    foreach ($advHeader as $advKey => $advVal) {
                        $items[$no][$advKey] = isset($row->$advKey) ? $row->$advKey : "-";
                    }
                }
            }
        }


        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            "pId"    => "pID",
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
        $headerFields["satuan"] = "UOM";

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
                "qty_" . $defPosition => "balance (QTY)",
            );
            $headerValueFields = array(
                $defPosition => "balance (IDR)",
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

        //arrPrintWebs($items);
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
            "mdl"       => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo"      => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            // "mdl_data"  => $mdlData,
            "cabang_id" => $cabangID,
        );
        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        //arrPrintWebs($items);
        $data = array(
            "mode"             => "saldo",
            "title"            => "$rekName",
            "subTitle"         => "$subTitle $blob_ext",
            "items"            => $items,
            "headerFields"     => $headerFields,
            "thisPage"         => $thisPage,
            "thisURL"          => $thisURL,
            "q"                => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
            "summary"          => $summaryAllowed,
            "items_blok"       => $items_blok,
            "param_to_excel"   => $param_to_excel_e,
            "pairedResult_add" => isset($pairedResult_add) ? $pairedResult_add : array(),
        );
        //endregion


        $this->load->view("ledger", $data);


    }

    //----------------------------
    public function viewMoveDetails_NEW()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);
        $transaksiIDStyle = (isset($_GET['trID']) && $_GET['trID'] > 0) ? array($_GET['trID'] => "font-size:18px;font-weight:bold;") : array();

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;


        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;
        $rekName_f = isset($this->config->item('accountAlias')[$rekName]) ? $this->config->item('accountAlias')[$rekName] : null;
        $accountBalanceLocker = isset($this->config->item('accountBalanceColumLocker')[$relName]) ? $this->config->item('accountBalanceColumLocker')[$relName] : array();

        $arrRekeningAkumulasi = array(
            "persediaan produk",
            "persediaan produk rakitan",
            "persediaan supplies",
        );

        if (in_array($rekName, $arrRekeningAkumulasi)) {
            $view_akumulasi = true;
        }
        else {
            $view_akumulasi = false;
        }


        //region show locker value
        $finalLocker = array();
        if (sizeof($accountBalanceLocker) > 0) {
            //region locker rekening
            $mdlLocker = $accountBalanceLocker['mdlName'];
            $this->load->model("Mdls/" . $mdlLocker);
            $lo = new $mdlLocker();
            $stateTmp = $accountBalanceLocker['state'];
            if (sizeof($stateTmp) > 0) {
                $finalLocker = array();
                $trIDLocker = array();
                foreach ($stateTmp as $state => $state_0) {
                    $lo->setFilters(array());
                    $lo->addFilter("cabang_id=$cabangID");
                    $lo->addFilter("produk_id=$externID");
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
                            //                            arrPrintWebs($tmpLocker);
                            //                            if (!isset($lockerValue[$tmpLocker->produk_id])) {
                            //                                $lockerValue[$tmpLocker->produk_id] = 0;
                            //                            }
                            //                            foreach ($state_0['viewedColums'] as $co => $aliasCol) {
                            //                                $lockerValue[$tmpLocker->produk_id] += $tmpLocker->$co;
                            //                            }
                            //                            $finalLocker[$state] = $lockerValue;
                            if ($state == "hold") {
                                if (isset($tmpLocker->nilai) && ($tmpLocker->nilai > 0)) {
                                    $trIDLocker[$tmpLocker->transaksi_id]['nilai'] = isset($tmpLocker->nilai) ? $tmpLocker->nilai : 0;
                                    //                                    $trIDLocker[$tmpLocker->transaksi_id]['jumlah'] = isset($tmpLocker->jumlah) ? $tmpLocker->jumlah : 0;
                                }
                                if (isset($tmpLocker->jumlah) && ($tmpLocker->jumlah > 0)) {
                                    //                                    $trIDLocker[$tmpLocker->transaksi_id]['nilai'] = isset($tmpLocker->nilai) ? $tmpLocker->nilai : 0;
                                    $trIDLocker[$tmpLocker->transaksi_id]['jumlah'] = isset($tmpLocker->jumlah) ? $tmpLocker->jumlah : 0;
                                }
                            }
                            elseif ($state == "active") {
                                $finalLocker['debet_akhir'] = (isset($tmpLocker->nilai) && ($tmpLocker->nilai > 0)) ? $tmpLocker->nilai : 0;
                                $finalLocker['qty_debet_akhir'] = (isset($tmpLocker->jumlah) && ($tmpLocker->jumlah > 0)) ? $tmpLocker->jumlah : 0;
                            }
                        }
                    }
                }

                if ($trIDLocker) {
                    $arrKeyLocker = array_keys($trIDLocker);
                    //                    arrPrint($arrKeyLocker);

                    $this->load->model("MdlTransaksi");
                    $tr = New MdlTransaksi();
                    $tr->addFilter("id in ('" . implode("','", $arrKeyLocker) . "')");
                    $trTmp = $tr->lookupAll()->result();
                    $arrJenisTr = array();
                    foreach ($trTmp as $spec) {
                        $arrJenisTr[$spec->id] = $spec->jenis;
                    }
                }
                $additionalInOut = array();
                if (sizeof($arrJenisTr) > 0) {
                    foreach ($arrJenisTr as $trID => $jenisTr) {
                        //                        $jenisTr = $accountBalanceLocker['label'];
                        if (!isset($additionalInOut[1][$jenisTr][$opPosition])) {
                            $additionalInOut[1][$jenisTr][$opPosition] = 0;
                        }
                        if (!isset($additionalInOut[1][$jenisTr]["qty_" . $opPosition])) {
                            $additionalInOut[1][$jenisTr]["qty_" . $opPosition] = 0;
                        }
                        $additionalInOut[1][$jenisTr][$opPosition] += $trIDLocker[$trID]['nilai'];
                        $additionalInOut[1][$jenisTr]["qty_" . $opPosition] += $trIDLocker[$trID]['jumlah'];
                        $arrMovedDetail["out"][$jenisTr] = $opPosition;
                    }
                }
                //                cekHere("cetak trID locker");
                //                arrPrintWebs($trIDLocker);
                //                cekHere("cetak jenisTr");
                //                arrPrintWebs($arrJenisTr);
                //                cekHere("cetak additional in out");
                //                arrPrintWebs($additionalInOut);
            }
            //endregion
            //-------------------------------------------------
            //region rekening cache
            $mdlLockerRek = $accountBalanceLocker['mdlNameRekeningCache'];
            $mdlFilter = $accountBalanceLocker['filter'];
            $this->load->model("Coms/" . $mdlLockerRek);
            $lr = new $mdlLockerRek();
            $lr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $lr->addFilter("extern_id='" . $externID . "'");
            if (sizeof($mdlFilter) > 0) {
                foreach ($mdlFilter as $f) {
                    $lr->addFilter($f);
                }
            }
            $lrTmp = $lr->lookupAll()->result();
            $position = $defPosition;
            $qty_position = "qty_" . $defPosition;
            $finalLocker[$defPosition . "_awal"] = (sizeof($lrTmp) > 0) ? $lrTmp[0]->$defPosition : 0;
            $finalLocker[$qty_position . "_awal"] = (sizeof($lrTmp) > 0) ? $lrTmp[0]->$qty_position : 0;
            //endregion

        }
        //        cekHere("cetak finalLocker");
        //        arrPrintWebs($finalLocker);
        //endregion
        //arrPrintPink($balConfig);

        $mdlName = "Com" . $relName;
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();
        $com->addFilter("cabang_id='$cabangID'");
        $com->addFilter("jenis<>''");
        if ($getExtern2ID != NULL) {
            $com->addFilter("extern2_id='$getExtern2ID'");
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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            $limit = 20;
            $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-01");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //        showLast_query("biru");
        //arrPrint($tmp);
        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();

            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $saldo_qty_berjalan = 0;
        $saldo_value_berjalan = 0;

        $arrProds = array();
        $arrProdsByHpp = array();
        $arrProdsByHppResult = array();
        $arrProdsByJenis = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                //                arrPrintWebs($temX);
                $transaksi_id = $temX->transaksi_id;
                $hpp_key = $temX->harga;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }
                }

                $arrProds[][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                );
                $arrProdsByJenis = array();
                $arrProdsByJenis[$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                );

                $tmpTrIds[$transaksi_id] = $transaksi_id;


                // mengurusi saldo berjalan...
                $saldo_qty_berjalan += ($temX->qty_debet - $temX->qty_kredit);
                $saldo_value_berjalan += ($temX->debet - $temX->kredit);
                $saldoBerjalan[$temX->id]['qty'] = $saldo_qty_berjalan;
                $saldoBerjalan[$temX->id]['value'] = $saldo_value_berjalan;
                //--- dikelompokkan per-hpp, per-transaksi
                $arrProdsByHpp[$transaksi_id][$hpp_key][] = $arrProdsByJenis;
            }
            $trIds = array_keys($tmpTrIds);
            //arrPrintWebs($arrProdsByHpp);
            //---------------------------
            foreach ($arrProdsByHpp as $trID => $trSpec) {
                foreach ($trSpec as $hpp => $hppSpec) {
                    foreach ($hppSpec as $ii => $iiSpec) {
                        foreach ($iiSpec as $jenis => $jenisSpec) {
                            //                            arrPrint($jenisSpec);
                            if (!isset($arrProdsByHppResult[$trID][$hpp][$jenis]['debet'])) {
                                $arrProdsByHppResult[$trID][$hpp][$jenis]['debet'] = 0;
                            }
                            if (!isset($arrProdsByHppResult[$trID][$hpp][$jenis]['qty_debet'])) {
                                $arrProdsByHppResult[$trID][$hpp][$jenis]['qty_debet'] = 0;
                            }
                            if (!isset($arrProdsByHppResult[$trID][$hpp][$jenis]['kredit'])) {
                                $arrProdsByHppResult[$trID][$hpp][$jenis]['kredit'] = 0;
                            }
                            if (!isset($arrProdsByHppResult[$trID][$hpp][$jenis]['qty_kredit'])) {
                                $arrProdsByHppResult[$trID][$hpp][$jenis]['qty_kredit'] = 0;
                            }

                            $arrProdsByHppResult[$trID][$hpp][$jenis]['debet'] += isset($jenisSpec['debet']) ? $jenisSpec['debet'] : 0;
                            $arrProdsByHppResult[$trID][$hpp][$jenis]['qty_debet'] += isset($jenisSpec['qty_debet']) ? $jenisSpec['qty_debet'] : 0;
                            $arrProdsByHppResult[$trID][$hpp][$jenis]['kredit'] += isset($jenisSpec['kredit']) ? $jenisSpec['kredit'] : 0;
                            $arrProdsByHppResult[$trID][$hpp][$jenis]['qty_kredit'] += isset($jenisSpec['qty_kredit']) ? $jenisSpec['qty_kredit'] : 0;
                        }
                    }
                }
            }
            //arrPrintHijau($arrProdsByHppResult);
            if ($view_akumulasi == true) {
                $arrProds = array();
                foreach ($arrProdsByHppResult as $resultSpec) {
                    foreach ($resultSpec as $rSpec) {
                        $arrProds[] = $rSpec;
                    }
                }
            }

        }
        //endregion


        if ((isset($additionalInOut)) && (sizeof($additionalInOut) > 0)) {
            foreach ($additionalInOut as $ii => $iiSpec) {
                $arrProds[] = $iiSpec;
            }
        }
        //        cekHere("cetak group in out, setelah diinject additional in out");
        //        arrPrintPink($arrProds);


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        //        cekLime($this->db->last_query());


        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];
            $arrHeaderBlacklist = array(
                "kredit",
                "debet",
                "qty_kredit",
                "qty_debet",
                "qty_kredit_akhir",
                "qty_debet_akhir",
                "kredit_akhir",
                "debet_akhir",
            );
            foreach ($headerFields as $key => $val) {
                if (in_array($key, $arrHeaderBlacklist)) {
                    unset($headerFields[$key]);
                }
            }
        }
        else {
            die("balance config for $relName is not yet defined");
        }


        $headerFields["ids_his"] = "reference number";
        $headerFields["transaksi_no"] = "receipt number";
        $headerFields["referenceNomer"] = "cancelled number";
        $headerFields["description_main_followup"] = "vendor's number referral";
        $headerFields["review_details"] = "&nbsp;";


        $jenisAliases = arrCodeAliasing($cabangID);


        // region builder data transaksi yg perlu muncul
        $arrDecode = array("ids_his", "counters");
        $trDatas = array();
        $regDatas = array();
        $arrTrIDs = array();
        $arrDataTr = array(
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "oleh_id",
            "oleh_nama",
            "jenis_master",
        );
        foreach ($tmpTrs as $tmpTr) {

            $datas = array();
            foreach ($headerFields as $hField => $hAlias) {
                //                $$hField = array_key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";
                if (array_key_exists($hField, $tmpTr)) {

                    if (array_key_exists($tmpTr->$hField, $jenisAliases)) {
                        $datas[$hField] = $jenisAliases[$tmpTr->$hField];

                    }
                    else {
                        if (isset($tmpTr->$hField) && !is_numeric($tmpTr->$hField)) {
                            //                            if ($tmpTr->$hField === base64_encode(base64_decode($tmpTr->$hField))) {
                            if (in_array($hField, $arrDecode)) {
                                //                                cekHitam("masuk disini, unBlob " . $tmpTr->$hField);
                                //                                $datas[$hField] = isset($tmpTr->$hField) ? unserialize(base64_decode($tmpTr->$hField)) : "";
                                $datas[$hField] = isset($tmpTr->$hField) ? blobDecode($tmpTr->$hField) : "";

                            }
                            else {
                                $datas[$hField] = $tmpTr->$hField;
                            }

                        }
                        else {
                            $datas[$hField] = $tmpTr->$hField;
                        }
                    }
                }
            }
            foreach ($arrDataTr as $kolomTr) {
                $datas[$kolomTr] = isset($tmpTr->$kolomTr) ? $tmpTr->$kolomTr : "";
            }
            $trDatas[$tmpTr->id] = $datas;
            $arrTrIDs[] = $tmpTr->id;
        }
        if (sizeof($arrTrIDs) > 0) {
            $trk = New MdlTransaksi();
            $trk->setFilters(array());
            $fields = array("main");
            $fieldString = implode(",", $fields);
            //            cekHere($fieldString);
            $trk->setJointSelectFields($fieldString . ",transaksi_id");
            //            $trk->addFilter("param='main'");
            $trk->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $trkTmp = $trk->lookupDataRegistries()->result();
            //            showLast_query("kuning");
            if (sizeof($trkTmp) > 0) {
                foreach ($trkTmp as $trkSpec) {
                    //                    arrPrintPink($trkSpec);
                    foreach ($trkSpec as $key_reg => $val_reg) {
                        if (in_array($key_reg, $fields)) {
                            $regTrID = $trkSpec->transaksi_id;
                            $param = $key_reg;
                            $regData = blobDecode($val_reg);

                            if (sizeof($regData) > 0) {
                                foreach ($headerFields as $key => $val) {
                                    if (isset($regData[$key])) {
                                        $regDatas[$regTrID][$key] = $regData[$key];
                                    }
                                }
                                //                        $regDatas[$regTrID]['description_main_followup'] = isset($regData['description_main_followup']) ? $regData['description_main_followup'] : "";
                            }
                        }
                    }
                }
            }
        }
        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(
            "qty_" . $defPosition . "_awal" => "prev (QTY)",
            "in_qty"                        => "in qty",
            "out_qty"                       => "out qty",

            //            "saldo_qty_berjalan" => "saldo (QTY)",

            "qty_" . $defPosition . "_akhir" => "balance (QTY)",
        );
        $headerValueFields = array(
            $defPosition . "_awal" => "prev (IDR)",
            "in"                   => "in (IDR)",
            "out"                  => "out (IDR)",

            "saldo_berjalan" => "saldo (IDR)",

            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        //region buld yIndex
        $itemsCek = array();
        $items0 = array();
        //        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();

            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;

                    //                    arrPrint($subs0);
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            foreach ($xxTemp as $tempLabels) {
                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];
                $detailsLabel[$keyLabel] = $keyValue;
            }
        }
        //endregion


        $title = "";
        // data transaksi dab
        $subItemsAdditional = array();
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);

            foreach ($tmp as $row) {

                if (isset($regDatas[$row->transaksi_id])) {
                    foreach ($regDatas[$row->transaksi_id] as $k_reg => $v_reg) {
                        $row->$k_reg = $v_reg;
                    }
                }

                if (isset($trDatas[$row->transaksi_id])) {
                    foreach ($trDatas[$row->transaksi_id] as $key => $val) {
                        $row->$key = $val;
                    }
                }

                //                arrPrintWebs($row);
                $rekening = $row->rekening;
                $position = detectRekDefaultPosition($rekening);
                switch ($position) {
                    case "debet":
                        if ($row->kredit_awal > 0) {
                            $row->debet_awal = $row->kredit_awal * -1;
                            $row->kredit_awal = 0;
                        }
                        if ($row->kredit_akhir > 0) {
                            $row->debet_akhir = $row->kredit_akhir * -1;
                            $row->kredit_akhir = 0;
                        }
                        break;
                    case "kredit":
                        if ($row->debet_awal > 0) {
                            $row->kredit_awal = $row->debet_awal * -1;
                            $row->debet_awal = 0;
                        }
                        if ($row->debet_akhir > 0) {
                            $row->kredit_akhir = $row->debet_akhir * -1;
                            $row->debet_akhir = 0;
                        }
                        break;
                }

                $subs_r = array();
                foreach ($headerFields as $key => $label) {
                    if (array_key_exists($key, $row)) {
                        if ((isset($row->$key)) && (!is_numeric($row->$key))) {
                            //                            if ($row->$key === base64_encode(base64_decode($row->$key))) {
                            //                            if (in_array($key, $arrDecode)) {
                            ////                                $subs_r[$key] = unserialize(base64_decode($row->$key));
                            //                                $subs_r[$key] = blobDecode($row->$key);
                            //
                            //                            }
                            //                            else {
                            //                                $subs_r[$key] = $row->$key;
                            //                            }
                            $subs_r[$key] = $row->$key;
                        }
                        else {
                            $subs_r[$key] = $row->$key;
                        }
                    }
                }

                $subs_r['saldo_qty_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['qty'] : 0;
                $subs_r['saldo_berjalan'] = isset($saldoBerjalan[$row->id]) ? $saldoBerjalan[$row->id]['value'] : 0;

                $subs_r['transaksi_id'] = $row->transaksi_id;
                $subs_r['review_details'] = $row->transaksi_no;
                //                $subs_r['review_details'] = $row->transaksi_id;

                $modul = isset($this->masterConfigUi[$row->jenis_master]["modul"]) ? $this->masterConfigUi[$row->jenis_master]["modul"] : false;
                $subs_r['modul'] = $modul;
                $subs_r['modul_path'] = base_url() . $modul . "/";
                $subs_r['jenis_master'] = $row->jenis_master;
                $subs_r['hpp_key'] = $row->harga;


                $items[] = $subs_r;
                //arrPrint($subs_r);

                $title = $row->extern_nama;
            }

            // ADDITIONAL ROW DARI LOCKER----------
            foreach ($headerFields as $key => $label) {
                $subItemsAdditional[$key] = isset($finalLocker[$key]) ? $finalLocker[$key] : "";
            }
            $items[] = $subItemsAdditional;
            //-------------------------------------

            //region mengakumulasi berdasarkan hpp yang sama, per-transaksi
            $items_2 = array();
            $items_3 = array();
            if (sizeof($items) > 0) {
                foreach ($items as $xx => $xxSpec) {
                    $items_2[$xxSpec['transaksi_id']][$xxSpec['hpp_key']][] = $xxSpec;
                }

                foreach ($items_2 as $trID => $xxSpec_2) {
                    foreach ($xxSpec_2 as $hpp => $xxSpec_3) {

                        $qty_debet = 0;
                        $qty_kredit = 0;
                        $debet = 0;
                        $kredit = 0;
                        foreach ($xxSpec_3 as $ii => $iiSpec) {
                            //                            $qty_debet += $iiSpec['qty_debet'];
                            //                            $qty_kredit += $iiSpec['qty_kredit'];
                            //                            $debet += $iiSpec['debet'];
                            //                            $kredit += $iiSpec['kredit'];
                        }
                        $xxAkumulasi = $xxSpec_3[0];
                        //                        $xxAkumulasi['qty_debet'] = $qty_debet;
                        //                        $xxAkumulasi['qty_kredit'] = $qty_kredit;
                        //                        $xxAkumulasi['debet'] = $debet;
                        //                        $xxAkumulasi['kredit'] = $kredit;
                        $xxAkumulasi['qty_debet_akhir'] = end($xxSpec_3)['qty_debet_akhir'];
                        //                        $xxAkumulasi['saldo'] = end($xxSpec_3)['saldo'];
                        unset($xxAkumulasi['transaksi_id']);
                        unset($xxAkumulasi['hpp_key']);
                        $items_3[] = $xxAkumulasi;


                    }
                }
            }
            //        arrPrintPink($items_3);
            //endregion

        }


        $link_excel = "";
        // $link_excel = base_url() . "ExcelWriter/mutasi/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID";

        $data = array(
            "mode"     => "mutasiDetails",
            "title"    => $rekName_f . " &nbsp;" . $titleTop . "",
            "subTitle" => " $blob_ext $subTitle_date $subSubTitle_date",
            "items"    => $view_akumulasi == true ? $items_3 : $items,
            "items2"   => $items0,

            //----tambahan locker di bawah---------------
            //            "items_additional" => isset($itemsAdditional) ? $itemsAdditional : array(),
            //            "items2_additional" => isset($items0_additional) ? $items0_additional : array(),
            //-------------------------------------------

            "headerFields"  => $headerFields,
            "headerFields2" => $arrMovedDetail,
            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",
            "link_excel"    => $link_excel,
            "itemsCek"      => $itemsCek,
            "summary"       => isset($this->summaryKey) ? $this->summaryKey : array(),
            "addStyle"      => isset($transaksiIDStyle) ? $transaksiIDStyle : array(),
        );

        $this->load->view("ledger", $data);

    }

    // mutasi dengan qty, @price, total value...
    public function viewMoveDetails_v1()
    {
        $arrMovedDetail = array();
        $relName = $this->uri->segment(3);
        $rekName = urldecode($this->uri->segment(4));
        $externID = $this->uri->segment(5);

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];
        //cekHere(":: $cabangID ::");

        //arrPrint($this->session->login);
        $membership = $this->session->login['membership'];
        //arrPrintPink($membership);

        $defPosition = detectRekDefaultPosition($rekName);
        $opPosition = $defPosition == "kredit" ? "debet" : "kredit";

        $balConfig = isset($this->config->item('accountMoveColumns')[$relName]) ? $this->config->item('accountMoveColumns')[$relName] : array();
        $headLooping = isset($this->config->item('accountMoveColumns')[$relName]['headerLooping']) ? $this->config->item('accountMoveColumns')[$relName]['headerLooping'] : array();
        $accountFilters = isset($this->config->item('accountBalanceColumns')[$relName]['viewFilters']) ? $this->config->item('accountBalanceColumns')[$relName]['viewFilters'] : array();
        $accountChildSourcesMdl = isset($this->config->item('accountChildSources')[$rekName]) ? $this->config->item('accountChildSources')[$rekName] : null;


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $this->load->model("MdlTransaksi");
        $com = new $mdlName();
        $tr = new MdlTransaksi();

        $com->addFilter("cabang_id='$cabangID'");

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


        if (!isset($_GET['date1']) && !isset($_GET['date2'])) {
            /*
             * dimatiin limit karena hanya nglimit tanpa lihat data yang dipilih jadi hasil random,
             */
            // $limit = 100;
            // $this->db->limit("$limit");
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $subTitle_date = "";
            //            $subSubTitle_date = " <span style='font-size:12px;font-style:italic;'>(mutasi $limit terakhir)</span>";
            $subSubTitle_date = "";
        }
        else {
            $this->db->order_by("id", "DESC");

            $date1 = isset($_GET['date1']) ? $_GET['date1'] : date("Y-m-d");
            $date2 = isset($_GET['date2']) ? $_GET['date2'] : date("Y-m-d");

            $this->db->where("fulldate>='" . $date1 . "'");
            $this->db->where("fulldate<='" . $date2 . "'");

            $subTitle_date = lgTranslateTime($date1) . " - " . lgTranslateTime($date2);
            $subSubTitle_date = "";
        }


        $tmp = $com->fetchMoves($rekName, $externID);
        //        cekHere($this->db->last_query() . "<hr> " . __METHOD__);
        //        arrPrint($tmp);
        $titleTop = "";
        if ($accountChildSourcesMdl != null) {
            $this->load->model("Mdls/" . $accountChildSourcesMdl);
            $cmdl = New $accountChildSourcesMdl();
            $rslt = $cmdl->lookupByID($externID)->result();
            $rsltCode = isset($rslt[0]->kode) ? $rslt[0]->kode : "";
            $rsltNama = isset($rslt[0]->nama) ? $rslt[0]->nama : "";
            $titleTop = "$rsltCode $rsltNama";
        }


        //region auto group in - out
        $arrProds = array();
        $tmpTrIds = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            foreach ($tmp as $temX) {
                $transaksi_id = $temX->transaksi_id;
                $position = $temX->debet > 0 ? "debet" : "kredit";
                $val = $temX->debet > 0 ? $temX->debet : $temX->kredit;
                $detectRek = detectRekByPosition($rekName, $val, $position);
                //            cekHere($temX->jenis);
                if ($detectRek > 0) {
                    $arrMovedDetail["in"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["in_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }
                else {
                    $arrMovedDetail["out"][$temX->jenis] = $position;
                    if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
                        $arrMovedDetail["out_qty"][$temX->jenis] = "qty_" . $position;
                        $newSelect = "qty_" . $position;
                        $qtyVal = $temX->$newSelect;
                    }
                    else {
                        $qtyVal = 0;
                    }

                }

                $arrProds[$temX->transaksi_id][$temX->jenis] = array(
                    "$position"        => "$val",
                    "qty_" . $position => $qtyVal,
                    "harga"            => $temX->harga,
                );

                $tmpTrIds[$transaksi_id] = 1;
            }
            $trIds = array_keys($tmpTrIds);
        }
        //endregion


        // region transaksi
        $listId = implode("','", $trIds);
        $tr->setFilters(array());
        $tr->addFilter("id in ('" . $listId . "')");
        $tmpTrs = $tr->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpTrs);
        $refDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $refDatas[$tmpTr->id]["nomer_top"] = $tmpTr->nomer_top;
            $refDatas[$tmpTr->id]["oleh_nama"] = $tmpTr->oleh_nama;
            $refDatas[$tmpTr->id]["cabang_nama"] = $tmpTr->cabang_nama;
            $refDatas[$tmpTr->id]["customers_nama"] = $tmpTr->customers_nama;
            $refDatas[$tmpTr->id]["suppliers_nama"] = $tmpTr->suppliers_nama;
        }
        // endregion transaksi

        if (sizeof($balConfig)) {
            $headerFields = $balConfig['viewedColumns'];

        }
        else {
            die("balance config for $relName is not yet defined");
        }

        if ($cabangID == CB_ID_PUSAT) {
            $headerFields = array(
                "dtime"          => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis"          => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top"      => array(
                    "label"      => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    "format"     => "formatField_he_format",
                ),
                "transaksi_no"   => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    //                    "format" => "formatField_he_format",
                ),
                "suppliers_nama" => array(
                    "label" => "vendor",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "cabang_nama"    => array(
                    "label" => "branch",

                    "attr" => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "oleh_nama"      => array(
                    "label" => "pic by",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
            );
            $total_colspan = sizeof($headerFields);
        }
        else {
            $headerFields = array(
                "dtime"          => array(
                    "label" => "date",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "jenis"          => array(
                    "label" => "note",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "nomer_top"      => array(
                    "label"      => "reference number",
                    "attrHeader" => "class='text-uppercase'",
                    "format"     => "formatField_he_format",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "transaksi_no"   => array(
                    "label" => "receip number",
                    // "attrHeader" => "class=''",
                    "attr"  => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                    //                    "format" => "formatField_he_format",
                ),
                // "cabang_id"    => "branch",
                "oleh_nama"      => array(
                    "label"      => "pic by",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                "customers_nama" => array(
                    "label"      => "customer",
                    "attrHeader" => "class='text-uppercase'",
                    "attr"       => "class='text-left bg-info text-uppercase valign-m' rowspan='2'",
                ),
                // "debet" => "values",
                // "kredit" => "values",
            );
            $total_colspan = sizeof($headerFields);
        }

        $jenisAliases = arrCodeAliasing($cabangID);
        $jmlLooping = sizeof($headLooping);

        // region penyusun / pemecah arai jadi array kecil-kecil
        foreach ($headerFields as $field => $fChilds) {
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
        // endregion penyusun


        // region builder data transaksi yg perlu muncul
        $trDatas = array();
        foreach ($tmpTrs as $tmpTr) {
            $datas = array();
            foreach ($fieldToshows as $hField => $hAlias) {
                $$hField = key_exists($hField, $tmpTr) ? $tmpTr->$hField : "";

                if (array_key_exists($hField, $tmpTr)) {
                    $datas[$hField] = key_exists($tmpTr->$hField, $jenisAliases) ? $jenisAliases[$tmpTr->$hField] : $tmpTr->$hField;
                }
            }
            $modul = isset($this->masterConfigUi[$tmpTr->jenis_master]["modul"]) ? $this->masterConfigUi[$tmpTr->jenis_master]["modul"] : false;
            $datas['modul'] = $modul;
            $datas['modul_path'] = base_url() . $modul . "/";
            $datas['jenis_master'] = $tmpTr->jenis_master;
            $trDatas[$tmpTr->id] = $datas;
        }
        //                arrPrint($trDatas);
        // endregion builder data transaksi yg perlu muncul

        $headerQtyFields = array(

            "qty_a" => array(
                "label" => "saldo awal",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),

            "in_qty"                         => array(
                "label" => "in",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            "out_qty"                        => array(
                "label" => "out",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            "qty_" . $defPosition . "_akhir" => array(
                "label" => "saldo",
                "attr"  => "class='text-center' colspan='$jmlLooping'",
            ),
            // "avg_price" => array(
            //     "label" => "avg price",
            //     "attr" => "class='text-center valign-m' rowspan='2'",
            // ),


        );
        $headerValueFields = array(
            $defPosition . "_awal"  => "prev (IDRs)",
            "in"                    => "in (IDR)",
            "out"                   => "out (IDR)",
            $defPosition . "_akhir" => "balance (IDR)",
        );
        if (isset($balConfig['showQty']) && $balConfig['showQty'] == true) {
            $headerFields = $headerFields + $headerQtyFields;
        }
        if (isset($balConfig['showValue']) && $balConfig['showValue'] == true) {
            $headerFields = $headerFields + $headerValueFields;
        }


        //region buld yIndex
        //        arrPrint($arrMovedDetail);
        //        arrPrint($arrProds);
        $itemsCek = array();
        $items0 = array();
        krsort($arrProds);
        foreach ($arrProds as $row0) {
            $temX = array();
            foreach ($arrMovedDetail as $yparent => $ySpec) {
                $subs0 = array();
                foreach ($ySpec as $jn => $colloumb) {

                    if (isset($row0[$jn][$colloumb])) {
                        $val = $row0[$jn][$colloumb];
                    }
                    else {
                        $val = 0;
                    }
                    $subs0[$jn] = $val;

                    $subItemCek[$yparent] = $val;
                }
                $temX[$yparent] = $subs0;
            }
            $items0[] = $temX;
            $itemsCek[] = $subItemCek;
        }
        //        krsort($items0);
        //        arrPrint($items0);
        //        arrPrint($itemsCek);
        //endregion


        //region label detil transaksi
        $childHeaderLabels = $this->config->item('heTransaksi_ui');
        //        arrPrint($childHeaderLabels);
        $detailsLabel = array();
        foreach ($childHeaderLabels as $tempSpec) {
            $xxTemp = $tempSpec["steps"];
            //            arrPrint($xxTemp);

            foreach ($xxTemp as $tempLabels) {

                $keyLabel = $tempLabels["target"];
                $keyValue = $tempLabels["label"];

                $detailsLabel[$keyLabel] = $keyValue;
            }
        }

        //        arrPrint($detailsLabel);
        //endregion


        $arrCodeAliasing = arrCodeAliasing($cabangID);

        $title = "";
        $items = array();
        if (sizeof($tmp) > 0) {
            krsort($tmp);
            $mainDatas2 = array();
            foreach ($tmp as $row) {

                $subs = array();
                foreach ($fieldToshows as $key => $label) {

                    $refDatas_2 = $refDatas[$row->transaksi_id];
                    //arrPrint($refDatas_2);

                    if (array_key_exists($key, $row)) {
                        $subs[$key] = array_key_exists($row->$key, $jenisAliases) ? $jenisAliases[$row->$key] : $row->$key;

                    }
                    else {
                        //                        cekUngu("-- $key -- " . $row->$key);
                    }

                    if ($key == 'jenis') {
                        $mValue = $arrCodeAliasing[$row->$key];
                    }
                    else {
                        $mValue = isset($row->$key) ? $row->$key : 0;
                    }


                    $mainDatas[$key] = isset($row->$key) ? $mValue : $refDatas_2[$key];
                }

                //                $subs_r = array_replace($subs, isset($trDatas[$row->transaksi_id]) ? $trDatas[$row->transaksi_id] : array());

                $jenis = $row->jenis;
                $saldo = $row->qty_debet_akhir * $row->harga_avg;
                //                cekHitam($jenis);
                //                arrPrintWebs($row);
                // cekLime($row->qty_debet_awal);

                switch ($jenis) {

                    // in
                    // case "9999":
                    // case "19855":
                    //
                    //     $mainDatas2["qty_debet"] = $row->qty_debet;
                    //     $mainDatas2["harga_debet"] = $row->harga;
                    //     $mainDatas2["debet"] = $row->debet;
                    //
                    //     $mainDatas2["qty_kredit"] = 0;
                    //     $mainDatas2["harga_kredit"] = 0;
                    //     $mainDatas2["kredit"] = 0;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;
                    //
                    // // ot
                    // case "5855":
                    // case "3344":
                    // case "382spd":
                    //     $mainDatas2["qty_debet"] = 0;
                    //     $mainDatas2["harga_debet"] = 0;
                    //     $mainDatas2["debet"] = 0;
                    //
                    //     $mainDatas2["qty_kredit"] = $row->qty_kredit;
                    //     $mainDatas2["harga_kredit"] = $row->harga;
                    //     $mainDatas2["kredit"] = $row->kredit;
                    //
                    //     $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                    //     $mainDatas2["harga_saldo"] = $row->harga;
                    //     $mainDatas2["saldo"] = $saldo;
                    //
                    //     // $mainDatas2["harga_avg"] = $row->harga_avg;
                    //     break;

                    // saldo
                    default:
                        $mainDatas2["qty_debet_awal"] = $row->qty_debet_awal;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_debet_awal"] = $row->qty_debet_awal > 0 ? $row->harga_awal : 0;
                            $mainDatas2["debet_awal"] = $row->debet_awal;
                        }
                        else {
                            //                            $mainDatas2["harga_debet_awal"] = "no access authority";
                            //                            $mainDatas2["debet_awal"] = "no access authority";
                            $mainDatas2["harga_debet_awal"] = 0;
                            $mainDatas2["debet_awal"] = 0;
                        }

                        $mainDatas2["qty_debet"] = $row->qty_debet;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_debet"] = $row->qty_debet > 0 ? $row->harga : 0;
                            $mainDatas2["debet"] = $row->debet;
                        }
                        else {
                            $mainDatas2["harga_debet"] = 0;
                            $mainDatas2["debet"] = 0;
                        }

                        $mainDatas2["qty_kredit"] = $row->qty_kredit;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_kredit"] = $row->qty_kredit > 0 ? $row->harga : 0;
                            $mainDatas2["kredit"] = $row->kredit;
                        }
                        else {
                            $mainDatas2["harga_kredit"] = 0;
                            $mainDatas2["kredit"] = 0;
                        }

                        $mainDatas2["qty_saldo"] = $row->qty_debet_akhir;
                        if ($cabangID == CB_ID_PUSAT) {
                            $mainDatas2["harga_saldo"] = $row->qty_debet_akhir > 0 ? $row->harga : 0;
                            $mainDatas2["saldo"] = $saldo;
                        }
                        else {
                            $mainDatas2["harga_saldo"] = 0;
                            $mainDatas2["saldo"] = 0;
                        }
                        // $mainDatas2["harga_avg"] = 0;
                        break;
                }

                $transaksi_id = $row->transaksi_id;
                //                arrPrintWebs($trDatas[$transaksi_id]);

                $mainDatas2['modul'] = isset($trDatas[$transaksi_id]['modul']) ? $trDatas[$transaksi_id]['modul'] : "";
                $mainDatas2['modul_path'] = isset($trDatas[$transaksi_id]['modul_path']) ? $trDatas[$transaksi_id]['modul_path'] : "";
                $mainDatas2['jenis_master'] = isset($trDatas[$transaksi_id]['jenis_master']) ? $trDatas[$transaksi_id]['jenis_master'] : "";
                $mainDatas2['transaksi_id'] = $transaksi_id;
                $mainDatas2['hpp_key'] = $row->harga;
                $items[] = $mainDatas + $mainDatas2;

                $title = $row->extern_nama;
            }
        }


        //region mengakumulasi berdasarkan hpp yang sama, per-transaksi
        $items_2 = array();
        $items_3 = array();
        if (sizeof($items) > 0) {
            foreach ($items as $xx => $xxSpec) {
                $items_2[$xxSpec['transaksi_id']][$xxSpec['hpp_key']][] = $xxSpec;
            }

            foreach ($items_2 as $trID => $xxSpec_2) {
                foreach ($xxSpec_2 as $hpp => $xxSpec_3) {

                    $qty_debet = 0;
                    $qty_kredit = 0;
                    $debet = 0;
                    $kredit = 0;
                    foreach ($xxSpec_3 as $ii => $iiSpec) {
                        $qty_debet += $iiSpec['qty_debet'];
                        $qty_kredit += $iiSpec['qty_kredit'];
                        $debet += $iiSpec['debet'];
                        $kredit += $iiSpec['kredit'];
                    }
                    $xxAkumulasi = $xxSpec_3[0];
                    $xxAkumulasi['qty_debet'] = $qty_debet;
                    $xxAkumulasi['qty_kredit'] = $qty_kredit;
                    $xxAkumulasi['debet'] = $debet;
                    $xxAkumulasi['kredit'] = $kredit;
                    $xxAkumulasi['qty_saldo'] = end($xxSpec_3)['qty_saldo'];
                    $xxAkumulasi['saldo'] = end($xxSpec_3)['saldo'];
                    unset($xxAkumulasi['transaksi_id']);
                    unset($xxAkumulasi['hpp_key']);
                    $items_3[] = $xxAkumulasi;


                }
            }
        }
        //        arrPrintPink($items_3);
        //endregion


        //region prepare params to viewer
        $propertyFields = array(
            "qty_debet_awal"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet_awal" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => false,
            ),
            "debet_awal"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_debet"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_debet" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "debet"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_kredit"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "harga_kredit" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),
            "kredit"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                "sumRow" => true,
            ),

            "qty_saldo"   => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),
            "harga_saldo" => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),
            "saldo"       => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
                // "sumRow" => true,
            ),

            "harga_avg"    => array(
                "format" => "formatField_he_format",
                "attr"   => "class='text-right'",
            ),
            "transaksi_no" => array(
                "format" => "formatField_he_format",
                // "attr" => "class='text-right'",
            ),
            "dtime"        => array(
                "format" => "formatField_he_format",
                // "attr" => "class='text-right'",
            ),
        );
        $summaryKey = array(
            //            "qty_debet_awal",
            //            "debet_awal",
            "qty_debet",
            "debet",
            "qty_kredit",
            "kredit",
        );

        $data = array(
            "mode"           => "mutasiDetails_v1",
            "title"          => $titleTop,
            "subTitle"       => "&nbsp; $subTitle_date $subSubTitle_date",
            //            "items" => $items,
            "items"          => $items_3,
            "items2"         => $items0,
            "fieldToshows"   => $fieldToshows,
            "propertyFields" => $propertyFields,
            "headerFields"   => $headerFields,
            // "headerFields2" => $arrMovedDetail,
            "headerFields2"  => $headLooping,
            "total_colspan"  => $total_colspan,


            "filters"       => array(
                "dates" => $this->dates,
                "date1" => $date1,
                "date2" => $date2,
            ),
            "detailsLabels" => $detailsLabel,
            "thisPage"      => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?o=$cabangID",

            "itemsCek"   => $itemsCek,
            "summaryKey" => $summaryKey,
            "trDatas"    => isset($trDatas) ? $trDatas : array(),
            "notToShow"  => array(
                "modul", "modul_path", "jenis_master"
            ),
        );
        //endregion


        $this->load->view("ledger", $data);

    }

    //----------------------------
    public function viewBalances_periode()
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

        $q = isset($_GET['q']) && strlen($_GET['q']) ? $_GET['q'] : "";
        $sortBy = isset($_GET['sortBy']) && strlen($_GET['sortBy']) ? $_GET['sortBy'] : "extern_nama";
        $sortMode = isset($_GET['sortMode']) && strlen($_GET['sortMode']) ? $_GET['sortMode'] : "ASC";

        $getExternID = isset($_GET['ext_id']) && strlen($_GET['ext_id']) ? $_GET['ext_id'] : NULL;
        $getExtern2ID = isset($_GET['ext2_id']) && strlen($_GET['ext2_id']) ? $_GET['ext2_id'] : NULL;
        $getMainExtern2ID = isset($_GET['main_ext2_id']) && strlen($_GET['main_ext2_id']) ? $_GET['main_ext2_id'] : NULL;
        $blob_ext = isset($_GET['blob_ext']) && strlen($_GET['blob_ext']) ? blobDecode($_GET['blob_ext']) : NULL;
        $dateSelect = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

        $cabangID = (isset($_GET['o']) && $_GET['o'] <> 0) ? $_GET['o'] : $this->session->login['cabang_id'];


        $thisPage = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?o=$cabangID";
        $thisURL = base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?q=$q&o=$cabangID";


        $mdlName = "Com" . $relName;

        $this->load->helper("he_mass_table");
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        //        cekHere(":: $mdlName ::");

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

        //------
        $periode = "bulanan";
        //        $dateSelect = "2022-12-31";
        //        $com->addFilter("gudang_id=9");
        //------
        //        $tmp = $com->fetchBalancesByPeriode($rekName, $q, $sortBy, $sortMode, $periode, $dateSelect);
        $tmp = $com->fetchMoves2_periode($rekName, $dateSelect);
        //        showLast_query("biru");
        //        arrPrintWebs($tmp);
        //endregion
        //mati_disini(__LINE__);


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
                    "due_date"    => $tmpSrcDue_tmp->due_date,
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
                        "aging"    => $dtime_val['aging_dtime'],
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
                        "aging"    => umurDay($aging) > 0 ? umurDay($aging) : "0",
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


        $masterResult = array();
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
            //                        cekHere($this->db->last_query());
            //                        arrPrint($mdlResult);
            if (sizeof($mdlResult) > 0) {
                foreach ($mdlResult as $rSpec) {
                    //                    arrPrintWebs($rSpec);
                    foreach ($balConfig['pairedModel']['fieldName'] as $key => $val) {
                        $pairedResult[$rSpec->$balConfig['pairedModel']['key']][$key] = isset($rSpec->$val) ? $rSpec->$val : "";
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
                        $kode = isset($rSpec->kode) ? $rSpec->kode : "";
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
                        "keterangan"   => $keterangan,
                        //                        "link_history" => $historyClick,
                        "link_history" => NULL,
                    );


                    $masterResult[$rSpec->id] = (array)$rSpec;
                }
            }


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


        if (isset($balConfig['additionalPairedModel']) && sizeof($balConfig['additionalPairedModel']) > 0) {
            $addMdlNameRek = $balConfig['additionalPairedModel']['mdlNameRek'];
            $addMethodRek = $balConfig['additionalPairedModel']['mdlMethodRek'];
            $addMethodRek_moves = $balConfig['additionalPairedModel']['mdlMethodRek_moves'];
            $addPrefix = $balConfig['additionalPairedModel']['prefix'];

            $addMdlNameData = $balConfig['additionalPairedModel']['mdlNameData'];
            $addMethodData = $balConfig['additionalPairedModel']['mdlMethodData'];

            $this->load->model("Mdls/$addMdlNameData");
            $dt = New $addMdlNameData();
            $dt->setFilters(array());
            $dt->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $dt->addFilter("trash=0");
            $dt->addFilter("status=1");
            $dt->addFilter("id>0");
            $tmpData = $dt->$addMethodData()->result();
            //            $tmpData = $dt->fetchMoves2_periode($rekName, $dateSelect);
            //            showLast_query("kuning");
            if (sizeof($tmpData) > 0) {
                $ids = array();
                foreach ($tmpData as $spec) {
                    $ids[] = $spec->id;
                }

                $dtr = New $addMdlNameRek();
                $dtr->setFilters(array());
                $dtr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                $dtr->addFilter("gudang_id in ('" . implode("','", $ids) . "')");
                //                $tmpData = $dtr->$addMethodRek($rekName, $q, $sortBy, $sortMode);
                $tmpData = $dtr->$addMethodRek_moves($rekName, $dateSelect);
                //                                showLast_query("merah");
                //                cekMerah(sizeof($tmpData));
                $addDataResult = array();
                if (sizeof($tmpData) > 0) {
                    foreach ($tmpData as $tmpSpec) {
                        if (sizeof($balConfig['additionalViewedColumns']) > 0) {
                            foreach ($balConfig['additionalViewedColumns'] as $addKey => $addVal) {
                                $addKey_fix = str_replace($addPrefix, "", $addKey);
                                $addDataResult[$tmpSpec->extern_id][$addKey] = $tmpSpec->$addKey_fix;
                            }
                        }
                    }
                }

            }
        }
        //        arrPrint($addDataResult);

        //region show locker value
        $finalLocker = array();
        if (sizeof($accountBalanceLocker) > 0) {
            if ($accountBalanceLocker['enabledView'] == true) {
                $mdlLocker = $accountBalanceLocker['mdlName'];
                $this->load->model("Mdls/" . $mdlLocker);
                $lo = new $mdlLocker();
                $stateTmp = $accountBalanceLocker['state'];
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
            foreach ($tmp as $row) {                //                arrPrintWebs($row);
                //                arrPrintWebs($row);
                if (isset($dueEmployee) && (sizeof($dueEmployee) > 0)) {
                    if (isset($dueEmployee[$row->extern_id])) {
                        foreach ($dueEmployee[$row->extern_id] as $advKey => $valKey) {
                            $row->$advKey = $valKey;
                        }
                    }
                }

                $rekening = $row->rekening;
                $extern_id = $row->extern_id;
                $extern_nama = $row->extern_nama;
                $position = detectRekDefaultPosition($rekening);

                $rekening_relasi_tmp = isset($rkTempResult[$row->extern_nama]) ? $rkTempResult[$row->extern_nama] : array();
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

                }
                // pembantu tingkat 3
                elseif (isset($accountSuperSubChilds[$extern_nama])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekOrange("ada relasi dengan accountSuperSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSuperSubChilds[$extern_nama] . "/" . $extern_nama . "?ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getMainExtern2ID . "&blob_ext=$text";

                }
                // pembantu tingkat 2
                elseif (isset($accountSubChilds[$rekening])) {
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    //                    cekPink("ada relasi dengan accountSubChilds, $extern_nama, $rekening, $text");
                    $tmpRow['link'] = base_url() . "Ledger/viewBalances_l1/" . $accountSubChilds[$rekening] . "/" . $rekening . "?ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";
                    $tmpRow['link_main']['extern_nama'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $row->extern_id . "&main_ext2_id=" . $row->extern_id . "&blob_ext=$text";

                }
                //
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

                }
                // tidak ada pembantu
                else {
                    //                    cekOrange("TIDAK ada relasi dengan accountSubChilds, $extern_nama, $rekening");
                    $text_pair = $blob_ext . " " . $extern_nama;
                    $text = blobEncode($text_pair);
                    if ($rekening != "laba ditahan") {
                        $tmpRow['link'] = base_url() . "Ledger/viewMoveDetails/$relName/$rekName/" . $row->extern_id . "?o=$cabangID" . "&ext2_id=" . $getExtern2ID . "&main_ext2_id=" . $getExtern2ID . "&blob_ext=$text";
                        $tmpRow['link_main']['extern_nama'] = NULL;
                    }
                    else {
                        $tmpRow['link'] = "#";
                        $tmpRow['link_main']['extern_nama'] = NULL;
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
                        "main"   => $no_main,
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
                if (isset($advHeader) && sizeof($advHeader) > 0) {
                    foreach ($advHeader as $advKey => $advVal) {
                        $items[$no][$advKey] = isset($row->$advKey) ? $row->$advKey : "-";
                    }
                }

                //                break;
            }
        }


        //ganti headerFields
        $headerFields = array(
            "rek_id" => "kode",
            "pId"    => "pID",
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
        $headerFields["satuan"] = "UOM";

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
                "qty_" . $defPosition => "balance (QTY)",
            );
            $headerValueFields = array(
                $defPosition => "balance (IDR)",
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

        //arrPrintWebs($items);
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
            "mdl"       => isset($mdlData) && (strlen($mdlData) > 5) ? $mdlData : $mdlName,
            "fifo"      => isset($mdlData) && (strlen($mdlData) > 5) ? "MdlFifoSupplies" : "MdlFifoProdukJadi",
            // "mdl_data"  => $mdlData,
            "cabang_id" => $cabangID,
        );
        $param_to_excel_e = str_replace("=", "", blobEncode($param_to_excel));

        //        arrPrintWebs($items);
        //        arrPrintWebs($masterResult);
        if (sizeof($masterResult) > 0) {
            $items_old = $items;
            unset($items);
            foreach ($masterResult as $pid => $mSpec) {
                $mSpec["pId"] = $mSpec["id"];
                $mSpec["extern_id"] = $mSpec["id"];
                $mSpec["extern_nama"] = $mSpec["nama"];
                $addSpecc = array();
                foreach ($items_old as $ioSpec) {
                    if ($ioSpec["pId"] == $pid) {
                        $addSpecc = $ioSpec;
                        break;
                    }
                }
                $items[$pid] = $mSpec + $addSpecc;
            }
        }
        //        arrPrintWebs($items);
        //        mati_disini(__LINE__);


        $rekName_f = isset(fetchAccountStructureAlias()[$rekName]) ? fetchAccountStructureAlias()[$rekName] : $rekName;
        $dateSelect_f = " per " . lgTranslateTime4($dateSelect);
        $data = array(
            "mode"             => "saldo",
            "title"            => "$rekName_f $dateSelect_f",
            "subTitle"         => "$subTitle $blob_ext",
            "items"            => $items,
            "headerFields"     => $headerFields,
            "thisPage"         => $thisPage,
            "thisURL"          => $thisURL,
            "q"                => $q,
            //            "inspectTarget_mutasi" => base_url() . "Ledger/viewMoves_l2/$relName/$rekName/",
            "summary"          => $summaryAllowed,
            "items_blok"       => $items_blok,
            //            "param_to_excel" => $param_to_excel_e,
            "pairedResult_add" => isset($pairedResult_add) ? $pairedResult_add : array(),
            "dateSelected"     => true,
            "defaultDate"      => $dateSelect,
            "oldDate"          => "2019-09-01",
        );
        //endregion


        $this->load->view("ledger", $data);


    }


    //untuk view master serial
    public function viewserial()
    {
        //        arrPrint($_GET);
        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $fields = array(
            "dtime" => "tgl masuk",
            "extern2_nama" => "sku",
            "extern_nama" => "serial",
            "qty_debet" => "jumlah",
            //             "barcode"=>"barcode",
            "qr" => "QR",
        );

        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProduk2");
        $tr = new ComRekeningPembantuProdukPerSerial();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        $tr->addFilter("qty_debet>0");
        $this->db->order_by("dtime", "asc");
        $items = $tr->fetchBalances("1010030030");
//        cekBiru($this->db->last_query());
        $datas = "<table class='table table-bordered table hover'>";
        $datas .= "<tr>";
        $datas .= "<th>No</th>";
        foreach ($fields as $kk => $label) {
            $datas .= "<th>$label</th>";
        }
        $datas .= "</tr>";

        if (count($items) > 0) {
            $ix = 0;
            foreach ($items as $ii => $items_0) {
                if (!isset($totalSku[$items_0->extern2_nama])) {
                    $totalSku[$items_0->extern2_nama] = 0;
                }
                $totalSku[$items_0->extern2_nama] += $items_0->qty_debet;
                $ix++;
                $datas .= "<tr>";
                $datas .= "<td>$ix</td>";
                $serial = $items_0->extern_nama;
                foreach ($fields as $k => $alias) {
                    $datas .= "<td>";

                    if ($k == "qr") {
                        $qrcode = $qr->get_qrcode_pembelian($serial);
                        $qrfile = base_url() . $qrcode['file'];
                        $listed = "<div class='bottom-borders' style='hheight: 30px;'>";
                        $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                        $listed .= "</div>";
                        $listed .= "</div>";

                        $listed .= "<img src='$qrfile' title='$ii' class='img-thumbnail' style='width: 85px'>";
                        $listed .= "</div>";
                        $value = "$listed";
                    }
                    //                    elseif ($k=="barcode"){
                    //                        $listed1 = "<div class='bottom-borders' style='hheight: 90px;'>";
                    //                        $listed1 .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    //                        $listed1 .= "<svg class='thumbnail' id='r_$ii' style='width:85px;height:90px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                    //                        $listed1 .= "<script>JsBarcode('#r_$ii', '$serial', {format: 'code128', lineColor: '#0d1720'});</script>";
                    //                        $listed1 .= "</div>";
                    //                        $listed1 .= "</div>";
                    //                        $value="$listed1";
                    //                    }
                    else {
                        $value = isset($items_0->$k) ? $items_0->$k : "";
                    }
                    $datas .= "$value";
                    $datas .= "</td>";
                }
                $datas .= "</tr>";
            }
        }

        //        arrPrint($totalSku);
        $datas .= "</table>";
        $headerTitle = "<div>";
        foreach ($totalSku as $sku => $jml) {
            $headerTitle .= "<div><span>$sku</span> : <span>$jml</span></div>";
        }
        $headerTitle .= "</div>";
        echo $headerTitle;
        echo $datas;

    }

    public function doRemoveSerial()
    {
        arrPrint(url_segment());
        $produk_id = url_segment(3);
        $this->db->trans_start();
        $tbl = "_rek_pembantu_produk_perserial_cache";
        $tbl_2 = "__rek_pembantu_produk_perserial__1010030030";
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $cps = new ComRekeningPembantuProdukPerSerial();

        $my_nama = my_name();
        $cabang_id = my_cabang_id();
        $rekening = "1010030030";
        /*---cek data---*/
        $condite = array(
            "produk_id"  => $produk_id,
            "cabang_id"  => $cabang_id,
            "qty_debet>" => 0,
            // "rekening" => '1010030030',
            // "note" => 'dikosongkan by ' . my_name(),
        );

        $this->db->where($condite);
        $items = $cps->fetchBalances($rekening);
        // showLast_query("orange");
        // arrPrint($items);

        if (count($items) > 0) {
            foreach ($items as $item) {
                $serial = $item->extern_nama;
                /*---cache---*/
                // $condite = array(
                //     "produk_id" => $produk_id,
                //     "cabang_id" => $cabang_id,
                //     "debet" => 0,
                // );
                $updDatas = array(
                    "debet"     => 0,
                    "qty_debet" => 0,
                );
                $cps->setTableName($tbl);
                $upd = $cps->updateData($condite, $updDatas);
                showLast_query("hijau");

                /*---mutasi---*/
                $newDatas = array(
                    "debet"       => 0,
                    "kredit"      => 1,
                    "extern_nama" => $serial,
                    "dtime"       => dtimeNow(),
                    "produk_id"   => $item->produk_id,
                    "produk_nama" => $item->produk_nama,
                    "cabang_id"   => $cabang_id,
                    "rekening"    => $rekening,
                    "keterangan"  => "dikosongkan by $my_nama",
                );
                $cps->setTableName($tbl_2);
                $cps->addData($newDatas);
                showLast_query("kuning");
            }
        }


        // matiHere("Transaksi normal (Dalam mode debuger) " . __LINE__ . __METHOD__ . " " . $produk_id . " $upd");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        if (count($items) > 0) {
            echo lgShowSuccess("Berhasil", "Serial Number telah dihapus");
        }
        else {
            echo lgShowWarning("Upss..", "Serial number yang dicari sudah tidak ada");
        }
    }

    public function viewserialTool()
    {
        //        arrPrint($_GET);
        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $fields = array(
            "dtime"        => "tgl masuk",
            "extern2_nama" => "sku",
            "extern_nama"  => "serial",
            "qty_debet"    => "jumlah",
            //             "barcode"=>"barcode",
            "qr"           => "QR",
        );

        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProduk2");
        $tr = new ComRekeningPembantuProdukPerSerial();

        $tools = 1;
        if($tools == 1){


            $this->load->model("Mdls/MdlProdukPerSerialNumber");
            $pps = new MdlProdukPerSerialNumber();
            $conditions = array(
                "produk_id" => $produk_id,
            );
            $srcPps = $pps->lookupByCondition($conditions)->result();
            showLast_query("merah");
            // arrPrint($srcPps);
            foreach ($srcPps as $srcPp) {
                $produk_serial_number_2 = $srcPp->produk_serial_number_2;

                // echo $produk_serial_number_2 ." ";
                $serialIn[] = $produk_serial_number_2;
            }
            $jmlSerialIn = count($serialIn);
            cekMerah("jmlSerialIn : $jmlSerialIn");
            // ------------------------------end off serial hasil generede
            // ---------------out
            $tbl_2 = "__rek_pembantu_produk_perserial__1010030030";
            $tbl_4 = "__rek_pembantu_produk__1010030030";

            //----------mutasi out cabang
            $condites = array(
                "produk_id"  => $produk_id,
                // "qty_kredit" => 1,
                "qty_debet" => 0,
                "cabang_id" => my_cabang_id(),
            );
            $tr->setTableName($tbl_2);
            $srcMutasi = $tr->lookupByCondition($condites)->result();
            showLast_query("biru");
            // arrPrintKuning($srcMutasi);
            foreach ($srcMutasi as $item) {
                $serial_out = $item->extern_nama;

                $arrSerialOut[] = $serial_out;
            }
            $jmlSerialOtCabang = count($arrSerialOut);
            cekBiru("jmlSerialOtCabang : $jmlSerialOtCabang");

            // -------------------out pusat
            $condites = array(
                "produk_id"  => $produk_id,
                // "qty_kredit" => 1,
                "qty_debet" => 0,
                "cabang_id" => '-1',
                "jenis" => '585',
            );
            $tr->setTableName($tbl_2);
            $srcMutasi = $tr->lookupByCondition($condites)->result();
            showLast_query("kuning");
            // arrPrintKuning($srcMutasi);
            $arrSerialOut = array();
            foreach ($srcMutasi as $item) {
                $serial_out = $item->extern_nama;

                $arrSerialOut[] = $serial_out;
            }
            $jmlSerialOtPusat = count($arrSerialOut);
            cekKuning("jmlSerialOtPusat : $jmlSerialOtPusat");

            // ------------------------------------------------------produk out
            $tr->setTableName($tbl_4);
            $condites = array(
                "extern_id"  => $produk_id,
                "qty_kredit>" => 0,
                // "qty_debet" => 0,
                // "cabang_id" => '-1',
                "jenis" => '5822spd',
            );
            $arrKoloms = array(
                "sum(qty_kredit) as 'sum_qty_kredit'"
            );
            $this->db->select($arrKoloms);
            $srcMutasiProduk = $tr->lookupByCondition($condites)->row();
            showLast_query("pink");
            // arrPrint($srcMutasiProduk);
            $jmlProdukOt = $srcMutasiProduk->sum_qty_kredit;
            cekPink("ot produk 5822spd : $jmlProdukOt serial");
            // ------------------------------------------------

            // cekHijau("produk_per_serialnumber : ".count($serialIn));


            $jmlSerialOt = $jmlSerialOtPusat + $jmlSerialOtCabang;
            cekHijau("in : $jmlSerialIn");
            // cekKuning("$tbl_2 : ".count($arrSerialOut));
            cekKuning("ot serial : $jmlSerialOt  =  $jmlSerialOtPusat + $jmlSerialOtCabang");

            //-------------------cache sisa cabang
            $tbl = "_rek_pembantu_produk_perserial_cache";
            $tbl_3 = "_rek_pembantu_produk_cache";
            $tr->setFilters(array());
            $tr->setTableName($tbl);
            $tr->addFilter("produk_id='$produk_id'");
            $tr->addFilter("qty_debet>0");
            $tr->addFilter("cabang_id=1");
            // ------------------------------------------------
            $items = $tr->fetchBalances("1010030030");
            cekOrange($this->db->last_query());
            foreach ($items as $ii => $items_0) {
                $serial = $items_0->extern_nama;

                $arrSerialSisa[] = $serial;
            }

            $jmlSerialSisa = count($arrSerialSisa);
            cekOrange("sisa : $jmlSerialSisa");

            // --------------------------sisa pusat
            $tr->setFilters(array());
            $tr->setTableName($tbl);
            $tr->addFilter("produk_id='$produk_id'");
            $tr->addFilter("qty_debet>0");
            $tr->addFilter("cabang_id='-1'");
            // ------------------------------------------------
            $items = $tr->fetchBalances("1010030030");
            cekHijau($this->db->last_query());
            $arrSerialSisa = array();
            foreach ($items as $ii => $items_0) {
                $serial = $items_0->extern_nama;

                $arrSerialSisa[] = $serial;
            }
            $jmlSerialSisaPusat = count($arrSerialSisa);
            cekHijau("jmlSerialSisaPusat : $jmlSerialSisaPusat ");
            $jmlSerialCamp = $jmlSerialSisa + $jmlSerialSisaPusat + $jmlSerialOt;

            cekMerah("sisa + ot : $jmlSerialSisa + $jmlSerialSisaPusat + $jmlSerialOt = $jmlSerialCamp");
        }


        // ---------------------------------dipakai ui---------------------------

        // echo $this->viewserial();
    }

}