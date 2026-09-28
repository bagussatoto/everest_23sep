<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 4/19/2019
 * Time: 8:28 PM
 */


// START OF COMPLETE REPEATED LOGIC
function resolveNestedRelativeRules($rules, $sessionMain)
{
    $activeKeys = array();
    foreach ($rules as $triggerField => $valuesSpec) {
        $currentValue = isset($sessionMain[$triggerField]) ? $sessionMain[$triggerField] : null;
        if ($triggerField === 'optionFreelancerID' && ($currentValue === null || $currentValue === '')) {
            $currentValue = 2; // Default to Non-Member
        }
        if ($currentValue !== null && isset($valuesSpec[$currentValue])) {
            $subConfig = $valuesSpec[$currentValue];
            foreach ($subConfig as $key => $val) {
                if (is_array($val)) {
                    // Masih ada level pemicu di bawahnya (rekursif)
                    $subKeys = resolveNestedRelativeRules(array($key => $val), $sessionMain);
                    $activeKeys = array_merge($activeKeys, $subKeys);
                } else {
                    // Ini rujukan key element langsung
                    $activeKeys[] = $val;
                }
            }
        }
    }
    return $activeKeys;
}

function getDescendantsOfField($field, $rules)
{
    $descendants = array();
    if (!is_array($rules)) {
        return $descendants;
    }
    if (isset($rules[$field])) {
        $descendants = array_merge($descendants, collectAllElementsInTree($rules[$field]));
    } else {
        foreach ($rules as $k => $v) {
            if (is_array($v)) {
                $subDesc = getDescendantsOfField($field, $v);
                $descendants = array_merge($descendants, $subDesc);
            }
        }
    }
    return array_unique($descendants);
}

function collectAllElementsInTree($tree)
{
    $elements = array();
    if (!is_array($tree)) {
        return $elements;
    }
    foreach ($tree as $key => $val) {
        if (is_string($key)) {
            $elements[] = $key;
        }
        if (is_string($val)) {
            $elements[] = $val;
        } elseif (is_array($val)) {
            $subElements = collectAllElementsInTree($val);
            $elements = array_merge($elements, $subElements);
        }
    }
    return $elements;
}

function heFetchElement($jenisTr, $elName, $mdlName, $key)
{

//    cekkuning($jenisTr);
//    cekkuning($elName);
//    cekkuning($mdlName);
//    cekkuning($key);


    $ci =& get_instance();
    $ci->load->helper("he_element");


    $cCode = "_TR_" . $jenisTr;

    $allUiConfigs = $ci->config->item('coTransaksiUi');
    if (!$allUiConfigs || !isset($allUiConfigs[$jenisTr])) {
        $allUiConfigs = $ci->config->item('heTransaksi_ui');
    }

    $elementConfigs = isset($allUiConfigs[$jenisTr]['receiptElements']) ? $allUiConfigs[$jenisTr]['receiptElements'] : array();

    // Integrasi Nested Relative Rules dengan Pool
    $nestedPool = isset($allUiConfigs[$jenisTr]['nestedElementsPool']) ? $allUiConfigs[$jenisTr]['nestedElementsPool'] : array();
    $nestedRules = isset($allUiConfigs[$jenisTr]['nestedRelativeRules']) ? $allUiConfigs[$jenisTr]['nestedRelativeRules'] : array();

    // Cascading reset for descendants if parent value is changing
    $oldVal = isset($_SESSION[$cCode]['main'][$elName]) ? $_SESSION[$cCode]['main'][$elName] : null;
    if ($oldVal !== $key) {
        $descendants = getDescendantsOfField($elName, $nestedRules);
        if (is_array($descendants) && sizeof($descendants) > 0) {
            foreach ($descendants as $desc) {
                if (isset($_SESSION[$cCode]['main_elements'][$desc])) {
                    unset($_SESSION[$cCode]['main_elements'][$desc]);
                }
                if (isset($_SESSION[$cCode]['main'][$desc])) {
                    unset($_SESSION[$cCode]['main'][$desc]);
                }
                if (isset($_SESSION[$cCode]['main'])) {
                    foreach ($_SESSION[$cCode]['main'] as $mKey => $mVal) {
                        if (strpos($mKey, $desc . "__") === 0) {
                            unset($_SESSION[$cCode]['main'][$mKey]);
                        }
                    }
                }
            }
        }
    }

    if (sizeof($nestedRules) > 0 && sizeof($nestedPool) > 0) {
        $activeKeys = resolveNestedRelativeRules($nestedRules, isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array());
        foreach ($activeKeys as $actKey) {
            if (isset($nestedPool[$actKey])) {
                $elementConfigs[$actKey] = $nestedPool[$actKey];
            }
        }
    }

    $relElementConfigs = isset($allUiConfigs[$jenisTr]['relativeElements']) ? $allUiConfigs[$jenisTr]['relativeElements'] : array();
    $relOptionConfigs = isset($allUiConfigs[$jenisTr]['relativeOptions']) ? $allUiConfigs[$jenisTr]['relativeOptions'] : array();
//    $metod2RelConfig = isset($allUiConfigs[$jenisTr]['relativeElements']['targetMethod2']) ? $allUiConfigs[$jenisTr]['relativeElements']['targetMethod2'] : array();
    $configRecomData = isset($allUiConfigs[$jenisTr]['pairRecomDataElement']) ? $allUiConfigs[$jenisTr]['pairRecomDataElement'] : array();

//    arrPrint($relElementConfigs);
    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {

                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {

                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }

                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);
//                            showLast_query("biru");
//                            arrPrint($prTemp);
                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
//                                cekHere(":: $fieldID :: $fieldLabel :: $key ::");
                                foreach ($prTemp as $prSpec) {
                                    $colName = $subSpec['pairedModel']['key'];
//                                    cekHitam($prSpec->$colName . " :: $colName ==> $key");
                                    if ($prSpec->$colName == $key) {
                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
//                                        cekKuning("dibuat paired relative -> " . $prSpec->$fieldID);
                                    }
                                }
                            }
                        }

                    }
                }

//                cekHere("== $eSrc == $esName ==");
//                arrPrintWebs($psubSpec);

                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
//                    cekBiru("-- $esName --");
//                    arrPrintWebs($psubSpec);
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }


        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$elName terdaftar pada relElements");

            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {
//                    cekbiru("evaluating value: $iVarName");

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {
//                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
//                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
//                            cekbiru("removing value: $iVarName");
//                        }

                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
//                                unset($_SESSION[$cCode]['main_inputs'][$k_input]);
                                cekbiru("removing value: $k_input");
                            }
                        }
                    }

                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }

    }

//arrPrint($pairedRelative);

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {

//            arrPrintWebs($subConfig);
//            cekHijau();
            if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);

//                showLast_query("biru");

                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {
//                        arrPrint($prTemp);

//                        $rslt = "";
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
//                                cekHere($prSpec->$colName . " : $elName : $colName == $key : " . $prSpec->jenis);
//                                cekPink2(": $key :");
//                                $paired[$fieldLabel] = $prSpec->$fieldID;
                                if ($rslt == "") {
                                    // $rslt =  formatField($fieldLabel, $prSpec->$fieldID);
                                    $rslt = $prSpec->$fieldID;
//                                    cekBiru(": $rslt :");
                                }
                                else {
                                    // $rslt = "$rslt + " . formatField($fieldLabel, $prSpec->$fieldID);
                                    $rslt .= "+" . $prSpec->$fieldID;
//                                    cekPink(": $rslt :");
                                }
                            }
                        }
//                        cekHitam(":: $elName, $fieldLabel :: $rslt ::");
                        $paired[$fieldLabel] = $rslt;
                    }
                    else {
//                        cekMerah("belum ada key");
                    }
                }
                else {

                }
            }
        }
    }
    else {
//        cekUngu("TIDAK ada elementConfig");
    }

//arrPrintWebs($paired);
//    mati_disini();
    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {

        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
//        cekHitam(":: tidak ada filterya ::");
    }

    $oo->init();
    $oo->setFilters(array());
    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
//        cekBiru("$keySrc='$key'");
        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }


    $tmp = $oo->lookupAll()->result();


    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }


            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                    }

                    if (isset($elementConfigs[$elName]['labelSrc'])) {

                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }

                    }
                }
            }
        }
    }
    else {

    }


    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
//            matiHEre("$key ".$tKey." ".$elName);
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
//                $ci->load->helper("he_value_builder");


                $tt = New $model();
                $tt->pair();
                $tt->exec();
//                $ci->fillValues($jenisTr);
            }
        }
    }

    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
//        matiHEre("pairMethod");
    }

//    arrPrint($relElementConfigs);
//    cekHere($elName);
    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {

                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }


                    }
                }
            }
        }
    }


    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }


    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
        );
        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }
        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
//                        matiHere("m");
                            foreach ($targetGate as $gate => $key) {

                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }

                }
            }
        }
    }


    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);


//        unset($_SESSION[$cCode]["out_master"][$elName]);
    }

//    mati_disini("matii");
}

function heRecordElement($jenisTr, $elName, $val)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
    $cCode = "_TR_" . $jenisTr;
//    $elName = $ci->uri->segment(4);
    $allUiConfigs = $ci->config->item('coTransaksiUi');
    if (!$allUiConfigs || !isset($allUiConfigs[$jenisTr])) {
        $allUiConfigs = $ci->config->item('heTransaksi_ui');
    }

    $elementConfigs = isset($allUiConfigs[$jenisTr]['receiptElements']) ? $allUiConfigs[$jenisTr]['receiptElements'] : array();
    $relElementConfigs = isset($allUiConfigs[$jenisTr]['relativeElements']) ? $allUiConfigs[$jenisTr]['relativeElements'] : array();
    $relOptionConfigs = isset($allUiConfigs[$jenisTr]['relativeOptions']) ? $allUiConfigs[$jenisTr]['relativeOptions'] : array();

    //        arrprint($relElementConfigs);
    // Integrasi Nested Relative Rules dengan Pool
    $nestedPool = isset($allUiConfigs[$jenisTr]['nestedElementsPool']) ? $allUiConfigs[$jenisTr]['nestedElementsPool'] : array();
    $nestedRules = isset($allUiConfigs[$jenisTr]['nestedRelativeRules']) ? $allUiConfigs[$jenisTr]['nestedRelativeRules'] : array();

    // Cascading reset for descendants if parent value is changing
    $oldVal = isset($_SESSION[$cCode]['main'][$elName]) ? $_SESSION[$cCode]['main'][$elName] : null;
    if ($oldVal !== $val) {
        $descendants = getDescendantsOfField($elName, $nestedRules);
        if (is_array($descendants) && sizeof($descendants) > 0) {
            foreach ($descendants as $desc) {
                if (isset($_SESSION[$cCode]['main_elements'][$desc])) {
                    unset($_SESSION[$cCode]['main_elements'][$desc]);
                }
                if (isset($_SESSION[$cCode]['main'][$desc])) {
                    unset($_SESSION[$cCode]['main'][$desc]);
                }
                if (isset($_SESSION[$cCode]['main'])) {
                    foreach ($_SESSION[$cCode]['main'] as $mKey => $mVal) {
                        if (strpos($mKey, $desc . "__") === 0) {
                            unset($_SESSION[$cCode]['main'][$mKey]);
                        }
                    }
                }
            }
        }
    }

    if (sizeof($nestedRules) > 0 && sizeof($nestedPool) > 0) {
        $activeKeys = resolveNestedRelativeRules($nestedRules, isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array());
        foreach ($activeKeys as $actKey) {
            if (isset($nestedPool[$actKey])) {
                $elementConfigs[$actKey] = $nestedPool[$actKey];
            }
        }
    }

    //        arrprint($relElementConfigs);
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
//				        $ssCtr=0;
                    foreach ($psubSpec as $rcID => $subSpec) {
//                        $elementConfigs[$eSrc . "_" . $esName . "_" . $rcID] = $subSpec;
                        $elementConfigs[$rcID] = $subSpec;
//                            $ssCtr++;
                    }
                }

            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");

            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['value']) && $_SESSION[$cCode]['main_elements'][$elName]['value'] == $trigVal) {
//                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {

//                    cekbiru("evaluating value: $iVarName");
                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
//                            cekbiru("removing value: $iVarName");
                        }
                    }


                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }

    }

//    $val = ($_GET['val']);
//matiHere("... ".__LINE__." ".__FUNCTION__);
    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
    $_SESSION[$cCode]['main_elements'][$elName] = array(
        "elementType" => $elementConfigs[$elName]['elementType'],
        "name" => $elName,
        "label" => $elementConfigs[$elName]['label'],
        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
        "mdl_name" => "",
        "value" => $val,
    );

// START OF COMPLETE REPEATED LOGIC
    //==masukkan ke gerbang utama
    $_SESSION[$cCode]["main"][$elName] = $val;
    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
        foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
            if (isset($_SESSION[$cCode]['items'][$iID][$elName])) {
                $_SESSION[$cCode]['items'][$iID][$elName] = null;
                unset($_SESSION[$cCode]['items'][$iID][$elName]);
            }
        }
    }
