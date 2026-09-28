<?php


class SetupBungaPihak3 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //loader library
        $this->load->library('pagination');
        $this->load->library("MobileDetect");

        //loader helper
        $this->load->helper("he_session_replacer");


        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(3);


        //region relation translator
        $this->relations = array();
        $this->relationPairs = array();
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            $fields = $o->getFields();
            foreach ($fields as $fName => $f2Spec) {
                if (isset($f2Spec['reference'])) {
                    $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                    $this->load->model("Mdls/" . $f2Spec['reference']);
                    $o3 = new $f2Spec['reference']();
                    $tmp3 = $o3->lookupAll()->result();
                    if (sizeof($tmp3) > 0) {
                        $mdlName = $f2Spec['kolom'];
                        $this->relationPairs[$mdlName] = array();
                        foreach ($tmp3 as $row3) {
                            $idxField = (null != $o3->getIndexFields()) ? $o3->getIndexFields() : "id";
                            $id = isset($row3->$idxField) ? $row3->$idxField : 0;
                            $name = isset($row3->nama) ? $row3->nama : "";
                            if (isset($row3->name)) {
                                $name = $row3->name;
                            }
                            $this->relationPairs[$mdlName][$id] = $name;
                        }
                    }
                    else {
                    }
                }
            }
        }

        //endregion

        $dataAccess = isset($this->config->item('heDataBehaviour')[$className]) ? $this->config->item('heDataBehaviour')[$className] : array(
            "viewers" => array(),
            "creators" => array(),
            "creatorAdmins" => array(),
            "updaters" => array(),
            "updaterAdmins" => array(),
            "deleters" => array(),
            "deleterAdmins" => array(),
            "historyViewers" => array(),
        );


        $menus = isset($this->config->item('menuConfig')['data']) ? $this->config->item('menuConfig')['data'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($dataAccess['view'])) {
            if (sizeof($menus) > 0) {
                foreach ($menus as $m => $rowSpec) {
                    if (!in_array($dataAccess['view'], $mems)) {
                        $this->pageMenu .= "<li><a href='" . base_url() . "$m'><span class='glyphicon glyphicon-hdd'></span>$rowSpec</a> </li>";
                    }
                }
                $this->pageMenu .= "<li><a href='authLogout'><span class='glyphicon glyphicon-off'>Keluar</a></li>";
            }
        }

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $this->allowView = false;
        $this->allowCreate = false;
        $this->allowEdit = false;
        $this->allowDelete = false;

        $this->allowCreateApproval = false;
        $this->allowEditApproval = false;
        $this->allowDeleteApproval = false;
        foreach ($mems as $mID) {
            if (in_array($mID, $dataAccess['viewers'])) {
                $this->allowView = true;
            }
            if (in_array($mID, $dataAccess['historyViewers'])) {
                $this->allowViewHistory = true;
            }
            if (in_array($mID, $dataAccess['creators'])) {
                $this->allowCreate = true;
            }
            if (in_array($mID, $dataAccess['updaters'])) {
                $this->allowEdit = true;
            }
            if (in_array($mID, $dataAccess['deleters'])) {
                $this->allowDelete = true;
            }

            if (in_array($mID, $dataAccess['creatorAdmins'])) {
                $this->allowCreateApproval = true;
            }
            if (in_array($mID, $dataAccess['updaterAdmins'])) {
                $this->allowEditApproval = true;
            }
            if (in_array($mID, $dataAccess['deleterAdmins'])) {
                $this->allowDeleteApproval = true;
            }
        }

        if (sizeof($dataAccess['creatorAdmins']) > 0) {
            $this->creatorUsingApproval = true;
        }
        else {
            $this->creatorUsingApproval = false;
        }
        if (sizeof($dataAccess['updaterAdmins']) > 0) {
            $this->updaterUsingApproval = true;
        }
        else {
            $this->updaterUsingApproval = false;
        }
        if (sizeof($dataAccess['deleterAdmins']) > 0) {
            $this->deleterUsingApproval = true;
        }
        else {
            $this->deleterUsingApproval = false;
        }

//---init listed-fields
//        $className = "Mdl" . $this->uri->segment(1).$this->uri->segment(3);

//        $selectedCabType = "";
//        if( isset($this->session->login['cabang_id']) && $this->session->login['cabang_id'] == 25 ){
//            $selectedCabType = false === strpos( $this->uri->segment(3) , "Production", 1) ? "Production" : "";
//            $className = $className . $selectedCabType;
//        }
//        else{
//            $selectedCabType = false === strpos( $this->uri->segment(3) , "Sales", 1) ? "Sales" : "";
//            $className = $className . $selectedCabType;
//        }
//
//        $this->load->model("Mdls/" . $className);
//        $o = new $className;
//
//        $this->listedFieldsPending  = $o->getListedFieldsPending();
//        $this->listedFieldsActive   = $o->getListedFieldsActive();
//        $this->listedFieldsSold     = $o->getListedFieldsSold();
//        $this->listedFieldsDepre    = $o->getListedFieldsDepre();
//
//        $mb = New MobileDetect();
//        $isMob = $mb->isMobile();
//        if ($isMob) {
//            $this->listedFieldsPending = $o->getCompactListedFields();
//            $this->listedFieldsActive  = $o->getCompactListedFields();
//            $this->listedFieldsSold    = $o->getCompactListedFields();
//            $this->listedFieldsDepre   = $o->getCompactListedFields();
//        }

    }

    public function view()
    {
        //catetan
        /*
         *list daftar aset total
         *list aset tersedia di cabang
         *list aset tersetting
         * pairing aset total dengan aset tersedia di cabang
         */

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }

        $sesionReplacer = replaceSession();

        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();
        $l->addFilter("jenis=447");
