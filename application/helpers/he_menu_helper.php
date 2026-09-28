<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 14/11/18
 * Time: 19:19
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function placeCanMakeTrans($memberships, $cabID, $whID, $trID)
{
    //    cekHitam("$cabID, $whID, $trID");
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->helpers("he_access_right");
    $eID = $ci->session->login['id'];
    //    cekHitam($whID);
    $requiredPlace = $ci->config->item('heTransaksi_ui')[$trID]['place'];
    $requiredGroupID = $ci->config->item('heTransaksi_ui')[$trID]['steps'][1]['userGroup'];
    $customAccess = alowedAccess($eID);
    //    arrPrint($customAccess);

    $result = "none";
    if (sizeof($customAccess) > 0) {
        $targetTrans = $ci->config->item('heTransaksi_ui')[$trID]['steps'][1]['target'];
        if (isset($customAccess[$trID][1][$targetTrans])) {
            $result = $customAccess[$trID][1][$targetTrans]['allowCreate'];
        }
        else {
            $result = false;
        }
    }
    else {
        if (is_array($memberships) && sizeof($memberships) > 0) {
            if (in_array($requiredGroupID, $memberships)) {
                switch ($requiredPlace) {
                    case "center":
                        //                    cekmerah("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID == "-1") {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    case "branch":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekbiru("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got, ".$gudSpec['gudang_id']);
                        //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                        cekhijau("allowed");
                            $result = true;
                        }
                        else {
                            //                        cekmerah("disallowed");
                            $result = false;
                        }
                        break;
                    case "warehouse":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                        cekungu("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                        if ($cabID > 0 && (int)$whID < 0) {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    default:
                        //                    cekhitam("none required, none got");
                        $result = false;
                        break;
                }
            }
            else {
                $result = false;
            }

        }
        else {
            $result = false;
        }
    }


    return $result;
}

function placeCanMakeTrans_he_menu($memberships, $cabID, $whID, $trID, $configUiJenis)
{
    //    cekHitam("$cabID, $whID, $trID");
    $ci =& get_instance();
    //    $ci->load->config("heTransaksi_ui");
    $ci->load->helpers("he_access_right");
    $eID = $ci->session->login['id'];
    //    cekHitam($whID);
    $requiredPlace = $configUiJenis['place'];
    $requiredGroupID = $configUiJenis['steps'][1]['userGroup'];
    $customAccess = alowedAccess($eID);
    //arrPrint($customAccess);
    //arrPrint($configUiJenis['steps'][1]);

    $result = "none";
    if (sizeof($customAccess) > 0) {
        //arrPrintKuning($customAccess[$trID]);
        $targetTrans = $configUiJenis['steps'][1]['target'];
        if (isset($customAccess[$trID][1][$targetTrans])) {
            $result = $customAccess[$trID][1][$targetTrans]['allowCreate'];
        }
        else {
            //            cekKuning(__LINE__ . " :: $targetTrans");
            $result = false;
        }
    }
    else {
        if (is_array($memberships) && sizeof($memberships) > 0) {
            if (in_array($requiredGroupID, $memberships)) {
                switch ($requiredPlace) {
                    case "center":
                        //                    cekmerah("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID == "-1") {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    case "branch":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekbiru("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got, ".$gudSpec['gudang_id']);
                        //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                        cekhijau("allowed");
                            $result = true;
                        }
                        else {
                            //                        cekmerah("disallowed");
                            $result = false;
                        }
                        break;
                    case "warehouse":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                        cekungu("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                        if ($cabID > 0 && (int)$whID < 0) {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    default:
                        //                    cekhitam("none required, none got");
                        $result = false;
                        break;
                }
            }
            else {
                $result = false;
            }

        }
        else {
            $result = false;
        }
    }

    //cekHitam(" ** $result");
    return $result;
}

function placeCanFollowupTrans($memberships, $cabID, $whID, $trID, $step, $mode = "")
{
    //    arrPrintWebs($memberships);
    //    cekHere("$cabID - $whID - $trID - $step - [$mode]");


    $ci =& get_instance();
    $eID = $ci->session->login['id'];
    $ci->load->config("heTransaksi_ui");
    $ci->load->helpers("he_access_right");

    $requiredPlace = $ci->config->item('heTransaksi_ui')[$trID]['place'];
    $requiredGroupID = isset($ci->config->item('heTransaksi_ui')[$trID]['steps'][$step]['userGroup']) ? $ci->config->item('heTransaksi_ui')[$trID]['steps'][$step]['userGroup'] : "";
    $customAccess = alowedAccess($eID);
    // cekKuning("group $requiredGroupID, place $requiredPlace");
    //    arrPrint($customAccess);

    $result = "none";
    if (sizeof($customAccess) > 0) {
        //        $newStep = $step > 1 ? $step - 1 : $step;
        $newStep = $step;
        $targetTrans = $ci->config->item('heTransaksi_ui')[$trID]['steps'][$step]['source'];
        $targetTrans_config = $ci->config->item('heTransaksi_ui')[$trID]['steps'][$step]['target'];

        //        cekPink($targetTrans . " --- " . $targetTrans_config . " --- " . $newStep . " --- " . $trID);
        //arrPrint($customAccess[$trID]);
        //        if (isset($customAccess[$trID][$newStep][$targetTrans])) {
        //            $result = $customAccess[$trID][$newStep][$targetTrans]['allowFollowUp'];
        if (isset($customAccess[$trID][$newStep][$targetTrans_config])) {
            $result = $customAccess[$trID][$newStep][$targetTrans_config]['allowFollowUp'];
            //            cekHijau("HAHA");
        }
        else {
            $result = false;
            //            cekHijau("HEHEHE");
        }
        // replace result...
        switch ($mode) {
            case "delete":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowDelete']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowDelete'] : false;
                //                cekKuning("resultnya dapat delete $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "reject":

                $result = isset($customAccess[$trID][$step][$targetTrans_config]['allowReject']) ? $customAccess[$trID][$step][$targetTrans_config]['allowReject'] : false;
                //                cekKuning("resultnya dapat reject $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "edit":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowEdit']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowEdit'] : false;
                //                cekKuning("resultnya dapat edit $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "undo":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowUndo']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowUndo'] : false;
                //                cekKuning("resultnya dapat undo $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            default:
                break;
        }
    }
    else {

        if (is_array($memberships) && sizeof($memberships) > 0) {
            if (in_array($requiredGroupID, $memberships)) {
                switch ($requiredPlace) {
                    case "center":
                        //                    cekmerah("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID == "-1") {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    case "branch":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekbiru("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got, ".$gudSpec['gudang_id']);
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                            //                        cekhijau("allowed");
                            $result = true;
                        }
                        else {
                            // cekmerah("disallowed");
                            $result = false;
                        }
                        break;
                    case "warehouse":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekungu("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    default:
                        //                    cekhitam("none required, none got");
                        $result = false;
                        break;
                }
            }
            else {

                $result = false;
            }

        }
        else {
            $result = false;
        }
    }


    return $result;
}

function placeCanFollowupTrans_he_menu($memberships, $cabID, $whID, $trID, $step, $mode = "", $configUiJenis)
{

    $ci =& get_instance();
    $eID = $ci->session->login['id'];
    //    $ci->load->config("heTransaksi_ui");
    $ci->load->helpers("he_access_right");

    $requiredPlace = $configUiJenis['place'];
    $requiredGroupID = isset($configUiJenis['steps'][$step]['userGroup']) ? $configUiJenis['steps'][$step]['userGroup'] : "";
    $customAccess = alowedAccess($eID);
    // cekKuning("group $requiredGroupID, place $requiredPlace");
    //    arrPrint($customAccess);

    $result = "none";
    if (sizeof($customAccess) > 0) {
        //        $newStep = $step > 1 ? $step - 1 : $step;
        $newStep = $step;
        $targetTrans = $configUiJenis['steps'][$step]['source'];
        $targetTrans_config = $configUiJenis['steps'][$step]['target'];


        if (isset($customAccess[$trID][$newStep][$targetTrans_config])) {
            $result = $customAccess[$trID][$newStep][$targetTrans_config]['allowFollowUp'];
            //            cekHijau("HAHA");
        }
        else {
            $result = false;
            //            cekHijau("HEHEHE");
        }
        // replace result...
        switch ($mode) {
            case "delete":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowDelete']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowDelete'] : false;
                //                cekKuning("resultnya dapat delete $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "reject":

                $result = isset($customAccess[$trID][$step][$targetTrans_config]['allowReject']) ? $customAccess[$trID][$step][$targetTrans_config]['allowReject'] : false;
                //                cekKuning("resultnya dapat reject $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "edit":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowEdit']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowEdit'] : false;
                //                cekKuning("resultnya dapat edit $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            case "undo":

                $result = isset($customAccess[$trID][$newStep][$targetTrans_config]['allowUndo']) ? $customAccess[$trID][$newStep][$targetTrans_config]['allowUndo'] : false;
                //                cekKuning("resultnya dapat undo $result :: [$trID][$newStep][$targetTrans_config]");
                break;
            default:
                break;
        }
    }
    else {

        if (is_array($memberships) && sizeof($memberships) > 0) {
            if (in_array($requiredGroupID, $memberships)) {
                switch ($requiredPlace) {
                    case "center":
                        //                    cekmerah("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID == "-1") {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    case "branch":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekbiru("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got, ".$gudSpec['gudang_id']);
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                            //                        cekhijau("allowed");
                            $result = true;
                        }
                        else {
                            // cekmerah("disallowed");
                            $result = false;
                        }
                        break;
                    case "warehouse":
                        $gudSpec = getDefaultWarehouseID($cabID);
                        //                    cekungu("$trID $requiredPlace/$requiredGroupID required, $cabID and $whID got");
                        if ($cabID > 0 && (int)$whID < 0) {
                            //                    if ($cabID > 0 && (int)$whID == (int)$gudSpec['gudang_id']) {
                            $result = true;
                        }
                        else {
                            $result = false;
                        }
                        break;
                    default:
                        //                    cekhitam("none required, none got");
                        $result = false;
                        break;
                }
            }
            else {

                $result = false;
            }

        }
        else {
            $result = false;
        }
    }


    return $result;
}

function callSearchingLeft()
{
    $linkSearch = base_url() . "Searching/Hasil";
    $strMenuLeft = "<form class='sidebar-form'>";
    $strMenuLeft .= "<div class='input-group'>";
    // $strMenuLeft .= "<input type='text' class='form-control' data-toggle='modal' data-target='#myModal'>";
    $strMenuLeft .= "<input type='text' autofocus class='form-control' placeholder='pencarian produk/supplier' onkeyup=\"getData('$linkSearch?str='+this.value, 'search_result')\">";
    $strMenuLeft .= "<span class='input-group-btn'>
                            <button type='button' name='search' id='search-btn' class='btn btn-flat'><i class='fa fa-search'></i>
                            </button>
                          </span>";
    $strMenuLeft .= "</div>";
    $strMenuLeft .= "</form>";

    return $strMenuLeft;
}

