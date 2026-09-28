<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 12/09/2019
 * Time: 14:00
 */

function callAvailTransaction()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = array(
                "source" => $stepDetails["source"],
                "target" => $stepDetails["target"],
            );
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function availGroupAccess()
{
    $ci = &get_instance();

    $membership = $ci->session->login['membership'];
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);

    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        $tempAvail = array();
        if (in_array($place, $availPlace)) {

            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $access_group = $stepDetails["userGroup"];
                if (in_array($access_group, $availGroup_allowed)) {
                    $tempAvail[$steps][] = $access_group;
                }
            }
            $availStepTemp[$place][$jenis] = $tempAvail;
        }

    }

    $temData = array();
    foreach ($availStepTemp as $place => $tempG) {
        $temp = array();
        foreach ($tempG as $gJenisTr => $gAvail) {
            foreach ($gAvail as $gId => $step) {
                $temp[$gId][$gJenisTr] = $step;
            }
        }
        $temData[$place] = $temp;
    }


    return $availStepTemp;
}

function groupAlias()
{
    $ci = &get_instance();
    $gDev = $ci->config->item("userGroup_root");
    $gCenter = $ci->config->item("userGroup");
    $gBrach = $ci->config->item("userGroup_cabang");
    $gWarehouse = $ci->config->item("userGroup_gudang");

    $groupAlias = array_merge($gDev, $gCenter, $gBrach, $gWarehouse);
    return array_flip($groupAlias);

}

function transactionStepAlias()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = $steps_label;
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function transactionJenisAlias()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();

        $availStepTemp[$jenis] = $details["label"];
    }
    return $availStepTemp;
}

function availCreatorCenter($place)
{
    //    cekHitam($place);
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"][1];
        $place2 = $details['place'];

        $availStepTemp[$place2][$jenis] = 1;
    }
    $temp = array();
    foreach ($availStepTemp as $placeID => $placeData) {
        //        cekHere("$placeID**");
        if ($placeID == $place) {

        }
        else {
            foreach ($placeData as $jn => $value) {
                $temp[$jn] = array($value => "0");
            }
        }

    }
    //    arrPrint($temp);
    //matiHere();
    return $temp;
}

function alowedAccess($employee_id)
{
    $xT = callAvailTransaction();
    //    $xG = availGroupAccess();
    //    $xGaliasing = groupAlias();
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $customAcces = $a->lookupAll()->result();
    //cekHitam($ci->db->last_query());
    //arrPrint($xT);
    //arrPrint($customAcces);
    if (sizeof($customAcces) > 0) {
        $eAvail = array();
        foreach ($customAcces as $eData) {
            //            arrPrint($eData);
            if (isset($xT[$eData->menu_category][$eData->steps])) {
                $allowCreate = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? false : true;
                $allowFollowUp = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? true : false;
                //                $allowFollowUp = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? true : true;


                $allowReject = $eData->steps > 1 ? true : false;
                $allowDelete = $eData->steps == 1 ? true : false;
                $allowEdit = $eData->steps == 1 ? true : false;
                $allowUndo = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['target']) > 0 ? true : false;

                $eAvail[$eData->menu_category][$eData->steps][$eData->steps_code] = array(
                    "allowCreate" => $allowCreate,
                    "allowFollowUp" => $allowFollowUp,
                    "allowReject" => $allowReject,
                    "allowDelete" => $allowDelete,
                    "allowEdit" => $allowEdit,
                    "allowUndo" => $allowUndo,
                );
            }
        }
    }
    else {
        $eAvail = array();
    }

    return $eAvail;


}

function subPlaceStep()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        foreach ($steps as $step => $stepDetails) {
            if (isset($stepDetails["subplace"])) {

                $subplace = $stepDetails["subplace"];
                $availStepTemp[$jenis][$step] = $subplace;
            }

        }
    }
    return $availStepTemp;
}

//---------------------------
function factoryAccess()
{
    $ci = &get_instance();

    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);
    //arrPrintWebs($availGroup_allowed);
    $placeExtend = "factory";

    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        if (isset($details['placeExtended']) && ($details['placeExtended'] == $placeExtend)) {
            $steps = $details["steps"];
            $parentLabels = $details["label"];
            $place = $details["place"];
            $tempAvail = array();
            if (in_array($place, $availPlace)) {
                foreach ($steps as $steps => $stepDetails) {
                    $steps_label = $stepDetails["label"];
                    $access_group = $stepDetails["userGroup"];

                    if (in_array($access_group, $availGroup_allowed)) {

                        $tempAvail[$steps][] = $access_group;
                    }
                }
                $availStepTemp[$place][$jenis] = $tempAvail;
            }
        }

    }

    return $availStepTemp;
}