//    $_SESSION[$cCode]["out_master"][$elName] = $val;
}
// END OF COMPLETE REPEATED LOGIC

function hePairFromElement($jenisTr, $elName, $elSpec)
{
//    echo gettype($elSpec);
//    arrprint($elSpec);
    $result = array(
        $elName . "_id" => 0,
        $elName . "_nama" => "none",
    );

    $ci =& get_instance();
    $ci->load->database();

    if (isset($ci->config->item("heTransaksi_elementPairs")[$jenisTr])) {
        $pairConfig = $ci->config->item("heTransaksi_elementPairs")[$jenisTr];

    }

//    cekMerah("pairconfig");
//    arrprint($pairConfig[$elName]);
    if (isset($pairConfig[$elName]['id']) && isset($pairConfig[$elName]['label'])) {
        $labelSrc = $pairConfig[$elName]['label'];
        $result = array(
            $elName . "_id" => isset($elSpec[$pairConfig[$elName]['id']]) ? $elSpec[$pairConfig[$elName]['id']] : 0,
            $elName . "_nama" => isset($elSpec[$labelSrc]) ? $elSpec[$labelSrc] : "-",
        );
    }
    return array(
        "identifiers" => array(
            $elName . "_id" => $elName . "_nama",
        ),
        "content" => $result,
    );
}

function heFetchItemsElement($jenisTr, $elName, $mdlName, $key, $helpName = "")
{

    cekkuning(":: $elName :: $mdlName :: $key :: $jenisTr :: $helpName ::");
    $ci =& get_instance();
    $ci->load->helper("he_element");


    $cCode = "_TR_" . $jenisTr;

    $elementConfigs = isset($ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElementsItemsAuto']) ? $ci->config->item('heTransaksi_ui')[$jenisTr]['receiptElementsItemsAuto'] : array();


    if (isset($_SESSION[$cCode]['items'][$elName])) {
        $items = $_SESSION[$cCode]['items'][$elName];

        $keySrc = $elementConfigs[0]['key'];
        $aFilter = isset($elementConfigs[0]['mdlFilter']) ? $elementConfigs[0]['mdlFilter'] : array();


        $prTemp = array();
        $paired = array();
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $subConfig) {

                if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $items, $items, $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);
                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];

                            if ($prSpec->$colName == $key) {
                                $paired[$fieldLabel] = $prSpec->$fieldID;
                            }
                        }
                    }
                }
            }

        }
        else {
            //        cekUngu("TIDAK ada elementConfig");
        }


        $ci->load->model("Mdls/" . $mdlName);
        $oo = new $mdlName();
        //        if (sizeof($aFilter) > 0) {
        //
        //            $oo = makeFilter($aFilter, $items, $oo);
        //        }
        //        else {
        //            cekHitam(":: tidak ada filterya ::");
        //        }


        $oo->init();
        $oo->setFilters(array());
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $items, $oo);
        }
        else {
            //            cekHitam(":: tidak ada filterya ::");
        }
        if ($oo->getTableName() == "static") {
            $oo->addFilter("$keySrc='$key'");
        }
        else {

            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");

        }

        $tmp = $oo->lookupAll()->result();
        //        cekLime($ci->db->last_query());
        //        arrPrintWebs($tmp);


        $contents = array();
        $labelValue = "";
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($paired) > 0) {
                    foreach ($paired as $prKey => $prVal) {
                        $row->$prKey = $prVal;
                    }
                }

                if (isset($elementConfigs[0]['usedFields']) && sizeof($elementConfigs[0]['usedFields']) > 0) {
                    if ($row->$keySrc == $key) {
                        foreach ($elementConfigs[0]['usedFields'] as $src => $label) {
                            $contents[$src] = isset($row->$src) ? $row->$src : "";
                        }

                        if (isset($elementConfigs[0]['labelSrc'])) {

                            $ex = explode("/", $elementConfigs[0]['labelSrc']);
                            if (sizeof($ex) > 1) {
                                $labelValue = "";
                                foreach ($ex as $col) {

                                    $labelValue .= $row->$col . " / ";
                                }
                                $labelValue = rtrim($labelValue, " / ");
                            }
                            else {
                                $kolomName = $elementConfigs[0]['labelSrc'];
                                $labelValue = $row->$kolomName;
                            }

                        }
                    }
                }
            }
        }
        else {

        }


        if (!isset($_SESSION[$cCode]['items_elements'])) {
            $_SESSION[$cCode]['items_elements'] = array();
        }


        //==daftarkan ke gerbang yang sesuai
        if (sizeof($tmp) > 0) {
            //            cekBiru("masuk ke main_elements");
            $_SESSION[$cCode]['items_elements'][$elName] = array(
                "elementType" => $elementConfigs[0]['elementType'],
                "name" => $elName,
                "label" => $elementConfigs[0]['label'],
                "key" => $key,
                "labelSrc" => isset($elementConfigs[0]['labelSrc']) ? $elementConfigs[0]['labelSrc'] : "--",
                "labelValue" => $labelValue,
                "mdl_name" => $mdlName,
                "contents" => base64_encode(serialize($contents)),
                "contents_intext" => print_r($contents, true),
            );
            //==masukkan ke gerbang items
            $_SESSION[$cCode]["items"][$elName][$helpName] = $key;
            $_SESSION[$cCode]["items"][$elName][$helpName . "__label"] = $labelValue;
            if (sizeof($contents)) {
                foreach ($contents as $key => $val) {
                    $_SESSION[$cCode]["items"][$elName][$helpName . "__" . $key] = $val;
                }
            }

        }
        else {
            unset($_SESSION[$cCode]['items_elements'][$elName]);
            unset($_SESSION[$cCode]["items"][$elName][$elName]);

        }

    }


}

function heFetchElement_modul($jenisTr, $elName, $mdlName, $key, $configUiJenis, $_get = array())
{

    $elementTimeStart = microtime(true);

    $ci =& get_instance();
    $ci->load->helper("he_element");


//    $cCode = "_TR_" . $jenisTr;
    $cCode = cCodeBuilderMisc($jenisTr);

// START OF COMPLETE REPEATED LOGIC
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $nestedPool = isset($configUiJenis['nestedElementsPool']) ? $configUiJenis['nestedElementsPool'] : array();
    if (sizeof($nestedPool) > 0) {
        foreach ($nestedPool as $npKey => $npSpec) {
            $elementConfigs[$npKey] = $npSpec;
        }
    }
// END OF COMPLETE REPEATED LOGIC

    $pihakMasterID = isset($_SESSION[$cCode]["main"]["pihakExternMasterID"]) ? $_SESSION[$cCode]["main"]["pihakExternMasterID"] : "";
    $receiptElementsTambahan = (!empty($pihakMasterID) && isset($configUiJenis['receiptElementsTambahan'][$pihakMasterID])) ? $configUiJenis['receiptElementsTambahan'][$pihakMasterID] : array();
    if(sizeof($receiptElementsTambahan)>0 && isset($receiptElementsTambahan["element"])){
        $elementConfigs = $elementConfigs + $receiptElementsTambahan["element"];
    }



    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
//    $metod2RelConfig = isset($configUiJenis['relativeElements']['targetMethod2']) ? $configUiJenis['relativeElements']['targetMethod2'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

//    arrPrint($relElementConfigs);
    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
//                cekHere("[$eSrc] [$esName]");
//                arrPrintCyan($psubSpec);
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {

                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }

                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);
                            showLast_query("biru");
//                            arrPrint($prTemp);
                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                $hsl = "";
                                foreach ($prTemp as $prSpec) {
                                    $colName = $subSpec['pairedModel']['key'];
                                    if ($prSpec->$colName == $key) {
//                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
//                                        cekKuning("$fieldLabel dibuat paired relative -> " . $prSpec->$fieldID);

                                        if ($hsl == "") {
                                            $hsl = $prSpec->$fieldID;
                                        }
                                        else {
                                            $hsl .= "+" . $prSpec->$fieldID;
                                        }
                                        $pairedRelative[$fieldLabel] = $hsl;
                                    }
                                }
                            }
                        }

                    }
                }

                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
//                    cekBiru("-- $esName --");
//                    arrPrintWebs($psubSpec);
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }


        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$elName terdaftar pada relElements");

            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {
//                    cekbiru("evaluating value: $iVarName");

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {
//                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
//                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
//                            cekbiru("removing value: $iVarName");
//                        }

                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
//                                unset($_SESSION[$cCode]['main_inputs'][$k_input]);
                                cekbiru("removing value: $k_input");
                            }
                        }
                    }

                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }

    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            $elementTimeStart = microtime(true);
            if (isset($subConfig['pairedModel']) && count($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);

                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {

                        $rslt = "";
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
                                if ($rslt == "") {
                                    $rslt = $prSpec->$fieldID;
                                }
                                else {
                                    $rslt .= "+" . $prSpec->$fieldID;
                                }
                                $paired[$fieldLabel] = $rslt;
                            }
                        }
                    }
                    else {
//                        cekMerah("belum ada key");
                    }
                }
                else {

                }
            }
        }
    }
    else {
//        cekUngu("TIDAK ada elementConfig");
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {
        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
//        cekHitam(":: tidak ada filterya ::");
    }

    $oo->init();
    $oo->setFilters(array());

    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
//        arrPrintKuning($aFilter);
        /**
         * pasang ulang filter
         * karena akan salah ambil data jika ada data lebih dari 1, karena filter sudah direset diawal code ($oo->setFilters())
         */
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
        }
        else {
//        cekHitam(":: tidak ada filterya ::");
            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
        }

//        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }

    $tmp = $oo->lookupAll()->result();
//    cekUngu(count($tmp));
//    arrPrint($tmp);
//    showLast_query("ungu");
//    cekUngu(__LINE__);

//    heGetTimedQuery($elementTimeStart, __LINE__);

    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }
            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                        //------
                        if (isset($elementConfigs[$elName]['editableUsedFields'][$src]["default_value"])) {
                            $contents[$src] = isset($_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]]) ? $_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]] : 0;
                        }


                    }
                    if (isset($elementConfigs[$elName]['labelSrc'])) {
                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
//                        cekMerah("count: " . count($ex) . "  ||  LINE: " . __LINE__);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }
                    }
                }
            }
        }
    }
    else {

    }

    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        $targetMethodAll = isset($elementConfigs[$elName]['targetMethodAll']) ? $elementConfigs[$elName]['targetMethodAll'] : false;
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
                $tt = New $model();
                $tt->pair();
                $tt->exec();
            }
            else {
                if ($targetMethodAll == true) {
                    $targetValue = isset($elementConfigs[$elName]['targetValue']) ? $elementConfigs[$elName]['targetValue'] : 0;
                    $targetValueNilai = isset($_SESSION[$cCode]["main"][$targetValue]) ? $_SESSION[$cCode]["main"][$targetValue] : 0;
                    $model = $tVal;
                    $ci->load->model("ReComs/" . $model);
                    $tt = New $model();
                    $tt->pair($key, $targetValueNilai);
                    $tt->exec();
                }
            }
        }
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
        // matiHEre("pairMethod".$model);
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    //-----------------------------------
    if (isset($elementConfigs[$elName]['resetElement']) && sizeof($elementConfigs[$elName]['resetElement']) > 0) {
        $arrResetElementKomponen = $elementConfigs[$elName]['resetElement'];
        foreach ($arrResetElementKomponen as $el_reset) {
            if (isset($_SESSION[$cCode]["main_elements"][$el_reset])) {
                $_SESSION[$cCode]["main_elements"][$el_reset] = NULL;
                unset($_SESSION[$cCode]["main_elements"][$el_reset]);
            }
            // mereset yang ada di main
            if (isset($_SESSION[$cCode]["main"])) {
                foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                    if (strpos($key_reset, $el_reset) !== false) {
                        unset($_SESSION[$cCode]["main"][$key_reset]);
                    }
                }
            }
            if (isset($_SESSION[$cCode]["tableIn_master_values"])) {
                foreach ($_SESSION[$cCode]["tableIn_master_values"] as $key_reset => $xxxxxxx) {
                    if (strpos($key_reset, $el_reset) !== false) {
                        unset($_SESSION[$cCode]["tableIn_master_values"][$key_reset]);
                    }
                }
            }
        }
    }
    //-----------------------------------