//        $l->addFilter("state=active");
        $paymentValue = $l->lookupAll()->result();

        $paymentSource = array();
        if (sizeof($paymentValue) > 0) {
            $totalSisa = array();
            foreach ($paymentValue as $paymentTmp) {
                if (!isset($totalSisa[$paymentTmp->extern_id])) {
                    $totalSisa[$paymentTmp->extern_id] = 0;
                }
                $totalSisa[$paymentTmp->extern_id] += $paymentTmp->sisa;
                $paymentSource[$paymentTmp->extern_id] = array(
                    "sisa" => $totalSisa[$paymentTmp->extern_id],
                );
            }
        }

//arrPrint($paymentSource);

        $this->load->model("Mdls/MdlSetupBungaPihak3");
        $a = new MdlSetupBungaPihak3();
        $defaultData = $a->lookupAll()->result();
        $mainData = array();
        foreach ($defaultData as $data) {
            $tmp = array();
            foreach ($data as $kol => $val) {
                $tmp[$kol] = $val;
            }
            $mainData[$data->id] = $tmp;
        }


        $selectedColloumb = array(
            "extern_id" => "extern_id",
            "extern_nama" => "extern_nama",
            "kredit" => "kredit",
        );

        $selectedMerger = array(
            "repeat" => "repeat",
            "extern_value" => "extern_value",
            "extern_value_2" => "extern_value_2",
            "nilai_bunga" => "nilai_bunga",
            "last_update" => "last_update",
            "harga_sisa" => "sisa",
            "history" => "history",
        );

        $ctrlName = "Mdl" . $this->uri->segment(1);
        $className = $this->uri->segment(1);

        $this->load->model("Mdls/" . $ctrlName);

        $this->load->model("Coms/ComRekeningPembantuHutangPihakLain");
        $o = new ComRekeningPembantuHutangPihakLain();
        $o->addFilter("cabang_id='" . $_SESSION['login']['cabang_id'] . "'");
        $o->addFilter("periode='forever'");

        $tmp = $o->lookupAll()->result();


        showLast_query("lime");
        arrPrint($tmp);

        $defData = array();
        foreach ($tmp as $tmp_0) {
            foreach ($selectedColloumb as $selColl => $alias) {
                if (!isset($defData[$tmp_0->extern_id][$alias])) {
                    $defData[$tmp_0->extern_id][$alias] = array();
                }
                $defData[$tmp_0->extern_id][$alias] = isset($tmp_0->$selColl) ? $tmp_0->$selColl : (isset($mainData[$tmp_0->extern_id][$alias]) ? $mainData[$tmp_0->extern_id][$alias] : "un-defined");
            }
        }


        //==========================================================
        //===========================MULAI==========================
        //==========================================================

        $d = new $ctrlName();
        $tmpDep = $d->lookupAll()->result();

        //region arrSettingPending
        $arrHead2 = array(
            "status" => "status",
            "action" => "action ",
        );
        $headerFieldPending = $d->getListedFieldsPending() + $arrHead2;

//        cekOrange('$tmpDep');
//        arrPrint($tmpDep);

        $arrSettingPending = array();
        $existData = array();
        if (sizeof($tmpDep) > 0) {
            foreach ($tmpDep as $Data) {
                foreach ($selectedMerger as $mainIndex => $alias) {
                    if (!isset($existData[$Data->extern_id][$alias])) {
                        $existData[$Data->extern_id][$alias] = array();
                    }
                    $existData[$Data->extern_id][$alias] = isset($Data->$alias) ? $Data->$alias : "";
                }
            }
        }


//        arrPrint($defData);

        foreach ($defData as $rID => $rData) {

//            arrPrint($existData[$rID]['extern_value']);
//            arrPrint($existData[$rID]);

            if (isset($existData[$rID]['repeat']) && $existData[$rID]['repeat'] == "") {

                $defVal = array(
                    "extern_id" => $rData['extern_id'],
                    "extern_nama" => $rData['extern_nama'],
                    "extern_value" => $existData[$rID]['extern_value'],
                    "extern_value_2" => $existData[$rID]['extern_value_2'],
                    "repeat" => isset($existData[$rID]['repeat']) ? $existData[$rID]['repeat'] * 1 : $rData['repeat'] * 1,
                );


//                cekBiru('$defVal');
//                arrPrint($defVal);

                $dataDef = blobEncode($defVal);

                if ($existData[$rID]['repeat'] != "") {
                    $updateLink = base_url() . get_class($this) . "/edit/" . $rID . "?val=" . $dataDef;
                    $addLink = "#";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify setup data depresiasi',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                   }
                              );";
                    $addClick = "#";
                    $cssAdd = "disabled";
                    $cssEdit = "";
                }
                else {
                    $updateLink = "#";
                    $addLink = base_url() . get_class($this) . "/add/" . $rID . "?val=" . $dataDef;
                    $editClick = "#";
                    $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'tambah data otomatisasi bunga pinjaman',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                           }
                        );";
                    $cssAdd = "";
                    $cssEdit = "disabled";
                }

                if (isset($existData[$rID])) {
                    $temp2 = array_replace($rData, $existData[$rID]);
                }
                else {
                    $temp2 = $rData;
                }

//            arrPrint($temp2);

                $updateCommentStr = "Klik untuk mengubah entri";
                $tmpItem = array();

                foreach ($headerFieldPending as $ofName => $fields) {
                    $tmpItem['action'] = "<div style='display: flex;' class='btn-group'>";
                    $tmpItem['action'] .= "<a class='btn btn-success' $cssAdd href='javascript:void(0);' onclick=\"$addClick\"><i class='glyphicon glyphicon-plus'></i></a>";
                    $tmpItem['action'] .= "<a class='btn btn-success' $cssEdit href='javascript:void(0);' onclick=\"$editClick\"><i class='glyphicon glyphicon-edit'></i></a>";
                    $tmpItem['action'] .= "</div>";

                    $tmpItem['status'] = "<span class='btn-block text-center'>";
                    $tmpItem['status'] .= isset($existData[$rID]['repeat']) && $existData[$rID]['repeat'] != "" ? "active" : "<span class='text-red text-bold'>belum disetting</span>";
                    $tmpItem['status'] .= "</span>";

                    $tmpItem['idShow'] = $rID;
                }