function callMenuleft_backup()
{
    $strMenuLeft = "";
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heMenu");
    $ci->load->helper("he_access_right");
    $ci->load->model("Mdls/MdlMenuGroupUi");
    $topGr = $ci->uri->segment(3);
    $gu = new MdlMenuGroupUi();
    $dataConfig = $heDataBehaviour = $ci->config->item('heDataBehaviour');
    $dataRelConfig = $ci->config->item('dataRelation');
    $settingConfig = $ci->config->item('heSettingAdmin');
    $otherMenuConfig = $ci->config->item('menu');
    $availMenuConfig = $ci->config->item('availMenu');

    $availMenuMutasiRekeningConfig = $ci->config->item('availMenuMutasiRekening');
    $menuMutasiRekeningConfig = $ci->config->item('onMenuMutasiRekening');

    $availMenuReportConfig = $ci->config->item('availMenuReports');
    $availMenuHistoryConfig = $ci->config->item('availMenuHistories');
    $reportMenuConfig = $ci->config->item('onMenuReports');
    $historyMenuConfig = $ci->config->item('onMenuHistories');
    $toolMenuConfig = $ci->config->item('onMenuTool');


    $arrIcon = fa_icon();
    $loginType = $ci->session->login['jenis'];
    $eID = $ci->session->login['id'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    // $heTransaksiGroup_ui = (null != $ci->config->item("heTransaksiGroup_ui")) ? $ci->config->item("heTransaksiGroup_ui") : array();
    $heTransaksiGroup_uiDb = $gu->callGroupMenuTransaksiUi();
    $heTransaksiGroup_ui = $heTransaksiGroup_uiDb['transaksi'];
    $heDataGroup_ui = $heTransaksiGroup_uiDb['data'];
    $overWriteData_menu = overWriteMenuData();
    // arrPrint($overWriteData_menu);
    //region custom access here
    $dataAcc = alowedAccess($eID);
    $allowedCustom = array();
    if (sizeof($dataAcc) > 0) {
        foreach ($dataAcc as $jn => $tempData) {
            foreach ($tempData as $step => $targetData) {
                $allowedCustom[$jn][] = $step;
            }
            //            $jn = $tempData->menu_category;
            //            $availSteps = $tempData->steps;
            //            $newStep = str_replace($jn . "_", "", $availSteps);
            //            $allowedCustom[$jn][] = $newStep;

        }
    }
    //endregion
    $settingMenus = array();

    //region menu setting
    if (sizeof($ci->load->config("heSettingAdmin")) > 0) {
        foreach ($settingConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers']) && sizeof($mSpec['viewers']) > 0) {
                $isAllowed = false;
                if (isset($loginType) && in_array($loginType, $mSpec['viewers'])) {
                    $isAllowed = true;
                } else if (in_array('*', $mSpec['viewers'])) {
                    $isAllowed = true;
                } else if (sizeof($membership) > 0) {
                    foreach ($membership as $gID) {
                        if (in_array($gID, $mSpec['viewers'])) {
                            $isAllowed = true;
                            break;
                        }
                    }
                }
                if ($isAllowed) {
                    $tmpLabel = str_replace("Mdl", "", $mdlName);
                    $label = isset($settingConfig[$mdlName]['label']) ? $settingConfig[$mdlName]['label'] : $tmpLabel;
                    $settingMenus[$tmpLabel] = $label;
                }
            }
        }
    }
    //endregion

    $dataMenus = array();
    $dataExcludes = array();
    if (sizeof($dataRelConfig) > 0) {
        foreach ($dataRelConfig as $srcMdl => $sSpec) {
            foreach ($sSpec as $xmdlName => $xSpec) {
                $dataExcludes[$xmdlName] = $xmdlName;
            }
        }
    }
    // arrPrint($dataRelConfig);

    /* --------------------------
     * ------- DATA -------
     * --------------------------*/
    $allowedDataMenus = array();
    if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
        foreach ($dataConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers'])) {
                if (sizeof($mSpec['viewers']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['viewers'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $dataMenus[$tmpLabel] = array(
                                        "label" => $label . createObjectSuffix($label),
                                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                    );

                                    $allowedDataMenus[$mdlName] = $mdlName;
                                }
                            }
                        }
                    }
                }
            }
            if (isset($mSpec['creators'])) {
                if (sizeof($mSpec['creators']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['creators'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $dataMenus[$tmpLabel] = array(
                                        "label" => $label . createObjectSuffix($label),
                                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                    );

                                    $allowedDataMenus[$mdlName] = $mdlName;
                                }
                            }
                        }
                    }
                }
            }
        }

        $arrFungsiGroups = array(
            "creators",
            // "creatorAdmins",
            "viewers",
            // "updaters",
            // "updaterAdmins",
            // "deleters",
            // "deleterAdmins",
            // "historyViewers",
        );
        foreach ($heDataBehaviour as $mdlNama => $behaviorItems) {
            $memberAllowes = array();
            // cekOrange($mdlNama);

            foreach ($arrFungsiGroups as $fungsiGroup) {

                $arrFungsies = $behaviorItems[$fungsiGroup];
                foreach ($arrFungsies as $arrFungsy) {
                    // arrPrint($arrFungsy);
                    if (in_array($arrFungsy, $membership)) {
                        $memberAllowes[$arrFungsy] = $arrFungsy;
                    }
                }
            }
            $dataMemberAllowes[$mdlNama] = $memberAllowes;
        }

        // $gData_ui = $uis_now;
        // group data
        // arrPrint($allowedDataMenus);
        // $dataMemberAllowes_0 = array_keys(array_filter($dataMemberAllowes));
        // $allowedjenis = array_diff($dataMemberAllowes_0, $dataExcludes);

        // arrPrintWebs($heDataGroup_ui);
        $dataGrjenis = array();
        $gData_ui = array();
        foreach ($heDataGroup_ui as $dKey => $dIitems) {

            $dDatas = $dIitems["heTransaksi_ui"];
            $dataGrjenis[$dKey] = $dDatas;

            foreach ($dDatas as $arrFungsy) {
                if (in_array($arrFungsy, $allowedDataMenus)) {
                    $gData_ui[$dKey] = $dIitems;
                }
                // arrPrint($arrFungsy);
            }
        }
    }
    // arrPrint($gData_ui);
    // arrPrint($dataGrjenis);
    // arrPrintWebs($dataMenus);
    // arrPrintWebs($heDataGroup_ui);
    // matiHere(__LINE__);
    /* -------------------------------------------
     * -------- TRANSAKSI ---------
     * -----------------------------------------------*/
    $transMenus = array();
    if (sizeof($heTransaksi_ui) > 0) {
        $transLabels = array();
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            if (!isset($jSpec['hideMenu']) OR isset($jSpec['hideMenu']) && $jSpec['hideMenu'] == false) {
                $transLabels[$jenis] = strtolower($jSpec['label']);
            }
        }
        asort($transLabels);
        // arrPrint($transLabels);
        foreach ($transLabels as $jenis => $label) {
            // foreach ($heTransaksi_ui as $jenis => $jSpec) {
            $jSpec = $heTransaksi_ui[$jenis];
            // arrPrintWebs($jSpec);
            // arrPrintWebs($jSpec['steps']);
            // arrPrintWebs($membership);
            //            cekHere($jenis);
            if (isset($allowedCustom[$jenis]) && sizeof($allowedCustom) > 0) {
                $transMenus[$jenis] = "<sup><span id='tra$jenis'></span><span id='trb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
            }

            if (sizeof($membership) > 0) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    foreach ($jSpec['steps'] as $num => $sSpec) {
                        if (($ci->session->login['cabang_id'] == "-1" && $jSpec['place'] == "center") || ($ci->session->login['cabang_id'] != "-1" && $jSpec['place'] != "center")) {

                            if (in_array($sSpec['userGroup'], $membership)) {
                                $transMenus[$jenis] = "<sup><span id='tra$jenis'></span><span id='trb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
                            }
                            else {

                            }
                        }
                    }
                }
            }
        }
        // ----------------------------------------------------------------------
        $allowedjenis = array();
        if (isset($allowedCustom) && sizeof($allowedCustom) > 0) {
            /* -------------------------------------
             * mendapatkan jenis-transaksi accessRight (hak akses custom)
             * ------------------------------*/
            foreach ($allowedCustom as $cjenis => $top_steps) {
                $allowedjenis[] = $cjenis;
            }
        }
        else {
            /* -------------------------------------
             * mendapatkan jenis-transaksi dari userGroup
             * ------------------------------*/
            foreach ($heTransaksi_ui as $jenis => $jSpec) {
                // arrPrintWebs($jSpec);
                foreach ($jSpec['steps'] as $step) {
                    $userGroup = $step['userGroup'];
                    // cekHere($userGroup);
                    // $jenisMembers[$jenis][] = $userGroup;
                    // $memberJenies[$userGroup][] = $jenis;

                    if (in_array($userGroup, $membership)) {
                        $memberAllowes[$jenis] = $jenis;
                    }
                }
            }

            $allowedjenis = array_keys($memberAllowes);
        }
        // cekBiru(sizeof($memberAllowes));
        // arrPrintWebs($memberAllowes);
        // arrPrintWebs($memberJenies);
        // arrPrintWebs($jenisMembers);
        // arrPrint($allowedjenis);
        $xx = 0;
        $transGrjenis = array();
        $transGrLabels = array();
        foreach ($heTransaksiGroup_ui as $group => $gparams) {
            $new_params = array_intersect($gparams['heTransaksi_ui'], $allowedjenis);
            // $transGrjenis[$group] = $gparams['heTransaksi_ui'];
            // arrPrint($new_params);
            if (sizeof($new_params) > 0) {
                $xx++;
                $def_index = isset($gparams['index']) ? $gparams['index'] : "none_" . $xx;
                $def_icon = !empty($gparams['icon']) ? $gparams['icon'] : "fa-circle";
                $g_icon = "<i class='fa $def_icon'></i>";
                $transGrLabels[$def_index]['label'] = "<span class='text-danger'>" . $g_icon . "&nbsp;</span><span>" . strtolower($gparams['label']) . "</span>";
                $transGrLabels[$def_index]['group'] = $group;
                $transGrjenis[$group] = $new_params;
            }
        }

    }
    // ------------------------------------------------------
    // arrPrint($heTransaksiGroup_ui);
    // arrPrint($transGrjenis);
    // arrPrintWebs($allowedjenis);
    // arrPrint($transGrLabels);
    // arrPrintWebs($heTransaksiGroup_ui);

    $otherMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($otherMenuConfig[$gID]) && sizeof($otherMenuConfig[$gID]) > 0) {
                foreach ($otherMenuConfig[$gID] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($otherMenuConfig["*"]) && sizeof($otherMenuConfig["*"]) > 0) {
                foreach ($otherMenuConfig["*"] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }

    $reportMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            // arrPrint($reportMenuConfig[$gID]);
            if (isset($reportMenuConfig[$gID]) && sizeof($reportMenuConfig[$gID]) > 0) {
                foreach ($reportMenuConfig[$gID] as $kode) {
                    if (isset($availMenuReportConfig[$kode])) {
                        $reportMenus[$kode] = array(
                            "label" => $availMenuReportConfig[$kode]['label'],
                            "icon" => $availMenuReportConfig[$kode]['icon'],
                            "target" => $availMenuReportConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($reportMenuConfig["*"]) && sizeof($reportMenuConfig["*"]) > 0) {
                foreach ($reportMenuConfig["*"] as $kode) {
                    if (isset($availMenuReportConfig[$kode])) {
                        $reportMenus[$kode] = array(
                            "label" => $availMenuReportConfig[$kode]['label'],
                            "icon" => $availMenuReportConfig[$kode]['icon'],
                            "target" => $availMenuReportConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }


    $historyMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            // arrPrint($reportMenuConfig[$gID]);
            if (isset($historyMenuConfig[$gID]) && sizeof($historyMenuConfig[$gID]) > 0) {
                foreach ($historyMenuConfig[$gID] as $kode) {
                    if (isset($availMenuHistoryConfig[$kode])) {
                        $historyMenus[$kode] = array(
                            "label" => $availMenuHistoryConfig[$kode]['label'],
                            "icon" => $availMenuHistoryConfig[$kode]['icon'],
                            "target" => $availMenuHistoryConfig[$kode]['target'],
                        );
                    }
                }
            }

        }
    }


    //========================================================================//
    $mutasiMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($menuMutasiRekeningConfig[$gID]) && sizeof($menuMutasiRekeningConfig[$gID]) > 0) {
                foreach ($menuMutasiRekeningConfig[$gID] as $kode) {
                    if (isset($availMenuMutasiRekeningConfig[$kode])) {
                        $mutasiMenus[$kode] = array(
                            "label" => $availMenuMutasiRekeningConfig[$kode]['label'],
                            "icon" => $availMenuMutasiRekeningConfig[$kode]['icon'],
                            "target" => $availMenuMutasiRekeningConfig[$kode]['target'],
                        );
                    }
                }
            }
            //            if (isset($menuMutasiRekeningConfig["*"]) && sizeof($menuMutasiRekeningConfig["*"]) > 0) {
            //                foreach ($menuMutasiRekeningConfig["*"] as $kode) {
            //                    if (isset($availMenuMutasiRekeningConfig[$kode])) {
            //                        $mutasiMenus[$kode] = array(
            //                            "label" => $availMenuMutasiRekeningConfig[$kode]['label'],
            //                            "icon" => $availMenuMutasiRekeningConfig[$kode]['icon'],
            //                            "target" => $availMenuMutasiRekeningConfig[$kode]['target'],
            //                        );
            //                    }
            //                }
            //            }
        }
    }
    //arrPrint($mutasiMenus);

    //======= tools hanya debuger ============================================//
    $toolMenus = array();
    if (isset($ci->session->login['debuger']) && ($ci->session->login['debuger'] == 1)) {
        //        if(isset($ci->session->login['ghost']) && ($ci->session->login['ghost'] == 1)){
        if (isset($toolMenuConfig["*"]) && sizeof($toolMenuConfig["*"]) > 0) {
            foreach ($toolMenuConfig["*"] as $kode) {
                if (isset($availMenuConfig[$kode])) {
                    $toolMenus[$kode] = array(
                        "label" => $availMenuConfig[$kode]['label'],
                        "icon" => $availMenuConfig[$kode]['icon'],
                        "target" => $availMenuConfig[$kode]['target'],
                    );
                }
            }
        }
        //        }
    }


    $cPosition = $ci->uri->segment(1);
    $last = $ci->uri->total_segments();
    $subPosition = $ci->uri->segment($last);
    $cPosition_f = strtolower($cPosition);
    if ((int)$subPosition > 0 & (int)$subPosition - (int)$subPosition === 0 & $cPosition !== 'Transaksi') {
        $subPosition = $ci->uri->segment($last - 1);
    }
    $menuOpenActive = array();
    switch ($cPosition_f) {
        case "historyreport":
        case "spread":
        case "stok":
        case "ledger":
        case "neraca":
        case "overdue_releaser":
        case "katalog":
            $menuOpenActive['others'] = "1";
            break;
        case "activityreport":
            $menuOpenActive['activityreport'] = "1";
            break;
        case "historyreport":
            $menuOpenActive['historyreport'] = "1";
            break;
        case "ledger":
            $menuOpenActive['mutasirekening'] = "1";
            break;
        case "tools":
            $menuOpenActive['tools'] = "1";
            break;
        case "transaksi":
            if (isset($_GET['gr'])) {
                $menuOpenActive['transaksi'] = "1";
            }
            else {
                $menuOpenActive[$cPosition_f] = "1";
            }
            break;
        case "data":
            if (isset($_GET['md'])) {
                $menuOpenActive['data'] = "1";
            }
            else {
                $menuOpenActive[$cPosition_f] = "1";
            }
            break;
        default:
            $menuOpenActive[$cPosition_f] = "1";
            break;

    }


    if (sizeof($settingMenus) > 0) {
        $menu = "settings";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $label_f = strtolower($menu);
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-white text-uppercase'><i class='fa $fa_i'></i> <span class='text-uppercase'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        foreach ($settingMenus as $key => $label) {
            $label_f = strtolower($label);
            $fullMdlName = "Mdl" . $key;
            $customTarget = isset($settingConfig[$fullMdlName]['target']) ? $settingConfig[$fullMdlName]['target'] : (isset($settingConfig[$key]['target']) ? $settingConfig[$key]['target'] : "");
            $customIcon = isset($settingConfig[$fullMdlName]['icon']) ? $settingConfig[$fullMdlName]['icon'] : (isset($settingConfig[$key]['icon']) ? $settingConfig[$key]['icon'] : "");
            $targetUrl = !empty($customTarget) ? (strpos($customTarget, 'http') === 0 ? $customTarget : base_url() . $customTarget) : base_url() . "data/view/$key";
            $fa_i = !empty($customIcon) ? $customIcon : (array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle");
            $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = $subPosition == $jenis ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . $targetUrl . "'><i class='fa $fa_i'></i> <span>$label $subMenuArrowActive</span></a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }

    /* -------------------------------------------
     * ---- TRANSAKSI - GUI -------
     * -----------------------------------------------*/
    if (sizeof($transMenus) > 0) {
        // $menu = "transaksi";
        // // $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
        // // cekHere($menuOpenActive[$menu]);
        // $classMenuOpen = "";
        // $styleMenuOpen = "";
        // if (!isset($_GET['gr'])) {
        //     // cekBiru($reqgroup);
        //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        // }
        // $label_f = strtolower($menu);
        // $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        // $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
        //             <a href='#' class='text-muted text-uppercase'><i class='fa $fa_i'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
        //                 <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
        //             </a>";
        // $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        // foreach ($transMenus as $jenis => $label) {
        //     $label_f = strtolower($label);
        //     $subMenuActive = $subPosition == $jenis ? "bg-primary active text-bold" : "";
        //     $subMenuArrowActive = $subPosition == $jenis ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
        //     $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
        //     $strMenuLeft .= " <a href='" . base_url() . "Transaksi/index/$jenis'>$label$subMenuArrowActive</a>";
        //     $strMenuLeft .= "";
        //     $strMenuLeft .= "</li>";
        // }
        // $strMenuLeft .= "</ul>";
        // $strMenuLeft .= "</li>";
        // $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }
    /* --------------------------------
     * $menu_mode = " .."; // diisi string php atau js
     * "php" == klik langsung lari ke halaman pertama dr mu dalam group
     * "js" == diem saja disitu yg berubah hanya menu top saja
     * ------------------------------------*/
    $menu_mode = "php";
    if (sizeof($transGrLabels) > 0) {
        // $transGrLabels
        $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
        $reqgroup = isset($_GET['gr']) ? base64_decode($req_group) : "";
        $menu = "transaksi";
        $classMenuOpen = "";
        $styleMenuOpen = "";
        if (isset($_GET['gr'])) {
            // cekBiru($reqgroup);
            $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
            $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        }

        $label_f = strtolower($menu);
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-exchange";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                    <a href='#' class='text-white text-uppercase'><i class='fa $fa_i'></i> <span class='text-white'>$menu</span> <i id='_mnpr_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                        <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>    
                    </a>";
        $strMenuLeft .= "<ul id='_$menu' class=\"treeview-menu\" $styleMenuOpen>";

        foreach ($transGrLabels as $jenis => $grLabels) {

            $label = $grLabels['label'];
            $label_f = strtolower($label);
            $glabel = $grLabels['group'];
            $glabel_sr = str_replace("=", "", base64_encode($glabel));
            $newIndex = reset($transGrjenis[$glabel]); // mengambil array pertama
            // cekOrange("$glabel ** $reqgroup");
            // $glabel ** $reqgroup
            $subMenuActive = "";
            $subMenuArrowActive = "";

            $perGroup = isset($heTransaksiGroup_ui[$glabel]['heTransaksi_ui']) ? $heTransaksiGroup_ui[$glabel]['heTransaksi_ui'] : array();

            if ($glabel == $reqgroup) {
                $subMenuActive = "text-red";
                $subMenuArrowActive = "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>";
            }

            $strMenuLeft .= "<li style='atext-shadow: 1px -1px #0a0a0a;' class='text-capitalize'>";
            $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr&md=transaksi";
            switch ($menu_mode) {
                case "php":
                    $strMenuLeft .= " <a class='$subMenuActive' id='$newIndex' href=\"javascript:void(0);\" hsref='" . base_url() . "Transaksi/index/$newIndex?gr=$glabel_sr'>
                                         <span>$label</span> $subMenuArrowActive 
                                    <span class='badge badge-sky' id='left_$glabel'></span>
                                      <div name='tempat_sub_ul' style='display:none;' class='tempat_sub_ul_$glabel_sr'></div>
                                      </a>
                                      
                                    ";
                    $strMenuLeft .= "
                    <script>
                        $('#$newIndex').on('click', delay_v2( function(){
                            if( $('.tempat_sub_ul_$glabel_sr').html().length > 0 ){
                                $('.tempat_sub_ul_$glabel_sr').fadeOut();
                                $('.tempat_sub_ul_$glabel_sr').html('');
                            }
                            else{
//                                $('#menu_top').load('$url_loader');
//                                $('div[name=tempat_sub_ul]').html('');
                                $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                                $('.tempat_sub_ul_$glabel_sr').fadeIn();
                            }
                        }, 100) );
                        if('$dKey'=='$reqgroup'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                        }
                        if('$glabel_sr'=='$req_group'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                        }
//                        console.log('tr->> dKey: $dKey');
//                        console.log('tr->> reqgroup: $reqgroup');
//                        console.log('tr->> req_group: $req_group');
//                        console.log('tr->> topGr: $topGr');
//                        console.log('tr->> glabel_sr: $glabel_sr');
//                        console.log('tr->> newIndex: $newIndex');
//                        $('#$newIndex').on('mouseover', delay_v2( function(barangam){
                            //$('#menu_top').load('$url_loader');
//                        }, 100) );
                    </script>";
                    break;
                case "js":
                    // $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr";
                    $strMenuLeft .= " <a href='javascript:void(0);' onclicks=\"$('#menu_top').load('$url_loader');\">$label$subMenuArrowActive
                                     <span class='badge badge-sky' id='left_$glabel'></span>
                                     </a>";
                    break;
            }

            $strMenuLeft .= "";
            $strMenuLeft .= "</li>";

            //            arrprint( callMenuTopJson($glabel_sr) );

            $strMenuLeft .= "\n<script>

                var perGroup = " . callMenuTopJson($glabel_sr) . ";
                var perGroupJenis = [];
                var perGroupTotal = 0;

                //console.log(\"%c$glabel\", \"color:green\");

                jQuery.each(perGroup, function(i, d){
                    perGroupJenis[d] = null != localStorage.getItem(d) ? localStorage.getItem(d) : 0
                    perGroupTotal += parseFloat(perGroupJenis[d]);
                });

                if( perGroupTotal > 0 ) {  $('#left_$glabel').html(perGroupTotal)  } else {  $('left_$glabel').html('')  }

                //console.log(perGroupTotal);
                //console.error('========== ========= ========== ========== ==========');

            </script>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }
    // -------------------------------------------- END OF TRANSAKASI GUI ------------------------

    /* ------------------------------
     * ---- DATA - GUI ----
     * ------------------------------*/
    if (sizeof($dataMenus) > 0) {
        // $menu = "data";
        // $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        // $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        // $label_f = strtolower($label);
        // $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        // $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
        //         <a href='#' class='text-muted text-uppercase'><i class='fa $fa_i'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
        //         <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
        //         </a>";
        // $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        // foreach ($dataMenus as $key => $arrData) {
        //     $label = strtolower($arrData['label']);
        //     $dataLabels[$key] = $label;
        // }
        // asort($dataLabels);
        // // arrPrint($dataLabels);
        // foreach ($dataLabels as $key => $label) {
        //     // foreach ($dataMenus as $key => $arrData) {
        //     $arrData = $dataMenus[$key];
        //
        //     $label = $arrData['label'];
        //     $badge = $arrData['badge'];
        //     $label_f = strtolower($label);
        //     $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
        //     $subMenuArrowActive = $subPosition == $key ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
        //     $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle";
        //
        //     $keyNew = $key == "DataHistory" ? "viewHistories" : "viewdt";
        //     $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
        //     $strMenuLeft .= "<a href='" . base_url() . "data/$keyNew/$key'>$badge <i class='fa $fa_i'></i> $label_f $subMenuArrowActive</a>";
        //     $strMenuLeft .= "</li>";
        //
        // }
        // $strMenuLeft .= "</ul>";
        // $strMenuLeft .= "</li>";
        // $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

        // -------------------- -------DATA BARU-----------------------
        $menu = "data";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $label_f = strtolower($label);
        $topGr = $ci->uri->segment(3);
        $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
        $reqgroup = isset($_GET['gr']) ? base64_decode($req_group) : "";
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-white text-uppercase'><i class='fa $fa_i'></i> 
                    <span class='text-white'>$menu</span>
                    <i class='badge badge-info' id='mn_$menu'></i> 
                    <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center pull-left blink'></i>
                    <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";

        $menu_mode = "php";

        foreach ($gData_ui as $dKey => $dIitems) {

            $label = $dIitems['label'];
            $badge = !empty($dIitems['icon']) ? $dIitems['icon'] : "fa-circle";
            $label_f = strtolower($label);

            $glabel = "";
            $glabel_sr = str_replace("=", "", base64_encode($dKey));
            $newIndex = reset($dataGrjenis[$dKey]); // mengambil array pertama

            $subMenuActive = "";
            $subMenuArrowActive = "";
            if ($dKey == $reqgroup) {
                $subMenuActive = "bg-primarys active text-bold";
                $subMenuArrowActive = "";
            }

            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize'>";

            $label = "<i class='fa $badge text-orange'></i> <span class='text-white'>$label_f</span> <span id='caret_$newIndex' class='text-white pull-right'><i class='fa fa-caret-left'></i></span>";

            $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr&md=data";
            switch ($menu_mode) {
                case "php":
                    $newIndex_f = str_replace("Mdl", "", $newIndex);
                    if (isset($overWriteData_menu[$newIndex_f])) {
                        $index_data = $overWriteData_menu[$newIndex_f];
                    }
                    else {
                        $index_data = "viewdt";
                    }
                    $strMenuLeft .= "<a class='$subMenuActive' id='$newIndex' href=\"javascript:void(0);\" hrsef='" . base_url() . "Data/$index_data/$newIndex_f?gr=$glabel_sr&md=data' onmousseovers=\"$('#menu_top').load('$url_loader');\">
                                        <div>$label</div> $subMenuArrowActive
                                    <span class='badge badge-sky' id='left_$glabel'></span>
                                        <div name='tempat_sub_ul' style='display:none;' class='tempat_sub_ul_$glabel_sr'></div>
                                    </a>";
                    $strMenuLeft .= "
                    <script>
                        $('#$newIndex').on('click', delay_v2( function(){
                            if( $('.tempat_sub_ul_$glabel_sr').html().length > 0 ){
                                $('.tempat_sub_ul_$glabel_sr').fadeOut();
                                $('.tempat_sub_ul_$glabel_sr').html('');
                            }
                            else{

                                $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                                $('.tempat_sub_ul_$glabel_sr').fadeIn();
                            }
                        }, 100) );
                        if('$dKey'=='$reqgroup'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                            $('#caret_$newIndex').html(\"<i class='fa fa-caret-down text-yellow'></i>\");
                        }

                    </script>";
                    break;
                case "js":
                    // $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr";
                    $strMenuLeft .= " <a href='javascript:void(0);' onclicks=\"$('#menu_top').load('$url_loader');\">$label$subMenuArrowActive
                                     <span class='badge badge-sky' id='left_$glabel'></span>
                                     </a>";
                    break;
            }

            $strMenuLeft .= "</li>";
        }

        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 200);</script>";
    }
    // -------------------------------------------- END OF DATA GUI ------------------------

    //=======================================================================
    if (sizeof($mutasiMenus) > 0) {
        $menu = "mutasirekening";
        $menuLabel = "mutasi rekening";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-white text-uppercase'><i class='fa fa-server'></i> <span class='text-white'>$menuLabel</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        $mutasiLabels = array();
        foreach ($mutasiMenus as $key => $mSpec) {
            $mutasiLabels[$key] = strtolower($mSpec['label']);
        }
        //        asort($mutasiLabels);
        //        arrPrint($mutasiLabels);

        foreach ($mutasiLabels as $key => $label) {
            $mSpec = $mutasiMenus[$key];
            // foreach ($otherMenus as $key => $mSpec) {

            $explodedTarget = explode('/', $mSpec['target']);
            $numExpoded = sizeof($explodedTarget);
            $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
            $strMenuLeft .= "</li>";

        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }
    if (sizeof($toolMenus) > 0) {

        $menu = "tools";
        $menuLabel = "tools";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $strMenuLeft .= "<li class='treeview$classMenuOpen'>
                <a href='#' class='text-white text-uppercase'><i class='fa fa-server'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        $toolLabels = array();
        foreach ($toolMenus as $key => $mSpec) {
            $toolLabels[$key] = strtolower($mSpec['label']);
        }

        foreach ($toolLabels as $key => $label) {
            $mSpec = $toolMenus[$key];
            // foreach ($otherMenus as $key => $mSpec) {

            $explodedTarget = explode('/', $mSpec['target']);
            $numExpoded = sizeof($explodedTarget);
            $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

    }

    if (sizeof($otherMenus) > 0) {
        $menu = "others";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $strMenuLeft .= "<li class='treeview$classMenuOpen'>
                <a href='#' class='text-muted text-uppercase'><i class='fa fa-server'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class='treeview-menu' $styleMenuOpen>";
        $otherLabels = array();
        foreach ($otherMenus as $key => $mSpec) {
            $otherLabels[$key] = strtolower($mSpec['label']);
        }
        asort($otherLabels);
        // arrPrint($otherLabels);

        foreach ($otherLabels as $key => $label) {
            $mSpec = $otherMenus[$key];
            // foreach ($otherMenus as $key => $mSpec) {

            $explodedTarget = explode('/', $mSpec['target']);
            $numExpoded = sizeof($explodedTarget);
            $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

    }
    if (sizeof($reportMenus) > 0) {
        $menu = "activityreport";
        // $menuLabel = "management reports";
        $menuLabel = "reportings";
        $aliasPosisi = array(
            "viewsalesrel" => "realisasiSo",
            "bl" => "invoicing",
            "582" => "monthly",
            "viewgraphsales" => "graphMonthly",
            "viewpresalesall" => "prepenjualanAll",
            "viewsalesall" => "penjualanAll",
            "viewpurchasingspall" => "pembelianSpAll",
            "viewpurchasingfgall" => "pembelianFgAll",
            "viewproduksales" => "biOrder",
        );
        $subPosition_s = strtolower(trim($ci->uri->segment($last)));
        $subPosition = isset($aliasPosisi[$subPosition_s]) ? $aliasPosisi[$subPosition_s] : "";

        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-muted text-uppercase'><i class='fa fa-line-chart'></i> <span class='text-muted'>$menuLabel</span> <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center'></i>
                <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";

        // arrPrint($subPosition_s);
        // arrPrint($reportMenus);
        foreach ($reportMenus as $key => $mSpec) {
            // $explodedTarget = explode('/', $mSpec['label']);
            // $numExpoded = sizeof($explodedTarget);
            // cekHijau($key ." ". $ci->uri->segment(2) . " " .$subPosition);

            $subMenuActive = trim($key) == $subPosition ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = trim($key) == $subPosition ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
            //             cekHijau($key . " *** " . $subPosition);

            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='fa " . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

    }
    if (sizeof($historyMenus) > 0) {
        $menu = "historyreport";
        $menuLabel = "historical reports";
        $subPosition = strtolower(trim($ci->uri->segment($last)));

        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $strMenuLeft .= "<li class='treeview$classMenuOpen'>
                <a href='#' class='text-muted text-uppercase'><i class='fa fa-history'></i> <span class='text-muted'>$menuLabel</span> <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center'></i>
                <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class='treeview-menu' $styleMenuOpen>";

        // arrPrint($subPosition);
        // arrPrint($reportMenus);
        foreach ($historyMenus as $key => $mSpec) {
            // $explodedTarget = explode('/', $mSpec['label']);
            // $numExpoded = sizeof($explodedTarget);
            // cekHijau($key ." ". $ci->uri->segment(2) . " " .$subPosition);

            $subMenuActive = trim($key) == $subPosition ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = trim($key) == $subPosition ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
            // cekHijau($key . " *** " . $subPosition);

            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='fa " . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

    }

    //region logout doang boss
    // https://demo.mayagrahakencana.com/tho-san/Login/authLogout
    $logout_l = base_url() . "auth/Login/authLogout";
    $strMenuLeft .= "<li class='treeview'>
                <a href='$logout_l' class='text-muted text-uppercase'><i class='fa fa-power-off'></i> <span class='text-muted'>Logout</span>
                <span class='pull-right-container'><i classs='fa fa-angle-left pull-right'></i></span>
                </a>";
    $strMenuLeft .= "</li>";
    //endregion

    $strMenuLeft .= "</ul>";

    return $strMenuLeft;
}

function callAvailableMenu_he_menu()
{
    $ci =& get_instance();
    $ci->load->config("heMenu");
    $groupMenuShowConfig = $ci->config->item('menu');
    $groupMenuConfig = $ci->config->item('groupMenu');
    foreach ($groupMenuConfig as $group_key => $group_params) {
        // $group_alias = $group_params['label'];
        // $availMenu[] = $group_params['availMenu'];
        // $groupDatas['mg_key'] = $group_key;
        // $groupDatas['mg_alias'] = $group_alias;
        // $mnGroups
        // $mgroups[] = $groupDatas;

        // cekBiru($availMenu);
        foreach ($group_params['availMenu'] as $menu_key => $menu_params) {
            $availMenu[$menu_key] = $menu_params;
        }
    }

    return $availMenu;
}

function callMenuLabel_he_menu()
{
    /* --------------------------------------------
        * title relatif dari label menu
        * --------------------------------------------*/
//    $ddd = isset($_GET['gr']) ? $_GET['gr'] : "";
    // cekHere(base64_decode($ddd));
    $menuLabel = "";
    if (isset($_GET['gr'])) {
        // $menuSign = blobDecode($_GET['gr']);
        $menuSign = base64_decode($_GET['gr']);
        // cekBiru($menuSign);
        $menuSign_exp = explode("-", $menuSign);

        $menuKey = $menuSign_exp[1];
        $availablemenus = callAvailableMenu_he_menu();
        $menuLabel = $availablemenus[$menuKey]['label'];
        // cekMerah("$menuKey $menuLabel");
        // $this->menuLabel = $menuLabel;
    }

    return $menuLabel;
}

function callMenuleft()
{
    //    $menuManufacturing = produksiMenu();
    $menuManufacturing = array();
    $strMenuLeft = "";
    // $strMenuLeft .= "<div >=====================</div>";
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heMenu");
    $ci->load->helper("he_access_right");
    $ci->load->model("Mdls/MdlMenuGroupUi");
    $topGr = $ci->uri->segment(3);
    $gu = new MdlMenuGroupUi();
    $dataConfig = $heDataBehaviour = $ci->config->item('heDataBehaviour');
    $dataRelConfig = $ci->config->item('dataRelation');
    $settingConfig = $ci->config->item('heSettingAdmin');
    $otherMenuConfig = $ci->config->item('menu');
    $availMenuConfig = $ci->config->item('availMenu');

    $groupMenuShowConfig = $ci->config->item('menu');
    $groupMenuConfig = $ci->config->item('groupMenu');

    $availMenuMutasiRekeningConfig = $ci->config->item('availMenuMutasiRekening');
    $menuMutasiRekeningConfig = $ci->config->item('onMenuMutasiRekening');

    $availMenuReportConfig = $ci->config->item('availMenuReports');
    $availMenuHistoryConfig = $ci->config->item('availMenuHistories');
    $reportMenuConfig = $ci->config->item('onMenuReports');
    $historyMenuConfig = $ci->config->item('onMenuHistories');
    $toolMenuConfig = $ci->config->item('onMenuTool');

    $arrIcon = fa_icon();
    $loginType = $ci->session->login['jenis'];
    $eID = $ci->session->login['id'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $heTransaksiGroup_uiDb = $gu->callGroupMenuTransaksiUi();
    // showLast_query("kuning");
    $heTransaksiGroup_ui = $heTransaksiGroup_uiDb['transaksi'];
    $heDataGroup_ui = $heTransaksiGroup_uiDb['data'];
    $overWriteData_menu = overWriteMenuData();
    //region custom access here
    $dataAcc = alowedAccess($eID);
    $allowedCustom = array();
    if (sizeof($dataAcc) > 0) {
        foreach ($dataAcc as $jn => $tempData) {
            foreach ($tempData as $step => $targetData) {
                $allowedCustom[$jn][] = $step;
            }
        }
    }
    //endregion
    $settingMenus = array();

    //region menu setting
    if (sizeof($ci->load->config("heSettingAdmin")) > 0) {
        foreach ($settingConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers']) && sizeof($mSpec['viewers']) > 0) {
                $isAllowed = false;
                if (isset($loginType) && in_array($loginType, $mSpec['viewers'])) {
                    $isAllowed = true;
                } else if (in_array('*', $mSpec['viewers'])) {
                    $isAllowed = true;
                } else if (sizeof($membership) > 0) {
                    foreach ($membership as $gID) {
                        if (in_array($gID, $mSpec['viewers'])) {
                            $isAllowed = true;
                            break;
                        }
                    }
                }
                if ($isAllowed) {
                    $tmpLabel = str_replace("Mdl", "", $mdlName);
                    $label = isset($settingConfig[$mdlName]['label']) ? $settingConfig[$mdlName]['label'] : $tmpLabel;
                    $settingMenus[$tmpLabel] = $label;
                }
            }
        }
    }
    //endregion

    $dataMenus = array();
    $dataExcludes = array();
    if (sizeof($dataRelConfig) > 0) {
        foreach ($dataRelConfig as $srcMdl => $sSpec) {
            foreach ($sSpec as $xmdlName => $xSpec) {
                $dataExcludes[$xmdlName] = $xmdlName;
            }
        }
    }
    /* --------------------------
     * ------- DATA -------
     * --------------------------*/
    // arrPrint($ci->session->login['membership']);
    $allowedDataMenus = array();
    /*
     * custom hak akses master data
     */
    $dipakai = 0;
    if ($dipakai == 1) {
        // script lama
        if (in_array("c_holding", $ci->session->login['membership'])) {
            if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
                foreach ($dataConfig as $mdlName => $mSpec) {
                    if (isset($mSpec['viewers'])) {
                        if (sizeof($mSpec['viewers']) > 0) {
                            if (sizeof($membership) > 0) {
                                foreach ($membership as $gID) {
                                    if (in_array($gID, $mSpec['viewers'])) {
                                        $tmpLabel = str_replace("Mdl", "", $mdlName);
                                        $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                        if (!in_array($mdlName, $dataExcludes)) {
                                            $dataMenus[$tmpLabel] = array(
                                                "label" => $label . createObjectSuffix($label),
                                                "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                            );

                                            $allowedDataMenus[$mdlName] = $mdlName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($mSpec['creators'])) {
                        if (sizeof($mSpec['creators']) > 0) {
                            if (sizeof($membership) > 0) {
                                foreach ($membership as $gID) {
                                    if (in_array($gID, $mSpec['creators'])) {
                                        $tmpLabel = str_replace("Mdl", "", $mdlName);
                                        $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                        if (!in_array($mdlName, $dataExcludes)) {
                                            $dataMenus[$tmpLabel] = array(
                                                "label" => $label . createObjectSuffix($label),
                                                "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                            );

                                            $allowedDataMenus[$mdlName] = $mdlName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // arrPrintWebs($allowedDataMenus);

            }
        }
        else {
            //untuk bagian custom hak akses data
            $dataTmpAkses = availMenuData($eID);
            $dataMenus = $dataTmpAkses["dataMenus"];
            $allowedDataMenus = $dataTmpAkses["allowedDataMenus"];
            $dataMemberAllowed = $dataTmpAkses["fungsi"];

            foreach ($heDataBehaviour as $mdlNama => $behaviorItem_0) {
                if (isset($dataMemberAllowed[$mdlNama])) {
                    foreach ($dataMemberAllowed[$mdlNama] as $fs => $fsMember) {
                        // unset( $heDataBehaviour[$mdlNama][$fs]);
                        $heDataBehaviour[$mdlNama][$fs] = $fsMember;
                    }
                }
            }
        }
    }
    else {
        $dataTmpAkses = availMenuData($eID);
        $dataMenus = isset($dataTmpAkses["dataMenus"]) ? $dataTmpAkses["dataMenus"] : array();
        $allowedDataMenus = isset($dataTmpAkses["allowedDataMenus"]) ? $dataTmpAkses["allowedDataMenus"] : array();
        $dataMemberAllowed = isset($dataTmpAkses["fungsi"]) ? $dataTmpAkses["fungsi"] : array();

        if (count($dataMemberAllowed) == 0) {
            // echo("dr behavior");
            if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
                foreach ($dataConfig as $mdlName => $mSpec) {
                    if (isset($mSpec['viewers'])) {
                        if (sizeof($mSpec['viewers']) > 0) {
                            if (sizeof($membership) > 0) {
                                foreach ($membership as $gID) {
                                    if (in_array($gID, $mSpec['viewers'])) {
                                        $tmpLabel = str_replace("Mdl", "", $mdlName);
                                        $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                        if (!in_array($mdlName, $dataExcludes)) {
                                            $dataMenus[$tmpLabel] = array(
                                                "label" => $label . createObjectSuffix($label),
                                                "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                            );

                                            $allowedDataMenus[$mdlName] = $mdlName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($mSpec['creators'])) {
                        if (sizeof($mSpec['creators']) > 0) {
                            if (sizeof($membership) > 0) {
                                foreach ($membership as $gID) {
                                    if (in_array($gID, $mSpec['creators'])) {
                                        $tmpLabel = str_replace("Mdl", "", $mdlName);
                                        $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                        if (!in_array($mdlName, $dataExcludes)) {
                                            $dataMenus[$tmpLabel] = array(
                                                "label" => $label . createObjectSuffix($label),
                                                "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                                            );

                                            $allowedDataMenus[$mdlName] = $mdlName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // arrPrintWebs($allowedDataMenus);
            }
        }
        else {
            foreach ($heDataBehaviour as $mdlNama => $behaviorItem_0) {
                if (isset($dataMemberAllowed[$mdlNama])) {
                    foreach ($dataMemberAllowed[$mdlNama] as $fs => $fsMember) {
                        // unset( $heDataBehaviour[$mdlNama][$fs]);
                        $heDataBehaviour[$mdlNama][$fs] = $fsMember;
                    }
                }
            }
        }
    }


    // arrPrintWebs($heDataBehaviour);
    $arrFungsiGroups = array(
        "creators",
        // "creatorAdmins",
        "viewers",
        // "updaters",
        // "updaterAdmins",
        // "deleters",
        // "deleterAdmins",
        // "historyViewers",
    );
    foreach ($heDataBehaviour as $mdlNama => $behaviorItems) {
        $memberAllowes = array();
        // cekOrange($mdlNama);

        foreach ($arrFungsiGroups as $fungsiGroup) {

            $arrFungsies = $behaviorItems[$fungsiGroup];
            // arrPrint($arrFungsies);
            foreach ($arrFungsies as $arrFungsy) {
                // cekMerah($arrFungsy);
                if (in_array($arrFungsy, $membership)) {
                    $memberAllowes[$arrFungsy] = $arrFungsy;
                }
            }
        }
        $dataMemberAllowes[$mdlNama] = $memberAllowes;
    }

    // $gData_ui = $uis_now;
    // group data
    // arrPrint($allowedDataMenus);
    // $dataMemberAllowes_0 = array_keys(array_filter($dataMemberAllowes));
    // $allowedjenis = array_diff($dataMemberAllowes_0, $dataExcludes);

    // arrPrintWebs($dataMemberAllowes);
    $dataGrjenis = array();
    $gData_ui = array();
    foreach ($heDataGroup_ui as $dKey => $dIitems) {
        $dDatas = $dIitems["heTransaksi_ui"];
        $dataGrjenis[$dKey] = $dDatas;
        foreach ($dDatas as $arrFungsy) {
            if (in_array($arrFungsy, $allowedDataMenus)) {
                $gData_ui[$dKey] = $dIitems;
            }
            // arrPrint($arrFungsy);
        }
    }

    /* -------------------------------------------
     * -------- TRANSAKSI ---------
     * -----------------------------------------------*/
    $transMenus = array();
    if (sizeof($heTransaksi_ui) > 0) {
        $transLabels = array();
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            if (!isset($jSpec['hideMenu']) OR isset($jSpec['hideMenu']) && $jSpec['hideMenu'] == false) {
                $transLabels[$jenis] = strtolower($jSpec['label']);
            }
        }
        asort($transLabels);
        // arrPrint($transLabels);
        foreach ($transLabels as $jenis => $label) {
            // foreach ($heTransaksi_ui as $jenis => $jSpec) {
            $jSpec = $heTransaksi_ui[$jenis];
            // arrPrintWebs($jSpec);
            // arrPrintWebs($jSpec['steps']);
            // arrPrintWebs($membership);
            //            cekHere($jenis);
            if (isset($allowedCustom[$jenis]) && sizeof($allowedCustom) > 0) {
                $transMenus[$jenis] = "<sup><span id='tra$jenis'></span><span id='trb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
            }

            if (sizeof($membership) > 0) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    foreach ($jSpec['steps'] as $num => $sSpec) {
                        if (($ci->session->login['cabang_id'] == "-1" && $jSpec['place'] == "center") || ($ci->session->login['cabang_id'] != "-1" && $jSpec['place'] != "center")) {

                            if (in_array($sSpec['userGroup'], $membership)) {
                                $transMenus[$jenis] = "<sup><span id='tra$jenis'></span><span id='trb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
                            }
                            else {

                            }
                        }
                    }
                }
            }
        }
        // ----------------------------------------------------------------------
        $allowedjenis = array();
        if (isset($allowedCustom) && sizeof($allowedCustom) > 0) {
            /* -------------------------------------
             * mendapatkan jenis-transaksi accessRight (hak akses custom)
             * ------------------------------*/
            foreach ($allowedCustom as $cjenis => $top_steps) {
                $allowedjenis[] = $cjenis;
            }
        }
        else {
            /* -------------------------------------
             * mendapatkan jenis-transaksi dari userGroup
             * ------------------------------*/
            foreach ($heTransaksi_ui as $jenis => $jSpec) {
                // arrPrintWebs($jSpec);
                foreach ($jSpec['steps'] as $step) {
                    $userGroup = $step['userGroup'];
                    // cekHere($userGroup);
                    // $jenisMembers[$jenis][] = $userGroup;
                    // $memberJenies[$userGroup][] = $jenis;

                    if (in_array($userGroup, $membership)) {
                        $memberAllowes[$jenis] = $jenis;
                    }
                }
            }

            $allowedjenis = array_keys($memberAllowes);
        }
        // cekBiru(sizeof($memberAllowes));
        // arrPrintWebs($memberAllowes);
        // arrPrintWebs($memberJenies);
        // arrPrintWebs($jenisMembers);
        // arrPrint($allowedjenis);
        $xx = 0;
        $transGrjenis = array();
        $transGrLabels = array();
        foreach ($heTransaksiGroup_ui as $group => $gparams) {
            $new_params = array_intersect($gparams['heTransaksi_ui'], $allowedjenis);
            // $transGrjenis[$group] = $gparams['heTransaksi_ui'];
            // arrPrint($new_params);
            if (sizeof($new_params) > 0) {
                $xx++;
                $def_index = isset($gparams['index']) ? $gparams['index'] : "none_" . $xx;
                $def_icon = !empty($gparams['icon']) ? $gparams['icon'] : "fa-circle";
                $g_icon = "<i class='fa $def_icon'></i>";
                $transGrLabels[$def_index]['label'] = "<span class='text-danger'>" . $g_icon . "&nbsp;</span><span>" . strtolower($gparams['label']) . "</span>";
                $transGrLabels[$def_index]['group'] = $group;
                $transGrjenis[$group] = $new_params;
            }
        }

    }

    $otherMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($otherMenuConfig[$gID]) && sizeof($otherMenuConfig[$gID]) > 0) {
                foreach ($otherMenuConfig[$gID] as $kode) {

                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }

                }
            }
            if (isset($otherMenuConfig["*"]) && sizeof($otherMenuConfig["*"]) > 0) {
                foreach ($otherMenuConfig["*"] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }

    $groupMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($groupMenuShowConfig[$gID]) && sizeof($groupMenuShowConfig[$gID]) > 0) {
                foreach ($groupMenuShowConfig[$gID] as $kode) {

                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }

                }
            }
            if (isset($groupMenuShowConfig["*"]) && sizeof($groupMenuShowConfig["*"]) > 0) {
                foreach ($groupMenuShowConfig["*"] as $kode) {

                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }

                }
            }
        }
    }

    foreach ($groupMenuConfig as $groupMenu => $groupMenus) {
        $groupLink = isset($groupMenus['target']) ? $groupMenus['target'] : "";
        $availMenus = isset($groupMenus['availMenu']) ? $groupMenus['availMenu'] : array();
        //         cekBiru($availMenus);
        foreach ($membership as $groupAkses) {
            // arrPrintPink($menuAkses);
            if (isset($groupMenus['target'])) {
                $groupingMenus[$groupMenu][$kode] = array(
                    "label" => isset($availMenus[$kode]['label']) ? $availMenus[$kode]['label'] : "",
                    "icon" => isset($availMenus[$kode]['icon']) ? $availMenus[$kode]['icon'] : "",
                    "target" => isset($availMenus[$kode]['target']) ? $availMenus[$kode]['target'] : "",
                );
            }
            else {
                if (isset($groupMenuShowConfig[$groupAkses])) {
                    foreach ($groupMenuShowConfig[$groupAkses] as $kode) {
                        // cekMerah($kode);
                        if (isset($availMenus[$kode])) {
                            $groupingMenus[$groupMenu][$kode] = array(
                                "label" => $availMenus[$kode]['label'],
                                "icon" => $availMenus[$kode]['icon'],
                                "target" => $availMenus[$kode]['target'],
                            );
                        }

                    }
                }

                if (!in_array("c_laporan", my_memberships())) {

                    foreach ($groupMenuShowConfig["*"] as $kode) {
                        // cekBiru($kode);
                        if (isset($availMenus[$kode])) {
                            $groupingMenus[$groupMenu][$kode] = array(
                                "label" => $availMenus[$kode]['label'],
                                "icon" => $availMenus[$kode]['icon'],
                                "target" => $availMenus[$kode]['target'],
                            );
                        }

                    }
                }
            }

        }
        //
        //=======menu tools hanya debuger ============================================//
        if (isset($ci->session->login['debuger']) && ($ci->session->login['debuger'] == 1)) {
            if (isset($toolMenuConfig["*"]) && sizeof($toolMenuConfig["*"]) > 0) {
                foreach ($toolMenuConfig["*"] as $kode) {
                    if (isset($availMenus[$kode])) {
                        $groupingMenus[$groupMenu][$kode] = array(
                            "label" => $availMenus[$kode]['label'],
                            "icon" => $availMenus[$kode]['icon'],
                            "target" => $availMenus[$kode]['target'],
                        );
                    }
                }
            }
        }

    }
// arrPrintHijau(my_memberships());

    $reportMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            // arrPrint($reportMenuConfig[$gID]);
            if (isset($reportMenuConfig[$gID]) && sizeof($reportMenuConfig[$gID]) > 0) {
                foreach ($reportMenuConfig[$gID] as $kode) {
                    if (isset($availMenuReportConfig[$kode])) {
                        $reportMenus[$kode] = array(
                            "label" => $availMenuReportConfig[$kode]['label'],
                            "icon" => $availMenuReportConfig[$kode]['icon'],
                            "target" => $availMenuReportConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($reportMenuConfig["*"]) && sizeof($reportMenuConfig["*"]) > 0) {
                foreach ($reportMenuConfig["*"] as $kode) {
                    if (isset($availMenuReportConfig[$kode])) {
                        $reportMenus[$kode] = array(
                            "label" => $availMenuReportConfig[$kode]['label'],
                            "icon" => $availMenuReportConfig[$kode]['icon'],
                            "target" => $availMenuReportConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }

    $historyMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            // arrPrint($reportMenuConfig[$gID]);
            if (isset($historyMenuConfig[$gID]) && sizeof($historyMenuConfig[$gID]) > 0) {
                foreach ($historyMenuConfig[$gID] as $kode) {
                    if (isset($availMenuHistoryConfig[$kode])) {
                        $historyMenus[$kode] = array(
                            "label" => $availMenuHistoryConfig[$kode]['label'],
                            "icon" => $availMenuHistoryConfig[$kode]['icon'],
                            "target" => $availMenuHistoryConfig[$kode]['target'],
                        );
                    }
                }
            }

        }
    }

    //========================================================================//
    $mutasiMenus = array();
    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($menuMutasiRekeningConfig[$gID]) && sizeof($menuMutasiRekeningConfig[$gID]) > 0) {
                foreach ($menuMutasiRekeningConfig[$gID] as $kode) {
                    if (isset($availMenuMutasiRekeningConfig[$kode])) {
                        $mutasiMenus[$kode] = array(
                            "label" => $availMenuMutasiRekeningConfig[$kode]['label'],
                            "icon" => $availMenuMutasiRekeningConfig[$kode]['icon'],
                            "target" => $availMenuMutasiRekeningConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($menuMutasiRekeningConfig["*"]) && sizeof($menuMutasiRekeningConfig["*"]) > 0) {
                foreach ($menuMutasiRekeningConfig["*"] as $kode) {
                    if (isset($availMenuMutasiRekeningConfig[$kode])) {
                        $mutasiMenus[$kode] = array(
                            "label" => $availMenuMutasiRekeningConfig[$kode]['label'],
                            "icon" => $availMenuMutasiRekeningConfig[$kode]['icon'],
                            "target" => $availMenuMutasiRekeningConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }
    //arrPrint($mutasiMenus);

    $cPosition = $ci->uri->segment(1);
    $last = $ci->uri->total_segments();
    $subPosition = $ci->uri->segment($last);
    $cPosition_f = strtolower($cPosition);
    if ((int)$subPosition > 0 & (int)$subPosition - (int)$subPosition === 0 & $cPosition !== 'Transaksi') {
        $subPosition = $ci->uri->segment($last - 1);
    }
    $menuOpenActive = array();
    switch ($cPosition_f) {
        case "historyreport":
        case "spread":
        case "stok":
        case "ledger":
        case "neraca":
        case "overdue_releaser":
        case "katalog":
            $menuOpenActive['others'] = "1";
            break;
        case "activityreport":
            $menuOpenActive['activityreport'] = "1";
            break;
        case "historyreport":
            $menuOpenActive['historyreport'] = "1";
            break;
        case "ledger":
            $menuOpenActive['mutasirekening'] = "1";
            break;
        case "tools":
            $menuOpenActive['tools'] = "1";
            break;
        case "transaksi":
            if (isset($_GET['gr'])) {
                $menuOpenActive['transaksi'] = "1";
            }
            else {
                $menuOpenActive[$cPosition_f] = "1";
            }
            break;
        case "data":
            if (isset($_GET['md'])) {
                $menuOpenActive['data'] = "1";
            }
            else {
                $menuOpenActive[$cPosition_f] = "1";
            }
            break;
        default:

            //jika menu module error, list di sini segment satu nya url, karena sejatinya, module tetap transaksi pada segment satu nya
            switch ($cPosition_f) {
                case "kas":
                    if (isset($_GET['gr'])) {
                        $menuOpenActive['transaksi'] = "1";
                    }
                    else {
                        $menuOpenActive[$cPosition_f] = "1";
                    }
                    break;
                case "distribusi":
                    if (isset($_GET['gr'])) {
                        $menuOpenActive['transaksi'] = "1";
                    }
                    else {
                        $menuOpenActive[$cPosition_f] = "1";
                    }
                    break;
                case "pembayaran":
                    if (isset($_GET['gr'])) {
                        $menuOpenActive['transaksi'] = "1";
                    }
                    else {
                        $menuOpenActive[$cPosition_f] = "1";
                    }
                    break;
                default:
                    $menuOpenActive[$cPosition_f] = "1";
                    break;
            }
            break;
    }

    if (sizeof($settingMenus) > 0) {
        $menu = "settings";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $label_f = strtolower($menu);
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-muted text-uppercase'><i class='fa $fa_i'></i> <span class='text-uppercase'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        foreach ($settingMenus as $key => $label) {
            $label_f = strtolower($label);
            $fullMdlName = "Mdl" . $key;
            $customTarget = isset($settingConfig[$fullMdlName]['target']) ? $settingConfig[$fullMdlName]['target'] : (isset($settingConfig[$key]['target']) ? $settingConfig[$key]['target'] : "");
            $customIcon = isset($settingConfig[$fullMdlName]['icon']) ? $settingConfig[$fullMdlName]['icon'] : (isset($settingConfig[$key]['icon']) ? $settingConfig[$key]['icon'] : "");
            $targetUrl = !empty($customTarget) ? (strpos($customTarget, 'http') === 0 ? $customTarget : base_url() . $customTarget) : base_url() . "data/view/$key";
            $fa_i = !empty($customIcon) ? $customIcon : (array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle");
            $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
            $subMenuArrowActive = $subPosition == $jenis ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
            $strMenuLeft .= "<a href='" . $targetUrl . "'><i class='fa $fa_i'></i> <span>$label $subMenuArrowActive</span></a>";
            $strMenuLeft .= "</li>";
        }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }

    /* ------------------------------
     * ---- DATA - GUI ----
     * ------------------------------*/
    if (sizeof($dataMenus) > 0) {
        // $menu = "data";
        // $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        // $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        // $label_f = strtolower($label);
        // $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        // $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
        //         <a href='#' class='text-muted text-uppercase'><i class='fa $fa_i'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
        //         <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
        //         </a>";
        // $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        // foreach ($dataMenus as $key => $arrData) {
        //     $label = strtolower($arrData['label']);
        //     $dataLabels[$key] = $label;
        // }
        // asort($dataLabels);
        // // arrPrint($dataLabels);
        // foreach ($dataLabels as $key => $label) {
        //     // foreach ($dataMenus as $key => $arrData) {
        //     $arrData = $dataMenus[$key];
        //
        //     $label = $arrData['label'];
        //     $badge = $arrData['badge'];
        //     $label_f = strtolower($label);
        //     $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
        //     $subMenuArrowActive = $subPosition == $key ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
        //     $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle";
        //
        //     $keyNew = $key == "DataHistory" ? "viewHistories" : "viewdt";
        //     $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
        //     $strMenuLeft .= "<a href='" . base_url() . "data/$keyNew/$key'>$badge <i class='fa $fa_i'></i> $label_f $subMenuArrowActive</a>";
        //     $strMenuLeft .= "</li>";
        //
        // }
        // $strMenuLeft .= "</ul>";
        // $strMenuLeft .= "</li>";
        // $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";

        // -------------------- -------DATA BARU-----------------------
        $menu = "data";
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $label_f = strtolower($label);
        $topGr = $ci->uri->segment(3);
        $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
        $reqgroup = isset($_GET['gr']) ? base64_decode($req_group) : "";
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-white text-uppercase'><i class='fa $fa_i'></i> 
                    <span class='text-white'>$menu</span>
                    <i class='badge badge-info' id='mn_$menu'></i> 
                    <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center pull-left blink'></i>
                    <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";

        $menu_mode = "php";

        foreach ($gData_ui as $dKey => $dIitems) {

            $label = $dIitems['label'];
            $badge = !empty($dIitems['icon']) ? $dIitems['icon'] : "fa-circle";
            $label_f = strtolower($label);

            $glabel = "";
            $glabel_sr = str_replace("=", "", base64_encode($dKey));
            $newIndex = reset($dataGrjenis[$dKey]); // mengambil array pertama

            $subMenuActive = "";
            $subMenuArrowActive = "";
            if ($dKey == $reqgroup) {
                $subMenuActive = "bg-primarys active text-bold";
                $subMenuArrowActive = "";
            }

            $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize'>";

            $label = "<i class='fa $badge text-orange'></i> <span class='text-white'>$label_f</span> <span id='caret_$newIndex' class='text-white pull-right'><i class='fa fa-caret-left'></i></span>";

            $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr&md=data";
            switch ($menu_mode) {
                case "php":
                    $newIndex_f = str_replace("Mdl", "", $newIndex);
                    if (isset($overWriteData_menu[$newIndex_f])) {
                        $index_data = $overWriteData_menu[$newIndex_f];
                    }
                    else {
                        $index_data = "viewdt";
                    }
                    $strMenuLeft .= "<a class='$subMenuActive' id='$newIndex' href=\"javascript:void(0);\" hrsef='" . base_url() . "statik/Data/$index_data/$newIndex_f?gr=$glabel_sr&md=data' onmousseovers=\"$('#menu_top').load('$url_loader');\">
                                        <div>$label</div> $subMenuArrowActive
                                    <span class='badge badge-sky' id='left_$glabel'></span>
                                        <div name='tempat_sub_ul' style='display:none;' class='tempat_sub_ul_$glabel_sr'></div>
                                    </a>";

                    $strMenuLeft .= "
                    <script>
                        $('#$newIndex').on('click', delay_v2( function(){
                            if( $('.tempat_sub_ul_$glabel_sr').html().length > 0 ){
                                $('.tempat_sub_ul_$glabel_sr').fadeOut();
                                $('.tempat_sub_ul_$glabel_sr').html('');
                            }
                            else{

                                $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                                $('.tempat_sub_ul_$glabel_sr').fadeIn();
                            }
                        }, 100) );
                        if('$dKey'=='$reqgroup'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                            $('#caret_$newIndex').html(\"<i class='fa fa-caret-down text-yellow'></i>\");
                        }

                    </script>";

                    break;
                case "js":
                    // $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr";
                    $strMenuLeft .= " <a href='javascript:void(0);' onclicks=\"$('#menu_top').load('$url_loader');\">$label$subMenuArrowActive
                                     <span class='badge badge-sky' id='left_$glabel'></span>
                                     </a>";
                    break;
            }

            $strMenuLeft .= "</li>";
        }

        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 200);</script>";
    }
    // -------------------------------------------- END OF DATA GUI ------------------------
    /*
     * menu manufacturing
     */
    if (sizeof($menuManufacturing) > 0) {
        $subFase = produksiFaseMenu();
        $menu = "manufaktur";
        //         arrPrint($menuManufacturing);
        // cekkuning($subFase);
        // arrPrint($ci->session->login);
        $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
        $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        $label_f = strtolower($menu);
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-tags";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                <a href='#' class='text-muted text-uppercase'><i class='fa $fa_i'></i> <span class='text-uppercase'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
                </a>";
        $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
        $loginType = $ci->session->login['jenis'];
        $eID = $ci->session->login['id'];
        $cID = $ci->session->login['cabang_id'];
        $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
        // arrPrint($membership);
        //        arrPrintKuning($menuManufacturing);
        $key = "cek";
        foreach ($menuManufacturing as $cid => $cData) {
            foreach ($cData as $PID => $dataProduk) {
                $label_f = strtolower($dataProduk["nama"]);
                $jenis = $dataProduk["jenis"];
                $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle";
                $availFaseTmp = array();
                if (isset($subFase[$PID])) {
                    $availFaseTmp[$PID] = array_keys($subFase[$PID]);
                    // $availFaseTmp[$PID] = $subFase[$PID];
                    // arrPrint($subFase[$PID]);
                }
                // arrPrint($availFase);
                $availFase = sizeof($availFaseTmp) > 0 ? base64_encode($availFaseTmp) : "";
                $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
                $subMenuArrowActive = $subPosition == $jenis ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
                $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
                $strMenuLeft .= "<a href='" . base_url() . "produksiproses/Transaksi/index/$jenis/$PID?availfase=$availFase'><i class='fa $fa_i'></i> <span>$label_f $subMenuArrowActive</span></a>";
                $strMenuLeft .= "</li>";
            }
            // arrPrint($cData);
        }
        // foreach ($menuManufacturing as $key => $label) {
        //     $label_f = strtolower($label);
        //     $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-circle";
        //     $subMenuActive = $subPosition == $key ? "bg-primary active text-bold" : "";
        //     $subMenuArrowActive = $subPosition == $jenis ? "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>" : "";
        //     $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
        //     $strMenuLeft .= "<a href='" . base_url() . "data/view/$key'><i class='fa $fa_i'></i> <span>$label $subMenuArrowActive</span></a>";
        //     $strMenuLeft .= "</li>";
        // }
        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }

    /* --------------------------------
     * $menu_mode = " .."; // diisi string php atau js
     * "php" == klik langsung lari ke halaman pertama dr mu dalam group
     * "js" == diem saja disitu yg berubah hanya menu top saja
     * ------------------------------------*/
    $menu_mode = "php";
    if (sizeof($transGrLabels) > 0) {

        $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
        $reqgroup = isset($_GET['gr']) ? base64_decode($req_group) : "";
        $topGr = isset($_GET['topGr']) ? $_GET['topGr'] : "";
        $menu = "transaksi";
        $classMenuOpen = "";
        $styleMenuOpen = "";
        if (isset($_GET['gr'])) {
            $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
            $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
        }

        $label_f = strtolower($menu);
        $fa_i = array_key_exists($label_f, $arrIcon) ? $arrIcon[$label_f] : "fa-exchange";
        $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
                    <a href='#' class='text-white text-uppercase'><i class='fa $fa_i'></i> <span class='text-white'>$menu</span><i class='badge badge-info' id='mn_$menu'></i> <i id='_mnpr_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
                        <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>    
                    </a>";
        $strMenuLeft .= "<ul id='_$menu' class=\"treeview-menu\" $styleMenuOpen>";

        foreach ($transGrLabels as $jenis => $grLabels) {

            $label = $grLabels['label'];
            $label_f = strtolower($label);
            $glabel = $grLabels['group'];
            $glabel_sr = str_replace("=", "", base64_encode($glabel));
            $newIndex = reset($transGrjenis[$glabel]); // mengambil array pertama

            $subMenuActive = "";
            $subMenuArrowActive = "";

            $perGroup = isset($heTransaksiGroup_ui[$glabel]['heTransaksi_ui']) ? $heTransaksiGroup_ui[$glabel]['heTransaksi_ui'] : array();

            if ($glabel == $reqgroup) {
                $subMenuActive = "text-red";
                $subMenuArrowActive = "<span class=\"pull-right-container\"><i class=\"fa fa-arrow-right pull-right text-lime blink\"></i></span>";
                $subMenuArrowActive = "";
            }

            $strMenuLeft .= "<li style='atext-shadow: 1px -1px #0a0a0a;' class='text-capitalize'>";
            $url_loader = base_url() . "Loader/menuTop/$newIndex?gr=$glabel_sr&topGr=$topGr&md=transaksi";
            switch ($menu_mode) {
                case "php":
                    $strMenuLeft .= " <a class='$subMenuActive link_$newIndex' id='$newIndex' href=\"javascript:void(0);\">
                                    <span class='overlay hidden'><i class='fa fa-refresh fa-spin'></i></span>
                                      <span>$label</span> $subMenuArrowActive 
                                      <span class='badge badge-sky' id='left_$glabel'></span>
                                      <div name='tempat_sub_ul' style='display:none;' class='tempat_sub_ul_$glabel_sr'></div>
                                      </a>
                                    ";
                    $strMenuLeft .= "
                    <script>
                        $('.link_$newIndex').on('click', delay_v2( function(){
                        
//                            console.log($('div.tempat_sub_ul_$glabel_sr', $(this)));
                            
                            if( $('div.tempat_sub_ul_$glabel_sr', $(this)).length > 0 && $('div.tempat_sub_ul_$glabel_sr', $(this)).html().length > 0 ){
                                $('div.tempat_sub_ul_$glabel_sr', $(this)).fadeOut();
                                $('div.tempat_sub_ul_$glabel_sr', $(this)).html('');
                            }
                            else{
                                $('span.overlay', $(this)).removeClass('hidden')
                                $('div.tempat_sub_ul_$glabel_sr', $(this)).load('$url_loader&dropdown=1&topGr=$topGr');
                                $('div.tempat_sub_ul_$glabel_sr', $(this)).fadeIn();
                            }
                        }, 100) );
                        if('$dKey'=='$reqgroup'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                        }
                        if('$glabel_sr'=='$req_group'){
                            $('.tempat_sub_ul_$glabel_sr').load('$url_loader&dropdown=1&topGr=$topGr');
                            $('.tempat_sub_ul_$glabel_sr').fadeIn();
                        }

                    </script>";
                    break;
                case "js":
                    $strMenuLeft .= " <a href='javascript:void(0);' onclicks=\"$('#menu_top').load('$url_loader');\">$label$subMenuArrowActive
                                     <span class='badge badge-sky' id='left_$glabel'></span>
                                     </a>";
                    break;
            }

            $strMenuLeft .= "";
            $strMenuLeft .= "</li>";

            $strMenuLeft .= "\n<script>

                var perGroup = " . callMenuTopJson($glabel_sr) . ";
                var perGroupJenis = [];
                var perGroupTotal = 0;
                jQuery.each(perGroup, function(i, d){
                    perGroupJenis[d] = null != localStorage.getItem(d) ? localStorage.getItem(d) : 0
                    perGroupTotal += parseFloat(perGroupJenis[d]);
                });
                if( perGroupTotal > 0 ) {  $('#left_$glabel').html(perGroupTotal)  } else {  $('left_$glabel').html('')  }

            </script>";
        }

        $strMenuLeft .= "</ul>";
        $strMenuLeft .= "</li>";
        $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    }
    // -------------------------------------------- END OF TRANSAKASI GUI ------------------------

    //=======================================================================
    // if (sizeof($mutasiMenus) > 0) {
    //     $menu = "mutasirekening";
    //     $menuLabel = "mutasi rekening";
    //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
    //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
    //     $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
    //             <a href='#' class='text-white text-uppercase'><i class='fa fa-server'></i> <span class='text-white'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
    //             <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
    //             </a>";
    //     $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
    //     $mutasiLabels = array();
    //     foreach ($mutasiMenus as $key => $mSpec) {
    //         $mutasiLabels[$key] = strtolower($mSpec['label']);
    //     }
    //     //        asort($mutasiLabels);
    //     //        arrPrint($mutasiLabels);
    //
    //     foreach ($mutasiLabels as $key => $label) {
    //         $mSpec = $mutasiMenus[$key];
    //         // foreach ($otherMenus as $key => $mSpec) {
    //
    //         $explodedTarget = explode('/', $mSpec['target']);
    //         $numExpoded = sizeof($explodedTarget);
    //         $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
    //         $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
    //         $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
    //         $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
    //         $strMenuLeft .= "</li>";
    //     }
    //     $strMenuLeft .= "</ul>";
    //     $strMenuLeft .= "</li>";
    //     $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    //
    // }
    // if (sizeof($toolMenus) > 0) {
    //
    //     $menu = "tools";
    //     $menuLabel = "tools";
    //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
    //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
    //     $strMenuLeft .= "<li class='treeview$classMenuOpen'>
    //             <a href='#' class='text-white text-uppercase'><i class='fa fa-server'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
    //             <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
    //             </a>";
    //     $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
    //     $toolLabels = array();
    //     foreach ($toolMenus as $key => $mSpec) {
    //         $toolLabels[$key] = strtolower($mSpec['label']);
    //     }
    //
    //     foreach ($toolLabels as $key => $label) {
    //         $mSpec = $toolMenus[$key];
    //         // foreach ($otherMenus as $key => $mSpec) {
    //
    //         $explodedTarget = explode('/', $mSpec['target']);
    //         $numExpoded = sizeof($explodedTarget);
    //         $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
    //         $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
    //         $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
    //         $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
    //         $strMenuLeft .= "</li>";
    //     }
    //     $strMenuLeft .= "</ul>";
    //     $strMenuLeft .= "</li>";
    //     $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    //
    // }
    //
    // if (sizeof($otherMenus) > 0) {
    //     $menu = "others";
    //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
    //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
    //     $strMenuLeft .= "<li class='treeview$classMenuOpen'>
    //             <a href='#' class='text-muted text-uppercase'><i class='fa fa-server'></i> <span class='text-muted'>$menu</span> <i id='_$menu' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>
    //             <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
    //             </a>";
    //     $strMenuLeft .= "<ul id='_mnpr_$menu' class='treeview-menu' $styleMenuOpen>";
    //     $otherLabels = array();
    //     foreach ($otherMenus as $key => $mSpec) {
    //         $otherLabels[$key] = strtolower($mSpec['label']);
    //     }
    //     asort($otherLabels);
    //     // arrPrint($otherLabels);
    //
    //     foreach ($otherLabels as $key => $label) {
    //         $mSpec = $otherMenus[$key];
    //         // foreach ($otherMenus as $key => $mSpec) {
    //
    //         $explodedTarget = explode('/', $mSpec['target']);
    //         $numExpoded = sizeof($explodedTarget);
    //         $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "bg-primary active text-bold" : "";
    //         $subMenuArrowActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
    //         $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
    //         $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='" . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
    //         $strMenuLeft .= "</li>";
    //     }
    //     $strMenuLeft .= "</ul>";
    //     $strMenuLeft .= "</li>";
    //     $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    //
    // }
    // if (sizeof($reportMenus) > 0) {
    //     $menu = "activityreport";
    //     // $menuLabel = "management reports";
    //     $menuLabel = "reportings";
    //     $aliasPosisi = array(
    //         "viewpresalesall" => "prepenjualanAll",
    //         "viewsalesall"    => "penjualanAll",
    //         "bl"              => "invoicing",
    //     );
    //     $subPosition_s = strtolower(trim($ci->uri->segment($last)));
    //     $subPosition = isset($aliasPosisi[$subPosition_s]) ? $aliasPosisi[$subPosition_s] : "";
    //
    //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
    //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
    //     $strMenuLeft .= "<li class=\"treeview$classMenuOpen\">
    //             <a href='#' class='text-muted text-uppercase'><i class='fa fa-line-chart'></i> <span class='text-muted'>$menuLabel</span> <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center'></i>
    //             <span class=\"pull-right-container\"><i class=\"fa fa-angle-left pull-right\"></i></span>
    //             </a>";
    //     $strMenuLeft .= "<ul id='_mnpr_$menu' class=\"treeview-menu\" $styleMenuOpen>";
    //
    //     // arrPrint($subPosition_s);
    //     // arrPrint($reportMenus);
    //     foreach ($reportMenus as $key => $mSpec) {
    //         // $explodedTarget = explode('/', $mSpec['label']);
    //         // $numExpoded = sizeof($explodedTarget);
    //         // cekHijau($key ." ". $ci->uri->segment(2) . " " .$subPosition);
    //
    //         $subMenuActive = trim($key) == $subPosition ? "bg-primary active text-bold" : "";
    //         $subMenuArrowActive = trim($key) == $subPosition ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
    //         // cekHijau($key . " *** " . $subPosition);
    //
    //         $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
    //         $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='fa " . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
    //         $strMenuLeft .= "</li>";
    //     }
    //     $strMenuLeft .= "</ul>";
    //     $strMenuLeft .= "</li>";
    //     $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    //
    // }
    // if (sizeof($historyMenus) > 0) {
    //     $menu = "historyreport";
    //     $menuLabel = "historical reports";
    //     $subPosition = strtolower(trim($ci->uri->segment($last)));
    //
    //     $classMenuOpen = isset($menuOpenActive[$menu]) ? " menu-open active" : "";
    //     $styleMenuOpen = isset($menuOpenActive[$menu]) ? "style='display: block;'" : "style='display: none;'";
    //     $strMenuLeft .= "<li class='treeview$classMenuOpen'>
    //             <a href='#' class='text-muted text-uppercase'><i class='fa fa-history'></i> <span class='text-muted'>$menuLabel</span> <i id='_$menu' style='zoom:50%;display:none;' class='fa fa-circle text-red text-center'></i>
    //             <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
    //             </a>";
    //     $strMenuLeft .= "<ul id='_mnpr_$menu' class='treeview-menu' $styleMenuOpen>";
    //
    //     // arrPrint($subPosition);
    //     // arrPrint($reportMenus);
    //     foreach ($historyMenus as $key => $mSpec) {
    //         // $explodedTarget = explode('/', $mSpec['label']);
    //         // $numExpoded = sizeof($explodedTarget);
    //         // cekHijau($key ." ". $ci->uri->segment(2) . " " .$subPosition);
    //
    //         $subMenuActive = trim($key) == $subPosition ? "bg-primary active text-bold" : "";
    //         $subMenuArrowActive = trim($key) == $subPosition ? "<span class='pull-right-container'><i class='fa fa-arrow-right pull-right text-lime blink'></i></span>" : "";
    //         // cekHijau($key . " *** " . $subPosition);
    //
    //         $strMenuLeft .= "<li style='text-shadow: 1px -1px #0a0a0a;' class='text-capitalize $subMenuActive'>";
    //         $strMenuLeft .= "<a href='" . base_url() . $mSpec['target'] . "'><i class='fa " . $mSpec['icon'] . "'></i> " . $mSpec['label'] . " $subMenuArrowActive</a>";
    //         $strMenuLeft .= "</li>";
    //     }
    //     $strMenuLeft .= "</ul>";
    //     $strMenuLeft .= "</li>";
    //     $strMenuLeft .= "<script>setInterval( function(){ var $menu = $('.badge.bg-red.text-white',$('#_mnpr_$menu')[0]).length; if($menu>0){ $('#_$menu').fadeIn() } }, 1000);</script>";
    //
    // }

    /* ----------------------------------------------
     * grouping menu
     * ----------------------------------------------*/
    //     arrPrint($groupingMenus);
    foreach ($groupingMenus as $groupKey => $groupingMenu) {
        $groupCbNama = isset($groupMenuConfig[$groupKey]['tanpaCbNama']) ? "" : my_cabang_nama();
        $groupLabel = $groupMenuConfig[$groupKey]['label'];
        $groupIcon = $groupMenuConfig[$groupKey]['icon'];
        $gLink = base64_encode("$groupKey-$groupKey");

        $jmlAnakanMenu = sizeof($groupingMenu);

        $pLinkTmp = isset($_GET['gr']) ? explode("-", base64_decode((trim($_GET['gr'])))) : array();
        $styleMenuGroupOn = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='background:#ae42f147;'" : "";


        if (isset($groupMenuConfig[$groupKey]['target']) || $jmlAnakanMenu == 100) {
            switch ($jmlAnakanMenu) {
                case "1":

                    foreach ($groupingMenu as $menuKey => $menuParams) {
                        $pLink = base64_encode("$groupKey-$menuKey");
                        // $groupLabel = $menuParams['label'];
                        // $groupIcon = $menuParams['icon'];
                        $menuTarget = $menuParams['target'];

                        $groupTarget = base_url() . $menuTarget . "?gr=$pLink";
                    }
                    break;
                default:
                    // cekLime();
                    $groupTarget = base_url() . $groupMenuConfig[$groupKey]['target'] . "?gr=$gLink";
                    break;
            }

            $classMenuOpen = "";
            $styleMenuOpen = "";
            $styleMenuOpenLabel = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='color:#00b0ff;'" : "";
            // $styleMenuOpenLabel = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='background:red;padding: 5px;'" : "";
            $menuAnakan = false;
            $angelIcon = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "fa-angle-right" : "";
        }
        else {
            $groupTarget = "#";
            $classMenuOpen = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "active" : "";
            $styleMenuOpen = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='display:block;'" : "";
            $styleMenuOpenLabel = "";
            // $styleMenuOpenLabel = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='color:red;padding: 5px;'" : "";
            // $styleMenuGroupOn = isset($_GET['gr']) && $pLinkTmp[0] == $groupKey ? "style='background:##ae42f147;'" : "";
            $menuAnakan = true;
            $angelIcon = "fa-angle-left";
        }


        $strMenuLeft .= "<li class='treeview $classMenuOpen' $styleMenuGroupOn>";
        $strMenuLeft .= "<a href='$groupTarget' class='text-muted text-uppercase'>";
        $strMenuLeft .= "<i class='$groupIcon'></i>";
        $strMenuLeft .= "<span class='text-white' $styleMenuOpenLabel>$groupLabel $groupCbNama</span>";
        $strMenuLeft .= "<i id='_$groupKey' style='zoom:50%;display:none;' class='pull-left fa fa-circle text-red text-center blink'></i>";
        $strMenuLeft .= "<span class='pull-right-container'>";
        $strMenuLeft .= "<i class='fa $angelIcon pull-right'></i>";
        $strMenuLeft .= "</span>";
        $strMenuLeft .= "</a>";

        if (isset($menuAnakan) && $menuAnakan === false) {
            $strMenuLeft .= "";
            /* --------------------------------------
             * group yg tidak punya anak menu
             * --------------------------------------*/
        }
        else {
            $strMenuLeft .= "<ul id='_mnpr_$groupKey' class='treeview-menu' $styleMenuOpen>";

            $availableMenus = callAvailableMenu_he_menu();
            $sortingMenu = array_intersect_key($availableMenus, $groupingMenu);
            // arrPrintHijau($groupingMenu);

            foreach ($sortingMenu as $menuKey => $menuParams) {
                $menuLabel = $menuParams['label'];
                $menuIcon = $menuParams['icon'];
                $menuTarget = $menuParams['target'];
                // arrPrintWebs($menuParams);

                if (isset($menuParams['target'])) {
                    $pLink = base64_encode("$groupKey-$menuKey");
                    $subMenuActive = isset($_GET['gr']) && isset($pLinkTmp[1]) && $pLinkTmp[1] == $menuKey ? "on" : "";
                    $aStyle = isset($_GET['gr']) && isset($pLinkTmp[1]) && $pLinkTmp[1] == $menuKey ? "style='color:white!important;padding:5px;'" : "";
                    $strMenuLeft .= "<li style='' class='text-capitalize '>";

//                    $strMenuLeft .= "<a  href='" . base_url() . $menuTarget . "?gr=$pLink'><i class='$menuIcon text-aqua'></i> <span $aStyle class='$subMenuActive'>$menuLabel</span></a>";
                    $strMenuLeft .= "<a  href='" . base_url() . $menuTarget . "'><i class='$menuIcon text-aqua'></i> <span $aStyle class='$subMenuActive'>$menuLabel</span></a>";

                    $strMenuLeft .= "</li>";
                }
                else {
                    $strMenuLeft .= "";
                }
            }
            $strMenuLeft .= "</ul>";
        }

        $strMenuLeft .= "</li>";
    }

    //region logout doang boss
    // https://demo.mayagrahakencana.com/tho-san/Login/authLogout
    $logout_l = base_url() . "auth/Login/authLogout";
    $strMenuLeft .= "<li class='treeview'>
                <a href='$logout_l' class='text-muted text-uppercase'><i class='fa fa-power-off'></i> <span class='text-muted'>Logout</span>
                <span class='pull-right-container'><i classs='fa fa-angle-left pull-right'></i></span>
                </a>";
    $strMenuLeft .= "</li>";
    //endregion

    $strMenuLeft .= "</ul>";

    return $strMenuLeft;
}

function callMenuTop()
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $jenis = $ci->uri->segment(3);
    // $trGroupJenis_e = (strlen($ci->uri->segment(4)) > 0) ? $ci->uri->segment(4) : "transaksi";
    $trGroupJenis_e = isset($_GET['md']) ? $_GET['md'] : "transaksi";
    // cekOrange($jenis);
    // $ci->load->model("Mdls/MdlAccessRight");
    // $ci->load->config("heMenu");
    $ci->load->helper("he_access_right");
    $ci->load->model("Mdls/MdlMother");
    $ci->load->model("Mdls/MdlMenuGroupUi");
    $gu = new MdlMenuGroupUi();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $heDataBehaviour = $ci->config->item('heDataBehaviour');
    $dataRelConfig = $ci->config->item('dataRelation');
    // $heTransaksiGroup_ui = (null != $ci->config->item("heTransaksiGroup_ui")) ? $ci->config->item("heTransaksiGroup_ui") : array();
    $heTransaksiGroup_uiDb = $gu->callGroupMenuTransaksiUi();
    $heTransaksiGroup_ui = $heTransaksiGroup_uiDb[$trGroupJenis_e];

    /*----------------------nginsert menu group ke transaksional------------*/
    $ci->load->config("heMenu");
    $grMenu = $ci->config->item('groupMenu');
    $grmenuLaporan = $grMenu['reporting']['availMenu'];
    // arrPrint($grmenuLaporan);
    // arrPrint($heTransaksiGroup_uiDb);
    $laporanToTransaksi = array();
    foreach ($grmenuLaporan as $keyGroupMenu => $menuParams) {
        // $keyTransaksi =
        if (isset($menuParams['transaksi'])) {
            foreach ($menuParams['transaksi'] as $jenisTr) {

                $laporanToTransaksi[$jenisTr][$keyGroupMenu] = $menuParams;
            }
        }
    }
    // arrPrintKuning($laporanToTransaksi);

    $req_group = isset($_GET['gr']) ? $_GET['gr'] : "";
    $top_req_group = isset($_GET['topGr']) ? $_GET['topGr'] : "";
    $reqgroup = isset($_GET['gr']) ? base64_decode($req_group) : "";
    // $reqgroup = isset($_GET['gr']) ? blobEncode($req_group) : "";
    $topreqgroup = isset($_GET['topGr']) ? $top_req_group : "";
    $eID = isset($ci->session->login['id']) ? $ci->session->login['id'] : 0;
    $membership = (isset($ci->session->login['membership']) && is_array($ci->session->login['membership'])) ? $ci->session->login['membership'] : array();

    // cekHere($reqgroup);
    //region custom access here
    // cekHere($eID);
    $dataAcc = alowedAccess($eID);
    // arrPrint($dataAcc);
    $allowedCustom = array();
    if (sizeof($dataAcc) > 0) {
        foreach ($dataAcc as $jn => $tempData) {
            foreach ($tempData as $step => $targetData) {
                $allowedCustom[$jn][] = $step;
            }
        }
    }
    //endregion

    // cekBiru("$jenis");
    // cekLime("membership " .__LINE__);
    // arrPrint($membership);
    // arrPrint($allowedCustom);
    // arrPrint($heTransaksiGroup_ui);

    $varReqGroupJs = "\n\n<script>";

    if (strlen($reqgroup) > 3) {
        // cekBiru($reqgroup . " $trGroupJenis_e");
        // arrPrint($heTransaksiGroup_ui[$reqgroup]);
        /* -------------------------------
         * --menu dari gruop menu yg diminta
         * --------------------*/
        $transGrjenis = array();
        foreach ($heTransaksiGroup_ui as $group => $gparams) {
            $def_index = isset($gparams['index']) ? $gparams['index'] : "none";
            $def_icon = isset($gparams['icon']) ? $gparams['icon'] : "fa-circle";
            $g_icon = "<i class='fa $def_icon'></i>";
            $transGrLabels[$def_index]['label'] = $g_icon . "&nbsp;" . strtolower($gparams['label']);
            $transGrLabels[$def_index]['group'] = $group;
            if ($group == $reqgroup) {
                // arrPrint($gparams);
                $transGrjenis[$group] = $gparams['heTransaksi_ui'];
            }
        }

        // cekHijau($group);
        // arrPrintWebs($transGrjenis);
        // arrPrint($membership);
        // arrPrint($heDataBehaviour);
        // arrPrintWebs($heTransaksi_ui);
        $var = "";
        $uis_now = array();
        $groupjenis = isset($transGrjenis[$reqgroup]) && sizeof($transGrjenis[$reqgroup]) > 0 ? $transGrjenis[$reqgroup] : array();

        // arrPrintWebs($uis_now);
        // arrPrint($allowedCustom);
        // cekOrange("$jenis");
        $customAkesData = availMenuData($eID);
        if (sizeof($customAkesData) > 0) {
            $dataMemberAllowed = $customAkesData["fungsi"];
            foreach ($heDataBehaviour as $mdlNama => $behaviorItem_0) {
                if (isset($dataMemberAllowed[$mdlNama])) {
                    foreach ($dataMemberAllowed[$mdlNama] as $fs => $fsMember) {
                        // unset( $heDataBehaviour[$mdlNama][$fs]);
                        $heDataBehaviour[$mdlNama][$fs] = $fsMember;
                    }
                }
            }
        }
        $allowedjenis = array();
        switch ($trGroupJenis_e) {
            case "data":
                // $base_link = "Data/viewdt/";
                // $base_links[$jenis] = "Data/viewdt/";
                $laporanTersedia = $grmenuLaporan[$trGroupJenis_e];

                $jenis = "Mdl" . $jenis;
                $dataExcludes = array();
                $dataExcludes_0 = array();
                if (sizeof($dataRelConfig) > 0) {
                    foreach ($dataRelConfig as $srcMdl => $sSpec) {
                        foreach ($sSpec as $xmdlName => $xSpec) {
                            $dataExcludes_0[$xmdlName] = $xmdlName;
                        }
                    }
                }
                $dataExcludes = array_keys($dataExcludes_0);
                // arrPrint($dataExcludes);
                $arrFungsiGroups = array(
                    "creators",
                    // "creatorAdmins",
                    "viewers",
                    // "updaters",
                    // "updaterAdmins",
                    // "deleters",
                    // "deleterAdmins",
                    // "historyViewers",
                );

                $base_links = array();
                foreach ($heDataBehaviour as $mdlNama => $behaviorItems) {
                    $base_links[$mdlNama] = "statik/Data/viewdt/";
                    // $base_links[$mdlNama] = "Data/view/";
                    $memberAllowes = array();
                    // cekOrange($mdlNama);

                    foreach ($arrFungsiGroups as $fungsiGroup) {

                        $arrFungsies = $behaviorItems[$fungsiGroup];
                        foreach ($arrFungsies as $arrFungsy) {
                            // arrPrint($arrFungsy);
                            if (in_array($arrFungsy, $membership)) {
                                $memberAllowes[$arrFungsy] = $arrFungsy;
                            }
                        }
                        // cekLime($fungsiGroup);
                        // arrPrintWebs($arrFungsies);
                    }
                    // arrPrint($memberAllowes);
                    if (sizeof($memberAllowes) > 0) {
                        // arrPrint(array_filter($memberAllowes));
                    }
                    // $allowedjenis = array_keys($memberAllowes);
                    // arrPrint($allowedjenis);
                    // echo "<hr>";
                    // $uis_now[$mdlNama] = $allowedjenis;
                    $dataMemberAllowes[$mdlNama] = $memberAllowes;
                }
                // cekLime("allowes");
                // arrPrintWebs(array_filter($dataMemberAllowes));

                $dataMemberAllowes_0 = array_keys(array_filter($dataMemberAllowes));
                $allowedjenis = array_diff($dataMemberAllowes_0, $dataExcludes);
                $new_ui = array_intersect($groupjenis, $allowedjenis);

                foreach ($new_ui as $transGrjeni) {
                    // arrPrint($transGrjeni);
                    $uis_now[$transGrjeni] = $heDataBehaviour[$transGrjeni];

                }

                // cekLime("ui");
                // arrPrintWebs($new_ui);
                // cekLime("group");
                // arrPrint($groupjenis);
                // cekLime("allow");
                // arrPrintWebs($allowedjenis);
                // matiHere($trGroupJenis_e);

                break;
            case "transaksi":
                /* -------------------------
                * penyambung menu untuk moduler
                * --------------------------------*/
                $base_links = array();
                foreach ($heTransaksi_ui as $mJenis => $jSpec) {
                    // arrPrint($jSpec);
                    if (isset($jSpec['modul'])) {
                        $strModul = strtolower($jSpec['modul']);
                        $ctrModul = ucfirst($jSpec['modul']);
                        // $ctrModul = ucfirst('Create');
                        //                        $base_links[$mJenis] = $strModul . "/$ctrModul/index/";
                        $base_links[$mJenis] = $strModul . "/Transaksi/index/";
                    }
                    else {
                        $base_links[$mJenis] = "Transaksi/index/";
                    }
                }
                // $base_link = "Transaksi/index/";
                if (isset($allowedCustom) && sizeof($allowedCustom) > 0) {
                    /* -------------------------------------
                     * mendapatkan jenis-transaksi accessRight (hak akses custom)
                     * ------------------------------*/
                    foreach ($allowedCustom as $cjenis => $top_steps) {
                        $allowedjenis[] = $cjenis;
                    }
                    // matiHere(__LINE__);
                }
                else {
                    /* -------------------------------------
                     * mendapatkan jenis-transaksi dari userGroup
                     * ------------------------------*/
                    foreach ($heTransaksi_ui as $mJenis => $jSpec) {
                        // arrPrintWebs($jSpec);
                        foreach ($jSpec['steps'] as $step) {
                            $userGroup = $step['userGroup'];
                            // cekHere($userGroup);
                            // $jenisMembers[$mJenis][] = $userGroup;
                            // $memberJenies[$userGroup][] = $mJenis;

                            if (in_array($userGroup, $membership)) {
                                $memberAllowes[$mJenis] = $mJenis;
                            }
                        }
                    }

                    $allowedjenis = array_keys($memberAllowes);
                }

                $new_ui = array_intersect($groupjenis, $allowedjenis);

                // cekLime("ui");
                // arrPrintWebs($new_ui);
                // cekLime("group");
                // arrPrint($groupjenis);
                // cekLime("allow");
                // arrPrintWebs($allowedjenis);
                // arrPrintWebs($transMenus);
                // arrPrint($uis_now);

                // foreach ($groupjenis as $transGrjeni) {
                // arrPrintHijau($laporanToTransaksi);
                // arrPrint($new_ui);
                $laporanTersedia = array();
                foreach ($new_ui as $transGrjeni) {
                    // cekHijau($transGrjeni);
                    // if (isset($laporanToTransaksi[$transGrjeni])) {
                    $laporanTersedia += isset($laporanToTransaksi[$transGrjeni]) ? $laporanToTransaksi[$transGrjeni] : array();
                    // $laporanTersedia +=  $laporanToTransaksi[$transGrjeni];
                    // }
                    // arrPrintWebs($laporanTersedia);

                    $uis_now[$transGrjeni] = $heTransaksi_ui[$transGrjeni];

                }

                // arrPrintPink($laporanTersedia);
                // arrPrintKuning($uis_now);
                $uis_now = $uis_now + $laporanTersedia;
                // arrPrintHijau($uis_now);
                break;
        }

        // cekLime("now");

        //         arrPrintWebs($base_links);
        $i = 0;
        $len = count($uis_now);


        //========= SHORTING =========
        //========= BY CHEPY =========
        //        function cmp($a, $b){
        //            if($a['label'] == $b['label']){
        //                return 0;
        //            }
        //            return ($a['label'] < $b['label']) ? -1 : 1;
        //        }
        //        usort($uis_now, "cmp");
        //============================
        //============================
        //        arrPrint($uis_now);

        foreach ($uis_now as $ui_jenis => $item_uis) {
            // cekBiru($ui_jenis);
            $ui_icon = isset($item_uis['icon']) ? $item_uis['icon'] : "fa-star";
            $ui_label = isset($item_uis['label']) ? $item_uis['label'] : "none";
            $ui_deskripsi = isset($item_uis['deskripsi']) ? $item_uis['deskripsi'] : $ui_label;
            $ui_dropdown = isset($_GET['dropdown']) ? $_GET['dropdown'] : "";
            $ui_jenis_f = str_replace("Mdl", "", $ui_jenis);
            $base_link = isset($base_links[$ui_jenis]) ? $base_links[$ui_jenis] : "";

            if (isset($item_uis["target"])) {
                $ui_label = isset($item_uis['label']) ? "Lap. " . $item_uis['label'] : "none";
                $ui_link = base_url() . $item_uis["target"] . "/$ui_jenis_f?gr=$req_group&topGr=$ui_jenis&md=$trGroupJenis_e";
            }
            else {
                $ui_link = base_url() . $base_link . "$ui_jenis_f?gr=$req_group&topGr=$ui_jenis&md=$trGroupJenis_e";
            }

            if ($i == 0) { //first
                if ($len > 1) {
                    $penyambung = "├";
                }
                else {
                    $penyambung = "└";
                }
            }
            else {
                if ($i == $len - 1) { //last
                    $penyambung = "└";
                }
                else {
                    $penyambung = "├"; // middle
                }
            }
            $i++;

            if ($ui_dropdown) {
                $ui_jenis = str_replace('Mdl', '', $ui_jenis);
                $ui_active = $ui_jenis == $topreqgroup ? "active on" : "";
                $ui_text_active = $ui_jenis == $topreqgroup ? "class='text-white'" : "";

                $var .= "<li class='$ui_jenis $topreqgroup dropdown messages-menu text-capitalize $ui_active'  style='margin-left: -27px;'>";
                $var .= "<a href='$ui_link' $ui_text_active>
                                    <span style='color: white;font-size:20px'>$penyambung&nbsp;</span>
                                    <i class='text-white fa $ui_icon'></i> 
                                    <span style='padding: 0 5px 3px;background: linear-gradient(to left, #4d626a, #2c3b41);border-radius: 3px;'>                                                                        
                                    <span class='label label-danger' style='padding: 0px 5px !important; margin-right: 3px;' id='kiri_$ui_jenis'></span>
                                    <span title='-" . strtoupper($ui_deskripsi) . "-' data-toggle='tooltip' class='text-white'>$ui_label</span>
                                    </span>
                                </a>";
                $var .= "</li>";
                $varReqGroupJs .= "\n var trb$ui_jenis =  null!=localStorage.getItem('" . $ui_jenis . "') ? localStorage.getItem('" . $ui_jenis . "') : 0 ;";
                $varReqGroupJs .= "\n console.log('trb$ui_jenis: ' + trb$ui_jenis);";
                $varReqGroupJs .= "\n if( trb$ui_jenis > 0 ) { $('#kiri_$ui_jenis').html(trb$ui_jenis); } else { $('#kiri_$ui_jenis').html(''); }  ";
            }
            else {
                $ui_active = $ui_jenis == $jenis ? "active bg-info" : "";
                $ui_text_active = $ui_jenis == $jenis ? "class='text-red'" : "";
                $var .= "<li class='dropdown messages-menu text-capitalize $ui_active'>";
                $var .= "<a class='btn btn-sm btn-flat btn-info text-bold' href='$ui_link' $ui_text_active>
                                    <i class='fa $ui_icon'></i> <span class='text-white'>$ui_label</span>
                                    <span class='label label-danger' id='top_$ui_jenis'></span>
                                </a>";
                $var .= "</li>";

                $varReqGroupJs .= "\n var trb$ui_jenis =  null!=localStorage.getItem('" . $ui_jenis . "') ? localStorage.getItem('" . $ui_jenis . "') : 0 ;";
                $varReqGroupJs .= "\n console.log('trb$ui_jenis: ' + trb$ui_jenis); ";
                $varReqGroupJs .= "\n if( trb$ui_jenis > 0 ) { $('#top_$ui_jenis').html(trb$ui_jenis); } else { $('#top_$ui_jenis').html(''); }  ";
            }

        }
        $varReqGroupJs .= "\n $('span.overlay').addClass('hidden')";
        $varReqGroupJs .= "\n</script>";

        $var .= $varReqGroupJs;
        //debuger
        //        $ci->output->enable_profiler(TRUE);

        return $var;
    }
}

function callMenuTopJson($thisJenis)
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    // $trGroupJenis_e = (strlen($ci->uri->segment(4)) > 0) ? $ci->uri->segment(4) : "transaksi";
    $trGroupJenis_e = isset($_GET['md']) ? $_GET['md'] : "transaksi";
    $trGroupJenis_e = "transaksi";
    $ci->load->helper("he_access_right");
    $ci->load->model("Mdls/MdlMother");
    $ci->load->model("Mdls/MdlMenuGroupUi");
    $gu = new MdlMenuGroupUi();

    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $heTransaksiGroup_uiDb = $gu->callGroupMenuTransaksiUi();
    $heTransaksiGroup_ui = $heTransaksiGroup_uiDb[$trGroupJenis_e];

    $reqgroup = base64_decode($thisJenis);
    $eID = $ci->session->login['id'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();

    $dataAcc = alowedAccess($eID);
    $allowedCustom = array();

    if (sizeof($dataAcc) > 0) {
        foreach ($dataAcc as $jn => $tempData) {
            foreach ($tempData as $step => $targetData) {
                $allowedCustom[$jn][] = $step;
            }
        }
    }

    if (strlen($reqgroup) > 3) {
        $transGrjenis = array();
        foreach ($heTransaksiGroup_ui as $group => $gparams) {
            $def_index = isset($gparams['index']) ? $gparams['index'] : "none";
            $def_icon = isset($gparams['icon']) ? $gparams['icon'] : "fa-circle";
            $g_icon = "<i class='fa $def_icon'></i>";
            $transGrLabels[$def_index]['label'] = $g_icon . "&nbsp;" . strtolower($gparams['label']);
            $transGrLabels[$def_index]['group'] = $group;
            if ($group == $reqgroup) {
                $transGrjenis[$group] = $gparams['heTransaksi_ui'];
            }
        }

        $var = "";
        $uis_now = array();
        $groupjenis = isset($transGrjenis[$reqgroup]) ? $transGrjenis[$reqgroup] : array();

        if (isset($allowedCustom) && sizeof($allowedCustom) > 0) {
            foreach ($allowedCustom as $cjenis => $top_steps) {
                $allowedjenis[] = $cjenis;
            }
        }
        else {
            foreach ($heTransaksi_ui as $mJenis => $jSpec) {
                foreach ($jSpec['steps'] as $step) {
                    $userGroup = $step['userGroup'];
                    if (in_array($userGroup, $membership)) {
                        $memberAllowes[$mJenis] = $mJenis;
                    }
                }
            }
            $allowedjenis = array_keys($memberAllowes);
        }
        $new_ui = array_intersect($groupjenis, $allowedjenis);
        foreach ($new_ui as $transGrjeni) {
            $uis_now[$transGrjeni] = $heTransaksi_ui[$transGrjeni];
        }
        $arrAllowToThisUser = array();
        foreach ($uis_now as $ui_jenis => $item_uis) {
            $arrAllowToThisUser[] = $ui_jenis;
        }
        return json_encode($arrAllowToThisUser);
    }
}

// START OF COMPLETE REPEATED LOGIC
function callMenuTaskbar__()
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heMenu");

    $dataConfig = $ci->config->item('heDataBehaviour');
    $dataRelConfig = $ci->config->item('dataRelation');
    $settingConfig = $ci->config->item('heSettingAdmin');
    $otherMenuConfig = $ci->config->item('menu');
    $availMenuConfig = $ci->config->item('availMenu');

    $arrIcon = fa_icon();
    $loginType = $ci->session->login['jenis'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();

    $otherMenus = array();

    if (sizeof($ci->load->config("heSettingAdmin")) > 0) {
        foreach ($settingConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers'])) {
                if (sizeof($mSpec['viewers']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['viewers'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($settingConfig[$mdlName]['label']) ? $settingConfig[$mdlName]['label'] : $tmpLabel;
                                $otherMenus[$tmpLabel] = $label;
                            }
                        }
                    }
                }
            }
        }
    }

    $dataExcludes = array();
    if (sizeof($dataRelConfig) > 0) {
        foreach ($dataRelConfig as $srcMdl => $sSpec) {
            foreach ($sSpec as $xmdlName => $xSpec) {
                $dataExcludes[$xmdlName] = $xmdlName;
            }
        }
    }

    if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
        foreach ($dataConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers'])) {
                if (sizeof($mSpec['viewers']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['viewers'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $keyNew = $tmpLabel == "DataHistory" ? "viewHistories" : "view";
                                    $label_f = strtolower($label) . createObjectSuffix($label);
                                    $fa_i = array_key_exists($label_f, $arrIcon) ? "fa " . $arrIcon[$label_f] : "fa fa-circle";
                                    $otherMenus[strtolower($tmpLabel)] = array(
                                        "label" => $label,
                                        "icon" => $fa_i,
                                        "target" => "data/$keyNew/$tmpLabel",
                                    );
                                }
                            }
                        }
                    }
                }
            }
            if (isset($mSpec['creators'])) {
                if (sizeof($mSpec['creators']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['creators'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $keyNew = $tmpLabel == "DataHistory" ? "viewHistories" : "view";
                                    $label_f = strtolower($label) . createObjectSuffix($label);
                                    $fa_i = array_key_exists($label_f, $arrIcon) ? "fa " . $arrIcon[$label_f] : "fa fa-circle";
                                    $otherMenus[strtolower($tmpLabel)] = array(
                                        "label" => $label,
                                        "icon" => $fa_i,
                                        "target" => "data/$keyNew/$tmpLabel",
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if (sizeof($heTransaksi_ui) > 0) {
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            if (sizeof($membership) > 0) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    foreach ($jSpec['steps'] as $num => $sSpec) {
                        if (($ci->session->login['cabang_id'] == "-1" && $jSpec['place'] == "center") || ($ci->session->login['cabang_id'] != "-1" && $jSpec['place'] != "center")) {
                            if (in_array($sSpec['userGroup'], $membership)) {
                                $otherMenus[$jenis] = array(
                                    "label" => $jSpec['label'],
                                    "icon" => $jSpec['icon'],
                                    "target" => base_url() . "Transaksi/index/$jenis",
                                );
                            }
                            else {
                            }
                        }
                    }
                }
            }
        }
    }

    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($otherMenuConfig[$gID]) && sizeof($otherMenuConfig[$gID]) > 0) {
                foreach ($otherMenuConfig[$gID] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($otherMenuConfig["*"]) && sizeof($otherMenuConfig["*"]) > 0) {
                foreach ($otherMenuConfig["*"] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }

    $cPosition = $ci->uri->segment(1);
    $last = $ci->uri->total_segments();
    $subPosition = $ci->uri->segment($last);
    if ((int)$subPosition > 0 & (int)$subPosition - (int)$subPosition === 0 & $cPosition !== 'Transaksi') {
        $subPosition = $ci->uri->segment($last - 1);
    }

    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $createIndexes = (null != $ci->config->item("transaksi_createIndex")) ? $ci->config->item("transaksi_createIndex") : array();

    if (sizeof($heTransaksi_ui) > 0) {
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                if (array_key_exists($jenis, $createIndexes)) {
                    $targetUrl = $createIndexes[$jenis] . "/$jenis";
                }
                else {
                    $targetUrl = "Transaksi/createForm/$jenis";
                }
                $otherMenus[$jSpec['label']] = array(
                    "label" => ucwords($jSpec['label']),
                    "icon" => $jSpec['icon'],
                    "target" => $targetUrl,
                );

            }
            else {
            }
        }
    }

    //    $makeStrArray = "";
    //    foreach($otherMenus as $keys => $data){
    //        $makeStrArray .= "\"".$keys."\",//".$data['label']."\n\n";
    //    }
    //    cekMerah($makeStrArray);

    $fixedMenu = array(
        //        "company", //Company profile
        //        "cabang", //Branch
        //        "div", //Division
        //        "supplier", //Vendor
        //        "customer", //Customer
        "produk",
        //Product
        //        "supplies", //Supplies & equipment
        //        "ppv", //PPV Index
        //        "produkrakitan", //Assembled Product
        //        "produkpaket", //Product Package
        //        "employee", //Employee
        //        "employeecabang", //Branch Employee
        //        "employeegudang", //Warehouse Employee
        //        "bank", //Bank
        //        "bankaccount_in", //Bank account (IN)
        //        "bankaccount_out", //Bank account (OUT)
        //        "supplieraddress", //My shipping address
        //        "courier", //Courier
        //        "currency", //Currency
        //        "satuan", //Satuan
        //        "tos", //Term of service
        //        "top", //Term of payment
        //        "capacity", //Capacity
        //        "folderproduk", //Product folder
        //        "foldersupplies", //Supplies folder
        //        "folderprodukrakitan", //Assembled Product folder
        //        "folderprodukpaket", //Package Product folder
        //        "folderpettycash", //Pettycash folder
        //        "activitylog", //activity log
        //        "adddiscount", //Add Discount
        //        "dtasubpendapatan", //Dta Sub Pendapatan
        //        "dtasupplier2", //Dta Supplier
        //        "dtamodal", //Dta Modal
        //        "dtabiayaoperasional", //Dta Biaya Operasional
        //        "dtabiayaumum", //Dta Biaya Umum
        //        "dtabiayausaha", //Dta Biaya Usaha
        //        "dtabiayaproduksi", //Dta Biaya Produksi
        //        "dtaakumpenyusutanaktivatetap", //Dta Penyusutan Aktiva Tetap
        //        "dtaaktivatakberwujud", //Dta Aktiva Tak Berwujud
        //        "dtaperson2", //Dta Person
        //        "466", //FG purchasing
        //        "461", //supplies purchasing
        //        "761", //supplies purchasing request
        //        "463", //service purchasing
        //        "583", //stock distribution
        //        "985", //stock reception (stock return receipt number)
        //        "1985", //stock reception (stock return by product)
        //        "489", //FG A/P payment
        //        "487", //supplies A/P payment
        //        "462", //service A/P payment
        //        "762", //pembiayaan supplies
        //        "967", //FG purchasing return
        //        "961", //supplies purchasing return
        //        "976", //product de-assembling
        //        "1757", //cash balance interchange
        //        "672", //pettycash initiation
        //        "771", //pettycash refill
        //        "770", //penambahan plafon pettycash
        //        "970", //pengurangan plafon pettycash
        //        "499", //(FG) credit note
        //        "758", //Penerimaan Setoran Kas
        //        "673", //expense request
        //        "473", //expense payment
        "persediaan_produk",
        //product inventory
        //        "rl", //profit & loss report
        //        "nrc", //balance
        //        "bls", //trial balance
        //        "persediaan_supplies", //supplies inventory
        "harga_produk",
        //product prices
        //        "harga_supplies", //supply prices
        //        "harga_rakitan", //assembled product prices
        //        "harga_paket", //package product prices
        //        "harga_vendor", //vendor prices
        "FG purchasing",
        //FG Purchasing
        //        "supplies purchasing", //Supplies Purchasing
        //        "supplies purchasing request", //Supplies Purchasing Request
        //        "service purchasing", //Service Purchasing
        //        "stock distribution", //Stock Distribution
        //        "FG A/P payment", //FG A/P Payment
        //        "supplies A/P payment", //Supplies A/P Payment
        //        "service A/P payment", //Service A/P Payment
        //        "pembiayaan supplies", //Pembiayaan Supplies
        //        "FG purchasing return ", //FG Purchasing Return
        //        "supplies purchasing return ", //Supplies Purchasing Return
        //        "product de-assembling", //Product De-assembling
        //        "cash balance interchange", //Cash Balance Interchange
        //        "pettycash refill", //Pettycash Refill
        //        "penambahan plafon pettycash", //Penambahan Plafon Pettycash
        //        "pengurangan plafon pettycash", //Pengurangan Plafon Pettycash
        //        "(FG) credit note", //(FG) Credit Note
        //        "expense request", //Expense Request
        //        "expense payment", //Expense Payment
    );

    $strMenuTaskbar = "";

    if (sizeof($fixedMenu) > 0) {
        foreach ($fixedMenu as $mSpec => $key) {
            if (isset($otherMenus[$key])) {
                $explodedTarget = explode('/', $otherMenus[$key]['target']);
                $numExpoded = sizeof($explodedTarget);
                $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "active" : "";
                $strMenuTaskbar .= "<a href='" . base_url() . $otherMenus[$key]['target'] . "' data-toggle='tooltip' data-placement='top' title='" . ucwords($otherMenus[$key]['label']) . "' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds $subMenuActive'>";
                $strMenuTaskbar .= "<i class='" . $otherMenus[$key]['icon'] . "'></i>";
                $strMenuTaskbar .= "</a>";
            }
        }
    }

    //region Menu Logout
    //    $strMenuTaskbar .= "<a href='". base_url()."Login/authLogout' data-toggle='tooltip' data-placement='top' title='Logout' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds'>";
    //    $strMenuTaskbar .= "<i class='fa fa-power-off'></i>";
    //    $strMenuTaskbar .= "</a>";
    //endregion Menu Logout

    //region outstanding
    $eId = $_SESSION['login']['id'];
    $strMenuTaskbar .= "<a href='" . url_sanhistory() . "public/penjualan/582k.php?Mode=PengeluaranBarang&mn=penjualan&md=sm&cm=4&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Outstanding' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-info' onclick=\"window.open('" . url_sanhistory() . "public/penjualan/582k.php?Mode=PengeluaranBarang&mn=penjualan&md=sm&cm=4&eid=$eId','popup','width=900,height=600'); return false;\">";
    $strMenuTaskbar .= "<i class='fa fa-share-square-o'></i>";
    $strMenuTaskbar .= "</a>";
    //endregion outstanding
    // public/penjualan/history.php?Mode=XX&mn=penjualan&cm=8
    //region history SO

    $link_2 = "public/penjualan/history.php?Mode=XX&mn=penjualan&cm=8";
    $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='History SO' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-warning' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    $strMenuTaskbar .= "<i class='fa fa-hand-rock-o text-putih'></i>";
    $strMenuTaskbar .= "</a>";
    //endregion history SO
    // https://sanhistory.mayagrahakencana.com/public/supplies/461.php?mn=supplies&cm=6
    //region barang belum masuk

    $link_2 = "public/supplies/461.php?mn=supplies&cm=6";
    $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Supplies belum diterima' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-success' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    $strMenuTaskbar .= "<i class='fa fa-hand-paper-o text-putih'></i>";
    $strMenuTaskbar .= "</a>";

    //endregion barang belum masuk
    // https://sanhistory.mayagrahakencana.com/public/pembelian/467.php?Mode=HistoryIn&mm=2&mn=pembelian
    //region penerimaan FG

    $link_2 = "public/pembelian/467.php?Mode=HistoryIn&mm=2&mn=pembelian";
    $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Penerimaan Finished Goods' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-danger' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    $strMenuTaskbar .= "<i class='fa fa-hand-rock-o text-putih'></i>";
    $strMenuTaskbar .= "</a>";

    //endregion barang belum masuk

    return $strMenuTaskbar;
}
// END OF COMPLETE REPEATED LOGIC

// START OF COMPLETE REPEATED LOGIC
function callMenuTaskbar()
{

    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heMenu");

    $dataConfig = $ci->config->item('heDataBehaviour');
    $dataRelConfig = $ci->config->item('dataRelation');
    $settingConfig = $ci->config->item('heSettingAdmin');
    $otherMenuConfig = $ci->config->item('menu');
    $availMenuConfig = $ci->config->item('availMenu');

    $arrIcon = fa_icon();
    $loginType = $ci->session->login['jenis'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();

    $otherMenus = array();

    if (sizeof($ci->load->config("heSettingAdmin")) > 0) {
        foreach ($settingConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers'])) {
                if (sizeof($mSpec['viewers']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['viewers'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($settingConfig[$mdlName]['label']) ? $settingConfig[$mdlName]['label'] : $tmpLabel;
                                $otherMenus[$tmpLabel] = $label;
                            }
                        }
                    }
                }
            }
        }
    }

    $dataExcludes = array();
    if (sizeof($dataRelConfig) > 0) {
        foreach ($dataRelConfig as $srcMdl => $sSpec) {
            foreach ($sSpec as $xmdlName => $xSpec) {
                $dataExcludes[$xmdlName] = $xmdlName;
            }
        }
    }

    if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
        foreach ($dataConfig as $mdlName => $mSpec) {
            if (isset($mSpec['viewers'])) {
                if (sizeof($mSpec['viewers']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['viewers'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $keyNew = $tmpLabel == "DataHistory" ? "viewHistories" : "view";
                                    $label_f = strtolower($label) . createObjectSuffix($label);
                                    $fa_i = array_key_exists($label_f, $arrIcon) ? "fa " . $arrIcon[$label_f] : "fa fa-circle";
                                    $otherMenus[strtolower($tmpLabel)] = array(
                                        "label" => $label,
                                        "icon" => $fa_i,
                                        "target" => "data/$keyNew/$tmpLabel",
                                    );
                                }
                            }
                        }
                    }
                }
            }
            if (isset($mSpec['creators'])) {
                if (sizeof($mSpec['creators']) > 0) {
                    if (sizeof($membership) > 0) {
                        foreach ($membership as $gID) {
                            if (in_array($gID, $mSpec['creators'])) {
                                $tmpLabel = str_replace("Mdl", "", $mdlName);
                                $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                if (!in_array($mdlName, $dataExcludes)) {
                                    $keyNew = $tmpLabel == "DataHistory" ? "viewHistories" : "view";
                                    $label_f = strtolower($label) . createObjectSuffix($label);
                                    $fa_i = array_key_exists($label_f, $arrIcon) ? "fa " . $arrIcon[$label_f] : "fa fa-circle";
                                    $otherMenus[strtolower($tmpLabel)] = array(
                                        "label" => $label,
                                        "icon" => $fa_i,
                                        "target" => "data/$keyNew/$tmpLabel",
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if (sizeof($heTransaksi_ui) > 0) {
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            $modul = isset($heTransaksi_ui[$jenis]["modul"]) ? $heTransaksi_ui[$jenis]["modul"] : "";
            if (sizeof($membership) > 0) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    foreach ($jSpec['steps'] as $num => $sSpec) {
                        if (($ci->session->login['cabang_id'] == "-1" && $jSpec['place'] == "center") || ($ci->session->login['cabang_id'] != "-1" && $jSpec['place'] != "center")) {
                            if (in_array($sSpec['userGroup'], $membership)) {
                                $otherMenus[$jenis] = array(
                                    "label" => $jSpec['label'],
                                    "icon" => $jSpec['icon'],
                                    "target" => base_url() . $modul . "/" . "Transaksi/index/$jenis",
                                );
                            }
                            else {
                            }
                        }
                    }
                }
            }
        }
    }

    if (sizeof($membership) > 0) {
        foreach ($membership as $gID) {
            if (isset($otherMenuConfig[$gID]) && sizeof($otherMenuConfig[$gID]) > 0) {
                foreach ($otherMenuConfig[$gID] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
            if (isset($otherMenuConfig["*"]) && sizeof($otherMenuConfig["*"]) > 0) {
                foreach ($otherMenuConfig["*"] as $kode) {
                    if (isset($availMenuConfig[$kode])) {
                        $otherMenus[$kode] = array(
                            "label" => $availMenuConfig[$kode]['label'],
                            "icon" => $availMenuConfig[$kode]['icon'],
                            "target" => $availMenuConfig[$kode]['target'],
                        );
                    }
                }
            }
        }
    }

    $cPosition = $ci->uri->segment(1);
    $last = $ci->uri->total_segments();
    $subPosition = $ci->uri->segment($last);
    if ((int)$subPosition > 0 & (int)$subPosition - (int)$subPosition === 0 & $cPosition !== 'Transaksi') {
        $subPosition = $ci->uri->segment($last - 1);
    }

    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $createIndexes = (null != $ci->config->item("transaksi_createIndex")) ? $ci->config->item("transaksi_createIndex") : array();

    if (sizeof($heTransaksi_ui) > 0) {
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            $modul = isset($heTransaksi_ui[$jenis]["modul"]) ? $heTransaksi_ui[$jenis]["modul"] : "";
            if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                if (array_key_exists($jenis, $createIndexes)) {
                    $targetUrl = $modul . "/" . $createIndexes[$jenis] . "/$jenis";
                }
                else {
                    $targetUrl = $modul . "/Create/index/$jenis";
                }
                $otherMenus[$jSpec['label']] = array(
                    "label" => ucwords($jSpec['label']),
                    "icon" => $jSpec['icon'],
                    "target" => $targetUrl,
                );

            }
            else {
            }
        }
    }

    //    $makeStrArray = "";
    //    foreach($otherMenus as $keys => $data){
    //        $makeStrArray .= "\"".$keys."\",//".$data['label']."\n\n";
    //    }
    //    cekMerah($makeStrArray);

    $fixedMenu = array(
        //        "company", //Company profile
        //        "cabang", //Branch
        //        "div", //Division
        //        "supplier", //Vendor
        //        "customer", //Customer
        "produk",
        //Product
        //        "supplies", //Supplies & equipment
        //        "ppv", //PPV Index
        //        "produkrakitan", //Assembled Product
        //        "produkpaket", //Product Package
        //        "employee", //Employee
        //        "employeecabang", //Branch Employee
        //        "employeegudang", //Warehouse Employee
        //        "bank", //Bank
        //        "bankaccount_in", //Bank account (IN)
        //        "bankaccount_out", //Bank account (OUT)
        //        "supplieraddress", //My shipping address
        //        "courier", //Courier
        //        "currency", //Currency
        //        "satuan", //Satuan
        //        "tos", //Term of service
        //        "top", //Term of payment
        //        "capacity", //Capacity
        //        "folderproduk", //Product folder
        //        "foldersupplies", //Supplies folder
        //        "folderprodukrakitan", //Assembled Product folder
        //        "folderprodukpaket", //Package Product folder
        //        "folderpettycash", //Pettycash folder
        //        "activitylog", //activity log
        //        "adddiscount", //Add Discount
        //        "dtasubpendapatan", //Dta Sub Pendapatan
        //        "dtasupplier2", //Dta Supplier
        //        "dtamodal", //Dta Modal
        //        "dtabiayaoperasional", //Dta Biaya Operasional
        //        "dtabiayaumum", //Dta Biaya Umum
        //        "dtabiayausaha", //Dta Biaya Usaha
        //        "dtabiayaproduksi", //Dta Biaya Produksi
        //        "dtaakumpenyusutanaktivatetap", //Dta Penyusutan Aktiva Tetap
        //        "dtaaktivatakberwujud", //Dta Aktiva Tak Berwujud
        //        "dtaperson2", //Dta Person
        //        "466", //FG purchasing
        //        "461", //supplies purchasing
        //        "761", //supplies purchasing request
        //        "463", //service purchasing
        //        "583", //stock distribution
        //        "985", //stock reception (stock return receipt number)
        //        "1985", //stock reception (stock return by product)
        //        "489", //FG A/P payment
        //        "487", //supplies A/P payment
        //        "462", //service A/P payment
        //        "762", //pembiayaan supplies
        //        "967", //FG purchasing return
        //        "961", //supplies purchasing return
        //        "976", //product de-assembling
        //        "1757", //cash balance interchange
        //        "672", //pettycash initiation
        //        "771", //pettycash refill
        //        "770", //penambahan plafon pettycash
        //        "970", //pengurangan plafon pettycash
        //        "499", //(FG) credit note
        //        "758", //Penerimaan Setoran Kas
        //        "673", //expense request
        //        "473", //expense payment
        "persediaan_produk",
        //product inventory
        //        "rl", //profit & loss report
        //        "nrc", //balance
        //        "bls", //trial balance
        //        "persediaan_supplies", //supplies inventory
        "harga_produk",
        //product prices
        //        "harga_supplies", //supply prices
        //        "harga_rakitan", //assembled product prices
        //        "harga_paket", //package product prices
        //        "harga_vendor", //vendor prices
        "FG purchasing",
        //FG Purchasing
        //        "supplies purchasing", //Supplies Purchasing
        //        "supplies purchasing request", //Supplies Purchasing Request
        //        "service purchasing", //Service Purchasing
        //        "stock distribution", //Stock Distribution
        //        "FG A/P payment", //FG A/P Payment
        //        "supplies A/P payment", //Supplies A/P Payment
        //        "service A/P payment", //Service A/P Payment
        //        "pembiayaan supplies", //Pembiayaan Supplies
        //        "FG purchasing return ", //FG Purchasing Return
        //        "supplies purchasing return ", //Supplies Purchasing Return
        //        "product de-assembling", //Product De-assembling
        //        "cash balance interchange", //Cash Balance Interchange
        //        "pettycash refill", //Pettycash Refill
        //        "penambahan plafon pettycash", //Penambahan Plafon Pettycash
        //        "pengurangan plafon pettycash", //Pengurangan Plafon Pettycash
        //        "(FG) credit note", //(FG) Credit Note
        //        "expense request", //Expense Request
        //        "expense payment", //Expense Payment
    );

    $strMenuTaskbar = "";

    if (sizeof($fixedMenu) > 0) {
        foreach ($fixedMenu as $mSpec => $key) {
            if (isset($otherMenus[$key])) {
                $explodedTarget = explode('/', $otherMenus[$key]['target']);
                $numExpoded = sizeof($explodedTarget);
                $subMenuActive = $subPosition == str_replace(' ', '%20', $explodedTarget[($numExpoded - 1)]) ? "active" : "";
                $strMenuTaskbar .= "<a href='" . base_url() . $otherMenus[$key]['target'] . "' data-toggle='tooltip' data-placement='top' title='" . ucwords($otherMenus[$key]['label']) . "' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds $subMenuActive'>";
                $strMenuTaskbar .= "<i class='" . $otherMenus[$key]['icon'] . "'></i>";
                $strMenuTaskbar .= "</a>";
            }
        }
    }

    //region Menu Logout
    //    $strMenuTaskbar .= "<a href='". base_url()."Login/authLogout' data-toggle='tooltip' data-placement='top' title='Logout' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds'>";
    //    $strMenuTaskbar .= "<i class='fa fa-power-off'></i>";
    //    $strMenuTaskbar .= "</a>";
    //endregion Menu Logout

    //region outstanding dimatikan dulu pc databasedi rita lama belum dikembalikan
    // $eId = $_SESSION['login']['id'];
    // $strMenuTaskbar .= "<a href='" . url_sanhistory() . "public/penjualan/582k.php?Mode=PengeluaranBarang&mn=penjualan&md=sm&cm=4&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Outstanding' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-info' onclick=\"window.open('" . url_sanhistory() . "public/penjualan/582k.php?Mode=PengeluaranBarang&mn=penjualan&md=sm&cm=4&eid=$eId','popup','width=900,height=600'); return false;\">";
    // $strMenuTaskbar .= "<i class='fa fa-share-square-o'></i>";
    // $strMenuTaskbar .= "</a>";
    // //endregion outstanding

    //region history SO

    // $link_2 = "public/penjualan/history.php?Mode=XX&mn=penjualan&cm=8";
    // $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='History SO' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-warning' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    // $strMenuTaskbar .= "<i class='fa fa-hand-rock-o text-putih'></i>";
    // $strMenuTaskbar .= "</a>";
    //endregion history SO

    //region barang belum masuk

    // $link_2 = "public/supplies/461.php?mn=supplies&cm=6";
    // $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Supplies belum diterima' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-success' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    // $strMenuTaskbar .= "<i class='fa fa-hand-paper-o text-putih'></i>";
    // $strMenuTaskbar .= "</a>";

    //endregion barang belum masuk

    //region penerimaan FG

    // $link_2 = "public/pembelian/467.php?Mode=HistoryIn&mm=2&mn=pembelian";
    // $strMenuTaskbar .= "<a href='" . url_sanhistory() . $link_2 . "&eid=$eId' data-toggle='tooltip' data-placement='top' target='popup' title='Penerimaan Finished Goods' class='hidden-xs btn btn__trigger-last-bawah-ds btn__trigger-last-views-bawah-ds btn-danger' onclick=\"window.open('" . url_sanhistory() . $link_2 . "&eid=$eId','popup','width=900,height=600'); return false;\">";
    // $strMenuTaskbar .= "<i class='fa fa-hand-rock-o text-putih'></i>";
    // $strMenuTaskbar .= "</a>";

    //endregion barang belum masuk

    return $strMenuTaskbar;
}
// END OF COMPLETE REPEATED LOGIC

function callTransMenu()
{
    $strMenuLeft = "";
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heTransaksi_misc");

    $loginType = $ci->session->login['jenis'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $createIndexes = (null != $ci->config->item("transaksi_createIndex")) ? $ci->config->item("transaksi_createIndex") : array();


    $transMenus = array();
    if (sizeof($heTransaksi_ui) > 0) {
        foreach ($heTransaksi_ui as $jenis => $jSpec) {
            if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                $transMenus[$jenis] = "<span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'];
            }
            else {
            }
        }
    }

    asort($transMenus);
    $strMenuLeft .= "<ul class=\"menu \">";
    if (sizeof($transMenus) > 0) {
        foreach ($transMenus as $jenis => $label) {
            if (array_key_exists($jenis, $createIndexes)) {
                $targetUrl = base_url() . $createIndexes[$jenis] . "/$jenis";
            }
            else {
                $targetUrl = base_url() . "Transaksi/createForm/$jenis";
            }
            $strMenuLeft .= "<li>";
            $strMenuLeft .= " <a style='color:#454545;' href='$targetUrl'>$label</a>";
            $strMenuLeft .= "</li>";
        }
    }
    else {
        $strMenuLeft .= "<li>";
        $strMenuLeft .= " <a>you have no such right <br>to make new transaction</a> ";
        $strMenuLeft .= "</li>";
    }
    $strMenuLeft .= "</ul class=\"sidebar-menu \">";

    return $strMenuLeft;
}

// START OF COMPLETE REPEATED LOGIC
function callFloatMenu_($position = 'bawah')
{
    $strFloatMenu = "";
    $strFloatMenuMb = "";
    $strFloatMenuDs = "";
    $strFloatMenu_ = "";
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heTransaksi_misc");

    $loginType = $ci->session->login['jenis'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $createIndexes = (null != $ci->config->item("transaksi_createIndex")) ? $ci->config->item("transaksi_createIndex") : array();
    $transMenus = array();

    switch ($position) {
        default:
        case "bawah":
            if (sizeof($heTransaksi_ui) > 0) {
                foreach ($heTransaksi_ui as $jenis => $jSpec) {
                    if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                        $transMenus[$jenis] = "<i class='" . $jSpec['icon'] . "'></i>   " . ucwords($jSpec['label']);
                    }
                    else {
                    }
                }
            }
            asort($transMenus);
            $strFloatMenuMb .= "<ul class=\"my-nav-$position-mb my-nav--list-$position-mb\">";
            $strFloatMenuMb .= "<div id=\"wrapper-templates\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . "Transaksi/createForm/$jenis";
                    }
                    $strFloatMenuMb .= "<li class=\"my-nav__item-$position-mb\">";
                    $strFloatMenuMb .= "<a class=\"my-nav__link-mb my-nav__link--template-mb text-white\" href='$targetUrl'>$label</a>";
                    $strFloatMenuMb .= "</li>";
                }
            }
            else {
                $strFloatMenuMb .= "<li>";
                $strFloatMenuMb .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenuMb .= "</li>";
            }
            $strFloatMenuMb .= "</div>";
            $strFloatMenuMb .= "</ul>";

            $strFloatMenuDs .= "<ul class=\"my-nav-$position-ds my-nav--list-$position-ds\">";
            $strFloatMenuDs .= "<div id=\"wrapper-templates-ds\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . "Transaksi/createForm/$jenis";
                    }
                    $strFloatMenuDs .= "<li class=\"my-nav__item-$position-ds\">";
                    $strFloatMenuDs .= "<a class=\"my-nav__link-ds my-nav__link--template-ds text-white\" href='$targetUrl'>$label</a>";
                    $strFloatMenuDs .= "</li>";
                }
            }
            else {
                $strFloatMenuDs .= "<li>";
                $strFloatMenuDs .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenuDs .= "</li>";
            }
            $strFloatMenuDs .= "</div>";
            $strFloatMenuDs .= "</ul>";

            $strFloatMenuMbActions = "<div class='ev-taskbar-actions-mb hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl'>"
                . "<div class='ev-action-slot' id='other_mb'></div>"
                . "<div class='ev-action-slot' id='geplak_mb'></div>"
                . "<div class='ev-action-slot' id='chat_ai_mb'></div>"
                . "<div class='ev-action-slot' id='gethuk_mb'></div>"
                . "<a class=\"btn btn__trigger-$position-mb btn__trigger--views-$position-mb ev-action-slot\" id=\"trigger-$position-mb\" title=\"Transaksi Baru\"><i class=\"glyphicon glyphicon-plus\"></i></a>"
                . "</div>";

            $strFloatMenuDsActions = "<div class='ev-taskbar-actions hidden-xs'>"
                . "<div class='ev-action-slot' id='other_ds'></div>"
                . "<div class='ev-action-slot' id='geplak_ds'></div>"
                . "<div class='ev-action-slot' id='chat_ai_ds'></div>"
                . "<div class='ev-action-slot' id='gethuk_ds'></div>"
                . "<a class=\"btn btn__trigger-$position-ds btn__trigger--views-$position-ds ev-action-slot\" id=\"trigger-$position-ds\" title=\"Transaksi Baru\"><i class=\"glyphicon glyphicon-plus\"></i></a>"
                . "</div>";

            $strFloatMenu .= $strFloatMenuMbActions;
            $strFloatMenu .= $strFloatMenuDsActions;
            $strFloatMenu .= $strFloatMenuMb;
            $strFloatMenu .= $strFloatMenuDs;

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-f-mb my-nav--list-$position-f-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-f-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-f-ds my-nav--list-$position-f-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-f-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-WT-mb my-nav--list-$position-WT-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-WT-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-WT-ds my-nav--list-$position-WT-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-WT-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-RK-mb my-nav--list-$position-RK-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-RK-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-RK-ds my-nav--list-$position-RK-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-RK-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "\n<script>";
            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var taskbarActions = $('.ev-taskbar-actions');\n";
            $strFloatMenu .= "if(taskbarActions.length > 0){\n";
            $strFloatMenu .= "    var geplak = $('#geplak_ds'), other = $('#other_ds'), chatAi = $('#chat_ai_ds'), gethuk = $('#gethuk_ds');\n";
            $strFloatMenu .= "    if(geplak.length > 0 && !taskbarActions.has(geplak).length){\n";
            $strFloatMenu .= "        if(chatAi.length > 0) geplak.insertBefore(chatAi); else if(gethuk.length > 0) geplak.insertBefore(gethuk); else taskbarActions.prepend(geplak);\n";
            $strFloatMenu .= "        geplak.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "    if(other.length > 0 && !taskbarActions.has(other).length){\n";
            $strFloatMenu .= "        if(geplak.length > 0) other.insertBefore(geplak); else if(chatAi.length > 0) other.insertBefore(chatAi); else taskbarActions.prepend(other);\n";
            $strFloatMenu .= "        other.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "var taskbarActionsMb = $('.ev-taskbar-actions-mb');\n";
            $strFloatMenu .= "if(taskbarActionsMb.length > 0){\n";
            $strFloatMenu .= "    var geplakMb = $('#geplak_mb'), otherMb = $('#other_mb'), chatAiMb = $('#chat_ai_mb'), gethukMb = $('#gethuk_mb');\n";
            $strFloatMenu .= "    if(geplakMb.length > 0 && !taskbarActionsMb.has(geplakMb).length){\n";
            $strFloatMenu .= "        if(chatAiMb.length > 0) geplakMb.insertBefore(chatAiMb); else if(gethukMb.length > 0) geplakMb.insertBefore(gethukMb); else taskbarActionsMb.prepend(geplakMb);\n";
            $strFloatMenu .= "        geplakMb.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "    if(otherMb.length > 0 && !taskbarActionsMb.has(otherMb).length){\n";
            $strFloatMenu .= "        if(geplakMb.length > 0) otherMb.insertBefore(geplakMb); else if(chatAiMb.length > 0) otherMb.insertBefore(chatAiMb); else taskbarActionsMb.prepend(otherMb);\n";
            $strFloatMenu .= "        otherMb.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "var triggerBawah=$(\"#trigger-$position\"),mainTargetBawah=$(\".my-nav-$position\"),targetItemBawah=$('.my-nav__item-$position');\n";
            $strFloatMenu .= "if(triggerBawah.length>0 && mainTargetBawah.length>0 && targetItemBawah.length>0 ){\n";
            $strFloatMenu .= "displayListBawah();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var triggerBawahMb=$(\"#trigger-$position-mb\"),mainTargetBawahMb=$(\".my-nav-$position-mb\"),targetItemBawahMb=$('.my-nav__item-$position-mb');\n";
            $strFloatMenu .= "if(triggerBawahMb.length>0 && mainTargetBawahMb.length>0 && targetItemBawahMb.length>0 ){\n";
            $strFloatMenu .= "displayListBawahMb();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah-mb').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var triggerBawahDs=$(\"#trigger-$position-ds\"),mainTargetBawahDs=$(\".my-nav-$position-ds\"),targetItemBawahDs=$('.my-nav__item-$position-ds');\n";
            $strFloatMenu .= "if(triggerBawahDs.length>0 && mainTargetBawahDs.length>0 && targetItemBawahDs.length>0 ){\n";
            $strFloatMenu .= "displayListBawahDs();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah-ds').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "</script>";

            break;
        case "atas":
            //region menu transaksi
            if (sizeof($heTransaksi_ui) > 0) {
                foreach ($heTransaksi_ui as $jenis => $jSpec) {
                    if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                        $transMenus[$jenis] = "<i class='" . $jSpec['icon'] . "'></i>   " . ucwords($jSpec['label']);
                    }
                    else {
                    }
                }
            }
            //endregion
            asort($transMenus);
            $strFloatMenu .= "<a class=\"btn btn__trigger-$position btn__trigger--views-$position hidden-xs hidden-sm\" id=\"trigger-$position\"><i class=\"glyphicon glyphicon-plus\"></i></a>";
            $strFloatMenu .= "<ul class=\"my-nav-$position my-nav--list-$position\">";
            $strFloatMenu .= "<div id=\"wrapper-templates\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . "Transaksi/createForm/$jenis";
                    }
                    $strFloatMenu .= "<li class=\"my-nav__item-$position\">";
                    $strFloatMenu .= "<a class=\"my-nav__link my-nav__link--template\" href='$targetUrl'>$label</a>";
                    $strFloatMenu .= "</li class=\"my-nav__item\">";
                }
            }
            else {
                $strFloatMenu .= "<li>";
                $strFloatMenu .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenu .= "</li>";
            }
            $strFloatMenu .= "</div id=\"wrapper-templates\">";
            $strFloatMenu .= "</ul>";

            break;
    }

    return $strFloatMenu;
}
// END OF COMPLETE REPEATED LOGIC

// START OF COMPLETE REPEATED LOGIC
function callFloatMenu($position = 'bawah')
{
    $strFloatMenu = "";
    $strFloatMenuMb = "";
    $strFloatMenuDs = "";
    $strFloatMenu_ = "";
    $ci =& get_instance();
    $ci->load->config("heTransaksi_ui");
    $ci->load->config("heTransaksi_misc");

    $loginType = $ci->session->login['jenis'];
    $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
    $heTransaksi_ui = (null != $ci->config->item("heTransaksi_ui")) ? $ci->config->item("heTransaksi_ui") : array();
    $createIndexes = (null != $ci->config->item("transaksi_createIndex")) ? $ci->config->item("transaksi_createIndex") : array();
    $transMenus = array();

    switch ($position) {
        default:
        case "bawah":
            if (sizeof($heTransaksi_ui) > 0) {
                foreach ($heTransaksi_ui as $jenis => $jSpec) {
                    if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                        $transMenus[$jenis] = "<i class='" . $jSpec['icon'] . "'></i>   " . ucwords($jSpec['label']);
                    }
                    else {
                    }
                }
            }
            asort($transMenus);
            $strFloatMenuMb .= "<ul class=\"my-nav-$position-mb my-nav--list-$position-mb\">";
            $strFloatMenuMb .= "<div id=\"wrapper-templates\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    $modul = isset($heTransaksi_ui[$jenis]["modul"]) ? $heTransaksi_ui[$jenis]["modul"] : "";
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $modul . "/" . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . $modul . "/" . "Create/index/$jenis";
                    }
                    $strFloatMenuMb .= "<li class=\"my-nav__item-$position-mb\">";
                    $strFloatMenuMb .= "<a class=\"my-nav__link-mb my-nav__link--template-mb text-white\" href='$targetUrl'>$label</a>";
                    $strFloatMenuMb .= "</li>";
                }
            }
            else {
                $strFloatMenuMb .= "<li>";
                $strFloatMenuMb .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenuMb .= "</li>";
            }
            $strFloatMenuMb .= "</div>";
            $strFloatMenuMb .= "</ul>";

            $strFloatMenuDs .= "<ul class=\"my-nav-$position-ds my-nav--list-$position-ds\">";
            $strFloatMenuDs .= "<div id=\"wrapper-templates-ds\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    $modul = isset($heTransaksi_ui[$jenis]["modul"]) ? $heTransaksi_ui[$jenis]["modul"] : "";
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $modul . "/" . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . $modul . "/" . "Create/index/$jenis";
                    }
                    $strFloatMenuDs .= "<li class=\"my-nav__item-$position-ds\">";
                    $strFloatMenuDs .= "<a class=\"my-nav__link-ds my-nav__link--template-ds text-white\" href='$targetUrl'>$label</a>";
                    $strFloatMenuDs .= "</li>";
                }
            }
            else {
                $strFloatMenuDs .= "<li>";
                $strFloatMenuDs .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenuDs .= "</li>";
            }
            $strFloatMenuDs .= "</div>";
            $strFloatMenuDs .= "</ul>";

            $strFloatMenuMbActions = "<div class='ev-taskbar-actions-mb hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl'>"
                . "<div class='ev-action-slot' id='other_mb'></div>"
                . "<div class='ev-action-slot' id='geplak_mb'></div>"
                . "<div class='ev-action-slot' id='chat_ai_mb'></div>"
                . "<div class='ev-action-slot' id='gethuk_mb'></div>"
                . "<a class=\"btn btn__trigger-$position-mb btn__trigger--views-$position-mb ev-action-slot\" id=\"trigger-$position-mb\" title=\"Transaksi Baru\"><i class=\"glyphicon glyphicon-plus\"></i></a>"
                . "</div>";

            $strFloatMenuDsActions = "<div class='ev-taskbar-actions hidden-xs'>"
                . "<div class='ev-action-slot' id='other_ds'></div>"
                . "<div class='ev-action-slot' id='geplak_ds'></div>"
                . "<div class='ev-action-slot' id='chat_ai_ds'></div>"
                . "<div class='ev-action-slot' id='gethuk_ds'></div>"
                . "<a class=\"btn btn__trigger-$position-ds btn__trigger--views-$position-ds ev-action-slot\" id=\"trigger-$position-ds\" title=\"Transaksi Baru\"><i class=\"glyphicon glyphicon-plus\"></i></a>"
                . "</div>";

            $strFloatMenu .= $strFloatMenuMbActions;
            $strFloatMenu .= $strFloatMenuDsActions;
            $strFloatMenu .= $strFloatMenuMb;
            $strFloatMenu .= $strFloatMenuDs;

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-f-mb my-nav--list-$position-f-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-f-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-f-ds my-nav--list-$position-f-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-f-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-WT-mb my-nav--list-$position-WT-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-WT-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-WT-ds my-nav--list-$position-WT-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-WT-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"hidden-sm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl my-nav-$position-RK-mb my-nav--list-$position-RK-mb\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-RK-mb\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "<ul class=\"my-nav-$position-RK-ds my-nav--list-$position-RK-ds\">";
            $strFloatMenu .= "<div id=\"wrapper-templates-bawah-RK-ds\">";
            $strFloatMenu .= "</div>";
            $strFloatMenu .= "</ul>";

            $strFloatMenu .= "\n<script>";
            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var taskbarActions = $('.ev-taskbar-actions');\n";
            $strFloatMenu .= "if(taskbarActions.length > 0){\n";
            $strFloatMenu .= "    var geplak = $('#geplak_ds'), other = $('#other_ds'), chatAi = $('#chat_ai_ds'), gethuk = $('#gethuk_ds');\n";
            $strFloatMenu .= "    if(geplak.length > 0 && !taskbarActions.has(geplak).length){\n";
            $strFloatMenu .= "        if(chatAi.length > 0) geplak.insertBefore(chatAi); else if(gethuk.length > 0) geplak.insertBefore(gethuk); else taskbarActions.prepend(geplak);\n";
            $strFloatMenu .= "        geplak.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "    if(other.length > 0 && !taskbarActions.has(other).length){\n";
            $strFloatMenu .= "        if(geplak.length > 0) other.insertBefore(geplak); else if(chatAi.length > 0) other.insertBefore(chatAi); else taskbarActions.prepend(other);\n";
            $strFloatMenu .= "        other.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "var taskbarActionsMb = $('.ev-taskbar-actions-mb');\n";
            $strFloatMenu .= "if(taskbarActionsMb.length > 0){\n";
            $strFloatMenu .= "    var geplakMb = $('#geplak_mb'), otherMb = $('#other_mb'), chatAiMb = $('#chat_ai_mb'), gethukMb = $('#gethuk_mb');\n";
            $strFloatMenu .= "    if(geplakMb.length > 0 && !taskbarActionsMb.has(geplakMb).length){\n";
            $strFloatMenu .= "        if(chatAiMb.length > 0) geplakMb.insertBefore(chatAiMb); else if(gethukMb.length > 0) geplakMb.insertBefore(gethukMb); else taskbarActionsMb.prepend(geplakMb);\n";
            $strFloatMenu .= "        geplakMb.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "    if(otherMb.length > 0 && !taskbarActionsMb.has(otherMb).length){\n";
            $strFloatMenu .= "        if(geplakMb.length > 0) otherMb.insertBefore(geplakMb); else if(chatAiMb.length > 0) otherMb.insertBefore(chatAiMb); else taskbarActionsMb.prepend(otherMb);\n";
            $strFloatMenu .= "        otherMb.addClass('ev-action-slot');\n";
            $strFloatMenu .= "    }\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "var triggerBawah=$(\"#trigger-$position\"),mainTargetBawah=$(\".my-nav-$position\"),targetItemBawah=$('.my-nav__item-$position');\n";
            $strFloatMenu .= "if(triggerBawah.length>0 && mainTargetBawah.length>0 && targetItemBawah.length>0 ){\n";
            $strFloatMenu .= "displayListBawah();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var triggerBawahMb=$(\"#trigger-$position-mb\"),mainTargetBawahMb=$(\".my-nav-$position-mb\"),targetItemBawahMb=$('.my-nav__item-$position-mb');\n";
            $strFloatMenu .= "if(triggerBawahMb.length>0 && mainTargetBawahMb.length>0 && targetItemBawahMb.length>0 ){\n";
            $strFloatMenu .= "displayListBawahMb();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah-mb').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "(function($) {\n";
            $strFloatMenu .= "var triggerBawahDs=$(\"#trigger-$position-ds\"),mainTargetBawahDs=$(\".my-nav-$position-ds\"),targetItemBawahDs=$('.my-nav__item-$position-ds');\n";
            $strFloatMenu .= "if(triggerBawahDs.length>0 && mainTargetBawahDs.length>0 && targetItemBawahDs.length>0 ){\n";
            $strFloatMenu .= "displayListBawahDs();\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "})(jQuery);\n";
            $strFloatMenu .= "$('.btn__trigger-bawah-ds').on('click', function(event){\n";
            $strFloatMenu .= "var hasClass = $('body').hasClass('sidebar-open');\n";
            $strFloatMenu .= "if(hasClass){\n";
            $strFloatMenu .= "$('body').toggleClass('sidebar-open');\n";
            $strFloatMenu .= "}\n";
            $strFloatMenu .= "});\n";

            $strFloatMenu .= "</script>";

            break;
        case "atas":
            //region menu transaksi
            if (sizeof($heTransaksi_ui) > 0) {
                foreach ($heTransaksi_ui as $jenis => $jSpec) {
                    if (placeCanMakeTrans($membership, $ci->session->login['cabang_id'], $ci->session->login['gudang_id'], $jenis)) {
                        $transMenus[$jenis] = "<i class='" . $jSpec['icon'] . "'></i>   " . ucwords($jSpec['label']);
                    }
                    else {
                    }
                }
            }
            //endregion

            asort($transMenus);
            $strFloatMenu .= "<a class=\"btn btn__trigger-$position btn__trigger--views-$position hidden-xs hidden-sm\" id=\"trigger-$position\"><i class=\"glyphicon glyphicon-plus\"></i></a>";
            $strFloatMenu .= "<ul class=\"my-nav-$position my-nav--list-$position\">";
            $strFloatMenu .= "<div id=\"wrapper-templates\">";
            if (sizeof($transMenus) > 0) {
                foreach ($transMenus as $jenis => $label) {
                    $modul = isset($heTransaksi_ui[$jenis]["modul"]) ? $heTransaksi_ui[$jenis]["modul"] : "";
                    if (array_key_exists($jenis, $createIndexes)) {
                        $targetUrl = base_url() . $modul . "/" . $createIndexes[$jenis] . "/$jenis";
                    }
                    else {
                        $targetUrl = base_url() . $modul . "/" . "Create/index/$jenis";
                    }
                    $strFloatMenu .= "<li class=\"my-nav__item-$position\">";
                    $strFloatMenu .= "<a class=\"my-nav__link my-nav__link--template\" href='$targetUrl'>$label</a>";
                    $strFloatMenu .= "</li class=\"my-nav__item\">";
                }
            }
            else {
                $strFloatMenu .= "<li>";
                $strFloatMenu .= " <a>you have no such right <br>to make new transaction</a> ";
                $strFloatMenu .= "</li>";
            }
            $strFloatMenu .= "</div id=\"wrapper-templates\">";
            $strFloatMenu .= "</ul>";

            break;
    }

    return $strFloatMenu;

}
// END OF COMPLETE REPEATED LOGIC

function callMenuRightIsi()
{
    $ci =& get_instance();
    $ci->load->config("heMenu");
    $menuRightConf = $ci->config->item('menuRight');
    $arrNavigasi = $menuRightConf['top'];
    $myid = my_id();

    $var = "";
    $var .= "<ul class='nav nav-tabs nav-justified control-sidebar-tabs'>";

    if (isset($arrNavigasi)) {
        foreach ($arrNavigasi as $item => $arrItem) {
            $active = isset($arrItem['status']) && $arrItem['status'] == 'active' ? "class='active'" : "";
            $var .= "<li $active><a href='#" . $arrItem['target'] . "' data-toggle='tab'><i class='" . $arrItem['icon'] . "'></i></a></li>";
        }

        $var .= "</ul>";

        $var .= "<div class='tab-content'>";
        foreach ($arrNavigasi as $item => $arrItem) {
            $active = isset($arrItem['status']) && $arrItem['status'] == 'active' ? "active" : "";
            $label = $arrItem['label'];
            $title = $arrItem['title'];
            //            $src = $arrItem['src'];
            $src = "MdlEmployee";
            $ci->load->model("Mdls/$src");
            // $ci->load->model("MdlActivityLog");
            $cc = new $src();
            $cc->setFilters(array());
            // $ccc = new MdlActivityLog();
            // arrPrint($arrData->id);
            $var .= "<div class='tab-pane $active' id='" . $arrItem['target'] . "'>";
            $var .= "<h3 class='control-sidebar-heading'>$title</h3>";
            switch ($src) {
                case "MdlEmployee":
                    $arrData = $cc->lookupByID(my_id())->result()[0];
                    $arrShow = isset($arrItem['show']) ? $arrItem['show'] : array();

                    if (sizeof($arrShow) > 0) {

                        $var .= "<ul class='control-sidebar-menu'>";
                        foreach ($arrShow as $field => $arrDatum_0) {

                            $var .= "<li>";
                            $var .= "<a>";
                            $var .= "<i class='menu-icon fa " . $arrDatum_0['iconcss'] . "'></i>";
                            $var .= "<div class='menu-info'>";
                            $var .= "<h4 class='control-sidebar-subheading'>";
                            $var .= $arrDatum_0['title'];
                            $var .= "<small> ";
                            $var .= $arrData->$field;
                            $var .= "</small>";
                            $var .= "</h4>";
                            $var .= "<p>";
                            $var .= $arrDatum_0['deskripsi'];
                            $var .= "</p>";
                            $var .= "</div>";
                            $var .= "</a>";
                            $var .= "</li>";

                        }
                        $var .= "</ul>";
                        $var .= "<div class='margin-top-20'>";
                        $var .= "<button type='button' class='btn btn-block btn-info' onclick=\"location.href='" . base_url() . "Data/myProfile/Employee/$myid'\" title='show profile' data-toggle='tooltip'>Show My Profile</button>";
                        $var .= "</div>";
                    }

                    break;
                case "MdlActivityLog":
                    $condite = "uid='$myid' order by id desc limit 10";
                    $arrData = $cc->lookupByCondition($condite)->result();
                    // arrPrint($arrData);

                    foreach ($arrData as $arrDatum) {
                        $category = $arrDatum->category;
                        $title = $arrDatum->title;
                        $sub_title = $arrDatum->sub_title;
                        $var .= "<label class='control-sidebar-subheading'>";
                        $var .= $arrDatum->dtime;
                        $var .= "</label>";
                        $var .= "<p>";
                        $var .= "$category";
                        $var .= " $title";
                        $var .= " $sub_title";
                        $var .= "</p>";
                    }
                    $var .= "<div class='margin-top-20'>";
                    $var .= "<button type='button' class='btn btn-block btn-info' onclick=\"location.href='" . base_url() . "Data/view/ActivityLog/$myid'\" title='show profile' data-toggle='tooltip'>Show More</button>";
                    $var .= "</div>";

                    break;
                default:
                    break;
            }
            $var .= "</div>";
        }
    }

    $var .= "</div>";

    return $var;
}

function callSubMenu()
{
    //    $ci =& get_instance();
    //    $ci->load->config("heMenu");
    //    //    $parent = $ci->$this->uri->segment(1);
    //    $menuRightConf = $ci->config->item('subMenu');
    //    //    cekHere("$parent ||");
    //    $arrNavigasi = isset($menuRightConf['Opname']) ? $menuRightConf['Opname'] : "";
    //    //    arrPrint($arrNavigasi);
    //    $var = "";
    //    if (sizeof($arrNavigasi) > 0) {
    //        foreach ($arrNavigasi as $jenis => $arrJenis) {
    //            $label = $arrJenis["label"];
    //            $link = $arrJenis["target"];
    //            $icon_f = $arrJenis["icon"];
    //
    //            //cekHere("$link $label");
    //            //        $active = $i_label == $label_active ? "active" : "";
    //            $fa_icon = strlen($icon_f) > 2 ? $icon_f : "fa-check-circle";
    //            $var .= "<li class='dropdown user user-menu '>";
    //            //            $str .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
    //            $var .= "<a href='$link' class='dropdown-toggle'>";
    //            $var .= "<i class='fa $fa_icon'></i>";
    //            $var .= "<span class='hidden-xs'>$label </span>";
    //            //        $var .= "<span class='label label-danger pull-right'>$nilai_1</span>";
    //            $var .= "</a>";
    //            $var .= "</li>";
    //        }
    //    }
    //
    //
    //    return $var;
}

//==panggil semua pre-processor, kalau semua reversible berarti boleh undo
function evaluatePreProcessors($trID, $stepNum)
{
    $result = null;
    $ci =& get_instance();
    $preProcessors_d = isset($ci->config->item('heTransaksi_core')[$trID]['preProcessor'][$stepNum]['detail']) ? $ci->config->item('heTransaksi_core')[$trID]['preProcessor'][$stepNum]['detail'] : array();
    $preProcessors_m = isset($ci->config->item('heTransaksi_core')[$trID]['preProcessor'][$stepNum]['master']) ? $ci->config->item('heTransaksi_core')[$trID]['preProcessor'][$stepNum]['master'] : array();
    $preProcessors_rev = null != ($ci->config->item('hePreProcessors')) ? $ci->config->item('hePreProcessors') : array();
    //    arrprint($preProcessors_rev);
    //    cekbiru("evaluating pre-procs $trID $stepNum....");

    if (sizeof($preProcessors_d) > 0 || sizeof($preProcessors_m) > 0) {
        $jmlReversible = 0;
        $jmlIrreversible = 0;
        if (sizeof($preProcessors_d) > 0) {

            foreach ($preProcessors_d as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    //                    cekbiru ("$comName is reversable");
                    $jmlReversible++;
                }
                else {
                    //                    cekbiru ("$comName is NOT reversable");
                    $jmlIrreversible++;
                }
            }

        }

        if (sizeof($preProcessors_m) > 0) {

            foreach ($preProcessors_m as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }


    }
    else {
        //        cekhijau("preprocs DONT exist");
    }

    return $result;
}

function evaluatePostProcessors($trID, $stepNum)
{
    $result = null;
    $ci =& get_instance();
    $preProcessors_d = isset($ci->config->item('heTransaksi_core')[$trID]['postProcessor'][$stepNum]['detail']) ? $ci->config->item('heTransaksi_core')[$trID]['postProcessor'][$stepNum]['detail'] : array();
    $preProcessors_m = isset($ci->config->item('heTransaksi_core')[$trID]['postProcessor'][$stepNum]['master']) ? $ci->config->item('heTransaksi_core')[$trID]['postProcessor'][$stepNum]['master'] : array();
    $preProcessors_rev = null != ($ci->config->item('hePostProcessors')) ? $ci->config->item('hePostProcessors') : array();

    //    arrprint($preProcessors_rev);

    if (sizeof($preProcessors_d) > 0 || sizeof($preProcessors_m) > 0) {
        $jmlReversible = 0;
        $jmlIrreversible = 0;
        if (sizeof($preProcessors_d) > 0) {

            foreach ($preProcessors_d as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if (sizeof($preProcessors_m) > 0) {
            foreach ($preProcessors_m as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }


    }
    else {
        //        cekhijau("preprocs DONT exist");
    }

    return $result;
}

function evaluateComponents($trID, $stepNum)
{
    $ci =& get_instance();
    $mComponents = isset($ci->config->item('heTransaksi_core')[$trID]['components'][$stepNum]['master']) ? $ci->config->item('heTransaksi_core')[$trID]['components'][$stepNum]['master'] : array();
    $result = null;
    $jmlReversible = 0;
    $jmlIrreversible = 0;
    if (sizeof($mComponents) > 0) {
        $comNames = array();
        foreach ($mComponents as $cSpec) {
            $comNames[] = $cSpec['comName'];
        }
        if (in_array("Jurnal", $comNames)) {
            $jmlIrreversible++;

            if ($jmlIrreversible > 0) {
                $result = $jmlIrreversible;
            }
        }
    }

    return $result;
}

function evaluateMain($trJenis, $stepNum)
{
    // cekHitam("$trJenis,$stepNum");
    $ci =& get_instance();
    $mComponents = isset($ci->config->item('heTransaksi_ui')[$trJenis]['allowedMainEdit']) ? $ci->config->item('heTransaksi_ui')[$trJenis]['allowedMainEdit'] : array();
    $result = null;
    $jmlReversible = 0;
    $jmlIrreversible = 0;
    if (sizeof($mComponents) > 0) {
        if (in_array($stepNum, $mComponents)) {
            $jmlReversible++;
        }
        else {
            $jmlIrreversible++;
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }
    }
    else {
        $result = 1;
    }

    return $result;
}

function callNextPIC($arrNextAction)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $ci->load->model("Mdls/MdlEmployee");
    $ci->load->model("Mdls/MdlEmployeeCabang");
    $ci->load->model("Mdls/MdlEmployeeGudang");
    $ci->load->model("Mdls/MdlCabang");


    $employeeData = array();
    $result = array();
//arrPrint($arrNextAction);
    if (sizeof($arrNextAction) > 0) {
        $nextCode = array();
        $nextStep = array();
        $nextSteps = array();
        $nextCodes = array();
        foreach ($arrNextAction as $trID => $spec) {
//            $nextSteps[$spec['next_step_num']] = $spec['next_step_num'];
//            $nextCodes[$spec['next_step_code']] = $spec['next_step_code'];
            if (isset($spec['next_step_num']) && ($spec['next_step_num'] != NULL)) {
                $nextSteps[$spec['next_step_num']] = $spec['next_step_num'];
            }
            if (isset($spec['next_step_code']) && ($spec['next_step_code'] != NULL)) {
                $nextCodes[$spec['next_step_code']] = $spec['next_step_code'];
            }
        }


        $a = new MdlAccessRight();
        if (sizeof($nextCodes) > 0) {
            $ci->db->where("steps_code in ('" . implode("','", $nextCodes) . "')");
        }
        if (sizeof($nextSteps) > 0) {
            $ci->db->where("steps in ('" . implode("','", $nextSteps) . "')");
        }
        $tmp = $a->lookupAll()->result();
        showLast_query("biru");

        $e = New MdlEmployee();
        $eResult = $e->lookupAll()->result();
        if (sizeof($eResult) > 0) {
            foreach ($eResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $ec = New MdlEmployeeCabang();
        $ecResult = $ec->lookupAll()->result();
        if (sizeof($ecResult) > 0) {
            foreach ($ecResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $eg = New MdlEmployeeCabang();
        $egResult = $eg->lookupAll()->result();
        if (sizeof($egResult) > 0) {
            foreach ($egResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $cb = New MdlCabang();
        $cbResult = $cb->lookupAll()->result();
        if (sizeof($cbResult) > 0) {
            foreach ($cbResult as $spec) {
                $cabangData[$spec->id] = array(
                    "nama" => $spec->nama,
                );
            }
        }

        //arrPrintWebs($tmp);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {

                if (isset($employeeData[$spec->employee_id])) {
                    $result[$spec->steps_code][$spec->steps][$spec->employee_id] = array(
                        "id" => $spec->employee_id,
                        "nama" => $employeeData[$spec->employee_id]['nama'],
                        "cabang_id" => $employeeData[$spec->employee_id]['cabang_id'],
                        "cabang_nama" => isset($cabangData[$employeeData[$spec->employee_id]['cabang_id']]['nama']) ? $cabangData[$employeeData[$spec->employee_id]['cabang_id']]['nama'] : "",
                    );
                }

            }
        }

    }

    return $result;
}

function callNextPICDetail($arrNextAction)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $ci->load->model("Mdls/MdlEmployee");
    $ci->load->model("Mdls/MdlEmployeeCabang");
    $ci->load->model("Mdls/MdlEmployeeGudang");


    $employeeData = array();
    $result = array();

    if (sizeof($arrNextAction) > 0) {
        $nextCode = array();
        $nextStep = array();
        $nextSteps = array();
        $nextCodes = array();
        //        foreach ($arrNextAction as $trID => $spec) {
        //            $nextSteps[$spec['next_step_num']] = $spec['next_step_num'];
        //            $nextCodes[$spec['next_step_code']] = $spec['next_step_code'];
        //        }
        $nextSteps = $arrNextAction['next_step_num'];
        $nextCodes = $arrNextAction['next_step_code'];

        $a = new MdlAccessRight();
        $a->addFilter("steps_code=$nextCodes");
        $a->addFilter("steps=$nextSteps");
        $tmp = $a->lookupAll()->result();


        $e = New MdlEmployee();
        $eResult = $e->lookupAll()->result();
        if (sizeof($eResult) > 0) {
            foreach ($eResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $ec = New MdlEmployeeCabang();
        $ecResult = $ec->lookupAll()->result();
        if (sizeof($ecResult) > 0) {
            foreach ($ecResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $eg = New MdlEmployeeCabang();
        $egResult = $eg->lookupAll()->result();
        if (sizeof($egResult) > 0) {
            foreach ($egResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }


        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {

                if (isset($employeeData[$spec->employee_id])) {
                    //                    $result[$spec->steps_code][$spec->steps][$spec->employee_id] = array(
                    $result[$spec->employee_id] = array(
                        "id" => $spec->employee_id,
                        "nama" => $employeeData[$spec->employee_id]['nama'],
                        "cabang_id" => $employeeData[$spec->employee_id]['cabang_id'],
                    );
                }

            }
        }

    }

    return $result;
}

function overWriteMenuData()
{
    //mdlName =>controller methode
    $confData = array(
        "Meja" => "pengaturan_meja"
    );
    return $confData;
}

//==panggil semua post-processor, kalau semua reversible berarti boleh undo
function evaluatePreProcessors_he_menu($trID, $stepNum, $configCoreJenis, $configUiJenis)
{
    $result = null;
    $ci =& get_instance();
    $jenisTrTarget = isset($configUiJenis['steps'][$stepNum]['target']) ? $configUiJenis['steps'][$stepNum]['target'] : NULL;
    $preProcessors_d = isset($configCoreJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
    $preProcessors_m = isset($configCoreJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreJenis['preProcessor'][$jenisTrTarget]['master'] : array();

    $preProcessors_rev = null != ($ci->config->item('hePreProcessors')) ? $ci->config->item('hePreProcessors') : array();
    //    arrprint($preProcessors_rev);
    //    cekbiru("evaluating pre-procs $trID $stepNum....");

    if (sizeof($preProcessors_d) > 0 || sizeof($preProcessors_m) > 0) {
        $jmlReversible = 0;
        $jmlIrreversible = 0;
        if (sizeof($preProcessors_d) > 0) {

            foreach ($preProcessors_d as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    //                    cekbiru ("$comName is reversable");
                    $jmlReversible++;
                }
                else {
                    //                    cekbiru ("$comName is NOT reversable");
                    $jmlIrreversible++;
                }
            }

        }

        if (sizeof($preProcessors_m) > 0) {

            foreach ($preProcessors_m as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }


    }
    else {
        //        cekhijau("preprocs DONT exist");
    }

    return $result;
}

function evaluatePostProcessors_he_menu($trID, $stepNum, $configCoreJenis, $configUiJenis)
{
    $result = null;
    $ci =& get_instance();
    $jenisTrTarget = isset($configUiJenis['steps'][$stepNum]['target']) ? $configUiJenis['steps'][$stepNum]['target'] : NULL;
    $preProcessors_d = isset($configCoreJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
    $preProcessors_m = isset($configCoreJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreJenis['postProcessor'][$jenisTrTarget]['master'] : array();
    $preProcessors_rev = null != ($ci->config->item('hePostProcessors')) ? $ci->config->item('hePostProcessors') : array();


    if (sizeof($preProcessors_d) > 0 || sizeof($preProcessors_m) > 0) {
        $jmlReversible = 0;
        $jmlIrreversible = 0;
        if (sizeof($preProcessors_d) > 0) {

            foreach ($preProcessors_d as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if (sizeof($preProcessors_m) > 0) {
            foreach ($preProcessors_m as $cSpec) {
                $comName = $cSpec['comName'];
                if (array_key_exists($comName, $preProcessors_rev)) {
                    $jmlReversible++;
                }
                else {
                    $jmlIrreversible++;
                }
            }
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }


    }
    else {
        //        cekhijau("preprocs DONT exist");
    }

    return $result;
}

function evaluateComponents_he_menu($trID, $stepNum, $configCoreJenis, $configUiJenis)
{
    $ci =& get_instance();
    $jenisTrTarget = isset($configUiJenis['steps'][$stepNum]['target']) ? $configUiJenis['steps'][$stepNum]['target'] : NULL;
    $mComponents = isset($configCoreJenis['components'][$jenisTrTarget]['master']) ? $configCoreJenis['components'][$jenisTrTarget]['master'] : array();
    $result = null;
    $jmlReversible = 0;
    $jmlIrreversible = 0;
    if (sizeof($mComponents) > 0) {
        $comNames = array();
        foreach ($mComponents as $cSpec) {
            $comNames[] = $cSpec['comName'];
        }
        if (in_array("Jurnal", $comNames)) {
            $jmlIrreversible++;

            if ($jmlIrreversible > 0) {
                $result = $jmlIrreversible;
            }
        }
    }

    return $result;
}

function evaluateMain_he_menu($trJenis, $stepNum, $configCoreJenis, $configUiJenis)
{

    $ci =& get_instance();
    $mComponents = isset($configUiJenis['allowedMainEdit']) ? $configUiJenis['allowedMainEdit'] : array();
    $result = null;
    $jmlReversible = 0;
    $jmlIrreversible = 0;
    if (sizeof($mComponents) > 0) {
        if (in_array($stepNum, $mComponents)) {
            $jmlReversible++;
        }
        else {
            $jmlIrreversible++;
        }

        if ($jmlIrreversible > 0) {
            $result = $jmlIrreversible;
        }
    }
    else {
        $result = 1;
    }

    return $result;
}

function produksiMenu()
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlProdukRakitan");
    $ci->load->model("Mdls/MdlProdukFase");
    $m = new MdlProdukRakitan();
    $f = new MdlProdukFase();
    $ci->db->order_by("nama", "asc");
    $m->addFilter("status_manufactur=1");
    $tempdata = $m->lookUpAll()->result();
    // $f->setSortBy(array("kolom"=>"urut","mode"=>"asc"));
    $tempFase = $f->conectedProduct();
    // cekKuning($tempdata);
    // arrPrintWebs($tempFase);

    $menus = array();
    if (sizeof($tempdata) > 0) {
        foreach ($tempdata as $tempData_0) {
            // $menus[$tempData_0->cabang_id][$tempData_0->id] = $tempData_0->nama;
            $menus[$tempData_0->cabang_id][$tempData_0->id] = array(
                "jenis" => $tempData_0->jenis_master,
                "nama" => $tempData_0->nama . "**",
            );
        }
    }
    return $menus;
    // arrPrint($tempdata);
}

function produksiFaseMenu()
{
    $ci =& get_instance();
    // $ci->load->model("Mdls/MdlProdukRakitan");
    $ci->load->model("Mdls/MdlProdukFase");
    // $m = new MdlProdukRakitan();
    $f = new MdlProdukFase();
    // $ci->db->order_by("nama","asc");
    // $tempdata = $m->lookUpAll()->result();
    // $f->setSortBy(array("kolom"=>"urut","mode"=>"asc"));
    $tempFase = $f->conectedProduct();
    return $tempFase;
    // arrPrint($tempFase);
    //
    // matiHere(__FUNCTION." LINE ".__LINE__);
    // arrPrint($tempdata);
}

//---------------
function produksiMenu_he_menu($cabang_id)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlProdukRakitan");
    $ci->load->model("Mdls/MdlProdukFase");
    $m = new MdlProdukRakitan();
    $f = new MdlProdukFase();
    $ci->db->order_by("nama", "asc");
    $m->addFilter("cabang_id=$cabang_id");
    $m->addFilter("status_manufactur=1");
    $tempdata = $m->lookUpAll()->result();
    showLast_query("orange");
    //    arrPrintWebs($tempdata);
    $pIDs = array();
    $pIDs_data = array();
    if (sizeof($tempdata) > 0) {
        foreach ($tempdata as $tempdataSpec) {
            $pIDs[] = $tempdataSpec->id;
            $pIDs_data[$tempdataSpec->id] = (array)$tempdataSpec;
        }
    }
    //    arrPrintPink($pIDs_data);


    $f->addFilter("produk_id in ('" . implode("','", $pIDs) . "')");
    $tempFase = $f->conectedProduct();
    //    showLast_query("orange");
    // cekKuning($tempdata);
    //    arrPrintWebs($tempFase);

    $menus = array();
    $menusLabel = array();
    //    if (sizeof($tempdata) > 0) {
    //        foreach ($tempdata as $tempData_0) {
    //            $menus[$tempData_0->cabang_id][$tempData_0->id] = array(
    //                "jenis" => $tempData_0->jenis_master,
    //                "nama" => $tempData_0->nama,
    //            );
    //        }
    //    }
    if (sizeof($tempFase) > 0) {
        foreach ($tempFase as $pID => $faseSpecs) {
            foreach ($faseSpecs as $faseSpec) {
                //                arrPrint($faseSpec);
                $menus[$pID][$faseSpec['kode']][$faseSpec['urut']] = $faseSpec['nama'];
                $menusLabel[$pID][$faseSpec['kode']] = $faseSpec;
            }
        }
    }

    //    arrPrintHijau($menusLabel);

    $arrMenus = array(
        "produk" => $pIDs_data,
        "menu_fase" => $menus,
        "menu_fase_label" => $menusLabel,
    );
    // arrprint($arrMenus);
    return $arrMenus;
    // arrPrint($tempdata);
}

//tambahan detektor stepcode hak akses, 21 maret 2023-------------------------
function stepCodeByEmployeeID($stepCode, $employeeID)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $a->addFilter("steps_code=$stepCode");
    $a->addFilter("employee_id=$employeeID");
    $tmp = $a->lookupAll()->result();
    if (sizeof($tmp) > 0) {
        $result = true;
    }
    else {
        $result = false;
    }

    return $result;
}

function callTutorialOnTop()
{
    $ci =& get_instance();
    $ci->load->config("heVideos");
    $videos = $ci->config->item("videos_top");
    $modul = url_segment(1);
    $ctr = url_segment(2);
    $classActive = strtolower(url_segment(4));
    // cekHere(url_segment());
    // arrPrint($videos[$modul]);
    $videosActive = isset($videos[$modul][$classActive]) ? $videos[$modul][$classActive] : array();
    $jml_videoActive = count($videosActive);

    $str_jml_videoActive = "";
    if ($jml_videoActive > 0) {

        $str_jml_videoActive = $jml_videoActive;
        $text_header = "$jml_videoActive tutorial $ctr/$classActive";
    }
    else {
        $text_header = "tidak tersedia turorial";
    }
    // cekHere($videosActive);

    $str = "";
    $str .= "<style type='text/css'>
        .menus {
            padding-left: 10px;
        }
    </style>";
    $str .= "<ul class='nav navbar-nav'>";

    // $str .= "<li class='dropdown messages-menu'>
    //     <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
    //     <i class='fa fa-envelope-o'></i>
    //     <span class='label label-success'>4</span>
    //     </a>
    //
    //         <ul class='dropdown-menu'>
    //     <li class='header'>You have 4 messages</li>
    //     <li>
    //
    //     <ul class='menu'>
    //         <li>
    //             <a href='#'>
    //                 <div class='pull-left'>
    //                     <img src='dist/img/user2-160x160.jpg' class='img-circle' alt='User Image'>
    //                 </div>
    //                 <h4>
    //                 Support Team
    //                 <small><i class='fa fa-clock-o'></i> 5 mins</small>
    //                 </h4>
    //                 <p>Why not buy a new awesome theme?</p>
    //             </a>
    //         </li>
    //
    //         <li>
    //         <a href='#'>
    //         <div class='pull-left'>
    //         <img src='dist/img/user3-128x128.jpg' class='img-circle' alt='User Image'>
    //         </div>
    //         <h4>
    //         AdminLTE Design Team
    //         <small><i class='fa fa-clock-o'></i> 2 hours</small>
    //         </h4>
    //         <p>Why not buy a new awesome theme?</p>
    //         </a>
    //         </li>
    //         <li>
    //         <a href='#'>
    //         <div class='pull-left'>
    //         <img src='dist/img/user4-128x128.jpg' class='img-circle' alt='User Image'>
    //         </div>
    //         <h4>
    //         Developers
    //         <small><i class='fa fa-clock-o'></i> Today</small>
    //         </h4>
    //         <p>Why not buy a new awesome theme?</p>
    //         </a>
    //         </li>
    //         <li>
    //         <a href='#'>
    //         <div class='pull-left'>
    //         <img src='dist/img/user3-128x128.jpg' class='img-circle' alt='User Image'>
    //         </div>
    //         <h4>
    //         Sales Department
    //         <small><i class='fa fa-clock-o'></i> Yesterday</small>
    //         </h4>
    //         <p>Why not buy a new awesome theme?</p>
    //         </a>
    //         </li>
    //         <li>
    //         <a href='#'>
    //         <div class='pull-left'>
    //         <img src='dist/img/user4-128x128.jpg' class='img-circle' alt='User Image'>
    //         </div>
    //         <h4>
    //         Reviewers
    //         <small><i class='fa fa-clock-o'></i> 2 days</small>
    //         </h4>
    //         <p>Why not buy a new awesome theme?</p>
    //         </a>
    //         </li>
    //         </ul>
    //
    //         </li>
    //         <li class='footer'><a href='#'>See All Messages</a></li>
    //         </ul>
    //     </li>";


    $str .= "<li class='dropdown notifications-menu' style='font-size:25px;'>";
    $str .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown' title='tutorial'>
        <i class='fa fa-youtube-play'></i> 
        <span class='label label-danger' style='font-size:15px;'>$str_jml_videoActive</span>
        </a>";

    /* ------------------------------------------------------------
     * header
     * ------------------------------------------------------------*/
    $str .= "<ul class='dropdown-menu'>";
    $str .= "<li class='header text-uppercase text-bold'>$text_header</li>";

    $str .= "<li>";

    $str .= "<ul class='menus'>";
    foreach ($videosActive as $video_embed => $video_caption) {

        $embedlink = blobEncode($video_embed);
        $link_video = base_url() . "Embed/Youtube?e=$embedlink";
        $modal_video = modalDialogBtn('Video Tutorial', $link_video);

        $str .= "<li>
            <a href='JavaScript:Void(0);' onclick=\"$modal_video\">
            <i class='fa fa-play-circle text-aqua'></i> $video_caption
            </a>
            </li>";
    }
    $str .= "</ul>";    // menu

    $str .= "</li>";    // dropdown inner

    /* ------------------------------------------------------------
     * footer
     * ------------------------------------------------------------*/
    $linkHome = base_url();
    $str .= "<li class='footer'><a href='$linkHome'>Lihat semua</a></li>";
    // ------------------------------------------------------------end footer
    $str .= "</ul>";    // dropdown-menu outer

    $str .= "</li>";    // notifications-menu

    // $str .= "<li class='dropdown tasks-menu'>
    // <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
    // <i class='fa fa-flag-o'></i>
    // <span class='label label-danger'>900</span>
    // </a>
    // <ul class='dropdown-menu'>
    // <li class='header'>You have 9 tasks</li>
    // <li>
    //
    // <ul class='menu'>
    // <li>
    // <a href='#'>
    // <h3>
    // Design some buttons
    // <small class='pull-right'>20%</small>
    // </h3>
    // <div class='progress xs'>
    // <div class='progress-bar progress-bar-aqua' style='width: 20%' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'>
    // <span class='sr-only'>20% Complete</span>
    // </div>
    // </div>
    // </a>
    // </li>
    //
    // <li>
    // <a href='#'>
    // <h3>
    // Create a nice theme
    // <small class='pull-right'>40%</small>
    // </h3>
    // <div class='progress xs'>
    // <div class='progress-bar progress-bar-green' style='width: 40%' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'>
    // <span class='sr-only'>40% Complete</span>
    // </div>
    // </div>
    // </a>
    // </li>
    //
    // <li>
    // <a href='#'>
    // <h3>
    // Some task I need to do
    // <small class='pull-right'>60%</small>
    // </h3>
    // <div class='progress xs'>
    // <div class='progress-bar progress-bar-red' style='width: 60%' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'>
    // <span class='sr-only'>60% Complete</span>
    // </div>
    // </div>
    // </a>
    // </li>
    //
    // <li>
    // <a href='#'>
    // <h3>
    // Make beautiful transitions
    // <small class='pull-right'>80%</small>
    // </h3>
    // <div class='progress xs'>
    // <div class='progress-bar progress-bar-yellow' style='width: 80%' role='progressbar' aria-valuenow='20' aria-valuemin='0' aria-valuemax='100'>
    // <span class='sr-only'>80% Complete</span>
    // </div>
    // </div>
    // </a>
    // </li>
    //
    // </ul>
    // </li>
    // <li class='footer'>
    // <a href='#'>View all tasks</a>
    // </li>
    // </ul>
    // </li>";

    $str .= "<li class='dropdown user user-menu'>
        <a href='#' class='dropdown-toggle' data-toggle='dropdownn'>
        <img src='" . img_profile_default() . "' class='user-image' alt='User Image'>
        <span class='hidden-xs text-capitalize'>{my_name}</span>
        </a>
    
        <ul class='dropdown-menu'>
        
            <li class='user-header'>
                <imgg src='" . img_profile_default() . "' class='img-circle' alt='User Image'>
                <p>
                Alexander Pierce - Web Developer
                <small>Member since Nov. 2012</small>
                </p>
            </li>
            
            <li class='user-body'>
                <div class='row'>
                <div class='col-xs-4 text-center'>
                <a href='#'>Followers</a>
                </div>
                <div class='col-xs-4 text-center'>
                <a href='#'>Sales</a>
                </div>
                <div class='col-xs-4 text-center'>
                <a href='#'>Friends</a>
                </div>
                </div>            
            </li>
            
            <li class='user-footer'>
                <div class='pull-left'>
                <a href='#' class='btn btn-default btn-flat'>Profile</a>
                </div>
                <div class='pull-right'>
                <a href='#' class='btn btn-default btn-flat'>Sign out</a>
                </div>
            </li>
        </ul>
    </li>";


    /*
     * side bar
     * */
    // $str .= "<li>
    // <a href='#' data-toggle='control-sidebar'><i class='fa fa-gears'></i></a>
    // </li>";

    $str .= "</ul>";

    return $str;
}


function menuRekening()
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlAccounts");

    $ma = New MdlAccounts();
    $maTmp = $ma->lookUpTransactionStructureLv1();
    $maLabelTmp = $ma->lookUpTransactionStructureLabel();
    showLast_query("biru");
    //        arrPrintWebs($maTmp);
    $arrRekening = array();
    foreach ($maTmp as $cat => $iiSpec) {
        foreach ($iiSpec as $coa) {
            $nama = isset($maLabelTmp[$coa]) ? $maLabelTmp[$coa] : $coa;
            $arrRekening[$cat][$coa] = $nama;
        }
    }
    //    arrPrintWebs($arrRekening);
    return $arrRekening;


}


function callPICData($arrNextAction, $mdlName)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlDataAccessRight");
    $ci->load->model("Mdls/MdlEmployee");
    $ci->load->model("Mdls/MdlEmployeeCabang");
    $ci->load->model("Mdls/MdlEmployeeGudang");
    $ci->load->model("Mdls/MdlCabang");


    $employeeData = array();
    $result = array();

    if (sizeof($arrNextAction) > 0) {

        $a = new MdlDataAccessRight();
        $a->addFilter("mdl_name='$mdlName'");
        $a->addFilter("steps in ('" . implode("','", $arrNextAction) . "')");
        $tmp = $a->lookupAll()->result();


        $e = New MdlEmployee();
        $eResult = $e->lookupAll()->result();
        if (sizeof($eResult) > 0) {
            foreach ($eResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }


        $ec = New MdlEmployeeCabang();
        $ecResult = $ec->lookupAll()->result();
        if (sizeof($ecResult) > 0) {
            foreach ($ecResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }


        $eg = New MdlEmployeeCabang();
        $egResult = $eg->lookupAll()->result();
        if (sizeof($egResult) > 0) {
            foreach ($egResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }


        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {

                if (isset($employeeData[$spec->employee_id])) {
                    $result[$spec->employee_id] = $employeeData[$spec->employee_id]["nama"];
                }

            }
        }

    }

    return $result;
}

function callNextPICName($arrNextAction, $cabang_id = NULL)
{
    $ci =& get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $ci->load->model("Mdls/MdlEmployee");
    $ci->load->model("Mdls/MdlEmployeeCabang");
    $ci->load->model("Mdls/MdlEmployeeGudang");
    $ci->load->model("Mdls/MdlCabang");


    $employeeData = array();
    $result = array();
//arrPrint($arrNextAction);
    if (sizeof($arrNextAction) > 0) {
        $nextCode = array();
        $nextStep = array();
        $nextSteps = array();
        $nextCodes = array();
        foreach ($arrNextAction as $trID => $spec) {
//            $nextSteps[$spec['next_step_num']] = $spec['next_step_num'];
//            $nextCodes[$spec['next_step_code']] = $spec['next_step_code'];
            if (isset($spec['next_step_num']) && ($spec['next_step_num'] != NULL)) {
                $nextSteps[$spec['next_step_num']] = $spec['next_step_num'];
            }
            if (isset($spec['next_step_code']) && ($spec['next_step_code'] != NULL)) {
                $nextCodes[$spec['next_step_code']] = $spec['next_step_code'];
            }
        }


        $a = new MdlAccessRight();
        if (sizeof($nextCodes) > 0) {
            $ci->db->where("steps_code in ('" . implode("','", $nextCodes) . "')");
        }
        if (sizeof($nextSteps) > 0) {
            $ci->db->where("steps in ('" . implode("','", $nextSteps) . "')");
        }
        $tmp = $a->lookupAll()->result();
        showLast_query("biru");

        $e = New MdlEmployee();
        if($cabang_id != NULL){
            $e->addFilter("cabang_id='$cabang_id'");
        }
        $eResult = $e->lookupAll()->result();
        if (sizeof($eResult) > 0) {
            foreach ($eResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $ec = New MdlEmployeeCabang();
        if($cabang_id != NULL){
            $ec->addFilter("cabang_id='$cabang_id'");
        }
        $ecResult = $ec->lookupAll()->result();
        if (sizeof($ecResult) > 0) {
            foreach ($ecResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $eg = New MdlEmployeeCabang();
        if($cabang_id != NULL){
            $eg->addFilter("cabang_id='$cabang_id'");
        }
        $egResult = $eg->lookupAll()->result();
        if (sizeof($egResult) > 0) {
            foreach ($egResult as $spec) {
                $employeeData[$spec->id] = array(
                    "nama" => $spec->nama,
                    "cabang_id" => $spec->cabang_id,
                );
            }
        }

        $cb = New MdlCabang();
        $cbResult = $cb->lookupAll()->result();
        if (sizeof($cbResult) > 0) {
            foreach ($cbResult as $spec) {
                $cabangData[$spec->id] = array(
                    "nama" => $spec->nama,
                );
            }
        }

        //arrPrintWebs($tmp);
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $spec) {
                if (isset($employeeData[$spec->employee_id])) {
                    $result_pic[$spec->steps_code][$spec->steps][$spec->employee_id] = array(
                        "id" => $spec->employee_id,
                        "nama" => $employeeData[$spec->employee_id]['nama'],
                        "cabang_id" => $employeeData[$spec->employee_id]['cabang_id'],
                        "cabang_nama" => isset($cabangData[$employeeData[$spec->employee_id]['cabang_id']]['nama']) ? $cabangData[$employeeData[$spec->employee_id]['cabang_id']]['nama'] : "",
                    );
                }

            }
            if (sizeof($result_pic) > 0) {
                $pic_nama = array();
                if (sizeof($result_pic) > 0) {
                    foreach ($result_pic as $nSpec) {
                        foreach ($nSpec as $nnSpec) {
                            foreach ($nnSpec as $nnnSpec) {
                                $pic_nama[$nnnSpec["nama"]] = $nnnSpec["nama"];
                            }
                        }
                    }
                }
//                $result = $pic_nama_implode = (sizeof($pic_nama) > 0) ? "PIC retur penjualan: " . implode(", ", $pic_nama) : "";
                $result = $pic_nama_implode = (sizeof($pic_nama) > 0) ? " " . implode(", ", $pic_nama) : "";

            }
        }

    }

    return $result;
}


