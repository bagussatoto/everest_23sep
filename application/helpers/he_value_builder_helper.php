<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/3/2018
 * Time: 12:32 PM
 */

function resetValues($tr)
{
    $valueGateConfig = isset(config_item('heTransaksi_core')[$tr]['valueGates']) ? config_item('heTransaksi_core')[$tr]['valueGates'] : array();
    $valueBuilderConfig = isset(config_item('heTransaksi_core')[$tr]['valueBuilders']) ? config_item('heTransaksi_core')[$tr]['valueBuilders'] : array();
    $cCode = "_TR_" . $tr;

//    $_SESSION[$cCode]['out_detail'] = array();
//    $_SESSION[$cCode]['tableIn_master']=array();
    $_SESSION[$cCode]['tableIn_detail'] = array();
    $_SESSION[$cCode]['tableIn_detail2_sum'] = array();
    $_SESSION[$cCode]['main_add_fields'] = array();
    $_SESSION[$cCode]['main_add_values'] = array();
    $_SESSION[$cCode]['tableIn_master_values'] = array();
    $_SESSION[$cCode]['tableIn_detail_values'] = array();
    $_SESSION[$cCode]['tableIn_detail_values2_sum'] = array();
    $_SESSION[$cCode]['tableIn_sub_detail'] = array();


}

function fillValues($tr, $fromStep = 0, $intoStep = 0)
{
    $cCode = "_TR_" . $tr;


    /*
     *
     *
#1 [custom value gates]
- items yang perlu dihitung

#2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main


#3 [custom value builder]
- main yang perlu dihitung

$4 kalau ada yang perlu dipopulasi
#5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)

#6 [POPULATE SAMPING]
- populate items ke detail_values (kecuali yang termasuk exceptions)
- populate main ke main_values (kecuali yang termasuk exceptions)

#7 [OTHERS]
- valueInjectors
- pairMakers
- cloners		> dari out_master ke out_detail

#8 [NGOPI]
- tableIn_master	> dari main
- tableIn_detail	> dari items
- tableIn_detail2	> dari items2
- tableIn_detail_rsltItems	> dari rsltItems
     */


    $valueGateConfig = isset(config_item('heTransaksi_core')[$tr]['valueGates']) ? config_item('heTransaksi_core')[$tr]['valueGates'] : array();
    $tableInConfig = isset(config_item('heTransaksi_core')[$tr]['tableIn']) ? config_item('heTransaksi_core')[$tr]['tableIn'] : array();
    $tableInConfig_static = isset(config_item('heTransaksi_core')[$tr]['tableIn_static']) ? config_item('heTransaksi_core')[$tr]['tableIn_static'] : array();
    //value builder
    $valueBuilderConfig = isset(config_item('heTransaksi_core')[$tr]['valueBuilders']) ? config_item('heTransaksi_core')[$tr]['valueBuilders'] : array();
    $valueBuilderConfig2 = isset(config_item('heTransaksi_core')[$tr]['valueBuilders2']) ? config_item('heTransaksi_core')[$tr]['valueBuilders2'] : array();
    $valueBuilderConfig2_sum = isset(config_item('heTransaksi_core')[$tr]['valueBuilders2_sum']) ? config_item('heTransaksi_core')[$tr]['valueBuilders2_sum'] : array();
    $valueBuilderConfig_rsltItems = isset(config_item('heTransaksi_core')[$tr]['valueBuilders_rsltItems']) ? config_item('heTransaksi_core')[$tr]['valueBuilders_rsltItems'] : array();
    $valueBuilderConfig_rsltItems2 = isset(config_item('heTransaksi_core')[$tr]['valueBuilders_rsltItems2']) ? config_item('heTransaksi_core')[$tr]['valueBuilders_rsltItems2'] : array();

    //value spreaderadditionalBuilders
    $valueSpreaderConfig = isset(config_item('heTransaksi_core')[$tr]['valueSpreaders']) ? config_item('heTransaksi_core')[$tr]['valueSpreaders'] : array();

    $itemNumLabels = isset(config_item('heTransaksi_ui')[$tr]['shoppingCartNumFields']) ? config_item('heTransaksi_ui')[$tr]['shoppingCartNumFields'] : array();
    $detailValueFields = isset(config_item('heTransaksi_core')[$tr]['tableIn']['detailValues']) ? config_item('heTransaksi_core')[$tr]['tableIn']['detailValues'] : array();
    $availPayments = isset(config_item('heTransaksi_ui')[$tr]['availPayments']) ? config_item('heTransaksi_ui')[$tr]['availPayments'] : array();
    $tagihanSrc = isset(config_item('heTransaksi_ui')[$tr]['tagihanSrc']) ? config_item('heTransaksi_ui')[$tr]['tagihanSrc'] : "sisa";

    $pairMakers = isset(config_item("heTransaksi_ui")[$tr]['pairMakers'][$intoStep]) ? config_item("heTransaksi_ui")[$tr]['pairMakers'][$intoStep] : array();
    $pairInjectors = isset(config_item("heTransaksi_ui")[$tr]['pairInjectors'][$intoStep]) ? config_item("heTransaksi_ui")[$tr]['pairInjectors'][$intoStep] : array();
    $valueInjectors = isset(config_item("heTransaksi_ui")[$tr]['mainValueInjectors']) ? config_item("heTransaksi_ui")[$tr]['mainValueInjectors'] : array();


    ////
    $itemCloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();
    $itemClonerTargets = config_item('heGlobalPopulators') != null ? config_item('heGlobalPopulators') : array();
    $itemRecapExceptions = config_item('transaksi_itemRecapExceptions') != null ? config_item('transaksi_itemRecapExceptions') : array();
    $itemPopulateExceptions = config_item('transaksi_itemPopulateExceptions') != null ? config_item('transaksi_itemPopulateExceptions') : array();
    $masterPopulateExceptions = config_item('transaksi_masterPopulateExceptions') != null ? config_item('transaksi_masterPopulateExceptions') : array();
//    $cloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();

    $fixedItem_subValues = config_item('transaksi_fixedItem_subValues') != null ? config_item('transaksi_fixedItem_subValues') : array();
    $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();
    $fixedTableIn_values = config_item('transaksi_fixedTableIn_values') != null ? config_item('transaksi_fixedTableIn_values') : array();

    $populators = isset(config_item("heTransaksi_core")[$tr]['populators']) ? config_item("heTransaksi_core")[$tr]['populators'] : array();
    $addBuilders = isset(config_item("heTransaksi_core")[$tr]['additionalBuilders']) ? config_item("heTransaksi_core")[$tr]['additionalBuilders'] : array();
    $addMainBuilders = isset(config_item("heTransaksi_core")[$tr]['additionalMainBuilders']) ? config_item("heTransaksi_core")[$tr]['additionalMainBuilders'] : array();
    $extFormulaConfig = isset(config_item("heTransaksi_core")[$tr]['extFormula']) ? config_item("heTransaksi_core")[$tr]['extFormula'] : array();

    //sessionToGateAlwaysUpdaters
    $alwaysUpdaters = null != config_item("sessionToGateAlwaysUpdaters") ? config_item("sessionToGateAlwaysUpdaters") : array();

    $productCostInjector = isset(config_item("heTransaksi_ui")[$tr]['pairCostInjectors'][$intoStep]) ? config_item("heTransaksi_ui")[$tr]['pairCostInjectors'][$intoStep] : array();
    $gateExchangeConfig = isset(config_item("heTransaksi_ui")[$tr]['gateExchange']) ? config_item("heTransaksi_ui")[$tr]['gateExchange'] : array();

    $rowConfigRound = isset(config_item("heTransaksi_core")[$tr]['additionalRound']) ? config_item("heTransaksi_core")[$tr]['additionalRound'] : array();
    $additionalPostMainBuilder = isset(config_item("heTransaksi_core")[$tr]['additionalPostMainBuilder']) ? config_item("heTransaksi_core")[$tr]['additionalPostMainBuilder'] : array();
    //transaksi_fixedTableIn_subValues

    $ci =& get_instance();

//    cekHitam("session MAIN before master dependent");


    /*
     #1 [custom value gates]
- items yang perlu dihitung
     */

    // membalikkan key dengan value.....
    // value gerbang menjadi key dan ceil/floor menjadi value, seperti ini ->
    // ppn => floor
    // dpp => ceil
    foreach ($extFormulaConfig as $gateName => $extSpec) {
        foreach ($extSpec as $formula => $fSpec) {
            foreach ($fSpec as $gate) {
                $extFormula[$gateName][$gate] = $formula;
            }
        }
    }


    if (isset($valueGateConfig['detail'])) {
        if (sizeof($valueGateConfig['detail']) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {

                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $iCtr++;

                    if ($iSpec['jml'] > 0) {
                        foreach ($valueGateConfig['detail'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);

                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                                if (array_key_exists($key, $extFormula['detail'])) {
                                    $mFormula = $extFormula['detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $_SESSION[$cCode]['items'][$id][$key] = $srcValue;
                        }
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['rsltItems'])) {
        if (sizeof($valueGateConfig['rsltItems']) > 0) {
            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {

                $iCtr = 0;
                foreach ($_SESSION[$cCode]['rsltItems'] as $id => $iSpec) {
                    $iCtr++;
                    if ($iSpec['jml'] > 0) {
                        foreach ($valueGateConfig['rsltItems'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['rsltItems'][$id], $_SESSION[$cCode]['rsltItems'][$id], 0);
//                            cekPink(":: $key => $src  => $srcValue_tmp ::");

                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                                if (array_key_exists($key, $extFormula['detail'])) {
                                    $mFormula = $extFormula['detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $_SESSION[$cCode]['rsltItems'][$id][$key] = $srcValue;
                        }
                    }
                }
            }
        }
    }


    if (sizeof($fixedItem_subValues) > 0) {
        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                foreach ($fixedItem_subValues as $key => $src) {
                    $_SESSION[$cCode]['items'][$id][$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], "");
                    //cekbiru("filling $key on $id with " . $_SESSION[$cCode]['items'][$id][$key]);
                }
            }
        }

    }


    /*
     #2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main
     */

    if (sizeof($productCostInjector) > 0) {
        $source = $productCostInjector['source'];
        $target = $productCostInjector['target'];
        $jenis = $productCostInjector['jenis'];
        if (isset($_SESSION[$cCode][$source])) {
            foreach ($_SESSION[$cCode][$source] as $pID => $kSpec) {
                if (isset($kSpec[$jenis])) {
                    foreach ($kSpec[$jenis] as $h => $jSpec) {
                        if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                            foreach ($_SESSION[$cCode][$target] as $i => $rslt) {
                                if ($rslt['id'] == $pID) {
                                    foreach ($productCostInjector['kolom'] as $k => $v) {
                                        $jSpecName = str_replace(' ', '_', $jSpec['nama']);

//                                        $_SESSION[$cCode][$target][$i][$k."_".$jSpecName] = $jSpec[$v];
                                        $_SESSION[$cCode][$target][$i][$k . "_" . $h] = $jSpec[$v];

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

//    cekHijau(":: cetak rsltItems2");


    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================
//    cekHitam(":::: bagian exchange ::::");
    if (sizeof($gateExchangeConfig) > 0) {
//        $arrBlacklist = array();
        foreach ($gateExchangeConfig as $gateExchange) {
            if (isset($gateExchange['enabled']) && ($gateExchange['enabled'] == true)) {
                $source = $gateExchange['source'];
                $postfix = $gateExchange['postfix'];
                $blacklist = $gateExchange['blacklist'];
                $exchange = isset($_SESSION[$cCode]['main'][$source]) ? $_SESSION[$cCode]['main'][$source] : NULL; // main exchange
                $mainGate = array(
                    "main",
                );
                $detailGate = array(
                    "items",
                    "items2_sum",
                    "items3_sum",
                );
//
//                $arrBlacklist[] = $postfix . "__";
//                $arrBlacklist[] = "sub_" . $postfix . "__";
//                arrPrint($blacklist);

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    foreach ($mainGate as $gateName) {
                        if (isset($_SESSION[$cCode][$gateName]) && (sizeof($_SESSION[$cCode][$gateName]) > 0)) {
                            if ($exchange != NULL) {
                                foreach ($_SESSION[$cCode][$gateName] as $mainKey => $mainVal) {
                                    if (is_numeric($mainVal)) {

                                        $newPostfix = $postfix . "__";
                                        $subNewPostfix = "sub_" . $postfix . "__";

                                        $mainKey_ex = explode("__", $mainKey);

                                        // direset dulu
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $_SESSION[$cCode][$gateName][$postfix . "__" . $mainKey] = 0;
//                                            cekKuning("$gateName $postfix $mainKey direset menjadi 0");
                                        }
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $_SESSION[$cCode][$gateName][$postfix . "__" . $mainKey] = $mainVal * $exchange;
//                                            cekPink("$gateName $postfix $mainKey diisi menjadi $mainVal * $exchange " . $mainVal * $exchange);
                                        }
                                    }
                                }
                            }
                            else {
//                                cekMerah(":: $gateName [$source][$exchange] :: tidak mengalikan gerbang baru ::");
                            }
                        }
                    }
                }

                foreach ($detailGate as $gateName) {
                    if (isset($_SESSION[$cCode][$gateName]) && (sizeof($_SESSION[$cCode][$gateName]) > 0)) {
                        foreach ($_SESSION[$cCode][$gateName] as $iID => $detailSpec) {
                            if (sizeof($detailSpec) > 0) {
                                $exchange = isset($detailSpec[$source]) ? $detailSpec[$source] : NULL;
                                if ($exchange != NULL) {
//                                    cekHijau(":: $gateName [$exchange] :: mengalikan gerbang baru ::");
                                    foreach ($detailSpec as $detailKey => $detailVal) {
                                        if (is_numeric($detailVal)) {
                                            $newPostfix = $postfix . "__";
                                            $subNewPostfix = "sub_" . $postfix . "__";
                                            $detailPostFix = substr($detailKey, 0, strlen($subNewPostfix));

                                            $detailKey_ex = explode("__", $detailKey);


//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $_SESSION[$cCode][$gateName][$iID][$postfix . "__" . $detailKey] = 0;

//                                                cekKuning("$gateName $postfix $detailKey direset menjadi 0");
                                            }

//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $_SESSION[$cCode][$gateName][$iID][$postfix . "__" . $detailKey] = $detailVal * $exchange;

//                                                cekPink("$gateName $postfix $detailKey diisi menjadi $detailVal * $exchange " . $detailVal * $exchange);
                                            }
                                        }
                                    }
                                }
                                else {
//                                    cekMerah(":: $gateName [$exchange] :: tidak mengalikan gerbang baru ::");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================


    $recapMakers = array( //==sumber detail ke target main
        "itemsSrc1" => "main",
        "itemsSrc1_sum" => "main",

        "itemsTarget1" => "main",
        "itemSrcBendahara_sum" => "main",
        "itemSrc_sum" => "main",
        "items" => "main",

        "items2" => "main",
        "items2_sum" => "main",
        "rsltItems" => "main",
        "rsltItems2" => "main",
        "rsltItems3" => "main",
    );
    foreach ($recapMakers as $src => $target) {
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {

            $iCtr = 0;
            foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                $iCtr++;


                //===reset dulu
                if ($iCtr == 1) {
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {

                                if (!in_array($iKey, $itemRecapExceptions)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $_SESSION[$cCode][$target][$iKey] = 0;
                                    }
                                }
                            }
                        }
                    }
                }


                if (sizeof($iCols) > 0) {
                    foreach ($iCols as $iKey => $iVal) {
                        if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {

//                        if(is_numeric($iVal)){
                            if (!in_array($iKey, $itemRecapExceptions)) {
                                if (is_numeric($iVal)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $_SESSION[$cCode][$src][$iID]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID]["jml"] * $_SESSION[$cCode][$src][$iID][$iKey]);
                                        $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID]["jml"] * $iVal);

                                    }


                                }


                            }
//                        }
                        }

                    }
                }
            }
        }
    }


    /*
         #3 [custom value builder]
    - main yang perlu dihitung
         */

    if (isset($valueGateConfig['master'])) {
        if (sizeof($valueGateConfig['master']) > 0) {
            foreach ($valueGateConfig['master'] as $key => $src) {
                if (isset($_SESSION[$cCode]['main'][$src])) {

                    $_SESSION[$cCode]['main'][$key] = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                }

            }
        }
    }

    // ---- mater dependence MAIN
    //region master (dependen)
    if (isset($valueGateConfig['master_dependent'])) {
        if (sizeof($valueGateConfig['master_dependent']) > 0) {
            foreach ($valueGateConfig['master_dependent'] as $srcKey => $anuSpec) {
                if (isset($_SESSION[$cCode]['main'][$srcKey])) {
                    $srcValue = $_SESSION[$cCode]['main'][$srcKey];
                    if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                        foreach ($anuSpec[$srcValue] as $k => $src) {
                            $srcVal = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                            $_SESSION[$cCode]['main'][$k] = $srcVal;
                            // cekPink2("$k = $src ---> $srcVal");
                        }
                    }
                    else {
                        //cekhijau("$srcValue TIDAK memenuhi syarat");
                    }
                }
            }
        }
    }
    //endregion

    // ---- mater dependence ITEMS
    //region master (dependen)
    if (isset($valueGateConfig['master_dependent_items'])) {
        if (sizeof($valueGateConfig['master_dependent_items']) > 0) {
            foreach ($valueGateConfig['master_dependent_items'] as $srcKey => $anuSpec) {
                if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    foreach ($_SESSION[$cCode]['items'] as $ii => $iSpec) {

                        if (isset($iSpec[$srcKey])) {
                            $srcValue = $iSpec[$srcKey];
                            if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                                foreach ($anuSpec[$srcValue] as $k => $src) {
                                    $srcVal = makeValue($src, $iSpec, $iSpec, 0);
                                    $_SESSION[$cCode]['items'][$ii][$k] = $srcVal;
                                }
                            }
                            else {
                                //cekhijau("$srcValue TIDAK memenuhi syarat");
                            }
                        }

                    }
                }
            }
        }
    }
    //endregion


    if (sizeof($valueBuilderConfig) > 0) {
        foreach ($valueBuilderConfig as $key => $src) {
            $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($key, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                        cekPink2("$key apa adanya...");
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($key, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$key];
                    $srcValue = $mFormula($srcValue_tmp);
//                    cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }

            $_SESSION[$cCode]['main'][$key] = $srcValue;
//            cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
        }
    }
//    arrPrintWebs($_SESSION[$cCode]['main']);


// $4 kalau ada yang perlu dipopulasi

    if (sizeof($populators) > 0) {
        if (sizeof($_SESSION[$cCode]['items'])) {
            foreach ($populators as $popID => $popSpec) {
                $nilaiAsal = $_SESSION[$cCode]['main'][$popSpec['mainSrc']['key']];
                //cekmerah("nilaiAsal: $nilaiAsal");
                $targetKey = $popSpec['itemTarget']['key'];
                $maxAmountSrc = $popSpec['itemTarget']['maxAmountSrc'];
                foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
                    $maxItemAmount = $_SESSION[$cCode]['items'][$iID][$maxAmountSrc];
                    if ($nilaiAsal >= $maxItemAmount) {
                        $diambil = $maxItemAmount;
                        //cekmerah("ambil nilai dari maxItemAmount: $maxItemAmount");
                    }
                    else {
                        $diambil = $nilaiAsal;
                        //cekmerah("ambil nilai dari nilaiAsal: $nilaiAsal");
                    }
                    $nilaiAsal -= $diambil;
                    $_SESSION[$cCode]['items'][$iID][$targetKey] = $diambil;
                    //cekmerah("$targetKey akan diisi dengan $diambil");
                }

            }
        }
        else {
            //cekmerah("NO ITEMS TO inject");
        }

    }
    else {
        //cekmerah("populators are not ready");
    }
//    die("DONE POPULATING values");
    //pembulatan master untuk AR/ Ap payment cek jika nilai pembulatan vs row nilai
    if (sizeof($rowConfigRound) > 0) {
//        arrPrint($rowConfigRound);
        foreach ($rowConfigRound as $gate => $target) {
            $_SESSION[$cCode]['main'][$target] = round($_SESSION[$cCode]['main'][$gate]);
        }
//        matiHere("hoop value builder test");
    }
// #5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)

    if (sizeof($addBuilders) > 0) {
        if (sizeof($_SESSION[$cCode]['items'])) {
            foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
                foreach ($addBuilders as $key => $src) {
                    $_SESSION[$cCode]['items'][$iID][$key] = makeValue($src, $_SESSION[$cCode]['items'][$iID], $_SESSION[$cCode]['items'][$iID], 0);
                }

            }
        }
    }
    if (sizeof($addMainBuilders) > 0) {
        foreach ($addMainBuilders as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

        }
//        matiHEre("isi main builder");
    }

    if (count($additionalPostMainBuilder) > 0) {
        foreach ($additionalPostMainBuilder as $srcKey => $anuSpec) {
            if (isset($_SESSION[$cCode]['main'][$srcKey])) {
                $srcValue = $_SESSION[$cCode]['main'][$srcKey];
                if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                    foreach ($anuSpec[$srcValue] as $k => $src) {
                        $srcVal = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                        $_SESSION[$cCode]['main'][$k] = $srcVal;
//                        cekPink2("$k = $src ---> $srcVal");
                    }
                }
                else {
//                    cekhijau("$srcValue TIDAK memenuhi syarat");
                }
            }
        }
    }
//mati_disini(__LINE__);
    /*
        #6 [POPULATE]
        - populate items ke detail_values (kecuali yang termasuk exceptions)
    - populate main ke main_values (kecuali yang termasuk exceptions)
    */


    $populators = array( //==sumber2 yang dipopulasi
        "items" => "tableIn_detail_values",
        "items2" => "tableIn_detail_values2",
        "items2_sum" => "tableIn_detail_values2_sum",
        "rsltItems" => "tableIn_detail_values_rsltItems",
        "rsltItems2" => "tableIn_detail_values_rsltItems2",
    );
//    $populateTarget = "tableIn_detail_values";
    foreach ($populators as $src => $populateTarget) {
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                if (sizeof($iCols) > 0) {
                    if (!isset($_SESSION[$cCode][$populateTarget][$iID])) {
                        $_SESSION[$cCode][$populateTarget][$iID] = array();
                    }
                    foreach ($iCols as $iKey => $iVal) {
                        if (is_numeric($iVal) && !in_array($iKey, $itemPopulateExceptions)) {
                            $_SESSION[$cCode][$populateTarget][$iID][$iKey] = $iVal;
                        }
                    }
                }
            }
        }
    }

    $populators = array( //==sumber2 yang dipopulasi
        "main",

    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($_SESSION[$cCode][$populateTarget])) {
            $_SESSION[$cCode][$populateTarget] = array();
        }
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            foreach ($_SESSION[$cCode][$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $_SESSION[$cCode][$populateTarget][$key] = $val;
                }
            }
        }
    }


    //region tableIn_master
    //static
    if (isset($tableInConfig_static['master'])) {
        //static, main
        if (isset($tableInConfig_static['master']) & sizeof($tableInConfig_static['master']) > 0) {
            foreach ($tableInConfig_static['master'] as $fieldName => $staticValue) {
                $_SESSION[$cCode]['tableIn_master'][$fieldName] = $staticValue;
            }
        }
    }
    //non-static
    if (isset($tableInConfig['master'])) {
        //non-static, main
        if (sizeof($tableInConfig['master']) > 0) {
//            //echo "======================MENGISI PARAMETER UNTUK MASUK TABEL UTAMA <br>";
            foreach ($tableInConfig['master'] as $fieldName => $src) {

                if (isset($_SESSION[$cCode]['main'][$src])) {

                    $_SESSION[$cCode]['tableIn_master'][$fieldName] = $_SESSION[$cCode]['main'][$src];
                }
                else {
//                    //echo "nggak tau";
                }
//                //echo "<br>";
            }

        }

    }
    //endregion


    if (sizeof($fixedTableIn_values) > 0) {
        foreach ($fixedTableIn_values as $key => $src) {
            $_SESSION[$cCode]['tableIn_master'][$key] = isset($_SESSION[$cCode]['main'][$src]) ? $_SESSION[$cCode]['main'][$src] : "";
        }
    }


//    //arrprint($tableInConfig);die();
    //table in details
    $copiers = array(
        'detail' => array(
            "src" => "items",
            "target" => "tableIn_detail",
        ),

        'detail2' => array(
            "src" => "items2",
            "target" => "tableIn_detail2",
        ),

        'detail2_sum' => array(
            "src" => "items2_sum",
            "target" => "tableIn_detail2_sum",
        ),

        'detail_rsltItems' => array(
            "src" => "rsltItems",
            "target" => "tableIn_detail_rsltItems",
        ),
        'detail_rsltItems2' => array(
            "src" => "rsltItems2",
            "target" => "tableIn_detail_rsltItems2",
        ),
    );


    foreach ($copiers as $conf => $cSpec) {
        if (isset($tableInConfig[$conf]) && sizeof($tableInConfig[$conf]) > 0) {
            if (isset($_SESSION[$cCode][$cSpec['src']]) && sizeof($_SESSION[$cCode][$cSpec['src']]) > 0) {
                foreach ($_SESSION[$cCode][$cSpec['src']] as $iID => $iSpec) {
                    foreach ($tableInConfig[$conf] as $key => $src) {
                        if (substr($src, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($src, ".");
                            $realValue = $realCol;
//                                //echo "$key apa adanya: $realCol<br>";
                        }
                        else {
                            $realValue = isset($iSpec[$src]) ? $iSpec[$src] : "";
                        }
                        $_SESSION[$cCode][$cSpec['target']][$iID][$key] = $realValue;
                    }
                }
            }
        }
    }


    $copiers = array(
        'detail' => 'items',
        'detail2' => 'items2',
        'detail2_sum' => 'items2_sum',
        'detail_rsltItems' => 'rsltItems',
        'detail_rsltItems2' => 'rsltItems2',
    );
    foreach ($copiers as $conf => $iterator) {
        if (isset($tableInConfig_static[$conf]) && sizeof($tableInConfig_static[$conf]) > 0) {
            if (isset($_SESSION[$cCode][$iterator]) && sizeof($_SESSION[$cCode][$iterator]) > 0) {
                foreach ($_SESSION[$cCode][$iterator] as $iID => $iSpec) {
                    foreach ($tableInConfig_static[$conf] as $key => $val) {
                        $_SESSION[$cCode]['tableIn_' . $conf][$iID][$key] = $val;
                    }
                }
            }
        }
    }


    if (sizeof($valueInjectors) > 0) {
        ////cekmerah("ada value injector");
        foreach ($valueInjectors as $key => $val) {
            $value = isset($_SESSION[$cCode]['main'][$val]) ? $_SESSION[$cCode]['main'][$val] + 0 : 0;
            ////cekmerah("injecting $key with $value");
            echo "<script>";
            //echo "console.log('trying to inject $key with $value');";

            echo "if(top.document.getElementById('$key')){top.document.getElementById('$key').value='" . $value . "';}";
            echo "</script>";
        }
    }
    else {
        ////cekmerah("TAK ada value injector");
    }

//    $cloneTargets = array(
//        "items" => "main",
////        "items2" => "main",
//        "items2_sum" => "main",
//        "rsltItems" => "main",
//        "rsltItems2" => "main",
//    );
    if (sizeof($itemClonerTargets) > 0) {

        foreach ($itemClonerTargets as $target => $src) {

            if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                foreach ($_SESSION[$cCode][$target] as $iID => $iSpec) {
                    foreach ($itemCloners as $key) {
                        if (isset($_SESSION[$cCode][$src][$key])) {

                            $_SESSION[$cCode][$target][$iID][$key] = $_SESSION[$cCode][$src][$key];
                        }
                    }
                }
            }
        }
    }


    if (sizeof($pairMakers) > 0) {
        foreach ($pairMakers as $prKey => $prSpec) {
//arrPrint($prSpec);
            $ci->load->helper("Pairs/" . $prSpec['helperName']);
            $result = $prSpec['functionName']($tr, $intoStep, $prSpec['params']);
            $_SESSION[$cCode]['pairs'][$prKey] = $result;

        }
//mati_disini();
        if (sizeof($pairInjectors) > 0) {
            foreach ($pairInjectors as $keyInjector => $viSpec) {
                if (array_key_exists($keyInjector, $pairMakers)) {
                    foreach ($viSpec as $gateName => $vgSpec) {
                        if (isset($_SESSION[$cCode][$gateName])) {
                            foreach ($_SESSION[$cCode][$gateName] as $cKey => $vSpec) {
                                if (array_key_exists($cKey, $_SESSION[$cCode]['pairs'][$keyInjector])) {
                                    if (is_array($_SESSION[$cCode]['pairs'][$keyInjector][$cKey])) {
                                        $urut = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey]['urut'];
                                        $val = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey]['value'];
                                        $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn'] . "_" . $urut] = $val;
                                    }
                                    else {
                                        $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn']] = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey];
                                    }
                                }
                                else {
                                    $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn']] = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
//        arrPrint($_SESSION[$cCode]['items2_sum']);
//        mati_disini();
    }


    if (($fromStep = 0 && $intoStep == 0) || ($fromStep = 1 && $intoStep == 1)) {//==ini pembuatan baru
//            ////cekMerah("build values saat buat baru");
        $addFieldValues = array(
            "jenis" => config_item('heTransaksi_ui')[$tr]['steps'][1]['target'],
            "transaksi_jenis" => config_item('heTransaksi_ui')[$tr]['steps'][1]['target'],
        );
        if (isset(config_item('heTransaksi_ui')[$tr]['steps'][2])) {
            $addFieldValues['next_step_code'] = config_item('heTransaksi_ui')[$tr]['steps'][2]['target'];
            $addFieldValues['next_group_code'] = config_item('heTransaksi_ui')[$tr]['steps'][2]['userGroup'];
            $addFieldValues['step_number'] = 1;
            $addFieldValues['step_current'] = 1;

            $addSubFieldValues['next_substep_code'] = config_item('heTransaksi_ui')[$tr]['steps'][2]['target'];
            $addSubFieldValues['next_subgroup_code'] = config_item('heTransaksi_ui')[$tr]['steps'][2]['userGroup'];
            $addSubFieldValues['sub_step_number'] = 1;
            $addSubFieldValues['sub_step_current'] = 1;

        }
        else {
            $addFieldValues['next_step_code'] = "";
            $addFieldValues['next_group_code'] = "";
            $addFieldValues['step_number'] = $intoStep;
            $addFieldValues['step_current'] = 0;

            $addSubFieldValues['next_substep_code'] = "";
            $addSubFieldValues['next_subgroup_code'] = "";
            $addSubFieldValues['sub_step_number'] = $intoStep;
            $addSubFieldValues['sub_step_current'] = 0;
        }
    }
    else {//==ini manipulasi saat edit
//            ////cekMerah("build values saat EDIT");
        $addFieldValues = array(
            "jenis" => config_item('heTransaksi_ui')[$tr]['steps'][$intoStep]['target'],
            "transaksi_jenis" => config_item('heTransaksi_ui')[$tr]['steps'][$intoStep]['target'],
        );
        $addFieldValues['next_step_code'] = "";
        $addFieldValues['next_group_code'] = "";
        $addFieldValues['step_number'] = $intoStep;
        $addFieldValues['step_current'] = 0;

        $addSubFieldValues['next_substep_code'] = "";
        $addSubFieldValues['next_subgroup_code'] = "";
        $addSubFieldValues['sub_step_number'] = $intoStep;
        $addSubFieldValues['sub_step_current'] = 0;
    }


    foreach ($addFieldValues as $fName => $value) {
        $_SESSION[$cCode]['main'][$fName] = $value;
        $_SESSION[$cCode]['tableIn_master'][$fName] = $value;
    }


    if (isset($addSubFieldValues) && sizeof($addSubFieldValues) > 0) {

        if (sizeof($_SESSION[$cCode]['items']) > 0 && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['items'][$iID][$fName] = $value;
                }
            }
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1,
                    //                    "valid_qty"       => $_SESSION[$cCode]['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($_SESSION[$cCode]['items'][$iID][$src])) {
                            $_SESSION[$cCode]['tableIn_detail'][$iID][$target] = $_SESSION[$cCode]['items'][$iID][$src];
                        }
                    }
                }
            }

        }
    }


