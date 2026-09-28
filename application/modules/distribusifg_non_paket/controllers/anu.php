<?php

class anu extends Modul_Controller
{

    public function terimaDistribusi($jenisTr, $no, $stepNum, $stepNumCurrent)
    {
        $transaksiID_reference = $no = $masterID;
//        $stepNum = 2;
//        $stepNumCurrent = 1;
        $nextStepNum = $stepNum + 1;

        $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $stepNowParameter = array();
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
        $tr->addFilter("step_number='" . $stepNumCurrent . "'");
        $tr->addFilterJoin("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined();
        if (sizeof($tmpTr) > 0) {
            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            $validItemSends = array();
            $validItemReqCancels = array();
            $validItemCancels = array();
            $validItemPreCancels = array();
            $validItemSents = array();
            foreach ($tmpTr as $row) {
                if ($row->valid_qty > 0) {
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    if (!isset($validItemSends[$row->produk_id])) {
                        $validItemSends[$row->produk_id] = 0;
                    }
                    if (!isset($validItemCancels[$row->produk_id])) {
                        $validItemCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemReqCancels[$row->produk_id])) {
                        $validItemReqCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPackeds[$row->produk_id])) {
                        $validItemPackeds[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPreCancels[$row->produk_id])) {
                        $validItemPreCancels[$row->produk_id] = 0;
                    }

                    $validItems[$row->produk_id] += isset($row->valid_qty) ? $row->valid_qty : 0;
                    $validItemSends[$row->produk_id] += isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0;
                    $validItemCancels[$row->produk_id] += isset($row->cancel_qty) ? $row->cancel_qty : 0;
                    $validItemReqCancels[$row->produk_id] += isset($row->req_cancel_qty) ? $row->req_cancel_qty : 0;
                    $validItemPreCancels[$row->produk_id] += isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0;
                    $validItemPackeds[$row->produk_id] += isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0;

                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id_detail] = array(
                        "id" => $row->id_detail,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
                        "valid_qty" => $row->valid_qty,
                        "transaksi_id" => $row->transaksi_id,
                        "packed_qty" => isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0,
                        "sent_qty" => isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0,
                        "req_cancel_qty" => isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0,
                        "cancel_qty" => $row->cancel_qty,
                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->valid_qty),
                    );
                }
            }
            $this->jenisTr = $tmpTr[0]->jenis_master;