//    heGetTimedQuery($elementTimeStart, __LINE__);

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {
                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }
                    }
                }
            }
        }
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);

    $preMulti = array();
    $getExisting = array();
    $mainSesKey = isset($elementConfigs[$elName]['mainSesKey']) ? $elementConfigs[$elName]['mainSesKey'] : "";
    if (isset($_SESSION[$cCode]['main'][$mainSesKey]) && !empty($_SESSION[$cCode]['main'][$mainSesKey])) {
        //reset multi jika tidak ada reference dari main->refPymSrcIDs
        if (isset($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $key2 => $kDatas) {
                $keyExtern2ID = $kDatas['extern2_id'];
                if (!in_array($keyExtern2ID, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$keyExtern2ID]);
                }
            }
        }
        $preMulti = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
        if (isset($_get['multi']) && $_get['multi'] == 1) {
            //multi sebelumnya akan di gabungkan dengan yang terbaru
            $getExisting = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
            $targetKey = $contents['extern2_id'];
            if ($_get['state_multi'] == 'true') {
                //verifikasi key yg masuk, harus yang ada pada main->refPymSrcIDs
                //handle current key
                if (in_array($targetKey, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    $tmpMulti = $contents;
                    $preMulti[$key] = $tmpMulti;
                }
            }
            else {
                $_SESSION[$cCode]['main_elements'][$elName]['multi'][$key] = null;
                unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$key]);
                $preMulti[$key] = null;
                $getExisting[$key] = null;
                unset($getExisting[$key]);
                unset($preMulti[$key]);
            }
        }
    }
    else {
        $_SESSION[$cCode]['main_elements'][$elName]['multi'] = null;
        unset($_SESSION[$cCode]['main_elements'][$elName]['multi']);
    }

    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
            "multi" => !empty($getExisting) ? $getExisting + $preMulti : $preMulti,
        );
        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {

            //untuk update ke items
            $resultItems = [];
            $groupKey = "extern2_id";
            $sumFields = ['sisa', 'ppn'];
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $keyawdkjawd => $row) {
                $keyi = $row[$groupKey];

                if (!isset($resultItems[$keyi])) {
                    $resultItems[$keyi] = [];
                    foreach ($row as $field => $value) {
                        if (in_array($field, $sumFields)) {
                            $resultItems[$keyi][$field] = 0;
                        }
                        else {
                            $resultItems[$keyi][$field] = [];
                        }
                    }
                }

                foreach ($row as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        $resultItems[$keyi][$field] += $value;
                    }
                    else {
                        if (!in_array($value, $resultItems[$keyi][$field], true)) {
                            $resultItems[$keyi][$field][] = $value;
                        }
                    }
                }
            }

            foreach ($resultItems as $keyi => &$row) {
                foreach ($row as $field => $value) {
                    if (!in_array($field, $sumFields)) {
                        $row[$field] = implode(",", $value);
                    }
                }
            }

            $prefix = $elName . '__';

// foreach ($_SESSION[$cCode]["items"] as $idItems => &$item) {
//     $srcId = isset($item['src_id']) ? $item['src_id'] : null;
//     if ($srcId && isset($resultItems[$srcId])) {
//         foreach ($resultItems[$srcId] as $sumKey => $sumVal) {
//             $_SESSION[$cCode]["items"][$idItems][$prefix . $sumKey] = $sumVal;
//         }
//     }
// }
// unset($item);

            $original = $resultItems;
            $remaining = unserialize(serialize($resultItems));
            if (isset($_SESSION[$cCode]['items']) && is_array($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $idItems => &$item) {
                    $srcId = isset($item['src_id']) ? $item['src_id'] : null;
                    if (!$srcId) {
                        continue;
                    }
                    if (!isset($original[$srcId])) {
                        continue;
                    }

                    foreach ($original[$srcId] as $sumKey => $sumVal) {
                        if ($sumKey === 'sisa') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'sisa'] = $sumVal;
                            // pengurang = new_sisa
                            $pengurang = isset($item['new_sisa']) ? $item['new_sisa'] : 0;
                            $avail = isset($remaining[$srcId]['sisa']) ? $remaining[$srcId]['sisa'] : 0;
                            $take = min($pengurang, $avail);
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'terbayar'] = $take;
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'terbayar_ppn'] = $take / 1.11;
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'new_sisa'] = $sumVal - $take;
                            $remaining[$srcId]['sisa'] = $avail - $take;
                        }
                        elseif ($sumKey === 'ppn') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn'] = $sumVal;
                            // pengurang = ppn di item
                            $pengurang = isset($item['ppn']) ? $item['ppn'] : 0;
                            $avail = isset($remaining[$srcId]['ppn']) ? $remaining[$srcId]['ppn'] : 0;
                            $take = min($pengurang, $avail);
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn_minus'] = $take;
                            $remaining[$srcId]['ppn'] = $avail - $take;
                        }
                        else {
                            // field lain
                            $_SESSION[$cCode]['items'][$idItems][$prefix . $sumKey] = $sumVal;
                        }
                    }
                }
                unset($item);
            }

            arrPrintWebs($resultItems);

            //summary untuk update ke content
            $combined = [];
            $sums = [];
            foreach ($resultItems as $rowc) {
                foreach ($rowc as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        if (!isset($sums[$field])) {
                            $sums[$field] = 0;
                        }
                        $sums[$field] += $value;
                    }
                    else {
                        if (!isset($combined[$field])) {
                            $combined[$field] = [];
                        }
                        if (!in_array($value, $combined[$field], true)) {
                            $combined[$field][] = $value;
                        }
                    }
                }
            }

            foreach ($combined as $field => $values) {
                $combined[$field] = implode(',', $values);
            }

            foreach ($sums as $field => $sum) {
                $combined[$field] = number_format($sum, 0);
            }

            $_SESSION[$cCode]['main_elements'][$elName]['contents'] = base64_encode(serialize($combined));
            $_SESSION[$cCode]['main_elements'][$elName]['contents_intext'] = print_r($combined, true);

            //update ke main
            if (sizeof($combined)) {
                foreach ($combined as $keyz => $val) {
                    $_SESSION[$cCode]["main"][$elName . "__" . $keyz] = $val;
                    if ($keyz == "sisa") {
                        $angkaBersih = str_replace(",", "", $val);
                        $_SESSION[$cCode]['main'][$elName . "__" . "dipakai"] = $angkaBersih;
                    }
                }
            }

        }


        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
//                    ceklIme($this->db->last_query());
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }
                }
            }
        }
        if (isset($elementConfigs[$elName]['recomInjectedItem'])) {
            if (isset($elementConfigs[$elName]['recomInjectedItem']["target"])) {
                arrprint($elementConfigs[$elName]['recomInjectedItem']);
                foreach ($elementConfigs[$elName]['recomInjectedItem']["target"] as $target_sess => $ses_fields) {
                    if (isset($_SESSION[$cCode][$target_sess]) && count($_SESSION[$cCode][$target_sess]) > 0) {
                        $keys = array_keys($_SESSION[$cCode][$target_sess])[0];
                        cekMerah($keys);
                        foreach ($ses_fields["field"] as $k => $src_val) {
                            if (isset($_SESSION[$cCode]["main"][$src_val])) {
                                $_SESSION[$cCode][$target_sess][$keys][$k] = $_SESSION[$cCode]["main"][$src_val];
//                                matiHEre($_SESSION[$cCode]["main"][$src_val]);
                            }

                        }
//                        matiHere(__LINE__);
                    }
                }
            }
            if (isset($elementConfigs[$elName]['recomInjectedItem']['fetchItems'])) {
                $model = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["model"];
                $method = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["method"];
//                $source_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["source"];
                $target_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["target"];
                $filter = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["filters"];
                $usedField = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["usedField"];
                $ci->load->model("Mdls/" . $model);
                $rm = new $model();
                foreach ($filter as $prKey => $prVal) {
                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                    $rm->addFilter("$prKey='$prVal'");
                }
//                $rm->addFilter("nilai");
                $temp = $rm->lookUpAll()->result();
                if (count($temp) > 0) {
                    $_SESSION[$cCode][$target_fields] = array();
                    $tmp = array();
                    foreach ($temp as $temp_0) {
                        foreach ($usedField as $k => $k_src) {
                            $tmp[$temp_0->id][$k] = $temp_0->$k_src;
                        }
                    }
                    $_SESSION[$cCode][$target_fields] = $tmp;
//                    arrPrint($tmp);
                }
//                cekMerah($ci->db->last_query());
//
//                arrPrint($temp);

//                arrPrint($temp);


//                matiHere(__LINE__);
            }
//arrprint($elementConfigs[$elName]['recomInjectedItem']);
//            matiHere(__LINE__."::::he_element");
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {
//            if(isset($_get['state_multi']) && $_get['state_multi']=='false'){
            if (empty($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
                unset($_SESSION[$cCode]['main_elements'][$elName]);
                //reset main
                if (isset($_SESSION[$cCode]["main"])) {
                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                        if (strpos($key_reset, $elName) !== false) {
                            unset($_SESSION[$cCode]["main"][$key_reset]);
                        }
                    }
                }
                //reset items
                if (isset($_SESSION[$cCode]["items"])) {
                    foreach ($_SESSION[$cCode]["items"] as $refItemsID => $xxx) {
                        foreach ($xxx as $key_reset => $xxxxxxx) {
                            if (strpos($key_reset, $elName) !== false) {
                                unset($_SESSION[$cCode]["items"][$refItemsID][$key_reset]);
                            }
                        }
                    }
                }
            }
//            }
        }
    }
    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);


//        unset($_SESSION[$cCode]["out_master"][$elName]);
    }

//    heGetTimedQuery($elementTimeStart, __LINE__);
}

function heRecordElement_modul($jenisTr, $elName, $val, $configUiJenis)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
    $cCode = cCodeBuilderMisc($jenisTr);
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();

    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                    }
                }
            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['value']) && $_SESSION[$cCode]['main_elements'][$elName]['value'] == $trigVal) {
//                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {

//                    cekbiru("evaluating value: $iVarName");
                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
//                            cekbiru("removing value: $iVarName");
                        }
                    }


                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }
    }

    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
    $_SESSION[$cCode]['main_elements'][$elName] = array(
        "elementType" => $elementConfigs[$elName]['elementType'],
        "name" => $elName,
        "label" => $elementConfigs[$elName]['label'],
        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
        "mdl_name" => "",
        "value" => $val,
    );

    //==masukkan ke gerbang utama
    $_SESSION[$cCode]["main"][$elName] = $val;
    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
        foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
            if (isset($_SESSION[$cCode]['items'][$iID][$elName])) {
                $_SESSION[$cCode]['items'][$iID][$elName] = null;
                unset($_SESSION[$cCode]['items'][$iID][$elName]);
            }
        }
    }
//    $_SESSION[$cCode]["out_master"][$elName] = $val;
}

function heRecordUsedElement_modul($jenisTr, $elName, $src, $val, $configUiJenis)
{

    $ci =& get_instance();
    $ci->load->helper("he_element");
    $cCode = cCodeBuilderMisc($jenisTr);
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();

    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
//				        $ssCtr=0;
                    foreach ($psubSpec as $rcID => $subSpec) {
//                        $elementConfigs[$eSrc . "_" . $esName . "_" . $rcID] = $subSpec;
                        $elementConfigs[$rcID] = $subSpec;
//                            $ssCtr++;
                    }
                }

            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");

            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {

                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['value']) && $_SESSION[$cCode]['main_elements'][$elName]['value'] == $trigVal) {
//                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {

//                    cekbiru("evaluating value: $iVarName");
                        if (isset($_SESSION[$cCode]['main_inputs'][$iVarName])) {
                            unset($_SESSION[$cCode]['main_inputs'][$iVarName]);
//                            cekbiru("removing value: $iVarName");
                        }
                    }


                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }

    }

    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }
//    $_SESSION[$cCode]['main_elements'][$elName] = array(
//        "elementType" => $elementConfigs[$elName]['elementType'],
//        "name" => $elName,
//        "label" => $elementConfigs[$elName]['label'],
//        "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
//        "mdl_name" => "",
//        "value" => $val,
//    );
    if (isset($_SESSION[$cCode]['main_elements'][$elName])) {
        $contents = blobDecode($_SESSION[$cCode]['main_elements'][$elName]["contents"]);
        $contents[$src] = $val;
        $contentsBlob = blobEncode($contents);
        $_SESSION[$cCode]['main_elements'][$elName]["contents"] = $contentsBlob;
    }

    //==masukkan ke gerbang utama
    $_SESSION[$cCode]["main"][$elName . "__" . $src] = $val;
    $_SESSION[$cCode]["main"]["default__" . $elName . "__" . $src] = $val;


}