//    if(isset($_GET['confirm']) && $_GET['confirm']=="1"){
//
//    }else{
//
//        echo "<script>\n";
//        echo "if(top.document.getElementById('ck')){top.document.getElementById('ck').checked=false;}\n";
//        echo "if(top.document.getElementById('btnProcess')){top.document.getElementById('btnProcess').disabled=true;}\n";
//        echo "</script>\n";
//    }


    // khusus setoran......................................................................
    $ci->load->model("MdlTransaksi");

    $additionalSource = isset(config_item("heTransaksi_core")[$tr]['additionalSource']) ? config_item("heTransaksi_core")[$tr]['additionalSource'] : false;
    $additionalItemSource = isset(config_item("heTransaksi_core")[$tr]['additionalItemSource']) ? config_item("heTransaksi_core")[$tr]['additionalItemSource'] : array();
    $additionalItemResult = isset(config_item("heTransaksi_core")[$tr]['additionalItemResult']) ? config_item("heTransaksi_core")[$tr]['additionalItemResult'] : array();
    $additionalItemSourceKey = isset(config_item("heTransaksi_core")[$tr]['additionalItemSourceKey']) ? config_item("heTransaksi_core")[$tr]['additionalItemSourceKey'] : array();
    $additionalPembulatan = isset(config_item("heTransaksi_core")[$tr]['valueInjectorBulat']) ? config_item("heTransaksi_core")[$tr]['valueInjectorBulat'] : array();
    if ($additionalSource == true) {
        if (sizeof($additionalItemSource)) {
            //cekUngu("cetak ITEMS AWAL...");
            //arrPrint($_SESSION[$cCode]['items']);
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {

                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                $trr->addFilter("param='main'");
                $trr->addFilter("transaksi_id='$id'");
                $tmpR = $trr->lookupRegistries()->result();
                $main = blobDecode($tmpR[0]->values);

                //cekUngu(":: MAIN ID $id");
//                arrPrint($main);
//                mati_disini();
                //cekUngu(":: ITEMS ID $id");
                //arrPrint($iSpec);

                foreach ($additionalItemSource as $key => $val) {
//                    if (!isset($_SESSION[$cCode]['items'][$id][$key])) {

                    $new_key = (sizeof($additionalItemResult) > 0 && (isset($additionalItemResult[$key]))) ? $additionalItemResult[$key] : $key;
                    if (!isset($_SESSION[$cCode]['items'][$id][$new_key])) {

                        $_SESSION[$cCode]['items'][$id][$new_key] = 0;
                    }

                    if (sizeof($additionalItemSourceKey) > 0) {
                        $persenValue = ($_SESSION[$cCode]['items'][$id][$additionalItemSourceKey['top']] / $main[$additionalItemSourceKey['bottom']]) * 100;
                    }
                    else {
                        $persenValue = 0;
                    }
                    $key_result = makeValue($val, $main, $main, 0);
                    $_SESSION[$cCode]['items'][$id]['persenValue'] = $persenValue;
                    $_SESSION[$cCode]['items'][$id][$new_key] = ($persenValue / 100) * $key_result;


//                    }
                }
            }

//                $persenValue = ($_SESSION[$cCode]['items'][$id]['nilai_bayar']/$_SESSION[$cCode]['items'][$id]['harga_nett2'])*100;
//                $_SESSION[$cCode]['items'][$id]['persenValue'] = $persenValue;
//                foreach ($additionalItemSource as $key => $val){
//
//                    $_SESSION[$cCode]['items'][$id]['source_'.$key] = ($_SESSION[$cCode]['items'][$id][$key]*$persenValue)/100;
//                }
        }
//        mati_disini();
    }

    if (sizeof($alwaysUpdaters) > 0) {
        foreach ($alwaysUpdaters as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = isset($ci->session->login[$src]) ? $ci->session->login[$src] : "";
        }
    }


    if (sizeof($additionalPembulatan) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatan['source'];
        $varBulat = makeDppBulat($_SESSION[$cCode]['main'][$source]);
        foreach ($additionalPembulatan['injectTo'] as $key => $fields) {
            $srcValue_tmp = $varBulat[$key];
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($fields, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink2("$fields :: asli -> $srcValue_tmp :: bulat -> $srcValue");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($fields, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$fields];
                    $srcValue = $mFormula($srcValue_tmp);
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }
            $_SESSION[$cCode]['main'][$fields] = $srcValue;
        }
    }
//mati_disini(__FUNCTION__);


    //region valueReplaceCalculate
    $valueReplaceCalculate = isset(config_item("heTransaksi_core")[$tr]['valueReplaceCalculate']) ? config_item("heTransaksi_core")[$tr]['valueReplaceCalculate'] : array();
    if (sizeof($valueReplaceCalculate) > 0) {
        foreach ($valueReplaceCalculate as $gate) {
            $curentVal = $_SESSION[$cCode]['main'][$gate];
            if ($curentVal > 0) {

            }
            else {
                $_SESSION[$cCode]['main'][$gate] = 0;
                $_SESSION[$cCode]['tableIn_master_values'][$gate] = 0;
            }
//            cekHitam($gate);
        }
    }


    //endregion


    //------------------------------------------------------------------
    $recapItemBuilder = isset(config_item("heTransaksi_core")[$tr]['recapItemBuilder']) ? config_item("heTransaksi_core")[$tr]['recapItemBuilder'] : array();
    if (sizeof($recapItemBuilder) > 0) {
        $gateNameSource = $recapItemBuilder['gateNameSource'];
        $gateNameTarget = $recapItemBuilder['gateNameTarget'];
        $key = $recapItemBuilder['key'];
        $vals = $recapItemBuilder['val'];

        //------ hapus/reset items2_sum
        if (isset($_SESSION[$cCode]['items4_sum'])) {
            $_SESSION[$cCode]['items4_sum'] = null;
            unset($_SESSION[$cCode]['items4_sum']);
        }

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $ii => $iSpec) {
                //------ build ulang items2_sum
                if (!isset($_SESSION[$cCode]['items4_sum'][$iSpec[$key]])) {
                    $iSpec['id'] = $iSpec[$key];
                    $_SESSION[$cCode]['items4_sum'][$iSpec[$key]] = $iSpec;

                    foreach ($vals as $val) {
                        $_SESSION[$cCode]['items4_sum'][$iSpec[$key]][$val] = 0;
                    }
                }
                //------ build ulang items2_sum
                foreach ($vals as $val) {
                    $_SESSION[$cCode]['items4_sum'][$iSpec[$key]][$val] += $iSpec[$val];
                }
            }
        }
    }

//    arrPrintWebs($_SESSION[$cCode]['main']);
//    mati_disini("value builder helper");

}

//versi MODUL-------------------------
function resetValues_he_value_builder($tr, $configCoreJenis)
{
    $valueGateConfig = isset($configCoreJenis['valueGates']) ? $configCoreJenis['valueGates'] : array();
    $valueBuilderConfig = isset($configCoreJenis['valueBuilders']) ? $configCoreJenis['valueBuilders'] : array();
    $cCode = "_TR_" . $tr;

    $_SESSION[$cCode]['tableIn_detail'] = array();
    $_SESSION[$cCode]['tableIn_detail2_sum'] = array();
    $_SESSION[$cCode]['main_add_fields'] = array();
    $_SESSION[$cCode]['main_add_values'] = array();
    $_SESSION[$cCode]['tableIn_master_values'] = array();
    $_SESSION[$cCode]['tableIn_detail_values'] = array();
    $_SESSION[$cCode]['tableIn_detail_values2_sum'] = array();


}