//-VERSI MODUL---------------
function callAvailTransaction_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = array(
                "source" => $stepDetails["source"],
                "target" => $stepDetails["target"],
            );
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function availGroupAccess_he_access_right($configUi)
{
    $ci = &get_instance();

    $membership = $ci->session->login['membership'];
    $availTrans = $configUi;
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);

    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        $tempAvail = array();
        if (in_array($place, $availPlace)) {

            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $access_group = $stepDetails["userGroup"];
                if (in_array($access_group, $availGroup_allowed)) {
                    $tempAvail[$steps][] = $access_group;
                }
            }
            $availStepTemp[$place][$jenis] = $tempAvail;
        }

    }

    $temData = array();
    foreach ($availStepTemp as $place => $tempG) {
        $temp = array();
        foreach ($tempG as $gJenisTr => $gAvail) {
            foreach ($gAvail as $gId => $step) {
                $temp[$gId][$gJenisTr] = $step;
            }
        }
        $temData[$place] = $temp;
    }


    return $availStepTemp;
}

function transactionStepAlias_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();
        foreach ($steps as $steps => $stepDetails) {
            $steps_label = $stepDetails["label"];
            $tempAvail[$steps] = $steps_label;
        }
        $availStepTemp[$jenis] = $tempAvail;
    }
    return $availStepTemp;
}

function transactionJenisAlias_he_access_right($configUi)
{
    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $tempAvail = array();

        $availStepTemp[$jenis] = $details["label"];
    }
    return $availStepTemp;
}

function availCreatorCenter_he_access_right($place, $configUi)
{

    $ci = &get_instance();
    $availTrans = $configUi;
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"][1];
        $place2 = $details['place'];

        $availStepTemp[$place2][$jenis] = 1;
    }
    $temp = array();
    foreach ($availStepTemp as $placeID => $placeData) {
        //        cekHere("$placeID**");
        if ($placeID == $place) {

        }
        else {
            foreach ($placeData as $jn => $value) {
                $temp[$jn] = array($value => "0");
            }
        }

    }

    return $temp;
}

function alowedAccess_he_access_right($employee_id, $configUi)
{
    $xT = callAvailTransaction_he_access_right($configUi);
    //    $xG = availGroupAccess();
    //    $xGaliasing = groupAlias();
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $customAcces = $a->lookupAll()->result();
    //cekHitam($ci->db->last_query());
    //arrPrint($xT);
    //arrPrint($customAcces);
    if (sizeof($customAcces) > 0) {
        $eAvail = array();
        foreach ($customAcces as $eData) {
            //            arrPrint($eData);
            if (isset($xT[$eData->menu_category][$eData->steps])) {
                $allowCreate = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? false : true;
                $allowFollowUp = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? true : false;
                //                $allowFollowUp = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? true : true;


                $allowReject = $eData->steps > 1 ? true : false;
                $allowDelete = $eData->steps == 1 ? true : false;
                $allowEdit = $eData->steps == 1 ? true : false;
                $allowUndo = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['target']) > 0 ? true : false;

                $eAvail[$eData->menu_category][$eData->steps][$eData->steps_code] = array(
                    "allowCreate" => $allowCreate,
                    "allowFollowUp" => $allowFollowUp,
                    "allowReject" => $allowReject,
                    "allowDelete" => $allowDelete,
                    "allowEdit" => $allowEdit,
                    "allowUndo" => $allowUndo,
                );
            }
        }
    }
    else {
        $eAvail = array();
    }

    return $eAvail;


}

//----------------------
function groupAccessLabel_he_access_right()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $arrGroupLabel = array();
    foreach ($availTrans as $jenis => $spec) {
        //arrPrint($spec);
        foreach ($spec['steps'] as $step => $subSpec) {
            //            cekHere($subSpec['userGroup']);
            if (!isset($arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'])) {
                $arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'] = array();
            }
            $arrGroupLabel[$subSpec['userGroup']][$jenis]['label'] = $spec['label'];
            $arrGroupLabel[$subSpec['userGroup']][$jenis]['sublabel'][] = $subSpec['label'];
        }
    }

    //    arrPrintWebs($arrGroupLabel);
    return $arrGroupLabel;
}