//            arrPrint($tmpItem);
                $arrSettingPending[$rID] = $temp2 + $tmpItem;
            }


        }
        //endregion arrSettingPending

        //==========================================================
        //==========================================================
        //==========================================================

        //region arrSettingActive
//        $d = new $ctrlName();
//        $d->addFilter("cabang_id='" . $_SESSION['login']['cabang_id'] . "'");
//        $tmpDep = $d->lookupAll()->result();

        $arrHead2 = array(
            "status" => "status",
            "action" => "action",
        );

        //edit masih di matikan, silahkan sesuaikan config di funsi edit() pada halaman ini
//        $headerFieldActive = $d->getListedFieldsActive() + $arrHead2;
        $headerFieldActive = $d->getListedFieldsActive();

        $headerField = $d->getListedFieldsActive() + $arrHead2;
        $this->listedFieldsActive = $d->getListedFieldsActive();

        // cekOrange("date sini");
        // arrPrint( date("d", strtotime("2020/4/10")) );
        // arrPrint( $d->getListedFieldsActive());
        // arrPrint($tmpDep);

//        arrPrint($tmpDep);
        $arrSettingActive = array();
        $existData = array();
        if (sizeof($tmpDep) > 0) {
            foreach ($tmpDep as $Data) {
                foreach ($selectedMerger as $mainIndex => $alias) {
                    if (!isset($existData[$Data->extern_id][$alias])) {
                        $existData[$Data->extern_id][$alias] = array();
                    }
                    $dValue = 0;
                    if ($alias == 'nilai_bunga') {
                        $dValue = isset($paymentSource[$Data->extern_id]['sisa']) ? ($paymentSource[$Data->extern_id]['sisa'] * $Data->extern_value_2) / 100 : 0;
                    }
                    elseif ($alias == 'last_update') {
                        $dValue = sizeof($Data->last_updated) > 0 ? $Data->last_updated : "-none-";
                    }
                    elseif ($alias == 'history') {
                        $htemp = array(
                            "extern_nama" => $Data->extern_nama,
                            "extern_value" => $Data->extern_value,
                            "extern_value_2" => $Data->extern_value_2,
                        );
                        $hrgTmp = blobEncode($htemp);
                        $historyLink = base_url() . get_class($this) . "/viewHistory/" . $Data->extern_id . "/4449?val=$hrgTmp";
                        $editLink = base_url() . get_class($this) . "/edit/" . $Data->extern_id . "?val=" . $hrgTmp;
                        $historyClick = "BootstrapDialog.show(
                               {
                                    title:'" . $Data->extern_nama . "',
                                    size: BootstrapDialog.SIZE_WIDE,
                                    cssClass: 'edit-dialog',
                                    message: $('<div></div>').load('" . $historyLink . "'),
                                    draggable:true,
                                    closable:true,
                                    });";

                        $editClick = "BootstrapDialog.show(
                               {
                                    title:'" . $Data->extern_nama . "',
                                    size: BootstrapDialog.SIZE_WIDE,
                                    cssClass: 'edit-dialog',
                                    message: $('<div></div>').load('" . $editLink . "'),
                                    draggable:true,
                                    closable:true,
                                    });";

                        $dValue = "";

                        $dValue .= "<div class='btn-group btn-group-justified_'>";

                        $dValue .= "<button type='button' onclick=\"$editClick\" class='btn btn-xs btn-success'>setting</button>";


                        $dValue .= "<button type='button' onclick=\"$historyClick\" class='btn btn-xs btn-info'>history</button>";


                        $dValue .= "<script>";
                        $dValue .= "

                        function req_bunga_" . $Data->extern_id . "() {
                            Sweetalert2({
                              title: 'Yakin untuk Melakukan Request Bunga Pinjaman?',
                              html: \"Bunga pinjaman yang di request bisa <span class='text-green text-bold'>diapprove</span> atau <b><r>direject</r></b> pada menu <b>Transaksi => <a href='javascript:void(0)' onclick=window.open('" . base_url() . "Transaksi/index/4449')>Auto Loan Interest</a></b>\",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yaa!'
                            }).then((result) => {
                                if (result) {
                                    $('#result').load('" . base_url() . "AutoLoanInterest?force=1&byexternid=" . $Data->extern_id . "&exe');
                                }
                            })
                        }

                        ";

                        $dValue .= "</script>";

                        $dValue .= "<button type='button' onclick=\"req_bunga_" . $Data->extern_id . "()\" class='btn btn-xs btn-warning'>mbuh (#" . $Data->extern_id . ")</button>";
                        $dValue .= "</div>";

                    }
                    else {

                    }
                    $existData[$Data->extern_id][$alias] = isset($Data->$alias) ? $Data->$alias : (isset($paymentSource[$Data->extern_id][$alias]) ? $paymentSource[$Data->extern_id][$alias] : "$dValue");
                }
            }
        }