function fillValues_he_value_builder($tr, $fromStep = 0, $intoStep = 0, $configCoreJenis = array(), $configUiJenis = array(), $configValuesJenis = array(), $ppnFactor = 0, $jenisTr_references = null)
{
    $cCode = cCodeBuilderMisc($tr);

    /*
     *
     *
#1 [custom value gates]
- items yang perlu dihitung

#2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main


#3 [custom value builder]
- main yang perlu dihitung

$4 kalau ada yang perlu dipopulasi
#5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)

#6 [POPULATE SAMPING]
- populate items ke detail_values (kecuali yang termasuk exceptions)
- populate main ke main_values (kecuali yang termasuk exceptions)

#7 [OTHERS]
- valueInjectors
- pairMakers
- cloners		> dari out_master ke out_detail

#8 [NGOPI]
- tableIn_master	> dari main
- tableIn_detail	> dari items
- tableIn_detail2	> dari items2
- tableIn_detail_rsltItems	> dari rsltItems
     */


    $valueGateConfig = isset($configCoreJenis['valueGates']) ? $configCoreJenis['valueGates'] : array();
    $tableInConfig = isset($configCoreJenis['tableIn']) ? $configCoreJenis['tableIn'] : array();
    $tableInConfig_static = isset($configCoreJenis['tableIn_static']) ? $configCoreJenis['tableIn_static'] : array();
    //value builder
    $valueBuilderConfig = isset($configCoreJenis['valueBuilders']) ? $configCoreJenis['valueBuilders'] : array();
    $valueBuilderConfig2 = isset($configCoreJenis['valueBuilders2']) ? $configCoreJenis['valueBuilders2'] : array();
    $valueBuilderConfig2_sum = isset($configCoreJenis['valueBuilders2_sum']) ? $configCoreJenis['valueBuilders2_sum'] : array();
    $valueBuilderConfig_rsltItems = isset($configCoreJenis['valueBuilders_rsltItems']) ? $configCoreJenis['valueBuilders_rsltItems'] : array();
    $valueBuilderConfig_rsltItems2 = isset($configCoreJenis['valueBuilders_rsltItems2']) ? $configCoreJenis['valueBuilders_rsltItems2'] : array();

    //value spreaderadditionalBuilders
    $valueSpreaderConfig = isset($configCoreJenis['valueSpreaders']) ? $configCoreJenis['valueSpreaders'] : array();

    $itemNumLabels = isset($configUiJenis['shoppingCartNumFields']) ? $configUiJenis['shoppingCartNumFields'] : array();
    $detailValueFields = isset($configCoreJenis['tableIn']['detailValues']) ? $configCoreJenis['tableIn']['detailValues'] : array();
    $availPayments = isset($configUiJenis['availPayments']) ? $configUiJenis['availPayments'] : array();
    $tagihanSrc = isset($configUiJenis['tagihanSrc']) ? $configUiJenis['tagihanSrc'] : "sisa";

    $pairMakers = isset($configUiJenis['pairMakers'][$intoStep]) ? $configUiJenis['pairMakers'][$intoStep] : array();
    $pairMakersProject = isset($configUiJenis['pairMakersProject'][$intoStep]) ? $configUiJenis['pairMakersProject'][$intoStep] : array();
    $pairElementMakers = isset($configUiJenis['pairElementGateBuilder'][$intoStep]) ? $configUiJenis['pairElementGateBuilder'][$intoStep] : array();
    $pairInjectors = isset($configUiJenis['pairInjectors'][$intoStep]) ? $configUiJenis['pairInjectors'][$intoStep] : array();
    $pairInjectorsProject = isset($configUiJenis['pairInjectorsProject'][$intoStep]) ? $configUiJenis['pairInjectorsProject'][$intoStep] : array();
    $valueInjectors = isset($configUiJenis['mainValueInjectors']) ? $configUiJenis['mainValueInjectors'] : array();


    ////
    $itemCloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();
    $itemClonerTargets = config_item('heGlobalPopulators') != null ? config_item('heGlobalPopulators') : array();
    $itemClonerTarget_sub = config_item('GlobalPopulator_sub') != null ? config_item('GlobalPopulator_sub') : array();
    $itemRecapExceptions = config_item('transaksi_itemRecapExceptions') != null ? config_item('transaksi_itemRecapExceptions') : array();
    $subItemRecapExceptions = config_item('transaksi_subitemRecapExceptions') != null ? config_item('transaksi_subitemRecapExceptions') : array();
    $itemPopulateExceptions = config_item('transaksi_itemPopulateExceptions') != null ? config_item('transaksi_itemPopulateExceptions') : array();
    $masterPopulateExceptions = config_item('transaksi_masterPopulateExceptions') != null ? config_item('transaksi_masterPopulateExceptions') : array();
//    $cloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();

    $fixedItem_subValues = config_item('transaksi_fixedItem_subValues') != null ? config_item('transaksi_fixedItem_subValues') : array();
    $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();
    $fixedTableIn_values = config_item('transaksi_fixedTableIn_values') != null ? config_item('transaksi_fixedTableIn_values') : array();

    $populatorsGate = isset($configCoreJenis['populatorsGate']) ? $configCoreJenis['populatorsGate'] : "items";
    $populators = isset($configCoreJenis['populators']) ? $configCoreJenis['populators'] : array();
    $addBuilders = isset($configCoreJenis['additionalBuilders']) ? $configCoreJenis['additionalBuilders'] : array();
    $addMainBuilders = isset($configCoreJenis['additionalMainBuilders']) ? $configCoreJenis['additionalMainBuilders'] : array();
    $extFormulaConfig = isset($configCoreJenis['extFormula']) ? $configCoreJenis['extFormula'] : array();

    $additionalPostMainBuilder = isset($configCoreJenis['additionalPostMainBuilder']) ? $configCoreJenis['additionalPostMainBuilder'] : array();
    //sessionToGateAlwaysUpdaters
    $alwaysUpdaters = null != config_item("sessionToGateAlwaysUpdaters") ? config_item("sessionToGateAlwaysUpdaters") : array();

    $productCostInjector = isset($configUiJenis['pairCostInjectors'][$intoStep]) ? $configUiJenis['pairCostInjectors'][$intoStep] : array();
    $gateExchangeConfig = isset($configUiJenis['gateExchange']) ? $configUiJenis['gateExchange'] : array();

    $rowConfigRound = isset($configCoreJenis['additionalRound']) ? $configCoreJenis['additionalRound'] : array();
    $recapValueException = isset($configCoreJenis['recapValueException']) ? $configCoreJenis['recapValueException'] : array();
    //-----------------------------
    $valueSubDetail = isset($configCoreJenis['valueSubDetail']) ? $configCoreJenis['valueSubDetail'] : false;
    $valueSubDetailRecap = isset($configCoreJenis['valueSubDetailRecap']) ? $configCoreJenis['valueSubDetailRecap'] : array();

    //transaksi_fixedTableIn_subValues

    $ci =& get_instance();

    // cekHitam("session MAIN before master dependent");

    if ($valueSubDetail == true) {
        if (isset($valueGateConfig['detail2'])) {
            if (sizeof($valueGateConfig['detail2']) > 0) {
                if (isset($_SESSION[$cCode]['items2']) && sizeof($_SESSION[$cCode]['items2']) > 0) {
                    foreach ($_SESSION[$cCode]['items2'] as $id => $subSpec) {
                        foreach ($subSpec as $ii => $sSpec) {
                            foreach ($valueGateConfig['detail2'] as $key => $src) {
                                $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items2'][$id][$ii], $_SESSION[$cCode]['items2'][$id][$ii], 0);
                                $srcValue = $srcValue_tmp;
                                $_SESSION[$cCode]['items2'][$id][$ii][$key] = $srcValue;
                                $_SESSION[$cCode]['items2'][$id][$ii]["sub_" . $key] = $sSpec['jml'] * $srcValue;
                            }
                        }
                    }
                    // direcap ke items
                    $target_recap = "items";
                    foreach ($_SESSION[$cCode]['items2'] as $id => $subSpec) {
                        $noCtr = 0;
                        foreach ($subSpec as $ii => $sSpec) {
                            $noCtr++;
                            if ($noCtr == 1) {
                                foreach ($valueSubDetailRecap as $irecap) {
                                    if (substr($irecap, 0, 4) != "sub_") {
                                        $_SESSION[$cCode][$target_recap][$id][$irecap] = 0;
                                    }
                                }
                            }

                            foreach ($sSpec as $skey => $sval) {
                                if ((in_array($skey, $valueSubDetailRecap)) && (is_numeric($sval))) {
                                    if (substr($skey, 0, 4) != "sub_") {
                                        $_SESSION[$cCode]['items2'][$id][$ii]["sub_" . $skey] = ($_SESSION[$cCode]['items2'][$id][$ii]["jml"] * $_SESSION[$cCode]['items2'][$id][$ii][$skey]);
                                        $_SESSION[$cCode][$target_recap][$id][$skey] += ($_SESSION[$cCode]['items2'][$id][$ii]["jml"] * $sval);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     #1 [custom value gates]
- items yang perlu dihitung
     */

    // membalikkan key dengan value.....
    // value gerbang menjadi key dan ceil/floor menjadi value, seperti ini ->
    // ppn => floor
    // dpp => ceil
    foreach ($extFormulaConfig as $gateName => $extSpec) {
        foreach ($extSpec as $formula => $fSpec) {
            foreach ($fSpec as $gate) {
                $extFormula[$gateName][$gate] = $formula;
            }
        }
    }

    /*
 * untuk handling tertinggal 1x klik karena gerbang nilai perlu direkap dari main ke item
 * sengaja didobel disini, yang di bawah tetapa ada
 */
    if (sizeof($itemClonerTargets) > 0) {
        foreach ($itemClonerTargets as $target => $src) {

            if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                foreach ($_SESSION[$cCode][$target] as $iID => $iSpec) {
                    foreach ($itemCloners as $key) {
                        if (isset($_SESSION[$cCode][$src][$key])) {

//                            cekHitam("yang diinject" . $key . " =" . $_SESSION[$cCode][$src][$key]);
                            $_SESSION[$cCode][$target][$iID][$key] = $_SESSION[$cCode][$src][$key];
                        }
                    }
                }
            }
        }
//        matiHEre(__LINE__);
    }

    if (isset($valueGateConfig['detail'])) {
        if (sizeof($valueGateConfig['detail']) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                        if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                            if (array_key_exists($key, $extFormula['detail'])) {
                                $mFormula = $extFormula['detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $_SESSION[$cCode]['items'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['rsltItems'])) {
        if (sizeof($valueGateConfig['rsltItems']) > 0) {
            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {
                $iCtr = 0;
                foreach ($_SESSION[$cCode]['rsltItems'] as $id => $iSpec) {
                    $iCtr++;
                    if ($iSpec['jml'] > 0) {
                        foreach ($valueGateConfig['rsltItems'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['rsltItems'][$id], $_SESSION[$cCode]['rsltItems'][$id], 0);
                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                                if (array_key_exists($key, $extFormula['detail'])) {
                                    $mFormula = $extFormula['detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $_SESSION[$cCode]['rsltItems'][$id][$key] = $srcValue;
                        }
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['detail2_sum'])) {
        if (sizeof($valueGateConfig['detail2_sum']) > 0) {
            if (isset($_SESSION[$cCode]['items2_sum']) && sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items2_sum'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail2_sum'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items2_sum'][$id], $_SESSION[$cCode]['items2_sum'][$id], 0);
                        if (isset($extFormula['detail2_sum']) && sizeof($extFormula['detail2_sum']) > 0) {
                            if (array_key_exists($key, $extFormula['detail2_sum'])) {
                                $mFormula = $extFormula['detail2_sum'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $_SESSION[$cCode]['items2_sum'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['sub_detail'])) {
        if (sizeof($valueGateConfig['sub_detail']) > 0) {
            if (isset($_SESSION[$cCode]['items2_sum']) && sizeof($_SESSION[$cCode]['items2_sum']) > 0) {

                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items2_sum'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items2_sum'][$id], $_SESSION[$cCode]['items2_sum'][$id], 0);

                        if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail'])) {
                                $mFormula = $extFormula['sub_detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $_SESSION[$cCode]['items2_sum'][$id][$key] = $srcValue;
                    }

                    //                    }
                }
            }
            if (isset($_SESSION[$cCode]['items2']) && sizeof($_SESSION[$cCode]['items2']) > 0) {

                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items2'] as $id => $ixSpec) {
                    $iCtr++;

                    foreach ($ixSpec as $ii => $iSpec) {
                        foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $iSpec, $iSpec, 0);
                            if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                                if (array_key_exists($key, $extFormula['sub_detail'])) {
                                    $mFormula = $extFormula['sub_detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $_SESSION[$cCode]['items2'][$id][$ii][$key] = $srcValue;
                        }
                    }


                    //                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['sub_detail_items'])) {
        if (sizeof($valueGateConfig['sub_detail_items']) > 0) {
            if (isset($_SESSION[$cCode]['rsltItems3_sub']) && sizeof($_SESSION[$cCode]['rsltItems3_sub']) > 0) {

                $iCtr = 0;
                foreach ($_SESSION[$cCode]['rsltItems3_sub'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['rsltItems3_sub'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['rsltItems3_sub'][$id], $_SESSION[$cCode]['rsltItems3_sub'][$id], 0);

                        if (isset($extFormula['sub_detail_items']) && sizeof($extFormula['sub_detail_items']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail_items'])) {
                                $mFormula = $extFormula['sub_detail_items'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $_SESSION[$cCode]['rsltItems3_sub'][$id][$key] = $srcValue;
                    }
                    //                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['detail4_sum'])) {
        if (sizeof($valueGateConfig['detail4_sum']) > 0) {
            if (isset($_SESSION[$cCode]['items4_sum']) && sizeof($_SESSION[$cCode]['items4_sum']) > 0) {
                $iCtr = 0;
                foreach ($_SESSION[$cCode]['items4_sum'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail4_sum'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['items4_sum'][$id], $_SESSION[$cCode]['items4_sum'][$id], 0);
                        if (isset($extFormula['detail4_sum']) && sizeof($extFormula['detail4_sum']) > 0) {
                            if (array_key_exists($key, $extFormula['detail4_sum'])) {
                                $mFormula = $extFormula['detail4_sum'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $_SESSION[$cCode]['items4_sum'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }

    if (sizeof($fixedItem_subValues) > 0) {
        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                foreach ($fixedItem_subValues as $key => $src) {
                    $_SESSION[$cCode]['items'][$id][$key] = makeValue($src, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], "");
                    //cekbiru("filling $key on $id with " . $_SESSION[$cCode]['items'][$id][$key]);
                }
            }
        }

    }

    /*
     #2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main
     */

    if (sizeof($productCostInjector) > 0) {
        $source = $productCostInjector['source'];
        $target = $productCostInjector['target'];
        $jenis = $productCostInjector['jenis'];
        if (isset($_SESSION[$cCode][$source])) {
            foreach ($_SESSION[$cCode][$source] as $pID => $kSpec) {
                if (isset($kSpec[$jenis])) {
                    foreach ($kSpec[$jenis] as $h => $jSpec) {
                        if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                            foreach ($_SESSION[$cCode][$target] as $i => $rslt) {
                                if ($rslt['id'] == $pID) {
                                    foreach ($productCostInjector['kolom'] as $k => $v) {
                                        $jSpecName = str_replace(' ', '_', $jSpec['nama']);

//                                        $_SESSION[$cCode][$target][$i][$k."_".$jSpecName] = $jSpec[$v];
                                        $_SESSION[$cCode][$target][$i][$k . "_" . $h] = $jSpec[$v];

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================
//    cekHitam(":::: bagian exchange :::: || FILE: " . __FILE__);
    if (sizeof($gateExchangeConfig) > 0) {
        foreach ($gateExchangeConfig as $gateExchange) {
            if (isset($gateExchange['enabled']) && ($gateExchange['enabled'] == true)) {
                $source = $gateExchange['source'];
                $postfix = $gateExchange['postfix'];
                $blacklist = $gateExchange['blacklist'];
                $exchange = isset($_SESSION[$cCode]['main'][$source]) ? $_SESSION[$cCode]['main'][$source] : NULL; // main exchange
                $mainGate = array(
                    "main",
                );
                $detailGate = array(
                    "items",
                    "items2_sum",
                    "items3_sum",
                    "items5_sum",
                );

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    foreach ($mainGate as $gateName) {
                        if (isset($_SESSION[$cCode][$gateName]) && (sizeof($_SESSION[$cCode][$gateName]) > 0)) {
                            if ($exchange != NULL) {
                                foreach ($_SESSION[$cCode][$gateName] as $mainKey => $mainVal) {
                                    if (is_numeric($mainVal)) {
                                        $newPostfix = $postfix . "__";
                                        $subNewPostfix = "sub_" . $postfix . "__";
                                        $mainKey_ex = explode("__", $mainKey);
                                        // direset dulu
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $_SESSION[$cCode][$gateName][$postfix . "__" . $mainKey] = 0;
//                                            cekKuning("$gateName $postfix $mainKey direset menjadi 0");
                                        }
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $_SESSION[$cCode][$gateName][$postfix . "__" . $mainKey] = $mainVal * $exchange;
//                                            cekPink("$gateName $postfix $mainKey diisi menjadi $mainVal * $exchange " . $mainVal * $exchange);
                                        }
                                    }
                                }
                            }
                            else {
//                                cekMerah(":: $gateName [$source][$exchange] :: tidak mengalikan gerbang baru ::");
                            }
                        }
                    }
                }

                foreach ($detailGate as $gateName) {
                    if (isset($_SESSION[$cCode][$gateName]) && (sizeof($_SESSION[$cCode][$gateName]) > 0)) {
                        foreach ($_SESSION[$cCode][$gateName] as $iID => $detailSpec) {
                            if (sizeof($detailSpec) > 0) {
                                $exchange = isset($detailSpec[$source]) ? $detailSpec[$source] : NULL;
                                if ($exchange != NULL) {
//                                    cekHijau(":: $gateName [$exchange] :: mengalikan gerbang baru ::");
                                    foreach ($detailSpec as $detailKey => $detailVal) {
                                        if (is_numeric($detailVal)) {
                                            $newPostfix = $postfix . "__";
                                            $subNewPostfix = "sub_" . $postfix . "__";
                                            $detailPostFix = substr($detailKey, 0, strlen($subNewPostfix));

                                            $detailKey_ex = explode("__", $detailKey);


//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $_SESSION[$cCode][$gateName][$iID][$postfix . "__" . $detailKey] = 0;

//                                                cekKuning("$gateName $postfix $detailKey direset menjadi 0");
                                            }

//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $_SESSION[$cCode][$gateName][$iID][$postfix . "__" . $detailKey] = $detailVal * $exchange;

//                                                cekPink("$gateName $postfix $detailKey diisi menjadi $detailVal * $exchange " . $detailVal * $exchange);
                                            }
                                        }
                                    }
                                }
                                else {
//                                    cekMerah(":: $gateName [$exchange] :: tidak mengalikan gerbang baru ::");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================


    $recapMakers = array( //==sumber detail ke target main
        "itemsSrc1" => "main",
        "itemsSrc1_sum" => "main",
        "itemsTarget1" => "main",
        "itemSrcBendahara_sum" => "main",
        "itemSrc_sum" => "main",
        "items" => "main",

        "items2" => "main",
        "items2_sum" => "main",
        "items3_sum" => "main",
        "items4_sum" => "main",
        "items5_sum" => "main",
//        "items6_sum" => "main",// me-summary komposisi dari paket
        "rsltItems" => "main",
        "rsltItems2" => "main",
        "rsltItems3" => "main",
        //------
        "rsltItems_revert" => "main",
//        "items6"=>"main",
    );

    switch ($tr) {
        default:
            break;
        case "9912":
            $skiper = $_SESSION[$cCode]["main"]["pihakExternID"];
            if ($skiper == "4467") {
                unset($recapMakers["items4_sum"]);
                unset($recapMakers["items5_sum"]);
            }
            break;
    }

    //untuk reset jika tidak diperbolehkan direkap ke main, baca dari configCore:: recapValueException
    if (sizeof($recapValueException) > 0) {
        foreach ($recapValueException as $valmakerKey) {
            if (isset($recapMakers[$valmakerKey])) {
                unset($recapMakers[$valmakerKey]);
            }
        }
    }

    foreach ($recapMakers as $src => $target) {
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            $iCtr = 0;
            foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                $iCtr++;
                //===reset dulu
                if ($iCtr == 1) {
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                if (!in_array($iKey, $itemRecapExceptions)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $_SESSION[$cCode][$target][$iKey] = 0;

                                    }
                                }
                            }
                        }
                    }
                }
                if (sizeof($iCols) > 0) {
                    foreach ($iCols as $iKey => $iVal) {
                        if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                            if (!in_array($iKey, $itemRecapExceptions)) {
        // START OF COMPLETE REPEATED LOGIC
                                if (is_numeric($iVal)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $_SESSION[$cCode][$src][$iID]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID]["jml"] * $_SESSION[$cCode][$src][$iID][$iKey]);
                                        if (!isset($_SESSION[$cCode][$target][$iKey])) {
                                            $_SESSION[$cCode][$target][$iKey] = 0;
                                        }
                                        $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID]["jml"] * $iVal);
                                        if ($src == "rsltItems") {
//                                            cekPink2(__LINE__ . " $iKey : " . $_SESSION[$cCode][$src][$iID]["jml"] . " * $iVal");
                                        }
                                    }
                                }
        // END OF COMPLETE REPEATED LOGIC
                            }
                            else {
//                                cekHitam($iKey);
                            }
                        }
                        else {
//                            cekHitam($iKey);
                        }
                    }
                }
            }
        }
    }


    //recap multidimesnsional detail/ dua level array detail
    $recapMaker_sub = array(
        "items6" => "main",
    );

    foreach ($recapMaker_sub as $src => $target) {
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            $iCtr = 0;
            foreach ($_SESSION[$cCode][$src] as $iID => $iiCols) {// id paket 1
                // ini untuk akumulasi value/gerbang nilai items6 dengan 2 tingkat
                // jadi dibawah ini tidak direset valuenya per-tingkat atau per-id paket
                // jadi nilai total hpp di gerbang main adalah total hpp dari gerbang items6 (walaupun multi paket).
//                $iCtr++;
                //===reset dulu
//                $iCtr = 0;
                foreach ($iiCols as $ixCol => $iCols) {
                    $iCtr++;
                    if ($iCtr == 1) {// $iCtr == 1, maka reset value
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                    if (!in_array($iKey, $subItemRecapExceptions)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $_SESSION[$cCode][$target][$iKey] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                if (!in_array($iKey, $subItemRecapExceptions)) {
                                    if (is_numeric($iVal)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $_SESSION[$cCode][$src][$iID][$ixCol]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID][$ixCol]["jml"] * $_SESSION[$cCode][$src][$iID][$ixCol][$iKey]);
                                            $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID][$ixCol]["jml"] * $iVal);
                                            if ($src == "rsltItems") {
//                                                cekPink2(__LINE__ . " $iKey : " . $_SESSION[$cCode][$src][$iID]["jml"] . " * $iVal");
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
    }


    /*
         #3 [custom value builder]
    - main yang perlu dihitung
         */

    if (isset($valueGateConfig['master'])) {
        if (sizeof($valueGateConfig['master']) > 0) {
            foreach ($valueGateConfig['master'] as $key => $src) {
                if (isset($_SESSION[$cCode]['main'][$src])) {

                    $_SESSION[$cCode]['main'][$key] = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                }

            }
        }
    }

    // ---- mater dependence MAIN
    //region master (dependen)
    // cekHitam(":: master_dependent ::");
    if (isset($valueGateConfig['master_dependent'])) {
        if (sizeof($valueGateConfig['master_dependent']) > 0) {

            foreach ($valueGateConfig['master_dependent'] as $srcKey => $anuSpec) {
                if (isset($_SESSION[$cCode]['main'][$srcKey])) {
                    $srcValue = $_SESSION[$cCode]['main'][$srcKey];
                    if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                        foreach ($anuSpec[$srcValue] as $k => $src) {
                            $srcVal = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                            $_SESSION[$cCode]['main'][$k] = $srcVal;
                        }
                    }
                    else {
                        cekhijau("$srcValue TIDAK memenuhi syarat");
                    }
                }
                else {
                    cekHere("TIDAK ADA master dependent $srcKey");
                }
            }
        }
    }
    //endregion
//    matiHEre("DEBUGERMODE ON::".__LINE__);
//matiHere(__LINE__);
    // ---- mater dependence ITEMS
    //region master (dependen)
    if (isset($valueGateConfig['master_dependent_items'])) {
        if (sizeof($valueGateConfig['master_dependent_items']) > 0) {
            foreach ($valueGateConfig['master_dependent_items'] as $srcKey => $anuSpec) {
                if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                    foreach ($_SESSION[$cCode]['items'] as $ii => $iSpec) {
                        if (isset($iSpec[$srcKey])) {
                            $srcValue = $iSpec[$srcKey];
                            if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                                foreach ($anuSpec[$srcValue] as $k => $src) {
                                    $srcVal = makeValue($src, $iSpec, $iSpec, 0);
                                    $_SESSION[$cCode]['items'][$ii][$k] = $srcVal;
                                }
                            }
                        }
                    }
                }
                if (isset($_SESSION[$cCode]['items2_sum']) && sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $ii => $iSpec) {
                        if (isset($iSpec[$srcKey])) {
                            $srcValue = $iSpec[$srcKey];
                            if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                                foreach ($anuSpec[$srcValue] as $k => $src) {
                                    $srcVal = makeValue($src, $iSpec, $iSpec, 0);
                                    $_SESSION[$cCode]['items2_sum'][$ii][$k] = $srcVal;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    //endregion

    //pembulatan master untuk AR/ Ap payment cek jika nilai pembulatan vs row nilai

    if (sizeof($valueBuilderConfig) > 0) {
        foreach ($valueBuilderConfig as $key => $src) {
            $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//            cekKuning("[$srcValue_tmp] [$src]");
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($key, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                        cekPink2("$key apa adanya...");
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($key, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$key];
                    $srcValue = $mFormula($srcValue_tmp);
//                    cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }

            $_SESSION[$cCode]['main'][$key] = $srcValue;
            // cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
        }
    }
//    arrPrintWebs($_SESSION[$cCode]['main']);


// $4 kalau ada yang perlu dipopulasi

    if (sizeof($populators) > 0) {
        if (sizeof($_SESSION[$cCode][$populatorsGate])) {
            foreach ($populators as $popID => $popSpec) {
                $nilaiAsal = isset($_SESSION[$cCode]['main'][$popSpec['mainSrc']['key']]) ? $_SESSION[$cCode]['main'][$popSpec['mainSrc']['key']] : 0;
                //cekmerah("nilaiAsal: $nilaiAsal");
                $targetKey = $popSpec['itemTarget']['key'];
                $maxAmountSrc = $popSpec['itemTarget']['maxAmountSrc'];
                foreach ($_SESSION[$cCode][$populatorsGate] as $iID => $iSpec) {
                    $maxItemAmount = isset($_SESSION[$cCode][$populatorsGate][$iID][$maxAmountSrc]) ? $_SESSION[$cCode][$populatorsGate][$iID][$maxAmountSrc] : 0;
                    if ($nilaiAsal >= $maxItemAmount) {
                        $diambil = $maxItemAmount;
                        //cekmerah("ambil nilai dari maxItemAmount: $maxItemAmount");
                    }
                    else {
                        $diambil = $nilaiAsal;
                        //cekmerah("ambil nilai dari nilaiAsal: $nilaiAsal");
                    }
                    $diambil = reformatExponent($diambil);
                    if ($diambil < 0) {
//                        cekHitam("masuk disini: $diambil");
                        $diambil = 0;
                    }
                    $nilaiAsal -= $diambil;
                    $_SESSION[$cCode][$populatorsGate][$iID][$targetKey] = $diambil;
                    //cekmerah("$targetKey akan diisi dengan $diambil");
                }

            }
        }
        else {
            //cekmerah("NO ITEMS TO inject");
        }

    }
    else {
        //cekmerah("populators are not ready");
    }
//    die("DONE POPULATING values");

// #5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)
    if (sizeof($rowConfigRound) > 0) {
        foreach ($rowConfigRound as $gate => $target) {
            $gateVal = isset($_SESSION[$cCode]['main'][$gate]) ? (float)$_SESSION[$cCode]['main'][$gate] : 0;
            $roundVal = round($gateVal);
            $selisih_x = ($roundVal != 0) ? (($gateVal / $roundVal) - 1) : 0;
            $selisih = $gateVal - $roundVal;
            $_SESSION[$cCode]['main'][$target] = $roundVal;
            $_SESSION[$cCode]['main']['selisih_round'] = number_format($selisih, 10, ".", "");
        }
    }
    // cekPink($selisih);
    // // matiHEre(__LINE__." ".$selisih);
    // cekHere("cek ".$_SESSION[$cCode]['main']['selisih_round']." + ".$selisih ."=". $selisih+$_SESSION[$cCode]['main']['nilai_round']);
    if (sizeof($addBuilders) > 0) {
        if (sizeof($_SESSION[$cCode]['items'])) {
            foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
                foreach ($addBuilders as $key => $src) {
                    $_SESSION[$cCode]['items'][$iID][$key] = makeValue($src, $_SESSION[$cCode]['items'][$iID], $_SESSION[$cCode]['items'][$iID], 0);
                }

            }
        }
    }
    if (sizeof($addMainBuilders) > 0) {
        foreach ($addMainBuilders as $key => $src) {
//            arrprint( $_SESSION[$cCode]['main']);
            $_SESSION[$cCode]['main'][$key] = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
//             cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
//            cekHitam("$key = $src ---> $srcVal " . ":: line" . __LINE__);

        }
//        matiHEre("isi main builder");
    }

    if (count($additionalPostMainBuilder) > 0) {
        foreach ($additionalPostMainBuilder as $srcKey => $anuSpec) {
//            cekHitam($srcKey);
//            cekHitam($_SESSION[$cCode]['main'][$srcKey]);
            if (isset($_SESSION[$cCode]['main'][$srcKey])) {
//                matiHere(__LINE__);
                $srcValue = $_SESSION[$cCode]['main'][$srcKey];
                if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                    foreach ($anuSpec[$srcValue] as $k => $src) {
                        $srcVal = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                        $_SESSION[$cCode]['main'][$k] = $srcVal;
                        // cekPink2("$k = $src ---> $srcVal");
                    }
                }
                else {
//                    cekhijau("$srcValue TIDAK memenuhi syarat");
                }
            }
        }
//        matiHere(__LINE__);
    }

//     matiHere("hoop value builder test");
    //region build target dari shopingcartpairElement
    if (count($pairElementMakers) > 0) {
        foreach ($pairElementMakers as $keyInjector => $viSpec) {
//            matiHere(__LINE__."|| ".$keyInjector);
            $defSrc = isset($_SESSION[$cCode]["main"][$keyInjector]) ? $_SESSION[$cCode]["main"][$keyInjector] : "";
            $defvalue = isset($_SESSION[$cCode]["main"][$keyInjector]) ? $_SESSION[$cCode]["main"][$keyInjector] : "";
            $paramSrc = $viSpec["params"];
            $paramSrcQty = $viSpec["paramsQty"];
            $srcGate = $viSpec["srcGate"];
            $pairSrcModelFields = $viSpec["pairSrcFields"];
            $srcGateTarget = $viSpec["targetGate"];
            $pairModelKeyReplacer = $viSpec["pairModelKeyReplacer"];
            //----
            $targetValueReplacer = $viSpec["targetValueReplacer"];
            $targetValueReplacerMethodeKey = $viSpec["targetValueReplacer"]["srcMethodeKey"];
            //----
            if (isset($_SESSION[$cCode]["main"][$keyInjector]) && $_SESSION[$cCode]["main"][$keyInjector] == $viSpec["trigerValue"][$keyInjector]) {
                if (isset($_SESSION[$cCode][$srcGate]) && count($_SESSION[$cCode][$srcGate]) > 0) {
//                    matiHere(__LINE__);

//                    matiHere();
                    $_SESSION[$cCode][$srcGateTarget] = array();
                    foreach ($_SESSION[$cCode][$srcGate] as $cKey => $vSpec) {
                        if (count($paramSrc) > 0) {
                            //----
                            $key_replacer = NULL;
                            $key_src = $pairModelKeyReplacer["key_src"];
                            if (array_key_exists($vSpec[$key_src], $pairModelKeyReplacer["key"])) {
                                $key_replacer = $pairModelKeyReplacer["key"][$vSpec[$key_src]];
                            }
                            //---- $srcGateTarget ke items5_sum
                            foreach ($paramSrc as $key => $srcKey) {
                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key] = $vSpec[$srcKey];
                            }
                            //----untuk akumulasi qty/jml bila free produk sama dari beberapa items beli
                            foreach ($paramSrcQty as $key => $srcKey) {
                                if (!isset($_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key])) {
                                    $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key] = 0;
                                }
                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key] += $vSpec[$srcKey];
                            }


                            if (isset($viSpec["pairModel"])) {
                                $pairModel = $viSpec["pairModel"];
                                $ci->load->model("Mdls/" . $pairModel);
                                $p = new $pairModel();
                                $p->setFilters(array());
                                if (isset($viSpec["pairModelKey"])) {
                                    $arrayKey = array();
                                    foreach ($viSpec["pairModelKey"] as $key => $src_key) {
                                        if ($key_replacer !== NULL) {
                                            $src_key = $key_replacer;
                                        }
                                        if (isset($vSpec[$src_key])) {
                                            $arrayKey[$key] = $vSpec[$src_key];
                                        }
                                    }
                                    if (count($arrayKey) > 0) {
                                        $ci->db->where($arrayKey);
                                    }
                                    else {
                                        matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi error line:" . __LINE__ . " FUNCTION" . __FUNCTION__);
                                    }
                                    $temp = $p->lookUpAll()->result();
//                                    arrprint($temp);
//                                    matiHere();
                                    if (count($temp) > 0) {
                                        $targetGate2 = null;
                                        if (isset($viSpec["targetGate2"])) {
                                            $targetGate2 = $viSpec["targetGate2"]["target"];
                                        }
                                        foreach ($temp as $temp_0) {
                                            foreach ($pairSrcModelFields as $keys => $srcKeys) {
                                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$keys] = isset($temp_0->$keys) ? $temp_0->$keys : "";
                                            }

                                            if ($targetGate2 != null) {
                                                $id = $temp_0->id;
                                                $jml_serial = $temp_0->jml_serial;
                                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]]['jml_serial'] = $jml_serial;
                                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]]['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                                                $arrCat = array();
                                                $arrCode = array();
                                                if (($jml_serial * 1) == 1) {
                                                    $d_kode = $temp_0->kode;
                                                    $_SESSION[$cCode]['items2'][$temp_0->id][$d_kode] = array();
                                                    $arrCat["barcode"] = 1;
                                                    $arrCode[$temp_0->kode] = 1;
                                                }
                                                elseif (($jml_serial * 1) == 0) {
                                                    $_SESSION[$cCode]['items2'][$temp_0->id] = array();
                                                    $arrCat["barcode"] = 0;
                                                    $arrCode[$temp_0->kode] = 0;
                                                }
                                                else {
                                                    if (isset($viSpec["targetGate2"]["produkUnitPart"])) {
                                                        foreach ($viSpec["targetGate2"]["produkUnitPart"] as $cat => $catSpec) {
                                                            foreach ($catSpec as $dkey => $dval) {
                                                                if (isset($temp_0->$dval) && ($temp_0->$dval != NULL)) {
                                                                    $_SESSION[$cCode]['items2'][$temp_0->id][$temp_0->$dval] = array();
                                                                    //--------------
                                                                    if (!isset($arrCat[$cat])) {
                                                                        $arrCat[$cat] = 0;
                                                                    }
                                                                    $arrCat[$cat] += 1;
                                                                    //--------------
                                                                    if (!isset($arrCode[$temp_0->$dval])) {
                                                                        $arrCode[$temp_0->$dval] = 0;
                                                                    }
                                                                    $arrCode[$temp_0->$dval] += 1;
                                                                    //--------------
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                $keterangan = "";
                                                $static_keterangan = "";
                                                if (!empty($arrCat)) {
                                                    foreach ($arrCat as $kcat => $vcat) {
                                                        $new_vcat = $vcat * $_SESSION[$cCode][$srcGateTarget][$id]["jml"];
//                                                        $new_vcat = $vcat * $_SESSION[$cCode][$srcGate][$id]["jml"];
                                                        if ($keterangan == "") {
                                                            $keterangan = " $new_vcat $kcat";
                                                        }
                                                        else {
                                                            $keterangan .= "<br> $new_vcat $kcat";
                                                        }
                                                        if ($static_keterangan == "") {
                                                            $static_keterangan = " $vcat $kcat";
                                                        }
                                                        else {
                                                            $static_keterangan .= "<br> $vcat $kcat";
                                                        }
                                                        $new_keyy = "qty_" . $kcat;
                                                        $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$new_keyy] = $vcat;
                                                    }
                                                }
                                                if (!empty($arrCode)) {
                                                    foreach ($arrCode as $kcat => $vcat) {
                                                        $new_vcat = $vcat * $_SESSION[$cCode][$srcGateTarget][$id]["jml"];
//                                                        $new_vcat = $vcat * $_SESSION[$cCode][$srcGate][$id]["jml"];
                                                        $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$kcat] = $new_vcat;
                                                    }
                                                }
                                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]]['keterangan'] = $keterangan;
                                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]]['static_keterangan'] = $static_keterangan;
                                                //----------------------------------------

                                            }
                                        }

                                    }
                                    else {
                                        matiHere("empty data on pair model " . __FUNCTION__);
                                    }
//                                    cekHitam($ci->db->last_query());
//                                    arrPrint($arrayKey);

                                }
                                else {
                                    matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi " . __LINE__ . " FUNCTION :: " . __FUNCTION__);
                                }
//                                matiHere(__LINE__);
                            }
                        }

                        $replacerCek = isset($vSpec[$targetValueReplacerMethodeKey]) ? $vSpec[$targetValueReplacerMethodeKey] : 0;
//                        cekHitam("replacerCek: $replacerCek");
                        if (count($targetValueReplacer["srcMethodeVal"][$replacerCek]) > 0) {
                            foreach ($targetValueReplacer["srcMethodeVal"][$replacerCek] as $rkey => $rval) {
//                                cekHitam("==== [$rkey => $rval] " . $vSpec[$rval]);
                                $_SESSION[$cCode][$srcGateTarget][$vSpec[$viSpec["index_key"]]][$rkey] = isset($vSpec[$rval]) ? $vSpec[$rval] : 0;
                            }
                        }
                    }
//                    arrPrint($_SESSION[$cCode][$srcGate]);
//                    arrPrint($arrayKey);
//                    matiHere();
                }
                else {
                    unset($_SESSION[$cCode][$srcGateTarget]);
                }
            }
            else {
                unset($_SESSION[$cCode][$srcGateTarget]);
//matiHEre("belum ada gerbangnya");
            }
        }

        /* 27 desember 2024
         * ini untuk merekap ulang (value dikalikan dengan jml) pada gerbangnya sendiri -> items, items2_sum, dll
         * sesuai yang terdaftar pada variable $recapMakers
         * dan dibawa ke gerbang main
         * */
        foreach ($recapMakers as $src => $target) {
            if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
                $iCtr = 0;
                foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                    $iCtr++;
                    //===reset dulu
                    if ($iCtr == 1) {
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {

                                    if (!in_array($iKey, $itemRecapExceptions)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $_SESSION[$cCode][$target][$iKey] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
//                        cekMErah($iKey);
                            if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {

                                if (!in_array($iKey, $itemRecapExceptions)) {
                                    if (is_numeric($iVal)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $_SESSION[$cCode][$src][$iID]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID]["jml"] * $_SESSION[$cCode][$src][$iID][$iKey]);
                                            $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID]["jml"] * $iVal);

                                            if ($src == "rsltItems") {
//                                            cekPink2(__LINE__ . " $iKey : " . $_SESSION[$cCode][$src][$iID]["jml"] . " * $iVal");
                                            }
                                        }
                                    }


                                }


                                else {
//                                cekHitam($iKey);
                                }
//                        }
                            }
                            else {
//                            cekHitam($iKey);
                            }

                        }
                    }
                }
            }
        }

    }
//    matiHere(__LINE__);
    //endregion
    /*
        #6 [POPULATE]
        - populate items ke detail_values (kecuali yang termasuk exceptions)
    - populate main ke main_values (kecuali yang termasuk exceptions)
    */


    $populators = array( //==sumber2 yang dipopulasi
        "items" => "tableIn_detail_values",
        "items2" => "tableIn_detail_values2",
        "items2_sum" => "tableIn_detail_values2_sum",
        "rsltItems" => "tableIn_detail_values_rsltItems",
        "rsltItems2" => "tableIn_detail_values_rsltItems2",
    );
//    $populateTarget = "tableIn_detail_values";
    foreach ($populators as $src => $populateTarget) {
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                if (sizeof($iCols) > 0) {
                    if (!isset($_SESSION[$cCode][$populateTarget][$iID])) {
                        $_SESSION[$cCode][$populateTarget][$iID] = array();
                    }
                    foreach ($iCols as $iKey => $iVal) {
                        if (is_numeric($iVal) && !in_array($iKey, $itemPopulateExceptions)) {
                            $_SESSION[$cCode][$populateTarget][$iID][$iKey] = $iVal;
                        }
                    }
                }
            }
        }
    }

    $populators = array( //==sumber2 yang dipopulasi
        "main",

    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($_SESSION[$cCode][$populateTarget])) {
            $_SESSION[$cCode][$populateTarget] = array();
        }
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            foreach ($_SESSION[$cCode][$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $_SESSION[$cCode][$populateTarget][$key] = $val;
                }
            }
        }
    }


    //region tableIn_master
    //static
    if (isset($tableInConfig_static['master'])) {
        //static, main
        if (isset($tableInConfig_static['master']) & sizeof($tableInConfig_static['master']) > 0) {
            foreach ($tableInConfig_static['master'] as $fieldName => $staticValue) {
                $_SESSION[$cCode]['tableIn_master'][$fieldName] = $staticValue;
            }
        }
    }
    //non-static
    if (isset($tableInConfig['master'])) {
        //non-static, main
        if (sizeof($tableInConfig['master']) > 0) {
//            //echo "======================MENGISI PARAMETER UNTUK MASUK TABEL UTAMA <br>";
            foreach ($tableInConfig['master'] as $fieldName => $src) {

                if (isset($_SESSION[$cCode]['main'][$src])) {

                    $_SESSION[$cCode]['tableIn_master'][$fieldName] = $_SESSION[$cCode]['main'][$src];
                }
                else {
//                    //echo "nggak tau";
                }
//                //echo "<br>";
            }

        }

    }
    //endregion


    if (sizeof($fixedTableIn_values) > 0) {
        foreach ($fixedTableIn_values as $key => $src) {
            $_SESSION[$cCode]['tableIn_master'][$key] = isset($_SESSION[$cCode]['main'][$src]) ? $_SESSION[$cCode]['main'][$src] : "";
        }
    }

    //table in details
    $copiers = array(
        'detail' => array(
            "src" => "items",
            "target" => "tableIn_detail",
        ),
        'sub_detail' => array(
            "src" => "items2_sum",
            "target" => "tableIn_sub_detail",
        ),
        'sub_detail_items' => array(
            "src" => "rsltItems3_sub",
            "target" => "tableIn_sub_detail_items",
        ),
        'detail2' => array(
            "src" => "items2",
            "target" => "tableIn_detail2",
        ),

        'detail2_sum' => array(
            "src" => "items2_sum",
            "target" => "tableIn_detail2_sum",
        ),

        'detail_rsltItems' => array(
            "src" => "rsltItems",
            "target" => "tableIn_detail_rsltItems",
        ),
        'detail_rsltItems2' => array(
            "src" => "rsltItems2",
            "target" => "tableIn_detail_rsltItems2",
        ),
    );


    foreach ($copiers as $conf => $cSpec) {
        if (isset($tableInConfig[$conf]) && sizeof($tableInConfig[$conf]) > 0) {
            if (isset($_SESSION[$cCode][$cSpec['src']]) && sizeof($_SESSION[$cCode][$cSpec['src']]) > 0) {
                foreach ($_SESSION[$cCode][$cSpec['src']] as $iID => $iSpec) {
                    foreach ($tableInConfig[$conf] as $key => $src) {
                        if (substr($src, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($src, ".");
                            $realValue = $realCol;
//                                //echo "$key apa adanya: $realCol<br>";
                        }
                        else {
                            $realValue = isset($iSpec[$src]) ? $iSpec[$src] : "";
                        }
                        if ($cSpec['target'] == "sub_detail") {

                        }
                        $_SESSION[$cCode][$cSpec['target']][$iID][$key] = $realValue;
                    }
                }
            }
        }
    }
    // matiHEre();


    $copiers = array(
        'detail' => 'items',
        'detail2' => 'items2',
        'sub_detail' => 'items2_sum',
        'detail2_sum' => 'items2_sum',
        'detail_rsltItems' => 'rsltItems',
        'detail_rsltItems2' => 'rsltItems2',
    );
    foreach ($copiers as $conf => $iterator) {
        if (isset($tableInConfig_static[$conf]) && sizeof($tableInConfig_static[$conf]) > 0) {
            if (isset($_SESSION[$cCode][$iterator]) && sizeof($_SESSION[$cCode][$iterator]) > 0) {
                foreach ($_SESSION[$cCode][$iterator] as $iID => $iSpec) {
                    foreach ($tableInConfig_static[$conf] as $key => $val) {
                        $_SESSION[$cCode]['tableIn_' . $conf][$iID][$key] = $val;
                    }
                }
            }
        }
    }


    if (sizeof($valueInjectors) > 0) {
        ////cekmerah("ada value injector");
        foreach ($valueInjectors as $key => $val) {
            $value = isset($_SESSION[$cCode]['main'][$val]) ? $_SESSION[$cCode]['main'][$val] + 0 : 0;
            ////cekmerah("injecting $key with $value");
            echo "<script>";
            //echo "console.log('trying to inject $key with $value');";

            echo "if(top.document.getElementById('$key')){top.document.getElementById('$key').value='" . $value . "';}";
            echo "</script>";
        }
    }
    else {
        ////cekmerah("TAK ada value injector");
    }


    // arrPrintWebs($itemClonerTargets);

// arrPrint($itemClonerTargets);
//     matiHEre();
    if (sizeof($itemClonerTargets) > 0) {
        // if (sizeof($recapValueException) > 0) {
        //     foreach ($recapValueException as $valmakerKey) {
        //         if (isset($itemClonerTargets[$valmakerKey])) {
        //             unset($itemClonerTargets[$valmakerKey]);
        //         }
        //     }
        // }
        // arrPrint($itemClonerTargets);
        foreach ($itemClonerTargets as $target => $src) {

            if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                foreach ($_SESSION[$cCode][$target] as $iID => $iSpec) {
                    foreach ($itemCloners as $key) {
                        if (isset($_SESSION[$cCode][$src][$key])) {

                            $_SESSION[$cCode][$target][$iID][$key] = $_SESSION[$cCode][$src][$key];
                        }
                    }
                }
            }
        }
    }
//    arrprint($_SESSION[$cCode]["items4_sum"]);
//    matiHere();
    if (count($itemClonerTarget_sub) > 0) {
        foreach ($itemClonerTarget_sub as $target => $src) {
            if (isset($_SESSION[$cCode][$target]) && sizeof($_SESSION[$cCode][$target]) > 0) {
                foreach ($_SESSION[$cCode][$target] as $iID => $iSpec) {
                    foreach ($iSpec as $iiID => $iiSpec) {
//                        cekHitam($iiID);
                        foreach ($itemCloners as $key) {
                            if (isset($_SESSION[$cCode][$src][$key])) {
//                                cekHitam($key);
                                $_SESSION[$cCode][$target][$iID][$iiID][$key] = $_SESSION[$cCode][$src][$key];
                            }
                            else {
//                                cekMerah(__LINE__.":: ".$key);
                            }
                        }
                    }

                }
            }
        }
    }
//    arrPrintWebs($_SESSION[$cCode]['items6_sum']);
//    matiHEre(__LINE__);
// arrprint($pairMakers);
    if (sizeof($pairMakers) > 0) {
        foreach ($pairMakers as $prKey => $prSpec) {
//arrPrint($prSpec);
// matiHEre();
            $ci->load->helper("Pairs/" . $prSpec['helperName']);
            $gateParam = isset($prSpec['gate']) ? $prSpec['gate'] : "items";
            $result = $prSpec['functionName']($tr, $intoStep, $prSpec['params'], $gateParam);
//            cekMerah($ci->db->last_query());
            $_SESSION[$cCode]['pairs'][$prKey] = $result;
// arrPrint( $prSpec['helperName']);
// matiHEre();
        }
        // arrPrint($pairMakers);
// mati_disini($cCode);
        if (sizeof($pairInjectors) > 0) {
            foreach ($pairInjectors as $keyInjector => $viSpec) {
                if (array_key_exists($keyInjector, $pairMakers)) {
                    foreach ($viSpec as $gateName => $vgSpec) {
                        if (isset($_SESSION[$cCode][$gateName])) {
                            foreach ($_SESSION[$cCode][$gateName] as $cKey => $vSpec) {
                                if (array_key_exists($cKey, $_SESSION[$cCode]['pairs'][$keyInjector])) {
                                    if (is_array($_SESSION[$cCode]['pairs'][$keyInjector][$cKey])) {
                                        $urut = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey]['urut'];
                                        $val = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey]['value'];
                                        $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn'] . "_" . $urut] = $val;
                                    }
                                    else {
                                        $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn']] = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey];
                                    }
                                }
                                else {
//                                    ceklIme("tidak ada pair " . $keyInjector);
                                    $_SESSION[$cCode][$gateName][$cKey][$vgSpec['targetColumn']] = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
        // arrPrint($_SESSION[$cCode]['items2_sum']);
        // mati_disini();
    }

    if (sizeof($pairMakersProject) > 0) {
        foreach ($pairMakersProject as $prKey => $prSpec) {
            $ci->load->helper("Pairs/" . $prSpec['helperName']);
            $gateParam = isset($prSpec['gate']) ? $prSpec['gate'] : "items";
            $result = $prSpec['functionName']($tr, $intoStep, $prSpec['params'], $gateParam);
            $_SESSION[$cCode]['pairs'][$prKey] = $result;
        }

        if (sizeof($pairInjectorsProject) > 0) {
            foreach ($pairInjectorsProject as $keyInjector => $viSpec) {
                if (array_key_exists($keyInjector, $pairMakersProject)) {
                    foreach ($viSpec as $gateName => $vgSpec) {
                        if (isset($_SESSION[$cCode][$gateName])) {
                            foreach ($_SESSION[$cCode][$gateName] as $cKey => $dSpec) {
                                foreach ($dSpec as $pKey => $vSpec) {
                                    if (array_key_exists($pKey, $_SESSION[$cCode]['pairs'][$keyInjector][$cKey])) {
                                        if (is_array($_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey])) {
                                            if (isset($_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey]['urut'])) {
                                                $urut = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey]['urut'];
                                                $val = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey]['value'];
                                                $_SESSION[$cCode][$gateName][$cKey][$pKey][$vgSpec['targetColumn'] . "_" . $urut] = $val;
                                            }
                                            else {
                                                foreach ($_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey] as $key_inject => $val_inject) {
                                                    $_SESSION[$cCode][$gateName][$cKey][$pKey][$key_inject] = $val_inject;
                                                }
                                            }
                                        }
                                        else {
                                            $_SESSION[$cCode][$gateName][$cKey][$pKey][$vgSpec['targetColumn']] = $_SESSION[$cCode]['pairs'][$keyInjector][$cKey][$pKey];
                                        }
                                    }
                                    else {
                                        $_SESSION[$cCode][$gateName][$cKey][$pKey][$vgSpec['targetColumn']] = 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


//     arrPrint($pairMakersProject);
//     matiHere("pairMakerProject<br>" . __LINE__);
    if (($fromStep = 0 && $intoStep == 0) || ($fromStep = 1 && $intoStep == 1)) {//==ini pembuatan baru
//            ////cekMerah("build values saat buat baru");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][1]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][1]['target'],
        );
        if (isset($configUiJenis['steps'][2])) {
            $addFieldValues['next_step_code'] = $configUiJenis['steps'][2]['target'];
            $addFieldValues['next_group_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addFieldValues['step_number'] = 1;
            $addFieldValues['step_current'] = 1;

            $addSubFieldValues['next_substep_code'] = $configUiJenis['steps'][2]['target'];
            $addSubFieldValues['next_subgroup_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addSubFieldValues['sub_step_number'] = 1;
            $addSubFieldValues['sub_step_current'] = 1;

        }
        else {
            $addFieldValues['next_step_code'] = "";
            $addFieldValues['next_group_code'] = "";
            $addFieldValues['step_number'] = $intoStep;
            $addFieldValues['step_current'] = 0;

            $addSubFieldValues['next_substep_code'] = "";
            $addSubFieldValues['next_subgroup_code'] = "";
            $addSubFieldValues['sub_step_number'] = $intoStep;
            $addSubFieldValues['sub_step_current'] = 0;
        }
    }
    else {//==ini manipulasi saat edit
//            ////cekMerah("build values saat EDIT");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][$intoStep]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][$intoStep]['target'],
        );
        $addFieldValues['next_step_code'] = "";
        $addFieldValues['next_group_code'] = "";
        $addFieldValues['step_number'] = $intoStep;
        $addFieldValues['step_current'] = 0;

        $addSubFieldValues['next_substep_code'] = "";
        $addSubFieldValues['next_subgroup_code'] = "";
        $addSubFieldValues['sub_step_number'] = $intoStep;
        $addSubFieldValues['sub_step_current'] = 0;
    }


    foreach ($addFieldValues as $fName => $value) {
        $_SESSION[$cCode]['main'][$fName] = $value;
        $_SESSION[$cCode]['tableIn_master'][$fName] = $value;
    }


    if (isset($addSubFieldValues) && sizeof($addSubFieldValues) > 0) {

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0 && isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['items'][$iID][$fName] = $value;
                }
            }
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1,
                    //                    "valid_qty"       => $_SESSION[$cCode]['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($_SESSION[$cCode]['items'][$iID][$src])) {
                            $_SESSION[$cCode]['tableIn_detail'][$iID][$target] = $_SESSION[$cCode]['items'][$iID][$src];
                        }
                    }
                }
            }

        }

        if (isset($_SESSION[$cCode]['items2_sum']) && sizeof($_SESSION[$cCode]['items2_sum']) > 0 && isset($_SESSION[$cCode]['tableIn_detail_subdetail']) && sizeof($_SESSION[$cCode]['tableIn_detail_subdetail']) > 0) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['items2_sum'][$iID][$fName] = $value;
                }
            }
            foreach ($_SESSION[$cCode]['tableIn_detail_subdetail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($_SESSION[$cCode]['main']['step_number']) ? $_SESSION[$cCode]['main']['step_number'] : 1,
                    //                    "valid_qty"       => $_SESSION[$cCode]['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $_SESSION[$cCode]['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($_SESSION[$cCode]['items2_sum'][$iID][$src])) {
                            $_SESSION[$cCode]['tableIn_detail_subdetail'][$iID][$target] = $_SESSION[$cCode]['items2_sum'][$iID][$src];
                        }
                    }
                }
            }

        }
    }


//    if(isset($_GET['confirm']) && $_GET['confirm']=="1"){
//
//    }else{
//
//        echo "<script>\n";
//        echo "if(top.document.getElementById('ck')){top.document.getElementById('ck').checked=false;}\n";
//        echo "if(top.document.getElementById('btnProcess')){top.document.getElementById('btnProcess').disabled=true;}\n";
//        echo "</script>\n";
//    }


    // khusus setoran......................................................................
    $ci->load->model("MdlTransaksi");

    $additionalSource = isset($configCoreJenis['additionalSource']) ? $configCoreJenis['additionalSource'] : false;
    $additionalItemSource = isset($configCoreJenis['additionalItemSource']) ? $configCoreJenis['additionalItemSource'] : array();
    $additionalItemResult = isset($configCoreJenis['additionalItemResult']) ? $configCoreJenis['additionalItemResult'] : array();
    $additionalItemSourceKey = isset($configCoreJenis['additionalItemSourceKey']) ? $configCoreJenis['additionalItemSourceKey'] : array();
    $additionalPembulatan = isset($configCoreJenis['valueInjectorBulat']) ? $configCoreJenis['valueInjectorBulat'] : array();
    $additionalPembulatanPajak = isset($configCoreJenis['injectorPajak']) ? $configCoreJenis['injectorPajak'] : array();
    $pairPembulatanPajak = isset($configCoreJenis['pairPajak']) ? $configCoreJenis['pairPajak'] : array();
    $additionalPembulatanPajakReseller = isset($configCoreJenis['injectorPajakReseller']) ? $configCoreJenis['injectorPajakReseller'] : array();
    $pairPembulatanPajakReseller = isset($configCoreJenis['pairPajakReseller']) ? $configCoreJenis['pairPajakReseller'] : array();
    if ($additionalSource == true) {
        if (sizeof($additionalItemSource)) {
            //cekUngu("cetak ITEMS AWAL...");
            //arrPrint($_SESSION[$cCode]['items']);
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {

                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                // $trr->addFilter("param='main'");
                $trr->addFilter("transaksi_id='$id'");
                $tmpR = $trr->lookupDataRegistries()->result();
                // arrPrint($tmpR);
                $main = blobDecode($tmpR[0]->main);

                //cekUngu(":: MAIN ID $id");
//                arrPrint($main);
//                mati_disini();
                //cekUngu(":: ITEMS ID $id");
                //arrPrint($iSpec);

                foreach ($additionalItemSource as $key => $val) {
//                    if (!isset($_SESSION[$cCode]['items'][$id][$key])) {

                    $new_key = (sizeof($additionalItemResult) > 0 && (isset($additionalItemResult[$key]))) ? $additionalItemResult[$key] : $key;
                    if (!isset($_SESSION[$cCode]['items'][$id][$new_key])) {

                        $_SESSION[$cCode]['items'][$id][$new_key] = 0;
                    }

                    if (sizeof($additionalItemSourceKey) > 0) {
                        $persenValue = ($_SESSION[$cCode]['items'][$id][$additionalItemSourceKey['top']] / $main[$additionalItemSourceKey['bottom']]) * 100;
                    }
                    else {
                        $persenValue = 0;
                    }
                    $key_result = makeValue($val, $main, $main, 0);
                    $_SESSION[$cCode]['items'][$id]['persenValue'] = $persenValue;
                    $_SESSION[$cCode]['items'][$id][$new_key] = ($persenValue / 100) * $key_result;


//                    }
                }
            }

//                $persenValue = ($_SESSION[$cCode]['items'][$id]['nilai_bayar']/$_SESSION[$cCode]['items'][$id]['harga_nett2'])*100;
//                $_SESSION[$cCode]['items'][$id]['persenValue'] = $persenValue;
//                foreach ($additionalItemSource as $key => $val){
//
//                    $_SESSION[$cCode]['items'][$id]['source_'.$key] = ($_SESSION[$cCode]['items'][$id][$key]*$persenValue)/100;
//                }
        }
//        mati_disini();
    }

    if (sizeof($alwaysUpdaters) > 0) {
        foreach ($alwaysUpdaters as $key => $src) {
            $_SESSION[$cCode]['main'][$key] = isset($ci->session->login[$src]) ? $ci->session->login[$src] : "";
        }
    }


    if (sizeof($additionalPembulatan) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatan['source'];
        $varBulat = makeDppBulat($_SESSION[$cCode]['main'][$source]);
        foreach ($additionalPembulatan['injectTo'] as $key => $fields) {
            $srcValue_tmp = $varBulat[$key];
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($fields, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink2("$fields :: asli -> $srcValue_tmp :: bulat -> $srcValue");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($fields, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$fields];
                    $srcValue = $mFormula($srcValue_tmp);
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }
            $_SESSION[$cCode]['main'][$fields] = $srcValue;
        }
    }
    if (sizeof($additionalPembulatanPajak) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajak['source'];
        $varBulat = pembulatan_pajak($_SESSION[$cCode]['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajak as $key => $fields) {
            // cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $_SESSION[$cCode]['main'][$key] = $varBulat[$fields];
        }
    }
    if (sizeof($additionalPembulatanPajakReseller) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajakReseller['source'];
        $varBulat = pembulatan_pajak($_SESSION[$cCode]['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajakReseller as $key => $fields) {
//            cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $_SESSION[$cCode]['main'][$key] = $varBulat[$fields];
        }
    }

    //region valueReplaceCalculate
    $valueReplaceCalculate = isset($configCoreJenis['valueReplaceCalculate']) ? $configCoreJenis['valueReplaceCalculate'] : array();
    if (sizeof($valueReplaceCalculate) > 0) {
        foreach ($valueReplaceCalculate as $gate) {
            $curentVal = $_SESSION[$cCode]['main'][$gate];
            if ($curentVal > 0) {

            }
            else {
                $_SESSION[$cCode]['main'][$gate] = 0;
                $_SESSION[$cCode]['tableIn_master_values'][$gate] = 0;
            }
//            cekHitam($gate);
        }
    }


    //endregion

    //------------------------------------------------------------------
    $recapItemBuilder = isset($configCoreJenis['recapItemBuilder']) ? $configCoreJenis['recapItemBuilder'] : array();
//    arrprint($recapItemBuilder);
//    matiHere();
    if (sizeof($recapItemBuilder) > 0) {
        $gateNameSource = $recapItemBuilder['gateNameSource'];
        $gateNameTarget = $recapItemBuilder['gateNameTarget'];
        $key = $recapItemBuilder['key'];
        $vals = $recapItemBuilder['val'];
        //------ hapus/reset items2_sum
        if (isset($_SESSION[$cCode]['items4_sum'])) {
            $_SESSION[$cCode]['items4_sum'] = null;
            unset($_SESSION[$cCode]['items4_sum']);
        }

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $ii => $iSpec) {
                //------ build ulang items2_sum
                if (!isset($_SESSION[$cCode]['items4_sum'][$iSpec[$key]])) {
                    $iSpec['id'] = $iSpec[$key];
                    $_SESSION[$cCode]['items4_sum'][$iSpec[$key]] = $iSpec;

                    foreach ($vals as $val) {
                        $_SESSION[$cCode]['items4_sum'][$iSpec[$key]][$val] = 0;
                    }
                }
                //------ build ulang items2_sum
                foreach ($vals as $val) {
                    $_SESSION[$cCode]['items4_sum'][$iSpec[$key]][$val] += $iSpec[$val];
                }
            }
        }
    }

//    arrPrintWebs($_SESSION[$cCode]['main']);
//    mati_disini("value builder helper");

    // rekalkulasi TableInMasterValues di akhir value builder....
    $populators = array( //==sumber2 yang dipopulasi
        "main",
    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($_SESSION[$cCode][$populateTarget])) {
            $_SESSION[$cCode][$populateTarget] = array();
        }
        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
            foreach ($_SESSION[$cCode][$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $_SESSION[$cCode][$populateTarget][$key] = $val;
                }
            }
        }
    }
    // matiHEre();
    //------------------------------------------------------------------
    switch ($tr) {
        case "583":
        case "585":
        case "5833":
        case "5855":
        case "5823":
        case "9823":
        case "5822":
        case "9822":
        case "19822":
            $gatePisah = pisahBarangJasa($_SESSION[$cCode]);
            $_SESSION[$cCode]["items4"] = $gatePisah["items4"];
            $_SESSION[$cCode]["items4_sum"] = $gatePisah["items4_sum"];
            $_SESSION[$cCode]["items9_sum"] = $gatePisah["items9_sum"];
            $_SESSION[$cCode]["items10_sum"] = $gatePisah["items10_sum"];
//arrPrintHijau($gatePisah);
            break;

        case "6698":
        case "466":
        case "1466":
            $gatePisah = pisahProdukSupplies($_SESSION[$cCode]);
            $_SESSION[$cCode]["items9_sum"] = $gatePisah["items9_sum"];
            $_SESSION[$cCode]["items10_sum"] = $gatePisah["items10_sum"];
            $recapMakersPisah = array( //==sumber detail ke target main
                "items9_sum" => "main",
                "items10_sum" => "main",
            );
            foreach ($recapMakersPisah as $src => $target) {
                if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
                    $iCtr = 0;
                    foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                        $iCtr++;
                        //===reset dulu
                        if ($iCtr == 1) {
                            if (sizeof($iCols) > 0) {
                                foreach ($iCols as $iKey => $iVal) {
                                    if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                        if (!in_array($iKey, $itemRecapExceptions)) {
                                            if (substr($iKey, 0, 4) != "sub_") {
                                                $_SESSION[$cCode][$target][$iKey] = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                    if (!in_array($iKey, $itemRecapExceptions)) {
                                        if (is_numeric($iVal)) {
                                            if (substr($iKey, 0, 4) != "sub_") {
                                                $_SESSION[$cCode][$src][$iID]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID]["jml"] * $_SESSION[$cCode][$src][$iID][$iKey]);
                                                $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID]["jml"] * $iVal);
                                            }
                                        }
                                    }
                                    else {

                                    }
                                }
                                else {

                                }
                            }
                        }
                    }
                }
            }

            break;

        case "9911":
            switch ($jenisTr_references) {
                case "466":
                    $gatePisah = pisahProdukSupplies($_SESSION[$cCode]);
                    $_SESSION[$cCode]["items9_sum"] = $gatePisah["items9_sum"];
                    $_SESSION[$cCode]["items10_sum"] = $gatePisah["items10_sum"];
                    $recapMakersPisah = array( //==sumber detail ke target main
                        "items9_sum" => "main",
                        "items10_sum" => "main",
                    );
                    foreach ($recapMakersPisah as $src => $target) {
                        if (isset($_SESSION[$cCode][$src]) && sizeof($_SESSION[$cCode][$src]) > 0) {
                            $iCtr = 0;
                            foreach ($_SESSION[$cCode][$src] as $iID => $iCols) {
                                $iCtr++;
                                //===reset dulu
                                if ($iCtr == 1) {
                                    if (sizeof($iCols) > 0) {
                                        foreach ($iCols as $iKey => $iVal) {
                                            if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                                if (!in_array($iKey, $itemRecapExceptions)) {
                                                    if (substr($iKey, 0, 4) != "sub_") {
                                                        $_SESSION[$cCode][$target][$iKey] = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if (sizeof($iCols) > 0) {
                                    foreach ($iCols as $iKey => $iVal) {
                                        if (!isset($_SESSION[$cCode]['main_elements'][$iKey])) {
                                            if (!in_array($iKey, $itemRecapExceptions)) {
                                                if (is_numeric($iVal)) {
                                                    if (substr($iKey, 0, 4) != "sub_") {
                                                        $_SESSION[$cCode][$src][$iID]["sub_" . $iKey] = ($_SESSION[$cCode][$src][$iID]["jml"] * $_SESSION[$cCode][$src][$iID][$iKey]);
                                                        $_SESSION[$cCode][$target][$iKey] += ($_SESSION[$cCode][$src][$iID]["jml"] * $iVal);
                                                    }
                                                }
                                            }
                                            else {

                                            }
                                        }
                                        else {

                                        }
                                    }
                                }
                            }
                        }
                    }

                    break;
            }
            break;

        case "749":// penerimaan kas ar
            // sementara dibuat ulang disini karena hilang gerbangnya...
            if (isset($_SESSION[$cCode]["items6_sum"]) && (sizeof($_SESSION[$cCode]["items6_sum"]) > 0)) {
                $kolom = array(
                    "ppn_final",
                    "dpp_nppn_final",
                    "ppn_sudah_faktur",
                    "dpp_pengganti",
                );
                // reset dulu ke 0
                foreach ($kolom as $kol) {
                    $_SESSION[$cCode]["main"][$kol] = 0;
                }
                foreach ($_SESSION[$cCode]["items6_sum"] as $spec) {
                    foreach ($kolom as $kol) {
                        if (!isset($_SESSION[$cCode]["main"][$kol])) {
                            $_SESSION[$cCode]["main"][$kol] = 0;
                        }
                        $_SESSION[$cCode]["main"][$kol] += isset($spec[$kol]) ? $spec[$kol] : 0;
                    }
                }
            }

            if(isset($_SESSION[$cCode]["main"]["lebih_bayar"]) && ($_SESSION[$cCode]["main"]["lebih_bayar"] < 0)){
                $_SESSION[$cCode]["main"]["lebih_bayar"] = 0;
            }
            if(isset($_SESSION[$cCode]["main"]["deposit_konsumen"]) && ($_SESSION[$cCode]["main"]["deposit_konsumen"] < 0)){
                $_SESSION[$cCode]["main"]["deposit_konsumen"] = 0;
            }
            if(isset($_SESSION[$cCode]["main"]["deposits_konsumen_marketplace"]) && ($_SESSION[$cCode]["main"]["deposits_konsumen_marketplace"] < 0)){
                $_SESSION[$cCode]["main"]["deposits_konsumen_marketplace"] = 0;
            }

            break;

        case "462":
            $cek_value = $_SESSION[$cCode]["main"]["nilai_entry"];
            if (($cek_value > -1) && ($cek_value < 1) && ($cek_value != 0)) {
//                cekMerah(__LINE__ . "[cek_value: $cek_value] || " . $_SESSION[$cCode]["main"]["nilai_entry"]);
                $_SESSION[$cCode]["main"]["nilai_entry"] = 0;
                $_SESSION[$cCode]["main"]["nilai_diskon_pembulatan"] = $cek_value;
            }
            break;
        case "483":
        case "487":
        case "489":
        case "4821":
            $cek_value = $_SESSION[$cCode]["main"]["nilai_entry"];
//            cekMerah("[cek_value: $cek_value] || " . $_SESSION[$cCode]["main"]["nilai_entry"]);
            if (($cek_value > -1) && ($cek_value < 1) && ($cek_value != 0)) {
                $_SESSION[$cCode]["main"]["nilai_entry"] = 0;
                $_SESSION[$cCode]["main"]["nilai_diskon_pembulatan"] = $cek_value;
            }
            break;

    }

    //------------------------------------------------------------------
    $rebuilderCore = isset($configCoreJenis['rebuilderCore']) ? $configCoreJenis['rebuilderCore'] : array();
    $rebuilderCoreDetail = isset($configCoreJenis['rebuilderCoreDetail']) ? $configCoreJenis['rebuilderCoreDetail'] : array();
    $rebuilderCoreKey = isset($configCoreJenis['rebuilderCoreKey']) ? $configCoreJenis['rebuilderCoreKey'] : NULL;
    if (sizeof($rebuilderCore) > 0) {
        if ($rebuilderCoreKey != NULL) {
            $rebuilderCoreKeyMain = isset($_SESSION[$cCode]['main'][$rebuilderCoreKey]) ? $_SESSION[$cCode]['main'][$rebuilderCoreKey] : 0;
            if (isset($rebuilderCore[$rebuilderCoreKeyMain]) && (sizeof($rebuilderCore[$rebuilderCoreKeyMain]) > 0)) {
                foreach ($rebuilderCore[$rebuilderCoreKeyMain] as $key => $val) {
                    $_SESSION[$cCode]['main'][$key] = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                }
                if (sizeof($rebuilderCoreDetail) > 0) {
                    if (isset($_SESSION[$cCode]['items']) && (sizeof($_SESSION[$cCode]['items']) > 0)) {
                        foreach ($rebuilderCoreDetail[$rebuilderCoreKeyMain] as $key => $val) {
                            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                                $_SESSION[$cCode]['items'][$id][$key] = makeValue($val, $_SESSION[$cCode]['items'][$id], $_SESSION[$cCode]['items'][$id], 0);
                            }
                        }
                    }
                }
            }
        }
    }


    $reload_value_builder = isset($configCoreJenis['reloadValueBuilder']) ? $configCoreJenis['reloadValueBuilder'] : false;
    if ($reload_value_builder == true) {
        // value builder di runnning ulang karena yang berhubungan
        // dengan pajak, nilainya dari pair pajak...
        if (sizeof($valueBuilderConfig) > 0) {
            foreach ($valueBuilderConfig as $key => $src) {
                $srcValue_tmp = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);

                if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                    if (array_key_exists($key, $extFormula['master'])) {
                        $mFormula = $extFormula['master'][$key];
                        $srcValue = $mFormula($srcValue_tmp);
//                    cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
                    }
                    else {
                        $srcValue = $srcValue_tmp;
                    }
                }
                else {
                    $srcValue = $srcValue_tmp;
                }

                $_SESSION[$cCode]['main'][$key] = $srcValue;
                cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
            }
        }
        //    arrPrintWebs($_SESSION[$cCode]['main']);


        // ---- mater dependence MAIN, build ulang...
        //region master (dependen)
        // cekHitam(":: master_dependent ::");
        if (isset($valueGateConfig['master_dependent'])) {
            if (sizeof($valueGateConfig['master_dependent']) > 0) {

                foreach ($valueGateConfig['master_dependent'] as $srcKey => $anuSpec) {
                    if (isset($_SESSION[$cCode]['main'][$srcKey])) {
                        $srcValue = $_SESSION[$cCode]['main'][$srcKey];
                        if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                            foreach ($anuSpec[$srcValue] as $k => $src) {
                                $srcVal = makeValue($src, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                $_SESSION[$cCode]['main'][$k] = $srcVal;
                            }
                        }
                        else {
                        }
                    }
                }
            }
        }
        //endregion
    }

    // region bersih-bersih gerbang dengan key kosong
    $arrGateBersihMain = array(
        "main",
        "tableIn_master_values",
        "tableIn_master",
    );
    foreach ($arrGateBersihMain as $srcGate) {
        if (isset($_SESSION[$cCode][$srcGate]) && (sizeof($_SESSION[$cCode][$srcGate]) > 0)) {
            foreach ($_SESSION[$cCode][$srcGate] as $key => $val) {
                if ($key == NULL) {
                    unset($_SESSION[$cCode][$srcGate][$key]);
                }
            }
        }
    }

    $arrGateBersihDetail = array(
        "items",
        "items2_sum",
        "items3_sum",
        "items4_sum",
        "items5_sum",
        "items6_sum",
        "items7_sum",
        "items8_sum",
        "items9_sum",
        "items10_sum",
    );
    foreach ($arrGateBersihDetail as $srcGate) {
        if (isset($_SESSION[$cCode][$srcGate]) && (sizeof($_SESSION[$cCode][$srcGate]) > 0)) {
            foreach ($_SESSION[$cCode][$srcGate] as $ii => $spec) {
                foreach ($spec as $key => $val) {
                    if ($key == NULL) {
                        unset($_SESSION[$cCode][$srcGate][$ii][$key]);
                    }
                }
            }
        }
    }

    // endregion bersih-bersih gerbang dengan key kosong

}


//versi MODUL NO SESSION, dikirim dengan $sessionData -------------------------
function resetValues_he_value_builder_ns($tr, $configCoreJenis, $sessionData)
{
    $valueGateConfig = isset($configCoreJenis['valueGates']) ? $configCoreJenis['valueGates'] : array();
    $valueBuilderConfig = isset($configCoreJenis['valueBuilders']) ? $configCoreJenis['valueBuilders'] : array();
    $cCode = "_TR_" . $tr;

    $sessionData['tableIn_detail'] = array();
    $sessionData['tableIn_detail2_sum'] = array();
    $sessionData['main_add_fields'] = array();
    $sessionData['main_add_values'] = array();
    $sessionData['tableIn_master_values'] = array();
    $sessionData['tableIn_detail_values'] = array();
    $sessionData['tableIn_detail_values2_sum'] = array();

    return $sessionData;
}

function fillValues_he_value_builder_ns_old($tr, $fromStep = 0, $intoStep = 0, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor = 0, $sessionData)
{
    if ($sessionData == NULL) {
        $msg = "Session data diperlukan untuk melanjutkan transaksi. Sesi anda belum terdaftar, silahkan hubungi admin. errcode" . __FUNCTION__;
        mati_disini($msg);
    }

    $cCode = cCodeBuilderMisc($tr);

    /*
     *
     *
#1 [custom value gates]
- items yang perlu dihitung

#2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main


#3 [custom value builder]
- main yang perlu dihitung

$4 kalau ada yang perlu dipopulasi
#5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)

#6 [POPULATE SAMPING]
- populate items ke detail_values (kecuali yang termasuk exceptions)
- populate main ke main_values (kecuali yang termasuk exceptions)

#7 [OTHERS]
- valueInjectors
- pairMakers
- cloners		> dari out_master ke out_detail

#8 [NGOPI]
- tableIn_master	> dari main
- tableIn_detail	> dari items
- tableIn_detail2	> dari items2
- tableIn_detail_rsltItems	> dari rsltItems
     */


    $valueGateConfig = isset($configCoreJenis['valueGates']) ? $configCoreJenis['valueGates'] : array();
    $tableInConfig = isset($configCoreJenis['tableIn']) ? $configCoreJenis['tableIn'] : array();
    $tableInConfig_static = isset($configCoreJenis['tableIn_static']) ? $configCoreJenis['tableIn_static'] : array();
    //value builder
    $valueBuilderConfig = isset($configCoreJenis['valueBuilders']) ? $configCoreJenis['valueBuilders'] : array();
    $valueBuilderConfig2 = isset($configCoreJenis['valueBuilders2']) ? $configCoreJenis['valueBuilders2'] : array();
    $valueBuilderConfig2_sum = isset($configCoreJenis['valueBuilders2_sum']) ? $configCoreJenis['valueBuilders2_sum'] : array();
    $valueBuilderConfig_rsltItems = isset($configCoreJenis['valueBuilders_rsltItems']) ? $configCoreJenis['valueBuilders_rsltItems'] : array();
    $valueBuilderConfig_rsltItems2 = isset($configCoreJenis['valueBuilders_rsltItems2']) ? $configCoreJenis['valueBuilders_rsltItems2'] : array();

    //value spreaderadditionalBuilders
    $valueSpreaderConfig = isset($configCoreJenis['valueSpreaders']) ? $configCoreJenis['valueSpreaders'] : array();

    $itemNumLabels = isset($configUiJenis['shoppingCartNumFields']) ? $configUiJenis['shoppingCartNumFields'] : array();
    $detailValueFields = isset($configCoreJenis['tableIn']['detailValues']) ? $configCoreJenis['tableIn']['detailValues'] : array();
    $availPayments = isset($configUiJenis['availPayments']) ? $configUiJenis['availPayments'] : array();
    $tagihanSrc = isset($configUiJenis['tagihanSrc']) ? $configUiJenis['tagihanSrc'] : "sisa";

    $pairMakers = isset($configUiJenis['pairMakers'][$intoStep]) ? $configUiJenis['pairMakers'][$intoStep] : array();
    $pairElementMakers = isset($configUiJenis['pairElementGateBuilder'][$intoStep]) ? $configUiJenis['pairElementGateBuilder'][$intoStep] : array();
    $pairInjectors = isset($configUiJenis['pairInjectors'][$intoStep]) ? $configUiJenis['pairInjectors'][$intoStep] : array();
    $valueInjectors = isset($configUiJenis['mainValueInjectors']) ? $configUiJenis['mainValueInjectors'] : array();


    ////
    $itemCloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();
    $itemClonerTargets = config_item('heGlobalPopulators') != null ? config_item('heGlobalPopulators') : array();
    $itemClonerTarget_sub = config_item('GlobalPopulator_sub') != null ? config_item('GlobalPopulator_sub') : array();
    $itemRecapExceptions = config_item('transaksi_itemRecapExceptions') != null ? config_item('transaksi_itemRecapExceptions') : array();
    $subItemRecapExceptions = config_item('transaksi_subitemRecapExceptions') != null ? config_item('transaksi_subitemRecapExceptions') : array();
    $itemPopulateExceptions = config_item('transaksi_itemPopulateExceptions') != null ? config_item('transaksi_itemPopulateExceptions') : array();
    $masterPopulateExceptions = config_item('transaksi_masterPopulateExceptions') != null ? config_item('transaksi_masterPopulateExceptions') : array();
//    $cloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();

    $fixedItem_subValues = config_item('transaksi_fixedItem_subValues') != null ? config_item('transaksi_fixedItem_subValues') : array();
    $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();
    $fixedTableIn_values = config_item('transaksi_fixedTableIn_values') != null ? config_item('transaksi_fixedTableIn_values') : array();

    $populatorsGate = isset($configCoreJenis['populatorsGate']) ? $configCoreJenis['populatorsGate'] : "items";
    $populators = isset($configCoreJenis['populators']) ? $configCoreJenis['populators'] : array();
    $addBuilders = isset($configCoreJenis['additionalBuilders']) ? $configCoreJenis['additionalBuilders'] : array();
    $addMainBuilders = isset($configCoreJenis['additionalMainBuilders']) ? $configCoreJenis['additionalMainBuilders'] : array();
    $extFormulaConfig = isset($configCoreJenis['extFormula']) ? $configCoreJenis['extFormula'] : array();

    $additionalPostMainBuilder = isset($configCoreJenis['additionalPostMainBuilder']) ? $configCoreJenis['additionalPostMainBuilder'] : array();
    //sessionToGateAlwaysUpdaters
    $alwaysUpdaters = null != config_item("sessionToGateAlwaysUpdaters") ? config_item("sessionToGateAlwaysUpdaters") : array();

    $productCostInjector = isset($configUiJenis['pairCostInjectors'][$intoStep]) ? $configUiJenis['pairCostInjectors'][$intoStep] : array();
    $gateExchangeConfig = isset($configUiJenis['gateExchange']) ? $configUiJenis['gateExchange'] : array();

    $rowConfigRound = isset($configCoreJenis['additionalRound']) ? $configCoreJenis['additionalRound'] : array();
    $recapValueException = isset($configCoreJenis['recapValueException']) ? $configCoreJenis['recapValueException'] : array();
    //-----------------------------
    $valueSubDetail = isset($configCoreJenis['valueSubDetail']) ? $configCoreJenis['valueSubDetail'] : false;
    $valueSubDetailRecap = isset($configCoreJenis['valueSubDetailRecap']) ? $configCoreJenis['valueSubDetailRecap'] : array();

    //transaksi_fixedTableIn_subValues

    $ci =& get_instance();

    // cekHitam("session MAIN before master dependent");

    if ($valueSubDetail == true) {
        if (isset($valueGateConfig['detail2'])) {
            if (sizeof($valueGateConfig['detail2']) > 0) {
                if (isset($sessionData['items2']) && sizeof($sessionData['items2']) > 0) {
                    foreach ($sessionData['items2'] as $id => $subSpec) {
                        foreach ($subSpec as $ii => $sSpec) {
                            foreach ($valueGateConfig['detail2'] as $key => $src) {
                                $srcValue_tmp = makeValue($src, $sessionData['items2'][$id][$ii], $sessionData['items2'][$id][$ii], 0);
                                $srcValue = $srcValue_tmp;
                                $sessionData['items2'][$id][$ii][$key] = $srcValue;
                                $sessionData['items2'][$id][$ii]["sub_" . $key] = $sSpec['jml'] * $srcValue;
                            }
                        }
                    }
                    // direcap ke items
                    $target_recap = "items";
                    foreach ($sessionData['items2'] as $id => $subSpec) {
                        $noCtr = 0;
                        foreach ($subSpec as $ii => $sSpec) {
                            $noCtr++;
                            if ($noCtr == 1) {
                                foreach ($valueSubDetailRecap as $irecap) {
                                    if (substr($irecap, 0, 4) != "sub_") {
                                        $sessionData[$target_recap][$id][$irecap] = 0;
                                    }
                                }
                            }

                            foreach ($sSpec as $skey => $sval) {
                                if ((in_array($skey, $valueSubDetailRecap)) && (is_numeric($sval))) {
                                    if (substr($skey, 0, 4) != "sub_") {
                                        $sessionData['items2'][$id][$ii]["sub_" . $skey] = ($sessionData['items2'][$id][$ii]["jml"] * $sessionData['items2'][$id][$ii][$skey]);
                                        $sessionData[$target_recap][$id][$skey] += ($sessionData['items2'][$id][$ii]["jml"] * $sval);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     #1 [custom value gates]
- items yang perlu dihitung
     */

    // membalikkan key dengan value.....
    // value gerbang menjadi key dan ceil/floor menjadi value, seperti ini ->
    // ppn => floor
    // dpp => ceil
    foreach ($extFormulaConfig as $gateName => $extSpec) {
        foreach ($extSpec as $formula => $fSpec) {
            foreach ($fSpec as $gate) {
                $extFormula[$gateName][$gate] = $formula;
            }
        }
    }

    if (isset($valueGateConfig['detail'])) {
        if (sizeof($valueGateConfig['detail']) > 0) {
            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['items'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items'][$id], $sessionData['items'][$id], 0);
                        if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                            if (array_key_exists($key, $extFormula['detail'])) {
                                $mFormula = $extFormula['detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['rsltItems'])) {
        if (sizeof($valueGateConfig['rsltItems']) > 0) {
            if (isset($sessionData['rsltItems']) && sizeof($sessionData['rsltItems']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['rsltItems'] as $id => $iSpec) {
                    $iCtr++;
                    if ($iSpec['jml'] > 0) {
                        foreach ($valueGateConfig['rsltItems'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $sessionData['rsltItems'][$id], $sessionData['rsltItems'][$id], 0);
                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                                if (array_key_exists($key, $extFormula['detail'])) {
                                    $mFormula = $extFormula['detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $sessionData['rsltItems'][$id][$key] = $srcValue;
                        }
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['detail2_sum'])) {
        if (sizeof($valueGateConfig['detail2_sum']) > 0) {
            if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['items2_sum'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail2_sum'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items2_sum'][$id], $sessionData['items2_sum'][$id], 0);
                        if (isset($extFormula['detail2_sum']) && sizeof($extFormula['detail2_sum']) > 0) {
                            if (array_key_exists($key, $extFormula['detail2_sum'])) {
                                $mFormula = $extFormula['detail2_sum'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items2_sum'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
//cekHitam("cetak items2_sum");
//arrPrintHijau($sessionData['items2_sum']);

    if (isset($valueGateConfig['sub_detail'])) {
        if (sizeof($valueGateConfig['sub_detail']) > 0) {
            if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['items2_sum'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items2_sum'][$id], $sessionData['items2_sum'][$id], 0);

                        if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail'])) {
                                $mFormula = $extFormula['sub_detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items2_sum'][$id][$key] = $srcValue;
                    }

                    //                    }
                }
            }
            if (isset($sessionData['items2']) && sizeof($sessionData['items2']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['items2'] as $id => $ixSpec) {
                    $iCtr++;

                    foreach ($ixSpec as $ii => $iSpec) {
                        foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $iSpec, $iSpec, 0);
                            if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                                if (array_key_exists($key, $extFormula['sub_detail'])) {
                                    $mFormula = $extFormula['sub_detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $sessionData['items2'][$id][$ii][$key] = $srcValue;
                        }
                    }


                    //                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['sub_detail_items'])) {
        if (sizeof($valueGateConfig['sub_detail_items']) > 0) {
            if (isset($sessionData['rsltItems3_sub']) && sizeof($sessionData['rsltItems3_sub']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['rsltItems3_sub'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['rsltItems3_sub'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['rsltItems3_sub'][$id], $sessionData['rsltItems3_sub'][$id], 0);

                        if (isset($extFormula['sub_detail_items']) && sizeof($extFormula['sub_detail_items']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail_items'])) {
                                $mFormula = $extFormula['sub_detail_items'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['rsltItems3_sub'][$id][$key] = $srcValue;
                    }
                    //                    }
                }
            }
        }
    }
    //-----
//    if (isset($valueGateConfig['rsltItems_revert'])) {
//        if (sizeof($valueGateConfig['rsltItems_revert']) > 0) {
//            if (isset($sessionData['rsltItems_revert']) && sizeof($sessionData['rsltItems_revert']) > 0) {
//                $iCtr = 0;
//                foreach ($sessionData['rsltItems_revert'] as $id => $iSpec) {
//                    $iCtr++;
//                    if ($iSpec['jml'] > 0) {
//                        foreach ($valueGateConfig['rsltItems_revert'] as $key => $src) {
//                            $srcValue_tmp = makeValue($src, $sessionData['rsltItems_revert'][$id], $sessionData['rsltItems_revert'][$id], 0);
//                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
//                                if (array_key_exists($key, $extFormula['detail'])) {
//                                    $mFormula = $extFormula['detail'][$key];
//                                    $srcValue = $mFormula($srcValue_tmp);
//                                }
//                                else {
//                                    $srcValue = $srcValue_tmp;
//                                }
//                            }
//                            else {
//                                $srcValue = $srcValue_tmp;
//                            }
//                            $sessionData['rsltItems_revert'][$id][$key] = $srcValue;
//                        }
//                    }
//                }
//            }
//        }
//    }


    if (sizeof($fixedItem_subValues) > 0) {
        if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
            foreach ($sessionData['items'] as $id => $iSpec) {
                foreach ($fixedItem_subValues as $key => $src) {
                    $sessionData['items'][$id][$key] = makeValue($src, $sessionData['items'][$id], $sessionData['items'][$id], "");
                    //cekbiru("filling $key on $id with " . $sessionData['items'][$id][$key]);
                }
            }
        }

    }

    /*
     #2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main
     */

    if (sizeof($productCostInjector) > 0) {
        $source = $productCostInjector['source'];
        $target = $productCostInjector['target'];
        $jenis = $productCostInjector['jenis'];
        if (isset($sessionData[$source])) {
            foreach ($sessionData[$source] as $pID => $kSpec) {
                if (isset($kSpec[$jenis])) {
                    foreach ($kSpec[$jenis] as $h => $jSpec) {
                        if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                            foreach ($sessionData[$target] as $i => $rslt) {
                                if ($rslt['id'] == $pID) {
                                    foreach ($productCostInjector['kolom'] as $k => $v) {
                                        $jSpecName = str_replace(' ', '_', $jSpec['nama']);

//                                        $sessionData[$target][$i][$k."_".$jSpecName] = $jSpec[$v];
                                        $sessionData[$target][$i][$k . "_" . $h] = $jSpec[$v];

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================
//    cekHitam(":::: bagian exchange :::: || FILE: " . __FILE__);
    if (sizeof($gateExchangeConfig) > 0) {
        foreach ($gateExchangeConfig as $gateExchange) {
            if (isset($gateExchange['enabled']) && ($gateExchange['enabled'] == true)) {
                $source = $gateExchange['source'];
                $postfix = $gateExchange['postfix'];
                $blacklist = $gateExchange['blacklist'];
                $exchange = isset($sessionData['main'][$source]) ? $sessionData['main'][$source] : NULL; // main exchange
                $mainGate = array(
                    "main",
                );
                $detailGate = array(
                    "items",
                    "items2_sum",
                    "items3_sum",
                    "items5_sum",
                );

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    foreach ($mainGate as $gateName) {
                        if (isset($sessionData[$gateName]) && (sizeof($sessionData[$gateName]) > 0)) {
                            if ($exchange != NULL) {
                                foreach ($sessionData[$gateName] as $mainKey => $mainVal) {
                                    if (is_numeric($mainVal)) {
                                        $newPostfix = $postfix . "__";
                                        $subNewPostfix = "sub_" . $postfix . "__";
                                        $mainKey_ex = explode("__", $mainKey);
                                        // direset dulu
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $sessionData[$gateName][$postfix . "__" . $mainKey] = 0;
//                                            cekKuning("$gateName $postfix $mainKey direset menjadi 0");
                                        }
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $sessionData[$gateName][$postfix . "__" . $mainKey] = $mainVal * $exchange;
                                            cekPink("$gateName $postfix $mainKey diisi menjadi $mainVal * $exchange " . $mainVal * $exchange);
                                        }
                                    }
                                }
                            }
                            else {
//                                cekMerah(":: $gateName [$source][$exchange] :: tidak mengalikan gerbang baru ::");
                            }
                        }
                    }
                }

                foreach ($detailGate as $gateName) {
                    if (isset($sessionData[$gateName]) && (sizeof($sessionData[$gateName]) > 0)) {
                        foreach ($sessionData[$gateName] as $iID => $detailSpec) {
                            if (sizeof($detailSpec) > 0) {
                                $exchange = isset($detailSpec[$source]) ? $detailSpec[$source] : NULL;
                                if ($exchange != NULL) {
//                                    cekHijau(":: $gateName [$exchange] :: mengalikan gerbang baru ::");
                                    foreach ($detailSpec as $detailKey => $detailVal) {
                                        if (is_numeric($detailVal)) {
                                            $newPostfix = $postfix . "__";
                                            $subNewPostfix = "sub_" . $postfix . "__";
                                            $detailPostFix = substr($detailKey, 0, strlen($subNewPostfix));

                                            $detailKey_ex = explode("__", $detailKey);


//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $sessionData[$gateName][$iID][$postfix . "__" . $detailKey] = 0;

//                                                cekKuning("$gateName $postfix $detailKey direset menjadi 0");
                                            }

//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $sessionData[$gateName][$iID][$postfix . "__" . $detailKey] = $detailVal * $exchange;

                                                cekPink("$gateName $postfix $detailKey diisi menjadi $detailVal * $exchange " . $detailVal * $exchange);
                                            }
                                        }
                                    }
                                }
                                else {
//                                    cekMerah(":: $gateName [$exchange] :: tidak mengalikan gerbang baru ::");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================


    $recapMakers = array( //==sumber detail ke target main
        "itemsSrc1" => "main",
        "itemsSrc1_sum" => "main",
        "itemsTarget1" => "main",
        "itemSrcBendahara_sum" => "main",
        "items" => "main",
        "itemSrc_sum" => "main",
        "items2" => "main",
        "items2_sum" => "main",
        "items3_sum" => "main",
        "items4_sum" => "main",
        "items5_sum" => "main",
//        "items6_sum" => "main",// me-summary komposisi dari paket
        "rsltItems" => "main",
        "rsltItems2" => "main",
        "rsltItems3" => "main",
        //------
        "rsltItems_revert" => "main",
//        "items6"=>"main",
    );
    //untuk reset jika tidak diperbolehkan direkap ke main, baca dari configCore:: recapValueException
    if (sizeof($recapValueException) > 0) {
        foreach ($recapValueException as $valmakerKey) {
            if (isset($recapMakers[$valmakerKey])) {
                unset($recapMakers[$valmakerKey]);
            }
        }
    }

    foreach ($recapMakers as $src => $target) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {

            $iCtr = 0;
            foreach ($sessionData[$src] as $iID => $iCols) {
                $iCtr++;


                //===reset dulu
                if ($iCtr == 1) {
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($sessionData['main_elements'][$iKey])) {

                                if (!in_array($iKey, $itemRecapExceptions)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $sessionData[$target][$iKey] = 0;
                                    }
                                }
                            }
                        }
                    }
                }


                if (sizeof($iCols) > 0) {
                    foreach ($iCols as $iKey => $iVal) {
//                        cekMErah($iKey);
                        if (!isset($sessionData['main_elements'][$iKey])) {

                            if (!in_array($iKey, $itemRecapExceptions)) {
                                if (is_numeric($iVal)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $sessionData[$src][$iID]["sub_" . $iKey] = ($sessionData[$src][$iID]["jml"] * $sessionData[$src][$iID][$iKey]);
                                        $sessionData[$target][$iKey] += ($sessionData[$src][$iID]["jml"] * $iVal);

                                        if ($src == "rsltItems") {
                                            cekPink2(__LINE__ . " $iKey : " . $sessionData[$src][$iID]["jml"] . " * $iVal");
                                        }
                                    }
                                }


                            }


                            else {
//                                cekHitam($iKey);
                            }
//                        }
                        }
                        else {
//                            cekHitam($iKey);
                        }

                    }
                }
            }
        }
    }
    //recap multidimesnsional detail/ dua level array detail
    $recapMaker_sub = array(
        "items6" => "main",
    );

    foreach ($recapMaker_sub as $src => $target) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            $iCtr = 0;
            foreach ($sessionData[$src] as $iID => $iiCols) {
                $iCtr++;
                //===reset dulu
                $iCtr = 0;
                foreach ($iiCols as $ixCol => $iCols) {
                    $iCtr++;
                    if ($iCtr == 1) {
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($sessionData['main_elements'][$iKey])) {

                                    if (!in_array($iKey, $subItemRecapExceptions)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $sessionData[$target][$iKey] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($sessionData['main_elements'][$iKey])) {

//                        if(is_numeric($iVal)){
                                if (!in_array($iKey, $subItemRecapExceptions)) {
                                    if (is_numeric($iVal)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $sessionData[$src][$iID][$ixCol]["sub_" . $iKey] = ($sessionData[$src][$iID][$ixCol]["jml"] * $sessionData[$src][$iID][$ixCol][$iKey]);
                                            $sessionData[$target][$iKey] += ($sessionData[$src][$iID][$ixCol]["jml"] * $iVal);

                                            if ($src == "rsltItems") {
                                                cekPink2(__LINE__ . " $iKey : " . $sessionData[$src][$iID]["jml"] . " * $iVal");
                                            }
                                        }


                                    }


                                }
//                        }
                            }

                        }
                    }
                }
            }
        }
    }


    /*
         #3 [custom value builder]
    - main yang perlu dihitung
         */

    if (isset($valueGateConfig['master'])) {
        if (sizeof($valueGateConfig['master']) > 0) {
            foreach ($valueGateConfig['master'] as $key => $src) {
                if (isset($sessionData['main'][$src])) {

                    $sessionData['main'][$key] = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                }

            }
        }
    }

    // ---- mater dependence MAIN
    //region master (dependen)
    // cekHitam(":: master_dependent ::");
    if (isset($valueGateConfig['master_dependent'])) {
        if (sizeof($valueGateConfig['master_dependent']) > 0) {
            foreach ($valueGateConfig['master_dependent'] as $srcKey => $anuSpec) {
                if (isset($sessionData['main'][$srcKey])) {
                    $srcValue = $sessionData['main'][$srcKey];
                    if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                        foreach ($anuSpec[$srcValue] as $k => $src) {
                            $srcVal = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                            $sessionData['main'][$k] = $srcVal;
                            cekPink2("$k = $src ---> $srcVal " . __LINE__);
                        }
                    }
                    else {
                        //cekhijau("$srcValue TIDAK memenuhi syarat");
                    }
                }
            }
        }
    }
    //endregion

//matiHere(__LINE__);
    // ---- mater dependence ITEMS
    //region master (dependen)
    if (isset($valueGateConfig['master_dependent_items'])) {
        if (sizeof($valueGateConfig['master_dependent_items']) > 0) {
            foreach ($valueGateConfig['master_dependent_items'] as $srcKey => $anuSpec) {
                if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                    foreach ($sessionData['items'] as $ii => $iSpec) {
                        if (isset($iSpec[$srcKey])) {
                            $srcValue = $iSpec[$srcKey];
                            if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                                foreach ($anuSpec[$srcValue] as $k => $src) {
                                    $srcVal = makeValue($src, $iSpec, $iSpec, 0);
                                    $sessionData['items'][$ii][$k] = $srcVal;
                                }
                            }
                            else {
                                //cekhijau("$srcValue TIDAK memenuhi syarat");
                            }
                        }

                    }
                }
            }
        }
    }
    //endregion

    //pembulatan master untuk AR/ Ap payment cek jika nilai pembulatan vs row nilai

//arrPrintKuning($valueBuilderConfig);
    if (sizeof($valueBuilderConfig) > 0) {
        foreach ($valueBuilderConfig as $key => $src) {
            $srcValue_tmp = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($key, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                        cekPink2("$key apa adanya...");
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($key, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$key];
                    $srcValue = $mFormula($srcValue_tmp);
                    cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }

            $sessionData['main'][$key] = $srcValue;
            cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
        }
    }
//    arrPrintWebs($sessionData['main']);


// $4 kalau ada yang perlu dipopulasi

    if (sizeof($populators) > 0) {
        if (sizeof($sessionData[$populatorsGate])) {
            foreach ($populators as $popID => $popSpec) {
                $nilaiAsal = $sessionData['main'][$popSpec['mainSrc']['key']];
                //cekmerah("nilaiAsal: $nilaiAsal");
                $targetKey = $popSpec['itemTarget']['key'];
                $maxAmountSrc = $popSpec['itemTarget']['maxAmountSrc'];
                foreach ($sessionData[$populatorsGate] as $iID => $iSpec) {
                    $maxItemAmount = $sessionData[$populatorsGate][$iID][$maxAmountSrc];
                    if ($nilaiAsal >= $maxItemAmount) {
                        $diambil = $maxItemAmount;
                        //cekmerah("ambil nilai dari maxItemAmount: $maxItemAmount");
                    }
                    else {
                        $diambil = $nilaiAsal;
                        //cekmerah("ambil nilai dari nilaiAsal: $nilaiAsal");
                    }
                    $nilaiAsal -= $diambil;
                    $sessionData[$populatorsGate][$iID][$targetKey] = $diambil;
                    //cekmerah("$targetKey akan diisi dengan $diambil");
                }

            }
        }
        else {
            //cekmerah("NO ITEMS TO inject");
        }

    }
    else {
        //cekmerah("populators are not ready");
    }
//    die("DONE POPULATING values");

// #5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)
    if (sizeof($rowConfigRound) > 0) {
//        arrPrint($rowConfigRound);
        foreach ($rowConfigRound as $gate => $target) {
            // $src = $sessionData['main'][$gate];
            // $trg = round($sessionData['main'][$gate]);
            // $val=$trg > $src ? $trg - $src : $src - $trg;
            // cekMerah($src."<---src trg -->".$trg." selisih ".$val);
            // cekHitam($src-$trg);
            $selisih_x = ($sessionData['main'][$gate] / round($sessionData['main'][$gate])) - 1;
            // $selisih = $sessionData['main'][$gate] - round($sessionData['main'][$gate])  > 0 ? $sessionData['main'][$gate] - round($sessionData['main'][$gate]):$sessionData['main'][$gate] - round($sessionData['main'][$gate])*-1;
            $selisih = $sessionData['main'][$gate] - round($sessionData['main'][$gate]);
            $sessionData['main'][$target] = round($sessionData['main'][$gate]);
            $sessionData['main']['selisih_round'] = number_format($selisih, 10, ".", "");
//            cekHitam($selisih);
            // cekHijau(PHP_FLOAT_MIN ($selisih));
        }

    }
    // cekPink($selisih);
    // // matiHEre(__LINE__." ".$selisih);
    // cekHere("cek ".$sessionData['main']['selisih_round']." + ".$selisih ."=". $selisih+$sessionData['main']['nilai_round']);
    if (sizeof($addBuilders) > 0) {
        if (sizeof($sessionData['items'])) {
            foreach ($sessionData['items'] as $iID => $iSpec) {
                foreach ($addBuilders as $key => $src) {
                    $sessionData['items'][$iID][$key] = makeValue($src, $sessionData['items'][$iID], $sessionData['items'][$iID], 0);
                }

            }
        }
    }
    if (sizeof($addMainBuilders) > 0) {
        foreach ($addMainBuilders as $key => $src) {
//            arrprint( $sessionData['main']);
            $sessionData['main'][$key] = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
//             cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
//            cekHitam("$key = $src ---> $srcVal " . ":: line" . __LINE__);

        }
//        matiHEre("isi main builder");
    }

    if (count($additionalPostMainBuilder) > 0) {
        foreach ($additionalPostMainBuilder as $srcKey => $anuSpec) {
//            cekHitam($srcKey);
//            cekHitam($sessionData['main'][$srcKey]);
            if (isset($sessionData['main'][$srcKey])) {
//                matiHere(__LINE__);
                $srcValue = $sessionData['main'][$srcKey];
                if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                    foreach ($anuSpec[$srcValue] as $k => $src) {
                        $srcVal = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                        $sessionData['main'][$k] = $srcVal;
                        cekPink2("$k = $src ---> $srcVal");
                    }
                }
                else {
                    cekhijau("$srcValue TIDAK memenuhi syarat");
                }
            }
        }
//        matiHere(__LINE__);
    }

//     matiHere("hoop value builder test");
    //region build target dari shopingcartpairElement
    if (count($pairElementMakers) > 0) {
        foreach ($pairElementMakers as $keyInjector => $viSpec) {
//            matiHere(__LINE__."|| ".$keyInjector);
            $defSrc = isset($sessionData["main"][$keyInjector]) ? $sessionData["main"][$keyInjector] : "";
            $defvalue = isset($sessionData["main"][$keyInjector]) ? $sessionData["main"][$keyInjector] : "";
            $paramSrc = $viSpec["params"];
            $srcGate = $viSpec["srcGate"];
            $pairSrcModelFields = $viSpec["pairSrcFields"];
            $srcGateTarget = $viSpec["targetGate"];
            if (isset($sessionData["main"][$keyInjector]) && $sessionData["main"][$keyInjector] == $viSpec["trigerValue"][$keyInjector]) {
                if (isset($sessionData[$srcGate]) && count($sessionData[$srcGate]) > 0) {
//                    matiHere(__LINE__);

//                    matiHere();
                    foreach ($sessionData[$srcGate] as $cKey => $vSpec) {
                        if (count($paramSrc) > 0) {

                            foreach ($paramSrc as $key => $srcKey) {
                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key] = $vSpec[$srcKey];
                            }
                            if (isset($viSpec["pairModel"])) {
                                $pairModel = $viSpec["pairModel"];
                                $ci->load->model("Mdls/" . $pairModel);
                                $p = new $pairModel();
                                $p->setFilters(array());
                                if (isset($viSpec["pairModelKey"])) {
                                    $arrayKey = array();
                                    foreach ($viSpec["pairModelKey"] as $key => $src_key) {
                                        if (isset($vSpec[$src_key])) {
                                            $arrayKey[$key] = $vSpec[$src_key];
                                        }
                                    }
                                    if (count($arrayKey) > 0) {
                                        $ci->db->where($arrayKey);
                                    }
                                    else {
                                        matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi error line:" . __LINE__ . " FUNCTION" . __FUNCTION__);
                                    }
                                    $temp = $p->lookUpAll()->result();
//                                    arrprint($temp);
//                                    matiHere();
                                    if (count($temp) > 0) {
                                        $targetGate2 = null;
                                        if (isset($viSpec["targetGate2"])) {
                                            $targetGate2 = $viSpec["targetGate2"]["target"];
                                        }
                                        foreach ($temp as $temp_0) {
                                            foreach ($pairSrcModelFields as $keys => $srcKeys) {
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$keys] = isset($temp_0->$keys) ? $temp_0->$keys : "";
                                            }

                                            if ($targetGate2 != null) {
                                                $jml_serial = $temp_0->jml_serial;
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['jml_serial'] = $jml_serial;
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                                                if ($jml_serial * 1 == 1) {
                                                    $d_kode = $temp_0->kode;
                                                    $sessionData['items2'][$temp_0->id][$d_kode] = array();
                                                }
                                                if (isset($viSpec["targetGate2"]["produkUnitPart"])) {
                                                    $arrCat = array();
                                                    $arrCode = array();
                                                    foreach ($viSpec["targetGate2"]["produkUnitPart"] as $cat => $catSpec) {
                                                        foreach ($catSpec as $dkey => $dval) {
                                                            if (isset($temp_0->$dval) && ($temp_0->$dval != NULL)) {
                                                                $sessionData['items2'][$temp_0->id][$temp_0->$dval] = array();
                                                                //--------------
                                                                if (!isset($arrCat[$cat])) {
                                                                    $arrCat[$cat] = 0;
                                                                }
                                                                $arrCat[$cat] += 1;
                                                                //--------------
                                                                if (!isset($arrCode[$temp_0->$dval])) {
                                                                    $arrCode[$temp_0->$dval] = 0;
                                                                }
                                                                $arrCode[$temp_0->$dval] += 1;
                                                                //--------------
                                                            }
                                                        }
                                                    }
                                                    $keterangan = "";
                                                    $static_keterangan = "";
                                                    if (!empty($arrCat)) {
                                                        foreach ($arrCat as $kcat => $vcat) {
                                                            $new_vcat = $vcat * $sessionData['items'][$id]["jml"];
                                                            if ($keterangan == "") {
                                                                $keterangan = " $new_vcat $kcat";
                                                            }
                                                            else {
                                                                $keterangan .= "<br> $new_vcat $kcat";
                                                            }
                                                            if ($static_keterangan == "") {
                                                                $static_keterangan = " $vcat $kcat";
                                                            }
                                                            else {
                                                                $static_keterangan .= "<br> $vcat $kcat";
                                                            }
                                                            $new_keyy = "qty_" . $kcat;
                                                            $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$new_keyy] = $vcat;
                                                        }
                                                    }
//                                                    arrprint($arrCode);
//                                                    matiHere();
                                                    if (!empty($arrCode)) {
                                                        foreach ($arrCode as $kcat => $vcat) {
                                                            $new_vcat = $vcat * $sessionData['items'][$id]["jml"];
                                                            $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$kcat] = $new_vcat;
                                                        }
                                                    }
                                                    $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['keterangan'] = $keterangan;
                                                    $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['static_keterangan'] = $static_keterangan;
                                                    //----------------------------------------

                                                }

                                            }
                                        }

                                    }
                                    else {
                                        matiHere("empty data on pair model " . __FUNCTION__);
                                    }
//                                    cekHitam($ci->db->last_query());
//                                    arrPrint($arrayKey);

                                }
                                else {
                                    matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi " . __LINE__ . " FUNCTION :: " . __FUNCTION__);
                                }
//                                matiHere(__LINE__);
                            }

                        }
                    }
//                    arrPrint($sessionData[$srcGate]);
//                    arrPrint($arrayKey);
//                    matiHere();
                }
                else {
                    unset($sessionData[$srcGateTarget]);
                }
            }
            else {
                unset($sessionData[$srcGateTarget]);
//matiHEre("belum ada gerbangnya");
            }
        }
    }
//    matiHere(__LINE__);
    //endregion
    /*
        #6 [POPULATE]
        - populate items ke detail_values (kecuali yang termasuk exceptions)
    - populate main ke main_values (kecuali yang termasuk exceptions)
    */


    $populators = array( //==sumber2 yang dipopulasi
        "items" => "tableIn_detail_values",
        "items2" => "tableIn_detail_values2",
        "items2_sum" => "tableIn_detail_values2_sum",
        "rsltItems" => "tableIn_detail_values_rsltItems",
        "rsltItems2" => "tableIn_detail_values_rsltItems2",
    );
//    $populateTarget = "tableIn_detail_values";
    foreach ($populators as $src => $populateTarget) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $iID => $iCols) {
                if (sizeof($iCols) > 0) {
                    if (!isset($sessionData[$populateTarget][$iID])) {
                        $sessionData[$populateTarget][$iID] = array();
                    }
                    foreach ($iCols as $iKey => $iVal) {
                        if (is_numeric($iVal) && !in_array($iKey, $itemPopulateExceptions)) {
                            $sessionData[$populateTarget][$iID][$iKey] = $iVal;
                        }
                    }
                }
            }
        }
    }

    $populators = array( //==sumber2 yang dipopulasi
        "main",

    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($sessionData[$populateTarget])) {
            $sessionData[$populateTarget] = array();
        }
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $sessionData[$populateTarget][$key] = $val;
                }
            }
        }
    }


    //region tableIn_master
    //static
    if (isset($tableInConfig_static['master'])) {
        //static, main
        if (isset($tableInConfig_static['master']) & sizeof($tableInConfig_static['master']) > 0) {
            foreach ($tableInConfig_static['master'] as $fieldName => $staticValue) {
                $sessionData['tableIn_master'][$fieldName] = $staticValue;
            }
        }
    }
    //non-static
    if (isset($tableInConfig['master'])) {
        //non-static, main
        if (sizeof($tableInConfig['master']) > 0) {
//            //echo "======================MENGISI PARAMETER UNTUK MASUK TABEL UTAMA <br>";
            foreach ($tableInConfig['master'] as $fieldName => $src) {

                if (isset($sessionData['main'][$src])) {

                    $sessionData['tableIn_master'][$fieldName] = $sessionData['main'][$src];
                }
                else {
//                    //echo "nggak tau";
                }
//                //echo "<br>";
            }

        }

    }
    //endregion


    if (sizeof($fixedTableIn_values) > 0) {
        foreach ($fixedTableIn_values as $key => $src) {
            $sessionData['tableIn_master'][$key] = isset($sessionData['main'][$src]) ? $sessionData['main'][$src] : "";
        }
    }


//    //arrprint($tableInConfig);die();
    //table in details
    $copiers = array(
        'detail' => array(
            "src" => "items",
            "target" => "tableIn_detail",
        ),
        'sub_detail' => array(
            "src" => "items2_sum",
            "target" => "tableIn_sub_detail",
        ),
        'sub_detail_items' => array(
            "src" => "rsltItems3_sub",
            "target" => "tableIn_sub_detail_items",
        ),
        'detail2' => array(
            "src" => "items2",
            "target" => "tableIn_detail2",
        ),

        'detail2_sum' => array(
            "src" => "items2_sum",
            "target" => "tableIn_detail2_sum",
        ),

        'detail_rsltItems' => array(
            "src" => "rsltItems",
            "target" => "tableIn_detail_rsltItems",
        ),
        'detail_rsltItems2' => array(
            "src" => "rsltItems2",
            "target" => "tableIn_detail_rsltItems2",
        ),
    );


    foreach ($copiers as $conf => $cSpec) {
        if (isset($tableInConfig[$conf]) && sizeof($tableInConfig[$conf]) > 0) {
            if (isset($sessionData[$cSpec['src']]) && sizeof($sessionData[$cSpec['src']]) > 0) {
                foreach ($sessionData[$cSpec['src']] as $iID => $iSpec) {
                    foreach ($tableInConfig[$conf] as $key => $src) {
                        if (substr($src, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($src, ".");
                            $realValue = $realCol;
//                                //echo "$key apa adanya: $realCol<br>";
                        }
                        else {
                            $realValue = isset($iSpec[$src]) ? $iSpec[$src] : "";
                        }
                        if ($cSpec['target'] == "sub_detail") {

                        }
                        $sessionData[$cSpec['target']][$iID][$key] = $realValue;
                    }
                }
            }
        }
    }
    // matiHEre();


    $copiers = array(
        'detail' => 'items',
        'detail2' => 'items2',
        'sub_detail' => 'items2_sum',
        'detail2_sum' => 'items2_sum',
        'detail_rsltItems' => 'rsltItems',
        'detail_rsltItems2' => 'rsltItems2',
    );
    foreach ($copiers as $conf => $iterator) {
        if (isset($tableInConfig_static[$conf]) && sizeof($tableInConfig_static[$conf]) > 0) {
            if (isset($sessionData[$iterator]) && sizeof($sessionData[$iterator]) > 0) {
                foreach ($sessionData[$iterator] as $iID => $iSpec) {
                    foreach ($tableInConfig_static[$conf] as $key => $val) {
                        $sessionData['tableIn_' . $conf][$iID][$key] = $val;
                    }
                }
            }
        }
    }


    if (sizeof($valueInjectors) > 0) {
        ////cekmerah("ada value injector");
        foreach ($valueInjectors as $key => $val) {
            $value = isset($sessionData['main'][$val]) ? $sessionData['main'][$val] + 0 : 0;
            ////cekmerah("injecting $key with $value");
            echo "<script>";
            //echo "console.log('trying to inject $key with $value');";

            echo "if(top.document.getElementById('$key')){top.document.getElementById('$key').value='" . $value . "';}";
            echo "</script>";
        }
    }
    else {
        ////cekmerah("TAK ada value injector");
    }


    // arrPrintWebs($itemClonerTargets);

// arrPrint($itemClonerTargets);
//     matiHEre();
    if (sizeof($itemClonerTargets) > 0) {
        // if (sizeof($recapValueException) > 0) {
        //     foreach ($recapValueException as $valmakerKey) {
        //         if (isset($itemClonerTargets[$valmakerKey])) {
        //             unset($itemClonerTargets[$valmakerKey]);
        //         }
        //     }
        // }
        // arrPrint($itemClonerTargets);
        foreach ($itemClonerTargets as $target => $src) {

            if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                foreach ($sessionData[$target] as $iID => $iSpec) {
                    foreach ($itemCloners as $key) {
                        if (isset($sessionData[$src][$key])) {

                            $sessionData[$target][$iID][$key] = $sessionData[$src][$key];
                        }
                    }
                }
            }
        }
    }
//    arrprint($sessionData["items4_sum"]);
//    matiHere();
    if (count($itemClonerTarget_sub) > 0) {
        foreach ($itemClonerTarget_sub as $target => $src) {
            if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                foreach ($sessionData[$target] as $iID => $iSpec) {
                    foreach ($iSpec as $iiID => $iiSpec) {
//                        cekHitam($iiID);
                        foreach ($itemCloners as $key) {
                            if (isset($sessionData[$src][$key])) {
//                                cekHitam($key);
                                $sessionData[$target][$iID][$iiID][$key] = $sessionData[$src][$key];
                            }
                            else {
//                                cekMerah(__LINE__.":: ".$key);
                            }
                        }
                    }

                }
            }
        }
    }
//    arrPrintWebs($sessionData['items6_sum']);
//    matiHEre(__LINE__);
// arrprint($pairMakers);
    if (sizeof($pairMakers) > 0) {
        foreach ($pairMakers as $prKey => $prSpec) {
//arrPrint($prSpec);
// matiHEre();
            $ci->load->helper("Pairs/" . $prSpec['helperName']);
            $gateParam = isset($prSpec['gate']) ? $prSpec['gate'] : "items";
            $result = $prSpec['functionName']($tr, $intoStep, $prSpec['params'], $gateParam);
//            cekMerah($ci->db->last_query());
            $sessionData['pairs'][$prKey] = $result;
// arrPrint( $prSpec['helperName']);
// matiHEre();
        }
        // arrPrint($pairMakers);
// mati_disini($cCode);
        if (sizeof($pairInjectors) > 0) {
            foreach ($pairInjectors as $keyInjector => $viSpec) {
                if (array_key_exists($keyInjector, $pairMakers)) {
                    foreach ($viSpec as $gateName => $vgSpec) {
                        if (isset($sessionData[$gateName])) {
                            foreach ($sessionData[$gateName] as $cKey => $vSpec) {
                                if (array_key_exists($cKey, $sessionData['pairs'][$keyInjector])) {
                                    if (is_array($sessionData['pairs'][$keyInjector][$cKey])) {
                                        $urut = $sessionData['pairs'][$keyInjector][$cKey]['urut'];
                                        $val = $sessionData['pairs'][$keyInjector][$cKey]['value'];
                                        $sessionData[$gateName][$cKey][$vgSpec['targetColumn'] . "_" . $urut] = $val;
                                    }
                                    else {
                                        $sessionData[$gateName][$cKey][$vgSpec['targetColumn']] = $sessionData['pairs'][$keyInjector][$cKey];
                                    }
                                }
                                else {
                                    ceklIme("tidak ada pair " . $keyInjector);
                                    $sessionData[$gateName][$cKey][$vgSpec['targetColumn']] = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
        // arrPrint($sessionData['items2_sum']);
        // mati_disini();
    }

    // arrPrint($pairMakers);
    // matiHEre();
    if (($fromStep = 0 && $intoStep == 0) || ($fromStep = 1 && $intoStep == 1)) {//==ini pembuatan baru
//            ////cekMerah("build values saat buat baru");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][1]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][1]['target'],
        );
        if (isset($configUiJenis['steps'][2])) {
            $addFieldValues['next_step_code'] = $configUiJenis['steps'][2]['target'];
            $addFieldValues['next_group_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addFieldValues['step_number'] = 1;
            $addFieldValues['step_current'] = 1;

            $addSubFieldValues['next_substep_code'] = $configUiJenis['steps'][2]['target'];
            $addSubFieldValues['next_subgroup_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addSubFieldValues['sub_step_number'] = 1;
            $addSubFieldValues['sub_step_current'] = 1;

        }
        else {
            $addFieldValues['next_step_code'] = "";
            $addFieldValues['next_group_code'] = "";
            $addFieldValues['step_number'] = $intoStep;
            $addFieldValues['step_current'] = 0;

            $addSubFieldValues['next_substep_code'] = "";
            $addSubFieldValues['next_subgroup_code'] = "";
            $addSubFieldValues['sub_step_number'] = $intoStep;
            $addSubFieldValues['sub_step_current'] = 0;
        }
    }
    else {//==ini manipulasi saat edit
//            ////cekMerah("build values saat EDIT");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][$intoStep]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][$intoStep]['target'],
        );
        $addFieldValues['next_step_code'] = "";
        $addFieldValues['next_group_code'] = "";
        $addFieldValues['step_number'] = $intoStep;
        $addFieldValues['step_current'] = 0;

        $addSubFieldValues['next_substep_code'] = "";
        $addSubFieldValues['next_subgroup_code'] = "";
        $addSubFieldValues['sub_step_number'] = $intoStep;
        $addSubFieldValues['sub_step_current'] = 0;
    }


    foreach ($addFieldValues as $fName => $value) {
        $sessionData['main'][$fName] = $value;
        $sessionData['tableIn_master'][$fName] = $value;
    }


    if (isset($addSubFieldValues) && sizeof($addSubFieldValues) > 0) {

        if (sizeof($sessionData['items']) > 0 && sizeof($sessionData['tableIn_detail']) > 0) {
            foreach ($sessionData['items'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['items'][$iID][$fName] = $value;
                }
            }
            foreach ($sessionData['tableIn_detail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['tableIn_detail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($sessionData['main']['step_number']) ? $sessionData['main']['step_number'] : 1,
                    //                    "valid_qty"       => $sessionData['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $sessionData['tableIn_detail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($sessionData['items'][$iID][$src])) {
                            $sessionData['tableIn_detail'][$iID][$target] = $sessionData['items'][$iID][$src];
                        }
                    }
                }
            }

        }

        if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0 && sizeof($sessionData['tableIn_detail_subdetail']) > 0) {
            foreach ($sessionData['items2_sum'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['items2_sum'][$iID][$fName] = $value;
                }
            }
            foreach ($sessionData['tableIn_detail_subdetail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($sessionData['main']['step_number']) ? $sessionData['main']['step_number'] : 1,
                    //                    "valid_qty"       => $sessionData['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $sessionData['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($sessionData['items2_sum'][$iID][$src])) {
                            $sessionData['tableIn_detail_subdetail'][$iID][$target] = $sessionData['items2_sum'][$iID][$src];
                        }
                    }
                }
            }

        }
    }


//    if(isset($_GET['confirm']) && $_GET['confirm']=="1"){
//
//    }else{
//
//        echo "<script>\n";
//        echo "if(top.document.getElementById('ck')){top.document.getElementById('ck').checked=false;}\n";
//        echo "if(top.document.getElementById('btnProcess')){top.document.getElementById('btnProcess').disabled=true;}\n";
//        echo "</script>\n";
//    }


    // khusus setoran......................................................................
    $ci->load->model("MdlTransaksi");

    $additionalSource = isset($configCoreJenis['additionalSource']) ? $configCoreJenis['additionalSource'] : false;
    $additionalItemSource = isset($configCoreJenis['additionalItemSource']) ? $configCoreJenis['additionalItemSource'] : array();
    $additionalItemResult = isset($configCoreJenis['additionalItemResult']) ? $configCoreJenis['additionalItemResult'] : array();
    $additionalItemSourceKey = isset($configCoreJenis['additionalItemSourceKey']) ? $configCoreJenis['additionalItemSourceKey'] : array();
    $additionalPembulatan = isset($configCoreJenis['valueInjectorBulat']) ? $configCoreJenis['valueInjectorBulat'] : array();
    $additionalPembulatanPajak = isset($configCoreJenis['injectorPajak']) ? $configCoreJenis['injectorPajak'] : array();
    $pairPembulatanPajak = isset($configCoreJenis['pairPajak']) ? $configCoreJenis['pairPajak'] : array();
    $additionalPembulatanPajakReseller = isset($configCoreJenis['injectorPajakReseller']) ? $configCoreJenis['injectorPajakReseller'] : array();
    $pairPembulatanPajakReseller = isset($configCoreJenis['pairPajakReseller']) ? $configCoreJenis['pairPajakReseller'] : array();
    if ($additionalSource == true) {
        if (sizeof($additionalItemSource)) {
            //cekUngu("cetak ITEMS AWAL...");
            //arrPrint($sessionData['items']);
            foreach ($sessionData['items'] as $id => $iSpec) {

                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                // $trr->addFilter("param='main'");
                $trr->addFilter("transaksi_id='$id'");
                $tmpR = $trr->lookupDataRegistries()->result();
                // arrPrint($tmpR);
                $main = blobDecode($tmpR[0]->main);

                //cekUngu(":: MAIN ID $id");
//                arrPrint($main);
//                mati_disini();
                //cekUngu(":: ITEMS ID $id");
                //arrPrint($iSpec);

                foreach ($additionalItemSource as $key => $val) {
//                    if (!isset($sessionData['items'][$id][$key])) {

                    $new_key = (sizeof($additionalItemResult) > 0 && (isset($additionalItemResult[$key]))) ? $additionalItemResult[$key] : $key;
                    if (!isset($sessionData['items'][$id][$new_key])) {

                        $sessionData['items'][$id][$new_key] = 0;
                    }

                    if (sizeof($additionalItemSourceKey) > 0) {
                        $persenValue = ($sessionData['items'][$id][$additionalItemSourceKey['top']] / $main[$additionalItemSourceKey['bottom']]) * 100;
                    }
                    else {
                        $persenValue = 0;
                    }
                    $key_result = makeValue($val, $main, $main, 0);
                    $sessionData['items'][$id]['persenValue'] = $persenValue;
                    $sessionData['items'][$id][$new_key] = ($persenValue / 100) * $key_result;


//                    }
                }
            }

//                $persenValue = ($sessionData['items'][$id]['nilai_bayar']/$sessionData['items'][$id]['harga_nett2'])*100;
//                $sessionData['items'][$id]['persenValue'] = $persenValue;
//                foreach ($additionalItemSource as $key => $val){
//
//                    $sessionData['items'][$id]['source_'.$key] = ($sessionData['items'][$id][$key]*$persenValue)/100;
//                }
        }
//        mati_disini();
    }

    if (sizeof($alwaysUpdaters) > 0) {
        foreach ($alwaysUpdaters as $key => $src) {
            $sessionData['main'][$key] = isset($ci->session->login[$src]) ? $ci->session->login[$src] : "";
        }
    }


    if (sizeof($additionalPembulatan) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatan['source'];
        $varBulat = makeDppBulat($sessionData['main'][$source]);
        foreach ($additionalPembulatan['injectTo'] as $key => $fields) {
            $srcValue_tmp = $varBulat[$key];
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($fields, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink2("$fields :: asli -> $srcValue_tmp :: bulat -> $srcValue");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($fields, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$fields];
                    $srcValue = $mFormula($srcValue_tmp);
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }
            $sessionData['main'][$fields] = $srcValue;
        }
    }
    if (sizeof($additionalPembulatanPajak) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajak['source'];
        $varBulat = pembulatan_pajak($sessionData['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajak as $key => $fields) {
//            cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $sessionData['main'][$key] = $varBulat[$fields];
        }
    }
    if (sizeof($additionalPembulatanPajakReseller) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajakReseller['source'];
        $varBulat = pembulatan_pajak($sessionData['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajakReseller as $key => $fields) {
//            cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $sessionData['main'][$key] = $varBulat[$fields];
        }
    }


    //region valueReplaceCalculate
    $valueReplaceCalculate = isset($configCoreJenis['valueReplaceCalculate']) ? $configCoreJenis['valueReplaceCalculate'] : array();
    if (sizeof($valueReplaceCalculate) > 0) {
        foreach ($valueReplaceCalculate as $gate) {
            $curentVal = $sessionData['main'][$gate];
            if ($curentVal > 0) {

            }
            else {
                $sessionData['main'][$gate] = 0;
                $sessionData['tableIn_master_values'][$gate] = 0;
            }
//            cekHitam($gate);
        }
    }


    //endregion


    //------------------------------------------------------------------
    $recapItemBuilder = isset($configCoreJenis['recapItemBuilder']) ? $configCoreJenis['recapItemBuilder'] : array();
//    arrprint($recapItemBuilder);
//    matiHere();
    if (sizeof($recapItemBuilder) > 0) {
        $gateNameSource = $recapItemBuilder['gateNameSource'];
        $gateNameTarget = $recapItemBuilder['gateNameTarget'];
        $key = $recapItemBuilder['key'];
        $vals = $recapItemBuilder['val'];
        //------ hapus/reset items2_sum
        if (isset($sessionData['items4_sum'])) {
            $sessionData['items4_sum'] = null;
            unset($sessionData['items4_sum']);
        }

        if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
            foreach ($sessionData['items'] as $ii => $iSpec) {
                //------ build ulang items2_sum
                if (!isset($sessionData['items4_sum'][$iSpec[$key]])) {
                    $iSpec['id'] = $iSpec[$key];
                    $sessionData['items4_sum'][$iSpec[$key]] = $iSpec;

                    foreach ($vals as $val) {
                        $sessionData['items4_sum'][$iSpec[$key]][$val] = 0;
                    }
                }
                //------ build ulang items2_sum
                foreach ($vals as $val) {
                    $sessionData['items4_sum'][$iSpec[$key]][$val] += $iSpec[$val];
                }
            }
        }
    }

//    arrPrintWebs($sessionData['main']);
//    mati_disini("value builder helper");


    // rekalkulasi TableInMasterValues di akhir value builder....
    $populators = array( //==sumber2 yang dipopulasi
        "main",
    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($sessionData[$populateTarget])) {
            $sessionData[$populateTarget] = array();
        }
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $sessionData[$populateTarget][$key] = $val;
                }
            }
        }
    }
    // matiHEre();


    //------------------------------------------------------------------
    switch ($tr) {
        case "583":
        case "585":
        case "5833":
        case "5855":
        case "5822":
        case "9822":
        case "19822":
            $gatePisah = pisahBarangJasa($sessionData);
            $sessionData["items4"] = $gatePisah["items4"];
            $sessionData["items4_sum"] = $gatePisah["items4_sum"];
            $sessionData["items9_sum"] = $gatePisah["items9_sum"];
            $sessionData["items10_sum"] = $gatePisah["items10_sum"];
//arrPrintHijau($gatePisah);
            break;
    }

    return $sessionData;
}

function fillValues_he_value_builder_ns($tr, $fromStep = 0, $intoStep = 0, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor = 0, $sessionData)
{
    if ($sessionData == NULL) {
        $msg = "Session data diperlukan untuk melanjutkan transaksi. Sesi anda belum terdaftar, silahkan hubungi admin. errcode" . __FUNCTION__;
        mati_disini($msg);
    }

    $cCode = cCodeBuilderMisc($tr);

    /*
     *
     *
#1 [custom value gates]
- items yang perlu dihitung

#2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main


#3 [custom value builder]
- main yang perlu dihitung

$4 kalau ada yang perlu dipopulasi
#5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)

#6 [POPULATE SAMPING]
- populate items ke detail_values (kecuali yang termasuk exceptions)
- populate main ke main_values (kecuali yang termasuk exceptions)

#7 [OTHERS]
- valueInjectors
- pairMakers
- cloners		> dari out_master ke out_detail

#8 [NGOPI]
- tableIn_master	> dari main
- tableIn_detail	> dari items
- tableIn_detail2	> dari items2
- tableIn_detail_rsltItems	> dari rsltItems
     */


    $valueGateConfig = isset($configCoreJenis['valueGates']) ? $configCoreJenis['valueGates'] : array();
    $tableInConfig = isset($configCoreJenis['tableIn']) ? $configCoreJenis['tableIn'] : array();
    $tableInConfig_static = isset($configCoreJenis['tableIn_static']) ? $configCoreJenis['tableIn_static'] : array();
    //value builder
    $valueBuilderConfig = isset($configCoreJenis['valueBuilders']) ? $configCoreJenis['valueBuilders'] : array();
    $valueBuilderConfig2 = isset($configCoreJenis['valueBuilders2']) ? $configCoreJenis['valueBuilders2'] : array();
    $valueBuilderConfig2_sum = isset($configCoreJenis['valueBuilders2_sum']) ? $configCoreJenis['valueBuilders2_sum'] : array();
    $valueBuilderConfig_rsltItems = isset($configCoreJenis['valueBuilders_rsltItems']) ? $configCoreJenis['valueBuilders_rsltItems'] : array();
    $valueBuilderConfig_rsltItems2 = isset($configCoreJenis['valueBuilders_rsltItems2']) ? $configCoreJenis['valueBuilders_rsltItems2'] : array();

    //value spreaderadditionalBuilders
    $valueSpreaderConfig = isset($configCoreJenis['valueSpreaders']) ? $configCoreJenis['valueSpreaders'] : array();

    $itemNumLabels = isset($configUiJenis['shoppingCartNumFields']) ? $configUiJenis['shoppingCartNumFields'] : array();
    $detailValueFields = isset($configCoreJenis['tableIn']['detailValues']) ? $configCoreJenis['tableIn']['detailValues'] : array();
    $availPayments = isset($configUiJenis['availPayments']) ? $configUiJenis['availPayments'] : array();
    $tagihanSrc = isset($configUiJenis['tagihanSrc']) ? $configUiJenis['tagihanSrc'] : "sisa";

    $pairMakers = isset($configUiJenis['pairMakers'][$intoStep]) ? $configUiJenis['pairMakers'][$intoStep] : array();
    $pairElementMakers = isset($configUiJenis['pairElementGateBuilder'][$intoStep]) ? $configUiJenis['pairElementGateBuilder'][$intoStep] : array();
    $pairInjectors = isset($configUiJenis['pairInjectors'][$intoStep]) ? $configUiJenis['pairInjectors'][$intoStep] : array();
    $valueInjectors = isset($configUiJenis['mainValueInjectors']) ? $configUiJenis['mainValueInjectors'] : array();


    ////
    $itemCloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();
    $itemClonerTargets = config_item('heGlobalPopulators') != null ? config_item('heGlobalPopulators') : array();
    $itemClonerTarget_sub = config_item('GlobalPopulator_sub') != null ? config_item('GlobalPopulator_sub') : array();
    $itemRecapExceptions = config_item('transaksi_itemRecapExceptions') != null ? config_item('transaksi_itemRecapExceptions') : array();
    $subItemRecapExceptions = config_item('transaksi_subitemRecapExceptions') != null ? config_item('transaksi_subitemRecapExceptions') : array();
    $itemPopulateExceptions = config_item('transaksi_itemPopulateExceptions') != null ? config_item('transaksi_itemPopulateExceptions') : array();
    $masterPopulateExceptions = config_item('transaksi_masterPopulateExceptions') != null ? config_item('transaksi_masterPopulateExceptions') : array();
//    $cloners = config_item('transaksi_masterToItemCloners') != null ? config_item('transaksi_masterToItemCloners') : array();

    $fixedItem_subValues = config_item('transaksi_fixedItem_subValues') != null ? config_item('transaksi_fixedItem_subValues') : array();
    $fixedTableIn_subValues = config_item('transaksi_fixedTableIn_subValues') != null ? config_item('transaksi_fixedTableIn_subValues') : array();
    $fixedTableIn_values = config_item('transaksi_fixedTableIn_values') != null ? config_item('transaksi_fixedTableIn_values') : array();

    $populatorsGate = isset($configCoreJenis['populatorsGate']) ? $configCoreJenis['populatorsGate'] : "items";
    $populators = isset($configCoreJenis['populators']) ? $configCoreJenis['populators'] : array();
    $addBuilders = isset($configCoreJenis['additionalBuilders']) ? $configCoreJenis['additionalBuilders'] : array();
    $addMainBuilders = isset($configCoreJenis['additionalMainBuilders']) ? $configCoreJenis['additionalMainBuilders'] : array();
    $extFormulaConfig = isset($configCoreJenis['extFormula']) ? $configCoreJenis['extFormula'] : array();

    $additionalPostMainBuilder = isset($configCoreJenis['additionalPostMainBuilder']) ? $configCoreJenis['additionalPostMainBuilder'] : array();
    //sessionToGateAlwaysUpdaters
    $alwaysUpdaters = null != config_item("sessionToGateAlwaysUpdaters") ? config_item("sessionToGateAlwaysUpdaters") : array();

    $productCostInjector = isset($configUiJenis['pairCostInjectors'][$intoStep]) ? $configUiJenis['pairCostInjectors'][$intoStep] : array();
    $gateExchangeConfig = isset($configUiJenis['gateExchange']) ? $configUiJenis['gateExchange'] : array();

    $rowConfigRound = isset($configCoreJenis['additionalRound']) ? $configCoreJenis['additionalRound'] : array();
    $recapValueException = isset($configCoreJenis['recapValueException']) ? $configCoreJenis['recapValueException'] : array();
    //-----------------------------
    $valueSubDetail = isset($configCoreJenis['valueSubDetail']) ? $configCoreJenis['valueSubDetail'] : false;
    $valueSubDetailRecap = isset($configCoreJenis['valueSubDetailRecap']) ? $configCoreJenis['valueSubDetailRecap'] : array();

    //transaksi_fixedTableIn_subValues

    $ci =& get_instance();

    // cekHitam("session MAIN before master dependent");

    if ($valueSubDetail == true) {
        if (isset($valueGateConfig['detail2'])) {
            if (sizeof($valueGateConfig['detail2']) > 0) {
                if (isset($sessionData['items2']) && sizeof($sessionData['items2']) > 0) {
                    foreach ($sessionData['items2'] as $id => $subSpec) {
                        foreach ($subSpec as $ii => $sSpec) {
                            foreach ($valueGateConfig['detail2'] as $key => $src) {
                                $srcValue_tmp = makeValue($src, $sessionData['items2'][$id][$ii], $sessionData['items2'][$id][$ii], 0);
                                $srcValue = $srcValue_tmp;
                                $sessionData['items2'][$id][$ii][$key] = $srcValue;
                                $sessionData['items2'][$id][$ii]["sub_" . $key] = $sSpec['jml'] * $srcValue;
                            }
                        }
                    }
                    // direcap ke items
                    $target_recap = "items";
                    foreach ($sessionData['items2'] as $id => $subSpec) {
                        $noCtr = 0;
                        foreach ($subSpec as $ii => $sSpec) {
                            $noCtr++;
                            if ($noCtr == 1) {
                                foreach ($valueSubDetailRecap as $irecap) {
                                    if (substr($irecap, 0, 4) != "sub_") {
                                        $sessionData[$target_recap][$id][$irecap] = 0;
                                    }
                                }
                            }

                            foreach ($sSpec as $skey => $sval) {
                                if ((in_array($skey, $valueSubDetailRecap)) && (is_numeric($sval))) {
                                    if (substr($skey, 0, 4) != "sub_") {
                                        $sessionData['items2'][$id][$ii]["sub_" . $skey] = ($sessionData['items2'][$id][$ii]["jml"] * $sessionData['items2'][$id][$ii][$skey]);
                                        $sessionData[$target_recap][$id][$skey] += ($sessionData['items2'][$id][$ii]["jml"] * $sval);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /*
     #1 [custom value gates]
- items yang perlu dihitung
     */

    // membalikkan key dengan value.....
    // value gerbang menjadi key dan ceil/floor menjadi value, seperti ini ->
    // ppn => floor
    // dpp => ceil
    foreach ($extFormulaConfig as $gateName => $extSpec) {
        foreach ($extSpec as $formula => $fSpec) {
            foreach ($fSpec as $gate) {
                $extFormula[$gateName][$gate] = $formula;
            }
        }
    }

    if (isset($valueGateConfig['detail'])) {
        if (sizeof($valueGateConfig['detail']) > 0) {
            if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['items'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items'][$id], $sessionData['items'][$id], 0);
                        if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                            if (array_key_exists($key, $extFormula['detail'])) {
                                $mFormula = $extFormula['detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['rsltItems'])) {
        if (sizeof($valueGateConfig['rsltItems']) > 0) {
            if (isset($sessionData['rsltItems']) && sizeof($sessionData['rsltItems']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['rsltItems'] as $id => $iSpec) {
                    $iCtr++;
                    if ($iSpec['jml'] > 0) {
                        foreach ($valueGateConfig['rsltItems'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $sessionData['rsltItems'][$id], $sessionData['rsltItems'][$id], 0);
                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
                                if (array_key_exists($key, $extFormula['detail'])) {
                                    $mFormula = $extFormula['detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $sessionData['rsltItems'][$id][$key] = $srcValue;
                        }
                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['detail2_sum'])) {
        if (sizeof($valueGateConfig['detail2_sum']) > 0) {
            if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0) {
                $iCtr = 0;
                foreach ($sessionData['items2_sum'] as $id => $iSpec) {
                    $iCtr++;
                    foreach ($valueGateConfig['detail2_sum'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items2_sum'][$id], $sessionData['items2_sum'][$id], 0);
                        if (isset($extFormula['detail2_sum']) && sizeof($extFormula['detail2_sum']) > 0) {
                            if (array_key_exists($key, $extFormula['detail2_sum'])) {
                                $mFormula = $extFormula['detail2_sum'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items2_sum'][$id][$key] = $srcValue;
                    }
                }
            }
        }
    }
//cekHitam("cetak items2_sum");
//arrPrintHijau($sessionData['items2_sum']);

    if (isset($valueGateConfig['sub_detail'])) {
        if (sizeof($valueGateConfig['sub_detail']) > 0) {
            if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['items2_sum'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['items2_sum'][$id], $sessionData['items2_sum'][$id], 0);

                        if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail'])) {
                                $mFormula = $extFormula['sub_detail'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['items2_sum'][$id][$key] = $srcValue;
                    }

                    //                    }
                }
            }
            if (isset($sessionData['items2']) && sizeof($sessionData['items2']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['items2'] as $id => $ixSpec) {
                    $iCtr++;

                    foreach ($ixSpec as $ii => $iSpec) {
                        foreach ($valueGateConfig['sub_detail'] as $key => $src) {
                            $srcValue_tmp = makeValue($src, $iSpec, $iSpec, 0);
                            if (isset($extFormula['sub_detail']) && sizeof($extFormula['sub_detail']) > 0) {
                                if (array_key_exists($key, $extFormula['sub_detail'])) {
                                    $mFormula = $extFormula['sub_detail'][$key];
                                    $srcValue = $mFormula($srcValue_tmp);
                                }
                                else {
                                    $srcValue = $srcValue_tmp;
                                }
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                            $sessionData['items2'][$id][$ii][$key] = $srcValue;
                        }
                    }


                    //                    }
                }
            }
        }
    }
    if (isset($valueGateConfig['sub_detail_items'])) {
        if (sizeof($valueGateConfig['sub_detail_items']) > 0) {
            if (isset($sessionData['rsltItems3_sub']) && sizeof($sessionData['rsltItems3_sub']) > 0) {

                $iCtr = 0;
                foreach ($sessionData['rsltItems3_sub'] as $id => $iSpec) {
                    $iCtr++;

                    //                    if ($iSpec['jml'] > 0) {
                    foreach ($valueGateConfig['rsltItems3_sub'] as $key => $src) {
                        $srcValue_tmp = makeValue($src, $sessionData['rsltItems3_sub'][$id], $sessionData['rsltItems3_sub'][$id], 0);

                        if (isset($extFormula['sub_detail_items']) && sizeof($extFormula['sub_detail_items']) > 0) {
                            if (array_key_exists($key, $extFormula['sub_detail_items'])) {
                                $mFormula = $extFormula['sub_detail_items'][$key];
                                $srcValue = $mFormula($srcValue_tmp);
                            }
                            else {
                                $srcValue = $srcValue_tmp;
                            }
                        }
                        else {
                            $srcValue = $srcValue_tmp;
                        }
                        $sessionData['rsltItems3_sub'][$id][$key] = $srcValue;
                    }
                    //                    }
                }
            }
        }
    }
    //-----
//    if (isset($valueGateConfig['rsltItems_revert'])) {
//        if (sizeof($valueGateConfig['rsltItems_revert']) > 0) {
//            if (isset($sessionData['rsltItems_revert']) && sizeof($sessionData['rsltItems_revert']) > 0) {
//                $iCtr = 0;
//                foreach ($sessionData['rsltItems_revert'] as $id => $iSpec) {
//                    $iCtr++;
//                    if ($iSpec['jml'] > 0) {
//                        foreach ($valueGateConfig['rsltItems_revert'] as $key => $src) {
//                            $srcValue_tmp = makeValue($src, $sessionData['rsltItems_revert'][$id], $sessionData['rsltItems_revert'][$id], 0);
//                            if (isset($extFormula['detail']) && sizeof($extFormula['detail']) > 0) {
//                                if (array_key_exists($key, $extFormula['detail'])) {
//                                    $mFormula = $extFormula['detail'][$key];
//                                    $srcValue = $mFormula($srcValue_tmp);
//                                }
//                                else {
//                                    $srcValue = $srcValue_tmp;
//                                }
//                            }
//                            else {
//                                $srcValue = $srcValue_tmp;
//                            }
//                            $sessionData['rsltItems_revert'][$id][$key] = $srcValue;
//                        }
//                    }
//                }
//            }
//        }
//    }


    if (sizeof($fixedItem_subValues) > 0) {
        if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
            foreach ($sessionData['items'] as $id => $iSpec) {
                foreach ($fixedItem_subValues as $key => $src) {
                    $sessionData['items'][$id][$key] = makeValue($src, $sessionData['items'][$id], $sessionData['items'][$id], "");
                    //cekbiru("filling $key on $id with " . $sessionData['items'][$id][$key]);
                }
            }
        }

    }

    /*
     #2 [REKAP]
- items direkap ke main
- items2 direkap ke main
- items2_sum direkap ke main
- rsltItems direkap ke main
- rsltItems2 direkap ke main
     */

    if (sizeof($productCostInjector) > 0) {
        $source = $productCostInjector['source'];
        $target = $productCostInjector['target'];
        $jenis = $productCostInjector['jenis'];
        if (isset($sessionData[$source])) {
            foreach ($sessionData[$source] as $pID => $kSpec) {
                if (isset($kSpec[$jenis])) {
                    foreach ($kSpec[$jenis] as $h => $jSpec) {
                        if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                            foreach ($sessionData[$target] as $i => $rslt) {
                                if ($rslt['id'] == $pID) {
                                    foreach ($productCostInjector['kolom'] as $k => $v) {
                                        $jSpecName = str_replace(' ', '_', $jSpec['nama']);

//                                        $sessionData[$target][$i][$k."_".$jSpecName] = $jSpec[$v];
                                        $sessionData[$target][$i][$k . "_" . $h] = $jSpec[$v];

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================
//    cekHitam(":::: bagian exchange :::: || FILE: " . __FILE__);
    if (sizeof($gateExchangeConfig) > 0) {
        foreach ($gateExchangeConfig as $gateExchange) {
            if (isset($gateExchange['enabled']) && ($gateExchange['enabled'] == true)) {
                $source = $gateExchange['source'];
                $postfix = $gateExchange['postfix'];
                $blacklist = $gateExchange['blacklist'];
                $exchange = isset($sessionData['main'][$source]) ? $sessionData['main'][$source] : NULL; // main exchange
                $mainGate = array(
                    "main",
                );
                $detailGate = array(
                    "items",
                    "items2_sum",
                    "items3_sum",
                    "items5_sum",
                );

                $pakai_ini = 1;
                if ($pakai_ini == 1) {
                    foreach ($mainGate as $gateName) {
                        if (isset($sessionData[$gateName]) && (sizeof($sessionData[$gateName]) > 0)) {
                            if ($exchange != NULL) {
                                foreach ($sessionData[$gateName] as $mainKey => $mainVal) {
                                    if (is_numeric($mainVal)) {
                                        $newPostfix = $postfix . "__";
                                        $subNewPostfix = "sub_" . $postfix . "__";
                                        $mainKey_ex = explode("__", $mainKey);
                                        // direset dulu
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $sessionData[$gateName][$postfix . "__" . $mainKey] = 0;
//                                            cekKuning("$gateName $postfix $mainKey direset menjadi 0");
                                        }
//                                    if ((substr($mainKey, 0, strlen($newPostfix)) != $newPostfix) && (substr($mainKey, 0, strlen($subNewPostfix)) != $subNewPostfix)) {
                                        if (!in_array($mainKey_ex[0], $blacklist)) {
                                            $sessionData[$gateName][$postfix . "__" . $mainKey] = $mainVal * $exchange;
//                                            cekPink("$gateName $postfix $mainKey diisi menjadi $mainVal * $exchange " . $mainVal * $exchange);
                                        }
                                    }
                                }
                            }
                            else {
//                                cekMerah(":: $gateName [$source][$exchange] :: tidak mengalikan gerbang baru ::");
                            }
                        }
                    }
                }

                foreach ($detailGate as $gateName) {
                    if (isset($sessionData[$gateName]) && (sizeof($sessionData[$gateName]) > 0)) {
                        foreach ($sessionData[$gateName] as $iID => $detailSpec) {
                            if (sizeof($detailSpec) > 0) {
                                $exchange = isset($detailSpec[$source]) ? $detailSpec[$source] : NULL;
                                if ($exchange != NULL) {
//                                    cekHijau(":: $gateName [$exchange] :: mengalikan gerbang baru ::");
                                    foreach ($detailSpec as $detailKey => $detailVal) {
                                        if (is_numeric($detailVal)) {
                                            $newPostfix = $postfix . "__";
                                            $subNewPostfix = "sub_" . $postfix . "__";
                                            $detailPostFix = substr($detailKey, 0, strlen($subNewPostfix));

                                            $detailKey_ex = explode("__", $detailKey);


//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $sessionData[$gateName][$iID][$postfix . "__" . $detailKey] = 0;

//                                                cekKuning("$gateName $postfix $detailKey direset menjadi 0");
                                            }

//                                            if ((substr($detailKey, 0, strlen($newPostfix)) != $newPostfix) && ($detailPostFix != $subNewPostfix)) {
                                            if (!in_array($detailKey_ex[0], $blacklist)) {
                                                $sessionData[$gateName][$iID][$postfix . "__" . $detailKey] = $detailVal * $exchange;

//                                                cekPink("$gateName $postfix $detailKey diisi menjadi $detailVal * $exchange " . $detailVal * $exchange);
                                            }
                                        }
                                    }
                                }
                                else {
//                                    cekMerah(":: $gateName [$exchange] :: tidak mengalikan gerbang baru ::");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // ===:::: bagian exchange ::::===================
    // ===:::: bagian exchange ::::===================


    $recapMakers = array( //==sumber detail ke target main
        "itemsSrc1" => "main",
        "itemsSrc1_sum" => "main",
        "itemsTarget1" => "main",
        "itemSrcBendahara_sum" => "main",
        "items" => "main",
        "itemSrc_sum" => "main",
        "items2" => "main",
        "items2_sum" => "main",
        "items3_sum" => "main",
        "items4_sum" => "main",
        "items5_sum" => "main",
//        "items6_sum" => "main",// me-summary komposisi dari paket
        "rsltItems" => "main",
        "rsltItems2" => "main",
        "rsltItems3" => "main",
        //------
        "rsltItems_revert" => "main",
//        "items6"=>"main",
    );
    //untuk reset jika tidak diperbolehkan direkap ke main, baca dari configCore:: recapValueException
    if (sizeof($recapValueException) > 0) {
        foreach ($recapValueException as $valmakerKey) {
            if (isset($recapMakers[$valmakerKey])) {
                unset($recapMakers[$valmakerKey]);
            }
        }
    }

    foreach ($recapMakers as $src => $target) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            $iCtr = 0;
            foreach ($sessionData[$src] as $iID => $iCols) {
                $iCtr++;
                //===reset dulu
                if ($iCtr == 1) {
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($sessionData['main_elements'][$iKey])) {

                                if (!in_array($iKey, $itemRecapExceptions)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $sessionData[$target][$iKey] = 0;
                                    }
                                }
                            }
                        }
                    }
                }
                if (sizeof($iCols) > 0) {
                    foreach ($iCols as $iKey => $iVal) {
//                        cekMErah($iKey);
                        if (!isset($sessionData['main_elements'][$iKey])) {

                            if (!in_array($iKey, $itemRecapExceptions)) {
                                if (is_numeric($iVal)) {
                                    if (substr($iKey, 0, 4) != "sub_") {
                                        $sessionData[$src][$iID]["sub_" . $iKey] = ($sessionData[$src][$iID]["jml"] * $sessionData[$src][$iID][$iKey]);
                                        $sessionData[$target][$iKey] += ($sessionData[$src][$iID]["jml"] * $iVal);

                                        if ($src == "rsltItems") {
//                                            cekPink2(__LINE__ . " $iKey : " . $sessionData[$src][$iID]["jml"] . " * $iVal");
                                        }
                                    }
                                }


                            }


                            else {
//                                cekHitam($iKey);
                            }
//                        }
                        }
                        else {
//                            cekHitam($iKey);
                        }

                    }
                }
            }
        }
    }
    //recap multidimesnsional detail/ dua level array detail
    $recapMaker_sub = array(
        "items6" => "main",
    );

    foreach ($recapMaker_sub as $src => $target) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            $iCtr = 0;
            foreach ($sessionData[$src] as $iID => $iiCols) {
                $iCtr++;
                //===reset dulu
                $iCtr = 0;
                foreach ($iiCols as $ixCol => $iCols) {
                    $iCtr++;
                    if ($iCtr == 1) {
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($sessionData['main_elements'][$iKey])) {

                                    if (!in_array($iKey, $subItemRecapExceptions)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $sessionData[$target][$iKey] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (sizeof($iCols) > 0) {
                        foreach ($iCols as $iKey => $iVal) {
                            if (!isset($sessionData['main_elements'][$iKey])) {

//                        if(is_numeric($iVal)){
                                if (!in_array($iKey, $subItemRecapExceptions)) {
                                    if (is_numeric($iVal)) {
                                        if (substr($iKey, 0, 4) != "sub_") {
                                            $sessionData[$src][$iID][$ixCol]["sub_" . $iKey] = ($sessionData[$src][$iID][$ixCol]["jml"] * $sessionData[$src][$iID][$ixCol][$iKey]);
                                            $sessionData[$target][$iKey] += ($sessionData[$src][$iID][$ixCol]["jml"] * $iVal);

                                            if ($src == "rsltItems") {
//                                                cekPink2(__LINE__ . " $iKey : " . $sessionData[$src][$iID]["jml"] . " * $iVal");
                                            }
                                        }


                                    }


                                }
//                        }
                            }

                        }
                    }
                }
            }
        }
    }


    /*
         #3 [custom value builder]
    - main yang perlu dihitung
         */

    if (isset($valueGateConfig['master'])) {
        if (sizeof($valueGateConfig['master']) > 0) {
            foreach ($valueGateConfig['master'] as $key => $src) {
                if (isset($sessionData['main'][$src])) {

                    $sessionData['main'][$key] = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                }

            }
        }
    }

    // ---- mater dependence MAIN
    //region master (dependen)
    // cekHitam(":: master_dependent ::");
    if (isset($valueGateConfig['master_dependent'])) {
        if (sizeof($valueGateConfig['master_dependent']) > 0) {
            foreach ($valueGateConfig['master_dependent'] as $srcKey => $anuSpec) {
                if (isset($sessionData['main'][$srcKey])) {
                    $srcValue = $sessionData['main'][$srcKey];
                    if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                        foreach ($anuSpec[$srcValue] as $k => $src) {
                            $srcVal = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                            $sessionData['main'][$k] = $srcVal;
                            // cekPink2("$k = $src ---> $srcVal " . __LINE__);
                        }
                    }
                    else {
                        //cekhijau("$srcValue TIDAK memenuhi syarat");
                    }
                }
            }
        }
    }
    //endregion

//matiHere(__LINE__);
    // ---- mater dependence ITEMS
    //region master (dependen)
    if (isset($valueGateConfig['master_dependent_items'])) {
        if (sizeof($valueGateConfig['master_dependent_items']) > 0) {
            foreach ($valueGateConfig['master_dependent_items'] as $srcKey => $anuSpec) {
                if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
                    foreach ($sessionData['items'] as $ii => $iSpec) {
                        if (isset($iSpec[$srcKey])) {
                            $srcValue = $iSpec[$srcKey];
                            if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                                foreach ($anuSpec[$srcValue] as $k => $src) {
                                    $srcVal = makeValue($src, $iSpec, $iSpec, 0);
                                    $sessionData['items'][$ii][$k] = $srcVal;
                                }
                            }
                            else {
                                //cekhijau("$srcValue TIDAK memenuhi syarat");
                            }
                        }

                    }
                }
            }
        }
    }
    //endregion

    //pembulatan master untuk AR/ Ap payment cek jika nilai pembulatan vs row nilai

//arrPrintKuning($valueBuilderConfig);
    if (sizeof($valueBuilderConfig) > 0) {
        foreach ($valueBuilderConfig as $key => $src) {
            $srcValue_tmp = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($key, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                        cekPink2("$key apa adanya...");
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($key, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$key];
                    $srcValue = $mFormula($srcValue_tmp);
//                    cekPink("nilai asli: $srcValue_tmp :: nilai pembulatan: $srcValue :: mode $mFormula -> $key");
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }

            $sessionData['main'][$key] = $srcValue;
            // cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
        }
    }
//    arrPrintWebs($sessionData['main']);


// $4 kalau ada yang perlu dipopulasi

    if (sizeof($populators) > 0) {
        if (sizeof($sessionData[$populatorsGate])) {
            foreach ($populators as $popID => $popSpec) {
                $nilaiAsal = $sessionData['main'][$popSpec['mainSrc']['key']];
                //cekmerah("nilaiAsal: $nilaiAsal");
                $targetKey = $popSpec['itemTarget']['key'];
                $maxAmountSrc = $popSpec['itemTarget']['maxAmountSrc'];
                foreach ($sessionData[$populatorsGate] as $iID => $iSpec) {
                    $maxItemAmount = $sessionData[$populatorsGate][$iID][$maxAmountSrc];
                    if ($nilaiAsal >= $maxItemAmount) {
                        $diambil = $maxItemAmount;
                        //cekmerah("ambil nilai dari maxItemAmount: $maxItemAmount");
                    }
                    else {
                        $diambil = $nilaiAsal;
                        //cekmerah("ambil nilai dari nilaiAsal: $nilaiAsal");
                    }
                    $diambil = reformatExponent($diambil);
                    if ($diambil < 0) {
//                        cekHitam("masuk disini: $diambil");
                        $diambil = 0;
                    }
                    $nilaiAsal -= $diambil;
                    $sessionData[$populatorsGate][$iID][$targetKey] = $diambil;
                    //cekmerah("$targetKey akan diisi dengan $diambil");
                }

            }
        }
        else {
            //cekmerah("NO ITEMS TO inject");
        }

    }
    else {
        //cekmerah("populators are not ready");
    }
//    die("DONE POPULATING values");

// #5 kalau ada yang perlu dihitung ulang di items (karena baru saja ada populasi)
    if (sizeof($rowConfigRound) > 0) {
//        arrPrint($rowConfigRound);
        foreach ($rowConfigRound as $gate => $target) {
            // $src = $sessionData['main'][$gate];
            // $trg = round($sessionData['main'][$gate]);
            // $val=$trg > $src ? $trg - $src : $src - $trg;
            // cekMerah($src."<---src trg -->".$trg." selisih ".$val);
            // cekHitam($src-$trg);
            $selisih_x = ($sessionData['main'][$gate] / round($sessionData['main'][$gate])) - 1;
            // $selisih = $sessionData['main'][$gate] - round($sessionData['main'][$gate])  > 0 ? $sessionData['main'][$gate] - round($sessionData['main'][$gate]):$sessionData['main'][$gate] - round($sessionData['main'][$gate])*-1;
            $selisih = $sessionData['main'][$gate] - round($sessionData['main'][$gate]);
            $sessionData['main'][$target] = round($sessionData['main'][$gate]);
            $sessionData['main']['selisih_round'] = number_format($selisih, 10, ".", "");
//            cekHitam($selisih);
            // cekHijau(PHP_FLOAT_MIN ($selisih));
        }

    }
    // cekPink($selisih);
    // // matiHEre(__LINE__." ".$selisih);
    // cekHere("cek ".$sessionData['main']['selisih_round']." + ".$selisih ."=". $selisih+$sessionData['main']['nilai_round']);
    if (sizeof($addBuilders) > 0) {
        if (sizeof($sessionData['items'])) {
            foreach ($sessionData['items'] as $iID => $iSpec) {
                foreach ($addBuilders as $key => $src) {
                    $sessionData['items'][$iID][$key] = makeValue($src, $sessionData['items'][$iID], $sessionData['items'][$iID], 0);
                }

            }
        }
    }
    if (sizeof($addMainBuilders) > 0) {
        foreach ($addMainBuilders as $key => $src) {
//            arrprint( $sessionData['main']);
            $sessionData['main'][$key] = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
//             cekHere("gerbang main, $key -> $srcValue || $srcValue_tmp, dengan rumus: $src");
//            cekHitam("$key = $src ---> $srcVal " . ":: line" . __LINE__);

        }
//        matiHEre("isi main builder");
    }

    if (count($additionalPostMainBuilder) > 0) {
        foreach ($additionalPostMainBuilder as $srcKey => $anuSpec) {
//            cekHitam($srcKey);
//            cekHitam($sessionData['main'][$srcKey]);
            if (isset($sessionData['main'][$srcKey])) {
//                matiHere(__LINE__);
                $srcValue = $sessionData['main'][$srcKey];
                if (isset($anuSpec[$srcValue]) && sizeof($anuSpec[$srcValue]) > 0) {
                    foreach ($anuSpec[$srcValue] as $k => $src) {
                        $srcVal = makeValue($src, $sessionData['main'], $sessionData['main'], 0);
                        $sessionData['main'][$k] = $srcVal;
                        // cekPink2("$k = $src ---> $srcVal");
                    }
                }
                else {
//                    cekhijau("$srcValue TIDAK memenuhi syarat");
                }
            }
        }
//        matiHere(__LINE__);
    }

//     matiHere("hoop value builder test");
    //region build target dari shopingcartpairElement
    if (count($pairElementMakers) > 0) {
        foreach ($pairElementMakers as $keyInjector => $viSpec) {
//            matiHere(__LINE__."|| ".$keyInjector);
            $defSrc = isset($sessionData["main"][$keyInjector]) ? $sessionData["main"][$keyInjector] : "";
            $defvalue = isset($sessionData["main"][$keyInjector]) ? $sessionData["main"][$keyInjector] : "";
            $paramSrc = $viSpec["params"];
            $srcGate = $viSpec["srcGate"];
            $pairSrcModelFields = $viSpec["pairSrcFields"];
            $srcGateTarget = $viSpec["targetGate"];
            if (isset($sessionData["main"][$keyInjector]) && $sessionData["main"][$keyInjector] == $viSpec["trigerValue"][$keyInjector]) {
                if (isset($sessionData[$srcGate]) && count($sessionData[$srcGate]) > 0) {
//                    matiHere(__LINE__);

//                    matiHere();
                    foreach ($sessionData[$srcGate] as $cKey => $vSpec) {
                        if (count($paramSrc) > 0) {

                            foreach ($paramSrc as $key => $srcKey) {
                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$key] = $vSpec[$srcKey];
                            }
                            if (isset($viSpec["pairModel"])) {
                                $pairModel = $viSpec["pairModel"];
                                $ci->load->model("Mdls/" . $pairModel);
                                $p = new $pairModel();
                                $p->setFilters(array());
                                if (isset($viSpec["pairModelKey"])) {
                                    $arrayKey = array();
                                    foreach ($viSpec["pairModelKey"] as $key => $src_key) {
                                        if (isset($vSpec[$src_key])) {
                                            $arrayKey[$key] = $vSpec[$src_key];
                                        }
                                    }
                                    if (count($arrayKey) > 0) {
                                        $ci->db->where($arrayKey);
                                    }
                                    else {
                                        matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi error line:" . __LINE__ . " FUNCTION" . __FUNCTION__);
                                    }
                                    $temp = $p->lookUpAll()->result();
//                                    arrprint($temp);
//                                    matiHere();
                                    if (count($temp) > 0) {
                                        $targetGate2 = null;
                                        if (isset($viSpec["targetGate2"])) {
                                            $targetGate2 = $viSpec["targetGate2"]["target"];
                                        }
                                        foreach ($temp as $temp_0) {
                                            foreach ($pairSrcModelFields as $keys => $srcKeys) {
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$keys] = isset($temp_0->$keys) ? $temp_0->$keys : "";
                                            }

                                            if ($targetGate2 != null) {
                                                $jml_serial = $temp_0->jml_serial;
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['jml_serial'] = $jml_serial;
                                                $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['scan_mode'] = $jml_serial > 0 ? "serial" : "simple";
                                                if ($jml_serial * 1 == 1) {
                                                    $d_kode = $temp_0->kode;
                                                    $sessionData['items2'][$temp_0->id][$d_kode] = array();
                                                }
                                                if (isset($viSpec["targetGate2"]["produkUnitPart"])) {
                                                    $arrCat = array();
                                                    $arrCode = array();
                                                    foreach ($viSpec["targetGate2"]["produkUnitPart"] as $cat => $catSpec) {
                                                        foreach ($catSpec as $dkey => $dval) {
                                                            if (isset($temp_0->$dval) && ($temp_0->$dval != NULL)) {
                                                                $sessionData['items2'][$temp_0->id][$temp_0->$dval] = array();
                                                                //--------------
                                                                if (!isset($arrCat[$cat])) {
                                                                    $arrCat[$cat] = 0;
                                                                }
                                                                $arrCat[$cat] += 1;
                                                                //--------------
                                                                if (!isset($arrCode[$temp_0->$dval])) {
                                                                    $arrCode[$temp_0->$dval] = 0;
                                                                }
                                                                $arrCode[$temp_0->$dval] += 1;
                                                                //--------------
                                                            }
                                                        }
                                                    }
                                                    $keterangan = "";
                                                    $static_keterangan = "";
                                                    if (!empty($arrCat)) {
                                                        foreach ($arrCat as $kcat => $vcat) {
                                                            $new_vcat = $vcat * $sessionData['items'][$id]["jml"];
                                                            if ($keterangan == "") {
                                                                $keterangan = " $new_vcat $kcat";
                                                            }
                                                            else {
                                                                $keterangan .= "<br> $new_vcat $kcat";
                                                            }
                                                            if ($static_keterangan == "") {
                                                                $static_keterangan = " $vcat $kcat";
                                                            }
                                                            else {
                                                                $static_keterangan .= "<br> $vcat $kcat";
                                                            }
                                                            $new_keyy = "qty_" . $kcat;
                                                            $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$new_keyy] = $vcat;
                                                        }
                                                    }
//                                                    arrprint($arrCode);
//                                                    matiHere();
                                                    if (!empty($arrCode)) {
                                                        foreach ($arrCode as $kcat => $vcat) {
                                                            $new_vcat = $vcat * $sessionData['items'][$id]["jml"];
                                                            $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]][$kcat] = $new_vcat;
                                                        }
                                                    }
                                                    $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['keterangan'] = $keterangan;
                                                    $sessionData[$srcGateTarget][$vSpec[$viSpec["index_key"]]]['static_keterangan'] = $static_keterangan;
                                                    //----------------------------------------

                                                }

                                            }
                                        }

                                    }
                                    else {
                                        matiHere("empty data on pair model " . __FUNCTION__);
                                    }
//                                    cekHitam($ci->db->last_query());
//                                    arrPrint($arrayKey);

                                }
                                else {
                                    matiHEre("pairmodel mmbutuhkan key. silahkan lengkapi " . __LINE__ . " FUNCTION :: " . __FUNCTION__);
                                }
//                                matiHere(__LINE__);
                            }

                        }
                    }
//                    arrPrint($sessionData[$srcGate]);
//                    arrPrint($arrayKey);
//                    matiHere();
                }
                else {
                    unset($sessionData[$srcGateTarget]);
                }
            }
            else {
                unset($sessionData[$srcGateTarget]);
//matiHEre("belum ada gerbangnya");
            }
        }
    }
//    matiHere(__LINE__);
    //endregion
    /*
        #6 [POPULATE]
        - populate items ke detail_values (kecuali yang termasuk exceptions)
    - populate main ke main_values (kecuali yang termasuk exceptions)
    */


    $populators = array( //==sumber2 yang dipopulasi
        "items" => "tableIn_detail_values",
        "items2" => "tableIn_detail_values2",
        "items2_sum" => "tableIn_detail_values2_sum",
        "rsltItems" => "tableIn_detail_values_rsltItems",
        "rsltItems2" => "tableIn_detail_values_rsltItems2",
    );
//    $populateTarget = "tableIn_detail_values";
    foreach ($populators as $src => $populateTarget) {
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $iID => $iCols) {
                if (sizeof($iCols) > 0) {
                    if (!isset($sessionData[$populateTarget][$iID])) {
                        $sessionData[$populateTarget][$iID] = array();
                    }
                    foreach ($iCols as $iKey => $iVal) {
                        if (is_numeric($iVal) && !in_array($iKey, $itemPopulateExceptions)) {
                            $sessionData[$populateTarget][$iID][$iKey] = $iVal;
                        }
                    }
                }
            }
        }
    }

    $populators = array( //==sumber2 yang dipopulasi
        "main",

    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($sessionData[$populateTarget])) {
            $sessionData[$populateTarget] = array();
        }
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $sessionData[$populateTarget][$key] = $val;
                }
            }
        }
    }


    //region tableIn_master
    //static
    if (isset($tableInConfig_static['master'])) {
        //static, main
        if (isset($tableInConfig_static['master']) & sizeof($tableInConfig_static['master']) > 0) {
            foreach ($tableInConfig_static['master'] as $fieldName => $staticValue) {
                $sessionData['tableIn_master'][$fieldName] = $staticValue;
            }
        }
    }
    //non-static
    if (isset($tableInConfig['master'])) {
        //non-static, main
        if (sizeof($tableInConfig['master']) > 0) {
//            //echo "======================MENGISI PARAMETER UNTUK MASUK TABEL UTAMA <br>";
            foreach ($tableInConfig['master'] as $fieldName => $src) {

                if (isset($sessionData['main'][$src])) {

                    $sessionData['tableIn_master'][$fieldName] = $sessionData['main'][$src];
                }
                else {
//                    //echo "nggak tau";
                }
//                //echo "<br>";
            }

        }

    }
    //endregion


    if (sizeof($fixedTableIn_values) > 0) {
        foreach ($fixedTableIn_values as $key => $src) {
            $sessionData['tableIn_master'][$key] = isset($sessionData['main'][$src]) ? $sessionData['main'][$src] : "";
        }
    }


//    //arrprint($tableInConfig);die();
    //table in details
    $copiers = array(
        'detail' => array(
            "src" => "items",
            "target" => "tableIn_detail",
        ),
        'sub_detail' => array(
            "src" => "items2_sum",
            "target" => "tableIn_sub_detail",
        ),
        'sub_detail_items' => array(
            "src" => "rsltItems3_sub",
            "target" => "tableIn_sub_detail_items",
        ),
        'detail2' => array(
            "src" => "items2",
            "target" => "tableIn_detail2",
        ),

        'detail2_sum' => array(
            "src" => "items2_sum",
            "target" => "tableIn_detail2_sum",
        ),

        'detail_rsltItems' => array(
            "src" => "rsltItems",
            "target" => "tableIn_detail_rsltItems",
        ),
        'detail_rsltItems2' => array(
            "src" => "rsltItems2",
            "target" => "tableIn_detail_rsltItems2",
        ),
    );


    foreach ($copiers as $conf => $cSpec) {
        if (isset($tableInConfig[$conf]) && sizeof($tableInConfig[$conf]) > 0) {
            if (isset($sessionData[$cSpec['src']]) && sizeof($sessionData[$cSpec['src']]) > 0) {
                foreach ($sessionData[$cSpec['src']] as $iID => $iSpec) {
                    foreach ($tableInConfig[$conf] as $key => $src) {
                        if (substr($src, 0, 1) == ".") {//==apa adanya, bukan variabel
                            $realCol = ltrim($src, ".");
                            $realValue = $realCol;
//                                //echo "$key apa adanya: $realCol<br>";
                        }
                        else {
                            $realValue = isset($iSpec[$src]) ? $iSpec[$src] : "";
                        }
                        if ($cSpec['target'] == "sub_detail") {

                        }
                        $sessionData[$cSpec['target']][$iID][$key] = $realValue;
                    }
                }
            }
        }
    }
    // matiHEre();


    $copiers = array(
        'detail' => 'items',
        'detail2' => 'items2',
        'sub_detail' => 'items2_sum',
        'detail2_sum' => 'items2_sum',
        'detail_rsltItems' => 'rsltItems',
        'detail_rsltItems2' => 'rsltItems2',
    );
    foreach ($copiers as $conf => $iterator) {
        if (isset($tableInConfig_static[$conf]) && sizeof($tableInConfig_static[$conf]) > 0) {
            if (isset($sessionData[$iterator]) && sizeof($sessionData[$iterator]) > 0) {
                foreach ($sessionData[$iterator] as $iID => $iSpec) {
                    foreach ($tableInConfig_static[$conf] as $key => $val) {
                        $sessionData['tableIn_' . $conf][$iID][$key] = $val;
                    }
                }
            }
        }
    }


    if (sizeof($valueInjectors) > 0) {
        ////cekmerah("ada value injector");
        foreach ($valueInjectors as $key => $val) {
            $value = isset($sessionData['main'][$val]) ? $sessionData['main'][$val] + 0 : 0;
            ////cekmerah("injecting $key with $value");
            echo "<script>";
            //echo "console.log('trying to inject $key with $value');";

            echo "if(top.document.getElementById('$key')){top.document.getElementById('$key').value='" . $value . "';}";
            echo "</script>";
        }
    }
    else {
        ////cekmerah("TAK ada value injector");
    }


    // arrPrintWebs($itemClonerTargets);

// arrPrint($itemClonerTargets);
//     matiHEre();
    if (sizeof($itemClonerTargets) > 0) {
        // if (sizeof($recapValueException) > 0) {
        //     foreach ($recapValueException as $valmakerKey) {
        //         if (isset($itemClonerTargets[$valmakerKey])) {
        //             unset($itemClonerTargets[$valmakerKey]);
        //         }
        //     }
        // }
        // arrPrint($itemClonerTargets);
        foreach ($itemClonerTargets as $target => $src) {

            if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                foreach ($sessionData[$target] as $iID => $iSpec) {
                    foreach ($itemCloners as $key) {
                        if (isset($sessionData[$src][$key])) {

                            $sessionData[$target][$iID][$key] = $sessionData[$src][$key];
                        }
                    }
                }
            }
        }
    }
//    arrprint($sessionData["items4_sum"]);
//    matiHere();
    if (count($itemClonerTarget_sub) > 0) {
        foreach ($itemClonerTarget_sub as $target => $src) {
            if (isset($sessionData[$target]) && sizeof($sessionData[$target]) > 0) {
                foreach ($sessionData[$target] as $iID => $iSpec) {
                    foreach ($iSpec as $iiID => $iiSpec) {
//                        cekHitam($iiID);
                        foreach ($itemCloners as $key) {
                            if (isset($sessionData[$src][$key])) {
//                                cekHitam($key);
                                $sessionData[$target][$iID][$iiID][$key] = $sessionData[$src][$key];
                            }
                            else {
//                                cekMerah(__LINE__.":: ".$key);
                            }
                        }
                    }

                }
            }
        }
    }
//    arrPrintWebs($sessionData['items6_sum']);
//    matiHEre(__LINE__);
// arrprint($pairMakers);
    if (sizeof($pairMakers) > 0) {
        foreach ($pairMakers as $prKey => $prSpec) {
//arrPrint($prSpec);
// matiHEre();
            $ci->load->helper("Pairs/" . $prSpec['helperName']);
            $gateParam = isset($prSpec['gate']) ? $prSpec['gate'] : "items";
            $result = $prSpec['functionName']($tr, $intoStep, $prSpec['params'], $gateParam);
//            cekMerah($ci->db->last_query());
            $sessionData['pairs'][$prKey] = $result;
// arrPrint( $prSpec['helperName']);
// matiHEre();
        }
        // arrPrint($pairMakers);
// mati_disini($cCode);
        if (sizeof($pairInjectors) > 0) {
            foreach ($pairInjectors as $keyInjector => $viSpec) {
                if (array_key_exists($keyInjector, $pairMakers)) {
                    foreach ($viSpec as $gateName => $vgSpec) {
                        if (isset($sessionData[$gateName])) {
                            foreach ($sessionData[$gateName] as $cKey => $vSpec) {
                                if (array_key_exists($cKey, $sessionData['pairs'][$keyInjector])) {
                                    if (is_array($sessionData['pairs'][$keyInjector][$cKey])) {
                                        $urut = $sessionData['pairs'][$keyInjector][$cKey]['urut'];
                                        $val = $sessionData['pairs'][$keyInjector][$cKey]['value'];
                                        $sessionData[$gateName][$cKey][$vgSpec['targetColumn'] . "_" . $urut] = $val;
                                    }
                                    else {
                                        $sessionData[$gateName][$cKey][$vgSpec['targetColumn']] = $sessionData['pairs'][$keyInjector][$cKey];
                                    }
                                }
                                else {
//                                    ceklIme("tidak ada pair " . $keyInjector);
                                    $sessionData[$gateName][$cKey][$vgSpec['targetColumn']] = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
        // arrPrint($sessionData['items2_sum']);
        // mati_disini();
    }

    // arrPrint($pairMakers);
    // matiHEre();
    if (($fromStep = 0 && $intoStep == 0) || ($fromStep = 1 && $intoStep == 1)) {//==ini pembuatan baru
//            ////cekMerah("build values saat buat baru");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][1]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][1]['target'],
        );
        if (isset($configUiJenis['steps'][2])) {
            $addFieldValues['next_step_code'] = $configUiJenis['steps'][2]['target'];
            $addFieldValues['next_group_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addFieldValues['step_number'] = 1;
            $addFieldValues['step_current'] = 1;

            $addSubFieldValues['next_substep_code'] = $configUiJenis['steps'][2]['target'];
            $addSubFieldValues['next_subgroup_code'] = $configUiJenis['steps'][2]['userGroup'];
            $addSubFieldValues['sub_step_number'] = 1;
            $addSubFieldValues['sub_step_current'] = 1;

        }
        else {
            $addFieldValues['next_step_code'] = "";
            $addFieldValues['next_group_code'] = "";
            $addFieldValues['step_number'] = $intoStep;
            $addFieldValues['step_current'] = 0;

            $addSubFieldValues['next_substep_code'] = "";
            $addSubFieldValues['next_subgroup_code'] = "";
            $addSubFieldValues['sub_step_number'] = $intoStep;
            $addSubFieldValues['sub_step_current'] = 0;
        }
    }
    else {//==ini manipulasi saat edit
//            ////cekMerah("build values saat EDIT");
        $addFieldValues = array(
            "jenis" => $configUiJenis['steps'][$intoStep]['target'],
            "transaksi_jenis" => $configUiJenis['steps'][$intoStep]['target'],
        );
        $addFieldValues['next_step_code'] = "";
        $addFieldValues['next_group_code'] = "";
        $addFieldValues['step_number'] = $intoStep;
        $addFieldValues['step_current'] = 0;

        $addSubFieldValues['next_substep_code'] = "";
        $addSubFieldValues['next_subgroup_code'] = "";
        $addSubFieldValues['sub_step_number'] = $intoStep;
        $addSubFieldValues['sub_step_current'] = 0;
    }


    foreach ($addFieldValues as $fName => $value) {
        $sessionData['main'][$fName] = $value;
        $sessionData['tableIn_master'][$fName] = $value;
    }


    if (isset($addSubFieldValues) && sizeof($addSubFieldValues) > 0) {

        if (sizeof($sessionData['items']) > 0 && sizeof($sessionData['tableIn_detail']) > 0) {
            foreach ($sessionData['items'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['items'][$iID][$fName] = $value;
                }
            }
            foreach ($sessionData['tableIn_detail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['tableIn_detail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($sessionData['main']['step_number']) ? $sessionData['main']['step_number'] : 1,
                    //                    "valid_qty"       => $sessionData['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $sessionData['tableIn_detail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($sessionData['items'][$iID][$src])) {
                            $sessionData['tableIn_detail'][$iID][$target] = $sessionData['items'][$iID][$src];
                        }
                    }
                }
            }

        }

        if (isset($sessionData['items2_sum']) && sizeof($sessionData['items2_sum']) > 0 && sizeof($sessionData['tableIn_detail_subdetail']) > 0) {
            foreach ($sessionData['items2_sum'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['items2_sum'][$iID][$fName] = $value;
                }
            }
            foreach ($sessionData['tableIn_detail_subdetail'] as $iID => $iSpec) {
                foreach ($addSubFieldValues as $fName => $value) {
                    $sessionData['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                $addFields = array(
                    "sub_step_number" => isset($sessionData['main']['step_number']) ? $sessionData['main']['step_number'] : 1,
                    //                    "valid_qty"       => $sessionData['items'][$iID]['jml'],
                );
                foreach ($addFields as $fName => $value) {
                    $sessionData['tableIn_detail_subdetail'][$iID][$fName] = $value;
                }
                if (sizeof($fixedTableIn_subValues) > 0) {
                    foreach ($fixedTableIn_subValues as $target => $src) {
                        if (isset($sessionData['items2_sum'][$iID][$src])) {
                            $sessionData['tableIn_detail_subdetail'][$iID][$target] = $sessionData['items2_sum'][$iID][$src];
                        }
                    }
                }
            }

        }
    }


//    if(isset($_GET['confirm']) && $_GET['confirm']=="1"){
//
//    }else{
//
//        echo "<script>\n";
//        echo "if(top.document.getElementById('ck')){top.document.getElementById('ck').checked=false;}\n";
//        echo "if(top.document.getElementById('btnProcess')){top.document.getElementById('btnProcess').disabled=true;}\n";
//        echo "</script>\n";
//    }


    // khusus setoran......................................................................
    $ci->load->model("MdlTransaksi");

    $additionalSource = isset($configCoreJenis['additionalSource']) ? $configCoreJenis['additionalSource'] : false;
    $additionalItemSource = isset($configCoreJenis['additionalItemSource']) ? $configCoreJenis['additionalItemSource'] : array();
    $additionalItemResult = isset($configCoreJenis['additionalItemResult']) ? $configCoreJenis['additionalItemResult'] : array();
    $additionalItemSourceKey = isset($configCoreJenis['additionalItemSourceKey']) ? $configCoreJenis['additionalItemSourceKey'] : array();
    $additionalPembulatan = isset($configCoreJenis['valueInjectorBulat']) ? $configCoreJenis['valueInjectorBulat'] : array();
    $additionalPembulatanPajak = isset($configCoreJenis['injectorPajak']) ? $configCoreJenis['injectorPajak'] : array();
    $pairPembulatanPajak = isset($configCoreJenis['pairPajak']) ? $configCoreJenis['pairPajak'] : array();
    $additionalPembulatanPajakReseller = isset($configCoreJenis['injectorPajakReseller']) ? $configCoreJenis['injectorPajakReseller'] : array();
    $pairPembulatanPajakReseller = isset($configCoreJenis['pairPajakReseller']) ? $configCoreJenis['pairPajakReseller'] : array();
    if ($additionalSource == true) {
        if (sizeof($additionalItemSource)) {
            //cekUngu("cetak ITEMS AWAL...");
            //arrPrint($sessionData['items']);
            foreach ($sessionData['items'] as $id => $iSpec) {

                $trr = new MdlTransaksi();
                $trr->setFilters(array());
                // $trr->addFilter("param='main'");
                $trr->addFilter("transaksi_id='$id'");
                $tmpR = $trr->lookupDataRegistries()->result();
                // arrPrint($tmpR);
                $main = blobDecode($tmpR[0]->main);

                //cekUngu(":: MAIN ID $id");
//                arrPrint($main);
//                mati_disini();
                //cekUngu(":: ITEMS ID $id");
                //arrPrint($iSpec);

                foreach ($additionalItemSource as $key => $val) {
//                    if (!isset($sessionData['items'][$id][$key])) {

                    $new_key = (sizeof($additionalItemResult) > 0 && (isset($additionalItemResult[$key]))) ? $additionalItemResult[$key] : $key;
                    if (!isset($sessionData['items'][$id][$new_key])) {

                        $sessionData['items'][$id][$new_key] = 0;
                    }

                    if (sizeof($additionalItemSourceKey) > 0) {
                        $persenValue = ($sessionData['items'][$id][$additionalItemSourceKey['top']] / $main[$additionalItemSourceKey['bottom']]) * 100;
                    }
                    else {
                        $persenValue = 0;
                    }
                    $key_result = makeValue($val, $main, $main, 0);
                    $sessionData['items'][$id]['persenValue'] = $persenValue;
                    $sessionData['items'][$id][$new_key] = ($persenValue / 100) * $key_result;


//                    }
                }
            }

//                $persenValue = ($sessionData['items'][$id]['nilai_bayar']/$sessionData['items'][$id]['harga_nett2'])*100;
//                $sessionData['items'][$id]['persenValue'] = $persenValue;
//                foreach ($additionalItemSource as $key => $val){
//
//                    $sessionData['items'][$id]['source_'.$key] = ($sessionData['items'][$id][$key]*$persenValue)/100;
//                }
        }
//        mati_disini();
    }

    if (sizeof($alwaysUpdaters) > 0) {
        foreach ($alwaysUpdaters as $key => $src) {
            $sessionData['main'][$key] = isset($ci->session->login[$src]) ? $ci->session->login[$src] : "";
        }
    }


    if (sizeof($additionalPembulatan) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatan['source'];
        $varBulat = makeDppBulat($sessionData['main'][$source]);
        foreach ($additionalPembulatan['injectTo'] as $key => $fields) {
            $srcValue_tmp = $varBulat[$key];
//            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
//                foreach ($extFormula['master'] as $mFormula => $gate) {
//                    if (in_array($fields, $gate)) {
//                        $srcValue = $mFormula($srcValue_tmp);
//                        cekPink2("$fields :: asli -> $srcValue_tmp :: bulat -> $srcValue");
//                    }
//                    else {
//                        $srcValue = $srcValue_tmp;
//                    }
//                }
//            }
//            else {
//                $srcValue = $srcValue_tmp;
//            }
            if (isset($extFormula['master']) && sizeof($extFormula['master']) > 0) {
                if (array_key_exists($fields, $extFormula['master'])) {
                    $mFormula = $extFormula['master'][$fields];
                    $srcValue = $mFormula($srcValue_tmp);
                }
                else {
                    $srcValue = $srcValue_tmp;
                }
            }
            else {
                $srcValue = $srcValue_tmp;
            }
            $sessionData['main'][$fields] = $srcValue;
        }
    }
    if (sizeof($additionalPembulatanPajak) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajak['source'];
        $varBulat = pembulatan_pajak($sessionData['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajak as $key => $fields) {
            // cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $sessionData['main'][$key] = $varBulat[$fields];
        }
    }
    if (sizeof($additionalPembulatanPajakReseller) > 0) {
        $ci->load->helper("he_angka");
        $source = $additionalPembulatanPajakReseller['source'];
        $varBulat = pembulatan_pajak($sessionData['main'][$source], $ppnFactor);
        foreach ($pairPembulatanPajakReseller as $key => $fields) {
//            cekMErah("inject :: " . $fields . "--->" . $varBulat[$fields]);
            $sessionData['main'][$key] = $varBulat[$fields];
        }
    }


    //region valueReplaceCalculate
    $valueReplaceCalculate = isset($configCoreJenis['valueReplaceCalculate']) ? $configCoreJenis['valueReplaceCalculate'] : array();
    if (sizeof($valueReplaceCalculate) > 0) {
        foreach ($valueReplaceCalculate as $gate) {
            $curentVal = $sessionData['main'][$gate];
            if ($curentVal > 0) {

            }
            else {
                $sessionData['main'][$gate] = 0;
                $sessionData['tableIn_master_values'][$gate] = 0;
            }
//            cekHitam($gate);
        }
    }


    //endregion


    //------------------------------------------------------------------
    $recapItemBuilder = isset($configCoreJenis['recapItemBuilder']) ? $configCoreJenis['recapItemBuilder'] : array();
//    arrprint($recapItemBuilder);
//    matiHere();
    if (sizeof($recapItemBuilder) > 0) {
        $gateNameSource = $recapItemBuilder['gateNameSource'];
        $gateNameTarget = $recapItemBuilder['gateNameTarget'];
        $key = $recapItemBuilder['key'];
        $vals = $recapItemBuilder['val'];
        //------ hapus/reset items2_sum
        if (isset($sessionData['items4_sum'])) {
            $sessionData['items4_sum'] = null;
            unset($sessionData['items4_sum']);
        }

        if (isset($sessionData['items']) && sizeof($sessionData['items']) > 0) {
            foreach ($sessionData['items'] as $ii => $iSpec) {
                //------ build ulang items2_sum
                if (!isset($sessionData['items4_sum'][$iSpec[$key]])) {
                    $iSpec['id'] = $iSpec[$key];
                    $sessionData['items4_sum'][$iSpec[$key]] = $iSpec;

                    foreach ($vals as $val) {
                        $sessionData['items4_sum'][$iSpec[$key]][$val] = 0;
                    }
                }
                //------ build ulang items2_sum
                foreach ($vals as $val) {
                    $sessionData['items4_sum'][$iSpec[$key]][$val] += $iSpec[$val];
                }
            }
        }
    }

//    arrPrintWebs($sessionData['main']);
//    mati_disini("value builder helper");


    // rekalkulasi TableInMasterValues di akhir value builder....
    $populators = array( //==sumber2 yang dipopulasi
        "main",
    );
    $populateTarget = "tableIn_master_values";
    foreach ($populators as $src) {
        if (!isset($sessionData[$populateTarget])) {
            $sessionData[$populateTarget] = array();
        }
        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
            foreach ($sessionData[$src] as $key => $val) {
                if (is_numeric($val) && !in_array($key, $masterPopulateExceptions)) {
                    $sessionData[$populateTarget][$key] = $val;
                }
            }
        }
    }
    // matiHEre();


    //------------------------------------------------------------------
    switch ($tr) {
        case "583":
        case "585":
        case "5833":
        case "5855":
        case "5822":
        case "9822":
        case "19822":
            $gatePisah = pisahBarangJasa($sessionData);
            $sessionData["items4"] = $gatePisah["items4"];
            $sessionData["items4_sum"] = $gatePisah["items4_sum"];
            $sessionData["items9_sum"] = $gatePisah["items9_sum"];
            $sessionData["items10_sum"] = $gatePisah["items10_sum"];
//arrPrintHijau($gatePisah);
            break;

        case "6698":
        case "466":
        case "1466":
            $gatePisah = pisahProdukSupplies($sessionData);
            $sessionData["items9_sum"] = $gatePisah["items9_sum"];
            $sessionData["items10_sum"] = $gatePisah["items10_sum"];
            $recapMakersPisah = array( //==sumber detail ke target main
                "items9_sum" => "main",
                "items10_sum" => "main",
            );
            foreach ($recapMakersPisah as $src => $target) {
                if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
                    $iCtr = 0;
                    foreach ($sessionData[$src] as $iID => $iCols) {
                        $iCtr++;
                        //===reset dulu
                        if ($iCtr == 1) {
                            if (sizeof($iCols) > 0) {
                                foreach ($iCols as $iKey => $iVal) {
                                    if (!isset($sessionData['main_elements'][$iKey])) {
                                        if (!in_array($iKey, $itemRecapExceptions)) {
                                            if (substr($iKey, 0, 4) != "sub_") {
                                                $sessionData[$target][$iKey] = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (sizeof($iCols) > 0) {
                            foreach ($iCols as $iKey => $iVal) {
                                if (!isset($sessionData['main_elements'][$iKey])) {
                                    if (!in_array($iKey, $itemRecapExceptions)) {
                                        if (is_numeric($iVal)) {
                                            if (substr($iKey, 0, 4) != "sub_") {
                                                $sessionData[$src][$iID]["sub_" . $iKey] = ($sessionData[$src][$iID]["jml"] * $sessionData[$src][$iID][$iKey]);
                                                $sessionData[$target][$iKey] += ($sessionData[$src][$iID]["jml"] * $iVal);
                                            }
                                        }
                                    }
                                    else {

                                    }
                                }
                                else {

                                }
                            }
                        }
                    }
                }
            }

            break;

        case "9911":
            switch ($jenisTr_references) {
                case "466":
                    $gatePisah = pisahProdukSupplies($sessionData);
                    $sessionData["items9_sum"] = $gatePisah["items9_sum"];
                    $sessionData["items10_sum"] = $gatePisah["items10_sum"];
                    $recapMakersPisah = array( //==sumber detail ke target main
                        "items9_sum" => "main",
                        "items10_sum" => "main",
                    );
                    foreach ($recapMakersPisah as $src => $target) {
                        if (isset($sessionData[$src]) && sizeof($sessionData[$src]) > 0) {
                            $iCtr = 0;
                            foreach ($sessionData[$src] as $iID => $iCols) {
                                $iCtr++;
                                //===reset dulu
                                if ($iCtr == 1) {
                                    if (sizeof($iCols) > 0) {
                                        foreach ($iCols as $iKey => $iVal) {
                                            if (!isset($sessionData['main_elements'][$iKey])) {
                                                if (!in_array($iKey, $itemRecapExceptions)) {
                                                    if (substr($iKey, 0, 4) != "sub_") {
                                                        $sessionData[$target][$iKey] = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if (sizeof($iCols) > 0) {
                                    foreach ($iCols as $iKey => $iVal) {
                                        if (!isset($sessionData['main_elements'][$iKey])) {
                                            if (!in_array($iKey, $itemRecapExceptions)) {
                                                if (is_numeric($iVal)) {
                                                    if (substr($iKey, 0, 4) != "sub_") {
                                                        $sessionData[$src][$iID]["sub_" . $iKey] = ($sessionData[$src][$iID]["jml"] * $sessionData[$src][$iID][$iKey]);
                                                        $sessionData[$target][$iKey] += ($sessionData[$src][$iID]["jml"] * $iVal);
                                                    }
                                                }
                                            }
                                            else {

                                            }
                                        }
                                        else {

                                        }
                                    }
                                }
                            }
                        }
                    }

                    break;
            }
            break;

    }

    //------------------------------------------------------------------
    $rebuilderCore = isset($configCoreJenis['rebuilderCore']) ? $configCoreJenis['rebuilderCore'] : array();
    $rebuilderCoreKey = isset($configCoreJenis['rebuilderCoreKey']) ? $configCoreJenis['rebuilderCoreKey'] : NULL;
    if (sizeof($rebuilderCore) > 0) {
        if ($rebuilderCoreKey != NULL) {
            $rebuilderCoreKeyMain = isset($sessionData['main'][$rebuilderCoreKey]) ? $sessionData['main'][$rebuilderCoreKey] : 0;
            if (isset($rebuilderCore[$rebuilderCoreKeyMain]) && (sizeof($rebuilderCore[$rebuilderCoreKeyMain]) > 0)) {
                foreach ($rebuilderCore[$rebuilderCoreKeyMain] as $key => $val) {
                    $sessionData['main'][$key] = makeValue($val, $sessionData['main'], $sessionData['main'], 0);
                }
            }
        }
    }

    // region bersih-bersih gerbang dengan key kosong
    $arrGateBersihMain = array(
        "main",
        "tableIn_master_values",
        "tableIn_master",
    );
    foreach ($arrGateBersihMain as $srcGate) {
        if (isset($sessionData[$srcGate]) && (sizeof($sessionData[$srcGate]) > 0)) {
            foreach ($sessionData[$srcGate] as $key => $val) {
                if ($key == NULL) {
                    unset($sessionData[$srcGate][$key]);
                }
            }
        }
    }

    $arrGateBersihDetail = array(
        "items",
        "items2_sum",
        "items3_sum",
        "items4_sum",
        "items5_sum",
        "items6_sum",
        "items7_sum",
        "items8_sum",
        "items9_sum",
        "items10_sum",
    );
    foreach ($arrGateBersihDetail as $srcGate) {
        if (isset($sessionData[$srcGate]) && (sizeof($sessionData[$srcGate]) > 0)) {
            foreach ($sessionData[$srcGate] as $ii => $spec) {
                foreach ($spec as $key => $val) {
                    if ($key == NULL) {
                        unset($sessionData[$srcGate][$ii][$key]);
                    }
                }
            }
        }
    }

    // endregion bersih-bersih gerbang dengan key kosong


    return $sessionData;
}

function fillValuesOpnameRecalculate($tr, $currentStepNum, $stepNumber, $configCoreJenis, $configUiJenis, $configValuesJenis, $data)
{
//    arrPrint($data);
    $cCode = cCodeBuilderMisc($tr);
    $itemNumLabels = isset($configUiJenis['shoppingCartNumFields'][$stepNumber]) ? $configUiJenis['shoppingCartNumFields'][$stepNumber] : array();
    $subAmountConfig = isset($configUiJenis['shoppingCartAmountValue'][$stepNumber]) ? $configUiJenis['shoppingCartAmountValue'][$stepNumber] : null;

//    arrprint($itemNumLabels);
//        matiHere();
    if (count($data) > 0) {
        foreach ($data as $id => $data_0) {
//    if($key=="qty_opname"){
            $val = $data_0["qty_opname"];
            $stok = $data_0['stok'];
//            $_SESSION[$cCode]['items'][$id]['qty_opname'] = $_GET['qty_opname'];
//            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($data_0['jml'] * ($data_0['harga'] + $_SESSION[$cCode]['items'][$id]['ppn']));

            $selisih = $val - $stok;
            if ($selisih > 0) {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = $selisih;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                $_SESSION[$cCode]['items'][$id]['debet'] = $selisih * $data_0['harga'];
                $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
            }
            elseif ($selisih < 0) {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = ($selisih * -1);
                $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['kredit'] = ($selisih * -1) * $data_0['harga'];
            }
            else {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
                $_SESSION[$cCode]['items'][$id]['debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['kredit'] = 0;
            }
            $_SESSION[$cCode]['items'][$id]['qty_selisih'] = $selisih;

            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }

            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }

            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);
        }


    }
    else {
//            echo(lgShowAlert("NOT replacing $key with $val"));
    }
//    matiHEre(__LINE__ . " underdebuger");
}

?>