function customAccessJenis_he_access_right($employee_id, $jenisTr, $configUiJenis)
{

    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRight");
    $a = new MdlAccessRight();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $a->addFilter("menu_category='$jenisTr'");
    $customAcces = $a->lookupAll()->result();
    //    $customAcces = array();
    if (sizeof($customAcces) > 0) {
        if (sizeof($customAcces) == 1) {
            // hanya 1, dan create maka masuk ke create (step 1)
            if ($customAcces[0]->steps == 1) {
                $link_redirect = MODUL_PATH . "Create/index/$jenisTr?gr=$getGr";
            }
            // hanya 1, dan bukan create maka masuk ke index (step > 1)
            else {
                //                    $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                $link_redirect = NULL;
            }
        }
        else {
            // lebih dari 1, maka masuk ke index
            //                $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
            $link_redirect = NULL;
        }
    }
    else {
        // tidak punya hak akses custom $this->>jenisTr .....
        $link_redirect = NULL;
        $mems = $ci->session->login['membership'];
        $groups = array();
        foreach ($configUiJenis['steps'] as $step => $data) {
            $groups[$data['userGroup']] = $step;
        }

        $groupsResult = array();
        foreach ($mems as $membership) {
            if (array_key_exists($membership, $groups)) {
                $groupsResult[] = $groups[$membership];
            }
        }

        if (sizeof($groupsResult) > 0) {
            if (sizeof($groupsResult) == 1) {
                // hanya 1, dan create maka masuk ke create (step 1)
                if ($groupsResult[0] == 1) {
                    $link_redirect = MODUL_PATH . "Create/index/$jenisTr?gr=$getGr";
                }
                // hanya 1, dan bukan create maka masuk ke index (step > 1)
                else {
                    //                    $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                    $link_redirect = NULL;
                }
            }
            else {
                // lebih dari 1, maka masuk ke index
                //                $link_redirect = MODUL_PATH . "Transaksi/index/$jenisTr?gr=$getGr";
                $link_redirect = NULL;
            }
        }
    }


    return $link_redirect;
}

//----------------------
function alowedAccessManufactur_he_access_right($employee_id)
{
    //    $xT = callAvailTransaction();
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlAccessRightManufactur");
    $a = new MdlAccessRightManufactur();
    $a->addFilter("trash='0'");
    $a->addFilter("employee_id='$employee_id'");
    $customAcces = $a->lookupAll()->result();
    //    cekHere($ci->db->last_query());
    if (sizeof($customAcces) > 0) {
        $eAvail = array();
        foreach ($customAcces as $eData) {
            //            if (isset($xT[$eData->menu_category][$eData->steps])) {
            //                $allowCreate = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['source']) > 0 ? false : true;
            //
            //                $allowReject = $eData->steps > 1 ? true : false;
            //                $allowDelete = $eData->steps == 1 ? true : false;
            //                $allowEdit = $eData->steps == 1 ? true : false;
            //                $allowUndo = isset($xT[$eData->menu_category][$eData->steps]) && strlen($xT[$eData->menu_category][$eData->steps]['target']) > 0 ? true : false;
            $allowCreate = true;
            $eAvail[$eData->menu_category][$eData->steps][$eData->steps_code] = array(
                "allowCreate" => $allowCreate,
                //                    "allowFollowUp" => $allowFollowUp,
                //                    "allowReject" => $allowReject,
                //                    "allowDelete" => $allowDelete,
                //                    "allowEdit" => $allowEdit,
                //                    "allowUndo" => $allowUndo,
            );
            //            }
        }
    }
    else {
        $eAvail = array();
    }

    return $eAvail;


}

function customProjectAccess($id, $membersip, $status_produk = "0")
{
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProjectAccessList");
    $ci->load->model("Mdls/MdlProjectAccessMember");
    $m = new MdlProjectAccessList();
    $ml = new MdlProjectAccessMember();
    $tmp = $m->lookUpAll()->result();
    $ml->addFilter("per_employee_id='$id'");
    $tmpMember = $ml->lookUpAll()->result();
    //     arrPrint($tmp);
    // cekBiru($ci->db->last_query());
    $dataGroup = array();
    if (count($tmp) > 0) {
        foreach ($tmp as $tmp_0) {
            $dataGroup[$tmp_0->access_id][$tmp_0->access_metode][] = $tmp_0->mdl_name;
        }
    }

    $dataMemAllowed = array();
    if (count($tmpMember) > 0) {
        if ($membersip == "o_project_spv") {
            $dataMemAllowed[$id] = 1;
        }
        else {
            foreach ($tmpMember as $tmpMember_0) {
                $dataMemAllowed[$id] = $tmpMember_0->acc_level;
                // arrPrint()
            }
        }
    }
    else {
        // cekHitam("sini kah $membersip");
        if ($membersip == "o_project_spv") {
            $dataMemAllowed[$id] = 1;
        }
    }
    // arrPrint($dataMemAllowed);
    $data = array();
    if (count($dataMemAllowed) > 0) {
        foreach ($dataMemAllowed as $mid => $master_mid) {
            $data = isset($dataGroup[$master_mid]) ? $dataGroup[$master_mid] : array();
        }
    }
    // arrPrintKuning($data);
    //
    // matiHEre($id);
    return $data;

}