//        cekHitam('$existData active');
//        arrPrintWebs($existData);
//        arrPrint($tmpDep);
//        arrPrint($defData);
        foreach ($defData as $rID => $rData) {


            if (isset($existData[$rID]['repeat']) && $existData[$rID]['repeat'] > 1) {

                $defVal = array(
                    "extern_id" => $rData['extern_id'],
                    "extern_nama" => $rData['extern_nama'],
                    "repeat" => isset($existData[$rID]['repeat']) ? $existData[$rID]['repeat'] * 1 : $rData['repeat'] * 1,
                );


                $dataDef = blobEncode($defVal);

                if (isset($existData[$rID])) {
                    $updateLink = base_url() . get_class($this) . "/edit/" . $this->uri->segment(3) . "/" . $rID . "?val=" . $dataDef;
                    $addLink = "#";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify setup data depresiasi',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                   });";
                    $addClick = "#";
                    $cssAdd = "disabled";
                    $cssEdit = "";
                }
                else {
                    $updateLink = "#";
                    $addLink = base_url() . get_class($this) . "/add/" . $this->uri->segment(3) . "/" . $rID . "?val=" . $dataDef;
                    $editClick = "#";
                    $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'Setup  data depresiasi',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );";
                    $cssAdd = "";
                    $cssEdit = "disabled";
                }

//                arrPrint($existData[$rID]);

                if (isset($existData[$rID])) {
                    $temp2 = array_replace($rData, $existData[$rID]);
                }
                else {
                    $temp2 = $rData;
                }

//                arrPrint($temp2);
//                arrPrint($rData);
//                arrPrint($headerField);

                $updateCommentStr = "Klik untuk mengubah entri";
                $tmpItem = array();

                //untuk tombol action
                foreach ($headerField as $ofName => $fields) {
                    $tmpItem['action'] = "<div style='display: flex;' class='btn-group'>";
                    $tmpItem['action'] .= "<a class='btn btn-success' $cssAdd href='javascript:void(0);' onclick=\"$addClick\"><i class='glyphicon glyphicon-plus'></i></a>";
                    $tmpItem['action'] .= "<a class='btn btn-success' $cssEdit href='javascript:void(0);' onclick=\"$editClick\"><i class='glyphicon glyphicon-edit'></i></a>";
                    $tmpItem['action'] .= "</div>";
                    $tmpItem['status'] = "<span class='btn-block text-center'>";
                    $tmpItem['status'] .= isset($existData[$rID]) ? "<span class='text-bold text-green'>active</span>" : "<span class='text-bold text-red'>un-define</span>";
                    $tmpItem['status'] .= "</span>";
                }

//                cekHere($rID);
                $arrSettingActive[$rID] = $temp2 + $tmpItem;
            }

//            arrPrint($rData);
        }

//        arrPrint($arrSettingActive);

        //endregion arrSettingActive

        //==========================================================
        //=========================SELESAI==========================
        //==========================================================

        //region data propose
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$ctrlName'");
        $tmpTmp = $tData->lookupAll()->result();

        $dataProposals = array();

        if (sizeof($tmpTmp) > 0) {
            $allowedCreate = array(
                "MdlSetupBungaPihak3" => array(
                    "label" => "setup bunga pihak ke-3",
                    "viewers" => array("o_finance", "o_finance_spv"),
                    "creators" => array("o_finance", "o_finance_spv", "c_holding"),
                    "creatorAdmins" => array("o_data", "c_holding", "o_finance"),
                    "updaters" => array("c_holding", "o_finance"),
                    "updaterAdmins" => array("o_finance"),
                    "deleters" => array(),
                    "deleterAdmins" => array("o_data"),
                    "historyViewers" => array("o_data", "c_holding"),
                ),
            );
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($allowedCreate[$mdlName]) ? $allowedCreate[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
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
                else {
                    cekHere("no access");
                }
            }
        }

        $t = new Table();
        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $headerField;
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {
            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
                $listedFields = $this->listedFieldsActive;
                foreach ($pSpec as $dSpec) {
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";
                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }
                    $approvalClick = "  BootstrapDialog.closeAll();
                                        BootstrapDialog.show(
                                            {
                                                title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ', message: $('<div></div>').load('" . base_url() . "SetupLoanInterest/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                                size: BootstrapDialog.SIZE_WIDE,
                                                draggable:false,
                                                closable:true,
                                            }
                                        );";
                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }
            }
        }
        //endregion

        $objState = 0;
        $folders = array();

        $data = array(

            "errMsg" => $this->session->errMsg,
            "title" => "Bunga Hutang Pihak ke-3 Setup",
            "mode" => $this->uri->segment(2),

            "title_pending_assets" => "pending assets",
            "title_active_assets" => "active assets",

            "title_depresiasi" => "<div style='font-size: 20px;' class='box-header text-bold text-green'>Setup Otomatisasi Bunga Pinjaman</div>",
            "subTitle" => "",
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of ",

            "linkStrPending" => isset($params['links']) ? $params['links'] : "",
            "linkStrActive" => isset($params['links']) ? $params['links'] : "",

            "arrayHistoryLabelsPending" => $headerFieldPending,
            "arrayHistoryLabelsActive" => $headerFieldActive,

            "arrayHistoryPending" => $arrSettingPending,
            "arrayHistoryActive" => $arrSettingActive,

            "badge_pending" => isset($arrSettingPending) ? sizeof($arrSettingPending) : "",
            "badge_active" => isset($arrSettingActive) ? sizeof($arrSettingActive) : "",

            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            "strDataHistTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels" => array(),
            "arrayRecap" => array(),

            "strEditLinkPending" => "",
            "strEditLinkActive" => "",

            "strAddLinkPending" => "",
            "strAddLinkActive" => "",

            "alternateLinkPending" => "",
            "alternateLinkActive" => "",

            "thisPagePending" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?trashed=$objState",
            "thisPageActive" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?trashed=$objState",

            "foldersPending" => $folders,
            "foldersActive" => $folders,

            "fmdlName" => isset($fmdlName) ? $fmdlName : "",
            "faddLink" => isset($faddLink) ? $faddLink : "",
            "feditLink" => isset($fupdateLink) ? $fupdateLink : "",
            "fmdlTarget" => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "fdeleteLink" => isset($fdeleteLink) ? $fdeleteLink : "",

        );
        $this->load->view('loan_interest', $data);
        $this->session->errMsg = "";
    }

    public function edit()
    {

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $jsBottomSrc = "";
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(3);
        $tmpDat = $o->lookupByCondition(array(
            "extern_id" => $selectedID,
//            "cabang_id" => $_SESSION['login']['cabang_id']
        ))->result();

        if (sizeof($tmpDat) > 0) {
            $tmp = $tmpDat;
        }
        else {
            $tmp = (object)blobDecode($_GET['val']);
        }

        $f = new MyForm($o, "edit", array(
            "id" => "f1ed_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target" => "result",
            "class" => "form-horizontal",
        ));

        $f->openForm(base_url() . get_class($this) . "/editProcess/" . $selectedID);
        $f->fillForm($className, $tmp);
        $f->closeForm();

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();
        //arrPrint($dataExtRel);
        //cekHitam($className);

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= ($f->getContent());
        $content .= "</div>";
        $content .= "</div>";

        // $content .= "<div class='row'>";
        // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";

        if (sizeof($dataRel) > 0) {
            $content .= "<div class='panel panel-info'>";

            // $content .= "<div class='row panel panel-default' style='background:#f0f0f0;'>";
            // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            foreach ($dataRel as $mdlName => $mSpec) {
                $tmpDataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $tmpDataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $tmpDataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $tmpDataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $tmpDataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }


                $relations = array();
                $relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$mdlName.php")) {
                    $this->load->model("Mdls/" . $mdlName);
                    $o = new $mdlName();
                    $fields = $o->getFields();
                    foreach ($fields as $f2Spec) {
                        if (isset($f2Spec['reference'])) {
                            if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                                $relations[$f2Spec['kolom']] = $f2Spec['reference'];
                                $this->load->model("Mdls/" . $f2Spec['reference']);
                                $o3 = new $f2Spec['reference']();
                                $tmp3 = $o3->lookupAll()->result();

                                if (sizeof($tmp3) > 0) {
                                    $mdlName2 = $f2Spec['kolom'];
                                    $relationPairs[$mdlName2] = array();
                                    foreach ($tmp3 as $row3) {
                                        $id = isset($row3->id) ? $row3->id : 0;
                                        $name = isset($row3->nama) ? $row3->nama : "";
                                        $relationPairs[$mdlName2][$id] = $name;
                                    }
                                }
                            }
                        }
                    }
                }


                $mdlLink = base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $content .= "<div class='panel-heading'>";
                $content .= "<span class='text-blue text-uppercase'>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'];
                $content .= "</a>";

                if ($allowCreate) {
                    $addLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;

                    $addClick = "
                                BootstrapDialog.show(
                                    {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    }
                                );";
                    $content .= "<span class='pull-right'>";
                    $content .= "<a class=\" btn btn-default btn-xs\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></a>";
                    $content .= "</span>";
                }
                $content .= "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();
                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";
                    }
                    $content .= "</tr>";
                    foreach ($tmpo2 as $row) {
                        $content .= "<tr>";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $content .= "<td>";
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$row->$fName]) ? $relationPairs[$fName][$row->$fName] : "unknown rel";
                            }
                            else {
                                $fieldLabel = $row->$fName;
                            }
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
                        $content .= "</tr>";
                    }
                }
                $content .= "</table class='table table-condensed'>";
            }

            // $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
        }
        // $content .= "</div>";

        /*-------------------------------------
         * editor dalam iframe
         * -----------------------------------*/
        if (sizeof($dataExtRel) > 0) {
            $num = 0;
            foreach ($dataExtRel as $mSpec) {
                $num++;
                $content .= "<div class='panel panel-default' style='background:#f0f0f0;'>";
                // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
                $content .= "<div class='panel-heading'>";
                // $content .= "<h5 class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h5>";
                $content .= "<span class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $mSpec['target'];
                $backLink = blobEncode(current_url());
                $iframeLink = base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink";
                //                $content .= "<div id='$selectedID$num' frameborder='0'  style='width:100%;height:350px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:scroll;'>";
                //                $content .= "</div>";
                //                $content .= "<script> $('#$selectedID$num').load('" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink'); </script>";

                $content .= "<iframe id='result2' frameborder='0' width=100% height=100% style='width:100%;height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink\'>";
                $content .= "</iframe>";
                if (show_debuger() == 1) {
                    $content .= "<a href='javaScript:void(0);' onclick=\"window.open('$iframeLink&dock=1','mywin','width=1000,height=600');\">open New Window</a>";
                }

                $content .= "</div>"; // body
                $content .= "</div>"; // panel
            }
        }

        $content .= $jsBottomSrc;
        // $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        // $content .= "</div class='row'>";

        echo $content;
        die();