function heFetchItemsElement_modul($jenisTr, $elName, $mdlName, $key, $helpName = "", $configUiJenis)
{

//        cekkuning(":: $elName :: $mdlName :: $key :: $jenisTr :: $helpName ::");
    $ci =& get_instance();
    $ci->load->helper("he_element");


    $cCode = "_TR_" . $jenisTr;

    $elementConfigs = isset($configUiJenis['receiptElementsItemsAuto']) ? $configUiJenis['receiptElementsItemsAuto'] : array();


    if (isset($_SESSION[$cCode]['items'][$elName])) {
        $items = $_SESSION[$cCode]['items'][$elName];

        $keySrc = $elementConfigs[0]['key'];
        $aFilter = isset($elementConfigs[0]['mdlFilter']) ? $elementConfigs[0]['mdlFilter'] : array();


        $prTemp = array();
        $paired = array();
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $subConfig) {
//arrPrintPink($subConfig);
                if (isset($subConfig['pairedModel']) && sizeof($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {

                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $items, $items, $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);
//                    cekHere($this->db->last_query());
                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];

                            if ($prSpec->$colName == $key) {
                                $paired[$fieldLabel] = $prSpec->$fieldID;
                            }
                        }
                    }
                }
            }

        }
        else {
            //        cekUngu("TIDAK ada elementConfig");
        }


        $ci->load->model("Mdls/" . $mdlName);
        $oo = new $mdlName();
        //        if (sizeof($aFilter) > 0) {
        //
        //            $oo = makeFilter($aFilter, $items, $oo);
        //        }
        //        else {
        //            cekHitam(":: tidak ada filterya ::");
        //        }


        $oo->init();
        $oo->setFilters(array());
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $items, $oo);
        }
        else {
            //            cekHitam(":: tidak ada filterya ::");
        }
        if ($oo->getTableName() == "static") {
            $oo->addFilter("$keySrc='$key'");
        }
        else {

            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");

        }

        $tmp = $oo->lookupAll()->result();
        //        cekLime($ci->db->last_query());
        //        arrPrintWebs($tmp);


        $contents = array();
        $labelValue = "";
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if (sizeof($paired) > 0) {
                    foreach ($paired as $prKey => $prVal) {
                        $row->$prKey = $prVal;
                    }
                }

                if (isset($elementConfigs[0]['usedFields']) && sizeof($elementConfigs[0]['usedFields']) > 0) {
                    if ($row->$keySrc == $key) {
                        foreach ($elementConfigs[0]['usedFields'] as $src => $label) {
                            $contents[$src] = isset($row->$src) ? $row->$src : "";
                        }

                        if (isset($elementConfigs[0]['labelSrc'])) {

                            $ex = explode("/", $elementConfigs[0]['labelSrc']);
                            if (sizeof($ex) > 1) {
                                $labelValue = "";
                                foreach ($ex as $col) {

                                    $labelValue .= $row->$col . " / ";
                                }
                                $labelValue = rtrim($labelValue, " / ");
                            }
                            else {
                                $kolomName = $elementConfigs[0]['labelSrc'];
                                $labelValue = $row->$kolomName;
                            }

                        }
                    }
                }
            }
        }
        else {

        }


        if (!isset($_SESSION[$cCode]['items_elements'])) {
            $_SESSION[$cCode]['items_elements'] = array();
        }


        //==daftarkan ke gerbang yang sesuai
        if (sizeof($tmp) > 0) {
            //            cekBiru("masuk ke main_elements");
            $_SESSION[$cCode]['items_elements'][$elName] = array(
                "elementType" => $elementConfigs[0]['elementType'],
                "name" => $elName,
                "label" => $elementConfigs[0]['label'],
                "key" => $key,
                "labelSrc" => isset($elementConfigs[0]['labelSrc']) ? $elementConfigs[0]['labelSrc'] : "--",
                "labelValue" => $labelValue,
                "mdl_name" => $mdlName,
                "contents" => base64_encode(serialize($contents)),
                "contents_intext" => print_r($contents, true),
            );
            //==masukkan ke gerbang items
            $_SESSION[$cCode]["items"][$elName][$helpName] = $key;
            $_SESSION[$cCode]["items"][$elName][$helpName . "__label"] = $labelValue;
            if (sizeof($contents)) {
                foreach ($contents as $key => $val) {
                    $_SESSION[$cCode]["items"][$elName][$helpName . "__" . $key] = $val;
                }
            }

        }
        else {
            unset($_SESSION[$cCode]['items_elements'][$elName]);
            unset($_SESSION[$cCode]["items"][$elName][$elName]);

        }

    }


}

function heGetTimedQuery($timestart, $line = 0)
{
//    // Mendapatkan waktu sekarang dalam milidetik
//    $dateNowMicro = microtime(true);
//
//    // Menghitung selisih waktu dalam milidetik
//    $perbedaan = ($dateNowMicro - $timestart) * 1000; // dalam milidetik
//
//    // Mengonversi milidetik ke dalam format yang lebih mudah dibaca
//    $detik = floor($perbedaan / 1000);
//    $milidetik = $perbedaan % 1000;
//
//    // Membentuk string yang mudah dibaca
//    $hasil = "{$detik} detik dan {$milidetik} milidetik <br>LINE: $line";
//
//    // Memanggil fungsi cekMerah dengan hasil selisih
//    cekMerah($hasil);

    // Mendapatkan waktu sekarang dalam detik desimal
    $dateNowMicro = microtime(true);

    // Menghitung selisih waktu dalam detik
    $perbedaan = $dateNowMicro - $timestart; // dalam detik desimal

    // Memanggil fungsi cekMerah dengan hasil selisih
    cekMerah(round($perbedaan, 6) . " detik <br>LINE: $line"); // Membulatkan hingga 3 angka desimal
}

function heRecomCalculate($jenisTr, $elName, $mdlName, $key, $configUiJenis)
{
    $ci =& get_instance();
    $ci->load->helper("he_element");

//    $cCode = "_TR_" . $jenisTr;
    $cCode = cCodeBuilderMisc($jenisTr);
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
//    $metod2RelConfig = isset($configUiJenis['relativeElements']['targetMethod2']) ? $configUiJenis['relativeElements']['targetMethod2'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

//    arrPrint($relElementConfigs);
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
//        cekHitam($key);
//        arrPrint($gateVal);
//        arrPrint($_SESSION[$cCode]["main"]["tagihan_bayar"]);
//        arrPrint($key);

        $tc->exec();
//        matiHEre("pairMethod".$model);
    }

}

function hePairedModel_he_element($pairedModel, $key, $sessionData)
{
    $ci =& get_instance();
    if (isset($pairedModel) && sizeof($pairedModel) > 0) {
        $ci->load->model("Coms/" . $pairedModel['mdlName']);
        $pr = new $pairedModel['mdlName']();
        if (sizeof($pairedModel['mdlFilter']) > 0) {
            foreach ($pairedModel['mdlFilter'] as $prKey => $prVal) {
                $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                $pr->addFilter("$prKey='$prVal'");
            }
        }

        $pairedRek = $pairedModel['rekening'];
        $pairedMethod = $pairedModel['mdlMethod'];
        $prTemp = $pr->$pairedMethod($pairedRek);
        showLast_query("biru");
//        arrPrint($prTemp);
        if (sizeof($prTemp) > 0) {
            $fieldID = $pairedModel['fieldID'];
            $fieldLabel = $pairedModel['fieldLabel'];
            if (isset($key) && ($key != NULL)) {
                $hsl = "";
                foreach ($prTemp as $prSpec) {
                    $colName = $pairedModel['key'];
                    if ($prSpec->$colName == $key) {
//                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
//                                        cekKuning("$fieldLabel dibuat paired relative -> " . $prSpec->$fieldID);

                        if ($hsl == "") {
                            $hsl = $prSpec->$fieldID;
                        }
                        else {
                            $hsl .= "+" . $prSpec->$fieldID;
                        }
                        $pairedRelative[$fieldLabel] = $hsl;
                    }
                }
            }
        }

    }

//    arrPrint($pairedRelative);
    return isset($pairedRelative) ? $pairedRelative : array();
}

function heFetchElement_modul_multi($jenisTr, $elName, $mdlName, $key, $configUiJenis, $_get = array())
{

    $elementTimeStart = microtime(true);

    $ci =& get_instance();
    $ci->load->helper("he_element");

    $cCode = cCodeBuilderMisc($jenisTr);

// START OF COMPLETE REPEATED LOGIC
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $nestedPool = isset($configUiJenis['nestedElementsPool']) ? $configUiJenis['nestedElementsPool'] : array();
    if (sizeof($nestedPool) > 0) {
        foreach ($nestedPool as $npKey => $npSpec) {
            $elementConfigs[$npKey] = $npSpec;
        }
    }
// END OF COMPLETE REPEATED LOGIC
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                                $pairedRelative = hePairedModel_he_element($subSpec['pairedModel'], $key, $_SESSION[$cCode]);
                            }
                        }
                        else {
                            if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                                $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                                $pr = new $subSpec['pairedModel']['mdlName']();
                                if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {
                                    foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                        $pr->addFilter("$prKey='$prVal'");
                                    }
                                }

                                $pairedRek = $subSpec['pairedModel']['rekening'];
                                $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                                $prTemp = $pr->$pairedMethod($pairedRek);
//                            showLast_query("biru");
//                            arrPrint($prTemp);
                                if (sizeof($prTemp) > 0) {
                                    $fieldID = $subSpec['pairedModel']['fieldID'];
                                    $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                    $hsl = "";
                                    foreach ($prTemp as $prSpec) {
                                        $colName = $subSpec['pairedModel']['key'];
                                        if ($prSpec->$colName == $key) {
//                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
//                                        cekKuning("$fieldLabel dibuat paired relative -> " . $prSpec->$fieldID);

                                            if ($hsl == "") {
                                                $hsl = $prSpec->$fieldID;
                                            }
                                            else {
                                                $hsl .= "+" . $prSpec->$fieldID;
                                            }
                                            $pairedRelative[$fieldLabel] = $hsl;
                                        }
                                    }
                                }

                            }
                        }

                    }
                }

                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
//                    cekBiru("-- $esName --");
//                    arrPrintWebs($psubSpec);
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }
        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
                                cekbiru("removing value: $k_input");
                            }
                        }
                    }
                }
            }
        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }
    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            $elementTimeStart = microtime(true);
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                    $paired = hePairedModel_he_element($subSpec['pairedModel'], $key, $_SESSION[$cCode]);
                }
            }
            else {
                if (isset($subConfig['pairedModel']) && count($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {
                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);

                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                        if (isset($key) && ($key != NULL)) {

                            $rslt = "";
                            foreach ($prTemp as $prSpec) {
                                $colName = $subConfig['pairedModel']['key'];
                                if ($prSpec->$colName == $key) {
                                    if ($rslt == "") {
                                        $rslt = $prSpec->$fieldID;
                                    }
                                    else {
                                        $rslt .= "+" . $prSpec->$fieldID;
                                    }
                                    $paired[$fieldLabel] = $rslt;
                                }
                            }
                        }
                        else {
//                        cekMerah("belum ada key");
                        }
                    }
                }
            }

        }
    }
    else {
//        cekUngu("TIDAK ada elementConfig");
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {
        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
//        cekHitam(":: tidak ada filterya ::");
    }

    $oo->init();
    $oo->setFilters(array());

    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        /**
         * pasang ulang filter
         * karena akan salah ambil data jika ada data lebih dari 1, karena filter sudah direset diawal code ($oo->setFilters())
         */
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
        }
        else {
//        cekHitam(":: tidak ada filterya ::");
            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
        }

//        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }

    $tmp = $oo->lookupAll()->result();

    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }
            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                        //------
                        if (isset($elementConfigs[$elName]['editableUsedFields'][$src]["default_value"])) {
                            $contents[$src] = isset($_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]]) ? $_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]] : 0;
                        }
                    }
                    if (isset($elementConfigs[$elName]['labelSrc'])) {
                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {
                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }
                    }
                }
            }
        }
    }
    else {

    }

    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        $targetMethodAll = isset($elementConfigs[$elName]['targetMethodAll']) ? $elementConfigs[$elName]['targetMethodAll'] : false;
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
                $tt = New $model();
                $tt->pair();
                $tt->exec();
            }
            else {
                if ($targetMethodAll == true) {
                    $targetValue = isset($elementConfigs[$elName]['targetValue']) ? $elementConfigs[$elName]['targetValue'] : 0;
                    $targetValueNilai = isset($_SESSION[$cCode]["main"][$targetValue]) ? $_SESSION[$cCode]["main"][$targetValue] : 0;
                    $model = $tVal;
                    $ci->load->model("ReComs/" . $model);
                    $tt = New $model();
                    $tt->pair($key, $targetValueNilai);
                    $tt->exec();
                }
            }
        }
    }

    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
    }

    //-----------------------------------
    if (isset($elementConfigs[$elName]['resetElement']) && sizeof($elementConfigs[$elName]['resetElement']) > 0) {
        $arrResetElementKomponen = $elementConfigs[$elName]['resetElement'];
        foreach ($arrResetElementKomponen as $el_reset) {
            if (isset($_SESSION[$cCode]["main_elements"][$el_reset])) {
                $_SESSION[$cCode]["main_elements"][$el_reset] = NULL;
                unset($_SESSION[$cCode]["main_elements"][$el_reset]);
            }
            // mereset yang ada di main
            if (isset($_SESSION[$cCode]["main"])) {
                foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                    if (strpos($key_reset, $el_reset) !== false) {
                        unset($_SESSION[$cCode]["main"][$key_reset]);
                    }
                }
            }
        }
    }
    //-----------------------------------

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {
                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }
                    }
                }
            }
        }
    }

    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }

//    unset($_SESSION[$cCode]['main_elements'][$elName]['multi']);

    $preMulti = array();
    $mainSesKey = isset($elementConfigs[$elName]['mainSesKey']) ? $elementConfigs[$elName]['mainSesKey'] : "";
    if (isset($_SESSION[$cCode]['main'][$mainSesKey]) && !empty($_SESSION[$cCode]['main'][$mainSesKey])) {
        //reset multi jika tidak ada reference dari main->refPymSrcIDs
        if (isset($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $key2 => $kDatas) {
                $keyExtern2ID = $kDatas['extern2_id'];
                if (!in_array($keyExtern2ID, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$keyExtern2ID]);
                }
            }
        }
        $preMulti = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
        if (isset($_get['multi']) && $_get['multi'] == 1) {
            //multi sebelumnya akan di gabungkan dengan yang terbaru
            $getExisting = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
            $targetKey = $contents['extern2_id'];
            if ($_get['state_multi'] == 'true') {
                //verifikasi key yg masuk, harus yang ada pada main->refPymSrcIDs
                //handle current key
                if (in_array($targetKey, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    $tmpMulti = $contents;
                    $preMulti[$key] = $tmpMulti;
                }
            }
            else {
                $_SESSION[$cCode]['main_elements'][$elName]['multi'][$key] = null;
                unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$key]);
                $preMulti[$key] = null;
                $getExisting[$key] = null;
                unset($getExisting[$key]);
                unset($preMulti[$key]);
            }
        }
    }
    else {
        $_SESSION[$cCode]['main_elements'][$elName]['multi'] = null;
        unset($_SESSION[$cCode]['main_elements'][$elName]['multi']);
    }

    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
            "multi" => !empty($getExisting) ? $getExisting + $preMulti : $preMulti,
        );

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {

            //untuk update ke items
            $resultItems = [];
            $groupKey = "extern2_id";
            $sumFields = ['sisa', 'ppn'];
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $keyawdkjawd => $row) {
                $keyi = $row[$groupKey];

                if (!isset($resultItems[$keyi])) {
                    $resultItems[$keyi] = [];
                    foreach ($row as $field => $value) {
                        if (in_array($field, $sumFields)) {
                            $resultItems[$keyi][$field] = 0;
                        }
                        else {
                            $resultItems[$keyi][$field] = [];
                        }
                    }
                }

                foreach ($row as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        $resultItems[$keyi][$field] += $value;
                    }
                    else {
                        if (!in_array($value, $resultItems[$keyi][$field], true)) {
                            $resultItems[$keyi][$field][] = $value;
                        }
                    }
                }
            }

            foreach ($resultItems as $keyi => &$row) {
                foreach ($row as $field => $value) {
                    if (!in_array($field, $sumFields)) {
                        $row[$field] = implode(",", $value);
                    }
                }
            }

            $prefix = $elName . '__';

// foreach ($_SESSION[$cCode]["items"] as $idItems => &$item) {
//     $srcId = isset($item['src_id']) ? $item['src_id'] : null;
//     if ($srcId && isset($resultItems[$srcId])) {
//         foreach ($resultItems[$srcId] as $sumKey => $sumVal) {
//             $_SESSION[$cCode]["items"][$idItems][$prefix . $sumKey] = $sumVal;
//         }
//     }
// }
// unset($item);

            $original = $resultItems;
            $remaining = unserialize(serialize($resultItems));
            if (isset($_SESSION[$cCode]['items']) && is_array($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $idItems => &$item) {
                    $srcId = isset($item['src_id']) ? $item['src_id'] : null;
                    if (!$srcId) {
                        continue;
                    }
                    if (!isset($original[$srcId])) {
                        continue;
                    }

                    foreach ($original[$srcId] as $sumKey => $sumVal) {
                        if ($sumKey === 'sisa') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'sisa'] = $sumVal;

                            // pengurang = dpp_ppn
                            $pengurang = isset($item['dpp_ppn']) ? $item['dpp_ppn'] : 0;
                            $avail = isset($remaining[$srcId]['sisa']) ? $remaining[$srcId]['sisa'] : 0;
                            $take = min($pengurang, $avail);

                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'sisa_minus'] = $take;
                            $remaining[$srcId]['sisa'] = $avail - $take;

                        }
                        elseif ($sumKey === 'ppn') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn'] = $sumVal;

                            // pengurang = ppn di item
                            $pengurang = isset($item['ppn']) ? $item['ppn'] : 0;
                            $avail = isset($remaining[$srcId]['ppn']) ? $remaining[$srcId]['ppn'] : 0;
                            $take = min($pengurang, $avail);

                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn_minus'] = $take;
                            $remaining[$srcId]['ppn'] = $avail - $take;

                        }
                        else {
                            // field lain
                            $_SESSION[$cCode]['items'][$idItems][$prefix . $sumKey] = $sumVal;
                        }
                    }
                }
                unset($item);
            }

            arrPrintWebs($resultItems);

            //summary untuk update ke content
            $combined = [];
            $sums = [];
            foreach ($resultItems as $rowc) {
                foreach ($rowc as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        if (!isset($sums[$field])) {
                            $sums[$field] = 0;
                        }
                        $sums[$field] += $value;
                    }
                    else {
                        if (!isset($combined[$field])) {
                            $combined[$field] = [];
                        }
                        if (!in_array($value, $combined[$field], true)) {
                            $combined[$field][] = $value;
                        }
                    }
                }
            }

            foreach ($combined as $field => $values) {
                $combined[$field] = implode(',', $values);
            }

            foreach ($sums as $field => $sum) {
                $combined[$field] = number_format($sum, 0);
            }

            $_SESSION[$cCode]['main_elements'][$elName]['contents'] = base64_encode(serialize($combined));
            $_SESSION[$cCode]['main_elements'][$elName]['contents_intext'] = print_r($combined, true);

            //update ke main
            if (sizeof($combined)) {
                foreach ($combined as $keyz => $val) {
                    $_SESSION[$cCode]["main"][$elName . "__" . $keyz] = $val;
                    if ($keyz == "sisa") {
                        $angkaBersih = str_replace(",", "", $val);
                        $_SESSION[$cCode]['main'][$elName . "__" . "dipakai"] = $angkaBersih;
                    }
                }
            }

        }

        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }
                }
            }
        }
        if (isset($elementConfigs[$elName]['recomInjectedItem'])) {
            if (isset($elementConfigs[$elName]['recomInjectedItem']["target"])) {
                foreach ($elementConfigs[$elName]['recomInjectedItem']["target"] as $target_sess => $ses_fields) {
                    if (isset($_SESSION[$cCode][$target_sess]) && count($_SESSION[$cCode][$target_sess]) > 0) {
                        $keys = array_keys($_SESSION[$cCode][$target_sess])[0];
                        foreach ($ses_fields["field"] as $k => $src_val) {
                            if (isset($_SESSION[$cCode]["main"][$src_val])) {
                                $_SESSION[$cCode][$target_sess][$keys][$k] = $_SESSION[$cCode]["main"][$src_val];
                            }
                        }
                    }
                }
            }
            if (isset($elementConfigs[$elName]['recomInjectedItem']['fetchItems'])) {
                $model = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["model"];
                $method = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["method"];
                $target_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["target"];
                $filter = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["filters"];
                $usedField = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["usedField"];
                $ci->load->model("Mdls/" . $model);
                $rm = new $model();
                foreach ($filter as $prKey => $prVal) {
                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                    $rm->addFilter("$prKey='$prVal'");
                }

                $temp = $rm->lookUpAll()->result();
                if (count($temp) > 0) {
                    $_SESSION[$cCode][$target_fields] = array();
                    $tmp = array();
                    foreach ($temp as $temp_0) {
                        foreach ($usedField as $k => $k_src) {
                            $tmp[$temp_0->id][$k] = $temp_0->$k_src;
                        }
                    }
                    $_SESSION[$cCode][$target_fields] = $tmp;
                }
            }
        }
        //jka mode multi dan state_multi = false, reset semua dari element atas

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {
//            if(isset($_get['state_multi']) && $_get['state_multi']=='false'){
            if (empty($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
                unset($_SESSION[$cCode]['main_elements'][$elName]);
                //reset main
                if (isset($_SESSION[$cCode]["main"])) {
                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                        if (strpos($key_reset, $elName) !== false) {
                            unset($_SESSION[$cCode]["main"][$key_reset]);
                        }
                    }
                }
                //reset items
                if (isset($_SESSION[$cCode]["items"])) {
                    foreach ($_SESSION[$cCode]["items"] as $refItemsID => $xxx) {
                        foreach ($xxx as $key_reset => $xxxxxxx) {
                            if (strpos($key_reset, $elName) !== false) {
                                unset($_SESSION[$cCode]["items"][$refItemsID][$key_reset]);
                            }
                        }
                    }
                }
            }
//            }
        }
    }
    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);
    }
}

function heFetchElement_modul_edit($jenisTr, $elName, $mdlName, $key, $configUiJenis, $_get = array())
{

    $elementTimeStart = microtime(true);

    $ci =& get_instance();
    $ci->load->helper("he_element");

    $cCode = cCodeBuilderMisc($jenisTr);

// START OF COMPLETE REPEATED LOGIC
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $nestedPool = isset($configUiJenis['nestedElementsPool']) ? $configUiJenis['nestedElementsPool'] : array();
    if (sizeof($nestedPool) > 0) {
        foreach ($nestedPool as $npKey => $npSpec) {
            $elementConfigs[$npKey] = $npSpec;
        }
    }
// END OF COMPLETE REPEATED LOGIC
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        $pakai_ini = 1;
                        if ($pakai_ini == 1) {
                            if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                                $pairedRelative = hePairedModel_he_element($subSpec['pairedModel'], $key, $_SESSION[$cCode]);
                            }
                        }
                        else {
                            if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                                $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                                $pr = new $subSpec['pairedModel']['mdlName']();
                                if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {
                                    foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                        $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                                        $pr->addFilter("$prKey='$prVal'");
                                    }
                                }

                                $pairedRek = $subSpec['pairedModel']['rekening'];
                                $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                                $prTemp = $pr->$pairedMethod($pairedRek);
//                            showLast_query("biru");
//                            arrPrint($prTemp);
                                if (sizeof($prTemp) > 0) {
                                    $fieldID = $subSpec['pairedModel']['fieldID'];
                                    $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                    $hsl = "";
                                    foreach ($prTemp as $prSpec) {
                                        $colName = $subSpec['pairedModel']['key'];
                                        if ($prSpec->$colName == $key) {
//                                        $pairedRelative[$fieldLabel] = $prSpec->$fieldID;
//                                        cekKuning("$fieldLabel dibuat paired relative -> " . $prSpec->$fieldID);

                                            if ($hsl == "") {
                                                $hsl = $prSpec->$fieldID;
                                            }
                                            else {
                                                $hsl .= "+" . $prSpec->$fieldID;
                                            }
                                            $pairedRelative[$fieldLabel] = $hsl;
                                        }
                                    }
                                }

                            }
                        }

                    }
                }

                if ($esName == (isset($_SESSION[$cCode]['main'][$eSrc]) ? $_SESSION[$cCode]['main'][$eSrc] : "")) {
//                    cekBiru("-- $esName --");
//                    arrPrintWebs($psubSpec);
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }
        if (array_key_exists($elName, $relElementConfigs)) {
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                foreach ($_SESSION[$cCode]['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
                        unset($_SESSION[$cCode]['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }
        }
        if (array_key_exists($elName, $relOptionConfigs)) {
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
                foreach ($options as $iVarName => $jasghahgsghasha) {
                    if (isset($_SESSION[$cCode]['main_elements'][$elName]['key']) && $_SESSION[$cCode]['main_elements'][$elName]['key'] == $trigVal) {
                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($_SESSION[$cCode]['main_inputs'])) {
                            foreach ($_SESSION[$cCode]['main_inputs'] as $k_input => $v_input) {
                                $_SESSION[$cCode]['main_inputs'][$k_input] = 0;
                                $_SESSION[$cCode]['main'][$k_input] = 0;
                                cekbiru("removing value: $k_input");
                            }
                        }
                    }
                }
            }
        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }
    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            $elementTimeStart = microtime(true);
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                    $paired = hePairedModel_he_element($subSpec['pairedModel'], $key, $_SESSION[$cCode]);
                }
            }
            else {
                if (isset($subConfig['pairedModel']) && count($subConfig['pairedModel']) > 0) {
                    $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                    $pr = new $subConfig['pairedModel']['mdlName']();
                    if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {
                        foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                            $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                            $pr->addFilter("$prKey='$prVal'");
                        }
                    }

                    $pairedRek = $subConfig['pairedModel']['rekening'];
                    $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                    $prTemp = $pr->$pairedMethod($pairedRek);

                    if (sizeof($prTemp) > 0) {
                        $fieldID = $subConfig['pairedModel']['fieldID'];
                        $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                        if (isset($key) && ($key != NULL)) {

                            $rslt = "";
                            foreach ($prTemp as $prSpec) {
                                $colName = $subConfig['pairedModel']['key'];
                                if ($prSpec->$colName == $key) {
                                    if ($rslt == "") {
                                        $rslt = $prSpec->$fieldID;
                                    }
                                    else {
                                        $rslt .= "+" . $prSpec->$fieldID;
                                    }
                                    $paired[$fieldLabel] = $rslt;
                                }
                            }
                        }
                        else {
//                        cekMerah("belum ada key");
                        }
                    }
                }
            }

        }
    }
    else {
//        cekUngu("TIDAK ada elementConfig");
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();


    if (sizeof($aFilter) > 0) {
        $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
    }
    else {
//        cekHitam(":: tidak ada filterya ::");
    }

    $oo->init();
    $oo->setFilters(array());

    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        /**
         * pasang ulang filter
         * karena akan salah ambil data jika ada data lebih dari 1, karena filter sudah direset diawal code ($oo->setFilters())
         */
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $_SESSION[$cCode]['main'], $oo);
        }
        else {
//        cekHitam(":: tidak ada filterya ::");
            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
        }