function groupMaster()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $dtaGrup = array();
    foreach ($availTrans as $jenis => $tmp) {
        $grup = isset($tmp["grupMenu"]) ? $tmp["grupMenu"] : 6;
        $dtaGrup[$grup][] = $jenis;

    }
    return $dtaGrup;
}

function groupPlaceMaster()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $dtaGrup = array();
    foreach ($availTrans as $jenis => $tmp) {
        $grup = isset($tmp["grupMenu"]) ? $tmp["grupMenu"] : 6;
        $dtaGrup[$tmp['place']][$grup][] = $jenis;

    }
    return $dtaGrup;
}

function availGroupPlace()
{
    $ci = &get_instance();

    $membership = $ci->session->login['membership'];
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availPlace = $ci->config->item("userPlace_allowed");
    $availGroup = $ci->config->item("userGroup") != null ? array_keys($ci->config->item("userGroup")) : array();
    $availGroup_cabang = $ci->config->item("userGroup_cabang") != null ? array_keys($ci->config->item("userGroup_cabang")) : array();
    $availGroup_gudang = $ci->config->item("userGroup_gudang") != null ? array_keys($ci->config->item("userGroup_gudang")) : array();
    $availGroup_allowed = array_merge($availGroup, $availGroup_cabang, $availGroup_gudang);
    //matiHEre("uu");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $parentLabels = $details["label"];
        $place = $details["place"];
        //        $availStepTemp = array();
        if (in_array($place, $availPlace)) {

            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                if ($steps > 1) {
                    $availStepTemp[$place][] = $jenis;
                }
                //                $access_group = $stepDetails["userGroup"];
                //                if (in_array($access_group, $availGroup_allowed)) {
                ////                    cekHere("jenis: $jenis, $access_group");
                //                    $tempAvail[$steps][] = $access_group;
                //                }
            }
            //            $availStepTemp[$place][$jenis] = $tempAvail;
        }
        //        arrPrint($availStepTemp);

    }

    //arrPrint($availStepTemp);
    //    matiHEre();

    return $availStepTemp;
}

function dataLabel()
{
    $data = array(
        "viewers" => "lihat",
        "creators" => "tambah ",
        "updaters" => "ubah/perbaharui ",
        "deleters" => "hapus",
        "historyViewers" => "histori",
    );
    return $data;
}

function dataAdditionalLabel()
{
    $data = array(
        "allow" => "lihat",
        // "creators"       => "tambah ",
        // "updaters"       => "ubah/perbaharui ",
        // "deleters"       => "hapus",
        // "historyViewers" => "histori",
    );
    return $data;
}

/*
 * baca dari data behavior semua model yang avaible
 */
function availMenuConfigData()
{
    $ci = &get_instance();
    $dataModel = $ci->config->item("heDataBehaviour");
    // arrprint($dataModel);
    $dataTmp = array();
    foreach ($dataModel as $MdlName => $data_0) {
        $dataTmp[$MdlName] = array(
            "restriction" => isset($data_0["restriction"]) ? $data_0["restriction"] : false,
            "allowedGroup" => isset($data_0["allowedRestriction"]) ? $data_0["allowedRestriction"] : array(),
            "model" => $MdlName,
            "nama" => $data_0["label"],
            "label" => $data_0["label"],
            "default" => isset($data_0["default"]) ? $data_0["default"] : "",
        );
    }
    return $dataTmp;
    // arrPrint($dataTmp);
}

/*
 * baca dari data set menu data sesuai employee
 */