//        $data = array(
//            "mode" => "edit",
//            "title" => "Data $ctrlName",
//            "subTitle" => "Create new $ctrlName",
//            "content" => $content,
//        );
//
//        $this->load->view('data', $data);
    }

    public function add()
    {
        $content = "";

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        //==menampilkan form penambahan data berdasarkan datamodel (kelas data) yang bersesuaian
        $className = "Mdl" . $this->uri->segment(1);

        $selectedID = $this->uri->segment(3);
        $ctrlName = $this->uri->segment(1);

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $jsBottomSrc = "";
        $selectedCabType = "";

        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $tmpDat = $o->lookupByCondition(array(
            "extern_id" => $selectedID,
            "cabang_id" => $_SESSION['login']['cabang_id']
        ))->result();

        if (sizeof($tmpDat) > 0) {
            $tmp = $tmpDat;
        }
        else {
            $tmp[0] = (object)blobDecode($_GET['val']);
        }

        $f = new MyForm($o, "add", array(
            "id" => "f1add_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/addProcess/$ctrlName",
            "target" => "result",
            "class" => "form-inline",
        ));

        $f->openForm(base_url() . get_class($this) . "/addProcess/" . $this->uri->segment(3));
        $f->fillForm($className, $tmp);
        $f->closeForm();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);

        $content .= "<div class='panel panel-success'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";
        $content .= "<div class='panel-body'>";
        $content .= ($f->getContent());
        $content .= "</div>";

        $content .= "<div id='errAlert' style='margin: 0 10px 10px 10px;' class='alert alert-info hidden'>";
        $content .= "<strong>Info!</strong> Indicates a neutral informative change or action.";
        $content .= "</div>";

        $content .= "</div>";

        echo $content;
        die();

    }

    public function addProcess()
    {

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $className = "Mdl" . $this->uri->segment(1);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(1);

        $selectedCabType = "";

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $f = new MyForm($o, "addProcess");
        $inserted = array();
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    die(lgShowAlert($errMsg));
                }
            }
            $this->db->trans_start();
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    cekMerah($spec['inputType'] . " | " . $fName);
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }

                if (isset($spec['strField'])) {
                    $this->load->model("Mdls/" . $spec["reference"]);
                    $idnya = $this->input->post($spec["kolom"]);
                    $tmpRe = new $spec["reference"]();
                    $tmpFields = $tmpRe->lookupByID($idnya)->result();
                    $strField = $tmpFields[0]->$spec["strField"];
//                    arrPrint($tmpFields);
//                    arrPrint($spec['strField']);
                    $data[$spec["kolom_nama"]] = $strField;
                }
            }