//                    $masterID = $_SESSION[$cCode]['main']['masterID'];
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $trID = $tmpTr[0]->transaksi_id;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = null;
                unset($_SESSION[$cCode]);
            }
            //region session init
            if (!isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($_SESSION[$cCode]['main'])) {
                $_SESSION[$cCode]['main'] = array();
            }
            if (!isset($_SESSION[$cCode]['items'])) {
                $_SESSION[$cCode]['items'] = array();
            }
            //endregion
            $_SESSION[$cCode]['extractedItems'] = $extractedItems;


            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");

            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");


            $jenisTrTarget = isset($configUiMasterModulJenis["steps"][$stepNum]["target"]) ? $configUiMasterModulJenis["steps"][$stepNum]["target"] : NULL;
            $detailValuesConfig = isset($configCoreMasterModulJenis['tableIn']['detailValues']) ? $configCoreMasterModulJenis['tableIn']['detailValues'] : array();
            $additionalData = isset($configUiMasterModulJenis["addDetailData"][$stepNum]) ? $configUiMasterModulJenis["addDetailData"][$stepNum] : array();


            $totalSteps = sizeof($configUiMasterModulJenis['steps']);
            //==references, previous entry
            $prevProp = array(
                "id" => $tmpTr[0]->transaksi_id,
                "jenis" => $tmpTr[0]->jenis,
                "nomer" => $tmpTr[0]->nomer,
            );
            //------
            $stepNowParameter = array(
                "next_step_code" => $tmpTr[0]->next_step_code,
                "next_step_label" => $tmpTr[0]->next_step_label,
                "next_group_code" => $tmpTr[0]->next_group_code,
                "next_step_num" => $tmpTr[0]->next_step_num,
                "step_current" => $tmpTr[0]->step_current,
            );
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }

            $main = array();
            $items = array();
            $prevIDs = array();
            $prevNos = array();
            foreach ($tmpTr as $row) {
                $items[$row->produk_id] = array(
                    "id" => $row->produk_id,
                    "nama" => $row->produk_nama,
                    "jml" => $row->produk_ord_jml,
                    "harga" => $row->produk_ord_hrg,
                    "valid_qty" => $row->valid_qty,
                    "transaksi_id" => $row->transaksi_id,
                    "nomer" => $row->nomer,
                );
                if ($row->valid_qty > 0) {
                    cekHitam("ok lanjut");
                }
                else {
                    if (isset($_SESSION[$cCode]['items'][$row->produk_id])) {
                        matiHere("Followed up already. Please close and refresh your browser " . $row->produk_nama . " " . $row->produk_id);//kalo session active ya harus dimatiin biar gak dobel
                    }
                }
                if (!in_array($row->transaksi_id, $prevIDs)) {
                    $prevIDs[] = $row->transaksi_id;
                }
                if (!in_array($row->nomer, $prevNos)) {
                    $prevNos[] = $row->nomer;
                }
                if (sizeof($detailValuesConfig) > 0) {
                    echo "detail values ada..<br>";
                    foreach ($detailValuesConfig as $key => $src) {
                        echo "$key akan ambil nilai dari $src<br>";
                        echo "<script>top.writeProgress('$key akan ambil nilai dari $src');</script>";
                        //                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
                            //                            $tmp[$key] = formatField($key, $detailValues[$row->produk_id][$key]);
                            $items[$row->produk_id][$key] = $detailValues[$row->produk_id][$key];
                        }
                        else {
                            if (isset($row->$key)) {
                                //                                $tmp[$key] = formatField($key, $row->$key);
                                $items[$row->produk_id][$key] = $row->$key;
                            }
                        }
                        echo "dan sekarang nilainya: " . $items[$row->produk_id][$key] . "<br>";
                        echo "<script>top.writeProgress('dan sekarang nilainya: " . $items[$row->produk_id][$key] . "');</script>";
                    }
                }
            }

            //region take from registries
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();
            cekKuning($this->db->last_query());
            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        switch ($key_reg) {
                            case "main"://
                                $main = $main + unserialize(base64_decode($val_reg));
                                break;
                            case "items"://
                                $items = $items + unserialize(base64_decode($val_reg));
                                break;
                            case "items2"://
                                $items2 = $items2 + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems"://
                                $rsltItems = $rsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems2"://
                                $rsltItems2 = $rsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "items2_sum"://
                                $items2_sum = $items2_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items3"://
                                $items3 = $items3 + unserialize(base64_decode($val_reg));
                                break;
                            case "items3_sum"://
                                $items3_sum = $items3_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items4_sum"://
                                $items4_sum = $items4_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master"://
                                $masterTableInParams = $masterTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail"://
                                $childTableInParams = $childTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems"://
                                $childTableInParamsRsltItems = $childTableInParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems2"://
                                $childTableInParamsRsltItems2 = $childTableInParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master_values"://
                                $masterTableInValueParams = $masterTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values"://
                                $childTableInValueParams = $childTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems"://
                                $childTableInValueParamsRsltItems = $childTableInValueParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems2"://
                                $childTableInValueParamsRsltItems2 = $childTableInValueParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_values"://
                                $masterAddValues = $masterAddValues + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_fields"://
                                $masterAddFields = $masterAddFields + unserialize(base64_decode($val_reg));
                                break;
                            case "main_elements"://
                                $mainElements = unserialize(base64_decode($val_reg));
                                break;
                            case "main_inputs"://
                                $mainInputs = unserialize(base64_decode($val_reg));
                                break;
                            case "items_komposisi"://
                                $itemsKomposisi = unserialize(base64_decode($val_reg));
                                break;
                        }
                    }
                }

            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            //region session-swapper
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4_sum" => $items4_sum,
                "items_child" => $itemChildData,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,


                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                "main_add_fields" => $masterAddFields,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $_SESSION[$cCode][$targetVar] = $src;

            }
            //endregion


            //region pembulatan replacer disini
            $injectBulat = isset($configCoreMasterModulJenis['valuePembulatan'][$stepNum]) ? $configCoreMasterModulJenis['valuePembulatan'][$stepNum] : array();
            if (sizeof($injectBulat) > 0) {
                echo "<script>top.writeProgress('PEMBULATAN', 'HEAD');</script>";
                //            arrPrint($injectBulat);
                $selectedSource = $injectBulat['source'];
                $injectSource = makeDppBulat($_SESSION[$cCode]['main'][$selectedSource]);
                foreach ($injectBulat['replacer'] as $k => $fields) {
                    $_SESSION[$cCode]['main'][$fields] = $injectSource[$k];
                    echo "<script>top.writeProgress('PEMBULATAN ($fields)');</script>";
                }

            }
            //endregion

            cekMerah(":: MEMULAI PRE-PROCC ITEMS...");
            $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");

            //region pre-processors (item)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";

                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            //                        $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

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
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                            }

                            if (sizeof($subParams) > 0) {

                                $tmpOutParams[$cCtr][] = $subParams;
                            }


                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                            echo "sub preproc #$it: $comName, sending values <br>";

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
                                $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();
                                cekHitam(":: PRE-PROCC -> GOTNAME, ITERATING...");
                                arrprint($gotParams);
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $paramSpec) {

                                        if (!isset($_SESSION[$cCode][$gateName])) {
                                            $_SESSION[$cCode][$gateName] = array();
                                            //                                    cekhijau("building the session: $gateName");
                                        }
                                        else {
                                            //                                    cekhijau("NOT building the session: $gateName");
                                        }

                                        foreach ($paramSpec as $id => $gSpec) {
                                            //                                        $id = $gSpec['id'];
                                            if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                $_SESSION[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //==inject gotParams to child gate
                                            if ($gateName == $srcGateName) {
                                                if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                    //                                        die();
                                                }
                                            }
                                        }
                                        //                                    arrPrint($_SESSION[$cCode][$gateName]);die();
                                    }
                                }

                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }

                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-processors (master)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                        echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

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
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }


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
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();

                            cekbiru("gotparams dari $comName");
                            arrprint($gotParams);

                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {

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
                                                    cekbiru("injecting param $key with $val");
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
                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-proc value injector items2 items2_sum dari gerbang main
            $injectValues = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum] : array();
            if (sizeof($injectValues) > 0) {
                $iterator = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]['master']) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        //                    echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

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
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }


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
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                //                            cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if ($gateName == "main") {
                                        foreach ($gSpec as $key => $val) {
                                            $_SESSION[$cCode]['main'][$key] = $val;
                                        }
                                    }
                                    if ($gateName == "items2") {
                                        foreach ($_SESSION[$cCode]['items2'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                foreach ($tmpSes as $y => $sesData) {
                                                    if (array_key_exists($key, $sesData)) {
                                                        $_SESSION[$cCode]['items2'][$k][$y][$key] = $val;
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    if ($gateName == "items2_sum") {
                                        foreach ($_SESSION[$cCode]['items2_sum'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['items2_sum'][$k][$key] = $val;
                                            }
                                        }

                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);

            }
            //endregion

            $this->load->library("Validator");
            $va = new Validator();
            $va->setConfigUiJenis($configUiMasterModulJenis);
            $va->setCCode($this->cCode);
            $va->midValidate($stepNum);
            $va->unionValidate();

            //region update step2an
            if (isset($configUiMasterModulJenis['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
                echo "authorizing to next step..<br>";
                $nextProp = array(
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
                );
            }
            else {//==ini step terakhir, tulis komponen jika ada
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion


            //==tulis signature
            $dwsign = $tr->writeSignature($masterID, array(
                "nomer" => $tmpNomorNota,
                "step_number" => $stepNum,
                "step_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['target'],
                "step_name" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'],
                "group_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'] . " oleh " . $this->session->login['nama'],
                "transaksi_id" => $masterID,
            )) or die("Failed to write signature");
            $mongoList['sign'][] = $dwsign;
            //cekKuning($this->db->last_query());

            //region update step terdahulu
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $topID), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $stepNum,

                "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,

            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$topID"),
                "value" => array(
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],
                    "next_step_num" => $nextProp['num'],
                    "step_current" => $stepNum,
                ),
            );
            cekHijau($this->db->last_query());

            //-------------------------------------------------
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $trID), array(
                "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,
            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$trID"),
                "value" => array(
                    "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,
                ),
            );


            //mati_disini("==== ==== ====");
            //endregion

            $tCode = $configUiMasterModulOrigJenis['steps'][$stepNum]['target'];
            $tCodeName = $configUiMasterModulOrigJenis['steps'][$stepNum]['label'];
            $masterReplacers = array(
                //            "referensi_id" => $masterID, (dimatikan)
                //            "id_master"       => $masterID,
                //            "id_top"          => $topID,
                "inv" => $tmpNomorNota,
                //            "jenis_top"           => $tCode,
                "jenis" => $tCode,
                "jenis_label" => $tCodeName,
                "transaksi_jenis" => $tCode,
                "cabang_id" => selectedTransactionSession() ? $_SESSION[$cCode]['main']['cabangID'] : $this->session->login['cabang_id'],
                "cabang_nama" => selectedTransactionSession() ? $_SESSION[$cCode]['main']['cabangName'] : $this->session->login['cabang_nama'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "step_current" => "0",
                "step_number" => $stepNum,
                //            "next_step_code"      => "",
                //            "next_step_label"     => "",
                //            "next_group_code"     => "",
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                //===references
                "id_master" => $masterID,
                "id_top" => $topID,
                "ids_prev" => base64_encode(serialize($prevIDs)),
                "ids_prev_intext" => print_r($prevIDs, true),
                "nomer_top2" => isset($_SESSION[$cCode]['main']['nomer_top2']) ? $_SESSION[$cCode]['main']['nomer_top2'] : "",
                "nomer_top" => $_SESSION[$cCode]['tableIn_master']['nomer_top'],
                "nomers_prev" => base64_encode(serialize($prevNos)),
                "nomers_prev_intext" => print_r($prevNos, true),
                //            "jenis_top"           => $this->jenisTr,
                "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
                "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
                "tail_number" => $stepNum,
                "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
            );

            foreach ($masterReplacers as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }

            $childTableRepaclers = array(
                "sub_step_number" => $stepNum,
                "sub_step_current" => $stepNum,
                "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
            );
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                //			$id = $dSpec['id'];
                foreach ($childTableRepaclers as $key => $val) {
                    $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }


            $masterReplacersO = array(

                "jenisTr" => $tCode,
                "jenisTrName" => $tCodeName,
                "olehID" => $this->session->login['id'],
                "olehName" => $this->session->login['nama'],
                "stepNumber" => $stepNum,
                "stepCode" => $tCode,
            );
            foreach ($masterReplacersO as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }

            //region menimbulkan nilai tagihan
            $unpaidList = null != $this->config->item('tr_unpaidList') ? $this->config->item('tr_unpaidList') : array();
            //        arrprint($_SESSION[$cCode]['tableIn_master']);
            if (in_array($tCode, $unpaidList)) {
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $_SESSION[$cCode]['tableIn_master']['transaksi_nilai'];
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($_SESSION[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $_SESSION[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
                //cekMerah("NULIS TAGIHANN");
            }
            else {
                //cekMerah("TIDAK NULIS TAGIHANN");
            }
            //endregion


            //region penomoran receipt #1

            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($configCoreMasterModulOrigJenis['formatNota']);
            if (!in_array($counterForNumber[0], $configCoreMasterModulOrigJenis['counters'])) {
                die(__LINE__ . " Used number should be registered in 'counters' config as well");
            }

            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }
            $tmpNomorNota2_current = $tmpNomorNota2 = $paramSpec['paramString'];
            $tmpNomorNota2Alias_current = $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);

            //endregion

            //region dynamic counters #1
            echo "<script>top.writeProgress('sedang membuat penomoran');</script>";
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $configCoreMasterModulOrigJenis['counters'];
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


            $masterReplacers = array(
                "nomer" => $tmpNomorNota2,
                "nomer2" => $tmpNomorNota2Alias,
                "counters" => $appliedCounters2,
                "counters_intext" => $appliedCounters_inText2,
            );
            foreach ($masterReplacers as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }

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
            //==tulis kloningan transaksi

            //region write entries
            if (sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {

                // region locker transaksi---------------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if ($this->session->login['ghost'] == 0) {
                        //                $followUpValidator = isset($configUiMasterModulOrigJenis['followUpValidator'][$stepNum]) ? $configUiMasterModulOrigJenis['followUpValidator'][$stepNum] : false;
                        //                if ($followUpValidator == true) {

                        $this->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->addFilter("transaksi_id='$no'");
                        $lt->addFilter("state='hold'");
                        $lt->addFilter("jumlah='1'");
                        $lt->addFilter("oleh_id=" . my_id());
                        $ltTmp = $lt->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($ltTmp) == 1) {
                            cekHijau(":: lanjuut eksekusi transaksi ini....");
                        }
                        else {
                            $msg = "Transaksi sudah dieksekusi atau ada indikasi transaksi ganda. Silahkan tutup halaman ini dan refresh ulang.";
                            cekMerah($msg);
                            die(lgShowAlertBiru($msg));
                        }

                        //                }
                    }
                }
                // endregion locker transaksi---------------------------------

                $_SESSION[$cCode]['tableIn_master']['status_4'] = 11;
                $_SESSION[$cCode]['tableIn_master']['trash_4'] = 0;
                $_SESSION[$cCode]['main']['status_4'] = 1;
                $_SESSION[$cCode]['main']['trash_4'] = 0;


                $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                $midmaster = $insertID;
                cekBiru("master invoice " . $insertID);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $_SESSION[$cCode]['tableIn_master']);
                $mongoList['main'] = array($insertID, $epID);
                $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                $mNumMaster = $insertNum;
                $mJenisMaster = $_SESSION[$cCode]['tableIn_master']['jenis'];
                $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }


                if (isset($_SESSION[$cCode]['tableIn_master']['ids_his'])) {
                    $idHis_decode = blobDecode($_SESSION[$cCode]['tableIn_master']['ids_his']);
                    $idHis_decode[$stepNum] = array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $_SESSION[$cCode]['main']['olehID'],
                        "olehName" => $_SESSION[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $_SESSION[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $_SESSION[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;


                    $tr = new MdlTransaksi();
                    $dup = $tr->updateData(array("id" => $insertID), array(
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");
                    cekUngu($this->db->last_query());
                }


                cekUngu(":: insertID => $insertID ::");
                if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                    $inserMainValues = array();
                    $mongoList['mainValues'] = array();
                    foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                    foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                    //                cekMerah("ada mainElements $cCode");
                    //                arrprint($_SESSION[$cCode]['main_elements']);die();
                    foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? print_r($aSpec['contents_intext'], true) : "",

                        ));
                    }
                }
                else {
                    //                cekMerah("TAK ada mainElements");
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
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
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
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
                if (isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) && sizeof($_SESSION[$cCode]['tableIn_detail_rsltItems']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_rsltItems'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detail_rsltItems'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detail_rsltItems'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail_rsltItems'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }

                //region update validQty pada step sebelumnya yang di-refer
                echo "<script>top.writeProgress('EXTRACT ITEMS...','head');</script>";
                $seluruhnya = true;
                $prevTrID = 0;
                $arrvalidQtySisa = array();
                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                    $closedRequest = isset($configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled']) ? $configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled'] : false;
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $iID => $dSpec) {
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
                                $mongoList['detail'][] = $insertDetailID;
                            }
                        }

                        cekHitam("EXTRACTED ITEMS... [$iID]");
                        echo "<script>top.writeProgress('" . strtoupper($dSpec['produk_nama']) . "');</script>";


                        if (isset($_SESSION[$cCode]['extractedItems'])) {
                            if (array_key_exists($iID, $_SESSION[$cCode]['extractedItems'])) {
                                $itemFulfilledJml = 0;
                                foreach ($_SESSION[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {
                                    $prevTrID = $triSpec['transaksi_id'];
                                    $tru = new MdlTransaksi();
                                    $tru->setFilters(array());
                                    $tru->setTableName($tru->getTableNames()['detail']);
                                    //----------------------------------------------------------
                                    if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                        $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    cekmerah("validQty dikurangi oleh produk_ord_jml, yaitu " . $dSpec['produk_ord_jml']);
                                    }
                                    else {
                                        $newValidQty = ($triSpec['valid_qty'] - $triSpec['valid_qty']);
                                        //                                    cekmerah("validQty dikurangi oleh triSpec,  myaitu " . $triSpec['valid_qty']);
                                    }
                                    //----------------------------------------------------------
                                    $newValidQtyNotApprove = 0;
                                    if ($closedRequest == true) {
                                        cekPink2("closed Request enabled, request: " . $triSpec['valid_qty'] . ", approve: " . $dSpec['produk_ord_jml'] . ", newValidQty: " . $newValidQty);
                                        if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                            $newValidQty = 0;
                                            $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);

                                        }
                                        //                                    else{
                                        //                                        $newValidQty = 0;
                                        //                                        $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    }
                                        cekPink2("new valid qty: $newValidQty, valid qty not approve: $newValidQtyNotApprove");
                                    }
                                    //----------------------------------------------------------


                                    $itemFulfilledJml += $newValidQty;
                                    $updateContents = array(
                                        "valid_qty" => $newValidQty,
                                        "valid_qty_no_approve" => $newValidQtyNotApprove,
                                    );
                                    if ($newValidQty < 1) {
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                    }
                                    else {//==kalau ada yang tidak habis, berarti TIDAK seluruhnya yang dilanjutkan pada step berikutnya
                                        $seluruhnya = false;
                                        $arrvalidQtySisa[$iID] = $newValidQty;
                                    }
                                    $dupState = $tru->updateData(array(
                                        "produk_id" => $iID,
                                        "id" => $triID,
                                        "transaksi_id" => $triSpec['transaksi_id'],
                                    ), $updateContents) or die("Failed to update previous detail entries!");
                                    cekHijau($this->db->last_query());

                                    $mongUpdateList['update']['detail'][] = array(
                                        "where" => array(
                                            //                                        "transaksi_id" => $triSpec['transaksi_id'],
                                            "id" => "$triID",
                                            //                                        "produk_id" => $iID,
                                        ),
                                        "value" => $updateContents,
                                    );
                                    unset($tru);
                                }
                            }
                            //                        else{
                            //                            if($closedRequest == true){
                            //
                            //                            }
                            //                        }
                        }
                    }

                    if ($closedRequest == true) {
                        if (isset($_SESSION[$cCode]['extractedItems'])) {
                            foreach ($_SESSION[$cCode]['extractedItems'] as $iIDex => $exSpec) {
                                if (!array_key_exists($iIDex, $_SESSION[$cCode]['tableIn_detail'])) {
                                    foreach ($exSpec as $trDataID => $trdSpec) {
                                        $tru = new MdlTransaksi();
                                        $tru->setFilters(array());
                                        $tru->setTableName($tru->getTableNames()['detail']);
                                        $updateContents = array(
                                            "valid_qty" => 0,
                                            "valid_qty_no_approve" => $trdSpec['qty'],
                                        );
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                        $dupState = $tru->updateData(array(
                                            "produk_id" => $iIDex,
                                            "id" => $trDataID,
                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                        ), $updateContents) or die("Failed to update previous detail entries!");
                                        //                                    cekHijau($this->db->last_query());
                                        $mongUpdateList['update']['detail'][] = array(
                                            "where" => array(
                                                //                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                                "id" => "$trDataID",
                                                //                                            "produk_id" => $iIDex,
                                            ),
                                            "value" => $updateContents,
                                        );
                                        unset($tru);
                                    }
                                }
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
                            arrPrint($arrID);
                            $arrBlob = blobEncode($arrID);
                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->db->last_query());
                        }
                    }

                    //-------------
                    $lastStepPartialApprove = isset($configUiMasterModulJenis['lastStepPartialApprove']) ? $configUiMasterModulJenis['lastStepPartialApprove'] : false;
                    if ($lastStepPartialApprove == true) {
                        cekKuning(__LINE__ . " $lastStepPartialApprove :: $totalSteps");
                        if ($totalSteps == 2) {
                            if (sizeof($arrvalidQtySisa) > 0) {
                                cekPink("ada valid qty yang tersisa");
                                $tr = new MdlTransaksi();
                                $dupState = $tr->updateData(array("id" => $topID), $stepNowParameter) or die("Failed to update tr next-state!");
                                cekHitam(__LINE__ . " ## 2 step, dan step akhir partial, YESS...");
                                showLast_query("orange");
                            }
                        }
                    }
                }
                else {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }

                if ($seluruhnya) {
                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $prevTrID), array(
                        "tail_number" => $stepNum,
                        "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                        "status_4" => $_SESSION[$cCode]['main']['status_4'],
                        "trash_4" => $_SESSION[$cCode]['main']['trash_4'],
                    )) or die("Failed to update tr next-state!");
                    cekHijau(":: UOPDATE transaksi dengan trID -> $prevTrID");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array(
                            "id" => "$prevTrID",
                        ),
                        "value" => array(
                            "tail_number" => $stepNum,
                            "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                            "status_4" => $_SESSION[$cCode]['main']['status_4'],
                            "trash_4" => $_SESSION[$cCode]['main']['trash_4'],
                        ),
                    );
                    cekHijau($this->db->last_query());
                }
                //endregion

                //region cloner items to item_child
                if (sizeof($additionalData) > 0) {
                    echo "<script>top.writeProgress('CLONING ITEMS TO ITEM CHILD...','head');</script>";
                    cekHitam("ini data");
                    $dataMdl = $additionalData["mdlName"];
                    $this->load->model("Mdls/" . $dataMdl);
                    $da = new $dataMdl();
                    $arrColl = $da->getFields();
                    $selectedCol = array();
                    foreach ($arrColl as $colSpec) {
                        $selectedCol[] = $colSpec['kolom'];
                    }

                    if (isset($_SESSION[$cCode]['items_child']) && sizeof($_SESSION[$cCode]['items_child'])) {
                        $gateData = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate']) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate'] : "detail";

                        $arrBlacklist = array(
                            "jml", "max_jml", "qty",
                        );
                        if (isset($_SESSION[$cCode]["items2_sum"])) {
                            unset($_SESSION[$cCode]["items2_sum"]);
                            unset($_SESSION[$cCode]["items2"]);
                            unset($_SESSION[$cCode]["tableIn_detail_values2_sum"]);
                        }
                        foreach ($_SESSION[$cCode]['items_child'] as $mainProdsID => $defData) {
                            if ($gateData == "detail") {
                                $itemsMain = isset($_SESSION[$cCode]['items'][$mainProdsID]) ? $_SESSION[$cCode]['items'][$mainProdsID] : array();
                            }
                            else {
                                $forceMainToItems = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData]) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData] : array();
                                if (sizeof($forceMainToItems) > 0) {
                                    foreach ($forceMainToItems as $key1 => $key2) {
                                        $keyForce = strlen($key2) > 2 ? $key2 : $key1;
                                        $itemsMain[$key1] = isset($_SESSION[$cCode]['main'][$keyForce]) ? $_SESSION[$cCode]['main'][$keyForce] : "";
                                    }
                                    $itemsMain["jml"] = "1";
                                    $itemsMain["qty"] = "1";
                                    $itemsMain["max_jml"] = "1";

                                }
                                else {
                                    matiHEre("detil aset gagal di tulis!");
                                }
                                //                            arrPrint($forceMainToItems);
                            }

                            $arrChilds = array_diff_key($itemsMain, array_flip($arrBlacklist));
                            //                        arrPrint($itemsMain);
                            //                        matiHEre();
                            //
                            //arrPrint($arrChilds);
                            cekLime("ini brooo " . $gateData);

                            $arrNew = array();
                            if (sizeof($itemsMain) > 0) {
                                foreach ($defData as $inID => $detil_child) {
                                    //                        $arrNewChild = array_diff($itemsMain,$detil_child);

                                    $paramDetil = array_replace($arrChilds, $detil_child);
                                    if (array_key_exists("id", $paramDetil)) {

                                        $paramDetil["parent_id"] = $paramDetil["id"];
                                        if (!isset($paramDetil["folders"]) || $paramDetil["folders"] == 0) {
                                            $paramDetil["folders"] = $paramDetil["pihakMainId"];
                                            $paramDetil["keterangan"] = $paramDetil["pihakMainName"];
                                        }
                                        unset($paramDetil["id"]);
                                    }
                                    $tmpData = array();
                                    foreach ($selectedCol as $i => $coloum) {
                                        if (isset($paramDetil[$coloum])) {
                                            $tmpData[$coloum] = $paramDetil[$coloum];
                                        }
                                    }
                                    //                                arrPrint($paramDetil);
                                    if (isset($paramDetil["subtotal"])) {
                                        $paramDetil["subtotal"] = $paramDetil["jml"] * $paramDetil["harga"];
                                    }

                                    $insertDataID = $da->addData($tmpData, $da->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                                    cekHere($this->db->last_query());
                                    $paramDetil["id"] = $insertDataID;
                                    echo "<script>top.writeProgress('PENGAJUAN DATA (TRID:$insertDataID)');</script>";
                                    $_SESSION[$cCode]["items2_sum"][$insertDataID] = $paramDetil;
                                    $_SESSION[$cCode]["items2"][$mainProdsID][$insertDataID] = $paramDetil;
                                    //                            $arrNew

                                }
                            }


                            //                        arrPrint($arrNew);
                            //


                            //                  arrPrint($itemsMain);
                        }

                    }
                }

                //endregion

                if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                    }
                }
                if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                        cekUngu($this->db->last_query());
                    }
                }


                if (isset($configUiMasterModulJenis['updateDueDate'][$stepNum])) {
                    $dueDateConf = $configUiMasterModulJenis['updateDueDate'][$stepNum];
                    $sourceDue = $dueDateConf['source'];
                    $targetDue = $dueDateConf['target'];
                    $datenow = date("Y-m-d");
                    foreach ($sourceDue as $key => $val) {
                        $indexVal = isset($_SESSION[$cCode]['main_elements'][$key][$val]) ? $_SESSION[$cCode]['main_elements'][$key][$val] : 14;
                        $dueDate = dueDate($datenow, $indexVal);
                    }
                    $fieldDue = $tr->getFields()["dueDate"];
                    $dataDue = array();
                    foreach ($fieldDue as $kol) {
                        if (isset($_SESSION[$cCode]['tableIn_master'][$kol])) {
                            $dataDue[$kol] = $_SESSION[$cCode]['tableIn_master'][$kol];
                        }
                    }
                    $dataDue['due_date'] = $dueDate;
                    $validateDue = validateDueDate($_SESSION[$cCode]['main']['customerID'], $_SESSION[$cCode]['main']['dtime']);

                    arrPrint($validateDue);
                    if ($validateDue['allow_create'] == "true") {
                        if (isset($_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']) && $_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen'] > 0) {
                            cekBiru($_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']);

                            $tr->writeDueDate($insertID, $dataDue);
                        }
                    }
                    else {
                        $allowedOver = validateOverDue($_SESSION[$cCode]['main']['customerID']);
                        if ($allowedOver['status'] == "allowed") {

                        }
                        else {
                            //                        matiHere($validateDue['error']);//matiin transaksi sudah over due
                        }
                        //                    arrPrint()
                        //                    matiHere($validateDue['error']);//matiin transaksi sudah over due
                    }
                    //                matiHere();
                    //update main elementnya
                    foreach ($targetDue as $keyTarget => $valTarget) {
                        $_SESSION[$cCode]['main_elements'][$keyTarget][$valTarget] = $dueDate;
                        $_SESSION[$cCode]['main']['dueDate'] = $dueDate;
                    }
                }
                arrPrintPink($_SESSION[$cCode]['tableIn_master']);

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
                    "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields'][$stepNum] : array(),
                    "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields'][$stepNum] : array(),
                    "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum] : array(),
                    "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields2'][$stepNum] : array(),
                    "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum] : array(),
                    "jurnal_index" => isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) ? $configCoreMasterModulJenis['components'][$jenisTrTarget] : array(),
                    "preProcessor" => isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget] : array(),
                    "postProcessor" => isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget] : array(),
                    "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),
                    "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                    "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
                    "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),

                );
                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                $mongRegID = $doWriteReg;
                echo "<script>top.writeProgress('MENULIS KE-REGISTRY....');</script>";
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan, silahkan cek kembali transaksi ini."));
            }
            //endregion
            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

            //region processing sub-post-processors, always
            //<editor-fold desc="----------sub postProc">
            // matiHEre();
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                    $tmpOutParams[$cCtr] = array();
                    foreach ($_SESSION[$cCode][$srcGateName] as $cnt => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['static'][$key] = $realValue;
                                cekBiru("$key diisi dengan $realValue");

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
                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, sending values <br>";
                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekBiru($this->db->last_query());
                }
            }

            //endregion

            //region processing main-post-processors, always
            //<editor-fold desc="----------postProc">

            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                            }
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

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
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //                cekBiru("kiriman komponem $comName");
                    //                                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


                }
            }


            //</editor-fold>
            //endregion

            //region ----------subcomponents GESER KE CLI

            //        $componentGate['detail'] = array();
            //        //arrPrint($paramForceFillers);
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
            $componentConfig['detail'] = $iterator;
            //        if (sizeof($iterator) > 0) {
            //            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            //            $filterNeeded = false;
            //            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                $filterNeeded = true;
            //            }
            //            foreach ($iterator as $cCtr => $tComSpec) {
            ////                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub-component: $comName, $srcGateName, initializing values <br>";
            //                $tmpOutParams[$cCtr] = array();
            //                foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
            //                    cekmerah("mengevaluasi $srcGateName..");
            //                    $comName = $tComSpec['comName'];
            //                    if (substr($comName, 0, 1) == "{") {
            //                        $comName = trim($comName, "{");
            //                        $comName = trim($comName, "}");
            //                        $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
            //                        $tComSpec['comName'] = $comName;
            //                        $iterator[$cCtr]['comName'] = $comName;
            //                    }
            //
            //                    $filterNeeded = false;
            //                    $mdlName = "Com" . ucfirst($comName);
            //                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                        $filterNeeded = true;
            //                    }
            //
            //
            //                    $subParams = array();
            //                    if (isset($tComSpec['loop'])) {
            //                        foreach ($tComSpec['loop'] as $key => $value) {
            //                            if (substr($key, 0, 1) == "{") {
            //                                $key = trim($key, "{");
            //                                $key = trim($key, "}");
            //                                $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
            //                            }
            //                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
            //                            $subParams['loop'][$key] = $realValue;
            //                            cekKuning("LOOP: $key diisi dengan $realValue");
            //
            //                            if ($filterNeeded) {
            //                                if ($subParams['loop'][$key] == 0) {
            //                                    unset($subParams['loop'][$key]);
            //                                }
            //                            }
            //                        }
            //                    }
            //                    if (isset($tComSpec['static'])) {
            //                        foreach ($tComSpec['static'] as $key => $value) {
            //
            //                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
            //                            $subParams['static'][$key] = $realValue;
            //                            cekKuning("STATIC: $key diisi dengan $realValue");
            //
            //                        }
            //                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
            //                            foreach ($paramPatchers[$comName] as $k => $v) {
            //                                if (!isset($subParams['static'][$k])) {
            //                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                    cekOrange("fill :: $comName :: $k => " . $subParams['static'][$k]);
            //                                }
            //                            }
            //                        }
            //                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
            //                            //                            cekOrange("comName:: $comName");
            //                            $jenis = $_SESSION[$cCode]['main']['jenis'];
            //                            foreach ($paramForceFillers[$comName] as $k => $v) {
            //                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                cekOrange("fillforce :: $comName :: $k => " . $subParams['static'][$k]);
            //                            }
            //                        }
            //                        $subParams['static']["fulldate"] = date("Y-m-d");
            //                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
            //                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
            //                    }
            //                    cekHitam("cetak subParams");
            //                    arrPrint($subParams);
            //                    if (sizeof($subParams) > 0) {
            //                        if ($filterNeeded) {
            //                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
            //                                $tmpOutParams[$cCtr][] = $subParams;
            //                            }
            //                        }
            //                        else {
            //
            //                            $tmpOutParams[$cCtr][] = $subParams;
            //                        }
            //                    }
            //                }
            //
            //                $componentGate['detail'][$cCtr] = $subParams;
            //            }
            //
            //
            //            $it = 0;
            //            foreach ($iterator as $cCtr => $tComSpec) {
            //                $it++;
            //
            //
            //                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub component #$it: $comName, sending values <br>";
            //
            //                $mdlName = "Com" . ucfirst($comName);
            //                $this->load->model("Coms/" . $mdlName);
            //                $m = new $mdlName();
            //
            //
            //                if (sizeof($tmpOutParams[$cCtr]) > 0) {
            //                    $tobeExecuted = true;
            //                }
            //                else {
            //                    $tobeExecuted = false;
            //                }
            //
            //
            //                if ($tobeExecuted) {
            //                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                }
            //                else {
            //                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
            //                }
            //            }
            //        }
            //        else {
            //            //cekKuning("subcomponents is not set");
            //        }

            //endregion

            //region ----------components
            //<editor-fold desc="----------components">
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal'][$stepNum]['master']) ? $_SESSION[$cCode]['revert']['jurnal'][$stepNum]['master'] : array();
            }
            else {
                if (isset($_SESSION[$cCode]['componentsBuilder'][$stepNum]['master'])) {
                    $iterator = $_SESSION[$cCode]['componentsBuilder'][$stepNum]['master'];
                }
                elseif (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master'])) {
                    $iterator = $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'];
                }
                else {
                    $iterator = array();
                }
            }


            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('KOMPONEN...', 'head');</script>";
                $componentConfig['master'] = $iterator;

                $it = 0;
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    //                cekPink($tComSpec);
                    //                mati_disini();
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component #$it: $comName :: $srcGateName <br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                //                            $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$key], $key);
                            }
                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            if ($key != null) {
                                $tmpOutParams['loop'][$key] = $realValue;
                            }

                        }
                    }
                    //                cekBiru($tmpOutParams);
                    //                mati_disini(__LINE__);
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                            cekHijau(":: NORMAL :: $key => $realValue ::");
                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            cekHijau(":: masuk ke PATCHER ::");
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                cekHijau(":: ada yang mau di-PATCHER ::");
                                arrPrint($tmpOutParams['static']);
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekHijau(":: PATCHER :: $key => $realValue ::");
                                }

                            }
                        }
                        else {
                            cekMerah(":: TIDAK TERMASUK PATCHER ::");
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                cekHijau(":: FORCEFILL :: $key => $realValue ::");
                            }
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

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
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
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
                        cekBiru("kiriman komponen $comName");
                        arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    else {
                        cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                    }

                    $componentGate['master'][$cCtr] = $tmpOutParams;
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion

            //region nulis paymentSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {
                $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $valueAdd = isset($_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
                        if (isset($paymentSrcConfig['model'])) {
                            $mdlName = $paymentSrcConfig['model'];
                            $this->load->model("Mdls/$mdlName");
                            $pMdl = New $mdlName();
                            $pTmpMdl = $pMdl->lookupAll()->result();
                            $pTmpMdlResult = array();
                            if (sizeof($pTmpMdl) > 0) {
                                foreach ($pTmpMdl as $pTmpMdlSpec) {
                                    $pTmpMdlResult[$pTmpMdlSpec->id] = $pTmpMdlSpec;
                                }
                            }
                        }
                        else {
                            $pTmpMdlResult = array();
                        }

                        if (isset($_SESSION[$cCode]['main'][$valueSrc]) && $_SESSION[$cCode]['main'][$valueSrc] > 0) {
                            if (isset($externSrc['extern_label2'])) {
                                //cek ada isinya atau kosong
                                $cek = strlen($_SESSION[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
                            }
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

                            //-----------------------
                            cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                            $this->load->helper("he_payment_source");
                            //                        paymentSource($this->jenisTr, $componentJurnal, $_SESSION[$cCode]['main'], $valueLabel, $valueSrc, $valueAdd);
                            //-----------------------

                            $arrPymSrc = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => isset($_SESSION[$cCode]['main'][$externSrc['id']]) ? $_SESSION[$cCode]['main'][$externSrc['id']] : "",
                                "extern_nama" => isset($_SESSION[$cCode]['main'][$externSrc['nama']]) ? $_SESSION[$cCode]['main'][$externSrc['nama']] : "",
                                "nomer" => $tmpNomorNota2,
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
                                "valas_id" => isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',

                                "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                "terbayar_valas" => 0,
                                "sisa_valas" => isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',

                                //                            "extern_label2" => isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "",
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",

                                "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($_SESSION[$cCode]['main'][$externSrc['dpp_ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                "ppn" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : 0,
                                "ppn_approved" => (isset($externSrc['ppn_approved']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_approved']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                "ppn_sisa" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : "",
                                "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "extern_date2" => (isset($externSrc['extern_date2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_date2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_date2']] : "",
                                "pph_23" => (isset($externSrc['pph_23']) && ($_SESSION[$cCode]['main'][$externSrc['pph_23']])) ? $_SESSION[$cCode]['main'][$externSrc['pph_23']] : "",

                                "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : "",
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                "extern_jenis" => (isset($externSrc['extern_jenis']) && ($_SESSION[$cCode]['main'][$externSrc['extern_jenis']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai3']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai4']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
                                //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($_SESSION[$cCode]['main'][$externSrc['payment_locked']])) ? $_SESSION[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                            );
                            $tr->writePaymentSrc($insertID, $arrPymSrc);

                        }


                        cekMerah($this->db->last_query());
                    }
                }

            }
            else {
                cekMerah("TIDAK nulis paymentSrc");
            }

            $addPaymentSource = isset($configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService']) ? $configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService'] : array();

            //endregion

            //region nulis paymentAntiSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_antiSource");
            if (array_key_exists($stepCode, $paymentSources)) {
                cekMerah(":: starting PAYMENT ANTI SOURCE");
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
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        ));
                        //cekMerah($this->db->last_query());
                    }
                }

            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion

            //region nulis uangMukaSource
            /*dimatiin geser ke ComUangmukaSourceDetail karena ada di items.
            /*revisi tanggal 27 mei 2020 subject digeser ke vendor dari jenis transaksi misal uangmuka asuransi,uang muka pembelian ->uang muka.
             *
             */
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $uangMukaSources = $this->config->item("uang_muka");

            if (array_key_exists($stepCode, $uangMukaSources)) {
                cekMerah(":: starting UANG MUKA  SOURCE");
                //            matiHere();
                $uangMukaConfigs = isset($uangMukaSources[$stepCode][$stepNum]) ? $uangMukaSources[$stepCode][$stepNum] : array();
                if (sizeof($uangMukaConfigs) > 0) {
                    $cekPreValue = "";
                    $this->load->model("Mdls/MdlPaymentUangMuka");
                    $l = new MdlPaymentUangMuka();
                    foreach ($uangMukaConfigs as $uangMukaSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        //                    arrPrint($uangMukaSrcConfig);
                        $valueSrc = $uangMukaSrcConfig['valueSrc'];
                        $externSrc = $uangMukaSrcConfig['externSrc'];
                        $l->addFilter("extern_id='" . $_SESSION[$cCode]['main'][$externSrc['id']] . "'");
                        $l->addFilter("extern_label2='" . $externSrc['extLabel'] . "'");
                        $tmpUm = $l->lookupAll()->result();
                        //                    arrPrint($tmpUm);
                        if (sizeof($tmpUm) > 0) {
                            //update here broo
                            $preTagihan = $tmpUm[0]->tagihan;
                            $preSisa = $tmpUm[0]->sisa;

                            $newTahigan = $preTagihan + $_SESSION[$cCode]['main'][$valueSrc];
                            $newsisa = $preSisa + $_SESSION[$cCode]['main'][$valueSrc];
                            $update = array(
                                "tagihan" => $newTahigan,
                                "sisa" => $newsisa,
                            );
                            $where = array(
                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                            );
                            $tr->updateUangMukaSrc($where, $update);
                            cekHitam($this->db->last_query());
                        }
                        else {
                            //insertbaru brooo
                            $tr->writeUangMukaSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                "nomer" => "",
                                "note" => "",
                                "label" => $uangMukaSrcConfig['label'],
                                "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "extern_label2" => $externSrc['extLabel'],
                            ));
                        }
                        cekMerah($this->db->last_query());
                    }
                }
                else {
                    cekLime("not write uang muka");
                }

            }
            else {
                cekMerah("not write uang muka");
            }
            //endregion


        }
        else {
            $masterID = 0;
            $tmpNomorNota = "XXXX";
            $origJenis = 0;
            $topID = 0;
            die(lgShowAlert("No such receipt ID: $no!"));
        }

    }

    public function otorisasiPenjualan($jenisTr, $no, $stepNum, $stepNumCurrent)
    {
        $transaksiID_reference = $masterID = $no;
        $nextStepNum = $stepNum + 1;

        $paramPatchers = $this->config->item('heTransaksi_paramPatchers') != null ? $this->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->config->item('heTransaksi_paramForceFillers') != null ? $this->config->item('heTransaksi_paramForceFillers') : array();
        $this->load->library("FieldCalculator");
        $cal = new FieldCalculator();
        $stepNowParameter = array();
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
        $tr->addFilter("step_number='" . $stepNumCurrent . "'");
        $tr->addFilterJoin("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined();
        if (sizeof($tmpTr) > 0) {
            $extractedItems = array();//==untuk urusan update transaksi referer
            $validItems = array();
            $validItemSends = array();
            $validItemReqCancels = array();
            $validItemCancels = array();
            $validItemPreCancels = array();
            $validItemSents = array();
            foreach ($tmpTr as $row) {
                if ($row->valid_qty > 0) {
                    if (!isset($validItems[$row->produk_id])) {
                        $validItems[$row->produk_id] = 0;
                    }
                    if (!isset($validItemSends[$row->produk_id])) {
                        $validItemSends[$row->produk_id] = 0;
                    }
                    if (!isset($validItemCancels[$row->produk_id])) {
                        $validItemCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemReqCancels[$row->produk_id])) {
                        $validItemReqCancels[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPackeds[$row->produk_id])) {
                        $validItemPackeds[$row->produk_id] = 0;
                    }
                    if (!isset($validItemPreCancels[$row->produk_id])) {
                        $validItemPreCancels[$row->produk_id] = 0;
                    }

                    $validItems[$row->produk_id] += isset($row->valid_qty) ? $row->valid_qty : 0;
                    $validItemSends[$row->produk_id] += isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0;
                    $validItemCancels[$row->produk_id] += isset($row->cancel_qty) ? $row->cancel_qty : 0;
                    $validItemReqCancels[$row->produk_id] += isset($row->req_cancel_qty) ? $row->req_cancel_qty : 0;
                    $validItemPreCancels[$row->produk_id] += isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0;
                    $validItemPackeds[$row->produk_id] += isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0;

                    if (!isset($extractedItems[$row->produk_id])) {
                        $extractedItems[$row->produk_id] = array();
                    }
                    $extractedItems[$row->produk_id][$row->id_detail] = array(
                        "id" => $row->id_detail,
                        "produk_id" => $row->produk_id,
                        "qty" => $row->produk_ord_jml,
                        "valid_qty" => $row->valid_qty,
                        "transaksi_id" => $row->transaksi_id,
                        "packed_qty" => isset($arrTmp__['582pkd'][$row->produk_id]) ? $arrTmp__['582pkd'][$row->produk_id] : 0,
                        "sent_qty" => isset($arrTmp__['582spd'][$row->produk_id]) ? $arrTmp__['582spd'][$row->produk_id] : 0,
                        "req_cancel_qty" => isset($arrPreTmp__['1982'][$row->produk_id]) ? $arrPreTmp__['1982'][$row->produk_id] : 0,
                        "cancel_qty" => $row->cancel_qty,
                        "outstanding" => $row->produk_ord_jml - ($row->produk_ord_jml - $row->valid_qty),
                    );
                }
            }
            $this->jenisTr = $tmpTr[0]->jenis_master;
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            //--------------------------------
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;

            $trID = $tmpTr[0]->transaksi_id;
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = null;
                unset($_SESSION[$cCode]);
            }
            //region session init
            if (!isset($_SESSION[$cCode])) {
                $_SESSION[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($_SESSION[$cCode]['main'])) {
                $_SESSION[$cCode]['main'] = array();
            }
            if (!isset($_SESSION[$cCode]['items'])) {
                $_SESSION[$cCode]['items'] = array();
            }
            //endregion
            $_SESSION[$cCode]['extractedItems'] = $extractedItems;


            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
            $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
            $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
            $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");

            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $configCoreMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiCore");
            $configLayoutMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiLayout");


            $jenisTrTarget = isset($configUiMasterModulJenis["steps"][$stepNum]["target"]) ? $configUiMasterModulJenis["steps"][$stepNum]["target"] : NULL;
            $detailValuesConfig = isset($configCoreMasterModulJenis['tableIn']['detailValues']) ? $configCoreMasterModulJenis['tableIn']['detailValues'] : array();
            $additionalData = isset($configUiMasterModulJenis["addDetailData"][$stepNum]) ? $configUiMasterModulJenis["addDetailData"][$stepNum] : array();


            $totalSteps = sizeof($configUiMasterModulJenis['steps']);
            //==references, previous entry
            $prevProp = array(
                "id" => $tmpTr[0]->transaksi_id,
                "jenis" => $tmpTr[0]->jenis,
                "nomer" => $tmpTr[0]->nomer,
            );
            //------
            $stepNowParameter = array(
                "next_step_code" => $tmpTr[0]->next_step_code,
                "next_step_label" => $tmpTr[0]->next_step_label,
                "next_group_code" => $tmpTr[0]->next_group_code,
                "next_step_num" => $tmpTr[0]->next_step_num,
                "step_current" => $tmpTr[0]->step_current,
            );
            $tmpVal_main = $tr->lookupMainValuesByTransID($trID)->result();
            $tmpVal_detail = $tr->lookupDetailValuesByTransID($trID)->result();
            $mainValues = array();
            if (sizeof($tmpVal_main) > 0) {
                foreach ($tmpVal_main as $row) {
                    $mainValues[$row->key] = $row->value;
                }
            }
            $detailValues = array();
            if (sizeof($tmpVal_detail) > 0) {
                foreach ($tmpVal_detail as $row) {
                    $detailValues[$row->produk_id][$row->key] = $row->value;
                }
            }

            $main = array();
            $items = array();
            $prevIDs = array();
            $prevNos = array();
            foreach ($tmpTr as $row) {
                $items[$row->produk_id] = array(
                    "id" => $row->produk_id,
                    "nama" => $row->produk_nama,
                    "jml" => $row->produk_ord_jml,
                    "harga" => $row->produk_ord_hrg,
                    "valid_qty" => $row->valid_qty,
                    "transaksi_id" => $row->transaksi_id,
                    "nomer" => $row->nomer,
                );
                if ($row->valid_qty > 0) {
                    cekHitam("ok lanjut");
                }
                else {
                    if (isset($_SESSION[$cCode]['items'][$row->produk_id])) {
                        matiHere("Followed up already. Please close and refresh your browser " . $row->produk_nama . " " . $row->produk_id);//kalo session active ya harus dimatiin biar gak dobel
                    }
                }
                if (!in_array($row->transaksi_id, $prevIDs)) {
                    $prevIDs[] = $row->transaksi_id;
                }
                if (!in_array($row->nomer, $prevNos)) {
                    $prevNos[] = $row->nomer;
                }
                if (sizeof($detailValuesConfig) > 0) {
                    echo "detail values ada..<br>";
                    foreach ($detailValuesConfig as $key => $src) {
                        echo "$key akan ambil nilai dari $src<br>";
                        echo "<script>top.writeProgress('$key akan ambil nilai dari $src');</script>";
                        //                            $tmp[$key]=isset($iSpec[$val])?$iSpec[$val]:0;
                        if (isset($detailValues[$row->produk_id][$key])) {
                            //                            $tmp[$key] = formatField($key, $detailValues[$row->produk_id][$key]);
                            $items[$row->produk_id][$key] = $detailValues[$row->produk_id][$key];
                        }
                        else {
                            if (isset($row->$key)) {
                                //                                $tmp[$key] = formatField($key, $row->$key);
                                $items[$row->produk_id][$key] = $row->$key;
                            }
                        }
                        echo "dan sekarang nilainya: " . $items[$row->produk_id][$key] . "<br>";
                        echo "<script>top.writeProgress('dan sekarang nilainya: " . $items[$row->produk_id][$key] . "');</script>";
                    }
                }
            }

            //region take from registries
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();
            cekKuning($this->db->last_query());
            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        switch ($key_reg) {
                            case "main"://
                                $main = $main + unserialize(base64_decode($val_reg));
                                break;
                            case "items"://
                                $items = $items + unserialize(base64_decode($val_reg));
                                break;
                            case "items2"://
                                $items2 = $items2 + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems"://
                                $rsltItems = $rsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems2"://
                                $rsltItems2 = $rsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "items2_sum"://
                                $items2_sum = $items2_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items3"://
                                $items3 = $items3 + unserialize(base64_decode($val_reg));
                                break;
                            case "items3_sum"://
                                $items3_sum = $items3_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items4_sum"://
                                $items4_sum = $items4_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master"://
                                $masterTableInParams = $masterTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail"://
                                $childTableInParams = $childTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems"://
                                $childTableInParamsRsltItems = $childTableInParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems2"://
                                $childTableInParamsRsltItems2 = $childTableInParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master_values"://
                                $masterTableInValueParams = $masterTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values"://
                                $childTableInValueParams = $childTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems"://
                                $childTableInValueParamsRsltItems = $childTableInValueParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems2"://
                                $childTableInValueParamsRsltItems2 = $childTableInValueParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_values"://
                                $masterAddValues = $masterAddValues + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_fields"://
                                $masterAddFields = $masterAddFields + unserialize(base64_decode($val_reg));
                                break;
                            case "main_elements"://
                                $mainElements = unserialize(base64_decode($val_reg));
                                break;
                            case "main_inputs"://
                                $mainInputs = unserialize(base64_decode($val_reg));
                                break;
                            case "items_komposisi"://
                                $itemsKomposisi = unserialize(base64_decode($val_reg));
                                break;
                        }
                    }
                }

            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion

            //region session-swapper
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;
            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4_sum" => $items4_sum,
                "items_child" => $itemChildData,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,


                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                "main_add_fields" => $masterAddFields,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $_SESSION[$cCode][$targetVar] = $src;

            }
            //endregion


            // region copy gerbang serial dari distribusi
            $shoppingCartCopySerialNumber = isset($configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNumber]) ? $configUiMasterModulJenis["shoppingCartCopySerialNumber"][$stepNumber] : array();
            if(sizeof($shoppingCartCopySerialNumber)>0){
                $statusGudangConfig = $shoppingCartCopySerialNumber["statusGudang"];
                $copyGateConfig = $shoppingCartCopySerialNumber["copyGate"];
                $copyJenisConfig = $shoppingCartCopySerialNumber["copyJenis"];
                if($gudangStatusJenis == $statusGudangConfig){
                    $trs = new MdlTransaksi();
                    $trs->addFilter("jenis='$copyJenisConfig'");
                    $trs->addFilter("reference_id_top='$topID'");
                    $trsTmp = $trs->lookupAll()->result();
                    $trsID = $trsTmp[0]->id;

                    $trs = new MdlTransaksi();
                    $trs->setFilters(array());
                    $trs->setJointSelectFields($copyGateConfig);
                    $trs->addFilter("transaksi_id='$trsID'");
                    $tmpReg = $trs->lookupDataRegistries()->result();
                    if (sizeof($tmpReg) > 0) {
                        foreach ($tmpReg as $row) {
                            foreach ($row as $key_reg => $val_reg) {
                                $_SESSION[$cCode][$key_reg] = blobDecode($val_reg);
                            }
                        }
                    }
                }

            }
            // endregion copy gerbang serial dari distribusi
            resetValues($this->jenisTr);
            fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            //region pembulatan replacer disini
            $injectBulat = isset($configCoreMasterModulJenis['valuePembulatan'][$stepNum]) ? $configCoreMasterModulJenis['valuePembulatan'][$stepNum] : array();
            if (sizeof($injectBulat) > 0) {
                echo "<script>top.writeProgress('PEMBULATAN', 'HEAD');</script>";
                //            arrPrint($injectBulat);
                $selectedSource = $injectBulat['source'];
                $injectSource = makeDppBulat($_SESSION[$cCode]['main'][$selectedSource]);
                foreach ($injectBulat['replacer'] as $k => $fields) {
                    $_SESSION[$cCode]['main'][$fields] = $injectSource[$k];
                    echo "<script>top.writeProgress('PEMBULATAN ($fields)');</script>";
                }

            }
            //endregion

            cekMerah(":: MEMULAI PRE-PROCC ITEMS...");
            $ppnFactor = isset($_SESSION[$cCode]["main"]["ppnFactor"]) ? $_SESSION[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin");

            //region pre-processors (item)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo __LINE__ . " :: sub-preproc: $comName, initializing values <br>";

                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            //                        $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

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
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                            }

                            if (sizeof($subParams) > 0) {

                                $tmpOutParams[$cCtr][] = $subParams;
                            }


                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                            echo "sub preproc #$it: $comName, sending values <br>";

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
                                $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();
                                cekHitam(":: PRE-PROCC -> GOTNAME, ITERATING...");
                                arrprint($gotParams);
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $paramSpec) {

                                        if (!isset($_SESSION[$cCode][$gateName])) {
                                            $_SESSION[$cCode][$gateName] = array();
                                            //                                    cekhijau("building the session: $gateName");
                                        }
                                        else {
                                            //                                    cekhijau("NOT building the session: $gateName");
                                        }

                                        foreach ($paramSpec as $id => $gSpec) {
                                            //                                        $id = $gSpec['id'];
                                            if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                $_SESSION[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }
                                            //==inject gotParams to child gate
                                            if ($gateName == $srcGateName) {
                                                if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                    //                                        die();
                                                }
                                            }
                                        }
                                        //                                    arrPrint($_SESSION[$cCode][$gateName]);die();
                                    }
                                }

                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }

                        $this->load->helper("he_value_builder");
                        fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-processors (master)
            if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'])) {
                $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();

                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    echo "<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>";
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;

                        echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

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
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }


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
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();

                            cekbiru("gotparams dari $comName");
                            arrprint($gotParams);

                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {

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
                                                    cekbiru("injecting param $key with $val");
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
                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }


                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);


            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }

            //endregion

            //region pre-proc value injector items2 items2_sum dari gerbang main
            $injectValues = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum] : array();
            if (sizeof($injectValues) > 0) {
                $iterator = isset($configCoreMasterModulJenis['preInjectValue'][$stepNum]['master']) ? $configCoreMasterModulJenis['preInjectValue'][$stepNum]['master'] : array();
                $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        //                    echo "master-preproc: $comName, initializing values <br>";
                        $tmpOutParams[$cCtr] = array();


                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                                $subParams['static'][$key] = $realValue;

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
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr] = $subParams;
                        }


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
                            $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                //                            cekhijau("ada gotparam, sekarang mau replace");
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if ($gateName == "main") {
                                        foreach ($gSpec as $key => $val) {
                                            $_SESSION[$cCode]['main'][$key] = $val;
                                        }
                                    }
                                    if ($gateName == "items2") {
                                        foreach ($_SESSION[$cCode]['items2'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                foreach ($tmpSes as $y => $sesData) {
                                                    if (array_key_exists($key, $sesData)) {
                                                        $_SESSION[$cCode]['items2'][$k][$y][$key] = $val;
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    if ($gateName == "items2_sum") {
                                        foreach ($_SESSION[$cCode]['items2_sum'] as $k => $tmpSes) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['items2_sum'][$k][$key] = $val;
                                            }
                                        }

                                    }

                                }
                            }
                            else {
                                cekmerah("TIDAK ada gotparam, tidak perlu replace");
                            }

                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues_he_value_builder($this->jenisTr, $stepNumCurrent, $stepNum, $configCoreMasterModulJenis, $configUiMasterModulJenis, $configValuesMasterModulJenis, $ppnFactor);

            }
            //endregion

            $this->load->library("Validator");
            $va = new Validator();
            $va->setConfigUiJenis($configUiMasterModulJenis);
            $va->setCCode($this->cCode);
            $va->midValidate($stepNum);
            $va->unionValidate();

            //region update step2an
            if (isset($configUiMasterModulJenis['steps'][$nextStepNum])) {//===masih ada langkah selanjutnya
                echo "authorizing to next step..<br>";
                $nextProp = array(
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
                );
            }
            else {//==ini step terakhir, tulis komponen jika ada
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion


            //==tulis signature
            $dwsign = $tr->writeSignature($masterID, array(
                "nomer" => $tmpNomorNota,
                "step_number" => $stepNum,
                "step_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['target'],
                "step_name" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'],
                "group_code" => $configUiMasterModulOrigJenis['steps'][$stepNum]['userGroup'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "keterangan" => $configUiMasterModulOrigJenis['steps'][$stepNum]['label'] . " oleh " . $this->session->login['nama'],
                "transaksi_id" => $masterID,
            )) or die("Failed to write signature");
            $mongoList['sign'][] = $dwsign;
            //cekKuning($this->db->last_query());

            //region update step terdahulu
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $topID), array(
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "next_step_num" => $nextProp['num'],
                "step_current" => $stepNum,

                "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,

            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$topID"),
                "value" => array(
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],
                    "next_step_num" => $nextProp['num'],
                    "step_current" => $stepNum,
                ),
            );
            cekHijau($this->db->last_query());

            //-------------------------------------------------
            $tr = new MdlTransaksi();
            $dupState = $tr->updateData(array("id" => $trID), array(
                "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,
            )) or die("Failed to update tr next-state!");
            $mongUpdateList['update']['main'][] = array(
                "where" => array("id" => "$trID"),
                "value" => array(
                    "partial" => isset($_SESSION[$cCode]['main']['partial']) ? $_SESSION[$cCode]['main']['partial'] : 0,
                ),
            );


            //mati_disini("==== ==== ====");
            //endregion

            $tCode = $configUiMasterModulOrigJenis['steps'][$stepNum]['target'];
            $tCodeName = $configUiMasterModulOrigJenis['steps'][$stepNum]['label'];
            $masterReplacers = array(
                //            "referensi_id" => $masterID, (dimatikan)
                //            "id_master"       => $masterID,
                //            "id_top"          => $topID,
                "inv" => $tmpNomorNota,
                //            "jenis_top"           => $tCode,
                "jenis" => $tCode,
                "jenis_label" => $tCodeName,
                "transaksi_jenis" => $tCode,
                "cabang_id" => selectedTransactionSession() ? $_SESSION[$cCode]['main']['cabangID'] : $this->session->login['cabang_id'],
                "cabang_nama" => selectedTransactionSession() ? $_SESSION[$cCode]['main']['cabangName'] : $this->session->login['cabang_nama'],
                "oleh_id" => $this->session->login['id'],
                "oleh_nama" => $this->session->login['nama'],
                "step_current" => "0",
                "step_number" => $stepNum,
                //            "next_step_code"      => "",
                //            "next_step_label"     => "",
                //            "next_group_code"     => "",
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                //===references
                "id_master" => $masterID,
                "id_top" => $topID,
                "ids_prev" => base64_encode(serialize($prevIDs)),
                "ids_prev_intext" => print_r($prevIDs, true),
                "nomer_top2" => isset($_SESSION[$cCode]['main']['nomer_top2']) ? $_SESSION[$cCode]['main']['nomer_top2'] : "",
                "nomer_top" => $_SESSION[$cCode]['tableIn_master']['nomer_top'],
                "nomers_prev" => base64_encode(serialize($prevNos)),
                "nomers_prev_intext" => print_r($prevNos, true),
                //            "jenis_top"           => $this->jenisTr,
                "jenises_prev" => base64_encode(serialize(array($prevProp['jenis']))),
                "jenises_prev_intext" => print_r(array($prevProp['jenis']), true),
                "tail_number" => $stepNum,
                "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
            );

            foreach ($masterReplacers as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }

            $childTableRepaclers = array(
                "sub_step_number" => $stepNum,
                "sub_step_current" => $stepNum,
                "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
            );
            foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                //			$id = $dSpec['id'];
                foreach ($childTableRepaclers as $key => $val) {
                    $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }


            $masterReplacersO = array(

                "jenisTr" => $tCode,
                "jenisTrName" => $tCodeName,
                "olehID" => $this->session->login['id'],
                "olehName" => $this->session->login['nama'],
                "stepNumber" => $stepNum,
                "stepCode" => $tCode,
            );
            foreach ($masterReplacersO as $key => $val) {
                $_SESSION[$cCode]['main'][$key] = $val;
            }

            //region menimbulkan nilai tagihan
            $unpaidList = null != $this->config->item('tr_unpaidList') ? $this->config->item('tr_unpaidList') : array();
            //        arrprint($_SESSION[$cCode]['tableIn_master']);
            if (in_array($tCode, $unpaidList)) {
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_tagihan"] = $_SESSION[$cCode]['tableIn_master']['transaksi_nilai'];
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_terbayar"] = 0;
                $_SESSION[$cCode]['tableIn_master']["transaksi_nilai_sisa"] = ($_SESSION[$cCode]['tableIn_master']['transaksi_nilai_tagihan'] - $_SESSION[$cCode]['tableIn_master']['transaksi_nilai_terbayar']);
                //cekMerah("NULIS TAGIHANN");
            }
            else {
                //cekMerah("TIDAK NULIS TAGIHANN");
            }
            //endregion


            //region penomoran receipt #1

            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($configCoreMasterModulOrigJenis['formatNota']);
            if (!in_array($counterForNumber[0], $configCoreMasterModulOrigJenis['counters'])) {
                die(__LINE__ . " Used number should be registered in 'counters' config as well");
            }

            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }
            $tmpNomorNota2_current = $tmpNomorNota2 = $paramSpec['paramString'];
            $tmpNomorNota2Alias_current = $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);

            //endregion

            //region dynamic counters #1
            echo "<script>top.writeProgress('sedang membuat penomoran');</script>";
            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $configCoreMasterModulOrigJenis['counters'];
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


            $masterReplacers = array(
                "nomer" => $tmpNomorNota2,
                "nomer2" => $tmpNomorNota2Alias,
                "counters" => $appliedCounters2,
                "counters_intext" => $appliedCounters_inText2,
            );
            foreach ($masterReplacers as $key => $val) {
                $_SESSION[$cCode]['tableIn_master'][$key] = $val;
            }

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
            //==tulis kloningan transaksi

            //region write entries
            if (sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {

                // region locker transaksi---------------------------------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if ($this->session->login['ghost'] == 0) {
                        //                $followUpValidator = isset($configUiMasterModulOrigJenis['followUpValidator'][$stepNum]) ? $configUiMasterModulOrigJenis['followUpValidator'][$stepNum] : false;
                        //                if ($followUpValidator == true) {

                        $this->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->addFilter("transaksi_id='$no'");
                        $lt->addFilter("state='hold'");
                        $lt->addFilter("jumlah='1'");
                        $lt->addFilter("oleh_id=" . my_id());
                        $ltTmp = $lt->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($ltTmp) == 1) {
                            cekHijau(":: lanjuut eksekusi transaksi ini....");
                        }
                        else {
                            $msg = "Transaksi sudah dieksekusi atau ada indikasi transaksi ganda. Silahkan tutup halaman ini dan refresh ulang.";
                            cekMerah($msg);
                            die(lgShowAlertBiru($msg));
                        }

                        //                }
                    }
                }
                // endregion locker transaksi---------------------------------

                $_SESSION[$cCode]['tableIn_master']['status_4'] = 11;
                $_SESSION[$cCode]['tableIn_master']['trash_4'] = 0;
                $_SESSION[$cCode]['main']['status_4'] = 1;
                $_SESSION[$cCode]['main']['trash_4'] = 0;


                $insertTransaksiID = $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                $midmaster = $insertID;
                cekBiru("master invoice " . $insertID);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $_SESSION[$cCode]['tableIn_master']);
                $mongoList['main'] = array($insertID, $epID);
                $insertNum = $_SESSION[$cCode]['tableIn_master']['nomer'];
                $mNumMaster = $insertNum;
                $mJenisMaster = $_SESSION[$cCode]['tableIn_master']['jenis'];
                $_SESSION[$cCode]['main']['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }


                if (isset($_SESSION[$cCode]['tableIn_master']['ids_his'])) {
                    $idHis_decode = blobDecode($_SESSION[$cCode]['tableIn_master']['ids_his']);
                    $idHis_decode[$stepNum] = array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $_SESSION[$cCode]['main']['olehID'],
                        "olehName" => $_SESSION[$cCode]['main']['olehName'],
                        "step" => $stepNum,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    $idHis_blob = blobEncode($idHis_decode);
                    $idHis_intext = print_r($idHis_decode, true);

                    $_SESSION[$cCode]['tableIn_master']['ids_his'] = $idHis_blob;
                    $_SESSION[$cCode]['tableIn_master']['ids_his_intext'] = $idHis_intext;


                    $tr = new MdlTransaksi();
                    $dup = $tr->updateData(array("id" => $insertID), array(
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or die("Failed to update tr next-state!");
                    cekUngu($this->db->last_query());
                }


                cekUngu(":: insertID => $insertID ::");
                if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                    $inserMainValues = array();
                    $mongoList['mainValues'] = array();
                    foreach ($_SESSION[$cCode]['tableIn_master_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
                if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                    foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $mongoList['mainValues'][] = $dd;
                    }
                }
                if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                    //                cekMerah("ada mainElements $cCode");
                    //                arrprint($_SESSION[$cCode]['main_elements']);die();
                    foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => $aSpec['label'],
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? print_r($aSpec['contents_intext'], true) : "",

                        ));
                    }
                }
                else {
                    //                cekMerah("TAK ada mainElements");
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : 0,
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
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
                            foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
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
                if (isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) && sizeof($_SESSION[$cCode]['tableIn_detail_rsltItems']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_rsltItems'] as $pID => $dSpec) {
                        if (isset($configCoreMasterModulJenis['tableIn']['detail_rsltItems'])) {
                            foreach ($configCoreMasterModulJenis['tableIn']['detail_rsltItems'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $_SESSION[$cCode]['tableIn_detail_rsltItems'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }


                    }
                }

                //region update validQty pada step sebelumnya yang di-refer
                echo "<script>top.writeProgress('EXTRACT ITEMS...','head');</script>";
                $seluruhnya = true;
                $prevTrID = 0;
                $arrvalidQtySisa = array();
                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                    $closedRequest = isset($configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled']) ? $configCoreMasterModulOrigJenis['closedRequest'][$stepNum]['enabled'] : false;
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $iID => $dSpec) {
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
                                $mongoList['detail'][] = $insertDetailID;
                            }
                        }

                        cekHitam("EXTRACTED ITEMS... [$iID]");
                        echo "<script>top.writeProgress('" . strtoupper($dSpec['produk_nama']) . "');</script>";


                        if (isset($_SESSION[$cCode]['extractedItems'])) {
                            if (array_key_exists($iID, $_SESSION[$cCode]['extractedItems'])) {
                                $itemFulfilledJml = 0;
                                foreach ($_SESSION[$cCode]['extractedItems'][$iID] as $triID => $triSpec) {
                                    $prevTrID = $triSpec['transaksi_id'];
                                    $tru = new MdlTransaksi();
                                    $tru->setFilters(array());
                                    $tru->setTableName($tru->getTableNames()['detail']);
                                    //----------------------------------------------------------
                                    if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                        $newValidQty = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    cekmerah("validQty dikurangi oleh produk_ord_jml, yaitu " . $dSpec['produk_ord_jml']);
                                    }
                                    else {
                                        $newValidQty = ($triSpec['valid_qty'] - $triSpec['valid_qty']);
                                        //                                    cekmerah("validQty dikurangi oleh triSpec,  myaitu " . $triSpec['valid_qty']);
                                    }
                                    //----------------------------------------------------------
                                    $newValidQtyNotApprove = 0;
                                    if ($closedRequest == true) {
                                        cekPink2("closed Request enabled, request: " . $triSpec['valid_qty'] . ", approve: " . $dSpec['produk_ord_jml'] . ", newValidQty: " . $newValidQty);
                                        if ($triSpec['valid_qty'] >= $dSpec['produk_ord_jml']) {
                                            $newValidQty = 0;
                                            $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);

                                        }
                                        //                                    else{
                                        //                                        $newValidQty = 0;
                                        //                                        $newValidQtyNotApprove = ($triSpec['valid_qty'] - $dSpec['produk_ord_jml']);
                                        //                                    }
                                        cekPink2("new valid qty: $newValidQty, valid qty not approve: $newValidQtyNotApprove");
                                    }
                                    //----------------------------------------------------------


                                    $itemFulfilledJml += $newValidQty;
                                    $updateContents = array(
                                        "valid_qty" => $newValidQty,
                                        "valid_qty_no_approve" => $newValidQtyNotApprove,
                                    );
                                    if ($newValidQty < 1) {
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                    }
                                    else {//==kalau ada yang tidak habis, berarti TIDAK seluruhnya yang dilanjutkan pada step berikutnya
                                        $seluruhnya = false;
                                        $arrvalidQtySisa[$iID] = $newValidQty;
                                    }
                                    $dupState = $tru->updateData(array(
                                        "produk_id" => $iID,
                                        "id" => $triID,
                                        "transaksi_id" => $triSpec['transaksi_id'],
                                    ), $updateContents) or die("Failed to update previous detail entries!");
                                    cekHijau($this->db->last_query());

                                    $mongUpdateList['update']['detail'][] = array(
                                        "where" => array(
                                            //                                        "transaksi_id" => $triSpec['transaksi_id'],
                                            "id" => "$triID",
                                            //                                        "produk_id" => $iID,
                                        ),
                                        "value" => $updateContents,
                                    );
                                    unset($tru);
                                }
                            }
                            //                        else{
                            //                            if($closedRequest == true){
                            //
                            //                            }
                            //                        }
                        }
                    }

                    if ($closedRequest == true) {
                        if (isset($_SESSION[$cCode]['extractedItems'])) {
                            foreach ($_SESSION[$cCode]['extractedItems'] as $iIDex => $exSpec) {
                                if (!array_key_exists($iIDex, $_SESSION[$cCode]['tableIn_detail'])) {
                                    foreach ($exSpec as $trDataID => $trdSpec) {
                                        $tru = new MdlTransaksi();
                                        $tru->setFilters(array());
                                        $tru->setTableName($tru->getTableNames()['detail']);
                                        $updateContents = array(
                                            "valid_qty" => 0,
                                            "valid_qty_no_approve" => $trdSpec['qty'],
                                        );
                                        $childPrevRepaclers = array(
                                            "next_substep_code" => "",
                                            "next_substep_label" => "",
                                            "next_subgroup_code" => "",
                                            "sub_tail_number" => $stepNum,
                                            "sub_tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                                        );
                                        foreach ($childPrevRepaclers as $key => $val) {
                                            $updateContents[$key] = $val;
                                        }
                                        $dupState = $tru->updateData(array(
                                            "produk_id" => $iIDex,
                                            "id" => $trDataID,
                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                        ), $updateContents) or die("Failed to update previous detail entries!");
                                        //                                    cekHijau($this->db->last_query());
                                        $mongUpdateList['update']['detail'][] = array(
                                            "where" => array(
                                                //                                            "transaksi_id" => $trdSpec['transaksi_id'],
                                                "id" => "$trDataID",
                                                //                                            "produk_id" => $iIDex,
                                            ),
                                            "value" => $updateContents,
                                        );
                                        unset($tru);
                                    }
                                }
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
                            arrPrint($arrID);
                            $arrBlob = blobEncode($arrID);
                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->db->last_query());
                        }
                    }

                    //-------------
                    $lastStepPartialApprove = isset($configUiMasterModulJenis['lastStepPartialApprove']) ? $configUiMasterModulJenis['lastStepPartialApprove'] : false;
                    if ($lastStepPartialApprove == true) {
                        cekKuning(__LINE__ . " $lastStepPartialApprove :: $totalSteps");
                        if ($totalSteps == 2) {
                            if (sizeof($arrvalidQtySisa) > 0) {
                                cekPink("ada valid qty yang tersisa");
                                $tr = new MdlTransaksi();
                                $dupState = $tr->updateData(array("id" => $topID), $stepNowParameter) or die("Failed to update tr next-state!");
                                cekHitam(__LINE__ . " ## 2 step, dan step akhir partial, YESS...");
                                showLast_query("orange");
                            }
                        }
                    }
                }
                else {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }

                if ($seluruhnya) {
                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $prevTrID), array(
                        "tail_number" => $stepNum,
                        "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                        "status_4" => $_SESSION[$cCode]['main']['status_4'],
                        "trash_4" => $_SESSION[$cCode]['main']['trash_4'],
                    )) or die("Failed to update tr next-state!");
                    cekHijau(":: UOPDATE transaksi dengan trID -> $prevTrID");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array(
                            "id" => "$prevTrID",
                        ),
                        "value" => array(
                            "tail_number" => $stepNum,
                            "tail_code" => $configUiMasterModulJenis['steps'][$stepNum]['target'],
                            "status_4" => $_SESSION[$cCode]['main']['status_4'],
                            "trash_4" => $_SESSION[$cCode]['main']['trash_4'],
                        ),
                    );
                    cekHijau($this->db->last_query());
                }
                //endregion

                //region cloner items to item_child
                if (sizeof($additionalData) > 0) {
                    echo "<script>top.writeProgress('CLONING ITEMS TO ITEM CHILD...','head');</script>";
                    cekHitam("ini data");
                    $dataMdl = $additionalData["mdlName"];
                    $this->load->model("Mdls/" . $dataMdl);
                    $da = new $dataMdl();
                    $arrColl = $da->getFields();
                    $selectedCol = array();
                    foreach ($arrColl as $colSpec) {
                        $selectedCol[] = $colSpec['kolom'];
                    }

                    if (isset($_SESSION[$cCode]['items_child']) && sizeof($_SESSION[$cCode]['items_child'])) {
                        $gateData = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate']) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['gate'] : "detail";

                        $arrBlacklist = array(
                            "jml", "max_jml", "qty",
                        );
                        if (isset($_SESSION[$cCode]["items2_sum"])) {
                            unset($_SESSION[$cCode]["items2_sum"]);
                            unset($_SESSION[$cCode]["items2"]);
                            unset($_SESSION[$cCode]["tableIn_detail_values2_sum"]);
                        }
                        foreach ($_SESSION[$cCode]['items_child'] as $mainProdsID => $defData) {
                            if ($gateData == "detail") {
                                $itemsMain = isset($_SESSION[$cCode]['items'][$mainProdsID]) ? $_SESSION[$cCode]['items'][$mainProdsID] : array();
                            }
                            else {
                                $forceMainToItems = isset($configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData]) ? $configUiMasterModulJenis['shopingCartDetailFields'][$stepNum]['changeToItems'][$gateData] : array();
                                if (sizeof($forceMainToItems) > 0) {
                                    foreach ($forceMainToItems as $key1 => $key2) {
                                        $keyForce = strlen($key2) > 2 ? $key2 : $key1;
                                        $itemsMain[$key1] = isset($_SESSION[$cCode]['main'][$keyForce]) ? $_SESSION[$cCode]['main'][$keyForce] : "";
                                    }
                                    $itemsMain["jml"] = "1";
                                    $itemsMain["qty"] = "1";
                                    $itemsMain["max_jml"] = "1";

                                }
                                else {
                                    matiHEre("detil aset gagal di tulis!");
                                }
                                //                            arrPrint($forceMainToItems);
                            }

                            $arrChilds = array_diff_key($itemsMain, array_flip($arrBlacklist));
                            //                        arrPrint($itemsMain);
                            //                        matiHEre();
                            //
                            //arrPrint($arrChilds);
                            cekLime("ini brooo " . $gateData);

                            $arrNew = array();
                            if (sizeof($itemsMain) > 0) {
                                foreach ($defData as $inID => $detil_child) {
                                    //                        $arrNewChild = array_diff($itemsMain,$detil_child);

                                    $paramDetil = array_replace($arrChilds, $detil_child);
                                    if (array_key_exists("id", $paramDetil)) {

                                        $paramDetil["parent_id"] = $paramDetil["id"];
                                        if (!isset($paramDetil["folders"]) || $paramDetil["folders"] == 0) {
                                            $paramDetil["folders"] = $paramDetil["pihakMainId"];
                                            $paramDetil["keterangan"] = $paramDetil["pihakMainName"];
                                        }
                                        unset($paramDetil["id"]);
                                    }
                                    $tmpData = array();
                                    foreach ($selectedCol as $i => $coloum) {
                                        if (isset($paramDetil[$coloum])) {
                                            $tmpData[$coloum] = $paramDetil[$coloum];
                                        }
                                    }
                                    //                                arrPrint($paramDetil);
                                    if (isset($paramDetil["subtotal"])) {
                                        $paramDetil["subtotal"] = $paramDetil["jml"] * $paramDetil["harga"];
                                    }

                                    $insertDataID = $da->addData($tmpData, $da->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                                    cekHere($this->db->last_query());
                                    $paramDetil["id"] = $insertDataID;
                                    echo "<script>top.writeProgress('PENGAJUAN DATA (TRID:$insertDataID)');</script>";
                                    $_SESSION[$cCode]["items2_sum"][$insertDataID] = $paramDetil;
                                    $_SESSION[$cCode]["items2"][$mainProdsID][$insertDataID] = $paramDetil;
                                    //                            $arrNew

                                }
                            }


                            //                        arrPrint($arrNew);
                            //


                            //                  arrPrint($itemsMain);
                        }

                    }
                }

                //endregion

                if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                    }
                }
                if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2'] as $iID => $dSpec) {
                        $dd = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                        if ($epID != 999) {
                            $dd = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $dd;
                            $mongoList['detail'][] = $dd;
                        }
                        cekUngu($this->db->last_query());
                    }
                }


                if (isset($configUiMasterModulJenis['updateDueDate'][$stepNum])) {
                    $dueDateConf = $configUiMasterModulJenis['updateDueDate'][$stepNum];
                    $sourceDue = $dueDateConf['source'];
                    $targetDue = $dueDateConf['target'];
                    $datenow = date("Y-m-d");
                    foreach ($sourceDue as $key => $val) {
                        $indexVal = isset($_SESSION[$cCode]['main_elements'][$key][$val]) ? $_SESSION[$cCode]['main_elements'][$key][$val] : 14;
                        $dueDate = dueDate($datenow, $indexVal);
                    }
                    $fieldDue = $tr->getFields()["dueDate"];
                    $dataDue = array();
                    foreach ($fieldDue as $kol) {
                        if (isset($_SESSION[$cCode]['tableIn_master'][$kol])) {
                            $dataDue[$kol] = $_SESSION[$cCode]['tableIn_master'][$kol];
                        }
                    }
                    $dataDue['due_date'] = $dueDate;
                    $validateDue = validateDueDate($_SESSION[$cCode]['main']['customerID'], $_SESSION[$cCode]['main']['dtime']);

                    arrPrint($validateDue);
                    if ($validateDue['allow_create'] == "true") {
                        if (isset($_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']) && $_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen'] > 0) {
                            cekBiru($_SESSION[$cCode]['main']['nilai_tambah_hutang_ke_konsumen']);

                            $tr->writeDueDate($insertID, $dataDue);
                        }
                    }
                    else {
                        $allowedOver = validateOverDue($_SESSION[$cCode]['main']['customerID']);
                        if ($allowedOver['status'] == "allowed") {

                        }
                        else {
                            //                        matiHere($validateDue['error']);//matiin transaksi sudah over due
                        }
                        //                    arrPrint()
                        //                    matiHere($validateDue['error']);//matiin transaksi sudah over due
                    }
                    //                matiHere();
                    //update main elementnya
                    foreach ($targetDue as $keyTarget => $valTarget) {
                        $_SESSION[$cCode]['main_elements'][$keyTarget][$valTarget] = $dueDate;
                        $_SESSION[$cCode]['main']['dueDate'] = $dueDate;
                    }
                }
                arrPrintPink($_SESSION[$cCode]['tableIn_master']);

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
                    "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields'][$stepNum] : array(),
                    "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields'][$stepNum] : array(),
                    "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][$stepNum] : array(),
                    "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][$stepNum]) ? $configLayoutMasterModulJenis['receiptSumFields2'][$stepNum] : array(),
                    "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][$stepNum] : array(),
                    "jurnal_index" => isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) ? $configCoreMasterModulJenis['components'][$jenisTrTarget] : array(),
                    "preProcessor" => isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget] : array(),
                    "postProcessor" => isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget] : array(),
                    "revert" => isset($_SESSION[$cCode]['revert']) ? $_SESSION[$cCode]['revert'] : array(),
                    "items_komposisi" => isset($_SESSION[$cCode]['items_komposisi']) ? $_SESSION[$cCode]['items_komposisi'] : array(),
                    "componentsBuilder" => isset($_SESSION[$cCode]['componentsBuilder']) ? $_SESSION[$cCode]['componentsBuilder'] : array(),
                    "jurnalItems" => isset($_SESSION[$cCode]['jurnalItems']) ? $_SESSION[$cCode]['jurnalItems'] : array(),

                );
                $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                $mongRegID = $doWriteReg;
                echo "<script>top.writeProgress('MENULIS KE-REGISTRY....');</script>";
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan, silahkan cek kembali transaksi ini."));
            }
            //endregion
            //mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

            //region processing sub-post-processors, always
            //<editor-fold desc="----------sub postProc">
            // matiHEre();
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, initializing values <br>";
                    echo "<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>";

                    $tmpOutParams[$cCtr] = array();
                    foreach ($_SESSION[$cCode][$srcGateName] as $cnt => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cnt], $_SESSION[$cCode][$srcGateName][$cnt], 0);
                                $subParams['static'][$key] = $realValue;
                                cekBiru("$key diisi dengan $realValue");

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
                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                        echo "<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>";
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "sub-postProcessor: $comName, sending values <br>";
                    echo "<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>";
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekBiru($this->db->last_query());
                }
            }

            //endregion

            //region processing main-post-processors, always
            //<editor-fold desc="----------postProc">

            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('MEMPROSES MAIN-PROCESSORS...', 'head');</script>";
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "post-processor: $comName<br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;

                        }
                    }
                    if (isset($tComSpec['static'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;

                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                                }
                            }
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                echo "<script>top.writeProgress(':: $key diisikan dengan " . $tmpOutParams['static'][$k] . ");</script>";
                            }
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$cCtr], $_SESSION[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

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
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //                cekBiru("kiriman komponem $comName");
                    //                                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);


                }
            }


            //</editor-fold>
            //endregion

            //region ----------subcomponents GESER KE CLI

            //        $componentGate['detail'] = array();
            //        //arrPrint($paramForceFillers);
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
            $componentConfig['detail'] = $iterator;
            //        if (sizeof($iterator) > 0) {
            //            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            //            $filterNeeded = false;
            //            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                $filterNeeded = true;
            //            }
            //            foreach ($iterator as $cCtr => $tComSpec) {
            ////                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub-component: $comName, $srcGateName, initializing values <br>";
            //                $tmpOutParams[$cCtr] = array();
            //                foreach ($_SESSION[$cCode][$srcGateName] as $id => $dSpec) {
            //                    cekmerah("mengevaluasi $srcGateName..");
            //                    $comName = $tComSpec['comName'];
            //                    if (substr($comName, 0, 1) == "{") {
            //                        $comName = trim($comName, "{");
            //                        $comName = trim($comName, "}");
            //                        $comName = str_replace($comName, $_SESSION[$cCode][$srcGateName][$id][$comName], $comName);
            //                        $tComSpec['comName'] = $comName;
            //                        $iterator[$cCtr]['comName'] = $comName;
            //                    }
            //
            //                    $filterNeeded = false;
            //                    $mdlName = "Com" . ucfirst($comName);
            //                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            //                        $filterNeeded = true;
            //                    }
            //
            //
            //                    $subParams = array();
            //                    if (isset($tComSpec['loop'])) {
            //                        foreach ($tComSpec['loop'] as $key => $value) {
            //                            if (substr($key, 0, 1) == "{") {
            //                                $key = trim($key, "{");
            //                                $key = trim($key, "}");
            //                                $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$id][$key], $key);
            //                            }
            //                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
            //                            $subParams['loop'][$key] = $realValue;
            //                            cekKuning("LOOP: $key diisi dengan $realValue");
            //
            //                            if ($filterNeeded) {
            //                                if ($subParams['loop'][$key] == 0) {
            //                                    unset($subParams['loop'][$key]);
            //                                }
            //                            }
            //                        }
            //                    }
            //                    if (isset($tComSpec['static'])) {
            //                        foreach ($tComSpec['static'] as $key => $value) {
            //
            //                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
            //                            $subParams['static'][$key] = $realValue;
            //                            cekKuning("STATIC: $key diisi dengan $realValue");
            //
            //                        }
            //                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
            //                            foreach ($paramPatchers[$comName] as $k => $v) {
            //                                if (!isset($subParams['static'][$k])) {
            //                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                    cekOrange("fill :: $comName :: $k => " . $subParams['static'][$k]);
            //                                }
            //                            }
            //                        }
            //                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
            //                            //                            cekOrange("comName:: $comName");
            //                            $jenis = $_SESSION[$cCode]['main']['jenis'];
            //                            foreach ($paramForceFillers[$comName] as $k => $v) {
            //                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
            //                                cekOrange("fillforce :: $comName :: $k => " . $subParams['static'][$k]);
            //                            }
            //                        }
            //                        $subParams['static']["fulldate"] = date("Y-m-d");
            //                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
            //                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];
            //                    }
            //                    cekHitam("cetak subParams");
            //                    arrPrint($subParams);
            //                    if (sizeof($subParams) > 0) {
            //                        if ($filterNeeded) {
            //                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
            //                                $tmpOutParams[$cCtr][] = $subParams;
            //                            }
            //                        }
            //                        else {
            //
            //                            $tmpOutParams[$cCtr][] = $subParams;
            //                        }
            //                    }
            //                }
            //
            //                $componentGate['detail'][$cCtr] = $subParams;
            //            }
            //
            //
            //            $it = 0;
            //            foreach ($iterator as $cCtr => $tComSpec) {
            //                $it++;
            //
            //
            //                $comName = $tComSpec['comName'];
            //                $srcGateName = $tComSpec['srcGateName'];
            //                $srcRawGateName = $tComSpec['srcRawGateName'];
            //
            //                echo "sub component #$it: $comName, sending values <br>";
            //
            //                $mdlName = "Com" . ucfirst($comName);
            //                $this->load->model("Coms/" . $mdlName);
            //                $m = new $mdlName();
            //
            //
            //                if (sizeof($tmpOutParams[$cCtr]) > 0) {
            //                    $tobeExecuted = true;
            //                }
            //                else {
            //                    $tobeExecuted = false;
            //                }
            //
            //
            //                if ($tobeExecuted) {
            //                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                }
            //                else {
            //                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
            //                }
            //            }
            //        }
            //        else {
            //            //cekKuning("subcomponents is not set");
            //        }

            //endregion

            //region ----------components
            //<editor-fold desc="----------components">
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
                $iterator = isset($_SESSION[$cCode]['revert']['jurnal'][$stepNum]['master']) ? $_SESSION[$cCode]['revert']['jurnal'][$stepNum]['master'] : array();
            }
            else {
                if (isset($_SESSION[$cCode]['componentsBuilder'][$stepNum]['master'])) {
                    $iterator = $_SESSION[$cCode]['componentsBuilder'][$stepNum]['master'];
                }
                elseif (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master'])) {
                    $iterator = $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'];
                }
                else {
                    $iterator = array();
                }
            }


            if (sizeof($iterator) > 0) {
                echo "<script>top.writeProgress('KOMPONEN...', 'head');</script>";
                $componentConfig['master'] = $iterator;

                $it = 0;
                //==filter nilai, jika NOL tidak dikirim, sesuai config==
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    //                cekPink($tComSpec);
                    //                mati_disini();
                    $it++;
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    echo "component #$it: $comName :: $srcGateName <br>";

                    $dSpec = $_SESSION[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                //                            $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
                                $key = str_replace($key, $_SESSION[$cCode][$srcGateName][$key], $key);
                            }
                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            if ($key != null) {
                                $tmpOutParams['loop'][$key] = $realValue;
                            }

                        }
                    }
                    //                cekBiru($tmpOutParams);
                    //                mati_disini(__LINE__);
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                            cekHijau(":: NORMAL :: $key => $realValue ::");
                        }
                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                            cekHijau(":: masuk ke PATCHER ::");
                            foreach ($paramPatchers[$comName] as $k => $v) {
                                cekHijau(":: ada yang mau di-PATCHER ::");
                                arrPrint($tmpOutParams['static']);
                                if (!isset($tmpOutParams['static'][$k])) {
                                    $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekHijau(":: PATCHER :: $key => $realValue ::");
                                }

                            }
                        }
                        else {
                            cekMerah(":: TIDAK TERMASUK PATCHER ::");
                        }
                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                            $jenis = $_SESSION[$cCode]['main']['jenis'];
                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                $tmpOutParams['static'][$k] = isset($$v) ? $$v : "_v";
                                cekHijau(":: FORCEFILL :: $key => $realValue ::");
                            }
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {

                            $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName], $_SESSION[$cCode][$srcGateName], 0);
                            $tmpOutParams['static2'][$key] = $realValue;

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
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $this->session->login['nama'];


                    }

                    //lgShowError("Ada kesalahan",);
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
                        cekBiru("kiriman komponen $comName");
                        arrPrint($tmpOutParams);
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    else {
                        cekBiru("komponem $comName tidak memenuhi syarat untuk ditulis");
                    }

                    $componentGate['master'][$cCtr] = $tmpOutParams;
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion

            //region nulis paymentSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {
                $payConfigs = isset($paymentSources[$stepCode][$stepNum]) ? $paymentSources[$stepCode][$stepNum] : array();
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $valueAdd = isset($_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']]) ? $_SESSION[$cCode]['main'][$paymentSrcConfig['addValueValidator']] : 0;
                        if (isset($paymentSrcConfig['model'])) {
                            $mdlName = $paymentSrcConfig['model'];
                            $this->load->model("Mdls/$mdlName");
                            $pMdl = New $mdlName();
                            $pTmpMdl = $pMdl->lookupAll()->result();
                            $pTmpMdlResult = array();
                            if (sizeof($pTmpMdl) > 0) {
                                foreach ($pTmpMdl as $pTmpMdlSpec) {
                                    $pTmpMdlResult[$pTmpMdlSpec->id] = $pTmpMdlSpec;
                                }
                            }
                        }
                        else {
                            $pTmpMdlResult = array();
                        }

                        if (isset($_SESSION[$cCode]['main'][$valueSrc]) && $_SESSION[$cCode]['main'][$valueSrc] > 0) {
                            if (isset($externSrc['extern_label2'])) {
                                //cek ada isinya atau kosong
                                $cek = strlen($_SESSION[$cCode]['main'][$externSrc['extern_label2']]) > 4 ? "" : matiHere("jenis biaya tidak dikenali " . __LINE__);//
                            }
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

                            //-----------------------
                            cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                            $this->load->helper("he_payment_source");
                            //                        paymentSource($this->jenisTr, $componentJurnal, $_SESSION[$cCode]['main'], $valueLabel, $valueSrc, $valueAdd);
                            //-----------------------

                            $arrPymSrc = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => isset($_SESSION[$cCode]['main'][$externSrc['id']]) ? $_SESSION[$cCode]['main'][$externSrc['id']] : "",
                                "extern_nama" => isset($_SESSION[$cCode]['main'][$externSrc['nama']]) ? $_SESSION[$cCode]['main'][$externSrc['nama']] : "",
                                "nomer" => $tmpNomorNota2,
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
                                "valas_id" => isset($externSrc['valasId']) && isset($_SESSION[$cCode]['main'][$externSrc['valasId']]) ? $_SESSION[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => isset($externSrc['valasLabel']) && isset($_SESSION[$cCode]['main'][$externSrc['valasLabel']]) ? $_SESSION[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => isset($externSrc['valasValue']) && isset($_SESSION[$cCode]['main'][$externSrc['valasValue']]) ? $_SESSION[$cCode]['main'][$externSrc['valasValue']] : '',

                                "tagihan_valas" => isset($externSrc['valasTagihan']) && isset($_SESSION[$cCode]['main'][$externSrc['valasTagihan']]) ? $_SESSION[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                "terbayar_valas" => 0,
                                "sisa_valas" => isset($externSrc['valasSisa']) && isset($_SESSION[$cCode]['main'][$externSrc['valasSisa']]) ? $_SESSION[$cCode]['main'][$externSrc['valasSisa']] : '',

                                //                            "extern_label2" => isset($_SESSION[$cCode]['main']['pihakMainName']) ? $_SESSION[$cCode]['main']['pihakMainName'] : "",
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_label2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_label2']] : "",

                                "dpp_ppn" => (isset($externSrc['dpp_ppn']) && ($_SESSION[$cCode]['main'][$externSrc['dpp_ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['dpp_ppn']] : 0,
                                "ppn" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : 0,
                                "ppn_approved" => (isset($externSrc['ppn_approved']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_approved']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_approved']] : 0,
                                "ppn_sisa" => (isset($externSrc['ppn']) && ($_SESSION[$cCode]['main'][$externSrc['ppn']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn']] : "",
                                "ppn_status" => (isset($externSrc['ppn_status'])) ? $externSrc['ppn_status'] : 0,
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "extern_date2" => (isset($externSrc['extern_date2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_date2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_date2']] : "",
                                "pph_23" => (isset($externSrc['pph_23']) && ($_SESSION[$cCode]['main'][$externSrc['pph_23']])) ? $_SESSION[$cCode]['main'][$externSrc['pph_23']] : "",

                                "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_id']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_id']] : "",
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($_SESSION[$cCode]['main'][$externSrc['extern2_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['extern2_nama']] : "",
                                "ppn_pph_faktor" => (isset($externSrc['ppn_pph_faktor']) && ($_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']])) ? $_SESSION[$cCode]['main'][$externSrc['ppn_pph_faktor']] : "",
                                "extern_jenis" => (isset($externSrc['extern_jenis']) && ($_SESSION[$cCode]['main'][$externSrc['extern_jenis']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_jenis']] : "",
                                "extern_nilai3" => (isset($externSrc['extern_nilai3']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai3']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai3']] : "",
                                "extern_nilai4" => (isset($externSrc['extern_nilai4']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai4']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai4']] : "",
                                "npwp" => (isset($externSrc['npwp']) && ($_SESSION[$cCode]['main'][$externSrc['npwp']])) ? $_SESSION[$cCode]['main'][$externSrc['npwp']] : "",
                                //                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($_SESSION[$cCode]['main'][$externSrc['extern_nilai2']])) ? $_SESSION[$cCode]['main'][$externSrc['extern_nilai2']] : "",
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($_SESSION[$cCode]['main'][$externSrc['payment_locked']])) ? $_SESSION[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($_SESSION[$cCode]['main'][$externSrc['cash_account_nama']])) ? $_SESSION[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                            );
                            $tr->writePaymentSrc($insertID, $arrPymSrc);

                        }


                        cekMerah($this->db->last_query());
                    }
                }

            }
            else {
                cekMerah("TIDAK nulis paymentSrc");
            }

            $addPaymentSource = isset($configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService']) ? $configUiMasterModulJenis['steps'][$stepNum]['additionalStep']['shippingService'] : array();

            //endregion

            //region nulis paymentAntiSource
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $paymentSources = $this->config->item("payment_antiSource");
            if (array_key_exists($stepCode, $paymentSources)) {
                cekMerah(":: starting PAYMENT ANTI SOURCE");
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
                            "oleh_id" => $this->session->login['id'],
                            "oleh_nama" => $this->session->login['nama'],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                        ));
                        //cekMerah($this->db->last_query());
                    }
                }

            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion

            //region nulis uangMukaSource
            /*dimatiin geser ke ComUangmukaSourceDetail karena ada di items.
            /*revisi tanggal 27 mei 2020 subject digeser ke vendor dari jenis transaksi misal uangmuka asuransi,uang muka pembelian ->uang muka.
             *
             */
            $stepCode = $configUiMasterModulJenis['steps'][$stepNum]['target'];
            $uangMukaSources = $this->config->item("uang_muka");

            if (array_key_exists($stepCode, $uangMukaSources)) {
                cekMerah(":: starting UANG MUKA  SOURCE");
                //            matiHere();
                $uangMukaConfigs = isset($uangMukaSources[$stepCode][$stepNum]) ? $uangMukaSources[$stepCode][$stepNum] : array();
                if (sizeof($uangMukaConfigs) > 0) {
                    $cekPreValue = "";
                    $this->load->model("Mdls/MdlPaymentUangMuka");
                    $l = new MdlPaymentUangMuka();
                    foreach ($uangMukaConfigs as $uangMukaSrcConfig) {
                        //					$paymentSrcConfig = $paymentSources[$stepCode];
                        //                    arrPrint($uangMukaSrcConfig);
                        $valueSrc = $uangMukaSrcConfig['valueSrc'];
                        $externSrc = $uangMukaSrcConfig['externSrc'];
                        $l->addFilter("extern_id='" . $_SESSION[$cCode]['main'][$externSrc['id']] . "'");
                        $l->addFilter("extern_label2='" . $externSrc['extLabel'] . "'");
                        $tmpUm = $l->lookupAll()->result();
                        //                    arrPrint($tmpUm);
                        if (sizeof($tmpUm) > 0) {
                            //update here broo
                            $preTagihan = $tmpUm[0]->tagihan;
                            $preSisa = $tmpUm[0]->sisa;

                            $newTahigan = $preTagihan + $_SESSION[$cCode]['main'][$valueSrc];
                            $newsisa = $preSisa + $_SESSION[$cCode]['main'][$valueSrc];
                            $update = array(
                                "tagihan" => $newTahigan,
                                "sisa" => $newsisa,
                            );
                            $where = array(
                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                            );
                            $tr->updateUangMukaSrc($where, $update);
                            cekHitam($this->db->last_query());
                        }
                        else {
                            //insertbaru brooo
                            $tr->writeUangMukaSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $uangMukaSrcConfig['jenisTarget'],
                                "reference_jenis" => $uangMukaSrcConfig['jenisSrc'],
                                "extern_id" => $_SESSION[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $_SESSION[$cCode]['main'][$externSrc['nama']],
                                "nomer" => "",
                                "note" => "",
                                "label" => $uangMukaSrcConfig['label'],
                                "tagihan" => $_SESSION[$cCode]['main'][$valueSrc],
                                "terbayar" => 0,
                                "sisa" => $_SESSION[$cCode]['main'][$valueSrc],
                                "cabang_id" => $_SESSION[$cCode]['main']['placeID'],
                                "cabang_nama" => $_SESSION[$cCode]['main']['placeName'],
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "extern_label2" => $externSrc['extLabel'],
                            ));
                        }
                        cekMerah($this->db->last_query());
                    }
                }
                else {
                    cekLime("not write uang muka");
                }

            }
            else {
                cekMerah("not write uang muka");
            }
            //endregion


            $returnTransaksi = array(
                "transaksi_id" => $insertTransaksiID,
                "transaksi_nomer" => $tmpNomorNota2_current,
            );
            return $returnTransaksi;
        }
        else {
            $masterID = 0;
            $tmpNomorNota = "XXXX";
            $origJenis = 0;
            $topID = 0;
            die(lgShowAlert("No such receipt ID: $no!"));
        }


    }
}