//        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }

    $tmp = $oo->lookupAll()->result();

    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }
            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                        //------
                        if (isset($elementConfigs[$elName]['editableUsedFields'][$src]["default_value"])) {
                            $contents[$src] = isset($_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]]) ? $_SESSION[$cCode]["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]] : 0;
                        }
                    }
                    if (isset($elementConfigs[$elName]['labelSrc'])) {
                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {
                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }
                    }
                }
            }
        }
    }
    else {

    }

    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        $targetMethodAll = isset($elementConfigs[$elName]['targetMethodAll']) ? $elementConfigs[$elName]['targetMethodAll'] : false;
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
                $tt = New $model();
                $tt->pair();
                $tt->exec();
            }
            else {
                if ($targetMethodAll == true) {
                    $targetValue = isset($elementConfigs[$elName]['targetValue']) ? $elementConfigs[$elName]['targetValue'] : 0;
                    $targetValueNilai = isset($_SESSION[$cCode]["main"][$targetValue]) ? $_SESSION[$cCode]["main"][$targetValue] : 0;
                    $model = $tVal;
                    $ci->load->model("ReComs/" . $model);
                    $tt = New $model();
                    $tt->pair($key, $targetValueNilai);
                    $tt->exec();
                }
            }
        }
    }

    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
    }

    //-----------------------------------
    if (isset($elementConfigs[$elName]['resetElement']) && sizeof($elementConfigs[$elName]['resetElement']) > 0) {
        $arrResetElementKomponen = $elementConfigs[$elName]['resetElement'];
        foreach ($arrResetElementKomponen as $el_reset) {
            if (isset($_SESSION[$cCode]["main_elements"][$el_reset])) {
                $_SESSION[$cCode]["main_elements"][$el_reset] = NULL;
                unset($_SESSION[$cCode]["main_elements"][$el_reset]);
            }
            // mereset yang ada di main
            if (isset($_SESSION[$cCode]["main"])) {
                foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                    if (strpos($key_reset, $el_reset) !== false) {
                        unset($_SESSION[$cCode]["main"][$key_reset]);
                    }
                }
            }
        }
    }
    //-----------------------------------

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {
                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }
                    }
                }
            }
        }
    }

    if (!isset($_SESSION[$cCode]['main_elements'])) {
        $_SESSION[$cCode]['main_elements'] = array();
    }

//    unset($_SESSION[$cCode]['main_elements'][$elName]['multi']);

    $preMulti = array();
    $mainSesKey = isset($elementConfigs[$elName]['mainSesKey']) ? $elementConfigs[$elName]['mainSesKey'] : "";
    if (isset($_SESSION[$cCode]['main'][$mainSesKey]) && !empty($_SESSION[$cCode]['main'][$mainSesKey])) {
        //reset multi jika tidak ada reference dari main->refPymSrcIDs
        if (isset($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $key2 => $kDatas) {
                $keyExtern2ID = $kDatas['extern2_id'];
                if (!in_array($keyExtern2ID, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$keyExtern2ID]);
                }
            }
        }
        $preMulti = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
        if (isset($_get['multi']) && $_get['multi'] == 1) {
            //multi sebelumnya akan di gabungkan dengan yang terbaru
            $getExisting = $_SESSION[$cCode]['main_elements'][$elName]['multi'];
            $targetKey = $contents['extern2_id'];
            if ($_get['state_multi'] == 'true') {
                //verifikasi key yg masuk, harus yang ada pada main->refPymSrcIDs
                //handle current key
                if (in_array($targetKey, $_SESSION[$cCode]['main'][$mainSesKey])) {
                    $tmpMulti = $contents;
                    $preMulti[$key] = $tmpMulti;
                }
            }
            else {
                $_SESSION[$cCode]['main_elements'][$elName]['multi'][$key] = null;
                unset($_SESSION[$cCode]['main_elements'][$elName]['multi'][$key]);
                $preMulti[$key] = null;
                $getExisting[$key] = null;
                unset($getExisting[$key]);
                unset($preMulti[$key]);
            }
        }
    }
    else {
        $_SESSION[$cCode]['main_elements'][$elName]['multi'] = null;
        unset($_SESSION[$cCode]['main_elements'][$elName]['multi']);
    }

    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $_SESSION[$cCode]['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
            "multi" => !empty($getExisting) ? $getExisting + $preMulti : $preMulti,
        );

        //==masukkan ke gerbang utama
        $_SESSION[$cCode]["main"][$elName] = $key;
        $_SESSION[$cCode]["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $_SESSION[$cCode]["main"][$elName . "__" . $key] = $val;
            }
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {

            //untuk update ke items
            $resultItems = [];
            $groupKey = "extern2_id";
            $sumFields = ['sisa', 'ppn'];
            foreach ($_SESSION[$cCode]['main_elements'][$elName]['multi'] as $keyawdkjawd => $row) {
                $keyi = $row[$groupKey];

                if (!isset($resultItems[$keyi])) {
                    $resultItems[$keyi] = [];
                    foreach ($row as $field => $value) {
                        if (in_array($field, $sumFields)) {
                            $resultItems[$keyi][$field] = 0;
                        }
                        else {
                            $resultItems[$keyi][$field] = [];
                        }
                    }
                }

                foreach ($row as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        $resultItems[$keyi][$field] += $value;
                    }
                    else {
                        if (!in_array($value, $resultItems[$keyi][$field], true)) {
                            $resultItems[$keyi][$field][] = $value;
                        }
                    }
                }
            }

            foreach ($resultItems as $keyi => &$row) {
                foreach ($row as $field => $value) {
                    if (!in_array($field, $sumFields)) {
                        $row[$field] = implode(",", $value);
                    }
                }
            }

            $prefix = $elName . '__';