//            arrPrint($this->input->post());
//            cekHere(__LINE__);

            $data = array_filter($data);

//            arrPrintWebs($data);

            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }

            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {
                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }

            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
//arrprint($data);
            $tmpData = array(
                "orig_id" => isset($this->input->post()['extern_id']) ? $this->input->post()['extern_id'] : 0,
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => dtimeNow(),
                "content" => blobEncode($data),
            );

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                showLast_query("lime");
//                arrPrint($tmpOrig);
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }

            if (sizeof($tmpOrig) > 0) {
                $where2 = array("produk_id" => $bProdukId);
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                showLast_query("biru");
//                arrPrint($tmpOrig2);
                $hasil = "";
                $hasil .= "$bNama  already set up<br>";
                foreach ($tmpOrig2 as $itemOrigs) {
                    $bNama2 = $itemOrigs->biaya_nama;
                    $bNilai2 = formatField("harga", $itemOrigs->nilai);
                    $var = "$bNama2 <span>$bNilai2</span>";
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil<br>$var";
                    }
                }

                $bJudul = "$bProduk";
                $alerts = array(
                    "type" => "warning",
                    "title" => $bJudul,
                    "html" => $hasil,
                );
                echo swalAlert($alerts);
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die();
                matiHere("data $bNama  already exist on $bProduk, no data change<hr>");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }

//            cekHere('$tmpData');
//            arrPrint($tmpData);
//            cekHere("approval");
            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            cekHitam($this->db->last_query());

            $this->session->errMsg = "Data proposal has been saved and pending approval";
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => 0,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label" => "proposed",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
//            cekHitam($this->db->last_query());
//            arrPrint($insertID);