function availMenuData($mployeeid)
{
    $ci = &get_instance();
    $availConfig = availMenuConfigData();
    $membership = $ci->session->login["membership"];
    // arrPrintWebs($availConfig);
    $ci->load->model("Mdls/MdlDataAccessRight");
    $m = new MdlDataAccessRight();
    $m->addFilter("employee_id='$mployeeid'");

    $tmp = $m->lookUpAll()->result();
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $temp_0) {
            $mdlName = $temp_0->mdl_name;
            $steps = $temp_0->steps;
            $label = $availConfig[$mdlName]["label"];
            $tmpLabel = str_replace("Mdl", "", $mdlName);
            // cekMerah($mdl_name." || ".$steps);
            switch ($steps) {
                case "viewers":
                    $datas["dataMenus"][$tmpLabel] = array(
                        "label" => $label . createObjectSuffix($label),
                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                    );
                    $datas["allowedDataMenus"][$mdlName] = $mdlName;


                    break;
                case "creators":
                    $datas["dataMenus"][$tmpLabel] = array(
                        "label" => $label . createObjectSuffix($label),
                        "badge" => "<sup><span id='crdta$tmpLabel'></span><span id='crdtb$tmpLabel'></span></sup>",
                    );
                    $datas["allowedDataMenus"][$mdlName] = $mdlName;
                    break;
            }
            $datas["fungsi"][$mdlName][$steps] = $membership;
        }

    }
    else {
        $datas = array();
    }
    return $datas;
    // arrPrint($datas);
}

/*
 * custom hak akses additional dijakian satu untuk menu yang menggunakan controller
 * cintoh reporting
 * leger
 * mutasi
 */
function availMenuconfigAdditional($jenisAkun)
{
    //jenis akun merupaka super user yang bisa memberikan hak aksesnya ke akun lain misalkan o_holding
    $ci = &get_instance();
    $dataModelSerc = $ci->config->item("groupMenu");
    $dataModel = $ci->config->item("menu")[$jenisAkun];
    // arrPrintPink($dataModelSerc);
    $avaibleGrMenu = array();
    foreach ($dataModelSerc as $grMenu => $grData) {
        // arrPrint($grData);
        foreach ($dataModel as $i => $iKey) {
            if (isset($grData["availMenu"][$iKey])) {
                // cekMErah($iKey);
                $avaibleGrMenu[$grMenu]["label"] = $grData["label"];
                $avaibleGrMenu[$grMenu]["icon"] = $grData["icon"];
                $avaibleGrMenu[$grMenu]["tanpaCbNama"] = isset($grData["tanpaCbNama"]) ? $grData["tanpaCbNama"] : "";
                $avaibleGrMenu[$grMenu]["availMenu"][$iKey] = $grData["availMenu"][$iKey];

            }
        }

    }
    return $avaibleGrMenu;
    // arrPrint($avaibleGrMenu);
}

function availMenuAdditional($jenisAKun)
{
    //jenis akun merupaka super user yang bisa memberikan hak aksesnya ke akun lain misalkan o_holding
    $ci = &get_instance();
    $dataModelSerc = availMenuconfigAdditional($jenisAKun);
    arrPrint($dataModelSerc);
    $avaibleGrMenu = array();
    if (count($dataModelSerc) > 0) {
        foreach ($dataModelSerc as $grMenu => $grData) {

        }
    }

    arrPrint($avaibleGrMenu);
    matiHere(__LINE__);
}

function menuTitleAliasing()
{
    $ci = &get_instance();
    $availTrans = $ci->config->item("heTransaksi_ui");
    $availStepTemp = array();
    foreach ($availTrans as $jenis => $details) {
        $steps = $details["steps"];
        $availStepTemp[$jenis] = isset($details["deskripsi"]) ? $details["deskripsi"] : $details["label"];;
    }
    return $availStepTemp;
}


//------------------------------------
function availHakAkses($jenisTr, $step)
{
    $ci = &get_instance();
    // Ambil daftar PIC berdasarkan hak akses khusus (set_menu)
    $sqlPic = "SELECT e.nama 
           FROM set_menu s
           JOIN per_employee e ON s.employee_id = e.id
           WHERE s.menu_category = ? 
             AND s.steps = ?
             AND s.trash = '0' 
             AND e.status = '1' 
             AND e.ghost = '0' 
             AND e.trash = '0'";
    $queryPic = $ci->db->query($sqlPic, array($jenisTr, $step));
    $picResults = $queryPic->result_array();

    $picNames = array();
    foreach ($picResults as $rowPic) {
        $picNames[] = ucwords($rowPic['nama']);
    }
    $picListStr = !empty($picNames) ? implode(', ', $picNames) : 'Tidak ada PIC kustom';

    return $picListStr;

}