// foreach ($_SESSION[$cCode]["items"] as $idItems => &$item) {
//     $srcId = isset($item['src_id']) ? $item['src_id'] : null;
//     if ($srcId && isset($resultItems[$srcId])) {
//         foreach ($resultItems[$srcId] as $sumKey => $sumVal) {
//             $_SESSION[$cCode]["items"][$idItems][$prefix . $sumKey] = $sumVal;
//         }
//     }
// }
// unset($item);

            $original = $resultItems;
            $remaining = unserialize(serialize($resultItems));
            if (isset($_SESSION[$cCode]['items']) && is_array($_SESSION[$cCode]['items'])) {
                foreach ($_SESSION[$cCode]['items'] as $idItems => &$item) {
                    $srcId = isset($item['src_id']) ? $item['src_id'] : null;
                    if (!$srcId) {
                        continue;
                    }
                    if (!isset($original[$srcId])) {
                        continue;
                    }

                    foreach ($original[$srcId] as $sumKey => $sumVal) {
                        if ($sumKey === 'sisa') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'sisa'] = $sumVal;

                            // pengurang = dpp_ppn
                            $pengurang = isset($item['dpp_ppn']) ? $item['dpp_ppn'] : 0;
                            $avail = isset($remaining[$srcId]['sisa']) ? $remaining[$srcId]['sisa'] : 0;
                            $take = min($pengurang, $avail);

                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'sisa_minus'] = $take;
                            $remaining[$srcId]['sisa'] = $avail - $take;

                        }
                        elseif ($sumKey === 'ppn') {
                            // nilai awal
                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn'] = $sumVal;

                            // pengurang = ppn di item
                            $pengurang = isset($item['ppn']) ? $item['ppn'] : 0;
                            $avail = isset($remaining[$srcId]['ppn']) ? $remaining[$srcId]['ppn'] : 0;
                            $take = min($pengurang, $avail);

                            $_SESSION[$cCode]['items'][$idItems][$prefix . 'ppn_minus'] = $take;
                            $remaining[$srcId]['ppn'] = $avail - $take;

                        }
                        else {
                            // field lain
                            $_SESSION[$cCode]['items'][$idItems][$prefix . $sumKey] = $sumVal;
                        }
                    }
                }
                unset($item);
            }

            arrPrintWebs($resultItems);

            //summary untuk update ke content
            $combined = [];
            $sums = [];
            foreach ($resultItems as $rowc) {
                foreach ($rowc as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        if (!isset($sums[$field])) {
                            $sums[$field] = 0;
                        }
                        $sums[$field] += $value;
                    }
                    else {
                        if (!isset($combined[$field])) {
                            $combined[$field] = [];
                        }
                        if (!in_array($value, $combined[$field], true)) {
                            $combined[$field][] = $value;
                        }
                    }
                }
            }

            foreach ($combined as $field => $values) {
                $combined[$field] = implode(',', $values);
            }

            foreach ($sums as $field => $sum) {
                $combined[$field] = number_format($sum, 0);
            }

            $_SESSION[$cCode]['main_elements'][$elName]['contents'] = base64_encode(serialize($combined));
            $_SESSION[$cCode]['main_elements'][$elName]['contents_intext'] = print_r($combined, true);

            //update ke main
            if (sizeof($combined)) {
                foreach ($combined as $keyz => $val) {
                    $_SESSION[$cCode]["main"][$elName . "__" . $keyz] = $val;
                    if ($keyz == "sisa") {
                        $angkaBersih = str_replace(",", "", $val);
                        $_SESSION[$cCode]['main'][$elName . "__" . "dipakai"] = $angkaBersih;
                    }
                }
            }

        }

        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $_SESSION[$cCode]['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 0;//false
                            }
                        }
                        else {
                            foreach ($targetGate as $gate => $key) {
                                $_SESSION[$cCode][$gate][$key] = 1;//true
                            }
                        }
                    }
                }
            }
        }
        if (isset($elementConfigs[$elName]['recomInjectedItem'])) {
            if (isset($elementConfigs[$elName]['recomInjectedItem']["target"])) {
                foreach ($elementConfigs[$elName]['recomInjectedItem']["target"] as $target_sess => $ses_fields) {
                    if (isset($_SESSION[$cCode][$target_sess]) && count($_SESSION[$cCode][$target_sess]) > 0) {
                        $keys = array_keys($_SESSION[$cCode][$target_sess])[0];
                        foreach ($ses_fields["field"] as $k => $src_val) {
                            if (isset($_SESSION[$cCode]["main"][$src_val])) {
                                $_SESSION[$cCode][$target_sess][$keys][$k] = $_SESSION[$cCode]["main"][$src_val];
                            }
                        }
                    }
                }
            }
            if (isset($elementConfigs[$elName]['recomInjectedItem']['fetchItems'])) {
                $model = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["model"];
                $method = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["method"];
                $target_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["target"];
                $filter = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["filters"];
                $usedField = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["usedField"];
                $ci->load->model("Mdls/" . $model);
                $rm = new $model();
                foreach ($filter as $prKey => $prVal) {
                    $prVal = makeValue($prVal, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                    $rm->addFilter("$prKey='$prVal'");
                }

                $temp = $rm->lookUpAll()->result();
                if (count($temp) > 0) {
                    $_SESSION[$cCode][$target_fields] = array();
                    $tmp = array();
                    foreach ($temp as $temp_0) {
                        foreach ($usedField as $k => $k_src) {
                            $tmp[$temp_0->id][$k] = $temp_0->$k_src;
                        }
                    }
                    $_SESSION[$cCode][$target_fields] = $tmp;
                }
            }
        }
        //jka mode multi dan state_multi = false, reset semua dari element atas

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {
//            if(isset($_get['state_multi']) && $_get['state_multi']=='false'){
            if (empty($_SESSION[$cCode]['main_elements'][$elName]['multi'])) {
                unset($_SESSION[$cCode]['main_elements'][$elName]);
                //reset main
                if (isset($_SESSION[$cCode]["main"])) {
                    foreach ($_SESSION[$cCode]["main"] as $key_reset => $xxxxxxx) {
                        if (strpos($key_reset, $elName) !== false) {
                            unset($_SESSION[$cCode]["main"][$key_reset]);
                        }
                    }
                }
                //reset items
                if (isset($_SESSION[$cCode]["items"])) {
                    foreach ($_SESSION[$cCode]["items"] as $refItemsID => $xxx) {
                        foreach ($xxx as $key_reset => $xxxxxxx) {
                            if (strpos($key_reset, $elName) !== false) {
                                unset($_SESSION[$cCode]["items"][$refItemsID][$key_reset]);
                            }
                        }
                    }
                }
            }
//            }
        }
    }
    else {
        unset($_SESSION[$cCode]['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($_SESSION[$cCode]["main"][$elName]);
    }
}


function heFetchElement_modul_ns($jenisTr, $elName, $mdlName, $key, $configUiJenis, $_get = array(), $sessionData)
{
    $elementTimeStart = microtime(true);

    $ci =& get_instance();
    $ci->load->helper("he_element");

    $cCode = cCodeBuilderMisc($jenisTr);

// START OF COMPLETE REPEATED LOGIC
    $elementConfigs = isset($configUiJenis['receiptElements']) ? $configUiJenis['receiptElements'] : array();
    $nestedPool = isset($configUiJenis['nestedElementsPool']) ? $configUiJenis['nestedElementsPool'] : array();
    if (sizeof($nestedPool) > 0) {
        foreach ($nestedPool as $npKey => $npSpec) {
            $elementConfigs[$npKey] = $npSpec;
        }
    }
// END OF COMPLETE REPEATED LOGIC
    $relElementConfigs = isset($configUiJenis['relativeElements']) ? $configUiJenis['relativeElements'] : array();
    $relOptionConfigs = isset($configUiJenis['relativeOptions']) ? $configUiJenis['relativeOptions'] : array();
    $configRecomData = isset($configUiJenis['pairRecomDataElement']) ? $configUiJenis['pairRecomDataElement'] : array();

    $pairedRelative = array();
    if (sizeof($relElementConfigs) > 0) {
        foreach ($relElementConfigs as $eSrc => $esSpec) {
            foreach ($esSpec as $esName => $psubSpec) {
                if (sizeof($psubSpec) > 0) {
                    foreach ($psubSpec as $rcID => $subSpec) {
                        $elementConfigs[$rcID] = $subSpec;
                        if (isset($subSpec['pairedModel']) && sizeof($subSpec['pairedModel']) > 0) {
                            $ci->load->model("Coms/" . $subSpec['pairedModel']['mdlName']);
                            $pr = new $subSpec['pairedModel']['mdlName']();
                            if (sizeof($subSpec['pairedModel']['mdlFilter']) > 0) {
                                foreach ($subSpec['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                                    $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                                    $pr->addFilter("$prKey='$prVal'");
                                }
                            }
                            $pairedRek = $subSpec['pairedModel']['rekening'];
                            $pairedMethod = $subSpec['pairedModel']['mdlMethod'];
                            $prTemp = $pr->$pairedMethod($pairedRek);
                            showLast_query("biru");
                            if (sizeof($prTemp) > 0) {
                                $fieldID = $subSpec['pairedModel']['fieldID'];
                                $fieldLabel = $subSpec['pairedModel']['fieldLabel'];
                                $hsl = "";
                                foreach ($prTemp as $prSpec) {
                                    $colName = $subSpec['pairedModel']['key'];
                                    if ($prSpec->$colName == $key) {
                                        if ($hsl == "") {
                                            $hsl = $prSpec->$fieldID;
                                        }
                                        else {
                                            $hsl .= "+" . $prSpec->$fieldID;
                                        }
                                        $pairedRelative[$fieldLabel] = $hsl;
                                    }
                                }
                            }
                        }

                    }
                }

                if ($esName == (isset($sessionData['main'][$eSrc]) ? $sessionData['main'][$eSrc] : "")) {
                    if (isset($psubSpec[$elName]['pairMethod']) && sizeof($psubSpec[$elName]['pairMethod']) > 0) {
                        $model = $psubSpec[$elName]['pairMethod']["recom"];
                        $ci->load->model("ReComs/" . $model);
                        $gateVal = $psubSpec[$elName]['pairMethod']["calculate"];
                        $tc = new $model();
                        $tc->pair($gateVal, $key);
                        $tc->exec();

                    }
                }
            }
        }

        if (array_key_exists($elName, $relElementConfigs)) {
//					cekhijau("$elName terdaftar pada relElements");
            //reset semua nilai anakan relatif yang mungkin saja terlanjur terbentuk
            if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
                foreach ($sessionData['main_elements'] as $eeName => $jasghhagsghaj) {
                    if (strpos($eeName, $elName . "_") !== false) {
//							cekkuning("$eeName mengandung kata $elName _ dan harus direset");
                        unset($sessionData['main_elements'][$eeName]);
                    }
                    else {
//							cekkuning("$eeName tidak perlu direset");
                    }
                }
            }


        }
        if (array_key_exists($elName, $relOptionConfigs)) {
//            cekhijau("$elName terdaftar pada relInputs");
            //reset semua inputan relatif yang mungkin saja terlanjur terbentuk
            foreach ($relOptionConfigs[$elName] as $trigVal => $options) {
//                cekbiru("evaluating condition: $trigVal");
                foreach ($options as $iVarName => $jasghahgsghasha) {
//                    cekbiru("evaluating value: $iVarName");
                    if (isset($sessionData['main_elements'][$elName]['key']) && $sessionData['main_elements'][$elName]['key'] == $trigVal) {
                        cekbiru("NO NEED to remove value: $iVarName");
                    }
                    else {
                        // hapus semua main_input dp/cia/diskon bila sudah diisi maka diisi ulang...
                        if (isset($sessionData['main_inputs'])) {
                            foreach ($sessionData['main_inputs'] as $k_input => $v_input) {
                                $sessionData['main_inputs'][$k_input] = 0;
                                $sessionData['main'][$k_input] = 0;
//                                unset($sessionData['main_inputs'][$k_input]);
                                cekbiru("removing value: $k_input");
                            }
                        }
                    }

                }

            }


        }
        else {
//            cekhijau("$elName TIDAK terdaftar pada relInputs");
        }

    }

    $keySrc = $elementConfigs[$elName]['key'];
    $aFilter = isset($elementConfigs[$elName]['mdlFilter']) ? $elementConfigs[$elName]['mdlFilter'] : array();

    $prTemp = array();
    $paired = array();
    if (sizeof($elementConfigs) > 0) {
        foreach ($elementConfigs as $subConfig) {
            $elementTimeStart = microtime(true);
            if (isset($subConfig['pairedModel']) && count($subConfig['pairedModel']) > 0) {
                $ci->load->model("Coms/" . $subConfig['pairedModel']['mdlName']);
                $pr = new $subConfig['pairedModel']['mdlName']();
                if (sizeof($subConfig['pairedModel']['mdlFilter']) > 0) {
                    foreach ($subConfig['pairedModel']['mdlFilter'] as $prKey => $prVal) {
                        $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                        $pr->addFilter("$prKey='$prVal'");
                    }
                }

                $pairedRek = $subConfig['pairedModel']['rekening'];
                $pairedMethod = $subConfig['pairedModel']['mdlMethod'];
                $prTemp = $pr->$pairedMethod($pairedRek);

                if (sizeof($prTemp) > 0) {
                    $fieldID = $subConfig['pairedModel']['fieldID'];
                    $fieldLabel = $subConfig['pairedModel']['fieldLabel'];

                    if (isset($key) && ($key != NULL)) {

                        $rslt = "";
                        foreach ($prTemp as $prSpec) {
                            $colName = $subConfig['pairedModel']['key'];
                            if ($prSpec->$colName == $key) {
                                if ($rslt == "") {
                                    $rslt = $prSpec->$fieldID;
                                }
                                else {
                                    $rslt .= "+" . $prSpec->$fieldID;
                                }
                                $paired[$fieldLabel] = $rslt;
                            }
                        }
                    }
                    else {
//                        cekMerah("belum ada key");
                    }
                }
                else {

                }
            }
        }
    }
    else {
//        cekUngu("TIDAK ada elementConfig");
    }

    $ci->load->model("Mdls/" . $mdlName);
    $oo = new $mdlName();
    if (sizeof($aFilter) > 0) {
        $oo = makeFilter($aFilter, $sessionData['main'], $oo);
    }
    else {
//        cekHitam(":: tidak ada filterya ::");
    }
    $oo->init();
    $oo->setFilters(array());
    if ($oo->getTableName() == "static") {
        $oo->addFilter("$keySrc='$key'");
    }
    else {
        /**
         * pasang ulang filter
         * karena akan salah ambil data jika ada data lebih dari 1, karena filter sudah direset diawal code ($oo->setFilters())
         */
        if (sizeof($aFilter) > 0) {
            $oo = makeFilter($aFilter, $sessionData['main'], $oo);
        }
        else {
//        cekHitam(":: tidak ada filterya ::");
            $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
        }

//        $oo->addFilter($oo->getTableName() . "." . "$keySrc='$key'");
    }
    $tmp = $oo->lookupAll()->result();

//    heGetTimedQuery($elementTimeStart, __LINE__);

    $contents = array();
    $labelValue = "";
    if (sizeof($tmp) > 0) {
        foreach ($tmp as $row) {
            if (sizeof($paired) > 0) {
                foreach ($paired as $prKey => $prVal) {
                    $row->$prKey = $prVal;
                }
            }
            if (sizeof($pairedRelative) > 0) {
                foreach ($pairedRelative as $prKeyRel => $prValRel) {
                    $row->$prKeyRel = $prValRel;
                }
            }
            if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
                if ($row->$keySrc == $key) {
                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
                        $contents[$src] = isset($row->$src) ? $row->$src : "";
                        //------
                        if (isset($elementConfigs[$elName]['editableUsedFields'][$src]["default_value"])) {
                            $contents[$src] = isset($sessionData["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]]) ? $sessionData["main"][$elementConfigs[$elName]['editableUsedFields'][$src]["default_value"]] : 0;
                        }
                    }
                    if (isset($elementConfigs[$elName]['labelSrc'])) {
                        $ex = explode("/", $elementConfigs[$elName]['labelSrc']);
//                        cekMerah("count: " . count($ex) . "  ||  LINE: " . __LINE__);
                        if (sizeof($ex) > 1) {
                            $labelValue = "";
                            foreach ($ex as $col) {

                                $labelValue .= $row->$col . " / ";
                            }
                            $labelValue = rtrim($labelValue, " / ");
                        }
                        else {
                            $kolomName = $elementConfigs[$elName]['labelSrc'];
                            $labelValue = $row->$kolomName;
                        }
                    }
                }
            }
        }
    }
    else {

    }

    //  method diskon....
    if (isset($elementConfigs[$elName]['targetMethod']) && sizeof($elementConfigs[$elName]['targetMethod']) > 0) {
        $targetMethodAll = isset($elementConfigs[$elName]['targetMethodAll']) ? $elementConfigs[$elName]['targetMethodAll'] : false;
        foreach ($elementConfigs[$elName]['targetMethod'] as $tKey => $tVal) {
            if ($key == $tKey) {
                $model = $tVal;
                $ci->load->model("ReComs/" . $model);
                $tt = New $model();
                $tt->pair();
                $tt->exec();
            }
            else {
                if ($targetMethodAll == true) {
                    $targetValue = isset($elementConfigs[$elName]['targetValue']) ? $elementConfigs[$elName]['targetValue'] : 0;
                    $targetValueNilai = isset($sessionData["main"][$targetValue]) ? $sessionData["main"][$targetValue] : 0;
                    $model = $tVal;
                    $ci->load->model("ReComs/" . $model);
                    $tt = New $model();
                    $tt->pair($key, $targetValueNilai);
                    $tt->exec();
                }
            }
        }
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    //kalkulasi relemet ke main jika ada perhitungan logic
    if (isset($elementConfigs[$elName]['pairMethod']) && sizeof($elementConfigs[$elName]['pairMethod']) > 0) {
        $model = $elementConfigs[$elName]['pairMethod']["recom"];
        $ci->load->model("ReComs/" . $model);
        $gateVal = $elementConfigs[$elName]['pairMethod']["calculate"];
        $tc = new $model();
        $tc->pair($gateVal, $key);
        $tc->exec();
        // matiHEre("pairMethod".$model);
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    //-----------------------------------
    if (isset($elementConfigs[$elName]['resetElement']) && sizeof($elementConfigs[$elName]['resetElement']) > 0) {
        $arrResetElementKomponen = $elementConfigs[$elName]['resetElement'];
        foreach ($arrResetElementKomponen as $el_reset) {
            if (isset($sessionData["main_elements"][$el_reset])) {
                $sessionData["main_elements"][$el_reset] = NULL;
                unset($sessionData["main_elements"][$el_reset]);
//                cekHitam("reset $el_reset " . __LINE__);
            }
            // mereset yang ada di main
            if (isset($sessionData["main"])) {
                foreach ($sessionData["main"] as $key_reset => $xxxxxxx) {
                    if (strpos($key_reset, $el_reset) !== false) {
//                        cekkuning("$key_reset mengandung kata $el_reset dan harus direset");
                        unset($sessionData["main"][$key_reset]);

                    }
                }
            }
        }
    }
    //-----------------------------------
//    heGetTimedQuery($elementTimeStart, __LINE__);

    if (isset($relElementConfigs[$elName]) && $elementConfigs[$elName] > 0) {
        if (isset($relElementConfigs[$elName][$key])) {
            foreach ($relElementConfigs[$elName][$key] as $tKey => $tVal) {
                if (isset($tVal['targetMethod2'])) {
                    foreach ($tVal['targetMethod2'] as $sKey => $sVal) {
                        if ($key == $sKey) {
                            $model = $sVal;
                            $ci->load->model("ReComs/" . $model);
                            $tt = New $model();
                            $tt->pair();
                            $tt->exec();
                        }
                    }
                }
            }
        }
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);
    if (!isset($sessionData['main_elements'])) {
        $sessionData['main_elements'] = array();
    }
//    heGetTimedQuery($elementTimeStart, __LINE__);

    $preMulti = array();
    $getExisting = array();
    $mainSesKey = isset($elementConfigs[$elName]['mainSesKey']) ? $elementConfigs[$elName]['mainSesKey'] : "";
    if (isset($sessionData['main'][$mainSesKey]) && !empty($sessionData['main'][$mainSesKey])) {
        //reset multi jika tidak ada reference dari main->refPymSrcIDs
        if (isset($sessionData['main_elements'][$elName]['multi'])) {
            foreach ($sessionData['main_elements'][$elName]['multi'] as $key2 => $kDatas) {
                $keyExtern2ID = $kDatas['extern2_id'];
                if (!in_array($keyExtern2ID, $sessionData['main'][$mainSesKey])) {
                    unset($sessionData['main_elements'][$elName]['multi'][$keyExtern2ID]);
                }
            }
        }
        $preMulti = $sessionData['main_elements'][$elName]['multi'];
        if (isset($_get['multi']) && $_get['multi'] == 1) {
            //multi sebelumnya akan di gabungkan dengan yang terbaru
            $getExisting = $sessionData['main_elements'][$elName]['multi'];
            $targetKey = $contents['extern2_id'];
            if ($_get['state_multi'] == 'true') {
                //verifikasi key yg masuk, harus yang ada pada main->refPymSrcIDs
                //handle current key
                if (in_array($targetKey, $sessionData['main'][$mainSesKey])) {
                    $tmpMulti = $contents;
                    $preMulti[$key] = $tmpMulti;
                }
            }
            else {
                $sessionData['main_elements'][$elName]['multi'][$key] = null;
                unset($sessionData['main_elements'][$elName]['multi'][$key]);
                $preMulti[$key] = null;
                $getExisting[$key] = null;
                unset($getExisting[$key]);
                unset($preMulti[$key]);
            }
        }
    }
    else {
        $sessionData['main_elements'][$elName]['multi'] = null;
        unset($sessionData['main_elements'][$elName]['multi']);
    }

    //==daftarkan ke gerbang yang sesuai
    if (sizeof($tmp) > 0) {
        $sessionData['main_elements'][$elName] = array(
            "elementType" => $elementConfigs[$elName]['elementType'],
            "name" => $elName,
            "label" => $elementConfigs[$elName]['label'],
            "key" => $key,
            "labelSrc" => isset($elementConfigs[$elName]['labelSrc']) ? $elementConfigs[$elName]['labelSrc'] : "--",
            "labelValue" => $labelValue,
            "mdl_name" => $mdlName,
            "contents" => base64_encode(serialize($contents)),
            "contents_intext" => print_r($contents, true),
            "multi" => !empty($getExisting) ? $getExisting + $preMulti : $preMulti,
        );
        //==masukkan ke gerbang utama
        $sessionData["main"][$elName] = $key;
        $sessionData["main"][$elName . "__label"] = $labelValue;
        if (sizeof($contents)) {
            foreach ($contents as $key => $val) {
                $sessionData["main"][$elName . "__" . $key] = $val;
            }
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {

            //untuk update ke items
            $resultItems = [];
            $groupKey = "extern2_id";
            $sumFields = ['sisa', 'ppn'];
            foreach ($sessionData['main_elements'][$elName]['multi'] as $keyawdkjawd => $row) {
                $keyi = $row[$groupKey];

                if (!isset($resultItems[$keyi])) {
                    $resultItems[$keyi] = [];
                    foreach ($row as $field => $value) {
                        if (in_array($field, $sumFields)) {
                            $resultItems[$keyi][$field] = 0;
                        }
                        else {
                            $resultItems[$keyi][$field] = [];
                        }
                    }
                }

                foreach ($row as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        $resultItems[$keyi][$field] += $value;
                    }
                    else {
                        if (!in_array($value, $resultItems[$keyi][$field], true)) {
                            $resultItems[$keyi][$field][] = $value;
                        }
                    }
                }
            }
            foreach ($resultItems as $keyi => &$row) {
                foreach ($row as $field => $value) {
                    if (!in_array($field, $sumFields)) {
                        $row[$field] = implode(",", $value);
                    }
                }
            }
            $prefix = $elName . '__';
            $original = $resultItems;
            $remaining = unserialize(serialize($resultItems));
            if (isset($sessionData['items']) && is_array($sessionData['items'])) {
                foreach ($sessionData['items'] as $idItems => &$item) {
                    $srcId = isset($item['src_id']) ? $item['src_id'] : null;
                    if (!$srcId) {
                        continue;
                    }
                    if (!isset($original[$srcId])) {
                        continue;
                    }
                    foreach ($original[$srcId] as $sumKey => $sumVal) {
                        if ($sumKey === 'sisa') {
                            // nilai awal
                            $sessionData['items'][$idItems][$prefix . 'sisa'] = $sumVal;
                            // pengurang = new_sisa
                            $pengurang = isset($item['new_sisa']) ? $item['new_sisa'] : 0;
                            $avail = isset($remaining[$srcId]['sisa']) ? $remaining[$srcId]['sisa'] : 0;
                            $take = min($pengurang, $avail);
                            $sessionData['items'][$idItems][$prefix . 'terbayar'] = $take;
                            $sessionData['items'][$idItems][$prefix . 'terbayar_ppn'] = $take / 1.11;
                            $sessionData['items'][$idItems][$prefix . 'new_sisa'] = $sumVal - $take;
                            $remaining[$srcId]['sisa'] = $avail - $take;
                        }
                        elseif ($sumKey === 'ppn') {
                            // nilai awal
                            $sessionData['items'][$idItems][$prefix . 'ppn'] = $sumVal;
                            // pengurang = ppn di item
                            $pengurang = isset($item['ppn']) ? $item['ppn'] : 0;
                            $avail = isset($remaining[$srcId]['ppn']) ? $remaining[$srcId]['ppn'] : 0;
                            $take = min($pengurang, $avail);
                            $sessionData['items'][$idItems][$prefix . 'ppn_minus'] = $take;
                            $remaining[$srcId]['ppn'] = $avail - $take;
                        }
                        else {
                            // field lain
                            $sessionData['items'][$idItems][$prefix . $sumKey] = $sumVal;
                        }
                    }
                }
                unset($item);
            }

            arrPrintWebs($resultItems);

            //summary untuk update ke content
            $combined = [];
            $sums = [];
            foreach ($resultItems as $rowc) {
                foreach ($rowc as $field => $value) {
                    if (in_array($field, $sumFields)) {
                        if (!isset($sums[$field])) {
                            $sums[$field] = 0;
                        }
                        $sums[$field] += $value;
                    }
                    else {
                        if (!isset($combined[$field])) {
                            $combined[$field] = [];
                        }
                        if (!in_array($value, $combined[$field], true)) {
                            $combined[$field][] = $value;
                        }
                    }
                }
            }

            foreach ($combined as $field => $values) {
                $combined[$field] = implode(',', $values);
            }

            foreach ($sums as $field => $sum) {
                $combined[$field] = number_format($sum, 0);
            }

            $sessionData['main_elements'][$elName]['contents'] = base64_encode(serialize($combined));
            $sessionData['main_elements'][$elName]['contents_intext'] = print_r($combined, true);

            //update ke main
            if (sizeof($combined)) {
                foreach ($combined as $keyz => $val) {
                    $sessionData["main"][$elName . "__" . $keyz] = $val;
                    if ($keyz == "sisa") {
                        $angkaBersih = str_replace(",", "", $val);
                        $sessionData['main'][$elName . "__" . "dipakai"] = $angkaBersih;
                    }
                }
            }
        }


        if (sizeof($configRecomData) > 0) {
            if (isset($configRecomData[$elName])) {
                $dataRe = $configRecomData[$elName];
                if (sizeof($configRecomData) > 0) {
                    $mdlName = $dataRe['mdlname'];
                    $filterKey = $dataRe['gateId'];
                    $targetGate = $dataRe['target'];
                    $keyID = $sessionData['main'][$filterKey];
                    $ci->load->model("Mdls/" . $mdlName);
                    $md = new $mdlName();
                    $tmRe = $md->lookUpAll()->result();
                    $array = array();
                    foreach ($tmRe as $data) {
                        $array[$data->id] = $data->name;
                    }
                    if (isset($array[$keyID])) {
                        if (isset($array[$keyID]) && $array[$keyID] == "dipotong") {
                            foreach ($targetGate as $gate => $key) {
                                $sessionData[$gate][$key] = 0;//false
                            }
                        }
                        else {
                            foreach ($targetGate as $gate => $key) {
                                $sessionData[$gate][$key] = 1;//true
                            }
                        }
                    }
                }
            }
        }
        if (isset($elementConfigs[$elName]['recomInjectedItem'])) {
            if (isset($elementConfigs[$elName]['recomInjectedItem']["target"])) {
                foreach ($elementConfigs[$elName]['recomInjectedItem']["target"] as $target_sess => $ses_fields) {
                    if (isset($sessionData[$target_sess]) && count($sessionData[$target_sess]) > 0) {
                        $keys = array_keys($sessionData[$target_sess])[0];
                        foreach ($ses_fields["field"] as $k => $src_val) {
                            if (isset($sessionData["main"][$src_val])) {
                                $sessionData[$target_sess][$keys][$k] = $sessionData["main"][$src_val];
                            }
                        }
                    }
                }
            }
            if (isset($elementConfigs[$elName]['recomInjectedItem']['fetchItems'])) {
                $model = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["model"];
                $method = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["method"];
//                $source_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["source"];
                $target_fields = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["target"];
                $filter = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["filters"];
                $usedField = $elementConfigs[$elName]['recomInjectedItem']['fetchItems']["usedField"];
                $ci->load->model("Mdls/" . $model);
                $rm = new $model();
                foreach ($filter as $prKey => $prVal) {
                    $prVal = makeValue($prVal, $sessionData['main'], $sessionData['main'], $static = 0);
                    $rm->addFilter("$prKey='$prVal'");
                }
//                $rm->addFilter("nilai");
                $temp = $rm->lookUpAll()->result();
                if (count($temp) > 0) {
                    $sessionData[$target_fields] = array();
                    $tmp = array();
                    foreach ($temp as $temp_0) {
                        foreach ($usedField as $k => $k_src) {
                            $tmp[$temp_0->id][$k] = $temp_0->$k_src;
                        }
                    }
                    $sessionData[$target_fields] = $tmp;
//                    arrPrint($tmp);
                }
//                cekMerah($ci->db->last_query());
//
//                arrPrint($temp);

//                arrPrint($temp);


//                matiHere(__LINE__);
            }
        }

        if (isset($elementConfigs[$elName]['multi']) && $elementConfigs[$elName]['multi'] == true) {
//            if(isset($_get['state_multi']) && $_get['state_multi']=='false'){
            if (empty($sessionData['main_elements'][$elName]['multi'])) {
                unset($sessionData['main_elements'][$elName]);
                //reset main
                if (isset($sessionData["main"])) {
                    foreach ($sessionData["main"] as $key_reset => $xxxxxxx) {
                        if (strpos($key_reset, $elName) !== false) {
                            unset($sessionData["main"][$key_reset]);
                        }
                    }
                }
                //reset items
                if (isset($sessionData["items"])) {
                    foreach ($sessionData["items"] as $refItemsID => $xxx) {
                        foreach ($xxx as $key_reset => $xxxxxxx) {
                            if (strpos($key_reset, $elName) !== false) {
                                unset($sessionData["items"][$refItemsID][$key_reset]);
                            }
                        }
                    }
                }
            }
//            }
        }
    }
    else {
        unset($sessionData['main_elements'][$elName]);
        //==masukkan ke gerbang utama
        unset($sessionData["main"][$elName]);


//        unset($sessionData["out_master"][$elName]);
    }

//    heGetTimedQuery($elementTimeStart, __LINE__);
    return $sessionData;
}


?>