//            matiHere("hoop ----DONE---- belom commit");
            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";

        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function editFrom()
    {

        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);

        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        $f = new MyForm($o, "edit", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

        $content .= ("<table class='table table-condensed'>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>data yang diajukan</h4></td></tr>");
        $ii = 0;

        foreach ($o->getFields() as $fName => $fSpec) {
            $fType = $fSpec['type'];
            $fInputType = $fSpec['inputType'];
            $fDataSource = isset($fSpec['dataSource']) ? $fSpec['dataSource'] : "";
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fieldLabel = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            //region terjemahan isi berdasat type data
            switch ($fType) {
                case "image":
                    $hasil = "<div class='thumbnail'>";
                    $styleImage = $fieldLabel !== '' ? "style='width: 35em'" : "style='width: 10em'";
                    $fieldLabel = $fieldLabel !== '' ? $fieldLabel : base_url() . "assets/images/img_blank.gif";
                    $hasil .= "<img src='$fieldLabel' class='img-responsive ($fieldLabel)' $styleImage >";
                    $hasil .= "<div class='caption'>";
                    $hasil .= "</div>";
                    $hasil .= "</div>";
                    $fieldLabel = $hasil;
                    $conten_f = "$fieldLabel";
                    break;
                case "blob":
                case "longbloob":
                case "mediumblob":
                    $isiBlop = $fieldLabel != null ? blobEncode($fieldLabel) : "";
                    if (is_array($isiBlop)) {
                        $hasil = "";
                        if (array_key_exists("image", $isiBlop)) {
                            $images = base64_encode($isiBlop["image"]);
                            $hasil = "<div class='thumbnail'>";
                            $hasil .= "<img src='$images' class='img-responsive' width='150px'>";
                            $hasil .= "<div class='caption'>";
                            $hasil .= "</div>";
                            $hasil .= "</div>";
                        }
                        else {
                            foreach ($isiBlop as $kBlop) {
                                $var = $fDataSource[$kBlop];
                                if ($hasil == "") {
                                    $hasil .= "$var";
                                }
                                else {
                                    $hasil = "$hasil, " . "$var";
                                }
                            }
                        }
                        $fieldLabel = $hasil;
                    }
                    $conten_f = "$fieldLabel";
                    break;
                case "password":
                    $fieldLabel = "*********";
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
                default:
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
            }
            //endregion
            //===if related
            if (array_key_exists($fColName, $this->relations)) {
                $fieldLabel = isset($this->relationPairs[$fColName][$fieldLabel]) ? "<span class='fa fa-folder-o' style='color:#ff7700;'></span> " . $this->relationPairs[$fColName][$fieldLabel] : "unknown rel";
            }
            $fContent = $fieldLabel;
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("$conten_f");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $addRows = array(
            "proposal type" => $tmp[0]->propose_type,
            "tgl. diajukan" => formatTanggal($tmp[0]->proposed_date),
            "oleh" => $tmp[0]->proposed_by_name,
            "ID data asli" => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>informasi pengajuan</h4></td></tr>");
        foreach ($addRows as $key => $val) {
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$key");
            $content .= ("</td>");
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$val'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $content .= ("</table width=100%>");

        $viewButton = false;
        switch ($tmp[0]->propose_type) {
            case "add":
            case "edit":
                $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID');";
                $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID');";
                if ($origID > 0) {
                    $rejectAlertMsg = "rejecting this proposal will activate the previous data entry";
                    $approveAlertMsg = "approving this proposal will turn the contents in this proposal into current active data";
                    $yesLabel = "proceed to modify this entry";
                    $noLabel = "reject data modification";
                }
                else {
                    $rejectAlertMsg = "this proposal will be deleted (instead of the original data)";
                    $approveAlertMsg = "contents within this proposal will be set as new active data";
                    $yesLabel = "proceed to add this entry";
                    $noLabel = "reject data addition";
                }
                $viewButton = true;
                break;
            case "delete":
                $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID');";
                $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID');";
                $rejectAlertMsg = "this deletion proposal will be ignored and related data will be set as active";
                $approveAlertMsg = "data entry related to this proposal will be DELETED";
                $yesLabel = "delete anyway";
                $noLabel = "dont delete";
                $viewButton = $this->allowDeleteApproval == true ? true : false;
                break;
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='javascript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">$noLabel</a>");
        $content .= ("</div class='col-sm-6'>");
        if ($viewButton == true) {
            $content .= ("<div class='col-sm-6'>");
            $content .= ("<a class='btn btn-success btn-block' href='javascript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">$yesLabel</a>");
            $content .= ("</div class='col-sm-6'>");
            $content .= ("</div class='row'>");
        }

        $f->closeForm();
        $content .= ($f->getContent());
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
        );

        echo $content;
        die();

    }

    public function editProcess()
    {
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );
        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->db->trans_start();
        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                    //lolos
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                if ($this->input->post($fName)) {
                                    $newFile = $this->input->post($fName);
                                }
                                else {
                                    $newFile = "";
                                }
                                $data[$fName] = $newFile;
                            }
                            break;
                        case "hidden":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
            }
            $where = array(
                "id" => $data['id'],
            );
            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            if ($this->updaterUsingApproval) {
                $data['trash'] = 0;
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }
            $tmpData = array(
                "orig_id" => $data['id'],
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => date("Y-m-d H:i:s"),
                "content" => blobEncode($data),
            );
            if ($this->updaterUsingApproval) {
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                $this->load->model("Mdls/" . "MdlDataHistory");

                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => $data['id'],
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "proposed",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
            else {
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, $data, $o->getTableName());
                $this->session->errMsg = "Data has been updated";

                if (isset($this->config->item("dataExtended")[$className])) {
                    createAccessData($this->input->post('membership'), $data['id'], "false");
                }

                arrPrint($postProcs);
                if (sizeof($postProcs) > 0) {
                    cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                    foreach ($postProcs as $pp) {
                        $comName = "DCom" . $pp;
                        cekmerah("post-proc name: $pp / $comName");
                        $this->load->model("DComs/" . $comName);
                        $o2 = new $comName();
                        $o2->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
                        $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
                    }
                }
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => $data['id'],
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "applied",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function doApproveFrom()
    {
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );
        echo swalAlert($arrAlert);
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(1);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");
        $tmp = $oTmp->lookupAll()->result();

        $tmpContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));

        if ($origID != 0) {//===edit
            $where = array(
                "extern_id" => $origID,
            );
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            cekMerah($this->db->last_query());
            if (sizeof($dcomConf) > 0) {
                cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                $comName = "DCom" . $dcomConf;
                cekmerah("post-proc name:  $comName");
                $this->load->model("DComs/" . $comName);
                $o2 = new $comName();
                $o2->pair($tmpContent) or die(lgShowError($comName, "failed to pair the params of DCom"));
                $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
            }

            $this->session->errMsg = "Data has been updated";

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            cekLime($this->db->last_query());
            //</editor-fold>
        }
        else {//===new data
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            unset($tmpContent["id"]);
            $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            cekMerah($this->db->last_query());
            if (sizeof($dcomConf) > 0) {
                $inParam = array_merge(array("id" => "$insertID"), $tmpContent);
                $className = "DCom" . $dcomConf;
                $this->load->Model("DComs/" . $className);
                $d = new $className();
                $d->setWriteMode("insert");
                $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                $gotParams = $d->exec();
            }
            $this->session->errMsg = "Data has been saved";

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

        }
        arrPrint($insertID);
//matiHere('mati dulu sana');
        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doRejectFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();

        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));

        if ($origID > 0) {//===edit


            //===ambil data sebelumnya
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();

            $where = array(
                "id" => $origID,
            );
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data proposal has been rejected dan being reverted back";

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));


        }
        else {//===new data
            $this->session->errMsg = "Data proposal has been rejected";
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function getThisMdl()
    {
        $ctrlName = $this->uri->segment(3);
        $toSelect = $this->uri->segment(4);

        $this->load->model("Mdls/" . $ctrlName);
        $d = new $ctrlName();
        $tmpDep = $d->lookupAll()->result();
        $result = "";
        if (sizeof($tmpDep) > 0) {
            $selected = "";
            foreach ($tmpDep as $nID => $details) {
                $id = $details->id;
                $nama = $details->nama;
                $mdl_name = isset($details->mdl_name) ? $details->mdl_name : "";
                $mdl_name2 = isset($details->mdl_name2) ? $details->mdl_name2 : "";
                $selected = $id == $toSelect ? "selected" : "";
                $result .= "<option mdl_name='$mdl_name' mdl_name2='$mdl_name2' name='$nama' value='$id' $selected >$nama</option>\n";
            }
            echo $result;
        }
        else {
            echo $result;
        }
    }

    public function viewHistory()
    {

        $sesionReplacer = replaceSession();
        $id = $this->uri->segment(3);
        $jnSlctd = $this->uri->segment(4);

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $tr->addFilter("transaksi.jenis=$jnSlctd");
        $tr->addFilter("transaksi_data.produk_id='" . $id . "'");

        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();

//arrPrint($tmpHist);

        $arrListDepre = array();
        $arrTrID = array();
        if (sizeof($tmpHist) > 0) {
            asort($tmpHist);
            foreach ($tmpHist as $k => $datas) {
                $arrListDepre[] = $datas;
                $arrTrID[] = $datas->id_master;
            }
        }


        $tmpReg_result = array();
        $trReg = new MdlTransaksi();
        $trReg->setFilters(array());
        $trReg->addFilter("param='main'");
        $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTrID) . "')");
        $tmpReg = $trReg->lookupRegistries()->result();

        if (sizeof($tmpReg) > 0) {
            foreach ($tmpReg as $regRow) {
                $param = $regRow->param;
                $tmpReg_result[$regRow->transaksi_id][$param] = blobDecode($regRow->values);
            }
        }

//        arrPrint($tmpReg_result);


        $arrHeader = array(
            "dtime" => "Tgl Depresiasi",
            "nomer" => "Nomer",
            "nilai_sisa" => "Nilai Pinjaman",
            "persen_bunga" => "Persentase Bunga (bulanan)",
            "transaksi_nilai" => "nilai bunga",
            "pph_nilai" => "persentase PPH",
            "nilai_pph23" => "nilai pph",
            "grand_total" => "total nilai bunga",
            "oleh_nama" => "approve by",
        );

        $arrHeader2 = array(
            "extern_nama" => "Nama Pihak ke 3",
            "extern_value" => "Nilai Pinjaman",
            "extern_value_2" => "Persentase Bunga Bulanan",
        );

        $countDepre = count($arrListDepre);
        $optData = array();
        $table = "";
        if (isset($_GET['val'])) {
//            $table .= "<div class='row'>";
            $table .= "<ul class='list-group'>";
            $optData = $arrVal = blobDecode($_GET['val']);
            foreach ($arrVal as $k => $value) {
                $table .= "<li class='list-group-item'>";
                $table .= "<div class='row'>";
                $table .= "<div class='col-md-2 text-muted'>" . $arrHeader2[$k] . "</div>";
                $table .= "<div class='col-md-4'>" . formatField($k, $value) . "</div>";
                $table .= "</div>";
                $table .= "</li>";
            }
            $table .= "</ul>";
        }

        $table .= "<div class='row'>";
        $table .= "<div class='col-sm-12'>";
        $table .= "<table id='table' class='table viewHistory'>";
        $table .= "<thead>";
        $table .= "<tr>";

        foreach ($arrHeader as $ky => $title) {
            $table .= "<th>";
            $table .= $title;
            $table .= "</th>";
        }

        $table .= "</tr>";
        $table .= "</thead>";
        $table .= "<tbody>";

        $total = 0;
        $mutasi = 0;
        $saldo = 0;
        if (sizeof($arrListDepre) > 0) {


//            arrPrint($optData);
//            if(sizeof($tmpReg_result)>0){
//                $arrListDepre = array_merge($tmpReg_result,$arrListDepre);
//            }
//            arrPrint($arrListDepre);
            foreach ($arrListDepre as $i => $val) {

                if ($tmpReg_result[$val->id_master]['main']) {
                    $val = (object)array_merge((array)$val, (array)$tmpReg_result[$val->id_master]['main']);
                }

//                arrPrint($val);
//                if( !isset($val->sisa_depre) ){
//                    $mutasi = $mutasi>0 ? $saldo : ($optData['harga_ori']*1) ;
//                    $val->sisa_depre = $mutasi;
//                }
//
//                if( !isset($val->saldo_sisa) ){
//                    $saldo = $saldo>0 ? ($saldo-$val->transaksi_nilai) : (($optData['harga_ori']*1)-$val->transaksi_nilai) ;
//                    $val->saldo_sisa = $saldo;
//                }

                $total += $val->grand_total;
                $table .= "<tr>";
                foreach ($arrHeader as $ky => $title) {
                    $table .= "<td>";
                    if (isset($val->$ky)) {
                        $table .= formatField($ky, $val->$ky);
                    }
                    else {
                        $table .= "-";
                    }

                    $table .= "</td>";
                }
                $table .= "</tr>";
            }

        }
        $table .= "</tbody>";

//        $table .= "<tfoot>";
//        $table .= "</tfoot>";

        $table .= "</table>";
        $table .= "</div>";
        $table .= "</div>";
        $table .= "\n<script>

//        $(document).ready( function () {

            top.$('.viewHistory').append(
                $('<tfoot/>').append( $('.viewHistory thead tr').clone() )
            );

            top.$('.viewHistory').DataTable({
                dom: 'lBfrtip',
                lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                pageLength: -1,
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
//                    total4 = api
//                        .column(4)
//                        .data()
//                        .reduce(function (a, b) {
//                            b = intVal( $(b, 'span').html() );
//                            return intVal(a) + intVal(b);
//                        }, 0);

                    // Total over this page
                    pageTotal4 = api
                        .column(4, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            b = intVal( $(b, 'span').html() );
                            return intVal(a) + intVal(b);
                        }, 0);
                    // Update footer
                    $(api.column(4).footer()).html(
                        addCommas(pageTotal4)
                    );

                    // Total over all pages
//                    total6 = api
//                        .column(6)
//                        .data()
//                        .reduce(function (a, b) {
//                            b = intVal( $(b, 'span').html() );
//                            return intVal(a) + intVal(b);
//                        }, 0);

                    // Total over this page
                    pageTotal6 = api
                        .column(6, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            d = intVal( $(b, 'span').html() );
                            d = d>0 ? d : intVal( $(b).html() );
console.log(d);
                            return intVal(a) + intVal(b);
                        }, 0);
                    // Update footer
                    $(api.column(6).footer()).html(
                        addCommas(pageTotal6)
                    );

                                        // Total over all pages
//                    total7 = api
//                        .column(7)
//                        .data()
//                        .reduce(function (a, b) {
//                            b = intVal( $(b, 'span').html() );
//                            return intVal(a) + intVal(b);
//                        }, 0);

                    // Total over this page
                    pageTotal7 = api
                        .column(7, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            b = intVal( $(b, 'span').html() );
                            return intVal(a) + intVal(b);
                        }, 0);
                    // Update footer
                    $(api.column(7).footer()).html(
                        addCommas(pageTotal7)
                    );

                    $(api.column(0).footer()).html('');
                    $(api.column(1).footer()).html('');
                    $(api.column(2).footer()).html('');
                    $(api.column(3).footer()).html('');
                    $(api.column(5).footer()).html('');
                    $(api.column(8).footer()).html('');

//                    $(api.column(6).footer()).html('');

                }
            });
//        } );

        </script>";

        echo $table;

    }
}
